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

if(!function_exists(imp_links_block_func))
{
	function imp_links_block_func()
	{
		global $template, $lang, $board_config, $db, $cms_config_vars, $block_id, $var_cache;

		$template->_tpldata['links_row.'] = array();
		//reset($template->_tpldata['links_row.']);
		$template->_tpldata['links_own1.'] = array();
		//reset($template->_tpldata['links_own1.']);
		$template->_tpldata['links_own2.'] = array();
		//reset($template->_tpldata['links_own2.']);
		$template->_tpldata['links_scroll.'] = array();
		//reset($template->_tpldata['links_scroll.']);
		$template->_tpldata['links_static.'] = array();
		//reset($template->_tpldata['links_static.']);

		//
		// Grab data
		//
		if($cms_config_vars['cache_enabled'])
		{
			$link_config = $var_cache->get('link_config', 86400, 'link');
		}
		if(!$link_config)
		{
			$sql = "SELECT *
					FROM ". LINK_CONFIG_TABLE;
			if(!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, "Could not query Link config information", "", __LINE__, __FILE__, $sql);
			}

			while($row = $db->sql_fetchrow($result))
			{
				$link_config[$row['config_name']] = $row['config_value'];
			}

			if($cms_config_vars['cache_enabled'])
			{
				$var_cache->save($link_config, 'link_config', 'link');
			}
		}
		$link_self_img = $link_config['site_logo'];
		$site_logo_height = $link_config['height'];
		$site_logo_width = $link_config['width'];
		$display_interval = $link_config['display_interval'];
		$display_logo_num = $link_config['display_logo_num'];

		$sql = "SELECT link_id, link_title, link_logo_src
			FROM " . LINKS_TABLE . "
			WHERE link_active = 1
			AND link_logo_src <> ''
			ORDER BY RAND()
			LIMIT ". $display_logo_num;

		if($cms_config_vars['md_links_style'][$block_id])
		{
			$style_row = 'links_scroll';
		}
		else
		{
			$style_row = 'links_static';
		}

		if($cms_config_vars['md_links_own1'][$block_id])
		{
			$template->assign_block_vars('links_own1','');
		}
		if($cms_config_vars['md_links_own2'][$block_id])
		{
			$template->assign_block_vars('links_own2','');
		}
		if($cms_config_vars['md_links_code'][$block_id])
		{
			$template->assign_block_vars('links_code','');
		}

		$template->assign_block_vars($style_row,"");

		$template->assign_vars(array(
			'SITE_LOGO_WIDTH' => $site_logo_width,
			'SITE_LOGO_HEIGHT' => $site_logo_height,
			'U_SITE_LOGO' => $link_config['site_logo']
			)
		);

		// If failed just ignore
		if($result = $db->sql_query($sql))
		{
			while($row = $db->sql_fetchrow($result))
			{
				//if (empty($row['link_logo_src'])) $row['link_logo_src'] = 'images/links/no_logo88a.gif';
				if ($row['link_logo_src'])
				{
					$template->assign_block_vars($style_row . '.links_row', array(
						'LINK_HREF' => 'links.' . PHP_EXT . '?action=go&amp;link_id=' . $row['link_id'],
						'LINK_LOGO_SRC' => $row['link_logo_src'],
						'LINK_TITLE' => $row['link_title']
						)
					);
				}
			}
		}
	}
}

imp_links_block_func();

?>