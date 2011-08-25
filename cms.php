<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

// CTracker_Ignore: File Checked By Human
define('IN_CMS', true);
define('CTRACKER_DISABLED', true);
define('IN_ICYPHOENIX', true);
//define('CMS_NO_AJAX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/class_cms_auth.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_cms_admin.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/class_cms_admin.' . PHP_EXT);

$config['jquery_ui'] = true;

// Start session management
$user->session_begin();
//$auth->acl($user->data);
$user->setup();
// End session management

$js_temp = array('js/cms.js');
$template->js_include = array_merge($template->js_include, $js_temp);
unset($js_temp);

$mode_array = array('blocks', 'config', 'layouts', 'layouts_special', 'smilies', 'block_settings', 'auth');
$action_array = array('add', 'delete', 'edit', 'editglobal', 'list', 'save', 'clone', 'addrole', 'editrole');

$cms_auth = new cms_auth();
$cms_auth->acl();

$cms_admin = new cms_admin();
$cms_admin->root = CMS_PAGE_CMS;
$cms_admin->init_vars($mode_array, $action_array);

$redirect_append = (!empty($cms_admin->mode) ? ('&mode=' . $cms_admin->mode) : '') . (!empty($cms_admin->action) ? ('&action=' . $cms_admin->action) : '') . (!empty($cms_admin->l_id) ? ('&l_id=' . $cms_admin->l_id) : '') . (!empty($cms_admin->b_id) ? ('&b_id=' . $cms_admin->b_id) : '');

if (!$user->data['session_admin'])
{
	redirect(append_sid(CMS_PAGE_LOGIN . '?redirect=' . $cms_admin->root . '&admin=1' . $redirect_append, true));
}

$access_allowed = get_cms_access_auth('cms', $cms_admin->mode, $cms_admin->action, $cms_admin->l_id, $cms_admin->b_id);

if (!$access_allowed)
{
	message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
}

include(IP_ROOT_PATH . 'includes/class_db.' . PHP_EXT);
$class_db = new class_db();

include(IP_ROOT_PATH . 'includes/class_form.' . PHP_EXT);
$class_form = new class_form();

include_once(IP_ROOT_PATH . 'includes/functions_selects.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_post.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);

setup_extra_lang(array('lang_admin', 'lang_cms', 'lang_blocks', 'lang_permissions'));

$cms_type = 'cms_standard';

$preview_block = isset($_POST['preview']) ? true : false;

if ($cms_admin->mode == 'smilies')
{
	generate_smilies('window');
	exit;
}

if(isset($_POST['block_reset']))
{
	if ($cms_admin->ls_id == false)
	{
		redirect(append_sid($cms_admin->root . '?mode=blocks&l_id=' . $cms_admin->l_id, true));
	}
	else
	{
		redirect(append_sid($cms_admin->root . '?mode=blocks&ls_id=' . $cms_admin->ls_id, true));
	}
}

if(isset($_POST['cancel']))
{
	redirect(append_sid($cms_admin->root, true));
}

$show_cms_menu = (($user->data['user_level'] == ADMIN) || ($user->data['user_cms_level'] == CMS_CONTENT_MANAGER)) ? true : false;
$template->assign_vars(array(
	'S_CMS_AUTH' => true,

	// Variabili provvisorie, da integrare permessi anche nel cms standard
	'S_EDIT_SETTINGS' => true,
	'S_L_ADD' => true,
	'S_L_EDIT' => true,
	'S_L_DELETE' => true,
	'S_B_ADD' => true,
	'S_B_EDIT' => true,
	'S_B_DELETE' => true,

	'S_SHOW_CMS_MENU' => $show_cms_menu
	)
);

$cms_admin->s_hidden_fields = '';
$cms_admin->s_append_url = '';
if ($cms_admin->mode)
{
	$cms_admin->s_hidden_fields .= '<input type="hidden" name="mode" value="' . $cms_admin->mode . '" />';
	$cms_admin->s_append_url .= '?mode=' . $cms_admin->mode;
}
if ($cms_admin->action)
{
	$cms_admin->s_hidden_fields .= '<input type="hidden" name="action" value="' . $cms_admin->action . '" />';
	$cms_admin->s_append_url .= '&amp;action=' . $cms_admin->action;
}

if(($cms_admin->mode == 'layouts') || ($cms_admin->l_id !== false))
{
	$cms_admin->id_var_name = 'l_id';
	$cms_admin->id_var_value = $cms_admin->l_id;
	$cms_admin->table_name = $cms_admin->tables['layout_table'];
	$cms_admin->field_name = 'lid';
	$cms_admin->block_layout_field = 'layout';
	$cms_admin->layout_value = $cms_admin->id_var_value;
	$cms_admin->layout_special_value = 0;
	$cms_admin->mode_layout_name = 'layouts';
	$cms_admin->mode_blocks_name = 'blocks';
	$is_layout_special = false;
}
else
{
	$cms_admin->id_var_name = 'ls_id';
	$cms_admin->id_var_value = $cms_admin->ls_id;
	$cms_admin->table_name = $cms_admin->tables['layout_special_table'];
	$cms_admin->field_name = 'lsid';
	$cms_admin->block_layout_field = 'layout_special';
	$cms_admin->layout_value = 0;
	$cms_admin->layout_special_value = $cms_admin->id_var_value;
	$cms_admin->mode_layout_name = 'layouts_special';
	$cms_admin->mode_blocks_name = 'blocks';
	$is_layout_special = true;
}

/* TABS - BEGIN */
$tab_mode = $cms_admin->mode;
if (($cms_admin->mode == 'blocks') && ($cms_admin->action != 'editglobal') && (($cms_admin->l_id != 0) || ($cms_admin->ls_id != 0)))
{
	if (($cms_admin->mode_layout_name == 'layouts') || ($cms_admin->mode_layout_name == 'layouts_special'))
	{
		$tab_mode = $cms_admin->mode_layout_name;
	}
}
$cms_admin->generate_tabs($tab_mode);
/* TABS - END */

if($cms_admin->mode == 'block_settings')
{
	if($cms_admin->bs_id !== false)
	{
		$s_hidden_fields .= '<input type="hidden" name="bs_id" value="' . $cms_admin->bs_id . '" />';
		$cms_admin->s_append_url .= '&amp;bs_id=' . $cms_admin->bs_id;
	}

	$class_db->main_db_table = $cms_admin->tables['block_settings_table'];
	$class_db->main_db_item = 'bs_id';

	$template->assign_var('CMS_PAGE_TITLE', $lang['CMS_BLOCK_SETTINGS_TITLE']);

	if(($cms_admin->action == 'add') || ($cms_admin->action == 'edit'))
	{
		if(isset($_POST['hascontent']))
		{
			$block_content = (isset($_POST['blockfile'])) ? trim($_POST['blockfile']) : false;
			if (empty($block_content))
			{
				$template_to_parse = CMS_TPL . 'cms_blocks_settings_edit_text_body.tpl';
			}
			else
			{
				$template_to_parse = CMS_TPL . 'cms_blocks_settings_edit_body.tpl';
			}
		}
		else
		{
			$template_to_parse = CMS_TPL . 'cms_blocks_settings_content_body.tpl';
		}

		$cms_admin->manage_block_settings();

		if ($preview_block == true)
		{
			$preview_type = (isset($_POST['type'])) ? intval($_POST['type']) : false;
			$message = isset($_POST['message']) ? stripslashes(trim($_POST['message'])) : '';
			show_preview($preview_type, $message);
		}
	}
	elseif($cms_admin->action == 'save')
	{
		$cms_admin->save_block_settings();
	}
	elseif($cms_admin->action == 'delete')
	{
		$cms_admin->delete_block_settings();
	}
	else
	{
		$template_to_parse = CMS_TPL . 'cms_blocks_settings_list_body.tpl';
		$template->assign_var('CMS_PAGE_TITLE', $lang['BLOCKS_TITLE']);

		$template->assign_vars(array(
			'S_BLOCKS_ACTION' => append_sid($cms_admin->root),
			'S_HIDDEN_FIELDS' => $cms_admin->s_hidden_fields
			)
		);

		$result = $cms_admin->show_blocks_settings_list_ajax();
		if(is_array($result))
		{
			// json data
			echo json_encode($result);
			garbage_collection();
			exit_handler();
			exit;
		}
		if(defined('AJAX_CMS'))
		{
			// ajax data present... show new page
			$template_to_parse = CMS_TPL . 'cms_blocks_settings_list_body_ajax.tpl';
		}
	}
}

if($cms_admin->mode == 'blocks')
{
	$class_db->main_db_table = $cms_admin->tables['blocks_table'];
	$class_db->main_db_item = 'bid';

	if($cms_admin->b_id)
	{
		$cms_admin->block_id = $cms_admin->b_id;
	}

	if($cms_admin->id_var_value !== false)
	{
		$cms_admin->s_hidden_fields .= '<input type="hidden" name="' . $cms_admin->id_var_name . '" value="' . $cms_admin->id_var_value . '" />';
		$cms_admin->s_append_url .= '&amp;' . $cms_admin->id_var_name . '=' . $cms_admin->id_var_value;
	}
	else
	{
		$cms_admin->id_var_value = 0;
	}

	if($cms_admin->b_id != false)
	{
		$cms_admin->s_hidden_fields .= '<input type="hidden" name="b_id" value="' . $cms_admin->b_id . '" />';
		$cms_admin->s_append_url .= '&amp;b_id=' . $cms_admin->b_id;
	}
	else
	{
		$cms_admin->b_id = 0;
	}

	if(($cms_admin->action == 'add') || ($cms_admin->action == 'edit'))
	{
		$template_to_parse = CMS_TPL . 'cms_block_content_body.tpl';
		$cms_admin->manage_block();
	}
	elseif($cms_admin->action == 'save')
	{
		$cms_admin->save_block();
	}
	elseif($cms_admin->action == 'delete')
	{
		$cms_admin->delete_block();
	}
	elseif(($cms_admin->id_var_value != 0) || ($cms_admin->action == 'editglobal'))
	{
		if(isset($_POST['action_update']))
		{
			$cms_admin->update_blocks();
		}

		$template_to_parse = CMS_TPL . 'cms_blocks_list_body.tpl';
		$template->assign_var('CMS_PAGE_TITLE', $lang['BLOCKS_TITLE']);

		$move = request_get_var('move', -1);

		if(($cms_admin->mode == 'blocks') && (($move == '0') || ($move == '1')))
		{
			$cms_admin->move_block($move);
		}

		$template->assign_vars(array(
			'S_BLOCKS_ACTION' => append_sid($cms_admin->root),
			'S_HIDDEN_FIELDS' => $cms_admin->s_hidden_fields
			)
		);

		// Old Version...
		/*
		if ($cms_admin->mode_layout_name == 'layouts_special')
		{
			$cms_admin->show_blocks_list();
		}
		else
		{
		*/
			$result = $cms_admin->show_blocks_list_ajax();
			if(is_array($result))
			{
				// json data
				echo json_encode($result);
				garbage_collection();
				exit_handler();
				exit;
			}
			if($result === false)
			{
				// no blocks found: show form to add a block
				$template_to_parse = CMS_TPL . 'cms_block_content_body.tpl';
				$cms_admin->manage_block();
			}
			elseif(defined('AJAX_CMS'))
			{
				// ajax data present. show new page
				$template_to_parse = CMS_TPL . 'cms_blocks_list_body_ajax.tpl';
			}
		/*
		}
		*/
	}
	else
	{
		message_die(GENERAL_MESSAGE, $lang['No_layout_selected']);
	}
}

if (($cms_admin->mode == 'layouts_special') || ($cms_admin->mode == 'layouts'))
{
	$class_db->main_db_table = $cms_admin->table_name;
	$class_db->main_db_item = $cms_admin->field_name;

	if($cms_admin->id_var_value != false)
	{
		$cms_admin->s_hidden_fields .= '<input type="hidden" name="' . $cms_admin->id_var_name . '" value="' . $cms_admin->id_var_value . '" />';
		$cms_admin->s_append_url .= '&amp;' . $cms_admin->id_var_name . '=' . $cms_admin->id_var_value;
	}
	else
	{
		$cms_admin->id_var_value = 0;
	}

	if(($cms_admin->action == 'edit') || ($cms_admin->action == 'add'))
	{
		$template_to_parse = CMS_TPL . 'cms_layout_edit_body.tpl';
		$template->assign_var('CMS_PAGE_TITLE', $lang['CMS_PAGES']);

		$l_info = array();
		if(($cms_admin->action == 'edit') && empty($cms_admin->id_var_value))
		{
			message_die(GENERAL_MESSAGE, $lang['No_layout_selected']);
		}
		$cms_admin->manage_layout($is_layout_special);
	}
	elseif($cms_admin->action == 'save')
	{
		$cms_admin->save_layout($is_layout_special);
	}
	elseif($cms_admin->action == 'delete')
	{
		$cms_admin->delete_layout();
	}
	elseif(($cms_admin->action == 'clone') && !$is_layout_special)
	{
		$cms_admin->clone_layout();
	}
	elseif (($cms_admin->action == 'list') || ($cms_admin->action == false))
	{
		if(isset($_POST['action_update']))
		{
			$cms_admin->update_layout();
		}

		if(isset($_GET['changes_saved']))
		{
			$template->assign_var('CMS_CHANGES_SAVED', true);
		}

		$template_to_parse = CMS_TPL . 'cms_layout_list_body.tpl';
		$template->assign_var('CMS_PAGE_TITLE', $lang['CMS_PAGES']);

		$template->assign_vars(array(
			'L_LAYOUT_TITLE' => $is_layout_special ? $lang['CMS_STANDARD_PAGES'] : $lang['CMS_CUSTOM_PAGES'],
			'L_LAYOUT_TEXT' => $is_layout_special ? $lang['Layout_Special_Explain'] : $lang['Layout_Explain'],
			'S_LAYOUT_SPECIAL' => $is_layout_special,
			'S_LAYOUT_ACTION' => append_sid($cms_admin->root),
			'S_HIDDEN_FIELDS' => $cms_admin->s_hidden_fields
			)
		);

		$cms_admin->show_layouts_list($is_layout_special);
	}
}

if($cms_admin->mode == 'config')
{
	$template_to_parse = CMS_TPL . 'cms_config_body.tpl';
	$template->assign_var('CMS_PAGE_TITLE', $lang['CMS_CONFIG']);

	// Pull all config data
	$sql = "SELECT * FROM " . $cms_admin->tables['block_variable_table'] . " AS b, " . $cms_admin->tables['block_config_table'] . " AS p
		WHERE (b.bid = 0)
			AND (p.bid = 0)
			AND (p.config_name = b.config_name)
		ORDER BY b.block, b.bvid, p.id";
	$result = $db->sql_query($sql);
	$controltype = array('1' => 'textbox', '2' => 'dropdown list', '3' => 'radio buttons', '4' => 'checkbox');
	while($row = $db->sql_fetchrow($result))
	{
		create_cms_field_tpl($row, true);
	}
	$db->sql_freeresult($result);

	if(isset($_POST['save']))
	{
		$message = $lang['CMS_Config_updated'] . '<br /><br />' . sprintf($lang['CMS_Click_return_config'], '<a href="' . append_sid($cms_admin->root . '?mode=config') . '">', '</a>') . '<br /><br />' . sprintf($lang['CMS_Click_return_cms'], '<a href="' . append_sid($cms_admin->root) . '">', '</a>') . '<br /><br />';
		message_die(GENERAL_MESSAGE, $message);
	}

	$template->assign_vars(array(
		'S_CONFIG_ACTION' => append_sid($cms_admin->root),
		'L_CONFIGURATION_TITLE' => $lang['CMS_CONFIG'],
		'L_CONFIGURATION_EXPLAIN' => $lang['Portal_Explain'],
		'L_GENERAL_CONFIG' => $lang['Portal_General_Config'],
		)
	);
}

//if (($cms_admin->mode == 'auth') && ($cms_auth->acl_get('cms_edit', $cms_admin->cms_id)))
if ($cms_admin->mode == 'auth')
{
	$template_to_parse = CMS_TPL . 'cms_auth_body.tpl';
	$cms_role_langs = cms_role_langs();

	if($cms_admin->user_id)
	{
		$cms_admin->s_hidden_fields .= '<input type="hidden" name="user_id" value="' . $cms_admin->user_id . '">';
	}

	switch ($cms_admin->action)
	{
		case 'addrole':
		case 'editrole':
			$cms_admin->s_hidden_fields .= '<input type="hidden" name="in_role" value="1" />';
			break;
		default:
			$cms_admin->s_hidden_fields .= '<input type="hidden" name="in_role" value="0" />';
			break;
	}

	if($cms_admin->action == 'save')
	{
		$class_db->main_db_table = ACL_USERS_TABLE;

		$s_in_role = request_var('in_role', 0) ? true : false;

		if(($cms_admin->user_id) || isset($_POST['username']))
		{
			$sql_where = $s_in_role ? ' AND auth_role_id <> 0' : ' AND auth_role_id = 0';

			if ($cms_admin->user_id)
			{
				$sql = "DELETE FROM " . ACL_USERS_TABLE . " WHERE user_id = '" . $cms_admin->user_id . "' AND forum_id = '" . $cms_admin->cms_id . "' " . $sql_where . "";
				$result = $db->sql_query($sql);
			}
			else
			{
				$this_userdata = get_userdata(request_var('username', ''), true);

				if (!is_array($this_userdata))
				{
					if (!defined('STATUS_404')) define('STATUS_404', true);
					message_die(GENERAL_MESSAGE, 'NO_USER');
				}

				if ($this_userdata['user_id'] == $user->data['user_id'])
				{
					redirect(append_sid($cms_admin->root . '?mode=auth'));
				}
				$cms_admin->user_id = $this_userdata['user_id'];
			}

			$data = array(
				'user_id' => $cms_admin->user_id,
				'forum_id' => $cms_admin->cms_id,
			);

			if($s_in_role)
			{
				$sql = "SELECT * FROM " . ACL_USERS_TABLE . " WHERE user_id = '" . $cms_admin->user_id . "' AND forum_id = '" . $cms_admin->cms_id . "' AND auth_role_id <> 0";
				$result = $db->sql_query($sql);
				$row = $db->sql_fetchrow($result);
				$db->sql_freeresult($result);

				if(empty($row))
				{
					$new_role = isset($_POST['role']) ? request_var('role', 0) : false;
					if($new_role)
					{
						$data['auth_role_id'] = $new_role;
						$class_db->insert_item($data);
					}
				}
			}
			else
			{
				$auth_array = array();
				//$auth_array = $_POST['auth'];
				$auth_array = request_var('auth', array(0));
				//die(print_r($auth_array));
				$data['auth_setting'] = '1';

				if (!empty($auth_array))
				{
					foreach($auth_array as $k => $update_data)
					{
						$data['auth_option_id'] = $k;
						$class_db->insert_item($data);
					}
				}
			}
		}
		redirect(append_sid($cms_admin->root . '?mode=auth'));
	}

	if(($cms_admin->action == 'delete') && ($cms_admin->user_id) && ($user->data['user_id'] != $cms_admin->user_id))
	{
		if(!isset($_POST['confirm']))
		{
			$template->assign_vars(array(
				'L_YES' => $lang['YES'],
				'L_NO' => $lang['NO'],

				'MESSAGE_TITLE' => $lang['Confirm'],
				'MESSAGE_TEXT' => $lang['Confirm_delete_item'],

				'S_CONFIRM_ACTION' => append_sid($cms_admin->root . $cms_admin->s_append_url),
				'S_HIDDEN_FIELDS' => $cms_admin->s_hidden_fields
				)
			);
			full_page_generation(CMS_TPL . 'confirm_body.tpl', $lang['Confirm'], '', '');
		}
		else
		{
			if($cms_admin->user_id != 0)
			{
				$sql = "DELETE FROM " . ACL_USERS_TABLE . " WHERE user_id = '" . $cms_admin->user_id . "' AND forum_id = '" . $cms_admin->cms_id . "' AND auth_role_id <>0";
				$result = $db->sql_query($sql);
			}
			redirect(append_sid($cms_admin->root . '?mode=auth'));
		}
	}

	$template->assign_vars(array(
		'U_AUTH_ADD' => append_sid($cms_admin->root . '?mode=auth&amp;action=add'),
		'U_AUTH_ADDROLE' => append_sid($cms_admin->root . '?mode=auth&amp;action=addrole'),
		'S_AUTH_ACTION' => append_sid($cms_admin->root . $cms_admin->s_append_url),
		'S_HIDDEN_FIELDS' => $cms_admin->s_hidden_fields
		)
	);

	if ($cms_admin->action == 'addrole')
	{
		$row_class = ($row_class == $theme['td_class1']) ? $theme['td_class2'] : $theme['td_class1'];
		$input = '<input type="text" name="username" id="username" maxlength="255" size="25" class="post" />';
		$input .= '<img src="' . $images['cms_icon_search'] . '" alt="' . $lang['Find_username'] . '" title="' . $lang['Find_username'] . '" style="cursor: pointer; vertical-align: middle;" onclick="window.open(\'' . append_sid(IP_ROOT_PATH . CMS_PAGE_SEARCH . '?mode=searchuser') . '\', \'_search\', \'width=400,height=250,resizable=yes\'); return false;" />';

		$cms_roles_select = $class_form->build_select_box('role', false, $cms_role_langs['ID'], $cms_role_langs['NAME']);

		$template->assign_block_vars('roles', array(
			'ROW_CLASS' => $row_class,
			'USERNAME' => $input,
			'CMS_ROLES' => $cms_roles_select,
			'BUTTON' => '<input type="submit" name="save" value="' . strtoupper($lang['CMS_SAVE']) . '" class="liteoption" />',
			)
		);
	}

	$sql = "SELECT au.*
					FROM " . ACL_USERS_TABLE . " au, " . ACL_ROLES_TABLE . " ar
					WHERE au.forum_id = '" . $cms_admin->cms_id . "'
						AND au.auth_role_id = ar.role_id
						AND au.auth_role_id <> 0
						AND ar.role_type LIKE 'cms_%'";
	$result = $db->sql_query($sql);
	$rows = $db->sql_fetchrowset($result);
	$db->sql_freeresult($result);

	if (!empty($rows))
	{
		foreach($rows as $data)
		{
			$row_class = ($row_class == $theme['td_class1']) ? $theme['td_class2'] : $theme['td_class1'];

			if (($cms_admin->action == 'editrole') && ($data['user_id'] == $cms_admin->user_id) && ($user->data['user_id'] != $cms_admin->user_id))
			{
				$cms_role = $class_form->build_select_box('role', $data['auth_role_id'], $cms_role_langs['ID'], $cms_role_langs['NAME']);
				$button = '<input type="submit" name="save" value="' . strtoupper($lang['CMS_SAVE']) . '" class="liteoption" />';
			}
			else
			{
				$cms_role = '<div style="margin-top:3px">' . $cms_role_langs['NAME_ARRAY'][$data['auth_role_id']] . '</div>';
				$button_link_edit = append_sid($cms_admin->root . '?mode=auth&amp;action=editrole&amp;user_id=' . $data['user_id']);
				$button_link_delete = append_sid($cms_admin->root . '?mode=auth&amp;action=delete&amp;user_id=' . $data['user_id']);
				if ($data['user_id'] == $user->data['user_id'])
				{
					$button = '';
				}
				else
				{
					$button = '<a class="cms-button-small" onclick="window.location.href=\'' . $button_link_edit . '\'" href="javascript:void(0);">' . strtoupper($lang['B_EDIT']) . '</a>';
					$button .= '<a class="cms-button-small" onclick="window.location.href=\'' . $button_link_delete . '\'" href="javascript:void(0);">' . strtoupper($lang['B_DELETE']) . '</a>';
				}
			}

			$template->assign_block_vars('roles', array(
				'ROW_CLASS' => $row_class,
				'USERNAME' => colorize_username($data['user_id']),
				'CMS_ROLES' => $cms_role,
				'BUTTON' => $button,
				)
			);
		}
	}
	elseif ($cms_admin->action != 'addrole')
	{
		$template->assign_var('NO_ROLE', true);
	}

	$cms_auth_langs_array = $cms_auth->auth_langs('cms_');

	$row_class = $theme['td_class1'];

	if ($cms_admin->action == 'add')
	{
		$button = '<input type="submit" name="save" value="' . strtoupper($lang['CMS_SAVE']) . '" class="liteoption" />';
		$input = '<input type="text" name="username" id="username" maxlength="255" size="25" class="post" />';
		$input .= '<img src="' . $images['cms_icon_search'] . '" alt="' . $lang['Find_username'] . '" title="' . $lang['Find_username'] . '" style="cursor: pointer; vertical-align: middle;" onclick="window.open(\'' . append_sid(IP_ROOT_PATH . CMS_PAGE_SEARCH . '?mode=searchuser') . '\', \'_search\', \'width=400,height=250,resizable=yes\'); return false;" />';

		$template->assign_block_vars('users', array(
				'ROW_CLASS' => $row_class,
				'USERNAME' => $input,
				'BUTTON' => $button,
			)
		);

		foreach($cms_auth_langs_array as $k => $data)
		{
			$auth_checkbox = '<input type="checkbox" name="auth[' . $k . ']">';

			$template->assign_block_vars('users.auth', array(
				'AUTH_CHECKBOX' => $auth_checkbox,
				'AUTH_CLASS' => '',
				'AUTH_NAME' => $cms_auth_langs_array[$k],
				)
			);
		}
	}

	$sql = "SELECT * FROM " . ACL_USERS_TABLE . " WHERE forum_id = '" . $cms_admin->cms_id . "' AND auth_role_id = 0 ORDER BY user_id";
	$result = $db->sql_query($sql);
	while($row = $db->sql_fetchrow($result))
	{
		$user_auth_array[$row['user_id']][$row['auth_option_id']] = $row['auth_setting'];
	}
	$db->sql_freeresult($result);

	if(!empty($user_auth_array))
	{
		foreach($user_auth_array as $id => $auth_data)
		{
			$row_class = ($row_class == $theme['td_class1']) ? $theme['td_class2'] : $theme['td_class1'];
			if(($cms_admin->action == 'edit') && ($cms_admin->user_id == $id) && ($user->data['user_id'] != $cms_admin->user_id))
			{
				$button =  '<input type="submit" name="save" value="' . strtoupper($lang['CMS_SAVE']) . '" class="liteoption" />';
			}
			else
			{
				$button_link = append_sid($cms_admin->root . '?mode=auth&amp;action=edit&amp;user_id=' . $id);
				$button = '<a class="cms-button-small" onclick="window.location.href=\'' . $button_link . '\'" href="javascript:void(0);">' . strtoupper($lang['B_EDIT']) . '</a>';
			}

			$template->assign_block_vars('users', array(
				'ROW_CLASS' => $row_class,
				'USERNAME' => colorize_username($id),
				'BUTTON' => $button,
				)
			);

			foreach($cms_auth_langs_array as $k => $data)
			{
				if (($cms_admin->action == 'edit') && $cms_admin->user_id == $id)
				{
					$is_checked = $auth_data[$k] ? 'checked="checked"' : '';
					$auth_checkbox = '<input type="checkbox" name="auth[' . $k . ']" ' . $is_checked . '>';
					$auth_class = '';
				}
				else
				{
					$auth_checkbox = '';
					$auth_class = $auth_data[$k] ? 'auth_yes' : 'auth_no';
				}

				$template->assign_block_vars('users.auth', array(
					'AUTH_CHECKBOX' => $auth_checkbox,
					'AUTH_CLASS' => $auth_class,
					'AUTH_NAME' => $cms_auth_langs_array[$k],
					)
				);
			}
		}
	}
	elseif ($cms_admin->action != 'add')
	{
		$template->assign_var('NO_AUTH', true);
	}

	foreach ($cms_role_langs['ID'] as $id_data)
	{
		$template->assign_block_vars('roles_desc', array(
			'ROLE_NAME' => $cms_role_langs['NAME_ARRAY'][$id_data],
			'ROLE_DESC' => $cms_role_langs['DESC_ARRAY'][$id_data],
			)
		);
	}
}

if (($cms_admin->mode == false))
{
	$template_to_parse = CMS_TPL . 'cms_index_body.tpl';
	$template->assign_var('CMS_PAGE_TITLE', false);
}

full_page_generation($template_to_parse, $lang['CMS_TITLE'], '', '');

?>