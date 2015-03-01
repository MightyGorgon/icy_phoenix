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

require(IP_ROOT_PATH . 'includes/class_image.' . PHP_EXT);

include(IP_ROOT_PATH . 'includes/class_images.' . PHP_EXT);
$class_images = new class_images();

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
$tmp_split = explode('/', $pic_id);
$pic_user_id = intval((int) $tmp_split[0]);
if ($pic_user_id < 0)
{
	image_no_thumbnail('no_thumb.jpg');
	exit;
	//message_die(GENERAL_MESSAGE, $lang['NO_PICS_SPECIFIED']);
}

$pic_filename = $tmp_split[1];
$pic_fullpath = POSTED_IMAGES_PATH . $pic_user_id . '/' . $pic_filename;
$pic_thumbnail = 'thumb_' . $pic_filename;
$pic_thumbnail_fullpath = POSTED_IMAGES_THUMBS_S_PATH . $pic_thumbnail;
$file_part = explode('.', strtolower($pic_filename));
$pic_filetype = $file_part[sizeof($file_part) - 1];
$pic_title = substr($pic_filename, 0, strlen($pic_filename) - strlen($pic_filetype) - 1);
$pic_title_reg = preg_replace('/[^A-Za-z0-9]+/', '_', $pic_title);

if (USERS_SUBFOLDERS_IMG == true)
{
	$pic_thumbnail_prefix = '';
	$pic_thumbnail_path = POSTED_IMAGES_THUMBS_S_PATH . $pic_user_id . '/';
	$thumbnail_data = $class_images->get_thumbnail_data($pic_thumbnail_path, $pic_thumbnail, $pic_thumbnail_fullpath, $pic_filename, $pic_thumbnail_prefix);
	$pic_thumbnail = $thumbnail_data['thumbnail'];
	$pic_thumbnail_fullpath = $thumbnail_data['full_path'];
}

if (!in_array($pic_filetype, array('gif', 'jpg', 'jpeg', 'png')))
{
	image_no_thumbnail('thumb_' . $pic_title_reg . '.' . $pic_filetype);
	exit;
}

// --------------------------------
// Check thumbnail cache. If cache is available we will SEND & EXIT
// --------------------------------

if(!empty($config['thumbnail_cache']) && file_exists($pic_thumbnail_fullpath))
{
	image_output($pic_thumbnail_fullpath, $pic_title_reg, $pic_filetype, 'thumb_');
	exit;
}

if(!@file_exists($pic_fullpath))
{
	image_no_thumbnail('no_thumb.jpg');
	exit;
	//message_die(GENERAL_MESSAGE, $lang['Pic_not_exist']);
}

$pic_size = @getimagesize($pic_fullpath);
$pic_width = $pic_size[0];
$pic_height = $pic_size[1];

if(($pic_width < $config['thumbnail_s_size']) && ($pic_height < $config['thumbnail_s_size']))
{
	$copy_success = @copy($pic_fullpath, $pic_thumbnail_fullpath);
	@chmod($pic_thumbnail_fullpath, 0777);
	image_output($pic_fullpath, $pic_title_reg, $pic_filetype, 'thumb_');
	exit;
}
else
{
	// --------------------------------
	// Cache is empty. Try to re-generate!
	// --------------------------------
	if ($pic_width > $pic_height)
	{
		$thumbnail_width = $config['thumbnail_s_size'];
		$thumbnail_height = $config['thumbnail_s_size'] * ($pic_height / $pic_width);
	}
	else
	{
		$thumbnail_height = $config['thumbnail_s_size'];
		$thumbnail_width = $config['thumbnail_s_size'] * ($pic_width / $pic_height);
	}

	$Image = new ImgObj();

	$Image->ReadSourceFile($pic_fullpath);

	$Image->Resize($thumbnail_width, $thumbnail_height);

	if (!empty($config['show_pic_size_on_thumb']))
	{
		$dimension_string = intval($pic_width) . 'x' . intval($pic_height) . '(' . intval(filesize($pic_fullpath) / 1024) . 'KB)';
		$Image->Text($dimension_string);
	}

	if (!empty($config['thumbnail_cache']))
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