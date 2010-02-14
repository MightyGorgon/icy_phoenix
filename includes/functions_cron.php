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
	global $db, $cache;

	$sql = "UPDATE " . CONFIG_TABLE . "
		SET config_value = '0'
		WHERE config_name = 'cron_lock'
			AND config_value = '" . $db->sql_escape(CRON_ID) . "'";
	$db->sql_query($sql);
	$cache->destroy('config');
}

/**
* Process Digests
*/
function process_digests()
{
	global $db, $config, $userdata, $table_prefix;

	// Digests - BEGIN
	if ($config['enable_digests'])
	{
		/*
		// MG PHP Cron Emulation For Digests - BEGIN
		$is_allowed = true;
		// If you want to assign the extra SQL charge to non registered users only, decomment this line... ;-)
		$is_allowed = (!$userdata['session_logged_in']) ? true : false;
		$page_url = pathinfo($_SERVER['SCRIPT_NAME']);
		$digests_pages_array = array(CMS_PAGE_PROFILE, CMS_PAGE_POSTING);
		if ($config['digests_php_cron'] && $is_allowed && !in_array($page_url['basename'], $digests_pages_array))
		//if ($config['digests_php_cron'] && ($config['digests_php_cron_lock'] == false) && (!$userdata['session_logged_in']) && !in_array($page_url['basename'], $digests_pages_array))
		{
			if ((time() - $config['digests_last_send_time']) > CRON_REFRESH)
			{
				$config['digests_last_send_time'] = ($config['digests_last_send_time'] == 0) ? (time() - 3600) : $config['digests_last_send_time'];
				$last_send_time = @getdate($config['digests_last_send_time']);
				$cur_time = @getdate();
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
	global $dbname, $db, $config, $userdata;

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
			$db->sql_return_on_error(true);
			$result = $db->sql_query($sql);
			$db->sql_return_on_error(false);
		}

		if (CRON_DEBUG == false)
		{
			set_config('cron_db_count', ($config['cron_db_count'] + 1));
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
	$db->sql_return_on_error(true);
	$result = $db->sql_query($sql);
	$db->sql_return_on_error(false);

	$sql = "DELETE FROM " . AJAX_SHOUTBOX_SESSIONS_TABLE;
	$db->sql_return_on_error(true);
	$result = $db->sql_query($sql);
	$db->sql_return_on_error(false);

	$sql = "DELETE FROM " . SEARCH_TABLE;
	$db->sql_return_on_error(true);
	$result = $db->sql_query($sql);
	$db->sql_return_on_error(false);
	*/

	if (CRON_DEBUG == false)
	{
		set_config('cron_session_last_run', time());
	}

}

?>