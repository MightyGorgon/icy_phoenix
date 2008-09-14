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
* (c) 2005 oxpus (Karsten Ude) <webmaster@oxpus.de> http://www.oxpus.de
* (c) hotschi / demolition fabi / oxpus
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

$sorting = (!$sorting) ? 'username' : $sorting;
$sql_order_dir = ($sort_order === '') ? 'ASC' : $sort_order;

if ($delete && (isset($_GET['del_id']) || isset($_POST['del_id'])))
{
	$del_id = ($_GET['del_id']) ? $_GET['del_id'] : $_POST['del_id'];

	if ($del_id == -1)
	{
		$sql = "DELETE FROM " . DL_STATS_TABLE . "
			WHERE user_id = -1";
		if (!($result= $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not delete statistical data from guests', '', __LINE__, __FILE__, $sql);
		}
	}
	else
	{
		$dl_id = '';
		for ($i = 0; $i < count($del_id); $i++)
		{
			$temp_id = intval($del_id[$i]);
			$dl_id .= ($dl_id == '') ? $temp_id : ', '.$temp_id;
		}

		$sql = "DELETE FROM " . DL_STATS_TABLE . "
			WHERE dl_id IN ($dl_id)";
		if (!($result= $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not delete statistical data', '', __LINE__, __FILE__, $sql);
		}
	}
}

switch($sorting)
{
	case 'cat':
		$sql_order_by = 'cat_name';
		$sql_order_by_2 = ', time_stamp DESC';
		break;

	case 'id':
		$sql_order_by = 'description';
		$sql_order_by_2 = ', time_stamp DESC';
		break;

	case 'size':
		$sql_order_by = 'traffic';
		$sql_order_by_2 = ', time_stamp DESC';
		break;

	case 'ip':
		$sql_order_by = 'user_ip';
		$sql_order_by_2 = ', time_stamp DESC';
		break;

	case 'agent':
		$sql_order_by = 'browser';
		$sql_order_by_2 = ', time_stamp DESC';
		break;

	case 'time':
		$sql_order_by = 'time_stamp';
		$sql_order_by_2 = '';
		break;

	default:
		$sql_order_by = 'username';
		$sql_order_by_2 = ', time_stamp DESC';
}

$s_sort_order = '<select name="sorting">';
$s_sort_order .= '<option value="username">' . $lang['Username'] . '</option>';
$s_sort_order .= '<option value="id">' . $lang['Downloads'] . '</option>';
$s_sort_order .= '<option value="cat">' . $lang['Dl_cat_name'] . '</option>';
$s_sort_order .= '<option value="size">' . $lang['Traffic'] . '</option>';
$s_sort_order .= '<option value="ip">' . $lang['Dl_ip'] . '</option>';
$s_sort_order .= '<option value="agent">' . $lang['Dl_browser'] . '</option>';
$s_sort_order .= '<option value="time">' . $lang['Time'] . '</option>';
$s_sort_order .= '</select>';
$s_sort_order = str_replace('value="'.$sorting.'">', 'value="'.$sorting.'" selected="selected">', $s_sort_order);

$s_sort_dir = '<select name="sort_order">';
$s_sort_dir .= '<option value="ASC">' . $lang['Sort_Ascending'] . '</option>';
$s_sort_dir .= '<option value="DESC">' . $lang['Sort_Descending'] . '</option>';
$s_sort_dir .= '</select>';
$s_sort_dir = str_replace('value="'.$sort_order.'">', 'value="'.$sort_order.'" selected="selected">', $s_sort_dir);

switch($filtering)
{
	case 'cat':
		$filter_by = 'd.cat_name';
		break;

	case 'id':
		$filter_by = 'd.description';
		break;

	case 'agent':
		$filter_by = 's.browser';
		break;

	case 'username':
		$filter_by = 's.username';
		break;

	default:
		$filter_by = '';
}

if ($filter_by && $filter_string)
{
	$filter_string = phpbb_clean_username(str_replace("*", "%", $filter_string));
	if ($filter_string != '%')
	{
		$sql_filter = (($show_guests) ? ' WHERE ' : ' AND ') . $filter_by . " LIKE ('%" . $filter_string . "%') ";

	}
	else
	{
		$sql_filter = '';
	}
}
else
{
	$sql_filter = '';
}

$s_filter = '<select name="filtering">';
$s_filter .= '<option value="-1">' . $lang['Dl_no_filter'] . '</option>';
$s_filter .= '<option value="username">' . $lang['Username'] . '</option>';
$s_filter .= '<option value="id">' . $lang['Downloads'] . '</option>';
$s_filter .= '<option value="cat">' . $lang['Dl_cat_name'] . '</option>';
$s_filter .= '<option value="agent">' . $lang['Dl_browser'] . '</option>';
$s_filter .= '</select>';
$s_filter = str_replace('value="' . $filtering . '">', 'value="' . $filtering . '" selected="selected">', $s_filter);

$template->set_filenames(array('stats' => ADM_TPL . 'dl_stats_admin_body.tpl'));

if ($show_guests)
{
	$sql_where = '';
}
else
{
	$sql_where = ' WHERE s.user_id <> ' . ANONYMOUS . ' ';
}

$sql = "SELECT s.*
	FROM ((" . DL_STATS_TABLE . " s
	LEFT JOIN " . DL_CAT_TABLE . " c ON c.id = s.cat_id)
	LEFT JOIN " . DOWNLOADS_TABLE . " d ON d.id = s.id)
		$sql_where
		$sql_filter";
if (!($result = $db->sql_query($sql)))
{
	message_die(GENERAL_ERROR, 'Could not fetch statistical data', '', __LINE__, __FILE__, $sql);
}

$total_data = $db->sql_numrows($result);
$db->sql_freeresult($result);

if ($total_data)
{
	if ($start >= $total_data && $start >= $dl_config['dl_links_per_page'])
	{
		$start -= $dl_config['dl_links_per_page'];
	}

	if ($total_data > $dl_config['dl_links_per_page'])
	{
		$pagination = generate_pagination('admin_downloads.' . PHP_EXT . '?submod=stats&sorting=' . $sorting . '&sort_order=' . $sort_order . '&show_guests=' . $show_guests, $total_data, $dl_config['dl_links_per_page'], $start);
	}
	else
	{
		$pagination = '';
	}

	$sql = "SELECT s.*, d.description, c.cat_name
		FROM ((" . DL_STATS_TABLE . " s
		LEFT JOIN " . DL_CAT_TABLE . " c ON c.id = s.cat_id)
		LEFT JOIN " . DOWNLOADS_TABLE . " d ON d.id = s.id)
			$sql_where
			$sql_filter
		ORDER BY $sql_order_by $sql_order_dir $sql_order_by_2
		LIMIT $start, " . $dl_config['dl_links_per_page'];
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not fetch statistical data', '', __LINE__, __FILE__, $sql);
	}

	$i = 0;
	while ($row = $db->sql_fetchrow($result))
	{
		switch ($row['direction'])
		{
			case 1:
				$direction = $lang['Dl_upload_file'];
				break;
			case 2:
				$direction = $lang['Dl_stat_edit'];
				break;
			default:
				$direction = $lang['Dl_download'];
		}

		$template->assign_block_vars('dl_stat_row', array(
			'CAT_NAME' => $row['cat_name'],
			'DESCRIPTION' => $row['description'],
			'USERNAME' => ($row['user_id'] == ANONYMOUS) ? $lang['Guest'] : '<a href="' . append_sid(IP_ROOT_PATH . PROFILE_MG . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $row['user_id']) . '">' . $row['username'] . '</a>',
			'TRAFFIC' => ($row['traffic'] == -1) ? $lang['Dl_extern'] : $dl_mod->dl_size($row['traffic']),
			'DIRECTION' => $direction,
			'USER_IP' => decode_ip($row['user_ip']),
			'BROWSER' => $row['browser'],
			'TIME_STAMP' => create_date($board_config['default_dateformat'], $row['time_stamp'], $board_config['board_timezone']),
			'ID' => $row['dl_id'],

			'ROW_CLASS' => ($i % 2) ? $theme['td_class1'] : $theme['td_class2'],

			'U_CAT_LINK' => append_sid(IP_ROOT_PATH . 'downloads.' . PHP_EXT . '?cat=' . $row['cat_id']),
			'U_DL_LINK' => append_sid(IP_ROOT_PATH . 'downloads.' . PHP_EXT . '?view=detail&amp;df_id='.$row['id'])
			)
		);
		$i++;
	}
	$db->sql_freeresult($result);

	$template->assign_block_vars('filled_footer', array());
}
else
{
	$template->assign_block_vars('no_dl_stat_row', array(
		'L_NO_STAT' => $lang['Dl_no_last_time']
		)
	);
}

$template->assign_vars(array(
	'L_DL_STATS' => $lang['Dl_stats'],
	'L_USERNAME' => $lang['Username'],
	'L_CAT_NAME' => $lang['Dl_cat_name'],
	'L_DL_FILE_NAME' => $lang['Downloads'],
	'L_TRAFFIC' => $lang['Traffic'],
	'L_DL_DIRECTION' => $lang['Dl_direction'],
	'L_USER_IP' => $lang['Dl_ip'],
	'L_BROWSER' => $lang['Dl_browser'],
	'L_TIME_STAMP' => $lang['Time'],
	'L_SORT_BY' => $lang['Sort'],
	'L_SORT_DIR' => $lang['Order'],
	'L_MARK_ALL' => $lang['Mark_all'],
	'L_UNMARK_ALL' => $lang['Unmark_all'],
	'L_DELETE' => $lang['Delete'],
	'L_SORT' => $lang['Sort'],
	'L_SHOW_GUESTS' => $lang['Dl_guest_stats_admin'],
	'L_GUESTS_DELETE' => $lang['Dl_guest_stat_delete'],
	'L_TOTAL_DATA' => $lang['Dl_total_entries'],
	'L_FILTER' => $lang['Dl_filter'],
	'L_FILTER_STRING' => $lang['Dl_filter_string'],

	'PAGINATION' => $pagination,
	'TOTAL_DATA' => $total_data,
	'FILTER_STRING' => $filter_string,

	'S_FILTER' => $s_filter,
	'S_SHOW_GUESTS' => ($show_guests) ? 'checked="checked"' : '',
	'S_FORM_ACTION' => append_sid('admin_downloads.' . PHP_EXT . '?submod=stats'),
	'S_SORT_ORDER' => $s_sort_order,
	'S_SORT_DIR' => $s_sort_dir
	)
);

$template->pparse('stats');

?>