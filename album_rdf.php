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
* DaMysterious (http://damysterious.xs4all.nl/)
* OryNider
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

// Get general album information
include(ALBUM_MOD_PATH . 'album_common.' . PHP_EXT);

// XML and nocaching headers
// header ('Cache-Control: private, pre-check=0, post-check=0, max-age=0');
header ('Expires: ' . gmdate('D, d M Y H:i:s', time()) . ' GMT');
header ('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header ('Content-Type: text/xml');

// Create main board url
$fap_full_url = fap_create_server_url();

$index_site = $fap_full_url . 'album.' . PHP_EXT;
$index_url = $fap_full_url . 'album_showpage.' . PHP_EXT;
$thumb_url = $fap_full_url . 'album_thumbnail.' . PHP_EXT;

// If not set, set the output count to 50
$count = ( isset($_GET['np']) ) ? intval($_GET['np']) : 25;
$count = ( $count == 0 ) ? 25 : $count;

// BEGIN Recent Photos
// Start check permissions
$sql_allowed_cat = '';
$check_sel = ($admin_mode) ? 0 : 1;
if($userdata['user_level'] != ADMIN)
{
	$album_user_access = personal_gallery_access(true, false);
	$not_allowed_cat = ($album_user_access['view'] == 1 ) ? '' : '0';
	$sql = "SELECT c.*
		FROM ". ALBUM_CAT_TABLE ." AS c
		WHERE cat_id <> 0";
	if( !($result = $db->sql_query($sql)) )
	{
		die("Could not query categories list");
	}
	while( $row = $db->sql_fetchrow($result) )
	{
		$album_user_access = album_user_access($row['cat_id'], $row, 1, 0, 0, 0, 0, 0); // VIEW
		if($admin_mode)
		{
			if ( ($album_user_access['moderator'] != 1) || ($row['cat_approval'] != MOD) )
			{
				$not_allowed_cat .= ($not_allowed_cat == '') ? $row['cat_id'] : ',' . $row['cat_id'];
			}
		}
		else
		{
			if ($album_user_access['view'] != 1)
			{
				$not_allowed_cat .= ($not_allowed_cat == '') ? $row['cat_id'] : ',' . $row['cat_id'];
			}
		}
	}
	$sql_not_allowed_cat = (empty($not_allowed_cat)) ? '' : "AND pic_cat_id NOT IN ($not_allowed_cat)";
}
// End check permissions
$NotErrorFlag = false;
$sql_limit_time = "";
if ( !$no_limit && isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) )
{
	$NotErrorFlag = true;
	$NotModifiedSince = strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']);
	if($NotModifiedSince > 0)
	{
		$sql_limit_time = "AND pic_time > " . $NotModifiedSince;
		$sql_limit_comment_time = "AND comment_time > " . $NotModifiedSince;
	}
}

$sql = "SELECT pic_id, pic_title, pic_time, pic_desc, pic_username, pic_cat_id, pic_approval, cat_title
	FROM " . ALBUM_TABLE . " LEFT JOIN " . ALBUM_CAT_TABLE . " ON (cat_id = pic_cat_id)
	WHERE pic_approval = $check_sel
	$sql_not_allowed_cat $sql_cat_where $sql_limit_time
	ORDER BY pic_time DESC
	LIMIT $count";

$picrow = $db->sql_query($sql);

if ( !$picrow )
{
	die("Failed obtaining list of active pictures");
}
else
{
	$topics = $db->sql_fetchrowset($picrow);
}
$LastPostTime = 0;

$rdf = '';

if ( count($topics) == 0 )
{
	die('No pictures found');
}
else
{
	// $topics contains all interesting data
	for ($i = 0; $i < count($topics); $i++)
	{
		$title = $topics[$i]['pic_title'];
		$title = str_replace('&', '&amp;', $title);
		$url = $index_url . '?' . 'pic_id=' . $topics[$i]['pic_id'] . $picrow[$i]['pic_id'];
		$thumb = $thumb_url . '?' . 'pic_id=' . $topics[$i]['pic_id'] . $picrow[$i]['pic_id'];
		$description = '<a href="' . $url . '"><img src="' . $thumb . '" alt="" /></a>';
		$description = htmlentities($description);
		$description .= $lang['Pic_Desc'] . ': ' . nl2br($topics[$i]['pic_desc']);
		$pic_time = date('D, j M Y G:i:s T', $topics[$i]['pic_time']);
		$rdf .= '<item>
			<title>' . $title . '</title>
			<description>' . $description . '</description>
			<link>' . $url . '</link>
		</item>';
	}
}

$board_config['sitename'] = str_replace('&', '&amp;', $board_config['sitename']);
$board_config['site_desc'] = str_replace('&', '&amp;', $board_config['site_desc']);

// Create RSS header
$rdf_header = '<?xml version="1.0" encoding="ISO-8859-2" ?>
<rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns="http://my.netscape.com/rdf/simple/0.9/">
<channel>
	<title>' . $board_config['sitename'] . ' Album (XXX needs registering)</title>
	<link>' . $index_url . '</link>
	<description>' . $board_config['site_desc'] . '</description>
	<language>en-us</language>
	<generator>FAP</generator>
</channel>';

// Create RDF footer
$rdf_footer = '</rdf:RDF>';

$rdf = $rdf_header . $rdf . $rdf_footer;

// Discritics Replace
$rdf = str_replace("&auml;", "ä", $rdf);
$rdf = str_replace("&ouml;", "ö", $rdf);
$rdf = str_replace("&uuml;", "ü", $rdf);

// Output the RDF
echo $rdf;

?>