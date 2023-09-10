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
* @Icy Phoenix is based on phpBB
* @copyright (c) 2008 phpBB Group
*
*/

/**
* Database auth plug-in for phpBB3
*
* Authentication plug-ins is largely down to Sergey Kanareykin, our thanks to him.
*
* This is for authentication via the integrated user table
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

/**
* Login function
*/
function login_db(&$username, &$password, $user_id = false, $increase_attempts = true)
{
	global $db, $config;

	// do not allow empty password
	if (!$password)
	{
		return array(
			'status' => LOGIN_ERROR_PASSWORD,
			'error_msg' => 'NO_PASSWORD_SUPPLIED',
			'user_row' => array('user_id' => ANONYMOUS),
		);
	}

	if (!$username)
	{
		return array(
			'status' => LOGIN_ERROR_USERNAME,
			'error_msg' => 'LOGIN_ERROR_USERNAME',
			'user_row' => array('user_id' => ANONYMOUS),
		);
	}

	// Username only!
	$sql_match = !empty($user_id) ? ("user_id = '" . $db->sql_escape($user_id) . "'") : ("username_clean = '" . $db->sql_escape(utf8_clean_string($username)) . "'");

	// Email only!
	//$sql_match = !empty($user_id) ? ("user_id = '" . $db->sql_escape($user_id) . "'") : ("user_email = '" . $db->sql_escape(utf8_clean_string($username)) . "'");

	// Username or email!
	//$sql_match = !empty($user_id) ? ("user_id = '" . $db->sql_escape($user_id) . "'") : (("username_clean = '" . $db->sql_escape(utf8_clean_string($username)) . "' OR user_email = '" . $db->sql_escape(utf8_clean_string($username)) . "'"));

	$sql = 'SELECT user_id, username, username_clean, user_password, user_passchg, user_pass_convert, user_email, user_active, user_level, user_login_attempts, user_last_login_attempt, user_first_name, user_last_name
		FROM ' . USERS_TABLE . '
		WHERE ' . $sql_match;
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);

	if (!$row)
	{
		return array(
			'status' => LOGIN_ERROR_USERNAME,
			'error_msg' => 'LOGIN_ERROR_USERNAME',
			'user_row' => array('user_id' => ANONYMOUS),
		);
	}

	// User inactive...
	if (empty($row['user_active']))
	{
		return array(
			'status' => LOGIN_ERROR_ACTIVE,
			'error_msg' => 'ACTIVE_ERROR',
			'user_row' => $row,
		);
	}

	$config['max_login_attempts'] = (int) $config['max_login_attempts'];
	$config['login_reset_time'] = (int) $config['login_reset_time'];

	// Check to see if user is allowed to login again... if his tries are exceeded
	if (!empty($config['max_login_attempts']) && !empty($row['user_last_login_attempt']) && !empty($config['max_login_attempts']) && ($row['user_last_login_attempt'] >= (time() - ($config['login_reset_time'] * 60))) && ($row['user_login_attempts'] >= ($config['max_login_attempts'] + 1)))
	{
		return array(
			'status' => LOGIN_ERROR_ATTEMPTS,
			'error_msg' => 'LOGIN_ATTEMPTS_EXCEEDED',
			'user_row' => array('user_id' => ANONYMOUS),
		);
	}

	// If there are too much login attempts, we need to check for a confirm image
	// Every auth module is able to define what to do by itself...
	if (!empty($config['max_login_attempts']) && ($row['user_login_attempts'] >= $config['max_login_attempts']))
	{
		/*
		// Visual Confirmation handling
		$captcha =& phpbb_captcha_factory::get_instance($config['captcha_plugin']);
		$captcha->init(CONFIRM_LOGIN);
		$vc_response = $captcha->validate();
		if ($vc_response)
		{
			return array(
				'status' => LOGIN_ERROR_ATTEMPTS,
				'error_msg' => 'LOGIN_ERROR_ATTEMPTS',
				'user_row' => $row,
			);
		}
		*/
		redirect(append_sid('login_captcha.' . PHP_EXT . '?uid=' . $row['user_id'], true));
	}

	// If the last login is more than x minutes ago, then reset the login tries/time
	if (!empty($config['login_reset_time']) && !empty($row['user_last_login_attempt']) && ($row['user_last_login_attempt'] < (time() - ($config['login_reset_time'] * 60))))
	{
		reset_login_attempts($row['user_id']);
		$row['user_last_login_attempt'] = 0;
		$row['user_login_attempts'] = 0;
	}

	// If the password convert flag is set we need to convert it
	if ($row['user_pass_convert'])
	{
		// in phpBB2 passwords were used exactly as they were sent, with addslashes applied
		$password_old_format = isset($_REQUEST['password']) ? (string) $_REQUEST['password'] : '';
		$password_new_format = '';

		set_var($password_new_format, stripslashes($password_old_format), 'string', true);

		if ($password == $password_new_format)
		{
			if (!function_exists('utf8_to_cp1252'))
			{
				include(IP_ROOT_PATH . 'includes/utf/data/recode_basic.' . PHP_EXT);
			}

			// cp1252 is phpBB2's default encoding, characters outside ASCII range might work when converted into that encoding
			// plain md5 support left in for conversions from other systems.
			if (((strlen($row['user_password']) == 34) && (phpbb_check_hash(md5($password_old_format), $row['user_password']) || phpbb_check_hash(md5(utf8_to_cp1252($password_old_format)), $row['user_password'])))
				|| ((strlen($row['user_password']) == 32) && ((md5($password_old_format) == $row['user_password']) || (md5(utf8_to_cp1252($password_old_format)) == $row['user_password']))))
			{
				// PROFILE EDIT BRIDGE - BEGIN
				$target_profile_data = array(
					'user_id' => $row['user_id'],
					'username' => $username,
					'first_name' => $row['user_first_name'],
					'last_name' => $row['user_last_name'],
					'password' => $password_new_format,
					'email' => $row['user_email']
				);
				include_once(IP_ROOT_PATH . 'includes/class_users.' . PHP_EXT);
				$class_users = new class_users();
				$class_users->profile_update($target_profile_data);
				unset($target_profile_data);
				// PROFILE EDIT BRIDGE - END

				$hash = phpbb_hash($password_new_format);

				// Update the password in the users table to the new format and remove user_pass_convert flag
				$sql = 'UPDATE ' . USERS_TABLE . '
					SET user_password = \'' . $db->sql_escape($hash) . '\',
						user_pass_convert = 0
					WHERE user_id = ' . $row['user_id'];
				$db->sql_query($sql);

				$row['user_pass_convert'] = 0;
				$row['user_password'] = $hash;
			}
			else
			{
				// Although we weren't able to convert this password we have to increase login attempt count to make sure this cannot be exploited
				if ($increase_attempts)
				{
					increase_login_attempts($row['user_id']);
				}

				return array(
					'status' => LOGIN_ERROR_PASSWORD_CONVERT,
					'error_msg' => 'LOGIN_ERROR_PASSWORD_CONVERT',
					'user_row' => $row,
				);
			}
		}
	}

	// Check password ...
	if (!$row['user_pass_convert'] && phpbb_check_hash($password, $row['user_password']))
	{
		// Check for old password hash...
		if (strlen($row['user_password']) == 32)
		{
			$hash = phpbb_hash($password);

			// Update the password in the users table to the new format
			$sql = 'UPDATE ' . USERS_TABLE . "
				SET user_password = '" . $db->sql_escape($hash) . "', user_pass_convert = 0
				WHERE user_id = {$row['user_id']}";
			$db->sql_query($sql);

			$row['user_password'] = $hash;
		}

		if ($row['user_login_attempts'] != 0)
		{
			reset_login_attempts($row['user_id']);
		}

		// Successful login... set user_login_attempts to zero...
		return array(
			'status' => LOGIN_SUCCESS,
			'error_msg' => false,
			'user_row' => $row,
		);
	}

	// Password incorrect - increase login attempts
	if ($increase_attempts)
	{
		increase_login_attempts($row['user_id']);
	}

	// Give status about wrong password...
	return array(
		'status' => LOGIN_ERROR_PASSWORD,
		'error_msg' => 'LOGIN_ERROR_PASSWORD',
		'user_row' => $row,
	);
}

/**
* Reset login attempts
*/
function reset_login_attempts($user_id)
{
	global $db;

	$user_id = (int) $user_id;
	$sql = 'UPDATE ' . USERS_TABLE . ' SET user_login_attempts = 0, user_last_login_attempt = 0 WHERE user_id = ' . $user_id;
	$result = $db->sql_query($sql);

	return true;
}

/**
* Increase login attempts
*/
function increase_login_attempts($user_id)
{
	global $db;

	$user_id = (int) $user_id;
	$sql = 'UPDATE ' . USERS_TABLE . ' SET user_login_attempts = user_login_attempts + 1, user_last_login_attempt = \'' . time() . '\' WHERE user_id = ' . $user_id;
	$result = $db->sql_query($sql);

	return true;
}

?>