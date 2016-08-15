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

define('IN_SEARCH', true);
// Added to optimize memory for attachments
define('ATTACH_DISPLAY', true);
define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_search.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_topics.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_calendar.' . PHP_EXT);
// Event Registration - BEGIN
include_once(IP_ROOT_PATH . 'includes/functions_events_reg.' . PHP_EXT);
// Event Registration - END

@include_once(IP_ROOT_PATH . 'includes/class_topics.' . PHP_EXT);
$class_topics = new class_topics();

// Adding CPL_NAV only if needed
define('PARSE_CPL_NAV', true);

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup();
// End session management

$search_id = request_var('search_id', '');
$search_mode = request_var('search_mode', '');
$search_mode = !empty($search_mode) ? $search_mode : $search_id;

if (($search_id != 'unanswered') && !$user->data['session_logged_in'] && $config['gsearch_guests'])
{
	$google_q = request_var('search_keywords', '', true);
	$google_sitesearch = preg_replace('#^\/?(.*?)\/?$#', '\1', trim($config['server_name']));
	$google_cof = 'FORID:9';
	//$google_ie = 'ISO-8859-1';
	$google_ie = 'UTF-8';
	$google_url_append = '?q=' . urlencode($google_q) . '&sitesearch=' . $google_sitesearch . '&cof=' . $google_cof . '&ie=' . $google_ie;
	redirect(append_sid('gsearch.' . PHP_EXT . (!empty($google_q) ? $google_url_append : ''), true));
}

// CrackerTracker v5.x
if (check_http_var_exists('mode', false) || check_http_var_exists('search_id', false) || check_http_var_exists('show_results', false) || isset($_GET['search_keywords']))
{
	include_once(IP_ROOT_PATH . 'includes/ctracker/classes/class_ct_userfunctions.' . PHP_EXT);
	$search_system = new ct_userfunctions();
	$search_system->search_handler();
	unset($search_system);
}
// CrackerTracker v5.x

// SELF AUTH
// MG Added for an indepth auth check and SELF posts - BEGIN
$is_auth_ary = array();
$is_auth_ary = auth(AUTH_ALL, AUTH_LIST_ALL, $user->data);
// MG Added for an indepth auth check and SELF posts - END

// UPI2DB - BEGIN
if($user->data['upi2db_access'])
{
	$params = array(
		POST_FORUM_URL => POST_FORUM_URL,
		POST_TOPIC_URL => POST_TOPIC_URL,
		POST_POST_URL => POST_POST_URL,
		'mar' => 'mar',
	);
	while(list($var, $param) = @each($params))
	{
		$$var = request_var($param, 0);
	}

	$params = array(
		'always_read' => 'always_read',
		's2' => 's2',
		'do' => 'do',
		'tt' => 'tt'
	);
	while(list($var, $param) = @each($params))
	{
		$$var = request_var($param, '');
	}

	$mar_topic_id = request_var('mar_topic_id', array(0));

	if (!defined('UPI2DB_UNREAD'))
	{
		$user->data['upi2db_unread'] = upi2db_unread();
	}

	if($always_read || $do || ($mar && !empty($mar_topic_id)))
	{
		if($always_read)
		{
			$mark_read_text = always_read($t, $always_read, $user->data['upi2db_unread']);
		}
		if($do)
		{
			$mark_read_text = set_unread($t, $f, $p, $user->data['upi2db_unread'], $do, $tt);
		}
		if($mar && !empty($mar_topic_id))
		{
			search_mark_as_read($mar_topic_id);
			$mark_read_text = $lang['upi2db_submit_topic_mark_read'];
		}

		$redirect_url = append_sid(CMS_PAGE_SEARCH . '?search_id=' . $search_id . (isset($s2) ? ('&amp;s2=' . $s2) : ''));
		meta_refresh(3, $redirect_url);

		$message = $mark_read_text . '<br /><br />' . sprintf($lang['Click_return_search'], '<a href="' . append_sid(CMS_PAGE_SEARCH . '?search_id=' . $search_id . (isset($s2) ? ('&amp;s2=' . $s2) : '')) . '">', '</a>');
		message_die(GENERAL_MESSAGE, $message);
	}
	$count_new_posts = sizeof($user->data['upi2db_unread']['new_posts']);
	$count_edit_posts = sizeof($user->data['upi2db_unread']['edit_posts']);
	$count_always_read = sizeof($user->data['upi2db_unread']['always_read']['topics']);
	$count_mark_unread = sizeof($user->data['upi2db_unread']['mark_posts']);
}
// UPI2DB - END

$cms_page['page_id'] = 'search';
$cms_page['page_nav'] = (!empty($cms_config_layouts[$cms_page['page_id']]['page_nav']) ? true : false);
$cms_page['global_blocks'] = (!empty($cms_config_layouts[$cms_page['page_id']]['global_blocks']) ? true : false);
$cms_auth_level = (isset($cms_config_layouts[$cms_page['page_id']]['view']) ? $cms_config_layouts[$cms_page['page_id']]['view'] : AUTH_ALL);
check_page_auth($cms_page['page_id'], $cms_auth_level);

$mode = request_var('mode', '');
$only_bluecards = (!empty($_POST['only_bluecards']) ? 1 : 0);
$search_keywords = request_var('search_keywords', '', true);
$is_newposts = false;
$search_author = request_var('search_author', '', true);

if (!empty($search_author))
{
	$search_author = phpbb_clean_username($search_author);
	$search_topic_starter = check_http_var_exists('search_topic_starter', true);
}
else
{
	$search_author = '';
	$search_topic_starter = false;
}

if (isset($search_mode) && ($search_mode == 'bookmarks'))
{
	// TO DO: force to false, and decide if we would like to overwrite it with Profile Global Blocks settings...
	//$cms_page['global_blocks'] = (!empty($cms_config_layouts['profile']['global_blocks']) ? true : false);
	$cms_page['global_blocks'] = false;
}

$search_terms = request_var('search_terms', '');
$search_terms = ($search_terms == 'all') ? 1 : 0;

$search_fields_types = array('all', 'titleonly', 'msgonly');
$search_fields = request_var('search_fields', '');
$search_fields = check_var_value($search_fields, $search_fields_types);

$search_cat = request_var('search_cat', -1);
$search_forum = request_var('search_forum', -1);

$search_thanks = request_var('search_thanks', 0);
$search_thanks = (($search_thanks >= '2') && empty($config['disable_likes_posts'])) ? $search_thanks : false;

$search_where = request_post_var('search_where', 'Root');
$search_where_topic = request_post_var('search_where_topic', 'Root');
$search_where_topic = (!empty($search_where_topic) ? (str_replace(POST_TOPIC_URL, '', $search_where_topic)) : false);
$search_where_topic = !empty($search_where_topic) ? intval($search_where_topic) : false;
$search_where_topic = ($search_where_topic > 0) ? $search_where_topic : false;

$sort_by = request_var('sort_by', 0);

$sort_dir = request_var('sort_dir', 'DESC');
$sort_dir = check_var_value($sort_dir, array('DESC', 'ASC'));

$psort_types = array('time', 'cat');
$psort = request_var('psort', 'time');
$psort = check_var_value($psort, $psort_types);

$topic_days = request_var('search_time', 0);
if (!empty($topic_days))
{
	$search_time = time() - ($topic_days * 86400);
}
else
{
	$search_time = 0;
	$topic_days = 0;
}

$search_date = request_var('d', 0);

$show_results = request_var('show_results', 'posts');
$show_results = check_var_value($show_results, array('posts', 'topics'));

// $sr is used to allow users to override the default result displaying for new posts
$sr_cn = $config['cookie_name'] . '_sr';
if(isset($_GET['sr']))
{
	$sr_get = (isset($_GET['sr']) && ($_GET['sr'] == 't')) ? 't' : 'p';
	$user->set_cookie('sr', $sr_get, $user->cookie_expire);
	$_COOKIE[$sr_cn] = $sr_get;
}

$sr_cookie = (isset($_COOKIE[$sr_cn]) && ($_COOKIE[$sr_cn] == 't')) ? 't' : 'p';
$sr = $sr_cookie;

$return_chars = request_var('return_chars', 200);
$return_chars = ($return_chars >= -1) ? $return_chars : 200;
// MG: if the users chooses to show no chars from posts, then we force topics view.
$show_results = ($return_chars == 0) ? 'topics' : $show_results;

$is_ajax = request_var('is_ajax', 0);

$start = request_var('start', 0);
$start = ($start < 0) ? 0 : $start;

$page_number = request_var('page_number', 0);
$page_number = ($page_number < 1) ? 0 : $page_number;

$start = (empty($page_number) ? $start : (($page_number * $config['topics_per_page']) - $config['topics_per_page']));

$sort_by_types = array($lang['Sort_Time'], $lang['Sort_Post_Subject'], $lang['Sort_Topic_Title'], $lang['Sort_Author'], $lang['Sort_Forum']);

// Start Advanced IP Tools Pack MOD
// For security reasons, we need to make sure the IP lookup is coming from an admin or mod.
$search_ip = '';
$ip_display_auth = ip_display_auth($user->data, false);
if (!empty($ip_display_auth))
{
	$ip_address = request_var('search_ip', '');
	if (!empty($ip_address))
	{
		$ip_address = $db->sql_escape($ip_address);
		$search_ip = str_replace('*', '%', $ip_address);
	}
	else
	{
		$search_ip = '';
	}
}
// End Advanced IP Tools Pack MOD

// encoding match for workaround
$multibyte_charset = 'utf-8, big5, shift_jis, euc-kr, gb2312';

// Begin core code
if (($search_mode == 'bookmarks') && !$user->data['session_logged_in'])
{
	redirect(append_sid(CMS_PAGE_LOGIN . '?redirect=' . CMS_PAGE_SEARCH . '?search_id=bookmarks&amp;search_mode=bookmarks', true));
}

if (($search_mode == 'bookmarks') && ($mode == 'removebm'))
{
	// Delete Bookmarks
	$delete = (isset($_POST['delete'])) ? true : false;
	if ($delete && isset($_POST['topic_id_list']))
	{
		$topics = request_post_var('topic_id_list', array(0));
		$topic_list = implode(',', $topics);
		if ($user->data['session_logged_in'])
		{
			remove_bookmark($topic_list);
			redirect(append_sid(CMS_PAGE_SEARCH . '?search_id=bookmarks&amp;search_mode=bookmarks' . (!empty($start) ? ('&amp;start=' . $start) : ''), true));
		}
	}
	// Reset settings
	$mode = '';
}

if ($mode == 'searchuser')
{
	// This handles the simple windowed user search functions called from various other scripts
	$search_username = request_var('search_username', '', true);
	$search_username = htmlspecialchars_decode($search_username, ENT_COMPAT);
	username_search($search_username);
	exit;
}
elseif (($search_keywords != '') || ($search_author != '') || $search_id || ($search_ip != '') || ($search_thanks != false))
{
	$store_vars = array('search_results', 'total_match_count', 'split_search', 'sort_by', 'psort', 'sort_dir', 'show_results', 'return_chars');
	$search_results = '';

	// Search ID Limiter, decrease this value if you experience further timeout problems with searching forums
	$limiter = 5000;
	$current_time = time();

	// Cycle through options ...
	$search_id_filter_array = array('newposts', 'upi2db', 'egosearch', 'unanswered', 'bookmarks', 'mini_cal', 'mini_cal_events');
	if (in_array($search_id, $search_id_filter_array) || ($search_keywords != '') || ($search_author != '') || ($search_ip != '') || ($search_thanks != false))
	{
		if (($search_id == 'newposts') || ($search_id == 'upi2db') || ($search_id == 'egosearch') || ($search_id == 'mini_cal') || ($search_id == 'mini_cal_events') || (($search_author != '') && ($search_keywords == '')) || ($search_ip != ''))
		{
			if (($search_id == 'newposts') || ($search_id == 'upi2db'))
			{
// UPI2DB REPLACE -------------------------------------------------
/*
				if ($user->data['session_logged_in'])
				{
					#$sql = "SELECT post_id
						#FROM " . POSTS_TABLE . "
						#WHERE post_time >= " . $user->data['user_lastvisit'];
				}
				else
				{
					redirect(append_sid(CMS_PAGE_LOGIN . '?redirect=' . CMS_PAGE_SEARCH . '&search_id=newposts', true));
				}

				$show_results = 'topics';
*/
// -------------------------------------------------
				if ($user->data['session_logged_in'])
				{
// UPI2DB - BEGIN
					if(!$user->data['upi2db_access'] || ($search_id == 'newposts'))
					{
						$sql = "SELECT post_id
							FROM " . POSTS_TABLE . "
							WHERE post_time >= " . $user->data['user_lastvisit'] . "
							AND poster_id != " . $user->data['user_id'];
					}
					else
					{
						if($search_id == 'upi2db')
						{
							switch($s2)
							{
								case 'perm':
								$sql_where = (sizeof($user->data['upi2db_unread']['always_read']['topics']) == 0) ? 0 : implode(',', $user->data['upi2db_unread']['always_read']['topics']);
								break;

								case 'new':
								$sql_where = (sizeof($user->data['upi2db_unread']['new_posts']) == 0) ? 0 : implode(',', $user->data['upi2db_unread']['new_posts']);
								$sql_where2 = (sizeof($user->data['upi2db_unread']['edit_posts']) == 0) ? 0 : implode(',', $user->data['upi2db_unread']['edit_posts']);
								break;

								case 'mark':
								$sql_where = (sizeof($user->data['upi2db_unread']['mark_posts']) == 0) ? 0 : implode(',', $user->data['upi2db_unread']['mark_posts']);
								$sql_where2 = 0;
								break;
							}

							if(($search_id == 'upi2db') && ($s2 == 'perm'))
							{
								$sql = "SELECT post_id
									FROM " . POSTS_TABLE . "
									WHERE topic_id IN (" . $sql_where . ")";
							}
							if(($search_id == 'upi2db') && ($s2 != 'perm'))
							{
								$sql = "SELECT post_id
									FROM " . POSTS_TABLE . "
									WHERE (post_id IN (" . $sql_where . ") OR post_id IN (" . $sql_where2 . "))";
							}
							if(empty($sql_where) && empty($sql_where2))
							{
								redirect(append_sid(CMS_PAGE_FORUM));
							}
						}
					}
// UPI2DB - END
				}
				else
				{
					redirect(append_sid(CMS_PAGE_LOGIN . '?redirect=' . CMS_PAGE_SEARCH . '&search_id=newposts', true));
				}
// UPI2DB - BEGIN
				if($search_id == 'newposts')
				{
					$is_newposts = true;
				}
				if((($search_id == 'newposts') && ($sr != 't')) || (($search_id == 'upi2db') && ($s2 == 'mark')))
				{
					$show_results = 'posts';
				}
				else
				{
					$show_results = 'topics';
				}
// UPI2DB - END
				$sort_by = 0;
				$sort_dir = 'DESC';
			}
			// Start Advanced IP Tools Pack MOD
			elseif ($search_ip != '')
			{
				$sql = "SELECT post_id FROM " . POSTS_TABLE . " WHERE poster_ip LIKE '$search_ip'";
				$show_results = 'posts';
				$sort_by = 0;
				$sort_dir = 'DESC';
			}
			//End Advanced IP Tools Pack MOD
			elseif ($search_cat != -1)
			{
				$sql = "SELECT post_id FROM " . POSTS_TABLE . " p, " . FORUMS_TABLE . " f WHERE p.forum_id = f.forum_id AND f.parent_id = $search_cat";
				$show_results = 'posts';
				$sort_by = 0;
				$sort_dir = 'DESC';
			}
			elseif ($search_forum != -1)
			{
				$sql = "SELECT post_id FROM " . POSTS_TABLE . " WHERE forum_id = $search_forum";
				$show_results = 'posts';
				$sort_by = 0;
				$sort_dir = 'DESC';
			}
			elseif ($search_id == 'egosearch')
			{
				if ($user->data['session_logged_in'])
				{
					$sql = "SELECT post_id
						FROM " . POSTS_TABLE . "
						WHERE poster_id = " . $user->data['user_id'];
				}
				else
				{
					redirect(append_sid(CMS_PAGE_LOGIN . '?redirect=' . CMS_PAGE_SEARCH . '&search_id=egosearch', true));
				}

				$show_results = 'topics';
				$sort_by = 0;
				$sort_dir = 'DESC';
			}
			elseif ($is_ajax)
			{
				$result_ar = array(
					'search_id' => 0,
					'results' => 0,
					'keywords' => ''
				);
				AJAX_message_die($result_ar);
			}
			elseif ((MINI_CAL_CALENDAR_VERSION != 'NONE') && ($search_id == 'mini_cal'))
			{
				$nix_tomorrow = gmmktime (0, 0, 0, gmdate('m', $search_date), gmdate('d', $search_date) + 1, gmdate('Y', $search_date));
				$sql = "SELECT post_id
						FROM " . POSTS_TABLE . "
						WHERE post_time >= $search_date
						AND post_time < $nix_tomorrow";

				$show_results = 'posts';
				$sort_by = 0;
				$sort_dir = 'DESC';
			}
			elseif ((MINI_CAL_CALENDAR_VERSION != 'NONE') && ($search_id == 'mini_cal_events'))
			{
			// include the required events calendar support
				define('IN_MINI_CAL', 1);
				include_once(IP_ROOT_PATH . 'includes/mini_cal/mini_cal_config.' . PHP_EXT);
				$mini_cal_inc = 'mini_cal_' . MINI_CAL_CALENDAR_VERSION;
				include_once(IP_ROOT_PATH . 'includes/mini_cal/' . $mini_cal_inc . '.' . PHP_EXT);
				$sql = getMiniCalSearchSql($search_date);

				$show_results = 'posts';
				$sort_by = 0;
				$sort_dir = 'DESC';
			}
			else
			{
				//$search_author = str_replace('*', '%', trim($db->sql_escape($search_author)));
				$search_author = str_replace('*', '%', trim($db->sql_escape(utf8_clean_string($search_author))));
				if(!$only_bluecards && (strpos($search_author, '%') !== false) && (strlen(str_replace('%', '', $search_author)) < $config['search_min_chars']))
				{
					$search_author = '';
					message_die(GENERAL_MESSAGE, sprintf($lang['SEARCH_MIN_CHARS'], $config['search_min_chars']));
				}

				$sql = get_users_sql($search_author, true, false, false, false);
				$result = $db->sql_query($sql);

				$matching_userids = '';
				if ($row = $db->sql_fetchrow($result))
				{
					do
					{
						$matching_userids .= (($matching_userids != '') ? ', ' : '') . $row['user_id'];
					}
					while($row = $db->sql_fetchrow($result));
				}
				else
				{
					message_die(GENERAL_MESSAGE, $lang['No_search_match']);
				}

				/*
				$sql = "SELECT post_id
					FROM " . POSTS_TABLE . "
					WHERE poster_id IN ($matching_userids)";
					$sql .= ($only_bluecards) ? " AND post_bluecard > 0 " : "";
				if ($search_time)
				{
					$sql .= " AND post_time >= " . $search_time;
				}
				*/

				$sql_from_ts = '';
				$sql_where_ts = '';
				if ($search_topic_starter)
				{
					$sql_from_ts = ", " . TOPICS_TABLE . " t";
					$sql_where_ts = " AND p.post_id = t.topic_first_post_id";
				}
				$sql = "SELECT p.post_id
					FROM " . POSTS_TABLE . " p" . $sql_from_ts . "
					WHERE p.poster_id IN (" . $matching_userids . ")" . $sql_where_ts;
					$sql .= ($only_bluecards) ? " AND p.post_bluecard > 0 " : "";
				if ($search_time)
				{
					$sql .= " AND p.post_time >= " . $search_time;
				}
			}
			//die($sql);
			$result = $db->sql_query($sql);

			$search_ids = array();
			while($row = $db->sql_fetchrow($result))
			{
				$search_ids[] = $row['post_id'];
			}
			$db->sql_freeresult($result);

			$total_match_count = sizeof($search_ids);

		}
		elseif ($search_keywords != '')
		{

			stopwords_synonyms_init();

			$split_search = array();
			$stripped_keywords = stripslashes($search_keywords);
			$split_search = (!strstr($multibyte_charset, $lang['ENCODING'])) ? split_words(clean_words('search', $stripped_keywords, $stopwords_array, $synonyms_array), 'search') : split(' ', $search_keywords);
			unset($stripped_keywords);

			$word_count = 0;
			$current_match_type = 'or';

			$word_match = array();
			$result_list = array();

			for($i = 0; $i < sizeof($split_search); $i++)
			{
				if (!$only_bluecards && strlen(str_replace(array('*', '%'), '', trim($split_search[$i]))) < $config['search_min_chars'])
				{
					$split_search[$i] = '';
					continue;
				}
				switch ($split_search[$i])
				{
					case 'and':
						$current_match_type = 'and';
						break;

					case 'or':
						$current_match_type = 'or';
						break;

					case 'not':
						$current_match_type = 'not';
						break;

					default:
						if (!empty($search_terms))
						{
							$current_match_type = 'and';
						}

						if (!strstr($multibyte_charset, $lang['ENCODING']))
						{
							$match_word = str_replace('*', '%', $split_search[$i]);
							$search_add_sql = '';
							if (($search_fields == 'msgonly') || ($search_fields == 'titleonly'))
							{
								$search_add_sql = 'AND m.title_match = ' . (($search_fields == 'msgonly') ? '0' : '1');
							}
							$search_add_sql .= ($only_bluecards) ? " AND p.post_bluecard > 0 AND m.post_id = p.post_id " : '';
							$sql = "SELECT m.post_id
								FROM " . SEARCH_WORD_TABLE . " w, " . SEARCH_MATCH_TABLE . " m " . (($only_bluecards) ? ','.POSTS_TABLE . ' p ' : '') . "
								WHERE w.word_text LIKE '" . $db->sql_escape($match_word) . "'
									AND m.word_id = w.word_id
									AND w.word_common <> 1
									" . $search_add_sql;
						}
						else
						{
							$match_word = addslashes('%' . str_replace('*', '', $split_search[$i]) . '%');
							$search_add_sql = '';
							if ($search_fields == 'msgonly')
							{
								$search_add_sql = "p.post_text LIKE '" . $db->sql_escape($match_word) . "'";
							}
							elseif ($search_fields == 'titleonly')
							{
								$search_add_sql = "p.post_subject LIKE '" . $db->sql_escape($match_word) . "'";
							}
							else
							{
								$search_add_sql = "p.post_text LIKE '" . $db->sql_escape($match_word) . "' OR p.post_subject LIKE '" . $db->sql_escape($match_word) . "'";
							}
							$search_add_sql .= ($only_bluecards) ? " AND p.post_bluecard > 0" : '';
							$sql = "SELECT p.post_id
								FROM " . POSTS_TABLE . " p
								WHERE " . $search_add_sql;
						}
						$result = $db->sql_query($sql);

						$row = array();
						while($temp_row = $db->sql_fetchrow($result))
						{
							$row[$temp_row['post_id']] = 1;

							if (!$word_count)
							{
								$result_list[$temp_row['post_id']] = 1;
							}
							elseif ($current_match_type == 'or')
							{
								$result_list[$temp_row['post_id']] = 1;
							}
							elseif ($current_match_type == 'not')
							{
								$result_list[$temp_row['post_id']] = 0;
							}
						}

						if ($current_match_type == 'and' && $word_count)
						{
							@reset($result_list);
							while(list($post_id, $match_count) = @each($result_list))
							{
								if (!$row[$post_id])
								{
									$result_list[$post_id] = 0;
								}
							}
						}

						$word_count++;

						$db->sql_freeresult($result);
				}
			}

			@reset($result_list);

			$search_ids = array();
			while(list($post_id, $matches) = each($result_list))
			{
				if ($matches)
				{
					$search_ids[] = $post_id;
				}
			}

			unset($result_list);
			$total_match_count = sizeof($search_ids);
		}

		//
		// If user is logged in then we'll check to see which (if any) private
		// forums they are allowed to view and include them in the search.
		//
		// If not logged in we explicitly prevent searching of private forums
		//
		$auth_sql = '';
		// get the object list
		$keys = array();
		$keys = get_auth_keys($search_where, true, -1, -1, 'auth_read');
		$keys = get_auth_keys($search_where, true);
		$s_flist = '';
		for ($i = 0; $i < sizeof($keys['id']); $i++)
		{
			if (isset($tree['type'][$keys['idx'][$i]]) && ($tree['type'][$keys['idx'][$i]] == POST_FORUM_URL) && isset($tree['auth'][$keys['id'][$i]]['auth_read']) && $tree['auth'][$keys['id'][$i]]['auth_read'])
			{
				$s_flist .= (($s_flist != '') ? ', ' : '') . $tree['id'][$keys['idx'][$i]];
			}
		}

		if ($s_flist != '')
		{
			$auth_sql .= (($auth_sql != '') ? " AND" : '') . " f.forum_id IN ($s_flist) ";
			$auth_sql .= ($search_where_topic ? (" AND p.topic_id = " . $search_where_topic) : '');
		}
		else
		{
			message_die(GENERAL_MESSAGE, $lang['No_searchable_forums']);
		}

		// Author name search
		if ($search_author != '')
		{
			if (preg_match('#^[\*%]+$#', trim($search_author)) || preg_match('#^[^\*]{1,2}$#', str_replace(array('*', '%'), '', trim($search_author))))
			{
				$search_author = '';
			}
			$search_author = str_replace('*', '%', trim($db->sql_escape($search_author)));
		}

		if ($total_match_count)
		{
			if ($show_results == 'topics')
			{
				// This one is a beast, try to seperate it a bit (workaround for connection timeouts)
				$search_id_chunks = array();
				$count = 0;
				$chunk = 0;

				if (sizeof($search_ids) > $limiter)
				{
					for ($i = 0; $i < sizeof($search_ids); $i++)
					{
						if ($count == $limiter)
						{
							$chunk++;
							$count = 0;
						}

						$search_id_chunks[$chunk][$count] = $search_ids[$i];
						$count++;
					}
				}
				else
				{
					$search_id_chunks[0] = $search_ids;
				}

				$search_ids = array();

				for ($i = 0; $i < sizeof($search_id_chunks); $i++)
				{
					$where_sql = '';

					if ($search_time)
					{
						$where_sql .= (($search_author == '') && ($auth_sql == '')) ? " AND post_time >= $search_time " : " AND p.post_time >= $search_time ";
					}

					if (($search_author == '') && ($auth_sql == ''))
					{
						$sql = "SELECT topic_id
							FROM " . POSTS_TABLE . "
							WHERE post_id IN (" . implode(", ", $search_id_chunks[$i]) . ")
							$where_sql
							GROUP BY topic_id";
					}
					else
					{
						$from_sql = POSTS_TABLE . " p";

						if ($search_author != '')
						{
							if ($search_topic_starter)
							{
								$from_sql .= ", " . USERS_TABLE . " u, " . TOPICS_TABLE . " t";
								$where_sql .= " AND u.user_id = p.poster_id AND LOWER(u.username) LIKE '" . strtolower($search_author) . "' AND p.post_id = t.topic_first_post_id ";
							}
							else
							{
								$from_sql .= ", " . USERS_TABLE . " u";
								$where_sql .= " AND u.user_id = p.poster_id AND LOWER(u.username) LIKE '" . strtolower($search_author) . "' ";
							}
						}

						if ($auth_sql != '')
						{
							$from_sql .= ", " . FORUMS_TABLE . " f";
							$where_sql .= " AND f.forum_id = p.forum_id AND $auth_sql";
						}

						$sql = "SELECT p.topic_id
							FROM $from_sql
							WHERE p.post_id IN (" . implode(", ", $search_id_chunks[$i]) . ")
								$where_sql
							GROUP BY p.topic_id";
					}
					//die($sql);
					$result = $db->sql_query($sql);

					while ($row = $db->sql_fetchrow($result))
					{
						$search_ids[] = $row['topic_id'];
					}
					$db->sql_freeresult($result);
				}

				$total_match_count = sizeof($search_ids);

			}
			elseif (($search_author != '') || $search_time || ($auth_sql != ''))
			{
				$search_id_chunks = array();
				$count = 0;
				$chunk = 0;

				if (sizeof($search_ids) > $limiter)
				{
					for ($i = 0; $i < sizeof($search_ids); $i++)
					{
						if ($count == $limiter)
						{
							$chunk++;
							$count = 0;
						}

						$search_id_chunks[$chunk][$count] = $search_ids[$i];
						$count++;
					}
				}
				else
				{
					$search_id_chunks[0] = $search_ids;
				}

				$search_ids = array();

				for ($i = 0; $i < sizeof($search_id_chunks); $i++)
				{
					$where_sql = ($search_author == '' && $auth_sql == '') ? 'post_id IN (' . implode(', ', $search_id_chunks[$i]) . ')' : 'p.post_id IN (' . implode(', ', $search_id_chunks[$i]) . ')';
					$select_sql = ($search_author == '' && $auth_sql == '') ? 'post_id' : 'p.post_id';
					$from_sql = ($search_author == '' && $auth_sql == '') ? POSTS_TABLE : POSTS_TABLE . ' p';

					if ($search_time)
					{
						$where_sql .= ($search_author == '' && $auth_sql == '') ? " AND post_time >= $search_time " : " AND p.post_time >= $search_time";
					}

					if ($auth_sql != '')
					{
						$from_sql .= ", " . FORUMS_TABLE . " f";
						$where_sql .= " AND f.forum_id = p.forum_id AND $auth_sql";
					}

					if ($search_author != '')
					{
						$from_sql .= ", " . USERS_TABLE . " u";
						$where_sql .= " AND u.user_id = p.poster_id AND LOWER(u.username) LIKE '" . strtolower($search_author) . "'";
					}

					$sql = "SELECT " . $select_sql . "
						FROM $from_sql
						WHERE $where_sql";
					$result = $db->sql_query($sql);

					while($row = $db->sql_fetchrow($result))
					{
						$search_ids[] = $row['post_id'];
					}
					$db->sql_freeresult($result);
				}

				$total_match_count = sizeof($search_ids);
			}
		}
		elseif ($search_thanks != false)
		{
			if ($user->data['session_logged_in'])
			{
				if ($auth_sql != '')
				{
					$sql = "SELECT DISTINCT(t.topic_id), f.forum_id
									FROM " . POSTS_LIKES_TABLE . " th, " . TOPICS_TABLE . " t, " . FORUMS_TABLE . " f
									WHERE t.topic_poster = '" . $search_thanks . "'
										AND t.topic_id = th.topic_id
										AND t.forum_id = f.forum_id
										AND th.post_id = t.topic_first_post_id
										AND $auth_sql";
				}
				else
				{
					$sql = "SELECT DISTINCT(t.topic_id)
									FROM " . POSTS_LIKES_TABLE . " th, " . TOPICS_TABLE . " t
									WHERE t.topic_poster = '" . $search_thanks . "'
										AND t.topic_id = th.topic_id
										AND th.post_id = t.topic_first_post_id";
				}
			}
			else
			{
				redirect(append_sid(CMS_PAGE_LOGIN . '?redirect=' . CMS_PAGE_SEARCH . '&search_thanks=' . $search_thanks, true));
			}
			$result = $db->sql_query($sql);

			$search_ids = array();
			while($row = $db->sql_fetchrow($result))
			{
				$search_ids[] = $row['topic_id'];
			}
			$db->sql_freeresult($result);

			$total_match_count = sizeof($search_ids);
			if ($total_match_count <= $start) // No results for the selected page
			{
				$start = $total_match_count - 1;
				$start = intval($start / $config['topics_per_page']) * $config['topics_per_page'];
			}

			$show_results = 'topics';
			$sort_by = 0;
			$sort_dir = 'DESC';
		}
		elseif ($search_id == 'unanswered')
		{
			if ($auth_sql != '')
			{
				$sql = "SELECT t.topic_id, f.forum_id
					FROM " . TOPICS_TABLE . " t, " . FORUMS_TABLE . " f
					WHERE t.topic_replies = 0
						AND t.forum_id = f.forum_id
						AND t.topic_moved_id = 0
						AND $auth_sql";
			}
			else
			{
				$sql = "SELECT topic_id
					FROM " . TOPICS_TABLE . "
					WHERE topic_replies = 0
						AND topic_moved_id = 0";
			}
			$result = $db->sql_query($sql);

			$search_ids = array();
			while($row = $db->sql_fetchrow($result))
			{
				$search_ids[] = $row['topic_id'];
			}
			$db->sql_freeresult($result);

			$total_match_count = sizeof($search_ids);

			// Basic requirements
			$show_results = 'topics';
			$sort_by = 0;
			$sort_dir = 'DESC';
		}
		elseif ($search_id == 'bookmarks')
		{
			if ($user->data['session_logged_in'])
			{
				if ($auth_sql != '')
				{
					$sql = "SELECT t.topic_id, f.forum_id
						FROM " . TOPICS_TABLE . " t, " . BOOKMARK_TABLE . " b, " . FORUMS_TABLE . " f
						WHERE t.topic_id = b.topic_id
							AND t.forum_id = f.forum_id
							AND b.user_id = " . $user->data['user_id'] . "
							AND $auth_sql";
				}
				else
				{
					$sql = "SELECT t.topic_id
						FROM " . TOPICS_TABLE . " t, " . BOOKMARK_TABLE . " b
						WHERE t.topic_id = b.topic_id
							AND b.user_id = " . $user->data['user_id'];
				}
			}
			else
			{
				redirect(append_sid(CMS_PAGE_LOGIN. '?redirect=' . CMS_PAGE_SEARCH . '?search_id=bookmarks&amp;search_mode=bookmarks', true));
			}
			$result = $db->sql_query($sql);

			$search_ids = array();
			while($row = $db->sql_fetchrow($result))
			{
				$search_ids[] = $row['topic_id'];
			}
			$db->sql_freeresult($result);

			$total_match_count = sizeof($search_ids);
			if ($total_match_count <= $start) // No results for the selected page
			{
				$start = $total_match_count - 1;
				$start = intval($start / $config['topics_per_page']) * $config['topics_per_page'];
			}

			// Basic requirements
			$show_results = 'bookmarks';
			$sort_by = 0;
			$sort_dir = 'DESC';
		}
		else
		{
			message_die(GENERAL_MESSAGE, $lang['No_search_match']);
		}

		//0 = post_time, 1; 2 = title, 3 = author, 4 = forum
		$sort_by = request_var('sort_by', 0);
		$sort_dir = request_var('sort_dir', '');
		$sort_dir = ($sort_dir == 'ASC') ? $sort_dir : 'DESC';

		// Delete old data from the search result table
		$sql = 'DELETE FROM ' . SEARCH_TABLE . ' WHERE search_time < ' . ($current_time - (int) $config['session_length']);
		$result = $db->sql_query($sql);

		// Store new result data
		$search_results = implode(', ', $search_ids);
		$per_page = ($show_results == 'posts') ? $config['posts_per_page'] : $config['topics_per_page'];

		//
		// Combine both results and search data (apart from original query)
		// so we can serialize it and place it in the DB
		//
		$store_search_data = array();

		//
		// Limit the character length (and with this the results displayed at all following pages) to prevent
		// truncated result arrays. Normally, search results above 12000 are affected.
		// - to include or not to include
		/*
		$max_result_length = 60000;
		if (strlen($search_results) > $max_result_length)
		{
			$search_results = substr($search_results, 0, $max_result_length);
			$search_results = substr($search_results, 0, strrpos($search_results, ','));
			$total_match_count = count(explode(', ', $search_results));
		}
		*/

		for($i = 0; $i < sizeof($store_vars); $i++)
		{
			$store_search_data[$store_vars[$i]] = !empty($$store_vars[$i]) ? $$store_vars[$i] : '';
		}

		$result_array = serialize($store_search_data);
		unset($store_search_data);

		mt_srand ((double) microtime() * 1000000);
		$search_type = $search_id; //create a save
		$search_id = mt_rand();

		$sql = "UPDATE " . SEARCH_TABLE . "
			SET search_id = $search_id, search_time = $current_time, search_array = '" . $db->sql_escape($result_array) . "'
			WHERE session_id = '" . $user->data['session_id'] . "'";
		$db->sql_return_on_error(true);
		$result = $db->sql_query($sql);
		$db->sql_return_on_error(false);
		if (!$result || !$db->sql_affectedrows())
		{
			$sql = "INSERT INTO " . SEARCH_TABLE . " (search_id, session_id, search_time, search_array)
				VALUES($search_id, '" . $user->data['session_id'] . "', $current_time, '" . $db->sql_escape($result_array) . "')";
			$result = $db->sql_query($sql);
		}
	}
	else
	{
		$search_id = intval($search_id);
		if ($search_id)
		{
			$sql = "SELECT search_array
				FROM " . SEARCH_TABLE . "
				WHERE search_id = " . $search_id . "
					AND session_id = '" . $user->data['session_id'] . "'";
			$result = $db->sql_query($sql);

			if ($row = $db->sql_fetchrow($result))
			{
				$search_data = unserialize($row['search_array']);
				$psort_main = $psort;
				for($i = 0; $i < sizeof($store_vars); $i++)
				{
					$$store_vars[$i] = $search_data[$store_vars[$i]];
				}
				$psort = $psort_main;
			}
		}
	}

	// Look up data ...
	if ($search_results != '')
	{
		//$this_auth = auth(AUTH_ALL, AUTH_LIST_ALL, $user->data);

		if ($show_results == 'posts')
		{
			$sql = "SELECT p.*, f.forum_id, f.forum_name, t.*, u.username, u.user_id, u.user_active, u.user_mask, u.user_color, u.user_sig
				FROM " . FORUMS_TABLE . " f, " . TOPICS_TABLE . " t, " . USERS_TABLE . " u, " . POSTS_TABLE . " p
				WHERE p.post_id IN ($search_results)
					AND f.forum_id = p.forum_id
					AND p.topic_id = t.topic_id
					AND p.poster_id = u.user_id";
		}
		else
		{
			$sql = "SELECT t.*, f.forum_id, f.forum_name, u.username, u.user_id, u.user_active, u.user_mask, u.user_color, u2.username as user2, u2.user_id as id2, u2.user_active as user_active2, u2.user_mask as user_mask2, u2.user_color as user_color2, p.post_username, p2.post_username AS post_username2, p2.post_time
				FROM " . TOPICS_TABLE . " t, " . FORUMS_TABLE . " f, " . USERS_TABLE . " u, " . POSTS_TABLE . " p, " . POSTS_TABLE . " p2, " . USERS_TABLE . " u2
				WHERE t.topic_id IN ($search_results)
					AND t.topic_poster = u.user_id
					AND f.forum_id = t.forum_id
					AND p.post_id = t.topic_first_post_id
					AND p2.post_id = t.topic_last_post_id
					AND u2.user_id = p2.poster_id";
		}

		$per_page = ($show_results == 'posts') ? $config['posts_per_page'] : $config['topics_per_page'];

		$sql .= " ORDER BY ";

		if ($psort == 'cat')
		{
			$sql .= 'f.forum_id ASC, ';
		}

		switch ($sort_by)
		{
			case 1:
				if ($show_results == 'posts')
				{
					$sql .= 'p.post_subject';
				break;
				}
			case 2:
				$sql .= 't.topic_title';
				$sort_by = 2;
				break;
			case 3:
				$sql .= 'u.username';
				break;
			case 4:
				$sql .= 'f.forum_id';
				break;
			default:
				$sql .= ($show_results == 'posts') ? 'p.post_time' : 'p2.post_time';
				$sort_by = 0;
				break;
		}

		$template->assign_vars(array(
				'U_SELF' => CMS_PAGE_SEARCH . '?search_id=' . $search_type . '&amp;s2=' . $s2,
				'U_SELF_SORT' => CMS_PAGE_SEARCH . '?search_id=' . $search_type . '&amp;s2=' . $s2 . '&amp;sort_by=' . $sort_by,
			)
		);

		$sql .= " $sort_dir LIMIT $start, " . $per_page;
		$result = $db->sql_query($sql);

		/* UPI2DB REPLACE
		$searchset = array();
		while($row = $db->sql_fetchrow($result))
		{
			$searchset[] = $row;
		}
		*/

// UPI2DB - BEGIN
		$searchset = array();
		$searchset_gae = array();
		$searchset_gan = array();
		$searchset_ae = array();
		$searchset_an = array();
		$searchset_se = array();
		$searchset_sn = array();
		$searchset_e = array();
		$searchset_n = array();

		while($row = $db->sql_fetchrow($result))
		{
			if($user->data['upi2db_access'])
			{
				if($config['upi2db_edit_topic_first'])
				{
					if(isset($user->data['upi2db_unread']['edit_topics']) && in_array($row['topic_id'], $user->data['upi2db_unread']['edit_topics']) && $row['topic_type'] == POST_GLOBAL_ANNOUNCE)
					{
						$searchset_gae[] = $row;
					}
					elseif($row['topic_type'] == POST_GLOBAL_ANNOUNCE)
					{
						$searchset_gan[] = $row;
					}
					elseif(isset($user->data['upi2db_unread']['edit_topics']) && in_array($row['topic_id'], $user->data['upi2db_unread']['edit_topics']) && $row['topic_type'] == POST_ANNOUNCE)
					{
						$searchset_ae[] = $row;
					}
					elseif($row['topic_type'] == POST_ANNOUNCE)
					{
						$searchset_an[] = $row;
					}
					elseif(isset($user->data['upi2db_unread']['edit_topics']) && in_array($row['topic_id'], $user->data['upi2db_unread']['edit_topics']) && $row['topic_type'] == POST_STICKY)
					{
						$searchset_se[] = $row;
					}
					elseif($row['topic_type'] == POST_STICKY)
					{
						$searchset_sn[] = $row;
					}
					elseif(isset($user->data['upi2db_unread']['edit_topics']) && in_array($row['topic_id'], $user->data['upi2db_unread']['edit_topics']) && $row['topic_type'] != POST_GLOBAL_ANNOUNCE && $row['topic_type'] != POST_ANNOUNCE && $row['topic_type'] != POST_STICKY)
					{
						$searchset_e[] = $row;
					}
					elseif($row['topic_type'] != POST_GLOBAL_ANNOUNCE && $row['topic_type'] != POST_ANNOUNCE && $row['topic_type'] != POST_STICKY)
					{
						$searchset_n[] = $row;
					}
				}
				else
				{
					if($row['topic_type'] == POST_GLOBAL_ANNOUNCE)
					{
						$searchset_gan[] = $row;
					}
					elseif($row['topic_type'] == POST_ANNOUNCE)
					{
						$searchset_an[] = $row;
					}
					elseif($row['topic_type'] == POST_STICKY)
					{
						$searchset_sn[] = $row;
					}
					elseif($row['topic_type'] != POST_GLOBAL_ANNOUNCE && $row['topic_type'] != POST_ANNOUNCE && $row['topic_type'] != POST_STICKY)
					{
						$searchset_n[] = $row;
					}
				}
			}
			else
			{
				$searchset[] = $row;
			}
		}
		if($user->data['upi2db_access'])
		{
			if($config['upi2db_edit_topic_first'])
			{
				$searchset = array_merge($searchset_gae, $searchset_gan, $searchset_ae, $searchset_an, $searchset_se, $searchset_sn, $searchset_e, $searchset_n);
			}
			else
			{
				$searchset = array_merge($searchset_gan, $searchset_an, $searchset_sn, $searchset_n);
			}
		}
// UPI2DB - END

		$db->sql_freeresult($result);

		if ($is_ajax == 1)
		{
			$result_ar = array(
				'search_id' => ($total_match_count == 1) ? $searchset[0]['topic_id'] : $search_id,
				'results' => $total_match_count,
				'keywords' => $search_keywords
			);
			AJAX_message_die($result_ar);
		}
		elseif ($is_ajax == 2)
		{
			$result_title = '';
			$max_results = ($total_match_count < 10) ? $total_match_count : 10;

			$search_result_text = ($total_match_count == 1) ? $lang['AJAX_quick_search_result'] : sprintf($lang['AJAX_quick_search_results'], $total_match_count);
			for ($sr = 0; $sr < $max_results; $sr++)
			{
				$result_title .= '<a href="' . append_sid(CMS_PAGE_VIEWTOPIC . '?' . POST_TOPIC_URL . '=' . $searchset[$sr]['topic_id'] . '&highlight=' . $search_keywords) . '">' . $searchset[$sr]['topic_title'] . '</a><br />';
			}

			$result_ar = array(
				'results' => $result_title,
				'error_msg' => $search_result_text
			);
			AJAX_message_die($result_ar);
		}

		include_once(IP_ROOT_PATH . 'includes/users_zebra_block.' . PHP_EXT);

		if ($show_results == 'bookmarks')
		{
			$template_to_parse = 'search_results_bookmarks.tpl';
		}
		elseif ($show_results == 'posts')
		{
			$template_to_parse = 'search_results_posts.tpl';
		}
		else
		{
			$template_to_parse = 'search_results_topics.tpl';
		}
		make_jumpbox(CMS_PAGE_VIEWFORUM);

		if ($show_results == 'bookmarks')
		{
			// Send variables for bookmarks
			//$s_hidden_fields = '<input type="hidden" name="mode" value="removebm" />';
			$template->assign_vars(array(
				'L_DELETE' => $lang['Delete'],
				'S_BM_ACTION' => append_sid(CMS_PAGE_SEARCH . '?search_id=bookmarks&amp;search_mode=bookmarks&amp;mode=removebm' . (!empty($start) ? ('&amp;start=' . $start) : '')),
				'S_HIDDEN_FIELDS' => $s_hidden_fields
				)
			);
		}

		$template->assign_vars(array(
			'L_SEARCH_MATCHES' => $l_search_matches,
			'L_TOPIC' => $lang['Topic']
			)
		);

		// Added by MG: creation of $highlight_match_string
		$words = array();
		$highlight_match_string = '';

		$highlight_active = '';
		$highlight_match = array();
		if (!empty($split_search))
		{
			for($j = 0; $j < sizeof($split_search); $j++)
			{
				$split_word = $split_search[$j];

				if (($split_word != 'and') && ($split_word != 'or') && ($split_word != 'not'))
				{
					$highlight_match[] = '#\b(' . str_replace("*", "([\w]+)?", $split_word) . ')\b#is';
					// Added by MG: creation of $highlight_match_string
					$words[] = $split_word;
					$highlight_active .= " " . $split_word;

					for ($k = 0; $k < sizeof($synonyms_array); $k++)
					{
						list($replace_synonym, $match_synonym) = split(' ', trim(strtolower($synonyms_array[$k])));

						if ($replace_synonym == $split_word)
						{
							$highlight_match[] = '#\b(' . str_replace("*", "([\w]+)?", $replace_synonym) . ')\b#is';
							// Added by MG: creation of $highlight_match_string
							$words[] = $replace_synonym;
							$highlight_active .= ' ' . $match_synonym;
						}
					}
				}
			}
		}

		// Added by MG: creation of $highlight_match_string
		for($i = 0; $i < sizeof($words); $i++)
		{
			$highlight_match_string .= (($highlight_match_string != '') ? '|' : '') . str_replace('*', '\w*', preg_quote($words[$i], '#'));
		}
		$highlight_match_string = rtrim($highlight_match_string, "\\");

		$highlight_active = urlencode(trim($highlight_active));

		$tracking_forums = (isset($_COOKIE[$config['cookie_name'] . '_f'])) ? unserialize($_COOKIE[$config['cookie_name'] . '_f']) : array();
		$tracking_topics = (isset($_COOKIE[$config['cookie_name'] . '_t'])) ? unserialize($_COOKIE[$config['cookie_name'] . '_t']) : array();

		if ($show_results == 'posts')
		{
			if ($search_where == -1)
			{
				$is_auth_ary = auth(AUTH_ALL, AUTH_LIST_ALL, $user->data);
			}
			else
			{
				$is_auth = auth(AUTH_ALL, $search_where, $user->data);
			}
		}

		// MG User Replied - BEGIN
		// check if user replied to the topic
		define('USER_REPLIED_ICON', true);
		$user_topics = $class_topics->user_replied_array($searchset);
		// MG User Replied - END

		$valid_results = 0;
		for($i = 0; $i < sizeof($searchset); $i++)
		{
			// CrackerTracker v5.x
			$sucheck = strtolower($highlight_active);
			$sucheck = str_replace($ct_rules, '*', $sucheck);
			if($sucheck != $highlight_active)
			{
				$highlight_active = '';
			}
			// CrackerTracker v5.x

			$forum_id = !empty($searchset[$i]['forum_id']) ? $searchset[$i]['forum_id'] : 0;
			$topic_id = !empty($searchset[$i]['topic_id']) ? $searchset[$i]['topic_id'] : 0;
			$post_id = !empty($searchset[$i]['post_id']) ? $searchset[$i]['post_id'] : 0;
			$forum_id_append = (!empty($forum_id) ? (POST_FORUM_URL . '=' . $forum_id) : '');
			$topic_id_append = (!empty($topic_id) ? (POST_TOPIC_URL . '=' . $topic_id) : '');
			$post_id_append = (!empty($post_id) ? (POST_POST_URL . '=' . $post_id) : '');
			$post_id_append_url = (!empty($post_id) ? ('#p' . $post_id) : '');
			$forum_url = append_sid(CMS_PAGE_VIEWFORUM . '?' . $forum_id_append);
			$topic_url = append_sid(CMS_PAGE_VIEWTOPIC . '?' . $forum_id_append . '&amp;' . $topic_id_append . '&amp;highlight=' . $highlight_active);
			$post_url = append_sid(CMS_PAGE_VIEWTOPIC . '?' . $forum_id_append . '&amp;' . $topic_id_append . '&amp;' . $post_id_append . '&amp;highlight=' . $highlight_active) . $post_id_append_url;
			$user_replied = (!empty($user_topics) && isset($user_topics[$topic_id]));

			$post_date = create_date_ip($config['default_dateformat'], $searchset[$i]['post_time'], $config['board_timezone']);

			$message = !empty($searchset[$i]['post_text']) ? $searchset[$i]['post_text'] : '';
			$message_compiled = (empty($searchset[$i]['post_text_compiled']) || !empty($user->data['session_logged_in']) || !empty($config['posts_precompiled'])) ? false : $searchset[$i]['post_text_compiled'];

			$topic_title_data = $class_topics->generate_topic_title($topic_id, $searchset[$i], 255);
			$topic_title = $topic_title_data['title'];
			$topic_title_clean = $topic_title_data['title_clean'];
			$topic_title_plain = $topic_title_data['title_plain'];
			$topic_title_label = $topic_title_data['title_label'];
			$topic_title_short = $topic_title_data['title_short'];

			if ($show_results == 'posts')
			{
				if ($search_where == -1)
				{
					$is_auth = $is_auth_ary[$forum_id];
				}

				$clean_tags = false;
				if ($return_chars != -1)
				{
					$clean_tags = true;
				}

				if($message_compiled === false)
				{
					$bbcode->allow_html = $config['allow_html'] && $searchset[$i]['enable_html'];
					$bbcode->allow_bbcode = $config['allow_bbcode'] && $searchset[$i]['enable_bbcode'];
					$bbcode->allow_smilies = $config['allow_smilies'] && $searchset[$i]['enable_smilies'];
					$bbcode->code_post_id = $searchset[$i]['post_id'];
					$message = $bbcode->parse($message, '', false, $clean_tags);
					$bbcode->code_post_id = 0;
				}
				else
				{
					$message = $message_compiled;
				}

				if ($return_chars != -1)
				{
					//$message = (strlen($message) > $return_chars) ? substr($message, 0, $return_chars) . ' ...' : $message;
					$message = truncate_html_string($message, $return_chars);
				}

				if ($highlight_active)
				{
					// Replaced by MG: creation of $highlight_match_string
					$message = preg_replace('#(?!<.*)(?<!\w)(' . $highlight_match_string . ')(?!\w|[^<>]*>)#i', '<span class="highlight-w"><b>\1</b></span>', $message);
				}

				$post_subject = !empty($searchset[$i]['post_subject']) ? censor_text($searchset[$i]['post_subject']) : $topic_title;
				$message = censor_text($message);

				$poster = ($searchset[$i]['user_id'] != ANONYMOUS) ? colorize_username($searchset[$i]['user_id'], $searchset[$i]['username'], $searchset[$i]['user_color'], $searchset[$i]['user_active']) : (($searchset[$i]['post_username'] != '') ? $searchset[$i]['post_username'] : $lang['Guest']);
				//$poster .= ($searchset[$i]['user_id'] != ANONYMOUS) ? $searchset[$i]['username'] : (($searchset[$i]['post_username'] != "") ? $searchset[$i]['post_username'] : $lang['Guest']);

				if (($user->data['user_level'] != ADMIN) && !empty($searchset[$i]['user_mask']) && empty($searchset[$i]['user_active']))
				{
					$poster = $lang['INACTIVE_USER'];
				}

// UPI2DB - BEGIN
				if(!$user->data['upi2db_access'])
				{
// UPI2DB - END
					if ($user->data['session_logged_in'] && ($searchset[$i]['post_time'] > $user->data['user_lastvisit']))
					{
						if (!empty($tracking_topics[$topic_id]) && !empty($tracking_forums[$forum_id]))
						{
							$topic_last_read = ($tracking_topics[$topic_id] > $tracking_forums[$forum_id]) ? $tracking_topics[$topic_id] : $tracking_forums[$forum_id];
						}
						elseif (!empty($tracking_topics[$topic_id]) || !empty($tracking_forums[$forum_id]))
						{
							$topic_last_read = (!empty($tracking_topics[$topic_id])) ? $tracking_topics[$topic_id] : $tracking_forums[$forum_id];
						}

						if ($searchset[$i]['post_time'] > $topic_last_read)
						{
							$mini_post_img = $images['icon_minipost_new'];
							$mini_post_alt = $lang['New_post'];
						}
						else
						{
							$mini_post_img = $images['icon_minipost'];
							$mini_post_alt = $lang['Post'];
						}
					}
					else
					{
						$mini_post_img = $images['icon_minipost'];
						$mini_post_alt = $lang['Post'];
					}
					if (!empty($searchset[$i]['topic_calendar_time']) && ($searchset[$i]['post_id'] == $searchset[$i]['topic_first_post_id']))
					{
						$post_subject .= '</a></b>' . get_calendar_title($searchset[$i]['topic_calendar_time'], $searchset[$i]['topic_calendar_duration']);
					}
// UPI2DB - BEGIN
					$folder_image = $images['topic_nor_read'];
					$folder_alt = ($searchset[$i]['topic_status'] == TOPIC_LOCKED) ? $lang['Topic_locked'] : $lang['No_new_posts'];
				}
				else
				{
					search_calc_unread_ip($user->data['upi2db_unread'], $topic_id, $searchset, $i, $mini_post_img, $mini_post_alt, $unread_color, $folder_image, $folder_alt);
				}
				$mark_topic_unread_array['unmark_post'] = 0;
				if($user->data['upi2db_access'])
				{
					if($s2 == 'mark')
					{
						$post_id = $searchset[$i]['post_id'];
						$mark_topic_unread_array['unmark_post'] = 1;
					}
				}
// UPI2DB - END
				// SELF AUTH - BEGIN
				// Comment the lines below if you wish to show RESERVED topics for AUTH_SELF.
				$is_topic_reserved = false;
				if (((($user->data['user_level'] != ADMIN) && ($user->data['user_level'] != MOD)) || (($user->data['user_level'] == MOD) && ($config['allow_mods_view_self'] == false))) && (intval($is_auth_ary[$searchset[$i]['forum_id']]['auth_read']) == AUTH_SELF) && ($searchset[$i]['user_id'] != $user->data['user_id']))
				{
					$is_topic_reserved = true;
					continue;
					/*
					$poster = $lang['Reserved_Author'];
					$topic_title = $lang['Reserved_Topic'];
					$message = $lang['Reserved_Post'];
					*/
				}
				// SELF AUTH - END
				$template->assign_block_vars('searchresults', array(
					'TOPIC_TITLE' => $topic_title,
					'TOPIC_TITLE_PLAIN' => $topic_title_plain,
					'FORUM_NAME' => get_object_lang(POST_FORUM_URL . $searchset[$i]['forum_id'], 'name'),
					//'POST_SUBJECT' => $post_subject,
					'POST_DATE' => $post_date,
					'POSTER_NAME' => $poster,
					'TOPIC_REPLIES' => $searchset[$i]['topic_replies'],
					'TOPIC_VIEWS' => $searchset[$i]['topic_views'],
					'MESSAGE' => $message,
					'MINI_POST_IMG' => $mini_post_img,
					'L_MINI_POST_ALT' => $mini_post_alt,
// UPI2DB - BEGIN
					'L_TOPIC_FOLDER_ALT' => $folder_alt,
					'TOPIC_FOLDER_IMG' => $folder_image,
					'UNREAD_COLOR' => $unread_color,

					'UPI2DB_UNMARK_POST' => !empty($mark_topic_unread_array['unmark_post']) ? true : false,
					'L_UPI2DB_UNMARK_POST' => $lang['upi2db_unmark_post'],
					'UPI2DB_UNMARK_POST_IMG' => $images['unmark_img'],
					'UPI2DB_UNMARK_POST_URL' => append_sid(CMS_PAGE_SEARCH . '?search_id=upi2db&amp;s2=mark&amp;' . POST_TOPIC_URL . '=' . $topic_id . '&amp;' . POST_FORUM_URL . '=' . $forum_id . '&amp;' . POST_POST_URL . '=' . $post_id . '&amp;do=unmark_post' . (isset($s2) ? ('&amp;s2=' . $s2) : '')),
// UPI2DB - END

					// AJAX Features - BEGIN
					'TOPIC_RAW_TITLE' => $topic_title_plain,
					'POST_SUBJECT' => (empty($post_subject)) ? $lang['No_subject'] : $post_subject,
					'POST_RAW_SUBJECT' => $post_subject,
					'RAW_MESSAGE' => $raw_message,
					'U_POST_ID' => $searchset[$i]['post_id'],
					'U_EDIT_POST' => $edit_url,
					'U_EDIT_IMG' => $edit_img,
					'S_AJAX_EDIT_TITLE' => ($can_edit) ? 'ondblclick="AJAXTitleEdit(' . $searchset[$i]['post_id'] . ', ' . $is_firstpost . ');"' : '',
					// AJAX Features - END

					'U_POST' => $post_url,
					'U_TOPIC' => $topic_url,
					'U_FORUM' => $forum_url
					)
				);
// UPI2DB - BEGIN
				if($user->data['upi2db_access'])
				{
					$template->assign_block_vars('searchresults.switch_upi2db_on', array());
				}
// UPI2DB - END
			}
			else
			{
				$message = '';

				$topic_title_data = $class_topics->generate_topic_title($topic_id, $searchset[$i], 255);
				$topic_title = $topic_title_data['title'];
				$topic_title_clean = $topic_title_data['title_clean'];
				$topic_title_plain = $topic_title_data['title_plain'];
				$topic_title_label = $topic_title_data['title_label'];
				$topic_title_short = $topic_title_data['title_short'];

				//$news_label = ($searchset[$i]['news_id'] > 0) ? $lang['News_Cmx'] . '' : '';
				$news_label = '';

				$views = $searchset[$i]['topic_views'];
				$replies = $searchset[$i]['topic_replies'];

				$topic_link = $class_topics->build_topic_icon_link($searchset[$i]['forum_id'], $searchset[$i]['topic_id'], $searchset[$i]['topic_type'], $searchset[$i]['topic_reg'], $searchset[$i]['topic_replies'], $searchset[$i]['news_id'], $searchset[$i]['poll_start'], $searchset[$i]['topic_status'], $searchset[$i]['topic_moved_id'], $searchset[$i]['post_time'], $user_replied, $replies);

				$topic_id = $topic_link['topic_id'];
				$topic_id_append = $topic_link['topic_id_append'];

				// Event Registration - BEGIN
				if (($searchset[$i]['topic_reg']) && check_reg_active($topic_id))
				{
					$regoption_array = array();

					if ($user->data['session_logged_in'])
					{
						$sql = "SELECT registration_status FROM " . REGISTRATION_TABLE . "
								WHERE topic_id = " . $topic_id . "
								AND registration_user_id = " . $user->data['user_id'];
						$result = $db->sql_query($sql);

						if ($regrow = $db->sql_fetchrow($result))
						{
							$status = $regrow['registration_status'];
							if ($status == REG_OPTION1)
							{
								$reg_user_own_reg = '<span class="text_green">&bull;</span>';
							}
							elseif ($status == REG_OPTION2)
							{
								$reg_user_own_reg = '<span class="text_blue">&bull;</span>';
							}
							elseif ($status == REG_OPTION3)
							{
								$reg_user_own_reg = '<span class="text_red">&bull;</span>';
							}
						}

						$db->sql_freeresult($result);
					}

					$sql = "SELECT u.user_id, u.username, u.user_active, u.user_color, r.registration_time, r.registration_status FROM " . REGISTRATION_TABLE . " r, " . USERS_TABLE . " u
							WHERE r.topic_id = $topic_id
							AND r.registration_user_id = u.user_id
							ORDER BY registration_status, registration_time";
					$result = $db->sql_query($sql);
					$reg_info = $db->sql_fetchrowset($result);
					$db->sql_freeresult($result);

					$numregs = sizeof($reg_info);
					$option1_count = 0;
					$option2_count = 0;
					$option3_count = 0;

					for ($u = 0; $u < $numregs; $u++)
					{
						if ($reg_info[$u]['registration_status'] == REG_OPTION1)
						{
							$option1_count++;
						}
						elseif ($reg_info[$u]['registration_status'] == REG_OPTION2)
						{
							$option2_count++;
						}
						elseif ($reg_info[$u]['registration_status'] == REG_OPTION3)
						{
							$option3_count++;
						}
					}

					$option1_count = '<span class="text_green">' . (0 + $option1_count) . '</span>';
					array_push($regoption_array, $option1_count);

					$option2_count = '<span class="text_blue">' . (0 + $option2_count) . '</span>';
					array_push($regoption_array, $option2_count);

					$option3_count = '<span class="text_red">' . (0 + $option3_count) . '</span>';
					array_push($regoption_array, $option3_count);

					$regoptions_count = sizeof($regoption_array);

					$v = 0;
					$regoptions = '';
					while ($v < $regoptions_count - 1)
					{
						$regoptions .= $regoption_array[$v] . '-';
						$v++;
					}
					$regoptions .= array_pop($regoption_array);
				}
				// Event Registration - END

				$topic_pagination = generate_topic_pagination($forum_id, $topic_id, $replies);

				if ($searchset[$i]['user_id'] != ANONYMOUS)
				{
					$topic_author = colorize_username($searchset[$i]['user_id'], $searchset[$i]['username'], $searchset[$i]['user_color'], $searchset[$i]['user_active']);
				}
				else
				{
					$sql = "SELECT p.post_username
						FROM " . TOPICS_TABLE . " t, " . POSTS_TABLE . " p
						WHERE t.topic_first_post_id = p.post_id
						AND t.topic_id = " . $searchset[$i]['topic_id'];
					$result = $db->sql_query($sql);
					$row = $db->sql_fetchrow($result);
					$topic_author_name = $row['post_username'];
					$topic_author = ($topic_author_name != '') ? $topic_author_name : $lang['Guest'];
				}

				if (($user->data['user_level'] != ADMIN) && !empty($searchset[$i]['user_mask']) && empty($searchset[$i]['user_active']))
				{
					$topic_author = $lang['INACTIVE_USER'];
				}

				$first_post_time = create_date($config['default_dateformat'], $searchset[$i]['topic_time'], $config['board_timezone']);
				$last_post_time = create_date_ip($config['default_dateformat'], $searchset[$i]['post_time'], $config['board_timezone']);
				$last_post_author = ($searchset[$i]['id2'] == ANONYMOUS) ? (($searchset[$i]['post_username2'] != '') ? $searchset[$i]['post_username2'] . ' ' : $lang['Guest'] . ' ') : colorize_username($searchset[$i]['id2'], $searchset[$i]['user2'], $searchset[$i]['user_color2'], $searchset[$i]['user_active2']);

				if (($user->data['user_level'] != ADMIN) && !empty($searchset[$i]['user_mask2']) && empty($searchset[$i]['user_active2']))
				{
					$last_post_author = $lang['INACTIVE_USER'];
				}

				$last_post_url = '<a href="' . append_sid(CMS_PAGE_VIEWTOPIC . '?' . $forum_id_append . '&amp;' . $topic_id_append . '&amp;' . POST_POST_URL . '=' . $searchset[$i]['topic_last_post_id']) . '#p' . $searchset[$i]['topic_last_post_id'] . '"><img src="' . $images['icon_latest_reply'] . '" alt="' . $lang['View_latest_post'] . '" title="' . $lang['View_latest_post'] . '" /></a>';
// UPI2DB - BEGIN
				if($user->data['upi2db_access'])
				{
					$mark_always_read = mark_always_read($searchset[$i]['topic_type'], $topic_id, $forum_id, 'search', 'icon', $user->data['upi2db_unread'], $start, $topic_link['image'], $search_id, $s2);
				}
				else
				{
					$mark_always_read = '<img src="' . $topic_link['image'] . '" alt="' . $topic_link['image_alt'] . '" title="' . $topic_link['image_alt'] . '" />';
				}
				$tt = $searchset[$i]['topic_type'];
// UPI2DB - END

				$mark_link_start = '';//($user->data['session_logged_in']) ? '<a onclick="return AJAXMarkTopic('. $topic_id .');" href="#">' : '';
				$mark_link_end = '';//($user->data['session_logged_in']) ? '</a>' : '';

				// SELF AUTH - BEGIN
				// Comment the lines below if you wish to show RESERVED topics for AUTH_SELF.
				$is_topic_reserved = false;
				if (((($user->data['user_level'] != ADMIN) && ($user->data['user_level'] != MOD)) || (($user->data['user_level'] == MOD) && ($config['allow_mods_view_self'] == false))) && (intval($is_auth_ary[$searchset[$i]['forum_id']]['auth_read']) == AUTH_SELF) && ($searchset[$i]['user_id'] != $user->data['user_id']))
				{
					$is_topic_reserved = true;
					continue;
					/*
					$topic_author = $lang['Reserved_Author'];
					$last_post_author = $lang['Reserved_Author'];
					$topic_title = $lang['Reserved_Topic'];
					*/
				}
				// SELF AUTH - END

// UPI2DB - BEGIN
				// Edited By Mighty Gorgon - BEGIN
				if (($user->data['user_level'] == ADMIN) || ($user->data['user_level'] == MOD))
				{
					$mark_read_forbid = false;
				}
				else
				{
					$mark_read_forbid = (($tt == POST_STICKY) || ($tt == POST_ANNOUNCE) || ($tt == POST_GLOBAL_ANNOUNCE)) ? true : false;
				}
				// Edited By Mighty Gorgon - END
// UPI2DB - END

				$template->assign_block_vars('searchresults', array(
					'ROW_CLASS' => (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'],
					'FORUM_NAME' => get_object_lang(POST_FORUM_URL . $searchset[$i]['forum_id'], 'name'),
					'FORUM_ID' => $forum_id,
					'TOPIC_ID' => $topic_id,

					'TOPIC_FOLDER_IMG' => $topic_link['image'],
					'L_TOPIC_FOLDER_ALT' => $topic_link['image_alt'],
					'TOPIC_AUTHOR' => $topic_author,
					'TOPIC_TITLE' => $topic_title,
					'TOPIC_TITLE_PLAIN' => $topic_title_plain,
					'TOPIC_TYPE' => $topic_link['type'],
					'TOPIC_TYPE_ICON' => $topic_link['icon'],
					'TOPIC_CLASS' => (!empty($topic_link['class_new']) ? ('topiclink' . $topic_link['class_new']) : $topic_link['class']),
					'CLASS_NEW' => $topic_link['class_new'],
					'NEWEST_POST_IMG' => $topic_link['newest_post_img'],
					'L_NEWS' => $news_label,
					'TOPIC_ATTACHMENT_IMG' => topic_attachment_image($searchset[$i]['topic_attachment']),

					// Event Registration - BEGIN
					'REG_OPTIONS' => $regoptions,
					'REG_USER_OWN_REG' => $reg_user_own_reg,
					// Event Registration - END
					'GOTO_PAGE' => $topic_pagination['base'],
					'GOTO_PAGE_FULL' => $topic_pagination['full'],
					'REPLIES' => $replies,
					'VIEWS' => $views,
					'FIRST_POST_TIME' => $first_post_time,
					'LAST_POST_TIME' => $last_post_time,
					'LAST_POST_AUTHOR' => $last_post_author,
					'LAST_POST_IMG' => $last_post_url,
// UPI2DB - BEGIN
					'NO_AGM' => ($mark_read_forbid || (isset($s2) && ($s2 == 'perm'))) ? 'disabled' : '',
					'U_MARK_ALWAYS_READ' => $mark_always_read,
// UPI2DB - END
					'U_VIEW_FORUM' => $forum_url,
					'U_VIEW_TOPIC' => $topic_url
					)
				);
// UPI2DB - BEGIN
				if($user->data['upi2db_access'])
				{
					$template->assign_block_vars('searchresults.switch_upi2db_on', array());
				}
// UPI2DB - END
				// Event Registration - BEGIN
				if (($searchset[$i]['topic_reg']) && check_reg_active($topic_id))
				{
					$template->assign_block_vars('searchresults.display_reg', array());
				}
				// Event Registration - END
			}
			$valid_results++;
		}

		// Header
		if ($show_results == 'bookmarks')
		{
			$nav_main_lang = $lang['Bookmarks'];
			$nav_main_url = append_sid('search.' . PHP_EXT . '?search_id=bookmarks');
			$l_search_matches = ($valid_results == 1) ? sprintf($lang['Found_bookmark'], $valid_results) : sprintf($lang['Found_bookmarks'], $valid_results);
			//$l_search_matches = ($total_match_count == 1) ? sprintf($lang['Found_bookmark'], $total_match_count) : sprintf($lang['Found_bookmarks'], $total_match_count);
		}
		else
		{
			$nav_main_lang = $lang['Search'];
			$nav_main_url = append_sid('search.' . PHP_EXT);
			$l_search_matches = ($valid_results == 1) ? sprintf($lang['Found_search_match'], $valid_results) : sprintf($lang['Found_search_matches'], $valid_results);
			//$l_search_matches = ($total_match_count == 1) ? sprintf($lang['Found_search_match'], $total_match_count) : sprintf($lang['Found_search_matches'], $total_match_count);
		}
		$meta_content['page_title'] = $nav_main_lang;
		$meta_content['description'] = '';
		$meta_content['keywords'] = '';
		$nav_server_url = create_server_url();
		$breadcrumbs['address'] = $lang['Nav_Separator'] . '<a href="' . $nav_server_url . $nav_main_url . '" class="nav-current">' . $nav_main_lang . '</a>';
		$breadcrumbs['bottom_right_links'] = '<span class="gensmall">' . $l_search_matches . '</span>';
		if (!empty($is_newposts))
		{
			$breadcrumbs['bottom_right_links'] .= '<span class="gensmall">&nbsp;&bull;&nbsp;<a href="' . append_sid('search.' . PHP_EXT . '?search_id=newposts&amp;sr=' . (($sr == 't') ? 'p' : 't')) . '">' . (($sr == 't') ? $lang['SN_SHOW_POSTS'] : $lang['SN_SHOW_TOPICS']) . '</a></span>';
		}

		// Valid results found?
		if (empty($valid_results))
		{
			message_die(GENERAL_MESSAGE, $lang['No_search_match']);
		}

		$base_url = CMS_PAGE_SEARCH . '?search_id=' . $search_id . '&amp;psort=' . $psort;
		$search_url_add = ($start > 0) ? ('&amp;start=' . $start) : '';
		$search_url_add .= (isset($s2) && ($s2 == 'new')) ? ('&amp;s2=' . $s2) : '';
		$s_hidden_fields = '';
		$s_hidden_fields .= '<input type="hidden" name="search_id" value="' . $search_id . '" />';
		$s_hidden_fields .= '<input type="hidden" name="search_mode" value="' . $search_mode . '" />';
		//$l_forum = (($show_results == 'topics') ? ('<a href="' . append_sid(CMS_PAGE_SEARCH . '?search_id=' . $search_id . $search_url_add . '&amp;psort=cat') . '">' . $lang['Forum'] . '</a>') : $lang['Forum']);
		$l_forum = $lang['Forum'];

		$template->assign_vars(array(
			'PAGINATION' => generate_pagination($base_url, $total_match_count, $per_page, $start),
			'PAGE_NUMBER' => sprintf($lang['Page_of'], (floor($start / $per_page) + 1), ceil($total_match_count / $per_page)),
			'SORT_BY' => $sort_by,
			'SORT_DIR' => $sort_dir,

			'L_AUTHOR' => $lang['Author'],
			'L_MESSAGE' => $lang['Message'],
			'L_FORUM' => $l_forum,
			'L_TOPICS' => $lang['Topics'],
			'L_REPLIES' => $lang['Replies'],
			'L_VIEWS' => $lang['Views'],
			'L_POSTS' => $lang['Posts'],
// UPI2DB - BEGIN
			'L_MAR' => $lang['upi2db_search_mark_read'],
			'L_SUBMIT_MARK_READ' => $lang['upi2db_submit_mark_read'],
			'S_POST_ACTION' => append_sid(CMS_PAGE_SEARCH . '?search_id=' . $search_id . (isset($s2) ? ('&amp;s2=' . $s2) : '')),
			'L_SUBMIT_MARK_READ' => $lang['upi2db_submit_mark_read'],
// UPI2DB - END
			'L_LASTPOST' => ($search_type == 'upi2db') ? $lang['Last_Post'] : ('<a href="' . append_sid(CMS_PAGE_SEARCH . '?search_id=' . $search_type . $search_url_add) . '">' . $lang['Last_Post'] . '</a>'),
			'L_SELECT' => $lang['Select'],
			'L_POSTED' => $lang['Posted'],
			'L_SUBJECT' => $lang['Subject'],
			'L_FULL_EDIT' => $lang['Full_edit'],
			'L_SAVE_CHANGES' => $lang['Save_changes'],
			'L_CANCEL' => $lang['Cancel'],

			'L_GOTO_PAGE' => $lang['Goto_page'],
			'S_HIDDEN_FIELDS' => $s_hidden_fields
			)
		);

		full_page_generation($template_to_parse, $meta_content['page_title'], $meta_content['description'], $meta_content['keywords']);
	}
	elseif ($is_ajax)
	{
		$result_ar = array(
			'search_id' => 0,
			'results' => 0,
			'keywords' => ''
		);
		AJAX_message_die($result_ar);
	}
	else
	{
		if ($show_results == 'bookmarks')
		{
			message_die(GENERAL_MESSAGE, $lang['No_Bookmarks']);
		}
		else
		{
			message_die(GENERAL_MESSAGE, $lang['No_search_match']);
		}
	}
}

$s_forums = get_tree_option();

// Number of chars returned
$s_characters = '<option value="-1">' . $lang['All_available'] . '</option>';
$s_characters .= '<option value="0">0</option>';
$s_characters .= '<option value="25">25</option>';
$s_characters .= '<option value="50">50</option>';

for($i = 100; $i < 1100 ; $i += 100)
{
	$selected = ($i == 200) ? ' selected="selected"' : '';
	$s_characters .= '<option value="' . $i . '"' . $selected . '>' . $i . '</option>';
}

// Sorting
$s_sort_by = '';
for($i = 0; $i < sizeof($sort_by_types); $i++)
{
	$s_sort_by .= '<option value="' . $i . '">' . $sort_by_types[$i] . '</option>';
}

// Search time
$previous_days = array(0, 1, 7, 14, 30, 90, 180, 364);
$previous_days_text = array($lang['ALL_POSTS'], $lang['1_DAY'], $lang['7_DAYS'], $lang['2_WEEKS'], $lang['1_MONTH'], $lang['3_MONTHS'], $lang['6_MONTHS'], $lang['1_YEAR']);

$s_time = '';
for($i = 0; $i < sizeof($previous_days); $i++)
{
	$selected = ($topic_days == $previous_days[$i]) ? ' selected="selected"' : '';
	$s_time .= '<option value="' . $previous_days[$i] . '"' . $selected . '>' . $previous_days_text[$i] . '</option>';
}
$l_only_bluecards = ($user->data['user_level'] >= ADMIN) ? '<input type="checkbox" name="only_bluecards" />&nbsp;' . $lang['Search_only_bluecards'] : '' ;

$breadcrumbs['bottom_right_links'] = '<span class="gensmall"><a href="' . append_sid('gsearch.' . PHP_EXT) . '">' . $lang['GSEARCH_ENGINE'] . '</a></span>';

make_jumpbox(CMS_PAGE_VIEWFORUM);

$template->assign_vars(array(
	'L_SEARCH_QUERY' => $lang['Search_query'],
	'L_SEARCH_OPTIONS' => $lang['Search_options'],
	'L_SEARCH_KEYWORDS' => $lang['Search_keywords'],
	'L_SEARCH_KEYWORDS_EXPLAIN' => $lang['Search_keywords_explain'],
	'L_SEARCH_AUTHOR' => $lang['Search_author'],
	'L_SEARCH_AUTHOR_EXPLAIN' => $lang['Search_author_explain'],
	'L_SEARCH_AUTHOR_TOPIC_STARTER' => $lang['Search_author_topic_starter'],
	// Start Advanced IP Tools Pack MOD
	'L_SEARCH_IP' => $lang['Search_ip'],
	'L_SEARCH_IP_EXPLAIN' => $lang['Search_ip_explain'],
	// End Advanced IP Tools Pack MOD
	'L_SEARCH_ANY_TERMS' => $lang['Search_for_any'],
	'L_SEARCH_ALL_TERMS' => $lang['Search_for_all'],
	'L_SEARCH_MESSAGE_TITLE' => $lang['Search_title_msg'],
	'L_SEARCH_TITLE_ONLY' => $lang['Search_title_only'],
	'L_SEARCH_MESSAGE_ONLY' => $lang['Search_msg_only'],
	'L_CATEGORY' => $lang['Category'],
	'L_RETURN_FIRST' => $lang['Return_first'],
	'L_CHARACTERS' => $lang['characters_posts'],
	'L_SORT_BY' => $lang['Sort_by'],
	'L_SORT_ASCENDING' => $lang['Sort_Ascending'],
	'L_SORT_DESCENDING' => $lang['Sort_Descending'],
	'L_SEARCH_PREVIOUS' => $lang['Search_previous'],
	'L_DISPLAY_RESULTS' => $lang['Display_results'],
	'L_FORUM' => $lang['Forum'],
	'L_TOPICS' => $lang['Topics'],
	'L_POSTS' => $lang['Posts'],
	'L_ONLY_BLUECARDS' => $l_only_bluecards,

	'S_SEARCH_ACTION' => append_sid(CMS_PAGE_SEARCH . '?mode=results'),
	'S_CHARACTER_OPTIONS' => $s_characters,
	'S_FORUM_OPTIONS' => $s_forums,
	'S_SEARCH_MESSAGE_OPTIONS' => (!$plus_config['enable_fulltextsearch']) ? 'checked="checked"' : 'DISABLED',
	'S_SEARCH_MESSAGE_OPTIONS2' => (!$plus_config['enable_fulltextsearch']) ? '' : 'checked="checked"',
	'S_CATEGORY_OPTIONS' => $s_categories,
	'S_TIME_OPTIONS' => $s_time,
	'S_SORT_OPTIONS' => $s_sort_by,
	'S_HIDDEN_FIELDS' => ''
	)
);

full_page_generation('search_body.tpl', $lang['Search'], '', '');

?>