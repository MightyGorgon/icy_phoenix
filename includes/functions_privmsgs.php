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
* Private Messages Class
*/
class privmsgs
{
	/*
	* PM Send
	*/
	function send($sender_id, $recipient_id, $pm_subject, $pm_text)
	{
		global $db;

		// We should not need this anymore...
		/*
		$pm_subject = ip_addslashes($pm_subject);
		$pm_text = ip_addslashes($pm_text);
		*/

		$pm_time = time();

		$sql_input_array = array(
			'privmsgs_type' => PRIVMSGS_NEW_MAIL,
			'privmsgs_subject' => $pm_subject,
			'privmsgs_text' => $pm_text,
			'privmsgs_from_userid' => $sender_id,
			'privmsgs_to_userid' => $recipient_id,
			'privmsgs_date' => $pm_time,
			'privmsgs_enable_html' => 0,
			'privmsgs_enable_bbcode' => 1,
			'privmsgs_enable_smilies' => 1,
			'privmsgs_attach_sig' => 1,
		);
		$sql_insert = $db->sql_build_insert_update($sql_input_array, true);
		$sql = "INSERT INTO " . PRIVMSGS_TABLE . $sql_insert;
		if (!$db->sql_query($sql))
		{
			return false;
		}

		// Add to the users new pm counter
		$sql = "UPDATE " . USERS_TABLE . "
			SET user_new_privmsg = user_new_privmsg + 1, user_last_privmsg = " . $pm_time . "
			WHERE user_id = " . $recipient_id;
		$status = $db->sql_query($sql);

		return true;
	}

	/*
	* PM Notification
	*/
	function notification($sender_id, $recipient_id, $recipient_email, $email_subject, $email_text, $use_bcc = false, $pm_subject = '', $recipient_username = '', $recipient_lang = '', $emty_email_template = false)
	{
		global $db, $board_config, $userdata, $user_ip, $lang;

		require(IP_ROOT_PATH . 'includes/emailer.' . PHP_EXT);

		$recipient_lang = empty($recipient_lang) ? $board_config['default_lang'] : $recipient_lang;

		// Let's do some checking to make sure that mass mail functions are working in win32 versions of php.
		if (preg_match('/[c-z]:\\\.*/i', getenv('PATH')) && !$board_config['smtp_delivery'])
		{
			$ini_val = (@phpversion() >= '4.0.0') ? 'ini_get' : 'get_cfg_var';
			// We are running on windows, force delivery to use our smtp functions since php's are broken by default
			$board_config['smtp_delivery'] = 1;
			$board_config['smtp_host'] = @$ini_val('SMTP');
		}

		$emailer = new emailer($board_config['smtp_delivery']);

		$email_headers = 'X-AntiAbuse: Board servername - ' . trim($board_config['server_name']) . "\n";
		$email_headers .= 'X-AntiAbuse: User_id - ' . $userdata['user_id'] . "\n";
		$email_headers .= 'X-AntiAbuse: Username - ' . $userdata['username'] . "\n";
		$email_headers .= 'X-AntiAbuse: User IP - ' . decode_ip($user_ip) . "\n";

		$emailer->extra_headers($email_headers);
		$emailer->from($board_config['board_email']);
		$emailer->replyto($board_config['board_email']);

		if ($use_bcc)
		{
			$emailer->email_address($board_config['board_email']);
			$emailer->bcc($recipient_email);
		}
		else
		{
			$emailer->email_address($recipient_email);
		}

		$emailer->set_subject($email_subject);

		if ($emty_email_template)
		{
			$emailer->use_template('admin_send_email', $recipient_lang);
			$emailer->assign_vars(array(
				'SITENAME' => $board_config['sitename'],
				'BOARD_EMAIL' => $board_config['board_email'],
				'MESSAGE' => $email_text
				)
			);
		}
		else
		{
			$script_name = preg_replace('/^\/?(.*?)\/?$/', "\\1", trim($board_config['script_path']));
			$script_name = ($script_name != '') ? $script_name . '/privmsg.' . PHP_EXT : 'privmsg.' . PHP_EXT;
			$server_name = trim($board_config['server_name']);
			$server_protocol = ($board_config['cookie_secure']) ? 'https://' : 'http://';
			$server_port = ($board_config['server_port'] <> 80) ? ':' . trim($board_config['server_port']) . '/' : '/';

			$recipient_username = empty($recipient_username) ? $lang['User'] : $recipient_username;
			$emailer->use_template('privmsg_notify', $recipient_lang);
			$emailer->assign_vars(array(
				'USERNAME' => $recipient_username,
				'SITENAME' => $board_config['sitename'],
				'EMAIL_SIG' => (!empty($board_config['board_email_sig'])) ? str_replace('<br />', "\n", "-- \n" . $board_config['board_email_sig']) : '',
				'FROM' => $userdata['username'],
				'DATE' => create_date($board_config['default_dateformat'], time(), $board_config['board_timezone']),
				'SUBJECT' => $pm_subject,
				'PRIV_MSG_TEXT' => $email_text,
				'FROM_USERNAME' => $userdata['username'],
				'U_INBOX' => $server_protocol . $server_name . $server_port . $script_name . '?folder=inbox'
				)
			);
		}

		$emailer->send();
		$emailer->reset();

		return true;
	}

	/*
	* Remove older messages
	*/
	function delete_older_message($privmsgs_type, $user_id)
	{
		global $db, $board_config;

		$sql_where = '';
		$max_folder_items = false;

		$user_id = intval($user_id);
		if (!$user_id || ($user_id <= 2))
		{
			return false;
		}

		switch ($privmsgs_type)
		{
			case 'PM_INBOX':
				$max_folder_items = $board_config['max_inbox_privmsgs'];
				$sql_where = "(privmsgs_type = " . PRIVMSGS_NEW_MAIL . " OR privmsgs_type = " . PRIVMSGS_READ_MAIL . " OR privmsgs_type = " . PRIVMSGS_UNREAD_MAIL . ")
											AND privmsgs_to_userid = '" . $user_id . "'";
				break;
			case 'PM_SAVED':
				$max_folder_items = $board_config['max_savebox_privmsgs'];
				$sql_where = "((privmsgs_to_userid = '" . $user_id . "' AND privmsgs_type = " . PRIVMSGS_SAVED_IN_MAIL . ")
											OR (privmsgs_from_userid = '" . $user_id . "' AND privmsgs_type = " . PRIVMSGS_SAVED_OUT_MAIL . "))";
				break;
			default:
				return false;
		}

		// See if recipient reached folder limit
		$sql = "SELECT COUNT(privmsgs_id) AS folder_items, MIN(privmsgs_date) AS oldest_post_time
			FROM " . PRIVMSGS_TABLE . "
			WHERE " . $sql_where;
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not obtain PM info', '', __LINE__, __FILE__, $sql);
		}

		if ($folder_info = $db->sql_fetchrow($result))
		{
			$current_folder_items = $folder_info['folder_items'];
			$db->sql_freeresult($result);

			if ($max_folder_items && ($current_folder_items >= $max_folder_items))
			{
				$sql = "SELECT privmsgs_id FROM " . PRIVMSGS_TABLE . "
					WHERE " . $sql_where . "
					ORDER BY privmsgs_date ASC";
				if (!$result = $db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'Could not find oldest privmsgs', '', __LINE__, __FILE__, $sql);
				}
				while (($this_pm = $db->sql_fetchrow($result)) && ($current_folder_items >= $max_folder_items))
				{
					$sql_del = "DELETE FROM " . PRIVMSGS_TABLE . "
						WHERE privmsgs_id = '" . $this_pm['privmsgs_id'] . "'";
					if (!$result_sql = $db->sql_query($sql_del))
					{
						message_die(GENERAL_ERROR, 'Could not delete oldest privmsgs', '', __LINE__, __FILE__, $sql_del);
					}
					$current_folder_items--;
				}
				$db->sql_freeresult($result);
			}
		}
		return true;
	}

}


?>