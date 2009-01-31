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

// CTracker_Ignore: File checked by human
if ((isset($_GET['mode']) && ($_GET['mode'] == 'viewprofile')) || (isset($_POST['mode']) && ($_POST['mode'] == 'viewprofile')))
{
	// MG Cash MOD For IP - BEGIN
	define('IN_CASHMOD', true);
	define('CM_VIEWPROFILE',true);
	// MG Cash MOD For IP - END
	// Added to optimize memory for attachments
	define('ATTACH_PROFILE', true);
	define('ATTACH_POSTING', true);
}
define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_profile.' . PHP_EXT);

// Adding CPL_NAV only if needed
define('PARSE_CPL_NAV', true);

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

// session id check
if (!empty($_POST['sid']) || !empty($_GET['sid']))
{
	$sid = (!empty($_POST['sid'])) ? $_POST['sid'] : $_GET['sid'];
}
else
{
	$sid = '';
}

// Set default email variables
$script_name = preg_replace('/^\/?(.*?)\/?$/', '\1', trim($board_config['script_path']));
$script_name = ($script_name != '') ? $script_name . '/' . PROFILE_MG : PROFILE_MG;
$server_name = trim($board_config['server_name']);
$server_protocol = ($board_config['cookie_secure']) ? 'https://' : 'http://';
$server_port = ($board_config['server_port'] <> 80) ? ':' . trim($board_config['server_port']) . '/' : '/';
$server_url = $server_protocol . $server_name . $server_port . $script_name;

$server_url = create_server_url();
$profile_server_url = $server_url . PROFILE_MG;

$page_title = $lang['Profile'];
$meta_description = '';
$meta_keywords = '';

// Page specific functions
function gen_rand_string($hash)
{
	$rand_str = unique_id();
	return ($hash) ? md5($rand_str) : substr($rand_str, 0, 8);
}
// End page specific functions

// Start of program proper
if (isset($_GET['mode']) || isset($_POST['mode']))
{
	$mode = (isset($_GET['mode']) ? $_GET['mode'] : $_POST['mode']);
	$mode = htmlspecialchars($mode);

	if ($mode != 'viewprofile')
	{
		include_once(IP_ROOT_PATH . 'includes/users_zebra_block.' . PHP_EXT);
	}

	if ($mode == 'viewprofile')
	{
		$cms_page_id = '6';
		$cms_page_name = 'profile';
		check_page_auth($cms_page_id, $cms_page_name);
		$cms_global_blocks = ($board_config['wide_blocks_' . $cms_page_name] == 1) ? true : false;

		// Mighty Gorgon - Full Album Pack - BEGIN
		include (ALBUM_MOD_PATH . 'album_constants.' . PHP_EXT);
		// Mighty Gorgon - Full Album Pack - END
		include(IP_ROOT_PATH . 'includes/usercp_viewprofile.' . PHP_EXT);
		exit;
	}
	elseif (($mode == 'editprofile') || ($mode == 'register'))
	{
		if (!$userdata['session_logged_in'] && ($mode == 'editprofile'))
		{
			redirect(append_sid(LOGIN_MG . '?redirect=' . PROFILE_MG . '&mode=editprofile', true));
			//redirect(append_sid(LOGIN_MG . '?redirect=' . PROFILE_MG . '&mode=editprofile&cpl_mode=reg_info', true));
		}
		include(IP_ROOT_PATH . 'includes/usercp_register.' . PHP_EXT);
		exit;
	}
	elseif ($mode == 'signature')
	{
		if (!$userdata['session_logged_in'] && ($mode == 'signature'))
		{
			$header_location = (@preg_match("/Microsoft|WebSTAR|Xitami/", getenv("SERVER_SOFTWARE"))) ? "Refresh: 0; URL=" : "Location: ";
			header($header_location . append_sid(LOGIN_MG . '?redirect=' . PROFILE_MG . '&mode=signature', true));
			exit;
		}

		include(IP_ROOT_PATH . 'includes/usercp_signature.' . PHP_EXT);
		exit;
	}
	elseif ($mode == 'confirm')
	{
		// Visual Confirmation
		if ($userdata['session_logged_in'] && (htmlspecialchars($_GET['id']) != 'Admin'))
		{
			exit;
		}
		include(IP_ROOT_PATH . 'includes/usercp_confirm.' . PHP_EXT);
		exit;
	}
	elseif ($mode == 'sendpassword')
	{
		include(IP_ROOT_PATH . 'includes/usercp_sendpasswd.' . PHP_EXT);
		exit;
	}
	elseif ($mode == 'activate')
	{
		include(IP_ROOT_PATH . 'includes/usercp_activate.' . PHP_EXT);
		exit;
	}
	elseif ($mode == 'resend')
	{
		include(IP_ROOT_PATH . 'includes/usercp_resend.' . PHP_EXT);
		exit;
	}
	elseif ($mode == 'email')
	{
		include(IP_ROOT_PATH . 'includes/usercp_email.' . PHP_EXT);
		exit;
	}
	elseif ($mode == 'zebra')
	{
		include(IP_ROOT_PATH . 'includes/usercp_zebra.' . PHP_EXT);
		exit;
	}
}

redirect(append_sid(FORUM_MG, true));

?>