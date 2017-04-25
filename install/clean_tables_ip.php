<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/*
* Modified version of the one created by: Vic D'Elfant <vic@phpbb.com>
*/

define('IN_ICYPHOENIX', true);
//if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);

// Language
$lang['title'] = 'Clean Icy Phoenix SQL tables';
$lang['incompatible_dbms'] = 'This script will only work with MySQL';
$lang['explain'] = 'This script has generated a list with fieldnames and tables in your Icy Phoenix DB which have (probably) been created by MODs.<br /><br />You can prevent a fieldname, table or configuration setting from being dropped by removing the check in front of that specific item. After you have made sure that no valuable fields, tables or configuration settings will be dropped you can click \'Clean tables\' to drop the selected items.<br /><br /><br /><b>Note that it is entirely your own responsibility to create a backup of your SQL database before running this script</b>';
$lang['unknown_fields'] = 'Unknown fieldnames';
$lang['unknown_tables'] = 'Unknown tables';
$lang['unknown_config'] = 'Unknown configuration settings';
$lang['no_fields_found'] = 'No unknown fieldnames have been found';
$lang['no_tables_found'] = 'No unknown tables have been found';
$lang['no_config_found'] = 'No unknown configuration settings have been found';
$lang['submit_button_caption'] = 'Clean tables';
$lang['cleaned_title'] = 'Cleaned successfully';
$lang['cleaned_explain'] = '<b>Your tables have successfully been cleaned</b></span><br /><br />Now delete this file';

$cleaned = false;

// $html class
class html
{
	var $table_name;

	function title($caption)
	{
		print '<tr><th colspan="2">' . $caption . '</th></tr>';
	}

	function header()
	{
		print '<tr>';
	}

	function table_name($table_name)
	{
		$this->table_name = $table_name;

		print '<td class="row1" align="left" valign="top" width="20%" style="padding-top: 5; padding-left: 5"><span class="genmed"><b>' . $table_name . '</b></span></td><td class="row2"><table>';
	}

	function right_row($type, $title)
	{
		if ($type == 'field')
		{
			print '<tr><td width="15"><input type="checkbox" name="' . $this->table_name . '__' . $title . '" checked="checked" value="1" /></td><td><span class="genmed">' . $title . '</span></td></tr>';
		}
		elseif ($type == 'config')
		{
			print '<tr><td width="15"><input type="checkbox" name="' . 'config__' . $title . '" checked="checked" value="1" /></td><td><span class="genmed">' . $title . '</span></td></tr>';
		}
		else
		{
			print '<tr><td width="15"><input type="checkbox" name="' . $title . '" checked="checked" value="1" /></td><td><span class="genmed">' . $title . '</span></td></tr>';
		}
	}

	function footer()
	{
		print '</table></td></tr>' . "\n";
	}
}

$html = new html();

// Did the user submitted the form?
if (isset($_POST['submit']))
{
	reset($_POST);
	while (list($key, $value) = each($_POST))
	{
		$key = htmlspecialchars(addslashes($key));
		$key = $db->sql_escape($key);

		$value = intval($value);

		if ($key != 'submit')
		{
			// Drop a config setting
			if (strstr($key, 'config__') && $value == 1)
			{
				$field = substr($key, 8);

				$sql = "DELETE FROM " . CONFIG_TABLE . " WHERE config_name = '" . $field . "'";
				$result = $db->sql_query($sql);
			}

			// Drop a field
			elseif (strstr($key, '__') && $value == 1)
			{
				$table = substr($key, 0, strpos($key, '__'));
				$field = substr($key, (strpos($key, '__') + 2));

				$sql = "ALTER TABLE " . $table . " DROP " . $field;
				$result = $db->sql_query($sql);
			}

			// Drop a table
			elseif ($value == 1)
			{
				$table = $key;

				$sql = "DROP TABLE " . $table;
				$result = $db->sql_query($sql);
			}
		}
	}

	$cleaned = true;
}

// This array holds the table names which are created by Icy Phoenix, as well as their fieldnames
$config_records = array('active_sessions', 'active_users_color', 'active_users_legend', 'ads_fib', 'ads_fit', 'ads_fix', 'ads_glb', 'ads_glf', 'ads_glh', 'ads_glt', 'ads_nmb', 'ads_nmt', 'ads_vfb', 'ads_vft', 'ads_vfx', 'ads_vtb', 'ads_vtt', 'ads_vtx', 'adsense_code', 'ajax_checks_register', 'ajax_features', 'allow_all_bbcode', 'allow_autologin', 'allow_avatar_generator', 'allow_avatar_local', 'allow_avatar_remote', 'allow_avatar_upload', 'allow_bbcode', 'allow_drafts', 'allow_ext_rating', 'allow_ftp_upload', 'allow_html', 'allow_html_only_for_admins', 'allow_html_tags', 'allow_moderators_edit_tags', 'allow_mods_edit_admin_posts', 'allow_mods_view_self', 'allow_namechange', 'allow_news', 'allow_only_id2_admin', 'allow_pm_attach', 'allow_rerate', 'allow_rss', 'allow_sig', 'allow_smilies', 'allow_theme_create', 'allow_zebra', 'always_show_edit_by', 'aprvmArchive', 'aprvmIP', 'aprvmRows', 'aprvmVersion', 'aprvmView', 'attach_version', 'attachment_quota', 'attachment_topic_review', 'attachments_stats', 'auth_view_pic_upload', 'autolink_first', 'avatar_filesize', 'avatar_gallery_path', 'avatar_generator_template_path', 'avatar_generator_version', 'avatar_max_height', 'avatar_max_width', 'avatar_path', 'bb_usage_stats_prscale', 'bb_usage_stats_specialgrp', 'bb_usage_stats_trscale', 'bb_usage_stats_viewlevel', 'bb_usage_stats_viewoptions', 'bin_forum', 'birthday_check_day', 'birthday_greeting', 'birthday_required', 'birthday_viewtopic', 'bluecard_limit', 'bluecard_limit_2', 'board_disable', 'board_disable_mess_st', 'board_disable_message', 'board_email', 'board_email_form', 'board_email_sig', 'board_startdate', 'board_timezone', 'bots_color', 'bots_legend', 'bots_reg_auth', 'bottom_html_block_text', 'browser_check', 'calendar_birthday', 'calendar_birthday_over', 'calendar_display_open', 'calendar_display_open_over', 'calendar_forum', 'calendar_forum_over', 'calendar_header_cells', 'calendar_header_cells_over', 'calendar_nb_row', 'calendar_nb_row_over', 'calendar_text_length', 'calendar_title_length', 'calendar_week_start', 'calendar_week_start_over', 'captcha_arcs', 'captcha_background_color', 'captcha_chess', 'captcha_config_01', 'captcha_config_02', 'captcha_ellipses', 'captcha_font', 'captcha_foreground_lattice_x', 'captcha_foreground_lattice_y', 'captcha_gammacorrect', 'captcha_height', 'captcha_image', 'captcha_jpeg', 'captcha_jpeg_quality', 'captcha_lattice_color', 'captcha_lines', 'captcha_pre_letters', 'captcha_pre_letters_great', 'captcha_width', 'cash_adminbig', 'cash_adminnavbar', 'cash_disable', 'cash_disable_spam_message', 'cash_disable_spam_num', 'cash_disable_spam_time', 'cash_display_after_posts', 'cash_installed', 'cash_post_message', 'cash_version', 'challenges_sent', 'check_anon_ip_when_rating', 'cms_version', 'config_cache', 'config_id', 'cookie_domain', 'cookie_name', 'cookie_path', 'cookie_secure', 'coppa_fax', 'coppa_mail', 'cron_birthdays_interval', 'cron_birthdays_last_run', 'cron_cache_interval', 'cron_cache_last_run', 'cron_database_interval', 'cron_database_last_run', 'cron_db_count', 'cron_db_show_begin_for', 'cron_db_show_not_optimized', 'cron_digests_interval', 'cron_digests_last_run', 'cron_files_interval', 'cron_files_last_run', 'cron_global_switch', 'cron_lock', 'cron_lock_hour', 'cron_queue_interval', 'cron_queue_last_run', 'cron_sessions_interval', 'cron_sessions_last_run', 'cron_sql_interval', 'cron_sql_last_run', 'cron_topics_interval', 'cron_topics_last_run', 'cron_users_interval', 'cron_users_last_run', 'ctracker_auto_recovery', 'ctracker_autoban_mails', 'ctracker_footer_layout', 'ctracker_global_message', 'ctracker_global_message_type', 'ctracker_ipblock_enabled', 'ctracker_ipblock_logsize', 'ctracker_last_checksum_scan', 'ctracker_last_file_scan', 'ctracker_login_history', 'ctracker_login_history_count', 'ctracker_login_ip_check', 'ctracker_loginfeature', 'ctracker_logsize_logins', 'ctracker_logsize_spammer', 'ctracker_massmail_protection', 'ctracker_massmail_time', 'ctracker_pw_complex', 'ctracker_pw_complex_min', 'ctracker_pw_complex_mode', 'ctracker_pw_control', 'ctracker_pw_reset_feature', 'ctracker_pw_validity', 'ctracker_pwreset_time', 'ctracker_reg_blocktime', 'ctracker_reg_ip_scan', 'ctracker_reg_last_reg', 'ctracker_reg_lastip', 'ctracker_reg_protection', 'ctracker_search_count_guest', 'ctracker_search_count_user', 'ctracker_search_feature_enabled', 'ctracker_search_time_guest', 'ctracker_search_time_user', 'ctracker_spam_attack_boost', 'ctracker_spam_keyword_det', 'ctracker_spammer_blockmode', 'ctracker_spammer_postcount', 'ctracker_spammer_time', 'ctracker_vconfirm_guest', 'current_ina_date', 'db_log_actions', 'dbmtnc_disallow_postcounter', 'dbmtnc_disallow_rebuild', 'dbmtnc_rebuild_end', 'dbmtnc_rebuild_pos', 'dbmtnc_rebuildcfg_maxmemory', 'dbmtnc_rebuildcfg_minposts', 'dbmtnc_rebuildcfg_php3only', 'dbmtnc_rebuildcfg_php3pps', 'dbmtnc_rebuildcfg_php4pps', 'dbmtnc_rebuildcfg_timelimit', 'dbmtnc_rebuildcfg_timeoverwrite', 'default_avatar_guests_url', 'default_avatar_set', 'default_avatar_users_url', 'default_cash', 'default_dateformat', 'default_dst_time_lag', 'default_lang', 'default_pm_quota', 'default_reward_dbfield', 'default_style', 'default_time_mode', 'default_upload_quota', 'disable_acronyms', 'disable_attachments_mod', 'disable_autolinks', 'disable_censor', 'disable_email_error', 'disable_html_guests', 'disable_likes_posts', 'disable_logins', 'disable_referers', 'disable_topic_view', 'display_order', 'display_shouts', 'display_tags_box', 'display_viewonline', 'display_viewonline_over', 'download_path', 'dst_time_lag', 'edit_notes', 'edit_notes_n', 'email_notification_html', 'emails_only_to_admins', 'enable_colorpicker', 'enable_confirm', 'enable_custom_bbcodes', 'enable_gravatars', 'enable_new_messages_number', 'enable_own_icons', 'enable_postimage_org', 'enable_quick_quote', 'enable_xs_version_check', 'extra_display', 'extra_max', 'fast_n_furious', 'flash_autoplay', 'flood_interval', 'footer_table_text', 'force_large_caps_mods', 'force_server_vars', 'form_token_lifetime', 'forum_limit_edit_time_interval', 'forum_tags_type', 'forum_wordgraph', 'ftp_pass', 'ftp_pasv_mode', 'ftp_path', 'ftp_server', 'ftp_user', 'ftr_all_users', 'ftr_disable', 'ftr_install_time', 'ftr_message', 'ftr_topic_number', 'gamelib_path', 'games_path', 'games_per_page', 'gd_version', 'gender_required', 'global_disable_acronyms', 'global_disable_autolinks', 'global_disable_censor', 'global_disable_upi2db', 'google_analytics', 'gravatar_default_image', 'gravatar_rating', 'gsearch_guests', 'gzip_compress', 'gzip_level', 'header_banner_text', 'header_rating_return_limit', 'header_table_text', 'hidde_last_logon', 'hot_threshold', 'html_email', 'img_create_thumbnail', 'img_display_inlined', 'img_imagick', 'img_link_height', 'img_link_width', 'img_max_height', 'img_max_width', 'img_min_thumb_filesize', 'img_shoutbox', 'ina_button_option', 'ina_cash_name', 'ina_challenge', 'ina_challenge_msg', 'ina_challenge_sub', 'ina_char_change_age_cost', 'ina_char_change_char_cost', 'ina_char_change_from_cost', 'ina_char_change_gender_cost', 'ina_char_change_intrests_cost', 'ina_char_change_name_cost', 'ina_char_change_saying_cost', 'ina_char_change_title_cost', 'ina_char_ge_per_beat_score', 'ina_char_ge_per_game', 'ina_char_ge_per_trophy', 'ina_char_name_effects_costs', 'ina_char_saying_effects_costs', 'ina_char_show_viewprofile', 'ina_char_show_viewtopic', 'ina_char_title_effects_costs', 'ina_daily_game_date', 'ina_daily_game_id', 'ina_daily_game_random', 'ina_default_charge', 'ina_default_g_height', 'ina_default_g_path', 'ina_default_g_reward', 'ina_default_g_width', 'ina_default_increment', 'ina_default_order', 'ina_delete', 'ina_disable_challenges_page', 'ina_disable_cheat', 'ina_disable_comments_page', 'ina_disable_everything', 'ina_disable_gamble_page', 'ina_disable_submit_scores_g', 'ina_disable_submit_scores_m', 'ina_disable_top5_page', 'ina_disable_trophy_page', 'ina_email_sent', 'ina_force_registration', 'ina_game_pass_cost', 'ina_game_pass_length', 'ina_guest_play', 'ina_jackpot_pool', 'ina_join_block', 'ina_join_block_count', 'ina_max_gamble', 'ina_max_games_per_day', 'ina_max_games_per_day_date', 'ina_new_game_count', 'ina_new_game_limit', 'ina_online_list_color', 'ina_online_list_text', 'ina_pm_trophy', 'ina_pm_trophy_msg', 'ina_pm_trophy_sub', 'ina_pop_game_limit', 'ina_post_block', 'ina_post_block_count', 'ina_rating_reward', 'ina_show_view_profile', 'ina_show_view_topic', 'ina_trophy_king', 'ina_use_daily_game', 'ina_use_logo', 'ina_use_max_games_per_day', 'ina_use_newest', 'ina_use_online', 'ina_use_rating_reward', 'ina_use_shoutbox', 'ina_use_trophy', 'ina_version', 'inactive_users_memberlists', 'index_birthday', 'index_last_msgs', 'index_rating_return', 'index_shoutbox', 'index_top_posters', 'ip_admins_only', 'ip_check', 'ip_version', 'large_rating_return_limit', 'last_logins_n', 'last_msgs_n', 'last_msgs_x', 'last_topic_title', 'last_topic_title_length', 'last_topic_title_over', 'last_user_id', 'limit_load', 'limit_search_load', 'link_this_topic', 'liw_attach_enabled', 'liw_enabled', 'liw_max_width', 'liw_sig_enabled', 'lofi_bots', 'login_reset_time', 'logs_path', 'max_attachments', 'max_attachments_pm', 'max_autologin_time', 'max_filesize', 'max_filesize_pm', 'max_inbox_privmsgs', 'max_link_bookmarks', 'max_login_attempts', 'max_poll_options', 'max_posts', 'max_savebox_privmsgs', 'max_sentbox_privmsgs', 'max_sig_chars', 'max_topics', 'max_user_age', 'max_user_bancard', 'max_users', 'message_board_disable_text', 'mg_log_actions', 'min_rates_number', 'min_user_age', 'new_msgs_mumber', 'news_base_url', 'news_index_file', 'news_item_num', 'news_item_trim', 'news_path', 'news_rss_cat', 'news_rss_desc', 'news_rss_image', 'news_rss_image_desc', 'news_rss_item_count', 'news_rss_language', 'news_rss_show_abstract', 'news_rss_ttl', 'news_title_trim', 'no_bump', 'no_right_click', 'online_last_msgs', 'online_shoutbox', 'online_time', 'override_user_style', 'page_gen', 'page_title_simple', 'points_name', 'portal_last_msgs', 'portal_shoutbox', 'posts_per_page', 'posts_precompiled', 'privmsg_disable', 'prune_enable', 'prune_shouts', 'quote_iterations', 'rand_seed', 'rand_seed_last_update', 'rating_max', 'read_only_forum', 'record_online_date', 'record_online_users', 'referer_validation', 'registration_closed', 'registration_status', 'report_cheater', 'report_forum', 'require_activation', 'robots_index_topics_no_replies', 'script_path', 'search_flood_interval', 'search_min_chars', 'select_lang', 'select_theme', 'sendmail_fix', 'server_name', 'server_port', 'session_last_gc', 'session_length', 'shout_allow_guest', 'shoutbox_floodinterval', 'show_alpha_bar', 'show_apcp', 'show_calendar_box_index', 'show_chat_online', 'show_forums_online_users', 'show_icons', 'show_img_no_gd', 'show_new_reply_posting', 'show_pic_size_on_thumb', 'show_random_quote', 'show_rss_forum_icon', 'show_social_bookmarks', 'show_thanks_profile', 'show_thanks_viewtopic', 'show_topic_description', 'sig_line', 'similar_ignore_forums_ids', 'similar_max_topics', 'similar_sort_type', 'similar_stopwords', 'similar_topics', 'site_desc', 'site_history', 'sitemap_announce_priority', 'sitemap_default_priority', 'sitemap_sort', 'sitemap_sticky_priority', 'sitemap_topic_limit', 'sitename', 'smart_header', 'smilie_columns', 'smilie_rows', 'smilie_single_row', 'smilie_window_columns', 'smilie_window_rows', 'smilies_insert', 'smilies_path', 'smilies_topic_title', 'smtp_delivery', 'smtp_host', 'smtp_password', 'smtp_port', 'smtp_username', 'split_cat', 'split_cat_over', 'split_ga_ann_sticky', 'stored_shouts', 'sub_forum', 'sub_forum_over', 'sub_level_links', 'sub_level_links_over', 'switch_bbcb_active_content', 'switch_bottom_html_block', 'switch_footer_table', 'switch_header_banner', 'switch_header_table', 'switch_poster_info_topic', 'switch_top_html_block', 'switch_viewtopic_banner', 'thumbnail_cache', 'thumbnail_highslide', 'thumbnail_lightbox', 'thumbnail_posts', 'thumbnail_quality', 'thumbnail_size', 'top_html_block_text', 'topic_icon', 'topics_per_page', 'upi2db_auto_read', 'upi2db_del_mark', 'upi2db_del_perm', 'upi2db_delete_old_data', 'upi2db_edit_as_new', 'upi2db_edit_color', 'upi2db_edit_topic_first', 'upi2db_install_time', 'upi2db_last_edit_as_new', 'upi2db_mark_color', 'upi2db_max_mark_posts', 'upi2db_max_new_posts', 'upi2db_max_new_posts_admin', 'upi2db_max_new_posts_mod', 'upi2db_max_permanent_topics', 'upi2db_no_group_min_posts', 'upi2db_no_group_min_regdays', 'upi2db_no_group_upi2db_on', 'upi2db_on', 'upi2db_unread_color', 'upi2db_version', 'upload_dir', 'upload_img', 'url_rw', 'url_rw_guests', 'use_allowance_system', 'use_captcha', 'use_cash_system', 'use_gamelib', 'use_gd2', 'use_gk_shop', 'use_point_system', 'use_rewards_mod', 'version', 'viewtopic_banner_text', 'visit_counter', 'visit_counter_switch', 'warn_cheater', 'wma_autoplay', 'word_graph_max_words', 'word_graph_word_counts', 'write_digests_log', 'write_errors_log', 'xmas_gfx', 'xs_add_comments', 'xs_auto_compile', 'xs_auto_recompile', 'xs_check_switches', 'xs_def_template', 'xs_downloads_count', 'xs_downloads_default', 'xs_ftp_host', 'xs_ftp_login', 'xs_ftp_path', 'xs_news_dateformat', 'xs_news_version', 'xs_php', 'xs_show_news', 'xs_show_news_subtitle', 'xs_show_ticker', 'xs_show_ticker_subtitle', 'xs_shownav', 'xs_template_time', 'xs_use_cache', 'xs_version', 'xs_warn_includes', 'site_meta_keywords', 'site_meta_keywords_switch', 'site_meta_description', 'site_meta_description_switch', 'site_meta_author', 'site_meta_author_switch', 'site_meta_copyright', 'site_meta_copyright_switch', 'spam_posts_number', 'spam_disable_url', 'spam_hide_signature', 'spam_post_edit_interval', 'mobile_style_disable', 'session_gc', 'session_last_visit_reset', 'check_dnsbl', 'check_dnsbl_posting', 'ajax_chat_msgs_refresh', 'ajax_chat_session_refresh', 'ajax_chat_session_clean', 'ajax_chat_link_type', 'ajax_chat_notification', 'ajax_chat_check_online', 'google_custom_search', 'use_jquery_tags', 'user_allow_pm_register', 'enable_social_connect', 'enable_facebook_login', 'facebook_app_id', 'facebook_app_secret', 'thumbnail_s_size', 'img_list_cols', 'img_list_rows', 'cookie_law');

$valid_fields = array(
	$table_prefix . 'acl_groups' => array('group_id', 'forum_id', 'auth_option_id', 'auth_role_id', 'auth_setting'),
	$table_prefix . 'acl_options' => array('auth_option_id', 'auth_option', 'is_global', 'is_local', 'founder_only'),
	$table_prefix . 'acl_roles' => array('role_id', 'role_name', 'role_description', 'role_type', 'role_order'),
	$table_prefix . 'acl_roles_data' => array('role_id', 'auth_option_id', 'auth_setting'),
	$table_prefix . 'acl_users' => array('user_id', 'forum_id', 'auth_option_id', 'auth_role_id', 'auth_setting'),
	$table_prefix . 'acronyms' => array('acronym_id', 'acronym', 'description'),
	$table_prefix . 'adminedit' => array('edit_id', 'edituser', 'editok'),
	$table_prefix . 'ads' => array('ad_id', 'ad_title', 'ad_text', 'ad_position', 'ad_auth', 'ad_format', 'ad_active'),
	$table_prefix . 'ajax_shoutbox' => array('shout_id', 'user_id', 'shouter_name', 'shout_text', 'shouter_ip', 'shout_time', 'shout_room'),
	$table_prefix . 'ajax_shoutbox_sessions' => array('session_id', 'session_user_id', 'session_username', 'session_ip', 'session_start', 'session_time'),
	$table_prefix . 'attach_quota' => array('user_id', 'group_id', 'quota_type', 'quota_limit_id'),
	$table_prefix . 'attachments' => array('attach_id', 'post_id', 'privmsgs_id', 'user_id_1', 'user_id_2'),
	$table_prefix . 'attachments_desc' => array('attach_id', 'physical_filename', 'real_filename', 'download_count', 'comment', 'extension', 'mimetype', 'filesize', 'filetime', 'thumbnail'),
	$table_prefix . 'attachments_stats' => array('attach_id', 'user_id', 'user_ip', 'user_browser', 'download_time'),
	$table_prefix . 'auth_access' => array('group_id', 'forum_id', 'auth_view', 'auth_read', 'auth_post', 'auth_reply', 'auth_edit', 'auth_delete', 'auth_sticky', 'auth_announce', 'auth_globalannounce', 'auth_news', 'auth_cal', 'auth_vote', 'auth_pollcreate', 'auth_attachments', 'auth_download', 'auth_ban', 'auth_greencard', 'auth_bluecard', 'auth_rate', 'auth_mod'),
	$table_prefix . 'autolinks' => array('link_id', 'link_keyword', 'link_title', 'link_url', 'link_comment', 'link_style', 'link_forum', 'link_int'),
	$table_prefix . 'banlist' => array('ban_id', 'ban_userid', 'ban_ip', 'ban_email', 'ban_start', 'ban_end', 'ban_by_userid', 'ban_priv_reason', 'ban_pub_reason_mode', 'ban_pub_reason'),
	$table_prefix . 'bbcodes' => array('bbcode_id', 'bbcode_tag', 'bbcode_helpline', 'display_on_posting', 'bbcode_match', 'bbcode_tpl', 'first_pass_match', 'first_pass_replace', 'second_pass_match', 'second_pass_replace'),
	$table_prefix . 'blogs' => array('blog_id', 'blog_owner', 'blog_user_id_create', 'blog_user_id_update', 'blog_time_creation', 'blog_time_update', 'blog_title', 'blog_description', 'blog_status', 'blog_notifications', 'blog_auth_read', 'blog_auth_post', 'blog_auth_reply', 'blog_auth_edit', 'blog_auth_delete'),
	$table_prefix . 'blogs_posts' => array('post_id', 'topic_id', 'blog_id', 'poster_id', 'post_time', 'poster_ip', 'poster_email', 'post_username', 'post_subject', 'post_text', 'post_status', 'post_flags'),
	$table_prefix . 'blogs_topics' => array('topic_id', 'blog_id', 'topic_title', 'topic_title_clean', 'topic_desc', 'topic_poster', 'topic_time', 'topic_views', 'topic_replies', 'topic_status', 'topic_approved', 'topic_first_post_id', 'topic_first_post_time', 'topic_first_poster_id', 'topic_first_poster_name', 'topic_first_poster_color', 'topic_last_post_id', 'topic_last_post_time', 'topic_last_poster_id', 'topic_last_poster_name', 'topic_last_poster_color', 'topic_rating'),
	$table_prefix . 'bookmarks' => array('topic_id', 'user_id'),
	$table_prefix . 'bots' => array('bot_id', 'bot_active', 'bot_name', 'bot_color', 'bot_agent', 'bot_ip', 'bot_last_visit', 'bot_visit_counter'),
	$table_prefix . 'cash' => array('cash_id', 'cash_order', 'cash_settings', 'cash_dbfield', 'cash_name', 'cash_default', 'cash_decimals', 'cash_imageurl', 'cash_exchange', 'cash_perpost', 'cash_postbonus', 'cash_perreply', 'cash_perthanks', 'cash_maxearn', 'cash_perpm', 'cash_perchar', 'cash_allowance', 'cash_allowanceamount', 'cash_allowancetime', 'cash_allowancenext', 'cash_forumlist'),
	$table_prefix . 'cash_events' => array('event_name', 'event_data'),
	$table_prefix . 'cash_exchange' => array('ex_cash_id1', 'ex_cash_id2', 'ex_cash_enabled'),
	$table_prefix . 'cash_groups' => array('group_id', 'group_type', 'cash_id', 'cash_perpost', 'cash_postbonus', 'cash_perreply', 'cash_perchar', 'cash_maxearn', 'cash_perpm', 'cash_allowance', 'cash_allowanceamount', 'cash_allowancetime', 'cash_allowancenext'),
	$table_prefix . 'cash_log' => array('log_id', 'log_time', 'log_type', 'log_action', 'log_text'),
	$table_prefix . 'cms_block_position' => array('bpid', 'layout', 'pkey', 'bposition'),
	$table_prefix . 'cms_block_settings' => array('bs_id', 'user_id', 'name', 'content', 'blockfile', 'view', 'type', 'groups', 'locked'),
	$table_prefix . 'cms_block_variable' => array('bvid', 'bid', 'label', 'sub_label', 'config_name', 'field_options', 'field_values', 'type', 'block'),
	$table_prefix . 'cms_blocks' => array('bid', 'bs_id', 'block_cms_id', 'layout', 'layout_special', 'title', 'bposition', 'weight', 'active', 'border', 'titlebar', 'background', 'local'),
	$table_prefix . 'cms_config' => array('id', 'bid', 'config_name', 'config_value'),
	$table_prefix . 'cms_layout' => array('lid', 'name', 'filename', 'template', 'layout_cms_id', 'global_blocks', 'page_nav', 'config_vars', 'view', 'groups'),
	$table_prefix . 'cms_layout_special' => array('lsid', 'page_id', 'locked', 'name', 'filename', 'template', 'global_blocks', 'page_nav', 'config_vars', 'view', 'groups'),
	$table_prefix . 'cms_nav_menu' => array('menu_item_id', 'menu_id', 'menu_parent_id', 'cat_id', 'cat_parent_id', 'menu_status', 'menu_order', 'menu_icon', 'menu_name_lang', 'menu_name', 'menu_desc', 'menu_link', 'menu_link_external', 'auth_view', 'auth_view_group', 'menu_default'),
	$table_prefix . 'config' => array('config_name', 'config_value'),
	$table_prefix . 'confirm' => array('confirm_id', 'session_id', 'confirm_type', 'code', 'seed'),
	$table_prefix . 'ctracker_backup' => array('config_name', 'config_value'),
	$table_prefix . 'ctracker_filechk' => array('filepath', 'hash'),
	$table_prefix . 'ctracker_filescanner' => array('id', 'filepath', 'safety'),
	$table_prefix . 'ctracker_ipblocker' => array('id', 'ct_blocker_value'),
	$table_prefix . 'ctracker_loginhistory' => array('ct_user_id', 'ct_login_ip', 'ct_login_time'),
	$table_prefix . 'db_fields' => array('field_id', 'table_id', 'field_name', 'field_lang_key', 'field_table_id', 'field_input_id', 'field_input', 'field_default_value', 'field_values', 'field_hidden', 'field_default', 'field_weight', 'field_style'),
	$table_prefix . 'db_main' => array('db_id', 'db_title', 'db_desc', 'db_owner', 'db_content', 'db_file', 'db_groups_view', 'db_groups_insert', 'db_groups_edit', 'db_groups_delete'),
	$table_prefix . 'digest_subscribed_forums' => array('user_id', 'forum_id'),
	$table_prefix . 'digest_subscriptions' => array('user_id', 'digest_type', 'format', 'show_text', 'show_mine', 'new_only', 'send_on_no_messages', 'send_hour', 'text_length'),
	$table_prefix . 'disallow' => array('disallow_id', 'disallow_username'),
	$table_prefix . 'downloads' => array('id', 'description', 'file_name', 'klicks', 'free', 'extern', 'long_desc', 'sort', 'cat', 'hacklist', 'hack_author', 'hack_author_email', 'hack_author_website', 'hack_version', 'hack_dl_url', 'test', 'req', 'todo', 'warning', 'mod_desc', 'mod_list', 'file_size', 'change_time', 'add_time', 'rating', 'file_traffic', 'overall_klicks', 'approve', 'add_user', 'change_user', 'last_time', 'down_user', 'thumbnail', 'broken'),
	$table_prefix . 'downloads_cat' => array('id', 'parent', 'path', 'cat_name', 'sort', 'description', 'rules', 'auth_view', 'auth_dl', 'auth_up', 'auth_mod', 'must_approve', 'allow_mod_desc', 'statistics', 'stats_prune', 'comments', 'cat_traffic', 'cat_traffic_use', 'allow_thumbs', 'auth_cread', 'auth_cpost', 'approve_comments', 'bug_tracker'),
	$table_prefix . 'drafts' => array('draft_id', 'user_id', 'topic_id', 'forum_id', 'save_time', 'draft_subject', 'draft_message'),
	$table_prefix . 'extension_groups' => array('group_id', 'group_name', 'cat_id', 'allow_group', 'download_mode', 'upload_icon', 'max_filesize', 'forum_permissions'),
	$table_prefix . 'extensions' => array('ext_id', 'group_id', 'extension', 'comment'),
	$table_prefix . 'flags' => array('flag_id', 'flag_name', 'flag_image'),
	$table_prefix . 'forbidden_extensions' => array('ext_id', 'extension'),
	$table_prefix . 'force_read_users' => array('user', 'time'),
	$table_prefix . 'forum_prune' => array('prune_id', 'forum_id', 'prune_days', 'prune_freq'),
	$table_prefix . 'forums' => array('forum_id', 'forum_type', 'parent_id', 'main_type', 'left_id', 'right_id', 'forum_parents', 'forum_name', 'forum_name_clean', 'forum_desc', 'forum_status', 'forum_order', 'forum_posts', 'forum_topics', 'forum_last_topic_id', 'forum_last_post_id', 'forum_last_poster_id', 'forum_last_post_subject', 'forum_last_post_time', 'forum_last_poster_name', 'forum_last_poster_color', 'forum_postcount', 'forum_likes', 'forum_notify', 'forum_limit_edit_time', 'forum_similar_topics', 'forum_topic_views', 'forum_tags', 'forum_sort_box', 'forum_kb_mode', 'forum_index_icons', 'forum_rules_switch', 'forum_rules', 'forum_rules_display_title', 'forum_rules_custom_title', 'forum_rules_in_viewforum', 'forum_rules_in_viewtopic', 'forum_rules_in_posting', 'forum_recurring_first_post', 'forum_link', 'forum_link_internal', 'forum_link_hit_count', 'forum_link_hit', 'icon', 'prune_next', 'prune_enable', 'auth_view', 'auth_read', 'auth_post', 'auth_reply', 'auth_edit', 'auth_delete', 'auth_sticky', 'auth_announce', 'auth_globalannounce', 'auth_news', 'auth_cal', 'auth_vote', 'auth_pollcreate', 'auth_attachments', 'auth_download', 'auth_ban', 'auth_greencard', 'auth_bluecard', 'auth_rate'),
	$table_prefix . 'forums_watch' => array('forum_id', 'user_id', 'notify_status'),
	$table_prefix . 'groups' => array('group_id', 'group_type', 'group_founder_manage', 'group_name', 'group_description', 'group_display', 'group_moderator', 'group_single_user', 'group_rank', 'group_color', 'group_legend', 'group_legend_order', 'group_sig_chars', 'group_receive_pm', 'group_message_limit', 'group_max_recipients', 'group_skip_auth', 'group_count', 'group_count_max', 'group_count_enable', 'group_dl_auto_traffic', 'upi2db_on', 'upi2db_min_posts', 'upi2db_min_regdays'),
	$table_prefix . 'hacks_list' => array('hack_id', 'hack_name', 'hack_desc', 'hack_author', 'hack_author_email', 'hack_author_website', 'hack_version', 'hack_hide', 'hack_download_url', 'hack_file', 'hack_file_mtime'),
	$table_prefix . 'jr_admin_users' => array('user_id', 'user_jr_admin', 'start_date', 'update_date', 'admin_notes', 'notes_view'),
	$table_prefix . 'liw_cache' => array('image_checksum', 'image_width', 'image_height'),
	$table_prefix . 'log' => array('log_id', 'log_type', 'user_id', 'forum_id', 'topic_id', 'reportee_id', 'log_ip', 'log_time', 'log_operation', 'log_data'),
	$table_prefix . 'logins' => array('login_id', 'login_userid', 'login_ip', 'login_user_agent', 'login_time'),
	$table_prefix . 'logs' => array('log_id', 'log_time', 'log_page', 'log_user_id', 'log_action', 'log_desc', 'log_target'),
	$table_prefix . 'megamail' => array('mail_id', 'mailsession_id', 'mass_pm', 'user_id', 'group_id', 'email_subject', 'email_body', 'email_format', 'batch_start', 'batch_size', 'batch_wait', 'status'),
	$table_prefix . 'moderator_cache' => array('forum_id', 'user_id', 'username', 'group_id', 'group_name', 'display_on_index'),
	$table_prefix . 'modules' => array('module_id', 'module_enabled', 'module_display', 'module_basename', 'module_class', 'parent_id', 'left_id', 'right_id', 'module_langname', 'module_mode', 'module_auth'),
	$table_prefix . 'news' => array('news_id', 'news_category', 'news_image'),
	$table_prefix . 'notes' => array('id', 'text'),
	$table_prefix . 'pa_auth' => array('group_id', 'cat_id', 'auth_view', 'auth_read', 'auth_view_file', 'auth_edit_file', 'auth_delete_file', 'auth_upload', 'auth_download', 'auth_rate', 'auth_email', 'auth_view_comment', 'auth_post_comment', 'auth_edit_comment', 'auth_delete_comment', 'auth_mod', 'auth_search', 'auth_stats', 'auth_toplist', 'auth_viewall'),
	$table_prefix . 'pa_cat' => array('cat_id', 'cat_name', 'cat_desc', 'cat_parent', 'parents_data', 'cat_order', 'cat_allow_file', 'cat_allow_ratings', 'cat_allow_comments', 'cat_files', 'cat_last_file_id', 'cat_last_file_name', 'cat_last_file_time', 'auth_view', 'auth_read', 'auth_view_file', 'auth_edit_file', 'auth_delete_file', 'auth_upload', 'auth_download', 'auth_rate', 'auth_email', 'auth_view_comment', 'auth_post_comment', 'auth_edit_comment', 'auth_delete_comment'),
	$table_prefix . 'pa_comments' => array('comments_id', 'file_id', 'comments_text', 'comments_title', 'comments_time', 'poster_id'),
	$table_prefix . 'pa_config' => array('config_name', 'config_value'),
	$table_prefix . 'pa_custom' => array('custom_id', 'custom_name', 'custom_description', 'data', 'field_order', 'field_type', 'regex'),
	$table_prefix . 'pa_customdata' => array('customdata_file', 'customdata_custom', 'data'),
	$table_prefix . 'pa_download_info' => array('file_id', 'user_id', 'downloader_ip', 'downloader_os', 'downloader_browser', 'browser_version'),
	$table_prefix . 'pa_files' => array('file_id', 'user_id', 'poster_ip', 'file_name', 'file_size', 'unique_name', 'real_name', 'file_dir', 'file_desc', 'file_creator', 'file_version', 'file_longdesc', 'file_ssurl', 'file_sshot_link', 'file_dlurl', 'file_time', 'file_update_time', 'file_catid', 'file_posticon', 'file_license', 'file_dls', 'file_last', 'file_pin', 'file_docsurl', 'file_approved', 'file_broken'),
	$table_prefix . 'pa_license' => array('license_id', 'license_name', 'license_text'),
	$table_prefix . 'pa_mirrors' => array('mirror_id', 'file_id', 'unique_name', 'file_dir', 'file_dlurl', 'mirror_location'),
	$table_prefix . 'pa_votes' => array('user_id', 'votes_ip', 'votes_file', 'rate_point', 'voter_os', 'voter_browser', 'browser_version'),
	$table_prefix . 'plugins' => array('plugin_name', 'plugin_version', 'plugin_dir', 'plugin_enabled'),
	$table_prefix . 'plugins_config' => array('config_name', 'config_value'),
	$table_prefix . 'poll_options' => array('poll_option_id', 'topic_id', 'poll_option_text', 'poll_option_total'),
	$table_prefix . 'poll_votes' => array('topic_id', 'poll_option_id', 'vote_user_id', 'vote_user_ip'),
	$table_prefix . 'posts' => array('post_id', 'topic_id', 'forum_id', 'poster_id', 'post_time', 'poster_ip', 'post_username', 'post_subject', 'post_text', 'post_text_compiled', 'enable_bbcode', 'enable_html', 'enable_smilies', 'enable_autolinks_acronyms', 'enable_sig', 'edit_notes', 'post_edit_time', 'post_edit_count', 'post_edit_id', 'post_attachment', 'post_bluecard', 'post_likes', 'post_images'),
	$table_prefix . 'posts_likes' => array('topic_id', 'post_id', 'user_id', 'like_time'),
	$table_prefix . 'privmsgs' => array('privmsgs_id', 'privmsgs_type', 'privmsgs_subject', 'privmsgs_text', 'privmsgs_from_userid', 'privmsgs_to_userid', 'privmsgs_date', 'privmsgs_ip', 'privmsgs_enable_bbcode', 'privmsgs_enable_html', 'privmsgs_enable_smilies', 'privmsgs_attach_sig', 'privmsgs_attachment', 'privmsgs_enable_autolinks_acronyms'),
	$table_prefix . 'privmsgs_archive' => array('privmsgs_id', 'privmsgs_type', 'privmsgs_subject', 'privmsgs_text', 'privmsgs_from_userid', 'privmsgs_to_userid', 'privmsgs_date', 'privmsgs_ip', 'privmsgs_enable_bbcode', 'privmsgs_enable_html', 'privmsgs_enable_smilies', 'privmsgs_attach_sig', 'privmsgs_attachment', 'privmsgs_enable_autolinks_acronyms'),
	$table_prefix . 'profile_fields' => array('field_id', 'field_name', 'field_description', 'field_type', 'text_field_default', 'text_field_maxlen', 'text_area_default', 'text_area_maxlen', 'radio_button_default', 'radio_button_values', 'checkbox_default', 'checkbox_values', 'is_required', 'users_can_view', 'view_in_profile', 'profile_location', 'view_in_memberlist', 'view_in_topic', 'topic_location'),
	$table_prefix . 'profile_view' => array('user_id', 'viewername', 'viewer_id', 'view_stamp', 'counter'),
	$table_prefix . 'quota_limits' => array('quota_limit_id', 'quota_desc', 'quota_limit'),
	$table_prefix . 'ranks' => array('rank_id', 'rank_title', 'rank_min', 'rank_special', 'rank_show_title', 'rank_image'),
	$table_prefix . 'rate_results' => array('rating_id', 'user_id', 'topic_id', 'rating', 'user_ip', 'rating_time'),
	$table_prefix . 'referers' => array('id', 'host', 'url', 't_url', 'ip', 'hits', 'firstvisit', 'lastvisit'),
	$table_prefix . 'registration' => array('topic_id', 'registration_user_id', 'registration_user_ip', 'registration_time', 'registration_status'),
	$table_prefix . 'registration_desc' => array('reg_id', 'topic_id', 'reg_active', 'reg_max_option1', 'reg_max_option2', 'reg_max_option3', 'reg_start', 'reg_length'),
	$table_prefix . 'search_results' => array('search_id', 'session_id', 'search_array', 'search_time'),
	$table_prefix . 'search_wordlist' => array('word_text', 'word_id', 'word_common'),
	$table_prefix . 'search_wordmatch' => array('post_id', 'word_id', 'title_match'),
	$table_prefix . 'sessions' => array('session_id', 'session_user_id', 'session_start', 'session_time', 'session_ip', 'session_browser', 'session_page', 'session_logged_in', 'session_forum_id', 'session_topic_id', 'session_last_visit', 'session_forwarded_for', 'session_viewonline', 'session_autologin', 'session_admin'),
	$table_prefix . 'sessions_keys' => array('key_id', 'user_id', 'last_ip', 'last_login'),
	$table_prefix . 'shout' => array('shout_id', 'shout_username', 'shout_user_id', 'shout_group_id', 'shout_session_time', 'shout_ip', 'shout_text', 'shout_active', 'enable_bbcode', 'enable_html', 'enable_smilies', 'enable_sig'),
	$table_prefix . 'site_history' => array('date', 'reg', 'hidden', 'guests', 'new_topics', 'new_posts'),
	$table_prefix . 'smilies' => array('smilies_id', 'code', 'smile_url', 'emoticon', 'smilies_order'),
	$table_prefix . 'stats_config' => array('config_name', 'config_value'),
	$table_prefix . 'stats_modules' => array('module_id', 'name', 'active', 'installed', 'display_order', 'update_time', 'auth_value', 'module_info_cache', 'module_db_cache', 'module_result_cache', 'module_info_time', 'module_cache_time'),
	$table_prefix . 'styles_downloads' => array('style_id', 'style_user_id', 'style_user_agent', 'style_user_ip', 'style_time', 'style_cat_id', 'style_style_id', 'style_filename'),
	$table_prefix . 'themes' => array('themes_id', 'template_name', 'style_name', 'head_stylesheet', 'body_background', 'body_bgcolor', 'tr_class1', 'tr_class2', 'tr_class3', 'td_class1', 'td_class2', 'td_class3'),
	$table_prefix . 'tickets_cat' => array('ticket_cat_id', 'ticket_cat_title', 'ticket_cat_des', 'ticket_cat_emails'),
	$table_prefix . 'topic_view' => array('topic_id', 'user_id', 'view_time', 'view_count'),
	$table_prefix . 'topics' => array('topic_id', 'forum_id', 'topic_title', 'topic_title_clean', 'topic_ftitle_clean', 'topic_tags', 'topic_desc', 'topic_similar_topics', 'topic_poster', 'topic_time', 'topic_views', 'topic_replies', 'topic_status', 'topic_type', 'poll_title', 'poll_start', 'poll_length', 'poll_max_options', 'poll_last_vote', 'poll_vote_change', 'topic_first_post_id', 'topic_first_post_time', 'topic_first_poster_id', 'topic_first_poster_name', 'topic_first_poster_color', 'topic_last_post_id', 'topic_last_post_time', 'topic_last_poster_name', 'topic_last_poster_color', 'topic_last_poster_id', 'topic_moved_id', 'topic_attachment', 'topic_label_compiled', 'news_id', 'topic_calendar_time', 'topic_calendar_duration', 'topic_reg', 'topic_rating', 'topic_show_portal'),
	$table_prefix . 'topics_labels' => array('id', 'label_name', 'label_code', 'label_bg_color', 'label_text_color', 'label_icon', 'date_format', 'admin_auth', 'mod_auth', 'poster_auth'),
	$table_prefix . 'topics_tags_list' => array('tag_text', 'tag_id', 'tag_count'),
	$table_prefix . 'topics_tags_match' => array('tag_id', 'topic_id', 'forum_id'),
	$table_prefix . 'topics_watch' => array('topic_id', 'forum_id', 'user_id', 'notify_status'),
	$table_prefix . 'upi2db_always_read' => array('topic_id', 'forum_id', 'user_id', 'last_update'),
	$table_prefix . 'upi2db_last_posts' => array('post_id', 'topic_id', 'forum_id', 'poster_id', 'post_time', 'post_edit_time', 'topic_type', 'post_edit_by'),
	$table_prefix . 'upi2db_unread_posts' => array('post_id', 'topic_id', 'forum_id', 'user_id', 'status', 'topic_type', 'last_update'),
	$table_prefix . 'user_group' => array('group_id', 'user_id', 'group_leader', 'user_pending'),
	$table_prefix . 'users' => array('user_id', 'user_active', 'user_mask', 'user_permissions', 'user_perm_from', 'username', 'username_clean', 'user_email', 'user_email_hash', 'user_facebook_id', 'user_google_id', 'user_first_name', 'user_last_name', 'user_password', 'user_passchg', 'user_pass_convert', 'user_form_salt', 'user_session_time', 'user_session_page', 'user_browser', 'user_lastvisit', 'user_regdate', 'user_type', 'user_level', 'user_ip', 'user_posts', 'user_timezone', 'user_style', 'user_lang', 'user_dateformat', 'user_new_privmsg', 'user_unread_privmsg', 'user_last_privmsg', 'user_private_chat_alert', 'user_emailtime', 'user_profile_view_popup', 'user_attachsig', 'user_setbm', 'user_options', 'user_allowhtml', 'user_allowbbcode', 'user_allowsmile', 'user_allowavatar', 'user_allow_pm', 'user_allow_pm_in', 'user_allow_viewemail', 'user_allow_mass_email', 'user_allow_viewonline', 'user_notify', 'user_notify_pm', 'user_popup_pm', 'user_rank', 'user_rank2', 'user_rank3', 'user_rank4', 'user_rank5', 'user_avatar', 'user_avatar_type', 'user_icq', 'user_jabber', 'user_website', 'user_from', 'user_sig', 'user_aim', 'user_yim', 'user_facebook', 'user_twitter', 'user_flickr', 'user_googleplus', 'user_youtube', 'user_linkedin', 'user_msnm', 'user_500px', 'user_github', 'user_instagram', 'user_pinterest', 'user_vimeo', 'user_occ', 'user_interests', 'user_actkey', 'user_newpasswd', 'ct_search_time', 'ct_search_count', 'ct_last_post', 'ct_post_counter', 'ct_enable_ip_warn', 'ct_last_used_ip', 'ct_global_msg_read', 'ct_miserable_user', 'ct_last_ip', 'user_birthday', 'user_birthday_y', 'user_birthday_m', 'user_birthday_d', 'user_next_birthday_greeting', 'user_sub_forum', 'user_split_cat', 'user_last_topic_title', 'user_sub_level_links', 'user_display_viewonline', 'group_id', 'user_color', 'user_gender', 'user_totaltime', 'user_totallogon', 'user_totalpages', 'user_calendar_display_open', 'user_calendar_header_cells', 'user_calendar_week_start', 'user_calendar_nb_row', 'user_calendar_birthday', 'user_calendar_forum', 'user_warnings', 'user_time_mode', 'user_dst_time_lag', 'user_skype', 'user_registered_ip', 'user_registered_hostname', 'user_profile_view', 'user_last_profile_view', 'user_topics_per_page', 'user_hot_threshold', 'user_posts_per_page', 'user_topic_show_days', 'user_topic_sortby_type', 'user_topic_sortby_dir', 'user_post_show_days', 'user_post_sortby_type', 'user_post_sortby_dir', 'user_allowswearywords', 'user_showavatars', 'user_showsignatures', 'user_login_attempts', 'user_last_login_attempt', 'user_sudoku_playing', 'user_from_flag', 'user_phone', 'user_selfdes', 'user_upi2db_which_system', 'user_upi2db_disable', 'user_upi2db_datasync', 'user_upi2db_new_word', 'user_upi2db_edit_word', 'user_upi2db_unread_color', 'user_personal_pics_count', 'user_trophies', 'ina_cheat_fix', 'ina_games_today', 'ina_last_visit_page', 'ina_last_playtype', 'ina_games_played', 'ina_game_playing', 'ina_game_pass', 'ina_games_pass_day', 'ina_time_playing', 'ina_settings', 'ina_char_name', 'ina_char_age', 'ina_char_from', 'ina_char_intrests', 'ina_char_img', 'ina_char_gender', 'ina_char_ge', 'ina_char_name_effects', 'ina_char_title_effects', 'ina_char_saying_effects', 'ina_char_views', 'ina_char_title', 'ina_char_saying', 'testing', 'user_allow_new_download_email', 'user_allow_fav_download_email', 'user_allow_new_download_popup', 'user_allow_fav_download_popup', 'user_dl_update_time', 'user_new_download', 'user_traffic', 'user_download_counter', 'user_dl_note_type', 'user_dl_sort_fix', 'user_dl_sort_opt', 'user_dl_sort_dir', 'user_euros'),
	$table_prefix . 'words' => array('word_id', 'word', 'replacement'),
	$table_prefix . 'xs_news' => array('news_id', 'news_date', 'news_text', 'news_display', 'news_smilies'),
	$table_prefix . 'xs_news_cfg' => array('config_name', 'config_value'),
	$table_prefix . 'xs_news_xml' => array('xml_id', 'xml_title', 'xml_show', 'xml_feed', 'xml_is_feed', 'xml_width', 'xml_height', 'xml_font', 'xml_speed', 'xml_direction'),
	$table_prefix . 'zebra' => array('user_id', 'zebra_id', 'friend', 'foe'),

	// PLUGINS

	// ALBUM
	$table_prefix . 'album' => array('pic_id', 'pic_filename', 'pic_size', 'pic_thumbnail', 'pic_title', 'pic_desc', 'pic_user_id', 'pic_username', 'pic_user_ip', 'pic_time', 'pic_cat_id', 'pic_view_count', 'pic_lock', 'pic_approval'),
	$table_prefix . 'album_cat' => array('cat_id', 'cat_title', 'cat_desc', 'cat_wm', 'cat_pics', 'cat_order', 'cat_view_level', 'cat_upload_level', 'cat_rate_level', 'cat_comment_level', 'cat_edit_level', 'cat_delete_level', 'cat_view_groups', 'cat_upload_groups', 'cat_rate_groups', 'cat_comment_groups', 'cat_edit_groups', 'cat_delete_groups', 'cat_moderator_groups', 'cat_approval', 'cat_parent', 'cat_user_id'),
	$table_prefix . 'album_comment' => array('comment_id', 'comment_pic_id', 'comment_cat_id', 'comment_user_id', 'comment_username', 'comment_user_ip', 'comment_time', 'comment_text', 'comment_edit_time', 'comment_edit_count', 'comment_edit_user_id'),
	$table_prefix . 'album_comment_watch' => array('pic_id', 'user_id', 'notify_status'),
	$table_prefix . 'album_config' => array('config_name', 'config_value'),
	$table_prefix . 'album_rate' => array('rate_pic_id', 'rate_user_id', 'rate_user_ip', 'rate_point', 'rate_hon_point'),

	// DOWNLOADS ADV
	$table_prefix . 'dl_auth' => array('cat_id', 'group_id', 'auth_view', 'auth_dl', 'auth_up', 'auth_mod'),
	$table_prefix . 'dl_banlist' => array('ban_id', 'user_id', 'user_ip', 'user_agent', 'username', 'guests'),
	$table_prefix . 'dl_bug_history' => array('report_his_id', 'df_id', 'report_id', 'report_his_type', 'report_his_date', 'report_his_value'),
	$table_prefix . 'dl_bug_tracker' => array('report_id', 'df_id', 'report_title', 'report_text', 'report_file_ver', 'report_date', 'report_author_id', 'report_assign_id', 'report_assign_date', 'report_status', 'report_status_date', 'report_php', 'report_db', 'report_forum'),
	$table_prefix . 'dl_comments' => array('dl_id', 'id', 'cat_id', 'user_id', 'username', 'comment_time', 'comment_edit_time', 'comment_text', 'approve'),
	$table_prefix . 'dl_config' => array('config_name', 'config_value'),
	$table_prefix . 'dl_ext_blacklist' => array('extention'),
	$table_prefix . 'dl_favorites' => array('fav_id', 'fav_dl_id', 'fav_dl_cat', 'fav_user_id'),
	$table_prefix . 'dl_hotlink' => array('user_id', 'session_id', 'hotlink_id', 'code'),
	$table_prefix . 'dl_notraf' => array('user_id', 'dl_id'),
	$table_prefix . 'dl_ratings' => array('dl_id', 'user_id', 'rate_point'),
	$table_prefix . 'dl_stats' => array('dl_id', 'id', 'cat_id', 'user_id', 'username', 'traffic', 'direction', 'user_ip', 'browser', 'time_stamp'),

	// KB
	$table_prefix . 'kb_articles' => array('article_id', 'article_category_id', 'article_title', 'article_description', 'article_date', 'article_author_id', 'username', 'article_body', 'article_type', 'approved', 'topic_id', 'views', 'article_rating', 'article_totalvotes'),
	$table_prefix . 'kb_categories' => array('category_id', 'category_name', 'category_details', 'number_articles', 'parent', 'cat_order', 'auth_view', 'auth_post', 'auth_rate', 'auth_comment', 'auth_edit', 'auth_delete', 'auth_approval', 'auth_approval_edit', 'auth_view_groups', 'auth_post_groups', 'auth_rate_groups', 'auth_comment_groups', 'auth_edit_groups', 'auth_delete_groups', 'auth_approval_groups', 'auth_approval_edit_groups', 'auth_moderator_groups', 'comments_forum_id'),
	$table_prefix . 'kb_config' => array('config_name', 'config_value'),
	$table_prefix . 'kb_custom' => array('custom_id', 'custom_name', 'custom_description', 'data', 'field_order', 'field_type', 'regex'),
	$table_prefix . 'kb_customdata' => array('customdata_file', 'customdata_custom', 'data'),
	$table_prefix . 'kb_results' => array('search_id', 'session_id', 'search_array'),
	$table_prefix . 'kb_search' => array('search_id', 'session_id', 'search_array'),
	$table_prefix . 'kb_types' => array('id', 'type'),
	$table_prefix . 'kb_votes' => array('votes_ip', 'votes_userid', 'votes_file'),
	$table_prefix . 'kb_wordlist' => array('word_text', 'word_id', 'word_common'),
	$table_prefix . 'kb_wordmatch' => array('article_id', 'word_id', 'title_match'),

	// LINKS
	$table_prefix . 'link_categories' => array('cat_id', 'cat_title', 'cat_order'),
	$table_prefix . 'link_config' => array('config_name', 'config_value'),
	$table_prefix . 'links' => array('link_id', 'link_title', 'link_desc', 'link_category', 'link_url', 'link_logo_src', 'link_joined', 'link_active', 'link_hits', 'user_id', 'user_ip', 'last_user_ip'),

	// SUDOKU
	$table_prefix . 'sudoku_sessions' => array('user_id', 'session_time'),
	$table_prefix . 'sudoku_solutions' => array('game_pack', 'game_num', 'line_1', 'line_2', 'line_3', 'line_4', 'line_5', 'line_6', 'line_7', 'line_8', 'line_9'),
	$table_prefix . 'sudoku_starts' => array('game_pack', 'game_num', 'game_level', 'line_1', 'line_2', 'line_3', 'line_4', 'line_5', 'line_6', 'line_7', 'line_8', 'line_9'),
	$table_prefix . 'sudoku_stats' => array('user_id', 'played', 'points'),
	$table_prefix . 'sudoku_users' => array('user_id', 'game_pack', 'game_num', 'game_level', 'line_1', 'line_2', 'line_3', 'line_4', 'line_5', 'line_6', 'line_7', 'line_8', 'line_9', 'points', 'done'),

);

// Figure out which fields do not belong in the SQL table, and store the names in $unknown_fields[tablename]
reset($valid_fields);
$unknown_fields = array();

while (list($table_name, $fields) = each($valid_fields))
{
	$result = $db->sql_query("SHOW FIELDS FROM $table_name");

	while ($record = $db->sql_fetchrow($result))
	{
		if (!in_array($record['Field'], $fields))
		{
			if (!is_array($unknown_fields[$table_name]))
			{
				$unknown_fields[$table_name] = array();
			}
			array_push($unknown_fields[$table_name], $record['Field']);
		}
	}
}

// Check all tables in the SQL db to see if any of them doesn't belong to the default phpBB installation
$tables = array();
$unknown_tables = array();

$result = $db->sql_query('SHOW TABLES');

while ($row = $db->sql_fetchrow($result))
{
	$current_table = $row['Tables_in_' . $dbname];
	$current_prefix = substr($current_table, 0, strlen($table_prefix));

	if ($current_prefix == $table_prefix)
	{
		array_push($tables, $current_table);
	}
}

reset($tables);
reset($valid_fields);

while (list(, $table_name) = each($tables))
{
	$match_found = false;

	reset($valid_fields);
	while (list($valid_table_name) = each($valid_fields))
	{
		if ($valid_table_name == $table_name)
		{
			$match_found = true;
		}
	}

	if (!$match_found)
	{
		array_push($unknown_tables, $table_name);
	}
}

// Now we'll go through phpbb_config
$unknown_config = array();

$sql = "SELECT config_name FROM " . CONFIG_TABLE . " WHERE config_name NOT IN ('" . implode("', '", $config_records) . "')";
$result = $db->sql_query($sql);

while ($config_data = $db->sql_fetchrow($result))
{
	$unknown_config[] = $config_data['config_name'];
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $lang['ENCODING']; ?>">
	<meta name="author" content="Icy Phoenix Team" />
	<title><?php echo $lang['title']; ?> :: Icy Phoenix</title>
	<link rel="stylesheet" href="./templates/common/acp.css" type="text/css" />
	<link rel="shortcut icon" href="./images/favicon.ico" />
	<!--[if lt IE 7]>
	<script type="text/javascript" src="./templates/common/js/pngfix.js"></script>
	<![endif]-->
</head>

<body>
<div id="global-wrapper" style="width: 960px; clear: both; margin: 0 auto;">
<div class="leftshadow"><div class="rightshadow"><div id="wrapper-inner">
<table id="forumtable" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td width="100%" colspan="3" valign="top">
		<div id="top_logo">
			<table class="" width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td height="150" align="left" valign="middle">
					<a href="http://www.icyphoenix.com" title="Icy Phoenix"><img src="./images/logo_ip.png" alt="Icy Phoenix" title="Icy Phoenix" /></a>
				</td>
			</tr>
			</table>
		</div>
	</td>
</tr>
<tr>
	<td width="100%" colspan="3" style="padding-left: 10px; padding-right: 10px;">

<?php
if (!$cleaned)
{
	?>
		<table class="forumline">
			<tr><td class="row-header" colspan="2"><span><?php echo $lang['title']; ?></span></td></tr>
			<tr><td class="row1" colspan="2"><br /><div class="gen"><?php echo $lang['explain']; ?></div><br /></td></tr>
		</table>
		<br />
	<?php
}
?>
		<form action="clean_tables_ip.<?php echo PHP_EXT; ?>" name="clean" method="post">
		<table class="forumline">
			<tr><td class="row-header" colspan="2"><span><?php echo $lang['title']; ?></span></td></tr>
<?php
if ($cleaned)
{
	$html->title($lang['cleaned_title']);
	print '<tr><td class="row1 row-center" colspan="2"><span class="genmed"><br /><span class="text_green">' . $lang['cleaned_explain'] . '<br />&nbsp;</span></td></tr>';
}
else
{
	// Unknown fields
	$html->title($lang['unknown_fields']);

	if (sizeof($unknown_fields) > 0)
	{
		reset($unknown_fields);

		while (list($table_name, $data) = each($unknown_fields))
		{
			$html->header();
			$html->table_name($table_name);

			while (list(, $fieldname) = each($data))
			{
				$html->right_row('field', $fieldname);
			}

			$html->footer();
		}
	}
	else
	{
		print '<tr><td class="row1 row-center" colspan="2"><span class="genmed"><br /><span class="text_green"><b>' . $lang['no_fields_found'] . '</b></span><br />&nbsp;</span></td></tr>';
	}

	// Unknown tables
	$html->title($lang['unknown_tables']);

	if (sizeof($unknown_tables) > 0)
	{
		reset($unknown_tables);

		$html->header();
		$html->table_name($lang['unknown_tables']);

		while (list(, $table_name) = each($unknown_tables))
		{
			$html->right_row('table', $table_name);
		}

		$html->footer();
	}
	else
	{
		print '<tr><td class="row1 row-center" colspan="2"><span class="genmed"><br /><span class="text_green"><b>' . $lang['no_tables_found'] . '</b></span><br />&nbsp;</span></td></tr>';
	}

	// Unknown config settings
	$html->title($lang['unknown_config']);

	if (sizeof($unknown_config) > 0)
	{
		reset($unknown_config);

		$html->header();
		$html->table_name($lang['unknown_config']);

		while (list(, $table_name) = each($unknown_config))
		{
			$html->right_row('config', $table_name);
		}

		$html->footer();
	}
	else
	{
		print '<tr><td class="row1 row-center" colspan="2"><span class="genmed"><br /><span class="text_green"><b>' . $lang['no_config_found'] . '</b></span><br />&nbsp;</span></td></tr>';
	}

	print '<tr><td class="cat" colspan="2"><input class="mainoption" name="submit" type="submit" value="' . $lang['submit_button_caption'] . '"' . ((sizeof($unknown_fields) == 0 && sizeof($unknown_tables) == 0 && sizeof($unknown_config) == 0) ? ' disabled="disabled"' : '') .  '/></td></tr>';
}
?>
			</td>
		</tr>
		</table>
		</form>
	</td>
</tr>
<tr>
	<td width="100%" colspan="3">
	<div id="bottom_logo_ext">
	<div id="bottom_logo">
		<table>
			<tr>
				<td nowrap="nowrap" width="45%" align="left">
					<span class="copyright">&nbsp;Powered by <a href="http://www.icyphoenix.com/" target="_blank">Icy Phoenix</a> based on <a href="http://www.phpbb.com/" target="_blank">phpBB</a></span>
				</td>
				<td class="tdalignc tdnw"><br />&nbsp;</td>
				<td nowrap="nowrap" width="45%" align="right">
					<span class="copyright">Design by <a href="http://www.lucalibralato.com/" target="_blank">Luca Libralato</a>&nbsp;</span>
				</td>
			</tr>
		</table>
	</div>
	</div>
	</td>
</tr>
</table>
</div></div></div>
</div>
</body>
</html>
