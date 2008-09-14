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
* MX-System - (jonohlsson@hotmail.com) - (www.mx-system.com)
*
*/

define('IN_ICYPHOENIX', true);

if (!empty($setmodules))
{
	$file = basename(__FILE__);
	$module['1800_KB_title']['150_Permissions'] = $file;
	return;
}

if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('./pagestart.' . PHP_EXT);
include(IP_ROOT_PATH . 'config.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/functions_admin.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/kb_constants.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/functions_kb.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/functions_kb_auth.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/functions_kb_field.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/functions_kb_mx.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/functions_search.' . PHP_EXT);

if (!isset($_POST['submit']))
{
	$s_kb_cat_list = get_kb_cat_list('', 0, 0, 0, 0, true);
	$template->set_filenames(array('body' => ADM_TPL . 'kb_cat_select_body.tpl'));

	$template->assign_vars(array('L_KB_AUTH_TITLE' => $lang['KB_Auth_Title'],
			'L_KB_AUTH_EXPLAIN' => $lang['KB_Auth_Explain'],
			'L_SELECT_CAT' => $lang['Select_a_Category'],
			'S_KB_ACTION' => append_sid("admin_kb_auth." . PHP_EXT),
			'L_LOOK_UP_CAT' => $lang['Look_up_Category'],
			'CAT_SELECT_TITLE' => $s_kb_cat_list
		)
	);

	$template->pparse('body');

	include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);
}
else
{
	if (!isset($_GET['cat_id']))
	{
		$cat_id = intval($_POST['cat_id']);

		$template->set_filenames(array('body' => ADM_TPL . 'kb_cat_auth_body.tpl'));

		$template->assign_vars(array(
				'L_KB_AUTH_TITLE' => $lang['KB_Auth_Title'],
				'L_KB_AUTH_EXPLAIN' => $lang['KB_Auth_Explain'],
				'L_SUBMIT' => $lang['Submit'],
				'L_RESET' => $lang['Reset'],
				'L_GROUPS' => $lang['Usergroups'],
				'L_VIEW' => $lang['View'],
				'L_UPLOAD' => $lang['Upload'],
				'L_RATE' => $lang['Rate'],
				'L_COMMENT' => $lang['Comment'],
				'L_EDIT' => $lang['Edit'],
				'L_DELETE' => $lang['Delete'],
				// 'L_APPROVAL' => $lang['Approval'],
				// 'L_APPROVAL_EDIT' => $lang['Approval_edit'],
				'L_IS_MODERATOR' => $lang['Is_Moderator'],
				'S_KB_ACTION' => append_sid('admin_kb_auth.' . PHP_EXT . '?cat_id=' . $cat_id),
				)
			);
		// Get the list of phpBB usergroups
		$sql = "SELECT group_id, group_name
				FROM " . GROUPS_TABLE . "
				WHERE group_single_user <> " . true . "
				ORDER BY group_name ASC";
		if (!($result = $db->sql_query($sql)))
		{
			mx_message_die(GENERAL_ERROR, 'Could not get group list', '', __LINE__, __FILE__, $sql);
		}

		while ($kb_row = $db->sql_fetchrow($result))
		{
			$groupdata[] = $kb_row;
		}
		// Get info of this cat
		$sql = "SELECT category_id, category_name, auth_view_groups, auth_post_groups, auth_rate_groups, auth_comment_groups, auth_edit_groups, auth_delete_groups, auth_approval_groups, auth_approval_edit_groups, auth_moderator_groups
				FROM " . KB_CATEGORIES_TABLE . "
				WHERE category_id = '$cat_id'";
		if (!$result = $db->sql_query($sql))
		{
			mx_message_die(GENERAL_ERROR, 'Could not get Category information', '', __LINE__, __FILE__, $sql);
		}

		$thiscat = $db->sql_fetchrow($result);

		$view_groups = @explode(',', $thiscat['auth_view_groups']);
		$post_groups = @explode(',', $thiscat['auth_post_groups']);
		$rate_groups = @explode(',', $thiscat['auth_rate_groups']);
		$comment_groups = @explode(',', $thiscat['auth_comment_groups']);
		$edit_groups = @explode(',', $thiscat['auth_edit_groups']);
		$delete_groups = @explode(',', $thiscat['auth_delete_groups']);
		// $approval_groups = @explode(',', $thiscat['auth_approval_groups']);
		// $approval_edit_groups = @explode(',', $thiscat['auth_approval_edit_groups']);

		$moderator_groups = @explode(',', $thiscat['auth_moderator_groups']);

		for ($i = 0; $i < count($groupdata); $i++)
		{
			$template->assign_block_vars('grouprow', array('GROUP_ID' => $groupdata[$i]['group_id'],
				'GROUP_NAME' => $groupdata[$i]['group_name'],
				'VIEW_CHECKED' => (in_array($groupdata[$i]['group_id'], $view_groups)) ? 'checked="checked"' : '',
				'POST_CHECKED' => (in_array($groupdata[$i]['group_id'], $post_groups)) ? 'checked="checked"' : '',
				'RATE_CHECKED' => (in_array($groupdata[$i]['group_id'], $rate_groups)) ? 'checked="checked"' : '',
				'COMMENT_CHECKED' => (in_array($groupdata[$i]['group_id'], $comment_groups)) ? 'checked="checked"' : '',
				'EDIT_CHECKED' => (in_array($groupdata[$i]['group_id'], $edit_groups)) ? 'checked="checked"' : '',
				'DELETE_CHECKED' => (in_array($groupdata[$i]['group_id'], $delete_groups)) ? 'checked="checked"' : '',
				// 'APPROVAL_CHECKED' => (in_array($groupdata[$i]['group_id'], $approval_groups)) ? 'checked="checked"' : '',
				// 'APPROVAL_EDIT_CHECKED' => (in_array($groupdata[$i]['group_id'], $approval_edit_groups)) ? 'checked="checked"' : '',
				'MODERATOR_CHECKED' => (in_array($groupdata[$i]['group_id'], $moderator_groups)) ? 'checked="checked"' : '',
				)
			);
		}

		$template->pparse('body');

		include(IP_ROOT_PATH . ADM .'/page_footer_admin.' . PHP_EXT);
	}
	else
	{
		$cat_id = intval($_GET['cat_id']);

		$view_groups = @implode(',', $_POST['view']);
		$post_groups = @implode(',', $_POST['post']);
		$rate_groups = @implode(',', $_POST['rate']);
		$comment_groups = @implode(',', $_POST['comment']);
		$edit_groups = @implode(',', $_POST['edit']);
		$delete_groups = @implode(',', $_POST['delete']);
		// $approval_groups = @implode(',', $_POST['approval']);
		// $approval_edit_groups = @implode(',', $_POST['approval_edit']);

		$moderator_groups = @implode(',', $_POST['moderator']);

		$sql = "UPDATE " . KB_CATEGORIES_TABLE . "
				SET auth_view_groups = '$view_groups', auth_post_groups = '$post_groups', auth_rate_groups = '$rate_groups', auth_comment_groups = '$comment_groups', auth_edit_groups = '$edit_groups', auth_delete_groups = '$delete_groups', auth_approval_groups = '$approval_groups', auth_approval_edit_groups = '$approval_edit_groups',	auth_moderator_groups = '$moderator_groups'
				WHERE category_id = '$cat_id'";
		if (!$result = $db->sql_query($sql))
		{
			mx_message_die(GENERAL_ERROR, 'Could not update KB config table', '', __LINE__, __FILE__, $sql);
		}
		$message = $lang['KB_Auth_successfully'] . '<br /><br />' . sprintf($lang['Click_return_KB_auth'], '<a href="' . append_sid("admin_kb_auth." . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid(IP_ROOT_PATH . ADM . 'index.' . PHP_EXT . '?pane=right') . '">', '</a>');

		mx_message_die(GENERAL_MESSAGE, $message);
	}
}

?>