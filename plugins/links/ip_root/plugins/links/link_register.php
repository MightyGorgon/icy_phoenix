<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

include_once(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_post.' . PHP_EXT);

// Users Authentication, members only area
if(!$user->data['session_logged_in'])
{
	header('Location: ' . append_sid(CMS_PAGE_LOGIN . '?redirect=links.' . PHP_EXT, true));
	exit;
}

$link_title = request_var('link_title', '', true);
$link_desc = request_var('link_desc', '', true);
$link_url = request_var('link_url', '', true);
$link_logo_src = request_var('link_logo_src', '', true);
$link_logo_src = ($link_logo_src == 'http://') ? '' : $link_logo_src;
$link_category = request_var('link_category', 0);
$link_joined = time();
$user_id = $user->data['user_id'];

// Check Link config
if($links_config['lock_submit_site'] && $user->data['user_level'] != ADMIN)
{
	$message = $lang['Link_lock_submit_site'];
	$message .= '<br /><br />' . sprintf($lang['Click_return_links'], '<a href="' . append_sid('links.' . PHP_EXT) . '">', '</a>');

	$redirect_url = append_sid('links.' . PHP_EXT);
	meta_refresh(3, $redirect_url);

	message_die(GENERAL_MESSAGE, $message);
}

if(!$links_config['allow_no_logo'] && !$link_logo_src)
{
	$message = $lang['Link_incomplete'];

	$message .= '<br /><br />' . sprintf($lang['Click_return_links'], '<a href="' . append_sid('links.' . PHP_EXT) . '">', '</a>');
	$message .= '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid(CMS_PAGE_FORUM) . '">', '</a>');

	$redirect_url = append_sid('links.' . PHP_EXT);
	meta_refresh(3, $redirect_url);

	message_die(GENERAL_MESSAGE, $message);
}

// Add new link
if(!empty($link_title) && !empty($link_desc) && !empty($link_category) && !empty($link_url))
{
	// Check regiter interval
	$sql = "SELECT MAX(link_joined) AS last_link_joined FROM " . LINKS_TABLE . "
		WHERE " . (($user_id != ANONYMOUS) ? "user_id = '$user_id'" : "user_ip = '$user_ip'");
	$result = $db->sql_query($sql);
	if($row = $db->sql_fetchrow($result))
	{
		$last_link_joined = $row['last_link_joined'];
	}
	else
	{
		$last_link_joined = 0;
	}

	if(($link_joined - $last_link_joined) > 60)
	{
		$is_admin = ($user->data['user_level'] == ADMIN) ? true : 0;
		$sql = "INSERT INTO " . LINKS_TABLE . " (link_title, link_desc, link_category, link_url, link_logo_src, link_joined,link_active , user_id , user_ip)
			VALUES ('" . $db->sql_escape($link_title) . "', '" . $db->sql_escape($link_desc) . "', '$link_category', '" . $db->sql_escape($link_url) . "', '" . $db->sql_escape($link_logo_src) . "', '$link_joined', '$is_admin', '$user_id ', '$user_ip')";
		$db->sql_query($sql);

		if ($user->data['user_level'] != ADMIN)
		{
			$sql = "SELECT user_id, username, user_notify_pm, user_allow_pm, user_email, user_lang, user_active
				FROM " . USERS_TABLE . "
				WHERE user_level = " . ADMIN;
			$admin_result = $db->sql_query($sql);

			if ($links_config['email_notify'])
			{
				include(IP_ROOT_PATH . 'includes/emailer.' . PHP_EXT);
				while($to_userdata = $db->sql_fetchrow($admin_result))
				{
					if ($to_userdata['user_email'])
					{
						$emailer = new emailer();
						$emailer->use_template('link_add', $to_userdata['user_lang'], false, PLUGINS_PATH . $config['plugins'][$plugin_name]['dir']);
						$emailer->to($to_userdata['user_email']);

						$emailer->assign_vars(array(
							'LINK_URL' => $link_url,
							'SITENAME' => $config['sitename']
							)
						);

						$emailer->send();
						$emailer->reset();
						unset($emailer);
					}
				}
			}

			if (empty($config['privmsg_disable']) && $links_config['pm_notify'])
			{
				include_once(IP_ROOT_PATH . 'includes/class_pm.' . PHP_EXT);

				$html_on = 0;
				$acro_auto_on = 0;
				$bbcode_on = 0;
				$smilies_on = 0;
				$attach_sig = 0;

				while($to_userdata = $db->sql_fetchrow($admin_result))
				{
					// Has admin prevented user from sending PM's?
					if ($to_userdata['user_allow_pm'])
					{
						$privmsg_sender = ($user->data['user_id'] != ANONYMOUS) ? $user->data['user_id'] : $to_userdata['user_id'];
						$privmsg_recipient = $to_userdata['user_id'];
						$privmsg_subject = $lang['Link_pm_notify_subject'];
						$privmsg_message = sprintf($lang['Link_pm_notify_message'], $link_url);
						$privmsg_message = stripslashes(prepare_message($privmsg_message, $html_on, $bbcode_on, $smilies_on));

						$privmsg = new class_pm();
						$privmsg->delete_older_message('PM_INBOX', $privmsg_recipient);
						$privmsg->send($privmsg_sender, $privmsg_recipient, $privmsg_subject, $privmsg_message);
						unset($privmsg);
					}
				}
			}
		}
		$message = $lang['Link_update_success'];
	}
	else
	{
		$message = $lang['Link_intval_warning'];
	}
}
else
{
	$message = $lang['Link_incomplete'];
}

$message .= '<br /><br />' . sprintf($lang['Click_return_links'], '<a href="' . append_sid('links.' . PHP_EXT) . '">', '</a>');
$message .= '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid(CMS_PAGE_FORUM) . '">', '</a>');

$redirect_url = append_sid('links.' . PHP_EXT);
meta_refresh(3, $redirect_url);

message_die(GENERAL_MESSAGE, $message);

?>