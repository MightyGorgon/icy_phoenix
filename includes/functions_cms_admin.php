<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

/**
* Check CMS permissions required to access current page
*/
function get_cms_access_auth($cms_page, $mode = '', $action = '', $l_id = '', $b_id = '')
{
	global $user;

	// If the user is admin... give immediate access and exit!
	if ($user->data['user_level'] == ADMIN)
	{
		return true;
	}

	$is_auth = false;

	// The user is not admin... let's check if he has some extra power... ;-)
	switch ($cms_page)
	{
		case 'cms':
			if ($mode == 'blocks')
			{
				if ((($action == 'list') || ($action == 'edit') || ($action == 'save') || empty($action)) && !isset($_POST['action_update']))
				{
					$is_auth = ($user->data['user_cms_level'] < CMS_PUBLISHER) ? false : true;
				}
				else
				{
					$is_auth = ($user->data['user_cms_level'] < CMS_CONTENT_MANAGER) ? false : true;
				}
			}
			elseif (($mode == 'layouts') || ($mode == 'layouts_adv') || ($mode == 'layouts_special'))
			{
				$is_auth = ($user->data['user_cms_level'] < CMS_CONTENT_MANAGER) ? false : true;
			}
			else
			{
				$is_auth = ($user->data['user_cms_level'] < CMS_CONTENT_MANAGER) ? false : true;
			}
			break;
		case 'cms_ads':
			$is_auth = ($user->data['user_cms_level'] < CMS_CONTENT_MANAGER) ? false : true;
			break;
		case 'cms_auth':
			$is_auth = ($user->data['user_cms_level'] < CMS_CONTENT_MANAGER) ? false : true;
			break;
		case 'cms_menu':
			$is_auth = ($user->data['user_cms_level'] < CMS_CONTENT_MANAGER) ? false : true;
			break;
		default:
			return false;
	}

	return $is_auth;
}

/*
* Default layout content
*/
function default_layout_content()
{
	global $db;

	$default_layout = array();
	$default_layout['options'] = '';
	$default_layout['values'] = '';
	$sql = "SELECT * FROM " . CMS_LAYOUT_TABLE . " ORDER BY lid";
	$result = $db->sql_query($sql);

	while($row = $db->sql_fetchrow($result))
	{
		$default_layout['options'] .= (($default_layout['options'] == '') ? '' : ',') . $row['name'];
		$default_layout['values'] .= (($default_layout['values'] == '') ? '' : ',') . $row['lid'];
	}
	$db->sql_freeresult($result);

	return $default_layout;
}

/*
* Get block info
*/
function get_block_info($blocks_table, $b_id)
{
	global $db;

	$sql = "SELECT * FROM " . $blocks_table . " WHERE bid = '" . $b_id . "'";
	$result = $db->sql_query($sql);
	$b_info = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);

	return $b_info;
}

/*
* Create CMS block
*/
function make_cms_block($l_id, $b_id, $b_i, $b_count, $b_position_l, $invalid)
{
	global $db, $images, $lang;

	if (empty($b_id))
	{
		return false;
	}

	$source_file = 'cms.';
	$cms_type_id = '1';
	$cms_block_table = CMS_BLOCKS_TABLE;

	$sql = "SELECT * FROM " . $cms_block_table . " WHERE bid = " . $b_id . "";
	$result = $db->sql_query($sql);
	$b_row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);

	switch ($b_row['view'])
	{
		case '0':
			$b_view = '[ ' . $lang['B_ALL'] . ' ]';
			break;
		case '1':
			$b_view = '[ ' . $lang['B_GUESTS'] . ' ]';
			break;
		case '2':
			$b_view = '[ ' . $lang['B_REG'] . ' ]';
			break;
		case '3':
			$b_view = '[ ' . $lang['B_MOD'] . ' ]';
			break;
		case '4':
			$b_view = '[ ' . $lang['B_ADMIN'] . ' ]';
			break;
	}

	if(!empty($b_row['groups']))
	{
		$groups = get_groups_names($b_row['groups']);
	}
	else
	{
		$groups = '[ ' . $lang['B_ALL'] . ' ]';
	}
	$b_content = (empty($b_row['blockfile'])) ? $lang['B_Text'] : $lang['B_File'];
	$b_type = (empty($b_row['blockfile'])) ? (($b_row['type']) ? '(' . $lang['B_BBCode'] . ')' : '(' . $lang['B_HTML'] . ')') : '&nbsp;';

	$img_active = ($b_row['active']) ? $images['turn_on'] : $images['turn_off'];
	$img_border = ($b_row['border']) ? $images['border_on'] : $images['border_off'];
	$img_titlebar = ($b_row['titlebar']) ? $images['titlebar_on'] : $images['titlebar_off'];
	$img_local = ($b_row['local']) ? $images['local_on'] : $images['local_off'];
	$img_background = ($b_row['background']) ? $images['background_on'] : $images['background_off'];

	$hidden_fields = '';
	$hidden_fields .= '<input type="hidden" id="active_' . $b_id . '" value="' . $b_row['active'] . '" />';
	$hidden_fields .= '<input type="hidden" id="border_' . $b_id . '" value="' . $b_row['border'] . '" />';
	$hidden_fields .= '<input type="hidden" id="titlebar_' . $b_id . '" value="' . $b_row['titlebar'] . '" />';
	$hidden_fields .= '<input type="hidden" id="local_' . $b_id . '" value="' . $b_row['local'] . '" />';
	$hidden_fields .= '<input type="hidden" id="background_' . $b_id . '" value="' . $b_row['background'] . '" />';

	$u_move = '<img class="handle" src="' . $images['block_move'] . '" alt="' . $lang['BLOCK_MOVE'] . '" title="' . $lang['BLOCK_MOVE'] . '" style="vertical-align: middle; cursor: move;"/>&nbsp;';

	$u_active = '<img src="' . $img_active . '" alt="' . $lang['TURN_ACTIVE'] . '" title="' . $lang['TURN_ACTIVE'] . '" style="cursor: pointer;" onclick="ChangeStatus(this, 0, ' . $b_id . ', ' . $cms_type_id . ')"/>';
	$u_border = '<img src="' . $img_border . '" alt="' . $lang['TURN_BORDER'] . '" title="' . $lang['TURN_BORDER'] . '" style="cursor: pointer;" onclick="ChangeStatus(this, 1, ' . $b_id . ', ' . $cms_type_id . ')"/>';
	$u_titlebar = '<img src="' . $img_titlebar . '" alt="' . $lang['TURN_TITLEBAR'] . '" title="' . $lang['TURN_TITLEBAR'] . '" style="cursor: pointer;" onclick="ChangeStatus(this, 2, ' . $b_id . ', ' . $cms_type_id . ')"/>';
	$u_local = '<img src="' . $img_local . '" alt="' . $lang['TURN_LOCAL'] . '" title="' . $lang['TURN_LOCAL'] . '" style="cursor: pointer;" onclick="ChangeStatus(this, 3, ' . $b_id . ', ' . $cms_type_id . ')"/>';
	$u_background = '<img src="' . $img_background . '" alt="' . $lang['TURN_BACKGROUND'] . '" title="' . $lang['TURN_BACKGROUND'] . '" style="cursor: pointer;" onclick="ChangeStatus(this, 4, ' . $b_id . ', ' . $cms_type_id . ')"/>';

	$u_edit = '<a href="' . append_sid($source_file . PHP_EXT . '?mode=blocks&amp;action=edit&amp;l_id=' . $l_id . '&amp;b_id=' . $b_id) . '"><img src="' . $images['block_edit'] . '" alt="' . $lang['Block_Edit'] . '" title="' . $lang['Block_Edit'] . '"/></a>';
	$u_delete = '<a href="' . append_sid($source_file . PHP_EXT . '?mode=blocks&amp;action=delete&amp;l_id=' . $l_id . '&amp;b_id=' . $b_id) . '"><img src="' . $images['block_delete'] . '" alt="' . $lang['CMS_Delete'] . '" title="' . $lang['CMS_Delete'] . '"/></a>';

	$block_class = (!$invalid) ? 'sortable-list-div' : 'sortable-invalid-list-div';

	$output = '';
	if ($b_i == 0)
	{
		$output .= '<div class="' . $block_class . '"><span>' . $b_position_l . '</span><ul class="sortable-list" id="list_' . $b_row['bposition'] . '">';
	}
	$output .= '<li class="cms-content" id="list_' . $b_row['bposition'] . '_id' . $b_id . '" >';
	$output .= '<div class="row1" style="min-height: 24px;">';
	$output .= '<div style="text-align: center; float: left;">' . $u_move . '</div>';
	$output .= '<div style="text-align: center;"><b>' . $b_row['title'] . '</b></div>';
	$output .= '</div>';
	$output .= '<div class="container row1">';
	$output .= '<div class="left">' . $u_border . $u_titlebar . $u_local . $u_background . '<br />';
	$output .= $b_content . '&nbsp;' . $b_type . '</div>';
	$output .= '<div class="right">' . $u_active . '&nbsp;' . $u_edit . '&nbsp;' . $u_delete .'&nbsp;</div>';
	$output .= '<div class="clearCol"></div></div>';
	$output .= '<div class="row1" style="text-align: center; color: red;">' . $b_view . '</div>';
	$output .= '<div class="row1" style="text-align: center; color: blue;">' . $groups . '</div>';
	$output .= $hidden_fields;

	$output .= '</li>';
	if ($b_i == ($b_count - 1))
	{
		$output .= '</ul></div>';
	}
	return $output;
}

/*
* Create CMS field
*/
function create_cms_field($config_array)
{
	global $db, $lang;
	global $layout_options, $layout_values;

	//$controltype = array('1' => 'textbox', '2' => 'dropdown list', '3' => 'radio buttons', '4' => 'checkbox');

	$cms_field[$config_array['config_name']] = array();
	$cms_field[$config_array['config_name']]['name'] = $config_array['config_name'];
	$cms_field[$config_array['config_name']]['value'] = $config_array['config_value'];
	$cms_field[$config_array['config_name']]['label'] = $config_array['label'];
	$cms_field[$config_array['config_name']]['sub_label'] = $config_array['sub_label'];
	$cms_field[$config_array['config_name']]['field_options'] = $config_array['field_options'];
	$cms_field[$config_array['config_name']]['field_values'] = $config_array['field_values'];
	$cms_field[$config_array['config_name']]['type'] = $config_array['type'];
	$cms_field[$config_array['config_name']]['block'] = str_replace('_', ' ', $config_array['block']);

	$cms_field[$config_array['config_name']]['label'] = $lang['cms_var_' . $config_array['config_name']];
	$cms_field[$config_array['config_name']]['sub_label'] = $lang['cms_var_' . $config_array['config_name'] . '_explain'];
	if($cms_field[$config_array['config_name']]['name'] == 'default_portal')
	{
		$default_layout = array();
		$default_layout = default_layout_content();
		$cms_field[$config_array['config_name']]['label'] = $lang['Default_Portal'];
		$cms_field[$config_array['config_name']]['sub_label'] = $lang['Default_Portal_Explain'];
		$cms_field[$config_array['config_name']]['field_options'] = $default_layout['options'];
		$cms_field[$config_array['config_name']]['field_values'] = $default_layout['values'];
		$cms_field[$config_array['config_name']]['type'] = '2';
		$cms_field[$config_array['config_name']]['block'] = '@Portal Config';
	}

	switch($cms_field[$config_array['config_name']]['type'])
	{
		case '1':
			$cms_field[$config_array['config_name']]['output'] = '<input type="text" maxlength="255" size="40" name="' . $cms_field[$config_array['config_name']]['name'] . '" value="' . $cms_field[$config_array['config_name']]['value'] . '" class="post" />';
			break;
		case '2':
			$options = explode(",", $cms_field[$config_array['config_name']]['field_options']);
			$values = explode(",", $cms_field[$config_array['config_name']]['field_values']);
			$cms_field[$config_array['config_name']]['output'] = '<select name = "' . $cms_field[$config_array['config_name']]['name'] . '">';
			$i = 0;
			while ($options[$i])
			{
				$tmp_option_val = preg_replace('/[^A-Za-z0-9]+/', '_', $options[$i]);
				$options[$i] = !empty($lang['cms_option_' . $tmp_option_val]) ? $lang['cms_option_' . $tmp_option_val] : $options[$i];
				$values[$i] = !empty($lang['cms_value_' . $tmp_option_val]) ? $lang['cms_value_' . $tmp_option_val] : $values[$i];
				$selected = ($cms_field[$config_array['config_name']]['value'] == trim($values[$i])) ? 'selected' : '';
				$cms_field[$config_array['config_name']]['output'] .= '<option value = "' . trim($values[$i]) . '" ' . $selected . '>' . trim($options[$i]) . '</option>';
				$i++;
			}
			$cms_field[$config_array['config_name']]['output'] .= '</select>';
			break;
		case '3':
			$options = explode("," , $cms_field[$config_array['config_name']]['field_options']);
			$values = explode("," , $cms_field[$config_array['config_name']]['field_values']);
			$cms_field[$config_array['config_name']]['output'] = '';
			$i = 0;
			while ($options[$i])
			{
				$tmp_option_val = preg_replace('/[^A-Za-z0-9]+/', '_', $options[$i]);
				$options[$i] = !empty($lang['cms_option_' . $tmp_option_val]) ? $lang['cms_option_' . $tmp_option_val] : $options[$i];
				$values[$i] = !empty($lang['cms_value_' . $tmp_option_val]) ? $lang['cms_value_' . $tmp_option_val] : $values[$i];
				$checked = ($cms_field[$config_array['config_name']]['value'] == trim($values[$i])) ? 'checked="checked"' : '';
				$cms_field[$config_array['config_name']]['output'] .= '<input type="radio" name = "' . $cms_field[$config_array['config_name']]['name'] . '" value = "' . trim($values[$i]) . '" ' . $checked . ' />' . trim($options[$i]) . '&nbsp;&nbsp;';
				$i++;
			}
			break;
		case '4':
			$checked = ($cms_field[$config_array['config_name']]['value']) ? 'checked="checked"' : '';
			$cms_field[$config_array['config_name']]['output'] = '<input type="checkbox" name="' . $cms_field[$config_array['config_name']]['name'] . '" ' . $checked . ' />';
			break;
		default:
			$cms_field[$config_array['config_name']]['output'] = '';
	}

	return $cms_field;
}

/*
* Create CMS field with templage
*/
function create_cms_field_tpl($config_array, $check_save = false)
{
	global $db, $cache, $config, $user, $lang, $template;

	$cms_field = array();
	$cms_field = create_cms_field($config_array);

	$config_name_tmp = $config_array['config_name'];

	$default_portal[$cms_field[$config_name_tmp]['name']] = $cms_field[$config_name_tmp]['value'];

	if($cms_field[$config_name_tmp]['type'] == '4')
	{
		$new[$cms_field[$config_name_tmp]['name']] = (isset($_POST[$cms_field[$config_name_tmp]['name']])) ? '1' : '0';
	}
	else
	{
		$config_value_tmp = request_post_var($cms_field[$config_name_tmp]['name'], '', true);
		$config_value_tmp = htmlspecialchars_decode($config_value_tmp, ENT_COMPAT);
		$new[$cms_field[$config_name_tmp]['name']] = (isset($_POST[$cms_field[$config_name_tmp]['name']])) ? $config_value_tmp : $default_portal[$cms_field[$config_name_tmp]['name']];
	}

	if(!empty($check_save) && isset($_POST['save']))
	{
		$sql = "UPDATE " . CMS_CONFIG_TABLE . " SET
			config_value = '" . $db->sql_escape($new[$cms_field[$config_name_tmp]['name']]) . "'
			WHERE config_name = '" . $cms_field[$config_name_tmp]['name'] . "'";
		$result = $db->sql_query($sql);
	}
	else
	{
		$is_block = ($cms_field[$config_name_tmp]['block'] != '@Portal Config') ? 'block ' : '';
		$template->assign_block_vars('cms_block', array(
			'L_FIELD_LABEL' => $cms_field[$config_name_tmp]['label'],
			'L_FIELD_SUBLABEL' => '<br /><br /><span class="gensmall">' . $cms_field[$config_name_tmp]['sub_label'] . ' [ ' . str_replace("@", "", $cms_field[$config_name_tmp]['block']) . ' ' . $is_block . ']</span>',
			'FIELD' => $cms_field[$config_name_tmp]['output']
			)
		);
	}

	return true;
}

/*
* Get all usergroups
*/
function get_all_usergroups($info_groups = '')
{
	global $db;

	$group = '';
	$checked = '';
	if ($info_groups != '')
	{
		$group_array = explode(",", $info_groups);
	}

	$sql = "SELECT group_id, group_name FROM " . GROUPS_TABLE . " WHERE group_single_user = 0 ORDER BY group_id";
	$result = $db->sql_query($sql);

	while ($row = $db->sql_fetchrow($result))
	{
		if ($info_groups != '')
		{
			$checked = (in_array($row['group_id'], $group_array)) ? ' checked="checked"' : '';
		}
		$group .= '<input type="checkbox" name="group' . strval($row['group_id']) . '"' . $checked . ' />&nbsp;' . $row['group_name'] . '&nbsp;<br />';
	}
	$db->sql_freeresult($result);

	return $group;
}

/*
* Get max group id
*/
function get_max_group_id()
{
	global $db;

	$sql = "SELECT MAX(group_id) max_group_id FROM " . GROUPS_TABLE . " WHERE group_single_user = 0";
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	$max_group_id = $row['max_group_id'];

	return $max_group_id;
}

function get_selected_groups()
{
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
	return $b_group;
}

/*
* Get groups names
*/
function get_groups_names($groups_ids)
{
	global $db;

	$sql = "SELECT group_name FROM " . GROUPS_TABLE . " WHERE group_id IN (" . $groups_ids . ")";
	$result = $db->sql_query($sql);

	$groups = '';
	while ($row = $db->sql_fetchrow($result))
	{
		$groups .= (($groups != '') ? '<br />' : '') . '[ ' . $row['group_name'] . ' ]';
	}
	$db->sql_freeresult($result);

	return $groups;
}

/**
* Testing File Creation
*/
function file_creation($path)
{
	$test_file = $path . 'icy_phoenix_testing_write_access_permissions.test';

	// Check if the test file already exists...
	if (file_exists($test_file))
	{
		if (!@unlink($test_file))
		{
			// It seems we haven't deleted it... try to change permissions
			if (!@chmod($test_file, 0666))
			{
				return false;
			}
			else
			{
				if (!@unlink($test_file))
				{
					return false;
				}
			}
		}
	}

	// Attempt to create a new file...
	if (!@touch($test_file))
	{
		return false;
	}
	else
	{
		if (!@chmod($test_file, 0666))
		{
			if (!@unlink($test_file))
			{
				return false;
			}
			else
			{
				return true;
			}
		}
		else
		{
			// We really want to make sure...
			if (file_exists($test_file))
			{
				if (!@unlink($test_file))
				{
					return false;
				}
				else
				{
					return true;
				}
			}
			else
			{
				return false;
			}
		}
	}
	return true;
}

function show_preview($preview_type, $message)
{
	global $bbcode, $template;

	if ($preview_type == true)
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

	return true;
}

function cms_role_langs()
{
	global $db, $lang;

	$sql = "SELECT * FROM " . ACL_ROLES_TABLE . " WHERE role_type = 'cms_' ORDER BY role_order ASC";
	$result = $db->sql_query($sql);
	$rows = $db->sql_fetchrowset($result);
	$db->sql_freeresult($result);

	$i = 0;
	foreach ($rows as $data)
	{
		$role_langs['ID'][$i] = $data['role_id'];
		$role_langs['NAME'][$i] = $lang[$data['role_name']];
		$role_langs['NAME_ARRAY'][$data['role_id']] = $lang[$data['role_name']];
		$role_langs['DESC_ARRAY'][$data['role_id']] = $lang[$data['role_description']];
		$i++;
	}

	return $role_langs;
}

?>