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
* NetizenKane (info@fragthe.net)
* KugeLSichA (http://www.caromonline.de)
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

if (!empty($forum_topic_data['topic_reg']) && (check_reg_active($topic_id) === true))
{
	$sql = "SELECT u.username, u.user_id, u.user_active, u.user_color, r.registration_time, r.registration_status
		FROM " . REGISTRATION_TABLE . " r, " . USERS_TABLE . " u
		WHERE r.topic_id = " . $topic_id . "
			AND r.registration_user_id = u.user_id
		ORDER BY r.registration_status, r.registration_time";
	$result = $db->sql_query($sql);
	$reg_info = $db->sql_fetchrowset($result);
	$db->sql_freeresult($result);

	$numregs = sizeof($reg_info);
	$option1_count = 0;
	$option2_count = 0;
	$option3_count = 0;
	$option1_list = array();
	$option2_list = array();
	$option3_list = array();
	$s_hidden_fields = '';
	$s_hidden_fields = '<input type="hidden" name="topic_id" value="' . $topic_id . '" /><input type="hidden" name="mode" value="vote" />';
	$s_hidden_fields = '<input type="hidden" name="sid" value="' . $user->data['session_id'] . '" />';

	$template->set_filenames(array('regbox' => 'viewtopic_events_reg.tpl'));

	$sql = "SELECT topic_id, reg_max_option1, reg_max_option2, reg_max_option3, reg_start, reg_length
			FROM " . REGISTRATION_DESC_TABLE . "
			WHERE topic_id = " . $topic_id;
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);

	$reg_start = $row['reg_start'];
	$reg_length = $row['reg_length'];

	$reg_max_option1 = $row['topic_reg_max_option1'];
	$reg_max_option2 = $row['topic_reg_max_option2'];
	$reg_max_option3 = $row['topic_reg_max_option3'];

	$self_registered = 0;

	for($u = 0; $u < $numregs; $u++)
	{
		if ($reg_info[$u]['user_id'] == $user->data['user_id'])
		{
			$self_registered = $reg_info[$u]['registration_status'];
		}
		$current_user = colorize_username($reg_info[$u]['user_id'], $reg_info[$u]['username'], $reg_info[$u]['user_color'], $reg_info[$u]['user_active']);
		$user_registered = create_date_ip($config['default_dateformat'], $reg_info[$u]['registration_time'], $config['board_timezone']);
		$reg_option_data = '<tr><td valign="top"><span class="gensmall">' . $current_user . '</span></td><td class="gensmall">' . $user_registered . '</td></tr>';
		if ($reg_info[$u]['registration_status'] == REG_OPTION1)
		{
			$option1_count++;
			$template->assign_block_vars('reg_option1_users', array(
				'USER' => $current_user,
				'DATE' => $user_registered,
				)
			);
			$reg_option1_data .= $reg_option_data;
		}
		elseif ($reg_info[$u]['registration_status'] == REG_OPTION2)
		{
			$option2_count++;
			$template->assign_block_vars('reg_option2_users', array(
				'USER' => $current_user,
				'DATE' => $user_registered,
				)
			);
			$reg_option2_data .= $reg_option_data;
		}
		elseif ($reg_info[$u]['registration_status'] == REG_OPTION3)
		{
			$option3_count++;
			$template->assign_block_vars('reg_option3_users', array(
				'USER' => $current_user,
				'DATE' => $user_registered,
				)
			);
			$reg_option3_data .= $reg_option_data;
		}
	}

	$reg_option1_option = $lang['Reg_Do'];
	$template->assign_block_vars('reg_option1', array(
		'REG_OPTION1_DATA' => $reg_option1_data
		)
	);

	$reg_option2_option = $lang['Reg_Maybe'];
	$template->assign_block_vars('reg_option2', array(
		'REG_OPTION2_DATA' => $reg_option2_data
		)
	);

	$reg_option3_option = $lang['Reg_Dont'];
	$template->assign_block_vars('reg_option3', array(
		'REG_OPTION3_DATA' => $reg_option3_data
		)
	);

	if ($self_registered != 0)
	{
		$template->assign_block_vars('reg_unregister', array(
			'REG_SELF_NAME' => $lang['Reg_Self_Unregister'],
			'REG_SELF_URL' => append_sid(CMS_PAGE_POSTING . '?mode=register&amp;register=' . REG_UNREGISTER . '&amp;' . POST_TOPIC_URL . '=' . $topic_id)
			)
		);
	}

	$reg_expired = ($reg_length) ? ((($reg_start + $reg_length) < time()) ? 1 : 0) : 0;
	if ($forum_topic_data['topic_status'] == TOPIC_LOCKED)
	{
		$reg_expired = 0;
	}

	$readonly_option1 = '';
	$readonly_option2 = '';
	$readonly_option3 = '';
	if (($self_registered == 1) || ($reg_expired === 1) || ((check_max_registration($topic_id, 1) === false) && (check_user_registered($topic_id, $user->data['user_id'], 1) === false)))
	{
		$readonly_option1 = 'disabled="disabled"';
	}
	if (($self_registered == 2) || ($reg_expired === 1) || ((check_max_registration($topic_id, 2) === false) && (check_user_registered($topic_id, $user->data['user_id'], 2) === false)))
	{
		$readonly_option2 = 'disabled="disabled"';
	}
	if (($self_registered == 3) || ($reg_expired === 1) || ((check_max_registration($topic_id, 3) === false) && (check_user_registered($topic_id, $user->data['user_id'], 3) === false)))
	{
		$readonly_option3 = 'disabled="disabled"';
	}

	$slots_left_option1 = check_slots_left($topic_id, 1);
	$slots_left_option2 = check_slots_left($topic_id, 2);
	$slots_left_option3 = check_slots_left($topic_id, 3);

	switch ($slots_left_option1)
	{
		case 0:
			$slots_left_option1_msg = $lang['Reg_No_Slots_Left'];
		break;
		case 1:
			$slots_left_option1_msg = $lang['Reg_One_Slot_Left'];
		break;
		default:
			$slots_left_option1_msg = $lang['Reg_Slots_Left'];
		break;
	}

	switch ($slots_left_option2)
	{
		case 0:
			$slots_left_option2_msg = $lang['Reg_No_Slots_Left'];
		break;
		case 1:
			$slots_left_option2_msg = $lang['Reg_One_Slot_Left'];
		break;
		default:
			$slots_left_option2_msg = $lang['Reg_Slots_Left'];
		break;
	}

	switch ($slots_left_option3)
	{
		case 0:
			$slots_left_option3_msg = $lang['Reg_No_Slots_Left'];
		break;
		case 1:
			$slots_left_option3_msg = $lang['Reg_One_Slot_Left'];
		break;
		default:
			$slots_left_option3_msg = $lang['Reg_Slots_Left'];
		break;
	}
	$slots_left_option1 = ($slots_left_option1 > -1) ? sprintf($slots_left_option1_msg, $slots_left_option1) : '';
	$slots_left_option2 = ($slots_left_option2 > -1) ? sprintf($slots_left_option2_msg, $slots_left_option2) : '';
	$slots_left_option3 = ($slots_left_option3 > -1) ? sprintf($slots_left_option3_msg, $slots_left_option3) : '';

	$template->assign_vars(array(
		'REG_TITLE' => $lang['Reg_Title'],
		'REG_HEAD_USERNAME' => $lang['Reg_Head_Username'],
		'REG_HEAD_TIME' => $lang['Reg_Head_Time'],
		'REG_OPTION1_NAME' => $reg_option1_option,
		'REG_OPTION1_COUNT' => $option1_count,
		'REG_OPTION1_URL' => append_sid(CMS_PAGE_POSTING . '?mode=register&amp;register=' . REG_OPTION1 . '&amp;' . POST_TOPIC_URL . '=' . $topic_id),
		'REG_OPTION2_NAME' => $reg_option2_option,
		'REG_OPTION2_COUNT' => $option2_count,
		'REG_OPTION2_URL' => append_sid(CMS_PAGE_POSTING . '?mode=register&amp;register=' . REG_OPTION2 . '&amp;' . POST_TOPIC_URL . '=' . $topic_id),
		'REG_OPTION3_NAME' => $reg_option3_option,
		'REG_OPTION3_COUNT' => $option3_count,
		'REG_OPTION3_URL' => append_sid(CMS_PAGE_POSTING . '?mode=register&amp;register=' . REG_OPTION3 . '&amp;' . POST_TOPIC_URL . '=' . $topic_id),

		'REG_OPTION1_READONLY' => $readonly_option1,
		'REG_OPTION2_READONLY' => $readonly_option2,
		'REG_OPTION3_READONLY' => $readonly_option3,
		'REG_OPTION1_SLOTS' => $slots_left_option1,
		'REG_OPTION2_SLOTS' => $slots_left_option2,
		'REG_OPTION3_SLOTS' => $slots_left_option3
		)
	);

	$template->assign_var_from_handle('REG_DISPLAY', 'regbox');
}

?>
