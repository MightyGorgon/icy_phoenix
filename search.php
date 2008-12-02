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

// CTracker_Ignore: File Checked By Human
define('IN_SEARCH', true);
// Added to optimize memory for attachments
define('ATTACH_DISPLAY', true);
define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/functions_search.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_topics.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_groups.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_calendar.' . PHP_EXT);

// Adding CPL_NAV only if needed
define('PARSE_CPL_NAV', true);

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

// CrackerTracker v5.x
if (isset($_POST['mode']) || isset($_GET['mode']) || !empty($_GET['search_id']) || isset($_POST['search_id']) || isset($_GET['search_keywords']) || isset($_POST['show_results']) || isset($_GET['show_results']))
{
	include_once(IP_ROOT_PATH . 'ctracker/classes/class_ct_userfunctions.' . PHP_EXT);
	$search_system = new ct_userfunctions();
	$search_system->search_handler();
	unset($search_system);
}
// CrackerTracker v5.x

// SELF AUTH
// MG Added for an indepth auth check and SELF posts - BEGIN
$is_auth_ary = array();
$is_auth_ary = auth(AUTH_ALL, AUTH_LIST_ALL, $userdata);
// MG Added for an indepth auth check and SELF posts - END

//<!-- BEGIN Unread Post Information to Database Mod -->
if($userdata['upi2db_access'])
{
	$params = array('always_read' => 'always_read', 't' => 't', 'f' => 'f', 'p' => 'p', 's2' => 's2', 'mar_topic_id' => 'mar_topic_id', 'mar' => 'mar', 'do' => 'do', 'search_id' => 'search_id', 'search_mode' => 'search_id', 'tt' => 'tt');
	while(list($var, $param) = @each($params))
	{
		if (!empty($_POST[$param]) || !empty($_GET[$param]))
		{
			$$var = (!empty($_POST[$param])) ? $_POST[$param] : $_GET[$param];
		}
		else
		{
			$$var = '';
		}
	}

	$unread = unread();

	if($always_read || $do || ($mar && !empty($mar_topic_id)))
	{
		if($always_read)
		{
			$mark_read_text = always_read($t, $always_read, $unread);
		}
		if($do)
		{
			$mark_read_text = set_unread($t, $f, $p, $unread, $do, $tt);
		}
		if($mar && !empty($mar_topic_id))
		{
			search_mark_as_read($mar_topic_id);
			$mark_read_text = $lang['upi2db_submit_topic_mark_read'];
		}

		$redirect_url = append_sid(SEARCH_MG . '?search_id=' . $search_mode . '&amp;s2=' . $s2);
		meta_refresh(3, $redirect_url);

		$message = $mark_read_text . '<br /><br />' . sprintf($lang['Click_return_search'], '<a href="' . append_sid(SEARCH_MG . '?search_id=' . $search_mode . '&amp;s2=' . $s2) . '">', '</a> ');
		message_die(GENERAL_MESSAGE, $message);
	}
	$count_new_posts = count($unread['new_posts']);
	$count_edit_posts = count($unread['edit_posts']);
	$count_always_read = count($unread['always_read']['topics']);
	$count_mark_unread = count($unread['mark_posts']);
}
//<!-- END Unread Post Information to Database Mod -->

$cms_page_id = '5';
$cms_page_name = 'search';
check_page_auth($cms_page_id, $cms_page_name);
$cms_global_blocks = ($board_config['wide_blocks_' . $cms_page_name] == 1) ? true : false;

if ($search_mode == 'bookmarks')
{
	// TO DO: force to false, and decide if we would like to overwrite it with Profile Global Blocks settings...
	//$cms_global_blocks = ($board_config['wide_blocks_profile'] == 1) ? true : false;
	$cms_global_blocks = false;
}

// Define initial vars
if (isset($_POST['mode']) || isset($_GET['mode']))
{
	$mode = (isset($_POST['mode']) ? $_POST['mode'] : $_GET['mode']);
}
else
{
	$mode = '';
}

$only_bluecards = (isset($_POST['only_bluecards'])) ? (($_POST['only_bluecards']) ? true : 0) : 0;
if (isset($_POST['search_keywords']) || isset($_GET['search_keywords']))
{
	$search_keywords = (isset($_POST['search_keywords'])) ? $_POST['search_keywords'] : $_GET['search_keywords'];
}
else
{
	$search_keywords = '';
}

if (isset($_POST['search_author']) || isset($_GET['search_author']))
{
	$search_author = (isset($_POST['search_author'])) ? $_POST['search_author'] : $_GET['search_author'];
	$search_author = phpbb_clean_username($search_author);
	$search_topic_starter = (!empty($_GET['search_topic_starter']) ? true : (!empty($_POST['search_topic_starter']) ? true : false));
}
else
{
	$search_author = '';
	$search_topic_starter = false;
}

$search_id = (isset($_GET['search_id'])) ? $_GET['search_id'] : '';
$search_mode = !empty($search_mode) ? $search_mode : $search_id;

if (isset($_POST['show_results']) || isset($_GET['show_results']))
{
	$show_results = (isset($_POST['show_results'])) ? $_POST['show_results'] : $_GET['show_results'];
}
else
{
	$show_results = 'posts';
}
$show_results = ($show_results == 'topics') ? 'topics' : 'posts';

if (isset($_POST['return_chars']) || isset($_GET['return_chars']))
{
	$return_chars = (isset($_POST['return_chars'])) ? intval($_POST['return_chars']) : intval($_GET['return_chars']);
}
else
{
	$return_chars = 200;
}
$return_chars = ($return_chars >= -1) ? $return_chars : 200;

// MG: if the users chooses to show no chars from posts, then we force topics view.
if ($return_chars == 0)
{
	$show_results = 'topics';
}

if (isset($_POST['is_ajax']) || isset($_GET['is_ajax']))
{
	$is_ajax = (isset($_POST['is_ajax'])) ? intval($_POST['is_ajax']) : intval($_GET['is_ajax']);
}
else
{
	$is_ajax = 0;
}

if ($show_results == 'topics')
{
	$header_html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
	$template->assign_vars(array(
		'SEARCH_HEADER' => $header_html,
		)
	);
}

if (isset($_POST['search_terms']) || isset($_GET['search_terms']))
{
	$search_terms = (isset($_POST['search_terms'])) ? $_POST['search_terms'] : $_GET['search_terms'];
}
else
{
	//$search_terms = 'any';
	$search_terms = 0;
}
$search_terms = ($search_terms == 'all') ? 1 : 0;

$search_fields_types = array('all', 'titleonly', 'msgonly');
$search_fields = $search_fields_types[0];

if (isset($_POST['search_fields']) || isset($_GET['search_fields']))
{
	$search_fields = (isset($_POST['search_fields'])) ? htmlspecialchars($_POST['search_fields']) : htmlspecialchars($_GET['search_fields']);
}

if (!in_array($search_fields, $search_fields_types))
{
	$search_fields = $search_fields_types[0];
}

if (isset($_POST['search_cat']) || isset($_GET['search_cat']))
{
	$search_cat = (isset($_POST['search_cat'])) ? intval($_POST['search_cat']) : intval($_GET['search_cat']);
}
else
{
	$search_cat = -1;
}

if (isset($_POST['search_thanks']) || isset($_GET['search_thanks']))
{
	$search_thanks = (isset($_POST['search_thanks'])) ? intval($_POST['search_thanks']) : intval($_GET['search_thanks']);
}
else
{
	$search_thanks = '0';
}

$search_thanks = (($search_thanks >= '2') && ($board_config['disable_thanks_topics'] == false)) ? $search_thanks : false;

$search_where = (isset($_POST['search_where'])) ? $_POST['search_where'] : 'Root';

if (isset($_POST['sort_by']) || isset($_GET['sort_by']))
{
	$sort_by = (isset($_POST['sort_by'])) ? intval($_POST['sort_by']) : intval($_GET['sort_by']);
}
else
{
	$sort_by = 0;
}

if (isset($_POST['sort_dir']) || isset($_GET['sort_dir']))
{
	$sort_dir = (isset($_POST['sort_dir'])) ? $_POST['sort_dir'] : $_GET['sort_dir'];
}
else
{
	$sort_dir = 'DESC';
}
$sort_dir = ($sort_dir == 'ASC') ? 'ASC' : 'DESC';

$psort_types = array('time', 'cat');
$psort = $psort_types[0];
if(isset($_GET['psort']) || isset($_POST['psort']))
{
	$psort = (isset($_GET['psort'])) ? $_GET['psort'] : $_POST['psort'];
}

if (!in_array($psort, $psort_types))
{
	$psort = $psort_types[0];
}

if (!empty($_POST['search_time']) || !empty($_GET['search_time']))
{
	$search_time = time() - (((!empty($_POST['search_time'])) ? intval($_POST['search_time']) : intval($_GET['search_time'])) * 86400);
	$topic_days = (!empty($_POST['search_time'])) ? intval($_POST['search_time']) : intval($_GET['search_time']);
}
else
{
	$search_time = 0;
	$topic_days = 0;
}
if (isset($_POST['d']) || isset($_GET['d']))
{
	$search_date = (isset($_POST['d'])) ? intval($_POST['d']) : intval($_GET['d']);
}
else
{
	$search_date = 0;
}

$start = isset($_POST['start']) ? intval($_POST['start']) : (isset($_GET['start']) ? intval($_GET['start']) : 0);
$start = ($start < 0) ? 0 : $start;

$page_number = (isset($_GET['page_number']) ? intval($_GET['page_number']) : (isset($_POST['page_number']) ? intval($_POST['page_number']) : false));
$page_number = ($page_number < 1) ? false : $page_number;

$start = (!$page_number) ? $start : (($page_number * $board_config['topics_per_page']) - $board_config['topics_per_page']);

$sort_by_types = array($lang['Sort_Time'], $lang['Sort_Post_Subject'], $lang['Sort_Topic_Title'], $lang['Sort_Author'], $lang['Sort_Forum']);
// Start Advanced IP Tools Pack MOD
$search_ip = '';

// For security reasons, we need to make sure the IP lookup is coming from an admin or mod.
if (($userdata['user_level'] == ADMIN) || ($userdata['user_level'] == MOD))
{
	if (!empty($_POST['search_ip']) || !empty($_GET['search_ip']))
	{
		$ip_address = (!empty($_POST['search_ip'])) ? $_POST['search_ip'] : $_GET['search_ip'];
		$ip_address = htmlspecialchars($ip_address);
		$ip_address = str_replace("\'", "''", $ip_address);
		$ip_pieces = explode('.', $ip_address);
		$ip_pieces_count = count($ip_pieces) - 1;

			for ($i = 0; $i <= $ip_pieces_count; $i++)
			{
				$search_ip .= ($ip_pieces[$i] == '*') ? '%' : sprintf('%02x', $ip_pieces[$i]);
			}
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
if (($search_mode == 'bookmarks') && ($mode == 'removebm'))
{
	// Delete Bookmarks
	$delete = (isset($_POST['delete'])) ? true : false;
	if ($delete && isset($_POST['topic_id_list']))
	{
		$topics = $_POST['topic_id_list'];
		for($i = 0; $i < count($topics); $i++)
		{
			$topic_list .= (($topic_list != '') ? ', ' : '') . intval($topics[$i]);
		}
		if ($userdata['session_logged_in'])
		{
			remove_bookmark($topic_list);
		}
		else
		{
			redirect(append_sid(LOGIN_MG . '?redirect=' . SEARCH_MG . '?search_id=bookmarks', true));
		}
	}
	// Reset settings
	$mode = '';
}

if ($mode == 'searchuser')
{
	// This handles the simple windowed user search functions called from various other scripts
	if (isset($_POST['search_username']) || isset($_GET['search_username']))
	{
		username_search((!empty($_POST['search_username'])) ? $_POST['search_username'] : $_GET['search_username']);
	}
	else
	{
		username_search('');
	}
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
				if ($userdata['session_logged_in'])
				{
					#$sql = "SELECT post_id
						#FROM " . POSTS_TABLE . "
						#WHERE post_time >= " . $userdata['user_lastvisit'];
				}
				else
				{
					redirect(append_sid(LOGIN_MG . '?redirect=' . SEARCH_MG . '&search_id=newposts', true));
				}

				$show_results = 'topics';
*/
// -------------------------------------------------
				if ($userdata['session_logged_in'])
				{
//<!-- BEGIN Unread Post Information to Database Mod -->
					if(!$userdata['upi2db_access'] || $search_id == 'newposts')
					{
						$sql = "SELECT post_id
							FROM " . POSTS_TABLE . "
							WHERE post_time >= " . $userdata['user_lastvisit'];
					}
					else
					{
						if($search_id == 'upi2db')
						{
							switch($s2)
							{
								case 'perm':
								$sql_where = (count($unread['always_read']['topics']) == 0) ? 0 : implode(',', $unread['always_read']['topics']);
								break;

								case 'new':
								$sql_where = (count($unread['new_posts']) == 0) ? 0 : implode(',', $unread['new_posts']);
								$sql_where2 = (count($unread['edit_posts']) == 0) ? 0 : implode(',', $unread['edit_posts']);
								break;

								case 'mark':
								$sql_where = (count($unread['mark_posts']) == 0) ? 0 : implode(',', $unread['mark_posts']);
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
								redirect(append_sid(FORUM_MG));
							}
						}
					}
//<!-- END Unread Post Information to Database Mod -->
				}
				else
				{
					redirect(append_sid(LOGIN_MG . '?redirect=' . SEARCH_MG . '&search_id=newposts', true));
				}
//<!-- BEGIN Unread Post Information to Database Mod -->
				if(($search_id == 'upi2db') && ($s2 == 'mark'))
				{
					$show_results = 'posts';
				}
				else
				{
					$show_results = 'topics';
				}
//<!-- END Unread Post Information to Database Mod -->
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
			elseif ($search_id == 'egosearch')
			{
				if ($userdata['session_logged_in'])
				{
					$sql = "SELECT post_id
						FROM " . POSTS_TABLE . "
						WHERE poster_id = " . $userdata['user_id'];
				}
				else
				{
					redirect(append_sid(LOGIN_MG . '?redirect=' . SEARCH_MG . '&search_id=egosearch', true));
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
				$nix_tomorrow = mktime (0, 0, 0, date('m', $search_date), date('d', $search_date) + 1, date('Y', $search_date));
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
				$search_author = str_replace('*', '%', trim($search_author));
				if(!$only_bluecards && (strpos($search_author, '%') !== false) && (strlen(str_replace('%', '', $search_author)) < $board_config['search_min_chars']))
				{
					$search_author = '';
				}

				$sql = "SELECT user_id
					FROM " . USERS_TABLE . "
					WHERE username LIKE '" . str_replace("\'", "''", $search_author) . "'";
				if (!($result = $db->sql_query($sql)))
				{
					message_die(GENERAL_ERROR, "Couldn't obtain list of matching users (searching for: $search_author)", "", __LINE__, __FILE__, $sql);
				}

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
				if ($search_topic_starter == true)
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

			if (!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'Could not obtain matched posts list', '', __LINE__, __FILE__, $sql);
			}

			$search_ids = array();
			while($row = $db->sql_fetchrow($result))
			{
				$search_ids[] = $row['post_id'];
			}
			$db->sql_freeresult($result);

			$total_match_count = count($search_ids);

		}
		elseif ($search_keywords != '')
		{
			$stopword_array = @file(IP_ROOT_PATH . 'language/lang_' . $board_config['default_lang'] . '/search_stopwords.txt');
			$synonym_array = @file(IP_ROOT_PATH . 'language/lang_' . $board_config['default_lang'] . '/search_synonyms.txt');

			$split_search = array();
			$stripped_keywords = stripslashes($search_keywords);
			$split_search = (!strstr($multibyte_charset, $lang['ENCODING'])) ? split_words(clean_words('search', $stripped_keywords, $stopword_array, $synonym_array), 'search') : split(' ', $search_keywords);
			unset($stripped_keywords);

			$word_count = 0;
			$current_match_type = 'or';

			$word_match = array();
			$result_list = array();

			for($i = 0; $i < count($split_search); $i++)
			{
				if (!$only_bluecards && strlen(str_replace(array('*', '%'), '', trim($split_search[$i]))) < $board_config['search_min_chars'])
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
								WHERE w.word_text LIKE '$match_word'
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
								$search_add_sql = "p.post_text LIKE '$match_word'";
							}
							elseif ($search_fields == 'titleonly')
							{
								$search_add_sql = "p.post_subject LIKE '$match_word'";
							}
							else
							{
								$search_add_sql = "p.post_text LIKE '$match_word' OR p.post_subject LIKE '$match_word'";
							}
							$search_add_sql .= ($only_bluecards) ? " AND p.post_bluecard > 0" : '';
							$sql = "SELECT p.post_id
								FROM " . POSTS_TABLE . " p
								WHERE " . $search_add_sql;
						}
						if (!($result = $db->sql_query($sql)))
						{
							message_die(GENERAL_ERROR, 'Could not obtain matched posts list', '', __LINE__, __FILE__, $sql);
						}

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
			$total_match_count = count($search_ids);
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
		for ($i = 0; $i < count($keys['id']); $i++)
		{
			if (($tree['type'][ $keys['idx'][$i] ] == POST_FORUM_URL) && $tree['auth'][ $keys['id'][$i] ]['auth_read'])
			{
				$s_flist .= (($s_flist != '') ? ', ' : '') . $tree['id'][ $keys['idx'][$i] ];
			}
		}
		if ($s_flist != '')
		{
			$auth_sql .= (($auth_sql != '') ? " AND" : '') . " f.forum_id IN ($s_flist) ";
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
			$search_author = str_replace('*', '%', trim(str_replace("\'", "''", $search_author)));
		}

		if ($total_match_count)
		{
			if ($show_results == 'topics')
			{
				// This one is a beast, try to seperate it a bit (workaround for connection timeouts)
				$search_id_chunks = array();
				$count = 0;
				$chunk = 0;

				if (count($search_ids) > $limiter)
				{
					for ($i = 0; $i < count($search_ids); $i++)
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

				for ($i = 0; $i < count($search_id_chunks); $i++)
				{
					$where_sql = '';

					if ($search_time)
					{
						$where_sql .= ($search_author == '' && $auth_sql == '') ? " AND post_time >= $search_time " : " AND p.post_time >= $search_time ";
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
							$from_sql .= ", " . USERS_TABLE . " u";
							$where_sql .= " AND u.user_id = p.poster_id AND u.username LIKE '$search_author' ";
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

					if (!($result = $db->sql_query($sql)))
					{
						message_die(GENERAL_ERROR, 'Could not obtain topic ids', '', __LINE__, __FILE__, $sql);
					}

					while ($row = $db->sql_fetchrow($result))
					{
						$search_ids[] = $row['topic_id'];
					}
					$db->sql_freeresult($result);
				}

				$total_match_count = count($search_ids);

			}
			elseif ($search_author != '' || $search_time || $auth_sql != '')
			{
				$search_id_chunks = array();
				$count = 0;
				$chunk = 0;

				if (count($search_ids) > $limiter)
				{
					for ($i = 0; $i < count($search_ids); $i++)
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

				for ($i = 0; $i < count($search_id_chunks); $i++)
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
						$where_sql .= " AND u.user_id = p.poster_id AND u.username LIKE '$search_author'";
					}

					$sql = "SELECT " . $select_sql . "
						FROM $from_sql
						WHERE $where_sql";
					if (!($result = $db->sql_query($sql)))
					{
						message_die(GENERAL_ERROR, 'Could not obtain post ids', '', __LINE__, __FILE__, $sql);
					}

					while($row = $db->sql_fetchrow($result))
					{
						$search_ids[] = $row['post_id'];
					}
					$db->sql_freeresult($result);
				}

				$total_match_count = count($search_ids);
			}
		}
		elseif ($search_thanks != false)
		{
			if ($userdata['session_logged_in'])
			{
				if ($auth_sql != '')
				{
					$sql = "SELECT DISTINCT(t.topic_id), f.forum_id
									FROM " . THANKS_TABLE . " th, " . TOPICS_TABLE . " t, " . FORUMS_TABLE . " f
									WHERE t.topic_poster = '" . $search_thanks . "'
										AND t.topic_id = th.topic_id
										AND t.forum_id = f.forum_id
										AND $auth_sql";
				}
				else
				{
					$sql = "SELECT DISTINCT(t.topic_id)
									FROM " . THANKS_TABLE . " th, " . TOPICS_TABLE . " t
									WHERE t.topic_poster = '" . $search_thanks . "'
										AND t.topic_id = th.topic_id";
				}
			}
			else
			{
				redirect(append_sid(LOGIN_MG . '?redirect=' . SEARCH_MG . '&search_thanks=' . $search_thanks, true));
			}

			if (!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'Could not obtain topics ids', '', __LINE__, __FILE__, $sql);
			}

			$search_ids = array();
			while($row = $db->sql_fetchrow($result))
			{
				$search_ids[] = $row['topic_id'];
			}
			$db->sql_freeresult($result);

			$total_match_count = count($search_ids);
			if ($total_match_count <= $start) // No results for the selected page
			{
				$start = $total_match_count - 1;
				$start = intval($start / $board_config['topics_per_page']) * $board_config['topics_per_page'];
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

			if (!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'Could not obtain topics ids', '', __LINE__, __FILE__, $sql);
			}

			$search_ids = array();
			while($row = $db->sql_fetchrow($result))
			{
				$search_ids[] = $row['topic_id'];
			}
			$db->sql_freeresult($result);

			$total_match_count = count($search_ids);

			// Basic requirements
			$show_results = 'topics';
			$sort_by = 0;
			$sort_dir = 'DESC';
		}
		elseif ($search_id == 'bookmarks')
		{
			if ($userdata['session_logged_in'])
			{
				if ($auth_sql != '')
				{
					$sql = "SELECT t.topic_id, f.forum_id
						FROM " . TOPICS_TABLE . " t, " . BOOKMARK_TABLE . " b, " . FORUMS_TABLE . " f
						WHERE t.topic_id = b.topic_id
							AND t.forum_id = f.forum_id
							AND b.user_id = " . $userdata['user_id'] . "
							AND $auth_sql";
				}
				else
				{
					$sql = "SELECT t.topic_id
						FROM " . TOPICS_TABLE . " t, " . BOOKMARK_TABLE . " b
						WHERE t.topic_id = b.topic_id
							AND b.user_id = " . $userdata['user_id'];
				}
			}
			else
			{
				redirect(append_sid(LOGIN_MG. '?redirect=' . SEARCH_MG . '?search_id=bookmarks', true));
			}

			if (!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'Could not obtain topics id', '', __LINE__, __FILE__, $sql);
			}

			$search_ids = array();
			while($row = $db->sql_fetchrow($result))
			{
				$search_ids[] = $row['topic_id'];
			}
			$db->sql_freeresult($result);

			$total_match_count = count($search_ids);
			if ($total_match_count <= $start) // No results for the selected page
			{
				$start = $total_match_count - 1;
				$start = intval($start / $board_config['topics_per_page']) * $board_config['topics_per_page'];
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

		// Delete old data from the search result table
		$sql = 'DELETE FROM ' . SEARCH_TABLE . '
			WHERE search_time < ' . ($current_time - (int) $board_config['session_length']);
		if (!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not delete old search id sessions', '', __LINE__, __FILE__, $sql);
		}

		// Store new result data
		$search_results = implode(', ', $search_ids);
		$per_page = ($show_results == 'posts') ? $board_config['posts_per_page'] : $board_config['topics_per_page'];

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

		for($i = 0; $i < count($store_vars); $i++)
		{
			$store_search_data[$store_vars[$i]] = $$store_vars[$i];
		}

		$result_array = serialize($store_search_data);
		unset($store_search_data);

		mt_srand ((double) microtime() * 1000000);
		$search_id = mt_rand();

		$sql = "UPDATE " . SEARCH_TABLE . "
			SET search_id = $search_id, search_time = $current_time, search_array = '" . str_replace("\'", "''", $result_array) . "'
			WHERE session_id = '" . $userdata['session_id'] . "'";
		if (!($result = $db->sql_query($sql)) || !$db->sql_affectedrows())
		{
			$sql = "INSERT INTO " . SEARCH_TABLE . " (search_id, session_id, search_time, search_array)
				VALUES($search_id, '" . $userdata['session_id'] . "', $current_time, '" . str_replace("\'", "''", $result_array) . "')";
			if (!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'Could not insert search results', '', __LINE__, __FILE__, $sql);
			}
		}
	}
	else
	{
		$search_id = intval($search_id);
		if ($search_id)
		{
			$sql = "SELECT search_array
				FROM " . SEARCH_TABLE . "
				WHERE search_id = $search_id
					AND session_id = '" . $userdata['session_id'] . "'";
			if (!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'Could not obtain search results', '', __LINE__, __FILE__, $sql);
			}

			if ($row = $db->sql_fetchrow($result))
			{
				$search_data = unserialize($row['search_array']);
				$psort_main = $psort;
				for($i = 0; $i < count($store_vars); $i++)
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
		//$this_auth = auth(AUTH_ALL, AUTH_LIST_ALL, $userdata);

		if ($show_results == 'posts')
		{
			$sql = "SELECT p.*, f.forum_id, f.forum_name, t.*, u.username, u.user_id, u.user_sig
				FROM " . FORUMS_TABLE . " f, " . TOPICS_TABLE . " t, " . USERS_TABLE . " u, " . POSTS_TABLE . " p
				WHERE p.post_id IN ($search_results)
					AND f.forum_id = p.forum_id
					AND p.topic_id = t.topic_id
					AND p.poster_id = u.user_id";
		}
		else
		{
			$sql = "SELECT t.*, f.forum_id, f.forum_name, u.username, u.user_id, u2.username as user2, u2.user_id as id2, p.post_username, p2.post_username AS post_username2, p2.post_time
				FROM " . TOPICS_TABLE . " t, " . FORUMS_TABLE . " f, " . USERS_TABLE . " u, " . POSTS_TABLE . " p, " . POSTS_TABLE . " p2, " . USERS_TABLE . " u2
				WHERE t.topic_id IN ($search_results)
					AND t.topic_poster = u.user_id
					AND f.forum_id = t.forum_id
					AND p.post_id = t.topic_first_post_id
					AND p2.post_id = t.topic_last_post_id
					AND u2.user_id = p2.poster_id";
		}

		$per_page = ($show_results == 'posts') ? $board_config['posts_per_page'] : $board_config['topics_per_page'];

		$sql .= " ORDER BY ";

		if ($psort == 'cat')
		{
			$sql .= 'f.forum_id ASC, ';
		}

		switch ($sort_by)
		{
			case 1:
				$sql .= ($show_results == 'posts') ? 'p.post_subject' : 't.topic_title';
				break;
			case 2:
				$sql .= 't.topic_title';
				break;
			case 3:
				$sql .= 'u.username';
				break;
			case 4:
				$sql .= 'f.forum_id';
				break;
			default:
				$sql .= ($show_results == 'posts') ? 'p.post_time' : 'p2.post_time';
				break;
		}
		$sql .= " $sort_dir LIMIT $start, " . $per_page;

		if (!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not obtain search results', '', __LINE__, __FILE__, $sql);
		}

		/* UPI2DB REPLACE
		$searchset = array();
		while($row = $db->sql_fetchrow($result))
		{
			$searchset[] = $row;
		}
		*/

//<!-- Begin Unread Post Information to Database Mod -->
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
			if($userdata['upi2db_access'])
			{
				if($board_config['upi2db_edit_topic_first'])
				{
					if(isset($unread['edit_topics']) && in_array($row['topic_id'], $unread['edit_topics']) && $row['topic_type'] == POST_GLOBAL_ANNOUNCE)
					{
						$searchset_gae[] = $row;
					}
					elseif($row['topic_type'] == POST_GLOBAL_ANNOUNCE)
					{
						$searchset_gan[] = $row;
					}
					elseif(isset($unread['edit_topics']) && in_array($row['topic_id'], $unread['edit_topics']) && $row['topic_type'] == POST_ANNOUNCE)
					{
						$searchset_ae[] = $row;
					}
					elseif($row['topic_type'] == POST_ANNOUNCE)
					{
						$searchset_an[] = $row;
					}
					elseif(isset($unread['edit_topics']) && in_array($row['topic_id'], $unread['edit_topics']) && $row['topic_type'] == POST_STICKY)
					{
						$searchset_se[] = $row;
					}
					elseif($row['topic_type'] == POST_STICKY)
					{
						$searchset_sn[] = $row;
					}
					elseif(isset($unread['edit_topics']) && in_array($row['topic_id'], $unread['edit_topics']) && $row['topic_type'] != POST_GLOBAL_ANNOUNCE && $row['topic_type'] != POST_ANNOUNCE && $row['topic_type'] != POST_STICKY)
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
		if($userdata['upi2db_access'])
		{
			if($board_config['upi2db_edit_topic_first'])
			{
				$searchset = array_merge($searchset_gae, $searchset_gan, $searchset_ae, $searchset_an, $searchset_se, $searchset_sn, $searchset_e, $searchset_n);
			}
			else
			{
				$searchset = array_merge($searchset_gan, $searchset_an, $searchset_sn, $searchset_n);
			}
		}
//<!-- END Unread Post Information to Database Mod -->

		$db->sql_freeresult($result);

		// Define censored word matches
		$orig_word = array();
		$replacement_word = array();
		obtain_word_list($orig_word, $replacement_word);

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
				$result_title .= '<a href="' . append_sid(VIEWTOPIC_MG . '?' . POST_TOPIC_URL . '=' . $searchset[$sr]['topic_id'] . '&highlight=' . $search_keywords) . '">' . $searchset[$sr]['topic_title'] . '</a><br />';
			}

			$result_ar = array(
				'results' => $result_title,
				'error_msg' => $search_result_text
			);
			AJAX_message_die($result_ar);
		}

		// Output header
		if ($show_results == 'bookmarks')
		{
			$nav_main_lang = $lang['Bookmarks'];
			$nav_main_url = append_sid('search.' . PHP_EXT . '?search_id=bookmarks');
			$l_search_matches = ($total_match_count == 1) ? sprintf($lang['Found_bookmark'], $total_match_count) : sprintf($lang['Found_bookmarks'], $total_match_count);
		}
		else
		{
			$nav_main_lang = $lang['Search'];
			$nav_main_url = append_sid('search.' . PHP_EXT);
			$l_search_matches = ($total_match_count == 1) ? sprintf($lang['Found_search_match'], $total_match_count) : sprintf($lang['Found_search_matches'], $total_match_count);
		}
		$page_title = $nav_main_lang;
		$meta_description = '';
		$meta_keywords = '';
		$nav_server_url = create_server_url();
		$breadcrumbs_address = $lang['Nav_Separator'] . '<a href="' . $nav_server_url . $nav_main_url . '" class="nav-current">' . $nav_main_lang . '</a>';
		$breadcrumbs_links_right = '<span class="gensmall">' . $l_search_matches . '</span>';
		include_once(IP_ROOT_PATH . 'includes/users_zebra_block.' . PHP_EXT);
		include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);

		if ($show_results == 'bookmarks')
		{
			$template->set_filenames(array('body' => 'search_results_bookmarks.tpl'));
		}
		elseif ($show_results == 'posts')
		{
			$template->set_filenames(array('body' => 'search_results_posts.tpl'));
		}
		else
		{
			$template->set_filenames(array('body' => 'search_results_topics.tpl'));
		}
		make_jumpbox(VIEWFORUM_MG);

		if ($show_results == 'bookmarks')
		{
			// Send variables for bookmarks
			//$s_hidden_fields = '<input type="hidden" name="mode" value="removebm" />';
			$template->assign_vars(array(
				'L_DELETE' => $lang['Delete'],
				'S_BM_ACTION' => append_sid(SEARCH_MG . '?search_id=bookmarks&amp;mode=removebm&amp;start=' . $start),
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
		for($j = 0; $j < count($split_search); $j++)
		{
			$split_word = $split_search[$j];

			if ($split_word != 'and' && $split_word != 'or' && $split_word != 'not')
			{
				$highlight_match[] = '#\b(' . str_replace("*", "([\w]+)?", $split_word) . ')\b#is';
				// Added by MG: creation of $highlight_match_string
				$words[] = $split_word;
				$highlight_active .= " " . $split_word;

				for ($k = 0; $k < count($synonym_array); $k++)
				{
					list($replace_synonym, $match_synonym) = split(' ', trim(strtolower($synonym_array[$k])));

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

		// Added by MG: creation of $highlight_match_string
		for($i = 0; $i < count($words); $i++)
		{
			$highlight_match_string .= (($highlight_match_string != '') ? '|' : '') . str_replace('*', '\w*', preg_quote($words[$i], '#'));
		}
		$highlight_match_string = phpbb_rtrim($highlight_match_string, "\\");

		$highlight_active = urlencode(trim($highlight_active));

		$tracking_topics = (isset($_COOKIE[$board_config['cookie_name'] . '_t'])) ? unserialize($_COOKIE[$board_config['cookie_name'] . '_t']) : array();
		$tracking_forums = (isset($_COOKIE[$board_config['cookie_name'] . '_f'])) ? unserialize($_COOKIE[$board_config['cookie_name'] . '_f']) : array();

		if ($show_results == 'posts')
		{
			if ($search_where == -1)
			{
				$is_auth_ary = auth(AUTH_ALL, AUTH_LIST_ALL, $userdata);
			}
			else
			{
				$is_auth = auth(AUTH_ALL, $search_where, $userdata);
			}
		}

		// MG User Replied - BEGIN
		// check if user replied to the topics
		define('USER_REPLIED_ICON', true);
		$user_topics = user_replied_array($searchset);
		// MG User Replied - END

		for($i = 0; $i < count($searchset); $i++)
		{
			// CrackerTracker v5.x
			$sucheck = strtolower($highlight_active);
			$sucheck = str_replace($ct_rules, '*', $sucheck);
			if($sucheck != $highlight_active)
			{
				$highlight_active = '';
			}
			// CrackerTracker v5.x

			$forum_id = $searchset[$i]['forum_id'];
			$topic_id = $searchset[$i]['topic_id'];
			$post_id = $searchset[$i]['post_id'];
			$forum_id_append = (!empty($forum_id) ? (POST_FORUM_URL . '=' . $forum_id) : '');
			$topic_id_append = (!empty($topic_id) ? (POST_TOPIC_URL . '=' . $topic_id) : '');
			$post_id_append = (!empty($post_id) ? (POST_POST_URL . '=' . $post_id) : '');
			$post_id_append_url = (!empty($post_id) ? ('#p' . $post_id) : '');
			$forum_url = append_sid(VIEWFORUM_MG . '?' . $forum_id_append);
			$topic_url = append_sid(VIEWTOPIC_MG . '?' . $forum_id_append . '&amp;' . $topic_id_append . '&amp;highlight=' . $highlight_active);
			$post_url = append_sid(VIEWTOPIC_MG . '?' . $forum_id_append . '&amp;' . $topic_id_append . '&amp;' . $post_id_append . '&amp;highlight=' . $highlight_active) . $post_id_append_url;
			$user_replied = (!empty($user_topics) && isset($user_topics[$topic_id]));

			$post_date = create_date2($board_config['default_dateformat'], $searchset[$i]['post_time'], $board_config['board_timezone']);

			$message = $searchset[$i]['post_text'];
			$message_compiled = empty($searchset[$i]['post_text_compiled']) ? false : $searchset[$i]['post_text_compiled'];
			$topic_title = $searchset[$i]['topic_title'];
			$topic_title_prefix = (empty($searchset[$i]['title_compl_infos'])) ? '' : $searchset[$i]['title_compl_infos'] . ' ';
			// AJAX Not Applied
			//$topic_raw_title = $topic_title;

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
					$bbcode->allow_html = $board_config['allow_html'] && $searchset[$i]['enable_html'];
					$bbcode->allow_bbcode = $board_config['allow_bbcode'] && $searchset[$i]['enable_bbcode'];
					$bbcode->allow_smilies = $board_config['allow_smilies'] && $searchset[$i]['enable_smilies'];
					$GLOBALS['code_post_id'] = $searchset[$i]['post_id'];
					$message = $bbcode->parse($message, '', false, $clean_tags);
					$GLOBALS['code_post_id'] = 0;
				}
				else
				{
					$message = $message_compiled;
				}

				if ($return_chars != -1)
				{
					$message = (strlen($message) > $return_chars) ? substr($message, 0, $return_chars) . ' ...' : $message;
				}

				if ($highlight_active)
				{
					// Replaced by MG: creation of $highlight_match_string
					$message = preg_replace('#(?!<.*)(?<!\w)(' . $highlight_match_string . ')(?!\w|[^<>]*>)#i', '<span class="highlight-w"><b>\1</b></span>', $message);
				}

				if (count($orig_word))
				{
					$topic_title = preg_replace($orig_word, $replacement_word, $topic_title);
					$topic_raw_title = preg_replace($orig_word, $replacement_word, $topic_raw_title);
					$post_subject = ($searchset[$i]['post_subject'] != "") ? preg_replace($orig_word, $replacement_word, $searchset[$i]['post_subject']) : $topic_title_prefix . $topic_title;
					// AJAX Not Applied
					//$post_subject = ($searchset[$i]['post_subject'] != "") ? preg_replace($orig_word, $replacement_word, $searchset[$i]['post_subject']) : '';

					$message = preg_replace($orig_word, $replacement_word, $message);
				}
				else
				{
					$post_subject = ($searchset[$i]['post_subject'] != '') ? $searchset[$i]['post_subject'] : $topic_title_prefix . $topic_title;
					// AJAX Not Applied
					//$post_subject = $searchset[$i]['post_subject'];
				}

				// AJAX Not Applied
				/*
				$is_firstpost = ($searchset[$i]['post_id'] == $searchset[$i]['topic_first_post_id']) ? 1 : 0;
				$edit_url = '';
				$edit_img = '';
				if ($can_edit = ($is_auth['auth_mod'] || (($searchset[$i]['user_id'] == $userdata['user_id'] && ($searchset[$i]['topic_status'] != TOPIC_LOCKED)) && $is_auth['auth_edit'])))
				{
					$raw_message = $searchset[$i]['post_text'];
					$raw_message = str_replace('<', '&lt;', $raw_message);
					$raw_message = str_replace('>', '&gt;', $raw_message);
					$raw_message = str_replace('<br />', "\n", $raw_message);

					$edit_url = append_sid('posting.' . PHP_EXT . '?mode=editpost&amp;' . POST_POST_URL . '=' . $searchset[$i]['post_id']);
					$edit_img = '<a id="editimg_'. $searchset[$i]['post_id'] .'" onclick="return AJAXPostEdit('. $searchset[$i]['post_id'] .');" href="'. $edit_url .'"><img src="'. $images['icon_edit'] .'" alt="'. $lang['Edit_delete_post'] .'" title="'. $lang['Edit_delete_post'] .'" border="0" align="right" /></a><br />';
				}
				*/

				$poster = ($searchset[$i]['user_id'] != ANONYMOUS) ? colorize_username($searchset[$i]['user_id']) : $lang['Guest'];
				//$poster .= ($searchset[$i]['user_id'] != ANONYMOUS) ? $searchset[$i]['username'] : (($searchset[$i]['post_username'] != "") ? $searchset[$i]['post_username'] : $lang['Guest']);

//<!-- BEGIN Unread Post Information to Database Mod -->
				if(!$userdata['upi2db_access'])
				{
//<!-- END Unread Post Information to Database Mod -->
					if ($userdata['session_logged_in'] && $searchset[$i]['post_time'] > $userdata['user_lastvisit'])
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
//<!-- BEGIN Unread Post Information to Database Mod -->
					$folder_image = $images['topic_nor_read'];
					$folder_alt = ($searchset[$i]['topic_status'] == TOPIC_LOCKED) ? $lang['Topic_locked'] : $lang['No_new_posts'];
				}
				else
				{
					search_calc_unread2($unread, $topic_id, $searchset, $i, $mini_post_img, $mini_post_alt, $unread_color, $folder_image, $folder_alt);
				}
				if($userdata['upi2db_access'])
				{
					if($s2 == 'mark')
					{
						$post_id = $searchset[$i]['post_id'];
						$mark_topic_unread = '<a href="' . append_sid(SEARCH_MG . '?search_id=upi2db&amp;s2=mark&amp;' . POST_TOPIC_URL . '=' . $topic_id . '&amp;' . POST_FORUM_URL . '=' . $forum_id . '&amp;' . POST_POST_URL . '=' . $post_id . '&amp;do=unmark_post&amp;s2=' . $s2) . '"><img src="' . $images['unmark_img'] . '" alt="' . $lang['upi2db_unmark_post'] . '" title="' . $lang['upi2db_unmark_post'] . '" /></a>';
					}
				}
//<!-- END Unread Post Information to Database Mod -->
				// SELF AUTH - BEGIN
				// Comment the lines below if you wish to show RESERVED topics for AUTH_SELF.
				if (((($userdata['user_level'] != ADMIN) && ($userdata['user_level'] != MOD)) || (($userdata['user_level'] == MOD) && ($board_config['allow_mods_view_self'] == false))) && (intval($is_auth_ary[$searchset[$i]['forum_id']]['auth_read']) == AUTH_SELF) && ($searchset[$i]['user_id'] != $userdata['user_id']))
				{
					$poster = $lang['Reserved_Author'];
					$topic_title = $lang['Reserved_Topic'];
					$message = $lang['Reserved_Post'];
				}
				else
				{
					$topic_title = $topic_title_prefix . $topic_title;
				}
				// SELF AUTH - END
				// Convert and clean special chars!
				$topic_title = htmlspecialchars_clean($topic_title);
				$template->assign_block_vars('searchresults', array(
					'TOPIC_TITLE' => $topic_title,
					'FORUM_NAME' => get_object_lang(POST_FORUM_URL . $searchset[$i]['forum_id'], 'name'),
					//'POST_SUBJECT' => $post_subject,
					'POST_DATE' => $post_date,
					'POSTER_NAME' => $poster,
					'TOPIC_REPLIES' => $searchset[$i]['topic_replies'],
					'TOPIC_VIEWS' => $searchset[$i]['topic_views'],
					'MESSAGE' => $message,
					'MINI_POST_IMG' => $mini_post_img,
					'L_MINI_POST_ALT' => $mini_post_alt,
//<!-- BEGIN Unread Post Information to Database Mod -->
					'L_TOPIC_FOLDER_ALT' => $folder_alt,
					'TOPIC_FOLDER_IMG' => $folder_image,
					'UNREAD_COLOR' => $unread_color,
					'UNREAD_IMG' => $mark_topic_unread,
//<!-- END Unread Post Information to Database Mod -->

					// AJAX Features - BEGIN
					'TOPIC_RAW_TITLE' => $topic_raw_title,
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
//<!-- BEGIN Unread Post Information to Database Mod -->
				if($userdata['upi2db_access'])
				{
					$template->assign_block_vars('searchresults.switch_upi2db_on', array());
				}
//<!-- END Unread Post Information to Database Mod -->
			}
			else
			{
				$message = '';

				if (count($orig_word))
				{
					$topic_title = preg_replace($orig_word, $replacement_word, $searchset[$i]['topic_title']);
				}

				//$news_label = ($searchset[$i]['news_id'] > 0) ? $lang['News_Cmx'] . '' : '';
				$news_label = '';

				$views = $searchset[$i]['topic_views'];
				$replies = $searchset[$i]['topic_replies'];

				$topic_link = build_topic_icon_link($searchset[$i]['forum_id'], $searchset[$i]['topic_id'], $searchset[$i]['topic_type'], $searchset[$i]['topic_replies'], $searchset[$i]['news_id'], $searchset[$i]['topic_vote'], $searchset[$i]['topic_status'], $searchset[$i]['topic_moved_id'], $searchset[$i]['post_time'], $user_replied, $replies, $unread);

				$topic_id = $topic_link['topic_id'];
				$topic_id_append = $topic_link['topic_id_append'];

				if (($replies + 1) > $board_config['posts_per_page'])
				{
					$total_pages = ceil(($replies + 1) / $board_config['posts_per_page']);
					$goto_page = ' [ <img src="' . $images['icon_gotopost'] . '" alt="' . $lang['Goto_page'] . '" title="' . $lang['Goto_page'] . '" />' . $lang['Goto_page'] . ': ';

					$times = 1;
					for($j = 0; $j < $replies + 1; $j += $board_config['posts_per_page'])
					{
						$goto_page .= '<a href="' . append_sid(VIEWTOPIC_MG . '?' . $forum_id_append . '&amp;' . $topic_id_append . '&amp;start=' . $j) . '">' . $times . '</a>';
						if (($times == 1) && ($total_pages > 4))
						{
							$goto_page .= ' ... ';
							$times = $total_pages - 3;
							$j += ($total_pages - 4) * $board_config['posts_per_page'];
						}
						elseif ($times < $total_pages)
						{
							$goto_page .= ', ';
						}
						$times++;
					}
					$goto_page .= ' ] ';
				}
				else
				{
					$goto_page = '';
				}

				if ($searchset[$i]['user_id'] != ANONYMOUS)
				{
					$topic_author = colorize_username($searchset[$i]['user_id']);
				}
				else
				{
					$sql = "SELECT p.post_username FROM " . TOPICS_TABLE . " t, " . POSTS_TABLE . " p
						WHERE t.topic_first_post_id = p.post_id
						AND t.topic_id = " . $searchset[$i]['topic_id'];
					if (!$result = $db->sql_query($sql))
					{
						message_die(GENERAL_ERROR, 'Could not get topic autor name', '', __LINE__, __FILE__, $sql);
					}
					$row = $db->sql_fetchrow($result);
					$topic_author_name = $row['post_username'];
					$topic_author = ($topic_author_name != '') ? $topic_author_name : $lang['Guest'];
				}

				$first_post_time = create_date($board_config['default_dateformat'], $searchset[$i]['topic_time'], $board_config['board_timezone']);
				$last_post_time = create_date2($board_config['default_dateformat'], $searchset[$i]['post_time'], $board_config['board_timezone']);
				$last_post_author = ($searchset[$i]['id2'] == ANONYMOUS) ? (($searchset[$i]['post_username2'] != '') ? $searchset[$i]['post_username2'] . ' ' : $lang['Guest'] . ' ') : colorize_username($searchset[$i]['id2']);

				$last_post_url = '<a href="' . append_sid(VIEWTOPIC_MG . '?' . $forum_id_append . '&amp;' . $topic_id_append . '&amp;' . POST_POST_URL . '=' . $searchset[$i]['topic_last_post_id']) . '#p' . $searchset[$i]['topic_last_post_id'] . '"><img src="' . $images['icon_latest_reply'] . '" alt="' . $lang['View_latest_post'] . '" title="' . $lang['View_latest_post'] . '" /></a>';
//<!-- BEGIN Unread Post Information to Database Mod -->
				if($userdata['upi2db_access'])
				{
					$mark_always_read = mark_always_read($searchset[$i]['topic_type'], $topic_id, $forum_id, 'search', 'icon', $unread, $start, $topic_link['image'], $search_mode, $s2);
				}
				else
				{
					$mark_always_read = '<img src="' . $topic_link['image'] . '" alt="' . $topic_link['image_alt'] . '" title="' . $topic_link['image_alt'] . '" />';
				}
				$tt = $searchset[$i]['topic_type'];
//<!-- END Unread Post Information to Database Mod -->

				$mark_link_start = '';//($userdata['session_logged_in']) ? '<a onclick="return AJAXMarkTopic('. $topic_id .');" href="#">' : '';
				$mark_link_end = '';//($userdata['session_logged_in']) ? '</a>' : '';
				//$this_auth = ($search_forums == -1) ? $is_auth : $is_auth_ary[$forum_id];
				$this_auth2 = $this_auth[$searchset[$i]['forum_id']];
				$can_edit = ($this_auth2['auth_mod'] || ((($searchset[$i]['topic_poster'] == $userdata['user_id']) && ($searchset[$i]['topic_status'] != TOPIC_LOCKED)) && $this_auth2['auth_edit']));

				// SELF AUTH - BEGIN
				// Comment the lines below if you wish to show RESERVED topics for AUTH_SELF.
				if (((($userdata['user_level'] != ADMIN) && ($userdata['user_level'] != MOD)) || (($userdata['user_level'] == MOD) && ($board_config['allow_mods_view_self'] == false))) && (intval($is_auth_ary[$searchset[$i]['forum_id']]['auth_read']) == AUTH_SELF) && ($searchset[$i]['user_id'] != $userdata['user_id']))
				{
					$topic_author = $lang['Reserved_Author'];
					$last_post_author = $lang['Reserved_Author'];
					$topic_title = $lang['Reserved_Topic'];
				}
				else
				{
					$topic_title = $topic_title_prefix . $topic_title;
				}
				// SELF AUTH - END

//<!-- BEGIN Unread Post Information to Database Mod -->
				// Edited By Mighty Gorgon - BEGIN
				if (($userdata['user_level'] == ADMIN) || ($userdata['user_level'] == MOD))
				{
					$mark_read_forbid = false;
				}
				else
				{
					$mark_read_forbid = (($tt == POST_STICKY) || ($tt == POST_ANNOUNCE) || ($tt == POST_GLOBAL_ANNOUNCE)) ? true : false;
				}
				// Edited By Mighty Gorgon - END
//<!-- END Unread Post Information to Database Mod -->

				// Convert and clean special chars!
				$topic_title = htmlspecialchars_clean($topic_title);
				$template->assign_block_vars('searchresults', array(
					'ROW_CLASS' => (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'],
					'FORUM_NAME' => get_object_lang(POST_FORUM_URL . $searchset[$i]['forum_id'], 'name'),
					'FORUM_ID' => $forum_id,
					'TOPIC_ID' => $topic_id,

					'TOPIC_FOLDER_IMG' => $topic_link['image'],
					'L_TOPIC_FOLDER_ALT' => $topic_link['image_alt'],
					'TOPIC_AUTHOR' => $topic_author,
					'TOPIC_TITLE' => $topic_title,
					'TOPIC_TYPE' => $topic_link['type'],
					'TOPIC_TYPE_ICON' => $topic_link['icon'],
					'TOPIC_CLASS' => (!empty($topic_link['class_new']) ? ('topiclink' . $topic_link['class_new']) : $topic_link['class']),
					'CLASS_NEW' => $topic_link['class_new'],
					'NEWEST_POST_IMG' => $topic_link['newest_post_img'],
					'L_NEWS' => $news_label,
					'TOPIC_ATTACHMENT_IMG' => topic_attachment_image($searchset[$i]['topic_attachment']),

					'GOTO_PAGE' => $goto_page,
					'REPLIES' => $replies,
					'VIEWS' => $views,
					'FIRST_POST_TIME' => $first_post_time,
					'LAST_POST_TIME' => $last_post_time,
					'LAST_POST_AUTHOR' => $last_post_author,
					'LAST_POST_IMG' => $last_post_url,
//<!-- BEGIN Unread Post Information to Database Mod -->
					'NO_AGM' => ($mark_read_forbid || ($s2 == 'perm')) ? 'disabled' : '',
					'U_MARK_ALWAYS_READ' => $mark_always_read,
//<!-- END Unread Post Information to Database Mod -->
					'U_VIEW_FORUM' => $forum_url,
					'U_VIEW_TOPIC' => $topic_url
					)
				);
//<!-- BEGIN Unread Post Information to Database Mod -->
				if($userdata['upi2db_access'])
				{
					$template->assign_block_vars('searchresults.switch_upi2db_on', array());
				}
//<!-- END Unread Post Information to Database Mod -->
			}
		}

		$base_url = SEARCH_MG . '?search_id=' . $search_id . '&amp;psort=' . $psort;
		$search_url_add = ($start > 0) ? ('&amp;start=' . $start) : '';
		$search_url_add .= ($s2 == 'new') ? ('&amp;s2=' . $s2) : '';
		$s_hidden_fields = '';
		$s_hidden_fields .= '<input type="hidden" name="search_id" value="' . $search_id . '" />';
		$s_hidden_fields .= '<input type="hidden" name="search_mode" value="' . $search_mode . '" />';
		$l_forum = (($show_results == 'topics') ? ('<a href="' . append_sid(SEARCH_MG . '?search_id=' . $search_id . $search_url_add . '&amp;psort=cat') . '">' . $lang['Forum'] . '</a>') : $lang['Forum']);

		$template->assign_vars(array(
			'PAGINATION' => generate_pagination($base_url, $total_match_count, $per_page, $start),
			'PAGE_NUMBER' => sprintf($lang['Page_of'], (floor($start / $per_page) + 1), ceil($total_match_count / $per_page)),

			'L_AUTHOR' => $lang['Author'],
			'L_MESSAGE' => $lang['Message'],
			'L_FORUM' => $l_forum,
			'L_TOPICS' => $lang['Topics'],
			'L_REPLIES' => $lang['Replies'],
			'L_VIEWS' => $lang['Views'],
			'L_POSTS' => $lang['Posts'],
//<!-- BEGIN Unread Post Information to Database Mod -->
			'L_MAR' => $lang['upi2db_search_mark_read'],
			'L_SUBMIT_MARK_READ' => $lang['upi2db_submit_mark_read'],
			'S_POST_ACTION' => append_sid(SEARCH_MG . '?search_id=' . $search_id . '&amp;s2=' . $s2),
			'L_UNMARK_ALL' => $lang['Unmark_all'],
			'L_MARK_ALL' => $lang['Mark_all'],
			'L_SUBMIT_MARK_READ' => $lang['upi2db_submit_mark_read'],
//<!-- END Unread Post Information to Database Mod -->
			'L_LASTPOST' => '<a href="' . append_sid(SEARCH_MG . '?search_id=' . $search_id . $search_url_add) . '">' . $lang['Last_Post'] . '</a>',
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

		$template->pparse('body');

		include(IP_ROOT_PATH . 'includes/page_tail.' . PHP_EXT);
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
for($i = 0; $i < count($sort_by_types); $i++)
{
	$s_sort_by .= '<option value="' . $i . '">' . $sort_by_types[$i] . '</option>';
}

// Search time
$previous_days = array(0, 1, 7, 14, 30, 90, 180, 364);
$previous_days_text = array($lang['All_Posts'], $lang['1_Day'], $lang['7_Days'], $lang['2_Weeks'], $lang['1_Month'], $lang['3_Months'], $lang['6_Months'], $lang['1_Year']);

$s_time = '';
for($i = 0; $i < count($previous_days); $i++)
{
	$selected = ($topic_days == $previous_days[$i]) ? ' selected="selected"' : '';
	$s_time .= '<option value="' . $previous_days[$i] . '"' . $selected . '>' . $previous_days_text[$i] . '</option>';
}
$l_only_bluecards = ($userdata['user_level'] >= ADMIN) ? '<input type="checkbox" name="only_bluecards" />&nbsp;' . $lang['Search_only_bluecards'] : '' ;

// Output the basic page
$page_title = $lang['Search'];
$meta_description = '';
$meta_keywords = '';
include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);

$template->set_filenames(array('body' => 'search_body.tpl'));
make_jumpbox(VIEWFORUM_MG);

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
	'S_SEARCH_ACTION' => append_sid(SEARCH_MG . '?mode=results'),
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

$template->pparse('body');

include(IP_ROOT_PATH . 'includes/page_tail.' . PHP_EXT);

?>