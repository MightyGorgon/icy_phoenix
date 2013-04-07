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

if(!function_exists('cms_block_jumpbox'))
{
	function cms_block_jumpbox()
	{
		global $db, $cache, $config, $template, $theme, $images, $user, $lang, $table_prefix, $block_id, $cms_config_vars;
		global $ip_cms;

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
		$print_cat = array();
		$jumpbox_id = 'jumpbox' . $block_id;
		$jumpbox = '<select name="' . $jumpbox_id . '">';
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

				if (($cat_item_data['menu_name_lang'] != '') && isset($lang['menu_item'][$cat_item_data['menu_name_lang']]))
				{
					$cat_name = $lang['menu_item'][$cat_item_data['menu_name_lang']];
				}
				else
				{
					$cat_name = (($cat_item_data['menu_name'] != '') ? stripslashes($cat_item_data['menu_name']) : 'cat_item' . $cat_item_data['cat_id']);
				}

				if ($cat_item_data['menu_link'] != '')
				{
					$cat_link = append_sid($cat_item_data['menu_link']);
					if ($cat_item_data['menu_link_external'] == true)
					{
						$cat_link .= '" target="_blank';
					}
				}
				$jumpbox .= '<option value="-1">' . $cat_name . '</option>';

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
						$jumpbox .= '<option value="' . $menu_link . '">|--' . $menu_name . '</option>';
					}
				}
			}
		}
		$jumpbox .= '</select>';
		switch ($cms_config_vars['md_jumpbox_align'][$block_id])
		{
			case '-1':
				$temp_align = 'left';
				break;
			case '0':
				$temp_align = 'center';
				break;
			case '1':
				$temp_align = 'right';
				break;
		}

		$template->assign_vars(array(
			'JUMPBOX_ID' => $jumpbox_id,
			'MAIN_MENU_NAME' => $main_menu_name,
			'MENU_JUMPBOX_ALIGN' => $temp_align,
			'MENU_JUMPBOX_GO' => $lang['Go'],
			'MENU_JUMPBOX' => $jumpbox
			)
		);
	}
}

cms_block_jumpbox();

?>