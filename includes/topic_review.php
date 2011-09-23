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
* @Icy Phoenix is based on phpBB
* @copyright (c) 2008 phpBB Group
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

function topic_review($forum_id, $topic_id, $is_inline_review)
{
	global $db, $config, $template, $images, $theme, $user, $lang, $bbcode, $tree;
	global $user_ip, $starttime, $gen_simple_header;

	if (!$is_inline_review)
	{
		if (!isset($topic_id) || !$topic_id)
		{
			if (!defined('STATUS_404')) define('STATUS_404', true);
			message_die(GENERAL_MESSAGE, 'NO_TOPIC');
		}

		// Get topic info ...
		$sql = "SELECT t.topic_title, t.topic_calendar_time, t.topic_calendar_duration, t.topic_first_post_id, f.forum_id, f.auth_view, f.auth_read, f.auth_post, f.auth_reply, f.auth_edit, f.auth_delete, f.auth_sticky, f.auth_announce, f.auth_pollcreate, f.auth_vote, f.auth_attachments
			FROM " . TOPICS_TABLE . " t, " . FORUMS_TABLE . " f
			WHERE t.topic_id = $topic_id
				AND f.forum_id = t.forum_id";
		$tmp = '';
		attach_setup_viewtopic_auth($tmp, $sql);
		$result = $db->sql_query($sql);

		if (!($forum_row = $db->sql_fetchrow($result)))
		{
			if (!defined('STATUS_404')) define('STATUS_404', true);
			message_die(GENERAL_MESSAGE, 'NO_TOPIC');
		}
		$db->sql_freeresult($result);

		$forum_id = $forum_row['forum_id'];
		$topic_title = $forum_row['topic_title'];
		$topic_calendar_time = intval($forum_row['topic_calendar_time']);
		$topic_first_post_id = intval($forum_row['topic_first_post_id']);
		$topic_calendar_duration = intval($forum_row['topic_calendar_duration']);

		// Start session management
		$user->session_begin();
		$auth->acl($user->data);
		$user->setup();
		// End session management

		$is_auth = array();
		$is_auth = auth(AUTH_ALL, $forum_id, $user->data, $forum_row);

		if (!$is_auth['auth_read'])
		{
			message_die(GENERAL_MESSAGE, sprintf($lang['Sorry_auth_read'], $is_auth['auth_read_type']));
		}

		$gen_simple_header = true;
		$meta_content['page_title'] = $lang['Topic_review'] . ' - ' . $topic_title;
		$meta_content['description'] = '';
		$meta_content['keywords'] = '';
		page_header($meta_content['page_title'], true);
		$template->set_filenames(array('reviewbody' => 'posting_topic_review.tpl'));
	}

	// Go ahead and pull all data for this topic
	$sql = "SELECT u.username, u.user_id, p.*
		FROM " . POSTS_TABLE . " p, " . USERS_TABLE . " u
		WHERE p.topic_id = $topic_id
			AND p.poster_id = u.user_id
		ORDER BY p.post_time DESC
		LIMIT " . $config['posts_per_page'];
	$result = $db->sql_query($sql);

	if (!empty($is_auth))
	{
		init_display_review_attachments($is_auth);
	}

	// Okay, let's do the loop, yeah come on baby let's do the loop and it goes like this ...
	if ($row = $db->sql_fetchrow($result))
	{
		//Begin Lo-Fi Mod
		global $lofi;
		//End Lo-Fi Mod
		$mini_post_img = $images['icon_minipost'];
		$mini_post_alt = $lang['Post'];

		$i = 0;
		do
		{
			$poster_id = $row['user_id'];
			$poster = $row['username'];

			$post_date = create_date($config['default_dateformat'], $row['post_time'], $config['board_timezone']);

			// Handle anon users posting with usernames
			if(($poster_id == ANONYMOUS) && ($row['post_username'] != ''))
			{
				$poster = $row['post_username'];
				$poster_rank = $lang['Guest'];
			}
			elseif ($poster_id == ANONYMOUS)
			{
				$poster = $lang['Guest'];
				$poster_rank = '';
			}

			$post_subject = ($row['post_subject'] != '') ? $row['post_subject'] : '';

			$message = $row['post_text'];

			// Quick Quote - BEGIN
			$look_up_array = array(
				'\"',
				'"',
				"<",
				">",
				"\n",
				chr(13),
			);

			$replacement_array = array(
				'&q_mg;',
				'\"',
				"&lt_mg;",
				"&gt_mg;",
				"\\n",
				"",
			);

			$plain_message = $row['post_text'];
			$plain_message = strtr($plain_message, array_flip(get_html_translation_table(HTML_ENTITIES)));
			//Hide MOD
			if(preg_match('/\[hide/i', $plain_message))
			{
				$search = array("/\[hide\](.*?)\[\/hide\]/");
				$replace = array('[hide]' . $lang['xs_bbc_hide_quote_message'] . '[/hide]');
				$plain_message =  preg_replace($search, $replace, $plain_message);
			}
			//Hide MOD
			$plain_message = censor_text($plain_message);
			$plain_message = str_replace($look_up_array, $replacement_array, $plain_message);
			// Quick Quote - END

			$post_subject = censor_text($post_subject);
			$message = censor_text($message);

			$bbcode->allow_html = (($config['allow_html'] && $row['enable_bbcode']) ? true : false);
			$bbcode->allow_bbcode = (($config['allow_bbcode'] && $row['enable_bbcode']) ? true : false);
			$bbcode->allow_smilies = (($config['allow_smilies'] && $row['enable_smilies']) ? true : false);
			$message = $bbcode->parse($message);

			if ($row['enable_autolinks_acronyms'])
			{
				$message = $bbcode->acronym_pass($message);
				$message = $bbcode->autolink_text($message, $forum_id);
			}
			//$message = kb_word_wrap_pass ($message);
			if (!empty($topic_calendar_time) && ($topic_first_post_id == $row['post_id']))
			{
				$post_subject .= get_calendar_title($topic_calendar_time, $topic_calendar_duration);
			}

			// Again this will be handled by the templating code at some point
			$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];

			$template->assign_block_vars('postrow', array(
				'ROW_CLASS' => $row_class,

				'MINI_POST_IMG' => $mini_post_img,
				'POSTER_NAME' => $poster,
				'POST_DATE' => $post_date,
				'POST_SUBJECT' => $post_subject,
				'MESSAGE' => $message,
				'U_POST_ID' => $row['post_id'],
				'PLAIN_MESSAGE' => $plain_message,

				'L_MINI_POST_ALT' => $mini_post_alt
				)
			);
			if (!empty($is_auth))
			{
				display_review_attachments($row['post_id'], $row['post_attachment'], $is_auth);
			}

			$i++;
		}
		while ($row = $db->sql_fetchrow($result));
	}
	else
	{
		if (!defined('STATUS_404')) define('STATUS_404', true);
		message_die(GENERAL_MESSAGE, 'NO_TOPIC', '', __LINE__, __FILE__, $sql);
	}
	$db->sql_freeresult($result);

	$template->assign_vars(array(
		'L_AUTHOR' => $lang['Author'],
		'L_MESSAGE' => $lang['Message'],
		'L_POSTED' => $lang['Posted'],
		'L_POST_SUBJECT' => $lang['Post_subject'],
		'IMG_QUICK_QUOTE' => $images['icon_quote'],
		'IMG_OFFTOPIC' => $images['icon_offtopic'],
		'L_QUICK_QUOTE' => $lang['QuickQuote'],
		'L_OFFTOPIC' => $lang['OffTopic'],
		'L_TOPIC_REVIEW' => $lang['Topic_review']
		)
	);

	if (!$is_inline_review)
	{
		$template->pparse('reviewbody');
		page_footer(true, '', true);
	}
}

?>