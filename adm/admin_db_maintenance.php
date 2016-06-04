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
* Philipp Kordowich
*
*/

define('IN_ICYPHOENIX', true);

// Mighty Gorgon - ACP Privacy - BEGIN
if (function_exists('check_acp_module_access'))
{
	$is_allowed = check_acp_module_access();
	if (empty($is_allowed))
	{
		return;
	}
}
// Mighty Gorgon - ACP Privacy - END

if (!empty($setmodules))
{
	$filename = basename(__FILE__);
	$module['1400_DB_Maintenance']['150_DB_Maintenance'] = $filename;
	$ja_module['1400_DB_Maintenance']['150_DB_Maintenance'] = false;
	return;
}

// Load default header
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
$no_page_header = true; // We do not send the page header right here to prevent problems with GZIP-compression
require('pagestart.' . PHP_EXT);

@set_time_limit(0);
$mem_limit = check_mem_limit();
@ini_set('memory_limit', $mem_limit);

// Mighty Gorgon - ACP Privacy - BEGIN
$is_allowed = check_acp_module_access();
if (empty($is_allowed))
{
	message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
}
// Mighty Gorgon - ACP Privacy - END

define('DBMTNC_VERSION', '1.3.6');
// CONFIG_LEVEL = 0: configuration is disabled
// CONFIG_LEVEL = 1: only general configuration available
// CONFIG_LEVEL = 2: also configuration of rebuilding available
// CONFIG_LEVEL = 3: also configuration of current rebuilding available
define('CONFIG_LEVEL', 3); // Level of configuration available (see above)
define('HEAP_SIZE', 500); // Limit of Heap-Table for session data
require(IP_ROOT_PATH . 'includes/functions_dbmtnc.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_selects.' . PHP_EXT);

// Set up timer
$timer = getmicrotime();

// Get language file for this mod
setup_extra_lang(array('lang_dbmtnc'));

// Set up variables and constants
$function = request_var('function', '');
$mode_id = request_var('mode', '');

if (($mode_id == 'perform') && !isset($_POST['confirm']))
{
	if ($function != 'perform_rebuild')
	{
		$mode_id = '';
	}
}

// Check for parameters
reset ($config_data);
while (list(, $value) = each ($config_data))
{
	if (!isset($config[$value]))
	{
		message_die(GENERAL_MESSAGE, sprintf($lang['Incomplete_configuration'], $value));
	}
}

// Switch of GZIP-compression when necessary and send the page header
if (($mode_id == 'start') || ($mode_id == 'perform'))
{
	$config['gzip_compress'] = false;
	$config['gzip_compress_runtime'] = false;
}

// Don't send header when rebuilding the search index
if ($function != 'perform_rebuild')
{
	include(IP_ROOT_PATH . ADM . '/page_header_admin.' . PHP_EXT);
}

switch($mode_id)
{
	case 'start': // Show warning message if specified
		if ($function == '')
		{
			message_die(GENERAL_ERROR, $lang['no_function_specified']);
		}
		$warning_message_defined = false;

		for($i = 0; $i < sizeof($mtnc); $i++)
		{
			if (sizeof($mtnc[$i]) && ($mtnc[$i][0] == $function))
			{
				$warning_message = $mtnc[$i];
				$warning_message_defined = true;
			}
		}

		if (!$warning_message_defined)
		{
			message_die(GENERAL_ERROR, $lang['function_unknown']);
		}
		elseif ($warning_message[3] != '')
		{
			$s_hidden_fields = '<input type="hidden" name="mode" value="perform" />';
			$s_hidden_fields .= '<input type="hidden" name="function" value="' . $function . '" />';

			$template->set_filenames(array('body' => ADM_TPL . 'dbmtnc_confirm_body.tpl'));

			$template->assign_vars(array(
				'MESSAGE_TITLE' => $warning_message[1],
				'MESSAGE_TEXT' => $warning_message[3],

				'L_YES' => $lang['Yes'],
				'L_NO' => $lang['No'],

				'S_CONFIRM_ACTION' => append_sid('admin_db_maintenance.' . PHP_EXT),
				'S_HIDDEN_FIELDS' => $s_hidden_fields
				)
			);

			$template->pparse('body');
			break;
		}
		// We do not exit if no warning message is specified. In this case we will start directly with performing...

	case 'perform': // Execute the commands
		//
		// phpBB-Template System not used here to allow output information directly to the screen
		// Using the font tag will allow to get the gen-class applied :-)
		//
		$list_open = false;

		//
		// Increase maximum execution time, but don't complain about it if it isn't
		// allowed.
		@set_time_limit(120);
		// Switch off buffering
		ob_end_flush();
		switch($function)
		{
			case 'statistic': // Statistics
				$template->set_filenames(array('body' => ADM_TPL . 'dbmtnc_statistic_body.tpl'));

				if (empty($config['max_topics']) || empty($config['max_posts']) || empty($config['max_users']) || empty($config['last_user_id']))
				{
					board_stats();
				}
				$total_topics = $config['max_topics'];
				$total_posts = $config['max_posts'];
				$total_users = $config['max_users'];
				$newest_user = $cache->obtain_newest_user();

				$sql = "SELECT COUNT(user_id) AS total
					FROM " . USERS_TABLE . "
					WHERE user_active = 0
						AND user_id <> " . ANONYMOUS;
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't get statistic data!", __LINE__, __FILE__, $sql);
				}
				if ($row = $db->sql_fetchrow($result))
				{
					$total_deactivated_users = $row['total'];
				}
				else
				{
					throw_error("Couldn't update pending information!", __LINE__, __FILE__, $sql);
				}
				$db->sql_freeresult($result);
				$sql = "SELECT COUNT(user_id) AS total
					FROM " . USERS_TABLE . "
					WHERE user_level = " . MOD . "
						AND user_id <> " . ANONYMOUS;
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't get statistic data!", __LINE__, __FILE__, $sql);
				}
				if ($row = $db->sql_fetchrow($result))
				{
					$total_moderators = $row['total'];
				}
				else
				{
					throw_error("Couldn't update pending information!", __LINE__, __FILE__, $sql);
				}
				$db->sql_freeresult($result);
				$sql = "SELECT COUNT(user_id) AS total
					FROM " . USERS_TABLE . "
					WHERE user_level IN (" . JUNIOR_ADMIN . ", " . ADMIN . ")
						AND user_id <> " . ANONYMOUS;
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't get statistic data!", __LINE__, __FILE__, $sql);
				}
				if ($row = $db->sql_fetchrow($result))
				{
					$total_administrators = $row['total'];
				}
				else
				{
					throw_error("Couldn't update pending information!", __LINE__, __FILE__, $sql);
				}
				$db->sql_freeresult($result);
				$administrator_names = '';
				$sql = "SELECT user_id, username, user_active, user_color
					FROM " . USERS_TABLE . "
					WHERE user_level IN (" . JUNIOR_ADMIN . ", " . ADMIN . ")
						AND user_id <> " . ANONYMOUS . "
					ORDER BY username";
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't get statistic data!", __LINE__, __FILE__, $sql);
				}
				while ($row = $db->sql_fetchrow($result))
				{
					$administrator_names .= (($administrator_names == '') ? '' : ', ') . colorize_username($row['user_id'], $row['username'], $row['user_color'], $row['user_active']);
				}
				$db->sql_freeresult($result);
				$template->assign_vars(array(
					'NUMBER_OF_TOPICS' => $total_topics,
					'NUMBER_OF_POSTS' => $total_posts,
					'NUMBER_OF_USERS' => $total_users,
					'NUMBER_OF_DEACTIVATED_USERS' => $total_deactivated_users,
					'NUMBER_OF_MODERATORS' => $total_moderators,
					'NUMBER_OF_ADMINISTRATORS' => $total_administrators,
					'NAMES_OF_ADMINISTRATORS' => $administrator_names
					)
				);

				// Database statistic
				if (check_mysql_version())
				{
					$stat = get_table_statistic();
					$template->assign_block_vars('db_statistics', array());
					$template->assign_vars(array(
						'NUMBER_OF_DB_TABLES' => $stat['all']['count'],
						'NUMBER_OF_CORE_DB_TABLES' => $stat['core']['count'],
						'NUMBER_OF_ADVANCED_DB_TABLES' => $stat['advanced']['count'],
						'NUMBER_OF_DB_RECORDS' => $stat['all']['records'],
						'NUMBER_OF_CORE_DB_RECORDS' => $stat['core']['records'],
						'NUMBER_OF_ADVANCED_DB_RECORDS' => $stat['advanced']['records'],
						'SIZE_OF_DB' => convert_bytes($stat['all']['size']),
						'SIZE_OF_CORE_DB' => convert_bytes($stat['core']['size']),
						'SIZE_OF_ADVANCED_DB' => convert_bytes($stat['advanced']['size'])
						)
					);
				}

				// Version information
				$sql = "SELECT VERSION() AS mysql_version";
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't obtain MySQL Version", __LINE__, __FILE__, $sql);
				}
				$row = $db->sql_fetchrow($result);
				$mysql_version = $row['mysql_version'];
				$db->sql_freeresult($result);

				$template->assign_vars(array(
					'PHPBB_VERSION' => '2' . $config['version'],
					'MOD_VERSION' => DBMTNC_VERSION,
					'PHP_VERSION' => phpversion(),
					'MYSQL_VERSION' => $mysql_version,

					'L_DBMTNC_TITLE' => $lang['DB_Maintenance'],
					'L_DBMTNC_SUB_TITLE' => $lang['Statistic_title'],
					'L_DB_INFO' => $lang['Database_table_info'],
					'L_BOARD_STATISTIC' => $lang['Board_statistic'],
					'L_DB_STATISTIC' => $lang['Database_statistic'],
					'L_VERSION_INFO' => $lang['Version_info'],
					'L_NUMBER_POSTS' => $lang['Number_posts'], // from lang_admin.php
					'L_NUMBER_TOPICS' => $lang['Number_topics'], // from lang_admin.php
					'L_NUMBER_USERS' => $lang['Number_users'], // from lang_admin.php
					'L_NUMBER_DEACTIVATED_USERS' => $lang['Thereof_deactivated_users'],
					'L_NUMBER_MODERATORS' => $lang['Thereof_Moderators'],
					'L_NUMBER_ADMINISTRATORS' => $lang['Thereof_Administrators'],
					'L_NAME_ADMINISTRATORS' => $lang['Users_with_Admin_Privileges'],
					'L_NUMBER_DB_TABLES' => $lang['Number_tables'],
					'L_NUMBER_DB_RECORDS' => $lang['Number_records'],
					'L_DB_SIZE' => $lang['DB_size'],
					'L_THEREOF_PHPBB_CORE' => $lang['Thereof_phpbb_core'],
					'L_THEREOF_PHPBB_ADVANCED' => $lang['Thereof_phpbb_advanced'],
					'L_IP_VERSION' => $lang['Version_of_ip'],
					'L_BOARD_VERSION' => $lang['Version_of_board'],
					'L_MOD_VERSION' => $lang['Version_of_mod'],
					'L_PHP_VERSION' => $lang['Version_of_PHP'],
					'L_MYSQL_VERSION' => $lang['Version_of_MySQL']
					)
				);

				$template->pparse('body');
				break;

			case 'config': // Configuration
				if(isset($_POST['submit']))
				{
					$disallow_postcounter = (isset($_POST['disallow_postcounter'])) ? intval($_POST['disallow_postcounter']) : 0;
					$disallow_rebuild = (isset($_POST['disallow_rebuild'])) ? intval($_POST['disallow_rebuild']) : 0;
					$rebuildcfg_timelimit = (isset($_POST['rebuildcfg_timelimit']) && is_numeric($_POST['rebuildcfg_timelimit'])) ? intval($_POST['rebuildcfg_timelimit']) : 240;
					$rebuildcfg_timeoverwrite = (isset($_POST['rebuildcfg_timeoverwrite']) && is_numeric($_POST['rebuildcfg_timeoverwrite'])) ? intval($_POST['rebuildcfg_timeoverwrite']) : 0;
					$rebuildcfg_maxmemory = (isset($_POST['rebuildcfg_maxmemory']) && is_numeric($_POST['rebuildcfg_maxmemory'])) ? intval($_POST['rebuildcfg_maxmemory']) : 500;
					$rebuildcfg_minposts = (isset($_POST['rebuildcfg_minposts']) && is_numeric($_POST['rebuildcfg_minposts'])) ? intval($_POST['rebuildcfg_minposts']) : 3;
					$rebuildcfg_php3only = (isset($_POST['rebuildcfg_php3only'])) ? intval($_POST['rebuildcfg_php3only']) : 0;
					$rebuildcfg_php4pps = (isset($_POST['rebuildcfg_php4pps']) && is_numeric($_POST['rebuildcfg_php4pps'])) ? intval($_POST['rebuildcfg_php4pps']) : 8;
					$rebuildcfg_php3pps = (isset($_POST['rebuildcfg_php3pps']) && is_numeric($_POST['rebuildcfg_php3pps'])) ? intval($_POST['rebuildcfg_php3pps']) : 1;
					$rebuild_pos = (isset($_POST['rebuild_pos']) && is_numeric($_POST['rebuild_pos'])) ? intval($_POST['rebuild_pos']) : -1;
					$rebuild_end = (isset($_POST['rebuild_end']) && is_numeric($_POST['rebuild_end'])) ? intval($_POST['rebuild_end']) : 0;

					switch(CONFIG_LEVEL)
					{
						case 3: // Current search config
							if ($rebuild_end >= 0)
							{
								set_config('dbmtnc_rebuild_end', $rebuild_end, false);
							}
							if ($rebuild_pos >= -1)
							{
								set_config('dbmtnc_rebuild_pos', $rebuild_pos, false);
							}
						case 2: // Search config
							if ($rebuildcfg_php3pps > 0)
							{
								set_config('dbmtnc_rebuildcfg_php3pps', $rebuildcfg_php3pps, false);
							}
							if ($rebuildcfg_php4pps > 0)
							{
								set_config('dbmtnc_rebuildcfg_php4pps', $rebuildcfg_php4pps, false);
							}
							if ($rebuildcfg_php3only >= 0 && $rebuildcfg_php3only <= 1)
							{
								set_config('dbmtnc_rebuildcfg_php3only', $rebuildcfg_php3only, false);
							}
							if ($rebuildcfg_minposts > 0)
							{
								set_config('dbmtnc_rebuildcfg_minposts', $rebuildcfg_minposts, false);
							}
							if ($rebuildcfg_maxmemory >= 0)
							{
								set_config('dbmtnc_rebuildcfg_maxmemory', $rebuildcfg_maxmemory, false);
							}
							if ($rebuildcfg_timeoverwrite >= 0)
							{
								set_config('dbmtnc_rebuildcfg_timeoverwrite', $rebuildcfg_timeoverwrite, false);
							}
							if ($rebuildcfg_timelimit >= 0)
							{
								set_config('dbmtnc_rebuildcfg_timelimit', $rebuildcfg_timelimit, false);
							}
						case 1: // DBMTNC config
							if ($disallow_rebuild >= 0 && $disallow_rebuild <= 1)
							{
								set_config('dbmtnc_disallow_rebuild', $disallow_rebuild, false);
							}
							if ($disallow_postcounter >= 0 && $disallow_postcounter <= 1)
							{
								set_config('dbmtnc_disallow_postcounter', $disallow_postcounter, false);
							}
					}
					$message = $lang['Dbmtnc_config_updated'] . '<br /><br />' . sprintf($lang['Click_return_dbmtnc_config'], '<a href="' . append_sid('admin_db_maintenance.' . PHP_EXT . '?mode=start&function=config') . '">', '</a>');
					message_die(GENERAL_MESSAGE, $message);
				}

				$template->set_filenames(array('body' => ADM_TPL . 'dbmtnc_config_body.tpl'));

				$template->assign_vars(array(
					'S_CONFIG_ACTION' => append_sid('admin_db_maintenance.' . PHP_EXT . '?mode=start&function=config'),

					'L_DBMTNC_TITLE' => $lang['DB_Maintenance'],
					'L_DBMTNC_SUB_TITLE' => $lang['Config_title'],
					'L_CONFIG_INFO' => $lang['Config_info'],
					'L_GENERAL_CONFIG' => $lang['General_Config'],
					'L_REBUILD_CONFIG' => $lang['Rebuild_Config'],
					'L_CURRENTREBUILD_CONFIG' => $lang['Current_Rebuild_Config'],
					'L_REBUILD_SETTINGS_EXPLAIN' => $lang['Rebuild_Settings_Explain'],
					'L_CURRENTREBUILD_SETTINGS_EXPLAIN' => $lang['Current_Rebuild_Settings_Explain'],
					'L_DISALLOW_POSTCOUNTER' => $lang['Disallow_postcounter'],
					'L_DISALLOW_POSTCOUNTER_EXPLAIN' => $lang['Disallow_postcounter_Explain'],
					'L_DISALLOW_REBUILD' => $lang['Disallow_rebuild'],
					'L_DISALLOW_REBUILD_EXPLAIN' => $lang['Disallow_rebuild_Explain'],
					'L_REBUILDCFG_TIMELIMIT' => $lang['Rebuildcfg_Timelimit'],
					'L_REBUILDCFG_TIMELIMIT_EXPLAIN' => $lang['Rebuildcfg_Timelimit_Explain'],
					'L_REBUILDCFG_TIMEOVERWRITE' => $lang['Rebuildcfg_Timeoverwrite'],
					'L_REBUILDCFG_TIMEOVERWRITE_EXPLAIN' => $lang['Rebuildcfg_Timeoverwrite_Explain'],
					'L_REBUILDCFG_MAXMEMORY' => $lang['Rebuildcfg_Maxmemory'],
					'L_REBUILDCFG_MAXMEMORY_EXPLAIN' => $lang['Rebuildcfg_Maxmemory_Explain'],
					'L_REBUILDCFG_MINPOSTS' => $lang['Rebuildcfg_Minposts'],
					'L_REBUILDCFG_MINPOSTS_EXPLAIN' => $lang['Rebuildcfg_Minposts_Explain'],
					'L_REBUILDCFG_PHP3ONLY' => $lang['Rebuildcfg_PHP3Only'],
					'L_REBUILDCFG_PHP3ONLY_EXPLAIN' => $lang['Rebuildcfg_PHP3Only_Explain'],
					'L_REBUILDCFG_PHP4PPS' => $lang['Rebuildcfg_PHP4PPS'],
					'L_REBUILDCFG_PHP4PPS_EXPLAIN' => $lang['Rebuildcfg_PHP4PPS_Explain'],
					'L_REBUILDCFG_PHP3PPS' => $lang['Rebuildcfg_PHP3PPS'],
					'L_REBUILDCFG_PHP3PPS_EXPLAIN' => $lang['Rebuildcfg_PHP3PPS_Explain'],
					'L_REBUILD_POS' => $lang['Rebuild_Pos'],
					'L_REBUILD_POS_EXPLAIN' => $lang['Rebuild_Pos_Explain'],
					'L_REBUILD_END' => $lang['Rebuild_End'],
					'L_REBUILD_END_EXPLAIN' => $lang['Rebuild_End_Explain'],

					'L_YES' => $lang['Yes'],
					'L_NO' => $lang['No'],
					'L_SUBMIT' => $lang['Submit'],
					'L_RESET' => $lang['Reset'],

					'DISALLOW_POSTCOUNTER_YES' => ($config['dbmtnc_disallow_postcounter']) ? 'checked="checked"' : '',
					'DISALLOW_POSTCOUNTER_NO' => (!$config['dbmtnc_disallow_postcounter']) ? 'checked="checked"' : '',
					'DISALLOW_REBUILD_YES' => ($config['dbmtnc_disallow_rebuild']) ? 'checked="checked"' : '',
					'DISALLOW_REBUILD_NO' => (!$config['dbmtnc_disallow_rebuild']) ? 'checked="checked"' : '',
					'REBUILDCFG_TIMELIMIT' => intval($config['dbmtnc_rebuildcfg_timelimit']),
					'REBUILDCFG_MAXMEMORY' => intval($config['dbmtnc_rebuildcfg_maxmemory']),
					'REBUILDCFG_TIMEOVERWRITE' => intval($config['dbmtnc_rebuildcfg_timeoverwrite']),
					'REBUILDCFG_MINPOSTS' => intval($config['dbmtnc_rebuildcfg_minposts']),
					'REBUILDCFG_PHP3ONLY_YES' => ($config['dbmtnc_rebuildcfg_php3only']) ? 'checked="checked"' : '',
					'REBUILDCFG_PHP3ONLY_NO' => (!$config['dbmtnc_rebuildcfg_php3only']) ? 'checked="checked"' : '',
					'REBUILDCFG_PHP4PPS' => intval($config['dbmtnc_rebuildcfg_php4pps']),
					'REBUILDCFG_PHP3PPS' => intval($config['dbmtnc_rebuildcfg_php3pps']),
					'REBUILD_POS' => intval($config['dbmtnc_rebuild_pos']),
					'REBUILD_END' => intval($config['dbmtnc_rebuild_end'])
					)
				);

				// Display of vonfiguration dependend on settings
				if (CONFIG_LEVEL >= 2)
				{
					$template->assign_block_vars('rebuild_settings', array());
				}
				if (CONFIG_LEVEL >= 3)
				{
					$template->assign_block_vars('currentrebuild_settings', array());
				}

				$template->pparse('body');
				break;

			case 'check_user': // Check user tables
				echo('<h1>' . $lang['Checking_user_tables'] . '</h1>' . "\n");
				lock_db();

				// Check for missing anonymous user
				echo('<p class="gen"><b>' . $lang['Checking_missing_anonymous'] . '</b></p>' . "\n");
				$sql = "SELECT user_id FROM " . USERS_TABLE . "
					WHERE user_id = " . ANONYMOUS;
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't get user information!", __LINE__, __FILE__, $sql);
				}
				if ($row = $db->sql_fetchrow($result)) // anonymous user exists
				{
					echo($lang['Nothing_to_do']);
				}
				else // anonymous user does not exist
				{
					// Recreate entry
					$sql = "INSERT INTO " . USERS_TABLE . " (user_id, username, user_level, user_regdate, user_password, user_email, user_icq, user_website, user_occ, user_from, user_interests, user_sig, user_allow_viewemail, user_style, user_aim, user_yim, user_msnm, user_posts, user_attachsig, user_allowsmile, user_allowhtml, user_allowbbcode, user_allow_pm, user_notify_pm, user_allow_viewonline, user_rank, user_avatar, user_lang, user_timezone, user_dateformat, user_actkey, user_newpasswd, user_notify, user_active)
						VALUES (" . ANONYMOUS . ", 'Anonymous', 0, 0, '', '', '', '', '', '', '', '', 0, NULL, '', '', '', 0, 0, 1, 0, 1, 0, 1, 1, NULL, '', '', 0, '', '', '', 0, 0)";
					$db->sql_return_on_error(true);
					$result = $db->sql_query($sql);
					$db->sql_return_on_error(false);
					if (!$result)
					{
						throw_error("Couldn't add user data!", __LINE__, __FILE__, $sql);
					}
					echo('<p class="gen">' . sprintf($lang['Anonymous_recreated'], $affected_rows) . '</p>' . "\n");
				}

				// Update incorrect pending information: either a single user group with pending state or a group with pending state NULL
				echo('<p class="gen"><b>' . $lang['Checking_incorrect_pending_information'] . '</b></p>' . "\n");
				$db_updated = false;
				// Update the cases where user_pending is null (there were some cases reported, so we just do it)
				$sql = "UPDATE " . USER_GROUP_TABLE . "
					SET user_pending = 1
					WHERE user_pending IS NULL";
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't update pending information!", __LINE__, __FILE__, $sql);
				}
				$affected_rows = $db->sql_affectedrows();
				if ($affected_rows == 1)
				{
					$db_updated = true;
					echo('<p class="gen">' . sprintf($lang['Updating_invalid_pendig_user'], $affected_rows) . '</p>' . "\n");
				}
				elseif ($affected_rows > 1)
				{
					$db_updated = true;
					echo('<p class="gen">' . sprintf($lang['Updating_invalid_pendig_users'], $affected_rows) . '</p>' . "\n");
				}
				// Check for pending single user groups
				if (check_mysql_version())
				{
					$sql = "SELECT g.group_id
						FROM " . USER_GROUP_TABLE . " ug
							INNER JOIN " . GROUPS_TABLE . " g ON ug.group_id = g.group_id
						WHERE ug.user_pending = 1
							AND g.group_single_user = 1";
				}
				else
				{
					$sql = "SELECT g.group_id
						FROM " . USER_GROUP_TABLE . " ug, " .
							GROUPS_TABLE . " g
						WHERE ug.group_id = g.group_id
							AND ug.user_pending = 1
							AND g.group_single_user = 1";
				}
				$result_array = array();
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't get user and group data!", __LINE__, __FILE__, $sql);
				}
				while ($row = $db->sql_fetchrow($result))
				{
					$result_array[] = $row['group_id'];
				}
				$db->sql_freeresult($result);
				if (sizeof($result_array))
				{
					$db_updated = true;
					$record_list = implode(',', $result_array);
					echo('<p class="gen">' . $lang['Updating_pending_information'] . ": $record_list</p>\n");
					$sql = "UPDATE " . USER_GROUP_TABLE . "
						SET user_pending = 0
						WHERE group_id IN ($record_list)";
					$db->sql_return_on_error(true);
					$result = $db->sql_query($sql);
					$db->sql_return_on_error(false);
					if (!$result)
					{
						throw_error("Couldn't update pending information!", __LINE__, __FILE__, $sql);
					}
				}
				if (!$db_updated)
				{
					echo($lang['Nothing_to_do']);
				}

				// Checking for users without a single user group
				echo('<p class="gen"><b>' . $lang['Checking_missing_user_groups'] . '</b></p>' . "\n");
				$db_updated = false;
				$sql = "SELECT u.user_id, Sum(g.group_single_user) AS group_count
					FROM " . USERS_TABLE . " u
						LEFT JOIN " . USER_GROUP_TABLE . " ug ON u.user_id = ug.user_id
						LEFT JOIN " . GROUPS_TABLE . " g ON ug.group_id = g.group_id
					GROUP BY u.user_id
					HAVING group_count <> 1 OR IsNull(group_count)";
				$missig_groups = array();
				$multiple_groups = array();
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't get user and group data!", __LINE__, __FILE__, $sql);
				}
				while ($row = $db->sql_fetchrow($result))
				{
					if ($row['group_count'] != 0)
					{
						$multiple_groups[] = $row['user_id'];
					}
					$missing_groups[] = $row['user_id'];
				}
				$db->sql_freeresult($result);
				// Check for multiple records
				if (sizeof($multiple_groups))
				{
					$db_updated = true;
					$record_list = implode(',', $multiple_groups);
					echo('<p class="gen">' . $lang['Found_multiple_SUG'] . ":</p>\n");
					echo("<div class=\"post-text\"><ul>\n");
					$list_open = true;
					echo("<li>" . $lang['Resolving_user_id'] . ": $record_list</li>\n");
					if (check_mysql_version())
					{
						$sql = "SELECT g.group_id
							FROM " . USERS_TABLE . " u
								INNER JOIN " . USER_GROUP_TABLE . " ug ON u.user_id = ug.user_id
								INNER JOIN " . GROUPS_TABLE . " g ON ug.group_id = g.group_id
							WHERE u.user_id IN ($record_list) AND g.group_single_user = 1";
					}
					else
					{
						$sql = "SELECT g.group_id
							FROM " . USERS_TABLE . " u, " .
								USER_GROUP_TABLE . " ug, " .
								GROUPS_TABLE . " g
							WHERE u.user_id = ug.user_id
								AND ug.group_id = g.group_id
								AND u.user_id IN ($record_list)
								AND g.group_single_user = 1";
					}
					$result_array = array();
					$db->sql_return_on_error(true);
					$result = $db->sql_query($sql);
					$db->sql_return_on_error(false);
					if (!$result)
					{
						throw_error("Couldn't get group data!", __LINE__, __FILE__, $sql);
					}
					while ($row = $db->sql_fetchrow($result))
					{
						$result_array[] = $row['group_id'];
					}
					$db->sql_freeresult($result);
					$record_list = implode(',', $result_array);
					echo("<li>" . $lang['Removing_groups'] . ": $record_list</li>\n");
					$sql = "DELETE FROM " . GROUPS_TABLE . "
						WHERE group_id IN ($record_list)";
					$db->sql_return_on_error(true);
					$result = $db->sql_query($sql);
					$db->sql_return_on_error(false);
					if (!$result)
					{
						throw_error("Couldn't delete groups!", __LINE__, __FILE__, $sql);
					}
					echo("<li>" . $lang['Removing_user_groups'] . ": $record_list</li>\n");
					$sql = "DELETE FROM " . USER_GROUP_TABLE . "
						WHERE group_id IN ($record_list)";
					$db->sql_return_on_error(true);
					$result = $db->sql_query($sql);
					$db->sql_return_on_error(false);
					if (!$result)
					{
						throw_error("Couldn't delete groups!", __LINE__, __FILE__, $sql);
					}
					echo("</ul></div>\n");
					$list_open = false;
				}
				// Create single user groups
				if (sizeof($missing_groups))
				{
					$db_updated = true;
					$record_list = implode(',', $missing_groups);
					echo('<p class="gen">' . $lang['Recreating_SUG'] . ": $record_list</p>\n");
					for($i = 0; $i < sizeof($missing_groups); $i++)
					{
						$group_name = ($missing_groups[$i] == ANONYMOUS) ? 'Anonymous' : '';
						$sql = "INSERT INTO " . GROUPS_TABLE . " (group_type, group_name, group_description, group_moderator, group_single_user)
							VALUES (1, '$group_name', 'Personal User', 0, 1)";
						$db->sql_return_on_error(true);
						$result = $db->sql_query($sql);
						$db->sql_return_on_error(false);
						if (!$result)
						{
							throw_error("Couldn't add group data!", __LINE__, __FILE__, $sql);
						}
						$group_id = $db->sql_nextid();
						$sql = "INSERT INTO " . USER_GROUP_TABLE . " (group_id, user_id, user_pending)
							VALUES ($group_id, " . $missing_groups[$i] . ", 0)";
						$db->sql_return_on_error(true);
						$result = $db->sql_query($sql);
						$db->sql_return_on_error(false);
						if (!$result)
						{
							throw_error("Couldn't add user - group connection!", __LINE__, __FILE__, $sql);
						}
					}
				}
				if (!$db_updated)
				{
					echo($lang['Nothing_to_do']);
				}

				// Check for group moderators who do not exist
				echo('<p class="gen"><b>' . $lang['Checking_for_invalid_moderators'] . '</b></p>' . "\n");
				$sql = "SELECT g.group_id, g.group_name
					FROM " . GROUPS_TABLE . " g
						LEFT JOIN " . USERS_TABLE . " u ON g.group_moderator = u.user_id
					WHERE g.group_single_user = 0
						AND (u.user_id IS NULL OR u.user_id = " . ANONYMOUS . ")";
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't get group data!", __LINE__, __FILE__, $sql);
				}
				while ($row = $db->sql_fetchrow($result))
				{
					if (!$list_open)
					{
						echo('<p class="gen"><b>' . $lang['Updating_Moderator'] . ":</b></p>\n");
						echo("<div class=\"post-text\"><ul>\n");
						$list_open = true;
					}
					echo("<li>" . htmlspecialchars($row['group_name']) . " (" . $row['group_id'] . ")</li>\n");
					$sql2 = "UPDATE " . GROUPS_TABLE . "
						SET group_moderator = " . $user->data['user_id'] . "
						WHERE group_id = " . $row['group_id'];
					$db->sql_return_on_error(true);
					$result2 = $db->sql_query($sql2);
					$db->sql_return_on_error(false);
					if (!$result2)
					{
						throw_error("Couldn't update group data!", __LINE__, __FILE__, $sql2);
					}
				}
				$db->sql_freeresult($result);
				if ($list_open)
				{
					echo("</ul></div>\n");
					$list_open = false;
				}
				else
				{
					echo($lang['Nothing_to_do']);
				}

				// Check for group moderators who are not member of the group they moderate
				echo('<p class="gen"><b>' . $lang['Checking_moderator_membership'] . '</b></p>' . "\n");
				$sql = "SELECT group_id, group_name, group_moderator
					FROM " . GROUPS_TABLE . " g
					WHERE g.group_single_user = 0";
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't get group data!", __LINE__, __FILE__, $sql);
				}
				while ($row = $db->sql_fetchrow($result))
				{
					$sql2 = "SELECT user_pending
						FROM " . USER_GROUP_TABLE . "
						WHERE group_id = " . $row['group_id'] . "
							AND user_id = " . $row['group_moderator'];
					$db->sql_return_on_error(true);
					$result2 = $db->sql_query($sql2);
					$db->sql_return_on_error(false);
					if (!$result2)
					{
						throw_error("Couldn't get group data!", __LINE__, __FILE__, $sql2);
					}
					if (!($row2 = $db->sql_fetchrow($result2))) // No record found
					{
						if (!$list_open)
						{
							echo('<p class="gen"><b>' . $lang['Updating_mod_membership'] . ":</b></p>\n");
							echo("<div class=\"post-text\"><ul>\n");
							$list_open = true;
						}
						echo("<li>" . htmlspecialchars($row['group_name']) . " (" . $row['group_id'] . ") - " . $lang['Moderator_added'] . "</li>\n");
						$sql3 = "INSERT INTO " . USER_GROUP_TABLE . " (group_id, user_id, user_pending)
							VALUES (" . $row['group_id'] . ", " . $row['group_moderator'] . ", 0)";
						$db->sql_return_on_error(true);
						$result3 = $db->sql_query($sql3);
						$db->sql_return_on_error(false);
						if (!$result3)
						{
							throw_error("Couldn't insert data in user-group-table!", __LINE__, __FILE__, $sql3);
						}
					}
					elseif ($row2['user_pending'] == 1) // Record found but moderator is pending
					{
						if (!$list_open)
						{
							echo('<p class="gen"><b>' . $lang['Updating_mod_membership'] . ":</b></p>\n");
							echo("<div class=\"post-text\"><ul>\n");
							$list_open = true;
						}
						echo("<li>" . htmlspecialchars($row['group_name']) . " (" . $row['group_id'] . ") - " . $lang['Moderator_changed_pending'] . "</li>\n");
						$sql3 = "UPDATE " . USER_GROUP_TABLE . "
							SET user_pending = 0
							WHERE group_id = " . $row['group_id'] . "
								AND user_id = " . $row['group_moderator'];
						$db->sql_return_on_error(true);
						$result3 = $db->sql_query($sql3);
						$db->sql_return_on_error(false);
						if (!$result3)
						{
							throw_error("Couldn't update data in user-group-table!", __LINE__, __FILE__, $sql3);
						}
					}
					$db->sql_freeresult($result2);
				}
				$db->sql_freeresult($result);
				if ($list_open)
				{
					echo("</ul></div>\n");
					$list_open = false;
				}
				else
				{
					echo($lang['Nothing_to_do']);
				}

				// Remove user-group data without a valid user
				echo('<p class="gen"><b>' . $lang['Remove_invalid_user_data'] . '</b></p>' . "\n");
				$sql = "SELECT ug.user_id
					FROM " . USER_GROUP_TABLE . " ug
						LEFT JOIN " . USERS_TABLE . " u ON ug.user_id = u.user_id
					WHERE u.user_id IS NULL
					GROUP BY ug.user_id";
				$result_array = array();
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't get user and group data!", __LINE__, __FILE__, $sql);
				}
				while ($row = $db->sql_fetchrow($result))
				{
					$result_array[] = $row['user_id'];
				}
				$db->sql_freeresult($result);
				if (sizeof($result_array))
				{
					$record_list = implode(',', $result_array);
					$sql = "DELETE FROM " . USER_GROUP_TABLE . "
						WHERE user_id IN ($record_list)";
					$db->sql_return_on_error(true);
					$result = $db->sql_query($sql);
					$db->sql_return_on_error(false);
					if (!$result)
					{
						throw_error("Couldn't update user-group data!", __LINE__, __FILE__, $sql);
					}
					$affected_rows = $db->sql_affectedrows();
					if ($affected_rows == 1)
					{
						echo('<p class="gen">' . sprintf($lang['Affected_row'], $affected_rows) . '</p>' . "\n");
					}
					elseif ($affected_rows > 1)
					{
						echo('<p class="gen">' . sprintf($lang['Affected_rows'], $affected_rows) . '</p>' . "\n");
					}
				}
				else
				{
					echo($lang['Nothing_to_do']);
				}

				// Remove groups without any members
				echo('<p class="gen"><b>' . $lang['Remove_empty_groups'] . '</b></p>' . "\n");
				// Since we alread added the moderators to the groups this will only include rests of single user groups. So we don't need to display more information
				$sql = "SELECT g.group_id
					FROM " . GROUPS_TABLE . " g
						LEFT JOIN " . USER_GROUP_TABLE . " ug ON g.group_id = ug.group_id
					WHERE ug.group_id IS NULL";
				$result_array = array();
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't get user and group data!", __LINE__, __FILE__, $sql);
				}
				while ($row = $db->sql_fetchrow($result))
				{
					$result_array[] = $row['group_id'];
				}
				$db->sql_freeresult($result);
				if (sizeof($result_array))
				{
					$record_list = implode(',', $result_array);
					$sql = "DELETE FROM " . GROUPS_TABLE . "
						WHERE group_id IN ($record_list)";
					$db->sql_return_on_error(true);
					$result = $db->sql_query($sql);
					$db->sql_return_on_error(false);
					if (!$result)
					{
						throw_error("Couldn't update group data!", __LINE__, __FILE__, $sql);
					}
					$affected_rows = $db->sql_affectedrows();
					if ($affected_rows == 1)
					{
						echo('<p class="gen">' . sprintf($lang['Affected_row'], $affected_rows) . '</p>' . "\n");
					}
					elseif ($affected_rows > 1)
					{
						echo('<p class="gen">' . sprintf($lang['Affected_rows'], $affected_rows) . '</p>' . "\n");
					}
				}
				else
				{
					echo($lang['Nothing_to_do']);
				}

				// Remove user-group data without a valid group
				echo('<p class="gen"><b>' . $lang['Remove_invalid_group_data'] . '</b></p>' . "\n");
				$sql = "SELECT ug.group_id
					FROM " . USER_GROUP_TABLE . " ug
						LEFT JOIN " . GROUPS_TABLE . " g ON ug.group_id = g.group_id
					WHERE g.group_id IS NULL
					GROUP BY ug.group_id";
				$result_array = array();
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't get user and group data!", __LINE__, __FILE__, $sql);
				}
				while ($row = $db->sql_fetchrow($result))
				{
					$result_array[] = $row['group_id'];
				}
				$db->sql_freeresult($result);
				if (sizeof($result_array))
				{
					$record_list = implode(',', $result_array);
					$sql = "DELETE FROM " . USER_GROUP_TABLE . "
						WHERE group_id IN ($record_list)";
					$db->sql_return_on_error(true);
					$result = $db->sql_query($sql);
					$db->sql_return_on_error(false);
					if (!$result)
					{
						throw_error("Couldn't update user-group data!", __LINE__, __FILE__, $sql);
					}
					$affected_rows = $db->sql_affectedrows();
					if ($affected_rows == 1)
					{
						echo('<p class="gen">' . sprintf($lang['Affected_row'], $affected_rows) . '</p>' . "\n");
					}
					elseif ($affected_rows > 1)
					{
						echo('<p class="gen">' . sprintf($lang['Affected_rows'], $affected_rows) . '</p>' . "\n");
					}
				}
				else
				{
					echo($lang['Nothing_to_do']);
				}

				// Checking for invalid ranks
				echo('<p class="gen"><b>' . $lang['Checking_ranks'] . '</b></p>' . "\n");
				$sql = "SELECT u.user_id, u.username
					FROM " . USERS_TABLE . " u
						LEFT JOIN " . RANKS_TABLE . " r ON u.user_rank = r.rank_id
					WHERE r.rank_id IS NULL AND u.user_rank <> 0";
				$result_array = array();
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't get user and rank data!", __LINE__, __FILE__, $sql);
				}
				while ($row = $db->sql_fetchrow($result))
				{
					if (!$list_open)
					{
						echo('<p class="gen">' . $lang['Invalid_ranks_found'] . ":</p>\n");
						echo("<div class=\"post-text\"><ul>\n");
						$list_open = true;
					}
					echo("<li>" . htmlspecialchars($row['username']) . " (" . $row['user_id'] . ")</li>\n");
					$result_array[] = $row['user_id'];
				}
				$db->sql_freeresult($result);
				if ($list_open)
				{
					echo("</ul></div>\n");
					$list_open = false;
				}
				if (sizeof($result_array))
				{
					echo('<p class="gen">' . $lang['Removing_invalid_ranks'] . '</p>' . "\n");
					$record_list = implode(',', $result_array);
					$sql = "UPDATE " . USERS_TABLE . "
						SET user_rank = 0
						WHERE user_id IN ($record_list)";
					$db->sql_return_on_error(true);
					$result = $db->sql_query($sql);
					$db->sql_return_on_error(false);
					if (!$result)
					{
						throw_error("Couldn't update user data!", __LINE__, __FILE__, $sql);
					}
				}
				else
				{
					echo($lang['Nothing_to_do']);
				}

				// Checking for invalid themes
				echo('<p class="gen"><b>' . $lang['Checking_themes'] . '</b></p>' . "\n");
				$sql = "SELECT u.user_style
					FROM " . USERS_TABLE . " u
						LEFT JOIN " . THEMES_TABLE . " t ON u.user_style = t.themes_id
					WHERE t.themes_id IS NULL AND u.user_id <> " . ANONYMOUS . "
					GROUP BY u.user_style";
				$result_array = array();
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't get user and theme data!", __LINE__, __FILE__, $sql);
				}
				while ($row = $db->sql_fetchrow($result))
				{
					if ($row['user_style'] == '')
					{
						// At least one style is NULL, so change these records
						echo('<p class="gen">' . $lang['Updating_users_without_style'] . '</p>' . "\n");
						$sql2 = "UPDATE " . USERS_TABLE . "
							SET user_style = 0
							WHERE user_style IS NULL AND user_id <> " . ANONYMOUS;
						$db->sql_return_on_error(true);
						$result2 = $db->sql_query($sql2);
						$db->sql_return_on_error(false);
						if (!$result2)
						{
							throw_error("Couldn't update themes data!", __LINE__, __FILE__, $sql2);
						}
						$result_array[] = 0;
					}
					else
					{
						$result_array[] = $row['user_style'];
					}
				}
				$db->sql_freeresult($result);
				if (sizeof($result_array))
				{
					$new_style = 0;
					$record_list = implode(',', $result_array);
					$sql = "SELECT themes_id
						FROM " . THEMES_TABLE . "
						WHERE themes_id = " . $config['default_style'];
					$db->sql_return_on_error(true);
					$result = $db->sql_query($sql);
					$db->sql_return_on_error(false);
					if (!$result)
					{
						throw_error("Couldn't get themes data!", __LINE__, __FILE__, $sql);
					}
					if ($row = $db->sql_fetchrow($result))
					{
						$new_style = $row['themes_id'];
					}
					else // the default template is not available
					{
						echo('<p class="gen">' . $lang['Default_theme_invalid'] . '</p>' . "\n");
						$db->sql_freeresult($result);
						$sql = "SELECT themes_id
							FROM " . THEMES_TABLE . "
							WHERE themes_id = " . $user->data['user_style'];
							echo($sql);
						$db->sql_return_on_error(true);
						$result = $db->sql_query($sql);
						$db->sql_return_on_error(false);
						if (!$result)
						{
							throw_error("Couldn't get themes data!", __LINE__, __FILE__, $sql);
						}
						if ($row = $db->sql_fetchrow($result))
						{
							$new_style = $row['themes_id'];
						}
						else // We never should get to this point. If both the board and the user style is invalid, I don't know how someone should get to this point
						{
							throw_error("Fatal error!");
						}
					}
					$db->sql_freeresult($result);
					echo('<p class="gen">' . sprintf($lang['Updating_themes'], $new_style) . "...</p>\n");
					$sql = "UPDATE " . USERS_TABLE . "
						SET user_style = $new_style
						WHERE user_style IN ($record_list)";
					$db->sql_return_on_error(true);
					$result = $db->sql_query($sql);
					$db->sql_return_on_error(false);
					if (!$result)
					{
						throw_error("Couldn't update themes data!", __LINE__, __FILE__, $sql);
					}
				}
				else
				{
					echo($lang['Nothing_to_do']);
				}

				// Checking for invalid languages
				echo('<p class="gen"><b>' . $lang['Checking_languages'] . '</b></p>' . "\n");
				$sql = "SELECT user_lang
					FROM " . USERS_TABLE . "
					WHERE user_id <> " . ANONYMOUS . "
					GROUP BY user_lang";
				$result_array = array();
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't get user and theme data!", __LINE__, __FILE__, $sql);
				}
				while ($row = $db->sql_fetchrow($result))
				{
					if (!file_exists(@phpbb_realpath(IP_ROOT_PATH . 'language/lang_' . $row['user_lang'] . '/lang_main.' . PHP_EXT)))
					{
						$result_array[] = $row['user_lang'];
					}
				}
				$db->sql_freeresult($result);
				if (sizeof($result_array))
				{
					// Getting default language as long as the original one was changed in functions.php
					$sql = "SELECT config_value
						FROM " . CONFIG_TABLE . "
						WHERE config_name = 'default_lang'";
					$db->sql_return_on_error(true);
					$result = $db->sql_query($sql);
					$db->sql_return_on_error(false);
					if (!$result)
					{
						throw_error("Couldn't get language data!", __LINE__, __FILE__, $sql);
					}
					if ($row = $db->sql_fetchrow($result))
					{
						$boad_language = $row['config_value'];
					}
					else
					{
						throw_error("Couldn't get config data! Please check your configuration table.");
					}
					$db->sql_freeresult($result);

					// Getting default language
					if (file_exists(@phpbb_realpath(IP_ROOT_PATH . 'language/lang_' . $boad_language . '/lang_main.' . PHP_EXT)))
					{
						$default_lang = $boad_language;
					}
					elseif (file_exists(@phpbb_realpath(IP_ROOT_PATH . 'language/lang_' . $user->data['user_lang'] . '/lang_main.' . PHP_EXT)))
					{
						echo('<p class="gen">' . $lang['Default_language_invalid'] . '</p>' . "\n");
						$default_lang = $user->data['user_lang'];
					}
					elseif (file_exists(@phpbb_realpath(IP_ROOT_PATH . 'language/lang_english/lang_main.' . PHP_EXT)))
					{
						echo('<p class="gen">' . $lang['Default_language_invalid'] . '</p>' . "\n");
						$default_lang = 'english';
					}
					else
					{
						echo('<p class="gen">' . $lang['English_language_invalid'] . '</p>' . "\n");
						$default_lang = 'english';
					}

					echo('<p class="gen">' . $lang['Invalid_languages_found'] . ":</p>\n");
					echo("<div class=\"post-text\"><ul>\n");
					$list_open = true;

					for($i = 0; $i < sizeof($result_array); $i++)
					{
						echo("<li>" . sprintf($lang['Changing_language'], $result_array[$i], $default_lang) . "</li>\n");
						$sql = "UPDATE " . USERS_TABLE . "
							SET user_lang = '$default_lang'
							WHERE user_lang = '" . $result_array[$i] . "'
								AND user_id <> " . ANONYMOUS;
						$db->sql_return_on_error(true);
						$result = $db->sql_query($sql);
						$db->sql_return_on_error(false);
						if (!$result)
						{
							throw_error("Couldn't update language language data!", __LINE__, __FILE__, $sql);
						}
					}

					echo("</ul></div>\n");
					$list_open = false;
				}
				else
				{
					echo($lang['Nothing_to_do']);
				}

				// Remove ban data without a valid user
				echo('<p class="gen"><b>' . $lang['Remove_invalid_ban_data'] . '</b></p>' . "\n");
				$sql = "SELECT b.ban_userid
					FROM " . BANLIST_TABLE . " b
						LEFT JOIN " . USERS_TABLE . " u ON b.ban_userid = u.user_id
					WHERE u.user_id IS NULL
						AND b.ban_userid > 0
						AND b.ban_userid IS NOT NULL";
				$result_array = array();
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't get banlist and user data!", __LINE__, __FILE__, $sql);
				}
				while ($row = $db->sql_fetchrow($result))
				{
					$result_array[] = $row['ban_userid'];
				}
				$db->sql_freeresult($result);
				if (sizeof($result_array))
				{
					$record_list = implode(',', $result_array);
					$sql = "DELETE FROM " . BANLIST_TABLE . "
						WHERE ban_userid IN ($record_list)";
					$db->sql_return_on_error(true);
					$result = $db->sql_query($sql);
					$db->sql_return_on_error(false);
					if (!$result)
					{
						throw_error("Couldn't update ban data!", __LINE__, __FILE__, $sql);
					}
					$affected_rows = $db->sql_affectedrows();
					if ($affected_rows == 1)
					{
						echo('<p class="gen">' . sprintf($lang['Affected_row'], $affected_rows) . '</p>' . "\n");
					}
					elseif ($affected_rows > 1)
					{
						echo('<p class="gen">' . sprintf($lang['Affected_rows'], $affected_rows) . '</p>' . "\n");
					}
				}
				else
				{
					echo($lang['Nothing_to_do']);
				}

				// Remove session key data without valid user
				if ($phpbb_version[0] == 0 && $phpbb_version[1] >= 18)
				{
					echo('<p class="gen"><b>' . $lang['Remove_invalid_session_keys'] . '</b></p>' . "\n");
					$sql = "SELECT k.key_id
						FROM " . SESSIONS_KEYS_TABLE . " k
							LEFT JOIN " . USERS_TABLE . " u ON k.user_id = u.user_id
						WHERE u.user_id IS NULL
							OR k.user_id = " . ANONYMOUS . "
							OR k.last_login > " . time();
					$result_array = array();
					$db->sql_return_on_error(true);
					$result = $db->sql_query($sql);
					$db->sql_return_on_error(false);
					if (!$result)
					{
						throw_error("Couldn't get session key data!", __LINE__, __FILE__, $sql);
					}
					while ($row = $db->sql_fetchrow($result))
					{
						$result_array[] = $row['key_id'];
					}
					$db->sql_freeresult($result);
					if (sizeof($result_array))
					{
						$record_list = '\'' . implode('\',\'', $result_array) . '\'';
						$sql = "DELETE FROM " . SESSIONS_KEYS_TABLE . "
							WHERE key_id IN ($record_list)";
						$db->sql_return_on_error(true);
						$result = $db->sql_query($sql);
						$db->sql_return_on_error(false);
						if (!$result)
						{
							throw_error("Couldn't update session key data!", __LINE__, __FILE__, $sql);
						}
						$affected_rows = $db->sql_affectedrows();
						if ($affected_rows == 1)
						{
							echo('<p class="gen">' . sprintf($lang['Affected_row'], $affected_rows) . '</p>' . "\n");
						}
						elseif ($affected_rows > 1)
						{
							echo('<p class="gen">' . sprintf($lang['Affected_rows'], $affected_rows) . '</p>' . "\n");
						}
					}
					else
					{
						echo($lang['Nothing_to_do']);
					}
				}

				lock_db(true);
				break;

			case 'check_post': // Checks post data
				echo('<h1>' . $lang['Checking_post_tables'] . '</h1>' . "\n");
				$db_state = lock_db();

				// Set a variable to check whether we should update the post data
				$update_post_data = false;

				// Check posts for invaild posters
				echo('<p class="gen"><b>' . $lang['Checking_invalid_posters'] . '</b></p>' . "\n");
				$sql = "SELECT p.post_id
					FROM " . POSTS_TABLE . " p
						LEFT JOIN " . USERS_TABLE . " u ON p.poster_id = u.user_id
					WHERE u.user_id IS NULL";
				$result_array = array();
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't get post and user data!", __LINE__, __FILE__, $sql);
				}
				while ($row = $db->sql_fetchrow($result))
				{
					$result_array[] = $row['post_id'];
				}
				$db->sql_freeresult($result);
				if (sizeof($result_array))
				{
					$record_list = implode(',', $result_array);
					echo('<p class="gen">' . $lang['Invalid_poster_found'] . ": $record_list</p>\n");
					echo('<p class="gen">' . $lang['Updating_posts'] . '</p>' . "\n");
					$sql = "UPDATE " . POSTS_TABLE . "
						SET poster_id = " . DELETED . ",
							post_username = ''
						WHERE post_id IN ($record_list)";
					$db->sql_return_on_error(true);
					$result = $db->sql_query($sql);
					$db->sql_return_on_error(false);
					if (!$result)
					{
						throw_error("Couldn't update post information!", __LINE__, __FILE__, $sql);
					}
				}
				else
				{
					echo($lang['Nothing_to_do']);
				}

				// Check topics for invaild posters
				echo('<p class="gen"><b>' . $lang['Checking_invalid_topic_posters'] . '</b></p>' . "\n");
				$sql = "SELECT t.topic_id, t.topic_poster
					FROM " . TOPICS_TABLE . " t
						LEFT JOIN " . USERS_TABLE . " u ON t.topic_poster = u.user_id
					WHERE u.user_id IS NULL";
				$result_array = array();
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't get topic and user data!", __LINE__, __FILE__, $sql);
				}
				while ($row = $db->sql_fetchrow($result))
				{
					if (!$list_open)
					{
						echo('<p class="gen">' . $lang['Invalid_topic_poster_found'] . ":</p>\n");
						echo("<div class=\"post-text\"><ul>\n");
						$list_open = true;
					}
					$poster_id = get_poster($row['topic_id']);
					echo("<li>" . sprintf($lang['Updating_topic'], $row['topic_id'], $row['topic_poster'], $poster_id) . "</li>\n");
					$sql2 = "UPDATE " . TOPICS_TABLE . "
						SET topic_poster = $poster_id
						WHERE topic_id = " . $row['topic_id'];
					$db->sql_return_on_error(true);
					$result2 = $db->sql_query($sql2);
					$db->sql_return_on_error(false);
					if (!$result2)
					{
						throw_error("Couldn't update topic information!", __LINE__, __FILE__, $sql2);
					}
				}
				$db->sql_freeresult($result);
				if ($list_open)
				{
					echo("</ul></div>\n");
					$list_open = false;
				}
				else
				{
					echo($lang['Nothing_to_do']);
				}

				// Check for topics without a post
				echo('<p class="gen"><b>' . $lang['Checking_topics_wo_post'] . '</b></p>' . "\n");
				$sql = "SELECT t.topic_id, t.topic_title
					FROM " . TOPICS_TABLE . " t
						LEFT JOIN " . POSTS_TABLE . " p ON t.topic_id = p.topic_id
					WHERE p.topic_id IS NULL
						AND t.topic_status <> " . TOPIC_MOVED;
				$result_array = array();
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't get topic and post data!", __LINE__, __FILE__, $sql);
				}
				while ($row = $db->sql_fetchrow($result))
				{
					if (!$list_open)
					{
						echo('<p class="gen">' . $lang['Topics_wo_post_found'] . ":</p>\n");
						echo("<div class=\"post-text\"><ul>\n");
						$list_open = true;
					}
					echo("<li>" . htmlspecialchars($row['topic_title']) . " (" . $row['topic_id'] . ")</li>\n");
					$result_array[] = $row['topic_id'];
				}
				$db->sql_freeresult($result);
				if ($list_open)
				{
					echo("</ul></div>\n");
					$list_open = false;
				}
				if (sizeof($result_array))
				{
					$record_list = implode(',', $result_array);
					echo('<p class="gen">' . $lang['Deleting_topics'] . " </p>\n");
					$sql = "DELETE FROM " . TOPICS_TABLE . "
						WHERE topic_id IN ($record_list)";
					$db->sql_return_on_error(true);
					$result = $db->sql_query($sql);
					$db->sql_return_on_error(false);
					if (!$result)
					{
						throw_error("Couldn't delete topic data!", __LINE__, __FILE__, $sql);
					}
					$update_post_data = true;
				}
				else
				{
					echo($lang['Nothing_to_do']);
				}

				// Check for topics with invalid forum
				echo('<p class="gen"><b>' . $lang['Checking_invalid_topics'] . '</b></p>' . "\n");
				$sql = "SELECT t.topic_id, t.topic_title
					FROM " . TOPICS_TABLE . " t
						LEFT JOIN " . FORUMS_TABLE . " f ON t.forum_id = f.forum_id
					WHERE f.forum_id IS NULL";
				$result_array = array();
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't get topic and forum data!", __LINE__, __FILE__, $sql);
				}
				while ($row = $db->sql_fetchrow($result))
				{
					if (!$list_open)
					{
						echo('<p class="gen">' . $lang['Invalid_topics_found'] . ":</p>\n");
						echo("<div class=\"post-text\"><ul>\n");
						$list_open = true;
					}
					echo("<li>" . htmlspecialchars($row['topic_title']) . " (" . $row['topic_id'] . ")</li>\n");
					$result_array[] = $row['topic_id'];
				}
				$db->sql_freeresult($result);
				if ($list_open)
				{
					echo("</ul></div>\n");
					$list_open = false;
				}
				if (sizeof($result_array))
				{
					$record_list = implode(',', $result_array);
					$new_forum = create_forum();
					echo('<p class="gen">' . sprintf($lang['Setting_forum'], $lang['New_forum_name']) . " </p>\n");
					$sql = "UPDATE " . TOPICS_TABLE . "
						SET forum_id = $new_forum
						WHERE topic_id IN ($record_list)";
					$db->sql_return_on_error(true);
					$result = $db->sql_query($sql);
					$db->sql_return_on_error(false);
					if (!$result)
					{
						throw_error("Couldn't update topic information!", __LINE__, __FILE__, $sql);
					}
					$sql = "UPDATE " . POSTS_TABLE . "
						SET forum_id = $new_forum
						WHERE topic_id IN ($record_list)";
					$db->sql_return_on_error(true);
					$result = $db->sql_query($sql);
					$db->sql_return_on_error(false);
					if (!$result)
					{
						throw_error("Couldn't update topic information!", __LINE__, __FILE__, $sql);
					}
					$update_post_data = true;
				}
				else
				{
					echo($lang['Nothing_to_do']);
				}

				// Check for posts with invalid topic
				echo('<p class="gen"><b>' . $lang['Checking_invalid_posts'] . '</b></p>' . "\n");
				$sql = "SELECT p.post_id, p.topic_id
					FROM " . POSTS_TABLE . " p
						LEFT JOIN " . TOPICS_TABLE . " t ON p.topic_id = t.topic_id
					WHERE t.topic_id IS NULL OR t.topic_status = " . TOPIC_MOVED . "
					ORDER BY p.topic_id, p.post_time";
				$current_topic = -1;
				$result_array = array();
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't get post and topic data!", __LINE__, __FILE__, $sql);
				}
				$row = $db->sql_fetchrow($result); // We need to do it outside the while-condition to prevent endless loops
				while ($row || sizeof($result_array))
				{
					if ($current_topic != $row['topic_id'] || !$row)
					{
						if (sizeof($result_array))
						{
							// Restoring topic
							if (!$list_open)
							{
								echo('<p class="gen">' . $lang['Invalid_posts_found'] . ":</p>\n");
								echo("<div class=\"post-text\"><ul>\n");
								$list_open = true;
							}
							$record_list = implode(',', $result_array);
							$new_forum = create_forum();
							$first_post = implode(',', array_slice($result_array, 0, 1));
							$last_post = implode(',', array_slice($result_array, -1, 1));
							$post_replies = sizeof($result_array) - 1;
							// Get title for new topic
							$sql2 = "SELECT post_subject
								FROM " . POSTS_TABLE . "
								WHERE post_id = $first_post";
							$db->sql_return_on_error(true);
							$result2 = $db->sql_query($sql2);
							$db->sql_return_on_error(false);
							if (!$result2)
							{
								throw_error("Couldn't get post information!", __LINE__, __FILE__, $sql2);
							}
							$row2 = $db->sql_fetchrow($result2);
							if (!$row2)
							{
								throw_error("Couldn't get post information!");
							}
							$db->sql_freeresult($result2);
							$topic_title = ($row2['post_subject'] == '') ? $lang['Restored_topic_name'] : $row2['post_subject'];
							// Get data from first post
							$sql2 = "SELECT poster_id, post_time
								FROM " . POSTS_TABLE . "
								WHERE post_id = $first_post";
							$db->sql_return_on_error(true);
							$result2 = $db->sql_query($sql2);
							$db->sql_return_on_error(false);
							if (!$result2)
							{
								throw_error("Couldn't get post information!", __LINE__, __FILE__, $sql2);
							}
							$row2 = $db->sql_fetchrow($result2);
							if (!$row2)
							{
								throw_error("Couldn't get post information!");
							}
							$db->sql_freeresult($result2);
							// Restore topic
							$sql2 = 'INSERT INTO ' . TOPICS_TABLE . " (forum_id, topic_title, topic_poster, topic_time, topic_views, topic_replies, topic_status, topic_type, topic_first_post_id, topic_last_post_id, topic_moved_id)
								VALUES ($new_forum, '" . addslashes($topic_title) . "', " . $row2['poster_id'] . ", " . $row2['post_time'] . ", 0, $post_replies, " . TOPIC_UNLOCKED . ", " . POST_NORMAL . ", $first_post, $last_post, 0)";
							$db->sql_return_on_error(true);
							$result2 = $db->sql_query($sql2);
							$db->sql_return_on_error(false);
							if (!$result2)
							{
								throw_error("Couldn't update topic data!", __LINE__, __FILE__, $sql2);
							}
							$new_topic = $db->sql_nextid();
							echo("<li>" . sprintf($lang['Setting_topic'], $record_list, htmlspecialchars($topic_title), $new_topic, $lang['New_forum_name']) . " </li>\n");
							$sql2 = "UPDATE " . POSTS_TABLE . "
								SET forum_id = $new_forum,
									topic_id = $new_topic
								WHERE post_id IN ($record_list)";
							$db->sql_return_on_error(true);
							$result2 = $db->sql_query($sql2);
							$db->sql_return_on_error(false);
							if (!$result2)
							{
								throw_error("Couldn't update post information!", __LINE__, __FILE__, $sql2);
							}
						}
						// Reset data
						$result_array = array();
						if ($row) // Update the array only if we have a new post
						{
							$result_array[] = $row['post_id'];
							$current_topic = $row['topic_id'];
							$row = $db->sql_fetchrow($result); // Go to the next record
						}
					}
					else
					{
						$result_array[] = $row['post_id'];
						$row = $db->sql_fetchrow($result); // Go to the next record
					}
				}
				$db->sql_freeresult($result);
				if ($list_open)
				{
					echo("</ul></div>\n");
					$list_open = false;
					$update_post_data = true;
				}
				else
				{
					echo($lang['Nothing_to_do']);
				}

				// Check for posts with invalid forum
				echo('<p class="gen"><b>' . $lang['Checking_invalid_forums_posts'] . '</b></p>' . "\n");
				$sql = "SELECT p.post_id, p.forum_id AS p_forum_id, fp.forum_name AS p_forum_name, t.forum_id AS t_forum_id, ft.forum_name AS t_forum_name
					FROM " . POSTS_TABLE . " p
						LEFT JOIN " . TOPICS_TABLE . " t ON p.topic_id = t.topic_id
						LEFT JOIN " . FORUMS_TABLE . " fp ON p.forum_id = fp.forum_id
						LEFT JOIN " . FORUMS_TABLE . " ft ON t.forum_id = ft.forum_id
					WHERE p.forum_id <> t.forum_id";
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't get post and topic data!", __LINE__, __FILE__, $sql);
				}
				while ($row = $db->sql_fetchrow($result))
				{
					if (!$list_open)
					{
						echo('<p class="gen">' . $lang['Invalid_forum_posts_found'] . ":</p>\n");
						echo("<div class=\"post-text\"><ul>\n");
						$list_open = true;
					}
					echo("<li>" . sprintf($lang['Setting_post_forum'], $row['post_id'], htmlspecialchars($row['p_forum_name']), $row['p_forum_id'], htmlspecialchars($row['t_forum_name']), $row['t_forum_id']) . "</li>\n");
					$sql2 = "UPDATE " . POSTS_TABLE . "
						SET forum_id = " . $row['t_forum_id'] . "
						WHERE post_id = " . $row['post_id'];
					$db->sql_return_on_error(true);
					$result2 = $db->sql_query($sql2);
					$db->sql_return_on_error(false);
					if (!$result2)
					{
						throw_error("Couldn't update post information!", __LINE__, __FILE__, $sql2);
					}
				}
				$db->sql_freeresult($result);
				if ($list_open)
				{
					echo("</ul></div>\n");
					$list_open = false;
					$update_post_data = true;
				}
				else
				{
					echo($lang['Nothing_to_do']);
				}

				// Check moved topics
				echo('<p class="gen"><b>' . $lang['Checking_moved_topics'] . '</b></p>' . "\n");
				$db_updated = false;
				$sql = "SELECT t.topic_id
					FROM " . TOPICS_TABLE . " t
						LEFT JOIN " . TOPICS_TABLE . " mt ON t.topic_moved_id = mt.topic_id
					WHERE mt.topic_id IS NULL AND
						t.topic_status = " . TOPIC_MOVED;
				$result_array = array();
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't get topic data!", __LINE__, __FILE__, $sql);
				}
				while ($row = $db->sql_fetchrow($result))
				{
					$result_array[] = $row['topic_id'];
				}
				$db->sql_freeresult($result);
				if (sizeof($result_array))
				{
					$record_list = implode(',', $result_array);
					echo('<p class="gen">' . $lang['Deleting_invalid_moved_topics'] . '</p>' . "\n");
					$sql = "DELETE FROM " . TOPICS_TABLE . "
						WHERE topic_id IN ($record_list)
							AND topic_status = " . TOPIC_MOVED;
					$db->sql_return_on_error(true);
					$result = $db->sql_query($sql);
					$db->sql_return_on_error(false);
					if (!$result)
					{
						throw_error("Couldn't update topic information!", __LINE__, __FILE__, $sql);
					}
					$affected_rows = $db->sql_affectedrows();
					if ($affected_rows == 1)
					{
						$db_updated = true;
						echo('<p class="gen">' . sprintf($lang['Affected_row'], $affected_rows) . '</p>' . "\n");
					}
					elseif ($affected_rows > 1)
					{
						$db_updated = true;
						echo('<p class="gen">' . sprintf($lang['Affected_rows'], $affected_rows) . '</p>' . "\n");
					}
				}
				// Check for normal topics with move information
				$sql = "UPDATE " . TOPICS_TABLE . "
					SET topic_moved_id = 0
					WHERE topic_moved_id <> 0
						AND topic_status <> " . TOPIC_MOVED;
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't update topic information!", __LINE__, __FILE__, $sql);
				}
				$affected_rows = $db->sql_affectedrows();
				if ($affected_rows == 1)
				{
					echo('<p class="gen">' . sprintf($lang['Updating_invalid_moved_topic'], $affected_rows) . '</p>' . "\n");
				}
				elseif ($affected_rows > 1)
				{
					echo('<p class="gen">' . sprintf($lang['Updating_invalid_moved_topics'], $affected_rows) . '</p>' . "\n");
				}
				elseif (!$db_updated)
				{
					echo($lang['Nothing_to_do']);
				}

				// Checking for invalid prune settings
				echo('<p class="gen"><b>' . $lang['Checking_prune_settings'] . '</b></p>' . "\n");
				$db_updated = false;
				$sql = "SELECT p.forum_id
					FROM " . PRUNE_TABLE . " p
						LEFT JOIN " . FORUMS_TABLE . " f ON p.forum_id = f.forum_id
					WHERE f.forum_id IS NULL
					GROUP BY p.forum_id";
				$result_array = array();
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't get forum and prune data!", __LINE__, __FILE__, $sql);
				}
				while ($row = $db->sql_fetchrow($result))
				{
					$result_array[] = $row['forum_id'];
				}
				$db->sql_freeresult($result);
				// Forums with multiple prune settings
				$sql = "SELECT p.forum_id
					FROM " . PRUNE_TABLE . " p
						LEFT JOIN " . FORUMS_TABLE . " f ON p.forum_id = f.forum_id
					GROUP BY p.forum_id
					HAVING Count(p.forum_id) > 1";
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't get forum and prune data!", __LINE__, __FILE__, $sql);
				}
				while ($row = $db->sql_fetchrow($result))
				{
					$result_array[] = $row['forum_id'];
				}
				$db->sql_freeresult($result);
				if (sizeof($result_array))
				{
					echo('<p class="gen">' . $lang['Removing_invalid_prune_settings'] . '</p>' . "\n");
					$record_list = implode(',', $result_array);
					$db_updated = true;
					$sql = "DELETE FROM " . PRUNE_TABLE . "
						WHERE forum_id IN ($record_list)";
					$db->sql_return_on_error(true);
					$result = $db->sql_query($sql);
					$db->sql_return_on_error(false);
					if (!$result)
					{
						throw_error("Couldn't update user data!", __LINE__, __FILE__, $sql);
					}
					$affected_rows = $db->sql_affectedrows();
					if ($affected_rows == 1)
					{
						echo('<p class="gen">' . sprintf($lang['Updating_invalid_moved_topic'], $affected_rows) . '</p>' . "\n");
					}
					elseif ($affected_rows > 1)
					{
						echo('<p class="gen">' . sprintf($lang['Updating_invalid_moved_topics'], $affected_rows) . '</p>' . "\n");
					}
				}
				// Forums with pruning enabled and no prune settings
				$sql = "SELECT f.forum_id
					FROM " . FORUMS_TABLE . " f
						LEFT JOIN " . PRUNE_TABLE . " p ON f.forum_id = p.forum_id
					WHERE p.forum_id IS NULL
						AND f.prune_enable = 1";
				$result_array = array();
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't get forum and prune data!", __LINE__, __FILE__, $sql);
				}
				while ($row = $db->sql_fetchrow($result))
				{
					$result_array[] = $row['forum_id'];
				}
				$db->sql_freeresult($result);
				if (sizeof($result_array))
				{
					$record_list = implode(',', $result_array);
					$sql = "UPDATE " . FORUMS_TABLE . "
						SET prune_enable = 0
						WHERE forum_id IN ($record_list)";
					$db->sql_return_on_error(true);
					$result = $db->sql_query($sql);
					$db->sql_return_on_error(false);
					if (!$result)
					{
						throw_error("Couldn't update user data!", __LINE__, __FILE__, $sql);
					}
					$affected_rows = $db->sql_affectedrows();
					if ($affected_rows == 1)
					{
						echo('<p class="gen">' . sprintf($lang['Updating_invalid_prune_setting'], $affected_rows) . '</p>' . "\n");
					}
					elseif ($affected_rows > 1)
					{
						echo('<p class="gen">' . sprintf($lang['Updating_invalid_moved_settings'], $affected_rows) . '</p>' . "\n");
					}
				}
				elseif (!$db_updated)
				{
					echo($lang['Nothing_to_do']);
				}

				// Checking for invalid topic-watch data
				echo('<p class="gen"><b>' . $lang['Checking_topic_watch_data'] . '</b></p>' . "\n");
				$sql = "SELECT tw.user_id
					FROM " . TOPICS_WATCH_TABLE . " tw
						LEFT JOIN " . USERS_TABLE . " u ON tw.user_id = u.user_id
					WHERE u.user_id IS NULL
					GROUP BY tw.user_id";
				$user_array = array();
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't get topic-watch and user data!", __LINE__, __FILE__, $sql);
				}
				while ($row = $db->sql_fetchrow($result))
				{
					$user_array[] = $row['user_id'];
				}
				$db->sql_freeresult($result);
				$sql = "SELECT tw.topic_id
					FROM " . TOPICS_WATCH_TABLE . " tw
						LEFT JOIN " . TOPICS_TABLE . " t ON tw.topic_id = t.topic_id
					WHERE t.topic_id IS NULL
					GROUP BY tw.topic_id";
				$topic_array = array();
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't get topic-watch and topic data!", __LINE__, __FILE__, $sql);
				}
				while ($row = $db->sql_fetchrow($result))
				{
					$topic_array[] = $row['topic_id'];
				}
				$db->sql_freeresult($result);
				if (sizeof($user_array) || sizeof($topic_array))
				{
					$sql_query = '';
					if (sizeof($user_array))
					{
						$sql_query = 'user_id IN (' . implode(',', $user_array) . ') ';
					}
					if (sizeof($topic_array))
					{
						$sql_query .= (($sql_query == '') ? '' : ' OR ') . 'topic_id IN (' . implode(',', $topic_array) . ') ';
					}
					$sql = "DELETE FROM " . TOPICS_WATCH_TABLE . "
						WHERE $sql_query";
					$db->sql_return_on_error(true);
					$result = $db->sql_query($sql);
					$db->sql_return_on_error(false);
					if (!$result)
					{
						throw_error("Couldn't update topic-watch data!", __LINE__, __FILE__, $sql);
					}
					$affected_rows = $db->sql_affectedrows();
					if ($affected_rows == 1)
					{
						echo('<p class="gen">' . sprintf($lang['Affected_row'], $affected_rows) . '</p>' . "\n");
					}
					elseif ($affected_rows > 1)
					{
						echo('<p class="gen">' . sprintf($lang['Affected_rows'], $affected_rows) . '</p>' . "\n");
					}
				}
				else
				{
					echo($lang['Nothing_to_do']);
				}

				// Checking for invalid auth-access data
				echo('<p class="gen"><b>' . $lang['Checking_auth_access_data'] . '</b></p>' . "\n");
				$sql = "SELECT aa.group_id
					FROM " . AUTH_ACCESS_TABLE . " aa
						LEFT JOIN " . GROUPS_TABLE . " g ON aa.group_id = g.group_id
					WHERE g.group_id IS NULL
					GROUP BY aa.group_id";
				$group_array = array();
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't get auth-access and group data!", __LINE__, __FILE__, $sql);
				}
				while ($row = $db->sql_fetchrow($result))
				{
					$group_array[] = $row['group_id'];
				}
				$db->sql_freeresult($result);
				$sql = "SELECT aa.forum_id
					FROM " . AUTH_ACCESS_TABLE . " aa
						LEFT JOIN " . FORUMS_TABLE . " f ON aa.forum_id = f.forum_id
					WHERE f.forum_id IS NULL
					GROUP BY aa.forum_id";
				$forum_array = array();
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't get auth-access and forum data!", __LINE__, __FILE__, $sql);
				}
				while ($row = $db->sql_fetchrow($result))
				{
					$forum_array[] = $row['forum_id'];
				}
				$db->sql_freeresult($result);
				if (sizeof($group_array) || sizeof($forum_array))
				{
					$sql_query = '';
					if (sizeof($group_array))
					{
						$sql_query = 'group_id IN (' . implode(',', $group_array) . ') ';
					}
					if (sizeof($forum_array))
					{
						$sql_query .= (($sql_query == '') ? '' : ' OR ') . 'forum_id IN (' . implode(',', $forum_array) . ') ';
					}
					$sql = "DELETE FROM " . AUTH_ACCESS_TABLE . "
						WHERE $sql_query";
					$db->sql_return_on_error(true);
					$result = $db->sql_query($sql);
					$db->sql_return_on_error(false);
					if (!$result)
					{
						throw_error("Couldn't update auth-access data!", __LINE__, __FILE__, $sql);
					}
					$affected_rows = $db->sql_affectedrows();
					if ($affected_rows == 1)
					{
						echo('<p class="gen">' . sprintf($lang['Affected_row'], $affected_rows) . '</p>' . "\n");
					}
					elseif ($affected_rows > 1)
					{
						echo('<p class="gen">' . sprintf($lang['Affected_rows'], $affected_rows) . '</p>' . "\n");
					}
				}
				else
				{
					echo($lang['Nothing_to_do']);
				}

				// Rebuild last posters and colors
				echo('<p class="gen"><b>' . $lang['Rebuild_Last_Poster_Details'] . '</b></p>' . "\n");
				if (!function_exists('rebuild_forums_topics_posters'))
				{
					@include_once(IP_ROOT_PATH . 'includes/functions_admin_forums.' . PHP_EXT);
				}
				rebuild_forums_topics_posters(true);
				if (!function_exists('update_all_clean_forum_names'))
				{
					@include_once(IP_ROOT_PATH . 'includes/functions_admin_forums.' . PHP_EXT);
				}
				update_all_clean_forum_names();
				echo('<p class="gen">' . $lang['Done'] . '</p>' . "\n");
				echo('<br />' . "\n");

				// If post or topic data has been updated, we interrupt here and add a link to resync the data
				if ($update_post_data)
				{
					echo('<p class="gen"><a href="' . append_sid('admin_db_maintenance.' . PHP_EXT . "?mode=perform&amp;function=synchronize_post_direct&amp;db_state=" . (($db_state) ? '1' : '0')) . '">' . $lang['Must_synchronize'] . "</a></p>\n");
					// Send Information about processing time
					echo('<p class="gensmall">' . sprintf($lang['Processing_time'], getmicrotime() - $timer) . '</p>');
					include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);
					exit;
				}
				else
				{
					lock_db(true);
				}
				break;

			case 'check_vote': // Check vote tables
				echo('<h1>' . $lang['Checking_vote_tables'] . '</h1>' . "\n");
				lock_db();

				// Check for votes without a topic
				echo('<p class="gen"><b>' . $lang['Checking_votes_wo_topic'] . '</b></p>' . "\n");
				$result_array = array();

				$sql = "SELECT o.*
					FROM " . POLL_OPTIONS_TABLE . " o
						LEFT JOIN " . TOPICS_TABLE . " t ON o.topic_id = t.topic_id
					WHERE t.topic_id IS NULL";
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't get vote and topic data!", __LINE__, __FILE__, $sql);
				}
				while ($row = $db->sql_fetchrow($result))
				{
					if (!$list_open)
					{
						echo('<p class="gen">' . $lang['Votes_wo_topic_found'] . ":</p>\n");
						echo("<div class=\"post-text\"><ul>\n");
						$list_open = true;
					}
					$result_array[] = $row['topic_id'];
				}
				$db->sql_freeresult($result);
				if ($list_open)
				{
					echo("</ul></div>\n");
					$list_open = false;
				}
				// Check for votes without results
				echo('<p class="gen"><b>' . $lang['Checking_votes_wo_result'] . '</b></p>' . "\n");
				$sql = "SELECT o.*
					FROM " . POLL_VOTES_TABLE . " o
						LEFT JOIN " . TOPICS_TABLE . " t ON o.topic_id = t.topic_id
					WHERE t.topic_id IS NULL";
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't get vote and result data!", __LINE__, __FILE__, $sql);
				}
				while ($row = $db->sql_fetchrow($result))
				{
					if (!$list_open)
					{
						echo('<p class="gen">' . $lang['Votes_wo_result_found'] . ":</p>\n");
						echo("<div class=\"post-text\"><ul>\n");
						$list_open = true;
					}
					$result_array[] = $row['topic_id'];
				}
				$db->sql_freeresult($result);
				if ($list_open)
				{
					echo("</ul></div>\n");
					$list_open = false;
				}
				if (sizeof($result_array))
				{
					$record_list = implode(',', $result_array);
					echo('<p class="gen">' . $lang['Deleting_Votes'] . " </p>\n");
					$sql = "DELETE FROM " . POLL_OPTIONS_TABLE . "
						WHERE topic_id IN ($record_list)";
					$db->sql_return_on_error(true);
					$result = $db->sql_query($sql);
					$db->sql_return_on_error(false);
					if (!$result)
					{
						throw_error("Couldn't delete vote data!", __LINE__, __FILE__, $sql);
					}
					$sql = "DELETE FROM " . POLL_VOTES_TABLE . "
						WHERE topic_id IN ($record_list)";
					$db->sql_return_on_error(true);
					$result = $db->sql_query($sql);
					$db->sql_return_on_error(false);
					if (!$result)
					{
						throw_error("Couldn't delete vote data!", __LINE__, __FILE__, $sql);
					}
				}
				else
				{
					echo($lang['Nothing_to_do']);
				}

				// Check for vote results without a vote
				echo('<p class="gen"><b>' . $lang['Checking_results_wo_vote'] . '</b></p>' . "\n");
				$sql = "SELECT v.*
					FROM " . POLL_VOTES_TABLE . " v
						LEFT JOIN " . POLL_OPTIONS_TABLE . " o ON v.poll_option_id = o.poll_option_id
					WHERE o.poll_option_id IS NULL";
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't get vote data!", __LINE__, __FILE__, $sql);
				}
				while ($row = $db->sql_fetchrow($result))
				{
					if (!$list_open)
					{
						echo('<p class="gen">' . $lang['Results_wo_vote_found'] . ":</p>\n");
						echo("<div class=\"post-text\"><ul>\n");
						$list_open = true;
					}
					echo("<li>" . sprintf($lang['Invalid_result'], htmlspecialchars($row['vote_option_text']), $row['vote_result']) . "</li>\n");
					$sql2 = "DELETE FROM " . POLL_VOTES_TABLE . "
						WHERE poll_option_id = " . $row['poll_option_id'] . "
							AND topic_id = " . $row['topic_id'];
					$db->sql_return_on_error(true);
					$result2 = $db->sql_query($sql2);
					$db->sql_return_on_error(false);
					if (!$result2)
					{
						throw_error("Couldn't delete vote result data!", __LINE__, __FILE__, $sql2);
					}
				}
				$db->sql_freeresult($result);
				if ($list_open)
				{
					echo("</ul></div>\n");
					$list_open = false;
				}
				else
				{
					echo($lang['Nothing_to_do']);
				}

				// Checking for invalid voters data
				echo('<p class="gen"><b>' . $lang['Checking_voters_data'] . '</b></p>' . "\n");
				$sql = "SELECT v.vote_user_id
					FROM " . POLL_VOTES_TABLE . " v
						LEFT JOIN " . USERS_TABLE . " u ON v.vote_user_id = u.user_id
					WHERE u.user_id IS NULL
					GROUP BY v.vote_user_id";
				$user_array = array();
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't get voters and user data!", __LINE__, __FILE__, $sql);
				}
				while ($row = $db->sql_fetchrow($result))
				{
					$user_array[] = $row['vote_user_id'];
				}
				$db->sql_freeresult($result);
				if (sizeof($user_array))
				{
					$sql_query = '';
					if (sizeof($user_array))
					{
						$sql_query = 'vote_user_id IN (' . implode(',', $user_array) . ') ';
					}
					$sql = "DELETE FROM " . POLL_VOTES_TABLE . "
						WHERE $sql_query";
					$db->sql_return_on_error(true);
					$result = $db->sql_query($sql);
					$db->sql_return_on_error(false);
					if (!$result)
					{
						throw_error("Couldn't update voters data!", __LINE__, __FILE__, $sql);
					}
					$affected_rows = $db->sql_affectedrows();
					if ($affected_rows == 1)
					{
						echo('<p class="gen">' . sprintf($lang['Affected_row'], $affected_rows) . '</p>' . "\n");
					}
					elseif ($affected_rows > 1)
					{
						echo('<p class="gen">' . sprintf($lang['Affected_rows'], $affected_rows) . '</p>' . "\n");
					}
				}
				else
				{
					echo($lang['Nothing_to_do']);
				}

				lock_db(true);
				break;

			case 'check_pm': // Check private messages
				echo('<h1>' . $lang['Checking_pm_tables'] . '</h1>' . "\n");
				lock_db();

				// Check pms for invaild senders
				echo('<p class="gen"><b>' . $lang['Checking_invalid_pm_senders'] . '</b></p>' . "\n");
				$sql = "SELECT pm.privmsgs_id
					FROM " . PRIVMSGS_TABLE . " pm
						LEFT JOIN " . USERS_TABLE . " u ON pm.privmsgs_from_userid = u.user_id
					WHERE u.user_id IS NULL";
				$result_array = array();
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't get private message and user data!", __LINE__, __FILE__, $sql);
				}
				while ($row = $db->sql_fetchrow($result))
				{
					$result_array[] = $row['privmsgs_id'];
				}
				$db->sql_freeresult($result);
				if (sizeof($result_array))
				{
					$record_list = implode(',', $result_array);
					echo('<p class="gen">' . $lang['Invalid_pm_senders_found'] . ": $record_list</p>\n");
					echo('<p class="gen">' . $lang['Updating_pms'] . '</p>' . "\n");
					$sql = "UPDATE " . PRIVMSGS_TABLE . "
						SET privmsgs_from_userid = " . DELETED . "
						WHERE privmsgs_id IN ($record_list)";
					$db->sql_return_on_error(true);
					$result = $db->sql_query($sql);
					$db->sql_return_on_error(false);
					if (!$result)
					{
						throw_error("Couldn't update private message information!", __LINE__, __FILE__, $sql);
					}
				}
				else
				{
					echo($lang['Nothing_to_do']);
				}

				// Check pms for invaild recipients
				echo('<p class="gen"><b>' . $lang['Checking_invalid_pm_recipients'] . '</b></p>' . "\n");
				$sql = "SELECT pm.privmsgs_id
					FROM " . PRIVMSGS_TABLE . " pm
						LEFT JOIN " . USERS_TABLE . " u ON pm.privmsgs_to_userid = u.user_id
					WHERE u.user_id IS NULL";
				$result_array = array();
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't get private message and user data!", __LINE__, __FILE__, $sql);
				}
				while ($row = $db->sql_fetchrow($result))
				{
					$result_array[] = $row['privmsgs_id'];
				}
				$db->sql_freeresult($result);
				if (sizeof($result_array))
				{
					$record_list = implode(',', $result_array);
					echo('<p class="gen">' . $lang['Invalid_pm_recipients_found'] . ": $record_list</p>\n");
					echo('<p class="gen">' . $lang['Updating_pms'] . '</p>' . "\n");
					$sql = "UPDATE " . PRIVMSGS_TABLE . "
						SET privmsgs_to_userid = " . DELETED . "
						WHERE privmsgs_id IN ($record_list)";
					$db->sql_return_on_error(true);
					$result = $db->sql_query($sql);
					$db->sql_return_on_error(false);
					if (!$result)
					{
						throw_error("Couldn't update private message information!", __LINE__, __FILE__, $sql);
					}
				}
				else
				{
					echo($lang['Nothing_to_do']);
				}

				// Check for pns with deleted sender or recipient
				echo('<p class="gen"><b>' . $lang['Checking_pm_deleted_users'] . '</b></p>' . "\n");
				$sql = "SELECT privmsgs_id
					FROM " . PRIVMSGS_TABLE . "
					WHERE (privmsgs_from_userid = " . DELETED . " AND privmsgs_type IN (" . PRIVMSGS_NEW_MAIL . "," . PRIVMSGS_UNREAD_MAIL . "," . PRIVMSGS_SENT_MAIL . "," . PRIVMSGS_SAVED_OUT_MAIL . ")) OR
						(privmsgs_to_userid = " . DELETED . " AND privmsgs_type IN (" . PRIVMSGS_NEW_MAIL . "," . PRIVMSGS_UNREAD_MAIL . "," . PRIVMSGS_READ_MAIL . "," . PRIVMSGS_SAVED_IN_MAIL . "))";
				$result_array = array();
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't get private message and user data!", __LINE__, __FILE__, $sql);
				}
				while ($row = $db->sql_fetchrow($result))
				{
					$result_array[] = $row['privmsgs_id'];
				}
				$db->sql_freeresult($result);
				if (sizeof($result_array))
				{
					$record_list = implode(',', $result_array);
					echo('<p class="gen">' . $lang['Invalid_pm_users_found'] . ": $record_list</p>\n");
					echo('<p class="gen">' . $lang['Deleting_pms'] . '</p>' . "\n");
					$sql = "DELETE FROM " . PRIVMSGS_TABLE . "
						WHERE privmsgs_id IN ($record_list)";
					$db->sql_return_on_error(true);
					$result = $db->sql_query($sql);
					$db->sql_return_on_error(false);
					if (!$result)
					{
						throw_error("Couldn't delete private message data!", __LINE__, __FILE__, $sql);
					}
				}
				else
				{
					echo($lang['Nothing_to_do']);
				}

				// Updating new pm counter
				echo('<p class="gen"><b>' . $lang['Synchronize_new_pm_data'] . '</b></p>' . "\n");
				if (check_mysql_version())
				{
					$sql = "SELECT u.user_id, u.username, u.user_new_privmsg, Count(pm.privmsgs_id) AS new_counter
						FROM " . USERS_TABLE . " u
							INNER JOIN " . PRIVMSGS_TABLE . " pm ON u.user_id = pm.privmsgs_to_userid
						WHERE u.user_id <> " . ANONYMOUS . "
							AND pm.privmsgs_type = " . PRIVMSGS_NEW_MAIL . "
						GROUP BY u.user_id, u.username, u.user_new_privmsg";
				}
				else
				{
					$sql = "SELECT u.user_id, u.username, u.user_new_privmsg, Count(pm.privmsgs_id) AS new_counter
						FROM " . USERS_TABLE . " u, " .
							PRIVMSGS_TABLE . " pm
						WHERE u.user_id = pm.privmsgs_to_userid
							AND u.user_id <> " . ANONYMOUS . "
							AND pm.privmsgs_type = " . PRIVMSGS_NEW_MAIL . "
						GROUP BY u.user_id, u.username, u.user_new_privmsg";
				}
				$result_array = array();
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't get user and private message data!", __LINE__, __FILE__, $sql);
				}
				while ($row = $db->sql_fetchrow($result))
				{
					$result_array[] = $row['user_id'];
					if ($row['new_counter'] != $row['user_new_privmsg'])
					{
						if (!$list_open)
						{
							echo('<p class="gen">' . $lang['Synchronizing_users'] . ":</p>\n");
							echo("<div class=\"post-text\"><ul>\n");
							$list_open = true;
						}
						echo("<li>" . sprintf($lang['Synchronizing_user'], htmlspecialchars($row['username']), $row['user_id']) . "</li>\n");
						$sql2 = "UPDATE " . USERS_TABLE . "
							SET user_new_privmsg = " . $row['new_counter'] . "
							WHERE user_id = " . $row['user_id'];
						$db->sql_return_on_error(true);
						$result2 = $db->sql_query($sql2);
						$db->sql_return_on_error(false);
						if (!$result2)
						{
							throw_error("Couldn't update user information!", __LINE__, __FILE__, $sql2);
						}
					}
				}
				$db->sql_freeresult($result);
				// All other users
				if (sizeof($result_array))
				{
					$sql_string = 'user_id NOT IN (' . implode(',', $result_array) . ') AND';
				}
				else
				{
					$sql_string = '';
				}
				$sql = "SELECT user_id, username
					FROM " . USERS_TABLE . "
					WHERE $sql_string user_new_privmsg <> 0";
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't get user data!", __LINE__, __FILE__, $sql);
				}
				while ($row = $db->sql_fetchrow($result))
				{
					if (!$list_open)
					{
						echo('<p class="gen">' . $lang['Synchronizing_users'] . ":</p>\n");
						echo("<div class=\"post-text\"><ul>\n");
						$list_open = true;
					}
					echo("<li>" . sprintf($lang['Synchronizing_user'], htmlspecialchars($row['username']), $row['user_id']) . "</li>\n");
					$sql2 = "UPDATE " . USERS_TABLE . "
						SET user_new_privmsg = 0
						WHERE user_id = " . $row['user_id'];
					$db->sql_return_on_error(true);
					$result2 = $db->sql_query($sql2);
					$db->sql_return_on_error(false);
					if (!$result2)
					{
						throw_error("Couldn't update user information!", __LINE__, __FILE__, $sql2);
					}
				}
				$db->sql_freeresult($result);
				if ($list_open)
				{
					echo("</ul></div>\n");
					$list_open = false;
				}
				else
				{
					echo($lang['Nothing_to_do']);
				}

				// Updating unread pm counter
				echo('<p class="gen"><b>' . $lang['Synchronize_unread_pm_data'] . '</b></p>' . "\n");
				if (check_mysql_version())
				{
					$sql = "SELECT u.user_id, u.username, u.user_unread_privmsg, Count(pm.privmsgs_id) AS new_counter
						FROM " . USERS_TABLE . " u
							INNER JOIN " . PRIVMSGS_TABLE . " pm ON u.user_id = pm.privmsgs_to_userid
						WHERE u.user_id <> " . ANONYMOUS . "
							AND pm.privmsgs_type = " . PRIVMSGS_UNREAD_MAIL . "
						GROUP BY u.user_id, u.username, u.user_unread_privmsg";
				}
				else
				{
					$sql = "SELECT u.user_id, u.username, u.user_unread_privmsg, Count(pm.privmsgs_id) AS new_counter
						FROM " . USERS_TABLE . " u, " .
							PRIVMSGS_TABLE . " pm
						WHERE u.user_id = pm.privmsgs_to_userid
							AND u.user_id <> " . ANONYMOUS . "
							AND pm.privmsgs_type = " . PRIVMSGS_UNREAD_MAIL . "
						GROUP BY u.user_id, u.username, u.user_unread_privmsg";
				}
				$result_array = array();
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't get user and private message data!", __LINE__, __FILE__, $sql);
				}
				while ($row = $db->sql_fetchrow($result))
				{
					$result_array[] = $row['user_id'];
					if ($row['new_counter'] != $row['user_unread_privmsg'])
					{
						if (!$list_open)
						{
							echo('<p class="gen">' . $lang['Synchronizing_users'] . ":</p>\n");
							echo("<div class=\"post-text\"><ul>\n");
							$list_open = true;
						}
						echo("<li>" . sprintf($lang['Synchronizing_user'], htmlspecialchars($row['username']), $row['user_id']) . "</li>\n");
						$sql2 = "UPDATE " . USERS_TABLE . "
							SET user_unread_privmsg = " . $row['new_counter'] . "
							WHERE user_id = " . $row['user_id'];
						$db->sql_return_on_error(true);
						$result2 = $db->sql_query($sql2);
						$db->sql_return_on_error(false);
						if (!$result2)
						{
							throw_error("Couldn't update user information!", __LINE__, __FILE__, $sql2);
						}
					}
				}
				$db->sql_freeresult($result);
				// All other users
				if (sizeof($result_array))
				{
					$sql_string = 'user_id NOT IN (' . implode(',', $result_array) . ') AND';
				}
				else
				{
					$sql_string = '';
				}
				$sql = "SELECT user_id, username
					FROM " . USERS_TABLE . "
					WHERE $sql_string user_unread_privmsg <> 0";
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't get user data!", __LINE__, __FILE__, $sql);
				}
				while ($row = $db->sql_fetchrow($result))
				{
					if (!$list_open)
					{
						echo('<p class="gen">' . $lang['Synchronizing_users'] . ":</p>\n");
						echo("<div class=\"post-text\"><ul>\n");
						$list_open = true;
					}
					echo("<li>" . sprintf($lang['Synchronizing_user'], htmlspecialchars($row['username']), $row['user_id']) . "</li>\n");
					$sql2 = "UPDATE " . USERS_TABLE . "
						SET user_unread_privmsg = 0
						WHERE user_id = " . $row['user_id'];
					$db->sql_return_on_error(true);
					$result2 = $db->sql_query($sql2);
					$db->sql_return_on_error(false);
					if (!$result2)
					{
						throw_error("Couldn't update user information!", __LINE__, __FILE__, $sql2);
					}
				}
				$db->sql_freeresult($result);
				if ($list_open)
				{
					echo("</ul></div>\n");
					$list_open = false;
				}
				else
				{
					echo($lang['Nothing_to_do']);
				}

				lock_db(true);
				break;

			case 'check_config': // Check config table
				echo('<h1>' . $lang['Checking_config_table'] . '</h1>' . "\n");
				lock_db();

				echo('<p class="gen"><b>' . $lang['Checking_config_entries'] . '</b></p>' . "\n");

				// Update config data to match current configuration
				if (!empty($_SERVER['SERVER_PROTOCOL']) || !empty($_ENV['SERVER_PROTOCOL']))
				{
					$protocol = (!empty($_SERVER['SERVER_PROTOCOL'])) ? $_SERVER['SERVER_PROTOCOL'] : $_ENV['SERVER_PROTOCOL'];
					if (strtolower(substr($protocol, 0 , 5)) == 'https')
					{
						$default_config['cookie_secure'] = '1';
					}
				}
				if (!empty($_SERVER['SERVER_NAME']) || !empty($_ENV['SERVER_NAME']))
				{
					$default_config['server_name'] = (!empty($_SERVER['SERVER_NAME'])) ? $_SERVER['SERVER_NAME'] : $_ENV['SERVER_NAME'];
				}
				else if (!empty($_SERVER['HTTP_HOST']) || !empty($_ENV['HTTP_HOST']))
				{
					$default_config['server_name'] = (!empty($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST'] : $_ENV['HTTP_HOST'];
				}
				if (!empty($_SERVER['SERVER_PORT']) || !empty($_ENV['SERVER_PORT']))
				{
					$default_config['server_port'] = (!empty($_SERVER['SERVER_PORT'])) ? $_SERVER['SERVER_PORT'] : $_ENV['SERVER_PORT'];
				}
				$default_config['script_path'] = str_replace('admin', '', dirname($_SERVER['SCRIPT_NAME']));
				$sql = "SELECT Min(topic_time) as startdate FROM " . TOPICS_TABLE;
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if ($result)
				{
					if (($row = $db->sql_fetchrow($result)) && $row['startdate'] > 0)
					{
						$default_config['board_startdate'] = $row['startdate'];
					}
				}

				// Start the job
				reset($default_config);
				while (list($key, $value) = each($default_config))
				{
					$sql = 'SELECT config_value FROM ' . CONFIG_TABLE . "
						WHERE config_name = '$key'";
					$db->sql_return_on_error(true);
					$result = $db->sql_query($sql);
					$db->sql_return_on_error(false);
					if (!$result)
					{
						throw_error("Couldn't query config table!", __LINE__, __FILE__, $sql);
					}
					if (!($row = $db->sql_fetchrow($result)))
					{
						// entry does not exists
						if (!$list_open)
						{
							echo('<p class="gen">' . $lang['Restoring_config'] . ":</p>\n");
							echo("<div class=\"post-text\"><ul>\n");
							$list_open = true;
						}
						echo("<li><b>$key:</b> $value</li>\n");
						$sql = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value)
							VALUES ('$key', '$value')";
						$db->sql_return_on_error(true);
						$result = $db->sql_query($sql);
						$db->sql_return_on_error(false);
						if (!$result)
						{
							throw_error("Couldn't update config table!", __LINE__, __FILE__, $sql);
						}
					}
				}
				if ($list_open)
				{
					echo("</ul></div>\n");
					$list_open = false;
				}
				else
				{
					echo($lang['Nothing_to_do']);
				}

				lock_db(true);
				break;

			case 'check_search_wordmatch': // Check search word match data
				echo('<h1>' . $lang['Checking_search_wordmatch_tables'] . '</h1>' . "\n");
				lock_db();

				// Checking for invalid search word match data
				echo('<p class="gen"><b>' . $lang['Checking_search_data'] . '</b></p>' . "\n");
				$sql = "SELECT sm.post_id
					FROM " . SEARCH_MATCH_TABLE . " sm
						LEFT JOIN " . POSTS_TABLE . " p ON sm.post_id = p.post_id
					WHERE p.post_id IS NULL
					GROUP BY sm.post_id";
				$post_array = array();
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't get search-match and post data!", __LINE__, __FILE__, $sql);
				}
				while ($row = $db->sql_fetchrow($result))
				{
					$post_array[] = $row['post_id'];
				}
				$db->sql_freeresult($result);
				$sql = "SELECT sm.word_id
					FROM " . SEARCH_MATCH_TABLE . " sm
						LEFT JOIN " . SEARCH_WORD_TABLE . " sw ON sm.word_id = sw.word_id
					WHERE sw.word_id IS NULL
						OR sw.word_common = 1
					GROUP BY sm.word_id";
				$word_array = array();
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't get search-match and word data!", __LINE__, __FILE__, $sql);
				}
				while ($row = $db->sql_fetchrow($result))
				{
					$word_array[] = $row['word_id'];
				}
				$db->sql_freeresult($result);
				if (sizeof($post_array) || sizeof($word_array))
				{
					$sql_query = '';
					if (sizeof($post_array))
					{
						$sql_query = 'post_id IN (' . implode(',', $post_array) . ') ';
					}
					if (sizeof($word_array))
					{
						$sql_query .= (($sql_query == '') ? '' : ' OR ') . 'word_id IN (' . implode(',', $word_array) . ') ';
					}
					$sql = "DELETE FROM " . SEARCH_MATCH_TABLE . "
						WHERE $sql_query";
					$db->sql_return_on_error(true);
					$result = $db->sql_query($sql);
					$db->sql_return_on_error(false);
					if (!$result)
					{
						throw_error("Couldn't update search-match data!", __LINE__, __FILE__, $sql);
					}
					$affected_rows = $db->sql_affectedrows();
					if ($affected_rows == 1)
					{
						echo('<p class="gen">' . sprintf($lang['Affected_row'], $affected_rows) . '</p>' . "\n");
					}
					elseif ($affected_rows > 1)
					{
						echo('<p class="gen">' . sprintf($lang['Affected_rows'], $affected_rows) . '</p>' . "\n");
					}
				}
				else
				{
					echo($lang['Nothing_to_do']);
				}

				lock_db(true);
				break;

			case 'check_search_wordlist': // Check search word list data
				echo('<h1>' . $lang['Checking_search_wordlist_tables'] . '</h1>' . "\n");
				lock_db();

				// Checking for invalid search word list data
				echo('<p class="gen"><b>' . $lang['Checking_search_words'] . '</b></p>' . "\n");
				$sql = "SELECT sw.word_id
					FROM " . SEARCH_WORD_TABLE . " sw
						LEFT JOIN " . SEARCH_MATCH_TABLE . " sm ON sw.word_id = sm.word_id
					WHERE sm.word_id IS NULL
						AND sw.word_common <> 1";
				$result_array = array();
				$affected_rows = 0;
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't get search data!", __LINE__, __FILE__, $sql);
				}
				while ($row = $db->sql_fetchrow($result))
				{
					$result_array[] = $row['word_id'];
					if (sizeof($result_array) >= 100)
					{
						echo('<p class="gen">' . $lang['Removing_part_invalid_words'] . "...</p>\n");
						$record_list = implode(',', $result_array);
						$sql2 = "DELETE FROM " . SEARCH_WORD_TABLE . "
							WHERE word_id IN ($record_list)";
						$db->sql_return_on_error(true);
						$result2 = $db->sql_query($sql2);
						$db->sql_return_on_error(false);
						if (!$result2)
						{
							throw_error("Couldn't update search words!", __LINE__, __FILE__, $sql2);
						}
						$affected_rows += $db->sql_affectedrows();
						$result_array = array();
					}
				}
				$db->sql_freeresult($result);
				if (sizeof($result_array))
				{
					echo('<p class="gen">' . $lang['Removing_invalid_words'] . '</p>' . "\n");
					$record_list = implode(',', $result_array);
					$sql = "DELETE FROM " . SEARCH_WORD_TABLE . "
						WHERE word_id IN ($record_list)";
					$db->sql_return_on_error(true);
					$result = $db->sql_query($sql);
					$db->sql_return_on_error(false);
					if (!$result)
					{
						throw_error("Couldn't update search words!", __LINE__, __FILE__, $sql);
					}
					$affected_rows += $db->sql_affectedrows();
				}
				if ($affected_rows == 1)
				{
					echo('<p class="gen">' . sprintf($lang['Affected_row'], $affected_rows) . '</p>' . "\n");
				}
				elseif ($affected_rows > 1)
				{
					echo('<p class="gen">' . sprintf($lang['Affected_rows'], $affected_rows) . '</p>' . "\n");
				}
				else
				{
					echo($lang['Nothing_to_do']);
				}

				lock_db(true);
				break;

			case 'sync_topics_subjects': // Synchronize Topics Subjects
				echo('<h1>' . $lang['Sync_topics_subjects'] . '</h1>' . "\n");
				$db_state = lock_db();

				// Synchronizing...
				echo('<p class="gen"><b>' . $lang['Sync_topics_subjects_progress'] . '</b></p>' . "\n");
				$affected_rows = 0;
				$sql= "UPDATE " . POSTS_TABLE . " p, " . TOPICS_TABLE . " t
					SET p.post_subject = t.topic_title
					WHERE p.post_id = t.topic_first_post_id";
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't get topics data!", __LINE__, __FILE__, $sql);
				}
				$affected_rows = $db->sql_affectedrows();
				if ($affected_rows == 1)
				{
					echo('<p class="gen">' . sprintf($lang['Affected_row'], $affected_rows) . '</p>' . "\n");
				}
				elseif ($affected_rows > 1)
				{
					echo('<p class="gen">' . sprintf($lang['Affected_rows'], $affected_rows) . '</p>' . "\n");
				}
				else
				{
					echo($lang['Nothing_to_do']);
				}

				lock_db(true);
				break;

			case 'rebuild_search_index': // Rebuild Search Index
				echo('<h1>' . $lang['Rebuilding_search_index'] . '</h1>' . "\n");
				$db_state = lock_db();

				// Clear Tables
				echo('<p class="gen"><b>' . $lang['Deleting_search_tables'] . '</b></p>' . "\n");
				$sql = "DELETE FROM " . SEARCH_TABLE;
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't delete from search result table!", __LINE__, __FILE__, $sql);
				}
				$sql = "DELETE FROM " . SEARCH_WORD_TABLE;
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't delete from search-word table!", __LINE__, __FILE__, $sql);
				}
				$sql = "DELETE FROM " . SEARCH_MATCH_TABLE;
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't delete from search-match table!", __LINE__, __FILE__, $sql);
				}
				echo('<p class="gen">' . $lang['Done'] . '</p>' . "\n");

				// Reset auto increment
				echo('<p class="gen"><b>' . $lang['Reset_search_autoincrement'] . '</b></p>' . "\n");
				$sql = "ALTER TABLE " . SEARCH_WORD_TABLE . " AUTO_INCREMENT=1";
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't reset auto_increment!", __LINE__, __FILE__, $sql);
				}
				echo('<p class="gen">' . $lang['Done'] . '</p>' . "\n");

				echo('<p class="gen"><b>' . $lang['Preparing_config_data'] . '</b></p>' . "\n");
				// Set data for start position in config table
				set_config('dbmtnc_rebuild_pos', '0', false);
				// Get data for end position
				$sql = "SELECT Max(post_id) AS max_post_id
					FROM " . POSTS_TABLE;
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't get post data!", __LINE__, __FILE__, $sql);
				}
				if (!($row = $db->sql_fetchrow($result)))
				{
					throw_error("Couldn't get post data!", __LINE__, __FILE__, $sql);
				}
				$db->sql_freeresult($result);
				// Set data for end position in config table
				set_config('dbmtnc_rebuild_end', intval($row['max_post_id']), false);
				echo('<p class="gen">' . $lang['Done'] . '</p>' . "\n");

				echo('<p class="gen"><a href="' . append_sid('admin_db_maintenance.' . PHP_EXT . "?mode=perform&amp;function=perform_rebuild&amp;db_state=" . (($db_state) ? '1' : '0')) . '">' . $lang['Can_start_rebuilding'] . '</a><br /><span class="gensmall">' . $lang['Click_once_warning'] . '</span></p>' . "\n");
				// Send Information about processing time
				echo('<p class="gensmall">' . sprintf($lang['Processing_time'], getmicrotime() - $timer) . '</p>');
				include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);
				exit;
				break;

			case 'proceed_rebuilding': // Proceed rebuilding search index
				echo('<h1>' . $lang['Preparing_to_proceed'] . '</h1>' . "\n");
				$db_state = lock_db();

				// Clear Tables
				echo('<p class="gen"><b>' . $lang['Preparing_search_tables'] . '</b></p>' . "\n");
				$sql = "DELETE FROM " . SEARCH_TABLE;
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't delete from search result table!", __LINE__, __FILE__, $sql);
				}
				$sql = "DELETE FROM " . SEARCH_MATCH_TABLE . "
					WHERE post_id > " . intval($config['dbmtnc_rebuild_pos']) . "
						AND post_id <= " . intval($config['dbmtnc_rebuild_end']);
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't delete from search-match table!", __LINE__, __FILE__, $sql);
				}
				echo('<p class="gen">' . $lang['Done'] . '</p>' . "\n");

				echo('<p class="gen"><a href="' . append_sid('admin_db_maintenance.' . PHP_EXT . "?mode=perform&amp;function=perform_rebuild&amp;db_state=" . (($db_state) ? '1' : '0')) . '">' . $lang['Can_start_rebuilding'] . '</a><br /><span class="gensmall">' . $lang['Click_once_warning'] . '</span></p>' . "\n");
				// Send Information about processing time
				echo('<p class="gensmall">' . sprintf($lang['Processing_time'], getmicrotime() - $timer) . '</p>');
				include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);
				exit;
				break;

			case 'perform_rebuild': // Rebuild search index (perform part)
				// ATTENTION: page_header not sent yet!
				$db_state = (isset($_GET['db_state'])) ? intval($_GET['db_state']) : 0;
				// Load functions
				include(IP_ROOT_PATH . 'includes/functions_search.' . PHP_EXT);
				// Identify PHP version and time limit configuration
				if (phpversion() >= '4.0.5' && ($config['dbmtnc_rebuildcfg_php3only'] == 0)) // Handle PHP beffore 4.0.5 as PHP 3 since array_search is not available
				{
					$php_ver = 4;
					// try to reset time limit
					$reset_allowed = true;
					$execution_time = $config['dbmtnc_rebuildcfg_timelimit'];
					set_error_handler('catch_error');
					set_time_limit($config['dbmtnc_rebuildcfg_timelimit']);
					restore_error_handler();
					// Try to set unlimited execution time
					@set_time_limit(0);
				}
				else
				{
					$php_ver = 3;
					$execution_time = get_cfg_var('max_execution_time');
					// Try to set unlimited execution time
					@set_time_limit(0);
				}
				if ($execution_time === false)
				{
					$execution_time = 30; // Asume 30 if an error occurs
				}
				// Calculate posts to process
				$posts_to_index = intval(($execution_time - 5) * (($php_ver == 4) ? $config['dbmtnc_rebuildcfg_php4pps'] : $config['dbmtnc_rebuildcfg_php3pps']));
				if ($posts_to_index < $config['dbmtnc_rebuildcfg_minposts'])
				{
					$posts_to_index = $config['dbmtnc_rebuildcfg_minposts'];
				}
				// Check whether a special limit was set
				if (intval($config['dbmtnc_rebuildcfg_timeoverwrite']) != 0)
				{
					$posts_to_index = intval($config['dbmtnc_rebuildcfg_timeoverwrite']);
				}
				// We have all data so get the post information
				$sql = "SELECT post_id, post_subject, post_text
					FROM " . POSTS_TABLE . "
					WHERE post_id > " . intval($config['dbmtnc_rebuild_pos']) . "
						AND post_id <= " . intval($config['dbmtnc_rebuild_end']) . "
					ORDER BY post_id
					LIMIT $posts_to_index";
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					include(IP_ROOT_PATH . ADM . '/page_header_admin.' . PHP_EXT);
					throw_error("Couldn't get post data!", __LINE__, __FILE__, $sql);
				}
				// Get first record
				$row = $db->sql_fetchrow($result);
				if (!$row) // Yeah! we reached the end of the posts - finish actions and exit
				{
					$db->sql_freeresult($result);
					include(IP_ROOT_PATH . ADM . '/page_header_admin.' . PHP_EXT);
					set_config('dbmtnc_rebuild_pos', '-1', false);
					set_config('dbmtnc_rebuild_end', '0', false);

					echo('<p class="gen">' . $lang['Indexing_finished'] . ".</p>\n");

					if ($db_state == 0)
					{
						lock_db(true, true, true);
					}
					else
					{
						echo('<p class="gen"><b>' . $lang['Unlock_db'] . '</b></p>' . "\n");
						echo('<p class="gen">' . $lang['Ignore_unlock_command'] . '</p>' . "\n");
					}

					echo('<p class="gen"><a href="' . append_sid('admin_db_maintenance.' . PHP_EXT) . '">' . $lang['Back_to_DB_Maintenance'] . "</a></p>\n");
					// Send Information about processing time
					echo('<p class="gensmall">' . sprintf($lang['Processing_time'], getmicrotime() - $timer) . '</p>');
					include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);
					exit;
				}
				$last_post = 0;
				switch ($php_ver)
				{
					case 3: // use standard method if we have PHP 3
						while ($row)
						{
							$last_post = $row['post_id'];
							add_search_words('single', $last_post, stripslashes($row['post_text']), stripslashes($row['post_subject']));
							$row = $db->sql_fetchrow($result);
						}
					break;
					case 4: // use advanced method if we have PHP 4+ (we can make use of the advanced array functions)
						$post_size = strlen($row['post_text']) + strlen($row['post_subject']); // needed for controlling array size
						// get stopword and synonym array
						stopwords_synonyms_init();
						if (!is_array($stopwords_array))
						{
							$stopwords_array = array();
						}
						if (!is_array($synonyms_array))
						{
							$synonyms_array = array();
						}
						$empty_array = array(); // We'll need this array for passing it to the clean_words function
						// Convert arrays
						for ($i = 0; $i < sizeof($stopwords_array); $i++)
						{
							$stopwords_array[$i] = trim(strtolower($stopwords_array[$i]));
						}
						$result_array = array(array(), array());
						for ($i = 0; $i < sizeof($synonyms_array); $i++)
						{
							list($replace_synonym, $match_synonym) = split(' ', trim(strtolower($synonyms_array[$i])));
							$result_array[0][] = trim($replace_synonym);
							$result_array[1][] = trim($match_synonym);
						}
						$synonyms_array = $result_array;
						$result_array = array(array(), array(), array());
						$i = 0;
						while ($row && ($post_size <= $config['dbmtnc_rebuildcfg_maxmemory'] * 1024 || $i < $config['dbmtnc_rebuildcfg_minposts']))
						{
							$last_post = $row['post_id'];
							// handle text
							$word_list = split_words(clean_words('post', $row['post_text'], $empty_array, $empty_array));
							foreach ($word_list as $word)
							{
								// cutting of long words in functions_search.php seems not to work under some conditions - so check it again
								if ($word != '' && strlen($word) <= 20)
								{
									$result_array[0][] = $last_post;
									$result_array[1][] = 0;
									$result_array[2][] = $word;
								}
							}
							// handle subject
							$word_list = split_words(clean_words('post', $row['post_subject'], $empty_array, $empty_array));
							foreach ($word_list as $word)
							{
								// cutting of long words in functions_search.php seems not to work under some conditions - so check it again
								if ($word != '' && strlen($word) <= 20)
								{
									$result_array[0][] = $last_post;
									$result_array[1][] = 1;
									$result_array[2][] = $word;
								}
							}
							unset($word_list);
							$row = $db->sql_fetchrow($result);
							$i++;
							$post_size += strlen($row['post_text']) + strlen($row['post_subject']);
						}
						// sort array
						array_multisort($result_array[2], SORT_ASC, SORT_STRING, $result_array[0], SORT_ASC, SORT_NUMERIC, $result_array[1]);
						// insert array in database
						$cache_word = '';
						$cache_word_id = 0;
						$insert_values = '';
						$word_array = array();
						$array_count = sizeof($result_array[0]);
						for ($i = 0; $i < $array_count; $i++)
						{
							if ($result_array[2][$i] !== $cache_word) // We have a new word (don't allow type conversions)
							{
								$cache_word_id = get_word_id($result_array[2][$i]);
								$cache_word = $result_array[2][$i];
								$word_array[] = $cache_word;
							}
							if (!is_null($cache_word_id) && ($last_post_id <> $result_array[0][$i] || $last_word_id <> $cache_word_id || $last_title_match <> $result_array[1][$i]))
							{
								$last_post_id = $result_array[0][$i];
								$last_word_id = $cache_word_id;
								$last_title_match = $result_array[1][$i];
								$sql = "INSERT INTO " . SEARCH_MATCH_TABLE . " (post_id, word_id, title_match) VALUES ($last_post_id, $last_word_id, $last_title_match)";
								$db->sql_return_on_error(true);
								$result_tmp = $db->sql_query($sql);
								$db->sql_return_on_error(false);
								if (!$result_tmp)
								{
									include(IP_ROOT_PATH . ADM . '/page_header_admin.' . PHP_EXT);
									throw_error("Couldn't insert into search match!", __LINE__, __FILE__, $sql);
								}
							}
							unset($result_array[0][$i]);
							unset($result_array[1][$i]);
							unset($result_array[2][$i]);
						}
						remove_common('single', 4/10, $word_array);
					break;
				}
				// All posts are indexed for this turn - update Config-Data
				set_config('dbmtnc_rebuild_pos', $last_post, false);
				// OK, all actions are done - send headers

				$redirect_url = append_sid('admin_db_maintenance.' . PHP_EXT . '?mode=perform&amp;function=perform_rebuild&amp;db_state=' . $db_state);
				meta_refresh(3, $redirect_url);

				include(IP_ROOT_PATH . ADM . '/page_header_admin.' . PHP_EXT);
				$db->sql_freeresult($result);
				// Get Statistics
				$posts_total = 0;
				$sql = "SELECT Count(*) AS posts_total
					FROM " . POSTS_TABLE . "
					WHERE post_id <= " . intval($config['dbmtnc_rebuild_end']);
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if ($result)
				{
					if ($row = $db->sql_fetchrow($result))
					{
						$posts_total = $row['posts_total'];
					}
					$db->sql_freeresult($result);
				}
				$posts_indexed = 0;
				$sql = "SELECT Count(*) AS posts_indexed
					FROM " . POSTS_TABLE . "
					WHERE post_id <= " . intval($last_post);
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if ($result)
				{
					if ($row = $db->sql_fetchrow($result))
					{
						$posts_indexed = $row['posts_indexed'];
					}
					$db->sql_freeresult($result);
				}

				echo('<p class="gen">' . sprintf($lang['Indexing_progress'], $posts_indexed, $posts_total, ($posts_indexed / $posts_total) * 100, $last_post) . '</p>' . "\n");
				echo('<p class="gen"><a href="' . append_sid('admin_db_maintenance.' . PHP_EXT . "?mode=perform&amp;function=perform_rebuild&amp;db_state=$db_state") . '">' . $lang['Click_or_wait_to_proceed'] . '</a><br /><span class="gensmall">' . $lang['Click_once_warning'] . '</span></p>' . "\n");
				// Send Information about processing time
				echo('<p class="gensmall">' . sprintf($lang['Processing_time'], getmicrotime() - $timer) . '</p>');
				include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);
				exit;
				break;

			case 'synchronize_notify_forum_id': // Synchronize Topic Notifications forum ids
				echo('<h1>' . $lang['Synchronizing_notify_forum_ids'] . '</h1>' . "\n");

				// ReSync forum_id in notifications table
				$sql = "UPDATE " . TOPICS_TABLE . " t, " . TOPICS_WATCH_TABLE . " tw
					SET tw.forum_id = t.forum_id
					WHERE tw.topic_id = t.topic_id";
				$result = $db->sql_query($sql);

				echo('<p class="gen">' . $lang['Done'] . '</p>' . "\n");

				// Send Information about processing time
				echo('<p class="gensmall">' . sprintf($lang['Processing_time'], getmicrotime() - $timer) . '</p>');

				include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);
				exit;
				break;

			case 'synchronize_post': // Synchronize post data

			case 'synchronize_post_direct': // Run directly
				echo('<h1>' . $lang['Synchronize_posts'] . '</h1>' . "\n");
				if ($function == 'synchronize_post_direct')
				{
					$db_state = (isset($_GET['db_state'])) ? intval($_GET['db_state']) : 1;
				}
				else
				{
					lock_db();
				}

				// Updating normal topics
				echo('<p class="gen"><b>' . $lang['Synchronize_topic_data'] . '</b></p>' . "\n");
				if (check_mysql_version())
				{
					$sql = "SELECT t.topic_id, t.topic_title, t.topic_replies, t.topic_first_post_id, t.topic_last_post_id, Count(p.post_id) - 1 AS new_replies, Min(p.post_id) AS new_first_post_id, Max(p.post_id) AS new_last_post_id
						FROM " . TOPICS_TABLE . " t
							INNER JOIN " . POSTS_TABLE . " p ON t.topic_id = p.topic_id
						GROUP BY t.topic_id, t.topic_title, t.topic_replies, t.topic_first_post_id, t.topic_last_post_id
						HAVING new_replies <> t.topic_replies OR
							new_first_post_id <> t.topic_first_post_id OR
							new_last_post_id <> t.topic_last_post_id";
				}
				else
				{
					$sql = "SELECT t.topic_id, t.topic_title, t.topic_replies, t.topic_first_post_id, t.topic_last_post_id, Count(p.post_id) - 1 AS new_replies, Min(p.post_id) AS new_first_post_id, Max(p.post_id) AS new_last_post_id
						FROM " . TOPICS_TABLE . " t, " .
							POSTS_TABLE . " p
						WHERE t.topic_id = p.topic_id
						GROUP BY t.topic_id, t.topic_title, t.topic_replies, t.topic_first_post_id, t.topic_last_post_id
						HAVING new_replies <> t.topic_replies OR
							new_first_post_id <> t.topic_first_post_id OR
							new_last_post_id <> t.topic_last_post_id";
				}
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't get topic and post data!", __LINE__, __FILE__, $sql);
				}
				while ($row = $db->sql_fetchrow($result))
				{
					if (!$list_open)
					{
						echo('<p class="gen">' . $lang['Synchronizing_topics'] . ":</p>\n");
						echo("<div class=\"post-text\"><ul>\n");
						$list_open = true;
					}
					echo("<li>" . sprintf($lang['Synchronizing_topic'], $row['topic_id'], htmlspecialchars($row['topic_title'])) . "</li>\n");
					$sql2 = "UPDATE " . TOPICS_TABLE . "
						SET topic_replies = " . $row['new_replies'] . ",
							topic_first_post_id = " . $row['new_first_post_id'] . ",
							topic_last_post_id = " . $row['new_last_post_id'] . "
						WHERE topic_id = " . $row['topic_id'];
					$db->sql_return_on_error(true);
					$result2 = $db->sql_query($sql2);
					$db->sql_return_on_error(false);
					if (!$result2)
					{
						throw_error("Couldn't update topic information!", __LINE__, __FILE__, $sql2);
					}
				}
				$db->sql_freeresult($result);
				if ($list_open)
				{
					echo("</ul></div>\n");
					$list_open = false;
				}
				else
				{
					echo($lang['Nothing_to_do']);
				}

				// Updating moved topics
				echo('<p class="gen"><b>' . $lang['Synchronize_moved_topic_data'] . '</b></p>' . "\n");
				$sql = "SELECT topic_id, topic_title, topic_last_post_id, topic_moved_id
					FROM " . TOPICS_TABLE . "
					WHERE topic_status = " . TOPIC_MOVED;
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't get topic data!", __LINE__, __FILE__, $sql);
				}
				while ($row = $db->sql_fetchrow($result))
				{
					// Getting data for original topic
					$sql2 = "SELECT topic_id, Count(post_id) - 1 AS topic_replies, Min(post_id) AS topic_first_post_id, Max(post_id) AS topic_last_post_id
						FROM " . POSTS_TABLE . "
						WHERE topic_id = " . $row['topic_moved_id'] . " AND
							post_id <= " . $row['topic_last_post_id'] . "
						GROUP BY topic_id";
					$db->sql_return_on_error(true);
					$result2 = $db->sql_query($sql2);
					$db->sql_return_on_error(false);
					if (!$result2)
					{
						throw_error("Couldn't get post information!", __LINE__, __FILE__, $sql2);
					}
					if ($row2 = $db->sql_fetchrow($result2))
					{
						$sql3 = "SELECT topic_id
							FROM " . TOPICS_TABLE . "
							WHERE topic_id = " . $row['topic_id'] . " AND
								(topic_replies <> " . $row2['topic_replies'] . " OR topic_first_post_id <> " . $row2['topic_first_post_id'] . " OR topic_last_post_id <> " . $row2['topic_last_post_id'] . ")";
						$db->sql_return_on_error(true);
						$result3 = $db->sql_query($sql3);
						$db->sql_return_on_error(false);
						if (!$result3)
						{
							throw_error("Couldn't get topic information!", __LINE__, __FILE__, $sql3);
						}
						$row3 = $db->sql_fetchrow($result3);
						$db->sql_freeresult($result3);
						if ($row3)
						{
							if (!$list_open)
							{
								echo('<p class="gen">' . $lang['Synchronizing_moved_topics'] . ":</p>\n");
								echo("<div class=\"post-text\"><ul>\n");
								$list_open = true;
							}
							echo("<li>" . sprintf($lang['Synchronizing_moved_topic'], $row['topic_id'], $row['topic_moved_id'], htmlspecialchars($row['topic_title'])) . "</li>\n");
							$sql3 = "UPDATE " . TOPICS_TABLE . "
								SET topic_replies = " . $row2['topic_replies'] . ",
									topic_first_post_id = " . $row2['topic_first_post_id'] . ",
									topic_last_post_id = " . $row2['topic_last_post_id'] . "
								WHERE topic_id = " . $row['topic_id'];
							$db->sql_return_on_error(true);
							$result3 = $db->sql_query($sql3);
							$db->sql_return_on_error(false);
							if (!$result3)
							{
								throw_error("Couldn't update topic information!", __LINE__, __FILE__, $sql3);
							}
						}
					}
					else
					{
						throw_error(sprintf($lang['Inconsistencies_found'], '<a href="' . append_sid('admin_db_maintenance.' . PHP_EXT . '?mode=perform&amp;function=check_post') . '">', '</a>'));
					}
					$db->sql_freeresult($result2);
				}
				$db->sql_freeresult($result);
				if ($list_open)
				{
					echo("</ul></div>\n");
					$list_open = false;
				}
				else
				{
					echo($lang['Nothing_to_do']);
				}

				// Updating topic data of forums
				echo('<p class="gen"><b>' . $lang['Synchronize_forum_topic_data'] . '</b></p>' . "\n");
				if (check_mysql_version())
				{
					$sql = "SELECT f.forum_id, f.forum_name, f.forum_topics, Count(t.topic_id) AS new_topics
						FROM " . FORUMS_TABLE . " f
							INNER JOIN " . TOPICS_TABLE . " t ON f.forum_id = t.forum_id
						GROUP BY f.forum_id, f.forum_name, f.forum_topics
						HAVING new_topics <> f.forum_topics";
				}
				else
				{
					$sql = "SELECT f.forum_id, f.forum_name, f.forum_topics, Count(t.topic_id) AS new_topics
						FROM " . FORUMS_TABLE . " f, " .
							TOPICS_TABLE . " t
						WHERE f.forum_id = t.forum_id
						GROUP BY f.forum_id, f.forum_name, f.forum_topics
						HAVING new_topics <> f.forum_topics";
				}
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't get forum and topic data!", __LINE__, __FILE__, $sql);
				}
				while ($row = $db->sql_fetchrow($result))
				{
					if (!$list_open)
					{
						echo('<p class="gen">' . $lang['Synchronizing_forums'] . ":</p>\n");
						echo("<div class=\"post-text\"><ul>\n");
						$list_open = true;
					}
					echo("<li>" . sprintf($lang['Synchronizing_forum'], $row['forum_id'], htmlspecialchars($row['forum_name'])) . "</li>\n");
					$sql2 = "UPDATE " . FORUMS_TABLE . "
						SET forum_topics = " . $row['new_topics'] . "
						WHERE forum_id = " . $row['forum_id'];
					$db->sql_return_on_error(true);
					$result2 = $db->sql_query($sql2);
					$db->sql_return_on_error(false);
					if (!$result2)
					{
						throw_error("Couldn't update forum information!", __LINE__, __FILE__, $sql2);
					}
				}
				$db->sql_freeresult($result);
				if ($list_open)
				{
					echo("</ul></div>\n");
					$list_open = false;
				}
				else
				{
					echo($lang['Nothing_to_do']);
				}

				// Updating forums without a topic
				echo('<p class="gen"><b>' . $lang['Synchronize_forum_data_wo_topic'] . '</b></p>' . "\n");
				$sql = "SELECT f.forum_id
					FROM " . FORUMS_TABLE . " f
						LEFT JOIN " . TOPICS_TABLE . " t ON f.forum_id = t.forum_id
					WHERE t.forum_id IS NULL AND
						(f.forum_topics <> 0 OR f.forum_last_post_id <> 0)";
				$result_array = array();
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't get forum and topic data!", __LINE__, __FILE__, $sql);
				}
				while ($row = $db->sql_fetchrow($result))
				{
					$result_array[] = $row['forum_id'];
				}
				$db->sql_freeresult($result);
				if (sizeof($result_array))
				{
					$record_list = implode(',', $result_array);
					$sql = "UPDATE " . FORUMS_TABLE . "
						SET forum_topics = 0,
							forum_last_post_id = 0
						WHERE forum_id IN ($record_list)";
					$db->sql_return_on_error(true);
					$result = $db->sql_query($sql);
					$db->sql_return_on_error(false);
					if (!$result)
					{
						throw_error("Couldn't update forum data!", __LINE__, __FILE__, $sql);
					}
					$affected_rows = $db->sql_affectedrows();
					if ($affected_rows == 1)
					{
						echo('<p class="gen">' . sprintf($lang['Affected_row'], $affected_rows) . '</p>' . "\n");
					}
					elseif ($affected_rows > 1)
					{
						echo('<p class="gen">' . sprintf($lang['Affected_rows'], $affected_rows) . '</p>' . "\n");
					}
				}
				elseif (!$db_updated)
				{
					echo($lang['Nothing_to_do']);
				}

				// Updating post data of forums
				echo('<p class="gen"><b>' . $lang['Synchronize_forum_post_data'] . '</b></p>' . "\n");
				if (check_mysql_version())
				{
					$sql = "SELECT f.forum_id, f.forum_name, f.forum_posts, f.forum_last_post_id, COUNT(p.post_id) AS new_posts, MAX(p.post_id) AS new_last_post_id
						FROM " . FORUMS_TABLE . " f
							INNER JOIN " . POSTS_TABLE . " p ON f.forum_id = p.forum_id
						GROUP BY f.forum_id, f.forum_name, f.forum_posts, f.forum_last_post_id
						HAVING new_posts <> f.forum_posts OR
							new_last_post_id <> f.forum_last_post_id";
				}
				else
				{
					$sql = "SELECT f.forum_id, f.forum_name, f.forum_posts, f.forum_last_post_id, COUNT(p.post_id) AS new_posts, MAX(p.post_id) AS new_last_post_id
						FROM " . FORUMS_TABLE . " f, " .
							POSTS_TABLE . " p
						WHERE f.forum_id = p.forum_id
						GROUP BY f.forum_id, f.forum_name, f.forum_posts, f.forum_last_post_id
						HAVING new_posts <> f.forum_posts OR
							new_last_post_id <> f.forum_last_post_id";
				}
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't get forum and post data!", __LINE__, __FILE__, $sql);
				}
				while ($row = $db->sql_fetchrow($result))
				{
					if (!$list_open)
					{
						echo('<p class="gen">' . $lang['Synchronizing_forums'] . ":</p>\n");
						echo("<div class=\"post-text\"><ul>\n");
						$list_open = true;
					}
					echo("<li>" . sprintf($lang['Synchronizing_forum'], $row['forum_id'], htmlspecialchars($row['forum_name'])) . "</li>\n");
					$sql2 = "UPDATE " . FORUMS_TABLE . "
						SET forum_posts = " . $row['new_posts'] . ",
							forum_last_post_id = " . $row['new_last_post_id'] . "
						WHERE forum_id = " . $row['forum_id'];
					$db->sql_return_on_error(true);
					$result2 = $db->sql_query($sql2);
					$db->sql_return_on_error(false);
					if (!$result2)
					{
						throw_error("Couldn't update forum information!", __LINE__, __FILE__, $sql2);
					}
				}
				$db->sql_freeresult($result);
				if ($list_open)
				{
					echo("</ul></div>\n");
					$list_open = false;
				}
				else
				{
					echo($lang['Nothing_to_do']);
				}

				// Updating forums without a post
				echo('<p class="gen"><b>' . $lang['Synchronize_forum_data_wo_post'] . '</b></p>' . "\n");
				$sql = "SELECT f.forum_id
					FROM " . FORUMS_TABLE . " f
						LEFT JOIN " . POSTS_TABLE . " p ON f.forum_id = p.forum_id
					WHERE p.forum_id IS NULL AND
						(f.forum_posts <> 0)";
				$result_array = array();
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't get forum and topic data!", __LINE__, __FILE__, $sql);
				}
				while ($row = $db->sql_fetchrow($result))
				{
					$result_array[] = $row['forum_id'];
				}
				$db->sql_freeresult($result);
				if (sizeof($result_array))
				{
					$record_list = implode(',', $result_array);
					$sql = "UPDATE " . FORUMS_TABLE . "
						SET forum_posts = 0
						WHERE forum_id IN ($record_list)";
					$db->sql_return_on_error(true);
					$result = $db->sql_query($sql);
					$db->sql_return_on_error(false);
					if (!$result)
					{
						throw_error("Couldn't update forum data!", __LINE__, __FILE__, $sql);
					}
					$affected_rows = $db->sql_affectedrows();
					if ($affected_rows == 1)
					{
						echo('<p class="gen">' . sprintf($lang['Affected_row'], $affected_rows) . '</p>' . "\n");
					}
					elseif ($affected_rows > 1)
					{
						echo('<p class="gen">' . sprintf($lang['Affected_rows'], $affected_rows) . '</p>' . "\n");
					}
				}
				elseif (!$db_updated)
				{
					echo($lang['Nothing_to_do']);
				}

				// Rebuild last posters and colors
				echo('<p class="gen"><b>' . $lang['Rebuild_Last_Poster_Details'] . '</b></p>' . "\n");
				if (!function_exists('rebuild_forums_topics_posters'))
				{
					@include_once(IP_ROOT_PATH . 'includes/functions_admin_forums.' . PHP_EXT);
				}
				rebuild_forums_topics_posters(true);
				update_all_clean_forum_names();
				echo('<p class="gen">' . $lang['Done'] . '</p>' . "\n");
				echo('<br />' . "\n");

				if ($function == 'synchronize_post_direct')
				{
					if ($db_state == 0)
					{
						lock_db(true, true, true);
					}
					else
					{
						echo('<p class="gen"><b>' . $lang['Unlock_db'] . '</b></p>' . "\n");
						echo('<p class="gen">' . $lang['Ignore_unlock_command'] . '</p>' . "\n");
					}
				}
				else
				{
					lock_db(true);
				}

				break;

			case 'synchronize_user': // Synchronize post counter of users
				echo('<h1>' . $lang['Synchronize_post_counters'] . '</h1>' . "\n");
				lock_db();

				// Updating new pm counter
				echo('<p class="gen"><b>' . $lang['Synchronize_user_post_counter'] . '</b></p>' . "\n");

				// List forum to not sync
				$sql = "SELECT forum_id FROM " . FORUMS_TABLE . "
								WHERE forum_postcount = 0";
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't query forum table!", __LINE__, __FILE__, $sql);
				}
				$exclude_list = '';
				while ($row = $db->sql_fetchrow($result))
				{
					$exclude_list .= (($exclude_list != '') ? ', ' : '') . "'" . $row['forum_id'] . "'";
				}
				$exclude_sql = '';
				if ($exclude_list != '')
				{
					$exclude_sql = "AND p.forum_id NOT IN (" . $exclude_list . ")";
				}

				if (check_mysql_version())
				{
					$sql = "SELECT u.user_id, u.username, u.user_posts, Count(p.post_id) AS new_counter
						FROM " . USERS_TABLE . " u
							INNER JOIN " . POSTS_TABLE . " p ON u.user_id = p.poster_id
						WHERE u.user_id <> " . ANONYMOUS . "
						" . $exclude_sql . "
						GROUP BY u.user_id, u.username, u.user_posts";
				}
				else
				{
					$sql = "SELECT u.user_id, u.username, u.user_posts, Count(p.post_id) AS new_counter
						FROM " . USERS_TABLE . " u, " . POSTS_TABLE . " p
						WHERE u.user_id = p.poster_id
							AND u.user_id <> " . ANONYMOUS . "
						" . $exclude_sql . "
						GROUP BY u.user_id, u.username, u.user_posts";
				}
				$result_array = array();
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't get user and post data!", __LINE__, __FILE__, $sql);
				}
				while ($row = $db->sql_fetchrow($result))
				{
					$result_array[] = $row['user_id'];
					if ($row['new_counter'] != $row['user_posts'])
					{
						if (!$list_open)
						{
							echo('<p class="gen">' . $lang['Synchronizing_users'] . ":</p>\n");
							echo("<div class=\"post-text\"><ul>\n");
							$list_open = true;
						}
						echo("<li>" . sprintf($lang['Synchronizing_user_counter'], htmlspecialchars($row['username']), $row['user_id'], $row['user_posts'], $row['new_counter']) . "</li>\n");
						$sql2 = "UPDATE " . USERS_TABLE . "
							SET user_posts = " . $row['new_counter'] . "
							WHERE user_id = " . $row['user_id'];
						$db->sql_return_on_error(true);
						$result2 = $db->sql_query($sql2);
						$db->sql_return_on_error(false);
						if (!$result2)
						{
							throw_error("Couldn't update user information!", __LINE__, __FILE__, $sql2);
						}
					}
				}
				$db->sql_freeresult($result);
				// All other users
				if (sizeof($result_array))
				{
					$sql_string = 'user_id NOT IN (' . implode(',', $result_array) . ') AND';
				}
				else
				{
					$sql_string = '';
				}
				$sql = "SELECT user_id, username, user_posts
					FROM " . USERS_TABLE . "
					WHERE $sql_string user_posts <> 0";
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't get user data!", __LINE__, __FILE__, $sql);
				}
				while ($row = $db->sql_fetchrow($result))
				{
					if (!$list_open)
					{
						echo('<p class="gen">' . $lang['Synchronizing_users'] . ":</p>\n");
						echo("<div class=\"post-text\"><ul>\n");
						$list_open = true;
					}
					echo("<li>" . sprintf($lang['Synchronizing_user_counter'], htmlspecialchars($row['username']), $row['user_id'], $row['user_posts'], 0) . "</li>\n");
					$sql2 = "UPDATE " . USERS_TABLE . "
						SET user_posts = 0
						WHERE user_id = " . $row['user_id'];
					$db->sql_return_on_error(true);
					$result2 = $db->sql_query($sql2);
					$db->sql_return_on_error(false);
					if (!$result2)
					{
						throw_error("Couldn't update user information!", __LINE__, __FILE__, $sql2);
					}
				}
				$db->sql_freeresult($result);
				if ($list_open)
				{
					echo("</ul></div>\n");
					$list_open = false;
				}
				else
				{
					echo($lang['Nothing_to_do']);
				}

				lock_db(true);
				break;

			case 'synchronize_mod_state': // Synchronize moderator status
				echo('<h1>' . $lang['Synchronize_moderators'] . '</h1>' . "\n");
				lock_db();

				// Getting moderator data
				echo('<p class="gen"><b>' . $lang['Getting_moderators'] . '</b></p>' . "\n");
				if (check_mysql_version())
				{
					$sql = "SELECT ug.user_id
						FROM " . USER_GROUP_TABLE . " ug
							INNER JOIN " . AUTH_ACCESS_TABLE . " aa ON ug.group_id = aa.group_id
						WHERE aa.auth_mod = 1 AND ug.user_pending <> 1
						GROUP BY ug.user_id";
				}
				else
				{
					$sql = "SELECT ug.user_id
						FROM " . USER_GROUP_TABLE . " ug, " .
							AUTH_ACCESS_TABLE . " aa
						WHERE ug.group_id = aa.group_id
							AND aa.auth_mod = 1
							AND ug.user_pending <> 1
						GROUP BY ug.user_id";
				}
				$result_array = array();
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't get moderator data!", __LINE__, __FILE__, $sql);
				}
				while ($row = $db->sql_fetchrow($result))
				{
					$result_array[] = $row['user_id'];
				}
				$db->sql_freeresult($result);
				if (sizeof($result_array))
				{
					$moderator_list = implode(',', $result_array);
				}
				else
				{
					$moderator_list = '0';
				}
				echo('<p class="gen">' . $lang['Done'] . '</p>' . "\n");

				// Checking non moderators
				echo('<p class="gen"><b>' . $lang['Checking_non_moderators'] . '</b></p>' . "\n");
				$sql = "SELECT user_id, username
					FROM " . USERS_TABLE . "
					WHERE user_level = " . MOD . "
						AND user_id NOT IN ($moderator_list)";
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't get user data!", __LINE__, __FILE__, $sql);
				}
				while ($row = $db->sql_fetchrow($result))
				{
					if (!$list_open)
					{
						echo('<p class="gen">' . $lang['Updating_mod_state'] . ":</p>\n");
						echo("<div class=\"post-text\"><ul>\n");
						$list_open = true;
					}
					echo("<li>" . sprintf($lang['Changing_moderator_status'], htmlspecialchars($row['username']), $row['user_id']) . "</li>\n");
					$sql2 = "UPDATE " . USERS_TABLE . "
						SET user_level = " . USER . "
						WHERE user_id = " . $row['user_id'];
					$db->sql_return_on_error(true);
					$result2 = $db->sql_query($sql2);
					$db->sql_return_on_error(false);
					if (!$result2)
					{
						throw_error("Couldn't update user information!", __LINE__, __FILE__, $sql2);
					}
				}
				$db->sql_freeresult($result);
				if ($list_open)
				{
					echo("</ul></div>\n");
					$list_open = false;
				}
				else
				{
					echo($lang['Nothing_to_do']);
				}

				// Checking moderators
				echo('<p class="gen"><b>' . $lang['Checking_moderators'] . '</b></p>' . "\n");
				$sql = "SELECT user_id, username
					FROM " . USERS_TABLE . "
					WHERE user_level = " . USER . "
						AND user_id IN ($moderator_list)";
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't get user data!", __LINE__, __FILE__, $sql);
				}
				while ($row = $db->sql_fetchrow($result))
				{
					if (!$list_open)
					{
						echo('<p class="gen">' . $lang['Updating_mod_state'] . ":</p>\n");
						echo("<div class=\"post-text\"><ul>\n");
						$list_open = true;
					}
					echo("<li>" . sprintf($lang['Changing_moderator_status'], htmlspecialchars($row['username']), $row['user_id']) . "</li>\n");
					$sql2 = "UPDATE " . USERS_TABLE . "
						SET user_level = " . MOD . "
						WHERE user_id = " . $row['user_id'];
					$db->sql_return_on_error(true);
					$result2 = $db->sql_query($sql2);
					$db->sql_return_on_error(false);
					if (!$result2)
					{
						throw_error("Couldn't update user information!", __LINE__, __FILE__, $sql2);
					}
				}
				$db->sql_freeresult($result);
				if ($list_open)
				{
					echo("</ul></div>\n");
					$list_open = false;
				}
				else
				{
					echo($lang['Nothing_to_do']);
				}

				lock_db(true);
				break;

			case 'reset_date': // Reset dates
				echo('<h1>' . $lang['Resetting_future_post_dates'] . '</h1>' . "\n");
				lock_db();

				// Set a variable with the current time
				$time = time();

				// Checking post table
				echo('<p class="gen"><b>' . $lang['Checking_post_dates'] . '</b></p>' . "\n");
				$sql = "UPDATE " . POSTS_TABLE . "
					SET post_time = $time
					WHERE post_time > $time";
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't update post data!", __LINE__, __FILE__, $sql);
				}
				$affected_rows = $db->sql_affectedrows();
				if ($affected_rows == 1)
				{
					echo('<p class="gen">' . sprintf($lang['Affected_row'], $affected_rows) . '</p>' . "\n");
				}
				elseif ($affected_rows > 1)
				{
					echo('<p class="gen">' . sprintf($lang['Affected_rows'], $affected_rows) . '</p>' . "\n");
				}
				else
				{
					echo($lang['Nothing_to_do']);
				}

				// Checking private messages table
				echo('<p class="gen"><b>' . $lang['Checking_pm_dates'] . '</b></p>' . "\n");
				$sql = "UPDATE " . PRIVMSGS_TABLE . "
					SET privmsgs_date = $time
					WHERE privmsgs_date > $time";
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't update private message data!", __LINE__, __FILE__, $sql);
				}
				$affected_rows = $db->sql_affectedrows();
				if ($affected_rows == 1)
				{
					echo('<p class="gen">' . sprintf($lang['Affected_row'], $affected_rows) . '</p>' . "\n");
				}
				elseif ($affected_rows > 1)
				{
					echo('<p class="gen">' . sprintf($lang['Affected_rows'], $affected_rows) . '</p>' . "\n");
				}
				else
				{
					echo($lang['Nothing_to_do']);
				}

				// Checking user table (last e-mail))
				echo('<p class="gen"><b>' . $lang['Checking_email_dates'] . '</b></p>' . "\n");
				$sql = "UPDATE " . USERS_TABLE . "
					SET user_emailtime = $time
					WHERE user_emailtime > $time";
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't update user data!", __LINE__, __FILE__, $sql);
				}
				$affected_rows = $db->sql_affectedrows();
				if ($affected_rows == 1)
				{
					echo('<p class="gen">' . sprintf($lang['Affected_row'], $affected_rows) . '</p>' . "\n");
				}
				elseif ($affected_rows > 1)
				{
					echo('<p class="gen">' . sprintf($lang['Affected_rows'], $affected_rows) . '</p>' . "\n");
				}
				else
				{
					echo($lang['Nothing_to_do']);
				}

				lock_db(true);
				break;

			case 'reset_sessions': // Reset sessions
				echo('<h1>' . $lang['Resetting_sessions'] . '</h1>' . "\n");
				lock_db();

				// Deleting tables
				echo('<p class="gen"><b>' . $lang['Deleting_session_tables'] . '</b></p>' . "\n");
				$sql = "DELETE FROM " . SESSIONS_TABLE;
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't delete from session table!", __LINE__, __FILE__, $sql);
				}
				$sql = "DELETE FROM " . SEARCH_TABLE;
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't delete from search result table!", __LINE__, __FILE__, $sql);
				}
				echo('<p class="gen">' . $lang['Done'] . '</p>' . "\n");

				// Restore session data of current user to prevent getting thrown out of the admin panel
				echo('<p class="gen"><b>' . $lang['Restoring_session'] . '</b></p>' . "\n");
				// Set Variables
				$session_id = $user->data['session_id'];
				$user_id = $user->data['user_id'];
				$current_time = time();
				$user_ip = $user->data['session_ip'];
				$page_id = $user->data['session_page'];
				$login = $user->data['session_logged_in'];
				if ($phpbb_version[1] >= 15)
				{
					$sql = "INSERT INTO " . SESSIONS_TABLE . "
						(session_id, session_user_id, session_start, session_time, session_ip, session_page, session_logged_in, session_admin)
						VALUES ('" . $session_id . "', '" . $user_id . "', '" . $current_time . "', '" . $current_time . "', '" . $db->sql_escape($user_ip) . "', '" . $page_id . "', '" . $login . "', '1')";
				}
				else
				{
					$sql = "INSERT INTO " . SESSIONS_TABLE . "
						(session_id, session_user_id, session_start, session_time, session_ip, session_page, session_logged_in)
						VALUES ('" . $session_id . "', '" . $user_id . "', '" . $current_time . "', '" . $current_time . "', '" . $db->sql_escape($user_ip) . "', '" . $page_id . "', '" . $login . "')";
				}
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't restore session data!", __LINE__, __FILE__, $sql);
				}
				echo('<p class="gen">' . $lang['Done'] . '</p>' . "\n");

				lock_db(true);
				break;

			case 'check_db': // Check database
				echo('<h1>' . $lang['Checking_db'] . '</h1>' . "\n");
				if (!check_mysql_version())
				{
					echo('<p class="gen">' . $lang['Old_MySQL_Version'] . '</p>' . "\n");
					break;
				}
				lock_db();
				echo('<p class="gen"><b>' . $lang['Checking_tables'] . ":</b></p>\n");
				echo("<div class=\"post-text\"><ul>\n");
				$list_open = true;

				for($i = 0; $i < sizeof($tables); $i++)
				{
					$tablename = $table_prefix . $tables[$i];
					$sql = "CHECK TABLE $tablename";
					$db->sql_return_on_error(true);
					$result = $db->sql_query($sql);
					$db->sql_return_on_error(false);
					if (!$result)
					{
						throw_error("Couldn't check table!", __LINE__, __FILE__, $sql);
					}
					if ($row = $db->sql_fetchrow($result))
					{
						if ($row['Msg_type'] == 'status')
						{
							echo("<li>$tablename: " . $lang['Table_OK'] . "</li>\n");
						}
						else //  We got an error
						{
							// Check whether the error results from HEAP-table type
							$sql2 = "SHOW TABLE STATUS LIKE '$tablename'";
							$result2 = $db->sql_query($sql2);
							$row2 = $db->sql_fetchrow($result2);
							if ((isset($row2['Type']) && $row2['Type'] == 'HEAP') || (isset($row2['Engine']) && ($row2['Engine'] == 'HEAP' || $row2['Engine'] == 'MEMORY')))
							{
								// Table is from HEAP-table type
								echo("<li>$tablename: " . $lang['Table_HEAP_info'] . "</li>\n");
							}
							else
							{
								echo("<li><b>$tablename:</b> " . htmlspecialchars($row['Msg_text']) . "</li>\n");
							}
							$db->sql_freeresult($result2);
						}
					}
					$db->sql_freeresult($result);
				}
				echo("</ul></div>\n");
				$list_open = false;
				lock_db(true);
				break;

			case 'repair_db': // Repair database
				echo('<h1>' . $lang['Repairing_db'] . '</h1>' . "\n");
				if (!check_mysql_version())
				{
					echo('<p class="gen">' . $lang['Old_MySQL_Version'] . '</p>' . "\n");
					break;
				}
				lock_db();
				echo('<p class="gen"><b>' . $lang['Repairing_tables'] . ":</b></p>\n");
				echo("<div class=\"post-text\"><ul>\n");
				$list_open = true;

				for($i = 0; $i < sizeof($tables); $i++)
				{
					$tablename = $table_prefix . $tables[$i];
					$sql = "REPAIR TABLE $tablename";
					$db->sql_return_on_error(true);
					$result = $db->sql_query($sql);
					$db->sql_return_on_error(false);
					if (!$result)
					{
						throw_error("Couldn't repair table!", __LINE__, __FILE__, $sql);
					}
					if ($row = $db->sql_fetchrow($result))
					{
						if ($row['Msg_type'] == 'status')
						{
							echo("<li>$tablename: " . $lang['Table_OK'] . "</li>\n");
						}
						else //  We got an error
						{
							// Check whether the error results from HEAP-table type
							$sql2 = "SHOW TABLE STATUS LIKE '$tablename'";
							$result2 = $db->sql_query($sql2);
							$row2 = $db->sql_fetchrow($result2);
							if ((isset($row2['Type']) && $row2['Type'] == 'HEAP') || (isset($row2['Engine']) && ($row2['Engine'] == 'HEAP' || $row2['Engine'] == 'MEMORY')))
							{
								// Table is from HEAP-table type
								echo("<li>$tablename: " . $lang['Table_HEAP_info'] . "</li>\n");
							}
							else
							{
								echo("<li><b>$tablename:</b> " . htmlspecialchars($row['Msg_text']) . "</li>\n");
							}
							$db->sql_freeresult($result2);
						}
					}
					$db->sql_freeresult($result);
				}
				echo("</ul></div>\n");
				$list_open = false;
				lock_db(true);
				break;

			case 'optimize_db': // Optimize database
				echo('<h1>' . $lang['Optimizing_db'] . '</h1>' . "\n");
				if (!check_mysql_version())
				{
					echo('<p class="gen">' . $lang['Old_MySQL_Version'] . '</p>' . "\n");
					break;
				}
				lock_db();
				$old_stat = get_table_statistic();
				echo('<p class="gen"><b>' . $lang['Optimizing_tables'] . ":</b></p>\n");
				echo("<div class=\"post-text\"><ul>\n");
				$list_open = true;

				for($i = 0; $i < sizeof($tables); $i++)
				{
					$tablename = $table_prefix . $tables[$i];
					$sql = "OPTIMIZE TABLE $tablename";
					$db->sql_return_on_error(true);
					$result = $db->sql_query($sql);
					$db->sql_return_on_error(false);
					if (!$result)
					{
						throw_error("Couldn't optimize table!", __LINE__, __FILE__, $sql);
					}
					if ($row = $db->sql_fetchrow($result))
					{
						if ($row['Msg_type'] == 'status')
						{
							echo("<li>$tablename: " . $lang['Table_OK'] . "</li>\n");
						}
						else //  We got an error
						{
							// Check whether the error results from HEAP-table type
							$sql2 = "SHOW TABLE STATUS LIKE '$tablename'";
							$result2 = $db->sql_query($sql2);
							$row2 = $db->sql_fetchrow($result2);
							if ((isset($row2['Type']) && $row2['Type'] == 'HEAP') || (isset($row2['Engine']) && ($row2['Engine'] == 'HEAP' || $row2['Engine'] == 'MEMORY')))
							{
								// Table is from HEAP-table type
								echo("<li>$tablename: " . $lang['Table_HEAP_info'] . "</li>\n");
							}
							else
							{
								echo("<li><b>$tablename:</b> " . htmlspecialchars($row['Msg_text']) . "</li>\n");
							}
							$db->sql_freeresult($result2);
						}
					}
					$db->sql_freeresult($result);
				}
				echo("</ul></div>\n");
				$list_open = false;
				$new_stat = get_table_statistic();
				$reduction_absolute = $old_stat['core']['size'] - $new_stat['core']['size'];
				$reduction_percent = ($reduction_absolute / $old_stat['core']['size']) * 100;
				echo('<p class="gen">' . sprintf($lang['Optimization_statistic'], convert_bytes($old_stat['core']['size']), convert_bytes($new_stat['core']['size']), convert_bytes($reduction_absolute), $reduction_percent) . '</b></p>' . "\n");
				lock_db(true);
				break;

			case 'reset_auto_increment': // Reset autoincrement values
				echo('<h1>' . $lang['Reset_ai'] . '</h1>' . "\n");
				lock_db();
				echo('<p class="gen"><b>' . $lang['Reset_ai'] . "...</b></p>\n");
				echo("<div class=\"post-text\"><ul>\n");

				set_autoincrement(BANLIST_TABLE, 'ban_id', 8);
				set_autoincrement(DISALLOW_TABLE, 'disallow_id', 8);
				set_autoincrement(PRUNE_TABLE, 'prune_id', 8);
				set_autoincrement(GROUPS_TABLE, 'group_id', 8, false);
				set_autoincrement(POSTS_TABLE, 'post_id', 8);
				set_autoincrement(PRIVMSGS_TABLE, 'privmsgs_id', 8);
				set_autoincrement(RANKS_TABLE, 'rank_id', 5);
				set_autoincrement(SEARCH_WORD_TABLE, 'word_id', 8);
				set_autoincrement(SMILIES_TABLE, 'smilies_id', 5);
				set_autoincrement(THEMES_TABLE, 'themes_id', 8);
				set_autoincrement(TOPICS_TABLE, 'topic_id', 8);
				set_autoincrement(WORDS_TABLE, 'word_id', 8);

				echo("</ul></div>\n");
				$list_open = false;

				lock_db(true);
				break;

			case 'heap_convert': // Convert session table to HEAP
				echo('<h1>' . $lang['Reset_ai'] . '</h1>' . "\n");
				if (!check_mysql_version())
				{
					echo('<p class="gen">' . $lang['Old_MySQL_Version'] . '</p>' . "\n");
					break;
				}
				lock_db();
				echo('<p class="gen"><b>' . $lang['Converting_heap'] . "...</b></p>\n");

				// First check for current table size
				$sql = "SELECT Count(*) as count FROM " . SESSIONS_TABLE;
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't get session data!", __LINE__, __FILE__, $sql);
				}
				if (!($row = $db->sql_fetchrow($result)))
				{
					throw_error("Couldn't get session data!", __LINE__, __FILE__, $sql);
				}
				if (intval($row['count']) > HEAP_SIZE)
				{
					// Table is to big - so delete some records
					$sql = "DELETE FROM " . SESSIONS_TABLE . "
						WHERE session_id != '" . $user->data['session_id'] . "'";
					if (SQL_LAYER == 'mysql4')
					{
						// When using MySQL 4: delete only the oldest records
						$sql .= " ORDER BY session_start
							LIMIT " . (intval($row['count']) - HEAP_SIZE);
					}
					$db->sql_return_on_error(true);
					$result = $db->sql_query($sql);
					$db->sql_return_on_error(false);
					if (!$result)
					{
						throw_error("Couldn't delete session data!", __LINE__, __FILE__, $sql);
					}
				}

				$sql = "ALTER TABLE " . SESSIONS_TABLE . "
					TYPE=HEAP MAX_ROWS=" . HEAP_SIZE;
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result)
				{
					throw_error("Couldn't convert table!", __LINE__, __FILE__, $sql);
				}

				lock_db(true);
				break;

			case 'unlock_db': // Unlock the database
				echo('<h1>' . $lang['Unlocking_db'] . '</h1>' . "\n");
				lock_db(true, true, true);
				break;
			default:
				echo('<p class="gen">' . $lang['function_unknown'] . '</p>' . "\n");
		}
		echo('<p class="gen"><a href="' . append_sid('admin_db_maintenance.' . PHP_EXT) . '">' . $lang['Back_to_DB_Maintenance'] . "</a></p>\n");
		// Send Information about processing time
		echo('<p class="gensmall">' . sprintf($lang['Processing_time'], getmicrotime() - $timer) . '</p>');
		ob_start();
		break;
	default:
		$template->set_filenames(array('body' => ADM_TPL . 'dbmtnc_list_body.tpl'));

		$template->assign_vars(array(
			'L_DBMTNC_TITLE' => $lang['DB_Maintenance'],
			'L_DBMTNC_TEXT' => $lang['DB_Maintenance_Description'],
			'L_FUNCTION' => $lang['Function'],
			'L_FUNCTION_DESCRIPTION' => $lang['Function_Description']
			)
		);

		//
		// OK, let's list the functions
		//
		for($i = 0; $i < sizeof($mtnc); $i++)
		{
			if (sizeof($mtnc[$i]) && check_condition($mtnc[$i][4]))
			{
				if ($mtnc[$i][0] == '--')
				{
					$template->assign_block_vars('function.spaceRow', array());
				}
				else
				{
					$template->assign_block_vars('function', array(
						'FUNCTION_NAME' => $mtnc[$i][1],
						'FUNCTION_DESCRIPTION' => $mtnc[$i][2],

						'U_FUNCTION_URL' => append_sid('admin_db_maintenance.' . PHP_EXT . '?mode=start&function=' . $mtnc[$i][0]))
					);
				}
			};
		}

		$template->pparse('body');
		break;
}

include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);

?>