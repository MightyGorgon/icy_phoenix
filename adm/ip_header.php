<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

define('IN_ICYPHOENIX', true);
define('IN_ADMIN', true);

// Load default header
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
$no_page_header = true;
require(IP_ROOT_PATH . 'common.' . PHP_EXT);

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

include_once(IP_ROOT_PATH . 'includes/functions_jr_admin.' . PHP_EXT);
$jr_admin_userdata = jr_admin_get_user_info($userdata['user_id']);

if(!$userdata['session_logged_in'])
{
	message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
}
else
{
	if (($userdata['user_level'] != ADMIN) && ($userdata['session_logged_in'] && (empty($jr_admin_userdata['user_jr_admin']))))
	{
		message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
	}
}

$template->set_filenames(array('body' => ADM_TPL . 'ip_header.tpl'));

// Check for new version
$current_version = explode('.', $config['ip_version']);
$minor_revision = (int) $current_version[3];
$errno = 0;
$errstr = $version_info = '';
// Version cache mod start
// Change following two variables if you need to:
$cache_update = 86400; // 24 hours cache timeout. change it to whatever you want
$cache_file = MAIN_CACHE_FOLDER . 'ip_update_' . $config['default_lang'] . $config['ip_version'] . '.php'; // file where to store cache

//global $config;
$do_update = true;
//$check_update = false;
$check_update = $config['enable_xs_version_check'];

if($check_update == true)
{
	if(@file_exists($cache_file))
	{
		$last_update = 0;
		$version_info = '';
		@include($cache_file);
		if($last_update && !empty($version_info) && $last_update > (time() - $cache_update))
		{
			$do_update = false;
		}
		else
		{
			$version_info = '';
		}
	}

	if($do_update == true)
	{
		// Version cache mod end
		if ($fsock = @fsockopen('www.icyphoenix.com', 80, $errno, $errstr, 15))
		{
			@fwrite($fsock, "GET /version/ip.txt HTTP/1.1\r\n");
			@fwrite($fsock, "HOST: www.icyphoenix.com\r\n");
			@fwrite($fsock, "Connection: close\r\n\r\n");

			$get_info = false;
			while (!@feof($fsock))
			{
				if ($get_info)
				{
					$version_info .= @fread($fsock, 1024);
				}
				else
				{
					if (@fgets($fsock, 1024) == "\r\n")
					{
						$get_info = true;
					}
				}
			}
			@fclose($fsock);

			$version_info = explode("\n", $version_info);
			$latest_head_revision = (int) $version_info[0];
			$latest_minor_revision = (int) $version_info[3];
			$latest_version = (int) $version_info[0] . '.' . (int) $version_info[1] . '.' . (int) $version_info[2] . '.' . (int) $version_info[3];
			$latest_version_text = $version_info[0] . '.' . $version_info[1] . '.' . $version_info[2] . '.' . $version_info[3];

			//if (($latest_head_revision == 1) && ($minor_revision == $latest_minor_revision))
			if ($latest_version_text == $config['ip_version'])
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
			if ($errstr)
			{
				$version_info = '<span class="text_red">' . sprintf($lang['Connect_socket_error_ip'], $errstr) . '</span>';
			}
			else
			{
				$version_info = '<span>' . $lang['Socket_functions_disabled'] . '</span>';
			}
		}

		// Version cache mod start
		if(@$f = fopen($cache_file, 'w'))
		{
			$search = array('\\', '\'');
			$replace = array('\\\\', '\\\'');
			fwrite($f, '<' . '?php $last_update = ' . time() . '; $version_info = \'' . str_replace($search, $replace, $version_info) . '\'; ?' . '>');
			fclose($f);
			@chmod($cache_file, 0777);
		}
		// Version cache mod end
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
	'U_MSQD' => append_sid('../msqd/'),
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