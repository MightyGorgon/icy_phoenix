<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

define('IN_ICYPHOENIX', true);

if(!empty($setmodules))
{
	$filename = basename(__FILE__);
	$module['1610_Users']['130_Userlist'] = $filename;
	return;
}

if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('pagestart.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/functions_users_delete.' . PHP_EXT);

$mode = request_var('mode', '');

$confirm = check_http_var_exists('confirm', false);

if(check_http_var_exists('cancel', false))
{
	$cancel = true;
	$mode = '';
}
else
{
	$cancel = false;
}

$start = request_var('start', 0);
$start = ($start < 0) ? 0 : $start;

$show = request_var('show', $config['topics_per_page']);
$show = ($show < 1) ? $config['topics_per_page'] : $show;

$sort_method = request_var('sort', 'user_regdate');

$sort_order = request_var('order', 'ASC');
$sort_order = check_var_value($sort_order, array('DESC', 'ASC'));

// alphanumeric stuff
$alphanum = request_var('alphanum', '');
if (!empty($alphanum))
{
	$alphanum = $db->sql_escape($alphanum);
	$alpha_where = ($alphanum == 'num') ? "AND username NOT RLIKE '^[A-Z]'" : "AND LOWER(username) LIKE '$alphanum%'";
}
else
{
	$alpahnum = '';
	$alpha_where = '';
}

$user_ids = request_var(POST_USERS_URL, array(0));

switch($mode)
{
	case 'delete':

		// see if cancel has been hit and redirect if it has shouldn't get to this point if it has been hit but do this just in case
		if ($cancel)
		{
			redirect(ADM . '/admin_userlist.' . PHP_EXT);
		}

		//
		// check confirm and either delete or show confirm message
		//
		if (!$confirm)
		{
			// show message
			$i = 0;
			$hidden_fields = '';
			while($i < sizeof($user_ids))
			{
				$user_id = intval($user_ids[$i]);
				$hidden_fields .= '<input type="hidden" name="' . POST_USERS_URL . '[]" value="' . $user_id . '" />';

				unset($user_id);
				$i++;
			}

			$template->set_filenames(array('body' => ADM_TPL . 'confirm_body.tpl'));
			$template->assign_vars(array(
				'MESSAGE_TITLE' => $lang['Delete'],
				'MESSAGE_TEXT' => $lang['Confirm_user_deleted'],

				'U_INDEX' => '',
				'L_INDEX' => '',

				'L_YES' => $lang['Yes'],
				'L_NO' => $lang['No'],

				'S_CONFIRM_ACTION' => append_sid('admin_userlist.' . PHP_EXT . '?mode=delete'),
				'S_HIDDEN_FIELDS' => $hidden_fields
				)
			);
		}
		else
		{
			// delete users
			$i = 0;
			while($i < sizeof($user_ids))
			{
				$user_id = intval($user_ids[$i]);
				if($user_id == '2')
				{
					message_die(GENERAL_ERROR, $lang['L_ADMINEDITMSG']);
				}
				$killed = ip_user_kill($user_id);
				unset($user_id);
				$i++;
			}

			$message = $lang['User_deleted_successfully'] . '<br /><br />' . sprintf($lang['Click_return_userlist'], '<a href="' . append_sid('admin_userlist.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

			message_die(GENERAL_MESSAGE, $message);
		}
		break;

	case 'ban':

		// see if cancel has been hit and redirect if it has shouldn't get to this point if it has been hit but do this just in case
		if ($cancel)
		{
			redirect(ADM . '/admin_userlist.' . PHP_EXT);
		}

		// check confirm and either ban or show confirm message
		if (!$confirm)
		{
			$i = 0;
			$hidden_fields = '';
			while($i < sizeof($user_ids))
			{
				$user_id = intval($user_ids[$i]);
				$hidden_fields .= '<input type="hidden" name="' . POST_USERS_URL . '[]" value="' . $user_id . '" />';

				unset($user_id);
				$i++;
			}

			$template->set_filenames(array('body' => ADM_TPL . 'confirm_body.tpl'));
			$template->assign_vars(array(
				'MESSAGE_TITLE' => $lang['Ban'],
				'MESSAGE_TEXT' => $lang['Confirm_user_ban'],

				'U_INDEX' => '',
				'L_INDEX' => '',

				'L_YES' => $lang['Yes'],
				'L_NO' => $lang['No'],

				'S_CONFIRM_ACTION' => append_sid('admin_userlist.' . PHP_EXT . '?mode=ban'),
				'S_HIDDEN_FIELDS' => $hidden_fields
				)
			);
		}
		else
		{
			// ban users
			$i = 0;
			while($i < sizeof($user_ids))
			{
				$user_id = intval($user_ids[$i]);

				$founder_id = (defined('FOUNDER_ID') ? FOUNDER_ID : get_founder_id());
				if(($user_id == 2) || ($user_id == $founder_id))
				{
					message_die(GENERAL_ERROR, $lang['L_ADMINEDITMSG']);
				}
				if($user_id == ANONYMOUS)
				{
					message_die(GENERAL_ERROR, 'Could not ban anonymous user');
				}

				$ban_insert_array = array(
					'ban_userid' => $user_id,
					'ban_by_userid' => $user->data['user_id'],
					'ban_start' => time()
				);
				$sql = "INSERT INTO " . BANLIST_TABLE . " " . $db->sql_build_insert_update($ban_insert_array, true);
				$result = $db->sql_query($sql);

				$sql = "UPDATE " . USERS_TABLE . " SET user_warnings = " . $config['max_user_bancard'] . " WHERE user_id = " . $user_id;
				$result = $db->sql_query($sql);

				unset($user_id);
				$i++;
			}

			$db->clear_cache('ban_', USERS_CACHE_FOLDER);

			$message = $lang['User_banned_successfully'] . '<br /><br />' . sprintf($lang['Click_return_userlist'], '<a href="' . append_sid('admin_userlist.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

			message_die(GENERAL_MESSAGE, $message);
		}
		break;

	case 'activate':

		// activate or deactive the seleted users
		$i = 0;
		while($i < sizeof($user_ids))
		{
			$user_id = intval($user_ids[$i]);
			$sql = "SELECT user_active FROM " . USERS_TABLE . "
				WHERE user_id = $user_id";
			$result = $db->sql_query($sql);
			$row = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);

			$new_status = ($row['user_active']) ? 0 : 1;

			$sql = "UPDATE " .  USERS_TABLE . "
				SET user_active = '" . $new_status . "'
				WHERE user_id = " . $user_id;
			$result = $db->sql_query($sql);

			if ($new_status == 0)
			{
				$clear_notification = user_clear_notifications($user_id);
			}

			unset($user_id);
			$i++;
		}

		$message = $lang['User_status_updated'] . '<br /><br />' . sprintf($lang['Click_return_userlist'], '<a href="' . append_sid('admin_userlist.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

		message_die(GENERAL_MESSAGE, $message);
		break;

	case 'group':

		// add users to a group
		if (!$confirm)
		{
			// show form to select which group to add users to
			$i = 0;
			$hidden_fields = '';
			while($i < sizeof($user_ids))
			{
				$user_id = intval($user_ids[$i]);
				$hidden_fields .= '<input type="hidden" name="' . POST_USERS_URL . '[]" value="' . $user_id . '" />';

				unset($user_id);
				$i++;
			}

			$template->set_filenames(array('body' => ADM_TPL . 'userlist_group.tpl'));

			$template->assign_vars(array(
				'MESSAGE_TITLE' => $lang['Add_group'],
				'MESSAGE_TEXT' => $lang['Add_group_explain'],

				'L_GROUP' => $lang['Group'],

				'S_GROUP_VARIABLE' => POST_GROUPS_URL,
				'S_ACTION' => append_sid(IP_ROOT_PATH . ADM . '/admin_userlist.' . PHP_EXT . '?mode=group'),
				'L_GO' => $lang['Go'],
				'L_CANCEL' => $lang['Cancel'],
				'L_SELECT' => $lang['Select_one'],
				'S_HIDDEN_FIELDS' => $hidden_fields
				)
			);

			$sql = "SELECT group_id, group_name FROM " . GROUPS_TABLE . "
				WHERE group_single_user <> " . TRUE . "
				ORDER BY group_name";
			$result = $db->sql_query($sql);

			// loop through groups
			while ($row = $db->sql_fetchrow($result))
			{
				$template->assign_block_vars('grouprow',array(
					'GROUP_NAME' => $row['group_name'],
					'GROUP_ID' => $row['group_id']
					)
				);
			}
		}
		else
		{
			// add the users to the selected group
			$group_id = intval($_POST[POST_GROUPS_URL]);

			include(IP_ROOT_PATH . 'includes/emailer.' . PHP_EXT);
			$emailer = new emailer();

			$i = 0;
			while($i < sizeof($user_ids))
			{
				$user_id = intval($user_ids[$i]);

				// For security, get the ID of the group moderator.
				$sql = "SELECT g.group_moderator, g.group_type, aa.auth_mod
					FROM (" . GROUPS_TABLE . " g
					LEFT JOIN " . AUTH_ACCESS_TABLE . " aa ON aa.group_id = g.group_id)
					WHERE g.group_id = $group_id";
				$result = $db->sql_query($sql);
				$group_info = $db->sql_fetchrow($result);

				$sql = "SELECT user_id, user_email, user_lang, user_level
					FROM " . USERS_TABLE . "
					WHERE user_id = $user_id";
				$result = $db->sql_query($sql);
				$row = $db->sql_fetchrow($result);

				$sql = "SELECT ug.user_id, u.user_level
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

					if (($row['user_level'] != ADMIN) && ($row['user_level'] != JUNIOR_ADMIN) && ($row['user_level'] != MOD) && $group_info['auth_mod'])
					{
						$sql = "UPDATE " . USERS_TABLE . "
							SET user_level = " . MOD . "
							WHERE user_id = " . $row['user_id'];
						$db->sql_query($sql);
					}

					// Get the group name
					// Email the user and tell them they're in the group
					$group_sql = "SELECT group_name
						FROM " . GROUPS_TABLE . "
						WHERE group_id = $group_id";
					$result = $db->sql_query($group_sql);
					$group_name_row = $db->sql_fetchrow($result);
					$group_name = $group_name_row['group_name'];

					$server_url = create_server_url();
					$groupcp_url = $server_url . CMS_PAGE_GROUP_CP;

					$emailer->use_template('group_added', $row['user_lang']);
					$emailer->to($row['user_email']);
					$emailer->set_subject($lang['Group_added']);

					$email_sig = create_signature($config['board_email_sig']);
					$emailer->assign_vars(array(
						'SITENAME' => $config['sitename'],
						'GROUP_NAME' => $group_name,
						'EMAIL_SIG' => $email_sig,
						'U_GROUPCP' => $groupcp_url . '?' . POST_GROUPS_URL . '=' . $group_id
						)
					);
					$emailer->send();
					$emailer->reset();

				}

				unset($user_id);
				$i++;
			}

			$message = $lang['User_add_group_successfully'] . '<br /><br />' . sprintf($lang['Click_return_userlist'], '<a href="' . append_sid('admin_userlist.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

			message_die(GENERAL_MESSAGE, $message);
		}
		break;

	default:

		// get and display all of the users
		$template->set_filenames(array('body' => ADM_TPL . 'userlist_body.tpl'));

		// gets for alphanum
		$alpha_range = array();
		$alpha_letters = array();
		$alpha_letters = range('A','Z');
		$alpha_start = array($lang['All'], '#');
		$alpha_range = array_merge($alpha_start, $alpha_letters);

		$i = 0;
		while($i < sizeof($alpha_range))
		{

			if ($alpha_range[$i] != $lang['All'])
			{
				if ($alpha_range[$i] != '#')
				{
					$temp = strtolower($alpha_range[$i]);
				}
				else
				{
					$temp = 'num';
				}
				$alphanum_search_url = append_sid(IP_ROOT_PATH . ADM . '/admin_userlist.' . PHP_EXT . '?sort=' . $sort_method . '&amp;order=' . $sort_order . '&amp;show=' . $show . '&amp;alphanum=' . $temp);
			}
			else
			{
				$alphanum_search_url = append_sid(IP_ROOT_PATH . ADM . '/admin_userlist.' . PHP_EXT . '?sort=' . $sort_method . '&amp;order=' . $sort_order . '&amp;show=' . $show);
			}

			$template->assign_block_vars('alphanumsearch', array(
				'SEARCH_SIZE' => floor(100 / sizeof($alpha_range)) . '%',
				'SEARCH_TERM' => $alpha_range[$i],
				'SEARCH_LINK' => $alphanum_search_url
				)
			);

			$i++;
		}

		$hidden_fields = '<input type="hidden" name="start" value="' . $start . '" />';
		$select_sort_by = array('user_id', 'user_active', 'username', 'user_regdate', 'user_session_time', 'user_level', 'user_posts', 'user_rank', 'user_email', 'user_website', 'user_birthday', 'user_lang', 'user_style');
		$select_sort_by_text = array($lang['User_id'], $lang['Active'], $lang['Username'], $lang['Joined'], $lang['Last_activity'], $lang['User_level'], $lang['Posts'], $lang['Rank'], $lang['Email'], $lang['Website'], $lang['Birthday'], $lang['Board_lang'], $lang['Board_style']);

		$select_sort = '<select name="sort" class="post">';
		for($i = 0; $i < sizeof($select_sort_by); $i++)
		{
			$selected = ($sort_method == $select_sort_by[$i]) ? ' selected="selected"' : '';
			$select_sort .= '<option value="' . $select_sort_by[$i] . '"' . $selected . '>' . $select_sort_by_text[$i] . '</option>';
		}
		$select_sort .= '</select>';

		$select_sort_order = '<select name="order" class="post">';
		if ($sort_order == 'ASC')
		{
			$select_sort_order .= '<option value="ASC" selected="selected">' . $lang['Ascending'] . '</option><option value="DESC">' . $lang['Descending'] . '</option>';
		}
		else
		{
			$select_sort_order .= '<option value="ASC">' . $lang['Ascending'] . '</option><option value="DESC" selected="selected">' . $lang['Descending'] . '</option>';
		}
		$select_sort_order .= '</select>';
		$hidden_fields .= '<input type="hidden" name="alphanum" value="' . $alphanum . '" />';

		//
		// set up template varibles
		//
		$template->assign_vars(array(
			'L_TITLE' => $lang['Userlist'],
			'L_DESCRIPTION' => $lang['Userlist_description'],

			'L_OPEN_CLOSE' => $lang['Open_close'],
			'L_ACTIVE' => $lang['Active'],
			'L_USERNAME' => $lang['Username'],
			'L_GROUP' => $lang['Group'],
			'L_RANK' => $lang['Rank'],
			'L_POSTS' => $lang['Posts'],
			'L_FIND_ALL_POSTS' => $lang['Find_all_posts'],
			'L_JOINED' => $lang['Joined'],
			'L_ACTIVTY' => $lang['Last_activity'],
			'L_MANAGE' => $lang['User_manage'],
			'L_PERMISSIONS' => $lang['Permissions'],
			'L_EMAIL' => $lang['Email'],
			'L_PM' => $lang['Private_Message'],
			'L_WEBSITE' => $lang['Website'],
			'L_BIRTHDAY' => $lang['Birthday'],
			'L_LANG' => $lang['Board_lang'],
			'L_STYLE' => $lang['Board_style'],

			'S_USER_VARIABLE' => POST_USERS_URL,
			'S_ACTION' => append_sid(IP_ROOT_PATH . ADM . '/admin_userlist.' . PHP_EXT),
			'L_GO' => $lang['Go'],
			'L_SELECT' => $lang['Select_one'],
			'L_DELETE' => $lang['Delete'],
			'L_BAN' => $lang['Ban'],
			'L_ACTIVATE_DEACTIVATE' => $lang['Activate_deactivate'],
			'L_ADD_GROUP' => $lang['Add_group'],

			'S_SHOW' => $show,
			'L_SORT_BY' => $lang['Sort_by'],
			'L_USER_ID' => $lang['User_id'],
			'L_USER_LEVEL' => $lang['User_level'],
			'L_ASCENDING' => $lang['Ascending'],
			'L_DESCENDING' => $lang['Descending'],
			'L_SHOW' => $lang['Show'],
			'S_SORT' => $lang['Sort'],
			'S_SELECT_SORT' => $select_sort,
			'S_SELECT_SORT_ORDER' => $select_sort_order,
			'S_HIDDEN_FIELDS' => $hidden_fields
			)
		);

		$order_by = "ORDER BY $sort_method $sort_order ";

		$sql = "SELECT *
			FROM " . USERS_TABLE . "
			WHERE user_id <> " . ANONYMOUS . "
				$alpha_where
			$order_by
			LIMIT $start, $show";
		$result = $db->sql_query($sql);

		// Query Ranks
		$rank_sql = "SELECT * FROM " . RANKS_TABLE . " ORDER BY rank_special ASC, rank_min ASC";
		$rank_result = $db->sql_query($rank_sql);

		while ($rank_row = $db->sql_fetchrow($rank_result))
		{
			$ranksrow[] = $rank_row;
		}
		$db->sql_freeresult($rank_result);

		// loop through users
		$i = 1;
		while ($row = $db->sql_fetchrow($result))
		{
			$avatar_img = user_get_avatar($row['user_id'], $row['user_level'], $row['user_avatar'], $row['user_avatar_type'], $row['user_allowavatar'], '../');

			$poster_rank = '';
			$rank_image = '';
			if ($row['user_rank'])
			{
				for($ji = 0; $ji < sizeof($ranksrow); $ji++)
				{
					if ($row['user_rank'] == $ranksrow[$ji]['rank_id'] && $ranksrow[$ji]['rank_special'])
					{
						$poster_rank = $ranksrow[$ji]['rank_title'];
						$rank_image = ($ranksrow[$ji]['rank_image']) ? '<img src="' . IP_ROOT_PATH . $ranksrow[$ji]['rank_image'] . '" alt="' . $poster_rank . '" title="' . $poster_rank . '" /><br />' : '';
					}
				}
			}
			else
			{
				for($ji = 0; $ji < sizeof($ranksrow); $ji++)
				{
					if ($row['user_posts'] >= $ranksrow[$ji]['rank_min'] && !$ranksrow[$ji]['rank_special'])
					{
						$poster_rank = $ranksrow[$ji]['rank_title'];
						$rank_image = ($ranksrow[$ji]['rank_image']) ? '<img src="' . IP_ROOT_PATH . $ranksrow[$ji]['rank_image'] . '" alt="' . $poster_rank . '" title="' . $poster_rank . '" /><br />' : '';
					}
				}
			}

			// setup user row template variables
			$user_full_name = (!empty($row['user_first_name']) ? $row['user_first_name'] : '') . (!empty($row['user_last_name']) ? ((!empty($row['user_first_name']) ? ' ' : '')) . $row['user_last_name'] : '');
			$template->assign_block_vars('user_row', array(
				'ROW_NUMBER' => $i + (intval($_GET['start']) + 1),
				'ROW_CLASS' => (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'],

				'USER_ID' => $row['user_id'],
				'ACTIVE' => ($row['user_active'] == true) ? $lang['Yes'] : $lang['No'],
				'USERNAME' => colorize_username($row['user_id'], $row['username'], $row['user_color'], $row['user_active']),
				'USER_FIRST_NAME' => htmlspecialchars($row['user_first_name']),
				'USER_LAST_NAME' => htmlspecialchars($row['user_last_name']),
				'USER_FULL_NAME' => $user_full_name,
				'U_PROFILE' => append_sid(IP_ROOT_PATH . CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $row['user_id']),
				'RANK' => $poster_rank,
				'I_RANK' => $rank_image,
				'I_AVATAR' => $avatar_img,
				'JOINED' => create_date($lang['JOINED_DATE_FORMAT'], $row['user_regdate'], $config['board_timezone']),
				'BIRTHDAY' => ($row['user_birthday'] != 999999) ? realdate($lang['DATE_FORMAT_BIRTHDAY'], $row['user_birthday']) : '',
				'LAST_ACTIVITY' => (!empty($row['user_session_time'])) ? create_date('d M Y @ h:ia', $row['user_session_time'], $config['board_timezone']) : $lang['Never'],
				'POSTS' => ($row['user_posts']) ? $row['user_posts'] : 0,
				'U_SEARCH' => append_sid(IP_ROOT_PATH . CMS_PAGE_SEARCH.'?search_author=' . urlencode(strip_tags($row['username'])) . '&amp;showresults=posts'),
				'U_WEBSITE' => ($row['user_website']) ? $row['user_website'] : '',
				'USER_LANG' => $row['user_lang'],
				'USER_STYLE' => $row['user_style'],
				'EMAIL' => htmlspecialchars($row['user_email']),
				'U_PM' => append_sid(IP_ROOT_PATH . CMS_PAGE_PRIVMSG . '?mode=post&amp;' . POST_USERS_URL . '='. $row['user_id']),
				'U_MANAGE' => append_sid(IP_ROOT_PATH . ADM . '/admin_users.' . PHP_EXT . '?mode=edit&amp;' . POST_USERS_URL . '=' . $row['user_id']),
				'U_PERMISSIONS' => append_sid(IP_ROOT_PATH . ADM . '/admin_ug_auth.' . PHP_EXT . '?mode=user&amp;' . POST_USERS_URL . '=' . $row['user_id'])
				)
			);

			// get the users group information
			$group_sql = "SELECT *
				FROM " . USER_GROUP_TABLE . " ug, " . GROUPS_TABLE . " g
				WHERE ug.user_id = " . $row['user_id'] . "
					AND g.group_single_user <> 1
					AND g.group_id = ug.group_id";
			$group_result = $db->sql_query($group_sql);

			$g = 0;
			while ($group_row = $db->sql_fetchrow($group_result))
			{
				// assign the group varibles
				if ($group_row['group_moderator'] == $row['user_id'])
				{
					$group_status = $lang['Moderator'];
				}
				else if ($group_row['user_pending'] == true)
				{
					$group_status = $lang['Pending'];
				}
				else
				{
					$group_status = $lang['Member'];
				}

				$template->assign_block_vars('user_row.group_row', array(
					'GROUP_NAME' => $group_row['group_name'],
					'GROUP_COLOR' => 'style="font-weight: bold; text-decoration: none;' . (($group_row['group_color'] != '') ? ('color: ' . $group_row['group_color'] . ';') : '') . '"',
					'GROUP_STATUS' => $group_status,
					'U_GROUP' => append_sid(IP_ROOT_PATH . CMS_PAGE_GROUP_CP . '?' . POST_GROUPS_URL . '=' . $group_row['group_id'])
					)
				);
				$g++;
			}

			if ($g == 0)
			{
				$template->assign_block_vars('user_row.no_group_row', array(
					'L_NONE' => $lang['None'])
				);
			}

			$i++;
		}
		$db->sql_freeresult($result);

		$count_sql = "SELECT count(user_id) AS total
			FROM " . USERS_TABLE . "
			WHERE user_id <> " . ANONYMOUS . " $alpha_where";
		$count_result = $db->sql_query($count_sql);

		if ($total = $db->sql_fetchrow($count_result))
		{
			$total_members = $total['total'];
			$pagination = generate_pagination(IP_ROOT_PATH . ADM . '/admin_userlist.' . PHP_EXT . '?sort=' . $sort_method . '&amp;order=' . $sort_order . '&amp;show=' . $show . ((isset($alphanum)) ? '&amp;alphanum=' . $alphanum : ''), $total_members, $show, $start);
		}

		$template->assign_vars(array(
			'PAGINATION' => $pagination,
			'PAGE_NUMBER' => sprintf($lang['Page_of'], (floor($start / $show) + 1), ceil($total_members / $show))
			)
		);


		break;

} // switch()

$template->pparse('body');

include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);

?>