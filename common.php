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

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

$starttime = explode(' ', microtime());
$starttime = $starttime[1] + $starttime[0];

//@ini_set('memory_limit', '24M');

// MIGHTY GORGON - DEBUG - BEGIN
@define('DEBUG', false); // Debugging ON/OFF => TRUE/FALSE
@define('DEBUG_EXTRA', false); // Extra Debugging ON/OFF => TRUE/FALSE
$error_reporting = E_ALL ^ E_NOTICE; // Report all errors, except notices
if (defined('DEBUG_EXTRA') && DEBUG_EXTRA)
{
	$error_reporting = E_ALL; // Report all errors
	$base_memory_usage = 0;
	if (function_exists('memory_get_usage'))
	{
		$base_memory_usage = @memory_get_usage();
	}
}
error_reporting($error_reporting);
// MIGHTY GORGON - DEBUG - END

/*
* Remove variables created by register_globals from the global scope
* Thanks to Matt Kavanagh
*/
function deregister_globals()
{
	$not_unset = array(
		'GLOBALS'						=> true,
		'_GET'							=> true,
		'_POST'							=> true,
		'_COOKIE'						=> true,
		'_REQUEST'					=> true,
		'_SERVER'						=> true,
		'_SESSION'					=> true,
		'_ENV'							=> true,
		'_FILES'						=> true,
		'no_page_header'		=> true,
		'starttime'					=> true,
		'base_memory_usage'	=> true,
	);

	// Not only will array_merge and array_keys give a warning if a parameter is not an array, array_merge will actually fail.
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
			if (($varname !== 'GLOBALS') || isset($_GET['GLOBALS']) || isset($_POST['GLOBALS']) || isset($_SERVER['GLOBALS']) || isset($_SESSION['GLOBALS']) || isset($_ENV['GLOBALS']) || isset($_FILES['GLOBALS']))
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
	@set_magic_quotes_runtime(0); // Disable magic_quotes_runtime
	if (@ini_get('register_globals') == '1' || (strtolower(@ini_get('register_globals')) == 'on') || !function_exists('ini_get'))
	{
		deregister_globals();
	}
	define('STRIP', (@get_magic_quotes_gpc()) ? true : false);
}

// Load Extensions
if (!empty($load_extensions))
{
	$load_extensions = explode(',', $load_extensions);

	foreach ($load_extensions as $extension)
	{
		@dl(trim($extension));
	}
}

// CrackerTracker v5.x
// Comment the following define to enable CT GET and POST parsing.
define('GLOBAL_CTRACKER_DISABLED', true);
if(defined('IN_ADMIN') || defined('IN_CMS') || defined('CTRACKER_DISABLED') || defined('GLOBAL_CTRACKER_DISABLED'))
{
	$ct_rules = array();
	define('protection_unit_one', true);
}
else
{
	include(IP_ROOT_PATH . 'includes/ctracker/engines/ct_security.' . PHP_EXT);
}
// CrackerTracker v5.x

// Initialize some basic configuration arrays this also prevents malicious rewriting of language and other array values via URI params
$config = array();
$cms_config_layouts = array();
$user = array();
$theme = array();
$images = array();
$lang = array();
$tree = array();
$nav_links = array();
$gen_simple_header = false;
$breadcrumbs = array();

require(IP_ROOT_PATH . 'config.' . PHP_EXT);

if(!defined('IP_INSTALLED') && !defined('IN_INSTALL'))
{
	die('<p>config.' . PHP_EXT . ' could not be found.</p><p><a href="' . IP_ROOT_PATH . 'install/install.' . PHP_EXT . '">Click here to install Icy Phoenix</a></p>');
	//header('Location: ' . IP_ROOT_PATH . 'install/install.' . PHP_EXT);
	exit;
}

require(IP_ROOT_PATH . 'includes/constants.' . PHP_EXT);
require(IP_ROOT_PATH . 'includes/template.' . PHP_EXT);
require(IP_ROOT_PATH . 'includes/sessions.' . PHP_EXT);
require(IP_ROOT_PATH . 'includes/auth.' . PHP_EXT);
require(IP_ROOT_PATH . 'includes/class_auth.' . PHP_EXT);
require(IP_ROOT_PATH . 'includes/class_cache.' . PHP_EXT);
require(IP_ROOT_PATH . 'includes/class_cache_extends.' . PHP_EXT);
require(IP_ROOT_PATH . 'includes/functions.' . PHP_EXT);
require(IP_ROOT_PATH . 'includes/functions_categories_hierarchy.' . PHP_EXT);
require(IP_ROOT_PATH . 'includes/utf/utf_tools.' . PHP_EXT);
require(IP_ROOT_PATH . 'includes/class_cms.' . PHP_EXT);
require(IP_ROOT_PATH . 'includes/class_settings.' . PHP_EXT);
if (defined('IN_ADMIN'))
{
	require_once(IP_ROOT_PATH . 'includes/functions_admin.' . PHP_EXT);
}

// We need to instantiate Cache Class before DB to correctly initialize DB Connection
$cache = new ip_cache();
$class_settings = new class_settings();
$user = new user();
$auth = new auth();
$ip_cms = new ip_cms();
$ip_cms->init_vars();

require(IP_ROOT_PATH . 'includes/db.' . PHP_EXT);

// We do not need these any longer, unset for safety purpose
unset($dbuser);
unset($dbpasswd);
unset($db->password);
unset($message);
unset($highlight);
unset($sql);

// Set PHP error handler to ours
set_error_handler(defined('IP_MSG_HANDLER') ? IP_MSG_HANDLER : 'msg_handler');

// Check if we are in ACP
if ((defined('IN_ADMIN') || defined('IN_CMS')) && !defined('ACP_MODULES'))
{
	define('NEED_SID', true);
	$cache->destroy('config');
}
else
{
	if (!defined('IN_POSTING') && defined('TIME_LIMIT'))
	{
		@set_time_limit(TIME_LIMIT);
	}
}

$config = $cache->obtain_config();
$config['default_style_row'] = $cache->obtain_default_style(false);
$config['gzip_compress_runtime'] = $config['gzip_compress'];

// Obtain and encode users IP
// Removing HTTP_X_FORWARDED_FOR ... this may well cause other problems such as private range IP's appearing instead of the guilty routable IP, tough, don't even bother complaining ... go scream and shout at the idiots out there who feel "clever" is doing harm rather than good ... karma is a great thing ... :)
$user_ip = (!empty($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : ((!empty($_ENV['REMOTE_ADDR'])) ? $_ENV['REMOTE_ADDR'] : getenv('REMOTE_ADDR'));
$user_ip = (!empty($user_ip) && ($user_ip != '::1')) ? $user_ip : '127.0.0.1';

// CrackerTracker v5.x
$config['ctracker_user_ip_value'] = $user_ip;
define('protection_unit_two', true);
if (!empty($config['ctracker_ipblock_enabled']))
{
	require(IP_ROOT_PATH . 'includes/ctracker/engines/ct_ipblocker.' . PHP_EXT);
}
define('protection_unit_three', true);
// CrackerTracker v5.x

// CMS Pages Config - BEGIN
if (!defined('SKIP_CMS_CONFIG') && !defined('IN_ADMIN') && !defined('IN_CMS'))
{
	//$cms_config_layouts = get_layouts_config(true);
	$cms_config_layouts = $cache->obtain_cms_layouts_config();
}
// CMS Pages Config - END

// Plugins - BEGIN
$config['plugins'] = array();
if (!class_exists('class_plugins')) include(IP_ROOT_PATH . 'includes/class_plugins.' . PHP_EXT);
if (empty($class_plugins)) $class_plugins = new class_plugins();
foreach ($cache->obtain_plugins_config() as $k => $plugin)
{
	// don't load disabled plugins
	if (empty($plugin['plugin_enabled']))
	{
		continue;
	}
	$config['plugins'][$k]['enabled'] = !empty($plugin['plugin_enabled']) ? true : false;
	$config['plugins'][$k]['dir'] = !empty($plugin['plugin_dir']) ? ($plugin['plugin_dir'] . '/') : '';
	// Plugins autoload - BEGIN
	$plugin_dir = IP_ROOT_PATH . PLUGINS_PATH . $config['plugins'][$k]['dir'];
	foreach ($class_plugins->plugin_includes_array as $plugin_include)
	{
		$config['plugins'][$k][$plugin_include] = !empty($plugin['plugin_' . $plugin_include]) ? true : false;
		if (!empty($config['plugins'][$k][$plugin_include]))
		{
			@include_once($plugin_dir . $plugin_include . '.' . PHP_EXT);
		}
	}

	// if the plugin has a class (events, etc), register it.
	$plugin_class_name = 'class_plugin_' . $k;
	if (class_exists($plugin_class_name))
	{
		$class_plugins->register($k, new $plugin_class_name());
	}
	// Plugins autoload - END
}
// Plugins - END

@include_once(IP_ROOT_PATH . ATTACH_MOD_PATH . 'attachment_mod.' . PHP_EXT);

// UPI2DB - BEGIN
if (!empty($config['global_disable_upi2db']))
{
	$config['upi2db_on'] = 0;
}
else
{
	@include_once(IP_ROOT_PATH . 'includes/functions_upi2db.' . PHP_EXT);
}
// UPI2DB - END

// MG Logs - BEGIN
if (!empty($config['mg_log_actions']) || !empty($config['db_log_actions']))
{
	@include_once(IP_ROOT_PATH . 'includes/functions_mg_log.' . PHP_EXT);
}
// MG Logs - END

if (!empty($config['url_rw']) || !empty($config['url_rw_guests']))
{
	@include_once(IP_ROOT_PATH . 'includes/functions_rewrite.' . PHP_EXT);
}

if ((isset($_GET['lofi']) && (intval($_GET['lofi']) == 1)) || (isset($_COOKIE[$config['cookie_name'] . '_lofi']) && (intval($_COOKIE[$config['cookie_name'] . '_lofi']) == 1)))
{
	$lofi = 1;
}

/*
foreach ($cache->obtain_hooks() as $hook)
{
	@include(IP_ROOT_PATH . 'includes/hooks/' . $hook . '.' . PHP_EXT);
}
*/

?>