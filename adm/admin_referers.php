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
* Bicet (bicets@gmail.com)
*
*/

define('IN_ICYPHOENIX', true);

if(!empty($setmodules))
{
	$file = basename(__FILE__);
	$module['1000_Configuration']['197_HTTP_REF'] = $file . '?mode=config';
	return;
}

if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('pagestart.' . PHP_EXT);

$referer_id = request_var('referer_id', 0);
$ref_kill_text = request_var('ref_kill_text', '', true);

$start = request_var('start', 0);
$start = ($start < 0) ? 0 : $start;

// Referers sorting
// SMART DELETE - BEGIN
$smart_delete_select_lang_array = array($lang['REFERER_HOST'], $lang['REFERER_URL'], $lang['REFERER_T_URL'], $lang['REFERER_IP']);
$smart_delete_select_array = array('host', 'url', 't_url', 'ip');
$smart_delete = request_var('ref_kill_select', $smart_delete_select_array[0]);
$smart_delete = (in_array($smart_delete, $smart_delete_select_array) ? $smart_delete : $smart_delete_select_array[0]);
$select_name = 'ref_kill_select';
$default = $smart_delete_select_array[0];
$select_js = '';
$smart_delete_select_box = $class_form->build_select_box($select_name, $default, $smart_delete_select_array, $smart_delete_select_lang_array, $select_js);
// SMART DELETE - END

// GROUP BY - BEGIN
$group_by_select_lang_array = array($lang['None'], $lang['REFERER_HOST'], $lang['REFERER_URL'], $lang['REFERER_T_URL'], $lang['REFERER_IP']);
$group_by_select_array = array('0', 'host', 'url', 't_url', 'ip');
$group_by = request_var('group_by', $group_by_select_array[0]);
$group_by = (in_array($group_by, $group_by_select_array) ? $group_by : $group_by_select_array[0]);
$select_name = 'group_by';
$default = $group_by;
$select_js = '';
$group_by_select_box = $class_form->build_select_box($select_name, $default, $group_by_select_array, $group_by_select_lang_array, $select_js);
// GROUP BY - END

// SORT ORDER - BEGIN
$sort_order_select_lang_array = array($lang['REFERER_HITS'], $lang['REFERER_HOST'], $lang['REFERER_URL'], $lang['REFERER_T_URL'], $lang['REFERER_IP'], $lang['REFERER_FIRST'],  $lang['REFERER_LAST']);
$sort_order_select_array = array('hits', 'host', 'url', 't_url', 'ip', 'first_visit', 'last_visit');
$mode = request_var('mode', $sort_order_select_array[0]);
$mode = (in_array($mode, $sort_order_select_array) ? $mode : $sort_order_select_array[0]);
$select_name = 'mode';
$default = $mode;
$select_js = '';
$sort_order_select_box = $class_form->build_select_box($select_name, $default, $sort_order_select_array, $sort_order_select_lang_array, $select_js);
// SORT ORDER - END

// SORT DIR - BEGIN
$sort_dir_select_array = array('ASC', 'DESC');
$sort_dir_select_lang_array = array($lang['Sort_Ascending'], $lang['Sort_Descending']);
$sort_dir = request_var('order', 'DESC');
$sort_dir = check_var_value($sort_dir, array('DESC', 'ASC'));
$select_name = 'order';
$default = ($sort_dir == 'ASC') ? 'ASC' : 'DESC';
$select_js = '';
$sort_dir_select_box = $class_form->build_select_box($select_name, $default, $sort_dir_select_array, $sort_dir_select_lang_array, $select_js);
// SORT DIR - END

$smart_delete_fields_array = array(
	'host' => 'host',
	'url' => 'url',
	't_url' => 't_url',
	'ip' => 'ip',
);
$smart_delete_field = isset($smart_delete_fields_array[$smart_delete]) ? $smart_delete_fields_array[$smart_delete] : $smart_delete_fields_array[0];

$modes_array = array(
	'hits' => 'hits',
	'host' => 'host',
	'url' => 'url',
	't_url' => 't_url',
	'ip' => 'ip',
	'first_visit' => 'firstvisit',
	'last_visit' => 'lastvisit',
);
$order_by = isset($modes_array[$mode]) ? $modes_array[$mode] : $modes_array[0];

if (isset($_POST['clear']))
{
	$sql = "DELETE FROM " . REFERERS_TABLE;
	$db->sql_query($sql);

	$message = $lang['REFERERS_CLEARED'] . '<br /><br />' . sprintf($lang['CLICK_RETURN_REFERERS'], '<a href="' . append_sid('admin_referers.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

	message_die(GENERAL_MESSAGE, $message);
}

if (isset($_POST['ref_kill']) && !empty($ref_kill_text) && (strlen($ref_kill_text) > 3))
{
	$ref_kill_text = str_replace('*', '%', $ref_kill_text);
	$sql = "DELETE FROM " . REFERERS_TABLE . " WHERE " . $smart_delete_field . " LIKE '" . $db->sql_escape($ref_kill_text) . "'";
	$db->sql_query($sql);

	$message = $lang['REFERERS_CLEARED'] . '<br /><br />' . sprintf($lang['CLICK_RETURN_REFERERS'], '<a href="' . append_sid('admin_referers.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

	message_die(GENERAL_MESSAGE, $message);
}

if (sizeof($_POST))
{
	$referer_ids = array();
	foreach($_POST as $key => $valx)
	{
		// Check for deletion items
		if (substr_count($key, 'delete_id_'))
		{
			$referer_ids[] = substr($key, strlen('delete_id_'));
		}
	}

	$sql = "DELETE FROM " . REFERERS_TABLE . " WHERE host = ''";
	$db->sql_query($sql);

	if (!empty($referer_ids))
	{
		$del_sql = implode(',', $referer_ids);
		$sql = "DELETE FROM " . REFERERS_TABLE ."
						WHERE id IN (" . $del_sql . ")";
		$db->sql_query($sql);
	}
}

$template->set_filenames(array('body' => ADM_TPL . 'admin_referers_body.tpl'));

$template->assign_vars(array(
	'L_CLEAR' => $lang['REFERERS_CLEAR'],
	'L_TITLE' => $lang['REFERERS_TITLE'],
	'L_SELECT_SORT_METHOD' => $lang['Select_sort_method'],
	'L_SUBMIT' => $lang['Sort'],
	'L_ORDER' => $lang['Order'],
	'L_SORT' => $lang['Sort'],
	'L_HOST' => $lang['REFERER_HOST'],
	'L_URL' => $lang['REFERER_URL'],
	'L_IP' => $lang['REFERER_IP'],
	'L_HITS' => $lang['REFERER_HITS'],
	'L_FIRST' => $lang['REFERER_FIRST'],
	'L_LAST' => $lang['REFERER_LAST'],
	'L_DELETE' => $lang['REFERER_DELETE'],
	'L_SELECT' => $lang['Select'],
	'S_SMART_DELETE_SELECT' => $smart_delete_select_box,
	'S_GROUP_BY_SELECT' => $group_by_select_box,
	'S_MODE_SELECT' => $sort_order_select_box,
	'S_ORDER_SELECT' => $sort_dir_select_box,
	'S_MODE_ACTION' => append_sid('admin_referers.' . PHP_EXT),
	'S_DELETE_ACTION' => append_sid('admin_referers.' . PHP_EXT)
	)
);

$total_sql = '';
$select_sql = '';
$group_by_sql = '';
$total_hits = false;
if (!empty($group_by))
{
	$group_by_array = array(
		'host' => 'host',
		'url' => 'url',
		't_url' => 't_url',
		'ip' => 'ip',
	);
	$group_by_field = isset($group_by_array[$group_by]) ? $group_by_array[$group_by] : $group_by_array[0];

	$total_hits = true;
	$select_sql = ", SUM(hits) AS total_hits ";
	$group_by_sql = " GROUP BY " . $group_by_field . " ";
	$total_sql = "SELECT count(distinct(" . $group_by_field . ")) AS total FROM " . REFERERS_TABLE;
	$order_by = ($mode == 'hits') ? 'total_hits' : $order_by;
}

$order_by = $group_by_sql . " ORDER BY " . $order_by . " " . $sort_dir . " LIMIT $start, " . $config['topics_per_page'];

$sql = "SELECT *" . $select_sql . " FROM " . REFERERS_TABLE . $order_by;
$result = $db->sql_query($sql);

$i = 0;
while($row = $db->sql_fetchrow($result))
{
	$row_class = ($i % 2) ? $theme['td_class2'] : $theme['td_class1'];

	$max_chars = 50;
	$url_name = (strlen($row['url']) > $max_chars) ? (substr($row['url'], 0, $max_chars) . '...') : $row['url'];
	$t_url_name = (strlen($row['t_url']) > $max_chars) ? (substr($row['t_url'], 0, $max_chars) . '...') : $row['t_url'];

	$template->assign_block_vars('refersrow', array(
		'ID' => $i + ($start + 1),
		'REFER_ID' => $row['id'],
		'ROW_CLASS' => $row_class,
		'HOST' => $row['host'],
		'URL' => '<a href="' . htmlspecialchars($row['url']) . '" rel="nofollow" target="_blank">' . htmlspecialchars($url_name) . '</a>',
		'T_URL' => '<a href="' . append_sid(IP_ROOT_PATH . $row['t_url']) . '" target="_blank">' . htmlspecialchars($t_url_name) . '</a>',
		'IP' => '<a href="http://whois.sc/' . htmlspecialchars(urlencode($row['ip'])) . '" target="_blank">' . htmlspecialchars($row['ip']) . '</a>',
		'HITS' => $total_hits ? $row['total_hits'] : $row['hits'],
		'FIRST' => create_date_ip($config['default_dateformat'], $row['firstvisit'], $config['board_timezone']),
		'LAST' => create_date_ip($config['default_dateformat'], $row['lastvisit'], $config['board_timezone'])
		)
	);

	$i++;
}

$sql = !empty($total_sql) ? $total_sql : ("SELECT count(*) AS total FROM " . REFERERS_TABLE);
$result = $db->sql_query($sql);
if ($total = $db->sql_fetchrow($result))
{
	$total_referers = $total['total'];
	$pagination = generate_pagination('admin_referers.' . PHP_EXT . '?mode=' . $mode . '&amp;order=' . $sort_dir . '&amp;group_by=' . $group_by, $total_referers , $config['topics_per_page'], $start) . '&nbsp;';
}
$db->sql_freeresult($result);

$template->assign_vars(array(
	'PAGINATION' => $pagination,
	'PAGE_NUMBER' => sprintf($lang['Page_of'], (floor($start / $config['topics_per_page']) + 1), ceil($total_referers / $config['topics_per_page'])),
	'L_GOTO_PAGE' => $lang['Goto_page']
	)
);

$template->pparse('body');

include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);

?>