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

$template->set_filenames(array('similar_viewtopic' => 'similar_viewtopic.tpl'));

$template->assign_block_vars('similar', array(
	'L_SIMILAR' => $lang['SimilarTopics'],
	'L_TOPIC' => $lang['Topic'],
	'L_AUTHOR' => $lang['Author'],
	'L_FORUM' =>  $lang['Forum'],
	'L_REPLIES' => $lang['Replies'],
	'L_LAST_POST' => $lang['Last_Post']
	)
);

for($i = 0; $i < $count_similar; $i++)
{
	$similar = $similar_topics[$i];
	$tracking_topics = (isset($_COOKIE[$board_config['cookie_name'] .'_t'])) ? unserialize($_COOKIE[$board_config['cookie_name'] .'_t']) : array();
	$tracking_forums = (isset($_COOKIE[$board_config['cookie_name'] .'_f'])) ? unserialize($_COOKIE[$board_config['cookie_name'] .'_f']) : array();
	$topic_type =  ($similar['topic_type'] == POST_ANNOUNCE) ? $lang['Topic_Announcement'] .' ': '';
	$topic_type .= ($similar['topic_type'] == POST_STICKY) ? $lang['Topic_Sticky'] .' ': '';
	$topic_type .= ($similar['topic_vote']) ? $lang['Topic_Poll'] .' ': '';
	$replies = $similar['topic_replies'];

	$topic_class = '';
	if($similar['topic_status'] == TOPIC_LOCKED)
	{
		$folder = $images['topic_nor_locked_read'];
		$folder_new = $images['topic_nor_locked_unread'];
	}
	elseif($similar['topic_type'] == POST_ANNOUNCE)
	{
		$folder = $images['topic_ann_read'];
		$folder_new = $images['topic_ann_unread'];
		$topic_class = 'topic_ann';
	}
	elseif($similar['topic_type'] == POST_GLOBAL_ANNOUNCE)
	{
		$folder = $images['topic_glo_read'];
		$folder_new = $images['topic_glo_unread'];
		$topic_class = 'topic_glo';
	}
	elseif($similar['topic_type'] == POST_STICKY)
	{
		$folder = $images['topic_imp_read'];
		$folder_new = $images['topic_imp_unread'];
		$topic_class = 'topic_imp';
	}
	else
	{
		if($replies >= $board_config['hot_threshold'])
		{
			$folder = $images['topic_hot_read'];
			$folder_new = $images['topic_hot_unread'];
		}
		else
		{
			$folder = $images['topic_nor_read'];
			$folder_new = $images['topic_nor_unread'];
		}
	}

	if($userdata['session_logged_in'])
	{
		if($similar['post_time'] > $userdata['user_lastvisit'])
		{
			if(!empty($tracking_topics) || !empty($tracking_forums) || isset($_COOKIE[$board_config['cookie_name'] .'_f_all']))
			{
				$unread_topics = true;
				if(!empty($tracking_topics[$topic_id]))
				{
					if($tracking_topics[$topic_id] >= $similar['post_time'])
					{
						$unread_topics = false;
					}
				}
				if(!empty($tracking_forums[$forum_id]))
				{
					if($tracking_forums[$forum_id] >= $similar['post_time'])
					{
						$unread_topics = false;
					}
				}
				if(isset($_COOKIE[$board_config['cookie_name'] .'_f_all']))
				{
					if($_COOKIE[$board_config['cookie_name'] .'_f_all'] >= $similar['post_time'])
					{
						$unread_topics = false;
					}
				}

				if ($unread_topics)
				{
					$folder_image = $folder_new;
					$folder_alt = $lang['New_posts'];
					$newest_img = '<a href="' . append_sid(VIEWTOPIC_MG . '?' . POST_TOPIC_URL . '=' . $topic_id . '&amp;view=newest') . '"><img src="' . $images['icon_newest_reply'] . '" alt="' . $lang['View_newest_post'] . '" title="' . $lang['View_newest_post'] . '" /></a> ';
				}
				else
				{
					$folder_image = $folder;
					$folder_alt = ($similar['topic_status'] == TOPIC_LOCKED) ? $lang['Topic_locked'] : $lang['No_new_posts'];
					$newest_img = '';
				}
			}
			else
			{
				$folder_image = $folder_new;
				$folder_alt = ($similar['topic_status'] == TOPIC_LOCKED) ? $lang['Topic_locked'] : $lang['New_posts'];
				$newest_img = '<a href="' . append_sid(VIEWTOPIC_MG . '?' . POST_TOPIC_URL . '=' . $topic_id . '&amp;view=newest') . '"><img src="' . $images['icon_newest_reply'] . '" alt="' . $lang['View_newest_post'] . '" title="' . $lang['View_newest_post'] . '" /></a> ';
			}
		}
		else
		{
			$folder_image = $folder;
			$folder_alt = ($similar['topic_status'] == TOPIC_LOCKED) ? $lang['Topic_locked'] : $lang['No_new_posts'];
			$newest_img = '';
		}
	}
	else
	{
		$folder_image = $folder;
		$folder_alt = ($similar['topic_status'] == TOPIC_LOCKED) ? $lang['Topic_locked'] : $lang['No_new_posts'];
		$newest_img = '';
	}

	// Censor topic title
	if (!empty($orig_word) && count($orig_word) && !$userdata['user_allowswearywords'])
	{
		$similar['topic_title'] = @preg_replace($orig_word, $replacement_word, $similar['topic_title']);
	}

	$similar_topic_title = (strlen($similar['topic_title']) > 45) ? (substr($similar['topic_title'], 0, 42) . '...') : $similar['topic_title'];
	// Convert and clean special chars!
	$similar_topic_title = htmlspecialchars_clean($similar_topic_title);
	// SMILEYS IN TITLE - BEGIN
	if (($board_config['smilies_topic_title'] == true) && !$lofi)
	{
		$bbcode->allow_smilies = ($board_config['allow_smilies'] ? true : false);
		$similar_topic_title = $bbcode->parse_only_smilies($similar_topic_title);
	}
	// SMILEYS IN TITLE - END
	$topic_url = '<a href="' . append_sid(VIEWTOPIC_MG . '?' . POST_TOPIC_URL . '=' . $similar['topic_id']) . '" class="' . $topic_class . '">' . $similar_topic_title . '</a>';

	$author = ($similar['user_id'] != ANONYMOUS) ? colorize_username($similar['user_id'], $similar['username'], $similar['user_color'], $similar['user_active']) : (($similar['post_username'] != '') ? '<span style="font-weight: bold; color: ' . $board_config['active_users_color'] . '">' . $similar['post_username'] . '</span>' : '<span style="font-weight: bold; color: ' . $board_config['active_users_color'] . '">' . $lang['Guest'] . '</span>');

	$forum_url = append_sid(VIEWFORUM_MG . '?' . POST_FORUM_URL . '=' . $similar['forum_id']);
	$forum = '<a href="' . $forum_url . '">' . $similar['forum_name'] . '</a>';

	$last_post_author = ($similar['id2'] != ANONYMOUS) ? colorize_username($similar['id2'], $similar['user2'], $similar['user_color2'], $similar['user_active2']) : (($similar['post_username2'] != '') ? '<span style="font-weight: bold; color: ' . $board_config['active_users_color'] . '">' . $similar['post_username2'] . '</span>' : '<span style="font-weight: bold; color: ' . $board_config['active_users_color'] . '">' . $lang['Guest'] . '</span>');

	$post_url = '<a href="' . append_sid(VIEWTOPIC_MG . '?' . POST_POST_URL . '=' . $similar['topic_last_post_id']) . '#p' . $similar['topic_last_post_id'] . '"><img src="' . $images['icon_latest_reply'] . '" alt="' . $lang['View_latest_post'] . '" title="' . $lang['View_latest_post'] . '" /></a><br />' . $last_post_author;

	$post_time = create_date_ip($board_config['default_dateformat'], $similar['topic_time'], $board_config['board_timezone']);

	$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];

	$template->assign_block_vars('similar.topics', array(
		'ROW_CLASS' => $row_class,
		'FOLDER' => $folder_image,
		'ALT' => $folder_alt,
		'TYPE' => $topic_type,
		'TITLE' => $topic_url,
		'AUTHOR' => $author,
		'FORUM' => $forum,
		'REPLIES' => $replies,
		'NEWEST' => $newest_img,
		'POST_TIME' => $post_time,
		'POST_URL' => $post_url
		)
	);

	/*
	if (!empty($similar['topic_desc']))
	{
		if (!empty($orig_word) && count($orig_word) && !$userdata['user_allowswearywords'])
		{
			$similar['topic_desc'] = @preg_replace($orig_word, $replacement_word, $similar['topic_desc']);
		}
		$topic_desc = (strlen($similar['topic_desc']) > 40) ? (substr($similar['topic_desc'], 0, 37) . '...') : $similar['topic_desc'];
		$template->assign_block_vars('similar.topics.desc', array(
			'TOPIC_DESC' => $topic_desc)
		);
	}
	*/
} //for

$template->assign_var_from_handle('SIMILAR_VIEWTOPIC', 'similar_viewtopic');

?>