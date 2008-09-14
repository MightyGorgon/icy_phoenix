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

$album_config_tabs[] =  array(
	'order' => 1,
	'selection' => 'index',
	'title' => $lang['Album_Index_Settings'],
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
	'generate_function' => 'album_generate_config_index_box',
	'template_file' => ADM_TPL . 'album_config_index_body.tpl'
);

function album_generate_config_index_box($config_data)
{
	global $template, $lang, $new;

	$template->assign_vars(array(
		// Index
		'INDEX_SHOW_SUBCATS_ENABLED' => ($new['show_index_subcats'] == 1) ? 'checked="checked"' : '',
		'INDEX_SHOW_SUBCATS_DISABLED' => ($new['show_index_subcats'] == 0) ? 'checked="checked"' : '',
		'INDEX_THUMB_ENABLED' => ($new['show_index_thumb'] == 1) ? 'checked="checked"' : '',
		'INDEX_THUMB_DISABLED' => ($new['show_index_thumb'] == 0) ? 'checked="checked"' : '',
		'INDEX_TOTAL_PICS_ENABLED' => ($new['show_index_total_pics'] == 1) ? 'checked="checked"' : '',
		'INDEX_TOTAL_PICS_DISABLED' => ($new['show_index_total_pics'] == 0) ? 'checked="checked"' : '',
		'INDEX_TOTAL_COMMENTS_ENABLED' => ($new['show_index_total_comments'] == 1) ? 'checked="checked"' : '',
		'INDEX_TOTAL_COMMENTS_DISABLED' => ($new['show_index_total_comments'] == 0) ? 'checked="checked"' : '',
		'INDEX_PICS_ENABLED' => ($new['show_index_pics'] == 1) ? 'checked="checked"' : '',
		'INDEX_PICS_DISABLED' => ($new['show_index_pics'] == 0) ? 'checked="checked"' : '',
		'INDEX_COMMENTS_ENABLED' => ($new['show_index_comments'] == 1) ? 'checked="checked"' : '',
		'INDEX_COMMENTS_DISABLED' => ($new['show_index_comments'] == 0) ? 'checked="checked"' : '',
		'INDEX_LAST_COMMENT_ENABLED' => ($new['show_index_last_comment'] == 1) ? 'checked="checked"' : '',
		'INDEX_LAST_COMMENT_DISABLED' => ($new['show_index_last_comment'] == 0) ? 'checked="checked"' : '',
		'INDEX_LAST_PIC_ENABLED' => ($new['show_index_last_pic'] == 1) ? 'checked="checked"' : '',
		'INDEX_LAST_PIC_DISABLED' => ($new['show_index_last_pic'] == 0) ? 'checked="checked"' : '',
		'INDEX_LAST_PIC_LV_ENABLED' => ($new['show_index_last_pic_lv'] == 1) ? 'checked="checked"' : '',
		'INDEX_LAST_PIC_LV_DISABLED' => ($new['show_index_last_pic_lv'] == 0) ? 'checked="checked"' : '',
		'INDEX_LINEBREAK_ENABLED' => ($new['line_break_subcats'] == 1) ? 'checked="checked"' : '',
		'INDEX_LINEBREAK_DISABLED' => ($new['line_break_subcats'] == 0) ? 'checked="checked"' : '',

		'INDEX_SHOW_PERSONAL_GALLERY_LINK_ENABLED' => ($new['show_personal_gallery_link'] == 1) ? 'checked="checked"' : '',
		'INDEX_SHOW_PERSONAL_GALLERY_LINK_DISABLED' => ($new['show_personal_gallery_link'] == 0) ? 'checked="checked"' : '',
		'NEW_PIC_CHECK_INTERVAL' => $new['new_pic_check_interval'],
		'INDEX_SUPERCELLS_ENABLED' => ($new['index_enable_supercells'] == 1) ? 'checked="checked"' : '',
		'INDEX_SUPERCELLS_DISABLED' => ($new['index_enable_supercells'] == 0) ? 'checked="checked"' : '',

		'L_INDEX_SHOW_SUBCATS' => $lang['Show_Index_Subcats'],
		'L_INDEX_THUMB' => $lang['Show_Index_Thumb'],
		'L_INDEX_TOTAL_PICS' => $lang['Show_Index_Total_Pics'],
		'L_INDEX_TOTAL_COMMENTS' => $lang['Show_Index_Total_Comments'],
		'L_INDEX_PICS' => $lang['Show_Index_Pics'],
		'L_INDEX_COMMENTS' => $lang['Show_Index_Comments'],
		'L_INDEX_LAST_COMMENT' => $lang['Show_Index_Last_Comment'],
		'L_INDEX_LAST_PIC' => $lang['Show_Index_Last_Pic'],
		'L_INDEX_LINEBREAK_SUBCATS' => $lang['Line_Break_Subcats'],

		'L_SHOW_PERSONAL_GALLERY_LINK' => $lang['Show_Personal_Gallery_Link'],

		'L_NEW_PIC_CHECK_INTERVAL' => $lang['New_Pic_Check_Interval'],
		'L_NEW_PIC_CHECK_INTERVAL_DESC' => $lang['New_Pic_Check_Interval_Desc'],
		'L_NEW_PIC_CHECK_INTERVAL_LV' => $lang['New_Pic_Check_Interval_LV'],
		'L_ENABLE_SUPERCELLS' => $lang['Enable_Index_Supercells'],

		// Display OTF Link
		'L_SHOW_OTF_LINK' => $lang['Show_OTF_Link'],
		'SHOW_OTF_LINK_ENABLED' => ($new['show_otf_link'] == 1) ? 'checked="checked"' : '',
		'SHOW_OTF_LINK_DISABLED' => ($new['show_otf_link'] == 0) ? 'checked="checked"' : '',

		// Display All Pics Link
		'L_SHOW_ALL_PICS_LINK' => $lang['Show_AllPics_Link'],
		'SHOW_ALL_PICS_LINK_ENABLED' => ($new['show_all_pics_link'] == 1) ? 'checked="checked"' : '',
		'SHOW_ALL_PICS_LINK_DISABLED' => ($new['show_all_pics_link'] == 0) ? 'checked="checked"' : '',

		// Display PG Link
		'L_SHOW_PERSONAL_GALLERIES_LINK' => $lang['Show_PG_Link'],
		'SHOW_PERSONAL_GALLERIES_LINK_ENABLED' => ($new['show_personal_galleries_link'] == 1) ? 'checked="checked"' : '',
		'SHOW_PERSONAL_GALLERIES_LINK_DISABLED' => ($new['show_personal_galleries_link'] == 0) ? 'checked="checked"' : '',

		// Display All Pics Link
		'L_SHOW_LAST_COMMENTS' => $lang['Show_Last_Comments'],
		'SHOW_LAST_COMMENTS_ENABLED' => ($new['show_last_comments'] == 1) ? 'checked="checked"' : '',
		'SHOW_LAST_COMMENTS_DISABLED' => ($new['show_last_comments'] == 0) ? 'checked="checked"' : '',

		// Display latest
		'L_DISPLAY_LATEST' => $lang['SP_Display_latest'],
		'DISPLAY_LATEST_ENABLED' => ($new['disp_late'] == 1) ? 'checked="checked"' : '',
		'DISPLAY_LATEST_DISABLED' => ($new['disp_late'] == 0) ? 'checked="checked"' : '',

		// Display highest
		'L_DISPLAY_HIGHEST' => $lang['SP_Display_highest'],
		'DISPLAY_HIGHEST_ENABLED' => ($new['disp_high'] == 1) ? 'checked="checked"' : '',
		'DISPLAY_HIGHEST_DISABLED' => ($new['disp_high'] == 0) ? 'checked="checked"' : '',

		// Display most viewed
		'L_DISPLAY_MOST_VIEWED' => $lang ['SP_Display_most_viewed'],
		'DISPLAY_MOST_VIEWED_ENABLED' => ($new['disp_mostv'] == 1) ? 'checked="checked"' : '',
		'DISPLAY_MOST_VIEWED_DISABLED' => ($new['disp_mostv'] == 0) ? 'checked="checked"' : '',

		// Display random
		'L_DISPLAY_RANDOM' => $lang['SP_Display_random'],
		'DISPLAY_RANDOM_ENABLED' => ($new['disp_rand'] == 1) ? 'checked="checked"' : '',
		'DISPLAY_RANDOM_DISABLED' => ($new['disp_rand'] == 0) ? 'checked="checked"' : '',

		// Display last comments
		'L_SHOW_LAST_COMMENTS' => $lang['Show_Last_Comments'],
		'SHOW_LAST_COMMENTS_ENABLED' => ($new['show_last_comments'] == 1) ? 'checked="checked"' : '',
		'SHOW_LAST_COMMENTS_DISABLED' => ($new['show_last_comments'] == 0) ? 'checked="checked"' : '',

		// How many pics
		'L_PIC_ROW' => $lang['SP_Pic_row'],
		'L_PIC_COL' => $lang['SP_Pic_col'],
		'PIC_ROW' => $new['img_rows'],
		'PIC_COL' => $new['img_cols'],

		'L_DISABLED' => $lang['Disabled'],
		'L_ENABLED' => $lang['Enabled'],
		'L_YES' => $lang['Yes'],
		'L_NO' => $lang['No']
		)
	);
}
?>