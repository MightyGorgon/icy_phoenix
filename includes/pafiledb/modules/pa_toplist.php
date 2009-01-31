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

class pafiledb_toplist extends pafiledb_public
{
	function main($action)
	{
		global $pafiledb_template, $lang, $board_config, $pafiledb_config, $db, $images, $userdata;

		if(!$this->auth_global['auth_toplist'])
		{
			if (!$userdata['session_logged_in'])
			{
				redirect(append_sid(LOGIN_MG . '?redirect=dload.' . PHP_EXT . '&action=stats', true));
			}

			$message = sprintf($lang['Sorry_auth_toplist'], $this->auth_global['auth_toplist_type']);
			message_die(GENERAL_MESSAGE, $message);
		}

		$mode = (isset($_REQUEST['mode'])) ? htmlspecialchars($_REQUEST['mode']) : 'newest';

		$days = (isset($_REQUEST['days'])) ? intval($_REQUEST['days']) : 7;

		$selected_date = ( isset($_REQUEST['selected_date']) ) ? $_REQUEST['selected_date'] : '';

		$most_num = ( isset($_REQUEST['most_num']) ) ? intval($_REQUEST['most_num']) : 10;

		$most_type = ( isset($_REQUEST['most_type']) ) ? htmlspecialchars($_REQUEST['most_type']) : 'num';

		if ($mode == 'downloads')
		{
			$l_current_toplist = $lang['Most_downloads'];
		}
		elseif ($mode == 'rating')
		{
			$l_current_toplist = $lang['Rated_downloads'];
		}
		else
		{
			$l_current_toplist = $lang['Latest_downloads'];
		}

		$pafiledb_template->assign_vars(array(
			'DOWNLOAD' => $pafiledb_config['settings_dbname'],
			'L_HOME' => $lang['Home'],
			'CURRENT_TIME' => sprintf($lang['Current_time'], create_date($board_config['default_dateformat'], time(), $board_config['board_timezone'])),

			'U_INDEX' => append_sid(PORTAL_MG),
			'U_DOWNLOAD' => append_sid('dload.' . PHP_EXT),
			'U_NEWEST_FILE' => append_sid('dload.' . PHP_EXT . '?action=toplist&mode=newest'),
			'U_MOST_POPULAR' => append_sid('dload.' . PHP_EXT . '?action=toplist&mode=downloads'),
			'U_TOP_RATED' => append_sid('dload.' . PHP_EXT . '?action=toplist&mode=rating'),
			'XS_NEW' => $xs_new,
			'L_CURRENT_TOPLIST' => $l_current_toplist,
			'L_NEWEST_FILE' => $lang['Latest_downloads'],
			'L_MOST_POPULAR' => $lang['Most_downloads'],
			'L_TOP_RATED' => $lang['Rated_downloads'],
			'L_INDEX' => sprintf($lang['Forum_Index'], $board_config['sitename']),
			'L_TOPLIST' => $lang['Toplist'])
		);

		$sql = 'SELECT file_time, file_id, file_catid
			FROM ' . PA_FILES_TABLE . "
			WHERE file_approved = '1'
			ORDER BY file_time DESC";

		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Couldnt Query stat info', '', __LINE__, __FILE__, $sql);
		}

		while($row = $db->sql_fetchrow($result))
		{
			if($this->auth[$row['file_catid']]['auth_read'])
			{
				$rowset[] = $row;
			}
		}

		$db->sql_freeresult($result);

		switch ($mode)
		{
			case 'newest':
				//get number of files in the last week
				$file_num_week = 0;
				$day_time = (time()-(86400 * 7));
				for($i = 0; $i < count($rowset); $i++)
				{
					if( ($rowset[$i]['file_time']) >= $day_time )
					{
						$file_num_week++;
					}
				}

				$file_num_month = 0;

				$day_time = (time()-(86400 * 30));
				for($i = 0; $i < count($rowset); $i++)
				{
					if( ($rowset[$i]['file_time']) >= $day_time )
					{
						$file_num_month++;
					}
				}

				$pafiledb_template->assign_vars(array(
					'IS_NEWEST' => true,
					'FILE_DATE' => (empty($selected_date)) ? true : false,

					'TOTAL_FILE_WEEK' => $file_num_week,
					'TOTAL_FILE_MONTH' => $file_num_month,

					'L_TOTAL_NEW_FILE' => $lang['Total_new_files'],
					'L_LAST_WEEK' => $lang['Last_week'],
					'L_LAST_30_DAYS' => $lang['Last_30_days'],
					'L_SHOW' => $lang['Show'],
					'L_ONE_WEEK' => $lang['One_week'],
					'L_TWO_WEEK' => $lang['Two_week'],
					'L_30_DAYS' => $lang['30_days'],
					'L_NEW_FILES' => sprintf($lang['New_Files'], $days),

					'U_ONE_WEEK' => append_sid('dload.' . PHP_EXT . '?action=toplist&amp;mode=newest&amp;days=7'),
					'U_TWO_WEEK' => append_sid('dload.' . PHP_EXT . '?action=toplist&amp;mode=newest&amp;days=14'),
					'U_30_DAYS' => append_sid('dload.' . PHP_EXT . '?action=toplist&amp;mode=newest&amp;days=30')
					)
				);

				if(empty($selected_date))
				{
					for($j = 0; $j <= $days - 1; $j++)
					{
						$day_time = (time()-(86400 * $j));
						$day_date = date('Y-m-d', $day_time);
						$file_num = 0;
						for($i = 0; $i < count($rowset); $i++)
						{
							$file_date = date('Y-m-d', $rowset[$i]['file_time']);
							if( $file_date == $day_date )
							{
								$file_num++;
							}
						}

						$pafiledb_template->assign_block_vars('files_date', array(
							'U_DATES' => append_sid('dload.' . PHP_EXT . '?action=toplist&amp;mode=newest&amp;days=7&amp;selected_date=' . $day_time),
							'DATES' => date('F d, Y', $day_time),
							'TOTAL_DOWNLOADS' => $file_num)
						);
					}
				}
				else
				{
					$pafiledb_template->assign_vars(array(
						'FILE_LIST' => true,

						'L_NEW_FILE' => $lang['New_file'],
						'L_RATE' => $lang['DlRating'],
						'L_DOWNLOADS' => $lang['Dls'],
						'L_DATE' => $lang['Date'],
						'L_NAME' => $lang['Name'],
						'L_FILE' => $lang['File'],
						'L_SUBMITER' => $lang['Submiter'],
						'L_CATEGORY' => $lang['Category'])
					);

					$file_ids = array();
					for($i = 0; $i < count($rowset); $i++)
					{
						$formated_date = date('Y-m-d', $selected_date);
						$file_date = date('Y-m-d', $rowset[$i]['file_time']);
						if($file_date == $formated_date)
						{
							$file_ids[] = $rowset[$i]['file_id'];
						}
					}
					$file_ids = implode(', ', $file_ids);
					if(!empty($file_ids))
					{
						$sql = "SELECT f1.*, AVG(r.rate_point) AS rating, COUNT(r.votes_file) AS total_votes, u.user_id, u.username, u.user_active, u.user_color, c.cat_id, c.cat_name, COUNT(cm.comments_id) AS total_comments
							FROM (" . PA_FILES_TABLE . " AS f1, " . PA_CATEGORY_TABLE . " AS c)
								LEFT JOIN " . PA_VOTES_TABLE . " AS r ON (f1.file_id = r.votes_file)
								LEFT JOIN ". USERS_TABLE ." AS u ON (f1.user_id = u.user_id)
								LEFT JOIN " . PA_COMMENTS_TABLE . " AS cm ON (f1.file_id = cm.file_id)
							WHERE c.cat_id = f1.file_catid
							AND f1.file_id IN ($file_ids)
							AND f1.file_approved = '1'
							GROUP BY f1.file_id
							ORDER BY file_time DESC";

						if (!($result = $db->sql_query($sql)))
						{
							message_die(GENERAL_ERROR, 'Couldnt Query stat info', '', __LINE__, __FILE__, $sql);
						}

						$file_rowset = array();
						while($row = $db->sql_fetchrow($result))
						{
							$file_rowset[] = $row;
						}
						$db->sql_freeresult($result);
					}
					else
					{
						$file_rowset = array();
					}



					for ($i = 0; $i < count($file_rowset); $i++)
					{

						$cat_url = append_sid('dload.' . PHP_EXT . '?action=category&amp;cat_id=' . $file_rowset[$i]['file_catid']);
						$file_url = append_sid('dload.' . PHP_EXT . '?action=file&amp;file_id=' . $file_rowset[$i]['file_id']);
						//===================================================
						// Format the date for the given file
						//===================================================

						$date = create_date2($board_config['default_dateformat'], $file_rowset[$i]['file_time'], $board_config['board_timezone']);

						//===================================================
						// Get rating for the file and format it
						//===================================================

						//$rating = ($file_rowset[$i]['rating'] != 0) ? round($file_rowset[$i]['rating'], 2) . ' / 10' : $lang['Not_rated'];
						//$rating2 = ($file_rowset[$i]['rating'] != 0) ? sprintf("%.1f", round(($file_rowset[$i]['rating']), 2)/2)  : '0.0';
						$rating2 = ($file_rowset[$i]['rating'] != 0) ? sprintf("%.1f", round(($file_rowset[$i]['rating']), 0)/2)  : '0.0';
						//===================================================
						// If the file is new then put a new image in front of it
						//===================================================

						$is_new = false;
						if (time() - ($pafiledb_config['settings_newdays'] * 24 * 60 * 60) < $file_rowset[$i]['file_time'])
						{
							$is_new = true;
						}

						$cat_name = $file_rowset[$i]['cat_name'];
						$xs_new = ($is_new) ? '-new' : '';

						//===================================================
						// Get the post icon fot this file
						//===================================================
						if ($file_rowset[$i]['file_pin'] != FILE_PINNED)
						{
							if (($file_rowset[$i]['file_posticon'] == 'none') || ($file_rowset[$i]['file_posticon'] == 'none.gif'))
							{
								$posticon = '<img src="' . IP_ROOT_PATH . FILES_ICONS_DIR . 'default.png" alt="" />';
							}
							else
							{
								$posticon = '<img src="' . FILES_ICONS_DIR . $file_rowset[$i]['file_posticon'] . '" alt="" />';
							}
						}
						else
						{
							$posticon = '<img src="' . $images['forum_link'] . '" alt="" />';
						}

						$poster = ($file_rowset[$i]['user_id'] == ANONYMOUS) ? $lang['Guest'] : colorize_username($file_rowset[$i]['user_id'], $file_rowset[$i]['username'], $file_rowset[$i]['user_color'], $file_rowset[$i]['user_active']);

						//===================================================
						// Assign Vars
						//===================================================
						$pafiledb_template->assign_block_vars('files_row', array(
							'CAT_NAME' => $cat_name,
							'FILE_NEW_IMAGE' => $images['pa_file_new'],
							'PIN_IMAGE' => $posticon,
							'XS_NEW' => $xs_new,
							'IS_NEW_FILE' => $is_new,
							'FILE_NAME' => $file_rowset[$i]['file_name'],
							'FILE_DESC' => $file_rowset[$i]['file_desc'],
							'FILE_SUBMITER' => $poster,
							'DATE' => $date,
							'RATING' => $rating2,
							'DOWNLOADS' => $file_rowset[$i]['file_dls'],

							'U_FILE' => $file_url,
							'U_CAT' => $cat_url
							)
						);
					}
				}


				break;
			case 'downloads':
			case 'rating':
				$rating_field = ($mode == 'rating') ? ', AVG(r.rate_point) AS rating' : '';
				$join_statement = ($mode == 'rating') ? 'LEFT JOIN ' . PA_VOTES_TABLE . ' AS r ON f.file_id = r.votes_file' : '';
				$group_statement = ($mode == 'rating') ? 'GROUP BY f.file_id' : '';

				$sql = "SELECT file_id" . $rating_field . "
					FROM " . PA_FILES_TABLE . " AS f
					$join_statement
					WHERE f.file_approved = '1'
					$group_statement
					ORDER BY f.file_time DESC";

				if (!($result = $db->sql_query($sql)))
				{
					message_die(GENERAL_ERROR, 'Couldnt Query category info for parent categories', '', __LINE__, __FILE__, $sql);
				}

				$file_num = 0;

				if($mode == 'downloads')
				{
					$file_num = $db->sql_numrows($result);
				}
				else
				{
					while($row = $db->sql_fetchrow($result))
					{
						if(!empty($row['rating']))
						{
							$file_num++;
						}
					}
				}

				$limit = $most_num;
				if($most_type == 'per')
				{
					$limit = $most_num / 100;
					$limit = $file_num * $limit;
					$limit = round($limit);
				}
				$limit = ($limit <= 0) ? 1 : $limit;

				$pafiledb_template->assign_vars(array(
					'IS_POPULAR' => true,
					'FILE_LIST' => true,

					'L_NEW_FILES' => sprintf( ($most_type == 'num') ? $lang['Popular_num'] : $lang['Popular_per'], $most_num, $file_num),
					'L_NEW_FILE' => $lang['New_file'],
					'L_SHOW_TOP' => $lang['Show_top'],
					'L_OR_TOP' => $lang['Or_top'],
					'L_RATE' => $lang['DlRating'],
					'L_DOWNLOADS' => $lang['Dls'],
					'L_DATE' => $lang['Date'],
					'L_NAME' => $lang['Name'],
					'L_FILE' => $lang['File'],
					'L_SUBMITER' => $lang['Submiter'],
					'L_CATEGORY' => $lang['Category'],


					'U_TOP_10' => append_sid('dload.' . PHP_EXT . '?action=toplist&amp;mode=' . $mode . '&amp;most_type=num&amp;most_num=10'),
					'U_TOP_25' => append_sid('dload.' . PHP_EXT . '?action=toplist&amp;mode=' . $mode . '&amp;most_type=num&amp;most_num=25'),
					'U_TOP_50' => append_sid('dload.' . PHP_EXT . '?action=toplist&amp;mode=' . $mode . '&amp;most_type=num&amp;most_num=50'),

					'U_TOP_PER_1' => append_sid('dload.' . PHP_EXT . '?action=toplist&amp;mode=' . $mode . '&amp;most_type=per&amp;most_num=1'),
					'U_TOP_PER_5' => append_sid('dload.' . PHP_EXT . '?action=toplist&amp;mode=' . $mode . '&amp;most_type=per&amp;most_num=5'),
					'U_TOP_PER_10' => append_sid('dload.' . PHP_EXT . '?action=toplist&amp;mode=' . $mode . '&amp;most_type=per&amp;most_num=10')
					)
				);

				if ($limit)
				{
					$sort_method = ($mode == 'downloads') ? 'file_dls' : 'rating';
					$sql_limit = "LIMIT 0, $limit ";
					$sql = "SELECT f1.*, AVG(r.rate_point) AS rating, COUNT(r.votes_file) AS total_votes, u.user_id, u.username, u.user_active, u.user_color, c.cat_id, c.cat_name
						FROM (" . PA_FILES_TABLE . " AS f1, " . PA_CATEGORY_TABLE . " AS c)
						LEFT JOIN " . PA_VOTES_TABLE . " AS r ON (f1.file_id = r.votes_file)
						LEFT JOIN ". USERS_TABLE ." AS u ON (f1.user_id = u.user_id)
						WHERE c.cat_id = f1.file_catid
						AND f1.file_approved = '1'
						GROUP BY f1.file_id
						ORDER BY $sort_method DESC
						$sql_limit";
					if (!($result = $db->sql_query($sql)))
					{
						message_die(GENERAL_ERROR, 'Couldnt Query category info for parent categories', '', __LINE__, __FILE__, $sql);
					}
				}
				$searchset = array();
				while($row = $db->sql_fetchrow($result))
				{
					$searchset[] = $row;
				}

				for($i = 0; $i < count($searchset); $i++)
				{
					if($mode == 'rating')
					{
						if(empty($searchset[$i]['rating']))
						{
							continue;
						}
					}

					$cat_url = append_sid('dload.' . PHP_EXT . '?action=category&amp;cat_id=' . $searchset[$i]['cat_id']);
					$file_url = append_sid('dload.' . PHP_EXT . '?action=file&amp;file_id=' . $searchset[$i]['file_id']);
					//===================================================
					// Format the date for the given file
					//===================================================

					$date = create_date2($board_config['default_dateformat'], $searchset[$i]['file_time'], $board_config['board_timezone']);

					//===================================================
					// Get rating for the file and format it
					//===================================================

					//$rating = ($searchset[$i]['rating'] != 0) ? round($searchset[$i]['rating'], 2) . ' / 10' : $lang['Not_rated'];
					//$rating2 = ($searchset[$i]['rating'] != 0) ? sprintf("%.1f", round(($searchset[$i]['rating']), 2)/2) : '0.0';
					$rating2 = ($searchset[$i]['rating'] != 0) ? sprintf("%.1f", round(($searchset[$i]['rating']), 0)/2) : '0.0';
					//===================================================
					// If the file is new then put a new image in front of it
					//===================================================

					$is_new = false;
					if (time() - ($pafiledb_config['settings_newdays'] * 24 * 60 * 60) < $searchset[$i]['file_time'])
					{
						$is_new = true;
					}
					$xs_new = ($is_new) ? '-new' : '';

					//===================================================
					// Get the post icon fot this file
					//===================================================
					if ($searchset[$i]['file_pin'] != FILE_PINNED)
					{
						if (($searchset[$i]['file_posticon'] == 'none') || ($searchset[$i]['file_posticon'] == 'none.gif'))
						{
							$posticon = '<img src="' . IP_ROOT_PATH . FILES_ICONS_DIR . 'default.png" alt="" />';
						}
						else
						{
							$posticon = '<img src="' . FILES_ICONS_DIR . $searchset[$i]['file_posticon'] . '" alt="" />';
						}
					}
					else
					{
						$posticon = '<img src="' . $images['forum_nor_read'] . '" alt="" />';
					}

					$poster = ($searchset[$i]['user_id'] == ANONYMOUS) ? $lang['Guest'] : colorize_username($searchset[$i]['user_id'], $searchset[$i]['username'], $searchset[$i]['user_color'], $searchset[$i]['user_active']);
					$pafiledb_template->assign_block_vars('files_row', array(
						'CAT_NAME' => $searchset[$i]['cat_name'],
						'FILE_NEW_IMAGE' => $images['pa_file_new'],
						'PIN_IMAGE' => $posticon,
						'L_HOME' => $lang['Home'],
						'CURRENT_TIME' => sprintf($lang['Current_time'], create_date($board_config['default_dateformat'], time(), $board_config['board_timezone'])),
						'L_HOME' => $lang['Home'],
						'CURRENT_TIME' => sprintf($lang['Current_time'], create_date($board_config['default_dateformat'], time(), $board_config['board_timezone'])),
						'XS_NEW' => $xs_new,
						'IS_NEW_FILE' => $is_new,
						'FILE_NAME' => $searchset[$i]['file_name'],
						'FILE_DESC' => $searchset[$i]['file_desc'],
						'FILE_SUBMITER' => $poster,
						'DATE' => $date,
						'RATING' => $rating2,
						'DOWNLOADS' => $searchset[$i]['file_dls'],
						'U_FILE' => $file_url,
						'U_CAT' => $cat_url
						)
					);
				}
				break;
		}

		$this->display($lang['Download'], 'pa_toplist_body.tpl');
	}
}

?>