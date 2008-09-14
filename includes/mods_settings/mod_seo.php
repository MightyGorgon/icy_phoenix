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
$mod_name = '70_SEO';

$config_fields = array(

	'url_rw' => array(
		'lang_key' => 'IP_url_rw',
		'explain' => 'IP_url_rw_explain',
		'type' => 'LIST_RADIO',
		'default' => 'No',
		'values' => $list_yes_no,
		),

	'url_rw_guests' => array(
		'lang_key' => 'IP_url_rw_guests',
		'explain' => 'IP_url_rw_guests_explain',
		'type' => 'LIST_RADIO',
		'default' => 'No',
		'values' => $list_yes_no,
		),

	'lofi_bots' => array(
		'lang_key' => 'IP_lofi_bots',
		'explain' => 'IP_lofi_bots_explain',
		'type' => 'LIST_RADIO',
		'default' => 'Yes',
		'values' => $list_yes_no,
		),

	'sitemap_topic_limit' => array(
		'lang_key' => 'IP_sitemap_topic_limit',
		'explain' => 'IP_sitemap_topic_limit_explain',
		'type' => 'VARCHAR',
		'default' => '250',
		),

	'sitemap_announce_priority' => array(
		'lang_key' => 'IP_sitemap_announce_priority',
		'explain' => 'IP_sitemap_announce_priority_explain',
		'type' => 'VARCHAR',
		'default' => '1.0',
		),

	'sitemap_sticky_priority' => array(
		'lang_key' => 'IP_sitemap_sticky_priority',
		'explain' => 'IP_sitemap_sticky_priority_explain',
		'type' => 'VARCHAR',
		'default' => '0.75',
		),

	'sitemap_default_priority' => array(
		'lang_key' => 'IP_sitemap_default_priority',
		'explain' => 'IP_sitemap_default_priority_explain',
		'type' => 'VARCHAR',
		'default' => '0.5',
		),

	'sitemap_sort' => array(
		'lang_key' => 'IP_sitemap_sort',
		'type' => 'LIST_RADIO_BR',
		'default' => 'IP_sitemap_new_first',
		'values' => array(
			'IP_sitemap_new_first' => 'DESC',
			'IP_sitemap_old_first' => 'ASC',
			),
		),

	'word_graph_max_words' => array(
		'lang_key' => 'IP_word_graph_max_words',
		'explain' => 'IP_word_graph_max_words_explain',
		'type' => 'VARCHAR',
		'default' => '100',
		),

	'word_graph_word_counts' => array(
		'lang_key' => 'IP_word_graph_word_counts',
		'explain' => 'IP_word_graph_word_counts_explain',
		'type' => 'LIST_RADIO',
		'default' => 'No',
		'values' => $list_yes_no,
		),

	'forum_wordgraph' => array(
		'lang_key' => 'IP_forum_wordgraph',
		'explain' => 'IP_forum_wordgraph_explain',
		'type' => 'LIST_RADIO',
		'default' => 'Yes',
		'values' => $list_yes_no,
		),

	'similar_topics' => array(
		'lang_key' => 'IP_similar_topics',
		'type' => 'LIST_RADIO',
		'default' => 'Yes',
		'values' => $list_yes_no,
		),

	'similar_stopwords' => array(
		'lang_key' => 'IP_similar_stopwords',
		'type' => 'LIST_RADIO',
		'default' => 'Yes',
		'values' => $list_yes_no,
		),

	'similar_max_topics' => array(
		'lang_key' => 'IP_similar_max_topics',
		'type' => 'VARCHAR',
		'default' => '5',
		),

	'similar_sort_type' => array(
		'lang_key' => 'IP_similar_sort_type',
		'explain' => 'IP_similar_sort_type_explain',
		'type' => 'LIST_RADIO_BR',
		'default' => 'IP_similar_sort_type_relev',
		'values' => array(
			'IP_similar_sort_type_relev' => 'relev',
			'IP_similar_sort_type_time' => 'time',
			),
		),

	'similar_ignore_forums_ids' => array(
		'lang_key' => 'IP_similar_ignore_forums_ids',
		'explain' => 'IP_similar_ignore_forums_ids_explain',
		'type' => 'TEXT',
		'default' => '',
		),

);

init_board_config($mod_name, $config_fields);

?>