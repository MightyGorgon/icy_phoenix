<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

if (!defined('IN_PHPBB'))
{
	die('Hacking attempt');
}

function mg_log($content)
{
	global $REQUEST_URI, $REMOTE_ADDR, $HTTP_USER_AGENT, $SERVER_NAME, $HTTP_REFERER;
	global $board_config, $lang, $phpbb_root_path, $userdata;

	$datecode = date('Ymd');
	$logs_path = !empty($board_config['logs_path']) ? $board_config['logs_path'] : 'logs';
	$log_file = $phpbb_root_path . $logs_path . '/mg_log_' . $datecode . '.txt';

	$phpbb_root_path = ($phpbb_root_path == '') ? '.' : $phpbb_root_path;
	$page_array = extract_current_page($phpbb_root_path);

	switch($page_array['page_name'])
	{
		case 'memberlist.' . $phpEx:
			return true;
			break;
		case POSTING_MG:
			if ((strpos(strtolower($page_array['query_string']), strtolower('mode=quote')) !== false) || (strpos(strtolower($page_array['query_string']), strtolower('mode=smilies')) !== false) || (strpos(strtolower($page_array['query_string']), strtolower('mode=thank')) !== false) || (strpos(strtolower($page_array['query_string']), strtolower('mode=topicreview')) !== false))
			{
				return true;
			}
			break;
		case PROFILE_MG:
			if ($userdata['user_id'] == ANONYMOUS)
			{
				return true;
			}
			break;
		case SEARCH_MG:
			return true;
			break;
		case VIEWTOPIC_MG:
			if ($userdata['user_id'] == ANONYMOUS)
			{
				return true;
			}
			break;
	}

	$remote_address = (!empty($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : ((!empty($_ENV['REMOTE_ADDR'])) ? $_ENV['REMOTE_ADDR'] : getenv('REMOTE_ADDR'));
	$user_agent = (!empty($_SERVER['HTTP_USER_AGENT']) ? trim($_SERVER['HTTP_USER_AGENT']) : (!empty($_ENV['HTTP_USER_AGENT']) ? trim($_ENV['HTTP_USER_AGENT']) : trim(getenv('HTTP_USER_AGENT'))));
	$referer = (!empty($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : getenv('HTTP_REFERER');
	$referer = preg_replace('/sid=[A-Za-z0-9]{32}/', '', $referer);

	$date = date('Y/m/d - H:i:s');

	$message = '[' . $date . ']';
	$message .= ' [USER_ID: ' . $userdata['user_id'] . ' ]';
	$message .= ' [REQ: ' . $page_array['page'] . ' ]';
	$message .= ' [IP: ' . $remote_address . ']';
	//$message .= ' [CLIENT: ' . $user_agent . ']';
	$message .= ' [REF: ' . $referer . ']';
	$message .= "\n";
	$message .= $content;
	$message .= "\n";
	$message .= "\n";
	$fp = fopen ($log_file, "a+");
	fwrite($fp, $message);
	fclose($fp);
	//die('TRUE');
	return true;
}

// Prepare a string for database submission?
function mg_str_prep($str)
{
	// most databases use a single-quote to escape a single-quote - rather than addslashes()
	// phpBB has however prepended single-quotes with a backslash in the $HTTP_-arrays
	$str = str_replace("\'", "''", $str);
	return $str;
}

// Cleanup function to avoid XSS attacks etc - admin at automapit dot com
function mg_clean_markup($str)
{
	$search = array('@<script[^>]*?>.*?</script>@si',	// Strip out javascript
		'@<[\/\!]*?[^<>]*?>@si',												// Strip out HTML tags
		'@<style[^>]*?>.*?</style>@siU',								// Strip style tags properly
		'@<![\s\S]*?--[ \t\n\r]*>@'											// Strip multi-line comments including CDATA
	);
	$str = preg_replace($search, '', $str);
	while($str != strip_tags($str))
	{
		$str = strip_tags($str);
	}
	return $str;
}

?>