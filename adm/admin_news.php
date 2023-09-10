<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

if (!defined('IN_ICYPHOENIX')) define('IN_ICYPHOENIX', true);

if(!empty($setmodules))
{
	$filename = basename(__FILE__);
	$module['1250_News_Admin']['100_News_Config'] = $filename;
	return;
}

// Load default Header
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('pagestart.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_selects.' . PHP_EXT);

// Pull all news config data only
$sql = "SELECT *
	FROM " . CONFIG_TABLE . ' as c ' .
	" WHERE
	config_name = 'allow_news' OR
	config_name = 'news_base_url' OR
	config_name = 'news_index_file' OR
	config_name = 'news_item_trim' OR
	config_name = 'news_title_trim' OR
	config_name = 'news_item_num' OR
	config_name = 'news_path' OR
	config_name = 'allow_rss' OR
	config_name = 'news_rss_desc' OR
	config_name = 'news_rss_language' OR
	config_name = 'news_rss_ttl' OR
	config_name = 'news_rss_cat' OR
	config_name = 'news_rss_image' OR
	config_name = 'news_rss_image_desc' OR
	config_name = 'news_rss_item_count' OR
	config_name = 'news_rss_show_abstract'";

$result = $db->sql_query($sql);

while($row = $db->sql_fetchrow($result))
{
	$config_name = $row['config_name'];
	$config_value = $row['config_value'];
	$default_config[$config_name] = $config_value;

	$new[$config_name] = (isset($_POST[$config_name])) ? $_POST[$config_name] : $default_config[$config_name];

	if(isset($_POST['submit']))
	{
		set_config($config_name, $new[$config_name], false);
	}
}
$cache->destroy('config');

if(isset($_POST['submit']))
{
	$message = $lang['Config_updated'] . '<br /><br />' . sprintf($lang['Click_return_newsadmin'], '<a href="' . append_sid('admin_news.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

	message_die(GENERAL_MESSAGE, $message);
}

$news_yes = ($new['allow_news']) ? 'checked="checked"' : '';
$news_no = (!$new['allow_news']) ? 'checked="checked"' : '';

$rss_yes = ($new['allow_rss']) ? 'checked="checked"' : '';
$rss_no = (!$new['allow_rss']) ? 'checked="checked"' : '';

$rss_abstract_yes = ($new['news_rss_show_abstract']) ? 'checked="checked"' : '';
$rss_abstract_no = (!$new['news_rss_show_abstract']) ? 'checked="checked"' : '';

$template->set_filenames(array('body' => ADM_TPL . 'news_config_body.tpl'));

// Escape any quotes in the site description for proper display in the text box on the admin page
$new['news_rss_name'] = str_replace('"', '&quot;', $new['news_rss_name']);
$new['news_rss_desc'] = str_replace('"', '&quot;', strip_tags($new['news_rss_desc']));
$new['news_rss_language'] = str_replace('"', '&quot;', $new['news_rss_language']);
$new['news_rss_cat'] = str_replace('"', '&quot;', $new['news_rss_cat']);
$new['news_rss_image_desc'] = str_replace('"', '&quot;', $new['news_rss_image_desc']);

$template->assign_vars(array(
	'S_CONFIG_ACTION' => append_sid("admin_news." . PHP_EXT),

	'L_YES' => $lang['Yes'],
	'L_NO' => $lang['No'],

	'L_SUBMIT' => $lang['Submit'],
	'L_RESET' => $lang['Reset'],

	'L_CONFIGURATION_TITLE' => $lang['News_Configuration'],
	'L_CONFIGURATION_EXPLAIN' => $lang['News_explain'],

	'L_GENERAL_SETTINGS' => $lang['News_settings'],

	'L_ALLOW_NEWS_POSTING' => $lang['Enable_News'],

	'L_NEWS_TRIM' => $lang['News_trim'],
	'L_NEWS_TRIM_EXPLAIN' => $lang['News_trim_explain'],

	'L_NEWS_BASE_URL' => $lang['News_base_url'],
	'L_NEWS_BASE_URL_EXPLAIN' => $lang['News_base_url_explain'],

	'L_NEWS_INDEX_FILE' => $lang['News_index_file'],
	'L_NEWS_INDEX_FILE_EXPLAIN' => $lang['News_index_file_explain'],

	'L_NEWS_TOPIC_TRIM' => $lang['News_topic_trim'],
	'L_NEWS_TOPIC_TRIM_EXPLAIN' => $lang['News_topic_trim_explain'],

	'L_NEWS_ITEMS_DISPLAY' => $lang['News_item_num'],
	'L_NEWS_ITEMS_DISPLAY_EXPLAIN' => $lang['News_item_num_explain'],

	'L_NEWS_PATH' => $lang['News_Path'],
	'L_NEWS_PATH_EXPLAIN' => $lang['News_Path_Explain'],

	'L_RSS_SETTINGS' => $lang['RSS_Configuration'],

	'L_ALLOW_RSS' => $lang['Enable_RSS'],
	'L_ALLOW_RSS_EXPLAIN' => $lang['Enable_RSS_explain'],

	'L_RSS_SHOW_ABSTRACT' => $lang['Show_RSS_abstract'],

	'L_RSS_DESC' => $lang['Feed_Description'],
	'L_RSS_DESC_EXPLAIN' => $lang['Feed_Description_Explain'],

	'L_RSS_LANG' => $lang['Feed_Language'],
	'L_RSS_LANG_EXPLAIN' => $lang['Feed_Language_Explain'],

	'L_RSS_TTL' => $lang['Feed_TTL'],
	'L_RSS_TTL_EXPLAIN' => $lang['Feed_TTL_Explain'],

	'L_RSS_CAT' => $lang['Feed_Category'],
	'L_RSS_IMG' => $lang['Feed_Image'],
	'L_RSS_IMG_EXPLAIN' => $lang['Feed_Image_Explain'],
	'L_RSS_IMG_DESC' => $lang['Feed_Image_Desc'],

	'NEWS_YES' => $news_yes,
	'NEWS_NO' => $news_no,

	'NEWS_BASE_URL' => $new['news_base_url'],

	'NEWS_INDEX_FILE' => $new['news_index_file'],

	'NEWS_ITEM_LENGTH' => $new['news_item_trim'],
	'NEWS_TITLE_LENGTH' => $new['news_title_trim'],
	'NEWS_ITEM_NUM' => $new['news_item_num'],

	'NEWS_PATH' => $new['news_path'],

	'RSS_YES' => $rss_yes,
	'RSS_NO' => $rss_no,
	'RSS_ABSTRACT_YES' => $rss_abstract_yes,
	'RSS_ABSTRACT_NO' => $rss_abstract_no,

	'RSS_ITEM_COUNT' => $new['news_rss_item_count'],
	'RSS_DESC' => $new['news_rss_desc'],
	'RSS_LANG' => $new['news_rss_language'],
	'RSS_TTL'  => $new['news_rss_ttl'],
	'RSS_CAT'  => $new['news_rss_cat'],
	'RSS_IMG'  => $new['news_rss_image'],
	'RSS_IMG_DESC' => $new['news_rss_image_desc']

	)
);

$template->pparse('body');

include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);

?>