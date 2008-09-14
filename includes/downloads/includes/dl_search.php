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
}

/*
* define initial search vars
*/
if ( isset($_POST['search_keywords']) || isset($_GET['search_keywords']) )
{
	$search_keywords = ( isset($_POST['search_keywords']) ) ? htmlspecialchars($_POST['search_keywords']) : htmlspecialchars($_GET['search_keywords']);
}
else
{
	$search_keywords = '';
}

if ( isset($_POST['search_cat']) || isset($_GET['search_cat']) )
{
	$search_cat = ( isset($_POST['search_cat']) ) ? intval($_POST['search_cat']) : intval($_GET['search_cat']);
}
else
{
	$search_cat = '-1';
}

if ( isset($_POST['sort_dir']) || isset($_GET['sort_dir']) )
{
	if ( isset($_POST['sort_dir']) )
	{
		$sort_dir = ( $_POST['sort_dir'] == 'DESC' ) ? 'DESC' : 'ASC';
	}

	if ( isset($_GET['sort_dir']) )
	{
		$sort_dir = ( $_GET['sort_dir'] == 'DESC' ) ? 'DESC' : 'ASC';
	}

}
else
{
	$sort_dir =  'ASC';
}

if ( isset($_POST['search_fields']) || isset($_GET['search_fields']) )
{
	$search_in_fields = ( isset($_POST['search_fields']) ) ? $_POST['search_fields'] : $_GET['search_fields'];
}
else
{
	$search_in_fields = 'all';
}

if ( isset($_POST['search_author']) || isset($_GET['search_author']) )
{
	$search_author = ( isset($_POST['search_author']) ) ? $_POST['search_author'] : $_GET['search_author'];
}
else
{
	$search_author = '';
}


$search_fnames = array($lang['Dl_all'], $lang['Dl_file_name'], $lang['Dl_file_description'], $lang['Dl_detail']);
$search_fields = array('all', 'file_name', 'description', 'long_desc');

/*
* search for keywords if entered
*/
if ($search_keywords != '' && !$search_author)
{
	$template->set_filenames(array('body' => 'dl_search_results.tpl'));

	$search_keywords = strtolower($search_keywords);
	$search_keywords = str_replace('sql', '', trim($search_keywords));
	$search_keywords = str_replace('union', '', $search_keywords);
	$search_keywords = str_replace('  ', ' ', $search_keywords);
	$search_keywords = str_replace(' ', '%', $search_keywords);
	$search_keywords = str_replace('*', '', $search_keywords);
	$search_keywords = str_replace('?', '', $search_keywords);

	$access_cats = array();
	$access_cats = $dl_mod->full_index(0, 0, 0, 1);
	$sql_access_cats = ($userdata['user_level'] == ADMIN) ? '' : ' AND cat IN (' . implode(',', $access_cats) . ') ';
	$sql_access_dls = ($userdata['user_level'] == ADMIN) ? '' : ' AND d.cat IN (' . implode(',', $access_cats) . ') ';

	$sql_cat = ($search_cat == -1) ? '' : ' AND cat = ' . $search_cat;
	$sql_cat_count = ($search_cat == -1) ? '' : ' AND cat = ' . $search_cat;

	$search_keywords = str_replace("\'", "''", $search_keywords);

	switch($search_in_fields)
	{
		case 'all':
			$sql_where = " AND (d.file_name LIKE ('%$search_keywords%')
					OR d.description LIKE ('%$search_keywords%')
					OR d.long_desc LIKE ('%$search_keywords%'))";
			$sql_where_count = " AND (file_name LIKE ('%$search_keywords%')
					OR description LIKE ('%$search_keywords%')
					OR long_desc LIKE ('%$search_keywords%'))";
			break;
		case 'file_name':
		case 'description':
		case 'long_desc':
			$sql_where = " AND d.$search_in_fields LIKE ('%$search_keywords%')";
			$sql_where_count = " AND $search_in_fields LIKE ('%$search_keywords%')";
			break;
		default:
			message_die(GENERAL_ERROR, 'Could not query search for wrong data fields', '', __LINE__, __FILE__, $sql);
	}

	$sql = "SELECT id FROM ".DOWNLOADS_TABLE."
		WHERE approve = " . TRUE . "
		$sql_access_cats
		$sql_cat_count
		$sql_where_count";
	if ( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'Could not read downloads data', '', __LINE__, __FILE__, $sql);
	}

	$total_found_dl = $db->sql_numrows($result);
	$db->sql_freeresult($result);

	$pagination = generate_pagination('downloads.' . PHP_EXT . '?view=search&search_keywords=' . $search_keywords . '&search_cat=' . $search_cat . '&sort_dir=' . $sort_dir, $total_found_dl, $board_config['topics_per_page'], $start);

	$template->assign_vars(array(
		'PAGINATION' => $pagination,
		'L_SEARCH_TITLE' => $lang['Download'] . ' ' . $lang['Search'],
		'L_STATUS' => $lang['Dl_info'],
		'L_CATEGORY' => $lang['Dl_cat_name'],
		'L_DESCRIPTION' => $lang['Dl_file_description'],
		'L_FILENAME' => $lang['Dl_file_name'],
		'L_LONG_DESC' => $lang['Dl_detail'],
		'L_NAV1' => $lang['Dl_cat_title'],
		'L_NAV2' => $lang['Search'] . ' ' . $lang['Downloads'],
		'U_NAV1' => append_sid('downloads.' . PHP_EXT),
		'U_NAV2' => append_sid('downloads.' . PHP_EXT . '?view=search')
		)
	);

	if ($total_found_dl == 0)
	{
		$template->assign_block_vars('no_searchresults', array(
			'L_NO_RESULTS' => $lang['No_search_match'])
		);
	}
	else
	{
		$sql = "SELECT d.*, c.cat_name FROM ".DOWNLOADS_TABLE." d, ".DL_CAT_TABLE." c
			WHERE d.cat = c.id
				AND d.approve = " . TRUE . "
				$sql_access_dls
				$sql_cat
				$sql_where
			ORDER BY sort $sort_dir
			LIMIT $start, " . $board_config['topics_per_page'];
		if ( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not read downloads data', '', __LINE__, __FILE__, $sql);
		}

		$i = 0;
		while ( $row = $db->sql_fetchrow($result) )
		{
			$cat_id = $row['cat'];
			$description = $row['description'];
			$file_id = $row['id'];
			$u_file_link = append_sid('downloads.' . PHP_EXT . '?view=detail&amp;df_id=' . $file_id);

			$dl_status = array();
			$dl_status = $dl_mod->dl_status($file_id);

			$status = $dl_status['status'];
			$file_name = $dl_status['file_name'];

			$mini_icon = $dl_mod->mini_status_file($index[$cat_id]['parent'], $file_id);

			$cat_name = $row['cat_name'];
			$u_cat_link = append_sid('downloads.' . PHP_EXT . "?cat=" . $cat_id);

			$long_desc = make_clickable(smilies_pass(bbencode_second_pass(stripslashes($row['long_desc']), $row['bbcode_uid'])));
			$long_desc = str_replace("\n", "\n<br />\n", $long_desc);

			$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

			$template->assign_block_vars('searchresults', array(
				'ROW_CLASS' => $row_class,
				'STATUS' => $status,
				'CAT_NAME' => $cat_name,
				'U_CAT_LINK' => $u_cat_link,
				'DESCRIPTION' => $description,
				'MINI_ICON' => $mini_icon,
				'U_FILE_LINK' => $u_file_link,
				'FILE_NAME' => $file_name,
				'LONG_DESC' => $long_desc
				)
			);
			$i++;
		}
	}
}
elseif ($search_author)
{
	$template->set_filenames(array('body' => 'dl_search_results.tpl'));

	$search_author = str_replace('sql', '', $search_author);
	$search_author = str_replace('union', '', $search_author);
	$search_author = str_replace('*', '%', trim($search_author));
	$search_author = phpbb_clean_username($search_author);

	$sql_cat = ($search_cat == -1) ? '' : ' AND cat = ' . $search_cat;
	$sql_cat_count = ($search_cat == -1) ? '' : ' AND cat = ' . $search_cat;

	$sql = "SELECT user_id FROM " . USERS_TABLE . "
		WHERE username LIKE '" . str_replace("\'", "''", $search_author) . "'";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, "Couldn't obtain list of matching users (searching for: $search_author)", "", __LINE__, __FILE__, $sql);
	}

	$total_users = $db->sql_numrows($result);
	if ($total_users)
	{
		$matching_userids = '0';
		while ($row = $db->sql_fetchrow($result))
		{
			$matching_userids .= ', ' . $row['user_id'];
		}

		$db->sql_freeresult($result);
	}
	else
	{
		$db->sql_freeresult($result);
		message_die(GENERAL_MESSAGE, $lang['No_search_match']);
	}

	$access_cats = array();
	$access_cats = $dl_mod->full_index(0, 0, 0, 1);
	$sql_access_cats = ($userdata['user_level'] == ADMIN) ? '' : ' AND cat IN (' . implode(',', $access_cats) . ') ';
	$sql_access_dls = ($userdata['user_level'] == ADMIN) ? '' : ' AND d.cat IN (' . implode(',', $access_cats) . ') ';

	$sql = "SELECT id FROM ".DOWNLOADS_TABLE."
		WHERE approve = " . TRUE . "
			$sql_access_cats
			$sql_cat_count
			AND (add_user IN ($matching_userids) OR change_user IN ($matching_userids))";
	if ( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'Could not read downloads data', '', __LINE__, __FILE__, $sql);
	}

	$total_found_dl = $db->sql_numrows($result);
	$db->sql_freeresult($result);

	$pagination = generate_pagination('downloads.' . PHP_EXT . '?view=search&search_author=' . $search_author . '&search_cat=' . $search_cat . '&sort_dir=' . $sort_dir, $total_found_dl, $board_config['topics_per_page'], $start);

	$template->assign_vars(array(
		'PAGINATION' => $pagination,
		'L_SEARCH_TITLE' => $lang['Download'] . ' ' . $lang['Search'],
		'L_STATUS' => $lang['Dl_info'],
		'L_CATEGORY' => $lang['Dl_cat_name'],
		'L_DESCRIPTION' => $lang['Dl_file_description'],
		'L_FILENAME' => $lang['Dl_file_name'],
		'L_LONG_DESC' => $lang['Dl_detail'],
		'L_NAV1' => $lang['Dl_cat_title'],
		'L_NAV2' => $lang['Search'] . ' ' . $lang['Downloads'],
		'U_NAV1' => append_sid('downloads.' . PHP_EXT),
		'U_NAV2' => append_sid('downloads.' . PHP_EXT . '?view=search')
		)
	);

	if ($total_found_dl == 0)
	{
		$template->assign_block_vars('no_searchresults', array(
			'L_NO_RESULTS' => $lang['No_search_match'])
		);
	}
	else
	{
		$sql = "SELECT d.*, c.cat_name FROM ".DOWNLOADS_TABLE." d, ".DL_CAT_TABLE." c
			WHERE d.cat = c.id
				AND d.approve = " . TRUE . "
				$sql_access_dls
				$sql_cat
				AND (d.add_user IN ($matching_userids) OR d.change_user IN ($matching_userids))
			ORDER BY c.cat_name, d.sort $sort_dir
			LIMIT $start, " . $board_config['topics_per_page'];
		if ( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not read downloads data', '', __LINE__, __FILE__, $sql);
		}

		$i = 0;
		while ( $row = $db->sql_fetchrow($result) )
		{
			$cat_id = $row['cat'];
			$description = $row['description'];
			$file_id = $row['id'];
			$u_file_link = append_sid('downloads.' . PHP_EXT . '?view=detail&amp;df_id=' . $file_id);

			$dl_status = array();
			$dl_status = $dl_mod->dl_status($file_id);

			$status = $dl_status['status'];
			$file_name = $dl_status['file_name'];

			$mini_icon = $dl_mod->mini_status_file($index[$cat_id]['parent'], $file_id);

			$cat_name = $row['cat_name'];
			$u_cat_link = append_sid('downloads.' . PHP_EXT . "?cat=" . $cat_id);

			$long_desc = make_clickable(smilies_pass(bbencode_second_pass(stripslashes($row['long_desc']), $row['bbcode_uid'])));
			$long_desc = str_replace("\n", "\n<br />\n", $long_desc);

			$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

			$template->assign_block_vars('searchresults', array(
				'ROW_CLASS' => $row_class,
				'STATUS' => $status,
				'CAT_NAME' => $cat_name,
				'U_CAT_LINK' => $u_cat_link,
				'DESCRIPTION' => $description,
				'MINI_ICON' => $mini_icon,
				'U_FILE_LINK' => $u_file_link,
				'FILE_NAME' => $file_name,
				'LONG_DESC' => $long_desc)
			);
			$i++;
		}
	}
}
else
{
	/*
	* default entry point of download searching
	*/
	$select_categories = '<select name="search_cat"><option value="-1">' . $lang['Dl_all'] . '</option>';
	$select_categories .= $dl_mod->dl_dropdown(0, 0, 0, 'auth_view');
	$select_categories .= '</select>';

	$s_sort_dir = '<select name="sort_dir">';
	if($sort_dir == 'ASC')
	{
		$s_sort_dir .= '<option value="ASC" selected="selected">' . $lang['Sort_Ascending'] . '</option><option value="DESC">' . $lang['Sort_Descending'] . '</option>';
	}
	else
	{
		$s_sort_dir .= '<option value="ASC">' . $lang['Sort_Ascending'] . '</option><option value="DESC" selected="selected">' . $lang['Sort_Descending'] . '</option>';
	}
	$s_sort_dir .= '</select>';

	$s_search_fields = '<select name="search_fields">';
	for ($i = 0; $i < count($search_fields); $i++)
	{
		$s_search_fields .= '<option value="'.$search_fields[$i].'">'.$search_fnames[$i].'</option>';
	}
	$s_search_fields .= '</select>';

	$template->set_filenames(array('body' => 'dl_search_body.tpl'));

	$template->assign_vars(array(
		'L_SEARCH_QUERY' => $lang['Search'] . ' ' . $lang['Downloads'],
		'L_SEARCH_OPTIONS' => $lang['Search_options'],
		'L_SEARCH_KEYWORDS' => $lang['Search_keywords'],
		'L_CATEGORY' => $lang['Category'],
		'L_SORT_BY' => $lang['Options'],
		'L_SORT_DIR' => $lang['Order'],
		'L_SEARCH' => $lang['Search'],
		'L_SEARCH' => $lang['Search'],
		'L_SEARCH_AUTHOR' => $lang['Dl_search_author'],

		'S_SEARCH_ACTION' => append_sid('downloads.' . PHP_EXT . '?view=search'),
		'S_CATEGORY_OPTIONS' => $select_categories,
		'S_SORT_ORDER' => $s_sort_dir,
		'S_SORT_OPTIONS' => $s_search_fields,
		'L_NAV1' => $lang['Dl_cat_title'],
		'L_NAV2' => $lang['Search'] . ' ' . $lang['Downloads'],
		'U_NAV1' => append_sid('downloads.' . PHP_EXT),
		'U_NAV2' => append_sid('downloads.' . PHP_EXT . '?view=search')
		)
	);
}

?>