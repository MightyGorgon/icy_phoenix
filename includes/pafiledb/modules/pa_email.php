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
* Todd - (todd@phparena.net) - (http://www.phparena.net)
*
*/

class pafiledb_email extends pafiledb_public
{
	function main($action)
	{
		global $template, $lang, $config, $pafiledb_config, $db, $images, $userdata, $debug;

		$file_id = request_var('file_id', 0);
		if (empty($file_id))
		{
			message_die(GENERAL_MESSAGE, $lang['File_not_exist']);
		}

		$sql = 'SELECT file_catid, file_name
			FROM ' . PA_FILES_TABLE . "
			WHERE file_id = $file_id";
		$result = $db->sql_query($sql);

		if(!$file_data = $db->sql_fetchrow($result))
		{
			message_die(GENERAL_MESSAGE, $lang['File_not_exist']);
		}

		$db->sql_freeresult($result);

		if((!$this->auth[$file_data['file_catid']]['auth_email']))
		{
			if (!$userdata['session_logged_in'])
			{
				redirect(append_sid(CMS_PAGE_LOGIN . '?redirect=dload.' . PHP_EXT . '&action=email&file_id=' . $file_id, true));
			}

			$message = sprintf($lang['Sorry_auth_email'], $this->auth[$file_data['file_catid']]['auth_email_type']);
			message_die(GENERAL_MESSAGE, $message);
		}

		if (isset($_POST['submit']))
		{
			// session id check
			$sid = request_post_var('sid', '');
			if (empty($sid) || ($sid != $userdata['session_id']))
			{
				message_die(GENERAL_ERROR, 'Invalid_session');
			}
			$error = false;

			$femail = request_var('femail', '');
			if (!empty($femail) && preg_match('/^[a-z0-9\.\-_\+]+@[a-z0-9\-_]+\.([a-z0-9\-_]+\.)*?[a-z]+$/is', $femail))
			{
				$user_email = $femail;
			}
			else
			{
				$error = true;
				$error_msg = (!empty($error_msg)) ? $error_msg . '<br />' . $lang['Email_invalid'] : $lang['Email_invalid'];
			}

			$username = request_var('fname', '', true);
			$sender_name = request_var('sname', '', true);

			if (!$userdata['session_logged_in'] || ($userdata['session_logged_in'] && ($sender_name != $userdata['username'])))
			{
				include(IP_ROOT_PATH . 'includes/functions_validate.' . PHP_EXT);

				$result = validate_username($username);
				if ($result['error'])
				{
					$error = true;
					$error_msg .= (!empty($error_msg)) ? '<br />' . $result['error_msg'] : $result['error_msg'];
				}
			}
			else
			{
				$sender_name = $userdata['username'];
			}

			if(!$userdata['session_logged_in'])
			{
				$semail = request_var('semail', '');
				if (!empty($semail) && preg_match('/^[a-z0-9\.\-_\+]+@[a-z0-9\-_]+\.([a-z0-9\-_]+\.)*?[a-z]+$/is', $femail))
				{
					$sender_email = $semail;
				}
				else
				{
					$error = true;
					$error_msg = (!empty($error_msg)) ? $error_msg . '<br />' . $lang['Email_invalid'] : $lang['Email_invalid'];
				}
			}
			else
			{
				$sender_email = $userdata['user_email'];
			}

			$subject = request_var('subject', '', true);
			$subject = htmlspecialchars_decode($subject, ENT_COMPAT);
			if (empty($subject))
			{
				$error = true;
				$error_msg = (!empty($error_msg)) ? $error_msg . '<br />' . $lang['Empty_subject_email'] : $lang['Empty_subject_email'];
			}

			$message = request_var('message', '', true);
			$message = htmlspecialchars_decode($message, ENT_COMPAT);
			if (empty($message))
			{
				$error = true;
				$error_msg = (!empty($error_msg)) ? $error_msg . '<br />' . $lang['Empty_message_email'] : $lang['Empty_message_email'];
			}

			if (!$error)
			{
				include(IP_ROOT_PATH . 'includes/emailer.' . PHP_EXT);

				$emailer = new emailer();

				$emailer->headers('X-AntiAbuse: Board servername - ' . trim($config['server_name']));
				$emailer->headers('X-AntiAbuse: User_id - ' . $userdata['user_id']);
				$emailer->headers('X-AntiAbuse: Username - ' . $userdata['username']);
				$emailer->headers('X-AntiAbuse: User IP - ' . decode_ip($user_ip));

				$emailer->use_template('profile_send_email', $user_lang);
				$emailer->to($user_email);
				$emailer->from($sender_email);
				$emailer->replyto($sender_email);
				$emailer->set_subject($subject);

				$emailer->assign_vars(array(
					'SITENAME' => $config['sitename'],
					'BOARD_EMAIL' => $config['board_email'],
					'FROM_USERNAME' => $sender_name,
					'TO_USERNAME' => $username,
					'MESSAGE' => $message
					)
				);

				$emailer->send();
				$emailer->reset();

				$message = $lang['Econf'] . '<br /><br />' . sprintf($lang['Click_return'], '<a href="' . append_sid('dload.' . PHP_EXT . '?action=file&amp;file_id=' . $file_id) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_forum'], '<a href="' . append_sid(CMS_PAGE_HOME) . '">', '</a>');

				message_die(GENERAL_MESSAGE, $message);
			}

			if ($error)
			{
				message_die(GENERAL_MESSAGE, $error_msg);
			}

		}


		$this->generate_category_nav($file_data['file_catid']);

		$template->assign_vars(array(
			'USER_LOGGED' => (!$userdata['session_logged_in']) ? true : false,
			'L_HOME' => $lang['Home'],
			'CURRENT_TIME' => sprintf($lang['Current_time'], create_date($config['default_dateformat'], time(), $config['board_timezone'])),

			'S_EMAIL_ACTION' => append_sid('dload.' . PHP_EXT),
			'S_HIDDEN_FIELDS' => '<input type="hidden" name="sid" value="' . $userdata['session_id'] . '" />',

			'L_INDEX' => sprintf($lang['Forum_Index'], $config['sitename']),
			'L_EMAIL' => $lang['Semail'],
			'L_EMAIL' => $lang['Emailfile'],
			'L_EMAILINFO' => $lang['Emailinfo'],
			'L_YNAME' => $lang['Yname'],
			'L_YEMAIL' => $lang['Yemail'],
			'L_FNAME' => $lang['Fname'],
			'L_FEMAIL' => $lang['Femail'],
			'L_ETEXT' => $lang['Etext'],
			'L_DEFAULTMAIL' => $lang['Defaultmail'],
			'L_SEMAIL' => $lang['Semail'],
			'L_ESUB' => $lang['Esub'],
			'L_EMPTY_SUBJECT_EMAIL' => $lang['Empty_subject_email'],
			'L_EMPTY_MESSAGE_EMAIL' => $lang['Empty_message_email'],

			'U_INDEX' => append_sid(CMS_PAGE_HOME),
			'U_DOWNLOAD_HOME' => append_sid('dload.' . PHP_EXT),
			'U_FILE_NAME' => append_sid('dload.' . PHP_EXT . '?action=file&amp;file_id=' . $file_id),

			'FILE_NAME' => $file_data['file_name'],
			'SNAME' => $userdata['username'],
			'SEMAIL' => $userdata['user_email'],
			'DOWNLOAD' => $pafiledb_config['settings_dbname'],
			'FILE_URL' => create_server_url() . 'dload.' . PHP_EXT . '?action=file&amp;file_id=' . $file_id,
			'ID' => $file_id
			)
		);
		$this->display($lang['Download'], 'pa_email_body.tpl');
	}
}

?>