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

function get_online_page($page_id)
{
	global $lang;

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
	elseif (strpos($page_id, 'viewonline.' . PHP_EXT) !== false)
	{
		$location['lang'] = $lang['Viewing_online'];
		$location['url'] = 'viewonline.' . PHP_EXT;
		return $location;
	}
	elseif (strpos($page_id, 'memberlist.' . PHP_EXT) !== false)
	{
		$location['lang'] = $lang['Viewing_member_list'];
		$location['url'] = 'memberlist.' . PHP_EXT;
		return $location;
	}
	elseif (strpos($page_id, 'privmsg.' . PHP_EXT) !== false)
	{
		$location['lang'] = $lang['Viewing_priv_msgs'];
		$location['url'] = 'privmsg.' . PHP_EXT;
		return $location;
	}
	elseif (strpos($page_id, 'credits.' . PHP_EXT) !== false)
	{
		$location['lang'] = $lang['Viewing_HACKSLIST'];
		$location['url'] = 'credits.' . PHP_EXT;
		return $location;
	}
	elseif (strpos($page_id, 'faq.' . PHP_EXT) !== false)
	{
		$location['lang'] = $lang['Viewing_FAQ'];
		$location['url'] = 'faq.' . PHP_EXT;
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
	elseif (strpos($page_id, 'recent.' . PHP_EXT) !== false)
	{
		$location['lang'] = $lang['Recent_topics'];
		$location['url'] = 'recent.' . PHP_EXT;
		return $location;
	}
	elseif (strpos($page_id, 'referrers.' . PHP_EXT) !== false)
	{
		$location['lang'] = $lang['Viewing_Referrers'];
		$location['url'] = 'referrers.' . PHP_EXT;
		return $location;
	}
	elseif (strpos($page_id, 'links') !== false)
	{
		$location['lang'] = $lang['Links'];
		$location['url'] = 'links.' . PHP_EXT;
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
		$location['url'] = 'staff.' . PHP_EXT . PHP_EXT;
		return $location;
	}
	elseif (strpos($page_id, 'statistics.' . PHP_EXT) !== false)
	{
		$location['lang'] = $lang['Statistics'];
		$location['url'] = 'statistics.' . PHP_EXT;
		return $location;
	}
	elseif (strpos($page_id, 'site_hist.' . PHP_EXT) !== false)
	{
		$location['lang'] = $lang['Statistics'];
		$location['url'] = 'site_hist.' . PHP_EXT;
		return $location;
	}
	elseif (strpos($page_id, 'attachments.' . PHP_EXT) !== false)
	{
		$location['lang'] = $lang['Downloads'];
		$location['url'] = 'attachments.' . PHP_EXT;
		return $location;
	}
	elseif (strpos($page_id, 'dload.' . PHP_EXT) !== false)
	{
		$location['lang'] = $lang['Downloads'];
		$location['url'] = 'dload.' . PHP_EXT;
		return $location;
	}
	elseif (strpos($page_id, 'calendar.' . PHP_EXT) !== false)
	{
		$location['lang'] = $lang['Calendar'];
		$location['url'] = 'calendar.' . PHP_EXT;
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
	elseif (strpos($page_id, 'calendar.' . PHP_EXT) !== false)
	{
		$location['lang'] = $lang['Calendar'];
		$location['url'] = 'calendar.' . PHP_EXT;
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
		$location['url'] = FORUM_MG;
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
		$location['url'] = PORTAL_MG;
		return $location;
	}
	return $location;
}

?>