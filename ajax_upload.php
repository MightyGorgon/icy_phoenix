<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
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

include(IP_ROOT_PATH . 'includes/class_images.' . PHP_EXT);
$class_images = new class_images();

// This page is not in layout special...
$cms_page['page_id'] = 'pic_upload';
$cms_page['page_nav'] = false;
$cms_page['global_blocks'] = false;
$cms_auth_level = (isset($config['auth_view_pic_upload']) ? $config['auth_view_pic_upload'] : AUTH_ALL);
check_page_auth($cms_page['page_id'], $cms_auth_level);

$upload_dir = POSTED_IMAGES_PATH;
$filetypes = 'jpg,jpeg,gif,png';
$maxsize = (1000 * 1024);

/* Results:
* 1 => Success
* 2 => Error
* 3 => Extension not allowed
* 4 => File is empty
* 5 => File too big
*/

if(isset($_FILES['userfile']))
{
	$filename = strtolower($_FILES['userfile']['name']);
	$types = explode(',', $filetypes);
	$file = explode('.', $filename);
	$extension = $file[sizeof($file) - 1];
	$filename = substr($filename, 0, strlen($filename) - strlen($extension) - 1);

	if(!in_array($extension, $types))
	{
		// Extension not allowed
		//echo('3');
		echo('3|' . $filename . '.' . $extension . '|0|0|0');
		exit;
	}

	$server_path = create_server_url();

	$image_upload_data = $class_images->get_image_upload_data($filename, $extension, $upload_dir);
	$upload_dir = $image_upload_data['upload_dir'];
	$filename = $image_upload_data['filename'];

	$filename_tmp = $_FILES['userfile']['tmp_name'];
	$file_size = $_FILES['userfile']['size'];

	if(empty($filename))
	{
		// File is empty
		//echo('4');
		echo('4|' . $filename . '.' . $extension . '|0|0|0');
		exit;
	}

	if($file_size > $maxsize)
	{
		// File is too big
		//echo('5');
		echo('5|' . $filename . '.' . $extension . '|0|0|0');
		exit;
	}

	$upload_result = $class_images->upload_image($filename, $extension, $upload_dir, $filename_tmp);
	if (empty($upload_result))
	{
		// Extension not allowed
		//echo('3');
		echo('3|' . $filename . '.' . $extension . '|0|0|0');
		exit;
	}
	// Success
	$filesize = filesize($upload_dir . $filename . '.' . $extension);
	$image_data = array(
		'pic_filename' => $filename . '.' . $extension,
		'pic_size' => $filesize,
		'pic_title' => $filename . '.' . $extension,
		'pic_desc' => $filename . '.' . $extension,
		'pic_user_id' => $user->data['user_id'],
		'pic_user_ip' => $user->ip,
		'pic_time' => time(),
	);
	$image_submit = $class_images->submit_image($image_data, 'insert');
	//echo('1');
	echo('1|' . $filename . '.' . $extension . '|' . (int) $filesize . '|' . (int) $upload_result[0] . '|' . (int) $upload_result[1]);
	//echo($filename . '.' . $extension);
	exit;
}
else
{
	// Error
	//echo('2');
	echo('2|' . $filename . '.' . $extension . '|0|0|0');
	exit;
}

?>