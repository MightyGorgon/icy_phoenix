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
	'id' => 'forums_settings',
	'name' => '50_Hierarchy_setting',
	'sort' => 0,
	'sub_name' => 'Preferences',
	'sub_sort' => 0,
	'menu_name' => 'Preferences',
	'menu_sort' => 0,
	'clear_cache' => false,
);

$settings_data = array();
$settings_data = array(

	'sub_forum' => array(
		'lang_key' => 'Use_sub_forum',
		'explain' => 'Index_packing_explain',
		'type' => 'LIST_RADIO',
		'default' => 1,
		'user' => 'user_sub_forum',
		'values' => array(
			'None' => 0,
			'Medium' => 1,
			'Full' => 2,
			'List' => 3,
		),
	),

	'split_cat' => array(
		'lang_key' => 'Split_categories',
		'type' => 'LIST_RADIO',
		'default' => 1,
		'user' => 'user_split_cat',
		'values' => $this->list_yes_no,
	),

	'last_topic_title' => array(
		'lang_key' => 'Use_last_topic_title',
		'type' => 'LIST_RADIO',
		'default' => 1,
		'user' => 'user_last_topic_title',
		'values' => $this->list_yes_no,
	),

	'last_topic_title_length' => array(
		'lang_key' => 'Last_topic_title_length',
		'type' => 'TINYINT',
		'default' => 24,
	),

	'sub_level_links' => array(
		'lang_key' => 'Sub_level_links',
		'explain' => 'Sub_level_links_explain',
		'type' => 'LIST_RADIO',
		'default' => 2,
		'user' => 'user_sub_level_links',
		'values' => array(
			'No' => 0,
			'Yes' => 1,
			'With_pics' => 2,
		),
	),

	'display_viewonline' => array(
		'lang_key' => 'Display_viewonline',
		'type' => 'LIST_RADIO',
		'default' => 2,
		'user' => 'user_display_viewonline',
		'values' => array(
			'Never' => 0,
			'Root_index_only' => 1,
			'Always' => 2,
		),
	),

	'max_posts' => array(
		'lang_key' => 'max_posts',
		'type' => 'INT',
		'default' => '0',
		'hide' => true,
	),

	'max_topics' => array(
		'lang_key' => 'max_topics',
		'type' => 'INT',
		'default' => '0',
		'hide' => true,
	),

	'max_users' => array(
		'lang_key' => 'max_users',
		'type' => 'INT',
		'default' => '0',
		'hide' => true,
	),
);

$this->init_config($settings_details, $settings_data);

?>