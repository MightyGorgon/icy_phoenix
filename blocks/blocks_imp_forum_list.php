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

if(!function_exists('imp_forum_list_block_func'))
{
	function imp_forum_list_block_func()
	{
		global $template, $cms_config_vars, $block_id, $table_prefix, $db, $lang, $board_config, $theme, $images, $userdata;

		$template->_tpldata['cat_row.'] = array();
		$template->_tpldata['forum_row.'] = array();

		$sql = "SELECT c.*
			FROM " . CATEGORIES_TABLE . " c
			ORDER BY c.cat_order";
		if(!($result = $db->sql_query($sql, false, 'forums_cats_', FORUMS_CACHE_FOLDER)))
		{
			message_die(GENERAL_ERROR, 'Could not query categories list', '', __LINE__, __FILE__, $sql);
		}

		unset($category_rows);
		$category_rows = array();
		while ($row = $db->sql_fetchrow($result))
		{
			$category_rows[$row['cat_id']] = $row;
		}
		$db->sql_freeresult($result);

		if(($total_categories = count($category_rows)))
		{
			if (!empty($cms_config_vars['md_list_forum_id'][$block_id]))
			{
				$sql_where = 'AND f.forum_id IN (' . $cms_config_vars['md_list_forum_id'][$block_id] . ')';
			}
			else
			{
				$sql_where = '';
			}
			$sql = "SELECT f.*
				FROM " . FORUMS_TABLE . " f
				WHERE f.auth_view = '0'
				" . $sql_where . "
				ORDER BY f.cat_id, f.forum_order";
			if (!($result = $db->sql_query($sql, false, 'forums_list_', FORUMS_CACHE_FOLDER)))
			{
				message_die(GENERAL_ERROR, 'Could not query forums information', '', __LINE__, __FILE__, $sql);
			}

			unset($forum_data);
			$forum_data = array();
			while($row = $db->sql_fetchrow($result))
			{
				$forum_data[] = $row;
			}
			$db->sql_freeresult($result);

			if (!($total_forums = count($forum_data)))
			{
				//message_die(GENERAL_MESSAGE, $lang['No_forums']);
			}

			$tmp_cat_id = '';

			foreach($forum_data as $menu_cat_item_data)
			{
				if (($menu_cat_item_data['cat_id'] == '') || ($menu_cat_item_data['cat_id'] != $tmp_cat_id))
				{
					$tmp_cat_id = $menu_cat_item_data['cat_id'];
					if (!empty($category_rows[$tmp_cat_id]['cat_id']) && ($menu_cat_item_data['main_type'] == 'c'))
					{
						$cat_icon = '<img src="' . $images['nav_menu_sep'] . '" alt="" title="" style="vertical-align:middle;" />&nbsp;&nbsp;';
						$template->assign_block_vars('cat_row', array(
							'CAT_ID' => $tmp_cat_id,
							'CAT_ITEM' => $category_rows[$tmp_cat_id]['cat_title'],
							'CAT_ICON' => $cat_icon,
							)
						);
					}
				}

				if (!empty($category_rows[$tmp_cat_id]['cat_id']) && ($menu_cat_item_data['main_type'] == 'c'))
				{
					$forum_name = stripslashes($menu_cat_item_data['forum_name']);
					$forum_link = append_sid(VIEWFORUM_MG . '?' . POST_FORUM_URL . '=' . $menu_cat_item_data['forum_id']);
					$forum_icon = '<img src="' . $images['nav_menu_sep'] . '" alt="" title="" style="vertical-align:middle;" />';

					$template->assign_block_vars('cat_row.forum_row', array(
						'FORUM_ITEM' => $forum_name,
						'FORUM_LINK' => $forum_link,
						'FORUM_ICON' => $forum_icon,
						)
					);
				}
			}
		}
		else
		{
			$template->assign_block_vars('no_forum', array(
				'NO_FORUM' => $lang['No_forums'],
				)
			);
		}
		$template->assign_vars(array(
			'TITLE' => $lang['Title_forum_list'],
			)
		);
	}
}

imp_forum_list_block_func();

?>