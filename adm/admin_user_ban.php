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
* @Icy Phoenix is based on phpBB
* @copyright (c) 2008 phpBB Group
*
*/

define('IN_ICYPHOENIX', true);

if ( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	$module['1610_Users']['210_Ban_Management'] = $filename;

	return;
}

// Load default header
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('./pagestart.' . PHP_EXT);

//
// Start program
//
if ( isset($_POST['submit']) )
{
	$user_bansql = '';
	$email_bansql = '';
	$ip_bansql = '';

	$user_list = array();
	if ( !empty($_POST['username']) )
	{
		$this_userdata = get_userdata($_POST['username'], true);
		if( !$this_userdata )
		{
			message_die(GENERAL_MESSAGE, $lang['No_user_id_specified'] );
		}

		// CrackerTracker v5.x
		$ctracker_config->first_admin_protection($user_id);
		// CrackerTracker v5.x

		$user_list[] = $this_userdata['user_id'];
	}

	$ip_list = array();
	if ( isset($_POST['ban_ip']) )
	{
		$ip_list_temp = explode(',', $_POST['ban_ip']);

		for($i = 0; $i < sizeof($ip_list_temp); $i++)
		{
			if ( preg_match('/^([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})[ ]*\-[ ]*([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})$/', trim($ip_list_temp[$i]), $ip_range_explode) )
			{
				//
				// Don't ask about all this, just don't ask ... !
				//
				$ip_1_counter = $ip_range_explode[1];
				$ip_1_end = $ip_range_explode[5];

				while ( $ip_1_counter <= $ip_1_end )
				{
					$ip_2_counter = ( $ip_1_counter == $ip_range_explode[1] ) ? $ip_range_explode[2] : 0;
					$ip_2_end = ( $ip_1_counter < $ip_1_end ) ? 254 : $ip_range_explode[6];

					if ( $ip_2_counter == 0 && $ip_2_end == 254 )
					{
						$ip_2_counter = 255;
						$ip_2_fragment = 255;

						$ip_list[] = encode_ip("$ip_1_counter.255.255.255");
					}

					while ( $ip_2_counter <= $ip_2_end )
					{
						$ip_3_counter = ( $ip_2_counter == $ip_range_explode[2] && $ip_1_counter == $ip_range_explode[1] ) ? $ip_range_explode[3] : 0;
						$ip_3_end = ( $ip_2_counter < $ip_2_end || $ip_1_counter < $ip_1_end ) ? 254 : $ip_range_explode[7];

						if ( $ip_3_counter == 0 && $ip_3_end == 254 )
						{
							$ip_3_counter = 255;
							$ip_3_fragment = 255;

							$ip_list[] = encode_ip("$ip_1_counter.$ip_2_counter.255.255");
						}

						while ( $ip_3_counter <= $ip_3_end )
						{
							$ip_4_counter = ( $ip_3_counter == $ip_range_explode[3] && $ip_2_counter == $ip_range_explode[2] && $ip_1_counter == $ip_range_explode[1] ) ? $ip_range_explode[4] : 0;
							$ip_4_end = ( $ip_3_counter < $ip_3_end || $ip_2_counter < $ip_2_end ) ? 254 : $ip_range_explode[8];

							if ( $ip_4_counter == 0 && $ip_4_end == 254 )
							{
								$ip_4_counter = 255;
								$ip_4_fragment = 255;

								$ip_list[] = encode_ip("$ip_1_counter.$ip_2_counter.$ip_3_counter.255");
							}

							while ( $ip_4_counter <= $ip_4_end )
							{
								$ip_list[] = encode_ip("$ip_1_counter.$ip_2_counter.$ip_3_counter.$ip_4_counter");
								$ip_4_counter++;
							}
							$ip_3_counter++;
						}
						$ip_2_counter++;
					}
					$ip_1_counter++;
				}
			}
			elseif ( preg_match('/^([\w\-_]\.?){2,}$/is', trim($ip_list_temp[$i])) )
			{
				$ip = gethostbynamel(trim($ip_list_temp[$i]));

				for($j = 0; $j < sizeof($ip); $j++)
				{
					if ( !empty($ip[$j]) )
					{
						$ip_list[] = encode_ip($ip[$j]);
					}
				}
			}
			elseif ( preg_match('/^([0-9]{1,3})\.([0-9\*]{1,3})\.([0-9\*]{1,3})\.([0-9\*]{1,3})$/', trim($ip_list_temp[$i])) )
			{
				$ip_list[] = encode_ip(str_replace('*', '255', trim($ip_list_temp[$i])));
			}
		}
	}

	$email_list = array();
	if ( isset($_POST['ban_email']) )
	{
		// CrackerTracker v5.x
		if ( !empty($_POST['ban_email']) )
		{
			include_once(IP_ROOT_PATH . 'ctracker/constants.' . PHP_EXT);
			$temp_userdata = get_userdata(CT_FIRST_ADMIN_UID, false);
			if( !$temp_userdata )
			{
				message_die(GENERAL_MESSAGE, $lang['No_user_id_specified'] );
			}

			if ( $temp_userdata['user_email'] == $_POST['ban_email'] )
			{
				message_die(GENERAL_MESSAGE, $lang['ctracker_gmb_1stadmin']);
			}
		}
		// CrackerTracker v5.x
		$email_list_temp = explode(',', $_POST['ban_email']);

		for($i = 0; $i < sizeof($email_list_temp); $i++)
		{
			//
			// This ereg match is based on one by php@unreelpro.com
			// contained in the annotated php manual at php.com (ereg
			// section)
			//
			if (preg_match('/^(([a-z0-9&\'\.\-_\+])|(\*))+@(([a-z0-9\-])|(\*))+\.([a-z0-9\-]+\.)*?[a-z]+$/is', trim($email_list_temp[$i])))
			{
				$email_list[] = trim($email_list_temp[$i]);
			}
		}
	}

	$sql = "SELECT * FROM " . BANLIST_TABLE;
	$result = $db->sql_query($sql);
	$current_banlist = $db->sql_fetchrowset($result);
	$db->sql_freeresult($result);

	$kill_session_sql = '';
	for($i = 0; $i < sizeof($user_list); $i++)
	{
		$in_banlist = false;
		for($j = 0; $j < sizeof($current_banlist); $j++)
		{
			if ( $user_list[$i] == $current_banlist[$j]['ban_userid'] )
			{
				$in_banlist = true;
			}
		}

		if ( !$in_banlist && ($user_list[$i] != ANONYMOUS) )
		{
			$kill_session_sql .= ( ( $kill_session_sql != '' ) ? ' OR ' : '' ) . "session_user_id = " . $user_list[$i];

			$sql = "INSERT INTO " . BANLIST_TABLE . " (ban_userid)
				VALUES (" . $user_list[$i] . ")";
			$db->sql_query($sql);

			$sql = "UPDATE " . USERS_TABLE . "
					SET user_warnings=".$config['max_user_bancard']."
					WHERE user_id=".$user_list[$i];
			$db->sql_query($sql);
		}
	}

	for($i = 0; $i < sizeof($ip_list); $i++)
	{
		$in_banlist = false;
		for($j = 0; $j < sizeof($current_banlist); $j++)
		{
			if ( $ip_list[$i] == $current_banlist[$j]['ban_ip'] )
			{
				$in_banlist = true;
			}
		}

		if ( !$in_banlist )
		{
			if ( preg_match('/(ff\.)|(\.ff)/is', chunk_split($ip_list[$i], 2, '.')) )
			{
				$kill_ip_sql = "session_ip LIKE '" . str_replace('.', '', preg_replace('/(ff\.)|(\.ff)/is', '%', chunk_split($ip_list[$i], 2, "."))) . "'";
			}
			else
			{
				$kill_ip_sql = "session_ip = '" . $ip_list[$i] . "'";
			}

			$kill_session_sql .= ( ( $kill_session_sql != '' ) ? ' OR ' : '' ) . $kill_ip_sql;

			$sql = "INSERT INTO " . BANLIST_TABLE . " (ban_ip)
				VALUES ('" . $ip_list[$i] . "')";
			$db->sql_query($sql);
		}
	}

	//
	// Now we'll delete all entries from the session table with any of the banned
	// user or IP info just entered into the ban table ... this will force a session
	// initialisation resulting in an instant ban
	//
	if ( $kill_session_sql != '' )
	{
		$sql = "DELETE FROM " . SESSIONS_TABLE . "
			WHERE $kill_session_sql";
		$db->sql_query($sql);
	}

	for($i = 0; $i < sizeof($email_list); $i++)
	{
		$in_banlist = false;
		for($j = 0; $j < sizeof($current_banlist); $j++)
		{
			if ( $email_list[$i] == $current_banlist[$j]['ban_email'] )
			{
				$in_banlist = true;
			}
		}

		if ( !$in_banlist )
		{
			$sql = "INSERT INTO " . BANLIST_TABLE . " (ban_email)
				VALUES ('" . str_replace("\'", "''", $email_list[$i]) . "')";
			$db->sql_query($sql);
		}
	}

	$where_sql = '';

	if ( isset($_POST['unban_user']) )
	{
		$user_list = $_POST['unban_user'];

		for($i = 0; $i < sizeof($user_list); $i++)
		{
			if ( $user_list[$i] != -1 )
			{
				$where_sql .= ( ( $where_sql != '' ) ? ', ' : '' ) . intval($user_list[$i]);
			}
		}
if (! empty($where_sql))
{
	$sql = "SELECT ban_userid FROM " . BANLIST_TABLE . "
		WHERE ban_id IN ($where_sql)";
	$result = $db->sql_query($sql);

	while ($user_id_list = $db->sql_fetchrow($result))
	{
		$where_user_sql .= ( ( $where_user_sql != '' ) ? ', ' : '' ) . $user_id_list['ban_userid'];
	}
	$sql = "UPDATE " . USERS_TABLE . "
		SET user_warnings='0'
		WHERE user_id IN ($where_user_sql)";
	$db->sql_query($sql);
}


	}

	if ( isset($_POST['unban_ip']) )
	{
		$ip_list = $_POST['unban_ip'];

		for($i = 0; $i < sizeof($ip_list); $i++)
		{
			if ( $ip_list[$i] != -1 )
			{
				$where_sql .= ( ( $where_sql != '' ) ? ', ' : '' ) . str_replace("\'", "''", $ip_list[$i]);
			}
		}
	}

	if ( isset($_POST['unban_email']) )
	{
		$email_list = $_POST['unban_email'];

		for($i = 0; $i < sizeof($email_list); $i++)
		{
			if ( $email_list[$i] != -1 )
			{
				$where_sql .= ( ( $where_sql != '' ) ? ', ' : '' ) . str_replace("\'", "''", $email_list[$i]);
			}
		}
	}

	if ( $where_sql != '' )
	{
		$sql = "DELETE FROM " . BANLIST_TABLE . "
			WHERE ban_id IN ($where_sql)";
		$db->sql_query($sql);
	}

	$db->clear_cache('ban_', USERS_CACHE_FOLDER);

	$message = $lang['Ban_update_sucessful'] . '<br /><br />' . sprintf($lang['Click_return_banadmin'], '<a href="' . append_sid("admin_user_ban." . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

	message_die(GENERAL_MESSAGE, $message);

}
else
{
	$template->set_filenames(array('body' => ADM_TPL . 'user_ban_body.tpl'));

	$template->assign_vars(array(
		'L_BAN_TITLE' => $lang['Ban_control'],
		'L_BAN_EXPLAIN' => $lang['Ban_explain'],
		'L_BAN_EXPLAIN_WARN' => $lang['Ban_explain_warn'],
		'L_IP_OR_HOSTNAME' => $lang['IP_hostname'],
		'L_EMAIL_ADDRESS' => $lang['Email_address'],
		'L_SUBMIT' => $lang['Submit'],
		'L_RESET' => $lang['Reset'],

		'S_BANLIST_ACTION' => append_sid('admin_user_ban.' . PHP_EXT)
		)
	);

	$template->assign_vars(array(
		'L_BAN_USER' => $lang['Ban_username'],
		'L_BAN_USER_EXPLAIN' => $lang['Ban_username_explain'],
		'L_BAN_IP' => $lang['Ban_IP'],
		'L_BAN_IP_EXPLAIN' => $lang['Ban_IP_explain'],
		'L_BAN_EMAIL' => $lang['Ban_email'],
		'L_BAN_EMAIL_EXPLAIN' => $lang['Ban_email_explain']
		)
	);

	$userban_count = 0;
	$ipban_count = 0;
	$emailban_count = 0;

	$sql = "SELECT b.ban_id, u.user_id, u.username
		FROM " . BANLIST_TABLE . " b, " . USERS_TABLE . " u
		WHERE u.user_id = b.ban_userid
			AND b.ban_userid <> 0
			AND u.user_id <> " . ANONYMOUS . "
		ORDER BY u.user_id ASC";
	$result = $db->sql_query($sql);
	$user_list = $db->sql_fetchrowset($result);
	$db->sql_freeresult($result);

	$select_userlist = '';
	for($i = 0; $i < sizeof($user_list); $i++)
	{
		$select_userlist .= '<option value="' . $user_list[$i]['ban_id'] . '">' . $user_list[$i]['username'] . '</option>';
		$userban_count++;
	}

	if( $select_userlist == '' )
	{
		$select_userlist = '<option value="-1">' . $lang['No_banned_users'] . '</option>';
	}

	$select_userlist = '<select name="unban_user[]" multiple="multiple" size="5">' . $select_userlist . '</select>';

	$sql = "SELECT ban_id, ban_ip, ban_email FROM " . BANLIST_TABLE;
	$result = $db->sql_query($sql);
	$banlist = $db->sql_fetchrowset($result);
	$db->sql_freeresult($result);

	$select_iplist = '';
	$select_emaillist = '';

	for($i = 0; $i < sizeof($banlist); $i++)
	{
		$ban_id = $banlist[$i]['ban_id'];

		if (!empty($banlist[$i]['ban_ip']))
		{
			$ban_ip = str_replace('255', '*', decode_ip($banlist[$i]['ban_ip']));
			$select_iplist .= '<option value="' . $ban_id . '">' . $ban_ip . '</option>';
			$ipban_count++;
		}
		elseif (!empty($banlist[$i]['ban_email']))
		{
			$ban_email = $banlist[$i]['ban_email'];
			$select_emaillist .= '<option value="' . $ban_id . '">' . $ban_email . '</option>';
			$emailban_count++;
		}
	}

	if ($select_iplist == '')
	{
		$select_iplist = '<option value="-1">' . $lang['No_banned_ip'] . '</option>';
	}

	if ($select_emaillist == '')
	{
		$select_emaillist = '<option value="-1">' . $lang['No_banned_email'] . '</option>';
	}

	$select_iplist = '<select name="unban_ip[]" multiple="multiple" size="5">' . $select_iplist . '</select>';
	$select_emaillist = '<select name="unban_email[]" multiple="multiple" size="5">' . $select_emaillist . '</select>';

	$template->assign_vars(array(
		'L_UNBAN_USER' => $lang['Unban_username'],
		'L_UNBAN_USER_EXPLAIN' => $lang['Unban_username_explain'],
		'L_UNBAN_IP' => $lang['Unban_IP'],
		'L_UNBAN_IP_EXPLAIN' => $lang['Unban_IP_explain'],
		'L_UNBAN_EMAIL' => $lang['Unban_email'],
		'L_UNBAN_EMAIL_EXPLAIN' => $lang['Unban_email_explain'],
		'L_USERNAME' => $lang['Username'],
		'L_LOOK_UP' => $lang['Look_up_User'],
		'L_FIND_USERNAME' => $lang['Find_username'],

		'U_SEARCH_USER' => append_sid(IP_ROOT_PATH . CMS_PAGE_SEARCH . '?mode=searchuser'),
		'S_UNBAN_USERLIST_SELECT' => $select_userlist,
		'S_UNBAN_IPLIST_SELECT' => $select_iplist,
		'S_UNBAN_EMAILLIST_SELECT' => $select_emaillist,
		'S_BAN_ACTION' => append_sid('admin_user_ban.' . PHP_EXT)
		)
	);
}

$template->pparse('body');

include('./page_footer_admin.' . PHP_EXT);

?>