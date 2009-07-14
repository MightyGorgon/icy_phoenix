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
* ycl6 (damian at phpbb dot cc)
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
	exit;
}

if (intval($board_config['require_activation']) == USER_ACTIVATION_ADMIN)
{
	message_die(GENERAL_ERROR, 'Invalid_activation');
}

if (isset($_POST['submit']))
{
	$username = isset($_POST['username']) ? phpbb_clean_username($_POST['username']) : '';
	$email = trim(htmlspecialchars($_POST['email']));

	$sql = "SELECT user_id, user_email, user_active, user_actkey, user_lang, user_last_login_try
		FROM " . USERS_TABLE . "
		WHERE username = '" . str_replace("\\'", "''", $username) . "'";

	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Error in obtaining userdata', '', __LINE__, __FILE__, $sql);
	}

	if (!($row = $db->sql_fetchrow($result)))
	{
		// No such name
		message_die(GENERAL_ERROR, 'User_not_exist');
	}

	if ($row['user_email'] != $email)
	{
		// Wrong Email provided
		message_die(GENERAL_ERROR, 'No_email_match');
	}

	if (!empty($row['user_active']))
	{
		// Already activated
		message_die(GENERAL_ERROR, 'Already_activated');
	}

	if (empty($row['user_actkey']))
	{
		// No activation key
		message_die(GENERAL_ERROR, 'No_actkey');
	}

	$current_time = time();

	if ((intval($row['user_last_login_try']) > 0) && (($current_time - intval($row['user_last_login_try'])) < $board_config['login_reset_time']))
	{
		// Request flood
		message_die(GENERAL_ERROR, 'Send_actmail_flood_error');
	}

	// Start the email process
	$unhtml_specialchars_match = array('#&gt;#', '#&lt;#', '#&quot;#', '#&amp;#');
	$unhtml_specialchars_replace = array('>', '<', '"', '&');

	include_once(IP_ROOT_PATH . 'includes/emailer.' . PHP_EXT);

	$emailer = new emailer($board_config['smtp_delivery']);
	$emailer->from($board_config['board_email']);
	$emailer->replyto($board_config['board_email']);

	$emailer->email_address(trim($row['user_email']));
	$emailer->use_template('user_welcome_inactive', $row['user_lang']);
	$emailer->set_subject($lang['Resend_activation_email']);

	$emailer->assign_vars(array(
		'SITENAME' => $board_config['sitename'],
		'USERNAME' => preg_replace($unhtml_specialchars_match, $unhtml_specialchars_replace, substr(str_replace("\'", "'", $username), 0, 25)),
		'PASSWORD' => '',
		'WELCOME_MSG' => sprintf($lang['Welcome_subject'], $board_config['sitename']),
		'EMAIL_SIG' => str_replace('<br />', "\n", "-- \n" . $board_config['board_email_sig']),
		'U_ACTIVATE' => $profile_server_url . '?mode=activate&' . POST_USERS_URL . '=' . $row['user_id'] . '&act_key=' . $row['user_actkey']
		)
	);
	$emailer->send();
	$emailer->reset();

	// Update last activation sent time
	$sql = "UPDATE " . USERS_TABLE . "
		SET user_last_login_try = $current_time
		WHERE username = '" . str_replace("\\'", "''", $username) . "'";

	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not update userdata', '', __LINE__, __FILE__, $sql);
	}

	message_die(GENERAL_MESSAGE, 'Resend_activation_email_done');
}
else
{
	$page_title = $lang['Resend_activation_email'];
	$meta_description = '';
	$meta_keywords = '';
	$link_name = $lang['Resend_activation_email'];
	$nav_server_url = create_server_url();
	$breadcrumbs_address = $lang['Nav_Separator'] . '<a href="' . $nav_server_url . append_sid('profile_main.' . PHP_EXT) . '"' . (!empty($link_name) ? '' : ' class="nav-current"') . '>' . $lang['Profile'] . '</a>' . (!empty($link_name) ? ($lang['Nav_Separator'] . '<a class="nav-current" href="#">' . $link_name . '</a>') : '');
	include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);

	$template->set_filenames(array('body' => 'profile_send_pass.tpl'));

	$template->assign_vars(array(
		'L_SEND_PASSWORD' => $lang['Resend_activation_email'],
		'L_ITEMS_REQUIRED' => $lang['Items_required'],
		'L_SUBMIT' => $lang['Submit'],
		'L_RESET' => $lang['Reset'],
		'S_PROFILE_ACTION' => append_sid(PROFILE_MG . '?mode=resend'),
		'S_HIDDEN_FIELDS' => ''
		)
	);

	$template->pparse('body');
	include(IP_ROOT_PATH . 'includes/page_tail.' . PHP_EXT);
}

?>