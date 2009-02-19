<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

// CTracker_Ignore: File checked by human
define('IN_ICYPHOENIX', true);
define('IN_LOGIN', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

// session id check
$sid = request_var('sid', '');

$redirect = request_var('redirect', '');
$redirect_url = (!empty($redirect) ? urldecode(str_replace(array('&amp;', '?', PHP_EXT . '&'), array('&', '&', PHP_EXT . '?'), $redirect)) : '');

if (strstr($redirect_url, "\n") || strstr($redirect_url, "\r") || strstr($redirect_url, ';url'))
{
	message_die(GENERAL_ERROR, 'Tried to redirect to potentially insecure url.');
}

// CrackerTracker v5.x
if (!empty($_POST['username']) && ($ctracker_config->settings['loginfeature'] == 1))
{
	$ctracker_config->check_login_status($_POST['username']);
}
// CrackerTracker v5.x

if(isset($_POST['login']) || isset($_GET['login']) || isset($_POST['logout']) || isset($_GET['logout']))
{
	if((isset($_POST['login']) || isset($_GET['login'])) && (!$userdata['session_logged_in'] || isset($_POST['admin'])))
	{
		$username = isset($_POST['username']) ? phpbb_clean_username($_POST['username']) : '';
		$password = isset($_POST['password']) ? $_POST['password'] : '';

		$sql = "SELECT user_id, username, user_password, user_active, user_level, user_login_tries, user_last_login_try, ct_login_count
			FROM " . USERS_TABLE . "
			WHERE username = '" . str_replace("\\'", "''", $username) . "'";
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Error in obtaining userdata', '', __LINE__, __FILE__, $sql);
		}

		if($row = $db->sql_fetchrow($result))
		{
			if(($row['user_level'] != ADMIN) && $board_config['board_disable'])
			{
				redirect(append_sid(FORUM_MG, true));
			}
			else
			{
				// If the last login is more than x minutes ago, then reset the login tries/time
				if ($row['user_last_login_try'] && $board_config['login_reset_time'] && ($row['user_last_login_try'] < (time() - ($board_config['login_reset_time'] * 60))))
				{
					$login_reset = mg_reset_login_system($row['user_id']);
					//$db->sql_query('UPDATE ' . USERS_TABLE . ' SET user_login_tries = 0, user_last_login_try = 0 WHERE user_id = ' . $row['user_id']);
					$row['user_last_login_try'] = $row['user_login_tries'] = 0;
				}
				// CrackerTracker v5.x
				if ($ctracker_config->settings['login_history'] == 1)
				{
					$ctracker_config->update_login_history($row['user_id']);
				}

				/*
				if ($ctracker_config->settings['loginfeature'] == 1)
				{
					$ctracker_config->reset_login_system($row['user_id']);
				}
				*/

				if ($ctracker_config->settings['login_ip_check'] == 1)
				{
					$ctracker_config->set_user_ip($row['user_id']);
				}
				// CrackerTracker v5.x

				// Check to see if user is allowed to login again... if his tries are exceeded
				if ($row['user_last_login_try'] && $board_config['login_reset_time'] && $board_config['max_login_attempts'] &&
					($row['user_last_login_try'] >= (time() - ($board_config['login_reset_time'] * 60))) && ($row['user_login_tries'] >= $board_config['max_login_attempts']) && ($userdata['user_level'] != ADMIN))
				{
					message_die(GENERAL_MESSAGE, sprintf($lang['Login_attempts_exceeded'], $board_config['max_login_attempts'], $board_config['login_reset_time']));
				}
				if((md5($password) == $row['user_password']) && $row['user_active'])
				{
					$autologin = (isset($_POST['autologin'])) ? true : 0;

					if (isset($_POST['online_status']))
					{
						if ($_POST['online_status'] == 'hidden')
						{
							$sql = 'UPDATE ' . USERS_TABLE . ' SET user_allow_viewonline = 0 WHERE user_id = ' . $row['user_id'];
							if(!$db->sql_query($sql))
							{
								//message_die(CRITICAL_ERROR, "Could not update user online status.", "", __LINE__, __FILE__, $sql);
							}
						}
						elseif ($_POST['online_status'] == 'visible')
						{
							$sql = 'UPDATE ' . USERS_TABLE . ' SET user_allow_viewonline = 1 WHERE user_id = ' . $row['user_id'];
							if(!$db->sql_query($sql))
							{
								//message_die(CRITICAL_ERROR, "Could not update user online status.", "", __LINE__, __FILE__, $sql);
							}
						}
					}

					$admin = (isset($_POST['admin'])) ? 1 : 0;
					$session_id = session_begin($row['user_id'], $user_ip, false, $autologin, $admin);

					// Reset login tries
					$db->sql_query('UPDATE ' . USERS_TABLE . ' SET user_login_tries = 0, user_last_login_try = 0 WHERE user_id = ' . $row['user_id']);

					if($session_id)
					{
						$redirect_url = ($redirect_url == '') ? FORUM_MG : $redirect_url;
						redirect(append_sid($redirect_url, true));
					}
					else
					{
						message_die(CRITICAL_ERROR, "Couldn't start session : login", "", __LINE__, __FILE__);
					}
				}
				// Only store a failed login attempt for an active user - inactive users can't login even with a correct password
				elseif($row['user_active'])
				{
					// Save login tries and last login
					if ($row['user_id'] != ANONYMOUS)
					{
						// CrackerTracker v5.x
						include_once(IP_ROOT_PATH . 'ctracker/classes/class_log_manager.' . PHP_EXT);
						$logfile = new log_manager();
						$logfile->prepare_log($row['username']);
						$logfile->write_general_logfile($ctracker_config->settings['logsize_logins'], 4);
						unset($logfile);

						if ($ctracker_config->settings['loginfeature'] == 1)
						{
							$ctracker_config->handle_wrong_login($row['user_id'], $row['ct_login_count']);
						}
						// CrackerTracker v5.x
						$sql = 'UPDATE ' . USERS_TABLE . '
							SET user_login_tries = user_login_tries + 1, user_last_login_try = ' . time() . '
							WHERE user_id = ' . $row['user_id'];
						$db->sql_query($sql);
					}
				}

				meta_refresh(3, (LOGIN_MG . '?redirect=' . htmlspecialchars($redirect_url)));

				$message = $lang['Error_login'] . '<br /><br />' . sprintf($lang['Click_return_login'], '<a href="' . LOGIN_MG . '?redirect=' . htmlspecialchars($redirect_url) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid(FORUM_MG) . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);
			}
		}
		else
		{
			meta_refresh(3, (LOGIN_MG . '?redirect=' . htmlspecialchars($redirect_url)));

			$message = $lang['Error_login'] . '<br /><br />' . sprintf($lang['Click_return_login'], '<a href="' . LOGIN_MG . '?redirect=' . htmlspecialchars($redirect_url) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid(FORUM_MG) . '">', '</a>');

			message_die(GENERAL_MESSAGE, $message);
		}
	}
	elseif((isset($_GET['logout']) || isset($_POST['logout'])) && $userdata['session_logged_in'])
	{
		// session id check
		if ($sid == '' || $sid != $userdata['session_id'])
		{
			message_die(GENERAL_ERROR, 'Invalid_session');
		}
		if($userdata['session_logged_in'])
		{
			session_end($userdata['session_id'], $userdata['user_id']);
		}

		$redirect_url = ($redirect_url == '') ? FORUM_MG : $redirect_url;
		redirect(append_sid($redirect_url, true));
	}
	else
	{
		$redirect_url = ($redirect_url == '') ? FORUM_MG : $redirect_url;
		redirect(append_sid($redirect_url, true));
	}
}
else
{
	// Do a full login page dohickey if user not already logged in
	include_once(IP_ROOT_PATH . 'includes/functions_jr_admin.' . PHP_EXT);
	$jr_admin_userdata = jr_admin_get_user_info($userdata['user_id']);

	if(!$userdata['session_logged_in'] || (isset($_GET['admin']) && $userdata['session_logged_in'] && (!empty($jr_admin_userdata['user_jr_admin']) || ($userdata['user_level'] == ADMIN) || (($userdata['user_cms_level'] >= CMS_PUBLISHER)))))
	{
		$page_title = $lang['Login'];
		$meta_description = '';
		$meta_keywords = '';
		$skip_nav_cat = true;
		include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);

		$template->set_filenames(array('body' => 'login_body.tpl'));

		if($redirect_url != '')
		{
			$forward_to = $_SERVER['QUERY_STRING'];

			if(preg_match("/^redirect=([a-z0-9\.#\/\?&=\+\-_]+)/si", $forward_to, $forward_matches))
			{
				$forward_to = (!empty($forward_matches[3])) ? $forward_matches[3] : $forward_matches[1];
				$forward_match = explode('&', $forward_to);

				if(count($forward_match) > 1)
				{
					$forward_page = '';
					for($i = 1; $i < count($forward_match); $i++)
					{
						if(!ereg("sid=", $forward_match[$i]))
						{
							if($forward_page != '')
							{
								$forward_page .= '&';
							}
							$forward_page .= $forward_match[$i];
						}
					}
					$forward_page = $forward_match[0] . '?' . $forward_page;
				}
				else
				{
					$forward_page = $forward_match[0];
				}
			}
		}

		$username = ($userdata['user_id'] != ANONYMOUS) ? $userdata['username'] : '';

		$s_hidden_fields = '<input type="hidden" name="redirect" value="' . htmlspecialchars($forward_page) . '" />';
		$s_hidden_fields .= (isset($_GET['admin'])) ? '<input type="hidden" name="admin" value="1" />' : '';

		make_jumpbox(VIEWFORUM_MG);
		$template->assign_vars(array(
			'USERNAME' => $username,

			'L_ENTER_PASSWORD' => (isset($_GET['admin'])) ? $lang['Admin_reauthenticate'] : $lang['Enter_password'],
			'L_SEND_PASSWORD' => $lang['Forgotten_password'],
			'L_RESEND_ACTIVATION_EMAIL' => $lang['Resend_activation_email'],
			'L_STATUS' => $lang['Login_Status'],
			'L_HIDDEN' => $lang['Login_Hidden'],
			'L_VISIBLE' => $lang['Login_Visible'],
			'L_DEFAULT' => $lang['Login_Default'],

			'U_SEND_PASSWORD' => append_sid(PROFILE_MG . '?mode=sendpassword'),
			'U_RESEND_ACTIVATION_EMAIL' => append_sid(PROFILE_MG . '?mode=resend'),

			'S_HIDDEN_FIELDS' => $s_hidden_fields
			)
		);

		if (!isset($_GET['admin']) && ($board_config['require_activation'] == USER_ACTIVATION_SELF))
		{
			$template->assign_block_vars('switch_resend_activation_email', array());
		}

		if (!isset($_GET['admin']) )
		{
			$template->assign_block_vars('switch_login_type', array());
		}

		$template->pparse('body');

		include(IP_ROOT_PATH . 'includes/page_tail.' . PHP_EXT);
	}
	else
	{
		redirect(append_sid(FORUM_MG, true));
	}
}

?>