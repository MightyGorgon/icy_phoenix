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

function ip_log($content, $db_log, $error_log = false)
{
	global $REQUEST_URI, $REMOTE_ADDR, $HTTP_USER_AGENT, $SERVER_NAME, $HTTP_REFERER;
	global $board_config, $lang, $userdata, $db;

	$db_log_actions = (($board_config['db_log_actions'] == '1') || ($board_config['db_log_actions'] == '2')) ? true : false;

	$page_array = extract_current_page(IP_ROOT_PATH);

	switch($page_array['page_name'])
	{
		case 'memberlist.' . PHP_EXT:
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

	if ($board_config['mg_log_actions'] == true)
	{
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

		$datecode = date('Ymd');
		$logs_path = !empty($board_config['logs_path']) ? $board_config['logs_path'] : 'logs';
		$log_file = IP_ROOT_PATH . $logs_path . '/mg_log_' . $datecode . '.txt';
		$fp = fopen ($log_file, "a+");
		fwrite($fp, $message);
		fclose($fp);
	}

	if ($db_log_actions == true)
	{
		if ($db_log['target'] !='' )
		{
			$db_target = explode(',', $db_log['target']);
			foreach ($db_target as $db_target_data)
			{
				$sql = "INSERT INTO " . LOGS_TABLE . " (log_time, log_page, log_user_id, log_action, log_desc, log_target)
					VALUES ('" . time() ."', '" . $page_array['page'] . "', '" . $userdata['user_id'] . "', '" . $db_log['action'] . "', '" . $db_log['desc'] . "', '" . $db_target_data . "')";
				if(!$result = $db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'Could not insert data into logs table', $lang['Error'], __LINE__, __FILE__, $sql);
				}
			}
		}
		else
		{
			$sql = "SELECT MAX(log_id) max_log_id FROM " . LOGS_TABLE . "";
			if(!($result = $db->sql_query($sql)))
			{
				message_die(CRITICAL_ERROR, 'Could not query log information', $lang['Error'], __LINE__, __FILE__, $sql);
			}
			$row = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			$new_log_id = $row['max_log_id'] + 1;

			$sql = "INSERT INTO " . LOGS_TABLE . " (log_id, log_time, log_page, log_user_id, log_action, log_desc, log_target)
				VALUES ('" . $new_log_id . "', '" . time() ."', '" . $page_array['page'] . "', '" . $userdata['user_id'] . "', '" . $db_log['action'] . "', '" . $db_log['desc'] . "', '')";
			if(!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not insert data into logs table', $lang['Error'], __LINE__, __FILE__, $sql);
			}
			if (($error_log) && $board_config['db_log_actions'] == '2')
			{
				$datecode = date('Ymd');
				$logs_path = !empty($board_config['logs_path']) ? $board_config['logs_path'] : 'logs';
				$log_file = IP_ROOT_PATH . $logs_path . '/error_log_' . $new_log_id . '.txt';
				$fp = fopen ($log_file, "a+");
				$message = '';
				//$message .= '[CODE: ' . $error_log['code'] . ']';
				$message .= "\n";
				$message .= '<b>' . $error_log['title'] . '</b>';
				$message .= "\n";
				$message .= "\n";
				$message .= $error_log['text'] . "\n";
				fwrite($fp, $message);
				fclose($fp);
			}
		}
	}
	//die('TRUE');
	return true;
}

?>