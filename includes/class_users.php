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
	function create_user($user_data, $check_values = true, $batch_process = false)
	{
		global $db, $config, $cache, $user, $lang;

		if ($check_values)
		{
			if (!function_exists('validate_username'))
			{
				include_once(IP_ROOT_PATH . 'includes/functions_validate.' . PHP_EXT);
			}

			$error = false;

			// Validating username
			if (empty($user_data['username']))
			{
				$error = true;
				$error_msg .= ((isset($error_msg)) ? '<br />' : '') . $lang['Fields_empty'];
			}
			else
			{
				$result = validate_username($user_data['username']);
				if ($result['error'])
				{
					$error = true;
					$error_msg .= ((isset($error_msg)) ? '<br />' : '') . $result['error_msg'];
				}
			}

			// Validating password
			if (empty($user_data['user_password']))
			{
				$error = true;
				$error_msg .= ((isset($error_msg)) ? '<br />' : '') . $lang['Fields_empty'];
			}

			// Validating email
			if (empty($user_data['user_email']))
			{
				$error = true;
				$error_msg .= ((isset($error_msg)) ? '<br />' : '') . $lang['Fields_empty'];
			}
			else
			{
				$result = validate_email($user_data['user_email']);
				if ($result['error'])
				{
					$error = true;
					$error_msg .= ((isset($error_msg)) ? '<br />' : '') . $result['error_msg'];
				}
			}

			if (!empty($error) && $batch_process)
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

		$user_data = array(
			'user_id' => $user_id,
			'username' => $user_data['username'],
			'username_clean' => utf8_clean_string($user_data['username']),
			'user_first_name' => !empty($user_data['user_first_name']) ? $user_data['user_first_name'] : '',
			'user_last_name' => !empty($user_data['user_last_name']) ? $user_data['user_last_name'] : '',
			'user_password' => phpbb_hash($user_data['user_password']),
			'user_regdate' => !empty($user_data['user_regdate']) ? $user_data['user_regdate'] : time(),
			'user_email' => $user_data['user_email'],
			'user_email_hash' => phpbb_email_hash($user_data['user_email']),
			'user_website' => !empty($user_data['user_website']) ? $user_data['user_website'] : '',
			'user_phone' => !empty($user_data['user_phone']) ? $user_data['user_phone'] : '',
			'user_timezone' => !empty($user_data['user_timezone']) ? $user_data['user_timezone'] : $config['board_timezone'],
			'user_dateformat' => !empty($user_data['user_dateformat']) ? $user_data['user_dateformat'] : $config['default_dateformat'],
			'user_lang' => !empty($user_data['user_lang']) ? $user_data['user_lang'] : $config['default_lang'],
			'user_style' => !empty($user_data['user_style']) ? $user_data['user_style'] : $config['default_style'],
			'user_level' => !empty($user_data['user_level']) ? $user_data['user_level'] : 0,
			'user_rank' => !empty($user_data['user_rank']) ? $user_data['user_rank'] : 0,
			'user_active' => !empty($user_data['user_active']) ? $user_data['user_active'] : 1,
			'user_actkey' => !empty($user_data['user_actkey']) ? $user_data['user_actkey'] : 'user_actkey',
		);

		// PROFILE EDIT BRIDGE - BEGIN
		$target_profile_data = array(
			'user_id' => $user_data['user_id'],
			'username' => $user_data['username'],
			'first_name' => !empty($user_data['user_first_name']) ? $user_data['user_first_name'] : '',
			'last_name' => !empty($user_data['user_last_name']) ? $user_data['user_last_name'] : '',
			'password' => $user_data['user_password'],
			'email' => $user_data['user_email']
		);
		$this->profile_update($target_profile_data);
		unset($target_profile_data);
		// PROFILE EDIT BRIDGE - END

		$sql = "INSERT INTO " . USERS_TABLE . " " . $db->sql_build_insert_update($user_data, true);
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

	/*
	* User profile update: this function is called when username, email and password are changed in a user profile
	*/
	function profile_update($target_profile_data)
	{
		global $db, $cache, $config, $user, $lang;

		/*
		$target_profile_data = array(
			'user_id' => '',
			'username' => '',
			'first_name' => '',
			'last_name' => '',
			'password' => '',
			'email' => ''
		);
		*/
		//print_r($target_profile_data);

		// Plugins - BEGIN
		foreach ($cache->obtain_plugins_config() as $k => $plugin)
		{
			$plugin_class_name = 'class_' . $plugin['plugin_dir'] . '_profile_update';
			$plugin_class_file = IP_ROOT_PATH . PLUGINS_PATH . $plugin['plugin_dir'] . '/includes/' . $plugin_class_name . '.' . PHP_EXT;
			if (!empty($plugin['plugin_enabled']) && !empty($plugin['plugin_dir']) && file_exists($plugin_class_file))
			{
				include($plugin_class_file);
				$class_plugin_profile_update = new $plugin_class_name();
				$class_plugin_profile_update->profile_update($target_profile_data);
				unset($class_plugin_profile_update);
			}
		}
		// Plugins - END

		return true;
	}

}

?>