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
* Niels Chr. Rød (ncr@db9.dk) - (http://mods.db9.dk)
*
*/

/*
#########################################################
## phpBB2 drop-in mod, that checks for unused accounts for X days use the script while logged in as ADMIN, add the days=X as a extra parameter
##   e.g. delete_users.php?mode=not_login&days=10 will delete all accounts who have never logged in and are older than 10 days
##
## And zero postes
##   e.g. delete_users.php?mode=zero_poster&days=10 will delete all accounts who have never posted and are older than 10 days
##
## You can also delete specific users
##   e.g. delete_users.php?mode=user_name&del_user=Niels
##   or delete_users.php?mode=user_id&del_user=18
## Will delete a specific user either by name or by id, remember that is is NOT case sensitive
## if the user have posted, then his/her posts will be converted to posted by guest, and the users
## name will still be shown
##
#########################################################
## Added by Mighty Gorgon:
## Possibility to recall it directly from command line with progress status.
##
## First set this
## define('KILL_CONFIRM', true);
##
## Then recall this address
## delete_users.php?mode=prune_mg&users_number=50&days=360
##
#########################################################
*/

// CTracker_Ignore: File Checked By Human
define('MG_KILL_CTRACK', true);
// to enable email notification to the user, after deletion, enable this
define('NOTIFY_USERS', true);
// to disable confirmation when executing PRUNE_MG
define('KILL_CONFIRM', false);
define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.' . $phpEx);
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
	$page_title = $lang['Home'];
	$meta_description = '';
	$meta_keywords = '';
	include($phpbb_root_path . 'includes/page_header.' . $phpEx);

	$ref_url = explode('/', $_SERVER['HTTP_REFERER']);

	$s_hidden_fields = '';
	$s_hidden_fields .= '<input type="hidden" name="ref_url" value="' . htmlspecialchars($ref_url[count($ref_url) - 1]) . '" />';
	$s_hidden_fields .= '<input type="hidden" name="del_user" value="' . $del_user . '" />';
	$s_hidden_fields .= '<input type="hidden" name="mode" value="' . $mode . '" />';
	$s_hidden_fields .= '<input type="hidden" name="days" value="' . $days . '" />';

	// Set template files
	$template->set_filenames(array('confirm' => 'confirm_body.tpl'));

	$template->assign_vars(array(
		'MESSAGE_TITLE' => $lang['Confirm'],
		'MESSAGE_TEXT' => $lang['Confirm_delete_user'],

		'L_YES' => $lang['Yes'],
		'L_NO' => $lang['No'],

		'S_CONFIRM_ACTION' => append_sid('delete_users.' . $phpEx),
		'S_HIDDEN_FIELDS' => $s_hidden_fields
		)
	);
	$template->pparse('confirm');
	include($phpbb_root_path . 'includes/page_tail.' . $phpEx);
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
	$message .= '<br /><br />' . sprintf($lang['Click_return_forum'], '<a href="' . append_sid(FORUM_MG) . '">', '</a>');
}

message_die(GENERAL_MESSAGE, $message);

?>