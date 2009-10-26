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

if(!function_exists('cms_block_search'))
{
	function cms_block_search()
	{
		global $config, $template, $lang, $block_id, $cms_config_vars;

		$template->assign_vars(array(
			'L_SEARCH2' => $lang['search2'],
			'L_SEARCH_AT' => $lang['search_at'],
			'L_ADVANCED_SEARCH' => $lang['Advanced_search'],
			'L_FORUM_OPTION' => (!empty($cms_config_vars['md_search_option_text'][$block_id])) ? $cms_config_vars['md_search_option_text'][$block_id] : $config ['sitename']
			)
		);
	}
}

cms_block_search();

?>