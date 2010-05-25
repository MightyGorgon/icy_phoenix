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

//
// Adds/updates a new session to the database for the given userid.
// Returns the new session ID on success.
//
function session_begin($user_id, $user_ip, $auto_create = 0, $enable_autologin = 0, $admin = 0)
{
	global $db, $config, $lang;
	global $SID;
	global $user_agent;

	$cookiename = $config['cookie_name'];
	$cookiepath = $config['cookie_path'];
	$cookiedomain = $config['cookie_domain'];
	$cookiesecure = $config['cookie_secure'];

	if (isset($_COOKIE[$cookiename . '_sid']) || isset($_COOKIE[$cookiename . '_data']))
	{
		$session_id = isset($_COOKIE[$cookiename . '_sid']) ? $_COOKIE[$cookiename . '_sid'] : '';
		$sessiondata = isset($_COOKIE[$cookiename . '_data']) ? unserialize(stripslashes($_COOKIE[$cookiename . '_data'])) : array();
		$sessionmethod = SESSION_METHOD_COOKIE;
	}
	else
	{
		$sessiondata = array();
		$session_id = request_get_var('sid', '');
		$sessionmethod = SESSION_METHOD_GET;
	}

	$page_array = extract_current_page(IP_ROOT_PATH);
	$forum_id = request_var(POST_FORUM_URL, 0);
	$forum_id = ($forum_id < 0) ? 0 : $forum_id;
	$topic_id = request_var(POST_TOPIC_URL, 0);
	$topic_id = ($topic_id < 0) ? 0 : $topic_id;
	$page_array['page_full'] .= (!empty($forum_id)) ? ((strpos($page_array['page_full'], '?') !== false) ? '&' : '?') . '_f_=' . (int) $forum_id . 'x' : '';
	$page_array['page_full'] .= (!empty($topic_id)) ? ((strpos($page_array['page_full'], '?') !== false) ? '&' : '?') . '_t_=' . (int) $topic_id . 'x' : '';

	$page_id = $db->sql_escape(substr($page_array['page_full'], 0, 254));

	$last_visit = 0;
	$current_time = time();

	//
	if (!preg_match('/^[A-Za-z0-9]*$/', $session_id))
	{
		$session_id = '';
	}

	//
	// Are auto-logins allowed?
	// If allow_autologin is not set or is true then they are (same behaviour as old 2.0.x session code)
	//
	if (isset($config['allow_autologin']) && !$config['allow_autologin'])
	{
		$enable_autologin = $sessiondata['autologinid'] = false;
	}

	//
	// First off attempt to join with the autologin value if we have one
	// If not, just use the user_id value
	//
	$userdata = array();

	if ($user_id != ANONYMOUS)
	{
		if (isset($sessiondata['autologinid']) && (string) $sessiondata['autologinid'] != '' && $user_id)
		{
			$sql = 'SELECT u.*
				FROM ' . USERS_TABLE . ' u, ' . SESSIONS_KEYS_TABLE . ' k
				WHERE u.user_id = ' . (int) $user_id . "
					AND u.user_active = 1
					AND k.user_id = u.user_id
					AND k.key_id = '" . md5($sessiondata['autologinid']) . "'";
			$result = $db->sql_query($sql);
			$userdata = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);

			$enable_autologin = $login = 1;
		}
		elseif (!$auto_create)
		{
			$sessiondata['autologinid'] = '';
			$sessiondata['userid'] = $user_id;

			$sql = 'SELECT *
				FROM ' . USERS_TABLE . '
				WHERE user_id = ' . (int) $user_id . '
					AND user_active = 1';
			$result = $db->sql_query($sql);
			$userdata = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);

			$login = 1;
		}

		if (isset($userdata['user_level']) && ($userdata['user_level'] == JUNIOR_ADMIN))
		{
			define('IS_JUNIOR_ADMIN', true);
			$userdata['user_level'] = (!defined('IN_ADMIN') && !defined('IN_CMS')) ? ADMIN : MOD;
		}
	}

	//
	// At this point either $userdata should be populated or
	// one of the below is true
	// * Key didn't match one in the DB
	// * User does not exist
	// * User is inactive
	//
	if (!sizeof($userdata) || !is_array($userdata) || !$userdata)
	{
		$sessiondata['autologinid'] = '';
		$sessiondata['userid'] = $user_id = ANONYMOUS;
		$enable_autologin = $login = 0;

		$sql = 'SELECT *
			FROM ' . USERS_TABLE . '
			WHERE user_id = ' . (int) $user_id;
		$result = $db->sql_query($sql);
		$userdata = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);
	}

	// Initial ban check against user id, IP and email address
	preg_match('/(..)(..)(..)(..)/', $user_ip, $user_ip_parts);

	$sql = "SELECT *
		FROM " . BANLIST_TABLE . "
		WHERE ban_ip IN ('" . $user_ip_parts[1] . $user_ip_parts[2] . $user_ip_parts[3] . $user_ip_parts[4] . "', '" . $user_ip_parts[1] . $user_ip_parts[2] . $user_ip_parts[3] . "ff', '" . $user_ip_parts[1] . $user_ip_parts[2] . "ffff', '" . $user_ip_parts[1] . "ffffff')
			OR ban_userid = $user_id";
	if ($user_id != ANONYMOUS)
	{
		$sql .= " OR ban_email LIKE '" . $db->sql_escape($userdata['user_email']) . "'
			OR ban_email LIKE '" . substr($db->sql_escape($userdata['user_email']), strpos($db->sql_escape($userdata['user_email']), "@")) . "'";
	}
	$result = CACHE_BAN_INFO ? $db->sql_query($sql, 0, 'ban_', USERS_CACHE_FOLDER) : $db->sql_query($sql);

	while ($ban_info = $db->sql_fetchrow($result))
	{
		if (($ban_info['ban_userid'] == ANONYMOUS) && ($ban_info['ban_ip'] == '') && ($ban_info['ban_email'] == null))
		{
			$sql = "DELETE FROM " . BANLIST_TABLE . " WHERE ban_userid = '" . ANONYMOUS . "'";
			$db->sql_query($sql);
			$db->clear_cache('ban_', USERS_CACHE_FOLDER);
		}
		else
		{
			if ($ban_info['ban_ip'] || $ban_info['ban_userid'] || $ban_info['ban_email'] || ($ban_info['ban_userid'] && (!$ban_info['ban_expire_time'])))
			{
				if (!empty($ban_info['ban_expire_time']) && ($ban_info['ban_expire_time'] <= time()))
				{
					$sql = "DELETE FROM " . BANLIST_TABLE . " WHERE ban_id = '" . $ban_info['ban_id'] . "'";
					$db->sql_query($sql);
					$db->clear_cache('ban_', USERS_CACHE_FOLDER);
				}
				else
				{
					// We need to make sure we have at least the basic lang files included...
					if (empty($lang))
					{
						setup_basic_lang();
					}

					if (($ban_info['ban_pub_reason_mode'] == '0') || !isset($ban_info['ban_pub_reason_mode']))
					{
						$reason = $lang['You_been_banned'];
					}
					elseif ($ban_info['ban_pub_reason_mode'] == '1')
					{
						$reason = str_replace("\n", '<br />', $ban_info['ban_priv_reason']);
					}
					elseif ($ban_info['ban_pub_reason_mode'] == '2')
					{
						$reason = str_replace("\n", '<br />', $ban_info['ban_pub_reason']);
					}

					$reason = empty($reason) ? $lang['You_been_banned'] : $reason;
					message_die(CRITICAL_MESSAGE, $reason);
				}
			}
		}
	}
	$db->sql_freeresult($result);

	// Create or update the session
	$sql_ip = ($user_id == ANONYMOUS) ? " AND session_ip = '$user_ip'" : '';
	$sql = "UPDATE " . SESSIONS_TABLE . "
		SET session_ip = '$user_ip', session_start = $current_time, session_time = $current_time, session_page = '$page_id', session_logged_in = $login, session_user_agent = '" . $db->sql_escape($user_agent) . "', session_admin = $admin
		WHERE session_id = '" . $db->sql_escape($session_id) . "' $sql_ip
			AND session_user_id = '$user_id'";
	$db->sql_return_on_error(true);
	$result = $db->sql_query($sql);
	$db->sql_return_on_error(false);
	if (!$result || !$db->sql_affectedrows())
	{
		$session_id = md5(unique_id());

		$sql = "INSERT INTO " . SESSIONS_TABLE . "
			(session_id, session_user_id, session_start, session_time, session_ip, session_user_agent, session_page, session_logged_in, session_admin)
			VALUES ('" . $db->sql_escape($session_id) . "', $user_id, $current_time, $current_time, '$user_ip', '" . $db->sql_escape($user_agent) . "', '$page_id', $login, $admin)";
		$db->sql_query($sql);
	}

	if ($user_id != ANONYMOUS)
	{
		$last_visit = ($userdata['user_session_time'] > 0) ? $userdata['user_session_time'] : $current_time;

		if (!$admin)
		{
			$sql = "UPDATE " . USERS_TABLE . "
				SET user_session_time = $current_time, user_http_agents = '" . $db->sql_escape($user_agent) . "', user_session_page = '$page_id', user_lastvisit = $last_visit, user_lastlogon = " . time() .  ", user_totallogon = (user_totallogon + 1)
				WHERE user_id = $user_id";
			$db->sql_query($sql);

			// Start Advanced IP Tools Pack MOD
			if ($config['disable_logins'] == 0)
			{
				$sql = "INSERT INTO " . LOGINS_TABLE . "
					(login_id, login_userid, login_ip, login_user_agent, login_time)
					VALUES (NULL, $user_id, '$user_ip', '" . $db->sql_escape($user_agent) . "', $current_time)";
				$db->sql_query($sql);

				// Now get the results in groups based on how many topics per page parameter set in the admin panel
				$sql = "SELECT * FROM " . LOGINS_TABLE . " WHERE login_userid = $user_id ORDER BY login_id ASC";
				$result = $db->sql_query($sql);
				$max_logins = $config['last_logins_n'];

				if ($user_logins = $db->sql_numrows($result))
				{
					if($user_logins > $max_logins)
					{
						$login_rows = $db->sql_fetchrowset($result);

						for($i = 0; $i < ($user_logins - $max_logins); $i++)
						{
							$sql = "DELETE FROM " . LOGINS_TABLE . " WHERE login_id = " . $login_rows[$i]['login_id'];
							$db->sql_query($sql);
						}
					}
				}
				else
				{
					message_die(GENERAL_ERROR, 'Error: Seek Help - User ID: ' . $user_id, '', __LINE__, __FILE__, $sql);
				}
			// End Advanced IP Tools Pack MOD
			}
		}

		$userdata['user_lastvisit'] = $last_visit;

		// Regenerate the auto-login key
		if ($enable_autologin)
		{
			$auto_login_key = unique_id() . unique_id();

			if (isset($sessiondata['autologinid']) && (string) $sessiondata['autologinid'] != '')
			{
				$sql = 'UPDATE ' . SESSIONS_KEYS_TABLE . "
					SET last_ip = '$user_ip', key_id = '" . md5($auto_login_key) . "', last_login = $current_time
					WHERE key_id = '" . md5($sessiondata['autologinid']) . "'";
			}
			else
			{
				$sql = 'INSERT INTO ' . SESSIONS_KEYS_TABLE . "(key_id, user_id, last_ip, last_login)
					VALUES ('" . md5($auto_login_key) . "', $user_id, '$user_ip', $current_time)";
			}
			$db->sql_query($sql);

			$sessiondata['autologinid'] = $auto_login_key;
			unset($auto_login_key);
		}
		else
		{
			$sessiondata['autologinid'] = '';
		}

//		$sessiondata['autologinid'] = (!$admin) ? (($enable_autologin && $sessionmethod == SESSION_METHOD_COOKIE) ? $auto_login_key : '') : $sessiondata['autologinid'];
		$sessiondata['userid'] = $user_id;
	}

	$userdata['session_id'] = $session_id;
	$userdata['session_ip'] = $user_ip;
	$userdata['session_ip_clean'] = decode_ip($user_ip);
	$userdata['session_user_id'] = $user_id;
	$userdata['session_logged_in'] = $login;
	$userdata['session_page'] = $page_id;
	$userdata['session_start'] = $current_time;
	$userdata['session_time'] = $current_time;
	$userdata['session_admin'] = $admin;
	$userdata['session_key'] = $sessiondata['autologinid'];
//<!-- BEGIN Unread Post Information to Database Mod -->
	$userdata['upi2db_access'] = false;
	if (!$config['board_disable'] && $userdata['session_logged_in'] && $config['upi2db_on'])
	{
		$userdata['upi2db_access'] = check_upi2db_on($userdata);
		if ($userdata['upi2db_access'] != false)
		{
			$userdata['always_read'] = select_always_read($userdata);
			$userdata['auth_forum_id'] = auth_forum_read($userdata);
			sync_database($userdata);
		}
	}
//<!-- END Unread Post Information to Database Mod -->

	$userdata['page'] = $page_array;

	setcookie($cookiename . '_data', serialize($sessiondata), $current_time + 31536000, $cookiepath, $cookiedomain, $cookiesecure);
	setcookie($cookiename . '_sid', $session_id, 0, $cookiepath, $cookiedomain, $cookiesecure);

	$SID = ($user_id > 0) ? ('sid=' . $session_id) : '';

	return $userdata;
}

/*
* Checks for a given user session, tidies session table and updates user sessions at each page refresh
*/
function session_pagestart($user_ip, $thispage_id = '')
{
	global $db, $config, $lang;
	global $user_agent;
	global $SID;

	$cookiename = $config['cookie_name'];
	$cookiepath = $config['cookie_path'];
	$cookiedomain = $config['cookie_domain'];
	$cookiesecure = $config['cookie_secure'];

	$current_time = time();
	unset($userdata);

	if (isset($_COOKIE[$cookiename . '_sid']) || isset($_COOKIE[$cookiename . '_data']))
	{
		$sessiondata = isset($_COOKIE[$cookiename . '_data']) ? unserialize(stripslashes($_COOKIE[$cookiename . '_data'])) : array();
		$session_id = isset($_COOKIE[$cookiename . '_sid']) ? $_COOKIE[$cookiename . '_sid'] : '';
		$sessionmethod = SESSION_METHOD_COOKIE;
	}
	else
	{
		$sessiondata = array();
		$session_id = (isset($_GET['sid'])) ? $_GET['sid'] : '';
		$sessionmethod = SESSION_METHOD_GET;
	}

	if (!preg_match('/^[A-Za-z0-9]*$/', $session_id))
	{
		$session_id = '';
	}

	if (($thispage_id === false) || defined('IMG_THUMB'))
	{
		$thispage_id = '';
		$parse_session = false;
	}
	else
	{
		$parse_session = true;
	}

	$page_array = extract_current_page(IP_ROOT_PATH);
	$forum_id = request_var(POST_FORUM_URL, 0);
	$forum_id = ($forum_id < 0) ? 0 : $forum_id;
	$topic_id = request_var(POST_TOPIC_URL, 0);
	$topic_id = ($topic_id < 0) ? 0 : $topic_id;
	$page_array['page_full'] .= (!empty($forum_id)) ? ((strpos($page_array['page_full'], '?') !== false) ? '&' : '?') . '_f_=' . (int) $forum_id . 'x' : '';
	$page_array['page_full'] .= (!empty($topic_id)) ? ((strpos($page_array['page_full'], '?') !== false) ? '&' : '?') . '_t_=' . (int) $topic_id . 'x' : '';

	$thispage_id = $db->sql_escape(substr($page_array['page_full'], 0, 254));

	$last_visit = 0;
	$current_time = time();
	$userdata_processed = false;

	// Does a session exist?
	if (!empty($session_id))
	{
		$expiry_time = $current_time - $config['session_length'] ;
		// session_id exists so go ahead and attempt to grab all data in preparation
		$sql = "SELECT u.*, s.*
			FROM " . SESSIONS_TABLE . " s, " . USERS_TABLE . " u
			WHERE s.session_id = '" . $db->sql_escape($session_id) . "'
				AND u.user_id = s.session_user_id AND session_time > $expiry_time";
		$result = $db->sql_query($sql);
		$userdata = $db->sql_fetchrow($result);

		if (isset($userdata['user_level']) && ($userdata['user_level'] == JUNIOR_ADMIN))
		{
			define('IS_JUNIOR_ADMIN', true);
			$userdata['user_level'] = (!defined('IN_ADMIN') && !defined('IN_CMS')) ? ADMIN : MOD;
		}

		// Did the session exist in the DB?
		if (isset($userdata['user_id']))
		{
			// Do not check IP assuming equivalence, if IPv4 we'll check only first 24 bits ... I've been told (by vHiker) this should alleviate problems with load balanced et al proxies while retaining some reliance on IP security.
			$ip_check_s = substr($userdata['session_ip'], 0, 6);
			$ip_check_u = substr($user_ip, 0, 6);

			if ($ip_check_s == $ip_check_u)
			{
				$SID = $userdata['user_id'] > 0 ? ((($sessionmethod == SESSION_METHOD_GET) || defined('IN_ADMIN')) ? 'sid=' . $session_id : '') : '';

				// Only update session DB a minute or so after last update
				$session_page_tmp = ($userdata['user_id'] == ANONYMOUS) ? $userdata['user_session_page'] : $userdata['session_page'];
				if (((($current_time - $userdata['session_time']) > SESSION_REFRESH) || ($session_page_tmp != $thispage_id)) && ($parse_session === true) && empty($_REQUEST['explain']))
				{
					// A little trick to reset session_admin on session re-usage
					$update_admin = (!defined('IN_ADMIN') && (($current_time - $userdata['session_time']) > ($config['session_length'] + SESSION_REFRESH))) ? ', session_admin = 0' : '';

					$sql = "UPDATE " . SESSIONS_TABLE . "
						SET session_time = " . $current_time . ", session_page = '" . $thispage_id . "'" . $update_admin . "
						WHERE session_id = '" . $userdata['session_id'] . "'";
					$db->sql_query($sql);

					if ($userdata['user_id'] != ANONYMOUS)
					{
						$sql = "UPDATE " . USERS_TABLE . "
							SET user_session_time = " . $current_time . ", user_session_page = '" . $thispage_id . "', user_totalpages = user_totalpages + 1, user_totaltime = user_totaltime + ($current_time - " . $userdata['session_time'] . ")
							WHERE user_id = " . $userdata['user_id'];
						$db->sql_query($sql);
					}

					session_clean($userdata['session_id']);

					setcookie($cookiename . '_data', serialize($sessiondata), $current_time + 31536000, $cookiepath, $cookiedomain, $cookiesecure);
					setcookie($cookiename . '_sid', $session_id, 0, $cookiepath, $cookiedomain, $cookiesecure);
				}
				// Add the session_key to the userdata array if it is set
				if (isset($sessiondata['autologinid']) && $sessiondata['autologinid'] != '')
				{
					$userdata['session_key'] = $sessiondata['autologinid'];
				}
//<!-- BEGIN Unread Post Information to Database Mod -->
				$userdata['upi2db_access'] = false;
				if (!$config['board_disable'] && $userdata['session_logged_in'] && $config['upi2db_on'])
				{
					$userdata['upi2db_access'] = check_upi2db_on($userdata);
					if ($userdata['upi2db_access'] != false)
					{
						$userdata['always_read'] = select_always_read($userdata);
						$userdata['auth_forum_id'] = auth_forum_read($userdata);
						sync_database($userdata);
					}
				}
//<!-- END Unread Post Information to Database Mod -->

				$userdata_processed = true;
			}
		}
	}
	elseif(empty($sessiondata))
	{
		// try to login guest
		$sql = "SELECT u.*, s.*
			FROM " . SESSIONS_TABLE . " s, " . USERS_TABLE . " u
			WHERE s.session_ip = '$user_ip'
				AND s.session_user_id = " . ANONYMOUS . "
				AND u.user_id = s.session_user_id
					LIMIT 0, 1";
		$result = $db->sql_query($sql);
		$userdata = $db->sql_fetchrow($result);

		if (isset($userdata['user_level']) && ($userdata['user_level'] == JUNIOR_ADMIN))
		{
			define('IS_JUNIOR_ADMIN', true);
			$userdata['user_level'] = (!defined('IN_ADMIN') && !defined('IN_CMS')) ? ADMIN : MOD;
		}

		if (isset($userdata['user_id']))
		{
			if (($current_time - $userdata['session_time']) > SESSION_REFRESH)
			{
				$sql = "UPDATE " . SESSIONS_TABLE . "
					SET session_time = $current_time, session_start = $current_time, session_page = '" . $thispage_id . "'
					WHERE session_id = '" . $userdata['session_id'] . "'";
				$db->sql_query($sql);
			}
//<!-- BEGIN Unread Post Information to Database Mod -->
			//$userdata['upi2db_access'] = check_upi2db_on($userdata);
			$userdata['upi2db_access'] = false;
			if (!$config['board_disable'] && $userdata['session_logged_in'] && $config['upi2db_on'])
			{
				$userdata['upi2db_access'] = check_upi2db_on($userdata);
				if ($userdata['upi2db_access'] != false)
				{
					$userdata['always_read'] = select_always_read($userdata);
					$userdata['auth_forum_id'] = auth_forum_read($userdata);
					sync_database($userdata);
				}
			}
//<!-- END Unread Post Information to Database Mod -->

			$userdata_processed = true;
		}
	}

	if (!$userdata_processed)
	{
		// If we reach here then no (valid) session exists. So we'll create a new one, using the cookie user_id if available to pull basic user prefs.
		$user_id = (isset($sessiondata['userid'])) ? intval($sessiondata['userid']) : ANONYMOUS;

		if (!($userdata = session_begin($user_id, $user_ip, true)))
		{
			message_die(CRITICAL_ERROR, 'Error creating user session', '', __LINE__, __FILE__, $sql);
		}
	}

	$userdata['session_ip_clean'] = decode_ip($userdata['session_ip']);
	// Mighty Gorgon - BOT SESSION - BEGIN
	$userdata['is_bot'] = false;
	if ($userdata['user_id'] != ANONYMOUS)
	{
		$userdata['bot_id'] = false;
	}
	else
	{
		$userdata['bot_id'] = bots_parse($user_ip, $config['bots_color'], $user_agent, true);
		if ($userdata['bot_id'] !== false)
		{
			/*
			$userdata['user_id'] = BOT;
			$userdata['session_user_id'] = BOT;
			$userdata['session_logged_in'] = 1;
			*/
			$userdata['is_bot'] = true;
			bots_table_update(bots_parse($user_ip, $config['bots_color'], $user_agent, true, true));
		}
	}
	// Mighty Gorgon - BOT SESSION - END
	return $userdata;
}

/**
* Terminates the specified session
* It will delete the entry in the sessions table for this session,
* remove the corresponding auto-login key and reset the cookies
*/
function session_end($session_id, $user_id)
{
	global $db, $config, $userdata, $lang;
	global $SID;

	$cookiename = $config['cookie_name'];
	$cookiepath = $config['cookie_path'];
	$cookiedomain = $config['cookie_domain'];
	$cookiesecure = $config['cookie_secure'];

	$current_time = time();

	if (!preg_match('/^[A-Za-z0-9]*$/', $session_id))
	{
		return;
	}

	// Delete existing session
	$sql = 'DELETE FROM ' . SESSIONS_TABLE . "
		WHERE session_id = '$session_id'
			AND session_user_id = $user_id";
	$db->sql_query($sql);

	// Remove this auto-login entry (if applicable)
	if (isset($userdata['session_key']) && $userdata['session_key'] != '')
	{
		$autologin_key = md5($userdata['session_key']);
		$sql = 'DELETE FROM ' . SESSIONS_KEYS_TABLE . '
			WHERE user_id = ' . (int) $user_id . "
				AND key_id = '$autologin_key'";
		$db->sql_query($sql);
	}

	// We expect that message_die will be called after this function, but just in case it isn't, reset $userdata to the details for a guest
	$sql = 'SELECT *
		FROM ' . USERS_TABLE . '
		WHERE user_id = ' . ANONYMOUS;
	$result = $db->sql_query($sql);

	if (!($userdata = $db->sql_fetchrow($result)))
	{
		message_die(CRITICAL_ERROR, 'Error obtaining user details', '', __LINE__, __FILE__, $sql);
	}
	$db->sql_freeresult($result);


	setcookie($cookiename . '_data', '', $current_time - 31536000, $cookiepath, $cookiedomain, $cookiesecure);
	setcookie($cookiename . '_sid', '', $current_time - 31536000, $cookiepath, $cookiedomain, $cookiesecure);

	return true;
}

/**
* Removes expired sessions and auto-login keys from the database
*/
function session_clean($session_id)
{
	global $config, $db;

	// Delete expired sessions
	$sql = "DELETE FROM " . SESSIONS_TABLE . "
		WHERE UNIX_TIMESTAMP() - session_time >= " . 172800 . "
			AND session_id <> '$session_id'";
	$db->sql_query($sql);

	//
	// Delete expired auto-login keys
	// If max_autologin_time is not set then keys will never be deleted
	// (same behaviour as old 2.0.x session code)
	//
	if (!empty($config['max_autologin_time']) && $config['max_autologin_time'] > 0)
	{
		$sql = 'DELETE FROM ' . SESSIONS_KEYS_TABLE . '
			WHERE last_login < ' . (time() - (86400 * (int) $config['max_autologin_time']));
		$db->sql_query($sql);
	}

	return true;
	}

/**
* Reset all login keys for the specified user
* Called on password changes
*/
function session_reset_keys($user_id, $user_ip)
{
	global $db, $config, $userdata;

	$key_sql = ($user_id == $userdata['user_id'] && !empty($userdata['session_key'])) ? "AND key_id != '" . md5($userdata['session_key']) . "'" : '';

	$sql = 'DELETE FROM ' . SESSIONS_KEYS_TABLE . '
		WHERE user_id = ' . (int) $user_id . "
			$key_sql";
	$db->sql_query($sql);

	$where_sql = 'session_user_id = ' . (int) $user_id;
	$where_sql .= ($user_id == $userdata['user_id']) ? " AND session_id <> '" . $db->sql_escape($userdata['session_id']) . "'" : '';
	$sql = 'DELETE FROM ' . SESSIONS_TABLE . "
		WHERE $where_sql";
	$db->sql_query($sql);

	if (!empty($key_sql))
	{
		$auto_login_key = unique_id() . unique_id();

		$current_time = time();

		$sql = 'UPDATE ' . SESSIONS_KEYS_TABLE . "
			SET last_ip = '$user_ip', key_id = '" . md5($auto_login_key) . "', last_login = $current_time
			WHERE key_id = '" . md5($userdata['session_key']) . "'";
		$db->sql_query($sql);

		// And now rebuild the cookie
		$sessiondata['userid'] = $user_id;
		$sessiondata['autologinid'] = $auto_login_key;
		$cookiename = $config['cookie_name'];
		$cookiepath = $config['cookie_path'];
		$cookiedomain = $config['cookie_domain'];
		$cookiesecure = $config['cookie_secure'];

		setcookie($cookiename . '_data', serialize($sessiondata), $current_time + 31536000, $cookiepath, $cookiedomain, $cookiesecure);

		$userdata['session_key'] = $auto_login_key;
		unset($sessiondata);
		unset($auto_login_key);
	}
}

/**
* Sets a cookie
*
* Sets a cookie of the given name with the specified data for the given length of time. If no time is specified, a session cookie will be set.
*
* @param string $name Name of the cookie, will be automatically prefixed with the phpBB cookie name. track becomes [cookie_name]_track then.
* @param string $cookiedata The data to hold within the cookie
* @param int $cookietime The expiration time as UNIX timestamp. If 0 is provided, a session cookie is set.
*/
function set_cookie($name, $cookiedata, $cookietime)
{
	global $config;

	$name_data = rawurlencode($config['cookie_name'] . '_' . $name) . '=' . rawurlencode($cookiedata);
	$expire = gmdate('D, d-M-Y H:i:s \\G\\M\\T', $cookietime);
	$domain = (!$config['cookie_domain'] || ($config['cookie_domain'] == 'localhost') || ($config['cookie_domain'] == '127.0.0.1')) ? '' : '; domain=' . $config['cookie_domain'];

	header('Set-Cookie: ' . $name_data . (($cookietime) ? '; expires=' . $expire : '') . '; path=' . $config['cookie_path'] . $domain . ((!$config['cookie_secure']) ? '' : '; secure') . '; HttpOnly', false);
}

/*
* Append $SID to a url. Borrowed from phplib and modified. This is an extra routine utilised by the session code above and acts as a wrapper around every single URL and form action.
* If you replace the session code you must include this routine, even if it's empty.
*/
function append_sid($url, $non_html_amp = false, $char_conversion = false, $params = false, $session_id = false)
{
	global $SID, $_SID, $_EXTRA_URL, $phpbb_hook;

	$_SID = !empty($SID) ? $SID : $_SID;
	$is_amp = empty($non_html_amp) ? true : false;
	$amp_delim = !empty($is_amp) ? '&amp;' : '&';
	$url_delim = (strpos($url, '?') === false) ? '?' : $amp_delim;

	if (empty($params))
	{
		$amp_delim = (!empty($char_conversion) ? '%26' : $amp_delim);
		$url_delim = (strpos($url, '?') === false) ? '?' : $amp_delim;
		if (!empty($SID) && !preg_match('#sid=#', $url))
		{
			$url .= $url_delim . $SID;
		}
		return $url;
	}

	// Developers using the hook function need to globalise the $_SID and $_EXTRA_URL on their own and also handle it appropiatly.
	// They could mimick most of what is within this function
	if (!empty($phpbb_hook) && $phpbb_hook->call_hook(__FUNCTION__, $url, $params, $is_amp, $session_id))
	{
		if ($phpbb_hook->hook_return(__FUNCTION__))
		{
			return $phpbb_hook->hook_return_result(__FUNCTION__);
		}
	}

	$params_is_array = is_array($params);

	// Get anchor
	$anchor = '';
	if (strpos($url, '#') !== false)
	{
		list($url, $anchor) = explode('#', $url, 2);
		$anchor = '#' . $anchor;
	}
	elseif (!$params_is_array && strpos($params, '#') !== false)
	{
		list($params, $anchor) = explode('#', $params, 2);
		$anchor = '#' . $anchor;
	}

	// Handle really simple cases quickly
	if (($_SID == '') && ($session_id === false) && empty($_EXTRA_URL) && !$params_is_array && !$anchor)
	{
		if ($params === false)
		{
			return $url;
		}
		return $url . (($params !== false) ? $url_delim . $params : '');
	}

	// Assign sid if session id is not specified
	if ($session_id === false)
	{
		$session_id = $_SID;
	}

	// Appending custom url parameter?
	$append_url = (!empty($_EXTRA_URL)) ? implode($amp_delim, $_EXTRA_URL) : '';

	// Use the short variant if possible ;)
	if ($params === false)
	{
		// Append session id
		if (!$session_id)
		{
			return $url . (($append_url) ? $url_delim . $append_url : '') . $anchor;
		}
		else
		{
			return $url . (($append_url) ? $url_delim . $append_url . $amp_delim : $url_delim) . 'sid=' . $session_id . $anchor;
		}
	}

	// Build string if parameters are specified as array
	if (is_array($params))
	{
		$output = array();

		foreach ($params as $key => $item)
		{
			if ($item === NULL)
			{
				continue;
			}

			if ($key == '#')
			{
				$anchor = '#' . $item;
				continue;
			}

			$output[] = $key . '=' . $item;
		}

		$params = implode($amp_delim, $output);
	}

	// Append session id and parameters (even if they are empty)
	// If parameters are empty, the developer can still append his/her parameters without caring about the delimiter
	return $url . (($append_url) ? $url_delim . $append_url . $amp_delim : $url_delim) . $params . ((!$session_id) ? '' : $amp_delim . 'sid=' . $session_id) . $anchor;

}

?>