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

/**
* Topics class
*/
class class_topics
{

	var $cat_id = 0;
	var $forum_id = 0;
	var $topic_id = 0;
	var $post_id = 0;
	var $draft_id = 0;

	/**
	* Initialize vars
	*/
	function var_init($get = false)
	{
		global $cat_id, $forum_id, $topic_id, $post_id, $draft_id;
		global $cat_id_append, $forum_id_append, $topic_id_append, $post_id_append, $post_id_append_url;

		if ($get)
		{
			$cat_id = request_var(POST_CAT_URL, 0);
			$cat_id = ($cat_id < 0) ? 0 : $cat_id;

			$forum_id = request_var(POST_FORUM_URL, 0);
			$forum_id = ($forum_id < 0) ? 0 : $forum_id;
			if (empty($forum_id) && isset($_GET['forum']))
			{
				$forum_id = request_var('forum', 0);
				$forum_id = ($forum_id < 0) ? 0 : $forum_id;
			}

			$topic_id = request_var(POST_TOPIC_URL, 0);
			$topic_id = ($topic_id < 0) ? 0 : $topic_id;
			if (empty($topic_id) && isset($_GET['topic']))
			{
				$topic_id = request_var('topic', 0);
				$topic_id = ($topic_id < 0) ? 0 : $topic_id;
			}

			$post_id = request_var(POST_POST_URL, 0);
			$post_id = ($post_id < 0) ? 0 : $post_id;

			$draft_id = request_var('d', 0);
			$draft_id = ($draft_id < 0) ? 0 : $draft_id;

			$cat_id_append = (!empty($cat_id) ? (POST_CAT_URL . '=' . $cat_id) : '');
			$forum_id_append = (!empty($forum_id) ? (POST_FORUM_URL . '=' . $forum_id) : '');
			$topic_id_append = (!empty($topic_id) ? (POST_TOPIC_URL . '=' . $topic_id) : '');
			$post_id_append = (!empty($post_id) ? (POST_POST_URL . '=' . $post_id) : '');
			$post_id_append_url = (!empty($post_id) ? ('#p' . $post_id) : '');
		}

		$this->cat_id = !empty($cat_id) ? $cat_id : 0;
		$this->forum_id = !empty($forum_id) ? $forum_id : 0;
		$this->topic_id = !empty($topic_id) ? $topic_id : 0;
		$this->post_id = !empty($post_id) ? $post_id : 0;
		$this->draft_id = !empty($draft_id) ? $draft_id : 0;

		return true;
	}

	/**
	* Initialize meta_content
	*/
	function meta_content_init($row_data, $mode = 'topic')
	{
		global $meta_content;

		$meta_content['cat_id'] = 0;
		$meta_content['forum_id'] = 0;
		$meta_content['topic_id'] = 0;
		$meta_content['post_id'] = 0;

		if ($mode == 'topic')
		{
			$meta_content['forum_id'] = $row_data['forum_id'];
			$meta_content['forum_name'] = strip_tags(stripslashes($row_data['forum_name']));
			$meta_content['forum_name_clean'] = $row_data['forum_name_clean'];

			$meta_content['topic_id'] = $row_data['topic_id'];
			$meta_content['topic_title'] = strip_tags(stripslashes($row_data['topic_title']));
			$meta_content['topic_title_clean'] = $row_data['topic_title_clean'];
			$meta_content['topic_tags'] = $row_data['topic_tags'];
			$meta_content['title_compl_infos'] = $row_data['title_compl_infos'];

			$meta_content['page_title'] = $meta_content['forum_name'] . ' :: ' . $meta_content['topic_title'];
			$meta_content['description'] = $meta_content['forum_name'] . ' - ' . $meta_content['topic_title'];
			$meta_content['keywords'] = $meta_content['topic_tags'];
			$meta_content['keywords'] = empty($meta_content['keywords']) ? str_replace(array(' ', ',, '), array(', ', ', '), ip_clean_string($meta_content['topic_title'], $lang['ENCODING'], true)) : $meta_content['keywords'];
		}

		if ($mode == 'forum')
		{
			$meta_content['forum_name'] = strip_tags(stripslashes($row_data['forum_name']));
			$meta_content['forum_name_clean'] = $row_data['forum_name_clean'];

			$meta_content['page_title'] = $meta_content['forum_name'];
			$meta_content['description'] = $meta_content['forum_name'] . (empty($row_data['forum_desc']) ? '' : (' - ' . strip_tags(stripslashes($row_data['forum_desc']))));
			$meta_content['keywords'] = $meta_content['forum_name'] . ', ';
		}

		return $meta_content;
	}

	/*
	* Builds icons for topics
	*/
	function build_topic_icon_link($forum_id, $topic_id, $topic_type, $topic_reg, $topic_replies, $topic_news_id, $topic_vote, $topic_status, $topic_moved_id, $topic_post_time, $user_replied, $replies, $unread)
	{
		//build_topic_icon_link($forum_id, $topic_rowset[$i]['topic_id'], $topic_rowset[$i]['topic_type'], $topic_rowset[$i]['topic_replies'], $topic_rowset[$i]['news_id'], $topic_rowset[$i]['topic_vote'], $topic_rowset[$i]['topic_status'], $topic_rowset[$i]['topic_moved_id'], $topic_rowset[$i]['post_time'], $user_replied, $replies, $unread);
		global $config, $lang, $images, $userdata, $tracking_topics, $tracking_forums, $forum_id_append, $topic_id_append;

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
				//$topic_link['icon'] = '<img src="' . $images['vf_topic_nor'] . '" alt="' . $lang['Topic'] . '" title="' . $lang['Topic'] . '" /> ';
				// Better empty icon for normal topics?
				$topic_link['icon'] = '';
				// Event Registration - BEGIN
				if($topic_reg)
				{
					$topic_link['type'] = '<img src="' . $images['vf_topic_event'] . '" alt="' . $lang['Topic_Event_nb'] . '" title="' . $lang['Topic_Event_nb'] . '" /> ' . $lang['Topic_Event'] . ' ';
					$topic_link['icon'] .= '<img src="' . $images['vf_topic_event'] . '" alt="' . $lang['Topic_Event_nb'] . '" title="' . $lang['Topic_Event_nb'] . '" /> ';
				}
				// Event Registration - END
				$topic_link['class'] = 'topiclink';
				if($replies >= $config['hot_threshold'])
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
				$topic_link['icon'] = '<img src="' . $images['vf_topic_news'] . '" alt="' . $lang['Topic_News_nb'] . '" title="' . $lang['Topic_News_nb'] . '" /> ' . $topic_link['icon'];
			}

			if($topic_vote)
			{
				//$topic_link['type'] .= $lang['Topic_Poll'] . ' ';
				$topic_link['type'] = '<img src="' . $images['vf_topic_poll'] . '" alt="' . $lang['Topic_Poll_nb'] . '" title="' . $lang['Topic_Poll_nb'] . '" /> ' . $topic_link['type'];
				$topic_link['icon'] = '<img src="' . $images['vf_topic_poll'] . '" alt="' . $lang['Topic_Poll_nb'] . '" title="' . $lang['Topic_Poll_nb'] . '" /> ' . $topic_link['icon'];
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
					if(!empty($tracking_topics) || !empty($tracking_forums) || isset($_COOKIE[$config['cookie_name'] . '_f_all']))
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

						if(isset($_COOKIE[$config['cookie_name'] . '_f_all']))
						{
							if(intval($_COOKIE[$config['cookie_name'] . '_f_all']) >= $topic_post_time)
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
				$upi_calc = $this->upi_calc_unread_simple($unread, $topic_link['topic_id']);
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
				$newest_post_img_url = append_sid(CMS_PAGE_VIEWTOPIC . '?' . $topic_link['forum_id_append'] . '&amp;' . $topic_link['topic_id_append'] . '&amp;view=newest');
			}
			else
			{
				$newest_post_img_url = append_sid(CMS_PAGE_VIEWTOPIC . '?' . $topic_link['forum_id_append'] . '&amp;' . $topic_link['topic_id_append'] . '&amp;' . POST_POST_URL . '=' . $upi_calc['newest_post_id']) . '#p' . $upi_calc['newest_post_id'];
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

	/*
	* UPI get unread messages
	*/
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
			$min_edit_post_id = (empty($unread[$topic_id]['edit_posts'])) ? '99999999' : min($unread[$topic_id]['edit_posts']);
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

	/*
	* Get topics where user replied
	*/
	function user_replied_array($topic_rowset)
	{
		global $userdata, $db, $config;
		$user_topics = array();
		if (($userdata['user_id'] != ANONYMOUS) && !$userdata['is_bot'] && $config['enable_own_icons'])
		{
			// get all the topic ids to display
			$topic_ids = array();
			for ($i = 0; $i < sizeof($topic_rowset); $i++)
			{
				$topic_ids[] = intval(substr($topic_rowset[$i]['topic_id'], 0));
			}
			// check if the user replied to
			if (!empty($topic_ids))
			{
				// check the posts
				$s_topic_ids = implode(', ', $topic_ids);
				$sql = "SELECT DISTINCT topic_id FROM " . POSTS_TABLE . "
						WHERE topic_id IN (" . $s_topic_ids . ")
							AND poster_id = " . $userdata['user_id'];
				$result = $db->sql_query($sql);

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
	* Get topic prefixes
	*/
	function get_topic_prefixes()
	{
		global $db;
		$sql = "SELECT * FROM " . TITLE_INFOS_TABLE . " ORDER BY title_info ASC";
		$result = $db->sql_query($sql, 0, 'topics_prefixes_', TOPICS_CACHE_FOLDER);
		$topic_prefixes = array();
		while ($row = $db->sql_fetchrow($result))
		{
			$topic_prefixes[$row['id']] = $row['title_info'];
		}
		$db->sql_freeresult($result);
		return $topic_prefixes;
	}

}

?>