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
*
*/

if (!defined('IN_PHPBB'))
{
	die('Hacking attempt');
}

$album_config_tabs[] =  array(
	'order' => 2,
	'selection' => 'personal',
	'title' => $lang['Album_Personal_Settings'],
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
	'generate_function' => 'album_generate_config_personal_box',
	'template_file' => $acp_prefix . 'album_config_personal_body.tpl'
	);

function album_generate_config_personal_box($config_data)
{
	global $template, $lang, $new;

	$template->assign_vars(array(

		'PERSONAL_GALLERY_LIMIT' => $new['personal_gallery_limit'],

		'PERSONAL_GALLERY_USER' => ($new['personal_gallery'] == ALBUM_USER) ? 'checked="checked"' : '',
		'PERSONAL_GALLERY_PRIVATE' => ($new['personal_gallery'] == ALBUM_PRIVATE) ? 'checked="checked"' : '',
		'PERSONAL_GALLERY_ADMIN' => ($new['personal_gallery'] == ALBUM_ADMIN) ? 'checked="checked"' : '',

		'PERSONAL_GALLERY_VIEW_ALL' => ($new['personal_gallery_view'] == ALBUM_GUEST) ? 'checked="checked"' : '',
		'PERSONAL_GALLERY_VIEW_REG' => ($new['personal_gallery_view'] == ALBUM_USER) ? 'checked="checked"' : '',
		'PERSONAL_GALLERY_VIEW_PRIVATE' => ($new['personal_gallery_view'] == ALBUM_PRIVATE) ? 'checked="checked"' : '',

		'PERSONAL_PICS_APPROVAL_DISABLED' => ($new['personal_pics_approval'] == ALBUM_USER) ? 'checked="checked"' : '',
		'PERSONAL_PICS_APPROVAL_MOD' => ($new['personal_pics_approval'] == ALBUM_MOD) ? 'checked="checked"' : '',
		'PERSONAL_PICS_APPROVAL_ADMIN' => ($new['personal_pics_approval'] == ALBUM_ADMIN) ? 'checked="checked"' : '',

		'PERSONAL_SHOW_RECENT_IN_SUBCATS_ENABLED' => ($new['personal_show_recent_in_subcats'] == 1) ? 'checked="checked"' : '',
		'PERSONAL_SHOW_RECENT_IN_SUBCATS_DISABLED' => ($new['personal_show_recent_in_subcats'] == 0) ? 'checked="checked"' : '',
		'PERSONAL_SHOW_RECENT_INSTEAD_OF_NOPICS_ENABLED' => ($new['personal_show_recent_instead_of_nopics'] == 1) ? 'checked="checked"' : '',
		'PERSONAL_SHOW_RECENT_INSTEAD_OF_NOPICS_DISABLED' => ($new['personal_show_recent_instead_of_nopics'] == 0) ? 'checked="checked"' : '',
		'PERSONAL_MOD_ENABLED' => ($new['personal_allow_gallery_mod'] == 1) ? 'checked="checked"' : '',
		'PERSONAL_MOD_DISABLED' => ($new['personal_allow_gallery_mod'] == 0) ? 'checked="checked"' : '',
		'PERSONAL_SUBCAT_ENABLED' => ($new['personal_allow_sub_categories'] == 1) ? 'checked="checked"' : '',
		'PERSONAL_SUBCAT_DISABLED' => ($new['personal_allow_sub_categories'] == 0) ? 'checked="checked"' : '',
		'PERSONAL_SUB_GALLERY_LIMIT' => $new['personal_sub_category_limit'],
		'PERSONAL_SHOW_SUBCATS_ENABLED' => ($new['personal_show_subcats_in_index'] == 1) ? 'checked="checked"' : '',
		'PERSONAL_SHOW_SUBCATS_DISABLED' => ($new['personal_show_subcats_in_index'] == 0) ? 'checked="checked"' : '',
		'PERSONAL_SHOW_ALL_PICS_ENABLED' => ($new['show_all_in_personal_gallery'] == 1) ? 'checked="checked"' : '',
		'PERSONAL_SHOW_ALL_PICS_DISABLED' => ($new['show_all_in_personal_gallery'] == 0) ? 'checked="checked"' : '',
		'PERSONAL_ALLOW_AVATARS_ENABLED' => ($new['personal_allow_avatar_gallery'] == 1) ? 'checked="checked"' : '',
		'PERSONAL_ALLOW_AVATARS_DISABLED' => ($new['personal_allow_avatar_gallery'] == 0) ? 'checked="checked"' : '',

		'S_GUEST' => ALBUM_GUEST,
		'S_USER' => ALBUM_USER,
		'S_PRIVATE' => ALBUM_PRIVATE,
		'S_MOD' => ALBUM_MOD,
		'S_ADMIN' => ALBUM_ADMIN,

		//--- Language setup ---

		'L_GUEST' => $lang['Forum_ALL'],
		'L_REG' => $lang['Forum_REG'],
		'L_PRIVATE' => $lang['Forum_PRIVATE'],
		'L_MOD' => $lang['Forum_MOD'],
		'L_ADMIN' => $lang['Forum_ADMIN'],

		'L_PERSONAL_GALLERY' => $lang['Personal_gallery'],
		'L_PERSONAL_GALLERY_LIMIT' => $lang['Personal_gallery_limit'],
		'L_PERSONAL_GALLERY_VIEW' => $lang['Personal_gallery_view'],

		'L_ALBUM_PERSONAL_PICS_APPROVAL' => $lang['Personal_Gallery_Approval'],
		'L_ALBUM_PERSONAL_MODERATOR' => $lang['Personal_Gallery_MOD'],
		'L_PERSONAL_SUB_GALLERY_LIMIT' => $lang['Personal_Sub_Cat_Limit'],
		'L_PERSONAL_ALLOW_SUB_CATEGORY' => $lang['User_Can_Create_Personal_SubCats'],
		'L_PERSONAL_SHOW_SUBCATS' => $lang['Show_Personal_Sub_Cats'],
		'L_PERSONAL_SHOW_RECENT_IN_SUBCATS' => $lang['Show_Recent_In_Personal_Subcats'],
		'L_PERSONAL_SHOW_RECENT_INSTEAD_OF_NOPICS' => $lang['Show_Recent_Instead_of_Personal_NoPics'],

		'L_ALBUM_DEBUG_MODE' => $lang['Album_debug_mode'],
		'L_PERSONAL_SHOW_ALL_PICS' => $lang['Enable_Show_All_Pics'],
		'L_PERSONAL_ALLOW_AVATARS' => $lang['Allow_Album_Avatars'],

		'L_DISABLED' => $lang['Disabled'],
		'L_ENABLED' => $lang['Enabled'],
		'L_YES' => $lang['Yes'],
		'L_NO' => $lang['No'])
	);
}
?>