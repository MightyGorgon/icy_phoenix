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
* masterdavid - Ronald John David
*
*/

if (!defined('IN_PHPBB'))
{
	die('Hacking attempt');
}

if(!function_exists(imp_album_block_func))
{
	function imp_album_block_func()
	{
		global $template, $phpbb_root_path, $phpEx, $db, $board_config, $lang, $images, $userdata;
		global $head_foot_ext, $cms_global_blocks, $cms_page_id, $cms_config_vars, $block_id;

		/*
		$cms_page_id = '12';
		$cms_page_name = 'album';
		*/
		$auth_level_req = $board_config['auth_view_album'];
		if ($auth_level_req > AUTH_ALL)
		{
			if (($auth_level_req == AUTH_REG) && (!$userdata['session_logged_in']))
			{
				return;
			}
			if ($userdata['user_level'] != ADMIN)
			{
				if ($auth_level_req == AUTH_ADMIN)
				{
					return;
				}
				if (($auth_level_req == AUTH_MOD) && ($userdata['user_level'] != MOD))
				{
					return;
				}
			}
		}

		if (!defined('IMG_THUMB'))
		{
			define('IMG_THUMB', true);
		}

		$template->_tpldata['recent_pics.'] = array();
		//reset($template->_tpldata['recent_pics.']);
		$template->_tpldata['recent_details.'] = array();
		//reset($template->_tpldata['recent_details.']);
		$template->_tpldata['no_pics'] = array();
		//reset($template->_tpldata['no_pics.']);

		/*
		echo($cms_config_vars['md_pics_all'][$block_id] . '<br />');
		echo($cms_config_vars[$block_id . '_' . 'md_pics_all']);
		exit;
		*/
		$album_root_path = $phpbb_root_path . ALBUM_MOD_PATH . '';
		include_once($album_root_path . 'album_common.' . $phpEx);
		global $album_config;
		include_once($phpbb_root_path . 'includes/functions_groups.' . $phpEx);

		$sql = "SELECT c.*, COUNT(p.pic_id) AS count
				FROM " . ALBUM_CAT_TABLE . " AS c
					LEFT JOIN " . ALBUM_TABLE . " AS p ON c.cat_id = p.pic_cat_id
				" . (($cms_config_vars['md_pics_all'][$block_id] == '1') ? '' : 'WHERE cat_user_id = 0') . "
				GROUP BY cat_id
				ORDER BY cat_order ASC";
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not query categories list', '', __LINE__, __FILE__, $sql);
		}
		$catrows = array();
		while($row = $db->sql_fetchrow($result))
		{
			$album_user_access = album_user_access($row['cat_id'], $row, 1, 0, 0, 0, 0, 0); // VIEW
			if ($album_user_access['view'] == 1)
			{
				$catrows[] = $row;
			}
		}
		$db->sql_freeresult($result);
		if ($cms_config_vars['md_pics_all'][$block_id] == '1')
		{
			$allowed_cat = '0'; // For Recent Public Pics below
		}
		else
		{
			$allowed_cat = '';
		}

		// $catrows now stores all categories which this user can view. Dump them out!
		for ($i = 0; $i < count($catrows); $i++)
		{
			// Build allowed category-list (for recent pics after here)
			$allowed_cat .= ($allowed_cat == '') ? $catrows[$i]['cat_id'] : ',' . $catrows[$i]['cat_id'];

			// Get Last Pic of this Category
			if ($catrows[$i]['count'] == 0)
			{
				//
				// Oh, this category is empty
				//
				$last_pic_info = $lang['No_Pics'];
				$u_last_pic = '';
				$last_pic_title = '';
			}
			else
			{
				// Check Pic Approval
				if (($catrows[$i]['cat_approval'] == ALBUM_ADMIN) || ($catrows[$i]['cat_approval'] == ALBUM_MOD))
				{
					$pic_approval_sql = 'AND p.pic_approval = 1'; // Pic Approval ON
				}
				else
				{
					$pic_approval_sql = ''; // Pic Approval OFF
				}
			}
		}

		if ($cms_config_vars['md_pics_all'][$block_id] == '1')
		{
			$pics_allowed = '0';
		}
		else
		{
			$pics_allowed = '';
		}

		if ($allowed_cat != $pics_allowed)
		{
			$category_id = $cms_config_vars['md_cat_id'][$block_id];

			if ($cms_config_vars['md_pics_sort'][$block_id] == '1')
			{
				if ($category_id != 0)
				{
					$sql = "SELECT p.*, u.user_id, u.username, r.rate_pic_id, AVG(r.rate_point) AS rating, COUNT(DISTINCT c.comment_id) AS comments
						FROM " . ALBUM_TABLE . " AS p
							LEFT JOIN " . USERS_TABLE . " AS u ON p.pic_user_id = u.user_id
							LEFT JOIN " . ALBUM_CAT_TABLE . " AS ct ON p.pic_cat_id = ct.cat_id
							LEFT JOIN " . ALBUM_RATE_TABLE . " AS r ON p.pic_id = r.rate_pic_id
							LEFT JOIN " . ALBUM_COMMENT_TABLE . " AS c ON p.pic_id = c.comment_pic_id
						WHERE p.pic_cat_id IN ($allowed_cat) AND (p.pic_approval = 1 OR ct.cat_approval = 0) AND pic_cat_id IN ($category_id)
						GROUP BY p.pic_id
						ORDER BY RAND()
						LIMIT " . $cms_config_vars['md_pics_number'][$block_id];
				}
				else
				{
					$sql = "SELECT p.*, u.user_id, u.username, r.rate_pic_id, AVG(r.rate_point) AS rating, COUNT(DISTINCT c.comment_id) AS comments
						FROM " . ALBUM_TABLE . " AS p
							LEFT JOIN " . USERS_TABLE . " AS u ON p.pic_user_id = u.user_id
							LEFT JOIN " . ALBUM_CAT_TABLE . " AS ct ON p.pic_cat_id = ct.cat_id
							LEFT JOIN " . ALBUM_RATE_TABLE . " AS r ON p.pic_id = r.rate_pic_id
							LEFT JOIN " . ALBUM_COMMENT_TABLE . " AS c ON p.pic_id = c.comment_pic_id
						WHERE p.pic_cat_id IN ($allowed_cat) AND (p.pic_approval = 1 OR ct.cat_approval = 0)
						GROUP BY p.pic_id
						ORDER BY RAND()
						LIMIT " . $cms_config_vars['md_pics_number'][$block_id];
					}
			}
			elseif ($cms_config_vars['md_pics_sort'][$block_id] == '0')
			{
				if ($category_id != 0)
				{
					$sql = "SELECT p.*, u.user_id, u.username, r.rate_pic_id, AVG(r.rate_point) AS rating, COUNT(DISTINCT c.comment_id) AS comments
						FROM " . ALBUM_TABLE . " AS p
							LEFT JOIN " . USERS_TABLE . " AS u ON p.pic_user_id = u.user_id
							LEFT JOIN " . ALBUM_CAT_TABLE . " AS ct ON p.pic_cat_id = ct.cat_id
							LEFT JOIN " . ALBUM_RATE_TABLE . " AS r ON p.pic_id = r.rate_pic_id
							LEFT JOIN " . ALBUM_COMMENT_TABLE . " AS c ON p.pic_id = c.comment_pic_id
						WHERE p.pic_cat_id IN ($allowed_cat) AND (p.pic_approval = 1 OR ct.cat_approval = 0) AND pic_cat_id IN ($category_id)
						GROUP BY p.pic_id
						ORDER BY pic_time DESC
						LIMIT " . $cms_config_vars['md_pics_number'][$block_id];
				}
				else
				{
					$sql = "SELECT p.*, u.user_id, u.username, r.rate_pic_id, AVG(r.rate_point) AS rating, COUNT(DISTINCT c.comment_id) AS comments
						FROM " . ALBUM_TABLE . " AS p
							LEFT JOIN " . USERS_TABLE . " AS u ON p.pic_user_id = u.user_id
							LEFT JOIN " . ALBUM_CAT_TABLE . " AS ct ON p.pic_cat_id = ct.cat_id
							LEFT JOIN " . ALBUM_RATE_TABLE . " AS r ON p.pic_id = r.rate_pic_id
							LEFT JOIN " . ALBUM_COMMENT_TABLE . " AS c ON p.pic_id = c.comment_pic_id
						WHERE p.pic_cat_id IN ($allowed_cat) AND (p.pic_approval = 1 OR ct.cat_approval = 0)
						GROUP BY p.pic_id
						ORDER BY pic_time DESC
						LIMIT " . $cms_config_vars['md_pics_number'][$block_id];
				}
			}
			if (!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'Could not query recent pics information', '', __LINE__, __FILE__, $sql);
			}
			$recentrow = array();

			while($row = $db->sql_fetchrow($result))
			{
				$recentrow[] = $row;
			}
			$db->sql_freeresult($result);

			$total_pics = count($recentrow);
			if ($total_pics > 0)
			{
				$total_rows = ceil($total_pics / $cms_config_vars['md_pics_rows_number'][$block_id]);
				$total_cols = ceil($total_pics / $cms_config_vars['md_pics_cols_number'][$block_id]);
				$image_counter = 0;

				while($image_counter < $total_pics)
				{
					for ($i = 0; $i < $cms_config_vars['md_pics_rows_number'][$block_id]; $i++)
					{
						$template->assign_block_vars('recent_pics', array());

						for ($j = 0; $j < $cms_config_vars['md_pics_cols_number'][$block_id]; $j++)
						{
							if ($image_counter >= $total_pics)
							{
								$template->assign_block_vars('recent_pics.recent_no_detail', array());
							}
							else
							{
								if (!$recentrow[$image_counter]['rating'])
								{
									$recentrow[$image_counter]['rating'] = $lang['Not_rated'];
								}
								else
								{
									$recentrow[$image_counter]['rating'] = round($recentrow[$image_counter]['rating'], 2);
								}

								if(($recentrow[$image_counter]['user_id'] == ALBUM_GUEST) || ($recentrow[$image_counter]['username'] == ''))
								{
									$recent_poster = ($recentrow[$image_counter]['pic_username'] == '') ? $lang['Guest'] : $recentrow[$image_counter]['pic_username'];
								}
								else
								{
									$recent_poster = colorize_username($recentrow[$image_counter]['user_id']);
								}

								$thumbnail_file = append_sid(album_append_uid('album_thumbnail.' . $phpEx . '?pic_id=' . $recentrow[$image_counter]['pic_id']));
								if (($album_config['thumbnail_cache'] == true) && ($album_config['quick_thumbs'] == true))
								{
									$pic_filename = $recentrow[$image_counter]['pic_filename'];
									$file_part = explode('.', strtolower($pic_filename));
									$pic_filetype = $file_part[count($file_part) - 1];
									$pic_title = substr($pic_filename, 0, strlen($pic_filename) - strlen($pic_filetype) - 1);
									//$pic_filetype = strtolower(substr($pic_filename, strlen($pic_filename) - 4, 4));
									//$pic_title = ucfirst(substr($pic_filename, 0, strlen($pic_filename) - 4));
									$pic_thumbnail = ($recentrow[$image_counter]['pic_thumbnail'] == '') ? md5($pic_filename) . '.' . $pic_filetype : $recentrow[$image_counter]['pic_thumbnail'];
									//$pic_thumbnail = ($recentrow[$image_counter]['pic_thumbnail'] == '') ? $pic_filename : $recentrow[$image_counter]['pic_thumbnail'];
									$pic_thumbnail_fullpath = ALBUM_CACHE_PATH . $pic_thumbnail;
									if (file_exists($pic_thumbnail_fullpath))
									{
										$thumbnail_file = $pic_thumbnail_fullpath;
									}
								}

								$template->assign_block_vars('recent_pics.recent_detail', array(
									'U_PIC' => ($album_config['fullpic_popup']) ? append_sid('album_pic.' . $phpEx . '?pic_id=' . $recentrow[$image_counter]['pic_id']) : append_sid('album_showpage.' . $phpEx . '?pic_id=' . $recentrow[$image_counter]['pic_id']),
									'THUMBNAIL' => $thumbnail_file,
									'DESC' => $recentrow[$image_counter]['pic_desc'],
									'TITLE' => $recentrow[$image_counter]['pic_title'],
									'POSTER' => $recent_poster,
									'TIME' => create_date2($board_config['default_dateformat'], $recentrow[$image_counter]['pic_time'], $board_config['board_timezone']),
									'VIEW' => $recentrow[$image_counter]['pic_view_count'],
									'RATING' => ($album_config['rate'] == 1) ? ($lang['Rating'] . ': ' . $recentrow[$image_counter]['rating'] . '<br />') : '',
									'COMMENTS' => ($album_config['comment'] == 1) ? ($lang['Comments'] . ': ' . $recentrow[$image_counter]['comments'] . '<br />') : '')
								);
							}

							$image_counter++;
						}
					}
				}
			}
			else
			{
				// No Pics Found
				$template->assign_block_vars('no_pics', array());
			}
		}
		else
		{
			// No Cats Found
			$template->assign_block_vars('no_pics', array());
		}

		$template->assign_vars(array(
			//'S_COL_WIDTH' => (100 / $cms_config_vars['md_pics_number'][$block_id]) . '%',
			'S_COL_WIDTH' => (100 / (($cms_config_vars['md_pics_cols_number'][$block_id] == 0) ? 4 : $cms_config_vars['md_pics_cols_number'][$block_id])) . '%',
			'TARGET_BLANK' => ($album_config['fullpic_popup']) ? 'target="_blank"' : '',
			'L_NO_PICS' => $lang['No_Pics'],
			'L_PIC_TITLE' => $lang['Pic_Title'],
			'L_VIEW' => $lang['View'],
			'L_POSTER' => $lang['Poster'],
			'L_POSTED' => $lang['Posted'],
			'U_ALBUM' => append_sid('album.' . $phpEx),
			'L_ALBUM' => $lang['Album']
			)
		);

	}
}

imp_album_block_func();

?>