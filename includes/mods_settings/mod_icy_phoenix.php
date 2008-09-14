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

include_once(IP_ROOT_PATH . 'includes/functions_mods_settings.' . PHP_EXT);
$mod_name = '10_Main_Settings_Icy_Phoenix';

$config_fields = array(

	'enable_xs_version_check' => array(
		'lang_key' => 'IP_enable_xs_version_check',
		'explain' => 'IP_enable_xs_version_check_explain',
		'type' => 'LIST_RADIO',
		'default' => 'No',
		'values' => $list_yes_no,
		),

	'disable_email_error' => array(
		'lang_key' => 'IP_disable_email_error',
		'type' => 'LIST_RADIO',
		'default' => 'Yes',
		'values' => $list_yes_no,
		),

	'html_email' => array(
		'lang_key' => 'IP_html_email',
		'explain' => 'IP_html_email_explain',
		'type' => 'LIST_RADIO',
		'default' => 'Yes',
		'values' => $list_yes_no,
		),

	'enable_digests' => array(
		'lang_key' => 'IP_enable_digests',
		'type' => 'LIST_RADIO',
		'default' => 'No',
		'values' => $list_yes_no,
		),

	'digests_php_cron' => array(
		'lang_key' => 'IP_digests_php_cron',
		'explain' => 'IP_digests_php_cron_explain',
		'type' => 'LIST_RADIO',
		'default' => 'Yes',
		'values' => $list_yes_no,
		),

	'emails_only_to_admins' => array(
		'lang_key' => 'IP_emails_only_to_admins',
		'explain' => 'IP_emails_only_to_admins_explain',
		'type' => 'LIST_RADIO',
		'default' => 'Yes',
		'values' => $list_yes_no,
		),

	'ajax_features' => array(
		'lang_key' => 'IP_ajax_features',
		'explain' => 'IP_ajax_features_explain',
		'type' => 'LIST_RADIO',
		'default' => 'Yes',
		'values' => $list_yes_no,
		),

	'ajax_checks_register' => array(
		'lang_key' => 'IP_ajax_checks_register',
		'explain' => 'IP_ajax_checks_register_explain',
		'type' => 'LIST_RADIO',
		'default' => 'Yes',
		'values' => $list_yes_no,
		),

	'inactive_users_memberlists' => array(
		'lang_key' => 'IP_inactive_users_memberlists',
		'explain' => 'IP_inactive_users_memberlists_explain',
		'type' => 'LIST_RADIO',
		'default' => 'No',
		'values' => $list_yes_no,
		),

	'page_gen' => array(
		'lang_key' => 'IP_page_gen',
		'type' => 'LIST_RADIO',
		'default' => 'Yes',
		'values' => $list_yes_no,
		),

	'switch_header_dropdown' => array(
		'lang_key' => 'IP_switch_header_dropdown',
		'explain' => 'IP_switch_header_dropdown_explain',
		'type' => 'LIST_RADIO',
		'default' => 'No',
		'values' => $list_yes_no,
		),

	'show_alpha_bar' => array(
		'lang_key' => 'IP_show_alpha_bar',
		'explain' => 'IP_show_alpha_bar_explain',
		'type' => 'LIST_RADIO',
		'default' => 'No',
		'values' => $list_yes_no,
		),

	'show_rss_forum_icon' => array(
		'lang_key' => 'IP_show_rss_forum_icon',
		'explain' => 'IP_show_rss_forum_icon_explain',
		'type' => 'LIST_RADIO',
		'default' => 'No',
		'values' => $list_yes_no,
		),

	'allow_mods_view_self' => array(
		'lang_key' => 'IP_allow_mods_view_self',
		'explain' => 'IP_allow_mods_view_self_explain',
		'type' => 'LIST_RADIO',
		'default' => 'No',
		'values' => $list_yes_no,
		),

	'xmas_fx' => array(
		'lang_key' => 'IP_xmas_fx',
		'explain' => 'IP_xmas_fx_explain',
		'type' => 'LIST_RADIO',
		'default' => 'No',
		'values' => $list_yes_no,
		),

	'xmas_gfx' => array(
		'lang_key' => 'IP_xmas_gfx',
		'explain' => 'IP_xmas_gfx_explain',
		'type' => 'LIST_RADIO',
		'default' => 'No',
		'values' => $list_yes_no,
		),

	'select_theme' => array(
		'lang_key' => 'IP_select_theme',
		'explain' => 'IP_select_theme_explain',
		'type' => 'LIST_RADIO',
		'default' => 'No',
		'values' => $list_yes_no,
		),

	'select_lang' => array(
		'lang_key' => 'IP_select_lang',
		'explain' => 'IP_select_lang_explain',
		'type' => 'LIST_RADIO',
		'default' => 'No',
		'values' => $list_yes_no,
		),

	/*
	'cms_dock' => array(
		'lang_key' => 'IP_cms_dock',
		'type' => 'LIST_RADIO',
		'default' => 'No',
		'values' => $list_yes_no,
		),
	*/

	'cms_style' => array(
		'lang_key' => 'IP_cms_style',
		'explain' => 'IP_cms_style_explain',
		'type' => 'LIST_RADIO',
		'default' => 'No',
		'values' => $list_yes_no,
		),

	'mg_log_actions' => array(
		'lang_key' => 'IP_mg_log_actions',
		'explain' => 'IP_mg_log_actions_explain',
		'type' => 'LIST_RADIO',
		'default' => 'No',
		'values' => $list_yes_no,
		),

	'admin_protect' => array(
		'lang_key' => 'IP_admin_protect',
		'explain' => 'IP_admin_protect_explain',
		'type' => 'LIST_RADIO',
		'default' => 'No',
		'values' => $list_yes_no,
		),

	'split_ga_ann_sticky' => array(
		'lang_key' => 'IP_split_ga_ann_sticky',
		'explain' => 'IP_split_ga_ann_sticky_explain',
		'type' => 'LIST_RADIO_BR',
		'default' => 'IP_split_topic_1',
		'values' => array(
			'IP_split_topic_0' => 0,
			'IP_split_topic_1' => 1,
			'IP_split_topic_2' => 2,
			'IP_split_topic_3' => 3,
			),
		),

	'write_errors_log' => array(
		'lang_key' => 'IP_write_errors_log',
		'explain' => 'IP_write_errors_log_explain',
		'type' => 'LIST_RADIO',
		'default' => 'No',
		'values' => $list_yes_no,
		),

	'write_digests_log' => array(
		'lang_key' => 'IP_write_digests_log',
		'explain' => 'IP_write_digests_log_explain',
		'type' => 'LIST_RADIO',
		'default' => 'No',
		'values' => $list_yes_no,
		),

	'logs_path' => array(
		'lang_key' => 'IP_logs_path',
		'explain' => 'IP_logs_path_explain',
		'type' => 'VARCHAR',
		'default' => 'logs',
		),


);

init_board_config($mod_name, $config_fields);

?>