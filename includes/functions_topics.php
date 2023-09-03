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

/* functions_separate.php - BEGIN */
/*
* Select topic to be suggested
*/
function get_dividers($topics)
{
	global $lang, $config;
	$dividers = array();
	$total_topics = sizeof($topics);
	$total_by_type = array(POST_GLOBAL_ANNOUNCE => 0, POST_ANNOUNCE => 0, POST_STICKY => 0, POST_NEWS => 0, POST_NORMAL => 0);

	for ($i = 0; $i < $total_topics; $i++)
	{
		$total_by_type[$topics[$i]['topic_type']]++;
	}

	//$config['split_ga_ann_sticky'] = 2;
	$split_options = $config['split_ga_ann_sticky'];

	//split_ga_ann_sticky == 0 -> No split
	//split_ga_ann_sticky == 1 -> Global Announce Announce and Sticky Toghether (not splitted)
	//split_ga_ann_sticky == 2 -> Split global Announce, Announce and Sticky Toghether (splitted)
	//split_ga_ann_sticky == 3 -> All Splitted

	if (($total_by_type[POST_GLOBAL_ANNOUNCE] + $total_by_type[POST_ANNOUNCE] + $total_by_type[POST_STICKY]) != 0)
	{
		$count_topics = 0;

		switch ($split_options)
		{
			case '0':
				break;

			case '1':
				$dividers[$count_topics] = $lang['Announcements_and_Sticky'];
				$count_topics += $total_by_type[POST_ANNOUNCE] + $total_by_type[POST_STICKY] + $total_by_type[POST_GLOBAL_ANNOUNCE];
				break;

			case '2':
				$dividers[$count_topics] = $lang['Global_Announcements'];
				$count_topics += $total_by_type[POST_GLOBAL_ANNOUNCE];

				$dividers[$count_topics] = $lang['Announcements_and_Sticky'];
				$count_topics += $total_by_type[POST_ANNOUNCE] + $total_by_type[POST_STICKY];
				break;

			case '3':
				$dividers[$count_topics] = $lang['Global_Announcements'];
				$count_topics += $total_by_type[POST_GLOBAL_ANNOUNCE];

				$dividers[$count_topics] = $lang['Announcements'];
				$count_topics += $total_by_type[POST_ANNOUNCE];

				$dividers[$count_topics] = $lang['Sticky_Topics'];
				$count_topics += $total_by_type[POST_STICKY];

				break;

		}//end of switch

		if ($count_topics < $total_topics)
		{
			$dividers[$count_topics] = $lang['Topics'];
		}
	}
	return $dividers;
}
/* functions_separate.php - END */

/* functions_bookmark.php - BEGIN */
// Checks whether a bookmark is set or not
function is_bookmark_set($topic_id)
{
	global $db, $user;

	$is_bookmark_set = false;
	$user_id = $user->data['user_id'];
	$sql = "SELECT topic_id, user_id
		FROM " . BOOKMARK_TABLE . "
		WHERE topic_id = " . $topic_id . "
			AND user_id = " . $user_id . "
		LIMIT 1";
	$db->sql_return_on_error(true);
	$result = $db->sql_query($sql);
	$db->sql_return_on_error(false);
	if ($result)
	{
		$is_bookmark_set = ($db->sql_fetchrow($result)) ? true : false;
		$db->sql_freeresult($result);
	}

	return $is_bookmark_set;
}

// Sets a bookmark
function set_bookmark($topic_id)
{
	global $db, $user;
	$user_id = $user->data['user_id'];

	if (!is_bookmark_set($topic_id, $user_id))
	{
		$sql = "INSERT INTO " . BOOKMARK_TABLE . " (topic_id, user_id)
			VALUES (" . $topic_id . ", " . $user_id . ")";
		$db->sql_query($sql);
	}

	return;
}

// Removes a bookmark
function remove_bookmark($topic_id)
{
	global $db, $user;

	$user_id = $user->data['user_id'];
	$sql = "DELETE FROM " . BOOKMARK_TABLE . "
		WHERE topic_id IN (" . $topic_id . ") AND user_id = " . $user_id;
	$db->sql_query($sql);

	return true;
}
/* functions_bookmark.php - END */

/**
* Get similar topics
* If user is guest or bot it will create a cache list in topics table to save some SQL charge
*/
function get_similar_topics($similar_forums_auth, $topic_id, $topic_title, $similar_topics_ids = '', $topic_desc = '')
{
	global $db, $config, $user, $lang;

	$similar_topics = array();
	if(($similar_topics_ids !== '') && (!$user->data['session_logged_in'] || $user->data['is_bot']))
	{
		if($similar_topics_ids == 'empty')
		{
			return $similar_topics;
		}

		$topics_array = $similar_topics_ids;
		$sql = "SELECT t.*, u.user_id, u.username, u.user_active, u.user_color, u2.username as user2, u2.user_id as id2, u2.user_active as user_active2, u2.user_color as user_color2, f.forum_id, f.forum_name, p.post_time, p.post_username
					FROM " . TOPICS_TABLE . " t, " . USERS_TABLE . " u, " . FORUMS_TABLE . " f, " . POSTS_TABLE . " p, " . USERS_TABLE . " u2
					WHERE t.topic_id IN (" . $topics_array . ")
						AND t.forum_id = f.forum_id
						AND p.poster_id = u2.user_id
						AND p.post_id = t.topic_last_post_id
						AND t.topic_poster = u.user_id
						AND t.topic_status <> " . TOPIC_MOVED . "
					GROUP BY t.topic_id
					ORDER BY p.post_time";
		$result = $db->sql_query($sql);
		$similar_topics = $db->sql_fetchrowset($result);
		$db->sql_freeresult($result);
		return $similar_topics;
	}

	if ($config['similar_ignore_forums_ids'])
	{
		$ignore_forums_ids = array_map('intval', explode("\n", trim($config['similar_ignore_forums_ids'])));
	}
	else
	{
		$ignore_forums_ids = array();
	}

	// Get forum auth information to insure privacy of hidden topics
	$forums_auth_sql = '';
	//foreach ($similar_forums_auth as $k=>$v)
	//$similar_forums_auth = auth(AUTH_ALL, AUTH_LIST_ALL, $user->data);
	foreach ($similar_forums_auth as $k => $v)
	{
		if (sizeof($ignore_forums_ids) && in_array($k, $ignore_forums_ids))
		{
			continue;
		}
		if ($v['auth_view'] && $v['auth_read'])
		{
			$forums_auth_sql .= (($forums_auth_sql == '') ? '': ', ') . $k;
		}
	}
	if ($forums_auth_sql != '')
	{
		$forums_auth_sql = ' AND t.forum_id IN (' . $forums_auth_sql . ') ';
	}

	if ($config['similar_stopwords'])
	{
		// encoding match for workaround
		$multibyte_charset = 'utf-8, big5, shift_jis, euc-kr, gb2312';

		// check against stopwords start
		@include_once(IP_ROOT_PATH . 'includes/functions_search.' . PHP_EXT);
		stopwords_synonyms_init();
		$synonyms_array = array();
		// check against stopwords end

		$title_search = '';
		$title_search_array = (!strstr($multibyte_charset, $lang['ENCODING'])) ? split_words(clean_words('post', $topic_title, $stopwords_array, $synonyms_array), 'search') : explode(' ', $topic_title);

		for ($i = 0; $i < sizeof($title_search_array); $i++)
		{
			$title_search .= (($title_search == '') ? '': ' ') . $title_search_array[$i];
		}
	}
	else
	{
		$title_search = $topic_title;
	}

	/*
	if (!empty($topic_desc) && $config['similar_topicdesc'])
	{
		if ($config['similar_stopwords'])
		{
			$topicdesc = '';
			$topic_desc_array = (!strstr($multibyte_charset, $lang['ENCODING'])) ? split_words(clean_words('post', $topic_desc, $stopwords_array, $synonyms_array), 'search') : explode(' ', $topic_desc);
			for ($i = 0; $i < sizeof($topic_desc_array); $i++)
			{
				$topicdesc .= (($topicdesc == '') ? '': ' ') . $topic_desc_array[$i];
			}
		}
		else
		{
			$topicdesc = $topic_desc;
		}
		$sql_topic_desc = "+MATCH(t.topic_desc) AGAINST('" . $db->sql_escape($topicdesc) . "')";
	}

	$sql_match = "MATCH(t.topic_title) AGAINST('" . $db->sql_escape($title_search) . "')" . $sql_topic_desc;
	*/
	$sql_match = "MATCH(t.topic_title) AGAINST('" . $db->sql_escape($title_search) . "')";

	if ($config['similar_sort_type'] == 'time')
	{
		$sql_sort = 'p.post_time';
	}
	else
	{
		$sql_sort = 'relevance';
	}

	//ORDER BY t.topic_type DESC, ' . $sql_sort . ' DESC LIMIT 0,' . intval($config['similar_max_topics']);
	$sql = "SELECT t.*, u.user_id, u.username, u.user_active, u.user_color, u2.username as user2, u2.user_id as id2, u2.user_active as user_active2, u2.user_color as user_color2, f.forum_id, f.forum_name, p.post_time, p.post_username, $sql_match as relevance
				FROM ". TOPICS_TABLE ." t, ". USERS_TABLE ." u, ". FORUMS_TABLE ." f, ". POSTS_TABLE ." p, " . USERS_TABLE . " u2
				WHERE t.topic_id <> $topic_id $forums_auth_sql
					AND $sql_match
					AND t.forum_id = f.forum_id
					AND p.poster_id = u2.user_id
					AND p.post_id = t.topic_last_post_id
					AND t.topic_poster = u.user_id
					AND t.topic_status <> " . TOPIC_MOVED . "
				GROUP BY t.topic_id
				ORDER BY " . $sql_sort . " DESC LIMIT 0," . intval($config['similar_max_topics']);
	$result = $db->sql_query($sql);
	$similar_topics = $db->sql_fetchrowset($result);
	$db->sql_freeresult($result);
	$count_similar = sizeof($similar_topics);

	if(!$user->data['session_logged_in'] || $user->data['is_bot'])
	{
		$similar_ids_array = 'empty';
		if (!empty($count_similar))
		{
			$similar_ids_array = '';
			for ($i = 0; $i < $count_similar; $i++)
			{
				$similar_ids_array .= (empty($similar_ids_array) ? '' : ',') . $similar_topics[$i]['topic_id'];
			}
		}
		$sql = "UPDATE " . TOPICS_TABLE . " SET topic_similar_topics = '" . $similar_ids_array . "' WHERE topic_id = " . $topic_id;
		$result = $db->sql_query($sql);
	}

	return $similar_topics;
}

/**
* Clear similar topics cache
*/
function clear_similar_topics($topic_id = 0)
{
	global $db;

	$sql_where = '';
	if (!empty($topic_id))
	{
		$sql_where = " WHERE topic_id = " . $topic_id;
	}
	$sql = "UPDATE " . TOPICS_TABLE . " SET topic_similar_topics = ''" . $sql_where;
	$result = $db->sql_query($sql);

	return true;
}

/**
* Create clean topic title
*/
function create_clean_topic_title($topic_id = 0, $forum_id = 0, $topic_title = '', $forum_name = '')
{
	global $db, $lang;

	if (empty($topic_id))
	{
		return false;
	}

	if (empty($forum_id))
	{
		$sql = "SELECT forum_id FROM " . TOPICS_TABLE . " WHERE topic_id = " . $topic_id;
		$result = $db->sql_query($sql);

		while ($row = $db->sql_fetchrow($result))
		{
			$forum_id = $row['forum_id'];
		}
		$db->sql_freeresult($result);
	}

	if (empty($forum_id))
	{
		return false;
	}

	if (empty($forum_name))
	{
		$sql = "SELECT * FROM " . FORUMS_TABLE . " WHERE forum_id = " . $forum_id;
		$result = $db->sql_query($sql);

		while ($row = $db->sql_fetchrow($result))
		{
			if (empty($row['forum_name_clean']))
			{
				if (!function_exists('update_clean_forum_name'))
				{
					@include_once(IP_ROOT_PATH . 'includes/functions_admin_forums.' . PHP_EXT);
				}
				$forum_name = substr(ip_clean_string($row['forum_name'], $lang['ENCODING']), 0, 254);
				update_clean_forum_name($row['forum_id'], $forum_name);
			}
			else
			{
				$forum_name = $row['forum_name_clean'];
			}
		}
		$db->sql_freeresult($result);
	}

	if (empty($topic_title))
	{
		$sql = "SELECT * FROM " . TOPICS_TABLE . " WHERE topic_id = " . $topic_id;
		$result = $db->sql_query($sql);

		while ($row = $db->sql_fetchrow($result))
		{
			$topic_title = empty($row['topic_title_clean']) ? $row['topic_title'] : $row['topic_title_clean'];
		}
		$db->sql_freeresult($result);
	}

	$topic_title = substr(ip_clean_string($topic_title, $lang['ENCODING']), 0, 254);
	$forum_name = substr(ip_clean_string($forum_name, $lang['ENCODING']), 0, 254);

	$sql = "UPDATE " . TOPICS_TABLE . " SET topic_title_clean = " . $db->sql_validate_value($topic_title) . ", topic_ftitle_clean = " . $db->sql_validate_value($forum_name) . " WHERE topic_id = " . $topic_id;
	$result = $db->sql_query($sql);

	return true;
}

/**
* Update clean topic title
*/
function update_clean_topic_title($topic_id = 0, $topic_title = '')
{
	global $db;

	if (empty($topic_id) || empty($topic_title))
	{
		return false;
	}
	$sql = "UPDATE " . TOPICS_TABLE . " SET topic_title_clean = " . $db->sql_validate_value($topic_title) . " WHERE topic_id = " . $topic_id;
	$result = $db->sql_query($sql);

	return true;
}

/**
* Clear clean topic title
*/
function clear_clean_topic_title($topic_id = 0, $forum_id = 0)
{
	global $db;

	$sql_where = '';
	if (!empty($topic_id))
	{
		$sql_where = " WHERE topic_id = " . $topic_id;
	}
	elseif (!empty($forum_id))
	{
		$sql_where = " WHERE forum_id = " . $forum_id;
	}
	$sql = "UPDATE " . TOPICS_TABLE . " SET topic_title_clean = '', topic_ftitle_clean = ''" . $sql_where;
	$result = $db->sql_query($sql);

	return true;
}

?>