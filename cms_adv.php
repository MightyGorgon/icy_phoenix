<?php
/**
*
* @package Icy Phoenix
* @version $Id: cms_adv.php 8 2008-08-28 00:32:49Z Mighty Gorgon $
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

// CTracker_Ignore: File Checked By Human
define('IN_CMS', true);
define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_cms_admin.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_groups.' . PHP_EXT);

$css_temp = array('cms.css');
$js_temp =  array('js/cms.js', 'scriptaculous/unittest.js');

if(is_array($css_include))
{
	$css_include = array_merge($css_include, $css_temp);
}
else
{
	$css_include = $css_temp;
}
unset($css_temp);

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

$mode_array = array('blocks', 'config', 'layouts', 'smilies', 'userslist');
$mode = (!empty($_GET['mode']) ? $_GET['mode'] : (!empty($_POST['mode']) ? $_POST['mode'] : false));
$mode = (in_array($mode, $mode_array) ? $mode : false);

$action_array = array('add', 'delete', 'duplicate', 'edit', 'editglobal', 'list', 'save');
$action = (!empty($_GET['action']) ? $_GET['action'] : (!empty($_POST['action']) ? $_POST['action'] : false));
$action = (isset($_POST['add']) ? 'add' : $action);
$action = (isset($_POST['save']) ? 'save' : $action);
$action = (isset($_POST['action_duplicate']) ? 'duplicate' : $action);
$action = (in_array($action, $action_array) ? $action : false);

$preview_block = isset($_POST['preview']) ? true : false;

$l_id = (isset($_GET['l_id']) ? intval($_GET['l_id']) : (isset($_POST['l_id']) ? intval($_POST['l_id']) : false));
$l_id = ($l_id < 0) ? false : $l_id;

$b_id = (isset($_GET['b_id']) ? intval($_GET['b_id']) : (isset($_POST['b_id']) ? intval($_POST['b_id']) : false));
$b_id = ($b_id < 0) ? false : $b_id;

$bv_id = (isset($_GET['bv_id']) ? intval($_GET['bv_id']) : (isset($_POST['bv_id']) ? intval($_POST['bv_id']) : false));
$bv_id = ($bv_id < 0) ? false : $bv_id;

$cms_id = (isset($_GET['cms_id']) ? intval($_GET['cms_id']) : (isset($_POST['cms_id']) ? intval($_POST['cms_id']) : false));
$cms_id = ($cms_id < 0) ? false : $cms_id;

$cms_type_array = array('cms_standard', 'cms_users');
$cms_type = (!empty($_GET['cms_type']) ? $_GET['cms_type'] : (!empty($_POST['cms_type']) ? $_POST['cms_type'] : $cms_type_array[0]));
//$cms_type = (in_array($cms_type, $cms_type_array) ? $cms_type : false);
$cms_type = (in_array($cms_type, $cms_type_array) ? $cms_type : $cms_type_array[0]);

$is_updated = (isset($_GET['updated']) ? $_GET['updated'] : (isset($_POST['updated']) ? $_POST['updated'] : false));

$redirect_append = '';
if ($mode == 'blocks')
{
	if ($action == 'edit')
	{
		$redirect_append = '&mode=blocks&action=edit&l_id=' . $l_id . '&b_id=' . $b_id;
		if (($userdata['user_level'] != ADMIN) && ($userdata['user_cms_level'] < CMS_PUBLISHER))
		{
			message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
		}
	}
	else
	{
		if (($userdata['user_level'] != ADMIN) && ($userdata['user_cms_level'] < CMS_CONTENT_MANAGER))
		{
			message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
		}
	}
}
elseif ($mode == 'layouts')
{
	$redirect_append = '&mode=layouts&l_id=' . $l_id;
	if (($userdata['user_level'] != ADMIN) && ($userdata['user_cms_level'] < CMS_CONTENT_MANAGER))
	{
		message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
	}
}
else
{
	if ($userdata['user_level'] != ADMIN)
	{
		message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
	}
}

if (!$userdata['session_admin'])
{
	redirect(append_sid(LOGIN_MG . '?redirect=cms_adv.' . PHP_EXT . '&admin=1' . $redirect_append, true));
}

if(isset($_POST['block_reset']))
{
	redirect(append_sid('cms_adv.' . PHP_EXT . '?mode=blocks&action=list&l_id=' . $l_id, true));
}

if(isset($_POST['cancel']))
{
	redirect(append_sid('cms_adv.' . PHP_EXT, true));
}

if(isset($_POST['hascontent']))
{
	$block_content = (isset($_POST['blockfile'])) ? trim($_POST['blockfile']) : false;
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

include(IP_ROOT_PATH . 'includes/functions_post.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);
if(!file_exists(IP_ROOT_PATH . 'language/lang_' . $board_config['default_lang'] . '/lang_cms.' . PHP_EXT))
{
	$board_config['default_lang'] = 'english';
}
include_once(IP_ROOT_PATH . 'language/lang_' . $board_config['default_lang'] . '/lang_admin.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'language/lang_' . $board_config['default_lang'] . '/lang_cms.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'language/lang_' . $board_config['default_lang'] . '/lang_blocks.' . PHP_EXT);

if ($mode == 'smilies')
{
	generate_smilies('window');
	exit;
}

$page_title = $lang['CMS_ADV'];
$meta_description = '';
$meta_keywords = '';
$template->assign_vars(array('S_CMS_AUTH' => true));
include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);

$template->assign_vars(array(
	'S_CMS_STANDARD' => ($cms_type == 'cms_standard') ? true : false,
	'U_CMS_ADV_USERSLIST' => append_sid('cms_adv.' . PHP_EXT . '?mode=userslist'),
	'U_CMS_ADV_CUSTOM_PAGES' => append_sid('cms_adv.' . PHP_EXT . '?mode=layouts'),
	)
);

if ($board_config['cms_dock'] == true)
{
	$template->assign_block_vars('cms_dock_on', array());
}
else
{
	$template->assign_block_vars('cms_dock_off', array());
}

if ($cms_type == 'cms_standard')
{
	$pages_table_name = CMS_LAYOUT_TABLE;
	$blocks_table = CMS_BLOCKS_TABLE;
	$block_variable_table = CMS_BLOCK_VARIABLE_TABLE;
	$block_position_table = CMS_BLOCK_POSITION_TABLE;
	$config_table_name = CMS_CONFIG_TABLE;
	$block_dir = IP_ROOT_PATH . 'blocks';
	$extra_where = '';
	$template_name = get_template_name($board_config['default_style']);
	$common_cms_template = IP_ROOT_PATH . 'templates/common/cms/';
	$layout_dir = IP_ROOT_PATH . 'templates/' . $template_name . '/layout/';
	//$layout_path = 'templates/' . $template_name . '/layout/';
	$layout_path = 'layout/';
	$layout_extension = '.tpl';
	$type_append_url = '&cms_type=cms_standard';
}
else
{
	$pages_table_name = CMS_ADV_PAGES_TABLE;
	$blocks_table = CMS_ADV_BLOCKS_TABLE;
	$block_variable_table = CMS_ADV_BLOCK_VARIABLE_TABLE;
	$block_position_table = CMS_ADV_BLOCK_POSITION_TABLE;
	$config_table_name = CMS_ADV_CONFIG_TABLE;
	$block_dir = IP_ROOT_PATH . 'cms/blocks';
	$extra_where = ' WHERE cms_id = 0';
	$common_cms_template = IP_ROOT_PATH . STYLES_PATH . 'common/cms/';
	$layout_dir = IP_ROOT_PATH . STYLES_PATH . 'common/layouts/';
	$layout_path = '../../' . STYLES_PATH . 'common/layouts/';
	$layout_extension = '.html';
	$type_append_url = '';
}

if($mode == 'blocks')
{
	$id_var_name = 'l_id';
	$id_var_value = $l_id;
	$layout_value = $id_var_value;

	$s_hidden_fields = '<input type="hidden" name="mode" value="' . $mode . '" />';
	$s_append_url = '?mode=' . $mode;
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
		$message = isset($_POST['message']) ? $_POST['message'] : '';
		$message = stripslashes($message);

		$l_row = get_global_blocks_layout($pages_table_name, 'lid', $id_var_value);

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
				$b_info = get_block_info($blocks_table, $b_id);

				$b_info['bposition'] = (isset($_POST['bposition'])) ? trim($_POST['bposition']) : $b_info['bposition'];
				$position = get_block_positions($block_position_table, $l_id_list, $b_info['bposition']);

				$blocks = opendir($block_dir);

				$block_content_file_old = $b_info['blockfile'];
				$b_info['blockfile'] = (isset($_POST['blockfile'])) ? trim($_POST['blockfile']) : $b_info['blockfile'];
				$blockfile = '<option value="">-- ' . $lang['B_Text_Block'] . ' --</option>';
				while ($file = readdir($blocks))
				{
					$pos = strpos($file, 'blocks_imp_');
					if (($pos == 0) && ($pos !== false))
					{
						$pos = strpos($file, '.' . PHP_EXT);
						if ($pos !== false)
						{
							$temp = ereg_replace('\.' . PHP_EXT, '', $file);
							$temp1 = ereg_replace('blocks_imp_', '', $temp);
							$temp2 = !empty($lang['cms_block_' . $temp1]) ? ('&nbsp;[' . $lang['cms_block_' . $temp1] . ']') : '';
							$temp1 = ereg_replace('_', ' ', $temp1);
							$blockfile .= '<option value="' . $temp . '" ';
							if($b_info['blockfile'] == $temp)
							{
								$blockfile .= 'selected="selected"';
								$block_content_file = $temp;
							}
							$blockfile .= '>' . $temp1 . $temp2 . '</option>';
						}
					}
				}

				$view_array = array(
					'0' => $lang['B_All'],
					'1' => $lang['B_Guests'],
					'2' => $lang['B_Reg'],
					'3' => $lang['B_Mod'],
					'4' => $lang['B_Admin']
				);

				$view ='';
				$b_info['view'] = (isset($_POST['view'])) ? trim($_POST['view']) : $b_info['view'];
				for ($i = 0; $i < count($view_array); $i++)
				{
					$view .= '<option value="' . $i .'" ';
					if($b_info['view'] == $i)
					{
						$view .= 'selected="selected"';
						$block_view = $i;
					}
					$view .= '>' . $view_array[$i] . '</option>';
				}

				$message = isset($_POST['message']) ? $_POST['message'] : $b_info['content'];
				$message = stripslashes($message);

				$group = get_all_usergroups($b_info['groups']);

				if(empty($group))
				{
					$group = '&nbsp;&nbsp;' . $lang['None'];
				}
			}
			else
			{
				message_die(GENERAL_MESSAGE, $lang['No_blocks_selected']);
			}
		}
		else
		{
			$b_info['bposition'] = (isset($_POST['bposition'])) ? trim($_POST['bposition']) : '';
			$position = get_block_positions($block_position_table, $l_id_list, $b_info['bposition']);

			$blocks = opendir($block_dir);

			$block_content_file_old = $b_info['blockfile'];
			$b_info['blockfile'] = (isset($_POST['blockfile'])) ? trim($_POST['blockfile']) : $b_info['blockfile'];
			$blockfile = '<option value="">-- ' . $lang['B_Text_Block'] . ' --</option>';
			while ($file = readdir($blocks))
			{
				$pos = strpos($file, 'blocks_imp_');
				if (($pos == 0) && ($pos !== false))
				{
					$pos = strpos($file, '.' . PHP_EXT);
					if ($pos !== false)
					{
						$temp = ereg_replace('\.' . PHP_EXT, '', $file);
						$temp1 = ereg_replace('blocks_imp_', '', $temp);
						$temp2 = !empty($lang['cms_block_' . $temp1]) ? ('&nbsp;[' . $lang['cms_block_' . $temp1] . ']') : '';
						$temp1 = ereg_replace('_', ' ', $temp1);
						$blockfile .= '<option value="' . $temp .'" ';
						if($b_info['blockfile'] == $temp)
						{
							$blockfile .= 'selected="selected"';
							$block_content_file = $temp;
						}
						$blockfile .= '>' . $temp1 . $temp2 . '</option>';
					}
				}
			}

			$view_array = array(
				'0' => $lang['B_All'],
				'1' => $lang['B_Guests'],
				'2' => $lang['B_Reg'],
				'3' => $lang['B_Mod'],
				'4' => $lang['B_Admin']
			);

			$view ='';
			$b_info['view'] = (isset($_POST['view'])) ? trim($_POST['view']) : 0;
			for ($i = 0; $i < count($view_array); $i++)
			{
				$view .= '<option value="' . $i .'" ';
				if($b_info['view'] == $i)
				{
					$view .= 'selected="selected"';
					$block_view = $i;
				}
				$view .= '>' . $view_array[$i] . '</option>';
			}

			$group = get_all_usergroups('');
			if(empty($group))
			{
				$group = '&nbsp;&nbsp;' . $lang['None'];
			}
		}

		$b_title = (isset($_POST['title'])) ? trim($_POST['title']) : ($b_info['title'] ? $b_info['title'] : '');
		//$b_bposition = (isset($_POST['bposition'])) ? trim($_POST['bposition']) : "";
		$b_active = (isset($_POST['active'])) ? intval($_POST['active']) : ($b_info['active'] ? $b_info['active'] : 0);
		$b_type = (isset($_POST['type'])) ? intval($_POST['type']) : ($b_info['type'] ? $b_info['type'] : 0);
		//$b_content = (isset($_POST['message'])) ? trim($_POST['message']) : "";
		//$b_blockfile = (isset($_POST['blockfile'])) ? trim($_POST['blockfile']) : ($blockfile ? $blockfile : '');
		//$b_view = (isset($_POST['view'])) ? trim($_POST['view']) : ($view ? $view : 0);
		$b_local = (isset($_POST['local'])) ? intval($_POST['local']) : ($b_info['local'] ? $b_info['local'] : 0);
		$b_titlebar = (isset($_POST['titlebar'])) ? intval($_POST['titlebar']) : ($b_info['titlebar'] ? $b_info['titlebar'] : 0);
		$b_border = (isset($_POST['border'])) ? intval($_POST['border']) : ($b_info['border'] ? $b_info['border'] : 0);
		$b_background = (isset($_POST['background'])) ? intval($_POST['background']) : ($b_info['background'] ? $b_info['background'] : 0);

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

		if($block_text == true)
		{
			$template->set_filenames(array('body' => CMS_TPL . 'cms_adv_block_edit_text_body.tpl'));
			$template->assign_var('CMS_PAGE_TITLE', $lang['Blocks_Creation_02']);
			//generate_smilies('inline');
			$s_hidden_fields .= '<input type="hidden" name="blockfile" value="" />';
			$s_hidden_fields .= '<input type="hidden" name="hascontent" value="1" />';
			$s_hidden_fields .= '<input type="hidden" name="title" value="' . htmlspecialchars($b_title) . '" />';
			$s_hidden_fields .= '<input type="hidden" name="bposition" value="' . $position['block'] . '" />';
			$s_hidden_fields .= '<input type="hidden" name="active" value="' . $b_active . '" />';
			$s_hidden_fields .= '<input type="hidden" name="local" value="' . $b_local . '" />';
			$s_hidden_fields .= '<input type="hidden" name="titlebar" value="' . $b_titlebar . '" />';
			$s_hidden_fields .= '<input type="hidden" name="border" value="' . $b_border . '" />';
			$s_hidden_fields .= '<input type="hidden" name="background" value="' . $b_background . '" />';
			$s_hidden_fields .= '<input type="hidden" name="view" value="' . $block_view . '" />';
			$s_hidden_fields .= $b_group_hidden;
		}
		elseif($block_content != false)
		{
			$template->set_filenames(array('body' => CMS_TPL . 'cms_adv_block_edit_body.tpl'));
			$template->assign_var('CMS_PAGE_TITLE', $lang['Blocks_Creation_02']);
			$s_hidden_fields .= '<input type="hidden" name="blockfile" value="' . $block_content_file . '" />';
			$s_hidden_fields .= '<input type="hidden" name="message" value="" />';
			$s_hidden_fields .= '<input type="hidden" name="type" value="0" />';
			$s_hidden_fields .= '<input type="hidden" name="title" value="' . htmlspecialchars($b_title) . '" />';
			$s_hidden_fields .= '<input type="hidden" name="bposition" value="' . $position['block'] . '" />';
			$s_hidden_fields .= '<input type="hidden" name="active" value="' . $b_active . '" />';
			$s_hidden_fields .= '<input type="hidden" name="local" value="' . $b_local . '" />';
			$s_hidden_fields .= '<input type="hidden" name="titlebar" value="' . $b_titlebar . '" />';
			$s_hidden_fields .= '<input type="hidden" name="border" value="' . $b_border . '" />';
			$s_hidden_fields .= '<input type="hidden" name="background" value="' . $b_background . '" />';
			$s_hidden_fields .= '<input type="hidden" name="view" value="' . $block_view . '" />';
			$s_hidden_fields .= $b_group_hidden;

			if (($b_id > 0) && ($block_content_file == $block_content_file_old))
			{
				$sql = "SELECT * FROM " . $config_table_name . " AS c, " . $block_variable_table . " AS bv
									WHERE c.bid = '" . $b_id . "'
										AND bv.bid = '" . $b_id . "'
										AND c.config_name = bv.config_name
									ORDER BY c.id";

				if(!$result = $db->sql_query($sql))
				{
					message_die(CRITICAL_ERROR, 'Could not query portal config information', '', __LINE__, __FILE__, $sql);
				}
				else
				{
					$controltype = array('1' => 'textbox', '2' => 'dropdown list', '3' => 'radio buttons', '4' => 'checkbox');
					$rows_counter = 0;
					while($row = $db->sql_fetchrow($result))
					{
						$portal_name = $row['config_name'];
						$portal_value = $row['config_value'];
						$row['label'] = !empty($lang['cms_var_' . $row['config_name']]) ? $lang['cms_var_' . $row['config_name']] : $row['label'];
						$row['sub_label'] = !empty($lang['cms_var_' . $row['config_name'] . '_explain']) ? $lang['cms_var_' . $row['config_name'] . '_explain'] : $row['sub_label'];

						switch($row['type'])
						{
							case '1':
								$field = '<input type="text" maxlength="255" size="40" name="' . $portal_name . '" value="' . $portal_value . '" class="post" />';
								break;
							case '2':
								$options = explode("," , $row['field_options']);
								$values = explode("," , $row['field_values']);
								$field = '<select name = "' . $portal_name . '">';
								$i = 0;
								while ($options[$i])
								{
									$tmp_option_val = ereg_replace("[^A-Za-z0-9]", "_", $options[$i]);
									$options[$i] = !empty($lang['cms_option_' . $tmp_option_val]) ? $lang['cms_option_' . $tmp_option_val] : $options[$i];
									$values[$i] = !empty($lang['cms_value_' . $tmp_option_val]) ? $lang['cms_value_' . $tmp_option_val] : $values[$i];
									$selected = ($portal_value == trim($values[$i])) ? 'selected' : '';
									$field .= '<option value = "' . trim($values[$i]) . '" ' . $selected . '>' . trim($options[$i]) . '</option>';
									$i++;
								}
								$field .= '</select>';
								break;
							case '3':
								$options = explode("," , $row['field_options']);
								$values = explode("," , $row['field_values']);
								$field = '';
								$i=0;
								while ($options[$i])
								{
									$tmp_option_val = ereg_replace("[^A-Za-z0-9]", "_", $options[$i]);
									$options[$i] = !empty($lang['cms_option_' . $tmp_option_val]) ? $lang['cms_option_' . $tmp_option_val] : $options[$i];
									$values[$i] = !empty($lang['cms_value_' . $tmp_option_val]) ? $lang['cms_value_' . $tmp_option_val] : $values[$i];
									$checked = ($portal_value == trim($values[$i])) ? 'checked="checked"' : '';
									$field .= '<input type="radio" name = "' . $portal_name . '" value = "' . trim($values[$i]) . '" ' . $checked . ' />' . trim($options[$i]) . '&nbsp;&nbsp;';
									$i++;
								}
								break;
							case '4':
								$checked = ($portal_value) ? 'checked="checked"' : '';
								$field = '<input type="checkbox" name="' . $portal_name . '" ' . $checked . ' />';
								break;
							default:
								$field = '';
						}

						$default_portal[$portal_name] = $portal_value;

						if($row['type'] == '4')
						{
							$new[$portal_name] = (isset($_POST[$portal_name])) ? '1' : '0';
						}
						else
						{
							$new[$portal_name] = (isset($_POST[$portal_name])) ? $_POST[$portal_name] : $default_portal[$portal_name];
						}

						$is_block = ($row['block'] != '@Portal Config') ? 'block ' : '';
						$template->assign_block_vars('cms_block', array(
							'L_FIELD_LABEL' => $row['label'],
							'L_FIELD_SUBLABEL' => '<br /><br /><span class="gensmall">' . $row['sub_label'] . ' [ ' . ereg_replace("@", "", $row['block']) . ' ' . $is_block . ']</span>',
							'FIELD' => $field
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
				}
				$db->sql_freeresult($result);
			}
			else
			{
				if(file_exists($block_dir . '/' . $block_content_file . '.cfg'))
				{
					$block_count_variables = 0;
					include($block_dir . '/' . $block_content_file . '.cfg');
					if ($block_count_variables > 0)
					{
						for($i = 0; $i < $block_count_variables; $i++)
						{
							$cms_config_name = $block_variables[$i][2];
							$cms_config_value = $block_variables[$i][7];
							$block_variables[$i][0] = !empty($lang['cms_var_' . $cms_config_name]) ? $lang['cms_var_' . $cms_config_name] : $block_variables[$i][0];
							$block_variables[$i][1] = !empty($lang['cms_var_' . $cms_config_name . '_explain']) ? $lang['cms_var_' . $cms_config_name . '_explain'] : $block_variables[$i][1];

							switch($block_variables[$i][5])
							{
								case '1':
									$field = '<input type="text" maxlength="255" size="40" name="' . $cms_config_name . '" value="' . $cms_config_value . '" class="post" />';
									break;
								case '2':
									$options = explode("," , $block_variables[$i][3]);
									$values = explode("," , $block_variables[$i][4]);
									$field = '<select name = "' . $cms_config_name . '">';
									$j = 0;
									while ($options[$j])
									{
										$tmp_option_val = ereg_replace("[^A-Za-z0-9]", "_", $options[$j]);
										$options[$j] = !empty($lang['cms_option_' . $tmp_option_val]) ? $lang['cms_option_' . $tmp_option_val] : $options[$j];
										$values[$j] = !empty($lang['cms_value_' . $tmp_option_val]) ? $lang['cms_value_' . $tmp_option_val] : $values[$j];
										$selected = ($cms_config_value == trim($values[$j])) ? 'selected' : '';
										$field .= '<option value = "' . trim($values[$j]) . '" ' . $selected . '>' . trim($options[$j]) . '</option>';
										$j++;
									}
									$field .= '</select>';
									break;
								case '3':
									$options = explode("," , $block_variables[$i][3]);
									$values = explode("," , $block_variables[$i][4]);
									$field = '';
									$j = 0;
									while ($options[$j])
									{
										$tmp_option_val = ereg_replace("[^A-Za-z0-9]", "_", $options[$j]);
										$options[$j] = !empty($lang['cms_option_' . $tmp_option_val]) ? $lang['cms_option_' . $tmp_option_val] : $options[$j];
										$values[$j] = !empty($lang['cms_value_' . $tmp_option_val]) ? $lang['cms_value_' . $tmp_option_val] : $values[$j];
										$checked = ($cms_config_value == trim($values[$j])) ? 'checked="checked"' : '';
										$field .= '<input type="radio" name = "' . $cms_config_name . '" value = "' . trim($values[$j]) . '" ' . $checked . ' />' . trim($options[$j]) . '&nbsp;&nbsp;';
										$j++;
									}
									break;
								case '4':
									$checked = ($cms_config_value) ? 'checked="checked"' : '';
									$field = '<input type="checkbox" name="' . $cms_config_name . '" ' . $checked . ' />';
									break;
								default:
									$field = '';
							}

							$default_cms_value[$cms_config_name] = $cms_config_value;

							if($block_variables[$i][5] == '4')
							{
								$new[$cms_config_name] = (isset($_POST[$cms_config_name])) ? '1' : '0';
							}
							else
							{
								$new[$cms_config_name] = (isset($_POST[$cms_config_name])) ? $_POST[$cms_config_name] : $default_cms_value[$cms_config_name];
							}

							$is_block = ($block_variables[$i][6] != '@Portal Config') ? 'block ' : '';
							$template->assign_block_vars('cms_block', array(
								'L_FIELD_LABEL' => $block_variables[$i][0],
								'L_FIELD_SUBLABEL' => '<br /><br /><span class="gensmall">' . $block_variables[$i][1] . ' [ ' . ereg_replace("@", "", $block_variables[$i][6]) . ' ' . $is_block . ']</span>',
								'FIELD' => $field
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
			$template->set_filenames(array('body' => CMS_TPL . 'cms_adv_block_content_body.tpl'));
			$template->assign_var('CMS_PAGE_TITLE', $lang['Blocks_Creation_01']);
			//$s_hidden_fields .= '<input type="hidden" name="message" value="' . $message . '" />';
			$s_hidden_fields .= '<input type="hidden" name="message" value="' . htmlspecialchars($message) . '" />';
			$s_hidden_fields .= '<input type="hidden" name="type" value="' . $b_type . '" />';
			$s_hidden_fields .= '<input type="hidden" name="hascontent" value="1" />';
		}

		if ($preview_block == true)
		{
			if ($b_type == true)
			{
				$message = preg_replace('#(<)([\/]?.*?)(>)#is', "&lt;\\2&gt;", $message);
				//$bbcode->allow_html = true;
				$bbcode->allow_html = false;
				$bbcode->allow_bbcode = true;
				$bbcode->allow_smilies = true;
				$preview_message = $bbcode->parse($message);
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
			'TITLE' => $b_title,
			'POSITION' => $position['select'],
			'ACTIVE' => ($b_active) ? 'checked="checked"' : '',
			'NOT_ACTIVE' => (!$b_active) ? 'checked="checked"' : '',
			'HTML' => (!$b_type) ? 'checked="checked"' : '',
			'BBCODE' => ($b_type) ? 'checked="checked"' : '',
			'CONTENT' => $message,
			'BLOCKFILE' => $blockfile,
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

			'L_EDIT_BLOCK' => $lang['Block_Edit'],
			'L_SUBMIT' => $lang['Submit'],
			'L_PREVIEW' => $lang['Preview'],
			'S_BLOCKS_ACTION' => append_sid('cms_adv.' . PHP_EXT . $s_append_url),
			'S_HIDDEN_FIELDS' => $s_hidden_fields
			)
		);

		// BBCBMG - BEGIN
		//$bbcbmg_in_acp = true;
		include(IP_ROOT_PATH . 'language/lang_' . $board_config['default_lang'] . '/lang_bbcb_mg.' . PHP_EXT);
		include(IP_ROOT_PATH . 'includes/bbcb_mg.' . PHP_EXT);
		$template->assign_var_from_handle('BBCB_MG', 'bbcb_mg');
		// BBCBMG - END
		// BBCBMG SMILEYS - BEGIN
		generate_smilies('inline');
		include(IP_ROOT_PATH . 'includes/bbcb_smileys_mg.' . PHP_EXT);
		$template->assign_var_from_handle('BBCB_SMILEYS_MG', 'bbcb_smileys_mg');
		// BBCBMG SMILEYS - END

		$template->pparse('body');

		include(IP_ROOT_PATH . 'includes/page_tail.' . PHP_EXT);
	}
	elseif($action == 'save')
	{
		$b_title = (isset($_POST['title'])) ? trim($_POST['title']) : '';
		$b_bposition = (isset($_POST['bposition'])) ? trim($_POST['bposition']) : '';
		$b_active = (isset($_POST['active'])) ? intval($_POST['active']) : 0;
		$b_type = (isset($_POST['type'])) ? intval($_POST['type']) : 0;
		$b_content = (isset($_POST['message'])) ? trim($_POST['message']) : '';
		$b_blockfile = (isset($_POST['blockfile'])) ? trim($_POST['blockfile']) : '';
		$b_view = (isset($_POST['view'])) ? trim($_POST['view']) : 0;
		$b_border = (isset($_POST['border'])) ? intval($_POST['border']) : 0;
		$b_titlebar = (isset($_POST['titlebar'])) ? intval($_POST['titlebar']) : 0;
		$b_local = (isset($_POST['local'])) ? intval($_POST['local']) : 0;
		$b_background = (isset($_POST['background'])) ? intval($_POST['background']) : 0;

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

		$gb_pos = array('gt', 'gb', 'gl', 'gr', 'hh', 'hl', 'hc', 'fc', 'fr', 'ff');
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

		$b_content = addslashes($b_content);
		if($b_type == true)
		{
			if(!empty($b_content))
			{
				//$b_content = prepare_message(trim($b_content), true, true, true);
				//$b_content = str_replace("\'", "''", $b_content);
			}
		}

		if($b_id)
		{
			$sql = "UPDATE " . $blocks_table . "
				SET
				title = '" . str_replace("\'", "''", $b_title) . "',
				bposition = '" . $b_bposition . "',
				active = '" . $b_active . "',
				type = '" . $b_type . "',
				content = '" . $b_content . "',
				blockfile = '" . $b_blockfile . "',
				layout = '" . $layout_value . "',
				view = '" . $b_view . "',
				border = '" . $b_border . "',
				titlebar = '" . $b_titlebar . "',
				local = '" . $b_local . "',
				background = '" . $b_background . "',
				groups = '" . $b_group . "'
				WHERE bid = $b_id";

			if(!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not insert data into blocks table', $lang['Error'], __LINE__, __FILE__, $sql);
			}

			if(file_exists($block_dir . '/' . $b_blockfile . '.cfg'))
			{
				include($block_dir . '/' . $b_blockfile . '.cfg');

				// let's empty the previously created config vars...
				$sql = "SELECT * FROM " . $config_table_name . " WHERE bid = '" . $b_id . "'";
				if(!$result = $db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'Could not query information from block variable table', $lang['Error'], __LINE__, __FILE__, $sql);
				}

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
						delete_block_config_single($config_table_name, $block_variable_table, $b_id, $row['config_name']);
					}
				}
				$db->sql_freeresult($result);
			}
			else
			{
				delete_block_config_all($config_table_name, $block_variable_table, $b_id);
			}

			if(!empty($b_blockfile))
			{
				if(file_exists($block_dir . '/' . $b_blockfile . '.cfg'))
				{
					include($block_dir . '/' . $b_blockfile . '.cfg');

					//$message .= '<br /><br />' . $lang['B_BV_added'];

					for($i = 0; $i < $block_count_variables; $i++)
					{
						if ((!empty($_POST[$block_variables[$i][2]])) || ($_POST[$block_variables[$i][2]] == '0'))
						{
							$block_variables[$i][7] = str_replace("\'", "''", $_POST[$block_variables[$i][2]]);
						}

						$existing = get_existing_block_var($block_variable_table, $b_id, $block_variables[$i][2]);

						if(!$existing)
						{
							$sql = "INSERT INTO " . $block_variable_table . " (bid, label, sub_label, config_name, field_options, field_values, type, block)
								VALUES ('" . $b_id ."', '" . str_replace("\'", "''", $block_variables[$i][0]) . "', '" . str_replace("\'", "''", $block_variables[$i][1]) . "', '" . str_replace("\'", "''", $block_variables[$i][2]) . "', '" . str_replace("\'", "''", $block_variables[$i][3]) . "', '" . $block_variables[$i][4] . "', '" . $block_variables[$i][5] . "', '" . str_replace("\'", "''", $block_variables[$i][6]) . "')";
							if(!$result = $db->sql_query($sql))
							{
								message_die(GENERAL_ERROR, 'Could not insert data into block variable table', $lang['Error'], __LINE__, __FILE__, $sql);
							}

							$sql = "INSERT INTO " . $config_table_name . " (bid, config_name, config_value)
								VALUES ('" . $b_id ."', '" . str_replace("\'", "''", $block_variables[$i][2]) . "', '" . $block_variables[$i][7] . "')";
							if(!$result = $db->sql_query($sql))
							{
								message_die(GENERAL_ERROR, 'Could not insert data into portal config table', $lang['Error'], __LINE__, __FILE__, $sql);
							}
						}
						else
						{
							$sql = "UPDATE " . $config_table_name . " SET config_value = '" . $block_variables[$i][7] . "'
											WHERE config_name = '" . str_replace("\'", "''", $block_variables[$i][2]) . "'
												AND bid = " . $b_id;
							if(!$result = $db->sql_query($sql))
							{
								message_die(GENERAL_ERROR, 'Could not insert data into portal config table', $lang['Error'], __LINE__, __FILE__, $sql);
							}
						}
					}
				}
			}
		}
		else
		{
			$weight = get_max_blocks_position($blocks_table, $id_var_value, $b_bposition) + 1;
			$b_id = get_max_block_id($blocks_table) + 1;

			$sql = "INSERT INTO " . $blocks_table . " (bid, title, content, bposition, weight, active, type, blockfile, view, layout, border, titlebar, background, local, groups) VALUES ('" . $b_id . "', '" . str_replace("\'", "''", $b_title) . "', '" . $b_content . "', '" . str_replace("\'", "''", $b_bposition) . "', '" . $weight . "', '" . $b_active . "', '" . $b_type . "', '" . str_replace("\'", "''", $b_blockfile) . "', '" . $b_view . "', '" . $layout_value . "', '" . $b_border . "', '" . $b_titlebar . "', '" . $b_background . "', '" . $b_local . "', '" . $b_group . "')";
			$message = $lang['Block_added'];
			if(!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not insert data into blocks table', $lang['Error'], __LINE__, __FILE__, $sql);
			}

			if(!empty($b_blockfile))
			{
				if(file_exists($block_dir . '/' . $b_blockfile . '.cfg'))
				{
					include($block_dir . '/' . $b_blockfile . '.cfg');

					//$message .= '<br /><br />' . $lang['B_BV_added'];

					for($i = 0; $i < $block_count_variables; $i++)
					{
						if ((!empty($_POST[$block_variables[$i][2]])) || ($_POST[$block_variables[$i][2]] == '0'))
						{
							$block_variables[$i][7] = str_replace("\'", "''", $_POST[$block_variables[$i][2]]);
						}

						$sql = "INSERT INTO " . $block_variable_table . " (bid, label, sub_label, config_name, field_options, field_values, type, block)
							VALUES ('" . $b_id . "', '" . str_replace("\'", "''", $block_variables[$i][0]) . "', '" . str_replace("\'", "''", $block_variables[$i][1]) . "', '" . str_replace("\'", "''", $block_variables[$i][2]) . "', '" . str_replace("\'", "''", $block_variables[$i][3]) . "', '" . $block_variables[$i][4] . "', '" . $block_variables[$i][5] . "', '" . str_replace("\'", "''", $block_variables[$i][6]) . "')";
						if(!$result = $db->sql_query($sql))
						{
							message_die(GENERAL_ERROR, 'Could not insert data into block variable table', $lang['Error'], __LINE__, __FILE__, $sql);
						}

						$sql = "INSERT INTO " . $config_table_name . " (bid, config_name, config_value)
							VALUES ('" . $b_id ."', '" . str_replace("\'", "''", $block_variables[$i][2]) . "', '" . $block_variables[$i][7] . "')";
						if(!$result = $db->sql_query($sql))
						{
							message_die(GENERAL_ERROR, 'Could not insert data into portal config table', $lang['Error'], __LINE__, __FILE__, $sql);
						}
					}
				}
			}
		}
		fix_weight_blocks($id_var_value, $pages_table_name);
		$db->clear_cache('cms_');
		$message .= '<br /><br />' . $lang['Block_updated'] . '<br /><br />' . sprintf($lang['Click_return_blocksadmin'], '<a href="' . append_sid('cms_adv.' . PHP_EXT . '?mode=blocks' . $type_append_url . '&amp;' . $id_var_name . '=' . $redirect_l_id) . '">', '</a>') . '<br />';
		message_die(GENERAL_MESSAGE, $message);
	}
	elseif($action == 'delete')
	{
		if(!isset($_POST['confirm']))
		{
			// Set template files
			$template->set_filenames(array('confirm' => CMS_TPL . 'confirm_body.tpl'));

			$template->assign_vars(array(
				'MESSAGE_TITLE' => $lang['Confirm'],
				'MESSAGE_TEXT' => $lang['Confirm_delete_item'],
				'L_YES' => $lang['Yes'],
				'L_NO' => $lang['No'],
				'S_CONFIRM_ACTION' => append_sid('cms_adv.' . PHP_EXT),
				'S_HIDDEN_FIELDS' => $s_hidden_fields
				)
			);
			$template->pparse('confirm');
			include(IP_ROOT_PATH . 'includes/page_tail.' . PHP_EXT);
			exit();
		}
		else
		{
			if($b_id != 0)
			{
				delete_block($blocks_table, $b_id);
				if (($l_id == 0) && ($id_var_name == 'l_id'))
				{
					$redirect_action = '&amp;action=editglobal';
				}
				else
				{
					$redirect_action = '&amp;action=list';
				}

				$db->clear_cache('cms_');

				$message = $lang['Block_removed'] . '<br /><br />' . sprintf($lang['Click_return_blocksadmin'], '<a href="' . append_sid('cms_adv.' . PHP_EXT . '?mode=blocks' . $type_append_url . '&amp;' . $id_var_name . '=' . $id_var_value . $redirect_action) . '">', '</a>') . '<br />';

				message_die(GENERAL_MESSAGE, $message);
			}
			else
			{
				message_die(GENERAL_MESSAGE, $lang['No_blocks_selected']);
			}
			fix_weight_blocks($id_var_value, $pages_table_name);
			fix_weight_blocks(0, $pages_table_name);
		}
	}
	elseif($action == 'duplicate')
	{
		if (!empty($_POST['duplicate_blocks']))
		{
			$blocks_dup = array();
			$blocks_dup = $_POST['block'];
			$blocks_dup_n = count($blocks_dup);
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
						FROM " . $blocks_table . "
						WHERE bid = '" . intval($blocks_dup[$i]) . "'";
					if(!$result = $db->sql_query($sql))
					{
						message_die(GENERAL_ERROR, 'Could not query blocks table', $lang['Error'], __LINE__, __FILE__, $sql);
					}

					$b_info = $db->sql_fetchrow($result);
					$db->sql_freeresult($result);

					$weight = get_max_blocks_position($blocks_table, $id_var_value, $b_bposition) + 1;
					$b_id = get_max_block_id($blocks_table) + 1;

					$sql = "INSERT INTO " . $blocks_table . " (bid, title, content, bposition, weight, active, type, blockfile, view, layout, border, titlebar, background, local, groups) VALUES ('" . $b_id . "', '" . $b_info['title'] . "', '" . $b_info['content'] . "', '" . $b_info['bposition'] . "', '" . $b_info['weight'] . "', '" . $b_info['active'] . "', '" . $b_info['type'] . "', '" . $b_info['blockfile'] . "', '" . $b_info['view'] . "', '" . (($id_var_name == 'l_id') ? $id_var_value : 0) . "', '" . $b_info['border'] . "', '" . $b_info['titlebar'] . "', '" . $b_info['background'] . "', '" . $b_info['local'] . "', '" . $b_info['groups'] . "')";
					if(!$result = $db->sql_query($sql))
					{
						message_die(GENERAL_ERROR, 'Could not insert data into blocks table', $lang['Error'], __LINE__, __FILE__, $sql);
					}

					$sql_cfg = "SELECT * FROM " . $config_table_name . " AS c, " . $block_variable_table . " AS bv
										WHERE c.bid = '" . intval($blocks_dup[$i]) . "'
											AND bv.bid = '" . intval($blocks_dup[$i]) . "'
											AND c.config_name = bv.config_name
										ORDER BY c.id";

					if(!$result_cfg = $db->sql_query($sql_cfg))
					{
						message_die(CRITICAL_ERROR, 'Could not query portal config information', '', __LINE__, __FILE__, $sql_cfg);
					}
					while($row_cfg = $db->sql_fetchrow($result_cfg))
					{
						$portal_name = $row_cfg['config_name'];
						$sql = "INSERT INTO " . $block_variable_table . " (bid, label, sub_label, config_name, field_options, field_values, type, block)
							VALUES ('" . $b_id . "', '" . $row_cfg['label'] . "', '" . $row_cfg['sub_label'] . "', '" . $row_cfg['config_name'] . "', '" . $row_cfg['field_options'] . "', '" . $row_cfg['field_values'] . "', '" . $row_cfg['type'] . "', '" . $row_cfg['block'] . "')";
						if(!$result = $db->sql_query($sql))
						{
							message_die(GENERAL_ERROR, 'Could not insert data into block variable table', $lang['Error'], __LINE__, __FILE__, $sql);
						}

						$sql = "INSERT INTO " . $config_table_name . " (bid, config_name, config_value)
							VALUES ('" . $b_id ."', '" . $row_cfg['config_name'] . "', '" . $row_cfg['config_value'] . "')";
						if(!$result = $db->sql_query($sql))
						{
							message_die(GENERAL_ERROR, 'Could not insert data into portal config table', $lang['Error'], __LINE__, __FILE__, $sql);
						}
					}
				}
			}
			fix_weight_blocks($id_var_value, $pages_table_name);
			$db->clear_cache('cms_');
			$message = '<br /><br />' . $lang['Blocks_duplicated'] . '<br /><br />' . sprintf($lang['Click_return_blocksadmin'], '<a href="' . append_sid('cms_adv.' . PHP_EXT . '?mode=blocks' . $type_append_url . '&amp;' . $id_var_name . '=' . $id_var_value) . '">', '</a>') . '<br />';
			message_die(GENERAL_MESSAGE, $message);
		}
		else
		{
			$template->set_filenames(array('body' => CMS_TPL . 'cms_adv_blocks_duplicate_body.tpl'));
			$template->assign_var('CMS_PAGE_TITLE', $lang['Blocks_Title']);

			$l_row = get_layout_name($pages_table_name, 'lid', $id_var_value);
			$l_name = $l_row['name'];
			$l_filename = $l_row['filename'];

			if (($id_var_value == 0) || ($id_var_name == 'ls_id'))
			{
				$page_url = append_sid(PORTAL_MG);
				$l_id_list = "'0'";
				$l_name = $l_filename . '.' . PHP_EXT;
			}
			else
			{
				if ($id_var_name == 'l_id')
				{
					$page_url = (substr($l_name, strlen($l_name) - (strlen(PHP_EXT) + 1), (strlen(PHP_EXT) + 1)) == ('.' . PHP_EXT)) ? append_sid($l_name) : append_sid(PORTAL_MG . '?page=' . $id_var_value);
					$l_name = ($l_name == '') ? $lang['Portal'] : $l_name;
				}
				else
				{
					$page_url = append_sid($l_filename . '.' . PHP_EXT);
					$l_name = $l_filename . '.' . PHP_EXT;
				}
				$l_id_list = "'" . $id_var_value . "'";
			}

			$sql = "SELECT bposition, pkey FROM " . $block_position_table . " WHERE layout IN (" . $l_id_list . ")";
			if(!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not query blocks position table', $lang['Error'], __LINE__, __FILE__, $sql);
			}
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
			$sql = "SELECT b.*, l.name
							FROM " . $blocks_table . " AS b, " . $pages_table_name . " AS l
							WHERE b.bposition IN (" . $positions_list . ")
								AND l.lid = b.layout
							ORDER BY " . $sort_sql;
			if(!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not query blocks table', $lang['Error'], __LINE__, __FILE__, $sql);
			}

			$b_rows = $db->sql_fetchrowset($result);
			$db->sql_freeresult($result);
			$b_count = count($b_rows);

			$template->assign_vars(array(
				'L_BLOCKS_TITLE' => $lang['B_Duplicate'],
				'L_BLOCKS_TEXT' => $lang['Blocks_Duplicate_Explain'],
				'L_B_TITLE' => $lang['B_Title'],
				'L_B_POSITION' => $lang['B_Position'],
				'L_B_ACTIVE' => $lang['B_Active'],
				'L_B_DISPLAY' => $lang['B_Display'],
				'L_B_TYPE' => $lang['B_Type'],
				'L_B_LAYOUT' => $lang['B_Layout'],
				'L_B_PAGE' => $lang['B_Page'],
				'LAYOUT_NAME' => $l_name,
				'PAGE_URL' => $page_url,
				'PAGE' => strval($id_var_value),
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
				'S_BLOCKS_ACTION' => append_sid('cms_adv.' . PHP_EXT),
				'S_HIDDEN_FIELDS' => $s_hidden_fields
				)
			);

			for($i = 0; $i < $b_count; $i++)
			{
				$b_id = $b_rows[$i]['bid'];
				$b_weight = $b_rows[$i]['weight'];
				$b_position = $b_rows[$i]['bposition'];
				$b_position_l = !empty($lang['cms_pos_' . $position[$b_position]]) ? $lang['cms_pos_' . $position[$b_position]] : $row['pkey'];

				$row_class = (!($i % 2)) ? 'row1 row-center' : 'row2 row-center';

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
					'U_EDIT' => append_sid('cms_adv.' . PHP_EXT . '?mode=blocks' . $type_append_url . '&amp;action=edit&amp;' . $id_var_name . '=' . $id_var_value . '&amp;b_id=' . $b_id),
					'U_DELETE' => append_sid('cms_adv.' . PHP_EXT . '?mode=blocks' . $type_append_url . '&amp;action=delete&amp;' . $id_var_name . '=' . $id_var_value . '&amp;b_id=' . $b_id),
					)
				);
			}
		}
	}
	elseif(($id_var_value != 0) || ($action == 'editglobal'))
	{
		if(isset($_POST['action_update']))
		{
			if ($action == 'editglobal')
			{
				$l_id_list = "'0'";
				$action_append = '&amp;action=editglobal';
			}
			else
			{
				$l_id_list = "'" . $id_var_value . "'";
				$action_append = '';
			}

			$sql = "SELECT bposition FROM " . $block_position_table . " WHERE layout = " . $l_id_list . "";
			if(!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not query blocks position table', $lang['Error'], __LINE__, __FILE__, $sql);
			}
			$l_rows = $db->sql_fetchrowset($result);
			$db->sql_freeresult($result);
			$l_count = count($l_rows);

			for($j = 0; $j < $l_count; $j++)
			{
				$bposition = $l_rows[$j]['bposition'];
				if(isset($_POST['list_' . $bposition]))
				{
					$block_debug = str_replace('list_' . $bposition . '[]=id', '', $_POST['list_' . $bposition]);
					$block_debug_array = explode("&", $block_debug);

					for ($i = 0; $i < count($block_debug_array); $i++)
					{
						$sql = "UPDATE " . $blocks_table . "
							SET weight = '" . $i . "', bposition = '" . $bposition . "'
							WHERE bid = '" . $block_debug_array[$i] . "'";
						if(!$result = $db->sql_query($sql))
						{
							message_die(GENERAL_ERROR, 'Could not update blocks table', $lang['Error'], __LINE__, __FILE__, $sql);
						}
					}
				}
			}

			$db->clear_cache('cms_');
			redirect(append_sid('cms_adv.' . PHP_EXT . '?mode=blocks' . $type_append_url . '&' . $id_var_name . '=' . $id_var_value . $action_append . '&updated=true'));
		}

		$template->set_filenames(array('body' => CMS_TPL . 'cms_adv_blocks_list_body.tpl'));
		$template->assign_var('CMS_PAGE_TITLE', $lang['Blocks_Title']);

		$l_row = get_layout_name($pages_table_name, 'lid', $id_var_value);
		$l_name = $l_row['name'];
		$l_filename = $l_row['filename'];

		if ($action == 'editglobal')
		{
			$page_url = append_sid(PORTAL_MG);
			$l_id_list = "'0'";
		}
		else
		{
			$page_url = append_sid($l_filename);
			$l_id_list = "'" . $id_var_value . "'";
		}

		if ($id_var_name == 'l_id')
		{
			$l_name = ($l_name == '') ? $lang['Portal'] : $l_name;
		}
		else
		{
			$l_name = $l_filename . '.' . PHP_EXT;
		}

		if (($id_var_name == 'l_id') && ($id_var_value > 0))
		{
			$template->assign_block_vars('duplicate_switch', array());
		}

		if ($is_updated == true)
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
			'L_B_PAGE' => $lang['B_Page'],
			'LAYOUT_NAME' => $l_name,
			'PAGE_URL' => $page_url,
			'PAGE' => strval($id_var_value),
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
			'S_BLOCKS_ACTION' => append_sid('cms_adv.' . PHP_EXT),
			'S_HIDDEN_FIELDS' => $s_hidden_fields
			)
		);

		/*
		if ($id_var_name == 'l_id')
		{
			$l_id_list = $id_var_value . "', '0";
		}
		else
		{
			$l_id_list = '0';
		}
		*/

		$sql = "SELECT bposition, pkey FROM " . $block_position_table . " WHERE layout = " . $id_var_value . "";
		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not query blocks position table', $lang['Error'], __LINE__, __FILE__, $sql);
		}
		$l_rows = $db->sql_fetchrowset($result);
		$db->sql_freeresult($result);
		$l_count = count($l_rows);

		$sql = "SELECT template FROM " . $pages_table_name . " WHERE lid = " . $l_id_list . "";
		if(!($layout_result = $db->sql_query($sql, false, 'cms_')))
		{
			message_die(CRITICAL_ERROR, "Could not query portal layout information", "", __LINE__, __FILE__, $sql);
		}
		$layout_row = $db->sql_fetchrow($layout_result);
		$layout_type = $layout_row['template'];

		$template->set_filenames(array('layout_blocks' => $layout_path . $layout_type));
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
			$sql = "SELECT bid FROM " . $blocks_table . " WHERE layout = " . $l_id_list . " AND bposition = '" . $l_rows[$j]['bposition'] . "' ORDER BY weight";
			if(!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not query blocks table', $lang['Error'], __LINE__, __FILE__, $sql);
			}
			$b_rows = $db->sql_fetchrowset($result);
			$db->sql_freeresult($result);

			$b_count = count($b_rows);
			$b_position_l = !empty($lang['cms_pos_' . $l_rows[$j]['pkey']]) ? $lang['cms_pos_' . $l_rows[$j]['pkey']] : $l_rows[$j]['pkey'];

			if ($b_count <> 0)
			{
				for($i = 0; $i < $b_count; $i++)
				{
					if (($action == 'editglobal'))
					{
					}
					else
					{
						$b_id = $b_rows[$i]['bid'];

						if (($l_id == 0) && ($id_var_name == 'l_id'))
						{
							$redirect_action = '&amp;action=editglobal';
						}
						else
						{
							$redirect_action = '&amp;action=list';
						}

						$output_block = make_cms_block($id_var_value, $b_id, $i, $b_count, $b_position_l, false, $cms_type);

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

			$template->assign_var('LAYOUT_BLOCKS', cms_assign_var_from_handle($template, 'layout_blocks'));

			$template->assign_block_vars('drop_blocks', array(
				'BPOSITION' => $l_rows[$j]['bposition'],
				)
			);

		}

		$sql = "SELECT bid, bposition FROM " . $blocks_table . " WHERE layout = " . $l_id_list . " AND bposition NOT IN (" . $b_position_array . ") ORDER BY weight";
		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not query blocks table', $lang['Error'], __LINE__, __FILE__, $sql);
		}
		$b_rows = $db->sql_fetchrowset($result);
		$db->sql_freeresult($result);

		$b_count = count($b_rows);
		$invalid_position = array();
		$invalid_position_count = 0;

		if ($b_count <> 0)
		{
			$template->set_filenames(array('invalid_blocks' => CMS_TPL . 'cms_invalid_blocks.tpl'));
			$template->assign_var('CMS_PAGE_TITLE', $lang['CMS_Pages']);

			for($i = 0; $i < $b_count; $i++)
			{
				if (($action == 'editglobal'))
				{
				}
				else
				{
					$b_id = $b_rows[$i]['bid'];

					$output_block = make_cms_block($id_var_value, $b_id, $i, $b_count, $lang['INVALID_BLOCKS'], true, $cms_type);

					$template->assign_block_vars('invalid_blocks_row', array(
						'CMS_BLOCK' => $output_block,
						'OUTPUT' => $output_block,
						)
					);

					if (!in_array($b_rows[$i]['bposition'], $invalid_position))
					{
						$invalid_position[$invalid_position_count] = $b_rows[$i]['bposition'];
						$invalid_position_count++;
					}
				}
			}
			$template->assign_var('INVALID_BLOCKS', cms_assign_var_from_handle($template, 'invalid_blocks'));
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
		message_die(GENERAL_MESSAGE, $lang['No_layout_selected']);
	}
}

if ($mode == 'layouts')
{
	$mode = 'layouts';
	$s_hidden_fields = '<input type="hidden" name="mode" value="' . $mode . '" />';
	$s_append_url = '?mode=' . $mode;
	$s_hidden_fields .= '<input type="hidden" name="action" value="' . $action . '" />';
	$s_append_url .= '&amp;action=' . $action;
	$s_hidden_fields .= '<input type="hidden" name="cms_type" value="' . $cms_type . '" />';
	$s_append_url .= '&amp;type=' . $cms_type;

	if($l_id != false)
	{
		$s_hidden_fields .= '<input type="hidden" name="l_id" value="' . $l_id . '" />';
		$s_append_url .= '&amp;' . l_id . '=' . $l_id;
	}
	else
	{
		$l_id = 0;
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

	if($cms_id != false)
	{
		$s_hidden_fields .= '<input type="hidden" name="cms_id" value="' . $cms_id . '" />';
		$s_append_url .= '&amp;cms_id=' . $cms_id;
	}
	else
	{
		$cms_id = 0;
	}

	if(($action == 'edit') || ($action == 'add'))
	{
		$template->set_filenames(array('body' => CMS_TPL . 'cms_adv_layout_edit_body.tpl'));
		$template->assign_var('CMS_PAGE_TITLE', $lang['CMS_Pages']);

		if(($action == 'edit') && $l_id)
		{
			$l_info = get_layout_info($pages_table_name, $l_id);
			$s_hidden_fields .= '<input type="hidden" name="filename_old" value="' . $l_info['filename'] . '" />';
		}

		$layouts = opendir($layout_dir);
		$layout_img = array();
		$layout_file = array();
		$num_layout = 0;

		while ($file = readdir($layouts))
		{
			$pos = strpos($file, $layout_extension);
			if (($pos !== false) && ($file != 'index.html'))
			{
				$img = 'layout_' . str_replace($layout_extension, '', $file) . '.png';
				if ($cms_type == 'cms_standard')
				{
					$img = (file_exists($common_cms_template . 'images/' . $img)) ? ($common_cms_template . 'images/' . $img) : ($common_cms_template . 'images/layout_unknown.png');
				}
				else
				{
					$img = (file_exists($common_cms_template . 'layouts/' . $img)) ? ($common_cms_template . 'layouts/' . $img) : ($common_cms_template . 'layouts/layout_unknown.png');
				}
				$layout_img[$num_layout] = '<img src="' . $img . '" alt="' . $file . '" title="' . $file . '"/>';
				$layout_file[$num_layout] = '<input type="radio" name="layout" value="' . $file . '"';
				if(!empty($l_info) && $l_info['template'] == $file)
				{
					$layout_file[$num_layout] .= 'checked="checked"';
				}
				$layout_file[$num_layout] .= '/>';
				$num_layout++;
			}
		}

		if ($cms_type != 'cms_standard')
		{
			$templates = opendir(IP_ROOT_PATH . STYLES_PATH);
			$is_default_selected = (!empty($l_info) && ($l_info['style'] == '')) ? 'selected="selected"' : '';
			$templatefolder = '';
			while ($file = readdir($templates))
			{
				if (file_exists(IP_ROOT_PATH . STYLES_PATH . $file .'/style.css'))
				{
					include(IP_ROOT_PATH . STYLES_PATH . $file . '/theme_info.cfg');
					$templatefolder .= '<option value="' . $file . '" ';
					if(!empty($l_info) && ($l_info['style'] == $file))
					{
						$templatefolder .= 'selected="selected"';
					}
					$templatefolder .= '>' . $style_info['style_name'] . '</option>';
				}
			}
		}

		$view_array = array(
			'0' => $lang['B_All'],
			'1' => $lang['B_Guests'],
			'2' => $lang['B_Reg'],
			'3' => $lang['B_Mod'],
			'4' => $lang['B_Admin']
		);

		$view ='';
		for ($i = 0; $i < count($view_array); $i++)
		{
			$view .= '<option value="' . $i .'" ';
			if(!empty($l_info) && ($l_info['view'] == $i))
			{
				$view .= 'selected="selected"';
			}
			$view .= '>' . $view_array[$i] . '</option>';
		}

		$auth_array = array(
			'0' => $lang['CMS_Guest'],
			'1' => $lang['CMS_Reg'],
			'2' => $lang['CMS_VIP'],
			'3' => $lang['CMS_Publisher'],
			'4' => $lang['CMS_Reviewer'],
			'5' => $lang['CMS_Content_Manager']
		);

		$edit_auth ='';
		for ($i = 3; $i <= 5; $i++)
		{
			$edit_auth .= '<option value="' . $i . '" ';
			if(!empty($l_info) && ($l_info['edit_auth'] == $i))
			{
				$edit_auth .= 'selected="selected"';
			}
			$edit_auth .= '>' . $auth_array[$i] . '</option>';
		}

		$group = (!empty($l_info)) ? get_all_usergroups($l_info['groups']) : get_all_usergroups('');
		if(empty($group))
		{
			$group = '&nbsp;&nbsp;' . $lang['None'];
		}

		if(($action == 'edit') && !$l_id)
		{
			message_die(GENERAL_MESSAGE, $lang['No_layout_selected']);
		}

		if (file_exists('testing_write_access_permissions.test'))
		{
			@unlink('testing_write_access_permissions.test');
		}
		$write_test = @copy('index_cms_empty.' . PHP_EXT, 'testing_write_access_permissions.test');
		if (file_exists('testing_write_access_permissions.test'))
		{
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

		$template->assign_vars(array(
			'L_CMS_PAGES' => $lang['CMS_Pages'],
			'L_CMS_ID' => $lang['CMS_ID'],
			'L_CMS_ACTIONS' => $lang['CMS_Actions'],
			'L_CMS_LAYOUT' => $lang['CMS_Layout'],
			'L_CMS_NAME' => $lang['CMS_Name'],
			'L_CMS_FILENAME' => $lang['CMS_Filename'],
			'L_CMS_FILENAME_EXPLAIN' => $lang['CMS_Filename_Explain'],
			'L_CMS_FILENAME_AUTH' => $file_creation_auth,
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
			'NAME' => $l_info['name'],
			'FILENAME' => $l_info['filename'],
			'LAYOUT' => $layoutfile,
			'TEMPLATE' => $templatefolder,
			'VIEW' => $view,
			'EDIT_AUTH' => $edit_auth,
			'GROUPS' => $group,
			'GLOBAL_BLOCKS' => ($l_info['global_blocks']) ? 'checked="checked"' : '',
			'NOT_GLOBAL_BLOCKS' => (!$l_info['global_blocks']) ? 'checked="checked"' : '',
			'PAGE_NAV' => ($l_info['page_nav']) ? 'checked="checked"' : '',
			'NOT_PAGE_NAV' => (!$l_info['page_nav']) ? 'checked="checked"' : '',
			'L_EDIT_LAYOUT' => $lang['Layout_Edit'],
			'L_SUBMIT' => $lang['Submit'],
			'S_LAYOUT_ACTION' => append_sid('cms_adv.' . PHP_EXT),
			'S_HIDDEN_FIELDS' => $s_hidden_fields
			)
		);

		for ($i = 0; $i < $num_layout; $i++)
		{
			$template->assign_block_vars('layouts', array(
				'LAYOUT_IMG' => $layout_img[$i],
				'LAYOUT_RADIO' => $layout_file[$i]
				)
			);
		}
	}
	elseif($action == 'save')
	{
		$l_name = (isset($_POST['name'])) ? trim($_POST['name']) : '';
		$l_filename = (isset($_POST['filename'])) ? htmlspecialchars(trim($_POST['filename'])) : '';
		$l_filename_old = (isset($_POST['filename_old'])) ? htmlspecialchars(trim($_POST['filename_old'])) : '';
		$l_layout = (isset($_POST['layout'])) ? trim($_POST['layout']) : '';
		if ($cms_type != 'cms_standard')
		{
			$l_template = (isset($_POST['template'])) ? trim($_POST['template']) : '';
			$extra_sql = ', style = \'' . str_replace("\'", "''", $l_template) . '\' ';
		}
		$l_global_blocks = (isset($_POST['global_blocks'])) ? intval($_POST['global_blocks']) : 0;
		$l_page_nav = (isset($_POST['page_nav'])) ? intval($_POST['page_nav']) : 0;
		$l_view = (isset($_POST['view'])) ? intval($_POST['view']) : 0;
		$l_edit_auth = (isset($_POST['edit_auth'])) ? intval($_POST['edit_auth']) : 0;

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

		if(($l_name == '') || ($l_layout == ''))
		{
			message_die(GENERAL_MESSAGE, $lang['Must_enter_layout']);
		}

		if($l_id != 0)
		{
			if ($l_filename_old != $l_filename)
			{
				@unlink($l_filename_old);

				if (substr($l_filename, strlen($l_filename) - (strlen(PHP_EXT) + 1), (strlen(PHP_EXT) + 1)) == ('.' . PHP_EXT))
				{
					$l_filename = ereg_replace("[^a-zA-Z0-9_]", "", substr(strtolower($l_filename), 0, strlen($l_filename) - (strlen(PHP_EXT) + 1))) . ('.' . PHP_EXT);
					if (file_exists($l_filename))
					{
						message_die(GENERAL_MESSAGE, $lang['CMS_FileAlreadyExists']);
					}
					else
					{
						$creation_success = @copy('index_cms_empty.' . PHP_EXT, $l_filename);
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

			$sql = "UPDATE " . $pages_table_name . "
				SET name = '" . str_replace("\'", "''", $l_name) . "',
				filename = '" . str_replace("\'", "''", $l_filename) . "',
				template = '" . str_replace("\'", "''", $l_layout) . "',
				global_blocks = '" . $l_global_blocks . "',
				page_nav = '" . $l_page_nav . "',
				view = '" . $l_view . "',
				edit_auth = '" . $l_edit_auth . "',
				groups = '" . $l_group . "'
				" . $extra_sql . "
				WHERE lid = '" . $l_id . "'";
			if(!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not insert data into layout table', $lang['Error'], __LINE__, __FILE__, $sql);
			}
			$message .= $lang['Layout_updated'];

			if(file_exists($layout_dir . ereg_replace($layout_extension, '.cfg', $l_layout)))
			{
				include($layout_dir . ereg_replace($layout_extension, '.cfg', $l_layout));

				$sql_test = "SELECT * FROM " . $block_position_table . " WHERE layout = '" . $l_id . "'";
				if(!$result_test = $db->sql_query($sql_test))
				{
					message_die(GENERAL_ERROR, 'Could not insert data into block position table', $lang['Error'], __LINE__, __FILE__, $sql);
				}

				while ($row_test = $db->sql_fetchrow($result_test))
				{
					$bp_found = false;
					for($i = 0; $i < $layout_count_positions; $i++)
					{
						if (($row_test['bposition'] == str_replace("\'", "''", $layout_block_positions[$i][1])) && ($row_test['pkey'] == str_replace("\'", "''", $layout_block_positions[$i][0])))
						{
							$bp_found = true;
						}
					}
					if ($bp_found == false)
					{
						$sql = "DELETE FROM " . $block_position_table . "
							WHERE layout = '" . $l_id . "'
								AND bposition = '" . $row_test['bposition'] . "'";
						if(!$result = $db->sql_query($sql))
						{
							message_die(GENERAL_ERROR, 'Could not remove data from blocks position table', $lang['Error'], __LINE__, __FILE__, $sql);
						}
					}
				}
				$db->sql_freeresult($result);

				for($i = 0; $i < $layout_count_positions; $i++)
				{

					$sql_test = "SELECT * FROM " . $block_position_table . "
						WHERE layout = '" . $l_id . "'
							AND bposition = '" . $layout_block_positions[$i][1] . "'
						LIMIT 1";
					if(!$result_test = $db->sql_query($sql_test))
					{
						message_die(GENERAL_ERROR, 'Could not insert data into block position table', $lang['Error'], __LINE__, __FILE__, $sql);
					}

					if (!($db->sql_fetchrow($result_test)))
					{
						$sql = "INSERT INTO " . $block_position_table . " (pkey, bposition, layout)
							VALUES ('" . str_replace("\'", "''", $layout_block_positions[$i][0]) . "', '" . str_replace("\'", "''", $layout_block_positions[$i][1]) . "', '" . $l_id . "')";
						if(!$result = $db->sql_query($sql))
						{
							message_die(GENERAL_ERROR, 'Could not insert data into block position table', $lang['Error'], __LINE__, __FILE__, $sql);
						}
					}
				}
			}
		}
		else
		{
			if ($l_filename_old != $l_filename)
			{
				@unlink($l_filename_old);
			}
			if (substr($l_filename, strlen($l_filename) - (strlen(PHP_EXT) + 1), (strlen(PHP_EXT) + 1)) == ('.' . PHP_EXT))
			{
				$l_filename = ereg_replace("[^a-zA-Z0-9_]", "", substr(strtolower($l_filename), 0, strlen($l_filename) - (strlen(PHP_EXT) + 1))) . ('.' . PHP_EXT);
				if (file_exists($l_filename))
				{
					message_die(GENERAL_MESSAGE, $lang['CMS_FileAlreadyExists']);
				}
				else
				{
					$creation_success = @copy('index_cms_empty.' . PHP_EXT, $l_filename);
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

			$sql = "INSERT INTO " . $pages_table_name . " (cms_id, name, filename, template, style, global_blocks, page_nav, view, edit_auth, groups)
				VALUES ('" . $cms_id . "','" . str_replace("\'", "''", $l_name) . "', '" . str_replace("\'", "''", $l_filename) . "', '" . str_replace("\'", "''", $l_layout) . "', '" . str_replace("\'", "''", $l_template) . "', '" . $l_global_blocks . "', '" . $l_page_nav . "', '" . $l_view . "', '" . $l_edit_auth . "', '" . $l_group . "')";
			if(!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not insert data into layout table', $lang['Error'], __LINE__, __FILE__, $sql);
			}
			$message .= $lang['Layout_added'];

			if(file_exists($layout_dir . ereg_replace($layout_extension, '.cfg', $l_layout)))
			{
				include($layout_dir . ereg_replace($layout_extension, '.cfg', $l_layout));

				$message .= '<br /><br />' . $lang['Layout_BP_added'];

				$layout_id = get_max_layout_id($pages_table_name);

				for($i = 0; $i < $layout_count_positions; $i++)
				{
					$sql = "INSERT INTO " . $block_position_table . " (pkey, bposition, layout)
						VALUES ('" . str_replace("\'", "''", $layout_block_positions[$i][0]) . "', '" . str_replace("\'", "''", $layout_block_positions[$i][1]) . "', '" . $layout_id . "')";
					if(!$result = $db->sql_query($sql))
					{
						message_die(GENERAL_ERROR, 'Could not insert data into block position table', $lang['Error'], __LINE__, __FILE__, $sql);
					}
				}
			}
		}

		$db->clear_cache('cms_');
		$message .= '<br /><br />' . sprintf($lang['Click_return_layoutadmin'], '<a href="' . append_sid('cms_adv.' . PHP_EXT) . '">', '</a>') . '<br /><br />';
		message_die(GENERAL_MESSAGE, $message);
	}
	elseif($action == 'delete')
	{
		if(!isset($_POST['confirm']))
		{
			$s_hidden_fields = '';
			$s_hidden_fields .= '<input type="hidden" name="mode" value="' . $mode . '" />';
			$s_hidden_fields .= '<input type="hidden" name="l_id" value="' . $l_id . '" />';
			$s_hidden_fields .= '<input type="hidden" name="action" value="' . $action . '" />';

			// Set template files
			$template->set_filenames(array('confirm' => CMS_TPL . 'confirm_body.tpl'));

			$template->assign_vars(array(
				'MESSAGE_TITLE' => $lang['Confirm'],
				'MESSAGE_TEXT' => $lang['Confirm_delete_item'],

				'L_YES' => $lang['Yes'],
				'L_NO' => $lang['No'],
				'L_ENABLED' => $lang['Enabled'],
				'L_DISABLED' => $lang['Disabled'],

				'S_CONFIRM_ACTION' => append_sid('cms_adv.' . PHP_EXT),
				'S_HIDDEN_FIELDS' => $s_hidden_fields
				)
			);
			$template->pparse('confirm');
			include(IP_ROOT_PATH . 'includes/page_tail.' . PHP_EXT);
			exit();
		}
		else
		{
			if($l_id != 0)
			{
				if ((substr($l_filename, strlen($l_filename) - (strlen(PHP_EXT) + 1), (strlen(PHP_EXT) + 1)) == ('.' . PHP_EXT)) && (file_exists($l_filename)))
				{
					@unlink($l_filename);
				}

				delete_layout($pages_table_name, $block_position_table, $l_id);

				$sql_list = "SELECT * FROM " . $blocks_table . " WHERE layout = '" . $l_id. "'";

				if(!($result_list = $db->sql_query($sql_list)))
				{
					message_die(GENERAL_ERROR, 'Could not query blocks list', $lang['Error'], __LINE__, __FILE__, $sql);
				}

				while($b_row = $db->sql_fetchrow($result_list))
				{
					delete_block($blocks_table, $b_row['bid']);
				}
				$db->sql_freeresult($result_list);

				$message = $lang['Layout_removed'] . '<br /><br />' . sprintf($lang['Click_return_layoutadmin'], '<a href="' . append_sid('cms_adv.' . PHP_EXT) . '">', '</a>') . '<br /><br />';

				message_die(GENERAL_MESSAGE, $message);
			}
			else
			{
				message_die(GENERAL_MESSAGE, $lang['No_layout_selected']);
			}
		}
	}
	elseif (($action == 'list') || ($action == false))
	{
		$template->set_filenames(array('body' => CMS_TPL . 'cms_adv_layout_list_body.tpl'));
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
			'L_LAYOUT_TITLE' => $lang['Custom_Pages'],
			'L_LAYOUT_TEXT' => $lang['Layout_Explain'],
			'L_STANDARD_PAGES' => $lang['Standard_Pages'],
			'L_CUSTOM_PAGES' => $lang['Custom_Pages'],
			'L_CHOOSE_LAYOUT' => $lang['Choose_Layout'],
			'L_CONFIGURE_BLOCKS' => $lang['CMS_Configure_Blocks'],
			'L_EDIT' => $lang['CMS_Edit'],
			'L_DELETE' => $lang['CSM_Delete'],
			'L_PREVIEW' => $lang['CMS_Preview'],
			'L_LAYOUT_ADD' => $lang['Layout_Add'],
			'S_LAYOUT_ACTION' => append_sid('cms_adv.' . PHP_EXT),
			'S_HIDDEN_FIELDS' => $s_hidden_fields
			)
		);

		if ($cms_id > 0)
		{
			$sql = "SELECT user_id, user_page_title FROM " . CMS_ADV_USERS_TABLE . " WHERE cms_id = " . $cms_id . "";
			if(!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not query layout table', $lang['Error'], __LINE__, __FILE__, $sql);
			}

			$cms_row = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);

			$template->assign_block_vars('cms_user', array(
				'CMS_PAGE_TITLE' => $cms_row['user_page_title'],
				'CMS_USERNAME' => colorize_username($cms_row['user_id']),
				)
			);
		}

		$template->assign_block_vars('layout', array());

		if( $cms_id > 0 )
		{
			$sql = "SELECT * FROM " . $pages_table_name . " WHERE cms_id = " . $cms_id . " ORDER BY lid";
			if(!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not query layout table', $lang['Error'], __LINE__, __FILE__, $sql);
			}
		}
		else
		{
			$sql = "SELECT * FROM " . $pages_table_name . $extra_where . " ORDER BY lid";
			if(!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not query layout table', $lang['Error'], __LINE__, __FILE__, $sql);
			}
		}

		$l_rows = $db->sql_fetchrowset($result);
		$db->sql_freeresult($result);
		$l_count = count($l_rows);

		$sql = "SELECT config_value FROM " . $config_table_name . " WHERE bid = '0' AND config_name = 'default_portal'";
		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not query layout table', $lang['Error'], __LINE__, __FILE__, $sql);
		}

		$c_row = $db->sql_fetchrowset($result);
		$db->sql_freeresult($result);

		$default_portal_id = $c_row[0]['config_value'];

		for($i = 0; $i < $l_count; $i++)
		{
			if ($l_rows[$i]['style'] != '' && ($cms_type != 'cms_standard'))
			{
				include(IP_ROOT_PATH . STYLES_PATH . $l_rows[$i]['style'] . '/theme_info.cfg');
				$template_name = $style_info['style_name'];
			}
			else
			{
				$template_name = $lang['CMS_ADV_DEFAULT_TEMPLATE'];
			}
			$row_class = (!($i % 2)) ? 'row1 row-center' : 'row2 row-center';

			$template->assign_block_vars('layout.l_row', array(
				'ROW_CLASS' => $row_class,
				'ROW_DEFAULT_STYLE' => ($l_rows[$i]['lid'] == $default_portal_id) ? 'font-weight:bold;' : '',
				'LAYOUT_ID' => $l_rows[$i]['lid'],
				'LAYOUT_NAME' => $l_rows[$i]['name'],
				'LAYOUT_FILENAME' => ($l_rows[$i]['filename'] == '') ? $lang['None'] : $l_rows[$i]['filename'],
				'LAYOUT_BLOCKS' => count_blocks_in_layout($blocks_table, '\'' . $l_rows[$i]['lid'] . '\'', false, true) . '/' . count_blocks_in_layout($blocks_table, '\'' . $l_rows[$i]['lid'] . '\'', false, false),
				'LAYOUT_LAYOUT' => $l_rows[$i]['template'],
				'LAYOUT_TEMPLATE' => $template_name,
				'U_PREVIEW_LAYOUT' => append_sid(($l_rows[$i]['filename'] == '') ? PORTAL_MG . '?page=' . $l_rows[$i]['lid'] : $l_rows[$i]['filename']),
				'U_EDIT_LAYOUT' => append_sid('cms_adv.' . PHP_EXT . '?mode=layouts' . $type_append_url . '&amp;l_id=' . $l_rows[$i]['lid'] . '&amp;action=edit'),
				'U_DELETE_LAYOUT' => append_sid('cms_adv.' . PHP_EXT . '?mode=layouts' . $type_append_url . '&amp;l_id=' . $l_rows[$i]['lid'] . '&amp;action=delete'),
				'U_LAYOUT' => append_sid('cms_adv.' . PHP_EXT . '?mode=blocks' . $type_append_url . '&amp;l_id=' . $l_rows[$i]['lid'])
				)
			);
		}
	}
}

if($mode == 'config')
{
	$sql = "SELECT * FROM " . $block_variable_table . "
		WHERE bid = 0
		ORDER BY bvid";
	if(!$result = $db->sql_query($sql))
	{
		message_die(CRITICAL_ERROR, 'Could not query site config information', $lang['Error'], __LINE__, __FILE__, $sql);
	}

	$var = array();
	while($row = $db->sql_fetchrow($result))
	{
		$var[$row['config_name']] = array();
		$var[$row['config_name']]['label'] = $row['label'];
		$var[$row['config_name']]['sub_label'] = $row['sub_label'];
		$var[$row['config_name']]['field_options'] = $row['field_options'];
		$var[$row['config_name']]['field_values'] = $row['field_values'];
		$var[$row['config_name']]['type'] = $row['type'];
		$var[$row['config_name']]['block'] = ereg_replace("_", " ", $row['block']);
	}
	$db->sql_freeresult($result);

	$sql = "SELECT * FROM " . $pages_table_name . " ORDER BY lid";
	if(!$result = $db->sql_query($sql))
	{
		message_die(CRITICAL_ERROR, 'Could not query layout information', $lang['Error'], __LINE__, __FILE__, $sql);
	}

	$template->set_filenames(array('body' => CMS_TPL . 'cms_adv_config_body.tpl'));
	$template->assign_var('CMS_PAGE_TITLE', $lang['CMS_Config']);

	$layout_options = '';
	$layout_values = '';
	$i = 0;
	while($row = $db->sql_fetchrow($result))
	{
		if(!$i)
		{
			$layout_options .= $row['name'];
			$layout_values .= $row['lid'];
		}else
		{
			$layout_options .= ',' . $row['name'];
			$layout_values .= ',' . $row['lid'];
		}
		$i++;
	}
	$db->sql_freeresult($result);

	// Pull all config data
	/*
	$sql = "SELECT * FROM " . $block_variable_table . " AS b RIGHT JOIN " . CMS_CONFIG_TABLE . " AS p
		USING (config_name)
		WHERE ((b.config_name IS NULL) OR (b.config_name IS NOT NULL))
			AND ((p.bid = 0))
		ORDER BY b.block, b.bvid, p.id";
	*/
	$sql = "SELECT * FROM " . $block_variable_table . " AS b, " . $config_table_name . " AS p
		WHERE (b.bid = 0)
			AND (p.bid = 0)
			AND (p.config_name = b.config_name)
		ORDER BY b.block, b.bvid, p.id";
	if(!$result = $db->sql_query($sql))
	{
		message_die(CRITICAL_ERROR, 'Could not query portal config information', $lang['Error'], __LINE__, __FILE__, $sql);
	}
	else
	{
		$controltype = array('1' => 'textbox', '2' => 'dropdown list', '3' => 'radio buttons', '4' => 'checkbox');
		while($row = $db->sql_fetchrow($result))
		{
			$portal_name = $row['config_name'];
			$portal_value = $row['config_value'];
			$var[$portal_name]['label'] = $lang['cms_var_' . $row['config_name']];
			$var[$portal_name]['sub_label'] = $lang['cms_var_' . $row['config_name'] . '_explain'];
			if($portal_name == 'default_portal')
			{
				$var[$portal_name]['label'] = $lang['Default_Portal'];
				$var[$portal_name]['sub_label'] = $lang['Default_Portal_Explain'];
				$var[$portal_name]['field_options'] = $layout_options;
				$var[$portal_name]['field_values'] = $layout_values;
				$var[$portal_name]['type'] = '2';
				$var[$portal_name]['block'] = '@Portal Config';
			}

			switch($var[$portal_name]['type'])
			{
				case '1':
					$field = '<input type="text" maxlength="255" size="40" name="' . $portal_name . '" value="' . $portal_value . '" class="post" />';
					break;
				case '2':
					$options = explode("," , $var[$portal_name]['field_options']);
					$values = explode("," , $var[$portal_name]['field_values']);
					$field = '<select name = "' . $portal_name . '">';
					$i = 0;
					while ($options[$i])
					{
						$tmp_option_val = ereg_replace("[^A-Za-z0-9]", "_", $options[$i]);
						$options[$i] = !empty($lang['cms_option_' . $tmp_option_val]) ? $lang['cms_option_' . $tmp_option_val] : $options[$i];
						$values[$i] = !empty($lang['cms_value_' . $tmp_option_val]) ? $lang['cms_value_' . $tmp_option_val] : $values[$i];
						$selected = ($portal_value == trim($values[$i])) ? 'selected' : '';
						$field .= '<option value = "' . trim($values[$i]) . '" ' . $selected . '>' . trim($options[$i]) . '</option>';
						$i++;
					}
					$field .= '</select>';
					break;
				case '3':
					$options = explode("," , $var[$portal_name]['field_options']);
					$values = explode("," , $var[$portal_name]['field_values']);
					$field = '';
					$i=0;
					while ($options[$i])
					{
						$tmp_option_val = ereg_replace("[^A-Za-z0-9]", "_", $options[$i]);
						$options[$i] = !empty($lang['cms_option_' . $tmp_option_val]) ? $lang['cms_option_' . $tmp_option_val] : $options[$i];
						$values[$i] = !empty($lang['cms_value_' . $tmp_option_val]) ? $lang['cms_value_' . $tmp_option_val] : $values[$i];
						$checked = ($portal_value == trim($values[$i])) ? 'checked="checked"' : '';
						$field .= '<input type="radio" name = "' . $portal_name . '" value = "' . trim($values[$i]) . '" ' . $checked . ' />' . trim($options[$i]) . '&nbsp;&nbsp;';
						$i++;
					}
					break;
				case '4':
					$checked = ($portal_value) ? 'checked="checked"' : '';
					$field = '<input type="checkbox" name="' . $portal_name . '" ' . $checked . ' />';
					break;
				default:
					$field = '';
			}

			$default_portal[$portal_name] = $portal_value;

			if($var[$portal_name]['type'] == '4')
			{
				$new[$portal_name] = (isset($_POST[$portal_name])) ? '1' : '0';
			}
			else
			{
				$new[$portal_name] = (isset($_POST[$portal_name])) ? $_POST[$portal_name] : $default_portal[$portal_name];
			}

			if(isset($_POST['save']))
			{
				$sql = "UPDATE " . $config_table_name . " SET
					config_value = '" . str_replace("\'", "''", $new[$portal_name]) . "'
					WHERE config_name = '$portal_name'";
				if(!$db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, "Failed to update configuration for $portal_name", "", __LINE__, __FILE__, $sql);
				}
			}
			else
			{
				$is_block = ($var[$portal_name]['block']!='@Portal Config') ? 'block ' : '';
				$template->assign_block_vars('cms_block', array(
					'L_FIELD_LABEL' => $var[$portal_name]['label'],
					'L_FIELD_SUBLABEL' => '<br /><br /><span class="gensmall">' . $var[$portal_name]['sub_label'] . ' [ ' . ereg_replace("@", "", $var[$portal_name]['block']) . ' ' . $is_block . ']</span>',
					'FIELD' => $field
					)
				);
			}
		}
		$db->sql_freeresult($result);

		if(isset($_POST['save']))
		{
			$db->clear_cache('cms_');
			$message = $lang['CMS_Config_updated'] . '<br /><br />' . sprintf($lang['CMS_Click_return_config'], '<a href="' . append_sid('cms_adv.' . PHP_EXT . '?mode=config') . '">', '</a>') . '<br /><br />' . sprintf($lang['CMS_Click_return_cms'], '<a href="' . append_sid('cms_adv.' . PHP_EXT) . '">', '</a>') . '<br /><br />';

			message_die(GENERAL_MESSAGE, $message);
		}
	}

	$template->assign_vars(array(
		'S_CONFIG_ACTION' => append_sid('cms_adv.' . PHP_EXT),
		'L_CONFIGURATION_TITLE' => $lang['CMS_Config'],
		'L_CONFIGURATION_EXPLAIN' => $lang['Portal_Explain'],
		'L_GENERAL_CONFIG' => $lang['Portal_General_Config'],
		'L_SUBMIT' => $lang['Submit'],
		'L_RESET' => $lang['Reset']
		)
	);
}

if($mode == 'userslist')
{

	$template->set_filenames(array('body' => CMS_TPL . 'cms_adv_userslist_body.tpl'));
	$template->assign_var('CMS_PAGE_TITLE', $lang['CMS_Pages']);

	$sql = "SELECT DISTINCT user_id FROM " . CMS_ADV_USERS_TABLE . " ORDER BY cms_id";
	if(!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not query layout table', $lang['Error'], __LINE__, __FILE__, $sql);
	}

	$u_rows = $db->sql_fetchrowset($result);
	$db->sql_freeresult($result);
	$u_count = count($u_rows);

	if($u_count)
	{
		for($i = 0; $i < $u_count; $i++)
		{
			$row_class = (!($i % 2)) ? 'row1 row-center' : 'row2 row-center';

			$sql = "SELECT user_email	FROM " . USERS_TABLE . " WHERE user_id = " . $u_rows[$i]['user_id'];
			if(!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not query layout table', $lang['Error'], __LINE__, __FILE__, $sql);
			}
			$row = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);

			$email_uri = ($board_config['board_email_form']) ? append_sid(PROFILE_MG . '?mode=email&amp;' . POST_USERS_URL .'=' . $u_rows[$i]['user_id']) : 'mailto:' . $row['user_email'];
			$email_img = '<a href="' . $email_uri . '"><img src="' . $images['email_topic'] . '" alt="' . $lang['Send_email'] . '" title="' . $lang['Send_email'] . '" /></a>';

			$temp_url = append_sid('privmsg.' . PHP_EXT . '?mode=post&amp;' . POST_USERS_URL . '=' . $u_rows[$i]['user_id']);
			$pm_img = '<a href="' . $temp_url . '"><img src="' . $images['cms_pm'] . '" alt="' . $lang['Send_private_message'] . '" title="' . $lang['Send_private_message'] . '" /></a>';

			$template->assign_block_vars('users_row', array(
				'ROW_CLASS' => $row_class,
				'CMS_USERNAME' => colorize_username($u_rows[$i]['user_id']),
				'CMS_PM_IMG' => $pm_img,
				'CMS_EMAIL_IMG' => $email_img,
				)
			);

			$sql = "SELECT * FROM " . CMS_ADV_USERS_TABLE . " WHERE user_id = " . $u_rows[$i]['user_id'];
			if(!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not query layout table', $lang['Error'], __LINE__, __FILE__, $sql);
			}

			$cms_rows = $db->sql_fetchrowset($result);
			$db->sql_freeresult($result);
			$cms_count = count($cms_rows);

			foreach ($cms_rows as $cms_data)
			{
				if ($cms_type != 'cms_standard')
				{
					include(IP_ROOT_PATH . STYLES_PATH . $cms_data['user_template'] . '/theme_info.cfg');
				}

				$template->assign_block_vars('users_row.user_data', array(
					'CMS_UID' => $cms_data['cms_id'],
					'U_CMS_EDIT' => append_sid('cms_adv.' . PHP_EXT . '?mode=layouts&amp;cms_id=' . $cms_data['cms_id']),
					'IMG_TURNED' => $images['turn_on'],
					'CMS_USER_TITLE' => $cms_data['user_page_title'],
					'CMS_USER_DESC' => $cms_data['user_page_desc'],
					'CMS_USER_TEMPLATE' => $style_info['style_name'],
					'U_CMS_FOLDER' => './users/' . $cms_data['user_folder'],
					)
				);
			}


		}
	}
	else
	{
		$template->assign_block_vars('cms_no_users', array());
	}
}

if (($mode == false))
{
	$template->set_filenames(array('body' => CMS_TPL . 'cms_adv_index_body.tpl'));
	$template->assign_var('CMS_PAGE_TITLE', $lang['CMS_Pages']);
}

$db->clear_cache('cms_');

$template->pparse('body');

include(IP_ROOT_PATH . 'includes/page_tail.' . PHP_EXT);

?>