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
		// Mighty Gorgon: I need to add this condition, because non UTF-8 lang will mess-up strings!!!
		global $lang;
		if (strtoupper($lang['ENCODING']) == 'UTF-8')
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
			if (($type == 'array') && is_array($v))
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
				if (($type == 'array') || is_array($v))
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
function set_config($config_name, $config_value, $clear_cache = true, $return = false)
{
	global $db, $cache, $config;

	$sql = "UPDATE " . CONFIG_TABLE . "
		SET config_value = '" . $db->sql_escape($config_value) . "'
		WHERE config_name = '" . $db->sql_escape($config_name) . "'";
	$db->sql_return_on_error($return);
	$db->sql_query($sql);
	$db->sql_return_on_error(false);

	if (!$db->sql_affectedrows() && !isset($config[$config_name]))
	{
		$sql = "INSERT INTO " . CONFIG_TABLE . " (`config_name`, `config_value`)
						VALUES ('" . $db->sql_escape($config_name) . "', '" . $db->sql_escape($config_value) . "')";
		$db->sql_return_on_error($return);
		$db->sql_query($sql);
		$db->sql_return_on_error(false);
	}

	$config[$config_name] = $config_value;

	if ($clear_cache)
	{
		$cache->destroy('config');
		//$db->clear_cache('config_');
	}
}

/**
* Get config values
*/
function get_config($from_cache = true)
{
	global $db;

	$config = array();
	$from_cache = ($from_cache && (CACHE_CFG == true) && !defined('IN_ADMIN') && !defined('IN_CMS')) ? true : false;
	$sql = "SELECT * FROM " . CONFIG_TABLE;
	$result = $from_cache ? $db->sql_query($sql, 0, 'config_') : $db->sql_query($sql);
	while ($row = $db->sql_fetchrow($result))
	{
		$config[$row['config_name']] = stripslashes($row['config_value']);
	}
	$db->sql_freeresult($result);

	return $config;
}

/**
* Get layouts config values
*/
function get_layouts_config($from_cache = true)
{
	global $db;

	$cms_config_layouts = array();
	$from_cache = $from_cache ? true : false;
	$sql = "SELECT lsid, page_id, filename, global_blocks, page_nav, view FROM " . CMS_LAYOUT_SPECIAL_TABLE . " ORDER BY page_id";
	$result = $from_cache ? $db->sql_query($sql, 0, 'cms_config_', CMS_CACHE_FOLDER) : $db->sql_query($sql);
	while ($row = $db->sql_fetchrow($result))
	{
		$cms_config_layouts[$row['page_id']] = $row;
	}
	$db->sql_freeresult($result);

	return $cms_config_layouts;
}

/**
* Get CMS config values
*/
function get_cms_config($from_cache = true)
{
	global $db;

	$cms_config_vars = array();
	$from_cache = $from_cache ? true : false;
	$sql = "SELECT bid, config_name, config_value FROM " . CMS_CONFIG_TABLE;
	$result = $from_cache ? $db->sql_query($sql, 0, 'cms_config_', CMS_CACHE_FOLDER) : $db->sql_query($sql);
	while ($row = $db->sql_fetchrow($result))
	{
		if ($row['bid'] > 0)
		{
			$cms_config_vars[$row['config_name']][$row['bid']] = $row['config_value'];
		}
		else
		{
			$cms_config_vars[$row['config_name']] = $row['config_value'];
		}
	}
	$db->sql_freeresult($result);

	return $cms_config_vars;
}

if (!function_exists('htmlspecialchars_decode'))
{
	/**
	* A wrapper for htmlspecialchars_decode
	*/
	function htmlspecialchars_decode($string, $quote_style = ENT_NOQUOTES)
	{
		return strtr($string, array_flip(get_html_translation_table(HTML_SPECIALCHARS, $quote_style)));
	}
}

/**
* html_entity_decode replacement (from php manual)
*/
if (!function_exists('html_entity_decode'))
{
	function html_entity_decode($given_html, $quote_style = ENT_QUOTES)
	{
		$trans_table = array_flip(get_html_translation_table(HTML_SPECIALCHARS, $quote_style));
		$trans_table['&#39;'] = "'";
		return (strtr($given_html, $trans_table));
	}
}

/**
* HTML Special Chars markup cleaning
*/
function htmlspecialchars_clean($string, $quote_style = ENT_NOQUOTES)
{
	return trim(str_replace(array('& ', '<', '%3C', '>', '%3E'), array('&amp; ', '&lt;', '&lt;', '&gt;', '&gt;'), htmlspecialchars_decode($string, $quote_style)));
}

/**
* Add slashes only if needed
*/
function ip_addslashes($string)
{
	return (STRIP ? addslashes($string) : $string);
}

/**
* Strip slashes only if needed
*/
function ip_stripslashes($string)
{
	return (STRIP ? stripslashes($string) : $string);
}

/**
* Escape single quotes for MySQL
*/
function ip_mysql_escape($string)
{
	return str_replace("\'", "''", $string);
}

/**
* Icy Phoenix UTF8 Conditional Decode
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
	global $cache, $db, $template, $theme, $images, $lang, $config, $nav_separator;
	global $tree;

	// Get all the mods settings
	setup_mods();

	/*
	if (isset($_GET[LANG_URL]) || isset($_POST[LANG_URL]))
	{
		$config['default_lang'] = urldecode((isset($_GET[LANG_URL])) ? $_GET[LANG_URL] : $_POST[LANG_URL]);
		setcookie($config['cookie_name'] . '_lang', $config['default_lang'] , (time() + 86400), $config['cookie_path'], $config['cookie_domain'], $config['cookie_secure']);
	}
	*/

	$default_lang = phpbb_ltrim(basename(phpbb_rtrim($config['default_lang'])), "'");
	if ($userdata['user_id'] != ANONYMOUS)
	{
		$default_lang = !empty($userdata['user_lang']) ? phpbb_ltrim(basename(phpbb_rtrim($userdata['user_lang'])), "'") : $default_lang;

		$config['board_timezone'] = !empty($userdata['user_timezone']) ? $userdata['user_timezone'] : $config['board_timezone'];
		$config['default_dateformat'] = !empty($userdata['user_dateformat']) ? $userdata['user_dateformat'] : $config['default_dateformat'];

		$config['topics_per_page'] = !empty($userdata['user_topics_per_page']) ? $userdata['user_topics_per_page'] : $config['topics_per_page'];
		$config['posts_per_page'] = !empty($userdata['user_posts_per_page']) ? $userdata['user_posts_per_page'] : $config['posts_per_page'];
		$config['hot_threshold'] = !empty($userdata['user_hot_threshold']) ? $userdata['user_hot_threshold'] : $config['hot_threshold'];
	}

	if (!file_exists(@phpbb_realpath(IP_ROOT_PATH . 'language/lang_' . $default_lang . '/lang_main.' . PHP_EXT)))
	{
		if ($userdata['user_id'] != ANONYMOUS)
		{
			// For logged in users, try the board default language next
			$default_lang = phpbb_ltrim(basename(phpbb_rtrim($config['default_lang'])), "'");
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
		$result = $db->sql_query($sql);
		$userdata['user_lang'] = $default_lang;
	}
	elseif (($userdata['user_id'] === ANONYMOUS) && ($config['default_lang'] !== $default_lang))
	{
		$sql = 'UPDATE ' . CONFIG_TABLE . "
			SET config_value = '" . $default_lang . "'
			WHERE config_name = 'default_lang'";
		$result = $db->sql_query($sql);
	}
	$config['default_lang'] = $default_lang;

	setup_basic_lang();

	$nav_separator = empty($nav_separator) ? (empty($lang['Nav_Separator']) ? '&nbsp;&raquo;&nbsp;' : $lang['Nav_Separator']) : $nav_separator;

	if (empty($tree['auth']))
	{
		get_user_tree($userdata);
	}

	// MG Logs - BEGIN
	if ($config['mg_log_actions'] || $config['db_log_actions'])
	{
		include(IP_ROOT_PATH . 'includes/log_http_cmd.' . PHP_EXT);
	}
	// MG Logs - END

	// Set up style
	$current_default_style = $config['default_style'];
	if (!$config['override_user_style'])
	{
		if (isset($_GET[STYLE_URL]) || isset($_POST[STYLE_URL]))
		{
			$current_style = $config['default_style'];
			$config['default_style'] = urldecode((isset($_GET[STYLE_URL])) ? intval($_GET[STYLE_URL]) : intval($_POST[STYLE_URL]));
			$config['default_style'] = ($config['default_style'] == 0) ? $current_style : $config['default_style'];
			$style = $config['default_style'];
			if ($theme = setup_style($style, $current_default_style, $current_style))
			{
				if ($userdata['user_id'] != ANONYMOUS)
				{
					// user logged in --> save new style ID in user profile
					$sql = "UPDATE " . USERS_TABLE . "
						SET user_style = " . $theme['themes_id'] . "
						WHERE user_id = " . $userdata['user_id'];
					$db->sql_query($sql);
					$userdata['user_style'] = $theme['themes_id'];
				}
				/*
				else
				{
					$config['default_style'] = $theme['themes_id'];
					setcookie($config['cookie_name'] . '_style', $config['default_style'] , (time() + 86400), $config['cookie_path'], $config['cookie_domain'], $config['cookie_secure']);
				}
				*/
				return;
			}
		}
		if (($userdata['user_id'] != ANONYMOUS) && ($userdata['user_style'] > 0))
		{
			if ($theme = setup_style($userdata['user_style'], $current_default_style))
			{
				return;
			}
		}
	}

	$theme = setup_style($config['default_style'], $current_default_style);

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
	$result = $db->sql_query($sql);

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

/*
* Get founder id
*/
function get_founder_id($clear_cache = false)
{
	global $db, $config;
	if ($clear_cache)
	{
		$db->clear_cache('founder_id_');
	}
	$founder_id = (intval($config['main_admin_id']) >= 2) ? $config['main_admin_id'] : 2;
	if ($founder_id != 2)
	{
		$sql = "SELECT user_id
			FROM " . USERS_TABLE . "
			WHERE user_id = '" . $founder_id . "'
			LIMIT 1";
		$result = $db->sql_query($sql, 0, 'founder_id_');
		$founder_id = 2;
		while ($row = $db->sql_fetchrow($result))
		{
			$founder_id = $row['user_id'];
		}
		$db->sql_freeresult($result);
	}
	return $founder_id;
}

/*
* Founder protection
*/
function founder_protect($founder_id)
{
	global $db;

	// Activate Main Admin Account
	$sql = "UPDATE " . USERS_TABLE . "
		SET user_active = 1
	WHERE user_id = " . $founder_id;
	$result = $db->sql_query($sql);

	// Delete Main Admin Ban
	$sql = "DELETE FROM " . BANLIST_TABLE . "
		WHERE ban_userid = " . $founder_id;
	$result = $db->sql_query($sql);

	$db->clear_cache('ban_', USERS_CACHE_FOLDER);

	return true;
}

/*
* Check auth level
* Returns true in case the user has the requested level
*/
function check_auth_level($level_required)
{
	global $userdata, $config;

	if ($level_required == AUTH_ALL)
	{
		return true;
	}

	if ($userdata['user_level'] == ADMIN)
	{
		if ($level_required == AUTH_ADMIN)
		{
			return true;
		}

		if ($level_required == AUTH_FOUNDER)
		{
			$founder_id = (defined('FOUNDER_ID') ? FOUNDER_ID : get_founder_id());
			return ($userdata['user_id'] == $founder_id) ? true : false;
		}
		elseif ($level_required == AUTH_MAIN_ADMIN)
		{
			if (defined('MAIN_ADMINS_ID'))
			{
				$allowed_admins = explode(',', MAIN_ADMINS_ID);
				return (in_array($userdata['user_id'], $allowed_admins)) ? true : false;
			}
		}
	}

	// Force to AUTH_ADMIN since we already checked all cases for founder or main admins
	if (($level_required == AUTH_FOUNDER) || ($level_required == AUTH_MAIN_ADMIN))
	{
		$level_required = AUTH_ADMIN;
	}

	// Access level required is at least REG and user is not an admin!
	// Remember that Junior Admin has the ADMIN level while not in CMS or ACP
	$not_auth = false;
	// Check if the user is REG or a BOT
	$is_reg = (($config['bots_reg_auth'] && $userdata['is_bot']) || $userdata['session_logged_in']) ? true : false;
	$not_auth = (!$not_auth && ($level_required == AUTH_REG) && !$is_reg) ? true : $not_auth;
	$not_auth = (!$not_auth && ($level_required == AUTH_MOD) && ($userdata['user_level'] != MOD) && ($userdata['user_level'] != ADMIN)) ? true : $not_auth;
	$not_auth = (!$not_auth && ($level_required == AUTH_ADMIN)) ? true : $not_auth;
	if ($not_auth)
	{
		return false;
	}

	return true;
}

/**
* Check if the user is allowed to access a page
*/
function check_page_auth($cms_page_id, $cms_auth_level, $return = false)
{
	global $userdata, $lang;

	$is_auth = check_auth_level($cms_auth_level);

	if (!$is_auth)
	{
		if ($return)
		{
			return false;
		}
		else
		{
			if (!$userdata['is_bot'] && !$userdata['session_logged_in'])
			{
				$page_array = array();
				$page_array = extract_current_page(IP_ROOT_PATH);
				redirect(append_sid(IP_ROOT_PATH . CMS_PAGE_LOGIN . '?redirect=' . str_replace(('.' . PHP_EXT . '?'), ('.' . PHP_EXT . '&'), $page_array['page']), true));
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
	global $db, $config, $dss_seeded;

	$val = $config['rand_seed'] . microtime();
	$val = md5($val);
	$config['rand_seed'] = md5($config['rand_seed'] . $val . $extra);

	if(($dss_seeded !== true) && ($config['rand_seed_last_update'] < (time() - rand(1,10))))
	{
		set_config('rand_seed', $config['rand_seed']);
		set_config('rand_seed_last_update', time());
		$dss_seeded = true;
	}

	return substr($val, 4, 16);
}

// added at phpBB 2.0.11 to properly format the username
function phpbb_clean_username($username)
{
	$username = substr(htmlspecialchars(str_replace("\'", "'", trim($username))), 0, 36);
	$username = phpbb_rtrim($username, "\\");
	$username = str_replace("'", "\'", $username);

	return $username;
}

/*
* Function to clear all unwanted chars in username
*/
function ip_clean_username($username)
{
	$username = preg_replace('/[^A-Za-z0-9&\-_]*/', '', trim($username));
	return $username;
}

/*
* Clean string
*/
function ip_clean_string($text, $charset = false, $extra_chars = false)
{
	$charset = empty($charset) ? 'utf-8' : $charset;

	// Remove all HTML tags and convert to lowercase
	$text = strtolower(strip_tags($text));

	// Convert &
	$text = str_replace(array('&amp;', '&nbsp;', '&quot;'), array('&', ' ', ''), $text);
	// Decode all HTML entities
	$text = html_entity_decode($text, ENT_COMPAT, $charset);

	// Some common chars replacements... are we sure we want to replace "&"???
	$find = array('&', '@', '©', '®', '€', '$', '£');
	$repl = array('and', 'at', 'copyright', 'rights', 'euro', 'dollar', 'pound');
	$text = str_replace($find, $repl, $text);

	// Attempt to convert all HTML numeric entities.
	if (preg_match('@\&\#\d+;@s', $text))
	{
		$text = preg_replace('~&#([0-9]+);~e', 'chr("\\1")', $text);
	}

	// Convert back all HTML entities into their aliases
	$text = htmlentities($text, ENT_COMPAT, $charset);

	// Replace some known HTML entities
	$find = array(
		'&#268;', '&#269;', // c
		'&#356;', '&#357;', // t
		'&#270;', '&#271;', // d
		'&#317;', '&#318;', // L, l
		'&#327;', '&#328;', // N, n
		'&#381;', '&#382;', 'Ž', 'ž', // z
		'œ', '&#338;', '&#339;', // OE, oe
		'&#198;', '&#230;', // AE, ae
		'&#223;', '&#946;', // ß
		'š', 'Š', // 'š','Š'
		'&#273;', '&#272;', // ?', '?', // 'dj','dj'
		'`', '‘', '’',
	);

	$repl = array(
		'c', 'c',
		't', 't',
		'd', 'd',
		'l', 'l',
		'n', 'n',
		'z', 'z', 'z', 'z',
		'oe', 'oe', 'oe',
		'ae', 'ae',
		'sz', 'sz',
		's', 's',
		'dj', 'dj',
		'-', '-', '-',
	);

	$text = str_replace($find, $repl, $text);

	// Convert localized special chars
	$text = preg_replace('/&([a-z][ez]?)(?:acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig);/','$1', $text);

	// Convert all remaining special chars
	$text = preg_replace('/&([a-z]+);/', '$1', $text);

	// If still some unrecognized HTML entities are there... kill them!!!
	$text = preg_replace('@\&\#\d+;@s', '', $text);

	// Replace all illegal chars with '-'
	if ($extra_chars)
	{
		// if $extra_chars is true then we will allow spaces, underscores and dots
		$text = preg_replace('![^a-z0-9\-._ ]!s', '-', $text);
	}
	else
	{
		$text = preg_replace('![^a-z0-9\-]!s', '-', $text);

		// Convert every white space char with "-"
		$text = preg_replace('!\s+!s', '-', $text);
	}

	// Replace multiple "-"
	$text = preg_replace('!-+!s', '-', $text);
	// Replace multiple "_"
	$text = preg_replace('!_+!s', '_', $text);
	// Remove leading / trailing "-"/"_"...
	$text = preg_replace('!^[-_]|[-_]$!s', '', $text);

	return $text;
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

/*
* jumpbox() : replace the original phpBB make_jumpbox()
*/
function jumpbox($action, $match_forum_id = 0)
{
	global $db, $template, $userdata, $lang;

	// build the jumpbox
	$boxstring  = '<select name="selected_id" onchange="if(this.options[this.selectedIndex].value != -1){ forms[\'jumpbox\'].submit() }">';
	$boxstring .= get_tree_option(POST_FORUM_URL . $match_forum_id);
	$boxstring .= '</select>';

	$boxstring .= '<input type="hidden" name="sid" value="' . $userdata['session_id'] . '" />';

	// dump this to template
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

/*
* Creates forum jumpbox
*/
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
function create_server_url($without_script_path = false)
{
	// usage: $server_url = create_server_url();
	global $config;

	if (!empty($config['current_site_url']))
	{
		return $config['current_site_url'];
	}

	$server_protocol = ($config['cookie_secure']) ? 'https://' : 'http://';
	$server_name = preg_replace('#^\/?(.*?)\/?$#', '\1', trim($config['server_name']));
	$server_port = ($config['server_port'] <> 80) ? ':' . trim($config['server_port']) : '';
	$script_name = preg_replace('/^\/?(.*?)\/?$/', '\1', trim($config['script_path']));
	$script_name = ($script_name == '') ? $script_name : '/' . $script_name;
	$server_url = $server_protocol . $server_name . $server_port . ($without_script_path ? '' : $script_name);
	while(substr($server_url, -1, 1) == '/')
	{
		$server_url = substr($server_url, 0, -1);
	}
	$server_url = $server_url . '/';

	$config['current_site_url'] = $server_url;
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
	global $db, $config, $lang;

	if (!$return)
	{
		garbage_collection();
	}

	// Make sure no &amp;'s are in, this will break the redirect
	$url = str_replace('&amp;', '&', $url);

	// Make sure no linebreaks are there... to prevent http response splitting for PHP < 4.4.2
	if ((strpos(urldecode($url), "\n") !== false) || (strpos(urldecode($url), "\r") !== false) || (strpos($url, ';') !== false))
	{
		message_die(GENERAL_ERROR, 'Tried to redirect to potentially insecure url');
		//trigger_error('Tried to redirect to potentially insecure url.', E_USER_ERROR);
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
		//trigger_error('Tried to redirect to potentially insecure url.', E_USER_ERROR);
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
* Global function for chmodding directories and files for internal use
* This function determines owner and group whom the file belongs to and user and group of PHP and then set safest possible file permissions.
* The function determines owner and group from common.php file and sets the same to the provided file. Permissions are mapped to the group, user always has rw(x) permission.
* The function uses bit fields to build the permissions.
* The function sets the appropiate execute bit on directories.
*
* Supported constants representing bit fields are:
*
* CHMOD_ALL - all permissions (7)
* CHMOD_READ - read permission (4)
* CHMOD_WRITE - write permission (2)
* CHMOD_EXECUTE - execute permission (1)
*
* NOTE: The function uses POSIX extension and fileowner()/filegroup() functions. If any of them is disabled, this function tries to build proper permissions, by calling is_readable() and is_writable() functions.
*
* @param $filename The file/directory to be chmodded
* @param $perms Permissions to set
* @return true on success, otherwise false
*
* @author faw, phpBB Group
*/
function phpbb_chmod($filename, $perms = CHMOD_READ)
{
	// Return if the file no longer exists.
	if (!file_exists($filename))
	{
		return false;
	}

	if (!function_exists('fileowner') || !function_exists('filegroup'))
	{
		$file_uid = $file_gid = false;
		$common_php_owner = $common_php_group = false;
	}
	else
	{
		// Determine owner/group of common.php file and the filename we want to change here
		$common_php_owner = fileowner(IP_ROOT_PATH . 'common.' . PHP_EXT);
		$common_php_group = filegroup(IP_ROOT_PATH . 'common.' . PHP_EXT);

		$file_uid = fileowner($filename);
		$file_gid = filegroup($filename);

		// Try to set the owner to the same common.php has
		if (($common_php_owner !== $file_uid) && ($common_php_owner !== false) && ($file_uid !== false))
		{
			// Will most likely not work
			if (@chown($filename, $common_php_owner));
			{
				clearstatcache();
				$file_uid = fileowner($filename);
			}
		}

		// Try to set the group to the same common.php has
		if (($common_php_group !== $file_gid) && ($common_php_group !== false) && ($file_gid !== false))
		{
			if (@chgrp($filename, $common_php_group));
			{
				clearstatcache();
				$file_gid = filegroup($filename);
			}
		}
	}

	// And the owner and the groups PHP is running under.
	$php_uid = (function_exists('posix_getuid')) ? @posix_getuid() : false;
	$php_gids = (function_exists('posix_getgroups')) ? @posix_getgroups() : false;

	// Who is PHP?
	if (($file_uid === false) || ($file_gid === false) || ($php_uid === false) || ($php_gids === false))
	{
		$php = NULL;
	}
	elseif ($file_uid == $php_uid)
	{
		$php = 'owner';
	}
	elseif (in_array($file_gid, $php_gids))
	{
		$php = 'group';
	}
	else
	{
		$php = 'other';
	}

	// Owner always has read/write permission
	$owner = CHMOD_READ | CHMOD_WRITE;
	if (is_dir($filename))
	{
		$owner |= CHMOD_EXECUTE;

		// Only add execute bit to the permission if the dir needs to be readable
		if ($perms & CHMOD_READ)
		{
			$perms |= CHMOD_EXECUTE;
		}
	}

	switch ($php)
	{
		case null:
		case 'owner':
			/* ATTENTION: if php is owner or NULL we set it to group here. This is the most failsafe combination for the vast majority of server setups.

			$result = @chmod($filename, ($owner << 6) + (0 << 3) + (0 << 0));

			clearstatcache();

			if (!is_null($php) || (is_readable($filename) && is_writable($filename)))
			{
				break;
			}
		*/

		case 'group':
			$result = @chmod($filename, ($owner << 6) + ($perms << 3) + (0 << 0));

			clearstatcache();

			if (!is_null($php) || ((!($perms & CHMOD_READ) || is_readable($filename)) && (!($perms & CHMOD_WRITE) || is_writable($filename))))
			{
				break;
			}

		case 'other':
			$result = @chmod($filename, ($owner << 6) + ($perms << 3) + ($perms << 0));

			clearstatcache();

			if (!is_null($php) || ((!($perms & CHMOD_READ) || is_readable($filename)) && (!($perms & CHMOD_WRITE) || is_writable($filename))))
			{
				break;
			}

		default:
			return false;
		break;
	}

	return $result;
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
* Setup mods
*/
function setup_mods()
{
	global $cache, $db, $config, $lang;
	global $mods, $list_yes_no, $list_time_intervals;

	// Get all the mods settings
	$mods = array();
	foreach ($cache->obtain_mods_settings() as $mod_file)
	{
		@include(IP_ROOT_PATH . 'includes/mods_settings/' . $mod_file . '.' . PHP_EXT);
	}
	return true;
}

/**
* Setup basic lang
*/
function setup_basic_lang()
{
	global $cache, $config, $lang;

	if (empty($lang))
	{
		if(!file_exists(IP_ROOT_PATH . 'language/lang_' . $config['default_lang'] . '/lang_main.' . PHP_EXT))
		{
			$config['default_lang'] = 'english';
		}

		$lang_files = array(
			'lang_main',
			'lang_main_settings',
			'lang_bbcb_mg',
			'lang_main_upi2db',
			'lang_news',
			'lang_main_attach',
			'lang_main_cback_ctracker',
		);

		if (defined('CASH_PLUGIN_ENABLED') && CASH_PLUGIN_ENABLED && defined('IN_CASHMOD'))
		{
			$lang_files = array_merge($lang_files, array('lang_cash'));
		}

		$lang_extend_admin = false;
		if (defined('IN_ADMIN'))
		{
			$lang_extend_admin = true;
			$lang_files_admin = array(
				'lang_admin',
				'lang_admin_cback_ctracker',
				'lang_admin_upi2db',
				'lang_admin_attach',
				'lang_jr_admin',
			);
			$lang_files = array_merge($lang_files, $lang_files_admin);
		}

		$lang_files = array_merge($lang_files, $cache->obtain_lang_files());
		$lang_files = array_merge($lang_files, array('lang_user_created'));

		foreach ($lang_files as $lang_file)
		{
			@include_once(IP_ROOT_PATH . 'language/lang_' . $config['default_lang'] . '/' . $lang_file . '.' . PHP_EXT);
		}
	}
	return true;
}

/**
* Setup extra lang
*/
function setup_extra_lang($lang_files_array, $lang_base_path = '')
{
	global $config, $lang;

	if (empty($lang_files_array))
	{
		return false;
	}

	if (!is_array($lang_files_array))
	{
		$lang_files_array = array($lang_files_array);
	}

	$lang_base_path = (empty($lang_base_path) ? (IP_ROOT_PATH . 'language/') : $lang_base_path);
	for ($i = 0; $i < sizeof($lang_files_array); $i++)
	{
		$user_lang_file = $lang_base_path . 'lang_' . $config['default_lang'] . '/' . $lang_files_array[$i] . '.' . PHP_EXT;
		$default_lang_file = $lang_base_path . 'lang_english/' . $lang_files_array[$i] . '.' . PHP_EXT;
		if (@file_exists($user_lang_file))
		{
			@include_once($user_lang_file);
		}
		elseif (@file_exists($default_lang_file))
		{
			@include_once($default_lang_file);
		}
	}

	return true;
}

/**
* Get style details
*/
function get_style($style_id, $from_cache = true)
{
	global $db, $config, $all_styles_array;

	if (!empty($all_styles_array[$style_id]))
	{
		$style_row = $all_styles_array[$style_id];
	}
	else
	{
		$style_row = array();
		$sql = "SELECT * FROM " . THEMES_TABLE . " WHERE themes_id = " . $style_id . " LIMIT 1";
		$result = $from_cache ? $db->sql_query($sql, 0, 'styles_') : $db->sql_query($sql);
		$style_row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);
		if (!empty($style_row))
		{
			$all_styles_array[$style_row['themes_id']] = $style_row;
		}
	}

	return $style_row;
}

/**
* Setup the default style
*/
function setup_style($style_id, $current_default_style, $current_style = false)
{
	global $db, $config, $template, $images, $all_styles_array;

	if (!empty($all_styles_array[$style_id]))
	{
		$template_row = $all_styles_array[$style_id];
	}
	elseif (($style_id == $config['default_style']) && !empty($config['default_style_row']))
	{
		$template_row = $config['default_style_row'];
	}
	else
	{
		$style_id = (int) $style_id;
		$template_row = get_style($style_id, true);
		$style_exists = !empty($template_row) ? true : false;

		if (!$style_exists)
		{
			// We are trying to setup a style which does not exist in the database
			// Try to fallback to the board default (if the user had a custom style)
			// and then any users using this style to the default if it succeeds
			$style_exists = false;
			if (($style_id != $config['default_style']) || !empty($current_style))
			{
				if (!empty($current_style))
				{
					$config['default_style'] = $current_style;
				}
				$style_id = (int) $config['default_style'];
				$template_row = get_style($style_id, true);
				$style_exists = !empty($template_row) ? true : false;

				if (!$style_exists)
				{
					$style_id = (int) $current_default_style;
					$template_row = get_style($style_id, true);
					$style_exists = !empty($template_row) ? true : false;
				}
				else
				{
					if (empty($current_style))
					{
						$sql = "UPDATE " . USERS_TABLE . "
							SET user_style = " . (int) $config['default_style'] . "
							WHERE user_style = '" . $style_id . "'";
						$result = $db->sql_query($sql);
					}
				}
			}

			if (!$style_exists)
			{
				message_die(CRITICAL_ERROR, "Could not get theme data for themes_id [$style_id]", '', __LINE__, __FILE__);
			}
		}
	}
	unset($row);
	$all_styles_array[$style_id] = $template_row;
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
	global $db, $config, $template, $images, $all_styles_array;

	$style_exists = false;

	if (!empty($all_styles_array[$style_id]))
	{
		$style_exists = true;
	}
	else
	{
		$template_row = array();
		$style_id = (int) $style_id;
		$template_row = get_style($style_id, true);
		$style_exists = !empty($template_row) ? true : false;
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
	global $config;
	return (@strtotime(gmdate('M d Y H:i:s', $gmepoch + (3600 * $tz))));
}

/*
* A more logic function to output serial dates
*/
/*
function dateserial($year, $month, $day, $hour, $minute, $timezone = 'UTC')
{
	$org_tz = date_default_timezone_get();
	date_default_timezone_set($timezone);
	$date_serial = gmmktime($hour, $minute, 0, $month, $day, $year);
	date_default_timezone_set($org_tz);
	return $date_serial;
}
*/

// Get DST
function get_dst($gmepoch, $tz = 0)
{
	global $config, $userdata;

	$tz = empty($tz) ? $config['board_timezone'] : $tz;
	if (!empty($userdata) && !$userdata['session_logged_in'])
	{
		$userdata['user_time_mode'] = $config['default_time_mode'];
		$userdata['user_dst_time_lag'] = $config['default_dst_time_lag'];
	}
	elseif (!empty($userdata))
	{
		$config['default_time_mode'] = $userdata['user_time_mode'];
		$config['default_dst_time_lag'] = $userdata['user_dst_time_lag'];
	}
	$time_mode = $config['default_time_mode'];
	$dst_time_lag = $config['default_dst_time_lag'];

	switch ($time_mode)
	{
		case MANUAL_DST:
			$dst_sec = $dst_time_lag * 60;
			break;
		case SERVER_SWITCH:
			//$dst_sec = gmdate('I', $gmepoch + (3600 * $tz)) * $dst_time_lag * 60;
			$dst_sec = @date('I', $gmepoch) * $dst_time_lag * 60;
			break;
		default:
			$dst_sec = 0;
			break;
	}
	return $dst_sec;
}

// Create date/time from format and timezone
function create_date($format, $gmepoch, $tz = 0)
{
	global $config, $userdata, $lang;
	static $translate;

	$tz = empty($tz) ? $config['board_timezone'] : $tz;
	// We need to force this ==> isset($lang['datetime']) <== otherwise we may have $lang initialized and we don't want that...
	if (empty($translate) && ($config['default_lang'] != 'english') && isset($lang['datetime']))
	{
		@reset($lang['datetime']);
		while (list($match, $replace) = @each($lang['datetime']))
		{
			$translate[$match] = $replace;
		}
	}

	$dst_sec = get_dst($gmepoch, $tz);
	$date = @gmdate($format, $gmepoch + (3600 * $tz) + $dst_sec);
	$date = (!empty($translate) ? strtr($date, $translate) : $date);
	return $date;
}

function create_date_midnight($gmepoch, $tz = 0)
{
	global $config;

	$tz = empty($tz) ? $config['board_timezone'] : $tz;
	$dst_sec = get_dst($gmepoch, $tz);
	$zone_offset = (3600 * $tz) + $dst_sec;
	list($d, $m, $y) = explode(' ', gmdate('j n Y', time() + $zone_offset));
	$midnight = gmmktime(0, 0, 0, $m, $d, $y) - $zone_offset;

	return $midnight;
}

function create_date_ip($format, $gmepoch, $tz = 0, $day_only = false)
{
	global $config, $lang;

	$tz = empty($tz) ? $config['board_timezone'] : $tz;
	$midnight = create_date_midnight($gmepoch, $tz);

	$output_date = '';
	$format_hour = 'H:i';
	if ($gmepoch > $midnight)
	{
		$format = ($day_only) ? $format : $format_hour;
		$output_date = ($day_only) ? $lang['TODAY'] : ($lang['Today_at'] . ' ');
	}
	elseif ($gmepoch > ($midnight - 86400))
	{
		$format = ($day_only) ? $format : $format_hour;
		$output_date = ($day_only) ? $lang['YESTERDAY'] : ($lang['Yesterday_at'] . ' ');
	}
	$output_date = $output_date . (($day_only && !empty($output_date)) ? '' : create_date($format, $gmepoch, $tz));
	return $output_date;
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
* Pagination get the page
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

/**
* Return current page (pagination)
*/
function on_page($num_items, $per_page, $start)
{
	global $lang;

	// Make sure $per_page is a valid value
	$per_page = ($per_page <= 0) ? 1 : $per_page;

	$on_page = floor($start / $per_page) + 1;
	$total_pages = ceil($num_items / $per_page);

	$page_number = sprintf($lang['Page_of'], $on_page, max($total_pages, 1));

	return $page_number;
}

/*
* Pagination routine, generates page number sequence
*/
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

/**
* Generate full pagination with template
*/
function generate_full_pagination($base_url, $num_items, $per_page, $start_item, $add_prevnext_text = true, $start = 'start')
{
	global $template, $lang;

	$template->assign_vars(array(
		'PAGINATION' => generate_pagination($base_url, $num_items, $per_page, $start_item, $add_prevnext_text, $start),
		'PAGE_NUMBER' => on_page($num_items, $per_page, $start_item),
		'L_GOTO_PAGE' => $lang['Goto_page']
		)
	);

	return true;
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

/**
* Censoring
*/
function censor_text($text)
{
	static $censors;

	if (empty($text))
	{
		return $text;
	}

	// We moved the word censor checks in here because we call this function quite often - and then only need to do the check once
	if (!isset($censors) || !is_array($censors))
	{
		global $userdata, $cache;

		// We check here if the user is having viewing censors disabled (and also allowed to do so).
		if ($userdata['user_allowswearywords'])
		{
			$censors = array();
		}
		else
		{
			$censors = $cache->obtain_word_list();
		}
	}

	if (sizeof($censors))
	{
		return preg_replace($censors['match'], $censors['replace'], $text);
	}

	return $text;
}

/**
* Little helper for the build_hidden_fields function
*/
function _build_hidden_fields($key, $value, $specialchar, $stripslashes)
{
	$hidden_fields = '';
	if (!is_array($value))
	{
		$value = ($stripslashes) ? stripslashes($value) : $value;
		$value = ($specialchar) ? htmlspecialchars($value, ENT_COMPAT, 'UTF-8') : $value;
		$hidden_fields .= '<input type="hidden" name="' . $key . '" value="' . $value . '" />' . "\n";
	}
	else
	{
		foreach ($value as $_key => $_value)
		{
			$_key = ($stripslashes) ? stripslashes($_key) : $_key;
			$_key = ($specialchar) ? htmlspecialchars($_key, ENT_COMPAT, 'UTF-8') : $_key;
			$hidden_fields .= _build_hidden_fields($key . '[' . $_key . ']', $_value, $specialchar, $stripslashes);
		}
	}
	return $hidden_fields;
}

/**
* Build simple hidden fields from array
*
* @param array $field_ary an array of values to build the hidden field from
* @param bool $specialchar if true, keys and values get specialchared
* @param bool $stripslashes if true, keys and values get stripslashed
*
* @return string the hidden fields
*/
function build_hidden_fields($field_ary, $specialchar = false, $stripslashes = false)
{
	$s_hidden_fields = '';
	foreach ($field_ary as $name => $vars)
	{
		$name = ($stripslashes) ? stripslashes($name) : $name;
		$name = ($specialchar) ? htmlspecialchars($name, ENT_COMPAT, 'UTF-8') : $name;
		$s_hidden_fields .= _build_hidden_fields($name, $vars, $specialchar, $stripslashes);
	}
	return $s_hidden_fields;
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

//--------------------------------------------------------------------------------------------------
// board_stats : update the board stats (topics, posts and users)
//--------------------------------------------------------------------------------------------------
function board_stats()
{
	global $db, $config;

	$config_updated = false;
	// max users
	$sql = "SELECT COUNT(user_id) AS user_total FROM " . USERS_TABLE . " WHERE user_id > 0";
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$max_users = intval($row['user_total']);

	// update
	if ($config['max_users'] != $max_users)
	{
		set_config('max_users', $max_users);
	}

	// newest user
	if ($config['inactive_users_memberlists'] == true)
	{
		$sql_active_users = '';
	}
	else
	{
		$sql_active_users = 'AND user_active = 1';
	}
	$sql = "SELECT user_id, username
		FROM " . USERS_TABLE . "
		WHERE user_id <> " . ANONYMOUS . "
		$sql_active_users
		ORDER BY user_id DESC
		LIMIT 1";
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$newest_user_id = intval($row['user_id']);

	if ($config['last_user_id'] != $newest_user_id)
	{
		set_config('last_user_id', $newest_user_id);
		$cache->destroy('newest_user');
		$newest_user = $cache->obtain_newest_user();
	}

	// topics and posts
	$sql = "SELECT SUM(forum_topics) AS topic_total, SUM(forum_posts) AS post_total FROM " . FORUMS_TABLE;
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$max_topics = intval($row['topic_total']);
	$max_posts = intval($row['post_total']);

	// update
	if ($config['max_topics'] != $max_topics)
	{
		set_config('max_topics', $max_topics);
	}
	if ($config['max_posts'] != $max_posts)
	{
		set_config('max_posts', $max_posts);
	}
}

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
	$result = $db->sql_query($sql, 0, 'bots_list_');

	while($row = $db->sql_fetchrow($result))
	{
		$active_bots[] = $row;
	}
	$db->sql_freeresult($result);

	for ($i = 0; $i < sizeof($active_bots); $i++)
	{
		if (!empty($active_bots[$i]['bot_agent']) && preg_match('#' . str_replace('\*', '.*?', preg_quote($active_bots[$i]['bot_agent'], '#')) . '#i', $browser))
		{
			$bot_name = (!empty($active_bots[$i]['bot_color']) ? $active_bots[$i]['bot_color'] : ('<b style="color:' . $bot_color . '">' . $active_bots[$i]['bot_name'] . '</b>'));
			if (($check_inactive == true) && ($active_bots[$i]['bot_active'] == 0))
			{
				message_die(GENERAL_ERROR, $lang['Not_Authorized']);
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
						message_die(GENERAL_ERROR, $lang['Not_Authorized']);
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
	global $db, $config;

	$sql = "UPDATE " . BOTS_TABLE . "
					SET bot_visit_counter = (bot_visit_counter + 1),
						bot_last_visit = '" . time() . "'
					WHERE bot_id = '" . $bot_id . "'";
	$result = $db->sql_query($sql);

	if ($config['google_bot_detector'])
	{
		if (eregi('googlebot', $_SERVER['HTTP_USER_AGENT']))
		{
			$url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'] . (($_SERVER['QUERY_STRING'] != '') ? '?' . $_SERVER['QUERY_STRING'] : '');
			$now = time();

			$sql = "INSERT INTO " . GOOGLE_BOT_DETECTOR_TABLE . "(detect_time, detect_url) VALUES('$now', '$url')";
			$result = $db->sql_query($sql);
		}
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
	global $db, $config, $lang;

	$user_id = empty($user_id) ? ANONYMOUS : $user_id;
	$is_guest = ($user_id == ANONYMOUS) ? true : false;

	if ((!$is_guest && $from_db) || (!$is_guest && empty($username) && empty($user_color)))
	{
		// Get the user info and see if they are assigned a color_group
		$sql = user_color_sql($user_id);
		$cache_cleared = (CACHE_COLORIZE && defined('IN_ADMIN')) ? clear_user_color_cache($user_id) : false;
		$result = ((CACHE_COLORIZE || $force_cache) && !defined('IN_ADMIN')) ? $db->sql_query($sql, 0, POST_USERS_URL . '_', USERS_CACHE_FOLDER) : $db->sql_query($sql);
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

	$username = (($user_id == ANONYMOUS) || empty($username)) ? $lang['Guest'] : str_replace('&amp;amp;', '&amp;', htmlspecialchars($username));
	$user_link_style = '';
	$user_link_begin = '<a href="' . append_sid(IP_ROOT_PATH . CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $user_id) . '"';
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
		$user_color = ($user_color != false) ? $user_color : $config['active_users_color'];
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
	global $config;

	$avatar_img = '&nbsp;';
	if ($config['default_avatar_set'] != 3)
	{
		if (($config['default_avatar_set'] == 0) && ($user_id == ANONYMOUS) && ($config['default_avatar_guests_url'] != ''))
		{
			$avatar_img = $config['default_avatar_guests_url'];
		}
		elseif (($config['default_avatar_set'] == 1) && ($user_id != ANONYMOUS) && ($config['default_avatar_users_url'] != ''))
		{
			$avatar_img = $config['default_avatar_users_url'];
		}
		elseif ($config['default_avatar_set'] == 2)
		{
			if (($user_id == ANONYMOUS) && ($config['default_avatar_guests_url'] != ''))
			{
				$avatar_img = $config['default_avatar_guests_url'];
			}
			elseif (($user_id != ANONYMOUS) && ($config['default_avatar_users_url'] != ''))
			{
				$avatar_img = $config['default_avatar_users_url'];
			}
		}
	}

	$avatar_img = ($avatar_img == '&nbsp;') ? '&nbsp;' : '<img src="' . $path_prefix . $avatar_img . '" alt="avatar" />';

	return $avatar_img;
}

function user_get_avatar($user_id, $user_level, $user_avatar, $user_avatar_type, $user_allow_avatar, $path_prefix = '')
{
	global $config;
	$user_avatar_link = '';
	if ($user_avatar_type && ($user_id != ANONYMOUS) && $user_allow_avatar)
	{
		switch($user_avatar_type)
		{
			case USER_AVATAR_UPLOAD:
				$user_avatar_link = ($config['allow_avatar_upload']) ? '<img src="' . $path_prefix . $config['avatar_path'] . '/' . $user_avatar . '" alt="avatar" style="margin-bottom: 3px;" />' : '';
				break;
			case USER_AVATAR_REMOTE:
				$user_avatar_link = resize_avatar($user_id, $user_level, $user_avatar);
				break;
			case USER_AVATAR_GALLERY:
				$user_avatar_link = ($config['allow_avatar_local']) ? '<img src="' . $path_prefix . $config['avatar_gallery_path'] . '/' . $user_avatar . '" alt="avatar" style="margin-bottom: 3px;" />' : '';
				break;
			case USER_GRAVATAR:
				$user_avatar_link = ($config['enable_gravatars']) ? '<img src="' . get_gravatar($user_avatar) . '" alt="avatar" style="margin-bottom: 3px;" />' : '';
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
	global $config;

	if ($user_level == ADMIN)
	{
		return '<img src="' . $avatar_url . '" alt="avatar" style="margin-bottom: 3px;" />';
	}

	// Set this to false if you want to force height as well
	$force_width_only = true;

	$avatar_width = $config['avatar_max_width'];
	$avatar_height = $config['avatar_max_height'];

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
	$avatar_img = ($config['allow_avatar_remote']) ? '<img src="' . $avatar_url . '"' . $avatar_img_dim . ' alt="avatar" style="margin-bottom: 3px;" />' : '';

	return $avatar_img;
}

function get_gravatar($email)
{
	global $config;

	$image = '';
	if(!empty($email))
	{
		$image = 'http://www.gravatar.com/avatar.php?gravatar_id=' . md5($email);

		if($config['gravatar_rating'])
		{
			$image .= '&amp;rating=' . $config['gravatar_rating'];
		}

		if($config['gravatar_default_image'])
		{
			$server_protocol = ($config['cookie_secure']) ? 'https://' : 'http://';
			$server_name = preg_replace('#^\/?(.*?)\/?$#', '\1', trim($config['server_name']));
			$server_port = ($config['server_port'] <> 80) ? ':' . trim($config['server_port']) : '';
			$script_name = preg_replace('#^\/?(.*?)\/?$#', '\1', trim($config['script_path']));
			$script_name = ($script_name == '') ? $script_name : '/' . $script_name;
			$url = preg_replace('#^\/?(.*?)\/?$#', '/\1', trim($config['gravatar_default_image']));

			$default_image = $server_protocol . $server_name . $server_port . $script_name . $url;
			$image .= '&amp;default=' . urlencode($default_image);
		}

		$max_size = ($config['avatar_max_width'] <= $config['avatar_max_height']) ? $config['avatar_max_width'] : $config['avatar_max_height'];
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

/*
* Get AD
*/
function get_ad($ad_position)
{
	global $db, $config, $userdata;

	$ad_text = '';
	if (!$config['ads_' . $ad_position])
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
	$result = $db->sql_query($sql, 0, 'ads_');

	$active_ads = array();
	while($row = $db->sql_fetchrow($result))
	{
		$active_ads[] = $row;
	}
	$db->sql_freeresult($result);

	$total_ads = sizeof($active_ads);
	if ($total_ads > 0)
	{
		$selected_ad = rand(0, $total_ads - 1);
		$ad_text = stripslashes($active_ads[$selected_ad]['ad_text']);
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

function files_remove($files_to_remove)
{
	for ($i = 0; $i < sizeof($files_to_remove); $i++)
	{
		if (file_exists($files_to_remove[$i]))
		{
			@unlink($files_to_remove[$i]);
		}
	}
	return true;
}

function empty_cache_folders_admin()
{
	global $cache;

	$cache->destroy_datafiles(array('_hooks', '_moderators'), MAIN_CACHE_FOLDER, 'data', false);
	$cache->destroy_datafiles(array('_birthdays_list', '_lang', '_today_visitors'), MAIN_CACHE_FOLDER, 'data', true);
	$cache->destroy_datafiles(array('_'), CMS_CACHE_FOLDER, 'sql', true);
	$cache->destroy_datafiles(array('_'), FORUMS_CACHE_FOLDER, 'sql', true);
	$cache->destroy_datafiles(array('_'), SQL_CACHE_FOLDER, 'sql', true);

	return true;
}

function empty_cache_folders_cms()
{
	global $cache;

	$cache->destroy_datafiles(array('_cms_config', '_cms_layouts_config'), MAIN_CACHE_FOLDER, 'data', false);
	$cache->destroy_datafiles(array('_cms_global_blocks_config'), MAIN_CACHE_FOLDER, 'data', true);
	$cache->destroy_datafiles(array('_'), CMS_CACHE_FOLDER, 'sql', true);
	$cache->destroy_datafiles(array('_'), SQL_CACHE_FOLDER, 'sql', true);

	return true;
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
		'empty_cache.bat',
	);

	$sql_prefix = 'sql_';
	$tpl_prefix = 'tpl_';

	// Make sure the forum tree is deleted...
	@unlink(MAIN_CACHE_FOLDER . CACHE_TREE_FILE);

	$cache_dirs_array = array(MAIN_CACHE_FOLDER, CMS_CACHE_FOLDER, FORUMS_CACHE_FOLDER, POSTS_CACHE_FOLDER, SQL_CACHE_FOLDER, TOPICS_CACHE_FOLDER, USERS_CACHE_FOLDER);
	$cache_dirs_array = ((empty($cache_folder) || !in_array($cache_folder, $cache_dirs_array)) ? $cache_dirs_array : array($cache_folder));
	$files_counter = 0;
	for ($i = 0; $i < sizeof($cache_dirs_array); $i++)
	{
		$dir = $cache_dirs_array[$i];
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

	$cache_dirs_array = array(POSTED_IMAGES_THUMBS_PATH, IP_ROOT_PATH . ALBUM_CACHE_PATH, IP_ROOT_PATH . ALBUM_MED_CACHE_PATH, IP_ROOT_PATH . ALBUM_WM_CACHE_PATH);
	$files_counter = 0;
	for ($i = 0; $i < sizeof($cache_dirs_array); $i++)
	{
		$dir = $cache_dirs_array[$i];
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

/**
* Page Header
*/
function page_header($title = '', $parse_template = false)
{
	global $db, $cache, $config, $template, $images, $theme, $userdata, $lang, $tree;
	global $table_prefix, $SID, $_SID, $user_ip;
	global $ip_cms, $cms_config_vars, $cms_config_global_blocks, $cms_config_layouts, $cms_page;
	global $ctracker_config, $session_length, $starttime, $base_memory_usage, $do_gzip_compress, $start;
	global $gen_simple_header, $meta_content, $nav_separator, $nav_links, $nav_pgm, $nav_add_page_title, $skip_nav_cat;
	global $breadcrumbs_address, $breadcrumbs_links_left, $breadcrumbs_links_right;
	global $css_include, $css_style_include, $js_include;

	if (defined('HEADER_INC'))
	{
		return;
	}

	define('HEADER_INC', true);

	// gzip_compression
	if ($config['gzip_compress'])
	{
		if (@extension_loaded('zlib') && !headers_sent())
		{
			ob_start('ob_gzhandler');
		}
	}

	// CMS
	if(!defined('CMS_INIT'))
	{
		define('CMS_INIT', true);
		$cms_config_vars = $cache->obtain_cms_config();
		$cms_config_global_blocks = $cache->obtain_cms_global_blocks_config(false);
	}

	if (defined('IN_CMS'))
	{
		$config['cms_style'] = (!empty($_GET['cms_style']) ? ((intval($_GET['cms_style']) == 1) ? 1 : 0) : $config['cms_style']);
		$cms_style_std = ($config['cms_style'] == 1) ? false : true;
		$template->assign_var('CMS_STD_TPL', $cms_style_std);
	}

	//$server_url = create_server_url();
	$page_url = pathinfo($_SERVER['PHP_SELF']);
	$page_query = $_SERVER['QUERY_STRING'];

	$meta_content['page_title'] = !empty($title) ? $title : $meta_content['page_title'];
	$meta_content['page_title'] = empty($meta_content['page_title']) ? htmlspecialchars($config['sitename']) : strip_tags($meta_content['page_title']);
	$meta_content['page_title_clean'] = empty($meta_content['page_title_clean']) ? strip_tags($meta_content['page_title']) : $meta_content['page_title_clean'];

	// DYNAMIC META TAGS - BEGIN
	$meta_content_pages_array = array(CMS_PAGE_VIEWFORUM, CMS_PAGE_VIEWFORUMLIST, CMS_PAGE_VIEWTOPIC);
	if (!in_array($page_url['basename'], $meta_content_pages_array))
	{
		$meta_content['cat_id'] = (!empty($_GET[POST_CAT_URL]) && (intval($_GET[POST_CAT_URL]) > 0)) ? intval($_GET[POST_CAT_URL]) : 0;
		$meta_content['forum_id'] = (!empty($_GET[POST_FORUM_URL]) && (intval($_GET[POST_FORUM_URL]) > 0)) ? intval($_GET[POST_FORUM_URL]) : 0;
		$meta_content['topic_id'] = (!empty($_GET[POST_TOPIC_URL]) && (intval($_GET[POST_TOPIC_URL]) > 0)) ? intval($_GET[POST_TOPIC_URL]) : 0;
		$meta_content['post_id'] = (!empty($_GET[POST_POST_URL]) && (intval($_GET[POST_POST_URL]) > 0)) ? intval($_GET[POST_POST_URL]) : 0;

		$no_meta_pages_array = array(CMS_PAGE_LOGIN, 'privmsg.' . PHP_EXT, CMS_PAGE_POSTING, 'sudoku.' . PHP_EXT, 'kb.' . PHP_EXT);
		if (!in_array($page_url['basename'], $no_meta_pages_array) && (!empty($meta_content['post_id']) || !empty($meta_content['topic_id']) || !empty($meta_content['forum_id']) || !empty($meta_content['cat_id'])))
		{
			@include_once(IP_ROOT_PATH . 'includes/functions_meta.' . PHP_EXT);
			create_meta_content();
		}
		else
		{
			$meta_content['page_title'] = (defined('IN_LOGIN') ? $lang['Login'] : $meta_content['page_title']);
			$meta_content['description'] = (defined('IN_LOGIN') ? $lang['Default_META_Description'] : $meta_content['description']);
			$meta_content['keywords'] = (defined('IN_LOGIN') ? $lang['Default_META_Keywords'] : $meta_content['keywords']);
		}
	}

	$meta_content['description'] = !empty($meta_content['description']) ? ($meta_content['description'] . (META_TAGS_ATTACH ? (' - ' . $lang['Default_META_Description']) : '')) : $lang['Default_META_Description'];
	$meta_content['keywords'] = !empty($meta_content['keywords']) ? ($meta_content['keywords'] . (META_TAGS_ATTACH ? (' - ' . $lang['Default_META_Keywords']) : '')) : $lang['Default_META_Keywords'];

	$meta_content['description'] = strip_tags($meta_content['description']);
	$meta_content['keywords'] = strip_tags($meta_content['keywords']);
	$meta_content['keywords'] = (substr($meta_content['keywords'], -2) == ', ') ? substr($meta_content['keywords'], 0, -2) : $meta_content['keywords'];

	$phpbb_meta = '<meta name="title" content="' . $meta_content['page_title'] . '" />' . "\n";
	$phpbb_meta .= '<meta name="author" content="' . $lang['Default_META_Author'] . '" />' . "\n";
	$phpbb_meta .= '<meta name="copyright" content="' . $lang['Default_META_Copyright'] . '" />' . "\n";
	$phpbb_meta .= '<meta name="description" content="' . str_replace('"', '', $meta_content['description']) . '" />' . "\n";
	$phpbb_meta .= '<meta name="keywords" content="' . str_replace('"', '', $meta_content['keywords']) . '" />' . "\n";
	$phpbb_meta .= '<meta name="category" content="general" />' . "\n";

	if (defined('IN_ADMIN') || defined('IN_CMS') || defined('IN_SEARCH') || defined('IN_POSTING'))
	{
		$phpbb_meta .= '<meta name="robots" content="noindex,nofollow" />' . "\n";
	}
	else
	{
		$phpbb_meta .= '<meta name="robots" content="index,follow" />' . "\n";
	}

	$phpbb_meta .= !empty($lang['Extra_Meta']) ? ($lang['Extra_Meta'] . "\n\n") : "\n";

	$canonical_pages_array = array(CMS_PAGE_FORUM, CMS_PAGE_VIEWFORUM, CMS_PAGE_VIEWTOPIC);
	if (in_array($page_url['basename'], $canonical_pages_array))
	{
		$canonical_append = '';
		if ($page_url['basename'] == CMS_PAGE_FORUM)
		{
			$canonical_append .= (!empty($meta_content['cat_id']) ? ((empty($canonical_append) ? '' : '&amp;') . POST_CAT_URL . '=' . $meta_content['cat_id']) : '');
		}
		$canonical_append .= (!empty($meta_content['forum_id']) ? ((empty($canonical_append) ? '' : '&amp;') . POST_FORUM_URL . '=' . $meta_content['forum_id']) : '');
		$canonical_append .= (!empty($meta_content['topic_id']) ? ((empty($canonical_append) ? '' : '&amp;') . POST_TOPIC_URL . '=' . $meta_content['topic_id']) : '');
		$canonical_append .= (!empty($meta_content['post_id']) ? ((empty($canonical_append) ? '' : '&amp;') . POST_POST_URL . '=' . $meta_content['post_id']) : '');
		$canonical_append .= (!empty($start) ? ((empty($canonical_append) ? '' : '&amp;') . 'start=' . $start) : '');

		$canonical_url = $page_url['basename'] . (empty($canonical_append) ? '' : '?') . $canonical_append;

		$phpbb_meta .= (!empty($canonical_url) ? ('<link rel="canonical" href="' . $canonical_url . '" />' . "\n") : '');
	}
	// DYNAMIC META TAGS - END

	// Mighty Gorgon - Smart Header - Begin
	$doctype_html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' . "\n";
	//$doctype_html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">' . "\n";
	$doctype_html .= '<html xmlns="http://www.w3.org/1999/xhtml" dir="' . $lang['DIRECTION'] . '" lang="' . $lang['HEADER_LANG'] . '" xml:lang="' . $lang['HEADER_XML_LANG'] . '">' . "\n";

	if ($page_url['basename'] == 'viewonline.' . PHP_EXT)
	{
		$phpbb_meta .= '<meta http-equiv="refresh" content="180;url=viewonline.' . PHP_EXT . '" />' . "\n";
	}
	// Mighty Gorgon - Smart Header - End

	// Mighty Gorgon - AJAX Features - Begin
	$ajax_user_check = '';
	$ajax_user_check_alt = '';
	if ($config['ajax_features'])
	{
		$template->assign_block_vars('switch_ajax_features', array());
		$ajax_user_check = 'onkeyup="AJAXUsernameSearch(this.value, 0);"';
		$ajax_user_check_alt = 'onkeyup="AJAXUsernameSearch(this.value, 1);"';
	}
	// Mighty Gorgon - AJAX Features - End

	// Generate HTML required for Mozilla Navigation bar
	$nav_base_url = create_server_url();

	// Mozilla navigation bar - Default items that should be valid on all pages.
	// Defined here to correctly assign the Language Variables and be able to change the variables within code.
	$nav_links['top'] = array (
		'url' => append_sid(CMS_PAGE_HOME),
		'title' => htmlspecialchars($config['sitename'])
	);
	$nav_links['forum'] = array (
		'url' => append_sid(CMS_PAGE_FORUM),
		'title' => sprintf($lang['Forum_Index'], htmlspecialchars($config['sitename']))
	);
	$nav_links['search'] = array (
		'url' => append_sid(CMS_PAGE_SEARCH),
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

	$nav_links_html = '';
	while(list($nav_item, $nav_array) = @each($nav_links))
	{
		if (!empty($nav_array['url']))
		{
			$nav_links_html .= '<link rel="' . $nav_item . '" type="text/html" title="' . strip_tags($nav_array['title']) . '" href="' . $nav_base_url . $nav_array['url'] . '" />' . "\n";
		}
		else
		{
			// We have a nested array, used for items like <link rel='chapter'> that can occur more than once.
			while(list(,$nested_array) = each($nav_array))
			{
				$nav_links_html .= '<link rel="' . $nav_item . '" type="text/html" title="' . strip_tags($nested_array['title']) . '" href="' . $nav_base_url . $nested_array['url'] . '" />' . "\n";
			}
		}
	}

	// RSS Autodiscovery - BEGIN
	$rss_url = $nav_base_url . 'rss.' . PHP_EXT;
	$rss_forum_id = (isset($_GET[POST_FORUM_URL]) ? intval($_GET[POST_FORUM_URL]) : 0);
	$rss_url_append = '';
	$rss_a_url_append = '';
	if($rss_forum_id != 0)
	{
		$rss_url_append = '?' . POST_FORUM_URL . '=' . $rss_forum_id;
		$rss_a_url_append = '&amp;' . POST_FORUM_URL . '=' . $rss_forum_id;
	}
	$nav_links_html .= '<link rel="alternate" type="application/rss+xml" title="RSS" href="' . $rss_url . $rss_url_append . '" />' . "\n";
	$nav_links_html .= '<link rel="alternate" type="application/atom+xml" title="Atom" href="' . $rss_url . '?atom' . $rss_a_url_append . '" />' . "\n";
	// RSS Autodiscovery - END

	if(!empty($css_style_include) && is_array($css_style_include))
	{
		for ($i = 0; $i < sizeof($css_style_include); $i++)
		{
			$template->assign_block_vars('css_style_include', array(
				'CSS_FILE' => $css_style_include[$i],
				)
			);
		}
	}

	if(!empty($css_include) && is_array($css_include))
	{
		for ($i = 0; $i < sizeof($css_include); $i++)
		{
			$template->assign_block_vars('css_include', array(
				'CSS_FILE' => $css_include[$i],
				)
			);
		}
	}

	if(!empty($js_include) && is_array($js_include))
	{
		for ($i = 0; $i < sizeof($js_include); $i++)
		{
			$template->assign_block_vars('js_include', array(
				'JS_FILE' => $js_include[$i],
				)
			);
		}
	}

	// Time Management - BEGIN
	// Format Timezone. We are unable to use array_pop here, because of PHP3 compatibility
	$s_timezone = str_replace('.0', '', sprintf('%.1f', number_format($config['board_timezone'], 1)));
	$l_timezone = $lang['tzs'][$s_timezone];

	if (!$userdata['session_logged_in'])
	{
		$userdata['user_time_mode'] = $config['default_time_mode'];
	}

	switch ($userdata['user_time_mode'])
	{
		case MANUAL_DST:
			$time_message = sprintf($lang['All_times'], $l_timezone) . $lang['dst_enabled_mode'];
			break;
		case SERVER_SWITCH:
			$time_message = sprintf($lang['All_times'], $l_timezone);
			if (@date('I'))
			{
				$time_message = $time_message . $lang['dst_enabled_mode'];
			}
			break;
		default:
			$time_message = sprintf($lang['All_times'], $l_timezone);
			break;
	}
	$time_message = str_replace('GMT', 'UTC', $time_message);
	// Time Management - END

	// Mighty Gorgon - Advanced Switches - BEGIN

	// LOGGED IN CHECK - BEGIN
	if (!$userdata['session_logged_in'])
	{
		// Allow autologin?
		if (!isset($config['allow_autologin']) || $config['allow_autologin'])
		{
			$template->assign_block_vars('switch_allow_autologin', array());
		}

		$smart_redirect = strrchr($_SERVER['PHP_SELF'], '/');
		$smart_redirect = substr($smart_redirect, 1, strlen($smart_redirect));

		if(($smart_redirect == (CMS_PAGE_PROFILE)) || ($smart_redirect == (CMS_PAGE_LOGIN)))
		{
			$smart_redirect = '';
		}

		if(isset($_GET) && !empty($smart_redirect))
		{
			$smart_get_keys = array_keys($_GET);

			for ($i = 0; $i < sizeof($_GET); $i++)
			{
				if ($smart_get_keys[$i] != 'sid')
				{
					$smart_redirect .= '&amp;' . $smart_get_keys[$i] . '=' . urlencode(ip_utf8_decode($_GET[$smart_get_keys[$i]]));
				}
			}
		}
		$u_login_logout = CMS_PAGE_LOGIN;
		$u_login_logout .= (!empty($smart_redirect)) ? '?redirect=' . $smart_redirect : '';
		$l_login_logout = $lang['Login'];
		$l_login_logout2 = $lang['Login'];

		$s_last_visit = '';
		$icon_pm = $images['pm_no_new_msg'];
		$l_privmsgs_text = $lang['Login_check_pm'];
		$l_privmsgs_text_unread = '';
		$s_privmsg_new = 0;
	}
	else
	{
		if (!empty($userdata['user_popup_pm']))
		{
			$template->assign_block_vars('switch_enable_pm_popup', array());
		}

		$u_login_logout = CMS_PAGE_LOGIN . '?logout=true&amp;sid=' . $userdata['session_id'];
		$l_login_logout = $lang['Logout'] . ' (' . $userdata['username'] . ') ';
		$l_login_logout2 = $lang['Logout'];
		$s_last_visit = create_date($config['default_dateformat'], $userdata['user_lastvisit'], $config['board_timezone']);

		// DOWNLOADS ADV - BEGIN
		//@include(IP_ROOT_PATH . DL_PLUGIN_PATH . 'dl_page_header_inc.' . PHP_EXT);
		// DOWNLOADS ADV - END

		// Obtain number of new private messages
		if (empty($gen_simple_header))
		{

			// Birthday - BEGIN
			// see if user has or have had birthday, also see if greeting are enabled
			if (($userdata['user_birthday'] != 999999) && $config['birthday_greeting'] && (create_date('Ymd', time(), $config['board_timezone']) >= $userdata['user_next_birthday_greeting'] . realdate('md', $userdata['user_birthday'])))
			{
				// Birthday PM - BEGIN
				$pm_subject = $lang['Greeting_Messaging'];
				$pm_date = gmdate('U');

				$year = create_date('Y', time(), $config['board_timezone']);
				$date_today = create_date('Ymd', time(), $config['board_timezone']);
				$user_birthday = realdate('md', $userdata['user_birthday']);
				$user_birthday2 = (($year . $user_birthday < $date_today) ? ($year + 1) : $year) . $user_birthday;

				$user_age = create_date('Y', time(), $config['board_timezone']) - realdate('Y', $userdata['user_birthday']);
				if (create_date('md', time(), $config['board_timezone']) < realdate('md', $userdata['user_birthday']))
				{
					$user_age--;
				}

				$pm_text = ($user_birthday2 == $date_today) ? sprintf($lang['Birthday_greeting_today'], $user_age) : sprintf($lang['Birthday_greeting_prev'], $user_age, realdate(str_replace('Y', '', $lang['DATE_FORMAT_BIRTHDAY']), $userdata['user_birthday']) . ((!empty($userdata['user_next_birthday_greeting']) ? ($userdata['user_next_birthday_greeting']) : '')));

				$founder_id = (defined('FOUNDER_ID') ? FOUNDER_ID : get_founder_id());

				include_once(IP_ROOT_PATH . 'includes/class_pm.' . PHP_EXT);
				$privmsg_subject = sprintf($pm_subject, $config['sitename']);
				$privmsg_message = sprintf($pm_text, $config['sitename'], $config['sitename']);
				$privmsg_sender = $founder_id;
				$privmsg_recipient = $userdata['user_id'];

				$privmsg = new class_pm();
				$privmsg->delete_older_message('PM_INBOX', $privmsg_recipient);
				$privmsg->send($privmsg_sender, $privmsg_recipient, $privmsg_subject, $privmsg_message);
				unset($privmsg);
				// Birthday PM - END

				$sql = "UPDATE " . USERS_TABLE . "
					SET user_next_birthday_greeting = " . (create_date('Y', time(), $config['board_timezone']) + 1) . "
					WHERE user_id = " . $userdata['user_id'];
				$status = $db->sql_query($sql);
			} //Sorry user shall not have a greeting this year
			// Birthday - END

			if ($userdata['user_profile_view'] && $userdata['user_profile_view_popup'])
			{
				$template->assign_vars(array(
					'PROFILE_VIEW' => true,
					'U_PROFILE_VIEW' => append_sid('profile_view_popup.' . PHP_EXT),
					)
				);
			}

			if ($userdata['user_new_privmsg'] && !$config['privmsg_disable'])
			{
				$l_message_new = ($userdata['user_new_privmsg'] == 1) ? $lang['New_pm'] : $lang['New_pms'];
				$l_privmsgs_text = sprintf($l_message_new, $userdata['user_new_privmsg']);

				if ($userdata['user_last_privmsg'] > $userdata['user_lastvisit'])
				{
					$sql = "UPDATE " . USERS_TABLE . "
						SET user_last_privmsg = '" . $userdata['user_lastvisit'] . "'
						WHERE user_id = " . $userdata['user_id'];
					$db->sql_query($sql);
					$s_privmsg_new = 1;
					$icon_pm = $images['pm_new_msg'];
				}
				else
				{
					$s_privmsg_new = 0;
					$icon_pm = $images['pm_new_msg'];
				}
			}
			else
			{
				$l_privmsgs_text = $lang['No_new_pm'];
				$s_privmsg_new = 0;
				$icon_pm = $images['pm_no_new_msg'];
			}

			if ($userdata['user_unread_privmsg'])
			{
				$l_message_unread = ($userdata['user_unread_privmsg'] == 1) ? $lang['Unread_pm'] : $lang['Unread_pms'];
				$l_privmsgs_text_unread = sprintf($l_message_unread, $userdata['user_unread_privmsg']);
			}
			else
			{
				$l_privmsgs_text_unread = $lang['No_unread_pm'];
			}
		}
		else
		{
			$icon_pm = $images['pm_no_new_msg'];
			$l_privmsgs_text = $lang['Login_check_pm'];
			$l_privmsgs_text_unread = '';
			$s_privmsg_new = 0;
		}

		// We don't want this SQL being too expensive... so we will allow the number of new messages only for users which log on frequently
		if ($config['enable_new_messages_number'] && ($userdata['user_lastvisit'] > (time() - (LAST_LOGIN_DAYS_NEW_POSTS_RESET * 60 * 60 * 24))))
		{
			$sql = "SELECT COUNT(post_id) as total
				FROM " . POSTS_TABLE . "
				WHERE post_time >= " . $userdata['user_lastvisit'] . "
				AND poster_id != " . $userdata['user_id'];
			$db->sql_return_on_error(true);
			$result = $db->sql_query($sql);
			$db->sql_return_on_error(false);
			if ($result)
			{
				$row = $db->sql_fetchrow($result);
				$lang['Search_new'] = $lang['Search_new'] . ' (' . $row['total'] . ')';
				$lang['New'] = $lang['New'] . ' (' . $row['total'] . ')';
				$lang['New2'] = $lang['New_Label'] . ' (' . $row['total'] . ')';
				$lang['New3'] = $lang['New_Messages_Label'] . ' (' . $row['total'] . ')';
				$lang['Search_new2'] = $lang['Search_new2'] . ' (' . $row['total'] . ')';
				$lang['Search_new_p'] = $lang['Search_new_p'] . ' (' . $row['total'] . ')';
				$db->sql_freeresult($result);
			}
		}
		else
		{
			$lang['New2'] = $lang['New_Label'];
			$lang['New3'] = $lang['New_Messages_Label'];
		}
	}
	// LOGGED IN CHECK - END

	if (!defined('IN_CMS'))
	{
		//<!-- BEGIN Unread Post Information to Database Mod -->
		$upi2db_first_use = '';
		$u_display_new = array();
		if($userdata['upi2db_access'])
		{
			$unread = unread();
			$u_display_new = index_display_new($unread);
			$template->assign_block_vars('switch_upi2db_on', array());
			$upi2db_first_use = ($userdata['user_upi2db_datasync'] == '0') ? ('<script type="text/javascript">' . "\n" . '// <![CDATA[' . "\n" . 'alert ("' . $lang['upi2db_first_use_txt'] . '");' . "\n" . '// ]]>' . "\n" . '</script>') : '';
		}
		else
		{
			if ($userdata['session_logged_in'])
			{
				$template->assign_block_vars('switch_upi2db_off', array());
			}
		}
		//<!-- END Unread Post Information to Database Mod -->

		// Digests - BEGIN
		if ($config['enable_digests'])
		{
			if (!defined('DIGEST_SITE_URL'))
			{
				$digest_server_url = create_server_url();
				define('DIGEST_SITE_URL', $digest_server_url);
			}
			@include(IP_ROOT_PATH . 'language/lang_' . $config['default_lang'] . '/lang_digests.' . PHP_EXT);
			if ($userdata['session_logged_in'])
			{
				$template->assign_block_vars('switch_show_digests', array());
			}

			// DIGESTS TEMP CODE - BEGIN
			// MG PHP Cron Emulation For Digests - BEGIN
			$is_allowed = true;
			// If you want to assign the extra SQL charge to non registered users only, decomment this line... ;-)
			$is_allowed = (!$userdata['session_logged_in']) ? true : false;
			$digests_pages_array = array(CMS_PAGE_PROFILE, CMS_PAGE_POSTING);
			if ($config['digests_php_cron'] && $is_allowed && !in_array($page_url['basename'], $digests_pages_array))
			//if ($config['digests_php_cron'] && ($config['digests_php_cron_lock'] == false) && (!$userdata['session_logged_in']) && !in_array($page_url['basename'], $digests_pages_array))
			{
				if ((time() - $config['digests_last_send_time']) > CRON_REFRESH)
				{
					$config['digests_last_send_time'] = ($config['digests_last_send_time'] == 0) ? (time() - 3600) : $config['digests_last_send_time'];
					$last_send_time = @getdate($config['digests_last_send_time']);
					$cur_time = @getdate();
					if ($cur_time['hours'] <> $last_send_time['hours'])
					{
						set_config('digests_php_cron_lock', 1);
						define('PHP_DIGESTS_CRON', true);
						include_once(IP_ROOT_PATH . 'mail_digests.' . PHP_EXT);
					}
				}
			}
			// MG PHP Cron Emulation For Digests - END
			// DIGESTS TEMP CODE - END
		}
		// Digests - END

		// Visit Counter - BEGIN
		if ($config['visit_counter_switch'])
		{
			$sql = "UPDATE " . CONFIG_TABLE . "
					SET config_value = (config_value + 1)
					WHERE config_name = 'visit_counter'";
			$result = $db->sql_query($sql);
		}
		// Visit Counter - END

		// Mighty Gorgon - Random Quote - Begin
		$randomquote_phrase = '';
		if ($config['show_random_quote'])
		{
			@include_once(IP_ROOT_PATH . 'language/lang_' . $config['default_lang'] . '/lang_randomquote.' . PHP_EXT);
			$randomquote_phrase = $randomquote[rand(0, sizeof($randomquote) - 1)];
		}
		// Mighty Gorgon - Random Quote - End

		// Mighty Gorgon - Advanced Switches - END

		// Show Online Block - BEGIN
		// Get basic (usernames + totals) online situation
		$online_userlist = '';
		$l_online_users = '';
		$ac_online_text = '';
		$ac_username_lists = '';
		if (defined('SHOW_ONLINE') && !$userdata['is_bot'])
		{
			include(IP_ROOT_PATH . 'includes/users_online_block.' . PHP_EXT);
		}
		// Show Online Block - END

		// CrackerTracker v5.x
		/*
		 * CrackerTracker IP Range Scanner
		 */
		if (isset($_GET['marknow']) && ($_GET['marknow'] == 'ipfeature') && $userdata['session_logged_in'])
		{
			// Mark IP Feature Read
			$userdata['ct_last_ip'] = $userdata['ct_last_used_ip'];
			$sql = 'UPDATE ' . USERS_TABLE . ' SET ct_last_ip = ct_last_used_ip WHERE user_id=' . $userdata['user_id'];
			$result = $db->sql_query($sql);

			if (!empty($_SERVER['HTTP_REFERER']))
			{
				preg_match('#/([^/]*?)$#', $_SERVER['HTTP_REFERER'], $backlink);
				redirect($backlink[1]);
			}
		}

		if (!empty($ctracker_config) && ($ctracker_config->settings['login_ip_check'] == 1) && ($userdata['ct_enable_ip_warn'] == 1) && $userdata['session_logged_in'])
		{
			include_once(IP_ROOT_PATH . '/ctracker/classes/class_ct_userfunctions.' . PHP_EXT);
			$ctracker_user = new ct_userfunctions();
			$check_ip_range = $ctracker_user->check_ip_range();

			if ($check_ip_range != 'allclear')
			{
				$template->assign_block_vars('ctracker_message', array(
					'ROW_COLOR' => 'ffdfdf',
					'ICON_GLOB' => $images['ctracker_note'],
					'L_MESSAGE_TEXT' => $check_ip_range,
					'L_MARK_MESSAGE' => $lang['ctracker_gmb_markip'],
					'U_MARK_MESSAGE' => append_sid('index.' . PHP_EXT . '?marknow=ipfeature')
					)
				);
			}
		}

		/*
		* CrackerTracker Global Message Function
		*/
		if (isset($_GET['marknow']) && ($_GET['marknow'] == 'globmsg') && $userdata['session_logged_in'])
		{
			// Mark Global Message as read
			$userdata['ct_global_msg_read'] = 0;
			$sql = 'UPDATE ' . USERS_TABLE . ' SET ct_global_msg_read = 0 WHERE user_id=' . $userdata['user_id'];
			$result = $db->sql_query($sql);

			if (!empty($_SERVER['HTTP_REFERER']))
			{
				preg_match('#/([^/]*?)$#', $_SERVER['HTTP_REFERER'], $backlink);
				redirect($backlink[1]);
			}
		}

		if (!empty($ctracker_config) && ($userdata['ct_global_msg_read'] == 1) && $userdata['session_logged_in'] && ($ctracker_config->settings['global_message'] != ''))
		{
			// Output Global Message
			$global_message_output = '';

			if ($ctracker_config->settings['global_message_type'] == 1)
			{
				$global_message_output = $ctracker_config->settings['global_message'];
			}
			else
			{
				$global_message_output = sprintf($lang['ctracker_gmb_link'], $ctracker_config->settings['global_message'], $ctracker_config->settings['global_message']);
			}

			$template->assign_block_vars('ctracker_message', array(
				'ROW_COLOR' => 'e1ffdf',
				'ICON_GLOB' => $images['ctracker_note'],
				'L_MESSAGE_TEXT' => $global_message_output,
				'L_MARK_MESSAGE' => $lang['ctracker_gmb_mark'],
				'U_MARK_MESSAGE' => append_sid('index.' . PHP_EXT . '?marknow=globmsg')
				)
			);
		}

		if (!empty($ctracker_config) && ((($ctracker_config->settings['login_history'] == 1) || ($ctracker_config->settings['login_ip_check'] == 1)) && ($userdata['session_logged_in'])))
		{
			$template->assign_block_vars('login_sec_link', array());
		}

		/*
		* CrackerTracker Password Expirement Check
		*/
		if ($userdata['session_logged_in'] && ($ctracker_config->settings['pw_control'] == 1))
		{
			if (time() > $userdata['ct_last_pw_reset'])
			{
				$template->assign_block_vars('ctracker_message', array(
					'ROW_COLOR' => 'ffdfdf',
					'ICON_GLOB' => $images['ctracker_note'],
					'L_MESSAGE_TEXT' => sprintf($lang['ctracker_info_pw_expired'], $ctracker_config->settings['pw_validity'], $userdata['user_id']),
					'L_MARK_MESSAGE' => '',
					'U_MARK_MESSAGE' => ''
					)
				);
			}
		}
		/*
		* CrackerTracker Debug Mode Check
		*/
		if (defined('CT_DEBUG_MODE') && (CT_DEBUG_MODE === true) && ($userdata['user_level'] == ADMIN))
		{
			$template->assign_block_vars('ctracker_message', array(
				'ROW_COLOR' => 'ffdfdf',
				'ICON_GLOB' => $images['ctracker_note'],
				'L_MESSAGE_TEXT' => $lang['ctracker_dbg_mode'],
				'L_MARK_MESSAGE' => '',
				'U_MARK_MESSAGE' => ''
				)
			);
		}
		// CrackerTracker v5.x

		if ($config['switch_header_table'])
		{
			$template->assign_block_vars('switch_header_table', array(
				'HEADER_TEXT' => $config['header_table_text'],
				'L_STAFF_MESSAGE' => $lang['staff_message'],
				)
			);
		}

		if ($config['show_calendar_box_index'])
		{
			$path_parts = pathinfo($_SERVER['PHP_SELF']);
			if ($path_parts['basename'] != CMS_PAGE_LOGIN)
			{
				if (!defined('IN_CALENDAR'))
				{
					if (intval($config['calendar_header_cells']) > 0)
					{
						$template->assign_block_vars('switch_calendar_box', array());
						include_once(IP_ROOT_PATH . 'includes/functions_calendar.' . PHP_EXT);
						display_calendar('CALENDAR_BOX', intval($config['calendar_header_cells']));
					}
				}
			}
		}

		$top_html_block_text = get_ad('glt');
		$header_banner_text = get_ad('glh');
		$nav_menu_ads_top = get_ad('nmt');
		$nav_menu_ads_bottom = get_ad('nmb');

		// The following assigns all _common_ variables that may be used at any point in a template.
		$template->assign_vars(array(
			'TOTAL_USERS_ONLINE' => $l_online_users,
			'LOGGED_IN_USER_LIST' => $online_userlist,
			'BOT_LIST' => !empty($online_botlist) ? $online_botlist : '',
			'AC_LIST_TEXT' => $ac_online_text,
			'AC_LIST' => $ac_username_lists,
			'RECORD_USERS' => sprintf($lang['Record_online_users'], $config['record_online_users'], create_date($config['default_dateformat'], $config['record_online_date'], $config['board_timezone'])),

		//<!-- BEGIN Unread Post Information to Database Mod -->
			'UPI2DB_FIRST_USE' => $upi2db_first_use,
		//<!-- END Unread Post Information to Database Mod -->

			'TOP_HTML_BLOCK' => $top_html_block_text,
			'HEADER_BANNER_CODE' => $header_banner_text,
			'NAV_MENU_ADS_TOP' => $nav_menu_ads_top,
			'NAV_MENU_ADS_BOTTOM' => $nav_menu_ads_bottom,

			'L_SEARCH_NEW' => $lang['Search_new'],
			'L_SEARCH_NEW2' => $lang['Search_new2'],
			'L_NEW' => $lang['New'],
			'L_NEW2' => (empty($lang['New2']) ? $lang['New_Label'] : $lang['New2']),
			'L_NEW3' => (empty($lang['New3']) ? $lang['New_Messages_Label'] : $lang['New3']),
			'L_POSTS' => $lang['Posts'],
		//<!-- BEGIN Unread Post Information to Database Mod -->
			'L_DISPLAY_ALL' => (!empty($u_display_new) ? $u_display_new['all'] : ''),
			'L_DISPLAY_U' => (!empty($u_display_new) ? $u_display_new['u'] : ''),
			'L_DISPLAY_M' => (!empty($u_display_new) ? $u_display_new['m'] : ''),
			'L_DISPLAY_P' => (!empty($u_display_new) ? $u_display_new['p'] : ''),
			'L_DISPLAY_UNREAD' => (!empty($u_display_new) ? $u_display_new['unread'] : ''),
			'L_DISPLAY_MARKED' => (!empty($u_display_new) ? $u_display_new['marked'] : ''),
			'L_DISPLAY_PERMANENT' => (!empty($u_display_new) ? $u_display_new['permanent'] : ''),

			'L_DISPLAY_U_S' => (!empty($u_display_new) ? $u_display_new['u_string_full'] : ''),
			'L_DISPLAY_M_S' => (!empty($u_display_new) ? $u_display_new['m_string_full'] : ''),
			'L_DISPLAY_P_S' => (!empty($u_display_new) ? $u_display_new['p_string_full'] : ''),
			'L_DISPLAY_UNREAD_S' => (!empty($u_display_new) ? $u_display_new['unread_string'] : ''),
			'L_DISPLAY_MARKED_S' => (!empty($u_display_new) ? $u_display_new['marked_string'] : ''),
			'L_DISPLAY_PERMANENT_S' => (!empty($u_display_new) ? $u_display_new['permanent_string'] : ''),
			'U_DISPLAY_U' => (!empty($u_display_new) ? $u_display_new['u_url'] : ''),
			'U_DISPLAY_M' => (!empty($u_display_new) ? $u_display_new['m_url'] : ''),
			'U_DISPLAY_P' => (!empty($u_display_new) ? $u_display_new['p_url'] : ''),
		//<!-- END Unread Post Information to Database Mod -->
			'L_SEARCH_UNANSWERED' => $lang['Search_unanswered'],
			'L_SEARCH_SELF' => $lang['Search_your_posts'],
			'L_RECENT' => $lang['Recent_topics'],
			'L_WATCHED_TOPICS' => $lang['Watched_Topics'],
			'L_BOOKMARKS' => $lang['Bookmarks'],
			'L_DIGESTS' => $lang['Digests'],
			'L_DRAFTS' => $lang['Drafts'],

			// Mighty Gorgon - Random Quote - Begin
			'RANDOM_QUOTE' => $randomquote_phrase,
			// Mighty Gorgon - Random Quote - End

			// CrackerTracker v5.x
			'L_LOGIN_SEC' => $lang['ctracker_gmb_loginlink'],
			'U_LOGIN_SEC' => append_sid('ct_login_history.' . PHP_EXT),
			// CrackerTracker v5.x

			// Mighty Gorgon - CPL - BEGIN
			'L_VIEWER' => $lang['Username'],
			'L_NUMBER' => $lang['Views'],
			'L_STAMP' => $lang['Last_updated'],
			'L_YOUR_ACTIVITY' => $lang['Cpl_Personal_Profile'],
			'L_PROFILE_EXPLAIN' => $lang['profile_explain'],
			'L_PROFILE_MAIN' => $lang['profile_main'],

			'L_CPL_NAV' => $lang['Profile'],
			'L_CPL_REG_INFO' => $lang['Registration_info'],
			'L_CPL_DELETE_ACCOUNT' => $lang['Delete_My_Account'],
			'L_CPL_PROFILE_INFO' => $lang['Profile_info'],
			'L_CPL_PROFILE_VIEWED' => $lang['Profile_viewed'],
			'L_CPL_AVATAR_PANEL' => $lang['Avatar_panel'],
			'L_CPL_SIG_EDIT' => $lang['sig_edit_link'],
			'L_CPL_PREFERENCES' => $lang['Preferences'],
			'L_CPL_SETTINGS_OPTIONS' => $lang['Cpl_Settings_Options'],
			'L_CPL_BOARD_SETTINGS' => $lang['Cpl_Board_Settings'],
			'L_CPL_MORE_INFO' => $lang['Cpl_More_info'],
			'L_CPL_NEWMSG' => $lang['Cpl_NewMSG'],
			'L_CPL_PERSONAL_PROFILE' => $lang['Cpl_Personal_Profile'],
			'L_CPL_OWN_POSTS' => $lang['Search_your_posts'],
			'L_CPL_OWN_PICTURES' => $lang['Personal_Gallery'],
			'L_CPL_BOOKMARKS' => $lang['Bookmarks'],
			'L_CPL_SUBSCFORUMS' => $lang['UCP_SubscForums'],
			'L_CPL_PRIVATE_MESSAGES' => $lang['Private_Messages'],
			'L_CPL_INBOX' => $lang['Inbox'],
			'L_CPL_OUTBOX' => $lang['Outbox'],
			'L_CPL_SAVEBOX' => $lang['Savebox'],
			'L_CPL_SENTBOX' => $lang['Sentbox'],
			'L_CPL_DRAFTS' => $lang['Drafts'],
			'L_CPL_ZEBRA' => $lang['UCP_ZEBRA'],

			'L_CPL_ZEBRA_EXPLAIN' => $lang['FRIENDS_EXPLAIN'],

			'U_CPL_PROFILE_VIEWED' => append_sid('profile_view_user.' . PHP_EXT . '?' . POST_USERS_URL . '=' . $userdata['user_id']),
			'U_CPL_NEWMSG' => append_sid('privmsg.' . PHP_EXT . '?mode=post'),
			'U_CPL_REGISTRATION_INFO' => append_sid(CMS_PAGE_PROFILE . '?mode=editprofile&amp;cpl_mode=reg_info'),
			'U_CPL_DELETE_ACCOUNT' => append_sid('contact_us.' . PHP_EXT . '?account_delete=' . $userdata['user_id']),
			'U_CPL_PROFILE_INFO' => append_sid(CMS_PAGE_PROFILE . '?mode=editprofile&amp;cpl_mode=profile_info'),
			'U_CPL_PREFERENCES' => append_sid(CMS_PAGE_PROFILE . '?mode=editprofile&amp;cpl_mode=preferences'),
			'U_CPL_BOARD_SETTINGS' => append_sid(CMS_PAGE_PROFILE . '?mode=editprofile&amp;cpl_mode=board_settings'),
			'U_CPL_AVATAR_PANEL' => append_sid(CMS_PAGE_PROFILE . '?mode=editprofile&amp;cpl_mode=avatar'),
			'U_CPL_SIGNATURE' => append_sid(CMS_PAGE_PROFILE . '?mode=signature'),
			'U_CPL_OWN_POSTS' => append_sid(CMS_PAGE_SEARCH. '?search_author=' . urlencode($userdata['username']) . '&amp;showresults=posts'),
			'U_CPL_OWN_PICTURES' => append_sid('album.' . PHP_EXT . '?user_id=' . $userdata['user_id']),
			'U_CPL_CALENDAR_SETTINGS' => append_sid('profile_options.' . PHP_EXT . '?sub=preferences&amp;mod=1&amp;' . POST_USERS_URL . '=' . $userdata['user_id']),
			'U_CPL_SUBFORUM_SETTINGS' => append_sid('profile_options.' . PHP_EXT . '?sub=preferences&amp;mod=0&amp;' . POST_USERS_URL . '=' . $userdata['user_id']),
			'U_CPL_SUBSCFORUMS' => append_sid('subsc_forums.' . PHP_EXT),
			'U_CPL_BOOKMARKS' => append_sid(CMS_PAGE_SEARCH . '?search_id=bookmarks'),
			'U_CPL_INBOX' => append_sid('privmsg.' . PHP_EXT . '?folder=inbox'),
			'U_CPL_OUTBOX' => append_sid('privmsg.' . PHP_EXT . '?folder=outbox'),
			'U_CPL_SAVEBOX' => append_sid('privmsg.' . PHP_EXT . '?folder=savebox'),
			'U_CPL_SENTBOX' => append_sid('privmsg.' . PHP_EXT . '?folder=sentbox'),
			'U_CPL_DRAFTS' => append_sid('drafts.' . PHP_EXT),
			'U_CPL_ZEBRA' => append_sid(CMS_PAGE_PROFILE . '?mode=zebra&amp;zmode=friends'),
			// Mighty Gorgon - CPL - END

			// Activity - BEGIN
			/*
			'L_WHOSONLINE_GAMES' => '<a href="'. append_sid('activity.' . PHP_EXT) .'"><span style="color:#'. str_replace('#', '', $config['ina_online_list_color']) . ';">' . $config['ina_online_list_text'] . '</span></a>',
			*/
			'P_ACTIVITY_MOD_PATH' => ACTIVITY_PLUGIN_PATH,
			'U_ACTIVITY' => append_sid('activity.' . PHP_EXT),
			'L_ACTIVITY' => $lang['Activity'],
			// Activity - END
			)
		);
	}

	// The following assigns all _common_ variables that may be used at any point in a template.
	$template->assign_vars(array(
		'DOCTYPE_HTML' => $doctype_html,
		'NAV_SEP' => $lang['Nav_Separator'],
		'NAV_DOT' => '&#8226;',
		'NAV_LINKS' => $nav_links_html,

		'U_LOGIN_LOGOUT' => append_sid(IP_ROOT_PATH . $u_login_logout),

		'L_PAGE_TITLE' => $meta_content['page_title_clean'],
		'PAGE_TITLE' => ($config['page_title_simple'] ? $meta_content['page_title_clean'] : $meta_content['page_title']),
		'META_TAG' => $phpbb_meta,
		'LAST_VISIT_DATE' => sprintf($lang['You_last_visit'], $s_last_visit),
		'CURRENT_TIME' => sprintf($lang['Current_time'], create_date($config['default_dateformat'], time(), $config['board_timezone'])),
		'S_TIMEZONE' => $time_message,

		'PRIVATE_MESSAGE_INFO' => $l_privmsgs_text,
		'PRIVATE_MESSAGE_INFO_UNREAD' => $l_privmsgs_text_unread,
		'PRIVATE_MESSAGE_NEW_FLAG' => $s_privmsg_new,
		'PRIVMSG_IMG' => $icon_pm,

		'L_USERNAME' => $lang['Username'],
		'L_PASSWORD' => $lang['Password'],
		'L_LOGIN_LOGOUT' => $l_login_logout,
		'L_LOGIN_LOGOUT2' => $l_login_logout2,
		'L_LOGIN' => $lang['Login'],
		'L_HOME' => $lang['Home'],
		'L_INDEX' => sprintf($lang['Forum_Index'], htmlspecialchars($config['sitename'])),
		'L_REGISTER' => $lang['Register'],
		'L_BOARDRULES' => $lang['BoardRules'],
		'L_PROFILE' => $lang['Profile'],
		'L_CPL_NAV' => $lang['Profile'],
		'L_SEARCH' => $lang['Search'],
		'L_PRIVATEMSGS' => $lang['Private_Messages'],
		'L_WHO_IS_ONLINE' => $lang['Who_is_Online'],
		'L_MEMBERLIST' => $lang['Memberlist'],
		'L_FAQ' => $lang['FAQ'],
		'L_REFERRERS' => $lang['Referrers'],
		'L_ADV_SEARCH' => $lang['Adv_Search'],
		'L_SEARCH_EXPLAIN' => $lang['Search_Explain'],

		'L_KB' => $lang['KB_title'],
		'L_NEWS' => $lang['News_Cmx'],
		'L_USERGROUPS' => $lang['Usergroups'],
		'L_BOARD_DISABLE' => $lang['Board_disabled'],

		// AJAX Features - BEGIN
		'S_AJAX_USER_CHECK' => $ajax_user_check,
		'S_AJAX_USER_CHECK_ALT' => $ajax_user_check_alt,
		// AJAX Features - END

		// Ajax Shoutbox - BEGIN
		'L_AJAX_SHOUTBOX' => $lang['Ajax_Chat'],
		// Ajax Shoutbox - END

		'L_BACK_TOP' => $lang['Back_to_top'],
		'L_BACK_BOTTOM' => $lang['Back_to_bottom'],

		// Mighty Gorgon - Nav Links - BEGIN
		'L_CALENDAR' => $lang['Calendar'],
		'L_DOWNLOADS' => $lang['Downloads'],
		'L_DOWNLOADS_ADV' => $lang['Downloads_ADV'],
		'L_HACKS_LIST' => $lang['Hacks_List'],
		'L_SUDOKU' => $lang['Sudoku'],
		'L_AVATAR_GEN' => $lang['AvatarGenerator'],
		'L_LINKS' => $lang['Links'],
		'L_WORDGRAPH' => $lang['Wordgraph'],
		'L_ACRONYMS' => $lang['Acronyms'],
		'L_SITEMAP' => $lang['Sitemap'],
		//'L_' => $lang[''],
		// Mighty Gorgon - Nav Links - END
		// Mighty Gorgon - Multiple Ranks - BEGIN
		'L_RANKS' => $lang['Rank_Header'],
		'L_STAFF' => $lang['Staff'],
		// Mighty Gorgon - Multiple Ranks - END
		//'U_STAFF' => append_sid('staff.' . PHP_EXT),
		'L_CONTACT_US' => $lang['Contact_us'],
		'L_UPLOAD_IMAGE' => $lang['Upload_Image_Local'],
		'L_UPLOADED_IMAGES' => $lang['Uploaded_Images_Local'],
		// Mighty Gorgon - Full Album Pack - BEGIN
		'L_ALBUM' => $lang['Album'],
		'L_PIC_NAME' => $lang['Pic_Name'],
		'L_DESCRIPTION' => $lang['Description'],
		'L_GO' => $lang['Go'],
		'L_SEARCH_CONTENTS' => $lang['Search_Contents'],
		'L_SEARCH_MATCHES' => $lang['Search_Matches'],
		// Mighty Gorgon - Full Album Pack - END

		'U_PREFERENCES' => append_sid('profile_options.' . PHP_EXT),
		'L_PREFERENCES' => $lang['Preferences'],
		)
	);

	// get the nav sentence
	$nav_key = '';
	$nav_key = (!empty($meta_content['cat_id']) ? (POST_CAT_URL . $meta_content['cat_id']) : $nav_key);
	$nav_key = (!empty($meta_content['forum_id']) ? (POST_FORUM_URL . $meta_content['forum_id']) : $nav_key);
	$nav_key = (!empty($meta_content['topic_id']) ? (POST_TOPIC_URL . $meta_content['topic_id']) : $nav_key);
	$nav_key = (!empty($meta_content['post_id']) ? (POST_POST_URL . $meta_content['post_id']) : $nav_key);
	if (empty($nav_key))
	{
		$selected_id = request_var('selected_id', 0);
		$nav_key = ($selected_id < 0) ? 0 : $selected_id;
		$nav_key = empty($nav_key) ? 'Root' : $nav_key;
	}

	$nav_separator = empty($nav_separator) ? (empty($lang['Nav_Separator']) ? '&nbsp;&raquo;&nbsp;' : $lang['Nav_Separator']) : $nav_separator;
	$nav_cat_desc = '';
	if (!isset($skip_nav_cat))
	{
		$nav_pgm = empty($nav_pgm) ? '' : $nav_pgm;
		$nav_cat_desc = make_cat_nav_tree($nav_key, $nav_pgm, $meta_content);
	}

	if (!empty($nav_cat_desc))
	{
		$nav_server_url = create_server_url();
		$nav_cat_desc = $nav_separator . $nav_cat_desc;
		$breadcrumbs_address = $nav_separator . '<a href="' . $nav_server_url . append_sid(CMS_PAGE_FORUM) . '">' . $lang['Forum'] . '</a>' . $nav_cat_desc;
		if (isset($nav_add_page_title) && ($nav_add_page_title == true))
		{
			$breadcrumbs_address = $breadcrumbs_address . $nav_separator . '<a href="#" class="nav-current">' . $meta_content['page_title'] . '</a>';
		}
	}

	// send to template
	$template->assign_vars(array(
		//'SPACER' => $images['spacer'],
		'S_PAGE_NAV' => (isset($cms_page['page_nav']) ? $cms_page['page_nav'] : true),
		'NAV_SEPARATOR' => $nav_separator,
		'NAV_CAT_DESC' => $nav_cat_desc,
		'BREADCRUMBS_ADDRESS' => (empty($breadcrumbs_address) ? (($meta_content['page_title_clean'] != htmlspecialchars($config['sitename'])) ? ($lang['Nav_Separator'] . '<a href="#" class="nav-current">' . $meta_content['page_title_clean'] . '</a>') : '') : $breadcrumbs_address),
		'S_BREADCRUMBS_LINKS_LEFT' => (empty($breadcrumbs_links_left) ? false : true),
		'BREADCRUMBS_LINKS_LEFT' => (empty($breadcrumbs_links_left) ? false : $breadcrumbs_links_left),
		'S_BREADCRUMBS_LINKS_RIGHT' => (empty($breadcrumbs_links_right) ? false : true),
		'BREADCRUMBS_LINKS_RIGHT' => (empty($breadcrumbs_links_right) ? '&nbsp;' : $breadcrumbs_links_right),
		)
	);

	// Mighty Gorgon - CMS IMAGES - BEGIN
	if (defined('IN_CMS'))
	{
		$template->assign_vars(array(
			'IMG_LAYOUT_BLOCKS_EDIT' => $images['layout_blocks_edit'],
			'IMG_LAYOUT_PREVIEW' => $images['layout_preview'],
			'IMG_BLOCK_EDIT' => $images['block_edit'],
			'IMG_BLOCK_DELETE' => $images['block_delete'],
			'IMG_CMS_ARROW_UP' => $images['arrows_cms_up'],
			'IMG_CMS_ARROW_DOWN' => $images['arrows_cms_down'],
			)
		);
	}
	// Mighty Gorgon - CMS IMAGES - END

	if ($config['board_disable'] && ($userdata['user_level'] == ADMIN))
	{
		$template->assign_block_vars('switch_admin_disable_board', array());
	}

	if (!defined('IN_CMS'))
	{
		$cms_page['global_blocks'] = (empty($cms_page['global_blocks']) ? false : true);
		//$cms_page['global_blocks'] = ((!isset($cms_page['page_id']) || !$cms_page['global_blocks']) ? false : true);
		$cms_page_blocks = ((empty($cms_page['page_id']) || empty($cms_config_layouts[$cms_page['page_id']])) ? false : true);
		if(empty($gen_simple_header) && !defined('HAS_DIED') && !defined('IN_LOGIN') && ($cms_page['global_blocks'] || $cms_page_blocks) && (!$config['board_disable'] || ($userdata['user_level'] == ADMIN)))
		{
			$template->assign_var('SWITCH_CMS_GLOBAL_BLOCKS', true);
			$ip_cms->cms_parse_blocks($cms_page['page_id'], !empty($cms_page['page_id']), $cms_page['global_blocks'], 'header');
			if ($ip_cms->cms_parse_blocks($cms_page['page_id'], !empty($cms_page['page_id']), $cms_page['global_blocks'], 'headerleft'))
			{
				$template->assign_vars(array(
					'HEADER_WIDTH' => $cms_config_vars['header_width'],
					'HL_BLOCK' => true,
					)
				);
			}
			if ($ip_cms->cms_parse_blocks($cms_page['page_id'], !empty($cms_page['page_id']), $cms_page['global_blocks'], 'headercenter'))
			{
				$template->assign_var('HC_BLOCK', true);
			}
		}

		if(empty($gen_simple_header))
		{
			if ($ip_cms->cms_parse_blocks(0, true, true, 'gheader'))
			{
				$template->assign_var('GH_BLOCK', true);
			}
			if ($ip_cms->cms_parse_blocks(0, true, true, 'ghtop'))
			{
				$template->assign_var('GT_BLOCK', true);
			}
			if ($ip_cms->cms_parse_blocks(0, true, true, 'ghbottom'))
			{
				$template->assign_var('GB_BLOCK', true);
			}
			if ($ip_cms->cms_parse_blocks(0, true, true, 'ghleft'))
			{
				$template->assign_var('GL_BLOCK', true);
			}
			if ($ip_cms->cms_parse_blocks(0, true, true, 'ghright'))
			{
				$template->assign_var('GR_BLOCK', true);
			}
		}

		if (defined('PARSE_CPL_NAV'))
		{
			$template->set_filenames(array('cpl_menu_output' => 'profile_cpl_menu.tpl'));
			$template->assign_var_from_handle('CPL_MENU_OUTPUT', 'cpl_menu_output');
		}
	}

	if (($userdata['user_level'] != ADMIN) && $config['board_disable'] && !defined('IN_ADMIN') && !defined('IN_LOGIN'))
	{
		if($config['board_disable_mess_st'])
		{
			message_die(GENERAL_MESSAGE, $config['board_disable_message']);
		}
		else
		{
			message_die(GENERAL_MESSAGE, $lang['Board_disabled']);
		}
	}

	if (!defined('AJAX_HEADERS'))
	{
		// application/xhtml+xml not used because of IE
		$header_lang = !empty($lang['HEADER_LANG']) ? $lang['HEADER_LANG'] : 'utf8';
		header('Content-type: text/html; charset=' . $header_lang);
		header('Cache-Control: private, no-cache="set-cookie"');
		header('Expires: 0');
		header('Pragma: no-cache');
	}

	if ($parse_template)
	{
		$header_tpl = empty($gen_simple_header) ? 'overall_header.tpl' : 'simple_header.tpl';
		$template->set_filenames(array('overall_header' => $header_tpl));
		$template->pparse('overall_header');
	}

	return;
}

/**
* Page Footer
*/
function page_footer($exit = true, $template_to_parse = 'body', $parse_template = false)
{
	global $db, $cache, $config, $template, $images, $theme, $userdata, $lang, $tree;
	global $table_prefix, $SID, $_SID, $user_ip;
	global $ip_cms, $cms_config_vars, $cms_config_global_blocks, $cms_config_layouts, $cms_page;
	global $ctracker_config, $session_length, $starttime, $base_memory_usage, $do_gzip_compress, $start;
	global $gen_simple_header, $meta_content, $nav_separator, $nav_links, $nav_pgm, $nav_add_page_title, $skip_nav_cat;
	global $breadcrumbs_address, $breadcrumbs_links_left, $breadcrumbs_links_right;
	global $css_include, $css_style_include, $js_include;

	if (!defined('IN_CMS'))
	{
		$cms_page['global_blocks'] = (empty($cms_page['global_blocks']) ? false : true);
		//$cms_page['global_blocks'] = ((!isset($cms_page['page_id']) || !$cms_page['global_blocks']) ? false : true);
		$cms_page_blocks = ((empty($cms_page['page_id']) || empty($cms_config_layouts[$cms_page['page_id']])) ? false : true);
		if(empty($gen_simple_header) && !defined('HAS_DIED') && !defined('IN_LOGIN') && ($cms_page['global_blocks'] || $cms_page_blocks) && (!$config['board_disable'] || ($userdata['user_level'] == ADMIN)))
		{
			$template->assign_var('SWITCH_CMS_GLOBAL_BLOCKS', true);
			if ($ip_cms->cms_parse_blocks($cms_page['page_id'], !empty($cms_page['page_id']), $cms_page['global_blocks'], 'tailcenter'))
			{
				$template->assign_var('TC_BLOCK', true);
			}
			if ($ip_cms->cms_parse_blocks($cms_page['page_id'], !empty($cms_page['page_id']), $cms_page['global_blocks'], 'tailright'))
			{
				$template->assign_vars(array(
					'FOOTER_WIDTH' => $cms_config_vars['footer_width'],
					'TR_BLOCK' => true,
					)
				);
			}
			$ip_cms->cms_parse_blocks($cms_page['page_id'], !empty($cms_page['page_id']), $cms_page['global_blocks'], 'tail');
			/*
			*/
		}

		if(empty($gen_simple_header))
		{
			if ($ip_cms->cms_parse_blocks(0, true, true, 'gfooter'))
			{
				$template->assign_var('GF_BLOCK', true);
			}
		}

		$bottom_html_block_text = get_ad('glb');
		$footer_banner_text = get_ad('glf');

		// CrackerTracker v5.x
		include_once(IP_ROOT_PATH . 'ctracker/engines/ct_footer.' . PHP_EXT);
		$output_login_status = ($userdata['ct_enable_ip_warn'] ? $lang['ctracker_ma_on'] : $lang['ctracker_ma_off']);
		// CrackerTracker v5.x

		$template->assign_vars(array(
			// CrackerTracker v5.x
			'CRACKER_TRACKER_FOOTER' => create_footer_layout($ctracker_config->settings['footer_layout']),
			'L_STATUS_LOGIN' => ($ctracker_config->settings['login_ip_check'] ? sprintf($lang['ctracker_ipwarn_info'], $output_login_status) : ''),
			// CrackerTracker v5.x
			)
		);
	}

	include_once(IP_ROOT_PATH . 'includes/functions_jr_admin.' . PHP_EXT);
	$admin_link = jr_admin_make_admin_link();

	//Begin Lo-Fi Mod
	$path_parts = pathinfo($_SERVER['PHP_SELF']);
	$lofi = '<a href="' . append_sid(IP_ROOT_PATH . $path_parts['basename'] . '?' . htmlspecialchars($_SERVER['QUERY_STRING']) . '&amp;lofi=' . (empty($_COOKIE['lofi']) ? '1' : '0')) . '">' . (empty($_COOKIE['lofi']) ? ($lang['Lofi']) : ($lang['Full_Version'])) . '</a>';
	$template->assign_vars(array(
		'L_LOFI' => $lang['Lofi'],
		'L_FULL_VERSION' => $lang['Full_Version'],
		'LOFI' => $lofi
		)
	);
	//End Lo-Fi Mod

	$template->assign_vars(array(
		'TRANSLATION_INFO' => ((isset($lang['TRANSLATION_INFO'])) && ($lang['TRANSLATION_INFO'] != '')) ? ('<br />&nbsp;' . $lang['TRANSLATION_INFO']) : (((isset($lang['TRANSLATION'])) && ($lang['TRANSLATION'] != '')) ? ('<br />&nbsp;' . $lang['TRANSLATION']) : ''),

		'BOTTOM_HTML_BLOCK' => $bottom_html_block_text,
		'FOOTER_BANNER_BLOCK' => $footer_banner_text,
		'GOOGLE_ANALYTICS' => $config['google_analytics'],

		'CMS_ACP' => (!empty($cms_acp_url) ? $cms_acp_url : ''),
		'ADMIN_LINK' => $admin_link
		)
	);

	// Mighty Gorgon - CRON - BEGIN
	if ($config['cron_global_switch'] && !defined('IN_CRON') && !defined('IN_ADMIN') && !defined('IN_CMS') && !$config['board_disable'])
	{
		$cron_time = time();
		$cron_append = '';
		//$cron_types = array('queue', 'digests', 'files', 'database', 'cache', 'sql', 'users', 'topics', 'sessions');
		$cron_types = array('files', 'database', 'cache', 'sql', 'users', 'topics');

		for ($i = 0; $i < sizeof($cron_types); $i++)
		{
			$cron_trigger = $cron_time - $config['cron_' . $cron_types[$i] . '_interval'];
			if (($config['cron_' . $cron_types[$i] . '_interval'] > 0) && ($cron_trigger > $config['cron_' . $cron_types[$i] . '_last_run']))
			{
				$cron_append .= (($cron_append == '') ? '?' : '&amp;') . $cron_types[$i] . '=1';
			}
		}

		// We can force digests as all checks are performed by the function
		$last_send_time = @getdate($config['digests_last_send_time']);
		$cur_time = @getdate();
		if ($config['enable_digests'] && $config['digests_php_cron'] && ($cur_time['hours'] <> $last_send_time['hours']))
		{
			$cron_append .= (($cron_append == '') ? '?' : '&amp;') . 'digests=1';
		}

		if (!empty($cron_append))
		{
			$template->assign_var('RUN_CRON_TASK', '<img src="' . append_sid(IP_ROOT_PATH . 'cron.' . PHP_EXT . $cron_append) . '" width="1" height="1" alt="cron" />');
		}
	}
	// Mighty Gorgon - CRON - END

	if ($config['page_gen'])
	{
		// Page generation time - BEGIN
		/* Set $page_gen_allowed to FALSE if you want only Admins to view page generation info */
		$page_gen_allowed = true;
		if (($userdata['user_level'] == ADMIN) || $page_gen_allowed)
		{
			$gzip_text = ($config['gzip_compress']) ? 'GZIP ' . $lang['Enabled']: 'GZIP ' . $lang['Disabled'];
			$debug_text = (DEBUG == true) ? $lang['Debug_On'] : $lang['Debug_Off'];
			$memory_usage_text = '';
			//$excuted_queries = $db->num_queries['total'];
			$excuted_queries = $db->num_queries['normal'];
			$mtime = microtime();
			$mtime = explode(" ", $mtime);
			$mtime = $mtime[1] + $mtime[0];
			$endtime = $mtime;
			$gentime = round(($endtime - $starttime), 4); // You can adjust the number 6
			$sql_time = round($db->sql_time, 4);

			$sql_part = round($sql_time / $gentime * 100);
			$php_part = 100 - $sql_part;

			// Mighty Gorgon - Extra Debug - BEGIN
			if (defined('DEBUG_EXTRA') && ($userdata['user_level'] == ADMIN))
			{
				if (function_exists('memory_get_usage'))
				{
					if ($memory_usage = memory_get_usage())
					{
						global $base_memory_usage;
						$memory_usage -= $base_memory_usage;
						$memory_usage = ($memory_usage >= 1048576) ? round((round($memory_usage / 1048576 * 100) / 100), 2) . ' ' . 'MB' : (($memory_usage >= 1024) ? round((round($memory_usage / 1024 * 100) / 100), 2) . ' ' . 'KB' : $memory_usage . ' ' . 'BYTES');
						$memory_usage_text = ' - ' . $lang['Memory_Usage'] . ': ' . $memory_usage;
					}
				}
				if (defined('DEBUG_EXTRA'))
				{
					$tmp_query_string = htmlspecialchars(str_replace(array('&explain=1', 'explain=1'), array('', ''), $_SERVER['QUERY_STRING']));
					$gzip_text .= ' - <a href="' . append_sid(IP_ROOT_PATH . $path_parts['basename'] . (!empty($tmp_query_string) ? ('?' . $tmp_query_string . '&amp;explain=1') : '?explain=1')) . '">Extra ' . $lang['Debug_On'] . '</a>';
				}
			}

			//if (defined('DEBUG_EXTRA') && ($userdata['user_level'] == ADMIN))
			if (defined('DEBUG_EXTRA') && !empty($_REQUEST['explain']) && ($userdata['user_level'] == ADMIN) && method_exists($db, 'sql_report'))
			{
				$db->sql_report('display');
			}
			// Mighty Gorgon - Extra Debug - END

			$template->assign_vars(array(
				'SPACER' => $images['spacer'],
				'S_GENERATION_TIME' => true,
				'PAGE_GEN_TIME' => $lang['Page_Generation_Time'] . ':',
				'GENERATION_TIME' => $gentime,
				'NUMBER_QUERIES' => $excuted_queries,
				'MEMORY_USAGE' => $memory_usage_text,
				'GZIP_TEXT' => $gzip_text,
				'SQL_QUERIES' => $lang['SQL_Queries'],
				'SQL_PART' => $sql_part,
				'PHP_PART' => $php_part,
				'DEBUG_TEXT' => $debug_text,
				)
			);

			/*
			$gen_log_file = IP_ROOT_PATH . 'cache/gen_log.txt';
			$fp = fopen ($gen_log_file, "a+");
			fwrite($fp, $gentime . "\t" . $memory_usage . "\n");
			fclose($fp);
			*/
		}
		// Page generation time - END
	}

	if ($parse_template || empty($template_to_parse))
	{
		$footer_tpl = empty($gen_simple_header) ? 'overall_footer.tpl' : 'simple_footer.tpl';
		$template->set_filenames(array('overall_footer' => $footer_tpl));
		$template->pparse('overall_footer');
	}
	else
	{
		//$template_to_parse = empty($template_to_parse) ? 'body' : $template_to_parse;
		$template->pparse($template_to_parse);
	}

	if ($exit)
	{
		garbage_collection();

		// Compress buffered output if required and send to browser

		// URL Rewrite - BEGIN
		if ($config['url_rw'] || ($config['url_rw_guests'] && ($userdata['user_id'] == ANONYMOUS)))
		{
			$contents = rewrite_urls(ob_get_contents());
		}
		else
		{
			$contents = ob_get_contents();
		}

		if(@extension_loaded('zlib') && $config['gzip_compress'])
		{
			ob_end_clean();
			ob_start('ob_gzhandler');
			echo $contents;
			ob_end_flush();
		}
		else
		{
			ob_end_clean();
			echo $contents;
		}
		// URL Rewrite - END

		exit_handler();
		exit;
	}

	return;
}

/**
* Closing the cache object and the database
*/
function garbage_collection()
{
	global $cache, $db;

	// If we are in ACP it is better to clear some files in cache to make sure all options are updated
	if (defined('IN_ADMIN') && !defined('ACP_MODULES'))
	{
		empty_cache_folders_admin();
	}

	// If we are in ACP it is better to clear some files in cache to make sure all options are updated
	if (defined('IN_CMS'))
	{
		empty_cache_folders_cms();
	}

	// Unload cache, must be done before the DB connection if closed
	if (!empty($cache))
	{
		$cache->unload();
	}

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
	global $phpbb_hook, $config;

	if (!empty($phpbb_hook) && $phpbb_hook->call_hook(__FUNCTION__))
	{
		if ($phpbb_hook->hook_return(__FUNCTION__))
		{
			return $phpbb_hook->hook_return_result(__FUNCTION__);
		}
	}

	// As a pre-caution... some setups display a blank page if the flush() is not there.
	(empty($config['gzip_compress'])) ? @flush() : @ob_flush();

	exit;
}

/**
* Full page generation
*/
function full_page_generation($page_template, $page_title = '', $page_description = '', $page_keywords = '')
{
	global $template, $meta_content;
	$meta_content['page_title'] = (!empty($page_title) ? $page_title : (!empty($meta_content['page_title']) ? $meta_content['page_title'] : ''));
	$meta_content['description'] = (!empty($page_description) ? $page_description : (!empty($meta_content['description']) ? $meta_content['description'] : ''));
	$meta_content['keywords'] = (!empty($page_keywords) ? $page_keywords : (!empty($meta_content['keywords']) ? $meta_content['keywords'] : ''));
	page_header();
	$template->set_filenames(array('body' => $page_template));
	page_footer();
}

/**
* Return a nicely formatted backtrace (parts from the php manual by diz at ysagoon dot com)
*/
function get_backtrace()
{
	$output = '<div style="font-family: monospace;">';
	$backtrace = debug_backtrace();
	$path = phpbb_realpath(IP_ROOT_PATH);

	foreach ($backtrace as $number => $trace)
	{
		// We skip the first one, because it only shows this file/function
		if ($number == 0)
		{
			continue;
		}

		// Strip the current directory from path
		if (empty($trace['file']))
		{
			$trace['file'] = '';
		}
		else
		{
			$trace['file'] = str_replace(array($path, '\\'), array('', '/'), $trace['file']);
			$trace['file'] = substr($trace['file'], 1);
		}
		$args = array();

		// If include/require/include_once is not called, do not show arguments - they may contain sensible information
		if (!in_array($trace['function'], array('include', 'require', 'include_once')))
		{
			unset($trace['args']);
		}
		else
		{
			// Path...
			if (!empty($trace['args'][0]))
			{
				$argument = htmlspecialchars($trace['args'][0]);
				$argument = str_replace(array($path, '\\'), array('', '/'), $argument);
				$argument = substr($argument, 1);
				$args[] = "'{$argument}'";
			}
		}

		$trace['class'] = (!isset($trace['class'])) ? '' : $trace['class'];
		$trace['type'] = (!isset($trace['type'])) ? '' : $trace['type'];

		$output .= '<br />';
		$output .= '<b>FILE:</b> ' . htmlspecialchars($trace['file']) . '<br />';
		$output .= '<b>LINE:</b> ' . ((!empty($trace['line'])) ? $trace['line'] : '') . '<br />';

		$output .= '<b>CALL:</b> ' . htmlspecialchars($trace['class'] . $trace['type'] . $trace['function']) . '(' . ((sizeof($args)) ? implode(', ', $args) : '') . ')<br />';
	}
	$output .= '</div>';
	return $output;
}

/**
* Error and message handler, call with trigger_error if reqd
*/
function msg_handler($errno, $msg_text, $errfile, $errline)
{
	global $config, $lang;
	global $msg_code, $msg_title, $msg_long_text;

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

	$msg_code = empty($msg_code) ? GENERAL_MESSAGE : $msg_code;

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

			if ((strpos($errfile, 'cache') === false) && (strpos($errfile, 'template.') === false))
			{
				// flush the content, else we get a white page if output buffering is on
				if ((int) @ini_get('output_buffering') === 1 || strtolower(@ini_get('output_buffering')) === 'on')
				{
					@ob_flush();
				}

				// Another quick fix for those having gzip compression enabled, but do not flush if the coder wants to catch "something". ;)
				if (!empty($config['gzip_compress']))
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
// GENERAL_MESSAGE : Use for any simple text message, eg. results of an operation, authorization failures, etc.
// GENERAL ERROR : Use for any error which occurs _AFTER_ the common.php include and session code, ie. most errors in pages/functions
// CRITICAL_MESSAGE : Used when basic config data is available but a session may not exist, eg. banned users
// CRITICAL_ERROR : Used when config data cannot be obtained, eg no database connection. Should _not_ be used in 99.5% of cases
//
function message_die($msg_code, $msg_text = '', $msg_title = '', $err_line = '', $err_file = '', $sql = '')
{
	global $db, $cache, $config, $template, $images, $theme, $userdata, $lang, $tree;
	global $table_prefix, $SID, $_SID, $user_ip;
	global $gen_simple_header, $session_length, $starttime, $base_memory_usage, $do_gzip_compress, $ctracker_config;
	global $ip_cms, $cms_config_vars, $cms_config_global_blocks, $cms_config_layouts, $cms_page;
	global $nav_separator, $nav_links;
	// Global vars needed by page header, but since we are in message_die better use default values instead of the assigned ones in case we are dying before including page_header.php
	/*
	global $meta_content;
	global $nav_pgm, $nav_add_page_title, $skip_nav_cat, $start;
	global $breadcrumbs_address, $breadcrumbs_links_left, $breadcrumbs_links_right;
	global $css_include, $css_style_include, $js_include;
	*/

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
		'sql' => htmlspecialchars($sql)
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
		if (!empty($config) && !empty($config['board_email']))
		{
			$custom_error_message = sprintf($custom_error_message, '<a href="mailto:' . $config['board_email'] . '">', '</a>');
		}
		else
		{
			$custom_error_message = sprintf($custom_error_message, '', '');
		}
		echo "<html>\n<body>\n<b>Critical Error!</b><br />\nmessage_die() was called multiple times.<br />&nbsp;<hr />";
		for($i = 0; $i < sizeof($msg_history); $i++)
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
			$debug_text .= '<br /><br />' . htmlspecialchars($sql_store);
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
			$theme = setup_style($config['default_style'], $current_default_style);
		}

		$template->assign_var('HAS_DIED', true);
		define('TPL_HAS_DIED', true);

		// Load the Page Header
		if (!defined('IN_ADMIN'))
		{
			$parse_template = defined('IN_CMS') ? false : true;
			page_header('', $parse_template);
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
			$config['default_lang'] = 'english';
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
	//if (($config['mg_log_actions'] == true) && ($msg_code == GENERAL_ERROR || $msg_code == CRITICAL_ERROR))
	if ($msg_code != GENERAL_MESSAGE)
	{
		if ($config['mg_log_actions'] || !empty($config['db_log_actions']))
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
			if (!function_exists('ip_log'))
			{
				@include(IP_ROOT_PATH . 'includes/functions_mg_log.' . PHP_EXT);
			}
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
			'HAS_DIED' => true,
			'MESSAGE_TITLE' => $msg_title,
			'MESSAGE_TEXT' => $msg_text
			)
		);

		$template->pparse('message_body');

		if (!defined('IN_ADMIN'))
		{
			$parse_template = defined('IN_CMS') ? false : true;
			page_footer(true, '', $parse_template);
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

?>