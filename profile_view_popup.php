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
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_groups.' . PHP_EXT);

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

$gen_simple_header = true;
$page_title = $lang['Profile'] . ' - ' . $lang['Views'];
$meta_description = '';
$meta_keywords = '';
include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);

$current_time = time();
$user_id = $userdata['user_id'];
$last_view =$userdata['user_last_profile_view'];

$template->set_filenames(array('body' => 'profile_view_popup_body.tpl'));

$sql = "SELECT * FROM ".PROFILE_VIEW_TABLE."
		WHERE user_id = ".$user_id."
		AND view_stamp >= ".$last_view."
		ORDER BY view_stamp DESC";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, "Could not read profile views.", '', __LINE__, __FILE__, $sql);
	}

	while ($row=$db->sql_fetchrow($result))
	{
		$viewer=$row['viewer_id'];
		$template->assign_block_vars('row', array(
			'VIEW_BY' => colorize_username($viewer),
			'STAMP' => create_date2($userdata['user_dateformat'], $row['view_stamp'], $userdata['user_timezone'])
			)
		);
	}

$template->assign_vars(array(
	'PROFILE' => '<a href="'.append_sid(PROFILE_MG . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $user_id).'" target="_new" class="nav-current">'.$userdata['username'].'</a>',
	'L_VIEW_TITLE' => $page_title,
	'L_CLOSE' => $lang['Close_window'],
	'L_VIEWER' => $lang['Username'],
	'L_STAMP' => $lang['Last_updated']
	)
);

$sql = "UPDATE " . USERS_TABLE . "
		SET user_profile_view = '0', user_last_profile_view = '$current_time'
		WHERE user_id = " . $user_id;
	if ( !$db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, "Could not update user data.", '', __LINE__, __FILE__, $sql);
	}

$template->pparse('body');

include(IP_ROOT_PATH . 'includes/page_tail.' . PHP_EXT);

?>