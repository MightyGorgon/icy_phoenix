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
* @Extra credits for this file
* BigRib (bigrib@gmx.de)
*
*/

/***************************************************************************
 *
 *   Included Functions:
 *   -------------------
 *
 * sync_database
 * select_always_read
 * delete_read_posts
 * except_time
 * viewforum_calc_unread
 * marking_posts
 * auth_forum_read
 * upi2db_faq_include
 * prune_upi2db
 *
 ***************************************************************************/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

//################################### sync_database ##########################################
// Version 1.0.0

if(!function_exists(sync_database))
{
	function sync_database($userdata)
	{
		global $board_config, $db;

		$time = time();

		if($userdata['user_upi2db_datasync'] > ($time - 10))
		{
			return;
		}

		$expired_post_time = $time - ($board_config['upi2db_auto_read'] * 86400);
		$del_mark_time = $time - ($board_config['upi2db_del_mark'] * 86400);
		$del_perm_time = $time - ($board_config['upi2db_del_perm'] * 86400);

		$always_read = $userdata['always_read'];
		$auth_forum_id = $userdata['auth_forum_id'];
		$always_read_forums = '';
		$always_read_topics = '';
		$user_id = $userdata['user_id'];
		$user_dbsync = $userdata['user_upi2db_datasync'];

		if(date('Ymd',$board_config['upi2db_delete_old_data']) != date('Ymd',time()))
		{
			delete_old_data($expired_post_time, $del_mark_time, $del_perm_time, $db);
		}

		if($always_read)
		{
			$always_read_forums = (count($always_read['forums']) == 1)  ? $always_read['forums'][0] : implode(',', $always_read['forums']);
			$always_read_topics = (count($always_read['topics']) == 1)  ? $always_read['topics'][0] : implode(',', $always_read['topics']);
		}

		$ar_forums = ($always_read_forums) ? 'AND forum_id NOT IN ('. $always_read_forums .')' : '';
		$ar_topics = ($always_read_topics) ? 'AND topic_id NOT IN ('. $always_read_topics .')' : '';
		$auth_forum = ($auth_forum_id) ? 'AND forum_id IN ('. $auth_forum_id .')' : '';
		$max_new_post = ($userdata['user_level'] != ADMIN) ? (($userdata['user_level'] != MOD) ? $board_config['upi2db_max_new_posts'] : $board_config['upi2db_max_new_posts_mod']): $board_config['upi2db_max_new_posts_admin'];
		// Edited By Mighty Gorgon - BEGIN
		$max_new_posts = ($max_new_posts == 0) ? 999999 : $max_new_posts;
		$new_post_limit = ($max_new_post > 0) ? 'ORDER BY post_time DESC, post_edit_time DESC LIMIT ' . $max_new_post : 'ORDER BY post_time DESC, post_edit_time DESC';
		// Edited By Mighty Gorgon - END
		$dbsync = ($user_dbsync < $userdata['user_regdate']) ? $userdata['user_regdate'] : $user_dbsync;
		$copy_annoncments = (empty($user_dbsync)) ? 'OR topic_type != 0' : '';


		$sql = "SELECT post_id, topic_id, forum_id, user_id, status FROM " . UPI2DB_UNREAD_POSTS_TABLE . "
			WHERE user_id = '" . $userdata['user_id'] . "'
			AND status != 2";

		$post_ids = array();

		if ($result = $db->sql_query($sql))
		{
			while($read = $db->sql_fetchrow($result))
			{
				if (!in_array($read['post_id'],$post_ids))
				{
					$post_ids[] = $read['post_id'];
				}
			}
		}
		$post_ids = implode(',', $post_ids);
		$no_post_ids = ($post_ids) ? 'AND post_id NOT IN (' . $post_ids . ')' : '';

// Mal testen --> INSERT DELAYED INTO

		$sql = "INSERT INTO " . UPI2DB_UNREAD_POSTS_TABLE . " (user_id, post_id, topic_id, forum_id, topic_type, status, last_update)
			SELECT " . $user_id . " AS user_id, post_id, topic_id, forum_id, topic_type, IF(post_edit_time > " . $dbsync . " && post_time < " . $dbsync . ", 1, 0) AS status, " . $time . " AS last_update
			FROM " . UPI2DB_LAST_POSTS_TABLE . "
			WHERE ((post_time > " . $dbsync . " OR post_edit_time > " . $dbsync . ") " . $copy_annoncments . ")
				AND ((poster_id != '" . $user_id . "') OR (poster_id = '" . $user_id . "' && post_edit_by != poster_id))
				$no_post_ids
				$auth_forum
				$ar_forums
				$ar_topics
				$new_post_limit";
		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Could not copy unread data", '', __LINE__, __FILE__, $sql);
		}
		else
		{
			$sql = "UPDATE " . USERS_TABLE . " SET user_upi2db_datasync = " . time() . "
				WHERE user_id = '" . $user_id . "'";
			$db->sql_query($sql);
			$userdata['db_sync'] = '1';
		}
	}
}

//################################### select_always_read ##########################################
// Version 1.0.0

if(!function_exists(select_always_read))
{
	function select_always_read($userdata)
	{
		global $db;
		$always_read['topics'] = array();
		$always_read['forums'] = array();
		$user_id = $userdata['user_id'];

		$sql = "SELECT topic_id, forum_id FROM " . UPI2DB_ALWAYS_READ_TABLE . "
			WHERE user_id = '" . $user_id . "'";
		if ($result = $db->sql_query($sql))
		{
			while($read = $db->sql_fetchrow($result))
			{
				if($read['topic_id'] != 0)
				{
					if (!in_array($read['topic_id'],$always_read['topics']))
					{
						$always_read['topics'][] = $read['topic_id'];
					}
				}
				if($read['forum_id'] != 0)
				{
					if (!in_array($read['forum_id'],$always_read['forums']))
					{
						$always_read['forums'][] = $read['forum_id'];
					}
				}
			}
		}
		$db->sql_freeresult($result);
		return $always_read;
	}
}

//################################### delete_read_posts ##########################################
// Version 1.0.0

if(!function_exists(delete_read_posts))
{
	function delete_read_posts($read_posts)
	{
		global $userdata, $db;

		$sql = "DELETE FROM " . UPI2DB_UNREAD_POSTS_TABLE . "
			WHERE post_id IN (" . $read_posts . ")
			AND user_id = " . $userdata['user_id'] . "
			AND status != '2'";
		$db->sql_query($sql);
	}
}

//################################### except_time ##########################################
// Version 1.0.0

if(!function_exists(except_time))
{
	function except_time()
	{
		global $board_config, $userdata;

		$save_time = time() - ($board_config['upi2db_auto_read'] * 86400);
		$except_time = ($userdata['user_regdate'] > $board_config['upi2db_install_time']) ? (($userdata['user_regdate'] > $save_time) ? $userdata['user_regdate'] : $save_time) : (($board_config['upi2db_install_time'] > $save_time) ? $board_config['upi2db_install_time'] : $save_time);

		return $except_time;
	}
}

//################################### viewforum_calc_unread ##########################################
// Version 1.0.0

if(!function_exists(viewforum_calc_unread))
{
	function viewforum_calc_unread($unread, $topic_id, $topic_rowset, $i, $folder_new, $folder, &$folder_alt, &$folder_image, &$newest_post_img, &$upi2db_status)
	{
		global $board_config, $userdata, $lang, $images;

		$upi2db_status = '';
		if (in_array($topic_id, $unread['new_topics']) || in_array($topic_id, $unread['edit_topics']))
		{
			$folder_image = $folder_new;
			$folder_alt = $lang['New_posts'];

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

			$newest_post_img = '<a href="' . append_sid(VIEWTOPIC_MG . '?' . POST_TOPIC_URL . '=' . $topic_id . '&amp;' . POST_POST_URL . '=' . $post_id) . '#p' . $post_id . '"><img src="' . $images['icon_newest_reply'] . '" alt="' . $lang['View_newest_post'] . '" title="' . $lang['View_newest_post'] . '" /></a> ' . $upi2db_status;
		}
		else
		{
			$folder_image = $folder;
			$folder_alt = ($topic_rowset[$i]['topic_status'] == TOPIC_LOCKED) ? $lang['Topic_locked'] : $lang['No_new_posts'];

			$newest_post_img = '';
		}
	}
}

//################################### marking_posts ##########################################
// Version 1.0.0

if(!function_exists(marking_posts))
{
	function marking_posts($forum_id = '')
	{
		global $db, $userdata, $board_config;

		$user_id = $userdata['user_id'];

		$mp_forum = (empty($forum_id)) ? "" : " AND forum_id = '" . $forum_id . "'";

		// Edited By Mighty Gorgon - BEGIN
		if (($userdata['user_level'] == ADMIN) || ($userdata['user_level'] == MOD))
		{
			$sql_add_mar = '';
		}
		else
		{
			$sql_add_mar = " AND topic_type != '" . POST_STICKY . "' AND topic_type != '" . POST_ANNOUNCE . "' AND topic_type != '" . POST_GLOBAL_ANNOUNCE . "'";
		}
		// Edited By Mighty Gorgon - END

		$sql = "DELETE FROM " . UPI2DB_UNREAD_POSTS_TABLE . "
			WHERE user_id = " . $user_id . "
			" . $sql_add_mar . "
			AND status != '2'
			" . $mp_forum;

		if (!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Couldn't mark posts", "", __LINE__, __FILE__, $sql);
		}
	}
}

//################################### auth_forum_read ##########################################
// Version 1.0.0

if(!function_exists(auth_forum_read))
{
	function auth_forum_read($userdata)
	{
		global $board_config, $db, $lang;

		$sql = "SELECT * FROM ". FORUMS_TABLE;
		if (!$result = $db->sql_query($sql, false, 'forums_'))
		{
			message_die(GENERAL_ERROR, 'Could not query forums information', '', __LINE__, __FILE__, $sql);
		}
		$forum_data = array();
		while($row = $db->sql_fetchrow($result))
		{
			$forum_data[] = $row;
		}

		$is_auth_ary = array();
		$is_auth_ary = auth(AUTH_ALL, AUTH_LIST_ALL, $userdata, $forum_data);

		for ($i = 0; $i < count($forum_data); $i++)
		{
			if (($is_auth_ary[$forum_data[$i]['forum_id']]['auth_read']) && ($is_auth_ary[$forum_data[$i]['forum_id']]['auth_view']))
			{
				if ($auth_forum_id == '')
				{
					$auth_forum_id = $forum_data[$i]['forum_id'];
				}
				else
				{
					$auth_forum_id .= ',' . $forum_data[$i]['forum_id'];
				}
			}
		}
		return $auth_forum_id;
	}
}

//################################### upi2db_faq_include ##########################################
// Version 1.0.0

if(!function_exists(upi2db_faq_include))
{
	function upi2db_faq_include($lang_file)
	{
		global $board_config, $faq;

		$unread_days = $board_config['upi2db_auto_read'];
		$del_mark = $board_config['upi2db_del_mark'];
		$max_mark = $board_config['upi2db_max_mark_posts'];
		$del_perm = $board_config['upi2db_del_perm'];
		$max_perm = $board_config['upi2db_max_permanent_topics'];

		if ($board_config['upi2db_on'] == 0)
		{
			return;
		}

		if ($lang_file == 'lang_faq')
		{
			if (!@file_exists(@phpbb_realpath(IP_ROOT_PATH . 'language/lang_' . $board_config['default_lang'] . '/lang_faq_upi2db.' . PHP_EXT)))
			{
				include(IP_ROOT_PATH . 'language/lang_english/lang_faq_upi2db.' . PHP_EXT);
			}
			else
			{
				include(IP_ROOT_PATH . 'language/lang_' . $board_config['default_lang'] . '/lang_faq_upi2db.' . PHP_EXT);
			}
		}
	}
}

//################################### prune_upi2db ##########################################
// Version 1.0.0

if(!function_exists(prune_upi2db))
{
	function prune_upi2db($sql_post)
	{
		global $board_config, $db, $userdata, $lang;

		$user_id = $userdata['user_id'];

		$sql = "DELETE FROM " . UPI2DB_UNREAD_POSTS_TABLE . "
			WHERE post_id IN (" . $sql_post . ")";

		if (!$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not delete topic reads', '', __LINE__, __FILE__, $sql);
		}

		$sql = "DELETE FROM " . UPI2DB_LAST_POSTS_TABLE . "
			WHERE post_id IN (" . $sql_post . ")";

		if (!$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not delete topic reads', '', __LINE__, __FILE__, $sql);
		}
	}
}

?>