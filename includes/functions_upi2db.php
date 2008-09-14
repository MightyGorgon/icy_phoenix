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
* BigRib (bigrib@gmx.de)
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

//################################### include AddOn Modules ##########################################
//Removed by Mighty Gorgon
/*
$dir = @opendir(UPI2DB_PATH);

while( $file = @readdir($dir) )
{
	if( preg_match("/^upi2db_add_.*?\." . PHP_EXT . "$/", $file) )
	{
		include(UPI2DB_PATH . '/' . $file);
	}
}

@closedir($dir);
*/

//################################### include Orig Modules ##########################################
//Modified by Mighty Gorgon

define('UPI2DB_VERSION', 'Full');
//define('UPI2DB_VERSION', 'IP');

if(UPI2DB_VERSION == 'IP')
{
	include(UPI2DB_PATH . '/upi2db_orig_ip.' . PHP_EXT);
}

include(UPI2DB_PATH . '/upi2db_orig_full.' . PHP_EXT);
include(UPI2DB_PATH . '/upi2db_orig_all.' . PHP_EXT);

//################################### check_condition ##########################################
function check_group_auth($userdata)
{
	global $board_config, $db;

	if(!$userdata['session_logged_in'])
	{
		return false;
	}

	$no_group_upi2db_on = $board_config['upi2db_no_group_upi2db_on'];
	$no_group_min_posts = $board_config['upi2db_no_group_min_posts'];
	$no_group_min_regdays = $board_config['upi2db_no_group_min_regdays'];

	$user_min_posts = $userdata['user_posts'];
	$user_min_regdays  = floor((time() - $userdata['user_regdate']) / 86400);

	$check_user_upi2db_on = FALSE;
	$count_user_in_groups = 0;
	$count_groups = 0;

	$sql = "SELECT g.upi2db_on, g.upi2db_min_posts, g.upi2db_min_regdays
		FROM " . GROUPS_TABLE . " g, " . USER_GROUP_TABLE . " ug
			WHERE ug.group_id = g.group_id
			AND g.group_single_user <> " . TRUE . "
			AND ug.user_pending <> ".TRUE . "
			AND ug.user_id = " . $userdata['user_id'] . "
			GROUP BY g.group_id";
	if ($result = $db->sql_query($sql) )
	{
		while( $row = $db->sql_fetchrow($result) )
		{
			$group_access[] = $row;
		}
	}
	$db->sql_freeresult($result);

	if ( empty($group_access) )
	{
		if ( $no_group_upi2db_on == 1 && $no_group_min_posts <= $user_min_posts && $no_group_min_regdays <= $user_min_regdays )
		{
			return true;
		}
	}
	else
	{
		for($i = 0; $i < count($group_access); $i++)
		{
			if($group_access[$i]['upi2db_on'] == '1' && $group_access[$i]['upi2db_min_posts'] <= $user_min_posts && $group_access[$i]['upi2db_min_regdays'] <= $user_min_regdays)
			{
				return true;
			}
		}
	}
	return false;
}

//################################### check_is_upi2db_on ##########################################
function check_upi2db_on($userdata)
{
	global $board_config;
	$user_upi2db_on = $userdata['user_upi2db_which_system'];
	$user_upi2db_disable = $userdata['user_upi2db_disable'];
	$admin_upi2db_on = $board_config['upi2db_on'];

	if($board_config['board_disable'] || $user_upi2db_disable || !$userdata['session_logged_in'] || !$admin_upi2db_on )
	{
		return false;
	}
	elseif(($admin_upi2db_on == 1) || (($admin_upi2db_on == 2) && ($user_upi2db_on == 1)))
	{
		return check_group_auth($userdata);
	}
	return false;
}

/**
* added by BigRib (C) 2006 for UPI2DB 3
*/
function display_new_txt($unread)
{
	global $lang, $images, $board_config, $unread_new_posts, $unread_edit_posts;

	$edit_posts = count($unread['edit_posts']) - $unread_edit_posts;
	$new_posts = count($unread['new_posts']) - $unread_new_posts;
	$unread_posts = $new_posts + $edit_posts;
	$always_read = count($unread['always_read']['topics']);
	$mark_unread = count($unread['mark_posts']);

	$max_perm_read = $board_config['upi2db_max_permanent_topics'];
	$max_mark = $board_config['upi2db_max_mark_posts'];

	$u_display_new = ($new_posts) ? ' <a href="' . append_sid(SEARCH_MG.'?search_id=upi2db&s=new') . '" class="mainmenu" >' . $lang['Neue_Beitraege'] . ' (' . $new_posts . ')</a> ' : '';
	$u_display_new .= ($edit_posts) ? ' <a href="' . append_sid(SEARCH_MG.'?search_id=upi2db&s=new') . '" class="mainmenu" >' . $lang['Editierte_Beitraege'] . ' (' . $edit_posts . ')</a> ' : '';
	$u_display_new .= ($mark_unread) ? ' <a href="' . append_sid(SEARCH_MG.'?search_id=upi2db&s=mark') . '" class="mainmenu" >' . $lang['Ungelesen_Markiert'] . ' (' . $mark_unread . '/' . $max_mark .')</a>' : '';
	$u_display_new .= ($always_read) ? ' <a href="' . append_sid(SEARCH_MG.'?search_id=upi2db&s=perm') . '" class="mainmenu" >' . $lang['Permanent_Gelesen'] . ' (' . $always_read . '/' . $max_perm_read .')</a> ' : '';

	return $u_display_new;
}

/**
* added by OXPUS (C) 2006 for UPI2DB 3
*/
function board_display_new($unread)
{
	global $lang, $images, $board_config, $unread_new_posts, $unread_edit_posts;

	$count_edit_posts = count($unread['edit_posts']);
	$count_new_posts = count($unread['new_posts']);
	$count_unread_posts = $count_new_posts + $count_edit_posts;
	$count_always_read = count($unread['always_read']['topics']);
	$count_mark_unread = count($unread['mark_posts']);

	$max_perm_read = $board_config['upi2db_max_permanent_topics'];
	$max_mark = $board_config['upi2db_max_mark_posts'];

	$u_display_new = '';
	$u_display_new .= ($count_new_posts) ? $count_new_posts . ' ' . $lang['Neue_Beitraege'] : '';
	$u_display_new .= ($u_display_new && $count_edit_posts) ? ' | ' : '';
	$u_display_new .= ($count_edit_posts) ? $count_edit_posts . ' ' . $lang['Editierte_Beitraege'] : '';
	$u_display_new .= ($u_display_new && $count_mark_unread) ? ' | ' : '';
	$u_display_new .= ($count_mark_unread) ? $count_mark_unread . '/' . $max_mark . ' ' . $lang['Ungelesen_Markiert'] : '';
	$u_display_new .= ($u_display_new && $count_always_read) ? ' | ' : '';
	$u_display_new .= ($count_always_read) ? $count_always_read . '/' . $max_perm_read . ' ' . $lang['Permanent_Gelesen'] : '';

	$u_display_new = ($u_display_new) ? ' | '.$u_display_new : '';

	return $u_display_new;
}

?>