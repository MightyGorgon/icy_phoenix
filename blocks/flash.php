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

if(!function_exists('cms_block_flash'))
{
	function cms_block_flash()
	{
		global $db, $cache, $config, $template, $theme, $images, $userdata, $lang, $table_prefix, $block_id, $cms_config_vars, $cms_config_layouts, $cms_page;

		if (empty($cms_config_layouts[$cms_page_id_tmp]['md_flash_src']))
		{
			return;
		}

		$flash_src = $cms_config_layouts[$cms_page_id_tmp]['md_flash_src'];
		$flash_id = str_replace('.', '_', ip_clean_string($cms_config_layouts[$cms_page_id_tmp]['md_flash_id'], 'utf-8', false, true));
		$flash_w = (int) $cms_config_layouts[$cms_page_id_tmp]['md_flash_w'];
		$flash_h = (int) $cms_config_layouts[$cms_page_id_tmp]['md_flash_h'];

		if (($flash_w <= 0) || ($flash_w >= 1000))
		{
			$flash_w = 400;
		}

		if (($flash_h <= 0) || ($flash_h >= 1000))
		{
			$flash_h = 300;
		}

		$template->assign_vars(array(
			'FLASH_SRC' => $flash_src,
			'FLASH_ID' => $flash_id,
			'FLASH_W' => $flash_w,
			'FLASH_H' => $flash_h,
			)
		);

	}
}

cms_block_flash();

?>