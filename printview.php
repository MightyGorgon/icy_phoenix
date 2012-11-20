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
* Adam Ismay
*
*/

define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup();
// End session management

// gzip_compression
$do_gzip_compress = false;
if($config['gzip_compress'])
{
	$phpver = phpversion();
	if(extension_loaded('zlib'))
	{
		ob_start('ob_gzhandler');
	}
}

header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: pre-check=0, post-check=0, max-age=0', false);
header('Pragma: no-cache');
header('Expires: ' . gmdate('D, d M Y H:i:s', time()) . ' GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');

// Make sure a topic id was passed
$topic_id = request_var(POST_TOPIC_URL, 0);
$topic_id = empty($topic_id) ? request_var('topic', 0) : $topic_id;
$topic_id = ($topic_id > 0) ? $topic_id : 0;

$post_id = request_var(POST_POST_URL, 0);
$post_id = empty($post_id) ? request_var('post', 0) : $post_id;
$post_id = ($post_id > 0) ? $post_id : 0;

if(empty($topic_id) && empty($post_id))
{
	ob_end_clean();
	if (!defined('STATUS_404')) define('STATUS_404', true);
	message_die(GENERAL_MESSAGE, 'NO_TOPIC');
}

$is_article = request_var('is_article', 0);
$start = (!empty($is_article) ? 0 : request_var('start', 0));
$limit = (!empty($is_article) ? 1 : request_var('limit', 50));
$post_order = request_var('post_order', 'ASC');
$post_order = ($post_order == 'DESC') ? 'DESC' : 'ASC';

$template->set_filenames(array('body' => 'viewtopic_print.tpl'));

if (!empty($topic_id))
{
	$sql = "SELECT t.topic_id, t.topic_title, t.topic_status, t.topic_replies, t.topic_time, t.topic_type, t.poll_start, f.forum_name, f.forum_status, f.forum_id, f.auth_view, f.auth_read
		FROM " . TOPICS_TABLE . " t, " . FORUMS_TABLE . " f
		WHERE t.topic_id = '" . $db->sql_escape($topic_id) . "'
			AND f.forum_id = t.forum_id";
}
else
{
	$sql = "SELECT t.topic_id, t.topic_title, t.topic_status, t.topic_replies, t.topic_time, t.topic_type, t.poll_start, f.forum_name, f.forum_status, f.forum_id, f.auth_view, f.auth_read
		FROM " . POSTS_TABLE . " p, " . TOPICS_TABLE . " t, " . FORUMS_TABLE . " f
		WHERE p.post_id = '" . $db->sql_escape($post_id) . "'
			AND t.topic_id = p.topic_id
			AND f.forum_id = p.forum_id";
}
$result = $db->sql_query($sql);

if(!($forum_row = $db->sql_fetchrow($result)))
{
	ob_end_clean();
	if (!defined('STATUS_404')) define('STATUS_404', true);
	message_die(GENERAL_MESSAGE, 'NO_TOPIC');
}
$forum_id = $forum_row['forum_id'];
$forum_name = $forum_row['forum_name'];
$topic_title = $forum_row['topic_title'];
$topic_time = $forum_row['topic_time'];

// Start auth check
$is_auth = array();
$is_auth = auth(AUTH_READ, $forum_id, $user->data, $forum_row);

if(!$is_auth['auth_read'])
{
	if (!$user->data['session_logged_in'])
	{
		$redirect = POST_TOPIC_URL . '=' . $topic_id;
		header('Location: ' . append_sid(CMS_PAGE_LOGIN . '?redirect=printview.' . PHP_EXT . '&' . $redirect, true));
	}
	$message = sprintf($lang['Sorry_auth_read'], $is_auth['auth_read_type']);
	ob_end_clean();
	message_die(GENERAL_MESSAGE, $message);
}
// End auth check

// Right we have auth checked and a topic id so we can fetch the topic data.
if (!empty($topic_id))
{
	$sql = "SELECT u.username, u.user_id, u.user_posts, u.user_from, u.user_website, u.user_email, u.user_icq, u.user_aim, u.user_yim, u.user_regdate, u.user_msnm, u.user_allow_viewemail, u.user_rank, u.user_sig, u.user_avatar, u.user_avatar_type, u.user_allowavatar, u.user_allowsmile, p.*
		FROM " . POSTS_TABLE . " p, " . USERS_TABLE . " u
		WHERE p.topic_id = '" . $db->sql_escape($topic_id) . "'
			AND u.user_id = p.poster_id
		ORDER BY p.post_time $post_order
		LIMIT $start, $limit";
}
else
{
	$is_article = 1;
	$sql = "SELECT u.username, u.user_id, u.user_posts, u.user_from, u.user_website, u.user_email, u.user_icq, u.user_aim, u.user_yim, u.user_regdate, u.user_msnm, u.user_allow_viewemail, u.user_rank, u.user_sig, u.user_avatar, u.user_avatar_type, u.user_allowavatar, u.user_allowsmile, p.*
		FROM " . POSTS_TABLE . " p, " . USERS_TABLE . " u
		WHERE p.post_id = '" . $db->sql_escape($post_id) . "'
			AND u.user_id = p.poster_id";
}
$result = $db->sql_query($sql);

if(!$total_posts = $db->sql_numrows($result))
{
	ob_end_clean();
	message_die(GENERAL_MESSAGE, $lang['No_posts_topic']);
}
$postrow = $db->sql_fetchrowset($result);
$db->sql_freeresult($result);

$topic_title = censor_text($topic_title);

// Loop through the posts (even though there is only one)
for($i = 0; $i < $total_posts; $i++)
{
	$poster_id = $postrow[$i]['user_id'];
	$poster = $postrow[$i]['username'];

	$post_date = create_date($config['default_dateformat'], $postrow[$i]['post_time'], $config['board_timezone']);
	$post_subject = ($postrow[$i]['post_subject'] != '') ? $postrow[$i]['post_subject'] : '';
	$message = $postrow[$i]['post_text'];

	$post_subject = censor_text($post_subject);
	$message = censor_text($message);

	// Convert and clean special chars!
	$post_subject = htmlspecialchars_clean($post_subject);

	// SMILEYS IN TITLE - BEGIN
	if (($config['smilies_topic_title'] == true) && !$lofi)
	{
		$bbcode->allow_smilies = ($config['allow_smilies'] && $postrow[$i]['enable_smilies'] ? true : false);
		$post_subject = $bbcode->parse_only_smilies($post_subject);
	}
	// SMILEYS IN TITLE - END

	// Mighty Gorgon - New BBCode Functions - BEGIN
	$bbcode->allow_html = (($config['allow_html'] && $user->data['user_allowhtml']) || $config['allow_html_only_for_admins']) && $postrow[$i]['enable_html'];
	$bbcode->allow_bbcode = $config['allow_bbcode'] && $user->data['user_allowbbcode'] && $postrow[$i]['enable_bbcode'];
	$bbcode->allow_smilies = $config['allow_smilies'] && empty($lofi) && $postrow[$i]['enable_smilies'];

	if(preg_match('/\[code/i', $message))
	{
		$bbcode->allow_html = false;
	}

	if(preg_match('/\[hide/i', $message))
	{
		$message_compiled = false;
	}

	$message = $bbcode->parse($message);
	if ($bbcode->allow_bbcode == false)
	{
		$message = str_replace("\n", "<br />", preg_replace("/\r\n/", "\n", $message));
	}
	// Mighty Gorgon - New BBCode Functions - END

	$template->assign_block_vars('postrow', array(
		'POSTER_NAME' => $poster,
		'POST_DATE' => $post_date,
		'POST_SUBJECT' => $post_subject,
		'MESSAGE' => $message
		)
	);
}

// Set up all the other template variables
$meta_content['page_title'] = $lang['View_topic'] . ' - ' . $topic_title;
$meta_content['description'] = '';
$meta_content['keywords'] = '';
$s_hidden_fields = '<input type="hidden" name="' . POST_TOPIC_URL . '" value="' . $topic_id . '" />';
$template->assign_vars(array(
	'FORUM_ID' => $forum_id,
	'FORUM_NAME' => $forum_name,
	'TOPIC_ID' => $topic_id,
	'TOPIC_TITLE' => $topic_title,
	'SITENAME' => $config['sitename'],
	'SITE_DESCRIPTION' => $config['site_desc'],
	'PAGE_TITLE' => $meta_content['page_title'],
	'POSTS_START' => $start,
	'POSTS_LIMIT' => $limit,

	'L_POSTED' => $lang['Posted'],
	'L_POST_SUBJECT' => $lang['Post_subject'],
	'L_POSTED' => $lang['Posted'],
	'L_AUTHOR' => $lang['Author'],
	'L_SUBJECT' => $lang['Subject'],
	'L_MESSAGE' => $lang['Message'],
	'L_FORUM' => $lang['Forum'],
	'L_TOPICS' => $lang['Topics'],

	'IS_ARTICLE' => (!empty($is_article) ? true : false),

	'U_TOPIC' => append_sid(CMS_PAGE_VIEWTOPIC . '?' . POST_TOPIC_URL . '=' . $topic_id),

	'S_ACTION' => append_sid('printview.' . PHP_EXT . '?' . POST_TOPIC_URL . '=' . $topic_id),
	'S_HIDDEN_FIELDS' => $s_hidden_fields,
	'S_CONTENT_DIRECTION' => $lang['DIRECTION'],
	'S_CONTENT_ENCODING' => $lang['ENCODING'],
	'S_TIMEZONE' => sprintf($lang['All_times'], $lang['tz'][str_replace('.0', '', sprintf('%.1f', number_format($config['board_timezone'], 1)))]),
	)
);

// Right, thats got it all, send out to template.
$template->pparse('body');
$db->sql_close();

// Compress buffered output if required and send to browser
if($do_gzip_compress)
{
	// Borrowed from php.net!
	$gzip_contents = ob_get_contents();
	ob_end_clean();

	$gzip_size = strlen($gzip_contents);
	$gzip_crc = crc32($gzip_contents);

	$gzip_contents = gzcompress($gzip_contents, 9);
	$gzip_contents = substr($gzip_contents, 0, strlen($gzip_contents) - 4);

	echo "\x1f\x8b\x08\x00\x00\x00\x00\x00";
	echo $gzip_contents;
	echo pack("V", $gzip_crc);
	echo pack("V", $gzip_size);
}
exit;

?>