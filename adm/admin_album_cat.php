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
* Smartor (smartor_xp@hotmail.com)
*
*/

define('IN_ICYPHOENIX', true);

if( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	$module['2200_Photo_Album']['120_Album_Categories'] = $filename;
	return;
}

// Let's set the root dir for phpBB
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('pagestart.' . PHP_EXT);
require_once(IP_ROOT_PATH . 'language/lang_' . $config['default_lang'] . '/lang_album_main.' . PHP_EXT);
require_once(IP_ROOT_PATH . 'language/lang_' . $config['default_lang'] . '/lang_album_admin.' . PHP_EXT);

require(ALBUM_MOD_PATH . 'album_common.' . PHP_EXT);
require_once(ALBUM_MOD_PATH . 'album_acp_functions.' . PHP_EXT);

$album_user_id = ALBUM_PUBLIC_GALLERY;

// Mighty Gorgon - Synchronize Pics Counter - BEGIN
if(isset($_POST['sync_pics_counter']))
{
	synchronize_all_cat_pics_counter();
}
// Mighty Gorgon - Synchronize Pics Counter - END

if(!isset($_POST['mode']))
{
	if(!isset($_GET['action']))
	{
		album_read_tree();

		/*
		if we still get layout issues then replace the template file with this
		ADM_TPL . 'album_cat_body_debug.tpl', BUT ONLY FOR DEBUGGING PURPOSE, and send me a screenshot of it
		then go back to this template file ADM_TPL . 'album_cat_body.tpl'.
		*/
		$template->set_filenames(array('body' => ADM_TPL . 'album_cat_body.tpl'));

		$template->assign_vars(array(
			'S_ALBUM_ACTION' => append_sid('admin_album_cat.' . PHP_EXT),
			'L_CREATE_CATEGORY' => $lang['Create_category'],
			'L_SYNC_PICS_COUNTER' => $lang['Cat_Pics_Synchronize'],
			'L_ALBUM_INDEX' => $lang['Album_Categories_Title']
			)
		);

		// get the values of level selected
		if (!empty($cat_id))
		{
			$parent = $cat_id;
		}

		if (!isset($album_cat_tree['keys'][$parent]))
		{
			$parent = ALBUM_ROOT_CATEGORY;
		}

		// display the tree
		album_display_admin_index($parent);

		$template->pparse('body');

		include('./page_footer_admin.' . PHP_EXT);
	}
	else
	{
		if( $_GET['action'] == 'edit' )
		{
			$cat_id = intval($_GET['cat_id']);

			$sql = "SELECT cat.*, cat2.cat_title AS cat_parent_title, cat2.cat_id AS cat_parent_id
					FROM ". ALBUM_CAT_TABLE ." AS cat LEFT OUTER JOIN ". ALBUM_CAT_TABLE ." AS cat2
					ON cat2.cat_id = cat.cat_parent WHERE cat.cat_id = '$cat_id'";
			$result = $db->sql_query($sql);

			if($db->sql_numrows($result) == 0)
			{
				message_die(GENERAL_ERROR, 'The requested category is not existed');
			}
			$catrow = $db->sql_fetchrow($result);

			album_read_tree();
			$s_album_cat_list = album_get_tree_option($catrow['cat_parent_id'], ALBUM_AUTH_VIEW, ALBUM_SELECTBOX_INCLUDE_ALL | ALBUM_SELECTBOX_INCLUDE_ROOT);

			$template->set_filenames(array('body' => ADM_TPL . 'album_cat_new_body.tpl'));

			$template->assign_block_vars('acp', array(
				'L_ALBUM_CAT_TITLE' => $lang['Album_Categories_Title'],
				'L_ALBUM_CAT_EXPLAIN' => $lang['Album_Categories_Explain']
				)
			);

			$template->assign_vars(array(
				'S_ALBUM_ACTION' => append_sid('admin_album_cat.' . PHP_EXT . '?cat_id=' . $cat_id),
				'L_CAT_TITLE' => $lang['Category_Title'],
				'L_CAT_DESC' => $lang['Category_Desc'],
				'L_CAT_PARENT_TITLE' => $lang['Parent_Category'],
				'L_CAT_PERMISSIONS' => $lang['Category_Permissions'],
				'L_VIEW_LEVEL' => $lang['View_level'],
				'L_UPLOAD_LEVEL' => $lang['Upload_level'],
				'L_RATE_LEVEL' => $lang['Rate_level'],
				'L_COMMENT_LEVEL' => $lang['Comment_level'],
				'L_EDIT_LEVEL' => $lang['Edit_level'],
				'L_DELETE_LEVEL' => $lang['Delete_level'],
				'L_PICS_APPROVAL' => $lang['Pics_Approval'],
				'L_GUEST' => $lang['Forum_ALL'],
				'L_REG' => $lang['Forum_REG'],
				'L_PRIVATE' => $lang['Forum_PRIVATE'],
				'L_MOD' => $lang['Forum_MOD'],
				'L_ADMIN' => $lang['Forum_ADMIN'],
				'L_DISABLED' => $lang['Disabled'],
				'L_WATERMARK' => $lang['Watermark'],
				'L_WATERMARK_EXPLAIN' => $lang['Watermark_explain'],

				'S_CAT_TITLE' => stripslashes($catrow['cat_title']),
				'S_CAT_DESC' => stripslashes($catrow['cat_desc']),
				'S_CAT_WM' => $catrow['cat_wm'],
				'S_CAT_PARENT_OPTIONS' => $s_album_cat_list,
				'VIEW_GUEST' => ($catrow['cat_view_level'] == ALBUM_GUEST) ? 'selected="selected"' : '',
				'VIEW_REG' => ($catrow['cat_view_level'] == ALBUM_USER) ? 'selected="selected"' : '',
				'VIEW_PRIVATE' => ($catrow['cat_view_level'] == ALBUM_PRIVATE) ? 'selected="selected"' : '',
				'VIEW_MOD' => ($catrow['cat_view_level'] == ALBUM_MOD) ? 'selected="selected"' : '',
				'VIEW_ADMIN' => ($catrow['cat_view_level'] == ALBUM_ADMIN) ? 'selected="selected"' : '',

				'UPLOAD_GUEST' => ($catrow['cat_upload_level'] == ALBUM_GUEST) ? 'selected="selected"' : '',
				'UPLOAD_REG' => ($catrow['cat_upload_level'] == ALBUM_USER) ? 'selected="selected"' : '',
				'UPLOAD_PRIVATE' => ($catrow['cat_upload_level'] == ALBUM_PRIVATE) ? 'selected="selected"' : '',
				'UPLOAD_MOD' => ($catrow['cat_upload_level'] == ALBUM_MOD) ? 'selected="selected"' : '',
				'UPLOAD_ADMIN' => ($catrow['cat_upload_level'] == ALBUM_ADMIN) ? 'selected="selected"' : '',

				'RATE_GUEST' => ($catrow['cat_rate_level'] == ALBUM_GUEST) ? 'selected="selected"' : '',
				'RATE_REG' => ($catrow['cat_rate_level'] == ALBUM_USER) ? 'selected="selected"' : '',
				'RATE_PRIVATE' => ($catrow['cat_rate_level'] == ALBUM_PRIVATE) ? 'selected="selected"' : '',
				'RATE_MOD' => ($catrow['cat_rate_level'] == ALBUM_MOD) ? 'selected="selected"' : '',
				'RATE_ADMIN' => ($catrow['cat_rate_level'] == ALBUM_ADMIN) ? 'selected="selected"' : '',

				'COMMENT_GUEST' => ($catrow['cat_comment_level'] == ALBUM_GUEST) ? 'selected="selected"' : '',
				'COMMENT_REG' => ($catrow['cat_comment_level'] == ALBUM_USER) ? 'selected="selected"' : '',
				'COMMENT_PRIVATE' => ($catrow['cat_comment_level'] == ALBUM_PRIVATE) ? 'selected="selected"' : '',
				'COMMENT_MOD' => ($catrow['cat_comment_level'] == ALBUM_MOD) ? 'selected="selected"' : '',
				'COMMENT_ADMIN' => ($catrow['cat_comment_level'] == ALBUM_ADMIN) ? 'selected="selected"' : '',

				'EDIT_REG' => ($catrow['cat_edit_level'] == ALBUM_USER) ? 'selected="selected"' : '',
				'EDIT_PRIVATE' => ($catrow['cat_edit_level'] == ALBUM_PRIVATE) ? 'selected="selected"' : '',
				'EDIT_MOD' => ($catrow['cat_edit_level'] == ALBUM_MOD) ? 'selected="selected"' : '',
				'EDIT_ADMIN' => ($catrow['cat_edit_level'] == ALBUM_ADMIN) ? 'selected="selected"' : '',

				'DELETE_REG' => ($catrow['cat_delete_level'] == ALBUM_USER) ? 'selected="selected"' : '',
				'DELETE_PRIVATE' => ($catrow['cat_delete_level'] == ALBUM_PRIVATE) ? 'selected="selected"' : '',
				'DELETE_MOD' => ($catrow['cat_delete_level'] == ALBUM_MOD) ? 'selected="selected"' : '',
				'DELETE_ADMIN' => ($catrow['cat_delete_level'] == ALBUM_ADMIN) ? 'selected="selected"' : '',

				'APPROVAL_DISABLED' => ($catrow['cat_approval'] == ALBUM_USER) ? 'selected="selected"' : '',
				'APPROVAL_MOD' => ($catrow['cat_approval'] == ALBUM_MOD) ? 'selected="selected"' : '',
				'APPROVAL_ADMIN' => ($catrow['cat_approval'] == ALBUM_ADMIN) ? 'selected="selected"' : '',

				'S_MODE' => 'edit',

				'S_GUEST' => ALBUM_GUEST,
				'S_USER' => ALBUM_USER,
				'S_PRIVATE' => ALBUM_PRIVATE,
				'S_MOD' => ALBUM_MOD,
				'S_ADMIN' => ALBUM_ADMIN,

				'L_PANEL_TITLE' => $lang['Edit_Category']
				)
			);

			$template->pparse('body');

			include('./page_footer_admin.' . PHP_EXT);
		}
		elseif( $_GET['action'] == 'delete' )
		{
			$cat_id = intval($_GET['cat_id']);

			$sql = "SELECT cat_id, cat_title, cat_order
					FROM ". ALBUM_CAT_TABLE ."
					ORDER BY cat_order ASC";
			$result = $db->sql_query($sql);

			$cat_found = false;
			while( $row = $db->sql_fetchrow($result) )
			{
				if( $row['cat_id'] == $cat_id )
				{
					$thiscat = $row;
					$cat_found = true;
				}
				else
				{
					$catrow[] = $row;
				}
			}
			if( $cat_found == false )
			{
				message_die(GENERAL_ERROR, 'The requested category is not existed');
			}

			album_read_tree();
			$select_to = '<select name="target">';
			$select_to .= album_get_tree_option($catrow['cat_parent_id'], ALBUM_AUTH_VIEW, ALBUM_SELECTBOX_ALL);
			$select_to .= '</select>';

			$template->set_filenames(array('body' => ADM_TPL . 'album_cat_delete_body.tpl'));

			$template->assign_vars(array(
				'S_ALBUM_ACTION' => append_sid('admin_album_cat.' . PHP_EXT . '?cat_id=' . $cat_id),
				'L_CAT_DELETE' => $lang['Delete_Category'],
				'L_CAT_DELETE_EXPLAIN' => $lang['Delete_Category_Explain'],
				'L_CAT_TITLE' => $lang['Category_Title'],
				'S_CAT_TITLE' => stripslashes($thiscat['cat_title']),
				'L_MOVE_CONTENTS' => $lang['Move_contents'],
				'L_MOVE_DELETE' => $lang['Move_and_Delete'],
				'S_SELECT_TO' => $select_to
				)
			);

			$template->pparse('body');

			include('./page_footer_admin.' . PHP_EXT);
		}
		elseif( $_GET['action'] == 'move' )
		{
			$cat_id = intval($_GET['cat_id']);
			$move = intval($_GET['move']);

			album_move_tree($cat_id, $move);

			// Return a message...
			showResultMessage($lang['Category_changed_order']);
		}
	}
}
else
{
	if( $_POST['mode'] == 'new' )
	{
		if ( is_array($_POST['addcategory']))
		{
			list($cat_id) = each($_POST['addcategory']);
			$cat_title = stripslashes($_POST['name'][$cat_id]);
			$cat_parent = $cat_id;
			$cat_id = -1;
		}

		if( !isset($_POST['cat_title']) )
		{
			album_read_tree();
			$s_album_cat_list = album_get_tree_option($cat_parent, ALBUM_AUTH_VIEW, ALBUM_SELECTBOX_INCLUDE_ALL);

			$template->set_filenames(array('body' => ADM_TPL . 'album_cat_new_body.tpl'));

			$template->assign_vars(array(
				'L_ALBUM_CAT_TITLE' => $lang['Album_Categories_Title'],
				'L_ALBUM_CAT_EXPLAIN' => $lang['Album_Categories_Explain'],
				'S_ALBUM_ACTION' => append_sid('admin_album_cat.' . PHP_EXT),

				'L_CAT_TITLE' => $lang['Category_Title'],
				'L_CAT_DESC' => $lang['Category_Desc'],
				'L_CAT_PARENT_TITLE' => $lang['Parent_Category'],
				'L_CAT_PERMISSIONS' => $lang['Category_Permissions'],

				'L_VIEW_LEVEL' => $lang['View_level'],
				'L_UPLOAD_LEVEL' => $lang['Upload_level'],
				'L_RATE_LEVEL' => $lang['Rate_level'],
				'L_COMMENT_LEVEL' => $lang['Comment_level'],
				'L_EDIT_LEVEL' => $lang['Edit_level'],
				'L_DELETE_LEVEL' => $lang['Delete_level'],
				'L_PICS_APPROVAL' => $lang['Pics_Approval'],
				'L_GUEST' => $lang['Forum_ALL'],
				'L_REG' => $lang['Forum_REG'],
				'L_PRIVATE' => $lang['Forum_PRIVATE'],
				'L_MOD' => $lang['Forum_MOD'],
				'L_ADMIN' => $lang['Forum_ADMIN'],
				'L_DISABLED' => $lang['Disabled'],
				'L_WATERMARK' => $lang['Watermark'],
				'L_WATERMARK_EXPLAIN' => $lang['Watermark_explain'],

				'S_CAT_TITLE' => stripslashes($cat_title),
				'S_CAT_PARENT_OPTIONS' => $s_album_cat_list,
				'VIEW_GUEST' => 'selected="selected"',
				'UPLOAD_REG' => 'selected="selected"',
				'RATE_REG' => 'selected="selected"',
				'COMMENT_REG' => 'selected="selected"',
				'EDIT_REG' => 'selected="selected"',
				'DELETE_MOD' => 'selected="selected"',
				'APPROVAL_DISABLED' => 'selected="selected"',

				'S_MODE' => 'new',

				'S_GUEST' => ALBUM_GUEST,
				'S_USER' => ALBUM_USER,
				'S_PRIVATE' => ALBUM_PRIVATE,
				'S_MOD' => ALBUM_MOD,
				'S_ADMIN' => ALBUM_ADMIN,

				'L_PANEL_TITLE' => $lang['Create_category']
				)
			);

			$template->pparse('body');

			include('./page_footer_admin.' . PHP_EXT);
		}
		else
		{
			if( !get_magic_quotes_gpc() )
			{
				$cat_title = addslashes(htmlspecialchars(trim($_POST['cat_title'])));
				$cat_desc = addslashes(trim($_POST['cat_desc']));
			}
			else
			{
				$cat_title = htmlspecialchars(trim($_POST['cat_title']));
				$cat_desc = trim($_POST['cat_desc']);
			}
			$cat_wm = trim($_POST['cat_wm']);
			$view_level = intval($_POST['cat_view_level']);
			$upload_level = intval($_POST['cat_upload_level']);
			$rate_level = intval($_POST['cat_rate_level']);
			$comment_level = intval($_POST['cat_comment_level']);
			$edit_level = intval($_POST['cat_edit_level']);
			$delete_level = intval($_POST['cat_delete_level']);
			$cat_approval = intval($_POST['cat_approval']);
			$cat_parent = ($_POST['cat_parent_id'] == ALBUM_ROOT_CATEGORY) ? 0 : intval($_POST['cat_parent_id']);
			$cat_parent = ($cat_parent < 0) ? 0 : $cat_parent;

			// Get the last ordered category
			$sql = "SELECT cat_order FROM ". ALBUM_CAT_TABLE ."
					ORDER BY cat_order DESC
					LIMIT 1";
			$result = $db->sql_query($sql);
			$row = $db->sql_fetchrow($result);
			$last_order = $row['cat_order'];
			$cat_order = $last_order + 10;

			// Here we insert a new row into the db
			$sql = "INSERT INTO ". ALBUM_CAT_TABLE ." (cat_title, cat_desc, cat_wm, cat_order, cat_view_level, cat_upload_level, cat_rate_level, cat_comment_level, cat_edit_level, cat_delete_level, cat_approval, cat_parent, cat_user_id)
					VALUES ('$cat_title', '$cat_desc', '$cat_wm', '$cat_order', '$view_level', '$upload_level', '$rate_level', '$comment_level', '$edit_level', '$delete_level', '$cat_approval', '$cat_parent' ,'" . ALBUM_PUBLIC_GALLERY ."')";
			$result = $db->sql_query($sql);

			// Return a message...
			showResultMessage($lang['New_category_created']);
		}
	}
	elseif( $_POST['mode'] == 'edit' )
	{
		// Get posting variables
		$cat_id = intval($_GET['cat_id']);
		if( !get_magic_quotes_gpc() )
		{
			$cat_title = addslashes(htmlspecialchars(trim($_POST['cat_title'])));
			$cat_desc = addslashes(trim($_POST['cat_desc']));
		}
		else
		{
			$cat_title = htmlspecialchars(trim($_POST['cat_title']));
			$cat_desc = trim($_POST['cat_desc']);
		}
		$cat_wm = trim($_POST['cat_wm']);
		$view_level = intval($_POST['cat_view_level']);
		$upload_level = intval($_POST['cat_upload_level']);
		$rate_level = intval($_POST['cat_rate_level']);
		$comment_level = intval($_POST['cat_comment_level']);
		$edit_level = intval($_POST['cat_edit_level']);
		$delete_level = intval($_POST['cat_delete_level']);
		$cat_approval = intval($_POST['cat_approval']);
		$cat_parent = ($_POST['cat_parent_id'] == ALBUM_ROOT_CATEGORY) ? 0 : intval($_POST['cat_parent_id']);
		$cat_parent = ($cat_parent < 0) ? 0 : $cat_parent;

		if ( ($cat_id == $cat_parent) && (album_get_personal_root_id($album_user_id) != $cat_id) )
		{
			showResultMessage($lang['No_Self_Refering_Cat']);
		}

		if ( (album_get_personal_root_id($album_user_id) == $cat_id) && ($cat_parent != 0) )
		{
			showResultMessage($lang['Can_Not_Change_Main_Parent']);
		}

		// Now we update this row
		$sql = "UPDATE ". ALBUM_CAT_TABLE ."
				SET cat_title = '$cat_title', cat_desc = '$cat_desc', cat_wm = '$cat_wm', cat_view_level = '$view_level', cat_upload_level = '$upload_level', cat_rate_level = '$rate_level', cat_comment_level = '$comment_level', cat_edit_level = '$edit_level', cat_delete_level = '$delete_level', cat_approval = '$cat_approval', cat_parent = '$cat_parent'
				WHERE cat_id = '$cat_id'";
		$result = $db->sql_query($sql);

		// Return a message...
		showResultMessage($lang['Category_updated']);
	}
	elseif( $_POST['mode'] == 'delete' )
	{
		$parent_cat_deleted = false;
		$parent_cat_id = 0;
		$parent_cat_title = "";

		$cat_id = intval($_GET['cat_id']);
		$target = intval($_POST['target']);

		if($target == ALBUM_JUMPBOX_DELETE) // Delete All
		{
			// check if the selected category is a parent to another category
			$sql = "SELECT cat_id FROM ". ALBUM_CAT_TABLE ." WHERE cat_parent = " . $cat_id .";";
			$result = $db->sql_query($sql);

			// the selected category is parent to another...proceed
			if ($db->sql_numrows($result) > 0)
			{
				$parent_cat_id = 0;
				if (isset($lang[$config['sitename']]))
				{
					$parent_cat_title = sprintf($lang['Forum_Index'], $lang[htmlspecialchars($config['sitename'])]);
				}
				else
				{
					$parent_cat_title = sprintf($lang['Forum_Index'], htmlspecialchars($config['sitename']));
				}

				//it is so set the indicator that we are deleting a parent category
				$parent_cat_deleted = true;

				//... then check if the selected category is a child to another category
				$sql = "SELECT cat.cat_id, parent.cat_title AS cat_parent_title, parent.cat_id AS cat_parent_id
						FROM ". ALBUM_CAT_TABLE ." AS cat, ". ALBUM_CAT_TABLE ." AS parent
						WHERE cat.cat_id = '$cat_id' AND parent.cat_id = cat.cat_parent";
				$result = $db->sql_query($sql);

				if ($db->sql_numrows($result) > 0)
				{
					while( $row = $db ->sql_fetchrow($result) )
					{
						// get the parent id for the selected id
						$parent_cat_id = $row['cat_parent_id'];
						$parent_cat_title = stripslashes($row['cat_parent_title']);

						// move the the selected category's child categories to the selected parent category (which can be nothing = cat_parent = 0)
						$sql = "UPDATE ". ALBUM_CAT_TABLE ." SET cat_parent = '" . $parent_cat_id . "' WHERE cat_parent = '" . $cat_id . "'";
						$result = $db->sql_query($sql);
					}
				}
			}

			// Get file information of all pics in this category
			$sql = "SELECT pic_id, pic_filename, pic_thumbnail, pic_cat_id
					FROM ". ALBUM_TABLE ."
					WHERE pic_cat_id = '$cat_id'";
			$result = $db->sql_query($sql);

			$picrow = array();
			while($row = $db ->sql_fetchrow($result))
			{
				$picrow[] = $row;
				$pic_id_row[] = $row['pic_id'];
			}

			if(sizeof($picrow) != 0) // if this category is not empty
			{
				// Delete all physical pic & cached thumbnail files
				for ($i = 0; $i < sizeof($picrow); $i++)
				{
					$pic_filename = $picrow[$i]['pic_filename'];

					if (USERS_SUBFOLDERS_ALBUM == true)
					{
						if (strpos($pic_filename, '/') !== false)
						{
							$pic_path[] = array();
							$pic_path = explode('/', $pic_filename);
							$pic_filename = $pic_path[sizeof($pic_path) - 1];
						}
					}

					$file_part = explode('.', strtolower($pic_filename));
					$pic_filetype = $file_part[sizeof($file_part) - 1];
					$pic_filename_only = substr($pic_filename, 0, strlen($pic_filename) - strlen($pic_filetype) - 1);
					$pic_base_path = IP_ROOT_PATH . ALBUM_UPLOAD_PATH;
					$pic_extra_path = '';
					$pic_new_filename = $pic_extra_path . $pic_filename;
					$pic_fullpath = $pic_base_path . $pic_new_filename;
					$pic_thumbnail = $picrow[$i]['pic_thumbnail'];
					$pic_thumbnail_fullpath = IP_ROOT_PATH . ALBUM_CACHE_PATH . $pic_thumbnail;

					if (USERS_SUBFOLDERS_ALBUM == true)
					{
						if (sizeof($pic_path) == 2)
						{
							$pic_extra_path = $pic_path[0] . '/';
							$pic_base_full_path = IP_ROOT_PATH . ALBUM_UPLOAD_PATH . $pic_extra_path;
							$pic_thumbnail_path = IP_ROOT_PATH . ALBUM_CACHE_PATH . $pic_extra_path;
							if (is_dir($pic_base_full_path))
							{
								$pic_new_filename = $pic_extra_path . $pic_filename;
								$pic_fullpath = $pic_base_path . $pic_new_filename;
								$pic_thumbnail_fullpath = $pic_thumbnail_path . $pic_thumbnail;
							}
							else
							{
								message_die(GENERAL_MESSAGE, $lang['Pic_not_exist']);
							}
						}
					}

					@unlink($pic_thumbnail_fullpath);
					@unlink(IP_ROOT_PATH . ALBUM_MED_CACHE_PATH . $pic_extra_path . $pic_thumbnail);
					@unlink(IP_ROOT_PATH . ALBUM_WM_CACHE_PATH . $pic_extra_path . $pic_thumbnail);
					@unlink($pic_fullpath);
				}

				$pic_id_sql = '(' . implode(',', $pic_id_row) . ')';

				// Delete all related ratings
				$sql = "DELETE FROM ". ALBUM_RATE_TABLE ."
						WHERE rate_pic_id IN ". $pic_id_sql;
				$result = $db->sql_query($sql);

				// Delete all related comments
				$sql = "DELETE FROM ". ALBUM_COMMENT_TABLE ."
						WHERE comment_pic_id IN ". $pic_id_sql;
				$result = $db->sql_query($sql);

				// Delete pic entries in db
				$sql = "DELETE FROM ". ALBUM_TABLE ."
						WHERE pic_cat_id = '$cat_id'";
				$result = $db->sql_query($sql);
			}

			// This category is now emptied, we can remove it!
			$sql = "DELETE FROM ". ALBUM_CAT_TABLE ."
					WHERE cat_id = '$cat_id'";
			$result = $db->sql_query($sql);

			// Re-order the rest of categories
			album_reorder_cat();

			// Return a message...
			$message = "";
			if ($parent_cat_deleted == true) {
				$message = sprintf($lang['Child_Category_Moved'], $parent_cat_title) . "<br />";
			}

			showResultMessage($message . $lang['Category_deleted']);
		}
		else // Move content...
		{
			$sql = "UPDATE ". ALBUM_TABLE ."
					SET pic_cat_id = '$target'
					WHERE pic_cat_id = '$cat_id'";
			$result = $db->sql_query($sql);

			// This category is now emptied, we can remove it!
			$sql = "DELETE FROM ". ALBUM_CAT_TABLE ."
					WHERE cat_id = '$cat_id'";
			$result = $db->sql_query($sql);

			// Re-order the rest of categories
			album_reorder_cat();

			// Return a message...
			showResultMessage($lang['Category_deleted']);
		}
	}
}

?>