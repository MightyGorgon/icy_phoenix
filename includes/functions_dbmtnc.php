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
* Philipp Kordowich
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

// List of tables used
$tables = array(
	'acl_groups',
	'acl_options',
	'acl_roles',
	'acl_roles_data',
	'acl_users',
	'acronyms',
	'adminedit',
	'ajax_shoutbox',
	'ajax_shoutbox_sessions',
	'album',
	'album_cat',
	'album_comment',
	'album_comment_watch',
	'album_config',
	'album_rate',
	'attachments',
	'attachments_desc',
	'attachments_stats',
	'attach_quota',
	'auth_access',
	'autolinks',
	'banlist',
	'bbcodes',
	'bookmarks',
	'cash',
	'cash_events',
	'cash_exchange',
	'cash_groups',
	'cash_log',
	'cms_blocks',
	'cms_block_position',
	'cms_block_variable',
	'cms_layout',
	'cms_layout_special',
	'cms_config',
	'cms_nav_menu',
	'config',
	'confirm',
	/*
	'ctracker_backup',
	*/
	'ctracker_filechk',
	'ctracker_filescanner',
	'ctracker_ipblocker',
	'ctracker_loginhistory',
	'digest_subscriptions',
	'digest_subscribed_forums',
	'disallow',
	'drafts',
	'extensions',
	'extension_groups',
	'flags',
	'forbidden_extensions',
	'force_read_users',
	'forums',
	'forums_watch',
	'forum_prune',
	'groups',
	'hacks_list',
	'jr_admin_users',
	'kb_articles',
	'kb_categories',
	'kb_config',
	'kb_custom',
	'kb_customdata',
	'kb_results',
	'kb_search',
	'kb_types',
	'kb_votes',
	'kb_wordlist',
	'kb_wordmatch',
	'links',
	'link_categories',
	'link_config',
	'liw_cache',
	'logins',
	'log',
	'logs',
	'megamail',
	'moderator_cache',
	'modules',
	'news',
	'notes',
	'pa_auth',
	'pa_cat',
	'pa_comments',
	'pa_config',
	'pa_custom',
	'pa_customdata',
	'pa_download_info',
	'pa_files',
	'pa_license',
	'pa_mirrors',
	'pa_votes',
	'plugins',
	'plugins_config',
	'poll_options',
	'poll_votes',
	'posts',
	'posts_likes',
	'privmsgs',
	'privmsgs_archive',
	'profile_fields',
	'profile_view',
	'quota_limits',
	'ranks',
	'rate_results',
	'referers',
	'registration',
	'registration_desc',
	'search_results',
	'search_wordlist',
	'search_wordmatch',
	'sessions',
	'sessions_keys',
	'shout',
	'site_history',
	'smilies',
	'stats_config',
	'stats_modules',
	'sudoku_sessions',
	'sudoku_solutions',
	'sudoku_starts',
	'sudoku_stats',
	'sudoku_users',
	'themes',
	'tickets_cat',
	'topic_view',
	'topics',
	'topics_labels',
	'topics_tags_list',
	'topics_tags_match',
	'topics_watch',
	'upi2db_always_read',
	'upi2db_last_posts',
	'upi2db_unread_posts',
	'users',
	'user_group',
	'words',
	'xs_news',
	'xs_news_xml',
	'zebra'
);

if (!empty($config['plugins']['activity']['enabled']))
{
	$tables = array_merge($tables, array(
		'ina_ban',
		'ina_categories',
		'ina_challenge_tracker',
		'ina_challenge_users',
		'ina_chat',
		'ina_cheat_fix',
		'ina_data',
		'ina_favorites',
		'ina_gamble',
		'ina_gamble_in_progress',
		'ina_games',
		'ina_hall_of_fame',
		'ina_last_game_played',
		'ina_rating_votes',
		'ina_scores',
		'ina_sessions',
		'ina_top_scores',
		'ina_trophy_comments',
		)
	);
}

// List of configuration data required
$config_data = array('dbmtnc_disallow_postcounter', 'dbmtnc_disallow_rebuild', 'dbmtnc_rebuildcfg_maxmemory', 'dbmtnc_rebuildcfg_minposts', 'dbmtnc_rebuildcfg_php3only', 'dbmtnc_rebuildcfg_php3pps', 'dbmtnc_rebuildcfg_php4pps', 'dbmtnc_rebuildcfg_timeoverwrite', 'dbmtnc_rebuildcfg_timelimit', 'dbmtnc_rebuild_end', 'dbmtnc_rebuild_pos');

// Default configuration records - from installation file
$default_config = array(
	'config_id' => '1',
	'board_disable' => '0',
	'sitename' => 'Icy Phoenix',
	'site_desc' => 'Icy Phoenix',
	'cookie_name' => 'ip',
	'cookie_path' => '/',
	'cookie_domain' => '',
	'cookie_secure' => '0',
	'session_length' => '3600',
	'allow_html' => '0',
	'allow_html_tags' => 'a,b,i,u,pre,table,tr,td',
	'allow_bbcode' => '1',
	'allow_smilies' => '1',
	'allow_sig' => '1',
	'allow_namechange' => '0',
	'allow_theme_create' => '0',
	'allow_avatar_local' => '1',
	'allow_avatar_remote' => '1',
	'allow_avatar_upload' => '1',
	'enable_confirm' => '1',
	'override_user_style' => '0',
	'posts_per_page' => '20',
	'topics_per_page' => '100',
	'hot_threshold' => '40',
	'max_poll_options' => '10',
	'max_sig_chars' => '255',
	'max_inbox_privmsgs' => '50',
	'max_sentbox_privmsgs' => '25',
	'max_savebox_privmsgs' => '50',
	'board_email_sig' => 'Thanks, The Management',
	'board_email' => 'admin@domainname.com',
	'smtp_delivery' => '0',
	'smtp_host' => '',
	'smtp_username' => '',
	'smtp_password' => '',
	'sendmail_fix' => '0',
	'require_activation' => '1',
	'flood_interval' => '15',
	'board_email_form' => '1',
	'avatar_filesize' => '6144',
	'avatar_max_width' => '80',
	'avatar_max_height' => '80',
	'avatar_path' => 'images/avatars',
	'avatar_gallery_path' => 'images/avatars/gallery',
	'smilies_path' => 'images/smiles',
	'default_style' => '2',
	'default_dateformat' => 'D d M, Y H:i',
	'board_timezone' => '0',
	'prune_enable' => '1',
	'privmsg_disable' => '0',
	'gzip_compress' => '1',
	'coppa_fax' => '',
	'coppa_mail' => '',
	'record_online_users' => '0',
	'record_online_date' => '1163901212',
	'server_name' => 'localhost',
	'server_port' => '80',
	'script_path' => '/',
	'sig_line' => '____________',
	'birthday_required' => '0',
	'birthday_greeting' => '0',
	'max_user_age' => '100',
	'min_user_age' => '5',
	'birthday_check_day' => '7',
	'bluecard_limit' => '3',
	'bluecard_limit_2' => '1',
	'max_user_bancard' => '3',
	'report_forum' => '0',
	'index_rating_return' => '10',
	'min_rates_number' => '5',
	'rating_max' => '10',
	'allow_ext_rating' => '1',
	'large_rating_return_limit' => '30',
	'check_anon_ip_when_rating' => '1',
	'allow_rerate' => '1',
	'header_rating_return_limit' => '3',
	'default_time_mode' => '0',
	'default_dst_time_lag' => '60',
	'search_flood_interval' => '15',
	'rand_seed' => '00a028026ec9f2303da985f6f447382e',
	'allow_news' => '1',
	'news_item_trim' => '100',
	'news_title_trim' => '100',
	'news_item_num' => '10',
	'news_path' => 'images/forums',
	'allow_rss' => '1',
	'news_rss_desc' => '',
	'news_rss_language' => 'en_us',
	'news_rss_ttl' => '60',
	'news_rss_cat' => '',
	'news_rss_image' => '',
	'news_rss_image_desc' => '',
	'news_rss_item_count' => '15',
	'news_rss_show_abstract' => '1',
	'news_base_url' => '',
	'news_index_file' => 'index.php',
	'default_avatar_guests_url' => 'images/avatars/default_avatars/guest.gif',
	'default_avatar_users_url' => 'images/avatars/default_avatars/member.gif',
	'enable_gravatars' => '1',
	'gravatar_rating' => 'PG',
	'gravatar_default_image' => 'images/avatars/default_avatars/member.gif',
	'default_avatar_set' => '2',
	'bin_forum' => '0',
	'liw_enabled' => '0',
	'liw_sig_enabled' => '0',
	'liw_max_width' => '500',
	'liw_attach_enabled' => '0',
	'xs_news_version' => '2.0.3',
	'board_disable_message' => 'Site disabled',
	'board_disable_mess_st' => '1',
	'sitemap_announce_priority' => '1.0',
	'sitemap_default_priority' => '0.5',
	'sitemap_sort' => 'DESC',
	'sitemap_sticky_priority' => '0.75',
	'sitemap_topic_limit' => '250',
	'registration_status' => '0',
	'registration_closed' => '',
	'prune_shouts' => '0',
	'xs_shownav' => '17',
	'allow_avatar_generator' => '1',
	'avatar_generator_template_path' => 'images/avatars/generator_templates',
	'avatar_generator_version' => '2.0.2',
	'max_login_attempts' => '5',
	'login_reset_time' => '30',
	'hidde_last_logon' => '0',
	'online_time' => '60',
	'gzip_level' => '9',
	'gender_required' => '0',
	'smilie_columns' => '3',
	'smilie_rows' => '3',
	'smilie_window_columns' => '3',
	'smilie_window_rows' => '3',
	'smilie_single_row' => '20',
	'allow_autologin' => '1',
	'max_autologin_time' => '0',
	'autolink_first' => '0',
	'smilies_insert' => '1',
	'max_link_bookmarks' => '0',
	'visit_counter' => '0',
	'word_graph_max_words' => '100',
	'word_graph_word_counts' => '1',
	'search_min_chars' => '3',
	'extra_max' => '0',
	'extra_display' => '0',
	'upi2db_max_permanent_topics' => '20',
	'upi2db_del_mark' => '60',
	'upi2db_del_perm' => '120',
	'upi2db_max_mark_posts' => '10',
	'upi2db_unread_color' => 'aaffcc',
	'upi2db_edit_color' => 'ffccaa',
	'upi2db_mark_color' => 'ffffaa',
	'upi2db_auto_read' => '30',
	'upi2db_edit_as_new' => '1',
	'upi2db_last_edit_as_new' => '1',
	'upi2db_on' => '0',
	'upi2db_edit_topic_first' => '1',
	'upi2db_no_group_min_regdays' => '0',
	'upi2db_no_group_min_posts' => '0',
	'upi2db_no_group_upi2db_on' => '1',
	'upi2db_install_time' => '$install_time',
	'upi2db_delete_old_data' => '1175555067',
	'upi2db_max_new_posts' => '1000',
	'upi2db_version' => '3.0.7',
	'use_captcha' => '0',
	'version' => '.0.22',
	'board_startdate' => '1162429182',
	'default_lang' => 'english',
	'calendar_display_open' => '0',
	'calendar_display_open_over' => '0',
	'calendar_header_cells' => '5',
	'calendar_header_cells_over' => '0',
	'calendar_week_start' => '1',
	'calendar_week_start_over' => '0',
	'calendar_title_length' => '30',
	'calendar_text_length' => '200',
	'calendar_nb_row' => '5',
	'calendar_nb_row_over' => '0',
	'calendar_birthday' => '1',
	'calendar_birthday_over' => '0',
	'calendar_forum' => '1',
	'calendar_forum_over' => '0',
	'sub_forum' => '1',
	'sub_forum_over' => '0',
	'split_cat' => '1',
	'split_cat_over' => '0',
	'last_topic_title' => '1',
	'last_topic_title_over' => '0',
	'last_topic_title_length' => '24',
	'sub_level_links' => '2',
	'sub_level_links_over' => '0',
	'display_viewonline' => '2',
	'display_viewonline_over' => '0',
	'max_posts' => '52',
	'max_topics' => '37',
	'max_users' => '7',
	'xs_auto_compile' => '1',
	'xs_auto_recompile' => '1',
	'xs_use_cache' => '1',
	'xs_php' => 'php',
	'xs_def_template' => 'default',
	'xs_check_switches' => '1',
	'xs_warn_includes' => '1',
	'xs_add_comments' => '0',
	'xs_ftp_host' => '',
	'xs_ftp_login' => '',
	'xs_ftp_path' => '',
	'xs_downloads_count' => '0',
	'xs_downloads_default' => '0',
	'xs_template_time' => '1162429386',
	'xs_version' => '8',
	'similar_stopwords' => '0',
	'similar_ignore_forums_ids' => '',
	'similar_sort_type' => 'relev',
	'similar_max_topics' => '5',
	'similar_topics' => '1',
	'bb_usage_stats_viewlevel' => '16',
	'bb_usage_stats_viewoptions' => '1',
	'bb_usage_stats_specialgrp' => '-1',
	'bb_usage_stats_prscale' => '1',
	'bb_usage_stats_trscale' => '1',
	'forum_wordgraph' => '1',
	'shoutbox_floodinterval' => '3',
	'display_shouts' => '5',
	'stored_shouts' => '20',
	'shout_allow_guest' => '2',
	'upi2db_max_new_posts_admin' => '0',
	'upi2db_max_new_posts_mod' => '2000',
	'show_calendar_box_index' => '0',
	'url_rw' => '0',
	'switch_header_table' => '0',
	'header_table_text' => 'Text',
	'fast_n_furious' => '0',
	'new_msgs_mumber' => '0',
	'index_last_msgs' => '0',
	'portal_last_msgs' => '0',
	'online_last_msgs' => '1',
	'index_shoutbox' => '0',
	'portal_shoutbox' => '0',
	'online_shoutbox' => '1',
	'last_msgs_n' => '5',
	'last_msgs_x' => '',
	'posts_precompiled' => '1',
	'index_birthday' => '0',
	'site_history' => '0',
	'smilies_topic_title' => '0',
	'html_email' => '1',
	'config_cache' => '1',
	'ftr_disable' => '1',
	'disable_logins' => '1',
	'last_logins_n' => '10',
	'edit_notes' => '1',
	'edit_notes_n' => '5',
	'quote_iterations' => '2',
	'page_gen' => '1',
	'birthday_viewtopic' => '0',
	'img_shoutbox' => '0',
	'split_ga_ann_sticky' => '1',
	'email_notification_html' => '1',
	'select_theme' => '1',
	'select_lang' => '1',
	'show_icons' => '1',
	'show_random_quote' => '0',
	'visit_counter_switch' => '0',
	'emails_only_to_admins' => '1',
	'no_right_click' => '0',
	'gd_version' => '2',
	'show_img_no_gd' => '0',
	'show_pic_size_on_thumb' => '0',
	'thumbnail_posts' => '1',
	'thumbnail_cache' => '1',
	'thumbnail_quality' => '75',
	'thumbnail_size' => '400',
	'disable_html_guests' => '0',
	'disable_email_error' => '1',
	'switch_poster_info_topic' => '0',
	'switch_bbcb_active_content' => '1',
	'enable_quick_quote' => '1',
	'enable_xs_version_check' => '0',
	'allow_all_bbcode' => '1',
	'xmas_gfx' => '0',
	'logs_path' => 'logs',
	'url_rw_guests' => '0',
	'lofi_bots' => '0',
	'ajax_checks_register' => '1',
	'inactive_users_memberlists' => '0',
	'auth_view_pic_upload' => '1',
	'enable_postimage_org' => '0',
	'enable_new_messages_number' => '0',
	'ajax_features' => '0',
	'show_rss_forum_icon' => '1',
	'disable_acronyms' => '1',
	'disable_autolinks' => '1',
	'disable_censor' => '1',
	'global_disable_acronyms' => '1',
	'global_disable_autolinks' => '1',
	'global_disable_censor' => '1',
	'disable_topic_view' => '1',
	'disable_referers' => '1',
	'aprvmArchive' => '0',
	'aprvmVersion' => '1.6.0',
	'aprvmView' => '0',
	'aprvmRows' => '25',
	'aprvmIP' => '1',
	'page_title_simple' => '0',
	'mg_log_actions' => '0',
	'active_users_color' => '#224455',
	'active_users_legend' => '1',
	'bots_color' => '#667788',
	'bots_legend' => '1',
	'show_social_bookmarks' => '0',
	'show_forums_online_users' => '0',
	'allow_drafts' => '1',
	'main_admin_id' => '2',
	'allow_mods_edit_admin_posts' => '1',
	'force_large_caps_mods' => '1',
	'enable_colorpicker' => '0',
	'always_show_edit_by' => '0',
	'show_new_reply_posting' => '1',
	'show_chat_online' => '0',
	'allow_zebra' => '1',
	'allow_mods_view_self' => '0',
	'enable_own_icons' => '1',
	'show_thanks_profile' => '0',
	'show_thanks_viewtopic' => '0',
	'index_top_posters' => '1',
	'global_disable_upi2db' => '0',
	'last_user_id' => '2',
	'write_errors_log' => '0',
	'write_digests_log' => '0',
	'no_bump' => '0',
	'link_this_topic' => '0',
	'show_alpha_bar' => '0',
	'db_log_actions' => '1',
	'show_topic_description' => '0',
	'bots_reg_auth' => '0',
	'cron_global_switch' => '0',
	'cron_lock' => '0',
	'cron_lock_hour' => '0',
	'cron_queue_interval' => '0',
	'cron_queue_last_run' => '0',
	'cron_digests_interval' => '0',
	'cron_digests_last_run' => '0',
	'cron_files_interval' => '0',
	'cron_files_last_run' => '0',
	'cron_database_interval' => '0',
	'cron_database_last_run' => '0',
	'cron_cache_interval' => '0',
	'cron_cache_last_run' => '0',
	'cron_sql_interval' => '0',
	'cron_sql_last_run' => '0',
	'cron_users_interval' => '0',
	'cron_users_last_run' => '0',
	'cron_topics_interval' => '0',
	'cron_topics_last_run' => '0',
	'cron_sessions_interval' => '0',
	'cron_sessions_last_run' => '0',
	'cron_db_count' => '0',
	'cron_db_show_begin_for' => '',
	'cron_db_show_not_optimized' => '0',
	'rand_seed_last_update' => '0',
	'gsearch_guests' => '1',
	'ads_glt' => '0',
	'ads_glb' => '0',
	'ads_glh' => '0',
	'ads_glf' => '0',
	'ads_fix' => '0',
	'ads_fit' => '0',
	'ads_fib' => '0',
	'ads_vfx' => '0',
	'ads_vft' => '0',
	'ads_vfb' => '0',
	'ads_vtx' => '0',
	'ads_vtt' => '0',
	'ads_vtb' => '0',
	'ads_nmt' => '0',
	'ads_nmb' => '0',
	'adsense_code' => '',
	'google_analytics' => '',
	'thumbnail_highslide' => '1',
	'read_only_forum' => '0',
	'forum_limit_edit_time_interval' => '1440',
	'ftr_topic_number' => '0',
	'ftr_message' => 'Before going on... please make sure you have read and understood this post. It contains important informations regarding this site.',
	'ftr_install_time' => '0',
	'ftr_all_users' => '0',
	'allow_html_only_for_admins' => '0',
	'display_tags_box' => '0',
	'allow_moderators_edit_tags' => '0',
	'enable_custom_bbcodes' => '0',
	'forum_tags_type' => '0',
	'ctracker_ipblock_enabled' => '0',
	'ctracker_ipblock_logsize' => '100',
	'ctracker_auto_recovery' => '0',
	'ctracker_vconfirm_guest' => '1',
	'ctracker_autoban_mails' => '1',
	'ctracker_search_time_guest' => '30',
	'ctracker_search_time_user' => '20',
	'ctracker_search_count_guest' => '1',
	'ctracker_search_count_user' => '4',
	'ctracker_massmail_protection' => '0',
	'ctracker_reg_protection' => '0',
	'ctracker_reg_blocktime' => '30',
	'ctracker_reg_lastip' => '0.0.0.0',
	'ctracker_pwreset_time' => '20',
	'ctracker_massmail_time' => '20',
	'ctracker_spammer_time' => '30',
	'ctracker_spammer_postcount' => '4',
	'ctracker_spammer_blockmode' => '0',
	'ctracker_loginfeature' => '0',
	'ctracker_pw_reset_feature' => '0',
	'ctracker_reg_last_reg' => '1155944976',
	'ctracker_login_history' => '0',
	'ctracker_login_history_count' => '10',
	'ctracker_login_ip_check' => '0',
	'ctracker_pw_validity' => '30',
	'ctracker_pw_complex_min' => '4',
	'ctracker_pw_complex_mode' => '1',
	'ctracker_pw_control' => '0',
	'ctracker_pw_complex' => '0',
	'ctracker_last_file_scan' => '1156000091',
	'ctracker_last_checksum_scan' => '1156000082',
	'ctracker_logsize_logins' => '100',
	'ctracker_logsize_spammer' => '100',
	'ctracker_reg_ip_scan' => '0',
	'ctracker_global_message' => 'Hello world!',
	'ctracker_global_message_type' => '1',
	'ctracker_search_feature_enabled' => '1',
	'ctracker_spam_attack_boost' => '1',
	'ctracker_spam_keyword_det' => '1',
	'ctracker_footer_layout' => '6',
	'upload_dir' => 'files',
	'upload_img' => 'images/attach_post.png',
	'topic_icon' => 'images/disk_multiple.png',
	'display_order' => '0',
	'max_filesize' => '262144',
	'attachment_quota' => '52428800',
	'max_filesize_pm' => '262144',
	'max_attachments' => '3',
	'max_attachments_pm' => '1',
	'disable_attachments_mod' => '0',
	'allow_pm_attach' => '1',
	'attachment_topic_review' => '0',
	'allow_ftp_upload' => '0',
	'show_apcp' => '0',
	'attach_version' => '2.4.5',
	'default_upload_quota' => '0',
	'default_pm_quota' => '0',
	'ftp_server' => '',
	'ftp_path' => '',
	'download_path' => '',
	'ftp_user' => '',
	'ftp_pass' => '',
	'ftp_pasv_mode' => '1',
	'img_display_inlined' => '1',
	'img_max_width' => '0',
	'img_max_height' => '0',
	'img_link_width' => '0',
	'img_link_height' => '0',
	'img_create_thumbnail' => '0',
	'img_min_thumb_filesize' => '12000',
	'img_imagick' => '',
	'use_gd2' => '0',
	'wma_autoplay' => '0',
	'flash_autoplay' => '0',
	'cron_site_history_interval' => '0',
	'cron_site_history_last_run' => '0',
	'smtp_port' => '25',
	'disable_likes_posts' => '1',
	'ip_admins_only' => '0',
	'attachments_stats' => '0',
	'cron_lock_hour' => '0',
	'cron_birthdays_interval' => '0',
	'cron_birthdays_last_run' => '0',
	'robots_index_topics_no_replies' => '1',
	'limit_load' => '0',
	'limit_search_load' => '0',
	'ip_check' => '0',
	'browser_check' => '0',
	'referer_validation' => '0',
	'force_server_vars' => '0',
	'session_last_gc' => '0',
	'active_sessions' => '0',
	'form_token_lifetime' => '7200',
	'site_meta_keywords' => 'your keywords, comma, separated',
	'site_meta_keywords_switch' => '1',
	'site_meta_description' => 'Your Site Description',
	'site_meta_description_switch' => '1',
	'site_meta_author' => 'Author',
	'site_meta_author_switch' => '1',
	'site_meta_copyright' => 'Copyright',
	'site_meta_copyright_switch' => '1',
	'spam_posts_number' => '5',
	'spam_disable_url' => '1',
	'spam_hide_signature' => '1',
	'spam_post_edit_interval' => '60',
	'mobile_style_disable' => '0',
	'session_gc' => '3600',
	'session_last_visit_reset' => '0',
	'check_dnsbl' => '0',
	'check_dnsbl_posting' => '0',
	'ajax_chat_msgs_refresh' => '5',
	'ajax_chat_session_refresh' => '10',
	'ajax_chat_link_type' => '0',
	'ajax_chat_notification' => '1',
	'ajax_chat_check_online' => '0',
	'google_custom_search' => '',
	'use_jquery_tags' => '0',
	'user_allow_pm_register' => '1',
	'enable_social_connect' => '0',
	'enable_facebook_login' => '0',
	'facebook_app_id' => '',
	'facebook_app_secret' => '',
	'enable_google_login' => '0',
	'google_app_id' => '',
	'google_app_secret' => '',
	'thumbnail_s_size' => '120',
	'img_list_cols' => '4',
	'img_list_rows' => '5',

	// IP Version
	'ip_version' => ICYPHOENIX_VERSION,
	'cms_version' => '2.0.0',

	// Cash
	'cash_disable' => 0,
	'cash_display_after_posts' => 1,
	'cash_post_message' => 'You earned %s for that post',
	'cash_disable_spam_num' => 10,
	'cash_disable_spam_time' => 24,
	'cash_disable_spam_message' => 'You have exceeded the alloted amount of posts and will not earn anything for your post',
	'cash_installed' => 'yes',
	'cash_version' => '2.2.3',
	'cash_adminbig' => '0',
	'cash_adminnavbar' => '1',
	'points_name' => 'Points',

	// DB Maintenance specific entries
	'dbmtnc_rebuild_end' => '0',
	'dbmtnc_rebuild_pos' => '-1',
	'dbmtnc_rebuildcfg_maxmemory' => '500',
	'dbmtnc_rebuildcfg_minposts' => '3',
	'dbmtnc_rebuildcfg_php3only' => '0',
	'dbmtnc_rebuildcfg_php3pps' => '1',
	'dbmtnc_rebuildcfg_php4pps' => '8',
	'dbmtnc_rebuildcfg_timelimit' => '240',
	'dbmtnc_rebuildcfg_timeoverwrite' => '0',
	'dbmtnc_disallow_postcounter' => '0',
	'dbmtnc_disallow_rebuild' => '0'
);

// append data added in later versions
if (isset($config) && isset($config['version']))
{
	$phpbb_version = explode('.', substr($config['version'], 1));
}
else
{
	// Fallback for ERC
	$phpbb_version = array(0, 19);
}
if ($phpbb_version[0] == 0 && $phpbb_version[1] >= 5)
{
	$tables[] = 'confirm';
}
if ($phpbb_version[0] == 0 && $phpbb_version[1] >= 18)
{
	$tables[] = 'sessions_keys';
	$default_config['allow_autologin'] = '1';
	$default_config['max_autologin_time'] = '0';
}
if (($phpbb_version[0] == 0) && ($phpbb_version[1] >= 19))
{
	$default_config['max_login_attempts'] = '5';
	$default_config['login_reset_time'] = '30';
}
sort($tables);


// This is the equivalent function for message_die. Since we do not use the template system when doing database work, message_die() will not work.
function throw_error($msg_text = '', $err_line = '', $err_file = '', $sql = '')
{
	global $db, $template, $theme, $lang;
	global $list_open;

	$sql_store = $sql;

	//
	// Get SQL error if we are debugging. Do this as soon as possible to prevent
	// subsequent queries from overwriting the status of sql_error()
	//
	if (DEBUG)
	{
		$sql_error = $db->sql_error();

		$debug_text = '';

		if ($sql_error['message'] != '')
		{
			$debug_text .= '<br /><br />SQL Error : ' . $sql_error['code'] . ' ' . $sql_error['message'];
		}

		if ($sql_store != '')
		{
			$debug_text .= "<br /><br />$sql_store";
		}

		if ($err_line != '' && $err_file != '')
		{
			$debug_text .= '<br /><br />Line : ' . $err_line . '<br />File : ' . $err_file;
		}
	}
	else
	{
		$debug_text = '';
	}

	// Close the list if one is still open
	if ($list_open)
	{
		echo('</ul></span>' . "\n");
	}

	if ($msg_text == '')
	{
		$msg_text = $lang['An_error_occured'];
	}

	echo('<p class="gen"><b><span class="text_red">' . $lang['Error'] . ':</span></b> ' . $msg_text . $debug_text . '</p>' . "\n");

	// Include Tail and exit
	echo('<p class="gen"><a href="' . append_sid('admin_db_maintenance.' . PHP_EXT) . '">' . $lang['Back_to_DB_Maintenance'] . '</a></p>' . "\n");
	include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);
	exit;
}

// Locks or unlocks the database
function lock_db($unlock = false, $delay = true, $ignore_default = false)
{
	global $config, $db, $lang;
	static $db_was_locked = false;

	if ($unlock)
	{
		echo('<p class="gen"><b>' . $lang['Unlock_db'] . '</b></p>' . "\n");
		if ($db_was_locked && !$ignore_default)
		{
			// The database was locked and we were not told to ignore the default. So we exit
			echo('<p class="gen">' . $lang['Ignore_unlock_command'] . '</p>' . "\n");
			return;
		}
	}
	else
	{
		echo('<p class="gen"><b>' . $lang['Lock_db'] . '</b></p>' . "\n");
		// Check current lock state
		if ($config['board_disable'])
		{
			// DB is already locked. Write this to var and exit
			$db_was_locked = true;
			echo('<p class="gen">' . $lang['Already_locked'] . '</p>' . "\n");
			return $db_was_locked;
		}
		else
		{
			$db_was_locked = false;
		}
	}

	// OK, now we can update the settings
	set_config('board_disable', ($unlock) ? '0' : '1', true);
	$db->clear_cache();
	$db->clear_cache('', TOPICS_CACHE_FOLDER);

	// Delay 3 seconds to allow database to finish operation
	if (!$unlock && $delay)
	{
		global $timer;
		echo('<p class="gen">' . $lang['Delay_info'] . '</p>' . "\n");
		sleep(3);
		$timer += 3; // remove delaying time from timer
	}
	else
	{
		echo('<p class="gen">' . $lang['Done'] . '</p>' . "\n");
	}
	return $db_was_locked;
}

// Checks several conditions for the menu
function check_condition($check)
{
	global $db, $config;

	switch ($check)
	{
		case 0: // No check
			return true;
			break;
		case 1: // MySQL >= 3.23.17
			return check_mysql_version();
			break;
		case 2: // Session Table not HEAP
			if (!check_mysql_version())
			{
				return false;
			}
			$sql = "SHOW TABLE STATUS LIKE '" . SESSIONS_TABLE . "'";
			$db->sql_return_on_error(true);
			$result = $db->sql_query($sql);
			$db->sql_return_on_error(false);
			if (!$result)
			{
				return false; // Status unknown
			}
			$row = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			if(!$row)
			{
				return false; // Status unknown
			}
			if ((isset($row['Type']) && $row['Type'] == 'HEAP') || (isset($row['Engine']) && ($row['Engine'] == 'HEAP' || $row['Engine'] == 'MEMORY')))
			{
				return false;
			}
			else
			{
				return true;
			}
			break;
		case 3: // DB locked
			if ($config['board_disable'])
			{
				// DB is locked
				return true;
			}
			else
			{
				return false;
			}
			break;
		case 4: // Search index in recreation
			if($config['dbmtnc_rebuild_pos'] <> -1)
			{
				// Rebuilding was interrupted - check for end position
				if ($config['dbmtnc_rebuild_end'] >= $config['dbmtnc_rebuild_pos'])
				{
					return true;
				}
				else
				{
					return false;
				}
			}
			else
			{
				// Rebuilding was not interrupted
				return false;
			}
			break;
		case 5: // Configuration disabled
			return (CONFIG_LEVEL != 0) ? true : false;
			break;
		case 6: // User post counter disabled
			return ($config['dbmtnc_disallow_postcounter'] != 1) ? true : false;
			break;
		case 7: // Rebuilding disabled
			return ($config['dbmtnc_disallow_rebuild'] != 1) ? true : false;
			break;
		case 8: // Separator for rebuilding
			return (check_condition(4) || check_condition(7)) ? true : false;
			break;
		default:
			return false;
	}
}

// Checks whether MySQL supports HEAP-Tables, ANSI compatible INNER JOINs and other commands
function check_mysql_version()
{
	global $db;

	$sql = 'SELECT VERSION() AS mysql_version';
	$db->sql_return_on_error(true);
	$result = $db->sql_query($sql);
	$db->sql_return_on_error(false);
	if (!$result)
	{
		throw_error("Couldn't obtain MySQL Version", __LINE__, __FILE__, $sql);
	}
	$row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);

	$version = $row['mysql_version'];

	if (preg_match("/^3\.23\.([0-9]$|[0-9]-|1[0-3]$|1[0-6]-)/", $version)) // Version from 3.23.0 to 3.23.16
	{
		return false;
	}
	elseif (preg_match("/^(3\.23)|(4\.)|(5\.)/", $version))
	{
		return true;
	}
	else // Versions before 3.23.0
	{
		return false;
	}
}

// Gets the current time in microseconds
function getmicrotime()
{
	list($usec, $sec) = explode(' ', microtime());
	return ((float) $usec + (float) $sec);
}

// Gets table statistics
function get_table_statistic()
{
	global $db, $table_prefix;
	global $tables;

	$stat['all']['count'] = 0;
	$stat['all']['records'] = 0;
	$stat['all']['size'] = 0;
	$stat['advanced']['count'] = 0;
	$stat['advanced']['records'] = 0;
	$stat['advanced']['size'] = 0;
	$stat['core']['count'] = 0;
	$stat['core']['records'] = 0;
	$stat['core']['size'] = 0;

	$sql = 'SHOW TABLE STATUS';
	$db->sql_return_on_error(true);
	$result = $db->sql_query($sql);
	$db->sql_return_on_error(false);
	if (!$result)
	{
		throw_error("Couldn't obtain table data", __LINE__, __FILE__, $sql);
	}
	while($row = $db->sql_fetchrow($result))
	{
		$stat['all']['count']++;
		$stat['all']['records'] += intval($row['Rows']);
		$stat['all']['size'] += intval($row['Data_length']) + intval($row['Index_length']);
		if ($table_prefix == substr($row['Name'], 0, strlen($table_prefix)))
		{
			$stat['advanced']['count']++;
			$stat['advanced']['records'] += intval($row['Rows']);
			$stat['advanced']['size'] += intval($row['Data_length']) + intval($row['Index_length']);
		}
		for ($i = 0; $i < sizeof($tables); $i++)
		{
			if ($table_prefix . $tables[$i] == $row['Name'])
			{
				$stat['core']['count']++;
				$stat['core']['records'] += intval($row['Rows']);
				$stat['core']['size'] += intval($row['Data_length']) + intval($row['Index_length']);
			}
		}
	}
	$db->sql_freeresult($result);
	return $stat;
}

// Converts Bytes to a apropriate Value
function convert_bytes($bytes)
{
	if($bytes >= 1048576)
	{
		return sprintf("%.2f MB", ($bytes / 1048576));
	}
	else if($bytes >= 1024)
	{
		return sprintf("%.2f KB", ($bytes / 1024));
	}
	else
	{
		return sprintf("%.2f Bytes", $bytes);
	}
}

// Creates a new category
function create_cat()
{
	global $db, $lang;

	static $cat_created = false;
	static $cat_id = 0;

	if (!$cat_created)
	{
		// Identify the highest value of cat_order
		$sql = 'SELECT MAX(forum_id) AS forum_id, MAX(forum_order) AS forum_order
			FROM ' . FORUMS_TABLE;
		$db->sql_return_on_error(true);
		$result = $db->sql_query($sql);
		$db->sql_return_on_error(false);
		if (!$result)
		{
			throw_error("Couldn't get categories data!", __LINE__, __FILE__, $sql);
		}
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);
		if(!$row)
		{
			throw_error("Couldn't get categories data!", __LINE__, __FILE__, $sql);
		}
		$next_cat_id = $row['forum_id'] + 1;
		$next_cat_order = $row['forum_order'] + 10;

		$sql = "INSERT INTO " . FORUMS_TABLE . " (forum_id, parent_id, forum_type, main_type, forum_name, forum_desc, forum_order)
			VALUES (" . $next_cat_id . ", 0, " . FORUM_CAT . ", '" . POST_CAT_URL . "', '" . $db->sql_escape($lang['New_cat_name']) . "', '" . $db->sql_escape($lang['New_cat_name']) . "', " . $next_cat_order . ")";
		$db->sql_return_on_error(true);
		$result = $db->sql_query($sql);
		$db->sql_return_on_error(false);
		if (!$result)
		{
			throw_error("Couldn't update categories data!", __LINE__, __FILE__, $sql);
		}
		$cat_id = $db->sql_nextid();
		$cat_created = true;
	}
	return $cat_id;
}

// Creates a new forum
function create_forum()
{
	global $db, $lang;

	static $forum_created = false;
	static $forum_id = 0;
	$cat_id = create_cat();

	if (!$forum_created)
	{
		// Identify the highest value of forum_id
		$sql = 'SELECT MAX(forum_id) AS forum_id
						FROM ' . FORUMS_TABLE;
		$db->sql_return_on_error(true);
		$result = $db->sql_query($sql);
		$db->sql_return_on_error(false);
		if (!$result)
		{
			throw_error("Couldn't get forum data!", __LINE__, __FILE__, $sql);
		}
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);
		if(!$row)
		{
			throw_error("Couldn't get forum data!", __LINE__, __FILE__, $sql);
		}
		$next_forum_id = $row['forum_id'] + 1;
		// Identify the highest value of forum_order
		$sql = 'SELECT MAX(forum_order) AS forum_order
				FROM ' . FORUMS_TABLE;
		$db->sql_return_on_error(true);
		$result = $db->sql_query($sql);
		$db->sql_return_on_error(false);
		if (!$result)
		{
			throw_error("Couldn't get forum data!", __LINE__, __FILE__, $sql);
		}
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);
		if(!$row)
		{
			throw_error("Couldn't get forum data!", __LINE__, __FILE__, $sql);
		}
		$next_forum_order = $row['forum_order'] + 10;

		$forum_permission = AUTH_ADMIN;
		$sql = 'INSERT INTO ' . FORUMS_TABLE . " (forum_id, forum_type, parent_id, forum_name, forum_desc, forum_status, forum_order, forum_posts, forum_topics, forum_last_post_id, prune_next, prune_enable, auth_view, auth_read, auth_post, auth_reply, auth_edit, auth_delete, auth_sticky, auth_announce, auth_vote, auth_pollcreate, auth_attachments)
			VALUES ($next_forum_id, " . FORUM_POST . ", $cat_id, '" . $db->sql_escape($lang['New_forum_name']) . "', '', " . FORUM_LOCKED . ", $next_forum_order, 0, 0, 0, NULL, 0, $forum_permission, $forum_permission, $forum_permission, $forum_permission, $forum_permission, $forum_permission, $forum_permission, $forum_permission, $forum_permission, $forum_permission, 0)";
		$db->sql_return_on_error(true);
		$result = $db->sql_query($sql);
		$db->sql_return_on_error(false);
		if (!$result)
		{
			throw_error("Couldn't update forums data!", __LINE__, __FILE__, $sql);
		}
		$forum_id = $next_forum_id;
		$forum_created = true;
	}
	return $forum_id;
}

// Create a new topic
function create_topic()
{
	global $db, $lang;

	static $topic_created = false;
	static $topic_id = 0;
	$forum_id = create_forum();

	if (!$topic_created)
	{
		$sql = 'INSERT INTO ' . TOPICS_TABLE . " (forum_id, topic_title, topic_poster, topic_time, topic_views, topic_replies, topic_status, topic_type, topic_first_post_id, topic_last_post_id, topic_moved_id)
			VALUES ($forum_id, '" . $db->sql_escape($lang['New_topic_name']) . "', -1, " . time() . ", 0, 0, " . TOPIC_UNLOCKED . ", " . POST_NORMAL . ", 0, 0, 0)";
		$db->sql_return_on_error(true);
		$result = $db->sql_query($sql);
		$db->sql_return_on_error(false);
		if (!$result)
		{
			throw_error("Couldn't update topics data!", __LINE__, __FILE__, $sql);
		}
		$topic_id = $db->sql_nextid();
		$topic_created = true;
	}
	return $topic_id;
}

// Gets the poster of a topic
function get_poster($topic_id)
{
	global $db;

	$sql = 'SELECT MIN(post_id) AS first_post
		FROM ' . POSTS_TABLE . "
		WHERE topic_id = $topic_id";
	$db->sql_return_on_error(true);
	$result = $db->sql_query($sql);
	$db->sql_return_on_error(false);
	if (!$result)
	{
		throw_error("Couldn't get post data!", __LINE__, __FILE__, $sql);
	}
	$row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	if(!$row || $row['first_post'] == '')
	{
		return DELETED;
	}
	$sql = 'SELECT poster_id
		FROM ' . POSTS_TABLE . '
		WHERE post_id = ' . $row['first_post'];
	$db->sql_return_on_error(true);
	$result = $db->sql_query($sql);
	$db->sql_return_on_error(false);
	if (!$result)
	{
		throw_error("Couldn't get post data!", __LINE__, __FILE__, $sql);
	}
	$row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	if(!$row)
	{
		throw_error("Couldn't get post data!", __LINE__, __FILE__, $sql);
	}
	return $row['poster_id'];
}

// Error handler when trying to reset timelimit
function catch_error($errno, $errstr)
{
	global $execution_time;
	$execution_time = ini_get('max_execution_time'); // Will only get execute when running on PHP 4+
}

// Gets the ID of a word or creates it
function get_word_id($word)
{
	global $db, $config, $template, $theme, $lang;
	global $stopword_array, $synonym_array;

	// Check whether word is in stopword array
	if (!empty($word) && !empty($stopword_array) && in_array($word, $stopword_array))
	{
		return NULL;
	}
	if (!empty($word) && !empty($synonym_array[1]) && in_array($word, $synonym_array[1]))
	{
		$key = array_search($word, $synonym_array[1]);
		$word = $synonym_array[0][$key];
	}

	$sql = "SELECT word_id, word_common
		FROM " . SEARCH_WORD_TABLE . "
		WHERE word_text = '" . $db->sql_escape($word) . "'";
	$db->sql_return_on_error(true);
	$result = $db->sql_query($sql);
	$db->sql_return_on_error(false);
	if (!$result)
	{
		include(IP_ROOT_PATH . ADM . '/page_header_admin.' . PHP_EXT);
		throw_error("Couldn't get search word data!", __LINE__, __FILE__, $sql);
	}
	if ($row = $db->sql_fetchrow($result)) // Word was found
	{
		if ($row['word_common']) // Common word
		{
			return NULL;
		}
		else // Not a common word
		{
			return $row['word_id'];
		}
	}
	else // Word was not found
	{
		$sql = "INSERT INTO " . SEARCH_WORD_TABLE . " (word_text, word_common) VALUES ('" . $db->sql_escape($word) . "', 0)";
		$db->sql_return_on_error(true);
		$result = $db->sql_query($sql);
		$db->sql_return_on_error(false);
		if (!$result)
		{
			include(IP_ROOT_PATH . ADM . '/page_header_admin.' . PHP_EXT);
			throw_error("Couldn't insert search word data!", __LINE__, __FILE__, $sql);
		}
		return $db->sql_nextid();
	}
	$db->sql_freeresult($result);
}

// Resets the auto increment for a table
function set_autoincrement($table, $column, $length, $unsigned = true)
{
	global $db, $lang;

	$sql = "ALTER IGNORE TABLE $table MODIFY $column mediumint($length) " . (($unsigned) ? 'unsigned ' : '') . "NOT NULL auto_increment";
	if (check_mysql_version())
	{
		$sql2 = "SHOW COLUMNS FROM $table LIKE '$column'";
		$db->sql_return_on_error(true);
		$result = $db->sql_query($sql2);
		$db->sql_return_on_error(false);
		if (!$result)
		{
			throw_error("Couldn't get table status!", __LINE__, __FILE__, $sql2);
		}
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);
		if(!$row)
		{
			throw_error("Couldn't get table status!", __LINE__, __FILE__, $sql2);
		}
		if (strpos($row['Extra'], 'auto_increment') !== false)
		{
			echo("<li>$table: " . $lang['Ai_message_no_update'] . '</li>' . "\n");
		}
		else
		{
			echo("<li>$table: <b>" . $lang['Ai_message_update_table'] . '</b></li>' . "\n");
			$db->sql_return_on_error(true);
			$result = $db->sql_query($sql);
			$db->sql_return_on_error(false);
			if (!$result)
			{
				throw_error("Couldn't alter table!", __LINE__, __FILE__, $sql);
			}
		}
	}
	else // old Version of MySQL - do the update in any case
	{
		echo("<li>$table: <b>" . $lang['Ai_message_update_table_old_mysql'] . '</b></li>' . "\n");
		$db->sql_return_on_error(true);
		$result = $db->sql_query($sql);
		$db->sql_return_on_error(false);
		if (!$result)
		{
			throw_error("Couldn't alter table!", __LINE__, __FILE__, $sql);
		}
	}
}

// Functions for Emergency Recovery Console
function erc_throw_error($msg_text = '', $err_line = '', $err_file = '', $sql = '')
{
	global $db, $lang;

	$sql_store = $sql;

	//
	// Get SQL error if we are debugging. Do this as soon as possible to prevent
	// subsequent queries from overwriting the status of sql_error()
	//
	if (DEBUG)
	{
		$sql_error = $db->sql_error();

		$debug_text = '';

		if ($sql_error['message'] != '')
		{
			$debug_text .= '<br /><br />SQL Error : ' . $sql_error['code'] . ' ' . $sql_error['message'];
		}

		if ($sql_store != '')
		{
			$debug_text .= '<br /><br />' . $sql_store;
		}

		if ($err_line != '' && $err_file != '')
		{
			$debug_text .= '<br /><br />Line : ' . $err_line . '<br />File : ' . $err_file;
		}
	}
	else
	{
		$debug_text = '';
	}

	if ($msg_text == '')
	{
		$msg_text = $lang['An_error_occured'];
	}

	echo('<p class="gen"><b>' . $lang['Error'] . ':</b> ' . $msg_text . $debug_text . '</p>' . "\n");

	exit;
}

function check_authorization($die = true)
{
	global $db, $cache, $lang, $dbuser, $dbpasswd, $option;

	$auth_method = request_post_var('auth_method', '');
	$board_user = request_post_var('board_user', '', true);
	$board_user = htmlspecialchars_decode($board_user, ENT_COMPAT);
	$board_password = request_post_var('board_password', '', true);
	$board_password = htmlspecialchars_decode($board_password, ENT_COMPAT);
	$db_user = request_post_var('db_user', '', true);
	$db_user = htmlspecialchars_decode($db_user, ENT_COMPAT);
	$db_password = request_post_var('db_password', '', true);
	$db_password = htmlspecialchars_decode($db_password, ENT_COMPAT);

	// Change authentication mode if selected option does not allow database authentication
	if (($option == 'rld') || ($option == 'rtd'))
	{
		$auth_method = 'board';
	}

	switch ($auth_method)
	{
		case 'board':
			include_once(IP_ROOT_PATH . 'includes/auth_db.' . PHP_EXT);
			$login_result = login_db($board_user, $board_password, false, true);
			$allow_access = false;
			if (($login_result['status'] === LOGIN_SUCCESS) && ($login_result['user_row']['user_level'] == ADMIN))
			{
				$allow_access = true;
			}
			break;
		case 'db':
			if (($db_user == $dbuser) && ($db_password == $dbpasswd))
			{
				$allow_access = true;
			}
			else
			{
				$allow_access = false;
			}
			break;
		default:
			$allow_access = false;
	}
	if (!$allow_access && $die)
	{
?>
	<p><span style="color: red;"><?php echo $lang['Auth_failed']; ?></span></p>
</body>
</html>
<?php
		exit;
	}
	return $allow_access;
}

function get_config_data($option)
{
	global $db;

	$sql = "SELECT config_value
		FROM " . CONFIG_TABLE . "
		WHERE config_name = '$option'";
	$db->sql_return_on_error(true);
	$result = $db->sql_query($sql);
	$db->sql_return_on_error(false);
	if (!$result)
	{
		erc_throw_error("Couldn't get config data!", __LINE__, __FILE__, $sql);
	}
	$row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	if (!$row)
	{
		erc_throw_error("Config data does not exist!", __LINE__, __FILE__, $sql);
	}

	return $row['config_value'];
}

function success_message($text)
{
	global $lang, $lg;

?>
	<p><?php echo $text; ?></p>
	<p style="text-align: center;"><a href="<?php echo $_SERVER['SCRIPT_NAME'] . '?lg=' . $lg; ?>"><?php echo $lang['Return_ERC']; ?></a></p>
<?php
}

?>