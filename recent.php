<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

// CTracker_Ignore: File checked by human
// Added to optimize memory for attachments
define('ATTACH_DISPLAY', true);
define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_topics.' . PHP_EXT);

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

$start = (isset($_GET['start'])) ? intval($_GET['start']) : 0;
$start = ($start < 0) ? 0 : $start;

$page_number = (isset($_GET['page_number']) ? intval($_GET['page_number']) : (isset($_POST['page_number']) ? intval($_POST['page_number']) : false));
$page_number = ($page_number < 1) ? false : $page_number;

$start = (!$page_number) ? $start : (($page_number * $board_config['topics_per_page']) - $board_config['topics_per_page']);

// ############         Edit below         ########################################
$topic_length = '60'; // length of topic title
$topic_limit = $board_config['topics_per_page'];
$special_forums = '0'; // specify forums ('0' = no; '1' = yes)
$forum_ids = ''; // IDs of forums; separate them with a comma
$set_mode = 'last24'; // set default mode ('today', 'yesterday', 'last24', 'lastweek', 'lastXdays')
$set_days = '7'; // set default days (used for lastXdays mode)
// ############         Edit above         ########################################

//<!-- BEGIN Unread Post Information to Database Mod -->
if($userdata['upi2db_access'])
{
	$unread = unread();
	$count_new_posts = count($unread['new_posts']);
	$count_edit_posts = count($unread['edit_posts']);
	$count_always_read = count($unread['always_read']['topics']);
	$count_mark_unread = count($unread['mark_posts']);
}
//<!-- END Unread Post Information to Database Mod -->

$cms_page_id = '17';
$cms_page_name = 'recent';
check_page_auth($cms_page_id, $cms_page_name);
$cms_global_blocks = ($board_config['wide_blocks_' . $cms_page_name] == 1) ? true : false;

$mode_types = array('today', 'yesterday', 'last24', 'lastweek', 'lastXdays', 'utopics');
if (($userdata['user_level'] == ADMIN) || ($userdata['user_level'] == MOD))
{
	$mode_types = array_merge($mode_types, array('uposts', 'utview'));
}

if(isset($_GET['mode']) || isset($_POST['mode']))
{
	$mode = (isset($_GET['mode'])) ? htmlspecialchars($_GET['mode']) : htmlspecialchars($_POST['mode']);
}
else
{
	$mode = $set_mode;
}

if (!in_array($mode, $mode_types))
{
	$mode = $set_mode;
}

if(isset($_GET['amount_days']) || isset($_POST['amount_days']))
{
	$amount_days = (isset($_GET['amount_days'])) ? intval($_GET['amount_days']) : intval($_POST['amount_days']);
}

if ($amount_days <= 0)
{
	$amount_days = $set_days;
}

if(isset($_GET[POST_USERS_URL]) || isset($_POST[POST_USERS_URL]))
{
	$user_id = (isset($_GET[POST_USERS_URL])) ? intval($_GET[POST_USERS_URL]) : intval($_POST[POST_USERS_URL]);
	$user_id = ($user_id < 2) ? false : $user_id;

	if ($user_id != false)
	{
		$sql = "SELECT username
			FROM " . USERS_TABLE . "
			WHERE user_id = '" . $user_id . "'";
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, "Could not get user information", $lang['Error'], __LINE__, __FILE__, $sql);
		}

		$row = $db->sql_fetchrow($result);
		$username = $row['username'];
		if ($username == '')
		{
			$mode = $set_mode;
		}
	}
	else
	{
		$mode = $set_mode;
	}
}

$psort_types = array('time', 'cat');
$psort = $psort_types[0];

if(isset($_GET['psort']) || isset($_POST['psort']))
{
	$psort = (isset($_GET['psort'])) ? htmlspecialchars($_GET['psort']) : htmlspecialchars($_POST['psort']);
}

if (!in_array($psort, $psort_types))
{
	$psort = $psort_types[0];
}

$page_title = $lang['Recent_topics'];
$meta_description = '';
$meta_keywords = '';
$nav_server_url = create_server_url();
$breadcrumbs_address = $lang['Nav_Separator'] . '<a href="' . $nav_server_url . append_sid('recent.' . PHP_EXT) . '" class="nav-current">' . $lang['Recent_topics'] . '</a>';
include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);

$except_forums = build_exclusion_forums_list();

$where_forums = ($special_forums == '0') ? 't.forum_id NOT IN (' . $except_forums . ')' : 't.forum_id NOT IN (' . $except_forums . ') AND t.forum_id IN (' . $forum_ids . ')';
$sql_sort = ' ORDER BY t.topic_last_post_id DESC ';
if ($psort == 'cat')
{
	$sql_sort = ' ORDER BY f.forum_id ASC, t.topic_last_post_id DESC ';
}

$extra_tables = '';
$extra_fields = '';
if ($mode == 'utview')
{
	$extra_fields = ", tv.view_time, tv.view_count";
	$extra_tables = ", " . TOPIC_VIEW_TABLE . " tv";
}

$sql_start = "SELECT DISTINCT(t.topic_id), t.*, p.poster_id, p.post_username AS last_poster_name, p.post_id, p.post_time, f.forum_name, f.forum_id, u.username AS last_poster, u.user_id AS last_poster_id, u.user_active AS last_poster_active, u.user_color AS last_poster_color, u2.username AS first_poster, u2.user_id AS first_poster_id, u2.user_active AS first_poster_active, u2.user_color AS first_poster_color, p2.post_username AS first_poster_name" . $extra_fields . "
		FROM (" . TOPICS_TABLE . " t, " . POSTS_TABLE . " p" . $extra_tables . ")
			LEFT OUTER JOIN " . POSTS_TABLE . " p2 ON (p2.post_id = t.topic_first_post_id)
			LEFT OUTER JOIN " . FORUMS_TABLE . " f ON (f.forum_id = p.forum_id)
			LEFT OUTER JOIN " . USERS_TABLE . " u ON (u.user_id = p.poster_id)
			LEFT OUTER JOIN " . USERS_TABLE . " u2 ON (u2.user_id = t.topic_poster)
		WHERE $where_forums
			AND p.post_id = t.topic_last_post_id
			AND t.topic_status <> 2
			AND ";
$sql_end = "LIMIT $start, $topic_limit";

if (!$userdata['session_logged_in'])
{
	$userdata['user_time_mode'] = $board_config['default_time_mode'];
	$userdata['user_timezone'] = $board_config['board_timezone'];
	$userdata['user_dst_time_lag'] = $board_config['default_dst_time_lag'];
}

switch($userdata['user_time_mode'])
{
	case MANUAL_DST:
		$adj_time = (3600 * $userdata['user_timezone']) + ($userdata['user_dst_time_lag'] * 60);
		break;
	case SERVER_SWITCH:
		$adj_time = (3600 * $userdata['user_timezone']) + (date('I', time()) * $userdata['user_dst_time_lag'] * 60);
		break;
	default:
		$adj_time = 3600 * $userdata['user_timezone'];
		break;
}

//$adj_time = ($userdata['session_logged_in']) ? 3600 * $userdata['user_timezone'] : 3600 * $board_config['board_timezone'];
$int_day_sec = intval((time() + $adj_time) / 86400) * 86400;
//$int_day_sec = (intval(time() / 86400) * 86400) - $adj_time;

$mode_pagination = '&amp;amount_days=' . $amount_days;
$total_topics = 0;

switch($mode)
{
	case 'today':
		$sql_tmp = "(p.post_time + " . $adj_time . ") > " . $int_day_sec;
		$sql = $sql_start . $sql_tmp . $sql_sort . $sql_end;
		$template->assign_vars(array('STATUS' => $lang['Recent_today']));
		$where_count = $where_forums . " AND " . $sql_tmp;
		$l_mode = $lang['Recent_title_today'];
		break;

	case 'yesterday':
		$sql_tmp = "(p.post_time + 86400 + " . $adj_time . ") > " . $int_day_sec . " AND (p.post_time + " . $adj_time . ") < " . $int_day_sec;
		$sql = $sql_start . $sql_tmp . $sql_sort . $sql_end;
		$template->assign_vars(array('STATUS' => $lang['Recent_yesterday']));
		$where_count = $where_forums . " AND " . $sql_tmp;
		$l_mode = $lang['Recent_title_yesterday'];
		break;

	case 'last24':
		$sql = $sql_start . "UNIX_TIMESTAMP(NOW()) - p.post_time < 86400" . $sql_sort . $sql_end;
		$template->assign_vars(array('STATUS' => $lang['Recent_last24']));
		$where_count = $where_forums . " AND UNIX_TIMESTAMP(NOW()) - p.post_time < 86400";
		$l_mode = $lang['Recent_title_last24'];
		break;

	case 'lastweek':
		$sql = $sql_start . "UNIX_TIMESTAMP(NOW()) - p.post_time < 691200" . $sql_sort . $sql_end;
		$template->assign_vars(array('STATUS' => $lang['Recent_lastweek']));
		$where_count = $where_forums . " AND UNIX_TIMESTAMP(NOW()) - p.post_time < 691200";
		$l_mode = $lang['Recent_title_lastweek'];
		break;

	case 'lastXdays':
		$sql = $sql_start . "UNIX_TIMESTAMP(NOW()) - p.post_time < 86400 * " . $amount_days . $sql_sort . $sql_end;
		$template->assign_vars(array('STATUS' => sprintf($lang['Recent_lastXdays'], $amount_days)));
		$where_count = $where_forums . " AND UNIX_TIMESTAMP(NOW()) - p.post_time < 86400 * $amount_days";
		$l_mode = sprintf($lang['Recent_title_lastXdays'], $amount_days);
		break;

	case 'utopics':
		$sql = $sql_start . "t.topic_poster = " . $user_id . $sql_sort . $sql_end;
		$template->assign_vars(array('STATUS' => sprintf($lang['RECENT_USER_STARTED_NAV'], $username)));
		$where_count = $where_forums . " AND t.topic_poster = '" . $user_id . "'";
		$l_mode = sprintf($lang['RECENT_USER_STARTED_TITLE'], $username);
		$mode_pagination = '&amp;' . POST_USERS_URL . '=' . $user_id;
		break;

	case 'uposts':
		$sql = "SELECT DISTINCT(topic_id)
			FROM " . POSTS_TABLE . "
			WHERE poster_id = '" . $user_id . "'";
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not obtain matched posts list', '', __LINE__, __FILE__, $sql);
		}
		$search_ids = array();
		while($row = $db->sql_fetchrow($result))
		{
			$search_ids[] = $row['topic_id'];
		}
		$db->sql_freeresult($result);
		$sql_add = '';
		$total_topics = count($search_ids);
		if ($total_topics > 0)
		{
			$sql_add = " t.topic_id IN (" . implode(',', $search_ids) . ") ";
			$where_forums = $where_forums . " AND" . $sql_add;
		}
		$sql = $sql_start . $sql_add . $sql_sort . $sql_end;
		$template->assign_vars(array('STATUS' => sprintf($lang['RECENT_USER_POSTS_NAV'], $username)));
		$where_count = $where_forums . " AND p.poster_id = '" . $user_id . "'";
		$l_mode = sprintf($lang['RECENT_USER_POSTS_TITLE'], $username);
		$mode_pagination = '&amp;' . POST_USERS_URL . '=' . $user_id;
		break;

	case 'utview':
		$sql_sort = ' ORDER BY tv.view_time DESC ';
		if ($psort == 'cat')
		{
			$sql_sort = ' ORDER BY f.forum_id ASC, tv.view_time DESC ';
		}
		$sql_start .= "tv.topic_id = t.topic_id AND tv.user_id = '" . $user_id . "' ";
		$sql = $sql_start . $sql_sort . $sql_end;
		$template->assign_vars(array('STATUS' => sprintf($lang['RECENT_USER_VIEWS_NAV'], $username)));
		$where_count = $where_forums . " AND tv.topic_id = t.topic_id AND tv.user_id = '" . $user_id . "'";
		$l_mode = sprintf($lang['RECENT_USER_VIEWS_TITLE'], $username);
		$mode_pagination = '&amp;' . POST_USERS_URL . '=' . $user_id;
		break;

	default:
		$message = $lang['Recent_wrong_mode'] . '<br /><br />' . sprintf($lang['Recent_click_return'], '<a href="' . append_sid('recent.' . PHP_EXT) . '">', '</a>') . '<br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid(FORUM_MG) . '">', '</a>');
		message_die(GENERAL_MESSAGE, $message);
		break;
}

if(!$result = $db->sql_query($sql))
{
	message_die(GENERAL_ERROR, 'could not obtain main information.', '', __LINE__, __FILE__, $sql);
}
$line = array();
while($row = $db->sql_fetchrow($result))
{
	$line[] = $row;
}
$db->sql_freeresult($result);

$template->set_filenames(array('body' => 'recent_body.tpl'));

$orig_word = array();
$replacement_word = array();
obtain_word_list($orig_word, $replacement_word);

$tracking_topics = (isset($_COOKIE[$board_config['cookie_name'] .'_t'])) ? unserialize($_COOKIE[$board_config['cookie_name'] .'_t']) : array();
$tracking_forums = (isset($_COOKIE[$board_config['cookie_name'] .'_f'])) ? unserialize($_COOKIE[$board_config['cookie_name'] .'_f']) : array();

// MG User Replied - BEGIN
// check if user replied to the topics
define('USER_REPLIED_ICON', true);
$user_topics = user_replied_array($line);
// MG User Replied - END

for($i = 0; $i < count($line); $i++)
{
	$forum_id = $line[$i]['forum_id'];
	$topic_id = $line[$i]['topic_id'];
	$forum_id_append = (!empty($forum_id) ? (POST_FORUM_URL . '=' . $forum_id) : '');
	$topic_id_append = (!empty($topic_id) ? (POST_TOPIC_URL . '=' . $topic_id) : '');
	$forum_url = append_sid(VIEWFORUM_MG . '?' . $forum_id_append);
	$topic_url = append_sid(VIEWTOPIC_MG . '?' . $forum_id_append . '&amp;' . $topic_id_append);
	$user_replied = (!empty($user_topics) && isset($user_topics[$topic_id]));

	$word_censor = (count($orig_word)) ? preg_replace($orig_word, $replacement_word, $line[$i]['topic_title']) : $line[$i]['topic_title'];
	$topic_title = (strlen($line[$i]['topic_title']) < $topic_length) ? $word_censor : substr(stripslashes($word_censor), 0, $topic_length) . '...';
	$topic_title_prefix = (empty($line[$i]['title_compl_infos'])) ? '' : $line[$i]['title_compl_infos'] . ' ';
	$topic_title = $topic_title_prefix . $topic_title;

	//$news_label = ($line[$i]['news_id'] > 0) ? $lang['News_Cmx'] . '' : '';
	$news_label = '';

	$views = $line[$i]['topic_views'];
	$replies = $line[$i]['topic_replies'];

	$topic_link = build_topic_icon_link($forum_id, $line[$i]['topic_id'], $line[$i]['topic_type'], $line[$i]['topic_replies'], $line[$i]['news_id'], $line[$i]['topic_vote'], $line[$i]['topic_status'], $line[$i]['topic_moved_id'], $line[$i]['post_time'], $user_replied, $replies, $unread);

	$topic_id = $topic_link['topic_id'];
	$topic_id_append = $topic_link['topic_id_append'];

	if(($replies + 1) > $board_config['posts_per_page'])
	{
		$total_pages = ceil(($replies + 1) / $board_config['posts_per_page']);
		$goto_page = ' [ <img src="' . $images['icon_gotopost'] . '" alt="' . $lang['Goto_page'] . '" title="' . $lang['Goto_page'] . '" />&nbsp;' . $lang['Goto_page'] . ': ';
		$times = '1';
		for($j = 0; $j < $replies + 1; $j += $board_config['posts_per_page'])
		{
			$goto_page .= '<a href="' . append_sid(VIEWTOPIC_MG . '?' . $forum_id_append . '&amp;' . $topic_id_append . '&amp;start=' . $j) . '"><b>' . $times . '</b></a>';
			if(($times == '1') && ($total_pages > '4'))
			{
				$goto_page .= ' ... ';
				$times = $total_pages - 3;
				$j += ($total_pages - 4) * $board_config['posts_per_page'];
			}
			elseif($times < $total_pages)
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

	$first_time = create_date_simple($lang['DATE_FORMAT_VF'], $line[$i]['topic_time'], $board_config['board_timezone']);
	// Old format
	//$first_time = create_date2($board_config['default_dateformat'], $line[$i]['topic_time'], $board_config['board_timezone']);
	$first_author = ($line[$i]['first_poster_id'] != ANONYMOUS) ? colorize_username($line[$i]['first_poster_id'], $line[$i]['first_poster'], $line[$i]['first_poster_color'], $line[$i]['first_poster_active']) : (($line[$i]['first_poster_name'] != '') ? $line[$i]['first_poster_name'] : $lang['Guest']);
	$last_time = create_date2($board_config['default_dateformat'], $line[$i]['post_time'], $board_config['board_timezone']);
	$last_author = ($line[$i]['last_poster_id'] != ANONYMOUS) ? colorize_username($line[$i]['last_poster_id'], $line[$i]['last_poster'], $line[$i]['last_poster_color'], $line[$i]['last_poster_active']): (($line[$i]['last_poster_name'] != '') ? $line[$i]['last_poster_name'] : $lang['Guest']);
	$last_url = '<a href="' . append_sid(VIEWTOPIC_MG . '?' . $forum_id_append . '&amp;' . $topic_id_append . '&amp;' . POST_POST_URL . '=' . $line[$i]['topic_last_post_id']) . '#p' . $line[$i]['topic_last_post_id'] . '"><img src="' . $images['icon_latest_reply'] . '" alt="' . $lang['View_latest_post'] . '" title="' . $lang['View_latest_post'] . '" /></a>';

	// SELF AUTH - BEGIN
	// Comment the lines below if you wish to show RESERVED topics for AUTH_SELF.
	/*
	if ((($userdata['user_level'] != ADMIN) && ($userdata['user_level'] != MOD)) && (intval($is_auth_ary[$line[$i]['forum_id']]['auth_read']) == AUTH_SELF) && ($line[$i]['first_poster_id'] != $userdata['user_id']))
	{
		$first_author = $lang['Reserved_Author'];
		$last_author = $lang['Reserved_Author'];
		$topic_title = $lang['Reserved_Topic'];
	}
	*/
	// SELF AUTH - END

	if($mode == 'utview')
	{
		$last_time = $last_time = create_date2($board_config['default_dateformat'], $line[$i]['view_time'], $board_config['board_timezone']);;
		$last_author = '';
		$last_url = '';
	}

	// Convert and clean special chars!
	$topic_title = htmlspecialchars_clean($topic_title);
	$template->assign_block_vars('recent', array(
		'ROW_CLASS' => (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'],

		'TOPIC_ID' => $topic_id,
		'TOPIC_FOLDER_IMG' => $topic_link['image'],
		'L_TOPIC_FOLDER_ALT' => $topic_link['image_alt'],
		'TOPIC_TITLE' => $topic_title,
		'TOPIC_TYPE' => $topic_link['type'],
		'TOPIC_TYPE_ICON' => $topic_link['icon'],
		'TOPIC_CLASS' => (!empty($topic_link['class_new']) ? ('topiclink' . $topic_link['class_new']) : $topic_link['class']),
		'CLASS_NEW' => $topic_link['class_new'],
		'NEWEST_POST_IMG' => $topic_link['newest_post_img'],
		'L_NEWS' => $news_label,
		'TOPIC_ATTACHMENT_IMG' => topic_attachment_image($line[$i]['topic_attachment']),

		//'GOTO_PAGE' => $goto_page,
		'GOTO_PAGE' => (($goto_page == '') ? '' : '<span class="gotopage">' . $goto_page . '</span>'),
		'L_VIEWS' => $lang['Views'],
		'VIEWS' => $views,

		'L_REPLIES' => $lang['Replies'],
		'REPLIES' => $replies,
		//'FIRST_TIME' => sprintf($lang['Recent_first'], $first_time),
		'FIRST_TIME' => $first_time,
		'FIRST_AUTHOR' => $first_author,
		'LAST_TIME' => $last_time,
		'LAST_AUTHOR' => $last_author,
		'LAST_URL' => $last_url,
		'FORUM_NAME' => $line[$i]['forum_name'],
		'U_VIEW_FORUM' => $forum_url,
		'U_VIEW_TOPIC' => $topic_url,
		)
	);
}

if ($total_topics == 0)
{
	$sql = "SELECT count(DISTINCT(t.topic_id)) AS total_topics
					FROM " . TOPICS_TABLE . " t, " . POSTS_TABLE . " p" . $extra_tables . "
					WHERE $where_count
					AND p.post_id = t.topic_last_post_id";
	if(!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'error getting total topics.', '', __LINE__, __FILE__, $sql);
	}
	if($total = $db->sql_fetchrow($result))
	{
		$total_topics = $total['total_topics'];
	}
}

$base_url = 'recent.' . PHP_EXT . '?mode=' . $mode . $mode_pagination;
if ($psort != $psort_types[0])
{
	$base_url .= '&amp;psort=' . $psort;
}
$pagination = generate_pagination($base_url, $total_topics, $topic_limit, $start) . '&nbsp;';

if($total_topics == '0')
{
	$template->assign_block_vars('switch_no_topics', array());
}

$template->assign_vars(array(
	'L_RECENT_TITLE' => ($total_topics == '1') ? sprintf($lang['Recent_title_one'], $total_topics, $l_mode) : sprintf($lang['Recent_title_more'], $total_topics, $l_mode),
	'L_TODAY' => $lang['Recent_today'],
	'L_YESTERDAY' => $lang['Recent_yesterday'],
	'L_LAST24' => $lang['Recent_last24'],
	'L_LASTWEEK' => $lang['Recent_lastweek'],
	'L_LAST' => $lang['Recent_last'],
	'L_DAYS' => $lang['Recent_days'],
	'L_SELECT_MODE' => $lang['Recent_select_mode'],
	'L_SHOWING_POSTS' => $lang['Recent_showing_posts'],
	'L_LASTPOST' => ($mode == 'utview') ? $lang['Topic_time'] : $lang['Last_Post'],
	'L_NO_TOPICS' => $lang['Recent_no_topics'],
	'U_SORT_CAT' => append_sid('recent.' . PHP_EXT . '?amount_days=' . $amount_days . '&amp;mode=' . $mode . '&amp;psort=cat&amp;start=' . $start),
	'U_SORT_TIME' => append_sid('recent.' . PHP_EXT . '?amount_days=' . $amount_days . '&amp;mode=' . $mode . '&amp;psort=time&amp;start=' . $start),
	'AMOUNT_DAYS' => $amount_days,
	'FORM_ACTION' => append_sid('recent.' . PHP_EXT),
	'PAGINATION' => ($total_topics != '0') ? $pagination : '&nbsp;',
	'PAGE_NUMBER' => ($total_topics != '0') ? sprintf($lang['Page_of'], (floor($start / $topic_limit) + 1), ceil($total_topics / $topic_limit)) : '&nbsp;',
	)
);

$template->pparse('body');
include(IP_ROOT_PATH . 'includes/page_tail.' . PHP_EXT);

?>