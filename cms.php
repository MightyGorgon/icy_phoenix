<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

define('IN_PHPBB', true);
define('IN_CMS', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.' . $phpEx);
$common_cms_template = $phpbb_root_path . 'templates/common/cms/';
include_once($phpbb_root_path . 'includes/functions_cms_admin.' . $phpEx);

$js_temp =  array('js/cms.js', 'scriptaculous/unittest.js');

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

$cms_type = 'cms_standard';

$mode_array = array('blocks', 'blocks_adv', 'config', 'layouts', 'layouts_adv', 'layouts_special', 'smilies');
$mode = (!empty($_GET['mode']) ? $_GET['mode'] : (!empty($_POST['mode']) ? $_POST['mode'] : false));
$mode = (in_array($mode, $mode_array) ? $mode : false);

$action_array = array('add', 'delete', 'duplicate', 'edit', 'editglobal', 'list', 'save');
$action = (!empty($_GET['action']) ? $_GET['action'] : (!empty($_POST['action']) ? $_POST['action'] : false));
$action = (isset($_POST['add']) ? 'add' : $action);
$action = (isset($_POST['save']) ? 'save' : $action);
$action = (isset($_POST['action_duplicate']) ? 'duplicate' : $action);
$action = (in_array($action, $action_array) ? $action : false);

$preview_block = isset($_POST['preview']) ? true : false;

$ls_id = (isset($_GET['ls_id']) ? intval($_GET['ls_id']) : (isset($_POST['ls_id']) ? intval($_POST['ls_id']) : false));
$ls_id = ($ls_id < 0) ? false : $ls_id;

$l_id = (isset($_GET['l_id']) ? intval($_GET['l_id']) : (isset($_POST['l_id']) ? intval($_POST['l_id']) : false));
$l_id = ($l_id < 0) ? false : $l_id;

$b_id = (isset($_GET['b_id']) ? intval($_GET['b_id']) : (isset($_POST['b_id']) ? intval($_POST['b_id']) : false));
$b_id = ($b_id < 0) ? false : $b_id;

$bv_id = (isset($_GET['bv_id']) ? intval($_GET['bv_id']) : (isset($_POST['bv_id']) ? intval($_POST['bv_id']) : false));
$bv_id = ($bv_id < 0) ? false : $bv_id;

$is_updated = (isset($_GET['updated']) ? $_GET['updated'] : (isset($_POST['updated']) ? $_POST['updated'] : false));

$redirect_append = '';
if (($mode == 'blocks') || ($mode == 'blocks_adv'))
{
	if ($action == 'edit')
	{
		$redirect_append = '&mode=' . $mode . '&action=edit&l_id=' . $l_id . '&b_id=' . $b_id;
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
elseif (($mode == 'layouts') || ($mode == 'layouts_adv'))
{
	$redirect_append = '&mode=' . $mode . '&l_id=' . $l_id;
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
	redirect(append_sid(LOGIN_MG . '?redirect=cms.' . $phpEx . '&admin=1' . $redirect_append, true));
}

if(isset($_POST['block_reset']))
{
	if ($ls_id == false)
	{
		redirect(append_sid('cms.' . $phpEx . '?mode=blocks&action=list&l_id=' . $l_id, true));
	}
	else
	{
		redirect(append_sid('cms.' . $phpEx . '?mode=blocks&action=list&ls_id=' . $ls_id, true));
	}
}

if(isset($_POST['cancel']))
{
	redirect(append_sid('cms.' . $phpEx, true));
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

include($phpbb_root_path . 'includes/functions_post.' . $phpEx);
include($phpbb_root_path . 'includes/bbcode.' . $phpEx);
if(!file_exists($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_cms.' . $phpEx))
{
	$board_config['default_lang'] = 'english';
}
include_once($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_admin.' . $phpEx);
include_once($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_cms.' . $phpEx);
include_once($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_blocks.' . $phpEx);

if ($mode == 'smilies')
{
	generate_smilies('window');
	exit;
}

$page_title = $lang['Home'];
$meta_description = '';
$meta_keywords = '';
$template->assign_vars(array('S_CMS_AUTH' => true));
include($phpbb_root_path . 'includes/page_header.' . $phpEx);

if ($board_config['cms_dock'] == true)
{
	$template->assign_block_vars('cms_dock_on', array());
}
else
{
	$template->assign_block_vars('cms_dock_off', array());
}

if(($mode == 'blocks') || ($mode == 'blocks_adv'))
{
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
		$message = isset($_POST['message']) ? $_POST['message'] : '';
		$message = stripslashes($message);

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

				$b_info['bposition'] = (isset($_POST['bposition'])) ? trim($_POST['bposition']) : $b_info['bposition'];
				$position = get_block_positions(CMS_BLOCK_POSITION_TABLE, $l_id_list, $b_info['bposition']);

				$block_dir = $phpbb_root_path . 'blocks';
				$blocks = opendir($block_dir);

				$block_content_file_old = $b_info['blockfile'];
				$b_info['blockfile'] = (isset($_POST['blockfile'])) ? trim($_POST['blockfile']) : $b_info['blockfile'];
				$blockfile = '<option value="">-- ' . $lang['B_Text_Block'] . ' --</option>';
				while ($file = readdir($blocks))
				{
					$pos = strpos($file, 'blocks_imp_');
					if (($pos == 0) && ($pos !== false))
					{
						$pos = strpos($file, '.' . $phpEx);
						if ($pos !== false)
						{
							$temp = ereg_replace('\.' . $phpEx, '', $file);
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
			$position = get_block_positions(CMS_BLOCK_POSITION_TABLE, $l_id_list, $b_info['bposition']);

			$block_dir = $phpbb_root_path . 'blocks';
			$blocks = opendir($block_dir);

			$block_content_file_old = $b_info['blockfile'];
			$b_info['blockfile'] = (isset($_POST['blockfile'])) ? trim($_POST['blockfile']) : $b_info['blockfile'];
			$blockfile = '<option value="">-- ' . $lang['B_Text_Block'] . ' --</option>';
			while ($file = readdir($blocks))
			{
				$pos = strpos($file, 'blocks_imp_');
				if (($pos == 0) && ($pos !== false))
				{
					$pos = strpos($file, '.' . $phpEx);
					if ($pos !== false)
					{
						$temp = ereg_replace('\.' . $phpEx, '', $file);
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
			$template->set_filenames(array('body' => CMS_TPL . 'cms_block_edit_text_body.tpl'));
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
			$template->set_filenames(array('body' => CMS_TPL . 'cms_block_edit_body.tpl'));
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
				$sql = "SELECT * FROM " . CMS_CONFIG_TABLE . " AS c, " . CMS_BLOCK_VARIABLE_TABLE . " AS bv
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
							'L_FIELD_SUBLABEL' => '<br /><br /><span class="gensmall">' . $cms_field[$row['config_name']]['sub_label'] . ' [ ' . ereg_replace("@", "", $cms_field[$row['config_name']]['block']) . ' ' . $is_block . ']</span>',
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
				}
				$db->sql_freeresult($result);
			}
			else
			{
				if(file_exists($phpbb_root_path . '/blocks/' . $block_content_file . '.cfg'))
				{
					$block_count_variables = 0;
					include($phpbb_root_path . '/blocks/' . $block_content_file . '.cfg');
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
								'L_FIELD_SUBLABEL' => '<br /><br /><span class="gensmall">' . $cms_field[$row['config_name']]['sub_label'] . ' [ ' . ereg_replace("@", "", $cms_field[$row['config_name']]['block']) . ' ' . $is_block . ']</span>',
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
			$template->set_filenames(array('body' => CMS_TPL . 'cms_block_content_body.tpl'));
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
				$bbcode_uid = $b_info['block_bbcode_uid'];
				//$bbcode->allow_html = true;
				$bbcode->allow_html = false;
				$bbcode->allow_bbcode = true;
				$bbcode->allow_smilies = true;
				$preview_message = $bbcode->parse($message, $bbcode_uid);
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
			'S_BLOCKS_ACTION' => append_sid('cms.' . $phpEx . $s_append_url),
			'S_HIDDEN_FIELDS' => $s_hidden_fields
			)
		);

		// BBCBMG - BEGIN
		//$bbcbmg_in_acp = true;
		include($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_bbcb_mg.' . $phpEx);
		include($phpbb_root_path . 'includes/bbcb_mg.' . $phpEx);
		$template->assign_var_from_handle('BBCB_MG', 'bbcb_mg');
		// BBCBMG - END
		// BBCBMG SMILEYS - BEGIN
		generate_smilies('inline');
		include($phpbb_root_path . 'includes/bbcb_smileys_mg.' . $phpEx);
		$template->assign_var_from_handle('BBCB_SMILEYS_MG', 'bbcb_smileys_mg');
		// BBCBMG SMILEYS - END

		$template->pparse('body');

		include($phpbb_root_path . 'includes/page_tail.' . $phpEx);
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

		$bbcode_uid = '';
		$b_content = addslashes($b_content);
		if($b_type == true)
		{
			if(!empty($b_content))
			{
				$bbcode_uid = make_bbcode_uid();
				//$b_content = prepare_message(trim($b_content), true, true, true, $bbcode_uid);
				//$b_content = str_replace("\'", "''", $b_content);
			}
		}

		if($b_id)
		{
			$sql = "UPDATE " . CMS_BLOCKS_TABLE . "
				SET
				title = '" . str_replace("\'", "''", $b_title) . "',
				bposition = '" . $b_bposition . "',
				active = '" . $b_active . "',
				type = '" . $b_type . "',
				content = '" . $b_content . "',
				block_bbcode_uid = '" . $bbcode_uid . "',
				blockfile = '" . $b_blockfile . "',
				layout = '" . $layout_value . "',
				layout_special = '" . $layout_special_value . "',
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

			if(file_exists($phpbb_root_path . '/blocks/' . $b_blockfile . '.cfg'))
			{
				include($phpbb_root_path . '/blocks/' . $b_blockfile . '.cfg');

				// let's empty the previously created config vars...
				$sql = "SELECT * FROM " . CMS_CONFIG_TABLE . " WHERE bid = '" . $b_id . "'";
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
				if(file_exists($phpbb_root_path . '/blocks/' . $b_blockfile . '.cfg'))
				{
					include($phpbb_root_path . '/blocks/' . $b_blockfile . '.cfg');

					//$message .= '<br /><br />' . $lang['B_BV_added'];

					for($i = 0; $i < $block_count_variables; $i++)
					{
						if ((!empty($_POST[$block_variables[$i][2]])) || ($_POST[$block_variables[$i][2]] == '0'))
						{
							$block_variables[$i][7] = str_replace("\'", "''", $_POST[$block_variables[$i][2]]);
						}

						$existing = get_existing_block_var(CMS_BLOCK_VARIABLE_TABLE, $b_id, $block_variables[$i][2]);

						if(!$existing)
						{
							$sql = "INSERT INTO " . CMS_BLOCK_VARIABLE_TABLE . " (bid, label, sub_label, config_name, field_options, field_values, type, block)
								VALUES ('" . $b_id ."', '" . str_replace("\'", "''", $block_variables[$i][0]) . "', '" . str_replace("\'", "''", $block_variables[$i][1]) . "', '" . str_replace("\'", "''", $block_variables[$i][2]) . "', '" . str_replace("\'", "''", $block_variables[$i][3]) . "', '" . $block_variables[$i][4] . "', '" . $block_variables[$i][5] . "', '" . str_replace("\'", "''", $block_variables[$i][6]) . "')";
							if(!$result = $db->sql_query($sql))
							{
								message_die(GENERAL_ERROR, 'Could not insert data into block variable table', $lang['Error'], __LINE__, __FILE__, $sql);
							}

							$sql = "INSERT INTO " . CMS_CONFIG_TABLE . " (bid, config_name, config_value)
								VALUES ('" . $b_id ."', '" . str_replace("\'", "''", $block_variables[$i][2]) . "', '" . $block_variables[$i][7] . "')";
							if(!$result = $db->sql_query($sql))
							{
								message_die(GENERAL_ERROR, 'Could not insert data into portal config table', $lang['Error'], __LINE__, __FILE__, $sql);
							}
						}
						else
						{
							$sql = "UPDATE " . CMS_CONFIG_TABLE . " SET config_value = '" . $block_variables[$i][7] . "'
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
			$weight = get_max_blocks_position(CMS_BLOCKS_TABLE, $id_var_value, $b_bposition) + 1;
			$b_id = get_max_block_id(CMS_BLOCKS_TABLE) + 1;

			$sql = "INSERT INTO " . CMS_BLOCKS_TABLE . " (bid, title, content, bposition, weight, active, type, blockfile, view, layout, layout_special, block_bbcode_uid, border, titlebar, background, local, groups) VALUES ('" . $b_id . "', '" . str_replace("\'", "''", $b_title) . "', '" . $b_content . "', '" . str_replace("\'", "''", $b_bposition) . "', '" . $weight . "', '" . $b_active . "', '" . $b_type . "', '" . str_replace("\'", "''", $b_blockfile) . "', '" . $b_view . "', '" . $layout_value . "', '" . $layout_special_value . "', '" . $bbcode_uid . "', '" . $b_border . "', '" . $b_titlebar . "', '" . $b_background . "', '" . $b_local . "', '" . $b_group . "')";
			$message = $lang['Block_added'];
			if(!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not insert data into blocks table', $lang['Error'], __LINE__, __FILE__, $sql);
			}

			if(!empty($b_blockfile))
			{
				if(file_exists($phpbb_root_path . '/blocks/' . $b_blockfile . '.cfg'))
				{
					include($phpbb_root_path . '/blocks/' . $b_blockfile . '.cfg');

					//$message .= '<br /><br />' . $lang['B_BV_added'];

					for($i = 0; $i < $block_count_variables; $i++)
					{
						if ((!empty($_POST[$block_variables[$i][2]])) || ($_POST[$block_variables[$i][2]] == '0'))
						{
							$block_variables[$i][7] = str_replace("\'", "''", $_POST[$block_variables[$i][2]]);
						}

						$sql = "INSERT INTO " . CMS_BLOCK_VARIABLE_TABLE . " (bid, label, sub_label, config_name, field_options, field_values, type, block)
							VALUES ('" . $b_id . "', '" . str_replace("\'", "''", $block_variables[$i][0]) . "', '" . str_replace("\'", "''", $block_variables[$i][1]) . "', '" . str_replace("\'", "''", $block_variables[$i][2]) . "', '" . str_replace("\'", "''", $block_variables[$i][3]) . "', '" . $block_variables[$i][4] . "', '" . $block_variables[$i][5] . "', '" . str_replace("\'", "''", $block_variables[$i][6]) . "')";
						if(!$result = $db->sql_query($sql))
						{
							message_die(GENERAL_ERROR, 'Could not insert data into block variable table', $lang['Error'], __LINE__, __FILE__, $sql);
						}

						$sql = "INSERT INTO " . CMS_CONFIG_TABLE . " (bid, config_name, config_value)
							VALUES ('" . $b_id ."', '" . str_replace("\'", "''", $block_variables[$i][2]) . "', '" . $block_variables[$i][7] . "')";
						if(!$result = $db->sql_query($sql))
						{
							message_die(GENERAL_ERROR, 'Could not insert data into portal config table', $lang['Error'], __LINE__, __FILE__, $sql);
						}
					}
				}
			}
		}
		fix_weight_blocks($id_var_value, $table_name);
		$db->clear_cache('cms_');
		$message .= '<br /><br />' . $lang['Block_updated'] . '<br /><br />' . sprintf($lang['Click_return_blocksadmin'], '<a href="' . append_sid('cms.' . $phpEx . '?mode=blocks&amp;' . $id_var_name . '=' . $redirect_l_id) . '">', '</a>') . '<br />';
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
				'S_CONFIRM_ACTION' => append_sid('cms.' . $phpEx),
				'S_HIDDEN_FIELDS' => $s_hidden_fields
				)
			);
			$template->pparse('confirm');
			include($phpbb_root_path . 'includes/page_tail.' . $phpEx);
			exit();
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

				$db->clear_cache('cms_');

				$message = $lang['Block_removed'] . '<br /><br />' . sprintf($lang['Click_return_blocksadmin'], '<a href="' . append_sid('cms.' . $phpEx . '?mode=blocks&amp;' . $id_var_name . '=' . $id_var_value . $redirect_action) . '">', '</a>') . '<br />';

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
						FROM " . CMS_BLOCKS_TABLE . "
						WHERE bid = '" . intval($blocks_dup[$i]) . "'";
					if(!$result = $db->sql_query($sql))
					{
						message_die(GENERAL_ERROR, 'Could not query blocks table', $lang['Error'], __LINE__, __FILE__, $sql);
					}

					$b_info = $db->sql_fetchrow($result);
					$db->sql_freeresult($result);

					$weight = get_max_blocks_position(CMS_BLOCKS_TABLE, $id_var_value, $b_bposition, 'layout') + 1;
					$b_id = get_max_block_id(CMS_BLOCKS_TABLE) + 1;

					$sql = "INSERT INTO " . CMS_BLOCKS_TABLE . " (bid, title, content, bposition, weight, active, type, blockfile, view, layout, layout_special, block_bbcode_uid, border, titlebar, background, local, groups) VALUES ('" . $b_id . "', '" . $b_info['title'] . "', '" . $b_info['content'] . "', '" . $b_info['bposition'] . "', '" . $b_info['weight'] . "', '" . $b_info['active'] . "', '" . $b_info['type'] . "', '" . $b_info['blockfile'] . "', '" . $b_info['view'] . "', '" . (($id_var_name == 'l_id') ? $id_var_value : 0) . "', '" . (($id_var_name == 'ls_id') ? $id_var_value : 0) . "', '" . $b_info['block_bbcode_uid'] . "', '" . $b_info['border'] . "', '" . $b_info['titlebar'] . "', '" . $b_info['background'] . "', '" . $b_info['local'] . "', '" . $b_info['groups'] . "')";
					if(!$result = $db->sql_query($sql))
					{
						message_die(GENERAL_ERROR, 'Could not insert data into blocks table', $lang['Error'], __LINE__, __FILE__, $sql);
					}

					$sql_cfg = "SELECT * FROM " . CMS_CONFIG_TABLE . " AS c, " . CMS_BLOCK_VARIABLE_TABLE . " AS bv
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
						$sql = "INSERT INTO " . CMS_BLOCK_VARIABLE_TABLE . " (bid, label, sub_label, config_name, field_options, field_values, type, block)
							VALUES ('" . $b_id . "', '" . $row_cfg['label'] . "', '" . $row_cfg['sub_label'] . "', '" . $row_cfg['config_name'] . "', '" . $row_cfg['field_options'] . "', '" . $row_cfg['field_values'] . "', '" . $row_cfg['type'] . "', '" . $row_cfg['block'] . "')";
						if(!$result = $db->sql_query($sql))
						{
							message_die(GENERAL_ERROR, 'Could not insert data into block variable table', $lang['Error'], __LINE__, __FILE__, $sql);
						}

						$sql = "INSERT INTO " . CMS_CONFIG_TABLE . " (bid, config_name, config_value)
							VALUES ('" . $b_id ."', '" . $row_cfg['config_name'] . "', '" . $row_cfg['config_value'] . "')";
						if(!$result = $db->sql_query($sql))
						{
							message_die(GENERAL_ERROR, 'Could not insert data into portal config table', $lang['Error'], __LINE__, __FILE__, $sql);
						}
					}
				}
			}
			fix_weight_blocks($id_var_value, $table_name);
			$db->clear_cache('cms_');
			$message = '<br /><br />' . $lang['Blocks_duplicated'] . '<br /><br />' . sprintf($lang['Click_return_blocksadmin'], '<a href="' . append_sid('cms.' . $phpEx . '?mode=blocks&amp;' . $id_var_name . '=' . $id_var_value) . '">', '</a>') . '<br />';
			message_die(GENERAL_MESSAGE, $message);
		}
		else
		{
			$template->set_filenames(array('body' => CMS_TPL . 'cms_blocks_duplicate_body.tpl'));
			$template->assign_var('CMS_PAGE_TITLE', $lang['Blocks_Title']);

			$l_row = get_layout_name($table_name, $field_name, $id_var_value);
			$l_name = $l_row['name'];
			$l_filename = $l_row['filename'];

			if (($id_var_value == 0) || ($id_var_name == 'ls_id'))
			{
				$page_url = append_sid(PORTAL_MG);
				$l_id_list = "'0'";
				$l_name = $l_filename . '.' . $phpEx;
			}
			else
			{
				if ($id_var_name == 'l_id')
				{
					if (($l_filename != '') && file_exists($l_filename . '.' . $phpEx))
					{
						$page_url = append_sid($l_filename . '.' . $phpEx);
					}
					else
					{
						$page_url = (substr($l_name, strlen($l_name) - (strlen($phpEx) + 1), (strlen($phpEx) + 1)) == ('.' . $phpEx)) ? append_sid($l_name) : append_sid(PORTAL_MG . '?page=' . $id_var_value);
					}
					$l_name = ($l_name == '') ? $lang['Portal'] : $l_name;
				}
				else
				{
					$page_url = append_sid($l_filename . '.' . $phpEx);
					$l_name = $l_filename . '.' . $phpEx;
				}
				$l_id_list = "'" . $id_var_value . "'";
			}

			$sql = "SELECT bposition, pkey FROM " . CMS_BLOCK_POSITION_TABLE . " WHERE layout IN (" . $l_id_list . ")";
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
							FROM " . CMS_BLOCKS_TABLE . " AS b, " . CMS_LAYOUT_TABLE . " AS l
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
				'L_MOVE_UP' => $lang['B_Move_Up'],
				'L_MOVE_DOWN' => $lang['B_Move_Down'],
				'S_BLOCKS_ACTION' => append_sid('cms.' . $phpEx),
				'S_HIDDEN_FIELDS' => $s_hidden_fields
				)
			);

			for($i = 0; $i < $b_count; $i++)
			{
				$b_id = $b_rows[$i]['bid'];
				$b_weight = $b_rows[$i]['weight'];
				$b_position = $b_rows[$i]['bposition'];
				$b_position_l = !empty($lang['cms_pos_' . $position[$b_position]]) ? $lang['cms_pos_' . $position[$b_position]] : $row['pkey'];

				$row_color = (!($i % 2)) ? $theme['td_color1'] : $theme['td_color2'];
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
					'ROW_COLOR' => '#' . $row_color,
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
					'U_EDIT' => append_sid('cms.' . $phpEx . '?mode=blocks&amp;action=edit&amp;' . $id_var_name . '=' . $id_var_value . '&amp;b_id=' . $b_id),
					'U_DELETE' => append_sid('cms.' . $phpEx . '?mode=blocks&amp;action=delete&amp;' . $id_var_name . '=' . $id_var_value . '&amp;b_id=' . $b_id),
					'U_MOVE_UP' => append_sid('cms.' . $phpEx . '?mode=blocks' . $redirect_action . '&amp;' . $id_var_name . '=' . $id_var_value . '&amp;move=1&amp;b_id=' . $b_id . '&amp;weight=' . $b_weight . '&amp;pos=' . $b_position),
					'U_MOVE_DOWN' => append_sid('cms.' . $phpEx . '?mode=blocks' . $redirect_action . '&amp;' . $id_var_name . '=' . $id_var_value . '&amp;move=0&amp;b_id=' . $b_id . '&amp;weight=' . $b_weight . '&amp;pos=' . $b_position)
					)
				);
			}
		}
	}
	elseif(($id_var_value != 0) || ($action == 'editglobal'))
	{
		if(isset($_POST['action_update']))
		{
			$blocks_upd = array();
			$blocks_upd = $_POST['block'];
			$blocks_upd_n = count($blocks_upd);
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

			if (($mode == 'blocks') || ($action == 'editglobal'))
			{
				$b_rows = get_blocks_from_layouts(CMS_BLOCKS_TABLE, $block_layout_field, $l_id_list, $sql_no_gb);
				$b_count = count($b_rows);

				for($i = 0; $i < $b_count; $i++)
				{
					$b_active = empty($blocks_upd) ? 0 : (in_array($b_rows[$i]['bid'], $blocks_upd) ? 1 : 0);
					$sql = "UPDATE " . CMS_BLOCKS_TABLE . "
									SET active = '" . $b_active . "'
									WHERE bid = '" . $b_rows[$i]['bid'] . "'";
					if(!$result = $db->sql_query($sql))
					{
						message_die(GENERAL_ERROR, 'Could not update blocks table', $lang['Error'], __LINE__, __FILE__, $sql);
					}
				}
				fix_weight_blocks($id_var_value, $table_name);
				$db->clear_cache('cms_');
				$message = '<br /><br />' . $lang['Blocks_updated'] . '<br /><br />' . sprintf($lang['Click_return_blocksadmin'], '<a href="' . append_sid('cms.' . $phpEx . '?mode=' . $mode . '&amp;' . $id_var_name . '=' . $id_var_value . $action_append) . '">', '</a>') . '<br />';
				message_die(GENERAL_MESSAGE, $message);
			}
			else
			{
				$sql = "SELECT bposition FROM " . CMS_BLOCK_POSITION_TABLE . " WHERE layout = " . $l_id_list . "";
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
							$sql = "UPDATE " . CMS_BLOCKS_TABLE . "
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
				redirect(append_sid('cms.' . $phpEx . '?mode=blocks_adv&' . $id_var_name . '=' . $id_var_value . '&updated=true'));
			}
		}

		$template_file = ($mode == 'blocks') ? 'cms_blocks_list_body.tpl' : 'cms_blocks_adv_list_body.tpl';
		$template->set_filenames(array('body' => CMS_TPL . $template_file));
		$template->assign_var('CMS_PAGE_TITLE', $lang['Blocks_Title']);

		$move = (isset($_GET['move'])) ? $_GET['move'] : -1;

		if(($mode == 'blocks') && (($move == '0') || ($move == '1')))
		{
			$b_weight = (isset($_GET['weight'])) ? $_GET['weight'] : 0;
			$b_position = (isset($_GET['pos'])) ? $_GET['pos'] : 0;
			$gb_pos = array('gt', 'gb', 'gl', 'gr', 'hh', 'hl', 'hc', 'fc', 'fr', 'ff');
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
				if(!$result = $db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'Could not update data in blocks table', $lang['Error'], __LINE__, __FILE__, $sql);
				}
				$sql = "UPDATE " . CMS_BLOCKS_TABLE . " SET weight = '" . $temp . "' WHERE bid = '" . $b_id . "'";
				if(!$result = $db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'Could not update data in blocks table', $lang['Error'], __LINE__, __FILE__, $sql);
				}
				fix_weight_blocks($id_var_value, $table_name);
			}
		}

		$l_row = get_layout_name($table_name, $field_name, $id_var_value);
		$l_name = $l_row['name'];
		$l_filename = $l_row['filename'];

		if ($action == 'editglobal')
		{
			$page_url = append_sid(PORTAL_MG);
			$l_id_list = "'0'";
		}
		else
		{
			if ($id_var_name == 'l_id')
			{
				if (($l_filename != '') && file_exists($l_filename . '.' . $phpEx))
				{
					$page_url = append_sid($l_filename . '.' . $phpEx);
				}
				else
				{
					$page_url = (substr($l_name, strlen($l_name) - (strlen($phpEx) + 1), (strlen($phpEx) + 1)) == ('.' . $phpEx)) ? append_sid($l_name) : append_sid(PORTAL_MG . '?page=' . $id_var_value);
				}
			}
			else
			{
				$page_url = append_sid($l_filename . '.' . $phpEx);
			}
			$l_id_list = "'" . $id_var_value . "'";
		}

		if ($id_var_name == 'l_id')
		{
			$l_name = ($l_name == '') ? $lang['Portal'] : $l_name;
		}
		else
		{
			$l_name = $l_filename . '.' . $phpEx;
		}

		if (($id_var_name == 'l_id') && ($id_var_value > 0))
		{
			$template->assign_block_vars('duplicate_switch', array());
		}

		if (($mode == 'blocks_adv') && ($is_updated == true))
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
			'L_MOVE_UP' => $lang['B_Move_Up'],
			'L_MOVE_DOWN' => $lang['B_Move_Down'],
			'S_BLOCKS_ACTION' => append_sid('cms.' . $phpEx),
			'S_HIDDEN_FIELDS' => $s_hidden_fields
			)
		);

		if (($mode == 'blocks') || ($action == 'editglobal'))
		{
			$b_rows = get_blocks_from_layouts(CMS_BLOCKS_TABLE, $block_layout_field, $l_id_list, '');
			$b_count = count($b_rows);

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

					$row_color = (!($else_counter % 2)) ? $theme['td_color1'] : $theme['td_color2'];
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
						'ROW_COLOR' => '#' . $row_color,
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
						'U_EDIT' => append_sid('cms.' . $phpEx . '?mode=' . $mode . '&amp;action=edit&amp;' . $id_var_name . '=' . $id_var_value . '&amp;b_id=' . $b_id),
						'U_DELETE' => append_sid('cms.' . $phpEx . '?mode=' . $mode . '&amp;action=delete&amp;' . $id_var_name . '=' . $id_var_value . '&amp;b_id=' . $b_id),
						'U_MOVE_UP' => append_sid('cms.' . $phpEx . '?mode=' . $mode . $redirect_action . '&amp;' . $id_var_name . '=' . $id_var_value . '&amp;move=1&amp;b_id=' . $b_id . '&amp;weight=' . $b_weight . '&amp;pos=' . $b_position),
						'U_MOVE_DOWN' => append_sid('cms.' . $phpEx . '?mode=' . $mode . $redirect_action . '&amp;' . $id_var_name . '=' . $id_var_value . '&amp;move=0&amp;b_id=' . $b_id . '&amp;weight=' . $b_weight . '&amp;pos=' . $b_position)
						)
					);
				}
			}
		}
		else
		{
			$sql = "SELECT bposition, pkey FROM " . CMS_BLOCK_POSITION_TABLE . " WHERE layout = " . $id_var_value . "";
			if(!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not query blocks position table', $lang['Error'], __LINE__, __FILE__, $sql);
			}
			$l_rows = $db->sql_fetchrowset($result);
			$db->sql_freeresult($result);
			$l_count = count($l_rows);

			$sql = "SELECT template FROM " . CMS_LAYOUT_TABLE . " WHERE lid = " . $l_id_list . "";
			if(!($layout_result = $db->sql_query($sql, false, 'cms_')))
			{
				message_die(CRITICAL_ERROR, "Could not query portal layout information", "", __LINE__, __FILE__, $sql);
			}
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
						$b_id = $b_rows[$i]['bid'];

						$redirect_action = '&amp;action=list';
						$output_block = make_cms_block($id_var_value, $b_id, $i, $b_count, $b_position_l, false, $cms_type);

						$template->assign_block_vars($l_rows[$j]['pkey'] . '_blocks_row', array(
							'CMS_BLOCK' => $output_block,
							'OUTPUT' => $output_block,
							)
						);
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

			$sql = "SELECT bid, bposition FROM " . CMS_BLOCKS_TABLE . " WHERE layout = " . $l_id_list . " AND bposition NOT IN (" . $b_position_array . ") ORDER BY weight";
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
	}
	else
	{
		message_die(GENERAL_MESSAGE, $lang['No_layout_selected']);
	}
}

if (($mode == 'layouts') || ($mode == 'layouts_adv'))
{
	/*
	$var_name = 'l_id';
	$table_name = CMS_LAYOUT_TABLE;
	$field_name = 'lid';
	$block_layout_field = 'layout';
	if($ls_id != false)
	{
		$l_id = $ls_id;
		$var_name = 'ls_id';
		$table_name = CMS_LAYOUT_SPECIAL_TABLE;
		$field_name = 'lsid';
		$block_layout_field = 'layout_special';
	}
	*/

	$s_hidden_fields = '';
	$s_append_url = '';
	$s_hidden_fields .= '<input type="hidden" name="mode" value="' . $mode . '" />';
	$s_append_url .= '?mode=' . $mode;
	$s_hidden_fields .= '<input type="hidden" name="action" value="' . $action . '" />';
	$s_append_url .= '&amp;action=' . $action;

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

	if(($action == 'edit') || ($action == 'add'))
	{
		if($ls_id != false)
		{
			message_die(GENERAL_ERROR, $lang['Not_Authorised']);
		}

		$template->set_filenames(array('body' => CMS_TPL . 'cms_layout_edit_body.tpl'));
		$template->assign_var('CMS_PAGE_TITLE', $lang['CMS_Pages']);

		if(($action == 'edit') && $l_id)
		{
			$l_info = get_layout_info(CMS_LAYOUT_TABLE, $l_id);
			$s_hidden_fields .= '<input type="hidden" name="filename_old" value="' . $l_info['filename'] . '" />';
		}

		$template_name = get_template_name($board_config['default_style']);
		$template_dir = $phpbb_root_path . '/templates/' . $template_name . '/layout';

		if ($mode == 'layouts')
		{
			$template->assign_var('S_LAYOUT_ADV', false);
			$layout_details = get_layouts_details_select($template_dir, '.tpl');
			$template->assign_vars(array(
				'TEMPLATE' => $layout_details,
				)
			);
		}
		else
		{
			$template->assign_var('S_LAYOUT_ADV', true);
			$layout_details = get_layouts_details($template_dir, '.tpl', $common_cms_template, 'template', $cms_type);
			for ($i = 0; $i < count($layout_details); $i++)
			{
				$template->assign_block_vars('layouts', array(
					'LAYOUT_IMG' => $layout_details[$i]['img'],
					'LAYOUT_RADIO' => $layout_details[$i]['file']
					)
				);
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
		$write_test = @copy('index_empty.' . $phpEx, 'testing_write_access_permissions.test');
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
			'TEMPLATE' => $layout_details,
			'VIEW' => $view,
			'EDIT_AUTH' => $edit_auth,
			'GROUPS' => $group,
			'GLOBAL_BLOCKS' => ($l_info['global_blocks']) ? 'checked="checked"' : '',
			'NOT_GLOBAL_BLOCKS' => (!$l_info['global_blocks']) ? 'checked="checked"' : '',
			'PAGE_NAV' => ($l_info['page_nav']) ? 'checked="checked"' : '',
			'NOT_PAGE_NAV' => (!$l_info['page_nav']) ? 'checked="checked"' : '',
			'L_EDIT_LAYOUT' => $lang['Layout_Edit'],
			'L_SUBMIT' => $lang['Submit'],
			'S_LAYOUT_ACTION' => append_sid('cms.' . $phpEx),
			'S_HIDDEN_FIELDS' => $s_hidden_fields
			)
		);
	}
	elseif($action == 'save')
	{
		if($ls_id != false)
		{
			message_die(GENERAL_ERROR, $lang['Not_Authorised']);
		}

		$l_name = (isset($_POST['name'])) ? trim($_POST['name']) : '';
		$l_filename = (isset($_POST['filename'])) ? htmlspecialchars(trim($_POST['filename'])) : '';
		$l_filename_old = (isset($_POST['filename_old'])) ? htmlspecialchars(trim($_POST['filename_old'])) : '';
		$l_template = (isset($_POST['template'])) ? trim($_POST['template']) : '';
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

		if(($l_name == '') || ($l_template == ''))
		{
			message_die(GENERAL_MESSAGE, $lang['Must_enter_layout']);
		}

		if($l_id != 0)
		{
			if ($l_filename_old != $l_filename)
			{
				@unlink($l_filename_old);

				if (substr($l_filename, strlen($l_filename) - (strlen($phpEx) + 1), (strlen($phpEx) + 1)) == ('.' . $phpEx))
				{
					$l_filename = ereg_replace("[^a-zA-Z0-9_]", "", substr(strtolower($l_filename), 0, strlen($l_filename) - (strlen($phpEx) + 1))) . ('.' . $phpEx);
					if (file_exists($l_filename))
					{
						message_die(GENERAL_MESSAGE, $lang['CMS_FileAlreadyExists']);
					}
					else
					{
						$creation_success = @copy('index_empty.' . $phpEx, $l_filename);
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

			$sql = "UPDATE " . CMS_LAYOUT_TABLE . "
				SET name = '" . str_replace("\'", "''", $l_name) . "',
				filename = '" . str_replace("\'", "''", $l_filename) . "',
				template = '" . str_replace("\'", "''", $l_template) . "',
				global_blocks = '" . $l_global_blocks . "',
				page_nav = '" . $l_page_nav . "',
				view = '" . $l_view . "',
				edit_auth = '" . $l_edit_auth . "',
				groups = '" . $l_group . "'
				WHERE lid = '" . $l_id . "'";
			if(!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not insert data into layout table', $lang['Error'], __LINE__, __FILE__, $sql);
			}
			$message .= $lang['Layout_updated'];

			$template_name = get_template_name($board_config['default_style']);

			if(file_exists($phpbb_root_path . '/templates/' . $template_name . '/layout/' . ereg_replace('.tpl', '.cfg', $l_template)))
			{
				include($phpbb_root_path . '/templates/' . $template_name . '/layout/' . ereg_replace('.tpl', '.cfg', $l_template));

				$sql_test = "SELECT * FROM " . CMS_BLOCK_POSITION_TABLE . " WHERE layout = '" . $l_id . "'";
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
						$sql = "DELETE FROM " . CMS_BLOCK_POSITION_TABLE . "
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

					$sql_test = "SELECT * FROM " . CMS_BLOCK_POSITION_TABLE . "
						WHERE layout = '" . $l_id . "'
							AND bposition = '" . $layout_block_positions[$i][1] . "'
						LIMIT 1";
					if(!$result_test = $db->sql_query($sql_test))
					{
						message_die(GENERAL_ERROR, 'Could not insert data into block position table', $lang['Error'], __LINE__, __FILE__, $sql);
					}

					if (!($db->sql_fetchrow($result_test)))
					{
						$sql = "INSERT INTO " . CMS_BLOCK_POSITION_TABLE . " (pkey, bposition, layout)
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
			if (substr($l_filename, strlen($l_filename) - (strlen($phpEx) + 1), (strlen($phpEx) + 1)) == ('.' . $phpEx))
			{
				$l_filename = ereg_replace("[^a-zA-Z0-9_]", "", substr(strtolower($l_filename), 0, strlen($l_filename) - (strlen($phpEx) + 1))) . ('.' . $phpEx);
				if (file_exists($l_filename))
				{
					message_die(GENERAL_MESSAGE, $lang['CMS_FileAlreadyExists']);
				}
				else
				{
					$creation_success = @copy('index_empty.' . $phpEx, $l_filename);
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

			$sql = "INSERT INTO " . CMS_LAYOUT_TABLE . " (name, filename, template, global_blocks, page_nav, view, edit_auth, groups)
				VALUES ('" . str_replace("\'", "''", $l_name) . "', '" . str_replace("\'", "''", $l_filename) . "', '" . str_replace("\'", "''", $l_template) . "', '" . $l_global_blocks . "', '" . $l_page_nav . "', '" . $l_view . "', '" . $l_edit_auth . "', '" . $l_group . "')";
			if(!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not insert data into layout table', $lang['Error'], __LINE__, __FILE__, $sql);
			}
			$message .= $lang['Layout_added'];

			$template_name = get_template_name($board_config['default_style']);

			if(file_exists($phpbb_root_path . '/templates/' . $template_name . '/layout/' . ereg_replace('.tpl', '.cfg', $l_template)))
			{
				include($phpbb_root_path . '/templates/' . $template_name . '/layout/' . ereg_replace('.tpl', '.cfg', $l_template));

				$layout_id = get_max_layout_id(CMS_LAYOUT_TABLE);

				for($i = 0; $i < $layout_count_positions; $i++)
				{
					$sql = "INSERT INTO " . CMS_BLOCK_POSITION_TABLE . " (pkey, bposition, layout)
						VALUES ('" . str_replace("\'", "''", $layout_block_positions[$i][0]) . "', '" . str_replace("\'", "''", $layout_block_positions[$i][1]) . "', '" . $layout_id . "')";
					if(!$result = $db->sql_query($sql))
					{
						message_die(GENERAL_ERROR, 'Could not insert data into block position table', $lang['Error'], __LINE__, __FILE__, $sql);
					}
				}

				$message .= '<br /><br />' . $lang['Layout_BP_added'];
				$message .= '<br /><br />' . sprintf($lang['Click_return_blocksadmin'], '<a href="' . append_sid('cms.' . $phpEx . '?mode=blocks&amp;l_id=' . $layout_id) . '">', '</a>');

			}
		}

		$db->clear_cache('cms_');
		$message .= '<br /><br />' . sprintf($lang['Click_return_layoutadmin'], '<a href="' . append_sid('cms.' . $phpEx) . '">', '</a>') . '<br /><br />';
		message_die(GENERAL_MESSAGE, $message);
	}
	elseif($action == 'delete')
	{
		if($ls_id != false)
		{
			message_die(GENERAL_ERROR, $lang['Not_Authorised']);
		}

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

				'S_CONFIRM_ACTION' => append_sid('cms.' . $phpEx),
				'S_HIDDEN_FIELDS' => $s_hidden_fields
				)
			);
			$template->pparse('confirm');
			include($phpbb_root_path . 'includes/page_tail.' . $phpEx);
			exit();
		}
		else
		{
			if($l_id != 0)
			{
				if ((substr($l_filename, strlen($l_filename) - (strlen($phpEx) + 1), (strlen($phpEx) + 1)) == ('.' . $phpEx)) && (file_exists($l_filename)))
				{
					@unlink($l_filename);
				}

				delete_layout(CMS_LAYOUT_TABLE, CMS_BLOCK_POSITION_TABLE, $l_id);

				$sql_list = "SELECT * FROM " . CMS_BLOCKS_TABLE . " WHERE layout = '" . $l_id. "'";

				if(!($result_list = $db->sql_query($sql_list)))
				{
					message_die(GENERAL_ERROR, 'Could not query blocks list', $lang['Error'], __LINE__, __FILE__, $sql);
				}

				while($b_row = $db->sql_fetchrow($result_list))
				{
					delete_block(CMS_BLOCKS_TABLE, $b_row['bid']);
				}
				$db->sql_freeresult($result_list);

				$message = $lang['Layout_removed'] . '<br /><br />' . sprintf($lang['Click_return_layoutadmin'], '<a href="' . append_sid('cms.' . $phpEx) . '">', '</a>') . '<br /><br />';

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
		$template->set_filenames(array('body' => CMS_TPL . 'cms_layout_list_body.tpl'));
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
			'S_LAYOUT_ACTION' => append_sid('cms.' . $phpEx),
			'S_HIDDEN_FIELDS' => $s_hidden_fields
			)
		);

		$template->assign_block_vars('layout', array());

		$sql = "SELECT * FROM " . CMS_LAYOUT_TABLE . " ORDER BY lid";
		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not query layout table', $lang['Error'], __LINE__, __FILE__, $sql);
		}

		$l_rows = $db->sql_fetchrowset($result);
		$db->sql_freeresult($result);
		$l_count = count($l_rows);

		$sql = "SELECT config_value FROM " . CMS_CONFIG_TABLE . " WHERE bid = '0' AND config_name = 'default_portal'";
		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not query layout table', $lang['Error'], __LINE__, __FILE__, $sql);
		}

		$c_row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);
		$default_portal_id = $c_row['config_value'];

		for($i = 0; $i < $l_count; $i++)
		{
			$row_color = (!($i % 2)) ? $theme['td_color2'] : $theme['td_color1'];
			$row_class = (!($i % 2)) ? $theme['td_class2'] : $theme['td_class1'];

			$template->assign_block_vars('layout.l_row', array(
				'ROW_COLOR' => '#' . $row_color,
				'ROW_CLASS' => $row_class,
				'ROW_DEFAULT_STYLE' => ($l_rows[$i]['lid'] == $default_portal_id) ? 'font-weight:bold;' : '',
				'LAYOUT_ID' => $l_rows[$i]['lid'],
				'LAYOUT_NAME' => $l_rows[$i]['name'],
				'LAYOUT_FILENAME' => ($l_rows[$i]['filename'] == '') ? $lang['None'] : $l_rows[$i]['filename'],
				'LAYOUT_BLOCKS' => count_blocks_in_layout(CMS_BLOCKS_TABLE, '\'' . $l_rows[$i]['lid'] . '\'', true) . '/' . count_blocks_in_layout(CMS_BLOCKS_TABLE, '\'' . $l_rows[$i]['lid'] . '\'', false),
				'LAYOUT_TEMPLATE' => $l_rows[$i]['template'],
				'U_PREVIEW_LAYOUT' => append_sid(($l_rows[$i]['filename'] == '') ? PORTAL_MG . '?page=' . $l_rows[$i]['lid'] : $l_rows[$i]['filename']),
				'U_EDIT_LAYOUT' => append_sid('cms.' . $phpEx . '?mode=' . $mode . '&amp;l_id=' . $l_rows[$i]['lid'] . '&amp;action=edit'),
				'U_DELETE_LAYOUT' => append_sid('cms.' . $phpEx . '?mode=' . $mode . '&amp;l_id=' . $l_rows[$i]['lid'] . '&amp;action=delete'),
				'U_LAYOUT' => append_sid('cms.' . $phpEx . '?mode=' . (($mode == 'layouts') ? 'blocks' : 'blocks_adv') . '&amp;l_id=' . $l_rows[$i]['lid'])
				)
			);
		}
	}
}

if ($mode == 'layouts_special')
{
	$mode = 'layouts';
	$s_hidden_fields = '';
	$s_append_url = '';
	$s_hidden_fields .= '<input type="hidden" name="mode" value="' . $mode . '" />';
	$s_append_url .= '?mode=' . $mode;
	$s_hidden_fields .= '<input type="hidden" name="action" value="' . $action . '" />';
	$s_append_url .= '&amp;action=' . $action;
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

	if (($action == 'list') || ($action == false))
	{
		$template->set_filenames(array('body' => CMS_TPL . 'cms_layout_list_body.tpl'));
		$template->assign_var('CMS_PAGE_TITLE', $lang['CMS_Pages']);

		$template->assign_vars(array(
			'L_CMS_PAGES' => $lang['CMS_Pages'],
			'L_CMS_ID' => $lang['CMS_ID'],
			'L_CMS_ACTIONS' => $lang['CMS_Actions'],
			'L_CMS_LAYOUT' => $lang['CMS_Layout'],
			'L_CMS_BLOCKS' => $lang['CMS_Blocks'],
			'L_CMS_NAME' => $lang['CMS_Name'],
			'L_CMS_FILENAME' => $lang['CMS_Filename'],
			'L_CMS_FILENAME_EXPLAIN' => $lang['CMS_Filename_Explain'],
			'L_CMS_TEMPLATE' => $lang['CMS_Template'],
			'L_LAYOUT_TITLE' => $lang['Standard_Pages'],
			'L_LAYOUT_TEXT' => $lang['Layout_Special_Explain'],
			'L_STANDARD_PAGES' => $lang['Standard_Pages'],
			'L_CUSTOM_PAGES' => $lang['Custom_Pages'],
			'L_CHOOSE_LAYOUT' => $lang['Choose_Layout'],
			'L_CONFIGURE_BLOCKS' => $lang['CMS_Configure_Blocks'],
			'L_EDIT' => $lang['CMS_Edit'],
			'L_DELETE' => $lang['CSM_Delete'],
			'L_PREVIEW' => $lang['CMS_Preview'],
			'L_LAYOUT_ADD' => $lang['Layout_Add'],
			'S_LAYOUT_ACTION' => append_sid('cms.' . $phpEx),
			'S_HIDDEN_FIELDS' => $s_hidden_fields
			)
		);

		$template->assign_block_vars('layout_special', array());

		$sql = "SELECT * FROM " . CMS_LAYOUT_SPECIAL_TABLE . " ORDER BY lsid";
		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not query layout table', $lang['Error'], __LINE__, __FILE__, $sql);
		}

		$l_rows = $db->sql_fetchrowset($result);
		$db->sql_freeresult($result);
		$l_count = count($l_rows);

		for($i = 0; $i < $l_count; $i++)
		{
			$row_color = (!($i % 2)) ? $theme['td_color2'] : $theme['td_color1'];
			$row_class = (!($i % 2)) ? $theme['td_class2'] : $theme['td_class1'];
			$lang_var = 'auth_view_' . $l_rows[$i]['name'];

			$template->assign_block_vars('layout_special.ls_row', array(
				'ROW_COLOR' => '#' . $row_color,
				'ROW_CLASS' => $row_class,
				'LAYOUT_ID' => $l_rows[$i]['lsid'],
				'LAYOUT_NAME' => $lang[$lang_var],
				'LAYOUT_FILENAME' => ($l_rows[$i]['filename'] != '') ? append_sid($l_rows[$i]['filename'] . '.' . $phpEx) : $lang['None'],
				'LAYOUT_TEMPLATE' => '',
				'LAYOUT_BLOCKS' => count_blocks_in_layout(CMS_BLOCKS_TABLE, 'layout_special', '\'' . $l_rows[$i]['lsid'] . '\'', true) . '/' . count_blocks_in_layout(CMS_BLOCKS_TABLE, 'layout_special', '\'' . $l_rows[$i]['lsid'] . '\'', false),
				'U_PREVIEW_LAYOUT' => append_sid(($l_rows[$i]['filename'] != '') ? ($l_rows[$i]['filename'] . '.' . $phpEx) : '#'),
				'U_EDIT_LAYOUT' => '#',
				'U_DELETE_LAYOUT' => '#',
				'U_LAYOUT' => append_sid('cms.' . $phpEx . '?mode=blocks&amp;ls_id=' . $l_rows[$i]['lsid'])
				)
			);
		}
	}
}

if($mode == 'config')
{
	$template->set_filenames(array('body' => CMS_TPL . 'cms_config_body.tpl'));
	$template->assign_var('CMS_PAGE_TITLE', $lang['CMS_Config']);

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
	if(!$result = $db->sql_query($sql))
	{
		message_die(CRITICAL_ERROR, 'Could not query CMS config information', $lang['Error'], __LINE__, __FILE__, $sql);
	}
	else
	{
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
					config_value = '" . str_replace("\'", "''", $new[$cms_field[$row['config_name']]['name']]) . "'
					WHERE config_name = '" . $cms_field[$row['config_name']]['name'] . "'";
				if(!$db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, "Failed to update configuration for " . $cms_field[$row['config_name']]['name'], "", __LINE__, __FILE__, $sql);
				}
			}
			else
			{
				$is_block = ($cms_field[$row['config_name']]['block'] != '@Portal Config') ? 'block ' : '';
				$template->assign_block_vars('cms_block', array(
					'L_FIELD_LABEL' => $cms_field[$row['config_name']]['label'],
					'L_FIELD_SUBLABEL' => '<br /><br /><span class="gensmall">' . $cms_field[$row['config_name']]['sub_label'] . ' [ ' . ereg_replace("@", "", $cms_field[$row['config_name']]['block']) . ' ' . $is_block . ']</span>',
					'FIELD' => $cms_field[$row['config_name']]['output']
					)
				);
			}
		}
		$db->sql_freeresult($result);

		if(isset($_POST['save']))
		{
			$db->clear_cache('cms_');
			$message = $lang['CMS_Config_updated'] . '<br /><br />' . sprintf($lang['CMS_Click_return_config'], '<a href="' . append_sid('cms.' . $phpEx . '?mode=config') . '">', '</a>') . '<br /><br />' . sprintf($lang['CMS_Click_return_cms'], '<a href="' . append_sid('cms.' . $phpEx) . '">', '</a>') . '<br /><br />';

			message_die(GENERAL_MESSAGE, $message);
		}
	}

	$template->assign_vars(array(
		'S_CONFIG_ACTION' => append_sid('cms.' . $phpEx),
		'L_CONFIGURATION_TITLE' => $lang['CMS_Config'],
		'L_CONFIGURATION_EXPLAIN' => $lang['Portal_Explain'],
		'L_GENERAL_CONFIG' => $lang['Portal_General_Config'],
		'L_SUBMIT' => $lang['Submit'],
		'L_RESET' => $lang['Reset']
		)
	);
}

if (($mode == false))
{
	$template->set_filenames(array('body' => CMS_TPL . 'cms_index_body.tpl'));
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
		'S_LAYOUT_ACTION' => append_sid('cms.' . $phpEx),
		'S_HIDDEN_FIELDS' => $s_hidden_fields
		)
	);
}

$db->clear_cache('cms_');
$template->pparse('body');

include($phpbb_root_path . 'includes/page_tail.' . $phpEx);

?>