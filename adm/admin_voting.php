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

if (!defined('IN_ICYPHOENIX')) define('IN_ICYPHOENIX', true);

if(!empty($setmodules))
{
	$filename = basename(__FILE__);
	$module['1610_Users']['290_Poll_Results'] = $filename;
	return;
}

if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('pagestart.' . PHP_EXT);

if (!class_exists('bbcode') || empty($bbcode))
{
	@include_once(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);
}
$bbcode->allow_html = ($config['allow_html'] ? true : false);
$bbcode->allow_bbcode = ($config['allow_bbcode'] ? true : false);
$bbcode->allow_smilies = ($config['allow_smilies'] ? true : false);

$start = request_var('start', 0);
$start = ($start < 0) ? 0 : $start;

$sort_field = request_var('field', 'vote_id');

$sort_order = request_var('order', 'ASC');
$sort_order = check_var_value($sort_order, array('DESC', 'ASC'));

// Assign sort fields
$sort_fields_text = array(
	$lang['Sort_poll_topic'],
	$lang['Sort_poll_title'],
	$lang['Sort_poll_start'],
);

$sort_fields = array(
	'poll_topic',
	'poll_title',
	'poll_start',
);

if (empty($sort_field))
{
	$sort_field = 'poll_topic';
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
	case 'poll_topic':
		$order_by = 'topic_id ' . $sort_order;
		break;
	case 'poll_title':
		$order_by = 'poll_title ' . $sort_order;
		break;
	case 'poll_start':
		$order_by = 'poll_start ' . $sort_order;
		break;
	default:
		$sort_field = 'topic_id';
		$sort_order = 'ASC';
		$order_by = 'topic_id ' . $sort_order;
		break;
}
$order_by = $order_by . ' LIMIT ' . $start . ", " . $config['topics_per_page'];

$template->set_filenames(array('pollbody' => ADM_TPL . 'admin_voting_body.tpl'));

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

	'ADMIN_VOTING_ICON' => '<img src="' . IP_ROOT_PATH . 'templates/common/images/admin_voting_icon.gif" alt="' . $lang['Admin_Vote_Title'] . '" />',
	)
);

// Assign Username array
$sql = "SELECT DISTINCT u.user_id, u.username, u.user_active, u.user_color
		FROM " . USERS_TABLE . " AS u , " . POLL_VOTES_TABLE . " AS v
		WHERE u.user_id = v.vote_user_id";
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
$db->sql_freeresult($result);

// Assign poll options array
$sql = "SELECT *
		FROM " . POLL_OPTIONS_TABLE . "
		ORDER BY topic_id ASC";
$result = $db->sql_query($sql);

while ($row = $db->sql_fetchrow($result))
{
	$option_arr[$row['topic_id']][$row['poll_option_id']]['poll_option_text'] = $row['poll_option_text'];
	$option_arr[$row['topic_id']][$row['poll_option_id']]['poll_option_total'] = $row['poll_option_total'];
}
$db->sql_freeresult($result);

// Assign individual vote results
$sql = "SELECT *
		FROM " . POLL_VOTES_TABLE . "
		ORDER BY topic_id ASC, poll_option_id ASC, vote_user_id ASC";
$result = $db->sql_query($sql);

while ($row = $db->sql_fetchrow($result))
{
	$voter_arr[$row['topic_id']][$row['vote_user_id']][$row['poll_option_id']] = $row['poll_option_id'];
}
$db->sql_freeresult($result);

$sql ="SELECT * FROM " . TOPICS_TABLE . "
		WHERE poll_title <> ''
		ORDER BY " . $order_by;
$result = $db->sql_query($sql);

$i = 0;
while ($row = $db->sql_fetchrow($result))
{
	$topic_row_color = (($i % 2) == 0) ? 'row1' : 'row2';
	$topic_id = $row['topic_id'];
	$vote_text = $row['poll_title'];
	$vote_start = $row['poll_start'];
	$vote_length = $row['poll_length'];
	$vote_end = $vote_start + $vote_length;

	if (time() < $vote_end)
	{
		$vote_duration = (date('Y/m/d', $vote_start)) . ' - ' . (date('Y/m/d', $vote_end)) . $lang['POLL_ONGOING'];
	}
	elseif ($vote_length == 0)
	{
		$vote_duration = (date('Y/m/d', $vote_start)) . ' - ' . $lang['POLL_INFINITE'];
	}
	else
	{
		$vote_duration = (date('Y/m/d', $vote_start)) . ' - ' . (date('Y/m/d', $vote_end)) . $lang['POLL_COMPLETED'];
	}

	$target_user = '';
	$target_user_arr = array();
	$user_option = '';
	$user_option_arr = array();
	$users_added = array();

	if (!empty($voter_arr[$topic_id]))
	{
		ksort($voter_arr[$topic_id]);
		foreach($voter_arr[$topic_id] as $user_id => $option_id)
		{
			$current_username = colorize_username($users_arr[$user_id]['user_id'], $users_arr[$user_id]['username'], $users_arr[$user_id]['user_color'], $users_arr[$user_id]['user_active']);
			$target_user_arr[] = $current_username;
			foreach ($option_id as $result_id)
			{
				$user_option_arr[$result_id][] = $current_username;
				/*
				if (!in_array($user_id, $users_added))
				{
					$user_option_arr[$result_id][] = $current_username;
					$users_added[] = $user_id;
				}
				*/
			}
		}
		if (!empty($target_user_arr))
		{
			$target_user = implode(', ', $target_user_arr);
		}
	}


	$template->assign_block_vars('votes', array(
		'COLOR' => $topic_row_color,
		'LINK' => IP_ROOT_PATH . CMS_PAGE_VIEWTOPIC . '?' . POST_TOPIC_URL . '=' . $topic_id,
		'DESCRIPTION' => $bbcode->parse($vote_text),
		'USER' => $target_user,
		'ENDDATE' => $vote_end,
		'VOTE_DURATION' => $vote_duration,
		'VOTE_ID' => $topic_id
		)
	);

	if (!empty($option_arr[$topic_id]))
	{
		foreach($option_arr[$topic_id] as $vote_option_id => $elem)
		{
			$target_user_option = '';
			$option_text = $elem['poll_option_text'];
			$option_result = $elem['poll_option_total'];
			if (!empty($user_option_arr[$vote_option_id]))
			{
				$target_user_option = implode(', ', $user_option_arr[$vote_option_id]);
			}

			$template->assign_block_vars('votes.detail', array(
				'OPTION' => $bbcode->parse($option_text),
				'RESULT' => $option_result,
				'USER' => $target_user_option
				)
			);
		}
	}

	$i++;
}
$db->sql_freeresult($result);

// Get all polls
$sql ="SELECT topic_id FROM " . TOPICS_TABLE . "
		WHERE poll_title <> ''";
$result = $db->sql_query($sql);
$num_polls = $db->sql_numrows($result);

// Pagination routine
if ($num_polls > 0)
{
	$pagination = generate_pagination('admin_voting.' . PHP_EXT . '?mode=' . $sort_field . '&amp;order=' . $sort_order, $num_polls, $config['topics_per_page'], $start) . '&nbsp;';
}

$template->assign_vars(array(
	'PAGINATION' => $pagination,
	'PAGE_NUMBER' => sprintf($lang['Page_of'], (floor($start / $config['topics_per_page']) + 1), ceil($num_polls / $config['topics_per_page'])),

	'L_GOTO_PAGE' => $lang['Goto_page']
	)
);

$template->pparse('pollbody');

include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);

?>