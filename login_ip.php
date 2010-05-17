<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

define('IN_LOGIN', true);
define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/auth_db.' . PHP_EXT);

if (!class_exists('ct_database'))
{
	include(IP_ROOT_PATH . 'includes/ctracker/classes/class_ct_database.' . PHP_EXT);
	$ctracker_config = new ct_database();
}

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

// session id check
$sid = request_var('sid', '');

$redirect = request_var('redirect', '', true);
$redirect_url = (!empty($redirect) ? urldecode(str_replace(array('&amp;', '?', PHP_EXT . '&'), array('&', '&', PHP_EXT . '?'), $redirect)) : '');

if (strstr($redirect_url, "\n") || strstr($redirect_url, "\r") || strstr($redirect_url, ';url'))
{
	message_die(GENERAL_ERROR, 'Tried to redirect to potentially insecure url.');
}

if(isset($_POST['login']) || isset($_GET['login']) || isset($_POST['logout']) || isset($_GET['logout']))
{
	if((isset($_POST['login']) || isset($_GET['login'])) && (!$userdata['session_logged_in'] || isset($_POST['admin'])))
	{
		$username = isset($_POST['username']) ? phpbb_clean_username($_POST['username']) : '';
		$password = isset($_POST['password']) ? $_POST['password'] : '';

		$login_result = login_db($username, $password, false, true);

		if ($login_result['status'] === LOGIN_ERROR_ATTEMPTS)
		{
			message_die(GENERAL_MESSAGE, sprintf($lang['LOGIN_ATTEMPTS_EXCEEDED'], $config['max_login_attempts'], $config['login_reset_time']));
		}

		if ($login_result['status'] === LOGIN_SUCCESS)
		{
			if(($login_result['user_row']['user_level'] != ADMIN) && $config['board_disable'])
			{
				redirect(append_sid(CMS_PAGE_FORUM, true));
			}
			else
			{
				// CrackerTracker v5.x
				if ($config['ctracker_login_history'] == 1)
				{
					$ctracker_config->update_login_history($login_result['user_row']['user_id']);
				}

				if ($config['ctracker_login_ip_check'] == 1)
				{
					$ctracker_config->set_user_ip($login_result['user_row']['user_id']);
				}
				// CrackerTracker v5.x

				$autologin = (isset($_POST['autologin'])) ? true : 0;

				if (isset($_POST['online_status']) && (($_POST['online_status'] == 'hidden') || ($_POST['online_status'] == 'visible')))
				{
					$sql = 'UPDATE ' . USERS_TABLE . ' SET user_allow_viewonline = ' . (($_POST['online_status'] == 'hidden') ? '0' : '1') . ' WHERE user_id = ' . $login_result['user_row']['user_id'];
					$db->sql_return_on_error(true);
					$db->sql_query($sql);
					$db->sql_return_on_error(false);
				}

				$admin = (isset($_POST['admin'])) ? 1 : 0;
				$session_id = session_begin($login_result['user_row']['user_id'], $user_ip, false, $autologin, $admin);

				if($session_id)
				{
					$redirect_url = ($redirect_url == '') ? CMS_PAGE_FORUM : $redirect_url;
					redirect(append_sid($redirect_url, true));
				}
				else
				{
					message_die(CRITICAL_ERROR, "Couldn't start session: login", "", __LINE__, __FILE__);
				}
			}
		}
		else
		{
			if (($login_result['status'] === LOGIN_ERROR_USERNAME) || ($login_result['status'] === LOGIN_ERROR_PASSWORD) || ($login_result['status'] === LOGIN_ERROR_ACTIVE))
			{
				if ($login_result['error_msg'] === 'LOGIN_ERROR_PASSWORD')
				{
					// CrackerTracker v5.x
					if (!class_exists('log_manager'))
					{
						include(IP_ROOT_PATH . 'includes/ctracker/classes/class_log_manager.' . PHP_EXT);
					}
					$logfile = new log_manager();
					$logfile->prepare_log($login_result['user_row']['username']);
					$logfile->write_general_logfile($config['ctracker_logsize_logins'], 4);
					unset($logfile);
					// CrackerTracker v5.x
				}
				$error_message = ($login_result['error_msg'] === 'NO_PASSWORD_SUPPLIED') ? $lang[$login_result['error_msg']] : sprintf($lang[$login_result['error_msg']], '<a href="' . append_sid(CMS_PAGE_CONTACT_US) . '">', '</a>');
				message_die(GENERAL_MESSAGE, $error_message);
			}

			meta_refresh(3, (CMS_PAGE_LOGIN . '?redirect=' . htmlspecialchars($redirect_url)));

			$message = $lang['Error_login'] . '<br /><br />' . sprintf($lang['Click_return_login'], '<a href="' . CMS_PAGE_LOGIN . '?redirect=' . htmlspecialchars($redirect_url) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid(CMS_PAGE_FORUM) . '">', '</a>');

			message_die(GENERAL_MESSAGE, $message);
		}
	}
	elseif((isset($_GET['logout']) || isset($_POST['logout'])) && $userdata['session_logged_in'])
	{
		// session id check
		if (($sid == '') || ($sid != $userdata['session_id']))
		{
			message_die(GENERAL_ERROR, 'Invalid_session');
		}
		if($userdata['session_logged_in'])
		{
			session_end($userdata['session_id'], $userdata['user_id']);
		}

		$redirect_url = ($redirect_url == '') ? CMS_PAGE_FORUM : $redirect_url;
		redirect(append_sid($redirect_url, true));
	}
	else
	{
		$redirect_url = ($redirect_url == '') ? CMS_PAGE_FORUM : $redirect_url;
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
		$skip_nav_cat = true;

		if($redirect_url != '')
		{
			$forward_to = $_SERVER['QUERY_STRING'];

			if(preg_match("/^redirect=([a-z0-9\.#\/\?&=\+\-_]+)/si", $forward_to, $forward_matches))
			{
				$forward_to = (!empty($forward_matches[3])) ? $forward_matches[3] : $forward_matches[1];
				$forward_match = explode('&', $forward_to);

				if(sizeof($forward_match) > 1)
				{
					$forward_page = '';
					for($i = 1; $i < sizeof($forward_match); $i++)
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

		make_jumpbox(CMS_PAGE_VIEWFORUM);
		$template->assign_vars(array(
			'USERNAME' => $username,

			'L_ENTER_PASSWORD' => (isset($_GET['admin'])) ? $lang['Admin_reauthenticate'] : $lang['Enter_password'],
			'L_SEND_PASSWORD' => $lang['Forgotten_password'],
			'L_RESEND_ACTIVATION_EMAIL' => $lang['Resend_activation_email'],
			'L_STATUS' => $lang['Login_Status'],
			'L_HIDDEN' => $lang['Login_Hidden'],
			'L_VISIBLE' => $lang['Login_Visible'],
			'L_DEFAULT' => $lang['Login_Default'],

			'U_SEND_PASSWORD' => append_sid(CMS_PAGE_PROFILE . '?mode=sendpassword'),
			'U_RESEND_ACTIVATION_EMAIL' => append_sid(CMS_PAGE_PROFILE . '?mode=resend'),

			'S_HIDDEN_FIELDS' => $s_hidden_fields
			)
		);

		if (!isset($_GET['admin']) && ($config['require_activation'] == USER_ACTIVATION_SELF))
		{
			$template->assign_block_vars('switch_resend_activation_email', array());
		}

		if (!isset($_GET['admin']))
		{
			$template->assign_block_vars('switch_login_type', array());
		}

		full_page_generation('login_body.tpl', $lang['Login'], '', '');
	}
	else
	{
		redirect(append_sid(CMS_PAGE_FORUM, true));
	}
}

?>