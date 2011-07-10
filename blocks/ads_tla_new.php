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
* @Extra credits for this file
* masterdavid - Ronald John David
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

if(!function_exists('cms_block_ads_tla_new'))
{
	function cms_block_ads_tla_new()
	{
		global $db, $cache, $config, $template, $images, $userdata, $lang, $block_id, $cms_config_vars;

		$ads_content = tla_ads($cms_config_vars['md_tla_xml_filename'][$block_id], $cms_config_vars['md_tla_inventory_key'][$block_id], $cms_config_vars['md_tla_display'][$block_id]);

		$template->assign_vars(array(
			'ADS_CONTENT' => (empty($ads_content) ? '&nbsp;' : $ads_content),
			)
		);
	}

	function tla_ads($xml_filename, $inventory_key, $box_type)
	{
		// Examples
		/*
		$xml_filename = 'local_222925.xml';
		$inventory_key = 'I3XF0SPPQPJOOFR2WSS9';
		*/

		// Number of seconds before connection to XML times out
		// (This can be left the way it is)
		$CONNECTION_TIMEOUT = 15;

		// Local file to store XML
		// This file MUST be writable by web server
		// You should create a blank file and CHMOD it to 666
		$LOCAL_XML_FILENAME = IP_ROOT_PATH . 'files/' . $xml_filename;

		$ads_content = '';

		if (!file_exists($LOCAL_XML_FILENAME))
		{
			@touch($LOCAL_XML_FILENAME);
			@chmod($LOCAL_XML_FILENAME, 0666);
		}

		if(!file_exists($LOCAL_XML_FILENAME) || !is_writable($LOCAL_XML_FILENAME))
		{
			$ads_content = 'File ' . htmlspecialchars($LOCAL_XML_FILENAME) . ' either doesn\'t exist or isn\'t writable.';
			return $ads_content;
		}

		if ((filemtime($LOCAL_XML_FILENAME) < (time() - 3600)) || (filesize($LOCAL_XML_FILENAME) < 3))
		{
			$url = 'http://www.text-link-ads.com/xml.php?k=' . $inventory_key . '&l=php-tla-2.0.1';
			if (function_exists('json_decode') && is_array(json_decode('{"a":1}', true)))
			{
				$url .= '&f=json';
			}
			tla_updateLocal($url, $LOCAL_XML_FILENAME, $CONNECTION_TIMEOUT);
		}

		if((filemtime($LOCAL_XML_FILENAME) < (time() - 3600)) || (filesize($LOCAL_XML_FILENAME) < 20))
		{
			$request_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
			$user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
			tla_updateLocalXML('http://www.text-link-ads.com/xml.php?inventory_key=' . $inventory_key . '&referer=' . urlencode($request_uri) . '&user_agent=' . urlencode($user_agent), $LOCAL_XML_FILENAME, $CONNECTION_TIMEOUT);
		}

		$xml = tla_getLocal($LOCAL_XML_FILENAME);
		$links = tla_decode($xml);

		if ($links && is_array($links))
		{
			$ads_content .= empty($box_type) ? "\n<ul>\n" : '';

			$ads_content .= empty($box_type) ? "\n</ul>\n" : '';

			echo "\n<ul>\n";
			foreach ($links as $link)
			{
				if(isset($link['PostID']) && ($link['PostID'] > 0))
				{
					continue;
				}

				//echo '<li style="padding: 0; float: left; margin: 0; width: 25%; clear: none; display: block;">' . ($link['BeforeText'] ? $link['BeforeText'] . ' ' : '') . '<a href="' . $link['URL'] . '">' . $link['Text'] . '</a>' . ($link['AfterText'] ? ' ' . $link['AfterText'] : '') . '</li>' . "\n";

				$ads_content .= (empty($box_type) ? '<li>' : ($ads_count > 0 ? '&nbsp;&nbsp;&bull;&nbsp;&nbsp;' : '')) . $link['BeforeText'] . '<a href="' . $link['URL'] . '">' . $link['Text'] . '</a> ' . $link['AfterText'] . (empty($box_type) ? ('</li>' . "\n") : '');
				$ads_count++;

				if (isset($link['PostID']) && $link['PostID'] > 0)
				{
					continue;
				}
			}
			echo '</ul>';
		}

		$ads_content = empty($box_type) ? $ads_content : ('<div class="gensmall" style="text-align: center;">' . $ads_content . '</div>');

		return $ads_content;
	}

	function tla_updateLocal($url, $file, $time_out)
	{
		touch($file);

		if ($xml = file_get_contents_tla($url, $time_out))
		{
			if ($handle = fopen($file, 'w'))
			{
				fwrite($handle, $xml);
				fclose($handle);
			}
		}
	}

	function tla_getLocal($file)
	{
		if (function_exists('file_get_contents'))
		{
			return file_get_contents($file);
		}

		$contents = '';
		if ($handle = fopen($file, 'r'))
		{
			$contents = fread($handle, filesize($file) + 1);
			fclose($handle);
		}

		return $contents;
	}

	function file_get_contents_tla($url, $time_out)
	{
		$result = '';
		$urlInfo = parse_url($url);

		if ($handle = @fsockopen($urlInfo['host'], 80))
		{
			if (function_exists('socket_set_timeout'))
			{
				socket_set_timeout($handle, $time_out, 0);
			}
			elseif (function_exists('stream_set_timeout'))
			{
				stream_set_timeout($handle, $time_out, 0);
			}

			fwrite($handle, 'GET ' . $urlInfo['path'] . '?' . $urlInfo['query'] . " HTTP/1.0\r\nHost: " . $urlInfo['host'] . "\r\nConnection: Close\r\n\r\n");
			while (!feof($handle))
			{
				$result .= @fread($handle, 40960);
			}
			fclose($handle);
		}
		elseif (function_exists('curl_init'))
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $time_out);
			curl_setopt($ch, CURLOPT_TIMEOUT, $time_out);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			$result = curl_exec($ch);
			curl_close($ch);
		}

		$return = '';
		$capture = false;
		foreach (explode("\n", $result) as $line)
		{
			$char = substr(trim($line), 0, 1);
			if ($char == '[' || $char == '<')
			{
				$capture = true;
			}

			if ($capture)
			{
				$return .= $line . "\n";
			}
		}

		return $return;
	}

	function tla_decode($str)
	{
		if (!function_exists('html_entity_decode'))
		{
			function html_entity_decode($string)
			{
				// replace numeric entities
				$string = preg_replace('~&#x([0-9a-f]+);~ei', 'chr(hexdec("\1"))', $string);
				$string = preg_replace('~&#([0-9]+);~e', 'chr(\1)', $string);
				// replace literal entities
				$trans_tbl = get_html_translation_table(HTML_ENTITIES);
				$trans_tbl = array_flip($trans_tbl);
				return strtr($string, $trans_tbl);
			}
		}

		if (substr($str, 0, 1) == '[')
		{
			$arr = json_decode($str, true);
			foreach ($arr as $i => $a)
			{
				foreach ($a as $k => $v)
				{
					$arr[$i][$k] = tla_decode_str($v);
				}
			}

			return $arr;
		}

		$out = '';
		$retarr = '';

		preg_match_all("/<(.*?)>(.*?)</", $str, $out, PREG_SET_ORDER);
		$n = 0;
		while (isset($out[$n]))
		{
			$retarr[$out[$n][1]][] = tla_decode_str($out[$n][0]);
			$n++;
		}

		if (!$retarr)
		{
			return false;
		}

		$arr = array();
		$count = count($retarr['URL']);
		for ($i = 0; $i < $count; $i++)
		{
			$arr[] = array(
				'BeforeText' => $retarr['BeforeText'][$i],
				'URL' => $retarr['URL'][$i],
				'Text' => $retarr['Text'][$i],
				'AfterText' => $retarr['AfterText'][$i],
			);
		}

		return $arr;
	}

	function tla_decode_str($str)
	{
		$search_ar = array('&#60;', '&#62;', '&#34;');
		$replace_ar = array('<', '>', '"');
		return str_replace($search_ar, $replace_ar, html_entity_decode(strip_tags($str)));
	}
}

cms_block_ads_tla_new();

?>