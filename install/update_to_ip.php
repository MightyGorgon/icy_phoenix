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

define('IN_PHPBB', true);
define('IN_ICYPHOENIX', true);
define('IP_INSTALLED', true);
define('PHPBB_INSTALLED', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
$phpbb_root_path = IP_ROOT_PATH;
$phpEx = PHP_EXT;
if (file_exists(IP_ROOT_PATH . 'extension.inc'))
{
	include(IP_ROOT_PATH . 'extension.inc');
}
include(IP_ROOT_PATH . 'common.' . PHP_EXT);

// Start session management
$userdata = session_pagestart($user_ip, 0);
init_userprefs($userdata);
// End session management

define('THIS_FILE', 'update_to_ip.' . PHP_EXT);

if (defined('IP_INSTALLED') && function_exists('get_founder_id'))
{
	$founder_id = (defined('FOUNDER_ID') ? FOUNDER_ID : get_founder_id());
	if ($userdata['user_id'] != $founder_id)
	{
		die('Only the founder may run this script!!!');
	}
}
else
{
	if ($userdata['user_level'] != ADMIN)
	{
		die('Only admins may run this script!!!');
	}
}

if (isset($_POST['mode']) || isset($_GET['mode']))
{
	$mode = (isset($_POST['mode'])) ? $_POST['mode'] : $_GET['mode'];
}


$page_title = 'Updating to latest Icy Phoenix';
include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);

$older_update = false;

if (substr($mode, 0, 6) == 'update')
{
	echo '<table class="forumline" width="100%" cellspacing="0" cellpadding="0">';
	echo '<tr><th>Updating the database</th></tr>';
	echo '<tr><td class="row1">';
	echo '<div class="post-text"><div class="genmed">';

	require('schemas/versions.' . PHP_EXT);
	$current_ip_version = $ip_version;
	require('schemas/sql_update_ip.' . PHP_EXT);
	$sql_results_ok = '';
	$sql_results_error = '';
	// Executing SQL
	for($i = 0; $i < count($sql); $i++)
	{
		if(!$result = $db->sql_query($sql[$i]))
		{
			$error = $db->sql_error();
			$sql_results_error .= '<li>' . $sql[$i] . '<br /> +++ <span style="color:#dd2222;"><b>Error:</b></span> ' . $error['message'] . '<br /><br /></li>';
		}
		else
		{
			$sql_results_ok .= '<li>' . $sql[$i] . '<br /> +++ <span style="color:#228822;"><b>Successful!</b></span><br /><br /></li>';
		}
	}

	echo('<b>SQL Errors:</b><br /><br /><ul type="circle">' . $sql_results_error . '</ul><br /><br /><br />' . '<b>SQL Success:</b><br /><br /><ul type="circle">' . $sql_results_ok . '</ul><br />');

	$db->clear_cache();

	// CONFIG REWRITE
	/*
	$filename_tmp = IP_ROOT_PATH . 'config.' . PHP_EXT;
	$content = file_read($filename_tmp);
	$content = str_replace('PHPBB_INSTALLED', 'IP_INSTALLED', $content);
	$result = file_write($filename_tmp, $content);
	$class = ($result !== false) ? 'text_red' : 'text_green';
	echo '<li><span class="genmed"><b class="' . $class . '">' . $filename_tmp . '</b></span></li><br />';
	*/

	echo '</div></div>';
	echo '</td></tr>';
	echo '<tr><td class="cat" height="28">&nbsp;</td></tr>';
	echo '<tr><th>Upgrade Complete!</th></tr>';
	echo '<tr><td class="row1"><span class="genmed">Please be sure to delete this file now.<br />If your site is not working properly please visit <a href="http://www.icyphoenix.com/viewforum.php?f=4" title="Documentation for Icy Phoenix" target="_blank">Icy Phoenix Documentation</a> and if you don\'t find an answer to your problem try to visit <a href="http://www.icyphoenix.com/" title="Icy Phoenix Support Site" target="_blank">Icy Phoenix Support Site</a> and ask for support.</span></td></tr>';
	echo '<tr><td class="cat" height="28" align="center"><span class="genmed"><a href="' . append_sid('index.' . PHP_EXT) . '">Have a nice day</a></span></td></tr></table>';
}
elseif (substr($mode, 0, 5) == 'chmod')
{
	apply_chmod();
}
else
{
	show_main_options();
}

include(IP_ROOT_PATH . 'includes/page_tail.' . PHP_EXT);

// FUNCTIONS
function apply_chmod()
{
	echo '<table class="forumline" width="100%" cellspacing="0" cellpadding="0">';
	echo '<tr><th>Applying CHMOD</th></tr><tr><td class="row1"><div class="post-text"><div class="genmed"><ul type="circle">';

	// Read CHMOD vars from file
	require('schemas/versions.' . PHP_EXT);

	echo '<br /><br /><br /><li><b><span style="color:#DD3333;"><b>CHMOD Files</b></span></li><br /><br /><br />';

	for ($i = 0; $i < count($chmod_777); $i++)
	{
		if (!@chmod($chmod_777[$i], 0777))
		{
			echo '<li>CHMOD 0777: <b>' . $chmod_777[$i] . '</b><br /> +++ <span style="color:#DD3333;"><b>Error CHMOD 0777:</b></span> <b>' . $chmod_777[$i] . '</b></li><br />';
		}
		else
		{
			echo '<li>CHMOD 0777: <b>' . $chmod_777[$i] . '</b><br /> +++ <span style="color:#228844;"><b>Successfull CHMOD 0777:</b></span> <b>' . $chmod_777[$i] . '</b></li><br />';
		}
	}

	for ($i = 0; $i < count($chmod_666); $i++)
	{
		if (!@chmod($chmod_666[$i], 0666))
		{
			echo '<li>CHMOD 0666: <b>' . $chmod_666[$i] . '</b><br /> +++ <span style="color:#DD3333;"><b>Error CHMOD 0666:</b></span> <b>' . $chmod_666[$i] . '</b></li><br />';
		}
		else
		{
			echo '<li>CHMOD 0666: <b>' . $chmod_666[$i] . '</b><br /> +++ <span style="color:#228844;"><b>Successful CHMOD 0666:</b></span> <b>' . $chmod_666[$i] . '</b></li><br />';
		}
	}

	echo '</ul></div></div></td></tr><tr><td class="cat" height="28">&nbsp;</td></tr>';
	echo '<tr><th>CHMOD</th></tr><tr><td class="row1"><span class="genmed">CHMOD process ended. If you don\'t need to run upgrade, please delete this file now.</span></td></tr>';
	echo '<tr><td class="cat" height="28" align="center"><span class="genmed"><a href="' . append_sid(THIS_FILE) . '">Return to update options</a>&nbsp;|&nbsp;<a href="' . append_sid(IP_ROOT_PATH . 'index.' . PHP_EXT) . '">Return to your site</a></span></td></tr></table>';

	return true;
}

function file_read($filename)
{
	$handle = @fopen($filename, 'r');
	$content = @fread($handle, filesize($filename));
	@fclose($handle);
	return $content;
}

function file_write($filename, $content)
{
	$handle = @fopen($filename, 'w');
	$result = @fwrite($handle, $content, strlen($content));
	@fclose($handle);
	return $result;
}

function show_main_options()
{
	echo '<table class="forumline" width="100%" cellspacing="0" cellpadding="0">';
	echo '<tr><th>Upgrading your Icy Phoenix</th></tr>';
	echo '<tr><td class="row1">';
	echo '<div class="post-text">';

	//echo '<br /><span class="topic_glo"><b><span class="gen">Remember that this file has to be in your root!</span></b></span><br /><br />';
	echo '<br /><span class="topic_ann"><b><span class="gen">Upgrading options:</span></b></span><br />';

	echo '<div class="genmed"><br /><br /><ul type="circle">';
	echo '<li><a href="' . append_sid(THIS_FILE . '?mode=update') . '"><b class="topic_glo">Update to latest Icy Phoenix from phpBB or any older phpBB XS version</b></a><br /><br /></li>';
	echo '<li><a href="' . append_sid(THIS_FILE . '?mode=update_058') . '"><b>Update to latest Icy Phoenix from phpBB XS 058</b></a><br /><br /></li>';
	echo '<li><a href="' . append_sid(THIS_FILE . '?mode=update_1055') . '">Update to latest Icy Phoenix from Icy Phoenix 1.0.5.5 Beta</a><br /><br /></li>';
	echo '<li><a href="' . append_sid(THIS_FILE . '?mode=update_101111') . '">Update to latest Icy Phoenix from Icy Phoenix 1.0.11.11 RC1</a><br /><br /></li>';
	echo '<li><a href="' . append_sid(THIS_FILE . '?mode=update_11015') . '"><b class="topic_ann">Update to latest Icy Phoenix from Icy Phoenix 1.1.0.15 STABLE</b></a><br /><br /></li>';
	echo '<li><a href="' . append_sid(THIS_FILE . '?mode=update_11116') . '">Update to latest Icy Phoenix from Icy Phoenix 1.1.1.16 Beta</a><br /><br /></li>';
	echo '<li><a href="' . append_sid(THIS_FILE . '?mode=update_11520') . '">Update to latest Icy Phoenix from Icy Phoenix 1.1.5.20 Beta</a><br /><br /></li>';
	echo '<li><a href="' . append_sid(THIS_FILE . '?mode=update_11722') . '">Update to latest Icy Phoenix from Icy Phoenix 1.1.7.22 RC1</a><br /><br /></li>';
	echo '<li><a href="' . append_sid(THIS_FILE . '?mode=update_11924') . '">Update to latest Icy Phoenix from Icy Phoenix 1.1.9.24 RC2</a><br /><br /></li>';
	echo '<li><a href="' . append_sid(THIS_FILE . '?mode=update_111025') . '">Update to latest Icy Phoenix from Icy Phoenix 1.1.10.25 RC3</a><br /><br /></li>';
	echo '<li><a href="' . append_sid(THIS_FILE . '?mode=update_12027') . '"><b class="topic_imp">Update to latest Icy Phoenix from Icy Phoenix 1.2.0.27 STABLE</b></a><br /><br /></li>';
	echo '<li><a href="' . append_sid(THIS_FILE . '?mode=update_12229') . '">Update to latest Icy Phoenix from Icy Phoenix 1.2.2.29</a><br /><br /></li>';
	echo '<li><a href="' . append_sid(THIS_FILE . '?mode=update_12431') . '">Update to latest Icy Phoenix from Icy Phoenix 1.2.4.31</a><br /><br /></li>';
	echo '<li><a href="' . append_sid(THIS_FILE . '?mode=update_12734') . '"><b class="topic_glo">Update to latest Icy Phoenix from Icy Phoenix 1.2.7.34 (or higher)</b></a><br /><br /></li>';
	echo '</ul></div>';
	echo '<div class="genmed"><br /><br /><ul type="circle">';
	echo '<li><a href="' . append_sid(THIS_FILE . '?mode=chmod') . '"><b class="topic_imp">Apply CHMOD (Please note that not all servers support CHMOD via PHP!!!)</b></a><br /><br /></li>';
	echo '</ul></div>';

	echo '</div>';
	echo '</td></tr>';
	echo '<tr><td class="cat" height="28" align="center"><span class="genmed"><a href="' . append_sid(IP_ROOT_PATH . 'index.' . PHP_EXT) . '">Return to your site</a></span></td></tr></table>';

	return true;
}
?>