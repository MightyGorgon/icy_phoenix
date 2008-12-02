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
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

if ($board_config['disable_referrers'] == true)
{
	message_die(GENERAL_MESSAGE, $lang['Feature_Disabled']);
}

$cms_page_id = '18';
$cms_page_name = 'referrers';
check_page_auth($cms_page_id, $cms_page_name);
$cms_global_blocks = ($board_config['wide_blocks_' . $cms_page_name] == 1) ? true : false;

$start = (isset($_GET['start'])) ? intval($_GET['start']) : 0;
$start = ($start < 0) ? 0 : $start;

if (isset($_GET['mode']) || isset($_POST['mode']))
{
	$mode = (isset($_POST['mode'])) ? htmlspecialchars($_POST['mode']) : htmlspecialchars($_GET['mode']);
}
else
{
	$mode = 'hits';
}

if(isset($_POST['order']))
{
	$sort_order = ($_POST['order'] == 'ASC') ? 'ASC' : 'DESC';
}
elseif(isset($_GET['order']))
{
	$sort_order = ($_GET['order'] == 'ASC') ? 'ASC' : 'DESC';
}
else
{
	$sort_order = 'DESC';
}

// Referrers sorting
$mode_types_text = array($lang['Referrer_host'], $lang['Referrer_url'], $lang['Referrer_hits'], $lang['Referrer_ip'], $lang['Referrer_first'],  $lang['Referrer_last']);
$mode_types = array('host', 'url', 'hits', 'ip', 'first_visit', 'last_visit');

$select_sort_mode = '<select name="mode">';
for($i = 0; $i < count($mode_types_text); $i++)
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

//Referrers Deletion
$params = array('mode' => '', 'referrer_id' => '');

foreach($params as $var => $default)
{
	$$var = $default;
	if(isset($_POST[$var]) || isset($_GET[$var]))
	{
		$$var = (isset($_POST[$var])) ? $_POST[$var] : $_GET[$var];
	}
}

if (count($_POST))
{
	foreach($_POST as $key => $valx)
	{
		// Check for deletion items
		if (substr_count($key, 'delete_id_'))
		{
			$referrer_id = substr($key, 10);

			$sql = "SELECT * FROM " . REFERRERS_TABLE ."
				WHERE referrer_id = $referrer_id";
			if(!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Error on querying referrers Table', '', __LINE__, __FILE__, $sql);
			}

			$sql = "DELETE FROM " . REFERRERS_TABLE ."
				WHERE referrer_id = $referrer_id";
			if(!$db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Error on deleting from referrers Table', '', __LINE__, __FILE__, $sql);
			}
		}
	}
}

// Start output of page
$page_title = $lang['Referrers'];
$meta_description = '';
$meta_keywords = '';
include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);

$template->set_filenames(array('body' => 'referrers_body.tpl'));
make_jumpbox(VIEWFORUM_MG);

$template->assign_vars(array(
	'L_SELECT_SORT_METHOD' => $lang['Select_sort_method'],
	'L_SUBMIT' => $lang['Sort'],
	'L_ORDER' => $lang['Order'],
	'L_SORT' => $lang['Sort'],
	'L_HOST' => $lang['Referrer_host'],
	'L_URL' => $lang['Referrer_url'],
	'L_IP' =>	$lang['Referrer_ip'],
	'L_HITS' => $lang['Referrer_hits'],
	'L_FIRST' => $lang['Referrer_first'],
	'L_LAST' => $lang['Referrer_last'],
	'L_DELETE' => $lang['Referrer_delete'],
	'L_SELECT' => $lang['Select'],
	'L_MARK_ALL' => $lang['Mark_all'],
	'L_UNMARK_ALL'=> $lang['Unmark_all'],
	'S_MODE_SELECT' => $select_sort_mode,
	'S_ORDER_SELECT' => $select_sort_order,
	'S_MODE_ACTION' => append_sid('referrers.' . PHP_EXT)
	)
);

//Check Level of User
if (($userdata['user_level'] == ADMIN) || ($userdata['user_level'] == MOD))
{
	$template->assign_block_vars('switch_admin_or_mod',array());
}

switch($mode)
{
	case 'host':
		$order_by = "referrer_host $sort_order LIMIT $start, " . $board_config['topics_per_page'];
		break;
	case 'url':
		$order_by = "referrer_url $sort_order LIMIT $start, " . $board_config['topics_per_page'];
		break;
	case 'hits':
		$order_by = "referrer_hits $sort_order LIMIT $start, " . $board_config['topics_per_page'];
		break;
	case 'ip':
		$order_by = "referrer_ip $sort_order LIMIT $start, " . $board_config['topics_per_page'];
		break;
	case 'first_visit':
		$order_by = "referrer_firstvisit $sort_order LIMIT $start, " . $board_config['topics_per_page'];
		break;
	case 'last_visit':
		$order_by = "referrer_lastvisit $sort_order LIMIT $start, " . $board_config['topics_per_page'];
		break;
	default:
		$order_by = "referrer_hits $sort_order LIMIT $start, " . $board_config['topics_per_page'];
		break;
}

//	Gathering required Information from referrers Table
$sql = "SELECT * FROM " . REFERRERS_TABLE ." ORDER BY $order_by";
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not query referrers information', '', __LINE__, __FILE__, $sql);
	}

	$i = 0;

	while($row = $db->sql_fetchrow($result))
	{
		$row_color = (!($i % 2)) ? $theme['td_color1'] : $theme['td_color2'];
		$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];
		$url_name = (strlen($row['referrer_url']) > 50)?substr ($row['referrer_url'], 0, 50).'...' :$row['referrer_url'];// Cut Url name if it is longer than 50 chars

		$template->assign_block_vars('refersrow', array(
			'ID' => $i + ($start + 1),
			'REFER_ID' => $row['referrer_id'],
			'ROW_CLASS' => $row_class,
			'HOST' => $row['referrer_host'],
			'URL' => '<a href="' . htmlspecialchars($row['referrer_url']) . '" rel="nofollow" target="_blank">' . htmlspecialchars($url_name) . '</a>',
			'IP' => '<a href="http://whois.sc/' . decode_ip($row['referrer_ip']) . '" target="_blank">' . decode_ip($row['referrer_ip']) . '</a>',
			'HITS' => $row['referrer_hits'],
			'FIRST' => create_date2($board_config['default_dateformat'], $row['referrer_firstvisit'], $board_config['board_timezone']),
			'LAST' => create_date2($board_config['default_dateformat'], $row['referrer_lastvisit'], $board_config['board_timezone'])
			)
		);
		$i++;
}

$sql = "SELECT count(*) AS total
	FROM " . REFERRERS_TABLE;
if (!($result = $db->sql_query($sql)))
{
	message_die(GENERAL_ERROR, 'Error getting total referrers', '', __LINE__, __FILE__, $sql);
}
if ($total = $db->sql_fetchrow($result))
{
	$total_referrers = $total['total'];

	$pagination = generate_pagination('referrers.' . PHP_EXT . '?mode=' . $mode . '&amp;order=' . $sort_order, $total_referrers , $board_config['topics_per_page'], $start) . '&nbsp;';
}
$db->sql_freeresult($result);

$template->assign_vars(array(
	'PAGINATION' => $pagination,
	'PAGE_NUMBER' => sprintf($lang['Page_of'], (floor($start / $board_config['topics_per_page']) + 1), ceil($total_referrers  / $board_config['topics_per_page'])),
	'L_GOTO_PAGE' => $lang['Goto_page']
	)
);

$template->pparse('body');
include(IP_ROOT_PATH . 'includes/page_tail.' . PHP_EXT);

?>