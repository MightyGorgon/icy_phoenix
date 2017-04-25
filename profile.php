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
else
{
	//define('CTRACKER_DISABLED', true);
}
define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_profile.' . PHP_EXT);

// Adding CPL_NAV only if needed
define('PARSE_CPL_NAV', true);

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup();
// End session management

$meta_content['page_title'] = $lang['Profile'];
$meta_content['description'] = '';
$meta_content['keywords'] = '';

// Set default email variables
$server_url = create_server_url();
$profile_server_url = $server_url . CMS_PAGE_PROFILE;

if ($config['enable_social_connect'])
{
	include_once(IP_ROOT_PATH . 'includes/class_social_connect.' . PHP_EXT);
	$available_networks = SocialConnect::get_available_networks();

	// Intercept oauth2 authentication requests.
	// They can't forward query parameters, so we fake them here
	$social_network = isset($_SESSION['login_social_network']) ? $_SESSION['login_social_network'] : '';
	if (!empty($social_network) && !empty($available_networks[$social_network]))
	{
		$available_networks[$social_network]->shim_register_request();
	}
}

$sid = request_var('sid', '');
$mode = request_var('mode', '');

// Start of program proper
if (!empty($mode))
{
	if ($mode != 'viewprofile')
	{
		include_once(IP_ROOT_PATH . 'includes/users_zebra_block.' . PHP_EXT);
	}

	if ($mode == 'viewprofile')
	{
		$cms_page['page_id'] = 'profile';
		$cms_page['page_nav'] = (!empty($cms_config_layouts[$cms_page['page_id']]['page_nav']) ? true : false);
		$cms_page['global_blocks'] = (!empty($cms_config_layouts[$cms_page['page_id']]['global_blocks']) ? true : false);
		$cms_auth_level = (isset($cms_config_layouts[$cms_page['page_id']]['view']) ? $cms_config_layouts[$cms_page['page_id']]['view'] : AUTH_ALL);
		check_page_auth($cms_page['page_id'], $cms_auth_level);

		include(IP_ROOT_PATH . 'includes/usercp_viewprofile.' . PHP_EXT);
		exit;
	}
	elseif (($mode == 'editprofile') || ($mode == 'register'))
	{
		if (!$user->data['session_logged_in'] && ($mode == 'editprofile'))
		{
			redirect(append_sid(CMS_PAGE_LOGIN . '?redirect=' . CMS_PAGE_PROFILE . '&mode=editprofile', true));
			//redirect(append_sid(CMS_PAGE_LOGIN . '?redirect=' . CMS_PAGE_PROFILE . '&mode=editprofile&cpl_mode=reg_info', true));
		}
		include(IP_ROOT_PATH . 'includes/usercp_register.' . PHP_EXT);
		exit;
	}
	elseif ($mode == 'signature')
	{
		if (!$user->data['session_logged_in'] && ($mode == 'signature'))
		{
			$header_location = (@preg_match("/Microsoft|WebSTAR|Xitami/", getenv("SERVER_SOFTWARE"))) ? "Refresh: 0; URL=" : "Location: ";
			header($header_location . append_sid(CMS_PAGE_LOGIN . '?redirect=' . CMS_PAGE_PROFILE . '&mode=signature', true));
			exit;
		}

		include(IP_ROOT_PATH . 'includes/usercp_signature.' . PHP_EXT);
		exit;
	}
	elseif ($mode == 'confirm')
	{
		// Visual Confirmation
		$force_captcha = request_var('force_captcha', 0);
		if (empty($force_captcha) && $user->data['session_logged_in'] && ($_GET['confirm_id'] != 'Admin'))
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

redirect(append_sid(CMS_PAGE_FORUM, true));

?>