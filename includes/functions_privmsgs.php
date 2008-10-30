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

function delete_older_message($privmsgs_type)
{
	global $db, $board_config, $userdata;

	$result = false;
	$sql_where = '';
	$max_folder_items = false;
	switch ($privmsgs_type)
	{
		case 'PM_INBOX':
			$max_folder_items = $board_config['max_inbox_privmsgs'];
			$sql_where = "(privmsgs_type = " . PRIVMSGS_NEW_MAIL . " OR privmsgs_type = " . PRIVMSGS_READ_MAIL . " OR privmsgs_type = " . PRIVMSGS_UNREAD_MAIL . ")
										AND privmsgs_to_userid = '" . $to_userdata['user_id'] . "'";
			break;
		case 'PM_SAVED':
			$max_folder_items = $board_config['max_savebox_privmsgs'];
			$sql_where = "((privmsgs_to_userid = '" . $userdata['user_id'] . "' AND privmsgs_type = " . PRIVMSGS_SAVED_IN_MAIL . ")
										OR (privmsgs_from_userid = '" . $userdata['user_id'] . "' AND privmsgs_type = " . PRIVMSGS_SAVED_OUT_MAIL . "))";
			break;
	}

	// See if recipient reached folder limit
	$sql = "SELECT COUNT(privmsgs_id) AS folder_items, MIN(privmsgs_date) AS oldest_post_time
		FROM " . PRIVMSGS_TABLE . "
		WHERE " . $sql_where;
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not obtain sent message info for sender', '', __LINE__, __FILE__, $sql);
	}

	if ($folder_info = $db->sql_fetchrow($result))
	{
		if ($max_folder_items && ($folder_info['folder_items'] >= $max_folder_items))
		{
			$sql = "SELECT privmsgs_id FROM " . PRIVMSGS_TABLE . "
				WHERE " . $sql_where . " AND privmsgs_date = " . $folder_info['oldest_post_time'];
			if (!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not find oldest privmsgs (save)', '', __LINE__, __FILE__, $sql);
			}
			$old_privmsgs_id = $db->sql_fetchrow($result);
			$old_privmsgs_id = $old_privmsgs_id['privmsgs_id'];

			$sql = "DELETE FROM " . PRIVMSGS_TABLE . "
				WHERE privmsgs_id = '" . $old_privmsgs_id . "'";
			if (!$db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not delete oldest privmsgs', '', __LINE__, __FILE__, $sql);
			}
		}
	}
}

?>