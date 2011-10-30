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

/*
* get_online_users()
* Global function to get users online, used on index, viewforum, viewonline and ACP
*/
function get_online_users($reg_only, $extra_info, $forum_sql = '')
{
	global $db, $cache, $config, $user, $lang;

	$reg_only_sql = empty($reg_only) ? '' : (" AND u.user_id <> " . ANONYMOUS . " AND s.session_logged_in = 1 ");
	$extra_info_sql = empty($extra_info) ? '' : (", u.user_session_time, u.user_session_page, s.session_start, s.session_page, s.session_forum_id, s.session_topic_id");

	// Changed sorting by username_clean instead of username
	$sql = "SELECT u.user_id, u.username, u.user_active, u.user_color, u.user_allow_viewonline, u.user_level, s.session_logged_in, s.session_ip, s.session_time, s.session_browser" . $extra_info_sql . "
		FROM " . USERS_TABLE . " u, " . SESSIONS_TABLE . " s
		WHERE u.user_id = s.session_user_id
		AND s.session_time >= " . (time() - ONLINE_REFRESH) . "
			$reg_only_sql
			$forum_sql
		ORDER BY u.username_clean ASC, s.session_ip ASC";
	$result = $db->sql_query($sql);
	$online_users = $db->sql_fetchrowset($result);
	$db->sql_freeresult($result);

	return $online_users;
}

/*
* get_online_page()
* Function needed to translate location of users
*/
function get_online_page($page_id)
{
	global $lang;

	if (strpos($page_id, CMS_PAGE_HOME) !== false)
	{
		$location['lang'] = $lang['Portal'];
		$location['url'] = CMS_PAGE_HOME;
		return $location;
	}
	elseif (strpos($page_id, CMS_PAGE_FORUM) !== false)
	{
		$location['lang'] = $lang['Forum_index'];
		$location['url'] = CMS_PAGE_FORUM;
		return $location;
	}
	elseif (strpos($page_id, CMS_PAGE_POSTING) !== false)
	{
		$location['lang'] = $lang['Posting_message'];
		$location['url'] = CMS_PAGE_FORUM;
		return $location;
	}
	elseif (strpos($page_id, CMS_PAGE_LOGIN) !== false)
	{
		$location['lang'] = $lang['Logging_on'];
		$location['url'] = CMS_PAGE_FORUM;
		return $location;
	}
	elseif (strpos($page_id, CMS_PAGE_SEARCH) !== false)
	{
		$location['lang'] = $lang['Searching_forums'];
		$location['url'] = CMS_PAGE_SEARCH;
		return $location;
	}
	elseif ( (strpos($page_id, CMS_PAGE_PROFILE) !== false) || (strpos($page_id, 'profile_') !== false) )
	{
		$location['lang'] = $lang['Viewing_profile'];
		$location['url'] = CMS_PAGE_FORUM;
		return $location;
	}
	elseif (strpos($page_id, CMS_PAGE_VIEWONLINE) !== false)
	{
		$location['lang'] = $lang['Viewing_online'];
		$location['url'] = CMS_PAGE_VIEWONLINE;
		return $location;
	}
	elseif (strpos($page_id, CMS_PAGE_MEMBERLIST) !== false)
	{
		$location['lang'] = $lang['Viewing_member_list'];
		$location['url'] = CMS_PAGE_MEMBERLIST;
		return $location;
	}
	elseif (strpos($page_id, CMS_PAGE_PRIVMSG) !== false)
	{
		$location['lang'] = $lang['Viewing_priv_msgs'];
		$location['url'] = CMS_PAGE_PRIVMSG;
		return $location;
	}
	elseif (strpos($page_id, CMS_PAGE_CREDITS) !== false)
	{
		$location['lang'] = $lang['Viewing_HACKSLIST'];
		$location['url'] = CMS_PAGE_CREDITS;
		return $location;
	}
	elseif (strpos($page_id, CMS_PAGE_FAQ) !== false)
	{
		$location['lang'] = $lang['Viewing_FAQ'];
		$location['url'] = CMS_PAGE_FAQ;
		return $location;
	}
	elseif (strpos($page_id, 'sudoku.' . PHP_EXT) !== false)
	{
		$location['lang'] = $lang['Sudoku'];
		$location['url'] = 'sudoku.' . PHP_EXT;
		return $location;
	}
	elseif (strpos($page_id, 'ajax_') !== false)
	{
		$location['lang'] = $lang['Ajax_Shoutbox'];
		$location['url'] = 'ajax_shoutbox.' . PHP_EXT;
		return $location;
	}
	elseif (strpos($page_id, 'shoutbox') !== false)
	{
		$location['lang'] = $lang['Shoutbox'];
		$location['url'] = 'shoutbox_max.' . PHP_EXT;
		return $location;
	}
	elseif (strpos($page_id, CMS_PAGE_RECENT) !== false)
	{
		$location['lang'] = $lang['Recent_topics'];
		$location['url'] = CMS_PAGE_RECENT;
		return $location;
	}
	elseif (strpos($page_id, CMS_PAGE_REFERERS) !== false)
	{
		$location['lang'] = $lang['VIEWING_REFERERS'];
		$location['url'] = CMS_PAGE_REFERERS;
		return $location;
	}
	elseif (strpos($page_id, 'links') !== false)
	{
		$location['lang'] = $lang['Links'];
		$location['url'] = CMS_PAGE_LINKS;
		return $location;
	}
	elseif (strpos($page_id, 'ranks.' . PHP_EXT) !== false)
	{
		$location['lang'] = $lang['Rank_Header'];
		$location['url'] = 'ranks.' . PHP_EXT;
		return $location;
	}
	elseif (strpos($page_id, 'staff.' . PHP_EXT) !== false)
	{
		$location['lang'] = $lang['Staff'];
		$location['url'] = 'staff.' . PHP_EXT;
		return $location;
	}
	elseif (strpos($page_id, CMS_PAGE_STATISTICS) !== false)
	{
		$location['lang'] = $lang['Statistics'];
		$location['url'] = CMS_PAGE_STATISTICS;
		return $location;
	}
	elseif (strpos($page_id, 'attachments.' . PHP_EXT) !== false)
	{
		$location['lang'] = $lang['Downloads'];
		$location['url'] = 'attachments.' . PHP_EXT;
		return $location;
	}
	elseif (strpos($page_id, CMS_PAGE_DL_DEFAULT) !== false)
	{
		$location['lang'] = $lang['Downloads'];
		$location['url'] = CMS_PAGE_DL_DEFAULT;
		return $location;
	}
	elseif (strpos($page_id, CMS_PAGE_CALENDAR) !== false)
	{
		$location['lang'] = $lang['Calendar'];
		$location['url'] = CMS_PAGE_CALENDAR;
		return $location;
	}
	elseif (strpos($page_id, 'rating.' . PHP_EXT) !== false)
	{
		$location['lang'] = $lang['Rating'];
		$location['url'] = 'rating.' . PHP_EXT;
		return $location;
	}
	elseif (strpos($page_id, 'ratings.' . PHP_EXT) !== false)
	{
		$location['lang'] = $lang['Ratings'];
		$location['url'] = 'ratings.' . PHP_EXT;
		return $location;
	}
	elseif (strpos($page_id, 'kb.' . PHP_EXT) !== false)
	{
		$location['lang'] = $lang['Viewing_KB'];
		$location['url'] = 'kb.' . PHP_EXT;
		return $location;
	}
	elseif (strpos($page_id, 'rss.' . PHP_EXT) !== false)
	{
		$location['lang'] = $lang['Viewing_RSS'];
		$location['url'] = 'rss.' . PHP_EXT;
		return $location;
	}
	elseif (strpos($page_id, 'topic_view_users.' . PHP_EXT) !== false)
	{
		$location['lang'] = $lang['Topic_view_count'];
		$location['url'] = CMS_PAGE_FORUM;
		return $location;
	}
	elseif (strpos($page_id, 'album_allpics.' . PHP_EXT) !== false)
	{
		$location['lang'] = $lang['View_Album_Index'];
		$location['url'] = 'album_allpics.' . PHP_EXT;
		return $location;
	}
	elseif (strpos($page_id, 'album_hotornot.' . PHP_EXT) !== false)
	{
		$location['lang'] = $lang['View_Album_Index'];
		$location['url'] = 'album_hotornot.' . PHP_EXT;
		return $location;
	}
	elseif (strpos($page_id, 'album_otf.' . PHP_EXT) !== false)
	{
		$location['lang'] = $lang['View_Album_Index'];
		$location['url'] = 'album_otf.' . PHP_EXT;
		return $location;
	}
	elseif (strpos($page_id, 'album_rss.' . PHP_EXT) !== false)
	{
		$location['lang'] = $lang['View_Album_Index'];
		$location['url'] = 'album_rss.' . PHP_EXT;
		return $location;
	}
	elseif (strpos($page_id, 'album_rdf.' . PHP_EXT) !== false)
	{
		$location['lang'] = $lang['View_Album_Index'];
		$location['url'] = 'album_rdf.' . PHP_EXT;
		return $location;
	}
	elseif (strpos($page_id, 'album_search.' . PHP_EXT) !== false)
	{
		$location['lang'] = $lang['Album_Search'];
		$location['url'] = 'album_search.' . PHP_EXT;
		return $location;
	}
	elseif (strpos($page_id, 'album_personal_index.' . PHP_EXT) !== false)
	{
		$location['lang'] = $lang['View_Album_Personal'];
		$location['url'] = 'album_personal_index.' . PHP_EXT;
		return $location;
	}
	elseif (strpos($page_id, 'album_showpage.' . PHP_EXT) !== false)
	{
		$location['lang'] = $lang['View_Pictures'];
		$location['url'] = 'album.' . PHP_EXT;
		return $location;
	}
	elseif (strpos($page_id, 'album') !== false)
	{
		$location['lang'] = $lang['View_Album_Index'];
		$location['url'] = 'album.' . PHP_EXT;
		return $location;
	}
	elseif (strpos($page_id, 'customicy') !== false)
	{
		$location['lang'] = $lang['CustomIcy'];
		$location['url'] = 'customicy_avatars.' . PHP_EXT;
		return $location;
	}
	elseif (strpos($page_id, 'activity') !== false)
	{
		$location['lang'] = $lang['Activity'];
		$location['url'] = 'activity.' . PHP_EXT;
		return $location;
	}
	else
	{
		$location['lang'] = $lang['Portal'];
		$location['url'] = CMS_PAGE_HOME;
		return $location;
	}
	return $location;
}

?>