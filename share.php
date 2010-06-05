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
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

@include_once(IP_ROOT_PATH . 'includes/class_topics.' . PHP_EXT);
$class_topics = new class_topics();

// Init common vars: forum_id, topic_id, post_id, etc.
$class_topics->var_init(true);

$link_topic = false;

if (!empty($post_id))
{
	$sql = "SELECT f.*, t.*, p.*
		FROM " . FORUMS_TABLE . " f, " . TOPICS_TABLE . " t, " . POSTS_TABLE . " p
		WHERE p.post_id = " . $post_id . "
			AND t.topic_id = p.topic_id
			AND f.forum_id = t.forum_id";
}
elseif (!empty($topic_id))
{
	$link_topic = true;
	$sql = "SELECT f.*, t.*, p.*
		FROM " . FORUMS_TABLE . " f, " . TOPICS_TABLE . " t, " . POSTS_TABLE . " p
		WHERE t.topic_id = " . $topic_id . "
			AND p.post_id = t.topic_first_post_id
			AND f.forum_id = t.forum_id";
}
else
{
	message_die(GENERAL_MESSAGE, 'Topic_post_not_exist');
}
// Get topic and post info ...
$result = $db->sql_query($sql);
$post_data = $db->sql_fetchrow($result);
if (empty($post_data))
{
	message_die(GENERAL_MESSAGE, 'Topic_post_not_exist');
}
$db->sql_freeresult($result);

$forum_id = $post_data['forum_id'];
$topic_id = $post_data['topic_id'];
$post_id = $post_data['post_id'];
$forum_id_append = (!empty($forum_id) ? (POST_FORUM_URL . '=' . $forum_id) : '');
$topic_id_append = (!empty($topic_id) ? (POST_TOPIC_URL . '=' . $topic_id) : '');
$post_id_append = (!empty($post_id) ? (POST_POST_URL . '=' . $post_id) : '');
$post_id_append_url = (!empty($post_id) ? ('#p' . $post_id) : '');

$is_auth = array();
$is_auth = auth(AUTH_ALL, $forum_id, $userdata, $post_data);

if (!$is_auth['auth_read'] || !$is_auth['auth_view'])
{
	message_die(GENERAL_MESSAGE, sprintf($lang['Sorry_auth_read'], $is_auth['auth_read_type']));
}

$share_append = (empty($link_topic) ? ($post_id_append . $post_id_append_url): $topic_id_append);
$share_append_rw = (empty($link_topic) ? ('-vp' . $post_id . '.html' . $post_id_append_url) : ('-vt' . $topic_id . '.html'));

$topic_title = $post_data['topic_title'];
$topic_title_enc = urlencode(ip_utf8_decode($topic_title));
// URL Rewrite - BEGIN
// Rewrite Social Bookmars URLs if any of URL Rewrite rules has been enabled
// Forum ID and KB Mode removed from topic_url_enc to avoid compatibility problems with redirects in tell a friend
if (($config['url_rw'] == true) || ($config['url_rw_guests'] == true))
{
	$topic_url = append_sid(make_url_friendly($topic_title) . $share_append_rw);
	$topic_url_ltt = htmlspecialchars((create_server_url() . make_url_friendly($topic_title) . $share_append_rw));
	$topic_url_enc = urlencode(ip_utf8_decode(create_server_url() . make_url_friendly($topic_title) . $share_append_rw));
}
else
{
	$topic_url = append_sid(CMS_PAGE_VIEWTOPIC . '?' . $share_append);
	$topic_url_ltt = htmlspecialchars(ip_utf8_decode(create_server_url() . CMS_PAGE_VIEWTOPIC . '?' . $share_append));
	$topic_url_enc = urlencode(ip_utf8_decode(create_server_url() . CMS_PAGE_VIEWTOPIC . '?' . $share_append));
}
// URL Rewrite - END
// Convert and clean special chars!
$topic_title = htmlspecialchars_clean($topic_title);
$template->assign_vars(array(
	'FORUM_ID' => $forum_id,
	'TOPIC_ID' => $topic_id,
	'POST_ID' => $post_id,
	'TOPIC_TITLE' => $topic_title,
	'TOPIC_TITLE_SHORT' => ((strlen($topic_title) > 80) ? substr($topic_title, 0, 80) . '...' : $topic_title),
	'TOPIC_TITLE_ENC' => $topic_title_enc,
	'TOPIC_URL_ENC' => $topic_url_enc,
	'TOPIC_URL_LTT' => $topic_url_ltt,

	'S_SHARE_BOX' => true,

	'U_TOPIC' => $topic_url,
	'U_TELL' => append_sid('tellafriend.' . PHP_EXT . '?topic_title=' . $topic_title_enc . '&amp;topic_id=' . $topic_id),

	'L_AUTHOR' => $lang['Author'],
	'L_MESSAGE' => $lang['Message'],
	'L_POSTED' => $lang['Posted'],
	'L_POST_SUBJECT' => $lang['Post_subject'],

	'CLOSE_WINDOW' => $lang['Close_window'],
	)
);

$gen_simple_header = true;
$meta_content['page_title'] = $topic_title;
full_page_generation('share_body.tpl', $meta_content['page_title'], '', '');

?>