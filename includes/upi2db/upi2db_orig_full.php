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
 * delete_old_data
 * unread
 * always_read
 * mark_always_read
 * mar_icon
 * mark_post_viewtopic
 * index_display_new
 * always_read_forum
 * viewtopic_calc_unread
 * search_calc_unread
 * search_calc_unread2
 * set_unread
 * search_mark_as_read
 *
 ***************************************************************************/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

//################################### delete_old_data ##########################################
// Version 1.0.0

if(!function_exists(delete_old_data))
{
	function delete_old_data($expired_post_time, $del_mark_time, $del_perm_time, $db)
	{
		$sql = "DELETE FROM " . UPI2DB_LAST_POSTS_TABLE . "
			WHERE (post_time < '" . $expired_post_time . "' AND post_edit_time < '" . $expired_post_time . "')
			AND topic_type != " . POST_STICKY . "";
		$db->sql_query($sql);

		$sql = "DELETE FROM " . UPI2DB_UNREAD_POSTS_TABLE . "
			WHERE ((last_update < '" . $expired_post_time . "' AND status != '2')
				OR (last_update < '" . $del_mark_time . "' AND status = '2'))
			AND topic_type != " . POST_STICKY . "";
		$db->sql_query($sql);

		$sql = "DELETE FROM " . UPI2DB_ALWAYS_READ_TABLE . "
			WHERE (last_update < '" . $del_perm_time . "')";
		$db->sql_query($sql);

		set_config('upi2db_delete_old_data', time());
	}
}

//################################### unread ##########################################
// Version 1.0.0

if(!function_exists(unread))
{
	function unread()
	{
		global $board_config, $userdata, $db;

		if(!$userdata['session_logged_in'])
		{
			return;
		}

		$user_id = $userdata['user_id'];
		$auth_forum_id = $userdata['auth_forum_id'];

		$anz_unread = 0;
		$auth_forum = ($auth_forum_id) ? ' AND forum_id IN (' . $auth_forum_id . ')' : '';
		$max_new_posts = ($userdata['user_level'] != ADMIN) ? (($userdata['user_level'] != MOD) ? $board_config['upi2db_max_new_posts'] : $board_config['upi2db_max_new_posts_mod']) : $board_config['upi2db_max_new_posts_admin'];
		// Edited By Mighty Gorgon - BEGIN
		$max_new_posts = ($max_new_posts == 0) ? UPI2DB_MAX_UNREAD_POSTS : $max_new_posts;
		// Edited By Mighty Gorgon - END

		$sql = "SELECT post_id, topic_id, forum_id, user_id, status, topic_type FROM " . UPI2DB_UNREAD_POSTS_TABLE . "
			WHERE user_id = '" . $user_id . "'
			" . $auth_forum . "
			ORDER BY last_update DESC";

		$unread['forums'] = array();
		$unread['new_topics'] = array();
		$unread['edit_topics'] = array();
		$unread['new_posts'] = array();
		$unread['edit_posts'] = array();
		$unread['mark_unread'] = array();
		$unread['mark_posts'] = array();
		$unread['mark_topics'] = array();
		$unread['del_posts'] = array();
		$unread['always_read'] = $userdata['always_read'];

		if ($result = $db->sql_query($sql))
		{
			while($read = $db->sql_fetchrow($result))
			{
				$topic_id = $read['topic_id'];

				if (!in_array($read['forum_id'], $unread['forums']) && ($read['status'] != '2') && ($anz_unread <= $max_new_posts || $read['topic_type'] != POST_STICKY))
				{
					$unread['forums'][] = ($read['topic_type'] != POST_GLOBAL_ANNOUNCE) ? $read['forum_id'] : 'A';
				}

				if(($read['status'] == 0) && (($anz_unread <= $max_new_posts) || ($read['topic_type'] != POST_NORMAL)))
				{
					if (!in_array($read['topic_id'], $unread['new_topics']))
					{
						$unread['new_topics'][] = $read['topic_id'];
					}
				}
				elseif(($read['status'] == 1) && (($anz_unread <= $max_new_posts) || ($read['topic_type'] != POST_NORMAL)))
				{
					if (!in_array($read['topic_id'], $unread['edit_topics']))
					{
						$unread['edit_topics'][] = $read['topic_id'];
					}
				}

				if($read['status'] == 2)
				{
					if (!in_array($read['topic_id'], $unread['mark_topics']))
					{
						$unread['mark_topics'][] = $read['topic_id'];
					}
				}

				if(($read['status'] == 0) && (($anz_unread <= $max_new_posts) || ($read['topic_type'] != POST_NORMAL)))
				{
					$unread[$topic_id]['new_posts'][] = $read['post_id'];
					$unread['new_posts'][] = $read['post_id'];
					$anz_unread++;
				}
				elseif(($read['status'] == 1) && (($anz_unread <= $max_new_posts) || ($read['topic_type'] != POST_NORMAL)))
				{
					$unread[$topic_id]['edit_posts'][] = $read['post_id'];
					$unread['edit_posts'][] = $read['post_id'];
					$anz_unread++;
				}

				if($read['status'] == 2)
				{
					$unread[$topic_id]['mark_posts'][] = $read['post_id'];
					$unread['mark_posts'][] = $read['post_id'];
				}

				if(($anz_unread > $max_new_posts) && ($read['topic_type'] == POST_NORMAL))
				{
					$unread['del_posts'][] = $read['post_id'];
				}
			}
		}
		$db->sql_freeresult($result);

		$sql_where = (count($unread['del_posts']) == 0) ? 0 : implode(',', $unread['del_posts']);
		if ($sql_where != 0)
		{
			$sql = "DELETE FROM " . UPI2DB_UNREAD_POSTS_TABLE . "
				 WHERE post_id IN (" . $sql_where . ")";
			$db->sql_query($sql);
		}

		return $unread;
	}
}

//################################### always_read ##########################################
// Version 1.0.0

if(!function_exists(always_read))
{
	function always_read($topic_id, $always_read, $unread)
	{
		global $board_config, $userdata, $db, $lang;

		$user_id = $userdata['user_id'];
		$time_now = time();
		if((count($unread['always_read']['topics']) >= $board_config['upi2db_max_permanent_topics']) && ($always_read == 'set'))
		{
			$mark_read_text = $lang['upi2db_always_read_no_more'];
			return $mark_read_text;
		}
		if ($always_read == 'set')
		{
			$sql = "INSERT INTO " . UPI2DB_ALWAYS_READ_TABLE . "
				(user_id, topic_id, last_update)
				VALUES ('$user_id' , '$topic_id', '$time_now')";
			if (!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, "Couldn't Build Topic Read database", "", __LINE__, __FILE__, $sql);
			}
			else
			{
				$sql = "DELETE FROM " . UPI2DB_UNREAD_POSTS_TABLE . "
					WHERE user_id = $user_id
					AND topic_id = $topic_id";
				$db->sql_query($sql);

				$mark_read_text = $lang['upi2db_always_read_is_set'];
			}
		}
		else
		{
			$sql = "DELETE FROM " . UPI2DB_ALWAYS_READ_TABLE . "
				WHERE user_id = $user_id
				AND topic_id = $topic_id";

			if (!$db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Error in posting', '', __LINE__, __FILE__, $sql);
			}
			else
			{
				$mark_read_text = $lang['upi2db_always_read_is_unset'];
			}
		}

		return $mark_read_text;
	}
}

//################################### mark_always_read ##########################################
// Version 1.0.0

if(!function_exists(mark_always_read))
{
	function mark_always_read($topic_type, $topic_id, $forum_id, $file, $art, $unread, $start = false, $folder_image = false, $search_mode = false, $s2 = false)
	{
		global $board_config, $userdata, $lang, $images;

		// Edited By Mighty Gorgon - BEGIN
		if (($userdata['user_level'] == ADMIN) || ($userdata['user_level'] == MOD))
		{
			$except_ar_topics = false;
		}
		else
		{
			$except_ar_topics = (($topic_type == POST_STICKY) || ($topic_type == POST_ANNOUNCE) || ($topic_type == POST_GLOBAL_ANNOUNCE)) ? true : false;
		}
		// Edited By Mighty Gorgon - END

		$folder_image_ar = $images['topic_ar_read'];

		$ar_t = $unread['always_read']['topics'];
		$ar_f = $unread['always_read']['forums'];

		if($except_ar_topics == false)
		{
			if(count($ar_t) && in_array($topic_id, $ar_t))
			{
				$mark_always_url = append_sid($file . '.' . PHP_EXT . '?' . POST_TOPIC_URL . '=' . $topic_id . '&amp;' . POST_FORUM_URL . '=' . $forum_id . '&amp;start=' . $start . '&amp;search_id=' . $search_mode . '&amp;s2=' . $s2 . '&amp;always_read=unset');
				$mark_always_icon = '<a href="' . $mark_always_url . '">' . mar_icon($folder_image_ar, $lang['upi2db_always_read_unset']) . '</a>';
				$mark_always_txt = '<a href="' . $mark_always_url . '">' . $lang['upi2db_always_read_unset'] . '</a>';
			}
			else
			{
				if(count($ar_f) && in_array($forum_id, $ar_f))
				{
					$mark_always_icon = mar_icon($folder_image_ar, $lang['upi2db_forum_is_always_read']);
					$mark_always_txt = $lang['upi2db_forum_is_always_read'];
				}
				else
				{
					$mark_always_url = append_sid($file . '.' . PHP_EXT . '?' . POST_TOPIC_URL . '=' . $topic_id . '&amp;' . POST_FORUM_URL . '=' . $forum_id . '&amp;start=' . $start . '&amp;search_id=' . $search_mode . '&amp;s2=' . $s2 . '&amp;always_read=set');
					$mark_always_icon = '<a href="' . $mark_always_url . '">' . mar_icon($folder_image, $lang['upi2db_always_read']) . '</a>';
					$mark_always_txt = '<a href="' . $mark_always_url . '">' . $lang['upi2db_always_read'] . '</a>';
				}
			}

			if(count($ar_t) >= $board_config['upi2db_max_permanent_topics'])
			{
				$mark_always_icon = mar_icon($folder_image, $lang['upi2db_always_read_no_more']);
				$mark_always_txt = $lang['upi2db_always_read_no_more'];
			}
		}
		else
		{
			$mark_always_icon = mar_icon($folder_image, $lang['upi2db_always_read_cant_set']);
			$mark_always_txt = $lang['upi2db_always_read_cant_set'];
		}

		$mark_always = ($art == 'txt') ? $mark_always_txt : $mark_always_icon;

		return $mark_always;
	}
}

//################################### mark_always_read ##########################################
// Version 1.0.0

if(!function_exists(mark_always_read_vt_ip))
{
	function mark_always_read_vt_ip($topic_type, $topic_id, $forum_id, $art, $unread)
	{
		global $board_config, $userdata, $lang, $images;

		// Edited By Mighty Gorgon - BEGIN
		if (($userdata['user_level'] == ADMIN) || ($userdata['user_level'] == MOD))
		{
			$except_ar_topics = false;
		}
		else
		{
			$except_ar_topics = (($topic_type == POST_STICKY) || ($topic_type == POST_ANNOUNCE) || ($topic_type == POST_GLOBAL_ANNOUNCE)) ? true : false;
		}
		// Edited By Mighty Gorgon - END

		$ar_t = $unread['always_read']['topics'];
		$ar_f = $unread['always_read']['forums'];

		if($except_ar_topics == false)
		{
			if(count($ar_t) && in_array($topic_id, $ar_t))
			{
				$mark_always_url = append_sid(VIEWFORUM_MG . '?' . POST_FORUM_URL . '=' . $forum_id . '&amp;' . POST_TOPIC_URL . '=' . $topic_id . '&amp;always_read=unset');
				$mark_always_txt = '<a href="' . $mark_always_url . '">' . $lang['upi2db_always_read_unset'] . '</a>';
				$mark_always_icon = '<a href="' . $mark_always_url . '"><img src="' . $images['topic_ar_switch_off'] . '" alt="' . $lang['upi2db_always_read_unset'] . '" title="' . $lang['upi2db_always_read_unset'] . '" /></a>';
			}
			else
			{
				if(count($ar_f) && in_array($forum_id, $ar_f))
				{
					$mark_always_txt = $lang['upi2db_forum_is_always_read'];
					$mark_always_icon = '<img src="' . $images['topic_ar_switch_off'] . '" alt="' . $lang['upi2db_forum_is_always_read'] . '" title="' . $lang['upi2db_forum_is_always_read'] . '" />';
				}
				else
				{
					$mark_always_url = append_sid(VIEWFORUM_MG . '?' . POST_FORUM_URL . '=' . $forum_id . '&amp;' . POST_TOPIC_URL . '=' . $topic_id . '&amp;always_read=set');
					$mark_always_txt = '<a href="' . $mark_always_url . '">' . $lang['upi2db_always_read'] . '</a>';
					$mark_always_icon = '<a href="' . $mark_always_url . '"><img src="' . $images['topic_ar_switch_on'] . '" alt="' . $lang['upi2db_always_read'] . '" title="' . $lang['upi2db_always_read'] . '" /></a>';
				}
			}
			if(count($ar_t) >= $board_config['upi2db_max_permanent_topics'])
			{
				$mark_always_txt = $lang['upi2db_always_read_no_more'];
				$mark_always_icon = '<img src="' . $images['topic_ar_switch_off'] . '" alt="' . $lang['upi2db_always_read_no_more'] . '" title="' . $lang['upi2db_always_read_no_more'] . '" />';
			}
		}
		else
		{
			$mark_always_txt = $lang['upi2db_always_read_cant_set'];
			$mark_always_icon = '<img src="' . $images['topic_ar_switch_off'] . '" alt="' . $lang['upi2db_always_read_cant_set'] . '" title="' . $lang['upi2db_always_read_cant_set'] . '" />';
		}

		$mark_always = ($art == 'txt') ? $mark_always_txt : $mark_always_icon;

		return $mark_always;
	}
}

//################################### mar_icon ##########################################
// Version 1.0.0

if(!function_exists(mar_icon))
{
	function mar_icon($folder_image, $folder_txt)
	{
		$mark_always_read_icon = '<img src="' . $folder_image . '" style="margin-right:4px;" alt="' . $folder_txt . '" title="' . $folder_txt . '" />';
		return $mark_always_read_icon;
	}
}

//################################### mark_post_viewtopic ##########################################
// Version 1.0.0

if(!function_exists(mark_post_viewtopic))
{
	function mark_post_viewtopic($post_time_max, $unread, $topic_id, $forum_id, $post_id, $except_time, $topic_type)
	{
		global $board_config, $userdata, $lang, $images;

		if(!in_array($forum_id, $unread['always_read']['forums']) && !in_array($topic_id, $unread['always_read']['topics']) && $post_time_max > $except_time)
		{
			$mark_topic_unread_url = append_sid(VIEWFORUM_MG . '?' . POST_TOPIC_URL . '=' . $topic_id . '&amp;' . POST_FORUM_URL . '=' . $forum_id . '&amp;' . POST_POST_URL . '=' . $post_id . '&amp;tt=' . $topic_type . '&amp;do=mark_unread');
			$mark_topic_unread = '<a href="' . $mark_topic_unread_url . '"><img src="' . $images['unread_img'] . '" alt="' . $lang['upi2db_mark_post_unread'] . '" title="' . $lang['upi2db_mark_post_unread'] . '" /></a>';

			if(count($unread['mark_posts']) >= $board_config['upi2db_max_mark_posts'])
			{
				$mark_topic_unread .= '&nbsp;<img src="' . $images['mark_img'] . '" alt="' . $lang['upi2db_post_cant_mark'] . '" title="' . $lang['upi2db_post_cant_mark'] . '" />';
			}
			else
			{
				if(!in_array($post_id, $unread['mark_posts']))
				{
					$mark_topic_unread_url = append_sid(VIEWFORUM_MG . '?' . POST_TOPIC_URL . '=' . $topic_id . '&amp;' . POST_FORUM_URL . '=' . $forum_id . '&amp;' . POST_POST_URL . '=' . $post_id . '&amp;tt=' . $topic_type . '&amp;do=mark_post');
					$mark_topic_unread .= '&nbsp;<a href="' . $mark_topic_unread_url . '"><img src="' . $images['mark_img'] . '" alt="' . $lang['upi2db_mark_post'] . '" title="' . $lang['upi2db_mark_post'] . '" /></a>';
				}
				else
				{
					$mark_topic_unread_url = append_sid(VIEWFORUM_MG . '?' . POST_TOPIC_URL . '=' . $topic_id . '&amp;' . POST_FORUM_URL . '=' . $forum_id . '&amp;' . POST_POST_URL . '=' . $post_id . '&amp;tt=' . $topic_type . '&amp;do=unmark_post');
					$mark_topic_unread .= '&nbsp;<a href="' . $mark_topic_unread_url . '"><img src="' . $images['unmark_img'] . '" alt="' . $lang['upi2db_unmark_post'] . '" title="' . $lang['upi2db_unmark_post'] . '" /></a>';
				}
			}
		}
		else
		{
			$mark_topic_unread = '';
		}

		return $mark_topic_unread;
	}
}

//################################### index_display_new ##########################################
// Version 1.0.0

if(!function_exists(index_display_new))
{
	function index_display_new($unread)
	{
		global $lang, $images, $board_config, $unread_new_posts, $unread_edit_posts;

		$edit_posts = count($unread['edit_posts']) - $unread_edit_posts;
		$new_posts = count($unread['new_posts']) - $unread_new_posts;
		$unread_posts = $new_posts + $edit_posts;
		$always_read = count($unread['always_read']['topics']);
		$mark_unread = count($unread['mark_posts']);

		$max_perm_read = $board_config['upi2db_max_permanent_topics'];
		$max_mark = $board_config['upi2db_max_mark_posts'];

		/*
		// These images have been removed in CFG!!!
		$icon_u = $images['mini_upi2db_u'];
		$icon_p = $images['mini_upi2db_p'];
		$icon_m = $images['mini_upi2db_m'];

		$u_display_new = ($unread_posts) ? ' <a href="' . append_sid(SEARCH_MG . '?search_id=upi2db&amp;s2=new') . '" class="mainmenu" ><img src="' . $icon_u . '" border="0" hspace="3" title="' . $lang['Neue_Beitraege'] . ' (' . $new_posts . ') / ' . $lang['Editierte_Beitraege'] . ' (' . $edit_posts . ')" alt="' . $lang['Neue_Beitraege'] . ' (' . $new_posts . ') / ' . $lang['Editierte_Beitraege'] . ' (' . $edit_posts . ')"> ' . $unread_posts . '</a> ' : ' <img src="' . $icon_u . '" width="12" height="13" border="0" hspace="3" title="' . $lang['Neue_Beitraege'] . ' (' . $new_posts . ') / ' . $lang['Editierte_Beitraege'] . ' (' . $edit_posts . ')" alt="' . $lang['Neue_Beitraege'] . ' (' . $new_posts . ') / ' . $lang['Editierte_Beitraege'] . ' (' . $edit_posts . ')"> 0 ';
		$u_display_new .= ($mark_unread) ? ' <a href="' . append_sid(SEARCH_MG . '?search_id=upi2db&amp;s2=mark') . '" class="mainmenu" ><img src="' . $icon_m . '" border="0" hspace="3" title="' . $lang['Ungelesen_Markiert'] . ' (' . $mark_unread . '/' . $max_mark .')" alt="' . $lang['Ungelesen_Markiert'] . ' (' . $mark_unread . '/' . $max_mark .')"> ' . $mark_unread . '</a> ' : ' <img src="' . $icon_m . '" width="12" height="13" border="0" hspace="3" title="' . $lang['Ungelesen_Markiert'] . ' (' . $mark_unread . '/' . $max_mark .')" alt="' . $lang['Ungelesen_Markiert'] . ' (' . $mark_unread . '/' . $max_mark .')"> 0 ';
		$u_display_new .= ($always_read) ? ' <a href="' . append_sid(SEARCH_MG . '?search_id=upi2db&amp;s2=perm') . '" class="mainmenu" ><img src="' . $icon_p . '" border="0" hspace="3" title="' . $lang['Permanent_Gelesen'] . ' (' . $always_read . '/' . $max_perm_read .')" alt="' . $lang['Permanent_Gelesen'] . ' (' . $always_read . '/' . $max_perm_read .')"> ' . $always_read . ' </a>' : ' <img src="' . $icon_p . '" width="12" height="13" border="0" hspace="3" title="' . $lang['Permanent_Gelesen'] . ' (' . $always_read . '/' . $max_perm_read .')" alt="' . $lang['Permanent_Gelesen'] . ' (' . $always_read . '/' . $max_perm_read .')"> 0 ';

		$u_display_new = ' <a href="' . append_sid(SEARCH_MG . '?search_id=upi2db&amp;s2=new') . '" class="mainmenu" title="' . $lang['Neue_Beitraege'] . ' (' . $new_posts . ') / ' . $lang['Editierte_Beitraege'] . ' (' . $edit_posts . ')" alt="' . $lang['Neue_Beitraege'] . ' (' . $new_posts . ') / ' . $lang['Editierte_Beitraege'] . ' (' . $edit_posts . ')"> U: ' . $unread_posts . ' </a> ';
		$u_display_new .= ' <a href="' . append_sid(SEARCH_MG . '?search_id=upi2db&amp;s2=mark') . '" class="mainmenu" title="' . $lang['Ungelesen_Markiert'] . ' (' . $mark_unread . '/' . $max_mark . ')" alt="' . $lang['Ungelesen_Markiert'] . ' (' . $mark_unread . '/' . $max_mark . ')"> M: ' . $mark_unread . ' </a> ';
		$u_display_new .= ' <a href="' . append_sid(SEARCH_MG . '?search_id=upi2db&amp;s2=perm') . '" class="mainmenu" title="' . $lang['Permanent_Gelesen'] . ' (' . $always_read . '/' . $max_perm_read . ')" alt="' . $lang['Permanent_Gelesen'] . ' (' . $always_read . '/' . $max_perm_read . ')"> P: ' . $always_read . ' </a> ';
		*/

		$u_display_new['all'] = '<a href="' . append_sid(SEARCH_MG . '?search_id=upi2db&amp;s2=new') . '" class="mainmenu" title="' . $lang['Neue_Beitraege'] . ' (' . $new_posts . ') / ' . $lang['Editierte_Beitraege'] . ' (' . $edit_posts . ')"> U: ' . $unread_posts . '</a>';
		$u_display_new['all'] .= '<a href="' . append_sid(SEARCH_MG . '?search_id=upi2db&amp;s2=mark') . '" class="mainmenu" title="' . $lang['Ungelesen_Markiert'] . ' (' . $mark_unread . '/' . $max_mark . ')"> M: ' . $mark_unread . '</a>';
		$u_display_new['all'] .= '<a href="' . append_sid(SEARCH_MG . '?search_id=upi2db&amp;s2=perm') . '" class="mainmenu" title="' . $lang['Permanent_Gelesen'] . ' (' . $always_read . '/' . $max_perm_read . ')"> P: ' . $always_read . '</a>';

		$u_display_new['u'] = '<a href="' . append_sid(SEARCH_MG . '?search_id=upi2db&amp;s2=new') . '" class="mainmenu" title="' . $lang['Neue_Beitraege'] . ' (' . $new_posts . ') / ' . $lang['Editierte_Beitraege'] . ' (' . $edit_posts . ')">' . $lang['upi2db_u'] . ' (' . $unread_posts . ')</a>';
		$u_display_new['m'] = '<a href="' . append_sid(SEARCH_MG . '?search_id=upi2db&amp;s2=mark') . '" class="mainmenu" title="' . $lang['Ungelesen_Markiert'] . ' (' . $mark_unread . '/' . $max_mark . ')">' . $lang['upi2db_m'] . ' (' . $mark_unread . ')</a>';
		$u_display_new['p'] = '<a href="' . append_sid(SEARCH_MG . '?search_id=upi2db&amp;s2=perm') . '" class="mainmenu" title="' . $lang['Permanent_Gelesen'] . ' (' . $always_read . '/' . $max_perm_read . ')">' . $lang['upi2db_p'] . ' (' . $always_read . ')</a>';

		$u_display_new['unread'] = '<a href="' . append_sid(SEARCH_MG . '?search_id=upi2db&amp;s2=new') . '" class="mainmenu" title="' . $lang['Neue_Beitraege'] . ' (' . $new_posts . ') / ' . $lang['Editierte_Beitraege'] . ' (' . $edit_posts . ')">' . $lang['upi2db_unread'] . ' (' . $unread_posts . ')</a>';
		$u_display_new['marked'] = '<a href="' . append_sid(SEARCH_MG . '?search_id=upi2db&amp;s2=mark') . '" class="mainmenu" title="' . $lang['Ungelesen_Markiert'] . ' (' . $mark_unread . '/' . $max_mark . ')">' . $lang['upi2db_marked'] . ' (' . $mark_unread . ')</a>';
		$u_display_new['permanent'] = '<a href="' . append_sid(SEARCH_MG . '?search_id=upi2db&amp;s2=perm') . '" class="mainmenu" title="' . $lang['Permanent_Gelesen'] . ' (' . $always_read . '/' . $max_perm_read . ')">' . $lang['upi2db_perm_read'] . ' (' . $always_read . ')</a>';

		// Mighty Gorgon - Full Lang Explain For Quick Links - BEGIN
		$u_display_new['unread_string'] = $lang['upi2db_unread'] . ' (' . $unread_posts . ')';
		$u_display_new['u_string'] = $lang['upi2db_u'] . ' (' . $unread_posts . ')';
		$u_display_new['u_string_full'] = $lang['Neue_Beitraege'] . ' (' . $new_posts . ') / ' . $lang['Editierte_Beitraege'] . ' (' . $edit_posts . ')';
		$u_display_new['u_url'] = append_sid(SEARCH_MG . '?search_id=upi2db&amp;s2=new');

		$u_display_new['marked_string'] = $lang['upi2db_marked'] . ' (' . $mark_unread . ')';
		$u_display_new['m_string'] = $lang['upi2db_m'] . ' (' . $mark_unread . ')';
		$u_display_new['m_string_full'] = $lang['Ungelesen_Markiert'] . ' (' . $mark_unread . '/' . $max_mark . ')';
		$u_display_new['m_url'] = append_sid(SEARCH_MG . '?search_id=upi2db&amp;s2=mark');

		$u_display_new['permanent_string'] = $lang['upi2db_perm_read'] . ' (' . $always_read . ')';
		$u_display_new['p_string'] = $lang['upi2db_p'] . ' (' . $always_read . ')';
		$u_display_new['p_string_full'] = $lang['Permanent_Gelesen'] . ' (' . $always_read . '/' . $max_perm_read . ')';
		$u_display_new['p_url'] = append_sid(SEARCH_MG . '?search_id=upi2db&amp;s2=perm');;
		// Mighty Gorgon - Full Lang Explain For Quick Links - END

		return $u_display_new;
	}
}

//################################### always_read_forum ##########################################
// Version 1.0.0

if(!function_exists(always_read_forum))
{
	function always_read_forum($forum_id, $always_read)
	{
		global $board_config, $userdata, $db, $lang;

		$user_id = $userdata['user_id'];
		$time_now = time();
		if ($always_read == 'set')
		{
			$sql = "INSERT INTO " . UPI2DB_ALWAYS_READ_TABLE . "
				(user_id, forum_id, last_update)
				VALUES ('$user_id' , '$forum_id', '$time_now')";
			if (!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, "Couldn't Build Topic Read database", "", __LINE__, __FILE__, $sql);
			}
			else
			{
				$sql = "DELETE FROM " . UPI2DB_UNREAD_POSTS_TABLE . "
					WHERE user_id = $user_id
					AND forum_id = $forum_id";
				$db->sql_query($sql);

				$mark_always_read_text = $lang['upi2db_forum_is_always_read'];
			}
		}
		else
		{
			$sql = "DELETE FROM " . UPI2DB_ALWAYS_READ_TABLE . "
				WHERE user_id = $user_id
				AND forum_id = $forum_id";

			if (!$db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Error in posting', '', __LINE__, __FILE__, $sql);
			}
			else
			{
				$mark_always_read_text = $lang['upi2db_forum_isnt_always_read'];
			}
		}

		return $mark_always_read_text;
	}
}

//################################### viewtopic_calc_unread ##########################################
// Version 1.0.0

if(!function_exists(viewtopic_calc_unread))
{
	function viewtopic_calc_unread($unread, $topic_id, $post_id, $forum_id, &$mini_post_img, &$mini_post_alt, &$unread_color, &$read_posts)
	{
		global $board_config, $userdata, $lang, $images;

		if ((in_array($post_id, $unread['edit_posts']) || in_array($post_id, $unread['new_posts']) || in_array($post_id, $unread['mark_posts'])) && !in_array($forum_id, $unread['always_read']['forums']))
		{
			$mini_post_img = $images['icon_minipost_new'];
			$mini_post_alt = $lang['New_post'];

/*
			if(in_array($post_id, $unread['mark_posts']))
			{
				$titel_color = $board_config['upi2db_mark_color'];
			}
			if(in_array($post_id, $unread['edit_posts']))
			{
				$titel_color = $board_config['upi2db_edit_color'];
			}
			if(in_array($post_id, $unread['new_posts']))
			{
				$titel_color = $board_config['upi2db_unread_color'];
			}
*/
			if(in_array($post_id, $unread['mark_posts']))
			{
				$titel_color = 'upi2db_mark_color';
			}
			if(in_array($post_id, $unread['edit_posts']))
			{
				$titel_color = 'upi2db_edit_color';
			}
			if(in_array($post_id, $unread['new_posts']))
			{
				$titel_color = 'upi2db_unread_color';
			}
			//$unread_color = ($userdata['user_upi2db_unread_color']) ? 'style="background-color:#' . $titel_color . ' ; background-image : url(' . $images[backgrount_vt] . ')"' : '';
			$unread_color = ($userdata['user_upi2db_unread_color']) ? $titel_color : '';

			if ($read_posts == '')
			{
				$read_posts = $post_id;
			}
			else
			{
				$read_posts .= ',' . $post_id;
			}
		}
		else
		{
			$mini_post_img = $images['icon_minipost'];
			$mini_post_alt = $lang['Post'];
			$unread_color = '';
		}
	}
}

//################################### search_calc_unread ##########################################
// Version 1.0.0

if(!function_exists(search_calc_unread))
{
	function search_calc_unread($unread, $topic_id, $searchset, $i, $folder_new, $folder, &$newest_post_img, &$topic_type, &$folder_image, &$folder_alt)
	{
		global $board_config, $userdata, $lang, $images;

		if ((in_array($topic_id, $unread['new_topics']) || in_array($topic_id, $unread['edit_topics'])) && (!in_array($forum_id, $unread['always_read']['forums']) || !in_array($topic_id, $unread['always_read']['topics'])))
		{
			$folder_image = $folder_new;
			$folder_alt = $lang['New_posts'];

			$min_new_post_id = (empty($unread[$topic_id]['new_posts'])) ? '99999999' : min($unread[$topic_id]['new_posts']);
			$min_edit_post_id = (empty($unread[$topic_id]['edit_posts'])) ? '99999999' :  min($unread[$topic_id]['edit_posts']);
			$post_id = ($min_edit_post_id >= $min_new_post_id) ? $min_new_post_id : $min_edit_post_id;

			$newest_post_img = '<a href="' . append_sid(VIEWTOPIC_MG . '?' . POST_TOPIC_URL . '=' . $topic_id . '&amp;' . POST_POST_URL . '=' . $post_id) . '#p' . $post_id . '"><img src="' . $images['icon_newest_reply'] . '" alt="' . $lang['View_newest_post'] . '" title="' . $lang['View_newest_post'] . '" /></a> ';

			if((in_array($topic_id, $unread['new_topics']) && in_array($topic_id, $unread['edit_topics'])) && ($userdata['user_upi2db_new_word'] && $userdata['user_upi2db_edit_word']))
			{
				$topic_type = $lang['upi2db_post_edit'] . $lang['upi2db_post_and'] . $lang['upi2db_post_new'] . ': ' . $topic_type;
			}
			else
			{
				if(in_array($topic_id, $unread['new_topics']) && $userdata['user_upi2db_new_word'])
				{
					$topic_type = $lang['upi2db_post_new'] . ': ' . $topic_type;
				}

				if(in_array($topic_id, $unread['edit_topics']) && $userdata['user_upi2db_edit_word'])
				{
					$topic_type = $lang['upi2db_post_edit'] . ': ' . $topic_type;
				}
			}
		}
		else
		{
			$folder_image = $folder;
			$folder_alt = ($searchset[$i]['topic_status'] == TOPIC_LOCKED) ? $lang['Topic_locked'] : $lang['No_new_posts'];

			$newest_post_img = '';
		}
	}
}

//################################### search_calc_unread2 ##########################################
// Version 1.0.0

if(!function_exists(search_calc_unread2))
{
	function search_calc_unread2($unread, $topic_id, $searchset, $i, &$mini_post_img, &$mini_post_alt, &$unread_color, &$folder_image, &$folder_alt)
	{
		global $board_config, $userdata, $lang, $images;

		$post_id = $searchset[$i]['post_id'];
		$unread_color  = '';

		$mini_post_img = $images['icon_minipost'];
		$mini_post_alt = $lang['Post'];
		$folder_image = $images['topic_nor_read'];
		$folder_alt = ($searchset[$i]['topic_status'] == TOPIC_LOCKED) ? $lang['Topic_locked'] : $lang['No_new_posts'];

		if ((in_array($post_id, $unread['new_posts']) || in_array($post_id, $unread['edit_posts']) || in_array($post_id, $unread['mark_posts'])) && !in_array($forum_id, $unread['always_read']['forums']))
		{
			if (in_array($post_id, $unread['new_posts']) || in_array($post_id, $unread['edit_posts']) || in_array($post_id, $unread['mark_posts']))
			{
				$mini_post_img = $images['icon_minipost_new'];
				$mini_post_alt = $lang['New_post'];
				$folder_image = $images['topic_nor_unread'];
				$folder_alt = $lang['New_posts'];
/*
				if(in_array($post_id, $unread['mark_posts']))
				{
					$titel_color = $board_config['upi2db_mark_color'];
				}
				if(in_array($post_id, $unread['edit_posts']))
				{
					$titel_color = $board_config['upi2db_edit_color'];
				}
				if(in_array($post_id, $unread['new_posts']))
				{
					$titel_color = $board_config['upi2db_unread_color'];
				}
*/
				if(in_array($post_id, $unread['mark_posts']))
				{
					$titel_color = 'upi2db_mark_color';
				}
				if(in_array($post_id, $unread['edit_posts']))
				{
					$titel_color = 'upi2db_edit_color';
				}
				if(in_array($post_id, $unread['new_posts']))
				{
					$titel_color = 'upi2db_unread_color';
				}
				if($userdata['user_upi2db_unread_color'])
				{
					//$unread_color = 'style="background-color:#' . $titel_color . ' ; background-image : url(' . $images[backgrount_vt] . ')"';
					$unread_color = $titel_color;
				}
				else
				{
					$unread_color  = '';
				}
			}
		}
		else
		{
			if(in_array($forum_id, $unread['always_read']['forums']) || in_array($topic_id, $unread['always_read']['topics']))
			{
				$folder_image = $images['topic_ar_read'];
				$folder_alt = $lang['upi2db_always_read_forum'];
			}
		}
	}
}

//################################### set_unread ##########################################
// Version 1.0.0

if(!function_exists(set_unread))
{
	function set_unread($t, $f, $p, $unread, $do, $tt)
	{
		global $db, $userdata, $board_config, $lang;

		$user_id = $userdata['user_id'];
		$time = time();

		if((count($unread['mark_posts']) >= $board_config['upi2db_max_mark_posts']) && ($do == 'mark_post'))
		{
			$mark_read_text = $lang['upi2db_post_cant_mark'];
			return $mark_read_text;
		}

		if($do == 'mark_post' || $do == 'mark_unread')
		{
			switch($do)
			{
				case 'mark_post':
					$mark_id = '2';
					$mark_read_text = $lang['upi2db_post_marked'];
				break;

				case 'mark_unread':
					$mark_id = '0';
					$mark_read_text = $lang['upi2db_mark_post_is_unread'];
				break;
			}

			if(isset($unread['mark_posts']) && in_array($p, $unread['mark_posts']))
			{
				$sql = "UPDATE " . UPI2DB_UNREAD_POSTS_TABLE . " SET status = '" . $mark_id . "' WHERE post_id = " . $p;
			}
			else
			{
				$sql = "INSERT INTO " . UPI2DB_UNREAD_POSTS_TABLE . "
					(post_id, topic_id, forum_id, user_id, status, last_update, topic_type)
					VALUES ('$p', '$t', '$f', '$user_id', '$mark_id', '$time', '$tt')";
			}

			if (!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, "Couldn't mark post", "", __LINE__, __FILE__, $sql);
			}
		}
		else
		{
			$sql = "DELETE FROM " . UPI2DB_UNREAD_POSTS_TABLE . "
				WHERE post_id = " . $p . "
				AND user_id = " . $userdata['user_id'];

			if (!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, "Couldn't unmark post", "", __LINE__, __FILE__, $sql);
			}

			$mark_read_text = $lang['upi2db_post_unmarked'];
		}
		return $mark_read_text;
	}
}

//################################### search_mark_as_read ##########################################
// Version 1.0.0

if(!function_exists(search_mark_as_read))
{
	function search_mark_as_read($mar_topic_id)
	{
		global $board_config, $db, $userdata, $lang;

		$user_id = $userdata['user_id'];

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

		foreach($mar_topic_id as $topic_id)
		{
			$time_now = time();
			$sql = "DELETE FROM " . UPI2DB_UNREAD_POSTS_TABLE . "
				WHERE user_id = " . $user_id . "
				AND topic_id = " . $topic_id . "
				" . $sql_add_mar;

			if (!$db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, "Couldn't delete read info", "", __LINE__, __FILE__, $sql);
			}
		}
	}
}

?>