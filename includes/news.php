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
	var $is_topic = false;

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
		global $db, $config, $template;

		$server_protocol = !empty($config['cookie_secure']) ? 'https://' : 'http://';
		$this->root_path = $server_protocol . $config['server_name'] . $config['script_path'];
		$this->root_path_link = IP_ROOT_PATH;
		$this->template = &$template;
		$this->config = &$config;
		$this->name = 'news';
		$this->item_count = 1;

		//$index_file = CMS_PAGE_HOME;
		$index_file = (!empty($_SERVER['SCRIPT_NAME'])) ? $_SERVER['SCRIPT_NAME'] : getenv('SCRIPT_NAME');
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

		$this->data = new NewsDataAccess($root_path);
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
		global $lang, $config, $images, $is_auth, $theme, $user, $block_id, $cms_config_var;

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
			if ($config['display_tags_box'])
			{
				@include_once(IP_ROOT_PATH . 'includes/class_topics_tags.' . PHP_EXT);
				$class_topics_tags = new class_topics_tags();
			}
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

				$dateformat = ($user->data['user_id'] == ANONYMOUS) ? $config['default_dateformat'] : $user->data['user_dateformat'];
				$timezone = ($user->data['user_id'] == ANONYMOUS) ? $config['board_timezone'] : $user->data['user_timezone'];

				//$index_file = CMS_PAGE_HOME;
				$index_file = (!empty($_SERVER['SCRIPT_NAME'])) ? $_SERVER['SCRIPT_NAME'] : getenv('SCRIPT_NAME');
				//$page_query = $_SERVER['QUERY_STRING'];
				//$page_query = (!empty($_SERVER['QUERY_STRING'])) ? explode('&', $_SERVER['QUERY_STRING']) : explode('&', getenv('QUERY_STRING'));
				if($this->config['news_base_url'] != '')
				{
					$index_file = $this->config['news_base_url'] . $index_file;
				}
				$index_file = htmlspecialchars(urldecode($index_file));

				$portal_page_id = request_var('page', 0);
				$portal_page_id = !empty($portal_page_id) ? ('page=' . $portal_page_id . '&amp;') : '';

				$ubid_link = request_var('ubid', 0);
				$ubid_link = !empty($ubid_link) ? ('ubid=' . $ubid_link . '&amp;') : '';

				$format = 'r';
				$gmepoch = $article['post_time'];
				$tz = $timezone;
				$news_dst_sec = get_dst($gmepoch, $tz);
				$news_date = @gmdate($format, $gmepoch + (3600 * $tz) + $news_dst_sec);

				$topic_tags_links = '';
				$topic_tags_display = false;
				if ($config['display_tags_box'])
				{
					$topic_id = $article['topic_id'];
					$topic_tags_links = $class_topics_tags->build_tags_list(array($topic_id));
					$topic_tags_display = !empty($topic_tags_links) ? true : false;
				}

				$topic_label = !empty($article['topic_label_compiled']) ? $article['topic_label_compiled'] : '';
				$topic_title = htmlspecialchars_clean($article['topic_title']);
				$full_topic_title = $topic_label . $topic_title;
				$this->setBlockVariables('articles', array(
					'L_FULL_TITLE' => $full_topic_title,
					'L_TITLE' => $topic_title,
					'ID' => $article['topic_id'],
					'KEY' => (!empty($article['article_key']) ? $article['article_key'] : ''),
					'DAY' => $this->getDay($article['topic_time']),
					'MONTH' => $this->getMonth($article['topic_time']),
					'YEAR' => $this->getYear($article['topic_time']),
					'CATEGORY' => $article['news_category'],
					'CAT_ID' => $article['news_id'],
					'COUNT_VIEWS' => $article['topic_views'],
					'CAT_IMG' => $article['news_image'] ? $this->root_path . $config['news_path'] . '/' . $article['news_image'] : '',
					'POST_DATE' => create_date_ip($dateformat, $article['post_time'], $timezone, true),
					'RFC_POST_DATE' => $news_date,
					'L_POSTER' => colorize_username($article['user_id'], $article['username'], $article['user_color'], $article['user_active']),
					'L_COMMENTS' => $article['topic_replies'],

					'S_TOPIC_TAGS' => $topic_tags_display,
					'TOPIC_TAGS' => $topic_tags_links,

					/*
					'U_COMMENTS' => $this->root_path . CMS_PAGE_VIEWTOPIC . '?' . POST_FORUM_URL . '=' . $article['forum_id'] . '&amp;' . POST_TOPIC_URL . '=' . $article['topic_id'],
					'U_COMMENT' => $this->root_path . CMS_PAGE_VIEWTOPIC . '?' . POST_FORUM_URL . '=' . $article['forum_id'] . '&amp;' . POST_TOPIC_URL . '=' . $article['topic_id'],
					'U_VIEWS' => $this->root_path . 'topic_view_users.' . PHP_EXT . '?' . POST_TOPIC_URL . '=' . $article['topic_id'],
					*/
					'U_COMMENTS' => append_sid(CMS_PAGE_VIEWTOPIC . '?' . POST_FORUM_URL . '=' . $article['forum_id'] . '&amp;' . POST_TOPIC_URL . '=' . $article['topic_id']),
					'U_COMMENT' => append_sid(CMS_PAGE_VIEWTOPIC . '?' . POST_FORUM_URL . '=' . $article['forum_id'] . '&amp;' . POST_TOPIC_URL . '=' . $article['topic_id']),
					'U_VIEWS' => append_sid('topic_view_users.' . PHP_EXT . '?' . POST_TOPIC_URL . '=' . $article['topic_id']),
					'U_POST_COMMENT' => append_sid('posting.' . PHP_EXT . '?mode=reply&amp;' . POST_FORUM_URL . '=' . $article['forum_id'] . '&amp;' . POST_TOPIC_URL . '=' . $article['topic_id']),
					'U_PRINT_TOPIC' => append_sid('printview.' . PHP_EXT . '?' . POST_FORUM_URL . '=' . $article['forum_id'] . '&amp;' . POST_TOPIC_URL . '=' . $article['topic_id'] . '&amp;start=0'),
					'U_EMAIL_TOPIC' => append_sid('tellafriend.' . PHP_EXT . '?topic_title=' . urlencode(ip_utf8_decode($article['topic_title'])) . '&amp;topic_id=' . $article['topic_id']),
					'L_TITLE_HTML' => urlencode(ip_utf8_decode($article['topic_title'])),
					//'TELL_LINK' => urlencode(ip_utf8_decode($server_protocol . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'] . '?topic_id=' . $article['topic_id'])),
					'COUNT_COMMENTS' => $article['topic_replies'],
					'BODY' => ($show_abstract && $trimmed) ? $article['post_abstract'] : $article['post_text'],
					'READ_MORE_LINK' => ($show_abstract && $trimmed) ? '<a href="' . $index_file . '?' . $portal_page_id . $ubid_link . 'topic_id=' . $article['topic_id'] . '">' . $lang['Read_More'] . '</a>' : '',
					)
				);

				if ($show_attachments)
				{
					display_attachments($article['post_id'], 'articles');
				}
				$post_id = $article['post_id'];
			}
		}

		if (sizeof($articles) == 0)
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

		$catid = request_var('cat_id', 0);
		$start = request_var('start', 0);
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
		global $config, $user, $lang;
		$trimmed = false;

		$comments = $this->data->fetchPosts($article_id, $start);
		$this->item_count = $this->data->fetchPostsCount($article_id);

		if(is_array($comments))
		{
			foreach($comments as $comment)
			{
				$comment['post_text'] = $this->parseMessage($comment['post_text'], $comment['enable_bbcode'], $comment['enable_html'], $comment['enable_smilies'], $comment['enable_autolinks_acronyms']);

				$dateformat = ($user->data['user_id'] == ANONYMOUS) ? $config['default_dateformat'] : $user->data['user_dateformat'];
				$timezone = ($user->data['user_id'] == ANONYMOUS) ? $config['board_timezone'] : $user->data['user_timezone'];

				$this->setBlockVariables('comments', array(
					'L_TITLE' => $comment['post_subject'],
					'POST_DATE' => create_date_ip($dateformat, $comment['post_time'], $timezone),
					'L_POSTER' => colorize_username($comment['user_id'], $comment['username'], $comment['user_color'], $comment['user_active']),
					'BODY' => $comment['post_text']
					)
				);
			}
		}
	}

	function renderTopics()
	{
		global $config;
		$categories = $this->data->fetchCategories();

		if(is_array($categories))
		{
			foreach($categories as $category)
			{
				$this->setBlockVariables('categories', array(
					'ID' => $category['news_id'],
					'TITLE' => $category['news_category'],
					'IMAGE' => $category['news_image'] ? $this->root_path . $config['news_path'] . '/' . $category['news_image'] : '',
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
			//'L_MONTH' => $lang['datetime'][gmdate('F', gmmktime(0, 0, 0, $month, 1, $year))],
			// Changed to half month because of problems with timezones...
			'L_MONTH' => $lang['datetime'][gmdate('F', gmmktime(0, 0, 0, $month, 15, $year))],
			'POST_COUNT' => '',
			'MONTH' => $month
			)
		);

		$this->setBlockVariables('arch.year.month.day', array(
			//'L_DAY' => gmdate('j', gmmktime(0, 0, 0, $month, $day, $year)),
			//'L_DAY3' => $lang['datetime'][gmdate('l', gmmktime(0, 0, 0, $month, $day, $year))],
			// Changed to 12 o'clock because of problems with timezones...
			'L_DAY' => gmdate('j', gmmktime(12, 0, 0, $month, $day, $year)),
			'L_DAY3' => $lang['datetime'][gmdate('l', gmmktime(12, 0, 0, $month, $day, $year))],
			'POST_COUNT' => '',
			'DAY' => $day
			)
		);

		$articles = $this->data->fetchDay($day, $month, $year);

		$this->prepareArticles($articles, true, $show_attachments);
	}

	function renderDays($year, $month)
	{
		global $lang;

		global $config, $user;
		$tz = $config['board_timezone'];
		$tm = $config['default_time_mode'];
		$tl = $config['default_dst_time_lag'];
		if ($user->data['session_logged_in'])
		{
			$tz = $user->data['user_timezone'];
			$tm = $user->data['user_time_mode'];
			$tl = $user->data['user_dst_time_lag'];
		}
		switch ($tm)
		{
			case MANUAL_DST:
				$td = ($tl + ($tz * 60)) * 60;
				break;
			case SERVER_SWITCH:
				$td = ((gmdate('I') * $tl) + ($tz * 60)) * 60;
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
		//$last_day = gmdate('d', gmmktime(0, 0, 0, $month + 1, 0, $year));
		// Changed to 12 o'clock because of problems with timezones...
		$last_day = gmdate('d', gmmktime(12, 0, 0, $month + 1, 0, $year));

		//for($d = $last_day; $d >= 1; $d--)
		for($d = 31; $d >= 1; $d--)
		{
			if($days[$d] > 0)
			{
				$this->setBlockVariables('arch.year.month.day', array(
					//'L_DAY' => gmdate('j', gmmktime(0, 0, 0, $month, $d, $year)),
					//'L_DAY2' => $lang['datetime'][gmdate('l', gmmktime(0, 0, 0, $month, $d, $year))],
					// Changed to 12 o'clock because of problems with timezones...
					'L_DAY' => gmdate('j', gmmktime(12, 0, 0, $month, $d, $year)),
					'L_DAY2' => $lang['datetime'][gmdate('l', gmmktime(12, 0, 0, $month, $d, $year))],
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
					//'L_MONTH' => $lang['datetime'][gmdate('F', gmmktime(0, 0, 0, $m, 1, 0))],
					// Changed to half month because of problems with timezones...
					'L_MONTH' => $lang['datetime'][gmdate('F', gmmktime(0, 0, 0, $m, 15, 0))],
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
	* Sets up the Syndication Specific template variables.
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
			'NEWS_TITLE' => $this->config['sitename'],
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
			'PUB_DATE' => gmdate('r', gmmktime(0, 0, 0, gmdate('m'), gmdate('d'), gmdate('y')))
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

		$catid = request_var('cat_id', 0);

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

		$news_var = request_var('news', '');

		if($news_var == 'topics')
		{
			$this->setVariables(array('NEWS_TITLE' => $lang['News_Cmx'] . ' ' . $lang['Categories']));
			$this->renderTopics();
		}
		elseif($news_var == 'archives')
		{
			$year = request_var('year', 0);
			$month = request_var('month', 0);
			$day = request_var('day', 0);
			$key = request_var('key', '');

			$this->setVariables(array('NEWS_TITLE' => $lang['News_Cmx'] . ' ' . $lang['Archives']));
			$this->renderArchives($year, $month, $day, $key);
		}
		else
		{
			$topic_id = request_var('topic_id', 0);
			$news_id = request_var('news_id', 0);
			$topic_id = (empty($topic_id) && !empty($news_id)) ? $news_id : $topic_id;
			$topic_id = ($topic_id < 0) ? 0 : $topic_id;

			if (!empty($topic_id))
			{
				$this->is_topic = true;
			}

			$this->setVariables(array('NEWS_TITLE' => $lang['News_Cmx'] . ' ' . $lang['Articles']));
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

		if ($this->is_topic)
		{
			$news_counter = $this->config['posts_per_page'];
		}

		if($this->item_count > $news_counter)
		{
			//$index_file = CMS_PAGE_HOME;
			$index_file = (!empty($_SERVER['SCRIPT_NAME'])) ? $_SERVER['SCRIPT_NAME'] : getenv('SCRIPT_NAME');
			$base_url = htmlspecialchars(urldecode($index_file)) . '?news=article';

			$topic_id = request_var('topic_id', 0);
			$cat_id = request_var('cat_id', 0);
			$page = request_var('page', 0);
			$ubid = request_var('ubid', 0);

			if(!empty($topic_id))
			{
				$base_url .= '&amp;topic_id=' . $topic_id;
			}
			if(!empty($cat_id))
			{
				$base_url .= '&amp;cat_id=' .$cat_id;
			}
			if(!empty($page))
			{
				$base_url .= '&amp;page=' . $page;
			}
			if(!empty($ubid))
			{
				$base_url .= '&amp;ubid=' . $ubid;
			}

			$start = request_var('start', 0);
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
	* Post based on a delimiter present in the source text.
	*
	* @access public
	*
	* @param string $source The string to be trimmed.
	* @param string $delim The delimiter used to mark the break in text.
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

	function parseMessage($text, $enable_bbcode, $enable_html, $enable_smilies, $enable_autolinks_acronyms)
	{
		global $db, $cache, $config, $user, $bbcode, $lofi;

		if (!class_exists('bbcode')) include(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);
		if (empty($bbcode)) $bbcode = new bbcode();

		if(!empty($text))
		{
			$text = censor_text($text);

			// Parse message and/or sig for BBCode if reqd
			$bbcode->allow_html = (($this->config['allow_html'] == true) && ($enable_html == true)) ? true : false;
			$bbcode->allow_bbcode = (($this->config['allow_bbcode'] == true) && ($enable_bbcode == true)) ? true : false;
			$bbcode->allow_smilies = (($this->config['allow_smilies'] == true) && (!$lofi == true) && ($enable_smilies == true)) ? true : false;
			$text = $bbcode->parse($text);

			if ($enable_autolinks_acronyms)
			{
				$text = $bbcode->acronym_pass($text);
				$text = $bbcode->autolink_text($text, '999999');
			}
		}
		else
		{
			$text = '';
		}

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