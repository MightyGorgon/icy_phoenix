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

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
	exit;
}

include(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);
global $bbcode, $board_config;

// Is send through board enabled? No, return to index
if (!$board_config['board_email_form'])
{
	redirect(append_sid(FORUM_MG, true));
}

if (!empty($_GET[POST_USERS_URL]) || !empty($_POST[POST_USERS_URL]))
{
	$user_id_dest = (!empty($_GET[POST_USERS_URL])) ? intval($_GET[POST_USERS_URL]) : intval($_POST[POST_USERS_URL]);
}
else
{
	message_die(GENERAL_MESSAGE, $lang['No_user_specified']);
}

if (!$userdata['session_logged_in'])
{
	redirect(append_sid(LOGIN_MG . '?redirect=' . PROFILE_MG . '&mode=email&' . POST_USERS_URL . '=' . $user_id_dest, true));
}

$sql = "SELECT username, user_email, user_viewemail, user_lang, user_level
	FROM " . USERS_TABLE . "
	WHERE user_id = $user_id_dest";
if ($result = $db->sql_query($sql))
{
	if ($row = $db->sql_fetchrow($result))
	{

		$username = $row['username'];
		$user_email = $row['user_email'];
		$user_lang = $row['user_lang'];
		$user_level = $row['user_level'];

		if ($board_config['emails_only_to_admins'] == true)
		{
			if (($user_level != JUNIOR_ADMIN) && ($user_level != ADMIN))
			{
				message_die(GENERAL_MESSAGE, $lang['Emails_Only_To_Admins_Error']);
			}
		}

		if ($row['user_viewemail'] || ($userdata['user_level'] == ADMIN))
		{

			// CrackerTracker v5.x
			if ($userdata['ct_last_mail'] >= time() && $ctracker_config->settings['massmail_protection'] == 1)
			{
				message_die(GENERAL_MESSAGE, sprintf($lang['ctracker_sendmail_info'], $ctracker_config->settings['massmail_time']));
			}
			// CrackerTracker v5.x

			if (time() - $userdata['user_emailtime'] < $board_config['flood_interval'])
			{
				message_die(GENERAL_MESSAGE, $lang['Flood_email_limit']);
			}

			if (isset($_POST['submit']))
			{
				$error = false;

				if (!empty($_POST['subject']))
				{
					$subject = trim(stripslashes($_POST['subject']));
				}
				else
				{
					$error = true;
					$error_msg = (!empty($error_msg)) ? $error_msg . '<br />' . $lang['Empty_subject_email'] : $lang['Empty_subject_email'];
				}

				if (!empty($_POST['message']))
				{
					$message = trim(stripslashes($_POST['message']));
				}
				else
				{
					$error = true;
					$error_msg = (!empty($error_msg)) ? $error_msg . '<br />' . $lang['Empty_message_email'] : $lang['Empty_message_email'];
				}

				if (!$error)
				{
					$mtimetemp = time() + 240;
					$sql = "UPDATE " . USERS_TABLE . "
						SET ct_mailcount = " . $mtimetemp . "
						WHERE user_id = " . $userdata['user_id'];
					$db->sql_query($sql);
					// CrackerTracker v5.x
					$new_mailtime = time() + $ctracker_config->settings['massmail_time'] * 60;
					$sql = 'UPDATE ' . USERS_TABLE . '
						SET user_emailtime = ' . time() . ', ct_last_mail = ' . $new_mailtime . ' WHERE user_id = ' . $userdata['user_id'];
					// CrackerTracker v5.x
					/*
					$sql = "UPDATE " . USERS_TABLE . "
						SET user_emailtime = " . time() . "
						WHERE user_id = " . $userdata['user_id'];
					*/
					if ($result = $db->sql_query($sql))
					{
						include(IP_ROOT_PATH . 'includes/emailer.' . PHP_EXT);
						$emailer = new emailer($board_config['smtp_delivery']);

						$email_headers = 'X-AntiAbuse: Board servername - ' . trim($board_config['server_name']) . "\n";
						$email_headers .= 'X-AntiAbuse: User_id - ' . $userdata['user_id'] . "\n";
						$email_headers .= 'X-AntiAbuse: Username - ' . $userdata['username'] . "\n";
						$email_headers .= 'X-AntiAbuse: User IP - ' . decode_ip($user_ip) . "\n";

						$emailer->use_template('profile_send_email', $user_lang);
						$emailer->email_address($user_email);
						$emailer->from($userdata['user_email']);
						$emailer->replyto($userdata['user_email']);
						$emailer->extra_headers($email_headers);
						$emailer->set_subject($subject);

						$emailer->assign_vars(array(
							'SITENAME' => $board_config['sitename'],
							'BOARD_EMAIL' => $board_config['board_email'],
							'FROM_USERNAME' => $userdata['username'],
							'TO_USERNAME' => $username,
							'MESSAGE' => $message
							)
						);
						$emailer->send();
						$emailer->reset();

						if (!empty($_POST['cc_email']))
						{
							$emailer->use_template('profile_send_email');
							$emailer->email_address($userdata['user_email']);
							$emailer->from($userdata['user_email']);
							$emailer->replyto($userdata['user_email']);
							$emailer->set_subject($subject);

							$emailer->assign_vars(array(
								'SITENAME' => $board_config['sitename'],
								'BOARD_EMAIL' => $board_config['board_email'],
								'FROM_USERNAME' => $userdata['username'],
								'TO_USERNAME' => $username,
								'MESSAGE' => $message
								)
							);
							$emailer->send();
							$emailer->reset();
						}

						$template->assign_vars(array(
							'META' => '<meta http-equiv="refresh" content="5;url=' . append_sid(FORUM_MG) . '">')
						);

						$message = $lang['Email_sent'] . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid(FORUM_MG) . '">', '</a>');

						message_die(GENERAL_MESSAGE, $message);
					}
					else
					{
						message_die(GENERAL_ERROR, 'Could not update last email time', '', __LINE__, __FILE__, $sql);
					}
				}
			}

			include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);

			$template->set_filenames(array('body' => 'profile_send_email.tpl'));
			make_jumpbox(VIEWFORUM_MG);

			if ($error)
			{
				$template->set_filenames(array('reg_header' => 'error_body.tpl'));
				$template->assign_vars(array(
					'ERROR_MESSAGE' => $error_msg
					)
				);
				$template->assign_var_from_handle('ERROR_BOX', 'reg_header');
			}

			$template->assign_vars(array(
				'USERNAME' => $username,

				'S_HIDDEN_FIELDS' => '',
				'S_POST_ACTION' => append_sid(PROFILE_MG . '?mode=email&amp;' . POST_USERS_URL . '=' . $user_id_dest),

				'L_SEND_EMAIL_MSG' => $lang['Send_email_msg'],
				'L_RECIPIENT' => $lang['Recipient'],
				'L_SUBJECT' => $lang['Subject'],
				'L_MESSAGE_BODY' => $lang['Message_body'],
				'L_MESSAGE_BODY_DESC' => $lang['Email_message_desc'],
				'L_EMPTY_SUBJECT_EMAIL' => $lang['Empty_subject_email'],
				'L_EMPTY_MESSAGE_EMAIL' => $lang['Empty_message_email'],
				'L_OPTIONS' => $lang['Options'],
				'L_CC_EMAIL' => $lang['CC_email'],
				'L_SPELLCHECK' => $lang['Spellcheck'],
				'L_SEND_EMAIL' => $lang['Send_email']
				)
			);

			$template->pparse('body');

			include(IP_ROOT_PATH . 'includes/page_tail.' . PHP_EXT);
		}
		else
		{
			message_die(GENERAL_MESSAGE, $lang['User_prevent_email']);
		}
	}
	else
	{
		message_die(GENERAL_MESSAGE, $lang['User_not_exist']);
	}
}
else
{
	message_die(GENERAL_ERROR, 'Could not select user data', '', __LINE__, __FILE__, $sql);
}

?>