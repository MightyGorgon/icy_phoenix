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
$mod_name = '50_Hierarchy_setting';

$config_fields = array(

	'sub_forum' => array(
		'lang_key' => 'Use_sub_forum',
		'explain' => 'Index_packing_explain',
		'type' => 'LIST_RADIO',
		'default' => 'Medium',
		'user' => 'user_sub_forum',
		'values' => array(
			'None' => 0,
			'Medium' => 1,
			'Full' => 2,
			),
		),

	'split_cat' => array(
		'lang_key' => 'Split_categories',
		'type' => 'LIST_RADIO',
		'default' => 'Yes',
		'user' => 'user_split_cat',
		'values' => $list_yes_no,
		),

	'last_topic_title' => array(
		'lang_key' => 'Use_last_topic_title',
		'type' => 'LIST_RADIO',
		'default' => 'Yes',
		'user' => 'user_last_topic_title',
		'values' => $list_yes_no,
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
		'default' => 'With_pics',
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
		'default' => 'Always',
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

// init config table
init_board_config($mod_name, $config_fields);

?>