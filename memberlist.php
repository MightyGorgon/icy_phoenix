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

define('IN_ICYPHOENIX', true);
// MG Cash MOD For IP - BEGIN
define('IN_CASHMOD', true);
define('CM_MEMBERLIST', true);
// MG Cash MOD For IP - END
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_users.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_groups.' . PHP_EXT);

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

$cms_page['page_id'] = 'memberlist';
$cms_page['page_nav'] = (!empty($cms_config_layouts[$cms_page['page_id']]['page_nav']) ? true : false);
$cms_page['global_blocks'] = (!empty($cms_config_layouts[$cms_page['page_id']]['global_blocks']) ? true : false);
$cms_auth_level = (isset($cms_config_layouts[$cms_page['page_id']]['view']) ? $cms_config_layouts[$cms_page['page_id']]['view'] : AUTH_ALL);
check_page_auth($cms_page['page_id'], $cms_auth_level);

$start = request_var('start', 0);
$start = ($start < 0) ? 0 : $start;

$page_number = request_var('page_number', 0);
$page_number = ($page_number < 1) ? 0 : $page_number;

$start = (empty($page_number) ? $start : (($page_number * $config['topics_per_page']) - $config['topics_per_page']));

$sort_order = request_var('order', 'ASC');
$sort_order = check_var_value($sort_order, array('ASC', 'DESC'));

// Memberlist sorting
$mode = request_var('mode', 'joined');

$mode_types_text = array($lang['Fast'], $lang['Standard'], $lang['Staff'], $lang['Sort_Joined'], $lang['Sort_Username'], $lang['Sort_Location'], $lang['Sort_Posts'], $lang['Sort_Email'], $lang['Sort_Website'], $lang['Sort_Top_Ten'], $lang['Sort_Birthday'], $lang['Style'], $lang['Who_is_Online'], $lang['Sort_LastLogon']);
$mode_types = array('fast', 'standard', 'staff', 'joined', 'username', 'location', 'posts', 'email', 'website', 'topten', 'birthday', 'style', 'online', 'lastlogon');

// Do not change strpos() == 0! Remember that == 0 is different from === false!!!
if (!in_array($mode, $mode_types) && !(strpos($mode, 'cash_') == 0))
{
	$mode = 'joined';
}

// Mighty Gorgon - Power Memberlist - BEGIN
$alphanum = request_var('alphanum', '');
if (!empty($alphanum))
{
	$alphanum = ($alphanum == '#') ? '#' : (phpbb_clean_username(ip_clean_username(urldecode($alphanum))));
	$alpha_where = ($alphanum == '#') ? "AND username NOT RLIKE '^[A-Z]'" : "AND username LIKE '" . $db->sql_escape($alphanum) . "%'";
}

$users_per_page = request_var('users_per_page', $config['topics_per_page']);
$users_per_page = ((!$users_per_page || ($users_per_page < 0) || ($users_per_page > 200)) ? $config['topics_per_page'] : $users_per_page);
// Mighty Gorgon - Power Memberlist - END

// MG Cash MOD For IP - BEGIN
if (!empty($config['plugins']['cash']['enabled']))
{
	$cm_memberlist->droplists($mode_types_text, $mode_types);
}
// MG Cash MOD For IP - END

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

make_jumpbox(CMS_PAGE_VIEWFORUM);

build_groups_list_template();

$template->assign_vars(array(
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
	// Start add - Last visit MOD
	'L_LOGON' => $lang['Last_logon'],
	// End add - Last visit MOD
	'L_POSTS' => $lang['Posts'],
	// Start add - Online/Offline/Hidden Mod
	'L_ONLINE_STATUS' => $lang['Online_status'],
	// End add - Online/Offline/Hidden Mod
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
	'S_MODE_ACTION' => append_sid(CMS_PAGE_MEMBERLIST)
	)
);

// Mighty Gorgon - Power Memberlist - BEGIN
$alpha_range = range('A', 'Z');
$alphanum_range = array_merge(array('' => 'All'), array('%23' => '#'), $alpha_range);
foreach ($alphanum_range as $key => $alpha)
{
	if (in_array($alpha,$alpha_range)) $key = $alpha;
	$alphanum_search_url = append_sid(CMS_PAGE_MEMBERLIST . '?mode=' . ((isset($_GET['mode']) || isset($_POST['mode'])) ? $mode : 'username') . '&amp;sort=' . $sort_order . '&amp;alphanum=' . strtolower($key));
	$template->assign_block_vars('alphanumsearch', array(
		'SEARCH_SIZE' => floor(100 / sizeof($alphanum_range)) . '%',
		'SEARCH_TERM' => $alpha,
		'SEARCH_LINK' => $alphanum_search_url)
	);
}
// Mighty Gorgon - Power Memberlist - END

// Mighty Gorgon - Multiple Ranks - BEGIN
$ranks_array = $cache->obtain_ranks(false);
// Mighty Gorgon - Multiple Ranks - END

$last_x_mins = time() - 300;
$sql_style = false;

$cash_condition = false;
// MG Cash MOD For IP - BEGIN
if (!empty($config['plugins']['cash']['enabled']))
{
	switch($mode)
	{
		case $cm_memberlist->modecheck($mode):
			$order_by = $cm_memberlist->getfield($mode) . " $sort_order LIMIT $start, " . $users_per_page;
			$cash_condition = true;
			break;
	}
}
// MG Cash MOD For IP - END
if ($cash_condition == false)
{
	switch($mode)
	{
		case 'joined':
			$order_by = "u.user_regdate $sort_order LIMIT $start, " . $users_per_page;
			break;
		case 'username':
			$order_by = "u.username $sort_order LIMIT $start, " . $users_per_page;
			break;
		case 'location':
			$order_by = "u.user_from $sort_order LIMIT $start, " . $users_per_page;
			break;
		case 'posts':
			$order_by = "u.user_posts $sort_order LIMIT $start, " . $users_per_page;
			break;
		case 'email':
			$order_by = "u.user_email $sort_order LIMIT $start, " . $users_per_page;
			break;
		case 'website':
			$order_by = "u.user_website $sort_order LIMIT $start, " . $users_per_page;
			break;
		case 'topten':
			$order_by = "u.user_posts DESC LIMIT 10";
			break;
		case 'birthday':
			$order_by = "u.user_birthday $sort_order LIMIT $start, " . $users_per_page;
			break;
		case 'style':
			$order_by = "u.user_style $sort_order LIMIT $start, " . $users_per_page;
			$sql_style = true;
			break;
		case 'staff':
			$where_sql = "AND u.user_level != 0";
			$order_by = "u.user_level $sort_order LIMIT $start, " . $users_per_page;
			break;
		case 'online':
			if ($userdata['user_level'] == ADMIN)
			{
				$where_sql = "AND u.user_session_time >= $last_x_mins";
			}
			else
			{
				$where_sql = "AND u.user_session_time >= $last_x_mins AND u.user_allow_viewonline != 0";
			}
			$order_by = "u.user_session_time $sort_order LIMIT $start, " . $users_per_page;
			break;
		case 'lastlogon':
			if ($userdata['user_level'] == ADMIN)
			{
				$order_by = "u.user_lastlogon $sort_order LIMIT $start, " . $users_per_page;
			}
			else
			{
				$where_sql = "AND u.user_allow_viewonline != 0";
				$order_by = "u.user_lastlogon $sort_order LIMIT $start, " . $users_per_page;
			}
			break;
		default:
			$order_by = "u.user_regdate $sort_order LIMIT $start, " . $users_per_page;
			break;
	}
}

if ($config['inactive_users_memberlists'] == true)
{
	$sql_active_users = '';
}
else
{
	$sql_active_users = 'AND user_active = 1';
}

if ($sql_style == true)
{
	$sql_style_select = ', t.themes_id, t.style_name';
	$sql_style_from = ', ' . THEMES_TABLE . ' t';
	$sql_style_where = 'AND t.themes_id = u.user_style';
}
else
{
	$sql_style_select = '';
	$sql_style_from = '';
	$sql_style_where = '';
}

//$sql = "SELECT u.*" . $sql_style_select . "
$sql = "SELECT u.username, u.user_id, u.user_active, u.user_color, u.user_level, u.user_viewemail, u.user_posts, u.user_regdate, u.user_from, u.user_website, u.user_email, u.user_icq, u.user_aim, u.user_yim, u.user_msnm, u.user_skype, u.user_avatar, u.user_avatar_type, u.user_allowavatar, u.user_from, u.user_from_flag, u.user_rank, u.user_rank2, u.user_rank3, u.user_rank4, u.user_rank5, u.user_birthday, u.user_gender, u.user_allow_viewonline, u.user_lastlogon, u.user_lastvisit, u.user_session_time, u.user_style, u.user_lang" . $sql_style_select . "
	FROM " . USERS_TABLE . " u" . $sql_style_from . "
	WHERE u.user_id <> " . ANONYMOUS . "
		" . $sql_style_where . "
		$sql_active_users
		$where_sql
		$alpha_where
		ORDER BY $order_by";

// MG Cash MOD For IP - BEGIN
if (!empty($config['plugins']['cash']['enabled']))
{
	$cm_memberlist->generate_columns($template, $sql, 8);
}
// MG Cash MOD For IP - END

$result = $db->sql_query($sql);

if ($row = $db->sql_fetchrow($result))
{
	// Custom Profile Fields MOD - BEGIN
	include_once(IP_ROOT_PATH . 'includes/functions_profile.' . PHP_EXT);
	$profile_data = get_fields('WHERE view_in_memberlist = ' . VIEW_IN_MEMBERLIST . ' AND users_can_view = ' . ALLOW_VIEW);

	foreach($profile_data as $field)
	{
		$template->assign_block_vars('custom_field_names', array('FIELD_NAME' => $field['field_name']));
	}

	$template->assign_var('NUMCOLS', sizeof($profile_data) + 12);
	// Custom Profile Fields MOD - END
	$i = 0;
	do
	{
		if ($mode == 'fast')
		{
			$username = $row['username'];
			$user_id = $row['user_id'];
			$temp_url = append_sid(CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $user_id);
			$profile = '<a href="' . $temp_url . '">' . $lang['Read_profile'] . '</a>';
			$from = (!empty($row['user_from'])) ? $row['user_from'] : '&nbsp;';
			$joined = create_date($lang['JOINED_DATE_FORMAT'], $row['user_regdate'], $config['board_timezone']);
			$posts = ($row['user_posts']) ? $row['user_posts'] : 0;

			$user_rank_01 = '';
			$user_rank_01_img = '';
			$user_rank_02 = '';
			$user_rank_02_img = '';
			$user_rank_03 = '';
			$user_rank_03_img = '';
			$user_rank_04 = '';
			$user_rank_04_img = '';
			$user_rank_05 = '';
			$user_rank_05_img = '';

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
			$yim_url = '';
			$yim_img = '';
			$yim = '';
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

			$user_rank_01 = ($user_ranks['rank_01'] == '') ? '' : ($user_ranks['rank_01'] . '<br />');
			$user_rank_01_img = ($user_ranks['rank_01_img'] == '') ? '' : ($user_ranks['rank_01_img'] . '<br />');
			$user_rank_02 = ($user_ranks['rank_02'] == '') ? '' : ($user_ranks['rank_02'] . '<br />');
			$user_rank_02_img = ($user_ranks['rank_02_img'] == '') ? '' : ($user_ranks['rank_02_img'] . '<br />');
			$user_rank_03 = ($user_ranks['rank_03'] == '') ? '' : ($user_ranks['rank_03'] . '<br />');
			$user_rank_03_img = ($user_ranks['rank_03_img'] == '') ? '' : ($user_ranks['rank_03_img'] . '<br />');
			$user_rank_04 = ($user_ranks['rank_04'] == '') ? '' : ($user_ranks['rank_04'] . '<br />');
			$user_rank_04_img = ($user_ranks['rank_04_img'] == '') ? '' : ($user_ranks['rank_04_img'] . '<br />');
			$user_rank_05 = ($user_ranks['rank_05'] == '') ? '' : ($user_ranks['rank_05'] . '<br />');
			$user_rank_05_img = ($user_ranks['rank_05_img'] == '') ? '' : ($user_ranks['rank_05_img'] . '<br />');
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

		$deluser_url = (($userdata['user_level'] == ADMIN) ? append_sid('delete_users.' . PHP_EXT . '?mode=user_id&amp;del_user=' . $user_id) : '');
		$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];
		$template->assign_block_vars('memberrow', array(
			'ROW_NUMBER' => $i + ($start + 1),
			//'ROW_NUMBER' => $i + ($_GET['start'] + 1) . (($userdata['user_level'] == ADMIN) ? '&nbsp;<a href="' . append_sid('delete_users.' . PHP_EXT . '?mode=user_id&amp;del_user=' . $user_id) . '"><img src="' . $images['icon_delpost'] . '" alt="' . $lang['Delete'] . '" title="' . $lang['Delete'] . '" /></a>&nbsp;':''),
			'ROW_CLASS' => $row_class,
			'USERNAME' => colorize_username($row['user_id'], $row['username'], $row['user_color'], $row['user_active']),
			'FROM' => $from,
			'JOINED' => $joined,
			'DELETE' => (($userdata['user_level'] == ADMIN) ? '&nbsp;<a href="' . $deluser_url . '"><img src="' . $images['icon_delpost'] . '" alt="' . $lang['Delete'] . '" title="' . $lang['Delete'] . '" /></a>&nbsp;':''),

			// Start add - Last visit MOD
			'LAST_LOGON' => ($userdata['user_level'] == ADMIN || (!$config['hidde_last_logon'] && $row['user_allow_viewonline'])) ? (($row['user_lastlogon'])? create_date($config['default_dateformat'], $row['user_lastlogon'], $config['board_timezone']) : $lang['Never_last_logon']) : $lang['Hidde_last_logon'],
			// End add - Last visit MOD

			'POSTS' => $posts,
			'AVATAR_IMG' => $poster_avatar,
			'PROFILE_IMG' => $profile_img,
			'PROFILE' => $profile,
			'SEARCH_IMG' => $search_img,
			'SEARCH' => $search,
			'PM_IMG' => $pm_img,
			'PM' => $pm,
			'EMAIL_IMG' => (!$userdata['session_logged_in']) ? '' : $email_img,
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
			'YIM_IMG' => $yim_img,
			'YIM' => $yim,
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
			'U_YIM' => $yim_url,
			'L_POSTER_ONLINE_STATUS' => $online_status_lang,
			'POSTER_ONLINE_STATUS_CLASS' => $online_status_class,
			'U_POSTER_ONLINE_STATUS' => $online_status_url,
			'U_DELETE' => $deluser_url,

			// Mighty Gorgon - Multiple Ranks - BEGIN
			'USER_LEVEL' => $level,
			'USER_RANK_01' => $user_rank_01,
			'USER_RANK_01_IMG' => $user_rank_01_img,
			'USER_RANK_02' => $user_rank_02,
			'USER_RANK_02_IMG' => $user_rank_02_img,
			'USER_RANK_03' => $user_rank_03,
			'USER_RANK_03_IMG' => $user_rank_03_img,
			'USER_RANK_04' => $user_rank_04,
			'USER_RANK_04_IMG' => $user_rank_04_img,
			'USER_RANK_05' => $user_rank_05,
			'USER_RANK_05_IMG' => $user_rank_05_img,
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

		$i++;
	}
	while ($row = $db->sql_fetchrow($result));
	$db->sql_freeresult($result);
}
else
{
	$template->assign_var('NUMCOLS', 20);
}

if (($mode != 'topten') || ($users_per_page < 10))
{
	if ($config['inactive_users_memberlists'] == true)
	{
		$sql_active_users = '';
	}
	else
	{
		$sql_active_users = 'AND user_active = 1';
	}

	$sql = "SELECT count(*) AS total
		FROM " . USERS_TABLE . " u
		WHERE user_id <> " . ANONYMOUS . "
		$sql_active_users
		$where_sql
		$alpha_where";
	$result = $db->sql_query($sql);

	if ($total = $db->sql_fetchrow($result))
	{
		$total_members = $total['total'];

		//$pagination = generate_pagination(CMS_PAGE_MEMBERLIST . '?mode=' . $mode . '&amp;order=' . $sort_order, $total_members, $users_per_page, $start). '&nbsp;';
		$pagination = generate_pagination(CMS_PAGE_MEMBERLIST . '?mode=' . $mode . '&amp;order=' . $sort_order . '&amp;users_per_page=' . $users_per_page . (!empty($alphanum) ? '&amp;alphanum=' . htmlspecialchars($alphanum) : ''), $total_members, $users_per_page, $start);
	}
	$db->sql_freeresult($result);
}
else
{
	$pagination = '&nbsp;';
	$total_members = 10;
}

$template->assign_vars(array(
	'PAGINATION' => $pagination,
	'PAGE_NUMBER' => sprintf($lang['Page_of'], (floor($start / $users_per_page) + 1), ceil($total_members / $users_per_page)),
	'L_GOTO_PAGE' => $lang['Goto_page'])
);

full_page_generation('memberlist_body.tpl', $lang['Memberlist'], '', '');

?>