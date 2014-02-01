<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

define('IMG_THUMB', true);
define('CT_SECLEVEL', 'MEDIUM');
$ct_ignoregvar = array('');
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
$pic_id = request_var('pic_id', '');
if (empty($pic_id))
{
	image_no_thumbnail('no_thumb.jpg');
	exit;
	//die($lang['NO_PICS_SPECIFIED']);
	//message_die(GENERAL_MESSAGE, $lang['NO_PICS_SPECIFIED']);
}
$pic_id = urldecode($pic_id);

// ------------------------------------
//Configuration Options
// ------------------------------------
/*
$config['show_pic_size_on_thumb'] = 1; //1 = Size Informations on Thumbnails; 0 = Size Informations on Thumbnails
$config['show_img_no_gd'] = 0;
$config['thumbnail_cache'] = 1;
$config['gd_version'] = 2;
$config['thumbnail_quality'] = 85;
$config['thumbnail_size'] = 400;
$config['thumbnail_posts'] = 1;
*/

// Mighty Gorgon: this code is reserved for generating extra thumbnail size on the fly...
$req_thumb_size = request_var('thumbnail_size', $config['thumbnail_size']);
$req_thumb_size = (($req_thumb_size > 600) || ($req_thumb_size < 30)) ? $config['thumbnail_size'] : $req_thumb_size;

$pic_fullpath = str_replace(array(' '), array('%20'), $pic_id);
$pic_id = str_replace('http://', '', str_replace('https://', '', $pic_id));
$pic_path[] = array();
$pic_path = explode('/', $pic_id);
$pic_filename = $pic_path[sizeof($pic_path) - 1];
$file_part = explode('.', strtolower($pic_filename));
$pic_filetype = $file_part[sizeof($file_part) - 1];
// Mighty Gorgon: this prefix is needed to not overwrite standard thumbnails with the ones generated with the extra size...
$pic_thumbnail_prefix = 'mid_' . (($req_thumb_size != $config['thumbnail_size']) ? ($req_thumb_size . '_') : '') . md5($pic_id);
$thumb_ext_array = array('gif', 'jpg', 'png');
$image_processed = false;
if (!in_array($pic_filetype, $thumb_ext_array))
{
	$image_processed = true;
	$pic_size = get_full_image_info($pic_fullpath);

	if(empty($pic_size))
	{
		image_no_thumbnail('thumb_' . $pic_title_reg . '.' . $pic_filetype);
		exit;
	}

	$pic_width = $pic_size['width'];
	$pic_height = $pic_size['height'];
	$pic_filetype = strtolower($pic_size['type']);

	$pic_title = substr($pic_filename, 0, strlen($pic_filename) - strlen($pic_filetype) - 1);
	$pic_title_reg = preg_replace('/[^A-Za-z0-9]+/', '_', $pic_title);
	$pic_thumbnail = $pic_thumbnail_prefix . '.' . $pic_filetype;
}
else
{
	$pic_title = substr($pic_filename, 0, strlen($pic_filename) - strlen($pic_filetype) - 1);
	$pic_title_reg = preg_replace('/[^A-Za-z0-9]+/', '_', $pic_title);
	$pic_thumbnail = $pic_thumbnail_prefix . '_' . $pic_filename;
}

$pic_thumbnail_fullpath = POSTED_IMAGES_THUMBS_PATH . $pic_thumbnail;

if (USERS_SUBFOLDERS_IMG == true)
{
	if ((sizeof($pic_path) > 4) && (strpos($pic_id, $config['server_name']) !== false))
	{
		// Mighty Gorgon: this prefix is needed to not overwrite standard thumbnails with the ones generated with the extra size...
		$pic_thumbnail_prefix = (($req_thumb_size != $config['thumbnail_size']) ? ('__' . $req_thumb_size . '__') : '');
		// We need to add IP_ROOT_PATH because POSTED_IMAGES_PATH already includes it...
		$pic_main_folder = IP_ROOT_PATH . $pic_path[sizeof($pic_path) - 4] . '/' . $pic_path[sizeof($pic_path) - 3] . '/';
		if ($pic_main_folder == POSTED_IMAGES_PATH)
		{
			$pic_thumbnail_path = POSTED_IMAGES_THUMBS_PATH . $pic_path[sizeof($pic_path) - 2];
			if (is_dir($pic_thumbnail_path))
			{
				$pic_thumbnail = $pic_thumbnail_prefix . $pic_filename;
				$pic_thumbnail_fullpath = $pic_thumbnail_path . '/' . $pic_thumbnail;
			}
			else
			{
				$dir_creation = @mkdir($pic_thumbnail_path, 0777);
				if ($dir_creation == true)
				{
					$pic_thumbnail = $pic_thumbnail_prefix . $pic_filename;
					$pic_thumbnail_fullpath = $pic_thumbnail_path . '/' . $pic_thumbnail;
				}
			}
		}
	}
}

// This is needed to set up the new thumbnail size if requested via $_GET
$config['thumbnail_size'] = $req_thumb_size;

// --------------------------------
// Check thumbnail cache. If cache is available we will SEND & EXIT
// --------------------------------
// Do not use CACHE if cache=false parameter is passed through the url: posted_img_thumbnail.php?pic_id=XXX&cache=false
if(!empty($_GET['cache']))
{
	$config['thumbnail_cache'] = ($_GET['cache'] == 'false') ? false : $config['thumbnail_cache'];
}

if(($config['thumbnail_cache'] == true) && file_exists($pic_thumbnail_fullpath))
{
	image_output($pic_thumbnail_fullpath, $pic_title_reg, $pic_filetype, 'thumb_');
	exit;
}

$server_path = create_server_url();
$pic_exists = false;
$pic_local = false;
if ((strpos($pic_fullpath, $server_path) !== false) || @file_exists($pic_fullpath))
{
	$pic_local = true;
	$pic_localpath = str_replace($server_path, '', $pic_fullpath);
	if(@file_exists($pic_localpath))
	{
		// Mighty Gorgon - Are we sure that this won't cause other issues??? Test please...
		$pic_fullpath = $pic_localpath;
		$pic_exists = true;
	}
	else
	{
		if (@file_exists(array_shift(explode('?', basename($pic_localpath)))))
		{
			$pic_exists = true;
		}
	}
}

if(!$pic_exists && any_url_exists($pic_fullpath))
{
	$pic_exists = true;
}

if(!$pic_exists)
{
	image_no_thumbnail('thumb_' . $pic_title_reg . '.' . $pic_filetype);
	exit;
}

if ($image_processed == false)
{
	$pic_size = get_full_image_info($pic_fullpath, null, $pic_local);
	if(empty($pic_size))
	{
		image_no_thumbnail('thumb_' . $pic_title_reg . '.' . $pic_filetype);
		exit;
	}

	$pic_width = $pic_size['width'];
	$pic_height = $pic_size['height'];
	$pic_filetype = strtolower($pic_size['type']);
}

// ------------------------------------
// Send Thumbnail to browser
// ------------------------------------
if(($pic_width < $config['thumbnail_size']) && ($pic_height < $config['thumbnail_size']))
{
	if($config['thumbnail_cache'] == true)
	{
		$copy_success = @copy($pic_fullpath, $pic_thumbnail_fullpath);
		@chmod($pic_thumbnail_fullpath, 0777);
	}
	image_output($pic_fullpath, $pic_title_reg, $pic_filetype, '');
	exit;
}
else
{
	// --------------------------------
	// Cache is empty. Try to re-generate!
	// --------------------------------
	if ($pic_width > $pic_height)
	{
		$thumbnail_width = $config['thumbnail_size'];
		$thumbnail_height = $config['thumbnail_size'] * ($pic_height / $pic_width);
	}
	else
	{
		$thumbnail_height = $config['thumbnail_size'];
		$thumbnail_width = $config['thumbnail_size'] * ($pic_width / $pic_height);
	}

	$Image = new ImgObj();

	$Image->JPGQuality = $config['thumbnail_quality'];
	$Image->ReadSourceFile($pic_fullpath);
	$Image->Resize($thumbnail_width, $thumbnail_height);

	if($config['show_pic_size_on_thumb'] == 1)
	{
		$image_filesize = get_formatted_filesize(filesize($pic_fullpath));
		$image_dimension = intval($pic_width) . "x" . intval($pic_height);
		$image_text_output = $image_dimension . (($thumbnail_width >= 360) ? (' - ' . $image_filesize) : '');

		if ($thumbnail_width >= 260)
		{
			$image_exif_data = $Image->ExifData;
			if (!empty($image_exif_data))
			{
				$image_exif_data_short = $Image->exif_get_data_short($image_exif_data);
				$exif_data_key_array = array('MODEL', 'FOCAL_LENGTH', 'EXPOSURE', 'APERTURE', 'ISO');
				$exif_data_array = array();
				foreach ($exif_data_key_array as $exif_key)
				{
					if ($image_exif_data_short[$exif_key] != 'EXIF_UNKNOWN')
					{
						$process_tag = true;
						if ($exif_key == 'ISO')
						{
							$image_exif_data_short[$exif_key] = 'ISO ' . $image_exif_data_short[$exif_key];
						}
						elseif ($exif_key == 'MODEL')
						{
							if ($thumbnail_width >= 320)
							{
								$image_exif_data_short[$exif_key] = str_replace(array('DIGITAL', '  '), array('', ' '), $image_exif_data_short[$exif_key]);
								$image_exif_data_short[$exif_key] = ((strlen($image_exif_data_short[$exif_key]) >= 23) ? (substr($image_exif_data_short[$exif_key], 0, 18) . '...') : $image_exif_data_short[$exif_key]);
							}
							else
							{
								$process_tag = false;
							}
						}

						if (!empty($process_tag))
						{
							$exif_data_array[] = $image_exif_data_short[$exif_key];
						}
					}
				}
				$image_text_output = (!empty($exif_data_array) ? (implode(' - ', $exif_data_array) . ' - ') : '') . $image_text_output;
			}
		}
		$image_text_output = str_replace(' - 0BYTES', '', $image_text_output);
		$Image->Text($image_text_output);
	}

	if ($config['thumbnail_cache'] == true)
	{
		$Image->SendToFile($pic_thumbnail_fullpath, $config['thumbnail_quality']);
		//@chmod($pic_thumbnail_fullpath, 0777);
	}

	$Image->SendToBrowser($pic_title_reg, $pic_filetype, 'thumb_', '', $config['thumbnail_quality']);

	if ($Image == true)
	{
		$Image->Destroy();
		exit;
	}
	else
	{
		$Image->Destroy();
		image_no_thumbnail('thumb_' . $pic_title_reg . '.' . $pic_filetype);
		exit;
	}
}

?>