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
	'id' => 'seo_settings',
	'name' => '70_SEO',
	'sort' => 0,
	'sub_name' => '',
	'sub_sort' => 0,
	'menu_name' => 'Preferences',
	'menu_sort' => 0,
	'clear_cache' => false,
);

$settings_data = array();
$settings_data = array(

	'url_rw' => array(
		'lang_key' => 'IP_url_rw',
		'explain' => 'IP_url_rw_explain',
		'type' => 'LIST_RADIO',
		'default' => 0,
		'values' => $this->list_yes_no,
	),

	'url_rw_guests' => array(
		'lang_key' => 'IP_url_rw_guests',
		'explain' => 'IP_url_rw_guests_explain',
		'type' => 'LIST_RADIO',
		'default' => 0,
		'values' => $this->list_yes_no,
	),

	'bots_reg_auth' => array(
		'lang_key' => 'IP_bots_reg_auth',
		'explain' => 'IP_bots_reg_auth_explain',
		'type' => 'LIST_RADIO',
		'default' => 0,
		'values' => $this->list_yes_no,
	),

	'lofi_bots' => array(
		'lang_key' => 'IP_lofi_bots',
		'explain' => 'IP_lofi_bots_explain',
		'type' => 'LIST_RADIO',
		'default' => 1,
		'values' => $this->list_yes_no,
	),

	/*
	'seo_cyrillic' => array(
		'lang_key' => 'IP_seo_cyrillic',
		'explain' => 'IP_seo_cyrillic_explain',
		'type' => 'LIST_RADIO',
		'default' => 0,
		'values' => $this->list_yes_no,
	),
	*/

	'adsense_code' => array(
		'lang_key' => 'IP_adsense_code',
		'explain' => 'IP_adsense_code_explain',
		'type' => 'VARCHAR',
		'default' => '',
	),

	'google_analytics' => array(
		'lang_key' => 'IP_google_analytics',
		'explain' => 'IP_google_analytics_explain',
		'type' => 'HTMLTEXT',
		'default' => '',
	),

	'google_custom_search' => array(
		'lang_key' => 'IP_google_custom_search',
		'explain' => 'IP_google_custom_search_explain',
		'type' => 'VARCHAR',
		'default' => '',
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
		'default' => 'DESC',
		'values' => array(
			'IP_sitemap_new_first' => 'DESC',
			'IP_sitemap_old_first' => 'ASC',
		),
	),

	'robots_index_topics_no_replies' => array(
		'lang_key' => 'IP_robots_index_topics_no_replies',
		'explain' => 'IP_robots_index_topics_no_replies_explain',
		'type' => 'LIST_RADIO',
		'default' => 1,
		'values' => $this->list_yes_no,
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
		'default' => 0,
		'values' => $this->list_yes_no,
	),

	'forum_wordgraph' => array(
		'lang_key' => 'IP_forum_wordgraph',
		'explain' => 'IP_forum_wordgraph_explain',
		'type' => 'LIST_RADIO',
		'default' => 1,
		'values' => $this->list_yes_no,
	),

	'forum_tags_type' => array(
		'lang_key' => 'IP_forum_tags_type',
		'explain' => 'IP_forum_tags_type_explain',
		'type' => 'LIST_RADIO',
		'default' => 0,
		'values' => array(
			'IP_forum_tags_type_tags' => 0,
			'IP_forum_tags_type_wordgraph' => 1,
		),
	),

	'similar_topics' => array(
		'lang_key' => 'IP_similar_topics',
		'explain' => 'IP_similar_topics_explain',
		'type' => 'LIST_RADIO',
		'default' => 1,
		'values' => $this->list_yes_no,
	),

	'similar_stopwords' => array(
		'lang_key' => 'IP_similar_stopwords',
		'type' => 'LIST_RADIO',
		'default' => 1,
		'values' => $this->list_yes_no,
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
		'default' => 'relev',
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

$this->init_config($settings_details, $settings_data);

?>