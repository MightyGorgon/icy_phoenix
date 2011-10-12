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

if(!function_exists('cms_block_global_header_simple'))
{
	function cms_block_global_header_simple()
	{
		global $db, $cache, $config, $template, $theme, $images, $table_prefix, $user, $lang, $block_id, $cms_config_vars;

		include_once(IP_ROOT_PATH . 'includes/functions_cms_menu.' . PHP_EXT);
		// Before starting with the loop... let's load the full menu links array!
		$default_links_array = default_links_array();

		$template->_tpldata['header_row.'] = array();
		$template->_tpldata['header_desc.'] = array();

		setup_extra_lang(array('lang_dyn_menu'));

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
				$cat_allowed = true;
				$auth_level_req = $cat_item_data['auth_view'];
				switch($auth_level_req)
				{
					case '0':
						$cat_allowed = true;
						break;
					case '1':
						$cat_allowed = ($user->data['session_logged_in'] ? false : true);
						break;
					case '2':
						$cat_allowed = ($user->data['session_logged_in'] ? true : false);
						break;
					case '3':
						$cat_allowed = ((($user->data['user_level'] == MOD) || ($user->data['user_level'] == ADMIN)) ? true : false);
						break;
					case '4':
						$cat_allowed = (($user->data['user_level'] == ADMIN) ? true : false);
						break;
					default:
						$cat_allowed = true;
						break;
				}

				$cat_id = ($cat_item_data['cat_id']);

				foreach($menu_cat[$cat_id] as $menu_cat_item_data)
				{
					if ($menu_cat_item_data['menu_status'] == false)
					{
						$menu_allowed = false;
					}
					else
					{
						$menu_allowed = true;
						$auth_level_req = $menu_cat_item_data['auth_view'];
						switch($auth_level_req)
						{
							case '0':
								$menu_allowed = true;
								break;
							case '1':
								$menu_allowed = ($user->data['session_logged_in'] ? false : true);
								break;
							case '2':
								$menu_allowed = ($user->data['session_logged_in'] ? true : false);
								break;
							case '3':
								$menu_allowed = ((($user->data['user_level'] == MOD) || ($user->data['user_level'] == ADMIN)) ? true : false);
								break;
							case '4':
								$menu_allowed = (($user->data['user_level'] == ADMIN) ? true : false);
								break;
							default:
								$menu_allowed = true;
								break;
						}
					}

					if ($menu_allowed == true)
					{
						//echo($menu_cat_item_data['menu_name'] . '<br />');
						if ($first_item == 0)
						{
							$menu_sep = '';
							$first_item++;
						}
						else
						{
							$menu_sep = ($cms_config_vars['md_show_sep_icon'][$block_id] == true) ? '<img src="' . $images['nav_menu_sep'] . '" alt="" title="" style="vertical-align:middle;" />' : '';
						}
						if ($cms_config_vars['md_show_links_icon'][$block_id] == true)
						{
							$menu_icon = (($menu_cat_item_data['menu_icon'] != '') ? '<img src="' . $menu_cat_item_data['menu_icon'] . '" alt="" title="" style="vertical-align:middle;" />&nbsp;' : '<img src="' . $images['nav_menu_sep'] . '" alt="" title="" style="vertical-align:middle;" />&nbsp;');
						}
						if ($menu_cat_item_data['menu_default'] == 0)
						{
							if (($menu_cat_item_data['menu_name_lang'] != '') && isset($lang['menu_item'][$menu_cat_item_data['menu_name_lang']]))
							{
								$menu_name = $lang['menu_item'][$menu_cat_item_data['menu_name_lang']];
							}
							else
							{
								$menu_name = (($menu_cat_item_data['menu_name'] != '') ? stripslashes($menu_cat_item_data['menu_name']) : 'cat_item' . $menu_cat_item_data['cat_id']);
							}
							if ($menu_cat_item_data['menu_link_external'] == true)
							{
								$menu_link = $menu_cat_item_data['menu_link'];
								$menu_link .= '" target="_blank';
							}
							else
							{
								$menu_link = append_sid($menu_cat_item_data['menu_link']);
							}
							$menu_url = '<a href="' . $menu_link . '">' . $menu_icon . $menu_name . '</a>';
						}
						else
						{
							$menu_url = build_complete_url($menu_cat_item_data['menu_default'], $block_id, $menu_cat_item_data['menu_link'], $menu_icon);
						}
						if ($cms_config_vars['md_show_desc'][$block_id] == true)
						{
							$menu_desc = $menu_cat_item_data['menu_desc'];
						}
						$template->assign_block_vars('header_row', array(
							'MENU_SEP' => $menu_sep,
							'MENU_ICON' => $menu_icon,
							'MENU_URL' => $menu_url,
							'MENU_DESC' => $menu_desc,
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

cms_block_global_header_simple();

?>