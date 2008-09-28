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

// Query Logs
function get_logs($logs_type, $logs_start = 0, $logs_number = 30, $logs_sort = 'log_id', $logs_sort_dir = 'DESC')
{
	global $db, $lang;
	$sql = "SELECT * FROM " . LOGS_TABLE . "
					ORDER BY " . $logs_sort . " " . $logs_sort_dir . "
					LIMIT " . $logs_start . ", " . $logs_number;
	if(!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not query logs table', $lang['Error'], __LINE__, __FILE__, $sql);
	}
	$logs = array();
	$logs_item = array();
	while ($logs = $db->sql_fetchrow($result))
	{
		$logs_item[] = $logs;
	}
	$db->sql_freeresult($result);
	return $logs_item;
}

// Query Logs
function parse_logs_action($log_id, $log_action_type, $log_desc, $log_username, $log_target)
{
	global $lang, $board_config;

	$logs_path = !empty($board_config['logs_path']) ? $board_config['logs_path'] : 'logs';

	$log_action['desc'] = '';
	$log_action['desc_extra'] = '';
	$log_action_array = explode(';', $log_desc);

	switch($log_action_type)
	{
		case 'POST_EDIT':
			$log_action['desc'] = $log_username . ' ' . $lang['LOGS_POST_EDIT'] . ' ' . $log_target . ' => <a href="' . append_sid(IP_ROOT_PATH . VIEWTOPIC_MG . '?' . POST_POST_URL . '=' . $log_action_array[0]) . '#p' . $log_action_array[0] . '">' . htmlspecialchars($log_action_array[1]) . '</a>';
			break;
		case 'POST_DELETE':
			$log_action['desc'] = $log_username . ' ' . $lang['LOGS_POST_DELETE'] . ' ' . $log_target . ' => [ID = ' . $log_action_array[0] . ']';
			break;
		case 'GROUP_JOIN':
			$group_name = get_group_name($log_action_array[0]);
			$log_action['desc'] = $log_username . ' ' . $lang['LOGS_GROUP_JOIN'] . ' <a href="' . append_sid(IP_ROOT_PATH . 'groupcp.' . PHP_EXT . '?' . POST_GROUPS_URL . '=' . $log_action_array[0]) . '">' . htmlspecialchars($group_name) . '</a>';
			break;
		case 'GROUP_EDIT':
			$group_name = get_group_name($log_action_array[0]);
			$log_action['desc'] = $log_username . ' ' . sprintf($lang['LOGS_GROUP_EDIT'], $log_target) . ' <a href="' . append_sid(IP_ROOT_PATH . 'groupcp.' . PHP_EXT . '?' . POST_GROUPS_URL . '=' . $log_action_array[0]) . '">' . htmlspecialchars($group_name) . '</a>';
			break;
		case 'GROUP_ADD':
			$group_name = get_group_name($log_action_array[0]);
			$log_action['desc'] = $log_username . ' ' . sprintf($lang['LOGS_GROUP_ADD'], $log_target) . ' <a href="' . append_sid(IP_ROOT_PATH . 'groupcp.' . PHP_EXT . '?' . POST_GROUPS_URL . '=' . $log_action_array[0]) . '">' . htmlspecialchars($group_name) . '</a>';
			break;
		case 'GROUP_TYPE':
			$group_name = get_group_name($log_action_array[0]);
			$log_action['desc'] = $log_username . ' ' . sprintf($lang['LOGS_GROUP_TYPE'], ' <a href="' . append_sid(IP_ROOT_PATH . 'groupcp.' . PHP_EXT . '?' . POST_GROUPS_URL . '=' . $log_action_array[0]) . '">' . htmlspecialchars($group_name) . '</a>', $lang['LOGS_GROUP_TYPE_' . $log_action_array[1]]);
			break;
		case 'MESSAGE':
			$log_action['desc'] = sprintf($lang['LOGS_MESSAGE'], $log_action_array[0]);
			$filename = IP_ROOT_PATH . $logs_path . '/error_log_' . $log_id . '.txt';
			if (file_exists($filename))
			{
				$file = fopen($filename,'r');
				$file_content = nl2br(fread($file, filesize($filename)));
				fclose($file);
				$log_action['desc_extra'] = $file_content;
			}
			break;
		case 'MODCP_DELETE':
			$log_action['desc'] = $log_username . ' ' . sprintf($lang['LOGS_MODCP_DELETE'], $log_target) . ' => [ID = ' . $log_action_array[0] . ']';
			break;
		case 'MODCP_RECYCLE':
			$log_action['desc'] = $log_username . ' ' . sprintf($lang['LOGS_MODCP_RECYCLE'], $log_target) . ' => [ID = ' . $log_action_array[0] . ']';
			break;
		case 'MODCP_LOCK':
			$log_action['desc'] = $log_username . ' ' . sprintf($lang['LOGS_MODCP_LOCK'], $log_target) . ' => [ID = ' . $log_action_array[0] . ']';
			break;
		case 'MODCP_UNLOCK':
			$log_action['desc'] = $log_username . ' ' . sprintf($lang['LOGS_MODCP_UNLOCK'], $log_target) . ' => [ID = ' . $log_action_array[0] . ']';
			break;
		case 'MODCP_MOVE':
			$log_action['desc'] = $log_username . ' ' . sprintf($lang['LOGS_MODCP_MOVE'], $log_target) . ' => [ID = ' . $log_action_array[0] . '] => <a href="' . append_sid(IP_ROOT_PATH . VIEWFORUM_MG . '?' . POST_FORUM_URL . '=' . $log_action_array[1]) . '">' . htmlspecialchars($log_action_array[2]) . '</a>';
			break;
		case 'MODCP_MERGE':
			$log_action['desc'] = $log_username . ' ' . sprintf($lang['LOGS_MODCP_MERGE'], $log_target) . ' => [ID = ' . $log_action_array[0] . '] => <a href="' . append_sid(IP_ROOT_PATH . VIEWTOPIC_MG . '?' . POST_TOPIC_URL . '=' . $log_action_array[1]) . '">' . htmlspecialchars($log_action_array[2]) . '</a>';
			break;
		case 'MODCP_SPLIT':
			$log_action['desc'] = $log_username . ' ' . sprintf($lang['LOGS_MODCP_SPLIT'], $log_target) . ' => [ID = ' . $log_action_array[0] . '] => "' . $log_action_array[3] . '" => <a href="' . append_sid(IP_ROOT_PATH . VIEWFORUM_MG . '?' . POST_FORUM_URL . '=' . $log_action_array[1]) . '">' . htmlspecialchars($log_action_array[2]) . '</a>';
			break;
		case 'TOPIC_BIN':
			$log_action['desc'] = $log_username . ' ' . $lang['LOGS_TOPIC_BIN'] . ' ' . $log_target . ' => <a href="' . append_sid(IP_ROOT_PATH . VIEWTOPIC_MG . '?' . POST_TOPIC_URL . '=' . $log_action_array[0]) . '">' . htmlspecialchars($log_action_array[1]) . '</a>';
			break;
		case 'TOPIC_ATTACK':
			$log_action['desc'] = $lang['LOGS_TOPIC_ATTACK'] . ' ' . $log_target . ' => <a href="' . append_sid(IP_ROOT_PATH . VIEWTOPIC_MG . '?' . POST_TOPIC_URL . '=' . $log_action_array[0]) . '">' . htmlspecialchars($log_action_array[1]) . '</a>';
			break;
		case 'CARD_BAN':
			$log_action['desc'] = $log_username . ' ' . $lang['LOGS_CARD_BAN'] . ' ' . $log_target;
			break;
		case 'CARD_WARN':
			$log_action['desc'] = $log_username . ' ' . $lang['LOGS_CARD_WARN'] . ' ' . $log_target;
			break;
		case 'CARD_UNBAN':
			$log_action['desc'] = $log_username . ' ' . $lang['LOGS_CARD_UNBAN'] . ' ' . $log_target;
			break;
		case 'ADMIN_CAT_ADD':
			$log_action['desc'] = $log_username . ' ' . $lang['LOGS_ADMIN_CAT_ADD'] . ' => ' . $log_action_array[0];
			break;
		case 'ADMIN_FORUM_AUTH':
			$log_action['desc'] = $log_username . ' ' . $lang['LOGS_ADMIN_FORUM_AUTH'] . ' => ' . $log_action_array[0];
			break;
		case 'ADMIN_DB_UTILITIES_BACKUP':
			$log_action['desc'] = $log_username . ' ' . sprintf($lang['LOGS_ADMIN_DB_UTILITIES_BACKUP'], $lang['LOGS_ADMIN_DB_UTILITIES_BACKUP_' . $log_action_array[0]]) . $lang['LOGS_ADMIN_DB_UTILITIES_BACKUP_' . $log_action_array[1]];
			break;
		case 'ADMIN_DB_UTILITIES_RESTORE':
			$log_action['desc'] = $log_username . ' ' . $lang['LOGS_ADMIN_DB_UTILITIES_RESTORE'] . ' ' . $log_action_array[0];
			break;
		case 'ADMIN_BOARD_CONFIG':
			$log_action['desc'] = $log_username . ' ' . $lang['LOGS_ADMIN_BOARD_CONFIG'];
			break;
		case 'ADMIN_BOARD_IP_CONFIG':
			$log_action['desc'] = $log_username . ' ' . $lang['LOGS_ADMIN_BOARD_IP_CONFIG'];
			break;
		case 'ADMIN_GROUP_NEW':
			$log_action['desc'] = $log_username . ' ' . $lang['LOGS_ADMIN_GROUP_NEW'] . ' ' . $log_action_array[0];
			break;
		case 'ADMIN_GROUP_DELETE':
			$log_action['desc'] = $log_username . ' ' . $lang['LOGS_ADMIN_GROUP_DELETE'] . ' ' . $log_action_array[0];
			break;
		case 'ADMIN_GROUP_EDIT':
			$log_action['desc'] = $log_username . ' ' . $lang['LOGS_ADMIN_GROUP_EDIT'] . ' ' . $log_action_array[0];
			break;
		case 'ADMIN_USER_AUTH':
			$log_action['desc'] = $log_username . ' ' . $lang['LOGS_ADMIN_USER_AUTH'] . ' ' . $log_target . ' (' . $log_action_array[0] . ')';
			break;
		case 'ADMIN_GROUP_AUTH':
			$group_name = get_group_name($log_action_array[0]);
			$log_action['desc'] = $log_username . ' ' . $lang['LOGS_ADMIN_GROUP_AUTH'] . ' <a href="' . append_sid(IP_ROOT_PATH . 'groupcp.' . PHP_EXT . '?' . POST_GROUPS_URL . '=' . $log_action_array[0]) . '">' . htmlspecialchars($group_name) . '</a>';
			break;
		case 'ADMIN_USER_BAN':
			$log_action['desc'] = $log_username . ' ' . $lang['LOGS_ADMIN_USER_BAN'] . ' => ' . $log_action_array[0] . ' (' . $log_target . ')';
			break;
		case 'ADMIN_USER_UNBAN':
			$log_action['desc'] = $log_username . ' ' . $lang['LOGS_ADMIN_USER_UNBAN'] . ' => ' . $log_action_array[0];
			break;
		case 'ADMIN_USER_DELETE':
			$log_action['desc'] = $log_username . ' ' . $lang['LOGS_ADMIN_USER_DELETE'] . ' ' . $log_action_array[0];
			break;
		case 'ADMIN_USER_EDIT':
			$log_action['desc'] = $log_username . ' ' . $lang['LOGS_ADMIN_USER_EDIT'] . ' ' . $log_target;
			break;
		case 'CMS_LAYOUT_EDIT':
			$log_action['desc'] = $log_username . ' ' . sprintf($lang['LOGS_CMS_LAYOUT_EDIT'], '<a href="' . append_sid(IP_ROOT_PATH . 'index.' . PHP_EXT . '?page=' . $log_action_array[0]) . '">', '</a>');
			break;
		case 'CMS_LAYOUT_DELETE':
			$log_action['desc'] = $log_username . ' ' . sprintf($lang['LOGS_CMS_LAYOUT_DELETE'], $log_action_array[0]);
			break;
		case 'CMS_BLOCK_EDIT':
			$log_action['desc'] = $log_username . ' ' . sprintf($lang['LOGS_CMS_BLOCK_EDIT'], $log_action_array[0], '<a href="' . append_sid(IP_ROOT_PATH . 'index.' . PHP_EXT . '?page=' . $log_action_array[1]) . '">', '</a>');
			break;
		case 'CMS_BLOCK_EDIT_LS':
			$log_action['desc'] = $log_username . ' ' . sprintf($lang['LOGS_CMS_BLOCK_EDIT_LS'], $log_action_array[0], $log_action_array[1]);
			break;
		case 'CMS_BLOCK_DELETE':
			$log_action['desc'] = $log_username . ' ' . sprintf($lang['LOGS_CMS_BLOCK_DELETE'], $log_action_array[0], '<a href="' . append_sid(IP_ROOT_PATH . 'index.' . PHP_EXT . '?page=' . $log_action_array[1]) . '">', '</a>');
			break;
		case 'CMS_BLOCK_DELETE_LS':
			$log_action['desc'] = $log_username . ' ' . sprintf($lang['LOGS_CMS_BLOCK_DELETE_LS'], $log_action_array[0], $log_action_array[1]);
			break;
	}

	return $log_action;
}

function get_group_name($group_id)
{
	global $db;

	$sql = "SELECT group_name FROM " . GROUPS_TABLE . " WHERE group_id = '" . $group_id . "'";
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not obtain group color', '', __LINE__, __FILE__, $sql);
	}
	$row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	return $row['group_name'];
}

function delete_error_log_file($file_array = '')
{
	global $board_config;

	$logs_path = !empty($board_config['logs_path']) ? $board_config['logs_path'] : 'logs';

	$skip_files = array(
		'.',
		'..',
		'.htaccess',
		'index.htm',
		'index.html',
		'index.' . PHP_EXT,
	);
	$dir = IP_ROOT_PATH . $logs_path . '/';
	$res = @opendir($dir);
	while(($file = readdir($res)) !== false)
	{
		$file_full_path = $dir . $file;
		if ($file_array == '')
		{
			if (!in_array($file, $skip_files) && (substr($file, 0, 10) == 'error_log_'))
			{
				$res2 = @unlink($file_full_path);
			}
		}
		else
		{
			if (in_array($file, $file_array) && (substr($file, 0, 10) == 'error_log_'))
			{
				$res2 = @unlink($file_full_path);
			}
		}
	}
	closedir($res);

	return true;
}

?>