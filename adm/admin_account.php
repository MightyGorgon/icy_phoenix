<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

define('IN_ICYPHOENIX', true);
if(!empty($setmodules))
{
	$file = basename(__FILE__);
	$module['1610_Users']['170_Account_inactive'] = $file .'?action=inactive';
	$module['1610_Users']['160_Account_active'] = $file .'?action=active';
	return;
}

if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('pagestart.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/functions_users_delete.' . PHP_EXT);

if(!function_exists('period'))
{
	function period($date) // borrowed from birthday mod
	{
		global $lang;

		$years = floor($date / 31536000);
		$date = $date - ($years * 31536000);
		$weeks = floor($date / 604800);
		$date = $date - ($weeks * 604800);
		$days = floor($date / 86400);
		$date = $date - ($days * 86400);
		$hours = floor($date / 3600);

		$result = (($years) ? $years .' '. (($years == '1') ? $lang['Account_year'] : $lang['Account_years']) .', ' : '').
		(($years || $weeks) ? $weeks .' '. (($weeks == '1') ? $lang['Account_week'] : $lang['Account_weeks']) .', ' : '').
		(($years || $weeks || $days) ? $days .' '. (($days == '1') ? $lang['Account_day'] : $lang['Account_days']) .', ' : '') .
		(($years || $weeks || $days || $hours) ? $hours .' '. (($hours == '1') ? $lang['Account_hour'] : $lang['Account_hours']) : '');
		return $result;
	}
}

$submit_wait = (isset($_POST['submit_wait'])) ? true : false;
$confirm = (isset($_POST['confirm'])) ? true : false;
$delete = (isset($_POST['delete'])) ? true : false;
$activate = (isset($_POST['activate'])) ? true : false;
$mark_list = (!empty($_POST['mark'])) ? $_POST['mark'] : 0;

if(check_http_var_exists('letter', false))
{
	$by_letter = request_var('letter', 'all');
}

$action = request_var('action', 'inactive');
$action = check_var_value($action, array('inactive', 'active'));

$mode = request_var('mode', '');

$start = request_var('start', 0);
$start = ($start < 0) ? 0 : $start;

$user_id = request_var(POST_USERS_URL, 0);
$user_id = ($user_id < 2) ? ANONYMOUS : $user_id;

if((($delete && $confirm) || $activate) && $mark_list)
{
	if(sizeof($mark_list))
	{
		$email_id = '';
		for($i = 0; $i < sizeof($mark_list); $i++)
		{
			$email_id .= (($email_id != '') ? ', ' : '') . intval($mark_list[$i]);
		}

		$sql_mail = "SELECT username, user_email, user_lang, user_active FROM " . USERS_TABLE . " WHERE user_id IN ($email_id)";
		$result_mail = $db->sql_query($sql_mail);

		while($mail = $db->sql_fetchrow($result_mail))
		{
			if($delete)
			{
				$subject = $lang['Account_deleted'];
				$text = $lang['Account_deleted_text'];
			}
			elseif($activate)
			{
				$subject = ($mail['user_active'] == '0') ? $lang['Account_activated'] : $lang['Account_deactivated'];
				$text = ($mail['user_active'] == '0') ? $lang['Account_activated_text'] : $lang['Account_deactivated_text'];
			}

			include_once(IP_ROOT_PATH . 'includes/emailer.' . PHP_EXT);
			$emailer = new emailer();
			$emailer->use_template('admin_account_action', stripslashes($mail['user_lang']));
			$emailer->to($mail['user_email']);
			$emailer->set_subject($subject);

			$email_sig = create_signature($config['board_email_sig']);
			$emailer->assign_vars(array(
				'SUBJECT' => $subject,
				'TEXT' => sprintf($text, $config['sitename']),
				'USERNAME' => $mail['username'],
				'EMAIL_SIG' => $email_sig,
				)
			);
			$emailer->send();
			$emailer->reset();
		}
		$db->sql_freeresult($result_mail);
	}
}

if($delete && $mark_list)
{
	if(isset($mark_list) && !is_array($mark_list))
	{
		$mark_list = array();
	}

	if(!$confirm)
	{
		$s_hidden_fields = '<input type="hidden" name="mode" value="' . $mode . '" />';
		$s_hidden_fields .= '<input type="hidden" name="delete" value="true" />';

		for($i = 0; $i < sizeof($mark_list); $i++)
		{
			$s_hidden_fields .= '<input type="hidden" name="mark[]" value="' . intval($mark_list[$i]) . '" />';
		}

		$template->set_filenames(array('confirm_body' => ADM_TPL . 'confirm_body.tpl'));
		$template->assign_vars(array(
			'MESSAGE_TITLE' => $lang['Information'],
			'MESSAGE_TEXT' => (sizeof($mark_list) == '1') ? $lang['Account_delete_user'] : $lang['Account_delete_users'],
			'L_YES' => $lang['Yes'],
			'L_NO' => $lang['No'],
			'S_CONFIRM_ACTION' => append_sid('admin_account.' . PHP_EXT . '?action=' . $action),
			'S_HIDDEN_FIELDS' => $s_hidden_fields,
			)
		);
		$template->pparse('confirm_body');
		include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);
	}
	elseif($confirm)
	{
		if(sizeof($mark_list))
		{
			for($i = 0; $i < sizeof($mark_list); $i++)
			{
				$user_id = (int) $mark_list[$i];
				$killed = ip_user_kill($user_id);
			}

			$template->assign_vars(array('MESSAGE' => ((sizeof($mark_list) == '1') ? $lang['Account_user_deleted'] : $lang['Account_users_deleted']).' '. $lang['Account_notification']));
			$template->assign_block_vars('switch_message', array());
		}
	}
}
elseif($activate && $mark_list)
{
	if(sizeof($mark_list))
	{
		$activate_id = '';
		for ($i = 0; $i < sizeof($mark_list); $i++)
		{
			$activate_id .= (($activate_id != '') ? ', ' : '') . intval($mark_list[$i]);
		}

		$activate_sql = "UPDATE ". USERS_TABLE;
		switch($action)
		{
			case 'inactive':
				$activate_sql .= " SET user_active = '1' WHERE user_active = '0'";
				break;
			case 'active':
				$activate_sql .= " SET user_active = '0' WHERE user_active = '1'";
				$clear_notification = user_clear_notifications($user->data['user_id']);
				break;
		}
		$activate_sql .= " AND user_id IN ($activate_id)";
		$db->sql_query($activate_sql);

		$template->assign_vars(array('MESSAGE' => ((sizeof($mark_list) == '1') ? (($action == 'active') ? $lang['Account_user_deactivated'] : $lang['Account_user_activated']) : (($action == 'active') ? $lang['Account_users_deactivated'] : $lang['Account_users_activated'])) . ' ' . $lang['Account_notification']));
		$template->assign_block_vars('switch_message', array());
	}
}

// Output
$template->set_filenames(array('body' => ADM_TPL . 'admin_account_body.tpl'));

$others_sql = '';
$select_letter = '';
for($i = 97; $i <= 122; $i++)
{
	$others_sql .= " AND username NOT LIKE '" . chr($i) . "%' ";
	$select_letter .= ($by_letter == chr($i)) ? strtoupper(chr($i)) .'&nbsp;' : '<a href="'. append_sid('admin_account.' . PHP_EXT . '?action=' . $action . '&amp;letter=' . chr($i) . '&amp;start=' . $start) .'">'. strtoupper(chr($i)) .'</a>&nbsp;';
}
$select_letter .= ($by_letter == 'others') ? $lang['Account_others'] .'&nbsp;' : '<a href="'. append_sid('admin_account.' . PHP_EXT . '?action=' . $action . '&amp;letter=others&amp;start=' . $start) .'">'. $lang['Account_others'] .'</a>&nbsp;';
$select_letter .= ($by_letter == 'all') ? $lang['Account_all'] : '<a href="'. append_sid('admin_account.' . PHP_EXT . '?action=' . $action . '&amp;letter=all&amp;start=' . $start) .'">'. $lang['Account_all'] .'</a>';

if($by_letter == 'all')
{
	$letter_sql = '';
}
elseif($by_letter == 'others')
{
	$letter_sql = $others_sql;
}
else
{
	$letter_sql = " AND LOWER(username) LIKE '" . $db->sql_escape($by_letter) . "%' ";
}

$sql_count = "SELECT COUNT(user_id) AS total_users
	FROM " . USERS_TABLE . "
	WHERE user_id <> " . ANONYMOUS . " ";

$sql = "SELECT username, user_id, user_active, user_color, user_actkey, user_regdate, user_email, user_first_name, user_last_name
	FROM " . USERS_TABLE . "
	WHERE user_id <> " . ANONYMOUS . " ";

switch($action)
{
	case 'inactive':
		$sql_extra = " AND user_active <> '1' ";
		break;
	case 'active':
		$sql_extra = " AND user_active <> '0' ";
		break;
	default:
		message_die(GENERAL_MESSAGE, $lang['No_mode']);
		break;
}

$sql_count = $sql_count . $sql_extra . $letter_sql;
$sql = $sql . $sql_extra . $letter_sql;

if($submit_wait && (!empty($_POST['days']) || !empty($_GET['days'])))
{
	$days = request_var('days', 0);
	$awaits = time() - ($days * 86400);

	$limit_awaits_count = " AND user_regdate > $awaits";
	$limit_awaits = " AND user_regdate > $awaits ";

	if(!empty($_POST['days']))
	{
		$start = 0;
	}
}
else
{
	$limit_awaits = '';
	$post_days = 0;
}

$sql .= $limit_awaits . " ORDER BY user_regdate DESC LIMIT $start, " . $config['topics_per_page'];
$sql_all = $sql_count;
$sql_count .= $limit_awaits_count;
$result = $db->sql_query($sql_count);
$total_users = ($row = $db->sql_fetchrow($result)) ? $row['total_users'] : 0;
$result = $db->sql_query($sql_all);
$all_total_users = ($row = $db->sql_fetchrow($result)) ? $row['total_users'] : 0;

$previous_days = array(0, 1, 7, 14, 30, 90, 180, 364);
$previous_days_text = array($lang['Account_all'], $lang['1_DAY'], $lang['7_DAYS'], $lang['2_WEEKS'], $lang['1_MONTH'], $lang['3_MONTHS'], $lang['6_MONTHS'], $lang['1_YEAR']);

$select_days = '';
for($i = 0; $i < sizeof($previous_days); $i++)
{
	$selected = ($days == $previous_days[$i]) ? ' selected="selected"' : '';
	$select_days .= '<option value="' . $previous_days[$i] . '"' . $selected . '>' . $previous_days_text[$i] . '</option>';
}

$l_activation = $lang['Account_activation'] . ': <b>' . (($config['require_activation'] == USER_ACTIVATION_SELF) ? $lang['Acc_User'] : (($config['require_activation'] == USER_ACTIVATION_ADMIN) ? $lang['Acc_Admin'] : $lang['None'])) . '</b>';

$template->assign_vars(array(
	'L_ACCOUNT_ACTIONS' => $lang['Account_actions'],
	'L_ACCOUNT_ACTIONS_EXPLAIN' => ($action == 'inactive') ? $lang['Account_inactive_explain'] : $lang['Account_active_explain'],
	'L_MARK' => $lang['Mark'],
	'L_DELETE_MARKED' => $lang['Delete_marked'],
	'L_DE_ACTIVATE_MARKED' => ($action == 'inactive') ? $lang['Account_activate'] : $lang['Account_deactivate'],
	'L_EDIT_USER' => $lang['Edit'],
	'L_USER_AUTH' => $lang['Permissions'],
	'L_SORT_PER_LETTER' => $lang['Account_sort_letter'],
	'L_GO' => $lang['Go'],
	'L_USERNAME' => $lang['Username'],
	'L_EMAIL' => $lang['Email'],
	'L_JOINED' => $lang['Joined'],
	'L_REGISTERED_AWAITS' => ($action == 'inactive') ? $lang['Account_awaits'] : $lang['Account_registered'],
	'L_ACTIVATION' => $l_activation,
	'L_POSTS_ANDOR_PICS' => empty($config['plugins']['album']['enabled']) ? $lang['Posts'] : $lang['POSTS_PICS'],
	'TOTAL_USERS' => ($total_users == '1') ? sprintf($lang['Account_total_user'], $total_users) : sprintf($lang['Account_total_users'], $total_users),
	'PAGINATION' => ($total_users == '0') ? '' : generate_pagination('admin_account.' . PHP_EXT . '?action=' . $action . '&amp;letter=' . $by_letter, $total_users, $config['topics_per_page'], $start),
	'PAGE_NUMBER' => ($total_users == '0') ? '' : sprintf($lang['Page_of'], (floor($start / $config['topics_per_page']) + 1), ceil($total_users / $config['topics_per_page'])),
	'S_LETTER_SELECT' => $select_letter,
	'S_LETTER_HIDDEN' => '<input type="hidden" name="letter" value="' . $by_letter . '" />',
	'S_ACCOUNT_ACTION' => append_sid('admin_account.' . PHP_EXT . '?action=' . $action),
	'S_HIDDEN_FIELDS' => '',
	'S_SELECT_DAYS' => $select_days,
	)
);

$result = $db->sql_query($sql);

if($row = $db->sql_fetchrow($result))
{
	$i = 0;
	do
	{
		$user_id = $row['user_id'];

		$sql_posts_count = "SELECT COUNT(post_id) AS total_posts
			FROM " . POSTS_TABLE . "
			WHERE poster_id = " . $user_id;
		$result_posts = $db->sql_query($sql_posts_count);
		$row_posts = $db->sql_fetchrow($result_posts);
		$total_posts = $row_posts['total_posts'];
		$db->sql_freeresult($result_posts);

		$email_url = ($config['board_email_form']) ? append_sid('../' . CMS_PAGE_PROFILE . '?mode=email&amp;' . POST_USERS_URL . '=' . $user_id) : 'mailto:' . $row['user_email'];
		$email = '<a href="' . $email_url . '" class="gensmall">' . $row['user_email'] . '</a>';

		if (!empty($config['plugins']['album']['enabled']))
		{
			$sql_pics_count = "SELECT COUNT(pic_id) AS total_pics
				FROM " . ALBUM_TABLE . "
				WHERE pic_user_id = " . $user_id;
			$result_pics = $db->sql_query($sql_pics_count);
			$row_pics = $db->sql_fetchrow($result_pics);
			$total_pics = $row_pics['total_pics'];
			$db->sql_freeresult($result_pics);
		}

		$i++;
		$user_full_name = (!empty($row['user_first_name']) ? $row['user_first_name'] : '') . (!empty($row['user_last_name']) ? ((!empty($row['user_first_name']) ? ' ' : '')) . $row['user_last_name'] : '');
		$template->assign_block_vars('admin_account', array(
			'ROW_NUMBER' => ($i == '1') ? '1' : ($i + intval($_GET['start'])),
			'ROW_CLASS' => (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'],
			'USERNAME' => colorize_username($row['user_id'], $row['username'], $row['user_color'], $row['user_active']),
			'USER_FIRST_NAME' => htmlspecialchars($row['user_first_name']),
			'USER_LAST_NAME' => htmlspecialchars($row['user_last_name']),
			'USER_FULL_NAME' => $user_full_name,
			'EMAIL' => $email,
			'POSTS' => $total_posts,
			'JOINED' => create_date($config['default_dateformat'], $row['user_regdate'], $config['board_timezone']),
			'PERIOD' => period(time() - $row['user_regdate']),
			'U_EDIT_USER' => append_sid('admin_users.' . PHP_EXT . '?mode=edit&amp;' . POST_USERS_URL . '=' . $user_id),
			'U_USER_AUTH' => append_sid('admin_ug_auth.' . PHP_EXT . '?mode=user&amp;' . POST_USERS_URL . '=' . $user_id),
			'S_MARK_ID' => $user_id,
			'PICS' => !empty($total_pics) ? $total_pics : null,
			)
		);
	}
	while($row = $db->sql_fetchrow($result));
}
else
{
	$template->assign_vars(array('L_NO_USERS' => $lang['Account_none']));
	$template->assign_block_vars('switch_no_users', array());
}
$db->sql_freeresult($result);

$template->pparse('body');
include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);

?>