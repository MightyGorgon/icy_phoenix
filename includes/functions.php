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
		$script_name = (($pos = strpos($script_name, '?')) !== false) ? substr($script_name, 0, $pos) : $script_name;
		$page_array['failover'] = 1;
	}

	// Replace backslashes and doubled slashes (could happen on some proxy setups)
	$script_name = str_replace(array('\\', '//'), '/', $script_name);

	// Now, remove the sid and let us get a clean query string...
	$use_args = array();

	// Since some browser do not encode correctly we need to do this with some "special" characters...
	// " -> %22, ' => %27, < -> %3C, > -> %3E
	$find = array('"', "'", '<', '>');
	$replace = array('%22', '%27', '%3C', '%3E');

	foreach ($args as $key => $argument)
	{
		if (strpos($argument, 'sid=') === 0)
		{
			continue;
		}

		$use_args[] = str_replace($find, $replace, $argument);
	}
	unset($args);

	// The following examples given are for an request uri of {path to the phpbb directory}/adm/index.php?i=10&b=2

	// The current query string
	$query_string = trim(implode('&', $use_args));

	// basenamed page name (for example: index.php)
	$page_name = basename($script_name);
	$page_name = urlencode(htmlspecialchars($page_name));

	// current directory within the phpBB root (for example: adm)
	$root_dirs = explode('/', str_replace('\\', '/', phpbb_realpath($root_path)));
	$page_dirs = explode('/', str_replace('\\', '/', phpbb_realpath('./')));
	$intersection = array_intersect_assoc($root_dirs, $page_dirs);

	$root_dirs = array_diff_assoc($root_dirs, $intersection);
	$page_dirs = array_diff_assoc($page_dirs, $intersection);

	$page_dir = str_repeat('../', sizeof($root_dirs)) . implode('/', $page_dirs);

	if ($page_dir && substr($page_dir, -1, 1) == '/')
	{
		$page_dir = substr($page_dir, 0, -1);
	}

	// Current page from Icy Phoenix root (for example: adm/index.php?i=10&b=2)
	$page = (($page_dir) ? $page_dir . '/' : '') . $page_name . (($query_string) ? '?' . $query_string : '');

	// The script path from the webroot to the current directory (for example: /ip/adm/) : always prefixed with / and ends in /
	$script_path = trim(str_replace('\\', '/', dirname($script_name)));

	// The script path from the webroot to the Icy Phoenix root (for example: /ip/)
	$script_dirs = explode('/', $script_path);
	array_splice($script_dirs, -sizeof($page_dirs));
	$root_script_path = implode('/', $script_dirs) . (sizeof($root_dirs) ? '/' . implode('/', $root_dirs) : '');

	// We are on the base level (Icy Phoenix root == webroot), lets adjust the variables a bit...
	if (!$root_script_path)
	{
		$root_script_path = ($page_dir) ? str_replace($page_dir, '', $script_path) : $script_path;
	}

	$script_path .= (substr($script_path, -1, 1) == '/') ? '' : '/';
	$root_script_path .= (substr($root_script_path, -1, 1) == '/') ? '' : '/';
	$post_forum_url = (defined('POST_FORUM_URL') ? POST_FORUM_URL : 'f');

	$page_array += array(
		'root_script_path'	=> str_replace(' ', '%20', htmlspecialchars($root_script_path)),
		'script_path'				=> str_replace(' ', '%20', htmlspecialchars($script_path)),
		'page_dir'					=> $page_dir,
		'page_name'					=> $page_name,
		'page'							=> $page,
		'query_string'			=> $query_string,
		'forum'							=> (isset($_REQUEST[$post_forum_url]) && $_REQUEST[$post_forum_url] > 0) ? (int) $_REQUEST[$post_forum_url] : 0,
		'page_full'					=> $page_name . (($query_string) ? '?' . $query_string : ''),
	);

	return $page_array;
}

/**
* Get valid hostname/port. HTTP_HOST is used, SERVER_NAME if HTTP_HOST not present.
* function backported from phpBB3 - Olympus
*/
/*
function extract_current_hostname()
{
	global $board_config;

	// Get hostname
	$host = (!empty($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST'] : ((!empty($_SERVER['SERVER_NAME'])) ? $_SERVER['SERVER_NAME'] : getenv('SERVER_NAME'));

	// Should be a string and lowered
	$host = (string) strtolower($host);

	// If host is equal the cookie domain or the server name (if config is set), then we assume it is valid
	if ((isset($board_config['cookie_domain']) && ($host === $board_config['cookie_domain'])) || (isset($board_config['server_name']) && ($host === $board_config['server_name'])))
	{
		return $host;
	}

	// Is the host actually a IP? If so, we use the IP... (IPv4)
	if (long2ip(ip2long($host)) === $host)
	{
		return $host;
	}

	// Now return the hostname (this also removes any port definition). The http:// is prepended to construct a valid URL, hosts never have a scheme assigned
	$host = @parse_url('http://' . $host);
	$host = (!empty($host['host'])) ? $host['host'] : '';

	// Remove any portions not removed by parse_url (#)
	$host = str_replace('#', '', $host);

	// If, by any means, the host is now empty, we will use a "best approach" way to guess one
	if (empty($host))
	{
		if (!empty($board_config['server_name']))
		{
			$host = $board_config['server_name'];
		}
		elseif (!empty($board_config['cookie_domain']))
		{
			$host = (strpos($board_config['cookie_domain'], '.') === 0) ? substr($board_config['cookie_domain'], 1) : $board_config['cookie_domain'];
		}
		else
		{
			// Set to OS hostname or localhost
			$host = (function_exists('php_uname')) ? php_uname('n') : 'localhost';
		}
	}

	// It may be still no valid host, but for sure only a hostname (we may further expand on the cookie domain... if set)
	return $host;
}
*/

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

/**
* Set config value. Creates missing config entry.
*/
function set_config($config_name, $config_value)
{
	global $db, $board_config;

	$sql = "UPDATE " . CONFIG_TABLE . "
		SET config_value = '" . $db->sql_escape($config_value) . "'
		WHERE config_name = '" . $db->sql_escape($config_name) . "'";
	$db->sql_query($sql);

	if (!$db->sql_affectedrows() && !isset($board_config[$config_name]))
	{
		$sql = "INSERT INTO " . CONFIG_TABLE . " (`config_name`, `config_value`)
						VALUES ('" . $db->sql_escape($config_name) . "', '" . $db->sql_escape($config_value) . "')";
		$db->sql_query($sql);
	}

	$board_config[$config_name] = $config_value;
	$db->clear_cache('config_');
}

if (!function_exists('htmlspecialchars_decode'))
{
	/**
	* A wrapper for htmlspecialchars_decode
	* @ignore
	*/
	function htmlspecialchars_decode($string, $quote_style = ENT_NOQUOTES)
	{
		return strtr($string, array_flip(get_html_translation_table(HTML_SPECIALCHARS, $quote_style)));
	}
}

/**
* HTML Special Chars markup cleaning
* @ignore
*/
function htmlspecialchars_clean($string, $quote_style = ENT_NOQUOTES)
{
	return trim(str_replace(array('& ', '<', '%3C', '>', '%3E'), array('&amp; ', '&lt;', '&lt;', '&gt;', '&gt;'), htmlspecialchars_decode($string, $quote_style)));
}

/**
* Add slashes only if needed
* @ignore
*/
function ip_addslashes($string)
{
	return (STRIP ? addslashes($string) : $string);
}

/**
* Strip slashes only if needed
* @ignore
*/
function ip_stripslashes($string)
{
	return (STRIP ? stripslashes($string) : $string);
}

/**
* Escape single quotes for MySQL
* @ignore
*/
function ip_mysql_escape($string)
{
	return str_replace("\'", "''", $string);
}

/**
* Icy Phoenix UTF8 Conditional Decode
* @ignore
*/
function ip_utf8_decode($string)
{
	global $lang;
	$string = ($lang['ENCODING'] == 'utf8') ? $string : utf8_decode($string);
	return $string;
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
				$topic_title = (!empty($orig_word) && count($orig_word) && !$userdata['user_allowswearywords']) ? preg_replace($orig_word, $replacement_word, $post_rows[$i]['topic_title']) : $post_rows[$i]['topic_title'];
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
		//message_die(GENERAL_ERROR, 'User does not exist.');
		return false;
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

/**
* Check if the user is allowed to access a page
*/
function check_page_auth($cms_page_id, $cms_page_name, $return = false)
{
	global $lang, $board_config, $userdata;

	$auth_level_req = $board_config['auth_view_' . $cms_page_name];
	// If access for all or user is admin, then return true
	if (($auth_level_req == AUTH_ALL) || ($userdata['user_level'] == ADMIN))
	{
		return true;
	}

	// Access level required is at least REG and user is not an admin!
	// Remember that Junior Admin has the ADMIN level while not in CMS or ACP
	$not_auth = false;
	// Check if the user is REG or a BOT
	$is_reg = ((($board_config['bots_reg_auth'] == true) && ($userdata['bot_id'] !== false)) || $userdata['session_logged_in']) ? true : false;
	$not_auth = (!$not_auth && ($auth_level_req == AUTH_REG) && !$is_reg) ? true : $not_auth;
	$not_auth = (!$not_auth && ($auth_level_req == AUTH_MOD) && ($userdata['user_level'] != MOD)) ? true : $not_auth;
	$not_auth = (!$not_auth && ($auth_level_req == AUTH_ADMIN)) ? true : $not_auth;
	if ($not_auth)
	{
		if ($return)
		{
			return false;
		}
		else
		{
			if (($userdata['bot_id'] === false) && !$userdata['session_logged_in'])
			{
				$page_array = array();
				$page_array = extract_current_page(IP_ROOT_PATH);
				redirect(append_sid(IP_ROOT_PATH . LOGIN_MG . '?redirect=' . str_replace(('.' . PHP_EXT . '?'), ('.' . PHP_EXT . '&'), $page_array['page']), true));
			}
			else
			{
				message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
			}
		}
	}

	return true;
}

/**
* Return unique id
* @param string $extra additional entropy
*/
function unique_id($extra = 'c')
{
	global $db, $board_config, $dss_seeded;

	$val = $board_config['rand_seed'] . microtime();
	$val = md5($val);
	$board_config['rand_seed'] = md5($board_config['rand_seed'] . $val . $extra);

	if(($dss_seeded !== true) && ($board_config['rand_seed_last_update'] < (time() - rand(1,10))))
	{
		set_config('rand_seed', $board_config['rand_seed']);
		set_config('rand_seed_last_update', time());
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

/*
* Function to clear all unwanted chars in username
*/
function ip_clean_username($username)
{
	$username = ereg_replace("[^A-Za-z0-9&\-_ ]", "", $username);
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
	return jumpbox($action, $match_forum_id);
}

/**
* Checks if a path ($path) is absolute or relative
*
* @param string $path Path to check absoluteness of
* @return boolean
*/
function is_absolute($path)
{
	return ($path[0] == '/' || (DIRECTORY_SEPARATOR == '\\' && preg_match('#^[a-z]:/#i', $path))) ? true : false;
}

/**
* @author Chris Smith <chris@project-minerva.org>
* @copyright 2006 Project Minerva Team
* @param string $path The path which we should attempt to resolve.
* @return mixed
*/
function phpbb_own_realpath($path)
{
	// Now to perform funky shizzle

	// Switch to use UNIX slashes
	$path = str_replace(DIRECTORY_SEPARATOR, '/', $path);
	$path_prefix = '';

	// Determine what sort of path we have
	if (is_absolute($path))
	{
		$absolute = true;

		if ($path[0] == '/')
		{
			// Absolute path, *NIX style
			$path_prefix = '';
		}
		else
		{
			// Absolute path, Windows style
			// Remove the drive letter and colon
			$path_prefix = $path[0] . ':';
			$path = substr($path, 2);
		}
	}
	else
	{
		// Relative Path
		// Prepend the current working directory
		if (function_exists('getcwd'))
		{
			// This is the best method, hopefully it is enabled!
			$path = str_replace(DIRECTORY_SEPARATOR, '/', getcwd()) . '/' . $path;
			$absolute = true;
			if (preg_match('#^[a-z]:#i', $path))
			{
				$path_prefix = $path[0] . ':';
				$path = substr($path, 2);
			}
			else
			{
				$path_prefix = '';
			}
		}
		elseif (isset($_SERVER['SCRIPT_FILENAME']) && !empty($_SERVER['SCRIPT_FILENAME']))
		{
			// Warning: If chdir() has been used this will lie!
			// Warning: This has some problems sometime (CLI can create them easily)
			$path = str_replace(DIRECTORY_SEPARATOR, '/', dirname($_SERVER['SCRIPT_FILENAME'])) . '/' . $path;
			$absolute = true;
			$path_prefix = '';
		}
		else
		{
			// We have no way of getting the absolute path, just run on using relative ones.
			$absolute = false;
			$path_prefix = '.';
		}
	}

	// Remove any repeated slashes
	$path = preg_replace('#/{2,}#', '/', $path);

	// Remove the slashes from the start and end of the path
	$path = trim($path, '/');

	// Break the string into little bits for us to nibble on
	$bits = explode('/', $path);

	// Remove any . in the path, renumber array for the loop below
	$bits = array_values(array_diff($bits, array('.')));

	// Lets get looping, run over and resolve any .. (up directory)
	for ($i = 0, $max = sizeof($bits); $i < $max; $i++)
	{
		// @todo Optimise
		if ($bits[$i] == '..')
		{
			if (isset($bits[$i - 1]))
			{
				if ($bits[$i - 1] != '..')
				{
					// We found a .. and we are able to traverse upwards, lets do it!
					unset($bits[$i]);
					unset($bits[$i - 1]);
					$i -= 2;
					$max -= 2;
					$bits = array_values($bits);
				}
			}
			else if ($absolute) // ie. !isset($bits[$i - 1]) && $absolute
			{
				// We have an absolute path trying to descend above the root of the filesystem
				// ... Error!
				return false;
			}
		}
	}

	// Prepend the path prefix
	array_unshift($bits, $path_prefix);

	$resolved = '';

	$max = sizeof($bits) - 1;

	// Check if we are able to resolve symlinks, Windows cannot.
	$symlink_resolve = (function_exists('readlink')) ? true : false;

	foreach ($bits as $i => $bit)
	{
		if (@is_dir("$resolved/$bit") || ($i == $max && @is_file("$resolved/$bit")))
		{
			// Path Exists
			if ($symlink_resolve && is_link("$resolved/$bit") && ($link = readlink("$resolved/$bit")))
			{
				// Resolved a symlink.
				$resolved = $link . (($i == $max) ? '' : '/');
				continue;
			}
		}
		else
		{
			// Something doesn't exist here!
			// This is correct realpath() behaviour but sadly open_basedir and safe_mode make this problematic
			// return false;
		}
		$resolved .= $bit . (($i == $max) ? '' : '/');
	}

	// @todo If the file exists fine and open_basedir only has one path we should be able to prepend it
	// because we must be inside that basedir, the question is where...
	// @internal The slash in is_dir() gets around an open_basedir restriction
	if (!@file_exists($resolved) || (!is_dir($resolved . '/') && !is_file($resolved)))
	{
		return false;
	}

	// Put the slashes back to the native operating systems slashes
	$resolved = str_replace('/', DIRECTORY_SEPARATOR, $resolved);

	// Check for DIRECTORY_SEPARATOR at the end (and remove it!)
	if (substr($resolved, -1) == DIRECTORY_SEPARATOR)
	{
		return substr($resolved, 0, -1);
	}

	return $resolved; // We got here, in the end!
}

/**
* A wrapper for realpath
* @ignore
*/
function phpbb_realpath($path)
{
	if (!function_exists('realpath'))
	{
		return phpbb_own_realpath($path);
	}
	else
	{
		$realpath = realpath($path);

		// Strangely there are provider not disabling realpath but returning strange values. :o
		// We at least try to cope with them.
		if ($realpath === $path || $realpath === false)
		{
			return phpbb_own_realpath($path);
		}

		// Check for DIRECTORY_SEPARATOR at the end (and remove it!)
		if (substr($realpath, -1) == DIRECTORY_SEPARATOR)
		{
			$realpath = substr($realpath, 0, -1);
		}

		return $realpath;
	}
}

/*
* Creates a full server path
*/
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
* Setup basic lang
*/
function setup_basic_lang()
{
	global $board_config, $lang;

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
}

/**
* Setup the default style
*/
function setup_style($style, $old_default_style, $old_style = false)
{
	global $db, $board_config, $template, $images, $themes_style;

	if (!empty($themes_style[$style]))
	{
		$row = $themes_style[$style];
	}
	else
	{
		$sql = "SELECT * FROM " . THEMES_TABLE . " WHERE themes_id = " . (int) $style . " LIMIT 1";
		if (!($result = $db->sql_query($sql, false, 'themes_')))
		{
			message_die(CRITICAL_ERROR, 'Could not query database for theme info');
		}

		$template_row = array();

		$style_exists = false;
		while ($row = $db->sql_fetchrow($result))
		{
			$template_row = $row;
			$style_exists = true;
		}
		$db->sql_freeresult($result);

		if (!$style_exists)
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
				$sql = "SELECT * FROM " . THEMES_TABLE . " WHERE themes_id = " . (int) $board_config['default_style'] . " LIMIT 1";
				if (!($result = $db->sql_query($sql, false, 'themes_')))
				{
					message_die(CRITICAL_ERROR, 'Could not query database for theme info');
				}

				while ($row = $db->sql_fetchrow($result))
				{
					$template_row = $row;
					$style_exists = true;
				}
				$db->sql_freeresult($result);

				if (!$style_exists)
				{
					$style = $old_default_style;
					//message_die(CRITICAL_ERROR, "Could not get theme data for themes_id [$style]", '', __LINE__, __FILE__);
				}

				$sql = "UPDATE " . USERS_TABLE . "
					SET user_style = " . (int) $board_config['default_style'] . "
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
	unset($row);
	$row = $template_row;
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
	}

	return $row;
}

function check_style_exists($style_id)
{
	global $db, $board_config, $template, $images, $themes_style;

	$style_exists = false;

	if (!empty($themes_style[$style_id]))
	{
		$style_exists = true;
	}
	else
	{
		//$sql = "SELECT themes_id FROM " . THEMES_TABLE . " WHERE themes_id = '" . (int) $style_id . "'";
		$sql = "SELECT themes_id FROM " . THEMES_TABLE . " WHERE themes_id = " . (int) $style_id . " LIMIT 1";
		if (!($result = $db->sql_query($sql, false, 'themes_')))
		{
			message_die(CRITICAL_ERROR, 'Could not query database for theme info');
		}

		while ($row = $db->sql_fetchrow($result))
		{
			$style_exists = true;
		}
		$db->sql_freeresult($result);
	}

	return $style_exists;
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

/*
* A more logic function to output serial dates
*/
/*
function dateserial($year, $month, $day, $hour, $minute, $timezone = 'UTC')
{
	$org_tz = date_default_timezone_get();
	date_default_timezone_set($timezone);
	$date_serial = mktime($hour, $minute, 0, $month, $day, $year);
	date_default_timezone_set($org_tz);
	return $date_serial;
}
*/

// Create date/time from format and timezone
function create_date($format, $gmepoch, $tz)
{
	global $board_config, $lang, $userdata;
	static $translate;

	// We need to force this ==> isset($lang['datetime']) <== otherwise we may have $lang initialized and we don't want that...
	if (empty($translate) && ($board_config['default_lang'] != 'english') && isset($lang['datetime']))
	{
		@reset($lang['datetime']);
		while (list($match, $replace) = @each($lang['datetime']))
		{
			$translate[$match] = $replace;
		}
	}

	$time_mode = $board_config['default_time_mode'];
	$dst_time_lag = $board_config['default_dst_time_lag'];
	if (!empty($userdata) && !$userdata['session_logged_in'])
	{
		$userdata['user_time_mode'] = $board_config['default_time_mode'];
		$userdata['user_dst_time_lag'] = $board_config['default_dst_time_lag'];
	}
	elseif (!empty($userdata))
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

// Birthday - BEGIN
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
function realdate($date_syntax = 'Ymd', $date = 0)
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
	//you may gain speed performance by remove some of the below entry's if they are not needed/used
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
// Birthday - END

/*
* Pagination routine, generates page number sequence
*/
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
		// Define censored word matches
		$sql = "SELECT word, replacement FROM " . WORDS_TABLE . " ORDER BY length(word) DESC";
		if(!($result = $db->sql_query($sql, false, 'word_censor_')))
		{
			message_die(GENERAL_ERROR, 'Could not get censored words from database', '', __LINE__, __FILE__, $sql);
		}

		while ($row = $db->sql_fetchrow($result))
		{
			$ic_word = '';
			$ic_first = 0;
			$ic_chars = preg_split('//', $row['word'], -1, PREG_SPLIT_NO_EMPTY);
			foreach ($ic_chars as $char)
			{
				if (($ic_first == 1) && ($char != "*"))
				{
					$ic_word .= '_';
				}
				$ic_word .= $char; $ic_first = 1;
			}
			$ic_search = array('\*','z','s','a','b','l','i','o','p','_');
			$ic_replace = array('\w*?','(?:z|2)','(?:s|\$)','(?:a|\@)','(?:b|8|3)','(?:l|1|i|\!)','(?:i|1|l|\!)','(?:o|0)','(?:p|\?)','(?:_|\W)*');
			$orig_word[] = '#(?<=^|\W)(' . str_replace($ic_search, $ic_replace, phpbb_preg_quote($ic_word, '#')) . ')(?=\W|$)#i';
			$replacement_word[] = $row['replacement'];
		}
		$db->sql_freeresult($result);

		$global_orig_word = $orig_word;
		$global_replacement_word = $replacement_word;
	}

	return true;
}

/**
* Error and message handler, call with trigger_error if reqd
*/
function msg_handler($errno, $msg_text, $errfile, $errline)
{
	global $board_config, $lang;
	global $msg_title, $msg_long_text;

	// Do not display notices if we suppress them via @
	if (error_reporting() == 0)
	{
		return;
	}

	// Message handler is stripping text. In case we need it, we are possible to define long text...
	if (isset($msg_long_text) && $msg_long_text && !$msg_text)
	{
		$msg_text = $msg_long_text;
	}

	switch ($errno)
	{
		case E_NOTICE:
			// Mighty Gorgon: if you want to report uninitialized variables, comment the "BREAK" below...
		break;
		case E_WARNING:
			// Check the error reporting level and return if the error level does not match

			// If DEBUG is defined to FALSE then return
			if (defined('DEBUG') && !DEBUG)
			{
				return;
			}

			// If DEBUG is defined the default level is E_ALL
			if (($errno & ((defined('DEBUG')) ? E_ALL : error_reporting())) == 0)
			{
				return;
			}

			if (strpos($errfile, 'cache') === false && strpos($errfile, 'template.') === false)
			{
				// flush the content, else we get a white page if output buffering is on
				if ((int) @ini_get('output_buffering') === 1 || strtolower(@ini_get('output_buffering')) === 'on')
				{
					@ob_flush();
				}

				// Another quick fix for those having gzip compression enabled, but do not flush if the coder wants to catch "something". ;)
				if (!empty($board_config['gzip_compress']))
				{
					if (@extension_loaded('zlib') && !headers_sent() && !ob_get_level())
					{
						@ob_flush();
					}
				}

				// remove complete path to installation, with the risk of changing backslashes meant to be there
				$errfile = str_replace(array(phpbb_realpath(IP_ROOT_PATH), '\\'), array('', '/'), $errfile);
				$msg_text = str_replace(array(phpbb_realpath(IP_ROOT_PATH), '\\'), array('', '/'), $msg_text);

				echo '<b>[Icy Phoenix Debug] PHP Notice</b>: in file <b>' . $errfile . '</b> on line <b>' . $errline . '</b>: <b>' . $msg_text . '</b><br />' . "\n";
			}

			return;

		break;

		case E_USER_ERROR:

			$msg_text = (!empty($lang[$msg_text])) ? $lang[$msg_text] : $msg_text;
			$msg_title_default = (!empty($lang['General_Error'])) ? $lang['General_Error'] : 'General Error';
			$msg_title = (!empty($lang[$msg_title])) ? $lang[$msg_title] : $msg_title_default;
			$return_url = (!empty($lang['CLICK_RETURN_HOME'])) ? sprintf($lang['CLICK_RETURN_HOME'], '<a href="' . IP_ROOT_PATH . '">', '</a>') : ('<a href="' . IP_ROOT_PATH . '">Return to home page</a>');
			garbage_collection();
			html_message($msg_title, $msg_text, $return_url);
			exit_handler();

			// On a fatal error (and E_USER_ERROR *is* fatal) we never want other scripts to continue and force an exit here.
			exit;
		break;

		case E_USER_WARNING:
		case E_USER_NOTICE:
			define('IN_ERROR_HANDLER', true);
			message_die($msg_code, $msg_text, $msg_title, $errline, $errfile, '');
	}
}

/**
* Closing the cache object and the database
*/
function garbage_collection()
{
	global $db;

	// Close our DB connection.
	if (!empty($db))
	{
		$db->sql_close();
	}
}

/**
* Handler for exit calls in phpBB.
*
* Note: This function is called after the template has been outputted.
*/
function exit_handler()
{
	global $board_config;

	// As a pre-caution... some setups display a blank page if the flush() is not there.
	(empty($board_config['gzip_compress'])) ? @flush() : @ob_flush();

	exit;
}

/**
* HTML Message
*/
function html_message($msg_title, $msg_text, $return_url)
{
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
	echo '<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">';
	echo '<head>';
	echo '<meta http-equiv="content-type" content="text/html; charset=utf-8" />';
	echo '<title>' . $msg_title . '</title>';
	echo '<style type="text/css">';
	echo "\n" . '/* <![CDATA[ */' . "\n";
	echo '* { margin: 0; padding: 0; } html { font-size: 100%; height: 100%; margin-bottom: 1px; background-color: #e8eef8; } body { font-family: "Trebuchet MS", "Lucida Grande", Verdana, Helvetica, Arial, sans-serif; color: #225599; background: #e8eef8; font-size: 62.5%; margin: 0; } ';
	echo 'a:link, a:active, a:visited { color: #336699; text-decoration: none; } a:hover { color: #dd2222; text-decoration: underline; } ';
	echo '#wrap { padding: 0 20px 15px 20px; min-width: 615px; } #page-header { text-align: right; height: 40px; } #page-footer { clear: both; font-size: 1em; text-align: center; } ';
	echo '.panel { margin: 4px 0; background-color: #ffffff; border: solid 1px #dde8ee; } ';
	echo '#errorpage #page-header a { font-weight: bold; line-height: 6em; } #errorpage #content { padding: 10px; } #errorpage #content h1 { line-height: 1.2em; margin-bottom: 0; color: #dd2222; } ';
	echo '#errorpage #content div { margin-top: 20px; margin-bottom: 5px; border-bottom: 1px solid #dddddd; padding-bottom: 5px; color: #333333; font: bold 1.2em "Trebuchet MS", "Lucida Grande", Arial, Helvetica, sans-serif; text-decoration: none; line-height: 120%; text-align: left; } ';
	echo "\n" . '/* ]]> */' . "\n";
	echo '</style>';
	echo '</head>';
	echo '<body id="errorpage">';
	echo '<div id="wrap">';
	echo '	<div id="page-header">';
	echo '		' . $return_url;
	echo '	</div>';
	echo '	<div class="panel">';
	echo '		<div id="content">';
	echo '			<h1>' . $msg_title . '</h1>';
	echo '			<div>' . $msg_text . '</div>';
	echo '		</div>';
	echo '	</div>';
	echo '	<div id="page-footer">';
	echo '		Powered by <a href="http://www.icyphoenix.com/" target="_blank">Icy Phoenix</a> based on <a href="http://www.phpbb.com/" target="_blank">phpBB</a>';
	echo '	</div>';
	echo '</div>';
	echo '</body>';
	echo '</html>';
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
	global $cms_global_blocks, $cms_page_id, $cms_config_vars;
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
		setup_basic_lang();

		if (empty($template) || empty($theme))
		{
			$theme = setup_style($board_config['default_style'], $old_default_style);
		}

		$template->assign_var('HAS_DIED', true);
		define('TPL_HAS_DIED', true);

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

			// We force english to make sure we have at least the default language
			$board_config['default_lang'] = 'english';
			setup_basic_lang();

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
		// If we have already defined the var in header, let's output it in footer as well
		if(defined('TPL_HAS_DIED'))
		{
			$template->assign_var('HAS_DIED', true);
		}

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

	garbage_collection();
	exit_handler();

	exit;
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

/*
* MG BOTS Parsing Function
*/
function bots_parse($ip_address, $bot_color = '#888888', $browser = false, $check_inactive = false, $return_id = false)
{
	global $db, $lang;
	/*
	// Testing!!!
	$browser = 'msnbot/ ciao';
	$bot_name = 'MG';
	return $bot_name;
	*/

	$bot_name = false;
	//return $bot_name;
	$bot_color = empty($bot_color) ? '#888888' : $bot_color;

	if ($browser != false)
	{
		if ((strpos($browser, 'Firefox') !== false) || (strpos($browser, 'MSIE') !== false) || (strpos($browser, 'Opera') !== false))
		{
			$bot_name = false;
			return $bot_name;
		}
	}

	$active_bots = array();
	$sql = "SELECT bot_id, bot_name, bot_active, bot_agent, bot_ip, bot_color
		FROM " . BOTS_TABLE . "
		ORDER BY bot_id";
	if(!$result = $db->sql_query($sql, false, 'bots_list_'))
	{
		message_die(GENERAL_ERROR, 'Could not query bots table', $lang['Error'], __LINE__, __FILE__, $sql);
	}

	while($row = $db->sql_fetchrow($result))
	{
		$active_bots[] = $row;
	}
	$db->sql_freeresult($result);

	for ($i = 0; $i < count($active_bots); $i++)
	{
		if (!empty($active_bots[$i]['bot_agent']) && preg_match('#' . str_replace('\*', '.*?', preg_quote($active_bots[$i]['bot_agent'], '#')) . '#i', $browser))
		{
			$bot_name = (!empty($active_bots[$i]['bot_color']) ? $active_bots[$i]['bot_color'] : ('<b style="color:' . $bot_color . '">' . $active_bots[$i]['bot_name'] . '</b>'));
			if (($check_inactive == true) && ($active_bots[$i]['bot_active'] == 0))
			{
				message_die(GENERAL_ERROR, $lang['Not_Authorised']);
			}
			if ($return_id == true)
			{
				$bot_name = $active_bots[$i]['bot_id'];
			}
			return $bot_name;
		}

		if (!empty($active_bots[$i]['bot_ip']))
		{
			foreach (explode(',', $active_bots[$i]['bot_ip']) as $bot_ip)
			{
				if (strpos(decode_ip($ip_address), trim($bot_ip)) === 0)
				{
					$bot_name = (!empty($active_bots[$i]['bot_color']) ? $active_bots[$i]['bot_color'] : ('<b style="color:' . $bot_color . '">' . $active_bots[$i]['bot_name'] . '</b>'));
					if (($check_inactive == true) && ($active_bots[$i]['bot_active'] == 0))
					{
						message_die(GENERAL_ERROR, $lang['Not_Authorised']);
					}
					if ($return_id == true)
					{
						$bot_name = $active_bots[$i]['bot_id'];
					}
					return $bot_name;
				}
			}
		}
	}

	return false;
}

/*
* Update bots table
*/
function bots_table_update($bot_id)
{
	global $db, $lang;
	$sql = "UPDATE " . BOTS_TABLE . "
					SET bot_visit_counter = (bot_visit_counter + 1),
						bot_last_visit = '" . time() . "'
					WHERE bot_id = '" . $bot_id . "'";
	if(!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not update bots table', $lang['Error'], __LINE__, __FILE__, $sql);
	}
	return true;
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

/**
* @return valid color or false
* @param color as string
* @desc Checks for a valid color string in #rrggbb, rrggbb, #rgb, rgb, rgb(rrr,ggg,bbb) format or color name defined in constant RGB_COLORS_LIST.
*/
function check_valid_color($color)
{
	$color = strtolower($color);
	// hex colors
	if (preg_match('/#[0-9,a-f]{6}/', $color) || preg_match('/#[0-9,a-f]{3}/', $color))
	{
		return $color;
	}
	// hex colors
	if (preg_match('/[0-9,a-f]{6}/', $color) || preg_match('/[0-9,a-f]{3}/', $color))
	{
		return '#' . $color;
	}
	// rgb color
	if(substr($color, 0, 4) === 'rgb(' && preg_match('/^rgb\([0-9]+,[0-9]+,[0-9]+\)$/', $color))
	{
		$colors = explode(',', substr($color, 4, strlen($color) - 5));
		for($i = 0; $i < 3; $i++)
		{
			if($colors[$i] > 255)
			{
				return false;
			}
		}
		return sprintf('#%02X%02X%02X', $colors[0], $colors[1], $colors[2]);
	}
	// text color in array
	if (in_array($color, explode(',', RGB_COLORS_LIST)))
	{
		return $color;
	}
	// text color
	if(preg_match('/^[a-z]+$/', $color))
	{
		return $color;
	}
	return false;
}

/**
 * Create the sql needed to query the color... this is used also to precisely locate the cache file!
*/
function user_color_sql($user_id)
{
	$sql = "SELECT u.username, u.user_active, u.user_color, u.user_color_group
		FROM " . USERS_TABLE . " u
		WHERE u.user_id = '" . $user_id . "'
			LIMIT 1";
	return $sql;
}

/**
 * Clear user color cache.
 *
 * @param => user_id
 * @return => true on success
*/
function clear_user_color_cache($user_id)
{
	$dir = ((@file_exists(USERS_CACHE_FOLDER)) ? USERS_CACHE_FOLDER : @phpbb_realpath(USERS_CACHE_FOLDER));
	@unlink($dir . 'sql_' . POST_USERS_URL . '_' . md5(user_color_sql($user_id)) . '.' . PHP_EXT);
	return true;
}

/**
 * Create a profile link for the user with his own color
*/
function colorize_username($user_id, $username = '', $user_color = '', $user_active = true, $no_profile = false, $get_only_color_style = false, $from_db = false, $force_cache = false)
{
	global $db, $board_config, $lang;

	$user_id = empty($user_id) ? ANONYMOUS : $user_id;
	$is_guest = ($user_id == ANONYMOUS) ? true : false;

	if ((!$is_guest && $from_db) || (!$is_guest && empty($username) && empty($user_color)))
	{
		// Get the user info and see if they are assigned a color_group
		$sql = user_color_sql($user_id);
		$cache_cleared = (CACHE_COLORIZE && defined('IN_ADMIN')) ? clear_user_color_cache($user_id) : false;
		$result = ((CACHE_COLORIZE || $force_cache) && !defined('IN_ADMIN')) ? $db->sql_query($sql, false, POST_USERS_URL . '_', USERS_CACHE_FOLDER) : $db->sql_query($sql);
		$sql_row = array();
		$row = array();
		while ($sql_row = $db->sql_fetchrow($result))
		{
			$row = $sql_row;
		}
		$db->sql_freeresult($result);
		$username = $row['username'];
		$user_color = $row['user_color'];
		$user_active = $row['user_active'];
	}

	$username = (($user_id == ANONYMOUS) || empty($username)) ? $lang['Guest'] : htmlspecialchars($username);
	$user_link_style = '';
	$user_link_begin = '<a href="' . append_sid(IP_ROOT_PATH . PROFILE_MG . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $user_id) . '"';
	$user_link_end = '>' . $username . '</a>';

	if (!$user_active || $is_guest)
	{
		$user_link = $user_link_begin . $user_link_style . $user_link_end;
		$user_link = ($no_profile || $is_guest) ? $username : $user_link;
		$user_link = ($get_only_color_style) ? '' : $user_link;
	}
	else
	{
		$user_color = check_valid_color($user_color);
		$user_color = ($user_color != false) ? $user_color : $board_config['active_users_color'];
		$user_link_style = ' style="font-weight: bold; text-decoration: none; color: ' . $user_color . ';"';

		if ($no_profile)
		{
			$user_link = '<span' . $user_link_style . '>' . $username . '</span>';
		}
		else
		{
			$user_link = $user_link_begin . $user_link_style . $user_link_end;
		}

		$user_link = ($get_only_color_style) ? $user_link_style : $user_link;
	}

	return $user_link;
}

function get_default_avatar($user_id, $path_prefix = '')
{
	global $board_config;

	$avatar_img = '&nbsp;';
	if ($board_config['default_avatar_set'] != 3)
	{
		if (($board_config['default_avatar_set'] == 0) && ($user_id == ANONYMOUS) && ($board_config['default_avatar_guests_url'] != ''))
		{
			$avatar_img = $board_config['default_avatar_guests_url'];
		}
		elseif (($board_config['default_avatar_set'] == 1) && ($user_id != ANONYMOUS) && ($board_config['default_avatar_users_url'] != ''))
		{
			$avatar_img = $board_config['default_avatar_users_url'];
		}
		elseif ($board_config['default_avatar_set'] == 2)
		{
			if (($user_id == ANONYMOUS) && ($board_config['default_avatar_guests_url'] != ''))
			{
				$avatar_img = $board_config['default_avatar_guests_url'];
			}
			elseif (($user_id != ANONYMOUS) && ($board_config['default_avatar_users_url'] != ''))
			{
				$avatar_img = $board_config['default_avatar_users_url'];
			}
		}
	}

	$avatar_img = ($avatar_img == '&nbsp;') ? '&nbsp;' : '<img src="' . $path_prefix . $avatar_img . '" alt="avatar" />';

	return $avatar_img;
}

function user_get_avatar($user_id, $user_level, $user_avatar, $user_avatar_type, $user_allow_avatar, $path_prefix = '')
{
	global $board_config;
	$user_avatar_link = '';
	if ($user_avatar_type && ($user_id != ANONYMOUS) && $user_allow_avatar)
	{
		switch($user_avatar_type)
		{
			case USER_AVATAR_UPLOAD:
				$user_avatar_link = ($board_config['allow_avatar_upload']) ? '<img src="' . $path_prefix . $board_config['avatar_path'] . '/' . $user_avatar . '" alt="avatar" style="margin-bottom: 3px;" />' : '';
				break;
			case USER_AVATAR_REMOTE:
				$user_avatar_link = resize_avatar($user_id, $user_level, $user_avatar);
				break;
			case USER_AVATAR_GALLERY:
				$user_avatar_link = ($board_config['allow_avatar_local']) ? '<img src="' . $path_prefix . $board_config['avatar_gallery_path'] . '/' . $user_avatar . '" alt="avatar" style="margin-bottom: 3px;" />' : '';
				break;
			case USER_GRAVATAR:
				$user_avatar_link = ($board_config['enable_gravatars']) ? '<img src="' . get_gravatar($user_avatar) . '" alt="avatar" style="margin-bottom: 3px;" />' : '';
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

function resize_avatar($user_id, $user_level, $avatar_url)
{
	global $board_config;

	if ($user_level == ADMIN)
	{
		return '<img src="' . $avatar_url . '" alt="avatar" style="margin-bottom: 3px;" />';
	}

	// Set this to false if you want to force height as well
	$force_width_only = true;

	$avatar_width = $board_config['avatar_max_width'];
	$avatar_height = $board_config['avatar_max_height'];

	/*
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
				$avatar_height = $avatar_width * ($pic_height / $pic_width);
			}
			else
			{
				$avatar_width = $avatar_height * ($pic_width / $pic_height);
			}
		}
	}
	*/

	$avatar_img_dim = ($force_width_only) ? (' width="' . $avatar_width . '"') : (' width="' . $avatar_width . '" height="' . $avatar_height . '"');
	$avatar_img = ($board_config['allow_avatar_remote']) ? '<img src="' . $avatar_url . '"' . $avatar_img_dim . ' alt="avatar" style="margin-bottom: 3px;" />' : '';

	return $avatar_img;
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

function build_im_link($im_type, $im_id, $im_lang = '', $im_img = false, $im_url = false)
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
	$im_link = ($im_url !== false) ? $im_ref : '<a href="' . $im_ref . '">' . $link_content . '</a>';
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

/*
* Get AD
*/
function get_ad($ad_position)
{
	global $db, $board_config, $userdata;

	$ad_text = '';
	if (!$board_config['ads_' . $ad_position])
	{
		return $ad_text;
	}

	$user_auth = AUTH_ALL;
	$user_level = ($userdata['user_id'] == ANONYMOUS) ? ANONYMOUS : $userdata['user_level'];
	switch ($user_level)
	{
		case ADMIN:
			$user_auth = AUTH_ADMIN;
		break;
		case MOD:
			$user_auth = AUTH_MOD;
		break;
		case USER:
			$user_auth = AUTH_REG;
		break;
	}

	$sql = "SELECT *
		FROM " . ADS_TABLE . "
		WHERE ad_position = '" . $ad_position . "'
			AND ad_active = 1
			AND ad_auth >= " . $user_auth . "
		ORDER BY ad_id";
	if(!$result = $db->sql_query($sql, false, 'ads_'))
	{
		message_die(GENERAL_ERROR, 'Could not query ads table', $lang['Error'], __LINE__, __FILE__, $sql);
	}

	$active_ads = array();
	while($row = $db->sql_fetchrow($result))
	{
		$active_ads[] = $row;
	}
	$db->sql_freeresult($result);

	$total_ads = count($active_ads);
	if ($total_ads > 0)
	{
		$selected_ad = rand(0, $total_ads - 1);
		$ad_text = ((STRIP) ? stripslashes($active_ads[$selected_ad]['ad_text']) : $active_ads[$selected_ad]['ad_text']);
		if ($active_ads[$selected_ad]['ad_format'])
		{
			global $bbcode;
			@include_once(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);
			$bbcode->allow_html = false;
			$bbcode->allow_bbcode = true;
			$bbcode->allow_smilies = true;
			$ad_text = $bbcode->parse($ad_text);
		}
	}

	return $ad_text;
}

function empty_cache_folders($cache_folder = '', $files_per_step = 0)
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

	$dirs_array = array(MAIN_CACHE_FOLDER, FORUMS_CACHE_FOLDER, POSTS_CACHE_FOLDER, SQL_CACHE_FOLDER, TOPICS_CACHE_FOLDER, USERS_CACHE_FOLDER);
	$dirs_array = ((empty($cache_folder) || !in_array($cache_folder, $dirs_array)) ? $dirs_array : array($cache_folder));
	$files_counter = 0;
	for ($i = 0; $i < count($dirs_array); $i++)
	{
		$dir = $dirs_array[$i];
		$dir = ((is_dir($dir)) ? $dir : @phpbb_realpath($dir));
		$res = opendir($dir);
		while(($file = readdir($res)) !== false)
		{
			$file_full_path = $dir . $file;
			if (!in_array($file, $skip_files) && !is_dir($file_full_path))
			{
				@chmod($file_full_path, 0777);
				$res2 = @unlink($file_full_path);
				$files_counter++;
			}
			if (($files_per_step > 0) && ($files_counter >= $files_per_step))
			{
				closedir($res);
				return $files_per_step;
			}
		}
		closedir($res);
	}
	return true;
}

function empty_images_cache_folders($files_per_step = 0)
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
	$files_counter = 0;
	for ($i = 0; $i < count($dirs_array); $i++)
	{
		$dir = $dirs_array[$i];
		$dir = ((is_dir($dir)) ? $dir : @phpbb_realpath($dir));
		$res = opendir($dir);
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
						if (!in_array($subfile, $skip_files) && !is_dir($subfile_full_path))
						{
							if(preg_match('/(\.gif$|\.png$|\.jpg|\.jpeg)$/is', $subfile))
							{
								@chmod($subfile_full_path, 0777);
								$res2 = @unlink($subfile_full_path);
								$files_counter++;
							}
							if (($files_per_step > 0) && ($files_counter >= $files_per_step))
							{
								closedir($subres);
								return $files_per_step;
							}
						}
					}
					closedir($subres);
				}
				elseif(preg_match('/(\.gif$|\.png$|\.jpg|\.jpeg)$/is', $file))
				{
					@chmod($file_full_path, 0777);
					$res2 = @unlink($file_full_path);
					$files_counter++;
				}
			}
			if (($files_per_step > 0) && ($files_counter >= $files_per_step))
			{
				closedir($res);
				return $files_per_step;
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