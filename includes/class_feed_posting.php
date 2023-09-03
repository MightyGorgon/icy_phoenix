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
* (c) 2009-2010 Jiri Smika (Smix) http://phpbb3.smika.net
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

define('GET_FROM_DB', false);
define('POSTING_DEBUG', true);

define('SMIXMODS_FEED_NEWS_CENTER_FEEDS', $table_prefix . 'smixmods_feed_news_center');
$feeds_array = array(
	'1' => array(
		'feed_name' => 'Icy Phoenix',
		'feed_type' => 'rss',
		'url' => 'http://www.icyphoenix.com/rss.php',
		'encoding' => 'UTF-8',
		'enabled_posting' => '1',
		'enabled_displaying' => '1',
		'template_for_posting' => '',
		'template_for_displaying' => '',
		'poster_id' => 2,
		'poster_forum_destination_id' => 2,
		'poster_topic_destination_id' => 2,
		'refresh_after' => '3600',
		'posting_limit' => '9', // from 1 to 9
		'next_update' => 0, // forces update
	),
);

/*
$download_functions = array('simplexml', 'curl', 'fopen');
$feed_types = array('rss', 'atom', 'rdf');
*/

$config['sfnc_download_function'] = 'simplexml';
$config['sfnc_cron_init'] = '0';
$config['sfnc_cron_posting'] = '1';
$config['sfnc_index_init'] = '0';
$config['sfnc_index_posting'] = '0';

class class_feed_posting
{
	// config
	private $download_function = 'simplexml';

	// from db
	private $feed_id = 0;
	private $feed_type = '';
	private $feed_name = '';
	// (if encoding is not parsed, try UTF-8)
	private $encoding = 'UTF-8';
	private $url = '';
	// time in seconds
	private $refresh_after = 3600;
	private $next_update = 0;
	private $last_update = 0;

	// downloaded data
	private $data = '';
	// parsed data array => feed items / entries ...
	private $items = array();

	// download settings
	private $enabled_posting = 0;
	private $enabled_displaying = 0;
	private $cron_init = false; // forces download
	private $cron_posting = false; // post in cron run
	private $index_init = true; // init on index.php
	private $index_posting = true; // init on index.php

	// some informations
	private $channel_info = array();
	private $available_feed_atributes = array();
	private $available_item_atributes = array();

	// templates
	private $template_for_posting = '';
	private $template_for_displaying = '';

	// posting bot
	private $poster_id = 0; // 2;
	private $poster_forum_destination_id = 0; // 2;
	private $poster_topic_destination_id = 0; // 0;
	private $posting_limit = 3; // 3;

	/**
	* Construct
	*/
	function __construct()
	{

	}

	/**
	* Caches feed items
	*
	* @global cache $cache
	*/
	private function cache_store_feed()
	{
		global $cache;

		$cache->_write('smixmods_feed_' . md5($this->url), $this->items, time());

		// update latest_update info
		$this->feed_updated();
	}

	/**
	* Loads cached feed items
	*
	* @global cache $cache
	* @return array
	*/
	private function cache_load_feed()
	{
		global $cache;

		return $cache->_read('smixmods_feed_' . md5($this->url));
	}

	/**
	* Adds feed index into array of indexes, if not already added
	*
	* @param string $index
	*/
	private function check_feed_atributes($index)
	{
		$available_attributes = ($this->available_feed_atributes) ? array_flip($this->available_feed_atributes) : array();

		if (!isset($available_attributes[$index]))
		{
			$this->available_feed_atributes[] = $index;
		}
	}

	/**
	* Adds item index into array of indexes, if not already added
	*
	* @param string $index
	*/
	private function check_item_atributes($index)
	{
		$available_attributes = ($this->available_item_atributes) ? array_flip($this->available_item_atributes) : array();

		if (!isset($available_attributes[$index]))
		{
			$this->available_item_atributes[] = $index;
		}
	}

	/**
	* Gets data from URL
	*
	* @return xml
	*/
	private function get_file()
	{
		if ($this->download_function == 'simplexml')
		{
			return simplexml_load_file($this->url, 'SimpleXMLElement', LIBXML_NOCDATA); //
		}
		// note - this is probably not longer required ...
		elseif ($this->download_function == 'curl')
		{
			$content = $this->get_file_curl($this->url);
			return $content = simplexml_load_string($content['content']);
		}
		else
		{
			$content = $this->get_file_fopen($this->url);
			return $content = simplexml_load_string($content['content']);
		}
	}

	/**
	* Gets remote file using cURL function
	*
	* @param string $url
	* @return string
	*/
	private function get_file_curl($url)
	{
		// initiate and set options
		$ch = @curl_init($url);
		@curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
		@curl_setopt( $ch, CURLOPT_HEADER, 0);
		@curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);

		//@curl_setopt( $ch, CURLOPT_ENCODING, '');
		@curl_setopt( $ch, CURLOPT_USERAGENT, 'SmiX.MODs_feed_center');
		// initial connection timeout

		@curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 5);
		// setting this to higher means longer time for loading the page for user!
		@curl_setopt( $ch, CURLOPT_TIMEOUT, 60);
		@curl_setopt( $ch, CURLOPT_MAXREDIRS, 0);

		// get content
		$content['content'] = @curl_exec($ch);
		$content['errno'] = @curl_errno($ch);
		$content['errmsg'] = @curl_error($ch);
		$content['getinfo'] = @curl_getinfo($ch);
		@curl_close($ch);

		return $content;
	}

	/**
	* Gets remote file using fopen fopen
	*
	* @param string $url
	* @return string
	*/
	private function get_file_fopen($url)
	{
		$content['content'] = '';

		// else use fopen if possible
		if ($f = @fopen($url, 'r'))
		{
			while (!feof($f))
			{
				@$content['content'] .= fgets($f, 4096);
			}
			fclose($f);
		}

		return $content;
	}

	/**
	* HTML to BBCode replacement
	*
	* @param string $string
	* @return string
	*/
	private function html_to_bbcode($string)
	{
		$htmltags = array(
			"/\<b\>(.*?)\<\/b\>/is",
			"/\<i\>(.*?)\<\/i\>/is",
			"/\<u\>(.*?)\<\/u\>/is",
			"/\<ul\>(.*?)\<\/ul\>/is",
			"/\<li\>(.*?)\<\/li\>/is",
			"/\<img(.*?) src=\"(.*?)\" (.*?)\>/is",
			"/\<div\>(.*?)\<\/div\>/is",
			"/\<br(.*?)\>/is",
			"/\<strong\>(.*?)\<\/strong\>/is",
			"/\<a href=\"(.*?)\"(.*?)\>(.*?)\<\/a\>/is",

		);

		// Replace with
		$bbtags = array(
			"[b]$1[/b]",
			"[i]$1[/i]",
			"[u]$1[/u]",
			"[list]$1[/list]",
			"[*]$1",
			"[img]$2[/img]",
			"$1",
			"\n",
			"[b]$1[/b]",
			"[url=$1]$3[/url]",
		);

		// Replace $htmltags in $text with $bbtags
		$string = preg_replace($htmltags, $bbtags, $string);

		// Strip all other HTML tags
		$string = strip_tags($string);

		return $string;
	}

	/**
	* Is downloaded feed in RSS format?
	*
	* @param xml object $xml
	* @return bool
	*/
	private function is_rss()
	{
		return ($this->data->channel->item) ? true : false;
	}

	/**
	* Main parsing function for RSS format
	*/
	private function parse_rss()
	{
		// list all channel tags, which are available
		foreach ($this->data->channel as $k => $v)
		{
			foreach ($v as $attribute => $attribute_value)
			{
				$this->check_feed_atributes($attribute);
			}
		}

		$i = 0;
		// list all item tags, which are available
		foreach ($this->data->channel->item as $item)
		{
			foreach ($item as $k => $v)
			{
				$this->items[$i][utf8_recode($k, $this->encoding)] = (string)utf8_recode($v, $this->encoding);
				$this->check_item_atributes($k);
			}
			$i++;
		}
	}

	/**
	* Is downloaded feed in RDF format?
	*
	* @param xml object $xml
	* @return bool
	*/
	private function is_rdf()
	{
		return ($this->data->item) ? true : false;
	}

	/**
	* Main parsing function for RDF format
	*/
	private function parse_rdf()
	{
		// list all channel tags, which are available
		foreach ($this->data->channel as $k => $v)
		{
			foreach ($v as $at => $av)
			{
				$this->check_feed_atributes($at);
			}
		}

		$i = 0;
		// list all item tags, which are available
		foreach ($this->data->item as $item)
		{
			foreach ($item as $k => $v)
			{
				$this->items[$i][utf8_recode($k, $this->encoding)] = (string)utf8_recode($v, $this->encoding);
				$this->check_item_atributes($k);
			}
			$i++;
		}
	}

	/**
	* Is downloaded feed in ATOM format?
	*
	* @param xml object $xml
	* @return bool
	*/
	private function is_atom()
	{
		return ($this->data->entry) ? true : false;
	}

	/**
	* Main parsing function for ATOM format
	*/
	private function parse_atom()
	{
		// get root
		$root = $this->data->children('http://www.w3.org/2005/Atom');

		// get feed data
		foreach ($root as $ak => $av)
		{
			if ($ak != 'entry')
			{
				$this->check_feed_atributes($ak);
			}
		}

		// do we have some data ?
		if (isset($root->entry))
		{
			$i = 0;
			foreach ($root->entry as $entry)
			{
				$details = $entry->children('http://www.w3.org/2005/Atom');

				foreach ($details as $k => $v)
				{
					$this->items[$i][utf8_recode($k, $this->encoding)] = (string)utf8_recode($v, $this->encoding);
					$this->check_item_atributes($k);
				}
				$i++;
			}
		}
	}

	/**
	* Prepare feed items for later use
	*
	* @param integer $id feed_id
	*/
	private function populate($id)
	{
		// get cached data
		if ($this->cron_init || ($this->index_init && ($this->next_update < time())))
		{
			// this feed will be actually checked and updated,
			// don´t wait until it ends,to prevent multiple loading of the same ...
			$this->feed_checked();

			$this->data = $this->get_file($this->url);

			// switch parsing by data type
			if ($this->data)
			{
				if ($this->is_atom() || $this->feed_type == 'atom')
				{
					$this->feed_type = 'atom';
					$this->parse_atom();
				}
				elseif ($this->is_rss() || $this->feed_type == 'rss')
				{
					$this->feed_type = 'rss';
					$this->parse_rss();
				}
				elseif ($this->is_rdf() || $this->feed_type == 'rdf')
				{
					$this->feed_type = 'rdf';
					$this->parse_rdf();
				}
				else
				{
					// TODO add lang entry to lang file
					$this->add_log('Unable to detext feed type: ' . $this->name);
				}

				// if download was successful
				if (!empty($this->items))
				{
					$this->cache_store_feed();
					$this->autosave_settings();
				}
			}
			else
			{
				// TODO add lang entry to lang file
				$this->add_log('No data downloaded from the feed ' . $this->name);
			}
		}
		else
		{
			// load data from cache
			$this->items = $this->cache_load_feed();
		}
	}

	/**
	* Main configuration for the parser
	*/
	private function setup()
	{
		global $config;

		$this->download_function = $config['sfnc_download_function'];
		$this->cron_init = $config['sfnc_cron_init'];
		$this->cron_posting = $config['sfnc_cron_posting'];
		$this->index_init = $config['sfnc_index_init'];
		$this->index_posting = $config['sfnc_index_posting'];
	}

	private function reset_feed()
	{
		// from db
		$this->feed_id = 0;
		$this->feed_type = '';
		$this->feed_name = '';
		// if encoding is unknown, try UTF-8 instead
		$this->encoding = 'UTF-8';
		$this->url = '';

		// setting
		$this->enabled_posting = 0;
		$this->enabled_displaying = 0;

		// downloaded data
		$this->data = '';
		// parsed data array => feed items / entries ...
		$this->items = array();

		// download settings
		// time in seconds
		$this->refresh_after = 3600;
		$this->next_update = 0;
		$this->last_update = 0;

		// some informations
		$this->channel_info = array();
		$this->available_feeed_atributes = array();
		$this->available_item_atributes = array();

		// templates
		$this->template_for_posting = '';
		$this->template_for_displaying = '';

		// posting bot
		$this->poster_id = 0; // 2;
		$this->poster_forum_destination_id = 0; // 2;
		$this->poster_topic_destination_id = 0; // 2;
		$this->posting_limit = 1; // 1
	}

	/**
	* Changes latest feed check time
	*
	* @global db $db
	*/
	private function feed_checked()
	{
		global $db;

		if (GET_FROM_DB)
		{
			$sql = 'UPDATE ' . SMIXMODS_FEED_NEWS_CENTER_FEEDS . '
					SET next_update = ' . (time() + $this->refresh_after) . '
					WHERE id = ' . (int) $this->feed_id;

			$db->sql_query($sql);
		}
	}

	/**
	* Changes latest update time
	*
	* @global db $db
	*/
	private function feed_updated()
	{
		global $db;

		if (GET_FROM_DB)
		{
			$sql = 'UPDATE ' . SMIXMODS_FEED_NEWS_CENTER_FEEDS . '
					SET last_update = ' . (time() + 5). '
					WHERE id = ' . (int) $this->feed_id;

			$db->sql_query($sql);
		}
	}

	/**
	* Saves feed_type, encoding and available parsed atributes
	*
	* @global db $db
	*/
	private function autosave_settings()
	{
		global $db;

		if (GET_FROM_DB)
		{
			$sql = 'UPDATE ' . SMIXMODS_FEED_NEWS_CENTER_FEEDS . '
					SET feed_type = "' . strtolower($this->feed_type) . '",
						encoding = "' . strtolower($this->encoding) . '",
						available_feed_atributes = "' . implode(',', $this->available_feed_atributes) . '",
						available_item_atributes = "' . implode(',', $this->available_item_atributes) . '"
					WHERE id = ' . (int) $this->feed_id;

			$db->sql_query($sql);
		}
	}

	/**
	* Sets settings for selected feed
	* @global global $db
	* @param integer $feed_id
	*/
	private function setup_feed($feed_id)
	{
		global $db;

		// reset possible previous feed settings
		$this->reset_feed();

		$this->feed_id = (int) $feed_id;

		if (GET_FROM_DB)
		{
			// parser setup
			$sql = 'SELECT url, feed_name, feed_type, encoding,
						next_update, last_update, refresh_after,
						template_for_displaying, template_for_posting,
						poster_id, poster_forum_destination_id, poster_topic_destination_id, posting_limit,
						available_feed_atributes, available_item_atributes,
						enabled_posting, enabled_displaying
					FROM ' . SMIXMODS_FEED_NEWS_CENTER_FEEDS . '
					WHERE id = ' . $this->feed_id;

			$result = $db->sql_query($sql);
			$feed_data = array();
			$feed_data = $db->sql_fetchrow($result);
		}
		else
		{
			global $feeds_array;
			$feed_data = $feeds_array[$feed_id];
		}


		if ($feed_data)
		{
			// SETTINGS for specified feed
			foreach ($feed_data as $k => $v)
			{
				// override default only if value is available
				if ($v)
				{
					$this->$k = $v;
				}
			}

			// split values are from db ...
			if (!is_array($this->available_feed_atributes))
			{
				$this->available_feed_atributes = explode(',', $this->available_feed_atributes);
			}
			if (!is_array($this->available_item_atributes))
			{
				$this->available_item_atributes = explode(',', $this->available_item_atributes);
			}

			// get data from the feed and prepare it for later use if wanted
			if ($this->enabled_posting || $this->enabled_displaying)
			{
				$this->populate($this->feed_id);
			}
		}
	}

	// POSTING BOT MOD part [+]
	public function setup_posting($id)
	{
		$this->setup_feed($id);

		// post only if posting is enabled
		if (!empty($this->items) && $this->enabled_posting)
		{
			$this->init_posting();
		}
	}

	private function init_posting()
	{
		global $db, $config, $user, $lang;

		include(IP_ROOT_PATH . 'includes/functions_post.' . PHP_EXT);

		$sql = 'SELECT *
				FROM ' . USERS_TABLE . '
				WHERE user_id = ' . (int) $this->poster_id;

		$result = $db->sql_query($sql);
		$poster_data = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);

		// backward posting (from the oldest to the newest)
		$i = (sizeof($this->items) > $this->posting_limit) ? $this->posting_limit - 1 : sizeof($this->items);
		$j = 0;
		while ($i >= 0 && (($this->posting_limit == 0) || ($this->posting_limit > $j)))
		{
			$subject = substr($this->items[$i]['title'], 0, 254);

			// check if this topic is not already posted
			$sql = 'SELECT topic_title
					FROM ' . TOPICS_TABLE . '
					WHERE topic_title = "' . $db->sql_escape($subject) . '"
						AND topic_poster = ' . (int) $this->poster_id;
			$result = $db->sql_query($sql);
			$row = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);

			// Do we have a new item to post ?
			if (strnatcasecmp($row['topic_title'], $subject))
			{
				// templates RSS / ATOM has different indexes for messages
				$temp = (($this->feed_type == 'rss') || ($this->feed_type == 'rdf'))? 'description' : 'content';
				$message = $this->html_to_bbcode($this->feed_name . "\n\n" . $this->items[$i][$temp]);
				$post_time = time();

				// Icy Phoenix Posting - BEGIN
				// Force the user to be admin to avoid flood check...
				$user->data['user_level'] = ADMIN;
				$topic_title_clean = substr(ip_clean_string($subject, $lang['ENCODING']), 0, 254);
				$forum_id = $this->poster_forum_destination_id;
				$topic_id = 0;
				$post_id = 0;
				$post_mode = 'newtopic';
				$post_data = array();
				$poll_data = array(
					'title' => '',
					'start' => time(),
					'length' => 0,
					'max_options' => 1,
					'change' => 0
				);

				if (POSTING_DEBUG)
				{
					die($subject . '<br /><br />' . $message);
				}
				else
				{
					prepare_post($post_mode, $post_data, 1, 0, 0, '', $poster_data['username'], $subject, $message, '', array(), $poll_data, '', '', '', '', '', '', '', 0, 0);
					submit_post($post_mode, $post_data, '', '', $forum_id, $topic_id, $post_id, $topic_type, 1, 0, 1, 0, 1, $poster_data['username'], $subject, $topic_title_clean, '', $message, '', '', $poll_data, '', '', '', '', '', '', 0, 0, false, '', 0, 0);
				}
				// Icy Phoenix Posting - END
			}
			// change $i to the next (ehm previous :D ) item
			$i--;
			$j++;
		}

		// TODO rebuild/sync forums latest topics and post counts

		// redirect to index
		if (!$this->cron_init)
		{
			redirect(create_server_url());
		}

	}
	// POSTING BOT MOD [-]

	public function index_init()
	{
		global $db;

		$this->setup();

		// initiated on index.php
		// update feed, only if .MOD is not set to run in cron mode
		if (!$this->cron_init)
		{
			if (GET_FROM_DB)
			{
				$sql = 'SELECT id
						FROM ' . SMIXMODS_FEED_NEWS_CENTER_FEEDS . '
						WHERE next_update < ' . time() . '
							AND (enabled_posting = 1) OR (enabled_displaying = 1)
						LIMIT 0,1';
				$result = $db->sql_query($sql);
				$row = $db->sql_fetchrow($result);
				$id = $row['id'];
				$db->sql_freeresult($result);

				if ($id)
				{
					if ($this->index_posting)
					{
						$this->setup_posting($id);
					}
					else
					{
						$this->setup_feed($id);
					}
				}
			}
			else
			{
				global $feeds_array;
				foreach ($feeds_array as $k => $v)
				{
					$this->setup_posting($k);
				}
			}
		}
	}

	/**
	* Updates all feeds
	*/
	public function cron_init()
	{
		global $db;

		$this->setup();

		// forces download
		$this->cron_init = true;

		if (GET_FROM_DB)
		{
			$sql = 'SELECT id
					FROM ' . SMIXMODS_FEED_NEWS_CENTER_FEEDS . '
					WHERE (enabled_posting = 1) OR (enabled_displaying = 1)';

			$result = $db->sql_query($sql);

			$ids = array();

			while ($row = $db->sql_fetchrow($result))
			{
				$ids[] = $row['id'];
			}

			$db->sql_freeresult($result);
		}
		else
		{
			global $feeds_array;
			$ids = array();
			foreach ($feeds_array as $k => $v)
			{
				$ids[] = $k;
			}
		}

		if ($ids)
		{
			foreach ($ids as $id)
			{
				if ($this->cron_posting)
				{
					$this->setup_posting($id);
				}
				else
				{
					$this->setup_feed($id);
				}
			}
		}
	}

	public function add_log($message)
	{
		global $config;

		$datecode = gmdate('Ymd');
		$logs_path = !empty($config['logs_path']) ? $config['logs_path'] : 'logs';
		$log_file = IP_ROOT_PATH . $logs_path . '/rss_feeder_log_' . $datecode . '.txt';
		$fp = @fopen ($log_file, "a+");
		$message = gmdate('Ymd') . ': ' . $message . "\n";
		@fwrite($fp, $message);
		@fclose($fp);
	}

}
?>