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

class pafiledb_search extends pafiledb_public
{
	function main($action)
	{
		global $pafiledb_template, $lang, $config, $pafiledb_config, $db, $images, $userdata;

		if(!$this->auth_global['auth_search'])
		{
			if ( !$userdata['session_logged_in'] )
			{
				redirect(append_sid(CMS_PAGE_LOGIN . '?redirect=dload.' . PHP_EXT . '&action=stats', true));
			}

			$message = sprintf($lang['Sorry_auth_search'], $this->auth_global['auth_search_type']);
			message_die(GENERAL_MESSAGE, $message);
		}

		include(IP_ROOT_PATH . 'includes/functions_search.' . PHP_EXT);

		if ( isset($_REQUEST['search_keywords']) )
		{
			$search_keywords = htmlspecialchars($_REQUEST['search_keywords']);
		}
		else
		{
			$search_keywords = '';
		}

		$search_author = ( isset($_REQUEST['search_author']) ) ? htmlspecialchars($_REQUEST['search_author']) : '';

		$search_id = ( isset($_REQUEST['search_id']) ) ? intval($_REQUEST['search_id']) : 0;

		if ( isset($_REQUEST['search_terms']) )
		{
			$search_terms = ( $_REQUEST['search_terms'] == 'all' ) ? 1 : 0;
		}
		else
		{
			$search_terms = 0;
		}

		$cat_id = ( isset($_REQUEST['cat_id']) ) ? intval($_REQUEST['cat_id']) : 0;


		if ( isset($_REQUEST['comments_search']) )
		{
			$comments_search = ( $_REQUEST['comments_search'] == 'YES' ) ? 1 : 0;
		}
		else
		{
			$comments_search =  0;
		}

		$start = ( isset($_REQUEST['start']) ) ? intval($_REQUEST['start']) : 0;
		$start = ($start < 0) ? 0 : $start;

		if( isset($_REQUEST['sort_method']) )
		{
			switch ($_REQUEST['sort_method'])
			{
				case 'file_name':
					$sort_method = 'file_name';
					break;
				case 'file_time':
					$sort_method = 'file_time';
					break;
				case 'file_dls':
					$sort_method = 'file_dls';
					break;
				case 'file_rating':
					$sort_method = 'rating';
					break;
				case 'file_update_time':
					$sort_method = 'file_update_time';
					break;
				default:
					$sort_method = $pafiledb_config['sort_method'];
			}
		}
		else
		{
			$sort_method = $pafiledb_config['sort_method'];
		}

		if( isset($_REQUEST['sort_order']) )
		{
			switch ($_REQUEST['sort_order'])
			{
				case 'ASC':
					$sort_order = 'ASC';
					break;
				case 'DESC':
					$sort_order = 'DESC';
					break;
				default:
					$sort_order = $pafiledb_config['sort_order'];
			}
		}
		else
		{
			$sort_order = $pafiledb_config['sort_order'];
		}


		$limit_sql = ($start == 0) ? $pafiledb_config['settings_file_page'] : $start . ',' . $pafiledb_config['settings_file_page'];
		//
		// encoding match for workaround
		//
		$multibyte_charset = 'utf-8, big5, shift_jis, euc-kr, gb2312';


		if ( isset($_POST['submit']) ||  $search_author != '' || $search_keywords != '' || $search_id )
		{
			$store_vars = array('search_results', 'total_match_count', 'split_search', 'sort_method', 'sort_order');

			if($search_author != '' || $search_keywords != '')
			{
				if ( $search_author != '' && $search_keywords == '' )
				{
					$search_author = str_replace('*', '%', trim($search_author));

					$sql = "SELECT user_id
						FROM " . USERS_TABLE . "
						WHERE username LIKE '" . str_replace("\'", "''", $search_author) . "'";
					$result = $db->sql_query($sql);

					$matching_userids = '';
					if ( $row = $db->sql_fetchrow($result) )
					{
						do
						{
							$matching_userids .= ( ( $matching_userids != '' ) ? ', ' : '' ) . $row['user_id'];
						}
						while( $row = $db->sql_fetchrow($result) );
					}
					else
					{
						message_die(GENERAL_MESSAGE, $lang['No_search_match']);
					}

					$sql = "SELECT *
						FROM " . PA_FILES_TABLE . "
						WHERE user_id IN ($matching_userids)";
					$result = $db->sql_query($sql);

					$search_ids = array();
					while( $row = $db->sql_fetchrow($result) )
					{
						if($this->auth[$row['file_catid']]['auth_view'])
						{
							$search_ids[] = $row['file_id'];
						}
					}
					$db->sql_freeresult($result);

					$total_match_count = sizeof($search_ids);
				}
				else if ( $search_keywords != '' )
				{
					$stopword_array = @file(IP_ROOT_PATH . 'language/lang_' . $config['default_lang'] . '/search_stopwords.txt');
					$synonym_array = @file(IP_ROOT_PATH . 'language/lang_' . $config['default_lang'] . '/search_synonyms.txt');

					$split_search = array();
					$split_search = ( !strstr($multibyte_charset, $lang['ENCODING']) ) ?  split_words(clean_words('search', stripslashes($search_keywords), $stopword_array, $synonym_array), 'search') : split(' ', $search_keywords);

					$word_count = 0;
					$current_match_type = 'or';

					$word_match = array();
					$result_list = array();

					for($i = 0; $i < sizeof($split_search); $i++)
					{
						switch ( $split_search[$i] )
						{
							case 'and':
								$current_match_type = 'and';
								break;

							case 'or':
								$current_match_type = 'or';
								break;

							case 'not':
								$current_match_type = 'not';
								break;

							default:
								if ( !empty($search_terms) )
								{
									$current_match_type = 'and';
								}
								$match_word =  addslashes('%' . str_replace('*', '', $split_search[$i]) . '%');

								$sql = "SELECT file_id
									FROM " . PA_FILES_TABLE . "
									WHERE (file_name LIKE '$match_word'
									OR file_creator LIKE '$match_word'
									OR file_desc LIKE '$match_word'
									OR file_longdesc LIKE '$match_word')";
								$result = $db->sql_query($sql);

							$row = array();
							while( $temp_row = $db->sql_fetchrow($result) )
							{
								$row[$temp_row['file_id']] = 1;

								if ( !$word_count )
								{
									$result_list[$temp_row['file_id']] = 1;
								}
								else if ( $current_match_type == 'or' )
								{
									$result_list[$temp_row['file_id']] = 1;
								}
								else if ( $current_match_type == 'not' )
								{
									$result_list[$temp_row['file_id']] = 0;
								}
							}

							if ( $current_match_type == 'and' && $word_count )
							{
								@reset($result_list);
								while( list($file_id, $match_count) = @each($result_list) )
								{
									if ( !$row[$file_id] )
									{
										$result_list[$file_id] = 0;
									}
								}
							}

							if($comments_search)
							{
								$sql = "SELECT file_id
									FROM " . PA_COMMENTS_TABLE . "
									WHERE (comments_title LIKE '$match_word'
									OR comments_text LIKE '$match_word')";
								$result = $db->sql_query($sql);

								$row = array();
								while( $temp_row = $db->sql_fetchrow($result) )
								{
									$row[$temp_row['file_id']] = 1;

									if ( !$word_count )
									{
										$result_list[$temp_row['file_id']] = 1;
									}
									else if ( $current_match_type == 'or' )
									{
										$result_list[$temp_row['file_id']] = 1;
									}
									else if ( $current_match_type == 'not' )
									{
										$result_list[$temp_row['file_id']] = 0;
									}
								}

								if ( $current_match_type == 'and' && $word_count )
								{
									@reset($result_list);
									while( list($file_id, $match_count) = @each($result_list) )
									{
										if ( !$row[$file_id] )
										{
											$result_list[$file_id] = 0;
										}
									}
								}
							}

							$word_count++;

							$db->sql_freeresult($result);
						}
					}
					@reset($result_list);

					$search_ids = array();
					while( list($file_id, $matches) = each($result_list) )
					{
						if ( $matches )
						{
							$search_ids[] = $file_id;
						}
					}

					unset($result_list);
					$total_match_count = sizeof($search_ids);
				}
			//
			// Author name search
			//
				if ( $search_author != '' )
				{
					$search_author = str_replace('*', '%', trim(str_replace("\'", "''", $search_author)));
				}

				if ( $total_match_count )
				{
					$where_sql = ($cat_id) ? 'AND file_catid IN (' . $this->gen_cat_ids($cat_id, '') . ')' : '';

					if ( $search_author == '')
					{
						$sql = "SELECT file_id, file_catid
							FROM " . PA_FILES_TABLE . "
							WHERE file_id IN (" . implode(", ", $search_ids) . ")
								$where_sql
							GROUP BY file_id";
					}
					else
					{
						$from_sql = PA_FILES_TABLE . " f";
						if ( $search_author != '' )
						{
							$from_sql .= ", " . USERS_TABLE . " u";
							$where_sql .= " AND u.user_id = f.user_id AND u.username LIKE '$search_author' ";
						}

						$where_sql .= ($cat_id) ? 'AND file_catid IN (' . $this->gen_cat_ids($cat_id, '') . ')' : '';

						$sql = "SELECT f.file_id, f.file_catid
							FROM $from_sql
							WHERE f.file_id IN (" . implode(", ", $search_ids) . ")
							$where_sql
							GROUP BY f.file_id";
					}
					$result = $db->sql_query($sql);

					$search_ids = array();
					while( $row = $db->sql_fetchrow($result) )
					{
						if($this->auth[$row['file_catid']]['auth_view'])
						{
							$search_ids[] = $row['file_id'];
						}
					}
					$db->sql_freeresult($result);
					$total_match_count = sizeof($search_ids);
				}
				else
				{
					message_die(GENERAL_MESSAGE, $lang['No_search_match']);
				}

				//
				// Finish building query (for all combinations)
				// and run it ...
				//
				$expiry_time = $current_time - $config['session_length'];
				$sql = "SELECT session_id
					FROM " . SESSIONS_TABLE ."
					WHERE session_time > $expiry_time";
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if ($result)
				{
					$delete_search_ids = array();
					while( $row = $db->sql_fetchrow($result) )
					{
						$delete_search_ids[] = "'" . $row['session_id'] . "'";
					}

					if ( sizeof($delete_search_ids) )
					{
						$sql = "DELETE FROM " . SEARCH_TABLE . "
							WHERE session_id NOT IN (" . implode(", ", $delete_search_ids) . ")";
						$result = $db->sql_query($sql);
					}
				}

				//
				// Store new result data
				//
				$search_results = implode(', ', $search_ids);

				$store_search_data = array();

				for($i = 0; $i < sizeof($store_vars); $i++)
				{
					$store_search_data[$store_vars[$i]] = $$store_vars[$i];
				}

				$result_array = serialize($store_search_data);
				unset($store_search_data);

				mt_srand ((double) microtime() * 1000000);
				$search_id = mt_rand();

				$sql = "UPDATE " . SEARCH_TABLE . "
					SET search_id = $search_id, search_array = '" . str_replace("\'", "''", $result_array) . "'
					WHERE session_id = '" . $userdata['session_id'] . "'";
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if (!$result || !$db->sql_affectedrows())
				{
					$sql = "INSERT INTO " . SEARCH_TABLE . " (search_id, session_id, search_array)
						VALUES($search_id, '" . $userdata['session_id'] . "', '" . str_replace("\'", "''", $result_array) . "')";
					$result = $db->sql_query($sql);
				}
			}
			else
			{
				$search_id = intval($search_id);
				if ( $search_id )
				{
					$sql = "SELECT search_array
						FROM " . SEARCH_TABLE . "
						WHERE search_id = $search_id
						AND session_id = '" . $userdata['session_id'] . "'";
					$result = $db->sql_query($sql);

					if ( $row = $db->sql_fetchrow($result) )
					{
						$search_data = unserialize($row['search_array']);
						for($i = 0; $i < sizeof($store_vars); $i++)
						{
							$$store_vars[$i] = $search_data[$store_vars[$i]];
						}
					}
				}
			}


			if ( $search_results != '' )
			{
				$sql = "SELECT f1.*, AVG(r.rate_point) AS rating, COUNT(r.votes_file) AS total_votes, u.user_id, u.username, u.user_active, u.user_color, c.cat_id, c.cat_name, COUNT(cm.comments_id) AS total_comments
					FROM (" . PA_FILES_TABLE . " AS f1, " . PA_CATEGORY_TABLE . " AS c)
						LEFT JOIN " . PA_VOTES_TABLE . " AS r ON f1.file_id = r.votes_file
						LEFT JOIN ". USERS_TABLE ." AS u ON f1.user_id = u.user_id
						LEFT JOIN " . PA_COMMENTS_TABLE . " AS cm ON f1.file_id = cm.file_id
					WHERE f1.file_id IN ($search_results)
					AND c.cat_id = f1.file_catid
					AND f1.file_approved = '1'
					GROUP BY f1.file_id
					ORDER BY $sort_method $sort_order
					LIMIT $limit_sql";
				$result = $db->sql_query($sql);

				$searchset = array();
				while($row = $db->sql_fetchrow($result))
				{
					$searchset[] = $row;
				}

				$db->sql_freeresult($result);

				$l_search_matches = ( $total_match_count == 1 ) ? sprintf($lang['Found_search_match'], $total_match_count) : sprintf($lang['Found_search_matches'], $total_match_count);

				$pafiledb_template->assign_vars(array(
					'L_SEARCH_MATCHES' => $l_search_matches
					)
				);

				for($i = 0; $i < sizeof($searchset); $i++)
				{
					$cat_url = append_sid('dload.' . PHP_EXT . '?action=category&amp;cat_id=' . $searchset[$i]['cat_id']);
					$file_url = append_sid('dload.' . PHP_EXT . '?action=file&amp;file_id=' . $searchset[$i]['file_id']);
					//===================================================
					// Format the date for the given file
					//===================================================

					$date = create_date_ip($config['default_dateformat'], $searchset[$i]['file_time'], $config['board_timezone']);

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
							//$posticon = '&nbsp;';
						}
						else
						{
							$posticon = '<img src="' . FILES_ICONS_DIR . $searchset[$i]['file_posticon'] . '" alt="" />';
						}
					}
					else
					{
						$posticon = '<img src="' . $images['forum_link'] . '" alt="" />';
					}

					$poster = ($searchset[$i]['user_id'] == ANONYMOUS) ? $lang['Guest'] : colorize_username($searchset[$i]['user_id'], $searchset[$i]['username'], $searchset[$i]['user_color'], $searchset[$i]['user_active']);
					$pafiledb_template->assign_block_vars('searchresults', array(
						'CAT_NAME' => $searchset[$i]['cat_name'],
						'FILE_NEW_IMAGE' => $images['pa_file_new'],
						'PIN_IMAGE' => $posticon,
						'L_HOME' => $lang['Home'],
						'CURRENT_TIME' => sprintf($lang['Current_time'], create_date($config['default_dateformat'], time(), $config['board_timezone'])),
						'XS_NEW' => $xs_new,
						'IS_NEW_FILE' => $is_new,
						'FILE_NAME' => $searchset[$i]['file_name'],
						'FILE_DESC' => $searchset[$i]['file_desc'],
						'FILE_SUBMITER' => $poster,
						'DATE' => $date,
						'RATING' => $rating2,
						'DOWNLOADS' => $searchset[$i]['file_dls'],
						'U_FILE' => $file_url,
						'U_CAT' => $cat_url)
					);
				}
				$base_url = append_sid('dload.' . PHP_EXT . '?action=search&amp;search_id=' . $search_id);

				$pafiledb_template->assign_vars(array(
					'PAGINATION' => generate_pagination($base_url, $total_match_count, $pafiledb_config['settings_file_page'], $start),
					'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / $pafiledb_config['settings_file_page'] ) + 1 ), ceil( $total_match_count / $pafiledb_config['settings_file_page'] )),
					'DOWNLOAD' => $pafiledb_config['settings_dbname'],
					'L_HOME' => $lang['Home'],
					'U_INDEX' => append_sid(CMS_PAGE_HOME),
					'U_DOWNLOAD' => append_sid('dload.' . PHP_EXT),
					'L_HOME' => $lang['Home'],
   				'CURRENT_TIME' => sprintf($lang['Current_time'], create_date($config['default_dateformat'], time(), $config['board_timezone'])),
					'XS_NEW' => $xs_new,
					'L_INDEX' => sprintf($lang['Forum_Index'], htmlspecialchars($config['sitename'])),
					'L_RATE' => $lang['DlRating'],
					'L_DOWNLOADS' => $lang['Dls'],
					'L_DATE' => $lang['Date'],
					'L_NAME' => $lang['Name'],
					'L_FILE' => $lang['File'],
					'L_SUBMITER' => $lang['Submiter'],
					'L_CATEGORY' => $lang['Category'],
					'L_NEW_FILE' => $lang['New_file'])
				);

				$this->display($lang['Download'], 'pa_search_result.tpl');
			}
			else
			{
				message_die(GENERAL_MESSAGE, $lang['No_search_match']);
			}
		}
		if ( !isset($_POST['submit']) || ($search_author == '' && $search_keywords == '' && !$search_id)  )
		{
			$dropmenu = $this->jumpmenu_option();

			$pafiledb_template->assign_vars(array(
				'S_SEARCH_ACTION' => append_sid('dload.php'),
				'S_CAT_MENU' => $dropmenu,

				'DOWNLOAD' => $pafiledb_config['settings_dbname'],

				'U_INDEX' => append_sid(CMS_PAGE_HOME),
				'U_DOWNLOAD' => append_sid('dload.' . PHP_EXT),
				'L_HOME' => $lang['Home'],
   			'CURRENT_TIME' => sprintf($lang['Current_time'], create_date($config['default_dateformat'], time(), $config['board_timezone'])),
				'XS_NEW' => $xs_new,
				'L_YES' => $lang['Yes'],
				'L_NO' => $lang['No'],
				'L_SEARCH_OPTIONS' => $lang['Search_options'],
				'L_SEARCH_KEYWORDS' => $lang['Search_keywords'],
				'L_SEARCH_KEYWORDS_EXPLAIN' => $lang['Search_keywords_explain'],
				'L_SEARCH_AUTHOR' => $lang['Search_author'],
				'L_SEARCH_AUTHOR_EXPLAIN' => $lang['Search_author_explain'],
				'L_SEARCH_ANY_TERMS' => $lang['Search_for_any'],
				'L_SEARCH_ALL_TERMS' => $lang['Search_for_all'],
				'L_INCLUDE_COMMENTS' => $lang['Include_comments'],
				'L_SORT_BY' => $lang['Select_sort_method'],
				'L_SORT_DIR' => $lang['Order'],
				'L_SORT_ASCENDING' => $lang['Sort_Ascending'],
				'L_SORT_DESCENDING' => $lang['Sort_Descending'],
				'L_INDEX' => sprintf($lang['Forum_Index'], htmlspecialchars($config['sitename'])),
				'L_RATING' => $lang['DlRating'],
				'L_DOWNLOADS' => $lang['Dls'],
				'L_DATE' => $lang['Date'],
				'L_NAME' => $lang['Name'],
				'L_UPDATE_TIME' => $lang['Update_time'],
				'L_SEARCH' => $lang['Search'],
				'L_SEARCH_FOR' => $lang['Search_for'],
				'L_ALL' => $lang['All'],
				'L_CHOOSE_CAT' => $lang['Choose_cat'])
			);
			$this->display($lang['Download'], 'pa_search_body.tpl');
		}
	}
}

?>