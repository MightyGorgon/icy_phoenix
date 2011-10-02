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
* netclectic - Adrian Cockburn - adrian@netclectic.com
*
*/

// Added to optimize memory for attachments
define('ATTACH_DISPLAY', true);
define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);

@include_once(IP_ROOT_PATH . 'includes/class_topics.' . PHP_EXT);
$class_topics = new class_topics();

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup();
// End session management

$start = request_var('start', 0);
$start = ($start < 0) ? 0 : $start;

if (!$user->data['session_logged_in'])
{
	$redirect = (isset($start)) ? ('&start=' . $start) : '';
	redirect(append_sid(CMS_PAGE_LOGIN . '?redirect=watched_topics.' . PHP_EXT . $redirect, true));
}

// are we un-watching some topics?
if (isset($_POST['unwatch_list']))
{
	$topic_ids = $_POST['unwatch_list'];
	$sql = "DELETE FROM " . TOPICS_WATCH_TABLE . "
		WHERE " . $db->sql_in_set('topic_id', $topics_ids) . "
			AND user_id = " . $user->data['user_id'];
	$result = $db->sql_query($sql);
}

include_once(IP_ROOT_PATH . 'includes/users_zebra_block.' . PHP_EXT);

$template->assign_vars(array(
	'S_FORM_ACTION' => append_sid(IP_ROOT_PATH . 'watched_topics.' . PHP_EXT),
	'L_NO_WATCHED_TOPICS' => $lang['No_Watched_Topics'],
	'L_FORUM' => $lang['Forum'],
	'L_REPLIES' => $lang['Replies'],
	'L_STARTED' => $lang['Watched_Topics_Started'],
	'L_LAST_POST' => $lang['Last_Post'],
	'L_STOP_WATCH' => $lang['Watched_Topics_Stop'],
	'L_CHECK_ALL' => $lang['Check_All'],
	'L_UNCHECK_ALL' => $lang['UnCheck_All'],
	)
);

$sql = "SELECT COUNT(*) as watch_count FROM " . TOPICS_WATCH_TABLE . " w WHERE w.user_id = " . $user->data['user_id'];
$result = $db->sql_query($sql);
$row = $db->sql_fetchrow($result);
$watch_count = ($row['watch_count']) ? $row['watch_count'] : 0;
$db->sql_freeresult($result);

if ($watch_count > 0)
{
	// grab a list of watched topics
	$sql = "SELECT w.*, t.*, p.post_time, p.poster_id, f.forum_name,
			first.username as author_username, first.user_active as author_active, first.user_color as author_color,
			last.username as last_username, last.user_active as last_user_active, last.user_color as last_user_color
		FROM " . TOPICS_WATCH_TABLE . " w,
			" . TOPICS_TABLE . " t,
			" . POSTS_TABLE . " p,
			" . FORUMS_TABLE . " f,
			" . USERS_TABLE . " first,
			" . USERS_TABLE . " last
		WHERE t.topic_id = w.topic_id
			AND p.post_id = t.topic_last_post_id
			AND t.topic_poster = first.user_id
			AND p.poster_id = last.user_id
			AND f.forum_id = t.forum_id
			AND w.user_id = " . $user->data['user_id'] . "
		ORDER BY t.topic_last_post_id DESC
		LIMIT $start, " . $config['topics_per_page'];
	$result = $db->sql_query($sql);
	$watch_rows = $db->sql_fetchrowset($result);

	// are we currently watching any topics?
	if ($watch_rows)
	{
		$tracking_topics = (isset($_COOKIE[$config['cookie_name'] .'_t'])) ? unserialize($_COOKIE[$config['cookie_name'] .'_t']) : array();
		$tracking_forums = (isset($_COOKIE[$config['cookie_name'] .'_f'])) ? unserialize($_COOKIE[$config['cookie_name'] .'_f']) : array();

		// MG User Replied - BEGIN
		// check if user replied to the topic
		define('USER_REPLIED_ICON', true);
		$user_topics = $class_topics->user_replied_array($watch_rows);
		// MG User Replied - END

		$template->assign_block_vars('switch_watched_topics_block', array());
		for ($i = 0; $i < sizeof($watch_rows); $i++)
		{
			$forum_id = $watch_rows[$i]['forum_id'];
			$topic_id = $watch_rows[$i]['topic_id'];
			$post_id = $watch_rows[$i]['post_id'];
			$forum_id_append = (!empty($forum_id) ? (POST_FORUM_URL . '=' . $forum_id) : '');
			$topic_id_append = (!empty($topic_id) ? (POST_TOPIC_URL . '=' . $topic_id) : '');
			$post_id_append = (!empty($post_id) ? (POST_POST_URL . '=' . $post_id) : '');
			$post_id_append_url = (!empty($post_id) ? ('#p' . $post_id) : '');
			$forum_url = append_sid(CMS_PAGE_VIEWFORUM . '?' . $forum_id_append);
			$topic_url = append_sid(CMS_PAGE_VIEWTOPIC . '?' . $forum_id_append . '&amp;' . $topic_id_append);
			$post_url = append_sid(CMS_PAGE_VIEWTOPIC . '?' . $forum_id_append . '&amp;' . $topic_id_append . '&amp;' . $post_id_append) . $post_id_append_url;
			$user_replied = (!empty($user_topics) && isset($user_topics[$topic_id]));

			$last_poster = ($watch_rows[$i]['poster_id'] == ANONYMOUS) ? (($watch_rows[$i]['last_username'] != '') ? $watch_rows[$i]['last_username'] . ' ' : $lang['Guest'] . ' ') : colorize_username($watch_rows[$i]['poster_id'], $watch_rows[$i]['last_username'], $watch_rows[$i]['last_user_color'], $watch_rows[$i]['last_user_active']);
			$last_poster .= '<a href="' . append_sid(IP_ROOT_PATH . CMS_PAGE_VIEWTOPIC . '?' . POST_POST_URL . '=' . $watch_rows[$i]['topic_last_post_id']) . '#p' . $watch_rows[$i]['topic_last_post_id'] . '"><img src="' . $images['icon_latest_reply'] . '" alt="' . $lang['View_latest_post'] . '" title="' . $lang['View_latest_post'] . '" /></a>';
			$topic_poster = ($watch_rows[$i]['topic_poster'] == ANONYMOUS) ? (($watch_rows[$i]['author_username'] != '') ? $watch_rows[$i]['author_username'] . ' ' : $lang['Guest'] . ' ') : colorize_username($watch_rows[$i]['topic_poster'], $watch_rows[$i]['author_username'], $watch_rows[$i]['author_color'], $watch_rows[$i]['author_active']);

			$news_label = ($watch_rows[$i]['news_id'] > 0) ? $lang['News_Cmx'] . '' : '';

			$replies = $watch_rows[$i]['topic_replies'];

			$topic_link = $class_topics->build_topic_icon_link($watch_rows[$i]['forum_id'], $watch_rows[$i]['topic_id'], $watch_rows[$i]['topic_type'], $watch_rows[$i]['topic_reg'], $watch_rows[$i]['topic_replies'], $watch_rows[$i]['news_id'], $watch_rows[$i]['poll_start'], $watch_rows[$i]['topic_status'], $watch_rows[$i]['topic_moved_id'], $watch_rows[$i]['post_time'], $user_replied, $replies);

			$topic_pagination = generate_topic_pagination($forum_id, $topic_id, $replies);

			$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];
			$template->assign_block_vars('topic_watch_row', array(
				'ROW_CLASS' => $row_class,

				'TOPIC_ID' => $topic_id,
				'TOPIC_FOLDER_IMG' => $topic_link['image'],
				'L_TOPIC_FOLDER_ALT' => $topic_link['image_alt'],
				'TOPIC_AUTHOR' => $topic_author,
				'TOPIC_TITLE' => $watch_rows[$i]['topic_title'],
				'TOPIC_TYPE' => $topic_link['type'],
				'TOPIC_TYPE_ICON' => $topic_link['icon'],
				'TOPIC_CLASS' => (!empty($topic_link['class_new']) ? ('topiclink' . $topic_link['class_new']) : $topic_link['class']),
				'CLASS_NEW' => $topic_link['class_new'],
				'NEWEST_POST_IMG' => $topic_link['newest_post_img'],
				'L_NEWS' => $news_label,
				'TOPIC_ATTACHMENT_IMG' => topic_attachment_image($watch_rows[$i]['topic_attachment']),

				'S_WATCHED_TOPIC_ID' => $watch_rows[$i]['topic_id'],
				'S_WATCHED_TOPIC' => $watch_rows[$i]['topic_title'],
				'S_WATCHED_TOPIC_REPLIES' => $watch_rows[$i]['topic_replies'],
				'S_WATCHED_TOPIC_START' => create_date_ip($config['default_dateformat'], $watch_rows[$i]['topic_time'], $config['board_timezone']),
				'S_WATCHED_TOPIC_LAST' => create_date_ip($config['default_dateformat'], $watch_rows[$i]['post_time'], $config['board_timezone']),
				'FORUM_NAME' => $watch_rows[$i]['forum_name'],
				'TOPIC_POSTER' => $topic_poster,
				'LAST_POSTER' => $last_poster,
				'GOTO_PAGE' => $topic_pagination['base'],
				'GOTO_PAGE_FULL' => $topic_pagination['full'],

				'U_VIEW_FORUM' => append_sid(IP_ROOT_PATH . CMS_PAGE_VIEWFORUM . '?' . $forum_id_append),
				'U_VIEW_TOPIC' => append_sid(IP_ROOT_PATH . CMS_PAGE_VIEWTOPIC . '?' . $forum_id_append . '&amp;' . $topic_id_append),
				)
			);
		}

		$pagination = generate_pagination('watched_topics.' . PHP_EXT . '?mode=watched_topics', $watch_count, $config['topics_per_page'], $start);

		$template->assign_vars(array(
			'PAGINATION' => $pagination,
			'PAGE_NUMBER' => sprintf($lang['Page_of'], (floor($start / $config['topics_per_page']) + 1), ceil($watch_count / $config['topics_per_page'])),
			'L_GOTO_PAGE' => $lang['Goto_page']
			)
		);
	}
	$db->sql_freeresult($result);
}
else
{
	$template->assign_block_vars('switch_no_watched_topics', array());
}

full_page_generation('watched_topics_body.tpl', $lang['Watched_Topics'], '', '');

?>