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
	'L_ALLOWED_EXT' => $lang['Upload_File_Type_Allowed'] . ': ' . str_replace('|', ', ', $allowed_extensions) . '.<br />' . $lang['Upload_File_Max_Size'] . ' ' . floor($max_file_size / 1024) . $lang['KB'] . '.',
	)
);

full_page_generation($template_to_parse, $lang['Upload_Image_Local'], '', '');

?>