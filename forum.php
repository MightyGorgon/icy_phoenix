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
if (defined('ACTIVITY_PLUGIN_ENABLED') && ACTIVITY_PLUGIN_ENABLED)
{
	include(IP_ROOT_PATH . ACTIVITY_PLUGIN_PATH . 'includes/functions_amod_index.' . PHP_EXT);
}
// Activity - END

//<!-- BEGIN Unread Post Information to Database Mod -->
$mark_always_read = request_var('always_read', '');
$mark_forum_id = request_var('forum_id', 0);

if($userdata['upi2db_access'])
{
	$always_read_topics_string = explode(',', $unread['always_read']['topics']);
	$always_read_forums_string = explode(',', $unread['always_read']['forums']);

	if (!empty($mark_always_read))
	{
		$mark_always_read_text = always_read_forum($mark_forum_id, $mark_always_read);

		$redirect_url = append_sid(CMS_PAGE_FORUM);
		meta_refresh(3, $redirect_url);

		$message = $mark_always_read_text . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid(CMS_PAGE_FORUM) . '">', '</a> ');
		message_die(GENERAL_MESSAGE, $message);
	}
}
//<!-- END Unread Post Information to Database Mod -->

$cms_page['page_id'] = 'forum';
$cms_page['page_nav'] = (!empty($cms_config_layouts[$cms_page['page_id']]['page_nav']) ? true : false);
$cms_page['global_blocks'] = (!empty($cms_config_layouts[$cms_page['page_id']]['global_blocks']) ? true : false);
$cms_auth_level = (isset($cms_config_layouts[$cms_page['page_id']]['view']) ? $cms_config_layouts[$cms_page['page_id']]['view'] : AUTH_ALL);
check_page_auth($cms_page['page_id'], $cms_auth_level);

require(IP_ROOT_PATH . 'language/lang_' . $config['default_lang'] . '/lang_main_link.' . PHP_EXT);

$viewcat = (!empty($_GET[POST_CAT_URL]) ? intval($_GET[POST_CAT_URL]) : -1);
$viewcat = (($viewcat <= 0) ? -1 : $viewcat);
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
	// Force last visit to max 60 days limit to avoid having too much unread topics
	if($userdata['session_logged_in'] && !$userdata['is_bot'])
	{
		if ($userdata['user_lastvisit'] < (time() - (LAST_LOGIN_DAYS_NEW_POSTS_RESET * 24 * 60 * 60)))
		{
			$userdata['user_lastvisit'] = time() - (LAST_LOGIN_DAYS_NEW_POSTS_RESET * 24 * 60 * 60);
		}
	}

	if ($viewcat < 0)
	{
		if($userdata['session_logged_in'] && !$userdata['is_bot'])
		{
			//<!-- BEGIN Unread Post Information to Database Mod -->
			if(!$userdata['upi2db_access'])
			{
				setcookie($config['cookie_name'] . '_f_all', time(), 0, $config['cookie_path'], $config['cookie_domain'], $config['cookie_secure']);
			}
			else
			{
				marking_posts();
			}
			//<!-- END Unread Post Information to Database Mod -->
		}

		$redirect_url = append_sid(CMS_PAGE_FORUM);
		meta_refresh(3, $redirect_url);
	}
	else
	{
		if($userdata['session_logged_in'] && !$userdata['is_bot'])
		{
			// get the list of object authorized
			$keys = array();
			$keys = get_auth_keys($viewcatkey);

			// mark each forums
			for ($i = 0; $i < sizeof($keys['id']); $i++)
			{
				if ($tree['type'][ $keys['idx'][$i] ] == POST_FORUM_URL)
				{
					$forum_id = $tree['id'][ $keys['idx'][$i] ];
					$sql = "SELECT MAX(post_time) AS last_post FROM " . POSTS_TABLE . " WHERE forum_id = '" . $forum_id . "'";
					$result = $db->sql_query($sql);
					if ($row = $db->sql_fetchrow($result))
					{
						$tracking_forums = (isset($_COOKIE[$config['cookie_name'] . '_f'])) ? unserialize($_COOKIE[$config['cookie_name'] . '_f']) : array();
						$tracking_topics = (isset($_COOKIE[$config['cookie_name'] . '_t'])) ? unserialize($_COOKIE[$config['cookie_name'] . '_t']) : array();

						if ((sizeof($tracking_forums) + sizeof($tracking_topics)) >= 150 && empty($tracking_forums[$forum_id]))
						{
							asort($tracking_forums);
							unset($tracking_forums[key($tracking_forums)]);
						}

						if ($row['last_post'] > $userdata['user_lastvisit'])
						{
							$tracking_forums[$forum_id] = time();
							setcookie($config['cookie_name'] . '_f', serialize($tracking_forums), 0, $config['cookie_path'], $config['cookie_domain'], $config['cookie_secure']);
						}
					}
				}
			}
		}

		$redirect_url = append_sid(CMS_PAGE_FORUM . '?' . POST_CAT_URL . '=' . $viewcat);
		meta_refresh(3, $redirect_url);
	}

	$message = $lang['Forums_marked_read'] . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid(CMS_PAGE_FORUM) . '">', '</a> ');

	message_die(GENERAL_MESSAGE, $message);
}
// End handle marking posts

if (($config['display_viewonline'] == 2) || (($viewcat < 0) && ($config['display_viewonline'] == 1)))
{
	define('SHOW_ONLINE_CHAT', true);
	define('SHOW_ONLINE', true);
	if (empty($config['max_topics']) || empty($config['max_posts']) || empty($config['max_users']) || empty($config['last_user_id']))
	{
		board_stats();
	}
	$total_topics = $config['max_topics'];
	$total_posts = $config['max_posts'];
	$total_users = $config['max_users'];
	$newest_user = $cache->obtain_newest_user();

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
	$today_visitors = array();
	$today_visitors = $cache->obtain_today_visitors();

	$today_visitors['admins'] = '<b>' . $lang['Users_Admins'] . ':</b>&nbsp;' . (empty($today_visitors['admins']) ? $lang['None'] : $today_visitors['admins']);
	$today_visitors['mods'] = '<b>' . $lang['Users_Mods'] . ':</b>&nbsp;' . (empty($today_visitors['mods']) ? $lang['None'] : $today_visitors['mods']);
	$today_visitors['users'] = '<b>' . $lang['Users_Regs'] . ':</b>&nbsp;' . (empty($today_visitors['users']) ? $lang['None'] : $today_visitors['users']);
	$l_today_user_s = ($today_visitors['total_users']) ? (($today_visitors['total_users'] == 1)? $lang['User_today_total'] : $lang['Users_today_total']) : $lang['Users_today_zero_total'];
	$l_today_r_user_s = ($today_visitors['reg_visible']) ? (($today_visitors['reg_visible'] == 1) ? $lang['Reg_user_total'] : $lang['Reg_users_total']) : $lang['Reg_users_zero_total'];
	$l_today_h_user_s = ($today_visitors['reg_hidden']) ? (($today_visitors['reg_hidden'] == 1) ? $lang['Hidden_user_total'] : $lang['Hidden_users_total']) : $lang['Hidden_users_zero_total'];
	$l_today_g_user_s = ($today_visitors['total_guests']) ? (($today_visitors['total_guests'] == 1) ? $lang['Guest_user_total'] : $lang['Guest_users_total']) : $lang['Guest_users_zero_total'];
	$l_today_users = sprintf($l_today_user_s, $today_visitors['total_users']);
	$l_today_users .= sprintf($l_today_r_user_s, $today_visitors['reg_visible']);
	$l_today_users .= sprintf($l_today_h_user_s, $today_visitors['reg_hidden']);
	$l_today_users .= sprintf($l_today_g_user_s, $today_visitors['total_guests']);
	$l_today_text = ($today_visitors['last_hour']) ? sprintf($lang['Users_lasthour_explain'], $today_visitors['last_hour']) : $lang['Users_lasthour_none_explain'];
	// Last Visit - END

	// Birthday Box - BEGIN
	if ($config['index_birthday'] && ($config['birthday_check_day'] > 0))
	{
		$template->assign_vars(array('S_BIRTHDAYS' => true));
		$birthdays_list = array();
		@include_once(IP_ROOT_PATH . 'includes/functions_calendar.' . PHP_EXT);
		$birthdays_list = get_birthdays_list_full();
	}
	// Birthday Box - END
}

$avatar_img = user_get_avatar($userdata['user_id'], $userdata['user_level'], $userdata['user_avatar'], $userdata['user_avatar_type'], $userdata['user_allowavatar']);

// Check For Anonymous User
if ($userdata['user_id'] != ANONYMOUS)
{
	$username = colorize_username($userdata['user_id'], $userdata['username'], $userdata['user_color'], $userdata['user_active']);
}
else
{
	$username = $lang['Guest'];
	$avatar_img = '<img src="' . $config['default_avatar_guests_url'] . '" alt="Avatar" />';
}

if ($config['index_links'] == true)
{
	$sql = "SELECT * FROM " . LINK_CONFIG_TABLE;
	$result = $db->sql_query($sql, 0, 'links_config_');
	while($row = $db->sql_fetchrow($result))
	{
		$link_config_name = $row['config_name'];
		$link_config_value = $row['config_value'];
		$link_config[$link_config_name] = $link_config_value;
		$link_self_img = $link_config['site_logo'];
		$site_logo_height = $link_config['height'];
		$site_logo_width = $link_config['width'];
	}
	$template->assign_vars(array('S_LINKS' => true));
	$db->sql_freeresult($result);
}
else
{
	$link_self_img = '';
	$site_logo_height = '';
	$site_logo_width = '';
}

if ($config['site_history'])
{
	$current_time = time();
	$minutes = gmdate('is', $current_time);
	$hour_now = $current_time - (60 * ($minutes[0] . $minutes[1])) - ($minutes[2] . $minutes[3]);
	// change the number late in the next line, to what ever time zone your forum is located, this need to be hard coded in the release of this mod, the number is 1
	$dato = create_date('H', $current_time,1);
	$timetoday = $hour_now - (3600 * $dato);

	$sql = 'SELECT COUNT(DISTINCT session_ip) as guests_today FROM ' . SESSIONS_TABLE . ' WHERE session_user_id="' . ANONYMOUS . '" AND session_time >= ' . $timetoday . ' AND session_time < ' . ($timetoday + 86399);
	$result = $db->sql_query($sql);
	$guest_count = $db->sql_fetchrow($result);

	$sql = 'SELECT user_allow_viewonline, COUNT(*) as count FROM ' . USERS_TABLE . ' WHERE user_id!="' . ANONYMOUS . '" AND user_session_time >= ' . $timetoday . ' AND user_session_time < ' . ($timetoday + 86399) . ' GROUP BY user_allow_viewonline';
	$result = $db->sql_query($sql);
	while ($reg_count = $db->sql_fetchrow ($result))
	{
		if ($reg_count['user_allow_viewonline'])
		{
			$today_visitors['reg_visible'] = $reg_count['count'];
		}
		else
		{
			$today_visitors['reg_hidden'] = $reg_count['count'];
		}
	}
	$db->sql_freeresult($result);

	$sql = 'UPDATE ' . SITE_HISTORY_TABLE . ' SET reg="' . $today_visitors['reg_visible'] . '", hidden="' . $today_visitors['reg_hidden'] . '", guests="' . $guest_count['guests_today'] . '" WHERE date=' . $hour_now;
	$result = $db->sql_query($sql);
	$affectedrows = $db->sql_affectedrows();
	if (!$result || !$affectedrows)
	{
		$sql = 'INSERT IGNORE INTO ' . SITE_HISTORY_TABLE . ' (date, reg, hidden, guests)
			VALUES (' . $hour_now . ', "' . $today_visitors['reg_visible'] . '", "' . $today_visitors['reg_hidden'] . '", "' . $guest_count['guests_today'] . '")';
		$db->sql_query($sql);
	}
	if (isset($result))
	{
		$db->sql_freeresult($result);
	}
}

// set the param of the mark read func
$mark = ($viewcat == -1) ? '' : '&amp;' . POST_CAT_URL . '=' . $viewcat;

if (!$config['board_disable'] || ($config['board_disable'] && ($userdata['user_level'] == ADMIN)))
{
	$template->vars['S_TPL_FILENAME'] = 'index';
}

build_groups_list_template();

//$template->assign_block_vars('google_ad', array());
if ($userdata['session_logged_in'] && !$userdata['is_bot'])
{
	$nav_server_url = create_server_url();
	$breadcrumbs_links_right = '<a href="' . $nav_server_url . append_sid(CMS_PAGE_FORUM . '?mark=forums') . '">' . $lang['Mark_all_forums'] . '</a>&nbsp;' . MENU_SEP_CHAR . '&nbsp;<a href="' . $nav_server_url . append_sid(CMS_PAGE_SEARCH . '?search_id=newposts') . '">' . $lang['Search_new'] . '</a>&nbsp;' . MENU_SEP_CHAR . '&nbsp;<a href="' . $nav_server_url . append_sid(CMS_PAGE_SEARCH . '?search_id=egosearch') . '">' . $lang['Search_your_posts'] . '</a>';
}

$forumindex_banner_element = get_ad('fix');

$template->assign_vars(array(
	'TOTAL_POSTS' => sprintf($l_total_post_s, $total_posts),
	'TOTAL_USERS' => sprintf($l_total_user_s, $total_users),
	//'TOTAL_MALE' => sprintf($l_total_male, $total_male),
	//'TOTAL_FEMALE' => sprintf($l_total_female, $total_female),
	//'TOTAL_UNKNOWN' => sprintf($l_total_unknown, $total_unknown),
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
	'ADMINS_TODAY_LIST' => $today_visitors['admins'],
	'MODS_TODAY_LIST' => $today_visitors['mods'],
	'USERS_TODAY_LIST' => $today_visitors['users'],
	'L_LEGEND' => $lang['legend'],
	'L_USERS' => $lang['users'],
	'L_USERS_LASTHOUR' => ($today_visitors['last_hour']) ? sprintf($lang['Users_lasthour_explain'], $today_visitors['last_hour']) : $lang['Users_lasthour_none_explain'],
	'L_USERS_TODAY' => $l_today_users,
	// End add - Last visit MOD
	// Start add - Birthday MOD
	'L_WHOSBIRTHDAY_WEEK' => ($config['birthday_check_day'] >= 1) ? sprintf((($birthdays_list['xdays']) ? $lang['Birthday_week'] : $lang['Nobirthday_week']), $config['birthday_check_day']) . $birthdays_list['xdays'] : '',
	'L_WHOSBIRTHDAY_TODAY' => ($config['birthday_check_day']) ? ($birthdays_list['today']) ? $lang['Birthday_today'] . $birthdays_list['today'] : $lang['Nobirthday_today'] : '',
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

	'FORUMINDEX_BANNER_ELEMENT' => $forumindex_banner_element,

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
	'U_MARK_READ' => append_sid(CMS_PAGE_FORUM . '?mark=forums' . $mark)
	)
);

// Okay, let's build the index

// Display the board statistics
if (($config['display_viewonline'] == 2) || (($viewcat < 0) && ($config['display_viewonline'] == 1)))
{
	$template->assign_vars(array('S_VIEWONLINE' => true));
	if ($config['index_last_msgs'] == 1)
	{
		$template->assign_block_vars('show_recent', array());

		$except_forums = build_exclusion_forums_list();

		if(!empty($config['last_msgs_x']))
		{
			$except_forums .= ',' . $config['last_msgs_x'];
		}

		$except_forums = str_replace(' ', '', $except_forums);

		$sql = "SELECT t.topic_id, t.topic_title, t.topic_last_post_id, t.forum_id, p.post_id, p.poster_id, p.post_time, u.user_id, u.username
				FROM " . TOPICS_TABLE . " AS t, " . POSTS_TABLE . " AS p, " . USERS_TABLE . " AS u
				WHERE t.forum_id NOT IN (" . $except_forums . ")
					AND t.topic_status <> 2
					AND p.post_id = t.topic_last_post_id
					AND p.poster_id = u.user_id
				ORDER BY p.post_id DESC
				LIMIT " . intval($config['last_msgs_n']);
		$result = $db->sql_query($sql);
		$number_recent_topics = $db->sql_numrows($result);
		$recent_topic_row = array();
		while ($row = $db->sql_fetchrow($result))
		{
			$recent_topic_row[] = $row;
		}
		for ($i = 0; $i < $number_recent_topics; $i++)
		{
			$template->assign_block_vars('show_recent.recent_topic_row', array(
				'U_TITLE' => append_sid(CMS_PAGE_VIEWTOPIC . '?' . POST_POST_URL . '=' . $recent_topic_row[$i]['post_id']) . '#p' . $recent_topic_row[$i]['post_id'],
				'L_TITLE' => $recent_topic_row[$i]['topic_title'],
				'U_POSTER' => append_sid(CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $recent_topic_row[$i]['user_id']),
				'S_POSTER' => $recent_topic_row[$i]['username'],
				'S_POSTTIME' => create_date($config['default_dateformat'], $recent_topic_row[$i]['post_time'], $config['board_timezone'])
				)
			);
		}
		// Recent Topics - END
	}
	if ($config['show_random_quote'] == true)
	{
		$template->assign_block_vars('switch_show_random_quote', array());
	}

	if ($config['show_chat_online'] == true)
	{
		$template->assign_block_vars('switch_ac_online', array());
	}

	if ($config['index_top_posters'] == true)
	{
		include_once(IP_ROOT_PATH . 'includes/functions_users.' . PHP_EXT);
		$template->assign_block_vars('top_posters', array(
			'TOP_POSTERS' => top_posters(8, true, true, false),
			)
		);
	}
}

// Display the index
$display = display_index($viewcatkey);

// check shoutbox permissions and display only to authorized users
$auth_level_req = (isset($cms_config_layouts['shoutbox']['view']) ? $cms_config_layouts['shoutbox']['view'] : AUTH_ALL);
if (($config['index_shoutbox'] && (($userdata['user_level'] + 1) >= $auth_level_req) && $userdata['session_logged_in'] && !$userdata['is_bot']) || ($config['index_shoutbox'] && ($userdata['user_level'] == ADMIN)))
{
	$template->assign_vars(array('S_SHOUTBOX' => true));
}

if (!$display)
{
	message_die(GENERAL_MESSAGE, $lang['No_forums']);
}

// Should the news banner be shown?
if($config['xs_show_news'])
{
	include(IP_ROOT_PATH . 'includes/xs_news.' . PHP_EXT);
	$template->assign_block_vars('switch_show_news', array());
}

$forumindex_banner_top = get_ad('fit');
$forumindex_banner_bottom = get_ad('fib');
$template->assign_vars(array(
	'FORUMINDEX_BANNER_TOP' => $forumindex_banner_top,
	'FORUMINDEX_BANNER_BOTTOM' => $forumindex_banner_bottom,
	)
);

full_page_generation('index_body.tpl', $lang['Forum'], '', '');

?>