<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup();
// End session management

$config['jquery_ui'] = true;

// CMS - BEGIN
$cms_page['page_id'] = 'tags';
$cms_page['page_nav'] = (isset($cms_config_layouts[$cms_page['page_id']]['page_nav']) ? $cms_config_layouts[$cms_page['page_id']]['page_nav'] : true);
$cms_page['global_blocks'] = (!empty($cms_config_layouts[$cms_page['page_id']]['global_blocks']) ? true : false);
$cms_auth_level = (isset($cms_config_layouts[$cms_page['page_id']]['view']) ? $cms_config_layouts[$cms_page['page_id']]['view'] : AUTH_ALL);
check_page_auth($cms_page['page_id'], $cms_auth_level);
// CMS - END

// COMMON - BEGIN
@include_once(IP_ROOT_PATH . 'includes/class_form.' . PHP_EXT);
$class_form = new class_form();

@include_once(IP_ROOT_PATH . 'includes/class_topics.' . PHP_EXT);
$class_topics = new class_topics();

@include_once(IP_ROOT_PATH . 'includes/class_topics_tags.' . PHP_EXT);
$class_topics_tags = new class_topics_tags();
// COMMON - END

// CONFIG - BEGIN
$table_fields = array(
	'tag_count' => array('lang_key' => 'TAG_COUNT', 'view_level' => AUTH_ALL),
	'tag_text' => array('lang_key' => 'TAG_TEXT', 'view_level' => AUTH_ALL),
);
// CONFIG - END

// VARS - BEGIN
$tag_id = request_var('tag_id', 0);
$tag_id = ($tag_id < 0) ? 0 : $tag_id;

$tag_text = request_var('tag_text', '', true);
$tag_text = ip_clean_string(urldecode(trim($tag_text)), $lang['ENCODING'], true);

$mode_types = array('cloud', 'list', 'view', 'replace');
$mode = request_var('mode', $mode_types[0]);
$mode = check_var_value($mode, $mode_types);

$action_types = array('list');
$action = request_var('action', $action_types[0]);
$action = check_var_value($action, $action_types);

$start = request_var('start', 0);
$start = ($start < 0) ? 0 : $start;

$per_page = request_var('per_page', 0);
$per_page = (empty($per_page) || ($per_page < 20) || ($per_page > 300)) ? $config['topics_per_page'] : $per_page;

$s_hidden_fields = '';

// SORT ORDER - BEGIN
$sort_order_array = array();
$sort_order_select_array = array();
$sort_order_select_lang_array = array();
foreach ($table_fields as $k => $v)
{
	$is_auth = (check_auth_level($v['view_level']));
	if ($is_auth)
	{
		$sort_order_array[] = $k;
		$sort_order_select_array[] = $k;
		$sort_order_select_lang_array[] = $class_form->get_lang($v['lang_key']);
	}
}
$sort_order_default = ((isset($sort_order_default) && in_array($sort_order_default, $sort_order_array)) ? $sort_order_default : $sort_order_array[0]);
$sort_order = request_var('sort_order', $sort_order_default);
$sort_order = (in_array($sort_order, $sort_order_array) ? $sort_order : $sort_order_array[0]);

$select_name = 'sort_order';
$default = $sort_order;
$select_js = '';
$sort_order_select_box = $class_form->build_select_box($select_name, $default, $sort_order_select_array, $sort_order_select_lang_array, $select_js);
// SORT ORDER - END

// SORT DIR - BEGIN
$sort_dir_default = ((isset($sort_dir_default) && in_array($sort_dir_default, array('ASC', 'DESC'))) ? $sort_dir_default : 'DESC');
$sort_dir = request_var('sort_dir', $sort_dir_default);
$sort_dir = ($sort_dir == 'ASC') ? 'ASC' : 'DESC';

$sort_dir_select_array = array('ASC', 'DESC');
$sort_dir_select_lang_array = array($lang['Sort_Ascending'], $lang['Sort_Descending']);

$select_name = 'sort_dir';
$default = $sort_dir;
$select_js = '';
$sort_dir_select_box = $class_form->build_select_box($select_name, $default, $sort_dir_select_array, $sort_dir_select_lang_array, $select_js);
// SORT DIR - END
// VARS - END

if ($mode == 'view')
{
	if (empty($tag_text))
	{
		$msg_title = $lang['TOPIC_TAGS'];
		trigger_error('TAGS_NO_TAG', E_USER_NOTICE);
	}

	$breadcrumbs['bottom_right_links'] .= (($breadcrumbs['bottom_right_links'] != '') ? ('&nbsp;' . MENU_SEP_CHAR . '&nbsp;') : '') . '<a href="' . append_sid(CMS_PAGE_TAGS) . '">' . $lang['TOPIC_TAGS'] . '</a>';
	$breadcrumbs['bottom_right_links'] .= (($user->data['user_level'] == ADMIN) ? ('&nbsp;' . MENU_SEP_CHAR . '&nbsp;') . '<a href="' . append_sid(CMS_PAGE_TAGS . '?mode=replace') . '">' . $lang['TOPIC_TAGS_REPLACE'] . '</a>' : '');

	$template_to_parse = 'tags_view_body.tpl';

	$tags = array($tag_text);
	$topics = $class_topics_tags->get_topics_with_tags($tags, $start, $per_page);
	$num_items = 0;
	$tags_counters = $class_topics_tags->get_tags_counters($tags);
	foreach ($tags_counters as $tag_counter)
	{
		if ($tag_counter['tag_text'] == $tag_text)
		{
			$num_items = $tag_counter['tag_count'];
			break;
		}
	}

	$topic_length = 60;

	// UPI2DB - BEGIN
	if($user->data['upi2db_access'])
	{
		if (!defined('UPI2DB_UNREAD'))
		{
			$user->data['upi2db_unread'] = upi2db_unread();
		}
		$count_new_posts = sizeof($user->data['upi2db_unread']['new_posts']);
		$count_edit_posts = sizeof($user->data['upi2db_unread']['edit_posts']);
		$count_always_read = sizeof($user->data['upi2db_unread']['always_read']['topics']);
		$count_mark_unread = sizeof($user->data['upi2db_unread']['mark_posts']);
	}
	// UPI2DB - END

	// MG User Replied - BEGIN
	// check if user replied to the topic
	define('USER_REPLIED_ICON', true);
	$user_topics = $class_topics->user_replied_array($topics);
	// MG User Replied - END

	$i = 0;
	foreach ($topics as $topic)
	{
		$class = ($i % 2) ? $theme['td_class1'] : $theme['td_class2'];

		$forum_id = $topic['forum_id'];
		$topic_id = $topic['topic_id'];
		$forum_id_append = (!empty($forum_id) ? (POST_FORUM_URL . '=' . $forum_id) : '');
		$topic_id_append = (!empty($topic_id) ? (POST_TOPIC_URL . '=' . $topic_id) : '');
		$forum_url = append_sid(CMS_PAGE_VIEWFORUM . '?' . $forum_id_append);
		$topic_url = append_sid(CMS_PAGE_VIEWTOPIC . '?' . $forum_id_append . '&amp;' . $topic_id_append);
		$views = $topic['topic_views'];
		$replies = $topic['topic_replies'];
		$user_replied = (!empty($user_topics) && isset($user_topics[$topic_id]));
		//$news_label = ($line[$i]['news_id'] > 0) ? $lang['News_Cmx'] . '' : '';
		$news_label = '';

		$topic_title_data = $class_topics->generate_topic_title($topic_id, $topic, $topic_length);
		$topic_title = $topic_title_data['title'];
		$topic_title_clean = $topic_title_data['title_clean'];
		$topic_title_plain = $topic_title_data['title_plain'];
		$topic_title_label = $topic_title_data['title_label'];
		$topic_title_short = $topic_title_data['title_short'];

		$topic_link = $class_topics->build_topic_icon_link($forum_id, $topic['topic_id'], $topic['topic_type'], $topic['topic_reg'], $topic['topic_replies'], $topic['news_id'], $topic['poll_start'], $topic['topic_status'], $topic['topic_moved_id'], $topic['post_time'], $user_replied, $replies);

		$topic_id = $topic_link['topic_id'];
		$topic_id_append = $topic_link['topic_id_append'];

		$topic_pagination = generate_topic_pagination($forum_id, $topic_id, $replies);

		$first_time = create_date_ip($lang['DATE_FORMAT_VF'], $topic['topic_time'], $config['board_timezone'], true);
		$first_author = ($topic['first_poster_id'] != ANONYMOUS) ? colorize_username($topic['topic_first_poster_id'], $topic['topic_first_poster_name'], $topic['topic_first_poster_color'], 1) : (($topic['topic_first_poster_name'] != '') ? $topic['topic_first_poster_name'] : $lang['Guest']);
		$last_time = create_date_ip($config['default_dateformat'], $topic['topic_last_post_time'], $config['board_timezone']);
		$last_author = ($topic['topic_last_poster_id'] != ANONYMOUS) ? colorize_username($topic['topic_last_poster_id'], $topic['topic_last_poster_name'], $topic['topic_last_poster_color'], 1) : (($topic['topic_last_poster_name'] != '') ? $topic['topic_last_poster_name'] : $lang['Guest']);
		$last_url = '<a href="' . append_sid(CMS_PAGE_VIEWTOPIC . '?' . $forum_id_append . '&amp;' . $topic_id_append . '&amp;' . POST_POST_URL . '=' . $topic['topic_last_post_id']) . '#p' . $topic['topic_last_post_id'] . '"><img src="' . $images['icon_latest_reply'] . '" alt="' . $lang['View_latest_post'] . '" title="' . $lang['View_latest_post'] . '" /></a>';

		$topic_tags_links = $class_topics_tags->build_tags_list_single_topic($topic['topic_tags']);

		$template->assign_block_vars('row', array(
			'CLASS' => $class,
			'ROW_NUMBER' => $i + 1,

			'TOPIC_ID' => $topic_id,
			'TOPIC_FOLDER_IMG' => $topic_link['image'],
			'L_TOPIC_FOLDER_ALT' => $topic_link['image_alt'],
			'TOPIC_TITLE' => $topic_title,
			'TOPIC_TITLE_PLAIN' => $topic_title_plain,
			'TOPIC_TYPE' => $topic_link['type'],
			'TOPIC_TYPE_ICON' => $topic_link['icon'],
			'TOPIC_CLASS' => (!empty($topic_link['class_new']) ? ('topiclink' . $topic_link['class_new']) : $topic_link['class']),
			'CLASS_NEW' => $topic_link['class_new'],
			'NEWEST_POST_IMG' => $topic_link['newest_post_img'],
			'L_NEWS' => $news_label,
			'GOTO_PAGE' => $topic_pagination['base'],
			'GOTO_PAGE_FULL' => $topic_pagination['full'],
			'VIEWS' => $views,
			'TOPIC_TAGS' => $topic_tags_links,

			'REPLIES' => $replies,
			//'FIRST_POST_TIME' => sprintf($lang['Recent_first'], $first_time),
			'FIRST_POST_TIME' => $first_time,
			'FIRST_AUTHOR' => $first_author,
			'LAST_POST_TIME' => $last_time,
			'LAST_AUTHOR' => $last_author,
			'LAST_URL' => $last_url,
			'FORUM_NAME' => $topic['forum_name'],

			'U_VIEW_FORUM' => $forum_url,
			'U_VIEW_TOPIC' => $topic_url,
			)
		);
		$i++;
	}

	$template->assign_vars(array(
		'L_VIEWS' => $lang['Views'],
		'L_LASTPOST' => $lang['Last_Post'],
		'L_REPLIES' => $lang['Replies'],
		'L_TAG_RESULTS' => sprintf($lang['TAG_RESULTS'], htmlspecialchars($tag_text) . ' (' . $num_items . ')'),

		'U_TAG_RESULTS' => append_sid(CMS_PAGE_TAGS . '?mode=view&amp;tag_text=' . htmlspecialchars(urlencode($tag_text))),
		)
	);
}
elseif ($mode == 'replace')
{
	if ($user->data['user_level'] != ADMIN)
	{
		message_die(GENERAL_MESSAGE, $lang['Not_Authorized']);
	}

	$template_to_parse = 'tags_replace_body.tpl';

	$breadcrumbs['bottom_right_links'] .= (($breadcrumbs['bottom_right_links'] != '') ? ('&nbsp;' . MENU_SEP_CHAR . '&nbsp;') : '') . '<a href="' . append_sid(CMS_PAGE_TAGS) . '">' . $lang['TOPIC_TAGS'] . '</a>';
	$breadcrumbs['bottom_right_links'] .= (($user->data['user_level'] == ADMIN) ? ('&nbsp;' . MENU_SEP_CHAR . '&nbsp;') : '') . '<a href="' . append_sid(CMS_PAGE_TAGS . '?mode=replace') . '">' . $lang['TOPIC_TAGS_REPLACE'] . '</a>';

	$search_replace_submit = false;
	if (isset($_POST['submit']))
	{
		$search_replace_submit = true;
		$tag_old = request_var('tag_old', '', true);
		$tag_new = request_var('tag_new', '', true);
		$topics_data = $class_topics_tags->replace_tag($tag_old, $tag_new);
		message_die(GENERAL_MESSAGE, sprintf($lang['TAGS_SEARCH_REPLACE_RESULT'], sizeof($topics_data)));
	}
}
else
{
	$template_to_parse = 'tags_list_body.tpl';

	$breadcrumbs['bottom_right_links'] .= (($breadcrumbs['bottom_right_links'] != '') ? ('&nbsp;' . MENU_SEP_CHAR . '&nbsp;') : '') . '<a href="' . append_sid(CMS_PAGE_TAGS . '?mode=' . (($mode == 'cloud') ? 'list' : 'cloud')) . '">' . (($mode == 'cloud') ? $lang['TOPIC_TAGS_LIST'] : $lang['TOPIC_TAGS_CLOUDS']) . '</a>';
	$breadcrumbs['bottom_right_links'] .= (($user->data['user_level'] == ADMIN) ? ('&nbsp;' . MENU_SEP_CHAR . '&nbsp;') : '') . '<a href="' . append_sid(CMS_PAGE_TAGS . '?mode=replace') . '">' . $lang['TOPIC_TAGS_REPLACE'] . '</a>';

	$per_page = ($mode == 'cloud') ? $config['word_graph_max_words'] : $per_page;
	$num_items = $class_topics_tags->get_total_tags();
	$tags = $class_topics_tags->get_tags($sort_order, $sort_dir, $start, $per_page);

	$row_class = '';
	$i = 0;
	foreach ($tags as $tag)
	{
		$row_class = ip_zebra_rows($row_class);
		$tag_font_size = intval(mt_rand(8, 14));
		$template->assign_block_vars('row', array(
			'CLASS' => $row_class,
			'ROW_NUMBER' => $i + 1,

			'U_TAG_TEXT' => append_sid(CMS_PAGE_TAGS . '?mode=view&amp;tag_text=' . htmlspecialchars(urlencode($tag['tag_text']))),
			'TAG_TEXT' => htmlspecialchars($tag['tag_text']),
			'TAG_FONT_SIZE' => $tag_font_size,
			'TAG_COUNT' => $tag['tag_count'],
			)
		);
		$i++;
	}
}

$template->assign_vars(array(
	'S_SHOW_CLOUD' => ($mode == 'cloud') ? true : false,
	'S_FORM_ACTION' => append_sid(CMS_PAGE_TAGS),
	'S_HIDDEN_FIELDS' => $s_hidden_fields,
	'S_SORT_ORDER_SELECT' => $sort_order_select_box,
	'S_SORT_DIR_SELECT' => $sort_dir_select_box,

	'U_TAGS_SEARCH_PAGE' => append_sid(CMS_PAGE_TAGS),
	'U_TAGS_SEARCH_REPLACE' => append_sid(CMS_PAGE_TAGS . '?mode=replace'),
	'U_TAGS' => append_sid(CMS_PAGE_TAGS),
	)
);

$pagination_append = ($mode == 'list') ? ('&amp;sort_order=' . $sort_order . '&amp;sort_dir=' . $sort_dir) : ('&amp;tag_text=' . $tag_text);

generate_full_pagination(CMS_PAGE_TAGS . '?mode=' . $mode . $pagination_append, $num_items, $per_page, $start);

full_page_generation($template_to_parse, $lang['TOPIC_TAGS'], '', '');

?>