<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
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
}

// ------------------------------------
// Get this pic info and current category info
// ------------------------------------
$sql = "SELECT p.*, c.*
		FROM ". ALBUM_TABLE ." AS p, ". ALBUM_CAT_TABLE ." AS c
		WHERE pic_id = '$pic_id'
			AND c.cat_id = p.pic_cat_id";

if( !$result = $db->sql_query($sql) )
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
$pic_title = $thispic['pic_title'];
$pic_thumbnail = $thispic['pic_thumbnail'];

if( empty($thispic) || !file_exists(ALBUM_UPLOAD_PATH . $pic_filename) )
{
	message_die(GENERAL_MESSAGE, $lang['Pic_not_exist']);
}

// ------------------------------------
// Check the permissions
// ------------------------------------
$album_user_access = album_permissions($album_user_id, $cat_id, ALBUM_AUTH_VIEW, $thispic);
if( $album_user_access['view'] == false )
{
	message_die(GENERAL_MESSAGE, $lang['Not_Authorised']);
}

// ------------------------------------
// Check Pic Approval
// ------------------------------------

if( $userdata['user_level'] != ADMIN )
{
	if( ($thispic['cat_approval'] == ADMIN) || (($thispic['cat_approval'] == MOD) && !$album_user_access['moderator']) )
	{
		if( $thispic['pic_approval'] != 1 )
		{
			message_die(GENERAL_MESSAGE, $lang['Not_Authorised']);
		}
	}
}

// ------------------------------------
// Check hotlink
// ------------------------------------
if( ($album_config['hotlink_prevent'] == true) && (isset($_SERVER['HTTP_REFERER'])) )
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

		if( (strstr($check_referer, $good_referers[$i])) && ($good_referers[$i] != '') )
		{
			$errored = false;
		}
	}

	if( $errored )
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

$nuff_http = nuff_http_vars();

$Image = new ImgObj();
$Image->ReadSourceFile(ALBUM_UPLOAD_PATH . $pic_filename);

if( (($nuff_http['nuff_sepia'] == true) || ($nuff_http['nuff_bw'] == true) || ($nuff_http['nuff_blur'] == true) || ($nuff_http['nuff_scatter'] == true)) && ($album_config['enable_sepia_bw'] == true) )
{

	( ($nuff_http['nuff_resize_w'] == 0) || ($nuff_http['nuff_resize_w'] > 200) ) ? ($nuff_http['nuff_resize_w'] = 200) : false;
	( ($nuff_http['nuff_resize_h'] == 0) || ($nuff_http['nuff_resize_h'] > 150) ) ? ($nuff_http['nuff_resize_h'] = 150) : false;

	$Image->Resize($nuff_http['nuff_resize_w'], $nuff_http['nuff_resize_h']);

	//Apply sepia filter (best to resize before this)
	($nuff_http['nuff_sepia'] == true) ? $Image->Sepia() : false;

	//Apply grayscale filter (best to resize before this)
	($nuff_http['nuff_bw'] == true) ? $Image->Grayscale() : false;

	//Apply blur filter (best to resize before this)
	($nuff_http['nuff_blur'] == true) ? $Image->Blur(10, 10) : false;

	//Apply scatter filter (best to resize before this)
	($nuff_http['nuff_scatter'] == true) ? $Image->Scatter(3) : false;

}
else
{
	if ($nuff_http['nuff_resize'] == true)
	{
		$Image->Resize($nuff_http['nuff_resize_w'], $nuff_http['nuff_resize_h']);
	}
}

//Apply pixelate filter
($nuff_http['nuff_pixelate'] == true) ? $Image->Pixelate(4) : false;

//Apply stereogram (best to resize before this)
($nuff_http['nuff_stereogram'] == true) ? $Image->Stereogram(1) : false;

//Apply infrared filter
($nuff_http['nuff_infrared'] == true) ? $Image->Infrared() : false;

//Apply tint filter
($nuff_http['nuff_tint'] == true) ? $Image->Tint(160, 0, 0) : false;

//Apply interlace filter
($nuff_http['nuff_interlace'] == true) ? $Image->Interlace() : false;

//Apply screen filter
($nuff_http['nuff_screen'] == true) ? $Image->Screen() : false;

//Mirror image [1=horizontal, 2=vertical, 3=both]
($nuff_http['nuff_mirror'] == true) ? $Image->Flip(1) : false;

//Flip image [1=horizontal, 2=vertical, 3=both]
($nuff_http['nuff_flip'] == true) ? $Image->Flip(2) : false;

//Rotate anti-clockwise degrees (transparency lost)
if( $nuff_http['nuff_rotation_d'] > 0 )
{
	($nuff_http['nuff_rotation'] == true) ? $Image->Rotate($nuff_http['nuff_rotation_d']) : false;
}

//WatermarkPos(File, Pos, Size, Transition)
if( ($pic_filetype != 'gif') && ($album_config['use_watermark'] == true) && ($userdata['user_level'] != ADMIN) &&
	( (!$userdata['session_logged_in']) || ($album_config['wut_users'] == 1)) )
{
	//$wm_file = ALBUM_WM_FILE;
	$wm_file = (file_exists($thispic['cat_wm']) ? $thispic['cat_wm'] : ALBUM_WM_FILE);
	$wm_position = ( ($album_config['disp_watermark_at'] > 0) && ($album_config['disp_watermark_at'] < 10) ) ? $album_config['disp_watermark_at'] : 5;
	$wm_maxsize = 50;
	$wm_transition = 100;
	$Image->WatermarkPos($wm_file, $wm_position, $wm_maxsize, $wm_transition);
}

//$Image->SendToFile("cache/test2"); //Write image to file

//JPG Compression
( ($nuff_http['nuff_recompress'] == false) || ($nuff_http['nuff_recompress_r'] == 0) ) ? ($nuff_http['nuff_recompress_r'] = 75) : false;

$Image->SendToBrowser($pic_title, $pic_filetype, '', '_nuffed', $nuff_http['nuff_recompress_r']);
$Image->Destroy(); //Destroy whole class including GD image in memory.

?>