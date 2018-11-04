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
* Wooly Spud (phpbb@xgmag.com.com)
*
*/

define('IN_ICYPHOENIX', true);

if (!empty($setmodules))
{
	$filename = basename(__FILE__);
	$module['1610_Users']['220_Bantron'] = $filename;
	return;
}

// Load default header
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('pagestart.' . PHP_EXT);

// Set Overall Variables
$mode = request_var('mode', '');

$show = request_var('show', 'all');
$order = request_var('order', 'ASC');
$order = check_var_value($order, array('ASC', 'DESC'));

$start = request_var('start', 0);
$start = ($start < 0) ? 0 : $start;

if (isset($_POST['delete_submit']))
{
	if (isset($_POST['ban_delete']))
	{
		foreach ($_POST['ban_delete'] as $ban_id)
		{
			$sql = "DELETE FROM " . BANLIST_TABLE . " WHERE ban_id = $ban_id";
			if (!$db->sql_query ($sql))
			{
				message_die (GENERAL_ERROR, "Couldn't delete selected bans from database", "", __LINE__, __FILE__, $sql);
			}
		}
	}

	$db->clear_cache('ban_', USERS_CACHE_FOLDER);

	$message = $lang['Ban_update_sucessful'] .'<br /><br />'. sprintf ($lang['Click_return_banadmin'], '<a href="'. append_sid ('admin_user_bantron.' . PHP_EXT) .'">', '</a>') .'<br /><br />' . sprintf ($lang['Click_return_admin_index'], '<a href="'. append_sid ('index.' . PHP_EXT . '?pane=right') .'">', '</a>');

	message_die (GENERAL_MESSAGE, $message);
}
elseif (isset($_POST['submit_add']) || isset($_POST['submit_update']))
{
	$user_bansql = '';
	$email_bansql = '';
	$ip_bansql = '';

	$ban_start = time();
	$ban_by_userid = $user->data['user_id'];
	$ban_priv_reason = request_post_var('ban_priv_reason', 'NULL', true);
	$ban_pub_reason_mode = request_post_var('ban_pub_reason_mode', '', true);
	$ban_pub_reason = request_post_var('ban_pub_reason', 'NULL', true);
	$ban_pub_reason = (($ban_pub_reason_mode == '2') && !empty($ban_pub_reason)) ? $ban_pub_reason : 'NULL';

	$ban_expire_time_mode = request_post_var('ban_expire_time_mode', '');
	if ($ban_expire_time_mode == 'never')
	{
		$ban_end = 'NULL';
	}
	elseif ($ban_expire_time_mode == 'relative')
	{
		$ban_end = @strtotime('+' . $_POST['ban_expire_time_relative'] . ' ' . $_POST['ban_expire_time_relative_units']);
	}
	elseif ($ban_expire_time_mode == 'absolute')
	{
		$ban_end = @strtotime($_POST['ban_expire_time_absolute_hour'] . ':' . $_POST['ban_expire_time_absolute_minute'] . ' ' . $_POST['ban_expire_time_absolute_ampm'] .' '. $_POST['ban_expire_time_absolute_month'] . '/' . $_POST['ban_expire_time_absolute_mday'] . '/' . $_POST['ban_expire_time_absolute_year']);
	}

	$user_list = array ();
	$username = request_var('username', '', true);
	$username = htmlspecialchars_decode($username, ENT_COMPAT);
	if (!empty($username))
	{
		$this_userdata = get_userdata($username);
		if (!$this_userdata)
		{
			if (!defined('STATUS_404')) define('STATUS_404', true);
			message_die(GENERAL_MESSAGE, 'NO_USER');
		}

		$user_list[] = $this_userdata['user_id'];
	}

	$ip_list = array ();
	$ban_ips = request_var('ban_ip', '');
	if (!empty($ban_ips))
	{
		$ip_list = match_ips($ban_ips);
	}

	$email_list = array ();
	$ban_emails = request_var('ban_email', '', true);
	if (!empty($ban_emails))
	{
		$email_list_temp = explode(',', $ban_emails);

		for ($i = 0; $i < sizeof($email_list_temp); $i++)
		{
			// This preg_match match is based on one by php@unreelpro.com contained in the annotated php manual at php.com (preg_match section) - Originally it was contained @ php.com -> ereg section
			if (preg_match('/^(([a-z0-9&\'\.\-_\+])|(\*))+@(([a-z0-9\-])|(\*))+\.([a-z0-9\-]+\.)*?[a-z]+$/is', trim($email_list_temp[$i])))
			{
				$email_list[] = trim($email_list_temp[$i]);
			}
		}
	}

	if (isset($_POST['submit_update']))
	{
		$sql = "UPDATE ". BANLIST_TABLE ."
			SET ban_end = $ban_end, ban_priv_reason = '" . $db->sql_escape($ban_priv_reason) . "', ban_pub_reason_mode = '" . $db->sql_escape($ban_pub_reason_mode) . "', ban_pub_reason = '" . $db->sql_escape($ban_pub_reason) . "'
			WHERE ban_id = '" . intval($_POST['ban_id']) . "'";
		if (!$db->sql_query ($sql))
		{
			message_die (GENERAL_ERROR, "Couldn't update ban information", "", __LINE__, __FILE__, $sql);
		}
	}
	else
	{
		$sql = "SELECT * FROM " . BANLIST_TABLE;
		if (!($result = $db->sql_query ($sql)))
		{
			message_die (GENERAL_ERROR, "Couldn't obtain banlist information", "", __LINE__, __FILE__, $sql);
		}

		$current_banlist = $db->sql_fetchrowset($result);
		$db->sql_freeresult ($result);

		$kill_session_sql = '';
		for ($i = 0; $i < sizeof($user_list); $i++)
		{
			$in_banlist = false;
			for ($j = 0; $j < sizeof($current_banlist); $j++)
			{
				if ($user_list[$i] == $current_banlist[$j]['ban_userid'])
				{
					$in_banlist = true;
				}
			}

			if (!$in_banlist)
			{
				$kill_session_sql .= (($kill_session_sql != '') ? ' OR ' : '') . "session_user_id = " . $user_list[$i];

				$sql = "INSERT INTO ". BANLIST_TABLE ." (ban_userid, ban_start, ban_end, ban_by_userid, ban_priv_reason, ban_pub_reason_mode, ban_pub_reason)
					VALUES (" . (int) $user_list[$i] . ", $ban_start, $ban_end, $ban_by_userid, '" . $db->sql_escape($ban_priv_reason) . "', " . $db->sql_escape($ban_pub_reason_mode) . ", '" . $db->sql_escape($ban_pub_reason) . "')";
				if (!$db->sql_query ($sql))
				{
					message_die (GENERAL_ERROR, "Couldn't insert ban_userid info into database", "", __LINE__, __FILE__, $sql);
				}
				if (!empty($user_list[$i]))
				{
					$sql = "UPDATE " . USERS_TABLE . " SET user_warnings = " . $config['max_user_bancard'] . " WHERE user_id = " . (int) $user_list[$i];
					$result = $db->sql_query($sql);
				}
			}
		}

		if (!empty($user_list))
		{
			// Delete notifications for banned users
			if (!class_exists('class_notifications'))
			{
				include(IP_ROOT_PATH . 'includes/class_notifications.' . PHP_EXT);
				$class_notifications = new class_notifications();
			}
			$class_notifications->delete_user_notifications($user_list);
		}

		for ($i = 0; $i < sizeof($ip_list); $i++)
		{
			$in_banlist = false;
			for ($j = 0; $j < sizeof($current_banlist); $j++)
			{
				if ($ip_list[$i] == $current_banlist[$j]['ban_ip'])
				{
					$in_banlist = true;
				}
			}

			if (!$in_banlist)
			{
				// Mighty Gorgon: we don't use this replacement any more...
				/*
				if (preg_match('/(255\.)|(\.255)/is', $ip_list[$i]))
				{
					$kill_ip_sql = "session_ip LIKE '" . str_replace('.', '', preg_replace('/(255\.)|(\.255)/is', '%', $ip_list[$i])) . "'";
				}
				else
				{
					$kill_ip_sql = "session_ip = '" . $db->sql_escape($ip_list[$i]) . "'";
				}
				*/
				$kill_ip_sql = "session_ip = '" . $db->sql_escape($ip_list[$i]) . "'";

				$kill_session_sql .= (($kill_session_sql != '') ? ' OR ' : '') . $kill_ip_sql;

				$sql = "INSERT INTO ". BANLIST_TABLE ." (ban_ip, ban_start, ban_end, ban_by_userid, ban_priv_reason, ban_pub_reason_mode, ban_pub_reason)
					VALUES ('" . $db->sql_escape($ip_list[$i]) . "', $ban_start, $ban_end, $ban_by_userid, '" . $db->sql_escape($ban_priv_reason) . "', " . $db->sql_escape($ban_pub_reason_mode) . ", '" . $db->sql_escape($ban_pub_reason) . "')";
				if (!$db->sql_query ($sql))
				{
					message_die (GENERAL_ERROR, "Couldn't insert ban_ip info into database", "", __LINE__, __FILE__, $sql);
				}
			}
		}

		//
		// Now we'll delete all entries from the session table with any of the banned
		// user or IP info just entered into the ban table ... this will force a session
		// initialisation resulting in an instant ban
		//
		if ($kill_session_sql != '')
		{
			$sql = "DELETE FROM ". SESSIONS_TABLE ."
				WHERE $kill_session_sql";
			if (!$db->sql_query ($sql))
			{
				message_die (GENERAL_ERROR, "Couldn't delete banned sessions from database", "", __LINE__, __FILE__, $sql);
			}
		}

		for ($i = 0; $i < sizeof($email_list); $i++)
		{
			$in_banlist = false;
			for ($j = 0; $j < sizeof($current_banlist); $j++)
			{
				if ($email_list[$i] == $current_banlist[$j]['ban_email'])
				{
					$in_banlist = true;
				}
			}

			if (!$in_banlist)
			{
				$sql = "INSERT INTO " . BANLIST_TABLE . " (ban_email, ban_start, ban_end, ban_by_userid, ban_priv_reason, ban_pub_reason_mode, ban_pub_reason)
					VALUES ('" . $db->sql_escape($email_list[$i]) . "', $ban_start, $ban_end, $ban_by_userid, '" . $db->sql_escape($ban_priv_reason) . "', " . $db->sql_escape($ban_pub_reason_mode) . ", '" . $db->sql_escape($ban_pub_reason) . "')";
				if (!$db->sql_query ($sql))
				{
					message_die (GENERAL_ERROR, "Couldn't insert ban_email info into database", "", __LINE__, __FILE__, $sql);
				}
			}
		}
	}

	$db->clear_cache('ban_', USERS_CACHE_FOLDER);

	$message = $lang['Ban_update_sucessful'] . '<br /><br />' . sprintf($lang['Click_return_banadmin'], '<a href="' . append_sid ("admin_user_bantron." . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid ('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

	message_die (GENERAL_MESSAGE, $message);
}
elseif ($_GET['mode'] == 'view_reasons')
{
	$template->set_filenames(array('body' => ADM_TPL . 'user_bantron_reasons.tpl'));

	$sql = "SELECT b.*, u.username
		FROM ". BANLIST_TABLE ." AS b LEFT JOIN ". USERS_TABLE ." AS u
		ON b.ban_userid = u.user_id
		WHERE ban_id = ". $_GET['ban_id'];

	// Get results to be used to return ban information
	if (!($result = $db->sql_query ($sql)))
	{
		message_die (GENERAL_ERROR, 'Could not select ban reason', '', __LINE__, __FILE__, $sql);
	}

	$row = $db->sql_fetchrow ($result);

	if ($row['ban_userid'] != 0)
	{
		$template->assign_block_vars('ban_username', array(
			'L_USERNAME' => $lang['Username'],
			'USERNAME' => $row['username']
			)
		);
	}
	elseif (!empty($row['ban_ip']))
	{
		$template->assign_block_vars('ban_ip', array(
			'L_IP' => $lang['BM_IP'],
			// Mighty Gorgon: we don't use this replacement any more...
			//'IP' => str_replace('255', '*', $row['ban_ip'])
			'IP' => $row['ban_ip']
			)
		);
	}
	elseif (isset($row['ban_email']))
	{
		$template->assign_block_vars('ban_email', array(
			'L_EMAIL' => $lang['Email'],
			'EMAIL' => $row['ban_email']
			)
		);
	}

	if ($row['ban_pub_reason_mode'] == '0')
	{
		$ban_pub_reason = $lang['You_been_banned'];
	}
	elseif ($row['ban_pub_reason_mode'] == '1')
	{
		$ban_pub_reason = str_replace("\n", '<br />', stripslashes($row['ban_priv_reason']));
	}
	elseif ($row['ban_pub_reason_mode'] == '2')
	{
		$ban_pub_reason = str_replace("\n", '<br />', stripslashes($row['ban_pub_reason']));
	}

	$template->assign_vars(array(
		'L_BAN_REASONS' => $lang['BM_Ban_reasons'],
		'L_PRIVATE_REASON' => $lang['BM_Private_reason'],
		'L_PUBLIC_REASON' => $lang['BM_Public_reason'],

		'PRIVATE_REASON' => str_replace("\n", '<br />', stripslashes($row['ban_priv_reason'])),
		'PUBLIC_REASON' => $ban_pub_reason
		)
	);

	for ($i = 0; $row = $db->sql_fetchrow($data_results); $i++)
	{
		$reason = $row['ban_reason'];
		$reason = str_replace("\n","<br />",$reason);
	}
}
elseif (isset($_POST['add']) || ($mode == 'edit'))
{
	$template->set_filenames(array('body' => ADM_TPL . 'user_bantron_edit.tpl'));

	$template->assign_vars(array(
		'L_BM_TITLE' => $lang['BM_Title'],
		'L_BM_EXPLAIN' => $lang['BM_Explain'],

		'L_ADD_A_NEW_BAN' => $lang['BM_Add_a_new_ban'],

		'L_PRIVATE_REASON' => $lang['BM_Private_reason'],
		'L_PRIVATE_REASON_EXPLAIN' => $lang['BM_Private_reason_explain'],
		'L_PUBLIC_REASON' => $lang['BM_Public_reason'],
		'L_PUBLIC_REASON_EXPLAIN' => $lang['BM_Public_reason_explain'],
		'L_GENERIC_REASON' => $lang['BM_Generic_reason'],
		'L_MIRROR_PRIVATE_REASON' => $lang['BM_Mirror_private_reason'],
		'L_OTHER' => $lang['BM_Other'],
		'L_EXPIRE_TIME' => $lang['BM_Expire_time'],
		'L_EXPIRE_TIME_EXPLAIN' => $lang['BM_Expire_time_explain'],
		'L_NEVER' => $lang['BM_Never'],
		'L_AFTER_SPECIFIED_LENGTH_OF_TIME' => $lang['BM_After_specified_length_of_time'],
		'L_MINUTES' => $lang['BM_Minutes'],
		'L_HOURS' => $lang['BM_Hours'],
		'L_DAYS' => $lang['BM_Days'],
		'L_WEEKS' => $lang['BM_Weeks'],
		'L_MONTHS' => $lang['BM_Months'],
		'L_YEARS' => $lang['BM_Years'],
		'L_AFTER_SPECIFIED_DATE' => $lang['BM_After_specified_date'],
		'L_AM' => $lang['BM_AM'],
		'L_PM' => $lang['BM_PM'],
		'L_24_HOUR' => $lang['BM_24_hour'],
		'L_SUBMIT' => $lang['Submit'],

		'SUBMIT' => (isset($_POST['add'])) ? 'submit_add' : 'submit_update',

		'S_BANLIST_ACTION' => append_sid('admin_user_bantron.' . PHP_EXT)
		)
	);

	if ($mode == 'edit')
	{
		$sql = "SELECT b.*, u.username
						FROM ". BANLIST_TABLE ." b, ". USERS_TABLE ." u
						WHERE b.ban_id = '". $_GET['ban_id'] ."'
							AND u.user_id = b.ban_userid";
		if (!($result = $db->sql_query ($sql)))
		{
			message_die (GENERAL_ERROR, "Couldn't obtain banlist information for specified record", "", __LINE__, __FILE__, $sql);
		}

		$row = $db->sql_fetchrow ($result);
		$db->sql_freeresult ($result);

		if ($row['ban_userid'] != 0)
		{
			$template->assign_block_vars('username_row', array(
				'L_USERNAME' => $lang['Username'],
				'U_SEARCH_USER' => append_sid(IP_ROOT_PATH . CMS_PAGE_SEARCH . '?mode=searchuser'),
				'USERNAME' => $row['username']
				)
			);
		}
		elseif (!empty($row['ban_ip']))
		{
			$template->assign_block_vars('ip_row', array(
				'L_IP_OR_HOSTNAME' => $lang['IP_hostname'],
				'L_BAN_IP_EXPLAIN' => $lang['Ban_IP_explain'],
				// Mighty Gorgon: we don't use this replacement any more...
				//'BAN_IP' => (!empty($row['ban_ip'])) ? str_replace('255', '*', $row['ban_ip']) : ''
				'BAN_IP' => (!empty($row['ban_ip'])) ? $row['ban_ip'] : ''
				)
			);
		}
		elseif (isset($row['ban_email']))
		{
			$template->assign_block_vars('email_row', array(
				'L_EMAIL_ADDRESS' => $lang['Email_address'],
				'L_BAN_EMAIL_EXPLAIN' => $lang['Ban_email_explain'],
				'BAN_EMAIL' => $row['ban_email']
				)
			);
		}

		if (isset($row['ban_end']))
		{
			$ban_expire_time = @getdate($row['ban_end']);

			if ($ban_expire_time['hours'] < 13)
			{
				$ban_expire_time_ampm = 'am';
			}
			else
			{
				$ban_expire_time['hours'] = $ban_expire_time['hours'] - 12;
				$ban_expire_time_ampm = 'pm';
			}

			$template->assign_vars(array(
				'BAN_EXPIRE_TIME_MODE_ABSOLUTE' => ($row['ban_expire_time'] != '-1') ? ' checked' : '',
				'BAN_EXPIRE_TIME_ABSOLUTE_HOUR' => str_pad($ban_expire_time['hours'], '2', '0', STR_PAD_LEFT),
				'BAN_EXPIRE_TIME_ABSOLUTE_MINUTE' => str_pad($ban_expire_time['minutes'], '2', '0', STR_PAD_LEFT),
				'BAN_EXPIRE_TIME_ABSOLUTE_AM' => ($ban_expire_time_ampm == 'am') ? ' checked' : '',
				'BAN_EXPIRE_TIME_ABSOLUTE_PM' => ($ban_expire_time_ampm == 'pm') ? ' checked' : '',
				'BAN_EXPIRE_TIME_ABSOLUTE_MONTH' => str_pad($ban_expire_time['mon'], '2', '0', STR_PAD_LEFT),
				'BAN_EXPIRE_TIME_ABSOLUTE_MDAY' => str_pad($ban_expire_time['mday'], '2', '0', STR_PAD_LEFT),
				'BAN_EXPIRE_TIME_ABSOLUTE_YEAR' => $ban_expire_time['year']
				)
			);
		}
		else
		{
			$template->assign_vars(array(
				'BAN_EXPIRE_TIME_MODE_NEVER' => ' checked'
				)
			);
		}

		$template->assign_vars(array(
			'BAN_PRIV_REASON' => stripslashes ($row['ban_priv_reason']),
			'BAN_PUB_REASON_MODE_0' => (!isset($row['ban_pub_reason_mode']) || $row['ban_pub_reason_mode'] == '0') ? ' checked' : '',
			'BAN_PUB_REASON_MODE_1' => ($row['ban_pub_reason_mode'] == '1') ? ' checked' : '',
			'BAN_PUB_REASON_MODE_2' => ($row['ban_pub_reason_mode'] == '2') ? ' checked' : '',
			'BAN_PUB_REASON' => ($row['ban_pub_reason_mode'] == '2') ? stripslashes ($row['ban_pub_reason']) : ''
			)
		);

		$template->assign_block_vars('ban_id', array(
			'BAN_ID' => $_GET['ban_id']
			)
		);
	}
	else
	{
		$template->assign_vars(array(
			'BAN_PUB_REASON_MODE_0' => ' checked',
			'BAN_EXPIRE_TIME_MODE_NEVER' => ' checked'
			)
		);

		$template->assign_block_vars('username_row', array(
			'L_USERNAME' => $lang['Username'],
			'U_SEARCH_USER' => append_sid(IP_ROOT_PATH . CMS_PAGE_SEARCH . '?mode=searchuser')
			)
		);

		$template->assign_block_vars('ip_row', array(
			'L_IP_OR_HOSTNAME' => $lang['IP_hostname'],
			'L_BAN_IP_EXPLAIN' => $lang['Ban_IP_explain']
			)
		);

		$template->assign_block_vars('email_row', array(
			'L_EMAIL_ADDRESS' => $lang['Email_address'],
			'L_BAN_EMAIL_EXPLAIN' => $lang['Ban_email_explain']
			)
		);
	}
}
else
{
	$template->set_filenames(array('body' => ADM_TPL . 'user_bantron_body.tpl'));

	$template->assign_vars(array(
		'L_BM_TITLE' => $lang['BM_Title'],
		'L_BM_EXPLAIN' => $lang['BM_Explain'],

		'L_SHOW_BANS_BY' => $lang['BM_Show_bans_by'],
		'L_USERNAME' => $lang['Username'],
		'L_IP' => $lang['BM_IP'],
		'L_EMAIL' => $lang['Email'],
		'L_ALL' => $lang['BM_All'],
		'L_SHOW' => $lang['BM_Show'],

		'L_ORDER' => $lang['Order'],
		'L_ASCENDING' => $lang['Sort_Ascending'],
		'L_DESCENDING' => $lang['Sort_Descending'],
		'L_SORT' => $lang['Sort'],

		'L_BANNED' => $lang['BM_Banned'],
		'L_EXPIRES' => $lang['BM_Expires'],
		'L_BY' => $lang['BM_By'],
		'L_REASONS' => $lang['BM_Reasons'],
		'L_EDIT' => $lang['Edit'],
		'L_DELETE' => $lang['Delete'],

		'L_ADD_A_NEW_BAN' => $lang['BM_Add_a_new_ban'],
		'L_DELETE_SELECTED_BANS' => $lang['BM_Delete_selected_bans'],

		'USERNAME_SELECTED' => ($show == 'username') ? ' selected="selected"' : '',
		'IP_SELECTED' => ($show == 'ip') ? ' selected="selected"' : '',
		'EMAIL_SELECTED' => ($show == 'email') ? ' selected="selected"' : '',
		'ALL_SELECTED' => ($show == 'all') ? ' selected="selected"' : '',

		'ASC_SELECTED' => ($order == 'ASC') ? ' selected="selected"' : '',
		'DESC_SELECTED' => ($order == 'DESC') ? ' selected="selected"' : '',

		'SHOW' => $show,
		'ORDER' => $order,

		'S_BANTRON_ACTION' => append_sid('admin_user_bantron.' . PHP_EXT)
		)
	);

	switch ($show)
	{
		case 'username':
			$template->assign_block_vars('username_header', array(
				'L_USERNAME' => $lang['Username']
				)
			);

			$count_sql = "SELECT COUNT(*) AS total
				FROM ". BANLIST_TABLE ." b, ". USERS_TABLE ." u
				WHERE u.user_id = b.ban_userid
					AND b.ban_userid != 0
					AND u.user_id != ". ANONYMOUS;
			$sql = "SELECT b.*, u.user_id, u.username
				FROM ". BANLIST_TABLE ." b, ". USERS_TABLE ." u
				WHERE u.user_id = b.ban_userid
					AND b.ban_userid != 0
					AND u.user_id != ". ANONYMOUS ."
				ORDER BY u.username $order LIMIT $start, ". $config['topics_per_page'];

			break;

		case 'ip':
			$template->assign_block_vars('ip_header', array(
				'L_IP' => $lang['BM_IP']
				)
			);

			$count_sql = "SELECT COUNT(*) AS total
				FROM ". BANLIST_TABLE ."
				WHERE ban_ip != ''";
			$sql = "SELECT *
				FROM ". BANLIST_TABLE ."
				WHERE ban_ip != ''
				ORDER BY ban_email $order LIMIT $start, ". $config['topics_per_page'];

			break;

		case 'email':
			$template->assign_block_vars('email_header', array(
				'L_EMAIL' => $lang['Email']
				)
			);

			$count_sql = "SELECT COUNT(*) AS total
				FROM ". BANLIST_TABLE ."
				WHERE ban_email IS NOT NULL";
			$sql = "SELECT *
				FROM ". BANLIST_TABLE ."
				WHERE ban_email IS NOT NULL
				ORDER BY ban_email $order LIMIT $start, ". $config['topics_per_page'];

			break;

		case 'all':
			$template->assign_block_vars('username_header', array(
				'L_USERNAME' => $lang['Username']
				)
			);

			$template->assign_block_vars('ip_header', array(
				'L_IP' => $lang['BM_IP']
				)
			);

			$template->assign_block_vars('email_header', array(
				'L_EMAIL' => $lang['Email']
				)
			);

			$count_sql = "SELECT COUNT(*) AS total
				FROM ". BANLIST_TABLE;
			$sql = "SELECT b.*, u.user_id, u.username
				FROM ". BANLIST_TABLE ." b LEFT JOIN ". USERS_TABLE ." u
				ON b.ban_userid = u.user_id
				ORDER BY ban_id $order LIMIT $start, ". $config['topics_per_page'];

			break;
	}

	// Get results to be used to return ban information
	if (!($result = $db->sql_query ($sql)))
	{
		message_die (GENERAL_ERROR, 'Could not select ban data', '', __LINE__, __FILE__, $sql);
	}

	// Fill the Rows
	for ($i = 0; $row = $db->sql_fetchrow ($result); $i++)
	{
		$ban_id = $row['ban_id'];
		$ban_by = !empty($row['ban_by_userid']) ? colorize_username($row['ban_by_userid']) : '-';
		$ban_start = (isset($row['ban_start'])) ? create_date($lang['DATE_FORMAT'], $row['ban_start'], $config['board_timezone']) : '-';
		$ban_end = (isset($row['ban_end'])) ? create_date($lang['DATE_FORMAT'], $row['ban_end'], $config['board_timezone']) : '-';
		$ban_reason = (isset($row['ban_priv_reason']) || isset($row['ban_pub_reason'])) ? "<a href=\"javascript:void (0);\" onclick=\"window.open ('" . append_sid('admin_user_bantron.' . PHP_EXT . '?mode=view_reasons&amp;ban_id=' . $ban_id) . "','ban_reason','scrollbars=yes,width=540,height=450')\">" . $lang['View'] . '</a>' : '-';

		$template->assign_block_vars('rowlist', array(
			'ROW_CLASS' => (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'],
			'BAN_ID' => $ban_id,
			'BAN_TIME' => $ban_start,
			'BAN_EXPIRE_TIME' => $ban_end,
			'U_BAN_EDIT' => append_sid('admin_user_bantron.' . PHP_EXT . '?mode=edit&amp;ban_id=' . $ban_id),
			'BAN_REASON' => $ban_reason,
			'BAN_BY' => $ban_by
			)
		);

		switch ($show)
		{
			case 'username':
				$template->assign_block_vars('rowlist.username_content', array(
					'USERNAME' => $row['username']
					)
				);

				break;

			case 'ip':
				$template->assign_block_vars('rowlist.ip_content', array(
					// Mighty Gorgon: we don't use this replacement any more...
					//'IP' => str_replace('255', '*', $row['ban_ip'])
					'IP' => $row['ban_ip']
					)
				);

				break;

			case 'email':
				$template->assign_block_vars('rowlist.email_content', array(
					'EMAIL' => $row['ban_email']
					)
				);

				break;

			case 'all':
				$template->assign_block_vars('rowlist.username_content', array(
					'USERNAME' => ($row['ban_userid'] != '0') ? $row['username'] : '-'
					)
				);

				$template->assign_block_vars('rowlist.ip_content', array(
					// Mighty Gorgon: we don't use this replacement any more...
					//'IP' => (empty ($row['ban_ip'])) ? '-' : str_replace('255', '*', $row['ban_ip'])
					'IP' => (empty ($row['ban_ip'])) ? '-' : $row['ban_ip']
					)
				);

				$template->assign_block_vars('rowlist.email_content', array(
					'EMAIL' => (isset($row['ban_email'])) ? $row['ban_email'] : '-'
					)
				);

				break;
		}
	}

	if (!($result = $db->sql_query ($count_sql)))
	{
		message_die (GENERAL_ERROR, 'Could not count ban data', '', __LINE__, __FILE__, $count_sql);
	}

	$num_bans = $db->sql_fetchrow ($result);

	$pagination = generate_pagination('admin_user_bantron.' . PHP_EXT . '?show=' . $show . '&amp;order=' . $order, $num_bans['total'], $config['topics_per_page'], $start) . '&nbsp;';

	$template->assign_vars(array(
		'PAGINATION' => $pagination,
		'PAGE_NUMBER' => sprintf ($lang['Page_of'], (floor($start / $config['topics_per_page']) + 1), ceil($num_bans['total'] / $config['topics_per_page'])),
		'L_GOTO_PAGE' => $lang['Goto_page']
		)
	);
}

$template->pparse('body');

include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);

?>