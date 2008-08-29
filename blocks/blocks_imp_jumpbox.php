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

if (!defined('IN_PHPBB'))
{
	die('Hacking attempt');
}

if(!function_exists(imp_jumpbox_block_func))
{
	function imp_jumpbox_block_func()
	{
		global $phpbb_root_path, $template, $cms_config_vars, $block_id, $table_prefix, $phpEx, $db, $lang, $board_config, $theme, $images, $userdata;

		include_once($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_dyn_menu.' . $phpEx);

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
		$jumpbox_id = 'jumpbox' . $block_id;
		$jumpbox = '<select name="' . $jumpbox_id . '">';

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
						$cat_allowed = (($userdata['user_level'] == ADMIN) ? true : false);
						break;
					default:
						$cat_allowed = true;
						break;
				}

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
								$menu_allowed = (($userdata['user_level'] == ADMIN) ? true : false);
								break;
							default:
								$menu_allowed = true;
								break;
						}
					}

					if ($menu_allowed == true)
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

imp_jumpbox_block_func();

?>