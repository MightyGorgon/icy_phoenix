<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

define('IN_ICYPHOENIX', true);

if(!empty($setmodules))
{
	$filename = basename(__FILE__);
	$module['1610_Users']['116_CMS_Permissions_Users'] = $filename;
	return;
}

// Load default header
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
$no_page_header = true;
require('pagestart.' . PHP_EXT);

setup_extra_lang(array('lang_cms'));

//Start Quick Administrator User Options and Information MOD
$redirect = request_var('redirect', '');
//End Quick Administrator User Options and Information MOD

$user_id = request_post_var(POST_USERS_URL, 0);

$username = request_post_var('username', '', true);
if (!empty($username))
{
	$this_userdata = get_userdata($username, true);
	if (!is_array($this_userdata))
	{
		if (!defined('STATUS_404')) define('STATUS_404', true);
		message_die(GENERAL_MESSAGE, 'NO_USER');
	}
	$user_id = $this_userdata['user_id'];
}

$posted_user_cms_level = (isset($_POST['user_cms_level']) ? request_post_var('user_cms_level', 0) : false);

// Please note that !== is on purpose...
if ($posted_user_cms_level !== false)
{
	$sql = "UPDATE " . USERS_TABLE . "
		SET user_cms_level = '" . $posted_user_cms_level . "'
		WHERE user_id = '" . $user_id . "'";
	$result = $db->sql_query($sql);

	$message = $lang['Auth_updated'] . '<br /><br />' . sprintf($lang['Click_return_userauth'], '<a href="' . append_sid('admin_cms_auth.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');
	message_die(GENERAL_MESSAGE, $message);
}

//Start Quick Administrator User Options and Information MOD
if(!empty($redirect))
{
	$message = $lang['Auth_updated'] . '<br /><br />' . sprintf($lang['Click_return_userprofile'], '<a href="' . append_sid('../' . CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $user_id) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT) . '">', '</a>');
}
//End Quick Administrator User Options and Information MOD
elseif (!empty($user_id))
{
	$user_cms_level = '';
	$sql = "SELECT u.*
		FROM " . USERS_TABLE . " u
		WHERE u.user_id = " . $user_id;
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$user_cms_level = $row['user_cms_level'];
	$username = $row['username'];
	$db->sql_freeresult($result);

	$auth_array = array(
		'0' => $lang['CMS_GUEST'],
		'1' => $lang['CMS_REG'],
		'2' => $lang['CMS_VIP'],
		'3' => $lang['CMS_PUBLISHER'],
		'4' => $lang['CMS_REVIEWER'],
		'5' => $lang['CMS_CONTENT_MANAGER']
	);

	$s_user_type = '<select name="user_cms_level">';
	for ($i = 0; $i <= 5; $i++)
	{
		$s_selected = ($user_cms_level == $i) ? ' selected="selected"' : '';
		$s_user_type .= '<option value="' . $i . '"' . $s_selected . '>' . $auth_array[$i] . '</option>';
	}
	$s_user_type .= '</select>';

	$s_hidden_fields .= '<input type="hidden" name="' . POST_USERS_URL . '" value="' . $user_id . '" />';
	$s_hidden_fields .= '<input type="hidden" name="cms_level" value="true" />';

	include('./page_header_admin.' . PHP_EXT);

	$template->set_filenames(array('body' => ADM_TPL . 'auth_cms_body.tpl'));

	$template->assign_vars(array(
		'USERNAME' => $username,
		'USER_LEVEL' => '<b>' . $lang['User_Level'] . '</b>: ' . $s_user_type,
		'L_USER' => $lang['Username'],
		'L_AUTH_TITLE' => $lang['Auth_Control_User'],
		'L_PERMISSIONS' => $lang['Permissions'],
		'L_SUBMIT' => $lang['Submit'],
		'L_RESET' => $lang['Reset'],
		'U_USER' => append_sid('admin_cms_auth.' . PHP_EXT),
		'S_AUTH_ACTION' => append_sid('admin_cms_auth.' . PHP_EXT),
		'S_HIDDEN_FIELDS' => $s_hidden_fields
		)
	);
}
else
{
	// Select a user/group
	include('./page_header_admin.' . PHP_EXT);

	$template->set_filenames(array('body' => ADM_TPL . 'user_select_body.tpl'));
	$template->assign_vars(array(
		'L_FIND_USERNAME' => $lang['Find_username'],
		'U_SEARCH_USER' => append_sid('../' . CMS_PAGE_SEARCH . '?mode=searchuser')
		)
	);

	$s_hidden_fields = '<input type="hidden" name="mode" value="user" />';
	$l_type = 'USER';

	$template->assign_vars(array(
		'L_LOOK_UP' => $lang['Look_up_User'],
		'S_HIDDEN_FIELDS' => $s_hidden_fields,
		'S_' . $l_type . '_ACTION' => append_sid('admin_cms_auth.' . PHP_EXT))
	);
}

$template->pparse('body');

include('./page_footer_admin.' . PHP_EXT);

?>