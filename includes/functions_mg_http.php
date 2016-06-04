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
	$HTTP_USER_AGENT = (!empty($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '');
	return $HTTP_USER_AGENT;
}

function get_user_referer()
{
	$HTTP_REFERER = (!empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '');
	return $HTTP_REFERER;
}

function get_user_os($http_user_agent_str)
{
	global $lang;

	$user_os_img_path = 'images/http_agents/os/';
	$user_os_ver = $lang['UNKNOWN'];
	$user_os_img = $user_os_img_path . 'unknown.png';

	$os = array(
		'bot' => array(
			'main' => array('str_pos' => array('bot','spider','crawler'), 'name' => 'Bot', 'icon' => 'bot.png'),
			'sub' => array(
				'bot_google' => array('str_pos' => array('Googlebot'), 'name' => 'Googlebot', 'icon' => 'bot.png'),
				'bot_bing' => array('str_pos' => array('bingbot'), 'name' => 'Bingbot', 'icon' => 'bot.png'),
				'bot_omgili' => array('str_pos' => array('omgilibot'), 'name' => 'Omgili Crawler', 'icon' => 'bot.png'),
				'bot_baidu' => array('str_pos' => array('Baiduspider'), 'name' => 'Baidu Spider', 'icon' => 'bot.png'),
				'bot_msn' => array('str_pos' => array('msnbot'), 'name' => 'MSN Bot', 'icon' => 'bot.png'),
			),
		),
		'windows' => array(
			'main' => array('str_pos' => array('Win'), 'name' => 'Windows', 'icon' => 'windows.png'),
			'sub' => array(
				'windows_10' => array('str_pos' => array('Windows NT 10.0'), 'name' => 'Windows 10', 'icon' => 'windows_10.png'),
				'windows_8' => array('str_pos' => array('Windows NT 6.2'), 'name' => 'Windows 8', 'icon' => 'windows_8.png'),
				'windows_seven' => array('str_pos' => array('Windows NT 6.1'), 'name' => 'Windows 7', 'icon' => 'windows_seven.png'),
				'windows_vista' => array('str_pos' => array('Windows NT 6.0'), 'name' => 'Windows Vista', 'icon' => 'windows_vista.png'),
				'windows_2003' => array('str_pos' => array('Windows NT 5.2'), 'name' => 'Windows 2003', 'icon' => 'windows_2003.png'),
				'windows_xp' => array('str_pos' => array('Windows NT 5.1'), 'name' => 'Windows XP', 'icon' => 'windows_xp.png'),
				'windows_2000' => array('str_pos' => array('Windows NT 5.0'), 'name' => 'Windows 2000', 'icon' => 'windows_2000.png'),
			),
		),
		'ipad' => array('main' => array('str_pos' => array('iPad'), 'name' => 'iPad', 'icon' => 'ipad_2.png')),
		'iphone' => array('main' => array('str_pos' => array('iPhone'), 'name' => 'iPhone', 'icon' => 'iphone_4.png')),
		'mac' => array('main' => array('str_pos' => array('Mac'), 'name' => 'Mac', 'icon' => 'mac_osx.png')),
		'android' => array('main' => array('str_pos' => array('Android'), 'name' => 'Android', 'icon' => 'android.png')),
		'symbian' => array('main' => array('str_pos' => array('Symb'), 'name' => 'Symbian OS', 'icon' => 'symbian.png')),
		'linux' => array(
			'main' => array('str_pos' => array('Linux'), 'name' => 'Linux', 'icon' => 'linux.png'),
			'sub' => array(
				'linux_slackware' => array('str_pos' => array('Slackware'), 'name' => 'Slackware Linux', 'icon' => 'linux_slackware.png'),
				'linux_mandrake' => array('str_pos' => array('Mandrake'), 'name' => 'Mandrake Linux', 'icon' => 'linux_mandrake.png'),
				'linux_mandriva' => array('str_pos' => array('Mandriva'), 'name' => 'Mandriva Linux', 'icon' => 'linux_mandriva.png'),
				'linux_suse' => array('str_pos' => array('SuSE'), 'name' => 'SuSE Linux', 'icon' => 'linux_suse.png'),
				'linux_novell' => array('str_pos' => array('Novell'), 'name' => 'Novell Linux', 'icon' => 'linux_novell.png'),
				'linux_ubuntu' => array('str_pos' => array('Ubuntu'), 'name' => 'Ubuntu Linux', 'icon' => 'linux_ubuntu.png'),
				'linux_kubuntu' => array('str_pos' => array('Kubuntu'), 'name' => 'Kubuntu Linux', 'icon' => 'linux_kubuntu.png'),
				'linux_xubuntu' => array('str_pos' => array('Xubuntu'), 'name' => 'Xubuntu Linux', 'icon' => 'linux_xubuntu.png'),
				'linux_edubuntu' => array('str_pos' => array('Edubuntu'), 'name' => 'Edubuntu Linux', 'icon' => 'linux_edubuntu.png'),
				'linux_debian' => array('str_pos' => array('Debian'), 'name' => 'Debian Linux', 'icon' => 'linux_debian.png'),
				'linux_redhat' => array('str_pos' => array('Red Hat'), 'name' => 'Red Hat Linux', 'icon' => 'linux_redhat.png'),
				'linux_gentoo' => array('str_pos' => array('Gentoo'), 'name' => 'Gentoo Linux', 'icon' => 'linux_gentoo.png'),
				'linux_fedora' => array('str_pos' => array('Fedora'), 'name' => 'Fedora Linux', 'icon' => 'linux_fedora.png'),
			),
		),
		'unix' => array('main' => array('str_pos' => array('Unix'), 'name' => 'Unix', 'icon' => 'unix.png')),
		'nintendo' => array('main' => array('str_pos' => array('Nintendo'), 'name' => 'Nintendo', 'icon' => 'nintendo.png')),
	);

	$os_processed = false;
	foreach ($os as $os_data)
	{
		if (!empty($os_data['main']['str_pos']) && is_array($os_data['main']['str_pos']))
		{
			foreach ($os_data['main']['str_pos'] as $os_str_pos)
			{
				if (strpos(strtolower($http_user_agent_str), strtolower($os_str_pos)) !== false)
				{
					$user_os_ver = $os_data['main']['name'];
					if (!empty($os_data['main']['icon']))
					{
						$user_os_img = $user_os_img_path . $os_data['main']['icon'];
					}
					$os_processed = true;
					break 1;
				}
			}
		}
		if (!empty($os_processed) && !empty($os_data['sub']) && is_array($os_data['sub']))
		{
			foreach ($os_data['sub'] as $sub_os_data)
			{
				if (!empty($sub_os_data['str_pos']) && is_array($sub_os_data['str_pos']))
				{
					foreach ($sub_os_data['str_pos'] as $sub_os_str_pos)
					{
						if (strpos(strtolower($http_user_agent_str), strtolower($sub_os_str_pos)) !== false)
						{
							$user_os_ver = $sub_os_data['name'];
							if (!empty($sub_os_data['icon']))
							{
								$user_os_img = $user_os_img_path . $sub_os_data['icon'];
							}
							break 3;
						}
					}
				}
			}
		}
		if (!empty($os_processed))
		{
			break 1;
		}
	}

	$user_os['os'] = $user_os_ver;
	$user_os['img'] = '<img src="' . $user_os_img . '" alt="' . $user_os_ver . '" title="' . $user_os_ver . '" />';
	return $user_os;
}

function get_user_browser($http_user_agent_str)
{
	global $lang;

	$user_browser_img_path = 'images/http_agents/browsers/';
	$user_browser_ver = $lang['UNKNOWN'];
	$user_browser_img = $user_browser_img_path . 'unknown.png';

	// Order is important... do not move browsers unless you know what you are doing!!!
	$browsers = array(
		'edge' => array('regex' => array('/Edge\/([0-9]{1,2}.[0-9]{1,4})/'), 'check_version' => false, 'name' => 'MS Edge', 'icon' => 'edge.png'),
		'msie' => array('regex' => array('/MSIE ([0-9]{1,2}.[0-9]{1,4})/'), 'check_version' => false, 'name' => 'MS Internet Explorer', 'icon' => 'msie.png'),
		'opera' => array('regex' => array('/Opera\/([0-9]{1,2}.[0-9]{1,4})/', '/Opera ([0-9]{1,2}.[0-9]{1,4})/'), 'check_version' => true, 'name' => 'Opera', 'icon' => 'opera.png'),
		'firefox' => array('regex' => array('/Firefox\/([0-9]{1,2}.[0-9]{1,4})/'), 'check_version' => false, 'name' => 'Firefox', 'icon' => 'firefox.png'),
		// Iron must be before Chrome
		'iron' => array('regex' => array('/Iron\/([0-9]{1,2}.[0-9]{1,4})/'), 'check_version' => false, 'name' => 'Iron', 'icon' => 'iron.png'),
		// Chromium must be before Chrome
		'chromium' => array('regex' => array('/Chromium\/([0-9]{1,2}.[0-9]{1,4})/'), 'check_version' => false, 'name' => 'Chromium', 'icon' => 'chromium.png'),
		// ChromePlus must be before Chrome
		'chrome_plus' => array('regex' => array('/ChromePlus\/([0-9]{1,2}.[0-9]{1,4})/'), 'check_version' => false, 'name' => 'ChromePlus', 'icon' => 'chrome_plus.png'),
		// CoolNovo must be before Chrome
		'coolnovo' => array('regex' => array('/CoolNovo\/([0-9]{1,2}.[0-9]{1,4})/'), 'check_version' => false, 'name' => 'CoolNovo', 'icon' => 'coolnovo.png'),
		// Chrome must be before Safari
		'chrome' => array('regex' => array('/Chrome\/([0-9]{1,2}.[0-9]{1,4})/'), 'check_version' => false, 'name' => 'Chrome', 'icon' => 'chrome.png'),
		'gingerbread' => array('regex' => array('/\/GINGERBREAD/'), 'check_version' => true, 'name' => 'Gingerbread', 'icon' => 'gingerbread.png',),
		'mobilesafari' => array('regex' => array('/Mobile Safari\/([0-9]{1,5}.[0-9]{1,5})/'), 'check_version' => true, 'name' => 'Safari Mobile', 'icon' => 'safari.png',),
		'safari' => array('regex' => array('/Safari\/([0-9]{1,5}.[0-9]{1,5})/'), 'check_version' => true, 'name' => 'Safari', 'icon' => 'safari.png'),
		'konqueror' => array('regex' => array('/Konqueror\/([0-9]{1,2}.[0-9]{1,4})/'), 'check_version' => false, 'name' => 'Konqueror', 'icon' => 'konqueror.png'),
		'facebook' => array('regex' => array('/facebookexternalhit\/([0-9]{1,2}.[0-9]{1,4})/'), 'check_version' => true, 'name' => 'Facebook', 'icon' => 'facebook.png'),
		'vivaldi' => array('regex' => array('/Vivaldi\/([0-9]{1,2}.[0-9]{1,4})/'), 'check_version' => false, 'name' => 'Vivaldi', 'icon' => 'vivaldi.png'),
		// Mozilla must be the last one!!!
		'mozilla' => array('regex' => array('/Mozilla\/([0-9]{1,2}.[0-9]{1,4})/'), 'check_version' => false, 'name' => 'Mozilla', 'icon' => 'mozilla.png'),
	);

	foreach ($browsers as $browser_data)
	{
		if (!empty($browser_data['regex']) && is_array($browser_data['regex']))
		{
			foreach ($browser_data['regex'] as $browser_regex)
			{
				if (!empty($browser_regex))
				{
					if (preg_match($browser_regex, $http_user_agent_str, $log_version))
					{
						$version_f = '';
						$version = '';
						if (!empty($browser_data['check_version']))
						{
							$version_f = get_user_browser_version($http_user_agent_str);
						}
						$version = (!empty($version_f) ? $version_f : (!empty($log_version[1]) ? $log_version[1] : ''));
						if (!empty($browser_data['name']))
						{
							$user_browser_ver = $browser_data['name'] . (!empty($version) ? (' ' . $version) : '');
							if (!empty($browser_data['icon']))
							{
								$user_browser_img = $user_browser_img_path . $browser_data['icon'];
							}
						}
						break 2;
					}
				}
			}
		}
	}

	$user_browser['browser'] = $user_browser_ver;
	$user_browser['img'] = '<img src="' . $user_browser_img . '" alt="' . $user_browser_ver . '" title="' . $user_browser_ver . '" />';
	return $user_browser;
}

function get_user_browser_version($http_user_agent_str)
{
	$version = '';
	if (preg_match('/Version\/([0-9]{1,2}.[0-9]{1,4})/', $http_user_agent_str, $log_version))
	{
		$version = $log_version[1];
	}

	return $version;
}

?>