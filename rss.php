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
* Egor Naklonyaeff (http://naklon.info/rss/about.htm)
*
*/

define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);

$ProgName = 'RSS Feed 2.2.4';
$verinfo = 'V224';
include_once(IP_ROOT_PATH . 'includes/rss_config.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/rss_functions.' . PHP_EXT);
// END Includes of phpBB scripts

// MG: not all modes implemented yet
$mode_types = array('all', 'ann', 'cats', 'glo', 'imp', 'news');

if(isset($_GET['mode']) || isset($_POST['mode']))
{
	$mode = (isset($_GET['mode'])) ? htmlspecialchars($_GET['mode']) : htmlspecialchars($_POST['mode']);
}
else
{
	$mode = $mode_types[0];
}

if (!in_array($mode, $mode_types))
{
	$mode = $mode_types[0];
}

$deadline = 0;

if(isset($_SERVER['HTTP_IF_MODIFIED_SINCE']))
{
	$deadline = strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']);
	if(CACHE_TIME > 0) if((time() - $deadline) < CACHE_TIME)
	{
		ExitWithHeader('304 Not Modified');
	}
}
$sql = "SELECT MAX(post_time) as pt FROM ". POSTS_TABLE;
if(!($result = $db->sql_query($sql)))
{
	ExitWithHeader('500 Internal Server Error', 'Error in obtaining post data');
}

if($row = $db->sql_fetchrow($result))
{
	if($row['pt'] <= $deadline)
	{
		ExitWithHeader('304 Not Modified');
	}
	$deadline = $row['pt'];
}

// BEGIN Cache Mod
$use_cached = false;
$cache_file = '';
if(CACHE_TO_FILE && (CACHE_TIME > 0))
{
	$cache_file = IP_ROOT_PATH . $cache_root . $cache_filename;
	if(($cache_root != '') && empty($_GET))
	{
		$cachefiletime = @filemtime($cache_file);
		$timedif = ($deadline - $cachefiletime);
		if(($timedif < CACHE_TIME) && (filesize($cache_file) > 0))
		{
			$use_cached = true;
		}
	}
}
// END Cache Mod

// gzip_compression
$do_gzip_compress = false;
$useragent = (isset($_SERVER['HTTP_USER_AGENT'])) ? $_SERVER['HTTP_USER_AGENT'] : getenv('HTTP_USER_AGENT');
if($use_cached && AUTOSTYLED && strpos($useragent,'MSIE'))
{
	$use_cached = false;
}

if($board_config['gzip_compress'])
{
	$phpver = phpversion();
	if($phpver >= '4.0.4pl1' && (strstr($useragent,'compatible') || strstr($useragent,'Gecko')))
	{
		if(extension_loaded('zlib'))
		{
			ob_start('ob_gzhandler');
		}
	}
	elseif($phpver > '4.0')
	{
		if(strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
		{
			if(extension_loaded('zlib'))
			{
				$do_gzip_compress = true;
				ob_start();
				ob_implicit_flush(0);
				header('Content-Encoding: gzip');
			}
		}
	}
}
// end gzip block

// How many posts do you want to returnd (count)?
// Specified in the URL with "c=".  Defaults to 25, upper limit of 50.
$count = (isset($_GET['c'])) ? intval($_GET['c']) : DEFAULT_ITEMS;
$count = ($count == 0) ? DEFAULT_ITEMS : $count;
$count = ($count > MAX_ITEMS) ? MAX_ITEMS : $count;
// Which forum do you want posts from (forum_id)?  specified in the url with "f=".  Defaults to all (public) forums.
$forum_id = (isset($_GET['f'])) ? intval($_GET['f']) : '';
$no_limit = (isset($_GET['nolimit'])) ? true : false;
$needlogin = (isset($_GET['login']) or isset($_GET['uid'])) ? true : false;

$sql_forum_where = (!empty($forum_id)) ? ' AND f.forum_id = ' . $forum_id : ' ';

// Return topics only, or all posts?  Specified in the URL with "t=".  Defaults to all posts (0).
$topics_only = (isset($_GET['t'])) ? intval($_GET['t']) : 0;
$topics_view = (isset($_GET['topic'])) ? intval($_GET['topic']) : 0;
$sql_topics_only_where = '';
if($topics_only == 1)
{
	$sql_topics_only_where = 'AND p.post_id = t.topic_first_post_id';
}
if($topics_view != 0)
{
	$sql_topic_view = 'AND t.topic_id =' . $topics_view;
}

// BEGIN Session management
// Check user
$user_id = ($needlogin)? rss_get_user() : ANONYMOUS;
if(($user_id == ANONYMOUS) && AUTOLOGIN)
{
	$userdata = session_pagestart($user_ip);
	$user_id = $userdata['user_id'];
}
else
{
	$userdata = rss_session_begin($user_id, $user_ip, 0);
}

init_userprefs($userdata);
$username = $userdata['username'];
// END session management

if(strpos($useragent, 'MSIE'))
{
	$encoding_charset = $lang['ENCODING'];
}
else
{
	$encoding_charset = $lang['ENCODING_ALT'];
}

// BEGIN Cache Mod
if(($user_id == ANONYMOUS) && $use_cached)
{
	$MyETag = '"RSS' . gmdate('YmdHis', $cachefiletime) . $verinfo . '"';
	$MyGMTtime = gmdate('D, d M Y H:i:s', $cachefiletime) . ' GMT';
	if(!empty($_SERVER['SERVER_SOFTWARE']) && strstr($_SERVER['SERVER_SOFTWARE'], 'Apache/2'))
	{
		header('Cache-Control: no-cache, pre-check=0, post-check=0, max-age=0');
	}
	else
	{
		header('Cache-Control: private, pre-check=0, post-check=0, max-age=0');
	}
	header('Last-Modified: ' . $MyGMTtime);
	header('Etag: ' . $MyETag);
	header('Expires: ' . gmdate('D, d M Y H:i:s', time()) . ' GMT');
	header('Content-Type: text/xml; charset=' . $encoding_charset);
	readfile($cache_file);
}
else
{
	// END Cache Mod

	// Define censored word matches
	$orig_word = array();
	$replacement_word = array();
	obtain_word_list($orig_word, $replacement_word);

	// BEGIN Create main board information (some code borrowed from functions_post.php)
	// Build URL components
	$index_url = create_server_url();
	$viewpost = VIEWTOPIC_MG;
	$replypost = POSTING_MG . '?mode=quote';
	$index = PORTAL_MG;
	$viewpost_url = $index_url . $viewpost;
	$replypost_url = $index_url . $replypost;
	// Reformat site name and description
	$site_name = strip_tags($board_config['sitename']);
	$site_description = strip_tags($board_config['site_desc']);
	// Set the fully qualified url to your smilies folder
	$smilies_path = $board_config['smilies_path'];
	$smilies_url = $index_url . $smilies_path;
	$smilies_path = preg_replace("/\//", "\/", $smilies_path);
	// END Create main board information

	// Auth check
	$sql_forum_where='';
	if($userdata['user_level'] <> ADMIN)
	{
		$is_auth = array();
		$is_auth = auth(AUTH_READ, AUTH_LIST_ALL, $userdata);
		if($forum_id == '')
		{
			while (list($forumId, $auth_mode) = each($is_auth))
			{
				if(!$auth_mode['auth_read'])
				{
					$unauthed .= ',' . $forumId;
				}
			}
			$sql_forum_where="AND f.forum_id NOT IN (" . $unauthed . ")";
		}
		else
		{
			if((!$is_auth[$forum_id]['auth_read']) or (strpos(",$unauthed," , ",$forum_id,")))
			{
				if($needlogin)
				{
					ExitWithHeader("404 Not Found","This forum does not exists");
				}
				else
				{
					header('Location: ' . $index_url . 'rss.' . PHP_EXT . '?' . POST_FORUM_URL . '=' . $forum_id . (($no_limit) ? '&nolimit' : '') . (isset($_GET['atom']) ? '&atom' : '') . (isset($_GET['c']) ? '&c=' . $count : '') . (isset($_GET['t']) ? '&t=' . $topics_only : '') . (isset($_GET['styled']) ? '&styled' : '') . '&login');
					ExitWithHeader('301 Moved Permanently');
				}
			}
			else
			{
				$sql_forum_where = 'AND f.forum_id = ' . $forum_id;
			}
		}
		unset($is_auth);
	}
	elseif($forum_id!='')
	{
		$sql_forum_where = 'AND f.forum_id = ' . $forum_id;
	}

	// BEGIN Initialise template
	if(isset($_GET['atom']))
	{
		$template->set_filenames(array('body' => 'atom_body.tpl'));
		$verinfo .= 'A';
	}
	else
	{
		$template->set_filenames(array('body' => 'rss_body.tpl'));
		$verinfo .= 'R';
	}
	// END Initialise template

	if(isset($_GET['styled']) || (AUTOSTYLED && strpos($useragent,'MSIE')))
	{
		$template->assign_block_vars('switch_enable_xslt', array());
	}

	// BEGIN SQL statement to fetch active posts of allowed forums
	$sql_limit_by_http = '';
	$MaxRecordAge = time() - MAX_WEEKS_AGO * 604800;
	$sql_limit_time = (MAX_WEEKS_AGO > 0) ? "p.post_time > " . $MaxRecordAge : "1";
	if(!$no_limit)
	{
		if(isset($_SERVER['HTTP_IF_MODIFIED_SINCE']))
		{
			$NotErrorFlag = true;
			$NotModifiedSince = strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']);
			if(SEE_MODIFYED)
			{
				$sql_limit_by_http =  "AND (p.post_time > " . $NotModifiedSince . " OR p.post_edit_time >" . $NotModifiedSince . ")";
			}
			elseif($NotModifiedSince > $MaxRecordAge)
			{
				$sql_limit_time = "p.post_time > " . $NotModifiedSince;
			}
		}
	}

	$sql_news = '';
	if ($mode == 'news')
	{
		$sql_news = "AND t.news_id > 0";
	}
	$getdesc = ($forum_id <> '') ? ', f.forum_desc' : '';
	$sql = "SELECT f.forum_name" . $getdesc . ", f.forum_topic_views, t.topic_id, t.topic_title, u.user_id, u.username, u.user_sig, u.user_allowsmile, p.*, t.topic_replies, t.topic_first_post_id
		FROM " . FORUMS_TABLE . " AS f, " . TOPICS_TABLE . " AS t, " . USERS_TABLE . " AS u, " . POSTS_TABLE . " AS p
		WHERE
				$sql_limit_time
				$sql_forum_where
				$sql_limit_by_http
				AND t.forum_id = f.forum_id
				AND p.poster_id = u.user_id
				AND p.topic_id = t.topic_id
				$sql_news
				$sql_topics_only_where
				$sql_topic_view
		ORDER BY p.post_time DESC LIMIT $count";
	$posts_query = $db->sql_query($sql);
	// END SQL statement to fetch active posts of public forums

	// BEGIN Query failure check
	if(!$posts_query)
	{
		ExitWithHeader("500 Internal Server Error","Could not query list of active posts");
	}

	$allposts = $db->sql_fetchrowset($posts_query);
	if(($forum_id <> '') && (count($allposts) != 0))
	{
		$site_name = strip_tags($allposts[0]['forum_name']);
		$site_description = $allposts[0]['forum_desc'];
	}

	// BEGIN Assign static variables to template
	// Variable reassignment for Topic Replies
	$l_topic_replies = $lang['Topic'] . ' ' . $lang['Replies'];
	$user_lang = $userdata['user_lang'];
	if(empty($user_lang))
	{
		$user_lang = $board_config['default_lang'];
	}
	$template->assign_vars(array(
		'S_CONTENT_ENCODING' => $lang['ENCODING'],
		'BOARD_URL' => $index_url,
		'BOARD_TITLE' => htmlspecialchars(undo_htmlspecialchars($site_name)),
		'PROGRAM' => $ProgName,
		'BOARD_DESCRIPTION' => htmlspecialchars(undo_htmlspecialchars($site_description)),
		'BOARD_MANAGING_EDITOR' => $board_config['board_email'],
		'BOARD_WEBMASTER' => $board_config['board_email'],
		'BUILD_DATE' => gmdate('D, d M Y H:i:s') . ' GMT',
		'ATOM_BUILD_DATE' => gmdate('Y-m-d\TH:i:s') . 'Z',
		'READER' => $username,
		'L_AUTHOR' => $lang['Author'],
		'L_POSTED' => $lang['Posted'],
		'L_TOPIC_REPLIES' => $l_topic_replies,
		'LANGUAGE' => FormatLanguage($user_lang),
		'L_POST' => $lang['Post']
		)
	);
	// END Assign static variabless to template
	$LastPostTime = 0;
	if(count($allposts) == 0)
	{
		if($NotErrorFlag) ExitWithHeader('304 Not Modified');
	}
	else
	{
	// BEGIN "item" loop
		$PostCount = 0;
		$SeenTopics = array();
		foreach ($allposts as $post)
		{
			if($post['post_time'] > $LastPostTime)
			{
				$LastPostTime = $post['post_time'];
			}
			if($post['post_edit_time'] > $LastPostTime)
			{
				$LastPostTime = $post['post_edit_time'];
			}
			$topic_id = $post['topic_id'];
			$PostCount++;
			$SeenTopics[$topic_id]++;
			// Variable reassignment and reformatting for post text
			$post_id = $post['post_id'];
			$post_subject = ($post['post_subject'] != '') ? $post['post_subject'] : '';
			$message = $post['post_text'];
			$user_sig = ($post['enable_sig'] && $post['user_sig'] != '' && $board_config['allow_sig']) ? $post['user_sig'] : '';
			// If the board has HTML off but the post has HTML on then we process it, else leave it alone
			$html_on = ($userdata['user_allowhtml'] && $board_config['allow_html']) ? 1 : 0 ;
			$bbcode_on = ($userdata['user_allowbbcode'] && $board_config['allow_bbcode']) ? 1 : 0 ;
			$smilies_on = ($userdata['user_allowsmile'] && $board_config['allow_smilies']) ? 1 : 0 ;

			$bbcode->allow_html = $html_on;
			$bbcode->allow_bbcode = $bbcode_on;
			$bbcode->allow_smilies = $smilies_on;
			$text = $bbcode->parse($text);
			$bbcode->is_sig = true;
			$user_sig = $bbcode->parse($user_sig);
			$bbcode->is_sig = false;
			$message = $bbcode->parse($message);

			// Replace naughty words
			if(!empty($orig_word) && count($orig_word))
			{
				$post_subject = preg_replace($orig_word, $replacement_word, $post_subject);

				if($user_sig != '')
				{
					$user_sig = str_replace('\"', '"', substr(preg_replace('#(\>(((?>([^><]+|(?R)))*)\<))#se', "preg_replace(\$orig_word, \$replacement_word, '\\0')", '>' . $user_sig . '<'), 1, -1));
				}

				$message = str_replace('\"', '"', substr(preg_replace('#(\>(((?>([^><]+|(?R)))*)\<))#se', "preg_replace(\$orig_word, \$replacement_word, '\\0')", '>' . $message . '<'), 1, -1));
			}

			// Replace newlines (we use this rather than nl2br because till recently it wasn't XHTML compliant)
			if($user_sig != '')
			{
				$user_sig = '<br />' . $board_config['sig_line'] . '<br />' . str_replace("\n", "\n<br />\n", $user_sig);
				//$user_sig = '<br />_________________<br />' . str_replace("\n", "\n<br />\n", $user_sig);
			}

			$message = str_replace("\n", "\n<br />\n", $message);
			if($post_subject != '')
			{
				$post_subject = $lang['Subject'] . ': ' . $post_subject . '<br />';
			}
			// Variable reassignment for topic title, and show whether it is the start of topic, or a reply
			$topic_title = $post['topic_title'];
			if($post['post_id'] != $post['topic_first_post_id'])
			{
				$topic_title = 'RE: ' . $topic_title;
			}
			// Variable reassignment and reformatting for author
			$author = $post['username'];
			$author0 = $author;
			if($post['user_id'] != -1)
			{
				$author = '<a href="' . $index_url . PROFILE_MG . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $post['user_id'] . '" target="_blank">' . $author . '</a>';
			}
			else
			{
				// Uncomment next string if you want or
				// $author0 = 'Anonymous';
				// $author0 = $post['post_username'];
				$author= $post['post_username'];
			}
			$author = make_clickable($author);
			// Assign "item" variables to template
			$topic_title = utf8_encode($topic_title);
			$post_subject = utf8_encode($post_subject);
			$message = utf8_encode($message);
			$template->assign_block_vars('post_item', array(
				'POST_URL' => $viewpost_url . '?' . POST_POST_URL . '=' . $post['post_id'] . '#p' . $post['post_id'],
				'FIRST_POST_URL' => $viewpost_url . '?' . POST_POST_URL . '=' . $post['topic_first_post_id'] . '#p' . $post['topic_first_post_id'],
				'REPLY_URL' => $replypost_url . '&amp;' . POST_POST_URL . '=' . $post['post_id'],
				'AUTHOR0' => htmlspecialchars($author0),
				'ATOM_TIME' => gmdate('Y-m-d\TH:i:s', $post['post_time']) . 'Z',
				'ATOM_TIME_M' => (($post['post_edit_time'] <> '') ? gmdate('Y-m-d\TH:i:s', $post['post_edit_time']) . 'Z' : gmdate('Y-m-d\TH:i:s', $post['post_time']) . 'Z'),
				'UTF_TIME' => RSSTimeFormat($post['post_time'],$userdata['user_timezone']),

				'FORUM_NAME' => $post['forum_name'],
				'TOPIC_TITLE' => undo_htmlspecialchars($topic_title),

				'AUTHOR' => $author,
				'POST_TIME' => create_date($board_config['default_dateformat'], $post['post_time'], $board_config['board_timezone']) . ' (GMT ' . $board_config['board_timezone'] . ')',
				'POST_SUBJECT' => $post_subject,
				//'POST_TEXT' => htmlspecialchars(preg_replace('|[\x00-\x08\x0B\x0C\x0E-\x1f]|', '', $message)),
				'POST_TEXT' => $message,
				'USER_SIG' => $user_sig,

				'TOPIC_REPLIES' => $post['topic_replies']
				)
			);
		}
		// END "item" loop

		if(($user_id != ANONYMOUS) && UPDATE_VIEW_COUNT)
		{
			$updlist = '';
			foreach ($SeenTopics as $topic_id=>$tcount)
			{
				$updlist .= (empty($updlist)) ? $topic_id : ',' . $topic_id;
				if (($board_config['disable_topic_view'] == 0) && ($forum_topic_data['forum_topic_views'] == 1))
				{
					$sql = 'UPDATE ' . TOPIC_VIEW_TABLE . ' SET topic_id = "' . $topic_id . '", view_time = "' . time() . '", view_count = view_count + 1 WHERE topic_id = ' . $topic_id . ' AND user_id = ' . $user_id;
					if(!$db->sql_query($sql) || !$db->sql_affectedrows())
					{
						$sql = 'INSERT IGNORE INTO ' . TOPIC_VIEW_TABLE . ' (topic_id, user_id, view_time, view_count)
						VALUES (' . $topic_id . ', "' . $user_id . '", "' . time() . '", "1")';
						if(!($db->sql_query($sql)))
						{
							ExitWithHeader('500 Internal Server Error', 'Error create user view topic information');
						}
					}
				}
			}
			if($updlist != '')
			{
				// Update the topic view counter
				$sql = "UPDATE " . TOPICS_TABLE . "
				SET topic_views = topic_views + 1
				WHERE topic_id IN ($updlist)";
				if(!$db->sql_query($sql))
				{
					ExitWithHeader('500 Internal Server Error', 'Could not update topic views');
				}
			}
		}
		if(LV_MOD_INSTALLED and $user_id!=ANONYMOUS)
		{
			$sql = "UPDATE " . USERS_TABLE . "
			SET user_totalpages = user_totalpages + $PostCount
			WHERE user_id = $user_id";
			if(!$db->sql_query($sql))
			{
				ExitWithHeader('500 Internal Server Error', 'Error updating user totalpages ');
			}
		}
	}
	// Check for E-Tag
	if($LastPostTime == 0)
	{
		$LastPostTime = $deadline;
	}
	$MyETag = '"RSS' . gmdate('YmdHis', $LastPostTime) . $verinfo . '"';
	$MyGMTtime = gmdate('D, d M Y H:i:s', $LastPostTime) . ' GMT';
	if(isset($_SERVER['HTTP_IF_NONE_MATCH'])&& ($_SERVER['HTTP_IF_NONE_MATCH']== $MyETag))
	{
		ExitWithHeader('304 Not Modified');
	}
	if(isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && ($_SERVER['HTTP_IF_MODIFIED_SINCE'] == $MyGMTtime))
	{
		ExitWithHeader('304 Not Modified');
	}

	// BEGIN XML and nocaching headers (copied from page_header.php)
	if(!empty($_SERVER['SERVER_SOFTWARE']) && strstr($_SERVER['SERVER_SOFTWARE'], 'Apache/2'))
	{
		header('Cache-Control: no-cache, pre-check=0, post-check=0, max-age=0');
	}
	else
	{
		header('Cache-Control: private, pre-check=0, post-check=0, max-age=0');
	}
	header('Last-Modified: ' . $MyGMTtime);
	header('Etag: ' . $MyETag);
	header('Expires: ' . gmdate('D, d M Y H:i:s', time()) . ' GMT');
	header('Content-Type: text/xml; charset=' . $encoding_charset);
	// End XML and nocaching headers

	// BEGIN Output XML page
	// BEGIN Cache Mod
	if(($user_id == ANONYMOUS) && CACHE_TO_FILE && ($cache_root!='') && empty($_GET) && !isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && !(AUTOSTYLED && strpos($useragent,'MSIE')))
	{
		ob_start();
		$template->pparse('body');
		$out = ob_get_contents();
		ob_end_flush();
		if($f = @fopen($cache_file, 'w'))
		{
			@fwrite($f, $out, strlen($out));
			@fclose($f);
		}
	}
	else
	{
		$template->pparse('body');
	}
}
// END Cache Mod
// And remove temporary session from database
if(defined(TEMP_SESSION))
{
	rss_session_end;
}

$gzip_text = ($board_config['gzip_compress']) ? 'GZIP enabled' : 'GZIP disabled';
$mtime = microtime();
$mtime = explode(" ", $mtime);
$mtime = $mtime[1] + $mtime[0];
$endtime = $mtime;
$gentime = round(($endtime - $starttime), 4);
if($show_time)
{
	echo '<!-- Page generation time: '.$gentime .'s ';
	$sql_time = round($db->sql_time, 4);
	$sql_part = round($sql_time / $gentime * 100);
	$excuted_queries = $db->num_queries;
	$php_part = 100 - $sql_part;
	echo '(PHP: ' . $php_part . '% - SQL: ' . $sql_part . '%) - SQL queries: ' . $excuted_queries;

	if(function_exists('memory_get_usage') && ($mem = @memory_get_usage()))
	{
		echo ' - Memory Usage: ' . (number_format(($mem / (1024 * 1024)), 3)) . ' Mb ';
	}
	echo ' - ' . $gzip_text . ' -->';
}
// END Output XML page

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
	echo pack('V', $gzip_crc);
	echo pack('V', $gzip_size);
}
exit;

?>