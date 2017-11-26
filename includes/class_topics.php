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
			$meta_content['topic_label_compiled'] = $row_data['topic_label_compiled'];

			/*
			$meta_content['page_title'] = $meta_content['forum_name'] . ' :: ' . $meta_content['topic_title'];
			$meta_content['description'] = $meta_content['forum_name'] . ' - ' . $meta_content['topic_title'];
			*/
			$meta_content['page_title'] = $meta_content['topic_title'];
			$meta_content['description'] = $meta_content['topic_title'];
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
	function build_topic_icon_link($forum_id, $topic_id, $topic_type, $topic_reg, $topic_replies, $topic_news_id, $poll_start, $topic_status, $topic_moved_id, $topic_post_time, $user_replied, $replies)
	{
		//build_topic_icon_link($forum_id, $topic_rowset[$i]['topic_id'], $topic_rowset[$i]['topic_type'], $topic_rowset[$i]['topic_replies'], $topic_rowset[$i]['news_id'], $topic_rowset[$i]['poll_start'], $topic_rowset[$i]['topic_status'], $topic_rowset[$i]['topic_moved_id'], $topic_rowset[$i]['post_time'], $user_replied, $replies);
		global $config, $lang, $images, $user, $tracking_topics, $tracking_forums, $forum_id_append, $topic_id_append;

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

			if($poll_start > 0)
			{
				//$topic_link['type'] .= $lang['Topic_Poll'] . ' ';
				$topic_link['type'] = '<img src="' . $images['vf_topic_poll'] . '" alt="' . $lang['Topic_Poll_nb'] . '" title="' . $lang['Topic_Poll_nb'] . '" /> ' . $topic_link['type'];
				$topic_link['icon'] = '<img src="' . $images['vf_topic_poll'] . '" alt="' . $lang['Topic_Poll_nb'] . '" title="' . $lang['Topic_Poll_nb'] . '" /> ' . $topic_link['icon'];
			}
		}

		if($user->data['session_logged_in'])
		{
			//-----------------------------------------------------------
			// UPI2DB - BEGIN
			if(!$user->data['upi2db_access'] || !is_array($user->data['upi2db_unread']))
			{
			// UPI2DB - END
			//------------------------------------------------------------

				if($topic_post_time > $user->data['user_lastvisit'])
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
			// UPI2DB - BEGIN
			}
			else
			{
				$upi_calc = $this->upi_calc_unread_simple($topic_link['topic_id']);
				$unread_topics = $upi_calc['unread'];
				//$topic_link['type'] = $upi_calc['upi_prefix'] . $topic_link['type'];
				$upi_calc['newest_post_id'] = $post_id;
			}
			// UPI2DB - END
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
	function upi_calc_unread_simple($topic_id)
	{
		global $user, $lang;

		$upi2db_status = '';
		if (!empty($user->data['upi2db_unread']) && (in_array($topic_id, $user->data['upi2db_unread']['new_topics']) || in_array($topic_id, $user->data['upi2db_unread']['edit_topics'])))
		{
			if((in_array($topic_id, $user->data['upi2db_unread']['new_topics']) && in_array($topic_id, $user->data['upi2db_unread']['edit_topics'])) && $user->data['user_upi2db_new_word'] && $user->data['user_upi2db_edit_word'])
			{
				$upi2db_status = $lang['upi2db_post_edit'] . $lang['upi2db_post_and'] . $lang['upi2db_post_new'] . ': ';
			}
			else
			{
				if(in_array($topic_id, $user->data['upi2db_unread']['new_topics']) && $user->data['user_upi2db_new_word'])
				{
					$upi2db_status = $lang['upi2db_post_new'] . ': ';
				}

				if(in_array($topic_id, $user->data['upi2db_unread']['edit_topics']) && $user->data['user_upi2db_edit_word'])
				{
					$upi2db_status = $lang['upi2db_post_edit'] . ': ';
				}
			}
			$min_new_post_id = (empty($user->data['upi2db_unread'][$topic_id]['new_posts'])) ? '99999999' : min($user->data['upi2db_unread'][$topic_id]['new_posts']);
			$min_edit_post_id = (empty($user->data['upi2db_unread'][$topic_id]['edit_posts'])) ? '99999999' : min($user->data['upi2db_unread'][$topic_id]['edit_posts']);
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
		global $user, $db, $config;
		$user_topics = array();
		if (($user->data['user_id'] != ANONYMOUS) && !$user->data['is_bot'] && $config['enable_own_icons'])
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
							AND poster_id = " . $user->data['user_id'];
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
	* Get topic labels
	*/
	function get_topics_labels()
	{
		global $db, $cache, $config;
		$sql = "SELECT * FROM " . TOPICS_LABELS_TABLE . " ORDER BY label_name ASC";
		$result = $db->sql_query($sql, 0, 'topics_labels_', TOPICS_CACHE_FOLDER);
		$topic_labels = array();
		while ($row = $db->sql_fetchrow($result))
		{
			$topic_labels[$row['id']] = $row;
		}
		$db->sql_freeresult($result);
		return $topic_labels;
	}

	/*
	* Generate topic labels select box
	*/
	function gen_topics_labels_select()
	{
		global $db, $cache, $config, $user, $bbcode, $lang;

		/*
		// SELECT BOX doesn't allow styling... so just use NAME in the select box...
		// Temporary allow all BBCode for Quick Title, and store current vars to temp arrays
		if (!class_exists('bbcode')) include(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);
		if (empty($bbcode)) $bbcode = new bbcode();
		$bbcode_allow_html_tmp = $bbcode->allow_html;
		$bbcode_allow_bbcode_tmp = $bbcode->allow_bbcode;
		$bbcode_allow_smilies_tmp = $bbcode->allow_smilies;
		*/

		$topics_labels = $this->get_topics_labels();
		$topics_labels_options = '';
		foreach ($topics_labels as $label_id => $label_data)
		{
			// SELECT BOX doesn't allow styling... so just use NAME in the select box...
			//$label_compiled = $this->gen_label_compiled($label_data);
			$label_name = $label_data['label_name'];
			$label_style = '';
			if (!empty($label_data['label_bg_color']) || !empty($label_data['label_text_color']))
			{
				$label_style = ' style="' . (!empty($label_data['label_bg_color']) ? 'background-color: ' . $label_data['label_bg_color'] . ';' : '') . (!empty($label_data['label_text_color']) ? 'color: ' . $label_data['label_text_color'] . ';' : '') . '"';
			}
			$topics_labels_options .= '<option value="' . $label_data['id'] . '"' . $label_style . '>' . $label_name . '</option>';
		}

		/*
		// SELECT BOX doesn't allow styling... so just use NAME in the select box...
		// Restore BBCode status...
		$bbcode->allow_html = $bbcode_allow_html_tmp;
		$bbcode->allow_bbcode = $bbcode_allow_bbcode_tmp;
		$bbcode->allow_smilies = $bbcode_allow_smilies_tmp;
		*/

		$topic_labels_select .= '<select name="label_id">';
		$topic_labels_select .= '<option value="0">---</option>';
		$topic_labels_select .= $topics_labels_options;
		$topic_labels_select .= '</select>&nbsp;';

		return $topic_labels_select;
	}

	/**
	* Generate the label
	*/
	function gen_label_compiled($label_data)
	{
		global $db, $cache, $config, $user, $bbcode, $lang;

		$bbcode->allow_html = in_array($label_data['label_code_switch'], array(2, 3)) ? true : false;
		$bbcode->allow_bbcode = in_array($label_data['label_code_switch'], array(1, 3)) ? true : false;
		$bbcode->allow_smilies = in_array($label_data['label_code_switch'], array(1, 2, 3)) ? true : false;
		$label_code = $label_data['label_code'];
		$label_code = in_array($label_data['label_code_switch'], array(1, 2, 3)) ? $bbcode->parse($label_code) : htmlspecialchars($label_code);
		$label_date_format = !empty($label_data['date_format']) ? $label_data['date_format'] : $config['default_dateformat'];
		$label_date = create_date($label_date_format, time(), $config['board_timezone']);
		$label_code = str_replace('%mod%', $user->data['username'], $label_code);
		$label_code = str_replace('%date%', $label_date, $label_code);

		$label_compiled = $label_code;

		if (!empty($label_data['label_icon']))
		{
			$label_compiled = '<i class="fa ' . $label_data['label_icon'] . '"></i>' . ' ' . $label_compiled;
		}
		if (!empty($label_data['label_bg_color']) || !empty($label_data['label_text_color']))
		{
			$label_compiled = '<span class="label" style="' . (!empty($label_data['label_bg_color']) ? 'background-color: ' . $label_data['label_bg_color'] . ';' : '') . (!empty($label_data['label_text_color']) ? 'color: ' . $label_data['label_text_color'] . ';' : '') . '">' . $label_compiled . '</span>';
		}

		return $label_compiled;
	}

	/*
	* Generate topic title
	*/
	function generate_topic_title($topic_id, $topic_data, $max_title_length)
	{
		global $config, $bbcode, $lang, $lofi;

		$max_title_length = (((int) $max_title_length > 255) || ($max_title_length < 15)) ? 255 : $max_title_length;
		$topic_title = censor_text($topic_data['topic_title']);
		$topic_title_clean = (empty($topic_data['topic_title_clean'])) ? substr(ip_clean_string($topic_title, $lang['ENCODING']), 0, 254) : $topic_data['topic_title_clean'];
		if (empty($topic_data['topic_title_clean']))
		{
			if (!function_exists('update_clean_topic_title'))
			{
				@include_once(IP_ROOT_PATH . 'includes/functions_topics.' . PHP_EXT);
			}
			update_clean_topic_title($topic_id, $topic_title_clean);
		}

		$topic_title_label = (empty($topic_data['topic_label_compiled'])) ? '' : trim($topic_data['topic_label_compiled']) . ' ';
		// Convert and clean special chars!
		$topic_title = htmlspecialchars_clean($topic_title);
		// SMILEYS IN TITLE - BEGIN
		if (($config['smilies_topic_title'] == true) && !$lofi)
		{
			if (!class_exists('bbcode')) include(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);
			if (empty($bbcode)) $bbcode = new bbcode();
			$bbcode->allow_smilies = (($config['allow_smilies'] && $topic_data['enable_smilies']) ? true : false);
			$topic_title = $bbcode->parse_only_smilies($topic_title);
		}
		// SMILEYS IN TITLE - END
		$topic_title = $topic_title_label . $topic_title;
		$topic_title_plain = htmlspecialchars(strip_tags($topic_title));
		$topic_title_short = $topic_title;
		if (strlen($topic_title) > ($max_title_length - 3))
		{
			// remove tags from the short version, in case a smiley or a topic lavel is in there
			$topic_title_short = substr(strip_tags($topic_title), 0, intval($max_title_length)) . '...';
		}

		$topic_title_data = array(
			'title' => $topic_title,
			'title_clean' => $topic_title_clean,
			'title_plain' => $topic_title_plain,
			'title_label' => $topic_title_label,
			'title_short' => $topic_title_short,
		);

		return $topic_title_data;
	}

	/*
	* Fetch posts
	*/
	function fetch_posts($forum_sql, $number_of_posts, $text_length, $show_portal = false, $sort_mode = 0, $single_post = false, $only_auth_view = true)
	{
		global $db, $cache, $config, $user, $bbcode, $lofi;

		if (!class_exists('bbcode') || empty($bbcode))
		{
			@include_once(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);
		}

		$except_forums = build_exclusion_forums_list($only_auth_view);

		$add_to_sql = '';
		if (empty($single_post) && !empty($forum_sql))
		{
			$except_forums_exp = explode(',', str_replace(' ', '', $except_forums));
			$allowed_forums_exp = explode(',', str_replace(' ', '', $forum_sql));
			$except_forums = '';
			for ($e = 0; $e < sizeof($except_forums_exp); $e++)
			{
				if (!in_array($except_forums_exp[$e], $allowed_forums_exp))
				{
					$except_forums .= ($except_forums == '') ? $except_forums_exp[$e] : (', ' . $except_forums_exp[$e]);
				}
			}
			$add_to_sql .= ' AND t.forum_id IN (' . $forum_sql . ')';
			$add_to_sql .= ' AND t.forum_id NOT IN (' . $except_forums . ')';
		}
		else
		{
			$add_to_sql .= ' AND t.forum_id NOT IN (' . $except_forums . ')';
		}

		if (!empty($show_portal))
		{
			$add_to_sql .= ' AND t.topic_show_portal = 1';
		}

		if ($sort_mode == 1)
		{
			// Random
			$order_sql = 'RAND()';
		}
		elseif ($sort_mode == 2)
		{
			// Alphabetical
			$order_sql = 't.topic_title ASC';
		}
		else
		{
			// Recent
			$order_sql = 't.topic_time DESC';
		}

		if ($number_of_posts != 0)
		{
			$limit_sql = ' LIMIT 0,' . $number_of_posts;
		}
		else
		{
			$limit_sql = '';
		}

		if (!empty($single_post))
		{
			$single_post_id = $forum_sql;
			$sql = "SELECT p.post_id, p.topic_id, p.forum_id, p.enable_html, p.enable_bbcode, p.enable_smilies, p.post_attachment, p.enable_autolinks_acronyms, p.post_text, p.post_text_compiled, t.forum_id, t.topic_time, t.topic_title, t.topic_first_post_id, t.topic_attachment, t.topic_views, t.topic_replies, u.username, u.user_id, u.user_active, u.user_color
					FROM " . POSTS_TABLE . " AS p, " . TOPICS_TABLE . " AS t, " . USERS_TABLE . " AS u
					WHERE p.post_id = '" . $single_post_id . "'
						" . $add_to_sql . "
						AND t.topic_id = p.topic_id
						AND p.poster_id = u.user_id";
		}
		else
		{
			$sql = "SELECT t.topic_id, t.topic_time, t.topic_title, t.forum_id, t.topic_poster, t.topic_first_post_id, t.topic_status, t.topic_show_portal, t.topic_attachment, t.topic_views, t.topic_replies, u.username, u.user_id, u.user_active, u.user_color, p.post_id, p.enable_html, p.enable_bbcode, p.enable_smilies, p.post_attachment, p.enable_autolinks_acronyms, p.post_text, p.post_text_compiled
					FROM " . TOPICS_TABLE . " AS t, " . USERS_TABLE . " AS u, " . POSTS_TABLE . " AS p
					WHERE t.topic_time <= " . time() . "
						" . $add_to_sql . "
						AND t.topic_poster = u.user_id
						AND t.topic_first_post_id = p.post_id
						AND t.topic_status <> 2
					ORDER BY " . $order_sql . $limit_sql;
		}
		// query the database
		$result = $db->sql_query($sql);

		// fetch all postings
		$posts = array();
		if ($row = $db->sql_fetchrow($result))
		{
			$i = 0;
			do
			{
				$posts[$i]['enable_bbcode'] = $row['enable_bbcode'];
				$posts[$i]['enable_html'] = $row['enable_html'];
				$posts[$i]['enable_smilies'] = $row['enable_smilies'];
				$posts[$i]['enable_autolinks_acronyms'] = $row['enable_autolinks_acronyms'];
				$posts[$i]['post_text'] = $row['post_text'];
				$posts[$i]['forum_id'] = $row['forum_id'];
				$posts[$i]['topic_id'] = $row['topic_id'];
				$posts[$i]['topic_first_post_id'] = $row['topic_first_post_id'];
				$posts[$i]['topic_views'] = $row['topic_views'];
				$posts[$i]['topic_replies'] = $row['topic_replies'];
				$posts[$i]['topic_time'] = create_date_ip($config['default_dateformat'], $row['topic_time'], $config['board_timezone']);
				$posts[$i]['topic_title'] = $row['topic_title'];
				$posts[$i]['user_id'] = $row['user_id'];
				$posts[$i]['username'] = $row['username'];
				$posts[$i]['user_active'] = $row['user_active'];
				$posts[$i]['user_color'] = $row['user_color'];
				$posts[$i]['topic_attachment'] = $row['topic_attachment'];
				$posts[$i]['post_id'] = $row['post_id'];
				$posts[$i]['post_attachment'] = $row['post_attachment'];

				if ($text_length >= 0)
				{
					$message = $posts[$i]['post_text'];
					$message_compiled = (empty($posts[$i]['post_text_compiled']) || !empty($user->data['session_logged_in']) || !empty($config['posts_precompiled'])) ? false : $posts[$i]['post_text_compiled'];

					$bbcode->allow_bbcode = ($config['allow_bbcode'] && $user->data['user_allowbbcode'] && $posts[$i]['enable_bbcode']) ? true : false;
					$bbcode->allow_html = ((($config['allow_html'] && $user->data['user_allowhtml']) || $config['allow_html_only_for_admins']) && $posts[$i]['enable_html']) ? true : false;
					$bbcode->allow_smilies = ($config['allow_smilies'] && $posts[$i]['enable_smilies'] && !$lofi) ? true : false;

					$clean_tags = false;
					if ((strlen($posts[$i]['post_text']) > $text_length) && ($text_length > 0))
					{
						$clean_tags = true;
						$posts[$i]['striped'] = 1;
					}

					$posts[$i]['post_text'] = ($message_compiled === false) ? $bbcode->parse($posts[$i]['post_text'], '', false, $clean_tags) : $message_compiled;

					if (!empty($clean_tags))
					{
						$posts[$i]['post_text'] = (strlen($posts[$i]['post_text']) > $text_length) ? truncate_html_string($posts[$i]['post_text'], $text_length) : $posts[$i]['post_text'];
					}

					$posts[$i]['post_text'] = censor_text($posts[$i]['post_text']);

					//Acronyms, AutoLinks - BEGIN
					if ($posts[$i]['enable_autolinks_acronyms'])
					{
						$posts[$i]['post_text'] = $bbcode->acronym_pass($posts[$i]['post_text']);
						$posts[$i]['post_text'] = $bbcode->autolink_text($posts[$i]['post_text'], '999999');
					}
					//Acronyms, AutoLinks - END
				}
				$posts[$i]['topic_title'] = censor_text($posts[$i]['topic_title']);

				$i++;
			}
			while ($row = $db->sql_fetchrow($result));
		}
		$db->sql_freeresult($result);

		// return the result
		return $posts;
	}

	/**
	* Gets poll data for a topic
	*/
	function get_poll_data($topic_id)
	{
		global $db, $cache, $config, $user;

		$sql = "SELECT o.*
			FROM " . POLL_OPTIONS_TABLE . " o
			WHERE o.topic_id = " . (int) $topic_id . "
			ORDER BY o.poll_option_id";
		$result = $db->sql_query($sql);

		$poll_info = array();
		while ($row = $db->sql_fetchrow($result))
		{
			$poll_info[] = $row;
		}
		$db->sql_freeresult($result);

		$cur_voted_id = array();
		if (!empty($user->data) && $user->data['session_logged_in'] && ($user->data['bot_id'] === false))
		{
			$sql = "SELECT v.poll_option_id
				FROM " . POLL_VOTES_TABLE . " v
				WHERE v.topic_id = " . (int) $topic_id . "
					AND v.vote_user_id = " . (int) $user->data['user_id'];
			$result = $db->sql_query($sql);

			while ($row = $db->sql_fetchrow($result))
			{
				$cur_voted_id[] = $row['poll_option_id'];
			}
			$db->sql_freeresult($result);
		}
		else
		{
			// Cookie based guest tracking... I don't like this but hum ho... it's oft requested. This relies on "nice" users who don't feel the need to delete cookies to mess with results
			if (isset($_COOKIE[$config['cookie_name'] . '_poll_' . $topic_id]))
			{
				$cur_voted_id = explode(',', $_COOKIE[$config['cookie_name'] . '_poll_' . $topic_id]);
				$cur_voted_id = array_map('intval', $cur_voted_id);
			}
		}

		return array('poll_info' => $poll_info, 'cur_voted_id' => $cur_voted_id);
	}

	/**
	* Display a poll
	*/
	function poll_display($topic_data, $is_cms_block = false)
	{
		global $db, $cache, $config, $user, $lang, $template, $images, $bbcode;
		global $start, $kb_mode_append, $is_auth, $lofi;

		if (!class_exists('bbcode') || empty($bbcode))
		{
			@include_once(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);
		}

		$poll_data_result = $this->get_poll_data($topic_data['topic_id']);
		$poll_info = $poll_data_result['poll_info'];
		$cur_voted_id = $poll_data_result['cur_voted_id'];
		unset($poll_data_result);

		$poll_total = 0;
		foreach ($poll_info as $poll_option)
		{
			$poll_total += $poll_option['poll_option_total'];
		}

		// Mighty Gorgon: Shall we enable BBCode for polls?
		$poll_bbcode = true;
		$bbcode->allow_bbcode = ($config['allow_bbcode'] && $user->data['user_allowbbcode']) ? true : false;
		$bbcode->allow_html = (($config['allow_html'] && $user->data['user_allowhtml']) || $config['allow_html_only_for_admins']) ? true : false;
		$bbcode->allow_smilies = ($config['allow_smilies'] && !$lofi) ? true : false;
		for ($i = 0, $size = sizeof($poll_info); $i < $size; $i++)
		{
			$poll_info[$i]['poll_option_text'] = censor_text($poll_info[$i]['poll_option_text']);

			if (!empty($poll_bbcode))
			{
				$poll_info[$i]['poll_option_text'] = $bbcode->parse($poll_info[$i]['poll_option_text']);
			}
		}

		$topic_data['poll_title'] = censor_text($topic_data['poll_title']);

		if (!empty($poll_bbcode))
		{
			$topic_data['poll_title'] = $bbcode->parse($topic_data['poll_title']);
		}
		unset($poll_bbcode);

		$user_voted = !empty($cur_voted_id) ? true : false;
		$poll_expired = (!empty($topic_data['poll_length'])) ? ((((int) $topic_data['poll_start'] + (int) $topic_data['poll_length']) < time()) ? true : false) : false;
		$s_display_results = request_var('vote', '');
		$s_display_results = ($user_voted || ($s_display_results == 'viewresult')) ? true : false;
		$s_auth_vote = $is_auth['auth_vote'] ? true : false;
		$s_can_vote = (($user->data['user_level'] == ADMIN) || ((!$user_voted || !empty($topic_data['poll_vote_change'])) && !$poll_expired && $s_auth_vote && ($topic_data['topic_status'] != TOPIC_LOCKED))) ? true : false;

		if (!empty($is_cms_block))
		{
			$s_can_vote = false;
			$s_display_results = true;
		}

		$template->set_filenames(array('pollbox' => 'viewtopic_poll_result.tpl'));

		$vote_graphic = 0;
		$vote_graphic_max = sizeof($images['voting_graphic']);

		foreach ($poll_info as $poll_option)
		{
			$option_pct = ($poll_total > 0) ? $poll_option['poll_option_total'] / $poll_total : 0;
			$option_pct = round($option_pct, 2);
			$option_pct_txt = sprintf("%.1d%%", round($option_pct * 100));

			$option_color = ($option_pct <= 0.33) ? 'red' : ((($option_pct > 0.33) && ($option_pct <= 0.66)) ? 'blue' : 'green');
			$option_graphic_length = round($option_pct * $config['vote_graphic_length']);
			$option_graphic = ($option_graphic < $option_graphic_max - 1) ? $option_graphic + 1 : 0;
			$template->assign_block_vars('poll_option', array(
				'POLL_OPTION_ID' => $poll_option['poll_option_id'],
				'POLL_OPTION_CAPTION' => $poll_option['poll_option_text'],
				'POLL_OPTION_RESULT' => $poll_option['poll_option_total'],
				'POLL_OPTION_PERCENT' => $option_pct_txt,
				'POLL_OPTION_PCT' => round($option_pct * 100),
				//'POLL_OPTION_IMG' => $user->img('poll_center', $option_pct_txt, round($option_pct * 250)),
				'POLL_OPTION_VOTED' => (in_array($poll_option['poll_option_id'], $cur_voted_id)) ? true : false,

				'POLL_OPTION_COLOR' => $option_color,
				'POLL_OPTION_IMG' => $images['voting_graphic'][$option_graphic],
				'POLL_OPTION_IMG_WIDTH' => $option_graphic_length,
				'POLL_GRAPHIC' => $images['voting_graphic_' . $option_color],
				'POLL_GRAPHIC_BODY' => $images['voting_graphic_' . $option_color . '_body'],
				'POLL_GRAPHIC_LEFT' => $images['voting_graphic_' . $option_color . '_left'],
				'POLL_GRAPHIC_RIGHT' => $images['voting_graphic_' . $option_color . '_right'],
				)
			);
		}

		$poll_end = $topic_data['poll_start'] + $topic_data['poll_length'];
		$s_hidden_fields = '<input type="hidden" name="topic_id" value="' . $topic_data['topic_id'] . '" />';
		$s_hidden_fields .= '<input type="hidden" name="mode" value="vote" />';
		$s_hidden_fields .= '<input type="hidden" name="sid" value="' . $user->data['session_id'] . '" />';

		$forum_id_append = POST_FORUM_URL . '=' . $topic_data['forum_id'];
		$topic_id_append = POST_TOPIC_URL . '=' . $topic_data['topic_id'];
		$template->assign_vars(array(
			'TOTAL_VOTES' => $poll_total,
			'POLL_QUESTION' => $topic_data['poll_title'],
			'S_HAS_POLL' => true,
			'S_CAN_VOTE' => $s_can_vote,
			'S_DISPLAY_RESULTS' => $s_display_results,
			'S_IS_MULTI_CHOICE' => ($topic_data['poll_max_options'] > 1) ? true : false,
			'S_HIDDEN_FIELDS' => $s_hidden_fields,
			'S_POLL_ACTION' => append_sid(CMS_PAGE_POSTING . '?mode=vote&amp;' . $forum_id_append . '&amp;' . $topic_id_append . '&amp;start=' . $start),
			'S_CMS_BLOCK' => !empty($is_cms_block) ? true : false,

			'U_VIEW_RESULTS' => append_sid(CMS_PAGE_VIEWTOPIC . '?' . $forum_id_append . '&amp;' . $topic_id_append . $kb_mode_append . '&amp;vote=viewresult'),

			'L_MAX_VOTES' => ($topic_data['poll_max_options'] == 1) ? $lang['MAX_OPTION_SELECT'] : sprintf($lang['MAX_OPTIONS_SELECT'], $topic_data['poll_max_options']),
			'L_POLL_LENGTH' => (!empty($topic_data['poll_length'])) ? sprintf($lang[($poll_end > time()) ? 'POLL_RUN_TILL' : 'POLL_ENDED_AT'], create_date($config['default_dateformat'], $poll_end, $config['board_timezone'])) : '',
			'L_TOTAL_VOTES' => $lang['Total_votes'],
			'L_SUBMIT_VOTE' => $lang['Submit_vote'],
			'L_VIEW_RESULTS' => $lang['View_results'],
			)
		);
		unset($poll_end, $poll_info, $voted_id);

		$template->assign_var_from_handle('POLL_DISPLAY', 'pollbox');
	}

	/**
	* Like a post
	*/
	function post_like_add($post_data)
	{
		global $db, $cache, $config, $user, $lang;

		if (empty($post_data) || empty($post_data['post_id']) || ($post_data['user_id'] == ANONYMOUS))
		{
			return false;
		}
		/*
		$post_data = array(
			'topic_id' => $topic_id,
			'post_id' => $post_id,
			'user_id' => $user_id,
			'like_time' => time()
		);
		*/

		// Check if the user already liked this post!
		$sql = "SELECT COUNT(pl.user_id) AS total_likes, p.poster_id
			FROM " . POSTS_LIKES_TABLE . " pl, " . POSTS_TABLE . " p
			WHERE pl.post_id = " . $post_data['post_id'] . "
				AND p.post_id = pl.post_id
				AND pl.user_id = " . $post_data['user_id'];
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		$total_likes = $row['total_likes'];
		$poster_id = $row['poster_id'];

		if (empty($total_likes) && ($poster_id != $post_data['user_id']))
		{
			$sql_ary = array(
				'topic_id' => $post_data['topic_id'],
				'post_id' => $post_data['post_id'],
				'user_id' => $post_data['user_id'],
				'like_time' => $post_data['like_time']
			);

			$sql_insert = $db->sql_build_insert_update($sql_ary, true);

			$sql = "UPDATE " . TOPICS_TABLE . " SET topic_likes = topic_likes + 1 WHERE topic_id = " . $post_data['topic_id'];
			$db->sql_query($sql);

			$sql = "UPDATE " . POSTS_TABLE . " SET post_likes = post_likes + 1 WHERE post_id = " . $post_data['post_id'];
			$db->sql_query($sql);

			$sql = "INSERT INTO " . POSTS_LIKES_TABLE . " " . $sql_insert;
			$db->sql_query($sql);
		}

		return true;
	}

	/**
	* Remove like from a post
	*/
	function post_like_remove($post_data)
	{
		global $db, $cache, $config, $user, $lang;

		if (empty($post_data) || empty($post_data['topic_id']) || empty($post_data['post_id']) || empty($post_data['user_id']))
		{
			return false;
		}

		$sql = "UPDATE " . TOPICS_TABLE . " SET topic_likes = topic_likes - 1 WHERE topic_id = " . $post_data['topic_id'];
		$db->sql_query($sql);

		$sql = "UPDATE " . POSTS_TABLE . " SET post_likes = post_likes - 1 WHERE post_id = " . $post_data['post_id'];
		$db->sql_query($sql);

		$sql = "DELETE FROM " . POSTS_LIKES_TABLE . " WHERE post_id = " . $post_data['post_id'] . " AND user_id = " . $post_data['user_id'];
		$db->sql_query($sql);

		return true;
	}

	/**
	* Remove all like for a user
	*/
	function post_like_user_remove($user_id)
	{
		global $db, $cache, $config, $user, $lang;

		if (empty($user_id))
		{
			return false;
		}

		$sql = "DELETE FROM " . POSTS_LIKES_TABLE . " WHERE user_id = " . $user_id;
		$db->sql_query($sql);

		return true;
	}

	/**
	* Remove like from a topic
	*/
	function topic_posts_likes_get($post_data, $posts_list = false)
	{
		global $db, $cache, $config, $user, $lang;

		if (empty($post_data) || empty($post_data['topic_id']))
		{
			return false;
		}

		if (!empty($posts_list) && is_array($posts_list))
		{
			$sql_where = " post_id IN(" . implode(',', $posts_list) . ") ";
		}
		else
		{
			$sql_where = " topic_id = " . $post_data['topic_id'];
		}

		$topic_posts_likes = array();
		$sql = "SELECT *
			FROM " . POSTS_LIKES_TABLE . "
			WHERE " . $sql_where;
		$result = $db->sql_query($sql);
		while ($row = $db->sql_fetchrow($result))
		{
			$topic_posts_likes['posts'][$row['post_id']][] = $row['user_id'];
			$topic_posts_likes['users'][$row['user_id']][] = $row['post_id'];
		}
		$db->sql_freeresult($result);

		return $topic_posts_likes;
	}

	/**
	* Remove like from a topic
	*/
	function topic_posts_likes_remove($post_data)
	{
		global $db, $cache, $config, $user, $lang;

		if (empty($post_data) || empty($post_data['topic_id']))
		{
			return false;
		}

		$sql = "UPDATE " . TOPICS_TABLE . " SET topic_likes = 0 WHERE topic_id = " . $post_data['topic_id'];
		$db->sql_query($sql);

		$sql = "UPDATE " . POSTS_TABLE . " SET post_likes = 0 WHERE topic_id = " . $post_data['topic_id'];
		$db->sql_query($sql);

		$sql = "DELETE FROM " . POSTS_LIKES_TABLE . " WHERE topic_id = " . $post_data['topic_id'];
		$db->sql_query($sql);

		return true;
	}

	/**
	* Posts Likes ReSync
	*/
	function topics_posts_likes_resync()
	{
		global $db, $cache, $config, $user, $lang;

		$sql = "UPDATE " . POSTS_TABLE . " p SET p.post_likes = (SELECT COUNT(pl.post_id) FROM " . POSTS_LIKES_TABLE . " pl WHERE pl.post_id = p.post_id)";
		$db->sql_query($sql);

		$sql = "UPDATE " . POSTS_TABLE . " p, " . POSTS_LIKES_TABLE . " pl SET pl.topic_id = p.topic_id WHERE pl.post_id = p.post_id";
		$db->sql_query($sql);

		$sql = "UPDATE " . TOPICS_TABLE . " t SET t.topic_likes = (SELECT COUNT(pl.topic_id) FROM " . POSTS_LIKES_TABLE . " pl WHERE pl.topic_id = t.topic_id)";
		$db->sql_query($sql);

		return true;
	}

}

?>