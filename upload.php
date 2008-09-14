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
* difus (admin@digi-sky.net)
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

/*
$cms_page_id = '0';
$cms_page_name = 'pic_upload';
*/
$auth_level_req = $board_config['auth_view_pic_upload'];
if ($auth_level_req > AUTH_ALL)
{
	if (($auth_level_req == AUTH_REG) && (!$userdata['session_logged_in']))
	{
		message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
	}
	if ($userdata['user_level'] != ADMIN)
	{
		if ($auth_level_req == AUTH_ADMIN)
		{
			message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
		}
		if (($auth_level_req == AUTH_MOD) && ($userdata['user_level'] != MOD))
		{
			message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
		}
	}
}

$gen_simple_header = true;
$page_title = $lang['Upload_Image_Local'];
$meta_description = '';
$meta_keywords = '';
include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);

$upload_dir = POSTED_IMAGES_PATH;
$filetypes = 'jpg,gif,png';
$maxsize = (1000 * 1024);

if(isset($_FILES['userfile']))
{
	$filename = strtolower($_FILES['userfile']['name']);
	$types = explode(',', $filetypes);
	$file = explode('.', $filename);
	$extension = $file[count($file) - 1];
	$filename = substr($filename, 0, strlen($filename) - strlen($extension) - 1);

	if(!in_array($extension, $types))
	{
		message_die(GENERAL_MESSAGE, $lang['Upload_File_Type_Allowed'] . ': ' . str_replace(',', ', ', $filetypes) . '.');
	}

	$template->set_filenames(array('body' => 'uploaded_image_bbc_popup.tpl'));

	$server_path = create_server_url();

	if ($userdata['user_id'] < 0)
	{
		$filename = 'guest_' . ereg_replace("[^a-z0-9]", "_", $filename);
	}
	else
	{
		$filename = ereg_replace("[^a-z0-9]", "_", $filename);
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
		message_die(GENERAL_MESSAGE, $lang['Upload_Image_Empty']);
	}

	if($file_size > $maxsize)
	{
		message_die(GENERAL_MESSAGE, $lang['Upload_File_Too_Big'] . ' ' . ($maxsize / 1000) . 'KB');
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
		message_die(GENERAL_MESSAGE, $lang['Upload_File_Type_Allowed'] . ': ' . str_replace(',', ', ', $filetypes) . '.');
	}

	$template->assign_vars(array(
		'S_ACTION' => append_sid('upload.' . PHP_EXT),
		'L_UPLOAD_IMAGE' => $lang['Upload_Image_Local'],
		'L_BBCODE' => $lang['BBCode'],
		'L_BBCODE_DES' => $lang['Uploaded_Image_BBC'],
		'L_UPLOAD_SUCCESS' => $lang['Uploaded_Image_Success'],
		'L_INSERT_BBC' => $lang['Upload_Insert_Image'],
		'L_CLOSE_WINDOW' => $lang['Upload_Close'],
		'IMG_BBCODE' => '[img]' . $server_path . $upload_dir . $filename . '.' . $extension . '[/img]',
		)
	);

}
else
{
	$template->set_filenames(array('body' => 'upload_image_popup.tpl'));

	$template->assign_vars(array(
		'S_ACTION' => append_sid('upload.' . PHP_EXT),
		'L_UPLOAD_IMAGE' => $lang['Upload_Image_Local'],
		'L_UPLOAD_IMAGE_EXPLAIN' => $lang['Upload_Image_Local_Explain'],
		'L_ALLOWED_EXT' => $lang['Upload_File_Type_Allowed'] . ': ' . str_replace(',', ', ', $filetypes) . '.',
		'L_SUBMIT' => $lang['Submit'],
		)
	);

}

$template->pparse('body');

include(IP_ROOT_PATH . 'includes/page_tail.' . PHP_EXT);

?>