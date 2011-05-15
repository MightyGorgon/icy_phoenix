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
* IdleVoid (idlevoid@slater.dk)
*
*/

define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);

// Start session management
$user->session_begin();
//$auth->acl($user->data);
$user->setup();
// End session management

// Get general album information
include(ALBUM_MOD_PATH . 'album_common.' . PHP_EXT);

setup_extra_lang(array('lang_album_main', 'lang_album_admin', 'lang_admin'));

$meta_content['page_title'] = $lang['Personal_Cat_Admin'];
$meta_content['description'] = '';
$meta_content['keywords'] = '';

// ------------------------------------------------------------------------
// Get $album_user_id
// ------------------------------------------------------------------------
if(isset($_POST['user_id']) || isset($_GET['user_id']))
{
	$album_user_id = request_var('user_id', 0);
}

// ------------------------------------------------------------------------
// Get $cat_id
// ------------------------------------------------------------------------
if(isset($_POST['cat_id']) || isset($_GET['cat_id']))
{
	$cat_id = request_var('cat_id', 0);
}

// ------------------------------------------------------------------------
// Check if user exists and get his/her name
// ------------------------------------------------------------------------
$username = album_get_user_name($album_user_id);
if (empty($username))
{
	if (!defined('STATUS_404')) define('STATUS_404', true);
	message_die(GENERAL_MESSAGE, 'NO_USER');
}

// ------------------------------------------------------------------------
// Check the actual personal gallery exists, if not there is nothing to manage
// ------------------------------------------------------------------------
if (album_get_personal_root_id($album_user_id) == ALBUM_ROOT_CATEGORY)
{
	if(!isset($_POST['submit']))
	{
		album_init_personal_gallery($album_user_id);
	}
	else
	{
		album_create_personal_gallery($album_user_id, $album_config['personal_gallery_view'], ALBUM_PRIVATE);
		album_read_tree($album_user_id);
	}
}
else
{
	album_read_tree($album_user_id);
}

// ------------------------------------------------------------------------
// Only the owner of the personal gallery AND the admin of the personal
// gallery can manage the categories
// TODO : should the moderator also be allowed ?
// ------------------------------------------------------------------------
if((isset($_GET['action'])) && ($_GET['action'] == 'create'))
{
	$auth_data = album_permissions($album_user_id, $cat_id, ALBUM_AUTH_UPLOAD);
	if (!album_check_permission($auth_data, ALBUM_AUTH_UPLOAD))
	{
		if (($album_user_id != $user->data['user_id']) && ($user->data['user_level'] != ADMIN))
		{
			if(($album_user_id <= 0) && (!$user->data['session_logged_in']))
			{
				redirect(append_sid(CMS_PAGE_LOGIN . '?redirect=album_cat.' . PHP_EXT));
			}
			$album_user_id = (isset($_GET['user_id']) && (intval($_GET['user_id']) > 1)) ? intval($_GET['user_id']) : $user->data['user_id'];
			//$album_user_id = $user->data['user_id'];
		}
		else
		{
			$message = $lang['No_Personal_Category_admin'];
			$message .= '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid(album_append_uid('album.' . PHP_EXT)) . '">', '</a>');
			message_die(GENERAL_MESSAGE, $message);
		}
	}
}
else
{
	$auth_data = album_permissions($album_user_id, $cat_id, ALBUM_AUTH_MANAGE_PERSONAL_CATEGORIES);
	if (!album_check_permission($auth_data, ALBUM_AUTH_MANAGE_PERSONAL_CATEGORIES))
	{
		if (($album_user_id != $user->data['user_id']) && ($user->data['user_level'] != ADMIN))
		{
			if(($album_user_id <= 0) && (!$user->data['session_logged_in']))
			{
				redirect(append_sid(CMS_PAGE_LOGIN . '?redirect=album_cat.' . PHP_EXT));
			}

			if(!isset($_GET['action']))
			{
				redirect(append_sid('album.' . PHP_EXT));
			}
			$album_user_id = $user->data['user_id'];
		}
		else
		{
			$message = $lang['No_Personal_Category_admin'];
			$message .= '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.' . PHP_EXT) . '">', '</a>');
			message_die(GENERAL_MESSAGE, $message);
		}
	}
}

// ------------------------------------------------------------------------
//  A common function to generate the 'sucess' or 'failure' message
// ------------------------------------------------------------------------
function showResultMessage($in_message)
{
	global $lang, $album_user_id;

	if (album_get_personal_root_id($album_user_id) == ALBUM_ROOT_CATEGORY && strcmp('delete',isset($_GET['action']) == 0))
	{
		$message = $in_message . '<br /><br />' . sprintf($lang['Click_return_personal_gallery_index'], '<a href="' . append_sid(album_append_uid('album_personal_index.' . PHP_EXT)) . '">', '</a>');
	}
	else
	{
		$message = $in_message . '<br /><br />' . sprintf($lang['Click_return_personal_gallery'], '<a href="' . append_sid(album_append_uid('album.' . PHP_EXT)) . '">', '</a>');
	}

	message_die(GENERAL_MESSAGE, $message);
}


// ------------------------------------------------------------------------
// now start processing the page...
// ------------------------------------------------------------------------
if(!isset($_POST['mode']))
{
	if(!isset($_GET['action']))
	{
		/* 'global' template vars */
		$template->assign_vars(array(
			'S_ALBUM_ACTION' => append_sid(album_append_uid('album_personal_cat_admin.' . PHP_EXT)),

			'L_CREATE_CATEGORY' => $lang['Create_category'],
			'L_ALBUM_INDEX' => $lang['Album_Categories_Title'],
			'L_ALBUM_CAT_TITLE' => $lang['Album_Categories_Title'],
			'L_ALBUM_CAT_EXPLAIN' => $lang['Album_Categories_Explain'],
			'L_PERSONAL_ALBUM' => sprintf($lang['Personal_Gallery_Of_User'], $username),
			'L_PERSONAL_CAT_ADMIN' => $lang['Personal_Cat_Admin'],

			'ALBUM_NAVIGATION_ARROW' => ALBUM_NAV_ARROW,

			'U_PERSONAL_ALBUM' => append_sid(album_append_uid('album.' . PHP_EXT)),
			'U_PERSONAL_CAT_ADMIN' => append_sid(album_append_uid('album_personal_cat_admin.' . PHP_EXT . '?cat_id=' . $cat_id))
			)
		);

		// get the values of level selected
		if (!empty($cat_id))
		{
			$parent = $cat_id;
		}

		if (!isset($album_cat_tree['keys'][$parent]))
		{
			$parent = ALBUM_ROOT_CATEGORY; //album_get_personal_root_id($album_user_id);
		}

		// display the tree
		album_display_admin_index($parent);

		// ------------------------------------------------------------------------------------
		// Check if we have reached the maximum number of sub categories in personal gallery
		// if we have, then disable creation button
		// ------------------------------------------------------------------------------------
		$sql = "SELECT COUNT(*) AS count FROM " . ALBUM_CAT_TABLE . " WHERE cat_user_id = $album_user_id AND cat_parent <> 0";
		$result = $db->sql_query($sql);

		if($db->sql_numrows($result) >= 0)
		{
			$row = $db->sql_fetchrow($result);

			if ($row['count'] >= $album_config['personal_sub_category_limit'] && $album_config['personal_sub_category_limit'] >= 0)
			{
				$template->assign_vars(array('DISABLE_CREATION' => 'disabled'));
			}
		}
		// ------------------------------------------
		full_page_generation('album_personal_cat_body.tpl', $lang['Album'], '', '');
	}
	else
	{
		if($_GET['action'] == 'create')
		{
			album_create_personal_gallery($album_user_id, $album_config['personal_gallery_view'], ALBUM_PRIVATE);
			album_read_tree($album_user_id);
			showResultMessage($lang['New_category_created']);
		}
		elseif($_GET['action'] == 'edit')
		{
			$cat_id = intval($_GET['cat_id']);

			//$is_personal_root_cat = ($cat_id == album_get_personal_root_id($album_user_id)) ? true : false;
			if (($cat_id == album_get_personal_root_id($album_user_id)) || -1 == album_get_personal_root_id($album_user_id))
			{
				$is_personal_root_cat = true;
			}
			else
			{
				$is_personal_root_cat = false;
			}

			if ($cat_id != 0)
			{
				$sql = "SELECT cat.*, cat2.cat_title AS cat_parent_title, cat2.cat_id AS cat_parent_id
						FROM " . ALBUM_CAT_TABLE . " AS cat LEFT OUTER JOIN " . ALBUM_CAT_TABLE . " AS cat2
						ON cat2.cat_id = cat.cat_parent WHERE cat.cat_id = '$cat_id' AND cat.cat_user_id = " . $album_user_id;
				$result = $db->sql_query($sql);

				if($db->sql_numrows($result) == 0)
				{
					message_die(GENERAL_ERROR, 'The requested category is not existed');
				}

				$catrow = $db->sql_fetchrow($result);
			}
			else
			{
				$catrow = $album_data['data'][0];
			}

			$parent_cat_id = ($is_personal_root_cat) ? $cat_id : $catrow['cat_parent_id'];
			$s_album_cat_list = album_get_tree_option($parent_cat_id, ALBUM_AUTH_VIEW, ALBUM_SELECTBOX_INCLUDE_ALL|ALBUM_SELECTBOX_INCLUDE_ROOT);//, true, false, true);

			if ($cat_id == 0)
			{
				$cat_id = ALBUM_ROOT_CATEGORY;
			}

			$template->assign_vars(array(
				'S_ALBUM_ACTION' => append_sid(album_append_uid('album_personal_cat_admin.' . PHP_EXT . '?cat_id=' . $cat_id)),
				'L_CAT_TITLE' => $lang['Category_Title'],
				'L_CAT_DESC' => $lang['Category_Desc'],
				'L_CAT_PARENT_TITLE' => $lang['Parent_Category'],
				'L_CAT_PERMISSIONS' => $lang['Category_Permissions'],
				'L_VIEW_LEVEL' => $lang['View_level'],
				'L_UPLOAD_LEVEL' => $lang['Upload_level'],
				'L_RATE_LEVEL' => $lang['Rate_level'],
				'L_COMMENT_LEVEL' => $lang['Comment_level'],
				'L_EDIT_LEVEL' => $lang['Edit_level'],

				'ALBUM_NAVIGATION_ARROW' => ALBUM_NAV_ARROW,
				'U_PERSONAL_ALBUM' => append_sid(album_append_uid('album.' . PHP_EXT)),
				'L_PERSONAL_ALBUM' => sprintf($lang['Personal_Gallery_Of_User'], $username),
				'U_PERSONAL_CAT_ADMIN' => append_sid(album_append_uid('album_personal_cat_admin.' . PHP_EXT . '?cat_id=' . $cat_id)),
				'L_PERSONAL_CAT_ADMIN' => $lang['Personal_Cat_Admin'],

				'L_GUEST' => $lang['Forum_ALL'],
				'L_REG' => $lang['Forum_REG'],
				'L_PRIVATE' => $lang['Forum_PRIVATE'],

				'L_DISABLED' => $lang['Disabled'],

				'READ_ONLY' => ($is_personal_root_cat) ? 'readonly' :'',
				'DISABLED' => ($is_personal_root_cat) ? 'disabled' :'',

				'S_CAT_TITLE' => ($is_personal_root_cat) ? sprintf($lang['Personal_Gallery_Of_User'], $username) : stripslashes($catrow['cat_title']),
				'S_CAT_DESC' => $catrow['cat_desc'],
				'S_CAT_PARENT_OPTIONS' => $s_album_cat_list,

				'VIEW_GUEST' => ($catrow['cat_view_level'] == ALBUM_GUEST) ? 'selected="selected"' : '',
				'VIEW_REG' => ($catrow['cat_view_level'] == ALBUM_USER) ? 'selected="selected"' : '',
				'VIEW_PRIVATE' => ($catrow['cat_view_level'] == ALBUM_PRIVATE) ? 'selected="selected"' : '',

				'UPLOAD_GUEST' => ($catrow['cat_upload_level'] == ALBUM_GUEST) ? 'selected="selected"' : '',
				'UPLOAD_REG' => ($catrow['cat_upload_level'] == ALBUM_USER) ? 'selected="selected"' : '',
				'UPLOAD_PRIVATE' => ($catrow['cat_upload_level'] == ALBUM_PRIVATE) ? 'selected="selected"' : '',

				'RATE_GUEST' => ($catrow['cat_rate_level'] == ALBUM_GUEST) ? 'selected="selected"' : '',
				'RATE_REG' => ($catrow['cat_rate_level'] == ALBUM_USER) ? 'selected="selected"' : '',
				'RATE_PRIVATE' => ($catrow['cat_rate_level'] == ALBUM_PRIVATE) ? 'selected="selected"' : '',

				'COMMENT_GUEST' => ($catrow['cat_comment_level'] == ALBUM_GUEST) ? 'selected="selected"' : '',
				'COMMENT_REG' => ($catrow['cat_comment_level'] == ALBUM_USER) ? 'selected="selected"' : '',
				'COMMENT_PRIVATE' => ($catrow['cat_comment_level'] == ALBUM_PRIVATE) ? 'selected="selected"' : '',

				'EDIT_REG' => ($catrow['cat_edit_level'] == ALBUM_USER) ? 'selected="selected"' : '',
				'EDIT_PRIVATE' => ($catrow['cat_edit_level'] == ALBUM_PRIVATE) ? 'selected="selected"' : '',

				'DELETE_REG' => ($catrow['cat_delete_level'] == ALBUM_USER) ? 'selected="selected"' : '',
				'DELETE_PRIVATE' => ($catrow['cat_delete_level'] == ALBUM_PRIVATE) ? 'selected="selected"' : '',

				'S_MODE' => 'edit',

				'CATEGORY_ID' => $cat_id,
				'ROOT_CATEGORY_ID' => album_get_personal_root_id($album_user_id),
				'ALBUM_PUBLIC_GALLERY' => intval(ALBUM_JUMPBOX_PUBLIC_GALLERY),
				'ALBUM_ROOT_CATEGORY' => intval(ALBUM_ROOT_CATEGORY),
				'ALBUM_USERS_GALLERY' => intval(ALBUM_JUMPBOX_USERS_GALLERY),
				'ALBUM_JUMPBOX_SEPARATOR' => intval(ALBUM_JUMPBOX_SEPARATOR),
				'L_NO_SELF_REFERING' => $lang['No_Self_Refering_Cat'],
				'L_NO_VALID_CAT_SELECTED' => $lang['No_valid_category_selected'],

				'S_GUEST' => ALBUM_GUEST,
				'S_USER' => ALBUM_USER,
				'S_PRIVATE' => ALBUM_PRIVATE,

				'L_PANEL_TITLE' => $lang['Edit_Category']
				)
			);
			full_page_generation('album_personal_cat_new_body.tpl', $lang['Album'], '', '');
		}
		elseif($_GET['action'] == 'delete')
		{
			$cat_id = intval($_GET['cat_id']);

			$sql = "SELECT cat_id, cat_title, cat_order
					FROM " . ALBUM_CAT_TABLE . "
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
				message_die(GENERAL_ERROR, 'The requested category is not existed');
			}

			$select_to  = '<select name="target">';
			if ($cat_id == album_get_personal_root_id($album_user_id))
			{
				$select_to .= '<option value="0" selected="selected">' . $lang['Delete_all_pics'] . '</option>' ;
			}
			else
			{
				$select_to .= album_get_tree_option($catrow['cat_parent_id'], ALBUM_AUTH_VIEW, ALBUM_SELECTBOX_ALL);
			}

			$select_to .= '</select>';

			$template->assign_vars(array(
				'S_ALBUM_ACTION' => append_sid(album_append_uid('album_personal_cat_admin.' . PHP_EXT . '?cat_id=' . $cat_id)),
				'L_CAT_DELETE' => $lang['Delete_Category'],
				'L_CAT_DELETE_EXPLAIN' => $lang['Delete_Category_Explain'],
				'L_CAT_TITLE' => $lang['Category_Title'],
				'L_MOVE_CONTENTS' => $lang['Move_contents'],
				'L_MOVE_DELETE' => $lang['Move_and_Delete'],
				'L_PERSONAL_ALBUM' => sprintf($lang['Personal_Gallery_Of_User'], $username),
				'L_PERSONAL_CAT_ADMIN' => $lang['Personal_Cat_Admin'],

				'ALBUM_NAVIGATION_ARROW' => ALBUM_NAV_ARROW,

				'U_PERSONAL_ALBUM' => append_sid(album_append_uid('album.' . PHP_EXT)),
				'U_PERSONAL_CAT_ADMIN' => append_sid(album_append_uid('album_personal_cat_admin.' . PHP_EXT . '?cat_id=' . $cat_id)),

				'S_CAT_TITLE' => stripslashes($thiscat['cat_title']),
				'S_SELECT_TO' => $select_to
				)
			);
			full_page_generation('album_personal_cat_delete_body.tpl', $lang['Album'], '', '');
		}
		elseif($_GET['action'] == 'move')
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
	if($_POST['mode'] == 'new')
	{
		if (is_array($_POST['addcategory']))
		{
			list($cat_id) = each($_POST['addcategory']);
			$cat_title = stripslashes($_POST['name'][$cat_id]);
			$cat_parent = $cat_id;
			$cat_id = -1;
		}

		if(!isset($_POST['cat_title']))
		{
			$s_album_cat_list = album_get_tree_option($cat_parent, ALBUM_AUTH_VIEW);

			if (empty($s_album_cat_list) || $cat_parent == 0)
			{
				$s_album_cat_list = '<option value="-1" selected="selected">'. sprintf($lang['Personal_Gallery_Of_User'], $username). '</option>';
			}

			$template->assign_vars(array(
				'S_ALBUM_ACTION' => append_sid(album_append_uid('album_personal_cat_admin.' . PHP_EXT)),

				'L_CAT_TITLE' => $lang['Category_Title'],
				'L_CAT_DESC' => $lang['Category_Desc'],
				'L_CAT_PARENT_TITLE' => $lang['Parent_Category'],
				'L_CAT_PERMISSIONS' => $lang['Category_Permissions'],

				'ALBUM_NAVIGATION_ARROW' => ALBUM_NAV_ARROW,
				'U_PERSONAL_ALBUM' => append_sid('album.' . PHP_EXT),
				'L_PERSONAL_ALBUM' => sprintf($lang['Personal_Gallery_Of_User'], $username),
				'U_PERSONAL_CAT_ADMIN' => append_sid(album_append_uid('album_personal_cat_admin.' . PHP_EXT . '?cat_id=' . $cat_id)),
				'L_PERSONAL_CAT_ADMIN' => $lang['Personal_Cat_Admin'],

				'L_VIEW_LEVEL' => $lang['View_level'],
				'L_UPLOAD_LEVEL' => $lang['Upload_level'],
				'L_RATE_LEVEL' => $lang['Rate_level'],
				'L_COMMENT_LEVEL' => $lang['Comment_level'],

				'L_GUEST' => $lang['Forum_ALL'],
				'L_REG' => $lang['Forum_REG'],
				'L_PRIVATE' => $lang['Forum_PRIVATE'],

				'VIEW_GUEST' => ($album_config['personal_gallery_view'] == ALBUM_GUEST) ? 'selected="selected"' : '',
				'VIEW_REG' => ($album_config['personal_gallery_view'] == ALBUM_USER) ? 'selected="selected"' : '',
				'VIEW_PRIVATE' => ($album_config['personal_gallery_view'] == ALBUM_PRIVATE) ? 'selected="selected"' : '',

				'UPLOAD_PRIVATE' => 'selected="selected"',
				'RATE_REG' => 'selected="selected"',
				'COMMENT_REG' => 'selected="selected"',

				'S_CAT_TITLE' => stripslashes($cat_title),
				'S_CAT_PARENT_OPTIONS' => $s_album_cat_list,

				'CATEGORY_ID'=> 0,
				'ROOT_CATEGORY_ID' => ALBUM_ROOT_CATEGORY,
				'ALBUM_PUBLIC_GALLERY' => intval(ALBUM_JUMPBOX_PUBLIC_GALLERY),
				'ALBUM_ROOT_CATEGORY' => intval(ALBUM_ROOT_CATEGORY),
				'ALBUM_USERS_GALLERY' => intval(ALBUM_JUMPBOX_USERS_GALLERY),
				'ALBUM_JUMPBOX_SEPARATOR' => intval(ALBUM_JUMPBOX_SEPARATOR),
				'L_NO_VALID_CAT_SELECTED' => $lang['No_valid_category_selected'],

				'S_MODE' => 'new',

				'S_GUEST' => ALBUM_GUEST,
				'S_USER' => ALBUM_USER,
				'S_PRIVATE' => ALBUM_PRIVATE,
				'S_MOD' => ALBUM_MOD,
				'S_ADMIN' => ALBUM_ADMIN,

				'L_PANEL_TITLE' => $lang['Create_category']
				)
			);
			full_page_generation('album_personal_cat_new_body.tpl', $lang['Album'], '', '');
		}
		else
		{
			$cat_title = request_var('cat_title', '', true);
			$cat_desc = request_var('cat_desc', '', true);

			$view_level = intval($_POST['cat_view_level']);
			$upload_level = intval($_POST['cat_upload_level']);
			$rate_level = intval($_POST['cat_rate_level']);
			$comment_level = intval($_POST['cat_comment_level']);
			$edit_level = intval($_POST['cat_edit_level']);
			$delete_level = intval($_POST['cat_delete_level']);
			$cat_approval = intval($_POST['cat_approval']);
			$cat_parent = ($_POST['cat_parent_id'] == ALBUM_ROOT_CATEGORY) ? album_get_personal_root_id($album_user_id) : intval($_POST['cat_parent_id']);

			// Get the last ordered category
			$sql = "SELECT cat_order FROM " . ALBUM_CAT_TABLE . "
					ORDER BY cat_order DESC
					LIMIT 1";
			$result = $db->sql_query($sql);
			$row = $db->sql_fetchrow($result);
			$last_order = $row['cat_order'];
			$cat_order = $last_order + 10;

			// Here we insert a new row into the db

			$sql = "INSERT INTO " . ALBUM_CAT_TABLE . " (cat_title, cat_desc, cat_order, cat_view_level, cat_upload_level, cat_rate_level, cat_comment_level, cat_edit_level, cat_delete_level, cat_approval, cat_parent, cat_user_id)
					VALUES ('" . $db->sql_escape($cat_title) . "', '" . $db->sql_escape($cat_desc) . "', '$cat_order', '$view_level', '$upload_level', '$rate_level', '$comment_level', '" . ALBUM_PRIVATE . "', '" . ALBUM_PRIVATE . "', '0', '$cat_parent', '$album_user_id')";
			$result = $db->sql_query($sql);

			// Return a message...
			showResultMessage($lang['New_category_created']);
		}
	}
	elseif($_POST['mode'] == 'edit')
	{
		// Get posting variables
		$cat_id = request_var('cat_id', 0);
		$cat_title = request_var('cat_title', '', true);
		$cat_desc = request_var('cat_desc', '', true);
		$view_level = intval($_POST['cat_view_level']);
		$upload_level = intval($_POST['cat_upload_level']);
		$rate_level = intval($_POST['cat_rate_level']);
		$comment_level = intval($_POST['cat_comment_level']);
		$edit_level = intval($_POST['cat_edit_level']);
		$delete_level = intval($_POST['cat_delete_level']);
		$cat_approval = intval($_POST['cat_approval']);
		$cat_parent = ($_POST['cat_parent_id'] == ALBUM_ROOT_CATEGORY) ? 0 : intval($_POST['cat_parent_id']);

		if (($cat_id == $cat_parent) && (album_get_personal_root_id($album_user_id) != $cat_id))
		{
			showResultMessage($lang['No_Self_Refering_Cat']);
		}

		if ((album_get_personal_root_id($album_user_id) == $cat_id) && ($cat_parent != 0))
		{
			showResultMessage($lang['Can_Not_Change_Main_Parent']);
		}

		// Now we update this row

		$sql = "UPDATE " . ALBUM_CAT_TABLE . "
				SET cat_title = '" . $db->sql_escape($cat_title) . "', cat_desc = '" . $db->sql_escape($cat_desc) . "', cat_view_level = '$view_level', cat_upload_level = '$upload_level', cat_rate_level = '$rate_level', cat_comment_level = '$comment_level', cat_edit_level = '" . ALBUM_PRIVATE . "', cat_delete_level = '" . ALBUM_PRIVATE . "', cat_approval = '0', cat_parent = '$cat_parent', cat_user_id = '$album_user_id'
				WHERE cat_id = '$cat_id'";
		$result = $db->sql_query($sql);

		// Return a message...
		showResultMessage($lang['Category_updated']);
	}
	elseif($_POST['mode'] == 'delete')
	{
		$parent_cat_id = 0;
		$parent_cat_title = '';
		$parent_cat_deleted = false;

		$source_cat_id = request_var('cat_id', 0);
		$target_cat_id = request_var('target', 0);

		if($target_cat_id == ALBUM_JUMPBOX_DELETE) // Delete All
		{
			// check if the selected category is a parent to another category
			$sql = "SELECT cat_id FROM " . ALBUM_CAT_TABLE . " WHERE cat_parent = " . $source_cat_id .";";
			$result = $db->sql_query($sql);

			// the selected category is parent to another...proceed
			if ($db->sql_numrows($result) > 0)
			{
				$parent_cat_id = 0;
				//set the indicator that we are deleting a parent category
				$parent_cat_deleted = true;

				if (isset($lang[$config['sitename']]))
				{
					$parent_cat_title = sprintf($lang['Forum_Index'], $lang[$config['sitename']]);
				}
				else
				{
					$parent_cat_title = sprintf($lang['Forum_Index'], $config['sitename']);
				}

				//... then check if the selected category is a child to another category
				$sql = "SELECT cat.cat_id, parent.cat_title AS cat_parent_title, parent.cat_id AS cat_parent_id
						FROM " . ALBUM_CAT_TABLE . " AS cat, " . ALBUM_CAT_TABLE . " AS parent
						WHERE cat.cat_id = '$source_cat_id' AND parent.cat_id = cat.cat_parent";
				$result = $db->sql_query($sql);

				if ($db->sql_numrows($result) > 0)
				{
					while($row = $db ->sql_fetchrow($result))
					{
						// get the paretn id for the selected id
						$parent_cat_id = $row['cat_parent_id'];
						$parent_cat_title = stripslashes($row['cat_parent_title']);
					}
				}

				// move the the selected category's child categories to the selected parent category (which can be nothing = cat_parent = 0)
				$sql = "UPDATE " . ALBUM_CAT_TABLE . " SET cat_parent = '" . $parent_cat_id . "' WHERE cat_parent = '" . $source_cat_id . "'";
				$result = $db->sql_query($sql);
			}

			// Get file information of all pics in this category
			$sql = "SELECT pic_id, pic_filename, pic_thumbnail, pic_cat_id
					FROM " . ALBUM_TABLE . "
					WHERE pic_cat_id = '$source_cat_id'";
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
					$pic_title = $picrow[$i]['pic_title'];
					$pic_title_reg = preg_replace('/[^A-Za-z0-9]+/', '_', $pic_title);
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
						WHERE rate_pic_id IN " . $pic_id_sql;
				$result = $db->sql_query($sql);

				// Delete all related comments
				$sql = "DELETE FROM ". ALBUM_COMMENT_TABLE ."
						WHERE comment_pic_id IN " . $pic_id_sql;
				$result = $db->sql_query($sql);

				// Delete pic entries in db
				$sql = "DELETE FROM " . ALBUM_TABLE . "
						WHERE pic_cat_id = '" . $source_cat_id . "'";
				$result = $db->sql_query($sql);
			}

			// This category is now emptied, we can remove it!
			$sql = "DELETE FROM " . ALBUM_CAT_TABLE . "
					WHERE cat_id = '" . $source_cat_id . "'";
			$result = $db->sql_query($sql);

			// Re-order the rest of categories
			album_reorder_cat($album_user_id);

			// Return a message...
			$message = ($parent_cat_deleted == true) ? sprintf($lang['Child_Category_Moved'], $parent_cat_title) . '<br />' : '';
			showResultMessage($message . $lang['Category_deleted']);
		}
		else // Move content...
		{
			$sql = "UPDATE " . ALBUM_TABLE . "
					SET pic_cat_id = '" . $target_cat_id . "'
					WHERE pic_cat_id = '" . $source_cat_id . "'";
			$result = $db->sql_query($sql);

			// This category is now emptied, we can remove it!
			$sql = "DELETE FROM " . ALBUM_CAT_TABLE . "
					WHERE cat_id = '" . $source_cat_id . "'";
			$result = $db->sql_query($sql);

			// Re-order the rest of categories
			album_reorder_cat($album_user_id);

			// Return a message...
			showResultMessage($lang['Category_deleted']);
		}
	}
}

?>