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

define('IN_ICYPHOENIX', true);

if (!empty($setmodules))
{
	$filename = basename(__FILE__);
	$module['1900_Attachments']['100_Control_Panel'] = $filename;
	return;
}

// Load default Header
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('pagestart.' . PHP_EXT);

if (!intval($config['allow_ftp_upload']))
{
	if (($config['upload_dir'][0] == '/') || (($config['upload_dir'][0] != '/') && ($config['upload_dir'][1] == ':')))
	{
		$upload_dir = $config['upload_dir'];
	}
	else
	{
		$upload_dir = '../' . $config['upload_dir'];
	}
}
else
{
	$upload_dir = $config['download_path'];
}

// Init Variables
$start = request_var('start', 0);
$sort_order = request_var('order', 'ASC');
$sort_order = ($sort_order == 'ASC') ? 'ASC' : 'DESC';
$mode = request_var('mode', '');
$view = request_var('view', '');
$uid = (isset($_POST['u_id'])) ? request_var('u_id', 0) : request_var('uid', 0);

$view = (isset($_POST['search']) && $_POST['search']) ? 'attachments' : $view;

// process modes based on view
if ($view == 'username')
{
	$mode_types_text = array($lang['SORT_USERNAME'], $lang['Sort_Attachments'], $lang['Sort_Size']);
	$mode_types = array('username', 'attachments', 'filesize');

	if (!$mode)
	{
		$mode = 'attachments';
		$sort_order = 'DESC';
	}
}
elseif ($view == 'attachments')
{
	$mode_types_text = array($lang['Sort_Filename'], $lang['Sort_Comment'], $lang['Sort_Extension'], $lang['Sort_Size'], $lang['Sort_Downloads'], $lang['Sort_Posttime'], /* $lang['Sort_Posts'] */);
	$mode_types = array('real_filename', 'comment', 'extension', 'filesize', 'downloads', 'post_time' /* , 'posts' */);

	if (!$mode)
	{
		$mode = 'real_filename';
		$sort_order = 'ASC';
	}
}
elseif ($view == 'search')
{
	$mode_types_text = array($lang['Sort_Filename'], $lang['Sort_Comment'], $lang['Sort_Extension'], $lang['Sort_Size'], $lang['Sort_Downloads'], $lang['Sort_Posttime'], /* $lang['Sort_Posts'] */);
	$mode_types = array('real_filename', 'comment', 'extension', 'filesize', 'downloads', 'post_time' /* , 'posts'*/);

	$sort_order = 'DESC';
}
else
{
	$view = 'stats';
	$mode_types_text = array();
	$sort_order = 'ASC';
}


// Pagination ?
$do_pagination = ($view != 'stats' && $view != 'search') ? true : false;

// Set Order
$order_by = '';

if ($view == 'username')
{
	switch ($mode)
	{
		case 'username':
			$order_by = 'ORDER BY u.username ' . $sort_order . ' LIMIT ' . $start . ', ' . $config['topics_per_page'];
		break;

		case 'attachments':
			$order_by = 'ORDER BY total_attachments ' . $sort_order . ' LIMIT ' . $start . ', ' . $config['topics_per_page'];
		break;

		case 'filesize':
			$order_by = 'ORDER BY total_size ' . $sort_order . ' LIMIT ' . $start . ', ' . $config['topics_per_page'];
		break;

		default:
			$mode = 'attachments';
			$sort_order = 'DESC';
			$order_by = 'ORDER BY total_attachments ' . $sort_order . ' LIMIT ' . $start . ', ' . $config['topics_per_page'];
		break;
	}
}
else if ($view == 'attachments')
{
	switch ($mode)
	{
		case 'filename':
			$order_by = 'ORDER BY a.real_filename ' . $sort_order . ' LIMIT ' . $start . ', ' . $config['topics_per_page'];
		break;

		case 'comment':
			$order_by = 'ORDER BY a.comment ' . $sort_order . ' LIMIT ' . $start . ', ' . $config['topics_per_page'];
		break;

		case 'extension':
			$order_by = 'ORDER BY a.extension ' . $sort_order . ' LIMIT ' . $start . ', ' . $config['topics_per_page'];
		break;

		case 'filesize':
			$order_by = 'ORDER BY a.filesize ' . $sort_order . ' LIMIT ' . $start . ', ' . $config['topics_per_page'];
		break;

		case 'downloads':
			$order_by = 'ORDER BY a.download_count ' . $sort_order . ' LIMIT ' . $start . ', ' . $config['topics_per_page'];
		break;

		case 'post_time':
			$order_by = 'ORDER BY a.filetime ' . $sort_order . ' LIMIT ' . $start . ', ' . $config['topics_per_page'];
		break;

		default:
			$mode = 'a.real_filename';
			$sort_order = 'ASC';
			$order_by = 'ORDER BY a.real_filename ' . $sort_order . ' LIMIT ' . $start . ', ' . $config['topics_per_page'];
		break;
	}
}

// Set select fields
$view_types_text = array($lang['View_Statistic'], $lang['View_Search'], $lang['View_Username'], $lang['View_Attachments']);
$view_types = array('stats', 'search', 'username', 'attachments');

$select_view = '<select name="view">';

for($i = 0; $i < sizeof($view_types_text); $i++)
{
	$selected = ($view == $view_types[$i]) ? ' selected="selected"' : '';
	$select_view .= '<option value="' . $view_types[$i] . '"' . $selected . '>' . $view_types_text[$i] . '</option>';
}
$select_view .= '</select>';

if (sizeof($mode_types_text) > 0)
{
	$select_sort_mode = '<select name="mode">';

	for($i = 0; $i < sizeof($mode_types_text); $i++)
	{
		$selected = ($mode == $mode_types[$i]) ? ' selected="selected"' : '';
		$select_sort_mode .= '<option value="' . $mode_types[$i] . '"' . $selected . '>' . $mode_types_text[$i] . '</option>';
	}
	$select_sort_mode .= '</select>';
}

$select_sort_order = '<select name="order">';
if ($sort_order == 'ASC')
{
	$select_sort_order .= '<option value="ASC" selected="selected">' . $lang['Sort_Ascending'] . '</option><option value="DESC">' . $lang['Sort_Descending'] . '</option>';
}
else
{
	$select_sort_order .= '<option value="ASC">' . $lang['Sort_Ascending'] . '</option><option value="DESC" selected="selected">' . $lang['Sort_Descending'] . '</option>';
}
$select_sort_order .= '</select>';

$submit_change = (isset($_POST['submit_change'])) ? true : false;
$delete = (isset($_POST['delete'])) ? true : false;
$delete_id_list = request_var('delete_id_list', array(0));

$confirm = ($_POST['confirm']) ? true : false;

if ($confirm && (sizeof($delete_id_list) > 0))
{
	$attachments = array();

	delete_attachment(0, $delete_id_list);
}
else if ($delete && sizeof($delete_id_list) > 0)
{
	// Not confirmed, show confirmation message
	$hidden_fields = '<input type="hidden" name="view" value="' . $view . '" />';
	$hidden_fields .= '<input type="hidden" name="mode" value="' . $mode . '" />';
	$hidden_fields .= '<input type="hidden" name="order" value="' . $sort_order . '" />';
	$hidden_fields .= '<input type="hidden" name="u_id" value="' . $uid . '" />';
	$hidden_fields .= '<input type="hidden" name="start" value="' . $start . '" />';

	for ($i = 0; $i < sizeof($delete_id_list); $i++)
	{
		$hidden_fields .= '<input type="hidden" name="delete_id_list[]" value="' . $delete_id_list[$i] . '" />';
	}

	$template->set_filenames(array('confirm' => ADM_TPL . 'confirm_body.tpl'));

	$template->assign_vars(array(
		'MESSAGE_TITLE' => $lang['Confirm'],
		'MESSAGE_TEXT' => $lang['Confirm_delete_attachments'],

		'L_YES' => $lang['Yes'],
		'L_NO' => $lang['No'],

		'S_CONFIRM_ACTION' => append_sid('admin_attach_cp.' . PHP_EXT),
		'S_HIDDEN_FIELDS' => $hidden_fields
		)
	);

	$template->pparse('confirm');

	include('page_footer_admin.' . PHP_EXT);

	exit;
}

// Assign Default Template Vars
$template->assign_vars(array(
	'L_VIEW' => $lang['View'],
	'L_SUBMIT' => $lang['Submit'],
	'L_CONTROL_PANEL_TITLE' => $lang['Control_panel_title'],
	'L_CONTROL_PANEL_EXPLAIN' => $lang['Control_panel_explain'],

	'S_VIEW_SELECT'	=> $select_view,
	'S_MODE_ACTION'	=> append_sid('admin_attach_cp.' . PHP_EXT)
	)
);

if ($submit_change && ($view == 'attachments'))
{
	$attach_change_list = request_var('attach_id_list', array(0));
	$attach_comment_list = request_var('attach_comment_list', array(''));
	$attach_download_count_list = request_var('attach_count_list', array(0));

	// Generate correct Change List
	$attachments = array();

	for ($i = 0; $i < sizeof($attach_change_list); $i++)
	{
		$attachments['_' . $attach_change_list[$i]]['comment'] = $attach_comment_list[$i];
		$attachments['_' . $attach_change_list[$i]]['download_count'] = $attach_download_count_list[$i];
	}

	$sql = 'SELECT *
		FROM ' . ATTACHMENTS_DESC_TABLE . '
		ORDER BY attach_id';
	$result = $db->sql_query($sql);

	while ($attachrow = $db->sql_fetchrow($result))
	{
		if (isset($attachments['_' . $attachrow['attach_id']]))
		{
			if ($attachrow['comment'] != $attachments['_' . $attachrow['attach_id']]['comment'] || $attachrow['download_count'] != $attachments['_' . $attachrow['attach_id']]['download_count'])
			{
				$sql = "UPDATE " . ATTACHMENTS_DESC_TABLE . "
					SET comment = '" . $db->sql_escape($attachments['_' . $attachrow['attach_id']]['comment']) . "', download_count = " . (int) $attachments['_' . $attachrow['attach_id']]['download_count'] . "
					WHERE attach_id = " . (int) $attachrow['attach_id'];
				$db->sql_query($sql);
			}
		}
	}
	$db->sql_freeresult($result);
}

// Statistics
if ($view == 'stats')
{
	$template->set_filenames(array('body' => ADM_TPL . 'attach_cp_body.tpl'));

	$upload_dir_size = get_formatted_dirsize();

	if ($config['attachment_quota'] >= 1048576)
	{
		$attachment_quota = round($config['attachment_quota'] / 1048576 * 100) / 100 . ' ' . $lang['MB'];
	}
	else if ($config['attachment_quota'] >= 1024)
	{
		$attachment_quota = round($config['attachment_quota'] / 1024 * 100) / 100 . ' ' . $lang['KB'];
	}
	else
	{
		$attachment_quota = $config['attachment_quota'] . ' ' . $lang['Bytes'];
	}

	$sql = "SELECT count(*) AS total
		FROM " . ATTACHMENTS_DESC_TABLE;
	$result = $db->sql_query($sql);
	$total = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);

	$number_of_attachments = $total['total'];

	$sql = "SELECT post_id
		FROM " . ATTACHMENTS_TABLE . "
		WHERE post_id <> 0
		GROUP BY post_id";
	$result = $db->sql_query($sql);
	$number_of_posts = $db->sql_numrows($result);
	$db->sql_freeresult($result);

	$sql = "SELECT privmsgs_id
		FROM " . ATTACHMENTS_TABLE . "
		WHERE privmsgs_id <> 0
		GROUP BY privmsgs_id";
	$result = $db->sql_query($sql);
	$number_of_pms = $db->sql_numrows($result);
	$db->sql_freeresult($result);

	$sql = "SELECT p.topic_id
		FROM " . ATTACHMENTS_TABLE . " a, " . POSTS_TABLE . " p
		WHERE a.post_id = p.post_id AND p.deleted != 2
		GROUP BY p.topic_id";
	$result = $db->sql_query($sql);
	$number_of_topics = $db->sql_numrows($result);
	$db->sql_freeresult($result);

	$sql = "SELECT user_id_1
		FROM " . ATTACHMENTS_TABLE . "
		WHERE (post_id <> 0)
		GROUP BY user_id_1";
	$result = $db->sql_query($sql);
	$number_of_users = $db->sql_numrows($result);
	$db->sql_freeresult($result);

	$template->assign_vars(array(
		'L_STATISTIC' => $lang['Statistic'],
		'L_VALUE' => $lang['Value'],
		'L_NUMBER_OF_ATTACHMENTS' => $lang['Number_of_attachments'],
		'L_TOTAL_FILESIZE' => $lang['Total_filesize'],
		'L_ATTACH_QUOTA' => $lang['Attach_quota'],
		'L_NUMBER_OF_POSTS' => $lang['Number_posts_attach'],
		'L_NUMBER_OF_PMS' => $lang['Number_pms_attach'],
		'L_NUMBER_OF_TOPICS' => $lang['Number_topics_attach'],
		'L_NUMBER_OF_USERS' => $lang['Number_users_attach'],

		'TOTAL_FILESIZE' => $upload_dir_size,
		'ATTACH_QUOTA' => $attachment_quota,
		'NUMBER_OF_ATTACHMENTS' => $number_of_attachments,
		'NUMBER_OF_POSTS' => $number_of_posts,
		'NUMBER_OF_PMS' => $number_of_pms,
		'NUMBER_OF_TOPICS' => $number_of_topics,
		'NUMBER_OF_USERS' => $number_of_users
		)
	);

}

// Search
if ($view == 'search')
{
	// Get Forums and Categories
	$sql = "SELECT c.forum_name AS cat_title, c.forum_id AS cat_id, f.forum_name, f.forum_id
		FROM " . FORUMS_TABLE . " c, " . FORUMS_TABLE . " f
		WHERE f.parent_id = c.forum_id
		ORDER BY f.forum_order";
	$result = $db->sql_query($sql);

	$s_forums = '';
	while ($row = $db->sql_fetchrow($result))
	{
		$s_forums .= '<option value="' . $row['forum_id'] . '">' . $row['forum_name'] . '</option>';

		if(empty($list_cat[$row['parent_id']]))
		{
			$list_cat[$row['parent_id']] = $row['cat_title'];
		}
	}

	if ($s_forums != '')
	{
		$s_forums = '<option value="0">' . $lang['All_available'] . '</option>' . $s_forums;

		// Category to search
		$s_categories = '<option value="0">' . $lang['All_available'] . '</option>';

		foreach ($list_cat as $parent_id => $cat_title)
		{
			$s_categories .= '<option value="' . $parent_id . '">' . $cat_title . '</option>';
		}
	}
	else
	{
		message_die(GENERAL_MESSAGE, $lang['No_searchable_forums']);
	}

	$template->set_filenames(array('body' => ADM_TPL . 'attach_cp_search.tpl'));

	$template->assign_vars(array(
		'L_ATTACH_SEARCH_QUERY' => $lang['Attach_search_query'],
		'L_FILENAME' => $lang['File_name'],
		'L_COMMENT' => $lang['File_comment'],
		'L_SEARCH_OPTIONS' => $lang['Search_options'],
		'L_SEARCH_AUTHOR' => $lang['Search_author'],
		'L_WILDCARD_EXPLAIN' => $lang['Search_wildcard_explain'],
		'L_SIZE_SMALLER_THAN' => $lang['Size_smaller_than'],
		'L_SIZE_GREATER_THAN' => $lang['Size_greater_than'],
		'L_COUNT_SMALLER_THAN' => $lang['Count_smaller_than'],
		'L_COUNT_GREATER_THAN' => $lang['Count_greater_than'],
		'L_MORE_DAYS_OLD' => $lang['More_days_old'],
		'L_CATEGORY' => $lang['Category'],
		'L_ORDER' => $lang['Order'],
		'L_SORT_BY' => $lang['Select_sort_method'],
		'L_FORUM' => $lang['Forum'],
		'L_SEARCH' => $lang['Search'],

		'S_FORUM_OPTIONS' => $s_forums,
		'S_CATEGORY_OPTIONS' => $s_categories,
		'S_SORT_OPTIONS' => $select_sort_mode,
		'S_SORT_ORDER' => $select_sort_order
		)
	);
}

// Username
if ($view == 'username')
{
	$template->set_filenames(array('body' => ADM_TPL . 'attach_cp_user.tpl'));

	$template->assign_vars(array(
		'L_SELECT_SORT_METHOD' => $lang['Select_sort_method'],
		'L_ORDER' => $lang['Order'],
		'L_USERNAME' => $lang['Username'],
		'L_TOTAL_SIZE' => $lang['Size_in_kb'],
		'L_ATTACHMENTS' => $lang['Attachments'],

		'S_MODE_SELECT' => $select_sort_mode,
		'S_ORDER_SELECT' => $select_sort_order
		)
	);


	// Get all Users with their respective total attachments amount
	$sql = "SELECT u.username, a.user_id_1 as user_id, COUNT(*) as total_attachments
		FROM " . ATTACHMENTS_TABLE . " a, " . USERS_TABLE . " u
		WHERE a.user_id_1 = u.user_id
		GROUP BY a.user_id_1, u.username";

	if ($mode != 'filesize')
	{
		$sql .= ' ' . $order_by;
	}

	$result = $db->sql_query($sql);
	$members = $db->sql_fetchrowset($result);
	$num_members = $db->sql_numrows($result);
	$db->sql_freeresult($result);

	if ($num_members > 0)
	{
		for ($i = 0; $i < $num_members; $i++)
		{
			// Get all attach_id's the specific user posted
			$sql = "SELECT attach_id
				FROM " . ATTACHMENTS_TABLE . "
				WHERE user_id_1 = " . intval($members[$i]['user_id']) . "
				GROUP BY attach_id";
			$result = $db->sql_query($sql);
			$attach_ids = $db->sql_fetchrowset($result);
			$num_attach_ids = $db->sql_numrows($result);
			$db->sql_freeresult($result);

			$attach_id = array();

			for ($j = 0; $j < $num_attach_ids; $j++)
			{
				$attach_id[] = intval($attach_ids[$j]['attach_id']);
			}

			if (sizeof($attach_id))
			{
				// Now get the total filesize
				$sql = "SELECT sum(filesize) as total_size
					FROM " . ATTACHMENTS_DESC_TABLE . "
					WHERE attach_id IN (" . implode(', ', $attach_id) . ")";
				$result = $db->sql_query($sql);
				$row = $db->sql_fetchrow($result);
				$db->sql_freeresult($result);

				$members[$i]['total_size'] = (int) $row['total_size'];
			}
		}

		if ($mode == 'filesize')
		{
			$members = sort_multi_array($members, 'total_size', $sort_order, false);
			$members = limit_array($members, $start, $config['topics_per_page']);
		}

		for ($i = 0; $i < sizeof($members); $i++)
		{
			$username = $members[$i]['username'];
			$total_attachments = $members[$i]['total_attachments'];
			$total_size = $members[$i]['total_size'];

			$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];

			$template->assign_block_vars('memberrow', array(
				'ROW_NUMBER' => $i + (intval($_GET['start']) + 1),
				'ROW_CLASS' => $row_class,
				'USERNAME' => $username,
				'TOTAL_ATTACHMENTS' => $total_attachments,
				'TOTAL_SIZE' => round(($total_size / MEGABYTE), 2),
				'U_VIEW_MEMBER' => append_sid('admin_attach_cp.' . PHP_EXT . '?view=attachments&amp;uid=' . $members[$i]['user_id']))
			);
		}
	}

	$sql = "SELECT user_id_1
		FROM " . ATTACHMENTS_TABLE . "
		GROUP BY user_id_1";
	$result = $db->sql_query($sql);
	$total_rows = $db->sql_numrows($result);
	$db->sql_freeresult($result);
}

// Attachments
if ($view == 'attachments')
{
	$user_based = ($uid) ? true : false;
	$search_based = (isset($_POST['search']) && $_POST['search']) ? true : false;

	$hidden_fields = '';

	$template->set_filenames(array('body' => ADM_TPL . 'attach_cp_attachments.tpl'));

	$template->assign_vars(array(
		'L_SELECT_SORT_METHOD' => $lang['Select_sort_method'],
		'L_ORDER' => $lang['Order'],

		'L_FILENAME' => $lang['File_name'],
		'L_FILECOMMENT' => $lang['File_comment_cp'],
		'L_EXTENSION' => $lang['Extension'],
		'L_SIZE' => $lang['Size_in_kb'],
		'L_DOWNLOADS' => $lang['Downloads'],
		'L_POST_TIME' => $lang['Post_time'],
		'L_POSTED_IN_TOPIC' => $lang['Posted_in_topic'],
		'L_DELETE' => $lang['Delete'],
		'L_DELETE_MARKED' => $lang['Delete_marked'],
		'L_SUBMIT_CHANGES' => $lang['Submit_changes'],
		'L_MARK_ALL' => $lang['Mark_all'],
		'L_UNMARK_ALL' => $lang['Unmark_all'],

		'S_MODE_SELECT' => $select_sort_mode,
		'S_ORDER_SELECT' => $select_sort_order
		)
	);

	$total_rows = 0;

	// Are we called from Username ?
	if ($user_based)
	{
		$sql = "SELECT username
			FROM " . USERS_TABLE . "
			WHERE user_id = " . intval($uid);
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);
		$username = $row['username'];

		$s_hidden = '<input type="hidden" name="u_id" value="' . intval($uid) . '" />';

		$template->assign_block_vars('switch_user_based', array());

		$template->assign_vars(array(
			'S_USER_HIDDEN' => $s_hidden,
			'L_STATISTICS_FOR_USER' => sprintf($lang['Statistics_for_user'], $username)
			)
		);

		$sql = "SELECT attach_id
			FROM " . ATTACHMENTS_TABLE . "
			WHERE user_id_1 = " . intval($uid) . "
			GROUP BY attach_id";
		$result = $db->sql_query($sql);
		$attach_ids = $db->sql_fetchrowset($result);
		$num_attach_ids = $db->sql_numrows($result);
		$db->sql_freeresult($result);

		if ($num_attach_ids == 0)
		{
			message_die(GENERAL_MESSAGE, 'For some reason no Attachments are assigned to the User "' . $username . '".');
		}

		$total_rows = $num_attach_ids;

		$attach_id = array();

		for ($j = 0; $j < $num_attach_ids; $j++)
		{
			$attach_id[] = intval($attach_ids[$j]['attach_id']);
		}

		$sql = "SELECT a.*
			FROM " . ATTACHMENTS_DESC_TABLE . " a
			WHERE a.attach_id IN (" . implode(', ', $attach_id) . ") " .
			$order_by;

	}
	elseif ($search_based)
	{
		// we are called from search
		$attachments = search_attachments($order_by, $total_rows);
	}
	else
	{
		$sql = "SELECT a.*
			FROM " . ATTACHMENTS_DESC_TABLE . " a " .
			$order_by;
	}

	if (!$search_based)
	{
		$result = $db->sql_query($sql);
		$attachments = $db->sql_fetchrowset($result);
		$num_attach = $db->sql_numrows($result);
		$db->sql_freeresult($result);
	}

	if (sizeof($attachments) > 0)
	{
		for ($i = 0; $i < sizeof($attachments); $i++)
		{
			$delete_box = '<input type="checkbox" name="delete_id_list[]" value="' . intval($attachments[$i]['attach_id']) . '" />';

			for ($j = 0; $j < sizeof($delete_id_list); $j++)
			{
				if ($delete_id_list[$j] == $attachments[$i]['attach_id'])
				{
					$delete_box = '<input type="checkbox" name="delete_id_list[]" value="' . intval($attachments[$i]['attach_id']) . '" checked="checked" />';
					break;
				}
			}

			$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];

			// Is the Attachment assigned to more than one post ?
			// If it's not assigned to any post, it's an private message thingy. ;)
			$post_titles = array();

			$sql = "SELECT *
				FROM " . ATTACHMENTS_TABLE . "
				WHERE attach_id = " . intval($attachments[$i]['attach_id']);
			$result = $db->sql_query($sql);
			$ids = $db->sql_fetchrowset($result);
			$num_ids = $db->sql_numrows($result);
			$db->sql_freeresult($result);

			for ($j = 0; $j < $num_ids; $j++)
			{
				if ($ids[$j]['post_id'] != 0)
				{
					$sql = "SELECT t.topic_title
						FROM " . TOPICS_TABLE . " t, " . POSTS_TABLE . " p
						WHERE p.post_id = " . intval($ids[$j]['post_id']) . " AND p.topic_id = t.topic_id AND p.deleted != 2
						GROUP BY t.topic_id, t.topic_title";
					$result = $db->sql_query($sql);
					$row = $db->sql_fetchrow($result);
					$db->sql_freeresult($result);

					$post_title = $row['topic_title'];

					if (strlen($post_title) > 32)
					{
						$post_title = substr($post_title, 0, 30) . '...';
					}

					$view_topic = append_sid(IP_ROOT_PATH . 'viewtopic.' . PHP_EXT . '?' . POST_POST_URL . '=' . $ids[$j]['post_id'] . '#p' . $ids[$j]['post_id']);

					$post_titles[] = '<a href="' . $view_topic . '" class="gen" target="_blank">' . $post_title . '</a>';
				}
				else
				{
					$post_titles[] = $lang['Private_Message'];
				}
			}

			$post_titles = implode('<br />', $post_titles);

			$hidden_field = '<input type="hidden" name="attach_id_list[]" value="' . intval($attachments[$i]['attach_id']) . '" />';

			$template->assign_block_vars('attachrow', array(
				'ROW_NUMBER' => $i + ($_GET['start'] + 1),
				'ROW_CLASS' => $row_class,

				'FILENAME' => $attachments[$i]['real_filename'],
				'COMMENT' => $attachments[$i]['comment'],
				'EXTENSION' => $attachments[$i]['extension'],
				'SIZE' => round(($attachments[$i]['filesize'] / MEGABYTE), 2),
				'DOWNLOAD_COUNT'=> $attachments[$i]['download_count'],
				'POST_TIME' => create_date($config['default_dateformat'], $attachments[$i]['filetime'], $config['board_timezone']),
				'POST_TITLE' => $post_titles,

				'S_DELETE_BOX' => $delete_box,
				'S_HIDDEN' => $hidden_field,
				'U_VIEW_ATTACHMENT' => append_sid(IP_ROOT_PATH . 'download.' . PHP_EXT . '?id=' . $attachments[$i]['attach_id']),
				//'U_VIEW_POST' => ($attachments[$i]['post_id'] != 0) ? append_sid("../" . CMS_PAGE_VIEWTOPIC . "?" . POST_POST_URL . "=" . $attachments[$i]['post_id'] . "#" . $attachments[$i]['post_id']) : ''
				)
			);

		}
	}

	if (!$search_based && !$user_based)
	{
		if ($total_attachments == 0)
		{
			$sql = "SELECT attach_id FROM " . ATTACHMENTS_DESC_TABLE;
			$result = $db->sql_query($sql);
			$total_rows = $db->sql_numrows($result);
			$db->sql_freeresult($result);
		}
	}
}

// Generate Pagination
if ($do_pagination && $total_rows > $config['topics_per_page'])
{
	$pagination = generate_pagination('admin_attach_cp.' . PHP_EXT . '?view=' . $view . '&amp;mode=' . $mode . '&amp;order=' . $sort_order . '&amp;uid=' . $uid, $total_rows, $config['topics_per_page'], $start).'&nbsp;';

	$template->assign_vars(array(
		'PAGINATION' => $pagination,
		'PAGE_NUMBER' => sprintf($lang['Page_of'], (floor($start / $config['topics_per_page']) + 1), ceil($total_rows / $config['topics_per_page'])),

		'L_GOTO_PAGE' => $lang['Goto_page']
		)
	);
}

$template->assign_vars(array(
	'ATTACH_VERSION' => sprintf($lang['Attachment_version'], $config['attach_version'])
	)
);

$template->pparse('body');

include('page_footer_admin.' . PHP_EXT);

?>