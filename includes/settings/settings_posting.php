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
	'id' => 'posting_settings',
	'name' => '30_Posting',
	'sort' => 0,
	'sub_name' => '',
	'sub_sort' => 0,
	'menu_name' => 'Preferences',
	'menu_sort' => 0,
	'clear_cache' => false,
);

$settings_data = array();
$settings_data = array(

	'allow_html' => array(
		'lang_key' => 'Allow_HTML',
		'separator' => 'BBCODE_SETTINGS',
		'type' => 'LIST_RADIO',
		'default' => 0,
		'values' => $this->list_yes_no,
		),

	'allow_html_only_for_admins' => array(
		'lang_key' => 'IP_allow_html_only_for_admins',
		'explain' => 'IP_allow_html_only_for_admins_explain',
		'type' => 'LIST_RADIO',
		'default' => 1,
		'values' => $this->list_yes_no,
		),

	'allow_html_tags' => array(
		'lang_key' => 'Allowed_tags',
		'explain' => 'Allowed_tags_explain',
		'type' => 'VARCHAR',
		'default' => 'a,b,i,u,pre,table,tr,td',
		),

	'allow_bbcode' => array(
		'lang_key' => 'Allow_BBCode',
		'type' => 'LIST_RADIO',
		'default' => 1,
		'values' => $this->list_yes_no,
		),

	'enable_custom_bbcodes' => array(
		'lang_key' => 'IP_enable_custom_bbcodes',
		'explain' => 'IP_enable_custom_bbcodes_explain',
		'type' => 'LIST_RADIO',
		'default' => 0,
		'values' => $this->list_yes_no,
		),

	'allow_all_bbcode' => array(
		'lang_key' => 'IP_allow_all_bbcode',
		'explain' => 'IP_allow_all_bbcode_explain',
		'type' => 'LIST_RADIO',
		'default' => 0,
		'values' => $this->list_yes_no,
		),

	'switch_bbcb_active_content' => array(
		'lang_key' => 'IP_switch_bbcb_active_content',
		'explain' => 'IP_switch_bbcb_active_content_explain',
		'type' => 'LIST_RADIO',
		'default' => 1,
		'values' => $this->list_yes_no,
		),

	'allow_smilies' => array(
		'lang_key' => 'Allow_smilies',
		'type' => 'LIST_RADIO',
		'default' => 1,
		'values' => $this->list_yes_no,
		),

	'smilies_topic_title' => array(
		'lang_key' => 'IP_smilies_topic_title',
		'explain' => 'IP_smilies_topic_title_explain',
		'type' => 'LIST_RADIO',
		'default' => 0,
		'values' => $this->list_yes_no,
		),

	'smilies_path' => array(
		'lang_key' => 'Smilies_path',
		'explain' => 'Smilies_path_explain',
		'type' => 'VARCHAR',
		'default' => 'images/smiles',
		),

	'smilie_columns' => array(
		'lang_key' => 'Smilie_table_columns',
		'type' => 'SMALLINT',
		'default' => 5,
		),

	'smilie_rows' => array(
		'lang_key' => 'Smilie_table_rows',
		'type' => 'SMALLINT',
		'default' => 5,
		),

	'smilie_window_columns' => array(
		'lang_key' => 'Smilie_window_columns',
		'type' => 'SMALLINT',
		'default' => 5,
		),

	'smilie_window_rows' => array(
		'lang_key' => 'Smilie_window_rows',
		'type' => 'SMALLINT',
		'default' => 5,
		),

	'smilie_single_row' => array(
		'lang_key' => 'Smilie_single_row',
		'explain' => 'Smilie_single_row_explain',
		'type' => 'SMALLINT',
		'default' => 15,
		),

	'autolink_first' => array(
		'lang_key' => 'Autolink_first',
		'type' => 'LIST_RADIO',
		'default' => 0,
		'values' => $this->list_yes_no,
		),

	'enable_colorpicker' => array(
		'lang_key' => 'IP_enable_colorpicker',
		'type' => 'LIST_RADIO',
		'default' => 1,
		'values' => $this->list_yes_no,
		),

	'disable_html_guests' => array(
		'lang_key' => 'IP_disable_html_guests',
		'type' => 'LIST_RADIO',
		'default' => 0,
		'values' => $this->list_yes_no,
		),

	'max_poll_options' => array(
		'lang_key' => 'Max_poll_options',
		'separator' => 'POSTING_SETTINGS',
		'type' => 'SMALLINT',
		'default' => 10,
		),

	'enable_quick_quote' => array(
		'lang_key' => 'IP_enable_quick_quote',
		'explain' => 'IP_enable_quick_quote_explain',
		'type' => 'LIST_RADIO',
		'default' => 0,
		'values' => $this->list_yes_no,
		),

	'flood_interval' => array(
		'lang_key' => 'Flood_Interval',
		'explain' => 'Flood_Interval_explain',
		'type' => 'SMALLINT',
		'default' => 15,
		),

	'search_flood_interval' => array(
		'lang_key' => 'Search_Flood_Interval',
		'explain' => 'Search_Flood_Interval_explain',
		'type' => 'SMALLINT',
		'default' => 25,
		),

	'topics_per_page' => array(
		'lang_key' => 'Topics_per_page',
		'type' => 'SMALLINT',
		'default' => 50,
		),

	'posts_per_page' => array(
		'lang_key' => 'Posts_per_page',
		'type' => 'SMALLINT',
		'default' => 10,
		),

	'hot_threshold' => array(
		'lang_key' => 'Hot_threshold',
		'type' => 'SMALLINT',
		'default' => 20,
		),

	'posts_precompiled' => array(
		'lang_key' => 'IP_posts_precompiled',
		'explain' => 'IP_posts_precompiled_explain',
		'type' => 'LIST_RADIO',
		'default' => 1,
		'values' => $this->list_yes_no,
		),

	'read_only_forum' => array(
		'lang_key' => 'IP_read_only_forum',
		'explain' => 'IP_read_only_forum_explain',
		'type' => 'LIST_RADIO',
		'default' => 0,
		'values' => $this->list_yes_no,
		),

	'allow_drafts' => array(
		'lang_key' => 'IP_allow_drafts',
		'explain' => 'IP_allow_drafts_explain',
		'type' => 'LIST_RADIO',
		'default' => 1,
		'values' => $this->list_yes_no,
		),

	'allow_mods_edit_admin_posts' => array(
		'lang_key' => 'IP_allow_mods_edit_admin_posts',
		'explain' => 'IP_allow_mods_edit_admin_posts_explain',
		'type' => 'LIST_RADIO',
		'default' => 0,
		'values' => $this->list_yes_no,
		),

	'forum_limit_edit_time_interval' => array(
		'lang_key' => 'IP_forum_limit_edit_time_interval',
		'explain' => 'IP_forum_limit_edit_time_interval_explain',
		'type' => 'VARCHAR',
		'default' => '1440',
		),

	'force_large_caps_mods' => array(
		'lang_key' => 'IP_force_large_caps_mods',
		'explain' => 'IP_force_large_caps_mods_explain',
		'type' => 'LIST_RADIO',
		'default' => 1,
		'values' => $this->list_yes_no,
		),

	'show_new_reply_posting' => array(
		'lang_key' => 'IP_show_new_reply_posting',
		'explain' => 'IP_show_new_reply_posting_explain',
		'type' => 'LIST_RADIO',
		'default' => 1,
		'values' => $this->list_yes_no,
		),

	'no_bump' => array(
		'lang_key' => 'IP_no_bump',
		'explain' => 'IP_no_bump_explain',
		'type' => 'LIST_RADIO',
		'default' => 0,
		'values' => array(
			'No' => 0,
			'Yes' => 1,
			'MODS_ALLOWED' => 2,
			),
		),

	'max_link_bookmarks' => array(
		'lang_key' => 'Max_bookmarks_links',
		'explain' => 'Max_bookmarks_links_explain',
		'type' => 'SMALLINT',
		'default' => 0,
		),

	'display_tags_box' => array(
		'lang_key' => 'IP_display_tags_box',
		'explain' => 'IP_display_tags_box_explain',
		'type' => 'LIST_RADIO',
		'default' => 0,
		'values' => $this->list_yes_no,
		),

	'allow_moderators_edit_tags' => array(
		'lang_key' => 'IP_allow_moderators_edit_tags',
		'explain' => 'IP_allow_moderators_edit_tags_explain',
		'type' => 'LIST_RADIO',
		'default' => 0,
		'values' => $this->list_yes_no,
		),

	'show_topic_description' => array(
		'lang_key' => 'IP_show_topic_description',
		'explain' => 'IP_show_topic_description_explain',
		'type' => 'LIST_RADIO',
		'default' => 0,
		'values' => $this->list_yes_no,
		),

	'edit_notes' => array(
		'lang_key' => 'IP_edit_notes',
		'explain' => 'IP_edit_notes_explain',
		'type' => 'LIST_RADIO',
		'default' => 1,
		'values' => $this->list_yes_no,
		),

	'edit_notes_n' => array(
		'lang_key' => 'IP_edit_notes_n',
		'type' => 'VARCHAR',
		'default' => '5',
		),

	'always_show_edit_by' => array(
		'lang_key' => 'IP_always_show_edit_by',
		'explain' => 'IP_always_show_edit_by_explain',
		'type' => 'LIST_RADIO',
		'default' => 0,
		'values' => $this->list_yes_no,
		),

	'show_social_bookmarks' => array(
		'lang_key' => 'IP_show_social_bookmarks',
		'explain' => 'IP_show_social_bookmarks_explain',
		'type' => 'LIST_RADIO',
		'default' => 1,
		'values' => $this->list_yes_no,
		),

	'link_this_topic' => array(
		'lang_key' => 'IP_link_this_topic',
		'explain' => 'IP_link_this_topic_explain',
		'type' => 'LIST_RADIO',
		'default' => 0,
		'values' => $this->list_yes_no,
		),

	'quote_iterations' => array(
		'lang_key' => 'IP_quote_iterations',
		'type' => 'VARCHAR',
		'default' => '2',
		),

	'ftr_disable' => array(
		'lang_key' => 'IP_ftr_disable',
		'explain' => 'IP_ftr_disable_explain',
		'type' => 'LIST_RADIO',
		'default' => 1,
		'values' => $this->list_yes_no,
		),

	'birthday_viewtopic' => array(
		'lang_key' => 'IP_birthday_viewtopic',
		'type' => 'LIST_RADIO',
		'default' => 0,
		'values' => $this->list_yes_no,
		),

	'switch_poster_info_topic' => array(
		'lang_key' => 'IP_switch_poster_info_topic',
		'type' => 'LIST_RADIO',
		'default' => 0,
		'values' => $this->list_yes_no,
		),

);

$this->init_config($settings_details, $settings_data);

?>