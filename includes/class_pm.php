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
class class_pm
{
	/*
	* PM Send
	*/
	function send($sender_id, $recipient_id, $pm_subject, $pm_text, $attach_sig = true, $html_status = false, $bbcode_status = true, $smilies_status = true, $acro_auto_status = false)
	{
		global $db;

		// We should not need this anymore...
		/*
		$pm_subject = addslashes($pm_subject);
		$pm_text = addslashes($pm_text);
		*/

		$attach_sig = $attach_sig ? 1 : 0;
		$html_status = $html_status ? 1 : 0;
		$bbcode_status = $bbcode_status ? 1 : 0;
		$smilies_status = $smilies_status ? 1 : 0;
		$acro_auto_status = $acro_auto_status ? 1 : 0;

		$pm_time = time();

		$sql_input_array = array(
			'privmsgs_type' => PRIVMSGS_NEW_MAIL,
			'privmsgs_subject' => $pm_subject,
			'privmsgs_text' => $pm_text,
			'privmsgs_from_userid' => $sender_id,
			'privmsgs_to_userid' => $recipient_id,
			'privmsgs_date' => $pm_time,
			'privmsgs_enable_html' => $html_status,
			'privmsgs_enable_bbcode' => $bbcode_status,
			'privmsgs_enable_smilies' => $smilies_status,
			'privmsgs_enable_autolinks_acronyms' => $acro_auto_status,
			'privmsgs_attach_sig' => $attach_sig,
		);
		$sql_insert = $db->sql_build_insert_update($sql_input_array, true);
		$sql = "INSERT INTO " . PRIVMSGS_TABLE . $sql_insert;
		$db->sql_return_on_error(true);
		$result = $db->sql_query($sql);
		$db->sql_return_on_error(false);
		if (!$result)
		{
			return false;
		}

		// Add to the users new pm counter
		$sql = "UPDATE " . USERS_TABLE . "
			SET user_new_privmsg = user_new_privmsg + 1, user_last_privmsg = " . $pm_time . "
			WHERE user_id = " . $recipient_id;
		$db->sql_return_on_error(true);
		$result = $db->sql_query($sql);
		$db->sql_return_on_error(false);
		if (!$result)
		{
			return false;
		}

		return true;
	}

	/*
	* PM Notification
	*/
	function notification($sender_id, $recipient_id, $recipient_email, $email_subject, $email_text, $use_bcc = false, $pm_subject = '', $recipient_username = '', $recipient_lang = '', $emty_email_template = false)
	{
		global $db, $config, $userdata, $lang, $user_ip;

		require(IP_ROOT_PATH . 'includes/emailer.' . PHP_EXT);

		$recipient_lang = empty($recipient_lang) ? $config['default_lang'] : $recipient_lang;

		// Let's do some checking to make sure that mass mail functions are working in win32 versions of php.
		if (preg_match('/[c-z]:\\\.*/i', getenv('PATH')) && !$config['smtp_delivery'])
		{
			// We are running on windows, force delivery to use our smtp functions since php's are broken by default
			$config['smtp_delivery'] = 1;
			$config['smtp_host'] = @ini_get('SMTP');
		}

		$emailer = new emailer($config['smtp_delivery']);

		$email_headers = 'X-AntiAbuse: Board servername - ' . trim($config['server_name']) . "\n";
		$email_headers .= 'X-AntiAbuse: User_id - ' . $userdata['user_id'] . "\n";
		$email_headers .= 'X-AntiAbuse: Username - ' . $userdata['username'] . "\n";
		$email_headers .= 'X-AntiAbuse: User IP - ' . decode_ip($user_ip) . "\n";

		$emailer->extra_headers($email_headers);
		$emailer->from($config['board_email']);
		$emailer->replyto($config['board_email']);

		if ($use_bcc)
		{
			$emailer->email_address($config['board_email']);
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
				'SITENAME' => $config['sitename'],
				'BOARD_EMAIL' => $config['board_email'],
				'MESSAGE' => $email_text
				)
			);
		}
		else
		{
			$script_name = preg_replace('/^\/?(.*?)\/?$/', "\\1", trim($config['script_path']));
			$script_name = ($script_name != '') ? $script_name . '/privmsg.' . PHP_EXT : CMS_PAGE_PRIVMSG;
			$server_name = trim($config['server_name']);
			$server_protocol = ($config['cookie_secure']) ? 'https://' : 'http://';
			$server_port = ($config['server_port'] <> 80) ? ':' . trim($config['server_port']) . '/' : '/';

			$recipient_username = empty($recipient_username) ? $lang['User'] : $recipient_username;
			$emailer->use_template('privmsg_notify', $recipient_lang);
			$emailer->assign_vars(array(
				'USERNAME' => $recipient_username,
				'SITENAME' => $config['sitename'],
				'EMAIL_SIG' => (!empty($config['board_email_sig'])) ? str_replace('<br />', "\n", $config['sig_line'] . " \n" . $config['board_email_sig']) : '',
				'FROM' => $userdata['username'],
				'DATE' => create_date($config['default_dateformat'], time(), $config['board_timezone']),
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
		global $db, $config;

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
				$max_folder_items = $config['max_inbox_privmsgs'];
				$sql_where = "(privmsgs_type = " . PRIVMSGS_NEW_MAIL . " OR privmsgs_type = " . PRIVMSGS_READ_MAIL . " OR privmsgs_type = " . PRIVMSGS_UNREAD_MAIL . ")
											AND privmsgs_to_userid = '" . $user_id . "'";
				break;
			case 'PM_SAVED':
				$max_folder_items = $config['max_savebox_privmsgs'];
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
		$result = $db->sql_query($sql);

		if ($folder_info = $db->sql_fetchrow($result))
		{
			$current_folder_items = $folder_info['folder_items'];
			$db->sql_freeresult($result);

			if ($max_folder_items && ($current_folder_items >= $max_folder_items))
			{
				$sql = "SELECT privmsgs_id FROM " . PRIVMSGS_TABLE . "
					WHERE " . $sql_where . "
					ORDER BY privmsgs_date ASC";
				$result = $db->sql_query($sql);

				while (($this_pm = $db->sql_fetchrow($result)) && ($current_folder_items >= $max_folder_items))
				{
					$sql_del = "DELETE FROM " . PRIVMSGS_TABLE . "
						WHERE privmsgs_id = '" . $this_pm['privmsgs_id'] . "'";
					$result_sql = $db->sql_query($sql_del);
					$current_folder_items--;
				}
				$db->sql_freeresult($result);
			}
		}
		return true;
	}

	/*
	* Check if user is flooding
	*/
	function is_flood()
	{
		global $db, $config, $userdata;

		$return = false;
		$sql = "SELECT MAX(privmsgs_date) AS last_post_time
			FROM " . PRIVMSGS_TABLE . "
			WHERE privmsgs_from_userid = " . $userdata['user_id'];
		$result = $db->sql_query($sql);
		if ($db_row = $db->sql_fetchrow($result))
		{
			$ip_pm_flood_time = (int) $db_row['last_post_time'] + (int) $config['flood_interval'];
			if ($ip_pm_flood_time >= time())
			{
				$return = true;
			}
		}

		return $return;
	}

}

?>