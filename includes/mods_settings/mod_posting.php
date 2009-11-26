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

@include_once(IP_ROOT_PATH . 'includes/functions_mods_settings.' . PHP_EXT);
$mod_name = '30_Posting';

$config_fields = array(

	'posts_precompiled' => array(
		'lang_key' => 'IP_posts_precompiled',
		'explain' => 'IP_posts_precompiled_explain',
		'type' => 'LIST_RADIO',
		'default' => 'Yes',
		'values' => $list_yes_no,
		),

	'read_only_forum' => array(
		'lang_key' => 'IP_read_only_forum',
		'explain' => 'IP_read_only_forum_explain',
		'type' => 'LIST_RADIO',
		'default' => 'No',
		'values' => $list_yes_no,
		),

	'allow_drafts' => array(
		'lang_key' => 'IP_allow_drafts',
		'explain' => 'IP_allow_drafts_explain',
		'type' => 'LIST_RADIO',
		'default' => 'Yes',
		'values' => $list_yes_no,
		),

	'allow_mods_edit_admin_posts' => array(
		'lang_key' => 'IP_allow_mods_edit_admin_posts',
		'explain' => 'IP_allow_mods_edit_admin_posts_explain',
		'type' => 'LIST_RADIO',
		'default' => 'No',
		'values' => $list_yes_no,
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
		'default' => 'Yes',
		'values' => $list_yes_no,
		),

	'show_new_reply_posting' => array(
		'lang_key' => 'IP_show_new_reply_posting',
		'explain' => 'IP_show_new_reply_posting_explain',
		'type' => 'LIST_RADIO',
		'default' => 'Yes',
		'values' => $list_yes_no,
		),

	'no_bump' => array(
		'lang_key' => 'IP_no_bump',
		'explain' => 'IP_no_bump_explain',
		'type' => 'LIST_RADIO',
		'default' => 'No',
		'values' => array(
			'No' => 0,
			'Yes' => 1,
			'MODS_ALLOWED' => 2,
			),
		),

	'display_tags_box' => array(
		'lang_key' => 'IP_display_tags_box',
		'explain' => 'IP_display_tags_box_explain',
		'type' => 'LIST_RADIO',
		'default' => 'No',
		'values' => $list_yes_no,
		),

	'allow_moderators_edit_tags' => array(
		'lang_key' => 'IP_allow_moderators_edit_tags',
		'explain' => 'IP_allow_moderators_edit_tags_explain',
		'type' => 'LIST_RADIO',
		'default' => 'No',
		'values' => $list_yes_no,
		),

	'show_topic_description' => array(
		'lang_key' => 'IP_show_topic_description',
		'explain' => 'IP_show_topic_description_explain',
		'type' => 'LIST_RADIO',
		'default' => 'No',
		'values' => $list_yes_no,
		),

	'edit_notes' => array(
		'lang_key' => 'IP_edit_notes',
		'explain' => 'IP_edit_notes_explain',
		'type' => 'LIST_RADIO',
		'default' => 'Yes',
		'values' => $list_yes_no,
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
		'default' => 'No',
		'values' => $list_yes_no,
		),

	'show_social_bookmarks' => array(
		'lang_key' => 'IP_show_social_bookmarks',
		'explain' => 'IP_show_social_bookmarks_explain',
		'type' => 'LIST_RADIO',
		'default' => 'Yes',
		'values' => $list_yes_no,
		),

	'link_this_topic' => array(
		'lang_key' => 'IP_link_this_topic',
		'explain' => 'IP_link_this_topic_explain',
		'type' => 'LIST_RADIO',
		'default' => 'No',
		'values' => $list_yes_no,
		),

	'smilies_topic_title' => array(
		'lang_key' => 'IP_smilies_topic_title',
		'explain' => 'IP_smilies_topic_title_explain',
		'type' => 'LIST_RADIO',
		'default' => 'No',
		'values' => $list_yes_no,
		),

	'enable_colorpicker' => array(
		'lang_key' => 'IP_enable_colorpicker',
		'type' => 'LIST_RADIO',
		'default' => 'Yes',
		'values' => $list_yes_no,
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
		'default' => 'Yes',
		'values' => $list_yes_no,
		),

	'disable_html_guests' => array(
		'lang_key' => 'IP_disable_html_guests',
		'type' => 'LIST_RADIO',
		'default' => 'No',
		'values' => $list_yes_no,
		),

	'birthday_viewtopic' => array(
		'lang_key' => 'IP_birthday_viewtopic',
		'type' => 'LIST_RADIO',
		'default' => 'No',
		'values' => $list_yes_no,
		),

	'switch_poster_info_topic' => array(
		'lang_key' => 'IP_switch_poster_info_topic',
		'type' => 'LIST_RADIO',
		'default' => 'No',
		'values' => $list_yes_no,
		),

	'enable_quick_quote' => array(
		'lang_key' => 'IP_enable_quick_quote',
		'explain' => 'IP_enable_quick_quote_explain',
		'type' => 'LIST_RADIO',
		'default' => 'No',
		'values' => $list_yes_no,
		),

	'allow_html_only_for_admins' => array(
		'lang_key' => 'IP_allow_html_only_for_admins',
		'explain' => 'IP_allow_html_only_for_admins_explain',
		'type' => 'LIST_RADIO',
		'default' => 'Yes',
		'values' => $list_yes_no,
		),

	'enable_custom_bbcodes' => array(
		'lang_key' => 'IP_enable_custom_bbcodes',
		'explain' => 'IP_enable_custom_bbcodes_explain',
		'type' => 'LIST_RADIO',
		'default' => 'No',
		'values' => $list_yes_no,
		),

	'allow_all_bbcode' => array(
		'lang_key' => 'IP_allow_all_bbcode',
		'explain' => 'IP_allow_all_bbcode_explain',
		'type' => 'LIST_RADIO',
		'default' => 'No',
		'values' => $list_yes_no,
		),

	'switch_bbcb_active_content' => array(
		'lang_key' => 'IP_switch_bbcb_active_content',
		'explain' => 'IP_switch_bbcb_active_content_explain',
		'type' => 'LIST_RADIO',
		'default' => 'Yes',
		'values' => $list_yes_no,
		),

);

init_board_config($mod_name, $config_fields);

?>