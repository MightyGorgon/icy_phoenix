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

// MG Cash MOD For IP - BEGIN
define('IN_CASHMOD', true);
define('CM_MEMBERLIST', true);
// MG Cash MOD For IP - END
define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_users.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_groups.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/class_form.' . PHP_EXT);
$class_form = new class_form();

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup();
// End session management

$cms_page['page_id'] = 'memberlist';
$cms_page['page_nav'] = (!empty($cms_config_layouts[$cms_page['page_id']]['page_nav']) ? true : false);
$cms_page['global_blocks'] = (!empty($cms_config_layouts[$cms_page['page_id']]['global_blocks']) ? true : false);
$cms_auth_level = (isset($cms_config_layouts[$cms_page['page_id']]['view']) ? $cms_config_layouts[$cms_page['page_id']]['view'] : AUTH_ALL);
check_page_auth($cms_page['page_id'], $cms_auth_level);

$mode = request_var('mode', '');
$action = request_var('action', '');
$sort_order = request_var('order', '');
$sort_key = request_var('sk', '');
$sort_dir = request_var('sd', '');
$user_id = request_var(POST_USERS_URL, ANONYMOUS);
$username = request_var('un', '', true);
$group_id = request_var(POST_GROUPS_URL, 0);
$topic_id = request_var(POST_TOPIC_URL, 0);

$alphanum = request_var('alphanum', '');
if (!empty($alphanum))
{
	$alphanum = ($alphanum == '#') ? '#' : (phpbb_clean_username(ip_clean_username(urldecode($alphanum))));
	$alpha_where = ($alphanum == '#') ? "AND username NOT RLIKE '^[A-Z]'" : "AND username LIKE '" . $db->sql_escape($alphanum) . "%'";
}

$start = request_var('start', 0);
$start = ($start < 0) ? 0 : $start;

$page_number = request_var('page_number', 0);
$page_number = ($page_number < 1) ? 0 : $page_number;

$start = (empty($page_number) ? $start : (($page_number * $config['topics_per_page']) - $config['topics_per_page']));

$users_per_page = request_var('users_per_page', $config['topics_per_page']);
$users_per_page = ((!$users_per_page || ($users_per_page < 0) || ($users_per_page > 200)) ? $config['topics_per_page'] : $users_per_page);

// SORT - BEGIN
$sort_array = array(
	'a' => array('lang' => $lang['SORT_USERNAME'], 'mode' => 'username', 'sql' => 'u.username'),
	'b' => array('lang' => $lang['SORT_LOCATION'], 'mode' => 'location', 'sql' => 'u.user_from'),
	'c' => array('lang' => $lang['SORT_JOINED'], 'mode' => 'joined', 'sql' => 'u.user_regdate'),
	'd' => array('lang' => $lang['SORT_POSTS'], 'mode' => 'posts', 'sql' => 'u.user_posts'),
	'f' => array('lang' => $lang['SORT_WEBSITE'], 'mode' => 'website', 'sql' => 'u.user_website'),
	'g' => array('lang' => $lang['ICQ'], 'mode' => 'icq', 'sql' => 'u.user_icq'),
	'h' => array('lang' => $lang['AIM'], 'mode' => 'aim', 'sql' => 'u.user_aim'),
	'i' => array('lang' => $lang['MSNM'], 'mode' => 'msn', 'sql' => 'u.user_msnm'),
	'j' => array('lang' => $lang['YIM'], 'mode' => 'yahoo', 'sql' => 'u.user_yim'),
	'k' => array('lang' => $lang['JABBER'], 'mode' => 'jabber', 'sql' => 'u.user_jabber'),
	'm' => array('lang' => $lang['SORT_RANK'], 'mode' => 'rank', 'sql' => 'u.user_rank'),
	'n' => array('lang' => $lang['SORT_FAST'], 'mode' => 'fast', 'sql' => 'u.username'),
	'o' => array('lang' => $lang['SORT_STANDARD'], 'mode' => 'standard', 'sql' => 'u.username'),
	's' => array('lang' => $lang['SORT_STAFF'], 'mode' => 'staff', 'sql' => 'u.user_level'),
	't' => array('lang' => $lang['SORT_TOP_TEN'], 'mode' => 'topten', 'sql' => 'u.user_posts'),
	'u' => array('lang' => $lang['SORT_BIRTHDAY'], 'mode' => 'birthday', 'sql' => 'u.user_birthday'),
	'v' => array('lang' => $lang['SORT_STYLE'], 'mode' => 'style', 'sql' => 'u.user_style'),
	'w' => array('lang' => $lang['SORT_ONLINE'], 'mode' => 'online', 'sql' => 'u.user_session_time'),
);

if ($user->data['user_level'] == ADMIN)
{
	$sort_array['e'] = array('lang' => $lang['SORT_EMAIL'], 'mode' => 'email', 'sql' => 'u.user_email');
}

if (!empty($user->data['session_logged_in']))
{
	$sort_array['l'] = array('lang' => $lang['SORT_LASTLOGON'], 'mode' => 'lastlogon', 'sql' => 'u.user_session_time');
	//$sort_array['l'] = array('lang' => $lang['SORT_LASTLOGON'], 'mode' => 'lastlogon', 'sql' => 'u.user_lastvisit');
}

// MG Cash MOD For IP - BEGIN
if (!empty($config['plugins']['cash']['enabled']))
{
	$cm_memberlist->droplists($sort_array);
}
// MG Cash MOD For IP - END

$mode_types_key = array();
$mode_types = array();
$mode_types_text = array();
$mode_types_sql = array();
$sort_key_text = array();
$sort_key_sql = array();
foreach ($sort_array as $k => $v)
{
	$mode_types_key[$v['mode']] = $k;
	$mode_types[] = $v['mode'];
	$mode_types_text[] = $v['lang'];
	$mode_types_sql[] = $v['sql'];
	$sort_key_text[] = $v['lang'];
	$sort_key_sql[] = $v['sql'];
}

$mode = (empty($mode) && !empty($sort_array[$sort_key]['mode'])) ? $sort_array[$sort_key]['mode'] : $mode;
if ((empty($mode) || !in_array($mode, $mode_types)) && (strpos($mode, 'cash_') === false))
{
	$mode = 'joined';
}
$sort_key = $mode_types_key[$mode];
$sort_dir_types = array('a' => 'ASC', 'd' => 'DESC');
$sort_order_types = array('ASC' => 'a', 'DESC' => 'd');
$sort_order = (empty($sort_order) && !empty($sort_dir_types[$sort_dir])) ? $sort_dir_types[$sort_dir] : $sort_order;
$sort_order = check_var_value($sort_order, array('ASC', 'DESC'));
$sort_dir = $sort_order_types[$sort_order];

$select_sort_mode = $class_form->build_select_box('mode', $mode, $mode_types, $mode_types_text, '');
$select_sort_order = $class_form->build_select_box('order', $sort_order, array('ASC', 'DESC'), array($lang['ASCENDING'], $lang['DESCENDING']), '');
// SORT - END

// Additional sorting options for user search ... if search is enabled, if not then only admins can make use of this (for ACP functionality)
$sql_select = '';
$sql_where_data = '';
$sql_where = '';
$order_by = '';

$form = request_var('form', '');
$field = request_var('field', '');
$select_single = request_var('select_single', false);

// Search URL parameters, if any of these are in the URL we do a search
$search_params = array('username', 'email', 'icq', 'aim', 'yahoo', 'msn', 'jabber', 'joined_select', 'active_select', 'count_select', 'joined', 'active', 'count', 'ip');

// We validate form and field here, only id/class allowed
$form = (!preg_match('/^[a-z0-9_-]+$/i', $form)) ? '' : $form;
$field = (!preg_match('/^[a-z0-9_-]+$/i', $field)) ? '' : $field;
if ((($action == 'searchuser') || sizeof(array_intersect(array_keys($_GET), $search_params)) > 0) && ($user->data['user_level'] == ADMIN))
{
	$username = request_var('username', '', true);
	$email = strtolower(request_var('email', ''));
	$aim = request_var('aim', '');
	$icq = request_var('icq', '');
	$jabber = request_var('jabber', '');
	$msn = request_var('msn', '');
	$skype = request_var('skype', '');
	$yahoo = request_var('yahoo', '');
	$search_group_id = request_var('search_group_id', 0);

	// when using these, make sure that we actually have values defined in $find_key_match
	$joined_select = request_var('joined_select', 'lt');
	$active_select = request_var('active_select', 'lt');
	$count_select = request_var('count_select', 'eq');

	$joined = explode('-', request_var('joined', ''));
	$active = explode('-', request_var('active', ''));
	$count = (request_var('count', '') !== '') ? request_var('count', 0) : '';
	$ipdomain = request_var('ip', '');

	$find_key_match = array('lt' => '<', 'gt' => '>', 'eq' => '=');

	$find_count = array('lt' => $lang['LESS_THAN'], 'eq' => $lang['EQUAL_TO'], 'gt' => $lang['MORE_THAN']);
	$s_find_count = '';
	foreach ($find_count as $key => $value)
	{
		$selected = ($count_select == $key) ? ' selected="selected"' : '';
		$s_find_count .= '<option value="' . $key . '"' . $selected . '>' . $value . '</option>';
	}

	$find_time = array('lt' => $lang['BEFORE'], 'gt' => $lang['AFTER']);
	$s_find_join_time = '';
	foreach ($find_time as $key => $value)
	{
		$selected = ($joined_select == $key) ? ' selected="selected"' : '';
		$s_find_join_time .= '<option value="' . $key . '"' . $selected . '>' . $value . '</option>';
	}

	$s_find_active_time = '';
	foreach ($find_time as $key => $value)
	{
		$selected = ($active_select == $key) ? ' selected="selected"' : '';
		$s_find_active_time .= '<option value="' . $key . '"' . $selected . '>' . $value . '</option>';
	}

	$sql_where .= ($username) ? ' AND LOWER(u.username) ' . $db->sql_like_expression(str_replace('*', $db->any_char, utf8_clean_string($username))) : '';
	$sql_where .= (($user->data['user_level'] == ADMIN) && $email) ? ' AND u.user_email ' . $db->sql_like_expression(str_replace('*', $db->any_char, $email)) . ' ' : '';
	$sql_where .= ($aim) ? ' AND u.user_aim ' . $db->sql_like_expression(str_replace('*', $db->any_char, $aim)) . ' ' : '';
	$sql_where .= ($icq) ? ' AND u.user_icq ' . $db->sql_like_expression(str_replace('*', $db->any_char, $icq)) . ' ' : '';
	$sql_where .= ($jabber) ? ' AND u.user_jabber ' . $db->sql_like_expression(str_replace('*', $db->any_char, $jabber)) . ' ' : '';
	$sql_where .= ($msn) ? ' AND u.user_msnm ' . $db->sql_like_expression(str_replace('*', $db->any_char, $msn)) . ' ' : '';
	$sql_where .= ($skype) ? ' AND u.user_skype ' . $db->sql_like_expression(str_replace('*', $db->any_char, $skype)) . ' ' : '';
	$sql_where .= ($yahoo) ? ' AND u.user_yim ' . $db->sql_like_expression(str_replace('*', $db->any_char, $yahoo)) . ' ' : '';
	$sql_where .= (is_numeric($count) && isset($find_key_match[$count_select])) ? ' AND u.user_posts ' . $find_key_match[$count_select] . ' ' . (int) $count . ' ' : '';
	$sql_where .= (sizeof($joined) > 1 && isset($find_key_match[$joined_select])) ? " AND u.user_regdate " . $find_key_match[$joined_select] . ' ' . gmmktime(0, 0, 0, intval($joined[1]), intval($joined[2]), intval($joined[0])) : '';
	$sql_where .= (!empty($user->data['session_logged_in']) && sizeof($active) > 1 && isset($find_key_match[$active_select])) ? " AND u.user_lastvisit " . $find_key_match[$active_select] . ' ' . gmmktime(0, 0, 0, $active[1], intval($active[2]), intval($active[0])) : '';
	$sql_where .= ($search_group_id) ? " AND u.user_id = ug.user_id AND ug.group_id = $search_group_id AND ug.user_pending = 0 " : '';

	if ($ipdomain && ($user->data['user_level'] == ADMIN))
	{
		if (strspn($ipdomain, 'abcdefghijklmnopqrstuvwxyz'))
		{
			$hostnames = gethostbynamel($ipdomain);

			if ($hostnames !== false)
			{
				$ips = "'" . implode('\', \'', array_map(array($db, 'sql_escape'), preg_replace('#([0-9]{1,3}\.[0-9]{1,3}[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})#', "\\1", gethostbynamel($ipdomain)))) . "'";
			}
			else
			{
				$ips = false;
			}
		}
		else
		{
			$ips = "'" . str_replace('*', '%', $db->sql_escape($ipdomain)) . "'";
		}

		if ($ips === false)
		{
			// A minor fudge but it does the job :D
			$sql_where .= " AND u.user_id = 0";
		}
		else
		{
			$sql = "SELECT DISTINCT poster_id
				FROM " . POSTS_TABLE . "
				WHERE poster_ip " . ((strpos($ips, '%') !== false) ? 'LIKE' : 'IN') . " ($ips)";
			$result = $db->sql_query($sql);

			if ($row = $db->sql_fetchrow($result))
			{
				$user_ip_sql = array();
				do
				{
					$user_ip_sql[] = $row['poster_id'];
				}
				while ($row = $db->sql_fetchrow($result));

				$sql_where .= " AND " . $db->sql_in_set('u.user_id', $user_ip_sql);
			}
			else
			{
				// A minor fudge but it does the job :D
				$sql_where .= " AND u.user_id = 0";
			}
			$db->sql_freeresult($result);
		}
	}
}

$first_char = request_var('first_char', '');

if ($first_char == 'other')
{
	for ($i = 97; $i < 123; $i++)
	{
		$sql_where .= ' AND u.username NOT ' . $db->sql_like_expression(chr($i) . $db->any_char);
	}
}
elseif ($first_char)
{
	$sql_where .= ' AND u.username ' . $db->sql_like_expression(substr($first_char, 0, 1) . $db->any_char);
}

// Sorting and order
$order_by .= $sort_array[$sort_key]['sql'] . ' ' . (($sort_dir == 'a') ? 'ASC' : 'DESC');
// Unfortunately we must do this here for sorting by rank, else the sort order is applied wrongly
if ($sort_key == 'm')
{
	$order_by .= ', u.user_posts DESC';
}

if (($sort_key == 'a') || ($sort_key == 'n') || ($sort_key == 'o'))
{
	$order_by = ' LOWER(u.username) ' . (($sort_dir == 'a') ? 'ASC' : 'DESC');
}

// Mighty Gorgon - Multiple Ranks - BEGIN
$ranks_array = $cache->obtain_ranks(false);
// Mighty Gorgon - Multiple Ranks - END

$last_x_mins = time() - ONLINE_REFRESH;
$sql_style = false;

$cash_condition = false;
// MG Cash MOD For IP - BEGIN
if (!empty($config['plugins']['cash']['enabled']))
{
	switch($mode)
	{
		case $cm_memberlist->modecheck($mode):
			$order_by = $cm_memberlist->getfield($mode) . " $sort_order";
			$cash_condition = true;
			break;
	}
}
// MG Cash MOD For IP - END

if (empty($cash_condition))
{
	switch($mode)
	{
		case 'topten':
			$order_by = $sort_array[$sort_key]['sql'] . ' DESC';
			$users_per_page = 10;
			break;
		case 'staff':
			$sql_where = "AND u.user_level != 0";
			break;
		case 'online':
			if ($user->data['user_level'] == ADMIN)
			{
				$sql_where = "AND u.user_session_time >= $last_x_mins";
			}
			else
			{
				$sql_where = "AND u.user_session_time >= $last_x_mins AND u.user_allow_viewonline <> 0";
			}
			break;
		case 'lastlogon':
			if ($user->data['user_level'] != ADMIN)
			{
				$sql_where = "AND u.user_allow_viewonline <> 0";
			}
			break;
	}
}

$s_char_options = '<option value=""' . ((!$first_char) ? ' selected="selected"' : '') . '>&nbsp; &nbsp;</option>';
for ($i = 97; $i < 123; $i++)
{
	$s_char_options .= '<option value="' . chr($i) . '"' . (($first_char == chr($i)) ? ' selected="selected"' : '') . '>' . chr($i - 32) . '</option>';
}
$s_char_options .= '<option value="other"' . (($first_char == 'other') ? ' selected="selected"' : '') . '>' . $lang['OTHER'] . '</option>';

// Build a relevant pagination_url
$params = array();
$sort_params = array();

// We do not use request_var() here directly to save some calls (not all variables are set)
$check_params = array(
	'g' => array('g', 0),
	'sk' => array('sk', $sort_key),
	'sd' => array('sd', 'a'),
	'form' => array('form', ''),
	'field' => array('field', ''),
	'select_single' => array('select_single', $select_single),
	'username' => array('username', '', true),
	'email' => array('email', ''),
	'aim' => array('aim', ''),
	'icq' => array('icq', ''),
	'jabber' => array('jabber', ''),
	'msn' => array('msn', ''),
	'skype' => array('skype', ''),
	'yahoo' => array('yahoo', ''),
	'joined_select' => array('joined_select', 'lt'),
	'active_select' => array('active_select', 'lt'),
	'count_select' => array('count_select', 'eq'),
	'joined' => array('joined', ''),
	'active' => array('active', ''),
	'count' => (request_var('count', '') !== '') ? array('count', 0) : array('count', ''),
	'ip' => array('ip', ''),
	'first_char' => array('first_char', ''),
);

foreach ($check_params as $key => $call)
{
	if (!isset($_REQUEST[$key]))
	{
		continue;
	}

	$param = call_user_func_array('request_var', $call);
	$param = urlencode($key) . '=' . ((is_string($param)) ? urlencode($param) : $param);
	$params[] = $param;

	if (($key != 'sk') && ($key != 'sd'))
	{
		$sort_params[] = $param;
	}
}

if ($action == 'searchuser')
{
	$params[] = 'action=searchuser';
}
else
{
	if (!empty($alphanum))
	{
		$params[] = 'alphanum=' . $alphanum;
		$sort_params[] = 'alphanum=' . $alphanum;
	}

	$params[] = 'users_per_page=' . $users_per_page;
	$sort_params[] = 'users_per_page=' . $users_per_page;
	$params[] = 'mode=' . $mode;
	$sort_params[] = 'mode=' . $mode;
}

$pagination_url = append_sid(CMS_PAGE_MEMBERLIST, false, false, implode('&amp;', $params));
$sort_url = append_sid(CMS_PAGE_MEMBERLIST, false, false, implode('&amp;', $sort_params));

unset($search_params, $sort_params);

if (!empty($alphanum))
{
	$alphanum = ($alphanum == '#') ? '#' : (phpbb_clean_username(ip_clean_username(strtolower(urldecode($alphanum)))));
	$sql_where = ($alphanum == '#') ? "AND LOWER(username) NOT RLIKE '^[a-z]'" : "AND LOWER(username) LIKE '" . $db->sql_escape($alphanum) . "%'";
}

if (($action == 'searchuser') && ($user->data['user_level'] == ADMIN))
{
	$template->assign_vars(array(
		'USERNAME' => $username,
		'EMAIL' => $email,
		'AIM' => $aim,
		'ICQ' => $icq,
		'JABBER' => $jabber,
		'MSNM' => $msn,
		'SKYPE' => $skype,
		'YAHOO' => $yahoo,
		'JOINED' => implode('-', $joined),
		'ACTIVE' => implode('-', $active),
		'COUNT' => $count,
		'IP' => $ipdomain,

		'S_IP_SEARCH_ALLOWED' => ($user->data['user_level'] == ADMIN) ? true : false,
		'S_EMAIL_SEARCH_ALLOWED' => ($user->data['user_level'] == ADMIN) ? true : false,
		'S_IN_SEARCH_POPUP' => ($form && $field) ? true : false,
		'S_SEARCH_USER' => true,
		'S_FORM_NAME' => $form,
		'S_FIELD_NAME' => $field,
		'S_SELECT_SINGLE' => $select_single,
		'S_COUNT_OPTIONS' => $s_find_count,
		'S_SORT_OPTIONS' => $s_sort_key,
		'S_JOINED_TIME_OPTIONS' => $s_find_join_time,
		'S_ACTIVE_TIME_OPTIONS' => $s_find_active_time,
		'S_USER_SEARCH_ACTION' => append_sid(CMS_PAGE_MEMBERLIST, false, false, 'action=searchuser&amp;form=' . $form . '&amp;field=' . $field, implode('&amp;', $params))
		)
	);
}

// Get the users
$sql_active_users = empty($config['inactive_users_memberlists']) ? 'AND u.user_active = 1' : '';
$sql_from_where_part = " FROM " . USERS_TABLE . " u
	WHERE u.user_id <> " . ANONYMOUS . "
	$sql_active_users
	$sql_where";

// Count the users ...
$sql_count = "SELECT COUNT(u.user_id) AS total_users " . $sql_from_where_part;
$result = $db->sql_query($sql_count);
$total_users = (int) $db->sql_fetchfield('total_users');
$db->sql_freeresult($result);

// Get us some users :D
$sql = "SELECT u.user_id " . $sql_from_where_part . " ORDER BY $order_by";
$result = $db->sql_query_limit($sql, $users_per_page, $start);

$user_list = array();
while ($row = $db->sql_fetchrow($result))
{
	$user_list[] = (int) $row['user_id'];
}
$db->sql_freeresult($result);

// So, did we get any users?
if (sizeof($user_list))
{
	//phpBB 3 way to get sessions... currently disabled!
	/*
	// Session time?! Session time...
	$sql = "SELECT session_user_id, MAX(session_time) AS session_time
		FROM " . SESSIONS_TABLE . "
		WHERE session_time >= " . (time() - ONLINE_REFRESH) . "
			AND " . $db->sql_in_set('session_user_id', $user_list) . "
		GROUP BY session_user_id";
	$result = $db->sql_query($sql);

	$session_times = array();
	while ($row = $db->sql_fetchrow($result))
	{
		$session_times[$row['session_user_id']] = $row['session_time'];
	}
	$db->sql_freeresult($result);
	*/

	// Do the SQL thang
	$sql_style_select = '';
	$sql_style_from = '';
	$sql_style_where = '';
	if (!empty($sql_style))
	{
		$sql_style_select = ', t.themes_id, t.style_name';
		$sql_style_from = ', ' . THEMES_TABLE . ' t';
		$sql_style_where = 'AND t.themes_id = u.user_style';
	}

	//$sql = "SELECT u.*" . $sql_style_select . "
	$sql = "SELECT u.username, u.user_id, u.user_active, u.user_color, u.user_level, u.user_viewemail, u.user_posts, u.user_regdate, u.user_from, u.user_website, u.user_email, u.user_icq, u.user_aim, u.user_yim, u.user_msnm, u.user_skype, u.user_avatar, u.user_avatar_type, u.user_allowavatar, u.user_from, u.user_from_flag, u.user_rank, u.user_rank2, u.user_rank3, u.user_rank4, u.user_rank5, u.user_birthday, u.user_gender, u.user_allow_viewonline, u.user_lastvisit, u.user_session_time, u.user_style, u.user_lang" . $sql_style_select . "
		FROM " . USERS_TABLE . " u" . $sql_style_from . "
		WHERE " . $db->sql_in_set('u.user_id', $user_list) . " " . $sql_style_where;
	// MG Cash MOD For IP - BEGIN
	// This portion of code must be here... because this edits directly $sql var
	if (!empty($config['plugins']['cash']['enabled']))
	{
		$cm_memberlist->generate_columns($template, $sql, 8);
	}
	// MG Cash MOD For IP - END
	$result = $db->sql_query($sql);

	$id_cache = array();
	while ($row = $db->sql_fetchrow($result))
	{
		$row['last_visit'] = (!empty($row['user_session_time'])) ? $row['user_session_time'] : $row['user_lastvisit'];
		//phpBB 3 way to get sessions... currently disabled!
		//$row['session_time'] = (!empty($session_times[$row['user_id']])) ? $session_times[$row['user_id']] : 0;
		//$row['last_visit'] = (!empty($row['session_time'])) ? $row['session_time'] : $row['user_lastvisit'];

		$id_cache[$row['user_id']] = $row;
	}
	$db->sql_freeresult($result);

	// Custom Profile Fields MOD - BEGIN
	include_once(IP_ROOT_PATH . 'includes/functions_profile.' . PHP_EXT);
	$profile_data = get_fields('WHERE view_in_memberlist = ' . VIEW_IN_MEMBERLIST . ' AND users_can_view = ' . ALLOW_VIEW);

	foreach($profile_data as $field)
	{
		$template->assign_block_vars('custom_field_names', array('FIELD_NAME' => $field['field_name']));
	}

	$template->assign_var('NUMCOLS', sizeof($profile_data) + 12);
	// Custom Profile Fields MOD - END

	// If we sort by last active date we need to adjust the id cache due to user_lastvisit not being the last active date...
	if ($sort_key == 'l')
	{
		usort($user_list, '_sort_last_active');
	}

	for ($i = 0, $end = sizeof($user_list); $i < $end; ++$i)
	{
		$user_id = $user_list[$i];
		$row =& $id_cache[$user_id];

		if ($mode == 'fast')
		{
			$username = $row['username'];
			$user_id = $row['user_id'];
			$temp_url = append_sid(CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $user_id);
			$profile = '<a href="' . $temp_url . '">' . $lang['Read_profile'] . '</a>';
			$from = (!empty($row['user_from'])) ? $row['user_from'] : '&nbsp;';
			$joined = create_date($lang['JOINED_DATE_FORMAT'], $row['user_regdate'], $config['board_timezone']);
			$posts = ($row['user_posts']) ? $row['user_posts'] : 0;

			$user_ranks['rank_01_html'] = '';
			$user_ranks['rank_01_img_html'] = '';
			$user_ranks['rank_02_html'] = '';
			$user_ranks['rank_02_img_html'] = '';
			$user_ranks['rank_03_html'] = '';
			$user_ranks['rank_03_img_html'] = '';
			$user_ranks['rank_04_html'] = '';
			$user_ranks['rank_04_img_html'] = '';
			$user_ranks['rank_05_html'] = '';
			$user_ranks['rank_05_img_html'] = '';

			$gender_image = '';
			$level = '';
			$poster_avatar = '';
			$flag = '';
			$style = '';
			$lastlogon = '';
			$user_birthday = '';
			$profile_url = '';
			$profile_img = '';
			$profile = '';
			$pm_url = '';
			$pm_img = '';
			$pm = '';
			$email_url = '';
			$email_img = '';
			$email = '';
			$www_url = '';
			$www_img = '';
			$www = '';
			$aim_url = '';
			$aim_img = '';
			$aim = '';
			$icq_url = '';
			$icq_status_img = '';
			$icq_img = '';
			$icq = '';
			$msn_url = '';
			$msn_img = '';
			$msn = '';
			$skype_url = '';
			$skype_img = '';
			$skype = '';
			$yahoo_url = '';
			$yahoo_img = '';
			$yahoo = '';
			$album_url = '';
			$album_img = '';
			$album = '';
			$online_status_url = '';
			$online_status_img = '';
			$online_img = '';
		}
		else
		{
			$username = $row['username'];
			$user_id = $row['user_id'];

			// Mighty Gorgon - Multiple Ranks - BEGIN
			$user_ranks = generate_ranks($row, $ranks_array);
			// Mighty Gorgon - Multiple Ranks - END

			$username = colorize_username($row['user_id'], $row['username'], $row['user_color'], $row['user_active']);

			$user_info = array();
			$user_info = generate_user_info($row);
			foreach ($user_info as $k => $v)
			{
				$$k = $v;
			}

			$poster_avatar = '';
			if ($mode == 'staff')
			{
				$poster_avatar = $user_info['avatar'];
			}

			$style = '';
			if ($sql_style == true)
			{
				$style = '<br />' . $lang['Style'] . ':&nbsp;' . $row['style_name'];
			}
		}

		// Gender - BEGIN
		$gender_image = '';
		if (!empty($row['user_gender']))
		{
			switch ($row['user_gender'])
			{
				case 1:
					$gender_image = '<img src="' . $images['icon_minigender_male'] . '" alt="' . $lang['Gender'].  ': ' . $lang['Male'] . '" title="' . $lang['Gender'] . ': ' . $lang['Male'] . '" />';
					break;
				case 2:
					$gender_image = '<img src="' . $images['icon_minigender_female'] . '" alt="' . $lang['Gender']. ': ' . $lang['Female'] . '" title="' . $lang['Gender'] . ': ' . $lang['Female'] . '" />';
					break;
				default:
					$gender_image = '';
					break;
			}
		}
		// Gender - END

		if ($row['user_birthday'] != 999999)
		{
			$age = realdate('Y', (time() / 86400)) - realdate('Y', $row['user_birthday']);
			if (gmdate('md') < realdate('md', $row['user_birthday']))
			{
				$age--;
			}
			$age = '(' . $age . ')';
		}
		else
		{
			$age = ' ';
		}

		$deluser_url = (($user->data['user_level'] == ADMIN) ? append_sid('delete_users.' . PHP_EXT . '?mode=user_id&amp;del_user=' . $user_id) : '');
		$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];
		$template->assign_block_vars('memberrow', array(
			'ROW_NUMBER' => $i + ($start + 1),
			//'ROW_NUMBER' => $i + ($_GET['start'] + 1) . (($user->data['user_level'] == ADMIN) ? '&nbsp;<a href="' . append_sid('delete_users.' . PHP_EXT . '?mode=user_id&amp;del_user=' . $user_id) . '"><img src="' . $images['icon_delpost'] . '" alt="' . $lang['Delete'] . '" title="' . $lang['Delete'] . '" /></a>&nbsp;':''),
			'ROW_CLASS' => $row_class,
			'USERNAME' => colorize_username($row['user_id'], $row['username'], $row['user_color'], $row['user_active']),
			'FROM' => $from,
			'JOINED' => $joined,
			'DELETE' => (($user->data['user_level'] == ADMIN) ? '&nbsp;<a href="' . $deluser_url . '"><img src="' . $images['icon_delpost'] . '" alt="' . $lang['Delete'] . '" title="' . $lang['Delete'] . '" /></a>&nbsp;':''),

			// Start add - Last visit MOD
			'LAST_LOGON' => ($user->data['user_level'] == ADMIN || (!$config['hidde_last_logon'] && $row['user_allow_viewonline'])) ? (!empty($row['last_visit']) ? create_date($config['default_dateformat'], $row['last_visit'], $config['board_timezone']) : $lang['Never_last_logon']) : $lang['Hidde_last_logon'],
			// End add - Last visit MOD

			'POSTS' => $posts,
			'AVATAR_IMG' => $poster_avatar,
			'PROFILE_IMG' => $profile_img,
			'PROFILE' => $profile,
			'SEARCH_IMG' => $search_img,
			'SEARCH' => $search,
			'PM_IMG' => $pm_img,
			'PM' => $pm,
			'EMAIL_IMG' => (!$user->data['session_logged_in']) ? '' : $email_img,
			'EMAIL' => $email,
			'WWW_IMG' => $www_img,
			'WWW' => $www,
			'AIM_IMG' => $aim_img,
			'AIM' => $aim,
			'ICQ_STATUS_IMG' => $icq_status_img,
			'ICQ_IMG' => $icq_img,
			'ICQ' => $icq,
			'MSN_IMG' => $msn_img,
			'MSN' => $msn,
			'SKYPE_IMG' => $skype_img,
			'SKYPE' => $skype,
			'YIM_IMG' => $yahoo_img,
			'YIM' => $yahoo,
			'POSTER_GENDER' => $gender_image,
			'STYLE' => $style,
			'BIRTHDAY' => $row['user_birthday'],
			'AGE' => $age,
			'ONLINE_STATUS_IMG' => $online_status_img,

			'U_PROFILE' => $profile_url,
			'U_PM' => $pm_url,
			'U_EMAIL' => $email_url,
			'U_WWW' => $www_url,
			'U_AIM' => $aim_url,
			'U_ICQ' => $icq_url,
			'U_MSN' => $msn_url,
			'U_SKYPE' => $skype_url,
			'U_YIM' => $yahoo_url,
			'L_POSTER_ONLINE_STATUS' => $online_status_lang,
			'POSTER_ONLINE_STATUS_CLASS' => $online_status_class,
			'U_POSTER_ONLINE_STATUS' => $online_status_url,
			'U_DELETE' => $deluser_url,

			// Mighty Gorgon - Multiple Ranks - BEGIN
			'USER_LEVEL' => $level,
			'USER_RANK_01' => $user_ranks['rank_01_html'],
			'USER_RANK_01_IMG' => $user_ranks['rank_01_img_html'],
			'USER_RANK_02' => $user_ranks['rank_02_html'],
			'USER_RANK_02_IMG' => $user_ranks['rank_02_img_html'],
			'USER_RANK_03' => $user_ranks['rank_03_html'],
			'USER_RANK_03_IMG' => $user_ranks['rank_03_img_html'],
			'USER_RANK_04' => $user_ranks['rank_04_html'],
			'USER_RANK_04_IMG' => $user_ranks['rank_04_img_html'],
			'USER_RANK_05' => $user_ranks['rank_05_html'],
			'USER_RANK_05_IMG' => $user_ranks['rank_05_img_html'],
			// Mighty Gorgon - Multiple Ranks - END

			'U_VIEWPROFILE' => append_sid(CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $user_id)
			)
		);
		// MG Cash MOD For IP - BEGIN
		if (!empty($config['plugins']['cash']['enabled']))
		{
			$cm_memberlist->listing($template, $row);
		}
		// MG Cash MOD For IP - END

		// Custom Profile Fields MOD - BEGIN
		foreach($profile_data as $field)
		{
			$name = text_to_column($field['field_name']);
			$sql2 = "SELECT $name FROM " . USERS_TABLE . "
				WHERE user_id = $user_id";
			$result2 = $db->sql_query($sql2);
			$val = $db->sql_fetchrow($result2);
			$val = displayable_field_data($val[$name], $field['field_type']);

			$template->assign_block_vars('memberrow.custom_fields',array('CUSTOM_FIELD' => $val));
		}
		// Custom Profile Fields MOD - END

		unset($id_cache[$user_id]);
	}

	if (sizeof($user_list))
	{
		$template->assign_var('NUMCOLS', 20);
	}
}
else
{
	$template->assign_var('S_NO_USERS', true);
}

// Generate page
make_jumpbox(CMS_PAGE_VIEWFORUM);

build_groups_list_template();

$template->assign_vars(array(
	// phpBB3 - BEGIN
	/*
	'PAGINATION' => generate_pagination($pagination_url, $total_users, $config['topics_per_page'], $start),
	'PAGE_NUMBER' => on_page($total_users, $config['topics_per_page'], $start),
	'TOTAL_USERS' => ($total_users == 1) ? $lang['LIST_USER'] : sprintf($lang['LIST_USERS'], $total_users),
	*/
	'U_FIND_MEMBER' => append_sid(CMS_PAGE_MEMBERLIST . (($action == 'searchuser') ? '' : '?action=searchuser')),
	'L_FIND_MEMBER' => (($action == 'searchuser') ? $lang['FIND_USERNAME_HIDE'] : $lang['FIND_USERNAME']),
	'U_SORT_USERNAME' => $sort_url . '&amp;sk=a&amp;sd=' . (($sort_key == 'a' && $sort_dir == 'a') ? 'd' : 'a'),
	'U_SORT_FROM' => $sort_url . '&amp;sk=b&amp;sd=' . (($sort_key == 'b' && $sort_dir == 'a') ? 'd' : 'a'),
	'U_SORT_JOINED' => $sort_url . '&amp;sk=c&amp;sd=' . (($sort_key == 'c' && $sort_dir == 'a') ? 'd' : 'a'),
	'U_SORT_POSTS' => $sort_url . '&amp;sk=d&amp;sd=' . (($sort_key == 'd' && $sort_dir == 'a') ? 'd' : 'a'),
	'U_SORT_EMAIL' => $sort_url . '&amp;sk=e&amp;sd=' . (($sort_key == 'e' && $sort_dir == 'a') ? 'd' : 'a'),
	'U_SORT_WEBSITE' => $sort_url . '&amp;sk=f&amp;sd=' . (($sort_key == 'f' && $sort_dir == 'a') ? 'd' : 'a'),
	'U_SORT_LOCATION' => $sort_url . '&amp;sk=b&amp;sd=' . (($sort_key == 'b' && $sort_dir == 'a') ? 'd' : 'a'),
	'U_SORT_ICQ' => $sort_url . '&amp;sk=g&amp;sd=' . (($sort_key == 'g' && $sort_dir == 'a') ? 'd' : 'a'),
	'U_SORT_AIM' => $sort_url . '&amp;sk=h&amp;sd=' . (($sort_key == 'h' && $sort_dir == 'a') ? 'd' : 'a'),
	'U_SORT_MSN' => $sort_url . '&amp;sk=i&amp;sd=' . (($sort_key == 'i' && $sort_dir == 'a') ? 'd' : 'a'),
	'U_SORT_YIM' => $sort_url . '&amp;sk=j&amp;sd=' . (($sort_key == 'j' && $sort_dir == 'a') ? 'd' : 'a'),
	'U_SORT_ACTIVE' => ($user->data['user_level'] == ADMIN) ? $sort_url . '&amp;sk=l&amp;sd=' . (($sort_key == 'l' && $sort_dir == 'a') ? 'd' : 'a') : '',
	'U_SORT_RANK' => $sort_url . '&amp;sk=m&amp;sd=' . (($sort_key == 'm' && $sort_dir == 'a') ? 'd' : 'a'),
	'U_LIST_CHAR' => $sort_url . '&amp;sk=a&amp;sd=' . (($sort_key == 'l' && $sort_dir == 'a') ? 'd' : 'a'),

	'S_SORT_OPTIONS' => $s_sort_key,
	'S_JOINED_TIME_OPTIONS' => $s_find_join_time,
	'S_ACTIVE_TIME_OPTIONS' => $s_find_active_time,
	'S_CHAR_OPTIONS' => $s_char_options,

	'S_MODE_SELECT_BB3' => $s_sort_key,
	'S_ORDER_SELECT_BB3' => $s_sort_dir,
	'S_MODE_ACTION_BB3' => $pagination_url,
	// phpBB3 - END

	'L_SELECT_SORT_METHOD' => $lang['Select_sort_method'],
	'L_EMAIL' => $lang['Email'],
	'L_WEBSITE' => $lang['Website'],
	'L_FROM' => $lang['Location'],
	'L_ORDER' => $lang['Order'],
	'L_SORT' => $lang['Sort'],
	'L_SUBMIT' => $lang['Sort'],
	'L_JOINED' => $lang['Joined'],
	'L_USER_DELETE' => $lang['Delete'],
	'L_USER_WWW' => $lang['Website'],
	'L_USER_EMAIL' => $lang['Send_Email'],
	'L_USER_PROFILE' => $lang['Profile'],
	'L_LOGON' => $lang['Last_logon'],
	'L_POSTS' => $lang['Posts'],
	'L_ONLINE_STATUS' => $lang['Online_status'],
	'L_LEGEND' => $lang['legend'],

	// Mighty Gorgon - Power Memberlist - BEGIN
	'L_PM' => $lang['Private_Message'],
	'L_CONTACTS' => $lang['User_Contacts'],
	'L_USER_RANK' => $lang['Poster_rank'],
	'L_GENDER' => $lang['Gender'],
	'L_STYLE' => $lang['Style'],
	'L_CONTACT' => $lang['Contact'],
	'L_AVATAR' => $lang['Avatar'],
	'L_USERS_PER_PAGE' => $lang['Memberlist_Users_Display'],
	'S_USERS_PER_PAGE' => $users_per_page,
	// Mighty Gorgon - Power Memberlist - END

	'L_GO_TO_PAGE_NUMBER' => $lang['Go_To_Page_Number'],

	'S_MODE_SELECT' => $select_sort_mode,
	'S_ORDER_SELECT' => $select_sort_order,
	//'S_MODE_ACTION' => append_sid(CMS_PAGE_MEMBERLIST)
	'S_MODE_ACTION' => append_sid($pagination_url)

	)
);

// Mighty Gorgon - Power Memberlist - BEGIN
$alpha_range = range('A', 'Z');
$alphanum_range = array_merge(array('' => 'All'), array('%23' => '#'), $alpha_range);
foreach ($alphanum_range as $key => $alpha)
{
	if (in_array($alpha, $alpha_range)) $key = $alpha;
	//$alphanum_search_url = append_sid(CMS_PAGE_MEMBERLIST . '?mode=' . $mode . '&amp;sort=' . $sort_order . '&amp;alphanum=' . strtolower($key));
	$alphanum_search_url = append_sid(CMS_PAGE_MEMBERLIST . '?mode=username&amp;sort=' . $sort_order . '&amp;alphanum=' . strtolower($key));
	$template->assign_block_vars('alphanumsearch', array(
		'SEARCH_SIZE' => floor(100 / sizeof($alphanum_range)) . '%',
		'SEARCH_TERM' => $alpha,
		'SEARCH_LINK' => $alphanum_search_url)
	);
}
// Mighty Gorgon - Power Memberlist - END

$pagination = '&nbsp;';
if (($mode != 'topten') || ($users_per_page < 10))
{
	$pagination = generate_pagination(CMS_PAGE_MEMBERLIST . '?mode=' . $mode . '&amp;order=' . $sort_order . '&amp;users_per_page=' . $users_per_page . (!empty($alphanum) ? '&amp;alphanum=' . htmlspecialchars($alphanum) : ''), $total_users, $users_per_page, $start);
}

$template->assign_vars(array(
	'PAGINATION' => $pagination,
	'PAGE_NUMBER' => sprintf($lang['Page_of'], (floor($start / $users_per_page) + 1), ceil($total_users / $users_per_page)),
	'L_GOTO_PAGE' => $lang['Goto_page']
	)
);

full_page_generation('memberlist_body.tpl', $lang['Memberlist'], '', '');


function _sort_last_active($first, $second)
{
	global $id_cache, $sort_dir;

	$lesser_than = ($sort_dir === 'd') ? -1 : 1;
	return $lesser_than * (int) ($id_cache[$first]['last_visit'] - $id_cache[$second]['last_visit']);
}
?>
