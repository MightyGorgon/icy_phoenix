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

/**
* Function needed to fix config values before passing them to DB
*/
function fix_config_values($config_name, $config_value)
{
	global $config;

	if (in_array($config_name, array('header_table_text')))
	{
		$config_value = htmlspecialchars_decode($config_value, ENT_COMPAT);
	}

	if ($config_name == 'cookie_name')
	{
		$config_value = str_replace('.', '_', $config_value);
	}

	// Attempt to prevent a common mistake with this value,
	// http:// is the protocol and not part of the server name
	if ($config_name == 'server_name')
	{
		$config_value = str_replace('http://', '', $config_value);
	}

	if ($config_name == 'report_forum')
	{
		$config_value = str_replace('f', '', $config_value);
	}

	if ($config_name == 'bin_forum')
	{
		$config_value = str_replace('f', '', $config_value);
	}

	// Attempt to prevent a mistake with this value.
	if ($config_name == 'avatar_path')
	{
		$config_value = trim($config_value);
		if (strstr($config_value, "\0") || !is_dir(IP_ROOT_PATH . $config_value) || !is_writable(IP_ROOT_PATH . $config_value))
		{
			$config_value = $config['avatar_path'];
		}
	}

	// AJAX CHAT - BEGIN
	if ($config_name == 'ajax_chat_msgs_refresh')
	{
		// Just make sure ajax_chat_msgs_refresh is not below 1 second
		$config_value = ((int) $config_value < 1) ? 1 : $config_value;
	}
	if ($config_name == 'ajax_chat_session_refresh')
	{
		// Just make sure ajax_chat_session_refresh is not below 5 seconds
		$config_value = ((int) $config_value < 5) ? 5 : $config_value;
	}
	// AJAX CHAT - END

	return $config_value;
}

// Duplicate forum auth
function duplicate_auth($source_id, $target_id)
{
	global $db, $forum_auth_fields;

	$sql = "SELECT * FROM " . FORUMS_TABLE . "
					WHERE forum_id = " . (int) $source_id;
	$db->sql_return_on_error(true);
	$result = $db->sql_query($sql);
	$db->sql_return_on_error(false);
	if (!$result)
	{
		return false;
	}

	$row = $db->sql_fetchrow($result);
	$auth_sql = '';
	for ($i = 0; $i < sizeof($forum_auth_fields); $i++)
	{
		if ($i < (sizeof($forum_auth_fields) - 1))
		{
			$comma_append = ', ';
		}
		else
		{
			$comma_append = '';
		}
		$auth_sql .= $forum_auth_fields[$i] . ' = \'' . $row[$forum_auth_fields[$i]] . '\'' . $comma_append;
	}

	$sql = "UPDATE " . FORUMS_TABLE . "
		SET ". $auth_sql . "
		WHERE forum_id = " . (int) $target_id;
	$db->sql_return_on_error(true);
	$result = $db->sql_query($sql);
	$db->sql_return_on_error(false);
	if (!$result)
	{
		return false;
	}
	return true;
}

/**
* Check IP addresses
*/
function match_ips($ip_list_match)
{

	$ip_list = array();
	$ip_list_temp = explode(',', $ip_list_match);

	for($i = 0; $i < sizeof($ip_list_temp); $i++)
	{
		if (preg_match('/^([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})[ ]*\-[ ]*([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})$/', trim($ip_list_temp[$i]), $ip_range_explode))
		{
			// Don't ask about all this, just don't ask ... !
			$ip_1_counter = $ip_range_explode[1];
			$ip_1_end = $ip_range_explode[5];

			while ($ip_1_counter <= $ip_1_end)
			{
				$ip_2_counter = ($ip_1_counter == $ip_range_explode[1]) ? $ip_range_explode[2] : 0;
				$ip_2_end = ($ip_1_counter < $ip_1_end) ? 254 : $ip_range_explode[6];

				if (($ip_2_counter == 0) && ($ip_2_end == 254))
				{
					$ip_2_counter = 255;
					$ip_2_fragment = 255;
					$ip_list[] = "$ip_1_counter.255.255.255";
				}

				while ($ip_2_counter <= $ip_2_end)
				{
					$ip_3_counter = (($ip_2_counter == $ip_range_explode[2]) && ($ip_1_counter == $ip_range_explode[1])) ? $ip_range_explode[3] : 0;
					$ip_3_end = (($ip_2_counter < $ip_2_end) || ($ip_1_counter < $ip_1_end)) ? 254 : $ip_range_explode[7];

					if (($ip_3_counter == 0) && ($ip_3_end == 254))
					{
						$ip_3_counter = 255;
						$ip_3_fragment = 255;
						$ip_list[] = "$ip_1_counter.$ip_2_counter.255.255";
					}

					while ($ip_3_counter <= $ip_3_end)
					{
						$ip_4_counter = (($ip_3_counter == $ip_range_explode[3]) && ($ip_2_counter == $ip_range_explode[2]) && ($ip_1_counter == $ip_range_explode[1])) ? $ip_range_explode[4] : 0;
						$ip_4_end = (($ip_3_counter < $ip_3_end) || ($ip_2_counter < $ip_2_end)) ? 254 : $ip_range_explode[8];

						if (($ip_4_counter == 0) && ($ip_4_end == 254))
						{
							$ip_4_counter = 255;
							$ip_4_fragment = 255;
							$ip_list[] = "$ip_1_counter.$ip_2_counter.$ip_3_counter.255";
						}

						while ($ip_4_counter <= $ip_4_end)
						{
							$ip_list[] = "$ip_1_counter.$ip_2_counter.$ip_3_counter.$ip_4_counter";
							$ip_4_counter++;
						}
						$ip_3_counter++;
					}
					$ip_2_counter++;
				}
				$ip_1_counter++;
			}
		}
		elseif (preg_match('/^([\w\-_]\.?){2,}$/is', trim($ip_list_temp[$i])))
		{
			$ip = gethostbynamel(trim($ip_list_temp[$i]));

			for($j = 0; $j < sizeof($ip); $j++)
			{
				if ( !empty($ip[$j]) )
				{
					$ip_list[] = $ip[$j];
				}
			}
		}
		elseif (preg_match('/^([0-9]{1,3})\.([0-9\*]{1,3})\.([0-9\*]{1,3})\.([0-9\*]{1,3})$/', trim($ip_list_temp[$i])))
		{
			// Mighty Gorgon: we don't use this replacement any more...
			//$ip_list[] = str_replace('*', '255', trim($ip_list_temp[$i]));
			$ip_list[] = trim($ip_list_temp[$i]);
		}
	}

	return $ip_list;
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

/**
* Retrieve contents from remotely stored file
*/
function get_remote_file($host, $directory, $filename, &$errstr, &$errno, $port = 80, $timeout = 10)
{
	global $lang;

	if ($fsock = @fsockopen($host, $port, $errno, $errstr, $timeout))
	{
		@fputs($fsock, "GET $directory/$filename HTTP/1.1\r\n");
		@fputs($fsock, "HOST: $host\r\n");
		@fputs($fsock, "Connection: close\r\n\r\n");

		$file_info = '';
		$get_info = false;

		while (!@feof($fsock))
		{
			if ($get_info)
			{
				$file_info .= @fread($fsock, 1024);
			}
			else
			{
				$line = @fgets($fsock, 1024);
				if ($line == "\r\n")
				{
					$get_info = true;
				}
				elseif (stripos($line, '404 not found') !== false)
				{
					$errstr = $lang['FILE_NOT_FOUND'] . ': ' . $filename;
					return false;
				}
			}
		}
		@fclose($fsock);
	}
	else
	{
		if ($errstr)
		{
			$errstr = utf8_convert_message($errstr);
			return false;
		}
		else
		{
			$errstr = $lang['FSOCK_DISABLED'];
			return false;
		}
	}

	return $file_info;
}

/**
* Obtains the latest version information
*
* @param bool $force_update Ignores cached data. Defaults to false.
* @param bool $warn_fail Trigger a warning if obtaining the latest version information fails. Defaults to false.
* @param int $ttl Cache version information for $ttl seconds. Defaults to 86400 (24 hours).
*
* @return string | false Version info on success, false on failure.
*/
function obtain_latest_version_info($force_update = false, $warn_fail = false, $ttl = 86400)
{
	global $cache;

	$info = $cache->get('versioncheck');

	if (($info === false) || $force_update)
	{
		$errstr = '';
		$errno = 0;

		$info = get_remote_file('www.icyphoenix.com', '/version', 'ip2x.txt', $errstr, $errno);

		if ($info === false)
		{
			$cache->destroy('versioncheck');
			if ($warn_fail)
			{
				trigger_error($errstr, E_USER_WARNING);
			}
			return false;
		}

		$cache->put('versioncheck', $info, $ttl);
	}

	return $info;
}

/**
* Return language string value for storage
*/
function prepare_lang_entry($text, $store = true)
{
	$text = (STRIP) ? stripslashes($text) : $text;

	// Adjust for storage...
	if ($store)
	{
		$text = str_replace("'", "\\'", str_replace('\\', '\\\\', $text));
	}

	return $text;
}

/**
* Check if dir exists and eventually creates it
*/
function ip_mkdir($path)
{
	$result = false;
	$result = @is_dir($path);
	if (empty($result))
	{
		$result = @mkdir($path, 0755, true);
		//@chmod($path, 0777);
	}
	return $result;
}

?>