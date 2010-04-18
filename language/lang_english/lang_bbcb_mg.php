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
* Lopalong
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge($lang, array(
	'bbcb_mg_lang' => 'lang_english',

	'wrote' => 'wrote',
	'Quote' => 'Quote',
	'Code' => 'Code',
	'Hide' => 'Hide',
	'Show' => 'Show',
	'Download' => 'Download',
	'Syntax' => 'Syntax',
	'Select' => 'Select',
	'ReviewPost' => 'View Post',
	'OffTopic' => 'Off Topic',
	'OpenNewWindow' => 'Click to open image in new window',
	'C++' => 'C++',
	'PhpCode' => 'Php',

	'Close_Tags' => 'Close Tags',
	'Styles_tip' => 'Tip: Styles can be applied quickly to selected text.',

// Smiley Creator
	'bbcode_sc_help' => 'Smiley Creator: [schild=1]Text[/schild] Create a Smiley with your text',
	'Smiley_creator' => 'Smiley Creator',
	'SC_shieldtext' => 'Smiley Text',
	'SC_fontcolor' => 'Text Colour',
	'SC_shadowcolor' => 'Shadow Colour',
	'SC_shieldshadow' => 'Shield Shadow',
	'SC_shieldshadow_on' => 'Activate',
	'SC_shieldshadow_off' => 'Deactivate',
	'SC_smiliechooser' => 'Select Smiley',
	'SC_random_smilie' => 'Random Smiley',
	'SC_default_smilie' => 'Standard Smiley',
	'SC_create_smilie' => 'Create',
	'SC_stop_creating' => 'Cancel',
	'SC_error' => 'Here is your Shield - you have forgotten the Text...',
	'SC_another_shield' => 'Do you want to create another Smiley?',
	'SC_notext_error' => 'You cannot create Smileys without Text',

	'bbcode_b_help' => 'Bold text: [b]text[/b] (alt+b)',
	'bbcode_i_help' => 'Italic text: [i]text[/i] (alt+i)',
	'bbcode_u_help' => 'Underline text: [u]text[/u] (alt+u)',
	'bbcode_q_help' => 'Quote text: [quote]text[/quote] (alt+q)',
	'bbcode_c_help' => 'Code display: [code]code[/code] (alt+c)',
	'bbcode_l_help' => 'List: [list]text[/list] (alt+l)',
	'bbcode_o_help' => 'Ordered list: [list=]text[/list] (alt+o)',
	'bbcode_p_help' => 'Insert image: [img]http://image_url[/img] (alt+p)',
	'bbcode_w_help' => 'Insert URL: [url]http://url[/url] or [url=http://url]URL text[/url] (alt+w)',
	'bbcode_a_help' => 'Close all open BBCode tags',
	'bbcode_s_help' => 'Font colour: [color=red]text[/color] Tip: you can also use color=#FF0000',
	'bbcode_f_help' => 'Font size: [size=x-small]small text[/size]',

	'Font_color' => 'Font colour',
	'color_default' => 'Default',
	'color_dark_red' => 'Dark Red',
	'color_red' => 'Red',
	'color_orange' => 'Orange',
	'color_brown' => 'Brown',
	'color_yellow' => 'Yellow',
	'color_green' => 'Green',
	'color_olive' => 'Olive',
	'color_cyan' => 'Cyan',
	'color_blue' => 'Blue',
	'color_dark_blue' => 'Dark Blue',
	'color_indigo' => 'Indigo',
	'color_violet' => 'Violet',
	'color_white' => 'White',
	'color_black' => 'Black',

	'color_cadet_blue' => 'Cadet Blue',
	'color_coral' => 'Coral',
	'color_crimson' => 'Crimson',
	'color_dark_grey' => 'Dark Grey',
	'color_dark_orchid' => 'Dark Orchid',
	'color_gold' => 'Gold',
	'color_gray' => 'Gray',
	'color_light_blue' => 'Light Blue',
	'color_light_cyan' => 'Light Cyan',
	'color_light_green' => 'Light Green',
	'color_light_grey' => 'Light Grey',
	'color_light_orange' => 'Light Orange',
	'color_peach' => 'Peach',
	'color_power_orange' => 'Power Orange',
	'color_sea_green' => 'Sea Green',
	'color_silver' => 'Silver',
	'color_tomato' => 'Tomato',
	'color_turquoise' => 'Turquoise',
	'color_chocolate' => 'Chocolate',
	'color_deepskyblue' => 'Deep Sky Blue',
	'color_midnightblue' => 'Midnight Blue',
	'color_darkgreen' => 'Dark Green',

	'Font_size' => 'Font Size',
	'font_tiny' => 'Tiny',
	'font_small' => 'Small',
	'font_medium' => 'Medium',
	'font_normal' => 'Normal',
	'font_large' => 'Large',
	'font_huge' => 'Huge',
	'font_xl' => 'XL',

// Font Type
	'Font_Type' => 'Font',
	'Font_Arial' => 'Arial',
	'Font_Arial_black' => 'Arial Black',
	'Font_Comic_sans_ms' => 'Comic Sans MS',
	'Font_Courier_new' => 'Courier New',
	'Font_Impact' => 'Impact',
	'Font_Lucida_console' => 'Lucida Console',
	'Font_Lucida_sans_unicode' => 'Lucida Sans Unicode',
	'Font_Microsoft_sans_serif' => 'Microsoft Sans Serif',
	'Font_Symbol' => 'Symbol',
	'Font_Tahoma' => 'Tahoma',
	'Font_Times_new_roman' => 'Times New Roman',
	'Font_Traditional_arabic' => 'Traditional Arabic',
	'Font_Trebuchet_ms' => 'Trebuchet MS',
	'Font_Verdana' => 'Verdana',
	'Font_Webdings' => 'Webdings',
	'Font_Wingdings' => 'Wingdings',

// Alt Buttons
	'bbcb_mg_colorpicker' => 'Colour Picker',
	'bbcb_mg_j' => 'Justify',
	'bbcb_mg_r' => 'Right',
	'bbcb_mg_c' => 'Centre',
	'bbcb_mg_l' => 'Left',
	'bbcb_mg_b' => 'Bold',
	'bbcb_mg_i' => 'Italic',
	'bbcb_mg_u' => 'Underline',
	'bbcb_mg_s' => 'Strike',
	'bbcb_mg_f' => 'Fade',
	'bbcb_mg_g' => 'Gradient',
	'bbcb_mg_tab' => 'Table',
	'bbcb_mg_td' => 'Cell',
	'bbcb_mg_md' => 'Marquee down',
	'bbcb_mg_mu' => 'Marquee up',
	'bbcb_mg_ml' => 'Marquee left',
	'bbcb_mg_mr' => 'Marquee right',
	'bbcb_mg_code' => 'Code',
	'bbcb_mg_phpbbmod' => 'phpBB Mod Template',
	'bbcb_mg_quote' => 'Quote',
	'bbcb_mg_spoil' => 'Spoiler',
	'bbcb_mg_highlight' => 'Highlight',
	'bbcb_mg_url' => 'Url',
	'bbcb_mg_eml' => 'Email',
	'bbcb_mg_img' => 'Image',
	'bbcb_mg_imgl' => 'Image Left',
	'bbcb_mg_imgr' => 'Image Right',
	'bbcb_mg_albumimg' => 'Album Image',
	'bbcb_mg_flsh' => 'Flash',
	'bbcb_mg_vid' => 'Video',
	'bbcb_mg_gvid' => 'Google Video',
	'bbcb_mg_youtube' => 'YouTube',
	'bbcb_mg_ram' => 'Ram',
	'bbcb_mg_strm' => 'Stream',
	'bbcb_mg_emff' => 'EMFF (MP3)',
	'bbcb_mg_lst' => 'List',
	'bbcb_mg_hr' => 'Horizontal line',
	'bbcb_mg_bullet' => 'Bullet point',
	'bbcb_mg_sup' => 'Sup',
	'bbcb_mg_sub' => 'Sub',
	'bbcb_mg_lsto' => 'Ordered List',
	'bbcb_mg_fade' => 'Transparency',
	'bbcb_mg_spoiler' => 'Spoiler',
	'bbcb_mg_cell' => 'Cell',
	'bbcb_mg_grad' => 'Gradient',
	'bbcb_mg_upload_img' => 'Upload image to PostImage.org and add it to the message',
	'bbcb_mg_posticyimage' => 'Upload image and add it to the message',
	'bbcb_mg_albumimgl' => 'Album Image Left',
	'bbcb_mg_albumimgr' => 'Album Image Right',
	'bbcode_help' => 'BBCode Help',

	'xs_bbc_hide_message' => 'Hidden Message',
	'xs_bbc_hide_quote_message' => 'Quoted Hidden Message, which is still hidden.',
	'xs_bbc_hide_message_explain' => 'Sorry, but you must be registered and also post a reply to view this message.',

	'bbcode_url' => 'Enter the URL (eg. http://www.icyphoenix.com)',
	'bbcode_url_title' => 'Enter the title of the link',
	'bbcode_url_empty' => 'You didn\'t enter a url',
	'bbcode_url_errors' => 'Error!',
	)
);

// JavaScript Text - BEGIN
// Forms
$js_lang = array();

$js_lang['s_formerrors'] = 'You must insert some text to send a message';
$js_lang['s_gen_error'] = ':: Error ::\n\n';
$js_lang['s_url_insert'] = 'Insert the URL (eg. http://www.icyphoenix.com/)';
$js_lang['s_url_insert_tip'] = 'http://';
$js_lang['s_url_error'] = 'You didn\'t enter any URL';
$js_lang['s_url_title_insert'] = 'Enter the title of the link';
$js_lang['s_url_title_insert_tip'] = 'Link';
$js_lang['s_url_title_error'] = 'You didn\'t write the page name';
$js_lang['s_email_insert'] = 'Enter the Email Address';
$js_lang['s_email_insert_tip'] = 'yourname@yourdomain.com';
$js_lang['s_email_error'] = 'You didn\'t write the Email Address';
$js_lang['s_img_insert'] = 'Enter the image URL';
$js_lang['s_img_error'] = 'You didn\'t write the image URL';
$js_lang['s_albumimg_insert'] = 'Enter the Album Image ID';
$js_lang['s_albumimg_insert_tip'] = 'ID';
$js_lang['s_albumimg_error'] = 'You didn\'t write the Album Image ID';
$js_lang['s_ram_insert'] = 'Please write Real Media file URL';
$js_lang['s_stream_insert'] = 'Please write audio file URL';
$js_lang['s_video_insert'] = 'Please write video file URL';
$js_lang['s_video_w_insert'] = 'Please specify video file width';
$js_lang['s_video_w_error'] = 'You didn\'t specify video file width';
$js_lang['s_video_h_insert'] = 'Please specify video file height';
$js_lang['s_video_h_error'] = 'You didn\'t specify video file height';
$js_lang['s_googlevideo_insert'] = 'Please write Google Video file ID';
$js_lang['s_youtube_insert'] = 'Please write YouTube file ID';
$js_lang['s_emff_insert'] = 'Please write mp3 file URL';
$js_lang['s_flash_insert'] = 'Please write Flash file URL';
$js_lang['s_flash_w_insert'] = 'Please specify Flash file width';
$js_lang['s_flash_w_error'] = 'You didn\'t specify Flash file width';
$js_lang['s_flash_h_insert'] = 'Please specify Flash file height';
$js_lang['s_flash_h_error'] = 'You didn\'t specify Flash file height';
$js_lang['s_id_insert_tip'] = 'ID';
$js_lang['s_id_insert_error'] = 'You didn\'t specify an ID';
$js_lang['s_file_insert_error'] = 'You didn\'t specify file URL';
$js_lang['s_grad_select'] = 'Please select the text first';
$js_lang['s_grad_error'] = 'This only works for less than 120 letters';
$js_lang['s_grad_path'] = 'includes/grad.htm';
$js_lang['s_view_more_code'] = 'View more code';
$js_lang['s_image_upload'] = 'Upload image to PostImage.org and add it to the message';
$js_lang['s_posticyimage'] = 'Upload image and add it to the message';

// Helpline messages
$js_lang['s_a_help'] = 'Close all open tags';
$js_lang['s_b_help'] = 'Bold text: [b]text[/b]';
$js_lang['s_i_help'] = 'Italic text: [i]text[/i]';
$js_lang['s_u_help'] = 'Underline text: [u]text[/u]';
$js_lang['s_strike_help'] = 'Strike text: [strike]text[/strike]';
$js_lang['s_quote_help'] = 'Quote text: [quote]text[/quote]';
$js_lang['s_code_help'] = 'Code display: [code]code[/code]';
$js_lang['s_phpbbmod_help'] = 'phpBB Mod Template';
$js_lang['s_highlight_help'] = 'Highlight: [highlight=#FFFFAA]text[/highlight]';
$js_lang['s_img_help'] = 'Insert image: [img]http://image_url[/img]';
$js_lang['s_imgl_help'] = 'Insert image with left alignment: [img align=left]http://image_url[/img]';
$js_lang['s_imgr_help'] = 'Insert image with right alignment: [img align=right]http://image_url[/img]';
$js_lang['s_albumimg_help'] = 'Insert image from album: [albumimg]Album Pic ID[/albumimg]';
$js_lang['s_albumimgl_help'] = 'Insert image from album with left alignment: [albumimg align=left]Album Pic ID[/albumimg]';
$js_lang['s_albumimgr_help'] = 'Insert image from album with right alignment: [albumimg align=right]Album Pic ID[/albumimg]';
$js_lang['s_url_help'] = 'Insert URL: [url]http://url[/url] or [url=http://url]URL text[/url]';
$js_lang['s_fc_help'] = 'Font color: [color=red]text[/color] (Tip: you can also use color=#FF0000)';
$js_lang['s_fs_help'] = 'Font size: [size=9]small text[/size]';
$js_lang['s_ft_help'] = 'Font type: [font=Andalus]text[/font]';
$js_lang['s_table_help'] = 'Insert Table: [table]text[/table]';
$js_lang['s_td_help'] = 'Insert Table Column: [td]text[/td]';
$js_lang['s_mail_help'] = 'Insert Email: [email]Email Here[/email]';
$js_lang['s_grad_help'] = 'Insert gradient text';
$js_lang['s_right_help'] = 'Set text align to right: [align=right]text[/align]';
$js_lang['s_left_help'] = 'Set text align to left: [align=left]text[/align]';
$js_lang['s_center_help'] = 'Set text align to center: [align=center]text[/align]';
$js_lang['s_justify_help'] = 'Justify text: [align=justify]text[/align]';
$js_lang['s_marqr_help'] = 'Marque text to Right: [marquee direction=right]text[/marquee]';
$js_lang['s_marql_help'] = 'Marque text to Left: [marquee direction=left]text[/marquee]';
$js_lang['s_marqu_help'] = 'Marque text to up: [marquee direction=up]text[/marquee]';
$js_lang['s_marqd_help'] = 'Marque text to down: [marquee direction=down]text[/marquee]';
$js_lang['s_sup_help'] = 'Superscript: [sup]text[/sup]';
$js_lang['s_sub_help'] = 'Subscript: [sub]text[/sub]';
$js_lang['s_hr_help'] = 'Insert H-Line [hr]';
$js_lang['s_bullet_help'] = 'Insert bullet point [*]';
$js_lang['s_video_help'] = 'Insert video file: [video width=# height=#]file URL[/video]';
$js_lang['s_googlevideo_help'] = 'Insert Google Video file: [googlevideo]Google Video ID[/googlevideo]';
$js_lang['s_youtube_help'] = 'Insert YouTube video file: [youtube]YouTube ID[/youtube]';
$js_lang['s_quick_help'] = 'Quicktime video: [quick]http://quicktime_video_url/[/quick]';
$js_lang['s_flash_help'] = 'Insert flash file: [flash width=# height=#]flash URL[/flash]';
$js_lang['s_ram_help'] = 'Insert Real Media file: [ram]File URL[/ram]';
$js_lang['s_stream_help'] = 'Insert stream file: [stream]File URL[/stream]';
$js_lang['s_emff_help'] = 'Insert mp3 file: [emff]File URL[/emff]';
$js_lang['s_fade_help'] = 'Fade: [opacity]text[/opacity] or [opacity][img]http://image_url/[/img][/opacity]';
$js_lang['s_spoiler_help'] = 'Spoiler: [spoiler]text[/spoiler]';
$js_lang['s_cell_help'] = 'Cell: [cell]text[/cell]';
$js_lang['s_list_help'] = 'List: [list]text[/list] (Tip: you can use [*] to insert bullet)';
$js_lang['s_listo_help'] = 'Ordered list: [list=1|a]text[/list] (Tip: you can use [*] to insert bullet)';
$js_lang['s_help_help'] = 'Open BBCode Help';
$js_lang['s_image_upload_help'] = 'Upload image to PostImage.org and add it to the message';
$js_lang['s_posticyimage_help'] = 'Upload image and add it to the message';
$js_lang['s_smiley_creator'] = 'Smiley Creator: [smiley=1]Text[/smiley] insert a smiley with text';

// Please do not remove this!!!
foreach ($js_lang as $k => $v)
{
	$lang[$k] = $v;
}

$javascript_lang_vars = '';
foreach ($js_lang as $k => $v)
{
	$javascript_lang_vars .= 'var ' . $k . ' = \'' . str_replace("'", "\'", $v) . '\';' . "\n";
}

$lang['JAVASCRIPT_LANG_VARS'] = $javascript_lang_vars;
unset($js_lang);
// JavaScript Text - END

?>