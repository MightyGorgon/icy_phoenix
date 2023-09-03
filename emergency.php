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
* Christian Knerr (cback) - (www.cback.de)
*
*/

/**
* <b>emergency.php</b>
* A small emergency console to reset the last functioning Board configuration
* or reset the domain settings. Please remember that <b>this file is not part of
* phpBB</b> so it is really important that you exactly READ the instructions
* before you use the file!
*
* @author Christian Knerr (cback)
* @package ctracker
* @version 5.0.0
* @since 16.08.2006 - 00:20:13
* @copyright (c) 2006 www.cback.de
*/

// Warning........: File is not part of phpBB itself!

/*
* Comment out the following code part to use the Emergency Console. If you stop
* working with this file please remember to block this file again!! If not
* everyone could access it and use the functions in here!
*
* If you want access the recovery console just enter the url to that file into your Browser for example:
*
* www.example.com/emergency.php
*
*
* Our suggestion is to remove this file completely from your Board if you don't need it!
*/
die("<img src=\"admin/console/console_pic.png\" border=\"0\" alt=\"ECON\" title=\"ECON\"><br /><br /><b>Emergency Console Blocked!</b><br />See more instructions in this file!");

/*
* Define some vars & constants we need
*/
define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));

error_reporting (E_ERROR | E_WARNING | E_PARSE); // This will NOT report uninitialized variables
set_magic_quotes_runtime(0); // Disable magic_quotes_runtime

// The following code (unsetting globals)
// Thanks to Matt Kavanagh and Stefan Esser for providing feedback as well as patch files

// PHP5 with register_long_arrays off?
if (@phpversion() >= '5.0.0' && (!@ini_get('register_long_arrays') || @ini_get('register_long_arrays') == '0' || strtolower(@ini_get('register_long_arrays')) == 'off'))
{
	$HTTP_POST_VARS = $_POST;
	$HTTP_GET_VARS = $_GET;
	$HTTP_SERVER_VARS = $_SERVER;
	$HTTP_COOKIE_VARS = $_COOKIE;
	$HTTP_ENV_VARS = $_ENV;
	$HTTP_POST_FILES = $_FILES;

	// _SESSION is the only superglobal which is conditionally set
	if (isset($_SESSION))
	{
		$HTTP_SESSION_VARS = $_SESSION;
	}
}

// Protect against GLOBALS tricks
if (isset($HTTP_POST_VARS['GLOBALS']) || isset($HTTP_POST_FILES['GLOBALS']) || isset($HTTP_GET_VARS['GLOBALS']) || isset($HTTP_COOKIE_VARS['GLOBALS']))
{
	die('Hacking attempt');
}

// Protect against HTTP_SESSION_VARS tricks
if (isset($HTTP_SESSION_VARS) && !is_array($HTTP_SESSION_VARS))
{
	die('Hacking attempt');
}

if (@ini_get('register_globals') == '1' || strtolower(@ini_get('register_globals')) == 'on')
{
	// PHP4+ path
	$not_unset = array('HTTP_GET_VARS', 'HTTP_POST_VARS', 'HTTP_COOKIE_VARS', 'HTTP_SERVER_VARS', 'HTTP_SESSION_VARS', 'HTTP_ENV_VARS', 'HTTP_POST_FILES');

	// Not only will array_merge give a warning if a parameter
	// is not an array, it will actually fail. So we check if
	// HTTP_SESSION_VARS has been initialised.
	if (!isset($HTTP_SESSION_VARS) || !is_array($HTTP_SESSION_VARS))
	{
		$HTTP_SESSION_VARS = array();
	}

	// Merge all into one extremely huge array; unset
	// this later
	$input = array_merge($HTTP_GET_VARS, $HTTP_POST_VARS, $HTTP_COOKIE_VARS, $HTTP_SERVER_VARS, $HTTP_SESSION_VARS, $HTTP_ENV_VARS, $HTTP_POST_FILES);

	unset($input['input']);
	unset($input['not_unset']);

	//while (list($var,) = @each($input))
	foreach ($input as $var => $val)
	{
		if (in_array($var, $not_unset))
		{
			die('Hacking attempt!');
		}
		unset(${$var});
	}

	unset($input);
}

/*
* Include some files we need for the Emergency Console
*/
include(IP_ROOT_PATH . 'config.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/db.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/template.' . PHP_EXT);
define('PREFIX', $table_prefix);

/*
* DB Connection, Template and Adminclass
*/
$db = new sql_db($dbhost, $dbuser, $dbpasswd, $dbname);
$template = new Template();


/*
* Unset unused vars
*/
unset($dbname);					// Unset Database Name
unset($dbuser);					// Unset Database Username
unset($dbpasswd);				// Unset Database Password var
unset($db->password);		// Unset Database Password in DB Class
unset($sql);						// Unset maybe injected SQL Commands in this var

function phpbb_realpath($path)
{
	return (!@function_exists('realpath') || !@realpath(IP_ROOT_PATH . 'includes/functions.' . PHP_EXT)) ? $path : @realpath($path);
}

function message_die($msg_code, $msg_text = '', $msg_title = '', $err_line = '', $err_file = '', $sql = '')
{
	die("<html>\n<body bgcolor=\"#000000\">\n<span style=\"color:#FFFFFF;\">" . $msg_title . "</span>\n<br /><br />\n" . $msg_text . "</body>\n</html>");
}


/*
* The script itself :)
*/
$template->set_filenames(array('ct_body' => IP_ROOT_PATH . 'includes/ctracker/admin/console/emergency.tpl'));

/*
 * Console Operations
 */
$mode = $HTTP_GET_VARS['mode'];

if ($mode == 'restore')
{
	// Drop existing Config Table
	$sql = 'DROP TABLE IF EXISTS ' . PREFIX . 'config';
	$result = $db->sql_query($sql);

	// Create Config table
	$sql = 'CREATE TABLE ' . PREFIX . 'config (
				`config_name` varchar(190) NOT NULL ,
				`config_value` text NOT NULL ,
				PRIMARY KEY (`config_name`)
				)';
	$result = $db->sql_query($sql);

	// Insert config data
	$sql = 'SELECT * FROM ' . PREFIX . 'ctracker_backup';
	$result = $db->sql_query($sql);

	while ( $row = $db->sql_fetchrow($result) )
	{
		$sql2 = 'INSERT INTO ' . PREFIX . 'config (`config_name`, `config_value`) VALUES (\'' . $db->sql_escape($row['config_name']) . '\', \'' . $db->sql_escape($row['config_value']) . '\')';
		$result2 = $db->sql_query($sql2);
	}

	// Remove Backup Timestamp
	$sql = 'DELETE FROM ' . PREFIX . 'config WHERE config_name = \'ct_last_backup\'';
	$result = $db->sql_query($sql);

	$template->assign_block_vars('ok', array());
}
else if ( $mode == 'psrt' )
{
	$sql = "UPDATE " . PREFIX . "config SET
				config_value = '" . $db->sql_escape($HTTP_POST_VARS['cookie_name']) . "'
				WHERE config_name = 'cookie_name'";
	$result = $db->sql_query($sql);

	$sql = "UPDATE " . PREFIX . "config SET
				config_value = '" . $db->sql_escape($HTTP_POST_VARS['cookie_path']) . "'
				WHERE config_name = 'cookie_path'";
	$result = $db->sql_query($sql);

	$sql = "UPDATE " . PREFIX . "config SET
				config_value = '" . $db->sql_escape($HTTP_POST_VARS['cookie_domain']) . "'
				WHERE config_name = 'cookie_domain'";
	$result = $db->sql_query($sql);

	$sql = "UPDATE " . PREFIX . "config SET
				config_value = '" . $db->sql_escape($HTTP_POST_VARS['cookie_secure']) . "'
				WHERE config_name = 'cookie_secure'";
	$result = $db->sql_query($sql);

	$sql = "UPDATE " . PREFIX . "config SET
				config_value = '" . $db->sql_escape($HTTP_POST_VARS['server_name']) . "'
				WHERE config_name = 'server_name'";
	$result = $db->sql_query($sql);

	$sql = "UPDATE " . PREFIX . "config SET
				config_value = '" . $db->sql_escape($HTTP_POST_VARS['server_port']) . "'
				WHERE config_name = 'server_port'";
	$result = $db->sql_query($sql);

	$sql = "UPDATE " . PREFIX . "config SET
				config_value = '" . $db->sql_escape($HTTP_POST_VARS['script_path']) . "'
				WHERE config_name = 'script_path'";
	$result = $db->sql_query($sql);

	$sql = "UPDATE " . PREFIX . "config SET
				config_value = '" . $db->sql_escape($HTTP_POST_VARS['session_length']) . "'
				WHERE config_name = 'session_length'";
	$result = $db->sql_query($sql);

	$template->assign_block_vars('ok', array());
}

/*
 * Load backup status
 */
$save_status = '';
$saved_now   = false;
$sql = 'SELECT * FROM ' . PREFIX . 'ctracker_backup WHERE config_name = \'ct_last_backup\'';
$db->sql_return_on_error(true);
$result = $db->sql_query($sql);
$db->sql_return_on_error(false);
if (!$result)
{
	$save_status = 'no configuration backup available';
}
else
{
	$saved_now = true;
	while ($row = $db->sql_fetchrow($result))
	{
		$backup[$row['config_name']] = $row['config_value'];
	}
	$save_status = gmdate('d.m.Y - H:i', $backup['ct_last_backup']);
}


/*
* Send some vars to the template
*/
$template->assign_vars(array(
	'YEAR' => date(Y),
	'BACKUP' => $save_status,
	'PHP_EXT' => PHP_EXT,
	'RESTORE_OUTPUT' => ($saved_now)? '<a href="emergency.php?mode=restore" style="color:#FDFF00">&raquo; Click here to restore configuration table now! &laquo;</a>': ''
	)
);


// Generate the page
$template->pparse('ct_body');

/*
* Disconnect from Database
*/
if (!empty($db))
{
	$db->sql_close();
}

?>