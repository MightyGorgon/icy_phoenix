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
* OOHOO < webdev@phpbb-tw.net >
* Stefan2k1 and ddonker from www.portedmods.com
* CRLin from http://mail.dhjh.tcc.edu.tw/~gzqbyr/
*
*/

define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_post.' . PHP_EXT);

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
require(IP_ROOT_PATH . 'language/lang_' . $board_config['default_lang'] . '/lang_main_link.' . PHP_EXT);
// End session management

$cms_page_id = '13';
$cms_page_name = 'links';
check_page_auth($cms_page_id, $cms_page_name);
$cms_global_blocks = ($board_config['wide_blocks_' . $cms_page_name] == 1) ? true : false;

// Users Authentication, members only area
if(!$userdata['session_logged_in'])
{
	header('Location: ' . append_sid(LOGIN_MG . '?redirect=links.' . PHP_EXT, true));
	exit;
}

$link_title = (!empty($_POST['link_title'])) ? trim($_POST['link_title']) : '';
$link_desc = (!empty($_POST['link_desc'])) ? trim($_POST['link_desc']) : '';
$link_category = (!empty($_POST['link_category'])) ? (is_numeric($_POST['link_category']) ? $_POST['link_category'] : 0) : 0;
$link_url = (!empty($_POST['link_url'])) ? trim($_POST['link_url']) : '';
$link_logo_src = (!empty($_POST['link_logo_src'])) ? trim($_POST['link_logo_src']) : '';
if ($link_logo_src == 'http://')  $link_logo_src = '';
$link_joined = time();
$user_id = $userdata['user_id'];

//
// Get Link Config
//
$sql = "SELECT *
		FROM ". LINK_CONFIG_TABLE;
if(!$result = $db->sql_query($sql))
{
	message_die(GENERAL_ERROR, "Could not query Link config information", "", __LINE__, __FILE__, $sql);
}
while($row = $db->sql_fetchrow($result))
{
	$link_config_name = $row['config_name'];
	$link_config_value = $row['config_value'];
	$link_config[$link_config_name] = $link_config_value;
}

//
// Check Link config
//
if($link_config['lock_submit_site'] && $userdata['user_level'] != ADMIN)
{
	$message = $lang['Link_lock_submit_site'];
	$message .= '<br /><br />' . sprintf($lang['Click_return_links'], '<a href="' . append_sid('links.' . PHP_EXT) . '">', '</a>');

	$redirect_url = append_sid('links.' . PHP_EXT);
	meta_refresh(3, $redirect_url);

	message_die(GENERAL_MESSAGE, $message);
}

if(!$link_config['allow_no_logo'] && !$link_logo_src)
{
	$message = $lang['Link_incomplete'];

	$message .= '<br /><br />' . sprintf($lang['Click_return_links'], '<a href="' . append_sid('links.' . PHP_EXT) . '">', '</a>');
	$message .= '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid(FORUM_MG) . '">', '</a>');

	$redirect_url = append_sid('links.' . PHP_EXT);
	meta_refresh(3, $redirect_url);

	message_die(GENERAL_MESSAGE, $message);
}

//
// Add new link
//
if($link_title && $link_desc && $link_category && $link_url)
{
	// Check regiter interval
	$sql = "SELECT MAX(link_joined) AS last_link_joined FROM " . LINKS_TABLE . "
		WHERE " . (($user_id != ANONYMOUS) ? "user_id = '$user_id'" : "user_ip = '$user_ip'");

	if (!($result = $db->sql_query($sql)))
	{
		$message = $lang['Link_update_fail'];
	}
	else
	{
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
			$is_admin = ($userdata['user_level'] == ADMIN) ? true : 0;
			$sql = "INSERT INTO " . LINKS_TABLE . " (link_title, link_desc, link_category, link_url, link_logo_src, link_joined,link_active , user_id , user_ip)
				VALUES ('$link_title', '$link_desc', '$link_category', '$link_url', '$link_logo_src', '$link_joined', '$is_admin', '$user_id ', '$user_ip')";

			if (!$db->sql_query($sql))
			{
				$message = $lang['Link_update_fail'];
			}
			else
			{
				if ($userdata['user_level'] != ADMIN)
				{
					$sql = "SELECT user_id, username, user_notify_pm, user_allow_pm, user_email, user_lang, user_active
						FROM " . USERS_TABLE . "
						WHERE user_level = " . ADMIN;
					if (!($admin_result = $db->sql_query($sql)))
					{
						message_die(GENERAL_ERROR, "Could not query users table", "", __LINE__, __FILE__, $sql);
					}

					if ($link_config['email_notify'])
					{
						include(IP_ROOT_PATH . 'includes/emailer.' . PHP_EXT);
						while($to_userdata = $db->sql_fetchrow($admin_result))
						{
							if ($to_userdata['user_email'])
							{
								$emailer = new emailer($board_config['smtp_delivery']);

								$emailer->from($board_config['board_email']);
								$emailer->replyto($board_config['board_email']);

								$emailer->use_template('link_add', $to_userdata['user_lang']);
								$emailer->email_address($to_userdata['user_email']);

								$emailer->assign_vars(array(
									'LINK_URL' => $link_url,
									'SITENAME' => $board_config['sitename']
									)
								);

								$emailer->send();
								$emailer->reset();
								unset($emailer);
							}
						}
					}

					if (empty($board_config['privmsg_disable']) && $link_config['pm_notify'])
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
								$privmsg_sender = ($userdata['user_id'] != ANONYMOUS) ? $userdata['user_id'] : $to_userdata['user_id'];
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
		}
		else
		{
			$message = $lang['Link_intval_warning'];
		}
	}
}
else
{
	$message = $lang['Link_incomplete'];
}

$message .= '<br /><br />' . sprintf($lang['Click_return_links'], '<a href="' . append_sid('links.' . PHP_EXT) . '">', '</a>');
$message .= '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid(FORUM_MG) . '">', '</a>');

$redirect_url = append_sid('links.' . PHP_EXT);
meta_refresh(3, $redirect_url);

message_die(GENERAL_MESSAGE, $message);

?>