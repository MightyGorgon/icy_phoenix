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
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_cms_admin.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/class_cms_admin.' . PHP_EXT);

$config['jquery_ui'] = true;

$js_temp = array('js/cms.js');
$js_include = (!empty($js_include) && is_array($js_include)) ? array_merge($js_include, $js_temp) : $js_temp;
unset($js_temp);

// Start session management
$user->session_begin();
//$auth->acl($user->data);
$user->setup();
// End session management

$mode_array = array('blocks', 'config', 'layouts', 'layouts_special', 'smilies', 'block_settings');
$action_array = array('add', 'delete', 'edit', 'editglobal', 'list', 'save');

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

setup_extra_lang(array('lang_admin', 'lang_cms', 'lang_blocks'));

$cms_type = 'cms_standard';

$preview_block = isset($_POST['preview']) ? true : false;

$cms_ajax = request_var('cms_ajax', '');
$cms_ajax = (empty($cms_ajax) && (($_COOKIE['cms_ajax'] == 'true') || ($_COOKIE['cms_ajax'] == 'false')) ? $_COOKIE['cms_ajax'] : $cms_ajax);
$cms_ajax = (($cms_ajax == 'false') ? false : (($cms_ajax == 'true') ? true : ($config['cms_style'] ? true : false)));
if (($cms_ajax && ($_COOKIE['cms_ajax'] != 'true')) || (!$cms_ajax && ($_COOKIE['cms_ajax'] != 'false')))
{
	@setcookie('cms_ajax', ($cms_ajax ? 'true' : 'false'), time() + 31536000);
}
//$config['cms_style'] = $cms_ajax ? 1 : 0;
$config['cms_style'] = 0;
$cms_ajax_append = '&amp;cms_ajax=' . !empty($cms_ajax) ? 'true' : 'false';
$cms_ajax_redirect_append = '&cms_ajax=' . !empty($cms_ajax) ? 'true' : 'false';
$template->assign_vars(array(
	'U_CMS_AJAX_SWITCH' => append_sid($cms_admin->root . '?cms_ajax=' . (!empty($cms_ajax) ? 'false' : 'true')),
	'L_CMS_AJAX_SWITCH' => !empty($cms_ajax) ? $lang['CMS_AJAX_DISABLE'] : $lang['CMS_AJAX_ENABLE'],
	)
);

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

if ($config['cms_dock'])
{
	$template->assign_block_vars('cms_dock_on', array());
}
else
{
	$template->assign_block_vars('cms_dock_off', array());
}

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

		$cms_admin->show_blocks_settings_list();
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

		$cms_admin->show_blocks_list();
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
	elseif (($cms_admin->action == 'list') || ($cms_admin->action == false))
	{
		if(isset($_POST['action_update']))
		{
			$cms_admin->update_layout();
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

if (($cms_admin->mode == false))
{
	$template_to_parse = CMS_TPL . 'cms_index_body.tpl';
	$template->assign_var('CMS_PAGE_TITLE', false);
}

full_page_generation($template_to_parse, $lang['CMS_TITLE'], '', '');

?>