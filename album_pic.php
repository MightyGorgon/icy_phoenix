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

define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup();
// End session management

// Get general album information
include(ALBUM_MOD_PATH . 'album_common.' . PHP_EXT);
require(IP_ROOT_PATH . 'includes/class_image.' . PHP_EXT);

// ------------------------------------
// Check the request
// ------------------------------------
$pic_id = request_var('pic_id', 0);
if ($pic_id <= 0)
{
	die($lang['NO_PICS_SPECIFIED']);
	//message_die(GENERAL_MESSAGE, $lang['NO_PICS_SPECIFIED']);
}

// ------------------------------------
// Get this pic info and current Category Info
// ------------------------------------
$sql = "SELECT p.*, c.*
		FROM " . ALBUM_TABLE . " AS p, " . ALBUM_CAT_TABLE . " AS c
		WHERE p.pic_id = '" . $pic_id . "'
			AND c.cat_id = p.pic_cat_id
		LIMIT 1";
$result = $db->sql_query($sql);
$thispic = $db->sql_fetchrow($result);
$db->sql_freeresult($result);

$cat_id = $thispic['pic_cat_id'];
$album_user_id = $thispic['cat_user_id'];

$pic_info = array();
$pic_info = pic_info($thispic['pic_filename'], $thispic['pic_thumbnail'], $thispic['pic_title']);

$apply_wm = false;
$wm_file = (file_exists($thispic['cat_wm']) ? $thispic['cat_wm'] : ALBUM_WM_FILE);

if(($album_config['use_watermark'] == true) && ($user->data['user_level'] != ADMIN) && ((!$user->data['session_logged_in']) || ($album_config['wut_users'] == 1)))
{
	$apply_wm = true;
}

if(empty($thispic) || ($pic_info['exists'] == false) || !file_exists($pic_info['fullpath']))
{
	message_die(GENERAL_MESSAGE, $lang['Pic_not_exist']);
}

// ------------------------------------
// Check the permissions
// ------------------------------------
$album_user_access = album_permissions($album_user_id, $cat_id, ALBUM_AUTH_VIEW, $thispic);
if ($album_user_access['view'] == false)
{
	message_die(GENERAL_MESSAGE, $lang['Not_Authorized']);
}

// ------------------------------------
// Check Pic Approval
// ------------------------------------
if ($user->data['user_level'] != ADMIN)
{
	if (($thispic['cat_approval'] == ADMIN) || (($thispic['cat_approval'] == MOD) && !$album_user_access['moderator']))
	{
		if ($thispic['pic_approval'] != 1)
		{
			message_die(GENERAL_MESSAGE, $lang['Not_Authorized']);
		}
	}
}

// ------------------------------------
// Check hotlink
// ------------------------------------
if (($album_config['hotlink_prevent'] == true) && (isset($_SERVER['HTTP_REFERER'])) && ($album_config['hotlink_allowed'] != ''))
{
	$check_referer = explode('?', $_SERVER['HTTP_REFERER']);
	$check_referer = trim($check_referer[0]);

	$good_referers = array();

	if ($album_config['hotlink_allowed'] != '')
	{
		$good_referers = explode(',', $album_config['hotlink_allowed']);
	}

	$good_referers[] = $config['server_name'] . $config['script_path'];

	$errored = true;

	for ($i = 0; $i < sizeof($good_referers); $i++)
	{
		$good_referers[$i] = trim($good_referers[$i]);

		if ((strstr($check_referer, $good_referers[$i])) && ($good_referers[$i] != ''))
		{
			$errored = false;
		}
	}

	if ($errored)
	{
		message_die(GENERAL_MESSAGE, $lang['Not_Authorized']);
		/*
		header('Content-type: image/jpeg');
		header('Content-Disposition: filename=' . $pic_info['title_reg'] . '.' . $pic_info['filetype']);
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
$sql = "UPDATE " . ALBUM_TABLE . "
			SET pic_view_count = pic_view_count + 1
			WHERE pic_id = '" . $pic_id . "'";
$result = $db->sql_query($sql);

// ------------------------------------
// Okay, now we can send image to the browser
// ------------------------------------
switch ($pic_info['filetype'])
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
		header('Content-Disposition: filename=' . $pic_info['title_reg'] . '.' . $pic_info['filetype']);
		readfile($images['no_thumbnail']);
		exit;
		break;
}

if ((($pic_info['filetype'] == 'jpg') || ($pic_info['filetype'] == 'png')) && ($apply_wm == false))
{
	header($file_header);
	header('Content-Disposition: filename=' . $pic_info['title_reg'] . '.' . $pic_info['filetype']);
	readfile($pic_info['fullpath']);
	exit;
}

if ($pic_info['filetype'] == 'gif')
{
	header($file_header);
	header('Content-Disposition: filename=' . $pic_info['title_reg'] . '.' . $pic_info['filetype']);
	readfile($pic_info['fullpath']);
	exit;
}

if(($apply_wm == true) && file_exists($pic_info['thumbnail_w_f_fullpath']))
{
	header($file_header);
	header('Content-Disposition: filename=' . $pic_info['title_reg'] . '.' . $pic_info['filetype']);
	readfile($pic_info['thumbnail_w_f_fullpath']);
	exit;
}

// Old Thumbnails - BEGIN
// Old thumbnail generation functions, for GD1 and some strange servers...
if (($album_config['gd_version'] == 1) || ($album_config['use_old_pics_gen'] == 1))
{
	// MG Watermark - BEGIN
	if ($apply_wm == true)
	{
		$wm_position = (($album_config['disp_watermark_at'] > 0) && ($album_config['disp_watermark_at'] < 10)) ? $album_config['disp_watermark_at'] : 5;
		$wm_transition = 50;
		mergeResizePics($pic_info['fullpath'], $wm_file, 0, 0, $pic_info['filetype'], $wm_position, $wm_transition, false);
	}
	else
	{
		readfile($pic_info['fullpath']);
	}
	// MG Watermark - END
	exit;
}
// Old Thumbnails - END

$Image = new ImgObj();

if ($pic_info['filetype'] == 'jpg')
{
	$Image->ReadSourceFileJPG($pic_info['fullpath']);
}
else
{
	$Image->ReadSourceFile($pic_info['fullpath']);
}

if ($apply_wm == true)
{
	$wm_position = (($album_config['disp_watermark_at'] > 0) && ($album_config['disp_watermark_at'] < 10)) ? $album_config['disp_watermark_at'] : 5;
	$wm_maxsize = 50;
	$wm_transition = 75;
	$Image->WatermarkPos($wm_file, $wm_position, $wm_maxsize, $wm_transition);
	$Image->SendToFile($pic_info['thumbnail_new_w_f_fullpath'], $album_config['thumbnail_quality']);
	@chmod($pic_info['thumbnail_new_w_f_fullpath'], 0777);
}

if ($pic_info['filetype'] == 'jpg')
{
	$Image->SendToBrowserJPG($pic_info['title_reg'], $pic_info['filetype'], '', '', $album_config['thumbnail_quality']);
}
else
{
	$Image->SendToBrowser($pic_info['title_reg'], $pic_info['filetype'], '', '', $album_config['thumbnail_quality']);
}

$Image->Destroy();
exit;

?>