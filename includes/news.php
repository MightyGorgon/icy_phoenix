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
* CodeMonkeyX.net (webmaster@codemonkeyx.net)
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

require_once(IP_ROOT_PATH . 'includes/news_data.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);

/**
 * Class which displays news content.
 */
class NewsModule
{
	/**
	* news data access abstraction object.
	* @var object
	*/
	var $data;

	/**
	* path to phpbb.
	* @var object
	*/
	var $root_path;
	var $root_path_link;

	/**
	* @var object
	*/
	var $template;
	var $config;
	var $name;
	var $item_count;

	/**
	* Class constructor.
	*
	* @param string   (optional) location of the templates directory.
	*
	* @return void
	*
	* @access public
	*/
	function NewsModule($root_path)
	{
		global $db, $template, $board_config;

		$this->root_path = 'http://' . $board_config['server_name'] . $board_config['script_path'];
		$this->root_path_link = IP_ROOT_PATH;
		$this->template = &$template;
		$this->config = &$board_config;
		$this->name = 'news';
		$this->item_count = 1;

		//$index_file = PORTAL_MG;
		$index_file = (!empty($_SERVER['PHP_SELF'])) ? $_SERVER['PHP_SELF'] : getenv('PHP_SELF');
		//$page_query = $_SERVER['QUERY_STRING'];
		//$page_query = (!empty($_SERVER['QUERY_STRING'])) ? explode('&', $_SERVER['QUERY_STRING']) : explode('&', getenv('QUERY_STRING'));
		if($this->config['news_base_url'] != '')
		{
			$index_file = htmlspecialchars(urldecode($this->config['news_base_url'] . $index_file));
		}

		$this->setVariables(array(
			'INDEX_FILE' => $index_file,
			'ROOT_PATH' => $this->root_path
			)
		);

		$this->data =& new NewsDataAccess($root_path);
	}

	/**
	* prepares a list of articles.
	*
	* @param integer (optional) the article id to the article to be displayed.
	*
	* @return void
	*
	* @access private
	*/
	function prepareArticles($articles, $show_abstract = false, $show_attachments = true)
	{
		global $lang, $board_config, $images, $is_auth, $theme, $userdata, $block_id, $cms_config_var;

		if (isset($cms_config_var['md_news_length']))
		{
			$news_trim = $cms_config_var['md_news_length'];
		}
		else
		{
			$news_trim = $this->config['news_item_trim'];
		}

		if(is_array($articles))
		{
			foreach($articles as $article)
			{
				$trimmed = false;

				// Trim the post body if needed.
				if(($show_abstract) && ($news_trim > 0))
				{
					$article['post_abstract'] = $this->trimText($article['post_text'], $news_trim, $trimmed);
					$article['post_abstract'] = $this->parseMessage($article['post_abstract'] . ' ... ', $article['enable_bbcode'], $article['enable_html'], $article['enable_smilies'], $article['enable_autolinks_acronyms']);
				}

				$article['post_text'] = $this->parseMessage($article['post_text'], $article['enable_bbcode'], $article['enable_html'], $article['enable_smilies'], $article['enable_autolinks_acronyms']);

				if ($show_attachments == true)
				{
					init_display_post_attachments($article['topic_attachment'], $article, false, $block_id);
				}

				$sql = '';

				$dateformat = ($userdata['user_id'] == ANONYMOUS) ? $board_config['default_dateformat'] : $userdata['user_dateformat'];
				$timezone = ($userdata['user_id'] == ANONYMOUS) ? $board_config['board_timezone'] : $userdata['user_timezone'];

				$this->setVariables(array(
					'L_REPLIES' => $lang['Replies'],
					'L_REPLY_NEWS' => $lang['News_Reply'],
					'L_PRINT_NEWS' => $lang['News_Print'],
					'L_EMAIL_NEWS' => $lang['News_Email'],
					'MINIPOST_IMG' => $images['icon_minipost'],
					'NEWS_REPLY_IMG' => $images['news_reply'],
					'NEWS_PRINT_IMG' => $images['news_print'],
					'NEWS_EMAIL_IMG' => $images['news_email']
					)
				);

				//$index_file = PORTAL_MG;
				$index_file = (!empty($_SERVER['PHP_SELF'])) ? $_SERVER['PHP_SELF'] : getenv('PHP_SELF');
				//$page_query = $_SERVER['QUERY_STRING'];
				//$page_query = (!empty($_SERVER['QUERY_STRING'])) ? explode('&', $_SERVER['QUERY_STRING']) : explode('&', getenv('QUERY_STRING'));
				if($this->config['news_base_url'] != '')
				{
					$index_file = $this->config['news_base_url'] . $index_file;
				}
				$index_file = htmlspecialchars(urldecode($index_file));

				$portal_page_id = '';
				if(isset($_GET['page']))
				{
					$portal_page_id = 'page=' . htmlspecialchars(intval($_GET['page'])) . '&amp;';
				}

				$ubid_link = '';
				if(isset($_GET['ubid']))
				{
					$ubid_link = 'ubid=' . htmlspecialchars(intval($_GET['ubid'])) . '&amp;';
				}

				// Convert and clean special chars!
				$topic_title = htmlspecialchars_clean($article['topic_title']);
				$this->setBlockVariables('articles', array(
					'L_TITLE' => $topic_title,
					'ID' => $article['topic_id'],
					'KEY' => $article['article_key'],
					'DAY' => $this->getDay($article['topic_time']),
					'MONTH' => $this->getMonth($article['topic_time']),
					'YEAR' => $this->getYear($article['topic_time']),
					'CATEGORY' => $article['news_category'],
					'CAT_ID' => $article['news_id'],
					'COUNT_VIEWS' => $article['topic_views'],
					'CAT_IMG' => $this->root_path . $board_config['news_path'] . '/' . $article['news_image'],
					'POST_DATE' => create_date_simple($dateformat, $article['post_time'], $timezone),
					'RFC_POST_DATE' => create_date_simple('r', $article['post_time'], $timezone),
					'L_POSTER' => colorize_username($article['user_id'], $article['username'], $article['user_color'], $article['user_active']),
					'L_COMMENTS' => $article['topic_replies'],
					/*
					'U_COMMENTS' => $this->root_path . VIEWTOPIC_MG . '?' . POST_FORUM_URL . '=' . $article['forum_id'] . '&amp;' . POST_TOPIC_URL . '=' . $article['topic_id'],
					'U_COMMENT' => $this->root_path . VIEWTOPIC_MG . '?' . POST_FORUM_URL . '=' . $article['forum_id'] . '&amp;' . POST_TOPIC_URL . '=' . $article['topic_id'],
					'U_VIEWS' => $this->root_path . 'topic_view_users.' . PHP_EXT . '?' . POST_TOPIC_URL . '=' . $article['topic_id'],
					*/
					'U_COMMENTS' => append_sid(VIEWTOPIC_MG . '?' . POST_FORUM_URL . '=' . $article['forum_id'] . '&amp;' . POST_TOPIC_URL . '=' . $article['topic_id']),
					'U_COMMENT' => append_sid(VIEWTOPIC_MG . '?' . POST_FORUM_URL . '=' . $article['forum_id'] . '&amp;' . POST_TOPIC_URL . '=' . $article['topic_id']),
					'U_VIEWS' => append_sid('topic_view_users.' . PHP_EXT . '?' . POST_TOPIC_URL . '=' . $article['topic_id']),
					'U_POST_COMMENT' => append_sid('posting.' . PHP_EXT . '?mode=reply&amp;' . POST_FORUM_URL . '=' . $article['forum_id'] . '&amp;' . POST_TOPIC_URL . '=' . $article['topic_id']),
					'U_PRINT_TOPIC' => append_sid('printview.' . PHP_EXT . '?' . POST_FORUM_URL . '=' . $article['forum_id'] . '&amp;' . POST_TOPIC_URL . '=' . $article['topic_id'] . '&amp;start=0'),
					'U_EMAIL_TOPIC' => append_sid('tellafriend.' . PHP_EXT . '?topic_title=' . urlencode(ip_utf8_decode($article['topic_title'])) . '&amp;topic_id=' . $article['topic_id']),
					'L_TITLE_HTML' => urlencode(ip_utf8_decode($article['topic_title'])),
					//'TELL_LINK' => urlencode(ip_utf8_decode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '?topic_id=' . $article['topic_id'])),
					'COUNT_COMMENTS' => $article['topic_replies'],
					'BODY' => ($show_abstract && $trimmed) ? $article['post_abstract'] : $article['post_text'],
					'READ_MORE_LINK' => ($show_abstract && $trimmed) ? '<a href="' . $index_file . '?' . $portal_page_id . $ubid_link . 'topic_id=' . $article['topic_id'] . '">' . $lang['Read_More'] . '</a>' : '',
					)
				);

				if ($show_attachments == true)
				{
					display_attachments($article['post_id'], 'articles');
				}
				$post_id = $article[$i]['post_id'];
			}
		}

		if (count($articles) == 0)
		{
			$this->setBlockVariables('no_articles', array(
				'L_NO_NEWS' => $lang['No_articles']
				)
			);
		}
	}

	/**
	* Fetches articles from the database, and prepares them for display.
	*
	* @param integer (optional) the article id to the article to be displayed.
	*
	* @return void
	*
	* @access private
	*/
	function renderArticles($article_id = 0, $num_items = 0)
	{
		global $cms_config_var;
		$this->item_count = 1;

		$catid = (isset($_GET['cat_id'])) ? intval($_GET['cat_id']) : 0;
		$start = (isset($_GET['start'])) ? intval($_GET['start']) : 0;
		$start = ($start < 0) ? 0 : $start;
		$this->item_count = $this->data->fetchArticlesCount($catid);

		if($article_id <= 0)
		{
			if($num_items > 0)
			{
				$this->data->setItemCount($num_items);
			}
			else
			{
				if (!empty($cms_config_var['md_news_number']))
				{
					$this->data->setItemCount($cms_config_var['md_news_number']);
				}
				else
				{
					$this->data->setItemCount($this->config['news_item_num']);
				}
			}

			$articles = $this->data->fetchArticles(0, $catid, $start);
		}
		else
		{
			$articles = $this->data->fetchArticle($article_id);
			$this->renderComments($article_id, $start);
		}

		$this->prepareArticles($articles, ($article_id <= 0));
	}

	/**
	* Prepares the comments for display.
	*
	* @param integer  the article id to fetch the comments for.
	*
	* @return void
	*
	* @access public
	*/
	function renderComments($article_id, $start = 0)
	{
		global $userdata, $lang, $board_config;
		$trimmed = false;

		$comments = $this->data->fetchPosts($article_id, $start);
		$this->item_count = $this->data->fetchPostsCount($article_id);

		if(is_array($comments))
		{
			foreach($comments as $comment)
			{
				$comment['post_text'] = $this->parseMessage($comment['post_text'], $comment['enable_bbcode'], $comment['enable_html'], $comment['enable_smilies'], $comment['enable_autolinks_acronyms']);

				$dateformat = ($userdata['user_id'] == ANONYMOUS) ? $board_config['default_dateformat'] : $userdata['user_dateformat'];
				$timezone = ($userdata['user_id'] == ANONYMOUS) ? $board_config['board_timezone'] : $userdata['user_timezone'];

				$this->setBlockVariables('comments', array(
					'L_TITLE' => $comment['post_subject'],
					'POST_DATE' => create_date2($dateformat, $comment['post_time'], $timezone),
					'L_POSTER' => colorize_username($comment['user_id'], $comment['username'], $comment['user_color'], $comment['user_active']),
					'BODY' => $comment['post_text']
					)
				);
			}
		}
	}

	function renderTopics()
	{
		global $board_config;
		$categories = $this->data->fetchCategories();

		if(is_array($categories))
		{
			foreach($categories as $category)
			{
				$this->setBlockVariables('categories', array(
					'ID' => $category['news_id'],
					'TITLE' => $category['news_category'],
					'IMAGE' => $this->root_path . $board_config['news_path'] . '/' . $category['news_image'],
					)
				);
			}
		}
	}

	function renderDay($year, $month, $day, $key = '', $show_attachments = true)
	{
		global $lang;

		$this->setBlockVariables('arch.year', array(
			'YEAR' => $year
			)
		);

		$this->setBlockVariables('arch.year.month', array(
			//'L_MONTH' => $lang['datetime'][date('F', gmmktime(0, 0, 0, $month, 1, $year))],
			// Changed to half month because of problems with timezones...
			'L_MONTH' => $lang['datetime'][date('F', gmmktime(0, 0, 0, $month, 15, $year))],
			'POST_COUNT' => '',
			'MONTH' => $month
			)
		);

		$this->setBlockVariables('arch.year.month.day', array(
			//'L_DAY' => date('j', gmmktime(0, 0, 0, $month, $day, $year)),
			//'L_DAY3' => $lang['datetime'][date('l', gmmktime(0, 0, 0, $month, $day, $year))],
			// Changed to 12 o'clock because of problems with timezones...
			'L_DAY' => date('j', gmmktime(12, 0, 0, $month, $day, $year)),
			'L_DAY3' => $lang['datetime'][date('l', gmmktime(12, 0, 0, $month, $day, $year))],
			'POST_COUNT' => '',
			'DAY' => $day
			)
		);

		$articles = $this->data->fetchDay($day, $month, $year, $key);

		$this->prepareArticles($articles, true, $show_attachments);
	}

	function renderDays($year, $month)
	{
		global $lang;

		global $board_config, $userdata;
		$tz = $board_config['board_timezone'];
		$tm = $board_config['default_time_mode'];
		$tl = $board_config['default_dst_time_lag'];
		if ($userdata['session_logged_in'])
		{
			$tz = $userdata['user_timezone'];
			$tm = $userdata['user_time_mode'];
			$tl = $userdata['user_dst_time_lag'];
		}
		switch ($tm)
		{
			case MANUAL_DST:
				$td = ($tl + ($tz * 60)) * 60;
				break;
			case SERVER_SWITCH:
				$td = ((date('I') * $tl) + ($tz * 60)) * 60;
				break;
			default:
				$td = ($tl + ($tz * 60)) * 60;
				break;
		}

		$days = $this->data->fetchDays($month, $year);
		if ($month == 12)
		{
			$month = 0;
		}
		//$last_day = date('d', gmmktime(0, 0, 0, $month + 1, 0, $year));
		// Changed to 12 o'clock because of problems with timezones...
		$last_day = date('d', gmmktime(12, 0, 0, $month + 1, 0, $year));

		//for($d = $last_day; $d >= 1; $d--)
		for($d = 31; $d >= 1; $d--)
		{
			if($days[$d] > 0)
			{
				$this->setBlockVariables('arch.year.month.day', array(
					//'L_DAY' => date('j', gmmktime(0, 0, 0, $month, $d, $year)),
					//'L_DAY2' => $lang['datetime'][date('l', gmmktime(0, 0, 0, $month, $d, $year))],
					// Changed to 12 o'clock because of problems with timezones...
					'L_DAY' => date('j', gmmktime(12, 0, 0, $month, $d, $year)),
					'L_DAY2' => $lang['datetime'][date('l', gmmktime(12, 0, 0, $month, $d, $year))],
					'POST_COUNT' => '(' . $days[$d] . ')',
					'DAY' => $d
					)
				);
			}
		}
	}

	function renderMonths($year, $month = 0)
	{
		global $lang;

		$months = $this->data->fetchMonths($year);

		for($m = 12; $m >= 1; $m--)
		{
			if($months[$m] > 0)
			{
				$this->setBlockVariables('arch.year.month', array(
					//'L_MONTH' => $lang['datetime'][date('F', gmmktime(0, 0, 0, $m, 1, 0))],
					// Changed to half month because of problems with timezones...
					'L_MONTH' => $lang['datetime'][date('F', gmmktime(0, 0, 0, $m, 15, 0))],
					'POST_COUNT' => '(' . $months[$m] . ')',
					'MONTH' => $m
					)
				);
				if(($month > 0) && ($month == $m))
				{
					$this->renderDays($year, $m);
				}
			}
		}
	}

	function renderYears($year = 0, $month = 0)
	{
		$years = $this->data->fetchYears();

		if($years == array())
		{
			return '';
		}

		$render_all = !($year > 0 && $year >= $years['min'] && $year <= $years['max']);

		for($y = $years['max']; $y >= $years['min']; $y--)
		{
			$this->setBlockVariables('arch.year', array(
				'YEAR' => $y
				)
			);

			if($render_all || $year == $y)
			{
				$this->renderMonths($y, $month);
			}
		}
	}

	function renderArchives($year = 0, $month = 0, $day = 0, $key = '', $show_attachments = true)
	{
		$this->setBlockVariables('arch', array('TITLE' => $lang['archives']));

		if(($day > 0) && ($month > 0) && ($year > 0))
		{
			$this->setBlockVariables('arch', array('CLASS' => 'class="genmed"'));
			$this->renderDay($year, $month, $day, $key, $show_attachments);
		}
		else
		{
			$this->setBlockVariables('arch', array());
			$this->renderYears($year, $month);
		}
	}

	/**
	* Sets up the Sydication Specific template variables.
	*
	* @param integer Overides the number of items to be rendered.
	* @return void
	*
	* @access public
	*/
	function renderSyndication($num_items = 0)
	{
		global $lang;
		$encoding = $lang['ENCODING'];
		$sitename = $this->config['sitename'] . ' :: RSS';
		$copyright = $this->config['sitename'] . ' :: ' . gmdate('Y', time());
		$server_url = create_server_url();

		$this->setVariables(array(
			'TITLE' => $this->config['sitename'],
			'URL' => $server_url,
			'FORUM_PATH' => $this->config['script_path'],
			'DESC' => $this->config['news_rss_desc'],
			'LANGUAGE' => $this->config['news_rss_language'],
			'COPYRIGHT' => $copyright,
			'EDITOR' => $this->config['board_email'],
			'WEBMASTER' => $this->config['board_email'],
			'TTL' => $this->config['news_rss_ttl'],
			'CATEGORY' => $this->config['news_rss_cat'],
			'GENERATOR' => $sitename,
			'CONTENT_ENCODING' => $encoding,
			'PUB_DATE' => date('r', gmmktime(0, 0, 0, date('m'), date('d'), date('y')))
			)
		);

		if(($this->config['news_rss_image'] != '') && ($this->config['news_rss_image_desc'] != ''))
		{
			$this->setBlockVariables('image', array(
				'IMAGE' => $this->config['news_rss_image'],
				'IMAGE_TITLE' => $this->config['news_rss_image_desc']
				)
			);
		}

		$this->item_count = 1;

		$catid = (isset($_GET['cat_id'])) ? $_GET['cat_id'] : 0;

		if($num_items > 0)
		{
			$this->data->setItemCount($num_items);
		}
		else
		{
			$this->data->setItemCount($this->config['news_rss_item_count']);
		}

		$articles = $this->data->fetchArticles(0, $catid);

		$this->prepareArticles($articles, $this->config['news_rss_show_abstract'], false);
	}

	/**
	* prepares all the template variables ready for display.
	*
	* @return void
	*
	* @access public
	*/
	function render()
	{
		global $lang;

		// reset the item count.

		$this->setVariables(array(
			'L_INDEX' => $lang['Index'],
			'L_CATEGORIES' => $lang['Categories'],
			'L_ARCHIVES' => $lang['Archives']
			)
		);

		if((isset($_GET['news']) && ($_GET['news'] == 'topics')))
		{
			$this->setVariables(array('TITLE' => $lang['News_Cmx'] . ' ' . $lang['Categories']));
			$this->renderTopics();
		}
		elseif(isset($_GET['news']) && ($_GET['news'] == 'archives'))
		{
			$year = (isset($_GET['year'])) ? $_GET['year'] : 0;
			$month = (isset($_GET['month'])) ? $_GET['month'] : 0;
			$day = (isset($_GET['day'])) ? $_GET['day'] : 0;
			$key = (isset($_GET['key'])) ? $_GET['key'] : '';

			$this->setVariables(array('TITLE' => $lang['News_Cmx'] . ' ' . $lang['Archives']));
			$this->renderArchives($year, $month, $day, $key);
		}
		else
		{
			$topic_id = 0;
			if(isset($_GET['topic_id']))
			{
				$topic_id = $_GET['topic_id'];
			}
			elseif(isset($_GET['news_id']))
			{
				$topic_id = $_GET['news_id'];
			}

			$this->setVariables(array('TITLE' => $lang['News_Cmx'] . ' ' . $lang['Articles']));
			$this->renderArticles($topic_id);
		}

		$this->renderPagination();
	}
	// {{{ trimString()

	function renderPagination()
	{
		global $cms_config_var;

		if (!empty($cms_config_var['md_news_number']))
		{
			$news_counter = $cms_config_var['md_news_number'];
		}
		else
		{
			$news_counter = $this->config['news_item_num'];
		}

		if($this->item_count > $news_counter)
		{
			//$index_file = PORTAL_MG;
			$index_file = (!empty($_SERVER['PHP_SELF'])) ? $_SERVER['PHP_SELF'] : getenv('PHP_SELF');
			$base_url = htmlspecialchars(urldecode($index_file)) . '?news=article';
			if(isset($_GET['topic_id']))
			{
				$base_url .= '&amp;topic_id=' . htmlspecialchars(intval($_GET['topic_id']));
			}
			if(isset($_GET['cat_id']))
			{
				$base_url .= '&amp;cat_id=' . htmlspecialchars(intval($_GET['cat_id']));
			}
			if(isset($_GET['page']))
			{
				$base_url .= '&amp;page=' . htmlspecialchars(intval($_GET['page']));
			}
			if(isset($_GET['ubid']))
			{
				$base_url .= '&amp;ubid=' . htmlspecialchars(intval($_GET['ubid']));
			}

			$start = isset($_GET['start']) ? intval($_GET['start']) : (isset($_POST['start']) ? intval($_POST['start']) : 0);
			$start = ($start < 0) ? 0 : $start;

			$this->setBlockVariables('pagination', array(
				'PAGINATION' => generate_pagination($base_url, $this->item_count, $news_counter, $start)
				)
			);
		}
	}

	/**
	* Trims a given string to the passed length.
	*
	* @access public
	*
	* @param string $source The string to be trimmed..
	* @param integer $length The length the string is to be trimmed to.
	*
	* @return string The resulting trimmed string.
	*/
	function trimString($source, $length)
	{
		$length = intval($length);

		if($length <= 0 || strlen($source) < $length)
		{
			return $source;
		}

		$result = trim($source);  // Remove leading and trailing whitespace.
		$result = strip_tags($result);  // Remove any html or php tags.
		$result = html_entity_decode($result);  // Convert special entities to characters.

		$result = substr($result, 0, $length);

		return htmlspecialchars($result);
	}

	// }}}

	// {{{ trimText()

	/**
	* Post based on a delimeter present in the source text.
	*
	* @access public
	*
	* @param string $source The string to be trimmed.
	* @param string $delim The delimeter used to mark the break in text.
	*
	* @return string The resulting trimmed string.
	*/
	function trimText(&$text, $size, &$trimmed)
	{
		$pos = strpos($text, htmlspecialchars('<!--break-->'));
		if(($pos !== false) && ($pos < strlen($text)))
		{
			$trimmed = true;
			return substr($text, 0, $pos);
		}
		// Breaks up the message by blocks of bbcodes.
		// The message is divided into two parts,
		// 1. text inside a pair of bbcode tags.
		// 2. text not contained inside a pair of bbcode tags.
		$segments = preg_split('#(\[([a-zA-Z]+?).*?\].+?\[/\\2.*?\])#s', $text, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_OFFSET_CAPTURE);

		foreach($segments as $segment)
		{
			if(($segment[1] + strlen($segment[0]) > $size) && ($segment[1] <= $size))
			// $size fall inside the current block.
			{
				$trimmed = true;
				return substr($text, 0, $size);
			}
			elseif($segment[1] > $size)
			// We have gone past the trim point.
			{
				$trimmed = true;
				return substr($text, 0, $segment[1]);
			}
		}
		$trimmed = false;
		return $text;

	}

	// }}}

	// {{{ decodeBBText()

	/**
	* Converts BBCode tags to their html equivelents.
	*
	* @access public
	*
	* @param string $text The body of text to be processed.
	*
	* @return string The resulting decoded text.
	*/
	function decodeBBText($text, $enable_bbcode = true, $enable_html = false, $enable_smilies = true, $enable_autolinks_acronyms = true)
	{
		global $bbcode, $lofi, $userdata;

		if(!isset($text) || (strlen($text) <= 0))
		{
			return;
		}

		//$enable_autolinks_acronyms = true;
		if ($enable_autolinks_acronyms == true)
		{
			// Start Autolinks For phpBB Mod
			$orig_autolink = array();
			$replacement_autolink = array();
			obtain_autolink_list($orig_autolink, $replacement_autolink, 99999999);
			// End Autolinks For phpBB Mod
		}

		// Parse message and/or sig for BBCode if reqd
		$bbcode->allow_html = (($this->config['allow_html'] == true) && ($enable_html == true)) ? true : false;
		$bbcode->allow_bbcode = (($this->config['allow_bbcode'] == true) && ($enable_bbcode == true)) ? true : false;
		$bbcode->allow_smilies = (($this->config['allow_smilies'] == true) && (!$lofi == true) && ($enable_smilies == true)) ? true : false;
		$text = $bbcode->parse($text);

		if (!$userdata['user_allowswearywords'])
		{
			$orig_word = array();
			$replacement_word = array();
			obtain_word_list($orig_word, $replacement_word);
		}
		if ($enable_autolinks_acronyms == true)
		{
			$text = $bbcode->acronym_pass($text);
			if(count($orig_autolink))
			{
				$text = autolink_transform($text, $orig_autolink, $replacement_autolink);
			}
		}
		//$text = kb_word_wrap_pass ($text);
		if (count($orig_word))
		{
			$text = preg_replace($orig_word, $replacement_word, $text);
		}
		return $text;
	}

	// }}

	function parseMessage($text, $enable_bbcode, $enable_html, $enable_smilies, $enable_autolinks_acronyms)
	{
		$text = $this->decodeBBText($text, $enable_bbcode, $enable_html, $enable_smilies, $enable_autolinks_acronyms);

		// Strip out the <!--break--> delimiter.
		$delim = htmlspecialchars('<!--break-->');
		$pos = strpos($text, $delim);
		if(($pos !== false) && ($pos < strlen($text)))
		{
			$text = substr_replace($text, html_entity_decode($delim), $pos, strlen($delim));
		}

		return $text;
	}

	function setVariables($variables)
	{
		$this->template->assign_vars($variables);
	}

	function setBlockVariables($block, $variables)
	{
		$this->template->assign_block_vars($block, $variables);
	}

	function display()
	{
		$this->template->pparse($this->name);
	}

	function clear()
	{
		$this->template->destroy();
	}

	function getYear($timestamp)
	{
		return $this->getDateComp('Y', $timestamp);
	}

	function getMonth($timestamp)
	{
		return $this->getDateComp('m', $timestamp);
	}

	function getDay($timestamp)
	{
		return $this->getDateComp('d', $timestamp);
	}

	function getDateComp($format, $timestamp)
	{
		return gmdate($format, $timestamp);
	}
}

?>