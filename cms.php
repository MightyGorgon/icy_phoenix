<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

define('IN_CMS', true);
define('CTRACKER_DISABLED', true);
define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);
$common_cms_template = IP_ROOT_PATH . 'templates/common/cms/';
include_once(IP_ROOT_PATH . 'includes/functions_cms_admin.' . PHP_EXT);

$js_temp = array('js/cms.js', 'scriptaculous/unittest.js');

if(is_array($js_include))
{
	$js_include = array_merge($js_include, $js_temp);
}
else
{
	$js_include = $js_temp;
}
unset($js_temp);

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

include(IP_ROOT_PATH . 'includes/class_form.' . PHP_EXT);
$class_form = new class_form();

include_once(IP_ROOT_PATH . 'includes/functions_selects.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_post.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);

setup_extra_lang(array('lang_admin', 'lang_cms', 'lang_blocks'));

$cms_type = 'cms_standard';

if (!empty($_REQUEST['mode']) && !empty($_GET['mode']) && ($_POST['mode'] != $_GET['mode']))
{
	$_REQUEST['mode'] = $_GET['mode'];
	$_POST['mode'] = $_GET['mode'];
}
$mode_array = array('blocks', 'config', 'layouts', 'layouts_special', 'smilies');
$mode = request_var('mode', '');
$mode = (in_array($mode, $mode_array) ? $mode : false);

if (!empty($_REQUEST['action']) && !empty($_GET['action']) && ($_POST['action'] != $_GET['action']))
{
	$_REQUEST['action'] = $_GET['action'];
	$_POST['action'] = $_GET['action'];
}
$action_array = array('add', 'delete', 'duplicate', 'edit', 'editglobal', 'list', 'save');
$action = request_var('action', '');
$action = (isset($_POST['add']) ? 'add' : $action);
$action = (isset($_POST['save']) ? 'save' : $action);
$action = (isset($_POST['action_duplicate']) ? 'duplicate' : $action);
$action = (in_array($action, $action_array) ? $action : false);

$preview_block = isset($_POST['preview']) ? true : false;

$cms_ajax = request_var('cms_ajax', '');
$cms_ajax = (empty($cms_ajax) && (($_COOKIE['cms_ajax'] == 'true') || ($_COOKIE['cms_ajax'] == 'false')) ? $_COOKIE['cms_ajax'] : $cms_ajax);
$cms_ajax = (($cms_ajax == 'false') ? false : (($cms_ajax == 'true') ? true : ($config['cms_style'] ? true : false)));
if (($cms_ajax && ($_COOKIE['cms_ajax'] != 'true')) || (!$cms_ajax && ($_COOKIE['cms_ajax'] != 'false')))
{
	@setcookie('cms_ajax', ($cms_ajax ? 'true' : 'false'), time() + 31536000);
}
$config['cms_style'] = $cms_ajax ? 1 : 0;
$cms_ajax_append = '&amp;cms_ajax=' . !empty($cms_ajax) ? 'true' : 'false';
$cms_ajax_redirect_append = '&cms_ajax=' . !empty($cms_ajax) ? 'true' : 'false';
$template->assign_vars(array(
	'U_CMS_AJAX_SWITCH' => append_sid(CMS_PAGE_CMS . '?cms_ajax=' . (!empty($cms_ajax) ? 'false' : 'true')),
	'L_CMS_AJAX_SWITCH' => !empty($cms_ajax) ? $lang['CMS_AJAX_DISABLE'] : $lang['CMS_AJAX_ENABLE'],
	)
);

$ls_id = (isset($_GET['ls_id']) ? intval($_GET['ls_id']) : (isset($_POST['ls_id']) ? intval($_POST['ls_id']) : false));
$ls_id = ($ls_id < 0) ? false : $ls_id;

$l_id = (isset($_GET['l_id']) ? intval($_GET['l_id']) : (isset($_POST['l_id']) ? intval($_POST['l_id']) : false));
$l_id = ($l_id < 0) ? false : $l_id;

$b_id = (isset($_GET['b_id']) ? intval($_GET['b_id']) : (isset($_POST['b_id']) ? intval($_POST['b_id']) : false));
$b_id = ($b_id < 0) ? false : $b_id;

$bv_id = (isset($_GET['bv_id']) ? intval($_GET['bv_id']) : (isset($_POST['bv_id']) ? intval($_POST['bv_id']) : false));
$bv_id = ($bv_id < 0) ? false : $bv_id;

$is_updated = (isset($_GET['updated']) ? $_GET['updated'] : (isset($_POST['updated']) ? $_POST['updated'] : false));

$redirect_append = (!empty($mode) ? ('&mode=' . $mode) : '') . (!empty($action) ? ('&action=' . $action) : '') . (!empty($l_id) ? ('&l_id=' . $l_id) : '') . (!empty($b_id) ? ('&b_id=' . $b_id) : '');

if (!$userdata['session_admin'])
{
	redirect(append_sid(CMS_PAGE_LOGIN . '?redirect=cms.' . PHP_EXT . '&admin=1' . $redirect_append, true));
}

$access_allowed = get_cms_access_auth('cms', $mode, $action, $l_id, $b_id);

if (!$access_allowed)
{
	message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
}

if ($mode == 'smilies')
{
	generate_smilies('window');
	exit;
}

if(isset($_POST['block_reset']))
{
	if ($ls_id == false)
	{
		redirect(append_sid(CMS_PAGE_CMS . '?mode=blocks&action=list&l_id=' . $l_id, true));
	}
	else
	{
		redirect(append_sid(CMS_PAGE_CMS . '?mode=blocks&action=list&ls_id=' . $ls_id, true));
	}
}

if(isset($_POST['cancel']))
{
	redirect(append_sid(CMS_PAGE_CMS, true));
}

if(isset($_POST['hascontent']))
{
	$block_content = request_post_var('blockfile', '', true);
	$block_content = (isset($_POST['blockfile'])) ? $block_content : false;
	$block_text = (empty($block_content) ? true : false);
	$hascontent = true;
}
else
{
	$block_content = false;
	$block_text = false;
	$hascontent = false;
}
$block_content_file = $block_content;

$show_cms_menu = (($userdata['user_level'] == ADMIN) || ($userdata['user_cms_level'] == CMS_CONTENT_MANAGER)) ? true : false;
$template->assign_vars(array(
	'S_CMS_AUTH' => true,
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

if(($mode == 'blocks'))
{
	$blocks_dir = IP_ROOT_PATH . 'blocks/';
	$blocks_prefix = '';
	if($l_id !== false)
	{
		$id_var_name = 'l_id';
		$id_var_value = $l_id;
		$table_name = CMS_LAYOUT_TABLE;
		$field_name = 'lid';
		$block_layout_field = 'layout';
		$layout_value = $id_var_value;
		$layout_special_value = 0;
	}
	else
	{
		//$l_id = $ls_id;
		$id_var_name = 'ls_id';
		$id_var_value = $ls_id;
		$table_name = CMS_LAYOUT_SPECIAL_TABLE;
		$field_name = 'lsid';
		$block_layout_field = 'layout_special';
		$layout_value = 0;
		$layout_special_value = $id_var_value;
	}

	$s_hidden_fields = '';
	$s_append_url = '';
	$s_hidden_fields .= '<input type="hidden" name="mode" value="' . $mode . '" />';
	$s_append_url .= '?mode=' . $mode;
	$s_hidden_fields .= '<input type="hidden" name="action" value="' . $action . '" />';
	$s_append_url .= '&amp;action=' . $action;
	$s_hidden_fields .= '<input type="hidden" name="cms_type" value="' . $cms_type . '" />';
	$s_append_url .= '&amp;cms_type=' . $cms_type;

	if($id_var_value !== false)
	{
		$s_hidden_fields .= '<input type="hidden" name="' . $id_var_name . '" value="' . $id_var_value . '" />';
		$s_append_url .= '&amp;' . $id_var_name . '=' . $id_var_value;
	}
	else
	{
		$id_var_value = 0;
	}

	if($b_id != false)
	{
		$s_hidden_fields .= '<input type="hidden" name="b_id" value="' . $b_id . '" />';
		$s_append_url .= '&amp;b_id=' . $b_id;
	}
	else
	{
		$b_id = 0;
	}

	if(($action == 'add') || ($action == 'edit'))
	{
		$message = htmlspecialchars_decode(request_post_var('message', '', true), ENT_COMPAT);

		$l_row = get_global_blocks_layout($table_name, $field_name, $id_var_value);

		if (($id_var_value == 0) || ($id_var_name == 'ls_id'))
		{
			$l_id_list = "'0'";
		}
		else
		{
			$l_id_list = "'" . $id_var_value . "'";
		}
		//$l_id_list = ($l_row['global_blocks']) ? "'" . $id_var_value . "','0'" : $l_id_list = "'" . $id_var_value . "'";

		if($action == 'edit')
		{
			if($b_id)
			{
				$b_info = get_block_info(CMS_BLOCKS_TABLE, $b_id);
			}
			else
			{
				message_die(GENERAL_MESSAGE, $lang['No_blocks_selected']);
			}
		}
		else
		{
			$b_info['bposition'] = '';
			$b_info['blockfile'] = '';
			$b_info['view'] = 0;
			$b_info['groups'] = '';
		}

		$bposition = request_post_var('bposition', '');
		$b_info['bposition'] = (isset($_POST['bposition'])) ? $bposition : $b_info['bposition'];
		$position = get_blocks_positions(CMS_BLOCK_POSITION_TABLE, $l_id_list, $b_info['bposition']);

		$blocks_array = get_blocks_files_list($blocks_dir, $blocks_prefix);
		$options_array = array();
		$options_langs_array = array();
		$options_array[] = '';
		$options_langs_array[] = '[&nbsp;' . $lang['B_Text_Block'] . '&nbsp;]';
		foreach ($blocks_array as $block_file)
		{
			$options_array[] = $blocks_prefix . $block_file;
			$options_langs_array[] = $block_file . (!empty($lang['cms_block_' . $block_file]) ? ('&nbsp;[' . $lang['cms_block_' . $block_file] . ']') : '');
		}

		$block_content_file_old = $b_info['blockfile'];
		if($cms_ajax)
		{
			$block_content_file = $b_info['blockfile'];
		}
		$blockfile = request_post_var('blockfile', '', true);
		$b_info['blockfile'] = (isset($_POST['blockfile'])) ? $blockfile : $b_info['blockfile'];

		$select_name = 'blockfile';
		$default = $b_info['blockfile'];

		$select_js = ($cms_ajax) ? ' id="blockfile" onchange="javascript:ajaxpage(\'cms_ajax.' . PHP_EXT . '\', \'?mode=block_config&amp;blockfile=\'+this.form.blockfile.options[this.form.blockfile.selectedIndex].value, \'block_config\');"' : '';
		$blockfile = $class_form->build_select_box($select_name, $default, $options_array, $options_langs_array, $select_js);

		$view = request_post_var('view', '', true);
		$b_info['view'] = (isset($_POST['view'])) ? $view : $b_info['view'];

		$select_name = 'view';
		$default = $b_info['view'];
		$options_array = array(0, 1, 2, 3, 4);
		$options_langs_array = array($lang['B_All'], $lang['B_Guests'], $lang['B_Reg'], $lang['B_Mod'], $lang['B_Admin']);
		$select_js = '';
		$view = $class_form->build_select_box($select_name, $default, $options_array, $options_langs_array, $select_js);

		$message = htmlspecialchars_decode(request_post_var('message', '', true), ENT_COMPAT);
		$message = isset($_POST['message']) ? $message : (!empty($b_info['content']) ? trim($b_info['content']) : '');

		$group = get_all_usergroups($b_info['groups']);

		if(empty($group))
		{
			$group = '&nbsp;&nbsp;' . $lang['None'];
		}

		$b_title = request_post_var('title', '', true);
		$b_title = (isset($_POST['title'])) ? $b_title : (!empty($b_info['title']) ? trim($b_info['title']) : '');
		$b_active = request_post_var('active', 0);
		$b_active = (isset($_POST['active'])) ? $b_active : ($b_info['active'] ? $b_info['active'] : 0);
		$b_type = request_post_var('type', 0);
		$b_type = (isset($_POST['type'])) ? $b_type : ($b_info['type'] ? $b_info['type'] : 0);
		$b_view = (isset($b_info['view']) ? $b_info['view'] : 0);
		$b_local = request_post_var('local', 0);
		$b_local = (isset($_POST['local'])) ? $b_local : ($b_info['local'] ? $b_info['local'] : 0);
		$b_titlebar = request_post_var('titlebar', 0);
		$b_titlebar = (isset($_POST['titlebar'])) ? $b_titlebar : ($b_info['titlebar'] ? $b_info['titlebar'] : 0);
		$b_border = request_post_var('border', 0);
		$b_border = (isset($_POST['border'])) ? $b_border : ($b_info['border'] ? $b_info['border'] : 0);
		$b_background = request_post_var('background', 0);
		$b_background = (isset($_POST['background'])) ? $b_background : ($b_info['background'] ? $b_info['background'] : 0);

		$max_group_id = get_max_group_id();
		$b_group = '';
		$b_group_hidden = '';
		$not_first = false;
		for($i = 1; $i <= $max_group_id; $i++)
		{
			if(isset($_POST['group' . strval($i)]))
			{
				$b_group_hidden .= '<input type="hidden" name="group' . strval($i) . '" value="1" />';
			}
		}


		if ($cms_ajax)
		{
			$template_to_parse = CMS_TPL . 'ajax/cms_ajax_block_content_body.tpl';
			$template->assign_var('CMS_PAGE_TITLE', $lang['CMS_BLOCK_PAGE']);
			$s_hidden_fields .= '<input type="hidden" name="hascontent" value="1" />';
		}
		else
		{
			if($block_text == true)
			{
				$template_to_parse = CMS_TPL . 'cms_block_edit_text_body.tpl';
				$template->assign_var('CMS_PAGE_TITLE', $lang['Blocks_Creation_02']);
				//generate_smilies('inline');
				$s_hidden_fields .= '<input type="hidden" name="blockfile" value="" />';
				$s_hidden_fields .= '<input type="hidden" name="hascontent" value="1" />';
				$s_hidden_fields .= '<input type="hidden" name="title" value="' . $b_title . '" />';
				$s_hidden_fields .= '<input type="hidden" name="bposition" value="' . $position['block'] . '" />';
				$s_hidden_fields .= '<input type="hidden" name="active" value="' . $b_active . '" />';
				$s_hidden_fields .= '<input type="hidden" name="local" value="' . $b_local . '" />';
				$s_hidden_fields .= '<input type="hidden" name="titlebar" value="' . $b_titlebar . '" />';
				$s_hidden_fields .= '<input type="hidden" name="border" value="' . $b_border . '" />';
				$s_hidden_fields .= '<input type="hidden" name="background" value="' . $b_background . '" />';
				$s_hidden_fields .= '<input type="hidden" name="view" value="' . $b_view . '" />';
				$s_hidden_fields .= $b_group_hidden;
			}
			elseif($block_content != false)
			{
				$template_to_parse = CMS_TPL . 'cms_block_edit_body.tpl';
				$template->assign_var('CMS_PAGE_TITLE', $lang['Blocks_Creation_02']);
				$s_hidden_fields .= '<input type="hidden" name="blockfile" value="' . $block_content_file . '" />';
				$s_hidden_fields .= '<input type="hidden" name="message" value="" />';
				$s_hidden_fields .= '<input type="hidden" name="type" value="0" />';
				$s_hidden_fields .= '<input type="hidden" name="title" value="' . $b_title . '" />';
				$s_hidden_fields .= '<input type="hidden" name="bposition" value="' . $position['block'] . '" />';
				$s_hidden_fields .= '<input type="hidden" name="active" value="' . $b_active . '" />';
				$s_hidden_fields .= '<input type="hidden" name="local" value="' . $b_local . '" />';
				$s_hidden_fields .= '<input type="hidden" name="titlebar" value="' . $b_titlebar . '" />';
				$s_hidden_fields .= '<input type="hidden" name="border" value="' . $b_border . '" />';
				$s_hidden_fields .= '<input type="hidden" name="background" value="' . $b_background . '" />';
				$s_hidden_fields .= '<input type="hidden" name="view" value="' . $b_view . '" />';
				$s_hidden_fields .= $b_group_hidden;

				if (($b_id > 0) && ($block_content_file == $block_content_file_old))
				{
					$sql = "SELECT * FROM " . CMS_CONFIG_TABLE . " AS c, " . CMS_BLOCK_VARIABLE_TABLE . " AS bv
										WHERE c.bid = '" . $b_id . "'
											AND bv.bid = '" . $b_id . "'
											AND c.config_name = bv.config_name
										ORDER BY c.id";
					$result = $db->sql_query($sql);

					$controltype = array('1' => 'textbox', '2' => 'dropdown list', '3' => 'radio buttons', '4' => 'checkbox');
					$rows_counter = 0;
					while($row = $db->sql_fetchrow($result))
					{
						$cms_field = array();
						$cms_field = create_cms_field($row);

						$default_portal[$cms_field[$row['config_name']]['name']] = $cms_field[$row['config_name']]['value'];

						if($cms_field[$row['config_name']]['type'] == '4')
						{
							$new[$cms_field[$row['config_name']]['name']] = (isset($_POST[$cms_field[$row['config_name']]['name']])) ? '1' : '0';
						}
						else
						{
							$new[$cms_field[$row['config_name']]['name']] = (isset($_POST[$cms_field[$row['config_name']]['name']])) ? $_POST[$cms_field[$row['config_name']]['name']] : $default_portal[$cms_field[$row['config_name']]['name']];
						}

						$is_block = ($cms_field[$row['config_name']]['block'] != '@Portal Config') ? 'block ' : '';

						$template->assign_block_vars('cms_block', array(
							'L_FIELD_LABEL' => $cms_field[$row['config_name']]['label'],
							'L_FIELD_SUBLABEL' => '<br /><br /><span class="gensmall">' . $cms_field[$row['config_name']]['sub_label'] . ' [ ' . str_replace("@", "", $cms_field[$row['config_name']]['block']) . ' ' . $is_block . ']</span>',
							'FIELD' => $cms_field[$row['config_name']]['output']
							)
						);
						$rows_counter++;
					}

					if ($rows_counter == 0)
					{
						$template->assign_block_vars('cms_no_bv', array(
							'L_NO_BV' => $lang['No_bv_selected'],
							)
						);
					}
					$db->sql_freeresult($result);
				}
				else
				{
					if(file_exists($blocks_dir . $block_content_file . '.cfg'))
					{
						$block_count_variables = 0;
						include($blocks_dir . $block_content_file . '.cfg');
						if ($block_count_variables > 0)
						{
							for($i = 0; $i < $block_count_variables; $i++)
							{
								$row = array(
									'config_name' => $block_variables[$i][2],
									'config_value' => $block_variables[$i][7],
									'label' => $block_variables[$i][0],
									'sub_label' => $block_variables[$i][1],
									'field_options' => $block_variables[$i][3],
									'field_values' => $block_variables[$i][4],
									'type' => $block_variables[$i][5],
									'block' => $block_variables[$i][6],
								);

								$cms_field = array();
								$cms_field = create_cms_field($row);

								$default_portal[$cms_field[$row['config_name']]['name']] = $cms_field[$row['config_name']]['value'];

								if($cms_field[$row['config_name']]['type'] == '4')
								{
									$new[$cms_field[$row['config_name']]['name']] = (isset($_POST[$cms_field[$row['config_name']]['name']])) ? '1' : '0';
								}
								else
								{
									$new[$cms_field[$row['config_name']]['name']] = (isset($_POST[$cms_field[$row['config_name']]['name']])) ? $_POST[$cms_field[$row['config_name']]['name']] : $default_portal[$cms_field[$row['config_name']]['name']];
								}

								$is_block = ($cms_field[$row['config_name']]['block'] != '@Portal Config') ? 'block ' : '';

								$template->assign_block_vars('cms_block', array(
									'L_FIELD_LABEL' => $cms_field[$row['config_name']]['label'],
									'L_FIELD_SUBLABEL' => '<br /><br /><span class="gensmall">' . $cms_field[$row['config_name']]['sub_label'] . ' [ ' . str_replace("@", "", $cms_field[$row['config_name']]['block']) . ' ' . $is_block . ']</span>',
									'FIELD' => $cms_field[$row['config_name']]['output']
									)
								);
							}
						}
						else
						{
							$template->assign_block_vars('cms_no_bv', array(
								'L_NO_BV' => $lang['No_bv_selected'],
								)
							);
						}
					}
					else
					{
						$template->assign_block_vars('cms_no_bv', array(
							'L_NO_BV' => $lang['No_bv_selected'],
							)
						);
					}
				}
			}
			else
			{
				$template_to_parse = CMS_TPL . 'cms_block_content_body.tpl';
				$template->assign_var('CMS_PAGE_TITLE', $lang['Blocks_Creation_01']);
				$s_hidden_fields .= '<input type="hidden" name="message" value="' . htmlspecialchars($message) . '" />';
				$s_hidden_fields .= '<input type="hidden" name="type" value="' . $b_type . '" />';
				$s_hidden_fields .= '<input type="hidden" name="hascontent" value="1" />';
			}
		}

		if ($preview_block == true)
		{
			if ($b_type == true)
			{
				$preview_message = preg_replace('#(<)([\/]?.*?)(>)#is', "&lt;\\2&gt;", $message);
				$bbcode->allow_html = false;
				$bbcode->allow_bbcode = true;
				$bbcode->allow_smilies = true;
				$preview_message = $bbcode->parse($preview_message);
			}
			else
			{
				$preview_message = $message;
				// You shouldn't convert NEW LINES to <br /> because you are parsing HTML, so linebreaks must be inserted as <br />
				// If you want linebreaks to be converted automatically, just decomment this line.
				//$preview_message = str_replace("\n", "\n<br />\n", $message);
			}
			$template->assign_block_vars('block_preview', array(
				'PREVIEW_MESSAGE' => $preview_message,
				)
			);
		}

		if($cms_ajax)
		{
			if ($block_content_file)
			{
				$sql = "SELECT count(*) count_fields FROM " . CMS_CONFIG_TABLE . " AS c, " . CMS_BLOCK_VARIABLE_TABLE . " AS bv
									WHERE c.bid = '" . $b_id . "'
										AND bv.bid = '" . $b_id . "'
										AND c.config_name = bv.config_name
									ORDER BY c.id";
				$result = $db->sql_query($sql);
				$row = $db->sql_fetchrow($result);
				$db->sql_freeresult($result);

				if ($row['count_fields'] > 0)
				{
					$block_config = '<a href="#" onclick="ajaxpage(\'cms_ajax.' . PHP_EXT . '\', \'?mode=block_config&amp;blockfile=' . $block_content_file . '&amp;action=edit&amp;b_id=' . $b_id . '&amp;b_type=' . $b_type . '\', \'block_config\'); return false;">' . $lang['CMS_BLOCK_CONFIG_EDIT'] . '</a>';
				}
				else
				{
					$block_config = $lang['CMS_BLOCK_CONFIG_NO_VARS'];
				}
			}
			else
			{
				$block_config = '<a href="#" onclick="ajaxpage(\'cms_ajax.' . PHP_EXT . '\', \'?mode=block_config&amp;blockfile=' . $block_content_file . '&amp;action=edit&amp;b_id=' . $b_id . '&amp;b_type=' . $b_type . '\', \'block_config\'); return false;">' . $lang['CMS_BLOCK_CONFIG_EDIT'] . '</a>';
			}
		}

		$template->assign_vars(array(
			'L_BLOCKS_TITLE' => $lang['Blocks_Title'],
			'L_BLOCKS_TEXT' => $lang['Blocks_Explain'],
			'L_BLOCKS_PAGE_01' => $lang['Blocks_Creation_01'],
			'L_BLOCKS_PAGE_02' => $lang['Blocks_Creation_02'],
			'L_B_TITLE' => $lang['B_Title'],
			'L_B_POSITION' => $lang['B_Position'],
			'L_B_ACTIVE' => $lang['B_Active'],
			'L_B_CONTENT' => $lang['B_Content'],
			'L_B_HTML' => $lang['B_HTML'],
			'L_B_BBCODE' => $lang['B_BBCode'],
			'L_B_TYPE' => $lang['B_Type'],
			'L_B_BLOCK_FILE' => $lang['B_Blockfile'],
			'L_B_VIEW_BY' => $lang['B_View'],
			'L_B_BORDER' => $lang['B_Border'],
			'L_B_TITLEBAR' => $lang['B_Titlebar'],
			'L_B_LOCAL' => $lang['B_Local'],
			'L_B_BACKGROUND' => $lang['B_Background'],
			'L_B_GROUP' => $lang['B_Groups'],
			'L_YES' => $lang['Yes'],
			'L_NO' => $lang['No'],
			'L_ENABLED' => $lang['Enabled'],
			'L_DISABLED' => $lang['Disabled'],
			'L_EDIT_BLOCK' => $lang['Block_Edit'],
			'L_SUBMIT' => $lang['Submit'],
			'L_PREVIEW' => $lang['Preview'],

			'TITLE' => $b_title,
			'POSITION' => $position['select'],
			'ACTIVE' => ($b_active) ? 'checked="checked"' : '',
			'NOT_ACTIVE' => (!$b_active) ? 'checked="checked"' : '',
			'HTML' => (!$b_type) ? 'checked="checked"' : '',
			'BBCODE' => ($b_type) ? 'checked="checked"' : '',
			'CONTENT' => htmlspecialchars($message),
			'BLOCKFILE' => $blockfile,
			'BLOCK_CONFIG' => $block_config,
			'VIEWBY' => $view,
			'BORDER' => ($b_border) ? 'checked="checked"' : '',
			'NO_BORDER' => (!$b_border) ? 'checked="checked"' : '',
			'TITLEBAR' => ($b_titlebar) ? 'checked="checked"' : '',
			'NO_TITLEBAR' => (!$b_titlebar) ? 'checked="checked"' : '',
			'LOCAL' => ($b_local) ? 'checked="checked"' : '',
			'NOT_LOCAL' => (!$b_local) ? 'checked="checked"' : '',
			'BACKGROUND' => ($b_background) ? 'checked="checked"' : '',
			'NO_BACKGROUND' => (!$b_background) ? 'checked="checked"' : '',
			'GROUP' => $group,

			'S_BLOCKS_ACTION' => append_sid(CMS_PAGE_CMS . $s_append_url),
			'S_HIDDEN_FIELDS' => $s_hidden_fields
			)
		);

		// BBCBMG - BEGIN
		//$bbcbmg_in_acp = true;
		include(IP_ROOT_PATH . 'includes/bbcb_mg.' . PHP_EXT);
		$template->assign_var_from_handle('BBCB_MG', 'bbcb_mg');
		// BBCBMG - END
		// BBCBMG SMILEYS - BEGIN
		generate_smilies('inline');
		include(IP_ROOT_PATH . 'includes/bbcb_smileys_mg.' . PHP_EXT);
		$template->assign_var_from_handle('BBCB_SMILEYS_MG', 'bbcb_smileys_mg');
		// BBCBMG SMILEYS - END
	}
	elseif($action == 'save')
	{
		$is_auth = get_layout_edit_auth($table_name, $field_name, $id_var_value);
		if (!$is_auth)
		{
			message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
		}

		$b_title = request_post_var('title', '', true);
		$b_bposition = request_post_var('bposition', '', true);
		$b_active = request_post_var('active', 0);
		$b_type = request_post_var('type', 0);
		$b_content = htmlspecialchars_decode(request_post_var('message', '', true), ENT_COMPAT);
		$b_blockfile = request_post_var('blockfile', '', true);
		$b_view = request_post_var('view', '');
		$b_border = request_post_var('border', 0);
		$b_titlebar = request_post_var('titlebar', 0);
		$b_local = request_post_var('local', 0);
		$b_background = request_post_var('background', 0);
		$is_config = (isset($_GET['isconfig']) ? true : (isset($_POST['isconfig']) ? true : false));

		$max_group_id = get_max_group_id();
		$b_group = '';
		$not_first = false;
		for($i = 1; $i <= $max_group_id; $i++)
		{
			if(isset($_POST['group' . strval($i)]))
			{
				if($not_first)
				{
					$b_group .= ',' . strval($i);
				}
				else
				{
					$b_group .= strval($i);
					$not_first = true;
				}
			}
		}

		$gb_pos = array('gh', 'gf', 'gt', 'gb', 'gl', 'gr', 'hh', 'hl', 'hc', 'fc', 'fr', 'ff');
		if(in_array($b_position, $gb_pos))
		{
			if ($id_var_name == 'l_id')
			{
				$id_var_value = 0;
			}
		}

		if (($id_var_value == 0) && ($id_var_name == 'l_id'))
		{
			$redirect_l_id = $id_var_value . '&amp;action=editglobal';
		}
		else
		{
			$redirect_l_id = $id_var_value;
		}

		if($b_title == '')
		{
			message_die(GENERAL_MESSAGE, $lang['Must_enter_block']);
		}

		if($b_type == true)
		{
			if(!empty($b_content))
			{
				//$b_content = prepare_message(trim($b_content), true, true, true);
				//$b_content = $db->sql_escape($b_content);
			}
		}

		if($b_id)
		{
			$message = $lang['Block_updated'];

			if ($is_config || !$cms_ajax)
			{
				$sql = "UPDATE " . CMS_BLOCKS_TABLE . "
					SET
					title = '" . $db->sql_escape($b_title) . "',
					bposition = '" . $db->sql_escape($b_bposition) . "',
					active = '" . $b_active . "',
					type = '" . $b_type . "',
					content = '" . $db->sql_escape($b_content) . "',
					blockfile = '" . $db->sql_escape($b_blockfile) . "',
					layout = '" . $layout_value . "',
					layout_special = '" . $layout_special_value . "',
					view = '" . $b_view . "',
					border = '" . $b_border . "',
					titlebar = '" . $b_titlebar . "',
					local = '" . $b_local . "',
					background = '" . $b_background . "',
					groups = '" . $b_group . "'
					WHERE bid = $b_id";
				$result = $db->sql_query($sql);

				if(file_exists($blocks_dir . $b_blockfile . '.cfg'))
				{
					include($blocks_dir . $b_blockfile . '.cfg');

					// let's empty the previously created config vars...
					$sql = "SELECT * FROM " . CMS_CONFIG_TABLE . " WHERE bid = '" . $b_id . "'";
					$result = $db->sql_query($sql);

					while($row = $db->sql_fetchrow($result))
					{
						$delete_var = true;
						for($i = 0; $i < $block_count_variables; $i++)
						{
							if ($row['config_name'] == $block_variables[$i][2])
							{
								$delete_var = false;
							}
						}

						if ($delete_var == true)
						{
							delete_block_config_single(CMS_CONFIG_TABLE, CMS_BLOCK_VARIABLE_TABLE, $b_id, $row['config_name']);
						}
					}
					$db->sql_freeresult($result);
				}
				else
				{
					delete_block_config_all(CMS_CONFIG_TABLE, CMS_BLOCK_VARIABLE_TABLE, $b_id);
				}

				if(!empty($b_blockfile))
				{
					if(file_exists($blocks_dir . $b_blockfile . '.cfg'))
					{
						include($blocks_dir . $b_blockfile . '.cfg');

						//$message .= '<br /><br />' . $lang['B_BV_added'];

						for($i = 0; $i < $block_count_variables; $i++)
						{
							if ((!empty($_POST[$block_variables[$i][2]])) || ($_POST[$block_variables[$i][2]] == '0'))
							{
								$block_variables[$i][7] = $db->sql_escape($_POST[$block_variables[$i][2]]);
							}

							$existing = get_existing_block_var(CMS_BLOCK_VARIABLE_TABLE, $b_id, $block_variables[$i][2]);

							if(!$existing)
							{
								$sql = "INSERT INTO " . CMS_BLOCK_VARIABLE_TABLE . " (bid, label, sub_label, config_name, field_options, field_values, type, block)
									VALUES ('" . $b_id ."', '" . $db->sql_escape($block_variables[$i][0]) . "', '" . $db->sql_escape($block_variables[$i][1]) . "', '" . $db->sql_escape($block_variables[$i][2]) . "', '" . $db->sql_escape($block_variables[$i][3]) . "', '" . $db->sql_escape($block_variables[$i][4]) . "', '" . $block_variables[$i][5] . "', '" . $db->sql_escape($block_variables[$i][6]) . "')";
								$result = $db->sql_query($sql);

								$sql = "INSERT INTO " . CMS_CONFIG_TABLE . " (bid, config_name, config_value)
									VALUES ('" . $b_id ."', '" . $db->sql_escape($block_variables[$i][2]) . "', '" . $db->sql_escape($block_variables[$i][7]) . "')";
								$result = $db->sql_query($sql);
							}
							else
							{
								$sql = "UPDATE " . CMS_CONFIG_TABLE . " SET config_value = '" . $db->sql_escape($block_variables[$i][7]) . "'
												WHERE config_name = '" . $db->sql_escape($block_variables[$i][2]) . "'
													AND bid = " . $b_id;
								$result = $db->sql_query($sql);
							}
						}
					}
				}
			}
			else
			{
				$sql = "UPDATE " . CMS_BLOCKS_TABLE . "
					SET
					title = '" . $db->sql_escape($b_title) . "',
					bposition = '" . $db->sql_escape($b_bposition) . "',
					active = '" . $b_active . "',
					layout = '" . $layout_value . "',
					layout_special = '" . $layout_special_value . "',
					view = '" . $b_view . "',
					border = '" . $b_border . "',
					titlebar = '" . $b_titlebar . "',
					local = '" . $b_local . "',
					background = '" . $b_background . "',
					groups = '" . $b_group . "'
					WHERE bid = $b_id";
				$result = $db->sql_query($sql);
			}
		}
		else
		{
			$message = $lang['Block_added'];

			$weight = get_max_blocks_position(CMS_BLOCKS_TABLE, $id_var_value, $b_bposition) + 1;
			$b_id = get_max_block_id(CMS_BLOCKS_TABLE) + 1;

			$sql = "INSERT INTO " . CMS_BLOCKS_TABLE . " (bid, title, content, bposition, weight, active, type, blockfile, view, layout, layout_special, border, titlebar, background, local, groups) VALUES ('" . $b_id . "', '" . $db->sql_escape($b_title) . "', '" . $db->sql_escape($b_content) . "', '" . $b_bposition . "', '" . $weight . "', '" . $b_active . "', '" . $b_type . "', '" . $b_blockfile . "', '" . $b_view . "', '" . $layout_value . "', '" . $layout_special_value . "', '" . $b_border . "', '" . $b_titlebar . "', '" . $b_background . "', '" . $b_local . "', '" . $b_group . "')";
			$result = $db->sql_query($sql);

			if(!empty($b_blockfile))
			{
				if(file_exists($blocks_dir . $b_blockfile . '.cfg'))
				{
					include($blocks_dir . $b_blockfile . '.cfg');

					//$message .= '<br /><br />' . $lang['B_BV_added'];

					for($i = 0; $i < $block_count_variables; $i++)
					{
						if ((!empty($_POST[$block_variables[$i][2]])) || ($_POST[$block_variables[$i][2]] == '0'))
						{
							$block_variables[$i][7] = $db->sql_escape($_POST[$block_variables[$i][2]]);
						}

						$sql = "INSERT INTO " . CMS_BLOCK_VARIABLE_TABLE . " (bid, label, sub_label, config_name, field_options, field_values, type, block)
							VALUES ('" . $b_id . "', '" . $db->sql_escape($block_variables[$i][0]) . "', '" . $db->sql_escape($block_variables[$i][1]) . "', '" . $db->sql_escape($block_variables[$i][2]) . "', '" . $db->sql_escape($block_variables[$i][3]) . "', '" . $db->sql_escape($block_variables[$i][4]) . "', '" . $block_variables[$i][5] . "', '" . $db->sql_escape($block_variables[$i][6]) . "')";
						$result = $db->sql_query($sql);

						$sql = "INSERT INTO " . CMS_CONFIG_TABLE . " (bid, config_name, config_value)
							VALUES ('" . $b_id ."', '" . $db->sql_escape($block_variables[$i][2]) . "', '" . $db->sql_escape($block_variables[$i][7]) . "')";
						$result = $db->sql_query($sql);
					}
				}
			}
		}
		fix_weight_blocks($id_var_value, $table_name);
		$message .= '<br /><br />' . sprintf($lang['Click_return_blocksadmin'], '<a href="' . append_sid(CMS_PAGE_CMS . '?mode=blocks&amp;' . $id_var_name . '=' . $redirect_l_id) . '">', '</a>') . '<br />';
		message_die(GENERAL_MESSAGE, $message);
	}
	elseif($action == 'delete')
	{
		if(!isset($_POST['confirm']))
		{
			$template->assign_vars(array(
				'L_YES' => $lang['Yes'],
				'L_NO' => $lang['No'],

				'MESSAGE_TITLE' => $lang['Confirm'],
				'MESSAGE_TEXT' => $lang['Confirm_delete_item'],

				'S_CONFIRM_ACTION' => append_sid(CMS_PAGE_CMS . $s_append_url),
				'S_HIDDEN_FIELDS' => $s_hidden_fields
				)
			);
			full_page_generation(CMS_TPL . 'confirm_body.tpl', $lang['Confirm'], '', '');
		}
		else
		{
			if($b_id != 0)
			{
				delete_block(CMS_BLOCKS_TABLE, $b_id);
				if (($l_id == 0) && ($id_var_name == 'l_id'))
				{
					$redirect_action = '&amp;action=editglobal';
				}
				else
				{
					$redirect_action = '&amp;action=list';
				}

				$message = $lang['Block_removed'] . '<br /><br />' . sprintf($lang['Click_return_blocksadmin'], '<a href="' . append_sid(CMS_PAGE_CMS . '?mode=blocks&amp;' . $id_var_name . '=' . $id_var_value . $redirect_action) . '">', '</a>') . '<br />';
				message_die(GENERAL_MESSAGE, $message);
			}
			else
			{
				message_die(GENERAL_MESSAGE, $lang['No_blocks_selected']);
			}
			fix_weight_blocks($id_var_value, $table_name);
			fix_weight_blocks(0, $table_name);
		}
	}
	elseif($action == 'duplicate')
	{
		if (!empty($_POST['duplicate_blocks']))
		{
			$blocks_dup = array();
			$blocks_dup = $_POST['block'];
			$blocks_dup_n = sizeof($blocks_dup);
			/*
			$sql_blocks_dup = '';
			if ($blocks_dup_n > 0)
			{
				for($i = 0; $i < $blocks_dup_n; $i++)
				{
					$sql_blocks_dup .= (($sql_blocks_dup != '') ? '\', \'' : '') . intval($blocks_dup[$i]);
				}
			}
			$sql_blocks_dup = 'AND b.bid IN (\'' . $sql_blocks_dup . '\')';
			*/

			if ($blocks_dup_n > 0)
			{
				for($i = 0; $i < $blocks_dup_n; $i++)
				{
					$sql = "SELECT *
						FROM " . CMS_BLOCKS_TABLE . "
						WHERE bid = '" . intval($blocks_dup[$i]) . "'";
					$result = $db->sql_query($sql);
					$b_info = $db->sql_fetchrow($result);
					$db->sql_freeresult($result);
					$db->sql_transaction('begin');

					$weight = get_max_blocks_position(CMS_BLOCKS_TABLE, $id_var_value, $b_bposition, 'layout') + 1;
					$b_id = get_max_block_id(CMS_BLOCKS_TABLE) + 1;

					$sql = "INSERT INTO " . CMS_BLOCKS_TABLE . " (bid, title, content, bposition, weight, active, type, blockfile, view, layout, layout_special, border, titlebar, background, local, groups) VALUES ('" . $b_id . "', '" . $db->sql_escape($b_info['title']) . "', '" . $db->sql_escape($b_info['content']) . "', '" . $db->sql_escape($b_info['bposition']) . "', '" . $b_info['weight'] . "', '" . $b_info['active'] . "', '" . $b_info['type'] . "', '" . $b_info['blockfile'] . "', '" . $b_info['view'] . "', '" . (($id_var_name == 'l_id') ? $id_var_value : 0) . "', '" . (($id_var_name == 'ls_id') ? $id_var_value : 0) . "', '" . $b_info['border'] . "', '" . $b_info['titlebar'] . "', '" . $b_info['background'] . "', '" . $b_info['local'] . "', '" . $b_info['groups'] . "')";
					$result = $db->sql_query($sql);

					$sql_cfg = "SELECT * FROM " . CMS_CONFIG_TABLE . " AS c, " . CMS_BLOCK_VARIABLE_TABLE . " AS bv
										WHERE c.bid = '" . intval($blocks_dup[$i]) . "'
											AND bv.bid = '" . intval($blocks_dup[$i]) . "'
											AND c.config_name = bv.config_name
										ORDER BY c.id";
					$result_cfg = $db->sql_query($sql_cfg);

					while($row_cfg = $db->sql_fetchrow($result_cfg))
					{
						$portal_name = $row_cfg['config_name'];
						$sql = "INSERT INTO " . CMS_BLOCK_VARIABLE_TABLE . " (bid, label, sub_label, config_name, field_options, field_values, type, block)
							VALUES ('" . $b_id . "', '" . $db->sql_escape($row_cfg['label']) . "', '" . $db->sql_escape($row_cfg['sub_label']) . "', '" . $db->sql_escape($row_cfg['config_name']) . "', '" . $row_cfg['field_options'] . "', '" . $db->sql_escape($row_cfg['field_values']) . "', '" . $row_cfg['type'] . "', '" . $db->sql_escape($row_cfg['block']) . "')";
						$result = $db->sql_query($sql);

						$sql = "INSERT INTO " . CMS_CONFIG_TABLE . " (bid, config_name, config_value)
							VALUES ('" . $b_id . "', '" . $db->sql_escape($row_cfg['config_name']) . "', '" . $db->sql_escape($row_cfg['config_value']) . "')";
						$result = $db->sql_query($sql);
					}
					$db->sql_transaction('commit');
				}
			}
			fix_weight_blocks($id_var_value, $table_name);
			$message = '<br /><br />' . $lang['Blocks_duplicated'] . '<br /><br />' . sprintf($lang['Click_return_blocksadmin'], '<a href="' . append_sid(CMS_PAGE_CMS . '?mode=blocks&amp;' . $id_var_name . '=' . $id_var_value) . '">', '</a>') . '<br />';
			message_die(GENERAL_MESSAGE, $message);
		}
		else
		{
			$template_to_parse = CMS_TPL . 'cms_blocks_duplicate_body.tpl';
			$template->assign_var('CMS_PAGE_TITLE', $lang['Blocks_Title']);

			$l_row = get_layout_name($table_name, $field_name, $id_var_value);
			$l_name = $l_row['name'];
			$l_filename = $l_row['filename'];

			if (($id_var_value == 0) || ($id_var_name == 'ls_id'))
			{
				$page_url = append_sid(CMS_PAGE_HOME);
				$l_id_list = "'0'";
				$l_name = $l_filename;
			}
			else
			{
				if ($id_var_name == 'l_id')
				{
					if (($l_filename != '') && file_exists($l_filename))
					{
						$page_url = append_sid($l_filename);
					}
					else
					{
						$page_url = (substr($l_name, strlen($l_name) - (strlen(PHP_EXT) + 1), (strlen(PHP_EXT) + 1)) == ('.' . PHP_EXT)) ? append_sid($l_name) : append_sid(CMS_PAGE_HOME . '?page=' . $id_var_value);
					}
					$l_name = ($l_name == '') ? $lang['Portal'] : $l_name;
				}
				else
				{
					$page_url = append_sid($l_filename);
					$l_name = $l_filename;
				}
				$l_id_list = "'" . $id_var_value . "'";
			}

			$sql = "SELECT bposition, pkey FROM " . CMS_BLOCK_POSITION_TABLE . " WHERE layout IN (" . $l_id_list . ")";
			$result = $db->sql_query($sql);
			$position = array();
			$position_list = '';
			while ($row = $db->sql_fetchrow($result))
			{
				$position[$row['bposition']] = $row['pkey'];
				$positions_list .= '\'' . $row['bposition'] . '\',';
			}
			$db->sql_freeresult($result);
			$positions_list = substr($positions_list, 0, (strlen($positions_list) - 1));

			$sort_sql = ($_GET['dsort'] == 'p') ? 'b.bposition, b.layout' : 'b.layout, b.bposition';
			//$cms_level_sql = "AND b.edit_auth <= " . $userdata['user_cms_level'];
			$cms_level_sql = "";
			$sql = "SELECT b.*, l.name
							FROM " . CMS_BLOCKS_TABLE . " AS b, " . CMS_LAYOUT_TABLE . " AS l
							WHERE b.bposition IN (" . $positions_list . ")
								AND l.lid = b.layout
								" . $cms_level_sql . "
							ORDER BY " . $sort_sql;
			$result = $db->sql_query($sql);
			$b_rows = $db->sql_fetchrowset($result);
			$db->sql_freeresult($result);
			$b_count = sizeof($b_rows);

			$template->assign_vars(array(
				'L_BLOCKS_TITLE' => $lang['B_Duplicate'],
				'L_BLOCKS_TEXT' => $lang['Blocks_Duplicate_Explain'],
				'L_B_TITLE' => $lang['B_Title'],
				'L_B_POSITION' => $lang['B_Position'],
				'L_B_ACTIVE' => $lang['B_Active'],
				'L_B_DISPLAY' => $lang['B_Display'],
				'L_B_TYPE' => $lang['B_Type'],
				'L_B_LAYOUT' => $lang['B_Layout'],
				'L_B_LAYOUT_EDIT' => $lang['B_Layout_Edit'],
				'L_B_PAGE' => $lang['B_Page'],
				'L_B_VIEW_BY' => $lang['B_View'],
				'L_B_BORDER' => $lang['B_Border'],
				'L_B_TITLEBAR' => $lang['B_Titlebar'],
				'L_B_LOCAL' => $lang['B_Local'],
				'L_B_BACKGROUND' => $lang['B_Background'],
				'L_B_GROUPS' => $lang['B_Groups'],
				'L_ACTION' => $lang['Action'],
				'L_BLOCKS_ADD' => $lang['B_Add'],
				'L_BLOCKS_DUPLICATE' => $lang['B_Duplicate'],
				'L_BLOCKS_UPDATE' => $lang['B_Update'],
				'L_CONFIGURE_BLOCKS' => $lang['CMS_Configure_Blocks'],
				'L_EDIT' => $lang['CMS_Edit'],
				'L_DELETE' => $lang['CSM_Delete'],
				'L_PREVIEW' => $lang['CMS_Preview'],
				'L_MOVE_UP' => $lang['B_Move_Up'],
				'L_MOVE_DOWN' => $lang['B_Move_Down'],

				'LAYOUT_NAME' => $l_name,
				'PAGE_URL' => $page_url,
				'PAGE' => strval($id_var_value),
				'U_LAYOUT_EDIT' => (($block_layout_field == 'layout') ? append_sid(CMS_PAGE_CMS . '?mode=layouts&amp;action=edit' . '&amp;' . $id_var_name . '=' . $id_var_value) : ''),

				'S_BLOCKS_ACTION' => append_sid(CMS_PAGE_CMS),
				'S_HIDDEN_FIELDS' => $s_hidden_fields
				)
			);

			for($i = 0; $i < $b_count; $i++)
			{
				$b_id = $b_rows[$i]['bid'];
				$b_weight = $b_rows[$i]['weight'];
				$b_position = $b_rows[$i]['bposition'];
				$b_position_l = !empty($lang['cms_pos_' . $position[$b_position]]) ? $lang['cms_pos_' . $position[$b_position]] : $row['pkey'];

				$row_class = (!($i % 2)) ? $theme['td_class2'] : $theme['td_class1'];

				switch ($b_rows[$i]['view'])
				{
					case '0':
						$b_view = $lang['B_All'];
						break;
					case '1':
						$b_view = $lang['B_Guests'];
						break;
					case '2':
						$b_view = $lang['B_Reg'];
						break;
					case '3':
						$b_view = $lang['B_Mod'];
						break;
					case '4':
						$b_view = $lang['B_Admin'];
						break;
				}

				if(!empty($b_rows[$i]['groups']))
				{
					$groups = get_groups_names($b_rows[$i]['groups']);
				}
				else
				{
					$groups = $lang['B_All'];
				}

				if (($l_id == 0) && ($id_var_name == 'l_id'))
				{
					$redirect_action = '&amp;action=editglobal';
				}
				else
				{
					$redirect_action = '&amp;action=list';
				}

				$template->assign_block_vars('blocks', array(
					'ROW_CLASS' => $row_class,
					'TITLE' => $b_rows[$i]['title'],
					'LNAME' => $b_rows[$i]['name'],
					'BLOCK_CB_ID' => $b_rows[$i]['bid'],
					//'POSITION' => $position[$b_position],
					'POSITION' => $b_position_l,
					'L_POSITION' => $b_position_l,
					'ACTIVE' => ($b_rows[$i]['active']) ? $lang['Yes'] : $lang['No'],
					'TYPE' => (empty($b_rows[$i]['blockfile'])) ? (($b_rows[$i]['type']) ? $lang['B_BBCode'] : $lang['B_HTML']) : '&nbsp;',
					'BORDER' => ($b_rows[$i]['border']) ? $lang['Yes'] : $lang['No'],
					'TITLEBAR' => ($b_rows[$i]['titlebar']) ? $lang['Yes'] : $lang['No'],
					'LOCAL' => ($b_rows[$i]['local']) ? $lang['Yes'] : $lang['No'],
					'BACKGROUND' => ($b_rows[$i]['background']) ? $lang['Yes'] : $lang['No'],
					'GROUPS' => $groups,
					'CONTENT' => (empty($b_rows[$i]['blockfile'])) ? $lang['B_Text'] : $lang['B_File'],
					'VIEW' => $b_view,

					'U_EDIT' => append_sid(CMS_PAGE_CMS . '?mode=blocks&amp;action=edit&amp;' . $id_var_name . '=' . $id_var_value . '&amp;b_id=' . $b_id),
					'U_DELETE' => append_sid(CMS_PAGE_CMS . '?mode=blocks&amp;action=delete&amp;' . $id_var_name . '=' . $id_var_value . '&amp;b_id=' . $b_id),
					'U_MOVE_UP' => append_sid(CMS_PAGE_CMS . '?mode=blocks' . $redirect_action . '&amp;' . $id_var_name . '=' . $id_var_value . '&amp;move=1&amp;b_id=' . $b_id . '&amp;weight=' . $b_weight . '&amp;pos=' . $b_position),
					'U_MOVE_DOWN' => append_sid(CMS_PAGE_CMS . '?mode=blocks' . $redirect_action . '&amp;' . $id_var_name . '=' . $id_var_value . '&amp;move=0&amp;b_id=' . $b_id . '&amp;weight=' . $b_weight . '&amp;pos=' . $b_position)
					)
				);
			}
		}
	}
	elseif(($id_var_value != 0) || ($action == 'editglobal'))
	{
		// To be removed when it is fixed...
		$cms_ajax = false;

		if(isset($_POST['action_update']))
		{
			$blocks_upd = array();
			$blocks_upd = $_POST['block'];
			$blocks_upd_n = sizeof($blocks_upd);
			$sql_no_gb = '';

			if ($action == 'editglobal')
			{
				$l_id_list = "'0'";
				$action_append = '&amp;action=editglobal';
				$sql_no_gb = " AND layout_special = '0'";
			}
			else
			{
				$l_id_list = "'" . $id_var_value . "'";
				$action_append = '';
			}

			if ($cms_ajax)
			{
				$sql = "SELECT bposition FROM " . CMS_BLOCK_POSITION_TABLE . " WHERE layout = " . $l_id_list . "";
				$result = $db->sql_query($sql);
				$l_rows = $db->sql_fetchrowset($result);
				$db->sql_freeresult($result);
				$l_count = sizeof($l_rows);

				for($j = 0; $j < $l_count; $j++)
				{
					$bposition = $l_rows[$j]['bposition'];
					if(isset($_POST['list_' . $bposition]))
					{
						$block_debug = str_replace('list_' . $bposition . '[]=id', '', $_POST['list_' . $bposition]);
						$block_debug_array = explode("&", $block_debug);

						for ($i = 0; $i < sizeof($block_debug_array); $i++)
						{
							$sql = "UPDATE " . CMS_BLOCKS_TABLE . "
								SET weight = '" . $i . "', bposition = '" . $db->sql_escape($bposition) . "'
								WHERE bid = '" . $block_debug_array[$i] . "'";
							$result = $db->sql_query($sql);
						}
					}
				}
				redirect(append_sid(CMS_PAGE_CMS . '?mode=blocks&' . $id_var_name . '=' . $id_var_value . '&updated=true'));
			}
			else
			{
				if (($mode == 'blocks') || ($action == 'editglobal'))
				{
					$b_rows = get_blocks_from_layouts(CMS_BLOCKS_TABLE, $block_layout_field, $l_id_list, $sql_no_gb);
					$b_count = !empty($b_rows) ? sizeof($b_rows) : 0;

					for($i = 0; $i < $b_count; $i++)
					{
						$b_active = empty($blocks_upd) ? 0 : (in_array($b_rows[$i]['bid'], $blocks_upd) ? 1 : 0);
						$sql = "UPDATE " . CMS_BLOCKS_TABLE . "
										SET active = '" . $b_active . "'
										WHERE bid = '" . $b_rows[$i]['bid'] . "'";
						$result = $db->sql_query($sql);
					}
					fix_weight_blocks($id_var_value, $table_name);
					$message = '<br /><br />' . $lang['Blocks_updated'] . '<br /><br />' . sprintf($lang['Click_return_blocksadmin'], '<a href="' . append_sid(CMS_PAGE_CMS . '?mode=' . $mode . '&amp;' . $id_var_name . '=' . $id_var_value . $action_append) . '">', '</a>') . '<br />';
					message_die(GENERAL_MESSAGE, $message);
				}
			}
		}

		$template_file = ($cms_ajax) ? 'ajax/cms_ajax_blocks_list_body.tpl' : 'cms_blocks_list_body.tpl';
		$template_to_parse = CMS_TPL . $template_file;
		$template->assign_var('CMS_PAGE_TITLE', $lang['Blocks_Title']);

		$move = (isset($_GET['move'])) ? $_GET['move'] : -1;

		if(($mode == 'blocks') && (($move == '0') || ($move == '1')))
		{
			$b_weight = (isset($_GET['weight'])) ? $_GET['weight'] : 0;
			$b_position = (isset($_GET['pos'])) ? $_GET['pos'] : 0;
			$gb_pos = array('gh', 'gf', 'gt', 'gb', 'gl', 'gr', 'hh', 'hl', 'hc', 'fc', 'fr', 'ff');
			if(in_array($b_position, $gb_pos))
			{
				if ($id_var_name == 'l_id')
				{
					$id_var_value = 0;
					$l_id = 0;
					$layout_value = 0;
				}
			}

			if((($move == '1') && ($b_weight != '1') && ($b_weight != '0')) || (($move == '0') && ($b_weight != '0')))
			{
				if($move == '1')
				{
					$temp = $b_weight - 1;
				}
				else
				{
					$temp = $b_weight + 1;
				}
				$sql = "UPDATE " . CMS_BLOCKS_TABLE . " SET weight = '" . $b_weight . "' WHERE " . $block_layout_field . " = '" . $id_var_value . "' AND weight = '" . $temp . "' AND bposition = '" . $b_position . "'";
				$result = $db->sql_query($sql);
				$sql = "UPDATE " . CMS_BLOCKS_TABLE . " SET weight = '" . $temp . "' WHERE bid = '" . $b_id . "'";
				$result = $db->sql_query($sql);
				fix_weight_blocks($id_var_value, $table_name);
			}
		}

		$l_row = get_layout_name($table_name, $field_name, $id_var_value);
		$l_name = $l_row['name'];
		$l_filename = $l_row['filename'];

		if ($action == 'editglobal')
		{
			$page_url = append_sid(CMS_PAGE_HOME);
			$l_id_list = "'0'";
		}
		else
		{
			if ($id_var_name == 'l_id')
			{
				if (($l_filename != '') && file_exists($l_filename))
				{
					$page_url = append_sid($l_filename);
				}
				else
				{
					$page_url = (substr($l_name, strlen($l_name) - (strlen(PHP_EXT) + 1), (strlen(PHP_EXT) + 1)) == ('.' . PHP_EXT)) ? append_sid($l_name) : append_sid(CMS_PAGE_HOME . '?page=' . $id_var_value);
				}
			}
			else
			{
				$page_url = append_sid($l_filename);
			}
			$l_id_list = "'" . $id_var_value . "'";
		}

		if ($id_var_name == 'l_id')
		{
			$l_name = ($l_name == '') ? $lang['Portal'] : $l_name;
		}
		else
		{
			$l_name = $l_filename;
		}

		if (($id_var_name == 'l_id') && ($id_var_value > 0))
		{
			$template->assign_block_vars('duplicate_switch', array());
		}

		if (($cms_ajax) && ($is_updated == true))
		{
			$template->assign_block_vars('blocks_updated', array());
		}

		$template->assign_vars(array(
			'L_BLOCKS_TITLE' => $lang['Blocks_Title'],
			'L_BLOCKS_TEXT' => $lang['Blocks_Explain'],
			'L_B_TITLE' => $lang['B_Title'],
			'L_B_POSITION' => $lang['B_Position'],
			'L_B_ACTIVE' => $lang['B_Active'],
			'L_B_DISPLAY' => $lang['B_Display'],
			'L_B_TYPE' => $lang['B_Type'],
			'L_B_LAYOUT' => $lang['B_Layout'],
			'L_B_LAYOUT_EDIT' => $lang['B_Layout_Edit'],
			'L_B_PAGE' => $lang['B_Page'],
			'L_B_VIEW_BY' => $lang['B_View'],
			'L_B_BORDER' => $lang['B_Border'],
			'L_B_TITLEBAR' => $lang['B_Titlebar'],
			'L_B_LOCAL' => $lang['B_Local'],
			'L_B_BACKGROUND' => $lang['B_Background'],
			'L_B_GROUPS' => $lang['B_Groups'],
			'L_ACTION' => $lang['Action'],
			'L_BLOCKS_ADD' => $lang['B_Add'],
			'L_BLOCKS_DUPLICATE' => $lang['B_Duplicate'],
			'L_BLOCKS_UPDATE' => $lang['B_Update'],
			'L_CONFIGURE_BLOCKS' => $lang['CMS_Configure_Blocks'],
			'L_EDIT' => $lang['CMS_Edit'],
			'L_DELETE' => $lang['CSM_Delete'],
			'L_PREVIEW' => $lang['CMS_Preview'],
			'L_MOVE_UP' => $lang['B_Move_Up'],
			'L_MOVE_DOWN' => $lang['B_Move_Down'],

			'LAYOUT_NAME' => $l_name,
			'PAGE_URL' => $page_url,
			'PAGE' => strval($id_var_value),
			'U_LAYOUT_EDIT' => (($block_layout_field == 'layout') ? append_sid(CMS_PAGE_CMS . '?mode=layouts&amp;action=edit' . '&amp;' . $id_var_name . '=' . $id_var_value) : ''),

			'S_BLOCKS_ACTION' => append_sid(CMS_PAGE_CMS),
			'S_HIDDEN_FIELDS' => $s_hidden_fields
			)
		);

		if($cms_ajax)
		{
			$sql = "SELECT bposition, pkey FROM " . CMS_BLOCK_POSITION_TABLE . " WHERE layout = " . $id_var_value . "";
			$result = $db->sql_query($sql);
			$l_rows = $db->sql_fetchrowset($result);
			$db->sql_freeresult($result);
			$l_count = sizeof($l_rows);

			$sql = "SELECT template FROM " . CMS_LAYOUT_TABLE . " WHERE lid = " . $l_id_list . "";
			$layout_result = $db->sql_query($sql, 0, 'cms_', CMS_CACHE_FOLDER);
			$layout_row = $db->sql_fetchrow($layout_result);
			$layout_type = $layout_row['template'];

			$template->set_filenames(array('layout_blocks' => 'layout/' . $layout_type));
			$template->assign_var('CMS_PAGE_TITLE', $lang['CMS_Pages']);

			$extra_containment = '';

			for($j = 0; $j < $l_count; $j++)
			{
				$sep = ($j == 0) ? '': ',';
				$b_position_array .= $sep . "'" . $l_rows[$j]['bposition'] . "'";
				$containment .= $sep . '"list_' . $l_rows[$j]['bposition'] . '"';
			}

			for($j = 0; $j < $l_count; $j++)
			{
				$sql = "SELECT bid FROM " . CMS_BLOCKS_TABLE . " WHERE layout = " . $l_id_list . " AND bposition = '" . $l_rows[$j]['bposition'] . "' ORDER BY weight";
				$result = $db->sql_query($sql);
				$b_rows = $db->sql_fetchrowset($result);
				$db->sql_freeresult($result);

				$b_count = empty($b_rows) ? 0 : sizeof($b_rows);
				$b_position_l = !empty($lang['cms_pos_' . $l_rows[$j]['pkey']]) ? $lang['cms_pos_' . $l_rows[$j]['pkey']] : $l_rows[$j]['pkey'];

				if ($b_count > 0)
				{
					for($i = 0; $i < $b_count; $i++)
					{
						$b_id = $b_rows[$i]['bid'];

						$redirect_action = '&amp;action=list';
						$output_block = make_cms_block($id_var_value, $b_id, $i, $b_count, $b_position_l, false, $cms_type);

						if ($output_block !== false)
						{
							$template->assign_block_vars($l_rows[$j]['pkey'] . '_blocks_row', array(
								'CMS_BLOCK' => $output_block,
								'OUTPUT' => $output_block,
								)
							);
						}
					}
				}
				else
				{
					$output_block = '<div class="sortable-list-div">' . $b_position_l;
					$output_block .= '<ul class="sortable-list" id="list_' . $l_rows[$j]['bposition'] . '"><li></li></ul></div>';

					$template->assign_block_vars($l_rows[$j]['pkey'] . '_blocks_row', array(
						'CMS_BLOCK' => $output_block,
						'OUTPUT' => $output_block,
						)
					);
				}

				$template->assign_var('LAYOUT_BLOCKS', $ip_cms->cms_assign_var_from_handle($template, 'layout_blocks'));

				$template->assign_block_vars('drop_blocks', array(
					'BPOSITION' => $l_rows[$j]['bposition'],
					)
				);

			}

			$sql = "SELECT bid, bposition FROM " . CMS_BLOCKS_TABLE . " WHERE layout = " . $l_id_list . " AND bposition NOT IN (" . $b_position_array . ") ORDER BY weight";
			$result = $db->sql_query($sql);
			$b_rows = $db->sql_fetchrowset($result);
			$db->sql_freeresult($result);

			$b_count = empty($b_rows) ? 0 : sizeof($b_rows);
			$invalid_position = array();
			$invalid_position_count = 0;

			if ($b_count > 0)
			{
				$template->set_filenames(array('invalid_blocks' => CMS_TPL . 'cms_invalid_blocks.tpl'));
				$template->assign_var('CMS_PAGE_TITLE', $lang['CMS_Pages']);

				for($i = 0; $i < $b_count; $i++)
				{
					if ($action != 'editglobal')
					{
						if (!in_array($b_rows[$i]['bposition'], $invalid_position))
						{
							$invalid_position[$invalid_position_count] = $b_rows[$i]['bposition'];
							$invalid_position_count++;
						}

						$b_id = $b_rows[$i]['bid'];

						$output_block = make_cms_block($id_var_value, $b_id, $i, $b_count, $lang['INVALID_BLOCKS'], true, $cms_type);

						if ($output_block !== false)
						{
							$template->assign_block_vars('invalid_blocks_row', array(
								'CMS_BLOCK' => $output_block,
								'OUTPUT' => $output_block,
								)
							);
						}

					}
				}
				$template->assign_var('INVALID_BLOCKS', $ip_cms->cms_assign_var_from_handle($template, 'invalid_blocks'));
			}

			for($i = 0; $i < $invalid_position_count; $i++)
			{
				$extra_containment .= ',"list_' . $invalid_position[$i] . '"';
				$template->assign_block_vars('drop_blocks', array(
					'BPOSITION' => $invalid_position[$i],
					)
				);
			}
			$template->assign_vars(array(
				'CONTAINMENT' => $containment . $extra_containment,
				)
			);
		}
		else
		{
			if (($mode == 'blocks') || ($action == 'editglobal'))
			{
				$is_auth = get_layout_edit_auth($table_name, $field_name, $id_var_value);
				if (!$is_auth)
				{
					message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
				}

				$b_rows = get_blocks_from_layouts(CMS_BLOCKS_TABLE, $block_layout_field, $l_id_list, '');
				$b_count = !empty($b_rows) ? sizeof($b_rows) : 0;

				// Reassign $l_id_list
				if ($id_var_name == 'l_id')
				{
					$l_id_list = $id_var_value . "', '0";
				}
				else
				{
					$l_id_list = '0';
				}

				$position = get_blocks_positions_layout(CMS_BLOCK_POSITION_TABLE, $l_id_list);

				if ($b_count > 0)
				{
					$else_counter = 0;
					for($i = 0; $i < $b_count; $i++)
					{
						if (($b_rows[$i]['layout_special'] != 0) && ($action == 'editglobal'))
						{
						}
						else
						{
							$b_id = $b_rows[$i]['bid'];
							$b_weight = $b_rows[$i]['weight'];
							$b_position = $b_rows[$i]['bposition'];
							$b_position_l = !empty($lang['cms_pos_' . $position[$b_position]]) ? $lang['cms_pos_' . $position[$b_position]] : $row['pkey'];

							$row_class = (!($else_counter % 2)) ? $theme['td_class2'] : $theme['td_class1'];
							$else_counter++;

							switch ($b_rows[$i]['view'])
							{
								case '0':
									$b_view = $lang['B_All'];
									break;
								case '1':
									$b_view = $lang['B_Guests'];
									break;
								case '2':
									$b_view = $lang['B_Reg'];
									break;
								case '3':
									$b_view = $lang['B_Mod'];
									break;
								case '4':
									$b_view = $lang['B_Admin'];
									break;
							}

							if(!empty($b_rows[$i]['groups']))
							{
								$groups = get_groups_names($b_rows[$i]['groups']);
							}
							else
							{
								$groups = $lang['B_All'];
							}

							if (($l_id == 0) && ($id_var_name == 'l_id'))
							{
								$redirect_action = '&amp;action=editglobal';
							}
							else
							{
								$redirect_action = '&amp;action=list';
							}

							$template->assign_block_vars('blocks', array(
								'ROW_CLASS' => $row_class,
								'TITLE' => $b_rows[$i]['title'],
								'BLOCK_CB_ID' => $b_rows[$i]['bid'],
								//'POSITION' => $position[$b_position],
								'POSITION' => $b_position_l,
								'L_POSITION' => $b_position_l,
								'ACTIVE' => ($b_rows[$i]['active']) ? $lang['Yes'] : $lang['No'],
								'BLOCK_CHECKED' => ($b_rows[$i]['active']) ? ' checked="checked"' : '',
								'TYPE' => (empty($b_rows[$i]['blockfile'])) ? (($b_rows[$i]['type']) ? $lang['B_BBCode'] : $lang['B_HTML']) : '&nbsp;',
								'BORDER' => ($b_rows[$i]['border']) ? $lang['Yes'] : $lang['No'],
								'TITLEBAR' => ($b_rows[$i]['titlebar']) ? $lang['Yes'] : $lang['No'],
								'LOCAL' => ($b_rows[$i]['local']) ? $lang['Yes'] : $lang['No'],
								'BACKGROUND' => ($b_rows[$i]['background']) ? $lang['Yes'] : $lang['No'],
								'GROUPS' => $groups,
								'CONTENT' => (empty($b_rows[$i]['blockfile'])) ? $lang['B_Text'] : $lang['B_File'],
								'VIEW' => $b_view,

								'U_EDIT' => append_sid(CMS_PAGE_CMS . '?mode=' . $mode . '&amp;action=edit&amp;' . $id_var_name . '=' . $id_var_value . '&amp;b_id=' . $b_id),
								'U_DELETE' => append_sid(CMS_PAGE_CMS . '?mode=' . $mode . '&amp;action=delete&amp;' . $id_var_name . '=' . $id_var_value . '&amp;b_id=' . $b_id),
								'U_MOVE_UP' => append_sid(CMS_PAGE_CMS . '?mode=' . $mode . $redirect_action . '&amp;' . $id_var_name . '=' . $id_var_value . '&amp;move=1&amp;b_id=' . $b_id . '&amp;weight=' . $b_weight . '&amp;pos=' . $b_position),
								'U_MOVE_DOWN' => append_sid(CMS_PAGE_CMS . '?mode=' . $mode . $redirect_action . '&amp;' . $id_var_name . '=' . $id_var_value . '&amp;move=0&amp;b_id=' . $b_id . '&amp;weight=' . $b_weight . '&amp;pos=' . $b_position)
								)
							);
						}
					}
				}
				else
				{
					$template->assign_var('S_NO_BLOCKS', true);
				}
			}
		}
	}
	else
	{
		message_die(GENERAL_MESSAGE, $lang['No_layout_selected']);
	}
}

if (($mode == 'layouts_special') || ($mode == 'layouts'))
{
	$id_var_name = 'l_id';
	$id_var_value = $l_id;
	$table_name = CMS_LAYOUT_TABLE;
	$field_name = 'lid';
	$block_layout_field = 'layout';
	$mode_layout_name = 'layouts';
	$mode_blocks_name = 'blocks';
	$is_layout_special = false;
	if ($mode == 'layouts_special')
	{
		$id_var_name = 'ls_id';
		$id_var_value = $ls_id;
		$table_name = CMS_LAYOUT_SPECIAL_TABLE;
		$field_name = 'lsid';
		$block_layout_field = 'layout_special';
		$mode_layout_name = 'layouts_special';
		$mode_blocks_name = 'blocks';
		$is_layout_special = true;
	}
	/*
	elseif ($mode == 'layouts_adv')
	{
		$mode_blocks_name = 'blocks_adv';
	}
	*/

	$s_hidden_fields = '';
	$s_append_url = '';
	$s_hidden_fields .= '<input type="hidden" name="mode" value="' . $mode . '" />';
	$s_append_url .= '?mode=' . $mode;
	$s_hidden_fields .= '<input type="hidden" name="action" value="' . $action . '" />';
	$s_append_url .= '&amp;action=' . $action;

	if($id_var_value != false)
	{
		$s_hidden_fields .= '<input type="hidden" name="' . $id_var_name . '" value="' . $id_var_value . '" />';
		$s_append_url .= '&amp;' . $id_var_name . '=' . $id_var_value;
	}
	else
	{
		$id_var_value = 0;
	}

	if(($action == 'edit') || ($action == 'add'))
	{
		$template_file = ($cms_ajax) ? 'ajax/cms_ajax_layout_edit_body.tpl' : 'cms_layout_edit_body.tpl';
		$template_to_parse = CMS_TPL . $template_file;
		$template->assign_var('CMS_PAGE_TITLE', $lang['CMS_Pages']);

		$l_info = array();
		if(($action == 'edit') && empty($id_var_value))
		{
			message_die(GENERAL_MESSAGE, $lang['No_layout_selected']);
		}

		if($action == 'edit')
		{
			$l_info = get_layout_info($table_name, $field_name, $id_var_value);
			$s_hidden_fields .= '<input type="hidden" name="filename_old" value="' . $l_info['filename'] . '" />';
		}

		if (!$is_layout_special)
		{
			if (file_exists('testing_write_access_permissions.test'))
			{
				@unlink('testing_write_access_permissions.test');
			}
			$write_test = @copy('index_empty.' . PHP_EXT, 'testing_write_access_permissions.test');
			if (file_exists('testing_write_access_permissions.test'))
			{
				@chmod('testing_write_access_permissions.test', 0777);
				@unlink('testing_write_access_permissions.test');
			}
			if ($write_test)
			{
				$file_creation_auth = $lang['CMS_Filename_Explain_OK'];
			}
			else
			{
				$file_creation_auth = $lang['CMS_Filename_Explain_NO'];
			}

			$l_info['page_id'] = '';
			$template_name = 'default';
			$template_dir = IP_ROOT_PATH . '/templates/' . $template_name . '/layout';

			if ($cms_ajax)
			{
				$template->assign_var('S_LAYOUT_ADV', true);
				$layout_details = get_layouts_details($template_dir, '.tpl', $common_cms_template, 'template', $cms_type);
				for ($i = 0; $i < sizeof($layout_details); $i++)
				{
					$template->assign_block_vars('layouts', array(
						'LAYOUT_IMG' => $layout_details[$i]['img'],
						'LAYOUT_RADIO' => $layout_details[$i]['file']
						)
					);
				}
			}
			else
			{
				$template->assign_var('S_LAYOUT_ADV', false);
				$layout_details = get_layouts_details_select($template_dir, '.tpl');
				$template->assign_vars(array(
					'TEMPLATE' => $layout_details,
					)
				);
			}

			$select_name = 'view';
			$default = empty($l_info['view']) ? 0 : $l_info['view'];
			$options_array = array(0, 1, 2, 3, 4);
			$options_langs_array = array($lang['B_All'], $lang['B_Guests'], $lang['B_Reg'], $lang['B_Mod'], $lang['B_Admin']);
			$select_js = '';
			$view = $class_form->build_select_box($select_name, $default, $options_array, $options_langs_array, $select_js);

			$select_name = 'edit_auth';
			$default = empty($l_info['edit_auth']) ? 0 : $l_info['edit_auth'];
			/*
			$options_array = array(0, 1, 2, 3, 4, 5);
			$options_langs_array = array($lang['CMS_Guest'], $lang['CMS_Reg'], $lang['CMS_VIP'], $lang['CMS_Publisher'], $lang['CMS_Reviewer'], $lang['CMS_Content_Manager']);
			*/
			$options_array = array(3, 4, 5);
			$options_langs_array = array($lang['CMS_Publisher'], $lang['CMS_Reviewer'], $lang['CMS_Content_Manager']);
			$select_js = '';
			$edit_auth = $class_form->build_select_box($select_name, $default, $options_array, $options_langs_array, $select_js);

			$group = (!empty($l_info['groups'])) ? get_all_usergroups($l_info['groups']) : get_all_usergroups('');
			if(empty($group))
			{
				$group = '&nbsp;&nbsp;' . $lang['None'];
			}
		}
		else
		{
			if(($action == 'edit') && $l_info['locked'])
			{
				message_die(GENERAL_ERROR, $lang['Not_Authorized']);
			}

			$edit_auth = '';
			$group = '';
			$default = empty($l_info['view']) ? 0 : $l_info['view'];
			$view = auth_select('view', $default);
		}

		$template->assign_vars(array(
			'L_CMS_PAGES' => $lang['CMS_Pages'],
			'L_CMS_ID' => $lang['CMS_ID'],
			'L_CMS_ACTIONS' => $lang['CMS_Actions'],
			'L_CMS_LAYOUT' => $lang['CMS_Layout'],
			'L_CMS_NAME' => $lang['CMS_Name'],
			'L_CMS_FILENAME' => $lang['CMS_Filename'],
			'L_CMS_FILENAME_EXPLAIN' => $lang['CMS_Filename_Explain'],
			'L_CMS_FILENAME_AUTH' => (empty($file_creation_auth) ? '' : $file_creation_auth),
			'L_CMS_TEMPLATE' => $lang['CMS_Template'],
			'L_LAYOUT_TITLE' => $lang['Layout_Title'],
			'L_LAYOUT_TEXT' => $lang['Layout_Explain'],
			'L_LAYOUT_NAME' => $lang['Layout_Name'],
			'L_LAYOUT_TEMPLATE' => $lang['Layout_Template'],
			'L_LAYOUT_GLOBAL_BLOCKS' => $lang['Layout_Global_Blocks'],
			'L_LAYOUT_PAGE_NAV' => $lang['Layout_Page_Nav'],
			'L_LAYOUT_VIEW' => $lang['Layout_View'],
			'L_LAYOUT_GROUPS' => $lang['B_Groups'],
			'L_PERMISSIONS' => $lang['Layout_Edit_Perm'],
			'L_YES' => $lang['Yes'],
			'L_NO' => $lang['No'],
			'L_ENABLED' => $lang['Enabled'],
			'L_DISABLED' => $lang['Disabled'],
			'L_EDIT_LAYOUT' => $lang['Layout_Edit'],
			'L_SUBMIT' => $lang['Submit'],

			'NAME' => (empty($l_info['name']) ? '' : $l_info['name']),
			'FILENAME' => (empty($l_info['filename']) ? '' : $l_info['filename']),
			'PAGE_ID' => (empty($l_info['page_id']) ? '' : $l_info['page_id']),
			'TEMPLATE' => $layout_details,
			'VIEW' => $view,
			'EDIT_AUTH' => $edit_auth,
			'GROUPS' => $group,
			'GLOBAL_BLOCKS' => ((!empty($l_info['global_blocks']) && $l_info['global_blocks']) ? 'checked="checked"' : ''),
			'NOT_GLOBAL_BLOCKS' => (empty($l_info['global_blocks'])) ? 'checked="checked"' : '',
			'PAGE_NAV' => ((!empty($l_info['page_nav']) && $l_info['page_nav']) ? 'checked="checked"' : ''),
			'NOT_PAGE_NAV' => (empty($l_info['page_nav'])) ? 'checked="checked"' : '',

			'S_LAYOUT_SPECIAL' => $is_layout_special,
			'S_LAYOUT_ACTION' => append_sid(CMS_PAGE_CMS . $s_append_url),
			'S_HIDDEN_FIELDS' => $s_hidden_fields
			)
		);
	}
	elseif($action == 'save')
	{
		$l_name = request_post_var('name', '', true);
		$l_page_id = preg_replace('/[^A-Za-z0-9_]+/', '', strtolower(request_post_var('page_id', '', true)));
		$l_locked = request_post_var('locked', 0);
		$l_filename = request_post_var('filename', '', true);
		$l_filename_old = request_post_var('filename_old', '', true);
		$l_template = request_post_var('template', '', true);
		$l_global_blocks = request_post_var('template', 0);
		$l_page_nav = request_post_var('page_nav', 0);
		$l_view = request_post_var('view', 0);
		$l_edit_auth = request_post_var('edit_auth', 0);

		if (!$is_layout_special)
		{
			$max_group_id = get_max_group_id();
			$l_group = '';
			$not_first = false;
			for($i = 1; $i <= $max_group_id; $i++)
			{
				if(isset($_POST['group' . strval($i)]))
				{
					if($not_first)
					{
						$l_group .= ',' . strval($i);
					}
					else
					{
						$l_group .= strval($i);
						$not_first = true;
					}
				}
			}

			if(($l_name == '') || ($l_template == ''))
			{
				message_die(GENERAL_MESSAGE, $lang['Must_enter_layout']);
			}
		}
		else
		{
			if(($l_name == '') || ($l_page_id == '') || ($l_filename == ''))
			{
				message_die(GENERAL_MESSAGE, $lang['CMS_MUST_FILL_ALL_FIELDS']);
			}
		}

		if($id_var_value != 0)
		{
			if (!$is_layout_special)
			{
				if ($l_filename_old != $l_filename)
				{
					@unlink($l_filename_old);

					if (substr($l_filename, strlen($l_filename) - (strlen(PHP_EXT) + 1), (strlen(PHP_EXT) + 1)) == ('.' . PHP_EXT))
					{
						$l_filename = preg_replace('/[^A-Za-z0-9_]+/', '', substr(strtolower($l_filename), 0, strlen($l_filename) - (strlen(PHP_EXT) + 1))) . ('.' . PHP_EXT);
						if (file_exists($l_filename))
						{
							message_die(GENERAL_MESSAGE, $lang['CMS_FileAlreadyExists']);
						}
						else
						{
							$creation_success = @copy('index_empty.' . PHP_EXT, $l_filename);
							if ($creation_success)
							{
								@chmod($l_filename, 0755);
								$message = $lang['CMS_FileCreationSuccess'] . '<br /><br />';
							}
							else
							{
								//message_die(GENERAL_MESSAGE, $lang['CMS_FileCreationError']);
								$message = $lang['CMS_FileCreationError'] . '<br />' . $lang['CMS_FileCreationManual'] . '<br /><br />';
							}
						}
					}
				}

				$sql = "UPDATE " . $table_name . "
					SET name = '" . $db->sql_escape($l_name) . "',
					filename = '" . $db->sql_escape($l_filename) . "',
					template = '" . $db->sql_escape($l_template) . "',
					global_blocks = " . $l_global_blocks . ",
					page_nav = " . $l_page_nav . ",
					view = " . $l_view . ",
					edit_auth = " . $l_edit_auth . ",
					groups = '" . $l_group . "'
					WHERE " . $field_name . " = " . $id_var_value;
				$result = $db->sql_query($sql);
				$message .= $lang['Layout_updated'];

				$template_name = 'default';

				if(file_exists(IP_ROOT_PATH . '/templates/' . $template_name . '/layout/' . str_replace('.tpl', '.cfg', $l_template)))
				{
					include(IP_ROOT_PATH . '/templates/' . $template_name . '/layout/' . str_replace('.tpl', '.cfg', $l_template));

					$sql_test = "SELECT * FROM " . CMS_BLOCK_POSITION_TABLE . " WHERE layout = '" . $id_var_value . "'";
					$result_test = $db->sql_query($sql_test);

					while ($row_test = $db->sql_fetchrow($result_test))
					{
						$bp_found = false;
						for($i = 0; $i < $layout_count_positions; $i++)
						{
							if (($row_test['bposition'] == $db->sql_escape($layout_block_positions[$i][1])) && ($row_test['pkey'] == $db->sql_escape($layout_block_positions[$i][0])))
							{
								$bp_found = true;
							}
						}
						if ($bp_found == false)
						{
							$sql = "DELETE FROM " . CMS_BLOCK_POSITION_TABLE . "
								WHERE layout = '" . $id_var_value . "'
									AND bposition = '" . $row_test['bposition'] . "'";
							$result = $db->sql_query($sql);
						}
					}
					$db->sql_freeresult($result);

					for($i = 0; $i < $layout_count_positions; $i++)
					{
						$sql_test = "SELECT * FROM " . CMS_BLOCK_POSITION_TABLE . "
							WHERE layout = '" . $id_var_value . "'
								AND bposition = '" . $layout_block_positions[$i][1] . "'
							LIMIT 1";
						$result_test = $db->sql_query($sql_test);

						if (!($db->sql_fetchrow($result_test)))
						{
							$sql = "INSERT INTO " . CMS_BLOCK_POSITION_TABLE . " (pkey, bposition, layout)
								VALUES ('" . $db->sql_escape($layout_block_positions[$i][0]) . "', '" . $db->sql_escape($layout_block_positions[$i][1]) . "', '" . $id_var_value . "')";
							$result = $db->sql_query($sql);
						}
					}
				}
			}
			else
			{
				if($l_locked)
				{
					message_die(GENERAL_ERROR, $lang['Not_Authorized']);
				}
				$sql = "UPDATE " . $table_name . "
					SET name = '" . $db->sql_escape($l_name) . "',
					page_id = '" . $db->sql_escape($l_page_id) . "',
					filename = '" . $db->sql_escape($l_filename) . "',
					global_blocks = " . $l_global_blocks . ",
					page_nav = " . $l_page_nav . ",
					view = " . $l_view . ",
					edit_auth = " . $l_edit_auth . ",
					groups = '" . $l_group . "'
					WHERE " . $field_name . " = " . $id_var_value;
				$result = $db->sql_query($sql);
				$message .= $lang['Layout_updated'];
			}
		}
		else
		{
			if (!$is_layout_special)
			{
				if ($l_filename_old != $l_filename)
				{
					@unlink($l_filename_old);
				}
				if (substr($l_filename, strlen($l_filename) - (strlen(PHP_EXT) + 1), (strlen(PHP_EXT) + 1)) == ('.' . PHP_EXT))
				{
					$l_filename = preg_replace('/[^A-Za-z0-9_]+/', '', substr(strtolower($l_filename), 0, strlen($l_filename) - (strlen(PHP_EXT) + 1))) . ('.' . PHP_EXT);
					if (file_exists($l_filename))
					{
						message_die(GENERAL_MESSAGE, $lang['CMS_FileAlreadyExists']);
					}
					else
					{
						$creation_success = @copy('index_empty.' . PHP_EXT, $l_filename);
						if ($creation_success)
						{
							@chmod($l_filename, 0755);
							$message = $lang['CMS_FileCreationSuccess'] . '<br /><br />';
						}
						else
						{
							//message_die(GENERAL_MESSAGE, $lang['CMS_FileCreationError']);
							$message = $lang['CMS_FileCreationError'] . '<br />' . $lang['CMS_FileCreationManual'] . '<br /><br />';
						}
					}
				}

				$sql = "INSERT INTO " . $table_name . " (name, filename, template, global_blocks, page_nav, view, edit_auth, groups)
					VALUES ('" . $db->sql_escape($l_name) . "', '" . $db->sql_escape($l_filename) . "', '" . $db->sql_escape($l_template) . "', " . $l_global_blocks . ", " . $l_page_nav . ", " . $l_view . ", " . $l_edit_auth . ", '" . $l_group . "')";
				$result = $db->sql_query($sql);
				$message .= $lang['Layout_added'];

				$template_name = 'default';

				if(file_exists(IP_ROOT_PATH . '/templates/' . $template_name . '/layout/' . str_replace('.tpl', '.cfg', $l_template)))
				{
					include(IP_ROOT_PATH . '/templates/' . $template_name . '/layout/' . str_replace('.tpl', '.cfg', $l_template));

					$layout_id = get_max_layout_id($table_name);

					for($i = 0; $i < $layout_count_positions; $i++)
					{
						$sql = "INSERT INTO " . CMS_BLOCK_POSITION_TABLE . " (pkey, bposition, layout)
							VALUES ('" . $db->sql_escape($layout_block_positions[$i][0]) . "', '" . $db->sql_escape($layout_block_positions[$i][1]) . "', '" . $layout_id . "')";
						$result = $db->sql_query($sql);
					}

					$message .= '<br /><br />' . $lang['Layout_BP_added'];
				}
			}
			else
			{
				$sql = "INSERT INTO " . $table_name . " (name, page_id, locked, filename, global_blocks, page_nav, view, edit_auth, groups)
					VALUES ('" . $db->sql_escape($l_name) . "', '" . $db->sql_escape($l_page_id) . "', 0, '" . $db->sql_escape($l_filename) . "', " . $l_global_blocks . ", " . $l_page_nav . ", " . $l_view . ", " . $l_edit_auth . ", '" . $l_group . "')";
				$result = $db->sql_query($sql);
				$message .= $lang['Layout_added'];
			}
		}

		$message .= '<br /><br />' . sprintf($lang['Click_return_layoutadmin'], '<a href="' . append_sid(CMS_PAGE_CMS . '?mode=' . $mode_layout_name) . '">', '</a>');
		$message .= '<br /><br />' . sprintf($lang['Click_return_blocksadmin'], '<a href="' . append_sid(CMS_PAGE_CMS . '?mode=blocks&amp;' . $id_var_name . '=' . (!empty($layout_id) ? $layout_id : $id_var_value)) . '">', '</a>');
		$message .= '<br /><br />';
		message_die(GENERAL_MESSAGE, $message);
	}
	elseif($action == 'delete')
	{
		if($is_layout_special)
		{
			$l_info = get_layout_info($table_name, $field_name, $id_var_value);
			if (!empty($l_info['locked']))
			{
				message_die(GENERAL_ERROR, $lang['Not_Authorized']);
			}
		}

		if(!isset($_POST['confirm']))
		{
			$s_hidden_fields = '';
			$s_hidden_fields .= '<input type="hidden" name="mode" value="' . $mode . '" />';
			$s_hidden_fields .= '<input type="hidden" name="' . $id_var_name . '" value="' . $id_var_value . '" />';
			$s_hidden_fields .= '<input type="hidden" name="action" value="' . $action . '" />';

			$template->assign_vars(array(
				'L_YES' => $lang['Yes'],
				'L_NO' => $lang['No'],
				'L_ENABLED' => $lang['Enabled'],
				'L_DISABLED' => $lang['Disabled'],

				'MESSAGE_TITLE' => $lang['Confirm'],
				'MESSAGE_TEXT' => $lang['Confirm_delete_item'],

				'S_CONFIRM_ACTION' => append_sid(CMS_PAGE_CMS . $s_append_url),
				'S_HIDDEN_FIELDS' => $s_hidden_fields
				)
			);
			full_page_generation(CMS_TPL . 'confirm_body.tpl', $lang['Confirm'], '', '');
		}
		else
		{
			if($id_var_value != 0)
			{
				if (!$is_layout_special)
				{
					if ((substr($l_filename, strlen($l_filename) - (strlen(PHP_EXT) + 1), (strlen(PHP_EXT) + 1)) == ('.' . PHP_EXT)) && (file_exists($l_filename)))
					{
						@unlink($l_filename);
					}

					delete_layout($table_name, CMS_BLOCK_POSITION_TABLE, $id_var_value);

					$sql_list = "SELECT * FROM " . CMS_BLOCKS_TABLE . " WHERE " . $block_layout_field . " = " . $id_var_value;
					$result_list = $db->sql_query($sql_list);
					while($b_row = $db->sql_fetchrow($result_list))
					{
						delete_block(CMS_BLOCKS_TABLE, $b_row['bid']);
					}
					$db->sql_freeresult($result_list);
				}
				else
				{
					delete_layout_special($table_name, $block_layout_field, $field_name, $id_var_value);
				}
			}
			else
			{
				message_die(GENERAL_MESSAGE, $lang['No_layout_selected']);
			}

			$message = $lang['Layout_removed'] . '<br /><br />' . sprintf($lang['Click_return_layoutadmin'], '<a href="' . append_sid(CMS_PAGE_CMS . '?mode=' . $mode_layout_name) . '">', '</a>') . '<br /><br />';

			message_die(GENERAL_MESSAGE, $message);
		}
	}
	elseif (($action == 'list') || ($action == false))
	{
		if(isset($_POST['action_update']))
		{
			$l_gb_checkbox = array();
			$l_gb_checkbox = $_POST['layout_gb'];
			$l_gb_checkbox_n = sizeof($l_gb_checkbox);

			$l_bc_checkbox = array();
			$l_bc_checkbox = $_POST['layout_bc'];
			$l_bc_checkbox_n = sizeof($l_bc_checkbox);

			$l_rows = get_layouts_list($table_name, $field_name);
			$l_count = sizeof($l_rows);

			for($i = 0; $i < $l_count; $i++)
			{
				$view_value = isset($_POST['auth_view_' . $l_rows[$i][$field_name]]) ? intval($_POST['auth_view_' . $l_rows[$i][$field_name]]) : 0;
				$gb_value = in_array($l_rows[$i][$field_name], $l_gb_checkbox) ? 1 : 0;
				$bc_value = in_array($l_rows[$i][$field_name], $l_bc_checkbox) ? 1 : 0;
				$sql = "UPDATE " . $table_name . " SET view = " . $view_value . ", global_blocks = " . $gb_value . ", page_nav = " . $bc_value . " WHERE " . $field_name . " = " . $l_rows[$i][$field_name];
				$result = $db->sql_query($sql);
			}
			redirect(append_sid(CMS_PAGE_CMS . '?mode=' . $mode_layout_name . '&updated=true'));
		}

		$template_to_parse = CMS_TPL . 'cms_layout_list_body.tpl';
		$template->assign_var('CMS_PAGE_TITLE', $lang['CMS_Pages']);

		$template->assign_vars(array(
			'L_CMS_PAGES' => $lang['CMS_Pages'],
			'L_CMS_ID' => $lang['CMS_ID'],
			'L_CMS_ACTIONS' => $lang['CMS_Actions'],
			'L_CMS_BLOCKS' => $lang['CMS_Blocks'],
			'L_CMS_LAYOUT' => $lang['CMS_Layout'],
			'L_CMS_NAME' => $lang['CMS_Name'],
			'L_CMS_FILENAME' => $lang['CMS_Filename'],
			'L_CMS_FILENAME_EXPLAIN' => $lang['CMS_Filename_Explain'],
			'L_CMS_TEMPLATE' => $lang['CMS_Template'],
			'L_CMS_PERMISSION' => $lang['CMS_Permissions'],
			'L_CMS_GLOBAL_BLOCKS' => $lang['CMS_GLOBAL_BLOCKS'],
			'L_CMS_BREADCRUMBS' => $lang['CMS_BREADCRUMBS'],
			'L_CHOOSE_LAYOUT' => $lang['Choose_Layout'],
			'L_CONFIGURE_BLOCKS' => $lang['CMS_Configure_Blocks'],
			'L_EDIT' => $lang['CMS_Edit'],
			'L_DELETE' => $lang['CSM_Delete'],
			'L_PREVIEW' => $lang['CMS_Preview'],
			'L_LAYOUT_ADD' => $lang['Layout_Add'],

			'L_LAYOUT_TITLE' => $is_layout_special ? $lang['CMS_STANDARD_PAGES'] : $lang['CMS_CUSTOM_PAGES'],
			'L_LAYOUT_TEXT' => $is_layout_special ? $lang['Layout_Special_Explain'] : $lang['Layout_Explain'],

			'S_LAYOUT_SPECIAL' => $is_layout_special,

			'S_LAYOUT_ACTION' => append_sid(CMS_PAGE_CMS),
			'S_HIDDEN_FIELDS' => $s_hidden_fields
			)
		);

		if ($is_updated)
		{
			$template->assign_var('CMS_CHANGES_SAVED', true);
		}

		$template->assign_block_vars('layout', array());

		$l_rows = get_layouts_list($table_name, $field_name);
		$l_count = sizeof($l_rows);

		$default_portal_id = 0;
		if (!$is_layout_special)
		{
			$sql = "SELECT config_value FROM " . CMS_CONFIG_TABLE . " WHERE bid = '0' AND config_name = 'default_portal'";
			$result = $db->sql_query($sql);
			$c_row = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			$default_portal_id = $c_row['config_value'];
		}

		for($i = 0; $i < $l_count; $i++)
		{
			$row_class = (!($i % 2)) ? $theme['td_class2'] : $theme['td_class1'];
			$lang_var = 'auth_view_' . $l_rows[$i]['name'];
			$layout_id = $l_rows[$i][$field_name];
			$layout_name = ($is_layout_special ? (isset($lang[$lang_var]) ? htmlspecialchars($lang[$lang_var]) : $l_rows[$i]['name']) : $l_rows[$i]['name']);
			$layout_filename = $l_rows[$i]['filename'];
			$layout_preview = ($is_layout_special ? (empty($layout_filename) ? '#' : append_sid($layout_filename)) : (empty($layout_filename) ? (CMS_PAGE_HOME . '?page=' . $layout_id) : append_sid($layout_filename)));
			$layout_locked = false;

			$select_name = 'auth_view_' . $layout_id;
			$default = $l_rows[$i]['view'];
			$options_array = array(0, 1, 2, 3, 4);
			$options_langs_array = array($lang['B_All'], $lang['B_Guests'], $lang['B_Reg'], $lang['B_Mod'], $lang['B_Admin']);
			$select_js = '';
			$auth_view_select_box = $class_form->build_select_box($select_name, $default, $options_array, $options_langs_array, $select_js);

			if ($is_layout_special)
			{
				$layout_locked = !empty($l_rows[$i]['locked']) ? true : false;
				$auth_view_select_box = auth_select('auth_view_' . $layout_id, $l_rows[$i]['view']);
			}

			$template->assign_block_vars('layout.l_row', array(
				'ROW_CLASS' => $row_class,
				'ROW_DEFAULT_STYLE' => ($layout_id == $default_portal_id) ? 'font-weight: bold;' : '',
				'LAYOUT_ID' => $layout_id,
				'LAYOUT_NAME' => $layout_name,
				'LAYOUT_FILENAME' => (empty($layout_filename) ? $lang['None'] : $layout_filename),
				'LAYOUT_BLOCKS' => count_blocks_in_layout(CMS_BLOCKS_TABLE, '\'' . $layout_id . '\'', $is_layout_special, true) . '/' . count_blocks_in_layout(CMS_BLOCKS_TABLE, '\'' . $layout_id . '\'', $is_layout_special, false),
				'LAYOUT_TEMPLATE' => $l_rows[$i]['template'],

				'LOCKED' => $layout_locked,
				'PAGE_AUTH' => $auth_view_select_box,
				'GB_CHECKED' => ($l_rows[$i]['global_blocks']) ? ' checked="checked"' : '',
				'BC_CHECKED' => ($l_rows[$i]['page_nav']) ? ' checked="checked"' : '',

				'U_PREVIEW_LAYOUT' => $layout_preview,
				'U_EDIT_LAYOUT' => append_sid(CMS_PAGE_CMS . '?mode=' . $mode . '&amp;' . $id_var_name . '=' . $layout_id . '&amp;action=edit'),
				'U_DELETE_LAYOUT' => append_sid(CMS_PAGE_CMS . '?mode=' . $mode . '&amp;' . $id_var_name . '=' . $layout_id . '&amp;action=delete'),
				'U_LAYOUT' => append_sid(CMS_PAGE_CMS . '?mode=' . $mode_blocks_name . '&amp;' . $id_var_name . '=' . $layout_id)
				)
			);
		}
	}
}

if($mode == 'config')
{
	$template_to_parse = CMS_TPL . 'cms_config_body.tpl';
	$template->assign_var('CMS_PAGE_TITLE', $lang['CMS_CONFIG']);

	// Pull all config data
	/*
	$sql = "SELECT * FROM " . CMS_BLOCK_VARIABLE_TABLE . " AS b RIGHT JOIN " . CMS_CONFIG_TABLE . " AS p
		USING (config_name)
		WHERE ((b.config_name IS NULL) OR (b.config_name IS NOT NULL))
			AND ((p.bid = 0))
		ORDER BY b.block, b.bvid, p.id";
	*/
	$sql = "SELECT * FROM " . CMS_BLOCK_VARIABLE_TABLE . " AS b, " . CMS_CONFIG_TABLE . " AS p
		WHERE (b.bid = 0)
			AND (p.bid = 0)
			AND (p.config_name = b.config_name)
		ORDER BY b.block, b.bvid, p.id";
	$result = $db->sql_query($sql);
	$controltype = array('1' => 'textbox', '2' => 'dropdown list', '3' => 'radio buttons', '4' => 'checkbox');
	while($row = $db->sql_fetchrow($result))
	{
		$cms_field = array();
		$cms_field = create_cms_field($row);
		//$cms_field = array_merge($cms_field, create_cms_field($row));

		$default_portal[$cms_field[$row['config_name']]['name']] = $cms_field[$row['config_name']]['value'];

		if($cms_field[$row['config_name']]['type'] == '4')
		{
			$new[$cms_field[$row['config_name']]['name']] = (isset($_POST[$cms_field[$row['config_name']]['name']])) ? '1' : '0';
		}
		else
		{
			$new[$cms_field[$row['config_name']]['name']] = (isset($_POST[$cms_field[$row['config_name']]['name']])) ? $_POST[$cms_field[$row['config_name']]['name']] : $default_portal[$cms_field[$row['config_name']]['name']];
		}

		if(isset($_POST['save']))
		{
			$sql = "UPDATE " . CMS_CONFIG_TABLE . " SET
				config_value = '" . $db->sql_escape($new[$cms_field[$row['config_name']]['name']]) . "'
				WHERE config_name = '" . $db->sql_escape($cms_field[$row['config_name']]['name']) . "'";
			$result = $db->sql_query($sql);
		}
		else
		{
			$is_block = ($cms_field[$row['config_name']]['block'] != '@Portal Config') ? 'block ' : '';
			$template->assign_block_vars('cms_block', array(
				'L_FIELD_LABEL' => $cms_field[$row['config_name']]['label'],
				'L_FIELD_SUBLABEL' => '<br /><br /><span class="gensmall">' . $cms_field[$row['config_name']]['sub_label'] . ' [ ' . str_replace("@", "", $cms_field[$row['config_name']]['block']) . ' ' . $is_block . ']</span>',
				'FIELD' => $cms_field[$row['config_name']]['output']
				)
			);
		}
	}
	$db->sql_freeresult($result);

	if(isset($_POST['save']))
	{
		$message = $lang['CMS_Config_updated'] . '<br /><br />' . sprintf($lang['CMS_Click_return_config'], '<a href="' . append_sid(CMS_PAGE_CMS . '?mode=config') . '">', '</a>') . '<br /><br />' . sprintf($lang['CMS_Click_return_cms'], '<a href="' . append_sid(CMS_PAGE_CMS) . '">', '</a>') . '<br /><br />';
		message_die(GENERAL_MESSAGE, $message);
	}

	$template->assign_vars(array(
		'S_CONFIG_ACTION' => append_sid(CMS_PAGE_CMS),
		'L_CONFIGURATION_TITLE' => $lang['CMS_CONFIG'],
		'L_CONFIGURATION_EXPLAIN' => $lang['Portal_Explain'],
		'L_GENERAL_CONFIG' => $lang['Portal_General_Config'],
		'L_SUBMIT' => $lang['Submit'],
		'L_RESET' => $lang['Reset']
		)
	);
}

if (($mode == false))
{
	$template_to_parse = CMS_TPL . 'cms_index_body.tpl';
	$template->assign_var('CMS_PAGE_TITLE', $lang['CMS_Pages']);
	$template->assign_vars(array(
		'L_CMS_PAGES' => $lang['CMS_Pages'],
		'L_CMS_ID' => $lang['CMS_ID'],
		'L_CMS_ACTIONS' => $lang['CMS_Actions'],
		'L_CMS_LAYOUT' => $lang['CMS_Layout'],
		'L_CMS_NAME' => $lang['CMS_Name'],
		'L_CMS_FILENAME' => $lang['CMS_Filename'],
		'L_CMS_FILENAME_EXPLAIN' => $lang['CMS_Filename_Explain'],
		'L_CMS_TEMPLATE' => $lang['CMS_Template'],
		'L_LAYOUT_TITLE' => $lang['Layout_Title'],
		'L_LAYOUT_TEXT' => $lang['Layout_Explain'],
		'L_CHOOSE_LAYOUT' => $lang['Choose_Layout'],
		'L_CONFIGURE_BLOCKS' => $lang['CMS_Configure_Blocks'],
		'L_EDIT' => $lang['CMS_Edit'],
		'L_DELETE' => $lang['CSM_Delete'],
		'L_PREVIEW' => $lang['CMS_Preview'],
		'L_LAYOUT_ADD' => $lang['Layout_Add'],
		'S_LAYOUT_ACTION' => append_sid(CMS_PAGE_CMS),
		'S_HIDDEN_FIELDS' => $s_hidden_fields
		)
	);
}

full_page_generation($template_to_parse, $lang['Home'], '', '');

?>