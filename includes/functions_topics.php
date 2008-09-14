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
//
// Select topic to be suggested
//
function get_dividers($topics)
{
	global $lang, $board_config;
	$dividers = array();
	$total_topics = count($topics);
	$total_by_type = array (POST_GLOBAL_ANNOUNCE => 0, POST_ANNOUNCE => 0, POST_STICKY => 0, POST_NORMAL => 0);

	for ($i = 0; $i < $total_topics; $i++)
	{
		$total_by_type[$topics[$i]['topic_type']]++;
	}

	//$board_config['split_ga_ann_sticky'] = 2;
	$split_options = $board_config['split_ga_ann_sticky'];

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

/* functions_ftr.php - END */
function GetUsersView($user)
{
		global $db, $table_prefix;
		$q1 = "SELECT * FROM ". FORCE_READ_USERS_TABLE . "
					WHERE user = '$user'";
		$r1 = $db -> sql_query($q1);
		$row1 = $db -> sql_fetchrow($r1);
		$user = $row1['user'];
		$read = $row1['read'];
		if(($user) && ($read == "1"))
		{
			$viewed = 'true';
		}
		else
		{
			$viewed = 'false';
		}

	return $viewed;
}

function InsertReadTopic($user)
{
	global $db, $table_prefix;
	$time = time();
	$q = "INSERT INTO ". FORCE_READ_USERS_TABLE . "
			VALUES ('$user', '1', '$time')";
	$r = $db -> sql_query($q);
	return;
}
/* functions_ftr.php - END */

function build_topic_icon_link($forum_id, $topic_id, $topic_type, $topic_replies, $topic_news_id, $topic_vote, $topic_status, $topic_moved_id, $topic_post_time, $user_replied, $replies, $unread)
{
	//build_topic_icon_link($forum_id, $topic_rowset[$i]['topic_id'], $topic_rowset[$i]['topic_type'], $topic_rowset[$i]['topic_replies'], $topic_rowset[$i]['news_id'], $topic_rowset[$i]['topic_vote'], $topic_rowset[$i]['topic_status'], $topic_rowset[$i]['topic_moved_id'], $topic_rowset[$i]['post_time'], $user_replied, $replies, $unread);
	global $board_config, $lang, $images, $userdata, $tracking_topics, $tracking_forums, $forum_id_append, $topic_id_append;

	$topic_link = array();
	$topic_link['forum_id_append'] = $forum_id_append;
	$topic_link['topic_id_append'] = $topic_id_append;
	$topic_link['topic_id'] = $topic_id;
	$topic_link['type'] = '';
	$topic_link['icon'] = '';
	$topic_link['class'] = 'topiclink';
	$topic_link['class_new'] = '';
	$topic_link['image'] = '';
	$topic_link['image_read'] = '';
	$topic_link['image_unread'] = '';
	$topic_link['image_alt'] = '';
	$topic_link['newest_post_img'] = '';
	$upi_calc['upi_prefix'] = '';
	$upi_calc['newest_post_id'] = '';
	$icon_prefix = '';
	$icon_locked = ($topic_status == TOPIC_LOCKED) ? '_locked' : '';
	$icon_own = $user_replied ? '_own' : '';

	if($topic_status == TOPIC_MOVED)
	{
		$topic_link['type'] = $lang['Topic_Moved'] . ' ';
		$topic_link['topic_id'] = $topic_moved_id;
		$topic_link['topic_id_append'] = POST_TOPIC_URL . '=' . $topic_moved_id;
		$topic_link['forum_id_append'] = '';
		$topic_link['image_alt'] = $lang['Topics_Moved'];
		$topic_link['newest_post_img'] = '';
		$topic_link['class'] = 'topiclink';
		$icon_prefix = 'topic_nor';
		$icon_locked = '_locked';
		$topic_link['image_read'] = $images[$icon_prefix . $icon_locked . '_read' . $icon_own];
		$topic_link['image_unread'] = $images[$icon_prefix . $icon_locked . '_unread' . $icon_own];
	}
	else
	{
		if($topic_type == POST_GLOBAL_ANNOUNCE)
		{
			$topic_link['type'] = $lang['Topic_global_announcement'] . ' ';
			$topic_link['icon'] = '<img src="' . $images['vf_topic_ga'] . '" alt="' . $lang['Topic_global_announcement_nb'] . '" title="' . $lang['Topic_global_announcement_nb'] . '" /> ';
			$topic_link['class'] = 'topic_glo';
			$icon_prefix = 'topic_glo';
		}
		elseif($topic_type == POST_ANNOUNCE)
		{
			$topic_link['type'] = $lang['Topic_Announcement'] . ' ';
			$topic_link['icon'] = '<img src="' . $images['vf_topic_ann'] . '" alt="' . $lang['Topic_Announcement_nb'] . '" title="' . $lang['Topic_Announcement_nb'] . '" /> ';
			$topic_link['class'] = 'topic_ann';
			$icon_prefix = 'topic_ann';
		}
		elseif($topic_type == POST_STICKY)
		{
			$topic_link['type'] = $lang['Topic_Sticky'] . ' ';
			$topic_link['icon'] = '<img src="' . $images['vf_topic_imp'] . '" alt="' . $lang['Topic_Sticky_nb'] . '" title="' . $lang['Topic_Sticky_nb'] . '" /> ';
			$topic_link['class'] = 'topic_imp';
			$icon_prefix = 'topic_imp';
		}
		else
		{
			$topic_link['type'] = '';
			$topic_link['icon'] = '<img src="' . $images['vf_topic_nor'] . '" alt="' . $lang['Topic'] . '" title="' . $lang['Topic'] . '" /> ';
			$topic_link['class'] = 'topiclink';
			if($replies >= $board_config['hot_threshold'])
			{
				$icon_prefix = 'topic_hot';
			}
			else
			{
				$icon_prefix = 'topic_nor';
			}
		}

		$topic_link['image_read'] = $images[$icon_prefix . $icon_locked . '_read' . $icon_own];
		$topic_link['image_unread'] = $images[$icon_prefix . $icon_locked . '_unread' . $icon_own];

		if ($topic_news_id > 0)
		{
			//$topic_link['type'] = $lang['News_Cmx'] . ' ';
			$topic_link['type'] = '<img src="' . $images['vf_topic_news'] . '" alt="' . $lang['Topic_News_nb'] . '" title="' . $lang['Topic_News_nb'] . '" /> ' . $topic_link['type'];
			$topic_link['icon'] = $topic_link['icon'] . '<img src="' . $images['vf_topic_news'] . '" alt="' . $lang['Topic_News_nb'] . '" title="' . $lang['Topic_News_nb'] . '" /> ';
		}

		if($topic_vote)
		{
			//$topic_link['type'] .= $lang['Topic_Poll'] . ' ';
			$topic_link['type'] .= '<img src="' . $images['vf_topic_poll'] . '" alt="' . $lang['Topic_Poll_nb'] . '" title="' . $lang['Topic_Poll_nb'] . '" /> ';
			$topic_link['icon'] .= '<img src="' . $images['vf_topic_poll'] . '" alt="' . $lang['Topic_Poll_nb'] . '" title="' . $lang['Topic_Poll_nb'] . '" /> ';
		}
	}

	if($userdata['session_logged_in'])
	{
		//-----------------------------------------------------------
		//<!-- BEGIN Unread Post Information to Database Mod -->
		if(!$userdata['upi2db_access'] || !is_array($unread))
		{
		//<!-- END Unread Post Information to Database Mod -->
		//------------------------------------------------------------

			if($topic_post_time > $userdata['user_lastvisit'])
			{
				if(!empty($tracking_topics) || !empty($tracking_forums) || isset($_COOKIE[$board_config['cookie_name'] . '_f_all']))
				{
					$unread_topics = true;

					if(!empty($tracking_topics[$topic_link['topic_id']]))
					{
						if($tracking_topics[$topic_link['topic_id']] >= $topic_post_time)
						{
							$unread_topics = false;
						}
					}

					if(!empty($tracking_forums[$forum_id]))
					{
						if($tracking_forums[$forum_id] >= $topic_post_time)
						{
							$unread_topics = false;
						}
					}

					if(isset($_COOKIE[$board_config['cookie_name'] . '_f_all']))
					{
						if($_COOKIE[$board_config['cookie_name'] . '_f_all'] >= $topic_post_time)
						{
							$unread_topics = false;
						}
					}
				}
				else
				{
					$unread_topics = true;
				}
			}
			else
			{
				$unread_topics = false;
			}
		//--------------------------------------------------------
		//<!-- BEGIN Unread Post Information to Database Mod -->
		}
		else
		{
			$upi_calc = upi_calc_unread_simple($unread, $topic_link['topic_id']);
			$unread_topics = $upi_calc['unread'];
			//$topic_link['type'] = $upi_calc['upi_prefix'] . $topic_link['type'];
			$upi_calc['newest_post_id'] = $post_id;
		}
		//<!-- END Unread Post Information to Database Mod -->
		//--------------------------------------------------------
	}
	else
	{
		$unread_topics = false;
	}

	if($unread_topics == true)
	{
		$topic_link['class_new'] = '-new';
		$topic_link['image'] = $topic_link['image_unread'];
		$topic_link['image_alt'] = ($topic_status == TOPIC_LOCKED) ? $lang['Topic_locked'] : $lang['New_posts'];
		$topic_link['newest_post_img'] = '';
		if (empty($upi_calc['newest_post_id']))
		{
			$newest_post_img_url = append_sid(VIEWTOPIC_MG . '?' . $topic_link['forum_id_append'] . '&amp;' . $topic_link['topic_id_append'] . '&amp;view=newest');
		}
		else
		{
			$newest_post_img_url = append_sid(VIEWTOPIC_MG . '?' . $topic_link['forum_id_append'] . '&amp;' . $topic_link['topic_id_append'] . '&amp;' . POST_POST_URL . '=' . $upi_calc['newest_post_id']) . '#p' . $upi_calc['newest_post_id'];
		}
		$topic_link['newest_post_img'] = '<a href="' . $newest_post_img_url . '"><img src="' . $images['icon_newest_reply'] . '" alt="' . $lang['View_newest_post'] . '" title="' . $lang['View_newest_post'] . '" /></a> ' . $upi_calc['upi_prefix'];
	}
	else
	{
		$topic_link['class_new'] = '';
		$topic_link['image'] = $topic_link['image_read'];
		$topic_link['image_alt'] = ($topic_status == TOPIC_LOCKED) ? $lang['Topic_locked'] : $lang['No_new_posts'];
		$topic_link['newest_post_img'] = '';
	}
	return $topic_link;
}

function upi_calc_unread_simple($unread, $topic_id)
{
	global $userdata, $lang;

	$upi2db_status = '';
	if (in_array($topic_id, $unread['new_topics']) || in_array($topic_id, $unread['edit_topics']))
	{
		if((in_array($topic_id, $unread['new_topics']) && in_array($topic_id, $unread['edit_topics'])) && $userdata['user_upi2db_new_word'] && $userdata['user_upi2db_edit_word'])
		{
			$upi2db_status = $lang['upi2db_post_edit'] . $lang['upi2db_post_and'] . $lang['upi2db_post_new'] . ': ';
		}
		else
		{
			if(in_array($topic_id, $unread['new_topics']) && $userdata['user_upi2db_new_word'])
			{
				$upi2db_status = $lang['upi2db_post_new'] . ': ';
			}

			if(in_array($topic_id, $unread['edit_topics']) && $userdata['user_upi2db_edit_word'])
			{
				$upi2db_status = $lang['upi2db_post_edit'] . ': ';
			}
		}
		$min_new_post_id = (empty($unread[$topic_id]['new_posts'])) ? '99999999' : min($unread[$topic_id]['new_posts']);
		$min_edit_post_id = (empty($unread[$topic_id]['edit_posts'])) ? '99999999' :  min($unread[$topic_id]['edit_posts']);
		$post_id = ($min_edit_post_id >= $min_new_post_id) ? $min_new_post_id : $min_edit_post_id;
		$upi_calc['unread'] = true;
		$upi_calc['upi_prefix'] = $upi2db_status;
		$upi_calc['newest_post_id'] = $post_id;
	}
	else
	{
		$upi_calc['unread'] = false;
		$upi_calc['upi_prefix'] = '';
		$upi_calc['newest_post_id'] = '';
	}
	return $upi_calc;
}

function user_replied_array($topic_rowset)
{
	global $userdata, $db, $board_config;
	$user_topics = array();
	if (($userdata['user_id'] != ANONYMOUS) && ($board_config['enable_own_icons'] == true))
	{
		// get all the topic ids to display
		$topic_ids = array();
		for ($i = 0; $i < count($topic_rowset); $i++)
		{
			$topic_ids[] = intval(substr($topic_rowset[$i]['topic_id'], 0));
		}
		// check if the user replied to
		if (!empty($topic_ids))
		{
			// check the posts
			$s_topic_ids = implode(', ', $topic_ids);
			$sql = "SELECT DISTINCT topic_id FROM " . POSTS_TABLE . "
					WHERE topic_id IN ($s_topic_ids)
						AND poster_id = '" . $userdata['user_id'] . "'";
			if (!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'Could not obtain post information', '', __LINE__, __FILE__, $sql);
			}
			while ($row = $db->sql_fetchrow($result))
			{
				$user_topics[$row['topic_id']] = true;
			}
			$db->sql_freeresult($result);
		}
	}
	return $user_topics;
}

/*
* $topic_prefixes = get_topic_prefixes();
*/
function get_topic_prefixes()
{
	global $db;
	$sql = "SELECT * FROM " . TITLE_INFOS_TABLE . " ORDER BY title_info ASC";
	if (!($result = $db->sql_query($sql, false, 'topics_prefixes_')))
	{
		message_die(GENERAL_MESSAGE, 'Unable to query Quick Title Addon informations.');
	}
	$topic_prefixes = array();
	while ($row = $db->sql_fetchrow($result))
	{
		$topic_prefixes[$row['id']] = $row['title_info'];
	}
	$db->sql_freeresult($result);
	return $topic_prefixes;
}

/* functions_bookmark.php - BEGIN */
// Checks whether a bookmark is set or not
function is_bookmark_set($topic_id)
{
	global $db, $userdata;

	$user_id = $userdata['user_id'];
	$sql = "SELECT topic_id, user_id
		FROM " . BOOKMARK_TABLE . "
		WHERE topic_id = '" . $topic_id . "'
			AND user_id = '" . $user_id . "'
		LIMIT 1";
	if ($result = $db->sql_query($sql))
	{
		$is_bookmark_set = ($db->sql_fetchrow($result)) ? (true) : (false);
	}
	else
	{
		message_die(GENERAL_ERROR, 'Could not obtain bookmark information', '', __LINE__, __FILE__, $sql);
		$is_bookmark_set = false;
	}
	$db->sql_freeresult($result);

	return $is_bookmark_set;
}

// Sets a bookmark
function set_bookmark($topic_id)
{
	global $db, $userdata;

	$user_id = $userdata['user_id'];
	if (!is_bookmark_set($topic_id, $user_id))
	{
		$sql = "INSERT INTO " . BOOKMARK_TABLE . " (topic_id, user_id)
			VALUES ($topic_id, $user_id)";
		if (!$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not insert bookmark information', '', __LINE__, __FILE__, $sql);
		}
	}
	return;
}

// Removes a bookmark
function remove_bookmark($topic_id)
{
	global $db, $userdata;

	$user_id = $userdata['user_id'];
	$sql = "DELETE FROM " . BOOKMARK_TABLE . "
		WHERE topic_id IN ($topic_id) AND user_id = $user_id";
	if (!$db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not remove bookmark information', '', __LINE__, __FILE__, $sql);
	}
	return;
}
/* functions_bookmark.php - END */

?>