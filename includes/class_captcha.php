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
* This class is used for CAPTCHA
*/
class class_captcha
{

	var $attempts_limit = 3;
	var $code_length = 6;

	/*
	* Creates CAPTCHA image
	*/
	function build_captcha()
	{
		global $db, $cache, $config, $template, $user, $lang;

		// Clean old sessions and old confirm codes
		$user->confirm_gc();

		// Generate the required confirmation code
		$confirm_image = '';
		$code = unique_id();
		// 0 (zero) could get confused with O (the letter) so we change it
		//$code = substr(str_replace(array('0'), array('Z'), strtoupper(base_convert($code, 16, 35))), 2, 6);
		// Easiest to read charset... some letters and numbers may be ambiguous
		$code = substr(str_replace(array('0', '1', '2', '5', 'O', 'I', 'Z', 'S'), array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'), strtoupper(base_convert($code, 16, 35))), 2, $this->code_length);
		$confirm_id = md5(uniqid($user->ip));
		$sql = "INSERT INTO " . CONFIRM_TABLE . " (confirm_id, session_id, code)
			VALUES ('" . $db->sql_escape($confirm_id) . "', '" . $db->sql_escape($user->data['session_id']) . "', '" . $db->sql_escape($code) . "')";
		$result = $db->sql_query($sql);
		unset($code);
		$server_url = create_server_url();
		$confirm_image = '<img src="' . append_sid($server_url . CMS_PAGE_PROFILE . '?mode=confirm&amp;confirm_id=' . $confirm_id) . '" alt="" title="" />';

		$template->assign_vars(array(
			'S_CAPTCHA' => true,
			'CONFIRM_IMG' => $confirm_image,
			'CAPTCHA_HIDDEN' => '<input type="hidden" name="confirm_id" value="' . $confirm_id . '" />',
			'CAPTCHA_CODE_LENGTH' => $this->code_length,

			'L_CONFIRM_CODE_IMPAIRED' => sprintf($lang['CONFIRM_CODE_IMPAIRED'], '<a href="mailto:' . $config['board_email'] . '">', '</a>'),
			)
		);

		$return_array = array('confirm_id' => $confirm_id, 'confirm_image' => $confirm_image);
		return $return_array;
	}

	/*
	* Check number of attempts
	*/
	function check_attempts($return = false)
	{
		global $db, $cache, $config, $user, $lang;

		$return_value = true;

		// Check number of attempts for current session
		$sql = "SELECT COUNT(session_id) AS attempts
			FROM " . CONFIRM_TABLE . "
			WHERE session_id = '" . $db->sql_escape($user->data['session_id']) . "'";
		$result = $db->sql_query($sql);
		if ($row = $db->sql_fetchrow($result))
		{
			if ($row['attempts'] > $this->attempts_limit)
			{
				if ($return)
				{
					$return_value = false;
				}
				else
				{
					message_die(GENERAL_MESSAGE, $lang['TOO_MANY_ATTEMPTS']);
				}
			}
		}
		$db->sql_freeresult($result);

		return $return_value;
	}

	/*
	* Check CAPTCHA
	*/
	function check_code()
	{
		global $db, $cache, $config, $user, $lang;

		$return_array = array('error' => false, 'error_msg' => '');

		$confirm_id = request_post_var('confirm_id', '');
		$confirm_code = request_post_var('confirm_code', '');
		if (empty($confirm_id))
		{
			$return_array['error'] = true;
			$return_array['error_msg'] = $lang['CONFIRM_CODE_WRONG'];
		}
		else
		{
			if (!preg_match('/^[A-Za-z0-9]+$/', $confirm_id))
			{
				$confirm_id = '';
			}

			$sql = "SELECT code
				FROM " . CONFIRM_TABLE . "
				WHERE confirm_id = '" . $db->sql_escape($confirm_id) . "'
					AND session_id = '" . $db->sql_escape($user->data['session_id']) . "'";
			$result = $db->sql_query($sql);
			if ($row = $db->sql_fetchrow($result))
			{
				if ($row['code'] != $confirm_code)
				{
					$return_array['error'] = true;
					$return_array['error_msg'] = $lang['CONFIRM_CODE_WRONG'];
				}
				else
				{
					// Maybe better reset the whole session_id and not only the confirmation code...
					/*
					$sql = "DELETE FROM " . CONFIRM_TABLE . "
						WHERE confirm_id = '" . $db->sql_escape($confirm_id) . "'
							AND session_id = '" . $db->sql_escape($user->data['session_id']) . "'";
					*/
					$sql = "DELETE FROM " . CONFIRM_TABLE . " WHERE session_id = '" . $db->sql_escape($user->data['session_id']) . "'";
					$result = $db->sql_query($sql);
				}
			}
			else
			{
				$return_array['error'] = true;
				$return_array['error_msg'] = $lang['CONFIRM_CODE_WRONG'];
			}
			$db->sql_freeresult($result);
		}

		if ($return_array['error'])
		{
			$this->check_attempts(false);
		}

		return $return_array;
	}

}

?>