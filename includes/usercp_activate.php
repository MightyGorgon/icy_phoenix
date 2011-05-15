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

$user_id = request_var(POST_USERS_URL, 0);

$sql = "SELECT user_active, user_id, username, user_email, user_newpasswd, user_lang, user_actkey
	FROM " . USERS_TABLE . "
	WHERE user_id = '" . $db->sql_escape($user_id) . "'";
$result = $db->sql_query($sql);

if ($row = $db->sql_fetchrow($result))
{
	if ($row['user_active'] && (trim($row['user_actkey']) == ''))
	{
		$redirect_url = append_sid(CMS_PAGE_FORUM);
		meta_refresh(10, $redirect_url);

		message_die(GENERAL_MESSAGE, $lang['Already_activated']);
	}
	elseif ((trim($row['user_actkey']) == trim($_GET['act_key'])) && (trim($row['user_actkey']) != ''))
	{
		if ((intval($config['require_activation']) == USER_ACTIVATION_ADMIN) && ($row['user_newpasswd'] == ''))
		{
			if (!$user->data['session_logged_in'])
			{
				redirect(append_sid(CMS_PAGE_LOGIN . '?redirect=' . CMS_PAGE_PROFILE . '&mode=activate&' . POST_USERS_URL . '=' . $row['user_id'] . '&act_key=' . trim($_GET['act_key'])));
			}
			elseif ($user->data['user_level'] != ADMIN)
			{
				message_die(GENERAL_MESSAGE, $lang['Not_Authorized']);
			}
		}

		$sql_update_pass = ($row['user_newpasswd'] != '') ? ", user_password = '" . $db->sql_escape($row['user_newpasswd']) . "', user_newpasswd = ''" : '';

		$sql = "UPDATE " . USERS_TABLE . "
			SET user_active = 1, user_actkey = ''" . $sql_update_pass . "
			WHERE user_id = " . $row['user_id'];
		$result = $db->sql_query($sql);

		if ((intval($config['require_activation']) == USER_ACTIVATION_ADMIN) && ($sql_update_pass == ''))
		{
			include(IP_ROOT_PATH . 'includes/emailer.' . PHP_EXT);
			$emailer = new emailer();

			$emailer->use_template('admin_welcome_activated', $row['user_lang']);
			$emailer->to($row['user_email']);
			$emailer->set_subject($lang['Account_activated_subject']);

			$email_sig = create_signature($config['board_email_sig']);
			$emailer->assign_vars(array(
				'SITENAME' => $config['sitename'],
				'USERNAME' => $row['username'],
				'PASSWORD' => $password_confirm,
				'EMAIL_SIG' => $email_sig
				)
			);
			$emailer->send();
			$emailer->reset();

			$redirect_url = append_sid(CMS_PAGE_FORUM);
			meta_refresh(10, $redirect_url);

			message_die(GENERAL_MESSAGE, $lang['Account_active_admin']);
		}
		else
		{
			$redirect_url = append_sid(CMS_PAGE_FORUM);
			meta_refresh(10, $redirect_url);

			// Refresh last user id if needed...
			board_stats();

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
	if (!defined('STATUS_404')) define('STATUS_404', true);
	message_die(GENERAL_MESSAGE, 'NO_USER');
}

?>