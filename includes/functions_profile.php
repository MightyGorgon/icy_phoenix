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

// Add function mkrealdate for Birthday MOD
// the originate php "mktime()", does not work proberly on all OS, especially when going back in time
// before year 1970 (year 0), this function "mkrealtime()", has a mutch larger valid date range,
// from 1901 - 2099. it returns a "like" UNIX timestamp divided by 86400, so
// calculation from the originate php date and mktime is easy.
// mkrealdate, returns the number of day (with sign) from 1.1.1970.

function mkrealdate($day, $month, $birth_year)
{
	// range check months
	if ($month < 1 || $month > 12)
	{
		return "error";
	}
	// range check days
	switch ($month)
	{
		case 1:
			if ($day > 31) return "error";
			break;
		case 2:
			if ($day > 29) return "error";
			$epoch = $epoch + 31;
			break;
		case 3:
			if ($day > 31) return "error";
			$epoch = $epoch + 59;
			break;
		case 4:
			if ($day > 30) return "error" ;
			$epoch = $epoch + 90;
			break;
		case 5:
			if ($day > 31) return "error";
			$epoch = $epoch + 120;
			break;
		case 6:
			if ($day > 30) return "error";
			$epoch = $epoch + 151;
			break;
		case 7:
			if ($day > 31) return "error";
			$epoch = $epoch + 181;
			break;
		case 8:
			if ($day > 31) return "error";
			$epoch = $epoch + 212;
			break;
		case 9:
			if ($day > 30) return "error";
			$epoch = $epoch + 243;
			break;
		case 10:
			if ($day > 31) return "error";
			$epoch = $epoch + 273;
			break;
		case 11:
			if ($day > 30) return "error";
			$epoch = $epoch + 304;
			break;
		case 12:
			if ($day > 31) return "error";
			$epoch = $epoch + 334;
			break;
	}
	$epoch = $epoch + $day;
	$epoch_Y = sqrt(($birth_year - 1970)*($birth_year - 1970));
	$leapyear = round((($epoch_Y + 2) / 4) - .5);
	if (($epoch_Y + 2) % 4 == 0)
	{// curent year is leapyear
		$leapyear--;
		if ($birth_year > 1970 && $month >= 3)
		{
			$epoch = $epoch + 1;
		}
		if ($birth_year < 1970 && $month < 3)
		{
			$epoch = $epoch - 1;
		}
	}
	elseif ($month == 2 && $day > 28)
	{
		return "error";//only 28 days in feb.
	}
	//year
	if ($birth_year > 1970)
	{
		$epoch = $epoch + $epoch_Y * 365 - 1 + $leapyear;
	}
	else
	{
		$epoch = $epoch - $epoch_Y * 365 - 1 - $leapyear;
	}
	return $epoch;
}

function get_forum_most_active($user)
{
	global $db, $userdata;

	if ( intval($user) == 0 )
	{
		$user = trim(htmlspecialchars($user));
		$user = substr(str_replace("\\'", "'", $user), 0, 25);
		$user = str_replace("'", "\\'", $user);
	}
	else
	{
		$user = intval($user);
	}

	$sql_forum = "SELECT forum_id, forum_name FROM " . FORUMS_TABLE . " ORDER BY forum_id";
	if (!($result = $db->sql_query($sql_forum, false, 'forums_')))
	{
		message_die(GENERAL_ERROR, 'Could not obtain forums list', '', __LINE__, __FILE__, $sql_forum);
	}

	$most_active_id = array();
	while ( $line = $db->sql_fetchrow($result) )
	{
		$most_active_id[] = $line['forum_id'];
		$most_active_name[$line['forum_id']] = $line['forum_name'];
	}
	$db->sql_freeresult($result);

	$count_most_active_id = count($most_active_id);

	$most_active_posts = 0;
	$num_result = 0;

	foreach ($most_active_id as $i)
	{
		$is_auth = auth(AUTH_VIEW, $i, $userdata);
		if ($is_auth['auth_view'] == 1)
		{
			$sql_most = "SELECT *
				FROM " . POSTS_TABLE . "
				WHERE forum_id = $i AND poster_id = $user";
			if ( !($result = $db->sql_query($sql_most)) )
			{
				message_die(GENERAL_ERROR, 'Tried obtaining data for a non-existent user', '', __LINE__, __FILE__, $sql_most);
			}

			if ( $db->sql_numrows($result) > $most_active_posts )
			{
				$most_active_posts = $db->sql_numrows($result);
				$most_active_foren_id = $i;
				$most_active_forum_name = $most_active_name[$i];
			}
		}
	}

	return array('forum_id' => $most_active_foren_id, 'forum_name' => $most_active_forum_name, 'posts' => $most_active_posts);
}

?>