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

if(!function_exists(imp_index_block_func))
{
	function imp_index_block_func()
	{
		global $template, $cms_config_vars, $block_id, $table_prefix, $db, $lang, $board_config, $theme, $images, $userdata;
		include_once(IP_ROOT_PATH . 'includes/functions_cms_menu.' . PHP_EXT);

		$template->_tpldata['index_row.'] = array();
		//reset($template->_tpldata['index_row.']);
		$template->_tpldata['menu_row.'] = array();
		//reset($template->_tpldata['menu_row.']);
		$template->_tpldata['index_col.'] = array();
		//reset($template->_tpldata['index_line.']);
		$template->_tpldata['index_items.'] = array();
		//reset($template->_tpldata['index_line.']);

		include_once(IP_ROOT_PATH . 'language/lang_' . $board_config['default_lang'] . '/lang_dyn_menu.' . PHP_EXT);

		$sql = "SELECT * FROM " . CMS_NAV_MENU_TABLE . "
						WHERE menu_id = '" . intval($cms_config_vars['md_menu_id'][$block_id]) . "'
							LIMIT 1";
		if (!($result = $db->sql_query($sql, false, 'dyn_menu_')))
		{
			message_die(GENERAL_ERROR, 'Could not query dynamic menu table');
		}
		//$row = $db->sql_fetchrow($result);
		while ($row = $db->sql_fetchrow($result))
		{
			break;
		}

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
		if (!($result = $db->sql_query($sql, false, 'dyn_menu_')))
		{
			message_die(GENERAL_ERROR, 'Could not query dynamic menu table');
		}

		$menu_cat = array();
		$cat_item = array();
		$menu_item = array();
		$print_cat= array();

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

		$count = 0;
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
						$cat_allowed = ($userdata['session_logged_in'] ? false : true);
						break;
					case '2':
						$cat_allowed = ($userdata['session_logged_in'] ? true : false);
						break;
					case '3':
						$cat_allowed = ((($userdata['user_level'] == MOD) || ($userdata['user_level'] == ADMIN)) ? true : false);
						break;
					case '4':
						$cat_allowed = (($userdata['user_level'] == ADMIN)? true : false);
						break;
					default:
						$cat_allowed = true;
						break;
				}
			}

			if ($cat_allowed == true)
			{
				$print_cat[$count] = $cat_item_data;
				$count++;
			}
		}
		$icons = count($print_cat);
		$row_width = 100 / $cms_config_vars['md_col'][$block_id];
		for ($i = 0; $i < count($cat_item); $i += $cms_config_vars['md_col'][$block_id])
		{
			if ($icons > 0)
			{
				$template->assign_block_vars('index_row', array());
			}
			for ($j = $i; $j < ($i + $cms_config_vars['md_col'][$block_id]); $j++)
			{
				if($j >= $icons)
				{
				 break;
				}
				$cat_id = ($print_cat[$j]['cat_id']);
				$row_class = ($cms_config_vars['md_show_background'][$block_id] == true) ? 'row1' : '';
				$row_class_h = ($cms_config_vars['md_show_background'][$block_id] == true) ? 'row1h' : '';
				if ($cms_config_vars['md_show_title'][$block_id] == true)
				{
					if (($print_cat[$j]['menu_name_lang'] != '') && isset($lang['menu_item'][$print_cat[$j]['menu_name_lang']]))
					{
						$cat_name = $lang['menu_item'][$print_cat[$j]['menu_name_lang']];
					}
					else
					{
						$cat_name = (($print_cat[$j]['menu_name'] != '') ? stripslashes($print_cat[$j]['menu_name']) : 'cat_item' . $print_cat[$j]['cat_id']);
					}
					$template->assign_block_vars('index_row.index_items', array(
						'ROW_CLASS' => $row_class,
						'CAT_ITEM' => $cat_name,
						'ROW_WIDTH' => $row_width,
						)
					);
				}
				$cat_icon = (($print_cat[$j]['menu_icon'] != '') ? '<img src="' . $print_cat[$j]['menu_icon'] . '" alt="" title="' . $cat_name . '" style="vertical-align:middle;" />&nbsp;&nbsp;' : '<img src="' . $images['nav_menu_sep'] . '" alt="" title="" style="vertical-align:middle;" />&nbsp;&nbsp;');
				if ($print_cat[$j]['menu_link'] != '')
				{
					$cat_link = append_sid($print_cat[$j]['menu_link']);
					if ($print_cat[$j]['menu_link_external'] == true)
					{
						$cat_link .= '" target="_blank';
					}
				}

				$template->assign_block_vars('index_row.index_col', array(
					'ROW_CLASS' => $row_class_h,
					'CAT_ICON' => $cat_icon,
					)
				);

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
								$menu_allowed = ($userdata['session_logged_in'] ? false : true);
								break;
							case '2':
								$menu_allowed = ($userdata['session_logged_in'] ? true : false);
								break;
							case '3':
								$menu_allowed = ((($userdata['user_level'] == MOD) || ($userdata['user_level'] == ADMIN)) ? true : false);
								break;
							case '4':
								$menu_allowed = (($userdata['user_level'] == ADMIN)? true : false);
								break;
							default:
								$menu_allowed = true;
								break;
						}
					}

					if ($menu_allowed == true)
					{
						//echo($menu_cat_item_data['menu_name'] . '<br />');
						if ($cms_config_vars['md_show_links_icon'][$block_id] == true)
						{
							$menu_icon = (($menu_cat_item_data['menu_icon'] != '') ? '<img src="' . $menu_cat_item_data['menu_icon'] . '" alt="" title="' . $menu_name . '" style="vertical-align:middle;" />' : '<img src="' . $images['nav_menu_sep'] . '" alt="" title="" style="vertical-align:middle;" />');
						}
						if ($menu_cat_item_data['menu_default'] == 0)
						{
							if (($menu_cat_item_data['menu_name_lang'] != '') && isset($lang['menu_item'][$menu_cat_item_data['menu_name_lang']]))
							{
								$menu_name = $lang['menu_item'][$menu_cat_item_data['menu_name_lang']];
							}
							else
							{
								$menu_name = (($menu_cat_item_data['menu_name'] != '') ? stripslashes($menu_cat_item_data['menu_name']) : 'cat_item' . $menu_cat_item_data['cat_id']) ;
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
						$template->assign_block_vars('index_row.index_col.menu_row', array(
							'MENU_ITEM' => $menu_name,
							'MENU_URL' => $menu_url,
							'MENU_DESC' => $menu_desc,
						)
						);
					}
				}
			}
		}

		$template->assign_vars(array(
			'MAIN_MENU_ID' => $block_id,
			'MAIN_MENU_NAME' => $main_menu_name,
			)
		);
	}
}

imp_index_block_func();

?>