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

$sql = "SELECT user_active, user_id, username, user_email, user_newpasswd, user_lang, user_actkey
	FROM " . USERS_TABLE . "
	WHERE user_id = " . intval($_GET[POST_USERS_URL]);
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not obtain user information', '', __LINE__, __FILE__, $sql);
}

if ($row = $db->sql_fetchrow($result))
{
	if ($row['user_active'] && trim($row['user_actkey']) == '')
	{
		$redirect_url = append_sid(FORUM_MG);
		meta_refresh(10, $redirect_url);

		message_die(GENERAL_MESSAGE, $lang['Already_activated']);
	}
	elseif ((trim($row['user_actkey']) == trim($_GET['act_key'])) && (trim($row['user_actkey']) != ''))
	{
		if (intval($board_config['require_activation']) == USER_ACTIVATION_ADMIN && $row['user_newpasswd'] == '')
		{
			if (!$userdata['session_logged_in'])
			{
				redirect(append_sid(LOGIN_MG . '?redirect=' . PROFILE_MG . '&mode=activate&' . POST_USERS_URL . '=' . $row['user_id'] . '&act_key=' . trim($_GET['act_key'])));
			}
			elseif ($userdata['user_level'] != ADMIN)
			{
				message_die(GENERAL_MESSAGE, $lang['Not_Authorized']);
			}
		}

		$sql_update_pass = ($row['user_newpasswd'] != '') ? ", user_password = '" . str_replace("\'", "''", $row['user_newpasswd']) . "', user_newpasswd = ''" : '';

		$sql = "UPDATE " . USERS_TABLE . "
			SET user_active = 1, user_actkey = ''" . $sql_update_pass . "
			WHERE user_id = " . $row['user_id'];
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not update users table', '', __LINE__, __FILE__, $sql_update);
		}

		if (intval($board_config['require_activation']) == USER_ACTIVATION_ADMIN && $sql_update_pass == '')
		{
			include(IP_ROOT_PATH . 'includes/emailer.' . PHP_EXT);
			$emailer = new emailer($board_config['smtp_delivery']);

			$emailer->from($board_config['board_email']);
			$emailer->replyto($board_config['board_email']);

			$emailer->use_template('admin_welcome_activated', $row['user_lang']);
			$emailer->email_address($row['user_email']);
			$emailer->set_subject($lang['Account_activated_subject']);

			$emailer->assign_vars(array(
				'SITENAME' => $board_config['sitename'],
				'USERNAME' => $row['username'],
				'PASSWORD' => $password_confirm,
				'EMAIL_SIG' => (!empty($board_config['board_email_sig']) ? str_replace('<br />', "\n", "-- \n" . $board_config['board_email_sig']) : '')
				)
			);
			$emailer->send();
			$emailer->reset();

			$redirect_url = append_sid(FORUM_MG);
			meta_refresh(10, $redirect_url);

			message_die(GENERAL_MESSAGE, $lang['Account_active_admin']);
		}
		else
		{
			$redirect_url = append_sid(FORUM_MG);
			meta_refresh(10, $redirect_url);

			$message = ($sql_update_pass == '') ? $lang['Account_active'] : $lang['Password_activated'];
			message_die(GENERAL_MESSAGE, $message);
		}
	}
	else
	{
		message_die(GENERAL_MESSAGE, $lang['Wrong_activation']);
	}
}
else
{
	message_die(GENERAL_MESSAGE, $lang['No_such_user']);
}

?>