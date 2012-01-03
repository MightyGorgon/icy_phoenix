<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

// Usage
/*
// BBCBMG - BEGIN
define('IN_ICYPHOENIX', true);
//$bbcbmg_in_acp = true;
include(IP_ROOT_PATH . 'includes/bbcb_mg.' . PHP_EXT);
$template->assign_var_from_handle('BBCB_MG', 'bbcb_mg');
// BBCBMG - END
*/

setup_extra_lang(array('lang_bbcb_mg'));

// This page is not in layout special...
$cms_page_id_tmp = 'pic_upload';
$cms_auth_level_tmp = (isset($config['auth_view_pic_upload']) ? $config['auth_view_pic_upload'] : AUTH_ALL);
$view_pic_upload = check_page_auth($cms_page_id_tmp, $cms_auth_level_tmp, true);

if (defined('BBCB_MG_SMALL'))
{
	$template->set_filenames(array('bbcb_mg' => 'bbcb_mg_small.tpl'));
}
elseif (defined('BBCB_MG_CUSTOM'))
{
	$template->set_filenames(array('bbcb_mg' => 'bbcb_mg_custom.tpl'));
}
else
{
	$template->set_filenames(array('bbcb_mg' => 'bbcb_mg.tpl'));
}

if (!empty($config['enable_postimage_org']))
{
	$template->assign_var('S_POSTIMAGE_ORG', true);
}

if (!empty($config['enable_colorpicker']))
{
	$template->assign_var('S_COLORPICKER', true);
}

$allowed_langs = array('dutch', 'german', 'italian', 'spanish');
if (in_array($config['default_lang'], $allowed_langs))
{
	$post_image_lang = 'javascript:void(0);" onclick="window.open(\'http://www.postimage.org/index.php?mode=phpbb&lang=' . $config['default_lang'] . '&tpl=.&forumurl=\' + escape(document.location.href),\'_imagehost\',\'width=500,height=400,resizable=yes\');';
}
else
{
	$post_image_lang = 'javascript:void(0);" onclick="window.open(\'http://www.postimage.org/index.php?mode=phpbb&tpl=.&forumurl=\' + escape(document.location.href), \'_imagehost\',\'width=500,height=400,resizable=yes\');';
}

if (empty($s_disable_bbc_special_content))
{
	$template->assign_var('S_BBC_SPECIAL_CONTENT', true);
}

if (!empty($config['switch_bbcb_active_content']))
{
	$template->assign_var('S_BBC_ACTIVE_CONTENT', true);
}

if ($view_pic_upload == true)
{
	$template->assign_var('S_PIC_UPLOAD', true);
}

$bbcbmg_path_prefix = '';
if (isset($bbcbmg_in_acp))
{
	// Mighty Gorgon: if we use absolute path this is not needed...
	//$bbcbmg_path_prefix = ($bbcbmg_in_acp == true) ? '../' : '';
}

$external_form_url_append = array();
if (!empty($template->vars['BBCB_FORM_NAME']))
{
	$external_form_url_append[] = 'bbcb_form_name=' . htmlspecialchars($template->vars['BBCB_FORM_NAME']);
}
if (!empty($template->vars['BBCB_TEXT_NAME']))
{
	$external_form_url_append[] = 'bbcb_text_name=' . htmlspecialchars($template->vars['BBCB_TEXT_NAME']);
}
$external_form_url_append_text = trim(implode('&amp;', $external_form_url_append));

$template->assign_vars(array(
	'JAVASCRIPT_LANG_VARS' => $lang['JAVASCRIPT_LANG_VARS'],
	'BBCB_MG_PATH_PREFIX' => $bbcbmg_path_prefix,
	//'BBCB_MG_IMG_PATH' => $bbcbmg_path_prefix . 'images/bbcb_mg/images/',
	//'BBCB_MG_IMG_PATH' => $bbcbmg_path_prefix . 'images/bbcb_mg/images/png/',
	//'BBCB_MG_IMG_EXT' => '.png',
	'BBCB_MG_IMG_PATH' => $bbcbmg_path_prefix . 'images/bbcb_mg/images/gif/',
	'BBCB_MG_IMG_EXT' => '.gif',

	'L_MORE_SMILIES' => $lang['More_emoticons'],
	'U_MORE_SMILIES' => append_sid('posting.' . PHP_EXT . '?mode=smilies'),

	'L_BBCODE_HELP' => $lang['bbcode_help'],
	'U_BBCODE_HELP' => append_sid('faq.' . PHP_EXT . '?mode=bbcode'),

	'U_BBCODE_COLORPICKER' => append_sid('bbcb_mg_cp.' . PHP_EXT),
	'U_BBCODE_POSTIMAGE' => $post_image_lang,
	//'U_BBCODE_POSTICYIMAGE' => append_sid('upload.' . PHP_EXT),
	'U_BBCODE_POSTICYIMAGE' => append_sid('upload_ajax.' . PHP_EXT . (!empty($external_form_url_append_text) ? ('?' . $external_form_url_append_text) : '')),

	'L_BBCODE_B_HELP' => $lang['bbcode_b_help'],
	'L_BBCODE_I_HELP' => $lang['bbcode_i_help'],
	'L_BBCODE_U_HELP' => $lang['bbcode_u_help'],
	'L_BBCODE_Q_HELP' => $lang['bbcode_q_help'],
	'L_BBCODE_C_HELP' => $lang['bbcode_c_help'],
	'L_BBCODE_L_HELP' => $lang['bbcode_l_help'],
	'L_BBCODE_O_HELP' => $lang['bbcode_o_help'],
	'L_BBCODE_P_HELP' => $lang['bbcode_p_help'],
	'L_BBCODE_W_HELP' => $lang['bbcode_w_help'],

	'L_BBCODE_URL' => $lang['bbcode_url'],
	'L_BBCODE_URL_TITLE' => $lang['bbcode_url_title'],
	'L_BBCODE_URL_EMPTY' => $lang['bbcode_url_empty'],
	'L_BBCODE_URL_ERRORS' => $lang['bbcode_url_errors'],

	'L_BBCODE_A_HELP' => $lang['bbcode_a_help'],
	'L_BBCODE_S_HELP' => $lang['bbcode_s_help'],
	'L_BBCODE_F_HELP' => $lang['bbcode_f_help'],
	'L_EMPTY_MESSAGE' => $lang['Empty_message'],
	'L_MESSAGE_TOO_SHORT' => $lang['Message_too_short'],

	// Smiley Creator
	'L_BBCODE_SC_HELP' => $lang['bbcode_sc_help'],
	'L_SMILEY_CREATOR' => $lang['Smiley_creator'],

	'L_FONT_COLOR' => $lang['Font_color'],
	'L_COLOR_DEFAULT' => $lang['color_default'],
	'L_COLOR_DARK_RED' => $lang['color_dark_red'],
	'L_COLOR_RED' => $lang['color_red'],
	'L_COLOR_ORANGE' => $lang['color_orange'],
	'L_COLOR_BROWN' => $lang['color_brown'],
	'L_COLOR_YELLOW' => $lang['color_yellow'],
	'L_COLOR_GREEN' => $lang['color_green'],
	'L_COLOR_OLIVE' => $lang['color_olive'],
	'L_COLOR_CYAN' => $lang['color_cyan'],
	'L_COLOR_BLUE' => $lang['color_blue'],
	'L_COLOR_DARK_BLUE' => $lang['color_dark_blue'],
	'L_COLOR_INDIGO' => $lang['color_indigo'],
	'L_COLOR_VIOLET' => $lang['color_violet'],
	'L_COLOR_WHITE' => $lang['color_white'],
	'L_COLOR_BLACK' => $lang['color_black'],

	'L_COLOR_CADET_BLUE' => $lang['color_cadet_blue'],
	'L_COLOR_CORAL' => $lang['color_coral'],
	'L_COLOR_CRIMSON' => $lang['color_crimson'],
	'L_COLOR_DARK_ORCHID' => $lang['color_dark_orchid'],
	'L_COLOR_DARK_GREY' => $lang['color_dark_grey'],
	'L_COLOR_GOLD' => $lang['color_gold'],
	'L_COLOR_GRAY' => $lang['color_gray'],
	'L_COLOR_LIGHT_BLUE' => $lang['color_light_blue'],
	'L_COLOR_LIGHT_CYAN' => $lang['color_light_cyan'],
	'L_COLOR_LIGHT_GREEN' => $lang['color_light_green'],
	'L_COLOR_LIGHT_GREY' => $lang['color_light_grey'],
	'L_COLOR_LIGHT_ORANGE' => $lang['color_light_orange'],
	'L_COLOR_PEACH' => $lang['color_peach'],
	'L_COLOR_POWER_ORANGE' => $lang['color_power_orange'],
	'L_COLOR_SEA_GREEN' => $lang['color_sea_green'],
	'L_COLOR_SILVER' => $lang['color_silver'],
	'L_COLOR_TOMATO' => $lang['color_tomato'],
	'L_COLOR_TURQUOISE' => $lang['color_turquoise'],
	'L_COLOR_CHOCOLATE' => $lang['color_chocolate'],
	'L_COLOR_DEEPSKYBLUE' => $lang['color_deepskyblue'],
	'L_COLOR_MIDNIGHTBLUE' => $lang['color_midnightblue'],
	'L_COLOR_DARKGREEN' => $lang['color_darkgreen'],

	'L_FONT_SIZE' => $lang['Font_size'],
	'L_FONT_TINY' => $lang['font_tiny'],
	'L_FONT_SMALL' => $lang['font_small'],
	'L_FONT_MEDIUM' => $lang['font_medium'],
	'L_FONT_NORMAL' => $lang['font_normal'],
	'L_FONT_LARGE' => $lang['font_large'],
	'L_FONT_HUGE' => $lang['font_huge'],
	'L_FONT_XL' => $lang['font_xl'],

	'L_BBCB_MG_LANG' => $lang['bbcb_mg_lang'],
	'L_FONT_TYPE' => $lang['Font_Type'],
	'L_FONT_ARIAL' => $lang['Font_Arial'],
	'L_FONT_ARIAL_BLACK' => $lang['Font_Arial_black'],
	'L_FONT_COMIC_SANS_MS' => $lang['Font_Comic_sans_ms'],
	'L_FONT_COURIER_NEW' => $lang['Font_Courier_new'],
	'L_FONT_IMPACT' => $lang['Font_Impact'],
	'L_FONT_LUCIDA_CONSOLE' => $lang['Font_Lucida_console'],
	'L_FONT_LUCIDA_SANS_UNICODE' => $lang['Font_Lucida_sans_unicode'],
	'L_FONT_MICROSOFT_SANS_SERIF' => $lang['Font_Microsoft_sans_serif'],
	'L_FONT_SYMBOL' => $lang['Font_Symbol'],
	'L_FONT_TAHOMA' => $lang['Font_Tahoma'],
	'L_FONT_TIMES_NEW_ROMAN' => $lang['Font_Times_new_roman'],
	'L_FONT_TRADITIONAL_ARABIC' => $lang['Font_Traditional_arabic'],
	'L_FONT_TREBUCHET_MS' => $lang['Font_Trebuchet_ms'],
	'L_FONT_VERDANA' => $lang['Font_Verdana'],
	'L_FONT_WEBDINGS' => $lang['Font_Webdings'],
	'L_FONT_WINGDINGS' => $lang['Font_Wingdings'],

	'L_BBCB_MG_COLOR_PICKER' => $lang['bbcb_mg_colorpicker'],
	'L_BBCB_MG_J' => $lang['bbcb_mg_j'],
	'L_BBCB_MG_R' => $lang['bbcb_mg_r'],
	'L_BBCB_MG_C' => $lang['bbcb_mg_c'],
	'L_BBCB_MG_L' => $lang['bbcb_mg_l'],
	'L_BBCB_MG_B' => $lang['bbcb_mg_b'],
	'L_BBCB_MG_I' => $lang['bbcb_mg_i'],
	'L_BBCB_MG_U' => $lang['bbcb_mg_u'],
	'L_BBCB_MG_S' => $lang['bbcb_mg_s'],
	'L_BBCB_MG_F' => $lang['bbcb_mg_f'],
	'L_BBCB_MG_G' => $lang['bbcb_mg_g'],
	'L_BBCB_MG_TAB' => $lang['bbcb_mg_tab'],
	'L_BBCB_MG_TD' => $lang['bbcb_mg_td'],
	'L_BBCB_MG_MD' => $lang['bbcb_mg_md'],
	'L_BBCB_MG_MU' => $lang['bbcb_mg_mu'],
	'L_BBCB_MG_ML' => $lang['bbcb_mg_ml'],
	'L_BBCB_MG_MR' => $lang['bbcb_mg_mr'],
	'L_BBCB_MG_CODE' => $lang['bbcb_mg_code'],
	'L_BBCB_MG_PHPBBMOD' => $lang['bbcb_mg_phpbbmod'],
	'L_BBCB_MG_QUOTE' => $lang['bbcb_mg_quote'],
	'L_BBCB_MG_HIGHLIGHT' => $lang['bbcb_mg_highlight'],
	'L_BBCB_MG_URL' => $lang['bbcb_mg_url'],
	'L_BBCB_MG_EML' => $lang['bbcb_mg_eml'],
	'L_BBCB_MG_IMG' => $lang['bbcb_mg_img'],
	'L_BBCB_MG_IMGL' => $lang['bbcb_mg_imgl'],
	'L_BBCB_MG_IMGR' => $lang['bbcb_mg_imgr'],
	'L_BBCB_MG_ALBUMIMG' => $lang['bbcb_mg_albumimg'],
	'L_BBCB_MG_ALBUMIMGL' => $lang['bbcb_mg_albumimgl'],
	'L_BBCB_MG_ALBUMIMGR' => $lang['bbcb_mg_albumimgr'],
	'L_BBCB_MG_UPLOAD_IMG' => $lang['bbcb_mg_upload_img'],
	'L_BBCB_MG_POSTICYIMAGE' => $lang['bbcb_mg_posticyimage'],
	'L_BBCB_MG_FLSH' => $lang['bbcb_mg_flsh'],
	'L_BBCB_MG_VID' => $lang['bbcb_mg_vid'],
	'L_BBCB_MG_GVID' => $lang['bbcb_mg_gvid'],
	'L_BBCB_MG_YOUTUBE' => $lang['bbcb_mg_youtube'],
	'L_BBCB_MG_RAM' => $lang['bbcb_mg_ram'],
	'L_BBCB_MG_STRM' => $lang['bbcb_mg_strm'],
	'L_BBCB_MG_EMFF' => $lang['bbcb_mg_emff'],
	'L_BBCB_MG_LST' => $lang['bbcb_mg_lst'],
	'L_BBCB_MG_LSTO' => $lang['bbcb_mg_lsto'],
	'L_BBCB_MG_HR' => $lang['bbcb_mg_hr'],
	'L_BBCB_MG_SUP' => $lang['bbcb_mg_sup'],
	'L_BBCB_MG_SUB' => $lang['bbcb_mg_sub'],
	'L_BBCB_MG_FADE' => $lang['bbcb_mg_fade'],
	'L_BBCB_MG_SPOILER' => $lang['bbcb_mg_spoiler'],
	'L_BBCB_MG_CELL' => $lang['bbcb_mg_cell'],
	'L_BBCB_MG_GRAD' => $lang['bbcb_mg_grad'],

	'L_FORMERRORS' => $lang['s_formerrors'],
	'L_GEN_ERROR' => $lang['s_gen_error'],
	'L_URL_INSERT' => $lang['s_url_insert'],
	'L_URL_INSERT_TIP' => $lang['s_url_insert_tip'],
	'L_URL_ERROR' => $lang['s_url_error'],
	'L_URL_TITLE_INSERT' => $lang['s_url_title_insert'],
	'L_URL_TITLE_INSERT_TIP' => $lang['s_url_title_insert_tip'],
	'L_URL_TITLE_ERROR' => $lang['s_url_title_error'],
	'L_EMAIL_INSERT' => $lang['s_email_insert'],
	'L_EMAIL_INSERT_TIP' => $lang['s_email_insert_tip'],
	'L_EMAIL_ERROR' => $lang['s_email_error'],
	'L_IMG_INSERT' => $lang['s_img_insert'],
	'L_IMG_ERROR' => $lang['s_img_error'],
	'L_ALBUMIMG_INSERT' => $lang['s_albumimg_insert'],
	'L_ALBUMIMG_INSERT_TIP' => $lang['s_albumimg_insert_tip'],
	'L_ALBUMIMG_ERROR' => $lang['s_albumimg_error'],
	'L_RAM_INSERT' => $lang['s_ram_insert'],
	'L_STREAM_INSERT' => $lang['s_stream_insert'],
	'L_VIDEO_INSERT' => $lang['s_video_insert'],
	'L_VIDEO_W_INSERT' => $lang['s_video_w_insert'],
	'L_VIDEO_W_ERROR' => $lang['s_video_w_error'],
	'L_VIDEO_H_INSERT' => $lang['s_video_h_insert'],
	'L_VIDEO_H_ERROR' => $lang['s_video_h_error'],
	'L_FLASH_INSERT' => $lang['s_flash_insert'],
	'L_FLASH_W_INSERT' => $lang['s_flash_w_insert'],
	'L_FLASH_W_ERROR' => $lang['s_flash_w_error'],
	'L_FLASH_H_INSERT' => $lang['s_flash_h_insert'],
	'L_FLASH_H_ERROR' => $lang['s_flash_h_error'],
	'L_FILE_INSERT_ERROR' => $lang['s_file_insert_error'],
	'L_VIEW_MORE_CODE' => $lang['s_view_more_code'],

	// Helpline messages,
	'L_A_HELP' => $lang['s_a_help'],
	'L_B_HELP' => $lang['s_b_help'],
	'L_I_HELP' => $lang['s_i_help'],
	'L_U_HELP' => $lang['s_u_help'],
	'L_STRIKE_HELP' => $lang['s_strike_help'],
	'L_QUOTE_HELP' => $lang['s_quote_help'],
	'L_CODE_HELP' => $lang['s_code_help'],
	'L_SPOILER_HELP' => $lang['s_spoiler_help'],
	'L_HIGHLIGHT_HELP' => $lang['s_highlight_help'],
	'L_IMG_HELP' => $lang['s_img_help'],
	'L_IMGL_HELP' => $lang['s_imgl_help'],
	'L_IMGR_HELP' => $lang['s_imgr_help'],
	'L_ALBUMIMG_HELP' => $lang['s_albumimg_help'],
	'L_ALBUMIMGL_HELP' => $lang['s_albumimgl_help'],
	'L_ALBUMIMGR_HELP' => $lang['s_albumimgr_help'],
	'L_URL_HELP' => $lang['s_url_help'],
	'L_FC_HELP' => $lang['s_fc_help'],
	'L_FS_HELP' => $lang['s_fs_help'],
	'L_FT_HELP' => $lang['s_ft_help'],
	'L_TABLE_HELP' => $lang['s_table_help'],
	'L_TD_HELP' => $lang['s_td_help'],
	'L_MAIL_HELP' => $lang['s_mail_help'],
	'L_GRAD_HELP' => $lang['s_grad_help'],
	'L_RIGHT_HELP' => $lang['s_right_help'],
	'L_LEFT_HELP' => $lang['s_left_help'],
	'L_CENTER_HELP' => $lang['s_center_help'],
	'L_JUSTIFY_HELP' => $lang['s_justify_help'],
	'L_MARQR_HELP' => $lang['s_marqr_help'],
	'L_MARQL_HELP' => $lang['s_marql_help'],
	'L_MARQU_HELP' => $lang['s_marqu_help'],
	'L_MARQD_HELP' => $lang['s_marqd_help'],
	'L_SUP_HELP' => $lang['s_sup_help'],
	'L_SUB_HELP' => $lang['s_sub_help'],
	'L_HR_HELP' => $lang['s_hr_help'],
	'L_BULLET_HELP' => $lang['s_bullet_help'],
	'L_VIDEO_HELP' => $lang['s_video_help'],
	'L_GOOGLEVIDEO_HELP' => $lang['s_googlevideo_help'],
	'L_YOUTUBE_HELP' => $lang['s_youtube_help'],
	'L_QUICK_HELP' => $lang['s_quick_help'],
	'L_FLASH_HELP' => $lang['s_flash_help'],
	'L_RAM_HELP' => $lang['s_ram_help'],
	'L_STREAM_HELP' => $lang['s_stream_help'],
	'L_EMFF_HELP' => $lang['s_emff_help'],
	'L_FADE_HELP' => $lang['s_fade_help'],
	'L_LIST_HELP' => $lang['s_list_help'],

	'L_BBCODE_CLOSE_TAGS' => $lang['Close_Tags'],
	'L_STYLES_TIP' => $lang['Styles_tip']
	)
);

if (defined('BBCB_MG_SMALL'))
{
	if (!function_exists('generate_smilies_row'))
	{
		include_once(IP_ROOT_PATH . 'includes/functions_bbcode.' . PHP_EXT);
	}
	generate_smilies_row();
}

?>