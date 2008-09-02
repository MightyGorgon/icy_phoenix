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

if (!defined('IN_PHPBB'))
{
	die('Hacking attempt');
}

// List of tables used
$tables = array(
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
'attachments_config',
'attachments_desc',
'attachments_stats',
'attach_quota',
'auth_access',
'autolinks',
'banlist',
'bookmarks',
'captcha_config',
'cash',
'cash_events',
'cash_exchange',
'cash_groups',
'cash_log',
'categories',
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
'ctracker_config',
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
'force_read',
'force_read_users',
'forums',
'forums_watch',
'forum_prune',
'google_bot_detector',
'groups',
'hacks_list',
/*
'helpdesk_emails',
'helpdesk_importance',
'helpdesk_msgs',
'helpdesk_reply',
*/
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
'news',
'notes',
'optimize_db',
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
'posts',
'posts_text',
'privmsgs',
'privmsgs_archive',
'privmsgs_text',
'profile_fields',
'profile_view',
'quota_limits',
'ranks',
'rate_results',
'referrers',
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
'thanks',
'themes',
'themes_name',
'title_infos',
'topics',
'topics_watch',
'topic_view',
'upi2db_always_read',
'upi2db_last_posts',
'upi2db_unread_posts',
'users',
'user_group',
'vote_desc',
'vote_results',
'vote_voters',
'words',
'xs_news',
'xs_news_cfg',
'xs_news_xml',
'zebra'
);
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
	'allow_html_tags' => 'b,i,u,pre,table,tr,td',
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
	'max_user_bancard' => '10',
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
	'board_disable_message' => 'forum disabled',
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
	'sudoku_version' => '1.0.6',
	'yahoo_search_savepath' => 'cache',
	'yahoo_search_additional_urls' => 'http://www.icyphoenix.com',
	'yahoo_search_compress' => '1',
	'yahoo_search_compression_level' => '9',
	'max_link_bookmarks' => '0',
	'visit_counter' => '0',
	'word_graph_max_words' => '100',
	'word_graph_word_counts' => '1',
	'search_min_chars' => '3',
	'disable_registration_ip_check' => '1',
	'extra_max' => '0',
	'extra_display' => '0',
	'upi2db_max_permanent_topics' => '20',
	'upi2db_del_mark' => '60',
	'upi2db_del_perm' => '120',
	'upi2db_max_mark_posts' => '10',
	'upi2db_unread_color' => 'AAFFCC',
	'upi2db_edit_color' => 'FFCCAA',
	'upi2db_mark_color' => 'FFFFAA',
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
	'xs_def_template' => 'mg_themes',
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
	'shoutbox_refreshtime' => '5000',
	'shout_allow_guest' => '2',
	'upi2db_max_new_posts_admin' => '0',
	'upi2db_max_new_posts_mod' => '2000',
	'show_calendar_box_index' => '0',
	'url_rw' => '0',
	'xmas_fx' => '0',
	'switch_header_table' => '0',
	'header_table_text' => 'Text',
	'switch_footer_table' => '0',
	'footer_table_text' => 'Text',
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
	'index_links' => '0',
	'index_birthday' => '0',
	'db_cron' => '0',
	'site_history' => '0',
	'smilies_topic_title' => '0',
	'html_email' => '1',
	'config_cache' => '1',
	'admin_protect' => '0',
	'disable_ftr' => '1',
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
	'auth_view_portal' => '0',
	'auth_view_forum' => '0',
	'auth_view_viewf' => '0',
	'auth_view_viewt' => '0',
	'auth_view_faq' => '0',
	'auth_view_memberlist' => '0',
	'auth_view_group_cp' => '0',
	'auth_view_profile' => '0',
	'auth_view_search' => '0',
	'auth_view_album' => '1',
	'auth_view_links' => '0',
	'auth_view_calendar' => '0',
	'auth_view_attachments' => '0',
	'auth_view_download' => '0',
	'auth_view_kb' => '0',
	'auth_view_ranks' => '0',
	'auth_view_statistics' => '0',
	'auth_view_recent' => '0',
	'auth_view_referrers' => '0',
	'auth_view_rules' => '0',
	'auth_view_site_hist' => '0',
	'auth_view_shoutbox' => '0',
	'auth_view_viewonline' => '0',
	'auth_view_ajax_chat' => '1',
	'auth_view_ajax_chat_archive' => '5',
	'switch_header_banner' => '0',
	'switch_viewtopic_banner' => '0',
	'header_banner_text' => 'Text',
	'viewtopic_banner_text' => 'Text',
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
	'switch_header_dropdown' => '0',
	'switch_poster_info_topic' => '0',
	'switch_bbcb_active_content' => '1',
	'thumbnail_lightbox' => '1',
	'enable_quick_quote' => '1',
	'enable_xs_version_check' => '0',
	'allow_all_bbcode' => '1',
	'wide_blocks_portal' => '0',
	'wide_blocks_forum' => '0',
	'wide_blocks_viewf' => '1',
	'wide_blocks_viewt' => '0',
	'wide_blocks_faq' => '0',
	'wide_blocks_memberlist' => '0',
	'wide_blocks_group_cp' => '0',
	'wide_blocks_profile' => '0',
	'wide_blocks_search' => '0',
	'wide_blocks_album' => '1',
	'wide_blocks_links' => '1',
	'wide_blocks_calendar' => '0',
	'wide_blocks_attachments' => '0',
	'wide_blocks_download' => '1',
	'wide_blocks_kb' => '0',
	'wide_blocks_ranks' => '0',
	'wide_blocks_statistics' => '0',
	'wide_blocks_recent' => '0',
	'wide_blocks_referrers' => '0',
	'wide_blocks_rules' => '0',
	'wide_blocks_site_hist' => '0',
	'wide_blocks_shoutbox' => '0',
	'wide_blocks_viewonline' => '0',
	'auth_view_custom_pages' => '0',
	'wide_blocks_custom_pages' => '0',
	'wide_blocks_ajax_chat' => '0',
	'wide_blocks_ajax_chat_archive' => '0',
	'enable_digests' => '0',
	'digests_php_cron' => '0',
	'digests_last_send_time' => '1172516448',
	'xmas_gfx' => '0',
	'google_bot_detector' => '0',
	'logs_path' => 'logs',
	'url_rw_guests' => '0',
	'lofi_bots' => '0',
	'ajax_checks_register' => '1',
	'inactive_users_memberlists' => '1',
	'auth_view_pic_upload' => '1',
	'enable_postimage_org' => '0',
	'enable_new_messages_number' => '0',
	'disable_thanks_topics' => '1',
	'ajax_features' => '0',
	'show_rss_forum_icon' => '1',
	'disable_acronyms' => '1',
	'disable_autolinks' => '1',
	'disable_censor' => '1',
	'global_disable_acronyms' => '1',
	'global_disable_autolinks' => '1',
	'global_disable_censor' => '1',
	'disable_topic_view' => '1',
	'disable_referrers' => '1',
	'switch_top_html_block' => '0',
	'top_html_block_text' => '',
	'switch_bottom_html_block' => '0',
	'bottom_html_block_text' => '',
	'aprvmArchive' => '0',
	'aprvmVersion' => '1.6.0',
	'aprvmView' => '0',
	'aprvmRows' => '25',
	'aprvmIP' => '1',
	'page_title_simple' => '0',
	'digests_php_cron_lock' => '0',
	'mg_log_actions' => '0',
	'active_users_color' => '#224455',
	'active_users_legend' => '1',
	'bots_color' => '#667788',
	'bots_legend' => '1',
	'show_social_bookmarks' => '0',
	'show_forums_online_users' => '0',
	'cms_dock' => '0',
	'allow_drafts' => '1',
	'allow_only_main_admin_id' => '0',
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
	'cms_style' => '0',
	'show_alpha_bar' => '0',
	// IP Version
	'ip_version' => '1.2.7.34',
	// Cash
	'cash_disable' => 0,
	'cash_display_after_posts' => 1,
	'cash_post_message' => 'You earned %s for that post',
	'cash_disable_spam_num' =>  10,
	'cash_disable_spam_time' =>  24,
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
if ( isset($board_config) && isset($board_config['version']) )
{
	$phpbb_version = explode('.', substr($board_config['version'], 1));
}
else
{
	// Fallback for ERC
	$phpbb_version = array(0, 19);
}
if ( $phpbb_version[0] == 0 && $phpbb_version[1] >= 5 )
{
	$tables[] = 'confirm';
}
if ( $phpbb_version[0] == 0 && $phpbb_version[1] >= 18 )
{
	$tables[] = 'sessions_keys';
	$default_config['allow_autologin'] = '1';
	$default_config['max_autologin_time'] = '0';
}
if ( ($phpbb_version[0] == 0) && ($phpbb_version[1] >= 19) )
{
	$default_config['max_login_attempts'] = '5';
	$default_config['login_reset_time'] = '30';
}
sort($tables);


//
// Function for updating the config_table
//
function update_config($name, $value)
{
	global $db, $board_config;

	$sql = 'UPDATE ' . CONFIG_TABLE . " SET config_value = '$value' WHERE config_name = '$name'";
	$result = $db->sql_query($sql);
	if( !$result )
	{
		throw_error("Couldn't update forum configuration!", __LINE__, __FILE__, $sql);
	}
	$board_config[$name] = $value;
}

//
// This is the equivalent function for message_die. Since we do not use the template system when doing database work, message_die() will not work.
//
function throw_error($msg_text = '', $err_line = '', $err_file = '', $sql = '')
{
	global $db, $template, $lang, $phpEx, $phpbb_root_path, $theme;
	global $list_open;

	$sql_store = $sql;

	//
	// Get SQL error if we are debugging. Do this as soon as possible to prevent
	// subsequent queries from overwriting the status of sql_error()
	//
	if ( DEBUG )
	{
		$sql_error = $db->sql_error();

		$debug_text = '';

		if ( $sql_error['message'] != '' )
		{
			$debug_text .= '<br /><br />SQL Error : ' . $sql_error['code'] . ' ' . $sql_error['message'];
		}

		if ( $sql_store != '' )
		{
			$debug_text .= "<br /><br />$sql_store";
		}

		if ( $err_line != '' && $err_file != '' )
		{
			$debug_text .= '<br /><br />Line : ' . $err_line . '<br />File : ' . $err_file;
		}
	}
	else
	{
		$debug_text = '';
	}

	// Close the list if one is still open
	if ( $list_open )
	{
		echo("</ul></span>\n");
	}

	if ( $msg_text == '' )
	{
		$msg_text = $lang['An_error_occured'];
	}

	echo('<p class="gen"><b><span class="text_red">' . $lang['Error'] . ":</span></b> $msg_text$debug_text</p>\n");

	// Include Tail and exit
	echo("<p class=\"gen\"><a href=\"" . append_sid("admin_db_maintenance.$phpEx") . "\">" . $lang['Back_to_DB_Maintenance'] . "</a></p>\n");
	include('./page_footer_admin.' . $phpEx);
	exit;
}

// Locks or unlocks the database
function lock_db($unlock = false, $delay = true, $ignore_default = false)
{
	global $board_config, $db, $lang;
	static $db_was_locked = false;

	if ($unlock)
	{
		echo('<p class="gen"><b>' . $lang['Unlock_db'] . "</b></p>\n");
		if ( $db_was_locked && !$ignore_default )
		{
			// The database was locked and we were not told to ignore the default. So we exit
			echo('<p class="gen">' . $lang['Ignore_unlock_command'] . "</p>\n");
			return;
		}
	}
	else
	{
		echo('<p class="gen"><b>' . $lang['Lock_db'] . "</b></p>\n");
		// Check current lock state
		if ( $board_config['board_disable'] == 1 )
		{
			// DB is already locked. Write this to var and exit
			$db_was_locked = true;
			echo('<p class="gen">' . $lang['Already_locked'] . "</p>\n");
			return $db_was_locked;
		}
		else
		{
			$db_was_locked = false;
		}
	}

	// OK, now we can update the settings
	update_config('board_disable', ($unlock) ? '0' : '1');
	$db->clear_cache();

	// Delay 3 seconds to allow database to finish operation
	if (!$unlock && $delay)
	{
		global $timer;
		echo('<p class="gen">' . $lang['Delay_info'] . "</p>\n");
		sleep(3);
		$timer += 3; // remove delaying time from timer
	}
	else
	{
		echo('<p class="gen">' . $lang['Done'] . "</p>\n");
	}
	return $db_was_locked;
}

// Checks several conditions for the menu
function check_condition($check)
{
	global $db, $board_config;

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
			$result = $db->sql_query($sql);
			if( !$result )
			{
				return false; // Status unknown
			}
			$row = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			if( !$row )
			{
				return false; // Status unknown
			}
			if ( (isset($row['Type']) && $row['Type'] == 'HEAP') || (isset($row['Engine']) && ($row['Engine'] == 'HEAP' || $row['Engine'] == 'MEMORY')) )
			{
				return false;
			}
			else
			{
				return true;
			}
			break;
		case 3: // DB locked
			if ( $board_config['board_disable'] == 1 )
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
			if( $board_config['dbmtnc_rebuild_pos'] <> -1 )
			{
				// Rebuilding was interrupted - check for end position
				if ( $board_config['dbmtnc_rebuild_end'] >= $board_config['dbmtnc_rebuild_pos'] )
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
			return ($board_config['dbmtnc_disallow_postcounter'] != 1) ? true : false;
			break;
		case 7: // Rebuilding disabled
			return ($board_config['dbmtnc_disallow_rebuild'] != 1) ? true : false;
			break;
		case 8: // Seperator for rebuilding
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
	$result = $db->sql_query($sql);
	if( !$result )
	{
		throw_error("Couldn't obtain MySQL Version", __LINE__, __FILE__, $sql);
	}
	$row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);

	$version = $row['mysql_version'];

	if ( preg_match("/^3\.23\.([0-9]$|[0-9]-|1[0-3]$|1[0-6]-)/", $version) ) // Version from 3.23.0 to 3.23.16
	{
		return false;
	}
	elseif ( preg_match("/^(3\.23)|(4\.)|(5\.)/", $version) )
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
	list($usec, $sec) = explode(" ", microtime());
	return ((float)$usec + (float)$sec);
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
	$result = $db->sql_query($sql);
	if( !$result )
	{
		throw_error("Couldn't obtain table data", __LINE__, __FILE__, $sql);
	}
	while( $row = $db->sql_fetchrow($result) )
	{
		$stat['all']['count']++;
		$stat['all']['records'] += intval($row['Rows']);
		$stat['all']['size'] += intval($row['Data_length']) + intval($row['Index_length']);
		if ( $table_prefix == substr($row['Name'], 0, strlen($table_prefix)) )
		{
			$stat['advanced']['count']++;
			$stat['advanced']['records'] += intval($row['Rows']);
			$stat['advanced']['size'] += intval($row['Data_length']) + intval($row['Index_length']);
		}
		for ($i = 0; $i < count($tables); $i++)
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
	if( $bytes >= 1048576 )
	{
		return sprintf("%.2f MB", ( $bytes / 1048576 ));
	}
	else if( $bytes >= 1024 )
	{
		return sprintf("%.2f KB", ( $bytes / 1024 ));
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
		// Höchten Wert von cat_order ermitteln
		$sql = 'SELECT Max(cat_order) AS cat_order
			FROM ' . CATEGORIES_TABLE;
		$result = $db->sql_query($sql);
		if( !$result )
		{
			throw_error("Couldn't get categories data!", __LINE__, __FILE__, $sql);
		}
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);
		if( !$row )
		{
			throw_error("Couldn't get categories data!", __LINE__, __FILE__, $sql);
		}
		$next_cat_order = $row['cat_order'] + 10;

		$sql = 'INSERT INTO ' . CATEGORIES_TABLE . ' (cat_title, cat_order)
			VALUES (\'' . $lang['New_cat_name'] . "', $next_cat_order)";
		$result = $db->sql_query($sql);
		if( !$result )
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
		// Höchten Wert von forum_id ermitteln
		$sql = 'SELECT Max(forum_id) AS forum_id
			FROM ' . FORUMS_TABLE;
		$result = $db->sql_query($sql);
		if( !$result )
		{
			throw_error("Couldn't get forum data!", __LINE__, __FILE__, $sql);
		}
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);
		if( !$row )
		{
			throw_error("Couldn't get forum data!", __LINE__, __FILE__, $sql);
		}
		$next_forum_id = $row['forum_id'] + 1;
		// Höchten Wert von forum_order ermitteln
		$sql = 'SELECT Max(forum_order) AS forum_order
			FROM ' . FORUMS_TABLE . "
			WHERE cat_id = $cat_id";
		$result = $db->sql_query($sql);
		if( !$result )
		{
			throw_error("Couldn't get forum data!", __LINE__, __FILE__, $sql);
		}
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);
		if( !$row )
		{
			throw_error("Couldn't get forum data!", __LINE__, __FILE__, $sql);
		}
		$next_forum_order = $row['forum_order'] + 10;

		$forum_permission = AUTH_ADMIN;
		$sql = 'INSERT INTO ' . FORUMS_TABLE . " (forum_id, cat_id, forum_name, forum_desc, forum_status, forum_order, forum_posts, forum_topics, forum_last_post_id, prune_next, prune_enable, auth_view, auth_read, auth_post, auth_reply, auth_edit, auth_delete, auth_sticky, auth_announce, auth_vote, auth_pollcreate, auth_attachments)
			VALUES ($next_forum_id, $cat_id, '" . $lang['New_forum_name'] . "', '', " . FORUM_LOCKED . ", $next_forum_order, 0, 0, 0, NULL, 0, $forum_permission, $forum_permission, $forum_permission, $forum_permission, $forum_permission, $forum_permission, $forum_permission, $forum_permission, $forum_permission, $forum_permission, 0)";
		$result = $db->sql_query($sql);
		if( !$result )
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
		$sql = 'INSERT INTO ' . TOPICS_TABLE . " (forum_id, topic_title, topic_poster, topic_time, topic_views, topic_replies, topic_status, topic_vote, topic_type, topic_first_post_id, topic_last_post_id, topic_moved_id)
			VALUES ($forum_id, '" . $lang['New_topic_name'] . "', -1, " . time() . ", 0, 0, " . TOPIC_UNLOCKED . ", 0, " . POST_NORMAL . ", 0, 0, 0)";
		$result = $db->sql_query($sql);
		if( !$result )
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

	$sql = 'SELECT Min(post_id) AS first_post
		FROM ' . POSTS_TABLE . "
		WHERE topic_id = $topic_id";
	$result = $db->sql_query($sql);
	if( !$result )
	{
		throw_error("Couldn't get post data!", __LINE__, __FILE__, $sql);
	}
	$row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	if( !$row || $row['first_post'] == '')
	{
		return DELETED;
	}
	$sql = 'SELECT poster_id
		FROM ' . POSTS_TABLE . '
		WHERE post_id = ' . $row['first_post'];
	$result = $db->sql_query($sql);
	if( !$result )
	{
		throw_error("Couldn't get post data!", __LINE__, __FILE__, $sql);
	}
	$row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	if( !$row )
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
	global $board_config, $db, $lang, $phpEx, $template, $theme;
	global $stopword_array, $synonym_array;

	// Check whether word is in stopword array
	if ( in_array($word, $stopword_array) )
	{
		return NULL;
	}
	if ( in_array($word, $synonym_array[1]) )
	{
		$key = array_search($word, $synonym_array[1]);
		$word = $synonym_array[0][$key];
	}

	$sql = "SELECT word_id, word_common
		FROM " . SEARCH_WORD_TABLE . "
		WHERE word_text = '$word'";
	$result = $db->sql_query($sql);
	if ( !$result )
	{
		include('./page_header_admin.' . $phpEx);
		throw_error("Couldn't get search word data!", __LINE__, __FILE__, $sql);
	}
	if ( $row = $db->sql_fetchrow($result) ) // Word was found
	{
		if ( $row['word_common'] ) // Common word
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
		$sql = "INSERT INTO " . SEARCH_WORD_TABLE . " (word_text, word_common)
			VALUES ('$word', 0)";
		if ( !$db->sql_query($sql) )
		{
			include('./page_header_admin.' . $phpEx);
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
		$result = $db->sql_query($sql2);
		if( !$result )
		{
			throw_error("Couldn't get table status!", __LINE__, __FILE__, $sql2);
		}
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);
		if( !$row )
		{
			throw_error("Couldn't get table status!", __LINE__, __FILE__, $sql2);
		}
		if (strpos($row['Extra'], 'auto_increment') !== false)
		{
			echo("<li>$table: " . $lang['Ai_message_no_update'] . "</li>\n");
		}
		else
		{
			echo("<li>$table: <b>" . $lang['Ai_message_update_table'] . "</b></li>\n");
			$result = $db->sql_query($sql);
			if( !$result )
			{
				throw_error("Couldn't alter table!", __LINE__, __FILE__, $sql);
			}
		}
	}
	else // old Version of MySQL - do the update in any case
	{
		echo("<li>$table: <b>" . $lang['Ai_message_update_table_old_mysql'] . "</b></li>\n");
		$result = $db->sql_query($sql);
		if( !$result )
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
	if ( DEBUG )
	{
		$sql_error = $db->sql_error();

		$debug_text = '';

		if ( $sql_error['message'] != '' )
		{
			$debug_text .= '<br /><br />SQL Error : ' . $sql_error['code'] . ' ' . $sql_error['message'];
		}

		if ( $sql_store != '' )
		{
			$debug_text .= "<br /><br />$sql_store";
		}

		if ( $err_line != '' && $err_file != '' )
		{
			$debug_text .= '<br /><br />Line : ' . $err_line . '<br />File : ' . $err_file;
		}
	}
	else
	{
		$debug_text = '';
	}

	if ( $msg_text == '' )
	{
		$msg_text = $lang['An_error_occured'];
	}

	echo('<p class="gen"><b>' . $lang['Error'] . ":</b> $msg_text$debug_text</p>\n");

	exit;
}

function language_select($default, $select_name = "language", $file_to_check = "main", $dirname="language")
{
	global $phpEx, $phpbb_root_path, $lang;

	$dir = opendir($phpbb_root_path . $dirname);

	$lg = array();
	while ( $file = readdir($dir) )
	{
		if (preg_match('#^lang_#i', $file) && !is_file(@phpbb_realpath($phpbb_root_path . $dirname . '/' . $file)) && !is_link(@phpbb_realpath($phpbb_root_path . $dirname . '/' . $file)) && is_file(@phpbb_realpath($phpbb_root_path . $dirname . '/' . $file . '/lang_' . $file_to_check . '.' . $phpEx)) )
		{
			$filename = trim(str_replace("lang_", "", $file));
			$displayname = preg_replace("/^(.*?)_(.*)$/", "\\1 [ \\2 ]", $filename);
			$displayname = preg_replace("/\[(.*?)_(.*)\]/", "[ \\1 - \\2 ]", $displayname);
			$lg[$displayname] = $filename;
		}
	}

	closedir($dir);

	@asort($lg);
	@reset($lg);

	if ( count($lg) )
	{
		$lang_select = '<select name="' . $select_name . '">';
		while ( list($displayname, $filename) = @each($lg) )
		{
			$selected = ( strtolower($default) == strtolower($filename) ) ? ' selected="selected"' : '';
			$lang_select .= '<option value="' . $filename . '"' . $selected . '>' . ucwords($displayname) . '</option>';
		}
		$lang_select .= '</select>';
	}
	else
	{
		$lang_select = $lang['No_selectable_language'];
	}

	return $lang_select;
}

function style_select($default_style, $select_name = "style", $dirname = "templates")
{
	global $db;

	$sql = "SELECT themes_id, style_name
		FROM " . THEMES_TABLE . "
		ORDER BY template_name, themes_id";
	if ( !($result = $db->sql_query($sql)) )
	{
		erc_throw_error('Couldn\'t query themes table', __LINE__, __FILE__, $sql);
	}

	$style_select = '<select name="' . $select_name . '">';
	while ( $row = $db->sql_fetchrow($result) )
	{
		$selected = ( $row['themes_id'] == $default_style ) ? ' selected="selected"' : '';
		$style_select .= '<option value="' . $row['themes_id'] . '"' . $selected . '>' . htmlspecialchars($row['style_name']) . '</option>';
	}
	$db->sql_freeresult($result);
	$style_select .= "</select>";

	return $style_select;
}

function check_authorisation($die = true)
{
	global $db, $lang, $dbuser, $dbpasswd, $option, $_POST;

	$auth_method = ( isset($_POST['auth_method']) ) ? htmlspecialchars($_POST['auth_method']) : '';
	$board_user = isset($_POST['board_user']) ? trim(htmlspecialchars($_POST['board_user'])) : '';
	$board_user = substr(str_replace("\\'", "'", $board_user), 0, 25);
	$board_user = str_replace("'", "\\'", $board_user);
	$board_password = ( isset($_POST['board_password']) ) ? $_POST['board_password'] : '';
	$db_user = ( isset($_POST['db_user']) ) ? $_POST['db_user'] : '';
	$db_password = ( isset($_POST['db_password']) ) ? $_POST['db_password'] : '';
	// Change authentication mode if selected option does not allow database authentication
	if ( $option == 'rld' || $option == 'rtd' )
	{
		$auth_method = 'board';
	}

	switch ($auth_method)
	{
		case 'board':
			$sql = "SELECT user_id, username, user_password, user_active, user_level
				FROM " . USERS_TABLE . "
				WHERE username = '" . str_replace("\\'", "''", $board_user) . "'";
			if ( !($result = $db->sql_query($sql)) )
			{
				erc_throw_error('Error in obtaining userdata', __LINE__, __FILE__, $sql);
			}
			if( $row = $db->sql_fetchrow($result) )
			{
				if( md5($board_password) == $row['user_password'] && $row['user_active'] && $row['user_level'] == ADMIN )
				{
					$allow_access = true;
				}
				else
				{
					$allow_access = false;
				}
			}
			else
			{
				$allow_access = false;
			}
			$db->sql_freeresult($result);
			break;
		case 'db':
			if ($db_user == $dbuser && $db_password == $dbpasswd)
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
	if ( !$allow_access && $die )
	{
?>
	<p><span style="color:red"><?php echo $lang['Auth_failed']; ?></span></p>
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
	$result = $db->sql_query($sql);
	if ( !$result )
	{
		erc_throw_error("Couldn't get config data!", __LINE__, __FILE__, $sql);
	}
	$row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	if ( !$row )
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
	<p style="text-align:center"><a href="<?php echo $_SERVER['PHP_SELF'] . '?lg=' . $lg; ?>"><?php echo $lang['Return_ERC']; ?></a></p>
<?php
}
?>