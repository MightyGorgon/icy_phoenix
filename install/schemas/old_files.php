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

// OLD Files
$files_array = array(
	'extension.inc',
	'cpl_menu.php',
	'db_generator.php',
	'edit_post_time.php',
	'subscp.php',
	'uptime.php',
	'usercp.php',

	'adm/xs2_head.php',
	'adm/xs_avatar_generator.cfg',
	'adm/xs_direct_img.cfg',
	'adm/xs_ipb_profile.cfg',
	'adm/xs_lo_fi_mod.cfg',
	'adm/xs_news.cfg',

	'adm/admin_board_main.php',
	'adm/admin_board_posting.php',
	'adm/admin_board_queries.php',
	'adm/admin_color_groups.php',
	'adm/admin_db_generator.php',
	'adm/admin_lang_extend.php',
	'adm/admin_mass_email.php',
	'adm/admin_similar_topics.php',
	'adm/pa_block_config.php',

	'docs/hl/Color Groups.hl',
	'docs/hl/DB Generator.hl',

	'images/fap/fap_blank.gif',
	'images/fap/fap_info.gif',
	'images/fap/fap_loading.gif',
	'images/fap/fap_next.gif',
	'images/fap/fap_prev.gif',
	'images/fap/fap_nothumbnail.jpg',
	'images/smiles/makepak.php',

	'includes/album_mod/album_bbcode.php',
	'includes/album_mod/fap_loader.js',
	'includes/album_mod/moo.ajax.js',
	'includes/album_mod/moo.fx.js',
	'includes/album_mod/moo.fx.pack.js',
	'includes/album_mod/mooshow.1.04.js',
	'includes/album_mod/prototype.lite.js',

	'includes/album_mod/fap_alpha.png',
	'includes/album_mod/fap_blank.gif',
	'includes/album_mod/fap_blur.png',
	'includes/album_mod/fap_bw.png',
	'includes/album_mod/fap_flip.png',
	'includes/album_mod/fap_info.gif',
	'includes/album_mod/fap_infrared.png',
	'includes/album_mod/fap_interlace.png',
	'includes/album_mod/fap_loading.gif',
	'includes/album_mod/fap_mirror.png',
	'includes/album_mod/fap_next.gif',
	'includes/album_mod/fap_normal.png',
	'includes/album_mod/fap_nothumbnail.jpg',
	'includes/album_mod/fap_pixelate.png',
	'includes/album_mod/fap_prev.gif',
	'includes/album_mod/fap_recompress.png',
	'includes/album_mod/fap_resize.png',
	'includes/album_mod/fap_rotate.png',
	'includes/album_mod/fap_scatter.png',
	'includes/album_mod/fap_screen.png',
	'includes/album_mod/fap_sepia.png',
	'includes/album_mod/fap_stereogram.png',
	'includes/album_mod/fap_tint.png',
	'includes/album_mod/fap_watermark.png',
	'includes/album_mod/mark.png',
	'includes/album_mod/mark_.png',
	'includes/album_mod/mark_fap.png',
	'includes/album_mod/mark_fap_big.png',
	'includes/album_mod/nothumbnail.jpg',
	'includes/album_mod/rank.gif',
	'includes/album_mod/rank_big.gif',
	'includes/album_mod/rank_old.gif',
	'includes/album_mod/rank_small.gif',
	'includes/album_mod/rating_star.png',
	'includes/album_mod/rating_star_blue.png',
	'includes/album_mod/rating_star_red.png',
	'includes/album_mod/rating_star_yellow.png',
	'includes/album_mod/spacer.gif',

	'includes/functions_color_groups.php',
	'includes/functions_portal.php',
	'includes/news_common.php',

	'includes/upi2db/upi2db_orig_xs.php',

	'templates/common/acp/admin_similar_topics_body.tpl',
	'templates/common/acp/admin_db_generator_body.tpl',
	'templates/common/acp/board_config_main_body.tpl',
	'templates/common/acp/board_config_posting_body.tpl',
	'templates/common/acp/board_config_queries.tpl',
	'templates/common/acp/color_groups_manager.tpl',
	'templates/common/acp/color_groups_user_list.tpl',
	'templates/common/acp/guestbook_config_body.tpl',
	'templates/common/acp/lang_extend_body.tpl',
	'templates/common/acp/lang_extend_def.tpl',
	'templates/common/acp/lang_extend_key_body.tpl',
	'templates/common/acp/lang_extend_pack_body.tpl',
	'templates/common/acp/lang_extend_search_body.tpl',
	'templates/common/acp/user_email_body.tpl',
	'templates/common/acp/xs2_header.tpl',

	'templates/common/lightbox.css',
	'templates/common/js/lightbox.js',
	'templates/common/js/lightbox_alt.js',
	'templates/common/js/mg_scripts.js',
	'templates/common/js/mg_rating.js',
	'templates/common/js/toggle_display.js',
);

$language_array = array('dutch', 'catala', 'german', 'english', 'italian', 'spanish');
for ($i = 0; $i < count($language_array); $i++)
{
	$files_array[] = 'language/lang_' . $language_array[$i] . '/db_generator.tpl';

	$files_array[] = 'language/lang_' . $language_array[$i] . '/lang_admin_db_backup.php';
	$files_array[] = 'language/lang_' . $language_array[$i] . '/lang_bbc_tags.php';
	$files_array[] = 'language/lang_' . $language_array[$i] . '/lang_cback_ctracker.php';
	$files_array[] = 'language/lang_' . $language_array[$i] . '/lang_color_groups.php';
	$files_array[] = 'language/lang_' . $language_array[$i] . '/lang_extend.php';
	$files_array[] = 'language/lang_' . $language_array[$i] . '/lang_extend_categories_hierarchy.php';
	$files_array[] = 'language/lang_' . $language_array[$i] . '/lang_extend_lang_extend.php';
	$files_array[] = 'language/lang_' . $language_array[$i] . '/lang_extend_mods_settings.php';
	$files_array[] = 'language/lang_' . $language_array[$i] . '/lang_extend_topic_calendar.php';
}

$template_array = array('ca_aphrodite', 'fk_themes', 'mg_themes');
for ($i = 0; $i < count($template_array); $i++)
{
	$files_array[] = 'templates/' . $template_array[$i] . '/edit_post_time_body.tpl';
	$files_array[] = 'templates/' . $template_array[$i] . '/news_.tpl';
	$files_array[] = 'templates/' . $template_array[$i] . '/portal_page_header.tpl';
	$files_array[] = 'templates/' . $template_array[$i] . '/portal_page_headercenter.tpl';
	$files_array[] = 'templates/' . $template_array[$i] . '/portal_page_headerleft.tpl';
	$files_array[] = 'templates/' . $template_array[$i] . '/portal_page_tail.tpl';
	$files_array[] = 'templates/' . $template_array[$i] . '/portal_page_tailcenter.tpl';
	$files_array[] = 'templates/' . $template_array[$i] . '/portal_page_tailright.tpl';

	$files_array[] = 'templates/' . $template_array[$i] . '/xs/index.htm';
	$files_array[] = 'templates/' . $template_array[$i] . '/xs/index.html';
	$files_array[] = 'templates/' . $template_array[$i] . '/xs/jumpbox.tpl';
	$files_array[] = 'templates/' . $template_array[$i] . '/xs/xs_header.tpl';
	$files_array[] = 'templates/' . $template_array[$i] . '/xs/xs_index.tpl';
	$files_array[] = 'templates/' . $template_array[$i] . '/xs/xs_show.tpl';
	$files_array[] = 'templates/' . $template_array[$i] . '/xs/xs_topic.tpl';
}

$template_array = array('ca_aphrodite', 'fk_themes', 'mg_themes');
$template_color_array = array('apple', 'black', 'black_yellow', 'blue', 'cyan', 'dark_blue', 'dark_green', 'dark_red', 'floreal', 'gray', 'green', 'ice', 'lite_blue', 'm_blue', 'm_green', 'orange', 'p_black', 'p_blue', 'p_purple', 'p_white', 'sblue', 'sgreen', 'silver', 'sunflower', 'text', 'violet', 'white', 'white_blue', 'white_red', 'yellow');
$extension_array = array('gif', 'png');
for ($i = 0; $i < count($template_array); $i++)
{
	for ($j = 0; $j < count($template_color_array); $j++)
	{
		for ($k = 0; $k < count($extension_array); $k++)
		{
			if (($template_array[$i] != 'mg_themes') && ($extension_array[$k] == 'png'))
			{
				continue;
			}
			$files_array[] = 'templates/' . $template_array[$i] . '/images/' . $template_color_array[$j] . '/folder.' . $extension_array[$k];
			$files_array[] = 'templates/' . $template_array[$i] . '/images/' . $template_color_array[$j] . '/folder_ar.' . $extension_array[$k];
			$files_array[] = 'templates/' . $template_array[$i] . '/images/' . $template_color_array[$j] . '/folder_ar_big.' . $extension_array[$k];
			$files_array[] = 'templates/' . $template_array[$i] . '/images/' . $template_color_array[$j] . '/folder_global_announce.' . $extension_array[$k];
			$files_array[] = 'templates/' . $template_array[$i] . '/images/' . $template_color_array[$j] . '/folder_global_announce_new.' . $extension_array[$k];
			$files_array[] = 'templates/' . $template_array[$i] . '/images/' . $template_color_array[$j] . '/folder_global_announce_new_posted.' . $extension_array[$k];
			$files_array[] = 'templates/' . $template_array[$i] . '/images/' . $template_color_array[$j] . '/folder_global_announce_posted.' . $extension_array[$k];
			$files_array[] = 'templates/' . $template_array[$i] . '/images/' . $template_color_array[$j] . '/folder_hot.' . $extension_array[$k];
			$files_array[] = 'templates/' . $template_array[$i] . '/images/' . $template_color_array[$j] . '/folder_hot_new.' . $extension_array[$k];
			$files_array[] = 'templates/' . $template_array[$i] . '/images/' . $template_color_array[$j] . '/folder_hot_new_posted.' . $extension_array[$k];
			$files_array[] = 'templates/' . $template_array[$i] . '/images/' . $template_color_array[$j] . '/folder_hot_posted.' . $extension_array[$k];
			$files_array[] = 'templates/' . $template_array[$i] . '/images/' . $template_color_array[$j] . '/folder_link.' . $extension_array[$k];
			$files_array[] = 'templates/' . $template_array[$i] . '/images/' . $template_color_array[$j] . '/folder_locked.' . $extension_array[$k];
			$files_array[] = 'templates/' . $template_array[$i] . '/images/' . $template_color_array[$j] . '/folder_new.' . $extension_array[$k];
			$files_array[] = 'templates/' . $template_array[$i] . '/images/' . $template_color_array[$j] . '/folders.' . $extension_array[$k];
			$files_array[] = 'templates/' . $template_array[$i] . '/images/' . $template_color_array[$j] . '/folders_ar_big.' . $extension_array[$k];
			$files_array[] = 'templates/' . $template_array[$i] . '/images/' . $template_color_array[$j] . '/folders_new.' . $extension_array[$k];

			$files_array[] = 'templates/' . $template_array[$i] . '/images/' . $template_color_array[$j] . '/inbox.' . $extension_array[$k];
			$files_array[] = 'templates/' . $template_array[$i] . '/images/' . $template_color_array[$j] . '/outbox.' . $extension_array[$k];
			$files_array[] = 'templates/' . $template_array[$i] . '/images/' . $template_color_array[$j] . '/savebox.' . $extension_array[$k];
			$files_array[] = 'templates/' . $template_array[$i] . '/images/' . $template_color_array[$j] . '/sentbox.' . $extension_array[$k];

			$files_array[] = 'templates/' . $template_array[$i] . '/images/' . $template_color_array[$j] . '/icon_mini_upi2db_m.' . $extension_array[$k];
			$files_array[] = 'templates/' . $template_array[$i] . '/images/' . $template_color_array[$j] . '/icon_mini_upi2db_p.' . $extension_array[$k];
			$files_array[] = 'templates/' . $template_array[$i] . '/images/' . $template_color_array[$j] . '/icon_mini_upi2db_u.' . $extension_array[$k];

			$files_array[] = 'templates/' . $template_array[$i] . '/images/' . $template_color_array[$j] . '/post.' . $extension_array[$k];
			$files_array[] = 'templates/' . $template_array[$i] . '/images/' . $template_color_array[$j] . '/post_announce.' . $extension_array[$k];
			$files_array[] = 'templates/' . $template_array[$i] . '/images/' . $template_color_array[$j] . '/post_announce_new.' . $extension_array[$k];
			$files_array[] = 'templates/' . $template_array[$i] . '/images/' . $template_color_array[$j] . '/post_announce_new_posted.' . $extension_array[$k];
			$files_array[] = 'templates/' . $template_array[$i] . '/images/' . $template_color_array[$j] . '/post_announce_posted.' . $extension_array[$k];
			$files_array[] = 'templates/' . $template_array[$i] . '/images/' . $template_color_array[$j] . '/post_important.' . $extension_array[$k];
			$files_array[] = 'templates/' . $template_array[$i] . '/images/' . $template_color_array[$j] . '/post_important_new.' . $extension_array[$k];
			$files_array[] = 'templates/' . $template_array[$i] . '/images/' . $template_color_array[$j] . '/post_important_new_posted.' . $extension_array[$k];
			$files_array[] = 'templates/' . $template_array[$i] . '/images/' . $template_color_array[$j] . '/post_important_posted.' . $extension_array[$k];
			$files_array[] = 'templates/' . $template_array[$i] . '/images/' . $template_color_array[$j] . '/post_locked.' . $extension_array[$k];
			$files_array[] = 'templates/' . $template_array[$i] . '/images/' . $template_color_array[$j] . '/post_locked_new.' . $extension_array[$k];
			$files_array[] = 'templates/' . $template_array[$i] . '/images/' . $template_color_array[$j] . '/post_locked_new_posted.' . $extension_array[$k];
			$files_array[] = 'templates/' . $template_array[$i] . '/images/' . $template_color_array[$j] . '/post_locked_posted.' . $extension_array[$k];
			$files_array[] = 'templates/' . $template_array[$i] . '/images/' . $template_color_array[$j] . '/post_new.' . $extension_array[$k];
			$files_array[] = 'templates/' . $template_array[$i] . '/images/' . $template_color_array[$j] . '/post_new_posted.' . $extension_array[$k];
			$files_array[] = 'templates/' . $template_array[$i] . '/images/' . $template_color_array[$j] . '/post_posted.' . $extension_array[$k];

			$files_array[] = 'templates/' . $template_array[$i] . '/images/' . $template_color_array[$j] . '/control_fastforward_blue.gif';
			$files_array[] = 'templates/' . $template_array[$i] . '/images/' . $template_color_array[$j] . '/control_rewind_blue.gif';

			if ($template_array[$i] == 'mg_themes')
			{
				$files_array[] = 'templates/' . $template_array[$i] . '/images/' . $template_color_array[$j] . '/icon_left_arrow3.gif';
				$files_array[] = 'templates/' . $template_array[$i] . '/images/' . $template_color_array[$j] . '/icon_right_arrow3.gif';
			}

			$files_array[] = 'templates/' . $template_array[$i] . '/images/' . $template_color_array[$j] . '/open2.png';
			$files_array[] = 'templates/' . $template_array[$i] . '/images/' . $template_color_array[$j] . '/dot_aqua.png';
			$files_array[] = 'templates/' . $template_array[$i] . '/images/' . $template_color_array[$j] . '/dot_blue.png';
			$files_array[] = 'templates/' . $template_array[$i] . '/images/' . $template_color_array[$j] . '/dot_green.png';
			$files_array[] = 'templates/' . $template_array[$i] . '/images/' . $template_color_array[$j] . '/dot_orange.png';
			$files_array[] = 'templates/' . $template_array[$i] . '/images/' . $template_color_array[$j] . '/dot_white.png';
			$files_array[] = 'templates/' . $template_array[$i] . '/images/' . $template_color_array[$j] . '/dot_white_alpha.png';
			$files_array[] = 'templates/' . $template_array[$i] . '/images/' . $template_color_array[$j] . '/dot_yellow.png';
		}
	}
}


?>