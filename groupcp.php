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
include_once(IP_ROOT_PATH . 'includes/functions_users.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_groups.' . PHP_EXT);

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup();
// End session management

$cms_page['page_id'] = 'groupcp';
$cms_page['page_nav'] = (!empty($cms_config_layouts[$cms_page['page_id']]['page_nav']) ? true : false);
$cms_page['global_blocks'] = (!empty($cms_config_layouts[$cms_page['page_id']]['global_blocks']) ? true : false);
$cms_auth_level = (isset($cms_config_layouts[$cms_page['page_id']]['view']) ? $cms_config_layouts[$cms_page['page_id']]['view'] : AUTH_ALL);
check_page_auth($cms_page['page_id'], $cms_auth_level);

$server_url = create_server_url();
$server_url = $server_url . CMS_PAGE_GROUP_CP;

$group_id = request_var(POST_GROUPS_URL, 0);
$mode = request_var('mode', '');

$confirm = isset($_POST['confirm']) ? true : false;
$cancel = isset($_POST['cancel']) ? true : false;

$sid = request_var('sid', '');

$start = request_var('start', 0);
$start = ($start < 0) ? 0 : $start;

// Default var values
$is_moderator = false;

if (isset($_POST['groupstatus']) && $group_id)
{
	if (!$user->data['session_logged_in'])
	{
		redirect(append_sid(CMS_PAGE_LOGIN . '?redirect=groupcp.' . PHP_EXT . '&' . POST_GROUPS_URL . '=' . $group_id, true));
	}

	$query = array(
		'SELECT' => array('g.group_moderator'),
		'FROM' => array(
			GROUPS_TABLE => 'g',
		),
		'WHERE' => array(
			'g.group_id = ' . $group_id,
		),
	);

	/**
	* @event groupcp.select_moderators
	* @description Allows to edit the query to look up the group moderators.
	* @since 3.0
	* @var array query The SQL query parts.
	*/
	extract($class_plugins->trigger('groupcp.select_moderators', compact('query')));

	$sql = $db->sql_build_query('SELECT', $query);
	$result = $db->sql_query($sql);
	$group_moderators = array();
	while ($row = $db->sql_fetchrow($result))
	{
		$group_moderators[] = $row['group_moderator'];
	}

	if (!in_array($user->data['user_id'], $group_moderators) && ($user->data['user_level'] != ADMIN))
	{
		$redirect_url = append_sid(CMS_PAGE_FORUM);
		meta_refresh(3, $redirect_url);

		$message = $lang['Not_group_moderator'] . '<br /><br />' . sprintf($lang['Click_return_group'], '<a href="' . append_sid(CMS_PAGE_GROUP_CP . '?' . POST_GROUPS_URL . '=' . $group_id) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid(CMS_PAGE_FORUM) . '">', '</a>');

		message_die(GENERAL_MESSAGE, $message);
	}

	$sql = "UPDATE " . GROUPS_TABLE . "
		SET group_type = " . intval($_POST['group_type']) . "
		WHERE group_id = '" . $group_id . "'";
	$result = $db->sql_query($sql);

	$redirect_url = append_sid(CMS_PAGE_GROUP_CP . '?' . POST_GROUPS_URL . '=' . $group_id);
	meta_refresh(3, $redirect_url);

	$message = $lang['Group_type_updated'] . '<br /><br />' . sprintf($lang['Click_return_group'], '<a href="' . append_sid(CMS_PAGE_GROUP_CP . '?' . POST_GROUPS_URL . '=' . $group_id) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid(CMS_PAGE_FORUM) . '">', '</a>');

	$db->clear_cache('groups_', USERS_CACHE_FOLDER);

	message_die(GENERAL_MESSAGE, $message);
}
elseif (isset($_POST['colorize_all']) && $group_id)
{
	if (!$user->data['session_logged_in'])
	{
		redirect(append_sid(CMS_PAGE_LOGIN . '?redirect=groupcp.' . PHP_EXT . '&' . POST_GROUPS_URL . '=' . $group_id, true));
	}
	elseif ($sid !== $user->data['session_id'])
	{
		message_die(GENERAL_ERROR, $lang['Session_invalid']);
	}

	update_all_users_colors_ranks($group_id);

	$redirect_url = append_sid(CMS_PAGE_GROUP_CP . '?' . POST_GROUPS_URL . '=' . $group_id);
	meta_refresh(3, $redirect_url);

	$message = $lang['Group_members_updated'] . '<br /><br />' . sprintf($lang['Click_return_group'], '<a href="' . append_sid(CMS_PAGE_GROUP_CP . '?' . POST_GROUPS_URL . '=' . $group_id) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid(CMS_PAGE_FORUM) . '">', '</a>');

	message_die(GENERAL_MESSAGE, $message);
}
elseif (isset($_POST['joingroup']) && $group_id)
{
	// First, joining a group
	// If the user isn't logged in redirect them to login
	if (!$user->data['session_logged_in'])
	{
		redirect(append_sid(CMS_PAGE_LOGIN . '?redirect=groupcp.' . PHP_EXT . '&' . POST_GROUPS_URL . '=' . $group_id, true));
	}

	// Get group data
	$all_groups_data = get_groups_data(true, false, array($group_id));
	$this_group_data = array();
	if (!empty($all_groups_data))
	{
		foreach ($all_groups_data as $group_data)
		{
			$this_group_data = $group_data;
		}
		$is_autogroup_enable = (($this_group_data['group_count'] <= $user->data['user_posts']) && ($this_group_data['group_count_max'] > $user->data['user_posts'])) ? true : false;
		// Check if the group is open or we are "autojoining"
		if (($this_group_data['group_type'] != GROUP_OPEN) && empty($is_autogroup_enable))
		{
			$redirect_url = append_sid(CMS_PAGE_FORUM);
			meta_refresh(3, $redirect_url);

			$message = $lang['This_closed_group'] . '<br /><br />' . sprintf($lang['Click_return_group'], '<a href="' . append_sid(CMS_PAGE_GROUP_CP . '?' . POST_GROUPS_URL . '=' . $group_id) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid(CMS_PAGE_FORUM) . '">', '</a>');

			message_die(GENERAL_MESSAGE, $message);
		}
	}
	else
	{
		// The group doesn't exist!
		message_die(GENERAL_MESSAGE, $lang['No_groups_exist']);
	}

	// Check if the user is already a member
	$user_groups_data = get_groups_data_user($user->data['user_id'], false, false, array($group_id));
	if (!empty($user_groups_data))
	{
		$redirect_url = append_sid(CMS_PAGE_FORUM);
		meta_refresh(3, $redirect_url);

		$message = $lang['Already_member_group'] . '<br /><br />' . sprintf($lang['Click_return_group'], '<a href="' . append_sid(CMS_PAGE_GROUP_CP . '?' . POST_GROUPS_URL . '=' . $group_id) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid(CMS_PAGE_FORUM) . '">', '</a>');

		message_die(GENERAL_MESSAGE, $message);
	}

	$ug_add_result = group_user_add($group_id, $user->data['user_id'], false);

	$db->clear_cache();

	if (!$is_autogroup_enable)
	{
		$query = array(
			'SELECT' => array('u.user_email', 'u.username', 'u.user_lang', 'g.group_name'),
			'FROM' => array(
				USERS_TABLE => 'u',
				GROUPS_TABLE => 'g',
			),
			'WHERE' => array(
				'g.group_id = ' . $group_id,
				'u.user_id = g.group_moderator',
			),
		);

		/**
		* @event groupcp.get_moderator_users
		* @description Allows to edit the query to look up the group moderators.
		* @since 3.0
		* @var array query The SQL query parts.
		*/
		extract($class_plugins->trigger('groupcp.get_moderator_users', compact('query')));

		$sql = $db->sql_build_query('SELECT', $query);
		$result = $db->sql_query($sql);
		while ($moderator = $db->sql_fetchrow($result))
		{
			include(IP_ROOT_PATH . 'includes/emailer.' . PHP_EXT);
			$emailer = new emailer();
			$emailer->use_template('group_request', $moderator['user_lang']);
			$emailer->to($moderator['user_email']);
			$emailer->set_subject($lang['Group_request']);

			$email_sig = create_signature($config['board_email_sig']);
			$emailer->assign_vars(array(
				'SITENAME' => $config['sitename'],
				'GROUP_MODERATOR' => $moderator['username'],
				'EMAIL_SIG' => $email_sig,
				'U_GROUPCP' => $server_url . '?' . POST_GROUPS_URL . '=' . $group_id . '&validate=true'
				)
			);
			$emailer->send();
			$emailer->reset();
		}

		$redirect_url = append_sid(CMS_PAGE_FORUM);
		meta_refresh(3, $redirect_url);
	}

	$message = ($is_autogroup_enable) ? $lang['Group_added'] : $lang['Group_joined'] . '<br /><br />' . sprintf($lang['Click_return_group'], '<a href="' . append_sid(CMS_PAGE_GROUP_CP . '?' . POST_GROUPS_URL . '=' . $group_id) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid(CMS_PAGE_FORUM) . '">', '</a>');

	message_die(GENERAL_MESSAGE, $message);
}
elseif (isset($_POST['unsub']) || isset($_POST['unsubpending']) && $group_id)
{
	// Check for confirmation of unsub.
	if ($cancel)
	{
		redirect(append_sid(CMS_PAGE_GROUP_CP, true));
	}
	elseif (!$user->data['session_logged_in'])
	{
		redirect(append_sid(CMS_PAGE_LOGIN . '?redirect=groupcp.' . PHP_EXT . '&' . POST_GROUPS_URL . '=' . $group_id, true));
	}
	elseif ($sid !== $user->data['session_id'])
	{
		message_die(GENERAL_ERROR, $lang['Session_invalid']);
	}

	if ($confirm)
	{
		group_user_rem($group_id, $user->data['user_id'], false);

		/*
		update_user_color($user->data['user_id'], $config['active_users_color'], 0);
		update_user_posts_details($user->data['user_id'], '', '', false, false);
		*/

		$redirect_url = append_sid(CMS_PAGE_FORUM);
		meta_refresh(3, $redirect_url);

		$message = $lang['Unsub_success'] . '<br /><br />' . sprintf($lang['Click_return_group'], '<a href="' . append_sid(CMS_PAGE_GROUP_CP . '?' . POST_GROUPS_URL . '=' . $group_id) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid(CMS_PAGE_FORUM) . '">', '</a>');

		$db->clear_cache();

		message_die(GENERAL_MESSAGE, $message);
	}
	else
	{
		$unsub_msg = (isset($_POST['unsub'])) ? $lang['Confirm_unsub'] : $lang['Confirm_unsub_pending'];

		$s_hidden_fields = '<input type="hidden" name="' . POST_GROUPS_URL . '" value="' . $group_id . '" /><input type="hidden" name="unsub" value="1" />';
		$s_hidden_fields .= '<input type="hidden" name="sid" value="' . $user->data['session_id'] . '" />';

		$nav_server_url = create_server_url();
		$breadcrumbs['address'] = $lang['Nav_Separator'] . '<a href="' . $nav_server_url . append_sid(CMS_PAGE_GROUP_CP) . '" class="nav-current">' . $lang['Group_Control_Panel'] . '</a>';

		$template->assign_vars(array(
			'MESSAGE_TITLE' => $lang['Confirm'],
			'MESSAGE_TEXT' => $unsub_msg,
			'L_YES' => $lang['Yes'],
			'L_NO' => $lang['No'],
			'S_CONFIRM_ACTION' => append_sid(CMS_PAGE_GROUP_CP),
			'S_HIDDEN_FIELDS' => $s_hidden_fields
			)
		);
		full_page_generation('confirm_body.tpl', $lang['Group_Control_Panel'], '', '');
	}
}
elseif ($group_id)
{
	// Did the group moderator get here through an email?
	// If so, check to see if they are logged in.
	if (isset($_GET['validate']))
	{
		if (!$user->data['session_logged_in'])
		{
			redirect(append_sid(CMS_PAGE_LOGIN . '?redirect=groupcp.' . PHP_EXT . '&' . POST_GROUPS_URL . '=' . $group_id, true));
		}
	}

	// For security, get the ID of the group moderator.
	$sql = "SELECT g.group_moderator, g.group_type, g.group_rank, g.group_color, aa.auth_mod
		FROM (" . GROUPS_TABLE . " g
		LEFT JOIN " . AUTH_ACCESS_TABLE . " aa ON aa.group_id = g.group_id)
		WHERE g.group_id = '" . $group_id . "'
		ORDER BY auth_mod DESC";
	$result = $db->sql_query($sql);

	if ($group_info = $db->sql_fetchrow($result))
	{
		$group_moderator = $group_info['group_moderator'];
		$group_rank = $group_info['group_rank'];
		$group_color = $group_info['group_color'];

		$is_moderator = false;
		if (($group_moderator == $user->data['user_id']) || ($user->data['user_level'] == ADMIN) || $is_autogroup_enable)
		{
			$is_moderator = true;
		}

		/**
		* @event groupcp.is_modeator
		* @description Allows to check if a user is a group moderator.
		* @since 3.0
		* @var int user_id The user ID to check.
		* @var int group_id The group ID to check.
		* @var bool is_moderator Is the user already considered a moderator.
		*/
		extract($class_plugins->trigger('groupcp.is_moderator', array(
			'user_id' => $user->data['user_id'],
			'group_id' => $group_id,
			'is_moderator' => $is_moderator,
		)));

		// Handle Additions, removals, approvals and denials
		if (!empty($_POST['add']) || !empty($_POST['remove']) || isset($_POST['approve']) || isset($_POST['deny']) || isset($_POST['mass_colorize']))
		{
			if (!$user->data['session_logged_in'])
			{
				redirect(append_sid(CMS_PAGE_LOGIN . '?redirect=groupcp.' . PHP_EXT . '&' . POST_GROUPS_URL . '=' . $group_id, true));
			}
			elseif ($sid !== $user->data['session_id'])
			{
				message_die(GENERAL_ERROR, $lang['Session_invalid']);
			}

			if (!$is_moderator)
			{
				$redirect_url = append_sid(CMS_PAGE_FORUM);
				meta_refresh(3, $redirect_url);

				$message = $lang['Not_group_moderator'] . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid(CMS_PAGE_FORUM) . '">', '</a>');

				message_die(GENERAL_MESSAGE, $message);
			}

			if (isset($_POST['add']))
			{
				$username = (isset($_POST['username'])) ? phpbb_clean_username($_POST['username']) : '';

				$sql = get_users_sql($username, false, true, true, false);
				$result = $db->sql_query($sql);

				if (!($row = $db->sql_fetchrow($result)))
				{
					$redirect_url = append_sid(CMS_PAGE_GROUP_CP . '?' . POST_GROUPS_URL . '=' . $group_id);
					meta_refresh(3, $redirect_url);

					$message = $lang['Could_not_add_user'] . '<br /><br />' . sprintf($lang['Click_return_group'], '<a href="' . append_sid(CMS_PAGE_GROUP_CP . '?' . POST_GROUPS_URL . '=' . $group_id) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid(CMS_PAGE_FORUM) . '">', '</a>');

					message_die(GENERAL_MESSAGE, $message);
				}

				$row['user_level'] = ($row['user_level'] == JUNIOR_ADMIN) ? ADMIN : $row['user_level'];

				if ($row['user_id'] == ANONYMOUS)
				{
					$redirect_url = append_sid(CMS_PAGE_GROUP_CP . '?' . POST_GROUPS_URL . '=' . $group_id);
					meta_refresh(3, $redirect_url);

					$message = $lang['Could_not_anon_user'] . '<br /><br />' . sprintf($lang['Click_return_group'], '<a href="' . append_sid(CMS_PAGE_GROUP_CP . '?' . POST_GROUPS_URL . '=' . $group_id) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid(CMS_PAGE_FORUM) . '">', '</a>');

					message_die(GENERAL_MESSAGE, $message);
				}

				$sql = "SELECT ug.user_id, u.user_level, u.group_id, u.user_color, u.user_rank
					FROM " . USER_GROUP_TABLE . " ug, " . USERS_TABLE . " u
					WHERE u.user_id = " . $row['user_id'] . "
						AND ug.user_id = u.user_id
						AND ug.group_id = $group_id";
				$result = $db->sql_query($sql);

				if (!($db->sql_fetchrow($result)))
				{
					$sql = "INSERT INTO " . USER_GROUP_TABLE . " (user_id, group_id, user_pending)
						VALUES (" . $row['user_id'] . ", $group_id, 0)";
					$db->sql_query($sql);

					update_user_color($row['user_id'], $group_color, $group_id, false, false);
					update_user_posts_details($row['user_id'], $group_color, '', false, false);

					if (($row['user_rank'] == '0') && ($group_rank != '0'))
					{
						$sql_users = "UPDATE " . USERS_TABLE . "
							SET user_rank = '" . $group_rank . "'
							WHERE user_id = '" . $row['user_id'] . "'";
						$db->sql_query($sql_users);
					}

					if (($row['user_level'] != ADMIN) && ($row['user_level'] != MOD) && $group_info['auth_mod'])
					{
						$sql = "UPDATE " . USERS_TABLE . "
							SET user_level = " . MOD . "
							WHERE user_id = " . $row['user_id'];
						$db->sql_query($sql);
					}

					$db->clear_cache();

					// Get the group name
					$group_sql = "SELECT group_name
						FROM " . GROUPS_TABLE . "
						WHERE group_id = $group_id";
					$result = $db->sql_query($group_sql);
					$group_name_row = $db->sql_fetchrow($result);
					$db->sql_freeresult($result);

					$group_name = $group_name_row['group_name'];

					include(IP_ROOT_PATH . 'includes/emailer.' . PHP_EXT);
					$emailer = new emailer();
					$emailer->use_template('group_added', $row['user_lang']);
					$emailer->to($row['user_email']);
					$emailer->set_subject($lang['Group_added']);

					$email_sig = create_signature($config['board_email_sig']);
					$emailer->assign_vars(array(
						'SITENAME' => $config['sitename'],
						'GROUP_NAME' => $group_name,
						'EMAIL_SIG' => $email_sig,

						'U_GROUPCP' => $server_url . '?' . POST_GROUPS_URL . '=' . $group_id
						)
					);
					$emailer->send();
					$emailer->reset();
				}
				else
				{
					$redirect_url = append_sid(CMS_PAGE_GROUP_CP . '?' . POST_GROUPS_URL . '=' . $group_id);
					meta_refresh(3, $redirect_url);

					$message = $lang['User_is_member_group'] . '<br /><br />' . sprintf($lang['Click_return_group'], '<a href="' . append_sid(CMS_PAGE_GROUP_CP . '?' . POST_GROUPS_URL . '=' . $group_id) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid(CMS_PAGE_FORUM) . '">', '</a>');

					message_die(GENERAL_MESSAGE, $message);
				}
			}
			elseif (((isset($_POST['approve']) || isset($_POST['deny'])) && isset($_POST['pending_members'])) || (isset($_POST['remove']) && isset($_POST['members'])) || (isset($_POST['mass_colorize']) && isset($_POST['members'])))
			{

				$members = (isset($_POST['approve']) || isset($_POST['deny'])) ? $_POST['pending_members'] : $_POST['members'];

				$sql_in = '';
				for($i = 0; $i < sizeof($members); $i++)
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
						$db->sql_query($sql);
					}

					if (!empty($group_color) && ($group_color != $config['active_users_color']))
					{
						$sql_users = "UPDATE " . USERS_TABLE . "
							SET group_id = '" . $group_id . "'
							WHERE user_id IN ($sql_in)
								AND group_id = '0'";
						$db->sql_query($sql_users);
					}

					$sql_users = "UPDATE " . USERS_TABLE . "
						SET user_color = '" . $group_color . "'
						WHERE user_id IN ($sql_in)
							AND (user_color = '' OR user_color = '" . $config['active_users_color'] . "')";
					$db->sql_query($sql_users);

					$sql_users = "UPDATE " . USERS_TABLE . "
						SET user_rank = '" . $group_rank . "'
						WHERE user_id IN ($sql_in)
							AND user_rank = '0'";
					$db->sql_query($sql_users);

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
						SET group_id = '" . $group_id . "', user_color = '" . $group_color . "', user_rank = '" . $group_rank . "'
						WHERE user_id IN ($sql_in)";
					$db->sql_query($sql_users);

					$redirect_url = append_sid(CMS_PAGE_GROUP_CP . '?' . POST_GROUPS_URL . '=' . $group_id);
					meta_refresh(3, $redirect_url);

					$message = $lang['Group_members_updated'] . '<br /><br />' . sprintf($lang['Click_return_group'], '<a href="' . append_sid(CMS_PAGE_GROUP_CP . '?' . POST_GROUPS_URL . '=' . $group_id) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid(CMS_PAGE_FORUM) . '">', '</a>');

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
						$result = $db->sql_query($sql);

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
								if (sizeof($group_list) == 1)
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
								$db->sql_query($sql);
							}
						}
					}

					$sql = "DELETE FROM " . USER_GROUP_TABLE . "
						WHERE user_id IN ($sql_in)
							AND group_id = $group_id";
				}
				$db->sql_query($sql);

				$sql_users = "UPDATE " . USERS_TABLE . "
					SET group_id = '0'
					WHERE user_id IN ($sql_in)
						AND group_id = '" . $group_id . "'";
				$result = $db->sql_query($sql_users);

				$sql_users = "UPDATE " . USERS_TABLE . "
					SET user_color = ''
					WHERE user_id IN ($sql_in)
						AND user_color = '" . $group_color . "'";
				$db->sql_query($sql_users);
				$cache->remove_file(CACHE_TREE_FILE, false, MAIN_CACHE_FOLDER);
				$db->clear_cache(SQL_CACHE_FOLDER);
				$db->clear_cache(USERS_CACHE_FOLDER);

				// Email users when they are approved
				if (isset($_POST['approve']))
				{
					$result = $db->sql_query($sql_select);
					$bcc_list = array();
					while ($row = $db->sql_fetchrow($result))
					{
						$bcc_list[] = $row['user_email'];
					}

					// Get the group name
					$group_sql = "SELECT group_name
						FROM " . GROUPS_TABLE . "
						WHERE group_id = '" . $group_id . "'";
					$result = $db->sql_query($group_sql);
					$group_name_row = $db->sql_fetchrow($result);
					$group_name = $group_name_row['group_name'];

					include(IP_ROOT_PATH . 'includes/emailer.' . PHP_EXT);
					$emailer = new emailer();
					foreach ($bcc_list as $bcc_address)
					{
						if (!empty($bcc_address))
						{
							$emailer->bcc($bcc_address);
						}
					}
					$emailer->use_template('group_approved');
					$emailer->set_subject($lang['Group_approved']);

					$email_sig = create_signature($config['board_email_sig']);
					$emailer->assign_vars(array(
						'SITENAME' => $config['sitename'],
						'GROUP_NAME' => $group_name,
						'EMAIL_SIG' => $email_sig,

						'U_GROUPCP' => $server_url . '?' . POST_GROUPS_URL . '=' . $group_id
						)
					);
					$emailer->send();
					$emailer->reset();
				}
			}
		}
		else
		{
			/**
			* @event groupcp.additional_actions
			* @description Allows the plugins to run additional actions.
			* @since 3.0
			* @var int user_id The user ID.
			* @var int group_id The group ID.
			* @var bool is_moderator Is the user a moderator.
			*/
			$class_plugins->trigger('groupcp.additional_actions', array(
				'user_id' => $user->data['user_id'],
				'group_id' => $group_id,
				'is_moderator' => $is_moderator,
			));
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
	$result = $db->sql_query($sql);
	if (!($group_info = $db->sql_fetchrow($result)))
	{
		message_die(GENERAL_MESSAGE, $lang['Group_not_exist']);
	}
	$db->sql_freeresult($result);

	// Get group rank
	$sql_rank = "SELECT * FROM " . RANKS_TABLE . " WHERE rank_id = '" . $group_info['group_rank'] . "'";
	$result_rank = $db->sql_query($sql_rank);
	if($group_rank_row = $db->sql_fetchrow($result_rank))
	{
		$group_rank_image = $group_rank_row['rank_title'];
		if (!empty($group_rank_row['rank_image']))
		{
			$group_rank_image = '<img src="' . IP_ROOT_PATH . $group_rank_row['rank_image'] . '" alt="' . $group_rank_row['rank_title'] . '" />';
		}
	}
	$group_rank_image = !empty($group_rank_image) ? $group_rank_image : '-';
	$db->sql_freeresult($result);

	// Get moderator details for this group
	$sql = "SELECT username, user_id, user_active, user_color, group_id, user_allow_viewemail, user_posts, user_regdate, user_from, user_website, user_email, user_icq, user_aim, user_yim, user_msnm, user_allow_viewonline, user_session_time
		FROM " . USERS_TABLE . "
		WHERE user_id = " . $group_info['group_moderator'];
	$result = $db->sql_query($sql);
	$group_moderator = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);

	// Get user information for this group
	// Changed sorting by username_clean instead of username
	$sql = "SELECT u.username, u.user_id, u.user_active, u.user_color, u.group_id, u.user_allow_viewemail, u.user_posts, u.user_regdate, u.user_from, u.user_website, u.user_email, u.user_icq, u.user_aim, u.user_yim, u.user_msnm, ug.user_pending, u.user_allow_viewonline, u.user_session_time
		FROM " . USERS_TABLE . " u, " . USER_GROUP_TABLE . " ug
		WHERE ug.group_id = $group_id
			AND u.user_id = ug.user_id
			AND ug.user_pending = 0
			AND ug.user_id <> " . $group_moderator['user_id'] . "
		ORDER BY u.username_clean LIMIT $start, " . $config['topics_per_page'];
	$result = $db->sql_query($sql);
	$group_members = $db->sql_fetchrowset($result);
	$db->sql_freeresult($result);

	$sql = "SELECT u.username, u.user_id, u.user_active, u.user_color, u.group_id, u.user_allow_viewemail, u.user_posts, u.user_regdate, u.user_from, u.user_website, u.user_email, u.user_icq, u.user_aim, u.user_yim, u.user_msnm, u.user_allow_viewonline, u.user_session_time
		FROM " . USER_GROUP_TABLE . " ug, " . USERS_TABLE . " u
		WHERE ug.group_id = $group_id
			AND ug.user_pending = 1
			AND u.user_id = ug.user_id
		ORDER BY u.username_clean";
	$result = $db->sql_query($sql);
	$modgroup_pending_list = $db->sql_fetchrowset($result);
	$db->sql_freeresult($result);

	// Start replacement - Faster groupcp MOD
	$sql = "SELECT SUM(user_pending = 0) as members, SUM(user_pending = 1) as pending
		FROM " . USER_GROUP_TABLE . "
		WHERE group_id = $group_id
		AND user_id <> " . $group_moderator['user_id'];
	$result = $db->sql_query($sql);
	$counting_list = $db->sql_fetchrow($result);
	$members_count = $counting_list['members'];
	$modgroup_pending_count = $counting_list['pending'];
	$db->sql_freeresult($result);
	// End replacement - Faster groupcp MOD

	$is_group_member = 0;
	if ($members_count)
	{
		for($i = 0; $i < $members_count; $i++)
		{
			if (($group_members[$i]['user_id'] == $user->data['user_id']) && $user->data['session_logged_in'])
			{
				$is_group_member = true;
			}
		}
	}

	$is_autogroup_enable = ($group_info['group_count'] <= $user->data['user_posts'] && $group_info['group_count_max'] > $user->data['user_posts']) ? true : false;

	if ($modgroup_pending_count)
	{
		for($i = 0; $i < $modgroup_pending_count; $i++)
		{
			if ($modgroup_pending_list[$i]['user_id'] == $user->data['user_id'] && $user->data['session_logged_in'])
			{
				$is_group_pending_member = true;
			}
		}
	}

	if ($user->data['user_level'] == ADMIN)
	{
		$is_moderator = true;
	}

	if ($user->data['user_id'] == $group_info['group_moderator'])
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
	elseif ($user->data['user_id'] == ANONYMOUS)
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

	$meta_content['page_title'] = $lang['Group_Control_Panel'];
	$meta_content['description'] = '';
	$meta_content['keywords'] = '';
	$nav_server_url = create_server_url();
	$breadcrumbs['address'] = $lang['Nav_Separator'] . '<a href="' . $nav_server_url . append_sid(CMS_PAGE_GROUP_CP) . '">' . $lang['Group_Control_Panel'] . '</a>' . $lang['Nav_Separator'] . '<a class="nav-current" href="#">' . $group_info['group_name'] . '</a>';
	$breadcrumbs['bottom_right_links'] .= (($breadcrumbs['bottom_right_links'] != '') ? ('&nbsp;' . MENU_SEP_CHAR . '&nbsp;') : '') . '<a href="' . append_sid(CMS_PAGE_MEMBERLIST) . '">' . $lang['LINK_MEMBERLIST'] . '</a>';

	page_header($meta_content['page_title'], true);

	// Load templates
	$template->set_filenames(array(
		'info' => 'groupcp_info_body.tpl',
		'pendinginfo' => 'groupcp_pending_info.tpl'
		)
	);
	make_jumpbox(CMS_PAGE_VIEWFORUM);

	// Add the moderator
	$username = $group_moderator['username'];
	$user_id = $group_moderator['user_id'];

	$user_info = array();
	$user_info = generate_user_info($group_moderator, $config['default_dateformat'], $is_moderator);

	$s_hidden_fields .= '<input type="hidden" name="sid" value="' . $user->data['session_id'] . '" />';

	$group_color = check_valid_color($group_info['group_color']) ? check_valid_color($group_info['group_color']) : false;
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
		'L_CONTACTS' => $lang['User_Contacts'],
		'L_WEBSITE' => $lang['Website'],
		'L_FROM' => $lang['Location'],
		'L_ORDER' => $lang['Order'],
		'L_SORT' => $lang['Sort'],
		'L_SUBMIT' => $lang['Sort'],
		'L_SELECT' => $lang['Select'],
		'L_REMOVE_SELECTED' => $lang['Remove_selected'],
		'L_ADD_MEMBER' => $lang['Add_member'],
		'L_COLORIZE_ALL' => $lang['Colorize_All'],
		'L_COLORIZE_SELECTED' => $lang['Colorize_Selected'],

		'GROUP_NAME' => $group_info['group_name'],
		'GROUP_DESC' => $group_info['group_description'],
		'GROUP_DETAILS' => $group_details,
		'GROUP_RANK' => $group_rank_image,
		'GROUP_COLOR_STYLE' => ($group_color ? ' style="color:' . $group_color . ';font-weight:bold;"' : ' style="font-weight:bold;"'),
		'ROW_CLASS' => $theme['td_class1'],
		'USERNAME' => colorize_username($group_moderator['user_id'], $group_moderator['username'], $group_moderator['user_color'], $group_moderator['user_active']),
		'FROM' => $user_info['from'],
		'JOINED' => $user_info['joined'],
		'POSTS' => $user_info['posts'],
		'AVATAR_IMG' => $user_info['avatar'],
		'AGE' => $user_info['age'],
		'GENDER' => $user_info['gender'],
		'STYLE' => $user_info['style'],
		'PROFILE_IMG' => $user_info['profile_img'],
		'PROFILE_URL' => $user_info['profile_url'],
		'PROFILE' => $user_info['profile'],
		'SEARCH_IMG' => $user_info['search_img'],
		'SEARCH_URL' => $user_info['search_url'],
		'SEARCH' => $user_info['search'],
		'PM_IMG' => $user_info['pm_img'],
		'PM_URL' => $user_info['pm_url'],
		'PM' => $user_info['pm'],
		'EMAIL_IMG' => $user_info['email_img'],
		'EMAIL_URL' => $user_info['email_url'],
		'EMAIL' => $user_info['email'],
		'WWW_IMG' => $user_info['www_img'],
		'WWW_URL' => $user_info['www_url'],
		'WWW' => $user_info['www'],
		'AIM_IMG' => $user_info['aim_img'],
		'AIM_URL' => $user_info['aim_url'],
		'AIM' => $user_info['aim'],
		'ICQ_STATUS_IMG' => $user_info['icq_status_img'],
		'ICQ_IMG' => $user_info['icq_img'],
		'ICQ_URL' => $user_info['icq_url'],
		'ICQ' => $user_info['icq'],
		'MSN_IMG' => $user_info['msn_img'],
		'MSN_URL' => $user_info['msn_url'],
		'MSN' => $user_info['msn'],
		'SKYPE_IMG' => $user_info['skype_img'],
		'SKYPE_URL' => $user_info['skype_url'],
		'SKYPE' => $user_info['skype'],
		'YIM_IMG' => $user_info['yahoo_img'],
		'YIM_URL' => $user_info['yahoo_url'],
		'YIM' => $user_info['yahoo'],
		'ONLINE_STATUS_IMG' => $user_info['online_status_img'],
		'ONLINE_STATUS_CLASS' => $user_info['online_status_class'],
		'ONLINE_STATUS_URL' => $user_info['online_status_url'],
		'ONLINE_STATUS' => $user_info['online_status'],
		'L_ONLINE_STATUS' => $user_info['online_status_lang'],

		'U_VIEWPROFILE' => append_sid(CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $user_id),
		'U_SEARCH_USER' => append_sid(CMS_PAGE_SEARCH . '?mode=searchuser'),

		'S_GROUP_OPEN_TYPE' => GROUP_OPEN,
		'S_GROUP_CLOSED_TYPE' => GROUP_CLOSED,
		'S_GROUP_HIDDEN_TYPE' => GROUP_HIDDEN,
		'S_GROUP_OPEN_CHECKED' => ($group_info['group_type'] == GROUP_OPEN) ? ' checked="checked"' : '',
		'S_GROUP_CLOSED_CHECKED' => ($group_info['group_type'] == GROUP_CLOSED) ? ' checked="checked"' : '',
		'S_GROUP_HIDDEN_CHECKED' => ($group_info['group_type'] == GROUP_HIDDEN) ? ' checked="checked"' : '',
		'S_HIDDEN_FIELDS' => $s_hidden_fields,
		'S_MODE_SELECT' => $select_sort_mode,
		'S_ORDER_SELECT' => $select_sort_order,
		'S_GROUPCP_ACTION' => append_sid(CMS_PAGE_GROUP_CP . '?' . POST_GROUPS_URL . '=' . $group_id)
		)
	);

	// Dump out the remaining users
	// Start replacement - Faster groupcp MOD
	$i = -1;
	$row_class = '';
	while(!empty($group_members[++$i]['username']))
	// End replacement - Faster groupcp MOD
	{
		$username = $group_members[$i]['username'];
		$user_id = $group_members[$i]['user_id'];

		$user_info = array();
		$user_info = generate_user_info($group_members[$i], $config['default_dateformat'], $is_moderator);

		if ($group_info['group_type'] != GROUP_HIDDEN || $is_group_member || $is_moderator)
		{
			$row_class = ip_zebra_rows($row_class);
			$template->assign_block_vars('memberrow', array(
				'ROW_CLASS' => $row_class,
				'USER_ID' => $user_id,
				'USERNAME' => colorize_username($group_members[$i]['user_id'], $group_members[$i]['username'], $group_members[$i]['user_color'], $group_members[$i]['user_active']),
				'FROM' => $user_info['from'],
				'JOINED' => $user_info['joined'],
				'POSTS' => $user_info['posts'],
				'AVATAR_IMG' => $user_info['avatar'],
				'AGE' => $user_info['age'],
				'GENDER' => $user_info['gender'],
				'STYLE' => $user_info['style'],
				'PROFILE_IMG' => $user_info['profile_img'],
				'PROFILE_URL' => $user_info['profile_url'],
				'PROFILE' => $user_info['profile'],
				'SEARCH_IMG' => $user_info['search_img'],
				'SEARCH_URL' => $user_info['search_url'],
				'SEARCH' => $user_info['search'],
				'PM_IMG' => $user_info['pm_img'],
				'PM_URL' => $user_info['pm_url'],
				'PM' => $user_info['pm'],
				'EMAIL_IMG' => $user_info['email_img'],
				'EMAIL_URL' => $user_info['email_url'],
				'EMAIL' => $user_info['email'],
				'WWW_IMG' => $user_info['www_img'],
				'WWW_URL' => $user_info['www_url'],
				'WWW' => $user_info['www'],
				'AIM_IMG' => $user_info['aim_img'],
				'AIM_URL' => $user_info['aim_url'],
				'AIM' => $user_info['aim'],
				'ICQ_STATUS_IMG' => $user_info['icq_status_img'],
				'ICQ_IMG' => $user_info['icq_img'],
				'ICQ_URL' => $user_info['icq_url'],
				'ICQ' => $user_info['icq'],
				'MSN_IMG' => $user_info['msn_img'],
				'MSN_URL' => $user_info['msn_url'],
				'MSN' => $user_info['msn'],
				'SKYPE_IMG' => $user_info['skype_img'],
				'SKYPE_URL' => $user_info['skype_url'],
				'SKYPE' => $user_info['skype'],
				'YIM_IMG' => $user_info['yahoo_img'],
				'YIM_URL' => $user_info['yahoo_url'],
				'YIM' => $user_info['yahoo'],
				'ONLINE_STATUS_IMG' => $user_info['online_status_img'],
				'ONLINE_STATUS_CLASS' => $user_info['online_status_class'],
				'ONLINE_STATUS_URL' => $user_info['online_status_url'],
				'ONLINE_STATUS' => $user_info['online_status'],
				'L_ONLINE_STATUS' => $user_info['online_status_lang'],

				'U_VIEWPROFILE' => append_sid(CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $user_id)
				)
			);

			if ($is_moderator)
			{
				$template->assign_block_vars('memberrow.switch_mod_option', array());
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

	$current_page = (!$members_count) ? 1 : ceil($members_count / $config['topics_per_page']);

	$template->assign_vars(array(
		'PAGINATION' => generate_pagination(CMS_PAGE_GROUP_CP . '?' . POST_GROUPS_URL . '=' . $group_id, $members_count, $config['topics_per_page'], $start),
		'PAGE_NUMBER' => sprintf($lang['Page_of'], (floor($start / $config['topics_per_page']) + 1), $current_page),
		'L_GOTO_PAGE' => $lang['Goto_page']
		)
	);

	if ($group_info['group_type'] == GROUP_HIDDEN && !$is_group_member && !$is_moderator)
	{
		// No group members
		$template->assign_block_vars('switch_hidden_group', array());
		$template->assign_vars(array(
			'PAGINATION' => '&nbsp;',
			'PAGE_NUMBER' => '&nbsp;',
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
			$row_class = '';
			for($i = 0; $i < $modgroup_pending_count; $i++)
			{
				$username = $modgroup_pending_list[$i]['username'];
				$user_id = $modgroup_pending_list[$i]['user_id'];
				$user_info = array();
				$user_info = generate_user_info($modgroup_pending_list[$i], $config['default_dateformat'], $is_moderator);
				$row_class = ip_zebra_rows($row_class);
				$user_select = '<input type="checkbox" name="member[]" value="' . $user_id . '">';
				$template->assign_block_vars('pending_members_row', array(
					'ROW_CLASS' => $row_class,
					'USER_ID' => $user_id,
					'USERNAME' => colorize_username($modgroup_pending_list[$i]['user_id'], $modgroup_pending_list[$i]['username'], $modgroup_pending_list[$i]['user_color'], $modgroup_pending_list[$i]['user_active']),
					'FROM' => $user_info['from'],
					'JOINED' => $user_info['joined'],
					'POSTS' => $user_info['posts'],
					'AVATAR_IMG' => $user_info['avatar'],
					'AGE' => $user_info['age'],
					'GENDER' => $user_info['gender'],
					'STYLE' => $user_info['style'],
					'PROFILE_IMG' => $user_info['profile_img'],
					'PROFILE_URL' => $user_info['profile_url'],
					'PROFILE' => $user_info['profile'],
					'SEARCH_IMG' => $user_info['search_img'],
					'SEARCH_URL' => $user_info['search_url'],
					'SEARCH' => $user_info['search'],
					'PM_IMG' => $user_info['pm_img'],
					'PM_URL' => $user_info['pm_url'],
					'PM' => $user_info['pm'],
					'EMAIL_IMG' => $user_info['email_img'],
					'EMAIL_URL' => $user_info['email_url'],
					'EMAIL' => $user_info['email'],
					'WWW_IMG' => $user_info['www_img'],
					'WWW_URL' => $user_info['www_url'],
					'WWW' => $user_info['www'],
					'AIM_IMG' => $user_info['aim_img'],
					'AIM_URL' => $user_info['aim_url'],
					'AIM' => $user_info['aim'],
					'ICQ_STATUS_IMG' => $user_info['icq_status_img'],
					'ICQ_IMG' => $user_info['icq_img'],
					'ICQ_URL' => $user_info['icq_url'],
					'ICQ' => $user_info['icq'],
					'MSN_IMG' => $user_info['msn_img'],
					'MSN_URL' => $user_info['msn_url'],
					'MSN' => $user_info['msn'],
					'SKYPE_IMG' => $user_info['skype_img'],
					'SKYPE_URL' => $user_info['skype_url'],
					'SKYPE' => $user_info['skype'],
					'YIM_IMG' => $user_info['yahoo_img'],
					'YIM_URL' => $user_info['yahoo_url'],
					'YIM' => $user_info['yahoo'],
					'ONLINE_STATUS_IMG' => $user_info['online_status_img'],
					'ONLINE_STATUS_CLASS' => $user_info['online_status_class'],
					'ONLINE_STATUS_URL' => $user_info['online_status_url'],
					'ONLINE_STATUS' => $user_info['online_status'],
					'L_ONLINE_STATUS' => $user_info['online_status_lang'],

					'U_VIEWPROFILE' => append_sid(CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $user_id)
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
	if ($user->data['user_level'] == ADMIN)
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

	if ($user->data['session_logged_in'])
	{
		$in_group = array();
		$in_groups_data = array();

		$sql = "SELECT g.group_id, g.group_name, g.group_description, g.group_type, g.group_color, ug.user_pending
			FROM " . GROUPS_TABLE . " g, " . USER_GROUP_TABLE . " ug
			WHERE ug.user_id = " . $user->data['user_id'] . "
				AND ug.group_id = g.group_id
				AND g.group_single_user = '0'
			ORDER BY g.group_name, ug.user_id";
		$result = $db->sql_query($sql);
		$in_groups_data = $db->sql_fetchrowset($result);
		$db->sql_freeresult($result);

		$in_groups_joined = array();
		$in_groups_pending = array();
		foreach ($in_groups_data as $in_group_data)
		{
			if (!empty($in_group_data['user_pending']))
			{
				$in_groups_pending[] = $in_group_data;
			}
			else
			{
				$in_groups_joined[] = $in_group_data;
			}
		}

		$s_member_groups_opt = '';
		if (!empty($in_groups_joined))
		{
			$template->assign_block_vars('switch_groups_joined', array());
			foreach ($in_groups_joined as $in_group_joined)
			{
				$in_group[] = $in_group_joined['group_id'];
				$s_member_groups_opt .= '<option value="' . $in_group_joined['group_id'] . '">' . $in_group_joined['group_name'] . '</option>';
				$group_color = check_valid_color($in_group_joined['group_color']) ? check_valid_color($in_group_joined['group_color']) : false;
				$template->assign_block_vars('switch_groups_joined.mg_row', array(
					'GROUP_ID' => $in_group_joined['group_id'],
					'GROUP_NAME' => $in_group_joined['group_name'],
					'GROUP_DES' => $in_group_joined['group_description'],
					'GROUP_URL' => append_sid(CMS_PAGE_GROUP_CP . '?' . POST_GROUPS_URL . '=' . $in_group_joined['group_id']),
					'GROUP_COLOR_STYLE' => ' style="' . ($group_color ? ('color: ' . $group_color . '; ') : '') . 'font-weight: bold; text-decoration: none;"',
					)
				);
			}
			$s_member_groups = '<select name="' . POST_GROUPS_URL . '">' . $s_member_groups_opt . '</select>';
		}

		$s_pending_groups_opt = '';
		if (!empty($in_groups_pending))
		{
			$template->assign_block_vars('switch_groups_pending', array());
			foreach ($in_groups_pending as $in_group_pending)
			{
				$in_group[] = $in_group_pending['group_id'];
				$s_member_groups_opt .= '<option value="' . $in_group_pending['group_id'] . '">' . $in_group_pending['group_name'] . '</option>';
				$group_color = check_valid_color($in_group_pending['group_color']) ? check_valid_color($in_group_pending['group_color']) : false;
				$template->assign_block_vars('switch_groups_pending.pg_row', array(
					'GROUP_ID' => $in_group_pending['group_id'],
					'GROUP_NAME' => $in_group_pending['group_name'],
					'GROUP_DES' => $in_group_pending['group_description'],
					'GROUP_URL' => append_sid(CMS_PAGE_GROUP_CP . '?' . POST_GROUPS_URL . '=' . $in_group_pending['group_id']),
					'GROUP_COLOR_STYLE' => ' style="' . ($group_color ? ('color: ' . $group_color . '; ') : '') . 'font-weight: bold; text-decoration: none;"',
					)
				);
			}
			$s_pending_groups = '<select name="' . POST_GROUPS_URL . '">' . $s_pending_groups_opt . '</select>';
		}
	}

	// Select all other groups i.e. groups that this user is not a member of
	$ignore_group_sql = (sizeof($in_group)) ? "AND group_id NOT IN (" . implode(', ', $in_group) . ")" : '';
	$sql = "SELECT g.group_id, g.group_name, g.group_description, g.group_type, g.group_color, g.group_count, g.group_count_max
		FROM " . GROUPS_TABLE . " g
		WHERE group_single_user = '0'
			$ignore_group_sql
		ORDER BY g.group_name";
	$result = $db->sql_query($sql);

	$s_group_list_opt = '';
	if ($row = $db->sql_fetchrow($result))
	{
		$template->assign_block_vars('switch_groups_remaining', array());
		do
		{
			$is_autogroup_enable = (($row['group_count'] <= $user->data['user_posts']) && ($row['group_count_max'] > $user->data['user_posts'])) ? true : false;

			if (($row['group_type'] != GROUP_HIDDEN) || ($user->data['user_level'] == ADMIN))
			{
				$s_group_list_opt .='<option value="' . $row['group_id'] . '">' . $row['group_name'] . '</option>';
				$group_color = check_valid_color($row['group_color']) ? check_valid_color($row['group_color']) : false;
				$template->assign_block_vars('switch_groups_remaining.ag_row', array(
					'GROUP_ID' => $row['group_id'],
					'GROUP_NAME' => $row['group_name'],
					'GROUP_DES' => $row['group_description'],
					'GROUP_URL' => append_sid(CMS_PAGE_GROUP_CP . '?' . POST_GROUPS_URL . '=' . $row['group_id']),
					'GROUP_COLOR_STYLE' => ($group_color ? ' style="color:' . $group_color . ';font-weight:bold;text-decoration:none;"' : ' style="font-weight:bold;text-decoration:none;"'),
					)
				);
			}
		}
		while($row = $db->sql_fetchrow($result));
		$s_group_list = '<select name="' . POST_GROUPS_URL . '">' . $s_group_list_opt . '</select>';
	}

	if (($s_group_list_opt != '') || ($s_pending_groups_opt != '') || ($s_member_groups_opt != ''))
	{
		// Load and process templates
		$meta_content['page_title'] = $lang['Group_Control_Panel'];
		$meta_content['description'] = '';
		$meta_content['keywords'] = '';
		$nav_server_url = create_server_url();
		$breadcrumbs['address'] = $lang['Nav_Separator'] . '<a href="' . $nav_server_url . append_sid(CMS_PAGE_GROUP_CP) . '" class="nav-current">' . $lang['Group_Control_Panel'] . '</a>';
		$breadcrumbs['bottom_right_links'] .= (($breadcrumbs['bottom_right_links'] != '') ? ('&nbsp;' . MENU_SEP_CHAR . '&nbsp;') : '') . '<a href="' . append_sid(CMS_PAGE_MEMBERLIST) . '">' . $lang['LINK_MEMBERLIST'] . '</a>';

		page_header($meta_content['page_title'], true);

		$template->set_filenames(array('user' => 'groupcp_user_body.tpl'));
		make_jumpbox(CMS_PAGE_VIEWFORUM);

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

		$s_hidden_fields = '<input type="hidden" name="sid" value="' . $user->data['session_id'] . '" />';

		$template->assign_vars(array(
			'L_GROUP_MEMBERSHIP_DETAILS' => $lang['Group_member_details'],
			'L_JOIN_A_GROUP' => ($user->data['session_logged_in'] ? $lang['Group_member_join'] : $lang['Usergroups']),
			'L_YOU_BELONG_GROUPS' => $lang['Current_memberships'],
			'L_SELECT_A_GROUP' => $lang['Non_member_groups'],
			'L_PENDING_GROUPS' => $lang['Memberships_pending'],
			'L_SUBSCRIBE' => $lang['Subscribe'],
			'L_UNSUBSCRIBE' => $lang['Unsubscribe'],
			'L_VIEW_INFORMATION' => $lang['View_Information'],

			'S_USERGROUP_ACTION' => append_sid(CMS_PAGE_GROUP_CP),
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

page_footer(true, '', true);

?>