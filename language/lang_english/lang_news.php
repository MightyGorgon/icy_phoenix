<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
*
* @Extra credits for this file
* Lopalong
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge($lang, array(
// Main Stuff.
	'Regular_Post' => 'Regular Post (Not displayed as news)',
	'Current_Selection' => 'Current Selection',
	'Select_News_Category' => 'Select news category',
	'News' => 'News',
	'News_Cmx' => '<b>News:</b>&nbsp;',
	'Auth_News' => 'News',
	'View_Comments' => 'View Comments',
	'Read_More' => 'Read More',
	'News_Categories' => 'News Categories',

// Admin Stuff

// News Config
	'Add_news_categories' => 'Add News Categories',
	'News_Configuration' => 'News Configuration',
	'News_Add_Description' => 'Add new news categories',
	'Icon' => 'News Icon',
	'Add_new_category' => 'Add new news category',

	'Click_return_newsadmin' => 'Click %sHere%s to return to News Administration',
	'Category_Deleted' => 'News category successfully deleted',
	'Category_Updated' => 'News category successfully updated',
	'Category_Added' => 'News category successfully added',

	'News_Editing_Utility' => 'News Category Editing',
	'News_Config' => 'News Category Configuration',
	'News_Explain' => 'Add, remove and edit the news categories.',

	'Enable_News' => '<strong>Enable news posting</strong>',
	'News_Path' => '<strong>News icons path</strong>',
	'News_Path_Explain' => 'Path under your icyphoenix root dir, e.g. images/news',

	'News_explain' => 'Configure the Slashdot News Mod by CMX.',
	'News_settings' => 'News Settings',

	'News_trim' => '<strong>News Trim Length</strong>',
	'News_trim_explain' => 'Sets the max length for news posts before they are trimmed. (0 = no trim).',

	'News_topic_trim' => '<strong>News Trim Topic Length</strong>',
	'News_topic_trim_explain' => 'Sets the max length for news topics before they are trimmed. (0 = no trim).',

	'News_item_num' => '<strong>Number of Items To Display</strong>',
	'News_item_num_explain' => 'The default number of news posts displayed in news_viewnews.php.',

	'RSS_Configuration' => 'RSS News Feed Configuration',
	'Enable_RSS' => '<strong>Enable RSS Syndication</strong>',
	'Enable_RSS_explain' => 'RSS Syndication allows other websites to access your news and display it on their sites',

	'Feed_Description' => '<strong>Feed Description</strong>',
	'Feed_Description_Explain' => 'Phrase or sentence describing the feed.',

	'Feed_Language' => '<strong>Feed Language</strong>',
	'Feed_Language_Explain' => 'The language the channel is written in. You may use values defined by the W3C.',

	'Feed_TTL' => '<strong>Feed TTL (Time To Live)</strong>',
	'Feed_TTL_Explain' => 'TTL is the number of minutes indicating how long a channel can be cached before refreshing from the source.',

	'Feed_Category' => '<strong>Feed Category</strong>',

	'Feed_Image' => '<strong>Feed Image</strong>',
	'Feed_Image_Explain' => 'An image to be associated with your news feed. (Can only be a small button size image)',

	'Feed_Image_Desc' => '<strong>Feed Image Description</strong>',

// New
	'Articles' => 'Articles',
	'Archives' => 'Archives',
	'Categories' => 'Categories',

	'News_base_url' => '<strong>News Mod Base URL</strong>',
	'News_base_url_explain' => 'The location of your news index file.<br /> i.e. http://mydomain.com/, your news index file is located here.',
	'Show_RSS_abstract' => '<strong>Show Abstracts in RSS Feeds.</strong>',

	'News_index_file' => '<strong>News Mod Index File</strong>',
	'News_index_file_explain' => 'The name of the news index file. i.e. news_index.php.',
	)
);

?>