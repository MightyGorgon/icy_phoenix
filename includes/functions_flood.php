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
* Check if the user is flooding
*/
function check_flood_posting($return = false)
{
	global $db, $cache, $config, $userdata, $lang, $user_ip;

	if (($userdata['user_level'] != ADMIN) && ($userdata['user_level'] != MOD))
	{
		$where_sql = ($userdata['user_id'] == ANONYMOUS) ? ("poster_ip = '$user_ip'") : ('poster_id = ' . $userdata['user_id']);
		$sql = "SELECT MAX(post_time) AS last_post_time
			FROM " . POSTS_TABLE . "
			WHERE $where_sql";
		$result = $db->sql_query($sql);
		if ($row = $db->sql_fetchrow($result))
		{
			$ip_post_flood_time = (int) $row['last_post_time'] + (int) $config['flood_interval'];
			if ($ip_post_flood_time >= time())
			{
				if ($return)
				{
					return true;
				}
				else
				{
					message_die(GENERAL_MESSAGE, $lang['Flood_Error']);
				}
			}
		}
	}

	return false;
}

/*
* Check if the user is flooding by sending emails
*/
function check_flood_email($return = false)
{
	global $db, $cache, $config, $userdata, $lang;

	// CrackerTracker v5.x
	// Mighty Gorgon: old 'ct_last_mail' timer integrated with the field 'user_emailtime' to avoid duplicated fields
	$ct_mail_flood_time = (int) $userdata['user_emailtime'] + (int) $config['ctracker_massmail_time'] * 60;
	if (($userdata['user_level'] != ADMIN) && ($ct_mail_flood_time >= time()) && ($config['ctracker_massmail_protection'] == 1))
	{
		if ($return)
		{
			return true;
		}
		else
		{
			message_die(GENERAL_MESSAGE, sprintf($lang['ctracker_sendmail_info'], $config['ctracker_massmail_time']));
		}
	}
	// CrackerTracker v5.x

	$ip_mail_flood_time = (int) $userdata['user_emailtime'] + (int) $config['flood_interval'];
	if (($userdata['user_level'] != ADMIN) && ($ip_mail_flood_time >= time()))
	{
		if ($return)
		{
			return true;
		}
		else
		{
			message_die(GENERAL_MESSAGE, $lang['Flood_email_limit']);
		}
	}

	return false;
}

/*
* Update the flood time for emails
*/
function update_flood_time_email()
{
	global $db, $cache, $config, $userdata, $lang;

	// Update the emailtime to prevent flooding
	$sql = 'UPDATE ' . USERS_TABLE . ' SET user_emailtime = ' . time() . ' WHERE user_id = ' . $userdata['user_id'];
	$result = $db->sql_query($sql);

	return true;
}

?>