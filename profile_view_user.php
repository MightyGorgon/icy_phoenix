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
$user->session_begin();
$auth->acl($user->data);
$user->setup();
// End session management

$link_name = $lang['Profile_viewed'];
$nav_server_url = create_server_url();
$breadcrumbs['address'] = $lang['Nav_Separator'] . '<a href="' . $nav_server_url . append_sid(CMS_PAGE_PROFILE_MAIN) . '"' . (!empty($link_name) ? '' : ' class="nav-current"') . '>' . $lang['Profile'] . '</a>' . (!empty($link_name) ? ($lang['Nav_Separator'] . '<a class="nav-current" href="#">' . $link_name . '</a>') : '');
include_once(IP_ROOT_PATH . 'includes/users_zebra_block.' . PHP_EXT);

$user_id = request_var(POST_USERS_URL, 0);
$start = request_var('start', 0);
$start = ($start < 0) ? 0 : $start;

$sql = "SELECT username FROM " . USERS_TABLE . "
				WHERE user_id = '" . $user_id . "'";
$result = $db->sql_query($sql);
$profile = $db->sql_fetchrow($result);
if (!is_array($profile))
{
	message_die(GENERAL_ERROR, "Unknown User-ID!!!", '', __LINE__, __FILE__, $sql);
}

$sql = "SELECT p.*, u.user_avatar_type, u.user_allowavatar, u.user_avatar
				FROM " . PROFILE_VIEW_TABLE . " p, " . USERS_TABLE . " u
				WHERE p.viewer_id = u.user_id
					AND p.user_id = " . $user_id;
$result = $db->sql_query($sql);
$total = $db->sql_numrows($result);
$db->sql_freeresult($result);

$sql = "SELECT p.*, u.username, u.user_active, u.user_color, u.user_level, u.user_avatar_type, u.user_allowavatar, u.user_avatar
				FROM " . PROFILE_VIEW_TABLE . " p, " . USERS_TABLE . " u
				WHERE p.viewer_id = u.user_id
					AND p.user_id = " . $user_id . "
				ORDER BY p.view_stamp DESC
				LIMIT " . $start . ", " . $config['posts_per_page'];
$result = $db->sql_query($sql);

while ($row = $db->sql_fetchrow($result))
{
	$viewer = $row['viewer_id'];
	$viewer_avatar = user_get_avatar($row['viewer_id'], $row['user_level'], $row['user_avatar'], $row['user_avatar_type'], $row['user_allowavatar']);
	$template->assign_block_vars('row', array(
		'AVATAR' => $viewer_avatar,
		'VIEW_BY' => colorize_username($viewer, $row['username'], $row['user_color'], $row['user_active']),
		'NUMBER' => $row['counter'],
		'STAMP' => create_date_ip($user->data['user_dateformat'], $row['view_stamp'], $user->data['user_timezone'])
		)
	);
}

$template->assign_vars(array(
	'PAGINATION' => generate_pagination('profile_view_user.' . PHP_EXT . '?' . POST_USERS_URL . '=' . $user_id, $total, $config['posts_per_page'], $start),
	'PROFILE' => '<a href="' . append_sid(CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $user_id) . '" class="nav-current">' . $profile['username'] . '</a>',
	'L_VIEW_TITLE' => $meta_content['page_title'],
	'L_VIEWER' => $lang['Username'],
	'L_NUMBER' => $lang['Views'],
	'L_STAMP' => $lang['Last_updated']
	)
);

full_page_generation('profile_view_user_body.tpl', $lang['Profile'] . ' - ' . $lang['Views'], '', '');

?>
