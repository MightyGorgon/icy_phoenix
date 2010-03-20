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
* Ptirhiik (admin@rpgnet-fr.com)
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

// activate this line if you want to alternate the color of each row
// define('TOPIC_ALTERNATE_ROW_CLASS', true);

// different view for the topics the user replied too
define('USER_REPLIED_ICON', true); // activate this line if you are using different folder icons for the topic the user replied too
// define('USER_REPLIED_CLASS', 'quote'); // activate this line and set the class you prefer for the the topic the user replied too

// various includes
@include_once(IP_ROOT_PATH . './includes/functions_post.' . PHP_EXT);
@include_once(IP_ROOT_PATH . './includes/bbcode.' . PHP_EXT);

//--------------------------------------------------
// topic_list() : display a list of topic
// ------------
//	$box :				name of the tpl var for the box
//	$tpl :				name of the template file used (blank: topics_list_box.tpl) : do not set .tpl at the end
//	$topic_rowset :		list of the topics : note that topic_id is filled with the item type + id (ie t256)
//	$list_title :		title of the box (blank: $lang['Topics'])
//	$split_type :		if false, the topics won't be split whatever is the split topic per type setup
//	$display_nav_tree :	if true, display the forum name where stands the topic
//	$footer :			what to display at the bottom of the last box (sort by, order, etc.)
//	$inbox :			if false, the topics won't be splitted in different boxes per type
//	$select_field :		name of the select field
//	$select_type :		0: no select field, 1: checkbox field (multiple selection), 2: radio field (unique selection)
//	$select_formname :	name of the form where the select field will appear
//	$select_values :	selected values (array)
// ---------------------------------
// standard sql request in order to fill the topic_rowset array :
// ---------------------------------
// $sql = "SELECT t.*, u.username, u.user_id, u2.username as user2, u2.user_id as id2, p.post_username, p2.post_username AS post_username2, p2.post_time
//	FROM " . TOPICS_TABLE . " t, " . USERS_TABLE . " u, " . POSTS_TABLE . " p, " . POSTS_TABLE . " p2, " . USERS_TABLE . " u2
//	WHERE t.topic_poster = u.user_id
//		AND p.post_id = t.topic_first_post_id
//		AND p2.post_id = t.topic_last_post_id
//		AND u2.user_id = p2.poster_id
//	ORDER BY t.topic_type DESC, t.topic_last_post_id DESC
//	LIMIT $start, ".$config['topics_per_page'];
// ---------------------------------
// NB:
// ---------------------------------
//  topic_id should have in first position the main data row type, meaning for topics :
//    $topic_rowset[]['topic_id'] = POST_TOPIC_URL . $row['topic_id'];
//--------------------------------------------------
function topic_list($box, $tpl='', $topic_rowset, $list_title='', $split_type = false, $display_nav_tree = true, $footer='', $inbox = true, $select_field='', $select_type = 0, $select_formname = '', $select_values = array())
{
	global $template, $db, $cache, $config, $userdata, $lang, $images, $theme;
	global $tree, $bbcode, $userdata;
	//<!-- BEGIN Unread Post Information to Database Mod -->
	global $unread;
	//<!-- END Unread Post Information to Database Mod -->
	static $box_id;

	// save template state
	$sav_tpl = $template->_tpldata;

	// init
	if (empty($tpl))
	{
		$tpl = 'topics_list_box';
	}
	if (empty($list_title))
	{
		$list_title = $lang['Topics'];
	}
	if (!empty($select_values) && !is_array($select_values))
	{
		$s_values = $select_values;
		$select_values = array();
		$select_values[] = $s_values;
	}

	// selections
	$select_multi = false;
	$select_unique = false;
	if (!empty($select_field) && ($select_type > 0) && !empty($select_formname))
	{
		switch ($select_type)
		{
			case 1:
				$select_multi = true;
				break;
			case 2:
				$select_unique = true;
				break;
		}
	}

	// get split params
	$switch_split_global_announce = (isset($config['split_global_announce']) && isset($lang['Post_Global_Announcement'])) ? intval($config['split_global_announce']) : false;
	$switch_split_announce = isset($config['split_announce']) ? intval($config['split_announce']) : false;
	$switch_split_sticky = isset($config['split_sticky']) ? intval($config['split_sticky']) : false;
	$switch_split_news = isset($config['split_news']) ? intval($config['split_news']) : false;

	// set in separate table
	$split_box = $inbox && (isset($config['split_topic_split']) ? intval($config['split_topic_split']) : false);

	// take care of the context
	if (!$split_type)
	{
		$split_box = false;
		$switch_split_global_announce = false;
		$switch_split_announce = false;
		$switch_split_sticky = false;
		$switch_split_news = false;
	}

	if (!$switch_split_global_announce && !$switch_split_announce && !$switch_split_sticky && !$switch_split_news)
	{
		$split_type = false;
		$split_box = false;
	}

	// read the user cookie
	$tracking_topics = (isset($_COOKIE[$config['cookie_name'] . '_t'])) ? unserialize($_COOKIE[$config['cookie_name'] . '_t']) : array();
	$tracking_forums = (isset($_COOKIE[$config['cookie_name'] . '_f'])) ? unserialize($_COOKIE[$config['cookie_name'] . '_f']) : array();
	$tracking_all = (isset($_COOKIE[$config['cookie_name'] . '_f_all'])) ? intval($_COOKIE[$config['cookie_name'] . '_f_all']) : NULL;

	// categories hierarchy v 2 compliancy
	$cat_hierarchy = function_exists('get_auth_keys');
	if (!$cat_hierarchy)
	{
		// standard read
		$is_auth = array();
		$is_auth = auth(AUTH_ALL, AUTH_LIST_ALL, $userdata);
	}

	// topic icon present
	$icon_installed = function_exists('get_icon_title');

	// get a default title
	if (empty($list_title))
	{
		$list_title = $lang['forum'];
	}

	// choose template
	$template->set_filenames(array(
		$tpl => $tpl . '.tpl')
	);

	// check if user replied to the topic
	$user_topics = array();
	if ($userdata['user_id'] != ANONYMOUS)
	{
		// get all the topic ids to display
		$topic_ids = array();
		for ($i = 0; $i < sizeof($topic_rowset); $i++)
		{
			$topic_item_type = substr($topic_rowset[$i]['topic_id'], 0, 1);
			$topic_id = intval(substr($topic_rowset[$i]['topic_id'], 1));
			if ($topic_item_type == POST_TOPIC_URL)
			{
				$topic_ids[] = $topic_id;
			}
		}
		// check if the user replied to
		if (!empty($topic_ids))
		{
			// check the posts
			$s_topic_ids = implode(', ', $topic_ids);
			$sql = "SELECT DISTINCT topic_id FROM " . POSTS_TABLE . "
					WHERE topic_id IN ($s_topic_ids)
						AND poster_id = " . $userdata['user_id'];
			$result = $db->sql_query($sql);

			while ($row = $db->sql_fetchrow($result))
			{
				$user_topics[POST_TOPIC_URL . $row['topic_id']] = true;
			}
		}
	}

	// initiate
	$template->assign_block_vars($tpl, array(
		'FORMNAME' => $select_formname,
		'FIELDNAME' => $select_field,
		)
	);

	// spanning of the first column (list name)
	$span_left = 1;
	if (sizeof($topic_rowset) > 0)
	{
		// add folder image
		$span_left++;
	}
	if ($icon_installed)
	{
		// add topic icon
		$span_left++;
	}
	if ($select_unique)
	{
		// selection in front is asked
		$span_left++;
	}
	// spanning of the whole line (bottom row and/or empty list)
	$span_all = $span_left + 4;
	if ($select_multi && (sizeof($topic_rowset) >0))
	{
		$span_all++;
	}

	// display topics
	$color = false;
	$prec_topic_type = '';
	$header_sent = false;
	if (!isset($box_id)) $box_id = -1;
	for ($i = 0; $i < sizeof($topic_rowset); $i++)
	{
		$topic_item_type = substr($topic_rowset[$i]['topic_id'], 0, 1);
		$topic_id = intval(substr($topic_rowset[$i]['topic_id'], 1));
		$topic_title = censor_text($topic_rowset[$i]['topic_title']);
		$replies = $topic_rowset[$i]['topic_replies'];
		$topic_type = $topic_rowset[$i]['topic_type'];
		$user_replied = (!empty($user_topics) && isset($user_topics[$topic_rowset[$i]['topic_id']]));
		$force_type_display = false;
		$forum_id = $topic_rowset[$i]['forum_id'];

		if (defined('POST_BIRTHDAY') && ($topic_type == POST_BIRTHDAY))
		{
			$topic_type = $lang['Birthday'] . ': ';
		}
		//elseif($topic_rowset[$i]['news_id'] > 0)
		elseif($topic_type == POST_NEWS)
		{
			$topic_type = $lang['News'] . ': ';
		}
		elseif($topic_type == POST_GLOBAL_ANNOUNCE)
		{
			$topic_type = $lang['Topic_Global_Announcement'] . ' ';
		}
		elseif($topic_type == POST_ANNOUNCE)
		{
			$topic_type = $lang['Topic_Announcement'] . ' ';
		}
		elseif($topic_type == POST_STICKY)
		{
			$topic_type = $lang['Topic_Sticky'] . ' ';
		}
		else
		{
			$topic_type = '';
		}
		if($topic_rowset[$i]['topic_vote'])
		{
			$topic_type .= $lang['Topic_Poll'] . ' ';
			$force_type_display = true;
		}
		if (defined('POST_BIRTHDAY') && ($topic_rowset[$i]['topic_type'] == POST_BIRTHDAY))
		{
			$folder_image =  $images['folder_birthday'];
			$folder_alt = $lang['HAPPY_BIRTHDAY'];
			$newest_post_img = '';
		}
		elseif($topic_rowset[$i]['topic_status'] == TOPIC_MOVED)
		{
			$topic_type = $lang['Topic_Moved'] . ' ';
			$topic_id = $topic_rowset[$i]['topic_moved_id'];
			$folder_image =  $images['topic_nor_read'];
			$folder_alt = $lang['Topics_Moved'];
			$newest_post_img = '';
			$force_type_display = true;
		}
		else
		{
			/*
			define('USER_REPLIED_ICON', true);
			$user_replied = true;
			*/

			if(defined('POST_BIRTHDAY') && ($topic_rowset[$i]['topic_type'] == POST_BIRTHDAY))
			{
				$folder = $images['folder_birthday'];
				$folder_new = $images['folder_birthday'];
			}
			//elseif($topic_rowset[$i]['news_id'] > 0)
			elseif($topic_rowset[$i]['topic_type'] == POST_NEWS)
			{
				$folder = ($user_replied && defined('USER_REPLIED_ICON')) ? $images['topic_nor_read_own'] : $images['topic_nor_read'];
				$folder_new = ($user_replied && defined('USER_REPLIED_ICON')) ? $images['topic_nor_unread_own'] : $images['topic_nor_unread'];
			}
			elseif($topic_rowset[$i]['topic_type'] == POST_GLOBAL_ANNOUNCE)
			{
				$folder = ($user_replied && defined('USER_REPLIED_ICON')) ? $images['topic_glo_read_own'] : $images['topic_glo_read'];
				$folder_new = ($user_replied && defined('USER_REPLIED_ICON')) ? $images['topic_glo_unread_own'] : $images['topic_glo_unread'];
			}
			elseif($topic_rowset[$i]['topic_type'] == POST_ANNOUNCE)
			{
				$folder = ($user_replied && defined('USER_REPLIED_ICON')) ? $images['topic_ann_read_own'] : $images['topic_ann_read'];
				$folder_new = ($user_replied && defined('USER_REPLIED_ICON')) ? $images['topic_ann_unread_own'] : $images['topic_ann_unread'];
			}
			elseif($topic_rowset[$i]['topic_type'] == POST_STICKY)
			{
				$folder = ($user_replied && defined('USER_REPLIED_ICON')) ? $images['topic_imp_read_own'] : $images['topic_imp_read'];
				$folder_new = ($user_replied && defined('USER_REPLIED_ICON')) ? $images['topic_imp_unread_own'] : $images['topic_imp_unread'];
			}
			elseif($topic_rowset[$i]['topic_status'] == TOPIC_LOCKED)
			{
				$folder = ($user_replied && defined('USER_REPLIED_ICON')) ? $images['topic_nor_locked_read_own'] : $images['topic_nor_locked_read'];
				$folder_new = ($user_replied && defined('USER_REPLIED_ICON')) ? $images['topic_nor_locked_unread_own'] : $images['topic_nor_locked_unread'];
			}
			else
			{
				if($replies >= $config['hot_threshold'])
				{
					$folder = ($user_replied && defined('USER_REPLIED_ICON')) ? $images['topic_hot_read_own'] : $images['topic_hot_read'];
					$folder_new = ($user_replied && defined('USER_REPLIED_ICON')) ? $images['topic_hot_unread_own'] : $images['topic_hot_unread'];
				}
				else
				{
					$folder = ($user_replied && defined('USER_REPLIED_ICON')) ? $images['topic_nor_read_own'] : $images['topic_nor_read'];
					$folder_new = ($user_replied && defined('USER_REPLIED_ICON')) ? $images['topic_nor_unread_own'] : $images['topic_nor_unread'];
				}
			}
			$newest_post_img = '';
			if ($userdata['session_logged_in'] && ($topic_item_type == POST_TOPIC_URL))
			{
				//<!-- BEGIN Unread Post Information to Database Mod -->
				if(!$userdata['upi2db_access'])
				{
				//<!-- END Unread Post Information to Database Mod -->
					if($topic_rowset[$i]['post_time'] > $userdata['user_lastvisit'])
					{
						if(!empty($tracking_topics) || !empty($tracking_forums) || !empty($tracking_all))
						{
							$unread_topics = true;
							if(!empty($tracking_topics[$topic_id]))
							{
								if($tracking_topics[$topic_id] >= $topic_rowset[$i]['post_time'])
								{
									$unread_topics = false;
								}
							}
							if(!empty($tracking_forums[$forum_id]))
							{
								if($tracking_forums[$forum_id] >= $topic_rowset[$i]['post_time'])
								{
									$unread_topics = false;
								}
							}
							if(!empty($tracking_all))
							{
								if($tracking_all >= $topic_rowset[$i]['post_time'])
								{
									$unread_topics = false;
								}
							}
							if ($unread_topics)
							{
								$folder_image = $folder_new;
								$folder_alt = $lang['New_posts'];
								$newest_post_img = '<a href="' . append_sid(CMS_PAGE_VIEWTOPIC . "?" . POST_TOPIC_URL . "=$topic_id&amp;view=newest") . '"><img src="' . $images['icon_newest_reply'] . '" alt="' . $lang['View_newest_post'] . '" title="' . $lang['View_newest_post'] . '" /></a> ';
							}
							else
							{
								$folder_image = $folder;
								$folder_alt = ($topic_rowset[$i]['topic_status'] == TOPIC_LOCKED) ? $lang['Topic_locked'] : $lang['No_new_posts'];
								$newest_post_img = '';
							}
						}
						else
						{
							$folder_image = $folder_new;
							$folder_alt = ($topic_rowset[$i]['topic_status'] == TOPIC_LOCKED) ? $lang['Topic_locked'] : $lang['New_posts'];
							$newest_post_img = '<a href="' . append_sid(CMS_PAGE_VIEWTOPIC . "?" . POST_TOPIC_URL . "=$topic_id&amp;view=newest") . '"><img src="' . $images['icon_newest_reply'] . '" alt="' . $lang['View_newest_post'] . '" title="' . $lang['View_newest_post'] . '" /></a> ';
						}
					}
					else
					{
						$folder_image = $folder;
						$folder_alt = ($topic_rowset[$i]['topic_status'] == TOPIC_LOCKED) ? $lang['Topic_locked'] : $lang['No_new_posts'];
						$newest_post_img = '';
					}
				//<!-- BEGIN Unread Post Information to Database Mod -->
				}
				else
				{
					viewforum_calc_unread($unread, $topic_id, $topic_rowset, $i, $folder_new, $folder, $folder_alt, $folder_image, $newest_post_img, $upi2db_status);
				}
				//<!-- END Unread Post Information to Database Mod -->
			}
			else
			{
				$folder_image = $folder;
				$folder_alt = ($topic_rowset[$i]['topic_status'] == TOPIC_LOCKED) ? $lang['Topic_locked'] : $lang['No_new_posts'];
				$newest_post_img = '';
			}
		}

		// generate list of page for the topic
		$topic_pagination = generate_topic_pagination($forum_id, $topic_id, $replies);

		$topic_author = '';
		$first_post_time = '';
		$last_post_time = '';
		$last_post_url = '';
		$views = '';
		switch ($topic_item_type)
		{
			case POST_USERS_URL:
				$view_topic_url = append_sid(CMS_PAGE_VIEWTOPIC . '?' . POST_TOPIC_URL . '=' . $topic_id);
				break;
			default:
				$view_topic_url = append_sid(CMS_PAGE_VIEWTOPIC . '?' . POST_TOPIC_URL . '=' . $topic_id);
				$topic_author = ($topic_rowset[$i]['user_id'] != ANONYMOUS) ? '<a href="' . append_sid(CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $topic_rowset[$i]['user_id']) . '">' : '';
				$topic_author .= ($topic_rowset[$i]['user_id'] != ANONYMOUS) ? $topic_rowset[$i]['username'] : (($topic_rowset[$i]['post_username'] != '') ? $topic_rowset[$i]['post_username'] : $lang['Guest']);
				$topic_author .= ($topic_rowset[$i]['user_id'] != ANONYMOUS) ? '</a>' : '';
				$first_post_time = create_date_ip($config['default_dateformat'], $topic_rowset[$i]['topic_time'], $config['board_timezone']);
				$last_post_time = create_date_ip($config['default_dateformat'], $topic_rowset[$i]['post_time'], $config['board_timezone']);
				$last_post_author = ($topic_rowset[$i]['id2'] == ANONYMOUS) ? (($topic_rowset[$i]['post_username2'] != '') ? $topic_rowset[$i]['post_username2'] . ' ' : $lang['Guest'] . ' ') : '<a href="' . append_sid(CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '='  . $topic_rowset[$i]['id2']) . '">' . $topic_rowset[$i]['user2'] . '</a>';
				$last_post_url = '<a href="' . append_sid(CMS_PAGE_VIEWTOPIC . '?'  . POST_POST_URL . '=' . $topic_rowset[$i]['topic_last_post_id']) . '#p' . $topic_rowset[$i]['topic_last_post_id'] . '"><img src="' . $images['icon_latest_reply'] . '" alt="' . $lang['View_latest_post'] . '" title="' . $lang['View_latest_post'] . '" /></a>';
				$views = $topic_rowset[$i]['topic_views'];
				// BEGIN cmx_mod
				$news_label = ($topic_rowset[$i]['news_id'] > 0) ? $lang['News'] . ':' : '';
				// END cmx_mod
				break;
		}

		// categories hierarchy v 2 compliancy
		$nav_tree = '';
		if ($display_nav_tree && !empty($topic_rowset[$i]['forum_id']))
		{
			if ($cat_hierarchy)
			{
				if ($tree['auth'][POST_FORUM_URL . $topic_rowset[$i]['forum_id']]['tree.auth_view'])
				{
					$nav_tree = make_cat_nav_tree(POST_FORUM_URL . $topic_rowset[$i]['forum_id'], '', '', 'gensmall');
				}
			}
			else
			{
				if ($is_auth[ $topic_rowset[$i]['forum_id'] ]['auth_view'])
				{
					$nav_tree = '<a href="' . append_sid(CMS_PAGE_VIEWFORUM . '?' . POST_FORUM_URL . '=' . $topic_rowset[$i]['forum_id']) . '" class="gensmall">' . $topic_rowset[$i]['forum_name'] . '</a>';
				}
			}
		}
		if (!empty($nav_tree))
		{
			$nav_tree = '[ ' . $nav_tree . ' ]';
		}

		// get the type for rupture
		$topic_real_type = $topic_rowset[$i]['topic_type'];

		// if no split between global and standard announcement, group them with standard announcement
		if (!$switch_split_global_announce && ($topic_real_type == POST_GLOBAL_ANNOUNCE)) $topic_real_type = POST_ANNOUNCE;

		// if no split between announce and sticky, group them with sticky
		if (!$switch_split_announce && ($topic_real_type == POST_ANNOUNCE)) $topic_real_type = POST_NEWS;

		// if no split between news and global announcement, group them with normal
		if (!$switch_split_news && ($topic_real_type == POST_NEWS)) $topic_real_type = POST_STICKY;

		// if no split between sticky and normal, group them with normal
		if (!$switch_split_sticky && ($topic_real_type == POST_STICKY)) $topic_real_type = POST_NORMAL;

		// check if rupture
		$rupt = false;

		// split
		if (($i == 0) || $split_type)
		{
			if ($i == 0)
			{
				$rupt = true;
			}

			// check the rupt
			if ($prec_topic_type != $topic_real_type)
			{
				$rupt = true;
			}
		}
		$prec_topic_type = $topic_real_type;

		// header
		if ($rupt)
		{
			// close the prec box
			if ($split_box && ($i != 0))
			{
				// footer
				$template->assign_block_vars($tpl . '.row', array(
					'COLSPAN' => $span_all,
					)
				);

				// table closure
				$template->assign_block_vars($tpl . '.row.footer_table', array());

				// spacing
				$template->assign_block_vars($tpl . '.row', array());
				$template->assign_block_vars($tpl . '.row.spacer', array());

				// unset header
				$header_sent = false;
			}

			// get box title
			$main_title = $list_title;
			$sub_title = $list_title;
			switch ($topic_real_type)
			{
				case POST_BIRTHDAY:
					$sub_title = $lang['Birthday'];
					break;
				case POST_NEWS:
					$sub_title = $lang['News'];
					break;
				case POST_GLOBAL_ANNOUNCE:
					$sub_title = $lang['Post_Global_Announcement'];
					break;
				case POST_ANNOUNCE:
					$sub_title = $lang['Post_Announcement'];
					break;
				case POST_STICKY:
					$sub_title = $lang['Post_Sticky'];
					break;
				case POST_CALENDAR:
					$sub_title = $lang['Calendar_event'];
					break;
				case POST_NORMAL:
					$sub_title = $lang['Topics'];
					break;
			}
			$template->assign_block_vars($tpl . '.row', array(
				'L_TITLE' => (!$split_box) ? $main_title : $sub_title,
				'L_REPLIES' => $lang['Replies'],
				'L_AUTHOR' => $lang['Author'],
				'L_VIEWS' => $lang['Views'],
				'L_LASTPOST' => $lang['Last_Post'],
				'COLSPAN' => $span_all,
				)
			);

			// open a new box
			if ($split_box || ($i == 0))
			{
				$box_id++;
				$template->assign_block_vars($tpl . '.row.header_table', array(
					'COLSPAN' => $span_left,
					'BOX_ID' => $box_id,
					)
				);

				// selection fields
				if ($select_multi)
				{
					$template->assign_block_vars($tpl . '.row.header_table.multi_selection', array());
				}

				// set header
				$header_sent = true;
			}

			// not in box, send a row title
			if ($split_type && !$split_box)
			{
				$template->assign_block_vars($tpl . '.row', array(
					'L_TITLE' => $sub_title,
					'COLSPAN' => $span_all,
					)
				);
				$template->assign_block_vars($tpl . '.row.header_row', array());
			}
		}

		// erase the type before the title if split
		if ($split_type && ($topic_real_type == $topic_rowset[$i]['topic_type']) && !$force_type_display)
		{
			$topic_type = '';
		}

		// get the announces dates
		$topic_announces_dates = '';

		// get the calendar dates
		$topic_calendar_dates = '';
		if (function_exists(get_calendar_title))
		{
			$topic_calendar_dates = get_calendar_title($topic_rowset[$i]['topic_calendar_time'], $topic_rowset[$i]['topic_calendar_duration']);
		}

		// get the topic icons
		$icon = '';
		if ($icon_installed)
		{
			$type = $topic_rowset[$i]['topic_type'];
			if ($type == POST_NORMAL)
			{
				if (defined('POST_CALENDAR') && !empty($topic_rowset[$i]['topic_calendar_time']))
				{
					$type = POST_CALENDAR;
				}
				if (defined('POST_PICTURE') && !empty($topic_rowset[$i]['topic_pic_url']))
				{
					$type = POST_PICTURE;
				}
			}
			$icon = get_icon_title($topic_rowset[$i]['topic_icon'], 1, $type);
		}

		//<!-- BEGIN Unread Post Information to Database Mod -->
		if($userdata['upi2db_access'])
		{
			$mark_always_read = mark_always_read($topic_rowset[$i]['topic_type'], $topic_id, $forum_id, 'viewforum', 'icon', $unread, $start, $folder_image);
		}
		else
		{
			$mark_always_read = '<img src="' . $folder_image . '" alt="' . $folder_alt . '" title="' . $folder_alt . '" />';
		}
		//<!-- END Unread Post Information to Database Mod -->

		// send topic to template
		$selected = (!empty($select_values) && in_array($topic_rowset[$i]['topic_id'], $select_values));
		$color = !$color;
		$template->assign_block_vars($tpl . '.row', array(
			'ROW_CLASS' => ($color || !defined('TOPIC_ALTERNATE_ROW_CLASS')) ? $theme['td_class1'] : $theme['td_class2'],
			'ROW_FOLDER_CLASS' => ($user_replied && defined('USER_REPLIED_CLASS')) ? USER_REPLIED_CLASS : (($color || !defined('TOPIC_ALTERNATE_ROW_CLASS')) ? $theme['td_class1'] : $theme['td_class2']),
			'FORUM_ID' => $forum_id,
			'TOPIC_ID' => $topic_id,
			'TOPIC_FOLDER_IMG' => $folder_image,
			'TOPIC_AUTHOR' => $topic_author,
			'TOPIC_DESCRIPTION' => $topic_desc,
			'GOTO_PAGE' => $topic_pagination['base'],
			'GOTO_PAGE_FULL' => $topic_pagination['full'],
			'TOPIC_NAV_TREE' => !empty($nav_tree) ? (empty($goto_page) ? '<br />' : '') . $nav_tree : '',
			'REPLIES' => $replies,
			'NEWEST_POST_IMG' => $newest_post_img,
			'TOPIC_ATTACHMENT_IMG' => topic_attachment_image($topic_rowset[$i]['topic_attachment']),
			'ICON' => $icon,
			'TOPIC_TITLE' => $topic_title,
			'TOPIC_ANNOUNCES_DATES' => $topic_announces_dates,
			'TOPIC_CALENDAR_DATES' => $topic_calendar_dates,
			'TOPIC_TYPE' => $topic_type,
			'VIEWS' => $views,
			'FIRST_POST_TIME' => $first_post_time,
			'LAST_POST_TIME' => $last_post_time,
			'LAST_POST_AUTHOR' => $last_post_author,
			'LAST_POST_IMG' => $last_post_url,
			'L_NEWS' => $news_label,
			'L_TOPIC_FOLDER_ALT' => $folder_alt,
			'U_VIEW_TOPIC' => $view_topic_url,
			'BOX_ID' => $box_id,
			'FID' => $topic_rowset[$i]['topic_id'],
			//<!-- BEGIN Unread Post Information to Database Mod  -->
			'U_MARK_ALWAYS_READ' => $mark_always_read,
			//<!-- END Unread Post Information to Database Mod -->
			'L_SELECT' => ($selected && ($select_multi || $select_unique)) ? 'checked="checked"' : '',
			)
		);
		$template->assign_block_vars($tpl . '.row.topic', array());

		if (!empty($topic_rowset[$i]['topic_desc']) && $config['show_topic_description'])
		{
			$template->assign_block_vars($tpl . '.row.topic.switch_topic_desc', array());
		}
		// selection fields
		if ($select_multi)
		{
			$template->assign_block_vars($tpl . '.row.topic.multi_selection', array());
		}
		if ($select_unique)
		{
			$template->assign_block_vars($tpl . '.row.topic.single_selection', array());
		}

		// icons
		if ($icon_installed)
		{
			$template->assign_block_vars($tpl . '.row.topic.icon', array());
		}

		// nav tree asked
		if ($display_nav_tree && !empty($nav_tree))
		{
			$template->assign_block_vars($tpl . '.row.topic.nav_tree', array());
		}
	} // end for topic_rowset read

	// send an header if missing
	if (!$header_sent)
	{
		$template->assign_block_vars($tpl . '.row', array(
			'L_TITLE' => $list_title,
			'L_REPLIES' => $lang['Replies'],
			'L_AUTHOR' => $lang['Author'],
			'L_VIEWS' => $lang['Views'],
			'L_LASTPOST' => $lang['Last_Post'],
			'COLSPAN' => $span_all,
			)
		);

		// open a new box
		$template->assign_block_vars($tpl . '.row.header_table', array(
			'COLSPAN' => $span_left,
			)
		);
	}

	// no data
	if (sizeof($topic_rowset) == 0)
	{
		// send no topics notice
		$template->assign_block_vars($tpl . '.row', array(
			'L_NO_TOPICS' => $lang['No_search_match'],
			'COLSPAN' => $span_all,
			)
		);
		$template->assign_block_vars($tpl . '.row.no_topics', array());
	}

	// bottom line
	if (!empty($footer))
	{
		$template->assign_block_vars($tpl . '.row', array(
			'COLSPAN' => $span_all,
			'FOOTER' => $footer,
			)
		);
		$template->assign_block_vars($tpl . '.row.bottom', array());
	}

	// table closure
	$template->assign_block_vars($tpl . '.row', array(
		'COLSPAN' => $span_all,
		)
	);
	$template->assign_block_vars($tpl . '.row.footer_table', array());

	// spacing
	if (empty($footer))
	{
		// spacing
		$template->assign_block_vars($tpl . '.row', array());
		$template->assign_block_vars($tpl . '.row.spacer', array());
	}

	// transfert to a var
	$template->assign_var_from_handle('_box', $tpl);
	$res = $template->_tpldata['.'][0]['_box'];

	// restore template saved state
	$template->_tpldata = $sav_tpl;

	// assign value to the main template
	$template->assign_vars(array($box => $res));
}

?>