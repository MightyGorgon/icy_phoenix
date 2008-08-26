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
define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.' . $phpEx);
include($phpbb_root_path . 'includes/functions_profile.' . $phpEx);

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
	$rand_str = dss_rand();

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
		include_once($phpbb_root_path . 'includes/users_zebra_block.' . $phpEx);
	}

	if ($mode == 'viewprofile')
	{
		$cms_page_id = '6';
		$cms_page_name = 'profile';
		$auth_level_req = $board_config['auth_view_profile'];
		if ($auth_level_req > AUTH_ALL)
		{
			if (($auth_level_req == AUTH_REG) && (!$userdata['session_logged_in']))
			{
				message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
			}
			if ($userdata['user_level'] != ADMIN)
			{
				if ($auth_level_req == AUTH_ADMIN)
				{
					message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
				}
				if (($auth_level_req == AUTH_MOD) && ($userdata['user_level'] != MOD))
				{
					message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
				}
			}
		}
		$cms_global_blocks = ($board_config['wide_blocks_profile'] == 1) ? true : false;

		// Mighty Gorgon - Full Album Pack - BEGIN
		$album_root_path = $phpbb_root_path . ALBUM_MOD_PATH;
		include ($album_root_path . 'album_constants.' . $phpEx);
		// Mighty Gorgon - Full Album Pack - END
		include($phpbb_root_path . 'includes/usercp_viewprofile.' . $phpEx);
		exit;
	}
	elseif (($mode == 'editprofile') || ($mode == 'register'))
	{
		if (!$userdata['session_logged_in'] && ($mode == 'editprofile'))
		{
			redirect(append_sid(LOGIN_MG . '?redirect=' . PROFILE_MG . '&mode=editprofile', true));
			//redirect(append_sid(LOGIN_MG . '?redirect=' . PROFILE_MG . '&mode=editprofile&cpl_mode=reg_info', true));
		}
		include($phpbb_root_path . 'includes/usercp_register.' . $phpEx);
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

		include($phpbb_root_path . 'includes/usercp_signature.' . $phpEx);
		exit;
	}
	elseif ($mode == 'confirm')
	{
		// Visual Confirmation
		if ($userdata['session_logged_in'] && (htmlspecialchars($_GET['id']) != 'Admin'))
		{
			exit;
		}
		include($phpbb_root_path . 'includes/usercp_confirm.' . $phpEx);
		exit;
	}
	elseif ($mode == 'sendpassword')
	{
		include($phpbb_root_path . 'includes/usercp_sendpasswd.' . $phpEx);
		exit;
	}
	elseif ($mode == 'activate')
	{
		include($phpbb_root_path . 'includes/usercp_activate.' . $phpEx);
		exit;
	}
	elseif ($mode == 'resend')
	{
		include($phpbb_root_path . 'includes/usercp_resend.' . $phpEx);
		exit;
	}
	elseif ($mode == 'email')
	{
		include($phpbb_root_path . 'includes/usercp_email.' . $phpEx);
		exit;
	}
	elseif ($mode == 'zebra')
	{
		include($phpbb_root_path . 'includes/usercp_zebra.' . $phpEx);
		exit;
	}
}

redirect(append_sid(FORUM_MG, true));

?>