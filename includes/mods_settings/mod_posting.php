<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

if (!defined('IN_PHPBB'))
{
	die('Hacking attempt');
}

include_once($phpbb_root_path . 'includes/functions_mods_settings.' . $phpEx);
$mod_name = '30_Posting';

$config_fields = array(

	'posts_precompiled' => array(
		'lang_key' => 'IP_posts_precompiled',
		'explain' => 'IP_posts_precompiled_explain',
		'type' => 'LIST_RADIO',
		'default' => 'Yes',
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

	'disable_ftr' => array(
		'lang_key' => 'IP_disable_ftr',
		'explain' => 'IP_disable_ftr_explain',
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

	'allow_all_bbcode' => array(
		'lang_key' => 'IP_allow_all_bbcode',
		'explain' => 'IP_allow_all_bbcode_explain',
		'type' => 'LIST_RADIO',
		'default' => 'Yes',
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