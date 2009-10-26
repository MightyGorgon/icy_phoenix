<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

// start template
$template->assign_vars(array(
	'L_AVPOSTS' => $lang['Average_Posts'],
	'L_TITLE' => $lang['module_name_average_posts_per_user']
	)
);

// get total posts
$sql = "SELECT COUNT(post_id) as total_posts FROM " . POSTS_TABLE;
$result = $stat_db->sql_query($sql);
$row = $stat_db->sql_fetchrow($result);
$total_posts = $row['total_posts'];

// get total users
$sql = "SELECT COUNT(user_id) as total_users FROM " . USERS_TABLE;
$result = $stat_db->sql_query($sql);
$row = $stat_db->sql_fetchrow($result);
$total_users = $row['total_users'];

$avposts = round($total_posts / $total_users);

$class = $theme['td_class1'];

$template->assign_block_vars('av_posts', array(
	'CLASS' => $class,
	'AVPOSTS' => $avposts
	)
);

?>