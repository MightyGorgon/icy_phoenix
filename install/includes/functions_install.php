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

if (!defined('IN_INSTALL'))
{
	define('IN_INSTALL', true);
}

/**
* Some Icy Phoenix Functions
*/
class ip_functions
{
	/*
	* Remove variables created by register_globals from the global scope
	* Thanks to Matt Kavanagh
	*/
	function deregister_globals()
	{
		$not_unset = array(
			'GLOBALS' => true,
			'_GET' => true,
			'_POST' => true,
			'_COOKIE' => true,
			'_REQUEST' => true,
			'_SERVER' => true,
			'_SESSION' => true,
			'_ENV' => true,
			'_FILES' => true,
		);

		// Not only will array_merge and array_keys give a warning if
		// a parameter is not an array, array_merge will actually fail.
		// So we check if _SESSION has been initialised.
		if (!isset($_SESSION) || !is_array($_SESSION))
		{
			$_SESSION = array();
		}

		// Merge all into one extremely huge array; unset this later
		$input = array_merge(
			array_keys($_GET),
			array_keys($_POST),
			array_keys($_COOKIE),
			array_keys($_SERVER),
			array_keys($_SESSION),
			array_keys($_ENV),
			array_keys($_FILES)
		);

		foreach ($input as $varname)
		{
			if (isset($not_unset[$varname]))
			{
				// Hacking attempt. No point in continuing unless it's a COOKIE
				if ($varname !== 'GLOBALS' || isset($_GET['GLOBALS']) || isset($_POST['GLOBALS']) || isset($_SERVER['GLOBALS']) || isset($_SESSION['GLOBALS']) || isset($_ENV['GLOBALS']) || isset($_FILES['GLOBALS']))
				{
					exit;
				}
				else
				{
					$cookie = &$_COOKIE;
					while (isset($cookie['GLOBALS']))
					{
						foreach ($cookie['GLOBALS'] as $registered_var => $value)
						{
							if (!isset($not_unset[$registered_var]))
							{
								unset($GLOBALS[$registered_var]);
							}
						}
						$cookie = &$cookie['GLOBALS'];
					}
				}
			}

			unset($GLOBALS[$varname]);
		}

		unset($input);
	}

	/**
	* Read file content
	*/
	function file_read($filename)
	{
		$handle = @fopen($filename, 'r');
		$content = @fread($handle, filesize($filename));
		@fclose($handle);
		return $content;
	}

	/**
	* Write content to file
	*/
	function file_write($filename, $content)
	{
		$handle = @fopen($filename, 'w');
		$result = @fwrite($handle, $content, strlen($content));
		@fclose($handle);
		return $result;
	}

	/**
	* Edit config.php
	*/
	function fix_config()
	{
		$filename_tmp = IP_ROOT_PATH . 'config.' . PHP_EXT;
		$content = $this->file_read($filename_tmp);
		$content = str_replace('PHPBB_INSTALLED', 'IP_INSTALLED', $content);
		$result = $this->file_write($filename_tmp, $content);
		$result = ($result !== false) ? true : false;
		return $result;
	}

	/**
	* CMS Page Content
	*/
	function cms_page_content()
	{
		$content = '';
		$content .= '<' . '?php' . "\n";
		$content .= '/**' . "\n";
		$content .= '*' . "\n";
		$content .= '* @package Icy Phoenix' . "\n";
		$content .= '* @version $Id$' . "\n";
		$content .= '* @copyright (c) 2008 Icy Phoenix' . "\n";
		$content .= '* @license http://opensource.org/licenses/gpl-license.php GNU Public License' . "\n";
		$content .= '*' . "\n";
		$content .= '*/' . "\n";
		$content .= '' . "\n";
		$content .= 'define(\'IN_CMS_PAGE\', true);' . "\n";
		$content .= 'define(\'IN_ICYPHOENIX\', true);' . "\n";
		$content .= 'if (!defined(\'IP_ROOT_PATH\')) define(\'IP_ROOT_PATH\', \'./\');' . "\n";
		$content .= 'if (!defined(\'PHP_EXT\')) define(\'PHP_EXT\', substr(strrchr(__FILE__, \'.\'), 1));' . "\n";
		$content .= 'include(IP_ROOT_PATH . \'common.\' . PHP_EXT);' . "\n";
		$content .= 'include(IP_ROOT_PATH . \'includes/new_page_common.\' . PHP_EXT);' . "\n";
		$content .= '' . "\n";
		$content .= '?' . '>';
		return $content;
	}

	/**
	* Testing File Creation
	*/
	public static function file_creation($path)
	{
		$test_file = $path . 'icy_phoenix_testing_write_access_permissions.test';

		// Check if the test file already exists...
		if (file_exists($test_file))
		{
			if (!@unlink($test_file))
			{
				// It seems we haven't deleted it... try to change permissions
				if (!@chmod($test_file, 0666))
				{
					return false;
				}
				else
				{
					if (!@unlink($test_file))
					{
						return false;
					}
				}
			}
		}

		// Attempt to create a new file...
		if (!@touch($test_file))
		{
			return false;
		}
		else
		{
			if (!@chmod($test_file, 0666))
			{
				if (!@unlink($test_file))
				{
					return false;
				}
				else
				{
					return true;
				}
			}
			else
			{
				// We really want to make sure...
				if (file_exists($test_file))
				{
					if (!@unlink($test_file))
					{
						return false;
					}
					else
					{
						return true;
					}
				}
				else
				{
					return false;
				}
			}
		}
		return true;
	}

	/**
	* Check MEM Limit
	*/
	function check_mem_limit()
	{
		$mem_limit = @ini_get('memory_limit');
		if (!empty($mem_limit))
		{
			$unit = strtolower(substr($mem_limit, -1, 1));
			$mem_limit = (int) $mem_limit;

			if ($unit == 'k')
			{
				$mem_limit = floor($mem_limit / 1024);
			}
			elseif ($unit == 'g')
			{
				$mem_limit *= 1024;
			}
			elseif (is_numeric($unit))
			{
				$mem_limit = floor((int) ($mem_limit . $unit) / 1048576);
			}
			$mem_limit = max(128, $mem_limit) . 'M';
		}
		else
		{
			$mem_limit = '128M';
		}
		return $mem_limit;
	}

	function create_server_url()
	{
		// usage: $server_url = create_server_url();
		global $config;

		$server_protocol = ($config['cookie_secure']) ? 'https://' : 'http://';
		$server_name = preg_replace('#^\/?(.*?)\/?$#', '\1', trim($config['server_name']));
		$server_port = ($config['server_port'] <> 80) ? ':' . trim($config['server_port']) : '';
		$script_name = preg_replace('/^\/?(.*?)\/?$/', '\1', trim($config['script_path']));
		$script_name = ($script_name == '') ? '' : '/' . $script_name;
		$server_url = $server_protocol . $server_name . $server_port . $script_name;
		while(substr($server_url, -1, 1) == '/')
		{
			$server_url = substr($server_url, 0, -1);
		}
		$server_url = $server_url . '/';

		//$server_url = 'icyphoenix.com/';

		return $server_url;
	}

	function ip_realpath($path)
	{
		return (!@function_exists('realpath') || !@realpath(IP_ROOT_PATH . 'includes/functions.' . PHP_EXT)) ? $path : @realpath($path);
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

			$result = (defined('STRIP') && STRIP) ? stripslashes($result) : $result;
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
				$this->set_var($k, $k, $key_type);
				if ($type == 'array' && is_array($v))
				{
					foreach ($v as $_k => $_v)
					{
						if (is_array($_v))
						{
							$_v = null;
						}
						$this->set_var($_k, $_k, $sub_key_type);
						$this->set_var($var[$k][$_k], $_v, $sub_type, $multibyte);
					}
				}
				else
				{
					if ($type == 'array' || is_array($v))
					{
						$v = null;
					}
					$this->set_var($var[$k], $v, $type, $multibyte);
				}
			}
		}
		else
		{
			$this->set_var($var, $var, $type, $multibyte);
		}

		return $var;
	}

	//
	// Append $SID to a url. Borrowed from phplib and modified. This is an
	// extra routine utilised by the session code above and acts as a wrapper
	// around every single URL and form action. If you replace the session
	// code you must include this routine, even if it's empty.
	//
	public static function append_sid($url, $non_html_amp = false, $char_conversion = false)
	{
		global $SID;

		if (!empty($SID) && !preg_match('#sid=#', $url))
		{
			if ($char_conversion == true)
			{
				$url .= ((strpos($url, '?') !== false) ? '%26' : '?') . $SID;
			}
			else
			{
				$url .= ((strpos($url, '?') !== false) ? (($non_html_amp) ? '&' : '&amp;') : '?') . $SID;
			}
		}

		return $url;
	}

	// Guess an initial language ... borrowed from phpBB 2.2 it's not perfect,
	// really it should do a straight match first pass and then try a "fuzzy"
	// match on a second pass instead of a straight "fuzzy" match.
	function guess_lang()
	{

		// The order here _is_ important, at least for major_minor matches.
		// Don't go moving these around without checking with me first - psoTFX
		$match_lang = array(
			'arabic'											=> 'ar([_-][a-z]+)?',
			'bulgarian'										=> 'bg',
			'catalan'											=> 'ca',
			'czech'												=> 'cs',
			'danish'											=> 'da',
			'german'											=> 'de([_-][a-z]+)?',
			'english'											=> 'en([_-][a-z]+)?',
			'estonian'										=> 'et',
			'finnish'											=> 'fi',
			'french'											=> 'fr([_-][a-z]+)?',
			'greek'												=> 'el',
			'spanish_argentina'						=> 'es[_-]ar',
			'spanish'											=> 'es([_-][a-z]+)?',
			'gaelic'											=> 'gd',
			'galego'											=> 'gl',
			'gujarati'										=> 'gu',
			'hebrew'											=> 'he',
			'hindi'												=> 'hi',
			'croatian'										=> 'hr',
			'hungarian'										=> 'hu',
			'icelandic'										=> 'is',
			'indonesian'									=> 'id([_-][a-z]+)?',
			'italian'											=> 'it([_-][a-z]+)?',
			'japanese'										=> 'ja([_-][a-z]+)?',
			'korean'											=> 'ko([_-][a-z]+)?',
			'latvian'											=> 'lv',
			'lithuanian'									=> 'lt',
			'macedonian'									=> 'mk',
			'dutch'												=> 'nl([_-][a-z]+)?',
			'norwegian'										=> 'no',
			'punjabi'											=> 'pa',
			'polish'											=> 'pl',
			'portuguese_brazil'						=> 'pt[_-]br',
			'portuguese'									=> 'pt([_-][a-z]+)?',
			'romanian'										=> 'ro([_-][a-z]+)?',
			'russian'											=> 'ru([_-][a-z]+)?',
			'slovenian'										=> 'sl([_-][a-z]+)?',
			'albanian'										=> 'sq',
			'serbian'											=> 'sr([_-][a-z]+)?',
			'slovak'											=> 'sv([_-][a-z]+)?',
			'swedish'											=> 'sv([_-][a-z]+)?',
			'thai'												=> 'th([_-][a-z]+)?',
			'turkish'											=> 'tr([_-][a-z]+)?',
			'ukranian'										=> 'uk([_-][a-z]+)?',
			'urdu'												=> 'ur',
			'viatnamese'									=> 'vi',
			'chinese_traditional_taiwan'	=> 'zh[_-]tw',
			'chinese_simplified'					=> 'zh',
		);

		if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
		{
			$accept_lang_ary = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
			for ($i = 0; $i < sizeof($accept_lang_ary); $i++)
			{
				@reset($match_lang);
				while (list($lang, $match) = each($match_lang))
				{
					if (preg_match('#' . $match . '#i', trim($accept_lang_ary[$i])))
					{
						if (@file_exists(@$this->ip_realpath('language/lang_' . $lang)))
						{
							return $lang;
						}
					}
				}
			}
		}
		return 'english';
	}
}

/**
* Some functions used to process text
*/
class mg_functions
{
	public static function html_text_cleaning($text)
	{
		$look_up_array = array(
			"&agrave;",
			"&egrave;",
			"&igrave;",
			"&ograve;",
			"&ugrave;",
			"&eacute;",
			"&nbsp;",
		);

		$replacement_array = array(
			"à",
			"è",
			"ì",
			"ò",
			"ù",
			"é",
			" ",
		);

		$text = str_replace($look_up_array, $replacement_array, $text);

		return $text;
	}

	public static function html_text_format($text)
	{
		$look_up_array = array(
			"à",
			"è",
			"ì",
			"ò",
			"ù",
			"é",
			" ",
		);

		$replacement_array = array(
			"&agrave;",
			"&egrave;",
			"&igrave;",
			"&ograve;",
			"&ugrave;",
			"&eacute;",
			"&nbsp;",
		);

		$text = str_replace($look_up_array, $replacement_array, $text);

		return $text;
	}

	public static function text_replace($text)
	{
		$look_up_array = array(
			'phpbbxs.eu',
		);

		$replacement_array = array(
			'icyphoenix.com',
		);

		$text = str_replace($look_up_array, $replacement_array, $text);

		return $text;
	}

	public static function custom_text_replace($text)
	{
		//Comment this line if you want to use this function...
		return $text;

		$look_up_array = array(
			'phpbbxs.eu',
		);

		$replacement_array = array(
			'icyphoenix.com',
		);

		$text = str_replace($look_up_array, $replacement_array, $text);

		return $text;
	}

	public static function smileys_list()
	{
		global $db, $config;
		$server_url = ip_functions::create_server_url();
		$smileys_path = $server_url . (!empty($config['smilies_path']) ? ($config['smilies_path'] . '/') : 'images/smiles/');

		$sql = "SELECT code, smile_url FROM " . SMILIES_TABLE . " ORDER BY smilies_order";
		if ($result = $db->sql_query($sql))
		{
			$smileys_list = array();
			while ($row = $db->sql_fetchrow($result))
			{
				$arr = array(
					'code' => $row['code'],
					'replace' => '[img]' . $smileys_path . $row['smile_url'] . '[/img]'
				);
				$smileys_list[] = $arr;
			}
			$db->sql_freeresult($result);
			unset($arr);
		}
		return $smileys_list;
	}

	public static function smileys_replace_sets($smileys_list)
	{
		$smileys_replace_sets = array();
		$smileys_replace_sets['search_prev'] = array();
		$smileys_replace_sets['search_next'] = array();
		$smileys_replace_sets['replace_prev'] = array();
		$smileys_replace_sets['replace_next'] = array();
		for($i = 0; $i < sizeof($smileys_list); $i++)
		{
			$smileys_replace_sets['search_prev'][] = ' ' . $smileys_list[$i]['code'];
			$smileys_replace_sets['search_next'][] = $smileys_list[$i]['code'] . ' ';
			$smileys_replace_sets['replace_prev'][] = ' ' . $smileys_list[$i]['replace'];
			$smileys_replace_sets['replace_next'][] = $smileys_list[$i]['replace'] . ' ';
		}
		return $smileys_replace_sets;
	}

	public static function smileys_replace($text, $smileys_replace_sets)
	{
		$text = str_replace($smileys_replace_sets['search_prev'], $smileys_replace_sets['replace_prev'], $text);
		$text = str_replace($smileys_replace_sets['search_next'], $smileys_replace_sets['replace_next'], $text);
		return $text;
	}

	public static function old_bbcode_replace($text)
	{
		$look_up_array = array(
			'[xs]',
			'[ipstaff]',
			'[ipstaffstatic]',
			'[ipoverview]',
			'[iplicense]',
			'[iprequirements]',
			'[ipinstall]',
			'[upgrade]',
			'[upgradebb]',
			'[upgradexs]',
			'[upgradeip]',
		);

		$replacement_array = array(
			'[langvar]BBC_IP_CREDITS[/langvar]',
			'[langvar]BBC_IP_CREDITS[/langvar]',
			'[langvar]BBC_IP_CREDITS_STATIC[/langvar]',
			'[langvar]BBC_Overview[/langvar]',
			'[langvar]BBC_License[/langvar]',
			'[langvar]BBC_Requirements[/langvar]',
			'[langvar]BBC_Fresh_Installation[/langvar]',
			'[langvar]BBC_Upgrade_phpbb[/langvar]',
			'[langvar]BBC_Upgrade_phpbb[/langvar]',
			'[langvar]BBC_Upgrade_XS[/langvar]',
			'[langvar]BBC_Upgrade_IP[/langvar]',
		);

		$text = str_replace($look_up_array, $replacement_array, $text);

		return $text;
	}

	public static function old_bbcode_replace_ip2($text)
	{
		$look_up_array = array(
			'[langvar]BBC_IP_CREDITS[/langvar]',
			'[langvar]BBC_IP_CREDITS_STATIC[/langvar]',
			'[langvar]BBC_Overview[/langvar]',
			'[langvar]BBC_License[/langvar]',
			'[langvar]BBC_Requirements[/langvar]',
			'[langvar]BBC_Fresh_Installation[/langvar]',
			'[langvar]BBC_Upgrade_phpbb[/langvar]',
			'[langvar]BBC_Upgrade_XS[/langvar]',
			'[langvar]BBC_Upgrade_IP[/langvar]',
		);

		$replacement_array = array(
			'[url=docs/readme_english.html#license]Readme[/url]',
			'[url=docs/readme_english.html#license]Readme[/url]',
			'[url=docs/readme_english.html#overview]Readme[/url]',
			'[url=docs/readme_english.html#license]Readme[/url]',
			'[url=docs/readme_english.html#requirements]Readme[/url]',
			'[url=docs/readme_english.html#fresh_installation]Readme[/url]',
			'[url=docs/readme_english.html#upgrade_phpbb]Readme[/url]',
			'[url=docs/readme_english.html#upgrade_xs]Readme[/url]',
			'[url=docs/readme_english.html#upgrade_ip]Readme[/url]',
		);

		$text = str_replace($look_up_array, $replacement_array, $text);

		return $text;
	}

	public static function bbcuid_clean($text, $id = false)
	{
		if ($id != false)
		{
			$text = str_replace(':' . $id, '', $text);
		}
		else
		{
			// standard phpBB bbcuid (10 hex chars)
			$text = preg_replace("/\:([a-f0-9]{10})/s", '', $text);
			// phpBB 3 bbcuid (5 alphanumeric chars)
			//$text = preg_replace("/\:([a-z0-9]{5})/s", '', $text);
			// phpBB 3 bbcuid (8 alphanumeric chars)
			//$text = preg_replace("/\:([a-z0-9]{8})/s", '', $text);
		}
		return $text;
	}

	/*
	* Creates a short url to be used in replace, without server_protocol
	*/
	public static function create_short_server_url()
	{
		// usage: $server_url = create_short_server_url();
		global $config;

		if (!empty($config['short_site_url']))
		{
			return $config['short_site_url'];
		}

		$server_protocol = ($config['cookie_secure']) ? 'https://' : 'http://';
		$server_name = preg_replace('#^\/?(.*?)\/?$#', '\1', trim($config['server_name']));
		$server_port = ($config['server_port'] <> 80) ? ':' . trim($config['server_port']) : '';
		$script_name = preg_replace('/^\/?(.*?)\/?$/', '\1', trim($config['script_path']));
		$script_name = ($script_name == '') ? '' : '/' . $script_name;
		//$server_url = $server_protocol . $server_name . $server_port . $script_name;
		$server_url = $server_name . $server_port . $script_name;
		while(substr($server_url, -1, 1) == '/')
		{
			$server_url = substr($server_url, 0, -1);
		}
		$server_url = $server_url . '/';

		//$server_url = 'icyphoenix.com/';

		$config['short_site_url'] = $server_url;
		return $server_url;
	}

	public static function img_replace($text)
	{
		global $config;
		// We changed the constant, so we cannot use it any more...
		//$posted_images_folder = str_replace(IP_ROOT_PATH, '', POSTED_IMAGES_PATH);
		$posted_images_folders = array('files/posted_images/', 'files/images/');
		foreach ($posted_images_folders as $posted_images_folder)
		{
			$server_url = mg_functions::create_short_server_url();
			$look_up = "/" . str_replace('/', '\/', $server_url . $posted_images_folder) . "user_([0-9]{1,6})_/";
			$replacement = $server_url . $posted_images_folder . "$1/";
			$text = preg_replace($look_up, $replacement, $text);
		}
		return $text;
	}

	public static function img_replace_ip2($text)
	{
		global $config;
		$old_folder = 'files/posted_images/';
		$new_folder = str_replace(IP_ROOT_PATH, '', POSTED_IMAGES_PATH);
		$server_url = mg_functions::create_short_server_url();
		$look_up = "/" . str_replace('/', '\/', $server_url . $old_folder) . "([0-9]{1,6})\//";
		$replacement = $server_url . $new_folder . "$1/";
		$text = preg_replace($look_up, $replacement, $text);
		$text = str_replace(array('posted_img_list.php', 'posted_img_list_thumbnail.php', 'posted_img_thumbnail.php'), array('images_list.php', 'image_thumbnail_s.php', 'image_thumbnail.php'), $text);
		return $text;
	}
}

/*
* Some SQL functions
* Borrowed from phpBB package
*/
class ip_sql
{
	/**
	* Execute SQL and store errors
	*/
	function _sql($sql, &$errored, &$error_ary, $echo_dot = true)
	{
		global $db;
		if (!($result = $db->sql_query($sql)))
		{
			$errored = true;
			$error_ary['sql'][] = (is_array($sql)) ? $sql[$i] : $sql;
			$error_ary['error_code'][] = $db->sql_error();
		}
		if ($echo_dot)
		{
			echo ". \n";
			flush();
		}
		return $result;
	}

	/**
	* Get config values from DB
	*/
	function get_config_value($config_name)
	{
		global $db, $table_prefix;
		$sql = "SELECT config_value
						FROM " . CONFIG_TABLE . "
						WHERE config_name = '" . $config_name . "'
						LIMIT 1";
		if (!($result = $db->sql_query($sql)))
		{
			$config_value = false;
		}
		else
		{
			$row = $db->sql_fetchrow($result);
			$config_value = !empty($row['config_value']) ? $row['config_value'] : '';
		}
		return $config_value;
	}

	/**
	* Converts the DB to UTF-8
	*/
	function convert_utf8($echo_results = false)
	{
		global $db, $dbname, $table_prefix;

		$db->sql_return_on_error(true);

		$sql = "ALTER DATABASE {$db->sql_escape($dbname)}
			CHARACTER SET utf8
			DEFAULT CHARACTER SET utf8
			COLLATE utf8_bin
			DEFAULT COLLATE utf8_bin";
		$db->sql_query($sql);

		$sql = "SHOW TABLES";
		$result = $db->sql_query($sql);
		while ($row = $db->sql_fetchrow($result))
		{
			// This assignment doesn't work...
			//$table = $row[0];

			$current_item = each($row);
			$table = $current_item['value'];
			reset($row);

			$sql = "ALTER TABLE {$db->sql_escape($table)}
				DEFAULT CHARACTER SET utf8
				COLLATE utf8_bin";
			$db->sql_query($sql);

			if (!empty($echo_results))
			{
				echo("&bull;&nbsp;Table&nbsp;<b style=\"color: #dd2222;\">$table</b> converted to UTF-8<br />\n");
			}

			$sql = "SHOW FIELDS FROM {$db->sql_escape($table)}";
			$result_fields = $db->sql_query($sql);

			while ($row_fields = $db->sql_fetchrow($result_fields))
			{
				// These assignments don't work...
				/*
				$field_name = $row_fields[0];
				$field_type = $row_fields[1];
				$field_null = $row_fields[2];
				$field_key = $row_fields[3];
				$field_default = $row_fields[4];
				$field_extra = $row_fields[5];
				*/

				$field_name = $row_fields['Field'];
				$field_type = $row_fields['Type'];
				$field_null = $row_fields['Null'];
				$field_key = $row_fields['Key'];
				$field_default = $row_fields['Default'];
				$field_extra = $row_fields['Extra'];

				// Let's remove BLOB and BINARY for now...
				//if ((strpos(strtolower($field_type), 'char') !== false) || (strpos(strtolower($field_type), 'text') !== false) || (strpos(strtolower($field_type), 'blob') !== false) || (strpos(strtolower($field_type), 'binary') !== false))
				if ((strpos(strtolower($field_type), 'char') !== false) || (strpos(strtolower($field_type), 'text') !== false))
				{
					//$sql_fields = "ALTER TABLE {$db->sql_escape($table)} CHANGE " . $db->sql_escape($field_name) . " " . $db->sql_escape($field_name) . " " . $db->sql_escape($field_type) . " CHARACTER SET utf8 COLLATE utf8_bin";

					$sql_fields = "ALTER TABLE {$db->sql_escape($table)} CHANGE " . $db->sql_escape($field_name) . " " . $db->sql_escape($field_name) . " " . $db->sql_escape($field_type) . " CHARACTER SET utf8 COLLATE utf8_bin " . (($field_null != 'YES') ? "NOT " : "") . "NULL DEFAULT " . (($field_default != 'None') ? ((!empty($field_default) || !is_null($field_default)) ? (is_string($field_default) ? ("'" . $db->sql_escape($field_default) . "'") : $field_default) : (($field_null != 'YES') ? "''" : "NULL")) : "''");
					$db->sql_query($sql_fields);

					if (!empty($echo_results))
					{
						echo("\t&nbsp;&nbsp;&raquo;&nbsp;Field&nbsp;<b style=\"color: #4488aa;\">$field_name</b> (in table <b style=\"color: #009900;\">$table</b>) converted to UTF-8<br />\n");
					}
				}
			}

			if (!empty($echo_results))
			{
				echo("<br />\n");
				flush();
			}
		}

		$db->sql_return_on_error(false);
		return true;
	}

}


/**
* Icy Phoenix Template
*/
class ip_page
{

	var $tbl_h_l = '<table class="roundedtop" width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td width="27" align="right" valign="bottom"><img class="topcorners" src="style/tbl/tbl_h_l.gif" width="27" height="29" alt="" /></td>';
	var $tbl_h_c = '<td class="roundedhc" width="100%" align="center">';
	var $tbl_h_r = '</td><td width="27" align="left" valign="bottom"><img class="topcorners" src="style/tbl/tbl_h_r.gif" width="27" height="29" alt="" /></td></tr></table>';

	var $tbl_f_l = '<table class="roundedbottom"><tr><td width="4" align="right" valign="top"><img src="style/tbl/tbl_f_l.gif" width="4" height="3" alt="" /></td>';
	var $tbl_f_c = '<td class="roundedfc" width="100%" align="center">';
	var $tbl_f_r = '</td><td width="4" align="left" valign="top"><img src="style/tbl/tbl_f_r.gif" width="4" height="3" alt="" /></td></tr></table>';

	var $img_ok = '<img src="style/b_ok.png" alt="" title="" />';
	var $img_error = '<img src="style/b_cancel.png" alt="" title="" />';

	var $color_ok = '#228822';
	var $color_error = '#dd3333';

	var $color_blue = '#224488';
	var $color_green = '#228822';
	var $color_orange = '#ff5500';
	var $color_purple = '#880088';
	var $color_red = '#dd2222';

	function page_header($page_title, $content, $form_action = false, $write_form = true, $extra_css = false, $extra_js = false, $meta_refresh = '')
	{
		global $lang;
		$encoding_charset = !empty($lang['ENCODING']) ? $lang['ENCODING'] : 'UTF-8';

		echo('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' . "\n");
		echo('<html>' . "\n");
		echo('<head>' . "\n");
		echo('	<meta http-equiv="Content-Type" content="text/html; charset=' . $encoding_charset . '" />' . "\n");
		echo('	<meta http-equiv="content-style-type" content="text/css" />' . "\n");
		echo('	<meta name="author" content="Icy Phoenix Team" />' . "\n");
		if ($meta_refresh != '')
		{
			echo('	' . $meta_refresh . "\n");
		}
		echo('	<title>' . $page_title . ' :: Icy Phoenix</title>' . "\n");
		echo('	<link rel="stylesheet" href="style/style.css" type="text/css" />' . "\n");
		if (!empty($extra_css))
		{
			for ($i = 0; $i < sizeof($extra_css); $i++)
			{
				echo('	<link rel="stylesheet" href="' . $extra_css[$i] . '" type="text/css" />' . "\n");
			}
		}
		echo('	<link rel="shortcut icon" href="style/favicon.ico" />' . "\n");
		echo('	<script type="text/javascript" src="style/ip_scripts.js"></script>' . "\n");
		if (!empty($extra_js))
		{
			for ($i = 0; $i < sizeof($extra_js); $i++)
			{
				echo('	<script type="text/javascript" src="' . $extra_js[$i] . '"></script>' . "\n");
			}
		}
		echo('	<!--[if lt IE 7]>' . "\n");
		echo('	<script type="text/javascript" src="style/pngfix.js"></script>' . "\n");
		echo('	<![endif]-->' . "\n");
		echo('</head>' . "\n");
		echo('<body>' . "\n");
		echo('<span><a id="top">&nbsp;</a></span>' . "\n");
		echo('<div id="global-wrapper"><div id="wrapper"><div id="wrapper1"><div id="wrapper2"><div id="wrapper3"><div id="wrapper4"><div id="wrapper5"><div id="wrapper6"><div id="wrapper7"><div id="wrapper-inner">' . "\n");

		if ($write_form == true)
		{
			$form_action = $form_action ? $form_action : 'install.' . PHP_EXT;
			echo('<form action="' . $form_action . '" name="install" method="post">' . "\n");
		}

		echo('<table id="forumtable">' . "\n");

		echo('<tr>' . "\n");
		echo('	<td width="100%" colspan="3" valign="top">' . "\n");
		echo('		<div id="top_logo"><div style="margin-top: 20px; margin-left: 10px;"><a href="http://www.icyphoenix.com" title="Icy Phoenix"><img src="style/sitelogo_small.png" alt="Icy Phoenix" title="Icy Phoenix" /></a></div></div>' . "\n");
		echo('	</td>' . "\n");
		echo('</tr>' . "\n");

		echo('<tr>' . "\n");
		echo('	<td class="forum-buttons tw100pct" colspan="3">' . "\n");
		echo('	&nbsp;<img src="style/menu_sep.gif" alt="" />&nbsp;<a href="http://www.icyphoenix.com/">Icy Phoenix</a>&nbsp;<img src="style/menu_sep.gif" alt="" />&nbsp;' . "\n");
		echo('	</td>' . "\n");
		echo('</tr>' . "\n");

		echo('<tr>' . "\n");
		echo('	<td width="100%" colspan="3" style="padding-left: 10px; padding-right: 10px;">' . "\n");

		if (!empty($content))
		{
			echo('	<table class="forumline">' . "\n");
			echo('		<tr><td class="row-header" colspan="2"><span>' . $page_title . '</span></td></tr>' . "\n");
			echo('		<tr><td class="row-post" colspan="2"><br /><div class="post-text">' . $content . '</div><br /></td></tr>' . "\n");
			echo('	</table>' . "\n");
		}
	}

	function page_footer($write_form = true)
	{
		echo('	</td>' . "\n");
		echo('</tr>' . "\n");
		echo('<tr>' . "\n");
		echo('	<td colspan="3">' . "\n");
		echo('	<div id="bottom_logo_ext">' . "\n");
		echo('	<div id="bottom_logo">' . "\n");
		echo('		<table>' . "\n");
		echo('			<tr>' . "\n");
		echo('				<td class="min250 tdalignl tvalignm tdnw"><span class="copyright">&nbsp;Powered by <a href="http://www.icyphoenix.com/" target="_blank">Icy Phoenix</a> based on <a href="http://www.phpbb.com/" target="_blank">phpBB</a></span></td>' . "\n");
		echo('				<td class="tdalignc tvalignm tdnw">&nbsp;<br />&nbsp;</td>' . "\n");
		echo('				<td class="min250 tdalignr tvalignm tdnw"><span class="copyright">Design by <a href="http://www.lucalibralato.com/" target="_blank">Luca Libralato</a>&nbsp;</span></td>' . "\n");
		echo('			</tr>' . "\n");
		echo('		</table>' . "\n");
		echo('	</div>' . "\n");
		echo('	</div>' . "\n");
		echo('	</td>' . "\n");
		echo('</tr>' . "\n");
		echo('</table>' . "\n");
		if ($write_form == true)
		{
			echo('</form>' . "\n");
		}
		echo('</div></div></div></div></div></div></div></div></div></div>' . "\n");
		echo('<span><a id="bottom">&nbsp;</a></span>' . "\n");
		echo('</body>' . "\n");
		echo('</html>');
	}

	function box($bg_color, $color, $content)
	{
		$colors_array = array('blue', 'gray', 'green', 'orange', 'red', 'yellow');
		$bg_color = (in_array($bg_color, $colors_array) ? $bg_color : 'red');
		$color = (in_array($color, $colors_array) ? $color : 'red');
		echo('<div class="text_cont_center" style="width:400px;"><div class="text_' . $bg_color . '_cont"><span class="text_' . $color . '">' . $content . '</span></div></div>' . "\n");
	}

	function spoiler($spoiler_id, $content, $direct_output = true)
	{
		global $lang;

		$html = '';
		$html .= '<div class="spoiler">' . "\n";
		$html .= '<div class="code-header" id="spoilerhdr_' . $spoiler_id . '" style="position: relative;">' . $lang['Spoiler'] . ': [ <a href="javascript:void(0)" onclick="ShowHide(\'spoiler_' . $spoiler_id . '\', \'spoiler_h_' . $spoiler_id . '\', \'\'); ShowHide(\'spoilerhdr_' . $spoiler_id . '\', \'spoilerhdr_h_' . $spoiler_id . '\', \'\')">' . $lang['Show'] . '</a> ]</div>' . "\n";
		$html .= '<div class="code-header" id="spoilerhdr_h_' . $spoiler_id . '" style="position: relative; display: none;">' . $lang['Spoiler'] . ': [ <a href="javascript:void(0)" onclick="ShowHide(\'spoiler_' . $spoiler_id . '\', \'spoiler_h_' . $spoiler_id . '\', \'\'); ShowHide(\'spoilerhdr_' . $spoiler_id . '\', \'spoilerhdr_h_' . $spoiler_id . '\', \'\')">' . $lang['Hide'] . '</a> ]</div>' . "\n";
		$html .= '<div class="spoiler-content" id="spoiler_h_' . $spoiler_id . '" style="position: relative; display: none;">' . $content . '</div>' . "\n";
		$html .= '</div>' . "\n";
		if ($direct_output)
		{
			echo($html);
		}
		return($html);
	}

	function table_begin($header, $td_class = '', $colspan = 0, $spoiler_id = false)
	{
		$img_maximise = '';
		$img_minimise = '';
		if (!empty($spoiler_id))
		{
			$img_maximise = '<div class="max-min-right"><img src="style/switch_maximise.gif" onclick="ShowHide(\'' . $spoiler_id . '\',\'' . $spoiler_id . '_h\',\'' . $spoiler_id . '\');" alt="" />&nbsp;</div>';
			$img_minimise = '<div class="max-min-right"><img src="style/switch_minimise.gif" onclick="ShowHide(\'' . $spoiler_id . '\',\'' . $spoiler_id . '_h\',\'' . $spoiler_id . '\');" alt="" />&nbsp;</div>';
			echo('<div id="' . $spoiler_id . '_h" style="display: none;"><table class="forumline"><tr><td class="row-header">' . $img_maximise . '<span>' . $header . '</span></td></tr></table></div>' . "\n");
			echo('<div id="' . $spoiler_id . '">' . "\n");
		}
		echo('<table class="forumline">' . "\n");
		echo('<tr><td class="row-header"' . (($colspan > 0) ? (' colspan="' . $colspan . '"') : '') . '>' . $img_minimise . '<span>' . $header . '</span></td></tr>' . "\n");
		echo('<tr>' . "\n");
		echo('	<td' . (!empty($td_class) ? (' class="' . $td_class . '"') : '') . '>' . "\n");
	}

	function table_end($spoiler_id = false)
	{
		echo('	</td>' . "\n");
		echo('</tr>' . "\n");
		echo('</table>' . "\n");
		if (!empty($spoiler_id))
		{
			echo('</div>' . "\n");
			echo('<script type="text/javascript">' . "\n");
			echo('<!--' . "\n");
			echo('tmp = \'' . $spoiler_id . '\';' . "\n");
			echo('if(GetCookie(tmp) == \'2\')' . "\n");
			echo('{' . "\n");
			echo('	ShowHide(\'' . $spoiler_id . '\',\'' . $spoiler_id . '_h\',\'' . $spoiler_id . '\');' . "\n");
			echo('}' . "\n");
			echo('//-->' . "\n");
			echo('</script>' . "\n");
		}
	}

	function table_r_begin($header)
	{
		echo($this->tbl_h_l . $this->tbl_h_c . '<span class="forumlink">' . $header . '</span>' . $this->tbl_h_r . '<table class="forumlinenb">' . "\n");
	}

	function table_r_end()
	{
		echo('</table>' . $this->tbl_f_l . $this->tbl_f_c . $this->tbl_f_r . "\n");
	}

	function common_form($hidden, $submit)
	{
		echo('<tr><td class="cat" align="center" colspan="2" style="border-width: 0px;">' . $hidden . '<input class="mainoption" type="submit" value="' . $submit . '" /></td></tr>' . "\n");
	}

	function stats_box($current_ip_version, $current_phpbb_version)
	{
		global $lang;

		$current_ip_version_full = (empty($current_ip_version) ? $lang['NotInstalled'] : $current_ip_version);

		if ($current_phpbb_version === false)
		{
			$this->table_begin($lang['Information'], 'row-post');
			echo('<div class="post-text"><br /><br /><br />' . $lang['phpBB_NotDetected'] . '<br /><br /><br /></div>' . "\n");
			$this->table_end();
			exit;
		}

		switch ($current_phpbb_version)
		{
			case '':
				$current_phpbb_version_full = '&lt; RC-3';
				break;
			case 'RC-3':
				$current_phpbb_version_full = 'RC-3';
				break;
			case 'RC-4':
				$current_phpbb_version_full = '&lt; RC-4';
				break;
			default:
				$current_phpbb_version_full = '2' . $current_phpbb_version;
				break;
		}

		echo('<br class="clear" />' . "\n");
		$this->table_begin($lang['Information'], $td_class = 'row-post', 0, 'info');
		echo('<div class="post-text">' . "\n");
		$this->info_box($current_ip_version_full, $current_phpbb_version_full);
		echo('</div>' . "\n");
		$this->table_end('info');
	}

	function info_box($ip_version_full, $phpbb_version_full)
	{
		global $lang, $ip_version, $phpbb_version;

		$phpbb_color = ($phpbb_version_full == ('2' . $phpbb_version)) ? $this->color_ok : $this->color_error;
		$ip_color = ($ip_version_full == $ip_version) ? $this->color_ok : $this->color_error;

		$file_creation_test = ip_functions::file_creation(IP_ROOT_PATH);
		$file_creation_text = ($file_creation_test ? $lang['FileCreation_OK'] : $lang['FileCreation_ERROR']);
		$file_creation_color = ($file_creation_test ? $this->color_ok : $this->color_error);

		echo('<h2>' . $lang['VersionInformation'] . '</h2><br />' . "\n");
		echo('<div class="genmed"><ul type="circle">' . "\n");
		echo('<li><b>' . $lang['Current_phpBB_Version'] . '</b> :: <b style="color:' . $phpbb_color . ';">' . $phpbb_version_full . '</b> &raquo; ' . $lang['Latest_Release'] . ' :: <b style="color: ' . $this->color_ok . ';">2' . $phpbb_version . '</b></li>' . "\n");
		echo('<li><b>' . $lang['Current_IP_Version'] . '</b> :: <b style="color:' . $ip_color . ';">' . $ip_version_full . '</b> &raquo; ' . $lang['Latest_Release'] . ' :: <b style="color: ' . $this->color_ok . ';">' . $ip_version . '</b></li>' . "\n");
		echo('<li><b>' . $lang['dbms'] . '</b> :: <b>' . SQL_LAYER . '</b></li>' . "\n");
		echo('<li><b>' . $lang['FileWriting'] . '</b> :: <b style="color:' . $file_creation_color . ';">' . $file_creation_text . '</b></li>' . "\n");
		if ($ip_version_full != $lang['NotInstalled'])
		{
			echo('<li><b>' . $lang['CHMOD_Files'] . ':</b><br />' . $this->spoiler('chmod', $this->read_chmod(), false) . '</li>' . "\n");
		}
		echo('</ul></div><br /><br />' . "\n");
	}

	function ftp_type()
	{
		global $lang;

		echo('	<table class="forumline">' . "\n");
		echo('	<tr><th colspan="2">' . $lang['ftp_choose'] . '</th></tr>' . "\n");
		echo('	<tr>' . "\n");
		echo('		<td class="row1" align="right" width="50%"><span class="gen">' . $lang['Attempt_ftp'] . '</span></td>' . "\n");
		echo('		<td class="row2"><input type="radio" name="send_file" value="2" /></td>' . "\n");
		echo('	</tr>' . "\n");
		echo('	<tr>' . "\n");
		echo('		<td class="row1" align="right" width="50%"><span class="gen">' . $lang['Send_file'] . '</span></td>' . "\n");
		echo('		<td class="row2"><input type="radio" name="send_file" value="1" /></td>' . "\n");
		echo('	</tr>' . "\n");
	}

	function error($error_title, $error)
	{
		echo('<tr><th>' . $error_title . '</th></tr>' . "\n");
		echo('<tr><td class="row1" align="center"><span class="gen">' . $error . '</span></td></tr>' . "\n");
	}

	function ftp($s_hidden_fields)
	{
		global $lang;

		echo('	<table class="forumline">' . "\n");
		echo('	<tr><th colspan="2">' . $lang['ftp_info'] . '</th></tr>' . "\n");
		echo('	<tr>' . "\n");
		echo('		<td class="row1" align="right"><span class="gen">' . $lang['ftp_path'] . '</span></td>' . "\n");
		echo('		<td class="row2"><input class="post" type="text" name="ftp_dir"></td>' . "\n");
		echo('	</tr>' . "\n");
		echo('	<tr>' . "\n");
		echo('		<td class="row1" align="right"><span class="gen">' . $lang['ftp_username'] . '</span></td>' . "\n");
		echo('		<td class="row2"><input type="text" name="ftp_user"></td>' . "\n");
		echo('	</tr>' . "\n");
		echo('	<tr>' . "\n");
		echo('		<td class="row1" align="right"><span class="gen">' . $lang['ftp_password'] . '</span></td>' . "\n");
		echo('		<td class="row2"><input type="password" name="ftp_pass"></td>' . "\n");
		echo('	</tr>' . "\n");
		$this->common_form($s_hidden_fields, $lang['Transfer_config']);
		echo('	</td>' . "\n");
		echo('	</tr>' . "\n");
		echo('	</table>' . "\n");
	}

	function setup_form($error, $lang_select, $dbms_select, $upgrade_option, $dbhost, $dbname, $dbuser, $dbpasswd, $table_prefix, $board_email, $server_name, $server_port, $script_path, $admin_name, $admin_pass1, $admin_pass2, $language, $hidden_fields)
	{
		global $lang;

		$rowspan_admin = 8;
		$rowspan_admin = empty($upgrade_option) ? $rowspan_admin : $rowspan_admin++;

		echo('	<table class="forumline">' . "\n");
		echo('	<tr><td class="row-header" colspan="3"><span>' . $lang['Initial_config'] . '</span></td></tr>' . "\n");

		echo('	<tr><th colspan="3">' . $lang['Admin_config'] . '</th></tr>' . "\n");
		if ($error)
		{
			echo('	<tr><td class="row1" colspan="3" align="center"><span class="gen" style="color: ' . $this->color_error . '">' . $error . '</span></td></tr>' . "\n");
		}
		echo('	<tr><td class="row1 row-center" rowspan="' . $rowspan_admin . '" width="90"><img src="style/server_install.png" alt="' . $lang['Initial_config'] . '" title="' . $lang['Initial_config'] . '" /></td></tr>' . "\n");
		echo('	<!--' . "\n");
		echo('	<tr>' . "\n");
		echo('		<td class="row1" align="right"><span class="gen">' . $lang['Default_lang'] . ': </span></td>' . "\n");
		echo('		<td class="row2">' . $lang_select . '</td>' . "\n");
		echo('	</tr>' . "\n");
		echo('	-->' . "\n");
		if (!empty($upgrade_option))
		{
			echo('	<tr>' . "\n");
			echo('		<td class="row1" align="right"><span class="gen">' . $lang['Install_Method'] . ':</span></td>' . "\n");
			echo('		<td class="row2">' . $upgrade_option . '</td>' . "\n");
			echo('	</tr>' . "\n");
		}
		echo('	<tr>' . "\n");
		echo('		<td class="row1" align="right"><span class="gen">' . $lang['Admin_Username'] . ': </span></td>' . "\n");
		echo('		<td class="row2"><input type="text" class="post" name="admin_name" value="' . (($admin_name != '') ? $admin_name : '') . '" /></td>' . "\n");
		echo('	</tr>' . "\n");
		echo('	<tr>' . "\n");
		echo('		<td class="row1" align="right"><span class="gen">' . $lang['Admin_Password'] . ': </span></td>' . "\n");
		echo('		<td class="row2"><input type="password" class="post" name="admin_pass1" value="' . (($admin_pass1 != '') ? $admin_pass1 : '') . '" /></td>' . "\n");
		echo('	</tr>' . "\n");
		echo('	<tr>' . "\n");
		echo('		<td class="row1" align="right"><span class="gen">' . $lang['Admin_Password_confirm'] . ': </span></td>' . "\n");
		echo('		<td class="row2"><input type="password" class="post" name="admin_pass2" value="' . (($admin_pass2 != '') ? $admin_pass2 : '') . '" /></td>' . "\n");
		echo('	</tr>' . "\n");
		echo('	<tr>' . "\n");
		echo('		<td class="row1" align="right"><span class="gen">' . $lang['Admin_email'] . ': </span></td>' . "\n");
		echo('		<td class="row2"><input type="text" class="post" name="board_email" value="' . (($board_email != '') ? $board_email : '') . '" /></td>' . "\n");
		echo('	</tr>' . "\n");
		echo('	<tr>' . "\n");
		echo('		<td class="row1" align="right"><span class="gen">' . $lang['Server_name'] . ': </span></td>' . "\n");
		echo('		<td class="row2"><input type="text" class="post" name="server_name" value="' . $server_name . '" /></td>' . "\n");
		echo('	</tr>' . "\n");
		echo('	<tr>' . "\n");
		echo('		<td class="row1" align="right"><span class="gen">' . $lang['Server_port'] . ': </span></td>' . "\n");
		echo('		<td class="row2"><input type="text" class="post" name="server_port" value="' . $server_port . '" /></td>' . "\n");
		echo('	</tr>' . "\n");
		echo('	<tr>' . "\n");
		echo('		<td class="row1" align="right"><span class="gen">' . $lang['Script_path'] . ': </span></td>' . "\n");
		echo('		<td class="row2"><input type="text" class="post" name="script_path" value="' . $script_path . '" /></td>' . "\n");
		echo('	</tr>' . "\n");

		echo('	<tr><th colspan="3">' . $lang['DB_config'] . '</th></tr>' . "\n");
		echo('	<tr><td class="row1 row-center" rowspan="7" width="90"><img src="style/db_status.png" alt="' . $lang['DB_config'] . '" title="' . $lang['DB_config'] . '" /></td></tr>' . "\n");
		echo('	<tr>' . "\n");
		echo('		<td class="row1" align="right"><span class="gen">' . $lang['dbms'] . ': </span></td>' . "\n");
		echo('		<td class="row2">' . $dbms_select . '</td>' . "\n");
		echo('	</tr>' . "\n");
		echo('	<tr>' . "\n");
		echo('		<td class="row1" align="right"><span class="gen">' . $lang['DB_Host'] . ': </span></td>' . "\n");
		echo('		<td class="row2"><input type="text" class="post" name="dbhost" value="' . (($dbhost != '') ? $dbhost : '') . '" /></td>' . "\n");
		echo('	</tr>' . "\n");
		echo('	<tr>' . "\n");
		echo('		<td class="row1" align="right"><span class="gen">' . $lang['DB_Name'] . ': </span></td>' . "\n");
		echo('		<td class="row2"><input type="text" class="post" name="dbname" value="' . (($dbname != '') ? $dbname : '') . '" /></td>' . "\n");
		echo('	</tr>' . "\n");
		echo('	<tr>' . "\n");
		echo('		<td class="row1" align="right"><span class="gen">' . $lang['DB_Username'] . ': </span></td>' . "\n");
		echo('		<td class="row2"><input type="text" class="post" name="dbuser" value="' . (($dbuser != '') ? $dbuser : '') . '" /></td>' . "\n");
		echo('	</tr>' . "\n");
		echo('	<tr>' . "\n");
		echo('		<td class="row1" align="right"><span class="gen">' . $lang['DB_Password'] . ': </span></td>' . "\n");
		echo('		<td class="row2"><input type="password" class="post" name="dbpasswd" value="' . (($dbpasswd != '') ? $dbpasswd : '') . '" /></td>' . "\n");
		echo('	</tr>' . "\n");
		echo('	<tr>' . "\n");
		echo('		<td class="row1" align="right"><span class="gen">' . $lang['Table_Prefix'] . ': </span></td>' . "\n");
		echo('		<td class="row2"><input type="text" class="post" name="prefix" value="' . ((!empty($table_prefix)) ? $table_prefix : 'ip_') . '" /></td>' . "\n");
		echo('	</tr>' . "\n");

		echo('	<tr>' . "\n");
		echo('		<td class="cat" colspan="3" align="center" style="border-width: 0px;">' . "\n");
		echo('			' . $hidden_fields);
		echo('			<input type="hidden" name="install_step" value="2" />' . "\n");
		echo('			<input type="hidden" name="cur_lang" value="' . $language . '" />' . "\n");
		echo('			<input class="mainoption" type="submit" value="' . $lang['Continue_Install'] . '" />' . "\n");
		echo('		</td>' . "\n");
		echo('	</tr>' . "\n");

		echo('	</table>' . "\n");
	}

	function finish_install($hidden_fields)
	{
		global $db, $ip_sql, $lang;

		echo('	<table class="forumline">' . "\n");
		echo('	<tr><td class="row-header" colspan="2"><span>' . $lang['Finish_Install'] . '</span></td></tr>' . "\n");
		echo('' . "\n");
		echo('	<tr><th colspan="2">' . $lang['Finish_Install'] . '</th></tr>' . "\n");
		echo('	<tr><td class="row1 row-center" rowspan="2" width="90"><img src="style/setup.png" alt="' . $lang['Finish_Install'] . '" title="' . $lang['Finish_Install'] . '" /></td></tr>' . "\n");
		echo('	<tr><td class="row1"><span class="gen">' . $lang['Inst_Step_2'] . '</span></td></tr>' . "\n");
		echo('	<tr><td class="cat" colspan="2" align="center" style="border-width: 0px;"><?php $hidden_fields; ?><input class="mainoption" type="submit" value="' . $lang['Finish_Install'] . '" /></td></tr>' . "\n");
		echo('' . "\n");
		echo('	</table>' . "\n");

		$this->fix_last_posters();
		$ip_sql->convert_utf8(false);
	}

	function read_chmod()
	{
		// chmod files defined in schemas/versions.php
		global $chmod_777, $chmod_666;
		global $lang, $language;

		$img_ok = str_replace('alt="" title=""', 'alt="' . $lang['CHMOD_OK'] . '" title="' . $lang['CHMOD_File_Exists'] . '"', $this->img_ok);
		$img_error = str_replace('alt="" title=""', 'alt="' . $lang['CHMOD_Error'] . '" title="' . $lang['CHMOD_Error'] . '"', $this->img_error);

		$report_string_append = '';

		$chmod_items_array = array($chmod_777, $chmod_666);
		$chmod_values_array = array(0777, 0666);
		$chmod_langs_array = array($lang['CHMOD_777'], $lang['CHMOD_666']);

		$table_output = '';

		for ($i = 0; $i < sizeof($chmod_items_array); $i++)
		{
			if (!empty($chmod_items_array[$i]))
			{
				$table_output .= '<b>' . $chmod_langs_array[$i] . '</b><br />' . '<ul>';
				for ($j = 0; $j < sizeof($chmod_items_array[$i]); $j++ )
				{
					$report_string = '';
					$errored = false;
					if (!file_exists($chmod_items_array[$i][$j]))
					{
						$errored = true;
						$report_string = $lang['CHMOD_File_NotExists'];
					}
					else
					{
						if (!is_writable($chmod_items_array[$i][$j]))
						{
							$errored = true;
							$report_string = $lang['CHMOD_File_Exists_Read_Only'];
						}
						else
						{
							$report_string = $lang['CHMOD_File_Exists'];
						}
					}

					if ($errored)
					{
						$report_string_append = '&nbsp;<span class="genmed" style="color: ' . $this->color_error . ';"><b>' . $report_string . '</b></span>';
						$table_output .= '<li><span class="gensmall" style="color: ' . $this->color_error . ';"><b>' . $chmod_items_array[$i][$j] . '</b></span>&nbsp;' . $img_error . '</li>' . "\n";
					}
					else
					{
						$report_string_append = '&nbsp;<span class="genmed" style="color: ' . $this->color_ok . ';">' . $report_string . '</span>';
						$table_output .= '<li><span class="gensmall" style="color: ' . $this->color_ok . ';"><b>' . $chmod_items_array[$i][$j] . '</b></span>&nbsp;' . $img_ok . '</li>' . "\n";
					}
				}
				$table_output .= '</ul>' . '<br />';
			}
		}

		return $table_output;
	}

	function apply_chmod($install_mode = true)
	{
		// chmod files defined in schemas/versions.php
		global $chmod_777, $chmod_666;
		global $lang, $language;

		$img_ok = str_replace('alt="" title=""', 'alt="' . $lang['CHMOD_OK'] . '" title="' . $lang['CHMOD_OK'] . '"', $this->img_ok);
		$img_error = str_replace('alt="" title=""', 'alt="' . $lang['CHMOD_Error'] . '" title="' . $lang['CHMOD_Error'] . '"', $this->img_error);

		$chmod_errors = false;
		$file_exists_errors = false;
		$read_only_errors = false;
		$report_string_append = '';

		$chmod_items_array = array($chmod_777, $chmod_666);
		$chmod_values_array = array(0777, 0666);
		$chmod_langs_array = array($lang['CHMOD_777'], $lang['CHMOD_666']);
		$chmod_images_array = array('./style/folder_blue.png', './style/folder_red.png');

		$table_output = '';
		if ($install_mode)
		{
			$this->output_lang_select(THIS_FILE);
			$table_output .= '<form action="install.' . PHP_EXT . '" name="lang" method="post">' . "\n";
		}
		$table_output .= '<table class="forumline">' . "\n";
		$table_output .= '<tr><td class="row-header" colspan="3"><span>' . $lang['CHMOD_Files'] . '</span></td></tr>' . "\n";

		for ($i = 0; $i < sizeof($chmod_items_array); $i++)
		{
			if (!empty($chmod_items_array[$i]))
			{
				$table_output .= '<tr><th colspan="3"><span class="gen"><b>' . $chmod_langs_array[$i] . '</b></span></th></tr>' . "\n";
				$table_output .= '<tr><td class="row1 row-center" rowspan="' . (sizeof($chmod_items_array[$i]) + 1) . '" width="90"><img src="' . $chmod_images_array[$i] . '" alt="' . $chmod_langs_array[$i] . '" title="' . $chmod_langs_array[$i] . '" /></td></tr>' . "\n";
				for ($j = 0; $j < sizeof($chmod_items_array[$i]); $j++ )
				{
					$report_string = '';
					$errored = false;
					if (!file_exists($chmod_items_array[$i][$j]))
					{
						$errored = true;
						$report_string = $lang['CHMOD_File_NotExists'];
					}
					else
					{
						@chmod($chmod_items_array[$i][$j], $chmod_values_array[$i]);
						if (!is_writable($chmod_items_array[$i][$j]))
						{
							$errored = true;
							$report_string = $lang['CHMOD_File_Exists_Read_Only'];
						}
						else
						{
							$report_string = $lang['CHMOD_File_Exists'];
						}
					}

					if ($errored)
					{
						$report_string_append = '&nbsp;<span class="genmed" style="color: ' . $this->color_error . ';"><b>' . $report_string . '</b></span>';
						$table_output .= '<tr><td class="row1" width="40%"><span class="gen" style="color: ' . $this->color_error . ';"><b>' . $chmod_items_array[$i][$j] . '</b></span></td><td class="row2">' . $img_error . $report_string_append . '</td></tr>' . "\n";
						$chmod_errors = true;
					}
					else
					{
						$report_string_append = '&nbsp;<span class="genmed" style="color: ' . $this->color_ok . ';">' . $report_string . '</span>';
						$table_output .= '<tr><td class="row1" width="40%"><span class="gen" style="color: ' . $this->color_ok . ';"><b>' . $chmod_items_array[$i][$j] . '</b></span></td><td class="row2">' . $img_ok . $report_string_append . '</td></tr>' . "\n";
					}
				}
			}
		}

		if ($install_mode)
		{
			$table_output .= '<tr><th colspan="3"><span class="gen"><b>&nbsp;</b></span></th></tr>' . "\n";
			$s_hidden_fields = '';
			$s_hidden_fields .= '<input type="hidden" name="install_step" value="1" />' . "\n";
			$s_hidden_fields .= '<input type="hidden" name="lang" value="' . $language . '" />' . "\n";
			$table_output .= '<tr><td class="row2 row-center" colspan="3"><span style="color:' . ($chmod_errors ? $this->color_error : $this->color_ok) . ';"><b>' . ($chmod_errors ? ($lang['CHMOD_Files_Explain_Error'] . ' ' . $lang['Confirm_Install_anyway']) : ($lang['CHMOD_Files_Explain_Ok'] . ' ' . $lang['Can_Install'])) . '</b></span></td></tr>' . "\n";
			$table_output .= '<tr><td class="cat" colspan="3" align="center" style="border-width: 0px;">' . $s_hidden_fields . '<input class="mainoption" type="submit" value="' . ($chmod_errors ? $lang['Start_Install_Anyway'] : $lang['Start_Install']) . '" /></td></tr>' . "\n";

			$table_output .= '</table>' . "\n";
			$table_output .= '</form>' . "\n";
		}
		else
		{
			$table_output .= '<tr><td class="cat" colspan="3"><span class="gen"><b>&nbsp;</b></span></td></tr>' . "\n";
			$table_output .= '</table>' . "\n";
		}

		echo($table_output);
		return $chmod_errors;
	}

	function clean_old_files($action)
	{
		global $lang, $language;

		$lang_append = '&amp;lang=' . $language;

		if ($action == 'clean')
		{
			require('schemas/old_files.' . PHP_EXT);
			$tot_items = sizeof($files_array);
			$killed_counter = 0;
			$not_killed_counter = 0;
			$not_found_counter = 0;
			$killed_output = '';
			$not_killed_output = '';
			$not_found_output = '';
			$table_output = '';
			$table_output .= '<div class="post-text">' . "\n";
			for ($i = 0; $i < $tot_items; $i++)
			{
				if (file_exists(IP_ROOT_PATH . $files_array[$i]))
				{
					@chmod(IP_ROOT_PATH . $files_array[$i], 0777);
					$killed = @unlink(IP_ROOT_PATH . $files_array[$i]);
					if ($killed)
					{
						$killed_counter++;
						$killed_output .= '<li>' . $files_array[$i] . '<br /> +++ <span style="color: ' . $this->color_ok . ';"><b>' . $lang['FileDeletion_OK'] . '</b></span></li>' . "\n";
					}
					else
					{
						$not_killed_counter++;
						$not_killed_output .= '<li>' . $files_array[$i] . '<br /> +++ <span style="color: ' . $this->color_error . ';"><b>' . $lang['FileDeletion_ERROR'] . '</b></span></li>' . "\n";
					}
				}
				else
				{
					$not_found_counter++;
					//$not_found_output .= '<li>' . $files_array[$i] . '<br /> +++ <span style="color:#224488;"><b>' . $lang['FileDeletion_NF'] . '</b></span></li><br />';
				}
			}
			$killed_output = ($killed_output == '') ? $lang['FilesDeletion_None'] : $killed_output;
			$not_killed_output = ($not_killed_output == '') ? $lang['FilesDeletion_None'] : $not_killed_output;
			$table_output .= '<b>' . $lang['FilesDeletion_OK'] . '</b><br /><ul>' . $killed_output . '</ul><br /><br />' . "\n";
			$table_output .= '<b>' . $lang['FilesDeletion_NO'] . '</b><br /><ul>' . $not_killed_output . '</ul><br /><br />' . "\n";
			$table_output .= '<br /><br />' . "\n";

			$table_output .= '<br /><br />' . "\n";
			$table_output .= '<span style="color: ' . $this->color_ok . ';">' . $killed_counter . ' ' . $lang['FilesDeletion_OK'] . '!</span><br />' . "\n";
			$table_output .= '<span style="color: ' . $this->color_error . ';">' . $not_killed_counter . ' ' . $lang['FilesDeletion_ERROR'] . '!</span><br />' . "\n";
			$table_output .= '<span style="color: ' . $this->color_blue . ';">' . $not_found_counter . ' ' . $lang['FilesDeletion_NF'] . '!</span><br />' . "\n";
			$table_output .= '</div>' . "\n";

		}
		else
		{
			$table_output = '';
			$table_output .= '<div class="post-text">' . "\n";
			$table_output .= '<br /><span class="text_red"><b><span class="gen">' . $lang['ClickToClean'] . '</span></b></span><br /><br /><br />' . "\n";
			$table_output .= '<ul type="circle">' . "\n";
			$table_output .= '<li><a href="' . ip_functions::append_sid(THIS_FILE . '?mode=clean_old_files&amp;action=clean' . $lang_append) . '"><b>' . $lang['Clean_OldFiles_Explain'] . '</b></a></li>' . "\n";
			$table_output .= '</ul>' . "\n";
			$table_output .= '</div>' . "\n";
		}

		return $table_output;
	}

	function fix_birthdays($action)
	{
		global $db, $config, $lang, $language;
		global $wip;
		global $birthdays_number, $birthday_start, $total_birthdays, $total_birthdays_modified;

		$lang_append = '&amp;lang=' . $language;

		$table_output = '';

		switch ($action)
		{
			case 'fix':
				if ($total_birthdays == 0)
				{
					$sql = "SELECT * FROM " . USERS_TABLE . "
									WHERE user_birthday <> '999999'";
					if (!($result = $db->sql_query($sql)))
					{
						die('Could not obtain total users numbers');
					}
					$row = $db->sql_fetchrow($result);
					$total_birthdays = $db->sql_numrows($result);
					$total_birthdays_modified = 0;
				}

				if ($total_birthdays_modified >= $total_birthdays)
				{
					$wip = false;
					$table_output .= '<br /><br />' . "\n";
					$table_output .= '<div class="post-text">' . "\n";
					$table_output .= '<ul type="circle" style="align: left;">' . "\n";
					$table_output .= '<li><span style="color: ' . $this->color_green . ';"><b>' . $lang['FixingBirthdaysComplete'] . '</b></span></li>' . "\n";
					$table_output .= '<li><span style="color: ' . $this->color_blue . ';"><b>' . $total_birthdays_modified . $lang['FixingBirthdaysModified'] . '</b></span></li>' . "\n";
					$table_output .= '</ul>' . "\n";
					$table_output .= '</div>' . "\n";
					$table_output .= '<br class="clear" />' . "\n";
					$table_output .= '<br /><br />' . "\n";
					return $table_output;
				}

				$sql = "SELECT user_id, user_birthday
					FROM " . USERS_TABLE . "
					WHERE user_birthday <> '999999'
					ORDER BY user_id ASC
					LIMIT " . $birthday_start . ", " . $birthdays_number;
				$result = $db->sql_query($sql);

				while ($row = $db->sql_fetchrow($result))
				{
					$birthday_year = gmdate('Y', ($row['user_birthday'] * 86400) + 1);
					$birthday_month = gmdate('n', ($row['user_birthday'] * 86400) + 1);
					$birthday_day = gmdate('j', ($row['user_birthday'] * 86400) + 1);
					$sql_update = "UPDATE " . USERS_TABLE . " SET user_birthday_y = '" . $birthday_year . "', user_birthday_m = '" . $birthday_month . "', user_birthday_d = '" . $birthday_day . "' WHERE user_id = '" . $row['user_id'] . "'";

					if (!$result_new = $db->sql_query($sql_update))
					{
						die('Error in editing birthdays...');
					}

					$total_birthdays_modified++;
				}

				if ($total_birthdays_modified > $total_birthdays)
				{
					$total_birthdays_modified = $total_birthdays;
				}

				$table_output .= '<br /><br />' . "\n";
				$table_output .= '<div class="post-text">' . "\n";
				$table_output .= '<ul type="circle" style="align: left;">' . "\n";
				$table_output .= '<li><span style="color: ' . $this->color_green . ';"><b>' . sprintf($lang['FixingBirthdaysFrom'], ($birthday_start + 1), ((($birthday_start + $birthdays_number) > $total_birthdays) ? $total_birthdays : ($birthday_start + $birthdays_number))) . '</b></span></li>' . "\n";
				$table_output .= '<li><span style="color: ' . $this->color_purple . ';"><b>' . sprintf($lang['FixingBirthdaysTotal'], $total_birthdays_modified, $total_birthdays) . '</b></span></li>' . "\n";
				$table_output .= '</ul>' . "\n";
				$table_output .= '</div>' . "\n";
				$table_output .= '<br class="clear" />' . "\n";
				$table_output .= '<br /><br />' . "\n";

				// Increase $post_start to process the other posts
				$birthday_start = ($birthday_start + $birthdays_number);

				break;

			default:
				$table_output .= '<form action="' . ip_functions::append_sid(THIS_FILE . '?mode=fix_birthdays&amp;action=fix&amp;wip=true' . $lang_append) . '" method="post" enctype="multipart/form-data">' . "\n";
				$table_output .= '<table class="forumline">' . "\n";
				$table_output .= '<tr><td class="row-header" colspan="2"><span>' . $lang['FixBirthdays'] . '</span></td></tr>' . "\n";
				$table_output .= '<tr>' . "\n";
				$table_output .= '	<td class="row1"><span class="genmed">' . $lang['BirthdaysPerStep'] . '&nbsp;</span></td>' . "\n";
				$table_output .= '	<td class="row1"><span class="genmed"><input type="text" class="post" name="birthdays_number" value="100" size="10" /></span></td>' . "\n";
				$table_output .= '</tr>' . "\n";
				$table_output .= '<tr>' . "\n";
				$table_output .= '	<td class="cat" colspan="2" style="border-right-width: 0px; border-bottom-width: 0px;"><input type="submit" class="mainoption" name="submit" value="' . $lang['Start'] . '" /></td>' . "\n";
				$table_output .= '</tr>' . "\n";
				$table_output .= '</table>' . "\n";
				$table_output .= '</form>' . "\n";
		}

		return $table_output;
	}

	function fix_constants($action)
	{
		global $db, $lang, $language;

		$lang_append = '&amp;lang=' . $language;

		$img_ok = str_replace('alt="" title=""', 'alt="' . $lang['Fixed'] . '" title="' . $lang['Fixed'] . '"', $this->img_ok);
		$img_error = str_replace('alt="" title=""', 'alt="' . $lang['NotFixed'] . '" title="' . $lang['NotFixed'] . '"', $this->img_error);

		$content_output = '';
		$table_output = '';

		switch ($action)
		{
			case 'fix_all':
				$filename_tmp = IP_ROOT_PATH . 'config.' . PHP_EXT;
				$content = ip_functions::file_read($filename_tmp);
				$content = str_replace('PHPBB_INSTALLED', 'IP_INSTALLED', $content);
				$result = ip_functions::file_write($filename_tmp, $content);
				$color = ($result !== false) ? $this->color_ok : $this->color_error;
				$img = ($result !== false) ? $img_ok : $img_error;
				$content_output .= '<li><span class="genmed"><b style="color:' . $color . '">' . $filename_tmp . '</b>&nbsp;' . $img . '</span></li>';

			case 'fix_cms':
				if (defined('CMS_LAYOUT_TABLE'))
				{
					$sql = "SELECT filename FROM " . CMS_LAYOUT_TABLE . "
									WHERE filename <> ''
									ORDER BY filename ASC";
					if (!($result = $db->sql_query($sql)))
					{
						die('Could not query CMS Pages');
					}
					$files_array = array();
					while ($row = $db->sql_fetchrow($result))
					{
						$filename_path = IP_ROOT_PATH . $row['filename'];
						if (is_writable($filename_path))
						{
							$files_array[] = $filename_path;
						}
					}
					for ($i = 0; $i < sizeof($files_array); $i++)
					{
						$filename_tmp = $files_array[$i];
						$content = ip_functions::cms_page_content();
						$result = ip_functions::file_write($filename_tmp, $content);
						$color = ($result !== false) ? $this->color_ok : $this->color_error;
						$img = ($result !== false) ? $img_ok : $img_error;
						$content_output .= '<li><span class="genmed"><b style="color:' . $color . '">' . $filename_tmp . '</b>&nbsp;' . $img . '</span></li>' . "\n";
					}
				}
				$content_output = ($content_output == '') ? ('<li><span class="genmed"><b style="color: ' . $this->color_ok . '">' . $lang['None'] . '</b></span></li>' . "\n") : $content_output;

				$table_output .= '<div class="post-text">' . "\n";
				$table_output .= '<b>' . $lang['FilesProcessed'] . '</b><br /><ul>' . $content_output . '</ul><br /><br />' . "\n";
				$table_output .= '</div>' . "\n";
				break;

			default:
				if (is_writable(IP_ROOT_PATH . 'config.' . PHP_EXT))
				{
					$table_output .= '<div class="post-text">' . "\n";
					$table_output .= '<br /><span class="text_red"><b><span class="gen">' . $lang['ClickToFix'] . '</span></b></span><br /><br />' . "\n";
					$table_output .= '<ul type="circle">' . "\n";
					$table_output .= '<li><a href="' . ip_functions::append_sid(THIS_FILE . '?mode=fix_constants&amp;action=fix_all' . $lang_append) . '"><b>' . $lang['FixAllFiles'] . '</b></a></li>' . "\n";
					$table_output .= '<li><a href="' . ip_functions::append_sid(THIS_FILE . '?mode=fix_constants&amp;action=fix_cms' . $lang_append) . '"><b>' . $lang['FixCMSPages'] . '</b></a></li>' . "\n";
					$table_output .= '</ul>' . "\n";
					$table_output .= '</div>' . "\n";
				}
				else
				{
					$table_output .= '<div class="text_cont_center" style="width:400px;"><div class="text_red_cont"><span class="text_red">' . $lang['FileCreation_ERROR_Explain'] . '</span></div></div>' . "\n";
				}
		}

		return $table_output;
	}

	function fix_pics($action)
	{
		global $db, $config, $lang, $language;
		global $wip;
		global $pics_number, $pic_start, $total_pics, $total_pics_modified;

		$lang_append = '&amp;lang=' . $language;

		$table_output = '';
		$pics_moved = '';

		switch ($action)
		{
			case 'fix':
				if ($total_pics == 0)
				{
					$sql = "SELECT * FROM " . ALBUM_TABLE;
					if (!($result = $db->sql_query($sql)))
					{
						die('Could not obtain total pics numbers');
					}
					$row = $db->sql_fetchrow($result);
					$total_pics = $db->sql_numrows($result);
					$total_pics_modified = 0;
				}

				if ($total_pics_modified >= $total_pics)
				{
					$wip = false;
					$table_output .= '<br /><br />' . "\n";
					$table_output .= '<div class="post-text">' . "\n";
					$table_output .= '<ul type="circle" style="align: left;">' . "\n";
					$table_output .= '<li><span style="color: ' . $this->color_green . ';"><b>' . $lang['FixingPicsComplete'] . '</b></span></li>' . "\n";
					$table_output .= '<li><span style="color: ' . $this->color_blue . ';"><b>' . $total_pics_modified . $lang['FixingPicsModified'] . '</b></span></li>' . "\n";
					$table_output .= '</ul>' . "\n";
					$table_output .= '</div>' . "\n";
					$table_output .= '<br class="clear" />' . "\n";
					$table_output .= '<br /><br />' . "\n";
					return $table_output;
				}

				//$pic_base_path = ALBUM_UPLOAD_PATH;
				$pic_base_path = IP_ROOT_PATH . 'files/album/';
				$pic_extra_path = 'users/';
				if (!is_dir($pic_base_path . $pic_extra_path))
				{
					$dir_creation = @mkdir($pic_base_path . $pic_extra_path, 0777);
					@copy($pic_base_path . 'index.html', $pic_base_path . $pic_extra_path . 'index.html');
					@chmod($pic_base_path . $pic_extra_path . 'index.html', 0755);
				}

				$sql = "SELECT *
					FROM " . ALBUM_TABLE . "
					ORDER BY pic_id ASC
					LIMIT " . $pic_start . ", " . $pics_number;
				$result = $db->sql_query($sql);

				while ($row = $db->sql_fetchrow($result))
				{
					$pic_old_file = $pic_base_path . $row['pic_filename'];
					$pic_size = @filesize($pic_old_file);
					$pic_size = (!$pic_size ? 0 : $pic_size);
					$user_subfolder = (intval($row['pic_user_id']) >= 2) ? (intval($row['pic_user_id']) . '/') : '';
					$pic_new_path = $pic_base_path . $pic_extra_path . $user_subfolder;
					if ($user_subfolder != '')
					{
						$pic_new_file = $pic_new_path . $row['pic_filename'];
						$pic_new_filename = $user_subfolder . $row['pic_filename'];
						if (!is_dir($pic_new_path))
						{
							$dir_creation = @mkdir($pic_new_path, 0777);
							@copy($pic_base_path . 'index.html', $pic_new_path . 'index.html');
							@chmod($pic_new_path . 'index.html', 0755);
						}
						@chmod($pic_old_file, 0755);
						$copy_success = @copy($pic_old_file, $pic_new_file);
						if ($copy_success)
						{
							@chmod($pic_new_file, 0755);
							$pics_moved .= '<li><span class="gensmall">' . $pic_old_file . ' => ' . $pic_new_file . '</span></li>';
							//@unlink($pic_old_file);
						}
					}
					else
					{
						$pic_new_file = $pic_base_path . $pic_extra_path . $row['pic_filename'];
						$pic_new_filename = $row['pic_filename'];
						@chmod($pic_old_file, 0755);
						$copy_success = @copy($pic_old_file, $pic_new_file);
						if ($copy_success)
						{
							@chmod($pic_new_file, 0755);
							$pics_moved .= '<li><span class="gensmall">' . $pic_old_file . ' => ' . $pic_new_file . '</span></li>';
							@unlink($pic_old_file);
						}
					}

					$sql_update = "UPDATE " . ALBUM_TABLE . " SET pic_filename = '" . $pic_new_filename . "', pic_size = '" . $pic_size . "' WHERE pic_id = '" . $row['pic_id'] . "'";

					if (!$result_new = $db->sql_query($sql_update))
					{
						die('Error in editing pics...');
					}

					$total_pics_modified++;
				}

				if ($total_pics_modified > $total_pics)
				{
					$total_pics_modified = $total_pics;
				}

				$table_output .= '<br /><br />' . "\n";
				$table_output .= '<div class="post-text">' . "\n";
				$table_output .= '<ul type="circle" style="align: left;">' . "\n";
				$table_output .= '<li><span style="color: ' . $this->color_green . ';"><b>' . sprintf($lang['FixingPicsFrom'], ($pic_start + 1), ((($pic_start + $pics_number) > $total_pics) ? $total_pics : ($pic_start + $pics_number))) . '</b></span></li>' . "\n";
				$table_output .= '<li><span style="color: ' . $this->color_purple . ';"><b>' . sprintf($lang['FixingPicsTotal'], $total_pics_modified, $total_pics) . '</b></span></li>' . "\n";
				$table_output .= '</ul>' . "\n";
				$table_output .= '</div>' . "\n";
				$table_output .= '<br class="clear" />' . "\n";
				$table_output .= '<br /><br />' . "\n";

				$table_output .= '<div class="post-text" style="width: 90%;">' . "\n";
				$table_output .= '<ul type="circle" style="align: left;">' . "\n";
				$table_output .= $pics_moved . "\n";
				$table_output .= '</ul>' . "\n";
				$table_output .= '</div>' . "\n";
				$table_output .= '<br class="clear" />' . "\n";
				$table_output .= '<br /><br />' . "\n";

				// Increase $pic_start to process the other pics
				$pic_start = ($pic_start + $pics_number);

				break;

			default:
				$table_output .= '<form action="' . ip_functions::append_sid(THIS_FILE . '?mode=fix_images_album&amp;action=fix&amp;wip=true' . $lang_append) . '" method="post" enctype="multipart/form-data">' . "\n";
				$table_output .= '<table class="forumline">' . "\n";
				$table_output .= '<tr><td class="row-header" colspan="2"><span>' . $lang['FixPics'] . '</span></td></tr>' . "\n";
				$table_output .= '<tr>' . "\n";
				$table_output .= '	<td class="row1" width="200"><span class="genmed">' . $lang['PicsPerStep'] . '&nbsp;</span></td>' . "\n";
				$table_output .= '	<td class="row1"><span class="genmed"><input type="text" class="post" name="pics_number" value="100" size="10" /></span></td>' . "\n";
				$table_output .= '</tr>' . "\n";
				$table_output .= '<tr>' . "\n";
				$table_output .= '	<td class="row2"><span class="genmed">' . $lang['PicStartFrom'] . '&nbsp;</span></td>' . "\n";
				$table_output .= '	<td class="row2"><span class="genmed"><input type="text" class="post" name="pic_start" value="0" size="10" /></span></td>' . "\n";
				$table_output .= '</tr>' . "\n";
				$table_output .= '<tr>' . "\n";
				$table_output .= '	<td class="cat" colspan="2" style="border-right-width: 0px; border-bottom-width: 0px;"><input type="submit" class="mainoption" name="submit" value="' . $lang['Start'] . '" /></td>' . "\n";
				$table_output .= '</tr>' . "\n";
				$table_output .= '</table>' . "\n";
				$table_output .= '</form>' . "\n";
		}

		return $table_output;
	}

	function fix_content_ip($action_mode, $action)
	{
		global $db, $cache, $config, $user, $lang, $language;
		global $wip, $search_word, $replacement_word;
		global $remove_bbcode_uid, $remove_guess_bbcode_uid, $fix_posted_images;
		global $posts_number, $post_start, $total_posts, $total_posts_modified;

		$lang_append = '&amp;lang=' . $language;

		$table_output = '';

		switch ($action_mode)
		{
			case 'fix_posts':
			case 'fix_posts_ip2':
				$db_table = POSTS_TABLE;
				$db_update_table = POSTS_TABLE;
				$db_update_field = 'post_text';
				$db_update_field_id = 'post_id';
				if ($action_mode == 'fix_posts')
				{
					if (defined('POSTS_TEXT_TABLE'))
					{
						$sql_tmp = "SHOW TABLES LIKE " . POSTS_TEXT_TABLE;
						$result_tmp = $db->sql_query($sql_tmp);
						if ($row = $db->sql_fetchrow($result_tmp))
						{
							$db_update_table = POSTS_TEXT_TABLE;
						}
					}
				}
				$sql_total = "SELECT * FROM " . $db_table;
				$sql_step_by_step = "SELECT *
					FROM " . $db_table . "
					ORDER BY " . $db_update_field_id . " ASC
					LIMIT " . $post_start . ", " . $posts_number;
				$lang_fix_complete = $lang['FixingPostsComplete'];
				$lang_fix_modified = $lang['FixingPostsModified'];
				$lang_fix_from = $lang['FixingPostsFrom'];
				$lang_fix_total = $lang['FixingPostsTotal'];
				$lang_fix_title = (($action_mode == 'fix_posts_ip2') ? $lang['FixPosts_IP2'] : $lang['FixPosts']);
				$lang_fix_posts_per_step = $lang['PostsPerStep'];
				$lang_fix_start_from = $lang['StartFrom'];
			break;

			case 'fix_signatures':
			case 'fix_signatures_ip2':
				$db_table = USERS_TABLE;
				$db_update_table = USERS_TABLE;
				$db_update_field = 'user_sig';
				$db_update_field_id = 'user_id';
				$sql_total = "SELECT * FROM " . $db_table;
				// Removed user_sig_bbcode_uid
				$sql_step_by_step = "SELECT user_id, user_sig
					FROM " . $db_table . "
					ORDER BY user_id ASC
					LIMIT " . $post_start . ", " . $posts_number;
				$lang_fix_complete = $lang['FixingSignaturesComplete'];
				$lang_fix_modified = $lang['FixingSignaturesModified'];
				$lang_fix_from = $lang['FixingSignaturesFrom'];
				$lang_fix_total = $lang['FixingSignaturesTotal'];
				$lang_fix_title = (($action_mode == 'fix_signatures_ip2') ? $lang['FixSignatures_IP2'] : $lang['FixSignatures']);
				$lang_fix_posts_per_step = $lang['SignaturesPerStep'];
				$lang_fix_start_from = $lang['StartFromSignature'];
			break;

			default:
				die('No mode specified');
			break;
		}

		switch ($action)
		{
			case 'fix':
				if ($total_posts == 0)
				{
					$sql = $sql_total;
					if (!($result = $db->sql_query($sql)))
					{
						die('Could not obtain total items numbers');
					}
					$row = $db->sql_fetchrow($result);
					$total_posts = $db->sql_numrows($result);
					$total_posts_modified = 0;
				}

				if ($total_posts_modified >= $total_posts)
				{
					$wip = false;
					$table_output .= '<br /><br />' . "\n";
					$table_output .= '<div class="post-text">' . "\n";
					$table_output .= '<ul type="circle" style="align: left;">' . "\n";
					$table_output .= '<li><span style="color: ' . $this->color_green . ';"><b>' . $lang_fix_complete . '</b></span></li>' . "\n";
					$table_output .= '<li><span style="color: ' . $this->color_blue . ';"><b>' . $total_posts_modified . $lang_fix_modified . '</b></span></li>' . "\n";
					$table_output .= '</ul>' . "\n";
					$table_output .= '</div>' . "\n";
					$table_output .= '<br class="clear" />' . "\n";
					$table_output .= '<br /><br />' . "\n";
					return $table_output;
				}

				// Special functions needed if you wanto to converts smileys to [IMG] BBCode
				/*
				$smileys_list = mg_functions::smileys_list();
				$smileys_replace_sets = mg_functions::smileys_replace_sets($smileys_list);
				*/

				$sql = $sql_step_by_step;
				$result = $db->sql_query($sql);

				while ($row = $db->sql_fetchrow($result))
				{
					$item_id = in_array($action_mode, array('fix_signatures', 'fix_signatures_ip2')) ? $row['user_id'] : $row['post_id'];
					$bbcode_uid = in_array($action_mode, array('fix_signatures', 'fix_signatures_ip2')) ? (!empty($row['user_sig_bbcode_uid']) ? $row['user_sig_bbcode_uid'] : '') :  (!empty($row['bbcode_uid']) ? $row['bbcode_uid'] : '');
					$post_text_f = in_array($action_mode, array('fix_signatures', 'fix_signatures_ip2')) ? $row['user_sig'] : $row['post_text'];

					//$post_text_f = ((defined('STRIP') && STRIP)? stripslashes($post_text_f) : $post_text_f);

					if (!empty($search_word))
					{
						$post_text_f = str_replace($search_word, $replacement_word, $post_text_f);
					}

					// Special function which converts smileys to [IMG] BBCode
					//$post_text_f = mg_functions::smileys_replace($post_text_f, $smileys_replace_sets);
					// Special function which performs custom text replace, you need to customize it...
					//$post_text_f = mg_functions::custom_text_replace($post_text_f);

					switch ($action_mode)
					{
						case 'fix_posts':
						case 'fix_signatures':
							$post_text_f = mg_functions::old_bbcode_replace($post_text_f);
							$post_text_f = ($remove_bbcode_uid && !empty($bbcode_uid)) ? mg_functions::bbcuid_clean($post_text_f, $bbcode_uid) : $post_text_f;
							$post_text_f = ($remove_guess_bbcode_uid) ? mg_functions::bbcuid_clean($post_text_f, false) : $post_text_f;
							$post_text_f = ($fix_posted_images && !empty($config)) ? mg_functions::img_replace($post_text_f) : $post_text_f;
						break;

						case 'fix_posts_ip2':
						case 'fix_signatures_ip2':
							$post_text_f = mg_functions::old_bbcode_replace_ip2($post_text_f);
							$post_text_f = mg_functions::img_replace_ip2($post_text_f);
						break;

						default:
							die('No mode specified');
					}

					//$post_text_f = ((defined('STRIP') && STRIP)? addslashes($post_text_f) : $post_text_f);

					$sql_update = "UPDATE " . $db_update_table . " SET " . $db_update_field . " = '" . $db->sql_escape($post_text_f) . "' WHERE " . $db_update_field_id . " = " . $item_id;
					if (!$result_new = $db->sql_query($sql_update))
					{
						die('Error in editing content...');
					}

					$total_posts_modified++;
				}

				if ($total_posts_modified > $total_posts)
				{
					$total_posts_modified = $total_posts;
				}

				$table_output .= '<br /><br />' . "\n";
				$table_output .= '<div class="post-text">' . "\n";
				$table_output .= '<ul type="circle" style="align: left;">' . "\n";
				$table_output .= '<li><span style="color: ' . $this->color_green . ';"><b>' . sprintf($lang_fix_from, ($post_start + 1), ((($post_start + $posts_number) > $total_posts) ? $total_posts : ($post_start + $posts_number))) . '</b></span></li>' . "\n";
				$table_output .= '<li><span style="color: ' . $this->color_purple . ';"><b>' . sprintf($lang['FixingPostsTotal'], $total_posts_modified, $total_posts) . '</b></span></li>' . "\n";
				$table_output .= '</ul>' . "\n";
				$table_output .= '</div>' . "\n";
				$table_output .= '<br class="clear" />' . "\n";
				$table_output .= '<br /><br />' . "\n";

				// Increase $post_start to process the other posts
				$post_start = ($post_start + $posts_number);

			break;

			default:
				$table_output .= '<form action="' . ip_functions::append_sid(THIS_FILE . '?mode=' . $action_mode . '&amp;action=fix&amp;wip=true' . $lang_append) . '" method="post" enctype="multipart/form-data">' . "\n";
				$table_output .= '<table class="forumline">' . "\n";
				$table_output .= '<tr><td class="row-header" colspan="2"><span>' . $lang_fix_title . '</span></td></tr>' . "\n";
				$table_output .= '<tr>' . "\n";
				$table_output .= '	<td class="row1" width="200"><span class="genmed">' . $lang['SearchWhat'] . '&nbsp;</span></td>' . "\n";
				$table_output .= '	<td class="row1"><span class="genmed"><input type="text" class="post" name="search_word" value="" size="80" /></span></td>' . "\n";
				$table_output .= '</tr>' . "\n";
				$table_output .= '<tr>' . "\n";
				$table_output .= '	<td class="row2"><span class="genmed">' . $lang['ReplaceWith'] . '&nbsp;</span></td>' . "\n";
				$table_output .= '	<td class="row2"><span class="genmed"><input type="text" class="post" name="replacement_word" value="" size="80" /></span></td>' . "\n";
				$table_output .= '</tr>' . "\n";
				$table_output .= '<tr>' . "\n";
				$table_output .= '	<td class="row1"><span class="genmed">' . $lang_fix_posts_per_step . '&nbsp;</span></td>' . "\n";
				$table_output .= '	<td class="row1"><span class="genmed"><input type="text" class="post" name="posts_number" value="100" size="10" /></span></td>' . "\n";
				$table_output .= '</tr>' . "\n";
				$table_output .= '<tr>' . "\n";
				$table_output .= '	<td class="row2"><span class="genmed">' . $lang_fix_start_from . '&nbsp;</span></td>' . "\n";
				$table_output .= '	<td class="row2"><span class="genmed"><input type="text" class="post" name="post_start" value="0" size="10" /></span></td>' . "\n";
				$table_output .= '</tr>' . "\n";
				if (in_array($action_mode, array('fix_posts', 'fix_signatures')))
				{
					$table_output .= '<tr>' . "\n";
					$table_output .= '	<td class="row1" colspan="2">' . "\n";
					$table_output .= '	<div style="text-align: left; padding: 5px;">' . "\n";
					$table_output .= '	<label><input type="checkbox" name="remove_bbcode_uid" />&nbsp;' . $lang['RemoveBBCodeUID'] . '</label><br />' . "\n";
					$table_output .= '	<label><input type="checkbox" name="remove_guess_bbcode_uid" />&nbsp;' . $lang['RemoveBBCodeUID_Guess'] . '</label><br />' . "\n";
					$table_output .= '	<label><input type="checkbox" name="fix_posted_images" />&nbsp;' . $lang['FixPostedImagesPaths'] . '</label><br />' . "\n";
					$table_output .= '	</div>' . "\n";
					$table_output .= '	</td>' . "\n";
					$table_output .= '</tr>' . "\n";
				}
				$table_output .= '<tr>' . "\n";
				$table_output .= '	<td class="cat" colspan="2" style="border-right-width: 0px; border-bottom-width: 0px;"><input type="submit" class="mainoption" name="submit" value="' . $lang['Start'] . '" /></td>' . "\n";
				$table_output .= '</tr>' . "\n";
				$table_output .= '</table>' . "\n";
				$table_output .= '</form>' . "\n";
		}

		return $table_output;
	}

	function fix_forums($action)
	{
		global $db, $config, $lang, $language;

		$lang_append = '&amp;lang=' . $language;

		$table_output = '';

		switch ($action)
		{
			case 'fix':
				$this->convert_forums();
				$table_output .= '<br /><br />' . "\n";
				$table_output .= '<div class="post-text">' . "\n";
				$table_output .= '<ul type="circle" style="align: left;">' . "\n";
				$table_output .= '<li><span style="color: ' . $this->color_green . ';"><b>' . $lang['FixingForumsComplete'] . '</b></span></li>' . "\n";
				$table_output .= '</ul>' . "\n";
				$table_output .= '</div>' . "\n";
				$table_output .= '<br class="clear" />' . "\n";
				$table_output .= '<br /><br />' . "\n";
				break;

			default:
				$table_output .= '<form action="' . ip_functions::append_sid(THIS_FILE . '?mode=fix_forums&amp;action=fix' . $lang_append) . '" method="post" enctype="multipart/form-data">' . "\n";
				$table_output .= '<table class="forumline">' . "\n";
				$table_output .= '<tr><td class="row-header" colspan="2"><span>' . $lang['FixForums'] . '</span></td></tr>' . "\n";
				$table_output .= '<tr><td class="row1" colspan="2"><div class="post-text">' . $lang['FixForumsExplain'] . '</div></td></tr>' . "\n";
				$table_output .= '<tr>' . "\n";
				$table_output .= '	<td class="cat" colspan="2" style="border-right-width: 0px; border-bottom-width: 0px;"><input type="submit" class="mainoption" name="submit" value="' . $lang['Start'] . '" /></td>' . "\n";
				$table_output .= '</tr>' . "\n";
				$table_output .= '</table>' . "\n";
				$table_output .= '</form>' . "\n";
		}

		return $table_output;
	}

	/**
	* Insert/Convert forums
	*/
	function convert_forums()
	{
		global $db, $table_prefix;

		if (!defined('CATEGORIES_TABLE'))
		{
			define('CATEGORIES_TABLE', $table_prefix . 'categories');
		}

		if (!defined('FORUM_CAT'))
		{
			define('FORUM_CAT', 0);
		}

		/*
		$sql = 'SHOW TABLES LIKE ' . CATEGORIES_TABLE;
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		if (empty($row))
		{
			return false;
		}
		*/

		// Determine the highest id used within the old forums table (we add the categories after the forum ids)
		$sql = 'SELECT MAX(forum_id) AS max_forum_id
			FROM ' . FORUMS_TABLE;
		$result = $db->sql_query($sql);
		$max_forum_id = (int) $db->sql_fetchfield('max_forum_id');
		$db->sql_freeresult($result);

		$max_forum_id++;

		// Determine the highest order
		$sql = 'SELECT MAX(forum_order) AS max_forum_order
			FROM ' . FORUMS_TABLE;
		$result = $db->sql_query($sql);
		$max_forum_order = (int) $db->sql_fetchfield('max_forum_order');
		$db->sql_freeresult($result);

		$max_forum_order = $max_forum_order + 10;

		// Insert categories
		$sql = 'SELECT *
			FROM ' . CATEGORIES_TABLE . '
			ORDER BY cat_order';
		$result = $db->sql_query($sql);

		$cats_added = array();
		while ($row = $db->sql_fetchrow($result))
		{
			$sql_ary = array(
				'forum_id' => $max_forum_id,
				'parent_id' => empty($row['cat_main']) ? 0 : $row['cat_main'],
				'main_type' => empty($row['cat_main_type']) ? 'c' : $row['cat_main_type'],
				'forum_order' => $row['cat_order'], //$max_forum_order
				'forum_name' => $row['cat_title'],
				'forum_desc' => $row['cat_desc'],
				'forum_type' => FORUM_CAT,
				'icon' => $row['icon']
			);

			$sql = 'INSERT INTO ' . FORUMS_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_ary);
			$db->sql_query($sql);

			$cats_added[$row['cat_id']] = $max_forum_id;
			$max_forum_id++;
			$max_forum_order = $max_forum_order + 10;
		}
		$db->sql_freeresult($result);

		foreach ($cats_added as $k => $v)
		{
			$sql = 'UPDATE ' . FORUMS_TABLE . ' SET parent_id = ' . $v . ' WHERE parent_id = ' . $k . ' AND main_type = \'c\'';
			$db->sql_query($sql);
		}
		$sql = 'RENAME TABLE `' . CATEGORIES_TABLE . '` TO `_old_' . CATEGORIES_TABLE . '`';
		$db->sql_query($sql);

		$this->fix_last_posters();
	}

	/**
	* Convert all usernames to username_clean
	* Theoretically there should be no collision, since Icy Phoenix doesn't allow special chars in usernames
	*/
	function convert_usernames()
	{
		global $db, $table_prefix, $lang;

		if (!function_exists('utf8_clean_string'))
		{
			include_once(IP_ROOT_PATH . 'includes/utf/utf_tools.' . PHP_EXT);
		}

		/*
		// ASSIGN FOUNDER STATUS - BEGIN
		// Grab user ids of users with user_level of ADMIN
		$sql = "SELECT user_id
			FROM " . $table_prefix . "users
			WHERE user_level = 1
			ORDER BY user_regdate ASC";
		$result = $db->sql_query($sql);

		while ($row = $db->sql_fetchrow($result))
		{
			$user_id = (int) $row['user_id'];
			// Set founder admin...
			$sql = "UPDATE " . $table_prefix . "users
				SET user_type = " . USER_FOUNDER . "
				WHERE user_id = $user_id";
			$db->sql_query($sql);
		}
		$db->sql_freeresult($result);
		// ASSIGN FOUNDER STATUS - END
		*/

		// Make sure we have username_clean field
		$sql_username_clean = "ALTER TABLE " . $table_prefix . "users ADD username_clean varchar(255) DEFAULT '' NOT NULL AFTER username";
		$db->sql_return_on_error(true);
		$db->sql_query($sql_username_clean);
		$db->sql_return_on_error(false);

		// Create a temporary table in which we store the clean usernames
		$drop_sql = 'DROP TABLE ' . $table_prefix . 'userconv';
		$create_sql = 'CREATE TABLE ' . $table_prefix . 'userconv (
			user_id mediumint(8) NOT NULL,
			username_clean varchar(255) DEFAULT \'\' NOT NULL
		)';

		$db->sql_return_on_error(true);
		$db->sql_query($drop_sql);
		$db->sql_return_on_error(false);
		$db->sql_query($create_sql);

		// Now select all user_ids and usernames and then convert the username (this can take quite a while!)
		$sql = 'SELECT user_id, username
			FROM ' . $table_prefix . 'users';
		$result = $db->sql_query($sql);

		$insert_ary = array();
		$i = 0;
		while ($row = $db->sql_fetchrow($result))
		{
			$clean_name = utf8_clean_string($row['username']);
			$insert_ary[] = array('user_id' => $row['user_id'], 'username_clean' => $clean_name);

			$sql_update = "UPDATE " . $table_prefix . "users SET username_clean = '" . $db->sql_escape($clean_name) . "' WHERE user_id = " . (int) $row['user_id'];
			$db->sql_return_on_error(true);
			$db->sql_query($sql_update);
			$db->sql_return_on_error(false);

			if ($i % 1000 == 999)
			{
				$db->sql_multi_insert($table_prefix . 'userconv', $insert_ary);
				$insert_ary = array();
			}
			$i++;
		}
		$db->sql_freeresult($result);

		if (sizeof($insert_ary))
		{
			$db->sql_multi_insert($table_prefix . 'userconv', $insert_ary);
		}
		unset($insert_ary);

		// Now find the clean version of the usernames that collide
		$sql = 'SELECT username_clean
			FROM ' . $table_prefix . 'userconv
			GROUP BY username_clean
			HAVING COUNT(user_id) > 1';
		$result = $db->sql_query($sql);

		$colliding_names = array();
		while ($row = $db->sql_fetchrow($result))
		{
			$colliding_names[] = $row['username_clean'];
		}
		$db->sql_freeresult($result);

		// There was at least one collision, the admin will have to solve it before conversion can continue
		if (sizeof($colliding_names))
		{
			$sql = 'SELECT user_id, username_clean
				FROM ' . $table_prefix . 'userconv
				WHERE ' . $db->sql_in_set('username_clean', $colliding_names);
			$result = $db->sql_query($sql);
			unset($colliding_names);

			$colliding_user_ids = array();
			while ($row = $db->sql_fetchrow($result))
			{
				$colliding_user_ids[(int) $row['user_id']] = $row['username_clean'];
			}
			$db->sql_freeresult($result);

			$sql = 'SELECT username, user_id, user_posts
				FROM ' . $table_prefix . 'users
				WHERE ' . $db->sql_in_set('user_id', array_keys($colliding_user_ids));
			$result = $db->sql_query($sql);

			$colliding_users = array();
			while ($row = $db->sql_fetchrow($result))
			{
				$row['user_id'] = (int) $row['user_id'];
				if (isset($colliding_user_ids[$row['user_id']]))
				{
					$colliding_users[$colliding_user_ids[$row['user_id']]][] = $row;
				}
			}
			$db->sql_freeresult($result);
			unset($colliding_user_ids);

			$list = '';
			foreach ($colliding_users as $username_clean => $users)
			{
				$list .= sprintf($lang['COLLIDING_CLEAN_USERNAME'], $username_clean) . "<br />\n";
				foreach ($users as $i => $row)
				{
					$list .= sprintf($lang['COLLIDING_USER'], $row['user_id'], $row['username'], $row['user_posts']) . "<br />\n";
				}
			}

			$lang['INST_ERR_FATAL'] = $lang['CONV_ERR_FATAL'];
			die('<span style="color: red;">' . $lang['COLLIDING_USERNAMES_FOUND'] . '</span></b><br /><br />' . $list . '<b>');
		}
		else
		{

		}

		$db->sql_query($drop_sql);
	}

	/**
	* Fix last posters
	*/
	function fix_last_posters()
	{
		global $db, $table_prefix;

		$sql = "UPDATE `" . $table_prefix . "forums` f, `" . $table_prefix . "topics` t, `" . $table_prefix . "posts` p, `" . $table_prefix . "users` u SET f.forum_last_topic_id = p.topic_id, f.forum_last_poster_id = p.poster_id, f.forum_last_post_subject = t.topic_title, f.forum_last_post_time = p.post_time, f.forum_last_poster_name = u.username, f.forum_last_poster_color = u.user_color
		WHERE f.forum_last_post_id = p.post_id
		AND t.topic_id = p.topic_id
		AND p.poster_id = u.user_id";
		$db->sql_query($sql);

		$sql = "UPDATE `" . $table_prefix . "topics` t, `" . $table_prefix . "posts` p, `" . $table_prefix . "posts` p2, `" . $table_prefix . "users` u, `" . $table_prefix . "users` u2 SET t.topic_first_post_id = p.post_id, t.topic_first_post_time = p.post_time, t.topic_first_poster_id = p.poster_id, t.topic_first_poster_name = u.username, t.topic_first_poster_color = u.user_color, t.topic_last_post_id = p2.post_id, t.topic_last_post_time = p2.post_time, t.topic_last_poster_id = p2.poster_id, t.topic_last_poster_name = u2.username, t.topic_last_poster_color = u2.user_color
		WHERE t.topic_first_post_id = p.post_id
		AND p.poster_id = u.user_id
		AND t.topic_last_post_id = p2.post_id
		AND p2.poster_id = u2.user_id";
		$db->sql_query($sql);

		return true;
	}

	/**
	* Rename and move images
	*/
	function ren_move_images($action)
	{
		global $db, $config, $lang, $language;
		global $wip;
		global $pics_number, $total_pics_modified;

		$lang_append = '&amp;lang=' . $language;

		$table_output = '';
		$pics_moved = '';

		switch ($action)
		{
			case 'fix':
				$posted_images_folder = POSTED_IMAGES_PATH;
				$counter = 0;
				$dir = @opendir($posted_images_folder);
				while($file = readdir($dir))
				{
					if (!is_dir($posted_images_folder . $file))
					{
						$process_item = (($file != '.') && ($file != '..') && (!is_dir($posted_images_folder . $file)) && (!is_link($posted_images_folder . $file))) ? true : false;
						if($process_item)
						{
							if(preg_match('/(\.gif$|\.tif$|\.png$|\.jpg$|\.jpeg$)$/is', $file))
							{
								$counter++;
								if ($counter > $pics_number)
								{
									break;
								}
								$tmp_split = explode('_', $file);
								if ($tmp_split[0] == 'user')
								{
									$pic_path = $posted_images_folder . $tmp_split[1] . '/';
									$pic_old_file = $posted_images_folder . $file;
									$pic_new_file = $posted_images_folder . $tmp_split[1] . '/' . str_replace('user_' . $tmp_split[1] . '_', '', $file);
									if (!is_dir($pic_path))
									{
										$dir_creation = @mkdir($pic_path, 0777);
										@copy($posted_images_folder . 'index.html', $posted_images_folder . $tmp_split[1] . '/index.html');
										@chmod($posted_images_folder . $tmp_split[1] . 'index.html', 0755);
									}
									@chmod($pic_old_file, 0755);
									$copy_success = @copy($pic_old_file, $pic_new_file);
									if ($copy_success)
									{
										@chmod($pic_new_file, 0755);
										$pics_moved .= '<li><span class="gensmall">' . $pic_old_file . ' => ' . $pic_new_file . '</span></li>';
										@unlink($pic_old_file);
									}
								}
								$total_pics_modified++;
							}
						}
					}
				}

				if ($counter == 0)
				{
					$wip = false;
					$table_output .= '<br /><br />' . "\n";
					$table_output .= '<div class="post-text">' . "\n";
					$table_output .= '<ul type="circle" style="align: left;">' . "\n";
					$table_output .= '<li><span style="color: ' . $this->color_green . ';"><b>' . $lang['FixingPicsComplete'] . '</b></span></li>' . "\n";
					$table_output .= '</ul>' . "\n";
					$table_output .= '</div>' . "\n";
					$table_output .= '<br class="clear" />' . "\n";
					$table_output .= '<br /><br />' . "\n";
					return $table_output;
				}

				$table_output .= '<div class="post-text" style="width: 90%;">' . "\n";
				$table_output .= '<ul type="circle" style="align: left;">' . "\n";
				$table_output .= $pics_moved . "\n";
				$table_output .= '</ul>' . "\n";
				$table_output .= '</div>' . "\n";
				$table_output .= '<br class="clear" />' . "\n";
				$table_output .= '<br /><br />' . "\n";

				// FIX PA_FILE PATHS - BEGIN
				/*
				$old_pafile_folders = array(IP_ROOT_PATH . 'pafiledb/uploads/', IP_ROOT_PATH . 'pafiledb/images/screenshots/');
				$new_pafile_folders = array(IP_ROOT_PATH . 'downloads/', IP_ROOT_PATH . 'files/screenshots/');
				for ($pac = 0; $pac < sizeof($old_pafile_folders); $pac++)
				{
					if (is_dir($old_pafile_folders[$pac] . $file) && is_dir($new_pafile_folders[$pac] . $file))
					{
						$dir = @opendir($old_pafile_folders[$pac]);
						while($file = readdir($dir))
						{
							if (!is_dir($file))
							{
								$process_item = (($file != '.') && ($file != '..') && !is_link($file)) ? true : false;
								if(($process_item) && !file_exists($new_pafile_folders[$pac] . $file))
								{
									$result = @rename($old_pafile_folders[$pac] . $file, $new_pafile_folders[$pac] . $file);
								}
							}
						}
					}
				}
				*/
				// FIX PA_FILE PATHS - END

				break;

			default:
				$table_output .= '<form action="' . ip_functions::append_sid(THIS_FILE . '?mode=ren_move_images&amp;action=fix&amp;wip=true' . $lang_append) . '" method="post" enctype="multipart/form-data">' . "\n";
				$table_output .= '<table class="forumline">' . "\n";
				$table_output .= '<tr><td class="row-header" colspan="2"><span>' . $lang['RenMovePics'] . '</span></td></tr>' . "\n";
				$table_output .= '<tr>' . "\n";
				$table_output .= '	<td class="row1" width="200"><span class="genmed">' . $lang['PicsPerStep'] . '&nbsp;</span></td>' . "\n";
				$table_output .= '	<td class="row1"><span class="genmed"><input type="text" class="post" name="pics_number" value="100" size="10" /></span></td>' . "\n";
				$table_output .= '</tr>' . "\n";
				$table_output .= '<tr>' . "\n";
				$table_output .= '	<td class="cat" colspan="2" style="border-right-width: 0px; border-bottom-width: 0px;"><input type="submit" class="mainoption" name="submit" value="' . $lang['Start'] . '" /></td>' . "\n";
				$table_output .= '</tr>' . "\n";
				$table_output .= '</table>' . "\n";
				$table_output .= '</form>' . "\n";
		}

		return $table_output;
	}

	/**
	* Add uploaded images to DB
	*/
	function add_uploaded_images_to_db($action)
	{
		global $db, $cache, $config, $user, $lang, $language;
		global $wip;
		global $pics_number, $pic_start, $total_pics, $total_pics_modified;

		if (!defined('POSTED_IMAGES_PATH'))
		{
			include(IP_ROOT_PATH . 'includes/constants.' . PHP_EXT);
		}

		include(IP_ROOT_PATH . 'includes/class_images.' . PHP_EXT);
		$class_images = new class_images();

		$lang_append = '&amp;lang=' . $language;

		$table_output = '';
		$pics_moved = '';

		switch ($action)
		{
			case 'fix':
				$posted_images_folder = POSTED_IMAGES_PATH;
				if ($total_pics == 0)
				{
					$total_pics_modified = 0;
				}

				if (($all_pics_array = $cache->get('_uploaded_images')) === false)
				{
					$all_pics_array = array();
					$dir = @opendir($posted_images_folder);
					while($file = readdir($dir))
					{
						$item_path = $posted_images_folder . $file;
						$process_item = (($file != '.') && ($file != '..') && (is_dir($item_path)) && (!is_link($item_path))) ? true : false;
						if ($process_item)
						{
							$sub_dir = @opendir($item_path);
							while($sub_file = readdir($sub_dir))
							{
								$item_sub_path = $file . '/' . $sub_file;
								$item_sub_path_full = $posted_images_folder . $item_sub_path;
								$process_item = (($sub_file != '.') && ($sub_file != '..') && (!is_dir($item_sub_path_full)) && (!is_link($item_sub_path_full))) ? true : false;
								if($process_item && preg_match('/(\.gif$|\.tif$|\.png$|\.jpg$|\.jpeg$)$/is', $sub_file))
								{
									$all_pics_array[] = $item_sub_path;
								}
							}
						}
					}
					$cache->put('_uploaded_images', $all_pics_array);
				}
				$total_pics = sizeof($all_pics_array);

				if ($total_pics_modified >= $total_pics)
				{
					$wip = false;
					$table_output .= '<br /><br />' . "\n";
					$table_output .= '<div class="post-text">' . "\n";
					$table_output .= '<ul type="circle" style="align: left;">' . "\n";
					$table_output .= '<li><span style="color: ' . $this->color_green . ';"><b>' . $lang['FixingPicsComplete'] . '</b></span></li>' . "\n";
					$table_output .= '</ul>' . "\n";
					$table_output .= '</div>' . "\n";
					$table_output .= '<br class="clear" />' . "\n";
					$table_output .= '<br /><br />' . "\n";
					return $table_output;
				}

				$pics_updated = '';
				for ($i = $pic_start; $i < ($pic_start + $pics_number); $i++)
				{
					if (!empty($all_pics_array[$i]))
					{
						$image_data_tmp = explode('/', $all_pics_array[$i]);
						$image_details = $class_images->get_image_details($posted_images_folder . $all_pics_array[$i]);
						$image_data = array(
							'pic_filename' => $image_data_tmp[1],
							'pic_size' => $image_details['size'],
							'pic_title' => $image_data_tmp[1],
							'pic_desc' => $image_data_tmp[1],
							'pic_user_id' => $image_data_tmp[0],
							'pic_user_ip' => '0.0.0.0',
							'pic_time' => $image_details['time'],
						);
						//print_r($image_data);
						$image_submit = $class_images->submit_image($image_data, 'insert');
						$pics_updated .= '<li>' . $all_pics_array[$i] . '</li>';
					}
					$total_pics_modified++;
				}

				if ($total_pics_modified > $total_pics)
				{
					$total_pics_modified = $total_pics;
				}

				$table_output .= '<div class="post-text" style="width: 90%;">' . "\n";
				$table_output .= '<ul type="circle" style="align: left;">' . "\n";
				$table_output .= $pics_updated . "\n";
				$table_output .= '</ul>' . "\n";
				$table_output .= '</div>' . "\n";
				$table_output .= '<br class="clear" />' . "\n";
				$table_output .= '<br /><br />' . "\n";

				// Increase $post_start to process the other posts
				$pic_start = ($pic_start + $pics_number);

			break;

			default:
				$table_output .= '<form action="' . ip_functions::append_sid(THIS_FILE . '?mode=add_uploaded_images_to_db&amp;action=fix&amp;wip=true' . $lang_append) . '" method="post" enctype="multipart/form-data">' . "\n";
				$table_output .= '<table class="forumline">' . "\n";
				$table_output .= '<tr><td class="row-header" colspan="2"><span>' . $lang['AddPostedPicsDB'] . '</span></td></tr>' . "\n";
				$table_output .= '<tr>' . "\n";
				$table_output .= '	<td class="row1" width="200"><span class="genmed">' . $lang['PicsPerStep'] . '&nbsp;</span></td>' . "\n";
				$table_output .= '	<td class="row1"><span class="genmed"><input type="text" class="post" name="pics_number" value="100" size="10" /></span></td>' . "\n";
				$table_output .= '</tr>' . "\n";
				$table_output .= '<tr>' . "\n";
				$table_output .= '	<td class="row1" width="200"><span class="genmed">' . $lang['PicStartFrom'] . '&nbsp;</span></td>' . "\n";
				$table_output .= '	<td class="row1"><span class="genmed"><input type="text" class="post" name="pic_start" value="0" size="10" /></span></td>' . "\n";
				$table_output .= '</tr>' . "\n";
				$table_output .= '<tr>' . "\n";
				$table_output .= '	<td class="cat" colspan="2" style="border-right-width: 0px; border-bottom-width: 0px;"><input type="submit" class="mainoption" name="submit" value="' . $lang['Start'] . '" /></td>' . "\n";
				$table_output .= '</tr>' . "\n";
				$table_output .= '</table>' . "\n";
				$table_output .= '</form>' . "\n";
		}

		return $table_output;
	}

	/*
	* Displays a box with upgrade info
	*/
	function box_upgrade_info()
	{
		global $lang, $language;
		global $current_phpbb_version, $phpbb_version;
		global $current_ip_version, $ip_version;

		echo('<br />' . "\n");
		$phpbb_update = '';
		if ($current_phpbb_version == $phpbb_version)
		{
			//$box_message = $lang['phpBB_Version_UpToDate'];
			//$page_framework->box('green', 'green', $box_message);
		}
		else
		{
			$phpbb_update = '&amp;phpbb_update=true';
			// Comment "Force phpBB update" if you want to make all db updates all at once
			// Force phpBB update - BEGIN
			$box_message = $lang['phpBB_Version_NotUpToDate'] . '<br /><br />' . sprintf($lang['ClickUpdate'], '<a href="' . ip_functions::append_sid(THIS_FILE . '?mode=update_phpbb') . '">', '</a>');
			$this->box('yellow', 'red', $box_message);
			$this->page_footer(false);
			exit;
			// Force phpBB update - END
		}
		//echo('<br /><br />');

		if (($current_ip_version == $ip_version) && ($phpbb_update == ''))
		{
			$needs_update = false;
		}
		else
		{
			$needs_update = true;
			$phpbb_string = '';
			if ($phpbb_update != '')
			{
				$phpbb_string = $lang['phpBB_Version_NotUpToDate'] . '<br /><br />';
			}

			$ip_string = $lang['IcyPhoenix_Version_NotUpToDate'] . '<br /><br />';
			if ($current_ip_version == $lang['NotInstalled'])
			{
				$ip_string = $lang['IcyPhoenix_Version_NotInstalled'] . '<br /><br />';
			}
		}

		if ($needs_update == false)
		{
			$box_message = $lang['IcyPhoenix_Version_UpToDate'];
			$this->box('green', 'green', $box_message);
		}
		elseif (($needs_update == true) && version_compare($current_ip_version, '1.2.9.36', '<') && !defined('IP_DB_UPDATE'))
		{
			$this->box_upgrade_steps();
		}
		else
		{
			$box_message = $phpbb_string . $ip_string . sprintf($lang['ClickUpdate'], '<a href="' . ip_functions::append_sid(THIS_FILE . '?mode=update' . $phpbb_update) . '">', '</a>');
			$this->box('yellow', 'red', $box_message);
		}
		echo('<br class="clear" />' . "\n");
		echo('<br /><br />' . "\n");
	}

	/*
	* Displays a box with upgrade steps
	*/
	function box_upgrade_steps()
	{
		global $lang, $language;

		$lang_append = '&amp;lang=' . $language;
		$img_ok = str_replace('alt="" title=""', 'alt="' . $lang['Done'] . '" title="' . $lang['Done'] . '"', $this->img_ok);
		$img_error = str_replace('alt="" title=""', 'alt="' . $lang['NotDone'] . '" title="' . $lang['NotDone'] . '"', $this->img_error);

		$table_update_options = '';
		$table_update_options .= '<div class="post-text">' . "\n";

		$table_update_options .= '<div class="genmed"><br /><ol type="1">' . "\n";
		$table_update_options .= '<li><span class="text_red"><strong>' . $lang['MakeFullBackup'] . '</strong></span><br /><br /></li>' . "\n";

		//$table_update_options .= '<li><a href="' . ip_functions::append_sid(THIS_FILE . '?mode=update' . $lang_append) . '"><span class="text_gray">' . $lang['Update_phpBB'] . '</span></a><br /><br /></li>' . "\n";

		$table_update_options .= '<li><span class="text_red">' . $lang['Upload_NewFiles'] . '</span><br /><br /></li>' . "\n";
		$table_update_options .= '<li><a href="' . ip_functions::append_sid(THIS_FILE . '?mode=fix_posts_ip2' . $lang_append) . '"><span class="text_orange">' . $lang['FixPosts_IP2'] . '</span></a><br /><br /></li>' . "\n";
		$table_update_options .= '<li><a href="' . ip_functions::append_sid(THIS_FILE . '?mode=fix_signatures_ip2' . $lang_append) . '"><span class="text_orange">' . $lang['FixSignatures_IP2'] . '</span></a><br /><br /></li>' . "\n";
		$table_update_options .= '<li><a href="' . ip_functions::append_sid(THIS_FILE . '?mode=add_uploaded_images_to_db' . $lang_append) . '"><span class="text_orange">' . $lang['AddPostedPicsDB'] . '</span></a><br /><br /></li>' . "\n";
		// Updating to Icy Phoenix 1.3
		/*
		$table_update_options .= '<li><a href="' . ip_functions::append_sid(THIS_FILE . '?mode=fix_forums' . $lang_append) . '"><span class="text_orange">' . $lang['FixForums'] . '</span></a><br /><br /></li>' . "\n";
		$table_update_options .= '<li><a href="' . ip_functions::append_sid(THIS_FILE . '?mode=fix_posts' . $lang_append) . '"><span class="text_orange">' . $lang['Remove_BBCodeUID'] . '</span></a><br /><br /></li>' . "\n";
		$table_update_options .= '<li><a href="' . ip_functions::append_sid(THIS_FILE . '?mode=update' . $lang_append) . '"><span class="text_orange">' . $lang['Merge_PostsTables'] . '</span></a><br /><br /></li>' . "\n";
		$table_update_options .= '<li><a href="' . ip_functions::append_sid(THIS_FILE . '?mode=update' . $lang_append) . '"><span class="text_orange">' . $lang['Update_IcyPhoenix'] . '</span></a><br /><br /></li>' . "\n";
		$table_update_options .= '<li><span class="text_red">' . $lang['Upload_NewFiles'] . '</span><br /><br /></li>' . "\n";
		$table_update_options .= '<li><a href="' . ip_functions::append_sid(THIS_FILE . '?mode=fix_constants' . $lang_append) . '"><span class="text_orange">' . $lang['Adjust_Config'] . '</span></a><br /><br /></li>' . "\n";
		$table_update_options .= '<li><a href="' . ip_functions::append_sid(THIS_FILE . '?mode=fix_constants' . $lang_append) . '"><span class="text_orange">' . $lang['Adjust_CMSPages'] . '</span></a><br /><br /></li>' . "\n";
		$table_update_options .= '<li><a href="' . ip_functions::append_sid(THIS_FILE . '?mode=fix_images_album' . $lang_append) . '"><span class="text_blue">' . $lang['MoveImagesAlbum'] . '</span></a><br /><br /></li>' . "\n";
		$table_update_options .= '<li><a href="' . ip_functions::append_sid(THIS_FILE . '?mode=ren_move_images' . $lang_append) . '"><span class="text_blue">' . $lang['MoveImages'] . '</span></a><br /><br /></li>' . "\n";
		$table_update_options .= '<li><a href="' . ip_functions::append_sid(THIS_FILE . '?mode=clean_old_files' . $lang_append) . '"><span class="text_green">' . $lang['Clean_OldFiles'] . '</span></a><br /><br /></li>' . "\n";
		*/
		$table_update_options .= '</ol></div>' . "\n";

		$table_update_options .= '<br />' . "\n";

		$table_update_options .= '<div class="forumline" style="width: 700px; padding: 5px;">' . "\n";
		$table_update_options .= '<strong>' . $lang['ColorsLegend'] . '</strong><br />' . "\n";
		$table_update_options .= '<div class="gensmall"><ul>' . "\n";
		$table_update_options .= '<li><span class="text_red">' . $lang['ColorsLegendRed'] . '</span></li>' . "\n";
		$table_update_options .= '<li><span class="text_orange">' . $lang['ColorsLegendOrange'] . '</span></li>' . "\n";
		$table_update_options .= '<li><span class="text_gray">' . $lang['ColorsLegendGray'] . '</span></li>' . "\n";
		$table_update_options .= '<li><span class="text_blue">' . $lang['ColorsLegendBlue'] . '</span></li>' . "\n";
		$table_update_options .= '<li><span class="text_green">' . $lang['ColorsLegendGreen'] . '</span></li>' . "\n";
		$table_update_options .= '</ul></div>' . "\n";
		$table_update_options .= '</div>' . "\n";

		$table_update_options .= '<br />' . "\n";

		$table_update_options .= '</div>' . "\n";

		$this->table_begin($lang['Upgrade_Steps'], '', 0, 'upgrade_steps');
		echo($table_update_options);
		$this->table_end('upgrade_steps');
	}

	function box_ip_tools()
	{
		global $lang, $language;

		$lang_append = '&amp;lang=' . $language;

		//$this->output_lang_select(THIS_FILE, true);

		//$this->box('yellow', 'red', 'Icy Phoenix');

		$table_update_options = '';
		$table_update_options .= '<div class="post-text">' . "\n";

		// Update Options
		$table_update_options .= '<br /><br />' . "\n";
		$table_update_options .= '<span style="color:#880088;font-weight:bold">' . $lang['Upgrade_Options'] . '</span><br />' . "\n";

		// Only update options to be written inside the spoiler
		$update_options = '';
		$update_options .= '<div class="genmed"><br /><ul type="circle">' . "\n";
		$update_options .= '<li><a href="' . ip_functions::append_sid(THIS_FILE . '?mode=update' . $lang_append) . '"><span class="text_red">' . $lang['Upgrade_From'] . ' ' . $lang['Upgrade_From_phpBB'] . '</span></a><br /><br /></li>' . "\n";
		$update_options .= '<li><a href="' . ip_functions::append_sid(THIS_FILE . '?mode=update_11015' . $lang_append) . '"><span class="text_orange">' . $lang['Upgrade_From'] . ' ' . $lang['Upgrade_From_Version'] . ' 1.1.0.15</span></a><br /><br /></li>' . "\n";
		$update_options .= '<li><a href="' . ip_functions::append_sid(THIS_FILE . '?mode=update_12027' . $lang_append) . '"><span class="text_green">' . $lang['Upgrade_From'] . ' ' . $lang['Upgrade_From_Version'] . ' 1.2.0.27</span></a><br /><br /></li>' . "\n";
		$update_options .= '<li><a href="' . ip_functions::append_sid(THIS_FILE . '?mode=update_13053' . $lang_append) . '"><span class="text_blue">' . $lang['Upgrade_From'] . ' ' . $lang['Upgrade_From_Version'] . ' 1.3.0.53</span></a><br /><br /></li>' . "\n";
		$update_options .= '<li><a href="' . ip_functions::append_sid(THIS_FILE . '?mode=update_20084' . $lang_append) . '"><span class="text_gray">' . $lang['Upgrade_From'] . ' ' . $lang['Upgrade_From_Version'] . ' 2.0.0.84RC1 (' . $lang['Upgrade_Higher'] . ')</span></a><br /><br /></li>' . "\n";
		$update_options .= '</ul></div>' . "\n";

		// Output the spoiler
		$table_update_options .= $this->spoiler('update_options', $update_options, false);

		// CHMOD
		$table_update_options .= '<br /><br />' . "\n";
		$table_update_options .= '<span style="color: #008888; font-weight: bold;">' . $lang['CHMOD_Files'] . '</span><br />' . "\n";
		$table_update_options .= '<div class="genmed"><br /><ul type="circle">' . "\n";
		$table_update_options .= '<li><a href="' . ip_functions::append_sid(THIS_FILE . '?mode=chmod' . $lang_append) . '"><span class="text_red">' . $lang['CHMOD_Apply'] . '</span></a><br /><span class="gensmall">' . $lang['CHMOD_Apply_Warn'] . '</span><br /><br /></li>' . "\n";
		$table_update_options .= '</ul></div>' . "\n";

		// Fix Posts IP 2.0
		$table_update_options .= '<br /><br />' . "\n";
		$table_update_options .= '<span style="color: #228822; font-weight: bold;">' . $lang['FixPosts_IP2'] . '</span><br />' . "\n";
		$table_update_options .= '<div class="genmed"><br /><ul type="circle">' . "\n";
		$table_update_options .= '<li><a href="' . ip_functions::append_sid(THIS_FILE . '?mode=fix_posts_ip2' . $lang_append) . '"><span class="text_red">' . $lang['FixPosts_IP2_Explain'] . '</span></a><br /><span class="gensmall">' . $lang['ActionUndone'] . '</span><br /><br /></li>' . "\n";
		$table_update_options .= '</ul></div>' . "\n";

		// Fix Signatures IP 2.0
		$table_update_options .= '<br /><br />' . "\n";
		$table_update_options .= '<span style="color: #ffdd22; font-weight: bold;">' . $lang['FixSignatures_IP2'] . '</span><br />' . "\n";
		$table_update_options .= '<div class="genmed"><br /><ul type="circle">' . "\n";
		$table_update_options .= '<li><a href="' . ip_functions::append_sid(THIS_FILE . '?mode=fix_signatures_ip2' . $lang_append) . '"><span class="text_red">' . $lang['FixSignatures_IP2_Explain'] . '</span></a><br /><span class="gensmall">' . $lang['ActionUndone'] . '</span><br /><br /></li>' . "\n";
		$table_update_options .= '</ul></div>' . "\n";

		// Add Posted Pics To DB
		$table_update_options .= '<br /><br />' . "\n";
		$table_update_options .= '<span style="color: #aa0022; font-weight: bold;">' . $lang['AddPostedPicsDB'] . '</span><br />' . "\n";
		$table_update_options .= '<div class="genmed"><br /><ul type="circle">' . "\n";
		$table_update_options .= '<li><a href="' . ip_functions::append_sid(THIS_FILE . '?mode=add_uploaded_images_to_db' . $lang_append) . '"><span class="text_red">' . $lang['AddPostedPicsDBExplain'] . '</span></a><br /><span class="gensmall">' . $lang['ActionUndone'] . '</span><br /><br /></li>' . "\n";
		$table_update_options .= '</ul></div>' . "\n";

		// Fix Forums
		$table_update_options .= '<br /><br />' . "\n";
		$table_update_options .= '<span style="color: #dd2222; font-weight: bold;">' . $lang['FixForums'] . '</span><br />' . "\n";
		$table_update_options .= '<div class="genmed"><br /><ul type="circle">' . "\n";
		$table_update_options .= '<li><a href="' . ip_functions::append_sid(THIS_FILE . '?mode=fix_forums' . $lang_append) . '"><span class="text_red">' . $lang['FixForumsExplain'] . '</span></a><br /><span class="gensmall">' . $lang['ActionUndone'] . '</span><br /><br /></li>' . "\n";
		$table_update_options .= '</ul></div>' . "\n";

		// Fix Pics
		$table_update_options .= '<br /><br />' . "\n";
		$table_update_options .= '<span style="color: #ff5500; font-weight: bold;">' . $lang['FixPics'] . '</span><br />' . "\n";
		$table_update_options .= '<div class="genmed"><br /><ul type="circle">' . "\n";
		$table_update_options .= '<li><a href="' . ip_functions::append_sid(THIS_FILE . '?mode=fix_images_album' . $lang_append) . '"><span class="text_red">' . $lang['FixPicsExplain'] . '</span></a><br /><span class="gensmall">' . $lang['ActionUndone'] . '</span><br /><br /></li>' . "\n";
		$table_update_options .= '</ul></div>' . "\n";

		// Rename And Move Posted Pics
		$table_update_options .= '<br /><br />' . "\n";
		$table_update_options .= '<span style="color: #aa0022; font-weight: bold;">' . $lang['RenMovePics'] . '</span><br />' . "\n";
		$table_update_options .= '<div class="genmed"><br /><ul type="circle">' . "\n";
		$table_update_options .= '<li><a href="' . ip_functions::append_sid(THIS_FILE . '?mode=ren_move_images' . $lang_append) . '"><span class="text_red">' . $lang['RenMovePicsExplain'] . '</span></a><br /><span class="gensmall">' . $lang['ActionUndone'] . '</span><br /><br /></li>' . "\n";
		$table_update_options .= '</ul></div>' . "\n";

		// Fix Birthdays
		$table_update_options .= '<br /><br />' . "\n";
		$table_update_options .= '<span style="color: #aa6622; font-weight: bold;">' . $lang['FixBirthdays'] . '</span><br />' . "\n";
		$table_update_options .= '<div class="genmed"><br /><ul type="circle">' . "\n";
		$table_update_options .= '<li><a href="' . ip_functions::append_sid(THIS_FILE . '?mode=fix_birthdays' . $lang_append) . '"><span class="text_red">' . $lang['FixBirthdaysExplain'] . '</span></a><br /><span class="gensmall">' . $lang['ActionUndone'] . '</span><br /><br /></li>' . "\n";
		$table_update_options .= '</ul></div>' . "\n";

		// Fix Posts
		$table_update_options .= '<br /><br />' . "\n";
		$table_update_options .= '<span style="color: #006600; font-weight: bold;">' . $lang['FixPosts'] . '</span><br />' . "\n";
		$table_update_options .= '<div class="genmed"><br /><ul type="circle">' . "\n";
		$table_update_options .= '<li><a href="' . ip_functions::append_sid(THIS_FILE . '?mode=fix_posts' . $lang_append) . '"><span class="text_red">' . $lang['FixPosts_Explain'] . '</span></a><br /><span class="gensmall">' . $lang['ActionUndone'] . '</span><br /><br /></li>' . "\n";
		$table_update_options .= '</ul></div>' . "\n";

		// Fix Signatures
		$table_update_options .= '<br /><br />' . "\n";
		$table_update_options .= '<span style="color: #ddaa00; font-weight: bold;">' . $lang['FixSignatures'] . '</span><br />' . "\n";
		$table_update_options .= '<div class="genmed"><br /><ul type="circle">' . "\n";
		$table_update_options .= '<li><a href="' . ip_functions::append_sid(THIS_FILE . '?mode=fix_signatures' . $lang_append) . '"><span class="text_red">' . $lang['FixSignatures_Explain'] . '</span></a><br /><span class="gensmall">' . $lang['ActionUndone'] . '</span><br /><br /></li>' . "\n";
		$table_update_options .= '</ul></div>' . "\n";

		// Fix Constants
		$table_update_options .= '<br /><br />' . "\n";
		$table_update_options .= '<span style="color: #0033cc; font-weight: bold;">' . $lang['FixConstantsInFiles'] . '</span><br />' . "\n";
		$table_update_options .= '<div class="genmed"><br /><ul type="circle">' . "\n";
		$table_update_options .= '<li><a href="' . ip_functions::append_sid(THIS_FILE . '?mode=fix_constants' . $lang_append) . '"><span class="text_red">' . $lang['FixConstantsInFilesExplain'] . '</span></a><br /><span class="gensmall">' . $lang['ActionUndone'] . '</span><br /><br /></li>' . "\n";
		$table_update_options .= '</ul></div>' . "\n";

		// Clean Old Files
		$table_update_options .= '<br /><br />' . "\n";
		$table_update_options .= '<span style="color: #ff00ff; font-weight: bold;">' . $lang['Clean_OldFiles'] . '</span><br />' . "\n";
		$table_update_options .= '<div class="genmed"><br /><ul type="circle">' . "\n";
		$table_update_options .= '<li><a href="' . ip_functions::append_sid(THIS_FILE . '?mode=clean_old_files' . $lang_append) . '"><span class="text_red">' . $lang['Clean_OldFiles_Explain'] . '</span></a><br /><span class="gensmall">' . $lang['ActionUndone'] . '</span><br /><br /></li>' . "\n";
		$table_update_options .= '</ul></div>' . "\n";

		$table_update_options .= '</div>' . "\n";

		$this->table_begin($lang['IP_Utilities'], '', 0, 'ip_tools');
		echo($table_update_options);
		$this->table_end('ip_tools');
	}

	function output_lang_select($form_action = THIS_FILE, $single_line = false)
	{
		global $lang, $language;

		$lang_select = $this->build_lang_select($language);

		$table_lang_select = '<form action="' . $form_action . '" name="install" method="post"><table class="forumline">';
		if ($single_line == true)
		{
			$table_lang_select .= '<tr><td class="row-header" colspan="2"><span><b style="background-image:none;float:right;display:inline;padding-right:5px;">' . $lang_select . '</b>' . $lang['Select_lang'] . '</span></td></tr>';
		}
		else
		{
			$table_lang_select .= '<tr><td class="row-header" colspan="2"><span>' . $lang['Default_lang'] . '</span></td></tr>';
			$table_lang_select .= '<tr><td class="row1" align="right"><span class="gen">' . $lang['Default_lang'] .':</span></td><td class="row2">' . $lang_select . '</td></tr>';
		}
		$table_lang_select .= '</table></form>';
		echo($table_lang_select);
	}

	function build_lang_select($language = 'english')
	{
		$language = ($language == '') ? 'english' : $language;
		$dirname = 'language';
		$dir = opendir($dirname);

		$lang_options = array();
		while ($file = readdir($dir))
		{
			if (preg_match('#^lang_#i', $file) && !is_file(@ip_functions::ip_realpath($dirname . '/' . $file)) && !is_link(@ip_functions::ip_realpath($dirname . '/' . $file)))
			{
				$filename = trim(str_replace('lang_', '', $file));
				$displayname = preg_replace('/^(.*?)_(.*)$/', '\1 [ \2 ]', $filename);
				$displayname = preg_replace('/\[(.*?)_(.*)\]/', '[ \1 - \2 ]', $displayname);
				$lang_options[$displayname] = $filename;
			}
		}

		closedir($dir);

		@asort($lang_options);
		@reset($lang_options);

		$lang_select = '<select name="lang" onchange="this.form.submit()">';
		while (list($displayname, $filename) = @each($lang_options))
		{
			$selected = ($language == $filename) ? ' selected="selected"' : '';
			$lang_select .= '<option value="' . $filename . '"' . $selected . '>' . ucwords($displayname) . '</option>';
		}
		$lang_select .= '</select>';
		return $lang_select;
	}
}

?>