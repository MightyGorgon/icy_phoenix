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
* @Icy Phoenix is based on phpBB
* @copyright (c) 2008 phpBB Group
*
*/

// CTracker_Ignore: File checked by human
// Added to optimize memory for attachments
define('ATTACH_POSTING', true);
define('ATTACH_DISPLAY', true);
define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.' . $phpEx);
include_once($phpbb_root_path . 'includes/bbcode.' . $phpEx);
include_once($phpbb_root_path . 'includes/functions_topics.' . $phpEx);
include_once($phpbb_root_path . 'includes/functions_admin.' . $phpEx);
include_once($phpbb_root_path . 'includes/functions_groups.' . $phpEx);

if(isset($_GET[POST_FORUM_URL]) || isset($_POST[POST_FORUM_URL]))
{
	$forum_id = (isset($_POST[POST_FORUM_URL])) ? intval($_POST[POST_FORUM_URL]) : intval($_GET[POST_FORUM_URL]);
}
else
{
	$forum_id = '';
}

if(isset($_GET[POST_POST_URL]) || isset($_POST[POST_POST_URL]))
{
	$post_id = (isset($_POST[POST_POST_URL])) ? intval($_POST[POST_POST_URL]) : intval($_GET[POST_POST_URL]);
}
else
{
	$post_id = '';
}

if(isset($_GET[POST_TOPIC_URL]) || isset($_POST[POST_TOPIC_URL]))
{
	$topic_id = (isset($_POST[POST_TOPIC_URL])) ? intval($_POST[POST_TOPIC_URL]) : intval($_GET[POST_TOPIC_URL]);
}
else
{
	$topic_id = '';
}

$confirm = ($_POST['confirm']) ? true : 0;
$confirm_recycle = true;

if (isset($_GET['selected_id']) || isset($_POST['selected_id']))
{
	$selected_id = isset($_POST['selected_id']) ? $_POST['selected_id'] : $_GET['selected_id'];
	$type = substr($selected_id, 0, 1);
	$id = intval(substr($selected_id, 1));
	if ($type == POST_FORUM_URL)
	{
		$forum_id = $id;
	}
	elseif (($type == POST_CAT_URL) || ($selected_id == 'Root'))
	{
		$parm = ($id != 0) ? '?' . POST_CAT_URL . '=' . $id : '';
		redirect(append_sid('./' . FORUM_MG . $parm));
		exit;
	}
}

$start = (isset($_GET['start'])) ? intval($_GET['start']) : 0;
$start = ($start < 0) ? 0 : $start;

$delete = (isset($_POST['delete'])) ? true : false;
$poll_delete = (isset($_POST['poll_delete'])) ? true : false;
$move = (isset($_POST['move'])) ? true : false;
$lock = (isset($_POST['lock'])) ? true : false;
$unlock = (isset($_POST['unlock'])) ? true : false;
$quick_title_edit = (isset($_POST['quick_title_edit'])) ? true : false;
$qtnum = (isset($_POST['qtnum'])) ? intval($_POST['qtnum']) : 0;
$merge = (isset($_POST['merge'])) ? true : false;
$recycle = (isset($_POST['recycle'])) ? true : false;
$sticky = (isset($_POST['sticky'])) ? true : false;
$announce = (isset($_POST['announce'])) ? true : false;
$global_announce = (isset($_POST['super_announce'])) ? true : false;
$normalize = (isset($_POST['normalize'])) ? true : false;
$news_category_edit = (isset($_POST['news_category_edit'])) ? true : false;
$news_category = (isset($_POST['news_category'])) ? intval($_POST['news_category']) : 0;
$news_category = ($news_category < 0) ? 0 : $news_category;

if(isset($_POST['mode']) || isset($_GET['mode']))
{
	$mode = (isset($_POST['mode'])) ? $_POST['mode'] : $_GET['mode'];
	$mode = htmlspecialchars($mode);
}
else
{
	if($delete)
	{
		$mode = 'delete';
	}
	elseif($poll_delete)
	{
		$mode = 'poll_delete';
	}
	elseif($move)
	{
		$mode = 'move';
	}
	elseif($lock)
	{
		$mode = 'lock';
	}
	elseif($unlock)
	{
		$mode = 'unlock';
	}
	elseif ($quick_title_edit)
	{
		$mode = 'quick_title_edit';
	}
	elseif ($recycle)
	{
		$mode = 'recycle';
	}
	elseif ($merge)
	{
		$mode = 'merge';
	}
	elseif($sticky)
	{
		$mode = 'sticky';
	}
	elseif($announce)
	{
		$mode = 'announce';
	}
	elseif($global_announce)
	{
		$mode = 'super_announce';
	}
	elseif($normalize)
	{
		$mode = 'normalize';
	}
	elseif ($news_category_edit)
	{
		$mode = 'news_category_edit';
	}
	else
	{
		$mode = '';
	}
}

if(isset($_POST['type']) || isset($_GET['type']))
{
	$type = (isset($_POST['type'])) ? $_POST['type'] : $_GET['type'];
}
else
{
	$type = '';
}

if(!empty($_POST['sid']) || !empty($_GET['sid']))
{
	$sid = (!empty($_POST['sid'])) ? $_POST['sid'] : $_GET['sid'];
}
else
{
	$sid = '';
}

switch($type)
{
	case 'sticky':
		$where_type = " AND t.topic_type = " . POST_STICKY; break;
	case 'announce':
		$where_type = " AND t.topic_type = " . POST_ANNOUNCE; break;
 case 'super_announce':
		$where_type = " AND t.topic_type = " . POST_GLOBAL_ANNOUNCE; break;
	case 'shadow':
		$where_type = " AND t.topic_status = " . TOPIC_MOVED; break;
	case 'poll':
		$where_type = " AND t.topic_vote = '1' AND t.topic_type = " . POST_NORMAL; break;
	case 'locked':
		$where_type = " AND t.topic_status = " . TOPIC_LOCKED . " AND t.topic_type = " . POST_NORMAL . " AND t.topic_vote = '0'"; break;
	case 'unlocked':
		$where_type = " AND t.topic_status = " . TOPIC_UNLOCKED . " AND t.topic_type = " . POST_NORMAL . " AND t.topic_views > '0' AND t.topic_replies > '0' AND t.topic_vote = '0'"; break;
	case 'unread':
		$where_type = " AND t.topic_views = '0' AND t.topic_type = " . POST_NORMAL . " AND t.topic_status = " . TOPIC_UNLOCKED . " AND t.topic_vote = '0'"; break;
	case 'unanswered':
		$where_type = " AND t.topic_replies = '0' AND t.topic_type = " . POST_NORMAL . " AND t.topic_status = " . TOPIC_UNLOCKED . " AND t.topic_views > '0' AND t.topic_vote = '0'"; break;
	default:
		$where_type = ''; break;
}

if(!empty($topic_id))
{
	$sql = "SELECT f.forum_id, f.forum_name, f.forum_topics, t.topic_poster  FROM " . TOPICS_TABLE . " t, " . FORUMS_TABLE . " f
		WHERE t.topic_id = " . $topic_id . " AND f.forum_id = t.forum_id";
	if(!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_MESSAGE, 'Topic_post_not_exist');
	}
	$topic_row = $db->sql_fetchrow($result);
	$forum_topics = ($topic_row['forum_topics'] == '0') ? '1' : $topic_row['forum_topics'];
	$forum_id = $topic_row['forum_id'];
	$forum_name = get_object_lang(POST_FORUM_URL . $topic_row['forum_id'], 'name');
}
elseif(!empty($forum_id))
{
	$sql = "SELECT COUNT(t.topic_id) AS total_topics FROM " . TOPICS_TABLE . " t WHERE t.forum_id = " . $forum_id . " $where_type";
	if(!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_MESSAGE, 'Forum_not_exist');
	}
	$topic_row = $db->sql_fetchrow($result);
	$forum_topics = $topic_row['total_topics'];
}
else
{
	message_die(GENERAL_MESSAGE, 'Forum_not_exist');
}

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

if($sid == '' || $sid != $userdata['session_id'])
{
	message_die(GENERAL_ERROR, 'Invalid_session');
}

if(isset($_POST['cancel']))
{
	if($topic_id)
	{
		$redirect = VIEWTOPIC_MG . '?' . POST_TOPIC_URL . '=' . $topic_id;
	}
	elseif($forum_id)
	{
		$redirect = VIEWFORUM_MG . '?' . POST_FORUM_URL . '=' . $forum_id;
	}
	else
	{
		$redirect = FORUM_MG;
	}
	redirect(append_sid($redirect, true));
}

if(!function_exists('find_names'))
{
	function find_names($id)
	{
		global $db;

		$sql = "SELECT forum_name FROM " . FORUMS_TABLE . " WHERE forum_id = $id";
		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'error finding forum.', '', __LINE__, __FILE__, $sql);
		}
		$row = $db->sql_fetchrow($result);
		return $row['forum_name'];
	}
}

if (!($mode == 'quick_title_edit'))
{
	$is_auth = auth(AUTH_ALL, $forum_id, $userdata);

	if (!$is_auth['auth_mod'])
	{
		message_die(GENERAL_MESSAGE, $lang['Not_Moderator'], $lang['Not_Authorised']);
	}
}
else
{
	if ($qtnum > -1)
	{
		$sql_qt = "SELECT * FROM " . TITLE_INFOS_TABLE . " WHERE id = $qtnum";
		if (!($result_qt = $db->sql_query($sql_qt)))
		{
			message_die(GENERAL_MESSAGE, 'Quick Title Add-on does not exist');
		}
		$qt_row = $db->sql_fetchrow($result_qt);
		if ((($userdata['user_level'] == ADMIN) && ($qt_row['admin_auth'] == 0)) || (($userdata['user_level'] == MOD) && ($qt_row['mod_auth'] == 0)) || (($userdata['user_level'] == USER) && ($qt_row['poster_auth'] == 0)) || (($userdata['user_level'] == USER) && ($qt_row['poster_auth'] == 1) && ($userdata['user_id'] != $topic_row['topic_poster'])))
		{
			message_die(GENERAL_MESSAGE, $lang['Not_Authorised']);
		}
	}
	else
	{
		if (($userdata['user_level'] == USER) && ($userdata['user_id'] != $topic_row['topic_poster']))
		{
			message_die(GENERAL_MESSAGE, $lang['Not_Authorised']);
		}
		$qt_row = array('title_info' => '');
	}
}
// End Auth Check

$meta_description = '';
$meta_keywords = '';

switch($mode)
{
	case 'delete':
		if(!$is_auth['auth_delete'])
		{
			message_die(GENERAL_MESSAGE, sprintf($lang['Sorry_auth_delete'], $is_auth['auth_delete_type']));
		}

		$page_title = $lang['Mod_CP'] .' ('. $lang['Delete'] .')';
		include($phpbb_root_path .'includes/page_header.' . $phpEx);

		if($confirm)
		{
			include($phpbb_root_path . 'includes/functions_search.' . $phpEx);

			$topics = (isset($_POST['topic_id_list'])) ? $_POST['topic_id_list'] : array($topic_id);
			$topic_id_sql = '';
			for($i = 0; $i < count($topics); $i++)
			{
				$topic_id_sql .= (($topic_id_sql != '') ? ', ' : '') . intval($topics[$i]);
			}

			$sql = "SELECT topic_id FROM " . TOPICS_TABLE . " WHERE topic_id IN ($topic_id_sql) AND forum_id = $forum_id";
			if(!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'could not get topic id information.', '', __LINE__, __FILE__, $sql);
			}
			$topic_id_sql = '';
			while($row = $db->sql_fetchrow($result))
			{
				$topic_id_sql .= (($topic_id_sql != '') ? ', ' : '') . intval($row['topic_id']);
			}
			if ($topic_id_sql == '')
			{
				message_die(GENERAL_MESSAGE, $lang['None_selected']);
			}
			$db->sql_freeresult($result);

			$sql = "SELECT poster_id, COUNT(post_id) AS posts FROM " . POSTS_TABLE . "
				WHERE topic_id IN ($topic_id_sql) GROUP BY poster_id";
			if(!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'could not get poster id information.', '', __LINE__, __FILE__, $sql);
			}
			$count_sql = array();
			while($row = $db->sql_fetchrow($result))
			{
				$count_sql[] = "UPDATE " . USERS_TABLE . " SET user_posts = user_posts - " . $row['posts'] . "
						WHERE user_id = " . $row['poster_id'];
			}
			$db->sql_freeresult($result);

			if(count($count_sql))
			{
				for($i = 0; $i < count($count_sql); $i++)
				{
					if(!$db->sql_query($count_sql[$i]))
					{
						message_die(GENERAL_ERROR, 'could not update user post count information.', '', __LINE__, __FILE__, $sql);
					}
				}
			}

			$sql = "SELECT post_id FROM " . POSTS_TABLE . " WHERE topic_id IN ($topic_id_sql)";
			if(!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'could not get post id information.', '', __LINE__, __FILE__, $sql);
			}
			$post_id_sql = '';
			while($row = $db->sql_fetchrow($result))
			{
				$post_id_sql .= (($post_id_sql != '') ? ', ' : '') . intval($row['post_id']);
			}
			$db->sql_freeresult($result);

			$sql = "SELECT vote_id FROM " . VOTE_DESC_TABLE . " WHERE topic_id IN ($topic_id_sql)";
			if(!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'could not get vote id information.', '', __LINE__, __FILE__, $sql);
			}
			$vote_id_sql = '';
			while($row = $db->sql_fetchrow($result))
			{
				$vote_id_sql .= (($vote_id_sql != '') ? ', ' : '') . $row['vote_id'];
			}
			$db->sql_freeresult($result);
			$sql = "DELETE FROM " . THANKS_TABLE . "
					WHERE topic_id IN ($topic_id_sql)";
			if (!$db->sql_query($sql, BEGIN_TRANSACTION))
			{
				message_die(GENERAL_ERROR, 'Error in deleting Thanks post Information', '', __LINE__, __FILE__, $sql);
			}
			$sql = "DELETE FROM " . TOPICS_TABLE . " WHERE topic_id IN ($topic_id_sql) OR topic_moved_id IN ($topic_id_sql)";
			if(!$db->sql_query($sql, BEGIN_TRANSACTION))
			{
				message_die(GENERAL_ERROR, 'could not delete topics.', '', __LINE__, __FILE__, $sql);
			}
			$sql = "DELETE FROM " . BOOKMARK_TABLE . "
				WHERE topic_id IN ($topic_id_sql)";
			if (!$db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not delete bookmarks', '', __LINE__, __FILE__, $sql);
			}

			if($post_id_sql != '')
			{
				$sql = "DELETE FROM " . POSTS_TABLE . " WHERE post_id IN ($post_id_sql)";
				if(!$db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'could not delete posts.', '', __LINE__, __FILE__, $sql);
				}

				$sql = "DELETE FROM " . POSTS_TEXT_TABLE . " WHERE post_id IN ($post_id_sql)";
				if(!$db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'could not delete posts text.', '', __LINE__, __FILE__, $sql);
				}
				remove_search_post($post_id_sql);
			}

			if($vote_id_sql != '')
			{
				$sql = "DELETE FROM " . VOTE_DESC_TABLE . " WHERE vote_id IN ($vote_id_sql)";
				if(!$db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'could not delete vote descriptions.', '', __LINE__, __FILE__, $sql);
				}

				$sql = "DELETE FROM " . VOTE_RESULTS_TABLE . " WHERE vote_id IN ($vote_id_sql)";
				if(!$db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'could not delete vote results.', '', __LINE__, __FILE__, $sql);
				}

				$sql = "DELETE FROM " . VOTE_USERS_TABLE . " WHERE vote_id IN ($vote_id_sql)";
				if(!$db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'could not delete vote users.', '', __LINE__, __FILE__, $sql);
				}
			}

			$sql = "DELETE FROM " . TOPICS_WATCH_TABLE . " WHERE topic_id IN ($topic_id_sql)";
			if(!$db->sql_query($sql, END_TRANSACTION))
			{
				message_die(GENERAL_ERROR, 'could not delete watched post list.', '', __LINE__, __FILE__, $sql);
			}

			if(!empty($topic_id))
			{
				$redirect_page = VIEWFORUM_MG . '?' . POST_FORUM_URL . '=' . $forum_id . '&amp;sid=' . $userdata['session_id'];
				$l_redirect = sprintf($lang['Click_return_forum'], '<a href="'. $redirect_page .'">', '</a>');
			}
			else
			{
				$redirect_page = 'modcp.' . $phpEx . '?' . POST_FORUM_URL . '=' . $forum_id . '&amp;sid=' . $userdata['session_id'];
				$l_redirect = sprintf($lang['Click_return_modcp'], '<a href="'. $redirect_page .'">', '</a>') .'<br \><br \>'. sprintf($lang['Click_return_forum'], '<a href="' . VIEWFORUM_MG . '?' . POST_FORUM_URL . '=' . $forum_id . '&amp;sid=' . $userdata['session_id'] .'">', '</a>');
			}
			$template->assign_vars(array('META' => '<meta http-equiv="refresh" content="3;url=' . $redirect_page . '">'));

			$db->clear_cache('posts_');
			$db->clear_cache('forums_');
			sync('forum', $forum_id);

			message_die(GENERAL_MESSAGE, ((count($topics) == '1') ? $lang['Mod_CP_topic_removed'] : $lang['Topics_Removed']) . '<br /><br />' . $l_redirect);
		}
		else
		{
			if(empty($_POST['topic_id_list']) && empty($topic_id))
			{
				message_die(GENERAL_MESSAGE, $lang['None_selected']);
			}

			$hidden_fields = '<input type="hidden" name="sid" value="' . $userdata['session_id'] . '" /><input type="hidden" name="mode" value="' . $mode . '" /><input type="hidden" name="type" value="' . $type . '" /><input type="hidden" name="'. POST_FORUM_URL .'" value="' . $forum_id . '" />';
			if(isset($_POST['topic_id_list']))
			{
				$topics = $_POST['topic_id_list'];
				for($i = 0; $i < count($topics); $i++)
				{
					$hidden_fields .= '<input type="hidden" name="topic_id_list[]" value="'. intval($topics[$i]) .'" />';
				}
			}
			else
			{
				$hidden_fields .= '<input type="hidden" name="'. POST_TOPIC_URL .'" value="'. $topic_id .'" />';
			}

			$template->set_filenames(array('confirm' => 'confirm_body.tpl'));
			$template->assign_vars(array(
				'MESSAGE_TITLE' => $lang['Confirm'],
				'MESSAGE_TEXT' => $lang['Confirm_delete_topic'],
				'L_YES' => $lang['Yes'],
				'L_NO' => $lang['No'],
				'S_CONFIRM_ACTION' => append_sid('modcp.' . $phpEx),
				'S_HIDDEN_FIELDS' => $hidden_fields,
				)
			);
			$template->pparse('confirm');
			include($phpbb_root_path . 'includes/page_tail.' . $phpEx);
		}
		break;

	case 'poll_delete':
		if(!$is_auth['auth_pollcreate'])
		{
			message_die(MESSAGE, sprintf($lang['Sorry_auth_delete'], $is_auth['auth_delete_type']));
		}

		$page_title = $lang['Mod_CP'] .' ('. $lang['Delete_poll'] .')';
		include($phpbb_root_path . 'includes/page_header.' . $phpEx);

		if($confirm)
		{
			$topics = (isset($_POST['topic_id_list'])) ? $_POST['topic_id_list'] : array($topic_id);
			$topic_id_sql = '';
			for($i = 0; $i < count($topics); $i++)
			{
				$topic_id_sql .= (($topic_id_sql != '') ? ', ' : '') . intval($topics[$i]);
			}

			$sql = "UPDATE " . TOPICS_TABLE . " SET topic_vote = '0' WHERE topic_id IN ($topic_id_sql)";
			if(!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'could not reset topic vote.', '', __LINE__, __FILE__, $sql);
			}

			$sql = "SELECT vote_id FROM " . VOTE_DESC_TABLE . " WHERE topic_id IN ($topic_id_sql)";
			if(!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'could not get vote id information.', '', __LINE__, __FILE__, $sql);
			}
			$vote_id_sql = '';
			while($row = $db->sql_fetchrow($result))
			{
				$vote_id_sql .= (($vote_id_sql != '') ? ', ' : '') . $row['vote_id'];
			}
			$db->sql_freeresult($result);

			if($vote_id_sql != '')
			{
				$sql = "DELETE FROM " . VOTE_DESC_TABLE . " WHERE vote_id IN ($vote_id_sql)";
				if(!$db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'could not delete vote descriptions.', '', __LINE__, __FILE__, $sql);
				}

				$sql = "DELETE FROM " . VOTE_RESULTS_TABLE . " WHERE vote_id IN ($vote_id_sql)";
				if(!$db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'could not delete vote results.', '', __LINE__, __FILE__, $sql);
				}

				$sql = "DELETE FROM " . VOTE_USERS_TABLE . " WHERE vote_id IN ($vote_id_sql)";
				if(!$db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'could not delete vote users.', '', __LINE__, __FILE__, $sql);
				}
			}

			$redirect_page = 'modcp.' . $phpEx . '?' . POST_FORUM_URL . '=' . $forum_id . '&amp;sid=' . $userdata['session_id'];
			$l_redirect = sprintf($lang['Click_return_modcp'], '<a href="'. $redirect_page .'">', '</a>') .'<br \><br \>'. sprintf($lang['Click_return_forum'], '<a href="'. VIEWFORUM_MG . '?' . POST_FORUM_URL . '=' . $forum_id . '&amp;sid=' . $userdata['session_id'] .'">', '</a>');
			$template->assign_vars(array('META' => '<meta http-equiv="refresh" content="3;url='. $redirect_page .'">'));

			$db->clear_cache('posts_');

			message_die(GENERAL_MESSAGE, ((count($topics) == '1') ? $lang['Mod_CP_poll_removed'] : $lang['Mod_CP_polls_removed']) .'<br /><br />'. $l_redirect);
		}
		else
		{
			if(empty($_POST['topic_id_list']) && empty($topic_id))
			{
				message_die(GENERAL_MESSAGE, $lang['None_selected']);
			}

			$hidden_fields = '<input type="hidden" name="sid" value="' . $userdata['session_id'] . '" /><input type="hidden" name="mode" value="' . $mode . '" /><input type="hidden" name="type" value="' . $type . '" /><input type="hidden" name="' . POST_FORUM_URL . '" value="' . $forum_id . '" />';
			if(isset($_POST['topic_id_list']))
			{
				$topics = $_POST['topic_id_list'];
				for($i = 0; $i < count($topics); $i++)
				{
					$hidden_fields .= '<input type="hidden" name="topic_id_list[]" value="' . intval($topics[$i]) . '" />';
				}
			}
			else
			{
				$hidden_fields .= '<input type="hidden" name="' . POST_TOPIC_URL . '" value="' . $topic_id . '" />';
			}

			$template->set_filenames(array('confirm' => 'confirm_body.tpl'));
			$template->assign_vars(array(
				'MESSAGE_TITLE' => $lang['Confirm'],
				'MESSAGE_TEXT' => (count($topics) == '1') ? $lang['Confirm_delete_poll'] : $lang['Mod_CP_confirm_delete_polls'],
				'L_YES' => $lang['Yes'],
				'L_NO' => $lang['No'],
				'S_CONFIRM_ACTION' => append_sid('modcp.' . $phpEx),
				'S_HIDDEN_FIELDS' => $hidden_fields,
				)
			);
			$template->pparse('confirm');
			include($phpbb_root_path . 'includes/page_tail.' . $phpEx);
		}
		break;

	case 'move':
		$page_title = $lang['Mod_CP'] .' ('. $lang['Move'].')';
		include($phpbb_root_path . 'includes/page_header.' . $phpEx);

		if($confirm)
		{
			$new_forum_id = intval($_POST['new_forum']);
			$fid = $_POST['new_forum'];
			if ($fid == 'Root')
			{
				$type = POST_CAT_URL;
				$new_forum_id = 0;
			}
			else
			{
				$type = substr($fid, 0, 1);
				$new_forum_id = ($type == POST_FORUM_URL) ? intval(substr($fid, 1)) : 0;
			}
			if ($new_forum_id <= 0)
			{
				message_die(GENERAL_MESSAGE, $lang['Forum_not_exist']);
			}
			$old_forum_id = $forum_id;
			if($new_forum_id != $old_forum_id)
			{
				$topics = (isset($_POST['topic_id_list'])) ?  $_POST['topic_id_list'] : array($topic_id);
				$topic_list = '';
				for($i = 0; $i < count($topics); $i++)
				{
					$topic_list .= (($topic_list != '') ? ', ' : '') . intval($topics[$i]);
				}

				$sql = "SELECT * FROM " . TOPICS_TABLE . "
					WHERE topic_id IN ($topic_list) AND forum_id = $old_forum_id AND topic_status <> " . TOPIC_MOVED;
				if(!($result = $db->sql_query($sql, BEGIN_TRANSACTION)))
				{
					message_die(GENERAL_ERROR, 'could not select from topic table.', '', __LINE__, __FILE__, $sql);
				}
				$row = $db->sql_fetchrowset($result);
				$db->sql_freeresult($result);
				for($i = 0; $i < count($row); $i++)
				{
					$topic_id = $row[$i]['topic_id'];
					if(isset($_POST['move_leave_shadow']))
					{
						$sql = "INSERT INTO " . TOPICS_TABLE . " (forum_id, topic_title, topic_poster, topic_time, topic_status, topic_type, topic_vote, topic_views, topic_replies, topic_first_post_id, topic_last_post_id, topic_moved_id)
							VALUES ($old_forum_id, '" . addslashes(str_replace("\'", "''", $row[$i]['topic_title'])) . "', '" . str_replace("\'", "''", $row[$i]['topic_poster']) . "', " . $row[$i]['topic_time'] . ", " . TOPIC_MOVED . ", " . POST_NORMAL . ", " . $row[$i]['topic_vote'] . ", " . $row[$i]['topic_views'] . ", " . $row[$i]['topic_replies'] . ", " . $row[$i]['topic_first_post_id'] . ", " . $row[$i]['topic_last_post_id'] . ", $topic_id)";
						if(!$db->sql_query($sql))
						{
							message_die(GENERAL_ERROR, 'could not insert shadow topic.', '', __LINE__, __FILE__, $sql);
						}
					}

					$sql = "UPDATE " . TOPICS_TABLE . " SET forum_id = $new_forum_id WHERE topic_id = $topic_id";
					if(!$db->sql_query($sql))
					{
						message_die(GENERAL_ERROR, 'could not update old topic.', '', __LINE__, __FILE__, $sql);
					}

					$sql = "UPDATE " . POSTS_TABLE . " SET forum_id = $new_forum_id WHERE topic_id = $topic_id";
					if(!$db->sql_query($sql))
					{
						message_die(GENERAL_ERROR, 'could not update post topic ids.', '', __LINE__, __FILE__, $sql);
					}
				}
				$db->clear_cache('posts_');
				$db->clear_cache('forums_');
				sync('forum', $new_forum_id);
				sync('forum', $old_forum_id);
				$message = ((count($topics) == '1') ? sprintf($lang['Mod_CP_topic_moved'], find_names($old_forum_id), find_names($new_forum_id)) : sprintf($lang['Mod_CP_topics_moved'], find_names($old_forum_id), find_names($new_forum_id))) .'<br /><br />';
			}
			else
			{
				$message = $lang['No_Topics_Moved'] .'<br /><br />';
			}

			if(!empty($topic_id))
			{
				$redirect_page = VIEWTOPIC_MG . '?' . POST_TOPIC_URL . '=' . $topic_id . '&amp;sid=' . $userdata['session_id'];
				$message .= sprintf($lang['Click_return_topic'], '<a href="'. $redirect_page .'">', '</a>');
			}
			else
			{
				$redirect_page = 'modcp.' . $phpEx . '?' . POST_FORUM_URL . '=' . $forum_id . '&amp;sid=' . $userdata['session_id'];
				$message .= sprintf($lang['Click_return_modcp'], '<a href="'. $redirect_page .'">', '</a>');
			}
			$template->assign_vars(array('META' => '<meta http-equiv="refresh" content="3;url='. $redirect_page .'">'));
			message_die(GENERAL_MESSAGE, $message .'<br \><br \>'. sprintf($lang['Click_return_forum'], '<a href="'. VIEWFORUM_MG . '?' . POST_FORUM_URL . '=' . $old_forum_id . '&amp;sid=' . $userdata['session_id'] .'">', '</a>'));
		}
		else
		{
			if(empty($_POST['topic_id_list']) && empty($topic_id))
			{
				message_die(GENERAL_MESSAGE, $lang['None_selected']);
			}

			$hidden_fields = '<input type="hidden" name="sid" value="' . $userdata['session_id'] . '" /><input type="hidden" name="mode" value="' . $mode . '" /><input type="hidden" name="type" value="' . $type . '" /><input type="hidden" name="' . POST_FORUM_URL . '" value="' . $forum_id . '" />';
			if(isset($_POST['topic_id_list']))
			{
				$topics = $_POST['topic_id_list'];
				for($i = 0; $i < count($topics); $i++)
				{
					$hidden_fields .= '<input type="hidden" name="topic_id_list[]" value="' . intval($topics[$i]) . '" />';
				}
			}
			else
			{
				$hidden_fields .= '<input type="hidden" name="' . POST_TOPIC_URL . '" value="' . $topic_id . '" />';
			}

			$template->set_filenames(array('movetopic' => 'modcp_move.tpl'));
			$template->assign_vars(array(
				'MESSAGE_TITLE' => $lang['Confirm'],
				'MESSAGE_TEXT' => $lang['Confirm_move_topic'],
				'L_MOVE_TO_FORUM' => $lang['Move_to_forum'],
				'L_LEAVESHADOW' => $lang['Leave_shadow_topic'],
				'L_YES' => $lang['Yes'],
				'L_NO' => $lang['No'],
				'S_FORUM_SELECT' => selectbox('new_forum', $forum_id),
				'S_MODCP_ACTION' => append_sid('modcp.' . $phpEx),
				'S_HIDDEN_FIELDS' => $hidden_fields,
				)
			);

			$template->pparse('movetopic');

			include($phpbb_root_path . 'includes/page_tail.' . $phpEx);
		}
		break;

	case 'lock':
	case 'unlock':
		if(empty($_POST['topic_id_list']) && empty($topic_id))
		{
			message_die(GENERAL_MESSAGE, $lang['None_selected']);
		}

		$topics = (isset($_POST['topic_id_list'])) ?  $_POST['topic_id_list'] : array($topic_id);
		$topic_id_sql = '';
		for($i = 0; $i < count($topics); $i++)
		{
			$topic_id_sql .= (($topic_id_sql != '') ? ', ' : '') . intval($topics[$i]);
		}

		$sql = "UPDATE " . TOPICS_TABLE . " SET topic_status = " . (($mode == 'lock') ? TOPIC_LOCKED : TOPIC_UNLOCKED) . "
			WHERE topic_id IN ($topic_id_sql) AND forum_id = $forum_id AND topic_moved_id = 0";
		if(!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'could not update topics table.', '', __LINE__, __FILE__, $sql);
		}

		if(!empty($topic_id))
		{
			$redirect_page = VIEWTOPIC_MG . '?' . POST_TOPIC_URL . '=' . $topic_id . '&amp;sid=' . $userdata['session_id'];
			$message = sprintf($lang['Click_return_topic'], '<a href="'. $redirect_page .'">', '</a>');
		}
		else
		{
			$redirect_page = 'modcp.' . $phpEx . '?' . POST_FORUM_URL . '=' . $forum_id . '&amp;sid=' . $userdata['session_id'];
			$message = sprintf($lang['Click_return_modcp'], '<a href="'. $redirect_page .'">', '</a>');
		}

		$template->assign_vars(array('META' => '<meta http-equiv="refresh" content="3;url='. $redirect_page .'">'));

		$db->clear_cache('posts_');

		if($mode == 'lock')
		{
			message_die(GENERAL_MESSAGE, ((count($topics) == '1') ? $lang['Mod_CP_topic_locked'] : $lang['Topics_Locked']) .'<br /><br />'. $message . '<br \><br \>' . sprintf($lang['Click_return_forum'], '<a href="' . VIEWFORUM_MG . '?' . POST_FORUM_URL . '=' . $forum_id . '&amp;sid=' . $userdata['session_id'] . '">', '</a>'));
		}
		elseif($mode == 'unlock')
		{
			message_die(GENERAL_MESSAGE, ((count($topics) == '1') ? $lang['Mod_CP_topic_unlocked'] : $lang['Topics_Unlocked']) .'<br /><br />'. $message . '<br \><br \>'. sprintf($lang['Click_return_forum'], '<a href="' . VIEWFORUM_MG . '?' . POST_FORUM_URL . '=' . $forum_id . '&amp;sid=' . $userdata['session_id'] . '">', '</a>'));
		}
		break;

	case 'sticky':
	case 'announce':
	case 'super_announce':
	case 'normalize':
		if($mode == 'sticky' && !$is_auth['auth_sticky'])
		{
			$message = sprintf($lang['Sorry_auth_sticky'], $is_auth['auth_sticky_type']);
			message_die(GENERAL_MESSAGE, $message);
		}
		if($mode == 'announce' && !$is_auth['auth_announce'])
		{
			$message = sprintf($lang['Sorry_auth_announce'], $is_auth['auth_announce_type']);
			message_die(GENERAL_MESSAGE, $message);
		}
		if($mode == 'super_announce' && !$is_auth['auth_globalannounce'])
		{
			$message = sprintf($lang['Sorry_auth_announce'], $is_auth['auth_announce_type']);
			message_die(GENERAL_MESSAGE, $message);
		}
		if(empty($_POST['topic_id_list']) && empty($topic_id))
		{
			message_die(GENERAL_MESSAGE, $lang['None_selected']);
		}

		$topics = (isset($_POST['topic_id_list'])) ?  $_POST['topic_id_list'] : array($topic_id);
		$topic_id_sql = '';
		for($i = 0; $i < count($topics); $i++)
		{
			$topic_id_sql .= (($topic_id_sql != "") ? ', ' : '') . $topics[$i];
		}

		if($mode == 'sticky')
		{
			$topic_type = POST_STICKY;
		}
		elseif($mode == 'announce')
		{
			$topic_type = POST_ANNOUNCE;
		}
		elseif($mode == 'super_announce')
		{
			$topic_type = POST_GLOBAL_ANNOUNCE;
		}
		elseif($mode == 'normalize')
		{
			$topic_type = POST_NORMAL;
		}
		$sql = "UPDATE " . TOPICS_TABLE . " SET topic_type = " . $topic_type . " WHERE topic_id IN ($topic_id_sql) AND topic_moved_id = 0";
		if(!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'could not update topics table.', '', __LINE__, __FILE__, $sql);
		}

		if(!empty($topic_id))
		{
			$redirect_page = append_sid(VIEWTOPIC_MG . '?' . POST_TOPIC_URL . '=' . $topic_id);
			$message = sprintf($lang['Click_return_topic'], '<a href="'. $redirect_page .'">', '</a>');
		}
		else
		{
			$redirect_page = 'modcp.' . $phpEx . '?' . POST_FORUM_URL . '=' . $forum_id . '&sid=' . $userdata['session_id'];
			$message = sprintf($lang['Click_return_modcp'], '<a href="'. $redirect_page .'">', '</a>');
		}

		$message = $message . '<br \><br \>' . sprintf($lang['Click_return_forum'], '<a href="'. append_sid(VIEWFORUM_MG . '?' . POST_FORUM_URL . '=' . $forum_id) .'">', '</a>');
		$template->assign_vars(array('META' => '<meta http-equiv="refresh" content="3;url='. $redirect_page .'">'));
		switch($mode)
		{
			case 'sticky':
				$message = ((count($topics) == '1') ? $lang['Mod_CP_topic_sticked'] : $lang['Mod_CP_topics_sticked']) .'<br /><br />'. $message; break;
			case 'announce':
				$message = ((count($topics) == '1') ? $lang['Mod_CP_topic_announced'] : $lang['Mod_CP_topics_announced']) .'<br /><br />'. $message; break;
			case 'super_announce':
				$message = ((count($topics) == '1') ? $lang['Mod_CP_topic_globalized'] : $lang['Mod_CP_topics_globalized']) .'<br /><br />'. $message; break;
			case 'normalize':
				$message = ((count($topics) == '1') ? $lang['Mod_CP_topic_normalized'] : $lang['Mod_CP_topics_normalized']) .'<br /><br />'. $message; break;
		}

		$db->clear_cache('posts_');

		message_die(GENERAL_MESSAGE, $message);
		break;
	case 'merge':
		$page_title = $lang['Mod_CP'];
		include($phpbb_root_path . 'includes/page_header.' . $phpEx);

		if ($confirm)
		{
			if (empty($_POST['topic_id_list']) && empty($topic_id))
			{
				message_die(GENERAL_MESSAGE, $lang['None_selected']);
			}

			if (empty($_POST['topic_id_list']) && empty($topic_id))
			{
				message_die(GENERAL_MESSAGE, $lang['None_selected']);
			}

			$new_topic_id = $_POST['new_topic'];
			$topic_id_list = (isset($_POST['topic_id_list'])) ? $_POST['topic_id_list'] : array($topic_id);

			for ($i=0; $i < count($topic_id_list); $i++)
			{
				$old_topic_id = $topic_id_list[$i];

				if ($new_topic_id != $old_topic_id)
				{
					$sql = "UPDATE " . POSTS_TABLE . "
						SET topic_id = $new_topic_id
						WHERE topic_id = $topic_id_list[$i]";

					if (!($result = $db->sql_query($sql, BEGIN_TRANSACTION)))
					{
						message_die(GENERAL_ERROR, 'Could not update posts', '', __LINE__, __FILE__, $sql);
					}

//<!-- BEGIN Unread Post Information to Database Mod -->
					if($userdata['upi2db_access'])
					{
						/*
						$sql = "DELETE FROM " . UPI2DB_UNREAD_POSTS_TABLE . "
							WHERE topic_id IN ($topic_id_sql)";
						*/
						$sql = "DELETE FROM " . UPI2DB_UNREAD_POSTS_TABLE . "
							WHERE topic_id IN ($topic_id_list[$i])";

						if (!$db->sql_query($sql))
						{
							message_die(GENERAL_ERROR, 'Could not delete topic reads', '', __LINE__, __FILE__, $sql);
						}

						/*
						$sql = "DELETE FROM " . UPI2DB_LAST_POSTS_TABLE . "
							WHERE topic_id IN ($topic_id_sql)";
						*/
						$sql = "DELETE FROM " . UPI2DB_LAST_POSTS_TABLE . "
							WHERE topic_id IN ($topic_id_list[$i])";

						if (!$db->sql_query($sql))
						{
							message_die(GENERAL_ERROR, 'Could not delete topic reads', '', __LINE__, __FILE__, $sql);
						}
					}
//<!-- END Unread Post Information to Database Mod -->

					$sql = "DELETE FROM " . TOPICS_TABLE . "
						WHERE topic_id = $topic_id_list[$i]";

					if (!($result = $db->sql_query($sql, BEGIN_TRANSACTION)))
					{
						message_die(GENERAL_ERROR, 'Could not update posts', '', __LINE__, __FILE__, $sql);
					}

					$sql = "DELETE FROM  " . TOPICS_WATCH_TABLE . "
						WHERE topic_id = $topic_id_list[$i]";

					if (!($result = $db->sql_query($sql, BEGIN_TRANSACTION)))
					{
						message_die(GENERAL_ERROR, 'Could not update posts', '', __LINE__, __FILE__, $sql);
					}

					// Sync the forum indexes
					$db->clear_cache('posts_');
					$db->clear_cache('forums_');
					sync('forum', $forum_id);
					sync('topic', $new_topic_id);

					$message = $lang['Topics_Merged'] . '<br /><br />';
				}
				else
				{
					$message = $lang['No_Topics_Merged'] . '<br /><br />';
				}

			} // end for

			if (!empty($topic_id))
			{
				$redirect_page = VIEWTOPIC_MG . '?' . POST_TOPIC_URL . '=' . $topic_id . '&amp;sid=' . $userdata['session_id'];
				$message .= sprintf($lang['Click_return_topic'], '<a href="' . $redirect_page . '">', '</a>');
			}
			else
			{
				$redirect_page = 'modcp.' . $phpEx . '?' . POST_FORUM_URL . '=' . $forum_id . '&amp;sid=' . $userdata['session_id'];
				$message .= sprintf($lang['Click_return_modcp'], '<a href="' . $redirect_page . '">', '</a>');
			}

			$message = $message . '<br \><br \>' . sprintf($lang['Click_return_forum'], '<a href="' . append_sid(VIEWFORUM_MG . '?' . POST_FORUM_URL . '=' . $forum_id) . '">', '</a>');

			$template->assign_vars(array('META' => '<meta http-equiv="refresh" content="3;url=' . $redirect_page . '">'));

			message_die(GENERAL_MESSAGE, $message);
		}
		else
		{
			if (empty($_POST['topic_id_list']) && empty($topic_id))
			{
				message_die(GENERAL_MESSAGE, $lang['None_selected']);
			}

			$hidden_fields = '<input type="hidden" name="mode" value="' . $mode . '" /><input type="hidden" name="' . POST_FORUM_URL . '" value="' . $forum_id . '" />';
			$hidden_fields .= '<input type="hidden" name="sid" value="' . $userdata['session_id'] . '" />';

			if (isset($_POST['topic_id_list']))
			{
				$topics = $_POST['topic_id_list'];

				for($i = 0; $i < count($topics); $i++)
				{
					$hidden_fields .= '<input type="hidden" name="topic_id_list[]" value="' . intval($topics[$i]) . '" />';
				}
			}
			else
			{
				$hidden_fields .= '<input type="hidden" name="' . POST_TOPIC_URL . '" value="' . $topic_id . '" />';
			}

			// Set template files
			$template->set_filenames(array('mergetopic' => 'modcp_merge.tpl'));

			$template->assign_vars(array(
				'MESSAGE_TITLE' => $lang['Confirm'],
				'MESSAGE_TEXT' => $lang['Confirm_merge_topic'],

				'L_MERGE_TOPIC' => $lang['Merge_topic'],

				'L_YES' => $lang['Yes'],
				'L_NO' => $lang['No'],

				'S_TOPIC_SELECT' => make_topic_select('new_topic', $forum_id),
				'S_MODCP_ACTION' => append_sid('modcp.' . $phpEx),
				'S_HIDDEN_FIELDS' => $hidden_fields
				)
			);

			$template->pparse('mergetopic');

			include($phpbb_root_path . 'includes/page_tail.' . $phpEx);
		}
		break;
	case 'split':
		$page_title = $lang['Mod_CP'] . ' (' . $lang['Split'] . ')';
		include($phpbb_root_path . 'includes/page_header.' . $phpEx);

		$post_id_sql = '';
		if(isset($_POST['split_type_all']) || isset($_POST['split_type_beyond']))
		{
			$posts = $_POST['post_id_list'];
			for($i = 0; $i < count($posts); $i++)
			{
				$post_id_sql .= (($post_id_sql != '') ? ', ' : '') . intval($posts[$i]);
			}
		}

		if($post_id_sql != '')
		{
			$sql = "SELECT post_id FROM " . POSTS_TABLE . " WHERE post_id IN ($post_id_sql) AND forum_id = $forum_id";
			if(!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'could not get post id information.', '', __LINE__, __FILE__, $sql);
			}
			$post_id_sql = '';
			while($row = $db->sql_fetchrow($result))
			{
				$post_id_sql .= (($post_id_sql != '') ? ', ' : '') . intval($row['post_id']);
			}
			if ($post_id_sql == '')
			{
				message_die(GENERAL_MESSAGE, $lang['None_selected']);
			}
			$db->sql_freeresult($result);

			$sql = "SELECT post_id, poster_id, topic_id, post_time FROM " . POSTS_TABLE . "
				WHERE post_id IN ($post_id_sql) ORDER BY post_time ASC";
			if(!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'could not get post information.', '', __LINE__, __FILE__, $sql);
			}
			if($row = $db->sql_fetchrow($result))
			{
				$first_poster = $row['poster_id'];
				$topic_id = $row['topic_id'];
				$post_time = $row['post_time'];

				$user_id_sql = '';
				$post_id_sql = '';
				do
				{
					$user_id_sql .= (($user_id_sql != '') ? ', ' : '') . intval($row['poster_id']);
					$post_id_sql .= (($post_id_sql != '') ? ', ' : '') . intval($row['post_id']);;
				}
				while ($row = $db->sql_fetchrow($result));

				$post_subject = trim(htmlspecialchars($_POST['subject']));
				if(empty($post_subject))
				{
					message_die(GENERAL_MESSAGE, $lang['Empty_subject']);
				}

				$fid = $_POST['new_forum_id'];
				if ($fid == 'Root')
				{
					$type = POST_CAT_URL;
					$new_forum_id = 0;
				}
				else
				{
					$type = substr($fid, 0, 1);
					$new_forum_id = ($type == POST_FORUM_URL) ? intval(substr($fid, 1)) : 0;
				}

				if ($new_forum_id <= 0)
				{
					message_die(GENERAL_MESSAGE, $lang['Forum_not_exist']);
				}

				$topic_time = time();

				$sql  = "INSERT INTO " . TOPICS_TABLE . " (topic_title, topic_poster, topic_time, forum_id, topic_status, topic_type)
					VALUES ('" . str_replace("\'", "''", $post_subject) . "', $first_poster, " . $topic_time . ", $new_forum_id, " . TOPIC_UNLOCKED . ", " . POST_NORMAL . ")";
				if(!($db->sql_query($sql, BEGIN_TRANSACTION)))
				{
					message_die(GENERAL_ERROR, 'could not insert new topic.', '', __LINE__, __FILE__, $sql);
				}
				$new_topic_id = $db->sql_nextid();

				$sql = "UPDATE " . TOPICS_WATCH_TABLE . " SET topic_id = $new_topic_id
					WHERE topic_id = $topic_id AND user_id IN ($user_id_sql)";
				if(!$db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'could not update topics watch table.', '', __LINE__, __FILE__, $sql);
				}

				$sql_where = (!empty($_POST['split_type_beyond'])) ? " post_time >= $post_time AND topic_id = $topic_id" : "post_id IN ($post_id_sql)";
				$sql = "UPDATE " . POSTS_TABLE . " SET topic_id = $new_topic_id, forum_id = $new_forum_id WHERE $sql_where";
				if(!$db->sql_query($sql, END_TRANSACTION))
				{
					message_die(GENERAL_ERROR, 'could not update posts table.', '', __LINE__, __FILE__, $sql);
				}
//<!-- BEGIN Unread Post Information to Database Mod -->
				$sql_where_upi = (!empty($_POST['split_type_beyond'])) ? " topic_id = $topic_id" : "post_id IN ($post_id_sql)";

				$sql = "UPDATE " . UPI2DB_LAST_POSTS_TABLE . "
					SET topic_id = $new_topic_id, forum_id = $new_forum_id
					WHERE $sql_where_upi";
				if (!$db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'Could not update old topic', '', __LINE__, __FILE__, $sql);
				}
				$sql = "UPDATE " . UPI2DB_UNREAD_POSTS_TABLE . "
					SET topic_id = $new_topic_id, forum_id = $new_forum_id
					WHERE $sql_where_upi";
				if (!$db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'Could not update old topic', '', __LINE__, __FILE__, $sql);
				}
//<!-- END Unread Post Information to Database Mod -->

				$db->clear_cache('posts_');
				$db->clear_cache('forums_');
				sync('topic', $new_topic_id);
				sync('topic', $topic_id);
				sync('forum', $new_forum_id);
				sync('forum', $forum_id);

				$template->assign_vars(array('META' => '<meta http-equiv="refresh" content="3;url='. VIEWTOPIC_MG . '?' . POST_TOPIC_URL . '=' . $topic_id . '&amp;sid=' . $userdata['session_id'] .'">'));

				$message = $lang['Topic_split'] . '<br /><br />' . sprintf($lang['Mod_CP_click_return_topic'], '<a href="' . VIEWTOPIC_MG . '?' . POST_TOPIC_URL . '=' . $topic_id . '&amp;sid=' . $userdata['session_id'] . '">', '</a>', '<a href="' . VIEWTOPIC_MG . '?' . POST_TOPIC_URL . '=' . $new_topic_id . '&amp;sid=' . $userdata['session_id'] . '">', '</a>').'<br /><br />'. sprintf($lang['Click_return_modcp'], '<a href="modcp.' . $phpEx . '?' . POST_FORUM_URL . '=' . $forum_id . '&sid=' . $userdata['session_id'] .'">', '</a>').'<br \><br \>'. sprintf($lang['Click_return_forum'], '<a href="'. VIEWFORUM_MG . '?' . POST_FORUM_URL . '=' . $forum_id . '&amp;sid=' . $userdata['session_id'] .'">', '</a>');

				message_die(GENERAL_MESSAGE, $message);
			}
		}
		else
		{
			$template->set_filenames(array('split_body' => 'modcp_split.tpl'));

			$sql = "SELECT u.user_id, u.username, p.*, pt.post_text, pt.bbcode_uid, pt.post_subject, p.post_username
				FROM " . POSTS_TABLE . " p, " . USERS_TABLE . " u, " . POSTS_TEXT_TABLE . " pt
				WHERE p.topic_id = $topic_id
					AND p.poster_id = u.user_id
					AND p.post_id = pt.post_id
				ORDER BY p.post_time ASC";
			if(!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'could not get topic/post information.', '', __LINE__, __FILE__, $sql);
			}

			$s_hidden_fields = '<input type="hidden" name="sid" value="' . $userdata['session_id'] . '" /><input type="hidden" name="' . POST_FORUM_URL . '" value="' . $forum_id . '" /><input type="hidden" name="' . POST_TOPIC_URL . '" value="' . $topic_id . '" /><input type="hidden" name="mode" value="split" />';

			if(($total_posts = $db->sql_numrows($result)) > '0')
			{
				$postrow = $db->sql_fetchrowset($result);

				$template->assign_vars(array(
					'L_SPLIT_TOPIC' => $lang['Split_Topic'],
					'L_SPLIT_TOPIC_EXPLAIN' => $lang['Split_Topic_explain'],
					'L_AUTHOR' => $lang['Author'],
					'L_MESSAGE' => $lang['Message'],
					'L_SPLIT_SUBJECT' => $lang['Split_title'],
					'L_SPLIT_FORUM' => $lang['Split_forum'],
					'L_POSTED' => $lang['Posted'],
					'L_SPLIT_POSTS' => $lang['Split_posts'],
					'L_SUBMIT' => $lang['Submit'],
					'L_SPLIT_AFTER' => $lang['Split_after'],
					'L_POST_SUBJECT' => $lang['Post_subject'],
					'L_POST' => $lang['Post'],
					'FORUM_NAME' => $forum_name,
					'MINIPOST_IMG' => $images['icon_minipost'],
					'U_VIEW_FORUM' => append_sid(VIEWFORUM_MG . '?' . POST_FORUM_URL . '=' . $forum_id),
					'S_SPLIT_ACTION' => append_sid('modcp.' . $phpEx),
					'S_HIDDEN_FIELDS' => $s_hidden_fields,
					'S_FORUM_SELECT' => selectbox('new_forum_id', false, $forum_id),
					)
				);

				$orig_word = array();
				$replacement_word = array();
				obtain_word_list($orig_word, $replacement_word);

				for($i = 0; $i < $total_posts; $i++)
				{
					$bbcode_uid = $postrow[$i]['bbcode_uid'];
					$message = $postrow[$i]['post_text'];
					$post_subject = ($postrow[$i]['post_subject'] != '') ? $postrow[$i]['post_subject'] : $topic_title;
					$post_date = create_date2($board_config['default_dateformat'], $postrow[$i]['post_time'], $board_config['board_timezone']);

					if(!empty($postrow[$i]['post_text_compiled']))
					{
						$message = $postrow[$i]['post_text_compiled'];
					}
					else
					{
						$bbcode->allow_html = ($board_config['allow_html'] && $postrow[$i]['enable_bbcode'] ? true : false);
						$bbcode->allow_bbcode = ($board_config['allow_bbcode'] && $postrow[$i]['enable_bbcode'] ? true : false);
						$bbcode->allow_smilies = ($board_config['allow_smilies'] && $postrow[$i]['enable_smilies'] ? true : false);
						$message = $bbcode->parse($message, $bbcode_uid);
					}
					$checkbox = ($i > 0) ? '<input type="checkbox" name="post_id_list[]" value="' . $postrow[$i]['post_id'] . '" />' : '&nbsp;';

					$template->assign_block_vars('postrow', array(
						'ROW_CLASS' => (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'],
						'POSTER_NAME' => $postrow[$i]['username'],
						'U_PROFILE_COL' => colorize_username($postrow[$i]['user_id']),
						'POST_DATE' => $post_date,
						'POST_SUBJECT' => $post_subject,
						'MESSAGE' => $message,
						'POST_ID' => $postrow[$i]['post_id'],
						'S_SPLIT_CHECKBOX' => $checkbox,
						)
					);
				}
				$template->pparse('split_body');
			}
		}
		break;

	case 'ip':
		$page_title = $lang['Mod_CP'] .' ('. $lang['IP'] .')';
		include($phpbb_root_path .'includes/page_header.' . $phpEx);

		$rdns_ip_num = (isset($_GET['rdns'])) ? $_GET['rdns'] : '';

		if(!$post_id)
		{
			message_die(GENERAL_MESSAGE, $lang['No_such_post']);
		}

		$template->set_filenames(array('viewip' => 'modcp_viewip.tpl'));

		$sql = "SELECT poster_ip, poster_id FROM " . POSTS_TABLE . " WHERE post_id = $post_id AND forum_id = $forum_id";
		if(!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'could not get poster IP information.', '', __LINE__, __FILE__, $sql);
		}
		if(!($post_row = $db->sql_fetchrow($result)))
		{
			message_die(GENERAL_MESSAGE, $lang['No_such_post']);
		}

		$ip_this_post = decode_ip($post_row['poster_ip']);
		$ip_this_post = ($rdns_ip_num == $ip_this_post) ? htmlspecialchars(gethostbyaddr($ip_this_post)) : $ip_this_post;

		$poster_id = $post_row['poster_id'];

		$template->assign_vars(array(
			'L_IP_INFO' => $lang['IP_info'],
			'L_THIS_POST_IP' => $lang['This_posts_IP'],
			'L_OTHER_IPS' => $lang['Other_IP_this_user'],
			'L_OTHER_USERS' => $lang['Users_this_IP'],
			'L_LOOKUP_IP' => $lang['Lookup_IP'],
			'L_SEARCH' => $lang['Search'],
			'SEARCH_IMG' => $images['icon_search'],
			'IP' => $ip_this_post,
			'U_LOOKUP_IP' => 'modcp.' . $phpEx . '?mode=ip&amp;' . POST_POST_URL . '=' . $post_id . '&amp;' . POST_TOPIC_URL . '=' . $topic_id . '&amp;rdns=' . $ip_this_post . '&amp;sid=' . $userdata['session_id'],
			)
		);

		$sql = "SELECT poster_ip, COUNT(*) AS postings FROM " . POSTS_TABLE . " WHERE poster_id = $poster_id
			GROUP BY poster_ip ORDER BY " . ((SQL_LAYER == 'msaccess') ? 'COUNT(*)' : 'postings') . " DESC";
		if(!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'could not get IP information for this user.', '', __LINE__, __FILE__, $sql);
		}
		if($row = $db->sql_fetchrow($result))
		{
			$i = 0;
			do
			{
				if($row['poster_ip'] == $post_row['poster_ip'])
				{
					$template->assign_vars(array('POSTS' => $row['postings'] .' '. (($row['postings'] == '1') ? $lang['Post'] : $lang['Posts'])));
					continue;
				}

				$ip = decode_ip($row['poster_ip']);
				$ip = ($rdns_ip_num == $row['poster_ip'] || $rdns_ip_num == 'all') ? htmlspecialchars(gethostbyaddr($ip)) : $ip;

				$template->assign_block_vars('iprow', array(
					'ROW_CLASS' => (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'],
					'IP' => $ip,
					'POSTS' => $row['postings'] .' '. (($row['postings'] == '1') ? $lang['Post'] : $lang['Posts']),
					'U_LOOKUP_IP' => 'modcp.' . $phpEx . '?mode=ip&amp;' . POST_POST_URL . '=' . $post_id . '&amp;' . POST_TOPIC_URL . '=' . $topic_id . '&amp;rdns=' . $row['poster_ip'] . '&amp;sid=' . $userdata['session_id'],
				));
				$i++;
			}
			while($row = $db->sql_fetchrow($result));
		}

		// Get other users who've posted under this IP
		$sql = "SELECT u.user_id, u.username, COUNT(*) as postings FROM " . USERS_TABLE . " u, " . POSTS_TABLE . " p
			WHERE p.poster_id = u.user_id AND p.poster_ip = '" . $post_row['poster_ip'] . "'
			GROUP BY u.user_id, u.username ORDER BY " . ((SQL_LAYER == 'msaccess') ? 'COUNT(*)' : 'postings') . " DESC";
		if(!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'could not get posters information based on IP.', '', __LINE__, __FILE__, $sql);
		}
		if($row = $db->sql_fetchrow($result))
		{
			$i = 0;
			do
			{
				$template->assign_block_vars('userrow', array(
					'ROW_CLASS' => (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'],
					'USERNAME' => ($row['user_id'] == ANONYMOUS) ? $lang['Guest'] : $row['username'],
					'POSTS' => $row['postings'] .' '. (($row['postings'] == '1') ? $lang['Post'] : $lang['Posts']),
					'L_SEARCH_POSTS' => sprintf($lang['Search_user_posts'], (($row['user_id'] == ANONYMOUS) ? $lang['Guest'] : $row['username'])),
					'U_PROFILE' => ($row['user_id'] == ANONYMOUS) ? 'modcp.' . $phpEx . '?mode=ip&amp;' . POST_POST_URL . '=' . $post_id . '&amp;' . POST_TOPIC_URL . '=' . $topic_id . '&amp;sid=' . $userdata['session_id'] : append_sid(PROFILE_MG . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $row['user_id']),
					'U_SEARCHPOSTS' => append_sid(SEARCH_MG . '?search_author=' . (($id == ANONYMOUS) ? 'Anonymous' : urlencode($username)) . '&amp;showresults=topics'),
					'U_PROFILE_COL' => colorize_username($row['user_id']),
					//'U_SEARCHPOSTS' => append_sid(SEARCH_MG . '?search_author=' . urlencode((($row['user_id'] == ANONYMOUS) ? $lang['Guest'] : $row['username'])) . '&amp;showresults=topics'),
					)
				);
				$i++;
			}
			while($row = $db->sql_fetchrow($result));
		}
		$template->pparse('viewip');
		break;
	case 'recycle':
		$page_title = $lang['Mod_CP'];
		include($phpbb_root_path . 'includes/page_header.' . $phpEx);

		if ($confirm_recycle)
		{
			if (($board_config['bin_forum'] == 0) || (empty($_POST['topic_id_list']) && empty($topic_id)))
			{
				$redirect_page = 'modcp.' . $phpEx . '?' . POST_FORUM_URL . '=' . $forum_id . '&amp;sid=' . $userdata['session_id'];
				$message = sprintf($lang['Click_return_modcp'], '<a href="' . $redirect_page . '">', '</a>');
				$message = $message . '<br /><br />' . sprintf($lang['Click_return_forum'], '<a href="' . VIEWFORUM_MG . '?' . POST_FORUM_URL . '=' . $forum_id . '&amp;sid=' . $userdata['session_id'] . '">', '</a>');

				$template->assign_vars(array(
					'META' => '<meta http-equiv="refresh" content="3;url=' . $redirect_page . '">'
					)
				);

				message_die(GENERAL_MESSAGE, $lang['None_selected'] . '<br /><br />' . $message);
			}
			elseif (isset($_POST['topic_id_list']))
			{
				// Define bin forum
				$new_forum_id = intval($board_config['bin_forum']);
				$old_forum_id = $forum_id;

				if ($new_forum_id != $old_forum_id)
				{
					$topics = (isset($_POST['topic_id_list'])) ?  $_POST['topic_id_list'] : array($topic_id);

					$topic_list = '';
					for($i = 0; $i < count($topics); $i++)
					{
						$topic_list .= (($topic_list != '') ? ', ' : '') . intval($topics[$i]);
					}

					$sql = "SELECT *
						FROM " . TOPICS_TABLE . "
						WHERE topic_id IN ($topic_list)
							AND forum_id = $old_forum_id
							AND topic_status <> " . TOPIC_MOVED;
					if (!($result = $db->sql_query($sql, BEGIN_TRANSACTION)))
					{
						message_die(GENERAL_ERROR, 'Could not select from topic table', '', __LINE__, __FILE__, $sql);
					}

					$row = $db->sql_fetchrowset($result);
					$db->sql_freeresult($result);

					for($i = 0; $i < count($row); $i++)
					{
						$topic_id = $row[$i]['topic_id'];

						if (isset($_POST['move_leave_shadow']))
						{
							// Insert topic in the old forum that indicates that the forum has moved.
							$sql = "INSERT INTO " . TOPICS_TABLE . " (forum_id, topic_title, topic_poster, topic_time, topic_status, topic_type, topic_vote, topic_views, topic_replies, topic_first_post_id, topic_last_post_id, topic_moved_id)
								VALUES ($old_forum_id, '" . addslashes(str_replace("\'", "''", $row[$i]['topic_title'])) . "', '" . str_replace("\'", "''", $row[$i]['topic_poster']) . "', " . $row[$i]['topic_time'] . ", " . TOPIC_MOVED . ", " . POST_NORMAL . ", " . $row[$i]['topic_vote'] . ", " . $row[$i]['topic_views'] . ", " . $row[$i]['topic_replies'] . ", " . $row[$i]['topic_first_post_id'] . ", " . $row[$i]['topic_last_post_id'] . ", $topic_id)";
							if (!$db->sql_query($sql))
							{
								message_die(GENERAL_ERROR, 'Could not insert shadow topic', '', __LINE__, __FILE__, $sql);
							}
						}

						$sql = "UPDATE " . TOPICS_TABLE . "
							SET forum_id = $new_forum_id
							WHERE topic_id = $topic_id";
						if (!$db->sql_query($sql))
						{
							message_die(GENERAL_ERROR, 'Could not update old topic', '', __LINE__, __FILE__, $sql);
						}

//<!-- BEGIN Unread Post Information to Database Mod -->
						$sql = "UPDATE " . UPI2DB_LAST_POSTS_TABLE . "
							SET forum_id = $new_forum_id
							WHERE topic_id = $topic_id";
						if (!$db->sql_query($sql))
						{
							message_die(GENERAL_ERROR, 'Could not update old topic', '', __LINE__, __FILE__, $sql);
						}
						$sql = "UPDATE " . UPI2DB_UNREAD_POSTS_TABLE . "
							SET forum_id = $new_forum_id
							WHERE topic_id = $topic_id";
						if (!$db->sql_query($sql))
						{
							message_die(GENERAL_ERROR, 'Could not update old topic', '', __LINE__, __FILE__, $sql);
						}
//<!-- END Unread Post Information to Database Mod -->

						$sql = "UPDATE " . POSTS_TABLE . "
							SET forum_id = $new_forum_id
							WHERE topic_id = $topic_id";
						if (!$db->sql_query($sql))
						{
							message_die(GENERAL_ERROR, 'Could not update post topic ids', '', __LINE__, __FILE__, $sql);
						}
					}

					// Sync the forum indexes
					$db->clear_cache('posts_');
					$db->clear_cache('forums_');
					sync('forum', $new_forum_id);
					sync('forum', $old_forum_id);

					$message = $lang['Topics_Moved_bin'];
				}
				else
				{
					$message = $lang['No_Topics_Moved'];
				}

				$redirect_page = 'modcp.' . $phpEx . '?' . POST_FORUM_URL . '=' . $forum_id . '&amp;sid=' . $userdata['session_id'];
				$message .= '<br /><br />' . sprintf($lang['Click_return_modcp'], '<a href="' . $redirect_page . '">', '</a>');

				$message = $message . '<br /><br />' . sprintf($lang['Click_return_forum'], '<a href="' . VIEWFORUM_MG . '?' . POST_FORUM_URL . '=' . $old_forum_id . '&amp;sid=' . $userdata['session_id'] . '">', '</a>');

				$template->assign_vars(array(
					'META' => '<meta http-equiv="refresh" content="3;url=' . $redirect_page . '">'
					)
				);

				message_die(GENERAL_MESSAGE, $message);
			}
		}
		include($phpbb_root_path . 'includes/page_tail.' . $phpEx);
		break;
	case 'quick_title_edit':
		if (empty($_POST['topic_id_list']) && empty($topic_id))
		{
			message_die(GENERAL_MESSAGE, $lang['None_selected']);
		}

		$addon = str_replace('%mod%', addslashes($userdata['username']), $qt_row['title_info'] . ' ');
		$dateqt = ($qt_row['date_format'] == '') ? create_date($board_config['default_dateformat'], time(), $board_config['board_timezone']) : create_date($qt_row['date_format'], time(), $board_config['board_timezone']);
		$addon = str_replace('%date%', $dateqt, $addon);

		$topics = (isset($_POST['topic_id_list'])) ?  $_POST['topic_id_list'] : array($topic_id);

		$topic_id_sql = '';
		for($i = 0; $i < count($topics); $i++)
		{
			$topic_id_sql .= (($topic_id_sql != "") ? ', ' : '') . $topics[$i];
		}

		$sql = "UPDATE " . TOPICS_TABLE . "
			SET title_compl_infos = '" . addslashes($addon) . "'
			WHERE topic_id IN ($topic_id_sql)
				AND topic_moved_id = 0";
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not update topics table', '', __LINE__, __FILE__, $sql);
		}

		if (!empty($topic_id))
		{
			$redirect_page = VIEWTOPIC_MG . '?' . POST_TOPIC_URL . '=' . $topic_id . '&amp;sid=' . $userdata['session_id'];
			$message = sprintf($lang['Click_return_topic'], '<a href="' . $redirect_page . '">', '</a>');
		}
		else
		{
			$redirect_page = 'modcp.' . $phpEx . '?' . POST_FORUM_URL . '=' . $forum_id . '&amp;sid=' . $userdata['session_id'];
			$message = sprintf($lang['Click_return_modcp'], '<a href="' . $redirect_page . '">', '</a>');
		}

		$message = $message . '<br \><br \>' . sprintf($lang['Click_return_forum'], '<a href="' . VIEWFORUM_MG . '?' . POST_FORUM_URL . '=' . $forum_id . '&amp;sid=' . $userdata['session_id'] . '">', '</a>');

		$template->assign_vars(array('META' => '<meta http-equiv="refresh" content="3;url=' . $redirect_page . '">'));

		$db->clear_cache('posts_');

		message_die(GENERAL_MESSAGE, $lang['Topics_Title_Edited'] . '<br /><br />' . $message);
		break;

	case 'news_category_edit':
		if(!$is_auth['auth_news'])
		{
			message_die(MESSAGE, $lang['Not_Authorised']);
		}

		if (empty($_POST['topic_id_list']) && empty($topic_id))
		{
			message_die(GENERAL_MESSAGE, $lang['None_selected']);
		}

		$topics = (isset($_POST['topic_id_list'])) ?  $_POST['topic_id_list'] : array($topic_id);

		$topic_id_sql = '';
		for($i = 0; $i < count($topics); $i++)
		{
			$topic_id_sql .= (($topic_id_sql != "") ? ', ' : '') . $topics[$i];
		}

		$sql = "UPDATE " . TOPICS_TABLE . "
			SET news_id = '" . $news_category . "'
			WHERE topic_id IN ($topic_id_sql)
				AND topic_moved_id = 0";
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not update topics table', '', __LINE__, __FILE__, $sql);
		}

		if (!empty($topic_id))
		{
			$redirect_page = VIEWTOPIC_MG . '?' . POST_TOPIC_URL . '=' . $topic_id . '&amp;sid=' . $userdata['session_id'];
			$message = sprintf($lang['Click_return_topic'], '<a href="' . $redirect_page . '">', '</a>');
		}
		else
		{
			$redirect_page = 'modcp.' . $phpEx . '?' . POST_FORUM_URL . '=' . $forum_id . '&amp;sid=' . $userdata['session_id'];
			$message = sprintf($lang['Click_return_modcp'], '<a href="' . $redirect_page . '">', '</a>');
		}

		$message = $message . '<br \><br \>' . sprintf($lang['Click_return_forum'], '<a href="' . VIEWFORUM_MG . '?' . POST_FORUM_URL . '=' . $forum_id . '&amp;sid=' . $userdata['session_id'] . '">', '</a>');

		$template->assign_vars(array('META' => '<meta http-equiv="refresh" content="3;url=' . $redirect_page . '">'));

		$db->clear_cache('posts_');

		message_die(GENERAL_MESSAGE, $lang['Category_Updated'] . '<br /><br />' . $message);
		break;

	default:
		$page_title = $lang['Mod_CP'];
		include($phpbb_root_path . 'includes/page_header.' . $phpEx);

		$u_topic_type = array('super_announce', 'announce', 'sticky', 'poll', 'locked');
		$l_topic_type = array($lang['Display_global'], $lang['Display_announce'], $lang['Display_sticky'], $lang['Display_poll'], $lang['Display_locked']);
		for($tt = 0; $tt < count($u_topic_type); $tt++)
		{
			$topic_types .= ($type == $u_topic_type[$tt]) ? $l_topic_type[$tt] .'&nbsp;|&nbsp;' : '<a href="modcp.' . $phpEx . '?'. POST_FORUM_URL .'='. $forum_id .'&amp;type='. $u_topic_type[$tt] .'&amp;sid='. $userdata['session_id'] .'" class="genmed">'. $l_topic_type[$tt] .'</a>&nbsp;|&nbsp;';
		}
		$topic_types .= (empty($type)) ? $lang['Display_all'] : '<a href="modcp.' . $phpEx . '?'. POST_FORUM_URL .'='. $forum_id .'&amp;sid='. $userdata['session_id'] .'" class="genmed">'. $lang['Display_all'] .'</a>';

		if($forum_topics == '0')
		{
			$template->assign_block_vars("switch_no_topics", array());
		}

		// Quick Title
		$sql = "SELECT * FROM " . TITLE_INFOS_TABLE . " ORDER BY title_info ASC";
		if (!($result = $db->sql_query($sql, false, 'topics_prefixes_')))
		{
			message_die(GENERAL_ERROR, 'Unable to query Quick Title Addon informations', '', __LINE__, __FILE__, $sql);
		}
		$select_title = '<select name="qtnum"><option value="-1">---</option>';
		while ($row = $db->sql_fetchrow($result))
		{
			$addon = str_replace('%mod%', addslashes($userdata['username']), $row['title_info']);
			$dateqt = ($row['date_format'] == '') ? create_date($board_config['default_dateformat'], time(), $board_config['board_timezone']) : create_date($row['date_format'], time(), $board_config['board_timezone']);
			$addon = str_replace('%date%', $dateqt, $addon);
			$select_title .= '<option value="' . $row['id'] . '">' . htmlspecialchars($addon) . '</option>';
		}
		$select_title .= '</select>';
		$db->sql_freeresult($result);

		// News
		$sql = 'SELECT * FROM ' . NEWS_TABLE . ' ORDER BY news_category';
		if (!($result = $db->sql_query($sql, false, 'news_cats_')))
		{
			message_die(GENERAL_ERROR, 'Could not obtain news data', '', __LINE__, __FILE__, $sql);
		}
		$select_news_cats = '<select name="news_category"><option value="0">' . $lang['Regular_Post'] . '</option>';
		while ($row = $db->sql_fetchrow($result))
		{
			$select_news_cats .= '<option value="' . $row['news_id'] .'">' . $row['news_category'] . '</option>';
		}
		$select_news_cats .= '</select>';
		$db->sql_freeresult($result);

		$template->assign_vars(array(
			'TOPIC_TYPES' => $topic_types,
			'TOPIC_COUNT' => ($forum_topics == '1') ? sprintf($lang['Mod_CP_topic_count'], $forum_topics) : sprintf($lang['Mod_CP_topics_count'], $forum_topics),
			'FORUM_NAME' => find_names($forum_id),
			'SELECT_TITLE' => $select_title,
			'SELECT_NEWS_CATS' => $select_news_cats,

			'L_EDIT_TITLE' => $lang['Edit_title'],
			'L_NEWS_CATEGORY' => $lang['Select_News_Category'],
			'L_NO_TOPICS' => $lang['Mod_CP_no_topics'],
			'L_MOD_CP' => $lang['Mod_CP'],
			'L_ENHANCED' => $lang['Mod_CP_enhanced'],
			'L_DELETE' => $lang['Delete'],
			'L_POLL_DELETE' => $lang['Delete_poll'],
			'L_MOVE' => $lang['Move'],
			'L_LOCK' => $lang['Lock'],
			'L_UNLOCK' => $lang['Unlock'],
			'L_MERGE' => $lang['Merge'],
			'L_STICKY' => $lang['Mod_CP_sticky'],
			'L_ANNOUNCE' => $lang['Mod_CP_announce'],
			'L_GLOBAL_ANNOUNCE' => $lang['Mod_CP_global'],
			'L_NORMALIZE' => $lang['Mod_CP_normal'],
			'L_TOPICS' => $lang['Topics'],
			'L_REPLIES' => $lang['Replies'],
			'L_VIEWS' => $lang['Views'],
			'L_FIRSTPOST' => $lang['Mod_CP_first_post'],
			'L_LASTPOST' => $lang['Last_Post'],
			'L_RECYCLE' => $lang['Bin_recycle'],
			'L_MARK_ALL' => $lang['Mark_all'],
			'L_UNMARK_ALL' => $lang['Unmark_all'],
			'U_VIEW_FORUM' => append_sid(VIEWFORUM_MG . '?' . POST_FORUM_URL . '=' . $forum_id),
			'S_HIDDEN_FIELDS' => '<input type="hidden" name="sid" value="' . $userdata['session_id'] . '" /><input type="hidden" name="' . POST_FORUM_URL . '" value="' . $forum_id . '" />',
			'S_MODCP_ACTION' => append_sid('modcp.' . $phpEx),
			)
		);

		if($is_auth['auth_delete'])
		{
			$template->assign_block_vars('switch_auth_delete', array());
		}
		if($is_auth['auth_pollcreate'] && (($type == 'poll') || ($type == 'sticky') || ($type == 'announce') || ($type == 'super_announce')))
		{
			$template->assign_block_vars('switch_auth_poll_delete', array());
		}
		if($is_auth['auth_news'] && ($type != 'shadow'))
		{
			$template->assign_block_vars('switch_auth_news', array());
		}
		if($is_auth['auth_sticky'] && ($type != 'sticky') && ($type != 'shadow'))
		{
			$template->assign_block_vars('switch_auth_sticky', array());
		}
		if($is_auth['auth_announce'] && ($type != 'announce') && ($type != 'shadow'))
		{
			$template->assign_block_vars('switch_auth_announce', array());
		}
		if($is_auth['auth_globalannounce'] && ($type != 'super_announce') && ($type != 'shadow'))
		{
			$template->assign_block_vars('switch_auth_global_announce', array());
		}
		if(($is_auth['auth_sticky'] && ($type == 'sticky')) || ($is_auth['auth_announce'] && ($type == 'announce')) || ($is_auth['auth_globalannounce'] && ($type == 'super_announce')) || ($is_auth['auth_sticky'] && $is_auth['auth_announce'] && $is_auth['auth_globalannounce']))
		{
			$template->assign_block_vars('switch_auth_normalize', array());
		}
		if($type != 'shadow')
		{
			$template->assign_block_vars('switch_auth_move', array());
		}
		if(($type != 'locked') && ($type != 'shadow'))
		{
			$template->assign_block_vars('switch_auth_lock', array());
		}
		if(($type != 'unlocked' && (($type == 'locked') || ($type == 'poll') || ($type == 'sticky') || ($type == 'announce'))) || ($userdata['user_level'] == ADMIN))
		{
			$template->assign_block_vars('switch_auth_unlock', array());
		}

		$template->set_filenames(array('body' => 'modcp_body.tpl'));
		make_jumpbox('modcp.' . $phpEx);

		$orig_word = array();
		$replacement_word = array();
		obtain_word_list($orig_word, $replacement_word);

		// Define censored word matches
		$orig_word = array();
		$replacement_word = array();
		obtain_word_list($orig_word, $replacement_word);

		$sql = "SELECT t.*, u.username, u.user_id, p.post_time, p.post_id, p.post_username, u2.username AS topic_starter, u2.user_id AS topic_starter_id, p2.post_id, p2.post_username AS topic_starter_guest
			FROM " . TOPICS_TABLE . " t, " . USERS_TABLE . " u, " . POSTS_TABLE . " p, " . USERS_TABLE . " u2, " . POSTS_TABLE . " p2
			WHERE t.forum_id = $forum_id AND p.poster_id = u.user_id AND t.topic_poster = u2.user_id AND p.post_id = t.topic_last_post_id
				AND p2.post_id = t.topic_first_post_id $where_type
			ORDER BY t.topic_type DESC, p.post_time DESC LIMIT $start, " . $board_config['topics_per_page'];
		if(!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'could not obtain topic information.', '', __LINE__, __FILE__, $sql);
		}

		$total_topics = 0;
		while($row = $db->sql_fetchrow($result))
		{
			$topic_rowset[] = $row;
			$total_topics++;
		}
		$db->sql_freeresult($result);

		// MG User Replied - BEGIN
		// check if user replied to the topics
		define('USER_REPLIED_ICON', true);
		$user_topics = user_replied_array($topic_rowset);
		$unread = false;
		// MG User Replied - END

		for($i = 0; $i < $total_topics; $i++)
		{
			$forum_id = $topic_rowset[$i]['forum_id'];
			$forum_id_append = (!empty($forum_id) ? (POST_FORUM_URL . '=' . $forum_id) : '');
			$topic_id = $topic_rowset[$i]['topic_id'];
			$topic_id_append = (!empty($topic_id) ? (POST_TOPIC_URL . '=' . $topic_id) : '');
			$user_replied = (!empty($user_topics) && isset($user_topics[$topic_id]));

			$topic_title = (count($orig_word)) ? preg_replace($orig_word, $replacement_word, $topic_rowset[$i]['topic_title']) : $topic_rowset[$i]['topic_title'];
			$topic_title_prefix = (empty($topic_rowset[$i]['title_compl_infos'])) ? '' : $topic_rowset[$i]['title_compl_infos'] . ' ';
			$topic_title = $topic_title_prefix . $topic_title;
			if (($board_config['smilies_topic_title'] == true) && !$lofi)
			{
				//Start BBCode Parsing for title
				$bbcode->allow_html = false;
				$bbcode->allow_bbcode = false;
				$bbcode->allow_smilies = ($board_config['allow_smilies'] && $topic_rowset[$i]['enable_smilies'] ? true : false);
				$topic_title = ($bbcode ? $topic_title = $bbcode->parse($topic_title, $bbcode_uid, true) : $topic_title);
				//End BBCode Parsing for title
			}

			//$news_label = ($topic_rowset[$i]['news_id'] > 0) ? $lang['News_Cmx'] . '' : '';
			$news_label = '';

			$replies = $topic_rowset[$i]['topic_replies'];
			$topic_type = $topic_rowset[$i]['topic_type'];

			$topic_link = build_topic_icon_link($forum_id, $topic_rowset[$i]['topic_id'], $topic_rowset[$i]['topic_type'], $topic_rowset[$i]['topic_replies'], $topic_rowset[$i]['news_id'], $topic_rowset[$i]['topic_vote'], $topic_rowset[$i]['topic_status'], $topic_rowset[$i]['topic_moved_id'], $topic_rowset[$i]['post_time'], $user_replied, $replies, $unread);

			$topic_id = $topic_link['topic_id'];
			$topic_id_append = $topic_link['topic_id_append'];

			if(($replies + 1) > $board_config['posts_per_page'])
			{
				$total_pages = ceil(($replies + 1) / $board_config['posts_per_page']);
				$goto_page = ' [ <img src="' . $images['icon_gotopost'] . '" alt="' . $lang['Goto_page'] . '" title="' . $lang['Goto_page'] . '" />' . $lang['Goto_page'] . ': ';

				$times = 1;
				for($j = 0; $j < $replies + 1; $j += $board_config['posts_per_page'])
				{
					$goto_page .= '<a href="' . append_sid(VIEWTOPIC_MG . '?' . $forum_id_append . '&amp;' . $topic_id_append . '&amp;start=' . $j) . '">' . $times . '</a>';
					if(($times == 1) && ($total_pages > 4))
					{
						$goto_page .= ' ... ';
						$times = $total_pages - 3;
						$j += ($total_pages - 4) * $board_config['posts_per_page'];
					}
					elseif ($times < $total_pages)
					{
						$goto_page .= ', ';
					}
					$times++;
				}
				$goto_page .= ' ] ';
			}
			else
			{
				$goto_page = '';
			}

			$first_post_time = create_date2($board_config['default_dateformat'], $topic_rowset[$i]['topic_time'], $board_config['board_timezone']);
			//$first_post_author = ($topic_rowset[$i]['topic_starter_id'] == ANONYMOUS) ? (($topic_rowset[$i]['topic_starter_guest'] != '') ? $topic_rowset[$i]['topic_starter_guest'] . ' ' : $lang['Guest'] . ' ') : '<a href="' . append_sid(PROFILE_MG . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $topic_rowset[$i]['topic_starter_id']) . '">' . $topic_rowset[$i]['topic_starter'] . '</a> ';
			$first_post_author =  colorize_username($topic_rowset[$i]['topic_starter_id']);

			$first_post_url = ($type == 'shadow') ? '' : '<a href="' . append_sid(VIEWTOPIC_MG . '?' . POST_TOPIC_URL . '=' . $topic_id) . '"><img src="' . $images['icon_latest_reply'] . '" alt="' . $lang['View_latest_post'] . '" title="' . $lang['View_latest_post'] . '" /></a>';

			$last_post_time = create_date2($board_config['default_dateformat'], $topic_rowset[$i]['post_time'], $board_config['board_timezone']);
			//$last_post_author = ($topic_rowset[$i]['user_id'] == ANONYMOUS) ? (($topic_rowset[$i]['post_username'] != '') ? $topic_rowset[$i]['post_username'] . ' ' : $lang['Guest'] . ' ') : '<a href="' . append_sid(PROFILE_MG . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $topic_rowset[$i]['user_id']) . '">' . $topic_rowset[$i]['username'] . '</a> ';
			$last_post_author =  colorize_username($topic_rowset[$i]['user_id']);
			$last_post_url = '<a href="' . append_sid(VIEWTOPIC_MG . '?' . POST_POST_URL . '=' . $topic_rowset[$i]['topic_last_post_id']) . '#p' . $topic_rowset[$i]['topic_last_post_id'] . '"><img src="' . $images['icon_latest_reply'] . '" alt="' . $lang['View_latest_post'] . '" title="' . $lang['View_latest_post'] . '" /></a>';

			$u_view_topic = 'modcp.' . $phpEx . '?mode=split&amp;' . POST_TOPIC_URL . '=' . $topic_id . '&amp;sid=' . $userdata['session_id'];
			$topic_replies = $topic_rowset[$i]['topic_replies'];

			$last_post_time = create_date2($board_config['default_dateformat'], $topic_rowset[$i]['post_time'], $board_config['board_timezone']);

			$template->assign_block_vars('topicrow', array(
				'U_VIEW_TOPIC' => $u_view_topic,
				'TOPIC_ID' => $topic_id,
				'TOPIC_FOLDER_IMG' => $topic_link['image'],
				'L_TOPIC_FOLDER_ALT' => $topic_link['image_alt'],
				'TOPIC_AUTHOR' => $topic_author,
				'TOPIC_TITLE' => $topic_title,
				'TOPIC_TYPE' => $topic_link['type'],
				'TOPIC_TYPE_ICON' => $topic_link['icon'],
				'TOPIC_CLASS' => (!empty($topic_link['class_new']) ? ('topiclink' . $topic_link['class_new']) : $topic_link['class']),
				'CLASS_NEW' => $topic_link['class_new'],
				'NEWEST_POST_IMG' => $topic_link['newest_post_img'],
				'GOTO_PAGE' => (($goto_page == '') ? '' : '<span class="gotopage">' . $goto_page . '</span>'),
				'REPLIES' => $replies,
				'VIEWS' => $topic_rowset[$i]['topic_views'],
				'FIRST_POST_URL' => $first_post_url,
				'FIRST_POST_TIME' => $first_post_time,
				'FIRST_POST_AUTHOR' => $first_post_author,
				'LAST_POST_TIME' => $last_post_time,
				'LAST_POST_AUTHOR' => $last_post_author,
				'LAST_POST_URL' => $last_post_url,
				'L_TOPIC_FOLDER_ALT' => $topic_link['image_alt'],
				'TOPIC_ATTACHMENT_IMG' => topic_attachment_image($topic_rowset[$i]['topic_attachment']),
				)
			);
		}

		$template->assign_vars(array(
			'PAGINATION' => generate_pagination('modcp.' . $phpEx . '?' . POST_FORUM_URL . '=' . $forum_id . '&amp;sid=' . $userdata['session_id'], $forum_topics, $board_config['topics_per_page'], $start),
			'PAGE_NUMBER' => sprintf($lang['Page_of'], (floor($start / $board_config['topics_per_page']) + 1), ceil($forum_topics / $board_config['topics_per_page'])),
			'L_GOTO_PAGE' => $lang['Goto_page']
			)
		);
		$template->pparse('body');
		break;
}

include($phpbb_root_path . 'includes/page_tail.' . $phpEx);

?>