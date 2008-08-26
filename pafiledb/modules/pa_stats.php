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
* Todd - (todd@phparena.net) - (http://www.phparena.net)
*
*/

class pafiledb_stats extends pafiledb_public
{
	function main($action)
	{
		global $pafiledb_template, $lang, $board_config, $phpEx, $pafiledb_config, $db, $images, $phpbb_root_path, $userdata;


		if(!$this->auth_global['auth_stats'])
		{
			if ( !$userdata['session_logged_in'] )
			{
				redirect(append_sid(LOGIN_MG . '?redirect=dload.' . $phpEx . '&action=stats', true));
			}

			$message = sprintf($lang['Sorry_auth_stats'], $this->auth_global['auth_stats_type']);
			message_die(GENERAL_MESSAGE, $message);
		}

		$num['cats'] = $this->total_cat;

		$sql = "SELECT file_id
			FROM " . PA_FILES_TABLE . "
			WHERE file_approved = '1'";

		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Couldnt Query stat info', '', __LINE__, __FILE__, $sql);
		}

		$num['files'] = $db->sql_numrows($result);
		$db->sql_freeresult($result);

		$sql = 'SELECT file_id, file_name
			FROM ' . PA_FILES_TABLE . "
			WHERE file_approved = '1'
			ORDER BY file_time DESC";

		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Couldnt Query stat info', '', __LINE__, __FILE__, $sql);
		}

		$newest = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);

		$sql = 'SELECT file_id, file_name
			FROM ' . PA_FILES_TABLE . "
			WHERE file_approved = '1'
			ORDER BY file_time ASC";

		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Couldnt Query stat info', '', __LINE__, __FILE__, $sql);
		}

		$oldest = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);

		$sql = "SELECT r.votes_file, AVG(r.rate_point) AS rating, f.file_id, f.file_name
			FROM " . PA_VOTES_TABLE . " AS r, " . PA_FILES_TABLE . " AS f
			WHERE r.votes_file = f.file_id
			AND f.file_approved = '1'
			GROUP BY f.file_id
			ORDER BY rating DESC";

		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Couldnt Query stat info', '', __LINE__, __FILE__, $sql);
		}
		$popular = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);

		$sql = "SELECT r.votes_file, AVG(r.rate_point) AS rating, f.file_id, f.file_name
			FROM " . PA_VOTES_TABLE . " AS r, " . PA_FILES_TABLE . " AS f
			WHERE r.votes_file = f.file_id
			AND f.file_approved = '1'
			GROUP BY f.file_id
			ORDER BY rating ASC";

		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Couldnt Query stat info', '', __LINE__, __FILE__, $sql);
		}

		$lpopular = $db->sql_fetchrow($result);
		$total_votes = $total_rating = 0;

		while($row = $db->sql_fetchrow($result))
		{
			$total_rating += $row['rating'];
			$total_votes++;
		}
		$db->sql_freeresult($result);
		$sql = "SELECT file_id, file_name, file_dls
			FROM " . PA_FILES_TABLE . "
			WHERE file_approved = '1'
			ORDER BY file_dls DESC";

		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Couldnt Query stat info', '', __LINE__, __FILE__, $sql);
		}

		$mostdl = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);

		$sql = "SELECT file_id, file_name, file_dls
			FROM " . PA_FILES_TABLE . "
			WHERE file_approved = '1'
			ORDER BY file_dls ASC";

		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Couldnt Query stat info', '', __LINE__, __FILE__, $sql);
		}

		$leastdl = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);

		$sql = "SELECT file_dls
			FROM " . PA_FILES_TABLE . "
			WHERE file_approved = '1'";

		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Couldnt Query stat info', '', __LINE__, __FILE__, $sql);
		}

		while($row = $db->sql_fetchrow($result))
		{
			$totaldls += $row['file_dls'];
		}
		$db->sql_freeresult($result);

		$avg = @round($total_rating/$total_votes);

		$avgdls = @round($totaldls/$num['files']);

		require($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_pafiledb.' . $phpEx);

		$lang['Stats_text'] = str_replace("{total_files}", $num['files'], $lang['Stats_text']);
		$lang['Stats_text'] = str_replace("{total_categories}", $num['cats'], $lang['Stats_text']);
		$lang['Stats_text'] = str_replace("{total_downloads}", $totaldls, $lang['Stats_text']);
		$lang['Stats_text'] = str_replace("{u_newest_file}", append_sid('dload.' . $phpEx . '?action=file&file_id=' . $newest['file_id']), $lang['Stats_text']);
		$lang['Stats_text'] = str_replace("{newest_file}", $newest['file_name'], $lang['Stats_text']);
		$lang['Stats_text'] = str_replace("{u_oldest_file}", append_sid('dload.' . $phpEx . '?action=file&file_id=' . $oldest['file_id']), $lang['Stats_text']);
		$lang['Stats_text'] = str_replace("{oldest_file}", $oldest['file_name'], $lang['Stats_text']);
		$lang['Stats_text'] = str_replace("{average}", $avg, $lang['Stats_text']);
		$lang['Stats_text'] = str_replace("{u_popular}", append_sid('dload.' . $phpEx . '?action=file&file_id=' . $popular['file_id']), $lang['Stats_text']);
		$lang['Stats_text'] = str_replace("{popular}", $popular['file_name'], $lang['Stats_text']);
		$lang['Stats_text'] = str_replace("{most}", round($popular['rating'], 2), $lang['Stats_text']);
		$lang['Stats_text'] = str_replace("{u_lpopular}", append_sid('dload.' . $phpEx . '?action=file&file_id=' . $lpopular['file_id']), $lang['Stats_text']);
		$lang['Stats_text'] = str_replace("{lpopular}", $lpopular['file_name'], $lang['Stats_text']);
		$lang['Stats_text'] = str_replace("{least}", round($lpopular['rating'], 2), $lang['Stats_text']);
		$lang['Stats_text'] = str_replace("{avg_dls}", $avgdls, $lang['Stats_text']);
		$lang['Stats_text'] = str_replace("{u_most_dl}", append_sid('dload.' . $phpEx . '?action=file&file_id=' . $mostdl['file_id']), $lang['Stats_text']);
		$lang['Stats_text'] = str_replace("{most_dl}", $mostdl['file_name'], $lang['Stats_text']);
		$lang['Stats_text'] = str_replace("{most_no}", $mostdl['file_dls'], $lang['Stats_text']);
		$lang['Stats_text'] = str_replace("{u_least_dl}", append_sid('dload.' . $phpEx . '?action=file&file_id=' . $leastdl['file_id']), $lang['Stats_text']);
		$lang['Stats_text'] = str_replace("{least_dl}", $leastdl['file_name'], $lang['Stats_text']);
		$lang['Stats_text'] = str_replace("{least_no}", $leastdl['file_dls'], $lang['Stats_text']);

		$agent_lang = array('OPERA' => 'Opera', 'IE' => 'Internet Explorer', 'MOZILLA' => 'Mozilla', 'NETSCAPE' => 'NetScape', 'OTHER' => 'Other');
		$agent_image = array('OPERA' => 'opera.png', 'IE' => 'msie.png', 'MOZILLA' => 'mozilla.png', 'NETSCAPE' => 'netscape.png', 'OTHER' => 'unknown_browser.png');
		$agent_point = array('OPERA' => 0, 'IE' => 0, 'MOZILLA' => 0, 'NETSCAPE' => 0, 'OTHER' => 0);

		$os_lang = array('Win' => 'Windows', 'Mac' => 'Macintosh', 'Linux' => 'Linux', 'Unix' => 'Unix', 'Other' => 'Other');
		$os_image = array('Win' => 'windows.png', 'Mac' => 'apple.png', 'Linux' => 'linux.png', 'Unix' => 'linux.png', 'Other' => 'unknown_os.png');
		$os_point = array('Win' => 0, 'Mac' => 0, 'Linux' => 0, 'Unix' => 0, 'Other' => 0);

		$sql = "SELECT downloader_os, downloader_browser
			FROM " . PA_DOWNLOAD_INFO_TABLE;

		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not obtain downloads info', '', __LINE__, __FILE__, $sql);
		}

		$row_downloads = $db->sql_fetchrowset($result);
		$db->sql_freeresult($result);

		for($i = 0; $i < count($row_downloads); $i++)
		{
			$os_point[$row_downloads[$i]['downloader_os']]++;
			$agent_point[$row_downloads[$i]['downloader_browser']]++;
		}

		$os_graphic = 0;
		$os_graphic_max = count($images['voting_graphic']);

		foreach($os_point as $index => $point)
		{
			$temp_point = ($point > 100) ? 100 : $point;
			$os_graphic_img = $images['voting_graphic'][$os_graphic];
			$os_graphic = ($os_graphic < $os_graphic_max - 1) ? $os_graphic + 1 : 0;
// MX
			$pafiledb_template->assign_block_vars('downloads_os', array(
				'OS_IMG' => PA_FILE_DB_PATH . 'images/stats/' . $os_image[$index],
				'OS_NAME' => $os_lang[$index],
				'OS_OPTION_RESULT' => $point,
				'OS_OPTION_IMG' => $os_graphic_img,
				'OS_OPTION_IMG_WIDTH' => $temp_point * 2)
			);
		}

		$b_graphic = 0;
		$b_graphic_max = count($images['voting_graphic']);

		foreach($agent_point as $index => $point)
		{
			$temp_point = ($point > 100) ? 100 : $point;
			$b_graphic_img = $images['voting_graphic'][$b_graphic];
			$b_graphic = ($b_graphic < $b_graphic_max - 1) ? $b_graphic + 1 : 0;
// MX
			$pafiledb_template->assign_block_vars('downloads_b', array(
				'B_IMG' => PA_FILE_DB_PATH . 'images/stats/' . $agent_image[$index],
				'B_NAME' => $agent_lang[$index],
				'B_OPTION_RESULT' => $point,
				'B_OPTION_IMG' => $b_graphic_img,
				'B_OPTION_IMG_WIDTH' => $temp_point * 2)
			);
		}

		$agent_point = array('OPERA' => 0, 'IE' => 0, 'MOZILLA' => 0, 'NETSCAPE' => 0, 'OTHER' => 0);
		$os_point = array('Win' => 0, 'Mac' => 0, 'Linux' => 0, 'Unix' => 0, 'Other' => 0);

		$sql = "SELECT voter_os, voter_browser
			FROM " . PA_VOTES_TABLE;

		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not obtain downloads info', '', __LINE__, __FILE__, $sql);
		}

		$row_ratings = $db->sql_fetchrowset($result);
		$db->sql_freeresult($result);

		for($i = 0; $i < count($row_ratings); $i++)
		{
			$os_point[$row_ratings[$i]['voter_os']]++;
			$agent_point[$row_ratings[$i]['voter_browser']]++;
		}

		$os_graphic = 0;
		$os_graphic_max = count($images['voting_graphic']);

		foreach($os_point as $index => $point)
		{
			$temp_point = ($point > 100) ? 100 : $point;
			$os_graphic_img = $images['voting_graphic'][$os_graphic];
			$os_graphic = ($os_graphic < $os_graphic_max - 1) ? $os_graphic + 1 : 0;
// MX
			$pafiledb_template->assign_block_vars('rating_os', array(
				'OS_IMG' => PA_FILE_DB_PATH . 'images/stats/' . $os_image[$index],
				'OS_NAME' => $os_lang[$index],
				'OS_OPTION_RESULT' => $point,
				'OS_OPTION_IMG' => $os_graphic_img,
				'OS_OPTION_IMG_WIDTH' => $temp_point)
			);
		}


		$b_graphic = 0;
		$b_graphic_max = count($images['voting_graphic']);

		foreach($agent_point as $index => $point)
		{
			$temp_point = ($point > 100) ? 100 : $point;
			$b_graphic_img = $images['voting_graphic'][$b_graphic];
			$b_graphic = ($b_graphic < $b_graphic_max - 1) ? $b_graphic + 1 : 0;
// MX
			$pafiledb_template->assign_block_vars('rating_b', array(
				'B_IMG' => PA_FILE_DB_PATH . 'images/stats/' . $agent_image[$index],
				'B_NAME' => $agent_lang[$index],
				'B_OPTION_RESULT' => $point,
				'B_OPTION_IMG' => $b_graphic_img,
				'B_OPTION_IMG_WIDTH' => $temp_point)
			);
		}

		$pafiledb_template->assign_vars(array(
			'S_ACTION_CHART' => append_sid('dload.' . $phpEx . '?action=stats'),

		 	'L_STATISTICS' => $lang['Statistics'],
			'L_INDEX' => sprintf($lang['Forum_Index'], $board_config['sitename']),
			'L_GENERAL_INFO' => $lang['General_Info'],
			'L_DOWNLOADS_STATS' => $lang['Downloads_stats'],
			'L_RATING_STATS' => $lang['Rating_stats'],
			'L_OS' => $lang['Os'],
			'L_BROWSERS' => $lang['Browsers'],
			'L_HOME' => $lang['Home'],
			'CURRENT_TIME' => sprintf($lang['Current_time'], create_date($board_config['default_dateformat'], time(), $board_config['board_timezone'])),

			'U_INDEX' => append_sid(PORTAL_MG),
			'U_DOWNLOAD' => append_sid('dload.' . $phpEx),


			'U_VOTE_LCAP' => $images['voting_graphic_left'],
			'U_VOTE_RCAP' => $images['voting_graphic_right'],

			'DOWNLOAD' => $pafiledb_config['settings_dbname'],
		 	'STATS_TEXT' => $lang['Stats_text'])
		);
		$this->display($lang['Download'], 'pa_stats_body.tpl');
	}
}

?>