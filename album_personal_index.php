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
* Smartor (smartor_xp@hotmail.com)
*
*/

define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);

// Start session management
$user->session_begin();
//$auth->acl($user->data);
$user->setup();
// End session management

// Get general album information
include(ALBUM_MOD_PATH . 'album_common.' . PHP_EXT);

$start = request_var('start', 0);
$start = ($start < 0) ? 0 : $start;

$mode = request_var('mode', 'joined');

$sort_order = request_var('order', 'ASC');
$sort_order = ($sort_order == 'ASC') ? 'ASC' : 'DESC';

//
// Memberlist sorting
//
$mode_types_text = array($lang['SORT_JOINED'], $lang['SORT_USERNAME'], $lang['Pics'], $lang['Last_Pic']);
$mode_types = array('joindate', 'username', 'pics', 'last_pic');

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


/*
+----------------------------------------------------------
| Start output the page
+----------------------------------------------------------
*/
$nav_server_url = create_server_url();
$album_nav_cat_desc = ALBUM_NAV_ARROW . '<a href="' . $nav_server_url . append_sid('album_personal_index.' . PHP_EXT) . '" class="nav-current">' . $lang['Users_Personal_Galleries'] . '</a>';
$breadcrumbs_address = ALBUM_NAV_ARROW . '<a href="' . $nav_server_url . append_sid('album.' . PHP_EXT) . '">' . $lang['Album'] . '</a>' . $album_nav_cat_desc;

$template->assign_vars(array(
	'L_SELECT_SORT_METHOD' => $lang['Select_sort_method'],
	'L_ORDER' => $lang['Order'],
	'L_SORT' => $lang['Sort'],
	'L_JOINED' => $lang['Joined'],
	'L_PICS' => $lang['Pics'],
	'L_USERS_PERSONAL_GALLERIES' => $lang['Users_Personal_Galleries'],
	'S_MODE_SELECT' => $select_sort_mode,
	'S_ORDER_SELECT' => $select_sort_order,
	'S_MODE_ACTION' => append_sid(album_append_uid('album_personal_index.' . PHP_EXT))
	)
);

switch($mode)
{
	case 'joined':
		$order_by = "user_regdate ASC LIMIT $start, " . $config['topics_per_page'];
		break;
	case 'username':
		$order_by = "username $sort_order LIMIT $start, " . $config['topics_per_page'];
		break;
	case 'pics':
		$order_by = "pics $sort_order LIMIT $start, " . $config['topics_per_page'];
		break;
	case 'last_pic':
		$order_by = "last_pic $sort_order LIMIT $start, " . $config['topics_per_page'];
		break;
	default:
		$order_by = "user_regdate $sort_order LIMIT $start, " . $config['topics_per_page'];
		break;
}

$sql = "SELECT u.username, u.user_id, u.user_active, u.user_color, u.user_regdate, COUNT(p.pic_id) AS pics, MAX(p.pic_id) AS last_pic, COUNT(c.cat_user_id) AS cats
		FROM " . USERS_TABLE . " AS u, " . ALBUM_TABLE . " AS p, " . ALBUM_CAT_TABLE . " AS c
		WHERE u.user_id <> " . ANONYMOUS . "
			AND c.cat_user_id = u.user_id
			AND c.cat_id = p.pic_cat_id
		GROUP BY user_id
		ORDER BY $order_by";
$result = $db->sql_query($sql);
$memberrow = array();
while($row = $db->sql_fetchrow($result))
{
	$memberrow[] = $row;
}

for ($i = 0; $i < sizeof($memberrow); $i++)
{
	$username = colorize_username($memberrow[$i]['user_id'], $memberrow[$i]['username'], $memberrow[$i]['user_color'], $memberrow[$i]['user_active'], true, false, false, false);
	$template->assign_block_vars('memberrow', array(
		'ROW_CLASS' => (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'],
		'USERNAME' => $username,
		'U_VIEWGALLERY' => append_sid(album_append_uid('album.' . PHP_EXT . '?user_id=' . $memberrow[$i]['user_id'])),
		//'U_VIEWGALLERY' => append_sid(album_append_uid('album_cat.' . PHP_EXT . '?cat_id=' . album_get_personal_root_id($memberrow[$i]['user_id']) . 'user_id=' . $memberrow[$i]['user_id'])),
		'JOINED' => create_date($lang['DATE_FORMAT'], $memberrow[$i]['user_regdate'], $config['board_timezone']),
		'PICS' => $memberrow[$i]['pics']
		)
	);
}

$sql = "SELECT COUNT(DISTINCT u.user_id) AS total
		FROM " . USERS_TABLE . " AS u, " . ALBUM_TABLE . " AS p, " . ALBUM_CAT_TABLE . " AS c
		WHERE u.user_id <> " . ANONYMOUS . "
			AND c.cat_user_id = u.user_id
			AND c.cat_id = p.pic_cat_id";
$result = $db->sql_query($sql);
if ($total = $db->sql_fetchrow($result))
{
	$total_galleries = $total['total'];
	$pagination = generate_pagination('album_personal_index.' . PHP_EXT . '?mode=' . $mode . '&amp;order=' . $sort_order, $total_galleries, $config['topics_per_page'], $start) . '&nbsp;';
}

$template->assign_vars(array(
	'PAGINATION' => $pagination,
	'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / $config['topics_per_page'] ) + 1 ), ceil( $total_galleries / $config['topics_per_page'] ))
	)
);

full_page_generation('album_personal_index_body.tpl', $lang['Album'], '', '');

?>