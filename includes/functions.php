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

if (!defined('STRIP'))
{
	// If we are on PHP >= 6.0.0 we do not need some code
	if (version_compare(PHP_VERSION, '6.0.0-dev', '>='))
	{
		define('STRIP', false);
	}
	else
	{
		define('STRIP', (@get_magic_quotes_gpc()) ? true : false);
	}
}

/*
* Append $SID to a url. Borrowed from phplib and modified. This is an extra routine utilised by the session code and acts as a wrapper around every single URL and form action.
* If you replace the session code you must include this routine, even if it's empty.
*/
function append_sid($url, $non_html_amp = false, $char_conversion = false, $params = false, $session_id = false)
{
	global $SID, $_SID, $_EXTRA_URL, $phpbb_hook;

	$_SID = (empty($_SID) && !empty($SID) || (!empty($SID) && ($SID != ('sid=' . $_SID)))) ? str_replace('sid=', '', $SID) : $_SID;
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

	// Developers using the hook function need to globalise the $_SID and $_EXTRA_URL on their own and also handle it appropriately.
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

/**
* Re-Apply session id after page reloads
*/
function reapply_sid($url)
{
	// Remove previously added sid
	if (strpos($url, 'sid=') !== false)
	{
		$phpEx = PHP_EXT;
		// All kind of links
		$url = preg_replace('/(\?)?(&amp;|&)?sid=[a-z0-9]+/', '', $url);
		// if the sid was the first param, make the old second as first ones
		$url = preg_replace("/$phpEx(&amp;|&)+?/", "$phpEx?", $url);
	}

	return append_sid($url);
}

/**
* Build an URL with params
*/
function ip_build_url($url, $params = false, $html_amp = false)
{
	$amp_delim = !empty($html_amp) ? '&amp;' : '&';
	$url_delim = (strpos($url, '?') === false) ? '?' : $amp_delim;

	if (!empty($params) && is_array($params))
	{
		foreach ($params as $param)
		{
			$url_delim = (strpos($url, '?') === false) ? '?' : $amp_delim;
			if (!empty($param))
			{
				$url .= $url_delim . $param;
			}
		}
	}

	return $url;
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
	$script_name = (!empty($_SERVER['SCRIPT_NAME'])) ? $_SERVER['SCRIPT_NAME'] : getenv('SCRIPT_NAME');
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
	$page_name = (substr($script_name, -1, 1) == '/') ? '' : basename($script_name);
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

	$page_full = $page_name . (($query_string) ? '?' . $query_string : '');

	// Current page from Icy Phoenix root (for example: adm/index.php?i=10&b=2)
	$page = (($page_dir) ? $page_dir . '/' : '') . $page_full;

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
	$post_topic_url = (defined('POST_TOPIC_URL') ? POST_TOPIC_URL : 't');

	$page_array += array(
		'root_script_path'	=> str_replace(' ', '%20', htmlspecialchars($root_script_path)),
		'script_path'				=> str_replace(' ', '%20', htmlspecialchars($script_path)),
		'page_dir'					=> $page_dir,
		'page_name'					=> $page_name,
		'page'							=> $page,
		'query_string'			=> $query_string,
		'forum'							=> (isset($_REQUEST[$post_forum_url]) && $_REQUEST[$post_forum_url] > 0) ? (int) $_REQUEST[$post_forum_url] : 0,
		'topic'							=> (isset($_REQUEST[$post_topic_url]) && $_REQUEST[$post_topic_url] > 0) ? (int) $_REQUEST[$post_topic_url] : 0,
		'page_full'					=> $page_full,
	);

	return $page_array;
}

/**
* Get valid hostname/port. HTTP_HOST is used, SERVER_NAME if HTTP_HOST not present.
* function backported from phpBB3 - Olympus
*/
function extract_current_hostname()
{
	global $config;

	// Get hostname
	$host = (!empty($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST'] : ((!empty($_SERVER['SERVER_NAME'])) ? $_SERVER['SERVER_NAME'] : getenv('SERVER_NAME'));

	// Should be a lowercase string
	$host = (string) strtolower($host);

	// If host is equal the cookie domain or the server name (if config is set), then we assume it is valid
	if ((isset($config['cookie_domain']) && ($host === $config['cookie_domain'])) || (isset($config['server_name']) && ($host === $config['server_name'])))
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
		if (!empty($config['server_name']))
		{
			$host = $config['server_name'];
		}
		elseif (!empty($config['cookie_domain']))
		{
			$host = (strpos($config['cookie_domain'], '.') === 0) ? substr($config['cookie_domain'], 1) : $config['cookie_domain'];
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
		// normalize UTF-8 data
		if ($multibyte)
		{
			$result = utf8_normalize_nfc($result);
		}

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
* Get passed variable
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

	$super_global = ($cookie) ? '_COOKIE' : '_REQUEST';
	if (!isset($GLOBALS[$super_global][$var_name]) || is_array($GLOBALS[$super_global][$var_name]) != is_array($default))
	{
		return (is_array($default)) ? array() : $default;
	}

	$var = $GLOBALS[$super_global][$var_name];
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
					set_var($_k, $_k, $sub_key_type, $multibyte);
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
* Request the var value but returns only true of false, useful for forms validations
*/
function request_boolean_var($var_name, $default, $multibyte = false, $post_only = false)
{
	if ($post_only)
	{
		$return = request_post_var($var_name, $default, $multibyte);
	}
	else
	{
		$return = request_var($var_name, $default, $multibyte);
	}
	$return = !empty($return) ? true : false;
	return $return;
}

/**
* Gets only POST vars
*/
function request_post_var($var_name, $default, $multibyte = false)
{
	$return = $default;
	if (isset($_POST[$var_name]))
	{
		$return = request_var($var_name, $default, $multibyte);
	}
	return $return;
}

/**
* Get only GET vars
*/
function request_get_var($var_name, $default, $multibyte = false)
{
	$return = $default;
	if (isset($_GET[$var_name]))
	{
		$temp_post_var = isset($_POST[$var_name]) ? $_POST[$var_name] : '';
		$_POST[$var_name] = $_GET[$var_name];
		$return = request_var($var_name, $default, $multibyte);
		$_POST[$var_name] = $temp_post_var;
	}
	return $return;
}

/**
* Check GET POST vars exists
*/
function check_http_var_exists($var_name, $empty_var = false)
{
	if ($empty_var)
	{
		if (isset($_GET[$var_name]) || isset($_POST[$var_name]))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	else
	{
		if (!empty($_GET[$var_name]) || !empty($_POST[$var_name]))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	return false;
}

/**
* Check variable value against default array
*/
function check_var_value($var, $var_array, $var_default = false)
{
	if (!is_array($var_array) || empty($var_array))
	{
		return $var;
	}
	$var_default = (($var_default === false) ? $var_array[0] : $var_default);
	$var = in_array($var, $var_array) ? $var : $var_default;
	return $var;
}

/**
* Function to add slashes to vars array, may be used to globally escape HTTP vars if needed
*/
function slash_data(&$data)
{
	if (is_array($data))
	{
		foreach ($data as $k => $v)
		{
			$data[$k] = (is_array($v)) ? slash_data($v) : addslashes($v);
		}
	}
	return $data;
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
	// Old version, to be verified why &amp; gets converted twice...
	//return trim(str_replace(array('& ', '<', '%3C', '>', '%3E'), array('&amp; ', '&lt;', '&lt;', '&gt;', '&gt;'), htmlspecialchars_decode($string, $quote_style)));
	return trim(str_replace(array('& ', '<', '%3C', '>', '%3E', '{IP_EAMP_ESCAPE}'), array('&amp; ', '&lt;', '&lt;', '&gt;', '&gt;', '&amp;'), htmlspecialchars_decode(str_replace('&amp;', '{IP_EAMP_ESCAPE}', $string), $quote_style)));
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
	return $db->sql_escape($string);
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

/**
* Get option bitfield from custom data
*
* @param int $bitThe bit/value to get
* @param int $data Current bitfield to check
* @return bool Returns true if value of constant is set in bitfield, else false
*/
function phpbb_optionget($bit, $data)
{
	return ($data & 1 << (int) $bit) ? true : false;
}

/**
* Set option bitfield
*
* @param int $bit The bit/value to set/unset
* @param bool $set True if option should be set, false if option should be unset.
* @param int $data Current bitfield to change
* @return int The new bitfield
*/
function phpbb_optionset($bit, $set, $data)
{
	if ($set && !($data & 1 << $bit))
	{
		$data += 1 << $bit;
	}
	elseif (!$set && ($data & 1 << $bit))
	{
		$data -= 1 << $bit;
	}

	return $data;
}

/*
* Get user data, $target_user can be username or user_id.
* If force_str is true, the username will be forced.
*/
function get_userdata($target_user, $force_str = false)
{
	global $db;

	$target_user = (!is_numeric($target_user) || $force_str) ? phpbb_clean_username($target_user) : intval($target_user);

	$sql = "SELECT *
		FROM " . USERS_TABLE . "
		WHERE ";
	$sql .= (is_integer($target_user) ? ("user_id = " . (int) $target_user) : ("username_clean = '" . $db->sql_escape(utf8_clean_string($target_user)) . "'")) . " AND user_id <> " . ANONYMOUS;
	$result = $db->sql_query($sql);

	if ($db->sql_affectedrows() == 0)
	{
		//message_die(GENERAL_ERROR, 'User does not exist.');
		return false;
	}

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
* Generate an SQL to get users based on a search string
*/
function get_users_sql($username, $sql_like = false, $all_data = false, $data_escape = true, $clean_username = false)
{
	global $config, $cache, $db;

	$username = (!empty($clean_username) ? phpbb_clean_username($username) : $username);
	$sql = "SELECT " . (!empty($all_data) ? "*" : ("user_id, username, username_clean, user_active, user_color, user_level")) . " FROM " . USERS_TABLE . "
		WHERE username_clean " . (!empty($sql_like) ? (" LIKE ") : (" = ")) . "'" . (!empty($data_escape) ? $db->sql_escape(utf8_clean_string($username)) : $username) . "'" . (!empty($sql_like) ? "" : (" LIMIT 1"));

	return $sql;
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
	$founder_id = (intval($config['main_admin_id']) >= 2) ? (int) $config['main_admin_id'] : 2;
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
* Get groups data
*/
function get_groups_data($full_data = false, $sort_by_name = false, $sql_groups = array())
{
	global $db, $cache, $config;

	$groups_data = array();
	$sql_select = !empty($full_data) ? '*' : 'g.group_id, g.group_name, g.group_color, g.group_legend, g.group_legend_order';
	$sql_where = '';
	if (!empty($sql_groups))
	{
		if (!is_array($sql_groups))
		{
			$sql_groups = array($sql_groups);
		}
		$sql_where = !empty($sql_groups) ? (' AND ' . $db->sql_in_set('g.group_id', $sql_groups)) : '';
	}
	$sql_sort = !empty($sort_by_name) ? ' ORDER BY g.group_name ASC' : ' ORDER BY g.group_legend DESC, g.group_legend_order ASC, g.group_name ASC';
	$sql = "SELECT " . $sql_select . "
		FROM " . GROUPS_TABLE . " g
		WHERE g.group_single_user = 0" .
		$sql_where .
		$sql_sort;
	$result = $db->sql_query($sql, 0, 'groups_', USERS_CACHE_FOLDER);
	$groups_data = $db->sql_fetchrowset($result);
	$db->sql_freeresult($result);

	return $groups_data;
}

/*
* Get groups data for a specific user
*/
function get_groups_data_user($user_id, $full_data = false, $sort_by_name = false, $sql_groups = array())
{
	global $db, $cache, $config;

	$groups_data = array();
	$sql_select = !empty($full_data) ? 'g.*, ug.*' : 'g.group_id, g.group_name, g.group_color, g.group_legend, g.group_legend_order, ug.user_pending';
	$sql_where = '';
	if (!empty($sql_groups))
	{
		if (!is_array($sql_groups))
		{
			$sql_groups = array($sql_groups);
		}
		$sql_where = !empty($sql_groups) ? (' AND ' . $db->sql_in_set('g.group_id', $sql_groups)) : '';
	}
	$sql = "SELECT " . $sql_select . "
		FROM " . GROUPS_TABLE . " g, " . USER_GROUP_TABLE . " ug " . "
		WHERE g.group_single_user = 0" .
		$sql_where . "
		AND g.group_id = ug.group_id
		AND ug.user_id = " . (int) $user_id;
	$result = $db->sql_query($sql, 0, 'groups_', USERS_CACHE_FOLDER);
	$groups_data = $db->sql_fetchrowset($result);
	$db->sql_freeresult($result);

	return $groups_data;
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

/**
* Generates an alphanumeric random string of given length
*/
function gen_rand_string($num_chars = 8)
{
	$rand_str = unique_id();
	$rand_str = str_replace('0', 'Z', strtoupper(base_convert($rand_str, 16, 35)));

	return substr($rand_str, 0, $num_chars);
}

/**
* Return unique id
* @param string $extra additional entropy
*/
function unique_id($extra = 'c')
{
	static $dss_seeded = false;
	global $config, $cache;

	$val = $config['rand_seed'] . microtime();
	$val = md5($val);
	$config['rand_seed'] = md5($config['rand_seed'] . $val . $extra);

	if(($dss_seeded !== true) && ($config['rand_seed_last_update'] < (time() - rand(1, 10))))
	{
		// Maybe we can avoid emptying cache every random seed generation...
		set_config('rand_seed', $config['rand_seed'], false);
		set_config('rand_seed_last_update', time(), false);
		$dss_seeded = true;
	}

	return substr($val, 4, 16);
}

// Modified by MG
/**
* Return formatted string for filesizes
*
* @param int $value filesize in bytes
* @param bool $string_only true if language string should be returned
* @param array $allowed_units only allow these units (data array indexes)
*
* @return mixed data array if $string_only is false
* @author bantu
*/
function get_formatted_filesize($value, $string_only = true, $allowed_units = false)
{
	global $lang;

	$available_units = array(
		'gb' => array(
			'min' => 1073741824, // pow(2, 30)
			'index' => 3,
			'si_unit' => 'GB',
			'iec_unit' => 'GIB',
			'precision' => 2
		),
		'mb' => array(
			'min' => 1048576, // pow(2, 20)
			'index' => 2,
			'si_unit' => 'MB',
			'iec_unit' => 'MIB',
			'precision' => 2
		),
		'kb' => array(
			'min' => 1024, // pow(2, 10)
			'index' => 1,
			'si_unit' => 'KB',
			'iec_unit' => 'KIB',
			'precision' => 0
		),
		'b' => array(
			'min' => 0,
			'index' => 0,
			'si_unit' => 'BYTES', // Language index
			'iec_unit' => 'BYTES', // Language index
			'precision' => 0
		),
	);

	foreach ($available_units as $si_identifier => $unit_info)
	{
		if (!empty($allowed_units) && ($si_identifier != 'b') && !in_array($si_identifier, $allowed_units))
		{
			continue;
		}

		if ($value >= $unit_info['min'])
		{
			$unit_info['si_identifier'] = $si_identifier;

			break;
		}
	}
	unset($available_units);

	for ($i = 0; $i < $unit_info['index']; $i++)
	{
		$value /= 1024;
	}
	$value = round($value, $unit_info['precision']);

	// Lookup units in language dictionary
	$unit_info['si_unit'] = (isset($lang[$unit_info['si_unit']])) ? $lang[$unit_info['si_unit']] : $unit_info['si_unit'];
	$unit_info['iec_unit'] = (isset($lang[$unit_info['iec_unit']])) ? $lang[$unit_info['iec_unit']] : $unit_info['iec_unit'];

	// Default to SI
	$unit_info['unit'] = $unit_info['si_unit'];

	if (!$string_only)
	{
		$unit_info['value'] = $value;

		return $unit_info;
	}

	return $value . $unit_info['unit'];
}

/**
*
* @version Version 0.1 / slightly modified for phpBB 3.0.x (using $H$ as hash type identifier)
*
* Portable PHP password hashing framework.
*
* Written by Solar Designer <solar at openwall.com> in 2004-2006 and placed in
* the public domain.
*
* There's absolutely no warranty.
*
* The homepage URL for this framework is:
*
* http://www.openwall.com/phpass/
*
* Please be sure to update the Version line if you edit this file in any way.
* It is suggested that you leave the main version number intact, but indicate
* your project name (after the slash) and add your own revision information.
*
* Please do not change the "private" password hashing method implemented in
* here, thereby making your hashes incompatible.  However, if you must, please
* change the hash type identifier (the "$P$") to something different.
*
* Obviously, since this code is in the public domain, the above are not
* requirements (there can be none), but merely suggestions.
*
*
* Hash the password
*/
function phpbb_hash($password)
{
	$itoa64 = './0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

	$random_state = unique_id();
	$random = '';
	$count = 6;

	if (($fh = @fopen('/dev/urandom', 'rb')))
	{
		$random = fread($fh, $count);
		fclose($fh);
	}

	if (strlen($random) < $count)
	{
		$random = '';

		for ($i = 0; $i < $count; $i += 16)
		{
			$random_state = md5(unique_id() . $random_state);
			$random .= pack('H*', md5($random_state));
		}
		$random = substr($random, 0, $count);
	}

	$hash = _hash_crypt_private($password, _hash_gensalt_private($random, $itoa64), $itoa64);

	if (strlen($hash) == 34)
	{
		return $hash;
	}

	return md5($password);
}

/**
* Check for correct password
*
* @param string $password The password in plain text
* @param string $hash The stored password hash
*
* @return bool Returns true if the password is correct, false if not.
*/
function phpbb_check_hash($password, $hash)
{
	if (strlen($hash) == 34)
	{
		$itoa64 = './0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
		return (_hash_crypt_private($password, $hash, $itoa64) === $hash) ? true : false;
	}

	return (md5($password) === $hash) ? true : false;
}

/**
* Generate salt for hash generation
*/
function _hash_gensalt_private($input, &$itoa64, $iteration_count_log2 = 6)
{
	if ($iteration_count_log2 < 4 || $iteration_count_log2 > 31)
	{
		$iteration_count_log2 = 8;
	}

	$output = '$H$';
	$output .= $itoa64[min($iteration_count_log2 + ((PHP_VERSION >= 5) ? 5 : 3), 30)];
	$output .= _hash_encode64($input, 6, $itoa64);

	return $output;
}

/**
* Encode hash
*/
function _hash_encode64($input, $count, &$itoa64)
{
	$output = '';
	$i = 0;

	do
	{
		$value = ord($input[$i++]);
		$output .= $itoa64[$value & 0x3f];

		if ($i < $count)
		{
			$value |= ord($input[$i]) << 8;
		}

		$output .= $itoa64[($value >> 6) & 0x3f];

		if ($i++ >= $count)
		{
			break;
		}

		if ($i < $count)
		{
			$value |= ord($input[$i]) << 16;
		}

		$output .= $itoa64[($value >> 12) & 0x3f];

		if ($i++ >= $count)
		{
			break;
		}

		$output .= $itoa64[($value >> 18) & 0x3f];
	}
	while ($i < $count);

	return $output;
}

/**
* The crypt function/replacement
*/
function _hash_crypt_private($password, $setting, &$itoa64)
{
	$output = '*';

	// Check for correct hash
	if (substr($setting, 0, 3) != '$H$')
	{
		return $output;
	}

	$count_log2 = strpos($itoa64, $setting[3]);

	if ($count_log2 < 7 || $count_log2 > 30)
	{
		return $output;
	}

	$count = 1 << $count_log2;
	$salt = substr($setting, 4, 8);

	if (strlen($salt) != 8)
	{
		return $output;
	}

	/**
	* We're kind of forced to use MD5 here since it's the only
	* cryptographic primitive available in all versions of PHP
	* currently in use. To implement our own low-level crypto
	* in PHP would result in much worse performance and
	* consequently in lower iteration counts and hashes that are
	* quicker to crack (by non-PHP code).
	*/
	if (PHP_VERSION >= 5)
	{
		$hash = md5($salt . $password, true);
		do
		{
			$hash = md5($hash . $password, true);
		}
		while (--$count);
	}
	else
	{
		$hash = pack('H*', md5($salt . $password));
		do
		{
			$hash = pack('H*', md5($hash . $password));
		}
		while (--$count);
	}

	$output = substr($setting, 0, 12);
	$output .= _hash_encode64($hash, 16, $itoa64);

	return $output;
}

/**
* Hashes an email address to a big integer
*
* @param string $email Email address
* @return string Big Integer
*/
function phpbb_email_hash($email)
{
	return sprintf('%u', crc32(strtolower($email))) . strlen($email);
}

//Form validation

/**
* Add a secret hash for use in links/GET requests
* @param string $link_name The name of the link; has to match the name used in check_link_hash, otherwise no restrictions apply
* @return string the hash
*/
function generate_link_hash($link_name)
{
	global $user;

	if (!isset($user->data["hash_$link_name"]))
	{
		$user->data["hash_$link_name"] = substr(sha1($user->data['user_form_salt'] . $link_name), 0, 8);
	}

	return $user->data["hash_$link_name"];
}


/**
* checks a link hash - for GET requests
* @param string $token the submitted token
* @param string $link_name The name of the link
* @return boolean true if all is fine
*/
function check_link_hash($token, $link_name)
{
	return $token === generate_link_hash($link_name);
}

/**
* Add a secret token to the form (requires the S_FORM_TOKEN template variable)
* @param string $form_name The name of the form; has to match the name used in check_form_key, otherwise no restrictions apply
*/
function add_form_key($form_name)
{
	global $config, $template, $user;

	$now = time();
	$token_sid = (($user->data['user_id'] == ANONYMOUS) && !empty($config['form_token_sid_guests'])) ? $user->data['session_id'] : '';
	$token = sha1($now . $user->data['user_form_salt'] . $form_name . $token_sid);

	$s_fields = build_hidden_fields(array(
		'creation_time' => $now,
		'form_token' => $token,
		)
	);

	$template->assign_vars(array(
		'S_FORM_TOKEN' => $s_fields,
		)
	);
}

/**
* Check the form key. Required for all altering actions not secured by confirm_box
* @param string  $form_name The name of the form; has to match the name used in add_form_key, otherwise no restrictions apply
* @param int $timespan The maximum acceptable age for a submitted form in seconds. Defaults to the config setting.
* @param string $return_page The address for the return link
* @param bool $trigger If true, the function will triger an error when encountering an invalid form
*/
function check_form_key($form_name, $timespan = false, $return_page = '', $trigger = false)
{
	global $config, $user, $lang;

	if ($timespan === false)
	{
		// we enforce a minimum value of half a minute here.
		$timespan = ($config['form_token_lifetime'] == -1) ? -1 : max(30, $config['form_token_lifetime']);
	}

	if (isset($_POST['creation_time']) && isset($_POST['form_token']))
	{
		$creation_time = abs(request_var('creation_time', 0));
		$token = request_var('form_token', '');

		$diff = time() - $creation_time;

		// If creation_time and the time() now is zero we can assume it was not a human doing this (the check for if ($diff)...
		if ($diff && (($diff <= $timespan) || ($timespan === -1)))
		{
			$token_sid = (($user->data['user_id'] == ANONYMOUS) && !empty($config['form_token_sid_guests'])) ? $user->data['session_id'] : '';
			$key = sha1($creation_time . $user->data['user_form_salt'] . $form_name . $token_sid);

			if ($key === $token)
			{
				return true;
			}
		}
	}

	if ($trigger)
	{
		trigger_error($lang['FORM_INVALID'] . $return_page);
	}

	return false;
}

// added at phpBB 2.0.11 to properly format the username
function phpbb_clean_username($username)
{
	$username = substr(htmlspecialchars(trim($username)), 0, 36);
	$username = rtrim($username, "\\");

	return $username;
}

/*
* Function to clear all unwanted chars in username
*/
function ip_clean_username($username)
{
	$username = preg_replace('/[^A-Za-z0-9\-_. ]+/', '', trim($username));
	return $username;
}

/*
* Create email signature
*/
function create_signature($signature = '')
{
	global $config;

	$signature = !empty($signature) ? $signature : $config['board_email_sig'];
	$email_sig = (!empty($signature) ? str_replace('<br />', "\n", $config['sig_line'] . " \n" . $signature) : '');
	if (!empty($config['html_email']))
	{
		$email_sig = nl2br($email_sig);
	}

	return $email_sig;
}

/*
* Clean string
*/
function ip_clean_string($text, $charset = false, $extra_chars = false, $is_filename = false)
{
	$charset = empty($charset) ? 'utf-8' : $charset;

	// Function needed to convert some of the German characters into Latin correspondent characters
	$text = utf_ger_to_latin($text, false);

	// Function needed to convert some of the Cyrillic characters into Latin correspondent characters
	$text = utf_cyr_to_latin($text, false);

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
	// Mighty Gorgon: added a workaround for some special case... :-(
	$text_tmp = $text;
	$text = @htmlentities($text, ENT_COMPAT, $charset);
	if (!empty($text_tmp) && empty($text))
	{
		$text = htmlentities($text_tmp);
	}

	// Replace some known HTML entities
	$find = array(
		'&#268;', '&#269;', // c
		'&#356;', '&#357;', // t
		'&#270;', '&#271;', // d
		'&#317;', '&#318;', // L, l
		'&#327;', '&#328;', // N, n
		'&#381;', '&#382;', 'Ž', 'ž', // z
		'&#223;', '&#946;', 'ß', // ß
		'œ', '&#338;', '&#339;', // OE, oe
		'&#198;', '&#230;', // AE, ae
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
		'ss', 'ss', 'ss',
		'oe', 'oe', 'oe',
		'ae', 'ae',
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
	if ($extra_chars || $is_filename)
	{
		// if $extra_chars is true then we will allow spaces, underscores and dots
		$text = preg_replace('![^a-z0-9\-._ ]!s', '-', $text);
		if ($is_filename)
		{
			$text = str_replace(' ', '_', $text);
		}
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

	if ($is_filename)
	{
		// Remove any trailing dot at the end, to avoid messing up Windows naming system...
		$text = rtrim($text, '.');
	}

	return $text;
}

/**
* German to Latin chars conversion
*/
function utf_ger_to_latin($string, $reverse = false)
{
	$ger  = array(
		'&#223;', '&#946;', 'ß', // ß
		'&#196;', '&#228;', 'Ä', 'ä', // Ä, ä
		'&#214;', '&#246;', 'Ö', 'ö', // Ö, ö
		'&#220;', '&#252;', 'Ü', 'ü', // Ü, ü
	);

	$lat = array(
		'ss', 'ss', 'ss',
		'ae', 'ae', 'ae', 'ae',
		'oe', 'oe', 'oe', 'oe',
		'ue', 'ue', 'ue', 'ue',
	);

	$string = !empty($reverse) ? str_replace($lat, $ger, $string) : str_replace($ger, $lat, $string);

	return $string;
}

/**
* Cyrillic to Latin chars conversion
*/
function utf_cyr_to_latin($string, $reverse = false)
{
	$cyr  = array(
		'а', 'б', 'в', 'г', 'д',
		'e', 'ж', 'з', 'и', 'й',
		'к', 'л', 'м', 'н', 'о',
		'п', 'р', 'с', 'т', 'у',
		'ф', 'х', 'ц', 'ч', 'ш',
		'щ', 'ъ', 'ь', 'ю', 'я',
		'А', 'Б', 'В', 'Г', 'Д',
		'Е', 'Ж', 'З', 'И', 'Й',
		'К', 'Л', 'М', 'Н', 'О',
		'П', 'Р', 'С', 'Т', 'У',
		'Ф', 'Х', 'Ц', 'Ч', 'Ш',
		'Щ', 'Ъ', 'Ь', 'Ю', 'Я'
	);

	$lat = array(
		'a', 'b', 'v', 'g', 'd',
		'e', 'zh', 'z', 'i', 'y',
		'k', 'l', 'm', 'n', 'o',
		'p', 'r', 's', 't', 'u',
		'f', 'h', 'ts', 'ch', 'sh',
		'sht', 'a', 'y', 'yu', 'ya',
		'A', 'B', 'V', 'G', 'D',
		'E', 'Zh', 'Z', 'I', 'Y',
		'K', 'L', 'M', 'N', 'O',
		'P', 'R', 'S', 'T', 'U',
		'F', 'H', 'Ts', 'Ch', 'Sh',
		'Sht', 'A', 'Y', 'Yu', 'Ya'
	);

	$string = !empty($reverse) ? str_replace($lat, $cyr, $string) : str_replace($cyr, $lat, $string);

	return $string;
}

/**
* Generate back link
*/
function page_back_link($u_action)
{
	global $lang;
	return '<br /><br /><a href="' . $u_action . '">&laquo; ' . $lang['BACK_TO_PREV'] . '</a>';
}

/**
* Build Confirm box
* @param boolean $check True for checking if confirmed (without any additional parameters) and false for displaying the confirm box
* @param string $title Title/Message used for confirm box.
* message text is _CONFIRM appended to title.
* If title cannot be found in user->lang a default one is displayed
* If title_CONFIRM cannot be found in user->lang the text given is used.
* @param string $hidden Hidden variables
* @param string $html_body Template used for confirm box
* @param string $u_action Custom form action
*/
function confirm_box($check, $title = '', $hidden = '', $html_body = 'confirm_body.tpl', $u_action = '')
{
	global $db, $user, $lang, $template;

	if (isset($_POST['cancel']))
	{
		return false;
	}

	$confirm = false;
	if (isset($_POST['confirm']))
	{
		// language frontier
		if ($_POST['confirm'] === $lang['YES'])
		{
			$confirm = true;
		}
	}

	if ($check && $confirm)
	{
		$user_id = request_var('confirm_uid', 0);
		$session_id = request_var('sess', '');

		if (($user_id != $user->data['user_id']) || ($session_id != $user->session_id))
		{
			return false;
		}

		return true;
	}
	elseif ($check)
	{
		return false;
	}

	$s_hidden_fields = build_hidden_fields(array(
		'confirm_uid' => $user->data['user_id'],
		'sess' => $user->session_id,
		'sid' => $user->session_id,
		)
	);

	// re-add sid / transform & to &amp; for user->page (user->page is always using &)
	$use_page = ($u_action) ? IP_ROOT_PATH . $u_action : IP_ROOT_PATH . str_replace('&', '&amp;', $user->page['page']);
	$u_action = reapply_sid($use_page);
	$u_action .= ((strpos($u_action, '?') === false) ? '?' : '&amp;');

	$confirm_title = (!isset($lang[$title])) ? $lang['Confirm'] : $lang[$title];

	$template->assign_vars(array(
		'MESSAGE_TITLE' => $confirm_title,
		'MESSAGE_TEXT' => (!isset($lang[$title . '_CONFIRM'])) ? $title : $lang[$title . '_CONFIRM'],

		'YES_VALUE' => $lang['YES'],
		'S_CONFIRM_ACTION' => $u_action,
		'S_HIDDEN_FIELDS' => $hidden . $s_hidden_fields
		)
	);

	full_page_generation($html_body, $confirm_title, '', '');
}

/*
* jumpbox() : replace the original phpBB make_jumpbox()
*/
function jumpbox($action, $match_forum_id = 0)
{
	global $db, $template, $user, $lang;

	// build the jumpbox
	$boxstring  = '<select name="selected_id" onchange="if(this.options[this.selectedIndex].value != -1){ forms[\'jumpbox\'].submit() }">';
	$boxstring .= get_tree_option(POST_FORUM_URL . $match_forum_id);
	$boxstring .= '</select>';

	$boxstring .= '<input type="hidden" name="sid" value="' . $user->data['session_id'] . '" />';

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

	$server_protocol = ($config['cookie_secure']) ? 'https://' : 'http://';
	$server_name = preg_replace('#^\/?(.*?)\/?$#', '\1', trim($config['server_name']));
	$server_port = ($config['server_port'] <> 80) ? ':' . trim($config['server_port']) : '';
	$script_name = preg_replace('/^\/?(.*?)\/?$/', '\1', trim($config['script_path']));
	$script_name = ($script_name == '') ? '' : '/' . $script_name;
	$server_url = $server_protocol . $server_name . $server_port . ($without_script_path ? '' : $script_name);
	while(substr($server_url, -1, 1) == '/')
	{
		$server_url = substr($server_url, 0, -1);
	}
	$server_url = $server_url . '/';

	return $server_url;
}

/**
* Returns url from the session/current page with an re-appended SID with optionally stripping vars from the url
*/
function build_url($strip_vars = false)
{
	global $user;

	// Append SID
	$redirect = append_sid($user->page['page'], true);

	// Add delimiter if not there...
	if (strpos($redirect, '?') === false)
	{
		$redirect .= '?';
	}

	// Strip vars...
	if (($strip_vars !== false) && strpos($redirect, '?') !== false)
	{
		if (!is_array($strip_vars))
		{
			$strip_vars = array($strip_vars);
		}

		$query = $_query = array();

		$args = substr($redirect, strpos($redirect, '?') + 1);
		$args = ($args) ? explode('&', $args) : array();
		$redirect = substr($redirect, 0, strpos($redirect, '?'));

		foreach ($args as $argument)
		{
			$arguments = explode('=', $argument);
			$key = $arguments[0];
			unset($arguments[0]);

			if ($key === '')
			{
				continue;
			}

			$query[$key] = implode('=', $arguments);
		}

		// Strip the vars off
		foreach ($strip_vars as $strip)
		{
			if (isset($query[$strip]))
			{
				unset($query[$strip]);
			}
		}

		// Glue the remaining parts together... already urlencoded
		foreach ($query as $key => $value)
		{
			$_query[] = $key . '=' . $value;
		}
		$query = implode('&', $_query);

		$redirect .= ($query) ? '?' . $query : '';
	}

	// We need to be cautious here.
	// On some situations, the redirect path is an absolute URL, sometimes a relative path
	// For a relative path, let's prefix it with IP_ROOT_PATH to point to the correct location,
	// else we use the URL directly.
	$url_parts = @parse_url($redirect);

	// URL
	if (($url_parts !== false) && !empty($url_parts['scheme']) && !empty($url_parts['host']))
	{
		return str_replace('&', '&amp;', $redirect);
	}

	return IP_ROOT_PATH . str_replace('&', '&amp;', $redirect);
}

/**
* Redirects the user to another page then exits the script nicely
* This function is intended for urls within the board. It's not meant to redirect to cross-domains.
*
* @param string $url The url to redirect to
* @param bool $return If true, do not redirect but return the sanitized URL. Default is no return.
* @param bool $disable_cd_check If true, redirect() will redirect to an external domain. If false, the redirect point to the boards url if it does not match the current domain. Default is false.
*/
function redirect($url, $return = false, $disable_cd_check = false)
{
	global $db, $cache, $config, $user, $lang;

	$failover_flag = false;

	if (empty($lang))
	{
		setup_basic_lang();
	}

	if (!$return)
	{
		garbage_collection();
	}

	$server_url = create_server_url();

	// Make sure no &amp;'s are in, this will break the redirect
	$url = str_replace('&amp;', '&', $url);
	// Determine which type of redirect we need to handle...
	$url_parts = @parse_url($url);

	if ($url_parts === false)
	{
		// Malformed url, redirect to current page...
		$url = $server_url . $user->page['page'];
	}
	elseif (!empty($url_parts['scheme']) && !empty($url_parts['host']))
	{
		// Attention: only able to redirect within the same domain if $disable_cd_check is false (yourdomain.com -> www.yourdomain.com will not work)
		if (!$disable_cd_check && ($url_parts['host'] !== $user->host))
		{
			$url = $server_url;
		}
	}
	elseif ($url[0] == '/')
	{
		// Absolute uri, prepend direct url...
		$url = create_server_url(true) . $url;
	}
	else
	{
		// Relative uri
		$pathinfo = pathinfo($url);

		if (!$disable_cd_check && !file_exists($pathinfo['dirname'] . '/'))
		{
			$url = str_replace('../', '', $url);
			$pathinfo = pathinfo($url);

			if (!file_exists($pathinfo['dirname'] . '/'))
			{
				// fallback to "last known user page"
				// at least this way we know the user does not leave the phpBB root
				$url = $server_url . $user->page['page'];
				$failover_flag = true;
			}
		}

		if (!$failover_flag)
		{
			// Is the uri pointing to the current directory?
			if ($pathinfo['dirname'] == '.')
			{
				$url = str_replace('./', '', $url);

				// Strip / from the beginning
				if ($url && (substr($url, 0, 1) == '/'))
				{
					$url = substr($url, 1);
				}

				if ($user->page['page_dir'])
				{
					$url = $server_url . $user->page['page_dir'] . '/' . $url;
				}
				else
				{
					$url = $server_url . $url;
				}
			}
			else
			{
				// Used ./ before, but IP_ROOT_PATH is working better with urls within another root path
				$root_dirs = explode('/', str_replace('\\', '/', phpbb_realpath(IP_ROOT_PATH)));
				$page_dirs = explode('/', str_replace('\\', '/', phpbb_realpath($pathinfo['dirname'])));
				$intersection = array_intersect_assoc($root_dirs, $page_dirs);

				$root_dirs = array_diff_assoc($root_dirs, $intersection);
				$page_dirs = array_diff_assoc($page_dirs, $intersection);

				$dir = str_repeat('../', sizeof($root_dirs)) . implode('/', $page_dirs);

				// Strip / from the end
				if ($dir && substr($dir, -1, 1) == '/')
				{
					$dir = substr($dir, 0, -1);
				}

				// Strip / from the beginning
				if ($dir && substr($dir, 0, 1) == '/')
				{
					$dir = substr($dir, 1);
				}

				$url = str_replace($pathinfo['dirname'] . '/', '', $url);

				// Strip / from the beginning
				if (substr($url, 0, 1) == '/')
				{
					$url = substr($url, 1);
				}

				$url = (!empty($dir) ? $dir . '/' : '') . $url;
				$url = $server_url . $url;
			}
		}
	}

	// Make sure no linebreaks are there... to prevent http response splitting for PHP < 4.4.2
	if ((strpos(urldecode($url), "\n") !== false) || (strpos(urldecode($url), "\r") !== false) || (strpos($url, ';') !== false))
	{
		message_die(GENERAL_ERROR, 'Tried to redirect to potentially insecure url');
		//trigger_error('Tried to redirect to potentially insecure url.', E_USER_ERROR);
	}

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

		$encoding_charset = !empty($lang['ENCODING']) ? $lang['ENCODING'] : 'UTF-8';
		$lang_dir = !empty($lang['DIRECTION']) ? $lang['DIRECTION'] : 'ltr';
		$header_lang = !empty($lang['HEADER_LANG']) ? $lang['HEADER_LANG'] : 'en-gb';
		$xml_header_lang = !empty($lang['HEADER_LANG_XML']) ? $lang['HEADER_LANG_XML'] : 'en-gb';
		echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
		echo '<html xmlns="http://www.w3.org/1999/xhtml" dir="' . $lang_dir . '" lang="' . $header_lang . '" xml:lang="' . $xml_header_lang . '">';
		echo '<head>';
		echo '<meta http-equiv="content-type" content="text/html; charset=' . $encoding_charset . '" />';
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
* Outputs correct status line header.
*
* Depending on php sapi one of the two following forms is used:
*
* Status: 404 Not Found
*
* HTTP/1.x 404 Not Found
*
* HTTP version is taken from HTTP_VERSION environment variable,
* and defaults to 1.0.
*
* Sample usage:
*
* send_status_line(404, 'Not Found');
*
* @param int $code HTTP status code
* @param string $message Message for the status code
* @return void
*/
function send_status_line($code, $message)
{
	if (substr(strtolower(@php_sapi_name()), 0, 3) === 'cgi')
	{
		// in theory, we shouldn't need that due to php doing it. Reality offers a differing opinion, though
		@header("Status: $code $message", true, $code);
	}
	else
	{
		if (!empty($_SERVER['SERVER_PROTOCOL']))
		{
			$version = $_SERVER['SERVER_PROTOCOL'];
		}
		else
		{
			$version = 'HTTP/1.0';
		}
		@header("$version $code $message", true, $code);
	}
}

/**
* Setup basic lang
*/
function setup_basic_lang()
{
	global $cache, $config, $lang, $class_plugins;

	if (empty($lang))
	{
		$setup = true;
		if(!file_exists(IP_ROOT_PATH . 'language/lang_' . $config['default_lang'] . '/lang_main.' . PHP_EXT))
		{
			$config['default_lang'] = 'english';
		}

		$lang_files = array(
			'lang_main',
			'lang_bbcb_mg',
			'lang_main_upi2db',
			'lang_news',
			'lang_main_attach',
			'lang_main_cback_ctracker',
		);

		if (!empty($config['plugins']['cash']['enabled']) && defined('IN_CASHMOD'))
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

		if (defined('IN_CMS'))
		{
			$lang_files_cms = array(
				'lang_admin',
				'lang_cms',
				'lang_blocks',
				'lang_permissions',
			);
			$lang_files = array_merge($lang_files, $lang_files_cms);
		}

		$lang_files = array_merge($lang_files, $cache->obtain_lang_files());
		// Make sure we keep these files as last inclusion... to be sure they override what is needed to be overridden!!!
		$lang_files = array_merge($lang_files, array('lang_dyn_menu', 'lang_main_settings', 'lang_user_created'));

		foreach ($lang_files as $lang_file)
		{
			// Do not suppress error if in DEBUG_EXTRA mode
			$include_result = (defined('DEBUG_EXTRA') && DEBUG_EXTRA) ? (include(IP_ROOT_PATH . 'language/lang_' . $config['default_lang'] . '/' . $lang_file . '.' . PHP_EXT)) : (@include(IP_ROOT_PATH . 'language/lang_' . $config['default_lang'] . '/' . $lang_file . '.' . PHP_EXT));

			if ($include_result === false)
			{
				die('Language file ' . IP_ROOT_PATH . 'language/lang_' . $config['default_lang'] . '/' . $lang_file . '.' . PHP_EXT . ' couldn\'t be opened.');
			}
		}

		foreach ($config['plugins'] as $k => $plugin)
		{
			if ($plugin['enabled'])
			{
				$class_plugins->setup_lang($plugin['dir']);
			}
		}
	}
	return true;
}

/**
* Setup extra lang
*/
function setup_extra_lang($lang_files_array, $lang_base_path = '', $lang_override = '')
{
	global $config, $lang, $images, $faq, $mtnc;

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
		$lang_override = !empty($lang_override) ? $lang_override : $config['default_lang'];
		$user_lang_file = $lang_base_path . 'lang_' . $lang_override . '/' . $lang_files_array[$i] . '.' . PHP_EXT;
		$default_lang_file = $lang_base_path . 'lang_english/' . $lang_files_array[$i] . '.' . PHP_EXT;
		if (@file_exists($user_lang_file))
		{
			@include($user_lang_file);
		}
		elseif (@file_exists($default_lang_file))
		{
			@include($default_lang_file);
		}
	}

	return true;
}

/**
* Merge $lang with $user->lang
*/
function merge_user_lang()
{
	global $user, $lang;

	$user->lang = array_merge($user->lang, $lang);

	return true;
}

/**
* Stopwords, Synonyms, INIT
*/
function stopwords_synonyms_init()
{
	global $config, $stopwords_array, $synonyms_array;

	if (empty($stopwords_array))
	{
		$stopwords_array = @file(IP_ROOT_PATH . 'language/lang_' . $config['default_lang'] . '/search_stopwords.txt');
	}

	if (empty($synonyms_array))
	{
		$synonyms_array = @file(IP_ROOT_PATH . 'language/lang_' . $config['default_lang'] . '/search_synonyms.txt');
	}
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
function setup_style($style_id, $current_default_style)
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
			if ($style_id != $config['default_style'])
			{
				$config['default_style'] = $current_default_style;
				$style_id = (int) $config['default_style'];
				$template_row = get_style($style_id, true);
				$style_exists = !empty($template_row) ? true : false;

				if ($style_exists)
				{
					$sql = "UPDATE " . USERS_TABLE . "
						SET user_style = " . (int) $config['default_style'] . "
						WHERE user_style = '" . $style_id . "'";
					$result = $db->sql_query($sql);
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

/**
* Setup the mobile style
*/
function setup_mobile_style()
{
	global $db, $config, $template, $images;

	$row = array(
		'themes_id' => 0,
		'template_name' => 'mobile',
		'style_name' => 'Mobile',
		'head_stylesheet' => 'style_white.css',
		'body_background' => 'white',
		'body_bgcolor' => '',
		'tr_class1' => 'row1',
		'tr_class2' => 'row2',
		'tr_class3' => 'row3',
		'td_class1' => 'row1',
		'td_class2' => 'row2',
		'td_class3' => 'row3',
	);
	$template_path = 'templates/';
	$template_name = $row['template_name'];

	$template = new Template(IP_ROOT_PATH . $template_path . $template_name);

	if ($template)
	{
		$current_template_path = $template_path . $template_name;
		$current_template_cfg = IP_ROOT_PATH . $template_path . $template_name . '/' . $template_name . '.cfg';
		@include($current_template_cfg);

		if (!defined('TEMPLATE_CONFIG'))
		{
			message_die(CRITICAL_ERROR, "Could not open $current_template_cfg", '', __LINE__, __FILE__);
		}
	}

	return $row;
}

/*
* Checks if a style exists
*/
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

/*
* Get forum id for a post
*/
function get_forum_topic_id_post($post_id)
{
	global $db, $cache, $config;
	$post_data = array();
	$sql = "SELECT forum_id, topic_id FROM " . POSTS_TABLE . " WHERE post_id = '" . (int) $post_id . "' LIMIT 1";
	$result = $db->sql_query($sql);
	$post_data = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	return $post_data;
}

/*
* Check if a user is allowed to view IP addresses
*/
function ip_display_auth($user_data, $is_forum = false)
{
	global $config;

	$is_mod = ($user_data['user_level'] == MOD) ? true : false;
	if (!empty($is_forum))
	{
		global $is_auth;
		$is_mod = !empty($is_auth['auth_mod']) ? MOD : USER;
	}

	$ip_display_auth = (($user_data['user_level'] == ADMIN) || (empty($config['ip_admins_only']) && !empty($is_mod))) ? true : false;

	return $ip_display_auth;
}

/*
* Encode IP addresses to HEX
*/
function encode_ip($dotquad_ip)
{
	$ip_sep = explode('.', $dotquad_ip);
	return sprintf('%02x%02x%02x%02x', $ip_sep[0], $ip_sep[1], $ip_sep[2], $ip_sep[3]);
}

/*
* Decode IP addresses from HEX
*/
function decode_ip($int_ip)
{
	$hexipbang = explode('.', chunk_split($int_ip, 2, '.'));
	return hexdec($hexipbang[0]) . '.' . hexdec($hexipbang[1]) . '.' . hexdec($hexipbang[2]) . '.' . hexdec($hexipbang[3]);
}

/*
* Create calendar timestamp from timezone
*/
function cal_date($gmepoch, $tz)
{
	global $config;
	return (@strtotime(gmdate('M d Y H.i.s', $gmepoch + (3600 * $tz))));
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

/*
* strutime function which should convert some time formats into fragments of time
* (basic idea taken from here: http://it.php.net/manual/en/function.strptime.php)
*/
function strutime($date, $format)
{
	$masks = array(
		'Y' => '(?P<Y>[0-9]{4})',
		'm' => '(?P<m>[0-9]{2})',
		'd' => '(?P<d>[0-9]{2})',
		'H' => '(?P<H>[0-9]{2})',
		'M' => '(?P<M>[0-9]{2})',
		'S' => '(?P<S>[0-9]{2})',
	);

	$rexep = "#" . strtr(preg_quote($format), $masks) . "#";
	if(!preg_match($rexep, $date, $out))
	{
		return false;
	}

	$ret = array(
		'year' => !empty($out['Y']) ? (int) $out['Y'] : 0,
		'month' => !empty($out['m']) ? (int) $out['m'] : 0,
		'day' => !empty($out['d']) ? (int) $out['d'] : 0,
		'hour' => !empty($out['H']) ? (int) $out['H'] : 0,
		'minute' => !empty($out['M']) ? (int) $out['M'] : 0,
		'second' => !empty($out['S']) ? (int) $out['S'] : 0,
	);

	return $ret;
}

/*
* Get DST
*/
function get_dst($gmepoch, $tz = 0)
{
	global $config, $user;

	$tz = empty($tz) ? $config['board_timezone'] : $tz;
	if (!empty($user->data) && !$user->data['session_logged_in'])
	{
		$user->data['user_time_mode'] = $config['default_time_mode'];
		$user->data['user_dst_time_lag'] = $config['default_dst_time_lag'];
	}
	elseif (!empty($user->data))
	{
		$config['default_time_mode'] = $user->data['user_time_mode'];
		$config['default_dst_time_lag'] = $user->data['user_dst_time_lag'];
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

/*
* Create date/time using the specified format and timezone
*/
function create_date($format, $gmepoch, $tz = 0)
{
	global $config, $user, $lang;
	static $translate;

	$tz = empty($tz) ? $config['board_timezone'] : $tz;
	// We need to force this ==> isset($lang['datetime']) <== otherwise we may have $lang initialized and we don't want that...
	if (empty($translate) && ($config['default_lang'] != 'english') && isset($lang['datetime']))
	{
		$use_short_names = false;
		if (((strpos($format, '\M') === false) && (strpos($format, 'M') !== false)) || ((strpos($format, '\r') === false) && (strpos($format, 'r') !== false)))
		{
			$use_short_names = true;
		}
		@reset($lang['datetime']);
		while (list($match, $replace) = @each($lang['datetime']))
		{
			$var_name = $match;
			if ((strpos($match, '_short') !== false) && $use_short_names)
			{
				$var_name = str_replace('_short', '', $match);
			}
			$translate[$var_name] = $replace;
		}
	}

	$dst_sec = get_dst($gmepoch, $tz);
	$date = @gmdate($format, $gmepoch + (3600 * $tz) + $dst_sec);
	$date = (!empty($translate) ? strtr($date, $translate) : $date);
	return $date;
}

/*
* Create midnight time for a date
*/
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

/*
* Create date/time using the specified format and timezone
*/
function create_date_ip($format, $gmepoch, $tz = 0, $day_only = false)
{
	global $config, $lang;

	$tz = empty($tz) ? $config['board_timezone'] : $tz;
	$midnight = create_date_midnight($gmepoch, $tz);

	$output_date = '';
	$time_sep = !empty($lang['NUMBER_FORMAT_TIME_SEP']) ? $lang['NUMBER_FORMAT_TIME_SEP'] : ':';
	$format_hour = 'H' . $time_sep . 'i';
	if (($gmepoch >= $midnight) && ($gmepoch < ($midnight + 86400)))
	{
		$format = ($day_only) ? $format : $format_hour;
		$output_date = ($day_only) ? $lang['TODAY'] : ($lang['Today_at'] . ' ');
	}
	elseif (($gmepoch < $midnight) && ($gmepoch >= ($midnight - 86400)))
	{
		$format = ($day_only) ? $format : $format_hour;
		$output_date = ($day_only) ? $lang['YESTERDAY'] : ($lang['Yesterday_at'] . ' ');
	}
	$output_date = $output_date . (($day_only && !empty($output_date)) ? '' : create_date($format, $gmepoch, $tz));
	return $output_date;
}

/*
* Converts time fragments to MySQL dates ready for DB storage
*/
function create_date_mysql_db($date_fragments, $format)
{
	$date_fragments = array(
		'year' => !empty($date_fragments['year']) ? (string) $date_fragments['year'] : '0000',
		'month' => !empty($date_fragments['month']) ? (string) $date_fragments['month'] : '00',
		'day' => !empty($date_fragments['day']) ? (string) $date_fragments['day'] : '00',
		'hour' => !empty($date_fragments['hour']) ? (string) $date_fragments['hour'] : '00',
		'minute' => !empty($date_fragments['minute']) ? (string) $date_fragments['minute'] : '00',
		'second' => !empty($date_fragments['second']) ? (string) $date_fragments['second'] : '00',
	);

	$date_sep = '-';
	$time_sep = ':';
	$mysql_date = $date_fragments['year'] . $date_sep . $date_fragments['month'] . $date_sep . $date_fragments['day'];
	$mysql_time = $date_fragments['hour'] . $time_sep . $date_fragments['minute'] . $time_sep . $date_fragments['second'];

	switch ($format)
	{
		case 'date':
			$mysql_date_db = $mysql_date;
			break;

		case 'time':
			$mysql_date_db = $mysql_time;
			break;

		default:
			$mysql_date_db = $mysql_date . ' ' . $mysql_time;
			break;
	}

	return $mysql_date_db;
}

/*
* Convert unix to MySQL dates
*/
function create_date_mysql($time, $output = 'datetime', $tz = false)
{
	global $config, $lang;

	$tz = ($tz === false) ? $config['board_timezone'] : $tz;

	switch ($output)
	{
		case 'date':
			$mysql_date = create_date('Y-m-d', $time, $tz);
			break;

		case 'time':
			$mysql_date = create_date('H:i:s', $time, $tz);
			break;

		default:
			$mysql_date = create_date('Y-m-d H:i:s', $time, $tz);
			break;
	}

	return $mysql_date;
}

/*
* Format MySQL dates
*/
function format_date_mysql_php($mysql_date, $format = 'datetime', $output = 'mysql')
{
	global $config, $lang;

	$date_format = $lang['DATE_FORMAT_DATE_MYSQL_PHP'];
	$mysql_date_sep = ((empty($lang['NUMBER_FORMAT_DATE_SEP']) || ($output == 'mysql')) ? '-' : $lang['NUMBER_FORMAT_DATE_SEP']);
	$mysql_time_sep = ((empty($lang['NUMBER_FORMAT_TIME_SEP']) || ($output == 'mysql')) ? ':' : $lang['NUMBER_FORMAT_TIME_SEP']);

	if (($format == 'datetime') || ($format == 'date'))
	{
		// Mighty Gorgon: we suppose dates are always with leading zeroes and in one of the following formats: dd/mm/yyyy, mm/dd/yyyy, yyyy/mm/dd
		switch ($date_format)
		{
			case 'dmy':
				$mysql_date_only = ($output == 'mysql') ? (substr($mysql_date, 6, 4) . $mysql_date_sep . substr($mysql_date, 3, 2) . $mysql_date_sep . substr($mysql_date, 0, 2)) : (substr($mysql_date, 8, 2) . $mysql_date_sep . substr($mysql_date, 5, 2) . $mysql_date_sep . substr($mysql_date, 0, 4));
			break;

			case 'mdy':
				$mysql_date_only = ($output == 'mysql') ? (substr($mysql_date, 6, 4) . $mysql_date_sep . substr($mysql_date, 0, 2) . $mysql_date_sep . substr($mysql_date, 3, 2)) : (substr($mysql_date, 5, 2) . $mysql_date_sep . substr($mysql_date, 8, 2) . $mysql_date_sep . substr($mysql_date, 0, 4));
			break;

			case 'ymd':
			default:
				$mysql_date_only = ($output == 'mysql') ? (substr($mysql_date, 0, 4) . $mysql_date_sep . substr($mysql_date, 5, 2) . $mysql_date_sep . substr($mysql_date, 8, 2)) : (substr($mysql_date, 0, 4) . $mysql_date_sep . substr($mysql_date, 5, 2) . $mysql_date_sep . substr($mysql_date, 8, 2));
			break;
		}
	}

	switch ($format)
	{
		case 'date':
			$mysql_date = $mysql_date_only;
			break;

		case 'time':
			// Mighty Gorgon: we suppose time is always in the following format: hh:mm:ss
			$mysql_date = empty($mysql_date) ? ('00' . $mysql_time_sep . '00' . $mysql_time_sep . '00') : (substr($mysql_date, 0, 2) . $mysql_time_sep . substr($mysql_date, 3, 2) . $mysql_time_sep . substr($mysql_date, 6, 2));
			break;

		default:
			$mysql_date = $mysql_date_only . ' ' . substr($mysql_date, 12, 2) . $mysql_time_sep . substr($mysql_date, 15, 2) . $mysql_time_sep . substr($mysql_date, 18, 2);
			break;
	}

	return $mysql_date;
}

/*
* Convert time in hours in decimal format
*/
function convert_time_to_decimal($time, $time_sep = ':')
{
	$time_decimal = $time;
	$time_fragments = explode($time_sep, $time);
	if (sizeof($time_fragments) > 1)
	{
		$time_decimal = (int) $time_fragments[0] + ((int) $time_fragments[1] / 60);
		if (sizeof($time_fragments) == 3)
		{
			$time_decimal = $time_decimal + ((int) $time_fragments[2] / 3600);
		}
	}

	return $time_decimal;
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
function realdate($date_syntax = 'Ymd', $date = 0)
{
	global $config;

	$unix_time = ($date * 86400) + 1;
	// Since we are using create_date, we need to adjust time back by timezone and dst to avoid date change...
	$zone_offset = (3600 * $config['board_timezone']) + get_dst($unix_time, $config['board_timezone']);
	$unix_time = $unix_time - $zone_offset;

	return create_date($date_syntax, $unix_time, $config['board_timezone']);
}
// Birthday - END

/*
* Format file size
*/
function format_file_size($filesize)
{
	global $lang;

	$filesize = (int) $filesize;
	if($filesize >= 1073741824)
	{
		$filesize = sprintf('%.2f ' . $lang['GB'], ($filesize / 1073741824));
	}
	elseif($filesize >= 1048576)
	{
		$filesize = sprintf('%.2f ' . $lang['MB'], ($filesize / 1048576));
	}
	elseif($filesize >= 1024)
	{
		$filesize = sprintf('%.2f ' . $lang['KB'], ($filesize / 1024));
	}
	else
	{
		$filesize = sprintf('%.2f ' . $lang['Bytes'], $filesize);
	}

	return $filesize;
}

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
* Generate topic pagination
*/
function generate_topic_pagination($forum_id, $topic_id, $replies, $per_page = 0)
{
	global $config, $template, $images, $lang;

	$per_page = (!empty($per_page) && intval($per_page)) ? intval($per_page) : $config['posts_per_page'];
	$topic_pagination = array();
	$topic_pagination['base'] = '';
	$topic_pagination['full'] = '';

	$url_append = '';
	$url_append .= (empty($url_append) ? '' : '&amp;') . ((!empty($forum_id) ? (POST_FORUM_URL . '=' . $forum_id) : ''));
	$url_append .= (empty($url_append) ? '' : '&amp;') . ((!empty($topic_id) ? (POST_TOPIC_URL . '=' . $topic_id) : ''));

	if(($replies + 1) > $per_page)
	{
		$total_pages = ceil(($replies + 1) / $per_page);
		$goto_page_prefix = ' [';
		$goto_page = ' <img src="' . $images['icon_gotopage'] . '" alt="' . $lang['Goto_page'] . '" title="' . $lang['Goto_page'] . '" />&nbsp;';
		$times = '1';
		for($j = 0; $j < $replies + 1; $j += $per_page)
		{
			$goto_page .= '<a href="' . append_sid(CMS_PAGE_VIEWTOPIC . '?' . $url_append . '&amp;start=' . $j) . '" title="' . $lang['Goto_page'] . ' ' . $times . '"><b>' . $times . '</b></a>';
			if(($times == 1) && ($total_pages > 4))
			{
				$goto_page .= ' ... ';
				$times = $total_pages - 3;
				$j += ($total_pages - 4) * $per_page;
			}
			elseif($times < $total_pages)
			{
				//$goto_page .= ', ';
				$goto_page .= ' ';
			}
			$times++;
		}
		$goto_page_suffix = ' ]';
		$goto_page .= ' ';

		$topic_pagination['base'] = '<span class="gotopage">' . $goto_page . '</span>';
		$topic_pagination['full'] = '<span class="gotopage">' . $goto_page_prefix . ' ' . $lang['Goto_page'] . $goto_page . $goto_page_suffix . '</span>';
	}
	else
	{
		$topic_pagination['base'] = '&nbsp;';
		$topic_pagination['full'] = '&nbsp;';
	}

	return $topic_pagination;
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

/**
* Generate zebra rows
*/
function ip_zebra_rows($row_class)
{
	global $theme;
	$row1_class = (!empty($theme['td_class1']) ? $theme['td_class1'] : 'row1');
	$row2_class = (!empty($theme['td_class2']) ? $theme['td_class2'] : 'row2');
	$row_class = (empty($row_class) || ($row_class == $row2_class)) ? $row1_class : $row2_class;
	return $row_class;
}

/*
* This does exactly what preg_quote() does in PHP 4-ish
* If you just need the 1-parameter preg_quote call, then don't bother using this.
*/
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
		global $user, $cache;

		// We check here if the user is having viewing censors disabled (and also allowed to do so).
		if ($user->data['user_allowswearywords'])
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
	global $db, $cache, $config;

	$config_updated = false;
	// max users
	$sql = "SELECT COUNT(user_id) AS user_total FROM " . USERS_TABLE . " WHERE user_id > 0";
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	$max_users = (int) $row['user_total'];

	// update
	if ($config['max_users'] != $max_users)
	{
		set_config('max_users', $max_users);
	}

	// newest user
	$sql_active_users = empty($config['inactive_users_memberlists']) ? ' AND user_active = 1 ' : '';
	$sql = "SELECT user_id, username
		FROM " . USERS_TABLE . "
		WHERE user_id <> " . ANONYMOUS . "
		$sql_active_users
		ORDER BY user_id DESC
		LIMIT 1";
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	$newest_user_id = (int) $row['user_id'];

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
	$db->sql_freeresult($result);
	$max_topics = (int) $row['topic_total'];
	$max_posts = (int) $row['post_total'];

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
* Check if the browser is from a mobile device
*/
function is_mobile()
{
	global $config, $user;

	if (!empty($config['mobile_style_disable']))
	{
		return false;
	}

	if (!empty($user) && !empty($user->data['is_mobile']))
	{
		return true;
	}

	if (!empty($user) && !empty($user->data['is_bot']))
	{
		$user->data['is_mobile'] = false;
		return false;
	}

	if (!empty($user)) $user->data['is_mobile'] = false;

	$browser = (!empty($user) && !empty($user->browser)) ? $user->browser : (!empty($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '');
	$browser_lc = strtolower($browser);

	if (strpos($browser_lc, 'windows') !== false)
	{
		return false;
	}

	// Just a quick check on most common browsers...
	if(preg_match('/(android|blackberry|fennec|htc_|iphone|ipod|mobile|midp|mmp|opera mini|opera mobi|phone|symbian|smartphone|up.browser|up.link|wap)/i', $browser_lc))
	{
		if (!empty($user)) $user->data['is_mobile'] = true;
		return true;
	}

	if((strpos(strtolower($_SERVER['HTTP_ACCEPT']), 'application/vnd.wap.xhtml+xml') !== false) || ((isset($_SERVER['HTTP_X_WAP_PROFILE']) || isset($_SERVER['HTTP_PROFILE']))))
	{
		if (!empty($user)) $user->data['is_mobile'] = true;
		return true;
	}

	$mobile_ua = substr($browser_lc, 0, 4);
	$mobile_agents = array(
		'w3c ', 'acs-', 'alav', 'alca', 'amoi', 'audi', 'avan', 'benq', 'bird', 'blac',
		'blaz', 'brew', 'cell', 'cldc', 'cmd-', 'dang', 'doco', 'eric', 'hipt', 'inno',
		'ipaq', 'java', 'jigs', 'kddi', 'keji', 'leno', 'lg-c', 'lg-d', 'lg-g', 'lge-',
		'maui', 'maxo', 'midp', 'mits', 'mmef', 'mobi', 'mot-', 'moto', 'mwbp', 'nec-',
		'newt', 'noki', 'palm', 'pana', 'pant', 'phil', 'play', 'port', 'prox', 'qwap',
		'sage', 'sams', 'sany', 'sch-', 'sec-', 'send', 'seri', 'sgh-', 'shar', 'sie-',
		'siem', 'smal', 'smar', 'sony', 'sph-', 'symb', 't-mo', 'teli', 'tim-', 'tosh',
		'tsm-', 'upg1', 'upsi', 'vk-v', 'voda', 'wap-', 'wapa', 'wapi', 'wapp', 'wapr',
		'webc', 'winw', 'winw', 'xda', 'xda-'
	);

	if(in_array($mobile_ua, $mobile_agents))
	{
		if (!empty($user)) $user->data['is_mobile'] = true;
		return true;
	}

	if (strpos(strtolower($_SERVER['ALL_HTTP']), 'OperaMini') !== false)
	{
		if (!empty($user)) $user->data['is_mobile'] = true;
		return true;
	}

	// OLD Basic Code
	/*
	if ((strpos($browser, 'Mobile') !== false) || (strpos($browser, 'Symbian') !== false) || (strpos($browser, 'Opera M') !== false) || (strpos($browser, 'Android') !== false) || (stripos($browser, 'HTC_') !== false) || (strpos($browser, 'Fennec/') !== false) || (stripos($browser, 'Blackberry') !== false))
	*/

	// Full big preg_match!
	/*
	if(preg_match('/android|avantgo|blackberry|blazer|compal|elaine|fennec|hiptop|htc_|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mobile|mmp|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $browser) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|e\-|e\/|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(di|rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|xda(\-|2|g)|yas\-|your|zeto|zte\-/i', substr($browser, 0, 4)))
	{
		if (!empty($user)) $user->data['is_mobile'] = true;
		return true;
	}
	*/

	return false;
}

/*
* MG BOTS Parsing Function
*/
function bots_parse($ip_address, $bot_color = '#888888', $browser = false, $check_inactive = false)
{
	global $db, $lang;
	/*
	// Testing!!!
	$browser = 'mgbot/ 1.0';
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
			return array('name' => false, 'id' => 0);
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
			if (!empty($check_inactive) && ($active_bots[$i]['bot_active'] == 0))
			{
				if (!defined('STATUS_503')) define('STATUS_503', true);
				message_die(GENERAL_ERROR, $lang['Not_Authorized']);
			}
			return array('name' => $bot_name, 'id' => $active_bots[$i]['bot_id']);
		}

		if (!empty($ip_address) && !empty($active_bots[$i]['bot_ip']))
		{
			foreach (explode(',', $active_bots[$i]['bot_ip']) as $bot_ip)
			{
				$bot_ip = trim($bot_ip);
				if (!empty($bot_ip) && (strpos($ip_address, $bot_ip) === 0))
				{
					$bot_name = (!empty($active_bots[$i]['bot_color']) ? $active_bots[$i]['bot_color'] : ('<b style="color:' . $bot_color . '">' . $active_bots[$i]['bot_name'] . '</b>'));
					if (!empty($check_inactive) && ($active_bots[$i]['bot_active'] == 0))
					{
						if (!defined('STATUS_503')) define('STATUS_503', true);
						message_die(GENERAL_ERROR, $lang['Not_Authorized']);
					}
					return array('name' => $bot_name, 'id' => $active_bots[$i]['bot_id']);
				}
			}
		}
	}

	return array('name' => false, 'id' => 0);
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

	return true;
}

// Ajaxed - BEGIN
function AJAX_headers($json = false)
{
	//No caching whatsoever
	if (empty($json))
	{
		header('Content-Type: application/xml');
	}
	header('Expires: Thu, 15 Aug 1984 13:30:00 GMT');
	header('Last-Modified: '. gmdate('D, d M Y H:i:s') .' GMT');
	header('Cache-Control: no-cache, must-revalidate');  // HTTP/1.1
	header('Pragma: no-cache');                          // HTTP/1.0
}

function AJAX_message_die($data_ar, $json = false)
{
	global $template, $db, $cache, $config;

	if (!headers_sent())
	{
		AJAX_headers($json);
	}

	if (!empty($json))
	{
		echo(json_encode($data_ar));
	}
	else
	{
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
					'VALUE' => $value
					)
				);
			}
		}

		$template->pparse('ajax_result');
	}

	garbage_collection();
	exit_handler();
	exit;
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
	$sql = "SELECT u.username, u.user_active, u.user_mask, u.user_color, u.group_id
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
function colorize_username($user_id, $username = '', $user_color = '', $user_active = true, $no_profile = false, $get_only_color_style = false, $from_db = false, $force_cache = false, $alt_link_url = '')
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
		$user_mask = (empty($row['user_active']) && !empty($row['user_mask'])) ? true : false;
		if (!empty($user_mask))
		{
			global $user;
			$user_mask = ($user->data['user_level'] == ADMIN) ? false : true;
		}
		$user_id = $user_mask ? ANONYMOUS : $user_id;
		$username = $user_mask ? $lang['INACTIVE_USER'] : $row['username'];
		$user_color = $user_mask ? '' : $row['user_color'];
		$user_active = $row['user_active'];
	}

	$username = (($user_id == ANONYMOUS) || empty($username)) ? $lang['Guest'] : str_replace('&amp;amp;', '&amp;', htmlspecialchars($username));
	$user_link_url = !empty($alt_link_url) ? str_replace('$USER_ID', $user_id, $alt_link_url) : ((defined('USER_LINK_URL_OVERRIDE')) ? str_replace('$USER_ID', $user_id, USER_LINK_URL_OVERRIDE) : (CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $user_id));
	$user_link_style = '';
	$user_link_begin = '<a href="' . append_sid(IP_ROOT_PATH . $user_link_url) . '"';
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

function user_get_avatar($user_id, $user_level, $user_avatar, $user_avatar_type, $user_allow_avatar, $path_prefix = '', $max_width = 0)
{
	global $config;

	$user_avatar_path = '';
	$user_avatar_link = '';
	$user_avatar_dim = '';

	if ($user_avatar_type && ($user_id != ANONYMOUS) && $user_allow_avatar)
	{
		switch($user_avatar_type)
		{
			case USER_AVATAR_UPLOAD:
				$user_avatar_path = ($config['allow_avatar_upload']) ? ($path_prefix . $config['avatar_path'] . '/' . $user_avatar) : '';
				break;
			case USER_AVATAR_REMOTE:
				$user_avatar_path = $user_avatar;
				if ($user_level != ADMIN)
				{
					// Set this to false if you want to force height as well
					$force_width_only = true;

					$avatar_width = $config['avatar_max_width'];
					$avatar_height = $config['avatar_max_height'];

					if (!empty($config['allow_avatar_remote']))
					{
						$user_avatar_dim = ' width="' . $avatar_width . '"' . (($force_width_only) ? '' : (' height="' . $avatar_height . '"'));
						$user_avatar_path = $user_avatar;
					}
					else
					{
						$user_avatar_path = '';
					}
				}
				break;
			case USER_AVATAR_GALLERY:
				$user_avatar_path = ($config['allow_avatar_local']) ? ($path_prefix . $config['avatar_gallery_path'] . '/' . $user_avatar) : '';
				break;
			case USER_GRAVATAR:
				$user_avatar_path = ($config['enable_gravatars']) ? get_gravatar($user_avatar) : '';
				break;
			case USER_AVATAR_GENERATOR:
				$user_avatar_path = ($config['allow_avatar_generator']) ? $user_avatar : '';
				break;
			default:
				$user_avatar_path = '';
		}
	}

	if (empty($user_avatar_path))
	{
		$user_avatar_path = get_default_avatar($user_id, $path_prefix);
	}

	if (!empty($max_width))
	{
		$max_width = (int) $max_width;
		if (($max_width > 10) && ($max_width < 240))
		{
			$user_avatar_dim = ' style="width: ' . $max_width . 'px; max-width: ' . $max_width . 'px;"';
		}
	}
	$avatar_class = (($max_width > 10) && ($max_width < 40)) ? '' : ' class="avatar"';
	$user_avatar_link = (!empty($user_avatar_path)) ? '<img src="' . $user_avatar_path . '" alt="avatar"' . $avatar_class . $user_avatar_dim . ' />' : '&nbsp;';

	return $user_avatar_link;
}

function get_default_avatar($user_id, $path_prefix = '')
{
	global $config;

	$avatar_img = '';
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

	$avatar_img = $path_prefix . $avatar_img;

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

/*
* Gets all social networks and instant messaging (SN/IM) fields feeded only from profile table (doesn't get chat id...)
* This function should simplify adding/removing SN/IM fields to user profile
*/
function get_user_sn_im_array()
{
	$user_sn_im_array = array(
		'500px' => array('field' => 'user_500px', 'lang' => '500PX', 'icon_tpl' => '', 'icon_tpl_vt' => '', 'url' => '{REF}', 'alt_name' => '500px', 'form' => '500px'),
		'aim' => array('field' => 'user_aim', 'lang' => 'AIM', 'icon_tpl' => 'icon_aim', 'icon_tpl_vt' => 'icon_aim2', 'url' => 'aim:goim?screenname={REF}&amp;message=Hello', 'alt_name' => 'aim', 'form' => 'aim'),
		'facebook' => array('field' => 'user_facebook', 'lang' => 'FACEBOOK', 'icon_tpl' => '', 'icon_tpl_vt' => '', 'url' => '{REF}', 'alt_name' => 'facebook', 'form' => 'facebook'),
		'flickr' => array('field' => 'user_flickr', 'lang' => 'FLICKR', 'icon_tpl' => '', 'icon_tpl_vt' => '', 'url' => '{REF}', 'alt_name' => 'flickr', 'form' => 'flickr'),
		'github' => array('field' => 'user_github', 'lang' => 'GITHUB', 'icon_tpl' => '', 'icon_tpl_vt' => '', 'url' => '{REF}', 'alt_name' => 'github', 'form' => 'github'),
		'googleplus' => array('field' => 'user_googleplus', 'lang' => 'GOOGLEPLUS', 'icon_tpl' => '', 'icon_tpl_vt' => '', 'url' => '{REF}', 'alt_name' => 'googleplus', 'form' => 'googleplus'),
		'icq' => array('field' => 'user_icq', 'lang' => 'ICQ', 'icon_tpl' => 'icon_icq', 'icon_tpl_vt' => 'icon_icq2', 'url' => 'http://www.icq.com/people/webmsg.php?to={REF}', 'alt_name' => 'icq', 'form' => 'icq'),
		'instagram' => array('field' => 'user_instagram', 'lang' => 'INSTAGRAM', 'icon_tpl' => '', 'icon_tpl_vt' => '', 'url' => '{REF}', 'alt_name' => 'instagram', 'form' => 'instagram'),
		'jabber' => array('field' => 'user_jabber', 'lang' => 'JABBER', 'icon_tpl' => '', 'icon_tpl_vt' => '', 'url' => '{REF}', 'alt_name' => 'jabber', 'form' => 'jabber'),
		'linkedin' => array('field' => 'user_linkedin', 'lang' => 'LINKEDIN', 'icon_tpl' => '', 'icon_tpl_vt' => '', 'url' => '{REF}', 'alt_name' => 'linkedin', 'form' => 'linkedin'),
		'msn' => array('field' => 'user_msnm', 'lang' => 'MSNM', 'icon_tpl' => 'icon_msnm', 'icon_tpl_vt' => 'icon_msnm2', 'url' => 'http://spaces.live.com/{REF}', 'alt_name' => 'msnm', 'form' => 'msn'),
		'pinterest' => array('field' => 'user_pinterest', 'lang' => 'PINTEREST', 'icon_tpl' => '', 'icon_tpl_vt' => '', 'url' => '{REF}', 'alt_name' => 'pinterest', 'form' => 'pinterest'),
		'skype' => array('field' => 'user_skype', 'lang' => 'SKYPE', 'icon_tpl' => 'icon_skype', 'icon_tpl_vt' => 'icon_skype2', 'url' => 'callto://{REF}', 'alt_name' => 'skype', 'form' => 'skype'),
		'twitter' => array('field' => 'user_twitter', 'lang' => 'TWITTER', 'icon_tpl' => '', 'icon_tpl_vt' => '', 'url' => '{REF}', 'alt_name' => 'twitter', 'form' => 'twitter'),
		'vimeo' => array('field' => 'user_vimeo', 'lang' => 'VIMEO', 'icon_tpl' => '', 'icon_tpl_vt' => '', 'url' => '{REF}', 'alt_name' => 'vimeo', 'form' => 'vimeo'),
		'yahoo' => array('field' => 'user_yim', 'lang' => 'YIM', 'icon_tpl' => 'icon_yim', 'icon_tpl_vt' => 'icon_yim2', 'url' => 'http://edit.yahoo.com/config/send_webmesg?.target={REF}&amp;.src=pg', 'alt_name' => 'yim', 'form' => 'yim'),
		'youtube' => array('field' => 'user_youtube', 'lang' => 'YOUTUBE', 'icon_tpl' => '', 'icon_tpl_vt' => '', 'url' => '{REF}', 'alt_name' => 'youtube', 'form' => 'youtube'),
	);

	return $user_sn_im_array;
}

/*
* This function will build a complete IM link with image and lang
*/
function build_im_link($im_type, $user_data, $im_icon_type = false, $im_img = false, $im_url = false, $im_status = false, $im_lang = false)
{
	global $config, $user, $lang, $images;

	$available_im = get_user_sn_im_array();
	$extra_im = array(
		'chat' => array('field' => 'user_id', 'lang' => 'AJAX_SHOUTBOX_PVT_LINK', 'icon_tpl' => 'icon_im_chat', 'icon_tpl_vt' => 'icon_im_chat', 'url' => '{REF}')
	);
	$available_im = array_merge($available_im, $extra_im);

	// Default values
	$im_icon = '';
	$im_icon_append = '';
	if (!empty($user_data[$available_im[$im_type]['field']]))
	{
		$im_id = $user_data[$available_im[$im_type]['field']];
		$im_ref = $im_id;
	}
	else
	{
		return '';
	}

	if (!empty($im_status) && in_array($im_type, array('chat')) && in_array($im_status, array('online', 'offline', 'hidden')))
	{
		$im_icon_append = '_' . $im_status;
	}

	if (!empty($available_im[$im_type]))
	{
		if (!empty($im_icon_type) && in_array($im_icon_type, array('icon', 'icon_tpl', 'icon_tpl_vt')))
		{
			if ($im_icon_type == 'icon')
			{
				$im_icon = $images['icon_im_' . $im_type . $im_icon_append];
			}
			else
			{
				$im_icon = $images[$available_im[$im_type][$im_icon_type]];
			}
		}

		$im_ref = str_replace('{REF}', $im_ref, $available_im[$im_type]['url']);
		if ($im_type == 'chat')
		{
			// JHL: No chat icon if the user is anonymous, or the profiled user is offline
			if (empty($user->data['session_logged_in']) || empty($user_data['user_session_time']) || ($user_data['user_session_time'] < (time() - $config['online_time'])))
			{
				return '';
			}

			$ajax_chat_page = !empty($config['ajax_chat_link_type']) ? CMS_PAGE_AJAX_CHAT : CMS_PAGE_AJAX_SHOUTBOX;
			$ajax_chat_room = 'chat_room=' . (min($user->data['user_id'], $user_data['user_id']) . '|' . max($user->data['user_id'], $user_data['user_id']));
			$ajax_chat_link = append_sid($ajax_chat_page . '?' . $ajax_chat_room);

			$im_ref = !empty($config['ajax_chat_link_type']) ? ($ajax_chat_link . '" target="_chat') : ('#" onclick="window.open(\'' . $ajax_chat_link . '\', \'_chat\', \'width=720,height=600,resizable=yes\'); return false;');
		}

		$im_img = (!empty($im_img) && !empty($im_icon)) ? $im_icon : false;
		$im_lang = !empty($im_lang) ? $im_lang : (!empty($available_im[$im_type]['lang']) ? $lang[$available_im[$im_type]['lang']] : '');
	}

	$link_title = ($im_type == 'chat') ? '' : (' - ' . $im_id);
	$link_title = $im_lang . $link_title;
	$link_content = !empty($im_img) ? ('<img src="' . $im_img . '" alt="' . $im_lang . '"' . (empty($im_url) ? '' : (' title="' . $im_id . '"')) . ' />') : $im_lang;
	$im_link = !empty($im_url) ? $im_ref : ('<a href="' . $im_ref . '" title="' . $link_title . '">' . $link_content . '</a>');

	return $im_link;
}

/*
* Get AD
*/
function get_ad($ad_position)
{
	global $db, $config, $user;

	$ad_text = '';
	if (!$config['ads_' . $ad_position])
	{
		return $ad_text;
	}

	$user_auth = AUTH_ALL;
	$user_level = ($user->data['user_id'] == ANONYMOUS) ? ANONYMOUS : $user->data['user_level'];
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
		$ad_text = $active_ads[$selected_ad]['ad_text'];
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
	global $config;

	$skip_files = array(
		'.',
		'..',
		'.htaccess',
		'index.htm',
		'index.html',
		'index.' . PHP_EXT,
	);

	$cache_dirs_array = array(POSTED_IMAGES_THUMBS_PATH);
	if (!empty($config['plugins']['album']['enabled']))
	{
		$cache_dirs_array = array_merge($cache_dirs_array, array(
			IP_ROOT_PATH . ALBUM_CACHE_PATH,
			IP_ROOT_PATH . ALBUM_MED_CACHE_PATH,
			IP_ROOT_PATH . ALBUM_WM_CACHE_PATH
		));
	}

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

// Handler, header and footer

/**
* Page Header
*/
function page_header($title = '', $parse_template = false)
{
	global $db, $cache, $config, $user, $template, $images, $theme, $lang, $tree;
	global $table_prefix, $SID, $_SID;
	global $ip_cms, $cms_config_vars, $cms_config_global_blocks, $cms_config_layouts, $cms_page;
	global $starttime, $base_memory_usage, $do_gzip_compress, $start;
	global $gen_simple_header, $meta_content, $nav_separator, $nav_links, $nav_pgm, $nav_add_page_title, $skip_nav_cat;
	global $breadcrumbs;
	global $forum_id, $topic_id;

	if (defined('HEADER_INC'))
	{
		return;
	}

	define('HEADER_INC', true);

	// gzip_compression
	$config['gzip_compress_runtime'] = (isset($config['gzip_compress_runtime']) ? $config['gzip_compress_runtime'] : $config['gzip_compress']);
	$config['url_rw_runtime'] = ($config['url_rw'] || ($config['url_rw_guests'] && ($user->data['user_id'] == ANONYMOUS))) ? true : false;

	if ($config['gzip_compress_runtime'])
	{
		if (@extension_loaded('zlib') && !headers_sent())
		{
			ob_start('ob_gzhandler');
		}
	}
	else
	{
		// We need to enable this otherwise URL Rewrite will not work without output buffering
		if ($config['url_rw_runtime'] && !headers_sent())
		{
			ob_start();
		}
	}

	// CMS
	if(!defined('CMS_INIT'))
	{
		define('CMS_INIT', true);
		$cms_config_vars = $cache->obtain_cms_config();
		$cms_config_global_blocks = $cache->obtain_cms_global_blocks_config(false);
	}

	$server_url = create_server_url();
	$page_url = pathinfo($_SERVER['SCRIPT_NAME']);
	$page_query = $_SERVER['QUERY_STRING'];

	$meta_content['page_title'] = !empty($title) ? $title : $meta_content['page_title'];
	$meta_content['page_title'] = empty($meta_content['page_title']) ? $config['sitename'] : strip_tags($meta_content['page_title']);
	$meta_content['page_title_clean'] = empty($meta_content['page_title_clean']) ? strip_tags($meta_content['page_title']) : $meta_content['page_title_clean'];

	// DYNAMIC META TAGS - BEGIN
	// Reset some defaults... to be sure some values are taken from DB properly
	$lang['Default_META_Keywords'] = (!empty($config['site_meta_keywords_switch']) && !empty($config['site_meta_keywords'])) ? $config['site_meta_keywords'] : (!empty($lang['Default_META_Keywords']) ? $lang['Default_META_Keywords'] : strtolower(htmlspecialchars(strip_tags($config['sitename']))));
	$lang['Default_META_Description'] = (!empty($config['site_meta_description_switch']) && !empty($config['site_meta_description'])) ? $config['site_meta_description'] : (!empty($lang['Default_META_Description']) ? $lang['Default_META_Description'] : htmlspecialchars(strip_tags($config['site_desc'])));
	$lang['Default_META_Author'] = (!empty($config['site_meta_author_switch']) && !empty($config['site_meta_author'])) ? $config['site_meta_author'] : (!empty($lang['Default_META_Author']) ? $lang['Default_META_Author'] : htmlspecialchars(strip_tags($config['sitename'])));
	$lang['Default_META_Copyright'] = (!empty($config['site_meta_copyright_switch']) && !empty($config['site_meta_copyright'])) ? $config['site_meta_copyright'] : (!empty($lang['Default_META_Copyright']) ? $lang['Default_META_Copyright'] : htmlspecialchars(strip_tags($config['sitename'])));

	$meta_content_pages_array = array(CMS_PAGE_VIEWFORUM, CMS_PAGE_VIEWFORUMLIST, CMS_PAGE_VIEWTOPIC);
	if (!in_array($page_url['basename'], $meta_content_pages_array))
	{
		$meta_content['cat_id'] = request_var(POST_CAT_URL, 0);
		$meta_content['forum_id'] = request_var(POST_FORUM_URL, 0);
		$meta_content['topic_id'] = request_var(POST_TOPIC_URL, 0);
		$meta_content['post_id'] = request_var(POST_POST_URL, 0);

		$no_meta_pages_array = array(CMS_PAGE_LOGIN, CMS_PAGE_PRIVMSG, CMS_PAGE_POSTING, 'kb.' . PHP_EXT);
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

	$phpbb_meta = '';
	$phpbb_meta .= '<meta name="author" content="' . $lang['Default_META_Author'] . '" />' . "\n";
	$phpbb_meta .= '<meta name="description" content="' . str_replace('"', '', $meta_content['description']) . '" />' . "\n";
	$phpbb_meta .= '<meta name="keywords" content="' . str_replace('"', '', $meta_content['keywords']) . '" />' . "\n";
	// These META are not valid and needed anymore by SEO and HTML 5
	/*
	$phpbb_meta .= '<meta name="title" content="' . $meta_content['page_title'] . '" />' . "\n";
	$phpbb_meta .= '<meta name="copyright" content="' . $lang['Default_META_Copyright'] . '" />' . "\n";
	$phpbb_meta .= '<meta name="category" content="general" />' . "\n";
	$phpbb_meta .= '<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7; IE=EmulateIE9" />' . "\n";
	*/

	if (defined('IN_ADMIN') || defined('IN_CMS') || defined('IN_SEARCH') || defined('IN_POSTING'))
	{
		$phpbb_meta_content = 'noindex,nofollow';
	}
	else
	{
		if (defined('ROBOTS_NOINDEX'))
		{
			$phpbb_meta_content = 'noindex';
		}
		else
		{
			$phpbb_meta_content = 'index,follow';
		}
	}
	$phpbb_meta .= '<meta name="robots" content="' . $phpbb_meta_content . '" />' . "\n";

	$phpbb_meta .= !empty($lang['Extra_Meta']) ? ($lang['Extra_Meta'] . "\n\n") : "\n";

	// Mighty Gorgon - Smart Header - Begin
	$encoding_charset = !empty($lang['ENCODING']) ? $lang['ENCODING'] : 'UTF-8';
	$lang_dir = !empty($lang['DIRECTION']) ? $lang['DIRECTION'] : 'ltr';
	$header_lang = !empty($lang['HEADER_LANG']) ? $lang['HEADER_LANG'] : 'en-gb';
	$xml_header_lang = !empty($lang['HEADER_LANG_XML']) ? $lang['HEADER_LANG_XML'] : 'en-gb';
	$og_header_lang = !empty($lang['HEADER_OG_LANG']) ? $lang['HEADER_OG_LANG'] : 'en_GB';
	$doctype_html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' . "\n";
	//$doctype_html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">' . "\n";
	$doctype_html .= '<html xmlns="http://www.w3.org/1999/xhtml" dir="' . $lang_dir . '" lang="' . $header_lang . '" xml:lang="' . $xml_header_lang . '">' . "\n";

	if ($page_url['basename'] == CMS_PAGE_VIEWONLINE)
	{
		$phpbb_meta .= '<meta http-equiv="refresh" content="180;url=viewonline.' . PHP_EXT . '" />' . "\n";
	}
	// Mighty Gorgon - Smart Header - End

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

		$canonical_url = $server_url . $page_url['basename'] . (empty($canonical_append) ? '' : '?') . $canonical_append;

		// Mighty Gorgon - OpenGraph - BEGIN
		$phpbb_meta .= '<meta property="og:locale" content="' . $og_header_lang . '" />' . "\n";
		$phpbb_meta .= '<meta property="og:title" content="' . $meta_content['page_title'] . '" />' . "\n";
		$phpbb_meta .= '<meta property="og:type" content="article" />' . "\n";
		$phpbb_meta .= (!empty($canonical_url) ? ('<meta property="og:url" content="' . $canonical_url . '" />' . "\n") : '');
		$phpbb_meta .= '<meta property="og:site_name" content="' . $config['sitename'] . '" />' . "\n";
		$phpbb_meta .= '<meta property="og:description" content="' . $meta_content['description'] . '" />' . "\n";
		if (!empty($meta_content['og_img']))
		{
			foreach ($meta_content['og_img'] as $og_img_src)
			{
				$phpbb_meta .= '<meta property="og:image" content="' . $og_img_src . '" />' . "\n";
			}
		}
		$phpbb_meta .= "\n";
		// Mighty Gorgon - OpenGraph - END

		$phpbb_meta .= (!empty($canonical_url) ? ('<link rel="canonical" href="' . $canonical_url . '" />' . "\n") : '');
	}
	// DYNAMIC META TAGS - END

	// Mighty Gorgon - AJAX Features - Begin
	$ajax_user_check = '';
	$ajax_user_check_alt = '';
	if (!empty($config['ajax_features']))
	{
		$ajax_user_check = 'onkeyup="AJAXUsernameSearch(this.value, 0);"';
		$ajax_user_check_alt = 'onkeyup="AJAXUsernameSearch(this.value, 1);"';
	}
	// Mighty Gorgon - AJAX Features - End

	// Generate HTML required for Mozilla Navigation bar
	$nav_base_url = $server_url;

	// Mozilla navigation bar - Default items that should be valid on all pages.
	// Defined here to correctly assign the Language Variables and be able to change the variables within code.
	/*
	// TOP and FORUM are not allowed on HTML 5
	$nav_links['top'] = array (
		'url' => append_sid(CMS_PAGE_HOME),
		'title' => $config['sitename']
	);
	$nav_links['forum'] = array (
		'url' => append_sid(CMS_PAGE_FORUM),
		'title' => sprintf($lang['Forum_Index'], $config['sitename'])
	);
	*/
	$nav_links['search'] = array (
		'url' => append_sid(CMS_PAGE_SEARCH),
		'title' => $lang['Search']
	);
	$nav_links['help'] = array (
		'url' => append_sid(CMS_PAGE_FAQ),
		'title' => $lang['FAQ']
	);
	$nav_links['author'] = array (
		'url' => append_sid(CMS_PAGE_MEMBERLIST),
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
	$rss_forum_id = request_var(POST_FORUM_URL, 0);
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

	// Time Management - BEGIN
	// Format Timezone. We are unable to use array_pop here, because of PHP3 compatibility
	$s_timezone = str_replace('.0', '', sprintf('%.1f', number_format($config['board_timezone'], 1)));
	$l_timezone = $lang['tz'][$s_timezone];

	if (!$user->data['session_logged_in'])
	{
		$user->data['user_time_mode'] = $config['default_time_mode'];
	}

	switch ($user->data['user_time_mode'])
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

	$new_pm_switch = false;
	$new_private_chat_switch = false;

	// LOGGED IN CHECK - BEGIN
	if (!$user->data['session_logged_in'])
	{
		// Allow autologin?
		if (!isset($config['allow_autologin']) || $config['allow_autologin'])
		{
			$template->assign_block_vars('switch_allow_autologin', array());
		}

		$smart_redirect = strrchr($_SERVER['SCRIPT_NAME'], '/');
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
				//Better sanitize each key...
				$smart_get_keys[$i] = htmlspecialchars($smart_get_keys[$i]);
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

		$icon_private_chat = $images['private_chat'];
		$u_private_chat = '#';
	}
	else
	{
		if (!empty($user->data['user_popup_pm']))
		{
			$template->assign_block_vars('switch_enable_pm_popup', array());
		}

		$u_login_logout = CMS_PAGE_LOGIN . '?logout=true&amp;sid=' . $user->data['session_id'];
		$l_login_logout = $lang['Logout'] . ' (' . $user->data['username'] . ')';
		$l_login_logout2 = $lang['Logout'];
		$s_last_visit = create_date($config['default_dateformat'], $user->data['user_lastvisit'], $config['board_timezone']);

		// DOWNLOADS ADV - BEGIN
		//@include(IP_ROOT_PATH . PLUGINS_PATH . $config['plugins']['downloads']['dir'] . 'includes/dl_page_header_inc.' . PHP_EXT);
		// DOWNLOADS ADV - END

		// Obtain number of new private messages
		if (empty($gen_simple_header))
		{

			// Birthday - BEGIN
			// see if user has or have had birthday, also see if greeting are enabled
			if (($user->data['user_birthday'] != 999999) && $config['birthday_greeting'] && (create_date('Ymd', time(), $config['board_timezone']) >= $user->data['user_next_birthday_greeting'] . realdate('md', $user->data['user_birthday'])))
			{
				if (!function_exists('birthday_pm_send'))
				{
					include_once(IP_ROOT_PATH . 'includes/functions_users.' . PHP_EXT);
				}
				birthday_pm_send();
			}
			// Birthday - END

			if ($user->data['user_profile_view'] && $user->data['user_profile_view_popup'])
			{
				$template->assign_vars(array(
					'PROFILE_VIEW' => true,
					'U_PROFILE_VIEW' => append_sid('profile_view_popup.' . PHP_EXT),
					)
				);
			}

			if ($user->data['user_new_privmsg'] && !$config['privmsg_disable'])
			{
				$new_pm_switch = true;
				$l_message_new = ($user->data['user_new_privmsg'] == 1) ? $lang['New_pm'] : $lang['New_pms'];
				$l_privmsgs_text = sprintf($l_message_new, $user->data['user_new_privmsg']);

				if ($user->data['user_last_privmsg'] > $user->data['user_lastvisit'])
				{
					$sql = "UPDATE " . USERS_TABLE . "
						SET user_last_privmsg = '" . $user->data['user_lastvisit'] . "'
						WHERE user_id = " . $user->data['user_id'];
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

			$icon_private_chat = $images['private_chat'];
			if (!empty($user->data['user_private_chat_alert']))
			{
				$new_private_chat_switch = true;
				$icon_private_chat = $images['private_chat_alert'];

				$ajax_chat_page = !empty($config['ajax_chat_link_type']) ? CMS_PAGE_AJAX_CHAT : CMS_PAGE_AJAX_SHOUTBOX;
				$ajax_chat_room = 'chat_room=' . $user->data['user_private_chat_alert'];
				$ajax_chat_link = append_sid($ajax_chat_page . '?' . $ajax_chat_room);
				$ajax_chat_ref = !empty($config['ajax_chat_link_type']) ? ($ajax_chat_link . '" target="_chat') : ('#" onclick="window.open(\'' . $ajax_chat_link . '\', \'_chat\', \'width=720,height=600,resizable=yes\'); $(\'#shoutbox_pvt_alert\').css(\'display\', \'none\'); return false;');
				$u_private_chat = $ajax_chat_ref;
			}

			if ($user->data['user_unread_privmsg'])
			{
				$l_message_unread = ($user->data['user_unread_privmsg'] == 1) ? $lang['Unread_pm'] : $lang['Unread_pms'];
				$l_privmsgs_text_unread = sprintf($l_message_unread, $user->data['user_unread_privmsg']);
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

		// We don't want this SQL being too expensive... so we will allow the number of new messages only for some pages... (you can add here other pages if you wish!)
		// We will also allow the number of new messages only for users which log on frequently
		$new_messages_counter_pages_array = array(CMS_PAGE_FORUM, CMS_PAGE_VIEWFORUM);
		$display_counter = ($config['enable_new_messages_number'] && !$user->data['is_bot'] && in_array($page_url['basename'], $new_messages_counter_pages_array) && ($user->data['user_lastvisit'] > (time() - (LAST_LOGIN_DAYS_NEW_POSTS_RESET * 60 * 60 * 24)))) ? true : false;
		if ($display_counter)
		{
			$auth_forum = '';
			if ($user->data['user_level'] != ADMIN)
			{
				if (!function_exists('auth_forum_read'))
				{
					include_once(IP_ROOT_PATH . 'includes/functions_upi2db.' . PHP_EXT);
				}
				$user->data['auth_forum_id'] = isset($user->data['auth_forum_id']) ? $user->data['auth_forum_id'] : auth_forum_read($user->data);
				$auth_forum = (!empty($user->data['auth_forum_id'])) ? ' AND p.forum_id IN (' . $user->data['auth_forum_id'] . ') ' : '';
			}

			$sql = "SELECT p.forum_id, t.topic_poster
				FROM " . POSTS_TABLE . " p, " . TOPICS_TABLE . " t
				WHERE t.topic_id = p.topic_id
				AND p.post_time >= " . $user->data['user_lastvisit'] . $auth_forum . "
				AND p.poster_id != " . $user->data['user_id'];
			$db->sql_return_on_error(true);
			$result = $db->sql_query($sql);
			$db->sql_return_on_error(false);
			if ($result)
			{
				$is_auth_ary = auth(AUTH_READ, AUTH_LIST_ALL, $user->data);

				$new_posts = 0;
				while ($row = $db->sql_fetchrow($result))
				{
					if ((intval($is_auth_ary[$row['forum_id']]['auth_read']) != AUTH_SELF) || $user->data['user_level'] == ADMIN || ($user->data['user_level'] == MOD && $config['allow_mods_view_self'] == true) || ($row['topic_poster'] == $user->data['user_id']))
					{
						$new_posts++;
					}
				}

				$lang['Search_new'] = $lang['Search_new'] . ' (' . $new_posts . ')';
				$lang['New'] = $lang['New'] . ' (' . $new_posts . ')';
				$lang['NEW_POSTS_SHORT'] = $lang['New_Label'] . ' (' . $new_posts . ')';
				$lang['NEW_POSTS_LONG'] = $lang['New_Messages_Label'] . ' (' . $new_posts . ')';
				$lang['Search_new2'] = $lang['Search_new2'] . ' (' . $new_posts . ')';
				$lang['Search_new_p'] = $lang['Search_new_p'] . ' (' . $new_posts . ')';
				$db->sql_freeresult($result);
			}
		}
		else
		{
			$lang['NEW_POSTS_SHORT'] = $lang['New_Label'];
			$lang['NEW_POSTS_LONG'] = $lang['New_Messages_Label'];
		}
	}
	// LOGGED IN CHECK - END

	if (!defined('IN_CMS'))
	{
		// UPI2DB - BEGIN
		$upi2db_first_use = '';
		$u_display_new = array();
		if($user->data['upi2db_access'])
		{
			$u_display_new = index_display_new($user->data['upi2db_unread']);
			$template->assign_block_vars('switch_upi2db_on', array());
			$template->assign_var('IS_UPI2DB', true);
			$upi2db_first_use = ($user->data['user_upi2db_datasync'] == '0') ? ('<script type="text/javascript">' . "\n" . '// <![CDATA[' . "\n" . 'alert ("' . $lang['upi2db_first_use_txt'] . '");' . "\n" . '// ]]>' . "\n" . '</script>') : '';
		}
		else
		{
			if ($user->data['session_logged_in'])
			{
				$template->assign_block_vars('switch_upi2db_off', array());
			}
		}
		// UPI2DB - END

		// Digests - BEGIN
		if (!empty($config['cron_digests_interval']) && ($config['cron_digests_interval'] > 0))
		{
			if (!defined('DIGEST_SITE_URL'))
			{
				$digest_server_url = create_server_url();
				define('DIGEST_SITE_URL', $digest_server_url);
			}
			setup_extra_lang(array('lang_digests'));
			if ($user->data['session_logged_in'])
			{
				$template->assign_block_vars('switch_show_digests', array());
			}
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
		$ac_online_users = array('reg' => 0, 'guests' => 0, 'tot' => 0, 'list' => '', 'text' => '');
		if (defined('SHOW_ONLINE') && !$user->data['is_bot'])
		{
			include(IP_ROOT_PATH . 'includes/users_online_block.' . PHP_EXT);
		}
		// Show Online Block - END

		// CrackerTracker v5.x
		/*
		* CrackerTracker IP Range Scanner
		*/
		$marknow = request_var('marknow', '');
		if (($marknow == 'ipfeature') && $user->data['session_logged_in'])
		{
			// Mark IP Feature Read
			$user->data['ct_last_ip'] = $user->data['ct_last_used_ip'];
			$sql = 'UPDATE ' . USERS_TABLE . ' SET ct_last_ip = ct_last_used_ip WHERE user_id=' . $user->data['user_id'];
			$result = $db->sql_query($sql);

			if (!empty($_SERVER['HTTP_REFERER']))
			{
				preg_match('#/([^/]*?)$#', $_SERVER['HTTP_REFERER'], $backlink);
				redirect($backlink[1]);
			}
		}

		if (($config['ctracker_login_ip_check'] == 1) && ($user->data['ct_enable_ip_warn'] == 1) && $user->data['session_logged_in'])
		{
			include_once(IP_ROOT_PATH . 'includes/ctracker/classes/class_ct_userfunctions.' . PHP_EXT);
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
		if (($marknow == 'globmsg') && $user->data['session_logged_in'])
		{
			// Mark Global Message as read
			$user->data['ct_global_msg_read'] = 0;
			$sql = 'UPDATE ' . USERS_TABLE . ' SET ct_global_msg_read = 0 WHERE user_id=' . $user->data['user_id'];
			$result = $db->sql_query($sql);

			if (!empty($_SERVER['HTTP_REFERER']))
			{
				preg_match('#/([^/]*?)$#', $_SERVER['HTTP_REFERER'], $backlink);
				redirect($backlink[1]);
			}
		}

		if (($user->data['ct_global_msg_read'] == 1) && $user->data['session_logged_in'] && ($config['ctracker_global_message'] != ''))
		{
			// Output Global Message
			$global_message_output = '';

			if ($config['ctracker_global_message_type'] == 1)
			{
				$global_message_output = $config['ctracker_global_message'];
			}
			else
			{
				$global_message_output = sprintf($lang['ctracker_gmb_link'], $config['ctracker_global_message'], $config['ctracker_global_message']);
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

		if (((($config['login_history'] == 1) || ($config['login_ip_check'] == 1)) && ($user->data['session_logged_in'])))
		{
			$template->assign_block_vars('login_sec_link', array());
		}

		/*
		* CrackerTracker Password Expiry Check
		*/
		if ($user->data['session_logged_in'] && ($config['ctracker_pw_control'] == 1))
		{
			$pwd_expiry_time = $user->data['user_passchg'] + (!empty($config['ctracker_pw_validity']) ? (int) $config['ctracker_pw_validity'] : 365) * 24 * 60 * 60;
			if (time() > $pwd_expiry_time)
			{
				$template->assign_block_vars('ctracker_message', array(
					'ROW_COLOR' => 'ffdfdf',
					'ICON_GLOB' => $images['ctracker_note'],
					'L_MESSAGE_TEXT' => sprintf($lang['ctracker_info_pw_expired'], $config['ctracker_pw_validity'], $user->data['user_id']),
					'L_MARK_MESSAGE' => '',
					'U_MARK_MESSAGE' => ''
					)
				);
			}
		}
		/*
		* CrackerTracker Debug Mode Check
		*/
		if (defined('CT_DEBUG_MODE') && (CT_DEBUG_MODE === true) && ($user->data['user_level'] == ADMIN))
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
			$path_parts = pathinfo($_SERVER['SCRIPT_NAME']);
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

		$social_connect_buttons = '';
		if (!empty($config['enable_social_connect']))
		{
			include_once(IP_ROOT_PATH . 'includes/class_social_connect.' . PHP_EXT);
			$available_networks = SocialConnect::get_available_networks();

			foreach ($available_networks as $social_network)
			{
				$social_connect_url = append_sid(CMS_PAGE_LOGIN . '?social_network=' . $social_network->get_name_clean());
				$social_connect_img = '<img src="' . IP_ROOT_PATH . 'images/social_connect/' . $social_network->get_name_clean() . '_button_connect.png" alt="" title="' . $social_network->get_name() . '" />';
				$social_connect_buttons .= '<a href="' . $social_connect_url . '">' . $social_connect_img . '</a>';
			}
		}

		// The following assigns all _common_ variables that may be used at any point in a template.
		$template->assign_vars(array(
			'TOTAL_USERS_ONLINE' => $l_online_users,
			'LOGGED_IN_USER_LIST' => $online_userlist,
			'BOT_LIST' => !empty($online_botlist) ? $online_botlist : '',
			'AC_LIST_TEXT' => $ac_online_users['text'],
			'AC_LIST' => $ac_online_users['list'],
			'RECORD_USERS' => sprintf($lang['Record_online_users'], $config['record_online_users'], create_date($config['default_dateformat'], $config['record_online_date'], $config['board_timezone'])),

			'TOP_HTML_BLOCK' => $top_html_block_text,
			'S_HEADER_BANNER' => (empty($header_banner_text) ? false : true),
			'HEADER_BANNER_CODE' => $header_banner_text,
			'NAV_MENU_ADS_TOP' => $nav_menu_ads_top,
			'NAV_MENU_ADS_BOTTOM' => $nav_menu_ads_bottom,

			'L_SEARCH_NEW' => $lang['Search_new'],
			'L_SEARCH_NEW2' => $lang['Search_new2'],
			'L_NEW' => $lang['New'],
			'L_NEW2' => (empty($lang['NEW_POSTS_SHORT']) ? $lang['New_Label'] : $lang['NEW_POSTS_SHORT']),
			'L_NEW3' => (empty($lang['NEW_POSTS_LONG']) ? $lang['New_Messages_Label'] : $lang['NEW_POSTS_LONG']),
			'L_POSTS' => $lang['Posts'],
		// UPI2DB - BEGIN
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
			'UPI2DB_U_COUNTER' => (!empty($u_display_new) ? $u_display_new['counter_unread'] : 0),
			'UPI2DB_M_COUNTER' => (!empty($u_display_new) ? $u_display_new['counter_marked'] : 0),
			'UPI2DB_P_COUNTER' => (!empty($u_display_new) ? $u_display_new['counter_permanent'] : 0),
			'U_DISPLAY_U' => (!empty($u_display_new) ? $u_display_new['u_url'] : ''),
			'U_DISPLAY_M' => (!empty($u_display_new) ? $u_display_new['m_url'] : ''),
			'U_DISPLAY_P' => (!empty($u_display_new) ? $u_display_new['p_url'] : ''),
		// UPI2DB - END
			'L_SEARCH_UNANSWERED' => $lang['Search_unanswered'],
			'L_SEARCH_SELF' => $lang['Search_your_posts'],
			'L_RECENT' => $lang['Recent_topics'],
			'L_WATCHED_TOPICS' => $lang['Watched_Topics'],
			'L_BOOKMARKS' => $lang['Bookmarks'],
			'L_DIGESTS' => $lang['DIGESTS'],
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

			'U_CPL_PROFILE_VIEWED' => append_sid('profile_view_user.' . PHP_EXT . '?' . POST_USERS_URL . '=' . $user->data['user_id']),
			'U_CPL_NEWMSG' => append_sid(CMS_PAGE_PRIVMSG . '?mode=post'),
			'U_CPL_REGISTRATION_INFO' => append_sid(CMS_PAGE_PROFILE . '?mode=editprofile&amp;cpl_mode=reg_info'),
			'U_CPL_DELETE_ACCOUNT' => append_sid('contact_us.' . PHP_EXT . '?account_delete=' . $user->data['user_id']),
			'U_CPL_PROFILE_INFO' => append_sid(CMS_PAGE_PROFILE . '?mode=editprofile&amp;cpl_mode=profile_info'),
			'U_CPL_PREFERENCES' => append_sid(CMS_PAGE_PROFILE . '?mode=editprofile&amp;cpl_mode=preferences'),
			'U_CPL_BOARD_SETTINGS' => append_sid(CMS_PAGE_PROFILE . '?mode=editprofile&amp;cpl_mode=board_settings'),
			'U_CPL_AVATAR_PANEL' => append_sid(CMS_PAGE_PROFILE . '?mode=editprofile&amp;cpl_mode=avatar'),
			'U_CPL_SIGNATURE' => append_sid(CMS_PAGE_PROFILE . '?mode=signature'),
			'U_CPL_OWN_POSTS' => append_sid(CMS_PAGE_SEARCH. '?search_author=' . urlencode($user->data['username']) . '&amp;showresults=posts'),
			'U_CPL_OWN_PICTURES' => append_sid('album.' . PHP_EXT . '?user_id=' . $user->data['user_id']),
			'U_CPL_CALENDAR_SETTINGS' => append_sid('profile_options.' . PHP_EXT . '?sub=preferences&amp;module=calendar_settings&amp;' . POST_USERS_URL . '=' . $user->data['user_id']),
			'U_CPL_SUBFORUM_SETTINGS' => append_sid('profile_options.' . PHP_EXT . '?sub=preferences&amp;module=forums_settings&amp;' . POST_USERS_URL . '=' . $user->data['user_id']),
			'U_CPL_SUBSCFORUMS' => append_sid('subsc_forums.' . PHP_EXT),
			'U_CPL_BOOKMARKS' => append_sid(CMS_PAGE_SEARCH . '?search_id=bookmarks'),
			'U_CPL_INBOX' => append_sid(CMS_PAGE_PRIVMSG . '?folder=inbox'),
			'U_CPL_OUTBOX' => append_sid(CMS_PAGE_PRIVMSG . '?folder=outbox'),
			'U_CPL_SAVEBOX' => append_sid(CMS_PAGE_PRIVMSG . '?folder=savebox'),
			'U_CPL_SENTBOX' => append_sid(CMS_PAGE_PRIVMSG . '?folder=sentbox'),
			'U_CPL_DRAFTS' => append_sid('drafts.' . PHP_EXT),
			'U_CPL_ZEBRA' => append_sid(CMS_PAGE_PROFILE . '?mode=zebra&amp;zmode=friends'),
			// Mighty Gorgon - CPL - END

			'SOCIAL_CONNECT_BUTTONS' => $social_connect_buttons,

			// Activity - BEGIN
			/*
			'L_WHOSONLINE_GAMES' => '<a href="'. append_sid('activity.' . PHP_EXT) .'"><span style="color:#'. str_replace('#', '', $config['ina_online_list_color']) . ';">' . $config['ina_online_list_text'] . '</span></a>',
			*/
			'P_ACTIVITY_MOD_PATH' => PLUGINS_PATH . $config['plugins']['activity']['dir'],
			'U_ACTIVITY' => append_sid('activity.' . PHP_EXT),
			'L_ACTIVITY' => $lang['Activity'],
			// Activity - END
			)
		);
	}

	foreach ($config['plugins'] as $plugin_name => $plugin_config)
	{
		if ($plugin_config['enabled'])
		{
			$template->assign_var('PLUGIN_' . strtoupper($plugin_name), true);
			$template->assign_block_vars('plugins', array(
				'NAME' => $plugin_name,
				)
			);
		}
	}

	// The following assigns all _common_ variables that may be used at any point in a template.
	$current_time = create_date($config['default_dateformat'], time(), $config['board_timezone']);
	$template->assign_vars(array(
		'DOCTYPE_HTML' => $doctype_html,
		'HEADER_LANG' => $header_lang,
		'NAV_LINKS' => $nav_links_html,

		'S_HIGHSLIDE' => (!empty($config['thumbnail_highslide']) ? true : false),
		'S_COOKIE_LAW' => (!empty($config['cookie_law']) ? true : false),

		// AJAX Features - BEGIN
		'S_AJAX_FEATURES' => (!empty($config['ajax_features']) ? true : false),
		'S_AJAX_USER_CHECK' => $ajax_user_check,
		'S_AJAX_USER_CHECK_ALT' => $ajax_user_check_alt,
		// AJAX Features - END

		'U_LOGIN_LOGOUT' => append_sid(IP_ROOT_PATH . $u_login_logout),
		'USER_USERNAME' => $user->data['session_logged_in'] ? htmlspecialchars($user->data['username']) : $lang['Guest'],

		// UPI2DB - BEGIN
		'UPI2DB_FIRST_USE' => $upi2db_first_use,
		// UPI2DB - END

		'L_PAGE_TITLE' => $meta_content['page_title_clean'],
		'PAGE_TITLE' => ($config['page_title_simple'] ? $meta_content['page_title_clean'] : $meta_content['page_title']),
		'META_TAG' => $phpbb_meta,
		'LAST_VISIT_DATE' => sprintf($lang['You_last_visit'], $s_last_visit),
		'CURRENT_TIME' => sprintf($lang['Current_time'], $current_time),
		'CURRENT_TIME_ONLY' => $current_time,
		'S_TIMEZONE' => $time_message,

		'PRIVATE_MESSAGE_INFO' => $l_privmsgs_text,
		'PRIVATE_MESSAGE_INFO_UNREAD' => $l_privmsgs_text_unread,
		'PRIVATE_MESSAGE_NEW_FLAG' => $s_privmsg_new,
		'PRIVMSG_IMG' => $icon_pm,
		'NEW_PM_SWITCH' => $new_pm_switch,

		'PRIVATE_CHAT_IMG' => $icon_private_chat,
		'U_PRIVATE_CHAT' => $u_private_chat,
		'NEW_PRIVATE_CHAT_SWITCH' => $new_private_chat_switch,

		'L_USERNAME' => $lang['Username'],
		'L_PASSWORD' => $lang['Password'],
		'L_LOGIN_LOGOUT' => $l_login_logout,
		'L_LOGIN_LOGOUT2' => $l_login_logout2,
		'L_LOGIN' => $lang['Login'],
		'L_HOME' => $lang['Home'],
		'L_INDEX' => sprintf($lang['Forum_Index'], $config['sitename']),
		'L_REGISTER' => $lang['Register'],
		'L_BOARDRULES' => $lang['BoardRules'],
		'L_PROFILE' => $lang['Profile'],
		'L_CPL_NAV' => $lang['Profile'],
		'L_SEARCH' => $lang['Search'],
		'L_PRIVATEMSGS' => $lang['Private_Messages'],
		'L_WHO_IS_ONLINE' => $lang['Who_is_Online'],
		'L_MEMBERLIST' => $lang['Memberlist'],
		'L_FAQ' => $lang['FAQ'],
		'L_ADV_SEARCH' => $lang['Adv_Search'],
		'L_SEARCH_EXPLAIN' => $lang['Search_Explain'],

		'L_KB' => $lang['KB_title'],
		'L_NEWS' => $lang['News_Cmx'],
		'L_USERGROUPS' => $lang['Usergroups'],
		'L_BOARD_DISABLE' => $lang['Board_disabled'],

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
		$breadcrumbs['address'] = $nav_separator . '<a href="' . $nav_server_url . append_sid(CMS_PAGE_FORUM) . '">' . $lang['Forum'] . '</a>' . $nav_cat_desc;
		if (isset($nav_add_page_title) && ($nav_add_page_title == true))
		{
			$breadcrumbs['address'] = $breadcrumbs['address'] . $nav_separator . '<a href="#" class="nav-current">' . $meta_content['page_title'] . '</a>';
		}
	}

	// send to template
	$template->assign_vars(array(
		//'SPACER' => $images['spacer'],
		'S_PAGE_NAV' => (isset($cms_page['page_nav']) ? $cms_page['page_nav'] : true),
		'NAV_SEPARATOR' => $nav_separator,
		'NAV_CAT_DESC' => $nav_cat_desc,
		'BREADCRUMBS_ADDRESS' => (empty($breadcrumbs['address']) ? (($meta_content['page_title_clean'] != $config['sitename']) ? ($lang['Nav_Separator'] . '<a href="#" class="nav-current">' . $meta_content['page_title_clean'] . '</a>') : '') : $breadcrumbs['address']),
		'S_BREADCRUMBS_BOTTOM_LEFT_LINKS' => (empty($breadcrumbs['bottom_left_links']) ? false : true),
		'BREADCRUMBS_BOTTOM_LEFT_LINKS' => (empty($breadcrumbs['bottom_left_links']) ? '&nbsp;' : $breadcrumbs['bottom_left_links']),
		'S_BREADCRUMBS_BOTTOM_RIGHT_LINKS' => (empty($breadcrumbs['bottom_right_links']) ? false : true),
		'BREADCRUMBS_BOTTOM_RIGHT_LINKS' => (empty($breadcrumbs['bottom_right_links']) ? '&nbsp;' : $breadcrumbs['bottom_right_links']),
		)
	);

	if ($config['board_disable'] && ($user->data['user_level'] == ADMIN))
	{
		$template->assign_block_vars('switch_admin_disable_board', array());
	}

	if (!defined('IN_CMS'))
	{
		$cms_page['global_blocks'] = (empty($cms_page['global_blocks']) ? false : true);
		//$cms_page['global_blocks'] = ((!isset($cms_page['page_id']) || !$cms_page['global_blocks']) ? false : true);
		$cms_page_blocks = ((empty($cms_page['page_id']) || empty($cms_config_layouts[$cms_page['page_id']])) ? false : true);
		if(empty($gen_simple_header) && !defined('HAS_DIED') && !defined('IN_LOGIN') && ($cms_page['global_blocks'] || $cms_page_blocks) && (!$config['board_disable'] || ($user->data['user_level'] == ADMIN)))
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

	if (($user->data['user_level'] != ADMIN) && $config['board_disable'] && !defined('HAS_DIED') && !defined('IN_ADMIN') && !defined('IN_LOGIN'))
	{
		if (!defined('STATUS_503')) define('STATUS_503', true);
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
		$encoding_charset = !empty($lang['ENCODING']) ? $lang['ENCODING'] : 'UTF-8';
		header('Content-type: text/html; charset=' . $encoding_charset);
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

	define('HEADER_INC_COMPLETED', true);

	return;
}

/**
* Page Footer
*/
function page_footer($exit = true, $template_to_parse = 'body', $parse_template = false)
{
	global $db, $cache, $config, $user, $template, $images, $theme, $lang, $tree;
	global $table_prefix, $SID, $_SID;
	global $ip_cms, $cms_config_vars, $cms_config_global_blocks, $cms_config_layouts, $cms_page;
	global $starttime, $base_memory_usage, $do_gzip_compress, $start;
	global $gen_simple_header, $meta_content, $nav_separator, $nav_links, $nav_pgm, $nav_add_page_title, $skip_nav_cat;
	global $breadcrumbs;
	global $cms_acp_url;

	$config['gzip_compress_runtime'] = (isset($config['gzip_compress_runtime']) ? $config['gzip_compress_runtime'] : $config['gzip_compress']);
	$config['url_rw_runtime'] = (isset($config['url_rw_runtime']) ? $config['url_rw_runtime'] : (($config['url_rw'] || ($config['url_rw_guests'] && ($user->data['user_id'] == ANONYMOUS))) ? true : false));

	if (!defined('IN_CMS'))
	{
		$cms_page['global_blocks'] = (empty($cms_page['global_blocks']) ? false : true);
		//$cms_page['global_blocks'] = ((!isset($cms_page['page_id']) || !$cms_page['global_blocks']) ? false : true);
		$cms_page_blocks = ((empty($cms_page['page_id']) || empty($cms_config_layouts[$cms_page['page_id']])) ? false : true);
		if(empty($gen_simple_header) && !defined('HAS_DIED') && !defined('IN_LOGIN') && ($cms_page['global_blocks'] || $cms_page_blocks) && (!$config['board_disable'] || ($user->data['user_level'] == ADMIN)))
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
		/*
		include_once(IP_ROOT_PATH . 'includes/ctracker/engines/ct_footer.' . PHP_EXT);
		$output_login_status = ($user->data['ct_enable_ip_warn'] ? $lang['ctracker_ma_on'] : $lang['ctracker_ma_off']);

		$template->assign_vars(array(
			'CRACKER_TRACKER_FOOTER' => create_footer_layout($config['ctracker_footer_layout']),
			'L_STATUS_LOGIN' => ($config['ctracker_login_ip_check'] ? sprintf($lang['ctracker_ipwarn_info'], $output_login_status) : ''),
			)
		);
		*/
		// CrackerTracker v5.x
	}

	include_once(IP_ROOT_PATH . 'includes/functions_jr_admin.' . PHP_EXT);
	$admin_link = jr_admin_make_admin_link();

	//Begin Lo-Fi Mod
	$path_parts = pathinfo($_SERVER['SCRIPT_NAME']);
	$lofi = '<a href="' . append_sid(IP_ROOT_PATH . $path_parts['basename'] . '?' . htmlspecialchars(str_replace(array('&lofi=0', '&lofi=1', 'lofi=0', 'lofi=1'), array('', '', '', ''), $_SERVER['QUERY_STRING'])) . '&amp;lofi=' . (empty($_COOKIE[$config['cookie_name'] . '_lofi']) ? '1' : '0')) . '">' . (empty($_COOKIE[$config['cookie_name'] . '_lofi']) ? ($lang['Lofi']) : ($lang['Full_Version'])) . '</a>';
	$mobile_style = '<a href="' . append_sid(IP_ROOT_PATH . $path_parts['basename'] . '?' . htmlspecialchars(str_replace(array('&mob=0', '&mob=1', 'mob=0', 'mob=1'), array('', '', '', ''), $_SERVER['QUERY_STRING'])) . '&amp;mob=' . (!empty($_COOKIE[$config['cookie_name'] . '_mob']) ? '0' : '1')) . '">' . (!empty($_COOKIE[$config['cookie_name'] . '_mob']) ? ($lang['MOBILE_STYLE_DISABLE']) : ($lang['MOBILE_STYLE_ENABLE'])) . '</a>';
	$template->assign_vars(array(
		'L_LOFI' => $lang['Lofi'],
		'L_FULL_VERSION' => $lang['Full_Version'],
		'LOFI' => $lofi . ($user->data['is_mobile'] ? ('&nbsp;&bull;&nbsp;' . $mobile_style) : ''),
		'MOBILE_STYLE' => $mobile_style
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
	if ($config['cron_global_switch'] && !defined('IN_CRON') && !defined('IN_ADMIN') && !defined('IN_CMS') && empty($config['board_disable']))
	{
		$cron_time = time();
		$cron_append = '';
		$cron_types = array('files', 'database', 'cache', 'sql', 'users', 'topics', 'sessions');

		for ($i = 0; $i < sizeof($cron_types); $i++)
		{
			$cron_trigger = $cron_time - $config['cron_' . $cron_types[$i] . '_interval'];
			if (($config['cron_' . $cron_types[$i] . '_interval'] > 0) && ($cron_trigger > $config['cron_' . $cron_types[$i] . '_last_run']))
			{
				$cron_append .= (empty($cron_append) ? '?' : '&amp;') . $cron_types[$i] . '=1';
			}
		}

		// We can force hours crons as all checks are performed by the function
		$hour_cron_types = array('digests', 'birthdays');
		$cur_time = @getdate();
		foreach ($hour_cron_types as $hour_cron_type)
		{
			$config['cron_' . $hour_cron_type . '_last_run'] = !empty($config['cron_' . $hour_cron_type . '_last_run']) ? $config['cron_' . $hour_cron_type . '_last_run'] : (time() - 3600);
			$last_send_time = @getdate($config['cron_' . $hour_cron_type . '_last_run']);
			if (!empty($config['cron_' . $hour_cron_type . '_interval']) && ($config['cron_' . $hour_cron_type . '_interval'] > 0) && ($cur_time['hours'] != $last_send_time['hours']))
			{
				$cron_append .= (empty($cron_append) ? '?' : '&amp;') . $hour_cron_type . '=1';
			}
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
		if (($user->data['user_level'] == ADMIN) || $page_gen_allowed)
		{
			$gzip_text = ($config['gzip_compress_runtime']) ? 'GZIP ' . $lang['Enabled']: 'GZIP ' . $lang['Disabled'];
			$debug_text = (DEBUG == true) ? $lang['Debug_On'] : $lang['Debug_Off'];
			$memory_usage_text = '';
			//$excuted_queries = $db->num_queries['total'];
			$excuted_queries = $db->num_queries['normal'];
			$endtime = explode(' ', microtime());
			$endtime = $endtime[1] + $endtime[0];
			$gentime = round(($endtime - $starttime), 4); // You can adjust the number 6
			$sql_time = round($db->sql_time, 4);

			$sql_part = round($sql_time / $gentime * 100);
			$php_part = 100 - $sql_part;

			// Mighty Gorgon - Extra Debug - BEGIN
			if (defined('DEBUG_EXTRA') && DEBUG_EXTRA && ($user->data['user_level'] == ADMIN))
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
				if (defined('DEBUG_EXTRA') && DEBUG_EXTRA)
				{
					$tmp_query_string = htmlspecialchars(str_replace(array('&explain=1', 'explain=1'), array('', ''), $_SERVER['QUERY_STRING']));
					$gzip_text .= ' - <a href="' . append_sid(IP_ROOT_PATH . $path_parts['basename'] . (!empty($tmp_query_string) ? ('?' . $tmp_query_string . '&amp;explain=1') : '?explain=1')) . '">Extra ' . $lang['Debug_On'] . '</a>';
				}
			}

			//if (defined('DEBUG_EXTRA') && DEBUG_EXTRA && ($user->data['user_level'] == ADMIN))
			if (defined('DEBUG_EXTRA') && DEBUG_EXTRA && !empty($_REQUEST['explain']) && ($user->data['user_level'] == ADMIN) && method_exists($db, 'sql_report'))
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
			$gen_log_file = IP_ROOT_PATH . MAIN_CACHE_FOLDER . '/gen_log.txt';
			$fp = fopen ($gen_log_file, "a+");
			fwrite($fp, (!empty($gentime) ? $gentime : '0') . "\t" . (!empty($memory_usage) ? $memory_usage : '0') . "\t" . $user->page['page'] . "\n");
			fclose($fp);
			*/
		}
		// Page generation time - END
	}

	// Check for some switches here, in case we have changed/reset these swiches somewhere through the code or CMS blocks!
	$template->assign_vars(array(
		'S_PRINT_SIZE' => (!empty($config['display_print_size']) ? true : false),
		'S_JQUERY_UI' => (!empty($config['jquery_ui']) ? true : false),
		'S_JQUERY_UI_TP' => (!empty($config['jquery_ui_tp']) ? true : false),
		'S_JQUERY_UI_BA' => (!empty($config['jquery_ui_ba']) ? true : false),
		'S_JQUERY_UI_STYLE' => (!empty($config['jquery_ui_style']) ? $config['jquery_ui_style'] : 'cupertino'),
		'S_JQUERY_TAGS' => (!empty($config['jquery_tags']) ? true : false),
		)
	);

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
	global $db, $cache;

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

	// URL Rewrite - BEGIN
	// Compress buffered output if required and send to browser
	if (!empty($config['url_rw_runtime']))
	{
		$contents = rewrite_urls(ob_get_contents());
		ob_end_clean();
		(@extension_loaded('zlib') && !empty($config['gzip_compress_runtime'])) ? ob_start('ob_gzhandler') : ob_start();
		echo $contents;
	}
	// URL Rewrite - END

	// As a pre-caution... some setups display a blank page if the flush() is not there.
	(empty($config['gzip_compress_runtime']) && empty($config['url_rw_runtime'])) ? @flush() : @ob_flush();

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
* Add log event
*/
function add_log()
{
	global $db, $user;

	// In phpBB 3.1.x i want to have logging in a class to be able to control it
	// For now, we need a quite hakish approach to circumvent logging for some actions
	// @todo implement cleanly
	if (!empty($GLOBALS['skip_add_log']))
	{
		return false;
	}

	$args = func_get_args();

	$mode = array_shift($args);
	$reportee_id = ($mode == 'user') ? intval(array_shift($args)) : '';
	$forum_id = ($mode == 'mod') ? intval(array_shift($args)) : '';
	$topic_id = ($mode == 'mod') ? intval(array_shift($args)) : '';
	$action = array_shift($args);
	$data = (!sizeof($args)) ? '' : serialize($args);

	$sql_ary = array(
		'user_id' => (empty($user->data)) ? ANONYMOUS : $user->data['user_id'],
		'log_ip' => $user->ip,
		'log_time' => time(),
		'log_operation' => $action,
		'log_data' => $data,
	);

	switch ($mode)
	{
		case 'admin':
			$sql_ary['log_type'] = LOG_ADMIN;
		break;

		case 'mod':
			$sql_ary += array(
				'log_type' => LOG_MOD,
				'forum_id' => $forum_id,
				'topic_id' => $topic_id
			);
		break;

		case 'user':
			$sql_ary += array(
				'log_type' => LOG_USERS,
				'reportee_id' => $reportee_id
			);
		break;

		case 'critical':
			$sql_ary['log_type'] = LOG_CRITICAL;
		break;

		default:
			return false;
	}

	$db->sql_query('INSERT INTO ' . LOG_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_ary));

	return $db->sql_nextid();
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
* This function returns a regular expression pattern for commonly used expressions
* Use with / as delimiter for email mode and # for url modes
* mode can be: ipv4|ipv6
*/
function get_preg_expression($mode)
{
	switch ($mode)
	{
		// Whoa these look impressive!
		// The code to generate the following two regular expressions which match valid IPv4/IPv6 addresses can be found in the develop directory
		case 'ipv4':
			return '#^(?:(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$#';
		break;

		case 'ipv6':
			return '#^(?:(?:(?:[\dA-F]{1,4}:){6}(?:[\dA-F]{1,4}:[\dA-F]{1,4}|(?:(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])))|(?:::(?:[\dA-F]{1,4}:){5}(?:[\dA-F]{1,4}:[\dA-F]{1,4}|(?:(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])))|(?:(?:[\dA-F]{1,4}:):(?:[\dA-F]{1,4}:){4}(?:[\dA-F]{1,4}:[\dA-F]{1,4}|(?:(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])))|(?:(?:[\dA-F]{1,4}:){1,2}:(?:[\dA-F]{1,4}:){3}(?:[\dA-F]{1,4}:[\dA-F]{1,4}|(?:(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])))|(?:(?:[\dA-F]{1,4}:){1,3}:(?:[\dA-F]{1,4}:){2}(?:[\dA-F]{1,4}:[\dA-F]{1,4}|(?:(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])))|(?:(?:[\dA-F]{1,4}:){1,4}:(?:[\dA-F]{1,4}:)(?:[\dA-F]{1,4}:[\dA-F]{1,4}|(?:(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])))|(?:(?:[\dA-F]{1,4}:){1,5}:(?:[\dA-F]{1,4}:[\dA-F]{1,4}|(?:(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])))|(?:(?:[\dA-F]{1,4}:){1,6}:[\dA-F]{1,4})|(?:(?:[\dA-F]{1,4}:){1,7}:))$#i';
		break;
	}

	return '';
}

/**
* Returns the first block of the specified IPv6 address and as many additional
* ones as specified in the length paramater.
* If length is zero, then an empty string is returned.
* If length is greater than 3 the complete IP will be returned
*/
function short_ipv6($ip, $length)
{
	if ($length < 1)
	{
		return '';
	}

	// extend IPv6 addresses
	$blocks = substr_count($ip, ':') + 1;
	if ($blocks < 9)
	{
		$ip = str_replace('::', ':' . str_repeat('0000:', 9 - $blocks), $ip);
	}
	if ($ip[0] == ':')
	{
		$ip = '0000' . $ip;
	}
	if ($length < 4)
	{
		$ip = implode(':', array_slice(explode(':', $ip), 0, 1 + $length));
	}

	return $ip;
}

/**
* Wrapper for php's checkdnsrr function.
*
* @param string $host:Fully-Qualified Domain Name
* @param string $type: Resource record type to lookup
*						Supported types are: MX (default), A, AAAA, NS, TXT, CNAME
*						Other types may work or may not work
*
* @return mixed: true if entry found,
*					false if entry not found,
*					null if this function is not supported by this environment
*
* Since null can also be returned, you probably want to compare the result
* with === true or === false,
*
* @author bantu
*/
function phpbb_checkdnsrr($host, $type = 'MX')
{
	// The dot indicates to search the DNS root (helps those having DNS prefixes on the same domain)
	if (substr($host, -1) == '.')
	{
		$host_fqdn = $host;
		$host = substr($host, 0, -1);
	}
	else
	{
		$host_fqdn = $host . '.';
	}
	// $host: has format some.host.example.com
	// $host_fqdn: has format some.host.example.com.

	// If we're looking for an A record we can use gethostbyname()
	if (($type == 'A') && function_exists('gethostbyname'))
	{
		return (@gethostbyname($host_fqdn) == $host_fqdn) ? false : true;
	}

	// checkdnsrr() is available on Windows since PHP 5.3,
	// but until 5.3.3 it only works for MX records
	// See: http://bugs.php.net/bug.php?id=51844

	// Call checkdnsrr() if
	// we're looking for an MX record or
	// we're not on Windows or
	// we're running a PHP version where #51844 has been fixed

	// checkdnsrr() supports AAAA since 5.0.0
	// checkdnsrr() supports TXT since 5.2.4
	if ((($type == 'MX') || (DIRECTORY_SEPARATOR != '\\') || version_compare(PHP_VERSION, '5.3.3', '>=')) && (($type != 'AAAA') || version_compare(PHP_VERSION, '5.0.0', '>=')) && (($type != 'TXT') || version_compare(PHP_VERSION, '5.2.4', '>=')) && function_exists('checkdnsrr')
	)
	{
		return checkdnsrr($host_fqdn, $type);
	}

	// dns_get_record() is available since PHP 5; since PHP 5.3 also on Windows,
	// but on Windows it does not work reliable for AAAA records before PHP 5.3.1

	// Call dns_get_record() if
	// we're not looking for an AAAA record or
	// we're not on Windows or
	// we're running a PHP version where AAAA lookups work reliable
	if ((($type != 'AAAA') || (DIRECTORY_SEPARATOR != '\\') || version_compare(PHP_VERSION, '5.3.1', '>=')) && function_exists('dns_get_record'))
	{
		// dns_get_record() expects an integer as second parameter
		// We have to convert the string $type to the corresponding integer constant.
		$type_constant = 'DNS_' . $type;
		$type_param = (defined($type_constant)) ? constant($type_constant) : DNS_ANY;

		// dns_get_record() might throw E_WARNING and return false for records that do not exist
		$resultset = @dns_get_record($host_fqdn, $type_param);

		if (empty($resultset) || !is_array($resultset))
		{
			return false;
		}
		elseif ($type_param == DNS_ANY)
		{
			// $resultset is a non-empty array
			return true;
		}

		foreach ($resultset as $result)
		{
			if (isset($result['host']) && ($result['host'] == $host) && isset($result['type']) && ($result['type'] == $type))
			{
				return true;
			}
		}

		return false;
	}

	// If we're on Windows we can still try to call nslookup via exec() as a last resort
	if ((DIRECTORY_SEPARATOR == '\\') && function_exists('exec'))
	{
		@exec('nslookup -type=' . escapeshellarg($type) . ' ' . escapeshellarg($host_fqdn), $output);

		// If output is empty, the nslookup failed
		if (empty($output))
		{
			return NULL;
		}

		foreach ($output as $line)
		{
			$line = trim($line);

			if (empty($line))
			{
				continue;
			}

			// Squash tabs and multiple whitespaces to a single whitespace.
			$line = preg_replace('/\s+/', ' ', $line);

			switch ($type)
			{
				case 'MX':
					if (stripos($line, "$host MX") === 0)
					{
						return true;
					}
				break;

				case 'NS':
					if (stripos($line, "$host nameserver") === 0)
					{
						return true;
					}
				break;

				case 'TXT':
					if (stripos($line, "$host text") === 0)
					{
						return true;
					}
				break;

				case 'CNAME':
					if (stripos($line, "$host canonical name") === 0)
					{
						return true;
					}

				default:
				case 'A':
				case 'AAAA':
					if (!empty($host_matches))
					{
						// Second line
						if (stripos($line, "Address: ") === 0)
						{
							return true;
						}
						else
						{
							$host_matches = false;
						}
					}
					else if (stripos($line, "Name: $host") === 0)
					{
						// First line
						$host_matches = true;
					}
				break;
			}
		}

		return false;
	}

	return NULL;
}

/**
* Handler for init calls in phpBB. This function is called in user::setup();
* This function supports hooks.
*/
function phpbb_user_session_handler()
{
	global $phpbb_hook;

	if (!empty($phpbb_hook) && $phpbb_hook->call_hook(__FUNCTION__))
	{
		if ($phpbb_hook->hook_return(__FUNCTION__))
		{
			return $phpbb_hook->hook_return_result(__FUNCTION__);
		}
	}

	return;
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
				$config['gzip_compress_runtime'] = (isset($config['gzip_compress_runtime']) ? $config['gzip_compress_runtime'] : $config['gzip_compress']);
				if (!empty($config['gzip_compress_runtime']))
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
			$status_not_found_array = array('ERROR_NO_ATTACHMENT', 'NO_FORUM', 'NO_TOPIC', 'NO_USER');
			if (in_array($msg_text, $status_not_found_array))
			{
				if (!defined('STATUS_404')) define('STATUS_404', true);
			}
			message_die($msg_code, $msg_text, $msg_title, $errline, $errfile, '');
	}
}

/**
* HTML Message
*/
function html_message($msg_title, $msg_text, $return_url)
{
	global $lang;
	$encoding_charset = !empty($lang['ENCODING']) ? $lang['ENCODING'] : 'UTF-8';

	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
	echo '<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">';
	echo '<head>';
	echo '<meta http-equiv="content-type" content="text/html; charset=' . $encoding_charset . '" />';
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
	global $db, $cache, $config, $auth, $user, $lang, $template, $images, $theme, $tree;
	global $table_prefix, $SID, $_SID;
	global $gen_simple_header, $starttime, $base_memory_usage, $do_gzip_compress;
	global $ip_cms, $cms_config_vars, $cms_config_global_blocks, $cms_config_layouts, $cms_page;
	global $nav_separator, $nav_links;
	// Global vars needed by page header, but since we are in message_die better use default values instead of the assigned ones in case we are dying before including page_header.php
	/*
	global $meta_content;
	global $nav_pgm, $nav_add_page_title, $skip_nav_cat, $start;
	global $breadcrumbs;
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

	// Get SQL error if we are debugging. Do this as soon as possible to prevent subsequent queries from overwriting the status of sql_error()
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

	if(empty($user->data) && (($msg_code == GENERAL_MESSAGE) || ($msg_code == GENERAL_ERROR)))
	{
		// Start session management
		$user->session_begin();
		$auth->acl($user->data);
		$user->setup();
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

	if (!defined('IN_ADMIN') && !defined('IN_CMS') && !defined('HEADER_INC_COMPLETED') && ($msg_code != CRITICAL_ERROR))
	{
		if (!defined('TPL_HAS_DIED'))
		{
			$template->assign_var('HAS_DIED', true);
			define('TPL_HAS_DIED', true);
		}
		$header_tpl = empty($gen_simple_header) ? 'overall_header.tpl' : 'simple_header.tpl';
		$template->set_filenames(array('overall_header' => $header_tpl));
		$template->pparse('overall_header');
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
			// Critical errors mean we cannot rely on _ANY_ DB information being available so we're going to dump out a simple echo'd statement

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
		if (defined('STATUS_404'))
		{
			send_status_line(404, 'Not Found');
		}

		if (defined('STATUS_503'))
		{
			send_status_line(503, 'Service Unavailable');
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

		if (!defined('IN_CMS'))
		{
			$template->pparse('message_body');
		}

		// If we have already defined the var in header, let's output it in footer as well
		if(defined('TPL_HAS_DIED'))
		{
			$template->assign_var('HAS_DIED', true);
		}

		if (!defined('IN_ADMIN'))
		{
			$template_to_parse = defined('IN_CMS') ? 'message_body' : '';
			$parse_template = defined('IN_CMS') ? false : true;
			page_footer(true, $template_to_parse, $parse_template);
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

//
// Truncates HTML strings cleanly
// Taken from code in http://stackoverflow.com/questions/1193500/php-truncate-html-ignoring-tags
//
function truncate_html_string($text, $length, $ellipsis = '...')
{
	if (strlen(preg_replace(array('/<.*?>/', '/&#?[a-zA-Z0-9]+;/'), array('', ' '), $text)) <= $length)
	{
		return $text;
	}

	$printed_length = 0;
	$position = 0;
	$tags = array();
	$clean_text = '';
	while ($printed_length < $length && preg_match('{</?([a-z]+)[^>]*>|&#?[a-zA-Z0-9]+;}', $text, $match, PREG_OFFSET_CAPTURE, $position))
	{
		list($tag, $tag_position) = $match[0];

		// append text leading up to the tag.
		$str = substr($text, $position, $tag_position - $position);
		if ($printed_length + strlen($str) > $length)
		{
			break;
		}

		$clean_text .= $str;
		$printed_length += strlen($str);

		if ($tag[0] == '&')
		{
			// Handle the entity.
			$clean_text .= $tag;
			$printed_length++;
		}
		else
		{
			// Handle the tag.
			$tag_name = $match[1][0];
			if ($tag[1] == '/')
			{
				// This is a closing tag.

				$opening_tag = array_pop($tags);
				assert($opening_tag == $tag_name); // check that tags are properly nested.

				$clean_text .= $tag;
			}
			else if ($tag[strlen($tag) - 2] == '/')
			{
				// Self-closing tag.
				$clean_text .= $tag;
			}
			else
			{
				// Opening tag.
				$clean_text .= $tag;
				$tags[] = $tag_name;
			}
		}

		// Continue after the tag.
		$position = $tag_position + strlen($tag);
	}

	// Print any remaining text.
	if ($printed_length < $length && $position < strlen($text))
	{
		$max_length = $length - $printed_length;
		$utf8_length = 0;
		while ($utf8_length < $max_length)
		{
			$char = substr($text, $position + $utf8_length, 1);
			// UTF-8 character encoding - BEGIN
			$code = ord($char);
			if ($code >= 0x80)
			{
				if ($code < 0xE0)
				{
					// Two byte
					if (($max_length - $utf8_length) >= 2)
					{
						$utf8_length = $utf8_length + 2;
					}
					else
					{
						break;
					}
				}
				elseif ($code1 < 0xF0)
				{
					// Three byte
					if (($max_length - $utf8_length) >= 3)
					{
						$utf8_length = $utf8_length + 3;
					}
					else
					{
						break;
					}
				}
			}
			else
			{
				$utf8_length = $utf8_length + 1;
			}
			// UTF-8 character encoding - END
		}
		$clean_text .= substr($text, $position, $utf8_length);
	}
	$clean_text .= $ellipsis;

	// Close any open tags.
	while (!empty($tags))
	{
		$clean_text .= '</' . array_pop($tags) . '>';
	}
	return $clean_text;
}

?>