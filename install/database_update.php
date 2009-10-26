<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

// CTracker_Ignore: File Checked By Human
define('IN_INSTALL', true);
define('IN_ICYPHOENIX', true);
define('IP_DB_UPDATE', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
define('THIS_PATH', '_install/');
define('THIS_FILE', 'database_update.' . PHP_EXT);
//define('THIS_FILE', basename(__FILE__));
require('includes/functions_install.' . PHP_EXT);
require('schemas/versions.' . PHP_EXT);
$ip_functions = new ip_functions();
$ip_sql = new ip_sql();

// Open config.php... if it exists
if (@file_exists(@$ip_functions->ip_realpath(IP_ROOT_PATH . 'config.' . PHP_EXT)))
{
	include(IP_ROOT_PATH . 'config.' . PHP_EXT);
}

// Check if Icy Phoenix or phpBB are already installed
if (defined('IP_INSTALLED') || defined('PHPBB_INSTALLED'))
{
	if (empty($userdata) || !$userdata['session_logged_in'])
	{
		define('BASIC_COMMON', true);
		require('common.' . PHP_EXT);
		$table_prefix = ($table_prefix == '') ? 'phpbb_' : $table_prefix;
	}
	else
	{
		// phpBB only - BEGIN
		// No need to add an IF because these vars won't damage anything if we are in Icy Phoenix ;-)
		define('IN_PHPBB', true);
		$phpbb_root_path = IP_ROOT_PATH;
		$phpEx = PHP_EXT;
		// phpBB only - END
		include(IP_ROOT_PATH . 'common.' . PHP_EXT);

		// Start session management
		$userdata = session_pagestart($user_ip, 0);
		init_userprefs($userdata);
		// End session management

		if (defined('IP_INSTALLED'))
		{
			$founder_id = (defined('FOUNDER_ID') ? FOUNDER_ID : get_founder_id());
			if ($userdata['user_id'] != $founder_id)
			{
				message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
			}
		}
		else
		{
			if ($userdata['user_level'] != ADMIN)
			{
				// We need to use $lang['Not_Authorized'] because the $lang['Not_Auth_View'] isn't available in standard phpBB
				message_die(GENERAL_MESSAGE, $lang['Not_Authorized']);
			}
		}
		@set_time_limit(0);
		$mem_limit = $ip_functions->check_mem_limit();
		@ini_set('memory_limit', $mem_limit);
		$language = $config['default_lang'];
		$lang_request = $ip_functions->request_var('lang', '');
		if (!empty($lang_request) && preg_match('#^[a-z_]+$#', $lang_request))
		{
			$language = strip_tags($lang_request);
		}
	}
	include('language/lang_' . $language . '/lang_install.' . PHP_EXT);
	$current_ip_version = $ip_sql->get_config_value('ip_version');
	// Check that IP is installed, otherwise you don't need this table
	if (!empty($current_ip_version) && !defined('CMS_LAYOUT_TABLE'))
	{
		define('CMS_LAYOUT_TABLE', $table_prefix . 'cms_layout');
	}
	if (!empty($current_ip_version) && !defined('ALBUM_TABLE'))
	{
		define('ALBUM_TABLE', $table_prefix . 'album');
	}
	$current_phpbb_version = $ip_sql->get_config_value('version');
	$page_framework = new ip_page();

	require('includes/ip_tools.' . PHP_EXT);
	exit;
	/*
	$page_framework->page_header('Icy Phoenix', '', false, false);
	$page_framework->stats_box($current_ip_version, $current_phpbb_version);
	$page_framework->box_upgrade_info();
	$page_framework->page_footer(false);
	exit;
	*/
}

?>