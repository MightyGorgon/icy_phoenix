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
* UseLess
* Lopalong
*
*/

//
// CONTRIBUTORS:
//	 Add your details here if wanted,
//
//	e.g. Date, Name, username, email address, website
//
// <date>	<Name>		<Username>	<email>		<website>
// 09-01-2005			UseLess
//

$lang['n_title'] = 'News Administration';

$lang['n_main_title'] = 'News Items';
$lang['n_main_title_explain'] = 'Manage the news items.<br /><br />If you wish to change the display status of any particular news item then you will need to edit it.';

//
// Config
//
$lang['n_config_title'] = 'News Configuration';
$lang['n_config_title_explain'] = 'Alter the settings for News.';
$lang['n_config_updated'] = 'News Configuration updated.';

//
// Add menu item
//
$lang['n_add_page_title'] = 'Add News Item';
$lang['n_add_page_title_explain'] = 'Create a new news item.';
$lang['n_add_header'] = 'Add new News item';
$lang['n_news_item_added'] = 'News Item added.';

//
// edit menu item
//
$lang['n_edit_page_title'] = 'Edit News Item';
$lang['n_edit_page_title_explain'] = 'Edit the news item.';
$lang['n_edit_header'] = 'Edit existing news item';

//
// Delete
//
$lang['n_news_delete'] = 'Delete News Item.';
$lang['n_news_delete_explain'] = 'Delete a news item.';
$lang['n_confirm_delete_news'] = 'Are you sure you wish to delete this news item?';

//
// General Settings used by Add/Edit Menu item
//
$lang['n_news_item'] = 'News Item';
$lang['n_news_item_display'] = 'Display This Item?';

$lang['n_news_date'] = 'News Date';

$lang['n_create_item'] = 'Create New Item';
$lang['n_create_item_null'] = 'Can\'t create a news item with no news text.';

$lang['n_news_smilies'] = 'Enable smileys in this message?';
$lang['n_smilies_button'] = 'Smileys';

$lang['xs_no_news'] = 'There are no news items.';
$lang['xs_news_invalid_date'] = 'You have entered an Invalid Date, the format is: dd/mm/yyyy';
$lang['n_news_updated'] = 'The News item has been updated';
$lang['n_click_return_newslist'] = 'Click %sHere%s to return to the News List';

//
// News XML Settings
//
$lang['n_xml_title'] = 'News Ticker Administration';
$lang['n_xml_title_explain'] = 'Manage the News Tickers.';
$lang['n_xml_title_explain_0'] = 'If the ticker master switch (status shown above) is set to \'Off\' then altering the display status of any ticker is pointless as it will not be shown because the master setting overrides those settings shown below.<br /><br />However, if the switch is on and you wish to alter the display status of any ticker then you will need to edit it.';
$lang['n_xml_sub_title'] = 'News Tickers.';
$lang['n_xml_master_switch'] = 'The Ticker Master Switch is: <b>%s</b> which means News Tickers <b>%s</b> be displayed.';
$lang['n_xml_ms_will'] = 'will';
$lang['n_xml_ms_not'] = 'will not';

$lang['xs_news_ticker_settings'] = 'Settings for this News Ticker';
$lang['xs_news_ticker_title'] = 'Title for this news ticker:';
$lang['xs_news_ticker_title_explain'] = 'Used in the XML News Feed list to identify each news feed.';
$lang['xs_news_ticker_show'] = 'Show this News Ticker?';
$lang['xs_news_ticker_feed'] = 'XML News Feed';
$lang['xs_news_ticker_feed_explain'] = 'The URL to where the ticker should get the news items to scroll, or the text you wish to scroll.';
$lang['xs_news_ticker_is_feed'] = 'Is this an XML News Feed?';
$lang['xs_news_ticker_is_feed_explain'] = 'If set to \'Yes\' then a valid URL for the feed must be supplied, if set to \'No\' then any text entered into the textarea above will be scrolled.';
$lang['xs_news_ticker_wh'] = 'The Width x Height of the News Ticker.';
$lang['xs_news_ticker_wh_explain'] = 'You may specify the width x height of the news ticker, the default is 98% x 20, the width is based on a percentage of the table width while the height is in pixels.';
$lang['xs_news_ticker_fontsize'] = 'Font size for the News Ticker.';
$lang['xs_news_ticker_fontsize_explain'] = 'You may override the font size specified in the stylesheet, a setting of 0 (zero) disables this feature.';
$lang['xs_news_ticker_ss'] = 'Scroll Speed';
$lang['xs_news_ticker_ss_explain'] = 'The higher the value the faster the scroll';
$lang['xs_news_ticker_sd'] = 'Scroll Direction';
$lang['xs_news_left'] = 'Left';
$lang['xs_news_right'] = 'Right';

//
// Add menu item
//
$lang['n_xml_add_page_title'] = 'Add XML News Feed';
$lang['n_xml_add_page_title_explain'] = 'Create a new XML News Feed.';
$lang['n_xml_add_header'] = 'Add new XML News Feed';
$lang['n_xml_news_item_added'] = 'News Feed added.';

//
// edit menu item
//
$lang['n_xml_edit_page_title'] = 'Edit XML News Feed';
$lang['n_xml_edit_page_title_explain'] = 'Edit the XML News Feed.';
$lang['n_xml_edit_header'] = 'Edit existing XML News Feed';

//
// Delete
//
$lang['n_xml_news_delete'] = 'Delete XML News Feed.';
$lang['n_xml_news_delete_explain'] = 'Delete an XML News Feed.';
$lang['n_xml_confirm_delete_news'] = 'Are you sure you wish to delete this news feed?';

//
// General Settings used by Add/Edit Menu item
//
$lang['n_xml_news_item'] = 'News Item';
$lang['n_xml_news_item_display'] = 'Display This Item?';

$lang['n_xml_create_item'] = 'Create New Item';
$lang['n_xml_create_item_null'] = 'Can\'t create a News Ticker with no XML Feed URL or text to scroll.';

$lang['n_xml_no_feeds'] = 'There are no XML News Feeds.';
$lang['n_xml_news_updated'] = 'The ticker has been updated';
$lang['n_xml_click_return_newslist'] = 'Click %sHere%s to return to the News Ticker list';

$lang['n_xml_show'] = 'Show';
$lang['n_xml_title'] = 'Ticker Title';

?>