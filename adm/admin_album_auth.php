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

if(!empty($setmodules))
{
	$filename = basename(__FILE__);
	$module['2200_Photo_Album']['130_Album_Permissions'] = $filename;
	return;
}

// Load default Header
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('pagestart.' . PHP_EXT);

setup_extra_lang(array('lang_album_main', 'lang_album_admin'));

require(ALBUM_MOD_PATH . 'album_common.' . PHP_EXT);
$album_user_id = ALBUM_PUBLIC_GALLERY;

if(!isset($_POST['submit']))
{
	album_read_tree();
	$s_album_cat_list = album_get_tree_option(ALBUM_ROOT_CATEGORY, ALBUM_AUTH_VIEW, ALBUM_SELECTBOX_INCLUDE_ALL | ALBUM_SELECTBOX_INCLUDE_ROOT);

	$template->set_filenames(array('body' => ADM_TPL . 'album_cat_select_body.tpl'));

	$template->assign_vars(array(
		'L_ALBUM_AUTH_TITLE' => $lang['Album_Auth_Title'],
		'L_ALBUM_AUTH_EXPLAIN' => $lang['Album_Auth_Explain'],
		'L_SELECT_CAT' => $lang['Select_a_Category'],
		'S_ALBUM_ACTION' => append_sid("admin_album_auth." . PHP_EXT),
		'L_LOOK_UP_CAT' => $lang['Look_up_Category'],
		'CAT_SELECT_TITLE' => $s_album_cat_list)
	);

	$template->pparse('body');

	include('./page_footer_admin.' . PHP_EXT);
}
else
{
	if(!isset($_GET['cat_id']))
	{
		$cat_id = intval($_POST['cat_id']);

		$template->set_filenames(array('body' => ADM_TPL . 'album_auth_body.tpl'));

		$template->assign_vars(array(
			'L_ALBUM_AUTH_TITLE' => $lang['Album_Auth_Title'],
			'L_ALBUM_AUTH_EXPLAIN' => $lang['Album_Auth_Explain'],
			'L_SUBMIT' => $lang['Submit'],
			'L_RESET' => $lang['Reset'],

			'L_GROUPS' => $lang['Usergroups'],

			'L_VIEW' => $lang['View'],
			'L_UPLOAD' => $lang['Upload'],
			'L_RATE' => $lang['Rate'],
			'L_COMMENT' => $lang['Comment'],
			'L_EDIT' => $lang['Edit'],
			'L_DELETE' => $lang['Delete'],

			'L_IS_MODERATOR' => $lang['Is_Moderator'],
			'S_ALBUM_ACTION' => append_sid('admin_album_auth.' . PHP_EXT . '?cat_id=' . $cat_id),
			)
		);

		// Get the list of phpBB usergroups
		$sql = "SELECT group_id, group_name
				FROM " . GROUPS_TABLE . "
				WHERE group_single_user <> " . TRUE ."
				ORDER BY group_name ASC";
		$result = $db->sql_query($sql);

		while($row = $db->sql_fetchrow($result))
		{
			$groupdata[] = $row;
		}

		// Get info of this cat
		$sql = "SELECT cat_id, cat_title, cat_view_groups, cat_upload_groups, cat_rate_groups, cat_comment_groups, cat_edit_groups, cat_delete_groups, cat_moderator_groups
				FROM ". ALBUM_CAT_TABLE ."
				WHERE cat_id = '$cat_id'";
		$result = $db->sql_query($sql);
		$thiscat = $db->sql_fetchrow($result);

		$view_groups = @explode(',', $thiscat['cat_view_groups']);
		$upload_groups = @explode(',', $thiscat['cat_upload_groups']);
		$rate_groups = @explode(',', $thiscat['cat_rate_groups']);
		$comment_groups = @explode(',', $thiscat['cat_comment_groups']);
		$edit_groups = @explode(',', $thiscat['cat_edit_groups']);
		$delete_groups = @explode(',', $thiscat['cat_delete_groups']);

		$moderator_groups = @explode(',', $thiscat['cat_moderator_groups']);

		for ($i = 0; $i < sizeof($groupdata); $i++)
		{
			$class = ($i % 2) ? $theme['td_class1'] : $theme['td_class2'];
			$template->assign_block_vars('grouprow', array(
				'CLASS' => $class,

				'GROUP_ID' => $groupdata[$i]['group_id'],
				'GROUP_NAME' => $groupdata[$i]['group_name'],

				'VIEW_CHECKED' => (in_array($groupdata[$i]['group_id'], $view_groups)) ? 'checked="checked"' : '',

				'UPLOAD_CHECKED' => (in_array($groupdata[$i]['group_id'], $upload_groups)) ? 'checked="checked"' : '',

				'RATE_CHECKED' => (in_array($groupdata[$i]['group_id'], $rate_groups)) ? 'checked="checked"' : '',

				'COMMENT_CHECKED' => (in_array($groupdata[$i]['group_id'], $comment_groups)) ? 'checked="checked"' : '',

				'EDIT_CHECKED' => (in_array($groupdata[$i]['group_id'], $edit_groups)) ? 'checked="checked"' : '',

				'DELETE_CHECKED' => (in_array($groupdata[$i]['group_id'], $delete_groups)) ? 'checked="checked"' : '',

				'MODERATOR_CHECKED' => (in_array($groupdata[$i]['group_id'], $moderator_groups)) ? 'checked="checked"' : ''
				)
			);
		}

		$template->pparse('body');

		include('./page_footer_admin.' . PHP_EXT);
	}
	else
	{
		$cat_id = intval($_GET['cat_id']);

		$view_groups = @implode(',', $_POST['view']);
		$upload_groups = @implode(',', $_POST['upload']);
		$rate_groups = @implode(',', $_POST['rate']);
		$comment_groups = @implode(',', $_POST['comment']);
		$edit_groups = @implode(',', $_POST['edit']);
		$delete_groups = @implode(',', $_POST['delete']);

		$moderator_groups = @implode(',', $_POST['moderator']);

		$sql = "UPDATE ". ALBUM_CAT_TABLE ."
				SET cat_view_groups = '$view_groups', cat_upload_groups = '$upload_groups', cat_rate_groups = '$rate_groups', cat_comment_groups = '$comment_groups', cat_edit_groups = '$edit_groups', cat_delete_groups = '$delete_groups', cat_moderator_groups = '$moderator_groups'
				WHERE cat_id = '$cat_id'";
		$result = $db->sql_query($sql);

		// okay, return a message...
		$message = $lang['Album_Auth_successfully'] . '<br /><br />' . sprintf($lang['Click_return_album_auth'], '<a href="' . append_sid("admin_album_auth." . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

		message_die(GENERAL_MESSAGE, $message);
	}
}

?>