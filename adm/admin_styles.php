<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
*
* @Icy Phoenix is based on phpBB
* @copyright (c) 2008 phpBB Group
*
*/

if (!defined('IN_ICYPHOENIX')) define('IN_ICYPHOENIX', true);

if(!empty($setmodules))
{
	$file = basename(__FILE__);
	return;
}

if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
// Check if the user has canceled a confirmation message.
$confirm = isset($_POST['confirm']) ? true : false;
$cancel = isset($_POST['cancel']) ? true : false;
$no_page_header = (!empty($_POST['send_file']) || $cancel) ? true : false;
require('pagestart.' . PHP_EXT);
if ($cancel)
{
	redirect(ADM . '/'  . append_sid('admin_styles.' . PHP_EXT, true));
}

$mode = request_var('mode', '');

switch($mode)
{
	case 'addnew':
		$install_to = request_var('install_to', '');
		$style_name = request_var('style', '');

		if(isset($install_to))
		{

			include(IP_ROOT_PATH . 'templates/' . basename($install_to) . '/theme_info.cfg');

			$template_name = ${$install_to};
			$found = false;

			for($i = 0; $i < sizeof($template_name) && !$found; $i++)
			{
				if($template_name[$i]['style_name'] == $style_name)
				{
					//while(list($key, $val) = each($template_name[$i]))
					foreach ($template_name[$i] as $key => $val)
					{
						$db_fields[] = $key;
						$db_values[] = str_replace("\'", "''" , $val);
					}
				}
			}

			$sql = "INSERT INTO " . THEMES_TABLE . " (";

			for($i = 0; $i < sizeof($db_fields); $i++)
			{
				$sql .= $db_fields[$i];
				if($i != (sizeof($db_fields) - 1))
				{
					$sql .= ", ";
				}

			}

			$sql .= ") VALUES (";

			for($i = 0; $i < sizeof($db_values); $i++)
			{
				$sql .= "'" . $db_values[$i] . "'";
				if($i != (sizeof($db_values) - 1))
				{
					$sql .= ", ";
				}
			}
			$sql .= ")";
			$result = $db->sql_query($sql);

			$db->clear_cache('styles_');
			$message = $lang['Theme_installed'] . '<br /><br />' . sprintf($lang['Click_return_styleadmin'], '<a href="' . append_sid('admin_styles.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

			message_die(GENERAL_MESSAGE, $message);
		}
		else
		{

			$installable_themes = array();

			if($dir = @opendir(IP_ROOT_PATH . 'templates/'))
			{
				while($sub_dir = @readdir($dir))
				{
					if(!@is_file(@phpbb_realpath(IP_ROOT_PATH . 'templates/' . $sub_dir)) && !@is_link(@phpbb_realpath(IP_ROOT_PATH . 'templates/' .$sub_dir)) && ($sub_dir != '.') && ($sub_dir != '..') && ($sub_dir != 'common') && ($sub_dir != 'default'))
					{
						if(@file_exists(@phpbb_realpath(IP_ROOT_PATH . 'templates/' . $sub_dir . '/theme_info.cfg')))
						{
							include(IP_ROOT_PATH . 'templates/' . $sub_dir . '/theme_info.cfg');

							for($i = 0; $i < sizeof(${$sub_dir}); $i++)
							{
								$working_data = ${$sub_dir};

								$style_name = $working_data[$i]['style_name'];

								$sql = "SELECT themes_id
									FROM " . THEMES_TABLE . "
									WHERE style_name = '" . $db->sql_escape($style_name) . "'";
								$result = $db->sql_query($sql);

								if(!$db->sql_numrows($result))
								{
									$installable_themes[] = $working_data[$i];
								}
							}
						}
					}
				}

				$template->set_filenames(array('body' => ADM_TPL . 'styles_addnew_body.tpl'));

				$template->assign_vars(array(
					'L_STYLES_TITLE' => $lang['Styles_admin'],
					'L_STYLES_ADD_TEXT' => $lang['Styles_addnew_explain'],
					'L_STYLE' => $lang['Style'],
					'L_TEMPLATE' => $lang['Template'],
					'L_INSTALL' => $lang['Install'],
					'L_ACTION' => $lang['Action'])
				);

				for($i = 0; $i < sizeof($installable_themes); $i++)
				{
					$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];

					$template->assign_block_vars('styles', array(
						'ROW_CLASS' => $row_class,
						'STYLE_NAME' => $installable_themes[$i]['style_name'],
						'TEMPLATE_NAME' => $installable_themes[$i]['template_name'],

						'U_STYLES_INSTALL' => append_sid('admin_styles.' . PHP_EXT . '?mode=addnew&amp;style=' . urlencode($installable_themes[$i]['style_name']) . '&amp;install_to=' . urlencode($installable_themes[$i]['template_name']))
						)
					);

				}
				$template->pparse('body');

			}
			@closedir($dir);
		}
		break;

	case 'create':
	case 'edit':
		$submit = (isset($_POST['submit'])) ? true : 0;

		if($submit)
		{
			//
			// DAMN! That's a lot of data to validate...
			//
			$updated['style_name'] = $_POST['style_name'];
			$updated['template_name'] = $_POST['template_name'];
			$updated['head_stylesheet'] = $_POST['head_stylesheet'];
			$updated['body_background'] = $_POST['body_background'];
			$updated['body_bgcolor'] = $_POST['body_bgcolor'];
			$updated['tr_class1'] = $_POST['tr_class1'];
			$updated_name['tr_class1_name'] = $_POST['tr_class1_name'];
			$updated['tr_class2'] = $_POST['tr_class2'];
			$updated_name['tr_class2_name'] = $_POST['tr_class2_name'];
			$updated['tr_class3'] = $_POST['tr_class3'];
			$updated_name['tr_class3_name'] = $_POST['tr_class3_name'];
			$updated['td_class1'] = $_POST['td_class1'];
			$updated_name['td_class1_name'] = $_POST['td_class1_name'];
			$updated['td_class2'] = $_POST['td_class2'];
			$updated_name['td_class2_name'] = $_POST['td_class2_name'];
			$updated['td_class3'] = $_POST['td_class3'];
			$updated_name['td_class3_name'] = $_POST['td_class3_name'];
			$style_id = intval($_POST['style_id']);
			//
			// Wheeeew! Thank heavens for copy and paste and search and replace :D
			//

			if($mode == 'edit')
			{
				$sql = "UPDATE " . THEMES_TABLE . " SET ";
				$count = 0;

				//while(list($key, $val) = each($updated))
				foreach ($updated as $key => $val)
				{
					if($count != 0)
					{
						$sql .= ", ";
					}

					$count++;
				}

				$sql .= " WHERE themes_id = " . $style_id;
				$result = $db->sql_query($sql);

				$db->clear_cache('styles_');
				$message = $lang['Theme_updated'] . '<br /><br />' . sprintf($lang['Click_return_styleadmin'], '<a href="' . append_sid('admin_styles.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

				message_die(GENERAL_MESSAGE, $message);
			}
			else
			{
				// First, check if we already have a style by this name
				$sql = "SELECT themes_id
					FROM " . THEMES_TABLE . "
					WHERE style_name = '" . $db->sql_escape($updated['style_name']) . "'";
				$result = $db->sql_query($sql);

				if($db->sql_numrows($result))
				{
					message_die(GENERAL_ERROR, $lang['Style_exists'], $lang['Error']);
				}

				//while(list($key, $val) = each($updated))
				foreach ($updated as $key => $val)
				{
					$field_names[] = $key;

					if(stristr($key, "fontsize"))
					{
						$values[] = "$val";
					}
					else
					{
						$values[] = "'" . $db->sql_escape($val) . "'";
					}
				}

				$sql = "INSERT
					INTO " . THEMES_TABLE . " (";
				for($i = 0; $i < sizeof($field_names); $i++)
				{
					if($i != 0)
					{
						$sql .= ", ";
					}
					$sql .= $field_names[$i];
				}

				$sql .= ") VALUES (";
				for($i = 0; $i < sizeof($values); $i++)
				{
					if($i != 0)
					{
						$sql .= ", ";
					}
					$sql .= $values[$i];
				}
				$sql .= ")";

				$result = $db->sql_query($sql);
				$style_id = $db->sql_nextid();

				$db->clear_cache('styles_');
				$message = $lang['Theme_created'] . '<br /><br />' . sprintf($lang['Click_return_styleadmin'], '<a href="' . append_sid('admin_styles.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

				message_die(GENERAL_MESSAGE, $message);
			}
		}
		else
		{
			if($mode == 'edit')
			{
				$themes_title = $lang['Edit_theme'];
				$themes_explain = $lang['Edit_theme_explain'];

				$style_id = request_get_var('style_id', 0);

				$selected_names = array();
				$selected_values = array();

				// Fetch the Theme Info from the db
				$sql = "SELECT *
					FROM " . THEMES_TABLE . "
					WHERE themes_id = " . $style_id;
				$result = $db->sql_query($sql);

				if ($selected_values = $db->sql_fetchrow($result))
				{
					//while(list($key, $val) = @each($selected_values))
					foreach ($selected_values as $key => $val)
					{
						$selected[$key] = $val;
					}
				}

				$s_hidden_fields = '<input type="hidden" name="style_id" value="' . $style_id . '" />';
			}
			else
			{
				$themes_title = $lang['Create_theme'];
				$themes_explain = $lang['Create_theme_explain'];
			}

			$template->set_filenames(array('body' => ADM_TPL . 'styles_edit_body.tpl'));

			if($dir = @opendir(IP_ROOT_PATH . 'templates/'))
			{
				$s_template_select = '<select name="template_name">';
				while($file = @readdir($dir))
				{
					if(!@is_file(@phpbb_realpath(IP_ROOT_PATH . 'templates/' . $file)) && !@is_link(@phpbb_realpath(IP_ROOT_PATH . 'templates/' . $file)) && ($file != '.') && ($file != '..') && ($file != 'common') && ($file != 'default'))
					{
						if($file == $selected['template_name'])
						{
							$s_template_select .= '<option value="' . $file . '" selected="selected">' . $file . "</option>\n";
						}
						else
						{
							$s_template_select .= '<option value="' . $file . '">' . $file . "</option>\n";
						}
					}
				}
				$s_template_select .= '</select>';
			}
			else
			{
				message_die(GENERAL_MESSAGE, $lang['No_template_dir']);
			}
			@closedir($dir);

			$s_hidden_fields .= '<input type="hidden" name="mode" value="' . $mode . '" />';

			$template->assign_vars(array(
				'L_THEMES_TITLE' => $themes_title,
				'L_THEMES_EXPLAIN' => $themes_explain,
				'L_THEME_NAME' => $lang['Theme_name'],
				'L_TEMPLATE' => $lang['Template'],
				'L_THEME_SETTINGS' => $lang['Theme_settings'],
				'L_THEME_ELEMENT' => $lang['Theme_element'],
				'L_SIMPLE_NAME' => $lang['Simple_name'],
				'L_VALUE' => $lang['Value'],
				'L_STYLESHEET_EXPLAIN' => $lang['Stylesheet_explain'],
				'L_BACKGROUND_IMAGE' => $lang['Background_image'],
				'L_BACKGROUND_COLOR' => $lang['Background_color'],
				'L_TR_CLASS1' => $lang['Tr_class1'],
				'L_TR_CLASS2' => $lang['Tr_class2'],
				'L_TR_CLASS3' => $lang['Tr_class3'],
				'L_TD_CLASS1' => $lang['Td_class1'],
				'L_TD_CLASS2' => $lang['Td_class2'],
				'L_TD_CLASS3' => $lang['Td_class3'],
				'L_SAVE_SETTINGS' => $lang['Save_Settings'],
				'THEME_NAME' => $selected['style_name'],
				'HEAD_STYLESHEET' => $selected['head_stylesheet'],
				'BODY_BACKGROUND' => $selected['body_background'],
				'BODY_BGCOLOR' => $selected['body_bgcolor'],
				'TR_CLASS1' => $selected['tr_class1'],
				'TR_CLASS2' => $selected['tr_class2'],
				'TR_CLASS3' => $selected['tr_class3'],
				'TD_CLASS1' => $selected['td_class1'],
				'TD_CLASS2' => $selected['td_class2'],
				'TD_CLASS3' => $selected['td_class3'],

				'S_THEME_ACTION' => append_sid('admin_styles.' . PHP_EXT),
				'S_TEMPLATE_SELECT' => $s_template_select,
				'S_HIDDEN_FIELDS' => $s_hidden_fields
				)
			);

			$template->pparse('body');
		}
		break;

	case 'export';
		$template_name = request_post_var('export_template', '', true);
		if(!empty($template_name))
		{

			$sql = "SELECT *
				FROM " . THEMES_TABLE . "
				WHERE template_name = '" . $db->sql_escape($template_name) . "'";
			$result = $db->sql_query($sql);
			$theme_rowset = $db->sql_fetchrowset($result);

			if(sizeof($theme_rowset) == 0)
			{
				message_die(GENERAL_MESSAGE, $lang['No_themes']);
			}

			$theme_data = '<' . '?php' . "\n\n";
			$theme_data .= "//\n// phpBB 2.x auto-generated theme config file for $template_name\n// Do not change anything in this file!\n//\n\n";

			for($i = 0; $i < sizeof($theme_rowset); $i++)
			{
				//while(list($key, $val) = each($theme_rowset[$i]))
				foreach ($theme_rowset[$i] as $key => $val)
				{
					if(!intval($key) && ($key != "0") && ($key != 'themes_id'))
					{
						$theme_data .= '$' . $template_name . "[$i]['$key'] = \"" . addslashes($val) . "\";\n";
					}
				}
				$theme_data .= "\n";
			}

			$theme_data .= '?' . '>'; // Done this to prevent highlighting editors getting confused!

			@umask(0111);

			$fp = @fopen(IP_ROOT_PATH . 'templates/' . basename($template_name) . '/theme_info.cfg', 'w');

			if(!$fp)
			{
				// Unable to open the file writeable do something here as an attempt to get around that...
				$s_hidden_fields = '<input type="hidden" name="theme_info" value="' . htmlspecialchars($theme_data) . '" />';
				$s_hidden_fields .= '<input type="hidden" name="send_file" value="1" /><input type="hidden" name="mode" value="export" />';

				$download_form = '<form action="' . append_sid('admin_styles.' . PHP_EXT) . '" method="post"><input class="mainoption" type="submit" name="submit" value="' . $lang['Download'] . '" />' . $s_hidden_fields;

				$template->set_filenames(array('body' => ADM_TPL . 'message_body.tpl'));

				$template->assign_vars(array(
					'MESSAGE_TITLE' => $lang['Export_themes'],
					'MESSAGE_TEXT' => $lang['Download_theme_cfg'] . '<br /><br />' . $download_form
					)
				);

				$template->pparse('body');
				exit();
			}

			$result = @fwrite($fp, $theme_data, strlen($theme_data));
			fclose($fp);

			$db->clear_cache('styles_');
			$message = $lang['Theme_info_saved'] . '<br /><br />' . sprintf($lang['Click_return_styleadmin'], '<a href="' . append_sid('admin_styles.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

			message_die(GENERAL_MESSAGE, $message);

		}
		elseif($_POST['send_file'])
		{
			$theme_info = request_post_var('theme_info', '', true);
			$theme_info = htmlspecialchars_decode($theme_info, ENT_COMPAT);

			header("Content-Type: text/x-delimtext; name=\"theme_info.cfg\"");
			header("Content-disposition: attachment; filename=theme_info.cfg");

			echo stripslashes($theme_info);
		}
		else
		{
			$template->set_filenames(array('body' => ADM_TPL . 'styles_exporter.tpl'));

			if($dir = @opendir(IP_ROOT_PATH . 'templates/'))
			{
				$s_template_select = '<select name="export_template">';
				while($file = @readdir($dir))
				{
					if(!is_file(@phpbb_realpath(IP_ROOT_PATH . 'templates/' . $file)) && !is_link(phpbb_realpath(IP_ROOT_PATH . 'templates/' . $file)) && ($file != '.') && ($file != '..') && ($file != 'common') && ($file != 'default'))
					{
						$s_template_select .= '<option value="' . $file . '">' . $file . '</option>' . "\n";
					}
				}
				$s_template_select .= '</select>';
			}
			else
			{
				message_die(GENERAL_MESSAGE, $lang['No_template_dir']);
			}
			@closedir($dir);

			$template->assign_vars(array(
				'L_STYLE_EXPORTER' => $lang['Export_themes'],
				'L_EXPORTER_EXPLAIN' => $lang['Export_explain'],
				'L_TEMPLATE_SELECT' => $lang['Select_template'],
				'L_SUBMIT' => $lang['Submit'],

				'S_EXPORTER_ACTION' => append_sid('admin_styles.' . PHP_EXT . '?mode=export'),
				'S_TEMPLATE_SELECT' => $s_template_select
				)
			);

			$template->pparse('body');

		}
		break;

	case 'delete':
		$style_id = request_get_var('style_id', 0);

		if(!$confirm)
		{
			if($style_id == $config['default_style'])
			{
				message_die(GENERAL_MESSAGE, $lang['Cannot_remove_style']);
			}

			$hidden_fields = '<input type="hidden" name="mode" value="'.$mode.'" /><input type="hidden" name="style_id" value="'.$style_id.'" />';

			// Set template files
			$template->set_filenames(array('confirm' => ADM_TPL . 'confirm_body.tpl'));

			$template->assign_vars(array(
				'MESSAGE_TITLE' => $lang['Confirm'],
				'MESSAGE_TEXT' => $lang['Confirm_delete_style'],

				'L_YES' => $lang['Yes'],
				'L_NO' => $lang['No'],

				'S_CONFIRM_ACTION' => append_sid('admin_styles.' . PHP_EXT),
				'S_HIDDEN_FIELDS' => $hidden_fields
				)
			);

			$template->pparse('confirm');

		}
		else
		{
			//
			// The user has confirmed the delete. Remove the style, the style element
			// names and update any users who might be using this style
			//
			$sql = "DELETE FROM " . THEMES_TABLE . " WHERE themes_id = " . $style_id;
			$db->sql_transaction('begin');
			$result = $db->sql_query($sql);

			$sql = "UPDATE " . USERS_TABLE . "
				SET user_style = " . $config['default_style'] . "
				WHERE user_style = $style_id";
			$result = $db->sql_query($sql);
			$db->sql_transaction('commit');

			$db->clear_cache('styles_');
			$message = $lang['Style_removed'] . '<br /><br />' . sprintf($lang['Click_return_styleadmin'], '<a href="' . append_sid('admin_styles.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

			message_die(GENERAL_MESSAGE, $message);
		}
		break;

	default:

		$sql = "SELECT themes_id, template_name, style_name
			FROM " . THEMES_TABLE . "
			ORDER BY template_name";
		$result = $db->sql_query($sql);
		$style_rowset = $db->sql_fetchrowset($result);

		$template->set_filenames(array('body' => ADM_TPL . 'styles_list_body.tpl'));

		$template->assign_vars(array(
			'L_STYLES_TITLE' => $lang['Styles_admin'],
			'L_STYLES_TEXT' => $lang['Styles_explain'],
			'L_STYLE' => $lang['Style'],
			'L_TEMPLATE' => $lang['Template'],
			'L_EDIT' => $lang['Edit'],
			'L_DELETE' => $lang['Delete']
			)
		);

		for($i = 0; $i < sizeof($style_rowset); $i++)
		{
			$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];

			$template->assign_block_vars('styles', array(
				'ROW_CLASS' => $row_class,
				'STYLE_NAME' => $style_rowset[$i]['style_name'],
				'TEMPLATE_NAME' => $style_rowset[$i]['template_name'],

				'U_STYLES_EDIT' => append_sid('admin_styles.' . PHP_EXT . '?mode=edit&amp;style_id=' . $style_rowset[$i]['themes_id']),
				'U_STYLES_DELETE' => append_sid('admin_styles.' . PHP_EXT . '?mode=delete&amp;style_id=' . $style_rowset[$i]['themes_id'])
				)
			);
		}

		$template->pparse('body');
		break;
}

if (empty($_POST['send_file']))
{
	include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);
}

?>