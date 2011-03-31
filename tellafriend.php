<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

define('CT_SECLEVEL', 'MEDIUM');
$ct_ignoregvar = array('');
define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_post.' . PHP_EXT);

// Start session management
$user->session_begin();
//$auth->acl($user->data);
$user->setup();
// End session management

$topic_title = request_var('topic_title', '', true);
$topic_id = request_var('topic_id', 0);
$friendname = request_var('friendname', '', true);
$message = request_var('message', '', true);
$PHP_SELF = $_SERVER['SCRIPT_NAME'];

if (!$user->data['session_logged_in'])
{
	redirect(append_sid(CMS_PAGE_LOGIN . '?redirect=' . 'tellafriend.' . PHP_EXT . '&topic_title=' . urlencode($topic_title) . '&topic_id=' . $topic_id, true));
}

if (($config['url_rw'] == true) || ($config['url_rw_guests'] == true))
{
	$topic_link = create_server_url() . make_url_friendly($topic_title) . '-vt' . $topic_id . '.html';
}
else
{
	$topic_link = create_server_url() . CMS_PAGE_VIEWTOPIC . '?' . POST_TOPIC_URL . '=' . $topic_id;
}

$mail_body = str_replace("{TOPIC}", htmlspecialchars_decode($topic_title), $lang['TELL_FRIEND_BODY']);
$mail_body = str_replace("{LINK}", $topic_link, $mail_body);
$mail_body = str_replace("{SITENAME}", $config['sitename'], $mail_body);

$template->assign_vars(array(
	'SUBMIT_ACTION' => append_sid($PHP_SELF, true),
	'L_SUBMIT' => $lang['Send_email'],
	'SITENAME' => $config['sitename'],

	'SENDER_NAME' => $user->data['username'],
	'SENDER_MAIL' => $user->data['user_email'],

	'L_TELL_FRIEND_BODY' => $mail_body,

	'TOPIC_TITLE' => $topic_title,
	'TOPIC_ID' => $topic_id,
	'TOPIC_LINK' => $topic_link,
	)
);

/**************/
if (isset($_POST['submit']))
{
	$error = false;

	$friendemail = request_var('friendemail', '', true);
	$friendname = request_var('friendname', '', true);
	$topic_title = request_var('topic_title', '', true);
	$message = request_var('message', '', true);
	// We need to check if HTML emails are enabled so we can correctly escape content and linebreaks
	if (!empty($config['html_email']))
	{
		$message = nl2br(str_replace($topic_link, ('<a href="' . $topic_link . '">' . $topic_link . '</a>'), $message));
	}
	else
	{
		$message = htmlspecialchars_decode($message, ENT_COMPAT);
	}

	if (!empty($friendemail) && (strpos($friendemail, '@') > 0))
	{
		if (empty($friendname))
		{
			$friendname = substr($friendemail, 0, strpos($friendemail, '@'));
		}
	}
	else
	{
		$error = true;
		$error_msg = $lang['Tell_Friend_Wrong_Email'];
	}

	if (!$error)
	{
		include(IP_ROOT_PATH . 'includes/emailer.' . PHP_EXT);
		$emailer = new emailer();

		$emailer->headers('X-AntiAbuse: Board servername - ' . trim($config['server_name']));
		$emailer->headers('X-AntiAbuse: User_id - ' . $user->data['user_id']);
		$emailer->headers('X-AntiAbuse: Username - ' . $user->data['username']);
		$emailer->headers('X-AntiAbuse: User IP - ' . $user_ip);

		$emailer->use_template('tellafriend_email', $user_lang);
		$emailer->to($friendemail, $friendname);
		$emailer->from($user->data['user_email']);
		$emailer->replyto($user->data['user_email']);
		$emailer->set_subject($topic_title);

		$emailer->assign_vars(array(
			'SITENAME' => $config['sitename'],
			'BOARD_EMAIL' => $config['board_email'],
			'FROM_USERNAME' => $user->data['username'],
			'TO_USERNAME' => $friendname,
			'MESSAGE' => $message
			)
		);
		$emailer->send();
		$emailer->reset();

		$redirect_url = append_sid(CMS_PAGE_FORUM);
		meta_refresh(5, $redirect_url);

		$message = $lang['Email_sent'] . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid(CMS_PAGE_FORUM) . '">', '</a>');

		message_die(GENERAL_MESSAGE, $message);
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

}

full_page_generation('tellafriend_body.tpl', '', '', '');

?>