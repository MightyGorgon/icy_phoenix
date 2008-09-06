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

if (!defined('IN_PHPBB'))
{
	die('Hacking attempt');
}

//error_reporting(E_ALL ^ E_NOTICE); // Report all errors, except notices
error_reporting(E_ERROR | E_WARNING | E_PARSE); // This will NOT report uninitialized variables

// The following code (unsetting globals)
// Thanks to Matt Kavanagh and Stefan Esser for providing feedback as well as patch files

/*
* Remove variables created by register_globals from the global scope
* Thanks to Matt Kavanagh
*/
function deregister_globals()
{
	$not_unset = array(
		'GLOBALS'			=> true,
		'_GET'				=> true,
		'_POST'				=> true,
		'_COOKIE'			=> true,
		'_REQUEST'		=> true,
		'_SERVER'			=> true,
		'_SESSION'		=> true,
		'_ENV'				=> true,
		'_FILES'			=> true,
		'phpbb_root_path'	=> true,
		'phpEx'				=> true,
		'no_page_header'	=> true,
		'starttime'		=> true,
		'base_memory_usage'	=> true,
	);

	// Not only will array_merge and array_keys give a warning if
	// a parameter is not an array, array_merge will actually fail.
	// So we check if _SESSION has been initialised.
	if (!isset($_SESSION) || !is_array($_SESSION))
	{
		$_SESSION = array();
	}

	// Merge all into one extremely huge array; unset this later
	$input = array_merge(
		array_keys($_GET),
		array_keys($_POST),
		array_keys($_COOKIE),
		array_keys($_SERVER),
		array_keys($_SESSION),
		array_keys($_ENV),
		array_keys($_FILES)
	);

	foreach ($input as $varname)
	{
		if (isset($not_unset[$varname]))
		{
			// Hacking attempt. No point in continuing unless it's a COOKIE
			if ($varname !== 'GLOBALS' || isset($_GET['GLOBALS']) || isset($_POST['GLOBALS']) || isset($_SERVER['GLOBALS']) || isset($_SESSION['GLOBALS']) || isset($_ENV['GLOBALS']) || isset($_FILES['GLOBALS']))
			{
				exit;
			}
			else
			{
				$cookie = &$_COOKIE;
				while (isset($cookie['GLOBALS']))
				{
					foreach ($cookie['GLOBALS'] as $registered_var => $value)
					{
						if (!isset($not_unset[$registered_var]))
						{
							unset($GLOBALS[$registered_var]);
						}
					}
					$cookie = &$cookie['GLOBALS'];
				}
			}
		}
		unset($GLOBALS[$varname]);
	}
	unset($input);
}

// If we are on PHP >= 6.0.0 we do not need some code
if (version_compare(PHP_VERSION, '6.0.0-dev', '>='))
{
	define('STRIP', false);
}
else
{
	set_magic_quotes_runtime(0); // Disable magic_quotes_runtime

	// Be paranoid with passed vars
	if (@ini_get('register_globals') == '1' || strtolower(@ini_get('register_globals')) == 'on' || !function_exists('ini_get'))
	{
		deregister_globals();
	}

	define('STRIP', (get_magic_quotes_gpc()) ? true : false);
}

// CrackerTracker v5.x
// Uncomment the following define to disable CT GET and POST parsing.
//define('MG_KILL_CTRACK', true);
include($phpbb_root_path . 'ctracker/engines/ct_security.' . $phpEx);
// CrackerTracker v5.x

// Protect against GLOBALS tricks
if (isset($_POST['GLOBALS']) || isset($_FILES['GLOBALS']) || isset($_GET['GLOBALS']) || isset($_COOKIE['GLOBALS']))
{
	die('Hacking attempt');
}

// Protect against SESSION tricks
if (isset($_SESSION) && !is_array($_SESSION))
{
	die('Hacking attempt');
}

//
// addslashes to vars if magic_quotes_gpc is off
// this is a security precaution to prevent someone
// trying to break out of a SQL statement.
//
if(!STRIP)
{
	if(is_array($_GET))
	{
		while(list($k, $v) = each($_GET))
		{
			if(is_array($_GET[$k]))
			{
				while(list($k2, $v2) = each($_GET[$k]))
				{
					$_GET[$k][$k2] = addslashes($v2);
				}
				@reset($_GET[$k]);
			}
			else
			{
				$_GET[$k] = addslashes($v);
			}
		}
		@reset($_GET);
	}

	if(is_array($_POST))
	{
		while(list($k, $v) = each($_POST))
		{
			if(is_array($_POST[$k]))
			{
				while(list($k2, $v2) = each($_POST[$k]))
				{
					$_POST[$k][$k2] = addslashes($v2);
				}
				@reset($_POST[$k]);
			}
			else
			{
				$_POST[$k] = addslashes($v);
			}
		}
		@reset($_POST);
	}

	if(is_array($_COOKIE))
	{
		while(list($k, $v) = each($_COOKIE))
		{
			if(is_array($_COOKIE[$k]))
			{
				while(list($k2, $v2) = each($_COOKIE[$k]))
				{
					$_COOKIE[$k][$k2] = addslashes($v2);
				}
				@reset($_COOKIE[$k]);
			}
			else
			{
				$_COOKIE[$k] = addslashes($v);
			}
		}
		@reset($_COOKIE);
	}
}

//
// Define some basic configuration arrays this also prevents
// malicious rewriting of language and other array values via
// URI params
//
$board_config = array();
$xs_news_config = array();
$userdata = array();
$theme = array();
$images = array();
$lang = array();
$nav_links = array();
$dss_seeded = false;
$gen_simple_header = false;
//<!-- BEGIN Unread Post Information to Database Mod -->
$unread = array();
//<!-- END Unread Post Information to Database Mod -->

include($phpbb_root_path . 'config.' . $phpEx);

if(!defined('PHPBB_INSTALLED'))
{
	header('Location: ' . $phpbb_root_path . 'install/install.' . $phpEx);
	exit;
}

include($phpbb_root_path . 'includes/constants.' . $phpEx);
if ($non_xs)
{
	include($phpbb_root_path . 'includes/phpbb_template.' . $phpEx);
}
else
{
	include($phpbb_root_path . 'includes/template.' . $phpEx);
}
include($phpbb_root_path . 'includes/sessions.' . $phpEx);
include($phpbb_root_path . 'includes/auth.' . $phpEx);
include($phpbb_root_path . 'includes/functions_categories_hierarchy.' . $phpEx);
if (defined('IN_ADMIN'))
{
	include_once($phpbb_root_path . 'includes/functions_extra.' . $phpEx);
}
include($phpbb_root_path . 'includes/functions.' . $phpEx);
include($phpbb_root_path . 'includes/db.' . $phpEx);
// MG Cash MOD For IP - BEGIN
if (defined('CASH_MOD') && defined('IN_CASHMOD'))
{
	include($phpbb_root_path . 'includes/functions_cash.' . $phpEx);
}
// MG Cash MOD For IP - END
// We do not need this any longer, unset for safety purposes
unset($dbpasswd);

//
// Obtain and encode users IP
//
// I'm removing HTTP_X_FORWARDED_FOR ... this may well cause other problems such as
// private range IP's appearing instead of the guilty routable IP, tough, don't
// even bother complaining ... go scream and shout at the idiots out there who feel
// "clever" is doing harm rather than good ... karma is a great thing ... :)
//
$client_ip = (!empty($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : ((!empty($_ENV['REMOTE_ADDR'])) ? $_ENV['REMOTE_ADDR'] : getenv('REMOTE_ADDR'));
$user_ip = encode_ip($client_ip);
$user_agent = (!empty($_SERVER['HTTP_USER_AGENT']) ? trim($_SERVER['HTTP_USER_AGENT']) : (!empty($_ENV['HTTP_USER_AGENT']) ? trim($_ENV['HTTP_USER_AGENT']) : trim(getenv('HTTP_USER_AGENT'))));

// CrackerTracker v5.x
include($phpbb_root_path . 'ctracker/engines/ct_varsetter.' . $phpEx);
include($phpbb_root_path . 'ctracker/engines/ct_ipblocker.' . $phpEx);
// CrackerTracker v5.x

// Setup site wide options, if this fails then we output a CRITICAL_ERROR since basic forum information is not available

//BEGIN Getting Board Configuration
$sql = "SELECT * FROM " . CONFIG_TABLE;
if (CACHE_CFG == true)
{
	if(!($result = $db->sql_query($sql, false, 'config_')))
	{
		message_die(CRITICAL_ERROR, 'Could not query config information', '', __LINE__, __FILE__, $sql);
	}
}
else
{
	if(!($result = $db->sql_query($sql)))
	{
		message_die(CRITICAL_ERROR, 'Could not query config information', '', __LINE__, __FILE__, $sql);
	}
}
while ($row = $db->sql_fetchrow($result))
{
	$board_config[$row['config_name']] = $row['config_value'];
}
$db->sql_freeresult($result);
//END Getting Board Configuration

// Time Management - BEGIN
// PARSE DATEFORMAT TO GET TIME FORMAT
$time_reg = '([gh][[:punct:][:space:]]{1,2}[i][[:punct:][:space:]]{0,2}[a]?[[:punct:][:space:]]{0,2}[S]?)';
eregi($time_reg, $board_config['default_dateformat'], $regs);
$board_config['default_timeformat'] = $regs[1];
unset($time_reg);
unset($regs);

// GET THE TIME TODAY AND YESTERDAY
$today_ary = explode('|', create_date('m|d|Y', time(), $board_config['board_timezone']));
$board_config['time_today'] = gmmktime(0 - $board_config['board_timezone'] - $board_config['summer_time'], 0, 0, $today_ary[0], $today_ary[1], $today_ary[2]);
$board_config['time_yesterday'] = $board_config['time_today'] - 86400;
unset($today_ary);
// Time Management - END

include($phpbb_root_path . ATTACH_MOD_PATH . 'attachment_mod.' . $phpEx);

//<!-- BEGIN Unread Post Information to Database Mod -->
if ($board_config['global_disable_upi2db'] == false)
{
	include($phpbb_root_path . 'includes/functions_upi2db.' . $phpEx);
}
else
{
	$board_config['upi2db_on'] = '0';
}
//<!-- END Unread Post Information to Database Mod -->

if ($board_config['disable_referrers'] == false)
{
	include_once($phpbb_root_path . 'includes/functions_referrers.' . $phpEx);
}

// MG Logs - BEGIN
if ($board_config['mg_log_actions'] == true)
{
	include($phpbb_root_path . 'includes/functions_mg_log.' . $phpEx);
}
// MG Logs - END

if (($board_config['url_rw'] == true) || ($board_config['url_rw_guests'] == true))
{
	include($phpbb_root_path . 'includes/functions_rewrite.' . $phpEx);
}

// Mighty Gorgon - Change Lang/Style - Begin
if (isset($_GET[LANG_URL]) || isset($_POST[LANG_URL]))
{
	$language = urldecode((isset($_GET[LANG_URL])) ? $_GET[LANG_URL] : $_POST[LANG_URL]);
	$language = strtr($language, array_flip(get_html_translation_table(HTML_ENTITIES)));
	$language = htmlspecialchars($language);
	$board_config['default_lang'] = $language;
	setcookie($board_config['cookie_name'] . '_lang', $board_config['default_lang'], (time() + 86400), $board_config['cookie_path'], $board_config['cookie_domain'], $board_config['cookie_secure']);
}
else
{
	if (isset($_COOKIE[$board_config['cookie_name'] . '_lang']))
	{
		$board_config['default_lang'] = $_COOKIE[$board_config['cookie_name'] . '_lang'];
	}
}

if (isset($_GET[STYLE_URL]) || isset($_POST[STYLE_URL]))
{
	$old_style = $board_config['default_style'];
	$board_config['default_style'] = urldecode((isset($_GET[STYLE_URL])) ? intval($_GET[STYLE_URL]) : intval($_POST[STYLE_URL]));
	$board_config['default_style'] = (check_style_exists($board_config['default_style']) == false) ? $old_style : $board_config['default_style'];
	setcookie($board_config['cookie_name'] . '_style', $board_config['default_style'], (time() + 86400), $board_config['cookie_path'], $board_config['cookie_domain'], $board_config['cookie_secure']);
}
else
{
	if (isset($_COOKIE[$board_config['cookie_name'] . '_style']) && (check_style_exists($_COOKIE[$board_config['cookie_name'] . '_style']) != false))
	{
		$board_config['default_style'] = $_COOKIE[$board_config['cookie_name'] . '_style'];
	}
}
// Mighty Gorgon - Change Lang/Style - End

if (file_exists('install'))
{
	message_die(GENERAL_MESSAGE, 'Please_remove_install_contrib');
}

if ($board_config['admin_protect'] == true)
{
	$main_admin_id = (intval($board_config['main_admin_id']) >= 2) ? $board_config['main_admin_id'] : '2';
	if ($main_admin_id != '2')
	{
		$sql = "SELECT user_id
			FROM " . USERS_TABLE . "
			WHERE user_id = '" . $main_admin_id . "'
			LIMIT 1";
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Couldn\'t obtain user id', '', __LINE__, __FILE__, $sql);
		}
		if ($row = $db->sql_fetchrow($result))
		{
			$main_admin_id = $row['user_id'];
			$db->sql_freeresult($result);
		}
		else
		{
			$main_admin_id = '2';
		}
	}
	// Activate Main Admin Account
	$sql = "UPDATE " . USERS_TABLE . "
		SET user_active = 1
	WHERE user_id = '" . $main_admin_id . "'";
	if (!$db->sql_query($sql))
	{
		message_die(GENERAL_MESSAGE, 'Unable to access the Users Table.');
	}

	// Delete Main Admin Ban
	$sql = "DELETE FROM " . BANLIST_TABLE . "
		WHERE ban_userid = '" . $main_admin_id . "'";
	if (!$db->sql_query($sql))
	{
		message_die(GENERAL_MESSAGE, 'Unable to access the Banlist Table.');
	}
	$db->clear_cache('ban_');
}

if (intval($_GET['lofi']) || $_COOKIE['lofi'])
{
	$lofi = 1;
}

?>