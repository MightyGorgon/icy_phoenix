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
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

include(IP_ROOT_PATH . 'language/lang_' . $board_config['default_lang'] . '/lang_admin_attach.' . PHP_EXT);

$cms_page_id = 'attachments';
$cms_page_nav = (!empty($cms_config_layouts[$cms_page_id]['page_nav']) ? true : false);
$cms_global_blocks = (!empty($cms_config_layouts[$cms_page_id]['global_blocks']) ? true : false);
$cms_auth_level = (isset($cms_config_layouts[$cms_page_id]['view']) ? $cms_config_layouts[$cms_page_id]['view'] : AUTH_ALL);
check_page_auth($cms_page_id, $cms_auth_level);

$real_filename = 'real_filename';
$attach_table = ATTACHMENTS_TABLE;
$attach_desc_table = ATTACHMENTS_DESC_TABLE;
$attach_stats_table = ATTACHMENTS_STATS_TABLE;

// Start user modifiable variables

// Define the default forum by forum-id OR by Forum Name. If both are set, the forum id is used.
// To display the Attachments of all Forums, please set the display_all variable to true
$default_forum_id = '';
$default_forum_name = '';

// Set this to FALSE or fill the above values for a specific forum to be displayed
$display_all_forums = true;

// Define the default Sort.
// Valid values are: filename, comment, filesize, downloads, post_time
$default_sort_method = 'downloads';

// Default Sort Order: ASC or DESC
$default_sort_order = 'DESC';

// End user modifiable variables

// Determine the variables we need for sorting and such
$start = (isset($_GET['start'])) ? intval($_GET['start']) : 0;
$start = ($start < 0) ? 0 : $start;

$attach_id = (isset($_GET['attach_id'])) ? intval($_GET['attach_id']) : ((isset($_POST['attach_id'])) ? intval($_POST['attach_id']) : 0);
$attach_id = (($attach_id > 0) && ($userdata['user_level'] == ADMIN)) ? $attach_id : 0;

if(isset($_GET['order']) || isset($_POST['order']))
{
	$sort_order = (isset($_POST['order'])) ? $_POST['order'] : $_GET['order'];
}
else
{
	$sort_order = $default_sort_order;
}
$sort_order = ($sort_order == 'ASC') ? 'ASC' : 'DESC';

if(isset($_GET['mode']) || isset($_POST['mode']))
{
	$mode = (isset($_POST['mode'])) ? $_POST['mode'] : $_GET['mode'];
}
else
{
	$mode = $default_sort_method;
}

if ($attach_id > 0)
{
	// Sort and Mode Select
	$mode_types_text = array($lang['Date'], $lang['Memberlist_User']);
	$mode_types = array('download_time', 'user_id');
}
else
{
	$mode_types_text = array($lang['Sort_Filename'], $lang['Sort_Comment'], $lang['Sort_Size'], $lang['Sort_Downloads'], $lang['Sort_Posttime']);
	$mode_types = array('filename', 'comment', 'filesize', 'downloads', 'post_time');
}

$select_sort_mode = '<select name="mode">';
for($i = 0; $i < count($mode_types_text); $i++)
{
	$selected = ($mode == $mode_types[$i]) ? ' selected="selected"' : '';
	$select_sort_mode .= '<option value="' . $mode_types[$i] . '"' . $selected . '>' . $mode_types_text[$i] . '</option>';
}
$select_sort_mode .= '</select>';

$sort_types_text = array($lang['Sort_Ascending'], $lang['Sort_Descending']);
$sort_types = array('ASC', 'DESC');

$select_sort_order = '<select name="order">';
for($i = 0; $i < count($sort_types_text); $i++)
{
	$selected = ($sort_order == $sort_types[$i]) ? ' selected="selected"' : '';
	$select_sort_order .= '<option value="' . $sort_types[$i] . '"' . $selected . '>' . $sort_types_text[$i] . '</option>';
}
$select_sort_order .= '</select>';


$page_title = $lang['Downloads'];
$meta_description = '';
$meta_keywords = '';
include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);

if ($attach_id > 0)
{

	$s_hidden_fields = '<input type="hidden" name="attach_id" value="' . $attach_id . '" />';
	switch ($mode)
	{
		case 'user_id':
			$order_by = 'user_id ' . $sort_order . ', download_time DESC LIMIT ' . $start . ', ' . $board_config['topics_per_page'];
			break;
		default:
			$order_by = 'download_time ' . $sort_order . ' LIMIT ' . $start . ', ' . $board_config['topics_per_page'];
			break;
	}

	$template->set_filenames(array('body' => 'attachments_details.tpl'));

	$sql = "SELECT d.*
		FROM " . $attach_desc_table . " d
		WHERE d.attach_id = '" . $attach_id . "'
		LIMIT 1";
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Cound not query attachment stats table', '', __LINE__, __FILE__, $sql);
	}

	while ($row = $db->sql_fetchrow($result))
	{
		$template->assign_vars(array(
			'L_FILENAME' => $row['real_filename'],
			'L_COMMENT' => $row['comment'],
			)
		);
	}
	$db->sql_freeresult($result);


	$template->assign_vars(array(
		'L_SELECT_SORT_METHOD' => $lang['Select_sort_method'],
		'L_ORDER' => $lang['Order'],
		'L_SORT' => $lang['Sort'],
		'L_SUBMIT' => $lang['Submit'],
		'L_DATE' => $lang['Date'],
		'L_USER' => $lang['Memberlist_User'],

		'U_ATTACHMENTS' => append_sid('attachments.' . PHP_EXT),

		'S_HIDDEN_FIELDS' => $s_hidden_fields,
		'S_MODE_SELECT' => $select_sort_mode,
		'S_ORDER_SELECT' => $select_sort_order,
		'S_MODE_ACTION' => append_sid('attachments.' . PHP_EXT)
		)
	);

	$sql = "SELECT d.*, s.*, u.username, u.user_active, u.user_color
		FROM " . $attach_desc_table . " d, " . $attach_stats_table . " s, " . USERS_TABLE . " u
		WHERE d.attach_id = '" . $attach_id . "'
			AND s.attach_id = '" . $attach_id . "'
			AND u.user_id = s.user_id
		ORDER BY $order_by";
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Cound not query attachment stats table', '', __LINE__, __FILE__, $sql);
	}

	$counter = 0;
	while ($row = $db->sql_fetchrow($result))
	{
		$counter++;
		$template->assign_block_vars('row', array(
			'NUMBER' => $start + $counter,
			'DATE' => create_date2($board_config['default_dateformat'], $row['download_time'], $board_config['board_timezone']),
			'USER' => colorize_username($row['user_id'], $row['username'], $row['user_color'], $row['user_active'])
			)
		);
	}
	$db->sql_freeresult($result);

	$gen_pagination = true;
	$sql = "SELECT count(*) AS total
		FROM " . $attach_stats_table . " s
		WHERE s.attach_id = '" . $attach_id . "'";
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Error getting total items', '', __LINE__, __FILE__, $sql);
	}
	$pagination_append = 'attach_id=' . $attach_id . '&amp;';
}
else
{
	switch ($mode)
	{
		case 'filename':
			$order_by = '' . $real_filename . ' ' . $sort_order . ' LIMIT ' . $start . ', ' . $board_config['topics_per_page'];
			break;
		case 'comment':
			$order_by = 'comment ' . $sort_order . ' LIMIT ' . $start . ', ' . $board_config['topics_per_page'];
			break;
		case 'filesize':
			$order_by = 'filesize ' . $sort_order . ' LIMIT ' . $start . ', ' . $board_config['topics_per_page'];
			break;
		case 'downloads':
			$order_by = 'download_count ' . $sort_order . ' LIMIT ' . $start . ', ' . $board_config['topics_per_page'];
			break;
		case 'post_time':
			$order_by = 'filetime ' . $sort_order . ' LIMIT ' . $start . ', ' . $board_config['topics_per_page'];
			break;
		default:
			message_die(GENERAL_MESSAGE, "Please have a look at the attachments.php file and define valid sort order default values.");
			break;
	}

	// Forum Select
	if(isset($_POST[POST_FORUM_URL]) || isset($_GET[POST_FORUM_URL]))
	{
		$forum_id = (isset($_POST[POST_FORUM_URL])) ? intval($_POST[POST_FORUM_URL]) : intval($_GET[POST_FORUM_URL]);
	}
	else
	{
		$default_forum_id = intval($default_forum_id);
		if ($default_forum_id)
		{
			$forum_id = intval($default_forum_id);
		}
		elseif ($default_forum_name != '')
		{
			$sql = "SELECT forum_id
			FROM " . FORUMS_TABLE . "
			WHERE forum_name='" . $default_forum_name . "'
			LIMIT 1";

			if (!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_MESSAGE, "Please specify a valid Forum Name.'" . $default_forum_name . "' could not be found.");
			}

			$row = $db->sql_fetchrow($result);
			$forum_id = $row['forum_id'];
			$db->sql_freeresult($result);
		}
		elseif (!$display_all_forums)
		{
			message_die(GENERAL_MESSAGE, "Please have a look at the attachments.php file and define valid forum default values.");
		}

		if ($forum_id)
		{
			$sql = "SELECT forum_id
			FROM " . FORUMS_TABLE . "
			WHERE forum_id = $forum_id
			LIMIT 1";

			if (!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, "Couldn't query forums table.", '', __LINE__, __FILE__, $sql);
			}

			if ($db->sql_numrows($result) == 0)
			{
				message_die(GENERAL_MESSAGE, "The default forum id/name does not exist, please check your default values.");
			}
			$db->sql_freeresult($result);
		}
	}

	// Search forum - first delete those the user have not access to and then those the user have no permission to download to.
	$sql = "SELECT c.cat_title, c.cat_id, f.forum_name, f.forum_id
		FROM " . CATEGORIES_TABLE . " c, " . FORUMS_TABLE . " f
		WHERE f.cat_id = c.cat_id
		ORDER BY c.cat_id, f.forum_order";

	if (!($result = $db->sql_query($sql, false, 'attachments_forums_')))
	{
		message_die(GENERAL_ERROR, 'Could not obtain forum_name/forum_id', '', __LINE__, __FILE__, $sql);
	}

	$is_auth_ary = auth(AUTH_READ, AUTH_LIST_ALL, $userdata);
	$is_download_auth_ary = auth(AUTH_DOWNLOAD, AUTH_LIST_ALL, $userdata);

	$forum_ids = array();
	$select_forums = '';
	while($row = $db->sql_fetchrow($result))
	{
		if (($is_auth_ary[$row['forum_id']]['auth_read']) && ($is_download_auth_ary[$row['forum_id']]['auth_download']))
		{
			$selected = ($forum_id == $row['forum_id']) ? ' selected="selected"' : '';
			$select_forums .= '<option value="' . $row['forum_id'] . '"' . $selected . '>' . strip_tags($row['forum_name']) . '</option>';
			$forum_ids[] = $row['forum_id'];
		}
	}
	$db->sql_freeresult($result);

	if ($select_forums != '')
	{
		$select_forums = '<select name="' . POST_FORUM_URL . '"><option value="-1">' . $lang['All_available'] . '</option>' . $select_forums . '</select>';
	}
	else
	{
		message_die(GENERAL_MESSAGE, 'You are not authorized to view Attachments at all.');
	}

	$forum_id = (intval($forum_id) >= '-1') ? intval($forum_id) : '-1';

	//$template->assign_block_vars('google_ad', array());
	$template->set_filenames(array('body' => 'attachments.tpl'));

	$template->assign_vars(array(
		'L_SELECT_SORT_METHOD' => $lang['Select_sort_method'],
		'L_ORDER' => $lang['Order'],
		'L_SORT' => $lang['Sort'],
		'L_SUBMIT' => $lang['Submit'],
		'L_FORUM' => $lang['Forum'],

		'L_ATTACHMENTS' => $lang['Attachments'],
		'L_FILENAME' => $lang['File_name'],
		'L_FILECOMMENT' => $lang['File_comment'],
		'L_SIZE' => $lang['Size_in_kb'],
		'L_DOWNLOADS' => $lang['Downloads'],
		'L_POST_TIME' => $lang['Post_time'],
		'L_POSTED_IN_TOPIC' => $lang['Topic'],

		'S_MODE_SELECT' => $select_sort_mode,
		'S_ORDER_SELECT' => $select_sort_order,
		'S_FORUM_SELECT' => $select_forums,
		'S_MODE_ACTION' => append_sid('attachments.' . PHP_EXT)
		)
	);

	$sql = '';

	if ((!$forum_id || $forum_id == '-1') && $display_all_forums)
	{
		$sql = "SELECT a.post_id, t.topic_title, d.*
			FROM " . $attach_table . " a, " . $attach_desc_table . " d, " . POSTS_TABLE . " p, " . TOPICS_TABLE . " t
			WHERE (a.post_id = p.post_id) AND (p.forum_id IN (" . implode(', ', $forum_ids) . ")) AND (p.topic_id = t.topic_id) AND (a.attach_id = d.attach_id)
			ORDER BY $order_by";
	}
	elseif (($is_auth_ary[$forum_id]['auth_read']) && ($is_download_auth_ary[$forum_id]['auth_download']))
	{
		$sql = "SELECT a.post_id, t.topic_title, d.*
			FROM " . $attach_table . " a, " . $attach_desc_table . " d, " . POSTS_TABLE . " p, " . TOPICS_TABLE . " t
			WHERE (a.post_id = p.post_id) AND (p.forum_id = " . $forum_id . ") AND (p.topic_id = t.topic_id) AND (a.attach_id = d.attach_id)
			ORDER BY $order_by";
	}

	if ($sql != '')
	{
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Couldn\'t query attachments', '', __LINE__, __FILE__, $sql);
		}

		$attachments = $db->sql_fetchrowset($result);
		$num_attachments = $db->sql_numrows($result);
	}
	else
	{
		$attachments = array();
		$num_attachments = 0;
	}
	$db->sql_freeresult($result);

	for ($i = 0; $i < $num_attachments; $i++)
	{
		$class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];

		$post_title = $attachments[$i]['topic_title'];
		$post_title_2 = '';

		if (strlen($post_title) > 32)
		{
			$post_title_2 = substr($post_title, 0, 30) . '...';
		}

		$view_topic = append_sid(VIEWTOPIC_MG . '?' . POST_POST_URL . '=' . $attachments[$i]['post_id'] . '#p' . $attachments[$i]['post_id']);
		if ($post_title_2 != '')
		{
			$post_title = '<a href="' . $view_topic . '" class="gen" title="' . $post_title . '" target="_blank">' . $post_title_2 . '</a>';
		}
		else
		{
			$post_title = '<a href="' . $view_topic . '" class="gen" target="_blank">' . $post_title . '</a>';
		}

		$comment = htmlspecialchars($attachments[$i]['comment']);
		$comment_2 = '';

		if (strlen($comment) > 32)
		{
			$comment_2 = substr($comment, 0, 30) . '...';
		}

		if ($comment_2 != '')
		{
			$comment_field = '<span title="' . $comment . '">' . $comment_2 . '</span>';
		}
		else
		{
			$comment_field = $comment;
		}

		$filename = $attachments[$i][$real_filename];
		$filename_2 = '';

		if (strlen($filename) > 32)
		{
			$filename_2 = substr($filename, 0, 30) . '...';
		}

		$view_attachment = append_sid(IP_ROOT_PATH . 'download.' . PHP_EXT . '?id=' . intval($attachments[$i]['attach_id']));
		if ($filename_2 != '')
		{
			$filename_link = '<a href="' . $view_attachment . '" class="gen" title="' . $filename . '" target="_blank">' . $filename_2 . '</a>';
		}
		else
		{
			$filename_link = '<a href="' . $view_attachment . '" class="gen" target="_blank">' . $filename . '</a>';
		}

		if (($attachments[$i]['download_count'] > 0) && ($userdata['user_level'] == ADMIN))
		{
			$download_count_link = '<a href="' . append_sid('attachments.' . PHP_EXT . '?attach_id=' . intval($attachments[$i]['attach_id'])) . '">' . $attachments[$i]['download_count'] . '</a>';
		}
		else
		{
			$download_count_link = $attachments[$i]['download_count'];
		}

		$template->assign_block_vars('attachrow', array(
			'ROW_NUMBER' => $i + ($_GET['start'] + 1),
			'ROW_CLASS' => $class,

			'FILENAME' => $filename,
			'COMMENT' => $comment_field,
			'SIZE' => round(($attachments[$i]['filesize'] / 1024), 2),
			'DOWNLOAD_COUNT' => $download_count_link,
			'POST_TIME' => create_date2($board_config['default_dateformat'], $attachments[$i]['filetime'], $board_config['board_timezone']),
			'POST_TITLE' => $post_title,

			'VIEW_ATTACHMENT' => $filename_link
			)
		);
	}

	$gen_pagination = false;
	if ((!$forum_id || $forum_id == '-1') && $display_all_forums)
	{
		$gen_pagination = true;
		$sql = "SELECT count(*) AS total
			FROM " . $attach_table . " a, " . POSTS_TABLE . " p
			WHERE (a.post_id = p.post_id) AND (p.forum_id IN (" . implode(', ', $forum_ids) . "))";
	}
	elseif (($is_auth_ary[$forum_id]['auth_read']) && ($is_download_auth_ary[$forum_id]['auth_download']) && ($num_attachments > 0))
	{
		$gen_pagination = true;
		$sql = "SELECT count(*) AS total
			FROM " . $attach_table . " a, " . POSTS_TABLE . " p
			WHERE (a.post_id = p.post_id) AND (p.forum_id = " . $forum_id . ")";
	}

	if ($gen_pagination == true)
	{
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Error getting total items', '', __LINE__, __FILE__, $sql);
		}
	}
	$pagination_append = POST_FORUM_URL . '=' . $forum_id . '&amp;';
}

if ($gen_pagination == true)
{
	if ($total = $db->sql_fetchrow($result))
	{
		$total = $total['total'];
		$pagination = generate_pagination(append_sid('attachments.' . PHP_EXT . '?' . $pagination_append . 'mode=' . $mode . '&amp;order=' . $sort_order), $total, $board_config['topics_per_page'], $start) . '&nbsp;';
	}
	$db->sql_freeresult($result);

	$template->assign_vars(array(
		'PAGINATION' => $pagination,
		'PAGE_NUMBER' => sprintf($lang['Page_of'], (floor($start / $board_config['topics_per_page']) + 1), ceil($total / $board_config['topics_per_page'])),
		'L_GOTO_PAGE' => $lang['Goto_page']
		)
	);
}

$template->pparse('body');

include(IP_ROOT_PATH . 'includes/page_tail.' . PHP_EXT);

?>