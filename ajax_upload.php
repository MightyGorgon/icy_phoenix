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
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

// This page is not in layout special...
$cms_page['page_id'] = 'pic_upload';
$cms_page['page_nav'] = false;
$cms_page['global_blocks'] = false;
$cms_auth_level = (isset($config['auth_view_pic_upload']) ? $config['auth_view_pic_upload'] : AUTH_ALL);
check_page_auth($cms_page['page_id'], $cms_auth_level);

$upload_dir = POSTED_IMAGES_PATH;
$filetypes = 'jpg,gif,png';
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
		echo('3');
		//echo('new Array {"RESULT":"3", "FILENAME":"' . $filename . '"};');
		exit;
	}

	$server_path = create_server_url();

	if ($userdata['user_id'] < 0)
	{
		$filename = 'guest_' . preg_replace('/[^a-z0-9]+/', '_', $filename);
	}
	else
	{
		$filename = preg_replace('/[^a-z0-9]+/', '_', $filename);
		if (USERS_SUBFOLDERS_IMG == true)
		{
			if (is_dir($upload_dir . $userdata['user_id']))
			{
				$upload_dir = $upload_dir . $userdata['user_id'] . '/';
			}
			else
			{
				$dir_creation = @mkdir($upload_dir . $userdata['user_id'], 0777);
				if ($dir_creation == true)
				{
					$upload_dir = $upload_dir . $userdata['user_id'] . '/';
				}
				else
				{
					$filename = 'user_' . $userdata['user_id'] . '_' . $filename;
				}
			}
		}
		else
		{
			$filename = 'user_' . $userdata['user_id'] . '_' . $filename;
		}
	}
	while(file_exists($upload_dir . $filename . '.' . $extension))
	{
		$filename = $filename . '_' . time() . '_' . mt_rand(100000, 999999);
	}
	$filename_tmp = $_FILES['userfile']['tmp_name'];
	$file_size = $_FILES['userfile']['size'];

	if(empty($filename))
	{
		// File is empty
		echo('4');
		//echo('new Array {"RESULT":"4", "FILENAME":"' . $filename . '"};');
		exit;
	}

	if($file_size > $maxsize)
	{
		// File is too big
		echo('5');
		//echo('new Array {"RESULT":"5", "FILENAME":"' . $filename . '"};');
		exit;
	}

	// Purge Cache File - BEGIN
	$cache_data_file = MAIN_CACHE_FOLDER . 'posted_img_list_full.dat';
	if(@is_file($cache_data_file))
	{
		@unlink($cache_data_file);
	}
	$cache_data_file = MAIN_CACHE_FOLDER . 'posted_img_list_' . $userdata['user_id'] . '.dat';
	if(@is_file($cache_data_file))
	{
		@unlink($cache_data_file);
	}
	// Purge Cache File - END

	if(is_uploaded_file($filename_tmp))
	{
		@move_uploaded_file($filename_tmp, $upload_dir . $filename . '.' . $extension);
		@chmod($upload_dir . $filename . '.' . $extension, 0777);
	}

	$pic_size = @getimagesize($upload_dir . $filename . '.' . $extension);
	if($pic_size == false)
	{
		@unlink($upload_dir . $filename . '.' . $extension);
		// Extension not allowed
		echo('3');
		//echo('new Array {"RESULT":"3", "FILENAME":"' . $filename . '"};');
		exit;
	}
	// Success
	//echo('1');
	//echo('new Array {"RESULT":"1", "FILENAME":"' . $filename . '"};');
	echo($filename . '.' . $extension);
	exit;
}
else
{
	// Error
	echo('2');
	//echo('new Array {"RESULT":"2", "FILENAME":"' . $filename . '"};');
	exit;
}

?>