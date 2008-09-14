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
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

$album_config_tabs[] = array(
	'order' => 3,
	'selection' => 'upload',
	'title' => $lang['Upload_Settings'],
	'detail' => '',
	'sub_config' => array(
		/*
		0 => array(
			'order' => 0,
			'selection' => '',
			'title' => '',
			'detail' => ''
		)
		*/
	),
	'config_table_name' => ALBUM_CONFIG_TABLE,
	'generate_function' => 'album_generate_config_upload_box',
	'template_file' => ADM_TPL . 'album_config_upload_body.tpl'
);


function album_generate_config_upload_box($config_data)
{
	global $template, $lang, $new;

	//$template->assign_block_vars('switch_nuffload', array());

	$template->assign_vars(array(

		'MAX_PICS' => $new['max_pics'],
		'MAX_FILE_SIZE' => $new['max_file_size'],
		'MAX_FILE_SIZE_RESAMPLING' => $new['max_file_size_resampling'],
		'MAX_WIDTH' => $new['max_width'],
		'MAX_HEIGHT' => $new['max_height'],

		'MAX_FILES_TO_UPLOAD' => $new['max_files_to_upload'],
		'MAX_PREGENERATED_FIELDS'  => $new['max_pregenerated_fields'],

		'DYNFIELDS_ENABLED' => ($new['dynamic_fields'] == 1) ? 'checked="checked"' : '',
		'DYNFIELDS_DISABLED' => ($new['dynamic_fields'] == 0) ? 'checked="checked"' : '',

		'PREGENFIELDS_ENABLED' => ($new['pregenerate_fields'] == 1) ? 'checked="checked"' : '',
		'PREGENFIELDS_DISABLED' => ($new['pregenerate_fields'] == 0) ? 'checked="checked"' : '',

		'PROPERCASE_TITLE_ENABLED' => ($new['propercase_pic_title'] == 1) ? 'checked="checked"' : '',
		'PROPERCASE_TITLE_DISABLED' => ($new['propercase_pic_title'] == 0) ? 'checked="checked"' : '',

		'USER_PICS_LIMIT' => $new['user_pics_limit'],
		'MOD_PICS_LIMIT' => $new['mod_pics_limit'],

		'THUMBNAIL_CACHE_ENABLED' => ($new['thumbnail_cache'] == 1) ? 'checked="checked"' : '',
		'THUMBNAIL_CACHE_DISABLED' => ($new['thumbnail_cache'] == 0) ? 'checked="checked"' : '',

		'DYNAMIC_PIC_RESAMPLING_ENABLED' => ($new['dynamic_pic_resampling'] == 1) ? 'checked="checked"' : '',
		'DYNAMIC_PIC_RESAMPLING_DISABLED' => ($new['dynamic_pic_resampling'] == 0) ? 'checked="checked"' : '',

		'JPG_ENABLED' => ($new['jpg_allowed'] == 1) ? 'checked="checked"' : '',
		'JPG_DISABLED' => ($new['jpg_allowed'] == 0) ? 'checked="checked"' : '',

		'PNG_ENABLED' => ($new['png_allowed'] == 1) ? 'checked="checked"' : '',
		'PNG_DISABLED' => ($new['png_allowed'] == 0) ? 'checked="checked"' : '',

		'GIF_ENABLED' => ($new['gif_allowed'] == 1) ? 'checked="checked"' : '',
		'GIF_DISABLED' => ($new['gif_allowed'] == 0) ? 'checked="checked"' : '',

		'PIC_DESC_MAX_LENGTH' => $new['desc_length'],

		'NO_GD' => ($new['gd_version'] == 0) ? 'checked="checked"' : '',
		'GD_V1' => ($new['gd_version'] == 1) ? 'checked="checked"' : '',
		'GD_V2' => ($new['gd_version'] == 2) ? 'checked="checked"' : '',

		//--- Language Setup

		'L_MAX_PICS' => $lang['Max_pics'],
		'L_MAX_FILE_SIZE' => $lang['Max_file_size'],
		'L_MAX_FILE_SIZE_RESAMPLING' => $lang['Max_file_size_resampling'],
		'L_MAX_WIDTH' => $lang['Max_width'],
		'L_MAX_HEIGHT' => $lang['Max_height'],
		'L_DYNAMIC_PIC_RESAMPLING' => $lang['Pic_Resampling'],
		'L_USER_PICS_LIMIT' => $lang['User_pics_limit'],
		'L_MOD_PICS_LIMIT' => $lang['Moderator_pics_limit'],
		'L_MANUAL_THUMBNAIL' => $lang['Manual_thumbnail'],
		'L_JPG_ALLOWED' => $lang['JPG_allowed'],
		'L_PNG_ALLOWED' => $lang['PNG_allowed'],
		'L_GIF_ALLOWED' => $lang['GIF_allowed'],
		'L_PIC_DESC_MAX_LENGTH' => $lang['Pic_Desc_Max_Length'],
		'L_HOTLINK_PREVENT' => $lang['Hotlink_prevent'],
		'L_HOTLINK_ALLOWED' => $lang['Hotlink_allowed'],
		'L_GD_VERSION' => $lang['GD_version'],

		'L_MAX_FILES_TO_UPLOAD' => $lang['Max_Files_To_Upload'],
		'L_ALBUM_UPLOAD_SETTINGS' => $lang['Album_upload_settings'],
		'L_MAX_PREGENERATED_FIELDS' => $lang['Max_pregenerated_fields'],
		'L_DYN_GENERATE_FIELDS' => $lang['Dynamic_field_generation'],
		'L_PRE_GENERATE_FIELDS' => $lang['Pre_generate_fields'],
		'L_PROPERCASE_TITLE' => $lang['Propercase_pic_title'],

		'L_DISABLED' => $lang['Disabled'],
		'L_ENABLED' => $lang['Enabled'],
		'L_YES' => $lang['Yes'],
		'L_NO' => $lang['No'],

		// Nuffload
		'L_PROGRESS_BAR_CONFIG' => $lang['progress_bar_configuration'],
		'L_MULTIPLE_UPLOADS_CONFIG' => $lang['multiple_uploads_configuration'],
		'L_RESIZE_PICS_CONFIG' => $lang['image_resizing_configuration'],
		'L_ALBUM_NUFFLOAD_CONFIG' => $lang['Nuffload_Config'],
		'L_ENABLE_NUFFLOAD' => $lang['Enable_Nuffload'],
		'L_ENABLE_NUFFLOAD_EXPLAIN' => $lang['Enable_Nuffload_Explain'],

		'L_PERL_UPLOADER' => $lang['perl_uploader'],
		'L_PATH_TO_BIN' => $lang['path_to_bin'],
		'L_SHOW_PROGRESS_BAR' => $lang['show_progress_bar'],
		'L_CLOSE_ON_FINISH' => $lang['close_progress_bar'],
		'L_MAX_PAUSE' => $lang['activity_timeout'],
		'L_SIMPLE_FORMAT' => $lang['simple_format'],
		'L_MULTIPLE_UPLOADS' => $lang['multiple_uploads'],
		'L_MAX_UPLOADS' => $lang['max_uploads'],
		'L_ZIP_UPLOADS' => $lang['zip_uploads'],
		'L_RESIZE_PIC' => $lang['image_resizing'],
		'L_RESIZE_WIDTH' => $lang['image_width'],
		'L_RESIZE_HEIGHT' => $lang['image_height'],
		'L_RESIZE_QUALITY' => $lang['image_quality'],

		'NUFFLOAD_ENABLED' => ($new['switch_nuffload'] == 1) ? 'checked="checked"' : '',
		'NUFFLOAD_DISABLED' => ($new['switch_nuffload'] == 0) ? 'checked="checked"' : '',
		'PERL_UPLOADER_ENABLED' => ($new['perl_uploader'] == 1) ? 'checked="checked"' : '',
		'PERL_UPLOADER_DISABLED' => ($new['perl_uploader'] == 0) ? 'checked="checked"' : '',
		'PATH_TO_BIN' => $new['path_to_bin'],
		'SHOW_PROGRESS_BAR_ENABLED' => ($new['show_progress_bar'] == 1) ? 'checked="checked"' : '',
		'SHOW_PROGRESS_BAR_DISABLED' => ($new['show_progress_bar'] == 0) ? 'checked="checked"' : '',
		'CLOSE_ON_FINISH_ENABLED' => ($new['close_on_finish'] == 1) ? 'checked="checked"' : '',
		'CLOSE_ON_FINISH_DISABLED' => ($new['close_on_finish'] == 0) ? 'checked="checked"' : '',
		'MAX_PAUSE' => $new['max_pause'],
		'SIMPLE_FORMAT_ENABLED' => ($new['simple_format'] == 1) ? 'checked="checked"' : '',
		'SIMPLE_FORMAT_DISABLED' => ($new['simple_format'] == 0) ? 'checked="checked"' : '',
		'MULTIPLE_UPLOADS_ENABLED' => ($new['multiple_uploads'] == 1) ? 'checked="checked"' : '',
		'MULTIPLE_UPLOADS_DISABLED' => ($new['multiple_uploads'] == 0) ? 'checked="checked"' : '',
		'MAX_UPLOADS' => $new['max_uploads'],
		'ZIP_UPLOADS_ENABLED' => ($new['zip_uploads'] == 1) ? 'checked="checked"' : '',
		'ZIP_UPLOADS_DISABLED' => ($new['zip_uploads'] == 0) ? 'checked="checked"' : '',
		'RESIZE_PIC_ENABLED' => ($new['resize_pic'] == 1) ? 'checked="checked"' : '',
		'RESIZE_PIC_DISABLED' => ($new['resize_pic'] == 0) ? 'checked="checked"' : '',
		'RESIZE_WIDTH' => $new['resize_width'],
		'RESIZE_HEIGHT' => $new['resize_height'],
		'RESIZE_QUALITY' => $new['resize_quality'],

		'L_GUEST' => $lang['Forum_ALL'],
		'L_REG' => $lang['Forum_REG'],
		'L_PRIVATE' => $lang['Forum_PRIVATE'],
		'L_MOD' => $lang['Forum_MOD'],
		'L_ADMIN' => $lang['Forum_ADMIN'],

		'S_GUEST' => ALBUM_GUEST,
		'S_USER' => ALBUM_USER,
		'S_PRIVATE' => ALBUM_PRIVATE,
		'S_MOD' => ALBUM_MOD,
		'S_ADMIN' => ALBUM_ADMIN,
		)
	);
}
?>