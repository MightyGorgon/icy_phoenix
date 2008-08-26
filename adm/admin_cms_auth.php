<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

define('IN_PHPBB', true);

if( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	$module['1610_Users']['116_CMS_Permissions_Users'] = $filename;
	return;
}

// Load default header
$phpbb_root_path = './../';
$no_page_header = true;
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);
include_once($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_cms.' . $phpEx);

//Start Quick Administrator User Options and Information MOD
if( isset( $_POST['redirect'] ) || isset( $_GET['redirect'] ) )
{
	$redirect = ( isset( $_POST['redirect'] ) ) ? $_POST['redirect'] : $_GET['redirect'];
	$redirect = htmlspecialchars($redirect);
}
else
{
	$redirect = '';
}
//End Quick Administrator User Options and Information MOD

if ( !empty($_POST['u']) || !empty($_GET['u']) )
{
	$user_id = ( !empty($_POST['u']) ) ? intval($_POST['u']) : intval($_GET['u']);
}
else
{
	$user_id = false;
}

if ( isset($_POST['username']) )
{
	$this_userdata = get_userdata($_POST['username'], true);
	if ( !is_array($this_userdata) )
	{
		message_die(GENERAL_MESSAGE, $lang['No_such_user']);
	}
	$user_id = $this_userdata['user_id'];
	$username = $_POST['username'];
}

$posted_user_cms_level = ( isset($_POST['user_cms_level']) ? intval($_POST['user_cms_level']) : false );

if ( $posted_user_cms_level != false )
{
	$sql = "UPDATE " . USERS_TABLE . "
		SET user_cms_level = '" . $posted_user_cms_level . "'
		WHERE user_id = '" . $user_id . "'";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not update user cms level', '', __LINE__, __FILE__, $sql);
	}

	$message = $lang['Auth_updated'] . '<br /><br />' . sprintf($lang['Click_return_userauth'], '<a href="' . append_sid('admin_cms_auth.' . $phpEx) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . $phpEx . '?pane=right') . '">', '</a>');
	message_die(GENERAL_MESSAGE, $message);
}

//Start Quick Administrator User Options and Information MOD
if( $redirect != '' )
{
	$message = $lang['Auth_updated'] . '<br /><br />' . sprintf($lang['Click_return_userprofile'], '<a href="' . append_sid('../' . PROFILE_MG . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $user_id) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . $phpEx) . '">', '</a>');
}
//End Quick Administrator User Options and Information MOD
elseif ( $user_id != false )
{
	$user_cms_level = '';
	$sql = "SELECT u.*
		FROM " . USERS_TABLE . " u
		WHERE u.user_id = " . $user_id;
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not select info from users table', '', __LINE__, __FILE__, $sql);
	}
	$row = $db->sql_fetchrow($result);
	$user_cms_level = $row['user_cms_level'];
	$username = $row['username'];
	$db->sql_freeresult($result);

	$auth_array = array(
		'0' => $lang['CMS_Guest'],
		'1' => $lang['CMS_Reg'],
		'2' => $lang['CMS_VIP'],
		'3' => $lang['CMS_Publisher'],
		'4' => $lang['CMS_Reviewer'],
		'5' => $lang['CMS_Content_Manager']
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

	include('./page_header_admin.' . $phpEx);

	$template->set_filenames(array('body' => ADM_TPL . 'auth_cms_body.tpl'));

	$template->assign_vars(array(
		'USERNAME' => $username,
		'USER_LEVEL' => '<b>' . $lang['User_Level'] . '</b>: ' . $s_user_type,
		'L_USER' => $lang['Username'],
		'L_AUTH_TITLE' => $lang['Auth_Control_User'],
		'L_PERMISSIONS' => $lang['Permissions'],
		'L_SUBMIT' => $lang['Submit'],
		'L_RESET' => $lang['Reset'],
		'U_USER' => append_sid('admin_cms_auth.' . $phpEx),
		'S_AUTH_ACTION' => append_sid('admin_cms_auth.' . $phpEx),
		'S_HIDDEN_FIELDS' => $s_hidden_fields
		)
	);
}
else
{
	// Select a user/group
	include('./page_header_admin.' . $phpEx);

	$template->set_filenames(array('body' => ADM_TPL . 'user_select_body.tpl'));
	$template->assign_vars(array(
		'L_FIND_USERNAME' => $lang['Find_username'],
		'U_SEARCH_USER' => append_sid('../' . SEARCH_MG . '?mode=searchuser')
		)
	);

	$s_hidden_fields = '<input type="hidden" name="mode" value="user" />';
	$l_type = 'USER';

	$template->assign_vars(array(
		'L_LOOK_UP' => $lang['Look_up_User'],
		'S_HIDDEN_FIELDS' => $s_hidden_fields,
		'S_' . $l_type . '_ACTION' => append_sid('admin_cms_auth.' . $phpEx))
	);
}

$template->pparse('body');

include('./page_footer_admin.' . $phpEx);

?>