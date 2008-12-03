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

function mg_kill_user($user_id)
{
	global $board_config, $lang, $userdata, $db;

	if (!($this_userdata = get_userdata($user_id)))
	{
		message_die(GENERAL_MESSAGE, $lang['No_user_id_specified']);
	}

	if($userdata['user_id'] != $user_id)
	{
		$sql = "SELECT g.group_id
			FROM " . USER_GROUP_TABLE . " ug, " . GROUPS_TABLE . " g
			WHERE ug.user_id = $user_id
				AND g.group_id = ug.group_id
				AND g.group_single_user = 1";
		if(!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not obtain group information for this user', '', __LINE__, __FILE__, $sql);
		}

		$row = $db->sql_fetchrow($result);

		$sql = "UPDATE " . POSTS_TABLE . "
			SET poster_id = " . DELETED . ", post_username = '" . str_replace("\\'", "''", addslashes($this_userdata['username'])) . "'
			WHERE poster_id = $user_id";
		if(!$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not update posts for this user', '', __LINE__, __FILE__, $sql);
		}

		// Start add - Fully integrated shoutbox MOD
		$sql = "UPDATE " . SHOUTBOX_TABLE . "
			SET shout_user_id = " . DELETED . ", shout_username = '$username'
			WHERE shout_user_id = $user_id";
			if(!$db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not update shouts for this user', '', __LINE__, __FILE__, $sql);
			}
		// End add - Fully integrated shoutbox MOD

		$sql = "UPDATE " . TOPICS_TABLE . "
			SET topic_poster = " . DELETED . "
			WHERE topic_poster = $user_id";
		if(!$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not update topics for this user', '', __LINE__, __FILE__, $sql);
		}

		$sql = "UPDATE " . VOTE_USERS_TABLE . "
			SET vote_user_id = " . DELETED . "
			WHERE vote_user_id = $user_id";
		if(!$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not update votes for this user', '', __LINE__, __FILE__, $sql);
		}

		$sql = "UPDATE " . GROUPS_TABLE . "
			SET group_moderator = '" . $userdata['user_id'] . "'
			WHERE group_moderator = '" . $user_id . "'";
		if(!$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not update group moderators', '', __LINE__, __FILE__, $sql);
		}

		$sql = "DELETE FROM " . USERS_TABLE . "
			WHERE user_id = $user_id";
		if(!$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not delete user', '', __LINE__, __FILE__, $sql);
		}

		$sql = "DELETE FROM " . USER_GROUP_TABLE . "
			WHERE user_id = $user_id";
		if(!$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not delete user from user_group table', '', __LINE__, __FILE__, $sql);
		}

		if (!empty($row['group_id']))
		{
			$sql = "DELETE FROM " . GROUPS_TABLE . "
				WHERE group_id = '" . $row['group_id'] . "'";
			if(!$db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not delete group for this user', '', __LINE__, __FILE__, $sql);
			}

			$sql = "DELETE FROM " . AUTH_ACCESS_TABLE . "
				WHERE group_id = '" . $row['group_id'] . "'";
			if(!$db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not delete group for this user', '', __LINE__, __FILE__, $sql);
			}
		}

//<!-- BEGIN Unread Post Information to Database Mod -->
		$sql = "DELETE FROM " . UPI2DB_ALWAYS_READ_TABLE . "
			WHERE user_id = $user_id";

		if (!$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not delete always read', '', __LINE__, __FILE__, $sql);
		}

		$sql = "DELETE FROM " . UPI2DB_UNREAD_POSTS_TABLE . "
			WHERE user_id = $user_id";

		if (!$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not delete unread posts', '', __LINE__, __FILE__, $sql);
		}
//<!-- END Unread Post Information to Database Mod -->

		$sql = "DELETE FROM " . TOPICS_WATCH_TABLE . "
			WHERE user_id = $user_id";
		if (!$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not delete user from topic watch table', '', __LINE__, __FILE__, $sql);
		}

		$sql = "DELETE FROM " . BOOKMARK_TABLE . "
			WHERE user_id = $user_id";
		if (!$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not delete user\'s bookmarks', '', __LINE__, __FILE__, $sql);
		}

		$sql = "DELETE FROM " . DRAFTS_TABLE . "
			WHERE user_id = $user_id";
		if (!$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not delete user\'s drafts', '', __LINE__, __FILE__, $sql);
		}

		$sql = "DELETE FROM " . BANLIST_TABLE . "
			WHERE ban_userid = $user_id";
		if (!$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not delete user from banlist table', '', __LINE__, __FILE__, $sql);
		}

		$db->clear_cache('ban_');

		$sql = "DELETE FROM " . SESSIONS_TABLE . "
			WHERE session_user_id = $user_id";
		if (!$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not delete sessions for this user', '', __LINE__, __FILE__, $sql);
		}

		$sql = "DELETE FROM " . SESSIONS_KEYS_TABLE . "
			WHERE user_id = $user_id";
		if (!$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not delete auto-login keys for this user', '', __LINE__, __FILE__, $sql);
		}

		$sql = "UPDATE " . ALBUM_TABLE . "
		SET pic_user_id = " . ANONYMOUS . "
			WHERE pic_user_id = $user_id";
		if(!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not update album information for this user', '', __LINE__, __FILE__, $sql);
		}

		$sql = "UPDATE " . ALBUM_COMMENT_TABLE . "
		SET comment_user_id = " . ANONYMOUS . "
			WHERE comment_user_id = $user_id";
		if(!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not update album comment information for this user', '', __LINE__, __FILE__, $sql);
		}

		$sql = "DELETE FROM " . DL_FAVORITES_TABLE . "
			WHERE fav_user_id = $user_id";
		if (!$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not delete favorite downloads for this user', '', __LINE__, __FILE__, $sql);
		}

		$sql = "DELETE FROM " . DL_NOTRAF_TABLE . "
			WHERE user_id = $user_id";
		if (!$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not delete marked downloads for this user', '', __LINE__, __FILE__, $sql);
		}

		// Digests - BEGIN
		// First remove all individual forum subscriptions
		$sql = 'DELETE FROM ' . DIGEST_SUBSCRIBED_FORUMS_TABLE . ' WHERE user_id = ' . $user_id;
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not delete from ' . DIGEST_SUBSCRIBED_FORUMS_TABLE . ' table', '', __LINE__, __FILE__, $sql);
		}

		// remove subscription itself
		$sql = 'DELETE FROM ' . DIGEST_SUBSCRIPTIONS_TABLE . ' WHERE user_id = ' . $user_id;
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not delete from ' . DIGEST_SUBSCRIPTIONS_TABLE . ' table', '', __LINE__, __FILE__, $sql);
		}
		// Digests - END

		$sql = "SELECT privmsgs_id
			FROM " . PRIVMSGS_TABLE . "
			WHERE ((privmsgs_from_userid = $user_id
			AND privmsgs_type = " . PRIVMSGS_NEW_MAIL . ")
			OR (privmsgs_from_userid = $user_id
			AND privmsgs_type = " . PRIVMSGS_SENT_MAIL . ")
			OR (privmsgs_to_userid = $user_id
			AND privmsgs_type = " . PRIVMSGS_READ_MAIL . ")
			OR (privmsgs_to_userid = $user_id
			AND privmsgs_type = " . PRIVMSGS_SAVED_IN_MAIL . ")
			OR (privmsgs_from_userid = $user_id
			AND privmsgs_type = " . PRIVMSGS_SAVED_OUT_MAIL . "))";
		// This is more restrictive!
		/*
		$sql = "SELECT privmsgs_id
			FROM " . PRIVMSGS_TABLE . "
			WHERE privmsgs_from_userid = $user_id
				OR privmsgs_to_userid = $user_id";
		*/
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not select all users private messages', '', __LINE__, __FILE__, $sql);
		}

		// This little bit of code directly from the private messaging section.
		while ($row_privmsgs = $db->sql_fetchrow($result))
		{
			$mark_list[] = $row_privmsgs['privmsgs_id'];
		}

		if (count($mark_list))
		{
			$delete_sql_id = implode(', ', $mark_list);
			$delete_sql = "DELETE FROM " . PRIVMSGS_TABLE . "
				WHERE privmsgs_id IN ($delete_sql_id)";
			if (!$db->sql_query($delete_sql))
			{
				message_die(GENERAL_ERROR, 'Could not delete private message info', '', __LINE__, __FILE__, $delete_sql);
			}
		}

		$sql = "UPDATE " . PRIVMSGS_TABLE . "
			SET privmsgs_to_userid = " . DELETED . "
			WHERE privmsgs_to_userid = $user_id";
		if (!$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not update private messages saved to the user', '', __LINE__, __FILE__, $sql);
		}

		$sql = "UPDATE " . PRIVMSGS_TABLE . "
			SET privmsgs_from_userid = " . DELETED . "
			WHERE privmsgs_from_userid = $user_id";
		if (!$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not update private messages saved from the user', '', __LINE__, __FILE__, $sql);
		}

		return true;
	}

	return false;
}

?>