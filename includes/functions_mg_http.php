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

function get_http_user_agent()
{
	if (!empty($_SERVER['HTTP_USER_AGENT']))
	{
		$HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];
	}
	else
	{
		$HTTP_USER_AGENT = '';
	}

	return $HTTP_USER_AGENT;
}


function get_user_referer()
{
	if (!empty($_SERVER['HTTP_REFERER']))
	{
		$HTTP_REFERER = $_SERVER['HTTP_REFERER'];
	}
	else
	{
		$HTTP_REFERER = '';
	}

	return $HTTP_REFERER;
}


function get_user_os($http_user_agent_str)
{
	global $lang;
	$user_os_img = 'images/http_agents/os/';

	if (strpos($http_user_agent_str, 'Win'))
	{
		if (strpos($http_user_agent_str, 'Windows NT 6.1'))
		{
			$user_os_ver = 'Windows 7';
			$user_os_img .= 'winlong.png';
		}
		elseif (strpos($http_user_agent_str, 'Windows NT 6.0'))
		{
			$user_os_ver = 'Windows Vista';
			$user_os_img .= 'winlong.png';
		}
		elseif (strpos($http_user_agent_str, 'Windows NT 5.2'))
		{
			$user_os_ver = 'Windows 2003';
			$user_os_img .= 'win2003.png';
		}
		elseif (strpos($http_user_agent_str, 'Windows NT 5.1'))
		{
			$user_os_ver = 'Windows XP';
			$user_os_img .= 'winxp.png';
		}
		elseif (strpos($http_user_agent_str, 'Windows NT 5.0'))
		{
			$user_os_ver = 'Windows 2000';
			$user_os_img .= 'win2000.png';
		}
		else
		{
			$user_os_ver = 'Windows';
			$user_os_img .= 'win.png';
		}
	}
	elseif (strpos($http_user_agent_str, 'Mac'))
	{
		$user_os_ver = 'Mac';
		$user_os_img .= 'mac.png';
	}
	elseif (strpos($http_user_agent_str, 'Linux'))
	{
		if (strpos($http_user_agent_str, 'Slackware'))
		{
			$user_os_ver = 'Slackware Linux';
			$user_os_img .= 'slackware.png';
		}
		elseif (strpos($http_user_agent_str, 'Mandrake'))
		{
			$user_os_ver = 'Mandrake Linux';
			$user_os_img .= 'mandrake.png';
		}
		elseif (strpos($http_user_agent_str, 'SuSE'))
		{
			$user_os_ver = 'SuSE Linux';
			$user_os_img .= 'suse.png';
		}
		elseif (strpos($http_user_agent_str, 'Novell'))
		{
			$user_os_ver = 'Novell Linux';
			$user_os_img .= 'novell.png';
		}
		elseif (strpos($http_user_agent_str, 'Ubuntu'))
		{
			$user_os_ver = 'Ubuntu Linux';
			$user_os_img .= 'ubuntu.png';
		}
		elseif (strpos($http_user_agent_str, 'Kubuntu'))
		{
			$user_os_ver = 'Kubuntu Linux';
			$user_os_img .= 'kubuntu.png';
		}
		elseif (strpos($http_user_agent_str, 'Xubuntu'))
		{
			$user_os_ver = 'Xubuntu Linux';
			$user_os_img .= 'xubuntu.png';
		}
		elseif (strpos($http_user_agent_str, 'Edubuntu'))
		{
			$user_os_ver = 'Edubuntu Linux';
			$user_os_img .= 'edubuntu.png';
		}
		elseif (strpos($http_user_agent_str, 'Debian'))
		{
			$user_os_ver = 'Debian Linux';
			$user_os_img .= 'debian.png';
		}
		elseif (strpos($http_user_agent_str, 'Red Hat'))
		{
			$user_os_ver = 'Red Hat Linux';
			$user_os_img .= 'redhat.png';
		}
		elseif (strpos($http_user_agent_str, 'Gentoo'))
		{
			$user_os_ver = 'Gentoo Linux';
			$user_os_img .= 'gentoo.png';
		}
		elseif (strpos($http_user_agent_str, 'Fedora'))
		{
			$user_os_ver = 'Fedora Linux';
			$user_os_img .= 'fedora.png';
		}
		else
		{
			$user_os_ver = 'Linux';
			$user_os_img .= 'linux.png';
		}
	}
	elseif (strpos($http_user_agent_str, 'Unix'))
	{
		$user_os_ver = 'Unix';
		$user_os_img .= 'unix.png';
	}
	elseif (strpos($http_user_agent_str, 'Nintendo DS'))
	{
		$user_os_ver = 'Nintendo DS';
		$user_os_img .= 'unknown.png';
	}
	else
	{
		$user_os_ver = $lang['UNKNOWN'];
		$user_os_img .= 'unknown.png';
	}

	$user_os['os'] = $user_os_ver;
	$user_os['img'] = '<img src="' . $user_os_img . '" alt="' . $user_os_ver . '" title="' . $user_os_ver . '" />';
	return $user_os;
}


function get_user_browser($http_user_agent_str)
{
	global $lang;
	$user_browser_img = 'images/http_agents/browsers/';

	if (ereg('MSIE ([0-9].[0-9]{1,2})', $http_user_agent_str, $log_version))
	{
		$user_browser_ver = 'MSIE ' . $log_version[1];
		$user_browser_img .= 'msie.png';
	}
	elseif (ereg('Opera/([0-9].[0-9]{1,2})', $http_user_agent_str, $log_version) || ereg('Opera ([0-9].[0-9]{1,2})', $http_user_agent_str, $log_version))
	{
		$user_browser_ver = 'Opera ' . $log_version[1];
		$user_browser_img .= 'opera.png';
	}
	elseif (ereg('Firefox/([0-9].[0-9]{1,2})', $http_user_agent_str, $log_version))
	{
		$user_browser_ver = 'Firefox ' . $log_version[1];
		$user_browser_img .= 'firefox.png';
	}
	elseif (ereg('Chrome/([0-9].[0-9]{1,2})', $http_user_agent_str, $log_version))
	{
		$user_browser_ver = 'Chrome ' . $log_version[1];
		$user_browser_img .= 'chrome.png';
	}
	elseif (ereg('Mozilla/([0-9].[0-9]{1,2})', $http_user_agent_str, $log_version))
	{
		$user_browser_ver = 'Mozilla ' . $log_version[1];
		$user_browser_img .= 'mozilla.png';
	}
	elseif (ereg('Konqueror/([0-9].[0-9]{1,2})', $http_user_agent_str, $log_version))
	{
		$user_browser_ver = 'Konqueror ' . $log_version[1];
		$user_browser_img .= 'konqueror.png';
	}
	else
	{
		$user_browser_ver = $lang['UNKNOWN'];
		$user_browser_img .= 'unknown.png';
	}

	$user_browser['browser'] = $user_browser_ver;
	$user_browser['img'] = '<img src="' . $user_browser_img . '" alt="' . $user_browser_ver . '" title="' . $user_browser_ver . '" />';
	return $user_browser;
}

?>