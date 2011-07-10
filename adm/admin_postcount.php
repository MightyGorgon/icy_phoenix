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
* Antony Bailey & Freakin' Booty - (santony_bailey@lycos.co.uk)
*
*/

define('IN_ICYPHOENIX', true);

if(!empty($setmodules))
{
	$filename = basename(__FILE__);
	$module['1610_Users']['250_Postcount_Config'] = $filename;

	return;
}

if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('pagestart.' . PHP_EXT);

$username = request_var('username', '', true);
$username = htmlspecialchars_decode($username, ENT_COMPAT);

if(!empty($username))
{
	if(!$this_userdata = get_userdata($username))
	{
		message_die(GENERAL_MESSAGE, $lang['User_not_exist']);
	}
	$user_id = $this_userdata['user_id'];
	$username = $this_userdata['username'];

	if($_POST['update'])
	{
		$posts = request_var('posts', 0);

		$sql = "UPDATE " . USERS_TABLE . "
				SET user_posts = '$posts'
				WHERE user_id = $user_id";
		$db->sql_query($sql);

		$message = $lang['Post_count_changed'] . '<br /><br />' . sprintf($lang['Click_return_posts_config'], '<a href="' . append_sid('admin_postcount.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid('index.' . PHP_EXT) . '">', '</a>');
		message_die(GENERAL_MESSAGE, $message);
	}


	$s_hidden_fields = '<input type="hidden" name="username" value="' . htmlspecialchars($username) . '" />';

	$template->set_filenames(array('body' => ADM_TPL . 'postcount_body.tpl'));

	$template->assign_vars(array(
		'L_PC_TITLE' => $lang['Modify_post_counts'],
		'L_PC_EXPLAIN' => $lang['Post_count_explain'],
		'L_EDIT_PC' => sprintf($lang['Edit_post_count'], $username),
		'L_UPDATE' => $lang['Update'],
		'L_RESET' => $lang['Reset'],

		'POSTS' => $this_userdata['user_posts'],
		'S_HIDDEN_FIELDS' => $s_hidden_fields,
		'S_USER_ACTION' => append_sid('admin_postcount.' . PHP_EXT),
		'S_USER_SELECT' => $select_list
		)
	);
}
else
{
	$template->set_filenames(array('body' => ADM_TPL . 'user_select_body.tpl'));

	$template->assign_vars(array(
		'L_USER_TITLE' => $lang['Modify_post_counts'],
		'L_USER_EXPLAIN' => $lang['Post_count_explain'],
		'L_USER_SELECT' => $lang['Select_a_User'],
		'L_LOOK_UP' => $lang['Look_up_user'],
		'L_FIND_USERNAME' => $lang['Find_username'],

		'U_SEARCH_USER' => append_sid('./../' . CMS_PAGE_SEARCH . '?mode=searchuser'),

		'S_USER_ACTION' => append_sid('admin_postcount.' . PHP_EXT),
		'S_USER_SELECT' => $select_list
		)
	);
}

$template->pparse('body');

include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);

?>