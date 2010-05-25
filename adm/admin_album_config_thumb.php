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
* IdleVoid (idlevoid@slater.dk)
* Volodymyr (CLowN) Skoryk (blaatimmy72@yahoo.com)
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

$album_config_tabs[] =  array(
	'order' => 4,
	'selection' => 'thumb',
	'title' => $lang['Thumbnail_Settings'],
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
	'generate_function' => 'album_generate_config_thumb_box',
	'template_file' => ADM_TPL . 'album_config_thumb_body.tpl'
);

function album_generate_config_thumb_box($config_data)
{
	global $template, $lang, $new;

	$template->assign_vars(array(

		// Old thumbnails functions
		'L_USE_OLD_PICS_GEN' => $lang['Use_Old_Thumbnails'],
		'L_USE_OLD_PICS_GEN_EXPLAIN' => $lang['Use_Old_Thumbnails_Explain'],
		'USE_OLD_PICS_GEN_ENABLED' => ($new['use_old_pics_gen'] == 1) ? 'checked="checked"' : '',
		'USE_OLD_PICS_GEN_DISABLED' => ($new['use_old_pics_gen'] == 0) ? 'checked="checked"' : '',

		// Mid thumbnail
		'L_MIDTHUMB_USE' => $lang['SP_Midthumb_use'],
		'MIDTHUMB_ENABLED' => ($new['midthumb_use'] == 1) ? 'checked="checked"' : '',
		'MIDTHUMB_DISABLED' => ($new['midthumb_use'] == 0) ? 'checked="checked"' : '',

		// Mid thumbnail cache
		'L_MIDTHUMB_CACHE' => $lang['SP_Midthumb_cache'],
		'MIDTHUMB_CACHE_ENABLED' => ($new['midthumb_cache'] == 1) ? 'checked="checked"' : '',
		'MIDTHUMB_CACHE_DISABLED' => ($new['midthumb_cache'] == 0) ? 'checked="checked"' : '',

		// Size of midthumbnail
		'L_MIDTHUMB_HEIGHT' => $lang['SP_Midthumb_high'],
		'MIDTHUMB_HEIGHT' => $new['midthumb_height'],
		'L_MIDTHUMB_WIDTH' => $lang['SP_Midthumb_width'],
		'MIDTHUMB_WIDTH' => $new['midthumb_width'],

		// Thumbs
		'ROWS_PER_PAGE' => $new['rows_per_page'],
		'COLS_PER_PAGE' => $new['cols_per_page'],

		'THUMBNAIL_QUALITY' => $new['thumbnail_quality'],
		'THUMBNAIL_SIZE' => $new['thumbnail_size'],
		'THUMBNAIL_CACHE_ENABLED' => ($new['thumbnail_cache'] == 1) ? 'checked="checked"' : '',
		'THUMBNAIL_CACHE_DISABLED' => ($new['thumbnail_cache'] == 0) ? 'checked="checked"' : '',
		'QUICK_THUMBNAILS_ENABLED' => ($new['quick_thumbs'] == 1) ? 'checked="checked"' : '',
		'QUICK_THUMBNAILS_DISABLED' => ($new['quick_thumbs'] == 0) ? 'checked="checked"' : '',

		'SORT_TIME' => ($new['sort_method'] == 'pic_time') ? 'selected="selected"' : '',
		'SORT_PIC_TITLE' => ($new['sort_method'] == 'pic_title') ? 'selected="selected"' : '',
		'SORT_USERNAME' => ($new['sort_method'] == 'pic_user_id') ? 'selected="selected"' : '',
		'SORT_VIEW' => ($new['sort_method'] == 'pic_view_count') ? 'selected="selected"' : '',
		'SORT_RATING' => ($new['sort_method'] == 'rating') ? 'selected="selected"' : '',
		'SORT_COMMENTS' => ($new['sort_method'] == 'comments') ? 'selected="selected"' : '',
		'SORT_NEW_COMMENT' => ($new['sort_method'] == 'new_comment') ? 'selected="selected"' : '',

		'SORT_ASC' => ($new['sort_order'] == 'ASC') ? 'selected="selected"' : '',
		'SORT_DESC' => ($new['sort_order'] == 'DESC') ? 'selected="selected"' : '',

		'FULLPIC_POPUP_ENABLED' => ($new['fullpic_popup'] == 1) ? 'checked="checked"' : '',
		'FULLPIC_POPUP_DISABLED' => ($new['fullpic_popup'] == 0) ? 'checked="checked"' : '',

		'SHOW_IMG_NO_GD_ENABLED' => ($new['show_img_no_gd'] == 1) ? 'checked="checked"' : '',
		'SHOW_IMG_NO_GD_DISABLED' => ($new['show_img_no_gd'] == 0) ? 'checked="checked"' : '',

		'SHOW_GIF_MID_THUMB_ENABLED' => ($new['show_gif_mid_thumb'] == 1) ? 'checked="checked"' : '',
		'SHOW_GIF_MID_THUMB_DISABLED' => ($new['show_gif_mid_thumb'] == 0) ? 'checked="checked"' : '',

		'SHOW_PIC_SIZE_ENABLED' => ($new['show_pic_size_on_thumb'] == 1) ? 'checked="checked"' : '',
		'SHOW_PIC_SIZE_DISABLED' => ($new['show_pic_size_on_thumb'] == 0) ? 'checked="checked"' : '',

		'LB_PREVIEW_ENABLED' => ($new['lb_preview'] == 1) ? 'checked="checked"' : '',
		'LB_PREVIEW_DISABLED' => ($new['lb_preview'] == 0) ? 'checked="checked"' : '',

		//--- Language setup ---

		'L_ROWS_PER_PAGE' => $lang['Rows_per_page'],
		'L_COLS_PER_PAGE' => $lang['Cols_per_page'],

		'L_THUMBNAIL_QUALITY' => $lang['Thumbnail_quality'],
		'L_THUMBNAIL_SIZE' => $lang['Thumbnail_size'],
		'L_THUMBNAIL_CACHE' => $lang['Thumbnail_cache'],
		'L_QUICK_THUMBNAILS' => $lang['Quick_Thumbnails'],
		'L_QUICK_THUMBNAILS_EXPLAIN' => $lang['Quick_Thumbnails_explain'],

		'L_DEFAULT_SORT_METHOD' => $lang['Default_Sort_Method'],
		'L_TIME' => $lang['Time'],
		'L_PIC_TITLE' => $lang['Pic_Title'],
		'L_USERNAME' => $lang['SORT_USERNAME'],
		'L_VIEW' => $lang['View'],
		'L_RATING' => $lang['Rating'],
		'L_COMMENTS' => $lang['Comments'],
		'L_NEW_COMMENT' => $lang['New_Comment'],
		'L_DEFAULT_SORT_ORDER' => $lang['Default_Sort_Order'],
		'L_ASC' => $lang['Sort_Ascending'],
		'L_DESC' => $lang['Sort_Descending'],
		'L_FULLPIC_POPUP' => $lang['Fullpic_Popup'],
		'L_SHOW_IMG_NO_GD' => $lang['Show_IMG_No_GD'],
		'L_SHOW_GIF_MID_THUMB' => $lang['Show_GIF_MidThumb'],
		'L_SHOW_PIC_SIZE' => $lang['Show_Pic_Size'],
		'L_LB_PREVIEW' => $lang['LB_Preview'],
		'L_LB_PREVIEW_EXPLAIN' => $lang['LB_Preview_Explain'],

		'L_DISABLED' => $lang['Disabled'],
		'L_ENABLED' => $lang['Enabled'],
		'L_YES' => $lang['Yes'],
		'L_NO' => $lang['No']
		)
	);
}
?>