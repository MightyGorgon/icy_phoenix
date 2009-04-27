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
* R. U. Serious
*
*/

define('IN_ICYPHOENIX', true);

if(!empty($setmodules))
{
	$filename = basename(__FILE__);
	$module['1100_General']['140_Mega_Mail'] = $filename;
	return;
}

if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
$no_page_header = true;
require('./pagestart.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);

// SETTINGS - BEGIN
$def_wait = 10;
$def_size = 100;
define('MEGAMAIL_TABLE', $table_prefix . 'megamail');
define('DAYS_INACTIVE', 180);
// Increase maximum execution time in case of a lot of users, but don't complain about it if it isn't allowed.
@set_time_limit(1200);
// SETTINGS - END

$cancel = isset($_POST['cancel']);
if ($cancel)
{
	redirect(ADM . '/' . append_sid('admin_megamail.' . PHP_EXT, true));
}

$modes_array = array('list', 'send', 'delete');
$mode = request_var('mode', $modes_array[0]);
$mode = in_array($mode, $modes_array) ? $mode : $mode_array[0];

$mail_id = request_var('mail_id', 0);

// Delete if needed...
if (($mode == 'delete') && ($mail_id > 0))
{
	$confirm = isset($_POST['confirm']);

	if($confirm)
	{
		$sql = "DELETE FROM " . MEGAMAIL_TABLE . "
			WHERE mail_id = " . $mail_id;
		$result = $db->sql_query($sql);
		if(!$result)
		{
			message_die(GENERAL_ERROR, "Couldn't delete email", "", __LINE__, __FILE__, $sql);
		}

		$message = $lang['megamail_deleted'] . '<br /><br />' . sprintf($lang['megamail_click_return'], '<a href="' . append_sid('admin_megamail.' . PHP_EXT) . '">', '</a>');
		message_die(GENERAL_MESSAGE, $message);
	}
	else
	{
		include('./page_header_admin.' . PHP_EXT);
		$template->set_filenames(array('body' => ADM_TPL . 'confirm_body.tpl'));
		$hidden_fields = '<input type="hidden" name="mode" value="delete" /><input type="hidden" name="mail_id" value="' . $mail_id . '" />';

		$template->assign_vars(array(
			'MESSAGE_TITLE' => $lang['Confirm'],
			'MESSAGE_TEXT' => $lang['megamail_delete_confirm'],

			'L_YES' => $lang['Yes'],
			'L_NO' => $lang['No'],

			'S_CONFIRM_ACTION' => append_sid('admin_megamail.' . PHP_EXT),
			'S_HIDDEN_FIELDS' => $hidden_fields
			)
		);
		$template->pparse('body');
		include('./page_footer_admin.' . PHP_EXT);
		exit;
	}
}

$message = '';
$subject = '';

// Do the job ...
if (isset($_POST['message']) || isset($_POST['subject']))
{
	$batchsize = (is_numeric($_POST['batchsize'])) ? intval($_POST['batchsize']) : $def_size;
	$batchwait = (is_numeric($_POST['batchwait'])) ? intval($_POST['batchwait']) : $def_wait;
	$mass_pm = request_var('mass_pm', 0);
	$email_format = request_var('email_format', 0);
	$subject = request_var('subject', '');
	$subject = htmlspecialchars_decode($subject, ENT_QUOTES);
	//$message = request_var('message', '');
	//$message = htmlspecialchars_decode($message, ENT_QUOTES);
	$message = $_POST['message'];

	$mail_session_id = md5(uniqid(''));
	$sql = "INSERT INTO " . MEGAMAIL_TABLE . " (mailsession_id, mass_pm, user_id, group_id, email_subject, email_body, email_format, batch_start, batch_size, batch_wait, status)
			VALUES ('" . $mail_session_id . "', " . $mass_pm . ", " . $userdata['user_id'] . ", " . intval($_POST[POST_GROUPS_URL]) . ", '" . ip_addslashes($subject) . "', '" . ip_addslashes($message) . "', " . $email_format . ", 0, " . $batchsize . "," . $batchwait . ", 0)";

	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not insert the data into '. MEGAMAIL_TABLE, '', __LINE__, __FILE__, $sql);
	}
	$mail_id = $db->sql_nextid();
	$url = append_sid('admin_megamail.' . PHP_EXT . '?mail_id=' . $mail_id . '&amp;mail_session_id=' . $mail_session_id);

	$redirect_url = ADM . '/' . $url;
	meta_refresh($batchwait, $redirect_url);

	$message = sprintf($lang['megamail_created_message'], '<a href="' . $url . '">', '</a>');
	message_die(GENERAL_MESSAGE, $message);
}

if (isset($_GET['mail_id']) && isset($_GET['mail_session_id']))
{
	@ignore_user_abort(true);
	$mail_id = intval($_GET['mail_id']);
	$mail_session_id = stripslashes(trim($_GET['mail_session_id']));
	// Let's see if that session exists
	$sql = "SELECT *
			FROM " . MEGAMAIL_TABLE . "
			WHERE mail_id = '" . $mail_id . "'
				AND mailsession_id LIKE '" . $mail_session_id . "'";
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not query '. MEGAMAIL_TABLE , '', __LINE__, __FILE__, $sql);
	}
	$mail_data = $db->sql_fetchrow($result);

	if (!($mail_data))
	{
		message_die(GENERAL_MESSAGE, 'Mail ID and Mail Session ID do not match.', '', __LINE__, __FILE__, $sql);
	}
	//Ok, the session exists

	$subject = ip_stripslashes($mail_data['email_subject']);
	$message = ip_stripslashes($mail_data['email_body']);
	// Store the clean version of the message for PM
	$pm_message = $message;
	$group_id = $mail_data['group_id'];
	$mass_pm = $mail_data['mass_pm'];
	$email_format = $mail_data['email_format'];

	if ($email_format == 1)
	{
		$board_config['html_email'] = 1;
		$bbcode->allow_html = false;
		$bbcode->allow_bbcode = true;
		$bbcode->allow_smilies = true;
		$message = $bbcode->parse($message);
	}
	elseif ($email_format == 2)
	{
		// We are in FULL HTML here
		$board_config['html_email'] = 1;
	}

	//OLD HTML FORMAT
	/*
	if ($board_config['html_email'] == false)
	{
		$message = $bbcode->bbcode_killer($message, '');
		$message = strip_tags($mail_data['email_body'], '');
	}
	else
	{
		$bbcode->allow_html = true;
		$bbcode->allow_bbcode = ($board_config['allow_bbcode'] ? $board_config['allow_bbcode'] : false);
		$bbcode->allow_smilies = ($board_config['allow_smilies'] ? $board_config['allow_smilies'] : false);
		$message = $bbcode->parse($message);
	}
	*/

	$sql_non_recent_login = '';
	$process_groups = (($group_id == -1) || ($group_id == -2)) ? false : true;
	if ($group_id == -2)
	{
		$sql_non_recent_login = "AND u.user_lastlogon < '" . (time() - (86400 * DAYS_INACTIVE)) . "'";
	}

	//Now, let's see if we reached the upperlimit, if yes adjust the batch_size
	if ($process_groups)
	{
		$sql = "SELECT COUNT(u.user_email)
						FROM " . USERS_TABLE . " u, " . USER_GROUP_TABLE . " ug
						WHERE ug.group_id = '" . $group_id . "'
							AND ug.user_pending <> " . TRUE . "
							AND u.user_id = ug.user_id
							AND u.user_active = 1
							AND u.user_allow_mass_email = 1";
	}
	else
	{
		$sql = "SELECT COUNT(u.user_email)
						FROM " . USERS_TABLE . " u
						WHERE u.user_active = 1
							AND u.user_allow_mass_email = 1
							" . $sql_non_recent_login;
	}

	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not select group members', '', __LINE__, __FILE__, $sql);
	}
	$totalrecipients = $db->sql_fetchrow($result);
	$totalrecipients = $totalrecipients['COUNT(u.user_email)'];

	$is_done = '';
	/*
	// Forcing email max to $force_limit users
	$force_limit = 10000;
	$force_start = 10000;
	$totalrecipients = $force_limit;
	$mail_data['batch_start'] = ($mail_data['batch_start'] < $force_start) ? $force_start : $mail_data['batch_start'];
	*/
	if (($mail_data['batch_start'] + $mail_data['batch_size']) > $totalrecipients)
	{
		$mail_data['batch_size'] = $totalrecipients - $mail_data['batch_start'];
		$is_done = ', status = 1';
	}

	// Create new mail session
	$mail_session_id = md5(uniqid(''));
	$sql = "UPDATE " . MEGAMAIL_TABLE . "
			SET mailsession_id = '" . $mail_session_id . "', batch_start= " . ($mail_data['batch_start'] + $mail_data['batch_size']) . $is_done . "
			WHERE mail_id = '" . $mail_id . "'";

	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not insert the data into '. MEGAMAIL_TABLE, '', __LINE__, __FILE__, $sql);
	}

	// OK, now let's start sending
	$error = false;
	$error_msg = '';

	if ($process_groups)
	{
		$sql = "SELECT u.user_id, u.user_email
						FROM " . USERS_TABLE . " u, " . USER_GROUP_TABLE . " ug
						WHERE ug.group_id = '" . $group_id . "'
							AND ug.user_pending <> " . TRUE . "
							AND u.user_id = ug.user_id
							AND u.user_active = 1
							AND u.user_allow_mass_email = 1";
	}
	else
	{
		$sql = "SELECT user_id, user_email
						FROM " . USERS_TABLE . " u
						WHERE u.user_active = 1
							AND u.user_allow_mass_email = 1
							" . $sql_non_recent_login;
	}

	$sql .= " LIMIT " . $mail_data['batch_start'] . ", " . $mail_data['batch_size'];

	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not select group members', '', __LINE__, __FILE__, $sql);
	}

	if ($row = $db->sql_fetchrow($result))
	{
		if ($mass_pm)
		{
			include_once(IP_ROOT_PATH . 'includes/functions_privmsgs.' . PHP_EXT);
			$privmsg = new privmsgs();
		}
		$bcc_list = '';
		do
		{
			if ($mass_pm)
			{
				$privmsg->send($userdata['user_id'], $row['user_id'], $subject, $pm_message);
			}
			$bcc_list .= (($bcc_list != '') ? ', ' : '') . $row['user_email'];
		}
		while ($row = $db->sql_fetchrow($result));
		$db->sql_freeresult($result);
		if ($mass_pm)
		{
			unset($privmsg);
		}
	}
	else
	{
		$message = ($process_groups ? $lang['Group_not_exist'] : $lang['No_such_user']);
		$error = true;
		$error_msg .= (!empty($error_msg)) ? '<br />' . $message : $message;
	}

	if (!$error)
	{
		include(IP_ROOT_PATH . 'includes/emailer.' . PHP_EXT);
		// Let's do some checking to make sure that mass mail functions are working in win32 versions of php.
		if (preg_match('/[c-z]:\\\.*/i', getenv('PATH')) && !$board_config['smtp_delivery'])
		{
			$ini_val = (@phpversion() >= '4.0.0') ? 'ini_get' : 'get_cfg_var';

			// We are running on windows, force delivery to use our smtp functions
			// since php's are broken by default
			$board_config['smtp_delivery'] = 1;
			$board_config['smtp_host'] = @$ini_val('SMTP');
		}

		$emailer = new emailer($board_config['smtp_delivery']);

		$email_headers = 'X-AntiAbuse: Board servername - ' . trim($board_config['server_name']) . "\n";
		$email_headers .= 'X-AntiAbuse: User_id - ' . $userdata['user_id'] . "\n";
		$email_headers .= 'X-AntiAbuse: Username - ' . $userdata['username'] . "\n";
		$email_headers .= 'X-AntiAbuse: User IP - ' . decode_ip($user_ip) . "\n";

		if ($email_format == 2)
		{
			$emailer->use_template('empty_email', $board_config['default_lang'], true);
		}
		else
		{
			$emailer->use_template('admin_send_email', $board_config['default_lang']);
		}
		$emailer->bcc($bcc_list);
		$emailer->email_address($board_config['board_email']);
		$emailer->from($board_config['board_email']);
		$emailer->replyto($board_config['board_email']);
		$emailer->extra_headers($email_headers);
		$emailer->set_subject($subject);

		// Do we want to force line breaks? It is HTML, so we should not replace line breaks...
		//$message = preg_replace(array("/<br \/>\r\n/", "/<br>\r\n/", "/(\r\n|\n|\r)/"), array("\r\n", "\r\n", "<br />\r\n"), $message);

		if ($mass_pm)
		{
			$server_url = create_server_url();
			$pm_inbox_link = $server_url . 'privmsg.' . PHP_EXT . '?folder=inbox';
			$pm_inbox_link = (!$board_config['html_email']) ? $pm_inbox_link : ('<a href="' . $pm_inbox_link . '">' . $pm_inbox_link . '</a>');
			$message = str_replace(array('{SITENAME}', '{U_INBOX}'), array($board_config['sitename'], $pm_inbox_link), $lang['PM_NOTIFICATION']);
			$message = (!$board_config['html_email']) ? str_replace('<br />', "\r\n", $message) : $message;
		}

		$emailer->assign_vars(array(
			'SITENAME' => $board_config['sitename'],
			'BOARD_EMAIL' => $board_config['board_email'],
			'MESSAGE' => $message
			)
		);

		$emailer->send();
		$emailer->reset();

		if ($is_done == '')
		{
			$url= append_sid('admin_megamail.' . PHP_EXT . '?mail_id=' . $mail_id . '&amp;mail_session_id=' . $mail_session_id);

			$redirect_url = ADM . '/' . $url;
			meta_refresh($mail_data['batch_wait'], $redirect_url);

			$message = sprintf($lang['megamail_send_message'] ,$mail_data['batch_start'], ($mail_data['batch_start']+$mail_data['batch_size']), '<a href="' . $url . '">', '</a>');
		}
		else
		{
			$url= append_sid('admin_megamail.' . PHP_EXT);

			$redirect_url = ADM . '/' . $url;
			meta_refresh($mail_data['batch_wait'], $redirect_url);

			$message =  $lang['megamail_done'] . '<br />' . sprintf($lang['megamail_proceed'], '<a href="' . $url . '">', '</a>');
		}
		message_die(GENERAL_MESSAGE, $message);

//		message_die(GENERAL_MESSAGE, $lang['Email_sent'] . '<br /><br />' . sprintf($lang['Click_return_admin_index'],  '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>'));
	}
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

// Initial selection
$sql = "SELECT m.*, u.username, u.user_active, u.user_color, g.group_name
	FROM " . MEGAMAIL_TABLE . " m
	LEFT JOIN " . USERS_TABLE . " u ON (m.user_id = u.user_id)
	LEFT JOIN " . GROUPS_TABLE . " g ON (m.group_id = g.group_id)
	ORDER BY m.mail_id ASC";
if (!($result = $db->sql_query($sql)))
{
	message_die(GENERAL_MESSAGE, 'Could not query megamail table!', '', __LINE__, __FILE__, $sql);
}
$row_class = 0;
if ($mail_data = $db->sql_fetchrow($result))
{
	do
	{
		$url = append_sid('admin_megamail.' . PHP_EXT . '?mail_id=' . $mail_data['mail_id'] . '&amp;mail_session_id=' . $mail_data['mailsession_id']);

		$look_up_array = array(
			"\"",
			"<",
			">",
			"\n",
			chr(13),
		);

		$replacement_array = array(
			"\\\"",
			"&lt_mg;",
			"&gt_mg;",
			"\\n",
			"",
		);

		$plain_message = (STRIP ? stripslashes($mail_data['email_body']) : $mail_data['email_body']);
		$plain_message = strtr($plain_message, array_flip(get_html_translation_table(HTML_ENTITIES)));
		$plain_message = str_replace($look_up_array, $replacement_array, $plain_message);
		$delete_url = append_sid('admin_megamail.' . PHP_EXT . '?mail_id=' . $mail_data['mail_id'] . '&amp;mode=delete');

		$template->assign_block_vars('mail_sessions',array(
			'ROW' => ($row_class % 2) ? 'row2' : 'row1',
			'ID' => $mail_data['mail_id'],
			'GROUP' => ($mail_data['group_id'] != -1) ? $mail_data['group_name'] : $lang['All_users'],
			'SUBJECT' => (STRIP ? stripslashes($mail_data['email_subject']) : $mail_data['email_subject']),
			'MASS_PM' => $mail_data['mass_pm'] ? $lang['Yes'] : $lang['No'],
			'EMAIL_FORMAT' => (($mail_data['email_format'] == 2) ? $lang['FULL_HTML'] : (($mail_data['email_format'] == 1) ? $lang['BBCode'] : $lang['HTML'])),
			'MESSAGE_BODY' => $plain_message,
			'BATCHSTART' => $mail_data['batch_start'],
			'BATCHSIZE' => $mail_data['batch_size'],
			'BATCHWAIT' => $mail_data['batch_wait'] . ' s.',
			'SENDER' => colorize_username($mail_data['user_id'], $mail_data['username'], $mail_data['user_color'], $mail_data['user_active']),
			'STATUS' => ($mail_data['status'] == 0) ? sprintf($lang['megamail_proceed'], '<a href="' . $url . '">', '</a>') : 'Done',
			'U_DELETE' => $delete_url,
			)
		);
		$row_class++;
	}
	while($mail_data = $db->sql_fetchrow($result));
}
else
{
	$template->assign_block_vars('switch_no_sessions',array(
		'EMPTY' => $lang['megamail_none'],
		)
	);
}

$sql = "SELECT group_id, group_name
	FROM " . GROUPS_TABLE . "
	WHERE group_single_user <> 1";
if (!($result = $db->sql_query($sql)))
{
	message_die(GENERAL_ERROR, 'Could not obtain list of groups', '', __LINE__, __FILE__, $sql);
}

$select_list = '';
$select_list .= '<select name = "' . POST_GROUPS_URL . '">';
$select_list .= '<option value = "-1">' . $lang['All_users'] . '</option>';
$select_list .= '<option value = "-2">' . str_replace('{DAYS}', DAYS_INACTIVE, $lang['megamail_inactive_users']) . '</option>';
if ($row = $db->sql_fetchrow($result))
{
	do
	{
		$select_list .= '<option value = "' . $row['group_id'] . '">' . $row['group_name'] . '</option>';
	}
	while ($row = $db->sql_fetchrow($result));
}
$select_list .= '</select>';

// Generate page
include('./page_header_admin.' . PHP_EXT);

$template->set_filenames(array('body' => ADM_TPL . 'megamail.tpl'));

$template->assign_vars(array(
	'MESSAGE' => $message,
	'SUBJECT' => $subject,

	'L_EMAIL_TITLE' => $lang['140_Mega_Mail'],
	'L_EMAIL_EXPLAIN' => $lang['Megamail_Explain'],
	'L_COMPOSE' => $lang['Compose'],
	'L_RECIPIENTS' => $lang['Recipients'],
	'L_EMAIL_SUBJECT' => $lang['Subject'],
	'L_EMAIL_MSG' => $lang['Message'],
	'L_EMAIL' => $lang['Email'],
	'L_SEND' => $lang['Send'],
	'L_NOTICE' => $notice,

	'S_USER_ACTION' => append_sid('admin_megamail.' . PHP_EXT),
	'S_GROUP_SELECT' => $select_list,

	'L_MAIL_SESSION_HEADER' => $lang['megamail_header'],
	'L_ID' => 'ID',
	'L_GROUP' => $lang['group_name'],
	'L_BATCH_START' => $lang['megamail_batchstart'],
	'L_BATCH_SIZE'  => $lang['megamail_batchsize'],
	'L_BATCH_WAIT'  => $lang['megamail_batchwait'],
	//'L_SENDER' => $lang['Auth_Admin'],
	'L_BBCODE' => $lang['BBCode'],
	'L_STATUS' => $lang['megamail_status'],
	'DEFAULT_SIZE' => $def_size,
	'DEFAULT_WAIT' => $def_wait,
	)
);

$template->pparse('body');

include('./page_footer_admin.' . PHP_EXT);

?>