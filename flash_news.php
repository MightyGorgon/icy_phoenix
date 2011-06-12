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

// Options - BEGIN
// Number of items
$news_items = 10;
// Items type: 'news' or 'topics'
$news_type = 'news';
// Recent: true or false (if set to false then random items will be selected)
$news_recent = true;
// Cache: select true only if you want to use cached version of selected items
$news_cache = false;
// Cache file
$news_cache_file = MAIN_CACHE_FOLDER . 'flash_news_data.xml';
// Cache frequency
$news_cache_freq = 86400;
// Base address
$news_base_address = create_server_url();
// Viewtopic address
$news_base_url = CMS_PAGE_VIEWTOPIC;
// Options - END

/*
* Build_allowed_forums_list: needed to build a list of forum with read access
*/
function flash_build_allowed_forums_list()
{
	$allowed_forums = '';
	$forum_types = array(FORUM_POST);
	$forums_array = get_forums_ids($forum_types, true, false, true, true);
	foreach ($forums_array as $forum)
	{
		$allowed_forums .= (empty($allowed_forums) ? '' : ',') . $forum['forum_id'];
	}

	return $allowed_forums;
}

/*
* Output xml header
*/
function xml_header($time)
{
	if(!empty($_SERVER['SERVER_SOFTWARE']) && strstr($_SERVER['SERVER_SOFTWARE'], 'Apache/2'))
	{
		header('Cache-Control: no-cache, pre-check=0, post-check=0, max-age=0');
	}
	else
	{
		header('Cache-Control: private, pre-check=0, post-check=0, max-age=0');
	}
	header('Last-Modified: ' . $time);
	header('Expires: ' . gmdate('D, d M Y H:i:s', time()) . ' GMT');
	header('Content-Type: text/xml; charset=UTF-8');
}

/*
* Output xml header
*/
function xml_file_content($file_content)
{
	$xml_content = '';
	$xml_content .= '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
	$xml_content .= '<news>' . "\n";
	$xml_content .= $file_content . "\n";
	$xml_content .= '</news>' . "\n";
	return $xml_content;
}

// Cache - BEGIN
$use_cache = false;
if($news_cache && @file_exists($news_cache_file))
{
	$cache_file_time = @filemtime($news_cache_file);
	$cache_refresh_time = ($cache_file_time + $news_cache_freq);
	if($cache_refresh_time > time())
	{
		$use_cache = true;
	}
}
// Cache - END

if($use_cache)
{
	$time = gmdate('D, d M Y H:i:s', $cache_file_time) . ' GMT';
	xml_header($time);
	readfile($news_cache_file);
}
else
{
	$allowed_forums = flash_build_allowed_forums_list();

	if ($news_recent)
	{
		$sql_sort = "p.post_time DESC";
	}
	else
	{
		$sql_sort = "rand()";
	}

	$sql_news = '';
	if ($news_type == 'news')
	{
		$sql_news = "AND t.news_id > 0";
	}

	$sql = "SELECT t.topic_id, t.topic_title, t.topic_first_post_id, u.user_id, u.username, p.post_time
		FROM " . TOPICS_TABLE . " AS t, " . USERS_TABLE . " AS u, " . POSTS_TABLE . " AS p
		WHERE t.forum_id IN (" . $allowed_forums . ")
				AND u.user_id = t.topic_poster
				AND p.post_id = t.topic_first_post_id
				AND p.post_approval = " . POST_APPROVED . "
				" . $sql_news . "
		ORDER BY " . $sql_sort . "
		LIMIT " . $news_items;
	$result = ($news_recent ? $db->sql_query($sql, 0, 'posts_flash_', POSTS_CACHE_FOLDER) : $db->sql_query($sql));

	$xml_content = '';
	while($row = $db->sql_fetchrow($result))
	{
		$topic_title = strip_tags($row['topic_title']);
		$topic_author = $row['username'];
		$topic_user_id = $row['user_id'];
		$news_url = $news_base_address . $news_base_url . '?' . POST_TOPIC_URL . '=' . $row['topic_id'];
		$xml_content .= '<item url="' . $news_url . '">' . $topic_title . '</item>' . "\n";
	}

	$time = gmdate('D, d M Y H:i:s', time()) . ' GMT';
	$xml_content = xml_file_content($xml_content);
	if($news_cache)
	{
		if($f = @fopen($news_cache_file, 'w'))
		{
			@fwrite($f, $xml_content, strlen($xml_content));
			@fclose($f);
		}
	}

	xml_header($time);
	echo($xml_content);
}

$db->sql_close();
exit;

?>