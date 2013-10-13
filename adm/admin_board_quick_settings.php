<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

define('IN_ICYPHOENIX', true);

if(!empty($setmodules))
{
	$file = basename(__FILE__);
	$module['1000_Configuration']['210_MG_Quick_Settings'] = $file;
	return;
}

if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('pagestart.' . PHP_EXT);

$mode = request_var('mode', '');

if (substr($mode, 0, 3) == 'set')
{

	$sql = array();

	if ($mode == 'set_all')
	{
		$sql[] = "UPDATE `" . $table_prefix . "album_config` SET `config_value` = '75' WHERE `config_name` = 'thumbnail_quality'";
		$sql[] = "UPDATE `" . $table_prefix . "album_config` SET `config_value` = '125' WHERE `config_name` = 'thumbnail_size'";
		$sql[] = "UPDATE `" . $table_prefix . "album_config` SET `config_value` = '1' WHERE `config_name` = 'thumbnail_cache'";
		$sql[] = "UPDATE `" . $table_prefix . "album_config` SET `config_value` = '1' WHERE `config_name` = 'midthumb_use'";
		$sql[] = "UPDATE `" . $table_prefix . "album_config` SET `config_value` = '450' WHERE `config_name` = 'midthumb_height'";
		$sql[] = "UPDATE `" . $table_prefix . "album_config` SET `config_value` = '600' WHERE `config_name` = 'midthumb_width'";
		$sql[] = "UPDATE `" . $table_prefix . "album_config` SET `config_value` = '1' WHERE `config_name` = 'midthumb_cache'";
		//$sql[] = "UPDATE `" . $table_prefix . "album_config` SET `config_value` = '1' WHERE `config_name` = 'show_img_no_gd'";
		$sql[] = "UPDATE `" . $table_prefix . "album_config` SET `config_value` = '1' WHERE `config_name` = 'show_exif'";
		$sql[] = "UPDATE `" . $table_prefix . "album_config` SET `config_value` = '1' WHERE `config_name` = 'lb_preview'";
		$sql[] = "UPDATE `" . $table_prefix . "album_config` SET `config_value` = '1' WHERE `config_name` = 'quick_thumbs'";

		$sql[] = "UPDATE `" . $table_prefix . "cms_config` SET `config_value` = '1' WHERE `config_name` = 'cache_enabled'";

		/*
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'liw_enabled'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'liw_sig_enabled'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '500' WHERE `config_name` = 'liw_max_width'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'liw_attach_enabled'";
		*/

		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'allow_html'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = 'a,b,i,u,pre,table,tr,td' WHERE `config_name` = 'allow_html_tags'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'allow_bbcode'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'allow_smilies'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'allow_sig'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'enable_confirm'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '30' WHERE `config_name` = 'posts_per_page'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '100' WHERE `config_name` = 'topics_per_page'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '50' WHERE `config_name` = 'hot_threshold'";

		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'use_captcha'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'xs_use_cache'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'forum_wordgraph'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'show_calendar_box_index'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '2' WHERE `config_name` = 'display_viewonline'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'similar_topics'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '5' WHERE `config_name` = 'similar_max_topics'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'upi2db_on'";

		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'show_pic_size_on_thumb'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'show_img_no_gd'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'thumbnail_cache'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '2' WHERE `config_name` = 'gd_version'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '75' WHERE `config_name` = 'thumbnail_quality'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '400' WHERE `config_name` = 'thumbnail_size'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'thumbnail_posts'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'thumbnail_highslide'";

		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'img_shoutbox'";

		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'index_last_msgs'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'index_birthday'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'index_shoutbox'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'show_random_quote'";

		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'online_last_msgs'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'online_shoutbox'";

		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'smilies_topic_title'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'posts_precompiled'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '2' WHERE `config_name` = 'quote_iterations'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'birthday_viewtopic'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'switch_poster_info_topic'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'switch_bbcb_active_content'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'enable_quick_quote'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'edit_notes'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '5' WHERE `config_name` = 'edit_notes_n'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'allow_all_bbcode'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'disable_thanks_topics'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'enable_postimage_org'";

		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'config_cache'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'site_history'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'ftr_disable'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'disable_logins'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '20' WHERE `config_name` = 'last_logins_n'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'page_gen'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'select_theme'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'select_lang'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'show_icons'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'visit_counter_switch'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'switch_header_dropdown'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'enable_xs_version_check'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'ajax_checks_register'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'enable_new_messages_number'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'google_bot_detector'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'extra_display'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'disable_topic_view'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'disable_referers'";

		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'ctracker_login_history'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'ctracker_login_ip_check'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'ctracker_loginfeature'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'ctracker_reg_ip_scan'";

		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'xs_show_news'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'xs_show_ticker'";
	}

	if ($mode == 'set_fnf')
	{
		$sql[] = "UPDATE `" . $table_prefix . "album_config` SET `config_value` = '75' WHERE `config_name` = 'thumbnail_quality'";
		$sql[] = "UPDATE `" . $table_prefix . "album_config` SET `config_value` = '125' WHERE `config_name` = 'thumbnail_size'";
		$sql[] = "UPDATE `" . $table_prefix . "album_config` SET `config_value` = '1' WHERE `config_name` = 'thumbnail_cache'";
		$sql[] = "UPDATE `" . $table_prefix . "album_config` SET `config_value` = '1' WHERE `config_name` = 'midthumb_use'";
		$sql[] = "UPDATE `" . $table_prefix . "album_config` SET `config_value` = '403' WHERE `config_name` = 'midthumb_height'";
		$sql[] = "UPDATE `" . $table_prefix . "album_config` SET `config_value` = '536' WHERE `config_name` = 'midthumb_width'";
		$sql[] = "UPDATE `" . $table_prefix . "album_config` SET `config_value` = '1' WHERE `config_name` = 'midthumb_cache'";
		$sql[] = "UPDATE `" . $table_prefix . "album_config` SET `config_value` = '0' WHERE `config_name` = 'show_img_no_gd'";
		$sql[] = "UPDATE `" . $table_prefix . "album_config` SET `config_value` = '0' WHERE `config_name` = 'show_exif'";
		$sql[] = "UPDATE `" . $table_prefix . "album_config` SET `config_value` = '0' WHERE `config_name` = 'lb_preview'";
		$sql[] = "UPDATE `" . $table_prefix . "album_config` SET `config_value` = '1' WHERE `config_name` = 'quick_thumbs'";

		$sql[] = "UPDATE `" . $table_prefix . "cms_config` SET `config_value` = '1' WHERE `config_name` = 'cache_enabled'";

		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'liw_enabled'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'liw_sig_enabled'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '500' WHERE `config_name` = 'liw_max_width'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'liw_attach_enabled'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'check_dnsbl'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'use_captcha'";

		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'allow_html'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'allow_bbcode'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'allow_smilies'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '10' WHERE `config_name` = 'posts_per_page'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '50' WHERE `config_name` = 'topics_per_page'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '25' WHERE `config_name` = 'hot_threshold'";

		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'birthday_required'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '7' WHERE `config_name` = 'birthday_check_day'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'xs_use_cache'";

		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'forum_wordgraph'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'show_calendar_box_index'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'display_viewonline'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'similar_topics'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '3' WHERE `config_name` = 'similar_max_topics'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'upi2db_on'";

		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'show_pic_size_on_thumb'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'show_img_no_gd'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'thumbnail_cache'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '2' WHERE `config_name` = 'gd_version'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '75' WHERE `config_name` = 'thumbnail_quality'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '400' WHERE `config_name` = 'thumbnail_size'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'thumbnail_posts'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'thumbnail_highslide'";

		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'url_rw'";

		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'img_shoutbox'";

		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'index_last_msgs'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'index_birthday'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'index_shoutbox'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'show_random_quote'";

		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'online_last_msgs'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'online_shoutbox'";

		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'smilies_topic_title'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'posts_precompiled'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '2' WHERE `config_name` = 'quote_iterations'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'birthday_viewtopic'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'switch_poster_info_topic'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'switch_bbcb_active_content'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'enable_quick_quote'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'edit_notes'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '5' WHERE `config_name` = 'edit_notes_n'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'allow_all_bbcode'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'disable_thanks_topics'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'enable_postimage_org'";

		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'config_cache'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'site_history'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'ftr_disable'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'disable_logins'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '5' WHERE `config_name` = 'last_logins_n'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'page_gen'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'select_theme'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'select_lang'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'show_icons'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'disable_email_error'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'switch_header_dropdown'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'visit_counter_switch'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'switch_header_dropdown'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'enable_xs_version_check'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'ajax_checks_register'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'enable_new_messages_number'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'google_bot_detector'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'xmas_fx'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'extra_display'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'disable_topic_view'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'disable_referers'";

		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'ctracker_auto_recovery'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'ctracker_login_history'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'ctracker_login_ip_check'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'ctracker_loginfeature'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'ctracker_reg_ip_scan'";

		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'xs_show_news'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'xs_show_ticker'";
	}

	if ($mode == 'set_mg_fav')
	{
		$sql[] = "UPDATE `" . $table_prefix . "album_config` SET `config_value` = '75' WHERE `config_name` = 'thumbnail_quality'";
		$sql[] = "UPDATE `" . $table_prefix . "album_config` SET `config_value` = '125' WHERE `config_name` = 'thumbnail_size'";
		$sql[] = "UPDATE `" . $table_prefix . "album_config` SET `config_value` = '1' WHERE `config_name` = 'thumbnail_cache'";
		$sql[] = "UPDATE `" . $table_prefix . "album_config` SET `config_value` = '1' WHERE `config_name` = 'midthumb_use'";
		$sql[] = "UPDATE `" . $table_prefix . "album_config` SET `config_value` = '450' WHERE `config_name` = 'midthumb_height'";
		$sql[] = "UPDATE `" . $table_prefix . "album_config` SET `config_value` = '600' WHERE `config_name` = 'midthumb_width'";
		$sql[] = "UPDATE `" . $table_prefix . "album_config` SET `config_value` = '1' WHERE `config_name` = 'midthumb_cache'";
		$sql[] = "UPDATE `" . $table_prefix . "album_config` SET `config_value` = '0' WHERE `config_name` = 'show_img_no_gd'";
		$sql[] = "UPDATE `" . $table_prefix . "album_config` SET `config_value` = '0' WHERE `config_name` = 'show_exif'";
		$sql[] = "UPDATE `" . $table_prefix . "album_config` SET `config_value` = '1' WHERE `config_name` = 'lb_preview'";
		$sql[] = "UPDATE `" . $table_prefix . "album_config` SET `config_value` = '1' WHERE `config_name` = 'quick_thumbs'";

		$sql[] = "UPDATE `" . $table_prefix . "cms_config` SET `config_value` = '1' WHERE `config_name` = 'cache_enabled'";

		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'liw_enabled'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'liw_sig_enabled'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '500' WHERE `config_name` = 'liw_max_width'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'liw_attach_enabled'";

		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'allow_html'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = 'a,b,i,u,pre,table,tr,td' WHERE `config_name` = 'allow_html_tags'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'allow_bbcode'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'allow_smilies'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'allow_sig'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'enable_confirm'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '20' WHERE `config_name` = 'posts_per_page'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '100' WHERE `config_name` = 'topics_per_page'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '40' WHERE `config_name` = 'hot_threshold'";

		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'birthday_required'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '7' WHERE `config_name` = 'birthday_check_day'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'xs_use_cache'";

		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'check_dnsbl'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'use_captcha'";

		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'forum_wordgraph'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'show_calendar_box_index'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '2' WHERE `config_name` = 'display_viewonline'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'similar_topics'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '5' WHERE `config_name` = 'similar_max_topics'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'upi2db_on'";

		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'url_rw'";

		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'show_pic_size_on_thumb'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'show_img_no_gd'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'thumbnail_cache'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '2' WHERE `config_name` = 'gd_version'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '75' WHERE `config_name` = 'thumbnail_quality'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '400' WHERE `config_name` = 'thumbnail_size'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'thumbnail_posts'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'thumbnail_highslide'";

		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'new_msgs_mumber'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '5' WHERE `config_name` = 'last_msgs_n'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'img_shoutbox'";

		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'index_last_msgs'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'index_birthday'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'index_shoutbox'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'show_random_quote'";

		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'online_last_msgs'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'online_shoutbox'";

		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'smilies_topic_title'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'posts_precompiled'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '2' WHERE `config_name` = 'quote_iterations'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'birthday_viewtopic'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'switch_poster_info_topic'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'switch_bbcb_active_content'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'enable_quick_quote'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'edit_notes'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '5' WHERE `config_name` = 'edit_notes_n'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'allow_all_bbcode'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'disable_thanks_topics'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'enable_postimage_org'";

		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'config_cache'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'site_history'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'ftr_disable'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'disable_logins'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '10' WHERE `config_name` = 'last_logins_n'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'page_gen'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'select_theme'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'select_lang'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'show_icons'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'disable_email_error'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'switch_header_dropdown'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'visit_counter_switch'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'enable_xs_version_check'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'ajax_checks_register'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'enable_new_messages_number'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'google_bot_detector'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'inactive_users_memberlists'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'xmas_fx'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'extra_display'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'disable_topic_view'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'disable_referers'";

		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'ctracker_auto_recovery'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'ctracker_login_history'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'ctracker_login_ip_check'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'ctracker_loginfeature'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'ctracker_reg_ip_scan'";

		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'xs_show_news'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '0' WHERE `config_name` = 'xs_show_ticker'";
	}

	//
	// Executing SQL
	//

	$output_message = '<span class="genmed"><ul type="circle">';
	for($i = 0; $i < sizeof($sql); $i++)
	{
		if(!$result = $db->sql_query ($sql[$i]))
		{
			$error = $db->sql_error();
			$output_message .= '<li>' . $sql[$i] . '<br /> +++ <span class="text_red"><b>Error:</b></span> ' . $error['message'] . '</li><br />';
		}
		else
		{
			$output_message .= '<li>' . $sql[$i] . '<br /> +++ <span class="text_green"><b>Successfull</b></span></li><br />';
		}
	}

	$output_message .= '</ul></span>';

	$message = $lang['Config_updated'] . '<br /><br />' . sprintf($lang['Click_return_config_mg'], '<a href="' . append_sid('admin_board_quick_settings.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

	message_die(GENERAL_MESSAGE, $output_message . '<br /><br />' . $message);

}

$template->set_filenames(array('body' => ADM_TPL . 'board_config_quick_settings.tpl'));

$template->assign_vars(array(
	'S_CONFIG_ACTION' => append_sid('admin_board_quick_settings.' . PHP_EXT),

	'L_CONFIGURATION_TITLE' => $lang['MG_FNF_Header'],
	'L_CONFIGURATION_EXPLAIN' => $lang['MG_FNF_Header_Explain'],
	'L_GENERAL_SETTINGS' => $lang['General_settings'],

	'L_YES' => $lang['Yes'],
	'L_NO' => $lang['No'],
	'L_ENABLED' => $lang['Enabled'],
	'L_DISABLED' => $lang['Disabled'],
	'L_SUBMIT' => $lang['Submit'],
	'L_RESET' => $lang['Reset'],

	'L_FNF_OPTIONS_SET' => $lang['MG_FNF_Options_Set'],
	'L_FNF_FNF' => $lang['MG_FNF_FNF'],
	'L_FNF_FNF_EXPLAIN' => $lang['MG_FNF_FNF_Explain'],
	'L_FNF_MGS' => $lang['MG_FNF_MGS'],
	'L_FNF_MGS_EXPLAIN' => $lang['MG_FNF_MGS_Explain'],
	'L_FNF_FULL_FEATURES' => $lang['MG_FNF_Full_Features'],
	'L_FNF_FULL_FEATURES_EXPLAIN' => $lang['MG_FNF_Full_Features_Explain'],
	/*
	'L_FNF_' => $lang['MG_FNF_'],
	'L_FNF__EXPLAIN' => $lang['MG_FNF__Explain'],
	'L_FNF_' => $lang['MG_FNF_'],
	'L_FNF__EXPLAIN' => $lang['MG_FNF__Explain'],
	*/

	'U_FNF_FNF' => append_sid('admin_board_quick_settings.' . PHP_EXT . '?mode=set_fnf'),
	'U_FNF_MGS' => append_sid('admin_board_quick_settings.' . PHP_EXT . '?mode=set_mg_fav'),
	'U_FNF_FULL_FEATURES' => append_sid('admin_board_quick_settings.' . PHP_EXT . '?mode=set_all'),
	)
);

$template->pparse('body');

include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);

?>