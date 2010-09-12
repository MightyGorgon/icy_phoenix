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
* Egor Naklonyaeff
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

function FormatLanguage($lng)
{
// You can add you ISO 639 coutry code here or remove unused codes
	$iso639 = array(
		'albanian' => 'sq',
		'arabic' => 'ar',
		'azerbaijani' => 'az',
		'bulgarian' => 'bg',
		'chinese' => 'zh',
		'chinese_simplified' => 'zh',
		'chinese_traditional' => 'zh',
		'croatian' => 'hr',
		'czech' => 'cs',
		'danish' => 'da',
		'dutch' => 'nl',
		'english' => 'en',
		'esperanto' => 'eo',
		'estonian' => 'et',
		'finnish' => 'fi',
		'french' => 'fr',
		'japanese' => 'ja',
		'galego' => 'gl',
		'german' => 'de',
		'greek' => 'el',
		'hungarian' => 'hu',
		'hebrew' => 'he',
		'icelandic' => 'is',
		'indonesian' => 'id',
		'italian' => 'it',
		'korean' => 'ko',
		'kurdish' => 'ku',
		'macedonian' => 'mk',
		'moldavian' => 'mo',
		'mongolian' => 'mn',
		'norwegian' => 'no',
		'polish' => 'pl',
		'portuguese' => 'pt',
		'romanian' => 'ro',
		'russian' => 'ru',
		'russian_tu' => 'ru',
		'serbian' => 'sr',
		'slovak' => 'sk',
		'slovenian' => 'sl',
		'spanish' => 'es',
		'swedish' => 'sv',
		'thai' => 'th',
		'turkish' => 'tr',
		'uigur' => 'ug',
		'ukrainian' => 'uk',
		'vietnamese' => 'vi',
		'welsh' => 'cy'
	);
	$user_lang=(isset($iso639[$lng]))? $iso639[$lng]:'';
	return(($user_lang!='')?"\n<language>$user_lang</language>":'');
}

function RSSTimeFormat($utime, $uoffset = 0)
{
	global $user_id, $useragent;
	if(CACHE_TO_FILE && ($user_id == ANONYMOUS) && empty($_GET))
	{
		$uoffset = 0;
	}
	if((isset($_GET['time']) && ($_GET['time'] == 'local'))|| (strpos($useragent,'Abilon') !== false)|| (strpos($useragent,'ActiveRefresh') !== false))
	{
		$uoffset = intval($uoffset);
	}
	else
	{
		$uoffset = 0;
	}
	$result = gmdate("D, d M Y H:i:s", $utime + (3600 * $uoffset));
	$uoffset = intval($uoffset * 100);
	$result .= ' ' . (($uoffset>0) ? '+' : '') . (($uoffset == 0)? 'GMT' : sprintf((($uoffset < 0) ? "%05d" : "%04d"), $uoffset));
	return $result;
}

function GetHTTPPasswd()
{
	header('WWW-Authenticate: Basic realm="For registered users only"');
	ExitWithHeader('401 Unauthorized','For registered users only');
}

function ExitWithHeader($output,$message='')
{
	global $db;
	// Close our DB connection.
	if (!empty($db))
	{
		$db->sql_close();
	}
	if(function_exists("getallheaders"))
	{
		header("HTTP/1.1 $output");
	}
	else
	{
		header('Status: ' . $output);
	}
	$code=intval(substr($output, 0, 3));
	if(($code == 200)||($code == 304))
	{
		if(isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) header("Last-Modified: " . $_SERVER['HTTP_IF_MODIFIED_SINCE']);
		if(isset($_SERVER['HTTP_IF_NONE_MATCH'])) header("Etag: " . $_SERVER['HTTP_IF_NONE_MATCH']);
	}
	if(!empty($message))
	{
		header ('Content-Type: text/plain');
		echo $message;
	}
	exit;
}

function rss_session_begin($user_id, $user_ip)
{
	global $db, $config;
	$page_array = extract_current_page(IP_ROOT_PATH);

	$forum_id = request_var(POST_FORUM_URL, 0);
	$forum_id = ($forum_id < 0) ? 0 : $forum_id;
	$topic_id = request_var(POST_TOPIC_URL, 0);
	$topic_id = ($topic_id < 0) ? 0 : $topic_id;

	$page_array['page_full'] .= (!empty($forum_id)) ? ((strpos($page_array['page_full'], '?') !== false) ? '&' : '?') . '_f_=' . (int) $forum_id . 'x' : '';
	$page_array['page_full'] .= (!empty($topic_id)) ? ((strpos($page_array['page_full'], '?') !== false) ? '&' : '?') . '_t_=' . (int) $topic_id . 'x' : '';
	if (function_exists('mysql_real_escape_string'))
	{
		$page_id = @mysql_real_escape_string(substr($page_array['page_full'], 0, 254));
	}
	else
	{
		$page_id = substr(str_replace('\'', '%27', $page_array['page_full']), 0, 254);
	}
	$user_id= (int) $user_id;
	$password = md5($_SERVER['PHP_AUTH_PW']);
	$last_visit = 0;
	$current_time = time();
	$expiry_time = $current_time - $config['session_length'];
	$sql = "SELECT *
		FROM " . USERS_TABLE . "
		WHERE user_id = " . $user_id;
	$db->sql_return_on_error(true);
	$result = $db->sql_query($sql);
	$db->sql_return_on_error(false);
	if (!$result)
	{
		ExitWithHeader('500 Internal Server Error', 'Could not obtain lastvisit data from user table');
	}
	$userdata = $db->sql_fetchrow($result);
	if (isset($userdata['user_level']) && ($userdata['user_level'] == JUNIOR_ADMIN))
	{
		$userdata['user_level'] = (!defined('IN_ADMIN') && !defined('IN_CMS')) ? ADMIN : MOD;
	}
	if(($user_id != ANONYMOUS) && (!$userdata || ($password != $userdata['user_password'])))
	{
		ExitWithHeader('500 Internal Server Error', 'Error while create session');
	}
	$login = ($user_id != ANONYMOUS) ? 1 : 0;

	// Initial ban check against user id, IP and email address
	preg_match('/(..)(..)(..)(..)/', $user_ip, $user_ip_parts);

	$sql = "SELECT ban_ip, ban_userid, ban_email
		FROM " . BANLIST_TABLE . "
		WHERE ban_ip IN ('" . $user_ip_parts[1] . $user_ip_parts[2] . $user_ip_parts[3] . $user_ip_parts[4] . "', '" . $user_ip_parts[1] . $user_ip_parts[2] . $user_ip_parts[3] . "ff', '" . $user_ip_parts[1] . $user_ip_parts[2] . "ffff', '" . $user_ip_parts[1] . "ffffff')
			OR ban_userid = $user_id";
	if ($user_id != ANONYMOUS)
	{
		$sql .= " OR ban_email LIKE '" . $db->sql_escape($userdata['user_email']) . "'
			OR ban_email LIKE '" . substr($db->sql_escape($userdata['user_email']), strpos($db->sql_escape($userdata['user_email']), "@")) . "'";
	}

	$db->sql_return_on_error(true);
	$result = $db->sql_query($sql);
	$db->sql_return_on_error(false);
	if (!$result)
	{
		ExitWithHeader("500 Internal Server Error","Could not obtain ban information");
	}

	if ($ban_info = $db->sql_fetchrow($result))
	{
		if ($ban_info['ban_ip'] || $ban_info['ban_userid'] || $ban_info['ban_email'])
		{
			ExitWithHeader("403 Forbidden", "You been banned");
		}
	}

	list($sec, $usec) = explode(' ', microtime());
	mt_srand((float) $sec + ((float) $usec * 100000));
	$session_id = md5(uniqid(mt_rand(), true));
	$sql = "INSERT INTO " . SESSIONS_TABLE . "
		(session_id, session_user_id, session_start, session_time, session_ip, session_page, session_logged_in, session_admin)
		VALUES ('" . $db->sql_escape($session_id) . "', $user_id, $current_time, $current_time, '$user_ip', '$page_id', $login, 0)";
	$db->sql_return_on_error(true);
	$result = $db->sql_query($sql);
	$db->sql_return_on_error(false);
	if (!$result)
	{
		ExitWithHeader("500 Internal Server Error", "Error creating new session");
	}
	$last_visit = ($userdata['user_session_time'] > 0) ? $userdata['user_session_time'] : $current_time;
	$sql = "UPDATE " . USERS_TABLE . " SET user_session_time = $current_time, user_session_page = '$page_id', user_lastvisit = $last_visit ";
	if(LV_MOD_INSTALLED)
	{
		$sql .= ", user_lastlogon = $current_time, user_totallogon = (user_totallogon + 1)";
	}
	$sql .=" WHERE user_id = $user_id";
	$db->sql_return_on_error(true);
	$result = $db->sql_query($sql);
	$db->sql_return_on_error(false);
	if (!$result)
	{
		ExitWithHeader("500 Internal Server Error", 'Error updating last visit time');
	}

	$userdata['user_lastvisit'] = $last_visit;
	$userdata['session_id'] = $session_id;
	$userdata['session_ip'] = $user_ip;
	$userdata['session_user_id'] = $user_id;
	$userdata['session_logged_in'] = $login;
	$userdata['session_page'] = $page_id;
	$userdata['session_start'] = $current_time;
	$userdata['session_time'] = $current_time;
	$userdata['session_admin'] = 0;
	$userdata['session_key']='';
	$SID = 'sid=' . $session_id;
	define('TEMP_SESSION',true);

	// Mighty Gorgon - BOT SESSION - BEGIN
	$userdata['is_bot'] = false;
	if ($userdata['user_id'] != ANONYMOUS)
	{
		$userdata['bot_id'] = false;
	}
	else
	{
		$userdata['bot_id'] = bots_parse($user_ip, $config['bots_color'], $user_agent, true);
		if ($userdata['bot_id'] !== false)
		{
			/*
			$userdata['user_id'] = BOT;
			$userdata['session_user_id'] = BOT;
			$userdata['session_logged_in'] = 1;
			*/
			$userdata['is_bot'] = true;
			bots_table_update(bots_parse($user_ip, $config['bots_color'], $user_agent, true, true));
		}
	}
	// Mighty Gorgon - BOT SESSION - END

	return $userdata;
}

function rss_session_end()
{
	global $db, $userdata;

	$session_id = $userdata['session_id'];
	$user_id = $userdata['user_id'];
	$sql = 'DELETE FROM ' . SESSIONS_TABLE . "
		WHERE session_id = '" . $db->sql_escape($session_id) . "'
		AND session_user_id = $user_id";
	$db->sql_return_on_error(true);
	$result = $db->sql_query($sql);
	$db->sql_return_on_error(false);
	if (!$result)
	{
		ExitWithHeader("500 Internal Server Error","Error delete session");
	}
}

function rss_get_user()
{
	global $db;
	if((!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW'])) && isset($_SERVER['REMOTE_USER']) && preg_match('/Basic\s+(.*)$/i', $_SERVER['REMOTE_USER'], $matches))
	{
		list($name, $password) = explode(':', base64_decode($matches[1]), 2);
		$_SERVER['PHP_AUTH_USER'] = strip_tags($name);
		$_SERVER['PHP_AUTH_PW'] = strip_tags($password);
	}
	if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW']))
	{
		$username = phpbb_clean_username($_SERVER['PHP_AUTH_USER']);
		$password = $_SERVER['PHP_AUTH_PW'];

		if(isset($_GET['uid']))
		{
			$uid = intval($_GET['uid']);
			$uid = (int) $uid;
			$user_data = get_userdata($uid, false);
			if (!empty($user_data['username']))
			{
				$username = $user_data['username']
			}
			else
			{
				GetHTTPPasswd();
			}
		}

		if (!function_exists('login_db'))
		{
			include(IP_ROOT_PATH . 'includes/auth_db.' . PHP_EXT);
		}
		$login_result = login_db($username, $password, false, true);

		if ($login_result['status'] === LOGIN_SUCCESS)
		{
			return $row['user_id'];
		}
		else
		{
			GetHTTPPasswd();
		}
	}
	else
	{
		GetHTTPPasswd();
	}
	return ANONYMOUS;
}

?>