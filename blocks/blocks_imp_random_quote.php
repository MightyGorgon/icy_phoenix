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

if(!function_exists(imp_random_quote_func))
{
	function imp_random_quote_func()
	{
		global $lang, $template, $phpbb_root_path, $phpEx;
		include_once($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_randomquote.' . $phpEx);
		$randomquote_phrase = $randomquote[rand(0, count($randomquote) - 1)];
		$template->assign_vars(array(
			'RANDOM_QUOTE' => $randomquote_phrase,
			)
		);
	}
}

imp_random_quote_func();

?>