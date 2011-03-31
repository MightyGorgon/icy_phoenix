<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);

// Start session management
$user->session_begin();
//$auth->acl($user->data);
$user->setup();
// End session management

//Gather required Information
$subs_forums_list_sql = 'SELECT forum_id FROM ' . FORUMS_WATCH_TABLE . ' WHERE user_id = ' . $user->data['user_id'] . ' AND notify_status = 0';
$subs_forums_list = $db->sql_query($subs_forums_list_sql);

$tracking_topics = (isset($_COOKIE[$config['cookie_name'] . '_t'])) ? unserialize($_COOKIE[$config['cookie_name'] . '_t']) : array();
$tracking_forums = (isset($_COOKIE[$config['cookie_name'] . '_f'])) ? unserialize($_COOKIE[$config['cookie_name'] . '_f']) : array();

$subscribed_forums_count = 0;
while ($subs_forum_line = $db->sql_fetchrow($subs_forums_list))
{
	$subs_forum_id = $subs_forum_line['forum_id'];
	$subs_forums_name_sql = 'SELECT f.forum_id, f.forum_topics, f.forum_posts, f.forum_name, f.forum_desc, f.forum_last_post_id, f.forum_status, p.poster_id, p.post_id, p.post_time, u.user_id, u.username, u.user_active, u.user_color
		FROM ' . FORUMS_TABLE . ' f, ' . POSTS_TABLE . ' p, ' . USERS_TABLE . ' u
		WHERE f.forum_id = ' . $subs_forum_id . '
		AND p.post_id = f.forum_last_post_id
		AND u.user_id = p.poster_id';
	$subs_forums_name = $db->sql_query($subs_forums_name_sql);

	$empty_forum_status = 0;
	if ($db->sql_numrows($subs_forums_name) == 0)
	{
		$empty_forum_status = 1;

		$subs_forums_name_sql='SELECT f.forum_id, f.forum_topics, f.forum_posts, f.forum_name, f.forum_desc, f.forum_last_post_id, f.forum_status
			FROM ' . FORUMS_TABLE . ' f
			WHERE f.forum_id = ' . $subs_forum_id;
		$subs_forums_name = $db->sql_query($subs_forums_name_sql);
	}

	$forum_counter = 0;
	while ($subs_forums_name_line = $db->sql_fetchrow($subs_forums_name))
	{
		$forum_counter++;
		$is_auth = array();
		$is_auth = auth(AUTH_VIEW, $subs_forum_id, $user->data);

		if ($empty_forum_status == 1)
		{
			$forum_topics = 0;
			$forum_posts = 0;
			$forum_last_post_time = '';
			$last_post = $lang['No_Posts'];
		}
		else
		{
			$forum_topics = $subs_forums_name_line['forum_topics'];
			$forum_posts = $subs_forums_name_line['forum_posts'];
			$forum_last_post_time = create_date_ip($config['default_dateformat'], $subs_forums_name_line['post_time'], $config['board_timezone']);
			$last_post = '';
			$last_post .= ($subs_forums_name_line['user_id'] == ANONYMOUS) ? (($subs_forums_name_line['post_username'] != '') ? $subs_forums_name_line['post_username'] . ' ' : $lang['Guest'] . ' ') : colorize_username($subs_forums_name_line['user_id'], $subs_forums_name_line['username'], $subs_forums_name_line['user_color'], $subs_forums_name_line['user_active']);
			$last_post .= '<a href="' . append_sid(CMS_PAGE_VIEWTOPIC . '?' . POST_POST_URL . '=' . $subs_forums_name_line['forum_last_post_id']) . '#p' . $subs_forums_name_line['forum_last_post_id'] . '"><img src="' . $images['icon_latest_reply'] . '" alt="' . $lang['View_latest_post'] . '" title="' . $lang['View_latest_post'] . '" /></a>';
		}

		if (!empty($is_auth['auth_view']))
		{
			if ($subs_forums_name_line['forum_status'] == FORUM_LOCKED)
			{
				$folder_image = $images['forum_nor_locked_read'];
				$folder_alt = $lang['Forum_locked'];
			}
			else
			{
				$unread_topics = false;
				if ($user->data['session_logged_in'])
				{
					$sql = "SELECT t.forum_id, t.topic_id, p.post_time, t.topic_title
								FROM " . TOPICS_TABLE . " t, " . POSTS_TABLE . " p
								WHERE p.post_id = t.topic_last_post_id
								AND p.post_time > " . $user->data['user_lastvisit'] . "
								AND t.topic_moved_id = 0";
					$result = $db->sql_query($sql);

					$new_topic_data = array();
					while($topic_data = $db->sql_fetchrow($result))
					{
						$new_topic_data[$topic_data['forum_id']][$topic_data['topic_id']] = $topic_data['post_time'];
					}
					$db->sql_freeresult($result);
					$forum_id = $subs_forums_name_line['forum_id'];

					if (!empty($new_topic_data[$forum_id]))
					{
						while(list($check_topic_id, $check_post_time) = @each($new_topic_data[$forum_id]))
						{
							if (empty($tracking_topics[$check_topic_id]))
							{
								$unread_topics = true;
								$forum_last_post_time = max($check_post_time, $forum_last_post_time);
							}
							else
							{
								if ($tracking_topics[$check_topic_id] < $check_post_time)
								{
									$unread_topics = true;
									$forum_last_post_time = max($check_post_time, $forum_last_post_time);
								}
							}
						}

						if (!empty($tracking_forums[$forum_id]))
						{
							if ($tracking_forums[$forum_id] > $forum_last_post_time)
							{
								$unread_topics = false;
							}
						}

						if (isset($_COOKIE[$config['cookie_name'] . '_f_all']))
						{
							if (intval($_COOKIE[$config['cookie_name'] . '_f_all']) > $forum_last_post_time)
							{
								$unread_topics = false;
							}
						}
					}
				}

				$folder_image = ($unread_topics) ? $images['forum_unread'] : $images['forum_nor_read'];
				$folder_alt = ($unread_topics) ? $lang['New_posts'] : $lang['No_new_posts'];
			}

			$template->assign_block_vars('subsc_forums_row', array(
					'ROW_CLASS' => (!($forum_counter % 2)) ? $theme['td_class1'] : $theme['td_class2'],
					'FORUM_FOLDER_IMG' => $folder_image,

					'L_FORUM_FOLDER_ALT' => $folder_alt,

					'S_FORUM_NAME' => $subs_forums_name_line['forum_name'],
					'S_TOPICS' => $forum_topics,
					'S_POSTS' => $forum_posts,
					'S_LAST_POST_TIME' => $forum_last_post_time,
					'S_LAST_POST' => $last_post,

					'U_FORUM' => append_sid(IP_ROOT_PATH . CMS_PAGE_VIEWFORUM . '?' . POST_FORUM_URL . '=' . $subs_forum_id),
					'U_NEWTOPIC' => append_sid(IP_ROOT_PATH . CMS_PAGE_POSTING . '?mode=newtopic&amp;' . POST_FORUM_URL . '=' . $subs_forum_id),
					'U_UNSUBSCRIBE' => append_sid(IP_ROOT_PATH . CMS_PAGE_VIEWFORUM . '?' . POST_FORUM_URL . '=' . $subs_forum_id . '&unwatch=forum&start=0'),
					)
				);
			$subscribed_forums_count++;
		}
	}
}

// Generate the page
include_once(IP_ROOT_PATH . 'includes/users_zebra_block.' . PHP_EXT);

if ($subscribed_forums_count != 0)
{
	$template->assign_block_vars('subsc_forums', array());
}
else
{
	$template->assign_block_vars('subsc_no_forums', array());
}

$template->assign_vars(array(
		'L_SUBSCRIPTIONS' => $lang['UCP_Subscriptions'],
		'L_SUBSCFORUMS' => $lang['UCP_SubscForums'],
		'L_NO_SUBSCRIBED_FORUMS' => $lang['UCP_NoSubscForums'],
		'L_SUBSCFORUMS_FORUM' => $lang['Forum'],
		'L_SUBSCFORUMS_TOPICS' => $lang['Topics'],
		'L_SUBSCFORUMS_POSTS' => $lang['Posts'],
		'L_SUBSCFORUMS_LASTPOST' => $lang['Last_Post'],
		'L_SUBSCFORUMS_NEWTOPIC' => $lang['UCP_SubscForums_NewTopic'],
		'L_LATEST_POST' => $lang['View_latest_post'],
		'L_SUBSCFORUMS_UNSUBSCRIBE' => $lang['UCP_SubscForums_UnSubscribe'],

		'IMG_LAST_POST' => $images['icon_latest_reply'],
		)
);

full_page_generation('subsc_forums_body.tpl', $lang['UCP_SubscForums'], '', '');

?>