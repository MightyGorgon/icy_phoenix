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

/**
* This class manages some users functions
*/
class class_users
{

	/*
	* Create user
	*/
	function create_user($username, $user_password, $user_email, $user_style, $user_lang, $user_dateformat, $user_timezone, $check_values = true, $batch_process = false)
	{
		global $db, $config, $userdata, $lang;

		if ($check_values)
		{
			if (!function_exists('validate_username'))
			{
				include_once(IP_ROOT_PATH . 'includes/functions_validate.' . PHP_EXT);
			}

			$error = false;

			// Validating username
			if (empty($username))
			{
				$error = true;
				$error_msg .= ((isset($error_msg)) ? '<br />' : '') . $lang['Fields_empty'];
			}
			else
			{
				$result = validate_username($username);
				if ($result['error'])
				{
					$error = true;
					$error_msg .= ((isset($error_msg)) ? '<br />' : '') . $result['error_msg'];
				}
			}

			// Validating password
			if (empty($user_password))
			{
				$error = true;
				$error_msg .= ((isset($error_msg)) ? '<br />' : '') . $lang['Fields_empty'];
			}

			// Validating email
			if (empty($user_email))
			{
				$error = true;
				$error_msg .= ((isset($error_msg)) ? '<br />' : '') . $lang['Fields_empty'];
			}
			else
			{
				$result = validate_email($user_email);
				if ($result['error'])
				{
					$error = true;
					$error_msg .= ((isset($error_msg)) ? '<br />' : '') . $result['error_msg'];
				}
			}

			if ($batch_process)
			{
				return false;
			}

			if ($error)
			{
				message_die(GENERAL_MESSAGE, $error_msg);
			}
		}

		$sql = "SELECT MAX(user_id) AS total FROM " . USERS_TABLE;
		$db->sql_return_on_error(true);
		$result = $db->sql_query($sql);
		$db->sql_return_on_error(false);
		if (!$result)
		{
			if ($batch_process)
			{
				return false;
			}
			message_die(GENERAL_ERROR, 'Could not obtain next user_id information', '', __LINE__, __FILE__, $sql);
		}

		if (!($row = $db->sql_fetchrow($result)))
		{
			if ($batch_process)
			{
				return false;
			}
			message_die(GENERAL_ERROR, 'Could not obtain next user_id information', '', __LINE__, __FILE__, $sql);
		}

		$user_id = $row['total'] + 1;
		$user_password = md5($user_password);

		$sql = "INSERT INTO " . USERS_TABLE . " (user_id, username, user_regdate, user_password, user_email, user_style, user_timezone, user_dateformat, user_lang, user_level, user_active, user_actkey)
			VALUES ($user_id, '" . $db->sql_escape($username) . "', " . time() . ", '" . $db->sql_escape($user_password) . "', '" . $db->sql_escape($user_email) . "', $user_style, $user_timezone, '" . $db->sql_escape($user_dateformat) . "', '" . $db->sql_escape($user_lang) . "', 0, 1, 'user_actkey')";
		$db->sql_return_on_error(true);
		$db->sql_transaction('begin');
		$result = $db->sql_query($sql);
		$db->sql_return_on_error(false);
		if (!$result)
		{
			if ($batch_process)
			{
				return false;
			}
			message_die(GENERAL_ERROR, 'Could not insert data into users table', '', __LINE__, __FILE__, $sql);
		}

		$sql = "INSERT INTO " . GROUPS_TABLE . " (group_name, group_description, group_single_user, group_moderator) VALUES ('', 'Personal User', 1, 0)";
		$db->sql_return_on_error(true);
		$result = $db->sql_query($sql);
		$db->sql_return_on_error(false);
		if (!$result)
		{
			if ($batch_process)
			{
				return false;
			}
			message_die(GENERAL_ERROR, 'Could not insert data into groups table', '', __LINE__, __FILE__, $sql);
		}
		$group_id = $db->sql_nextid();

		$sql = "INSERT INTO " . USER_GROUP_TABLE . " (user_id, group_id, user_pending) VALUES ($user_id, $group_id, 0)";
		$db->sql_return_on_error(true);
		$result = $db->sql_query($sql);
		$db->sql_transaction('commit');
		$db->sql_return_on_error(false);
		if (!$result)
		{
			if ($batch_process)
			{
				return false;
			}
			message_die(GENERAL_ERROR, 'Could not insert data into groups table', '', __LINE__, __FILE__, $sql);
		}

		if (!$batch_process)
		{
			board_stats();
		}

		return true;
	}

}

?>