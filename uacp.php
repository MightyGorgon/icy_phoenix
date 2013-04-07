<?php
/**
*
* @package attachment_mod
* @version $Id$
* @copyright (c) 2002 Meik Sievertsen
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
*
* @Extra credits for this file
* (c) 2002 Meik Sievertsen (Acyd Burn)
*
*/

// Added to optimize memory for attachments
define('ATTACH_POSTING', true);
define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);

// session id check
$sid = request_var('sid', '');

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup();
// End session management

// session id check
if (($sid == '') || ($sid != $user->data['session_id']))
{
	message_die(GENERAL_ERROR, 'INVALID_SESSION');
}

// Obtain initial var settings
$user_id = request_var(POST_USERS_URL, 0);
if (empty($user_id))
{
	if (!defined('STATUS_404')) define('STATUS_404', true);
	message_die(GENERAL_MESSAGE, 'NO_USER');
}

$profiledata = get_userdata($user_id);

if ($profiledata['user_id'] != $user->data['user_id'] && $user->data['user_level'] != ADMIN)
{
	message_die(GENERAL_MESSAGE, $lang['Not_Authorized']);
}

setup_extra_lang(array('lang_admin_attach'));

$start = request_var('start', 0);
$start = ($start < 0) ? 0 : $start;

$sort_order = request_var('order', 'DESC');
$sort_order = check_var_value($sort_order, array('DESC', 'ASC'));

$mode = request_var('mode', '');

$mode_types_text = array($lang['Sort_Filename'], $lang['Sort_Comment'], $lang['Sort_Extension'], $lang['Sort_Size'], $lang['Sort_Downloads'], $lang['Sort_Posttime'], /* $lang['Sort_Posts'] */);
$mode_types = array('real_filename', 'comment', 'extension', 'filesize', 'downloads', 'post_time' /* , 'posts' */);

if (!$mode)
{
	$mode = 'real_filename';
	$sort_order = 'ASC';
}

// Pagination?
$do_pagination = true;

// Set Order
$order_by = '';

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

// Set select fields
$select_sort_mode = '';
$select_sort_order = '';

if (sizeof($mode_types_text) > 0)
{
	$select_sort_mode = '<select name="mode">';

	for ($i = 0; $i < sizeof($mode_types_text); $i++)
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

$delete = (isset($_POST['delete'])) ? true : false;
$delete_id_list = (isset($_POST['delete_id_list'])) ? array_map('intval', $_POST['delete_id_list']) : array();

$confirm = (isset($_POST['confirm']) && $_POST['confirm']) ? true : false;

if ($confirm && sizeof($delete_id_list) > 0)
{
	$attachments = array();

	for ($i = 0; $i < sizeof($delete_id_list); $i++)
	{
		$sql = 'SELECT post_id, privmsgs_id
			FROM ' . ATTACHMENTS_TABLE . '
			WHERE attach_id = ' . intval($delete_id_list[$i]) . '
				AND (user_id_1 = ' . intval($profiledata['user_id']) . '
					OR user_id_2 = ' . intval($profiledata['user_id']) . ')';
		$db->sql_return_on_error(true);
		$result = $db->sql_query($sql);
		$db->sql_return_on_error(false);
		if ($result)
		{
			$row = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);

			if ($row['post_id'] != 0)
			{
				delete_attachment(0, intval($delete_id_list[$i]));
			}
			else
			{
				delete_attachment(0, intval($delete_id_list[$i]), PAGE_PRIVMSGS, intval($profiledata['user_id']));
			}
		}
	}
}
elseif ($delete && sizeof($delete_id_list) > 0)
{
	// Not confirmed, show confirmation message
	$hidden_fields = '<input type="hidden" name="view" value="' . $view . '" />';
	$hidden_fields .= '<input type="hidden" name="mode" value="' . $mode . '" />';
	$hidden_fields .= '<input type="hidden" name="order" value="' . $sort_order . '" />';
	$hidden_fields .= '<input type="hidden" name="' . POST_USERS_URL . '" value="' . intval($profiledata['user_id']) . '" />';
	$hidden_fields .= '<input type="hidden" name="start" value="' . $start . '" />';
	$hidden_fields .= '<input type="hidden" name="sid" value="' . $user->data['session_id'] . '" />';

	for ($i = 0; $i < sizeof($delete_id_list); $i++)
	{
		$hidden_fields .= '<input type="hidden" name="delete_id_list[]" value="' . intval($delete_id_list[$i]) . '" />';
	}

	$template->assign_vars(array(
		'MESSAGE_TITLE' => $lang['Confirm'],
		'MESSAGE_TEXT' => $lang['Confirm_delete_attachments'],

		'L_YES' => $lang['Yes'],
		'L_NO' => $lang['No'],

		'S_CONFIRM_ACTION' => append_sid(IP_ROOT_PATH . 'uacp.' . PHP_EXT),
		'S_HIDDEN_FIELDS' => $hidden_fields)
	);
	full_page_generation('confirm_body.tpl', $lang['Confirm'], '', '');
}

$hidden_fields = '';

$total_rows = 0;

$username = $profiledata['username'];

$s_hidden = '<input type="hidden" name="' . POST_USERS_URL . '" value="' . intval($profiledata['user_id']) . '" />';
$s_hidden .= '<input type="hidden" name="sid" value="' . $user->data['session_id'] . '" />';

// Assign Template Vars
$template->assign_vars(array(
	'L_SUBMIT' => $lang['Submit'],
	'L_UACP' => $lang['UACP'],
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
	'L_MARK_ALL' => $lang['Mark_all'],
	'L_UNMARK_ALL' => $lang['Unmark_all'],

	'USERNAME' => $profiledata['username'],

	'S_USER_HIDDEN' => $s_hidden,
	'S_MODE_ACTION' => append_sid(IP_ROOT_PATH . 'uacp.' . PHP_EXT),
	'S_MODE_SELECT' => $select_sort_mode,
	'S_ORDER_SELECT' => $select_sort_order)
);

$sql = "SELECT attach_id
	FROM " . ATTACHMENTS_TABLE . "
	WHERE user_id_1 = " . intval($profiledata['user_id']) . " OR user_id_2 = " . intval($profiledata['user_id']) . "
	GROUP BY attach_id";
$result = $db->sql_query($sql);
$attach_ids = $db->sql_fetchrowset($result);
$num_attach_ids = $db->sql_numrows($result);
$db->sql_freeresult($result);

$total_rows = $num_attach_ids;

$attachments = array();

if ($num_attach_ids > 0)
{
	$attach_id = array();

	for ($j = 0; $j < $num_attach_ids; $j++)
	{
		$attach_id[] = (int) $attach_ids[$j]['attach_id'];
	}

	$sql = "SELECT a.*
		FROM " . ATTACHMENTS_DESC_TABLE . " a
		WHERE a.attach_id IN (" . implode(', ', $attach_id) . ") " .
		$order_by;
	$result = $db->sql_query($sql);
	$attachments = $db->sql_fetchrowset($result);
	$num_attach = $db->sql_numrows($result);
	$db->sql_freeresult($result);
}

if (sizeof($attachments) > 0)
{
	for ($i = 0; $i < sizeof($attachments); $i++)
	{
		$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

		// Is the Attachment assigned to more than one post?
		// If it's not assigned to any post, it's an private message thingy. ;)
		$post_titles = array();

		$sql = 'SELECT *
			FROM ' . ATTACHMENTS_TABLE . '
			WHERE attach_id = ' . (int) $attachments[$i]['attach_id'];
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
					WHERE p.post_id = " . (int) $ids[$j]['post_id'] . " AND p.topic_id = t.topic_id
					GROUP BY t.topic_id, t.topic_title";
				$result = $db->sql_query($sql);
				$row = $db->sql_fetchrow($result);
				$db->sql_freeresult($result);

				$post_title = $row['topic_title'];

				if (strlen($post_title) > 32)
				{
					$post_title = substr($post_title, 0, 30) . '...';
				}

				$view_topic = append_sid(IP_ROOT_PATH . CMS_PAGE_VIEWTOPIC . '?' . POST_POST_URL . '=' . $ids[$j]['post_id'] . '#p' . $ids[$j]['post_id']);

				$post_titles[] = '<a href="' . $view_topic . '" class="gen" target="_blank">' . $post_title . '</a>';
			}
			else
			{
				$desc = '';

				$sql = "SELECT privmsgs_type, privmsgs_to_userid, privmsgs_from_userid
					FROM " . PRIVMSGS_TABLE . "
					WHERE privmsgs_id = " . (int) $ids[$j]['privmsgs_id'];
				$result = $db->sql_query($sql);

				if ($db->sql_numrows($result) != 0)
				{
					$row = $db->sql_fetchrow($result);
					$privmsgs_type = $row['privmsgs_type'];


					if ($privmsgs_type == PRIVMSGS_READ_MAIL || $privmsgs_type == PRIVMSGS_NEW_MAIL || $privmsgs_type == PRIVMSGS_UNREAD_MAIL)
					{
						if ($row['privmsgs_to_userid'] == $profiledata['user_id'])
						{
							$desc = $lang['Private_Message'] . ' (' . $lang['Inbox'] . ')';
						}
					}
					else if ($privmsgs_type == PRIVMSGS_SENT_MAIL)
					{
						if ($row['privmsgs_from_userid'] == $profiledata['user_id'])
						{
							$desc = $lang['Private_Message'] . ' (' . $lang['Sentbox'] . ')';
						}
					}
					else if ($privmsgs_type == PRIVMSGS_SAVED_OUT_MAIL)
					{
						if ($row['privmsgs_from_userid'] == $profiledata['user_id'])
						{
							$desc = $lang['Private_Message'] . ' (' . $lang['Savebox'] . ')';
						}
					}
					else if ($privmsgs_type == PRIVMSGS_SAVED_IN_MAIL)
					{
						if ($row['privmsgs_to_userid'] == $profiledata['user_id'])
						{
							$desc = $lang['Private_Message'] . ' (' . $lang['Savebox'] . ')';
						}
					}

					if ($desc != '')
					{
						$post_titles[] = $desc;
					}
				}
				$db->sql_freeresult($result);
			}
		}

		// Iron out those Attachments assigned to us, but not more controlled by us. ;) (PM's)
		if (sizeof($post_titles) > 0)
		{
			$delete_box = '<input type="checkbox" name="delete_id_list[]" value="' . (int) $attachments[$i]['attach_id'] . '" />';

			for ($j = 0; $j < sizeof($delete_id_list); $j++)
			{
				if ($delete_id_list[$j] == $attachments[$i]['attach_id'])
				{
					$delete_box = '<input type="checkbox" name="delete_id_list[]" value="' . (int) $attachments[$i]['attach_id'] . '" checked />';
					break;
				}
			}

			$post_titles = implode('<br />', $post_titles);

			$hidden_field = '<input type="hidden" name="attach_id_list[]" value="' . (int) $attachments[$i]['attach_id'] . '">';
			$hidden_field .= '<input type="hidden" name="sid" value="' . $user->data['session_id'] . '" />';

			$comment = str_replace("\n", '<br />', $attachments[$i]['comment']);

			$template->assign_block_vars('attachrow', array(
				'ROW_NUMBER' => $i + ($start + 1 ),
				'ROW_CLASS' => $row_class,

				'FILENAME' => $attachments[$i]['real_filename'],
				'COMMENT' => $comment,
				'EXTENSION' => $attachments[$i]['extension'],
				'SIZE' => round(($attachments[$i]['filesize'] / MEGABYTE), 2),
				'DOWNLOAD_COUNT' => $attachments[$i]['download_count'],
				'POST_TIME' => create_date($config['default_dateformat'], $attachments[$i]['filetime'], $config['board_timezone']),
				'POST_TITLE' => $post_titles,

				'S_DELETE_BOX' => $delete_box,
				'S_HIDDEN' => $hidden_field,
				'U_VIEW_ATTACHMENT' => append_sid(IP_ROOT_PATH . 'download.' . PHP_EXT . '?id=' . $attachments[$i]['attach_id']))
	//			'U_VIEW_POST' => ($attachments[$i]['post_id'] != 0) ? append_sid("../viewtopic." . PHP_EXT . "?" . POST_POST_URL . "=" . $attachments[$i]['post_id'] . "#" . $attachments[$i]['post_id']) : '')
			);
		}
	}
}

// Generate Pagination
$pagination = '&nbsp;';
$page_number = '&nbsp;';

if ($do_pagination && $total_rows > $config['topics_per_page'])
{
	$pagination = generate_pagination(IP_ROOT_PATH . 'uacp.' . PHP_EXT . '?mode=' . $mode . '&amp;order=' . $sort_order . '&amp;' . POST_USERS_URL . '=' . $profiledata['user_id'] . '&amp;sid=' . $user->data['session_id'], $total_rows, $config['topics_per_page'], $start);
	$page_number = sprintf($lang['Page_of'], (floor($start / $config['topics_per_page']) + 1), ceil($total_rows / $config['topics_per_page']));
}
$template->assign_vars(array(
	'PAGINATION' => $pagination,
	'PAGE_NUMBER' => $page_number,
	'L_GOTO_PAGE' => $lang['Goto_page']
	)
);

full_page_generation('uacp_body.tpl', $lang['User_acp_title'], '', '');

?>