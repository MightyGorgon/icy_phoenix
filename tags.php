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
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

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

$tag_text = request_var('tag_text', '');
$tag_text = ip_clean_string(urldecode(trim($tag_text)), $lang['ENCODING'], true);

$mode_types = array('list', 'view');
$mode = request_var('mode', $mode_types[0]);
$mode = (!in_array($mode, $mode_types) ? $mode_types[0] : $mode);

$action_types = array('list');
$action = request_var('action', $action_types[0]);
$action = (!in_array($action, $action_types) ? $action_types[0] : $action);

$start = request_var('start', 0);
$start = ($start < 0) ? 0 : $start;

$per_page = request_var('per_page', 0);
$per_page = (($per_page < 20) || ($per_page > 200)) ? $config['topics_per_page'] : $per_page;

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
$sort_dir_default = ((isset($sort_dir_default) && in_array($sort_dir_default, array('ASC', 'DESC'))) ? $sort_dir_default : 'ASC');
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

	$template_to_parse = 'tags_view_body.tpl';

	$tags = array($tag_text);
	$num_items = $class_topics_tags->get_total_tags();
	$topics = $class_topics_tags->get_topics_with_tags($tags, $start, $per_page);

	foreach ($topics as $topic)
	{

	}
}
else
{
	$template_to_parse = 'tags_list_body.tpl';

	$num_items = $class_topics_tags->get_total_tags();
	$tags = $class_topics_tags->get_tags($start, $per_page);

	$i = 0;
	foreach ($tags as $tag)
	{
		$class = ($i % 2) ? $theme['td_class1'] : $theme['td_class2'];

		$template->assign_block_vars('row', array(
			'CLASS' => $class,
			'ROW_NUMBER' => $i + 1,

			'U_TAG_TEXT' => append_sid(CMS_PAGE_TAGS . '?tag_text=' . htmlspecialchars(urlencode($tag['tag_text']))),
			'TAG_TEXT' => htmlspecialchars($tag['tag_text']),
			'TAG_COUNT' => $tag['tag_count'],
			)
		);
		$i++;
	}
}

$template->assign_vars(array(
	'S_FORM_ACTION' => append_sid(CMS_PAGE_TAGS),
	'S_HIDDEN_FIELDS' => $s_hidden_fields,
	'S_SORT_ORDER_SELECT' => $sort_order_select_box,
	'S_SORT_DIR_SELECT' => $sort_dir_select_box,

	'U_TAGS' => append_sid(CMS_PAGE_TAGS),
	)
);

generate_full_pagination(CMS_PAGE_TAGS . '?mode=list', $num_items, $per_page, $start);

full_page_generation($template_to_parse, $lang['TOPIC_TAGS'], '', '');

?>