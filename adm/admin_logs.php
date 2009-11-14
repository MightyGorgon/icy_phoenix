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
define('IN_ICYPHOENIX', true);

// Mighty Gorgon - ACP Privacy - BEGIN
if (function_exists('check_acp_module_access'))
{
	$is_allowed = check_acp_module_access();
	if ($is_allowed == false)
	{
		return;
	}
}
// Mighty Gorgon - ACP Privacy - END

if (!empty($setmodules))
{
	$filename = basename(__FILE__);
	$module['1400_DB_Maintenance']['100_Actions_LOG'] = $filename;
	$ja_module['1400_DB_Maintenance']['100_Actions_LOG'] = false;
	return;
}

if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('pagestart.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_mg_log_admin.' . PHP_EXT);

// Mighty Gorgon - ACP Privacy - BEGIN
$is_allowed = check_acp_module_access();
if ($is_allowed == false)
{
	message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
}
// Mighty Gorgon - ACP Privacy - END

$founder_id = (defined('FOUNDER_ID') ? FOUNDER_ID : get_founder_id());
$is_allowed = ($userdata['user_id'] == $founder_id) ? true : false;

$template->set_filenames(array('body' => ADM_TPL . 'admin_logs_body.tpl'));

if (isset($_POST['clear']))
{
	if ($is_allowed)
	{
		$sql = "DELETE FROM " . LOGS_TABLE;
		$db->sql_query($sql);
		delete_error_log_file();
	}
	else
	{
		message_die(GENERAL_ERROR, $lang['LOGS_DENY']);
	}
}

if (isset($_POST['delete_sub']))
{
	if ($is_allowed)
	{
		$file_array = array();
		foreach($_POST as $key => $valx)
		{
			if (substr_count($key, 'delete_id_'))
			{
				$log_id = substr($key, 10);

				$sql = "DELETE FROM " . LOGS_TABLE . "
								WHERE log_id = '" . $log_id . "'";
				$db->sql_query($sql);
			}
			$file_array[] = 'error_log_' . $log_id . '.txt';
		}
		delete_error_log_file($file_array);
	}
	else
	{
		message_die(GENERAL_ERROR, $lang['LOGS_DENY']);
	}
}

$start = request_var('start', 0);
$start = ($start < 0) ? 0 : $start;

$sort_order_array = array('log_id', 'log_time', 'log_page', 'log_user_id', 'log_action', 'log_desc', 'log_target');
$sort_order = request_var('sort_order', $sort_order_array[0]);
$sort_order = (in_array($sort_order, $sort_order_array) ? $sort_order : $sort_order_array[0]);
$sort_dir = request_var('sort_dir', 'DESC');
$sort_dir = ($sort_dir == 'ASC') ? 'ASC' : 'DESC';

$logs_actions_filter = request_var('logs_actions_filter', 'ALL');

$log_item = array();
$log_item = get_logs('', $start, $config['topics_per_page'], $sort_order, $sort_dir, $logs_actions_filter);

foreach ($log_item as $log_item_data)
{
	$log_username = colorize_username($log_item_data['log_user_id']);
	$log_target = ($log_item_data['log_target'] >= 2) ? colorize_username($log_item_data['log_target']) : '&nbsp;';
	$log_action = parse_logs_action($log_item_data['log_id'], $log_item_data['log_action'], $log_item_data['log_desc'], $log_username, $log_target);
	$template->assign_block_vars('log_row', array(
			'LOG_ID' => $log_item_data['log_id'],
			'LOG_TIME' => create_date_ip($config['default_dateformat'], $log_item_data['log_time'], $config['board_timezone']),
			'LOG_PAGE' => htmlspecialchars($log_item_data['log_page']),
			'LOG_ACTION' => $log_item_data['log_action'],
			'LOG_USERNAME' => $log_username,
			'LOG_TARGET' => $log_target,
			'LOG_DESC' => $log_action['desc'],
			'S_LOG_DESC_EXTRA' => ($log_action['desc_extra'] != '') ? true : false,
			'LOG_DESC_EXTRA' => $log_action['desc_extra'],
		)
	);
}

$logs_actions_filter_select = actions_filter_select($logs_actions_filter);

$sort_lang = ($sort_dir == 'ASC') ? $lang['Sort_Ascending'] : $lang['Sort_Descending'];
$sort_img = ($sort_dir == 'ASC') ? 'images/sort_asc.png' : 'images/sort_desc.png';
$sort_img_full = '<img src="' . IP_ROOT_PATH . $sort_img . '" alt="' . $sort_lang . '" title="' . $sort_lang . '" style="padding-left: 3px;" />';
$sort_order_append = '&amp;sort_order=' . $sort_order;
$sort_dir_append = '&amp;sort_dir=' . $sort_dir;
$logs_actions_filter_append = ($logs_actions_filter == 'ALL') ? '' : ('&amp;logs_actions_filter=' . $logs_actions_filter);
$sort_dir_append_rev = '&amp;sort_dir=' . (($sort_dir == 'ASC') ? 'DESC' : 'ASC');
$this_page_address = 'admin_logs.' . PHP_EXT . '?' . $sort_dir_append_rev . $logs_actions_filter_append;

$sort_order_array = array('log_id', 'log_time', 'log_page', 'log_user_id', 'log_action', 'log_desc', 'log_target');
$template->assign_vars(array(
	'L_TITLE' => $lang['LOGS_TITLE'],
	'L_TITLE_EXPLAIN' => $lang['LOGS_EXPLAIN'],

	'U_LOG_ID_SORT' => append_sid($this_page_address . '&amp;sort_order=log_id'),
	'U_LOG_TIME_SORT' => append_sid($this_page_address . '&amp;sort_order=log_time'),
	'U_LOG_PAGE_SORT' => append_sid($this_page_address . '&amp;sort_order=log_page'),
	'U_LOG_USER_ID_SORT' => append_sid($this_page_address . '&amp;sort_order=log_user_id'),
	'U_LOG_ACTION_SORT' => append_sid($this_page_address . '&amp;sort_order=log_action'),
	'U_LOG_DESC_SORT' => append_sid($this_page_address . '&amp;sort_order=log_desc'),
	'U_LOG_TARGET_SORT' => append_sid($this_page_address . '&amp;sort_order=log_target'),

	'LOG_ID_SORT' => (($sort_order == 'log_id') ? $sort_img_full : ''),
	'LOG_TIME_SORT' => (($sort_order == 'log_time') ? $sort_img_full : ''),
	'LOG_PAGE_SORT' => (($sort_order == 'log_page') ? $sort_img_full : ''),
	'LOG_USER_ID_SORT' => (($sort_order == 'log_user_id') ? $sort_img_full : ''),
	'LOG_ACTION_SORT' => (($sort_order == 'log_action') ? $sort_img_full : ''),
	'LOG_DESC_SORT' => (($sort_order == 'log_desc') ? $sort_img_full : ''),
	'LOG_TARGET_SORT' => (($sort_order == 'log_target') ? $sort_img_full : ''),

	'L_CURRENT_SORT' => $sort_lang,

	'LOGS_ACTIONS_FILTER' => $logs_actions_filter_select,
	'S_MODE_ACTION' => append_sid('admin_logs.' . PHP_EXT)
	)
);

// Pagination
$logs_actions_filter_sql = (($logs_actions_filter == 'ALL') ? '' : (' WHERE log_action = \'' . $logs_actions_filter . '\''));
$sql = "SELECT count(*) AS total
				FROM " . LOGS_TABLE . "
				" . $logs_actions_filter_sql;
$result = $db->sql_query($sql);

if ($total = $db->sql_fetchrow($result))
{
	$total_logs = $total['total'];
	$pagination = generate_pagination('admin_logs.' . PHP_EXT . '?mode=list' . $logs_actions_filter_append . $sort_order_append . $sort_dir_append, $total_logs , $config['topics_per_page'], $start);
}
$db->sql_freeresult($result);

$template->assign_vars(array(
	'PAGINATION' => $pagination,
	'PAGE_NUMBER' => sprintf($lang['Page_of'], (floor($start / $config['topics_per_page']) + 1), ceil($total_logs / $config['topics_per_page'])),
	'L_GOTO_PAGE' => $lang['Goto_page']
	)
);

$template->pparse('body');

include('./page_footer_admin.' . PHP_EXT);

?>