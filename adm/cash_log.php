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
* Xore (mods@xore.ca)
*
*/

// CTracker_Ignore: File checked by human
define('IN_ICYPHOENIX', true);
define('IN_CASHMOD', true);

if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('./pagestart.' . PHP_EXT);

if ($config['cash_adminnavbar'])
{
	$navbar = 1;
	include('admin_cash.' . PHP_EXT);
}
$current_time = time();

$ar_time = array(
	"all" => "",
	"day" => "(log_time > " . ($current_time - 86400) . ")",
	"week" => "(log_time > " . ($current_time - 604800) . ")",
	"month" => "(log_time > " . ($current_time - 2592000) . ")",
	"year" => "(log_time > " . ($current_time - 31536000) . ")"
);

function lt($const)
{
	return "log_type = $const";
}
$action_types = array(
	CASH_LOG_DONATE => 'user',
	CASH_LOG_ADMIN_MODEDIT => 'admin',
	CASH_LOG_ADMIN_CREATE_CURRENCY => 'admin',
	CASH_LOG_ADMIN_DELETE_CURRENCY => 'admin',
	CASH_LOG_ADMIN_RENAME_CURRENCY => 'admin',
	CASH_LOG_ADMIN_COPY_CURRENCY => 'admin'
);

$action_users = array('user' => array(), 'admin' => array());
while (list($type,$user) = each ($action_types))
{
	$action_users[$user][] = lt($type);
}

$ar_action = array(
	'all' => "",
	'user' => "(" . implode(" OR ",$action_users['user']) . ")",
	'admin' => "(" . implode(" OR ",$action_users['admin']) . ")"
);

$ar_count = array(
	"a" => 10,
	"b" => 25,
	"c" => 50,
	"d" => 100
);

if (isset($_GET['delete']) &&
	 (($_GET['delete'] == "all") ||
	   ($_GET['delete'] == "admin") ||
	   ($_GET['delete'] == "user")))
{
	$deleteclause = $ar_action[$_GET['delete']];
	if ($deleteclause != "")
	{
		$deleteclause = " WHERE " . $deleteclause;
	}
	$sql = "DELETE FROM " . CASH_LOGS_TABLE . $deleteclause;
	$db->sql_query($sql);
}
//
// most of this is just stupid sorting stuff
// -- but then, that's mostly all the functionality that this page has :P
//

// The addslashes isn't really necessary, but it truncates the variable to a string if it's an array
$saction = isset($_GET['saction']) ? $_GET['saction'] : '';
$stime = isset($_GET['stime']) ? $_GET['stime'] : '';
$scount = isset($_GET['scount']) ? $_GET['scount'] : '';
$start = isset($_GET['start']) ? intval($_GET['start']) : 0;
$start = ($start < 0) ? 0 : $start;

$saction = (isset($ar_action[$saction])) ? $saction : 'all';
$stime = (isset($ar_time[$stime])) ? $stime : 'all';
$scount = (isset($ar_count[$scount])) ? $scount : 'b';

if (is_numeric($_GET['sindex']))
{
	$sindex = intval($_GET['sindex']);
	$sindex = max($sindex,0);
}
else
{
	$sindex = 0;
}
$clause = array();
if ($saction != 'all')
{

	$clause[] = $ar_action[$saction];

}
if ($stime != 'all')
{

	$clause[] = $ar_time[$stime];

}

$numactionfilters = sizeof($ar_action);
$numtimefilters = sizeof($ar_time);

$sql_clause = "";
if (sizeof($clause) != 0)
{
	$sql_clause = "WHERE " . implode(" AND ", $clause);
}

$sql = "SELECT count(log_id) AS log_items
	FROM " . CASH_LOGS_TABLE . "
	$sql_clause";
$result = $db->sql_query($sql);

if (!($row = $db->sql_fetchrow($result)))
{
	message_die(CRITICAL_ERROR, "Could not obtain log count", "", __LINE__, __FILE__, $sql);
}

$total = $row['log_items'];

$pagination = generate_pagination('cash_log.' . PHP_EXT . '?saction=' . $saction . '&amp;stime=' . $stime . '&amp;scount=' . $scount, max(1, $total), $ar_count[$scount], $start);


//
// Start page proper
//
$template->set_filenames(array('body' => ADM_TPL . 'cash_log.tpl'));

$template->assign_vars(array(
	'S_FORUM_ACTION' => append_sid('cash_forums.' . PHP_EXT),
	'L_LOG_TITLE' => $lang['Logs'],
	'L_LOG_EXPLAIN' => $lang['Logs_explain'],
	'L_LOG' => $lang['Log'],
	'L_TIME' => $lang['Time'],
	'L_TYPE' => $lang['Type'],
	'L_ACTION' => $lang['Action'],
	'L_PAGE' => $lang['Page'],
	'L_PER_PAGE' => $lang['Per_page'],
	'PAGINATION' => $pagination,

	'NUMACTIONFILTERS' => $numactionfilters,
	'NUMTIMEFILTERS' => $numtimefilters
	)
);

//
// Some more stuff (icky!)
// (it looks nice now that it's not hardcoded :P)
//
$i = 0;
while (list($key,) = each ($ar_action))
{
	$template->assign_block_vars('actionfilter', array(
		'ROW_CLASS' => ((!($i % 2)) ? $theme['td_class1'] : $theme['td_class2']),
		'NAME' => $lang['Cash_' . $key],
		'LINK' => append_sid('cash_log.' . PHP_EXT . '?saction=' . $key . '&amp;stime=' . $stime . '&amp;scount=' . $scount . '&amp;sindex=0'),
		'DELETE' => $lang['Delete_' . ' . $key . ' . '_logs'],
		'DELETECOMMAND' => append_sid('cash_log.' . PHP_EXT . '?delete=' . $key)
		)
	);
	if ($key != $saction)
	{
		$template->assign_block_vars('actionfilter.switch_linkpage_on', array());
	}
	else
	{
		$template->assign_block_vars('actionfilter.switch_linkpage_off', array());
	}
	$i++;
}
reset ($ar_action);
while (list($key,) = each ($ar_time))
{
	$template->assign_block_vars('timefilter', array(
		'ROW_CLASS' => ((!($i % 2)) ? $theme['td_class1'] : $theme['td_class2']),
		'NAME' => $lang[ucfirst($key)],
		'LINK' => append_sid('cash_log.' . PHP_EXT . '?saction=' . $saction . '&amp;stime=' . $key . '&amp;scount=' . $scount . '&amp;sindex=0')
		)
	);
	if ($key != $stime)
	{
		$template->assign_block_vars('timefilter.switch_linkpage_on', array());
	}
	else
	{
		$template->assign_block_vars('timefilter.switch_linkpage_off', array());
	}
	$i++;
}
reset ($ar_time);
while (list($key,$number) = each ($ar_count))
{
	$template->assign_block_vars("countfilter",array('NAME' => $number, 'LINK' => append_sid('cash_log.' . PHP_EXT . '?saction=' . $saction . '&amp;stime=' . $stime . '&amp;scount=' . $key . '&sindex=0')));
	if ($key != $scount)
	{
		$template->assign_block_vars('countfilter.switch_linkpage_on', array());
	}
	else
	{
		$template->assign_block_vars('countfilter.switch_linkpage_off', array());
	}
}
reset ($ar_count);

//$start = $ar_count[$scount] * $sindex;
$range = $ar_count[$scount];
$data_log = array();
$sql = "SELECT *
	FROM " . CASH_LOGS_TABLE . "
	$sql_clause
	ORDER BY log_time DESC
	LIMIT $start, $range";
$result = $db->sql_query($sql);

while ($row = $db->sql_fetchrow($result))
{
	$data_log[] = $row;
}

$i = 0;

for ($i = 0; $i < sizeof($data_log); $i++)
{
	$entry = $data_log[$i];
	$entry['log_time'] = create_date($config['default_dateformat'], $entry['log_time'], $config['board_timezone']);
	$entry['log_action'] = '<span class="gen">' . cash_clause($lang['Cash_clause'][$entry['log_type']],$entry['log_action']) . '</span>';
	$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];

	$template->assign_block_vars('logrow', array(
		'TIME' => $entry['log_time'],
		'TEXT' => nl2br($entry['log_text']),
		'TYPE' => $lang['Cash_' . $action_types[$entry['log_type']]],
		'ACTION' => $entry['log_action'],
		'ROW_CLASS' => $row_class,
		)
	);
}

$template->pparse('body');

include('./page_footer_admin.' . PHP_EXT);

?>