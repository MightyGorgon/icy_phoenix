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
* (c) 2002 Meik Sievertsen (Acyd Burn)
*
*/

if ( !defined('IN_PHPBB') )
{
	die('Hacking attempt');
	exit;
}

/**
* Called from usercp_viewprofile, displays the User Upload Quota Box, Upload Stats and a Link to the User Attachment Control Panel
* Groups are able to be grabbed, but it's not used within the Attachment Mod. ;)
* (includes/usercp_viewprofile.php)
*/
function display_upload_attach_box_limits($user_id, $group_id = 0)
{
	global $attach_config, $board_config, $phpbb_root_path, $lang, $db, $template, $phpEx, $userdata, $profiledata, $images;

	if (intval($attach_config['disable_mod']))
	{
		return;
	}

	if (($userdata['user_level'] != ADMIN) && ($userdata['user_id'] != $user_id))
	{
		return;
	}

	if (!$user_id)
	{
		return;
	}

	// Return if the user is not within the to be listed Group
	if ($group_id)
	{
		if (!user_in_group($user_id, $group_id))
		{
			return;
		}
	}

	$user_id = (int) $user_id;
	$group_id = (int) $group_id;

	$attachments = new attach_posting();
	$attachments->page = PAGE_INDEX;

	// Get the assigned Quota Limit. For Groups, we are directly getting the value, because this Quota can change from user to user.
	if ($group_id)
	{
		$sql = 'SELECT l.quota_limit
			FROM ' . QUOTA_TABLE . ' q, ' . QUOTA_LIMITS_TABLE . ' l
			WHERE q.group_id = ' . (int) $group_id . '
				AND q.quota_type = ' . QUOTA_UPLOAD_LIMIT . '
				AND q.quota_limit_id = l.quota_limit_id
			LIMIT 1';

		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not get Group Quota', '', __LINE__, __FILE__, $sql);
		}

		if ($db->sql_numrows($result) > 0)
		{
			$row = $db->sql_fetchrow($result);
			$attach_config['upload_filesize_limit'] = intval($row['quota_limit']);
			$db->sql_freeresult($result);
		}
		else
		{
			$db->sql_freeresult($result);

			// Set Default Quota Limit
			$quota_id = intval($attach_config['default_upload_quota']);

			if ($quota_id == 0)
			{
				$attach_config['upload_filesize_limit'] = $attach_config['attachment_quota'];
			}
			else
			{
				$sql = 'SELECT quota_limit
					FROM ' . QUOTA_LIMITS_TABLE . '
					WHERE quota_limit_id = ' . (int) $quota_id . '
					LIMIT 1';

				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'Could not get Quota Limit', '', __LINE__, __FILE__, $sql);
				}

				if ($db->sql_numrows($result) > 0)
				{
					$row = $db->sql_fetchrow($result);
					$attach_config['upload_filesize_limit'] = $row['quota_limit'];
				}
				else
				{
					$attach_config['upload_filesize_limit'] = $attach_config['attachment_quota'];
				}
				$db->sql_freeresult($result);
			}
		}
	}
	else
	{
		if (is_array($profiledata))
		{
			$attachments->get_quota_limits($profiledata, $user_id);
		}
		else
		{
			$attachments->get_quota_limits($userdata, $user_id);
		}
	}

	if (!$attach_config['upload_filesize_limit'])
	{
		$upload_filesize_limit = $attach_config['attachment_quota'];
	}
	else
	{
		$upload_filesize_limit = $attach_config['upload_filesize_limit'];
	}

	if ($upload_filesize_limit == 0)
	{
		$user_quota = $lang['Unlimited'];
	}
	else
	{
		$size_lang = ($upload_filesize_limit >= 1048576) ? $lang['MB'] : ( ($upload_filesize_limit >= 1024) ? $lang['KB'] : $lang['Bytes'] );

		if ($upload_filesize_limit >= 1048576)
		{
			$user_quota = (round($upload_filesize_limit / 1048576 * 100) / 100) . ' ' . $size_lang;
		}
		elseif ($upload_filesize_limit >= 1024)
		{
			$user_quota = (round($upload_filesize_limit / 1024 * 100) / 100) . ' ' . $size_lang;
		}
		else
		{
			$user_quota = ($upload_filesize_limit) . ' ' . $size_lang;
		}
	}

	// Get all attach_id's the specific user posted, but only uploads to the board and not Private Messages
	$sql = 'SELECT attach_id
		FROM ' . ATTACHMENTS_TABLE . '
		WHERE user_id_1 = ' . (int) $user_id . '
			AND privmsgs_id = 0
		GROUP BY attach_id';

	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Couldn\'t query attachments', '', __LINE__, __FILE__, $sql);
	}

	$attach_ids = $db->sql_fetchrowset($result);
	$num_attach_ids = $db->sql_numrows($result);
	$db->sql_freeresult($result);
	$attach_id = array();

	for ($j = 0; $j < $num_attach_ids; $j++)
	{
		$attach_id[] = intval($attach_ids[$j]['attach_id']);
	}

	$upload_filesize = (count($attach_id) > 0) ? get_total_attach_filesize($attach_id) : 0;

	$size_lang = ($upload_filesize >= 1048576) ? $lang['MB'] : ( ($upload_filesize >= 1024) ? $lang['KB'] : $lang['Bytes'] );

	if ($upload_filesize >= 1048576)
	{
		$user_uploaded = (round($upload_filesize / 1048576 * 100) / 100) . ' ' . $size_lang;
	}
	elseif ($upload_filesize >= 1024)
	{
		$user_uploaded = (round($upload_filesize / 1024 * 100) / 100) . ' ' . $size_lang;
	}
	else
	{
		$user_uploaded = ($upload_filesize) . ' ' . $size_lang;
	}

	$upload_limit_pct = ( $upload_filesize_limit > 0 ) ? round(( $upload_filesize / $upload_filesize_limit ) * 100) : 0;
	$upload_limit_img_length = ( $upload_filesize_limit > 0 ) ? round(( $upload_filesize / $upload_filesize_limit ) * $board_config['privmsg_graphic_length']) : 0;
	if ($upload_limit_pct > 100)
	{
		$upload_limit_img_length = $board_config['privmsg_graphic_length'];
	}
	$upload_limit_remain = ( $upload_filesize_limit > 0 ) ? $upload_filesize_limit - $upload_filesize : 100;
	if ( $upload_limit_pct <= 30 )
	{
		$bar_colour = 'green';
	}
	elseif ( ($upload_limit_pct > 30) && ($upload_limit_pct <= 70) )
	{
		$bar_colour = 'blue';
	}
	elseif ( $upload_limit_pct > 70 )
	{
		$bar_colour = 'red';
	}

	$vote_color = $bar_colour;
	$voting_bar = 'voting_graphic_' . $vote_color;
	$voting_bar_body = 'voting_graphic_' . $vote_color . '_body';
	$voting_bar_left = 'voting_graphic_' . $vote_color . '_left';
	$voting_bar_right = 'voting_graphic_' . $vote_color . '_right';

	$voting_bar_img = $images[$voting_bar];
	$voting_bar_body_img = $images[$voting_bar_body];
	$voting_bar_left_img = $images[$voting_bar_left];
	$voting_bar_right_img = $images[$voting_bar_right];

	$l_box_size_status = sprintf($lang['Upload_percent_profile'], $upload_limit_pct);

	$template->assign_block_vars('switch_upload_limits', array());

	$template->assign_vars(array(
		'L_UACP' => $lang['UACP'],
		'L_UPLOAD_QUOTA' => $lang['Upload_quota'],
		'U_UACP' => $phpbb_root_path . 'uacp.' . $phpEx . '?u=' . $user_id . '&amp;sid=' . $userdata['session_id'],
		'UPLOADED' => sprintf($lang['User_uploaded_profile'], $user_uploaded),
		'QUOTA' => sprintf($lang['User_quota_profile'], $user_quota),
		'UPLOAD_LIMIT_IMG_WIDTH' => $upload_limit_img_length,
		'UPLOAD_LIMIT_PERCENT' => $upload_limit_pct,
		'BAR_GRAPHIC' => $voting_bar_img,
		'BAR_GRAPHIC_BODY' => $voting_bar_body_img,
		'BAR_GRAPHIC_LEFT' => $voting_bar_left_img,
		'BAR_GRAPHIC_RIGHT' => $voting_bar_right_img,
		'BAR_COLOR' => $bar_colour,
		'PERCENT_FULL' => $l_box_size_status
		)
	);
}

?>