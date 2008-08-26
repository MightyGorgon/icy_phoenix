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
* masterdavid - Ronald John David
*
*/

if (!defined('IN_PHPBB'))
{
	die('Hacking attempt');
}

if(!function_exists(imp_xs_news_func))
{
	function imp_xs_news_func()
	{
		global $lang, $template, $board_config, $db, $phpbb_root_path, $phpEx;
		global $rss_channel, $currently_writing, $main, $item_counter;
		include($phpbb_root_path . 'includes/xs_news.' . $phpEx);
	}
}

imp_xs_news_func();

?>