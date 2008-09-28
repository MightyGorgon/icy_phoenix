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

define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_groups.' . PHP_EXT);

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

$cms_page_id = '8';
$cms_page_name = 'group_cp';
$auth_level_req = $board_config['auth_view_group_cp'];
if ($auth_level_req > AUTH_ALL)
{
	if (($auth_level_req == AUTH_REG) && (!$userdata['session_logged_in']))
	{
		message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
	}
	if ($userdata['user_level'] != ADMIN)
	{
		if ($auth_level_req == AUTH_ADMIN)
		{
			message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
		}
		if (($auth_level_req == AUTH_MOD) && ($userdata['user_level'] != MOD))
		{
			message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
		}
	}
}
$cms_global_blocks = ($board_config['wide_blocks_group_cp'] == 1) ? true : false;

$script_name = preg_replace('/^\/?(.*?)\/?$/', "\\1", trim($board_config['script_path']));
$script_name = ($script_name != '') ? $script_name . '/groupcp.' . PHP_EXT : 'groupcp.' . PHP_EXT;
$server_name = trim($board_config['server_name']);
$server_protocol = ($board_config['cookie_secure']) ? 'https://' : 'http://';
$server_port = ($board_config['server_port'] <> 80) ? ':' . trim($board_config['server_port']) . '/' : '/';

$server_url = $server_protocol . $server_name . $server_port . $script_name;

if (isset($_GET[POST_GROUPS_URL]) || isset($_POST[POST_GROUPS_URL]))
{
	$group_id = (isset($_POST[POST_GROUPS_URL])) ? intval($_POST[POST_GROUPS_URL]) : intval($_GET[POST_GROUPS_URL]);
}
else
{
	$group_id = '';
}

if (isset($_POST['mode']) || isset($_GET['mode']))
{
	$mode = (isset($_POST['mode'])) ? $_POST['mode'] : $_GET['mode'];
	$mode = htmlspecialchars($mode);
}
else
{
	$mode = '';
}

$confirm = isset($_POST['confirm']) ? true : 0;
$cancel = isset($_POST['cancel']) ? true : 0;

$sid = isset($_POST['sid']) ? $_POST['sid'] : '';

$start = isset($_GET['start']) ? intval($_GET['start']) : 0;
$start = ($start < 0) ? 0 : $start;

// Default var values
$is_moderator = false;

if (isset($_POST['groupstatus']) && $group_id)
{
	if (!$userdata['session_logged_in'])
	{
		redirect(append_sid(LOGIN_MG . '?redirect=groupcp.' . PHP_EXT . '&' . POST_GROUPS_URL . '=' . $group_id, true));
	}

	$sql = "SELECT group_moderator
		FROM " . GROUPS_TABLE . "
		WHERE group_id = '" . $group_id . "'";
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not obtain user and group information', '', __LINE__, __FILE__, $sql);
	}

	$row = $db->sql_fetchrow($result);

	if ($row['group_moderator'] != $userdata['user_id'] && $userdata['user_level'] != ADMIN)
	{
		$redirect_url = append_sid(FORUM_MG);
		meta_refresh(3, $redirect_url);

		$message = $lang['Not_group_moderator'] . '<br /><br />' . sprintf($lang['Click_return_group'], '<a href="' . append_sid('groupcp.' . PHP_EXT . '?' . POST_GROUPS_URL . '=' . $group_id) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid(FORUM_MG) . '">', '</a>');

		message_die(GENERAL_MESSAGE, $message);
	}

	$sql = "UPDATE " . GROUPS_TABLE . "
		SET group_type = " . intval($_POST['group_type']) . "
		WHERE group_id = '" . $group_id . "'";
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not obtain user and group information', '', __LINE__, __FILE__, $sql);
	}

	$redirect_url = append_sid('groupcp.' . PHP_EXT . '?' . POST_GROUPS_URL . '=' . $group_id);
	meta_refresh(3, $redirect_url);

	$message = $lang['Group_type_updated'] . '<br /><br />' . sprintf($lang['Click_return_group'], '<a href="' . append_sid('groupcp.' . PHP_EXT . '?' . POST_GROUPS_URL . '=' . $group_id) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid(FORUM_MG) . '">', '</a>');

	$db->clear_cache('groups_');

	message_die(GENERAL_MESSAGE, $message);
}
elseif (isset($_POST['colorize_all']) && $group_id)
{
	if (!$userdata['session_logged_in'])
	{
		redirect(append_sid(LOGIN_MG . '?redirect=groupcp.' . PHP_EXT . '&' . POST_GROUPS_URL . '=' . $group_id, true));
	}
	elseif ($sid !== $userdata['session_id'])
	{
		message_die(GENERAL_ERROR, $lang['Session_invalid']);
	}

	update_all_users_colors_ranks($group_id);

	$redirect_url = append_sid('groupcp.' . PHP_EXT . '?' . POST_GROUPS_URL . '=' . $group_id);
	meta_refresh(3, $redirect_url);

	$message = $lang['Group_members_updated'] . '<br /><br />' . sprintf($lang['Click_return_group'], '<a href="' . append_sid('groupcp.' . PHP_EXT . '?' . POST_GROUPS_URL . '=' . $group_id) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid(FORUM_MG) . '">', '</a>');

	message_die(GENERAL_MESSAGE, $message);
}
elseif (isset($_POST['joingroup']) && $group_id)
{
	// First, joining a group
	// If the user isn't logged in redirect them to login
	if (!$userdata['session_logged_in'])
	{
		redirect(append_sid(LOGIN_MG . '?redirect=groupcp.' . PHP_EXT . '&' . POST_GROUPS_URL . '=' . $group_id, true));
	}

	$sql = "SELECT ug.user_id, g.group_type, g.group_rank, g.group_color, g.group_count, g.group_count_max
		FROM " . USER_GROUP_TABLE . " ug, " . GROUPS_TABLE . " g
		WHERE g.group_id = '" . $group_id . "'
			AND (g.group_type <> " . GROUP_HIDDEN . " OR (g.group_count <= '" . $userdata['user_posts'] . "' AND g.group_count_max > '" . $userdata['user_posts'] . "'))
			AND ug.group_id = g.group_id";
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not obtain user and group information', '', __LINE__, __FILE__, $sql);
	}

	if ($row = $db->sql_fetchrow($result))
	{
		$is_autogroup_enable = (($row['group_count'] <= $userdata['user_posts']) && ($row['group_count_max'] > $userdata['user_posts'])) ? true : false;
		$group_rank = $row['group_rank'];
		$group_color = $row['group_color'];
		if (($row['group_type'] == GROUP_OPEN) || $is_autogroup_enable)
		{
			do
			{
				if ($userdata['user_id'] == $row['user_id'])
				{
					$redirect_url = append_sid(FORUM_MG);
					meta_refresh(3, $redirect_url);

					$message = $lang['Already_member_group'] . '<br /><br />' . sprintf($lang['Click_return_group'], '<a href="' . append_sid('groupcp.' . PHP_EXT . '?' . POST_GROUPS_URL . '=' . $group_id) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid(FORUM_MG) . '">', '</a>');

					message_die(GENERAL_MESSAGE, $message);
				}
			}
			while ($row = $db->sql_fetchrow($result));
		}
		else
		{
			$redirect_url = append_sid(FORUM_MG);
			meta_refresh(3, $redirect_url);

			$message = $lang['This_closed_group'] . '<br /><br />' . sprintf($lang['Click_return_group'], '<a href="' . append_sid('groupcp.' . PHP_EXT . '?' . POST_GROUPS_URL . '=' . $group_id) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid(FORUM_MG) . '">', '</a>');

			message_die(GENERAL_MESSAGE, $message);
		}
	}
	else
	{
		message_die(GENERAL_MESSAGE, $lang['No_groups_exist']);
	}

	$sql = "INSERT INTO " . USER_GROUP_TABLE . " (group_id, user_id, user_pending)
		VALUES ($group_id, " . $userdata['user_id'] . ",'" . (($is_autogroup_enable) ? 0 : 1) . "')";
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, "Error inserting user group subscription", "", __LINE__, __FILE__, $sql);
	}

	if ($is_autogroup_enable)
	{
		update_user_color($user_id, $group_data['group_color'], $userdata['user_color_group']);
	}

	if (($userdata['user_rank'] == '0') && ($group_rank != '0') && $is_autogroup_enable)
	{
		$sql_users = "UPDATE " . USERS_TABLE . "
			SET user_rank = '" . $group_rank . "'
			WHERE user_id = '" . $userdata['user_id'] . "'";
		if (!$db->sql_query($sql_users))
		{
			message_die(GENERAL_ERROR, 'Could not update users in groups', '', __LINE__, __FILE__, $sql);
		}
	}

	$db->clear_cache();

	$sql = "SELECT u.user_email, u.username, u.user_lang, g.group_name
		FROM " . USERS_TABLE . " u, " . GROUPS_TABLE . " g
		WHERE u.user_id = g.group_moderator
			AND g.group_id = '" . $group_id . "'";
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, "Error getting group moderator data", "", __LINE__, __FILE__, $sql);
	}

	$moderator = $db->sql_fetchrow($result);

	if (!$is_autogroup_enable)
	{
		include(IP_ROOT_PATH . 'includes/emailer.' . PHP_EXT);
		$emailer = new emailer($board_config['smtp_delivery']);

		$emailer->from($board_config['board_email']);
		$emailer->replyto($board_config['board_email']);

		$emailer->use_template('group_request', $moderator['user_lang']);
		$emailer->email_address($moderator['user_email']);
		$emailer->set_subject($lang['Group_request']);

		$emailer->assign_vars(array(
			'SITENAME' => $board_config['sitename'],
			'GROUP_MODERATOR' => $moderator['username'],
			'EMAIL_SIG' => (!empty($board_config['board_email_sig'])) ? str_replace('<br />', "\n", "-- \n" . $board_config['board_email_sig']) : '',
			'U_GROUPCP' => $server_url . '?' . POST_GROUPS_URL . '=' . $group_id . '&validate=true'
			)
		);
		$emailer->send();
		$emailer->reset();

		$redirect_url = append_sid(FORUM_MG);
		meta_refresh(3, $redirect_url);
	}

	$message = ($is_autogroup_enable) ? $lang['Group_added'] : $lang['Group_joined'] . '<br /><br />' . sprintf($lang['Click_return_group'], '<a href="' . append_sid('groupcp.' . PHP_EXT . '?' . POST_GROUPS_URL . '=' . $group_id) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid(FORUM_MG) . '">', '</a>');

	message_die(GENERAL_MESSAGE, $message);
}
elseif (isset($_POST['unsub']) || isset($_POST['unsubpending']) && $group_id)
{
	$sql = "SELECT g.group_rank, g.group_color
		FROM " . GROUPS_TABLE . " g
		WHERE g.group_id = '" . $group_id . "'";
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not obtain user and group information', '', __LINE__, __FILE__, $sql);
	}

	if ($row = $db->sql_fetchrow($result))
	{
		$group_rank = $row['group_rank'];
		$group_color = $row['group_color'];
	}
	else
	{
		$group_rank = '0';
		$group_color = $board_config['active_users_color'];
	}

	// Second, unsubscribing from a group
	// Check for confirmation of unsub.
	if ($cancel)
	{
		redirect(append_sid('groupcp.' . PHP_EXT, true));
	}
	elseif (!$userdata['session_logged_in'])
	{
		redirect(append_sid(LOGIN_MG . '?redirect=groupcp.' . PHP_EXT . '&' . POST_GROUPS_URL . '=' . $group_id, true));
	}
	elseif ($sid !== $userdata['session_id'])
	{
		message_die(GENERAL_ERROR, $lang['Session_invalid']);
	}

	if ($confirm)
	{
		$sql = "DELETE FROM " . USER_GROUP_TABLE . "
			WHERE user_id = " . $userdata['user_id'] . "
				AND group_id = '" . $group_id . "'";
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not delete group memebership data', '', __LINE__, __FILE__, $sql);
		}

		clear_user_color($userdata['user_id'], $group_color, $group_id);

		if (($userdata['user_level'] != ADMIN) && ($userdata['user_level'] == MOD))
		{
			$sql = "SELECT COUNT(auth_mod) AS is_auth_mod
				FROM " . AUTH_ACCESS_TABLE . " aa, " . USER_GROUP_TABLE . " ug
				WHERE ug.user_id = " . $userdata['user_id'] . "
					AND aa.group_id = ug.group_id
					AND aa.auth_mod = 1";
			if (!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'Could not obtain moderator status', '', __LINE__, __FILE__, $sql);
			}

			if (!($row = $db->sql_fetchrow($result)) || ($row['is_auth_mod'] == 0))
			{
				$sql = "UPDATE " . USERS_TABLE . "
					SET user_level = " . USER . "
					WHERE user_id = " . $userdata['user_id'];
				if (!($result = $db->sql_query($sql)))
				{
					message_die(GENERAL_ERROR, 'Could not update user level', '', __LINE__, __FILE__, $sql);
				}
			}
		}

		$redirect_url = append_sid(FORUM_MG);
		meta_refresh(3, $redirect_url);

		$message = $lang['Unsub_success'] . '<br /><br />' . sprintf($lang['Click_return_group'], '<a href="' . append_sid('groupcp.' . PHP_EXT . '?' . POST_GROUPS_URL . '=' . $group_id) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid(FORUM_MG) . '">', '</a>');

		$db->clear_cache();

		message_die(GENERAL_MESSAGE, $message);
	}
	else
	{
		$unsub_msg = (isset($_POST['unsub'])) ? $lang['Confirm_unsub'] : $lang['Confirm_unsub_pending'];

		$s_hidden_fields = '<input type="hidden" name="' . POST_GROUPS_URL . '" value="' . $group_id . '" /><input type="hidden" name="unsub" value="1" />';
		$s_hidden_fields .= '<input type="hidden" name="sid" value="' . $userdata['session_id'] . '" />';

		$page_title = $lang['Group_Control_Panel'];
		$meta_description = '';
		$meta_keywords = '';
		include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);

		$template->set_filenames(array('confirm' => 'confirm_body.tpl'));

		$template->assign_vars(array(
			'MESSAGE_TITLE' => $lang['Confirm'],
			'MESSAGE_TEXT' => $unsub_msg,
			'L_YES' => $lang['Yes'],
			'L_NO' => $lang['No'],
			'S_CONFIRM_ACTION' => append_sid('groupcp.' . PHP_EXT),
			'S_HIDDEN_FIELDS' => $s_hidden_fields
			)
		);

		$template->pparse('confirm');

		include(IP_ROOT_PATH . 'includes/page_tail.' . PHP_EXT);
	}
}
elseif ($group_id)
{
	// Did the group moderator get here through an email?
	// If so, check to see if they are logged in.
	if (isset($_GET['validate']))
	{
		if (!$userdata['session_logged_in'])
		{
			redirect(append_sid(LOGIN_MG . '?redirect=groupcp.' . PHP_EXT . '&' . POST_GROUPS_URL . '=' . $group_id, true));
		}
	}

	// For security, get the ID of the group moderator.
	$sql = "SELECT g.group_moderator, g.group_type, g.group_rank, g.group_color, aa.auth_mod
		FROM (" . GROUPS_TABLE . " g
		LEFT JOIN " . AUTH_ACCESS_TABLE . " aa ON aa.group_id = g.group_id)
		WHERE g.group_id = '" . $group_id . "'
		ORDER BY auth_mod DESC";
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not get moderator information', '', __LINE__, __FILE__, $sql);
	}

	if ($group_info = $db->sql_fetchrow($result))
	{
		$group_moderator = $group_info['group_moderator'];
		$group_rank = $group_info['group_rank'];
		$group_color = $group_info['group_color'];

		if (($group_moderator == $userdata['user_id']) || ($userdata['user_level'] == ADMIN) || $is_autogroup_enable)
		{
			$is_moderator = true;
		}

		// Handle Additions, removals, approvals and denials
		if (!empty($_POST['add']) || !empty($_POST['remove']) || isset($_POST['approve']) || isset($_POST['deny']) || isset($_POST['mass_colorize']))
		{
			if (!$userdata['session_logged_in'])
			{
				redirect(append_sid(LOGIN_MG . '?redirect=groupcp.' . PHP_EXT . '&' . POST_GROUPS_URL . '=' . $group_id, true));
			}
			elseif ($sid !== $userdata['session_id'])
			{
				message_die(GENERAL_ERROR, $lang['Session_invalid']);
			}

			if (!$is_moderator)
			{
				$redirect_url = append_sid(FORUM_MG);
				meta_refresh(3, $redirect_url);

				$message = $lang['Not_group_moderator'] . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid(FORUM_MG) . '">', '</a>');

				message_die(GENERAL_MESSAGE, $message);
			}

			if (isset($_POST['add']))
			{
				$username = (isset($_POST['username'])) ? phpbb_clean_username($_POST['username']) : '';

				$sql = "SELECT user_id, user_email, user_lang, user_level
					FROM " . USERS_TABLE . "
					WHERE username = '" . str_replace("\'", "''", $username) . "'";
				if (!($result = $db->sql_query($sql)))
				{
					message_die(GENERAL_ERROR, "Could not get user information", $lang['Error'], __LINE__, __FILE__, $sql);
				}

				if (!($row = $db->sql_fetchrow($result)))
				{
					$redirect_url = append_sid('groupcp.' . PHP_EXT . '?' . POST_GROUPS_URL . '=' . $group_id);
					meta_refresh(3, $redirect_url);

					$message = $lang['Could_not_add_user'] . '<br /><br />' . sprintf($lang['Click_return_group'], '<a href="' . append_sid('groupcp.' . PHP_EXT . '?' . POST_GROUPS_URL . '=' . $group_id) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid(FORUM_MG) . '">', '</a>');

					message_die(GENERAL_MESSAGE, $message);
				}

				$row['user_level'] = ($row['user_level'] == JUNIOR_ADMIN) ? ADMIN : $row['user_level'];

				if ($row['user_id'] == ANONYMOUS)
				{
					$redirect_url = append_sid('groupcp.' . PHP_EXT . '?' . POST_GROUPS_URL . '=' . $group_id);
					meta_refresh(3, $redirect_url);

					$message = $lang['Could_not_anon_user'] . '<br /><br />' . sprintf($lang['Click_return_group'], '<a href="' . append_sid('groupcp.' . PHP_EXT . '?' . POST_GROUPS_URL . '=' . $group_id) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid(FORUM_MG) . '">', '</a>');

					message_die(GENERAL_MESSAGE, $message);
				}

				$sql = "SELECT ug.user_id, u.user_level, u.user_color_group, u.user_color, u.user_rank
					FROM " . USER_GROUP_TABLE . " ug, " . USERS_TABLE . " u
					WHERE u.user_id = " . $row['user_id'] . "
						AND ug.user_id = u.user_id
						AND ug.group_id = $group_id";
				if (!($result = $db->sql_query($sql)))
				{
					message_die(GENERAL_ERROR, 'Could not get user information', '', __LINE__, __FILE__, $sql);
				}

				if (!($db->sql_fetchrow($result)))
				{
					$sql = "INSERT INTO " . USER_GROUP_TABLE . " (user_id, group_id, user_pending)
						VALUES (" . $row['user_id'] . ", $group_id, 0)";
					if (!$db->sql_query($sql))
					{
						message_die(GENERAL_ERROR, 'Could not add user to group', '', __LINE__, __FILE__, $sql);
					}

					update_user_color($row['user_id'], $group_color, $group_id);

					if (($row['user_rank'] == '0') && ($group_rank != '0'))
					{
						$sql_users = "UPDATE " . USERS_TABLE . "
							SET user_rank = '" . $group_rank . "'
							WHERE user_id = '" . $row['user_id'] . "'";
						if (!$db->sql_query($sql_users))
						{
							message_die(GENERAL_ERROR, 'Could not update users in groups', '', __LINE__, __FILE__, $sql);
						}
					}

					if (($row['user_level'] != ADMIN) && ($row['user_level'] != MOD) && $group_info['auth_mod'])
					{
						$sql = "UPDATE " . USERS_TABLE . "
							SET user_level = " . MOD . "
							WHERE user_id = " . $row['user_id'];
						if (!$db->sql_query($sql))
						{
							message_die(GENERAL_ERROR, 'Could not update user level', '', __LINE__, __FILE__, $sql);
						}
					}

					$db->clear_cache();

					// Get the group name
					//
					$group_sql = "SELECT group_name
						FROM " . GROUPS_TABLE . "
						WHERE group_id = $group_id";
					if (!($result = $db->sql_query($group_sql)))
					{
						message_die(GENERAL_ERROR, 'Could not get group information', '', __LINE__, __FILE__, $group_sql);
					}

					$group_name_row = $db->sql_fetchrow($result);

					$group_name = $group_name_row['group_name'];

					include(IP_ROOT_PATH . 'includes/emailer.' . PHP_EXT);
					$emailer = new emailer($board_config['smtp_delivery']);

					$emailer->from($board_config['board_email']);
					$emailer->replyto($board_config['board_email']);

					$emailer->use_template('group_added', $row['user_lang']);
					$emailer->email_address($row['user_email']);
					$emailer->set_subject($lang['Group_added']);

					$emailer->assign_vars(array(
						'SITENAME' => $board_config['sitename'],
						'GROUP_NAME' => $group_name,
						'EMAIL_SIG' => (!empty($board_config['board_email_sig'])) ? str_replace('<br />', "\n", "-- \n" . $board_config['board_email_sig']) : '',

						'U_GROUPCP' => $server_url . '?' . POST_GROUPS_URL . '=' . $group_id
						)
					);
					$emailer->send();
					$emailer->reset();
				}
				else
				{
					$redirect_url = append_sid('groupcp.' . PHP_EXT . '?' . POST_GROUPS_URL . '=' . $group_id);
					meta_refresh(3, $redirect_url);

					$message = $lang['User_is_member_group'] . '<br /><br />' . sprintf($lang['Click_return_group'], '<a href="' . append_sid('groupcp.' . PHP_EXT . '?' . POST_GROUPS_URL . '=' . $group_id) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid(FORUM_MG) . '">', '</a>');

					message_die(GENERAL_MESSAGE, $message);
				}
			}
			else
			{
				if (((isset($_POST['approve']) || isset($_POST['deny'])) && isset($_POST['pending_members'])) || (isset($_POST['remove']) && isset($_POST['members'])) || (isset($_POST['mass_colorize']) && isset($_POST['members'])))
				{

					$members = (isset($_POST['approve']) || isset($_POST['deny'])) ? $_POST['pending_members'] : $_POST['members'];

					$sql_in = '';
					for($i = 0; $i < count($members); $i++)
					{
						$sql_in .= (($sql_in != '') ? ', ' : '') . intval($members[$i]);
						clear_user_color_cache($members[$i]);
					}

					if (isset($_POST['approve']))
					{
						if ($group_info['auth_mod'])
						{
							$sql = "UPDATE " . USERS_TABLE . "
								SET user_level = " . MOD . "
								WHERE user_id IN ($sql_in)
									AND user_level NOT IN (" . MOD . ", " . JUNIOR_ADMIN . ", " . ADMIN . ")";
							if (!$db->sql_query($sql))
							{
								message_die(GENERAL_ERROR, 'Could not update user level', '', __LINE__, __FILE__, $sql);
							}
						}

						$sql_users = "UPDATE " . USERS_TABLE . "
							SET user_color_group = '" . $group_id . "'
							WHERE user_id IN ($sql_in)
								AND user_color_group = '0'";
						if (!$db->sql_query($sql_users))
						{
							message_die(GENERAL_ERROR, 'Could not update users in groups', '', __LINE__, __FILE__, $sql);
						}

						$sql_users = "UPDATE " . USERS_TABLE . "
							SET user_color = '" . $group_color . "'
							WHERE user_id IN ($sql_in)
								AND (user_color = '' OR user_color = '" . $board_config['active_users_color'] . "')";
						if (!$db->sql_query($sql_users))
						{
							message_die(GENERAL_ERROR, 'Could not update users in groups', '', __LINE__, __FILE__, $sql);
						}

						$sql_users = "UPDATE " . USERS_TABLE . "
							SET user_rank = '" . $group_rank . "'
							WHERE user_id IN ($sql_in)
								AND user_rank = '0'";
						if (!$db->sql_query($sql_users))
						{
							message_die(GENERAL_ERROR, 'Could not update users in groups', '', __LINE__, __FILE__, $sql);
						}

						$sql = "UPDATE " . USER_GROUP_TABLE . "
							SET user_pending = 0
							WHERE user_id IN ($sql_in)
								AND group_id = $group_id";
						$sql_select = "SELECT user_email
							FROM ". USERS_TABLE . "
							WHERE user_id IN ($sql_in)";
					}
					elseif (isset($_POST['mass_colorize']))
					{
						$sql_users = "UPDATE " . USERS_TABLE . "
							SET user_color_group = '" . $group_id . "', user_color = '" . $group_color . "', user_rank = '" . $group_rank . "'
							WHERE user_id IN ($sql_in)";
						if (!$db->sql_query($sql_users))
						{
							message_die(GENERAL_ERROR, 'Could not update users in groups', '', __LINE__, __FILE__, $sql);
						}

						$redirect_url = append_sid('groupcp.' . PHP_EXT . '?' . POST_GROUPS_URL . '=' . $group_id);
						meta_refresh(3, $redirect_url);

						$message = $lang['Group_members_updated'] . '<br /><br />' . sprintf($lang['Click_return_group'], '<a href="' . append_sid('groupcp.' . PHP_EXT . '?' . POST_GROUPS_URL . '=' . $group_id) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid(FORUM_MG) . '">', '</a>');

						message_die(GENERAL_MESSAGE, $message);
					}
					elseif (isset($_POST['deny']) || isset($_POST['remove']))
					{
						if ($group_info['auth_mod'])
						{
							$sql = "SELECT ug.user_id, ug.group_id
								FROM " . AUTH_ACCESS_TABLE . " aa, " . USER_GROUP_TABLE . " ug
								WHERE ug.user_id IN ($sql_in)
									AND aa.group_id = ug.group_id
									AND aa.auth_mod = 1
								GROUP BY ug.user_id, ug.group_id
								ORDER BY ug.user_id, ug.group_id";
							if (!($result = $db->sql_query($sql)))
							{
								message_die(GENERAL_ERROR, 'Could not obtain moderator status', '', __LINE__, __FILE__, $sql);
							}

							if ($row = $db->sql_fetchrow($result))
							{
								$group_check = array();
								$remove_mod_sql = '';

								do
								{
									$group_check[$row['user_id']][] = $row['group_id'];
								}
								while ($row = $db->sql_fetchrow($result));

								while(list($user_id, $group_list) = @each($group_check))
								{
									if (count($group_list) == 1)
									{
										$remove_mod_sql .= (($remove_mod_sql != '') ? ', ' : '') . $user_id;
									}
								}

								if ($remove_mod_sql != '')
								{
									$sql = "UPDATE " . USERS_TABLE . "
										SET user_level = " . USER . "
										WHERE user_id IN ($remove_mod_sql)
											AND user_level NOT IN (" . JUNIOR_ADMIN . ", " . ADMIN . ")";
									if (!$db->sql_query($sql))
									{
										message_die(GENERAL_ERROR, 'Could not update user level', '', __LINE__, __FILE__, $sql);
									}
								}
							}
						}

						$sql = "DELETE FROM " . USER_GROUP_TABLE . "
							WHERE user_id IN ($sql_in)
								AND group_id = $group_id";
					}

					if (!$db->sql_query($sql))
					{
						message_die(GENERAL_ERROR, 'Could not update user group table', '', __LINE__, __FILE__, $sql);
					}

					$sql_users = "UPDATE " . USERS_TABLE . "
						SET user_color_group = '0'
						WHERE user_id IN ($sql_in)
							AND user_color_group = '" . $group_id . "'";
					if (!$db->sql_query($sql_users))
					{
						message_die(GENERAL_ERROR, 'Could not update users in groups', '', __LINE__, __FILE__, $sql);
					}

					$sql_users = "UPDATE " . USERS_TABLE . "
						SET user_color = ''
						WHERE user_id IN ($sql_in)
							AND user_color = '" . $group_color . "'";
					if (!$db->sql_query($sql_users))
					{
						message_die(GENERAL_ERROR, 'Could not update users in groups', '', __LINE__, __FILE__, $sql);
					}

					$db->clear_cache();

					// Email users when they are approved
					if (isset($_POST['approve']))
					{
						if (!($result = $db->sql_query($sql_select)))
						{
							message_die(GENERAL_ERROR, 'Could not get user email information', '', __LINE__, __FILE__, $sql);
						}

						$bcc_list = array();
						while ($row = $db->sql_fetchrow($result))
						{
							$bcc_list[] = $row['user_email'];
						}

						// Get the group name
						$group_sql = "SELECT group_name
							FROM " . GROUPS_TABLE . "
							WHERE group_id = '" . $group_id . "'";
						if (!($result = $db->sql_query($group_sql)))
						{
							message_die(GENERAL_ERROR, 'Could not get group information', '', __LINE__, __FILE__, $group_sql);
						}

						$group_name_row = $db->sql_fetchrow($result);
						$group_name = $group_name_row['group_name'];

						include(IP_ROOT_PATH . 'includes/emailer.' . PHP_EXT);
						$emailer = new emailer($board_config['smtp_delivery']);

						$emailer->from($board_config['board_email']);
						$emailer->replyto($board_config['board_email']);

						for ($i = 0; $i < count($bcc_list); $i++)
						{
							$emailer->bcc($bcc_list[$i]);
						}

						$emailer->use_template('group_approved');
						$emailer->set_subject($lang['Group_approved']);

						$emailer->assign_vars(array(
							'SITENAME' => $board_config['sitename'],
							'GROUP_NAME' => $group_name,
							'EMAIL_SIG' => (!empty($board_config['board_email_sig'])) ? str_replace('<br />', "\n", "-- \n" . $board_config['board_email_sig']) : '',

							'U_GROUPCP' => $server_url . '?' . POST_GROUPS_URL . '=' . $group_id)
						);
						$emailer->send();
						$emailer->reset();
					}
				}
			}
		}
		// END approve or deny
	}
	else
	{
		message_die(GENERAL_MESSAGE, $lang['No_groups_exist']);
	}

	// Get group details
	$sql = "SELECT *
		FROM " . GROUPS_TABLE . "
		WHERE group_id = '" . $group_id . "'
			AND group_single_user = '0'";
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Error getting group information', '', __LINE__, __FILE__, $sql);
	}

	if (!($group_info = $db->sql_fetchrow($result)))
	{
		message_die(GENERAL_MESSAGE, $lang['Group_not_exist']);
	}

	// Get group rank
	$sql_rank = "SELECT * FROM " . RANKS_TABLE . "
		WHERE rank_id = '" . $group_info['group_rank'] . "'";
	if (!($result_rank = $db->sql_query($sql_rank)))
	{
		message_die(GENERAL_ERROR, 'Could not obtain ranks data', '', __LINE__, __FILE__, $sql_rank);
	}

	if($group_rank_row = $db->sql_fetchrow($result_rank))
	{
		$group_rank_image = '<img src="' . IP_ROOT_PATH . $group_rank_row['rank_image'] . '" alt="' . $group_rank_row['rank_title'] . '" />';
	}
	else
	{
		$group_rank_image = '-';
	}

	// Get moderator details for this group
	$sql = "SELECT username, user_id, user_viewemail, user_posts, user_regdate, user_from, user_website, user_email, user_icq, user_aim, user_yim, user_msnm, user_allow_viewonline, user_session_time
		FROM " . USERS_TABLE . "
		WHERE user_id = " . $group_info['group_moderator'];
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Error getting user list for group', '', __LINE__, __FILE__, $sql);
	}

	$group_moderator = $db->sql_fetchrow($result);

	// Get user information for this group
	$sql = "SELECT u.username, u.user_id, u.user_viewemail, u.user_posts, u.user_regdate, u.user_from, u.user_website, u.user_email, u.user_icq, u.user_aim, u.user_yim, u.user_msnm, ug.user_pending, u.user_allow_viewonline, u.user_session_time
		FROM " . USERS_TABLE . " u, " . USER_GROUP_TABLE . " ug
		WHERE ug.group_id = $group_id
			AND u.user_id = ug.user_id
			AND ug.user_pending = 0
			AND ug.user_id <> " . $group_moderator['user_id'] . "
		ORDER BY u.username LIMIT $start, " . $board_config['topics_per_page'];
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Error getting user list for group', '', __LINE__, __FILE__, $sql);
	}

	$group_members = $db->sql_fetchrowset($result);
	// 1 line deleted for Faster groupcp MOD
	$db->sql_freeresult($result);

	$sql = "SELECT u.username, u.user_id, u.user_viewemail, u.user_posts, u.user_regdate, u.user_from, u.user_website, u.user_email, u.user_icq, u.user_aim, u.user_yim, u.user_msnm, u.user_allow_viewonline, u.user_session_time
		FROM " . USER_GROUP_TABLE . " ug, " . USERS_TABLE . " u
		WHERE ug.group_id = $group_id
			AND ug.user_pending = 1
			AND u.user_id = ug.user_id
		ORDER BY u.username";
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Error getting user pending information', '', __LINE__, __FILE__, $sql);
	}

	$modgroup_pending_list = $db->sql_fetchrowset($result);
	// Start replacement - Faster groupcp MOD
	$sql = "SELECT SUM(user_pending = 0) as members, SUM(user_pending = 1) as pending
		FROM " . USER_GROUP_TABLE . "
		WHERE group_id = $group_id
		AND user_id <> " . $group_moderator['user_id'];
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Error getting user count information', '', __LINE__, __FILE__, $sql);
	}
	$counting_list = $db->sql_fetchrow($result);
	$members_count = $counting_list['members'];
	$modgroup_pending_count = $counting_list['pending'];
	// End replacement - Faster groupcp MOD

	$db->sql_freeresult($result);

	$is_group_member = 0;
	if ($members_count)
	{
		for($i = 0; $i < $members_count; $i++)
		{
			if ($group_members[$i]['user_id'] == $userdata['user_id'] && $userdata['session_logged_in'])
			{
				$is_group_member = true;
			}
		}
	}

	$is_autogroup_enable = ($group_info['group_count'] <= $userdata['user_posts'] && $group_info['group_count_max'] > $userdata['user_posts']) ? true : false;

	if ($modgroup_pending_count)
	{
		for($i = 0; $i < $modgroup_pending_count; $i++)
		{
			if ($modgroup_pending_list[$i]['user_id'] == $userdata['user_id'] && $userdata['session_logged_in'])
			{
				$is_group_pending_member = true;
			}
		}
	}

	if ($userdata['user_level'] == ADMIN)
	{
		$is_moderator = true;
	}

	if ($userdata['user_id'] == $group_info['group_moderator'])
	{
		$is_moderator = true;

		$group_details = $lang['Are_group_moderator'];

		$s_hidden_fields = '<input type="hidden" name="' . POST_GROUPS_URL . '" value="' . $group_id . '" />';
	}
	elseif ($is_group_member || $is_group_pending_member)
	{
		$template->assign_block_vars('switch_unsubscribe_group_input', array());

		$group_details = ($is_group_pending_member) ? $lang['Pending_this_group'] : $lang['Member_this_group'];

		$s_hidden_fields = '<input type="hidden" name="' . POST_GROUPS_URL . '" value="' . $group_id . '" />';
	}
	elseif ($userdata['user_id'] == ANONYMOUS)
	{
		$group_details = $lang['Login_to_join'];
		$s_hidden_fields = '';
	}
	else
	{
		if ($group_info['group_type'] == GROUP_OPEN)
		{
			$template->assign_block_vars('switch_subscribe_group_input', array());

			$group_details = $lang['This_open_group'];
			$s_hidden_fields = '<input type="hidden" name="' . POST_GROUPS_URL . '" value="' . $group_id . '" />';
		}
		elseif ($group_info['group_type'] == GROUP_CLOSED)
		{
			if ($is_autogroup_enable)
			{
				$template->assign_block_vars('switch_subscribe_group_input', array());
				$group_details = sprintf ($lang['This_closed_group'],$lang['Join_auto']);
				$s_hidden_fields = '<input type="hidden" name="' . POST_GROUPS_URL . '" value="' . $group_id . '" />';
			}
			else
			{
				$group_details = sprintf ($lang['This_closed_group'],$lang['No_more']);
				$s_hidden_fields = '';
			}
		}
		elseif ($group_info['group_type'] == GROUP_HIDDEN)
		{
			if ($is_autogroup_enable)
			{
				$template->assign_block_vars('switch_subscribe_group_input', array());
				$group_details = sprintf ($lang['This_hidden_group'],$lang['Join_auto']);
				$s_hidden_fields = '<input type="hidden" name="' . POST_GROUPS_URL . '" value="' . $group_id . '" />';
			}
			else
			{
				$group_details = sprintf ($lang['This_closed_group'],$lang['No_add_allowed']);
				$s_hidden_fields = '';
			}
		}
	}

	$page_title = $lang['Group_Control_Panel'];
	$meta_description = '';
	$meta_keywords = '';
	include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);

	// Load templates
	$template->set_filenames(array(
		'info' => 'groupcp_info_body.tpl',
		'pendinginfo' => 'groupcp_pending_info.tpl'
		)
	);
	make_jumpbox(VIEWFORUM_MG);

	// Add the moderator
	$username = $group_moderator['username'];
	$user_id = $group_moderator['user_id'];

	generate_user_info($group_moderator, $board_config['default_dateformat'], $is_moderator, $from, $posts, $joined, $poster_avatar, $profile_img, $profile, $search_img, $search, $pm_img, $pm, $email_img, $email, $www_img, $www, $icq_status_img, $icq_img, $icq, $aim_img, $aim, $msn_img, $msn, $yim_img, $yim, $userdata, $online_status_img, $online_status);

	$s_hidden_fields .= '<input type="hidden" name="sid" value="' . $userdata['session_id'] . '" />';

	$group_color = check_valid_color_mg($group_info['group_color']) ? check_valid_color_mg($group_info['group_color']) : false;
	$template->assign_vars(array(
		'L_GROUP_INFORMATION' => $lang['Group_Information'],
		'L_GROUP_NAME' => $lang['Group_name'],
		'L_GROUP_DESC' => $lang['Group_description'],
		'L_GROUP_TYPE' => $lang['Group_type'],
		'L_GROUP_MEMBERSHIP' => $lang['Group_membership'],
		'L_GROUP_RANK' => $lang['Rank'],
		'L_SUBSCRIBE' => $lang['Subscribe'],
		'L_UNSUBSCRIBE' => $lang['Unsubscribe'],
		'L_JOIN_GROUP' => $lang['Join_group'],
		'L_UNSUBSCRIBE_GROUP' => $lang['Unsubscribe'],
		'L_GROUP_OPEN' => $lang['Group_open'],
		'L_GROUP_CLOSED' => $lang['Group_closed'],
		'L_GROUP_HIDDEN' => $lang['Group_hidden'],
		'L_UPDATE' => $lang['Update'],
		'L_GROUP_MODERATOR' => $lang['Group_Moderator'],
		'L_GROUP_MEMBERS' => $lang['Group_Members'],
		'L_PENDING_MEMBERS' => $lang['Pending_members'],
		'L_COUNT' => $members_count + 1,
		'L_MEMBER_COUNT' => $lang['Member_Count'],
		'L_SELECT_SORT_METHOD' => $lang['Select_sort_method'],
		'L_PM' => $lang['Private_Message'],
		'L_EMAIL' => $lang['Email'],
		'L_POSTS' => $lang['Posts'],
		'L_WEBSITE' => $lang['Website'],
		'L_FROM' => $lang['Location'],
		'L_ORDER' => $lang['Order'],
		'L_SORT' => $lang['Sort'],
		'L_SUBMIT' => $lang['Sort'],
		'L_AIM' => $lang['AIM'],
		'L_YIM' => $lang['YIM'],
		'L_MSNM' => $lang['MSNM'],
		'L_ICQ' => $lang['ICQ'],
		'L_SELECT' => $lang['Select'],
		'L_REMOVE_SELECTED' => $lang['Remove_selected'],
		'L_ADD_MEMBER' => $lang['Add_member'],
		'L_FIND_USERNAME' => $lang['Find_username'],
		'L_COLORIZE_ALL' => $lang['Colorize_All'],
		'L_COLORIZE_SELECTED' => $lang['Colorize_Selected'],

		'GROUP_NAME' => $group_info['group_name'],
		'GROUP_DESC' => $group_info['group_description'],
		'GROUP_DETAILS' => $group_details,
		'GROUP_RANK' => $group_rank_image,
		'GROUP_COLOR_STYLE' => ($group_color ? ' style="color:' . $group_color . ';font-weight:bold;"' : ' style="font-weight:bold;"'),
		'MOD_ROW_COLOR' => '#' . $theme['td_color1'],
		'MOD_ROW_CLASS' => $theme['td_class1'],
		'MOD_USERNAME' => colorize_username($user_id),
		'MOD_FROM' => $from,
		'MOD_JOINED' => $joined,
		'MOD_POSTS' => $posts,
		'MOD_AVATAR_IMG' => $poster_avatar,
		'MOD_PROFILE_IMG' => $profile_img,
		'MOD_PROFILE' => $profile,
		'MOD_SEARCH_IMG' => $search_img,
		'MOD_SEARCH' => $search,
		'MOD_PM_IMG' => $pm_img,
		'MOD_PM' => $pm,
		'MOD_EMAIL_IMG' => $email_img,
		'MOD_EMAIL' => $email,
		'MOD_WWW_IMG' => $www_img,
		'MOD_WWW' => $www,
		'MOD_ICQ_STATUS_IMG' => $icq_status_img,
		'MOD_ICQ_IMG' => $icq_img,
		'MOD_ICQ' => $icq,
		'MOD_AIM_IMG' => $aim_img,
		'MOD_AIM' => $aim,
		'MOD_MSN_IMG' => $msn_img,
		'MOD_MSN' => $msn,
		'MOD_YIM_IMG' => $yim_img,
		'MOD_YIM' => $yim,
		// Start add - Online/Offline/Hidden Mod
		'MOD_ONLINE_STATUS_IMG' => $online_status_img,
		'MOD_ONLINE_STATUS' => $online_status,
		'L_ONLINE_STATUS' => $lang['Online_status'],
		// End add - Online/Offline/Hidden Mod

		'U_MOD_VIEWPROFILE' => append_sid(PROFILE_MG . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $user_id),
		'U_SEARCH_USER' => append_sid(SEARCH_MG . '?mode=searchuser'),

		'S_GROUP_OPEN_TYPE' => GROUP_OPEN,
		'S_GROUP_CLOSED_TYPE' => GROUP_CLOSED,
		'S_GROUP_HIDDEN_TYPE' => GROUP_HIDDEN,
		'S_GROUP_OPEN_CHECKED' => ($group_info['group_type'] == GROUP_OPEN) ? ' checked="checked"' : '',
		'S_GROUP_CLOSED_CHECKED' => ($group_info['group_type'] == GROUP_CLOSED) ? ' checked="checked"' : '',
		'S_GROUP_HIDDEN_CHECKED' => ($group_info['group_type'] == GROUP_HIDDEN) ? ' checked="checked"' : '',
		'S_HIDDEN_FIELDS' => $s_hidden_fields,
		'S_MODE_SELECT' => $select_sort_mode,
		'S_ORDER_SELECT' => $select_sort_order,
		'S_GROUPCP_ACTION' => append_sid('groupcp.' . PHP_EXT . '?' . POST_GROUPS_URL . '=' . $group_id)
		)
	);

	// Dump out the remaining users
	// Start replacement - Faster groupcp MOD
	$i = -1;
	while(!empty($group_members[++$i]['username']))
	// End replacement - Faster groupcp MOD
	{
		$username = $group_members[$i]['username'];
		$user_id = $group_members[$i]['user_id'];

		generate_user_info($group_members[$i], $board_config['default_dateformat'], $is_moderator, $from, $posts, $joined, $poster_avatar, $profile_img, $profile, $search_img, $search, $pm_img, $pm, $email_img, $email, $www_img, $www, $icq_status_img, $icq_img, $icq, $aim_img, $aim, $msn_img, $msn, $yim_img, $yim, $userdata, $online_status_img, $online_status);

		if ($group_info['group_type'] != GROUP_HIDDEN || $is_group_member || $is_moderator)
		{
			$row_color = (!($i % 2)) ? $theme['td_color1'] : $theme['td_color2'];
			$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];

			$template->assign_block_vars('member_row', array(
				'ROW_COLOR' => '#' . $row_color,
				'ROW_CLASS' => $row_class,
				'USERNAME' => colorize_username($user_id),
				'FROM' => $from,
				'JOINED' => $joined,
				'POSTS' => $posts,
				'USER_ID' => $user_id,
				'AVATAR_IMG' => $poster_avatar,
				'PROFILE_IMG' => $profile_img,
				'PROFILE' => $profile,
				'SEARCH_IMG' => $search_img,
				'SEARCH' => $search,
				'PM_IMG' => $pm_img,
				'PM' => $pm,
				'EMAIL_IMG' => $email_img,
				'EMAIL' => $email,
				'WWW_IMG' => $www_img,
				'WWW' => $www,
				'ICQ_STATUS_IMG' => $icq_status_img,
				'ICQ_IMG' => $icq_img,
				'ICQ' => $icq,
				'AIM_IMG' => $aim_img,
				'AIM' => $aim,
				'MSN_IMG' => $msn_img,
				'MSN' => $msn,
				'YIM_IMG' => $yim_img,
				'YIM' => $yim,
				// Start add - Online/Offline/Hidden Mod
				'ONLINE_STATUS_IMG' => $online_status_img,
				'ONLINE_STATUS' => $online_status,
				// End add - Online/Offline/Hidden Mod

				'U_VIEWPROFILE' => append_sid(PROFILE_MG . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $user_id)
				)
			);

			if ($is_moderator)
			{
				$template->assign_block_vars('member_row.switch_mod_option', array());
			}
		}
	}

	if (!$members_count)
	{
		// No group members
		$template->assign_block_vars('switch_no_members', array());
		$template->assign_vars(array(
			'L_NO_MEMBERS' => $lang['No_group_members']
			)
		);
	}

	$current_page = (!$members_count) ? 1 : ceil($members_count / $board_config['topics_per_page']);

	$template->assign_vars(array(
		'PAGINATION' => generate_pagination('groupcp.' . PHP_EXT . '?' . POST_GROUPS_URL . '=' . $group_id, $members_count, $board_config['topics_per_page'], $start),
		'PAGE_NUMBER' => sprintf($lang['Page_of'], (floor($start / $board_config['topics_per_page']) + 1), $current_page),
		'L_GOTO_PAGE' => $lang['Goto_page']
		)
	);

	if ($group_info['group_type'] == GROUP_HIDDEN && !$is_group_member && !$is_moderator)
	{
		// No group members
		$template->assign_block_vars('switch_hidden_group', array());
		$template->assign_vars(array(
			'PAGINATION' => '',
			'PAGE_NUMBER' => '',
			'L_HIDDEN_MEMBERS' => $lang['Group_hidden_members']
			)
		);
	}

	// We've displayed the members who belong to the group, now we do that pending memebers...
	if ($is_moderator)
	{
		// Users pending in ONLY THIS GROUP (which is moderated by this user)
		if ($modgroup_pending_count)
		{
			for($i = 0; $i < $modgroup_pending_count; $i++)
			{
				$username = $modgroup_pending_list[$i]['username'];
				$user_id = $modgroup_pending_list[$i]['user_id'];

				generate_user_info($modgroup_pending_list[$i], $board_config['default_dateformat'], $is_moderator, $from, $posts, $joined, $poster_avatar, $profile_img, $profile, $search_img, $search, $pm_img, $pm, $email_img, $email, $www_img, $www, $icq_status_img, $icq_img, $icq, $aim_img, $aim, $msn_img, $msn, $yim_img, $yim, $userdata, $online_status_img, $online_status);

				$row_color = (!($i % 2)) ? $theme['td_color1'] : $theme['td_color2'];
				$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];

				$user_select = '<input type="checkbox" name="member[]" value="' . $user_id . '">';

				$template->assign_block_vars('pending_members_row', array(
					'ROW_CLASS' => $row_class,
					'ROW_COLOR' => '#' . $row_color,
					'USERNAME' => $username,
					'FROM' => $from,
					'JOINED' => $joined,
					'POSTS' => $posts,
					'USER_ID' => $user_id,
					'AVATAR_IMG' => $poster_avatar,
					'PROFILE_IMG' => $profile_img,
					'PROFILE' => $profile,
					'SEARCH_IMG' => $search_img,
					'SEARCH' => $search,
					'PM_IMG' => $pm_img,
					'PM' => $pm,
					'EMAIL_IMG' => $email_img,
					'EMAIL' => $email,
					'WWW_IMG' => $www_img,
					'WWW' => $www,
					'ICQ_STATUS_IMG' => $icq_status_img,
					'ICQ_IMG' => $icq_img,
					'ICQ' => $icq,
					'AIM_IMG' => $aim_img,
					'AIM' => $aim,
					'MSN_IMG' => $msn_img,
					'MSN' => $msn,
					'YIM_IMG' => $yim_img,
					'YIM' => $yim,
					// Start add - Online/Offline/Hidden Mod
					'ONLINE_STATUS_IMG' => $online_status_img,
					'ONLINE_STATUS' => $online_status,
					// End add - Online/Offline/Hidden Mod

					'U_VIEWPROFILE' => append_sid(PROFILE_MG . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $user_id)
					)
				);
			}

			$template->assign_block_vars('switch_pending_members', array());

			$template->assign_vars(array(
				'L_SELECT' => $lang['Select'],
				'L_APPROVE_SELECTED' => $lang['Approve_selected'],
				'L_DENY_SELECTED' => $lang['Deny_selected']
				)
			);

			$template->assign_var_from_handle('PENDING_USER_BOX', 'pendinginfo');

		}
	}

	if ($is_moderator)
	{
		$template->assign_block_vars('switch_mod_option', array());
		$template->assign_block_vars('switch_add_member', array());
	}
	if ($userdata['user_level'] == ADMIN)
	{
		$template->assign_block_vars('switch_mod_option.switch_admin', array());
	}

	$template->pparse('info');
}
else
{
	// Show the main groupcp.php screen where the user can select a group.
	// Select all group that the user is a member of or where the user has a pending membership.
	$in_group = array();

	if ($userdata['session_logged_in'])
	{
		$in_group = array();

		$sql = "SELECT g.group_id, g.group_name, g.group_description, g.group_type, g.group_color, ug.user_pending
			FROM " . GROUPS_TABLE . " g, " . USER_GROUP_TABLE . " ug
			WHERE ug.user_id = " . $userdata['user_id'] . "
				AND ug.group_id = g.group_id
				AND g.group_single_user <> " . true . "
				AND ug.user_pending = '0'
			ORDER BY g.group_name, ug.user_id";
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Error getting group information', '', __LINE__, __FILE__, $sql);
		}

		$s_member_groups_opt = '';
		if ($row = $db->sql_fetchrow($result))
		{
			$template->assign_block_vars('switch_groups_joined', array());
			do
			{
				$in_group[] = $row['group_id'];
				$s_member_groups_opt .= '<option value="' . $row['group_id'] . '">' . $row['group_name'] . '</option>';
				$group_color = check_valid_color_mg($row['group_color']) ? check_valid_color_mg($row['group_color']) : false;
				$template->assign_block_vars('switch_groups_joined.mg_row', array(
					'GROUP_ID' => $row['group_id'],
					'GROUP_NAME' => $row['group_name'],
					'GROUP_DES' => $row['group_description'],
					'GROUP_URL' => append_sid('groupcp.' . PHP_EXT . '?' . POST_GROUPS_URL . '=' . $row['group_id']),
					'GROUP_COLOR_STYLE' => ($group_color ? ' style="color:' . $group_color . ';font-weight:bold;text-decoration:none;"' : ' style="font-weight:bold;text-decoration:none;"'),
					)
				);
			}
			while($row = $db->sql_fetchrow($result));
			$s_member_groups = '<select name="' . POST_GROUPS_URL . '">' . $s_member_groups_opt . '</select>';
		}

		$sql = "SELECT g.group_id, g.group_name, g.group_description, g.group_type, g.group_color, ug.user_pending
			FROM " . GROUPS_TABLE . " g, " . USER_GROUP_TABLE . " ug
			WHERE ug.user_id = " . $userdata['user_id'] . "
				AND ug.group_id = g.group_id
				AND g.group_single_user <> " . true . "
				AND ug.user_pending = '1'
			ORDER BY g.group_name, ug.user_id";
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Error getting group information', '', __LINE__, __FILE__, $sql);
		}

		$s_pending_groups_opt = '';
		if ($row = $db->sql_fetchrow($result))
		{
			$template->assign_block_vars('switch_groups_pending', array());
			do
			{
				$in_group[] = $row['group_id'];
				$s_pending_groups_opt .= '<option value="' . $row['group_id'] . '">' . $row['group_name'] . '</option>';
				$group_color = check_valid_color_mg($row['group_color']) ? check_valid_color_mg($row['group_color']) : false;
				$template->assign_block_vars('switch_groups_pending.pg_row', array(
					'GROUP_ID' => $row['group_id'],
					'GROUP_NAME' => $row['group_name'],
					'GROUP_DES' => $row['group_description'],
					'GROUP_URL' => append_sid('groupcp.' . PHP_EXT . '?' . POST_GROUPS_URL . '=' . $row['group_id']),
					'GROUP_COLOR_STYLE' => ($group_color ? ' style="color:' . $group_color . ';font-weight:bold;text-decoration:none;"' : ' style="font-weight:bold;text-decoration:none;"'),
					)
				);
			}
			while($row = $db->sql_fetchrow($result));
			$s_pending_groups = '<select name="' . POST_GROUPS_URL . '">' . $s_pending_groups_opt . '</select>';
		}
	}

	// Select all other groups i.e. groups that this user is not a member of
	$ignore_group_sql = (count($in_group)) ? "AND group_id NOT IN (" . implode(', ', $in_group) . ")" : '';
	$sql = "SELECT g.group_id, g.group_name, g.group_description, g.group_type, g.group_color, g.group_count, g.group_count_max
		FROM " . GROUPS_TABLE . " g
		WHERE group_single_user <> " . true . "
			$ignore_group_sql
		ORDER BY g.group_name";
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Error getting group information', '', __LINE__, __FILE__, $sql);
	}

	$s_group_list_opt = '';
	if ($row = $db->sql_fetchrow($result))
	{
		$template->assign_block_vars('switch_groups_remaining', array());
		do
		{
			$is_autogroup_enable = (($row['group_count'] <= $userdata['user_posts']) && ($row['group_count_max'] > $userdata['user_posts'])) ? true : false;

			if (($row['group_type'] != GROUP_HIDDEN) || ($userdata['user_level'] == ADMIN))
			{
				$s_group_list_opt .='<option value="' . $row['group_id'] . '">' . $row['group_name'] . '</option>';
				$group_color = check_valid_color_mg($row['group_color']) ? check_valid_color_mg($row['group_color']) : false;
				$template->assign_block_vars('switch_groups_remaining.ag_row', array(
					'GROUP_ID' => $row['group_id'],
					'GROUP_NAME' => $row['group_name'],
					'GROUP_DES' => $row['group_description'],
					'GROUP_URL' => append_sid('groupcp.' . PHP_EXT . '?' . POST_GROUPS_URL . '=' . $row['group_id']),
					'GROUP_COLOR_STYLE' => ($group_color ? ' style="color:' . $group_color . ';font-weight:bold;text-decoration:none;"' : ' style="font-weight:bold;text-decoration:none;"'),
					)
				);
			}
		}
		while($row = $db->sql_fetchrow($result));
		$s_group_list = '<select name="' . POST_GROUPS_URL . '">' . $s_group_list_opt . '</select>';
	}

	if ($s_group_list_opt != '' || $s_pending_groups_opt != '' || $s_member_groups_opt != '')
	{
		// Load and process templates
		$page_title = $lang['Group_Control_Panel'];
		$meta_description = '';
		$meta_keywords = '';
		include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);

		$template->set_filenames(array('user' => 'groupcp_user_body.tpl'));
		make_jumpbox(VIEWFORUM_MG);

		/*
		if ($s_pending_groups_opt != '' || $s_member_groups_opt != '')
		{
			$template->assign_block_vars('switch_groups_joined', array());
		}

		if ($s_member_groups_opt != '')
		{
			$template->assign_block_vars('switch_groups_joined.switch_groups_member', array());
		}

		if ($s_pending_groups_opt != '')
		{
			$template->assign_block_vars('switch_groups_joined.switch_groups_pending', array());
		}

		if ($s_group_list_opt != '')
		{
			$template->assign_block_vars('switch_groups_remaining', array());
		}
		*/

		$s_hidden_fields = '<input type="hidden" name="sid" value="' . $userdata['session_id'] . '" />';

		$template->assign_vars(array(
			'L_GROUP_MEMBERSHIP_DETAILS' => $lang['Group_member_details'],
			'L_JOIN_A_GROUP' => ($userdata['session_logged_in'] ? $lang['Group_member_join'] : $lang['Usergroups']),
			'L_YOU_BELONG_GROUPS' => $lang['Current_memberships'],
			'L_SELECT_A_GROUP' => $lang['Non_member_groups'],
			'L_PENDING_GROUPS' => $lang['Memberships_pending'],
			'L_SUBSCRIBE' => $lang['Subscribe'],
			'L_UNSUBSCRIBE' => $lang['Unsubscribe'],
			'L_VIEW_INFORMATION' => $lang['View_Information'],

			'S_USERGROUP_ACTION' => append_sid('groupcp.' . PHP_EXT),
			'S_HIDDEN_FIELDS' => $s_hidden_fields,

			'GROUP_LIST_SELECT' => $s_group_list,
			'GROUP_PENDING_SELECT' => $s_pending_groups,
			'GROUP_MEMBER_SELECT' => $s_member_groups)
		);

		$template->pparse('user');
	}
	else
	{
		message_die(GENERAL_MESSAGE, $lang['No_groups_exist']);
	}

}

include(IP_ROOT_PATH . 'includes/page_tail.' . PHP_EXT);

function generate_user_info(&$row, $date_format, $group_mod, &$from, &$posts, &$joined, &$poster_avatar, &$profile_img, &$profile, &$search_img, &$search, &$pm_img, &$pm, &$email_img, &$email, &$www_img, &$www, &$icq_status_img, &$icq_img, &$icq, &$aim_img, &$aim, &$msn_img, &$msn, &$yim_img, &$yim, &$userdata, &$online_status_img, &$online_status)
{
	global $lang, $images, $board_config;

	$from = (!empty($row['user_from'])) ? $row['user_from'] : '&nbsp;';
	$joined = create_date($date_format, $row['user_regdate'], $board_config['board_timezone']);
	$posts = ($row['user_posts']) ? $row['user_posts'] : 0;

	$poster_avatar = user_get_avatar($row['user_id'], $row['user_avatar'], $row['user_avatar_type'], $row['user_allowavatar']);

	if (empty($userdata['user_id']) || ($userdata['user_id'] == ANONYMOUS))
	{
		if (!empty($row['user_viewemail']))
		{
			$email_img = '<img src="' . $images['icon_email'] . '" alt="' . $lang['Hidden_email'] . '" title="' . $lang['Hidden_email'] . '" />';
		}
		else
		{
			$email_img = '&nbsp;';
		}
		$email = '&nbsp;';
	}
	else if (!empty($row['user_viewemail']) || $group_mod)
	{
		$email_uri = ($board_config['board_email_form']) ? append_sid(PROFILE_MG . '?mode=email&amp;' . POST_USERS_URL .'=' . $row['user_id']) : 'mailto:' . $row['user_email'];

		$email_img = '<a href="' . $email_uri . '"><img src="' . $images['icon_email'] . '" alt="' . $lang['Send_email'] . '" title="' . $lang['Send_email'] . '" /></a>';
		$email = '<a href="' . $email_uri . '">' . $lang['Send_email'] . '</a>';
	}
	else
	{
		$email_img = '&nbsp;';
		$email = '&nbsp;';
	}

	$temp_url = append_sid(PROFILE_MG . '?mode=viewprofile&amp;' . POST_USERS_URL . "=" . $row['user_id']);
	$profile_img = '<a href="' . $temp_url . '"><img src="' . $images['icon_profile'] . '" alt="' . $lang['Read_profile'] . '" title="' . $lang['Read_profile'] . '" /></a>';
	$profile = '<a href="' . $temp_url . '">' . $lang['Read_profile'] . '</a>';

	$temp_url = append_sid('privmsg.' . PHP_EXT . '?mode=post&amp;' . POST_USERS_URL . "=" . $row['user_id']);
	$pm_img = '<a href="' . $temp_url . '"><img src="' . $images['icon_pm'] . '" alt="' . $lang['Send_private_message'] . '" title="' . $lang['Send_private_message'] . '" /></a>';
	$pm = '<a href="' . $temp_url . '">' . $lang['Send_private_message'] . '</a>';

	$www_img = ($row['user_website']) ? '<a href="' . $row['user_website'] . '" target="_blank"><img src="' . $images['icon_www'] . '" alt="' . $lang['Visit_website'] . '" title="' . $lang['Visit_website'] . '" /></a>' : '';
	$www = ($row['user_website']) ? '<a href="' . $row['user_website'] . '" target="_blank">' . $lang['Visit_website'] . '</a>' : '';

	$icq_status_img = (!empty($row['user_icq'])) ? '<a href="http://wwp.icq.com/' . $row['user_icq'] . '#pager"><img src="http://web.icq.com/whitepages/online?icq=' . $row['user_icq'] . '&img=5" width="18" height="18" /></a>' : '';
	$icq_img = (!empty($row['user_icq'])) ? build_im_link('icq', $row['user_icq'], $lang['ICQ'], $images['icon_icq2']) : '';
	$icq = (!empty($row['user_icq'])) ? build_im_link('icq', $row['user_icq'], $lang['ICQ'], false) : '';

	$aim_img = (!empty($row['user_aim'])) ? build_im_link('aim', $row['user_aim'], $lang['AIM'], $images['icon_aim2']) : '';
	$aim = (!empty($row['user_aim'])) ? build_im_link('aim', $row['user_aim'], $lang['AIM'], false) : '';

	$msn_img = (!empty($row['user_msnm'])) ? build_im_link('msn', $row['user_msnm'], $lang['MSNM'], $images['icon_msnm2']) : '';
	$msn = (!empty($row['user_msnm'])) ? build_im_link('msn', $row['user_msnm'], $lang['MSNM'], false) : '';

	$yim_img = (!empty($row['user_yim'])) ? build_im_link('yahoo', $row['user_yim'], $lang['YIM'], $images['icon_yim2']) : '';
	$yim = (!empty($row['user_yim'])) ? build_im_link('yahoo', $row['user_yim'], $lang['YIM'], false) : '';

	$skype_img = (!empty($row['user_skype'])) ? build_im_link('skype', $row['user_skype'], $lang['SKYPE'], $images['icon_skype2']) : '';
	$skype = (!empty($row['user_skype'])) ? build_im_link('skype', $row['user_skype'], $lang['SKYPE'], false) : '';

	$temp_url = append_sid(SEARCH_MG . '?search_author=' . urlencode($row['username']) . '&amp;showresults=posts');
	$search_img = '<a href="' . $temp_url . '"><img src="' . $images['icon_search'] . '" alt="' . sprintf($lang['Search_user_posts'], $row['username']) . '" title="' . sprintf($lang['Search_user_posts'], $row['username']) . '" /></a>';
	$search = '<a href="' . $temp_url . '">' . sprintf($lang['Search_user_posts'], $row['username']) . '</a>';
	if ($row['user_session_time'] >= (time() - $board_config['online_time']))
	{
		if ($row['user_allow_viewonline'])
		{
			$online_status_img = '<a href="' . append_sid('viewonline.' . PHP_EXT) . '"><img src="' . $images['icon_online2'] . '" alt="' . sprintf($lang['is_online'], $row['username']) . '" title="' . sprintf($lang['is_online'], $row['username']) . '" /></a>';
		}
		elseif (!empty($row['user_allow_viewonline']) || $group_mod || $userdata['user_id'] == $row['user_id'])
		{
			$online_status_img = '<a href="' . append_sid('viewonline.' . PHP_EXT) . '"><img src="' . $images['icon_hidden2'] . '" alt="' . sprintf($lang['is_hidden'], $row['username']) . '" title="' . sprintf($lang['is_hidden'], $row['username']) . '" /></a>';
		}
		else
		{
			$online_status_img = '<img src="' . $images['icon_offline2'] . '" alt="' . sprintf($lang['is_offline'], $row['username']) . '" title="' . sprintf($lang['is_offline'], $row['username']) . '" />';
		}
	}
	else
	{
		$online_status_img = '<img src="' . $images['icon_offline2'] . '" alt="' . sprintf($lang['is_offline'], $row['username']) . '" title="' . sprintf($lang['is_offline'], $row['username']) . '" />';
	}

	return;
}

?>