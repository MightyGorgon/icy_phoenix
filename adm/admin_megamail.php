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

$def_wait = 10;
$def_size = 100;
define('MEGAMAIL_TABLE', $table_prefix . 'megamail');
include_once(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);

// Increase maximum execution time in case of a lot of users, but don't complain about it if it isn't allowed.
@set_time_limit(1200);

$message = '';
$subject = '';

if (isset($_GET['mode']))
{
	$sql = "CREATE TABLE " . MEGAMAIL_TABLE . "(
			mail_id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
			mailsession_id VARCHAR(32) NOT NULL,
			group_id MEDIUMINT(8) NOT NULL,
			email_subject VARCHAR(60) NOT NULL,
			email_body TEXT NOT NULL,
			batch_start MEDIUMINT(8) NOT NULL,
			batch_size SMALLINT UNSIGNED NOT NULL,
			batch_wait SMALLINT NOT NULL,
			status SMALLINT NOT NULL,
			user_id MEDIUMINT(8) NOT NULL
			)";
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not create tables. Are you sure you are using mySQL? Are you sure the table does not already exist?', '', __LINE__, __FILE__, $sql);
	}
}

// Do the job ...
if (isset($_POST['message']) || isset($_POST['subject']))
{
	$batchsize = (is_numeric($_POST['batchsize'])) ? intval($_POST['batchsize']) : $def_size;
	$batchwait = (is_numeric($_POST['batchwait'])) ? intval($_POST['batchwait']) : $def_wait;

	$mail_session_id = md5(uniqid(''));
	$sql = "INSERT INTO " . MEGAMAIL_TABLE ." (mailsession_id, group_id, email_subject, email_body, batch_start, batch_size, batch_wait, status, user_id)
			VALUES ('" . $mail_session_id . "', " . intval($_POST[POST_GROUPS_URL]) . ", '".str_replace("\'","''",trim($_POST['subject'])) . "', '" . str_replace("\'", "''", trim($_POST['message'])) . "', 0, " . $batchsize . "," . $batchwait . ", 0, " . $userdata['user_id'] . ")";

	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not insert the data into '. MEGAMAIL_TABLE, '', __LINE__, __FILE__, $sql);
	}
	$mail_id = $db->sql_nextid();
	$url = append_sid('admin_megamail.' . PHP_EXT . '?mail_id=' . $mail_id . '&amp;mail_session_id=' .$mail_session_id);

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

	$subject = $mail_data['email_subject'];
	$message = $mail_data['email_body'];
	$group_id = $mail_data['group_id'];

/* OLD HTML FORMAT
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

	//Now, let's see if we reached the upperlimit, if yes adjust the batch_size
	if ($group_id != -1)
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
						AND u.user_allow_mass_email = 1";
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

	if ($group_id != -1)
	{
		$sql = "SELECT u.user_email
						FROM " . USERS_TABLE . " u, " . USER_GROUP_TABLE . " ug
						WHERE ug.group_id = '" . $group_id . "'
						AND ug.user_pending <> " . TRUE . "
						AND u.user_id = ug.user_id
						AND u.user_active = 1
						AND u.user_allow_mass_email = 1";
	}
	else
	{
		$sql = "SELECT user_email
						FROM " . USERS_TABLE . " u
						WHERE u.user_active = 1
						AND u.user_allow_mass_email = 1";
	}

	$sql .= " LIMIT " . $mail_data['batch_start'] . ", " . $mail_data['batch_size'];

	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not select group members', '', __LINE__, __FILE__, $sql);
	}

	if ($row = $db->sql_fetchrow($result))
	{
		$bcc_list = '';
		do
		{
			$bcc_list .= (($bcc_list != '') ? ', ' : '') . $row['user_email'];
		}
		while ($row = $db->sql_fetchrow($result));
		$db->sql_freeresult($result);
	}
	else
	{
		$message = ($group_id != -1) ? $lang['Group_not_exist'] : $lang['No_such_user'];
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

		$emailer->use_template('admin_send_email', $board_config['default_lang']);
		$emailer->bcc($bcc_list);
		$emailer->email_address($board_config['board_email']);
		$emailer->from($board_config['board_email']);
		$emailer->replyto($board_config['board_email']);
		$emailer->extra_headers($email_headers);
		$emailer->set_subject($subject);

		// Do we want to force line breaks? It is HTML, so we should not replace line breaks...
		//$message = preg_replace(array("/<br \/>\r\n/", "/<br>\r\n/", "/(\r\n|\n|\r)/"), array("\r\n", "\r\n", "<br />\r\n"), $message);

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

			$message =  $lang['megamail_done']. '<br />' . sprintf($lang['megamail_proceed'], '<a href="' . $url . '">', '</a>');
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
$sql = "SELECT m.*, u.username, g.group_name
	FROM " . MEGAMAIL_TABLE . " m
	LEFT JOIN " . USERS_TABLE . " u ON (m.user_id = u.user_id)
	LEFT JOIN " . GROUPS_TABLE . " g ON (m.group_id = g.group_id)";
if (!($result = $db->sql_query($sql)))
{
	message_die(GENERAL_MESSAGE, sprintf('Could not obtain list of email-sessions. If you want to create the table, click <a href="%s">here to install</a>', append_sid('admin_megamail.' . PHP_EXT . '?mode=install')), '', __LINE__, __FILE__, $sql);
}
$row_class = 0;
if ($mail_data = $db->sql_fetchrow($result))
{
	do
	{
		$url = append_sid('admin_megamail.' . PHP_EXT . '?mail_id=' . $mail_data['mail_id'] . '&amp;mail_session_id=' . $mail_data['mailsession_id']);

		$look_up_array = array(
			"<",
			">",
			"\n",
			chr(13),
		);

		$replacement_array = array(
			"&lt_mg;",
			"&gt_mg;",
			"\\n",
			"",
		);

		$plain_message = $mail_data['email_body'];
		$plain_message = strtr($plain_message, array_flip(get_html_translation_table(HTML_ENTITIES)));
		$plain_message = addslashes($plain_message);
		$plain_message = str_replace($look_up_array, $replacement_array, $plain_message);

		$template->assign_block_vars('mail_sessions',array(
			'ROW' => ($row_class % 2) ? 'row2' : 'row1',
			'ID' => $mail_data['mail_id'],
			'GROUP' => ($mail_data['group_id'] != -1) ? $mail_data['group_name'] : $lang['All_users'],
			'SUBJECT' => $mail_data['email_subject'],
			'MESSAGE_BODY' => $plain_message,
			'BATCHSTART' => $mail_data['batch_start'],
			'BATCHSIZE' => $mail_data['batch_size'],
			'BATCHWAIT' => $mail_data['batch_wait'] . ' s.',
			'SENDER' => $mail_data['username'],
			'STATUS' => ($mail_data['status'] == 0) ? sprintf($lang['megamail_proceed'],  '<a href="' . $url . '">', '</a>') : 'Done',
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

$select_list = '<select name = "' . POST_GROUPS_URL . '"><option value = "-1">' . $lang['All_users'] . '</option>';
if ($row = $db->sql_fetchrow($result))
{
	do
	{
		$select_list .= '<option value = "' . $row['group_id'] . '">' . $row['group_name'] . '</option>';
	}
	while ($row = $db->sql_fetchrow($result));
}
$select_list .= '</select>';

//
// Generate page
//
include('./page_header_admin.' . PHP_EXT);

$template->set_filenames(array('body' => ADM_TPL . 'megamail.tpl'));

$template->assign_vars(array(
	'MESSAGE' => $message,
	'SUBJECT' => $subject,

	'L_EMAIL_TITLE' => $lang['Email'],
	'L_EMAIL_EXPLAIN' => $lang['Megamail_Explain'],
	'L_COMPOSE' => $lang['Compose'],
	'L_RECIPIENTS' => $lang['Recipients'],
	'L_EMAIL_SUBJECT' => $lang['Subject'],
	'L_EMAIL_MSG' => $lang['Message'],
	'L_EMAIL' => $lang['Email'],
	'L_NOTICE' => $notice,

	'S_USER_ACTION' => append_sid('admin_megamail.' . PHP_EXT),
	'S_GROUP_SELECT' => $select_list,

	'L_MAIL_SESSION_HEADER' => $lang['megamail_header'],
	'L_ID' => 'ID',
	'L_GROUP' => $lang['group_name'],
	'L_BATCH_START' => $lang['megamail_batchstart'],
	'L_BATCH_SIZE'  => $lang['megamail_batchsize'],
	'L_BATCH_WAIT'  => $lang['megamail_batchwait'],
	'L_SENDER' => $lang['Auth_Admin'],
	'L_STATUS' => $lang['megamail_status'],
	'DEFAULT_SIZE' => $def_size,
	'DEFAULT_WAIT' => $def_wait,
	)
);

$template->pparse('body');

include('./page_footer_admin.' . PHP_EXT);

?>