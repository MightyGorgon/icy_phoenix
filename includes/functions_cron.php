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

/*
// Shall we keep this?
	$sql = "UPDATE " . CONFIG_TABLE . "
		SET config_value = '0'
		WHERE config_name = 'cron_lock'
			AND config_value = '" . $db->sql_escape(CRON_ID) . "'";
	$db->sql_query($sql);
*/
	set_config('cron_lock', 0);
	set_config('cron_lock_hour', 0);

	$cache->destroy('config');
}

/**
* Process Digests
*/
function process_digests()
{
	global $db, $cache, $config, $auth, $user, $lang, $bbcode;
	global $template, $images, $theme, $table_prefix;

	// Digests - BEGIN
	if (!defined('DIGEST_SITE_URL'))
	{
		$digest_server_url = create_server_url();
		define('DIGEST_SITE_URL', $digest_server_url);
	}

	if (!empty($config['cron_digests_interval']) && ($config['cron_digests_interval'] > 0))
	{
		// MG PHP Cron Emulation For Digests - BEGIN
		$page_url = pathinfo($_SERVER['SCRIPT_NAME']);
		$digests_pages_array = array(CMS_PAGE_PROFILE, CMS_PAGE_POSTING);
		if (empty($config['cron_lock_hour']) && !in_array($page_url['basename'], $digests_pages_array))
		{
			if ((time() - $config['cron_digests_last_run']) > CRON_REFRESH)
			{
				$config['cron_digests_last_run'] = empty($config['cron_digests_last_run']) ? (time() - 3600) : $config['cron_digests_last_run'];
				$last_send_time = @getdate($config['cron_digests_last_run']);
				$cur_time = @getdate();
				if (!empty($config['cron_digests_interval']) && ($config['cron_digests_interval'] > 0) && ($cur_time['hours'] != $last_send_time['hours']))
				{
					$cron_lock_hour_id = defined('CRON_ID') ? CRON_ID : 1;
					set_config('cron_lock_hour', $cron_lock_hour_id);
					define('PHP_DIGESTS_CRON', true);
					define('PHP_DIGESTS_FUNCTIONS_CRON', true);
					include_once(IP_ROOT_PATH . 'mail_digests.' . PHP_EXT);
				}
			}
		}
		// MG PHP Cron Emulation For Digests - END
	}
	// Digests - END

	if (CRON_DEBUG == false)
	{
		set_config('cron_digests_last_run', time());
	}
}

/**
* Process Birthdays
*/
function process_birthdays()
{
	global $db, $cache, $config, $auth, $user, $lang, $bbcode;
	global $template, $images, $table_prefix;

	if (!empty($config['cron_birthdays_interval']))
	{
		// MG PHP Cron Emulation For Birthdays - BEGIN
		$page_url = pathinfo($_SERVER['SCRIPT_NAME']);
		$birthdays_pages_array = array(CMS_PAGE_PROFILE, CMS_PAGE_POSTING);
		if (empty($config['cron_lock_hour']) && !in_array($page_url['basename'], $birthdays_pages_array))
		{
			if ((time() - $config['cron_birthdays_last_run']) > CRON_REFRESH)
			{
				$config['cron_birthdays_last_run'] = ($config['cron_birthdays_last_run'] == 0) ? (time() - 3600) : $config['cron_birthdays_last_run'];
				$last_send_time = @getdate($config['cron_birthdays_last_run']);
				$cur_time = @getdate();
				if (!empty($config['cron_birthdays_interval']) && ($config['cron_birthdays_interval'] > 0) && ($cur_time['hours'] != $last_send_time['hours']))
				{
					$cron_lock_hour_id = defined('CRON_ID') ? CRON_ID : 1;
					set_config('cron_lock_hour', $cron_lock_hour_id);
					if (!function_exists('birthday_email_send'))
					{
						include_once(IP_ROOT_PATH . 'includes/functions_users.' . PHP_EXT);
					}
					birthday_email_send();
				}
			}
		}
		// MG PHP Cron Emulation For Birthdays - END
	}

	if (CRON_DEBUG == false)
	{
		set_config('cron_birthdays_last_run', time());
	}
}

/**
* Process Files
*/
function process_files()
{
	global $db, $cache, $config, $user;

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
	global $db, $cache, $config, $user;
	global $dbname;

	$is_allowed = true;
	// If you want to assign the extra SQL charge to non registered users only, decomment this line... ;-)
	$is_allowed = (!$user->data['session_logged_in']) ? true : false;

	if ($is_allowed)
	{
		$current_time = time();

		// Tables optimization
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

		// Here we check permission consistency
		// Sometimes, it can happen permission tables having forums listed which do not exist
		$sql = 'SELECT forum_id
			FROM ' . FORUMS_TABLE;
		$result = $db->sql_query($sql);

		$forum_ids = array(0);
		while ($row = $db->sql_fetchrow($result))
		{
			$forum_ids[] = $row['forum_id'];
		}
		$db->sql_freeresult($result);

		// Delete those rows from the acl tables not having listed the forums above
		$sql = 'DELETE FROM ' . ACL_GROUPS_TABLE . '
			WHERE ' . $db->sql_in_set('forum_id', $forum_ids, true);
		$db->sql_query($sql);

		$sql = 'DELETE FROM ' . ACL_USERS_TABLE . '
			WHERE ' . $db->sql_in_set('forum_id', $forum_ids, true);
		$db->sql_query($sql);

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
	global $db, $cache, $config;

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
	global $db, $cache, $config;

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
	global $db, $cache, $config;

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
	global $db, $cache, $config;

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
	global $db, $cache, $config, $auth, $user;

	$user->session_gc();

	$current_time = time();

	if (CRON_DEBUG == false)
	{
		set_config('session_last_gc', $current_time);
		set_config('cron_sessions_last_run', $current_time);
	}
}

?>