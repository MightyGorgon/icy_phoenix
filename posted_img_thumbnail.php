<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

// CTracker_Ignore: File checked by human
define('IN_ICYPHOENIX', true);
define('IMG_THUMB', true);
define('CT_SECLEVEL', 'MEDIUM');
$ct_ignoregvar = array('');
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

// Get general album information
include(ALBUM_MOD_PATH . 'album_common.' . PHP_EXT);
require(ALBUM_MOD_PATH . 'album_image_class.' . PHP_EXT);

// ------------------------------------
// Check the request
// ------------------------------------

$pic_id = (isset($_GET['pic_id']) ? $_GET['pic_id'] : (isset($_POST['pic_id']) ? $_POST['pic_id'] : message_die(GENERAL_MESSAGE, 'No pics specified')));
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

	if($pic_size == false)
	{
		header('Content-type: image/jpeg');
		header('Content-Disposition: filename=thumb_' . $pic_title_reg . '.' . $pic_filetype);
		readfile($images['no_thumbnail']);
		exit;
	}

	$pic_width = $pic_size['width'];
	$pic_height = $pic_size['height'];
	$pic_filetype = strtolower($pic_size['type']);

	$pic_title = substr($pic_filename, 0, strlen($pic_filename) - strlen($pic_filetype) - 1);
	$pic_title_reg = preg_replace('/[^A-Za-z0-9]*/', '_', $pic_title);
	$pic_thumbnail = $pic_thumbnail_prefix . '.' . $pic_filetype;
}
else
{
	$pic_title = substr($pic_filename, 0, strlen($pic_filename) - strlen($pic_filetype) - 1);
	$pic_title_reg = preg_replace('/[^A-Za-z0-9]*/', '_', $pic_title);
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

/*
switch ($pic_filetype)
{
	case 'gif':
		break;
	case 'jpg':
		break;
	case 'png':
		break;
	default:
		header('Content-type: image/jpeg');
		header('Content-Disposition: filename=thumb_' . $pic_title_reg . '.' . $pic_filetype);
		readfile($images['no_thumbnail']);
		exit;
		break;
}
*/

// --------------------------------
// Check thumbnail cache. If cache is available we will SEND & EXIT
// --------------------------------
// Do not use CACHE if is specified CACHE in the url: posted_img_thumbnail.php?pic_id=XXX&cache=false
if(!empty($_GET['cache']))
{
	$config['thumbnail_cache'] = ($_GET['cache'] == 'false') ? false : $config['thumbnail_cache'];
}

if(($config['thumbnail_cache'] == true) && file_exists($pic_thumbnail_fullpath))
{
	/*
	$Image = new ImgObj();
	$Image->ReadSourceFile($pic_thumbnail_fullpath);
	$Image->SendToBrowser($pic_title_reg, $pic_filetype, 'thumb_', '', $config['thumbnail_quality']);
	$Image->Destroy();
	exit;
	*/
	switch($pic_filetype)
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
			header('Content-Disposition: filename=thumb_' . $pic_title_reg . '.' . $pic_filetype);
			readfile($images['no_thumbnail']);
			exit;
			break;
	}
	header($file_header);
	header('Content-Disposition: filename=thumb_' . $pic_title_reg . '.' . $pic_filetype);
	readfile($pic_thumbnail_fullpath);
	exit;
}

$server_path = create_server_url();
$pic_exists = false;
$pic_local = false;
if ((strpos($pic_fullpath, $server_path) !== false) || @file_exists($pic_fullpath))
{
	$pic_local = true;
}

if(any_url_exists($pic_fullpath))
{
	$pic_exists = true;
}

if(!$pic_exists)
{
	header('Content-type: image/jpeg');
	header('Content-Disposition: filename=thumb_' . $pic_title_reg . '.' . $pic_filetype);
	readfile($images['no_thumbnail']);
	exit;
	//message_die(GENERAL_MESSAGE, $lang['Pic_not_exist']);
	//die($pic_fullpath);
}

if ($image_processed == false)
{
	$pic_size = get_full_image_info($pic_fullpath, null, $pic_local);

	if($pic_size == false)
	{
		header('Content-type: image/jpeg');
		header('Content-Disposition: filename=thumb_' . $pic_title_reg . '.' . $pic_filetype);
		readfile($images['no_thumbnail']);
		exit;
	}

	$pic_width = $pic_size['width'];
	$pic_height = $pic_size['height'];
	$pic_filetype = strtolower($pic_size['type']);
	//die($pic_filetype);
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
	/*
	$Image = new ImgObj();
	$Image->ReadSourceFile($pic_fullpath);
	$Image->SendToBrowser($pic_title_reg, $pic_filetype, '', '', $config['thumbnail_quality']);
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
			header('Content-Disposition: filename=' . $pic_title_reg . '.' . $pic_filetype);
			readfile($images['no_thumbnail']);
			exit;
			break;
	}
	header($file_header);
	header('Content-Disposition: filename=' . $pic_title_reg . '.' . $pic_filetype);
	readfile($pic_fullpath);
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

	if ($pic_filetype == 'jpg')
	{
		$Image->ReadSourceFileJPG($pic_fullpath);
	}
	else
	{
		$Image->ReadSourceFile($pic_fullpath);
	}

	$Image->Resize($thumbnail_width, $thumbnail_height);

	if( $config['show_pic_size_on_thumb'] == 1)
	{
		$dimension_string = intval($pic_width) . "x" . intval($pic_height) . "(" . intval(filesize($pic_fullpath)/1024) . "KB)";
		$Image->Text($dimension_string);
	}

	if ($config['thumbnail_cache'] == true)
	{
		if ($pic_filetype == 'jpg')
		{
			$Image->SendToFileJPG($pic_thumbnail_fullpath, $album_config['thumbnail_quality']);
		}
		else
		{
			$Image->SendToFile($pic_thumbnail_fullpath, $album_config['thumbnail_quality']);
		}
		//$Image->SendToFile($pic_thumbnail_fullpath, $config['thumbnail_quality']);
		//@chmod($pic_thumbnail_fullpath, 0777);
	}

	if ($pic_filetype == 'jpg')
	{
		$Image->SendToBrowserJPG($pic_title_reg, $pic_filetype, 'thumb_', '', $config['thumbnail_quality']);
	}
	else
	{
		$Image->SendToBrowser($pic_title_reg, $pic_filetype, 'thumb_', '', $config['thumbnail_quality']);
	}

	if ($Image == true)
	{
		$Image->Destroy();
		exit;
	}
	else
	{
		$Image->Destroy();
		header('Content-type: image/jpeg');
		header('Content-Disposition: filename=thumb_' . $pic_title_reg . '.' . $pic_filetype);
		readfile($images['no_thumbnail']);
		exit;
	}
}

?>