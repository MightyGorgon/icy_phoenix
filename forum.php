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

// Activity - BEGIN
//if (defined('ACTIVITY_MOD'))
if (defined('ACTIVITY_MOD') && (ACTIVITY_MOD == true))
{
	include(IP_ROOT_PATH . ACTIVITY_MOD_PATH . 'includes/functions_amod_index.' . PHP_EXT);
}
// Activity - END

//<!-- BEGIN Unread Post Information to Database Mod -->
$mark_always_read = (isset($_POST['always_read'])) ? $_POST['always_read'] : $_GET['always_read'];
$mark_forum_id = (isset($_POST['forum_id'])) ? $_POST['forum_id'] : $_GET['forum_id'];

if($userdata['upi2db_access'])
{
	$always_read_topics_string = explode(",", $unread['always_read']['topics']);
	$always_read_forums_string = explode(",", $unread['always_read']['forums']);

	if ($mark_always_read)
	{
		$mark_always_read_text = always_read_forum($mark_forum_id, $mark_always_read);

		$redirect_url = append_sid(FORUM_MG);
		meta_refresh(3, $redirect_url);

		$message = $mark_always_read_text . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid(FORUM_MG) . '">', '</a> ');
		message_die(GENERAL_MESSAGE, $message);
	}
}
//<!-- END Unread Post Information to Database Mod -->

$cms_page_id = '1';
$cms_page_name = 'forum';
$auth_level_req = $board_config['auth_view_forum'];
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
$cms_global_blocks = ($board_config['wide_blocks_forum'] == 1) ? true : false;

require(IP_ROOT_PATH . 'language/lang_' . $board_config['default_lang'] . '/lang_main_link.' . PHP_EXT);

$viewcat = (!empty($_GET[POST_CAT_URL])) ? $_GET[POST_CAT_URL] : -1;
$viewcat = intval($viewcat);
if ($viewcat <= 0) $viewcat = -1;
$viewcatkey = ($viewcat < 0) ? 'Root' : POST_CAT_URL . $viewcat;
if(isset($_GET['mark']) || isset($_POST['mark']))
{
	$mark_read = (isset($_POST['mark'])) ? $_POST['mark'] : $_GET['mark'];
}
else
{
	$mark_read = '';
}

// Handle marking posts
if($mark_read == 'forums')
{
	if ($viewcat < 0)
	{
		if($userdata['session_logged_in'])
		{
			// 60 days limit
			if ($userdata['user_lastvisit'] < (time() - 5184000))
			{
				$userdata['user_lastvisit'] = time() - 5184000;
			}
			//setcookie($board_config['cookie_name'] . '_f_all', time(), 0, $board_config['cookie_path'], $board_config['cookie_domain'], $board_config['cookie_secure']);
			//<!-- BEGIN Unread Post Information to Database Mod -->
			if(!$userdata['upi2db_access'])
			{
				setcookie($board_config['cookie_name'] . '_f_all', time(), 0, $board_config['cookie_path'], $board_config['cookie_domain'], $board_config['cookie_secure']);
			}
			else
			{
				marking_posts();
			}
			//<!-- END Unread Post Information to Database Mod -->
		}

		$redirect_url = append_sid(FORUM_MG);
		meta_refresh(3, $redirect_url);
	}
	else
	{
		if($userdata['session_logged_in'])
		{
			// get the list of object authorized
			$keys = array();
			$keys = get_auth_keys($viewcatkey);

			// mark each forums
			for ($i = 0; $i < count($keys['id']); $i++) if ($tree['type'][ $keys['idx'][$i] ] == POST_FORUM_URL)
			{
				$forum_id = $tree['id'][ $keys['idx'][$i] ];
				$sql = "SELECT MAX(post_time) AS last_post FROM " . POSTS_TABLE . " WHERE forum_id = '" . $forum_id . "'";
				if (!($result = $db->sql_query($sql))) message_die(GENERAL_ERROR, 'Could not obtain forums information', '', __LINE__, __FILE__, $sql);
				if ($row = $db->sql_fetchrow($result))
				{
					$tracking_forums = (isset($_COOKIE[$board_config['cookie_name'] . '_f'])) ? unserialize($_COOKIE[$board_config['cookie_name'] . '_f']) : array();
					$tracking_topics = (isset($_COOKIE[$board_config['cookie_name'] . '_t'])) ? unserialize($_COOKIE[$board_config['cookie_name'] . '_t']) : array();

					if ((count($tracking_forums) + count($tracking_topics)) >= 150 && empty($tracking_forums[$forum_id]))
					{
						asort($tracking_forums);
						unset($tracking_forums[key($tracking_forums)]);
					}

					if ($row['last_post'] > $userdata['user_lastvisit'])
					{
						$tracking_forums[$forum_id] = time();
						setcookie($board_config['cookie_name'] . '_f', serialize($tracking_forums), 0, $board_config['cookie_path'], $board_config['cookie_domain'], $board_config['cookie_secure']);
					}
				}
			}
		}

		$redirect_url = append_sid(FORUM_MG . '?' . POST_CAT_URL . '=' . $viewcat);
		meta_refresh(3, $redirect_url);
	}

	$message = $lang['Forums_marked_read'] . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid(FORUM_MG) . '">', '</a> ');

	message_die(GENERAL_MESSAGE, $message);
}
// End handle marking posts

include_once(IP_ROOT_PATH . 'includes/mods_settings/mod_categories_hierarchy.' . PHP_EXT);
if (($board_config['display_viewonline'] == 2) || (($viewcat < 0) && ($board_config['display_viewonline'] == 1)))
{
	define('SHOW_ONLINE', true);
	if (empty($board_config['max_topics']) || empty($board_config['max_posts']) || empty($board_config['max_users']) || empty($board_config['last_user_id']))
	{
		board_stats();
	}
	/*
	$total_topics = get_db_stat('topiccount');
	$total_posts = get_db_stat('postcount');
	$total_users = get_db_stat('usercount');
	$newest_userdata = get_db_stat('newestuser');
	*/
	$total_topics = $board_config['max_topics'];
	$total_posts = $board_config['max_posts'];
	$total_users = $board_config['max_users'];
	$newest_userdata['user_id'] = $board_config['last_user_id'];
	$newest_user = colorize_username($newest_userdata['user_id']);
	$newest_uid = $newest_userdata['user_id'];

	$l_total_post_s = $lang['Posted_articles_total'];

	if($total_users == 0)
	{
		$l_total_user_s = $lang['Registered_users_zero_total'];
	}
	elseif($total_users == 1)
	{
		$l_total_user_s = $lang['Registered_user_total'];
	}
	else
	{
		$l_total_user_s = $lang['Registered_users_total'];
	}

	// Last Visit - BEGIN
	$cache_data_file = MAIN_CACHE_FOLDER . 'last_visit_' . $userdata['user_level'] . '_' . $board_config['board_timezone'] . '.dat';
	$cache_update = true;
	$cache_file_time = time();
	if (@is_file($cache_data_file))
	{
		$cache_file_time = @filemtime($cache_data_file);
		if (((date('YzH', time()) - date('YzH', $cache_file_time)) < 1) && ((date('Y', time()) == date('Y', $cache_file_time))))
		{
			$cache_update = false;
		}
	}

	if (!$cache_update)
	{
		include($cache_data_file);
	}
	else
	{
		$time_now = time();
		$time1Hour = $time_now - 3600;
		$minutes = date('is', $time_now);
		$hour_now = $time_now - (60 * ($minutes[0] . $minutes[1])) - ($minutes[2] . $minutes[3]);
		$dato = create_date('H', $time_now, $board_config['board_timezone']);
		$timetoday = $hour_now - (3600 * $dato);
		$sql = 'SELECT session_ip, MAX(session_time) as session_time
						FROM ' . SESSIONS_TABLE . '
						WHERE session_user_id="' . ANONYMOUS . '"
						AND session_time >= ' . $timetoday . '
						AND session_time < ' . ($timetoday + 86399) . '
						GROUP BY session_ip';

		if (!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Couldn\'t retrieve guest user today data', '', __LINE__, __FILE__, $sql);
		}

		while($guest_list = $db->sql_fetchrow($result))
		{
			if ($guest_list['session_time'] > $time1Hour)
			{
				$users_lasthour++;
			}
		}
		$guests_today = $db->sql_numrows($result);
		$sql = 'SELECT user_id, username, user_allow_viewonline, user_level, user_lastlogon
						FROM ' . USERS_TABLE . '
						WHERE user_id != "' . ANONYMOUS . '"
							AND user_session_time >= ' . $timetoday . '
							AND user_session_time < ' . ($timetoday + 86399) . '
						ORDER BY username';

		if (!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Couldn't retrieve user today data", "", __LINE__, __FILE__, $sql);
		}

		while($todayrow = $db->sql_fetchrow($result))
		{
			$todayrow['user_level'] = ($todayrow['user_level'] == JUNIOR_ADMIN) ? ADMIN : $todayrow['user_level'];
			$style_color = '';
			if ($todayrow['user_lastlogon'] >= $time1Hour)
			{
				$users_lasthour++;
			}
			$colored_user = colorize_username($todayrow['user_id'], false);
			switch ($todayrow['user_level'])
			{
				case ADMIN:
					$admins_today_list .= ($todayrow['user_allow_viewonline']) ? $colored_user . ', ' : (($userdata['user_level'] == ADMIN) ? '<i>' . $colored_user . '</i>, ' : '');
				break;
				case MOD:
					$mods_today_list .= ($todayrow['user_allow_viewonline']) ? $colored_user . ', ' : (($userdata['user_level'] == ADMIN) ? '<i>' . $colored_user . '</i>, ' : '');
				break;
				default:
					$users_today_list .= ($todayrow['user_allow_viewonline']) ? $colored_user . ', ' : (($userdata['user_level'] == ADMIN) ? '<i>' . $colored_user . '</i>, ' : '');
				break;
			}

			if (!$todayrow['user_allow_viewonline'])
			{
				$logged_hidden_today++;
			}
			else
			{
				$logged_visible_today++;
			}
		}

		if ($admins_today_list)
		{
			//$admins_today_list[strlen($admins_today_list) - 2] = ' ';
			$admins_today_list = substr($admins_today_list, 0, strlen($admins_today_list) - 2);
		}
		else
		{
			$admins_today_list = $lang['None'];
		}

		if ($mods_today_list)
		{
			//$mods_today_list[strlen($mods_today_list) - 2] = ' ';
			$mods_today_list = substr($mods_today_list, 0, strlen($mods_today_list) - 2);
		}
		else
		{
			$mods_today_list = $lang['None'];
		}
		if ($users_today_list)
		{
			$users_today_list[strlen($users_today_list) - 2] = ' ';
			$users_today_list = substr($users_today_list, 0, strlen($users_today_list) - 2);
		}
		else
		{
			$users_today_list = $lang['None'];
		}

		$total_users_today = $db->sql_numrows($result)+$guests_today;

		$admins_today_list = addslashes($admins_today_list);
		$mods_today_list = addslashes($mods_today_list);
		$users_today_list = addslashes($users_today_list);

		if (isset($_COOKIE[$board_config['cookie_name'] . '_sid']))
		{
		// stores the data set in a cache file
			$data = '<' . '?php' . "\n";
			$data .= '$total_users_today = ' . intval($total_users_today) . ';' . "\n";
			$data .= '$users_lasthour = ' . intval($users_lasthour) . ';' . "\n";
			$data .= '$guests_today = ' . intval($guests_today) . ';' . "\n";
			$data .= '$logged_visible_today = ' . intval($logged_visible_today) . ';' . "\n";
			$data .= '$logged_hidden_today = ' . intval($logged_hidden_today) . ';' . "\n";
			$data .= '$admins_today_list = \'' . $admins_today_list . '\';' . "\n";
			$data .= '$mods_today_list = \'' . $mods_today_list . '\';' . "\n";
			$data .= '$users_today_list = \'' . $users_today_list . '\';' . "\n";
			$data .= '?' . '>';
			$fp = fopen($cache_data_file, 'w');
			@fwrite($fp, $data);
			@fclose($fp);
		}
	}

	$admins_today_list = '<b>' . $lang['Users_Admins'] . ':</b>&nbsp;' . stripslashes($admins_today_list);
	$mods_today_list = '<b>' . $lang['Users_Mods'] . ':</b>&nbsp;' . stripslashes($mods_today_list);
	$users_today_list = '<b>' . $lang['Users_Regs'] . ':</b>&nbsp;' . stripslashes($users_today_list);
	//$users_today_list = $lang['Registered_users'] . ' ' . $users_today_list;
	$l_today_user_s = ($total_users_today) ? (($total_users_today == 1)? $lang['User_today_total'] : $lang['Users_today_total']) : $lang['Users_today_zero_total'];
	$l_today_r_user_s = ($logged_visible_today) ? (($logged_visible_today == 1) ? $lang['Reg_user_total'] : $lang['Reg_users_total']) : $lang['Reg_users_zero_total'];
	$l_today_h_user_s = ($logged_hidden_today) ? (($logged_hidden_today == 1) ? $lang['Hidden_user_total'] : $lang['Hidden_users_total']) : $lang['Hidden_users_zero_total'];
	$l_today_g_user_s = ($guests_today) ? (($guests_today == 1) ? $lang['Guest_user_total'] : $lang['Guest_users_total']) : $lang['Guest_users_zero_total'];
	$l_today_users = sprintf($l_today_user_s, $total_users_today);
	$l_today_users .= sprintf($l_today_r_user_s, $logged_visible_today);
	$l_today_users .= sprintf($l_today_h_user_s, $logged_hidden_today);
	$l_today_users .= sprintf($l_today_g_user_s, $guests_today);
	$l_today_text = ($users_lasthour) ? sprintf($lang['Users_lasthour_explain'], $users_lasthour) : $lang['Users_lasthour_none_explain'];
	// Last Visit - END

	// Birthday Box - BEGIN
	if (($board_config['index_birthday'] == true) && ($board_config['birthday_check_day'] > 0))
	{
		// Birthday Mod, Show users with birthday
		$template->assign_block_vars('switch_show_birthday', array());

		$cache_data_file = MAIN_CACHE_FOLDER . 'birthday_' . $board_config['board_timezone'] . '.dat';
		$cache_update = true;
		$cache_file_time = time();
		if (@is_file($cache_data_file))
		{
			$cache_file_time = @filemtime($cache_data_file);
			if (((date('YzH', time()) - date('YzH', $cache_file_time)) < 1) && ((date('Y', time()) == date('Y', $cache_file_time))))
			{
				$cache_update = false;
			}
		}

		if (!$cache_update)
		{
			include($cache_data_file);
			$birthday_today_list = stripslashes($birthday_today_list);
			$birthday_week_list = stripslashes($birthday_week_list);
		}
		else
		{
			if ($board_config['birthday_check_day'])
			{
				if ($board_config['inactive_users_memberlists'] == false)
				{
					$where_sql = 'AND user_active = 1';
				}
				else
				{
					$where_sql = '';
				}
				$sql = "SELECT user_id, username, user_birthday, user_level
								FROM " . USERS_TABLE . "
								WHERE user_birthday != 999999
									" . $where_sql . "
									ORDER BY username";

				if($result = $db->sql_query($sql))
				{
					if (!empty($result))
					{
						$time_now = time();
						$this_year = create_date('Y', $time_now, $board_config['board_timezone']);
						$date_today = create_date('Ymd', $time_now, $board_config['board_timezone']);
						$date_forward = create_date('Ymd', $time_now+($board_config['birthday_check_day'] * 86400), $board_config['board_timezone']);
						while ($birthdayrow = $db->sql_fetchrow($result))
						{
							$user_birthday2 = $this_year . ($user_birthday = realdate('md', $birthdayrow['user_birthday']));
							if ($user_birthday2 < $date_today)
							{
								$user_birthday2 += 10000;
							}
							if (($user_birthday2 > $date_today) && ($user_birthday2 <= $date_forward))
							{
								// user are having birthday within the next days
								$user_age = ($this_year.$user_birthday < $date_today) ? $this_year - realdate('Y', $birthdayrow['user_birthday']) + 1 : $this_year - realdate('Y', $birthdayrow['user_birthday']);
								$style_color = colorize_username($birthdayrow['user_id']);
								$birthday_week_list .= ' ' . $style_color . ' (' . $user_age . '),';
							}
							elseif ($user_birthday2 == $date_today)
							{
								//user have birthday today
								$user_age = $this_year - realdate('Y', $birthdayrow['user_birthday']);
								$style_color = colorize_username($birthdayrow['user_id']);
								$birthday_today_list .= ' ' . $style_color . ' (' . $user_age . '),';
							}
						}
						if ($birthday_today_list)
						{
							$birthday_today_list[ strlen($birthday_today_list)-1] = ' ';
						}
						if ($birthday_week_list)
						{
							$birthday_week_list[ strlen($birthday_week_list)-1] = ' ';
						}
					}
					$db->sql_freeresult($result);
					if (empty($SID))
					{
						// stores the data set in a cache file
						$data = '<' . '?php' . "\n";
						$data .= '$birthday_today_list = \'' . addslashes($birthday_today_list) . "';\n";
						$data .= '$birthday_week_list = \'' . addslashes($birthday_week_list) . "';\n";
						$data .= '?' . '>';
						$fp = fopen($cache_data_file, "w");
						fwrite($fp, $data);
						fclose($fp);
					}
				}
			}
		}
		$birthday_today_list = stripslashes($birthday_today_list);
		$birthday_week_list = stripslashes($birthday_week_list);
	}
	// Birthday Box - END
}

$avatar_img = user_get_avatar($userdata['user_id'], $userdata['user_avatar'], $userdata['user_avatar_type'], $userdata['user_allowavatar']);

// Check For Anonymous User
if ($userdata['user_id'] != '-1')
{
	$username = colorize_username($userdata['user_id']);
}
else
{
	$username = $lang['Guest'];
	$avatar_img = '<img src="' . $board_config['default_avatar_guests_url'] . '" alt="" />';
}

if ($board_config['index_links'] == true)
{
	$sql = "SELECT * FROM " . LINK_CONFIG_TABLE;
		if(!$result = $db->sql_query($sql, false, 'links_cfg_'))
		{
			message_die(GENERAL_ERROR, "Could not query Link config information", "", __LINE__, __FILE__, $sql);
		}
		while($row = $db->sql_fetchrow($result))
		{
			$link_config_name = $row['config_name'];
			$link_config_value = $row['config_value'];
			$link_config[$link_config_name] = $link_config_value;
			$link_self_img = $link_config['site_logo'];
			$site_logo_height = $link_config['height'];
			$site_logo_width = $link_config['width'];
		}
		$template->assign_block_vars('switch_show_links', array());
}

if ($board_config['site_history'] == true)
{
	$current_time = time();
	$minutes = date('is', $current_time);
	$hour_now = $current_time - (60 * ($minutes[0] . $minutes[1])) - ($minutes[2] . $minutes[3]);
	// change the number late in the next line, to what ever time zone your forum is located, this need to be hard coded in the release of this mod, the number is 1
	$dato = create_date('H', $current_time,1);
	$timetoday = $hour_now - (3600 * $dato);
	$sql = 'SELECT COUNT(DISTINCT session_ip) as guests_today FROM ' . SESSIONS_TABLE . ' WHERE session_user_id="' . ANONYMOUS . '" AND session_time >= ' . $timetoday . ' AND session_time < ' . ($timetoday + 86399);
	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Couldn't retrieve guest user today data", "", __LINE__, __FILE__, $sql);
	}
	$guest_count = $db->sql_fetchrow ($result);
	$sql = 'SELECT user_allow_viewonline, COUNT(*) as count FROM ' . USERS_TABLE . ' WHERE user_id!="' . ANONYMOUS . '" AND user_session_time >= ' . $timetoday . ' AND user_session_time < ' . ($timetoday + 86399) . ' GROUP BY user_allow_viewonline';
	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Couldn\'t retrieve user today data', '', __LINE__, __FILE__, $sql);
	}
	while ($reg_count = $db->sql_fetchrow ($result))
	{
		if ($reg_count['user_allow_viewonline'])
		{
			$logged_visible_today=$reg_count['count'];
		}
		else
		{
			$logged_hidden_today=$reg_count['count'];
		}
	}
	$db->sql_freeresult($result);
	$sql = 'UPDATE ' . SITE_HISTORY_TABLE . ' SET reg="' . $logged_visible_today . '", hidden="' . $logged_hidden_today . '", guests="' . $guest_count['guests_today'] . '" WHERE date=' . $hour_now;
	if (!$db->sql_query($sql) || !$db->sql_affectedrows())
	{
		$sql = 'INSERT IGNORE INTO ' . SITE_HISTORY_TABLE . ' (date, reg, hidden, guests)
			VALUES (' . $hour_now . ', "' . $logged_visible_today . '", "' . $logged_hidden_today . '", "' . $guest_count['guests_today'] . '")';
		if (!($db->sql_query($sql)))
		{
			message_die(CRITICAL_ERROR, 'Error create new site_hitory ', '', __LINE__, __FILE__, $sql);
		}
	}
	if (isset($result))
	{
		$db->sql_freeresult($result);
	}
}

// set the param of the mark read func
$mark = ($viewcat == -1) ? '' : '&amp;' . POST_CAT_URL . '=' . $viewcat;

if (!$board_config['board_disable'] || ($board_config['board_disable'] && ($userdata['user_level'] == ADMIN)))
{
	$template->vars['S_TPL_FILENAME'] = 'index';
}

build_groups_list_template();

//$template->assign_block_vars('google_ad', array());
$page_title = $lang['Forum'];
$meta_description = '';
$meta_keywords = '';
include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);
$template->set_filenames(array('body' => 'index_body.tpl'));

$template->assign_vars(array(
	'TOTAL_POSTS' => sprintf($l_total_post_s, $total_posts),
	'TOTAL_USERS' => sprintf($l_total_user_s, $total_users),
	//'TOTAL_MALE' => sprintf($l_total_male, $total_male),
	//'TOTAL_FEMALE' => sprintf($l_total_female, $total_female),
	//'TOTAL_UNKNOWN' => sprintf($l_total_unknown, $total_unknown),
	//'NEWEST_USER' => sprintf($lang['Newest_user'], '<a href="' . append_sid(PROFILE_MG . '?mode=viewprofile&amp;' . POST_USERS_URL . "=$newest_uid") . '">', $newest_user, '</a>'),
	'NEWEST_USER' => sprintf($lang['Newest_user'], '', $newest_user, ''),
	'FORUM_IMG' => $images['forum_nor_read'],
	'FORUM_NEW_IMG' => $images['forum_nor_unread'],
	'FORUM_CAT_IMG' => $images['forum_sub_read'],
	'FORUM_NEW_CAT_IMG' => $images['forum_sub_unread'],
	'FORUM_LOCKED_IMG' => $images['forum_nor_locked_read'],
	'FORUM_LINK_IMG' => $images['forum_link'],
//<!-- BEGIN Unread Post Information to Database Mod -->
	'FOLDER_AR_BIG' => $images['forum_nor_ar'],
//<!-- END Unread Post Information to Database Mod -->
	// Start add - Fully integrated shoutbox MOD
	'U_SHOUTBOX' => append_sid('shoutbox.' . PHP_EXT),
	'L_SHOUTBOX' => $lang['Shoutbox'],
	'U_SHOUTBOX_MAX' => append_sid('shoutbox_max.' . PHP_EXT),
	// End add - Fully integrated shoutbox MOD
	'AVATAR_IMG' => $avatar_img,
	'STATS_IMG' => $images['stats_image'],
	'BIRTHDAY_IMG' => $images['birthday_image'],
	'CAT_BLOCK_IMG' => $images['category_block'],
	'USER_NAME' => $username,
	'TOTAL_TOPIC' => $total_topics,
	// Start add - Last visit MOD
	'ADMINS_TODAY_LIST' => $admins_today_list,
	'MODS_TODAY_LIST' => $mods_today_list,
	'USERS_TODAY_LIST' => $users_today_list,
	'L_LEGEND' => $lang['legend'],
	'L_USERS' => $lang['users'],
	'L_USERS_LASTHOUR' => ($users_lasthour) ? sprintf($lang['Users_lasthour_explain'], $users_lasthour) : $lang['Users_lasthour_none_explain'],
	'L_USERS_TODAY' => $l_today_users,
	// End add - Last visit MOD
	// Start add - Birthday MOD
	'L_WHOSBIRTHDAY_WEEK' => ($board_config['birthday_check_day'] >= 1) ? sprintf((($birthday_week_list) ? $lang['Birthday_week'] : $lang['Nobirthday_week']), $board_config['birthday_check_day']) . $birthday_week_list : '',
	'L_WHOSBIRTHDAY_TODAY' => ($board_config['birthday_check_day']) ? ($birthday_today_list) ? $lang['Birthday_today'] . $birthday_today_list : $lang['Nobirthday_today'] : '',
	// End add - Birthday MOD
	'L_FORUM' => $lang['Forum'],
	'L_TOPICS' => $lang['Topics'],
	'L_REPLIES' => $lang['Replies'],
	'L_VIEWS' => $lang['Views'],
	'L_POSTS' => $lang['Posts'],
	'L_LASTPOST' => $lang['Last_Post'],
	'L_NO_NEW_POSTS' => $lang['No_new_posts'],
	'L_NEW_POSTS' => $lang['New_posts'],
	'L_FORUM_NO_NEW_POSTS' => $lang['Forum_no_new_posts'],
	'L_FORUM_NEW_POSTS' => $lang['Forum_new_posts'],
	'L_CAT_NO_NEW_POSTS' => $lang['Cat_no_new_posts'],
	'L_CAT_NEW_POSTS' => $lang['Cat_new_posts'],
	'L_NO_NEW_POSTS_LOCKED' => $lang['No_new_posts_locked'],
	'L_NEW_POSTS_LOCKED' => $lang['New_posts_locked'],
	'L_ONLINE_EXPLAIN' => $lang['Online_explain'],

	'L_LINKS' => $lang['Site_links'],
	'U_LINKS' => append_sid('links.' . PHP_EXT),
	'U_LINKS_JS' => 'links.js.' . PHP_EXT,
	'U_SITE_LOGO' => $link_self_img,
	'SITE_LOGO_WIDTH' => $site_logo_width,
	'SITE_LOGO_HEIGHT' => $site_logo_height,
	'L_MODERATOR' => $lang['Moderators'],
	'L_FORUM_LOCKED' => $lang['Forum_is_locked'],
	'L_MARK_FORUMS_READ' => $lang['Mark_all_forums'],
//<!-- BEGIN Unread Post Information to Database Mod -->
	'L_AR_POSTS' => $lang['always_read_icon'],
	'L_FORUM_AR' => $lang['always_read_icon'],
//<!-- END Unread Post Information to Database Mod -->
	'U_MARK_READ' => append_sid(FORUM_MG . '?mark=forums' . $mark)
	)
);

// Okay, let's build the index

// Display the board statistics
if (($board_config['display_viewonline'] == 2) || (($viewcat < 0) && ($board_config['display_viewonline'] == 1)))
{
	$template->assign_block_vars('disable_viewonline', array());
	if ($board_config['index_last_msgs'] == 1)
	{
		$template->assign_block_vars('disable_viewonline.show_recent', array());

		$except_forums = build_exclusion_forums_list();

		if(!empty($board_config['last_msgs_x']))
		{
			$except_forums .= ',' . $board_config['last_msgs_x'];
		}

		$except_forums = str_replace(' ', '', $except_forums);

		$sql = "SELECT t.topic_id, t.topic_title, t.topic_last_post_id, t.forum_id, p.post_id, p.poster_id, p.post_time, u.user_id, u.username
				FROM " . TOPICS_TABLE . " AS t, " . POSTS_TABLE . " AS p, " . USERS_TABLE . " AS u
				WHERE t.forum_id NOT IN (" . $except_forums . ")
					AND t.topic_status <> 2
					AND p.post_id = t.topic_last_post_id
					AND p.poster_id = u.user_id
				ORDER BY p.post_id DESC
				LIMIT " . intval($board_config['last_msgs_n']);
		if (!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not query recent topics information', '', __LINE__, __FILE__, $sql);
		}
		$number_recent_topics = $db->sql_numrows($result);
		$recent_topic_row = array();
		while ($row = $db->sql_fetchrow($result))
		{
			$recent_topic_row[] = $row;
		}
		for ($i = 0; $i < $number_recent_topics; $i++)
		{
			$template->assign_block_vars('disable_viewonline.show_recent.recent_topic_row', array(
				'U_TITLE' => append_sid(VIEWTOPIC_MG . "?" . POST_POST_URL . '=' . $recent_topic_row[$i]['post_id']) . '#p' . $recent_topic_row[$i]['post_id'],
				'L_TITLE' => $recent_topic_row[$i]['topic_title'],
				'U_POSTER' => append_sid(PROFILE_MG . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $recent_topic_row[$i]['user_id']),
				'S_POSTER' => $recent_topic_row[$i]['username'],
				'S_POSTTIME' => create_date($board_config['default_dateformat'], $recent_topic_row[$i]['post_time'], $board_config['board_timezone'])
				)
			);
		}
		// Recent Topics - END
	}
	if ($board_config['show_random_quote'] == true)
	{
		$template->assign_block_vars('disable_viewonline.switch_show_random_quote', array());
	}

	if ($board_config['show_chat_online'] == true)
	{
		$template->assign_block_vars('disable_viewonline.switch_ac_online', array());
	}

	if ($board_config['index_top_posters'] == true)
	{
		$template->assign_block_vars('disable_viewonline.top_posters', array(
			'TOP_POSTERS' => top_posters(8, true, true),
			)
		);
	}
}

// Display the index
$display = display_index($viewcatkey);

// check shoutbox permissions and display only to authorized users
$auth_level_req = $board_config['auth_view_shoutbox'];
if ((($board_config['index_shoutbox'] == true) && (($userdata['user_level'] + 1) >= $auth_level_req) && ($userdata['session_logged_in'])) || (($board_config['index_shoutbox'] == true) && ($userdata['user_level'] == ADMIN)))
{
	$template->assign_block_vars('switch_show_shoutbox', array());
}

if (!$display)
{
	message_die(GENERAL_MESSAGE, $lang['No_forums']);
}

// Should the news banner be shown?
include(IP_ROOT_PATH . 'includes/xs_news.' . PHP_EXT);
if($xs_news_config['xs_show_news'])
{
	$template->assign_block_vars('switch_show_news', array());
}

// Generate the page

$template->pparse('body');

include(IP_ROOT_PATH . 'includes/page_tail.' . PHP_EXT);

?>