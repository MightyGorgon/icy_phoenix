<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

if (!defined('IN_PHPBB'))
{
	die('Hacking attempt');
}

function get_online_page($page_id)
{
	global $lang, $phpEx;

	if (strpos($page_id, PORTAL_MG) !== false)
	{
		$location['lang'] = $lang['Portal'];
		$location['url'] = PORTAL_MG;
		return $location;
	}
	elseif (strpos($page_id, FORUM_MG) !== false)
	{
		$location['lang'] = $lang['Forum_index'];
		$location['url'] = FORUM_MG;
		return $location;
	}
	elseif (strpos($page_id, POSTING_MG) !== false)
	{
		$location['lang'] = $lang['Posting_message'];
		$location['url'] = FORUM_MG;
		return $location;
	}
	elseif (strpos($page_id, LOGIN_MG) !== false)
	{
		$location['lang'] = $lang['Logging_on'];
		$location['url'] = FORUM_MG;
		return $location;
	}
	elseif (strpos($page_id, SEARCH_MG) !== false)
	{
		$location['lang'] = $lang['Searching_forums'];
		$location['url'] = SEARCH_MG;
		return $location;
	}
	elseif ( (strpos($page_id, PROFILE_MG) !== false) || (strpos($page_id, 'profile_') !== false) )
	{
		$location['lang'] = $lang['Viewing_profile'];
		$location['url'] = FORUM_MG;
		return $location;
	}
	elseif (strpos($page_id, 'viewonline.' . $phpEx) !== false)
	{
		$location['lang'] = $lang['Viewing_online'];
		$location['url'] = 'viewonline.' . $phpEx;
		return $location;
	}
	elseif (strpos($page_id, 'memberlist.' . $phpEx) !== false)
	{
		$location['lang'] = $lang['Viewing_member_list'];
		$location['url'] = 'memberlist.' . $phpEx;
		return $location;
	}
	elseif (strpos($page_id, 'privmsg.' . $phpEx) !== false)
	{
		$location['lang'] = $lang['Viewing_priv_msgs'];
		$location['url'] = 'privmsg.' . $phpEx;
		return $location;
	}
	elseif (strpos($page_id, 'credits.' . $phpEx) !== false)
	{
		$location['lang'] = $lang['Viewing_HACKSLIST'];
		$location['url'] = 'credits.' . $phpEx;
		return $location;
	}
	elseif (strpos($page_id, 'faq.' . $phpEx) !== false)
	{
		$location['lang'] = $lang['Viewing_FAQ'];
		$location['url'] = 'faq.' . $phpEx;
		return $location;
	}
	elseif (strpos($page_id, 'sudoku.' . $phpEx) !== false)
	{
		$location['lang'] = $lang['Sudoku'];
		$location['url'] = 'sudoku.' . $phpEx;
		return $location;
	}
	elseif (strpos($page_id, 'ajax_') !== false)
	{
		$location['lang'] = $lang['Ajax_Shoutbox'];
		$location['url'] = 'ajax_shoutbox.' . $phpEx;
		return $location;
	}
	elseif (strpos($page_id, 'shoutbox') !== false)
	{
		$location['lang'] = $lang['Shoutbox'];
		$location['url'] = 'shoutbox_max.' . $phpEx;
		return $location;
	}
	elseif (strpos($page_id, 'recent.' . $phpEx) !== false)
	{
		$location['lang'] = $lang['Recent_topics'];
		$location['url'] = 'recent.' . $phpEx;
		return $location;
	}
	elseif (strpos($page_id, 'referrers.' . $phpEx) !== false)
	{
		$location['lang'] = $lang['Viewing_Referrers'];
		$location['url'] = 'referrers.' . $phpEx;
		return $location;
	}
	elseif (strpos($page_id, 'links') !== false)
	{
		$location['lang'] = $lang['Links'];
		$location['url'] = 'links.' . $phpEx;
		return $location;
	}
	elseif (strpos($page_id, 'ranks.' . $phpEx) !== false)
	{
		$location['lang'] = $lang['Rank_Header'];
		$location['url'] = 'ranks.' . $phpEx;
		return $location;
	}
	elseif (strpos($page_id, 'staff.' . $phpEx) !== false)
	{
		$location['lang'] = $lang['Staff'];
		$location['url'] = 'staff.' . $phpEx . $phpEx;
		return $location;
	}
	elseif (strpos($page_id, 'statistics.' . $phpEx) !== false)
	{
		$location['lang'] = $lang['Statistics'];
		$location['url'] = 'statistics.' . $phpEx;
		return $location;
	}
	elseif (strpos($page_id, 'site_hist.' . $phpEx) !== false)
	{
		$location['lang'] = $lang['Statistics'];
		$location['url'] = 'site_hist.' . $phpEx;
		return $location;
	}
	elseif (strpos($page_id, 'attachments.' . $phpEx) !== false)
	{
		$location['lang'] = $lang['Downloads'];
		$location['url'] = 'attachments.' . $phpEx;
		return $location;
	}
	elseif (strpos($page_id, 'dload.' . $phpEx) !== false)
	{
		$location['lang'] = $lang['Downloads'];
		$location['url'] = 'dload.' . $phpEx;
		return $location;
	}
	elseif (strpos($page_id, 'calendar.' . $phpEx) !== false)
	{
		$location['lang'] = $lang['Calendar'];
		$location['url'] = 'calendar.' . $phpEx;
		return $location;
	}
	elseif (strpos($page_id, 'rating.' . $phpEx) !== false)
	{
		$location['lang'] = $lang['Rating'];
		$location['url'] = 'rating.' . $phpEx;
		return $location;
	}
	elseif (strpos($page_id, 'ratings.' . $phpEx) !== false)
	{
		$location['lang'] = $lang['Ratings'];
		$location['url'] = 'ratings.' . $phpEx;
		return $location;
	}
	elseif (strpos($page_id, 'calendar.' . $phpEx) !== false)
	{
		$location['lang'] = $lang['Calendar'];
		$location['url'] = 'calendar.' . $phpEx;
		return $location;
	}
	elseif (strpos($page_id, 'kb.' . $phpEx) !== false)
	{
		$location['lang'] = $lang['Viewing_KB'];
		$location['url'] = 'kb.' . $phpEx;
		return $location;
	}
	elseif (strpos($page_id, 'rss.' . $phpEx) !== false)
	{
		$location['lang'] = $lang['Viewing_RSS'];
		$location['url'] = 'rss.' . $phpEx;
		return $location;
	}
	elseif (strpos($page_id, 'topic_view_users.' . $phpEx) !== false)
	{
		$location['lang'] = $lang['Topic_view_count'];
		$location['url'] = FORUM_MG;
		return $location;
	}
	elseif (strpos($page_id, 'album_allpics.' . $phpEx) !== false)
	{
		$location['lang'] = $lang['View_Album_Index'];
		$location['url'] = 'album_allpics.' . $phpEx;
		return $location;
	}
	elseif (strpos($page_id, 'album_hotornot.' . $phpEx) !== false)
	{
		$location['lang'] = $lang['View_Album_Index'];
		$location['url'] = 'album_hotornot.' . $phpEx;
		return $location;
	}
	elseif (strpos($page_id, 'album_otf.' . $phpEx) !== false)
	{
		$location['lang'] = $lang['View_Album_Index'];
		$location['url'] = 'album_otf.' . $phpEx;
		return $location;
	}
	elseif (strpos($page_id, 'album_rss.' . $phpEx) !== false)
	{
		$location['lang'] = $lang['View_Album_Index'];
		$location['url'] = 'album_rss.' . $phpEx;
		return $location;
	}
	elseif (strpos($page_id, 'album_rdf.' . $phpEx) !== false)
	{
		$location['lang'] = $lang['View_Album_Index'];
		$location['url'] = 'album_rdf.' . $phpEx;
		return $location;
	}
	elseif (strpos($page_id, 'album_search.' . $phpEx) !== false)
	{
		$location['lang'] = $lang['Album_Search'];
		$location['url'] = 'album_search.' . $phpEx;
		return $location;
	}
	elseif (strpos($page_id, 'album_personal_index.' . $phpEx) !== false)
	{
		$location['lang'] = $lang['View_Album_Personal'];
		$location['url'] = 'album_personal_index.' . $phpEx;
		return $location;
	}
	elseif (strpos($page_id, 'album_showpage.' . $phpEx) !== false)
	{
		$location['lang'] = $lang['View_Pictures'];
		$location['url'] = 'album.' . $phpEx;
		return $location;
	}
	elseif (strpos($page_id, 'album') !== false)
	{
		$location['lang'] = $lang['View_Album_Index'];
		$location['url'] = 'album.' . $phpEx;
		return $location;
	}
	elseif (strpos($page_id, 'customicy') !== false)
	{
		$location['lang'] = $lang['CustomIcy'];
		$location['url'] = 'customicy_avatars.' . $phpEx;
		return $location;
	}
	elseif (strpos($page_id, 'activity') !== false)
	{
		$location['lang'] = $lang['Activity'];
		$location['url'] = 'activity.' . $phpEx;
		return $location;
	}
	else
	{
		$location['lang'] = $lang['Portal'];
		$location['url'] = PORTAL_MG;
		return $location;
	}
	return $location;
}

?>