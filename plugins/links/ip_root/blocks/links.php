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

if(!function_exists('cms_block_links'))
{
	function cms_block_links()
	{
		global $db, $config, $template, $lang, $block_id, $cms_config_vars;
		global $links_config;

		$plugin_name = 'links';
		if (empty($config['plugins'][$plugin_name]['enabled']))
		{
			return;
		}

		$template->_tpldata['links_row.'] = array();
		$template->_tpldata['links_own1.'] = array();
		$template->_tpldata['links_own2.'] = array();
		$template->_tpldata['links_scroll.'] = array();
		$template->_tpldata['links_static.'] = array();

		if (empty($links_config))
		{
			include(IP_ROOT_PATH . PLUGINS_PATH . $config['plugins'][$plugin_name]['dir'] . 'common.' . PHP_EXT);
			$links_config = get_links_config(true);
		}

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

		$template->assign_block_vars($style_row, '');

		$template->assign_vars(array(
			'SITE_LOGO_WIDTH' => $links_config['width'],
			'SITE_LOGO_HEIGHT' => $links_config['height'],
			'U_SITE_LOGO' => $links_config['site_logo']
			)
		);

		$sql_extra = '';
		if (!empty($cms_config_vars['md_links_cat_id'][$block_id]))
		{
			$links_cats_array = explode(',', $cms_config_vars['md_links_cat_id'][$block_id]);
			$sql_extra = " AND " . $db->sql_in_set('link_category', $links_cats_array);
		}

		$sql = "SELECT link_id, link_title, link_logo_src
			FROM " . LINKS_TABLE . "
			WHERE link_active = 1
				AND link_logo_src <> ''
			" . $sql_extra . "
			ORDER BY RAND()
			LIMIT " . $links_config['display_logo_num'];

		// If failed just ignore
		$db->sql_return_on_error(true);
		$result = $db->sql_query($sql);
		$db->sql_return_on_error(false);
		if($result)
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
			$db->sql_freeresult($result);
		}
	}
}

cms_block_links();

?>