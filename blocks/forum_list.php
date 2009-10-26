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

if(!function_exists('cms_block_forum_list'))
{
	function cms_block_forum_list()
	{
		global $db, $cache, $config, $template, $theme, $images, $table_prefix, $userdata, $lang, $block_id, $cms_config_vars;

		$template->_tpldata['cat_row.'] = array();
		$template->_tpldata['forum_row.'] = array();

		unset($category_rows);
		$category_rows = array();
		$forum_types = array(FORUM_CAT);
		$forums_array = get_forums_ids($forum_types, false, true, true, true);
		foreach ($forums_array as $forum)
		{
			$category_rows[$forum['forum_id']] = $forum;
		}

		if(($total_categories = sizeof($category_rows)))
		{
			if (!empty($cms_config_vars['md_list_forum_id'][$block_id]))
			{
				$sql_where = 'AND f.forum_id IN (' . $cms_config_vars['md_list_forum_id'][$block_id] . ')';
			}
			else
			{
				$sql_where = '';
			}
			$sql = "SELECT f.* FROM " . FORUMS_TABLE . " f
				WHERE f.auth_view = " . AUTH_ALL . "
					AND f.forum_type <> " . FORUM_CAT . "
					" . $sql_where . "
				ORDER BY f.forum_order";
			$result = $db->sql_query($sql, 0, 'forums_list_', FORUMS_CACHE_FOLDER);

			unset($forum_data);
			$forum_data = array();
			while($row = $db->sql_fetchrow($result))
			{
				$forum_data[] = $row;
			}
			$db->sql_freeresult($result);

			if (!($total_forums = sizeof($forum_data)))
			{
				//message_die(GENERAL_MESSAGE, $lang['No_forums']);
			}

			$tmp_cat_id = '';

			foreach($forum_data as $menu_cat_item_data)
			{
				if (($menu_cat_item_data['parent_id'] == '') || ($menu_cat_item_data['parent_id'] != $tmp_cat_id))
				{
					$tmp_cat_id = $menu_cat_item_data['parent_id'];
					if (!empty($category_rows[$tmp_cat_id]['forum_id']) && ($menu_cat_item_data['main_type'] == 'c'))
					{
						$cat_icon = '<img src="' . $images['nav_menu_sep'] . '" alt="" title="" style="vertical-align: middle;" />&nbsp;&nbsp;';
						$template->assign_block_vars('cat_row', array(
							'CAT_ID' => $tmp_cat_id,
							'CAT_ITEM' => $category_rows[$tmp_cat_id]['forum_name'],
							'CAT_ICON' => $cat_icon,
							)
						);
					}
				}

				if (!empty($category_rows[$tmp_cat_id]['forum_id']) && ($menu_cat_item_data['main_type'] == 'c'))
				{
					$forum_name = stripslashes($menu_cat_item_data['forum_name']);
					$forum_link = append_sid(CMS_PAGE_VIEWFORUM . '?' . POST_FORUM_URL . '=' . $menu_cat_item_data['forum_id']);
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

cms_block_forum_list();

?>