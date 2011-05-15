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

function ip_user_kill($user_id)
{
	global $config, $lang, $user, $db;

	if (!($this_userdata = get_userdata($user_id)))
	{
		if (!defined('STATUS_404')) define('STATUS_404', true);
		message_die(GENERAL_MESSAGE, 'NO_USER');
	}

	if($user->data['user_id'] != $user_id)
	{

		// We need to reset notifications before deleting the user from the table, because we also want to make sure to reset his profile if something goes wrong in deletion
		$clear_notification = user_clear_notifications($user_id);

		$sql = "SELECT g.group_id
			FROM " . USER_GROUP_TABLE . " ug, " . GROUPS_TABLE . " g
			WHERE ug.user_id = " . $user_id . "
				AND g.group_id = ug.group_id
				AND g.group_single_user = 1";
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);

		$sql = "UPDATE " . POSTS_TABLE . "
			SET poster_id = " . DELETED . ", post_username = '" . $db->sql_escape($this_userdata['username']) . "'
			WHERE poster_id = " . $user_id;
		$db->sql_query($sql);

		$sql = "UPDATE " . TOPICS_TABLE . "
			SET topic_poster = " . DELETED . "
			WHERE topic_poster = " . $user_id;
		$db->sql_query($sql);

		$sql = "UPDATE " . POLL_VOTES_TABLE . "
			SET vote_user_id = " . DELETED . "
			WHERE vote_user_id = " . $user_id;
		$db->sql_query($sql);

		$sql = "UPDATE " . GROUPS_TABLE . "
			SET group_moderator = '" . $user->data['user_id'] . "'
			WHERE group_moderator = '" . $user_id . "'";
		$db->sql_query($sql);

		$sql = "DELETE FROM " . USERS_TABLE . " WHERE user_id = " . $user_id;
		$db->sql_query($sql);

		$sql = "DELETE FROM " . USER_GROUP_TABLE . " WHERE user_id = " . $user_id;
		$db->sql_query($sql);

		if (!empty($row['group_id']))
		{
			$sql = "DELETE FROM " . GROUPS_TABLE . " WHERE group_id = '" . $row['group_id'] . "'";
			$db->sql_query($sql);

			$sql = "DELETE FROM " . AUTH_ACCESS_TABLE . " WHERE group_id = '" . $row['group_id'] . "'";
			$db->sql_query($sql);
		}

//<!-- BEGIN Unread Post Information to Database Mod -->
		$sql = "DELETE FROM " . UPI2DB_ALWAYS_READ_TABLE . " WHERE user_id = " . $user_id;
		$db->sql_query($sql);

		$sql = "DELETE FROM " . UPI2DB_UNREAD_POSTS_TABLE . " WHERE user_id = " . $user_id;
		$db->sql_query($sql);
//<!-- END Unread Post Information to Database Mod -->

		$sql = "DELETE FROM " . POSTS_LIKES_TABLE . " WHERE user_id = " . $user_id;
		$db->sql_query($sql);

		$sql = "DELETE FROM " . BOOKMARK_TABLE . " WHERE user_id = " . $user_id;
		$db->sql_query($sql);

		$sql = "DELETE FROM " . DRAFTS_TABLE . " WHERE user_id = " . $user_id;
		$db->sql_query($sql);

		$sql = "DELETE FROM " . LINKS_TABLE . " WHERE user_id = " . $user_id;
		$db->sql_query($sql);

		$sql = "DELETE FROM " . BANLIST_TABLE . " WHERE ban_userid = " . $user_id;
		$db->sql_query($sql);

		$sql = "DELETE FROM " . SESSIONS_TABLE . " WHERE session_user_id = " . $user_id;
		$db->sql_query($sql);

		$sql = "DELETE FROM " . SESSIONS_KEYS_TABLE . " WHERE user_id = " . $user_id;
		$db->sql_query($sql);

		$sql = "UPDATE " . ALBUM_TABLE . "
		SET pic_user_id = " . ANONYMOUS . "
			WHERE pic_user_id = " . $user_id;
		$result = $db->sql_query($sql);

		$sql = "UPDATE " . ALBUM_COMMENT_TABLE . "
		SET comment_user_id = " . ANONYMOUS . "
			WHERE comment_user_id = " . $user_id;
		$result = $db->sql_query($sql);

		$sql = "DELETE FROM " . DL_FAVORITES_TABLE . " WHERE fav_user_id = " . $user_id;
		$db->sql_query($sql);

		$sql = "DELETE FROM " . DL_NOTRAF_TABLE . " WHERE user_id = " . $user_id;
		$db->sql_query($sql);

		$sql = "DELETE FROM " . SUDOKU_STATS . " WHERE user_id = " . $user_id;
		$db->sql_query($sql);

		$sql = "DELETE FROM " . SUDOKU_USERS . " WHERE user_id = " . $user_id;
		$db->sql_query($sql);

		// Start add - Fully integrated shoutbox MOD
		$sql = "UPDATE " . SHOUTBOX_TABLE . "
			SET shout_user_id = " . DELETED . ", shout_username = '" . $db->sql_escape($username) . "'
			WHERE shout_user_id = " . $user_id;
			$db->sql_query($sql);
		// End add - Fully integrated shoutbox MOD

		// Event Registration - BEGIN
		$sql = "DELETE FROM " . REGISTRATION_TABLE . " WHERE registration_user_id = " . $user_id;
		$db->sql_query($sql);
		// Event Registration - END

		$sql = "SELECT privmsgs_id
			FROM " . PRIVMSGS_TABLE . "
			WHERE ((privmsgs_from_userid = " . $user_id . "
			AND privmsgs_type = " . PRIVMSGS_NEW_MAIL . ")
			OR (privmsgs_from_userid = " . $user_id . "
			AND privmsgs_type = " . PRIVMSGS_SENT_MAIL . ")
			OR (privmsgs_to_userid = " . $user_id . "
			AND privmsgs_type = " . PRIVMSGS_READ_MAIL . ")
			OR (privmsgs_to_userid = " . $user_id . "
			AND privmsgs_type = " . PRIVMSGS_SAVED_IN_MAIL . ")
			OR (privmsgs_from_userid = " . $user_id . "
			AND privmsgs_type = " . PRIVMSGS_SAVED_OUT_MAIL . "))";
		// This is more restrictive!
		/*
		$sql = "SELECT privmsgs_id
			FROM " . PRIVMSGS_TABLE . "
			WHERE privmsgs_from_userid = " . $user_id . "
				OR privmsgs_to_userid = " . $user_id;
		*/
		$result = $db->sql_query($sql);

		// This little bit of code directly from the private messaging section.
		while ($row_privmsgs = $db->sql_fetchrow($result))
		{
			$mark_list[] = $row_privmsgs['privmsgs_id'];
		}

		if (sizeof($mark_list))
		{
			$delete_sql_id = implode(', ', $mark_list);
			$delete_sql = "DELETE FROM " . PRIVMSGS_TABLE . "
				WHERE privmsgs_id IN ($delete_sql_id)";
			$db->sql_query($delete_sql);
		}

		$sql = "UPDATE " . PRIVMSGS_TABLE . "
			SET privmsgs_to_userid = " . DELETED . "
			WHERE privmsgs_to_userid = " . $user_id;
		$db->sql_query($sql);

		$sql = "UPDATE " . PRIVMSGS_TABLE . "
			SET privmsgs_from_userid = " . DELETED . "
			WHERE privmsgs_from_userid = " . $user_id;
		$db->sql_query($sql);

		$db->clear_cache('ban_', USERS_CACHE_FOLDER);

		return true;
	}

	return false;
}


/**
 * Clear all user notifications
*/
function user_clear_notifications($user_id)
{
	global $db;

	$sql = "UPDATE " . USERS_TABLE . " SET user_notify = 0, user_notify_pm = 0, user_allow_mass_email = 0 WHERE user_id = " . $user_id;
	$result = $db->sql_query($sql);

	$sql = "DELETE FROM " . TOPICS_WATCH_TABLE . "
		WHERE user_id = " . $user_id;
	$result = $db->sql_query($sql);

	$sql = "DELETE FROM " . FORUMS_WATCH_TABLE . "
		WHERE user_id = " . $user_id;
	$result = $db->sql_query($sql);

	$sql = "DELETE FROM " . ALBUM_COMMENT_WATCH_TABLE . "
		WHERE user_id = " . $user_id;
	$result = $db->sql_query($sql);

	// Digests - BEGIN
	// First remove all individual forum subscriptions
	$sql = 'DELETE FROM ' . DIGEST_SUBSCRIBED_FORUMS_TABLE . ' WHERE user_id = ' . $user_id;
	$result = $db->sql_query($sql);

	// remove subscription itself
	$sql = 'DELETE FROM ' . DIGEST_SUBSCRIPTIONS_TABLE . ' WHERE user_id = ' . $user_id;
	$result = $db->sql_query($sql);
	// Digests - END

	return true;
}

?>