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

if(!function_exists('cms_block_random_quote'))
{
	function cms_block_random_quote()
	{
		global $config, $template;
		include_once(IP_ROOT_PATH . 'language/lang_' . $config['default_lang'] . '/lang_randomquote.' . PHP_EXT);
		$randomquote_phrase = $randomquote[rand(0, sizeof($randomquote) - 1)];
		$template->assign_vars(array(
			'CMS_RANDOM_QUOTE' => $randomquote_phrase,
			)
		);
	}
}

cms_block_random_quote();

?>