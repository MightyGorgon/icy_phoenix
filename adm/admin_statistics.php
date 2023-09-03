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
* @Extra credits for this file
* Meik Sievertsen (acyd.burn@gmx.de)
*
*/

define('IN_ICYPHOENIX', true);

if(!empty($setmodules))
{
	$filename = basename(__FILE__);
	$module['2500_STATS']['Statistics_management'] = $filename . '?mode=manage';
	$module['2500_STATS']['Statistics_config'] = $filename . '?mode=config';
	return;
}

if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('pagestart.' . PHP_EXT);

setup_extra_lang(array('lang_statistics'));

// FUNCTIONS - BEGIN
if (!function_exists('gen_auth_select'))
{
	function gen_auth_select($default_auth_value)
	{
		global $lang;

		$auth_levels = array('ALL', 'REG', 'MOD', 'ADMIN');
		$auth_const = array(AUTH_ALL, AUTH_REG, AUTH_MOD, AUTH_ADMIN);

		$select_list = '<select name="auth_fields">';

		for($i = 0; $i < sizeof($auth_levels); $i++)
		{
			$selected = ($default_auth_value == $auth_const[$i]) ? ' selected="selected"' : '';
			$select_list .= '<option value="' . $auth_const[$i] . '"' . $selected . '>' . $lang['Forum_' . $auth_levels[$i]] . '</option>';
		}
		$select_list .= '</select>';

		return ($select_list);
	}
}

if (!function_exists('renumbering_order'))
{
	function renumbering_order()
	{
		global $db;

		$sql = "SELECT module_id FROM " . STATS_MODULES_TABLE . "
		ORDER BY display_order ASC";
		$result = $db->sql_query($sql);

		$i = 10;
		$inc = 10;

		while($row = $db->sql_fetchrow($result))
		{
			$sql = "UPDATE " . STATS_MODULES_TABLE . "
			SET display_order = " . $i . "
			WHERE module_id = " . $row['module_id'];
			$db->sql_query($sql);
			$i += $inc;
		}
	}
}
// FUNCTIONS - END

$__stats_config = array();

$db->clear_cache('stats_config_');
$sql = 'SELECT * FROM ' . STATS_CONFIG_TABLE;
$result = $db->sql_query($sql);

while ($row = $db->sql_fetchrow($result))
{
	$__stats_config[$row['config_name']] = trim($row['config_value']);
}

include(IP_ROOT_PATH . 'includes/functions_stats.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/class_stats_module.' . PHP_EXT);

// Mighty Gorgon: this should be not needed anymore...
/*
// Try to re-assign Images for Admin Display
@reset($images);
//while (list($key, $value) = each($images))
foreach ($images as $key => $value)
{
	if ((!is_array($images[$key])) && ($images[$key] != ''))
	{
		$images[$key] = './../' . $images[$key];
	}
}

// Now try to re-assign the smilies
$config['smilies_path'] = './../' . $config['smilies_path'];
*/

$mode = request_var('mode', '');
$submit = request_var('submit', '');
$module_id = request_var(POST_FORUM_URL, 0);

$msg = '';
$templated = true;

if(isset($_POST['update']))
{
	$modules_upd = array();
	$modules_upd = request_post_var('module_status', array(0));

	$sql = "SELECT * FROM " . STATS_MODULES_TABLE . " ORDER BY module_id ASC";
	$result = $db->sql_query($sql);
	$m_rows = array();
	$m_rows = $db->sql_fetchrowset($result);
	$m_count = sizeof($m_rows);

	for($i = 0; $i < $m_count; $i++)
	{
		$update_time = request_post_var('module_time_' . $m_rows[$i]['module_id'], 0);
		$m_active = empty($modules_upd) ? 0 : (in_array($m_rows[$i]['module_id'], $modules_upd) ? 1 : 0);
		$sql = "UPDATE " . STATS_MODULES_TABLE . "
						SET active = '" . $m_active . "', update_time = '" . $update_time . "'
						WHERE module_id = '" . $m_rows[$i]['module_id'] . "'";
		$result = $db->sql_query($sql);
	}
	$mode = 'manage';
}

if ($mode == 'order')
{
	// Change order of modules in the DB
	$move = request_var('move', 0);

	$sql = "UPDATE " . STATS_MODULES_TABLE . "
	SET display_order = display_order + $move
	WHERE module_id = " . $module_id;
	$result = $db->sql_query($sql);
	renumbering_order();
	$mode = 'manage';
}

if ($submit && ($mode == 'config'))
{
	if (!empty($_POST['return_limit_set']))
	{
		$update_value = request_var('return_limit_set', 0);

		if (intval($__stats_config['return_limit']) != intval($update_value))
		{
			$sql = "UPDATE " . STATS_CONFIG_TABLE . "
			SET config_value = '$update_value'
			WHERE (config_name = 'return_limit')";
			$result = $db->sql_query($sql);
			$msg .= '<br />' . $lang['Updated'] . ' : ' . $lang['Return_limit'];
		}
	}

	if (!empty($_POST['clear_cache_set']))
	{
		$sql = "UPDATE " . STATS_MODULES_TABLE . "
		SET module_info_time = 0,
		module_cache_time = 0";
		$result = $db->sql_query($sql);
		$msg .= '<br />' . $lang['Updated'] . ' : ' . $lang['Clear_cache'];
	}

	if (!empty($_POST['modules_dir_set']))
	{
		$update_value = request_var('modules_dir_set', '');

		if ($__stats_config['modules_dir'] != $update_value)
		{
			$sql = "UPDATE " . STATS_CONFIG_TABLE . "
			SET config_value = '$update_value'
			WHERE (config_name = 'modules_dir')";
			$result = $db->sql_query($sql);
			$msg .= '<br />' . $lang['Updated'] . ' : ' . $lang['Modules_directory'];
		}
	}
}

if ($mode == 'config')
{
	$template->set_filenames(array('body' => ADM_TPL . 'stat_config_body.tpl'));

	$__stats_config = array();

	$sql = 'SELECT * FROM ' . STATS_CONFIG_TABLE;
	$result = $db->sql_query($sql);

	while ($row = $db->sql_fetchrow($result))
	{
		$__stats_config[$row['config_name']] = $row['config_value'];
	}

	$template->assign_vars(array(
		'L_RETURN_LIMIT' => $lang['Return_limit'],
		'L_RETURN_LIMIT_DESC' => $lang['Return_limit_desc'],
		'L_CLEAR_CACHE' => $lang['Clear_cache'],
		'L_CLEAR_CACHE_DESC' => $lang['Clear_cache_desc'],
		'L_MODULES_DIR' => $lang['Modules_directory'],
		'L_MODULES_DIR_DESC' => $lang['Modules_directory_desc'],

		'L_MESSAGES' => $lang['Messages'],
		'L_RESET' => $lang['Reset'],
		'L_SUBMIT' => $lang['Submit'],

		'L_STATS_CONFIG' => $lang['Statistics_config_title'],
		'MESSAGE' => $msg,

		'RETURN_LIMIT' => $__stats_config['return_limit'],
		'MODULES_DIR' => $__stats_config['modules_dir'],

		'S_ACTION' => append_sid('admin_statistics.' . PHP_EXT . '?mode=config')
		)
	);
}

if ($mode == 'install_activate')
{
	$mode = 'install';
	$var = 'activate';
}

if ($mode == 'activate')
{
	$sql = "UPDATE " . STATS_MODULES_TABLE . "
	SET active = 1
	WHERE module_id = " . $module_id;
	$result = $db->sql_query($sql);

	$sql = "SELECT * FROM " . STATS_MODULES_TABLE . "
	WHERE module_id = " . $module_id;
	$result = $db->sql_query($sql);
	$module_info = generate_module_info($db->sql_fetchrow($result));
	$msg .= '<br />' . $lang['Updated'] . ' : ' . $lang['Activated'] . ' : ' . $module_info['name'];
	$mode = 'manage';
}

if ($mode == 'deactivate')
{
	$sql = "UPDATE " . STATS_MODULES_TABLE . "
	SET active = 0
	WHERE module_id = " . $module_id;
	$result = $db->sql_query($sql);

	$sql = "SELECT * FROM " . STATS_MODULES_TABLE . "
	WHERE module_id = " . $module_id;
	$result = $db->sql_query($sql);
	$module_info = generate_module_info($db->sql_fetchrow($result));
	$msg .= '<br />' . $lang['Updated'] . ' : ' . $lang['Deactivated'] . ' : ' . $module_info['name'];
	$mode = 'manage';
}

if ($mode == 'uninstall')
{
	$sql = "UPDATE " . STATS_MODULES_TABLE . "
	SET installed = 0, active = 0
	WHERE module_id = " . $module_id;
	$result = $db->sql_query($sql);

	$sql = "SELECT * FROM " . STATS_MODULES_TABLE . "
	WHERE module_id = " . $module_id;
	$result = $db->sql_query($sql);
	$module_info = generate_module_info($db->sql_fetchrow($result));
	$msg .= '<br />' . $lang['Updated'] . ' : ' . $lang['Uninstalled'] . ' : ' . $module_info['name'];
	$mode = 'manage';
}

if ($mode == 'auto_set')
{
	$errored = false;
	$templated = false;

	print '<br />';

	$stat_module_rows = get_module_list_from_db();
	$stat_module_data = get_module_data_from_db();

	@reset($stat_module_rows);

	//while (list($module_id, $module_name) = each($stat_module_rows))
	foreach ($stat_module_rows as $module_id => $module_name)
	{
		$module_name = trim($module_name);

		$module_info = generate_module_info($stat_module_data[$module_id]);

		// Start Time
		$stat_starttime = explode(' ', microtime());
		$stat_starttime = $stat_starttime[1] + $stat_starttime[0];

		$db->num_queries['total'] = 0;

		$modules_dir = trim($module_info['dname']);
		$return_limit = $__stats_config['return_limit'];

		$module_info = generate_module_info($stat_module_data[$module_id]);
		$mod_lang = 'module_language_parse';

		$statistics->result_cache_used = false;
		$statistics->db_cache_used = false;

		$stat_db->begin_cached_query();
		$result_cache->begin_cached_results();
		include(IP_ROOT_PATH . $__stats_config['modules_dir'] . '/' . $module_name . '_module.php');

		$template->set_filenames(array('module_tpl_' . $module_id => STATS_TPL . $module_info['dname'] . '.tpl'));

		$template->pparse('module_tpl_' . $module_id);

		// End Time
		$stat_endtime = explode(' ', microtime());
		$stat_endtime = $stat_endtime[1] + $stat_endtime[0];
		$stat_totaltime = ($stat_endtime - $stat_starttime);

		$num_queries = $db->num_queries['total'];

		$update_time_recommend = 0;

		if ($totaltime > 0.2)
		{
			// Original update_time_factor was 1.5
			$update_time_factor = 4;
			$update_time_recommend = round((($stat_totaltime * $num_queries) * $update_time_factor), 0);
		}

		print '<span class="gen">Time consumed: ' . $stat_totaltime . ' - Queries executed: ' . $num_queries . ' - Recommended Update Time: ' . $update_time_recommend . '</span><br />';
		print '<br />';

		$sql = "UPDATE " . STATS_MODULES_TABLE . "
		SET update_time = " . intval($update_time_recommend) . "
		WHERE module_id = " . $module_id;
		$result = $db->sql_query($sql);
	}

	print '<br /><br /><br /><a href="' . append_sid('admin_statistics.' . PHP_EXT . '?mode=manage') . '">' . $lang['Back_to_management'] . '</a>';
}

// Manage Modules
if ($mode == 'manage')
{
	$template->set_filenames(array('body' => ADM_TPL . 'stat_manage_modules.tpl'));

	$sql = "SELECT MAX(display_order) as max, MIN(display_order) as min
	FROM " . STATS_MODULES_TABLE;
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$curr_max = $row['max'];
	$curr_min = $row['min'];

	// Update Module List
	update_module_list();

	$sql = "SELECT *
	FROM " . STATS_MODULES_TABLE . "
	ORDER BY display_order ASC";
	$result = $db->sql_query($sql);
	$rows = $db->sql_fetchrowset($result);
	$num_rows = $db->sql_numrows($result);

	for ($i = 0; $i < $num_rows; $i++)
	{
		$row_class = (($i % 2) ? $theme['td_class2'] : $theme['td_class1']);

		$module_info = generate_module_info($rows[$i]);
		$move_up = '';
		$move_down = '';
		$edit_install = '';
		$state = '';

		if ($rows[$i]['display_order'] != $curr_min)
		{
			$link = append_sid('admin_statistics.' . PHP_EXT . '?mode=order&amp;move=-15&amp;' . POST_FORUM_URL . '=' . $rows[$i]['module_id']);
			//$move_up = '<a href="' . $link . '">' . $lang['MOVE_UP'] . '</a>';
			$move_up = '<a href="' . $link . '"><img src="' . $images['cms_arrow_up'] . '" alt="' . $lang['MOVE_UP'] . '" title="' . $lang['MOVE_UP'] . '" /></a>';
		}

		if ($rows[$i]['display_order'] != $curr_max)
		{
			$link = append_sid('admin_statistics.' . PHP_EXT . '?mode=order&amp;move=15&amp;' . POST_FORUM_URL . '=' . $rows[$i]['module_id']);
			//$move_down = '<a href="' . $link . '">' . $lang['MOVE_DOWN'] . '</a>';
			$move_down = '<a href="' . $link . '"><img src="' . $images['cms_arrow_down'] . '" alt="' . $lang['MOVE_DOWN'] . '" title="' . $lang['MOVE_DOWN'] . '" /></a>';
		}

		if (intval($rows[$i]['installed']) == 1)
		{
			//$edit_install = '<a href="' . append_sid('admin_statistics.' . PHP_EXT . '?mode=edit&amp;' . POST_FORUM_URL . '=' . $rows[$i]['module_id']) . '">' . $lang['Edit'] . '</a>';
			//$edit_install .= '<br /><a href="' . append_sid('admin_statistics.' . PHP_EXT . '?mode=uninstall&amp;' . POST_FORUM_URL . '=' . $rows[$i]['module_id']) . '">' . $lang['Uninstall'] . '</a>';
			$edit_install = '<a href="' . append_sid('admin_statistics.' . PHP_EXT . '?mode=edit&amp;' . POST_FORUM_URL . '=' . $rows[$i]['module_id']) . '"><img src="' . $images['cms_icon_edit'] . '" alt="' . $lang['Edit'] . '" title="' . $lang['Edit'] . '" /></a>';
			$edit_install .= '&nbsp;';
			$edit_install .= '<a href="' . append_sid('admin_statistics.' . PHP_EXT . '?mode=uninstall&amp;' . POST_FORUM_URL . '=' . $rows[$i]['module_id']) . '"><img src="' . $images['cms_icon_delete'] . '" alt="' . $lang['Uninstall'] . '" title="' . $lang['Uninstall'] . '" /></a>';
		}
		else
		{
			//$edit_install = '<a href="' . append_sid('admin_statistics.' . PHP_EXT . '?mode=install_activate&amp;' . POST_FORUM_URL . '=' . $rows[$i]['module_id']) . '">' . $lang['Install'] . ' &amp; ' . $lang['Activate'] . '</a>';
			//$edit_install .= '<br /><a href="' . append_sid('admin_statistics.' . PHP_EXT . '?mode=install&amp;' . POST_FORUM_URL . '=' . $rows[$i]['module_id']) . '">' . $lang['Install'] . '</a>';
			$edit_install = '<a href="' . append_sid('admin_statistics.' . PHP_EXT . '?mode=install_activate&amp;' . POST_FORUM_URL . '=' . $rows[$i]['module_id']) . '"><img src="' . $images['cms_icon_refresh'] . '" alt="' . $lang['Install'] . ' &amp; ' . $lang['Activate'] . '" title="' . $lang['Install'] . ' &amp; ' . $lang['Activate'] . '" /></a>';
			$edit_install .= '&nbsp;';
			$edit_install .= '<a href="' . append_sid('admin_statistics.' . PHP_EXT . '?mode=install&amp;' . POST_FORUM_URL . '=' . $rows[$i]['module_id']) . '"><img src="' . $images['cms_icon_add'] . '" alt="' . $lang['Install'] . '" title="' . $lang['Install'] . '" /></a>';
		}

		$status_checked = '';
		$show_status_check = false;
		if (intval($rows[$i]['active']) == 1)
		{
			$state_link = '<a href="' . append_sid('admin_statistics.' . PHP_EXT . '?mode=deactivate&amp;' . POST_FORUM_URL . '=' . $rows[$i]['module_id']) . '" alt="' . $lang['Deactivate'] . '">' . $lang['Active'] . '</a>';
			$show_status_check = true;
			$status_checked = ' checked="checked"';
		}
		elseif ((intval($rows[$i]['active']) == 0) && (intval($rows[$i]['installed']) == 1))
		{
			$state_link = '<a href="' . append_sid('admin_statistics.' . PHP_EXT . '?mode=activate&amp;' . POST_FORUM_URL . '=' . $rows[$i]['module_id']) . '" alt="' . $lang['Activate'] . '">' . $lang['Not_active'] . '</a>';
			$show_status_check = true;
		}
		elseif (intval($rows[$i]['active']) == 0)
		{
			$state_link = $lang['Not_active'];
		}

		$template->assign_block_vars('modulerow', array(
			'ROW_CLASS' => $row_class,
			'MODULE_ID' => $rows[$i]['module_id'],
			'NAME' => $module_info['name'],
			'DNAME' => (isset($lang['module_name_' . $module_info['name']]) ? $lang['module_name_' . $module_info['name']] : $rows[$i]['name']),
			'U_STATE' => $state_link,
			'S_STATUS_CHECK' => $show_status_check,
			'CHECKED' => $status_checked,
			'UPDATE_TIME' => $rows[$i]['update_time'],
			'U_MOVE_UP' => $move_up,
			'U_MOVE_DOWN' => $move_down,
			'U_EDIT' => $edit_install
			)
		);
	}

	$template->assign_vars(array(
		'L_STATS_MANAGE' => $lang['Statistics_modules_title'],
		'L_MESSAGES' => $lang['Messages'],
		'L_NAME' => $lang['Module_name'],
		'L_MODULES_UPDATED' => $lang['Modules_order_update'],
		'L_MODULE_NAME' => $lang['Module_file_name'],
		'L_STATUS' => $lang['Status'],
		'L_UPDATE_TIME' => $lang['Update_time'],
		'L_UPDATE_MODULES' => $lang['Update_Modules'],
		'L_GO' => $lang['Go'],
		'S_ACTION' => append_sid('admin_statistics.' . PHP_EXT),
		'U_AUTO_SET' => append_sid('admin_statistics.' . PHP_EXT . '?mode=auto_set'),

		'MESSAGE' => $msg
		)
	);
}

if ($mode == 'install')
{
	$errored = false;
	$templated = false;

	$sql = "SELECT * FROM " . STATS_MODULES_TABLE . "
	WHERE module_id = " . $module_id;
	$result = $db->sql_query($sql);
	$module_info = generate_module_info($db->sql_fetchrow($result), true);
	$default_update_time = intval($module_info['default_update_time']);

	// Need to use inline print functions here to take care of dynamic things with the sql parse.
	// A place is made for them to show up fine without taking header time.
	print '<h3>' . $lang['Install'] . ' : ' . $module_info['name'] . '</h4>';
	print '<br />';

	print '<br />Module Name: ' . $module_info['name'];
	print '<br />Module Version: ' . $module_info['version'];
	print '<br />Module Author: ' . $module_info['author'];
	print '<br />Author Email: ' . $module_info['email'];
	print '<br />Author URL: ' . $module_info['url'];
	print '<br /><br /><br />' . $module_info['extra_info'];

	$do_install = true;

	// Lets see if we are allowed to install this one. ;)
	if (!$module_info['condition_result'])
	{
		print '<br /><br />' . $lang['Not_allowed_to_install'] . '<br />';
		$do_install = false;
	}
	else
	{
		$version = str_replace('.', '', $__stats_config['version']);
		$version = intval($version);
		$module_version = str_replace('.', '', $module_info['stats_mod_version']);
		$module_version = intval($module_version);

		if ($version < $module_version)
		{
			print '<br /><br />' . sprintf($lang['Wrong_stats_mod_version'], $module_info['stats_mod_version']) . '<br />';
			$do_install = false;
		}

 }

	if ($do_install)
	{

		$dbms_file = IP_ROOT_PATH . $__stats_config['modules_dir'] . '/' . $module_info['dname'] . '_' . $available_dbms[$dbms]['SCHEMA'] . '.sql';

		$remove_remarks = $available_dbms[$dbms]['COMMENTS'];
		$delimiter = $available_dbms[$dbms]['DELIM'];
		$delimiter_basic = $available_dbms[$dbms]['DELIM_BASIC'];

		$sql = true;

		if (!($fp = @fopen($dbms_file, 'r')))
		{
	//		print "<br />No SQL File found... expected: " . $dbms_file . "<br />";
			print "<br /><br />No need to install any SQL specific things.<br />";
			$sql = false;
		}

		if ($sql)
		{
			fclose($fp);
			$sql_query = @fread(@fopen($dbms_file, 'r'), @filesize($dbms_file));
			$sql_query = preg_replace('/phpbb_/', $table_prefix, $sql_query);

			$db->remove_remarks($sql_query);
			$sql_query = $db->split_sql_file($sql_query, $delimiter);

			$sql_count = sizeof($sql_query);

			if ($sql_count == 0)
			{
				print "<br />SQL File empty... no need to install any SQL specific things.<br />";
			}

			for($i = 0; $i < $sql_count; $i++)
			{
				print "Running :: " . $sql_query[$i];
				flush();
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql_query[$i]);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					$errored = true;
					$error = $db->sql_error();
					print " -> <b>FAILED</b> ---> <u>" . $error['message'] . "</u><br /><br />\n\n";
				}
				else
				{
					print " -> <b>COMPLETED</b><br /><br />\n\n";
				}
			}
		}

		if (!$errored)
		{
			$sql = "UPDATE " . STATS_MODULES_TABLE . "
			SET installed = 1, update_time = " . $default_update_time . "
			WHERE module_id = " . $module_id;
			$result = $db->sql_query($sql);
		}
		else
		{
			print '<br /><span class="text_red">' . $lang['Module_install_error'] . '</span>';
		}

		if ((isset($var)) && ($var != ''))
		{
			if ($var == 'activate')
			{
				$sql = "UPDATE " . STATS_MODULES_TABLE . "
				SET active = 1
				WHERE module_id = " . $module_id;
				$result = $db->sql_query($sql);
				print '<br />' . $lang['Updated'] . ' : ' . $lang['Activated'] . ' : ' . $module_info['name'];
			}
		}
	}

	print '<br /><br /><br /><a href="' . append_sid('admin_statistics.' . PHP_EXT . '?mode=manage') . '">' . $lang['Back_to_management'] . '</a>';
}

if ($submit && ($mode == 'edit'))
{
	$auth_value = (!empty($_POST['auth_fields'])) ? intval($_POST['auth_fields']) : 0;

	$sql = "UPDATE " . STATS_MODULES_TABLE . "
	SET auth_value = " . $auth_value . "
	WHERE module_id = " . $module_id;
	$result = $db->sql_query($sql);

	$sql = "SELECT * FROM " . STATS_MODULES_TABLE . "
	WHERE module_id = " . $module_id;
	$result = $db->sql_query($sql);
	$module_info = generate_module_info($db->sql_fetchrow($result));
	$msg .= '<br />' . $lang['Updated'] . ' : ' . $lang['Auth_settings_updated'] . ' : ' . $module_info['name'];
	$update_value = (isset($_POST['active'])) ? intval($_POST['active']) : 0;

	if (intval($module_info['active']) != $update_value)
	{
		$sql = "UPDATE " . STATS_MODULES_TABLE . "
		SET active = " . intval($update_value) . "
		WHERE module_id = " . $module_id;
		$result = $db->sql_query($sql);
		$msg .= '<br />' . $lang['Updated'] . ' : ' . $lang['Active'] . ' : ' . $module_info['name'];
	}

	$update_value = (isset($_POST['updatetime'])) ? intval($_POST['updatetime']) : 0;

	if (intval($module_info['update_time']) != intval($update_value))
	{
		$sql = "UPDATE " . STATS_MODULES_TABLE . "
		SET update_time = " . intval($update_value) . "
		WHERE module_id = " . $module_id;
		$result = $db->sql_query($sql);
		$msg .= '<br />' . $lang['Updated'] . ' : ' . $lang['Update_time'] . ' : ' . $module_info['name'];
	}

	if (isset($_POST['uninstall']) && (intval($_POST['uninstall']) == 0))
	{
		$sql = "UPDATE " . STATS_MODULES_TABLE . "
		SET installed = 0, active = 0
		WHERE module_id = " . $module_id;
		$result = $db->sql_query($sql);
		$msg .= '<br />' . $lang['Updated'] . ' : ' . $lang['Uninstalled'] . ' : ' . $module_info['name'];
	}
}

if ($mode == 'edit')
{
	$template->set_filenames(array('body' => ADM_TPL . 'stat_edit_module.tpl'));

	// Set up Preview Page
	$sql = "SELECT * FROM " . STATS_MODULES_TABLE . "
	WHERE module_id = " . $module_id;
	$result = $db->sql_query($sql);
	$__stat_module_data[$module_id] = $db->sql_fetchrow($result);
	$module_info = generate_module_info($__stat_module_data[$module_id]);
	$module_name = trim($module_info['dname']);

	$auth_value = intval($module_info['auth_value']);

	$template->assign_vars(array(
		'ACTIVE_CHECKED_YES' => (intval($module_info['active']) == 1) ? 'checked="checked"' : '',
		'ACTIVE_CHECKED_NO' => (intval($module_info['active']) == 0) ? 'checked="checked"' : '',
		'UPDATE_TIME' => $module_info['update_time'],
		'MODULE_DNAME' => $module_info['dname'],
		'S_AUTH_SELECT' => gen_auth_select($auth_value),
		'MODULE_NAME' => $module_info['name']
		)
	);

	// Compile the Module without using cache functions if it's active
	$return_limit = $__stats_config['return_limit'];

	// Start Time
	$stat_starttime = explode(' ', microtime());
	$stat_starttime = $stat_starttime[1] + $stat_starttime[0];

	$db->num_queries['total'] = 0;

	$mod_lang = 'module_language_parse';
	$__module_id = $module_id;
	$__module_info = generate_module_info($__stat_module_data[$__module_id]);
	$__module_name = $module_name;

	$__tpl_name = 'preview';
	$__module_root_path = './../' . IP_ROOT_PATH;
	$__module_data = $__stat_module_data[$__module_id];

	$statistics->result_cache_used = false;
	$statistics->db_cache_used = false;

	$stat_db->begin_cached_query();
	$result_cache->begin_cached_results();
	include(IP_ROOT_PATH . $__stats_config['modules_dir'] . '/' . $__module_name . '_module.php');
	$stat_db->end_cached_query($__module_id);
	$result_cache->end_cached_query($__module_id);

	$template->set_filenames(array($__tpl_name => STATS_TPL . $__module_info['dname'] . '.tpl'));

	// End Time
	$stat_endtime = explode(' ', microtime());
	$stat_endtime = $stat_endtime[1] + $stat_endtime[0];
	$stat_totaltime = ($stat_endtime - $stat_starttime);

	$num_queries = $db->num_queries['total'];

	$update_time_recommend = 0;

	if ($totaltime > 0.2)
	{
		$update_time_recommend = round((($stat_totaltime * $num_queries) * 1.5), 0);
	}

	$template->assign_vars(array(
		'GRAPH_IMAGE' => $images['voting_graphic_body'],
		'LEFT_GRAPH_IMAGE' => $images['voting_graphic_left'],
		'RIGHT_GRAPH_IMAGE' => $images['voting_graphic_right'],
		'R_GRAPH_IMAGE' => $images['voting_graphic_red_body'],
		'R_LEFT_GRAPH_IMAGE' => $images['voting_graphic_red_left'],
		'R_RIGHT_GRAPH_IMAGE' => $images['voting_graphic_red_right'],
		'G_GRAPH_IMAGE' => $images['voting_graphic_green_body'],
		'G_LEFT_GRAPH_IMAGE' => $images['voting_graphic_green_left'],
		'G_RIGHT_GRAPH_IMAGE' => $images['voting_graphic_green_right'],
		'B_GRAPH_IMAGE' => $images['voting_graphic_blue_body'],
		'B_LEFT_GRAPH_IMAGE' => $images['voting_graphic_blue_left'],
		'B_RIGHT_GRAPH_IMAGE' => $images['voting_graphic_blue_right'],

		'MESSAGE' => $msg,
		'L_ACTIVE' => $lang['Active'],
		'L_ACTIVE_DESC' => $lang['Active_desc'],
		'L_AUTH_SETTINGS' => $lang['Permissions'],
		'L_EDIT' => $lang['Edit'],
		'L_UPDATE_TIME' => $lang['Update_time_minutes'],
		'L_UPDATE_TIME_DESC' => $lang['Update_time_desc'],
		'L_YES' => $lang['Yes'],
		'L_NO' => $lang['No'],
		'L_UNINSTALL' => $lang['Uninstall_module'],
		'L_UNINSTALL_DESC' => $lang['Uninstall_module_desc'],
		'L_MESSAGES' => $lang['Messages'],
		'L_SUBMIT' => $lang['Submit'],
		'L_RESET' => $lang['Reset'],
		'L_PREVIEW' => $lang['Preview'],
		'L_PREVIEW_DEBUG_INFO' => sprintf($lang['Preview_debug_info'], $stat_totaltime, $num_queries),
		'L_UPDATE_TIME_RECOMMEND' => sprintf($lang['Update_time_recommend'], $update_time_recommend),
		'L_BACK_TO_MANAGEMENT' => $lang['Back_to_management'],
		'U_MANAGEMENT' => append_sid('admin_statistics.' . PHP_EXT . '?mode=manage'),

		'S_ACTION' => append_sid('admin_statistics.' . PHP_EXT . '?mode=edit&amp;' . POST_FORUM_URL . '=' . $module_id)
		)
	);

	$template->assign_var_from_handle('PREVIEW_MODULE', 'preview');
}

$template->assign_vars(array(
	'VIEWED_INFO' => sprintf($lang['Viewed_info'], $__stats_config['page_views']),
	'INSTALL_INFO' => sprintf($lang['Install_info'], create_date($config['default_dateformat'], $__stats_config['install_date'], $config['board_timezone'])),
	'VERSION_INFO' => sprintf($lang['Version_info'], $__stats_config['version'])
	)
);

$db->clear_cache('stats_config_');

if ($templated)
{
	$template->pparse('body');
	include('page_footer_admin.' . PHP_EXT);
}

?>