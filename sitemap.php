<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

// CTracker_Ignore: File Checked By Human
define('IMG_THUMB', true);
define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_admin.' . PHP_EXT);

@set_time_limit(0);
$mem_limit = check_mem_limit();
@ini_set('memory_limit', $mem_limit);
@ini_set('max_execution_time', '3600');

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

$prog_name = 'Sitemap IP 1.0.0';
$verinfo = 'V100';

$cache_data_file = MAIN_CACHE_FOLDER . 'sitemap.xml';
$cache_update = true;
$cache_file_time = time();
if (@is_file($cache_data_file))
{
	//$valid = (date('YzH', time()) - date('YzH', @filemtime($cache_data_file)) < 1) ? true : false;
	$cache_file_time = @filemtime($cache_data_file);
	if ( ((date('YzH', time()) - date('YzH', $cache_file_time)) < 1) && ((date('Y', time()) == date('Y', $cache_file_time))) )
	{
		$cache_update = false;
	}
}

if( strpos($useragent, 'MSIE') )
{
	$encoding_charset = $lang['ENCODING'];
}
else
{
	$encoding_charset = $lang['ENCODING_ALT'];
}

// GZip - BEGIN
$do_gzip_compress = true;
/**/
$useragent = (isset($_SERVER['HTTP_USER_AGENT'])) ? $_SERVER['HTTP_USER_AGENT'] : getenv('HTTP_USER_AGENT');
if(strpos($useragent,'MSIE'))
{
	$use_cached = false;
}

if( $board_config['gzip_compress'] )
{
	$phpver = phpversion();
	if( $phpver >= '4.0.4pl1' && ( strstr($useragent,'compatible') || strstr($useragent,'Gecko') ) )
	{
		if( extension_loaded('zlib') )
		{
			ob_start('ob_gzhandler');
		}
	}
	elseif( $phpver > '4.0' )
	{
		if( strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') )
		{
			if( extension_loaded('zlib') )
			{
				$do_gzip_compress = true;
				ob_start();
				ob_implicit_flush(0);
				header('Content-Encoding: gzip');
			}
		}
	}
}
/**/
// GZip - END

if (!$cache_update)
{
	$MyETag = '"Sitemap' . gmdate('YmdHis', $cache_file_time) . $verinfo . '"';
	$MyGMTtime = gmdate('D, d M Y H:i:s', $cache_file_time) . ' GMT';
	if(!empty($_SERVER['SERVER_SOFTWARE']) && strstr($_SERVER['SERVER_SOFTWARE'], 'Apache/2'))
	{
		header ('Cache-Control: no-cache, pre-check=0, post-check=0, max-age=0');
	}
	else
	{
		header ('Cache-Control: private, pre-check=0, post-check=0, max-age=0');
	}
	header('Last-Modified: ' . $MyGMTtime);
	header('Etag: ' . $MyETag);
	header('Expires: ' . gmdate('D, d M Y H:i:s', time()) . ' GMT');
	header ('Content-Type: text/xml; charset=' . $encoding_charset);
	readfile($cache_data_file);
}
else
{
	$server_url = create_server_url();

	// Google only
	// OLD
	//$xml_urlset = '<urlset xmlns="http://www.google.com/schemas/sitemap/0.84">';
	// NEW
	//$xml_urlset = '<urlset xmlns="http://www.google.com/schemas/sitemap/0.84" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.google.com/schemas/sitemap/0.84 http://www.google.com/schemas/sitemap/0.84/sitemap.xsd">';

	// GYM
	$xml_urlset = '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

//' . "\n" . '
	$xml_sitemap_header = '<' . '?xml version="1.0" encoding="UTF-8"?' . '>
' . $xml_urlset . '
	<url>
		<loc>' . $server_url . '</loc>
		<changefreq>always</changefreq>
		<priority>1.0</priority>
	</url>';
	$xml_sitemap_body = '';
	$xml_sitemap_footer = '
</urlset>';

	// MG SITEMAP - FORUM - BEGIN
	//Get a list of publicly viewable forums
	$sql = "SELECT forum_id FROM " . FORUMS_TABLE . " WHERE auth_read = 0";
	//if ( !($result = $db->sql_query($sql)) )
	if ( !($result = $db->sql_query($sql, false, 'sitemap_forums_')) )
	{
		message_die(GENERAL_ERROR, 'Error getting permissions', '', __LINE__, __FILE__, $sql);
	}
	while ( $row = $db->sql_fetchrow($result) )
	{
		$forumids .= $row['forum_id'] . ',';
	}
	$forumids = substr($forumids, 0, strlen($forumids) - 1);

	if($board_config['sitemap_sort'] == 'ASC')
	{
		$order = 'DESC';
	}
	else
	{
		$order = 'ASC';
	}
	$sql = "SELECT topic_id FROM " . TOPICS_TABLE . " WHERE forum_id IN (" . $forumids . ") ORDER BY topic_id $order LIMIT 1";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Error getting topic information', '', __LINE__, __FILE__, $sql);
	}
	$result = $db->sql_fetchrow($result);
	$lastid = $result['topic_id'];

	//only get a limited number of topics per query (default 250) to keep server load down in case of large boards
	while($lasttopic != $lastid)
	{
		$result = '';
		//Newest topics first
		if(is_numeric($lasttopic) && $board_config['sitemap_sort'] == 'ASC')
		{
			$lasttopic++;
			$wheresql = "AND t.topic_id >= $lasttopic";
		}
		//Oldest topics first
		elseif(is_numeric($lasttopic))
		{
			$lasttopic--;
			$wheresql = "AND t.topic_id <= $lasttopic";
		}
		else
		{
			$wheresql = "";
		}
		$sql = "SELECT t.forum_id, t.topic_id, t.topic_title, t.topic_type, t.topic_status, t.news_id, p.post_time
						FROM " . TOPICS_TABLE . " AS t, " . POSTS_TABLE . " AS p
						WHERE t.topic_last_post_id=p.post_id
						AND t.forum_id IN (" . $forumids . ") $wheresql
						ORDER BY t.topic_id " . $board_config['sitemap_sort'] . "
						LIMIT " . $board_config['sitemap_topic_limit'];
		if ( !($result = $db->sql_query($sql, false, 'sitemap_topics_')) )
		{
			message_die(GENERAL_ERROR, 'Error obtaining topic data', '', __LINE__, __FILE__, $sql);
		}

		while ( $row = $db->sql_fetchrow($result) )
		{
			$topics = $row;
			$row['topic_type'] = ($row['news_id'] > 0) ? '2' : $row['topic_type'];
			switch ($row['topic_type'])
			{
				case 2:
					$topic_priority = $board_config['sitemap_announce_priority'];
				break;
				case 1:
					$topic_priority = $board_config['sitemap_sticky_priority'];
				break;
				default:
					$topic_priority = $board_config['sitemap_default_priority'];
			}
			if ($row['topic_status'] == 1)
			{
				$topic_change = 'never';
			}
			else
			{
				$topic_change = 'always';
			}
			if ( ($board_config['url_rw'] == '1') || ( ($board_config['url_rw_guests'] == '1') && ($userdata['user_id'] == ANONYMOUS) ) )
			{
				$url = $server_url . str_replace ('--', '-', make_url_friendly($row['topic_title']) . '-vt' . $row['topic_id'] . '.html');
			}
			else
			{
				$url = $server_url . VIEWTOPIC_MG . '?' . POST_FORUM_URL . '=' . $row['forum_id'] . '&amp;' . POST_TOPIC_URL . '=' . $row['topic_id'];
			}

			$xml_sitemap_body .= '
	<url>
		<loc>' . $url . '</loc>
		<lastmod>' . gmdate('Y-m-d\TH:i:s' . '+00:00', $row['post_time']) . '</lastmod>
		<changefreq>' . $topic_change . '</changefreq>
		<priority>' . $topic_priority . '</priority>
	</url>';
			$lasttopic = $row['topic_id'];
		}
		$db->sql_freeresult();
	}
	// MG SITEMAP - FORUM - END

	// MG SITEMAP - DOWNLOADS - BEGIN
		include(IP_ROOT_PATH . PA_FILE_DB_PATH . 'pafiledb_constants.' . PHP_EXT);
		$sql = "SELECT * FROM " . PA_FILES_TABLE . "
						WHERE file_approved = '1'
							ORDER BY file_time DESC";
		if ( !($result = $db->sql_query($sql, false, 'sitemap_files_')) )
		{
			message_die(GENERAL_ERROR, 'Could not query downloads');
		}

		$dl_priority = $board_config['sitemap_announce_priority'];
		$dl_change = 'never';
		while ($dl_sitemap = $db->sql_fetchrow($result))
		{
			/*
			$dl_sitemap['file_name'];
			$dl_sitemap['file_desc'];
			*/
			$xml_sitemap_body .= '
	<url>
		<loc>' . $server_url . 'dload.' . PHP_EXT . '?action=file&amp;file_id=' . $dl_sitemap['file_id'] . '</loc>
		<lastmod>' . gmdate('Y-m-d\TH:i:s' . '+00:00', $dl_sitemap['file_time']) . '</lastmod>
		<changefreq>' . $dl_change . '</changefreq>
		<priority>' . $dl_priority . '</priority>
	</url>';
		}
		$db->sql_freeresult();
	// MG SITEMAP - DOWNLOADS - END

	// MG SITEMAP - ALBUM - BEGIN
	// Get general album information
	include(ALBUM_MOD_PATH . 'album_common.' . PHP_EXT);
	$album_user_id = ALBUM_PUBLIC_GALLERY;
	//$album_user_id = ALBUM_ROOT_CATEGORY;
	$catrows = array ();
	$options = ALBUM_READ_ALL_CATEGORIES|ALBUM_AUTH_VIEW;
	$catrows = album_read_tree($album_user_id, $options);
	album_read_tree($album_user_id);
	$allowed_cat = ''; // For Recent Public Pics below
	for ($i = 0; $i < count($catrows); $i ++)
	{
		$allowed_cat .= ($allowed_cat == '') ? $catrows[$i]['cat_id'] : ',' . $catrows[$i]['cat_id'];
	}

	if($board_config['sitemap_sort'] == 'ASC')
	{
		$order = 'DESC';
	}
	else
	{
		$order = 'ASC';
	}
	$sql = "SELECT pic_id FROM " . ALBUM_TABLE . "
					WHERE pic_cat_id IN (" . $allowed_cat . ")
					ORDER BY pic_id $order LIMIT 1";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Error getting pic information', '', __LINE__, __FILE__, $sql);
	}
	$result = $db->sql_fetchrow($result);
	$lastid = $result['pic_id'];

	//only get a limited number of pics per query (default 250) to keep server load down in case of large boards
	while($lastpic != $lastid)
	{
		$result = '';
		//Newest pics first
		if(is_numeric($lastpic) && $board_config['sitemap_sort'] == 'ASC')
		{
			$lastpic++;
			$wheresql = "AND p.pic_id >= $lastpic";
		}
		//Oldest pics first
		elseif(is_numeric($lastpic))
		{
			$lastpic--;
			$wheresql = "AND p.pic_id <= $lastpic";
		}
		else
		{
			$wheresql = "";
		}

		$sql = "SELECT p.pic_id, p.pic_title, p.pic_desc, p.pic_user_id, p.pic_time, p.pic_lock
						FROM " . ALBUM_TABLE . " AS p
						WHERE p.pic_cat_id IN (" . $allowed_cat . ") $wheresql
						ORDER BY p.pic_id " . $board_config['sitemap_sort'] . "
						LIMIT " . $board_config['sitemap_topic_limit'];
		if ( !($result = $db->sql_query($sql, false, 'sitemap_pics_')) )
		{
			message_die(GENERAL_ERROR, 'Error obtaining topic data', '', __LINE__, __FILE__, $sql);
		}

		while ( $row = $db->sql_fetchrow($result) )
		{
			$pic_priority = $board_config['sitemap_default_priority'];
			$pic_change = 'never';
			$xml_sitemap_body .= '
	<url>
		<loc>' . $server_url . 'album_showpage.' . PHP_EXT . '?pic_id=' . $row['pic_id'] . '</loc>
		<lastmod>' . gmdate('Y-m-d\TH:i:s' . '+00:00', $row['pic_time']) . '</lastmod>
		<changefreq>' . $pic_change . '</changefreq>
		<priority>' . $pic_priority . '</priority>
	</url>';
			$lastpic = $row['pic_id'];
		}
		$db->sql_freeresult();
	}
	// MG SITEMAP - ALBUM - END

	$xml_content = $xml_sitemap_header . $xml_sitemap_body . $xml_sitemap_footer;
	// GZip - BEGIN
	/*
	ob_start();
	$gz_out = ob_get_contents();
	ob_end_flush();
	if($fp = @fopen($cache_data_file, 'w'))
	{
		@fwrite ($fp, $out,strlen($gz_out));
		@fclose($fp);
	}
	*/
	// GZip - END
	$fp = fopen($cache_data_file, 'w');
	@fwrite($fp, $xml_content);
	@fclose($fp);

	//Compresss the sitemap with gzip
	//this isn't as pretty as the code in page_header.php, but it's simple & it works :)
	if( function_exists(ob_gzhandler) && ($board_config['gzip_compress'] == 1) )
	{
		//ob_start(ob_gzhandler);
	}
	$MyETag = '"Sitemap' . gmdate('YmdHis', $cache_file_time) . $verinfo . '"';
	$MyGMTtime = gmdate('D, d M Y H:i:s', $cache_file_time) . ' GMT';
	if(!empty($_SERVER['SERVER_SOFTWARE']) && strstr($_SERVER['SERVER_SOFTWARE'], 'Apache/2'))
	{
		header ('Cache-Control: no-cache, pre-check=0, post-check=0, max-age=0');
	}
	else
	{
		header ('Cache-Control: private, pre-check=0, post-check=0, max-age=0');
	}
	header('Last-Modified: ' . $MyGMTtime);
	header('Etag: ' . $MyETag);
	header('Expires: ' . gmdate('D, d M Y H:i:s', time()) . ' GMT');
	header ('Content-Type: text/xml; charset=' . $encoding_charset);
	echo($xml_content);
}

// GZip - BEGIN
/*
if( $do_gzip_compress )
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
*/
// GZip - END

exit;

?>