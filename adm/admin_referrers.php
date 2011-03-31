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
	$module['2400_INFO']['140_HTTP_REF'] = $file . '?mode=config';
	return;
}

if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('pagestart.' . PHP_EXT);

if (isset($_POST['clear']))
{
	$sql = "DELETE FROM " . REFERRERS_TABLE;
	$db->sql_query($sql);

	$message = $lang['Referrers_Cleared'] . '<br /><br />' . sprintf($lang['Click_Return_Referrers'], '<a href="' . append_sid('admin_referrers.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

	message_die(GENERAL_MESSAGE, $message);
}

$mode = request_var('mode', 'hits');
$referrer_id = request_var('referrer_id', 0);

$start = request_var('start', 0);
$start = ($start < 0) ? 0 : $start;

$sort_order = request_var('order', 'DESC');
$sort_order = check_var_value($sort_order, array('DESC', 'ASC'));

// Referrers sorting
$mode_types_text = array($lang['Referrer_host'], $lang['Referrer_url'], $lang['Referrer_hits'], $lang['Referrer_ip'], $lang['Referrer_first'],  $lang['Referrer_last']);
$mode_types = array('host', 'url', 'hits', 'ip', 'first_visit', 'last_visit');

$select_sort_mode = '<select name="mode">';
for($i = 0; $i < sizeof($mode_types_text); $i++)
{
	$selected = ($mode == $mode_types[$i]) ? ' selected="selected"' : '';
	$select_sort_mode .= '<option value="' . $mode_types[$i] . '"' . $selected . '>' . $mode_types_text[$i] . '</option>';
}
$select_sort_mode .= '</select>';

$select_sort_order = '<select name="order">';
if($sort_order == 'ASC')
{
	$select_sort_order .= '<option value="ASC" selected="selected">' . $lang['Sort_Ascending'] . '</option><option value="DESC">' . $lang['Sort_Descending'] . '</option>';
}
else
{
	$select_sort_order .= '<option value="ASC">' . $lang['Sort_Ascending'] . '</option><option value="DESC" selected="selected">' . $lang['Sort_Descending'] . '</option>';
}
$select_sort_order .= '</select>';

if (sizeof($_POST))
{
	$referrer_ids = array();
	foreach($_POST as $key => $valx)
	{
		// Check for deletion items
		if (substr_count($key, 'delete_id_'))
		{
			$referrer_ids[] = substr($key, strlen('delete_id_'));
		}
	}

	if (!empty($referrer_ids))
	{
		$del_sql = implode(',', $referrer_ids);
		$sql = "DELETE FROM " . REFERRERS_TABLE ."
						WHERE referrer_id IN (" . $del_sql . ")";
		$db->sql_query($sql);
	}
}

$template->set_filenames(array('body' => ADM_TPL . 'admin_referrers_body.tpl'));

$template->assign_vars(array(
	'L_CLEAR' => $lang['Referrers_Clear'],
	'L_TITLE' => $lang['Referrers_Title'],
	'L_SELECT_SORT_METHOD' => $lang['Select_sort_method'],
	'L_SUBMIT' => $lang['Sort'],
	'L_ORDER' => $lang['Order'],
	'L_SORT' => $lang['Sort'],
	'L_HOST' => $lang['Referrer_host'],
	'L_URL' => $lang['Referrer_url'],
	'L_IP' => $lang['Referrer_ip'],
	'L_HITS' => $lang['Referrer_hits'],
	'L_FIRST' => $lang['Referrer_first'],
	'L_LAST' => $lang['Referrer_last'],
	'L_DELETE' => $lang['Referrer_delete'],
	'L_SELECT' => $lang['Select'],
	'L_MARK_ALL' => $lang['Mark_all'],
	'L_UNMARK_ALL'=> $lang['Unmark_all'],
	'S_MODE_SELECT' => $select_sort_mode,
	'S_ORDER_SELECT' => $select_sort_order,
	'S_MODE_ACTION' => append_sid('admin_referrers.' . PHP_EXT)
	)
);

switch($mode)
{
	case 'host':
		$order_by = "referrer_host $sort_order LIMIT $start, " . $config['topics_per_page'];
		break;
	case 'url':
		$order_by = "referrer_url $sort_order LIMIT $start, " . $config['topics_per_page'];
		break;
	case 'hits':
		$order_by = "referrer_hits $sort_order LIMIT $start, " . $config['topics_per_page'];
		break;
	case 'ip':
		$order_by = "referrer_ip $sort_order LIMIT $start, " . $config['topics_per_page'];
		break;
	case 'first_visit':
		$order_by = "referrer_firstvisit $sort_order LIMIT $start, " . $config['topics_per_page'];
		break;
	case 'last_visit':
		$order_by = "referrer_lastvisit $sort_order LIMIT $start, " . $config['topics_per_page'];
		break;
	default:
		$order_by = "referrer_hits $sort_order LIMIT $start, " . $config['topics_per_page'];
		break;
}

//	Gathering required Information from referrers Table
$sql = "SELECT * FROM " . REFERRERS_TABLE ." ORDER BY $order_by";
$result = $db->sql_query($sql);

$i = 0;

while($row = $db->sql_fetchrow($result))
{

	$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];
	$url_name = (strlen($row['referrer_url']) > 50) ? (substr($row['referrer_url'], 0, 50) . '...') : $row['referrer_url'];// Cut Url name if it is longer than 50 chars

	$template->assign_block_vars('refersrow', array(
		'ID' => $i + ($start + 1),
		'REFER_ID' => $row['referrer_id'],
		'ROW_CLASS' => $row_class,
		'HOST' => $row['referrer_host'],
		'URL' => '<a href="' . htmlspecialchars($row['referrer_url']) . '" rel="nofollow" target="_blank">' . htmlspecialchars($url_name) . '</a>',
		'IP' => '<a href="http://whois.sc/' . htmlspecialchars(urlencode($row['referrer_ip'])) . '" target="_blank">' . htmlspecialchars($row['referrer_ip']) . '</a>',
		'HITS' => $row['referrer_hits'],
		'FIRST' => create_date_ip($config['default_dateformat'], $row['referrer_firstvisit'], $config['board_timezone']),
		'LAST' => create_date_ip($config['default_dateformat'], $row['referrer_lastvisit'], $config['board_timezone'])
		)
	);

	//Check Level of User
	if (($user->data['user_level'] == ADMIN) || ($user->data['user_level'] == MOD))
	{
		$template->assign_block_vars('refersrow.switch_admin_or_mod',array());
	}
	$i++;
}

$sql = "SELECT count(*) AS total
			FROM " . REFERRERS_TABLE ;
$result = $db->sql_query($sql);
if ($total = $db->sql_fetchrow($result))
{
	$total_referrers = $total['total'];
	$pagination = generate_pagination('admin_referrers.' . PHP_EXT . '?mode=' . $mode . '&amp;order=' . $sort_order, $total_referrers , $config['topics_per_page'], $start). '&nbsp;';
}
$db->sql_freeresult($result);

$template->assign_vars(array(
	'PAGINATION' => $pagination,
	'PAGE_NUMBER' => sprintf($lang['Page_of'], (floor($start / $config['topics_per_page']) + 1), ceil($total_referrers  / $config['topics_per_page'])),
	'L_GOTO_PAGE' => $lang['Goto_page']
	)
);

$template->pparse('body');

include('./page_footer_admin.' . PHP_EXT);

?>