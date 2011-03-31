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

define('CTRACKER_DISABLED', true);
// to enable email notification to the user, after deletion, enable this
define('NOTIFY_USERS', true);
// to disable confirmation when executing PRUNE_MG
define('KILL_CONFIRM', false);
define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/digest_constants.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_users_delete.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/emailer.' . PHP_EXT);

@set_time_limit(180);

// Start session management
$user->session_begin();
//$auth->acl($user->data);
$user->setup();
// End session management

$sql = 'SELECT user_level FROM ' . USERS_TABLE . ' WHERE user_id="' . $user->data['user_id'] . '" LIMIT 1';
$result = $db->sql_query($sql);
$user_row = $db->sql_fetchrow($result);
$db->sql_freeresult($result);

if ($user_row['user_level'] != ADMIN)
{
	message_die(GENERAL_ERROR, $lang['Not_Authorized']);
}

$del_user = request_var('del_user', 0);
$del_user = ($del_user < 2) ? 0 : $del_user;

$mode = request_var('mode', '');
$days = request_var('days', 0);

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
	$s_hidden_fields .= '<input type="hidden" name="ref_url" value="' . htmlspecialchars($ref_url[sizeof($ref_url) - 1]) . '" />';
	$s_hidden_fields .= '<input type="hidden" name="del_user" value="' . $del_user . '" />';
	$s_hidden_fields .= '<input type="hidden" name="mode" value="' . $mode . '" />';
	$s_hidden_fields .= '<input type="hidden" name="days" value="' . $days . '" />';

	$template->assign_vars(array(
		'MESSAGE_TITLE' => $lang['Confirm'],
		'MESSAGE_TEXT' => $lang['Confirm_delete_user'],

		'L_YES' => $lang['Yes'],
		'L_NO' => $lang['No'],

		'S_CONFIRM_ACTION' => append_sid('delete_users.' . PHP_EXT),
		'S_HIDDEN_FIELDS' => $s_hidden_fields
		)
	);
	full_page_generation('confirm_body.tpl', $lang['Confirm'], '', '');
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
	$message .= '<br /><br />' . sprintf($lang['Click_return_forum'], '<a href="' . append_sid(CMS_PAGE_FORUM) . '">', '</a>');
}

message_die(GENERAL_MESSAGE, $message);

?>