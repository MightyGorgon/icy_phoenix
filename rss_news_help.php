<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.' . $phpEx);

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

$template->set_filenames(array('body' => 'rss_news_help.tpl'));

// Ok, only process those Groups allowed within this forum
$nothing = true;

$gen_simple_header = true;
$page_title = $lang['Rss_news_help_title'];
$meta_description = '';
$meta_keywords = '';
include($phpbb_root_path . 'includes/page_header.' . $phpEx);
$server_url = ( substr($server_url, strlen($server_url) - 1, 1) == '/' ) ? substr($server_url, 0, strlen($server_url) - 1) : $server_url;

$template->assign_vars(array(
	'L_RSS_NEWS_HELP_TITLE'				=> $lang['Rss_news_help_title'],
	'L_RSS_NEWS_HELP_HEADER'			=> $lang['Rss_news_help_header'],
	'L_RSS_NEWS_HELP_EXPLAIN'			=> $lang['Rss_news_help_explain'],
	'L_RSS_NEWS_HELP_HEADER_2'		=> $lang['Rss_news_help_header_2'],
	'L_RSS_NEWS_HELP_EXPLAIN_2'		=> $lang['Rss_news_help_explain_2'],
	'L_RSS_NEWS_HELP_HEADER_3'		=> $lang['Rss_news_help_header_3'],
	'L_RSS_NEWS_HELP_EXPLAIN_3'		=> $lang['Rss_news_help_explain_3'],
	'L_RSS_NEWS_HELP_RIGHTS'			=> $lang['Rss_news_help_rights'],
	'U_RSS'												=> $server_url . '/rss.' . $phpEx,
	'U_RSS_NEWS'									=> $server_url . '/news_rss.' . $phpEx,
	'U_RSS_ATOM'									=> $server_url . '/rss.' . $phpEx . '?atom',
	'L_URL_RSS_EXPLAIN'						=> $lang['L_url_rss_explain'],
	'L_URL_RSS_NEWS_EXPLAIN'			=> $lang['L_url_rss_news_explain'],
	'L_URL_RSS_ATOM_EXPLAIN'			=> $lang['L_url_rss_atom_explain'],
	'L_CLOSE_WINDOW'							=> $lang['Close_window']
	)
);

if ($nothing)
{
	$template->assign_block_vars('switch_nothing', array());
}

$template->pparse('body');
include($phpbb_root_path . 'includes/page_tail.' . $phpEx);

?>