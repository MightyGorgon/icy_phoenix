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
* Vjacheslav Trushkin (http://www.stsoftware.biz)
* UseLess
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

// Cached Query Config moved here by MG to reduce charge on common.php
$xs_news_config = array();
$sql = "SELECT * FROM " . XS_NEWS_CONFIG_TABLE;
if(!($result = $db->sql_query($sql, false, 'xs_config_')))
{
	message_die(CRITICAL_ERROR, 'Could not query XS News config information', '', __LINE__, __FILE__, $sql);
}
while ($row = $db->sql_fetchrow($result))
{
	$xs_news_config[$row['config_name']] = $row['config_value'];
}

if($xs_news_config['xs_show_news'] != false)
{
	if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
	/*
	$tempstarttime = $starttime;
		$starttime = $tempstarttime;
	*/
	include_once(IP_ROOT_PATH . 'includes/functions_xs_useless.' . PHP_EXT);

	// Start script

	// Set date format
	switch ($xs_news_config['xs_news_dateformat'])
	{
		case 0:
		$date_format = 'd M Y'; // displays '01 Jan 2005'
		break;

		case 1:
		$date_format = 'M d Y'; // displays 'Jan 01 2005'
		break;

		case 2:
		$date_format = 'd F Y'; // displays '01 January 2005'
		break;

		case 3:
		$date_format = 'F d Y'; // displays 'January 01 2005'
		break;

		case 4:
		$date_format = 'jS M Y'; //displays '1st Jan 2005'
		break;

		case 5:
		$date_format = 'M jS Y'; //displays 'Jan 1st 2005'
		break;

		case 6:
		$date_format = 'jS F Y'; // displays '1st January 2005'
		break;

		case 7:
		$date_format = 'F jS Y'; //displays 'January 1st 2005'
		break;

	}

	// Get contents of News table (cached by MG)
	$sql = "SELECT * FROM " . XS_NEWS_TABLE . "
		ORDER BY news_date DESC";
	if(!$q_news = $db->sql_query($sql, false, 'xs_news_'))
	{
		message_die(GENERAL_ERROR, "Could not query news table", "", __LINE__, __FILE__, $sql);
	}

	while ($test_row = $db->sql_fetchrow($q_news))
	{
		$news_rows[] = $test_row;
	}
	unset($test_row);

	// set template
	$template->set_filenames(array('news' => 'xs_news_banner.tpl'));

	$tick_displayed = 0;
	$news_displayed = 0;

	if($total_news = count($news_rows))
	{

		for($i = 0; $i < $total_news; $i++)
		{
			$news_id = $news_rows[$i]['news_id'];
			$news_date = create_date($date_format, $news_rows[$i]['news_date'], $board_config['board_timezone']);
			$news_text = xsm_unprepare_message($news_rows[$i]['news_text']);
			$news_display = $news_rows[$i]['news_display'];
			$news_smilies = $news_rows[$i]['news_smilies'];

			if($news_display)
			{
				if($news_smilies)
				{
					$news_text = smilies_news($news_text);
				}

				$template->assign_block_vars('newsitem', array(
					'NEWS_ITEM_DATE' => $news_date,
					'NEWS_ITEM' => $news_text
					)
				);

				++$news_displayed;
			}
		}
	}

	if($news_displayed == 0)
	{
		$template->assign_block_vars('newsitem', array(
			'NEWS_ITEM_DATE' => create_date($date_format, time(), $board_config['board_timezone']),
			'NEWS_ITEM' => $lang['xs_no_news']
			)
		);

		++$news_displayed;
	}

	// Should the news subtitle be shown?
	if($xs_news_config['xs_show_news_subtitle'])
	{
		$template->assign_block_vars('switch_news_subtitle', array());
	}

	// Should we show the XS News Ticker?
	if($xs_news_config['xs_show_ticker'])
	{
		$template->assign_block_vars('switch_news_ticker', array());

		if($xs_news_config['xs_show_ticker_subtitle'])
		{
			$template->assign_block_vars('switch_news_ticker.switch_ticker_subtitle', array());
		}

		// Get contents of XML table (cached by MG)
		$sql = "SELECT * FROM " . XS_NEWS_XML_TABLE . " ORDER BY xml_id ASC";
		if(!$q_xml = $db->sql_query($sql, false, 'xs_news_xml_'))
		{
			message_die(GENERAL_ERROR, "Could not query News Ticker table", "", __LINE__, __FILE__, $sql);
		}

		while ($test_row = $db->sql_fetchrow($q_xml))
		{
			$xml_row[] = $test_row;
		}
		unset($test_row);

		if($total_xml = count($xml_row))
		{

			for($i = 0; $i < $total_xml; $i++)
			{
				$xml_id = $xml_row[$i]['xml_id'];
				$xml_title = $xml_row[$i]['xml_title'];
				$xml_show = $xml_row[$i]['xml_show'];
	//			$xml_feed = "http://news.bbc.co.uk/rss/newsonline_uk_edition/front_page/rss091.xml";
				$xml_feed = xsm_unprepare_message($xml_row[$i]['xml_feed']);
				$xml_is_feed = $xml_row[$i]['xml_is_feed'];
				$xml_width = $xml_row[$i]['xml_width'];
				$xml_height = $xml_row[$i]['xml_height'];
				$xml_font = $xml_row[$i]['xml_font'];
				$xml_speed = $xml_row[$i]['xml_speed'];
				$xml_dir = (($xml_row[$i]['xml_direction'] == 0) ? 'left' : 'right');

				if($xml_show)
				{
					if($xml_is_feed)
					{
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

								$news_ticker_content = '';
								$item_count = 0;
								$news_ticker_id = 'news_ticker_' . $i;

								if (isset($rss_channel['items']))
								{
									if (count($rss_channel['items']) > 0)
									{
										$item_count = count($rss_channel['items']);
										for($j = 0; $j < $item_count; $j++)
										{
											$title = htmlspecialchars_clean(ip_utf8_decode(strip_tags($rss_channel['items'][$j]['title'])));
											//$news_ticker_content .= '<a href="' . $rss_channel['items'][$j]["link"] . '" target="_blank" title="' . $rss_channel['items'][$j]["link"] . '\n' . str_replace("'", "\'", $title) . '"  onmouseover="scrollStop(\\\'news_ticker\\\');" onmouseout="scrollStart(\\\'news_ticker\\\');">' . str_replace("'", "\'", $title) . '</a>';
											$news_ticker_content .= '<a href="' . $rss_channel['items'][$j]['link'] . '" target="_blank" title="'. $title .'" onmouseover="document.all.' . $news_ticker_id . '.stop();" onmouseout="document.all.' . $news_ticker_id . '.start();">' . $title . '</a>';

											if($j != (count($rss_channel['items']) - 1))
											{
												$news_ticker_content .= '&nbsp;&nbsp;&raquo;&nbsp;&nbsp;';
											}
										}
									}
									else
									{
										$item_count = 1;
										$news_ticker_content = 'There are no articles in this feed.';
									}
								}

								$rss_channel_title = (empty($rss_channel['title']) ? 'No Source Info Available' : ('<a href="' . $rss_channel['link'] . '" target="_blank">' . htmlspecialchars_clean(ip_utf8_decode(strip_tags($rss_channel['title']))) . '</a>'));
								$template->assign_block_vars('switch_news_ticker.news_ticker_row', array(
									'XS_NEWS_TICKER_FROM' => $rss_channel_title,
									'XS_NEWS_TICKER_ID' => $news_ticker_id,
									'XS_NEWS_TICKER_WIDTH' => $xml_width,
									'XS_NEWS_TICKER_HEIGHT' => $xml_height,
									'XS_NEWS_TICKER_FONTSIZE' => (($xml_font == 0) ? '' : 'style="font-size: ' . intval($xml_font) . 'px;"'),
									'XS_NEWS_TICKER_SPEED' => $xml_speed,
									'XS_NEWS_TICKER_SCROLL_DIR' => $xml_dir,
									'XS_NEWS_TICKER_CONTENTS' => $news_ticker_content,
									'XS_NEWS_TICKER_COLSPAN' => '',
									)
								);

								$template->assign_block_vars('switch_news_ticker.news_ticker_row.switch_show_feed', array());
							}
							else
							{
								$xml_error = true;
								$xml_error_msg = 'Unable to open the XML input';
							}
						}

						if($xml_error || $xml_feed_error)
						{
							$news_ticker_id = 'news_ticker_' . $i;

							$template->assign_block_vars('switch_news_ticker.news_ticker_row', array(
								'XS_NEWS_TICKER_ID' => $news_ticker_id,
								'XS_NEWS_TICKER_WIDTH' => $xml_width,
								'XS_NEWS_TICKER_HEIGHT' => $xml_height,
								'XS_NEWS_TICKER_FONTSIZE' => (($xml_font == 0) ? '' : 'style="font-size: ' . intval($xml_font) . 'px;"'),
								'XS_NEWS_TICKER_SPEED' => $xml_speed,
								'XS_NEWS_TICKER_SCROLL_DIR' => $xml_dir,
								'XS_NEWS_TICKER_CONTENTS' => '<b>' . $xml_error_msg . '</b>: ' . $xml_feed,
								'XS_NEWS_TICKER_COLSPAN' => 'colspan="2"',
								)
							);

							++$tick_displayed;
							continue;
						}
					}
					else
					{
						$news_ticker_id = 'news_ticker_' . $i;
						$xml_feed = str_replace('<a ', '<a  onmouseover="document.all.' . $news_ticker_id . '.stop();" onmouseout="document.all.' . $news_ticker_id . '.start();" ', $xml_feed);

						$template->assign_block_vars('switch_news_ticker.news_ticker_row', array(
							'XS_NEWS_TICKER_ID' => $news_ticker_id,
							'XS_NEWS_TICKER_WIDTH' => $xml_width,
							'XS_NEWS_TICKER_HEIGHT' => $xml_height,
							'XS_NEWS_TICKER_FONTSIZE' => (($xml_font == 0) ? '' : 'style="font-size: ' . intval($xml_font) . 'px;"'),
							'XS_NEWS_TICKER_SPEED' => $xml_speed,
							'XS_NEWS_TICKER_SCROLL_DIR' => $xml_dir,
							'XS_NEWS_TICKER_CONTENTS' => $xml_feed,
							'XS_NEWS_TICKER_COLSPAN' => 'colspan="2"',
							)
						);

					} // end if is_feed?

					++$tick_displayed;
				} // end if xml_show
			} // end for
		}
		else
		{
			// No feeds defined
			$news_ticker_id = 'news_ticker_0';

			$template->assign_block_vars('switch_news_ticker.news_ticker_row', array(
				'XS_NEWS_TICKER_ID' => $news_ticker_id,
				'XS_NEWS_TICKER_WIDTH' => '98%',
				'XS_NEWS_TICKER_HEIGHT' => '20',
				'XS_NEWS_TICKER_FONTSIZE' => '',
				'XS_NEWS_TICKER_SPEED' => '3',
				'XS_NEWS_TICKER_SCROLL_DIR' => 'left',
				'XS_NEWS_TICKER_CONTENTS' => $lang['xs_no_ticker'],
				'XS_NEWS_TICKER_COLSPAN' => 'colspan="2"',
				)
			);
		}
	}

	if(isset($board_config['xs_nav_version']))
	{
		$xml_collapse = "onmouseover=\"showStatus('news_0'); return true;\" onmouseout=\"window.status=' '; return true;\"";
	}
	else
	{
		$xml_collapse = "";
	}

	$template->assign_vars(array(
		'NEWS_TITLE' => $lang['xs_latest_news'],
		'XS_NEWS_VERSION' => sprintf($lang['xs_news_version'], (!empty($board_config['xs_news_version']) ? $board_config['xs_news_version'] : 'ver error')),
		'XS_NEWS_TICKERS_TITLE' => (($tick_displayed == 0 || $tick_displayed == 1) ? $lang['xs_news_ticker_title'] : $lang['xs_news_tickers_title']),
		'XS_NEWS_ITEMS_TITLE' => (($news_displayed == 0 || $news_displayed == 1) ? $lang['xs_news_item_title'] : $lang['xs_news_items_title']),
		'XS_NEWS_COLLAPSE' => $xml_collapse,
		)
	);

	$template->assign_var_from_handle('XS_NEWS', 'news');
}

?>