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
* geocator(geocator@gmail.com)
*/

// CTracker_Ignore: File checked by human
define('IN_ICYPHOENIX', true);
define('IN_ADMIN', true);

if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require(IP_ROOT_PATH . 'common.' . PHP_EXT);

$lang['Clear_browser'] = 'You need to clear your browser cookies and cache and restart it for the settings to take effect.';
$lang['Delete_file'] = 'Please delete this script and the install directory now!';

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

if (htmlspecialchars($_POST['action']) == "write")
{
	$sql = "UPDATE " . CONFIG_TABLE . " SET config_value = '" . str_replace("\'", "''", htmlspecialchars($_POST['cookie_domain'])) . "' WHERE config_name = '" . cookie_domain . "'";
	$db->sql_query($sql);

	$sql = "UPDATE " . CONFIG_TABLE . " SET config_value = '" . str_replace("\'", "''", htmlspecialchars($_POST['cookie_path'])) . "' WHERE config_name = '" . cookie_path . "'";
	$db->sql_query($sql);

	$sql = "UPDATE " . CONFIG_TABLE . " SET config_value = '" . str_replace("\'", "''", htmlspecialchars($_POST['cookie_name'])) . "' WHERE config_name = '" . cookie_name . "'";
	$db->sql_query($sql);

	$sql = "UPDATE " . CONFIG_TABLE . " SET config_value = '" . str_replace("\'", "''", htmlspecialchars($_POST['domain_name'])) . "' WHERE config_name = '" . server_name . "'";
	$db->sql_query($sql);

	$sql = "UPDATE " . CONFIG_TABLE . " SET config_value = '" . str_replace("\'", "''", htmlspecialchars($_POST['script_path'])) . "' WHERE config_name = '" . script_path . "'";
	$db->sql_query($sql);

	$sql = "UPDATE " . CONFIG_TABLE . " SET config_value = " . intval($_POST['server_port']) . " WHERE config_name = '" . server_port . "'";
	$db->sql_query($sql);

	$sql = "UPDATE " . CONFIG_TABLE . " SET config_value = " . intval($_POST['cookie_secure']) . " WHERE config_name = '" . cookie_secure . "'";
	$db->sql_query($sql);

	echo ('<p><b>' . $lang['Config_updated'] . '</b></p>');
	echo ('<p><b>' . $lang['Clear_browser'] . '</b></p>');
	echo ('<p><b>' . $lang['Delete_file'] . '</b></p>');
}
else
{
	$file_path = $_SERVER['SCRIPT_NAME'];
	$dirs = explode('/', $file_path);
	$dir_count = sizeof( $dirs ) - 1;
	unset( $dirs[$dir_count] );
	unset( $dirs[$dir_count-1] );
	$script_path = implode( '/', $dirs) . '/';

	$server_port = $_SERVER['SERVER_PORT'];

	$server_name = $_SERVER['SERVER_NAME'];

	$secure_yes = '';
	$secure_no = '';
	if ($_SERVER['SERVER_PORT'] == 443)
	{
		$secure_yes = 'checked="checked"';
	}
	else
	{
		$secure_no = 'checked="checked"';
	}
	if (strstr($server_name, 'www.'))
	{
		$cookie_domain = substr($server_name, 3);
	}
	else
	{
		$cookie_domain = $server_name;
	}



	$cookie_path = substr($script_path, 0, -1);

	if (strlen($cookie_path) == 0)
	{
		$cookie_path = '/';
	}

	$cookie_name = substr($cookie_domain, 1, 4) . substr($cookie_path, 1, 4) . '_ip';
	echo('<span class="nav">' . $lang['Cookie_settings_explain']);
	echo('<table class="genmed">');
	echo('<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '">');
	echo('<tr><td>' . $lang['Cookie_domain'] . ' </td><td><input type="text" name="cookie_domain" value="' . $cookie_domain . '"></td></tr>');
	echo('<tr><td>' . $lang['Cookie_path'] . ' </td><td><input type="text" name="cookie_path" value="' . $cookie_path . '"></td></tr>');
	echo('<tr><td>' . $lang['Cookie_name'] . ' </td><td><input type="text" name="cookie_name" value="' . $cookie_name . '"></td></tr>');
	echo('<br />');
	echo('<tr><td>' . $lang['Server_name'] . ' </td><td><input type="text" name="domain_name" value="' . $server_name . '"></td></tr>');
	echo('<tr><td>' . $lang['Script_path'] . ' </td><td><input type="text" name="script_path" value="' . $script_path . '"></td></tr>');
	echo('<tr><td>' . $lang['Server_port'] . ' </td><td><input type="text" name="server_port" value="' . $server_port . '"></td></tr>');
	echo('<tr><td>' . $lang['Cookie_secure'] . ' </td><td><input type="radio" name="cookie_secure" value="0" ' . $secure_no . '>' . $lang['Disabled'] . '<input type="radio" name="cookie_secure" value="1" ' . $secure_yes . '>' . $lang['Enabled'] .'</td></tr>');
	echo('</table>');
	echo('<input type="hidden" name="action" value="write">');
	echo('<input type="submit" value="' . $lang['Save_Settings'] . '">');
	echo('</form>');
}

?>