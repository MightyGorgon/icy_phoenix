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

/*
* Common SQL
*/

function fix_weight_blocks($l_id, $table_name)
{
	global $db;
	$layout_special_sql = '';
	if ($table_name == CMS_LAYOUT_TABLE)
	{
		$layout_value = $l_id;
		$layout_special_value = 0;
		$layout_special_sql = ' AND layout_special = \'' . $layout_special_value . '\'';
		$layout_field = 'layout';
		$blocks_table = CMS_BLOCKS_TABLE;
	}
	elseif ($table_name == CMS_LAYOUT_SPECIAL_TABLE)
	{
		$layout_value = 0;
		$layout_special_value = $l_id;
		$layout_special_sql = ' AND layout_special = \'' . $layout_special_value . '\'';
		$layout_field = 'layout';
		$blocks_table = CMS_BLOCKS_TABLE;
	}
	elseif ($table_name == CMS_ADV_PAGES_TABLE)
	{
		$layout_value = $l_id;
		//$layout_field = 'page';
		$layout_field = 'layout';
		$blocks_table = CMS_ADV_BLOCKS_TABLE;
	}
	else
	{
		message_die(GENERAL_ERROR, 'Wrong table');
	}

	$sql = "SELECT DISTINCT bposition FROM " . $blocks_table . " WHERE " . $layout_field . " = '" . $layout_value . "'" . $layout_special_sql;
	if(!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not query blocks table', $lang['Error'], __LINE__, __FILE__, $sql);
	}
	$rows = $db->sql_fetchrowset($result);
	$db->sql_freeresult($result);
	$count = count($rows);

	for($i = 0; $i < $count; $i++)
	{
		$sql = "SELECT bid FROM ". $blocks_table . " WHERE " . $layout_field . " = '" . $layout_value . "'" . $layout_special_sql . " AND bposition = '" . $rows[$i]['bposition'] . "' ORDER BY weight ASC";
		if(!$result1 = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not query blocks table', $lang['Error'], __LINE__, __FILE__, $sql);
		}
		$weight = 0;
		while($row = $db->sql_fetchrow($result1))
		{
			$weight++;
			$sql = "UPDATE " . $blocks_table . " SET weight = '" . $weight . "' WHERE bposition = '" . $rows[$i]['bposition'] . "' AND bid = '" . $row['bid'] . "'";
			if(!$result2 = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not update blocks table', $lang['Error'], __LINE__, __FILE__, $sql);
			}
		}
	}
}

function get_global_blocks_layout($table_name, $field_name, $id_var_value)
{
	global $db, $lang;
	$sql = "SELECT global_blocks FROM " . $table_name . " WHERE " . $field_name . " = '" . $id_var_value . "'";
	if(!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not query layout table', $lang['Error'], __LINE__, __FILE__, $sql);
	}
	$l_row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	return $l_row;
}

function get_block_info($blocks_table, $b_id)
{
	global $db, $lang;
	$sql = "SELECT * FROM " . $blocks_table . " WHERE bid = '" . $b_id . "'";
	if(!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not query blocks table', $lang['Error'], __LINE__, __FILE__, $sql);
	}
	$b_info = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	return $b_info;
}

function get_block_positions($table_name, $l_id_list, $b_info_bposition)
{
	global $db, $lang;
	$sql = "SELECT pkey, bposition FROM " . $table_name . " WHERE layout IN (" . $l_id_list . ") ORDER BY layout, bpid";
	if(!($result = $db->sql_query($sql)))
	{
		message_die(CRITICAL_ERROR, 'Could not query blocks position information', $lang['Error'], __LINE__, __FILE__, $sql);
	}

	$position = array();
	$position['select'] = '';
	while ($row = $db->sql_fetchrow($result))
	{
		$row['pkey'] = !empty($lang['cms_pos_' . $row['pkey']]) ? $lang['cms_pos_' . $row['pkey']] : $row['pkey'];
		$position['select'] .= '<option value="' . $row['bposition'] . '" ';
		if($b_info_bposition == $row['bposition'])
		{
			$position['select'] .= 'selected="selected"';
			$position['block'] = $row['bposition'];
		}
		$position['select'] .= '>' . $row['pkey'] . '</option>';
	}
	$db->sql_freeresult($result);
	return $position;
}

function get_all_usergroups($info_groups = '')
{
	global $db, $lang;

	$group = '';
	$checked = '';
	if ($info_groups != '')
	{
		$group_array = explode(",", $info_groups);
	}

	$sql = "SELECT group_id, group_name FROM " . GROUPS_TABLE . " WHERE group_single_user = 0 ORDER BY group_id";
	if(!($result = $db->sql_query($sql)))
	{
		message_die(CRITICAL_ERROR, 'Could not query user groups information', $lang['Error'], __LINE__, __FILE__, $sql);
	}
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

function get_max_group_id()
{
	global $db, $lang;
	$sql = "SELECT MAX(group_id) max_group_id FROM " . GROUPS_TABLE . " WHERE group_single_user = 0";
	if(!($result = $db->sql_query($sql)))
	{
		message_die(CRITICAL_ERROR, 'Could not query user groups information', $lang['Error'], __LINE__, __FILE__, $sql);
	}
	$row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	$max_group_id = $row['max_group_id'];
	return $max_group_id;
}

function delete_block_config_single($cfg_table, $blocks_var_table, $b_id, $config_name)
{
	global $db, $lang;
	$sql = "DELETE FROM " . $cfg_table . " WHERE bid = '" . $b_id . "' AND config_name = '" . $config_name . "'";
	if(!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not remove data from blocks config table', $lang['Error'], __LINE__, __FILE__, $sql);
	}

	$sql = "DELETE FROM " . $blocks_var_table . " WHERE bid = '" . $b_id . "' AND config_name = '" . $config_name . "'";
	if(!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not remove data from blocks vars table', $lang['Error'], __LINE__, __FILE__, $sql);
	}
	return true;
}

function delete_block_config_all($cfg_table, $blocks_var_table, $b_id)
{
	global $db, $lang;
	$sql = "DELETE FROM " . $cfg_table . " WHERE bid = '" . $b_id . "'";
	if(!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not remove data from blocks config table', $lang['Error'], __LINE__, __FILE__, $sql);
	}

	$sql = "DELETE FROM " . $blocks_var_table . " WHERE bid = '" . $b_id . "'";
	if(!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not remove data from blocks config table', $lang['Error'], __LINE__, __FILE__, $sql);
	}
	return true;
}

function delete_block($table_name, $b_id)
{
	global $db, $lang;

	if ($table_name == CMS_BLOCKS_TABLE)
	{
		$cfg_table = CMS_CONFIG_TABLE;
		$blocks_var_table = CMS_BLOCK_VARIABLE_TABLE;
	}
	else
	{
		$cfg_table = CMS_ADV_CONFIG_TABLE;
		$blocks_var_table = CMS_ADV_BLOCK_VARIABLE_TABLE;
	}

	$sql = "DELETE FROM " . $table_name . " WHERE bid = " . $b_id;
	if(!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not remove data from blocks table', $lang['Error'], __LINE__, __FILE__, $sql);
	}
	delete_block_config_all($cfg_table, $blocks_var_table, $b_id);
	return true;
}

function get_existing_block_var($table_name, $b_id, $block_variable_name)
{
	global $db, $lang;
	$sql = "SELECT count(1) existing FROM " . $table_name . "
		WHERE config_name = '" . $block_variable_name . "'
			AND bid = '" . $b_id . "'";
	if(!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not query information from block variable table', $lang['Error'], __LINE__, __FILE__, $sql);
	}
	$row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	$existing = $row['existing'];
	return $existing;
}

function get_max_blocks_position($table_name, $id_var_value, $b_bposition)
{
	global $db, $lang;
	$sql = "SELECT max(weight) mweight FROM " . $table_name . " WHERE layout = '" . $id_var_value . "' AND bposition = '" . $b_bposition . "'";
	if(!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not query from blocks table', $lang['Error'], __LINE__, __FILE__, $sql);
	}
	$row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	$weight = $row['mweight'];
	return $weight;
}

function get_max_block_id($table_name)
{
	global $db, $lang;
	$sql = "SELECT max(bid) mbid FROM " . $table_name;
	if(!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not query from blocks table', $lang['Error'], __LINE__, __FILE__, $sql);
	}
	$row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	$b_id = $row['mbid'];
	return $b_id;
}

function get_groups_names($groups_ids)
{
	global $db, $lang;
	$sql = "SELECT group_name FROM " . GROUPS_TABLE . " WHERE group_id IN (" . $groups_ids . ")";
	if(!($result = $db->sql_query($sql)))
	{
		message_die(CRITICAL_ERROR, 'Could not query user groups information', '', __LINE__, __FILE__, $sql);
	}
	$groups = '';
	while ($row = $db->sql_fetchrow($result))
	{
		$groups .= (($groups != '') ? '<br />' : '') . '[ ' . $row['group_name'] . ' ]';
	}
	$db->sql_freeresult($result);
	return $groups;
}

function get_layout_name($table_name, $field_name, $id_var_value)
{
	global $db, $lang;
	$sql = "SELECT name, filename, global_blocks, page_nav FROM " . $table_name . " WHERE " . $field_name . " = '" . $id_var_value . "'";
	if(!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not query layout table', $lang['Error'], __LINE__, __FILE__, $sql);
	}
	$l_row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	return $l_row;
}

function get_blocks_from_layouts($table_name, $block_layout_field, $l_id_list, $sql_no_gb = '')
{
	global $db, $lang;
	$sql = "SELECT * FROM " . $table_name . " WHERE " . $block_layout_field . " IN (" . $l_id_list . ")" . $sql_no_gb . " ORDER BY bposition, weight";
	if(!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not query blocks table', $lang['Error'], __LINE__, __FILE__, $sql);
	}
	$b_rows = $db->sql_fetchrowset($result);
	$db->sql_freeresult($result);
	return $b_rows;
}

function get_blocks_positions_layout($table_name, $l_id_list)
{
	global $db, $lang;
	$sql = "SELECT bposition, pkey FROM " . $table_name . " WHERE layout IN ('" . $l_id_list . "')";
	if(!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not query blocks position table', $lang['Error'], __LINE__, __FILE__, $sql);
	}
	$position = array();
	while ($row = $db->sql_fetchrow($result))
	{
		$position[$row['bposition']] = $row['pkey'];
	}
	$db->sql_freeresult($result);
	return $position;
}

function get_layout_info($table_name, $l_id)
{
	global $db, $lang;
	$sql = "SELECT * FROM " . $table_name . " WHERE lid = '" . $l_id . "'";
	if(!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not query layout table', "Error", __LINE__, __FILE__, $sql);
	}
	$l_info = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	return $l_info;
}

function get_max_layout_id($table_name)
{
	global $db, $lang;
	$sql = "SELECT lid FROM " . $table_name . " ORDER BY lid desc LIMIT 1";
	if(!($result = $db->sql_query($sql)))
	{
		message_die(CRITICAL_ERROR, 'Could not query themes information', $lang['Error'], __LINE__, __FILE__, $sql);
	}
	$row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	return $row[lid];
}

function delete_layout($layout_table, $block_pos_table, $l_id)
{
	global $db, $lang;
	$sql = "DELETE FROM " . $layout_table . " WHERE lid = '" . $l_id . "'";

	if(!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not remove data from layout table', $lang['Error'], __LINE__, __FILE__, $sql);
	}

	$sql = "DELETE FROM " . $block_pos_table . " WHERE layout = '" . $l_id . "'";

	if(!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not remove data from blocks position table', $lang['Error'], __LINE__, __FILE__, $sql);
	}
	return true;
}

function count_blocks_in_layout($table_name, $l_id_list, $is_special = false, $only_active = true)
{
	global $db, $lang;
	$only_active_sql = '';
	if ($only_active == true)
	{
		$only_active_sql = ' AND active = \'1\'';
	}
	$layout_field = 'layout';
	if ($is_special == true)
	{
		$layout_field = 'layout_special';
	}
	$sql = "SELECT count(bid) blocks_counter FROM " . $table_name . " WHERE " . $layout_field . " IN (" . $l_id_list . ")" . $only_active_sql;
	if(!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not query blocks table', $lang['Error'], __LINE__, __FILE__, $sql);
	}
	$row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	return $row['blocks_counter'];
}

function make_cms_block($l_id, $b_id, $b_i, $b_count, $b_position_l, $invalid, $cms_type)
{
	global $db, $lang, $images;

	if ($cms_type == 'cms_standard')
	{
		$cms_type_id = '1';
		$cms_block_table = CMS_BLOCKS_TABLE;
		$type_append_url = '&cms_type=cms_standard';
	}
	else
	{
		$cms_type_id = '0';
		$cms_block_table = CMS_ADV_BLOCKS_TABLE;
		$type_append_url = '';
	}

	$sql = "SELECT * FROM " . $cms_block_table . " WHERE bid = " . $b_id . "";
	if(!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not query blocks table', $lang['Error'], __LINE__, __FILE__, $sql);
	}
	$b_row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);

	switch ($b_row['view'])
	{
		case '0':
			$b_view = '[ ' . $lang['B_All'] . ' ]';
			break;
		case '1':
			$b_view = '[ ' . $lang['B_Guests'] . ' ]';
			break;
		case '2':
			$b_view = '[ ' . $lang['B_Reg'] . ' ]';
			break;
		case '3':
			$b_view = '[ ' . $lang['B_Mod'] . ' ]';
			break;
		case '4':
			$b_view = '[ ' . $lang['B_Admin'] . ' ]';
			break;
	}

	if(!empty($b_row['groups']))
	{
		$groups = get_groups_names($b_row['groups']);
	}
	else
	{
		$groups = '[ ' . $lang['B_All'] . ' ]';
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

	$u_move = '<img class="handle" src="' . $images['block_move'] . '" alt="' . $lang['Block_Move'] . '" title="' . $lang['BLOCK_MOVE'] . '" style="vertical-align:middle;cursor:move"/>&nbsp;';

	$u_active = '<img src="' . $img_active . '" alt="' . $lang['TURN_ACTIVE'] . '" title="' . $lang['TURN_ACTIVE'] . '" style="cursor:pointer" onclick="ChangeStatus(this, 0, ' . $b_id . ', ' . $cms_type_id . ')"/>';
	$u_border = '<img src="' . $img_border . '" alt="' . $lang['TURN_BORDER'] . '" title="' . $lang['TURN_BORDER'] . '" style="cursor:pointer" onclick="ChangeStatus(this, 1, ' . $b_id . ', ' . $cms_type_id . ')"/>';
	$u_titlebar = '<img src="' . $img_titlebar . '" alt="' . $lang['TURN_TITLEBAR'] . '" title="' . $lang['TURN_TITLEBAR'] . '" style="cursor:pointer" onclick="ChangeStatus(this, 2, ' . $b_id . ', ' . $cms_type_id . ')"/>';
	$u_local = '<img src="' . $img_local . '" alt="' . $lang['TURN_LOCAL'] . '" title="' . $lang['TURN_LOCAL'] . '" style="cursor:pointer" onclick="ChangeStatus(this, 3, ' . $b_id . ', ' . $cms_type_id . ')"/>';
	$u_background = '<img src="' . $img_background . '" alt="' . $lang['TURN_BACKGROUND'] . '" title="' . $lang['TURN_BACKGROUND'] . '" style="cursor:pointer" onclick="ChangeStatus(this, 4, ' . $b_id . ', ' . $cms_type_id . ')"/>';

	$u_edit = '<a href="' . append_sid('cms_adv.' . PHP_EXT . '?mode=blocks' . $type_append_url . '&amp;action=edit&amp;l_id=' . $l_id . '&amp;b_id=' . $b_id) . '"><img src="' . $images['block_edit'] . '" alt="' . $lang['Block_Edit'] . '" title="' . $lang['Block_Edit'] . '"/></a>';
	$u_delete = '<a href="' . append_sid('cms_adv.' . PHP_EXT . '?mode=blocks' . $type_append_url . '&amp;action=delete&amp;l_id=' . $l_id . '&amp;b_id=' . $b_id) . '"><img src="' . $images['block_delete'] . '" alt="' . $lang['CMS_Delete'] . '" title="' . $lang['CMS_Delete'] . '"/></a>';

	$block_class = (!$invalid) ? 'sortable-list-div' : 'sortable-invalid-list-div';

	$output = '';
	if ($b_i == 0)
	{
		$output .= '<div class="' . $block_class . '"><span>' . $b_position_l . '</span><ul class="sortable-list" id="list_' . $b_row['bposition'] . '">';
	}
	$output .= '<li class="cms-content" id="list_' . $b_row['bposition'] . '_id' . $b_id . '" >';
	$output .= '<div class="row1" style="min-height:24px;">';
	$output .= '<div style="text-align:center;float:left;">' . $u_move . '</div>';
	$output .= '<div style="text-align:center;"><b>' . $b_row['title'] . '</b></div>';
	$output .= '</div>';
	$output .= '<div class="container row1">';
	$output .= '<div class="left">' . $u_border . $u_titlebar . $u_local . $u_background . '<br />';
	$output .= $b_content . '&nbsp;' . $b_type . '</div>';
	$output .= '<div class="right">' . $u_active . '&nbsp;' . $u_edit . '&nbsp;' . $u_delete .'&nbsp;</div>';
	$output .= '<div class="clearCol"></div></div>';
	$output .= '<div class="row1" style="text-align:center;color:red">' . $b_view . '</div>';
	$output .= '<div class="row1" style="text-align:center;color:blue">' . $groups . '</div>';
	$output .= $hidden_fields;

	$output .= '</li>';
	if ($b_i == ($b_count - 1))
	{
		$output .= '</ul></div>';
	}
	return $output;
}

function default_layout_content()
{
	global $db, $lang;
	$default_layout = array();
	$default_layout['options'] = '';
	$default_layout['values'] = '';
	$sql = "SELECT * FROM " . CMS_LAYOUT_TABLE . " ORDER BY lid";
	if(!$result = $db->sql_query($sql))
	{
		message_die(CRITICAL_ERROR, 'Could not query layout information', $lang['Error'], __LINE__, __FILE__, $sql);
	}
	while($row = $db->sql_fetchrow($result))
	{
		$default_layout['options'] .= (($default_layout['options'] == '') ? '' : ',') . $row['name'];
		$default_layout['values'] .= (($default_layout['values'] == '') ? '' : ',') . $row['lid'];
	}
	$db->sql_freeresult($result);
	return $default_layout;
}

function get_cms_global_vars()
{
	global $db, $lang;
	$sql = "SELECT * FROM " . CMS_BLOCK_VARIABLE_TABLE . "
		WHERE bid = 0
		ORDER BY bvid";
	if(!$result = $db->sql_query($sql))
	{
		message_die(CRITICAL_ERROR, 'Could not query site config information', $lang['Error'], __LINE__, __FILE__, $sql);
	}

	$global_var = array();
	while($row = $db->sql_fetchrow($result))
	{
		$global_var[$row['config_name']] = array();
		$global_var[$row['config_name']]['label'] = $row['label'];
		$global_var[$row['config_name']]['sub_label'] = $row['sub_label'];
		$global_var[$row['config_name']]['field_options'] = $row['field_options'];
		$global_var[$row['config_name']]['field_values'] = $row['field_values'];
		$global_var[$row['config_name']]['type'] = $row['type'];
		$global_var[$row['config_name']]['block'] = ereg_replace("_", " ", $row['block']);
	}
	$db->sql_freeresult($result);
	return $global_var;
}

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
	$cms_field[$config_array['config_name']]['block'] = ereg_replace("_", " ", $config_array['block']);

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
				$tmp_option_val = ereg_replace("[^A-Za-z0-9]", "_", $options[$i]);
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
				$tmp_option_val = ereg_replace("[^A-Za-z0-9]", "_", $options[$i]);
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

function get_layouts_details($layout_dir, $layout_extension, $common_cms_template, $layout_field = 'template', $cms_type = 'cms_standard')
{
	global $l_info;
	$layout_details = array();
	$num_layout = 0;
	$layouts = opendir($layout_dir);
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
			$layout_details[$num_layout]['img'] = '<img src="' . $img . '" alt="' . $file . '" title="' . $file . '"/>';
			$layout_details[$num_layout]['file'] = '<input type="radio" name="' . $layout_field . '" value="' . $file . '"';
			if(!empty($l_info) && $l_info['template'] == $file)
			{
				$layout_details[$num_layout]['file'] .= 'checked="checked"';
			}
			$layout_details[$num_layout]['file'] .= '/>';
			$num_layout++;
		}
	}
	return $layout_details;
}

function get_layouts_details_select($layout_dir, $layout_extension)
{
	global $l_info;
	$layout_details = '';
	$layouts = opendir($layout_dir);
	while ($file = readdir($layouts))
	{
		$pos = strpos($file, $layout_extension);
		if (($pos !== false) && ($file != 'index.html'))
		{
			$layout_details .= '<option value="' . $file .'" ';
			if(!empty($l_info) && ($l_info['template'] == $file))
			{
				$layout_details .= 'selected="selected"';
			}
			$layout_details .= '>' . $file . '</option>';
		}
	}
	return $layout_details;
}

/*
function get_($, $)
{
	global $db, $lang;

	return $;
}

*/

?>