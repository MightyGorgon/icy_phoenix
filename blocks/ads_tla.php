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

if(!function_exists('cms_block_ads_tla'))
{
	function cms_block_ads_tla()
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
		$CONNECTION_TIMEOUT = 10;

		// Local file to store XML
		// This file MUST be writable by web server
		// You should create a blank file and CHMOD it to 666
		$LOCAL_XML_FILENAME = IP_ROOT_PATH . 'files/' . $xml_filename;

		$ads_content = '';

		if(!file_exists($LOCAL_XML_FILENAME) || !is_writable($LOCAL_XML_FILENAME))
		{
			return $ads_content;
		}

		if((filemtime($LOCAL_XML_FILENAME) < (time() - 3600)) || (filesize($LOCAL_XML_FILENAME) < 20))
		{
			$request_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
			$user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
			tla_updateLocalXML('http://www.text-link-ads.com/xml.php?inventory_key=' . $inventory_key . '&referer=' . urlencode($request_uri) . '&user_agent=' . urlencode($user_agent), $LOCAL_XML_FILENAME, $CONNECTION_TIMEOUT);
		}

		$xml = tla_getLocalXML($LOCAL_XML_FILENAME);

		$arr_xml = tla_decodeXML($xml);

		if (is_array($arr_xml))
		{
			$ads_count = 0;
			$ads_content .= empty($box_type) ? "\n<ul>\n" : '';
			for ($i = 0; $i < count($arr_xml['URL']); $i++)
			{
				if(isset($arr_xml['PostID'][$i]) && ($arr_xml['PostID'][$i] > 0))
				{
					continue;
				}
				$ads_content .= (empty($box_type) ? '<li>' : ($ads_count > 0 ? '&nbsp;&nbsp;&bull;&nbsp;&nbsp;' : '')) . $arr_xml['BeforeText'][$i] . '<a href="' . $arr_xml['URL'][$i] . '">' . $arr_xml['Text'][$i] . '</a> ' . $arr_xml['AfterText'][$i] . (empty($box_type) ? ('</li>' . "\n") : '');
				$ads_count++;
			}
			$ads_content .= empty($box_type) ? "\n</ul>\n" : '';
		}
		$ads_content = empty($box_type) ? $ads_content : ('<div class="gensmall" style="text-align: center;">' . $ads_content . '</div>');

		return $ads_content;
	}

	function tla_updateLocalXML($url, $file, $time_out)
	{
		if($handle = @fopen($file, "a"))
		{
			@fwrite($handle, "\n");
			@fclose($handle);
		}
		if($xml = tla_file_get_contents($url, $time_out))
		{
			$xml = substr($xml, strpos($xml, '<?'));
			if ($handle = @fopen($file, "w"))
			{
				@fwrite($handle, $xml);
				@fclose($handle);
			}
		}
	}

	function tla_getLocalXML($file)
	{
		$contents = "";
		if($handle = @fopen($file, "r"))
		{
			$contents = @fread($handle, @filesize($file) + 1);
			@fclose($handle);
		}
		return $contents;
	}

	function tla_file_get_contents($url, $time_out)
	{
		$result = "";
		$url = parse_url($url);

		if ($handle = @fsockopen($url["host"], 80))
		{
			if(function_exists("socket_set_timeout"))
			{
				socket_set_timeout($handle,$time_out,0);
			}
			elseif(function_exists("stream_set_timeout"))
			{
				stream_set_timeout($handle,$time_out,0);
			}

			@fwrite($handle, "GET $url[path]?$url[query] HTTP/1.0\r\nHost: $url[host]\r\nConnection: Close\r\n\r\n");
			while (!feof($handle))
			{
				$result .= @fread($handle, 40960);
			}
			@fclose($handle);
		}

		return $result;
	}

	function tla_decodeXML($xmlstg)
	{

		if(!function_exists('html_entity_decode'))
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

		$out = "";
		$retarr = "";

		preg_match_all("/<(.*?)>(.*?)</", $xmlstg, $out, PREG_SET_ORDER);
		$search_ar = array('&#60;', '&#62;', '&#34;');
		$replace_ar = array('<', '>', '"');
		$n = 0;
		while (isset($out[$n]))
		{
			$retarr[$out[$n][1]][] = str_replace($search_ar, $replace_ar, html_entity_decode(strip_tags($out[$n][0])));
			$n++;
		}
		return $retarr;
	}
}

cms_block_ads_tla();

?>