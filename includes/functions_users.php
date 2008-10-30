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

/*
* Top X Posters
*/
function top_posters($user_limit, $show_admin, $show_mod)
{
	global $db;
	if ( ($show_admin == true) && ($show_mod == true) )
	{
		$sql_level = "";
	}
	elseif ($show_admin == true)
	{
		$sql_level = "AND u.user_level IN (" . JUNIOR_ADMIN . ", " . ADMIN . ")";
	}
	elseif ($show_mod == true)
	{
		$sql_level = "AND u.user_level IN (" . USER . ", " . MOD . ")";
	}
	else
	{
		$sql_level = "AND u.user_level = " . USER;
	}
	$sql = "SELECT u.username, u.user_id, u.user_posts, u.user_level
	FROM " . USERS_TABLE . " u
	WHERE (u.user_id <> " . ANONYMOUS . ")
	" . $sql_level . "
	ORDER BY u.user_posts DESC
	LIMIT " . $user_limit;
	if (!($result = $db->sql_query($sql, false, 'top_posters_')))
	{
		message_die(GENERAL_ERROR, 'Could not query forum top poster information', '', __LINE__, __FILE__, $SQL);
	}
	$top_posters = '';
	while($row = $db->sql_fetchrow($result))
	{
		$top_posters .= ' ' . colorize_username($row['user_id']) . '(' . $row['user_posts'] . ') ';
	}
	return $top_posters;
}

?>