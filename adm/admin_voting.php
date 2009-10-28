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
* ErDrRon (ErDrRon@aol.com)
*
*/

define('IN_ICYPHOENIX', true);

if(!empty($setmodules))
{
	$filename = basename(__FILE__);
	$module['1610_Users']['290_Poll_Results'] = $filename;
	return;
}

if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('pagestart.' . PHP_EXT);

// Initialize variables
// Determine current starting row
$start = (isset($_GET['start'])) ? intval($_GET['start']) : 0;
$start = ($start < 0) ? 0 : $start;

// Determine current sort field
if(isset($_GET['field']) || isset($_POST['field']))
{
	$sort_field = (isset($_POST['field'])) ? $_POST['field'] : $_GET['field'];
}
else
{
	$sort_field = 'vote_id';
}

// Determine current sort order
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

// Assign sort fields
$sort_fields_text = array(
	$lang['Sort_vote_id'],
	$lang['Sort_poll_topic'],
	$lang['Sort_vote_start']
);

$sort_fields = array(
	'vote_id',
	'poll_topic',
	'vote_start'
);

if (empty($sort_field))
{
	$sort_field = 'vote_id';
	$sort_order = 'ASC';
}

// Set select fields
if (sizeof($sort_fields_text) > 0)
{
	$select_sort_field = '<select name="field">';

	for($i = 0; $i < sizeof($sort_fields_text); $i++)
	{
		$selected = ($sort_field == $sort_fields[$i]) ? ' selected="selected"' : '';
		$select_sort_field .= '<option value="' . $sort_fields[$i] . '"' . $selected . '>' . $sort_fields_text[$i] . '</option>';
	}
	$select_sort_field .= '</select>';
}

if (!empty($sort_order))
{
	$select_sort_order = '<select name="order">';
	if($sort_order == 'ASC')
	{
		$select_sort_order .= '<option value="ASC" selected="selected">' . $lang['Sort_ascending'] . '</option><option value="DESC">' . $lang['Sort_descending'] . '</option>';
	}
	else
	{
		$select_sort_order .= '<option value="ASC">' . $lang['Sort_ascending'] . '</option><option value="DESC" selected="selected">' . $lang['Sort_descending'] . '</option>';
	}
	$select_sort_order .= '</select>';
}

// Select query sort criteria
$order_by = '';

switch($sort_field)
{
	case 'vote_id':
		$order_by = 'vote_id ' . $sort_order .
					' LIMIT ' . $start . ", " . $config['topics_per_page'];
		break;
	case 'poll_topic':
		$order_by = 'vote_text ' . $sort_order .
					' LIMIT ' . $start . ", " . $config['topics_per_page'];
		break;
	case 'vote_start':
		$order_by = 'vote_start ' . $sort_order .
					' LIMIT ' . $start . ", " . $config['topics_per_page'];
		break;
	default:
		$sort_field = 'vote_id';
		$sort_order = 'ASC';
		$order_by = 'vote_id ' . $sort_order .
					' LIMIT ' . $start . ", " . $config['topics_per_page'];
		break;
}

// Build arrays
//
// Assign page template
$template->set_filenames(array('pollbody' => ADM_TPL . 'admin_voting_body.tpl'));

// Assign labels
$template->assign_vars(array(
	'L_ADMIN_VOTE_EXPLAIN' => $lang['Admin_Vote_Explain'],
	'L_ADMIN_VOTE_TITLE' => $lang['Admin_Vote_Title'],
	'L_VOTE_ID' => $lang['Vote_id'],
	'L_POLL_TOPIC' => $lang['Poll_topic'],
	'L_VOTE_USERNAME' => $lang['Vote_username'],
	'L_VOTE_END_DATE' => $lang['Vote_end_date'],
	'L_SUBMIT' => $lang['Submit'],
	'L_SELECT_SORT_FIELD' => $lang['Select_sort_field'],
	'L_SORT_ORDER' => $lang['Sort_order'],

	'S_FIELD_SELECT' => $select_sort_field,
	'S_ORDER_SELECT' => $select_sort_order,

	'ADMIN_VOTING_ICON' => '<img src="' . IP_ROOT_PATH . 'templates/common/images/admin_voting_icon.gif" alt="' . $lang['Admin_Vote_Title'] .'" />',
	));

// Assign Username array
$sql = "SELECT DISTINCT u.user_id, u.username, u.user_active, u.user_color
		FROM " . USERS_TABLE . " AS u , " . VOTE_USERS_TABLE . " AS vv
		WHERE u.user_id = vv.vote_user_id";
$result = $db->sql_query($sql);

$users_arr[] = array();
while ($row = $db->sql_fetchrow($result))
{
	$user_id = $row['user_id'];
	$username = $row['username'];
	$user_arr[$user_id] = $username;
	$users_arr[$user_id]['user_id'] = $row['user_id'];
	$users_arr[$user_id]['username'] = $row['username'];
	$users_arr[$user_id]['user_active'] = $row['user_active'];
	$users_arr[$user_id]['user_color'] = $row['user_color'];
}

// Assign poll options array
$sql = "SELECT *
		FROM " . VOTE_RESULTS_TABLE . "
		ORDER BY vote_id";
$result = $db->sql_query($sql);

while ($row = $db->sql_fetchrow($result))
{
	$vote_id = $row['vote_id'];
	$vote_option_id = $row['vote_option_id'];
	$vote_option_text = $row['vote_option_text'];
	$vote_result = $row['vote_result'];
	$option_arr[$vote_id][$vote_option_id]['text'] = $vote_option_text;
	$option_arr[$vote_id][$vote_option_id]['result'] = $vote_result;
}

// Assign individual vote results
$sql = "SELECT vote_id, vote_user_id, vote_cast
		FROM " . VOTE_USERS_TABLE . "
		ORDER BY vote_id";
$result = $db->sql_query($sql);

while ($row = $db->sql_fetchrow($result))
{
	$vote_id = $row['vote_id'];
	$vote_user_id = $row['vote_user_id'];
	$vote_cast = $row['vote_cast'];
	$voter_arr[$vote_id][$vote_user_id] = $vote_cast;
}


$sql ="SELECT *
		FROM " . VOTE_DESC_TABLE . "
		ORDER BY " . $order_by;
$result = $db->sql_query($sql);
$num_polls = $db->sql_numrows($result);

$i = 0;
while ($row = $db->sql_fetchrow($result))
{
	$topic_row_color = (($i % 2) == 0) ? 'row1' : 'row2';
	$vote_id = $row['vote_id'];
	$vote_text = $row['vote_text'];
	$topic_id = $row['topic_id'];
	$vote_start = $row['vote_start'];
	$vote_length = $row['vote_length'];
	$vote_end = $vote_start + $vote_length;

	if (time() < $vote_end)
	{
		$vote_duration = (date ('Y/m/d', $vote_start)) . " - " . (date ('Y/m/d', $vote_end)) . " (ongoing)";
	}
	elseif ($vote_length == 0)
	{
		$vote_duration = (date ('Y/m/d', $vote_start)) . " - " . "Infinite..." ;
	}
	else
	{
		$vote_duration = (date ('Y/m/d', $vote_start)) . " - " . (date ('Y/m/d', $vote_end)) . " (completed)" ;
	}

	$user = '';
	$users = '';
	$user_option_arr = '';

	if (sizeof($voter_arr[$vote_id]) > 0)
	{
		foreach($voter_arr[$vote_id] as $user_id => $option_id)
		{
			$current_username = colorize_username($users_arr[$user_id]['user_id'], $users_arr[$user_id]['username'], $users_arr[$user_id]['user_color'], $users_arr[$user_id]['user_active']);
			$user .= $current_username . ', ';
			$user_option_arr[$option_id] .= $current_username . ', ';
		}
		$user = substr($user, '0', strrpos($user, ', '));
	}

	$template->assign_block_vars('votes', array(
		'COLOR' => $topic_row_color,
		'LINK' => IP_ROOT_PATH . CMS_PAGE_VIEWTOPIC . '?' . POST_TOPIC_URL . '=' . $topic_id,
		'DESCRIPTION' => $vote_text,
		'USER' => $user,
		'ENDDATE' => $vote_end,
		'VOTE_DURATION' => $vote_duration,
		'VOTE_ID' => $vote_id
		)
	);

	if (sizeof($voter_arr[$vote_id]) > 0)
	{
		foreach($option_arr[$vote_id] as $vote_option_id => $elem)
		{
			$option_text = $elem['text'];
			$option_result = $elem['result'];
			$user = $user_option_arr[$vote_option_id];
			$user = substr($user, '0', strrpos($user, ', '));

			$template->assign_block_vars('votes.detail', array(
				'OPTION' => $option_text,
				'RESULT' => $option_result,
				'USER' => $user
				)
			);
		}
	}

	$i++;

}

// Pagination routine
$sql = "SELECT count(*) AS total" .
		" FROM " . VOTE_DESC_TABLE .
		" WHERE vote_id > 0";
$result = $db->sql_query($sql);

if ($total = $db->sql_fetchrow($result))
{
	$total_polls = $total['total'];
	$pagination = generate_pagination('admin_voting.' . PHP_EXT . '?mode=' . $sort_field . '&amp;order=' . $sort_order, $total_polls, $config['topics_per_page'], $start). '&nbsp;';
}

$template->assign_vars(array(
	'PAGINATION' => $pagination,
	'PAGE_NUMBER' => sprintf($lang['Page_of'], (floor($start / $config['topics_per_page']) + 1), ceil($total_polls / $config['topics_per_page'])),

	'L_GOTO_PAGE' => $lang['Goto_page']
	)
);

$template->pparse('pollbody');

include('./page_footer_admin.' . PHP_EXT);

?>