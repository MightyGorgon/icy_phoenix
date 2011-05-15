<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

define('IN_ADMIN', true);
define('IN_ICYPHOENIX', true);

// Load default header
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
$no_page_header = true;
require(IP_ROOT_PATH . 'common.' . PHP_EXT);

// Start session management
$user->session_begin();
//$auth->acl($user->data);
$user->setup();
// End session management

if (!function_exists('obtain_latest_version_info'))
{
	include_once(IP_ROOT_PATH . 'includes/functions_admin.' . PHP_EXT);
}

include_once(IP_ROOT_PATH . 'includes/functions_jr_admin.' . PHP_EXT);
$jr_admin_userdata = jr_admin_get_user_info($user->data['user_id']);

if(!$user->data['session_logged_in'])
{
	message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
}
else
{
	if (($user->data['user_level'] != ADMIN) && ($user->data['session_logged_in'] && (empty($jr_admin_userdata['user_jr_admin']))))
	{
		message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
	}
}

$template->set_filenames(array('body' => ADM_TPL . 'ip_header.tpl'));

$errno = 0;
$errstr = '';
$version_info = '';

//$check_update = false;
$check_update = $config['enable_xs_version_check'];

if(!empty($check_update))
{
	$latest_ip_version = obtain_latest_version_info();
	if (!empty($latest_ip_version))
	{
		$latest_version_info = explode("\n", $latest_ip_version);
		$latest_version = str_replace('rc', 'RC', strtolower(trim($latest_version_info[0])));
		$current_version = str_replace('rc', 'RC', strtolower($config['ip_version']));
		$version_up_to_date = version_compare($current_version, $latest_version, '<') ? false : true;
		if ($version_up_to_date)
		{
			$version_info = '<span class="text_green">' . $lang['Version_up_to_date_ip'] . '</span>';
		}
		else
		{
			$version_info = '<span class="text_red">' . $lang['Version_not_up_to_date_ip'] . '</span>';
		}
	}
	else
	{
		$fsock = @fsockopen('www.icyphoenix.com', 80, $errno, $errstr, 15);
		if ($errstr)
		{
			$version_info = '<span class="text_red">' . sprintf($lang['Connect_socket_error_ip'], $errstr) . '</span>';
		}
		else
		{
			$version_info = '<span>' . $lang['Socket_functions_disabled'] . '</span>';
		}
	}
}
else
{
	$version_info = '<span class="text_orange">' . $lang['Version_not_checked'] . '</span>';
}

$template->assign_vars(array(
	'VERSION_INFO' => $version_info,
	'L_VERSION_INFORMATION' => $lang['Version_information'],
	'L_HEADER_WELCOME' => $lang['Header_Welcome'],
	'L_FORUM_INDEX' => $lang['Main_index'],
	'L_ADMIN_INDEX' => $lang['Admin_Index'],
	'L_PREVIEW_FORUM' => $lang['Preview_forum'],
	'L_PORTAL' => $lang['Portal'],
	'L_PREVIEW_PORTAL' => $lang['Preview_Portal'],
	'L_CACHE_CLEAR' => $lang['127_Clear_Cache'],

	'U_FORUM_INDEX' => append_sid('../' . CMS_PAGE_FORUM),
	'U_PORTAL' => append_sid('../' . CMS_PAGE_HOME),
	'U_ADMIN_INDEX' => append_sid('index.' . PHP_EXT . '?pane=right'),
	'U_CMS' => append_sid('../cms.' . PHP_EXT),
	'U_CACHE_CLEAR' => append_sid('admin_board_clearcache.' . PHP_EXT . '?pane=right'),

	'U_IP_MAIN' => '<a href="http://www.icyphoenix.com" target="_blank">' . $lang['IcyPhoenix_Main'] . '</a>',
	'U_IP_DOWNLOAD' => '<a href="http://www.icyphoenix.com/dload.php" target="_blank">' . $lang['IcyPhoenix_Download'] . '</a>',
	'U_IP_CODE_CHANGES' => '<a href="http://www.icyphoenix.com/" target="_blank">' . $lang['IcyPhoenix_Code_Changes'] . '</a>',
	'U_IP_UPGRADE' => '<a href="http://www.icyphoenix.com/forum.php" target="_blank">' . $lang['IcyPhoenix_Updates'] . '</a>',
	'U_PHPBB_UPGRADE' => '<a href="http://www.phpbb.com/" target="_blank">' . $lang['PhpBB_Upgrade'] . '</a>',
	)
);

$template->pparse('body');

?>