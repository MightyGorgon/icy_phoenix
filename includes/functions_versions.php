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
* Check latest Icy Phoenix Version
*/
function get_ip_version()
{
	global $config;

	$cache_update = 86400; // 24 hours cache timeout. change it to whatever you want
	$cache_file = MAIN_CACHE_FOLDER . 'ip_update_' . $config['default_lang'] . $config['ip_version'] . '.' . PHP_EXT; // file where to store cache

	$latest_version_text = '';
	$do_update = true;

	if(@file_exists($cache_file))
	{
		$last_update = 0;
		$version_info = '';
		@include($cache_file);
		if($last_update && !empty($version_info) && ($last_update > (time() - $cache_update)))
		{
			$do_update = false;
		}
		else
		{
			$latest_version_text = '';
		}
	}

	if($do_update)
	{
		// Version cache mod end
		if ($fsock = @fsockopen('www.icyphoenix.com', 80, $errno, $errstr, 15))
		{
			@fwrite($fsock, "GET /version/ip.txt HTTP/1.1\r\n");
			@fwrite($fsock, "HOST: www.icyphoenix.com\r\n");
			@fwrite($fsock, "Connection: close\r\n\r\n");

			$get_info = false;
			while (!@feof($fsock))
			{
				if ($get_info)
				{
					$version_info .= @fread($fsock, 1024);
				}
				else
				{
					if (@fgets($fsock, 1024) == "\r\n")
					{
						$get_info = true;
					}
				}
			}
			@fclose($fsock);

			$version_info = explode("\n", $version_info);
			$latest_head_revision = (int) $version_info[0];
			$latest_minor_revision = (int) $version_info[3];
			$latest_version = (int) $version_info[0] . '.' . (int) $version_info[1] . '.' . (int) $version_info[2] . '.' . (int) $version_info[3];
			$latest_version_text = $version_info[0] . '.' . $version_info[1] . '.' . $version_info[2] . '.' . $version_info[3];
		}
		else
		{
			$latest_version_text = '';
		}

		if(@$f = fopen($cache_file, 'w'))
		{
			fwrite($f, '<' . '?php' . "\n" . '$last_update = ' . time() . ';' . "\n" . '$version_info = \'' . array_map('addslashes', $version_info) . '\';' . "\n" . '$latest_version_text = \'' . addslashes($latest_version_text) . '\';' . "\n" . '?' . '>');
			fclose($f);
			@chmod($cache_file, 0777);
		}
	}

	return $latest_version_text;
}

?>