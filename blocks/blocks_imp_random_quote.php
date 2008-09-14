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

if(!function_exists(imp_random_quote_func))
{
	function imp_random_quote_func()
	{
		global $lang, $template;
		include_once(IP_ROOT_PATH . 'language/lang_' . $board_config['default_lang'] . '/lang_randomquote.' . PHP_EXT);
		$randomquote_phrase = $randomquote[rand(0, count($randomquote) - 1)];
		$template->assign_vars(array(
			'RANDOM_QUOTE' => $randomquote_phrase,
			)
		);
	}
}

imp_random_quote_func();

?>