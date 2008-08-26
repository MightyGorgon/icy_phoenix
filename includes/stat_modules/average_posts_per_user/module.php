<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

if (!defined('IN_PHPBB'))
{
	die('Hacking attempt');
}

// setup

// start template
$template->assign_vars(array(
	'L_AVPOSTS' => $lang['Average_Posts'],
	'L_TITLE' => $lang['module_name_average_posts_per_user']
	)
);

// get total posts
$sql = "SELECT COUNT(post_id) as total_posts FROM " . POSTS_TABLE;

if (!($result = $stat_db->sql_query($sql)))
{
	message_die(GENERAL_ERROR, 'Unable to retrieve posts data', '', __LINE__, __FILE__, $sql);
}

$row = $stat_db->sql_fetchrow($result);
$total_posts = $row['total_posts'];

// get total users
$sql = "SELECT COUNT(user_id) as total_users FROM " .USERS_TABLE;

if (!($result = $stat_db->sql_query($sql)))
{
	message_die(GENERAL_ERROR, 'Unable to retrieve users data', '', __LINE__, __FILE__, $sql);
}
$row = $stat_db->sql_fetchrow($result);
$total_users = $row['total_users'];

$avposts = round($total_posts/$total_users);

$template->assign_block_vars('av_posts', array(
	'CLASS' => $class,
	'AVPOSTS' => $avposts
	)
);
?>