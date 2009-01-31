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
	exit;
}

/*
* check permissions and redirect if missing
*/
$stats_view = $dl_mod->stats_perm();
if (!$stats_view)
{
	redirect(append_sid('downloads.' . PHP_EXT));
}

if (count($index))
{
	$access_cats = array();
	$access_cats = $dl_mod->full_index(0, 0, 0, 1);
	if (count($access_cats))
	{
		/*
		* enable/disable guest data on basic statistics
		*/
		$sql_where = ($dl_config['guest_stats_show'] == 1) ? '' : ' AND u.user_id <> ' . ANONYMOUS;

		/*
		* latest downloads
		*/
		$sql = "SELECT d.*, u.username, u.user_active, u.user_color, c.cat_name
			FROM " . DOWNLOADS_TABLE . " d, " . DL_CAT_TABLE . " c, " . USERS_TABLE . " u
			WHERE d.cat = c.id
				AND d.down_user = u.user_id
				AND c.id IN (" . implode(',', $access_cats) . ")
				$sql_where
			ORDER BY d.last_time DESC
			LIMIT 10";
		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not query latest ten downloads', '', __LINE__, __FILE__, $sql);
		}

		$total_top_ten = $db->sql_numrows($result);
		if ($total_top_ten > 0)
		{
			$i = 0;
			$dl_pos = 1;
			while ($row = $db->sql_fetchrow($result))
			{
				$file_id = $row['id'];
				$cat_id = $row['cat'];
				$file_name_name = $row['file_name'];
				$description = $row['description'];
				$cat_name = $row['cat_name'];

				$dl_time = $row['last_time'];
				$dl_time = create_date($board_config['default_dateformat'], $dl_time, $board_config['board_timezone']);

				$file_link = append_sid('downloads.' . PHP_EXT . '?view=detail&amp;df_id=' . $file_id);

				$user_link = ($row['down_user'] == ANONYMOUS) ? $lang['Guest'] : colorize_username($row['down_user'], $row['username'], $row['user_color'], $row['user_active']);
				$user_name = ($row['down_user'] == ANONYMOUS) ? $lang['Guest'] : $row['username'];

				$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];

				$template->assign_block_vars('top_ten_latest', array(
					'POS' => $dl_pos,
					'DESCRIPTION' => $description,
					'U_FILE_LINK' => $file_link,
					'CAT_NAME' => $cat_name,
					'USER_NAME' => $user_name,
					'U_USER_LINK' => $user_link,
					'DL_TIME' => $dl_time,
					'ROW_CLASS' => $row_class
					)
				);

				$i++;
				$dl_pos++;
			}
			$db->sql_freeresult($result);
		}

		/*
		* lastest uploads
		*/
		$sql = "SELECT d.*, u.username, u.user_active, u.user_color, c.cat_name
			FROM " . DOWNLOADS_TABLE . " d, " . DL_CAT_TABLE . " c, " . USERS_TABLE . " u
			WHERE d.cat = c.id
				AND d.add_user = u.user_id
				AND approve = " . TRUE . "
				AND c.id IN (" . implode(',', $access_cats) . ")
			ORDER BY d.add_time DESC
			LIMIT 10";
		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not query latest ten downloads', '', __LINE__, __FILE__, $sql);
		}

		$total_top_ten = $db->sql_numrows($result);
		if ($total_top_ten > 0)
		{
			$i = 0;
			$dl_pos = 1;
			while ($row = $db->sql_fetchrow($result))
			{
				$file_id = $row['id'];
				$cat_id = $row['cat'];
				$file_name_name = $row['file_name'];
				$description = $row['description'];
				$cat_name = $row['cat_name'];

				$dl_time = $row['add_time'];
				$dl_time = create_date($board_config['default_dateformat'], $dl_time, $board_config['board_timezone']);

				$file_link = append_sid('downloads.' . PHP_EXT . '?view=detail&amp;df_id=' . $file_id);

				$user_link = ($row['add_user'] == ANONYMOUS) ? $lang['Guest'] : colorize_username($row['add_user'], $row['username'], $row['user_color'], $row['user_active']);
				$user_name = ($row['add_user'] == ANONYMOUS) ? $lang['Guest'] : $row['username'];

				$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];

				$template->assign_block_vars('top_ten_uploads', array(
					'POS' => $dl_pos,
					'DESCTIPTION' => $description,
					'U_FILE_LINK' => $file_link,
					'CAT_NAME' => $cat_name,
					'USER_NAME' => $user_name,
					'U_USER_LINK' => $user_link,
					'DL_TIME' => $dl_time,
					'ROW_CLASS' => $row_class
					)
				);

				$i++;
				$dl_pos++;
			}
			$db->sql_freeresult($result);
		}

		/*
		* top ten downloads this month
		*/
		$sql = "SELECT d.*, c.cat_name
			FROM " . DOWNLOADS_TABLE . " d, " . DL_CAT_TABLE . " c
			WHERE d.cat = c.id
				AND c.id IN (" . implode(',', $access_cats) . ")
			ORDER BY d.klicks DESC
			LIMIT 10";
		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not query latest ten downloads', '', __LINE__, __FILE__, $sql);
		}

		$total_top_ten = $db->sql_numrows($result);
		if ($total_top_ten > 0)
		{
			$i = 0;
			$dl_pos = 1;
			while ($row = $db->sql_fetchrow($result))
			{
				$file_id = $row['id'];
				$cat_id = $row['cat'];
				$file_name_name = $row['file_name'];
				$description = $row['description'];
				$cat_name = $row['cat_name'];

				$dl_klicks = $row['klicks'];

				$file_link = append_sid('downloads.' . PHP_EXT . '?view=detail&amp;df_id=' . $file_id);

				$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

				$template->assign_block_vars('top_ten_dl_cur_month', array(
					'POS' => $dl_pos,
					'DESCRIPTION' => $description,
					'U_FILE_LINK' => $file_link,
					'CAT_NAME' => $cat_name,
					'DL_KLICKS' => $dl_klicks,
					'ROW_CLASS' => $row_class
					)
				);

				$i++;
				$dl_pos++;
			}
			$db->sql_freeresult($result);
		}

		/*
		* top ten downloads overall
		*/
		$sql = "SELECT d.*, c.cat_name
			FROM " . DOWNLOADS_TABLE . " d, " . DL_CAT_TABLE . " c
			WHERE d.cat = c.id
				AND c.id IN (" . implode(',', $access_cats) . ")
			ORDER BY d.overall_klicks DESC
			LIMIT 10";
		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not query latest ten downloads', '', __LINE__, __FILE__, $sql);
		}

		$total_top_ten = $db->sql_numrows($result);
		if ($total_top_ten > 0)
		{
			$i = 0;
			$dl_pos = 1;
			while ($row = $db->sql_fetchrow($result))
			{
				$file_id = $row['id'];
				$cat_id = $row['cat'];
				$file_name_name = $row['file_name'];
				$description = $row['description'];
				$cat_name = $row['cat_name'];

				$dl_klicks = $row['overall_klicks'];

				$file_link = append_sid('downloads.' . PHP_EXT . '?view=detail&amp;df_id=' . $file_id);

				$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];

				$template->assign_block_vars('top_ten_dl_overall', array(
					'POS' => $dl_pos,
					'DESCRIPTION' => $description,
					'U_FILE_LINK' => $file_link,
					'CAT_NAME' => $cat_name,
					'DL_KLICKS' => $dl_klicks,
					'ROW_CLASS' => $row_class
					)
				);

				$i++;
				$dl_pos++;
			}
			$db->sql_freeresult($result);
		}

		/*
		* top ten traffic this month
		*/
		$sql = "SELECT (d.klicks * d.file_size) as month_traffic, d.*, c.cat_name
			FROM " . DOWNLOADS_TABLE . " d, " . DL_CAT_TABLE . " c
			WHERE d.cat = c.id
				AND c.id IN (" . implode(',', $access_cats) . ")
			ORDER BY month_traffic DESC
			LIMIT 10";
		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not query latest ten downloads', '', __LINE__, __FILE__, $sql);
		}

		$total_top_ten = $db->sql_numrows($result);
		if ($total_top_ten > 0)
		{
			$i = 0;
			$dl_pos = 1;
			while ($row = $db->sql_fetchrow($result))
			{
				$file_id = $row['id'];
				$cat_id = $row['cat'];
				$file_name_name = $row['file_name'];
				$description = $row['description'];
				$cat_name = $row['cat_name'];

				$dl_traffic = $dl_mod->dl_size($row['month_traffic']);

				$file_link = append_sid('downloads.' . PHP_EXT . '?view=detail&amp;df_id=' . $file_id);

				$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

				$template->assign_block_vars('top_ten_traffic_cur_month', array(
					'POS' => $dl_pos,
					'DESCRIPTION' => $description,
					'U_FILE_LINK' => $file_link,
					'CAT_NAME' => $cat_name,
					'DL_TRAFFIC' => $dl_traffic,
					'ROW_CLASS' => $row_class
					)
				);

				$i++;
				$dl_pos++;
			}
			$db->sql_freeresult($result);
		}

		/*
		* top ten traffic overall
		*/
		$sql = "SELECT (d.overall_klicks * d.file_size) as overall_traffic, d.*, c.cat_name
			FROM " . DOWNLOADS_TABLE . " d, " . DL_CAT_TABLE . " c
			WHERE d.cat = c.id
				AND c.id IN (" . implode(',', $access_cats) . ")
			ORDER BY overall_traffic DESC
			LIMIT 10";
		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not query latest ten downloads', '', __LINE__, __FILE__, $sql);
		}

		$total_top_ten = $db->sql_numrows($result);
		if ($total_top_ten > 0)
		{
			$i = 0;
			$dl_pos = 1;
			while ($row = $db->sql_fetchrow($result))
			{
				$file_id = $row['id'];
				$cat_id = $row['cat'];
				$file_name_name = $row['file_name'];
				$description = $row['description'];
				$cat_name = $row['cat_name'];

				$dl_traffic = $dl_mod->dl_size($row['overall_traffic']);

				$file_link = append_sid('downloads.' . PHP_EXT . '?view=detail&amp;df_id=' . $file_id);

				$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

				$template->assign_block_vars('top_ten_traffic_overall', array(
					'POS' => $dl_pos,
					'DESCRIPTION' => $description,
					'U_FILE_LINK' => $file_link,
					'CAT_NAME' => $cat_name,
					'DL_TRAFFIC' => $dl_traffic,
					'ROW_CLASS' => $row_class
					)
				);

				$i++;
				$dl_pos++;
			}
			$db->sql_freeresult($result);
		}

		/*
		* enable/disable guest data on extended statistics
		*/
		$sql_where = ($dl_config['guest_stats_show'] == 1) ? '' : ' AND s.user_id <> ' . ANONYMOUS;

		/*
		* top ten download counts
		*/
		$sql = "SELECT count(s.id) as dl_counts, s.user_id, s.username, u.username as user_username, u.user_active, u.user_color
			FROM " . DL_STATS_TABLE . " s, " . USERS_TABLE " u
			WHERE s.direction = 0
				AND s.cat_id IN (" . implode(',', $access_cats) . ")
				$sql_where
				AND u.user_id = s.user_id
			GROUP BY s.user_id, user_username
			ORDER BY dl_counts DESC
			LIMIT 10";
		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not query latest ten downloads', '', __LINE__, __FILE__, $sql);
		}

		$total_top_ten = $db->sql_numrows($result);
		if ($total_top_ten > 0)
		{
			$i = 0;
			$dl_pos = 1;
			while ($row = $db->sql_fetchrow($result))
			{
				$user_link = ($row['user_id'] == ANONYMOUS) ? $lang['Guest'] : colorize_username($row['user_id'], $row['user_username'], $row['user_color'], $row['user_active']);
				$user_name = ($row['user_id'] == ANONYMOUS) ? $lang['Guest'] : $row['username'];

				$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

				$template->assign_block_vars('top_ten_dl_counts', array(
					'POS' => $dl_pos,
					'USER_NAME' => $user_name,
					'U_USER_LINK' => $user_link,
					'DL_COUNTS' => $row['dl_counts'],
					'ROW_CLASS' => $row_class
					)
				);

				$i++;
				$dl_pos++;
			}
			$db->sql_freeresult($result);
		}

		/*
		* top ten download traffic
		*/
		$sql = "SELECT sum(s.traffic) as dl_traffic, s.user_id, s.username, u.username as user_username, u.user_active, u.user_color
			FROM " . DL_STATS_TABLE . " s, " . USERS_TABLE " u
			WHERE s.direction = 0
				AND s.cat_id IN (" . implode(',', $access_cats) . ")
				$sql_where
				AND u.user_id = s.user_id
			GROUP BY s.user_id, user_username
			ORDER BY dl_traffic DESC
			LIMIT 10";
		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not query latest ten downloads', '', __LINE__, __FILE__, $sql);
		}

		$total_top_ten = $db->sql_numrows($result);
		if ($total_top_ten > 0)
		{
			$i = 0;
			$dl_pos = 1;
			while ($row = $db->sql_fetchrow($result))
			{
				$user_link = ($row['user_id'] == ANONYMOUS) ? $lang['Guest'] : colorize_username($row['user_id'], $row['user_username'], $row['user_color'], $row['user_active']);
				$user_name = ($row['user_id'] == ANONYMOUS) ? $lang['Guest'] : $row['username'];
				$dl_traffic = $dl_mod->dl_size($row['dl_traffic']);

				$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

				$template->assign_block_vars('top_ten_dl_traffic', array(
					'POS' => $dl_pos,
					'USER_NAME' => $user_name,
					'U_USER_LINK' => $user_link,
					'DL_TRAFFIC' => $dl_traffic,
					'ROW_CLASS' => $row_class
					)
				);

				$i++;
				$dl_pos++;
			}
			$db->sql_freeresult($result);
		}

		/*
		* top ten upload counts
		*/
		$sql = "SELECT count(s.id) as dl_counts, s.user_id, s.username, u.username as user_username, u.user_active, u.user_color
			FROM " . DL_STATS_TABLE . " s, " . USERS_TABLE " u
			WHERE s.direction = 1
				AND s.cat_id IN (" . implode(',', $access_cats) . ")
				$sql_where
				AND u.user_id = s.user_id
			GROUP BY s.user_id, user_username
			ORDER BY dl_counts DESC
			LIMIT 10";
		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not query latest ten downloads', '', __LINE__, __FILE__, $sql);
		}

		$total_top_ten = $db->sql_numrows($result);
		if ($total_top_ten > 0)
		{
			$i = 0;
			$dl_pos = 1;
			while ($row = $db->sql_fetchrow($result))
			{
				$user_link = ($row['user_id'] == ANONYMOUS) ? $lang['Guest'] : colorize_username($row['user_id'], $row['user_username'], $row['user_color'], $row['user_active']);
				$user_name = ($row['user_id'] == ANONYMOUS) ? $lang['Guest'] : $row['username'];

				$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];

				$template->assign_block_vars('top_ten_up_counts', array(
					'POS' => $dl_pos,
					'USER_NAME' => $user_name,
					'U_USER_LINK' => $user_link,
					'DL_COUNTS' => $row['dl_counts'],
					'ROW_CLASS' => $row_class
					)
				);

				$i++;
				$dl_pos++;
			}
			$db->sql_freeresult($result);
		}

		/*
		* top ten upload traffic
		*/
		$sql = "SELECT sum(s.traffic) as dl_traffic, s.user_id, s.username, u.username as user_username, u.user_active, u.user_color
			FROM " . DL_STATS_TABLE . " s, " . USERS_TABLE " u
			WHERE s.direction = 1
				AND s.cat_id IN (" . implode(',', $access_cats) . ")
				$sql_where
				AND u.user_id = s.user_id
			GROUP BY s.user_id, user_username
			ORDER BY dl_traffic DESC
			LIMIT 10";
		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not query latest ten downloads', '', __LINE__, __FILE__, $sql);
		}

		$total_top_ten = $db->sql_numrows($result);
		if ($total_top_ten > 0)
		{
			$i = 0;
			$dl_pos = 1;
			while ($row = $db->sql_fetchrow($result))
			{
				$user_link = ($row['user_id'] == ANONYMOUS) ? $lang['Guest'] : colorize_username($row['user_id'], $row['user_username'], $row['user_color'], $row['user_active']);
				$user_name = ($row['user_id'] == ANONYMOUS) ? $lang['Guest'] : $row['username'];
				$dl_traffic = $dl_mod->dl_size($row['dl_traffic']);

				$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];

				$template->assign_block_vars('top_ten_up_traffic', array(
					'POS' => $dl_pos,
					'USER_NAME' => $user_name,
					'U_USER_LINK' => $user_link,
					'DL_TRAFFIC' => $dl_traffic,
					'ROW_CLASS' => $row_class
					)
				);

				$i++;
				$dl_pos++;
			}
			$db->sql_freeresult($result);
		}
	}
	else
	{
		redirect(append_sid('downloads.' . PHP_EXT));
	}
}
else
{
	redirect(append_sid('downloads.' . PHP_EXT));
}

$template->set_filenames(array('body' => 'dl_stat_body.tpl'));

$template->assign_vars(array(
	'L_PAGE' => $page_title,
	'L_LATEST_DOWNLOADS' => $lang['Dl_latest_downloads'],
	'L_LATEST_UPLOADS' => $lang['Dl_latest_uploads'],
	'L_DOWNLOADS_CUR_MONTH' => $lang['Dl_downloads_cur_month'],
	'L_DOWNLOADS_OVERALL' => $lang['Dl_downloads_overall'],
	'L_DOWNLOADS_DOWNLOADS_COUNT' => $lang['Dl_downloads_count'],
	'L_DOWNLOADS_DOWNLOADS_TRAFFIC' => $lang['Dl_downloads_traffic'],
	'L_DOWNLOADS_UPLOADS_COUNT' => $lang['Dl_uploads_count'],
	'L_DOWNLOADS_UPLOADS_TRAFFIC' => $lang['Dl_uploads_traffic'],
	'L_TRAFFIC_CUR_MONTH' => $lang['Dl_traffic_cur_month'],
	'L_TRAFFIC_OVERALL' => $lang['Dl_traffic_overall'],
	'L_POSITION' => $lang['Dl_pos'],
	'L_DOWNLOAD' => $lang['Dl_file_description'],
	'L_DL_TOP' => $lang['Dl_cat_title'],
	'L_TIME' => $lang['Dl_time'],
	'L_USERNAME' => $lang['User'],
	'L_DL_TRAFFIC' => $lang['Traffic'],
	'L_DL_COUNTS' => $lang['Dl_downloads_count'],
	'L_UP_COUNTS' => $lang['Dl_uploads_count'],
	'L_TIME' => $lang['Dl_time'],
	'L_KLICKS' => $lang['Dl_klicks'],
	'L_KLICKS_OVERALL' => $lang['Dl_overall_klicks'],
	'L_KL_M_T' => $lang['Dl_klicks_total'],
	'U_DL_TOP' => append_sid('downloads.' . PHP_EXT)
	)
);

?>