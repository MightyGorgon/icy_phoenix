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

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

$current_time = time();
$user_id = $userdata['user_id'];
$last_view = $userdata['user_last_profile_view'];

$sql = "SELECT p.*, u.username, u.user_active, u.user_color
		FROM " . PROFILE_VIEW_TABLE . " p, " . USERS_TABLE . " u
		WHERE p.user_id = " . $user_id . "
			AND p.view_stamp >= " . $last_view . "
			AND u.user_id = p.viewer_id
		ORDER BY p.view_stamp DESC";
$result = $db->sql_query($sql);

while ($row = $db->sql_fetchrow($result))
{
	$viewer = $row['viewer_id'];
	$template->assign_block_vars('row', array(
		'VIEW_BY' => colorize_username($viewer, $row['username'], $row['user_color'], $row['user_active']),
		'STAMP' => create_date_ip($userdata['user_dateformat'], $row['view_stamp'], $userdata['user_timezone'])
		)
	);
}

$template->assign_vars(array(
	'PROFILE' => '<a href="'.append_sid(CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $user_id).'" target="_new" class="nav-current">'.$userdata['username'].'</a>',
	'L_VIEW_TITLE' => $meta_content['page_title'],
	'L_CLOSE' => $lang['Close_window'],
	'L_VIEWER' => $lang['Username'],
	'L_STAMP' => $lang['Last_updated']
	)
);

$sql = "UPDATE " . USERS_TABLE . "
		SET user_profile_view = '0', user_last_profile_view = '$current_time'
		WHERE user_id = " . $user_id;
$db->sql_query($sql);

$gen_simple_header = true;
full_page_generation('profile_view_popup_body.tpl', $lang['Profile'] . ' - ' . $lang['Views'], '', '');

?>