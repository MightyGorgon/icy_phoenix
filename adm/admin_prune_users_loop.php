<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

// CTracker_Ignore: File checked by human
define('IN_PHPBB', true);
define('IN_ADMIN', true);
// to enable email notification to the user, after deletion, enable this
define('NOTIFY_USERS', true);
// to disable confirmation when executing PRUNE_MG
define('KILL_CONFIRM', false);

if (empty($_POST['mode']) && empty($_GET['mode']))
{
	return;
}

$phpbb_root_path = './../';
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);
include($phpbb_root_path . 'includes/digest_constants.' . $phpEx);
include($phpbb_root_path . 'includes/functions_mg_users.' . $phpEx);
include($phpbb_root_path . 'includes/emailer.' . $phpEx);

@set_time_limit(180);

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

if ($userdata['user_level'] != ADMIN)
{
	message_die(GENERAL_ERROR, $lang['Not_Authorised']);
}

$del_user = isset($_POST['del_user']) ? intval($_POST['del_user']) : (isset($_GET['del_user']) ? intval($_GET['del_user']) : '');
$mode = isset($_POST['mode']) ? $_POST['mode'] : (isset($_GET['mode']) ? $_GET['mode'] : '');
$days = isset($_POST['days']) ? intval($_POST['days']) : (isset($_GET['days']) ? intval($_GET['days']) : '');

if ($mode == 'prune_mg')
{
	$users_number = isset($_GET['users_number']) ? intval($_GET['users_number']) : intval($_POST['users_number']);
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
	$s_hidden_fields .= '<input type="hidden" name="ref_url" value="' . htmlspecialchars($ref_url[count($ref_url) - 1]) . '" />';
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

		'S_CONFIRM_ACTION' => append_sid('admin_prune_users_loop.' . $phpEx),
		'S_HIDDEN_FIELDS' => $s_hidden_fields
		)
	);
	$template->pparse('confirm');
	include('./page_footer_admin.' . $phpEx);
	exit();
}

// Recall kill script!
include($phpbb_root_path . 'includes/users_delete_inc.' . $phpEx);

$message = '<b>Mode</b>: [ <span class="topic_glo">' . $mode_des . '</span> ]<br />' . (($i) ? sprintf($lang['Prune_users_number'], $i) . $name_list : $lang['Prune_no_users']);

if (($mode == 'prune_mg') && ($users_number == $i))
{
	$template->assign_vars(array(
		'META' => '<meta http-equiv="refresh" content="3;url=' . append_sid('delete_users.' . $phpEx . '?mode=' . $mode . '&amp;users_number=' . $users_number . '&amp;days=' . $days) . '">'
		)
	);
	$message = '<span class="topic_glo">IN PROGRESS...</span><br /><br />' . $message;
}
else
{
	$message .= '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . $phpEx) . '">', '</a>');
}

message_die(GENERAL_MESSAGE, $message);

?>