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

$page_title = $lang['Profile'] . ' - ' . $lang['Views'];
$meta_description = '';
$meta_keywords = '';
$link_name = $lang['Profile_viewed'];
$nav_server_url = create_server_url();
$breadcrumbs_address = $lang['Nav_Separator'] . '<a href="' . $nav_server_url . append_sid('profile_main.' . PHP_EXT) . '"' . (!empty($link_name) ? '' : ' class="nav-current"') . '>' . $lang['Profile'] . '</a>' . (!empty($link_name) ? ($lang['Nav_Separator'] . '<a class="nav-current" href="#">' . $link_name . '</a>') : '');
include_once(IP_ROOT_PATH . 'includes/users_zebra_block.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);

$user_id = (isset($_POST[POST_USERS_URL])) ? intval($_POST[POST_USERS_URL]) : intval($_GET[POST_USERS_URL]);
$page_start = isset($_GET['start']) ? intval($_GET['start']) : (isset($_POST['start']) ? intval($_POST['start']) : 0);
$page_start = ($page_start < 0) ? 0 : $page_start;

$template->set_filenames(array('body' => 'profile_view_user_body.tpl'));

$sql = "SELECT username FROM " . USERS_TABLE . "
				WHERE user_id = '" . $user_id . "'";
if (!($result = $db->sql_query($sql)))
{
	message_die(GENERAL_ERROR, "Could not read userdata.", '', __LINE__, __FILE__, $sql);
}
$profile = $db->sql_fetchrow($result);
if (!is_array($profile))
{
	message_die(GENERAL_ERROR, "Unknown User-ID!!!", '', __LINE__, __FILE__, $sql);
}

$sql = "SELECT p.*, u.user_avatar_type, u.user_allowavatar, u.user_avatar
				FROM " . PROFILE_VIEW_TABLE . " p, " . USERS_TABLE . " u
				WHERE p.viewer_id = u.user_id
					AND p.user_id = " . $user_id;
if (!($result = $db->sql_query($sql)))
{
	message_die(GENERAL_ERROR, "Could not read profile views.", '', __LINE__, __FILE__, $sql);
}
$total = $db->sql_numrows($result);
$db->sql_freeresult($result);

$pagination = generate_pagination('profile_view_user.' . PHP_EXT . '?' . POST_USERS_URL . '=' . $user_id, $total, $board_config['posts_per_page'], $page_start);

$sql = "SELECT p.*, u.username, u.user_active, u.user_color, u.user_level, u.user_avatar_type, u.user_allowavatar, u.user_avatar
				FROM " . PROFILE_VIEW_TABLE . " p, " . USERS_TABLE . " u
				WHERE p.viewer_id = u.user_id
					AND p.user_id = " . $user_id . "
				ORDER BY p.view_stamp DESC
				LIMIT " . $page_start . ", " . $board_config['posts_per_page'];
if (!($result = $db->sql_query($sql)))
{
	message_die(GENERAL_ERROR, "Could not read profile views.", '', __LINE__, __FILE__, $sql);
}

while ($row = $db->sql_fetchrow($result))
{
	$viewer = $row['viewer_id'];
	$viewer_avatar = user_get_avatar($row['viewer_id'], $row['user_level'], $row['user_avatar'], $row['user_avatar_type'], $row['user_allowavatar']);
	$template->assign_block_vars('row', array(
		'AVATAR' => $viewer_avatar,
		'VIEW_BY' => colorize_username($viewer, $row['username'], $row['user_color'], $row['user_active']),
		'NUMBER' => $row['counter'],
		'STAMP' => create_date2($userdata['user_dateformat'], $row['view_stamp'], $userdata['user_timezone'])
		)
	);
}

$template->assign_vars(array(
	'PAGINATION' => $pagination,
	'PROFILE' => '<a href="' . append_sid(PROFILE_MG . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $user_id) . '" class="nav-current">' . $profile['username'] . '</a>',
	'L_VIEW_TITLE' => $page_title,
	'L_VIEWER' => $lang['Username'],
	'L_NUMBER' => $lang['Views'],
	'L_STAMP' => $lang['Last_updated']
	)
);

$template->pparse('body');
include (IP_ROOT_PATH . 'includes/page_tail.' . PHP_EXT);

?>