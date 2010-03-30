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

if (isset($_POST['submit']))
{
	$username = phpbb_clean_username(request_post_var('username', '', true));
	$username = htmlspecialchars_decode($username, ENT_COMPAT);
	$email = request_post_var('email', '');

	$sql = "SELECT user_id, username, user_email, user_active, user_lang, user_passchg
		FROM " . USERS_TABLE . "
		WHERE user_email = '" . $db->sql_escape($email) . "'
			AND username = '" . $db->sql_escape($username) . "'";
	$result = $db->sql_query($sql);

	if ($row = $db->sql_fetchrow($result))
	{
		if (!$row['user_active'])
		{
			message_die(GENERAL_MESSAGE, $lang['No_send_account_inactive']);
		}

		$username = $row['username'];
		$user_id = $row['user_id'];

		// CrackerTracker v5.x
		if ($config['ctracker_pw_reset_feature'] == 1)
		{
			$pwd_reset_flood_time = $row['user_passchg'] + (!empty($config['ctracker_pwreset_time']) ? (int) $config['ctracker_pwreset_time'] : 20) * 60;
			if (time() <= $pwd_reset_flood_time)
			{
				message_die(GENERAL_MESSAGE, sprintf($lang['ctracker_pwreset_info'], $config['ctracker_pwreset_time']));
			}
		}
		// CrackerTracker v5.x

		$user_actkey = gen_rand_string();
		//$key_len = 54 - strlen($server_url);
		$key_len = 54 - strlen($profile_server_url);
		$key_len = ($key_len > 6) ? $key_len : 6;
		$user_actkey = substr($user_actkey, 0, $key_len);
		$user_password = phpbb_hash(gen_rand_string());
		// CrackerTracker v5.x
		$sql = "UPDATE " . USERS_TABLE . "
			SET user_newpasswd = '" . $db->sql_escape($user_password) . "', user_actkey = '" . $user_actkey . "', user_passchg = '" . time() . "'
			WHERE user_id = " . $row['user_id'];
		// CrackerTracker v5.x
		$db->sql_query($sql);

		include(IP_ROOT_PATH . 'includes/emailer.' . PHP_EXT);
		$emailer = new emailer($config['smtp_delivery']);

		$emailer->from($config['board_email']);
		$emailer->replyto($config['board_email']);

		$emailer->use_template('user_activate_passwd', $row['user_lang']);
		$emailer->email_address($row['user_email']);
		$emailer->set_subject($lang['New_password_activation']);

		$emailer->assign_vars(array(
			'SITENAME' => $config['sitename'],
			'USERNAME' => $username,
			'PASSWORD' => $user_password,
			'EMAIL_SIG' => (!empty($config['board_email_sig'])) ? str_replace('<br />', "\n", $config['sig_line'] . " \n" . $config['board_email_sig']) : '',
			'U_ACTIVATE' => $profile_server_url . '?mode=activate&' . POST_USERS_URL . '=' . $user_id . '&act_key=' . $user_actkey
			)
		);
		$emailer->send();
		$emailer->reset();

		$redirect_url = append_sid(CMS_PAGE_FORUM);
		meta_refresh(15, $redirect_url);

		$message = $lang['Password_updated'] . '<br /><br />' . sprintf($lang['Click_return_index'],  '<a href="' . append_sid(CMS_PAGE_FORUM) . '">', '</a>');

		message_die(GENERAL_MESSAGE, $message);
	}
	elseif (empty($username) || empty($email))
	{
		$error = true;
		$error_msg .= ((isset($error_msg)) ? '<br />' : '') . $lang['Fields_empty'];
		//message_die(GENERAL_MESSAGE, $lang['Fields_empty']);
	}
	else
	{
		$error = true;
		$error_msg .= ((isset($error_msg)) ? '<br />' : '') . $lang['No_email_match'];
		//message_die(GENERAL_MESSAGE, $lang['No_email_match']);
	}
}
else
{
	$username = '';
	$email = '';
}

	if ($error)
	{
		$template->set_filenames(array('reg_header' => 'error_body.tpl'));
		$template->assign_vars(array(
			'ERROR_MESSAGE' => $error_msg
			)
		);
		$template->assign_var_from_handle('ERROR_BOX', 'reg_header');
	}

// Output basic page
$link_name = $lang['Send_password'];
$nav_server_url = create_server_url();
$breadcrumbs_address = $lang['Nav_Separator'] . '<a href="' . $nav_server_url . append_sid(CMS_PAGE_PROFILE_MAIN) . '"' . (!empty($link_name) ? '' : ' class="nav-current"') . '>' . $lang['Profile'] . '</a>' . (!empty($link_name) ? ($lang['Nav_Separator'] . '<a class="nav-current" href="#">' . $link_name . '</a>') : '');

make_jumpbox(CMS_PAGE_VIEWFORUM);

$template->assign_vars(array(
	'USERNAME' => $username,
	'EMAIL' => $email,

	'L_SEND_PASSWORD' => $lang['Send_password'],
	'L_ITEMS_REQUIRED' => $lang['Items_required'],
	'L_EMAIL_ADDRESS' => $lang['Email_address'],
	'L_SUBMIT' => $lang['Submit'],
	'L_RESET' => $lang['Reset'],

	'S_HIDDEN_FIELDS' => '',
	'S_PROFILE_ACTION' => append_sid(CMS_PAGE_PROFILE . '?mode=sendpassword')
	)
);

full_page_generation('profile_send_pass.tpl', $lang['Send_password'], '', '');

?>