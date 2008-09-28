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

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

/*
* extract_current_page
* function backported from phpBB3 - Olympus
* @param string $root_path current root path (IP_ROOT_PATH)
*/
function extract_current_page($root_path)
{
	$page_array = array();

	// First of all, get the request uri...
	$script_name = (!empty($_SERVER['PHP_SELF'])) ? $_SERVER['PHP_SELF'] : getenv('PHP_SELF');
	$args = (!empty($_SERVER['QUERY_STRING'])) ? explode('&', $_SERVER['QUERY_STRING']) : explode('&', getenv('QUERY_STRING'));

	// If we are unable to get the script name we use REQUEST_URI as a failover and note it within the page array for easier support...
	if (!$script_name)
	{
		$script_name = (!empty($_SERVER['REQUEST_URI'])) ? $_SERVER['REQUEST_URI'] : getenv('REQUEST_URI');
		$page_array['failover'] = 1;
	}

	// Replace backslashes and doubled slashes (could happen on some proxy setups)
	$script_name = str_replace(array('\\', '//'), '/', $script_name);

	// Now, remove the sid and let us get a clean query string...
	foreach ($args as $key => $argument)
	{
		if (strpos($argument, 'sid=') === 0)
		{
			unset($args[$key]);
			break;
		}
	}

	// The following examples given are for an request uri of {path to the phpbb directory}/adm/index.php?i=10&b=2

	// The current query string
	$query_string = trim(implode('&', $args));

	// basenamed page name (for example: index.php)
	$page_name = htmlspecialchars(basename($script_name));

	// current directory within the phpBB root (for example: adm)
	$root_dirs = explode('/', str_replace('\\', '/', realpath($root_path)));
	$page_dirs = explode('/', str_replace('\\', '/', realpath('./')));
	$intersection = array_intersect_assoc($root_dirs, $page_dirs);

	$root_dirs = array_diff_assoc($root_dirs, $intersection);
	$page_dirs = array_diff_assoc($page_dirs, $intersection);

	$page_dir = str_repeat('../', count($root_dirs)) . implode('/', $page_dirs);

	if ($page_dir && substr($page_dir, -1, 1) == '/')
	{
		$page_dir = substr($page_dir, 0, -1);
	}

	// Current page from phpBB root (for example: adm/index.php?i=10&b=2)
	$page = (($page_dir) ? $page_dir . '/' : '') . $page_name . (($query_string) ? '?' . $query_string : '');

	// The script path from the webroot to the current directory (for example: /phpBB2/adm/) : always prefixed with / and ends in /
	$script_path = trim(str_replace('\\', '/', dirname($script_name)));

	// The script path from the webroot to the phpBB root (for example: /phpBB2/)
	$script_dirs = explode('/', $script_path);
	array_splice($script_dirs, -count($page_dirs));
	$root_script_path = implode('/', $script_dirs) . (count($root_dirs) ? '/' . implode('/', $root_dirs) : '');

	// We are on the base level (phpBB root == webroot), lets adjust the variables abit...
	if (!$root_script_path)
	{
		$root_script_path = ($page_dir) ? str_replace($page_dir, '', $script_path) : $script_path;
	}

	$script_path .= (substr($script_path, -1, 1) == '/') ? '' : '/';
	$root_script_path .= (substr($root_script_path, -1, 1) == '/') ? '' : '/';

	$page_array += array(
		'root_script_path'	=> htmlspecialchars($root_script_path),
		'script_path'				=> htmlspecialchars($script_path),
		'page'							=> $page,
		'page_dir'					=> $page_dir,
		'page_name'					=> $page_name,
		'query_string'			=> $query_string,
		'page_full'					=> $page_name . (($query_string) ? '?' . $query_string : ''),
	);

	return $page_array;
}

/**
* Set variable, used by {@link request_var the request_var function}
* function backported from phpBB3 - Olympus
* @access private
*/
function set_var(&$result, $var, $type, $multibyte = false)
{
	settype($var, $type);
	$result = $var;

	if ($type == 'string')
	{
		$result = trim(htmlspecialchars(str_replace(array("\r\n", "\r"), array("\n", "\n"), $result), ENT_COMPAT, 'UTF-8'));

		if (!empty($result))
		{
			// Make sure multibyte characters are wellformed
			if ($multibyte)
			{
				if (!preg_match('/^./u', $result))
				{
					$result = '';
				}
			}
			else
			{
				// no multibyte, allow only ASCII (0-127)
				$result = preg_replace('/[\x80-\xFF]/', '?', $result);
			}
		}

		$result = (STRIP) ? stripslashes($result) : $result;
	}
}

/**
* Used to get passed variable
* function backported from phpBB3 - Olympus
*/
function request_var($var_name, $default, $multibyte = false, $cookie = false)
{
	if (!$cookie && isset($_COOKIE[$var_name]))
	{
		if (!isset($_GET[$var_name]) && !isset($_POST[$var_name]))
		{
			return (is_array($default)) ? array() : $default;
		}
		$_REQUEST[$var_name] = isset($_POST[$var_name]) ? $_POST[$var_name] : $_GET[$var_name];
	}

	if (!isset($_REQUEST[$var_name]) || (is_array($_REQUEST[$var_name]) && !is_array($default)) || (is_array($default) && !is_array($_REQUEST[$var_name])))
	{
		return (is_array($default)) ? array() : $default;
	}

	$var = $_REQUEST[$var_name];
	if (!is_array($default))
	{
		$type = gettype($default);
	}
	else
	{
		list($key_type, $type) = each($default);
		$type = gettype($type);
		$key_type = gettype($key_type);
		if ($type == 'array')
		{
			reset($default);
			$default = current($default);
			list($sub_key_type, $sub_type) = each($default);
			$sub_type = gettype($sub_type);
			$sub_type = ($sub_type == 'array') ? 'NULL' : $sub_type;
			$sub_key_type = gettype($sub_key_type);
		}
	}

	if (is_array($var))
	{
		$_var = $var;
		$var = array();

		foreach ($_var as $k => $v)
		{
			set_var($k, $k, $key_type);
			if ($type == 'array' && is_array($v))
			{
				foreach ($v as $_k => $_v)
				{
					if (is_array($_v))
					{
						$_v = null;
					}
					set_var($_k, $_k, $sub_key_type);
					set_var($var[$k][$_k], $_v, $sub_type, $multibyte);
				}
			}
			else
			{
				if ($type == 'array' || is_array($v))
				{
					$v = null;
				}
				set_var($var[$k], $v, $type, $multibyte);
			}
		}
	}
	else
	{
		set_var($var, $var, $type, $multibyte);
	}

	return $var;
}

// Initialise user settings on page load
function init_userprefs($userdata)
{
	global $board_config, $theme, $images, $template, $lang, $db, $nav_links;
	global $mods, $list_yes_no, $tree;

	// Get all the mods settings
	$dir = @opendir(IP_ROOT_PATH . 'includes/mods_settings');
	while($file = @readdir($dir))
	{
		if(preg_match("/^mod_.*?\." . PHP_EXT . "$/", $file))
		{
			include_once(IP_ROOT_PATH . 'includes/mods_settings/' . $file);
		}
	}
	@closedir($dir);

	/*
	if (isset($_GET[LANG_URL]) || isset($_POST[LANG_URL]))
	{
		$board_config['default_lang'] = urldecode((isset($_GET[LANG_URL])) ? $_GET[LANG_URL] : $_POST[LANG_URL]);
		setcookie($board_config['cookie_name'] . '_lang', $board_config['default_lang'] , (time() + 86400), $board_config['cookie_path'], $board_config['cookie_domain'], $board_config['cookie_secure']);
	}
	*/

	$default_lang = phpbb_ltrim(basename(phpbb_rtrim($board_config['default_lang'])), "'");
	if ($userdata['user_id'] != ANONYMOUS)
	{
		$default_lang = !empty($userdata['user_lang']) ? phpbb_ltrim(basename(phpbb_rtrim($userdata['user_lang'])), "'") : $default_lang;

		$board_config['board_timezone'] = !empty($userdata['user_timezone']) ? $userdata['user_timezone'] : $board_config['board_timezone'];
		$board_config['default_dateformat'] = !empty($userdata['user_dateformat']) ? $userdata['user_dateformat'] : $board_config['default_dateformat'];

		$board_config['topics_per_page'] = !empty($userdata['user_topics_per_page']) ? $userdata['user_topics_per_page'] : $board_config['topics_per_page'];
		$board_config['posts_per_page'] = !empty($userdata['user_posts_per_page']) ? $userdata['user_posts_per_page'] : $board_config['posts_per_page'];
		$board_config['hot_threshold'] = !empty($userdata['user_hot_threshold']) ? $userdata['user_hot_threshold'] : $board_config['hot_threshold'];
	}

	if (!file_exists(@phpbb_realpath(IP_ROOT_PATH . 'language/lang_' . $default_lang . '/lang_main.' . PHP_EXT)))
	{
		if ($userdata['user_id'] != ANONYMOUS)
		{
			// For logged in users, try the board default language next
			$default_lang = phpbb_ltrim(basename(phpbb_rtrim($board_config['default_lang'])), "'");
		}
		else
		{
			// For guests it means the default language is not present, try english
			// This is a long shot since it means serious errors in the setup to reach here,
			// but english is part of a new install so it's worth us trying
			$default_lang = 'english';
		}

		if (!file_exists(@phpbb_realpath(IP_ROOT_PATH . 'language/lang_' . $default_lang . '/lang_main.' . PHP_EXT)))
		{
			message_die(CRITICAL_ERROR, 'Could not locate valid language pack');
		}
	}

	// If we've had to change the value in any way then let's write it back to the database
	// before we go any further since it means there is something wrong with it
	if (($userdata['user_id'] != ANONYMOUS) && ($userdata['user_lang'] !== $default_lang))
	{
		$sql = 'UPDATE ' . USERS_TABLE . "
			SET user_lang = '" . $default_lang . "'
			WHERE user_lang = '" . $userdata['user_lang'] . "'";

		if (!($result = $db->sql_query($sql)))
		{
			message_die(CRITICAL_ERROR, 'Could not update user language info');
		}

		$userdata['user_lang'] = $default_lang;
	}
	elseif (($userdata['user_id'] === ANONYMOUS) && ($board_config['default_lang'] !== $default_lang))
	{
		$sql = 'UPDATE ' . CONFIG_TABLE . "
			SET config_value = '" . $default_lang . "'
			WHERE config_name = 'default_lang'";

		if (!($result = $db->sql_query($sql)))
		{
			message_die(CRITICAL_ERROR, 'Could not update user language info');
		}
	}
	$board_config['default_lang'] = $default_lang;

	include(IP_ROOT_PATH . 'language/lang_' . $board_config['default_lang'] . '/lang_main.' . PHP_EXT);
	include(IP_ROOT_PATH . 'language/lang_' . $board_config['default_lang'] . '/lang_main_settings.' . PHP_EXT);
	include_once(IP_ROOT_PATH . 'language/lang_' . $board_config['default_lang'] . '/lang_main_upi2db.' . PHP_EXT);
	include_once(IP_ROOT_PATH . 'language/lang_' . $board_config['default_lang'] . '/lang_news.' . PHP_EXT);
	include_once(IP_ROOT_PATH . 'language/lang_' . $board_config['default_lang'] . '/lang_main_attach.' . PHP_EXT);
	// CrackerTracker v5.x
	include_once(IP_ROOT_PATH . 'language/lang_' . $board_config['default_lang'] . '/lang_main_cback_ctracker.' . PHP_EXT);
	// CrackerTracker v5.x

	// MG Cash MOD For IP - BEGIN
	if (defined('CASH_MOD') && defined('IN_CASHMOD'))
	{
		include(IP_ROOT_PATH . 'language/lang_' . $board_config['default_lang'] . '/lang_cash.' . PHP_EXT);
	}
	// MG Cash MOD For IP - END

	if (defined('IN_ADMIN'))
	{
		if(!file_exists(@phpbb_realpath(IP_ROOT_PATH . 'language/lang_' . $board_config['default_lang'] . '/lang_admin.' . PHP_EXT)))
		{
			$board_config['default_lang'] = 'english';
		}
		include(IP_ROOT_PATH . 'language/lang_' . $board_config['default_lang'] . '/lang_admin.' . PHP_EXT);
		include_once(IP_ROOT_PATH . 'language/lang_' . $board_config['default_lang'] . '/lang_admin_cback_ctracker.' . PHP_EXT);
		include_once(IP_ROOT_PATH . 'language/lang_' . $board_config['default_lang'] . '/lang_admin_upi2db.' . PHP_EXT);
		include_once(IP_ROOT_PATH . 'language/lang_' . $board_config['default_lang'] . '/lang_admin_attach.' . PHP_EXT);
		include_once(IP_ROOT_PATH . 'language/lang_' . $board_config['default_lang'] . '/lang_kb.' . PHP_EXT);
		include_once(IP_ROOT_PATH . 'language/lang_' . $board_config['default_lang'] . '/lang_jr_admin.' . PHP_EXT);
	}

	if (empty($tree['auth']))
	{
		get_user_tree($userdata);
	}
	// include all lang_extend_*.php
	include(IP_ROOT_PATH . 'includes/lang_extend_mac.' . PHP_EXT);
	// include this as last file... so to be able to overwrite some vars from other common langs files
	include(IP_ROOT_PATH . 'language/lang_' . $board_config['default_lang'] . '/lang_user_created.' . PHP_EXT);

	// MG Logs - BEGIN
	if ($board_config['mg_log_actions'] || $board_config['db_log_actions'])
	{
		include(IP_ROOT_PATH . 'includes/log_http_cmd.' . PHP_EXT);
	}
	// MG Logs - END

	// Set up style
	$old_default_style = $board_config['default_style'];
	if (!$board_config['override_user_style'])
	{
		if (isset($_GET[STYLE_URL]) || isset($_POST[STYLE_URL]))
		{
			$old_style = $board_config['default_style'];
			$board_config['default_style'] = urldecode((isset($_GET[STYLE_URL])) ? intval($_GET[STYLE_URL]) : intval($_POST[STYLE_URL]));
			$board_config['default_style'] = ($board_config['default_style'] == 0) ? $old_style : $board_config['default_style'];
			$style = $board_config['default_style'];
			if ($theme = setup_style($style, $old_default_style, $old_style))
			{
				if ($userdata['user_id'] != ANONYMOUS)
				{
					// user logged in --> save new style ID in user profile
					$sql = "UPDATE " . USERS_TABLE . "
						SET user_style = " . $theme['themes_id'] . "
						WHERE user_id = " . $userdata['user_id'];
					if (!$db->sql_query($sql))
					{
						message_die(CRITICAL_ERROR, 'Error updating user style', '', __LINE__, __FILE__, $sql);
					}

					$userdata['user_style'] = $theme['themes_id'];
				}
				/*
				else
				{
					$board_config['default_style'] = $theme['themes_id'];
					setcookie($board_config['cookie_name'] . '_style', $board_config['default_style'] , (time() + 86400), $board_config['cookie_path'], $board_config['cookie_domain'], $board_config['cookie_secure']);
				}
				*/
				return;
			}
		}
		if (($userdata['user_id'] != ANONYMOUS) && ($userdata['user_style'] > 0))
		{
			if ($theme = setup_style($userdata['user_style'], $old_default_style))
			{
				return;
			}
		}
	}

	$theme = setup_style($board_config['default_style'], $old_default_style);

	// Mozilla navigation bar - Default items that should be valid on all pages.
	// Defined here to correctly assign the Language Variables and be able to change the variables within code.
	$nav_links['top'] = array (
		'url' => append_sid(PORTAL_MG),
		'title' => $board_config['sitename']
	);
	$nav_links['forum'] = array (
		'url' => append_sid(FORUM_MG),
		'title' => sprintf($lang['Forum_Index'], $board_config['sitename'])
	);
	$nav_links['search'] = array (
		'url' => append_sid(SEARCH_MG),
		'title' => $lang['Search']
	);
	$nav_links['help'] = array (
		'url' => append_sid('faq.' . PHP_EXT),
		'title' => $lang['FAQ']
	);
	$nav_links['author'] = array (
		'url' => append_sid('memberlist.' . PHP_EXT),
		'title' => $lang['Memberlist']
	);
	// Add bookmarks to Navigation bar
	if ($userdata['session_logged_in'] && ($board_config['max_link_bookmarks'] > 0))
	{
		$auth_sql = '';
		$is_auth_ary = auth(AUTH_READ, AUTH_LIST_ALL, $userdata);

		$ignore_forum_sql = '';
		while(list($key, $value) = each($is_auth_ary))
		{
			if (!$value['auth_read'])
			{
				$ignore_forum_sql .= (($ignore_forum_sql != '') ? ', ' : '') . $key;
			}
		}

		if ($ignore_forum_sql != '')
		{
			$auth_sql .= ($auth_sql != '') ? " AND f.forum_id NOT IN ($ignore_forum_sql) " : "f.forum_id NOT IN ($ignore_forum_sql) ";
		}

		if ($auth_sql != '')
		{
			$sql = "SELECT t.topic_id, t.topic_title, f.forum_id
				FROM " . TOPICS_TABLE . "  t, " . BOOKMARK_TABLE . " b, " . FORUMS_TABLE . " f, " . POSTS_TABLE . " p
				WHERE t.topic_id = b.topic_id
					AND t.forum_id = f.forum_id
					AND t.topic_last_post_id = p.post_id
					AND b.user_id = " . $userdata['user_id'] . "
					AND $auth_sql
				ORDER BY p.post_time DESC
				LIMIT " . (intval($board_config['max_link_bookmarks']) + 1);
		}
		else
		{
			$sql = "SELECT t.topic_id, t.topic_title
				FROM " . TOPICS_TABLE . " t, " . BOOKMARK_TABLE . " b, " . POSTS_TABLE . " p
				WHERE t.topic_id = b.topic_id
					AND t.topic_last_post_id = p.post_id
					AND b.user_id = " . $userdata['user_id'] . "
				ORDER BY p.post_time DESC
				LIMIT " . (intval($board_config['max_link_bookmarks']) + 1);
		}
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not obtain post ids', '', __LINE__, __FILE__, $sql);
		}
		$post_rows = array();
		while ($row = $db->sql_fetchrow($result))
		{
			$post_rows[] = $row;
		}
		$db->sql_freeresult($result);

		if ($total_posts = count($post_rows))
		{
			// Define censored word matches
			$orig_word = array();
			$replacement_word = array();
			obtain_word_list($orig_word, $replacement_word);

			for($i = 0; $i < min($total_posts, $board_config['max_link_bookmarks']); $i++)
			{
				$topic_title = (count($orig_word)) ? preg_replace($orig_word, $replacement_word, $post_rows[$i]['topic_title']) : $post_rows[$i]['topic_title'];
				//
				// Add an array to $nav_links for the Mozilla navigation bar.
				// 'bookmarks' can create multiple items, therefore we are using a nested array.
				//
				$nav_links['bookmark'][$i] = array (
					'url' => append_sid(VIEWTOPIC_MG . '?' . POST_TOPIC_URL . '=' . $post_rows[$i]['topic_id']),
					'title' => $topic_title
				);
			}
			if ($total_posts > $board_config['max_link_bookmarks'])
			{
				$start = intval($board_config['max_link_bookmarks'] / $board_config['topics_per_page']) * $board_config['topics_per_page'];
				$nav_links['bookmark'][$i] = array (
					'url' => append_sid(SEARCH_MG . '?search_id=bookmarks&amp;start=' . $start),
					'title' => $lang['More_bookmarks']
				);
			}
		}
	}

	return;
}

// Get Userdata, $user can be username or user_id. If force_str is true, the username will be forced.
function get_userdata($user, $force_str = false)
{
	global $db;

	if (!is_numeric($user) || $force_str)
	{
		$user = phpbb_clean_username($user);
	}
	else
	{
		$user = intval($user);
	}

	$sql = "SELECT *
		FROM " . USERS_TABLE . "
		WHERE ";
	$sql .= ((is_integer($user)) ? "user_id = $user" : "username = '" .  str_replace("\'", "''", $user) . "'") . " AND user_id <> " . ANONYMOUS;
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Tried obtaining data for a non-existent user', '', __LINE__, __FILE__, $sql);
	}
	// Start Advanced IP Tools Pack MOD
	if ($db->sql_affectedrows() == 0)
	{
		message_die(GENERAL_ERROR, 'User does not exist.');
	}
	// End Advanced IP Tools Pack MOD

	if ($row = $db->sql_fetchrow($result))
	{
		if (isset($row['user_level']) && ($row['user_level'] == JUNIOR_ADMIN))
		{
			$row['user_level'] = (!defined('IN_ADMIN') && !defined('IN_CMS')) ? ADMIN : MOD;
		}
		return $row;
	}
	else
	{
		return false;
	}
}

function get_userdata_notifications($user, $force_str = false)
{
	global $db;

	if (!is_numeric($user) || $force_str)
	{
		$user = phpbb_clean_username($user);
	}
	else
	{
		$user = intval($user);
	}

	$sql = "SELECT *
			FROM " . USERS_TABLE . "
			WHERE ";
	$sql .= ((is_integer($user)) ? "user_id = $user" : "username = '" . str_replace("\'", "''", $user) . "'") . " AND user_id <> " . ANONYMOUS;
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Tried obtaining data for a non-existent user', '', __LINE__, __FILE__, $sql);
	}
	return ($row = $db->sql_fetchrow($result)) ? $row : false;
}

/**
* Our own generator of random values
* This uses a constantly changing value as the base for generating the values
* The board wide setting is updated once per page if this code is called
* With thanks to Anthrax101 for the inspiration on this one
* Added in phpBB 2.0.20
*/
function dss_rand()
{
	global $db, $board_config, $dss_seeded;

	$val = $board_config['rand_seed'] . microtime();
	$val = md5($val);
	$board_config['rand_seed'] = md5($board_config['rand_seed'] . $val . 'a');

	if($dss_seeded !== true)
	{
		$sql = "UPDATE " . CONFIG_TABLE . " SET
			config_value = '" . $board_config['rand_seed'] . "'
			WHERE config_name = 'rand_seed'";

		if(!$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Unable to reseed PRNG", "", __LINE__, __FILE__, $sql);
		}

		$dss_seeded = true;
	}

	return substr($val, 4, 16);
}

// added at phpBB 2.0.11 to properly format the username
function phpbb_clean_username($username)
{
	$username = substr(htmlspecialchars(str_replace("\'", "'", trim($username))), 0, 25);
	$username = phpbb_rtrim($username, "\\");
	$username = str_replace("'", "\'", $username);

	return $username;
}

/**
* This function is a wrapper for ltrim, as charlist is only supported in php >= 4.1.0
* Added in phpBB 2.0.18
*/
function phpbb_ltrim($str, $charlist = false)
{
	if ($charlist === false)
	{
		return ltrim($str);
	}

	$php_version = explode('.', PHP_VERSION);

	// php version < 4.1.0
	if ((int) $php_version[0] < 4 || ((int) $php_version[0] == 4 && (int) $php_version[1] < 1))
	{
		while ($str{0} == $charlist)
		{
			$str = substr($str, 1);
		}
	}
	else
	{
		$str = ltrim($str, $charlist);
	}

	return $str;
}

// added at phpBB 2.0.12 to fix a bug in PHP 4.3.10 (only supporting charlist in php >= 4.1.0)
function phpbb_rtrim($str, $charlist = false)
{
	if ($charlist === false)
	{
		return rtrim($str);
	}

	$php_version = explode('.', PHP_VERSION);

	// php version < 4.1.0
	if ((int) $php_version[0] < 4 || ((int) $php_version[0] == 4 && (int) $php_version[1] < 1))
	{
		while ($str{strlen($str)-1} == $charlist)
		{
			$str = substr($str, 0, strlen($str)-1);
		}
	}
	else
	{
		$str = rtrim($str, $charlist);
	}

	return $str;
}

function make_jumpbox($action, $match_forum_id = 0)
{
	global $template, $userdata, $lang, $db, $nav_links, $SID;

	return jumpbox($action, $match_forum_id);

//	$is_auth = auth(AUTH_VIEW, AUTH_LIST_ALL, $userdata);

	$sql = "SELECT c.cat_id, c.cat_title, c.cat_order
		FROM " . CATEGORIES_TABLE . " c, " . FORUMS_TABLE . " f
		".(($userdata['user_level'] == ADMIN)? "" : " AND c.cat_id<>'".HIDDEN_CAT."'")."

		WHERE f.cat_id = c.cat_id
		GROUP BY c.cat_id, c.cat_title, c.cat_order
		ORDER BY c.cat_order";
	if (!($result = $db->sql_query($sql, false, 'jumpbox_')))
	{
		message_die(GENERAL_ERROR, "Couldn't obtain category list.", "", __LINE__, __FILE__, $sql);
	}

	$category_rows = array();
	while ($row = $db->sql_fetchrow($result))
	{
		$category_rows[] = $row;
	}

	if ($total_categories = count($category_rows))
	{
		$sql = "SELECT *
			FROM " . FORUMS_TABLE . "
			ORDER BY cat_id, forum_order";
		if (!($result = $db->sql_query($sql, false, 'forums_')))
		{
			message_die(GENERAL_ERROR, 'Could not obtain forums information', '', __LINE__, __FILE__, $sql);
		}

		$boxstring = '<select name="' . POST_FORUM_URL . '" onchange="if(this.options[this.selectedIndex].value != -1){ forms[\'jumpbox\'].submit() }"><option value="-1">' . $lang['Select_forum'] . '</option>';

		$forum_rows = array();
		while ($row = $db->sql_fetchrow($result))
		{
			$forum_rows[] = $row;
		}

		if ($total_forums = count($forum_rows))
		{
			for($i = 0; $i < $total_categories; $i++)
			{
				$boxstring_forums = '';
				for($j = 0; $j < $total_forums; $j++)
				{
					//if ($forum_rows[$j]['cat_id'] == $category_rows[$i]['cat_id'] && $is_auth[$forum_rows[$j]['forum_id']]['auth_view'])
					if ($forum_rows[$j]['cat_id'] == $category_rows[$i]['cat_id'] && $forum_rows[$j]['auth_view'] <= AUTH_REG)
					{
						$selected = ($forum_rows[$j]['forum_id'] == $match_forum_id) ? 'selected="selected"' : '';
						$boxstring_forums .=  '<option value="' . $forum_rows[$j]['forum_id'] . '"' . $selected . '>' . $forum_rows[$j]['forum_name'] . '</option>';
						//
						// Add an array to $nav_links for the Mozilla navigation bar.
						// 'chapter' and 'forum' can create multiple items, therefore we are using a nested array.
						//
						$nav_links['chapter forum'][$forum_rows[$j]['forum_id']] = array (
							//SEO TOLKIT BEGIN
							//'url' => append_sid(VIEWFORUM_MG . '?' . POST_FORUM_URL . '=' . $forum_rows[$j]['forum_id']),
							'url' => append_sid(make_url_friendly($forum_rows[$j]['forum_name']) . '-vf' . $forum_rows[$j]['forum_id'] . '.html') ,
							//SEO TOLKIT END
							'title' => $forum_rows[$j]['forum_name']
						);
					}
				}

				if ($boxstring_forums != '')
				{
					$boxstring .= '<option value="-1">&nbsp;</option>';
					$boxstring .= '<option value="-1">' . $category_rows[$i]['cat_title'] . '</option>';
					$boxstring .= '<option value="-1">----------------</option>';
					$boxstring .= $boxstring_forums;
				}
			}
		}

		$boxstring .= '</select>';
	}
	else
	{
		$boxstring .= '<select name="' . POST_FORUM_URL . '" onchange="if(this.options[this.selectedIndex].value != -1){ forms[\'jumpbox\'].submit() }"></select>';
	}

	// Let the jumpbox work again in sites having additional session id checks.
	/*
	if (!empty($SID))
	{
		$boxstring .= '<input type="hidden" name="sid" value="' . $userdata['session_id'] . '" />';
	}
	*/
	$boxstring .= '<input type="hidden" name="sid" value="' . $userdata['session_id'] . '" />';

	$template->set_filenames(array('jumpbox' => 'jumpbox.tpl'));
	$template->assign_vars(array(
		'L_GO' => $lang['Go'],
		'L_JUMP_TO' => $lang['Jump_to'],
		'L_SELECT_FORUM' => $lang['Select_forum'],

		'S_JUMPBOX_SELECT' => $boxstring,
		'S_JUMPBOX_ACTION' => append_sid($action)
		)
	);
	$template->assign_var_from_handle('JUMPBOX', 'jumpbox');

	return;
}

function create_server_url()
{
	// usage: $server_url = create_server_url();
	global $board_config;

	$server_protocol = ($board_config['cookie_secure']) ? 'https://' : 'http://';
	$server_name = preg_replace('#^\/?(.*?)\/?$#', '\1', trim($board_config['server_name']));
	$server_port = ($board_config['server_port'] <> 80) ? ':' . trim($board_config['server_port']) : '';
	$script_name = preg_replace('/^\/?(.*?)\/?$/', '\1', trim($board_config['script_path']));
	$script_name = ($script_name == '') ? $script_name : '/' . $script_name;
	$server_url = $server_protocol . $server_name . $server_port . $script_name . '/';
	$server_url = (substr($server_url, strlen($server_url) - 2, 2) == '//') ? substr($server_url, 0, strlen($server_url) - 1) : $server_url;

	return $server_url;
}

/**
* Redirects the user to another page then exits the script nicely
* This function is intended for urls within the board. It's not meant to redirect to cross-domains.
*
* @param string $url The url to redirect to
* @param bool $return If true, do not redirect but return the sanitized URL. Default is no return.
*/
function redirect($url, $return = false)
{
	global $db, $lang, $board_config;

	if (!empty($db) && !$return)
	{
		$db->sql_close();
	}

	// Make sure no &amp;'s are in, this will break the redirect
	$url = str_replace('&amp;', '&', $url);

	// Make sure no linebreaks are there... to prevent http response splitting for PHP < 4.4.2
	if ((strpos(urldecode($url), "\n") !== false) || (strpos(urldecode($url), "\r") !== false) || (strpos($url, ';') !== false))
	{
		message_die(GENERAL_ERROR, 'Tried to redirect to potentially insecure url');
	}

	$server_url = create_server_url();
	$url = preg_replace('#^\/?(.*?)\/?$#', '/\1', trim($url));
	// Strip ./ and / from the beginning
	$url = str_replace('./', '', $url);
	$url = ($url && substr($url, 0, 1) == '/') ? substr($url, 1) : $url;

	// Create full url path
	$url = $server_url . $url;

	// Now, also check the protocol and for a valid url the last time...
	$allowed_protocols = array('http', 'https', 'ftp', 'ftps');
	$url_parts = parse_url($url);

	if (($url_parts === false) || empty($url_parts['scheme']) || !in_array($url_parts['scheme'], $allowed_protocols))
	{
		message_die(GENERAL_ERROR, 'Tried to redirect to potentially insecure url');
	}

	if ($return)
	{
		return $url;
	}

	// Redirect via an HTML form for PITA webservers
	if (@preg_match('#Microsoft|WebSTAR|Xitami#', getenv('SERVER_SOFTWARE')))
	{
		header('Refresh: 0; URL=' . $url);

		echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
		echo '<html xmlns="http://www.w3.org/1999/xhtml" dir="' . $lang['DIRECTION'] . '" lang="' . $lang['HEADER_LANG'] . '" xml:lang="' . $lang['HEADER_XML_LANG'] . '">';
		echo '<head>';
		echo '<meta http-equiv="content-type" content="text/html; charset=utf-8" />';
		echo '<meta http-equiv="refresh" content="0; url=' . str_replace('&', '&amp;', $url) . '" />';
		echo '<title>' . $lang['Redirect'] . '</title>';
		echo '</head>';
		echo '<body>';
		echo '<div style="text-align: center;">' . sprintf($lang['Redirect_to'], '<a href="' . str_replace('&', '&amp;', $url) . '">', '</a>') . '</div>';
		echo '</body>';
		echo '</html>';

		exit;
	}

	// Behave as per HTTP/1.1 spec for others
	header('Location: ' . $url);
	exit;
}

/**
* Meta refresh assignment
*/
function meta_refresh($time, $url)
{
	global $template;

	$url = redirect($url, true);
	// For XHTML compatibility we change back & to &amp;
	$url = str_replace('&', '&amp;', $url);

	$template->assign_vars(array('META' => '<meta http-equiv="refresh" content="' . $time . ';url=' . $url . '" />'));

	return $url;
}

/**
* Setup the default style
*/
function setup_style($style, $old_default_style, $old_style = false)
{
	global $db, $board_config, $template, $images, $themes_style;

	if (defined('CACHE_THEMES'))
	{
		include(IP_ROOT_PATH . './includes/def_themes.' . PHP_EXT);
		if (empty($themes_style))
		{
			if (!@function_exists('cache_themes'))
			{
				@include_once(IP_ROOT_PATH . 'includes/functions_extra.' . PHP_EXT);
			}
			cache_themes();
			@include(IP_ROOT_PATH . './includes/def_themes.' . PHP_EXT);
		}
	}

	if (!empty($themes_style[$style]))
	{
		$row = $themes_style[$style];
	}
	else
	{
		//$sql = "SELECT * FROM " . THEMES_TABLE . " WHERE themes_id = '" . (int) $style . "'";
		$sql = "SELECT * FROM " . THEMES_TABLE . " WHERE themes_id = '" . (int) $style . "' LIMIT 1";
		if (!($result = $db->sql_query($sql, false, 'themes_')))
		{
			message_die(CRITICAL_ERROR, 'Could not query database for theme info');
		}

		if (!($row = $db->sql_fetchrow($result)))
		{
			// We are trying to setup a style which does not exist in the database
			// Try to fallback to the board default (if the user had a custom style)
			// and then any users using this style to the default if it succeeds
			if (($style != $board_config['default_style']) || ($old_style != false))
			{
				if ($old_style != false)
				{
					$board_config['default_style'] = $old_style;
				}
				//$sql = "SELECT * FROM " . THEMES_TABLE . " WHERE themes_id = '" . (int) $board_config['default_style'] . "'";
				$sql = "SELECT * FROM " . THEMES_TABLE . " WHERE themes_id = '" . (int) $board_config['default_style'] . "' LIMIT 1";
				if (!($result = $db->sql_query($sql, false, 'themes_')))
				{
					message_die(CRITICAL_ERROR, 'Could not query database for theme info');
				}

				if ($row = $db->sql_fetchrow($result))
				{
					$db->sql_freeresult($result);
				}
				else
				{
					$style = $old_default_style;
					//message_die(CRITICAL_ERROR, "Could not get theme data for themes_id [$style]", '', __LINE__, __FILE__);
				}

				$sql = "UPDATE " . USERS_TABLE . "
					SET user_style = '" . (int) $board_config['default_style'] . "'
					WHERE user_style = '" . $style . "'";
				if (!($result = $db->sql_query($sql)))
				{
					message_die(CRITICAL_ERROR, 'Could not update user theme info');
				}
			}
			else
			{
				message_die(CRITICAL_ERROR, "Could not get theme data for themes_id [$style]", '', __LINE__, __FILE__);
			}
		}
	}
	$template_path = 'templates/';
	$template_name = $row['template_name'];

	$template = new Template(IP_ROOT_PATH . $template_path . $template_name);

	if ($template)
	{
		$current_template_path = $template_path . $template_name;
		// Mighty Gorgon - Common TPL - BEGIN
		$cfg_path = $template_name;
		$cfg_name = $template_name;
		if (defined('IN_CMS') || defined('IN_ADMIN'))
		{
			$cfg_path = 'common';
			$cfg_name = 'style';
		}
		// Mighty Gorgon - Common TPL - END
		$current_template_cfg = IP_ROOT_PATH . $template_path . $cfg_path . '/' . $cfg_name . '.cfg';
		@include($current_template_cfg);

		if (!defined('TEMPLATE_CONFIG'))
		{
			message_die(CRITICAL_ERROR, "Could not open $current_template_cfg", '', __LINE__, __FILE__);
		}

		$img_lang = (file_exists(@phpbb_realpath(IP_ROOT_PATH . $current_template_path . '/images/lang_' . $board_config['default_lang']))) ? $board_config['default_lang'] : 'english';

		while(list($key, $value) = @each($images))
		{
			if (!is_array($value))
			{
				$images[$key] = str_replace('{LANG}', 'lang_' . $img_lang, $value);
			}
		}
	}

	return $row;
}

function check_style_exists($style_id)
{
	global $db, $board_config, $template, $images, $themes_style;

	if (defined('CACHE_THEMES'))
	{
		include(IP_ROOT_PATH . './includes/def_themes.' . PHP_EXT);
		if (empty($themes_style))
		{
			if (!@function_exists('cache_themes'))
			{
				@include_once(IP_ROOT_PATH . 'includes/functions_extra.' . PHP_EXT);
			}
			cache_themes();
			@include(IP_ROOT_PATH . './includes/def_themes.' . PHP_EXT);
		}
	}

	if (!empty($themes_style[$style_id]))
	{
		$stile_exists = true;
	}
	else
	{
		//$sql = "SELECT themes_id FROM " . THEMES_TABLE . " WHERE themes_id = '" . (int) $style_id . "'";
		$sql = "SELECT themes_id FROM " . THEMES_TABLE . " WHERE themes_id = '" . (int) $style_id . "' LIMIT 1";
		if (!($result = $db->sql_query($sql, false, 'themes_')))
		{
			message_die(CRITICAL_ERROR, 'Could not query database for theme info');
		}

		if (!($row = $db->sql_fetchrow($result)))
		{
			$stile_exists = false;
		}
		else
		{
			$stile_exists = true;
		}
	}

	return $stile_exists;
}

function encode_ip($dotquad_ip)
{
	$ip_sep = explode('.', $dotquad_ip);
	return sprintf('%02x%02x%02x%02x', $ip_sep[0], $ip_sep[1], $ip_sep[2], $ip_sep[3]);
}

function decode_ip($int_ip)
{
	$hexipbang = explode('.', chunk_split($int_ip, 2, '.'));
	return hexdec($hexipbang[0]). '.' . hexdec($hexipbang[1]) . '.' . hexdec($hexipbang[2]) . '.' . hexdec($hexipbang[3]);
}

// Create calendar timestamp from timezone
function cal_date($gmepoch, $tz)
{
	global $board_config;
	return (strtotime(gmdate('M d Y H:i:s', $gmepoch + (3600 * $tz))));
}

// Create date/time from format and timezone
function create_date($format, $gmepoch, $tz)
{
	global $board_config, $lang, $userdata;
	static $translate;

	if (empty($translate) && ($board_config['default_lang'] != 'english'))
	{
		@reset($lang['datetime']);
		while (list($match, $replace) = @each($lang['datetime']))
		{
			$translate[$match] = $replace;
		}
	}

	if (!empty($userdata) && !$userdata['session_logged_in'])
	{
		$time_mode = $board_config['default_time_mode'];
		$dst_time_lag = $board_config['default_dst_time_lag'];
		$userdata['user_time_mode'] = $board_config['default_time_mode'];
		$userdata['user_dst_time_lag'] = $board_config['default_dst_time_lag'];
	}
	else
	{
		$time_mode = $userdata['user_time_mode'];
		$dst_time_lag = $userdata['user_dst_time_lag'];
	}
	switch ($time_mode)
	{
		case MANUAL_DST:
			$dst_sec = $dst_time_lag * 60;
			return (!empty($translate)) ? strtr(@gmdate($format, $gmepoch + (3600 * $tz) + $dst_sec), $translate) : @gmdate($format, $gmepoch + (3600 * $tz) + $dst_sec);
			break;
		case SERVER_SWITCH:
			$dst_sec = date('I', $gmepoch) * $dst_time_lag * 60;
			return (!empty($translate)) ? strtr(@gmdate($format, $gmepoch + (3600 * $tz) + $dst_sec), $translate) : @gmdate($format, $gmepoch + (3600 * $tz) + $dst_sec);
			break;
		default:
			return (!empty($translate)) ? strtr(@gmdate($format, $gmepoch + (3600 * $tz)), $translate) : @gmdate($format, $gmepoch + (3600 * $tz));
			break;
	}
}

function create_date_ex($format, $gmepoch, $tz)
{
	global $lang;
	static $today, $yesterday, $time;
	if(empty($today))
	{
		$today = array();
		$yesterday = array();
		$time = time();
	}
	$str = create_date($format, $gmepoch, $tz);
	if(empty($today[$format]))
	{
		$today[$format] = create_date($format, $time, $tz);
		$yesterday[$format] = create_date($format, $time - 86400, $tz);
	}
	if($str === $today[$format])
	{
		return $lang['Today_at'];
	}
	elseif($str === $yesterday[$format])
	{
		return $lang['Yesterday_at'];
	}
	return $str;
}

function create_date2($format, $gmepoch, $tz)
{
	$str = create_date_ex('d M Y', $gmepoch, $tz);
	$str .= ' ' . create_date('H:i', $gmepoch, $tz);
	return $str;
}

function create_date_simple($format, $gmepoch, $tz)
{
	global $board_config, $lang;
	$date_day = create_date($format, $gmepoch, $tz);
	if ($board_config['time_today'] < $gmepoch)
	{
		$date_day = $lang['TODAY'];
	}
	elseif ($board_config['time_yesterday'] < $gmepoch)
	{
		$date_day = $lang['YESTERDAY'];
	}
	return $date_day;
}

//
// Pagination routine, generates
// page number sequence
//
function get_page($num_items, $per_page, $start_item)
{
	$total_pages = ceil($num_items/$per_page);

	if ($total_pages == 1)
	{
		return '1';
		exit;
	}

	$on_page = floor($start_item / $per_page) + 1;
	$page_string = '';

	for($i = 0; $i < $total_pages + 1; $i++)
	{
		if($i == $on_page)
		{
			$page_string = $i;
		}
	}
	return $page_string;
}

function generate_pagination($base_url, $num_items, $per_page, $start_item, $add_prevnext_text = true, $start = 'start')
{
	global $lang;

	$total_pages = ceil($num_items / $per_page);

	if ($total_pages == 1)
	{
		return '&nbsp;';
	}

	$on_page = floor($start_item / $per_page) + 1;

	$page_string = '';
	if ($total_pages > 10)
	{
		$init_page_max = ($total_pages > 3) ? 3 : $total_pages;

		for($i = 1; $i < $init_page_max + 1; $i++)
		{
			$page_string .= ($i == $on_page) ? '<b>' . $i . '</b>' : '<a href="' . append_sid($base_url . '&amp;' . $start . '=' . (($i - 1) * $per_page)) . '">' . $i . '</a>';
			if ($i < $init_page_max)
			{
				$page_string .= ', ';
			}
		}

		if ($total_pages > 3)
		{
			if (($on_page > 1) && ($on_page < $total_pages))
			{
				$page_string .= ($on_page > 5) ? ' ... ' : ', ';

				$init_page_min = ($on_page > 4) ? $on_page : 5;
				$init_page_max = ($on_page < $total_pages - 4) ? $on_page : $total_pages - 4;

				for($i = $init_page_min - 1; $i < $init_page_max + 2; $i++)
				{
					$page_string .= ($i == $on_page) ? '<b>' . $i . '</b>' : '<a href="' . append_sid($base_url . '&amp;' . $start . '=' . (($i - 1) * $per_page)) . '">' . $i . '</a>';
					if ($i <  $init_page_max + 1)
					{
						$page_string .= ', ';
					}
				}

				$page_string .= ($on_page < $total_pages - 4) ? ' ... ' : ', ';
			}
			else
			{
				$page_string .= ' ... ';
			}

			for($i = $total_pages - 2; $i < $total_pages + 1; $i++)
			{
				$page_string .= ($i == $on_page) ? '<b>' . $i . '</b>'  : '<a href="' . append_sid($base_url . '&amp;' . $start . '=' . (($i - 1) * $per_page)) . '">' . $i . '</a>';
				if($i < $total_pages)
				{
					$page_string .= ', ';
				}
			}
		}
	}
	else
	{
		for($i = 1; $i < $total_pages + 1; $i++)
		{
			$page_string .= ($i == $on_page) ? '<b>' . $i . '</b>' : '<a href="' . append_sid($base_url . '&amp;' . $start . '=' . (($i - 1) * $per_page)) . '">' . $i . '</a>';
			if ($i < $total_pages)
			{
				$page_string .= ', ';
			}
		}
	}

	if ($add_prevnext_text)
	{
		if ($on_page > 1)
		{
			$page_string = ' <a href="' . append_sid($base_url . '&amp;' . $start . '=' . (($on_page - 2) * $per_page)) . '">' . $lang['Previous'] . '</a>&nbsp;&nbsp;' . $page_string;
		}

		if ($on_page < $total_pages)
		{
			$page_string .= '&nbsp;&nbsp;<a href="' . append_sid($base_url . '&amp;' . $start . '=' . ($on_page * $per_page)) . '">' . $lang['Next'] . '</a>';
		}

	}

	$page_string = ($page_string != '') ? $lang['Goto_page'] . ' ' . $page_string : '&nbsp;';

	return $page_string;
}

//
// This does exactly what preg_quote() does in PHP 4-ish
// If you just need the 1-parameter preg_quote call, then don't bother using this.
//
function phpbb_preg_quote($str, $delimiter)
{
	$text = preg_quote($str);
	$text = str_replace($delimiter, '\\' . $delimiter, $text);

	return $text;
}

//
// Obtain list of naughty words and build preg style replacement arrays for use by the
// calling script, note that the vars are passed as references this just makes it easier
// to return both sets of arrays
//
function obtain_word_list(&$orig_word, &$replacement_word)
{
	global $db;
	global $global_orig_word, $global_replacement_word;
		if (isset($global_orig_word))
	{
		$orig_word = $global_orig_word;
		$replacement_word = $global_replacement_word;
	}
	else
	{
		if (defined('CACHE_WORDS'))
		{
			if (!@function_exists('cache_words'))
			{
				@include_once(IP_ROOT_PATH . 'includes/functions_extra.' . PHP_EXT);
			}
			@include(IP_ROOT_PATH . './includes/def_words.' . PHP_EXT);
			if (!isset($word_replacement))
			{
				cache_words();
				@include(IP_ROOT_PATH . './includes/def_words.' . PHP_EXT);
			}
		}
		if (isset($word_replacement))
		{
			$orig_word = array();
			$replacement_word = array();
			@reset($word_replacement);
			while (list($word, $replacement) = @each($word_replacement))
			{
				$orig_word[] = '#\b(' . str_replace('\*', '\w*?', preg_quote(stripslashes($word), '#')) . ')\b#i';
				$replacement_word[] = $replacement;
			}
		}
		else
		{
			// Define censored word matches
			$sql = "SELECT word, replacement
							FROM " . WORDS_TABLE . " ORDER BY length(word) DESC";
			if(!($result = $db->sql_query($sql, false, 'word_censor_')))
			{
				message_die(GENERAL_ERROR, 'Could not get censored words from database', '', __LINE__, __FILE__, $sql);
			}

			if ($row = $db->sql_fetchrow($result))
			{
				do
				{
					$ic_word = ''; $ic_first = 0;
					$ic_chars = preg_split('//', $row['word'], -1, PREG_SPLIT_NO_EMPTY);
					foreach ($ic_chars as $char)
					{
						if (($ic_first == 1) && ($char != "*"))
						{
							$ic_word .= "_";
						}
						$ic_word .= $char; $ic_first = 1;
					}
					$ic_search = array('\*','z','s','a','b','l','i','o','p','_');
					$ic_replace = array('\w*?','(?:z|2)','(?:s|\$)','(?:a|\@)','(?:b|8|3)','(?:l|1|i|\!)','(?:i|1|l|\!)','(?:o|0)','(?:p|\?)','(?:_|\W)*');
					$orig_word[] = '#(?<=^|\W)(' . str_replace($ic_search, $ic_replace, phpbb_preg_quote($ic_word, '#')) . ')(?=\W|$)#i';
					$replacement_word[] = $row['replacement'];
				}
				while ($row = $db->sql_fetchrow($result));
			}
		}
		$global_orig_word = $orig_word;
		$global_replacement_word = $replacement_word;
	}

	return true;
}

//
// This is general replacement for die(), allows templated
// output in users (or default) language, etc.
//
// $msg_code can be one of these constants:
//
// GENERAL_MESSAGE : Use for any simple text message, eg. results
// of an operation, authorisation failures, etc.
//
// GENERAL ERROR : Use for any error which occurs _AFTER_ the
// common.php include and session code, ie. most errors in
// pages/functions
//
// CRITICAL_MESSAGE : Used when basic config data is available but
// a session may not exist, eg. banned users
//
// CRITICAL_ERROR : Used when config data cannot be obtained, eg
// no database connection. Should _not_ be used in 99.5% of cases
//
function message_die($msg_code, $msg_text = '', $msg_title = '', $err_line = '', $err_file = '', $sql = '')
{
	global $db, $template, $board_config, $theme, $lang, $nav_links, $gen_simple_header, $images;
	global $head_foot_ext, $cms_global_blocks, $cms_page_id, $cms_config_vars;
	global $userdata, $user_ip, $session_length, $starttime, $tree;

	//+MOD: Fix message_die for multiple errors MOD
	static $msg_history;
	if(!isset($msg_history))
	{
		$msg_history = array();
	}
	$msg_history[] = array(
		'msg_code' => $msg_code,
		'msg_text' => $msg_text,
		'msg_title' => $msg_title,
		'err_line' => $err_line,
		'err_file' => $err_file,
		'sql' => $sql
	);
	//-MOD: Fix message_die for multiple errors MOD
	if(defined('HAS_DIED'))
	{
	//+MOD: Fix message_die for multiple errors MOD

		//
		// This message is printed at the end of the report.
		// Of course, you can change it to suit your own needs. ;-)
		//
		$custom_error_message = 'Please, contact the %swebmaster%s. Thank you.';
		if (!empty($board_config) && !empty($board_config['board_email']))
		{
			$custom_error_message = sprintf($custom_error_message, '<a href="mailto:' . $board_config['board_email'] . '">', '</a>');
		}
		else
		{
			$custom_error_message = sprintf($custom_error_message, '', '');
		}
		echo "<html>\n<body>\n<b>Critical Error!</b><br />\nmessage_die() was called multiple times.<br />&nbsp;<hr />";
		for($i = 0; $i < count($msg_history); $i++)
		{
			echo '<b>Error #' . ($i+1) . "</b>\n<br />\n";
			if(!empty($msg_history[$i]['msg_title']))
			{
				echo '<b>' . $msg_history[$i]['msg_title'] . "</b>\n<br />\n";
			}
			echo $msg_history[$i]['msg_text'] . "\n<br /><br />\n";
			if(!empty($msg_history[$i]['err_line']))
			{
				echo '<b>Line :</b> ' . $msg_history[$i]['err_line'] . '<br /><b>File :</b> ' . $msg_history[$i]['err_file'] . "</b>\n<br />\n";
			}
			if(!empty($msg_history[$i]['sql']))
			{
				echo '<b>SQL :</b> ' . $msg_history[$i]['sql'] . "\n<br />\n";
			}
			echo "&nbsp;<hr />\n";
		}
		echo $custom_error_message . '<hr /><br clear="all">';
		die("</body>\n</html>");
	//-MOD: Fix message_die for multiple errors MOD
	}

	define('HAS_DIED', 1);

	$sql_store = $sql;

	//
	// Get SQL error if we are debugging. Do this as soon as possible to prevent
	// subsequent queries from overwriting the status of sql_error()
	//
	if (DEBUG && (($msg_code == GENERAL_ERROR) || ($msg_code == CRITICAL_ERROR)))
	{
		$sql_error = $db->sql_error();

		$debug_text = '';

		if ($sql_error['message'] != '')
		{
			$debug_text .= '<br /><br />SQL Error : ' . $sql_error['code'] . ' ' . $sql_error['message'];
		}

		if ($sql_store != '')
		{
			$debug_text .= '<br /><br />' . $sql_store;
		}

		if ($err_line != '' && $err_file != '')
		{
			$debug_text .= '<br /><br />Line : ' . $err_line . '<br />File : ' . basename($err_file);
		}
	}

	if(empty($userdata) && (($msg_code == GENERAL_MESSAGE) || ($msg_code == GENERAL_ERROR)))
	{
		// Start session management
		$userdata = session_pagestart($user_ip);
		init_userprefs($userdata);
		// End session management
	}

	// If the header hasn't been parsed yet... then do it!
	if (!defined('HEADER_INC') && ($msg_code != CRITICAL_ERROR))
	{
		if (empty($lang))
		{
			if(!file_exists(IP_ROOT_PATH . 'language/lang_' . $board_config['default_lang'] . '/lang_main.' . PHP_EXT))
			{
				$board_config['default_lang'] = 'english';
			}
			include(IP_ROOT_PATH . 'language/lang_' . $board_config['default_lang'] . '/lang_main.' . PHP_EXT);
			include(IP_ROOT_PATH . 'language/lang_' . $board_config['default_lang'] . '/lang_main_settings.' . PHP_EXT);
			// include all lang_extend_*.php
			include(IP_ROOT_PATH . 'includes/lang_extend_mac.' . PHP_EXT);
		}

		if (empty($template) || empty($theme))
		{
			$theme = setup_style($board_config['default_style'], $old_default_style);
		}

		// Load the Page Header
		if (!defined('IN_ADMIN'))
		{
			include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);
		}
		else
		{
			include(IP_ROOT_PATH . ADM . '/page_header_admin.' . PHP_EXT);
		}
	}

	switch($msg_code)
	{
		case GENERAL_MESSAGE:
			if ($msg_title == '')
			{
				$msg_title = $lang['Information'];
			}
			break;

		case CRITICAL_MESSAGE:
			if ($msg_title == '')
			{
				$msg_title = $lang['Critical_Information'];
			}
			break;

		case GENERAL_ERROR:
			if ($msg_text == '')
			{
				$msg_text = $lang['An_error_occured'];
			}

			if ($msg_title == '')
			{
				$msg_title = $lang['General_Error'];
			}
			break;

		case CRITICAL_ERROR:
			//
			// Critical errors mean we cannot rely on _ANY_ DB information being
			// available so we're going to dump out a simple echo'd statement
			//
			include(IP_ROOT_PATH . 'language/lang_english/lang_main.' . PHP_EXT);
			include(IP_ROOT_PATH . 'language/lang_english/lang_main_settings.' . PHP_EXT);

			if ($msg_text == '')
			{
				$msg_text = $lang['A_critical_error'];
			}

			if ($msg_title == '')
			{
				$msg_title = '<b>' . $lang['Critical_Error'] . '</b>';
			}
			break;
	}

	//
	// Add on DEBUG info if we've enabled debug mode and this is an error. This
	// prevents debug info being output for general messages should DEBUG be
	// set TRUE by accident (preventing confusion for the end user!)
	//
	if (DEBUG && (($msg_code == GENERAL_ERROR) || ($msg_code == CRITICAL_ERROR)))
	{
		if ($debug_text != '')
		{
			$msg_text = $msg_text . '<br /><br /><b><u>DEBUG MODE</u></b>' . $debug_text;
		}
	}

	// MG Logs - BEGIN
	//if (($board_config['mg_log_actions'] == true) && ($msg_code == GENERAL_ERROR || $msg_code == CRITICAL_ERROR))
	if ($msg_code != GENERAL_MESSAGE)
	{
		if (($board_config['mg_log_actions'] == true) || ($board_config['db_log_actions'] == '1') || ($board_config['db_log_actions'] == '2'))
		{
			$db_log = array(
				'action' => 'MESSAGE',
				//'desc' => $msg_code . ',' . $msg_title . ',' . substr($msg_text,0,20) . '...',
				'desc' => $msg_code,
				'target' => '',
			);

			$error_log = array(
				'code' => $msg_code,
				'title' => $msg_title,
				'text' => $msg_text,
			);
			ip_log('[MSG_CODE: ' . $msg_code . '] - [MSG_TITLE: ' . $msg_title . '] - [MSG_TEXT: ' . $msg_text . ']', $db_log, $error_log);
		}
	}
	// MG Logs - END

	if ($msg_code != CRITICAL_ERROR)
	{
		if (!empty($lang[$msg_text]))
		{
			$msg_text = $lang[$msg_text];
		}

		if (defined('IN_ADMIN'))
		{
			$template->set_filenames(array('message_body' => ADM_TPL . 'admin_message_body.tpl'));
		}
		elseif (defined('IN_CMS'))
		{
			$template->set_filenames(array('message_body' => COMMON_TPL . 'cms/message_body.tpl'));
		}
		else
		{
			$template->set_filenames(array('message_body' => 'message_body.tpl'));
		}

		//echo('<br />' . htmlspecialchars($template->vars['META']));
		$template->assign_vars(array(
			'MESSAGE_TITLE' => $msg_title,
			'MESSAGE_TEXT' => $msg_text
			)
		);
		$template->pparse('message_body');

		if (!defined('IN_ADMIN'))
		{
			include(IP_ROOT_PATH . 'includes/page_tail.' . PHP_EXT);
		}
		else
		{
			include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);
		}
	}
	else
	{
		echo "<html>\n<body>\n" . $msg_title . "\n<br /><br />\n" . $msg_text . "</body>\n</html>";
	}

	exit;
}

//
// This function is for compatibility with PHP 4.x's realpath()
// function.  In later versions of PHP, it needs to be called
// to do checks with some functions.  Older versions of PHP don't
// seem to need this, so we'll just return the original value.
// dougk_ff7 <October 5, 2002>
function phpbb_realpath($path)
{
	return (!@function_exists('realpath') || !@realpath(IP_ROOT_PATH . 'includes/functions.' . PHP_EXT)) ? $path : @realpath($path);
}

// Mighty Gorgon - Full Album Pack - BEGIN
//--- FLAG operation functions
function setFlag($flags, $flag)
{
	return $flags | $flag;
}

function clearFlag($flags, $flag)
{
	return ($flags & ~$flag);
}

function checkFlag($flags, $flag)
{
	return (($flags & $flag) == $flag) ? true : false;
}
// Mighty Gorgon - Full Album Pack - END

// Add function realdate for Birthday MOD
// the originate php "date()", does not work proberly on all OS, especially when going back in time
// before year 1970 (year 0), this function "realdate()", has a much larger valid date range,
// from 1901 - 2099. it returns a "like" UNIX date format (only date, related letters may be used, due to the fact that
// the given date value should already be divided by 86400 - leaving no time information left)
// a input like a UNIX timestamp divided by 86400 is expected, so
// calculation from the originate php date and mktime is easy.
// e.g. realdate ("m d Y", 3) returns the string "1 3 1970"

// UNIX users should replace this function with the below code, since this should be faster
//
function realdate($date_syntax = 'Ymd', $date = 0)
{
	return create_date($date_syntax, ($date * 86400) + 1, 0);
}

/*
function realdate($date_syntax = 'Ymd',$date = 0)
{
	global $lang;
	$i = 2;
	if ($date >= 0)
	{
		return create_date($date_syntax, $date * 86400 + 1,0);
	}
	else
	{
		$year = -(date % 1461);
		$days = $date + $year * 1461;
		while ($days < 0)
		{
			$year--;
			$days += 365;
			if ($i++ == 3)
			{
				$i = 0;
				$days++;
			}
		}
	}
	$leap_year = ($i == 0) ? true : false;
	$months_array = ($i == 0) ? array (0, 31, 60, 91, 121, 152, 182, 213, 244, 274, 305, 335, 366) : array (0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334, 365);
	for ($month = 1; $month < 12; $month++)
	{
		if ($days < $months_array[$month]) break;
	}

	$day = $days - $months_array[$month - 1] + 1;
	//you may gain speed performance by remove som of the below entry's if they are not needed/used
	return strtr ($date_syntax, array(
		'a' => '',
		'A' => '',
		'\\d' => 'd',
		'd' => ($day > 9) ? $day : '0' . $day,
		'\\D' => 'D',
		'D' => $lang['day_short'][($date - 3) % 7],
		'\\F' => 'F',
		'F' => $lang['month_long'][$month - 1],
		'g' => '',
		'G' => '',
		'H' => '',
		'h' => '',
		'i' => '',
		'I' => '',
		'\\j' => 'j',
		'j' => $day,
		'\\l' => 'l',
		'l' => $lang['day_long'][($date - 3) % 7],
		'\\L' => 'L',
		'L' => $leap_year,
		'\\m' => 'm',
		'm' => ($month>9) ? $month : '0' . $month,
		'\\M' => 'M',
		'M' => $lang['month_short'][$month - 1],
		'\\n' => 'n',
		'n' => $month,
		'O' => '',
		's' => '',
		'S' => '',
		'\\t' => 't',
		't' => $months_array[$month] - $months_array[$month - 1],
		'w' => '',
		'\\y' => 'y',
		'y' => ($year > 29) ? $year - 30 : $year + 70,
		'\\Y' => 'Y',
		'Y' => $year + 1970,
		'\\z' => 'z',
		'z' => $days,
		'\\W' => '',
		'W' => ''));
}*/
// End add - Birthday MOD


// Start add - Last visit MOD
function make_hours($base_time)
{
	global $lang;
	$years = floor($base_time / 31536000);
	$base_time = $base_time - ($years * 31536000);
	$weeks = floor($base_time / 604800);
	$base_time = $base_time - ($weeks * 604800);
	$days = floor($base_time / 86400);
	$base_time = $base_time - ($days * 86400);
	$hours = floor($base_time / 3600);
	$base_time = $base_time - ($hours * 3600);
	$min = floor($base_time / 60);
	$sek = $base_time - ($min * 60);
	if ($sek < 10)
	{
		$sek = '0' . $sek;
	}
	if ($min < 10)
	{
		$min ='0' . $min;
	}
	if ($hours < 10)
	{
		$hours = '0' . $hours;
	}
	$result = (($years) ? $years . ' ' . (($years == 1) ? $lang['Year'] : $lang['Years']) . ', ' : '') . (($years || $weeks) ? $weeks . ' ' . (($weeks == 1) ? $lang['Week'] : $lang['Weeks']) . ', ' : '') . (($years || $weeks || $days) ? $days . ' ' . (($days == 1) ? $lang['Day'] : $lang['Days']) . ', ' : '') . (($hours) ? $hours . ':' : '00:') . (($min) ? $min . ':' : '00:') . $sek;
	return ($result) ? $result : $lang['None'];
}
// End add - Last visit MOD

// Top X Posters
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

// Autolinks - BEGIN
//
// Obtain list of autolink words and build preg style replacement arrays for use by the
// calling script, note that the vars are passed as references this just makes it easier
// to return both sets of arrays
//
// This is a copy of phpBB's obtain_word_list() function with slight changes.
//
function obtain_autolink_list(&$orig_autolink, &$replacement_autolink, $id)
{
	global $db;

	//$where = ($id) ? ' WHERE link_forum = 0 OR link_forum = ' . $id : ' WHERE link_forum = -1';
	$where = ($id) ? ' WHERE link_forum = 0 OR link_forum IN (' . $id . ')' : ' WHERE link_forum = -1';

	$sql = "SELECT * FROM  " . AUTOLINKS . $where;
	if(!($result = $db->sql_query($sql, false, 'autolinks_')))
	{
		message_die(GENERAL_ERROR, 'Could not get autolink data from database', '', __LINE__, __FILE__, $sql);
	}

	if($row = $db->sql_fetchrow($result))
	{
		do
		{
			// Munge word boundaries to stop autolinks from linking to
			// themselves or other autolinks in step 2 in the function below.
			$row['link_url'] = preg_replace('/(\b)/', '\\1ALSPACEHOLDER', $row['link_url']);
			$row['link_comment'] = preg_replace('/(\b)/', '\\1ALSPACEHOLDER', $row['link_comment']);

			if($row['link_style'])
			{
				$row['link_style'] = preg_replace('/(\b)/', '\\1ALSPACEHOLDER', $row['link_style']);
				$style = ' style="' . htmlspecialchars($row['link_style']) . '" ';
			}
			else
			{
				$style = ' ';
			}
			$orig_autolink[] = '/(?<![\/\w@\.:-])(?!\.\w)(' . phpbb_preg_quote($row['link_keyword'], '/'). ')(?![\/\w@:-])(?!\.\w)/i';
			if($row['link_int'])
			{
				$replacement_autolink[] = '<a href="' . append_sid(htmlspecialchars($row['link_url'])) . '" target="_self"' . $style . 'title="' . htmlspecialchars($row['link_comment']) . '">' . htmlspecialchars($row['link_title']) . '</a>';
			}
			else
			{
				$replacement_autolink[] = '<a href="' . htmlspecialchars($row['link_url']) . '" target="_blank"' . $style . 'title="' . htmlspecialchars($row['link_comment']) . '">' . htmlspecialchars($row['link_title']) . '</a>';
			}
		}
		while($row = $db->sql_fetchrow($result));
	}

	return true;
}

//
// Taken from the POST-NUKE pnuserapi.php Autolinks user API file with slight changes.
// Original Author - Jim McDonald.
//
function autolink_transform($message, $orig, $replacement)
{
	global $board_config;

	// Step 1 - move all tags out of the text and replace them with placeholders
	preg_match_all('/(<a\s+.*?\/a>|<[^>]+>)/i', $message, $matches);
	$matchnum = count($matches[1]);
	for($i = 0; $i < $matchnum; $i++)
	{
		$message = preg_replace('/' . preg_quote($matches[1][$i], '/') . '/', "ALPLACEHOLDER{$i}PH", $message, 1);
	}

	// Step 2 - s/r of the remaining text
	if($board_config['autolink_first'])
	{
		$message = preg_replace($orig, $replacement, $message, 1);
	}
	else
	{
		$message = preg_replace($orig, $replacement, $message);
	}

	// Step 3 - replace the spaces we munged in step 1
	$message = preg_replace('/ALSPACEHOLDER/', '', $message);

	// Step 4 - replace the HTML tags that we removed in step 1
	for($i = 0; $i <$matchnum; $i++)
	{
		$message = preg_replace("/ALPLACEHOLDER{$i}PH/", $matches[1][$i], $message, 1);
	}

	return $message;
}
// Autolinks - END

/*
MG BOTS Parsing Function
*/
function bots_parse($ip_address, $bot_color = '#888888', $browser = false)
{
	/*
	// Testing!!!
	$bot_name = 'MG';
	return $bot_name;
	*/
	$bot_name = false;
	//return $bot_name;
	$bot_color = ($bot_color == '') ? '#888888' : $bot_color;

	if ($browser != false)
	{
		//if ((strpos($browser, 'MSIE') !== false) || (strpos($browser, 'Opera') !== false) || (strpos($browser, 'Firefox') !== false) || (strpos($browser, 'Mozilla') !== false))
		if ((strpos($browser, 'MSIE') !== false) || (strpos($browser, 'Opera') !== false) || (strpos($browser, 'Firefox') !== false))
		{
			$bot_name = false;
			return $bot_name;
		}
	}

	$bot_name_ary = array(
		'adsbot' => 'AdsBot [Google]',
		'alexa' => 'Alexa',
		'alta_vista' => 'Alta Vista',
		'alltheweb' => 'AllTheWeb',
		'ask_jeeves' => 'Ask Jeeves',
		'ask_jeeves_teoma' => 'Ask Jeeves',
		'baidu' => 'Baidu [Spider]',
		'becomebot' => 'Become',
		'ebay' => 'eBay Ad',
		'edintorni' => 'eDintorni Crawler',
		'exabot' => 'Exabot',
		'fast_enterprise' => 'FAST Enterprise [Crawler]',
		'fast_webcrawler' => 'FAST WebCrawler [Crawler]',
		'francis' => 'Francis',
		'gigablast' => 'Gigablast',
		'gigabot' => 'Gigabot',
		'google_adsense' => 'Google Adsense',
		'google_desktop' => 'Google Desktop',
		'google_feedfetcher' => 'Google Feedfetcher',
		'google' => 'Google',
		'heise_it_markt' => 'Heise IT-Markt [Crawler]',
		'heritrix' => 'Heritrix [Crawler]',
		'jetbot' => 'JetBot',
		'ibm_research' => 'IBM Research',
		'iccrawler_icjobs' => 'ICCrawler - ICjobs',
		'ichiro' => 'ichiro [Crawler]',
		'ie_auto_discovery' => 'IEAutoDiscovery',
		'indy_library' => 'Indy Library',
		'infoseek' => 'Infoseek',
		'inktomi' => 'Inktomi',
		'live' => 'LiveBot',
		'looksmart' => 'LookSmart',
		'lycos' => 'Lycos',
		'magpierss' => 'MagpieRSS',
		'majestic_12' => 'Majestic-12',
		'metager' => 'Metager',
		'msn_newsblogs' => 'MSN NewsBlogs',
		'msn' => 'MSN',
		'msnbot_media' => 'MSNbot Media',
		'msrbot_media' => 'Microsoft Research',
		'ng_search' => 'NG-Search',
		'noxtrum' => 'Noxtrum [Crawler]',
		'nutch' => 'Nutch',
		'nutch_cvs' => 'Nutch/CVS',
		'omniexplorer' => 'OmniExplorer',
		'online_link' => 'Online link [Validator]',
		'perl' => 'Perl Script',
		'pompos' => 'Pompos',
		'psbot' => 'psbot [Picsearch]',
		'seekport' => 'Seekport',
		'sensis' => 'Sensis [Crawler]',
		'seo_crawler' => 'SEO Crawler [Crawler]',
		'seoma' => 'Seoma [Crawler]',
		'seosearch' => 'SEOSearch [Crawler]',
		'snapbot' => 'Snap Bot',
		'snappy' => 'Snappy',
		'speedy_spider' => 'Speedy Spider',
		'steeler' => 'Steeler [Crawler]',
		'synoo' => 'Synoo',
		'telekom' => 'Telekom',
		'turnitinbot' => 'TurnitinBot',
		'twiceler' => 'Twiceler',
		'virgilio' => 'Virgilio',
		'voila' => 'Voila',
		'voyager' => 'Voyager',
		'w3' => 'W3 [Sitesearch]',
		'w3c_linkcheck' => 'W3C [Linkcheck]',
		'w3c_validator' => 'W3C [Validator]',
		'wisenut' => 'WiseNut',
		'yacy' => 'YaCy',
		'yahoo_mmcrawler' => 'Yahoo MMCrawler',
		'yahoo_slurp' => 'Yahoo! DE Slurp',
		'yahoo' => 'Yahoo! Slurp',
		'yahooseeker' => 'YahooSeeker',
	);

	$bot_color_ary = array(
		'google' => '<span style="font-weight: bold; color: #2244BB;">G</span><span style="font-weight: bold; color: #DD2222;">o</span><span style="font-weight: bold; color: #EEBB00;">o</span><span style="font-weight: bold; color: #2244BB;">g</span><span style="font-weight: bold; color: #339933;">l</span><span style="font-weight: bold; color: #DD2222;">e</span>',
		'google_adsense' => '<span style="font-weight: bold; color: #2244BB;">G</span><span style="font-weight: bold; color: #DD2222;">o</span><span style="font-weight: bold; color: #EEBB00;">o</span><span style="font-weight: bold; color: #2244BB;">g</span><span style="font-weight: bold; color: #339933;">l</span><span style="font-weight: bold; color: #DD2222;">e</span><span style="font-weight: bold; color: #DD2222;"> Adsense</span>',
		'google_desktop' => '<span style="font-weight: bold; color: #2244BB;">G</span><span style="font-weight: bold; color: #DD2222;">o</span><span style="font-weight: bold; color: #EEBB00;">o</span><span style="font-weight: bold; color: #2244BB;">g</span><span style="font-weight: bold; color: #339933;">l</span><span style="font-weight: bold; color: #DD2222;">e</span><span style="font-weight: bold; color: #DD2222;"> Desktop</span>',
		'google_feedfetcher' => '<span style="font-weight: bold; color: #2244BB;">G</span><span style="font-weight: bold; color: #DD2222;">o</span><span style="font-weight: bold; color: #EEBB00;">o</span><span style="font-weight: bold; color: #2244BB;">g</span><span style="font-weight: bold; color: #339933;">l</span><span style="font-weight: bold; color: #DD2222;">e</span><span style="font-weight: bold; color: #DD2222;"> Feedfetcher</span>',
		'yahoo_mmcrawler' => '<span style="font-weight: bold; color: #DD2222;">Yahoo!</span><span style="font-weight: bold; color: #2244BB;"> MMCrawler</span>',
		'yahoo_slurp' => '<span style="font-weight: bold; color: #DD2222;">Yahoo!</span><span style="font-weight: bold; color: #2244BB;"> DE Slurp</span><span style="font-weight: bold; color: ' . $bot_color . ';"> [Bot]</span>',
		'yahoo' => '<span style="font-weight: bold; color: #DD2222;">Yahoo!</span><span style="font-weight: bold; color: #2244BB;"> Slurp</span>',
		'live' => '<span style="font-weight: bold; color: #446688;">LiveBot</span>',
		'msn_newsblogs' => '<span style="font-weight: bold; color: #446688;">MSN NewsBlogs</span>',
		'msn' => '<span style="font-weight: bold; color: #446688;">MSN</span>',
		'msnbot_media' => '<span style="font-weight: bold; color: #446688;">MSNbot Media</span>',
	);

	// list more probable first... to speed up things!
	$bot_reg_exp_ary = array(
		'yahoo' => 'Yahoo! Slurp',
		'google' => 'Googlebot',
		'msn' => 'msnbot/',
		'live' => 'LiveBot',
		'adsbot' => 'AdsBot-Google',
		'google_adsense' => 'Mediapartners-Google',
		'yahoo_slurp' => 'Yahoo! DE Slurp',
		'yahoo_mmcrawler' => 'Yahoo-MMCrawler/',
		'yahooseeker' => 'YahooSeeker/',
		'google_desktop' => 'Google Desktop',
		'google_feedfetcher' => 'Feedfetcher-Google',
		'msn_newsblogs' => 'msnbot-NewsBlogs/',
		'msnbot_media' => 'msnbot-media/',
		'alexa' => 'ia_archiver',
		'alta_vista' => 'Scooter/',
		'alltheweb' => 'alltheweb',
		'ask_jeeves' => 'Ask Jeeves',
		'ask_jeeves_teoma' => 'teoma',
		'baidu' => 'Baiduspider+(',
		'becomebot' => 'BecomeBot/',
		'edintorni' => 'eDintorni',
		'exabot' => 'Exabot/',
		'fast_enterprise' => 'FAST Enterprise Crawler',
		'fast_webcrawler' => 'FAST-WebCrawler/',
		'francis' => 'http://www.neomo.de/',
		'gigabot' => 'Gigabot/',
		'heise_it_markt' => 'heise-IT-Markt-Crawler',
		'heritrix' => 'heritrix/1.',
		'jetbot' => 'Jetbot',
		'ibm_research' => 'ibm.com/cs/crawler',
		'iccrawler_icjobs' => 'ICCrawler - ICjobs',
		'ichiro' => 'ichiro/2',
		'ie_auto_discovery' => 'IEAutoDiscovery',
		'indy_library' => 'Indy Library',
		'infoseek' => 'Infoseek',
		'live' => 'LiveBot',
		'looksmart' => 'MARTINI',
		'lycos' => 'Lycos',
		'magpierss' => 'MagpieRSS',
		'majestic_12' => 'MJ12bot/',
		'metager' => 'MetagerBot/',
		'msrbot_media' => 'MSRBOT',
		'ng_search' => 'NG-Search/',
		'noxtrum' => 'noxtrumbot',
		'nutch' => 'http://lucene.apache.org/nutch/',
		'nutch_cvs' => 'NutchCVS/',
		'omniexplorer' => 'OmniExplorer_Bot/',
		'online_link' => 'online link validator',
		'perl' => 'libwww-perl/',
		'psbot' => 'psbot/0',
		'seekport' => 'Seekbot/',
		'sensis' => 'Sensis Web Crawler',
		'seo_crawler' => 'SEO search Crawler/',
		'seoma' => 'Seoma',
		'seosearch' => 'SEOsearch/',
		'snapbot' => 'Snapbot/',
		'snappy' => 'Snappy/',
		'speedy_spider' => 'Speedy Spider',
		'steeler' => 'http://www.tkl.iis.u-tokyo.ac.jp/~crawler/',
		'synoo' => 'SynooBot/',
		'telekom' => 'crawleradmin.t-info@telekom.de',
		'turnitinbot' => 'TurnitinBot/',
		'twiceler' => 'Twiceler',
		'voyager' => 'voyager/1.0',
		'voila' => 'VoilaBot',
		'w3' => 'W3 SiteSearch Crawler',
		'w3c_linkcheck' => 'W3C-checklink/',
		'w3c_validator' => 'W3C_',
		'wisenut' => 'http://www.WISEnutbot.com',
		'yacy' => 'yacybot',
	);

	if ($browser != false)
	{
		while ($str_check = current($bot_reg_exp_ary))
		{
			if (strpos(strtolower($browser), strtolower($str_check)) !== false)
			{
				$bot_name = array_key_exists(key($bot_reg_exp_ary), $bot_color_ary) ? $bot_color_ary[key($bot_reg_exp_ary)] : '<span style="font-weight: bold; color: ' . $bot_color . ';">' . $bot_name_ary[key($bot_reg_exp_ary)] . '</span>';
				return $bot_name;
			}
			next($bot_reg_exp_ary);
		}
	}

	$tmp_list = explode(".", decode_ip($ip_address));

	if ($tmp_list[0] == "66" && $tmp_list[1] == "249")
	{
		return $bot_color_ary['google'];
	}
	elseif ($tmp_list[0] == "72" && $tmp_list[1] == "14" && $tmp_list[2] == "199")
	{
		return $bot_color_ary['google_feedfetcher'];
	}
	elseif (($tmp_list[0] == "66" && $tmp_list[1] == "196") || ($tmp_list[0] == "68" && $tmp_list[1] == "142") || ($tmp_list[0] == "72" && $tmp_list[1] == "30") || ($tmp_list[0] == "74" && $tmp_list[1] == "6") || ($tmp_list[0] == "202" && $tmp_list[1] == "160" && $tmp_list[2] == "180"))
	{
		return $bot_color_ary['yahoo'];
	}
	elseif (($tmp_list[0] == "207" && $tmp_list[1] == "66" && $tmp_list[2] == "146") || ($tmp_list[0] == "207" && $tmp_list[1] == "46") || ($tmp_list[0] == "65" && $tmp_list[1] == "54" && $tmp_list[2] == "188") || ($tmp_list[0] == "65" && $tmp_list[1] == "54" && $tmp_list[2] == "246") || ($tmp_list[0] == "65" && $tmp_list[1] == "55" && $tmp_list[2] == "210") || ($tmp_list[0] == "65" && $tmp_list[1] == "55" && $tmp_list[2] == "213") || ($tmp_list[0] == "65" && $tmp_list[1] == "54" && $tmp_list[2] == "165"))
	{
		return $bot_color_ary['msn'];
	}
	elseif ($tmp_list[0] == "195" && $tmp_list[1] == "101" && $tmp_list[2] == "94")
	{
		$bot_name = '<span style="font-weight: bold; color: ' . $bot_color . ';">' . $bot_name_ary['voila'] . '</span>';
		return $bot_name;
	}
	elseif ($tmp_list[0] == "65" && $tmp_list[1] == "19" && $tmp_list[2] == "150" && $tmp_list[3] >= 193 && $tmp_list[3] <= 256)
	{
		$bot_name = '<span style="font-weight: bold; color: ' . $bot_color . ';">' . $bot_name_ary['omniexplorer'] . '</span>';
		return $bot_name;
	}
	elseif ($tmp_list[0] == "212" && $tmp_list[1] == "27" && $tmp_list[2] == "41" && $tmp_list[3] >= 20 && $tmp_list[3] <= 50)
	{
		$bot_name = '<span style="font-weight: bold; color: ' . $bot_color . ';">' . $bot_name_ary['pompos'] . '</span>';
		return $bot_name;
	}
	elseif (($tmp_list[0] == "66" && $tmp_list[1] == "154" && $tmp_list[2] == "102") || ($tmp_list[0] == "66" && $tmp_list[1] == "154" && $tmp_list[2] == "103"))
	{
		$bot_name = '<span style="font-weight: bold; color: ' . $bot_color . ';">' . $bot_name_ary['gigablast'] . '</span>';
		return $bot_name;
	}
	elseif ($tmp_list[0] == "212" && $tmp_list[1] == "222" && $tmp_list[2] == "51")
	{
		$bot_name = '<span style="font-weight: bold; color: ' . $bot_color . ';">' . $bot_name_ary['ebay'] . '</span>';
		return $bot_name;
	}
	elseif ($tmp_list[0] == "65" && $tmp_list[1] == "214" && $tmp_list[2] == "44")
	{
		$bot_name = '<span style="font-weight: bold; color: ' . $bot_color . ';">' . $bot_name_ary['ask_jeeves'] . '</span>';
		return $bot_name;
	}
	elseif ($tmp_list[0] == "209" && $tmp_list[1] == "237" && $tmp_list[2] == "238")
	{
		$bot_name = '<span style="font-weight: bold; color: ' . $bot_color . ';">' . $bot_name_ary['alexa'] . '</span>';
		return $bot_name;
	}
	elseif ($tmp_list[0] == "212" && $tmp_list[1] == "48" && $tmp_list[2] == "8")
	{
		$bot_name = '<span style="font-weight: bold; color: ' . $bot_color . ';">' . $bot_name_ary['virgilio'] . '</span>';
		return $bot_name;
	}
	elseif (($tmp_list[0] == "66" && $tmp_list[1] == "94" && $tmp_list[2] == "229") || ($tmp_list[0] == "66" && $tmp_list[1] == "228" && $tmp_list[2] == "165"))
	{
		$bot_name = '<span style="font-weight: bold; color: ' . $bot_color . ';">' . $bot_name_ary['inktomi'] . '</span>';
		return $bot_name;
	}
	else
	{
		return $bot_name;
	}

	return $bot_name;
}

// Ajaxed - BEGIN
function AJAX_headers()
{
	//No caching whatsoever
	header('Content-Type: application/xml');
	header('Expires: Thu, 15 Aug 1984 13:30:00 GMT');
	header('Last-Modified: '. gmdate('D, d M Y H:i:s') .' GMT');
	header('Cache-Control: no-cache, must-revalidate');  // HTTP/1.1
	header('Pragma: no-cache');                          // HTTP/1.0
}

function AJAX_message_die($data_ar)
{
	global $template, $db;

	if (!headers_sent())
	{
		AJAX_headers();
	}

	$template->set_filenames(array('ajax_result' => 'ajax_result.tpl'));

	foreach($data_ar as $key => $value)
	{
		if ($value !== '')
		{
			$value = utf8_encode(htmlspecialchars($value));
			// Get special characters in posts back ;)
			$value = preg_replace('#&amp;\#(\d{1,4});#i', '&#\1;', $value);

			$template->assign_block_vars('tag', array(
				'TAGNAME' => $key,
				'VALUE' => $value)
			);
		}
	}

	$template->pparse('ajax_result');

	// Close our DB connection.
	if (!empty($db))
	{
		$db->sql_close();
	}
	exit;
}

// This function is taken from includes/bbcode.php and renamed
// We need this in special occasions
function unhtmlspecialchars($text)
{
	$text = preg_replace("/&gt;/i", ">", $text);
	$text = preg_replace("/&lt;/i", "<", $text);
	$text = preg_replace("/&quot;/i", "\"", $text);
	$text = preg_replace("/&amp;/i", "&", $text);

	return $text;
}

/**
* RFC1738 compliant replacement to PHP's rawurldecode - which actually works with unicode (using utf-8 encoding)
* @author Ronen Botzer
* @param $source [STRING]
* @return unicode safe rawurldecoded string [STRING]
* @access public
*/
function utf8_rawurldecode($source)
{
	// Strip slashes
	$source = stripslashes($source);

	$decodedStr = '';
	$pos = 0;
	$len = strlen ($source);

	while ($pos < $len)
	{
		$charAt = substr($source, $pos, 1);
		if ($charAt == '%')
		{
			$pos++;
			$charAt = substr($source, $pos, 1);
			if ($charAt == 'u')
			{
				// we got a unicode character
				$pos++;
				$unicodeHexVal = substr($source, $pos, 4);
				$unicode = hexdec($unicodeHexVal);
				$entity = "&#". $unicode .';';
				$decodedStr .= utf8_encode($entity);
				$pos += 4;
			}
			else
			{
				// we have an escaped ascii character
				$hexVal = substr ($source, $pos, 2);
				$decodedStr .= chr (hexdec ($hexVal));
				$pos += 2;
			}
		}
		else
		{
			$decodedStr .= $charAt;
			$pos++;
		}
	}

	// Add slashes before sending it back to the browser;
	// this keeps people from trying to inject SQL with some malformed string like %2527
	return addslashes($decodedStr);
}

// Used to escape AJAX data correctly.
// functions_post.php must be included before calling this function
function ajax_htmlspecialchars($text)
{
	global $html_entities_match, $html_entities_replace;

	return preg_replace($html_entities_match, $html_entities_replace, $text);
}
// Ajaxed - END

function sql_like_expression($expression)
{
	$expression = str_replace(array('_', '%'), array("\_", "\%"), $expression);
	$expression = str_replace(array(chr(0) . "\_", chr(0) . "\%"), array('_', '%'), $expression);
	if (function_exists('mysql_real_escape_string'))
	{
		$like_expression = ('LIKE \'' . @mysql_real_escape_string($expression) . '\'');
	}
	else
	{
		$like_expression = ('LIKE \'' . str_replace("'", '%27', $expression) . '\'');
	}
	return $like_expression;
}

function get_default_avatar($user_id, $path_prefix = '')
{
	global $board_config;

	$avatar_img = '&nbsp;';
	if ($board_config['default_avatar_set'] != 3)
	{
		if (($board_config['default_avatar_set'] == 0) && ($user_id == ANONYMOUS) && ($board_config['default_avatar_guests_url'] != ''))
		{
			$avatar_img = '<img src="' . $path_prefix . $board_config['default_avatar_guests_url'] . '" alt="" />';
		}
		elseif (($board_config['default_avatar_set'] == 1) && ($user_id != ANONYMOUS) && ($board_config['default_avatar_users_url'] != ''))
		{
			$avatar_img = '<img src="' . $path_prefix . $board_config['default_avatar_users_url'] . '" alt="" />';
		}
		elseif ($board_config['default_avatar_set'] == 2)
		{
			if (($user_id == ANONYMOUS) && ($board_config['default_avatar_guests_url'] != ''))
			{
				$avatar_img = '<img src="' . $path_prefix . $board_config['default_avatar_guests_url'] . '" alt="" />';
			}
			elseif (($user_id != ANONYMOUS) && ($board_config['default_avatar_users_url'] != ''))
			{
				$avatar_img = '<img src="' . $path_prefix . $board_config['default_avatar_users_url'] . '" alt="" />';
			}
		}
	}

	return $avatar_img;
}

function user_get_avatar($user_id, $user_avatar, $user_avatar_type, $user_allow_avatar, $path_prefix = '')
{
	global $board_config;
	$user_avatar_link = '';
	if ($user_avatar_type && ($user_id != ANONYMOUS) && $user_allow_avatar)
	{
		switch($user_avatar_type)
		{
			case USER_AVATAR_UPLOAD:
				$user_avatar_link = ($board_config['allow_avatar_upload']) ? '<img src="' . $path_prefix . $board_config['avatar_path'] . '/' . $user_avatar . '" alt="" />' : '';
				break;
			case USER_AVATAR_REMOTE:
				$user_avatar_link = resize_avatar($user_avatar);
				break;
			case USER_AVATAR_GALLERY:
				$user_avatar_link = ($board_config['allow_avatar_local']) ? '<img src="' . $path_prefix . $board_config['avatar_gallery_path'] . '/' . $user_avatar . '" alt="" />' : '';
				break;
			case USER_GRAVATAR:
				$user_avatar_link = ($board_config['enable_gravatars']) ? '<img src="' . get_gravatar($user_avatar) . '" alt="" />' : '';
				break;
			default:
				$user_avatar_link = '';
		}
	}

	if ($user_avatar_link == '')
	{
		$user_avatar_link = get_default_avatar($user_id, $path_prefix);
	}

	return $user_avatar_link;
}

function resize_avatar($avatar_url)
{
	global $board_config;
	$avatar_width = 80;
	$avatar_height = 80;
	/*
	$avatar_width = $board_config['avatar_max_width'];
	$avatar_height = $board_config['avatar_max_height'];
	if (function_exists('getimagesize'))
	{
		$pic_size = @getimagesize($avatar_url);
		if ($pic_size != false)
		{
			$pic_width = $pic_size[0];
			$pic_height = $pic_size[1];
			if (($pic_width < $avatar_width) && ($pic_height < $avatar_height))
			{
				$avatar_width = $pic_width;
				$avatar_height = $pic_height;
			}
			elseif ($pic_width > $pic_height)
			{
				$avatar_height = $avatar_width * ($pic_height/$pic_width);
			}
			else
			{
				$avatar_width = $avatar_height * ($pic_width/$pic_height);
			}
		}
	}
	*/
	return ($board_config['allow_avatar_remote']) ? '<img src="' . $avatar_url . '" width="' . $avatar_width . '" height="' . $avatar_height . '" alt="" />' : '';
}

function get_gravatar($email)
{
	global $board_config;

	$image = '';
	if(!empty($email))
	{
		$image = 'http://www.gravatar.com/avatar.php?gravatar_id=' . md5($email);

		if($board_config['gravatar_rating'])
		{
			$image .= '&amp;rating=' . $board_config['gravatar_rating'];
		}

		if($board_config['gravatar_default_image'])
		{
			$server_protocol = ($board_config['cookie_secure']) ? 'https://' : 'http://';
			$server_name = preg_replace('#^\/?(.*?)\/?$#', '\1', trim($board_config['server_name']));
			$server_port = ($board_config['server_port'] <> 80) ? ':' . trim($board_config['server_port']) : '';
			$script_name = preg_replace('#^\/?(.*?)\/?$#', '\1', trim($board_config['script_path']));
			$script_name = ($script_name == '') ? $script_name : '/' . $script_name;
			$url = preg_replace('#^\/?(.*?)\/?$#', '/\1', trim($board_config['gravatar_default_image']));

			$default_image = $server_protocol . $server_name . $server_port . $script_name . $url;
			$image .= '&amp;default=' . urlencode($default_image);
		}

		$max_size = ($board_config['avatar_max_width'] <= $board_config['avatar_max_height']) ? $board_config['avatar_max_width'] : $board_config['avatar_max_height'];
		$image .= ($max_size < 80) ? '&amp;size=' . $max_size : '';
	}

	return $image;
}

function user_get_thanks_received($user_id)
{
	global $db;
	$total_thanks_received = 0;
	$sql = "SELECT COUNT(th.topic_id) AS total_thanks
					FROM " . THANKS_TABLE . " th, " . TOPICS_TABLE . " t
					WHERE t.topic_poster = '" . $user_id . "'
						AND t.topic_id = th.topic_id";
	if(!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Could not query thanks informations", "", __LINE__, __FILE__, $sql);
	}
	if (!$row = $db->sql_fetchrow($result))
	{
		message_die(GENERAL_ERROR, "Could not query thanks informations", "", __LINE__, __FILE__, $sql);
	}
	$total_thanks_received = $row['total_thanks'];
	$db->sql_freeresult($result);
	return $total_thanks_received;
}

function build_im_link($im_type, $im_id, $im_lang = '', $im_img = false)
{
	switch($im_type)
	{
		case 'aim':
			$im_ref = 'aim:goim?screenname=' . $im_id . '&amp;message=Hello';
			break;
		case 'icq':
			// http://wwp.icq.com/scripts/search.dll?to=
			$im_ref = 'http://www.icq.com/people/webmsg.php?to=' . $im_id;
			break;
		case 'msn':
			$im_ref = 'http://spaces.live.com/' . $im_id;
			break;
		case 'yahoo':
			$im_ref = 'http://edit.yahoo.com/config/send_webmesg?.target=' . $im_id . '&amp;.src=pg';
			break;
		case 'skype':
			$im_ref = 'callto://' . $im_id;
			break;
		default:
			$im_ref = $im_id;
	}
	$link_content = ($im_img !== false) ? ('<img src="' . $im_img . '" alt="' . $im_lang . '" title="' . $im_id . '" />') : $im_lang;
	$im_link = '<a href="' . $im_ref . '">' . $link_content . '</a>';
	return $im_link;
}

function get_founder_id($clear_cache = false)
{
	global $db, $board_config;
	if ($clear_cache)
	{
		$db->clear_cache('founder_id_');
	}
	$founder_id = (intval($board_config['main_admin_id']) >= 2) ? $board_config['main_admin_id'] : '2';
	if ($founder_id != '2')
	{
		$sql = "SELECT user_id
			FROM " . USERS_TABLE . "
			WHERE user_id = '" . $founder_id . "'
			LIMIT 1";
		if (!($result = $db->sql_query($sql, false, 'founder_id_')))
		{
			message_die(GENERAL_ERROR, 'Couldn\'t obtain user id', '', __LINE__, __FILE__, $sql);
		}
		$founder_id = '2';
		while ($row = $db->sql_fetchrow($result))
		{
			$founder_id = $row['user_id'];
		}
		$db->sql_freeresult($result);
	}
	return $founder_id;
}

function empty_cache_folders($cg = false)
{

	$skip_files = array(
		'.',
		'..',
		'.htaccess',
		'index.htm',
		'index.html',
		'index.' . PHP_EXT,
	);

	$sql_prefix = 'sql_';
	$tpl_prefix = 'tpl_';
	$phpbb_update_prefix = 'phpbb_';
	$cache_prefix = 'cache_';
	$cg_prefix = POST_USERS_URL . '_';
	$dat_extension = '.dat';

	$dirs_array = array(USERS_CACHE_FOLDER, MAIN_CACHE_FOLDER, SQL_CACHE_FOLDER);
	for ($i = 0; $i < count($dirs_array); $i++)
	{
		$dir = $dirs_array[$i];
		$res = @opendir($dir);
		while(($file = readdir($res)) !== false)
		{
			$file_full_path = $dir . $file;
			if (!in_array($file, $skip_files))
			{
				$res2 = @unlink($file_full_path);
			}
		}
		closedir($res);
		if ($cg == true)
		{
			return true;
		}
	}
	return true;
}

function empty_images_cache_folders()
{

	$skip_files = array(
		'.',
		'..',
		'.htaccess',
		'index.htm',
		'index.html',
		'index.' . PHP_EXT,
	);

	$dirs_array = array(POSTED_IMAGES_THUMBS_PATH, IP_ROOT_PATH . ALBUM_CACHE_PATH, IP_ROOT_PATH . ALBUM_MED_CACHE_PATH, IP_ROOT_PATH . ALBUM_WM_CACHE_PATH);
	for ($i = 0; $i < count($dirs_array); $i++)
	{
		$dir = $dirs_array[$i];
		$res = @opendir($dir);
		while(($file = readdir($res)) !== false)
		{
			$file_full_path = $dir . $file;
			if (!in_array($file, $skip_files))
			{
				if (is_dir($file_full_path))
				{
					$subres = @opendir($file_full_path);
					while(($subfile = readdir($subres)) !== false)
					{
						$subfile_full_path = $file_full_path . '/' . $subfile;
						if (!in_array($subfile, $skip_files))
						{
							if(preg_match('/(\.gif$|\.png$|\.jpg|\.jpeg)$/is', $subfile))
							{
								$res2 = @unlink($subfile_full_path);
							}
						}
					}
					closedir($subres);
				}
				elseif(preg_match('/(\.gif$|\.png$|\.jpg|\.jpeg)$/is', $file))
				{
					$res2 = @unlink($file_full_path);
				}
			}
		}
		closedir($res);
		if ($cg == true)
		{
			return true;
		}
	}
	return true;
}

// Activity - BEGIN
//if (defined('ACTIVITY_MOD'))
if (defined('ACTIVITY_MOD') && (ACTIVITY_MOD == true))
{
	include_once(IP_ROOT_PATH . ACTIVITY_MOD_PATH . 'includes/functions_amod_includes_functions.' . PHP_EXT);
}
// Activity - END

?>