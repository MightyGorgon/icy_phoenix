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
require('./pagestart.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_groups.' . PHP_EXT);
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
		if (!$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Failed to update logs table', '', __LINE__, __FILE__, $sql);
		}
		delete_error_log_file();
	}
	else
	{
		message_die(GENERAL_ERROR, $lang['LOGS_DENY']);
	}
}

if (count($_POST))
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
				if(!$db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'Failed to update logs table', '', __LINE__, __FILE__, $sql);
				}
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

$start = (isset($_GET['start'])) ? intval($_GET['start']) : 0;
$start = ($start < 0) ? 0 : $start;

$log_item = array();
$log_item = get_logs('', $start, $board_config['posts_per_page'], 'log_id', 'DESC');

foreach ($log_item as $log_item_data)
{
	$log_username = colorize_username($log_item_data['log_user_id']);
	$log_target = colorize_username($log_item_data['log_target']);
	$log_action = parse_logs_action($log_item_data['log_id'], $log_item_data['log_action'], $log_item_data['log_desc'], $log_username, $log_target);
	$template->assign_block_vars('log_row', array(
			'LOG_ID' => $log_item_data['log_id'],
			'LOG_TIME' => create_date2($board_config['default_dateformat'], $log_item_data['log_time'], $board_config['board_timezone']),
			'LOG_PAGE' => $log_item_data['log_page'],
			'LOG_ACTION' => $log_item_data['log_action'],
			'LOG_USERNAME' => $log_username,
			'LOG_TARGET' => $log_target,
			'LOG_DESC' => $log_action['desc'],
			'S_LOG_DESC_EXTRA' => ($log_action['desc_extra'] != '') ? true : false,
			'LOG_DESC_EXTRA' => $log_action['desc_extra'],
		)
	);
}

$sql = "SELECT count(*) AS total
				FROM " . LOGS_TABLE ;
if (!($result = $db->sql_query($sql)))
{
	message_die(GENERAL_ERROR, 'Error getting total logs', '', __LINE__, __FILE__, $sql);
}
if ($total = $db->sql_fetchrow($result))
{
	$total_logs = $total['total'];
	$pagination = generate_pagination('admin_logs.' . PHP_EXT . '?mode=list', $total_logs , '30', $start). '&nbsp;';
}
$db->sql_freeresult($result);

$template->assign_vars(array(
	'PAGINATION' => $pagination,
	'PAGE_NUMBER' => sprintf($lang['Page_of'], (floor($start / 30) + 1), ceil($total_logs  / 30)),
	'L_GOTO_PAGE' => $lang['Goto_page']
	)
);

$template->assign_vars(array(
	'L_TITLE' => $lang['LOGS_TITLE'],
	'L_TITLE_EXPLAIN' => $lang['LOGS_EXPLAIN'],
	'S_MODE_ACTION' => append_sid('admin_logs.' . PHP_EXT)
	)
);

$template->pparse('body');

include('./page_footer_admin.' . PHP_EXT);

?>