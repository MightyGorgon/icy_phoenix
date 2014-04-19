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

include_once(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_flood.' . PHP_EXT);

// Is send through board enabled? No, return to index
if (!$config['board_email_form'])
{
	redirect(append_sid(CMS_PAGE_FORUM, true));
}

if (!empty($_GET[POST_USERS_URL]) || !empty($_POST[POST_USERS_URL]))
{
	$user_id_dest = (!empty($_GET[POST_USERS_URL])) ? intval($_GET[POST_USERS_URL]) : intval($_POST[POST_USERS_URL]);
}
else
{
	message_die(GENERAL_MESSAGE, $lang['No_user_specified']);
}

if (!$user->data['session_logged_in'])
{
	redirect(append_sid(CMS_PAGE_LOGIN . '?redirect=' . CMS_PAGE_PROFILE . '&mode=email&' . POST_USERS_URL . '=' . $user_id_dest, true));
}

$sql = "SELECT username, user_email, user_allow_viewemail, user_lang, user_level
	FROM " . USERS_TABLE . "
	WHERE user_id = $user_id_dest";
$result = $db->sql_query($sql);

if ($row = $db->sql_fetchrow($result))
{

	$username = $row['username'];
	$user_email = $row['user_email'];
	$user_lang = $row['user_lang'];
	$user_level = $row['user_level'];

	if (!empty($config['emails_only_to_admins']))
	{
		$email_allowed = (($user_level == JUNIOR_ADMIN) || ($user_level == ADMIN)) ? true : false;
		if (!$email_allowed)
		{
			message_die(GENERAL_MESSAGE, $lang['Emails_Only_To_Admins_Error']);
		}
	}

	if ($row['user_allow_viewemail'] || ($user->data['user_level'] == ADMIN))
	{
		check_flood_email(false);

		if (isset($_POST['submit']))
		{
			$error = false;

			$subject = request_var('subject', '', true);
			$subject = htmlspecialchars_decode($subject, ENT_COMPAT);
			$message = request_var('message', '', true);
			// We need to check if HTML emails are enabled so we can correctly escape content and linebreaks
			$message = !empty($config['html_email']) ? nl2br($message) : htmlspecialchars_decode($message, ENT_COMPAT);

			if (empty($subject))
			{
				$error = true;
				$error_msg = (!empty($error_msg)) ? $error_msg . '<br />' . $lang['Empty_subject_email'] : $lang['Empty_subject_email'];
			}

			if (empty($message))
			{
				$error = true;
				$error_msg = (!empty($error_msg)) ? $error_msg . '<br />' . $lang['Empty_message_email'] : $lang['Empty_message_email'];
			}

			if (!$error)
			{
				update_flood_time_email();

				include(IP_ROOT_PATH . 'includes/emailer.' . PHP_EXT);
				$emailer = new emailer();

				$emailer->headers('X-AntiAbuse: Board servername - ' . trim($config['server_name']));
				$emailer->headers('X-AntiAbuse: User_id - ' . $user->data['user_id']);
				$emailer->headers('X-AntiAbuse: Username - ' . $user->data['username']);
				$emailer->headers('X-AntiAbuse: User IP - ' . $user_ip);

				$emailer->use_template('profile_send_email', $user_lang);
				$emailer->to($user_email);
				$emailer->from($user->data['user_email']);
				$emailer->replyto($user->data['user_email']);
				$emailer->set_subject($subject);

				$emailer->assign_vars(array(
					'SITENAME' => $config['sitename'],
					'BOARD_EMAIL' => $config['board_email'],
					'FROM_USERNAME' => $user->data['username'],
					'TO_USERNAME' => $username,
					'MESSAGE' => $message
					)
				);
				$emailer->send();
				$emailer->reset();

				if (!empty($_POST['cc_email']))
				{
					$emailer->use_template('profile_send_email');
					$emailer->email_address($user->data['user_email']);
					$emailer->from($user->data['user_email']);
					$emailer->replyto($user->data['user_email']);
					$emailer->set_subject($subject);

					$emailer->assign_vars(array(
						'SITENAME' => $config['sitename'],
						'BOARD_EMAIL' => $config['board_email'],
						'FROM_USERNAME' => $user->data['username'],
						'TO_USERNAME' => $username,
						'MESSAGE' => $message
						)
					);
					$emailer->send();
					$emailer->reset();
				}

				$redirect_url = append_sid(CMS_PAGE_FORUM);
				meta_refresh(5, $redirect_url);

				$message = $lang['Email_sent'] . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid(CMS_PAGE_FORUM) . '">', '</a>');

				message_die(GENERAL_MESSAGE, $message);
			}
		}

		$link_name = $lang['Send_email_msg'];
		$nav_server_url = create_server_url();
		$breadcrumbs['address'] = $lang['Nav_Separator'] . '<a href="' . $nav_server_url . append_sid(CMS_PAGE_PROFILE_MAIN) . '"' . (!empty($link_name) ? '' : ' class="nav-current"') . '>' . $lang['Profile'] . '</a>' . (!empty($link_name) ? ($lang['Nav_Separator'] . '<a class="nav-current" href="#">' . $link_name . '</a>') : '');

		make_jumpbox(CMS_PAGE_VIEWFORUM);

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
			'S_POST_ACTION' => append_sid(CMS_PAGE_PROFILE . '?mode=email&amp;' . POST_USERS_URL . '=' . $user_id_dest),

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
		full_page_generation('profile_send_email.tpl', $lang['Send_email_msg'], '', '');
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

?>