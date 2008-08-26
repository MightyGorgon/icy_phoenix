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
* Volodymyr (CLowN) Skoryk (blaatimmy72@yahoo.com)
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
$pic_thumbnail_new = md5($pic_filename) . '.' . $pic_filetype;
$pic_thumbnail = ( $thispic['pic_thumbnail'] == '' ) ? $pic_thumbnail_new : $thispic['pic_thumbnail'];
$pic_thumbnail_fullpath = ALBUM_MED_CACHE_PATH . $pic_thumbnail;
$pic_thumbnail_new_fullpath = ALBUM_MED_CACHE_PATH . $pic_thumbnail_new;
$pic_thumbnail_wm_fullpath = ALBUM_WM_CACHE_PATH . $pic_thumbnail;
$pic_thumbnail_wm_new_fullpath = ALBUM_WM_CACHE_PATH . $pic_thumbnail_new;
$pic_title = $thispic['pic_title'];
$pic_title_reg = ereg_replace("[^A-Za-z0-9]", "_", $pic_title);
$apply_wm = false;
$wm_file = ALBUM_WM_FILE;

if( ($album_config['use_watermark'] == true) && ($userdata['user_level'] != ADMIN) && ( (!$userdata['session_logged_in']) || ($album_config['wut_users'] == 1) ) )
{
	$pic_thumbnail_fullpath = $pic_thumbnail_wm_fullpath;
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

	if ($errored)
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

// --------------------------------
// Check thumbnail cache. If cache is available we will SEND & EXIT
// --------------------------------
if( ($album_config['midthumb_cache'] == true) && file_exists($pic_thumbnail_fullpath) )
{
	/*
	$Image = new ImgObj();
	$Image->ReadSourceFile($pic_thumbnail_fullpath);
	$Image->SendToBrowser($pic_title_reg, $pic_filetype, 'mid_', '', $album_config['thumbnail_quality']);
	$Image->Destroy();
	exit;
	*/
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
			header("Content-Disposition: filename=mid_" . $pic_title_reg . '.' . $pic_filetype);
			readfile($images['no_thumbnail']);
			exit;
			break;
	}
	header($file_header);
	header("Content-Disposition: filename=mid_" . $pic_title_reg . '.' . $pic_filetype);
	readfile($pic_thumbnail_fullpath);
	exit;
}

if ( ($pic_filetype == 'gif') && ($album_config['show_gif_mid_thumb'] == 1) )
{
	header('Content-type: image/gif');
	header('Content-Disposition: filename=' . $pic_title_reg . '.' . $pic_filetype);
	readfile($pic_fullpath);
	exit;
}

// ------------------------------------
// Send Thumbnail to browser
// ------------------------------------
$pic_size = @getimagesize($pic_fullpath);
$pic_width = $pic_size[0];
$pic_height = $pic_size[1];

if( ($pic_width < $album_config['midthumb_width']) && ($pic_height < $album_config['midthumb_height']) )
{
	if ( $pic_filetype == 'gif' )
	{
		$copy_success = @copy($pic_fullpath, $pic_thumbnail_fullpath);
		header('Content-type: image/gif');
		header('Content-Disposition: filename=' . $pic_title_reg . '.' . $pic_filetype);
		readfile($pic_fullpath);
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
			$thumbnail = mergePics($pic_fullpath, $wm_file, $wm_position, $wm_transition, $pic_filetype);
		}
		// MG Watermark - END
		if ( ($album_config['midthumb_cache'] == true) )
		{
			// Re-generate successfully. Write it to disk!
			switch ($pic_filetype)
			{
				case 'jpg':
					@imagejpeg($thumbnail, $pic_thumbnail_new_fullpath, $album_config['thumbnail_quality']);
					break;
				case 'png':
					@imagepng($thumbnail, $pic_thumbnail_new_fullpath);
					break;
			}
			@chmod($pic_thumbnail_new_fullpath, 0777);
		}
		// After write to disk, do not forget to send to browser also
		switch ($pic_filetype)
		{
			case 'jpg':
				@imagejpeg($thumbnail, '', $album_config['thumbnail_quality']);
				break;
			case 'png':
				@imagepng($thumbnail);
				break;
			default:
				header('Content-type: image/jpeg');
				header("Content-Disposition: filename=mid_" . $pic_title_reg . '.' . $pic_filetype);
				readfile($images['no_thumbnail']);
				break;
		}
		exit;
	}
	// Old Thumbnails - END

	$Image = new ImgObj();
	//$Image->ReadSourceFile($pic_fullpath);
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
	}

	//$Image->SendToBrowser($pic_title_reg, $pic_filetype, '', '', $album_config['thumbnail_quality']);
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
}
else
{
	// --------------------------------
	// Hmm, cache is empty. Try to re-generate!
	// --------------------------------
	if ($pic_width > $pic_height)
	{
		$thumbnail_width = $album_config['midthumb_width'];
		$thumbnail_height = $album_config['midthumb_width'] * ($pic_height/$pic_width);
	}
	else
	{
		$thumbnail_height = $album_config['midthumb_height'];
		$thumbnail_width = $album_config['midthumb_height'] * ($pic_width/$pic_height);
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
			$thumbnail = mergeResizePics($pic_fullpath, $wm_file, $thumbnail_width, $thumbnail_height, $wm_position, $wm_transition, $pic_filetype);
		}
		else
		{
			$thumbnail = ($album_config['gd_version'] == 1) ? @imagecreate($thumbnail_width, $thumbnail_height) : @imagecreatetruecolor($thumbnail_width, $thumbnail_height);
			$resize_function = ($album_config['gd_version'] == 1) ? 'imagecopyresized' : 'imagecopyresampled';
			@$resize_function($thumbnail, $pic_fullpath, 0, 0, 0, 0, $thumbnail_width, $thumbnail_height, $pic_width, $pic_height);
		}
		// MG Watermark - END
		if ( ($album_config['midthumb_cache'] == 1) )
		{
			// Re-generate successfully. Write it to disk!
			switch ($pic_filetype)
			{
				case 'jpg':
					@imagejpeg($thumbnail, $pic_thumbnail_new_fullpath, $album_config['thumbnail_quality']);
					break;
				case 'png':
					@imagepng($thumbnail, $pic_thumbnail_new_fullpath);
					break;
			}
			@chmod($pic_thumbnail_new_fullpath, 0777);
		}
		// After write to disk, do not forget to send to browser also
		switch ($pic_filetype)
		{
			case 'jpg':
				@imagejpeg($thumbnail, '', $album_config['thumbnail_quality']);
				break;
			case 'png':
				@imagepng($thumbnail);
				break;
			default:
				header('Content-type: image/jpeg');
				header("Content-Disposition: filename=mid_" . $pic_title_reg . '.' . $pic_filetype);
				readfile($images['no_thumbnail']);
				break;
		}
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

	/*
	// This is most CPU consuming for JPG...
	$Image->ReadSourceFile($pic_fullpath);
	*/

	$Image->Resize($thumbnail_width, $thumbnail_height);
	if ( $apply_wm == true )
	{
		$wm_position = ( ($album_config['disp_watermark_at'] > 0) && ($album_config['disp_watermark_at'] < 10) ) ? $album_config['disp_watermark_at'] : 5;
		$wm_maxsize = 50;
		$wm_transition = 75;
		$Image->WatermarkPos($wm_file, $wm_position, $wm_maxsize, $wm_transition);
	}
	if ($album_config['midthumb_cache'] == true)
	{
		if ( $apply_wm == true )
		{
			$Image->SendToFile($pic_thumbnail_wm_new_fullpath, $album_config['thumbnail_quality']);
			//$Image->SendToFile($pic_thumbnail_wm_new_fullpath, $album_config['thumbnail_quality']);
			//@chmod($pic_thumbnail_wm_new_fullpath, 0777);
		}
		else
		{
			$Image->SendToFile($pic_thumbnail_new_fullpath, $album_config['thumbnail_quality']);
			//$Image->SendToFile($pic_thumbnail_new_fullpath, $album_config['thumbnail_quality']);
			//@chmod($pic_thumbnail_new_fullpath, 0777);
		}

		$sql = "UPDATE ". ALBUM_TABLE ."
			SET pic_thumbnail = '" . $pic_thumbnail_new . "'
			WHERE pic_id = '" . $pic_id . "'";
		if( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not update pic information', '', __LINE__, __FILE__, $sql);
		}
	}

	if ($pic_filetype == 'jpg')
	{
		$Image->SendToBrowserJPG($pic_title_reg, $pic_filetype, 'mid_', '', $album_config['thumbnail_quality']);
	}
	else
	{
		$Image->SendToBrowser($pic_title_reg, $pic_filetype, 'mid_', '', $album_config['thumbnail_quality']);
	}

	/*
	// This is most CPU consuming for JPG...
	$Image->SendToBrowser($pic_title_reg, $pic_filetype, 'mid_', '', $album_config['thumbnail_quality']);
	*/

	if ( $Image == true )
	{
		$Image->Destroy();
		exit;
	}
	else
	{
		$Image->Destroy();
		// It seems you have not GD installed :(
		if ($album_config['show_img_no_gd'] == false)
		{
			header('Content-type: image/jpeg');
			header("Content-Disposition: filename=mid_" . $pic_title_reg . '.' . $pic_filetype);
			readfile($images['no_thumbnail']);
			exit;
		}
		else
		{
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
					header("Content-Disposition: filename=mid_" . $pic_title_reg . '.' . $pic_filetype);
					readfile($images['no_thumbnail']);
					exit;
					break;
			}
			header($file_header);
			header("Content-Disposition: filename=mid_" . $pic_title_reg . '.' . $pic_filetype);
			readfile(ALBUM_UPLOAD_PATH . $pic_filename);
			exit;
		}
	}
}

?>