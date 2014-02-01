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

// Get general album information
include(ALBUM_MOD_PATH . 'album_common.' . PHP_EXT);

// We need to keep it here... so also error messages will initialize it correctly!
$gen_simple_header = true;

$js_temp = array('jquery/jquery_ajax_upload.js');
$template->js_include = array_merge($template->js_include, $js_temp);
unset($js_temp);

$server_path = create_server_url();
$upload_dir = POSTED_IMAGES_PATH;
$user_upload_dir = '';
$allowed_extensions = 'gif|jpg|jpeg|png';
$max_file_size = (1024 * 1024);

if (USERS_SUBFOLDERS_IMG)
{
	if (is_dir($upload_dir . $user->data['user_id']))
	{
		$user_upload_dir = $user->data['user_id'] . '/';
		$upload_dir = $upload_dir . $user_upload_dir;
	}
	else
	{
		$dir_creation = @mkdir($upload_dir . $user->data['user_id'], 0777);
		if ($dir_creation)
		{
			$user_upload_dir = $user->data['user_id'] . '/';
			$upload_dir = $upload_dir . $user_upload_dir;
		}
	}
}

$show_last_images = true;
if ($show_last_images && ($user->data['user_id'] != ANONYMOUS))
{
	$n_pics = 5;
	$images_data = $class_images->get_user_images($user->data['user_id'], 'i.pic_id DESC', 0, $n_pics);
	if (!empty($images_data))
	{
		$pics_parsed = 0;
		foreach ($images_data as $image_data)
		{
			$pics_parsed++;
			// We are checking for small thumbnails... added an underscore to distinguish those small thumbs respect to mid sized!
			$pic_img_sub_path = (USERS_SUBFOLDERS_IMG && (!empty($image_data['pic_user_id'])) ? ($image_data['pic_user_id'] . '/') : '') . $image_data['pic_filename'];
			$pic_img_url = POSTED_IMAGES_PATH . $pic_img_sub_path;
			$pic_thumbnail_fullpath = POSTED_IMAGES_THUMBS_S_PATH . $pic_img_sub_path;
			$pic_img_thumb = (@file_exists($pic_thumbnail_fullpath) ? $pic_thumbnail_fullpath : append_sid(CMS_PAGE_IMAGE_THUMBNAIL_S . '?pic_id=' . urlencode($pic_img_sub_path)));
			$image_data['pic_title'] = ((strlen($image_data['pic_title']) > 25) ? (substr($image_data['pic_title'], 0, 22) . '...') : $image_data['pic_title']);

			$template->assign_block_vars('pic_img', array(
				'PIC_IMAGE' => $pic_img_url,
				'PIC_THUMB' => $pic_img_thumb,
				'PIC_BBC_INPUT' => 'bbcode_box_r_' . $pics_parsed,
				'PIC_BBC' => '[img]' . $server_path . substr($pic_img_url, strlen(IP_ROOT_PATH)) . '[/img]',
				'PIC_NAME' => $image_data['pic_title']
				)
			);
		}
	}
}

$template_to_parse = 'upload_image_ajax.tpl';

$bbcb_form_name = request_var('bbcb_form_name', 'post');
$bbcb_text_name = request_var('bbcb_text_name', 'message');

$template->assign_vars(array(
	'S_UPLOAD_DIR' => $upload_dir,
	'S_USER_UPLOAD_DIR' => $user_upload_dir,
	'S_AJAX_UPLOAD' => 'ajax_upload.' . PHP_EXT,
	'S_ALLOWED_EXTENSIONS' => $allowed_extensions,
	'S_MAX_FILE_SIZE' => $max_file_size,
	'S_THUMBNAIL_SIZE' => $album_config['thumbnail_size'],

	'U_PERSONAL_IMAGES' => append_sid(CMS_PAGE_IMAGES),
	'U_AJAX_GET_MORE_IMAGES' => 'ajax.' . PHP_EXT . '?mode=get_more_images&json=1&sid=' . $user->data['session_id'],
	'S_AJAX_PIC_START' => ($show_last_images && ($n_pics > 0)) ? $n_pics : 5,

	'BBCB_FORM_NAME' => htmlspecialchars($bbcb_form_name),
	'BBCB_TEXT_NAME' => htmlspecialchars($bbcb_text_name),

	'L_BBCODE' => $lang['BBCode'],
	'L_BBCODE_DES' => $lang['Uploaded_Image_BBC'],
	'L_UPLOAD_SUCCESS' => $lang['Uploaded_Image_Success'],
	'L_UPLOAD_ERROR' => $lang['Upload_File_Error'],
	'L_UPLOAD_ERROR_SIZE' => $lang['Upload_File_Error_Size'],
	'L_UPLOAD_ERROR_TYPE' => $lang['Upload_File_Error_Type'],
	'L_INSERT_BBC' => $lang['Upload_Insert_Image'],
	'L_CLOSE_WINDOW' => $lang['Upload_Close'],
	'IMG_BBCODE' => '[img]' . $server_path . substr($upload_dir, strlen(IP_ROOT_PATH)) . '___IMAGE___' . '[/img]',

	// Used in JS, we need to escape
	'L_UPLOADING_JS' => addslashes($lang['Uploading']),
	'L_ALLOWED_EXT_JS' => addslashes($lang['Upload_File_Type_Allowed'] . ': ' . str_replace('|', ', ', $allowed_extensions) . '.'),
	'IMG_LOADING_JS' => '<img src="' . IP_ROOT_PATH . 'images/loading.gif' . '" alt="' . addslashes(htmlspecialchars($lang['Uploading'])) . '" />',

	'L_UPLOADING' => $lang['Uploading'],
	'L_UPLOAD_IMAGE' => $lang['Upload_Image_Local'],
	'L_UPLOAD_IMAGE_EXPLAIN' => $lang['Upload_Image_Local_Explain'],
	'L_UPLOADED_IMAGES' => $lang['Uploaded_Images_Local'],
	'L_ALLOWED_EXT' => $lang['Upload_File_Type_Allowed'] . ': ' . str_replace('|', ', ', $allowed_extensions) . '.<br />' . $lang['Upload_File_Max_Size'] . ' ' . floor($max_file_size / 1024) . $lang['KB'] . '.',
	)
);

full_page_generation($template_to_parse, $lang['Upload_Image_Local'], '', '');

?>