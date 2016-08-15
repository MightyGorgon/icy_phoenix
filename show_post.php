<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

// Added to optimize memory for attachments
define('ATTACH_DISPLAY', true);
define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_users.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_post.' . PHP_EXT);

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup();
// End session management

// Start initial var setup
$post_id = request_var(POST_POST_URL, 0);
$light_view = request_var('light_view', 0);

if (empty($post_id))
{
	if (!defined('STATUS_404')) define('STATUS_404', true);
	message_die(GENERAL_MESSAGE, 'NO_TOPIC');
}
$download = (isset($_GET['download'])) ? $_GET['download'] : '';

// Find topic id if user requested a newer or older topic
if (isset($_GET['view']))
{
	if (($_GET['view'] == 'next') || ($_GET['view'] == 'previous'))
	{
		$sql_condition = ($_GET['view'] == 'next') ? '>' : '<';
		$sql_ordering = ($_GET['view'] == 'next') ? 'ASC' : 'DESC';

		$sql = "SELECT topic_id, post_time FROM " . POSTS_TABLE . " WHERE post_id = " . $post_id . " LIMIT 1";
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		$topic_id = $row['topic_id'];
		$post_time = $row['post_time'];

		$sql = "SELECT post_id FROM " . POSTS_TABLE . "
			WHERE topic_id = $topic_id
			AND post_time $sql_condition " . $post_time . "
			ORDER BY post_time $sql_ordering
			LIMIT 1";
		$result = $db->sql_query($sql);

		if ($row = $db->sql_fetchrow($result))
		{
			$post_id = $row['post_id'];
		}
		else
		{
			$message = ($_GET['view'] == 'next') ? 'No_newer_posts' : 'No_older_posts';
			message_die(GENERAL_MESSAGE, $message);
		}
	}
}

if (!isset($post_id))
{
	if (!defined('STATUS_404')) define('STATUS_404', true);
	message_die(GENERAL_MESSAGE, 'NO_TOPIC');
}

// Get topic info ...
$sql = "SELECT t.topic_title, t.topic_id, f.forum_id, f.auth_view, f.auth_read, f.auth_post, f.auth_reply, f.auth_edit, f.auth_delete, f.auth_sticky, f.auth_announce, f.auth_pollcreate, f.auth_vote, f.auth_attachments
	FROM " . TOPICS_TABLE . " t, " . FORUMS_TABLE . " f, " . POSTS_TABLE . " p
	WHERE p.post_id = $post_id
		AND t.topic_id = p.topic_id
		AND f.forum_id = t.forum_id";
$result = $db->sql_query($sql);

$tmp = '';
if (!($forum_row = $db->sql_fetchrow($result)))
{
	if (!defined('STATUS_404')) define('STATUS_404', true);
	message_die(GENERAL_MESSAGE, 'NO_TOPIC');
}

$forum_id = $forum_row['forum_id'];
$topic_title = $forum_row['topic_title'];
$topic_id = $forum_row['topic_id'];

if ($download)
{
	$sql_download = ($download != -1) ? " AND p.post_id = " . intval($download) . " " : '';

	$sql = "SELECT u.*, p.*
		FROM " . POSTS_TABLE . " p, " . USERS_TABLE . " u
		WHERE p.topic_id = $topic_id
			$sql_download
			AND u.user_id = p.poster_id
			ORDER BY p.post_time ASC, p.post_id ASC";
	$result = $db->sql_query($sql);

	$download_file = '';
	$is_auth_read = array();
	while ($row = $db->sql_fetchrow($result))
	{
		$is_auth_read = auth(AUTH_ALL, $row['forum_id'], $user->data);

		$poster_id = $row['user_id'];
		$poster = ($poster_id == ANONYMOUS) ? $lang['Guest'] : $row['username'];
		$post_date = create_date($config['default_dateformat'], $row['post_time'], $config['board_timezone']);
		$post_subject = ($row['post_subject'] != '') ? $row['post_subject'] : '';

		$message = $row['post_text'];
		$message = strip_tags($message);
		$message = preg_replace('/\[url\]|\[\/url\]/si', '', $message);
		$message = preg_replace('/\:[0-9a-z\:]+\]/si', ']', $message);

		$message = unprepare_message($message);
		$search = array('/&#40;/', '/&#41;/', '/&#58;/', '/&#91;/', '/&#93;/', '/&#123;/', '/&#125;/');
		$replace = array('(', ')', ':', '[', ']', '{', '}',);
		$message =  preg_replace($search, $replace, $message);

		$post_subject = censor_text($post_subject);
		$message = censor_text($message);

		$break = "\n\r";
		$line = '-----------------------------------';
		$download_file .= $break . $line . $break . $poster . $break . $post_date . $break . $break . $post_subject . $break . $line . $break . $message . $break;
	}

	$disp_folder = ($download == -1) ? 'Topic_'.$topic_id : 'Post_'.$download;

	if (!$is_auth_read['auth_read'])
	{
		$download_file = sprintf($lang['Sorry_auth_read'], $is_auth_read['auth_read_type']);
		$disp_folder = 'Download';
	}

	$filename = ip_clean_string($config['sitename'], $lang['ENCODING']) . '_' . $disp_folder . '_' . gmdate('Ymd') . '.txt';
	header('Content-Type: text/x-delimtext; name="' . $filename . '"');
	header('Content-Disposition: attachment;filename="' . $filename . '"');
	header('Content-Transfer-Encoding: plain/text');
	header('Content-Length: ' . strlen($download_file));
	print $download_file;

	exit;
}
$is_auth = array();
$is_auth = auth(AUTH_ALL, $forum_id, $user->data, $forum_row);

if (!$is_auth['auth_read'])
{
	message_die(GENERAL_MESSAGE, sprintf($lang['Sorry_auth_read'], $is_auth['auth_read_type']));
}

$meta_content['page_title'] = $topic_title;

$view_prev_post_url = append_sid('show_post.' . PHP_EXT . '?' . POST_POST_URL . '=' . $post_id . '&amp;view=previous');
$view_next_post_url = append_sid('show_post.' . PHP_EXT . '?' . POST_POST_URL .' =' . $post_id . '&amp;view=next');

$template->assign_vars(array(
	'L_AUTHOR' => $lang['Author'],
	'L_MESSAGE' => $lang['Message'],
	'L_POSTED' => $lang['Posted'],
	'L_POST_SUBJECT' => $lang['Post_subject'],
	'L_POST_QUOTE' => $lang['Reply_with_quote'],
	'L_VIEW_NEXT_POST' => $lang['View_next_post'],
	'L_VIEW_PREVIOUS_POST' => $lang['View_previous_post'],
	'L_DOWNLOAD_POST' => $lang['Download_post'],
	'L_DOWNLOAD_TOPIC' => $lang['Download_topic'],
	'L_PM' => $lang['Private_Message'],
	'L_EMAIL' => $lang['Email'],
	'L_POSTS' => $lang['Posts'],
	'L_CONTACTS' => $lang['User_Contacts'],
	'L_WEBSITE' => $lang['Website'],
	'L_FROM' => $lang['Location'],
	'L_ONLINE_STATUS' => $lang['Online_status'],
	'L_USER_PROFILE' => $lang['Profile'],
	'DOWNLOAD_TOPIC' => append_sid('show_post.' . PHP_EXT . '?download=-1&amp;' . POST_TOPIC_URL . '=' . $topic_id),
	'CLOSE_WINDOW' => $lang['Close_window'],
	'IMG_LEFT' => $images['icon_previous'],
	'IMG_RIGHT' => $images['icon_next'],

	'U_VIEW_OLDER_POST' => $view_prev_post_url,
	'U_VIEW_NEWER_POST' => $view_next_post_url
	)
);

if ($config['edit_notes'] == 1)
{
	$template->assign_vars(array(
		'S_EDIT_NOTES' => true,
		)
	);
}

// Mighty Gorgon - Multiple Ranks - BEGIN
$ranks_array = $cache->obtain_ranks(false);
// Mighty Gorgon - Multiple Ranks - END

// Go ahead and pull all data for this topic
$sql = "SELECT u.*, p.*, t.topic_poster, t.topic_label_compiled
	FROM " . POSTS_TABLE . " p, " . USERS_TABLE . " u, " . TOPICS_TABLE . " t
	WHERE p.post_id = " . $post_id . "
	AND p.poster_id = u.user_id
	LIMIT 1";
$result = $db->sql_query($sql);

//init_display_review_attachments($is_auth);

// Okay, let's do the loop, yeah come on baby let's do the loop and it goes like this ...
if ($row = $db->sql_fetchrow($result))
{
	$mini_post_img = $images['icon_minipost'];
	$mini_post_alt = $lang['Post'];

	$i = 0;
	do
	{
		$poster_id = $row['user_id'];
		$poster = colorize_username($row['user_id'], $row['username'], $row['user_color'], $row['user_active']);
		$poster_no_link = colorize_username($row['user_id'], $row['username'], $row['user_color'], $row['user_active'], true);

		$user_info = array();
		$user_info = generate_user_info($row);
		foreach ($user_info as $k => $v)
		{
			$$k = $v;
		}

		$post_date = create_date_ip($config['default_dateformat'], $row['post_time'], $config['board_timezone']);
		$poster_posts = ($row['user_id'] != ANONYMOUS) ? $lang['Posts'] . ': ' . $row['user_posts'] : '';
		$poster_from = ($row['user_from'] && $row['user_id'] != ANONYMOUS) ? $lang['Location'] . ': ' . $row['user_from'] : '';
		$poster_joined = ($row['user_id'] != ANONYMOUS) ? $lang['Joined'] . ': ' . create_date($lang['JOINED_DATE_FORMAT'], $row['user_regdate'], $config['board_timezone']) : '';
		$poster_avatar = $user_info['avatar'];

		// Check For Anonymous User
		if ($user->data['user_id'] != '-1')
		{
			$name_link = '<a href="' . append_sid(CMS_PAGE_PROFILE . '?mode=editprofile&amp;' . $user->data['user_id']) . '">' . $user->data['username'] . '</a>';
		}
		else
		{
			$name_link = $lang['Guest'];
		}

		// Mighty Gorgon - Multiple Ranks - BEGIN
		$user_ranks = generate_ranks($row, $ranks_array);
		// Mighty Gorgon - Multiple Ranks - END

		// Handle anon users posting with usernames
		if (($poster_id == ANONYMOUS) && ($row['post_username'] != ''))
		{
			$poster = $row['post_username'];
			$user_ranks['rank_01_html'] = $lang['Guest'] . '<br />';
		}

		$quote_url = append_sid('posting.' . PHP_EXT . '?mode=quote&amp;' . POST_POST_URL . '=' . $row['post_id']);
		$quote_img = '<a href="' . $quote_url . '" target="_parent"><img src="' . $images['icon_quote'] . '" alt="' . $lang['Reply_with_quote'] . '" title="' . $lang['Reply_with_quote'] . '" /></a>';
		$quote = '<a href="' . $quote_url . '" target="_parent">' . $lang['Reply_with_quote'] . '</a>';

		$post_subject = ($row['post_subject'] != '') ? $row['post_subject'] : '';

		$message = $row['post_text'];
		$message_compiled = (empty($row['post_text_compiled']) || !empty($user->data['session_logged_in']) || !empty($config['posts_precompiled'])) ? false : $row['post_text_compiled'];

		$user_sig = ($row['enable_sig'] && ($row['user_sig'] != '') && $config['allow_sig']) ? $row['user_sig'] : '';

		// Note! The order used for parsing the message _is_ important, moving things around could break any output
		$user_sig = censor_text($user_sig);
		$post_subject = censor_text($post_subject);
		$message = censor_text($message);

		if($user_sig && empty($sig_cache[$row['user_id']]))
		{
			$bbcode->allow_html = ($config['allow_html'] && $user->data['user_allowhtml']) ? true : false;
			$bbcode->allow_bbcode = ($config['allow_bbcode'] && $user->data['user_allowbbcode']) ? true : false;
			$bbcode->allow_smilies = ($config['allow_smilies'] && $user->data['user_allowsmile'] && !$lofi) ? true : false;
			$bbcode->is_sig = true;
			$user_sig = $bbcode->parse($user_sig);
			$bbcode->is_sig = false;
			$sig_cache[$row['user_id']] = $user_sig;
		}
		elseif($user_sig)
		{
			$user_sig = $sig_cache[$row['user_id']];
		}

		// Parse message and/or sig for BBCode if reqd
		$bbcode->allow_html = ((($config['allow_html'] && $user->data['user_allowhtml']) || $config['allow_html_only_for_admins']) && $row['enable_html']) ? true : false;
		$bbcode->allow_bbcode = (($config['allow_bbcode'] && $user->data['user_allowbbcode']) && $row['enable_bbcode']) ? true : false;
		$bbcode->allow_smilies = ($config['allow_smilies'] && $user->data['user_allowsmile'] && $row['enable_smilies'] && !$lofi) ? true : false;

		if($message_compiled === false)
		{
			$bbcode->code_post_id = $row['post_id'];
			$message = $bbcode->parse($message);
			$bbcode->code_post_id = 0;
			if (empty($bbcode->allow_bbcode))
			{
				$message = str_replace("\n", "<br />", preg_replace("/\r\n/", "\n", $message));
			}
		}
		else
		{
			$message = $message_compiled;
		}

		// Replace newlines (we use this rather than nl2br because till recently it wasn't XHTML compliant)
		if ($user_sig != '')
		{
			$user_sig = '<br />' . $config['sig_line'] . '<br />' . $user_sig;
		}

		// Editing information
		if ($row['post_edit_count'])
		{
			$l_edit_time_total = ($row['post_edit_count'] == 1) ? $lang['Edited_time_total'] : $lang['Edited_times_total'];
			$l_edit_id = (intval($row['post_edit_id']) > 1) ? colorize_username($row['post_edit_id']) : $poster;
			$l_edited_by = '<br /><br />' . sprintf($l_edit_time_total, $l_edit_id, create_date($config['default_dateformat'], $row['post_edit_time'], $config['board_timezone']), $row['post_edit_count']);
		}
		else
		{
			$l_edited_by = '';
		}

		if ($row['enable_autolinks_acronyms'])
		{
			$message = $bbcode->acronym_pass($message);
			$message = $bbcode->autolink_text($message, $forum_id);
		}

		if (($config['url_rw'] == '1') || (($config['url_rw_guests'] == '1') && ($user->data['user_id'] == ANONYMOUS)))
		{
			$mini_post_url = str_replace ('--', '-', make_url_friendly($row['post_subject']) . '-vp' . $row['post_id'] . '.html#p' . $row['post_id']);
		}
		else
		{
			$mini_post_url = append_sid(CMS_PAGE_VIEWTOPIC . '?' . POST_POST_URL . '=' . $row['post_id']) . '#p' . $row['post_id'];
		}

		// Again this will be handled by the templating code at some point
		$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];

		$template->assign_block_vars('postrow', array(
			'DOWNLOAD_POST' => append_sid(CMS_PAGE_VIEWTOPIC . '?download=' . $row['post_id'] . '&amp;' . POST_TOPIC_URL . '=' .$topic_id),
			'ROW_CLASS' => $row_class,
			'POSTER_NAME' => $poster,
			'POSTER_NAME_NL' => $poster_no_link,
			// Mighty Gorgon - Multiple Ranks - BEGIN
			'USER_RANK_01' => $user_ranks['rank_01_html'],
			'USER_RANK_01_IMG' => $user_ranks['rank_01_img_html'],
			'USER_RANK_02' => $user_ranks['rank_02_html'],
			'USER_RANK_02_IMG' => $user_ranks['rank_02_img_html'],
			'USER_RANK_03' => $user_ranks['rank_03_html'],
			'USER_RANK_03_IMG' => $user_ranks['rank_03_img_html'],
			'USER_RANK_04' => $user_ranks['rank_04_html'],
			'USER_RANK_04_IMG' => $user_ranks['rank_04_img_html'],
			'USER_RANK_05' => $user_ranks['rank_05_html'],
			'USER_RANK_05_IMG' => $user_ranks['rank_05_img_html'],
			// Mighty Gorgon - Multiple Ranks - END
			'POSTER_JOINED' => $poster_joined,
			'POSTER_POSTS' => $poster_posts,
			'POSTER_FROM' => $poster_from,
			'POSTER_AVATAR' => $poster_avatar,
			'POST_DATE' => $post_date,
			'POST_SUBJECT' => $post_subject,
			'MESSAGE' => $message,
			'SIGNATURE' => $user_sig,
			'EDITED_MESSAGE' => $l_edited_by,
			'MINI_POST_IMG' => $mini_post_img,

			'GENDER' => $user_info['gender'],
			'PROFILE_URL' => $user_info['profile_url'],
			'PROFILE_IMG' => $user_info['profile_img'],
			'PROFILE' => $user_info['profile'],
			'PM_URL' => $user_info['pm_url'],
			'PM_IMG' => $user_info['pm_img'],
			'PM' => $user_info['pm'],
			'SEARCH_URL' => $user_info['search_url'],
			'SEARCH_IMG' => $user_info['search_img'],
			'SEARCH' => $user_info['search'],
			'IP_URL' => $user_info['ip_url'],
			'IP_IMG' => $user_info['ip_img'],
			'IP' => $user_info['ip'],
			'EMAIL_URL' => $user_info['email_url'],
			'EMAIL_IMG' => $user_info['email_img'],
			'EMAIL' => $user_info['email'],
			'WWW_URL' => $user_info['www_url'],
			'WWW_IMG' => $user_info['www_img'],
			'WWW' => $user_info['www'],
			'AIM_URL' => $user_info['aim_url'],
			'AIM_IMG' => $user_info['aim_img'],
			'AIM' => $user_info['aim'],
			'ICQ_STATUS_IMG' => $user_info['icq_status_img'],
			'ICQ_URL' => $user_info['icq_url'],
			'ICQ_IMG' => $user_info['icq_img'],
			'ICQ' => $user_info['icq'],
			'MSN_URL' => $user_info['msn_url'],
			'MSN_IMG' => $user_info['msn_img'],
			'MSN' => $user_info['msn'],
			'SKYPE_URL' => $user_info['skype_url'],
			'SKYPE_IMG' => $user_info['skype_img'],
			'SKYPE' => $user_info['skype'],
			'YIM_URL' => $user_info['yahoo_url'],
			'YIM_IMG' => $user_info['yahoo_img'],
			'YIM' => $user_info['yahoo'],
			'ONLINE_STATUS_URL' => $user_info['online_status_url'],
			'ONLINE_STATUS_CLASS' => $user_info['online_status_class'],
			'ONLINE_STATUS_IMG' => $user_info['online_status_img'],
			'ONLINE_STATUS' => $user_info['online_status'],
			'L_ONLINE_STATUS' => $user_info['online_status_lang'],

			'QUOTE_IMG' => $quote_img,
			'QUOTE_URL' => $quote_url,
			'QUOTE' => $quote,
			'DOWNLOAD_IMG' => $images['icon_download'],
			'IMG_LEFT' => $images['icon_previous'],
			'IMG_RIGHT' => $images['icon_next'],

			'L_MINI_POST_ALT' => $mini_post_alt,

			'U_MINI_POST' => $mini_post_url,
			'U_POST_ID' => $row['post_id']
			)
		);
		//display_review_attachments($row['post_id'], $row['post_attachment'], $is_auth);

		$i++;
	}
	while ($row = $db->sql_fetchrow($result));
}
else
{
	if (!defined('STATUS_404')) define('STATUS_404', true);
	message_die(GENERAL_MESSAGE, 'NO_TOPIC', '', __LINE__, __FILE__, $sql);
}

if (!empty($light_view))
{
	$template->assign_var('NO_PADDING', true);
}

$template_to_parse = (!empty($light_view) ? 'post_review_light.tpl' : 'post_review.tpl');
$gen_simple_header = true;
full_page_generation($template_to_parse, $meta_content['page_title'], '', '');

?>