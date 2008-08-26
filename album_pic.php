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
* Smartor (smartor_xp@hotmail.com)
*
*/

define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.' . $phpEx);

// Start session management
$userdata = defined('IS_ICYPHOENIX') ? session_pagestart($user_ip, false) : session_pagestart($user_ip, PAGE_ALBUM);
init_userprefs($userdata);
// End session management

// Get general album information
$album_root_path = $phpbb_root_path . ALBUM_MOD_PATH;
include($album_root_path . 'album_common.' . $phpEx);
require($album_root_path . 'album_image_class.' . $phpEx);

// ------------------------------------
// Check the request
// ------------------------------------
if( isset($_GET['pic_id']) )
{
	$pic_id = intval($_GET['pic_id']);
}
elseif( isset($_POST['pic_id']) )
{
	$pic_id = intval($_POST['pic_id']);
}
else
{
	message_die(GENERAL_MESSAGE, 'No pics specified');
	//die('No pics specified');
}

// ------------------------------------
// Get this pic info and current Category Info
// ------------------------------------
$sql = "SELECT p.*, c.*
		FROM ". ALBUM_TABLE ." AS p, ". ALBUM_CAT_TABLE ." AS c
		WHERE p.pic_id = '$pic_id'
			AND c.cat_id = p.pic_cat_id";

if( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not query pic information', '', __LINE__, __FILE__, $sql);
}

$thispic = $db->sql_fetchrow($result);
$db->sql_freeresult($result);

$cat_id = $thispic['pic_cat_id'];
$album_user_id = $thispic['cat_user_id'];

$pic_filename = $thispic['pic_filename'];
$file_part = explode('.', strtolower($pic_filename));
$pic_filetype = $file_part[count($file_part) - 1];
$pic_title = substr($pic_filename, 0, strlen($pic_filename) - strlen($pic_filetype) - 1);
$pic_fullpath = ALBUM_UPLOAD_PATH . $pic_filename;
$pic_wm_fullpath = ALBUM_WM_CACHE_PATH . 'full_' . $pic_filename;
$pic_title = $thispic['pic_title'];
$pic_title_reg = ereg_replace("[^A-Za-z0-9]", "_", $pic_title);
$apply_wm = false;
$wm_file = ALBUM_WM_FILE;

if( ($album_config['use_watermark'] == true) && ($userdata['user_level'] != ADMIN) && ( (!$userdata['session_logged_in']) || ($album_config['wut_users'] == 1) ) )
{
	$apply_wm = true;
	$wm_file = (file_exists($thispic['cat_wm']) ? $thispic['cat_wm'] : ALBUM_WM_FILE);
}

if( empty($thispic) || !file_exists($pic_fullpath) )
{
	message_die(GENERAL_MESSAGE, $lang['Pic_not_exist']);
}

// ------------------------------------
// Check the permissions
// ------------------------------------
$album_user_access = album_permissions($album_user_id, $cat_id, ALBUM_AUTH_VIEW, $thispic);
if ( $album_user_access['view'] == false )
{
	message_die(GENERAL_MESSAGE, $lang['Not_Authorised']);
}

// ------------------------------------
// Check Pic Approval
// ------------------------------------
if ( $userdata['user_level'] != ADMIN )
{
	if ( ($thispic['cat_approval'] == ADMIN) || (($thispic['cat_approval'] == MOD) && !$album_user_access['moderator']) )
	{
		if ( $thispic['pic_approval'] != 1 )
		{
			message_die(GENERAL_MESSAGE, $lang['Not_Authorised']);
		}
	}
}

// ------------------------------------
// Check hotlink
// ------------------------------------
if ( ($album_config['hotlink_prevent'] == true) && (isset($_SERVER['HTTP_REFERER'])) && ($album_config['hotlink_allowed'] != '') )
{
	$check_referer = explode('?', $_SERVER['HTTP_REFERER']);
	$check_referer = trim($check_referer[0]);

	$good_referers = array();

	if ($album_config['hotlink_allowed'] != '')
	{
		$good_referers = explode(',', $album_config['hotlink_allowed']);
	}

	$good_referers[] = $board_config['server_name'] . $board_config['script_path'];

	$errored = true;

	for ($i = 0; $i < count($good_referers); $i++)
	{
		$good_referers[$i] = trim($good_referers[$i]);

		if ( (strstr($check_referer, $good_referers[$i])) && ($good_referers[$i] != '') )
		{
			$errored = false;
		}
	}

	if ( $errored )
	{
		message_die(GENERAL_MESSAGE, $lang['Not_Authorised']);
		/*
		header('Content-type: image/jpeg');
		header('Content-Disposition: filename=' . $pic_title_reg . '.' . $pic_filetype);
		readfile($images['no_thumbnail']);
		exit;
		*/
	}
}

/*
+----------------------------------------------------------
| Main work here...
+----------------------------------------------------------
*/

// ------------------------------------
// Increase view counter
// ------------------------------------
$sql = "UPDATE ". ALBUM_TABLE ."
			SET pic_view_count = pic_view_count + 1
			WHERE pic_id = '$pic_id'";
if( !$result = $db->sql_query($sql) )
{
	message_die(GENERAL_ERROR, 'Could not update pic information', '', __LINE__, __FILE__, $sql);
}

// ------------------------------------
// Okay, now we can send image to the browser
// ------------------------------------
switch ($pic_filetype)
{
	case 'gif':
		$file_header = 'Content-type: image/gif';
		break;
	case 'jpg':
		$file_header = 'Content-type: image/jpeg';
		break;
	case 'png':
		$file_header = 'Content-type: image/png';
		break;
	default:
		header('Content-type: image/jpeg');
		header('Content-Disposition: filename=' . $pic_title_reg . '.' . $pic_filetype);
		readfile($images['no_thumbnail']);
		exit;
		break;
}

if ( (($pic_filetype == 'jpg') || ($pic_filetype == 'png')) && ($apply_wm == false) )
{
	header($file_header);
	header('Content-Disposition: filename=' . $pic_title_reg . '.' . $pic_filetype);
	readfile($pic_fullpath);
	exit;
}

if ( $pic_filetype == 'gif' )
{
	header($file_header);
	header('Content-Disposition: filename=' . $pic_title_reg . '.' . $pic_filetype);
	readfile($pic_fullpath);
	exit;
}

if( ($apply_wm == true) && file_exists($pic_wm_fullpath) )
{
	header($file_header);
	header('Content-Disposition: filename=' . $pic_title_reg . '.' . $pic_filetype);
	readfile($pic_wm_fullpath);
	exit;
}

// Old Thumbnails - BEGIN
// Old thumbnail generation functions, for GD1 and some strange servers...
if ( ($album_config['gd_version'] == 1) || ($album_config['use_old_pics_gen'] == 1) )
{
	// MG Watermark - BEGIN
	if ( $apply_wm == true )
	{
		$wm_position = ( ($album_config['disp_watermark_at'] > 0) && ($album_config['disp_watermark_at'] < 10) ) ? $album_config['disp_watermark_at'] : 5;
		$wm_transition = 50;
		mergePics($pic_fullpath, $wm_file, $wm_position, $wm_transition, $pic_filetype);
	}
	else
	{
		readfile($pic_fullpath);
	}
	// MG Watermark - END
	exit;
}
// Old Thumbnails - END

$Image = new ImgObj();

if ($pic_filetype == 'jpg')
{
	$Image->ReadSourceFileJPG($pic_fullpath);
}
else
{
	$Image->ReadSourceFile($pic_fullpath);
}

if ( $apply_wm == true )
{
	$wm_position = ( ($album_config['disp_watermark_at'] > 0) && ($album_config['disp_watermark_at'] < 10) ) ? $album_config['disp_watermark_at'] : 5;
	$wm_maxsize = 50;
	$wm_transition = 75;
	$Image->WatermarkPos($wm_file, $wm_position, $wm_maxsize, $wm_transition);
	$Image->SendToFile($pic_wm_fullpath, $album_config['thumbnail_quality']);
	@chmod($pic_wm_fullpath, 0777);
}

if ($pic_filetype == 'jpg')
{
	$Image->SendToBrowserJPG($pic_title_reg, $pic_filetype, '', '', $album_config['thumbnail_quality']);
}
else
{
	$Image->SendToBrowser($pic_title_reg, $pic_filetype, '', '', $album_config['thumbnail_quality']);
}

$Image->Destroy();
exit;

?>