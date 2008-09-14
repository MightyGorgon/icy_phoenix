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

// CTracker_Ignore: File checked by human
define('IN_ICYPHOENIX', true);
// MG Cash MOD For IP - BEGIN
define('IN_CASHMOD', true);
define('CM_MEMBERLIST', true);
// MG Cash MOD For IP - END
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_groups.' . PHP_EXT);

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

$cms_page_id = '7';
$cms_page_name = 'memberlist';
$auth_level_req = $board_config['auth_view_memberlist'];
if ($auth_level_req > AUTH_ALL)
{
	if (($auth_level_req == AUTH_REG) && (!$userdata['session_logged_in']))
	{
		message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
	}
	if ($userdata['user_level'] != ADMIN)
	{
		if ($auth_level_req == AUTH_ADMIN)
		{
			message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
		}
		if (($auth_level_req == AUTH_MOD) && ($userdata['user_level'] != MOD))
		{
			message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
		}
	}
}
$cms_global_blocks = ($board_config['wide_blocks_memberlist'] == 1) ? true : false;

$start = (isset($_GET['start'])) ? intval($_GET['start']) : 0;
$start = ($start < 0) ? 0 : $start;

$page_number = (isset($_GET['page_number']) ? intval($_GET['page_number']) : (isset($_POST['page_number']) ? intval($_POST['page_number']) : false));
$page_number = ($page_number < 1) ? false : $page_number;

$start = (!$page_number) ? $start : (($page_number * $board_config['topics_per_page']) - $board_config['topics_per_page']);

// Memberlist sorting
$mode_types_text = array($lang['Fast'], $lang['Standard'], $lang['Staff'], $lang['Sort_Joined'], $lang['Sort_Username'], $lang['Sort_Location'], $lang['Sort_Posts'], $lang['Sort_Email'], $lang['Sort_Website'], $lang['Sort_Top_Ten'], $lang['Sort_Birthday'], $lang['Style'], $lang['Who_is_Online'], $lang['Sort_LastLogon']);
$mode_types = array('fast', 'standard', 'staff', 'joined', 'username', 'location', 'posts', 'email', 'website', 'topten', 'birthday', 'style', 'online', 'lastlogon');

if (isset($_GET['mode']) || isset($_POST['mode']))
{
	$mode = (isset($_POST['mode'])) ? htmlspecialchars($_POST['mode']) : htmlspecialchars($_GET['mode']);
}
else
{
	$mode = 'joined';
}

// Do not change strpos() == 0! Remember that == 0 is different from === false!!!
if (!in_array($mode, $mode_types) && !(strpos($mode, 'cash_') == 0))
{
	$mode = 'joined';
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
	$sort_order = 'ASC';
}

// Mighty Gorgon - Power Memberlist - BEGIN
if (isset($_GET['alphanum']) || isset($_POST['alphanum']))
{
	$alphanum = (isset($_POST['alphanum'])) ? htmlspecialchars($_POST['alphanum']) : htmlspecialchars($_GET['alphanum']);
	$alpha_where = ($alphanum == '#') ? "AND username NOT RLIKE '^[A-Z]'" : "AND username LIKE '$alphanum%'";
}

if (isset($_GET['users_per_page']) || isset($_POST['users_per_page']))
{
	$board_config['topics_per_page'] = (isset($_POST['users_per_page'])) ? intval($_POST['users_per_page']) : intval($_GET['users_per_page']);
}
$users_per_page = ($board_config['topics_per_page'] > 200) ? 200 : $board_config['topics_per_page'];
// Mighty Gorgon - Power Memberlist - END

// MG Cash MOD For IP - BEGIN
if (defined('CASH_MOD'))
{
	$cm_memberlist->droplists($mode_types_text, $mode_types);
}
// MG Cash MOD For IP - END

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

// Generate page
$page_title = $lang['Memberlist'];
$meta_description = '';
$meta_keywords = '';
include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);

$template->set_filenames(array('body' => 'memberlist_body.tpl'));
make_jumpbox(VIEWFORUM_MG);

build_groups_list_template();

$template->assign_vars(array(
	'L_SELECT_SORT_METHOD' => $lang['Select_sort_method'],
	'L_EMAIL' => $lang['Email'],
	'L_WEBSITE' => $lang['Website'],
	'L_FROM' => $lang['Location'],
	'L_ORDER' => $lang['Order'],
	'L_SORT' => $lang['Sort'],
	'L_SUBMIT' => $lang['Sort'],
	'L_AIM' => $lang['AIM'],
	'L_YIM' => $lang['YIM'],
	'L_MSNM' => $lang['MSNM'],
	'L_ICQ' => $lang['ICQ'],
	'L_JOINED' => $lang['Joined'],
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
	'S_MODE_ACTION' => append_sid('memberlist.' . PHP_EXT)
	)
);

// Mighty Gorgon - Power Memberlist - BEGIN
$alpha_range = range('A','Z');
$alphanum_range = array_merge(array('' => 'All'),array('%23' => '#'),$alpha_range);
foreach ($alphanum_range as $key => $alpha)
{
	if (in_array($alpha,$alpha_range)) $key = $alpha;
	$alphanum_search_url = append_sid('memberlist.' . PHP_EXT . '?mode=' . ((isset($_GET['mode']) || isset($_POST['mode'])) ? $mode : 'username') . '&amp;sort=' . $sort_order . '&amp;alphanum=' . strtolower($key));
	$template->assign_block_vars('alphanumsearch', array(
		'SEARCH_SIZE' => floor(100 / count($alphanum_range)) . '%',
		'SEARCH_TERM' => $alpha,
		'SEARCH_LINK' => $alphanum_search_url)
	);
}
// Mighty Gorgon - Power Memberlist - END

// Mighty Gorgon - Multiple Ranks - BEGIN
require_once(IP_ROOT_PATH . 'includes/functions_mg_ranks.' . PHP_EXT);
$ranks_sql = query_ranks();
// Mighty Gorgon - Multiple Ranks - END

$last_x_mins = time() - 300;
$sql_style = false;

$cash_condition = false;
// MG Cash MOD For IP - BEGIN
if (defined('CASH_MOD'))
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

if ($board_config['inactive_users_memberlists'] == true)
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
$sql = "SELECT u.username, u.user_id, u.user_active, u.user_level, u.user_viewemail, u.user_posts, u.user_regdate, u.user_from, u.user_website, u.user_email, u.user_icq, u.user_aim, u.user_yim, u.user_msnm, u.user_skype, u.user_avatar, u.user_avatar_type, u.user_allowavatar, u.user_from, u.user_from_flag, u.user_rank, u.user_rank2, u.user_rank3, u.user_rank4, u.user_rank5, u.user_birthday, u.user_gender, u.user_allow_viewonline, u.user_lastlogon, u.user_lastvisit, u.user_session_time, u.user_style, u.user_lang" . $sql_style_select . "
	FROM " . USERS_TABLE . " u" . $sql_style_from . "
	WHERE u.user_id <> " . ANONYMOUS . "
		" . $sql_style_where . "
		$sql_active_users
		$where_sql
		$alpha_where
		ORDER BY $order_by";

// MG Cash MOD For IP - BEGIN
if (defined('CASH_MOD'))
{
	$cm_memberlist->generate_columns($template, $sql, 8);
}
// MG Cash MOD For IP - END

if(!($result = $db->sql_query($sql)))
{
	message_die(GENERAL_ERROR, 'Could not query users', '', __LINE__, __FILE__, $sql);
}

if ($row = $db->sql_fetchrow($result))
{
	// Custom Profile Fields MOD - BEGIN
	include_once(IP_ROOT_PATH . 'includes/functions_profile_fields.' . PHP_EXT);
	$profile_data = get_fields('WHERE view_in_memberlist = ' . VIEW_IN_MEMBERLIST . ' AND users_can_view = ' . ALLOW_VIEW);

	foreach($profile_data as $field)
	{
		$template->assign_block_vars('custom_field_names', array('FIELD_NAME' => $field['field_name']));
	}

	$template->assign_var('NUMCOLS', count($profile_data) + 12);
	// Custom Profile Fields MOD - END
	$i = 0;
	do
	{
		if ($mode == 'fast')
		{
			$username = $row['username'];
			$user_id = $row['user_id'];
			$temp_url = append_sid(PROFILE_MG . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $user_id);
			$profile = '<a href="' . $temp_url . '">' . $lang['Read_profile'] . '</a>';
			$from = (!empty($row['user_from'])) ? $row['user_from'] : '&nbsp;';
			$joined = create_date($lang['JOINED_DATE_FORMAT'], $row['user_regdate'], $board_config['board_timezone']);
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
			$online_img = '';
			$profile_img = '';
			$pm = '';
			$pm_img = '';
			$email = '';
			$email_img = '';
			$www = '';
			$www_img = '';
			$msn = '';
			$msn_img = '';
			$yim = '';
			$yim_img = '';
			$skype_img = '';
			$skype = '';
			$icq = '';
			$icq_status_img = '';
			$icq_img = '';
			$aim = '';
			$aim_img = '';
		}
		else
		{
			$username = $row['username'];
			$user_id = $row['user_id'];

			// Mighty Gorgon - Multiple Ranks - BEGIN
			$user_ranks = generate_ranks($row, $ranks_sql);

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

			$from = (!empty($row['user_from'])) ? $row['user_from'] : '&nbsp;';

			$joined = create_date($lang['JOINED_DATE_FORMAT'], $row['user_regdate'], $board_config['board_timezone']);
			$posts = ($row['user_posts']) ? $row['user_posts'] : 0;

			$username = colorize_username($user_id);

			$poster_avatar = '';
			if ($mode == 'staff')
			{
				$template->assign_vars(array(
					'L_MEMBERLIST' => $lang['Staff'],
					)
				);

				$poster_avatar = user_get_avatar($row['user_id'], $row['user_avatar'], $row['user_avatar_type'], $row['user_allowavatar']);
			}

			if ($sql_style == true)
			{
				$style = '<br />' . $lang['Style'] . ':&nbsp;' . $row['style_name'];
			}
			else
			{
				$style = '';
			}

			if (empty($userdata['user_id']) || ($userdata['user_id'] == ANONYMOUS))
			{
				if (!empty($row['user_viewemail']))
				{
					$email_img = '<img src="' . $images['icon_email'] . '" alt="' . $lang['Hidden_email'] . '" title="' . $lang['Hidden_email'] . '" />';
				}
				else
				{
					$email_img = '&nbsp;';
				}
				$email = '&nbsp;';
			}
			elseif (!empty($row['user_viewemail']) || $userdata['user_level'] == ADMIN)
			{
				$email_uri = ($board_config['board_email_form']) ? append_sid(PROFILE_MG . '?mode=email&amp;' . POST_USERS_URL .'=' . $user_id) : 'mailto:' . $row['user_email'];

				$email_img = '<a href="' . $email_uri . '"><img src="' . $images['icon_email'] . '" alt="' . $lang['Send_email'] . '" title="' . $lang['Send_email'] . '" /></a>';
				$email = '<a href="' . $email_uri . '">' . $lang['Send_email'] . '</a>';
			}
			else
			{
				$email_img = '&nbsp;';
				$email = '&nbsp;';
			}

			$temp_url = append_sid(PROFILE_MG . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $user_id);
			$profile_img = '<a href="' . $temp_url . '"><img src="' . $images['icon_profile'] . '" alt="' . $lang['Read_profile'] . '" title="' . $lang['Read_profile'] . '" /></a>';
			$profile = '<a href="' . $temp_url . '">' . $lang['Read_profile'] . '</a>';

			$temp_url = append_sid('privmsg.' . PHP_EXT . '?mode=post&amp;' . POST_USERS_URL . '=' . $user_id);
			$pm_img = '<a href="' . $temp_url . '"><img src="' . $images['icon_pm'] . '" alt="' . $lang['Send_private_message'] . '" title="' . $lang['Send_private_message'] . '" /></a>';
			$pm = '<a href="' . $temp_url . '">' . $lang['Send_private_message'] . '</a>';

			$www_img = ($row['user_website']) ? '<a href="' . $row['user_website'] . '" target="_blank"><img src="' . $images['icon_www'] . '" alt="' . $lang['Visit_website'] . '" title="' . $lang['Visit_website'] . '" /></a>' : '';
			$www = ($row['user_website']) ? '<a href="' . $row['user_website'] . '" target="_blank">' . $lang['Visit_website'] . '</a>' : '';

			$icq_status_img = (!empty($row['user_icq'])) ? '<a href="http://wwp.icq.com/' . $row['user_icq'] . '#pager"><img src="http://web.icq.com/whitepages/online?icq=' . $row['user_icq'] . '&img=5" width="18" height="18" /></a>' : '';
			$icq_img = (!empty($row['user_icq'])) ? build_im_link('icq', $row['user_icq'], $lang['ICQ'], $images['icon_icq2']) : '';
			$icq = (!empty($row['user_icq'])) ? build_im_link('icq', $row['user_icq'], $lang['ICQ'], false) : '';

			$aim_img = (!empty($row['user_aim'])) ? build_im_link('aim', $row['user_aim'], $lang['AIM'], $images['icon_aim2']) : '';
			$aim = (!empty($row['user_aim'])) ? build_im_link('aim', $row['user_aim'], $lang['AIM'], false) : '';

			$msn_img = (!empty($row['user_msnm'])) ? build_im_link('msn', $row['user_msnm'], $lang['MSNM'], $images['icon_msnm2']) : '';
			$msn = (!empty($row['user_msnm'])) ? build_im_link('msn', $row['user_msnm'], $lang['MSNM'], false) : '';

			$yim_img = (!empty($row['user_yim'])) ? build_im_link('yahoo', $row['user_yim'], $lang['YIM'], $images['icon_yim2']) : '';
			$yim = (!empty($row['user_yim'])) ? build_im_link('yahoo', $row['user_yim'], $lang['YIM'], false) : '';

			$skype_img = (!empty($row['user_skype'])) ? build_im_link('skype', $row['user_skype'], $lang['SKYPE'], $images['icon_skype2']) : '';
			$skype = (!empty($row['user_skype'])) ? build_im_link('skype', $row['user_skype'], $lang['SKYPE'], false) : '';

			$temp_url = append_sid(SEARCH_MG . '?search_author=' . urlencode($username) . '&amp;showresults=posts');
			$search_img = '<a href="' . $temp_url . '"><img src="' . $images['icon_search'] . '" alt="' . sprintf($lang['Search_user_posts'], $username) . '" title="' . sprintf($lang['Search_user_posts'], $username) . '" /></a>';
			$search = '<a href="' . $temp_url . '">' . sprintf($lang['Search_user_posts'], $username) . '</a>';

			// Start add - Online/Offline/Hidden Mod
			if ($row['user_session_time'] >= (time() - $board_config['online_time']))
			{
				if ($row['user_allow_viewonline'])
				{
					$online_status_img = '<a href="' . append_sid('viewonline.' . PHP_EXT) . '"><img src="' . $images['icon_online2'] . '" alt="' . $lang['Online'] . '" title="' . $lang['Online'] . '" /></a>';
					$online_status = '<strong><a href="' . append_sid('viewonline.' . PHP_EXT) . '" title="' . sprintf($lang['is_online'], $username) . '"' . $online_color . '>' . $lang['Online'] . '</a></strong>';
				}
				else if ($userdata['user_level'] == ADMIN || $userdata['user_id'] == $user_id)
				{
					$online_status_img = '<a href="' . append_sid('viewonline.' . PHP_EXT) . '"><img src="' . $images['icon_hidden2'] . '" alt="' . $lang['Hidden'] . '" title="' . $lang['Hidden'] . '" /></a>';
					$online_status = '<strong><em><a href="' . append_sid('viewonline.' . PHP_EXT) . '" title="' . sprintf($lang['is_hidden'], $username) . '"' . $hidden_color . '>' . $lang['Hidden'] . '</a></em></strong>';
				}
				else
				{
					$online_status_img = '<img src="' . $images['icon_offline2'] . '" alt="' . $lang['Offline'] . '" title="' . $lang['Offline'] . '" />';
					$online_status = '<span title="' . sprintf($lang['is_offline'], $username) . '"' . $offline_color . '><strong>' . $lang['Offline'] . '</strong></span>';
				}
			}
			else
			{
				$online_status_img = '<img src="' . $images['icon_offline2'] . '" alt="' . $lang['Offline'] . '" title="' . $lang['Offline'] . '" />';
				$online_status = '<span title="' . sprintf($lang['is_offline'], $username) . '"' . $offline_color . '><strong>' . $lang['Offline'] . '</strong></span>';
			}
			// End add - Online/Offline/Hidden Mod
		}

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
		if ($row['user_birthday'] != 999999)
		{
			$age = realdate('Y',(time() / 86400)) - realdate ('Y', $row['user_birthday']);
			if (date('md') < realdate('md', $row['user_birthday']))
			{
				$age--;
			}
			$age = '(' . $age . ')';
		}
		else
		{
			$age = ' ';
		}

		$row_color = (!($i % 2)) ? $theme['td_color1'] : $theme['td_color2'];
		$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];
		$template->assign_block_vars('memberrow', array(
			'ROW_NUMBER' => $i + ($start + 1),
			//'ROW_NUMBER' => $i + ($_GET['start'] + 1) . (($userdata['user_level'] == ADMIN) ? '&nbsp;<a href="' . append_sid('delete_users.' . PHP_EXT . '?mode=user_id&amp;del_user=' . $user_id) . '"><img src="' . $images['icon_delpost'] . '" alt="' . $lang['Delete'] . '" title="' . $lang['Delete'] . '" /></a>&nbsp;':''),
			'ROW_COLOR' => '#' . $row_color,
			'ROW_CLASS' => $row_class,
			'USERNAME' => colorize_username($user_id),
			'FROM' => $from,
			'JOINED' => $joined,
			'DELETE' => (($userdata['user_level'] == ADMIN) ? '&nbsp;<a href="' . append_sid('delete_users.' . PHP_EXT . '?mode=user_id&amp;del_user=' . $user_id) . '"><img src="' . $images['icon_delpost'] . '" alt="' . $lang['Delete'] . '" title="' . $lang['Delete'] . '" /></a>&nbsp;':''),

			// Start add - Last visit MOD
			'LAST_LOGON' => ($userdata['user_level'] == ADMIN || (!$board_config['hidde_last_logon'] && $row['user_allow_viewonline'])) ? (($row['user_lastlogon'])? create_date($board_config['default_dateformat'], $row['user_lastlogon'], $board_config['board_timezone']) : $lang['Never_last_logon']) : $lang['Hidde_last_logon'],
			// End add - Last visit MOD

			'POSTS' => $posts,
			'AVATAR_IMG' => $poster_avatar,
			'PROFILE_IMG' => $profile_img,
			'PROFILE' => $profile,
			'SEARCH_IMG' => $search_img,
			'SEARCH' => $search,
			'PM_IMG' => $pm_img,
			'PM' => $pm,
			'EMAIL_IMG' => (!$userdata['session_logged_in'])? '' : $email_img,
			'EMAIL' => $email,
			'WWW_IMG' => $www_img,
			'WWW' => $www,
			'ICQ_STATUS_IMG' => $icq_status_img,
			'ICQ_IMG' => $icq_img,
			'ICQ' => $icq,
			'AIM_IMG' => $aim_img,
			'AIM' => $aim,
			'MSN_IMG' => $msn_img,
			'MSN' => $msn,
			'YIM_IMG' => $yim_img,
			'YIM' => $yim,
			'SKYPE_IMG' => $skype_img,
			'SKYPE' => $skype,
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
			'POSTER_GENDER' => $gender_image,
			'STYLE' => $style,
			'BIRTHDAY' => $row['user_birthday'],
			'AGE' => $age,
			// Start add - Online/Offline/Hidden Mod
			'ONLINE_STATUS_IMG' => $online_status_img,
			// End add - Online/Offline/Hidden Mod

			'U_VIEWPROFILE' => append_sid(PROFILE_MG . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $user_id)
			)
		);
		// MG Cash MOD For IP - BEGIN
		if (defined('CASH_MOD'))
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
			if(!($result2 = $db->sql_query($sql2)))
				message_die(GENERAL_ERROR,'Could not get custom profile data','',__LINE__,__FILE__,$sql2);

			$val = $db->sql_fetchrow($result2);
			$val = displayable_field_data($val[$name],$field['field_type']);

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
	if ($board_config['inactive_users_memberlists'] == true)
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

	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Error getting total users', '', __LINE__, __FILE__, $sql);
	}

	if ($total = $db->sql_fetchrow($result))
	{
		$total_members = $total['total'];

		//$pagination = generate_pagination('memberlist.' . PHP_EXT . '?mode=' . $mode . '&amp;order=' . $sort_order, $total_members, $users_per_page, $start). '&nbsp;';
		$pagination = generate_pagination('memberlist.' . PHP_EXT . '?mode=' . $mode . '&amp;order=' . $sort_order . '&amp;users_per_page=' . $users_per_page . ((isset($alphanum)) ? '&amp;alphanum=' . $alphanum : ''), $total_members, $users_per_page, $start);
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

$template->pparse('body');

include(IP_ROOT_PATH . 'includes/page_tail.' . PHP_EXT);

?>