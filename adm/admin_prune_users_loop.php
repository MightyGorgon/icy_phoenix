<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

define('IN_ADMIN', true);
// to enable email notification to the user, after deletion, enable this
define('NOTIFY_USERS', true);
// to disable confirmation when executing PRUNE_MG
define('KILL_CONFIRM', false);

define('IN_ICYPHOENIX', true);

if (empty($_POST['mode']) && empty($_GET['mode']))
{
	return;
}

if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('pagestart.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/digest_constants.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/functions_users_delete.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/emailer.' . PHP_EXT);

@set_time_limit(180);

// Start session management
$user->session_begin();
//$auth->acl($user->data);
$user->setup();
// End session management

if ($user->data['user_level'] != ADMIN)
{
	message_die(GENERAL_ERROR, $lang['Not_Authorized']);
}

$mode = request_var('mode', '');
$days = request_var('days', 0);
$del_user = request_var('del_user', 0);

if ($mode == 'prune_mg')
{
	$users_number = request_var('users_number', 0);
	$users_number = ($users_number == 0) ? '50' : $users_number;
}

if(isset($_POST['cancel']))
{
	redirect(append_sid($_POST['ref_url'], true));
}

if(!isset($_POST['confirm']) && !KILL_CONFIRM)
{
	$ref_url = explode('/', $_SERVER['HTTP_REFERER']);

	$s_hidden_fields = '';
	$s_hidden_fields .= '<input type="hidden" name="ref_url" value="' . htmlspecialchars(end($ref_url)) . '" />';
	$s_hidden_fields .= '<input type="hidden" name="del_user" value="' . $del_user . '" />';
	$s_hidden_fields .= '<input type="hidden" name="mode" value="' . $mode . '" />';
	$s_hidden_fields .= '<input type="hidden" name="days" value="' . $days . '" />';

	// Set template files
	$template->set_filenames(array('confirm' => ADM_TPL . 'confirm_body.tpl'));

	$template->assign_vars(array(
		'MESSAGE_TITLE' => $lang['Confirm'],
		'MESSAGE_TEXT' => $lang['Account_delete_users'],

		'L_YES' => $lang['Yes'],
		'L_NO' => $lang['No'],

		'S_CONFIRM_ACTION' => append_sid('admin_prune_users_loop.' . PHP_EXT),
		'S_HIDDEN_FIELDS' => $s_hidden_fields
		)
	);
	$template->pparse('confirm');
	include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);
	exit();
}

// Recall kill script!
include(IP_ROOT_PATH . 'includes/users_delete_inc.' . PHP_EXT);

$message = '<b>Mode</b>: [ <span class="topic_glo">' . $mode_des . '</span> ]<br />' . (($i) ? sprintf($lang['Prune_users_number'], $i) . $name_list : $lang['Prune_no_users']);

if (($mode == 'prune_mg') && ($users_number == $i))
{
	$redirect_url = append_sid('delete_users.' . PHP_EXT . '?mode=' . $mode . '&amp;users_number=' . $users_number . '&amp;days=' . $days);
	meta_refresh(3, $redirect_url);
	$message = '<span class="topic_glo">' . $lang['InProgress'] . '</span><br /><br />' . $message;
}
else
{
	$message .= '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT) . '">', '</a>');
}

message_die(GENERAL_MESSAGE, $message);

?>