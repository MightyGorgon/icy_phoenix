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

$settings_details = array();
$settings_details = array(
	'id' => 'main_settings',
	'name' => '15_Various_Settings',
	'sort' => 0,
	'sub_name' => '',
	'sub_sort' => 0,
	'menu_name' => 'Preferences',
	'menu_sort' => 0,
	'clear_cache' => false,
);

$settings_data = array();
$settings_data = array(

	'enable_xs_version_check' => array(
		'lang_key' => 'IP_enable_xs_version_check',
		'explain' => 'IP_enable_xs_version_check_explain',
		'type' => 'LIST_RADIO',
		'default' => 0,
		'values' => $this->list_yes_no,
		),

	'privmsg_disable' => array(
		'lang_key' => 'Disable_privmsg',
		'type' => 'LIST_RADIO',
		'default' => 'Enabled',
		'values' => array(
			'Enabled' => 0,
			'Disabled' => 1,
			),
		),

	'max_inbox_privmsgs' => array(
		'lang_key' => 'Inbox_limits',
		'type' => 'SMALLINT',
		'default' => 50,
		),

	'max_sentbox_privmsgs' => array(
		'lang_key' => 'Sentbox_limits',
		'type' => 'SMALLINT',
		'default' => 25,
		),

	'max_savebox_privmsgs' => array(
		'lang_key' => 'Savebox_limits',
		'type' => 'SMALLINT',
		'default' => 50,
		),

	'disable_email_error' => array(
		'lang_key' => 'IP_disable_email_error',
		'type' => 'LIST_RADIO',
		'default' => 1,
		'values' => $this->list_yes_no,
		),

	'html_email' => array(
		'lang_key' => 'IP_html_email',
		'explain' => 'IP_html_email_explain',
		'type' => 'LIST_RADIO',
		'default' => 1,
		'values' => $this->list_yes_no,
		),

	'enable_digests' => array(
		'lang_key' => 'IP_enable_digests',
		'type' => 'LIST_RADIO',
		'default' => 0,
		'values' => $this->list_yes_no,
		),

	'digests_php_cron' => array(
		'lang_key' => 'IP_digests_php_cron',
		'explain' => 'IP_digests_php_cron_explain',
		'type' => 'LIST_RADIO',
		'default' => 1,
		'values' => $this->list_yes_no,
		),

	'emails_only_to_admins' => array(
		'lang_key' => 'IP_emails_only_to_admins',
		'explain' => 'IP_emails_only_to_admins_explain',
		'type' => 'LIST_RADIO',
		'default' => 1,
		'values' => $this->list_yes_no,
		),

	'ajax_features' => array(
		'lang_key' => 'IP_ajax_features',
		'explain' => 'IP_ajax_features_explain',
		'type' => 'LIST_RADIO',
		'default' => 1,
		'values' => $this->list_yes_no,
		),

	'ajax_checks_register' => array(
		'lang_key' => 'IP_ajax_checks_register',
		'explain' => 'IP_ajax_checks_register_explain',
		'type' => 'LIST_RADIO',
		'default' => 1,
		'values' => $this->list_yes_no,
		),

	'inactive_users_memberlists' => array(
		'lang_key' => 'IP_inactive_users_memberlists',
		'explain' => 'IP_inactive_users_memberlists_explain',
		'type' => 'LIST_RADIO',
		'default' => 0,
		'values' => $this->list_yes_no,
		),

	'page_gen' => array(
		'lang_key' => 'IP_page_gen',
		'type' => 'LIST_RADIO',
		'default' => 1,
		'values' => $this->list_yes_no,
		),

	'switch_header_dropdown' => array(
		'lang_key' => 'IP_switch_header_dropdown',
		'explain' => 'IP_switch_header_dropdown_explain',
		'type' => 'LIST_RADIO',
		'default' => 0,
		'values' => $this->list_yes_no,
		),

	'show_alpha_bar' => array(
		'lang_key' => 'IP_show_alpha_bar',
		'explain' => 'IP_show_alpha_bar_explain',
		'type' => 'LIST_RADIO',
		'default' => 0,
		'values' => $this->list_yes_no,
		),

	'show_rss_forum_icon' => array(
		'lang_key' => 'IP_show_rss_forum_icon',
		'explain' => 'IP_show_rss_forum_icon_explain',
		'type' => 'LIST_RADIO',
		'default' => 0,
		'values' => $this->list_yes_no,
		),

	'allow_mods_view_self' => array(
		'lang_key' => 'IP_allow_mods_view_self',
		'explain' => 'IP_allow_mods_view_self_explain',
		'type' => 'LIST_RADIO',
		'default' => 0,
		'values' => $this->list_yes_no,
		),

	'xmas_fx' => array(
		'lang_key' => 'IP_xmas_fx',
		'explain' => 'IP_xmas_fx_explain',
		'type' => 'LIST_RADIO',
		'default' => 0,
		'values' => $this->list_yes_no,
		),

	'xmas_gfx' => array(
		'lang_key' => 'IP_xmas_gfx',
		'explain' => 'IP_xmas_gfx_explain',
		'type' => 'LIST_RADIO',
		'default' => 0,
		'values' => $this->list_yes_no,
		),

	'select_theme' => array(
		'lang_key' => 'IP_select_theme',
		'explain' => 'IP_select_theme_explain',
		'type' => 'LIST_RADIO',
		'default' => 0,
		'values' => $this->list_yes_no,
		),

	'select_lang' => array(
		'lang_key' => 'IP_select_lang',
		'explain' => 'IP_select_lang_explain',
		'type' => 'LIST_RADIO',
		'default' => 0,
		'values' => $this->list_yes_no,
		),

	/*
	'cms_dock' => array(
		'lang_key' => 'IP_cms_dock',
		'type' => 'LIST_RADIO',
		'default' => 0,
		'values' => $this->list_yes_no,
		),
	*/

	'cms_style' => array(
		'lang_key' => 'IP_cms_style',
		'explain' => 'IP_cms_style_explain',
		'type' => 'LIST_RADIO',
		'default' => 0,
		'values' => $this->list_yes_no,
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

);

$this->init_config($settings_details, $settings_data);

?>