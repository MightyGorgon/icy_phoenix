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

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

//################################### check_condition ##########################################
function check_group_auth($userdata)
{
	global $config, $db;

	if(!$userdata['session_logged_in'])
	{
		return false;
	}

	$no_group_upi2db_on = $config['upi2db_no_group_upi2db_on'];
	$no_group_min_posts = $config['upi2db_no_group_min_posts'];
	$no_group_min_regdays = $config['upi2db_no_group_min_regdays'];

	$user_min_posts = $userdata['user_posts'];
	$user_min_regdays  = floor((time() - $userdata['user_regdate']) / 86400);

	$check_user_upi2db_on = false;
	$count_user_in_groups = 0;
	$count_groups = 0;

	$sql = "SELECT g.upi2db_on, g.upi2db_min_posts, g.upi2db_min_regdays
		FROM " . GROUPS_TABLE . " g, " . USER_GROUP_TABLE . " ug
			WHERE ug.group_id = g.group_id
			AND g.group_single_user <> " . TRUE . "
			AND ug.user_pending <> ".TRUE . "
			AND ug.user_id = " . $userdata['user_id'] . "
			GROUP BY g.group_id";
	$db->sql_return_on_error(true);
	$result = $db->sql_query($sql);
	$db->sql_return_on_error(false);
	if ($result)
	{
		while($row = $db->sql_fetchrow($result))
		{
			$group_access[] = $row;
		}
	}
	$db->sql_freeresult($result);

	if (empty($group_access))
	{
		if (($no_group_upi2db_on == 1) && ($no_group_min_posts <= $user_min_posts) && ($no_group_min_regdays <= $user_min_regdays))
		{
			return true;
		}
	}
	else
	{
		for($i = 0; $i < sizeof($group_access); $i++)
		{
			if(($group_access[$i]['upi2db_on'] == '1') && ($group_access[$i]['upi2db_min_posts'] <= $user_min_posts) && ($group_access[$i]['upi2db_min_regdays'] <= $user_min_regdays))
			{
				return true;
			}
		}
	}
	return false;
}

//################################### check_is_upi2db_on ##########################################
function check_upi2db_on($userdata)
{
	global $config;

	$user_upi2db_on = $userdata['user_upi2db_which_system'];
	$user_upi2db_disable = $userdata['user_upi2db_disable'];
	$admin_upi2db_on = $config['upi2db_on'];

	if($config['board_disable'] || $user_upi2db_disable || !$userdata['session_logged_in'] || !$admin_upi2db_on )
	{
		return false;
	}
	elseif(($admin_upi2db_on == 1) || (($admin_upi2db_on == 2) && ($user_upi2db_on == 1)))
	{
		return check_group_auth($userdata);
	}

	return false;
}

// Below this line all functions have been moved from upi2db_orig_full.php

//################################### delete_old_data ##########################################
// Version 1.0.0

if(!function_exists('delete_old_data'))
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

if(!function_exists('unread'))
{
	function unread()
	{
		global $db, $cache, $config, $userdata;

		if(!$userdata['session_logged_in'])
		{
			return;
		}

		$user_id = $userdata['user_id'];
		$auth_forum_id = $userdata['auth_forum_id'];

		$anz_unread = 0;
		$auth_forum = ($auth_forum_id) ? ' AND forum_id IN (' . $auth_forum_id . ')' : '';
		$max_new_posts = ($userdata['user_level'] != ADMIN) ? (($userdata['user_level'] != MOD) ? $config['upi2db_max_new_posts'] : $config['upi2db_max_new_posts_mod']) : $config['upi2db_max_new_posts_admin'];
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

		$db->sql_return_on_error(true);
		$result = $db->sql_query($sql);
		$db->sql_return_on_error(false);
		if ($result)
		{
			while($read = $db->sql_fetchrow($result))
			{
				$topic_id = $read['topic_id'];

				if (!in_array($read['forum_id'], $unread['forums']) && ($read['status'] != '2') && (($anz_unread <= $max_new_posts) || ($read['topic_type'] != POST_STICKY)))
				{
					$unread['forums'][] = $read['forum_id'];
					// Decomment this if you want all forums to be marked as read when a Global Announcement has new posts!
					//$unread['forums'][] = ($read['topic_type'] != POST_GLOBAL_ANNOUNCE) ? $read['forum_id'] : 'A';
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

		$sql_where = (sizeof($unread['del_posts']) == 0) ? 0 : implode(',', $unread['del_posts']);
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

if(!function_exists('always_read'))
{
	function always_read($topic_id, $always_read, $unread)
	{
		global $config, $userdata, $db, $lang;

		$user_id = $userdata['user_id'];
		$time_now = time();
		if((sizeof($unread['always_read']['topics']) >= $config['upi2db_max_permanent_topics']) && ($always_read == 'set'))
		{
			$mark_read_text = $lang['upi2db_always_read_no_more'];
			return $mark_read_text;
		}
		if ($always_read == 'set')
		{
			$sql = "INSERT INTO " . UPI2DB_ALWAYS_READ_TABLE . "
				(user_id, topic_id, last_update)
				VALUES ('$user_id' , '$topic_id', '$time_now')";
			$result = $db->sql_query($sql);

			$sql = "DELETE FROM " . UPI2DB_UNREAD_POSTS_TABLE . "
				WHERE user_id = $user_id
				AND topic_id = $topic_id";
			$db->sql_query($sql);
			$mark_read_text = $lang['upi2db_always_read_is_set'];
		}
		else
		{
			$sql = "DELETE FROM " . UPI2DB_ALWAYS_READ_TABLE . "
				WHERE user_id = $user_id
				AND topic_id = $topic_id";
			$db->sql_query($sql);
			$mark_read_text = $lang['upi2db_always_read_is_unset'];
		}

		return $mark_read_text;
	}
}

//################################### mark_always_read ##########################################
// Version 1.0.0

if(!function_exists('mark_always_read'))
{
	function mark_always_read($topic_type, $topic_id, $forum_id, $file, $art, $unread, $start = false, $folder_image = false, $search_mode = false, $s2 = false)
	{
		global $config, $userdata, $lang, $images;

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
			if(sizeof($ar_t) && in_array($topic_id, $ar_t))
			{
				$mark_always_url = append_sid($file . '.' . PHP_EXT . '?' . POST_TOPIC_URL . '=' . $topic_id . '&amp;' . POST_FORUM_URL . '=' . $forum_id . '&amp;start=' . $start . '&amp;search_id=' . $search_mode . '&amp;s2=' . $s2 . '&amp;always_read=unset');
				$mark_always_icon = '<a href="' . $mark_always_url . '">' . mar_icon($folder_image_ar, $lang['upi2db_always_read_unset']) . '</a>';
				$mark_always_txt = '<a href="' . $mark_always_url . '">' . $lang['upi2db_always_read_unset'] . '</a>';
			}
			else
			{
				if(sizeof($ar_f) && in_array($forum_id, $ar_f))
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

			if(sizeof($ar_t) >= $config['upi2db_max_permanent_topics'])
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

if(!function_exists('mark_always_read_vt_ip'))
{
	function mark_always_read_vt_ip($topic_type, $topic_id, $forum_id, $art, $unread)
	{
		global $config, $userdata, $lang, $images;

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
			if(sizeof($ar_t) && in_array($topic_id, $ar_t))
			{
				$mark_always_url = append_sid(CMS_PAGE_VIEWFORUM . '?' . POST_FORUM_URL . '=' . $forum_id . '&amp;' . POST_TOPIC_URL . '=' . $topic_id . '&amp;always_read=unset');
				$mark_always_txt = '<a href="' . $mark_always_url . '">' . $lang['upi2db_always_read_unset'] . '</a>';
				$mark_always_icon = '<a href="' . $mark_always_url . '"><img src="' . $images['topic_ar_switch_off'] . '" alt="' . $lang['upi2db_always_read_unset'] . '" title="' . $lang['upi2db_always_read_unset'] . '" /></a>';
			}
			else
			{
				if(sizeof($ar_f) && in_array($forum_id, $ar_f))
				{
					$mark_always_txt = $lang['upi2db_forum_is_always_read'];
					$mark_always_icon = '<img src="' . $images['topic_ar_switch_off'] . '" alt="' . $lang['upi2db_forum_is_always_read'] . '" title="' . $lang['upi2db_forum_is_always_read'] . '" />';
				}
				else
				{
					$mark_always_url = append_sid(CMS_PAGE_VIEWFORUM . '?' . POST_FORUM_URL . '=' . $forum_id . '&amp;' . POST_TOPIC_URL . '=' . $topic_id . '&amp;always_read=set');
					$mark_always_txt = '<a href="' . $mark_always_url . '">' . $lang['upi2db_always_read'] . '</a>';
					$mark_always_icon = '<a href="' . $mark_always_url . '"><img src="' . $images['topic_ar_switch_on'] . '" alt="' . $lang['upi2db_always_read'] . '" title="' . $lang['upi2db_always_read'] . '" /></a>';
				}
			}
			if(sizeof($ar_t) >= $config['upi2db_max_permanent_topics'])
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

if(!function_exists('mar_icon'))
{
	function mar_icon($folder_image, $folder_txt)
	{
		$mark_always_read_icon = '<img src="' . $folder_image . '" style="margin-right:4px;" alt="' . $folder_txt . '" title="' . $folder_txt . '" />';
		return $mark_always_read_icon;
	}
}

//################################### mark_post_viewtopic ##########################################
// Version 1.0.0

if(!function_exists('mark_post_viewtopic'))
{
	function mark_post_viewtopic($post_time_max, $unread, $topic_id, $forum_id, $post_id, $except_time, $topic_type)
	{
		global $config, $userdata, $lang, $images;

		if(is_array($unread['always_read']['forums']) && !in_array($forum_id, $unread['always_read']['forums']) && !in_array($topic_id, $unread['always_read']['topics']) && ($post_time_max > $except_time))
		{
			$mark_topic_unread_url = append_sid(CMS_PAGE_VIEWFORUM . '?' . POST_TOPIC_URL . '=' . $topic_id . '&amp;' . POST_FORUM_URL . '=' . $forum_id . '&amp;' . POST_POST_URL . '=' . $post_id . '&amp;tt=' . $topic_type . '&amp;do=mark_unread');
			$mark_topic_unread = '<a href="' . $mark_topic_unread_url . '"><img src="' . $images['unread_img'] . '" alt="' . $lang['upi2db_mark_post_unread'] . '" title="' . $lang['upi2db_mark_post_unread'] . '" /></a>';

			if(sizeof($unread['mark_posts']) >= $config['upi2db_max_mark_posts'])
			{
				$mark_topic_unread .= '&nbsp;<img src="' . $images['mark_img'] . '" alt="' . $lang['upi2db_post_cant_mark'] . '" title="' . $lang['upi2db_post_cant_mark'] . '" />';
			}
			else
			{
				if(!in_array($post_id, $unread['mark_posts']))
				{
					$mark_topic_unread_url = append_sid(CMS_PAGE_VIEWFORUM . '?' . POST_TOPIC_URL . '=' . $topic_id . '&amp;' . POST_FORUM_URL . '=' . $forum_id . '&amp;' . POST_POST_URL . '=' . $post_id . '&amp;tt=' . $topic_type . '&amp;do=mark_post');
					$mark_topic_unread .= '&nbsp;<a href="' . $mark_topic_unread_url . '"><img src="' . $images['mark_img'] . '" alt="' . $lang['upi2db_mark_post'] . '" title="' . $lang['upi2db_mark_post'] . '" /></a>';
				}
				else
				{
					$mark_topic_unread_url = append_sid(CMS_PAGE_VIEWFORUM . '?' . POST_TOPIC_URL . '=' . $topic_id . '&amp;' . POST_FORUM_URL . '=' . $forum_id . '&amp;' . POST_POST_URL . '=' . $post_id . '&amp;tt=' . $topic_type . '&amp;do=unmark_post');
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

if(!function_exists('index_display_new'))
{
	function index_display_new($unread)
	{
		global $lang, $images, $config, $unread_new_posts, $unread_edit_posts;

		$edit_posts = sizeof($unread['edit_posts']) - $unread_edit_posts;
		$new_posts = sizeof($unread['new_posts']) - $unread_new_posts;
		$unread_posts = $new_posts + $edit_posts;
		$always_read = sizeof($unread['always_read']['topics']);
		$mark_unread = sizeof($unread['mark_posts']);

		$max_perm_read = $config['upi2db_max_permanent_topics'];
		$max_mark = $config['upi2db_max_mark_posts'];

		$u_display_new['all'] = '<a href="' . append_sid(CMS_PAGE_SEARCH . '?search_id=upi2db&amp;s2=new') . '" class="mainmenu" title="' . $lang['Neue_Beitraege'] . ' (' . $new_posts . ') / ' . $lang['Editierte_Beitraege'] . ' (' . $edit_posts . ')"> U: ' . $unread_posts . '</a>';
		$u_display_new['all'] .= '<a href="' . append_sid(CMS_PAGE_SEARCH . '?search_id=upi2db&amp;s2=mark') . '" class="mainmenu" title="' . $lang['Ungelesen_Markiert'] . ' (' . $mark_unread . '/' . $max_mark . ')"> M: ' . $mark_unread . '</a>';
		$u_display_new['all'] .= '<a href="' . append_sid(CMS_PAGE_SEARCH . '?search_id=upi2db&amp;s2=perm') . '" class="mainmenu" title="' . $lang['Permanent_Gelesen'] . ' (' . $always_read . '/' . $max_perm_read . ')"> P: ' . $always_read . '</a>';

		$u_display_new['u'] = '<a href="' . append_sid(CMS_PAGE_SEARCH . '?search_id=upi2db&amp;s2=new') . '" class="mainmenu" title="' . $lang['Neue_Beitraege'] . ' (' . $new_posts . ') / ' . $lang['Editierte_Beitraege'] . ' (' . $edit_posts . ')">' . $lang['upi2db_u'] . ' (' . $unread_posts . ')</a>';
		$u_display_new['m'] = '<a href="' . append_sid(CMS_PAGE_SEARCH . '?search_id=upi2db&amp;s2=mark') . '" class="mainmenu" title="' . $lang['Ungelesen_Markiert'] . ' (' . $mark_unread . '/' . $max_mark . ')">' . $lang['upi2db_m'] . ' (' . $mark_unread . ')</a>';
		$u_display_new['p'] = '<a href="' . append_sid(CMS_PAGE_SEARCH . '?search_id=upi2db&amp;s2=perm') . '" class="mainmenu" title="' . $lang['Permanent_Gelesen'] . ' (' . $always_read . '/' . $max_perm_read . ')">' . $lang['upi2db_p'] . ' (' . $always_read . ')</a>';

		$u_display_new['unread'] = '<a href="' . append_sid(CMS_PAGE_SEARCH . '?search_id=upi2db&amp;s2=new') . '" class="mainmenu" title="' . $lang['Neue_Beitraege'] . ' (' . $new_posts . ') / ' . $lang['Editierte_Beitraege'] . ' (' . $edit_posts . ')">' . $lang['upi2db_unread'] . ' (' . $unread_posts . ')</a>';
		$u_display_new['marked'] = '<a href="' . append_sid(CMS_PAGE_SEARCH . '?search_id=upi2db&amp;s2=mark') . '" class="mainmenu" title="' . $lang['Ungelesen_Markiert'] . ' (' . $mark_unread . '/' . $max_mark . ')">' . $lang['upi2db_marked'] . ' (' . $mark_unread . ')</a>';
		$u_display_new['permanent'] = '<a href="' . append_sid(CMS_PAGE_SEARCH . '?search_id=upi2db&amp;s2=perm') . '" class="mainmenu" title="' . $lang['Permanent_Gelesen'] . ' (' . $always_read . '/' . $max_perm_read . ')">' . $lang['upi2db_perm_read'] . ' (' . $always_read . ')</a>';

		// Mighty Gorgon - Full Lang Explain For Quick Links - BEGIN
		$u_display_new['unread_string'] = $lang['upi2db_unread'] . ' (' . $unread_posts . ')';
		$u_display_new['u_string'] = $lang['upi2db_u'] . ' (' . $unread_posts . ')';
		$u_display_new['u_string_full'] = $lang['Neue_Beitraege'] . ' (' . $new_posts . ') / ' . $lang['Editierte_Beitraege'] . ' (' . $edit_posts . ')';
		$u_display_new['u_url'] = append_sid(CMS_PAGE_SEARCH . '?search_id=upi2db&amp;s2=new');

		$u_display_new['marked_string'] = $lang['upi2db_marked'] . ' (' . $mark_unread . ')';
		$u_display_new['m_string'] = $lang['upi2db_m'] . ' (' . $mark_unread . ')';
		$u_display_new['m_string_full'] = $lang['Ungelesen_Markiert'] . ' (' . $mark_unread . '/' . $max_mark . ')';
		$u_display_new['m_url'] = append_sid(CMS_PAGE_SEARCH . '?search_id=upi2db&amp;s2=mark');

		$u_display_new['permanent_string'] = $lang['upi2db_perm_read'] . ' (' . $always_read . ')';
		$u_display_new['p_string'] = $lang['upi2db_p'] . ' (' . $always_read . ')';
		$u_display_new['p_string_full'] = $lang['Permanent_Gelesen'] . ' (' . $always_read . '/' . $max_perm_read . ')';
		$u_display_new['p_url'] = append_sid(CMS_PAGE_SEARCH . '?search_id=upi2db&amp;s2=perm');;
		// Mighty Gorgon - Full Lang Explain For Quick Links - END

		return $u_display_new;
	}
}

//################################### always_read_forum ##########################################
// Version 1.0.0

if(!function_exists('always_read_forum'))
{
	function always_read_forum($forum_id, $always_read)
	{
		global $config, $userdata, $db, $lang;

		$user_id = $userdata['user_id'];
		$time_now = time();
		if ($always_read == 'set')
		{
			$sql = "INSERT INTO " . UPI2DB_ALWAYS_READ_TABLE . "
				(user_id, forum_id, last_update)
				VALUES ('$user_id' , '$forum_id', '$time_now')";
			$result = $db->sql_query($sql);

			$sql = "DELETE FROM " . UPI2DB_UNREAD_POSTS_TABLE . "
				WHERE user_id = $user_id
				AND forum_id = $forum_id";
			$db->sql_query($sql);
			$mark_always_read_text = $lang['upi2db_forum_is_always_read'];
		}
		else
		{
			$sql = "DELETE FROM " . UPI2DB_ALWAYS_READ_TABLE . "
				WHERE user_id = $user_id
				AND forum_id = $forum_id";
			$db->sql_query($sql);
			$mark_always_read_text = $lang['upi2db_forum_isnt_always_read'];
		}

		return $mark_always_read_text;
	}
}

//################################### viewtopic_calc_unread ##########################################
// Version 1.0.0

if(!function_exists('viewtopic_calc_unread'))
{
	function viewtopic_calc_unread($unread, $topic_id, $post_id, $forum_id, &$mini_post_img, &$mini_post_alt, &$unread_color, &$read_posts)
	{
		global $config, $userdata, $lang, $images;

		if (is_array($unread['always_read']['forums']) && (in_array($post_id, $unread['edit_posts']) || in_array($post_id, $unread['new_posts']) || in_array($post_id, $unread['mark_posts'])) && !in_array($forum_id, $unread['always_read']['forums']))
		{
			$mini_post_img = $images['icon_minipost_new'];
			$mini_post_alt = $lang['New_post'];

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

//################################### search_calc_unread_ip ##########################################
// Version 1.0.0

if(!function_exists('search_calc_unread_ip'))
{
	function search_calc_unread_ip($unread, $topic_id, $searchset, $i, &$mini_post_img, &$mini_post_alt, &$unread_color, &$folder_image, &$folder_alt)
	{
		global $config, $userdata, $lang, $images;

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

if(!function_exists('set_unread'))
{
	function set_unread($t, $f, $p, $unread, $do, $tt)
	{
		global $db, $config, $userdata, $lang;

		$user_id = $userdata['user_id'];
		$time = time();

		if((sizeof($unread['mark_posts']) >= $config['upi2db_max_mark_posts']) && ($do == 'mark_post'))
		{
			$mark_read_text = $lang['upi2db_post_cant_mark'];
			return $mark_read_text;
		}

		if(($do == 'mark_post') || ($do == 'mark_unread'))
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
			$result = $db->sql_query($sql);
		}
		else
		{
			$sql = "DELETE FROM " . UPI2DB_UNREAD_POSTS_TABLE . "
				WHERE post_id = " . $p . "
				AND user_id = " . $userdata['user_id'];
			$result = $db->sql_query($sql);
			$mark_read_text = $lang['upi2db_post_unmarked'];
		}
		return $mark_read_text;
	}
}

//################################### search_mark_as_read ##########################################
// Version 1.0.0

if(!function_exists('search_mark_as_read'))
{
	function search_mark_as_read($mar_topic_id)
	{
		global $config, $db, $userdata, $lang;

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
			$db->sql_query($sql);
		}
	}
}

// Below this line all functions have been moved from upi2db_orig_all.php

//################################### sync_database ##########################################
// Version 1.0.0

if(!function_exists('sync_database'))
{
	function sync_database($userdata)
	{
		global $config, $db;

		$time = time();

		if($userdata['user_upi2db_datasync'] > ($time - UPI2DB_RESYNC_TIME))
		{
			return;
		}

		$expired_post_time = $time - ($config['upi2db_auto_read'] * 86400);
		$del_mark_time = $time - ($config['upi2db_del_mark'] * 86400);
		$del_perm_time = $time - ($config['upi2db_del_perm'] * 86400);

		$always_read = $userdata['always_read'];
		$auth_forum_id = $userdata['auth_forum_id'];
		$always_read_forums = '';
		$always_read_topics = '';
		$user_id = $userdata['user_id'];
		$user_dbsync = $userdata['user_upi2db_datasync'];

		if(gmdate('Ymd',$config['upi2db_delete_old_data']) != gmdate('Ymd', time()))
		{
			delete_old_data($expired_post_time, $del_mark_time, $del_perm_time, $db);
		}

		if($always_read)
		{
			$always_read_forums = (sizeof($always_read['forums']) == 1)  ? $always_read['forums'][0] : implode(',', $always_read['forums']);
			$always_read_topics = (sizeof($always_read['topics']) == 1)  ? $always_read['topics'][0] : implode(',', $always_read['topics']);
		}

		$ar_forums = ($always_read_forums) ? 'AND forum_id NOT IN (' . $always_read_forums . ')' : '';
		$ar_topics = ($always_read_topics) ? 'AND topic_id NOT IN (' . $always_read_topics . ')' : '';
		$auth_forum = ($auth_forum_id) ? 'AND forum_id IN ('. $auth_forum_id .')' : '';
		$max_new_post = ($userdata['user_level'] != ADMIN) ? (($userdata['user_level'] != MOD) ? $config['upi2db_max_new_posts'] : $config['upi2db_max_new_posts_mod']): $config['upi2db_max_new_posts_admin'];
		// Edited By Mighty Gorgon - BEGIN
		$max_new_posts = ($max_new_posts == 0) ? UPI2DB_MAX_UNREAD_POSTS : $max_new_posts;
		$new_post_limit = ($max_new_post > 0) ? 'ORDER BY post_time DESC, post_edit_time DESC LIMIT ' . $max_new_post : 'ORDER BY post_time DESC, post_edit_time DESC';
		// Edited By Mighty Gorgon - END
		$dbsync = ($user_dbsync < $userdata['user_regdate']) ? $userdata['user_regdate'] : $user_dbsync;
		$copy_annoncments = (empty($user_dbsync)) ? 'OR topic_type != 0' : '';


		$sql = "SELECT post_id, topic_id, forum_id, user_id, status FROM " . UPI2DB_UNREAD_POSTS_TABLE . "
			WHERE user_id = '" . $userdata['user_id'] . "'
			AND status != 2";

		$post_ids = array();

		$db->sql_return_on_error(true);
		$result = $db->sql_query($sql);
		$db->sql_return_on_error(false);
		if ($result)
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
		$result = $db->sql_query($sql);

		$sql = "UPDATE " . USERS_TABLE . " SET user_upi2db_datasync = " . time() . "
			WHERE user_id = '" . $user_id . "'";
		$db->sql_query($sql);
		$userdata['db_sync'] = '1';
	}
}

//################################### select_always_read ##########################################
// Version 1.0.0

if(!function_exists('select_always_read'))
{
	function select_always_read($userdata)
	{
		global $db;
		$always_read['topics'] = array();
		$always_read['forums'] = array();
		$user_id = $userdata['user_id'];

		$sql = "SELECT topic_id, forum_id FROM " . UPI2DB_ALWAYS_READ_TABLE . "
			WHERE user_id = '" . $user_id . "'";
		$db->sql_return_on_error(true);
		$result = $db->sql_query($sql);
		$db->sql_return_on_error(false);
		if ($result)
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
			$db->sql_freeresult($result);
		}
		return $always_read;
	}
}

//################################### delete_read_posts ##########################################
// Version 1.0.0

if(!function_exists('delete_read_posts'))
{
	function delete_read_posts($read_posts)
	{
		global $userdata, $db;

		if (empty($read_posts))
		{
			return false;
		}
		else
		{
			$sql = "DELETE FROM " . UPI2DB_UNREAD_POSTS_TABLE . "
				WHERE post_id IN (" . $read_posts . ")
				AND user_id = " . $userdata['user_id'] . "
				AND status <> '2'";
			$result = $db->sql_query($sql);
		}
	}
}

//################################### except_time ##########################################
// Version 1.0.0

if(!function_exists('except_time'))
{
	function except_time()
	{
		global $config, $userdata;

		$save_time = time() - ($config['upi2db_auto_read'] * 86400);
		$except_time = ($userdata['user_regdate'] > $config['upi2db_install_time']) ? (($userdata['user_regdate'] > $save_time) ? $userdata['user_regdate'] : $save_time) : (($config['upi2db_install_time'] > $save_time) ? $config['upi2db_install_time'] : $save_time);

		return $except_time;
	}
}

//################################### viewforum_calc_unread ##########################################
// Version 1.0.0

if(!function_exists('viewforum_calc_unread'))
{
	function viewforum_calc_unread($unread, $topic_id, $topic_rowset, $i, $folder_new, $folder, &$folder_alt, &$folder_image, &$newest_post_img, &$upi2db_status)
	{
		global $config, $userdata, $lang, $images;

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

			$newest_post_img = '<a href="' . append_sid(CMS_PAGE_VIEWTOPIC . '?' . POST_TOPIC_URL . '=' . $topic_id . '&amp;' . POST_POST_URL . '=' . $post_id) . '#p' . $post_id . '"><img src="' . $images['icon_newest_reply'] . '" alt="' . $lang['View_newest_post'] . '" title="' . $lang['View_newest_post'] . '" /></a> ' . $upi2db_status;
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

if(!function_exists('marking_posts'))
{
	function marking_posts($forum_id = '')
	{
		global $db, $config, $userdata;

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

		$auth_forum_read = auth_forum_read($userdata);
		$sql_forum_auth = !empty($auth_forum_read) ? " OR (forum_id NOT IN(" . $auth_forum_read . "))" : "";
		// Edited By Mighty Gorgon - END

		$sql = "DELETE FROM " . UPI2DB_UNREAD_POSTS_TABLE . "
			WHERE user_id = " . $user_id . "
			AND (status != '2' " . $sql_add_mar . " " . $mp_forum . ")
			" . $sql_forum_auth;
		$result = $db->sql_query($sql);
	}
}

//################################### auth_forum_read ##########################################
// Version 1.0.0

if(!function_exists('auth_forum_read'))
{
	function auth_forum_read($userdata)
	{
		global $config, $db, $lang;

		$sql = "SELECT * FROM " . FORUMS_TABLE . " WHERE forum_type = " . FORUM_POST;
		$result = $db->sql_query($sql, 0, 'forums_', FORUMS_CACHE_FOLDER);

		$forum_data = array();
		while($row = $db->sql_fetchrow($result))
		{
			$forum_data[] = $row;
		}

		$is_auth_ary = array();
		$is_auth_ary = auth(AUTH_ALL, AUTH_LIST_ALL, $userdata, $forum_data);
		$auth_forum_id = '';
		for ($i = 0; $i < sizeof($forum_data); $i++)
		{
			if (($is_auth_ary[$forum_data[$i]['forum_id']]['auth_read']) && ($is_auth_ary[$forum_data[$i]['forum_id']]['auth_view']))
			{
				$auth_forum_id .= (!empty($auth_forum_id) ? ',' : '') . $forum_data[$i]['forum_id'];
			}
		}
		return $auth_forum_id;
	}
}

//################################### prune_upi2db ##########################################
// Version 1.0.0

if(!function_exists('prune_upi2db'))
{
	function prune_upi2db($sql_post)
	{
		global $config, $db, $userdata, $lang;

		$user_id = $userdata['user_id'];

		$sql = "DELETE FROM " . UPI2DB_UNREAD_POSTS_TABLE . "
			WHERE post_id IN (" . $sql_post . ")";
		$db->sql_query($sql);

		$sql = "DELETE FROM " . UPI2DB_LAST_POSTS_TABLE . "
			WHERE post_id IN (" . $sql_post . ")";
		$db->sql_query($sql);
	}
}

//################################### index_display_new removed from upi2db_orig_ip.php ##########################################

/*
//################################### index_display_new ##########################################
// Version 1.0.0

if(!function_exists('index_display_new'))
{
	function index_display_new($unread)
	{
		global $lang, $images, $config, $unread_new_posts, $unread_edit_posts;

		$edit_posts = sizeof($unread['edit_posts']) - $unread_edit_posts;
		$new_posts = sizeof($unread['new_posts']) - $unread_new_posts;
		$unread_posts = $new_posts + $edit_posts;
		$always_read = sizeof($unread['always_read']['topics']);
		$mark_unread = sizeof($unread['mark_posts']);

		$max_perm_read = $config['upi2db_max_permanent_topics'];
		$max_mark = $config['upi2db_max_mark_posts'];

		$u_display_new['all'] = '<a href="' . append_sid(CMS_PAGE_SEARCH . '?search_id=upi2db&s2=new') . '" class="mainmenu" title="' . $lang['Neue_Beitraege'] . ' (' . $new_posts . ') / ' . $lang['Editierte_Beitraege'] . ' (' . $edit_posts . ')" alt="' . $lang['Neue_Beitraege'] . ' (' . $new_posts . ') / ' . $lang['Editierte_Beitraege'] . ' (' . $edit_posts . ')"> U: ' . $unread_posts . '</a>';
		$u_display_new['all'] .= '<a href="' . append_sid(CMS_PAGE_SEARCH . '?search_id=upi2db&s2=mark') . '" class="mainmenu" title="' . $lang['Ungelesen_Markiert'] . ' (' . $mark_unread . '/' . $max_mark . ')" alt="' . $lang['Ungelesen_Markiert'] . ' (' . $mark_unread . '/' . $max_mark . ')"> M: ' . $mark_unread . '</a>';
		$u_display_new['all'] .= '<a href="' . append_sid(CMS_PAGE_SEARCH . '?search_id=upi2db&s2=perm') . '" class="mainmenu" title="' . $lang['Permanent_Gelesen'] . ' (' . $always_read . '/' . $max_perm_read . ')" alt="' . $lang['Permanent_Gelesen'] . ' (' . $always_read . '/' . $max_perm_read . ')"> P: ' . $always_read . '</a>';

		$u_display_new['u'] = '<a href="' . append_sid(CMS_PAGE_SEARCH . '?search_id=upi2db&s2=new') . '" class="mainmenu" title="' . $lang['Neue_Beitraege'] . ' (' . $new_posts . ') / ' . $lang['Editierte_Beitraege'] . ' (' . $edit_posts . ')" alt="' . $lang['Neue_Beitraege'] . ' (' . $new_posts . ') / ' . $lang['Editierte_Beitraege'] . ' (' . $edit_posts . ')">' . $lang['upi2db_u'] . ' (' . $unread_posts . ')</a>';
		$u_display_new['m'] = '<a href="' . append_sid(CMS_PAGE_SEARCH . '?search_id=upi2db&s2=mark') . '" class="mainmenu" title="' . $lang['Ungelesen_Markiert'] . ' (' . $mark_unread . '/' . $max_mark . ')" alt="' . $lang['Ungelesen_Markiert'] . ' (' . $mark_unread . '/' . $max_mark . ')">' . $lang['upi2db_m'] . ' (' . $mark_unread . ')</a>';
		$u_display_new['p'] = '<a href="' . append_sid(CMS_PAGE_SEARCH . '?search_id=upi2db&s2=perm') . '" class="mainmenu" title="' . $lang['Permanent_Gelesen'] . ' (' . $always_read . '/' . $max_perm_read . ')" alt="' . $lang['Permanent_Gelesen'] . ' (' . $always_read . '/' . $max_perm_read . ')">' . $lang['upi2db_p'] . ' (' . $always_read . ')</a>';

		$u_display_new['unread'] = '<a href="' . append_sid(CMS_PAGE_SEARCH . '?search_id=upi2db&s2=new') . '" class="mainmenu" title="' . $lang['Neue_Beitraege'] . ' (' . $new_posts . ') / ' . $lang['Editierte_Beitraege'] . ' (' . $edit_posts . ')" alt="' . $lang['Neue_Beitraege'] . ' (' . $new_posts . ') / ' . $lang['Editierte_Beitraege'] . ' (' . $edit_posts . ')">' . $lang['upi2db_unread'] . ' (' . $unread_posts . ')</a>';
		$u_display_new['marked'] = '<a href="' . append_sid(CMS_PAGE_SEARCH . '?search_id=upi2db&s2=mark') . '" class="mainmenu" title="' . $lang['Ungelesen_Markiert'] . ' (' . $mark_unread . '/' . $max_mark . ')" alt="' . $lang['Ungelesen_Markiert'] . ' (' . $mark_unread . '/' . $max_mark . ')">' . $lang['upi2db_marked'] . ' (' . $mark_unread . ')</a>';
		$u_display_new['permanent'] = '<a href="' . append_sid(CMS_PAGE_SEARCH . '?search_id=upi2db&s2=perm') . '" class="mainmenu" title="' . $lang['Permanent_Gelesen'] . ' (' . $always_read . '/' . $max_perm_read . ')" alt="' . $lang['Permanent_Gelesen'] . ' (' . $always_read . '/' . $max_perm_read . ')">' . $lang['upi2db_perm_read'] . ' (' . $always_read . ')</a>';

		// Mighty Gorgon - Full Lang Explain For Quick Links - BEGIN
		$u_display_new['unread_string'] = $lang['upi2db_unread'] . ' (' . $unread_posts . ')';
		$u_display_new['u_string'] = $lang['upi2db_u'] . ' (' . $unread_posts . ')';
		$u_display_new['u_string_full'] = $lang['Neue_Beitraege'] . ' (' . $new_posts . ') / ' . $lang['Editierte_Beitraege'] . ' (' . $edit_posts . ')';
		$u_display_new['u_url'] = append_sid(CMS_PAGE_SEARCH . '?search_id=upi2db&amp;s2=new');

		$u_display_new['marked_string'] = $lang['upi2db_marked'] . ' (' . $mark_unread . ')';
		$u_display_new['m_string'] = $lang['upi2db_m'] . ' (' . $mark_unread . ')';
		$u_display_new['m_string_full'] = $lang['Ungelesen_Markiert'] . ' (' . $mark_unread . '/' . $max_mark . ')';
		$u_display_new['m_url'] = append_sid(CMS_PAGE_SEARCH.'?search_id=upi2db&amp;s2=mark');

		$u_display_new['permanent_string'] = $lang['upi2db_perm_read'] . ' (' . $always_read . ')';
		$u_display_new['p_string'] = $lang['upi2db_p'] . ' (' . $always_read . ')';
		$u_display_new['p_string_full'] = $lang['Permanent_Gelesen'] . ' (' . $always_read . '/' . $max_perm_read . ')';
		$u_display_new['p_url'] = append_sid(CMS_PAGE_SEARCH . '?search_id=upi2db&amp;s2=perm');;
		// Mighty Gorgon - Full Lang Explain For Quick Links - END

		return $u_display_new;
	}
}
*/

?>