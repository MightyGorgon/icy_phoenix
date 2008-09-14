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

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

if(!function_exists(imp_xs_news_func))
{
	function imp_xs_news_func()
	{
		global $lang, $template, $board_config, $db;
		global $rss_channel, $currently_writing, $main, $item_counter;
		include(IP_ROOT_PATH . 'includes/xs_news.' . PHP_EXT);
	}
}

imp_xs_news_func();

?>