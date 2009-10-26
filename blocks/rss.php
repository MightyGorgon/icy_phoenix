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

if(!function_exists('cms_block_rss'))
{
	function cms_block_rss()
	{
		global $db, $cache, $config, $template, $lang, $block_id, $cms_config_vars;
		global $rss_channel, $currently_writing, $main, $item_counter;

		include_once(IP_ROOT_PATH . 'includes/functions_xs_useless.' . PHP_EXT);

		$xml_id = 'rss_' . $block_id;
		// $xml_feed = "http://news.bbc.co.uk/rss/newsonline_uk_edition/front_page/rss091.xml";
		$xml_feed = xsm_unprepare_message($cms_config_vars['md_rss_feeder'][$block_id]);
		$xml_title = $cms_config_vars['md_rss_title'][$block_id];
		$xml_style = $cms_config_vars['md_rss_style'][$block_id];
		$xml_scroll = $cms_config_vars['md_rss_scroll'][$block_id];
		$xml_speed = '3';

		if ($xml_style)
		{
			$xml_dir = 'left';
		}
		else
		{
			$xml_dir = 'up';
		}

		if ($xml_scroll)
		{
			$xml_marquee_start = '<marquee name="' . $xml_id . '" id="' . $xml_id . '" behavior="scroll" direction="' . $xml_dir . '" scrollamount="' . $xml_speed . '" loop="true" onmouseover="this.stop()" onmouseout="this.start()">';
			$xml_marquee_end = '</marquee>';
			$xml_marquee_append = ' onmouseover="document.all.' . $xml_id . '.stop();" onmouseout="document.all.' . $xml_id . '.start();"';
		}
		else
		{
			$xml_marquee_start = '';
			$xml_marquee_end = '';
			$xml_marquee_append = '';
		}

		$rss_channel = array();
		$currently_writing = '';
		$main = '';
		$item_counter = 0;

		$xml_feed_error = false;
		$xml_error = false;
		$xml_error_msg = '';

		if(empty($xml_feed))
		{
			$xml_feed_error = true;
			$xml_error_msg = 'No XML Feed URL';
		}

		$xml_parser = xml_parser_create();
		xml_set_element_handler($xml_parser, 'startElement', 'endElement');
		xml_set_character_data_handler($xml_parser, 'characterData');

		if (!$xml_feed_error)
		{
			if (($fp = @fopen($xml_feed, 'r')))
			{
				while ($xml_buffer = @fread($fp, 4096))
				{
					if (!xml_parse($xml_parser, $xml_buffer, feof($fp)))
					{
						$xml_error = true;
						$xml_error_msg = sprintf("XML error: %s at line %d", xml_error_string(xml_get_error_code($xml_parser)), xml_get_current_line_number($xml_parser));
					}
				}

				xml_parser_free($xml_parser);

				$rss_ticker_content = '';
				$item_count = 0;

				if (isset($rss_channel['items']))
				{
					if (sizeof($rss_channel['items']) > 0)
					{
						$item_count = sizeof($rss_channel['items']);
						for($j = 0; $j < $item_count; $j++)
						{
							$title = htmlspecialchars_clean(ip_utf8_decode(strip_tags($rss_channel['items'][$j]['title'])));
							$rss_ticker_content .= '&nbsp;&nbsp;&bull;&nbsp;&nbsp;<a href="' . $rss_channel['items'][$j]['link'] . '" target="_blank" title="' . $title . '"' . $xml_marquee_append . '><b>' . $title . '</b></a>';
							if (!$xml_style && !$xml_scroll)
							{
								$rss_ticker_content .= '<br />';
							}
							elseif (!$xml_style)
							{
								$rss_ticker_content .= '<br /><br />';
							}
						}
					}
					else
					{
						$item_count = 1;
						$rss_ticker_content = 'There are no articles in this feed.';
					}
				}

				$rss_channel_title = (empty($rss_channel['title']) ? 'No Source Info Available' : ('<a href="' . $rss_channel['link'] . '" target="_blank">' . htmlspecialchars_clean(ip_utf8_decode(strip_tags($rss_channel['title']))) . '</a>'));
				$xml_title = (!empty($xml_title) ? $xml_title : $rss_channel_title);
				$template->assign_vars(array(
					'RSS_TICKER_ID' => $xml_id,
					'RSS_TICKER_FROM' => $xml_title,
					'RSS_TICKER_CONTENTS' => $xml_marquee_start . $rss_ticker_content . $xml_marquee_end,
					'RSS_TICKER_COLSPAN' => '',
					)
				);
			}
			else
			{
				$xml_error = true;
				$xml_error_msg = 'Unable to open the XML input';
			}
		}

		if($xml_error || $xml_feed_error)
		{
			$template->assign_block_vars(array(
				'RSS_TICKER_ID' => $xml_id,
				'RSS_TICKER_FROM' => $xml_error_msg,
				'RSS_TICKER_CONTENTS' => '<b>' . $xml_error_msg . '</b>: ' . $xml_feed,
				'RSS_TICKER_COLSPAN' => 'colspan="2"',
				)
			);
		}
	}
}

cms_block_rss();

?>