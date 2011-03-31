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

if(!function_exists('cms_block_nav_links'))
{
	function cms_block_nav_links()
	{
		global $config, $template, $user, $lang, $cms_page, $cms_page_blocks;

		if(!defined('HAS_DIED') && !defined('IN_LOGIN') && ($cms_page['global_blocks'] || $cms_page_blocks))
		{
			$template->assign_var('SWITCH_CMS_SHOW_HIDE', true);
		}

	}
}

cms_block_nav_links();

?>