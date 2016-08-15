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
* Jamer (Colin James) - (http://www.jamer.co.uk/scripts/phpbb2)
*
*/

define('IN_ICYPHOENIX', true);
if(!empty($setmodules))
{
	$filename = basename(__FILE__);
	$module['1610_Users']['140_Email_List'] = append_sid($filename);
	return;
}

// Load default header
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('pagestart.' . PHP_EXT);

$start = request_var('start', 0);
$start = ($start < 0) ? 0 : $start;

$show = request_var('show', $config['topics_per_page']);

// Generate page
$template->set_filenames(array('body' => ADM_TPL . 'admin_users_email_list_body.tpl'));

$template->assign_vars(array(
	'L_ADMIN_USERS_LIST_MAIL_TITLE' => $lang['Admin_Users_List_Mail_Title'],
	'L_ADMIN_USERS_LIST_MAIL_EXPLAIN' => $lang['Admin_Users_List_Mail_Explain'],
	'L_USERNAME' => $lang['Usersname'],
	'L_EMAIL' => $lang['Email']
	)
);

$sql = "SELECT user_id, username, user_active, user_color, user_email, user_first_name, user_last_name FROM " . USERS_TABLE . "
				WHERE user_id <> " . ANONYMOUS . "
				ORDER BY username ASC
				LIMIT $start, $show";
$result = $db->sql_query($sql);

$i = 0;
while($row = $db->sql_fetchrow($result))
{
	$i++;
	$row_color = (($i % 2) == 0) ? 'row1' : 'row2';

	$user_full_name = (!empty($row['user_first_name']) ? $row['user_first_name'] : '') . (!empty($row['user_last_name']) ? ((!empty($row['user_first_name']) ? ' ' : '')) . $row['user_last_name'] : '');
	$template->assign_block_vars('userrow', array(
		'COLOR' => $row_color,
		'NUMBER' => ($start + $i + 1),
		'USERNAME' => colorize_username($row['user_id'], $row['username'], $row['user_color'], $row['user_active']),
		'USER_FIRST_NAME' => htmlspecialchars($row['user_first_name']),
		'USER_LAST_NAME' => htmlspecialchars($row['user_last_name']),
		'USER_FULL_NAME' => $user_full_name,
		'U_ADMIN_USER' => append_sid('admin_users.' . PHP_EXT . '?mode=edit&amp;' . POST_USERS_URL . '=' . $row['user_id']),
		'EMAIL' => $row['user_email']
		)
	);
}
$db->sql_freeresult($result);

$count_sql = "SELECT count(user_id) AS total
	FROM " . USERS_TABLE . "
	WHERE user_id <> " . ANONYMOUS;
$count_result = $db->sql_query($count_sql);
if ($total = $db->sql_fetchrow($count_result))
{
	$total_members = $total['total'];
	$pagination = generate_pagination(IP_ROOT_PATH . ADM . '/admin_email_list.' . PHP_EXT . '?show=' . $show, $total_members, $show, $start);
}

$template->assign_vars(array(
	'PAGINATION' => $pagination,
	'PAGE_NUMBER' => sprintf($lang['Page_of'], (floor($start / $show) + 1), ceil($total_members / $show))
	)
);

$template->pparse('body');
include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);

?>