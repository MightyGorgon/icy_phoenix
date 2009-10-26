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

// CTracker_Ignore: File Checked By Human
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

if ($dbms != 'mysql' && $dbms != 'mysql4')
{
	message_die(GENERAL_MESSAGE, $lang['incompatible_dbms']);
	exit;
}

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

		print '<td class="row1" align="left" valign="top" width="20%" style="padding-top: 5; padding-left: 5"><span class="genmed"><b>' . $table_name . '</b></span></td><td class="row2"><table width="100%" cellspacing="0" cellpadding="0" border="0">';
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
		$key = str_replace("\'", "''", $key);

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
$config_records = array('config_id', 'board_disable', 'sitename', 'site_desc', 'cookie_name', 'cookie_path', 'cookie_domain', 'cookie_secure', 'session_length', 'allow_html', 'allow_html_tags', 'allow_bbcode', 'allow_smilies', 'allow_sig', 'allow_namechange', 'allow_theme_create', 'allow_avatar_local', 'allow_avatar_remote', 'allow_avatar_upload', 'enable_confirm', 'override_user_style', 'posts_per_page', 'topics_per_page', 'hot_threshold', 'max_poll_options', 'max_sig_chars', 'max_inbox_privmsgs', 'max_sentbox_privmsgs', 'max_savebox_privmsgs', 'board_email_sig', 'board_email', 'smtp_delivery', 'smtp_host', 'smtp_username', 'smtp_password', 'sendmail_fix', 'require_activation', 'flood_interval', 'board_email_form', 'avatar_filesize', 'avatar_max_width', 'avatar_max_height', 'avatar_path', 'avatar_gallery_path', 'smilies_path', 'default_style', 'default_dateformat', 'board_timezone', 'prune_enable', 'privmsg_disable', 'gzip_compress', 'coppa_fax', 'coppa_mail', 'record_online_users', 'record_online_date', 'server_name', 'server_port', 'script_path', 'sig_line', 'birthday_required', 'birthday_greeting', 'max_user_age', 'min_user_age', 'birthday_check_day', 'bluecard_limit', 'bluecard_limit_2', 'max_user_bancard', 'report_forum', 'index_rating_return', 'min_rates_number', 'rating_max', 'allow_ext_rating', 'large_rating_return_limit', 'check_anon_ip_when_rating', 'allow_rerate', 'header_rating_return_limit', 'default_time_mode', 'default_dst_time_lag', 'search_flood_interval', 'rand_seed', 'allow_news', 'news_item_trim', 'news_title_trim', 'news_item_num', 'news_path', 'allow_rss', 'news_rss_desc', 'news_rss_language', 'news_rss_ttl', 'news_rss_cat', 'news_rss_image', 'news_rss_image_desc', 'news_rss_item_count', 'news_rss_show_abstract', 'news_base_url', 'news_index_file', 'dbmtnc_rebuild_end', 'dbmtnc_rebuild_pos', 'dbmtnc_rebuildcfg_maxmemory', 'dbmtnc_rebuildcfg_minposts', 'dbmtnc_rebuildcfg_php3only', 'dbmtnc_rebuildcfg_php3pps', 'dbmtnc_rebuildcfg_php4pps', 'dbmtnc_rebuildcfg_timelimit', 'dbmtnc_rebuildcfg_timeoverwrite', 'dbmtnc_disallow_postcounter', 'dbmtnc_disallow_rebuild', 'default_avatar_guests_url', 'default_avatar_users_url', 'enable_gravatars', 'gravatar_rating', 'gravatar_default_image', 'default_avatar_set', 'bin_forum', 'liw_enabled', 'liw_sig_enabled', 'liw_max_width', 'liw_attach_enabled', 'xs_news_version', 'board_disable_message', 'board_disable_mess_st', 'sitemap_announce_priority', 'sitemap_default_priority', 'sitemap_sort', 'sitemap_sticky_priority', 'sitemap_topic_limit', 'registration_status', 'registration_closed', 'prune_shouts', 'xs_shownav', 'allow_avatar_generator', 'avatar_generator_template_path', 'avatar_generator_version', 'max_login_attempts', 'login_reset_time', 'hidde_last_logon', 'online_time', 'gzip_level', 'gender_required', 'smilie_columns', 'smilie_rows', 'smilie_window_columns', 'allow_autologin', 'max_autologin_time', 'autolink_first', 'smilies_insert', 'sudoku_version', 'yahoo_search_savepath', 'yahoo_search_additional_urls', 'yahoo_search_compress', 'yahoo_search_compression_level', 'max_link_bookmarks', 'visit_counter', 'word_graph_max_words', 'word_graph_word_counts', 'search_min_chars', 'disable_registration_ip_check', 'extra_max', 'extra_display', 'upi2db_max_permanent_topics', 'upi2db_del_mark', 'upi2db_del_perm', 'upi2db_max_mark_posts', 'upi2db_unread_color', 'upi2db_edit_color', 'upi2db_mark_color', 'upi2db_auto_read', 'upi2db_edit_as_new', 'upi2db_last_edit_as_new', 'upi2db_on', 'upi2db_edit_topic_first', 'upi2db_no_group_min_regdays', 'upi2db_no_group_min_posts', 'upi2db_no_group_upi2db_on', 'upi2db_install_time', 'upi2db_delete_old_data', 'upi2db_max_new_posts', 'upi2db_version', 'use_captcha', 'version', 'board_startdate', 'default_lang', 'calendar_display_open', 'calendar_display_open_over', 'calendar_header_cells', 'calendar_header_cells_over', 'calendar_week_start', 'calendar_week_start_over', 'calendar_title_length', 'calendar_text_length', 'calendar_nb_row', 'calendar_nb_row_over', 'calendar_birthday', 'calendar_birthday_over', 'calendar_forum', 'calendar_forum_over', 'sub_forum', 'sub_forum_over', 'split_cat', 'split_cat_over', 'last_topic_title', 'last_topic_title_over', 'last_topic_title_length', 'sub_level_links', 'sub_level_links_over', 'display_viewonline', 'display_viewonline_over', 'max_posts', 'max_topics', 'max_users', 'xs_auto_compile', 'xs_auto_recompile', 'xs_use_cache', 'xs_php', 'xs_def_template', 'xs_check_switches', 'xs_warn_includes', 'xs_add_comments', 'xs_ftp_host', 'xs_ftp_login', 'xs_ftp_path', 'xs_downloads_count', 'xs_downloads_default', 'xs_template_time', 'xs_version', 'similar_stopwords', 'similar_ignore_forums_ids', 'similar_sort_type', 'similar_max_topics', 'similar_topics', 'bb_usage_stats_viewlevel', 'bb_usage_stats_viewoptions', 'bb_usage_stats_specialgrp', 'bb_usage_stats_prscale', 'bb_usage_stats_trscale', 'forum_wordgraph', 'shoutbox_floodinterval', 'display_shouts', 'stored_shouts', 'shoutbox_refreshtime', 'shout_allow_guest', 'upi2db_max_new_posts_admin', 'upi2db_max_new_posts_mod', 'smilie_window_rows', 'bots_color', 'show_calendar_box_index', 'url_rw', 'xmas_fx', 'switch_header_table', 'header_table_text', 'fast_n_furious', 'new_msgs_mumber', 'index_last_msgs', 'portal_last_msgs', 'online_last_msgs', 'index_shoutbox', 'portal_shoutbox', 'online_shoutbox', 'last_msgs_n', 'last_msgs_x', 'posts_precompiled', 'index_links', 'index_birthday', 'site_history', 'smilies_topic_title', 'html_email', 'config_cache', 'admin_protect', 'disable_ftr', 'disable_logins', 'last_logins_n', 'edit_notes', 'edit_notes_n', 'quote_iterations', 'page_gen', 'birthday_viewtopic', 'img_shoutbox', 'split_ga_ann_sticky', 'email_notification_html', 'select_theme', 'select_lang', 'show_icons', 'show_random_quote', 'visit_counter_switch', 'emails_only_to_admins', 'no_right_click', 'gd_version', 'show_img_no_gd', 'show_pic_size_on_thumb', 'thumbnail_posts', 'thumbnail_cache', 'thumbnail_quality', 'thumbnail_size', 'disable_html_guests', 'disable_email_error', 'switch_header_dropdown', 'switch_poster_info_topic', 'switch_bbcb_active_content', 'thumbnail_lightbox', 'enable_quick_quote', 'enable_xs_version_check', 'allow_all_bbcode', 'ip_version', 'enable_digests', 'digests_php_cron', 'digests_last_send_time', 'xmas_gfx', 'google_bot_detector', 'logs_path', 'url_rw_guests', 'lofi_bots', 'ajax_checks_register', 'inactive_users_memberlists', 'auth_view_pic_upload', 'enable_postimage_org', 'enable_new_messages_number', 'disable_thanks_topics', 'ajax_features', 'show_rss_forum_icon', 'disable_acronyms', 'disable_autolinks', 'disable_censor', 'global_disable_acronyms', 'global_disable_autolinks', 'global_disable_censor', 'disable_topic_view', 'disable_referrers', 'aprvmArchive', 'aprvmVersion', 'aprvmView', 'aprvmRows', 'aprvmIP', 'page_title_simple', 'digests_php_cron_lock', 'mg_log_actions', 'cash_disable', 'cash_display_after_posts', 'cash_post_message', 'cash_disable_spam_num', 'cash_disable_spam_time', 'cash_disable_spam_message', 'cash_installed', 'cash_version', 'cash_adminbig', 'cash_adminnavbar', 'points_name', 'active_users_color', 'active_users_legend', 'bots_legend', 'show_social_bookmarks', 'show_forums_online_users', 'cms_dock', 'smilie_single_row', 'main_admin_id', 'allow_mods_edit_admin_posts', 'force_large_caps_mods', 'enable_colorpicker', 'always_show_edit_by', 'show_new_reply_posting', 'show_chat_online', 'allow_zebra', 'allow_mods_view_self', 'enable_own_icons', 'show_thanks_profile', 'show_thanks_viewtopic', 'index_top_posters', 'global_disable_upi2db', 'last_user_id', 'write_errors_log', 'write_digests_log', 'no_bump', 'link_this_topic', 'cms_style', 'show_alpha_bar', 'db_log_actions', 'show_topic_description', 'bots_reg_auth', 'cron_global_switch', 'cron_lock', 'cron_queue_interval', 'cron_queue_last_run', 'cron_digests_interval', 'cron_digests_last_run', 'cron_files_interval', 'cron_files_last_run', 'cron_database_interval', 'cron_database_last_run', 'cron_cache_interval', 'cron_cache_last_run', 'cron_sql_interval', 'cron_sql_last_run', 'cron_users_interval', 'cron_users_last_run', 'cron_topics_interval', 'cron_topics_last_run', 'cron_sessions_interval', 'cron_sessions_last_run', 'cron_db_count', 'cron_db_show_begin_for', 'cron_db_show_not_optimized', 'rand_seed_last_update', 'gsearch_guests', 'ads_glt', 'ads_glb', 'ads_glh', 'ads_glf', 'ads_fix', 'ads_fit', 'ads_fib', 'ads_vfx', 'ads_vft', 'ads_vfb', 'ads_vtx', 'ads_vtt', 'ads_vtb', 'ads_nmt', 'ads_nmb', 'adsense_code', 'google_analytics', 'thumbnail_highslide', 'read_only_forum'
);

$valid_fields = array(
	$table_prefix . 'acronyms' => array('acronym_id', 'acronym', 'description'),
	$table_prefix . 'adminedit' => array('edit_id', 'edituser', 'editok'),
	$table_prefix . 'ajax_shoutbox' => array('shout_id', 'user_id', 'shouter_name', 'shout_text', 'shouter_ip', 'shout_time'),
	$table_prefix . 'ajax_shoutbox_sessions' => array('session_id', 'session_user_id', 'session_username', 'session_ip', 'session_start', 'session_time'),
	$table_prefix . 'album' => array('pic_id', 'pic_filename', 'pic_size', 'pic_thumbnail', 'pic_title', 'pic_desc', 'pic_user_id', 'pic_username', 'pic_user_ip', 'pic_time', 'pic_cat_id', 'pic_view_count', 'pic_lock', 'pic_approval'),
	$table_prefix . 'album_cat' => array('cat_id', 'cat_title', 'cat_desc', 'cat_wm', 'cat_pics', 'cat_order', 'cat_view_level', 'cat_upload_level', 'cat_rate_level', 'cat_comment_level', 'cat_edit_level', 'cat_delete_level', 'cat_view_groups', 'cat_upload_groups', 'cat_rate_groups', 'cat_comment_groups', 'cat_edit_groups', 'cat_delete_groups', 'cat_moderator_groups', 'cat_approval', 'cat_parent', 'cat_user_id'),
	$table_prefix . 'album_comment' => array('comment_id', 'comment_pic_id', 'comment_cat_id', 'comment_user_id', 'comment_username', 'comment_user_ip', 'comment_time', 'comment_text', 'comment_edit_time', 'comment_edit_count', 'comment_edit_user_id'),
	$table_prefix . 'album_comment_watch' => array('pic_id', 'user_id', 'notify_status'),
	$table_prefix . 'album_config' => array('config_name', 'config_value'),
	$table_prefix . 'album_rate' => array('rate_pic_id', 'rate_user_id', 'rate_user_ip', 'rate_point', 'rate_hon_point'),
	$table_prefix . 'attach_quota' => array('user_id', 'group_id', 'quota_type', 'quota_limit_id'),
	$table_prefix . 'attachments' => array('attach_id', 'post_id', 'privmsgs_id', 'user_id_1', 'user_id_2'),
	$table_prefix . 'attachments_config' => array('config_name', 'config_value'),
	$table_prefix . 'attachments_desc' => array('attach_id', 'physical_filename', 'real_filename', 'download_count', 'comment', 'extension', 'mimetype', 'filesize', 'filetime', 'thumbnail'),
	$table_prefix . 'attachments_stats' => array('attach_id', 'user_id', 'user_ip', 'user_http_agents', 'download_time'),
	$table_prefix . 'auth_access' => array('group_id', 'forum_id', 'auth_view', 'auth_read', 'auth_post', 'auth_reply', 'auth_edit', 'auth_delete', 'auth_sticky', 'auth_announce', 'auth_globalannounce', 'auth_news', 'auth_cal', 'auth_vote', 'auth_pollcreate', 'auth_attachments', 'auth_download', 'auth_ban', 'auth_greencard', 'auth_bluecard', 'auth_rate', 'auth_mod'),
	$table_prefix . 'autolinks' => array('link_id', 'link_keyword', 'link_title', 'link_url', 'link_comment', 'link_style', 'link_forum', 'link_int'),
	$table_prefix . 'banlist' => array('ban_id', 'ban_userid', 'ban_ip', 'ban_email', 'ban_time', 'ban_expire_time', 'ban_by_userid', 'ban_priv_reason', 'ban_pub_reason_mode', 'ban_pub_reason'),
	$table_prefix . 'bookmarks' => array('topic_id', 'user_id'),
	$table_prefix . 'bots' => array('bot_id', 'bot_active', 'bot_name', 'bot_color', 'bot_agent', 'bot_ip', 'bot_last_visit', 'bot_visit_counter'),
	$table_prefix . 'captcha_config' => array('config_name', 'config_value'),
	$table_prefix . 'cash' => array('cash_id', 'cash_order', 'cash_settings', 'cash_dbfield', 'cash_name', 'cash_default', 'cash_decimals', 'cash_imageurl', 'cash_exchange', 'cash_perpost', 'cash_postbonus', 'cash_perreply', 'cash_maxearn', 'cash_perpm', 'cash_perchar', 'cash_allowance', 'cash_allowanceamount', 'cash_allowancetime', 'cash_allowancenext', 'cash_forumlist'),
	$table_prefix . 'cash_events' => array('event_name', 'event_data'),
	$table_prefix . 'cash_exchange' => array('ex_cash_id1', 'ex_cash_id2', 'ex_cash_enabled'),
	$table_prefix . 'cash_groups' => array('group_id', 'group_type', 'cash_id', 'cash_perpost', 'cash_postbonus', 'cash_perreply', 'cash_perthanks', 'cash_perchar', 'cash_maxearn', 'cash_perpm', 'cash_allowance', 'cash_allowanceamount', 'cash_allowancetime', 'cash_allowancenext'),
	$table_prefix . 'cash_log' => array('log_id', 'log_time', 'log_type', 'log_action', 'log_text'),
	$table_prefix . 'categories' => array('cat_id', 'cat_main', 'cat_main_type', 'cat_title', 'cat_title_clean', 'cat_desc', 'icon', 'cat_order'),
	$table_prefix . 'cms_block_position' => array('bpid', 'layout', 'pkey', 'bposition'),
	$table_prefix . 'cms_block_variable' => array('bvid', 'bid', 'label', 'sub_label', 'config_name', 'field_options', 'field_values', 'type', 'block'),
	$table_prefix . 'cms_blocks' => array('bid', 'layout', 'layout_special', 'title', 'content', 'bposition', 'weight', 'active', 'blockfile', 'view', 'cache', 'cache_time', 'type', 'border', 'titlebar', 'background', 'local', 'edit_auth', 'groups'),
	$table_prefix . 'cms_config' => array('id', 'bid', 'config_name', 'config_value'),
	$table_prefix . 'cms_layout' => array('lid', 'name', 'filename', 'template', 'global_blocks', 'page_nav', 'config_vars', 'view', 'edit_auth', 'groups'),
	$table_prefix . 'cms_layout_special' => array('lsid', 'page_id', 'locked', 'name', 'filename', 'template', 'global_blocks', 'page_nav', 'config_vars', 'view', 'edit_auth', 'groups'),
	$table_prefix . 'cms_nav_menu' => array('menu_item_id', 'menu_id', 'menu_parent_id', 'cat_id', 'cat_parent_id', 'menu_default', 'menu_status', 'menu_order', 'menu_icon', 'menu_name_lang', 'menu_name', 'menu_desc', 'menu_link', 'menu_link_external', 'auth_view', 'auth_view_group'),
	$table_prefix . 'color_groups' => array('group_id', 'group_name', 'group_color', 'hidden', 'order_num'),
	$table_prefix . 'config' => array('config_name', 'config_value'),
	$table_prefix . 'confirm' => array('confirm_id', 'session_id', 'code'),
	$table_prefix . 'ctracker_backup' => array('config_name', 'config_value'),
	$table_prefix . 'ctracker_config' => array('ct_config_name', 'ct_config_value'),
	$table_prefix . 'ctracker_filechk' => array('filepath', 'hash'),
	$table_prefix . 'ctracker_filescanner' => array('id', 'filepath', 'safety'),
	$table_prefix . 'ctracker_ipblocker' => array('id', 'ct_blocker_value'),
	$table_prefix . 'ctracker_loginhistory' => array('ct_user_id', 'ct_login_ip', 'ct_login_time'),
	$table_prefix . 'digest_subscribed_forums' => array('user_id', 'forum_id'),
	$table_prefix . 'digest_subscriptions' => array('user_id', 'digest_type', 'format', 'show_text', 'show_mine', 'new_only', 'send_on_no_messages', 'send_hour', 'text_length'),
	$table_prefix . 'disallow' => array('disallow_id', 'disallow_username'),
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
	$table_prefix . 'downloads' => array('id', 'description', 'file_name', 'klicks', 'free', 'extern', 'long_desc', 'sort', 'cat', 'hacklist', 'hack_author', 'hack_author_email', 'hack_author_website', 'hack_version', 'hack_dl_url', 'test', 'req', 'todo', 'warning', 'mod_desc', 'mod_list', 'file_size', 'change_time', 'add_time', 'rating', 'file_traffic', 'overall_klicks', 'approve', 'add_user', 'change_user', 'last_time', 'down_user', 'thumbnail', 'broken'),
	$table_prefix . 'downloads_cat' => array('id', 'parent', 'path', 'cat_name', 'sort', 'description', 'rules', 'auth_view', 'auth_dl', 'auth_up', 'auth_mod', 'must_approve', 'allow_mod_desc', 'statistics', 'stats_prune', 'comments', 'cat_traffic', 'cat_traffic_use', 'allow_thumbs', 'auth_cread', 'auth_cpost', 'approve_comments', 'bug_tracker'),
	$table_prefix . 'extension_groups' => array('group_id', 'group_name', 'cat_id', 'allow_group', 'download_mode', 'upload_icon', 'max_filesize', 'forum_permissions'),
	$table_prefix . 'extensions' => array('ext_id', 'group_id', 'extension', 'comment'),
	$table_prefix . 'flags' => array('flag_id', 'flag_name', 'flag_image'),
	$table_prefix . 'forbidden_extensions' => array('ext_id', 'extension'),
	$table_prefix . 'force_read' => array('topic_number', 'message', 'install_date', 'active', 'effected'),
	$table_prefix . 'force_read_users' => array('user', 'read', 'time'),
	$table_prefix . 'forum_prune' => array('prune_id', 'forum_id', 'prune_days', 'prune_freq'),
	$table_prefix . 'forums' => array('forum_id', 'forum_type', 'cat_id', 'main_type', 'forum_parents', 'forum_name', 'forum_name_clean', 'forum_desc', 'forum_status', 'forum_order', 'forum_posts', 'forum_topics', 'forum_last_post_id', 'forum_last_poster_id', 'forum_last_post_subject', 'forum_last_post_time', 'forum_last_poster_name', 'forum_last_poster_color', 'forum_postcount', 'forum_thanks', 'forum_notify', 'forum_limit_edit_time', 'forum_similar_topics', 'forum_tags', 'forum_index_icons', 'forum_rules', 'forum_link', 'forum_link_internal', 'forum_link_hit_count', 'forum_link_hit', 'icon', 'prune_next', 'prune_enable', 'auth_view', 'auth_read', 'auth_post', 'auth_reply', 'auth_edit', 'auth_delete', 'auth_sticky', 'auth_announce', 'auth_globalannounce', 'auth_news', 'auth_cal', 'auth_vote', 'auth_pollcreate', 'auth_attachments', 'auth_download', 'auth_ban', 'auth_greencard', 'auth_bluecard', 'auth_rate'),
	$table_prefix . 'forums_rules' => array('forum_id', 'rules', 'rules_display_title', 'rules_custom_title', 'rules_in_viewforum', 'rules_in_viewtopic', 'rules_in_posting'),
	$table_prefix . 'forums_watch' => array('forum_id', 'user_id', 'notify_status'),
	$table_prefix . 'google_bot_detector' => array('detect_id', 'detect_time', 'detect_url'),
	$table_prefix . 'groups' => array('group_id', 'group_type', 'group_name', 'group_description', 'group_moderator', 'group_single_user', 'group_rank', 'group_color', 'group_legend', 'group_legend_order', 'group_count', 'group_count_max', 'group_count_enable', 'group_dl_auto_traffic', 'upi2db_on', 'upi2db_min_posts', 'upi2db_min_regdays'),
	$table_prefix . 'hacks_list' => array('hack_id', 'hack_name', 'hack_desc', 'hack_author', 'hack_author_email', 'hack_author_website', 'hack_version', 'hack_hide', 'hack_download_url', 'hack_file', 'hack_file_mtime'),
	$table_prefix . 'jr_admin_users' => array('user_id', 'user_jr_admin', 'start_date', 'update_date', 'admin_notes', 'notes_view'),
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
	$table_prefix . 'link_categories' => array('cat_id', 'cat_title', 'cat_order'),
	$table_prefix . 'link_config' => array('config_name', 'config_value'),
	$table_prefix . 'links' => array('link_id', 'link_title', 'link_desc', 'link_category', 'link_url', 'link_logo_src', 'link_joined', 'link_active', 'link_hits', 'user_id', 'user_ip', 'last_user_ip'),
	$table_prefix . 'liw_cache' => array('image_checksum', 'image_width', 'image_height'),
	$table_prefix . 'logins' => array('login_id', 'login_userid', 'login_ip', 'login_user_agent', 'login_time'),
	$table_prefix . 'logs' => array('log_id', 'log_time', 'log_page', 'log_user_id', 'log_action', 'log_desc', 'log_target'),
	$table_prefix . 'megamail' => array('mail_id', 'mailsession_id', 'mass_pm', 'user_id', 'group_id', 'email_subject', 'email_body', 'email_format', 'batch_start', 'batch_size', 'batch_wait', 'status'),
	$table_prefix . 'news' => array('news_id', 'news_category', 'news_image'),
	$table_prefix . 'notes' => array('id', 'text'),
	$table_prefix . 'pa_auth' => array('group_id', 'cat_id', 'auth_view', 'auth_read', 'auth_view_file', 'auth_edit_file', 'auth_delete_file', 'auth_upload', 'auth_download', 'auth_rate', 'auth_email', 'auth_view_comment', 'auth_post_comment', 'auth_edit_comment', 'auth_delete_comment', 'auth_mod', 'auth_search', 'auth_stats', 'auth_toplist', 'auth_viewall'),
	$table_prefix . 'pa_cat' => array('cat_id', 'cat_name', 'cat_desc', 'cat_parent', 'parents_data', 'cat_order', 'cat_allow_file', 'cat_allow_ratings', 'cat_allow_comments', 'cat_files', 'cat_last_file_id', 'cat_last_file_name', 'cat_last_file_time', 'auth_view', 'auth_read', 'auth_view_file', 'auth_edit_file', 'auth_delete_file', 'auth_upload', 'auth_download', 'auth_rate', 'auth_email', 'auth_view_comment', 'auth_post_comment', 'auth_edit_comment', 'auth_delete_comment'),
	$table_prefix . 'pa_comments' => array('comments_id', 'file_id', 'comments_text', 'comments_title', 'comments_time', 'poster_id'),
	$table_prefix . 'pa_config' => array('config_name', 'config_value'),
	$table_prefix . 'pa_custom' => array('custom_id', 'custom_name', 'custom_description', 'data', 'field_order', 'field_type', 'regex'),
	$table_prefix . 'pa_customdata' => array('customdata_file', 'customdata_custom', 'data'),
	$table_prefix . 'pa_download_info' => array('file_id', 'user_id', 'download_time', 'downloader_ip', 'downloader_os', 'downloader_browser', 'browser_version'),
	$table_prefix . 'pa_files' => array('file_id', 'user_id', 'poster_ip', 'file_name', 'file_size', 'unique_name', 'real_name', 'file_dir', 'file_desc', 'file_creator', 'file_version', 'file_longdesc', 'file_ssurl', 'file_sshot_link', 'file_dlurl', 'file_time', 'file_update_time', 'file_catid', 'file_posticon', 'file_license', 'file_dls', 'file_last', 'file_pin', 'file_docsurl', 'file_approved', 'file_broken'),
	$table_prefix . 'pa_license' => array('license_id', 'license_name', 'license_text'),
	$table_prefix . 'pa_mirrors' => array('mirror_id', 'file_id', 'unique_name', 'file_dir', 'file_dlurl', 'mirror_location'),
	$table_prefix . 'pa_votes' => array('user_id', 'votes_ip', 'votes_file', 'rate_point', 'voter_os', 'voter_browser', 'browser_version'),
	$table_prefix . 'posts' => array('post_id', 'topic_id', 'forum_id', 'poster_id', 'post_time', 'poster_ip', 'post_username', 'post_subject', 'post_text', 'post_text_compiled', 'enable_bbcode', 'enable_html', 'enable_smilies', 'enable_autolinks_acronyms', 'enable_sig', 'edit_notes', 'post_edit_time', 'post_edit_count', 'post_edit_id', 'post_attachment', 'post_bluecard'),
	$table_prefix . 'privmsgs' => array('privmsgs_id', 'privmsgs_type', 'privmsgs_subject', 'privmsgs_from_userid', 'privmsgs_to_userid', 'privmsgs_date', 'privmsgs_ip', 'privmsgs_enable_bbcode', 'privmsgs_enable_html', 'privmsgs_enable_smilies', 'privmsgs_enable_autolinks_acronyms', 'privmsgs_attach_sig', 'privmsgs_attachment'),
	$table_prefix . 'privmsgs_archive' => array('privmsgs_id', 'privmsgs_type', 'privmsgs_subject', 'privmsgs_from_userid', 'privmsgs_to_userid', 'privmsgs_date', 'privmsgs_ip', 'privmsgs_enable_bbcode', 'privmsgs_enable_html', 'privmsgs_enable_smilies', 'privmsgs_enable_autolinks_acronyms', 'privmsgs_attach_sig', 'privmsgs_attachment'),
	$table_prefix . 'privmsgs_text' => array('privmsgs_text_id', 'privmsgs_text'),
	$table_prefix . 'profile_fields' => array('field_id', 'field_name', 'field_description', 'field_type', 'text_field_default', 'text_field_maxlen', 'text_area_default', 'text_area_maxlen', 'radio_button_default', 'radio_button_values', 'checkbox_default', 'checkbox_values', 'is_required', 'users_can_view', 'view_in_profile', 'profile_location', 'view_in_memberlist', 'view_in_topic', 'topic_location'),
	$table_prefix . 'profile_view' => array('user_id', 'viewername', 'viewer_id', 'view_stamp', 'counter'),
	$table_prefix . 'quota_limits' => array('quota_limit_id', 'quota_desc', 'quota_limit'),
	$table_prefix . 'ranks' => array('rank_id', 'rank_title', 'rank_min', 'rank_special', 'rank_image'),
	$table_prefix . 'rate_results' => array('rating_id', 'user_id', 'topic_id', 'rating', 'user_ip', 'rating_time'),
	$table_prefix . 'referrers' => array('referrer_id', 'referrer_host', 'referrer_url', 'referrer_ip', 'referrer_hits', 'referrer_firstvisit', 'referrer_lastvisit'),
	$table_prefix . 'registration' => array('topic_id', 'registration_user_id', 'registration_user_ip', 'registration_time', 'registration_status'),
	$table_prefix . 'registration_desc' => array('reg_id', 'topic_id', 'reg_active', 'reg_max_option1', 'reg_max_option2', 'reg_max_option3', 'reg_start', 'reg_length'),
	$table_prefix . 'search_results' => array('search_id', 'session_id', 'search_array', 'search_time'),
	$table_prefix . 'search_wordlist' => array('word_text', 'word_id', 'word_common'),
	$table_prefix . 'search_wordmatch' => array('post_id', 'word_id', 'title_match'),
	$table_prefix . 'sessions' => array('session_id', 'session_user_id', 'session_start', 'session_time', 'session_ip', 'session_user_agent', 'session_page', 'session_logged_in', 'session_admin'),
	$table_prefix . 'sessions_keys' => array('key_id', 'user_id', 'last_ip', 'last_login'),
	$table_prefix . 'shout' => array('shout_id', 'shout_username', 'shout_user_id', 'shout_group_id', 'shout_session_time', 'shout_ip', 'shout_text', 'shout_active', 'enable_bbcode', 'enable_html', 'enable_smilies', 'enable_sig'),
	$table_prefix . 'site_history' => array('date', 'reg', 'hidden', 'guests', 'new_topics', 'new_posts'),
	$table_prefix . 'smilies' => array('smilies_id', 'code', 'smile_url', 'emoticon', 'smilies_order'),
	$table_prefix . 'stats_config' => array('config_name', 'config_value'),
	$table_prefix . 'stats_modules' => array('module_id', 'name', 'active', 'installed', 'display_order', 'update_time', 'auth_value', 'module_info_cache', 'module_db_cache', 'module_result_cache', 'module_info_time', 'module_cache_time'),
	$table_prefix . 'sudoku_sessions' => array('user_id', 'session_time'),
	$table_prefix . 'sudoku_solutions' => array('game_pack', 'game_num', 'line_1', 'line_2', 'line_3', 'line_4', 'line_5', 'line_6', 'line_7', 'line_8', 'line_9'),
	$table_prefix . 'sudoku_starts' => array('game_pack', 'game_num', 'game_level', 'line_1', 'line_2', 'line_3', 'line_4', 'line_5', 'line_6', 'line_7', 'line_8', 'line_9'),
	$table_prefix . 'sudoku_stats' => array('user_id', 'played', 'points'),
	$table_prefix . 'sudoku_users' => array('user_id', 'game_pack', 'game_num', 'game_level', 'line_1', 'line_2', 'line_3', 'line_4', 'line_5', 'line_6', 'line_7', 'line_8', 'line_9', 'points', 'done'),
	$table_prefix . 'thanks' => array('topic_id', 'user_id', 'thanks_time'),
	$table_prefix . 'themes' => array('themes_id', 'template_name', 'style_name', 'head_stylesheet', 'body_background', 'body_bgcolor', 'tr_class1', 'tr_class2', 'tr_class3', 'td_class1', 'td_class2', 'td_class3'),
	$table_prefix . 'tickets_cat' => array('ticket_cat_id', 'ticket_cat_title', 'ticket_cat_des', 'ticket_cat_emails'),
	$table_prefix . 'title_infos' => array('id', 'title_info', 'date_format', 'admin_auth', 'mod_auth', 'poster_auth'),
	$table_prefix . 'topic_view' => array('topic_id', 'user_id', 'view_time', 'view_count'),
	$table_prefix . 'topics' => array('topic_id', 'forum_id', 'topic_title', 'topic_title_clean', 'topic_ftitle_clean', 'topic_tags', 'topic_desc', 'topic_similar_topics', 'topic_poster', 'topic_time', 'topic_views', 'topic_replies', 'topic_status', 'topic_vote', 'topic_type', 'topic_first_post_id', 'topic_first_post_time', 'topic_first_poster_id', 'topic_first_poster_name', 'topic_first_poster_color', 'topic_last_post_id', 'topic_last_post_time', 'topic_last_poster_id', 'topic_last_poster_name', 'topic_last_poster_color', 'topic_moved_id', 'topic_attachment', 'title_compl_infos', 'news_id', 'topic_calendar_time', 'topic_calendar_duration', 'topic_reg', 'topic_rating', 'topic_show_portal'),
	$table_prefix . 'topics_tags' => array('topic_id', 'tag_text'),
	$table_prefix . 'topics_watch' => array('topic_id', 'user_id', 'notify_status'),
	$table_prefix . 'upi2db_always_read' => array('topic_id', 'forum_id', 'user_id', 'last_update'),
	$table_prefix . 'upi2db_last_posts' => array('post_id', 'topic_id', 'forum_id', 'poster_id', 'post_time', 'post_edit_time', 'topic_type', 'post_edit_by'),
	$table_prefix . 'upi2db_unread_posts' => array('post_id', 'topic_id', 'forum_id', 'user_id', 'status', 'topic_type', 'last_update'),
	$table_prefix . 'user_group' => array('group_id', 'user_id', 'user_pending'),
	$table_prefix . 'users' => array('user_id', 'user_active', 'username', 'username_clean', 'user_password', 'user_session_time', 'user_session_page', 'user_http_agents', 'user_lastvisit', 'user_regdate', 'user_level', 'user_cms_level', 'user_posts', 'user_timezone', 'user_style', 'user_lang', 'user_dateformat', 'user_new_privmsg', 'user_unread_privmsg', 'user_last_privmsg', 'user_emailtime', 'user_viewemail', 'user_profile_view_popup', 'user_attachsig', 'user_setbm', 'user_allowhtml', 'user_allowbbcode', 'user_allowsmile', 'user_allowavatar', 'user_allow_pm', 'user_allow_pm_in', 'user_allow_mass_email', 'user_allow_viewonline', 'user_notify', 'user_notify_pm', 'user_popup_pm', 'user_rank', 'user_rank2', 'user_rank3', 'user_rank4', 'user_rank5', 'user_avatar', 'user_avatar_type', 'user_email', 'user_icq', 'user_website', 'user_from', 'user_sig', 'user_aim', 'user_yim', 'user_msnm', 'user_occ', 'user_interests', 'user_actkey', 'user_newpasswd', 'ct_search_time', 'ct_search_count', 'ct_last_mail', 'ct_last_post', 'ct_post_counter', 'ct_last_pw_reset', 'ct_enable_ip_warn', 'ct_last_used_ip', 'ct_login_count', 'ct_login_vconfirm', 'ct_last_pw_change', 'ct_global_msg_read', 'ct_miserable_user', 'ct_last_ip', 'user_birthday', 'user_birthday_y', 'user_birthday_m', 'user_birthday_d', 'user_next_birthday_greeting', 'user_sub_forum', 'user_split_cat', 'user_last_topic_title', 'user_sub_level_links', 'user_display_viewonline', 'user_color_group', 'user_color', 'user_gender', 'user_lastlogon', 'user_totaltime', 'user_totallogon', 'user_totalpages', 'user_calendar_display_open', 'user_calendar_header_cells', 'user_calendar_week_start', 'user_calendar_nb_row', 'user_calendar_birthday', 'user_calendar_forum', 'user_warnings', 'user_time_mode', 'user_dst_time_lag', 'user_pc_timeOffsets', 'user_skype', 'user_registered_ip', 'user_registered_hostname', 'user_profile_view', 'user_last_profile_view', 'user_topics_per_page', 'user_hot_threshold', 'user_posts_per_page', 'user_allowswearywords', 'user_showavatars', 'user_showsignatures', 'user_login_tries', 'user_last_login_try', 'user_sudoku_playing', 'user_from_flag', 'user_phone', 'user_selfdes', 'user_upi2db_which_system', 'user_upi2db_disable', 'user_upi2db_datasync', 'user_upi2db_new_word', 'user_upi2db_edit_word', 'user_upi2db_unread_color', 'user_personal_pics_count', 'user_allow_new_download_email', 'user_allow_fav_download_email', 'user_allow_new_download_popup', 'user_allow_fav_download_popup', 'user_dl_update_time', 'user_new_download', 'user_traffic', 'user_download_counter', 'user_dl_note_type', 'user_dl_sort_fix', 'user_dl_sort_opt', 'user_dl_sort_dir'),
	$table_prefix . 'vote_desc' => array('vote_id', 'topic_id', 'vote_text', 'vote_start', 'vote_length'),
	$table_prefix . 'vote_results' => array('vote_id', 'vote_option_id', 'vote_option_text', 'vote_result'),
	$table_prefix . 'vote_voters' => array('vote_id', 'vote_user_id', 'vote_user_ip', 'vote_cast'),
	$table_prefix . 'words' => array('word_id', 'word', 'replacement'),
	$table_prefix . 'xs_news' => array('news_id', 'news_date', 'news_text', 'news_display', 'news_smilies'),
	$table_prefix . 'xs_news_xml' => array('xml_id', 'xml_title', 'xml_show', 'xml_feed', 'xml_is_feed', 'xml_width', 'xml_height', 'xml_font', 'xml_speed', 'xml_direction'),
	$table_prefix . 'zebra' => array('user_id', 'zebra_id', 'friend', 'foe')
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
		<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
			<tr><td class="row-header" colspan="2"><span><?php echo $lang['title']; ?></span></td></tr>
			<tr><td class="row1" colspan="2"><br /><div class="gen"><?php echo $lang['explain']; ?></div><br /></td></tr>
		</table>
		<br />
	<?php
}
?>
		<form action="clean_tables_ip.<?php echo PHP_EXT; ?>" name="clean" method="post">
		<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
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
		<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td nowrap="nowrap" width="45%" align="left">
					<span class="copyright">&nbsp;Powered by <a href="http://www.icyphoenix.com/" target="_blank">Icy Phoenix</a> based on <a href="http://www.phpbb.com/" target="_blank">phpBB</a></span>
				</td>
				<td nowrap="nowrap" align="center"><br />&nbsp;</td>
				<td nowrap="nowrap" width="45%" align="right">
					<span class="copyright">Design by <a href="http://www.mightygorgon.com" target="_blank">Mighty Gorgon</a>&nbsp;</span>
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
