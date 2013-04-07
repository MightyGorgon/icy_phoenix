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

if(!function_exists('cms_block_global_header_simple_menu'))
{
	function cms_block_global_header_simple_menu()
	{
		global $db, $cache, $config, $template, $theme, $images, $table_prefix, $user, $lang, $block_id, $cms_config_vars;
		global $ip_cms;

		// Before starting with the loop... let's load the full menu links array!
		if (!function_exists('cms_menu_default_links_array'))
		{
			include_once(IP_ROOT_PATH . 'includes/functions_cms_menu.' . PHP_EXT);
		}
		$default_links_array = cms_menu_default_links_array();

		$template->_tpldata['header_row.'] = array();
		$template->_tpldata['header_desc.'] = array();

		$sql = "SELECT * FROM " . CMS_NAV_MENU_TABLE . "
						WHERE menu_id = '" . intval($cms_config_vars['md_menu_id'][$block_id]) . "'
							LIMIT 1";
		$result = $db->sql_query($sql, 0, 'cms_menu_', CMS_CACHE_FOLDER);

		//$row = $db->sql_fetchrow($result);
		while ($row = $db->sql_fetchrow($result))
		{
			break;
		}
		$db->sql_freeresult($result);

		if (($row['menu_name_lang'] != '') && isset($lang[$row['menu_name_lang']]))
		{
			$main_menu_name = $lang[$row['menu_name_lang']];
		}
		else
		{
			$main_menu_name = (($row['menu_name'] != '') ? $row['menu_name'] : $lang['quick_links']);
		}

		$sql = "SELECT * FROM " . CMS_NAV_MENU_TABLE . "
						WHERE menu_parent_id = '" . intval($cms_config_vars['md_menu_id'][$block_id]) . "'
						ORDER BY cat_parent_id ASC, menu_order ASC";
		$result = $db->sql_query($sql, 0, 'cms_menu_', CMS_CACHE_FOLDER);

		$menu_cat = array();
		$cat_item = array();
		$menu_item = array();
		$print_cat= array();
		$first_item = 0;
		$auth_levels = $ip_cms->cms_auth_view();

		while ($menu_item = $db->sql_fetchrow($result))
		{
			if ($menu_item['cat_id'] > 0)
			{
				$cat_item[$menu_item['cat_id']] = $menu_item;
			}
			if ($menu_item['cat_parent_id'] > 0)
			{
				$menu_cat[$menu_item['cat_parent_id']][$menu_item['menu_item_id']] = $menu_item;
			}
		}
		$db->sql_freeresult($result);

		foreach($cat_item as $cat_item_data)
		{
			if ($cat_item_data['menu_status'] == false)
			{
				$cat_allowed = false;
			}
			else
			{
				$auth_level_req = $cat_item_data['auth_view'];
				$cat_allowed = in_array($auth_level_req, $auth_levels) ? true : false;

				$cat_id = ($cat_item_data['cat_id']);

				foreach($menu_cat[$cat_id] as $menu_cat_item_data)
				{
					if ($menu_cat_item_data['menu_status'] == false)
					{
						$menu_allowed = false;
					}
					else
					{
						$auth_level_req = $menu_cat_item_data['auth_view'];
						$menu_allowed = in_array($auth_level_req, $auth_levels) ? true : false;
					}

					if (!empty($menu_allowed))
					{
						//echo($menu_cat_item_data['menu_name'] . '<br />');
						$menu_sep = '<img src="' . $images['nav_menu_sep'] . '" alt="" title="" style="vertical-align: middle;" />';
						if ($first_item == 0)
						{
							$menu_sep = '';
							$first_item++;
						}

						$menu_link = cms_menu_build_link($menu_cat_item_data, $block_id, false);

						$template->assign_block_vars('header_row', array(
							'MENU_SEP' => $menu_sep,
							'MENU_ITEM' => $menu_link['name'],
							'MENU_LINK' => $menu_link['link'],
							'MENU_URL' => $menu_link['url']
							)
						);
					}
				}
			}
		}
		if ($cms_config_vars['md_show_desc'][$block_id] == true)
		{
			$template->assign_block_vars('header_desc', array());
		}
		$template->assign_vars(array(
			'MAIN_MENU_ID' => $block_id,
			'MAIN_MENU_NAME' => $main_menu_name,
			)
		);
	}
}

cms_block_global_header_simple_menu();

?>