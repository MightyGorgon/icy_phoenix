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

function topic_review($topic_id, $is_inline_review)
{
	global $db, $board_config, $template, $lang, $images, $theme, $bbcode;
	global $userdata, $user_ip;
	global $orig_word, $replacement_word;
	global $orig_autolink, $replacement_autolink;
	global $starttime;
	global $tree;

	if ( !$is_inline_review )
	{
		if ( !isset($topic_id) || !$topic_id)
		{
			message_die(GENERAL_MESSAGE, 'Topic_post_not_exist');
		}

		// Get topic info ...
		$sql = "SELECT t.topic_title, t.topic_calendar_time, t.topic_calendar_duration, t.topic_first_post_id, f.forum_id, f.auth_view, f.auth_read, f.auth_post, f.auth_reply, f.auth_edit, f.auth_delete, f.auth_sticky, f.auth_announce, f.auth_pollcreate, f.auth_vote, f.auth_attachments
			FROM " . TOPICS_TABLE . " t, " . FORUMS_TABLE . " f
			WHERE t.topic_id = $topic_id
				AND f.forum_id = t.forum_id";
		$tmp = '';
		attach_setup_viewtopic_auth($tmp, $sql);

		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not obtain topic information', '', __LINE__, __FILE__, $sql);
		}

		if ( !($forum_row = $db->sql_fetchrow($result)) )
		{
			message_die(GENERAL_MESSAGE, 'Topic_post_not_exist');
		}
		$db->sql_freeresult($result);

		$forum_id = $forum_row['forum_id'];
		$topic_title = $forum_row['topic_title'];
		$topic_calendar_time = intval($forum_row['topic_calendar_time']);
		$topic_first_post_id = intval($forum_row['topic_first_post_id']);
		$topic_calendar_duration = intval($forum_row['topic_calendar_duration']);

		// Start session management
		$userdata = session_pagestart($user_ip);
		init_userprefs($userdata);
		// End session management

		$is_auth = array();
		$is_auth = auth(AUTH_ALL, $forum_id, $userdata, $forum_row);

		if ( !$is_auth['auth_read'] )
		{
			message_die(GENERAL_MESSAGE, sprintf($lang['Sorry_auth_read'], $is_auth['auth_read_type']));
		}
	}

	//
	// Define censored word matches
	//
	if ( empty($orig_word) && !$userdata['user_allowswearywords'] )
	{
		$orig_word = array();
		$replacement_word = array();

		obtain_word_list($orig_word, $replacement_word);
	}
	// Start Autolinks For phpBB Mod
	if ( empty($orig_autolink) && empty($replacement_autolink) )
	{
		$orig_autolink = array();
		$replacement_autolink = array();
		obtain_autolink_list($orig_autolink, $replacement_autolink, $forum_id);
	}

	// End Autolinks For phpBB Mod

	//
	// Dump out the page header and load viewtopic body template
	//
	if ( !$is_inline_review )
	{
		$gen_simple_header = true;

		$page_title = $lang['Topic_review'] . ' - ' . $topic_title;
		$meta_description = '';
		$meta_keywords = '';
		include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);

		$template->set_filenames(array('reviewbody' => 'posting_topic_review.tpl'));
	}

	//
	// Go ahead and pull all data for this topic
	//
	$sql = "SELECT u.username, u.user_id, p.*, pt.post_text, pt.post_text_compiled, pt.post_subject
		FROM " . POSTS_TABLE . " p, " . USERS_TABLE . " u, " . POSTS_TEXT_TABLE . " pt
		WHERE p.topic_id = $topic_id
			AND p.poster_id = u.user_id
			AND p.post_id = pt.post_id
		ORDER BY p.post_time DESC
		LIMIT " . $board_config['posts_per_page'];
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain post/user information', '', __LINE__, __FILE__, $sql);
	}

	init_display_review_attachments($is_auth);

	//
	// Okay, let's do the loop, yeah come on baby let's do the loop
	// and it goes like this ...
	//
	if ( $row = $db->sql_fetchrow($result) )
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

			$post_date = create_date($board_config['default_dateformat'], $row['post_time'], $board_config['board_timezone']);

			// Handle anon users posting with usernames
			if(($poster_id == ANONYMOUS) && ($row['post_username'] != ''))
			{
				$poster = $row['post_username'];
				$poster_rank = $lang['Guest'];
			}
			elseif ( $poster_id == ANONYMOUS )
			{
				$poster = $lang['Guest'];
				$poster_rank = '';
			}

			$post_subject = ($row['post_subject'] != '') ? $row['post_subject'] : '';

			$message = $row['post_text'];

			// Quick Quote - BEGIN
			$plain_message = $row['post_text'];
			$plain_message = str_replace('-->', '--&gt;', $plain_message);
			if( preg_match('/\[hide/i', $plain_message) )
			{
			  $search = array("/\[hide\](.*?)\[\/hide\]/");
			  $replace = array('[hide]' . $lang['xs_bbc_hide_quote_message'] . '[/hide]');
			  $plain_message =  preg_replace($search, $replace, $plain_message);
			}
			//$plain_message = str_replace('postrow -->', 'postrow --&gt;', $plain_message);
			//$plain_message = str_replace('<', '&lt;', $plain_message);
			//$plain_message = str_replace('>', '&gt;', $plain_message);
			//$plain_message = str_replace('&amp;', '&', $plain_message);
			//$plain_message = str_replace('<br />', "\n", $plain_message);
			if (empty($orig_word) && !$userdata['user_allowswearywords'])
			{
				$orig_word = array();
				$replacement_word = array();
				obtain_word_list($orig_word, $replacement_word);
			}

			if ( !empty($orig_word) )
			{
				$plain_message = ( !empty($plain_message) ) ? preg_replace($orig_word, $replace_word, $plain_message) : '';
			}
			$plain_message = addslashes($plain_message);
			$plain_message = str_replace("\n", "\\n", $plain_message);
			// Quick Quote - END

			if(!empty($row['post_text_compiled']))
			{
				$message = $row['post_text_compiled'];
			}
			else
			{
				$bbcode->allow_html = (($board_config['allow_html'] && $row['enable_bbcode']) ? true : false);
				$bbcode->allow_bbcode = (($board_config['allow_bbcode'] && $row['enable_bbcode']) ? true : false);
				$bbcode->allow_smilies = (($board_config['allow_smilies'] && $row['enable_smilies']) ? true : false);
				$message = $bbcode->parse($message);
			}

			if (count($orig_word) && !$userdata['user_allowswearywords'])
			{
				$post_subject = preg_replace($orig_word, $replacement_word, $post_subject);
				$message = preg_replace($orig_word, $replacement_word, $message);
			}
			if ($row['enable_autolinks_acronyms'] == 1)
			{
				$message = $bbcode->acronym_pass($message);
				if (count($orig_autolink))
				{
					$message = autolink_transform($message, $orig_autolink, $replacement_autolink);
				}
			}
			//$message = kb_word_wrap_pass ($message);
			if (!empty($topic_calendar_time) && ($topic_first_post_id == $row['post_id']))
			{
				$post_subject .= get_calendar_title($topic_calendar_time, $topic_calendar_duration);
			}
			//
			// Again this will be handled by the templating
			// code at some point
			//
			$row_color = ( !($i % 2) ) ? $theme['td_color1'] : $theme['td_color2'];
			$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

			$template->assign_block_vars('postrow', array(
				'ROW_COLOR' => '#' . $row_color,
				'ROW_CLASS' => $row_class,

				'MINI_POST_IMG' => $mini_post_img,
				'POSTER_NAME' => $poster,
				'POST_DATE' => $post_date,
				'POST_SUBJECT' => $post_subject,
				'MESSAGE' => $message,
				'U_POST_ID' => $row['post_id'],
				'PLAIN_MESSAGE' => str_replace(chr(13), '', $plain_message),

				'L_MINI_POST_ALT' => $mini_post_alt)
			);
			display_review_attachments($row['post_id'], $row['post_attachment'], $is_auth);


			$i++;
		}
		while ( $row = $db->sql_fetchrow($result) );
	}
	else
	{
		message_die(GENERAL_MESSAGE, 'Topic_post_not_exist', '', __LINE__, __FILE__, $sql);
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

	if ( !$is_inline_review )
	{
		$template->pparse('reviewbody');
		include(IP_ROOT_PATH . 'includes/page_tail.' . PHP_EXT);
	}
}

?>