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
* UseLess
*
*/

function xsm_prepare_message($message)
{
	global $config;

	$html_entities_match = array('#&(?!(\#[0-9]+;))#', '#<#', '#>#');
	$html_entities_replace = array('&amp;', '&lt;', '&gt;');

	$allowed_html_tags = split(',', $config['allow_html_tags']);

	// Clean up the message
	$message = trim($message);

	$message = preg_replace($html_entities_match, $html_entities_replace, $message);

	return $message;
}

function xsm_unprepare_message($message)
{
	$unhtml_specialchars_match = array('#&gt;#', '#&lt;#', '#&quot;#', '#&amp;#');
	$unhtml_specialchars_replace = array('>', '<', '"', '&');

	return preg_replace($unhtml_specialchars_match, $unhtml_specialchars_replace, $message);
}

// Borrowed from bbcode.php and putting it here saves having to include the whole file (modified by MG)
function smilies_news($message)
{
	static $orig, $repl;

	if (!isset($orig))
	{
		global $db, $config;
		$orig = $repl = array();

		//$sql = "SELECT * FROM " . SMILIES_TABLE;
		$sql = "SELECT code, smile_url FROM " . SMILIES_TABLE . " ORDER BY smilies_order";
		$result = $db->sql_query($sql, 0, 'smileys_');

		$host = extract_current_hostname();

		$orig = array();
		$repl = array();
		while ($row = $db->sql_fetchrow($result))
		{
			$orig[] = "/(?<=.\W|\W.|^\W)" . phpbb_preg_quote($row['code'], "/") . "(?=.\W|\W.|\W$)/";
			$repl[] = '<img src="http://' . $host . $config['script_path'] . $config['smilies_path'] . '/' . $row['smile_url'] . '" alt="" />';
		}
	}

	if (sizeof($orig))
	{
		$message = preg_replace($orig, $repl, ' ' . $message . ' ');
		$message = substr($message, 1, -1);
	}

	return $message;
}

function smiley_news_sort($a, $b)
{
	if (strlen($a['code']) == strlen($b['code']))
	{
		return 0;
	}

	return (strlen($a['code']) > strlen($b['code'])) ? -1 : 1;
}

//
// Start XML functions
//
// Functions originally written by: Richard James Kendall (richard@richardjameskendall.com)
//
// Modified by UseLess
//
// xml_set_element_handler callback start_element_handler
//
function startElement($parser, $name, $attrs)
{
	global $rss_channel, $currently_writing, $main;

	$name = strtolower($name);

	switch($name)
	{
		case 'rss':
		case 'rdf:rdf':
		case 'items':
			$currently_writing = '';
			break;

		case 'channel':
			$main = 'channel';
			break;

		case 'image':
			$main = 'image';
			$rss_channel['image'] = array();
			break;

		case 'item':
			$main = 'items';
			break;

		default:
			$currently_writing = $name;
			break;
	}
}

// xml_set_element_handler callback end_element_handler
function endElement($parser, $name)
{
	global $rss_channel, $currently_writing, $item_counter;

	$name = strtolower($name);

	$currently_writing = '';
	if ($name == 'item')
	{
		$item_counter++;
	}
}

// Set up the character data handler
function characterData($parser, $data)
{
	global $rss_channel, $currently_writing, $main, $item_counter;

	$main = strtolower($main);

	if ($currently_writing != '')
	{
		switch($main)
		{
			case 'channel':
				if (isset($rss_channel[$currently_writing]))
				{
					$rss_channel[$currently_writing] .= $data;
				}
				else
				{
					$rss_channel[$currently_writing] = $data;
				}
				break;

			case 'image':
				if (isset($rss_channel[$main][$currently_writing]))
				{
					$rss_channel[$main][$currently_writing] .= $data;
				}
				else
				{
					$rss_channel[$main][$currently_writing] = $data;
				}
				break;

			case 'items':
				if (isset($rss_channel[$main][$item_counter][$currently_writing]))
				{
					$rss_channel[$main][$item_counter][$currently_writing] .= $data;
				}
				else
				{
					$rss_channel[$main][$item_counter][$currently_writing] = $data;
				}
				break;
		}
	}
}

?>