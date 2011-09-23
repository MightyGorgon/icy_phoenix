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
	die('No pics specified');
	//message_die(GENERAL_MESSAGE, 'No pics specified');
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
	$pic_info['thumbnail_m_fullpath'] = $pic_info['thumbnail_w_fullpath'];
	$pic_info['thumbnail_new_m_fullpath'] = $pic_info['thumbnail_new_w_fullpath'];
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

// --------------------------------
// Check thumbnail cache. If cache is available we will SEND & EXIT
// --------------------------------
if(($album_config['midthumb_cache'] == true) && file_exists($pic_info['thumbnail_m_fullpath']))
{
	/*
	$Image = new ImgObj();
	$Image->ReadSourceFile($pic_info['thumbnail_m_fullpath']);
	$Image->SendToBrowser($pic_info['title_reg'], $pic_info['filetype'], 'mid_', '', $album_config['thumbnail_quality']);
	$Image->Destroy();
	exit;
	*/
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
			header('Content-Disposition: filename=mid_' . $pic_info['title_reg'] . '.' . $pic_info['filetype']);
			readfile($images['no_thumbnail']);
			exit;
			break;
	}
	header($file_header);
	header('Content-Disposition: filename=mid_' . $pic_info['title_reg'] . '.' . $pic_info['filetype']);
	readfile($pic_info['thumbnail_m_fullpath']);
	exit;
}

if (($pic_info['filetype'] == 'gif') && ($album_config['show_gif_mid_thumb'] == 1))
{
	header('Content-type: image/gif');
	header('Content-Disposition: filename=' . $pic_info['title_reg'] . '.' . $pic_info['filetype']);
	readfile($pic_info['fullpath']);
	exit;
}

// ------------------------------------
// Send Thumbnail to browser
// ------------------------------------
$pic_size = @getimagesize($pic_info['fullpath']);
$pic_width = $pic_size[0];
$pic_height = $pic_size[1];

if(($pic_width < $album_config['midthumb_width']) && ($pic_height < $album_config['midthumb_height']))
{
	if ($pic_info['filetype'] == 'gif')
	{
		$copy_success = @copy($pic_info['fullpath'], $pic_info['thumbnail_m_fullpath']);
		@chmod($pic_info['thumbnail_m_fullpath'], 0777);
		header('Content-type: image/gif');
		header('Content-Disposition: filename=' . $pic_info['title_reg'] . '.' . $pic_info['filetype']);
		readfile($pic_info['fullpath']);
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
			$thumbnail = mergeResizePics($pic_info['fullpath'], $wm_file, 0, 0, $pic_info['filetype'], $wm_position, $wm_transition, false);
		}
		// MG Watermark - END
		if (($album_config['midthumb_cache'] == true))
		{
			// Re-generate successfully. Write it to disk!
			switch ($pic_info['filetype'])
			{
				case 'jpg':
					@imagejpeg($thumbnail, $pic_info['thumbnail_new_m_fullpath'], $album_config['thumbnail_quality']);
					break;
				case 'png':
					@imagepng($thumbnail, $pic_info['thumbnail_new_m_fullpath']);
					break;
			}
			@chmod($pic_info['thumbnail_new_m_fullpath'], 0777);
		}
		// After write to disk, do not forget to send to browser also
		switch ($pic_info['filetype'])
		{
			case 'jpg':
				@imagejpeg($thumbnail, '', $album_config['thumbnail_quality']);
				break;
			case 'png':
				@imagepng($thumbnail);
				break;
			default:
				header('Content-type: image/jpeg');
				header('Content-Disposition: filename=mid_' . $pic_info['title_reg'] . '.' . $pic_info['filetype']);
				readfile($images['no_thumbnail']);
				break;
		}
		exit;
	}
	// Old Thumbnails - END

	$Image = new ImgObj();
	//$Image->ReadSourceFile($pic_info['fullpath']);
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
	}

	//$Image->SendToBrowser($pic_info['title_reg'], $pic_info['filetype'], '', '', $album_config['thumbnail_quality']);
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
	if (($album_config['gd_version'] == 1) || ($album_config['use_old_pics_gen'] == 1))
	{
		// MG Watermark - BEGIN
		if ($apply_wm == true)
		{
			$wm_position = (($album_config['disp_watermark_at'] > 0) && ($album_config['disp_watermark_at'] < 10)) ? $album_config['disp_watermark_at'] : 5;
			$wm_transition = 50;
			$thumbnail = mergeResizePics($pic_info['fullpath'], $wm_file, $thumbnail_width, $thumbnail_height, $pic_info['filetype'], $wm_position, $wm_transition, true);
		}
		else
		{
			$thumbnail = ($album_config['gd_version'] == 1) ? @imagecreate($thumbnail_width, $thumbnail_height) : @imagecreatetruecolor($thumbnail_width, $thumbnail_height);
			$resize_function = ($album_config['gd_version'] == 1) ? 'imagecopyresized' : 'imagecopyresampled';
			@$resize_function($thumbnail, $pic_info['fullpath'], 0, 0, 0, 0, $thumbnail_width, $thumbnail_height, $pic_width, $pic_height);
		}
		// MG Watermark - END
		if (($album_config['midthumb_cache'] == 1))
		{
			// Re-generate successfully. Write it to disk!
			switch ($pic_info['filetype'])
			{
				case 'jpg':
					@imagejpeg($thumbnail, $pic_info['thumbnail_new_m_fullpath'], $album_config['thumbnail_quality']);
					break;
				case 'png':
					@imagepng($thumbnail, $pic_info['thumbnail_new_m_fullpath']);
					break;
			}
			@chmod($pic_info['thumbnail_new_m_fullpath'], 0777);
		}
		// After write to disk, do not forget to send to browser also
		switch ($pic_info['filetype'])
		{
			case 'jpg':
				@imagejpeg($thumbnail, '', $album_config['thumbnail_quality']);
				break;
			case 'png':
				@imagepng($thumbnail);
				break;
			default:
				header('Content-type: image/jpeg');
				header('Content-Disposition: filename=mid_' . $pic_info['title_reg'] . '.' . $pic_info['filetype']);
				readfile($images['no_thumbnail']);
				break;
		}
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

	/*
	// This is most CPU consuming for JPG...
	$Image->ReadSourceFile($pic_info['fullpath']);
	*/

	$Image->Resize($thumbnail_width, $thumbnail_height);
	if ($apply_wm == true)
	{
		$wm_position = (($album_config['disp_watermark_at'] > 0) && ($album_config['disp_watermark_at'] < 10)) ? $album_config['disp_watermark_at'] : 5;
		$wm_maxsize = 50;
		$wm_transition = 75;
		$Image->WatermarkPos($wm_file, $wm_position, $wm_maxsize, $wm_transition);
	}
	if ($album_config['midthumb_cache'] == true)
	{
		if ($pic_info['filetype'] == 'jpg')
		{
			$Image->SendToFileJPG($pic_info['thumbnail_new_m_fullpath'], $album_config['thumbnail_quality']);
			//$Image->SendToFile($pic_info['thumbnail_new_m_fullpath'], $album_config['thumbnail_quality']);
			//@chmod($pic_info['thumbnail_new_m_fullpath'], 0777);
		}
		else
		{
			$Image->SendToFile($pic_info['thumbnail_new_m_fullpath'], $album_config['thumbnail_quality']);
			//$Image->SendToFile($pic_info['thumbnail_new_m_fullpath'], $album_config['thumbnail_quality']);
			//@chmod($pic_info['thumbnail_new_m_fullpath'], 0777);
		}
	}

	if ($pic_info['filetype'] == 'jpg')
	{
		$Image->SendToBrowserJPG($pic_info['title_reg'], $pic_info['filetype'], 'mid_', '', $album_config['thumbnail_quality']);
	}
	else
	{
		$Image->SendToBrowser($pic_info['title_reg'], $pic_info['filetype'], 'mid_', '', $album_config['thumbnail_quality']);
	}

	/*
	// This is most CPU consuming for JPG...
	$Image->SendToBrowser($pic_info['title_reg'], $pic_info['filetype'], 'mid_', '', $album_config['thumbnail_quality']);
	*/

	if ($Image == true)
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
			header('Content-Disposition: filename=mid_' . $pic_info['title_reg'] . '.' . $pic_info['filetype']);
			readfile($images['no_thumbnail']);
			exit;
		}
		else
		{
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
					header('Content-Disposition: filename=mid_' . $pic_info['title_reg'] . '.' . $pic_info['filetype']);
					readfile($images['no_thumbnail']);
					exit;
					break;
			}
			header($file_header);
			header('Content-Disposition: filename=mid_' . $pic_info['title_reg'] . '.' . $pic_info['filetype']);
			readfile($pic_info['fullpath']);
			exit;
		}
	}
}

?>