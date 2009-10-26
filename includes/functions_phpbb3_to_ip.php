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


/**
* Alias for user class
*/
class user
{

	var $lang = array();
	var $help = array();
	var $theme = array();
	var $date_format;
	var $timezone;
	var $dst;

	var $lang_name = false;
	var $lang_id = false;
	var $lang_path;
	var $img_lang;
	var $img_array = array();

	// Able to add new option (id 7)
	var $keyoptions = array('viewimg' => 0, 'viewflash' => 1, 'viewsmilies' => 2, 'viewsigs' => 3, 'viewavatars' => 4, 'viewcensors' => 5, 'attachsig' => 6, 'bbcode' => 8, 'smilies' => 9, 'popuppm' => 10);
	var $keyvalues = array();

	var $cookie_data = array();
	var $page = array();
	var $data = array();
	var $browser = '';
	var $forwarded_for = '';
	var $host = '';
	var $session_id = '';
	var $ip = '';
	var $load = 0;
	var $time_now = 0;
	var $update_session_page = true;

	/**
	* Session begin
	*/
	function session_begin()
	{
		global $userdata, $user_ip;

		$userdata = session_pagestart($user_ip);

		return true;
	}

	/**
	* User setup
	*/
	function setup()
	{
		global $userdata, $lang;

		init_userprefs($userdata);

		$this->data = &$userdata;
		$this->lang = &$lang;

		$this->data['is_registered'] = $userdata['session_logged_in'] ;

		return true;
	}

	/**
	* More advanced language substitution
	* Function to mimic sprintf() with the possibility of using phpBB's language system to substitute nullar/singular/plural forms.
	* Params are the language key and the parameters to be substituted.
	* This function/functionality is inspired by SHS` and Ashe.
	*
	* Example call: <samp>$user->lang('NUM_POSTS_IN_QUEUE', 1);</samp>
	*/
	function lang()
	{
		$args = func_get_args();
		$key = $args[0];

		if (is_array($key))
		{
			$lang = &$this->lang[array_shift($key)];

			foreach ($key as $_key)
			{
				$lang = &$lang[$_key];
			}
		}
		else
		{
			$lang = &$this->lang[$key];
		}

		// Return if language string does not exist
		if (!isset($lang) || (!is_string($lang) && !is_array($lang)))
		{
			return $key;
		}

		// If the language entry is a string, we simply mimic sprintf() behaviour
		if (is_string($lang))
		{
			if (sizeof($args) == 1)
			{
				return $lang;
			}

			// Replace key with language entry and simply pass along...
			$args[0] = $lang;
			return call_user_func_array('sprintf', $args);
		}

		// It is an array... now handle different nullar/singular/plural forms
		$key_found = false;

		// We now get the first number passed and will select the key based upon this number
		for ($i = 1, $num_args = sizeof($args); $i < $num_args; $i++)
		{
			if (is_int($args[$i]))
			{
				$numbers = array_keys($lang);

				foreach ($numbers as $num)
				{
					if ($num > $args[$i])
					{
						break;
					}

					$key_found = $num;
				}
			}
		}

		// Ok, let's check if the key was found, else use the last entry (because it is mostly the plural form)
		if ($key_found === false)
		{
			$numbers = array_keys($lang);
			$key_found = end($numbers);
		}

		// Use the language string we determined and pass it to sprintf()
		$args[0] = $lang[$key_found];
		return call_user_func_array('sprintf', $args);
	}

	/**
	* Add lang files
	*/
	function add_lang($lang_set, $use_db = false, $use_help = false)
	{
		global $config, $lang;

		if (!empty($lang_set) && !is_array($lang_set))
		{
			$lang_file = IP_ROOT_PATH . 'language/lang_' . $config['default_lang'] . '/' . $lang_set . '.' . PHP_EXT;
			if (@file_exists($lang_file))
			{
				@include($lang_file);
			}
		}
		elseif (!empty($lang_set) && is_array($lang_set))
		{
			foreach ($lang_set as $key => $lang_file)
			{
				$lang_file = IP_ROOT_PATH . 'language/lang_' . $config['default_lang'] . '/' . $lang_file . '.' . PHP_EXT;
				if (@file_exists($lang_file))
				{
					@include($lang_file);
				}
			}
		}

		$this->lang = &$lang;

		return true;
	}

	/**
	* Date format
	*/
	function format_date($timestamp)
	{
		global $config;

		$output_date = create_date_ip($config['default_dateformat'], $timestamp, $config['board_timezone']);

		return $output_date;
	}

}

/**
* Alias for auth class
*/
class auth
{
	var $acl = array();
	var $cache = array();
	var $acl_options = array();
	var $acl_forum_ids = false;

	/**
	* ACL
	*/
	function acl(&$userdata)
	{

		return true;
	}

	/**
	* ACL GET
	*/
	function acl_get($opt, $f = 0)
	{
		global $userdata;
		$return_value = true;

		if (substr($opt, 0, 2) === 'a_')
		{
			$return_value = (($userdata['user_level'] == ADMIN) ? true : false);
		}
		elseif ((substr($opt, 0, 2) === 'm_') || (substr($opt, 0, 2) === 'f_'))
		{
			$return_value = ((($userdata['user_level'] == ADMIN) || ($userdata['user_level'] == MOD)) ? true : false);
		}

		return $return_value;
	}

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

	// Should be a string and lowered
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
* Generate board url (example: http://www.example.com/phpBB)
* @param bool $without_script_path if set to true the script path gets not appended (example: http://www.example.com)
*/
function generate_board_url($without_script_path = false)
{
	return create_server_url($without_script_path);
}

/**
* BBCodes
*/

/**
* nl2br
*/
function bbcode_nl2br($message)
{

	return $message;
}

/**
* Smileys
*/
function smiley_text($message)
{

	return $message;
}

if (empty($bbcode))
{
	include_once(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);
}

class phpbb3_bbcode extends bbcode
{

	/**
	* Smileys
	*/
	function bbcode_second_pass($message, $bbcode_uid, $bbcode_bitfield)
	{
		global $config, $userdata;

		$this->allow_html = ($userdata['user_allowhtml'] && $config['allow_html']) ? true : false;
		$this->allow_bbcode = ($userdata['user_allowbbcode'] && $config['allow_bbcode']) ? true : false;
		$this->allow_smilies = ($userdata['user_allowsmile'] && $config['allow_smilies']) ? true : false;
		$message = $this->parse($message);

		return $message;
	}

}

// Initialazing classes...
$user = new user();
$auth = new auth();
unset($bbcode);
$bbcode = new phpbb3_bbcode();

?>