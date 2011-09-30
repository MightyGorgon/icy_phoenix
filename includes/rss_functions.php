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
	$result .= ' ' . (($uoffset > 0) ? '+' : '') . (($uoffset == 0)? 'GMT' : sprintf((($uoffset < 0) ? "%05d" : "%04d"), $uoffset));
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

	if (function_exists('mysql_real_escape_string'))
	{
		$page_id = @mysql_real_escape_string(substr($page_array['page_full'], 0, 254));
	}
	else
	{
		$page_id = substr(str_replace('\'', '%27', $page_array['page_full']), 0, 254);
	}
	$user_id = (int) $user_id;
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
	$user->data = $db->sql_fetchrow($result);
	if (isset($user->data['user_level']) && ($user->data['user_level'] == JUNIOR_ADMIN))
	{
		$user->data['user_level'] = (!defined('IN_ADMIN') && !defined('IN_CMS')) ? ADMIN : MOD;
	}
	if(($user_id != ANONYMOUS) && (empty($user->data) || ($password != $user->data['user_password'])))
	{
		ExitWithHeader('500 Internal Server Error', 'Error while create session');
	}
	$login = ($user_id != ANONYMOUS) ? 1 : 0;

	$is_banned = check_ban($user_id, $user->ip, $user->data['user_email'], true);

	if ($is_banned)
	{
		ExitWithHeader("403 Forbidden", "You have been banned");
	}

	list($sec, $usec) = explode(' ', microtime());
	mt_srand((float) $sec + ((float) $usec * 100000));
	$session_id = md5(uniqid(mt_rand(), true));
	$sql = "INSERT INTO " . SESSIONS_TABLE . "
		(session_id, session_user_id, session_start, session_time, session_ip, session_page, session_forum_id, session_topic_id, session_logged_in, session_admin)
		VALUES ('" . $db->sql_escape($session_id) . "', $user_id, $current_time, $current_time, '" . $db->sql_escape($user_ip) . "', '" . $db->sql_escape($page_id) . "', '" . $db->sql_escape($forum_id) . "', '" . $db->sql_escape($topic_id) . "', $login, 0)";
	$db->sql_return_on_error(true);
	$result = $db->sql_query($sql);
	$db->sql_return_on_error(false);
	if (!$result)
	{
		ExitWithHeader("500 Internal Server Error", "Error creating new session");
	}
	$last_visit = ($user->data['user_session_time'] > 0) ? $user->data['user_session_time'] : $current_time;
	$sql = "UPDATE " . USERS_TABLE . " SET user_session_time = $current_time, user_session_page = '$page_id', user_lastvisit = $last_visit ";
	if(LV_MOD_INSTALLED)
	{
		$sql .= ", user_totallogon = (user_totallogon + 1)";
	}
	$sql .=" WHERE user_id = $user_id";
	$db->sql_return_on_error(true);
	$result = $db->sql_query($sql);
	$db->sql_return_on_error(false);
	if (!$result)
	{
		ExitWithHeader("500 Internal Server Error", 'Error updating last visit time');
	}

	$user->data['user_lastvisit'] = $last_visit;
	$user->data['session_id'] = $session_id;
	$user->data['session_ip'] = $user_ip;
	$user->data['session_user_id'] = $user_id;
	$user->data['session_logged_in'] = $login;
	$user->data['session_page'] = $page_id;
	$user->data['session_forum_id'] = $forum_id;
	$user->data['session_topic_id'] = $topic_id;
	$user->data['session_start'] = $current_time;
	$user->data['session_time'] = $current_time;
	$user->data['session_admin'] = 0;
	$user->data['session_key']='';
	$SID = 'sid=' . $session_id;
	define('TEMP_SESSION',true);

	// Mighty Gorgon - BOT SESSION - BEGIN
	$user->data['is_bot'] = false;
	if ($user->data['user_id'] != ANONYMOUS)
	{
		$user->data['bot_id'] = false;
	}
	else
	{
		$bot_name_tmp = bots_parse($user_ip, $config['bots_color'], $user_agent, true);
		$user->data['bot_id'] = $bot_name_tmp['name'];
		if ($user->data['bot_id'] !== false)
		{
			$user->data['is_bot'] = true;
			bots_table_update($bot_name_tmp['id']);
		}
	}
	// Mighty Gorgon - BOT SESSION - END

	return $user->data;
}

function rss_session_end()
{
	global $db, $user;

	$session_id = $user->data['session_id'];
	$user_id = $user->data['user_id'];
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
				$username = $user_data['username'];
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