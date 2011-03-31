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

// Report all errors, except notices
error_reporting(E_ALL ^ E_NOTICE);

// @todo Review this test and see if we can find out what it is which prevents PHP 4.2.x from even displaying the page with requirements on it
if (version_compare(PHP_VERSION, '4.3.3') < 0)
{
	die('You are running an unsupported PHP version. Please upgrade to PHP 4.3.3 or higher before trying to install Icy Phoenix');
}

// If we are on PHP >= 6.0.0 we do not need some code
if (version_compare(PHP_VERSION, '6.0.0-dev', '>='))
{
	define('STRIP', false);
}
else
{
	@set_magic_quotes_runtime(0);
	if (@ini_get('register_globals') == '1' || strtolower(@ini_get('register_globals')) == 'on')
	{
		$ip_functions->deregister_globals();
	}
	define('STRIP', (@get_magic_quotes_gpc()) ? true : false);
}

// Try to override some limits - maybe it helps on some servers...
@set_time_limit(0);
$mem_limit = $ip_functions->check_mem_limit();
@ini_set('memory_limit', $mem_limit);

$lang_request = $ip_functions->request_var('lang', '');
if (!empty($lang_request) && preg_match('#^[a-z_]+$#', $lang_request))
{
	$language = strip_tags($lang_request);
}
else
{
	$language = $ip_functions->guess_lang();
}

if (defined('BASIC_COMMON'))
{
	// extension.inc replaced by its content
	//include(IP_ROOT_PATH . 'extension.inc');
	// extension.inc - BEGIN
	$starttime = 0;
	// extension.inc - END
	include(IP_ROOT_PATH . 'config.' . PHP_EXT);
	include('includes/constants.' . PHP_EXT);
	include('includes/functions.' . PHP_EXT);
	include('includes/db.' . PHP_EXT);
	//include('includes/utf/utf_tools.' . PHP_EXT);
	/*
	include(IP_ROOT_PATH . 'includes/db.' . PHP_EXT);
	*/
	include(IP_ROOT_PATH . 'includes/utf/utf_tools.' . PHP_EXT);
}

if (defined('INSTALLING_ICYPHOENIX'))
{
	// Initialise some basic arrays
	$user = array();
	$lang = array();
	$error = false;

	// Define schema info
	$available_dbms = array(
		'mysql4' => array(
			'LABEL'				=> 'MySQL 4.x/5.x',
			'SCHEMA'			=> 'mysql',
			'DELIM'				=> ';',
			'DELIM_BASIC'	=> ';',
			'COMMENTS'		=> 'remove_remarks'
		),
		'mysql'=> array(
			'LABEL'				=> 'MySQL 3.x',
			'SCHEMA'			=> 'mysql',
			'DELIM'				=> ';',
			'DELIM_BASIC'	=> ';',
			'COMMENTS'		=> 'remove_remarks'
		),
	);

	// Obtain various vars
	$confirm = (isset($_POST['confirm'])) ? true : false;
	$cancel = (isset($_POST['cancel'])) ? true : false;

	$install_step = $ip_functions->request_var('install_step', 0);
	$upgrade = $ip_functions->request_var('upgrade', '');
	$upgrade_now = $ip_functions->request_var('install_step', '');

	$dbms = $ip_functions->request_var('dbms', '');
	$dbhost = $ip_functions->request_var('dbhost', 'localhost');
	$dbuser = $ip_functions->request_var('dbuser', '');
	$dbpasswd = $ip_functions->request_var('dbpasswd', '');
	$dbname = $ip_functions->request_var('dbname', '');

	$table_prefix = $ip_functions->request_var('prefix', '');

	$admin_name = $ip_functions->request_var('admin_name', '');
	$admin_pass1 = $ip_functions->request_var('admin_pass1', '');
	$admin_pass2 = $ip_functions->request_var('admin_pass2', '');

	$ftp_path = $ip_functions->request_var('ftp_path', '');
	$ftp_user = $ip_functions->request_var('ftp_user', '');
	$ftp_pass = $ip_functions->request_var('ftp_pass', '');

	$board_email = $ip_functions->request_var('board_email', '');
	$script_path = $ip_functions->request_var('script_path', str_replace('install', '', dirname($_SERVER['SCRIPT_NAME'])));

	if (!empty($_POST['server_name']))
	{
		$server_name = $_POST['server_name'];
	}
	else
	{
		// Guess at some basic info used for install...
		if (!empty($_SERVER['SERVER_NAME']) || !empty($_ENV['SERVER_NAME']))
		{
			$server_name = (!empty($_SERVER['SERVER_NAME'])) ? $_SERVER['SERVER_NAME'] : $_ENV['SERVER_NAME'];
		}
		elseif (!empty($_SERVER['HTTP_HOST']) || !empty($_ENV['HTTP_HOST']))
		{
			$server_name = (!empty($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST'] : $_ENV['HTTP_HOST'];
		}
		else
		{
			$server_name = '';
		}
	}

	if (!empty($_POST['server_port']))
	{
		$server_port = $_POST['server_port'];
	}
	else
	{
		if (!empty($_SERVER['SERVER_PORT']) || !empty($_ENV['SERVER_PORT']))
		{
			$server_port = (!empty($_SERVER['SERVER_PORT'])) ? $_SERVER['SERVER_PORT'] : $_ENV['SERVER_PORT'];
		}
		else
		{
			$server_port = '80';
		}
	}
}

?>