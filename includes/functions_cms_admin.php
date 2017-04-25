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
function get_cms_access_auth($cms_page, $mode = '', $action = '', $l_id = 0, $b_id = 0)
{
	global $db, $cache, $config, $auth, $user, $lang;

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
			switch ($mode)
			{
				case 'blocks':
					$is_auth = ($auth->acl_get('cms_admin') || $auth->acl_get('cms_blocks_global') || (!empty($l_id) && !empty($user->data['user_cms_auth']['cmsl_admin'][$l_id]))) ? true : false;
				break;

				case 'block_settings':
					// OLD IF... left here to be checked...
					//if ((($action == 'list') || ($action == 'edit') || ($action == 'save') || empty($action)) && !isset($_POST['action_update']))
					$is_auth = ($auth->acl_get('cms_admin') || $auth->acl_get('cms_blocks') || (!empty($b_id) && !empty($user->data['user_cms_auth']['cmsb_admin'][$b_id]))) ? true : false;
				break;

				case 'layouts':
				case 'layouts_adv':
					$is_auth = ($auth->acl_get('cms_admin') || $auth->acl_get('cms_layouts') || (!empty($l_id) && !empty($user->data['user_cms_auth']['cmsl_admin'][$l_id]))) ? true : false;
				break;

				case 'layouts_special':
					$is_auth = ($auth->acl_get('cms_admin') || $auth->acl_get('cms_layouts_special')) ? true : false;
				break;

				case 'config':
					$is_auth = ($auth->acl_get('cms_admin') || $auth->acl_get('cms_settings')) ? true : false;
				break;

				default:
					$is_auth = $auth->acl_get('cms_admin') ? true : false;
				break;
			}
		break;

		case 'cms_ads':
			$is_auth = ($auth->acl_get('cms_admin') || $auth->acl_get('cms_ads')) ? true : false;
		break;

		case 'cms_auth':
			$is_auth = ($auth->acl_get('cms_admin') || $auth->acl_get('cms_permissions')) ? true : false;
		break;

		case 'cms_menu':
			$is_auth = ($auth->acl_get('cms_admin') || $auth->acl_get('cms_menu')) ? true : false;
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

define('CMS_FIELD_TEXTBOX', '1');
define('CMS_FIELD_DROPDOWN', '2');
define('CMS_FIELD_RADIO', '3');
define('CMS_FIELD_CHECKBOX', '4');
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

	// if no label was given, guess from the config_name
	if (empty($config_array['label']))
	{
		$cms_field[$config_array['config_name']]['label'] = $lang['cms_var_' . $config_array['config_name']];
	}
	// if a label was given, and it's a lang key, use it
	elseif (isset($lang[$config_array['label']]))
	{
		$cms_field[$config_array['config_name']]['label'] = $lang[$config_array['label']];
	}
	
	// if no sub_label was given, guess from the config_name
	if (empty($config_array['sub_label']))
	{
		$cms_field[$config_array['config_name']]['sub_label'] = $lang['cms_var_' . $config_array['config_name'] . '_explain'];
	}
	// if a sub_label was given, and it's a lang key, use it
	elseif (isset($lang[$config_array['sub_label']]))
	{
		$cms_field[$config_array['config_name']]['sub_label'] = $lang[$config_array['sub_label']];
	}

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
		case CMS_FIELD_TEXTBOX:
			$cms_field[$config_array['config_name']]['output'] = '<input type="text" maxlength="255" size="40" name="' . $cms_field[$config_array['config_name']]['name'] . '" value="' . $cms_field[$config_array['config_name']]['value'] . '" class="post" />';
			break;
		case CMS_FIELD_DROPDOWN:
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
		case CMS_FIELD_RADIO:
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
		case CMS_FIELD_CHECKBOX:
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

	if($cms_field[$config_name_tmp]['type'] == CMS_FIELD_CHECKBOX)
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
	global $db, $cache;

	$group = '';
	$checked = '';
	if (!empty($info_groups))
	{
		$group_array = explode(",", $info_groups);
	}

	$groups_data = get_groups_data(false, true, array());
	foreach ($groups_data as $group_data)
	{
		$checked = (empty($info_groups) || in_array($group_data['group_id'], $group_array)) ? ' checked="checked"' : '';
		$group .= '<input type="checkbox" name="group' . strval($group_data['group_id']) . '"' . $checked . ' />&nbsp;' . $group_data['group_name'] . '&nbsp;<br />';
	}

	return $group;
}

/*
* Get max group id
*/
function get_max_group_id()
{
	global $db, $cache;

	$groups_data = get_groups_data(false, false, array());
	$groups_ids = array();
	foreach ($groups_data as $group_data)
	{
		$groups_ids[] = $group_data['group_id'];
	}
	$max_group_id = max($groups_ids);

	return $max_group_id;
}

/*
* Get selected groups
*/
function get_selected_groups()
{
	global $db, $cache;

	$selected_groups_result = '';
	$selected_groups = array();

	$groups_data = get_groups_data(false, true, array());
	foreach ($groups_data as $group_data)
	{
		if(isset($_POST['group' . strval($group_data['group_id'])]))
		{
			$selected_groups[] = strval($group_data['group_id']);
		}
	}

	if (!empty($selected_groups) && (sizeof($groups_data) != sizeof($selected_groups)))
	{
		$selected_groups_result = implode(',', $selected_groups);
	}

	return $selected_groups_result;
}

/*
* Get groups names
*/
function get_groups_names($groups_ids)
{
	global $db, $cache;

	$groups_ids_array = explode(',', str_replace(array(' ', ', '), array(' ', ','), $groups_ids));
	$groups_data = get_groups_data(false, true, $groups_ids_array);
	$groups = '';
	foreach ($groups_data as $group_data)
	{
		$groups .= (($groups != '') ? '<br />' : '') . '[ ' . $group_data['group_name'] . ' ]';
	}

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