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

if(!function_exists('cms_block_dyn_menu'))
{
	function cms_block_dyn_menu()
	{
		global $db, $cache, $config, $template, $theme, $images, $user, $lang, $table_prefix, $block_id, $cms_config_vars;

		include_once(IP_ROOT_PATH . 'includes/functions_cms_menu.' . PHP_EXT);
		// Before starting with the loop... let's load the full menu links array!
		$default_links_array = default_links_array();

		$template->_tpldata['cat_row.'] = array();
		$template->_tpldata['menu_row.'] = array();
		$template->_tpldata['show_hide.'] = array();

		setup_extra_lang(array('lang_dyn_menu'));

		$sql = "SELECT * FROM " . CMS_NAV_MENU_TABLE . "
						WHERE menu_id = '" . intval($cms_config_vars['md_menu_id'][$block_id]) . "'
							LIMIT 1";
		$result = $db->sql_query($sql, 0, 'cms_menu_', CMS_CACHE_FOLDER);

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
			}

			if ($cat_allowed)
			{
				$cat_id = ($cat_item_data['cat_id']);
				if (($cat_item_data['menu_name_lang'] != '') && isset($lang['menu_item'][$cat_item_data['menu_name_lang']]))
				{
					$cat_name = $lang['menu_item'][$cat_item_data['menu_name_lang']];
				}
				else
				{
					$cat_name = (($cat_item_data['menu_name'] != '') ? htmlspecialchars(stripslashes($cat_item_data['menu_name'])) : 'cat_item' . $cat_item_data['cat_id']);
				}
				$cat_icon = (($cat_item_data['menu_icon'] != '') ? '<img src="' . $cat_item_data['menu_icon'] . '" alt="" title="' . $cat_name . '" style="vertical-align: middle;" />&nbsp;&nbsp;' : '<img src="' . $images['nav_menu_sep'] . '" alt="" title="" style="vertical-align: middle;" />&nbsp;&nbsp;');
				if ($cat_item_data['menu_link'] != '')
				{
					$cat_link = append_sid($cat_item_data['menu_link']);
					if ($cat_item_data['menu_link_external'] == true)
					{
						$cat_link .= '" target="_blank';
					}
				}

				$template->assign_block_vars('cat_row', array(
					'CAT_ID' => $cat_item_data['menu_parent_id'] . '_' . $cat_item_data['cat_id'],
					'CAT_ITEM' => $cat_name,
					'CAT_ICON' => $cat_icon,
					)
				);

				if (!empty($menu_cat[$cat_id]))
				{
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

						if ($menu_allowed)
						{
							$menu_icon = (($menu_cat_item_data['menu_icon'] != '') ? '<img src="' . $menu_cat_item_data['menu_icon'] . '" alt="" title="" style="vertical-align: middle;" />&nbsp;' : '<img src="' . $images['nav_menu_sep'] . '" alt="" title="" style="vertical-align: middle;" />&nbsp;');
							if ($menu_cat_item_data['menu_default'] == '0')
							{
								if (($menu_cat_item_data['menu_name_lang'] != '') && isset($lang['menu_item'][$menu_cat_item_data['menu_name_lang']]))
								{
									$menu_name = $lang['menu_item'][$menu_cat_item_data['menu_name_lang']];
								}
								else
								{
									$menu_name = (($menu_cat_item_data['menu_name'] != '') ? htmlspecialchars(stripslashes($menu_cat_item_data['menu_name'])) : 'cat_item' . $menu_cat_item_data['cat_id']);
								}
								if ($menu_cat_item_data['menu_link_external'] == true)
								{
									$menu_link = htmlspecialchars($menu_cat_item_data['menu_link']);
									$menu_link .= '" target="_blank';
								}
								else
								{
									$menu_link = append_sid(htmlspecialchars($menu_cat_item_data['menu_link']));
								}
								$menu_url = '<div class="genmed" align="left"><a href="' . $menu_link . '">' . $menu_icon . $menu_name . '</a></div>';
							}
							else
							{
								$menu_url_temp = build_complete_url($menu_cat_item_data['menu_default'], $block_id, $menu_cat_item_data['menu_link'], $menu_icon);
								$menu_url = (($menu_url_temp != '') ? '<div class="genmed" align="left">' . $menu_url_temp . '</div>' : '');
							}

							$template->assign_block_vars('cat_row.menu_row', array(
								'MENU_ITEM' => $menu_name,
								'MENU_ICON' => $menu_icon,
								'MENU_URL' => $menu_url,
								)
							);
						}
					}
				}
			}
		}

		$show_hide_switch = (empty($user->data['is_bot']) && !empty($cms_config_vars['md_menu_show_hide'][$block_id])) ? true : false;

		if (!empty($show_hide_switch))
		{
			$template->assign_block_vars('show_hide_switch', array(
				)
			);
		}
		$template->assign_vars(array(
			'MAIN_MENU_ID' => $block_id,
			'MAIN_MENU_NAME' => $main_menu_name,
			)
		);
	}
}

cms_block_dyn_menu();

?>