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

// Added to optimize memory for attachments
define('ATTACH_POSTING', true);
define('ATTACH_DISPLAY', true);
define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_selects.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_post.' . PHP_EXT);

if (!class_exists('class_mcp')) include(IP_ROOT_PATH . 'includes/class_mcp.' . PHP_EXT);
if (empty($class_mcp)) $class_mcp = new class_mcp();

@include_once(IP_ROOT_PATH . 'includes/class_topics.' . PHP_EXT);
$class_topics = new class_topics();

// Init common vars: forum_id, topic_id, post_id, etc.
$class_topics->var_init(true);

$confirm = ($_POST['confirm']) ? true : 0;
$confirm_recycle = true;

$selected_id = request_var('selected_id', '');
if (!empty($selected_id))
{
	$type = substr($selected_id, 0, 1);
	$id = intval(substr($selected_id, 1));
	if ($type == POST_FORUM_URL)
	{
		$forum_id = $id;
	}
	elseif (($type == POST_CAT_URL) || ($selected_id == 'Root'))
	{
		$parm = ($id != 0) ? '?' . POST_CAT_URL . '=' . $id : '';
		redirect(append_sid(CMS_PAGE_FORUM . $parm));
		exit;
	}
}

$sid = request_var('sid', '');

$start = request_var('start', 0);
$start = ($start < 0) ? 0 : $start;

$delete = (isset($_POST['delete'])) ? true : false;
$poll_delete = (isset($_POST['poll_delete'])) ? true : false;
$move = (isset($_POST['move'])) ? true : false;
$move_all = (isset($_POST['move_all'])) ? true : false;
$lock = (isset($_POST['lock'])) ? true : false;
$unlock = (isset($_POST['unlock'])) ? true : false;
$label_edit = (isset($_POST['label_edit'])) ? true : false;
$label_id = request_var('label_id', 0);
$merge = (isset($_POST['merge'])) ? true : false;
$recycle = (isset($_POST['recycle'])) ? true : false;
$sticky = (isset($_POST['sticky'])) ? true : false;
$announce = (isset($_POST['announce'])) ? true : false;
$global_announce = (isset($_POST['super_announce'])) ? true : false;
$normalize = (isset($_POST['normalize'])) ? true : false;
$news_category_edit = (isset($_POST['news_category_edit'])) ? true : false;
$news_category = request_var('news_category', 0);
$news_category = ($news_category < 0) ? 0 : $news_category;

$mode = request_var('mode', '');
if(empty($mode))
{
	if($delete)
	{
		$mode = 'delete';
	}
	elseif ($poll_delete)
	{
		$mode = 'poll_delete';
	}
	elseif($move)
	{
		$mode = 'move';
	}
	elseif($move_all)
	{
		$mode = 'move_all';
	}
	elseif ($lock)
	{
		$mode = 'lock';
	}
	elseif ($unlock)
	{
		$mode = 'unlock';
	}
	elseif ($label_edit)
	{
		$mode = 'label_edit';
	}
	elseif ($recycle)
	{
		$mode = 'recycle';
	}
	elseif ($merge)
	{
		$mode = 'merge';
	}
	elseif ($sticky)
	{
		$mode = 'sticky';
	}
	elseif ($announce)
	{
		$mode = 'announce';
	}
	elseif ($global_announce)
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

$type = request_var('type', '');
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
		$where_type = " AND t.poll_start <> '0' AND t.topic_type = " . POST_NORMAL; break;
	case 'locked':
		$where_type = " AND t.topic_status = " . TOPIC_LOCKED . " AND t.topic_type = " . POST_NORMAL . " AND t.poll_start = '0'"; break;
	case 'unlocked':
		$where_type = " AND t.topic_status = " . TOPIC_UNLOCKED . " AND t.topic_type = " . POST_NORMAL . " AND t.topic_views > '0' AND t.topic_replies > '0' AND t.poll_start = '0'"; break;
	case 'unread':
		$where_type = " AND t.topic_views = '0' AND t.topic_type = " . POST_NORMAL . " AND t.topic_status = " . TOPIC_UNLOCKED . " AND t.poll_start = '0'"; break;
	case 'unanswered':
		$where_type = " AND t.topic_replies = '0' AND t.topic_type = " . POST_NORMAL . " AND t.topic_status = " . TOPIC_UNLOCKED . " AND t.topic_views > '0' AND t.poll_start = '0'"; break;
	default:
		$where_type = ''; break;
}

if(!empty($topic_id))
{
	$sql = "SELECT f.forum_id, f.forum_name, f.forum_topics, t.topic_poster FROM " . TOPICS_TABLE . " t, " . FORUMS_TABLE . " f
		WHERE t.topic_id = " . $topic_id . " AND f.forum_id = t.forum_id";
	$db->sql_return_on_error(true);
	$result = $db->sql_query($sql);
	$db->sql_return_on_error(false);
	if(!$result)
	{
		if (!defined('STATUS_404')) define('STATUS_404', true);
		message_die(GENERAL_MESSAGE, 'NO_TOPIC');
	}
	$topic_row = $db->sql_fetchrow($result);
	$forum_topics = ($topic_row['forum_topics'] == '0') ? '1' : $topic_row['forum_topics'];
	$forum_id = $topic_row['forum_id'];
	$forum_name = get_object_lang(POST_FORUM_URL . $topic_row['forum_id'], 'name');
}
elseif(!empty($forum_id))
{
	$sql = "SELECT COUNT(t.topic_id) AS total_topics FROM " . TOPICS_TABLE . " t WHERE t.forum_id = " . $forum_id . " $where_type";
	$db->sql_return_on_error(true);
	$result = $db->sql_query($sql);
	$db->sql_return_on_error(false);
	if(!$result)
	{
		if (!defined('STATUS_404')) define('STATUS_404', true);
		message_die(GENERAL_MESSAGE, 'NO_FORUM');
	}
	$topic_row = $db->sql_fetchrow($result);
	$forum_topics = $topic_row['total_topics'];
}
else
{
	if (!defined('STATUS_404')) define('STATUS_404', true);
	message_die(GENERAL_MESSAGE, 'NO_FORUM');
}

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup();
// End session management

if(empty($sid) || ($sid != $user->data['session_id']))
{
	message_die(GENERAL_ERROR, 'INVALID_SESSION');
}

if(isset($_POST['cancel']))
{
	if($topic_id)
	{
		$redirect = CMS_PAGE_VIEWTOPIC . '?' . POST_TOPIC_URL . '=' . $topic_id;
	}
	elseif($forum_id)
	{
		$redirect = CMS_PAGE_VIEWFORUM . '?' . POST_FORUM_URL . '=' . $forum_id;
	}
	else
	{
		$redirect = CMS_PAGE_FORUM;
	}
	redirect(append_sid($redirect, true));
}

if ($mode != 'label_edit')
{
	$is_auth = auth(AUTH_ALL, $forum_id, $user->data);

	if (!$is_auth['auth_mod'])
	{
		message_die(GENERAL_MESSAGE, $lang['Not_Moderator'], $lang['Not_Authorized']);
	}
}
else
{
	if ($label_id > 0)
	{
		$sql_label = "SELECT * FROM " . TOPICS_LABELS_TABLE . " WHERE id = " . $db->sql_escape($label_id);
		$result_label = $db->sql_query($sql_label);
		$label_data = $db->sql_fetchrow($result_label);
		if ((($user->data['user_level'] == ADMIN) && ($label_data['admin_auth'] == 0)) || (($user->data['user_level'] == MOD) && ($label_data['mod_auth'] == 0)) || (($user->data['user_level'] == USER) && ($label_data['poster_auth'] == 0)) || (($user->data['user_level'] == USER) && ($label_data['poster_auth'] == 1) && ($user->data['user_id'] != $topic_row['topic_poster'])))
		{
			message_die(GENERAL_MESSAGE, $lang['Not_Authorized']);
		}
	}
	else
	{
		if (($user->data['user_level'] == USER) && ($user->data['user_id'] != $topic_row['topic_poster']))
		{
			message_die(GENERAL_MESSAGE, $lang['Not_Authorized']);
		}
		$label_data = array('id' => 0);
	}
}
// End Auth Check

$meta_content['description'] = '';
$meta_content['keywords'] = '';

switch($mode)
{
	case 'delete':
		if(!$is_auth['auth_delete'])
		{
			message_die(GENERAL_MESSAGE, sprintf($lang['Sorry_auth_delete'], $is_auth['auth_delete_type']));
		}

		$meta_content['page_title'] = $lang['Mod_CP'] . ' (' . $lang['Delete'] . ')';

		if($confirm)
		{
			$topics = (isset($_POST['topic_id_list'])) ? $_POST['topic_id_list'] : array($topic_id);
			$class_mcp->topic_delete($topics, $forum_id);

			if(!empty($topic_id))
			{
				$redirect_page = CMS_PAGE_VIEWFORUM . '?' . POST_FORUM_URL . '=' . $forum_id . '&amp;sid=' . $user->data['session_id'];
				$l_redirect = sprintf($lang['Click_return_forum'], '<a href="'. $redirect_page .'">', '</a>');
			}
			else
			{
				$redirect_page = 'modcp.' . PHP_EXT . '?' . POST_FORUM_URL . '=' . $forum_id . '&amp;sid=' . $user->data['session_id'];
				$l_redirect = sprintf($lang['Click_return_modcp'], '<a href="'. $redirect_page .'">', '</a>') . '<br /><br />'. sprintf($lang['Click_return_forum'], '<a href="' . CMS_PAGE_VIEWFORUM . '?' . POST_FORUM_URL . '=' . $forum_id . '&amp;sid=' . $user->data['session_id'] .'">', '</a>');
			}

			$redirect_url = $redirect_page;
			meta_refresh(3, $redirect_url);

			message_die(GENERAL_MESSAGE, ((sizeof($topics) == '1') ? $lang['Mod_CP_topic_removed'] : $lang['Topics_Removed']) . '<br /><br />' . $l_redirect);
		}
		else
		{
			if(empty($_POST['topic_id_list']) && empty($topic_id))
			{
				message_die(GENERAL_MESSAGE, $lang['None_selected']);
			}

			$hidden_fields = '<input type="hidden" name="sid" value="' . $user->data['session_id'] . '" /><input type="hidden" name="mode" value="' . $mode . '" /><input type="hidden" name="type" value="' . $type . '" /><input type="hidden" name="'. POST_FORUM_URL .'" value="' . $forum_id . '" />';
			if(isset($_POST['topic_id_list']))
			{
				$topics = $_POST['topic_id_list'];
				for($i = 0; $i < sizeof($topics); $i++)
				{
					$hidden_fields .= '<input type="hidden" name="topic_id_list[]" value="'. intval($topics[$i]) .'" />';
				}
			}
			else
			{
				$hidden_fields .= '<input type="hidden" name="'. POST_TOPIC_URL .'" value="'. $topic_id .'" />';
			}

			$template->assign_vars(array(
				'MESSAGE_TITLE' => $lang['Confirm'],
				'MESSAGE_TEXT' => $lang['Confirm_delete_topic'],
				'L_YES' => $lang['Yes'],
				'L_NO' => $lang['No'],
				'S_CONFIRM_ACTION' => append_sid('modcp.' . PHP_EXT),
				'S_HIDDEN_FIELDS' => $hidden_fields,
				)
			);
			full_page_generation('confirm_body.tpl', $lang['Confirm'], '', '');
		}
		break;

	case 'poll_delete':
		if(!$is_auth['auth_pollcreate'])
		{
			message_die(MESSAGE, sprintf($lang['Sorry_auth_delete'], $is_auth['auth_delete_type']));
		}

		$meta_content['page_title'] = $lang['Mod_CP'] . ' (' . $lang['Delete_poll'] . ')';

		if($confirm)
		{
			$topics = (isset($_POST['topic_id_list'])) ? $_POST['topic_id_list'] : array($topic_id);
			$class_mcp->topic_poll_delete($topics);

			$redirect_page = 'modcp.' . PHP_EXT . '?' . POST_FORUM_URL . '=' . $forum_id . '&amp;sid=' . $user->data['session_id'];
			$l_redirect = sprintf($lang['Click_return_modcp'], '<a href="'. $redirect_page .'">', '</a>') . '<br /><br />'. sprintf($lang['Click_return_forum'], '<a href="'. CMS_PAGE_VIEWFORUM . '?' . POST_FORUM_URL . '=' . $forum_id . '&amp;sid=' . $user->data['session_id'] .'">', '</a>');

			$redirect_url = $redirect_page;
			meta_refresh(3, $redirect_url);

			message_die(GENERAL_MESSAGE, ((sizeof($topics) == '1') ? $lang['Mod_CP_poll_removed'] : $lang['Mod_CP_polls_removed']) . '<br /><br />'. $l_redirect);
		}
		else
		{
			if(empty($_POST['topic_id_list']) && empty($topic_id))
			{
				message_die(GENERAL_MESSAGE, $lang['None_selected']);
			}

			$hidden_fields = '<input type="hidden" name="sid" value="' . $user->data['session_id'] . '" /><input type="hidden" name="mode" value="' . $mode . '" /><input type="hidden" name="type" value="' . $type . '" /><input type="hidden" name="' . POST_FORUM_URL . '" value="' . $forum_id . '" />';
			if(isset($_POST['topic_id_list']))
			{
				$topics = $_POST['topic_id_list'];
				for($i = 0; $i < sizeof($topics); $i++)
				{
					$hidden_fields .= '<input type="hidden" name="topic_id_list[]" value="' . intval($topics[$i]) . '" />';
				}
			}
			else
			{
				$hidden_fields .= '<input type="hidden" name="' . POST_TOPIC_URL . '" value="' . $topic_id . '" />';
			}

			$template->assign_vars(array(
				'MESSAGE_TITLE' => $lang['Confirm'],
				'MESSAGE_TEXT' => (sizeof($topics) == '1') ? $lang['Confirm_delete_poll'] : $lang['Mod_CP_confirm_delete_polls'],
				'L_YES' => $lang['Yes'],
				'L_NO' => $lang['No'],
				'S_CONFIRM_ACTION' => append_sid('modcp.' . PHP_EXT),
				'S_HIDDEN_FIELDS' => $hidden_fields,
				)
			);
			full_page_generation('confirm_body.tpl', $lang['Confirm'], '', '');
		}
		break;

	case 'move':
	case 'move_all':
		$meta_content['page_title'] = $lang['Mod_CP'] . ' (' . $lang['Move'] . ')';

		if($confirm)
		{
			$topics = (isset($_POST['topic_id_list'])) ?  $_POST['topic_id_list'] : array($topic_id);
			$new_forum_id = $_POST['new_forum'];

			if ($mode == 'move_all')
			{
				$moved_topics_prefix = request_var('moved_topics_prefix', '', true);
				if ($class_mcp->topic_move_ren_all($forum_id, $new_forum_id, $moved_topics_prefix))
				{
					$message = sprintf($lang['Mod_CP_topics_moved'], $class_mcp->find_names($forum_id), $class_mcp->find_names($new_forum_id)) . '<br /><br />';
				}
				else
				{
					$message = $lang['No_Topics_Moved'] . '<br /><br />';
				}

				$redirect_page = 'modcp.' . PHP_EXT . '?' . POST_FORUM_URL . '=' . $forum_id . '&amp;sid=' . $user->data['session_id'];
				$message .= sprintf($lang['Click_return_modcp'], '<a href="' . $redirect_page . '">', '</a>');
			}
			else
			{
				if ($class_mcp->topic_move($topics, $forum_id, $new_forum_id, isset($_POST['move_leave_shadow'])))
				{
					$message = ((sizeof($topics) == '1') ? sprintf($lang['Mod_CP_topic_moved'], $class_mcp->find_names($forum_id), $class_mcp->find_names($new_forum_id)) : sprintf($lang['Mod_CP_topics_moved'], $class_mcp->find_names($forum_id), $class_mcp->find_names($new_forum_id))) . '<br /><br />';
				}
				else
				{
					$message = $lang['No_Topics_Moved'] . '<br /><br />';
				}

				if(!empty($topic_id))
				{
					$redirect_page = CMS_PAGE_VIEWTOPIC . '?' . POST_TOPIC_URL . '=' . $topic_id . '&amp;sid=' . $user->data['session_id'];
					$message .= sprintf($lang['Click_return_topic'], '<a href="' . $redirect_page . '">', '</a>');
				}
				else
				{
					$redirect_page = 'modcp.' . PHP_EXT . '?' . POST_FORUM_URL . '=' . $forum_id . '&amp;sid=' . $user->data['session_id'];
					$message .= sprintf($lang['Click_return_modcp'], '<a href="' . $redirect_page . '">', '</a>');
				}
			}

			$redirect_url = $redirect_page;
			meta_refresh(3, $redirect_url);

			message_die(GENERAL_MESSAGE, $message . '<br /><br />'. sprintf($lang['Click_return_forum'], '<a href="'. CMS_PAGE_VIEWFORUM . '?' . POST_FORUM_URL . '=' . $forum_id . '&amp;sid=' . $user->data['session_id'] .'">', '</a>'));
		}
		else
		{
			page_header($meta_content['page_title'], true);

			$hidden_fields = '<input type="hidden" name="sid" value="' . $user->data['session_id'] . '" /><input type="hidden" name="mode" value="' . $mode . '" /><input type="hidden" name="type" value="' . $type . '" /><input type="hidden" name="' . POST_FORUM_URL . '" value="' . $forum_id . '" />';

			$move_all_switch = false;
			if ($mode == 'move_all')
			{
				$move_all_switch = true;
			}
			else
			{
				if(empty($_POST['topic_id_list']) && empty($topic_id))
				{
					message_die(GENERAL_MESSAGE, $lang['None_selected']);
				}

				if(isset($_POST['topic_id_list']))
				{
					$topics = $_POST['topic_id_list'];
					for($i = 0; $i < sizeof($topics); $i++)
					{
						$hidden_fields .= '<input type="hidden" name="topic_id_list[]" value="' . intval($topics[$i]) . '" />';
					}
				}
				else
				{
					$hidden_fields .= '<input type="hidden" name="' . POST_TOPIC_URL . '" value="' . $topic_id . '" />';
				}
			}

			$template->set_filenames(array('movetopic' => 'modcp_move.tpl'));
			$template->assign_vars(array(
				'MESSAGE_TITLE' => $lang['Confirm'],
				'MESSAGE_TEXT' => $lang['Confirm_move_topic'],
				'L_MOVE_TO_FORUM' => $lang['Move_to_forum'],
				'L_LEAVESHADOW' => $lang['Leave_shadow_topic'],
				'L_YES' => $lang['Yes'],
				'L_NO' => $lang['No'],
				'S_MOVE_ALL_SWITCH' => $move_all_switch,
				'S_FORUM_SELECT' => ip_make_forum_select('new_forum', $forum_id),
				'S_MODCP_ACTION' => append_sid('modcp.' . PHP_EXT),
				'S_HIDDEN_FIELDS' => $hidden_fields,
				)
			);

			$template->pparse('movetopic');

			page_footer(true, '', true);
		}
		break;

	case 'lock':
	case 'unlock':
		if(empty($_POST['topic_id_list']) && empty($topic_id))
		{
			message_die(GENERAL_MESSAGE, $lang['None_selected']);
		}

		$topics = (isset($_POST['topic_id_list'])) ? $_POST['topic_id_list'] : array($topic_id);
		$class_mcp->topic_lock_unlock($topics, $mode, $forum_id);

		if(!empty($topic_id))
		{
			$redirect_page = CMS_PAGE_VIEWTOPIC . '?' . POST_TOPIC_URL . '=' . $topic_id . '&amp;sid=' . $user->data['session_id'];
			$message = sprintf($lang['Click_return_topic'], '<a href="'. $redirect_page .'">', '</a>');
		}
		else
		{
			$redirect_page = 'modcp.' . PHP_EXT . '?' . POST_FORUM_URL . '=' . $forum_id . '&amp;sid=' . $user->data['session_id'];
			$message = sprintf($lang['Click_return_modcp'], '<a href="'. $redirect_page .'">', '</a>');
		}

		$redirect_url = $redirect_page;
		meta_refresh(3, $redirect_url);

		if($mode == 'lock')
		{
			message_die(GENERAL_MESSAGE, ((sizeof($topics) == '1') ? $lang['Mod_CP_topic_locked'] : $lang['Topics_Locked']) . '<br /><br />'. $message . '<br /><br />' . sprintf($lang['Click_return_forum'], '<a href="' . CMS_PAGE_VIEWFORUM . '?' . POST_FORUM_URL . '=' . $forum_id . '&amp;sid=' . $user->data['session_id'] . '">', '</a>'));
		}
		elseif($mode == 'unlock')
		{
			message_die(GENERAL_MESSAGE, ((sizeof($topics) == '1') ? $lang['Mod_CP_topic_unlocked'] : $lang['Topics_Unlocked']) . '<br /><br />'. $message . '<br /><br />'. sprintf($lang['Click_return_forum'], '<a href="' . CMS_PAGE_VIEWFORUM . '?' . POST_FORUM_URL . '=' . $forum_id . '&amp;sid=' . $user->data['session_id'] . '">', '</a>'));
		}
		break;

	case 'sticky':
	case 'announce':
	case 'super_announce':
	case 'normalize':
		if(empty($_POST['topic_id_list']) && empty($topic_id))
		{
			message_die(GENERAL_MESSAGE, $lang['None_selected']);
		}

		$topics = (isset($_POST['topic_id_list'])) ? $_POST['topic_id_list'] : array($topic_id);
		$class_mcp->topic_switch_status($topics, $mode);

		if(!empty($topic_id))
		{
			$redirect_page = append_sid(CMS_PAGE_VIEWTOPIC . '?' . POST_TOPIC_URL . '=' . $topic_id);
			$message = sprintf($lang['Click_return_topic'], '<a href="'. $redirect_page .'">', '</a>');
		}
		else
		{
			$redirect_page = 'modcp.' . PHP_EXT . '?' . POST_FORUM_URL . '=' . $forum_id . '&sid=' . $user->data['session_id'];
			$message = sprintf($lang['Click_return_modcp'], '<a href="'. $redirect_page .'">', '</a>');
		}

		$redirect_url = $redirect_page;
		meta_refresh(3, $redirect_url);

		$message = $message . '<br /><br />' . sprintf($lang['Click_return_forum'], '<a href="'. append_sid(CMS_PAGE_VIEWFORUM . '?' . POST_FORUM_URL . '=' . $forum_id) .'">', '</a>');

		switch($mode)
		{
			case 'sticky':
				$message = ((sizeof($topics) == '1') ? $lang['Mod_CP_topic_sticked'] : $lang['Mod_CP_topics_sticked']) . '<br /><br />'. $message; break;
			case 'announce':
				$message = ((sizeof($topics) == '1') ? $lang['Mod_CP_topic_announced'] : $lang['Mod_CP_topics_announced']) . '<br /><br />'. $message; break;
			case 'super_announce':
				$message = ((sizeof($topics) == '1') ? $lang['Mod_CP_topic_globalized'] : $lang['Mod_CP_topics_globalized']) . '<br /><br />'. $message; break;
			case 'normalize':
				$message = ((sizeof($topics) == '1') ? $lang['Mod_CP_topic_normalized'] : $lang['Mod_CP_topics_normalized']) . '<br /><br />'. $message; break;
		}

		message_die(GENERAL_MESSAGE, $message);
		break;

	case 'merge':
		$meta_content['page_title'] = $lang['Mod_CP'] . ' (' . $lang['Merge_topic'] . ')';

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
			$topics = (isset($_POST['topic_id_list']) ? $_POST['topic_id_list'] : array($topic_id));

			if(sizeof($topics) > 0)
			{
				$class_mcp->topic_merge($topics, $new_topic_id, $forum_id);
				$message = $lang['Topics_Merged'] . '<br /><br />';
			}
			else
			{
				$message = $lang['No_Topics_Merged'] . '<br /><br />';
			}

			if (!empty($topic_id))
			{
				$redirect_page = CMS_PAGE_VIEWTOPIC . '?' . POST_TOPIC_URL . '=' . $new_topic_id . '&amp;sid=' . $user->data['session_id'];
				$message .= sprintf($lang['Click_return_topic'], '<a href="' . $redirect_page . '">', '</a>');
			}
			else
			{
				$redirect_page = 'modcp.' . PHP_EXT . '?' . POST_FORUM_URL . '=' . $forum_id . '&amp;sid=' . $user->data['session_id'];
				$message .= sprintf($lang['Click_return_modcp'], '<a href="' . $redirect_page . '">', '</a>');
			}

			$message = $message . '<br /><br />' . sprintf($lang['Click_return_forum'], '<a href="' . append_sid(CMS_PAGE_VIEWFORUM . '?' . POST_FORUM_URL . '=' . $forum_id) . '">', '</a>');

			$redirect_url = $redirect_page;
			meta_refresh(3, $redirect_url);

			message_die(GENERAL_MESSAGE, $message);
		}
		else
		{
			page_header($meta_content['page_title'], true);
			if (empty($_POST['topic_id_list']) && empty($topic_id))
			{
				message_die(GENERAL_MESSAGE, $lang['None_selected']);
			}

			$hidden_fields = '<input type="hidden" name="mode" value="' . $mode . '" /><input type="hidden" name="' . POST_FORUM_URL . '" value="' . $forum_id . '" />';
			$hidden_fields .= '<input type="hidden" name="sid" value="' . $user->data['session_id'] . '" />';

			if (isset($_POST['topic_id_list']))
			{
				$topics = $_POST['topic_id_list'];

				for($i = 0; $i < sizeof($topics); $i++)
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

				'S_TOPIC_SELECT' => ip_make_topic_select('new_topic', $forum_id),
				'S_MODCP_ACTION' => append_sid('modcp.' . PHP_EXT),
				'S_HIDDEN_FIELDS' => $hidden_fields
				)
			);

			$template->pparse('mergetopic');

			page_footer(true, '', true);
		}
		break;

	case 'split':
		$meta_content['page_title'] = $lang['Mod_CP'] . ' (' . $lang['Split'] . ')';

		if((isset($_POST['split_type_all']) || isset($_POST['split_type_beyond'])) && isset($_POST['post_id_list']))
		{
			$posts = $_POST['post_id_list'];
			$fid = $_POST['new_forum_id'];
			$topic_id = $_POST[POST_TOPIC_URL];
			$split_beyond = (isset($_POST['split_type_beyond'])) ? true : false;
			$topic_subject = trim(htmlspecialchars($_POST['subject']));
			if(empty($topic_subject))
			{
				message_die(GENERAL_MESSAGE, $lang['Empty_subject']);
			}

			$new_topic_id = $class_mcp->topic_split($posts, $forum_id, $fid, $topic_id, $split_beyond, $topic_subject);
			$redirect_url = CMS_PAGE_VIEWTOPIC . '?' . POST_TOPIC_URL . '=' . $topic_id . '&amp;sid=' . $user->data['session_id'];
			meta_refresh(3, $redirect_url);

			$message = $lang['Topic_split'] . '<br /><br />' . sprintf($lang['Mod_CP_click_return_topic'], '<a href="' . $redirect_url . '">', '</a>', '<a href="' . CMS_PAGE_VIEWTOPIC . '?' . POST_TOPIC_URL . '=' . $new_topic_id . '&amp;sid=' . $user->data['session_id'] . '">', '</a>') . '<br /><br />'. sprintf($lang['Click_return_modcp'], '<a href="modcp.' . PHP_EXT . '?' . POST_FORUM_URL . '=' . $forum_id . '&sid=' . $user->data['session_id'] .'">', '</a>') . '<br /><br />'. sprintf($lang['Click_return_forum'], '<a href="'. CMS_PAGE_VIEWFORUM . '?' . POST_FORUM_URL . '=' . $forum_id . '&amp;sid=' . $user->data['session_id'] .'">', '</a>');

			message_die(GENERAL_MESSAGE, $message);
		}
		else
		{
			page_header($meta_content['page_title'], true);
			$template->set_filenames(array('split_body' => 'modcp_split.tpl'));

			$sql = "SELECT u.user_id, u.username, u.user_active, u.user_color, p.*
				FROM " . POSTS_TABLE . " p, " . USERS_TABLE . " u
				WHERE p.topic_id = $topic_id
					AND p.poster_id = u.user_id
				ORDER BY p.post_time ASC";
			$result = $db->sql_query($sql);

			$s_hidden_fields = '<input type="hidden" name="sid" value="' . $user->data['session_id'] . '" /><input type="hidden" name="' . POST_FORUM_URL . '" value="' . $forum_id . '" /><input type="hidden" name="' . POST_TOPIC_URL . '" value="' . $topic_id . '" /><input type="hidden" name="mode" value="split" />';

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
					'U_VIEW_FORUM' => append_sid(CMS_PAGE_VIEWFORUM . '?' . POST_FORUM_URL . '=' . $forum_id),
					'S_SPLIT_ACTION' => append_sid('modcp.' . PHP_EXT),
					'S_HIDDEN_FIELDS' => $s_hidden_fields,
					'S_FORUM_SELECT' => ip_make_forum_select('new_forum_id', false, $forum_id),
					)
				);

				for($i = 0; $i < $total_posts; $i++)
				{
					$message = $postrow[$i]['post_text'];
					$post_subject = ($postrow[$i]['post_subject'] != '') ? $postrow[$i]['post_subject'] : $topic_title;
					$post_date = create_date_ip($config['default_dateformat'], $postrow[$i]['post_time'], $config['board_timezone']);

					$bbcode->allow_html = ($config['allow_html'] && $postrow[$i]['enable_bbcode'] ? true : false);
					$bbcode->allow_bbcode = ($config['allow_bbcode'] && $postrow[$i]['enable_bbcode'] ? true : false);
					$bbcode->allow_smilies = ($config['allow_smilies'] && $postrow[$i]['enable_smilies'] ? true : false);
					$message = $bbcode->parse($message);

					$checkbox = ($i > 0) ? '<input type="checkbox" name="post_id_list[]" value="' . $postrow[$i]['post_id'] . '" />' : '&nbsp;';

					$template->assign_block_vars('postrow', array(
						'ROW_CLASS' => (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'],
						'POSTER_NAME' => $postrow[$i]['username'],
						'U_PROFILE_COL' => colorize_username($postrow[$i]['user_id'], $postrow[$i]['username'], $postrow[$i]['user_color'], $postrow[$i]['user_active']),
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
		$ip_display_auth = ip_display_auth($user->data, false);
		if (empty($ip_display_auth))
		{
			message_die(GENERAL_MESSAGE, $lang['Not_Authorized']);
		}

		$meta_content['page_title'] = $lang['Mod_CP'] . ' (' . $lang['IP'] . ')';
		page_header($meta_content['page_title'], true);

		$rdns_ip_num = (isset($_GET['rdns'])) ? $_GET['rdns'] : '';

		if(!$post_id)
		{
			message_die(GENERAL_MESSAGE, $lang['No_such_post']);
		}

		$template->set_filenames(array('viewip' => 'modcp_viewip.tpl'));

		$sql = "SELECT poster_ip, poster_id FROM " . POSTS_TABLE . " WHERE post_id = $post_id AND forum_id = $forum_id";
		$result = $db->sql_query($sql);

		if(!($post_row = $db->sql_fetchrow($result)))
		{
			message_die(GENERAL_MESSAGE, $lang['No_such_post']);
		}

		$ip_this_post = $post_row['poster_ip'];
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
			'IP' => htmlspecialchars($ip_this_post),
			'U_LOOKUP_IP' => 'modcp.' . PHP_EXT . '?mode=ip&amp;' . POST_POST_URL . '=' . $post_id . '&amp;' . POST_TOPIC_URL . '=' . $topic_id . '&amp;rdns=' . htmlspecialchars(urlencode($ip_this_post)) . '&amp;sid=' . $user->data['session_id'],
			'U_WHOIS_IP' => htmlspecialchars('http://whois.domaintools.com/' . $ip_this_post),
			)
		);

		$sql = "SELECT poster_ip, COUNT(*) AS postings FROM " . POSTS_TABLE . " WHERE poster_id = $poster_id
			GROUP BY poster_ip ORDER BY postings DESC";
		$result = $db->sql_query($sql);

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

				$ip = $row['poster_ip'];
				$ip = ($rdns_ip_num == $row['poster_ip'] || $rdns_ip_num == 'all') ? htmlspecialchars(gethostbyaddr($ip)) : $ip;

				$template->assign_block_vars('iprow', array(
					'ROW_CLASS' => (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'],
					'IP' => htmlspecialchars($ip),
					'POSTS' => $row['postings'] .' '. (($row['postings'] == '1') ? $lang['Post'] : $lang['Posts']),
					'U_LOOKUP_IP' => 'modcp.' . PHP_EXT . '?mode=ip&amp;' . POST_POST_URL . '=' . $post_id . '&amp;' . POST_TOPIC_URL . '=' . $topic_id . '&amp;rdns=' . htmlspecialchars(urlencode($row['poster_ip'])) . '&amp;sid=' . $user->data['session_id'],
					'U_WHOIS_IP' => htmlspecialchars('http://whois.domaintools.com/' . $ip),
					)
				);
				$i++;
			}
			while($row = $db->sql_fetchrow($result));
		}

		// Get other users who've posted under this IP
		$sql = "SELECT u.user_id, u.username, u.user_active, u.user_color, COUNT(*) as postings
			FROM " . USERS_TABLE . " u, " . POSTS_TABLE . " p
			WHERE p.poster_id = u.user_id AND p.poster_ip = '" . $post_row['poster_ip'] . "'
			GROUP BY u.user_id, u.username ORDER BY postings DESC";
		$result = $db->sql_query($sql);

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
					'U_PROFILE' => ($row['user_id'] == ANONYMOUS) ? 'modcp.' . PHP_EXT . '?mode=ip&amp;' . POST_POST_URL . '=' . $post_id . '&amp;' . POST_TOPIC_URL . '=' . $topic_id . '&amp;sid=' . $user->data['session_id'] : append_sid(CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $row['user_id']),
					'U_SEARCHPOSTS' => append_sid(CMS_PAGE_SEARCH . '?search_author=' . (($id == ANONYMOUS) ? 'Anonymous' : urlencode($username)) . '&amp;showresults=topics'),
					'U_PROFILE_COL' => colorize_username($row['user_id'], $row['username'], $row['user_color'], $row['user_active']),
					//'U_SEARCHPOSTS' => append_sid(CMS_PAGE_SEARCH . '?search_author=' . urlencode((($row['user_id'] == ANONYMOUS) ? $lang['Guest'] : $row['username'])) . '&amp;showresults=topics'),
					)
				);
				$i++;
			}
			while($row = $db->sql_fetchrow($result));
		}
		$template->pparse('viewip');
		break;

	case 'recycle':
		$meta_content['page_title'] = $lang['Mod_CP'];

		if ($confirm_recycle)
		{
			if (($config['bin_forum'] == 0) || (empty($_POST['topic_id_list']) && empty($topic_id)))
			{
				$redirect_page = 'modcp.' . PHP_EXT . '?' . POST_FORUM_URL . '=' . $forum_id . '&amp;sid=' . $user->data['session_id'];
				$message = sprintf($lang['Click_return_modcp'], '<a href="' . $redirect_page . '">', '</a>');
				$message = $message . '<br /><br />' . sprintf($lang['Click_return_forum'], '<a href="' . CMS_PAGE_VIEWFORUM . '?' . POST_FORUM_URL . '=' . $forum_id . '&amp;sid=' . $user->data['session_id'] . '">', '</a>');

				$redirect_url = $redirect_page;
				meta_refresh(3, $redirect_url);

				message_die(GENERAL_MESSAGE, $lang['None_selected'] . '<br /><br />' . $message);
			}
			elseif (isset($_POST['topic_id_list']))
			{
				$topics = (isset($_POST['topic_id_list'])) ? $_POST['topic_id_list'] : array($topic_id);

				if($class_mcp->topic_recycle($topics, $forum_id))
				{
					$message = $lang['Topics_Moved_bin'];
				}
				else
				{
					$message = $lang['No_Topics_Moved'];
				}

				$redirect_page = 'modcp.' . PHP_EXT . '?' . POST_FORUM_URL . '=' . $forum_id . '&amp;sid=' . $user->data['session_id'];
				$message .= '<br /><br />' . sprintf($lang['Click_return_modcp'], '<a href="' . $redirect_page . '">', '</a>');

				$message = $message . '<br /><br />' . sprintf($lang['Click_return_forum'], '<a href="' . CMS_PAGE_VIEWFORUM . '?' . POST_FORUM_URL . '=' . $old_forum_id . '&amp;sid=' . $user->data['session_id'] . '">', '</a>');

				$redirect_url = $redirect_page;
				meta_refresh(3, $redirect_url);

				message_die(GENERAL_MESSAGE, $message);
			}
		}
		$message = $lang['No_Topics_Moved'];
		message_die(GENERAL_MESSAGE, $message);
		break;

	case 'label_edit':
		if (empty($_POST['topic_id_list']) && empty($topic_id))
		{
			message_die(GENERAL_MESSAGE, $lang['None_selected']);
		}

		$topics = (isset($_POST['topic_id_list'])) ?  $_POST['topic_id_list'] : array($topic_id);
		$class_mcp->topic_label_edit($topics, $label_data);

		if (!empty($topic_id))
		{
			$redirect_page = CMS_PAGE_VIEWTOPIC . '?' . POST_TOPIC_URL . '=' . $topic_id . '&amp;sid=' . $user->data['session_id'];
			$message = sprintf($lang['Click_return_topic'], '<a href="' . $redirect_page . '">', '</a>');
		}
		else
		{
			$redirect_page = 'modcp.' . PHP_EXT . '?' . POST_FORUM_URL . '=' . $forum_id . '&amp;sid=' . $user->data['session_id'];
			$message = sprintf($lang['Click_return_modcp'], '<a href="' . $redirect_page . '">', '</a>');
		}

		$message = $message . '<br /><br />' . sprintf($lang['Click_return_forum'], '<a href="' . CMS_PAGE_VIEWFORUM . '?' . POST_FORUM_URL . '=' . $forum_id . '&amp;sid=' . $user->data['session_id'] . '">', '</a>');

		$redirect_url = $redirect_page;
		meta_refresh(3, $redirect_url);

		message_die(GENERAL_MESSAGE, $lang['Topics_Title_Edited'] . '<br /><br />' . $message);
		break;

	case 'news_category_edit':
		if(!$is_auth['auth_news'])
		{
			message_die(MESSAGE, $lang['Not_Authorized']);
		}

		if (empty($_POST['topic_id_list']) && empty($topic_id))
		{
			message_die(GENERAL_MESSAGE, $lang['None_selected']);
		}

		$topics = (isset($_POST['topic_id_list'])) ?  $_POST['topic_id_list'] : array($topic_id);
		$class_mcp->topic_news_category_edit($topics, $news_category);

		if (!empty($topic_id))
		{
			$redirect_page = CMS_PAGE_VIEWTOPIC . '?' . POST_TOPIC_URL . '=' . $topic_id . '&amp;sid=' . $user->data['session_id'];
			$message = sprintf($lang['Click_return_topic'], '<a href="' . $redirect_page . '">', '</a>');
		}
		else
		{
			$redirect_page = 'modcp.' . PHP_EXT . '?' . POST_FORUM_URL . '=' . $forum_id . '&amp;sid=' . $user->data['session_id'];
			$message = sprintf($lang['Click_return_modcp'], '<a href="' . $redirect_page . '">', '</a>');
		}

		$message = $message . '<br /><br />' . sprintf($lang['Click_return_forum'], '<a href="' . CMS_PAGE_VIEWFORUM . '?' . POST_FORUM_URL . '=' . $forum_id . '&amp;sid=' . $user->data['session_id'] . '">', '</a>');

		$redirect_url = $redirect_page;
		meta_refresh(3, $redirect_url);

		message_die(GENERAL_MESSAGE, $lang['Category_Updated'] . '<br /><br />' . $message);
		break;

	default:
		$meta_content['page_title'] = $lang['Mod_CP'];
		page_header($meta_content['page_title'], true);

		$u_topic_type = array('super_announce', 'announce', 'sticky', 'poll', 'locked');
		$l_topic_type = array($lang['Display_global'], $lang['Display_announce'], $lang['Display_sticky'], $lang['Display_poll'], $lang['Display_locked']);
		for($tt = 0; $tt < sizeof($u_topic_type); $tt++)
		{
			$topic_types .= ($type == $u_topic_type[$tt]) ? $l_topic_type[$tt] .'&nbsp;|&nbsp;' : '<a href="modcp.' . PHP_EXT . '?'. POST_FORUM_URL .'='. $forum_id .'&amp;type='. $u_topic_type[$tt] .'&amp;sid='. $user->data['session_id'] .'" class="genmed">'. $l_topic_type[$tt] .'</a>&nbsp;|&nbsp;';
		}
		$topic_types .= (empty($type)) ? $lang['Display_all'] : '<a href="modcp.' . PHP_EXT . '?'. POST_FORUM_URL .'='. $forum_id .'&amp;sid='. $user->data['session_id'] .'" class="genmed">'. $lang['Display_all'] .'</a>';

		if($forum_topics == '0')
		{
			$template->assign_block_vars("switch_no_topics", array());
		}

		// Topics Labels - BEGIN
		$topics_labels_select = $class_topics->gen_topics_labels_select();
		// Topics Labels - END

		// News
		$sql = 'SELECT * FROM ' . NEWS_TABLE . ' ORDER BY news_category';
		$result = $db->sql_query($sql, 0, 'news_cats_');
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
			'FORUM_NAME' => $class_mcp->find_names($forum_id),
			'TOPICS_LABELS_SELECT' => $topics_labels_select,
			'SELECT_NEWS_CATS' => $select_news_cats,

			'L_NEWS_CATEGORY' => $lang['Select_News_Category'],
			'L_NO_TOPICS' => $lang['Mod_CP_no_topics'],
			'L_MOD_CP' => $lang['Mod_CP'],
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
			'U_VIEW_FORUM' => append_sid(CMS_PAGE_VIEWFORUM . '?' . POST_FORUM_URL . '=' . $forum_id),
			'S_HIDDEN_FIELDS' => '<input type="hidden" name="sid" value="' . $user->data['session_id'] . '" /><input type="hidden" name="' . POST_FORUM_URL . '" value="' . $forum_id . '" />',
			'S_MODCP_ACTION' => append_sid('modcp.' . PHP_EXT),
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
		if(($type != 'unlocked' && (($type == 'locked') || ($type == 'poll') || ($type == 'sticky') || ($type == 'announce'))) || ($user->data['user_level'] == ADMIN))
		{
			$template->assign_block_vars('switch_auth_unlock', array());
		}

		$template->set_filenames(array('body' => 'modcp_body.tpl'));
		make_jumpbox('modcp.' . PHP_EXT);

		$sql = "SELECT t.*, u.username, u.user_id, u.user_active, u.user_color, p.post_time, p.post_id, p.post_username, p.enable_smilies, u2.username AS topic_starter, u2.user_id AS topic_starter_id, u2.user_active AS topic_starter_active, u2.user_color AS topic_starter_color, p2.post_id, p2.post_username AS topic_starter_guest
			FROM " . TOPICS_TABLE . " t, " . USERS_TABLE . " u, " . POSTS_TABLE . " p, " . USERS_TABLE . " u2, " . POSTS_TABLE . " p2
			WHERE t.forum_id = " . $forum_id . "
				AND p.poster_id = u.user_id
				AND t.topic_poster = u2.user_id
				AND p.post_id = t.topic_last_post_id
				AND p2.post_id = t.topic_first_post_id " . $where_type . "
			ORDER BY t.topic_type DESC, p.post_time DESC LIMIT " . $start . ", " . $config['topics_per_page'];
		$result = $db->sql_query($sql);

		$total_topics = 0;
		while($row = $db->sql_fetchrow($result))
		{
			$topic_rowset[] = $row;
			$total_topics++;
		}
		$db->sql_freeresult($result);

		// MG User Replied - BEGIN
		// check if user replied to the topic
		define('USER_REPLIED_ICON', true);
		$user_topics = $class_topics->user_replied_array($topic_rowset);
		// MG User Replied - END

		for($i = 0; $i < $total_topics; $i++)
		{
			$forum_id = $topic_rowset[$i]['forum_id'];
			$forum_id_append = (!empty($forum_id) ? (POST_FORUM_URL . '=' . $forum_id) : '');
			$topic_id = $topic_rowset[$i]['topic_id'];
			$topic_id_append = (!empty($topic_id) ? (POST_TOPIC_URL . '=' . $topic_id) : '');
			$user_replied = (!empty($user_topics) && isset($user_topics[$topic_id]));

			$topic_title = censor_text($topic_rowset[$i]['topic_title']);
			// Convert and clean special chars!
			$topic_title = htmlspecialchars_clean($topic_title);
			// SMILEYS IN TITLE - BEGIN
			if (($config['smilies_topic_title'] == true) && !$lofi)
			{
				$bbcode->allow_smilies = (($config['allow_smilies'] && $topic_rowset[$i]['enable_smilies']) ? true : false);
				$topic_title = $bbcode->parse_only_smilies($topic_title);
			}
			// SMILEYS IN TITLE - END
			$topic_title_label = (empty($topic_rowset[$i]['topic_label_compiled'])) ? '' : $topic_rowset[$i]['topic_label_compiled'] . ' ';
			$topic_title = $topic_title_label . $topic_title;

			//$news_label = ($topic_rowset[$i]['news_id'] > 0) ? $lang['News_Cmx'] . '' : '';
			$news_label = '';

			$replies = $topic_rowset[$i]['topic_replies'];
			$topic_type = $topic_rowset[$i]['topic_type'];

			$topic_link = $class_topics->build_topic_icon_link($forum_id, $topic_rowset[$i]['topic_id'], $topic_rowset[$i]['topic_type'], $topic_rowset[$i]['topic_reg'], $topic_rowset[$i]['topic_replies'], $topic_rowset[$i]['news_id'], $topic_rowset[$i]['poll_start'], $topic_rowset[$i]['topic_status'], $topic_rowset[$i]['topic_moved_id'], $topic_rowset[$i]['post_time'], $user_replied, $replies);

			if (!$topic_rowset[$i]['topic_status'] == TOPIC_MOVED)
			{
				$topic_id = $topic_link['topic_id'];
			}
			$topic_id_append = $topic_link['topic_id_append'];

			$topic_pagination = generate_topic_pagination($forum_id, $topic_id, $replies);

			$first_post_time = create_date_ip($config['default_dateformat'], $topic_rowset[$i]['topic_time'], $config['board_timezone']);
			//$first_post_author = ($topic_rowset[$i]['topic_starter_id'] == ANONYMOUS) ? (($topic_rowset[$i]['topic_starter_guest'] != '') ? $topic_rowset[$i]['topic_starter_guest'] . ' ' : $lang['Guest'] . ' ') : '<a href="' . append_sid(CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $topic_rowset[$i]['topic_starter_id']) . '">' . $topic_rowset[$i]['topic_starter'] . '</a> ';
			$first_post_author =  colorize_username($topic_rowset[$i]['topic_starter_id'], $topic_rowset[$i]['topic_starter'], $topic_rowset[$i]['topic_starter_color'], $topic_rowset[$i]['topic_starter_active']);

			$first_post_url = ($type == 'shadow') ? '' : '<a href="' . append_sid(CMS_PAGE_VIEWTOPIC . '?' . POST_TOPIC_URL . '=' . $topic_id) . '"><img src="' . $images['icon_latest_reply'] . '" alt="' . $lang['View_latest_post'] . '" title="' . $lang['View_latest_post'] . '" /></a>';

			$last_post_time = create_date_ip($config['default_dateformat'], $topic_rowset[$i]['post_time'], $config['board_timezone']);
			//$last_post_author = ($topic_rowset[$i]['user_id'] == ANONYMOUS) ? (($topic_rowset[$i]['post_username'] != '') ? $topic_rowset[$i]['post_username'] . ' ' : $lang['Guest'] . ' ') : '<a href="' . append_sid(CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $topic_rowset[$i]['user_id']) . '">' . $topic_rowset[$i]['username'] . '</a> ';
			$last_post_author =  colorize_username($topic_rowset[$i]['user_id'], $topic_rowset[$i]['username'], $topic_rowset[$i]['user_color'], $topic_rowset[$i]['user_active']);
			$last_post_url = '<a href="' . append_sid(CMS_PAGE_VIEWTOPIC . '?' . POST_POST_URL . '=' . $topic_rowset[$i]['topic_last_post_id']) . '#p' . $topic_rowset[$i]['topic_last_post_id'] . '"><img src="' . $images['icon_latest_reply'] . '" alt="' . $lang['View_latest_post'] . '" title="' . $lang['View_latest_post'] . '" /></a>';

			$u_view_topic = 'modcp.' . PHP_EXT . '?mode=split&amp;' . POST_TOPIC_URL . '=' . $topic_id . '&amp;sid=' . $user->data['session_id'];
			$topic_replies = $topic_rowset[$i]['topic_replies'];

			$last_post_time = create_date_ip($config['default_dateformat'], $topic_rowset[$i]['post_time'], $config['board_timezone']);

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
				'GOTO_PAGE' => $topic_pagination['base'],
				'GOTO_PAGE_FULL' => $topic_pagination['full'],
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
			'PAGINATION' => generate_pagination('modcp.' . PHP_EXT . '?' . POST_FORUM_URL . '=' . $forum_id . '&amp;sid=' . $user->data['session_id'], $forum_topics, $config['topics_per_page'], $start),
			'PAGE_NUMBER' => sprintf($lang['Page_of'], (floor($start / $config['topics_per_page']) + 1), ceil($forum_topics / $config['topics_per_page'])),
			'L_GOTO_PAGE' => $lang['Goto_page']
			)
		);
		$template->pparse('body');
		break;
}

page_footer(true, '', true);

?>