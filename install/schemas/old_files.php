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

// SETTINGS - BEGIN
$mg_themes_array = array('floreal', 'icy_phoenix', 'mg_themes', 'morpheus', 'pearl', 'squared');
$templates_array = array('ca_aphrodite', 'default', 'fk_themes', 'floreal', 'icy_phoenix', 'mg_themes', 'morpheus', 'pearl', 'prosilver_ip', 'squared');

$template_colors_array = array();
for ($i = 0; $i < sizeof($templates_array); $i++)
{
	$source_folder = IP_ROOT_PATH . 'templates/' . $templates_array[$i] . '/images/';
	$directory = @opendir($source_folder);
	while (@$file = readdir($directory))
	{
		$full_path_file = $source_folder . '/' . $file;
		if (($file != '.') && ($file != '..') && is_dir($source_folder . '/' . $file))
		{
			$template_colors_array[$templates_array[$i]][] = $file;
			//echo($file . '<br />');
		}
	}
	@closedir($directory);
}

// NOT USED ANYMORE!!!
//$template_color_array = array('apple', 'black', 'black_yellow', 'blue', 'cyan', 'dark_blue', 'dark_green', 'dark_red', 'floreal', 'gray', 'green', 'ice', 'lite_blue', 'm_blue', 'm_green', 'orange', 'p_black', 'p_blue', 'p_purple', 'p_white', 'sblue', 'sgreen', 'silver', 'sunflower', 'text', 'violet', 'white', 'white_blue', 'white_red', 'yellow');

$extensions_array = array('gif', 'png');

$languages_array = array('catala', 'dutch', 'english', 'german', 'italian', 'spanish');

$input_paths_lang = array('lang_catala/', 'lang_dutch/', 'lang_english/', 'lang_german/', 'lang_italian/', 'lang_spanish/');
$output_paths_lang = array('lang_catala/', 'lang_dutch/', 'lang_english/', 'lang_german/', 'lang_italian/', 'lang_spanish/');
// SETTINGS - END

// OLD FILES - BEGIN
$files_array = array(
	'extension.inc',
	'album_pclzip_lib.php',
	'changelang.php',
	'changestyle.php',
	'cpl_menu.php',
	'cms_adv.php',
	'cms_auth.php',
	'ctracker_login.php',
	'db_generator.php',
	'edit_post_time.php',
	'fetchposts.php',
	'posted_img_list.php',
	'posted_img_list_thumbnail.php',
	'posted_img_thumbnail.php'
	'referrers.php',
	'site_hist.php',
	'subscp.php',
	'uptime.php',
	'usercp.php',

	'adm/admin_board_headers_banners.php',
	'adm/admin_board_main.php',
	'adm/admin_board_posting.php',
	'adm/admin_board_queries.php',
	'adm/admin_board_server.php',
	'adm/admin_color_groups.php',
	'adm/admin_db_generator.php',
	'adm/admin_forums.php',
	'adm/admin_lang_extend.php',
	'adm/admin_mass_email.php',
	'adm/admin_quick_title.php',
	'adm/admin_similar_topics.php',
	'adm/pa_block_config.php',

	'adm/xs2_head.php',
	'adm/xs_avatar_generator.cfg',
	'adm/xs_direct_img.cfg',
	'adm/xs_ipb_profile.cfg',
	'adm/xs_lo_fi_mod.cfg',
	'adm/xs_news.cfg',

	'docs/hl/Color Groups.hl',
	'docs/hl/DB Generator.hl',

	'images/fap/fap_blank.gif',
	'images/fap/fap_info.gif',
	'images/fap/fap_loading.gif',
	'images/fap/fap_next.gif',
	'images/fap/fap_prev.gif',
	'images/fap/fap_nothumbnail.jpg',
	'images/smiles/makepak.php',

	'includes/bbcb_mg_small.php',
	'includes/def_themes.php',
	'includes/def_tree.php',
	'includes/def_words.php',
	'includes/digest_emailer.php',
	'includes/functions_admin_extra.php',
	'includes/functions_archives.php',
	'includes/functions_cache.php',
	'includes/functions_cms.php',
	'includes/functions_color_groups.php',
	'includes/functions_db_backup.php',
	'includes/functions_mg_files.php',
	'includes/functions_mg_ranks.php',
	'includes/functions_mg_users.php',
	'includes/functions_mods_settings.php',
	'includes/functions_module.php',
	'includes/functions_portal.php',
	'includes/functions_privmsgs.php',
	'includes/functions_privmsgs_admin.php',
	'includes/functions_profile_fields.php',
	'includes/lang_extend_mac.php',
	'includes/meta_parsing.php',
	'includes/page_header.php',
	'includes/page_tail.php',
	'includes/optimize_database_cron.php',
	'includes/phpbb_template.php',

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

	'includes/cache_tpls/def_themes_def.tpl',
	'includes/cache_tpls/def_tree_def.tpl',
	'includes/cache_tpls/def_words_def.tpl',

	'includes/db/mysql4.php',

	'includes/pafiledb/functions_cache.php',
	'includes/pafiledb/functions_field.php',
	'includes/pafiledb/template.php',

	'includes/upi2db/upi2db_orig_all.php',
	'includes/upi2db/upi2db_orig_full.php',
	'includes/upi2db/upi2db_orig_ip.php',
	'includes/upi2db/upi2db_orig_xs.php',

	'templates/common/acp/admin_similar_topics_body.tpl',
	'templates/common/acp/admin_db_generator_body.tpl',
	'templates/common/acp/board_config_headers_banners.tpl',
	'templates/common/acp/board_config_main_body.tpl',
	'templates/common/acp/board_config_posting_body.tpl',
	'templates/common/acp/board_config_queries.tpl',
	'templates/common/acp/board_config_server_body.tpl',
	'templates/common/acp/category_edit_body.tpl',
	'templates/common/acp/color_groups_manager.tpl',
	'templates/common/acp/color_groups_user_list.tpl',
	'templates/common/acp/forum_admin_body.tpl',
	'templates/common/acp/forum_edit_body.tpl',
	'templates/common/acp/guestbook_config_body.tpl',
	'templates/common/acp/lang_extend_body.tpl',
	'templates/common/acp/lang_extend_def.tpl',
	'templates/common/acp/lang_extend_key_body.tpl',
	'templates/common/acp/lang_extend_pack_body.tpl',
	'templates/common/acp/lang_extend_search_body.tpl',
	'templates/common/acp/title_edit_body.tpl',
	'templates/common/acp/title_list_body.tpl',
	'templates/common/acp/user_email_body.tpl',
	'templates/common/acp/xs2_header.tpl',

	'templates/common/cms/cms_pages_auth_body.tpl',

	'templates/common/lightbox.css',
	'templates/common/js/bbcb_mg_ajax_chat.js',
	'templates/common/js/color_bar_ajax_chat.js',
	'templates/common/js/lightbox.js',
	'templates/common/js/lightbox_alt.js',
	'templates/common/js/mg_scripts.js',
	'templates/common/js/mg_rating.js',
	'templates/common/js/toggle_display.js',
);
// OLD FILES - END

for ($i = 0; $i < sizeof($languages_array); $i++)
{
	$files_array[] = 'language/lang_' . $languages_array[$i] . '/db_generator.tpl';

	$files_array[] = 'language/lang_' . $languages_array[$i] . '/lang_admin_db_backup.php';
	$files_array[] = 'language/lang_' . $languages_array[$i] . '/lang_bbc_tags.php';
	$files_array[] = 'language/lang_' . $languages_array[$i] . '/lang_cback_ctracker.php';
	$files_array[] = 'language/lang_' . $languages_array[$i] . '/lang_color_groups.php';
	$files_array[] = 'language/lang_' . $languages_array[$i] . '/lang_edit_post_date.php';
	$files_array[] = 'language/lang_' . $languages_array[$i] . '/lang_extend.php';
	$files_array[] = 'language/lang_' . $languages_array[$i] . '/lang_extend_categories_hierarchy.php';
	$files_array[] = 'language/lang_' . $languages_array[$i] . '/lang_extend_lang_extend.php';
	$files_array[] = 'language/lang_' . $languages_array[$i] . '/lang_extend_mods_settings.php';
	$files_array[] = 'language/lang_' . $languages_array[$i] . '/lang_extend_topic_calendar.php';
}

for ($i = 0; $i < sizeof($templates_array); $i++)
{
	$files_array[] = 'templates/' . $templates_array[$i] . '/breadcrumbs.tpl';
	$files_array[] = 'templates/' . $templates_array[$i] . '/breadcrumbs_a.tpl';
	$files_array[] = 'templates/' . $templates_array[$i] . '/breadcrumbs_i.tpl';
	$files_array[] = 'templates/' . $templates_array[$i] . '/cms_block_inc_nav.tpl';
	$files_array[] = 'templates/' . $templates_array[$i] . '/ctracker_login.tpl';
	$files_array[] = 'templates/' . $templates_array[$i] . '/db_generator_body.tpl';
	$files_array[] = 'templates/' . $templates_array[$i] . '/edit_post_time_body.tpl';
	$files_array[] = 'templates/' . $templates_array[$i] . '/faq_dhtml.tpl';
	$files_array[] = 'templates/' . $templates_array[$i] . '/news_.tpl';
	$files_array[] = 'templates/' . $templates_array[$i] . '/pa_quickdl_cat_body.tpl';
	$files_array[] = 'templates/' . $templates_array[$i] . '/portal_page_header.tpl';
	$files_array[] = 'templates/' . $templates_array[$i] . '/portal_page_headercenter.tpl';
	$files_array[] = 'templates/' . $templates_array[$i] . '/portal_page_headerleft.tpl';
	$files_array[] = 'templates/' . $templates_array[$i] . '/portal_page_tail.tpl';
	$files_array[] = 'templates/' . $templates_array[$i] . '/portal_page_tailcenter.tpl';
	$files_array[] = 'templates/' . $templates_array[$i] . '/portal_page_tailright.tpl';
	$files_array[] = 'templates/' . $templates_array[$i] . '/portal_poll_ballot.tpl';
	$files_array[] = 'templates/' . $templates_array[$i] . '/portal_poll_result.tpl';
	$files_array[] = 'templates/' . $templates_array[$i] . '/site_hist.tpl';
	$files_array[] = 'templates/' . $templates_array[$i] . '/viewtopic_nav.tpl';
	$files_array[] = 'templates/' . $templates_array[$i] . '/viewtopic_poll_ballot.tpl';

	$files_array[] = 'templates/' . $templates_array[$i] . '/xs/index.htm';
	$files_array[] = 'templates/' . $templates_array[$i] . '/xs/index.html';
	$files_array[] = 'templates/' . $templates_array[$i] . '/xs/jumpbox.tpl';
	$files_array[] = 'templates/' . $templates_array[$i] . '/xs/xs_header.tpl';
	$files_array[] = 'templates/' . $templates_array[$i] . '/xs/xs_index.tpl';
	$files_array[] = 'templates/' . $templates_array[$i] . '/xs/xs_show.tpl';
	$files_array[] = 'templates/' . $templates_array[$i] . '/xs/xs_topic.tpl';
}

for ($i = 0; $i < sizeof($templates_array); $i++)
{
	for ($j = 0; $j < sizeof($template_colors_array[$templates_array[$i]]); $j++)
	{
		$current_full_path = 'templates/' . $templates_array[$i] . '/images/' . $template_colors_array[$templates_array[$i]][$j] . '/';
		if (is_dir(IP_ROOT_PATH . $current_full_path))
		{
			for ($k = 0; $k < sizeof($extensions_array); $k++)
			{
				if (!in_array($templates_array[$i], $mg_themes_array) && ($extensions_array[$k] == 'png'))
				{
					continue;
				}

				$files_array[] = $current_full_path . 'folder.' . $extensions_array[$k];
				$files_array[] = $current_full_path . 'folder_ar.' . $extensions_array[$k];
				$files_array[] = $current_full_path . 'folder_ar_big.' . $extensions_array[$k];
				$files_array[] = $current_full_path . 'folder_global_announce.' . $extensions_array[$k];
				$files_array[] = $current_full_path . 'folder_global_announce_new.' . $extensions_array[$k];
				$files_array[] = $current_full_path . 'folder_global_announce_new_posted.' . $extensions_array[$k];
				$files_array[] = $current_full_path . 'folder_global_announce_posted.' . $extensions_array[$k];
				$files_array[] = $current_full_path . 'folder_hot.' . $extensions_array[$k];
				$files_array[] = $current_full_path . 'folder_hot_new.' . $extensions_array[$k];
				$files_array[] = $current_full_path . 'folder_hot_new_posted.' . $extensions_array[$k];
				$files_array[] = $current_full_path . 'folder_hot_posted.' . $extensions_array[$k];
				$files_array[] = $current_full_path . 'folder_link.' . $extensions_array[$k];
				$files_array[] = $current_full_path . 'folder_locked.' . $extensions_array[$k];
				$files_array[] = $current_full_path . 'folder_new.' . $extensions_array[$k];
				$files_array[] = $current_full_path . 'folders.' . $extensions_array[$k];
				$files_array[] = $current_full_path . 'folders_ar_big.' . $extensions_array[$k];
				$files_array[] = $current_full_path . 'folders_new.' . $extensions_array[$k];

				$files_array[] = $current_full_path . 'inbox.' . $extensions_array[$k];
				$files_array[] = $current_full_path . 'outbox.' . $extensions_array[$k];
				$files_array[] = $current_full_path . 'savebox.' . $extensions_array[$k];
				$files_array[] = $current_full_path . 'sentbox.' . $extensions_array[$k];

				$files_array[] = $current_full_path . 'icon_mini_upi2db_m.' . $extensions_array[$k];
				$files_array[] = $current_full_path . 'icon_mini_upi2db_p.' . $extensions_array[$k];
				$files_array[] = $current_full_path . 'icon_mini_upi2db_u.' . $extensions_array[$k];

				$files_array[] = $current_full_path . 'icon_up_arrow.' . $extensions_array[$k];
				$files_array[] = $current_full_path . 'icon_down_arrow.' . $extensions_array[$k];

				$files_array[] = $current_full_path . 'post.' . $extensions_array[$k];
				$files_array[] = $current_full_path . 'post_announce.' . $extensions_array[$k];
				$files_array[] = $current_full_path . 'post_announce_new.' . $extensions_array[$k];
				$files_array[] = $current_full_path . 'post_announce_new_posted.' . $extensions_array[$k];
				$files_array[] = $current_full_path . 'post_announce_posted.' . $extensions_array[$k];
				$files_array[] = $current_full_path . 'post_important.' . $extensions_array[$k];
				$files_array[] = $current_full_path . 'post_important_new.' . $extensions_array[$k];
				$files_array[] = $current_full_path . 'post_important_new_posted.' . $extensions_array[$k];
				$files_array[] = $current_full_path . 'post_important_posted.' . $extensions_array[$k];
				$files_array[] = $current_full_path . 'post_locked.' . $extensions_array[$k];
				$files_array[] = $current_full_path . 'post_locked_new.' . $extensions_array[$k];
				$files_array[] = $current_full_path . 'post_locked_new_posted.' . $extensions_array[$k];
				$files_array[] = $current_full_path . 'post_locked_posted.' . $extensions_array[$k];
				$files_array[] = $current_full_path . 'post_new.' . $extensions_array[$k];
				$files_array[] = $current_full_path . 'post_new_posted.' . $extensions_array[$k];
				$files_array[] = $current_full_path . 'post_posted.' . $extensions_array[$k];

				$files_array[] = $current_full_path . 'control_fastforward_blue.gif';
				$files_array[] = $current_full_path . 'control_rewind_blue.gif';

				$files_array[] = $current_full_path . 'post_next_.' . $extensions_array[$k];
				$files_array[] = $current_full_path . 'post_prev_.' . $extensions_array[$k];

				$files_array[] = $current_full_path . 'open2.png';
				$files_array[] = $current_full_path . 'dot_aqua.png';
				$files_array[] = $current_full_path . 'dot_blue.png';
				$files_array[] = $current_full_path . 'dot_green.png';
				$files_array[] = $current_full_path . 'dot_orange.png';
				$files_array[] = $current_full_path . 'dot_white.png';
				$files_array[] = $current_full_path . 'dot_white_alpha.png';
				$files_array[] = $current_full_path . 'dot_yellow.png';

				if (!in_array($templates_array[$i], $mg_themes_array))
				{
					$files_array[] = $current_full_path . 'icon_left_arrow3.gif';
					$files_array[] = $current_full_path . 'icon_right_arrow3.gif';
					$files_array[] = $current_full_path . 'lang_english/upi2db_mark.' . $extensions_array[$k];
					$files_array[] = $current_full_path . 'lang_english/upi2db_unmark.' . $extensions_array[$k];
					$files_array[] = $current_full_path . 'lang_english/upi2db_unread.' . $extensions_array[$k];
				}

				// ONLY FOR MG_THEMES - BEGIN
				if (in_array($templates_array[$i], $mg_themes_array))
				{
					// MOVE INTO BUTTONS - BEGIN
					$buttons_folder = 'buttons/';

					$input_images = array('arrow', 'arrow_down', 'arrow_down_alt', 'arrow_down_blue', 'arrow_down_rounded', 'arrow_down_rounded', 'arrow_left', 'arrow_left_alt', 'arrow_left_blue', 'arrow_left_rounded', 'arrow_left_rounded', 'arrow_next_blue', 'arrow_previous_blue', 'arrow_right', 'arrow_right_alt', 'arrow_right_blue', 'arrow_right_rounded', 'arrow_right_rounded', 'arrow_up', 'arrow_up_alt', 'arrow_up_blue', 'arrow_up_rounded', 'arrow_up_rounded', 'cat_block', 'forum_link', 'forum_nor_ar_read', 'forum_nor_locked_read', 'forum_nor_read', 'forum_nor_unread', 'forum_sub_ar_read', 'forum_sub_locked_read', 'forum_sub_locked_unread', 'forum_sub_read', 'forum_sub_unread', 'icon_aim2', 'icon_down_arrow', 'icon_download2', 'icon_email2', 'icon_empty_squared', 'icon_hidden2', 'icon_icq2', 'icon_ip2', 'icon_jabber2', 'icon_minicat', 'icon_minicat_lock', 'icon_minicat_new', 'icon_minilink', 'icon_minipost', 'icon_minipost_lock', 'icon_minipost_new', 'icon_msn2', 'icon_offline2', 'icon_online2', 'icon_post', 'icon_post_new', 'icon_reply', 'icon_reply_new', 'icon_skype2', 'icon_tiny_topic', 'icon_topic_reported', 'icon_topic_unapproved', 'icon_up_arrow', 'icon_view2', 'icon_yim2', 'icon_yim2', 'maximise', 'maximise_', 'menu_sep', 'menu_sep_np', 'minimise', 'minimise_', 'pm_inbox', 'pm_outbox', 'pm_read', 'pm_replied', 'pm_savebox', 'pm_sentbox', 'pm_unread', 'post_next', 'post_next_', 'post_prev', 'post_prev_', 'topic_ann_locked_read', 'topic_ann_locked_read_own', 'topic_ann_locked_unread', 'topic_ann_locked_unread_own', 'topic_ann_read', 'topic_ann_read_own', 'topic_ann_unread', 'topic_ann_unread_own', 'topic_ar_read', 'topic_glo_locked_read', 'topic_glo_locked_read_own', 'topic_glo_locked_unread', 'topic_glo_locked_unread_own', 'topic_glo_read', 'topic_glo_read_own', 'topic_glo_unread', 'topic_glo_unread_own', 'topic_hot_locked_read', 'topic_hot_locked_read_own', 'topic_hot_locked_unread', 'topic_hot_locked_unread_own', 'topic_hot_read', 'topic_hot_read_own', 'topic_hot_unread', 'topic_hot_unread_own', 'topic_imp_locked_read', 'topic_imp_locked_read_own', 'topic_imp_locked_unread', 'topic_imp_locked_unread_own', 'topic_imp_read', 'topic_imp_read_own', 'topic_imp_unread', 'topic_imp_unread_own', 'topic_moved', 'topic_nor_locked_read', 'topic_nor_locked_read_own', 'topic_nor_locked_unread', 'topic_nor_locked_unread_own', 'topic_nor_read', 'topic_nor_read_own', 'topic_nor_unread', 'topic_nor_unread_own', 'upi2db_mark', 'upi2db_unmark', 'upi2db_unread');

					$output_images = array();
					for ($n = 0; $n < sizeof($input_images); $n++)
					{
						$output_images[$n] = $buttons_folder . $input_images[$n];
					}

					if (!is_dir(IP_ROOT_PATH . $current_full_path . $buttons_folder))
					{
						mkdir(IP_ROOT_PATH . $current_full_path . $buttons_folder);
						copy(IP_ROOT_PATH . $current_full_path . 'index.html', IP_ROOT_PATH . $current_full_path . $buttons_folder . 'index.html');
					}

					for ($m = 0; $m < sizeof($input_images); $m++)
					{
						$input_image = $input_images[$m];
						$output_image = $output_images[$m];
						$input_file = IP_ROOT_PATH . $current_full_path . $input_image . '.' . $extensions_array[$k];
						$output_file = IP_ROOT_PATH . $current_full_path . $output_image . '.' . $extensions_array[$k];
						$action_result = (file_exists($input_file) ? rename($input_file, $output_file) : false);
					}
					// MOVE INTO BUTTONS - END

					// RENAME LANG BUTTONS - BEGIN
					$input_images = array('icon_aim2', 'icon_hidden2', 'icon_icq2', 'icon_ip2', 'icon_jabber2', 'icon_msn2', 'icon_offline2', 'icon_online2', 'icon_skype2', 'icon_yim2', 'icon_download2', 'icon_view2', 'maximise', 'minimise');

					$output_images = array('icon_im_aim', 'icon_user_hidden', 'icon_im_icq', 'icon_user_ip', 'icon_im_jabber', 'icon_im_msn', 'icon_user_offline', 'icon_user_online', 'icon_im_skype', 'icon_im_yahoo', 'icon_topic_download', 'icon_topic_view', 'switch_maximise', 'switch_minimise');

					$input_images_lang = array('announce', 'button_pm_new', 'button_pm_reply', 'download', 'global', 'icon_aim', 'icon_album', 'icon_approve', 'icon_censor', 'icon_delete', 'icon_download', 'icon_edit', 'icon_email', 'icon_email2', 'icon_global_announce_l', 'icon_hidden', 'icon_icq', 'icon_ip', 'icon_jabber', 'icon_msn', 'icon_offline', 'icon_offtopic', 'icon_online', 'icon_pa_download', 'icon_pa_email', 'icon_pa_post_comment', 'icon_pa_rate', 'icon_pa_upload', 'icon_pm', 'icon_profile', 'icon_quick_quote', 'icon_quote', 'icon_search', 'icon_skype', 'icon_sticky_l', 'icon_unapprove', 'icon_view', 'icon_www', 'icon_yim', 'jupload_pic', 'locked', 'manage_pic', 'new_post', 'new_topic', 'normal', 'normal_view', 'pm_reply', 'post_reply', 'quick_reply', 'show_all_comments', 'show_all_pics', 'show_all_ratings', 'showall', 'simple_view', 'sticky', 'thanks', 'topic_bin', 'topic_copy', 'topic_delete', 'topic_lock', 'topic_merge', 'topic_move', 'topic_split', 'topic_unlock', 'upload', 'upload_pic');

					$output_images_lang = array('modcp_announce', 'button_pm_new', 'button_pm_reply', 'button_download', 'modcp_global', 'icon_im_aim', 'icon_user_album', 'icon_post_approve', 'icon_post_censor', 'icon_post_delete', 'icon_post_download', 'icon_post_edit', 'icon_user_email', 'icon_user_email2', 'modcp_global_announce_l', 'icon_user_hidden', 'icon_im_icq', 'icon_user_ip', 'icon_im_jabber', 'icon_im_msn', 'icon_user_offline', 'icon_post_offtopic', 'icon_user_online', 'button_pa_download', 'button_pa_email', 'button_pa_post_comment', 'button_pa_rate', 'button_pa_upload', 'icon_user_pm', 'icon_user_profile', 'icon_post_quick_quote', 'icon_post_quote', 'icon_user_search', 'icon_im_skype', 'modcp_sticky_l', 'icon_post_unapprove', 'icon_post_view', 'icon_user_www', 'icon_im_yahoo', 'button_jupload_pic', 'button_locked', 'button_manage_pic', 'button_new_post', 'button_new_topic', 'modcp_normal', 'button_normal_view', 'button_pm_reply_small', 'button_post_reply', 'button_quick_reply', 'button_show_all_comments', 'button_show_all_pics', 'button_show_all_ratings', 'button_showall', 'button_simple_view', 'modcp_sticky', 'button_thanks', 'modcp_bin', 'modcp_copy', 'modcp_delete', 'modcp_lock', 'modcp_merge', 'modcp_move', 'modcp_split', 'modcp_unlock', 'button_upload', 'button_upload_pic');

					for ($m = 0; $m < sizeof($input_images); $m++)
					{
						$input_image = $input_images[$m];
						$output_image = $output_images[$m];
						$input_file = IP_ROOT_PATH . $current_full_path . $buttons_folder . $input_image . '.' . $extensions_array[$k];
						$output_file = IP_ROOT_PATH . $current_full_path . $buttons_folder . $output_image . '.' . $extensions_array[$k];
						$action_result = (file_exists($input_file) ? rename($input_file, $output_file) : false);
					}

					for ($l = 0; $l < sizeof($input_paths_lang); $l++)
					{
						if (is_dir(IP_ROOT_PATH . $current_full_path . $input_paths_lang[$l]))
						{
							for ($m = 0; $m < sizeof($input_images_lang); $m++)
							{
								$input_file = IP_ROOT_PATH . $current_full_path . $input_paths_lang[$l] . $input_images_lang[$m] . '.' . $extensions_array[$k];
								$output_file = IP_ROOT_PATH . $current_full_path . $output_paths_lang[$l] . $output_images_lang[$m] . '.' . $extensions_array[$k];
								//echo($input_file . ' - ' . $output_file . '<br />');
								$action_result = (file_exists($input_file) ? rename($input_file, $output_file) : false);
							}
						}
					}
					// RENAME LANG BUTTONS - END

					// DUPLICATE MISSED TOPICS ICONS - BEGIN
					$buttons_folder = 'buttons/';
					$buttons_types = array('glo', 'ann', 'imp', 'hot');
					$input_images = array('locked_read', 'locked_read_own', 'locked_unread', 'locked_unread_own', 'read', 'read_own', 'unread', 'unread_own');
					for ($l = 0; $l < sizeof($buttons_types); $l++)
					{
						for ($m = 0; $m < sizeof($input_images); $m++)
						{
							$input_image = 'topic_nor_' . $input_images[$m] . '.' . $extensions_array[$k];
							$output_image = 'topic_' . $buttons_types[$l] . '_' . $input_images[$m] . '.' . $extensions_array[$k];
							$input_file = IP_ROOT_PATH . $current_full_path . $buttons_folder . $input_image;
							$output_file = IP_ROOT_PATH . $current_full_path . $buttons_folder . $output_image;
							$action_result = ((file_exists($input_file) && !file_exists($output_file)) ? copy($input_file, $output_file) : false);
						}
					}
					// DUPLICATE MISSED TOPICS ICONS - END
				}
				// ONLY FOR MG_THEMES - END

				// DUPLICATE MISSED BUTTONS - BEGIN
				// You need to leave this code here (after buttons moving/renaming)... because some buttons won't be renamed correctly otherwise
				$input_images_lang = array('modcp_split');
				$output_images_lang = array('modcp_copy');

				for ($l = 0; $l < sizeof($input_paths_lang); $l++)
				{
					if (is_dir(IP_ROOT_PATH . $current_full_path . $input_paths_lang[$l]))
					{
						for ($m = 0; $m < sizeof($input_images_lang); $m++)
						{
							$input_file = IP_ROOT_PATH . $current_full_path . $input_paths_lang[$l] . $input_images_lang[$m] . '.' . $extensions_array[$k];
							$output_file = IP_ROOT_PATH . $current_full_path . $output_paths_lang[$l] . $output_images_lang[$m] . '.' . $extensions_array[$k];
							$action_result = ((file_exists($input_file) && !file_exists($output_file)) ? copy($input_file, $output_file) : false);
						}
					}
				}
				// DUPLICATE MISSED BUTTONS - END

			}
		}
	}
}

// FIX PA_FILE PATHS - BEGIN
/*
$old_pafile_folders = array(IP_ROOT_PATH . 'pafiledb/uploads/', IP_ROOT_PATH . 'pafiledb/images/screenshots/');
$new_pafile_folders = array(IP_ROOT_PATH . 'downloads/', IP_ROOT_PATH . 'files/screenshots/');
for ($pac = 0; $pac < sizeof($old_pafile_folders); $pac++)
{
	if (is_dir($old_pafile_folders[$pac] . $file) && is_dir($new_pafile_folders[$pac] . $file))
	{
		$dir = @opendir($old_pafile_folders[$pac]);
		while($file = readdir($dir))
		{
			if (!is_dir($file))
			{
				$process_item = (($file != '.') && ($file != '..') && !is_link($file)) ? true : false;
				if(($process_item) && !file_exists($new_pafile_folders[$pac] . $file))
				{
					$result = @rename($old_pafile_folders[$pac] . $file, $new_pafile_folders[$pac] . $file);
				}
			}
		}
	}
}
*/
// FIX PA_FILE PATHS - END

?>