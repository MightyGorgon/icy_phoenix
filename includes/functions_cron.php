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
* Unlock cron script
*/
function unlock_cron()
{
	global $db;

	$sql = "UPDATE " . CONFIG_TABLE . "
		SET config_value = '0'
		WHERE config_name = 'cron_lock'
			AND config_value = '" . $db->sql_escape(CRON_ID) . "'";
	$db->sql_query($sql);
	$db->clear_cache('config_');
}

/**
* Process Digests
*/
function process_digests()
{
	global $db, $table_prefix, $board_config, $userdata;

	// Digests - BEGIN
	if ($board_config['enable_digests'] == true)
	{
		/*
		// MG PHP Cron Emulation For Digests - BEGIN
		$is_allowed = true;
		// If you want to assign the extra SQL charge to non registered users only, decomment this line... ;-)
		$is_allowed = (!$userdata['session_logged_in']) ? true : false;
		$page_url = pathinfo($_SERVER['PHP_SELF']);
		$digests_pages_array = array(PROFILE_MG, POSTING_MG);
		if (($board_config['digests_php_cron'] == true) && $is_allowed && !in_array($page_url['basename'], $digests_pages_array))
		//if (($board_config['digests_php_cron'] == true) && ($board_config['digests_php_cron_lock'] == false) && (!$userdata['session_logged_in']) && !in_array($page_url['basename'], $digests_pages_array))
		{
			if ((time() - $board_config['digests_last_send_time']) > CRON_REFRESH)
			{
				$board_config['digests_last_send_time'] = ($board_config['digests_last_send_time'] == 0) ? (time() - 3600) : $board_config['digests_last_send_time'];
				$last_send_time = getdate($board_config['digests_last_send_time']);
				$cur_time = getdate();
				if ($cur_time['hours'] <> $last_send_time['hours'])
				{
					set_config('digests_php_cron_lock', 1);
					define('PHP_DIGESTS_CRON', true);
					include_once(IP_ROOT_PATH . 'mail_digests.' . PHP_EXT);
				}
			}
		}
		// MG PHP Cron Emulation For Digests - END
		*/
	}
	// Digests - END

	if (CRON_DEBUG == false)
	{
		set_config('cron_digests_last_run', time());
	}
}

/**
* Process Files
*/
function process_files()
{
	$files_array = array();
	$files_array = explode(',', CRON_FILES);
	foreach ($files_array as $cron_file)
	{
		$cron_file_full = CRON_REAL_PATH . $cron_file;
		@include($cron_file_full);
	}
	if (CRON_DEBUG == false)
	{
		set_config('cron_files_last_run', time());
	}
}

/**
* Tidy database
*/
function tidy_database()
{
	global $dbname, $db, $board_config, $userdata;

	$is_allowed = true;
	// If you want to assign the extra SQL charge to non registered users only, decomment this line... ;-)
	$is_allowed = (!$userdata['session_logged_in']) ? true : false;

	if ($is_allowed)
	{
		$current_time = time();

		@ignore_user_abort();
		// Get tables list
		$all_tables = array();
		$sql_list = "SHOW TABLES";
		$result_list = $db->sql_query($sql_list);
		// Optimize tables
		while ($row = $db->sql_fetchrow($result_list))
		{
			$all_tables[] = $row['Tables_in_' . $dbname];
		}
		$db->sql_freeresult($result_list);

		foreach ($all_tables as $table_name)
		{
			$sql = "OPTIMIZE TABLES $table_name";
			if (!$result = $db->sql_query($sql))
			{
				// Comment the die message, because we don't want the CRON to hang...
				//message_die(GENERAL_ERROR, "Couldn't optimize database", "", __LINE__, __FILE__, $sql);
			}
		}

		if (CRON_DEBUG == false)
		{
			set_config('cron_db_count', ($board_config['cron_db_count'] + 1));
			set_config('cron_database_last_run', time());
		}
	}
}

/**
* Tidy cache
*/
function tidy_cache()
{
	empty_cache_folders(MAIN_CACHE_FOLDER);
	if (CRON_DEBUG == false)
	{
		set_config('cron_cache_last_run', time());
	}
}

/**
* Tidy sql
*/
function tidy_sql()
{
	empty_cache_folders(SQL_CACHE_FOLDER);
	if (CRON_DEBUG == false)
	{
		set_config('cron_sql_last_run', time());
	}
}

/**
* Tidy users
*/
function tidy_users()
{
	empty_cache_folders(USERS_CACHE_FOLDER);
	if (CRON_DEBUG == false)
	{
		set_config('cron_users_last_run', time());
	}
}

/**
* Tidy topics
*/
function tidy_topics()
{
	empty_cache_folders(POSTS_CACHE_FOLDER);
	empty_cache_folders(TOPICS_CACHE_FOLDER);
	empty_cache_folders(FORUMS_CACHE_FOLDER);
	if (CRON_DEBUG == false)
	{
		set_config('cron_topics_last_run', time());
	}
}

/**
* Tidy sessions
*/
function tidy_sessions()
{
	global $db;
/*
	$sql = "DELETE FROM " . SESSIONS_TABLE;
	$result = $db->sql_query($sql);
	if(!$result)
	{
		//message_die("Couldn't delete sessions table!", __LINE__, __FILE__, $sql);
	}

	$sql = "DELETE FROM " . AJAX_SHOUTBOX_SESSIONS_TABLE;
	$result = $db->sql_query($sql);
	if(!$result)
	{
		//message_die("Couldn't delete AJAX Shoutbox sessions table!", __LINE__, __FILE__, $sql);
	}

	$sql = "DELETE FROM " . SEARCH_TABLE;
	$result = $db->sql_query($sql);
	if(!$result)
	{
		//message_die("Couldn't delete search result table!", __LINE__, __FILE__, $sql);
	}
*/
	if (CRON_DEBUG == false)
	{
		set_config('cron_session_last_run', time());
	}

}

?>