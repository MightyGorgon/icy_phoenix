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

define('IN_VIEWFORUM', true);
define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_topics.' . PHP_EXT);

@include_once(IP_ROOT_PATH . 'includes/class_topics.' . PHP_EXT);
$class_topics = new class_topics();

// Init common vars: forum_id, topic_id, post_id, etc.
$class_topics->var_init(true);

// CONFIG - BEGIN
define('VIEWFORUMLIST_PER_PAGE', 1000);
// CONFIG - END

// Start initial var setup
$selected_id = request_var('selected_id', '');
if (!empty($selected_id))
{
	$type = substr($selected_id, 0, 1);
	$id = intval(substr($selected_id, 1));
	if ($type == POST_FORUM_URL)
	{
		$forum_id = $id;
		$forum_id_append = (!empty($forum_id) ? (POST_FORUM_URL . '=' . $forum_id) : '');
	}
	elseif (($type == POST_CAT_URL) || ($selected_id == 'Root'))
	{
		$parm = ($id != 0) ? '?' . POST_CAT_URL . '=' . $id : '';
		redirect(append_sid(CMS_PAGE_FORUM . $parm));
		exit;
	}
}

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup();
// End session management

$config['topics_per_page'] = VIEWFORUMLIST_PER_PAGE;

$cms_page['page_id'] = 'viewforum';
$cms_page['page_nav'] = (!empty($cms_config_layouts[$cms_page['page_id']]['page_nav']) ? true : false);
$cms_page['global_blocks'] = (!empty($cms_config_layouts[$cms_page['page_id']]['global_blocks']) ? true : false);
$cms_auth_level = (isset($cms_config_layouts[$cms_page['page_id']]['view']) ? $cms_config_layouts[$cms_page['page_id']]['view'] : AUTH_ALL);
check_page_auth($cms_page['page_id'], $cms_auth_level);

$start = request_var('start', 0);
$start = ($start < 0) ? 0 : $start;

$page_number = request_var('page_number', 0);
$page_number = ($page_number < 1) ? 0 : $page_number;

$start = (empty($page_number) ? $start : (($page_number * $config['topics_per_page']) - $config['topics_per_page']));

// Topics Sorting - BEGIN
$letters_array = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
$start_letter = request_var('start_letter', '');
$start_letter = (in_array($start_letter, $letters_array) ? $start_letter : '');

$sort_order_array = array('AZ', 'ZA', 'newest', 'oldest', 'views', 'replies', 'time', 'author');
$sort_order = request_var('sort_order', 'AZ');
$sort_order = (in_array($sort_order, $sort_order_array) ? $sort_order : $sort_order_array[0]);
$sort_dir = request_var('sort_dir', 'DESC');
$sort_dir = ($sort_dir == 'ASC') ? 'ASC' : 'DESC';

switch ($sort_order)
{
	case 'AZ':
		$sort_dir = 'ASC';
		$sort_order_sql = "t.topic_title " . $sort_dir;
		break;
	case 'ZA':
		$sort_dir = 'DESC';
		$sort_order_sql = "t.topic_title " . $sort_dir;
		break;
	case 'views':
		$sort_order_sql = "t.topic_views " . $sort_dir;
		break;
	case 'replies':
		$sort_order_sql = "t.topic_replies " . $sort_dir;
		break;
	case 'time':
		$sort_order_sql = "t.topic_time " . $sort_dir;
		break;
	case 'author':
		$sort_order_sql = "t.topic_poster " . $sort_dir;
		break;
	case 'oldest':
		$sort_dir = 'ASC';
		$sort_order_sql = "t.topic_last_post_id " . $sort_dir;
		break;
	case 'newest':
	default:
		$sort_order = 'newest';
		$sort_dir = 'DESC';
		$sort_order_sql = "t.topic_last_post_id " . $sort_dir;
		break;
}

if (!in_array($start_letter, $letters_array))
{
	$start_letter = '';
	$start_letter_sql = '';
}
else // we have a single letter, so let's sort alphabetically...
{
	$sort_dir = 'ASC';
	$sort_order_sql = "t.topic_title " . $sort_dir;
	$start_letter_sql = "AND t.topic_title LIKE '" . $db->sql_escape($start_letter) . "%'";
}
// Topics Sorting - END

// get the forum row
//
// Check if the user has actually sent a forum ID with his/her request
// If not give them a nice error page.
//

$forum_row = $tree['data'][$tree['keys'][POST_FORUM_URL . $forum_id]];
if (empty($forum_row))
{
	if (!defined('STATUS_404')) define('STATUS_404', true);
	message_die(GENERAL_MESSAGE, 'NO_FORUM');
}

$meta_content = array();
$meta_content = $class_topics->meta_content_init($forum_row, 'forum');
$meta_content['forum_id'] = $forum_id;

// handle forum link type
$selected_id = POST_FORUM_URL . $forum_id;
$CH_this = isset($tree['keys'][$selected_id]) ? $tree['keys'][$selected_id] : -1;
if (($CH_this > -1) && !empty($tree['data'][$CH_this]['forum_link']))
{
	if (!defined('STATUS_404')) define('STATUS_404', true);
	message_die(GENERAL_MESSAGE, 'NO_FORUM');
}

// Start auth check
$is_auth = array();
$is_auth = $tree['auth'][POST_FORUM_URL . $forum_id];

if (!$is_auth['auth_read'] || !$is_auth['auth_view'])
{
	if (!$user->data['session_logged_in'])
	{
		$redirect = $forum_id_append . ((isset($start)) ? '&start=' . $start : '');
		redirect(append_sid(CMS_PAGE_LOGIN . '?redirect=' . CMS_PAGE_VIEWFORUMLIST . '&' . $redirect, true));
	}

	// The user is not authed to read this forum ...
	$message = (!$is_auth['auth_view']) ? $lang['NO_FORUM'] : sprintf($lang['Sorry_auth_read'], $is_auth['auth_read_type']);

	message_die(GENERAL_MESSAGE, $message);
}
// End of auth check

// Topics Sorting - BEGIN
if (!empty($start_letter))
{
	$sql = "SELECT COUNT(topic_id) AS forum_topics
		FROM " . TOPICS_TABLE . " t
		WHERE t.forum_id = '" . $forum_id . "'
			AND t.topic_status <> " . TOPIC_MOVED . "
			" . $start_letter_sql . "
		ORDER BY " . $sort_order_sql;
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$topics_count = ($row['forum_topics']) ? $row['forum_topics'] : 1;
	$db->sql_freeresult($result);
}
else
{
	$topics_count = ($forum_row['forum_topics']) ? $forum_row['forum_topics'] : 1;
}
// Topics Sorting - END

$forum_row['forum_name'] = get_object_lang(POST_FORUM_URL . $forum_id, 'name');

$meta_content['page_title'] = $forum_row['forum_name'];
$meta_content['description'] = '';
$meta_content['keywords'] = '';

$template_to_parse = 'viewforumlist_body.tpl';

make_jumpbox(CMS_PAGE_VIEWFORUMLIST);

$sort_lang = ($sort_dir == 'ASC') ? $lang['Sort_Ascending'] : $lang['Sort_Descending'];
$sort_img = ($sort_dir == 'ASC') ? 'images/sort_asc.png' : 'images/sort_desc.png';
$sort_img_full = '<img src="' . $sort_img . '" alt="' . $sort_lang . '" title="' . $sort_lang . '" style="padding-left: 3px;" />';
$start_letter_append = ($start_letter == '') ? '' : ('&amp;start_letter=' . $start_letter);
$sort_order_append = '&amp;sort_order=' . $sort_order;
$sort_dir_append = '&amp;sort_dir=' . $sort_dir;
$sort_dir_append_rev = '&amp;sort_dir=' . (($sort_dir == 'ASC') ? 'DESC' : 'ASC');
$this_forum_address = CMS_PAGE_VIEWFORUMLIST . '?' . $forum_id_append . $start_letter_append;

$template->assign_vars(array(
	'FORUM_ID' => $forum_id,
	'FORUM_ID_FULL' => POST_FORUM_URL . $forum_id,
	'FORUM_NAME' => $forum_row['forum_name'],
	'FOLDER_IMG' => $images['vf_topic_nor'],
	'L_TOPICS' => $lang['Topics'],
	'U_VIEW_FORUM' => append_sid(CMS_PAGE_VIEWFORUM . '?' . $forum_id_append),
	)
);
// End header

$topic_rowset = array();
$sql = "SELECT t.topic_id, t.forum_id, t.topic_title, t.topic_title_clean, t.topic_label_compiled
				FROM " . TOPICS_TABLE . " t
				WHERE t.forum_id = " . $forum_id . "
					AND t.topic_status <> " . TOPIC_MOVED . "
					" . $start_letter_sql . "
				ORDER BY " . $sort_order_sql . "
				LIMIT " . $start . ", " . $config['topics_per_page'];
$result = $db->sql_query($sql);

while($row = $db->sql_fetchrow($result))
{
	$topic_rowset[] = $row;
}
$db->sql_freeresult($result);

$total_topics = sizeof($topic_rowset);

// Okay, lets dump out the page...
if($total_topics)
{
	for($i = 0; $i < $total_topics; $i++)
	{
		$forum_id = $topic_rowset[$i]['forum_id'];
		$forum_id_append = (!empty($forum_id) ? (POST_FORUM_URL . '=' . $forum_id) : '');
		$topic_id = $topic_rowset[$i]['topic_id'];
		$topic_id_append = (!empty($topic_id) ? (POST_TOPIC_URL . '=' . $topic_id) : '');

		$topic_title_data = $class_topics->generate_topic_title($topic_id, $topic_rowset[$i], $config['last_topic_title_length']);
		$topic_title = $topic_title_data['title'];
		$topic_title_clean = $topic_title_data['title_clean'];
		$topic_title_plain = $topic_title_data['title_plain'];
		$topic_title_label = $topic_title_data['title_label'];
		$topic_title_short = $topic_title_data['title_short'];

		if (($config['url_rw'] == '1') || (($config['url_rw_guests'] == '1') && ($user->data['user_id'] == ANONYMOUS)))
		{
			$view_topic_url = append_sid(str_replace ('--', '-', make_url_friendly($topic_title) . '-vt' . $topic_id . '.html'));
		}
		else
		{
			$view_topic_url = append_sid(CMS_PAGE_VIEWTOPIC . '?' . $forum_id_append . '&amp;' . $topic_id_append . $kb_mode_append);
		}

		$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];

		$template->assign_block_vars('topicrow', array(
			'ROW_CLASS' => $row_class,
			'FORUM_ID' => $forum_id,
			'TOPIC_ID' => $topic_id,
			'TOPIC_TITLE' => $topic_title,
			'TOPIC_TITLE_PLAIN' => $topic_title_plain,
			'U_VIEW_TOPIC' => $view_topic_url
			)
		);
	}

	$number_of_page = (ceil($topics_count / $config['topics_per_page']) == 0) ? 1 : ceil($topics_count / $config['topics_per_page']);

	$template->assign_vars(array(
		'PAGINATION' => generate_pagination(CMS_PAGE_VIEWFORUMLIST . '?' . $forum_id_append . '&amp;start_letter=' . $start_letter . '&amp;sort_order=' . $sort_order . '&amp;sort_dir=' . $sort_dir, $topics_count, $config['topics_per_page'], $start),
		'PAGE_NUMBER' => sprintf($lang['Page_of'], (floor($start / $config['topics_per_page']) + 1), $number_of_page),
		'L_GOTO_PAGE' => $lang['Goto_page']
		)
	);
}
else
{
	// No topics
	$template->assign_var('L_NO_TOPICS', $lang['No_topics_post_one']);
}

// Topics Sorting - BEGIN
// Begin Configuration Section
// Change this to whatever you want the divider to be. Be sure to keep both apostrophies.
$divider = ' &bull; ';
$divider_letters = ' ';
// End Configuration Section

// Do not change anything below this line.
$total_letters_count = sizeof($letters_array);
$this_letter_number = 0;

$template->assign_vars(array(
	'S_SHOW_ALPHA_BAR' => true,
	'DIVIDER' => $divider,
	'U_NEWEST' => append_sid(CMS_PAGE_VIEWFORUMLIST . '?' . $forum_id_append . '&amp;start_letter=&amp;sort_order=newest'),
	'U_OLDEST' => append_sid(CMS_PAGE_VIEWFORUMLIST . '?' . $forum_id_append . '&amp;start_letter=&amp;sort_order=oldest'),
	'U_AZ' => append_sid(CMS_PAGE_VIEWFORUMLIST . '?' . $forum_id_append . '&amp;start_letter=&amp;sort_order=AZ'),
	'U_ZA' => append_sid(CMS_PAGE_VIEWFORUMLIST . '?' . $forum_id_append . '&amp;start_letter=&amp;sort_order=ZA'),
	)
);

foreach ($letters_array as $letter)
{
	$this_letter_number++;
	$template->assign_block_vars('alphabetical_sort', array(
		'LETTER' => $letter,
		'U_LETTER' => append_sid(CMS_PAGE_VIEWFORUMLIST . '?' . $forum_id_append . '&amp;start_letter=' . $letter),
		'DIVIDER' => ($this_letter_number != $total_letters_count) ? $divider_letters : '',
		)
	);
}
// Topics Sorting - END

full_page_generation($template_to_parse, $meta_content['page_title'], $meta_content['description'], $meta_content['keywords']);

?>