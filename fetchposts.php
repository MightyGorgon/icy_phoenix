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
* Smartor: EzPortal, phpBB Fetch Posts
* Ca5ey and Mouse Hover Topic Preview MOD by Shannado
*
*/

if (!defined('IN_PHPBB'))
{
	die('Hacking attempt');
}

error_reporting(E_ERROR | E_WARNING | E_PARSE);
set_magic_quotes_runtime(0);

include_once($phpbb_root_path . 'includes/bbcode.' . $phpEx);

function phpbb_fetch_posts($forum_sql, $number_of_posts, $text_length)
{
	global $db, $board_config, $bbcode;

	$sql = 'SELECT t.topic_id, t.topic_time, t.topic_title, t.topic_desc, t.forum_id, t.topic_poster, t.topic_first_post_id, t.topic_status, t.topic_replies, pt.post_text, pt.post_text_compiled, pt.bbcode_uid, pt.post_id, p.post_id, p.enable_smilies, u.username, u.user_id
			FROM ' . TOPICS_TABLE . ' AS t, ' . USERS_TABLE . ' AS u, ' . POSTS_TEXT_TABLE . ' AS pt, ' . POSTS_TABLE . ' AS p
			WHERE t.forum_id IN (' . $forum_sql . ')
				AND t.topic_time <= ' . time() . '
				AND t.topic_poster = u.user_id
				AND t.topic_first_post_id = pt.post_id
				AND t.topic_first_post_id = p.post_id
				AND t.topic_status <> 2
			ORDER BY t.topic_time DESC';
	if ($number_of_posts != 0)
	{
		$sql .= ' LIMIT 0,' . $number_of_posts;
	}

	// query the database
	if(!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not query announcements information', '', __LINE__, __FILE__, $sql);
	}

	// fetch all postings
	$posts = array();
	if ($row = $db->sql_fetchrow($result))
	{
		$i = 0;
		do
		{
			$posts[$i]['bbcode_uid'] = $row['bbcode_uid'];
			$posts[$i]['enable_smilies'] = $row['enable_smilies'];
			$posts[$i]['post_text'] = $row['post_text'];
			$posts[$i]['forum_id'] = $row['forum_id'];
			$posts[$i]['topic_id'] = $row['topic_id'];
			$posts[$i]['topic_replies'] = $row['topic_replies'];
			$posts[$i]['topic_time'] = create_date2($board_config['default_dateformat'], $row['topic_time'], $board_config['board_timezone']);
			$posts[$i]['topic_title'] = $row['topic_title'];
			$posts[$i]['topic_desc'] = $row['topic_desc'];
			$posts[$i]['user_id'] = $row['user_id'];
			$posts[$i]['username'] = $row['username'];

			$message_compiled = empty($posts[$i]['post_text_compiled']) ? false : $posts[$i]['post_text_compiled'];
			$bbcode_uid = $posts[$i]['bbcode_uid'];

			$bbcode->allow_bbcode = $board_config['allow_bbcode'];
			$bbcode->allow_html = $board_config['allow_html'];
			if ($board_config['allow_smilies'] && !$lofi)
			{
				$bbcode->allow_smilies = $board_config['allow_smilies'];
			}
			else
			{
				$bbcode->allow_smilies = false;
			}

			$clean_tags = false;
			if ((strlen($posts[$i]['post_text']) > $text_length) && ($text_length > 0))
			{
				$clean_tags = true;
				$posts[$i]['striped'] = 1;
			}

			if($message_compiled === false)
			{
				$bbcode->allow_smilies = $board_config['allow_smilies'] && $posts[$i]['enable_smilies'] ? true : false;
				$posts[$i]['post_text'] = $bbcode->parse($posts[$i]['post_text'], $bbcode_uid, false, $clean_tags);
			}
			else
			{
				$posts[$i]['post_text'] = $message_compiled;
			}

			if ($clean_tags == true)
			{
				$posts[$i]['post_text'] = (strlen($posts[$i]['post_text']) > $text_length) ? substr($posts[$i]['post_text'], 0, $text_length) . ' ...' : $posts[$i]['post_text'];
			}

			// define censored word matches
			$orig_word = array();
			$replacement_word = array();
			obtain_word_list($orig_word, $replacement_word);
			// censor text and title
			if (count($orig_word))
			{
				$posts[$i]['topic_title'] = preg_replace($orig_word, $replacement_word, $posts[$i]['topic_title']);
				$posts[$i]['post_text'] = preg_replace($orig_word, $replacement_word, $posts[$i]['post_text']);
			}
			$posts[$i]['post_text'] = nl2br($posts[$i]['post_text']);
			$i++;
		}
		while ($row = $db->sql_fetchrow($result));
	}
	// return the result
	return $posts;
} // phpbb_fetch_posts

function phpbb_fetch_posts_attach($forum_sql, $number_of_posts, $text_length, $show_portal = true, $random_mode = false, $single_post = false, $only_auth_view = true)
{
	global $db, $board_config, $bbcode, $userdata;

	$except_forums = build_exclusion_forums_list($only_auth_view);

	$add_to_sql = '';
	if (($single_post == false) && !empty($forum_sql))
	{
		$except_forums_exp = explode(',', str_replace(' ', '', $except_forums));
		$allowed_forums_exp = explode(',', str_replace(' ', '', $forum_sql));
		$except_forums = '';
		for ($e = 0; $e < count($except_forums_exp); $e++)
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

	if ($show_portal == true)
	{
		$add_to_sql .= ' AND t.topic_show_portal = 1';
	}

	if ($random_mode == false)
	{
		$order_sql = 't.topic_time DESC';
	}
	else
	{
		$order_sql = 'RAND()';
	}

	if ($number_of_posts != 0)
	{
		$limit_sql = ' LIMIT 0,' . $number_of_posts;
	}
	else
	{
		$limit_sql = '';
	}

	if ($single_post == true)
	{
		$single_post_id = $forum_sql;
		$sql = "SELECT p.post_id, p.topic_id, p.forum_id, p.enable_smilies, p.post_attachment, p.enable_autolinks_acronyms, pt.post_text, pt.post_text_compiled, pt.bbcode_uid, t.forum_id, t.topic_time, t.topic_title, t.topic_attachment, t.topic_replies, u.username, u.user_id
				FROM " . POSTS_TABLE . " AS p, " . POSTS_TEXT_TABLE . " AS pt, " . TOPICS_TABLE . " AS t, " . USERS_TABLE . " AS u
				WHERE p.post_id = '" . $single_post_id . "'
					" . $add_to_sql . "
					AND pt.post_id = p.post_id
					AND t.topic_id = p.topic_id
					AND p.poster_id = u.user_id";
	}
	else
	{
		$sql = "SELECT t.topic_id, t.topic_time, t.topic_title, t.forum_id, t.topic_poster, t.topic_first_post_id, t.topic_status, t.topic_show_portal, t.topic_attachment, t.topic_replies, pt.post_text, pt.post_text_compiled, pt.post_id, u.username, u.user_id, pt.bbcode_uid, p.post_id, p.enable_smilies, p.post_attachment, p.enable_autolinks_acronyms
				FROM " . TOPICS_TABLE . " AS t, " . USERS_TABLE . " AS u, " . POSTS_TEXT_TABLE . " AS pt, " . POSTS_TABLE . " AS p
				WHERE t.topic_time <= " . time() . "
					" . $add_to_sql . "
					AND t.topic_poster = u.user_id
					AND t.topic_first_post_id = pt.post_id
					AND t.topic_first_post_id = p.post_id
					AND t.topic_status <> 2
				ORDER BY " . $order_sql . $limit_sql;
	}
	// query the database
	if(!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not query announcements information', '', __LINE__, __FILE__, $sql);
	}

	$orig_autolink = array();
	$replacement_autolink = array();
	obtain_autolink_list($orig_autolink, $replacement_autolink, $forum_sql);

	// fetch all postings
	$posts = array();
	if ($row = $db->sql_fetchrow($result))
	{
		$i = 0;
		do
		{
			$posts[$i]['bbcode_uid'] = $row['bbcode_uid'];
			$posts[$i]['enable_smilies'] = $row['enable_smilies'];
			$posts[$i]['enable_autolinks_acronyms'] = $row['enable_autolinks_acronyms'];
			$posts[$i]['post_text'] = $row['post_text'];
			$message = $posts[$i]['post_text'];
			$posts[$i]['forum_id'] = $row['forum_id'];
			$posts[$i]['topic_id'] = $row['topic_id'];
			$posts[$i]['topic_replies'] = $row['topic_replies'];
			$posts[$i]['topic_time'] = create_date2($board_config['default_dateformat'], $row['topic_time'], $board_config['board_timezone']);
			$posts[$i]['topic_title'] = $row['topic_title'];
			$posts[$i]['user_id'] = $row['user_id'];
			$posts[$i]['username'] = $row['username'];
			$posts[$i]['topic_attachment'] = $row['topic_attachment'];
			$posts[$i]['post_id'] = $row['post_id'];
			$posts[$i]['post_attachment'] = $row['post_attachment'];

			$message_compiled = empty($posts[$i]['post_text_compiled']) ? false : $posts[$i]['post_text_compiled'];
			$bbcode_uid = $posts[$i]['bbcode_uid'];

			$bbcode->allow_bbcode = $board_config['allow_bbcode'];
			$bbcode->allow_html = $board_config['allow_html'];
			if ($board_config['allow_smilies'] && !$lofi)
			{
				$bbcode->allow_smilies = $board_config['allow_smilies'];
			}
			else
			{
				$bbcode->allow_smilies = false;
			}

			$clean_tags = false;
			if ((strlen($posts[$i]['post_text']) > $text_length) && ($text_length > 0))
			{
				$clean_tags = true;
				$posts[$i]['striped'] = 1;
			}

			if($message_compiled === false)
			{
				$bbcode->allow_smilies = $board_config['allow_smilies'] && $posts[$i]['enable_smilies'] ? true : false;
				$posts[$i]['post_text'] = $bbcode->parse($posts[$i]['post_text'], $bbcode_uid, false, $clean_tags);
			}
			else
			{
				$posts[$i]['post_text'] = $message_compiled;
			}

			if ($clean_tags == true)
			{
				$posts[$i]['post_text'] = (strlen($posts[$i]['post_text']) > $text_length) ? substr($posts[$i]['post_text'], 0, $text_length) . ' ...' : $posts[$i]['post_text'];
			}

			// define censored word matches
			$orig_word = array();
			$replacement_word = array();
			obtain_word_list($orig_word, $replacement_word);
			// censor text and title
			if (count($orig_word))
			{
				$posts[$i]['topic_title'] = preg_replace($orig_word, $replacement_word, $posts[$i]['topic_title']);
				$posts[$i]['post_text'] = preg_replace($orig_word, $replacement_word, $posts[$i]['post_text']);
			}
			//Acronyms, AutoLinks, Wrap - BEGIN
			if ($posts[$i]['enable_autolinks_acronyms'] == 1)
			{
				if(function_exists('acronym_pass'))
				{
					$posts[$i]['post_text'] = acronym_pass($posts[$i]['post_text']);
				}
				if(count($orig_autolink))
				{
					$posts[$i]['post_text'] = autolink_transform($posts[$i]['post_text'], $orig_autolink, $replacement_autolink);
				}
				//$posts[$i]['post_text'] = kb_word_wrap_pass ($posts[$i]['post_text']);
			}
			//Acronyms, AutoLinks, Wrap -END
			$i++;
		}
		while ($row = $db->sql_fetchrow($result));
	}
	$db->sql_freeresult($result);
	// return the result
	return $posts;
}


function phpbb_fetch_poll($forum_sql)
{
	global $db;

	$sql = 'SELECT t.*, vd.*
					FROM ' . TOPICS_TABLE . ' AS t, ' . VOTE_DESC_TABLE  . ' AS vd
			WHERE t.forum_id IN (' . $forum_sql . ')
				AND t.topic_status <> 1
				AND t.topic_status <> 2
				AND t.topic_vote = 1
				AND t.topic_id = vd.topic_id
			ORDER BY t.topic_time DESC
			LIMIT 0,1';

	if (!$query = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not query poll information', '', __LINE__, __FILE__, $sql);
	}

	$result = $db->sql_fetchrow($query);

	if ($result)
	{
		$sql = 'SELECT * FROM ' . VOTE_RESULTS_TABLE . '
				WHERE vote_id = ' . $result['vote_id'] . '
				ORDER BY vote_option_id';

		if (!$query = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not query vote result information', '', __LINE__, __FILE__, $sql);
		}

		while ($row = $db->sql_fetchrow($query))
		{
			$result['options'][] = $row;
		}
	}

	return $result;
} // end func phpbb_fetch_poll

//
// Function strip all BBcodes (borrowed from Mouse Hover Topic Preview MOD)
//
function bbencode_strip($text, $uid)
{
	// pad it with a space so we can distinguish between FALSE and matching the 1st char (index 0).
	// This is important; bbencode_quote(), bbencode_list(), and bbencode_code() all depend on it.
	$text = " " . $text;

	// First: If there isn't a "[" and a "]" in the message, don't bother.
	if (! (strpos($text, "[") && strpos($text, "]")))
	{
		// Remove padding, return.
		$text = substr($text, 1);
		return $text;
	}

	// [CODE] and [ /CODE ] for posting code (HTML, PHP, C etc etc) in your posts.
	$text = str_replace("[code:1:$uid]","", $text);
	$text = str_replace("[/code:1:$uid]", "", $text);
	$text = str_replace("[code:$uid]", "", $text);
	$text = str_replace("[/code:$uid]", "", $text);

	// [QUOTE] and [/QUOTE] for posting replies with quote, or just for quoting stuff.
	$text = str_replace("[quote:1:$uid]","", $text);
	$text = str_replace("[/quote:1:$uid]", "", $text);
	$text = str_replace("[quote:$uid]", "", $text);
	$text = str_replace("[/quote:$uid]", "", $text);
	// New one liner to deal with opening quotes with usernames...
	// replaces the two line version that I had here before..
	$text = preg_replace("/\[quote:$uid=(?:\"?([^\"]*)\"?)\]/si", "", $text);
	$text = preg_replace("/\[quote:1:$uid=(?:\"?([^\"]*)\"?)\]/si", "", $text);

	// [list] and [list=x] for (un)ordered lists.
	// unordered lists
	$text = str_replace("[list:$uid]", "", $text);
	// li tags
	$text = str_replace("[*:$uid]", "", $text);
	// ending tags
	$text = str_replace("[/list:u:$uid]", "", $text);
	$text = str_replace("[/list:o:$uid]", "", $text);
	// Ordered lists
	$text = preg_replace("/\[list=([a1]):$uid\]/si", "", $text);

	// colours
	$text = preg_replace("/\[color=(\#[0-9A-F]{6}|[a-z]+):$uid\]/si", "", $text);
	$text = str_replace("[/color:$uid]", "", $text);
	$text = preg_replace("/\[glow=(\#[0-9A-F]{6}|[a-z]+):$uid\]/si", "", $text);
	$text = str_replace("[/glow:$uid]", "", $text);
	$text = preg_replace("/\[shadow=(\#[0-9A-F]{6}|[a-z]+):$uid\]/si", "", $text);
	$text = str_replace("[/shadow:$uid]", "", $text);
	$text = preg_replace("/\[highlight=(\#[0-9A-F]{6}|[a-z]+):$uid\]/si", "", $text);
	$text = str_replace("[/highlight:$uid]", "", $text);

	// url #2
	$text = str_replace("[url]","", $text);
	$text = str_replace("[/url]", "", $text);

	// url /\[url=([a-z0-9\-\.,\?!%\*_\/:;~\\&$@\/=\+]+)\](.*?)\[/url\]/si
	$text = preg_replace("/\[url=([a-z0-9\-\.,\?!%\*_\/:;~\\&$@\/=\+]+)\]/si", "", $text);
	$text = str_replace("[/url:$uid]", "", $text);

	// img
	$text = str_replace("[img:$uid]","", $text);
	$text = str_replace("[/img:$uid]", "", $text);
	$text = str_replace("[imgl:$uid]","", $text);
	$text = str_replace("[/imgl:$uid]", "", $text);
	$text = str_replace("[imgr:$uid]","", $text);
	$text = str_replace("[/imgr:$uid]", "", $text);
	$text = str_replace("[albumimg:$uid]","", $text);
	$text = str_replace("[/albumimg:$uid]", "", $text);
	$text = str_replace("[albumimgl:$uid]","", $text);
	$text = str_replace("[/albumimgl:$uid]", "", $text);
	$text = str_replace("[albumimgr:$uid]","", $text);
	$text = str_replace("[/albumimgr:$uid]", "", $text);

	// email
	$text = str_replace("[email:$uid]","", $text);
	$text = str_replace("[/email:$uid]", "", $text);

	// size
	$text = preg_replace("/\[size=([\-\+]?[1-2]?[0-9]):$uid\]/si", "", $text);
	$text = str_replace("[/size:$uid]", "", $text);

	// [b] and [/b] for bolding text.
	$text = str_replace("[b:$uid]","", $text);
	$text = str_replace("[/b:$uid]", "", $text);

	// [u] and [/u] for underlining text.
	$text = str_replace("[u:$uid]", "", $text);
	$text = str_replace("[/u:$uid]", "", $text);

	// [i] and [/i] for italicizing text.
	$text = str_replace("[i:$uid]", "", $text);
	$text = str_replace("[/i:$uid]", "", $text);

	// Remove our padding from the string..
	$text = substr($text, 1);

	return $text;
}

?>