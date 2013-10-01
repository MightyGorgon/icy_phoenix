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
* OOHOO < webdev@phpbb-tw.net >
* Stefan2k1 and ddonker from www.portedmods.com
* CRLin from http://mail.dhjh.tcc.edu.tw/~gzqbyr/
*
*/

define('IN_ICYPHOENIX', true);

if(!empty($setmodules))
{
	if (empty($config['plugins']['links']['enabled']))
	{
		return;
	}

	$filename = IP_ROOT_PATH . PLUGINS_PATH . $config['plugins']['links']['dir'] . ADM . '/' . basename(__FILE__);
	$module['2100_Links']['110_Category'] = $filename;
	return;
}

// Load default Header
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../../../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require(IP_ROOT_PATH . 'adm/pagestart.' . PHP_EXT);
include(IP_ROOT_PATH . PLUGINS_PATH . $config['plugins']['links']['dir'] . 'common.' . PHP_EXT);

// This function will sort the order of all categories
if(!function_exists('links_reorder_cat'))
{
	function links_reorder_cat()
	{
		global $db;

		$sql = "SELECT cat_id, cat_order
				FROM ". LINK_CATEGORIES_TABLE ."
				WHERE cat_id <> 0
				ORDER BY cat_order ASC";
		$result = $db->sql_query($sql);

		$i = 10;

		while($row = $db->sql_fetchrow($result))
		{
			$sql = "UPDATE ". LINK_CATEGORIES_TABLE ."
					SET cat_order = $i
					WHERE cat_id = ". $row['cat_id'];
			$db->sql_query($sql);

			$i += 10;
		}
	}
}
$mode = request_var('mode', '');
$action = request_var('action', '');

if(empty($mode))
{
	if(empty($action))
	{
		$template->set_filenames(array('body' => LINKS_ADM_TPL_PATH . 'admin_link_cat_body.tpl'));

		$template->assign_vars(array(
			'L_LINK_CAT_TITLE' => $lang['Link_Categories_Title'],
			'L_LINK_CAT_EXPLAIN' => $lang['Link_Categories_Explain'],
			'L_LINK_ACTION' => append_sid('admin_links_cat.' . PHP_EXT),
			'L_MOVE_UP' => $lang['MOVE_UP'],
			'L_MOVE_DOWN' => $lang['MOVE_DOWN'],
			'L_EDIT' => $lang['Edit'],
			'L_DELETE' => $lang['Delete'],
			'S_MODE' => 'new',
			'L_CREATE_CATEGORY' => $lang['Create_category']
			)
		);

		$sql = "SELECT *
				FROM " . LINK_CATEGORIES_TABLE . "
				ORDER BY cat_order ASC";
		$result = $db->sql_query($sql);

		while ($row = $db->sql_fetchrow($result))
		{
			$catrow[] = $row;
		}

		for($i = 0; $i < sizeof($catrow); $i++)
		{
			$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];
			$template->assign_block_vars('catrow', array(
				'ROW_CLASS' => $row_class,
				'TITLE' => $catrow[$i]['cat_title'],
				'S_MOVE_UP' => append_sid('admin_links_cat.' . PHP_EXT . '?action=move&amp;move=-15&amp;cat_id=' . $catrow[$i]['cat_id']),
				'S_MOVE_DOWN' => append_sid('admin_links_cat.' . PHP_EXT . '?action=move&amp;move=15&amp;cat_id=' . $catrow[$i]['cat_id']),
				'S_EDIT_ACTION' => append_sid('admin_links_cat.' . PHP_EXT . '?action=edit&amp;cat_id=' . $catrow[$i]['cat_id']),
				'S_DELETE_ACTION' => append_sid('admin_links_cat.' . PHP_EXT . '?action=delete&amp;cat_id=' . $catrow[$i]['cat_id'])
				)
			);
		}

		$template->pparse('body');

		include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);
	}
	else
	{
		if($action == 'edit')
		{
			$cat_id = request_var('cat_id', 0);

			$sql = "SELECT *
					FROM " . LINK_CATEGORIES_TABLE . "
					WHERE cat_id = '" . $cat_id . "'";
			$result = $db->sql_query($sql);

			if($db->sql_numrows($result) == 0)
			{
				message_die(GENERAL_ERROR, 'The requested category doesn\'t exist');
			}
			$catrow = $db->sql_fetchrow($result);

			$template->set_filenames(array('body' => LINKS_ADM_TPL_PATH . 'admin_link_cat_new_body.tpl'));

			$template->assign_vars(array(
				'L_LINK_CAT_TITLE' => $lang['Link_Categories_Title'],
				'L_LINK_CAT_EXPLAIN' => $lang['Link_Categories_Explain'],
				'S_LINK_ACTION' => append_sid('admin_links_cat.' . PHP_EXT . '?cat_id=' . $cat_id),
				'L_CAT_TITLE' => $lang['Category_Title'],
				'L_DISABLED' => $lang['Disabled'],

				'S_CAT_TITLE' => $catrow['cat_title'],
				'S_MODE' => 'edit',
				'L_PANEL_TITLE' => $lang['Edit_Category']
				)
			);

			$template->pparse('body');

			include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);
		}
		elseif($action == 'delete')
		{
			$cat_id = request_var('cat_id', 0);

			$sql = "SELECT cat_id, cat_title, cat_order
					FROM " . LINK_CATEGORIES_TABLE . "
					ORDER BY cat_order ASC";
			$result = $db->sql_query($sql);

			$cat_found = false;
			while($row = $db->sql_fetchrow($result))
			{
				if($row['cat_id'] == $cat_id)
				{
					$thiscat = $row;
					$cat_found = true;
				}
				else
				{
					$catrow[] = $row;
				}
			}
			if($cat_found == false)
			{
				message_die(GENERAL_ERROR, 'The requested category doesn\'t exist');
			}

			$select_to = '<select name="target"><option value="0">'. $lang['Delete_all_links'] .'</option>';
			for ($i = 0; $i < sizeof($catrow); $i++)
			{
				$select_to .= '<option value="'. $catrow[$i]['cat_id'] .'">'. $catrow[$i]['cat_title'] .'</option>';
			}
			$select_to .= '</select>';

			$template->set_filenames(array('body' => LINKS_ADM_TPL_PATH . 'admin_link_cat_delete_body.tpl'));

			$template->assign_vars(array(
				'S_LINK_ACTION' => append_sid('admin_links_cat.' . PHP_EXT . '?cat_id=' . $cat_id),
				'L_CAT_DELETE' => $lang['Delete_Category'],
				'L_CAT_DELETE_EXPLAIN' => $lang['Delete_Category_Explain'],
				'L_CAT_TITLE' => $lang['Category_Title'],
				'S_CAT_TITLE' => $thiscat['cat_title'],
				'L_MOVE_CONTENTS' => $lang['Move_contents'],
				'L_MOVE_DELETE' => $lang['Move_and_Delete'],
				'S_SELECT_TO' => $select_to)
			);

			$template->pparse('body');

			include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);
		}
		elseif($action == 'move')
		{
			$cat_id = request_var('cat_id', 0);
			$move = request_var('move', 0);

			$sql = "UPDATE ". LINK_CATEGORIES_TABLE ."
					SET cat_order = cat_order + $move
					WHERE cat_id = '" . $cat_id . "'";
			$result = $db->sql_query($sql);
			links_reorder_cat();

			// Return a message...
			$message = $lang['Category_changed_order'] . '<br /><br />' . sprintf($lang['Click_return_link_category'], '<a href="' . append_sid('admin_links_cat.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

			message_die(GENERAL_MESSAGE, $message);
		}
	}
}
else
{
	if($mode == 'new')
	{
		if(!isset($_POST['cat_title']))
		{
			$template->set_filenames(array('body' => LINKS_ADM_TPL_PATH . 'admin_link_cat_new_body.tpl'));

			$template->assign_vars(array(
				'L_LINK_CAT_TITLE' => $lang['Link_Categories_Title'],
				'L_LINK_CAT_EXPLAIN' => $lang['Link_Categories_Explain'],
				'S_LINK_ACTION' => append_sid('admin_links_cat.' . PHP_EXT),
				'L_CAT_TITLE' => $lang['Category_Title'],
				'L_DISABLED' => $lang['Disabled'],
				'S_MODE' => 'new',
				'L_PANEL_TITLE' => $lang['Create_category']
				)
			);

			$template->pparse('body');

			include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);
		}
		else
		{
			// Get posting variables
			$cat_title = request_var('cat_title', '', true);

			// Get the last ordered category
			$sql = "SELECT cat_order FROM " . LINK_CATEGORIES_TABLE . "
					ORDER BY cat_order DESC
					LIMIT 1";
			$result = $db->sql_query($sql);
			$row = $db->sql_fetchrow($result);
			$last_order = $row['cat_order'];
			$cat_order = $last_order + 10;

			// Here we insert a new row into the db
			$sql = "INSERT INTO " . LINK_CATEGORIES_TABLE . " (cat_title, cat_order)
					VALUES ('" . $db->sql_escape($cat_title) . "', '" . $cat_order . "')";
			$result = $db->sql_query($sql);

			// Return a message...
			$message = $lang['New_category_created'] . '<br /><br />' . sprintf($lang['Click_return_link_category'], '<a href="' . append_sid('admin_links_cat.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

			message_die(GENERAL_MESSAGE, $message);
		}
	}
	elseif($mode == 'edit')
	{
		// Get posting variables
		$cat_id = request_var('cat_id', 0);
		$cat_title = request_var('cat_title', '', true);

		// Now we update this row
		$sql = "UPDATE " . LINK_CATEGORIES_TABLE . "
				SET cat_title = '" . $db->sql_escape($cat_title) . "'
				WHERE cat_id = '" . $cat_id . "'";
		$result = $db->sql_query($sql);

		// Return a message...
		$message = $lang['Category_updated'] . '<br /><br />' . sprintf($lang['Click_return_link_category'], '<a href="' . append_sid('admin_links_cat.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

		message_die(GENERAL_MESSAGE, $message);
	}
	elseif($mode == 'delete')
	{
		$cat_id = request_var('cat_id', 0);
		$target = request_var('target', 0);

		if($target == 0) // Delete All
		{
			// Get file information of all pics in this category
			$sql = "SELECT *
					FROM " . LINKS_TABLE . "
					WHERE link_category = '" . $cat_id . "'";
			$result = $db->sql_query($sql);

			$catrow = array();
			while($row = $db ->sql_fetchrow($result))
			{
				$catrow[] = $row;
				$cat_id_row[] = $row['link_id'];
			}

			if(sizeof($catrow) != 0) // if this category is not empty
			{
				// Delete entries in db
				$sql = "DELETE FROM " . LINKS_TABLE . " WHERE link_category = '" . $cat_id . "'";
				$result = $db->sql_query($sql);
			}

			// This category is now emptied, we can remove it!
			$sql = "DELETE FROM " . LINK_CATEGORIES_TABLE . " WHERE cat_id = '" . $cat_id . "'";
			$result = $db->sql_query($sql);

			// Re-order the rest of categories
			links_reorder_cat();

			// Return a message...
			$message = $lang['Category_deleted'] . '<br /><br />' . sprintf($lang['Click_return_link_category'], '<a href="' . append_sid('admin_links_cat.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

			message_die(GENERAL_MESSAGE, $message);
		}
		else // Move content...
		{
			$sql = "UPDATE " . LINKS_TABLE . "
					SET pic_cat_id = '" . $target . "'
					WHERE pic_cat_id = '" . $cat_id . "'";
			$result = $db->sql_query($sql);

			// This category is now emptied, we can remove it!
			$sql = "DELETE FROM " . LINK_CATEGORIES_TABLE . " WHERE cat_id = '" . $cat_id . "'";
			$result = $db->sql_query($sql);

			// Re-order the rest of categories
			links_reorder_cat();

			// Return a message...
			$message = $lang['Category_deleted'] . '<br /><br />' . sprintf($lang['Click_return_link_category'], '<a href="' . append_sid('admin_links_cat.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

			message_die(GENERAL_MESSAGE, $message);
		}
	}
}

/* Powered by Photo Link v2.x.x (c) 2002-2003 Smartor */

?>