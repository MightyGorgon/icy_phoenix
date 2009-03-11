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
define('IN_POSTING', true);
// MG Cash MOD For IP - BEGIN
define('IN_CASHMOD', true);
define('CM_POSTING', true);
// MG Cash MOD For IP - END
// Added to optimize memory for attachments
define('ATTACH_DISPLAY', true);
define('ATTACH_POSTING', true);
define('CT_SECLEVEL', 'MEDIUM');
$ct_ignorepvar = array('helpbox');
define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_post.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_topics.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_calendar.' . PHP_EXT);

// Check and set various parameters
// UI2DB ADD
//, 'mark_edit' => 'mark_edit'
$params = array('submit' => 'post', 'news_category' => 'news_category', 'preview' => 'preview', 'draft' => 'draft', 'draft_mode' => 'draft_mode', 'delete' => 'delete', 'poll_delete' => 'poll_delete', 'poll_add' => 'add_poll_option', 'poll_edit' => 'edit_poll_option', 'mode' => 'mode', 'mark_edit' => 'mark_edit');
while(list($var, $param) = @each($params))
{
	if (!empty($_POST[$param]) || !empty($_GET[$param]))
	{
		$$var = (!empty($_POST[$param])) ? htmlspecialchars($_POST[$param]) : htmlspecialchars($_GET[$param]);
	}
	else
	{
		$$var = '';
	}
}

$confirm = isset($_POST['confirm']) ? true : false;
$sid = (isset($_POST['sid'])) ? $_POST['sid'] : 0;
$draft_confirm = false;
if(!empty($_POST['draft_confirm']))
{
	$draft_confirm = (($_POST['draft_confirm'] == true) ? true : false);
}
$draft = (!empty($draft) || ($draft_confirm == true)) ? true : false;

$params = array('forum_id' => POST_FORUM_URL, 'topic_id' => POST_TOPIC_URL, 'post_id' => POST_POST_URL, 'draft_id' => 'd', 'lock_subject' => 'lock_subject');
while(list($var, $param) = @each($params))
{
	if (!empty($_POST[$param]) || !empty($_GET[$param]))
	{
		$$var = (!empty($_POST[$param])) ? intval($_POST[$param]) : intval($_GET[$param]);
	}
	else
	{
		$$var = '';
	}
}

$draft_id = (!empty($draft_id) ? $draft_id : 0);
$draft_id = (($draft_id < 0) ? 0 : $draft_id);

if (($board_config['allow_drafts'] == true) && ($draft_mode == 'draft_load') && ($draft_id > 0))
{
	$sql = "SELECT d.*
		FROM " . DRAFTS_TABLE . " d
		WHERE d.draft_id = '" . $draft_id . "'
		LIMIT 1";

	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not obtain drafts', '', __LINE__, __FILE__, $sql);
	}
	if ($draft_row = $db->sql_fetchrow($result))
	{
		$db->sql_freeresult($result);
		if ($draft_row['forum_id'] > 0)
		{
			$forum_id = $draft_row['forum_id'];
			if ($draft_row['topic_id'] > 0)
			{
				$topic_id = $draft_row['topic_id'];
			}
			else
			{
				$topic_id = '';
			}
			//$_POST['subject'] = stripslashes($draft_row['draft_subject']);
			//$_POST['message'] = stripslashes($draft_row['draft_message']);
			$_POST['subject'] = htmlspecialchars_decode($draft_row['draft_subject'], ENT_COMPAT);
			$_POST['message'] = htmlspecialchars_decode($draft_row['draft_message'], ENT_COMPAT);
			$preview = true;
		}
		else
		{
			$_POST['subject'] = ip_stripslashes($draft_row['draft_subject']);
			$_POST['message'] = ip_stripslashes($draft_row['draft_message']);
			$preview = true;
		}
	}
}

$forum_id_append = (!empty($forum_id) ? (POST_FORUM_URL . '=' . $forum_id) : '');
$topic_id_append = (!empty($topic_id) ? (POST_TOPIC_URL . '=' . $topic_id) : '');
$post_id_append = (!empty($post_id) ? (POST_POST_URL . '=' . $post_id) : '');

// . (!empty($forum_id_append) ? ($forum_id_append . '&amp;') : '') . (!empty($topic_id_append) ? ($topic_id_append . '&amp;') : '') . $post_id_append

$s_hidden_fields = '';
$hidden_form_fields = '';
$refresh = $preview || $poll_add || $poll_edit || $poll_delete || ($draft && !$draft_confirm);
$orig_word = $replacement_word = array();

// Set topic type
//echo $topic_type;
//$topic_type = (in_array($topic_type, array(0, 1, 2, 3, 4))) ? $topic_type : POST_NORMAL;
$topic_show_portal = (!empty($_POST['topic_show_portal'])) ? true : false;
$topic_type = (!empty($_POST['topictype'])) ? intval($_POST['topictype']) : POST_NORMAL;
if (!$topic_type)
{
	$topic_type = POST_NORMAL;
}
$year = (!empty($_POST['topic_calendar_year'])) ? intval($_POST['topic_calendar_year']) : '';
$month = (!empty($_POST['topic_calendar_month'])) ? intval($_POST['topic_calendar_month']) : '';
$day = (!empty($_POST['topic_calendar_day'])) ? intval($_POST['topic_calendar_day']) : '';
$hour = (!empty($_POST['topic_calendar_hour'])) ? intval($_POST['topic_calendar_hour']) : '';
$min = (!empty($_POST['topic_calendar_min'])) ? intval($_POST['topic_calendar_min']) : '';
$d_day = (!empty($_POST['topic_calendar_duration_day'])) ? intval($_POST['topic_calendar_duration_day']) : '';
$d_hour = (!empty($_POST['topic_calendar_duration_hour'])) ? intval($_POST['topic_calendar_duration_hour']) : '';
$d_min = (!empty($_POST['topic_calendar_duration_min'])) ? intval($_POST['topic_calendar_duration_min']) : '';
if (empty($year) || empty($month) || empty($day))
{
	$year = '';
	$month = '';
	$day = '';
	$hour = '';
	$min = '';
	$d_day = '';
	$d_hour = '';
	$d_min = '';
}
if (empty($hour) && empty($min))
{
	$hour = '';
	$min = '';
	$d_hour = '';
	$d_min = '';
}

// start event
$topic_calendar_time = 0;
if (!empty($year))
{
	$topic_calendar_time = mktime(intval($hour), intval($min), 0, intval($month), intval($day), intval($year));
}

// duration
$topic_calendar_duration = 0;
$d_dur = $d_day . $d_hour . $d_min;
if (!empty($topic_calendar_time) && !empty($d_dur))
{
	$topic_calendar_duration = intval($d_day) * 86400 + intval($d_hour) * 3600 + intval($d_min) * 60;
	if ($topic_calendar_duration < 0)
	{
		$topic_calendar_duration = 0;
	}
}

// If the mode is set to topic review then output that review...
if ($mode == 'topicreview')
{
	require(IP_ROOT_PATH . 'includes/topic_review.' . PHP_EXT);

	topic_review($forum_id, $topic_id, false);
	exit;
}
elseif ($mode == 'smilies')
{
	generate_smilies('window');
	exit;
}

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

//
// Was cancel pressed? If so then redirect to the appropriate
// page, no point in continuing with any further checks
//
if (isset($_POST['cancel']))
{
	if ($postreport)
	{
		$redirect = VIEWTOPIC_MG . '?' . (!empty($forum_id_append) ? ($forum_id_append . '&') : '') . (!empty($topic_id_append) ? ($topic_id_append . '&') : '') . POST_POST_URL . '=' . $postreport;
		$post_append = '';
	}
	elseif ($post_id)
	{
		$redirect = VIEWTOPIC_MG . '?' . (!empty($forum_id_append) ? ($forum_id_append . '&') : '') . (!empty($topic_id_append) ? ($topic_id_append . '&') : '') . $post_id_append;
		$post_append = '#p' . $post_id;
	}
	elseif ($topic_id)
	{
		$redirect = VIEWTOPIC_MG . '?' . (!empty($forum_id_append) ? ($forum_id_append . '&') : '') . $topic_id_append;
		$post_append = '';
	}
	elseif ($forum_id)
	{
		$redirect = VIEWFORUM_MG . '?' . $forum_id_append;
		$post_append = '';
	}
	else
	{
		$redirect = FORUM_MG;
		$post_append = '';
	}
	redirect(append_sid($redirect, true) . $post_append);
}

// What auth type do we need to check?
$is_auth = array();
$is_auth_type = '';
$is_auth_type_cal = '';
switch($mode)
{
	case 'newtopic':
		if ($topic_type == POST_GLOBAL_ANNOUNCE)
		{
			$is_auth_type = 'auth_globalannounce';
		}
		elseif ($topic_type == POST_ANNOUNCE)
		{
			$is_auth_type = 'auth_announce';
		}
		elseif ($topic_type == POST_STICKY)
		{
			$is_auth_type = 'auth_sticky';
		}
		else
		{
			$is_auth_type = 'auth_post';
		}
		if (!empty($topic_calendar_time))
		{
			$is_auth_type_cal = 'auth_cal';
		}
		break;
	case 'reply':
	case 'quote':
		$is_auth_type = 'auth_reply';
		break;
	case 'editpost':
		$is_auth_type = 'auth_edit';
		break;
	case 'delete':
	case 'poll_delete':
		$is_auth_type = 'auth_delete';
		break;
	case 'vote':
		$is_auth_type = 'auth_vote';
		break;
	case 'topicreview':
		$is_auth_type = 'auth_read';
		break;
	case 'thank':
		$is_auth_type = 'auth_read';
		break;
	default:
		message_die(GENERAL_MESSAGE, $lang['No_post_mode']);
		break;
}

//
// Here we do various lookups to find topic_id, forum_id, post_id etc.
// Doing it here prevents spoofing (eg. faking forum_id, topic_id or post_id
//
$error_msg = '';
$post_data = array();
switch ($mode)
{
	case 'newtopic':
		if (empty($forum_id))
		{
			message_die(GENERAL_MESSAGE, $lang['Forum_not_exist']);
		}

		$sql = "SELECT *
			FROM " . FORUMS_TABLE . "
			WHERE forum_id = '" . $forum_id . "'";
		break;
	case 'thank':
	case 'reply':
	case 'vote':
		if (empty($topic_id))
		{
			message_die(GENERAL_MESSAGE, $lang['No_topic_id']);
		}

		$cash_sql = '';
		// MG Cash MOD For IP - BEGIN
		if (defined('CASH_MOD'))
		{
			$cash_sql = ', t.topic_poster';
		}
		// MG Cash MOD For IP - END
		$sql = "SELECT f.*, t.topic_status, t.topic_title, t.topic_type" . $cash_sql . "
			FROM " . FORUMS_TABLE . " f, " . TOPICS_TABLE . " t
			WHERE t.topic_id = '" . $topic_id . "'
				AND f.forum_id = t.forum_id";
		break;

	case 'quote':
	case 'editpost':
	case 'delete':
	case 'poll_delete':
		if (empty($post_id))
		{
			message_die(GENERAL_MESSAGE, $lang['No_post_id']);
		}

		// MG Cash MOD For IP - BEGIN
		if (defined('CASH_MOD'))
		{
			$temp = $submit;
			$submit = !(!$submit || (isset($board_config['cash_disable']) && !$board_config['cash_disable'] && (($mode == 'editpost') || ($mode == 'delete'))));
		}
		// MG Cash MOD For IP - END

		$select_sql = (!$submit) ? ', t.topic_title, t.news_id, t.topic_desc, t.topic_calendar_time, t.topic_calendar_duration, p.enable_bbcode, p.enable_html, p.enable_autolinks_acronyms, p.enable_smilies, p.enable_sig, p.post_username, p.post_subject, p.post_text, u.username, u.user_id, u.user_sig, u.user_level' : '';
		$from_sql = (!$submit) ? ", " . USERS_TABLE . " u" : '';
		$where_sql = (!$submit) ? "AND u.user_id = p.poster_id" : '';
		// MG Cash MOD For IP - BEGIN
		if (defined('CASH_MOD'))
		{
			$submit = $temp;
			unset($temp);
		}
		// MG Cash MOD For IP - END

		$sql = "SELECT f.*, t.topic_id, t.topic_status, t.topic_type, t.topic_first_post_id, t.topic_last_post_id, t.topic_vote, t.topic_show_portal, p.post_id, p.poster_id" . $select_sql . "
			FROM " . POSTS_TABLE . " p, " . TOPICS_TABLE . " t, " . FORUMS_TABLE . " f, " . FORUMS_RULES_TABLE . " fr" . $from_sql . "
			WHERE p.post_id = '" . $post_id . "'
				AND t.topic_id = p.topic_id
				AND f.forum_id = p.forum_id
				AND fr.forum_id = p.forum_id
				" . $where_sql;
		break;

	default:
		message_die(GENERAL_MESSAGE, $lang['No_valid_mode']);
}

if (($result = $db->sql_query($sql)) && ($post_info = $db->sql_fetchrow($result)))
{
	$db->sql_freeresult($result);

	$forum_id = $post_info['forum_id'];
	if (!empty($post_info['topic_calendar_duration']))
	{
		$post_info['topic_calendar_duration']++;
	}
	$forum_name = get_object_lang(POST_FORUM_URL . $post_info['forum_id'], 'name');

	$is_auth = auth(AUTH_ALL, $forum_id, $userdata, $post_info);

	// Topic Lock/Unlock
	$lock = (isset($_POST['lock'])) ? true : false;
	$unlock = (isset($_POST['unlock'])) ? true : false;

	if (($submit || $confirm) && ($lock || $unlock) && ($is_auth['auth_mod']) && ($mode != 'newtopic') && (!$refresh))
	{
		$t_id = (!isset($post_info['topic_id'])) ? $topic_id : $post_info['topic_id'];

		if ($unlock)
		{
			$sql = "UPDATE " . TOPICS_TABLE . "
			SET topic_status = " . TOPIC_UNLOCKED . "
			WHERE topic_id = " . $t_id . "
			AND topic_moved_id = 0";
		}
		elseif ($lock)
		{
			$sql = "UPDATE " . TOPICS_TABLE . "
			SET topic_status = " . TOPIC_LOCKED . "
			WHERE topic_id = " . $t_id . "
			AND topic_moved_id = 0";
		}

		if ($lock || $unlock)
		{
			if (!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'Could not update topics table', '', __LINE__, __FILE__, $sql);
			}
		}
	}

	if ($post_info['forum_status'] == FORUM_LOCKED && !$is_auth['auth_mod'])
	{
		message_die(GENERAL_MESSAGE, $lang['Forum_locked']);
	}
	elseif ($mode != 'newtopic' &&  $mode != 'thank' && $post_info['topic_status'] == TOPIC_LOCKED && !$is_auth['auth_mod'])
	{
		message_die(GENERAL_MESSAGE, $lang['Topic_locked']);
	}

	if (($mode == 'editpost') || ($mode == 'delete') || ($mode == 'poll_delete'))
	{
		$topic_id = $post_info['topic_id'];
		$topic_id_append = (!empty($topic_id) ? (POST_TOPIC_URL . '=' . $topic_id) : '');
		// MG Cash MOD For IP - BEGIN
		if (defined('CASH_MOD'))
		{
			$post_data['post_text'] = (($mode == 'editpost') || ($mode == 'delete')) ? $post_info['post_text'] : '';
		}
		// MG Cash MOD For IP - END
		$post_data['poster_post'] = ($post_info['poster_id'] == $userdata['user_id']) ? true : false;
		$post_data['first_post'] = ($post_info['topic_first_post_id'] == $post_id) ? true : false;
		$post_data['last_post'] = ($post_info['topic_last_post_id'] == $post_id) ? true : false;
		$post_data['last_topic'] = ($post_info['forum_last_post_id'] == $post_id) ? true : false;
		$post_data['has_poll'] = ($post_info['topic_vote']) ? true : false;
		$post_data['topic_type'] = $post_info['topic_type'];
		$topic_show_portal = ($topic_show_portal || $post_info['topic_show_portal']) ? true : false;
		$post_data['topic_show_portal'] = $topic_show_portal;
		$post_data['topic_calendar_time'] = $post_info['topic_calendar_time'];
		$post_data['topic_calendar_duration'] = $post_info['topic_calendar_duration'];
		$post_data['poster_id'] = $post_info['poster_id'];

		if (($board_config['allow_mods_edit_admin_posts'] == false) && ($post_info['user_level'] == ADMIN) && ($userdata['user_level'] != ADMIN))
		{
			message_die(GENERAL_ERROR, $lang['CannotEditAdminsPosts']);
		}

		if ($post_data['first_post'] && $post_data['has_poll'])
		{
			$sql = "SELECT *
				FROM " . VOTE_DESC_TABLE . " vd, " . VOTE_RESULTS_TABLE . " vr
				WHERE vd.topic_id = $topic_id
					AND vr.vote_id = vd.vote_id
				ORDER BY vr.vote_option_id";
			if (!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'Could not obtain vote data for this topic', '', __LINE__, __FILE__, $sql);
			}

			$poll_options = array();
			$poll_results_sum = 0;
			if ($row = $db->sql_fetchrow($result))
			{
				$poll_title = $row['vote_text'];
				$poll_id = $row['vote_id'];
				$poll_length = $row['vote_length'] / 86400;

				do
				{
					$poll_options[$row['vote_option_id']] = $row['vote_option_text'];
					$poll_results_sum += $row['vote_result'];
				}
				while ($row = $db->sql_fetchrow($result));
			}
			$db->sql_freeresult($result);

			$post_data['edit_poll'] = ((!$poll_results_sum || $is_auth['auth_mod']) && $post_data['first_post']) ? true : 0;
		}
		else
		{
			$post_data['edit_poll'] = ($post_data['first_post'] && $is_auth['auth_pollcreate']) ? true : false;
		}

		// Can this user edit/delete the post/poll?
		if ($post_info['poster_id'] != $userdata['user_id'] && !$is_auth['auth_mod'])
		{
			$message = ($delete || ($mode == 'delete')) ? $lang['Delete_own_posts'] : $lang['Edit_own_posts'];
			$message .= '<br /><br />' . sprintf($lang['Click_return_topic'], '<a href="' . append_sid(VIEWTOPIC_MG . '?' . (!empty($forum_id_append) ? ($forum_id_append . '&amp;') : '') . $topic_id_append) . '">', '</a>');

			message_die(GENERAL_MESSAGE, $message);
		}
		elseif (!$post_data['last_post'] && !$is_auth['auth_mod'] && ($mode == 'delete' || $delete))
		{
			message_die(GENERAL_MESSAGE, $lang['Cannot_delete_replied']);
		}
		elseif (!$post_data['edit_poll'] && !$is_auth['auth_mod'] && ($mode == 'poll_delete' || $poll_delete))
		{
			message_die(GENERAL_MESSAGE, $lang['Cannot_delete_poll']);
		}
	}
	else
	{
		if ($mode == 'quote')
		{
			$topic_id = $post_info['topic_id'];
			$topic_id_append = (!empty($topic_id) ? (POST_TOPIC_URL . '=' . $topic_id) : '');
		}

		if ($mode == 'newtopic')
		{
			$post_data['topic_type'] = POST_NORMAL;
		}
		elseif ($mode == 'reply')
		{
			$post_data['topic_type'] = $post_info['topic_type'];
		}

		// MG Cash MOD For IP - BEGIN
		if (defined('CASH_MOD'))
		{
			$post_data['topic_poster'] = ($mode == 'reply') ? $post_info['topic_poster'] : 0;
		}
		// MG Cash MOD For IP - END
		$post_data['first_post'] = ($mode == 'newtopic') ? true : 0;
		$post_data['last_post'] = false;
		$post_data['has_poll'] = false;
		$post_data['edit_poll'] = false;
	}

	if ($mode == 'poll_delete' && !isset($poll_id))
	{
		message_die(GENERAL_MESSAGE, $lang['No_such_post']);
	}

	// BEGIN cmx_slash_news_mod
	if($board_config['allow_news'] && $post_data['first_post'] && $is_auth['auth_post'] && ($is_auth['auth_news'] || ($is_auth['auth_mod'] && ($mode == 'editpost'))))
	{
		if($mode == 'editpost')
		{
			$post_data['news_id'] = $post_info['news_id'];
		}
		else
		{
			$post_data['news_id'] = 0;
		}
		$post_data['disp_news'] = true;
	}
	else
	{
		if($board_config['allow_news'] && $post_data['first_post'] && $is_auth['auth_post'] && !$is_auth['auth_news'] && ($mode == 'editpost'))
		{
			$post_data['news_id'] = $post_info['news_id'];
		}
		else
		{
			$post_data['news_id'] = 0;
		}
		$post_data['news_id'] = !empty($_POST['news_category']) ? $_POST['news_category'] : (!empty($post_data['news_id']) ? $post_data['news_id'] : 0);
		$hidden_form_fields .= '<input type="hidden" name="news_category" value="' . $post_data['news_id'] . '" />';
		$post_data['disp_news'] = false;
	}
// END cmx_slash_news_mod

}
else
{
	message_die(GENERAL_MESSAGE, $lang['No_such_post']);
}

// The user is not authed, if they're not logged in then redirect them, else show them an error message
if (!$is_auth[$is_auth_type] || (!empty($is_auth_type_cal) && !$is_auth[$is_auth_type_cal]))
{
	if ($userdata['session_logged_in'])
	{
		if (!empty($is_auth_type_cal) && !$is_auth[$is_auth_type_cal])
		{
			message_die(GENERAL_MESSAGE, sprintf($lang['Sorry_' . $is_auth_type_cal], $is_auth[$is_auth_type_cal . '_type']));
		}
		message_die(GENERAL_MESSAGE, sprintf($lang['Sorry_' . $is_auth_type], $is_auth[$is_auth_type . '_type']));
	}

	switch($mode)
	{
		case 'newtopic':
			$redirect = 'mode=newtopic&' . $forum_id_append;
			break;
		case 'thank':
		case 'reply':
		case 'topicreview':
			$redirect = 'mode=reply&' . (!empty($forum_id_append) ? ($forum_id_append . '&') : '') . $topic_id_append;
			break;
		case 'quote':
		case 'editpost':
			$redirect = 'mode=quote&' . (!empty($forum_id_append) ? ($forum_id_append . '&') : '') . (!empty($topic_id_append) ? ($topic_id_append . '&') : '') . $post_id_append;
			break;
	}
	$redirect .= ($post_reportid) ? '&post_reportid=' . $post_reportid : '';
	redirect(append_sid(LOGIN_MG . '?redirect=posting.' . PHP_EXT . '?' . $redirect, true));
}
// Self AUTH - BEGIN
elseif (intval($is_auth[$is_auth_type]) == AUTH_SELF)
{
	//self auth mod
	switch($mode)
	{
		case 'quote':
		case 'reply':
			$sql = "SELECT t.topic_id FROM " . TOPICS_TABLE . " t, " . USERS_TABLE. " u
				WHERE t.topic_id = '" . $topic_id . "'
				AND t.topic_poster = u.user_id
				AND u.user_id = '" . $userdata['user_id'] . "'";
			break;
	}
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not obtain self auth data for this topic', '', __LINE__, __FILE__, $sql);
	}
	$self_auth = $db->sql_fetchrow($result);
	if (empty($self_auth))
	{
		message_die(GENERAL_MESSAGE, sprintf($lang['Sorry_' . $is_auth_type], $is_auth[$is_auth_type . '_type']));
	}
}
// Self AUTH - END

// Set toggles for various options
if (!$board_config['allow_html'])
{
	$html_on = 0;
}
else
{
	$html_on = ($submit || $refresh) ? ((!empty($_POST['disable_html'])) ? 0 : 1) : (($userdata['user_id'] == ANONYMOUS) ? $board_config['allow_html'] : $userdata['user_allowhtml']);
}

$acro_auto_on = ($submit || $refresh) ? ((!empty($_POST['disable_acro_auto'])) ? 0 : 1) : 1;

if (!$board_config['allow_bbcode'])
{
	$bbcode_on = 0;
}
else
{
	$bbcode_on = ($submit || $refresh) ? ((!empty($_POST['disable_bbcode'])) ? 0 : 1) : (($userdata['user_id'] == ANONYMOUS) ? $board_config['allow_bbcode'] : $userdata['user_allowbbcode']);
}

if (!$board_config['allow_smilies'])
{
	$smilies_on = 0;
}
else
{
	$smilies_on = ($submit || $refresh) ? ((!empty($_POST['disable_smilies'])) ? 0 : 1) : (($userdata['user_id'] == ANONYMOUS) ? $board_config['allow_smilies'] : $userdata['user_allowsmile']);
}

if($is_auth['auth_news'])
{
	$topic_show_portal = ($submit || $refresh) ? (!empty($_POST['topic_show_portal']) ? 1 : 0) : 0;
}
else
{
	$topic_show_portal = ($submit || $refresh || ($mode == 'editpost')) ? (!empty($post_data['topic_show_portal']) ? 1 : 0) : 0;
}

if (($submit || $refresh) && $is_auth['auth_read'])
{
	$notify_user = (!empty($_POST['notify'])) ? 1 : 0;
}
else
{
	if ($mode != 'newtopic' && $userdata['session_logged_in'] && $is_auth['auth_read'])
	{
		$sql = "SELECT topic_id
			FROM " . TOPICS_WATCH_TABLE . "
			WHERE topic_id = $topic_id
				AND user_id = " . $userdata['user_id'];
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not obtain topic watch information', '', __LINE__, __FILE__, $sql);
		}

		$notify_user = ($db->sql_fetchrow($result)) ? true : $userdata['user_notify'];
		$db->sql_freeresult($result);
	}
	else
	{
		$notify_user = ($userdata['session_logged_in'] && $is_auth['auth_read']) ? $userdata['user_notify'] : 0;
	}
}

$attach_sig = ($submit || $refresh) ? ((!empty($_POST['attach_sig'])) ? true : 0) : (($userdata['user_id'] == ANONYMOUS) ? 0 : $userdata['user_attachsig']);
$setbm = ($submit || $refresh) ? ((!empty($_POST['setbm'])) ? true : 0) : (($userdata['user_id'] == ANONYMOUS) ? 0 : $userdata['user_setbm']);
execute_posting_attachment_handling();

// What shall we do?

// BEGIN cmx_slash_news_mod
// Get News Categories.
if($userdata['session_logged_in'] && $post_data['disp_news'])
{
	if (($mode == 'editpost') && empty($post_id))
	{
		message_die(GENERAL_MESSAGE, $lang['No_post_id']);
	}

	$sql = 'SELECT * FROM ' . NEWS_TABLE . ' ORDER BY news_category';
	if (!($result = $db->sql_query($sql, false, 'news_cats_')))
	{
		message_die(GENERAL_ERROR, 'Could not obtain news data', '', __LINE__, __FILE__, $sql);
	}

	$news_sel = array();
	$news_cat = array();
	while ($row = $db->sql_fetchrow($result))
	{
		if(($news_category > 0 && $news_category == $row['news_id']) ||
		($post_data['news_id'] > 0 && $post_data['news_id'] == $row['news_id']))
		{
			$news_sel = $row;
		}

		if($post_data['news_id'] != 0 && $post_data['news_id'] == $row['news_id'])
		{
			$news_sel = $row;
		}
		$news_cat[] = $row;
	}

	if(($post_data['news_id'] == 0) && ($news_category == 0))
	{
		$boxstring = '<option value="0">' . $lang['Regular_Post'] . '</option>';
	}
	else
	{
		$boxstring = '<option value="' . $news_sel['news_id'] . '">' . $news_sel['news_category'] . ' (' . $lang['Current_Selection'] . ')</option>';
		$boxstring .= '<option value="0">' . $lang['Regular_Post'] . '</option>';
	}

	if(count($news_cat) > 0)
	{
		for($i = 0; $i < count($news_cat); $i++)
		{
			if($news_cat[$i]['news_id'] != $post_data['news_id'])
			{
				$boxstring .= '<option value="' . $news_cat[$i]['news_id'] . '">' . $news_cat[$i]['news_category'] . '</option>';
			}
		}

		$template->assign_block_vars('switch_news_cat', array(
			'L_NEWS_CATEGORY' => $lang['Select_News_Category'],
			'S_NAME' => 'news_category',
			'S_CATEGORY_BOX' => $boxstring
			)
		);
	}
}
// END cmx_slash_news_mod

if (($delete || $poll_delete || ($mode == 'delete')) && !$confirm)
{
	// Confirm deletion
	$s_hidden_fields .= '<input type="hidden" name="' . POST_POST_URL . '" value="' . $post_id . '" />';
	$s_hidden_fields .= ($delete || $mode == 'delete') ? '<input type="hidden" name="mode" value="delete" />' : '<input type="hidden" name="mode" value="poll_delete" />';
	$s_hidden_fields .= '<input type="hidden" name="sid" value="' . $userdata['session_id'] . '" />';

	$l_confirm = ($delete || $mode == 'delete') ? $lang['Confirm_delete'] : $lang['Confirm_delete_poll'];

	// Output confirmation page
	include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);

	$template->set_filenames(array('confirm_body' => 'confirm_body.tpl'));

	$template->assign_vars(array(
		'MESSAGE_TITLE' => $lang['Information'],
		'MESSAGE_TEXT' => $l_confirm,

		'L_YES' => $lang['Yes'],
		'L_NO' => $lang['No'],

		'S_CONFIRM_ACTION' => append_sid('posting.' . PHP_EXT),
		'S_HIDDEN_FIELDS' => $s_hidden_fields
		)
	);

	$template->pparse('confirm_body');

	include(IP_ROOT_PATH . 'includes/page_tail.' . PHP_EXT);
}
elseif ($mode == 'thank')
{
	$topic_id = intval($_GET[POST_TOPIC_URL]);
	$topic_id_append = (!empty($topic_id) ? (POST_TOPIC_URL . '=' . $topic_id) : '');
	if (!($userdata['session_logged_in']))
	{
		$message = $lang['thanks_not_logged'];
		$message .=  '<br /><br />' . sprintf($lang['Click_return_topic'], '<a href="' . append_sid(VIEWTOPIC_MG . '?' . (!empty($forum_id_append) ? ($forum_id_append . '&amp;') : '') . $topic_id_append) . '">', '</a>');
		message_die(GENERAL_MESSAGE, $message);
	}
	if (empty($topic_id))
	{
		message_die(GENERAL_MESSAGE, 'No topic Selected');
	}

	$userid = $userdata['user_id'];
	$thanks_date = time();

	// Check if user is the topic starter
	$sql = "SELECT `topic_poster`
			FROM " . TOPICS_TABLE . "
			WHERE topic_id = '" . $topic_id . "'";
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Couldn\'t check for topic starter', '', __LINE__, __FILE__, $sql);
	}

	if (!($topic_starter_check = $db->sql_fetchrow($result)))
	{
		message_die(GENERAL_ERROR, 'Couldn\'t check for topic starter', '', __LINE__, __FILE__, $sql);
	}

	if ($topic_starter_check['topic_poster'] == $userdata['user_id'])
	{
		$message = $lang['t_starter'];
		$message .=  '<br /><br />' . sprintf($lang['Click_return_topic'], '<a href="' . append_sid(VIEWTOPIC_MG . '?' . (!empty($forum_id_append) ? ($forum_id_append . '&amp;') : '') . $topic_id_append) . '">', '</a>');
		message_die(GENERAL_MESSAGE, $message);
	}

	// Check if user had thanked before
	$sql = "SELECT `topic_id`
			FROM " . THANKS_TABLE . "
			WHERE topic_id = '" . $topic_id . "'
			AND user_id = '" . $userid . "'";
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Couldn\'t check for previous thanks', '', __LINE__, __FILE__, $sql);

	}
	if (!($thankfull_check = $db->sql_fetchrow($result)))
	{
		// Insert thanks
		$sql = "INSERT INTO " . THANKS_TABLE . " (topic_id, user_id, thanks_time)
		VALUES ('" . $topic_id . "', '" . $userid . "', " . $thanks_date . ") ";
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not insert thanks information', '', __LINE__, __FILE__, $sql);
		}
		$message = $lang['thanks_add'];
		// MG Cash MOD For IP - BEGIN
		if (defined('CASH_MOD'))
		{
			$message .= '<br />' . $GLOBALS['cm_posting']->cash_update_thanks($topic_starter_check['topic_poster']);
		}
		// MG Cash MOD For IP - END
	}
	else
	{
		$message = $lang['thanked_before'];
	}

	$redirect_url = append_sid(VIEWTOPIC_MG . '?' . (!empty($forum_id_append) ? ($forum_id_append . '&amp;') : '') . $topic_id_append);
	meta_refresh(3, $redirect_url);

	$message .= '<br /><br />' . sprintf($lang['Click_return_topic'], '<a href="' . $redirect_url . '">', '</a>');
	message_die(GENERAL_MESSAGE, $message);
}
elseif ($mode == 'vote')
{
	// Vote in a poll
	if (!empty($_POST['vote_id']))
	{
		$vote_option_id = intval($_POST['vote_id']);

		$sql = "SELECT vd.vote_id
			FROM " . VOTE_DESC_TABLE . " vd, " . VOTE_RESULTS_TABLE . " vr
			WHERE vd.topic_id = $topic_id
				AND vr.vote_id = vd.vote_id
				AND vr.vote_option_id = $vote_option_id
			GROUP BY vd.vote_id";
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not obtain vote data for this topic', '', __LINE__, __FILE__, $sql);
		}

		if ($vote_info = $db->sql_fetchrow($result))
		{
			$vote_id = $vote_info['vote_id'];

			$sql = "SELECT *
				FROM " . VOTE_USERS_TABLE . "
				WHERE vote_id = $vote_id
					AND vote_user_id = " . $userdata['user_id'];
			if (!($result2 = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'Could not obtain user vote data for this topic', '', __LINE__, __FILE__, $sql);
			}

			if (!($row = $db->sql_fetchrow($result2)))
			{
				$sql = "UPDATE " . VOTE_RESULTS_TABLE . "
					SET vote_result = vote_result + 1
					WHERE vote_id = $vote_id
						AND vote_option_id = $vote_option_id";
				if (!$db->sql_query($sql, BEGIN_TRANSACTION))
				{
					empty_cache_folders(POSTS_CACHE_FOLDER);
					message_die(GENERAL_ERROR, 'Could not update poll result', '', __LINE__, __FILE__, $sql);
				}

				$sql = "INSERT INTO " . VOTE_USERS_TABLE . " (vote_id, vote_user_id, vote_user_ip, vote_cast)
					VALUES ($vote_id, " . $userdata['user_id'] . ", '$user_ip', $vote_option_id)";
				if (!$db->sql_query($sql, END_TRANSACTION))
				{
					empty_cache_folders(POSTS_CACHE_FOLDER);
					message_die(GENERAL_ERROR, 'Could not insert user_id for poll', '', __LINE__, __FILE__, $sql);
				}

				$message = $lang['Vote_cast'];
			}
			else
			{
				$message = $lang['Already_voted'];
			}
			$db->sql_freeresult($result2);
		}
		else
		{
			$message = $lang['No_vote_option'];
		}
		$db->sql_freeresult($result);

		$redirect_url = append_sid(VIEWTOPIC_MG . '?' . (!empty($forum_id_append) ? ($forum_id_append . '&amp;') : '') . $topic_id_append);
		meta_refresh(3, $redirect_url);

		$message .= '<br /><br />' . sprintf($lang['Click_view_message'], '<a href="' . append_sid(VIEWTOPIC_MG . '?' . (!empty($forum_id_append) ? ($forum_id_append . '&amp;') : '') . $topic_id_append) . '">', '</a>');

		empty_cache_folders(POSTS_CACHE_FOLDER);

		message_die(GENERAL_MESSAGE, $message);
	}
	else
	{
		redirect(append_sid(VIEWTOPIC_MG . '?' . (!empty($forum_id_append) ? ($forum_id_append . '&') : '') . $topic_id_append, true));
	}
}
elseif ($submit || $confirm || ($draft && $draft_confirm))
{
	// Submit post/vote (newtopic, edit, reply, etc.)
	$return_message = '';
	$return_meta = '';
	// session id check
	if (($sid == '') || ($sid != $userdata['session_id']))
	{
		$error_msg .= (!empty($error_msg)) ? '<br />' . $lang['Session_invalid'] : $lang['Session_invalid'];
	}

	switch ($mode)
	{
		case 'editpost':
		case 'newtopic':
		case 'reply':
			// CrackerTracker v5.x
			if ($ctracker_config->settings['vconfirm_guest'] == 1 && !$userdata['session_logged_in'])
			{
				define('CRACKER_TRACKER_VCONFIRM', true);
				define('POST_CONFIRM_CHECK', true);
				include_once(IP_ROOT_PATH . 'ctracker/engines/ct_visual_confirm.' . PHP_EXT);
			}
			// CrackerTracker v5.x
			$username = (!empty($_POST['username'])) ? $_POST['username'] : '';
			$subject = (!empty($_POST['subject'])) ? trim($_POST['subject']) : '';
			$topic_desc = (!empty($_POST['topic_desc'])) ? trim($_POST['topic_desc']) : '';
			$message = (!empty($_POST['message'])) ? trim($_POST['message']) : '';
			$topic_calendar_time = ($topic_calendar_time != $post_data['topic_calendar_time'] && !$is_auth['auth_cal']) ? $post_data['topic_calendar_time'] : $topic_calendar_time;
			if (empty($topic_calendar_time)) $topic_calendar_time = 0;
			$topic_calendar_duration = ($topic_calendar_duration != $post_data['topic_calendar_duration'] && !$is_auth['auth_cal']) ? $post_data['topic_calendar_duration'] : $topic_calendar_duration;
			if (!empty($topic_calendar_duration))
			{
				$topic_calendar_duration--;
			}
			if (empty($topic_calendar_time) || empty($topic_calendar_duration))
			{
				$topic_calendar_duration = 0;
			}
			$poll_title = (isset($_POST['poll_title']) && $is_auth['auth_pollcreate']) ? $_POST['poll_title'] : '';
			$poll_options = (isset($_POST['poll_option_text']) && $is_auth['auth_pollcreate']) ? $_POST['poll_option_text'] : '';
			$poll_length = (isset($_POST['poll_length']) && $is_auth['auth_pollcreate']) ? $_POST['poll_length'] : '';
			$notes = empty($_POST['notes']) ? '' : trim(stripslashes($_POST['notes']));

			prepare_post($mode, $post_data, $bbcode_on, $html_on, $smilies_on, $error_msg, $username, $subject, $message, $poll_title, $poll_options, $poll_length, $topic_desc, $topic_calendar_time, $topic_calendar_duration);

			// MG Drafts - BEGIN
			if (($board_config['allow_drafts'] == true) && $draft && $draft_confirm && $userdata['session_logged_in'] && (($mode == 'reply') || ($mode == 'newtopic')))
			{
				save_draft($draft_id, $userdata['user_id'], $forum_id, $topic_id, ip_addslashes(strip_tags($subject)), ip_addslashes($message));
				//save_draft($draft_id, $userdata['user_id'], $forum_id, $topic_id, str_replace("\'", "''", strip_tags($subject)), str_replace("\'", "''", $message));
				$message = $lang['Drafts_Saved'] . '<br /><br />' . sprintf($lang['Click_return_forum'], '<a href="' . append_sid(VIEWFORUM_MG . '?' . POST_FORUM_URL . '=' . $forum_id) . '">', '</a>');

				$redirect_url = append_sid(VIEWFORUM_MG . '?' . POST_FORUM_URL . '=' . $forum_id);
				meta_refresh(3, $redirect_url);

				message_die(GENERAL_MESSAGE, $message);
			}
			// MG Drafts - END

			if ($error_msg == '')
			{
				if ($mode == 'reply')
				{
					$topic_type = $post_data['topic_type'];
				}
				else
				{
					$topic_type = (($topic_type != $post_data['topic_type']) && !$is_auth['auth_sticky'] && !$is_auth['auth_announce'] && !$is_auth['auth_globalannounce']) ? $post_data['topic_type'] : $topic_type;
				}
				if(($mode == 'editpost') && ($board_config['edit_notes'] == 1) && (strlen($notes) > 2))
				{
					$sql = "SELECT edit_notes FROM " . POSTS_TABLE . " WHERE post_id='" . $post_id . "'";
					$result = $db->sql_query($sql);
					$row = $db->sql_fetchrow($result);
					$db->sql_freeresult($result);
					$notes_list = strlen($row['edit_notes']) ? unserialize($row['edit_notes']) : array();
					// check limit and delete notes
					if(count($notes_list) >= intval($board_config['edit_notes_n']))
					{
						if($notes_list[$i]['poster'] == $userdata['user_id'])
						{
							$del_num = $i;
						}
						for($i = count($notes_list) - 1; $i >= 0; $i--)
						{
							$del_num = $i;
						}
						if($del_num >= 0)
						{
							$new_list = array();
							for($n = 0; $n < count($notes_list); $n++)
							{
								if($n !== $del_num)
								{
									$new_list[] = $notes_list[$n];
								}
							}
							$notes_list = $new_list;
						}
					}
					$notes_list[] = array(
						'poster' => $userdata['user_id'],
						'time' => time(),
						//'text' => htmlspecialchars($notes)
						'text' => $notes
					);
					empty_cache_folders(POSTS_CACHE_FOLDER);
					$sql = "UPDATE " . POSTS_TABLE . " SET edit_notes='" . addslashes(serialize($notes_list)) . "' WHERE post_id='" . $post_id . "'";
					$db->sql_query($sql);

					// We need this, otherwise editing for normal users will be account twice!
					$edit_count_sql = '';
					if($userdata['user_level'] == ADMIN)
					{
						$edit_count_sql = ", post_edit_count = (post_edit_count + 1)";
					}
					$edited_sql = "post_edit_time = '" . time() . "'" . $edit_count_sql . ", post_edit_id = '" . $userdata['user_id'] . "'";
					$sql = "UPDATE " . POSTS_TABLE . " SET " . $edited_sql . " WHERE post_id='" . $post_id . "'";
					$db->sql_query($sql);
				}
				if ($lock_subject)
				{
					$url = '[url="' . VIEWTOPIC_MG . '?' . (!empty($forum_id_append) ? ($forum_id_append . '&amp;') : '') . (!empty($topic_id_append) ? ($topic_id_append . '&amp;') : '') . POST_POST_URL . '=' . $lock_subject . '#p' . $lock_subject . '"]';
					$message = addslashes(sprintf($lang['Link_to_post'], $url, '[/url]')) . $message;
				}

				submit_post($mode, $post_data, $return_message, $return_meta, $forum_id, $topic_id, $post_id, $poll_id, $topic_type, $bbcode_on, $html_on, $acro_auto_on, $smilies_on, $attach_sig, str_replace("\'", "''", $username), str_replace("\'", "''", $subject), str_replace("\'", "''", $message), str_replace("\'", "''", $poll_title), $poll_options, $poll_length, $mark_edit, str_replace("\'", "''", $topic_desc), $topic_calendar_time, $topic_calendar_duration, $news_category, $topic_show_portal);
			}
			break;

		case 'delete':
		case 'poll_delete':
			if ($error_msg != '')
			{
				message_die(GENERAL_MESSAGE, $error_msg);
			}
			delete_post($mode, $post_data, $return_message, $return_meta, $forum_id, $topic_id, $post_id, $poll_id);
			break;
	}

	if ($error_msg == '')
	{
		if ($mode != 'editpost')
		{
			$user_id = (($mode == 'reply') || ($mode == 'newtopic')) ? $userdata['user_id'] : $post_data['poster_id'];
			update_post_stats($mode, $post_data, $forum_id, $topic_id, $post_id, $user_id);
		}
		$attachment_mod['posting']->insert_attachment($post_id);

		if (($error_msg == '') && ($mode != 'poll_delete'))
		{
			// Forum Notification - BEGIN
			$post_data['subject'] = $subject;
			$post_data['username'] = ($userdata['user_id'] == ANONYMOUS) ? $username : $userdata['username'];
			$post_data['message'] = $message;
			if ($post_data['first_post'])
			{
				// fetch topic title
				$sql = "SELECT topic_title, topic_id
					FROM " . TOPICS_TABLE . "
					WHERE topic_id = $topic_id";

				if (!($result = $db->sql_query($sql)))
				{
					empty_cache_folders(POSTS_CACHE_FOLDER);
					message_die(GENERAL_ERROR, 'Could not obtain topic title for notification', '', __LINE__, __FILE__, $sql);
				}

				if ($topic_info = $db->sql_fetchrow($result))
				{
					user_notification('newtopic', $post_data, $topic_info['topic_title'], $forum_id, $topic_id, $post_id, $notify_user);
				}

			}
			else
			{
				if ($setbm)
				{
					set_bookmark($topic_id);
				}
				// Forum Notification - BEGIN
				user_notification($mode, $post_data, $post_info['topic_title'], $forum_id, $topic_id, $post_id, $notify_user);
				// Forum Notification - END
			}
			// Forum Notification - END
		}

		if ($lock_subject)
		{
			$url = '<a href="' . append_sid(VIEWTOPIC_MG . '?' . (!empty($forum_id_append) ? ($forum_id_append . '&amp;') : '') . (!empty($topic_id_append) ? ($topic_id_append . '&amp;') : '') . POST_POST_URL . '=' . $lock_subject . '#p' . $lock_subject) . '">';
			$return_message = $lang['Report_stored'] . '<br /><br />' . sprintf($lang['Send_report'], $url, '</a>');
			$return_meta = str_replace($post_id, $lock_subject, $return_meta);
		}

		if (($error_msg == '') && ($lock) && ($mode == 'newtopic'))
		{
			empty_cache_folders(POSTS_CACHE_FOLDER);
			empty_cache_folders(FORUMS_CACHE_FOLDER);
			$sql = "UPDATE " . TOPICS_TABLE . "
				SET topic_status = " . TOPIC_LOCKED . "
				WHERE topic_id = " . $topic_id . "
					AND topic_moved_id = 0";

			if (!($result = $db->sql_query($sql)))
			{
				empty_cache_folders(POSTS_CACHE_FOLDER);
				empty_cache_folders(FORUMS_CACHE_FOLDER);
				message_die(GENERAL_ERROR, 'Could not update topics table', '', __LINE__, __FILE__, $sql);
			}
		}
		if (($mode == 'newtopic') || ($mode == 'reply'))
		{
			$tracking_topics = (!empty($_COOKIE[$board_config['cookie_name'] . '_t'])) ? unserialize($_COOKIE[$board_config['cookie_name'] . '_t']) : array();
			$tracking_forums = (!empty($_COOKIE[$board_config['cookie_name'] . '_f'])) ? unserialize($_COOKIE[$board_config['cookie_name'] . '_f']) : array();

			if (count($tracking_topics) + count($tracking_forums) == 100 && empty($tracking_topics[$topic_id]))
			{
				asort($tracking_topics);
				unset($tracking_topics[key($tracking_topics)]);
			}

			$tracking_topics[$topic_id] = time();

			setcookie($board_config['cookie_name'] . '_t', serialize($tracking_topics), 0, $board_config['cookie_path'], $board_config['cookie_domain'], $board_config['cookie_secure']);
		}

		// MOD: Redirect to Post (normal post) - BEGIN
		if (($mode == 'delete') && $post_data['first_post'] && $post_data['last_post'])
		{
			// URL for redirection after deleting an entire topic
			$redirect = VIEWFORUM_MG . '?' . POST_FORUM_URL . '=' . $forum_id;
			// If the above URL points to a location outside the phpBB directories
			// move the slashes on the next line to the start of the following line:
			//redirect(append_sid($redirect, true), true);
			redirect(append_sid($redirect, true));
		}
		elseif ($mode == 'delete')
		{
			// URL for redirection after deleting a post
			$redirect = VIEWTOPIC_MG . '?' . (!empty($forum_id_append) ? ($forum_id_append . '&') : '') . $topic_id_append;
			if (($board_config['url_rw'] == '1') || (($board_config['url_rw_guests'] == '1') && ($userdata['user_id'] == ANONYMOUS)))
			{
				$redirect = str_replace ('--', '-', make_url_friendly($subject) . '-vt' . $topic_id . '.html');
			}
			// If the above URL points to a location outside the phpBB directories
			// move the slashes on the next line to the start of the following line:
			//redirect(append_sid($redirect, true), true);
			redirect(append_sid($redirect, true));
		}
		elseif (($mode == 'reply') || ($mode == 'editpost') || ($mode == 'newtopic'))
		{
			// URL for redirection after posting or editing a post
			$redirect = VIEWTOPIC_MG . '?' . (!empty($forum_id_append) ? ($forum_id_append . '&') : '') . (!empty($topic_id_append) ? ($topic_id_append . '&') : '') . POST_POST_URL . '=' . $post_id;
			$post_append = '#p' . $post_id;
			if (($board_config['url_rw'] == '1') || (($board_config['url_rw_guests'] == '1') && ($userdata['user_id'] == ANONYMOUS)))
			{
				$redirect = str_replace ('--', '-', make_url_friendly($subject) . '-vp' . $post_id . '.html');
			}
			// If the above URL points to a location outside the phpBB directories
			// move the slashes on the next line to the start of the following line:
			//redirect(append_sid($redirect, true) . $post_append, true);
			redirect(append_sid($redirect, true) . $post_append);
		}
		// MOD: Redirect to Post (normal post) - END

		$template->assign_vars(array('META' => $return_meta));
		message_die(GENERAL_MESSAGE, $return_message);
	}
}
$notes = '';
if($refresh || isset($_POST['del_poll_option']) || ($error_msg != ''))
{
	$username = (!empty($_POST['username'])) ? htmlspecialchars(trim(stripslashes($_POST['username']))) : '';
	$subject = (!empty($_POST['subject'])) ? htmlspecialchars(trim(stripslashes($_POST['subject']))) : '';
	$message = (!empty($_POST['message'])) ? htmlspecialchars(trim(stripslashes($_POST['message']))) : '';
	$topic_desc = (!empty($_POST['topic_desc'])) ? htmlspecialchars(trim(stripslashes($_POST['topic_desc']))) : '';
	$notes = empty($_POST['notes']) ? '' : trim(stripslashes($_POST['notes']));

	$poll_title = (!empty($_POST['poll_title'])) ? htmlspecialchars(trim(stripslashes($_POST['poll_title']))) : '';
	$poll_length = (isset($_POST['poll_length'])) ? max(0, intval($_POST['poll_length'])) : 0;

	$poll_options = array();
	if (!empty($_POST['poll_option_text']))
	{
		while(list($option_id, $option_text) = @each($_POST['poll_option_text']))
		{
			if(isset($_POST['del_poll_option'][$option_id]))
			{
				unset($poll_options[$option_id]);
			}
			elseif (!empty($option_text))
			{
				$poll_options[intval($option_id)] = htmlspecialchars(trim(stripslashes($option_text)));
			}
		}
	}

	if (isset($poll_add) && !empty($_POST['add_poll_option_text']))
	{
		$poll_options[] = htmlspecialchars(trim(stripslashes($_POST['add_poll_option_text'])));
	}

	if ($mode == 'newtopic' || $mode == 'reply')
	{
		$user_sig = ($userdata['user_sig'] != '' && $board_config['allow_sig']) ? $userdata['user_sig'] : '';
	}
	elseif ($mode == 'editpost')
	{
		$user_sig = ($post_info['user_sig'] != '' && $board_config['allow_sig']) ? $post_info['user_sig'] : '';
	}

	if($preview)
	{
		if (!$userdata['user_allowswearywords'])
		{
			$orig_word = array();
			$replacement_word = array();
			obtain_word_list($orig_word, $replacement_word);
		}

		$preview_message = stripslashes(prepare_message(addslashes(unprepare_message($message)), $html_on, $bbcode_on, $smilies_on));
		$preview_subject = $subject;
		$preview_username = $username;

		// Finalise processing as per viewtopic
		if(!$html_on)
		{
			if(($user_sig != '') || !$userdata['user_allowhtml'])
			{
				$user_sig = preg_replace('#(<)([\/]?.*?)(>)#is', '&lt;\2&gt;', $user_sig);
			}
		}

		if(($attach_sig) && ($user_sig != ''))
		{
			$bbcode->allow_html = ($userdata['user_allowhtml'] && $board_config['allow_html']) ? true : false;
			$bbcode->allow_bbcode = ($userdata['user_allowbbcode'] && $board_config['allow_bbcode']) ? true : false;
			$bbcode->allow_smilies = ($userdata['user_allowsmile'] && $board_config['allow_smilies']) ? true : false;
			$bbcode->is_sig = true;
			$user_sig = $bbcode->parse($user_sig);
			$bbcode->is_sig = false;
			$user_sig = str_replace('&amp;', '&', $user_sig);
		}

		if(!empty($orig_word))
		{
			$preview_username = (!empty($username)) ? preg_replace($orig_word, $replacement_word, $preview_username) : '';
			$preview_subject = (!empty($subject)) ? preg_replace($orig_word, $replacement_word, $preview_subject) : '';
			$preview_message = (!empty($preview_message)) ? preg_replace($orig_word, $replacement_word, $preview_message) : '';
		}

		$bbcode->allow_html = $html_on;
		$bbcode->allow_bbcode = $bbcode_on;
		$bbcode->allow_smilies = $smilies_on;
		$preview_message = $bbcode->parse($preview_message);
		$preview_message = (($bbcode_on == false) && ($html_on == false)) ? str_replace("\n", '<br />', preg_replace("/\r\n/", "\n", $preview_message)) : $preview_message;
		// Start Autolinks For phpBB Mod
		if($acro_auto_on == true)
		{
			$orig_autolink = array();
			$replacement_autolink = array();
			obtain_autolink_list($orig_autolink, $replacement_autolink, $forum_id);
			$preview_message = $bbcode->acronym_pass($preview_message);
			if(!empty($orig_autolink))
			{
				$preview_message = (!empty($preview_message)) ? autolink_transform($preview_message, $orig_autolink, $replacement_autolink) : '';
			}
		}
		//$preview_message = kb_word_wrap_pass($preview_message);
		// End Autolinks For phpBB Mod
		if($attach_sig && ($user_sig != ''))
		{
			$user_sig = '<br /><br />' . $board_config['sig_line'] . '<br />' . $user_sig;
		}

		//$preview_message = str_replace("\n", '<br />', $preview_message);
		$url = '[url="' . VIEWTOPIC_MG . '?' . (!empty($forum_id_append) ? ($forum_id_append . '&') : '') . (!empty($topic_id_append) ? ($topic_id_append . '&') : '') . POST_POST_URL . '=' . $lock_subject . '#p' . $lock_subject . '"]';
		$extra_message_body = addslashes(sprintf($lang['Link_to_post'], $url, '[/url]')) . $message;
		$preview_message = ($lock_subject) ? stripslashes($extra_message_body) . $preview_message : $preview_message;

		$template->set_filenames(array('preview' => 'posting_preview.tpl'));
		if (!empty($topic_calendar_time))
		{
			$topic_calendar_duration_preview = $topic_calendar_duration-1;
			if ($topic_calendar_duration_preview < 0)
			{
				$topic_calendar_duration_preview = 0;
			}
			$preview_subject .= get_calendar_title($topic_calendar_time, $topic_calendar_duration_preview);
		}
		$attachment_mod['posting']->preview_attachments();

		$preview_subject = strtr($preview_subject, array_flip(get_html_translation_table(HTML_ENTITIES)));
		$template->assign_vars(array(
			'TOPIC_TITLE' => $preview_subject,
			'POST_SUBJECT' => $preview_subject,
			'POSTER_NAME' => $preview_username,
			'POST_DATE' => create_date2($board_config['default_dateformat'], time(), $board_config['board_timezone']),
			'MESSAGE' => $preview_message,
			'USER_SIG' => ($attach_sig) ? $user_sig : '',

			'L_POST_SUBJECT' => $lang['Post_subject'],
			'L_PREVIEW' => $lang['Preview'],
			'L_POSTED' => $lang['Posted'],
			'L_POST' => $lang['Post']
			)
		);
		$template->assign_var_from_handle('POST_PREVIEW_BOX', 'preview');
	}
	elseif($error_msg != '')
	{
		$template->set_filenames(array('reg_header' => 'error_body.tpl'));
		$template->assign_vars(array(
			'ERROR_MESSAGE' => $error_msg
			)
		);
		$template->assign_var_from_handle('ERROR_BOX', 'reg_header');
	}
}
else
{
	// User default entry point
	$postreport=(isset($_GET['postreport']))? intval($_GET['postreport']) : 0;
	if ($postreport)
	{
		$sql = 'SELECT topic_id FROM ' . POSTS_TABLE . ' WHERE post_id="' . $postreport . '"';
		if(!($result = $db->sql_query($sql)))
			message_die(GENERAL_ERROR, "Couldn't get post subject information");
		$post_details = $db->sql_fetchrow($result);
		$post_topic_id=$post_details['topic_id'];
		$sql = 'SELECT p.post_subject FROM ' . POSTS_TABLE . ' p WHERE p.topic_id="' . $post_topic_id . '" ORDER BY p.post_time ASC LIMIT 1';
		if(!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, "Couldn't get topic subject information <br />" . $sql);
		}
		$post_details = $db->sql_fetchrow($result);
		$subject = '(' . $postreport . ')' . $post_details['post_subject'];
		$lock_subject = $postreport;
	}
	else
	{
		$subject = '';
		$lock_subject = '';
	}

	if ($mode == 'newtopic')
	{
		$user_sig = ($userdata['user_sig'] != '') ? $userdata['user_sig'] : '';
		$message = '';
		// Start replacement - Yellow card MOD
		$username = ($userdata['session_logged_in']) ? $userdata['username'] : '';
		$poll_title = '';
		$poll_length = '';
		// End replacement - Yellow card MOD
	}
	elseif ($mode == 'reply')
	{
		$user_sig = ($userdata['user_sig'] != '') ? $userdata['user_sig'] : '';
		$username = ($userdata['session_logged_in']) ? $userdata['username'] : '';
		$subject = $lang['RE'] . ': ' . $post_info['topic_title'];
		$message = '';
	}
	elseif ($mode == 'quote' || $mode == 'editpost')
	{
		$subject = ($post_data['first_post']) ? $post_info['topic_title'] : $post_info['post_subject'];
		$message = $post_info['post_text'];
		$topic_desc = $post_info['topic_desc'];
		if ($mode == 'editpost')
		{
			$attach_sig = ($post_info['enable_sig'] && $post_info['user_sig'] != '') ? 1 : 0;
			$user_sig = $post_info['user_sig'];

			$topic_show_portal = ($post_info['topic_show_portal']) ? 1 : 0;
			$html_on = ($post_info['enable_html']) ? 1 : 0;
			$acro_auto_on = ($post_info['enable_autolinks_acronyms']) ? 1 : 0;
			$bbcode_on = ($post_info['enable_bbcode']) ? 1 : 0;
			$smilies_on = ($post_info['enable_smilies']) ? 1 : 0;
		}
		else
		{
			$attach_sig = ($userdata['user_attachsig']) ? 1 : 0;
			$user_sig = $userdata['user_sig'];
		}

		$message = str_replace('<', '&lt;', $message);
		$message = str_replace('>', '&gt;', $message);
		$message = str_replace('<br />', "\n", $message);

		if ($mode == 'quote')
		{
			if(preg_match('/\[hide/i', $message))
			{
				$search = array("/\[hide\](.*?)\[\/hide\]/");
				$replace = array('[hide]' . $lang['xs_bbc_hide_quote_message'] . '[/hide]');
				$message =  preg_replace($search, $replace, $message);
			}
			if (!$userdata['user_allowswearywords'])
			{
				$orig_word = array();
				$replacement_word = array();
				obtain_word_list($orig_word, $replacement_word);
			}

			$msg_date = create_date2($board_config['default_dateformat'], $postrow['post_time'], $board_config['board_timezone']);

			// Use trim to get rid of spaces placed there by MS-SQL 2000
			$quote_username = (trim($post_info['post_username']) != '') ? $post_info['post_username'] : $post_info['username'];
			//$message = '[quote="' . $quote_username . '"]' . $message . '[/quote]';
			$message = '[quote user="' . $quote_username . '" post="' . $post_id . '"]' . $message . '[/quote]';

			if (!empty($orig_word))
			{
				$subject = (!empty($subject)) ? preg_replace($orig_word, $replacement_word, $subject) : '';
				$message = (!empty($message)) ? preg_replace($orig_word, $replacement_word, $message) : '';
			}

			if (!preg_match('/^Re:/', $subject) && strlen($subject) > 0)
			{
				$subject = 'Re: ' . $subject;
			}

			$mode = 'reply';
		}
		else
		{
			$username = ($post_info['user_id'] == ANONYMOUS && !empty($post_info['post_username'])) ? $post_info['post_username'] : '';
		}
	}
}

if($mode == 'editpost' && $board_config['edit_notes'] == 1)
{
	$template->assign_block_vars('switch_edit', array(
		'L_EDIT_NOTES' => $lang['Edit_notes'],
		'NOTES' => htmlspecialchars($notes),
		)
	);
}

// Signature toggle selection
if($user_sig != '')
{
	$template->assign_block_vars('switch_signature_checkbox', array());
}

// HTML toggle selection
if ($board_config['allow_html'])
{
	$html_status = $lang['HTML_is_ON'];
	$template->assign_block_vars('switch_html_checkbox', array());
}
else
{
	$html_status = $lang['HTML_is_OFF'];
}

// BBCode toggle selection
if ($board_config['allow_bbcode'])
{
	$bbcode_status = $lang['BBCode_is_ON'];
	$template->assign_block_vars('switch_bbcode_checkbox', array());
}
else
{
	$bbcode_status = $lang['BBCode_is_OFF'];
}

// Smilies toggle selection
if ($board_config['allow_smilies'])
{
	$smilies_status = $lang['Smilies_are_ON'];
	$template->assign_block_vars('switch_smilies_checkbox', array());
}
else
{
	$smilies_status = $lang['Smilies_are_OFF'];
}

if(!$userdata['session_logged_in'] || (($mode == 'editpost') && $post_info['poster_id'] == ANONYMOUS))
{
	$template->assign_block_vars('switch_username_select', array());
}

//<!-- BEGIN Unread Post Information to Database Mod -->
if($userdata['upi2db_access'] && ($mode == 'editpost') && (($userdata['user_level'] == ADMIN) || ($userdata['user_level'] == MOD)))
{
	$template->assign_block_vars('switch_mark_edit_checkbox', array());
	$mark_edit = ($refresh) ? $mark_edit : true;
}
//<!-- END Unread Post Information to Database Mod -->

// Notify checkbox - only show if user is logged in
if ($userdata['session_logged_in'] && $is_auth['auth_read'])
{
	if ($mode != 'editpost' || ($mode == 'editpost' && $post_info['poster_id'] != ANONYMOUS))
	{
		$template->assign_block_vars('switch_notify_checkbox', array());
	}
}

// Bookmark checkbox - only show if user is logged in and not editing a post
if ($userdata['session_logged_in'])
{
	if ($mode != 'editpost')
	{
		$template->assign_block_vars('switch_bookmark_checkbox', array());
	}
}

// Delete selection
if (($mode == 'editpost') && (($is_auth['auth_delete'] && $post_data['last_post'] && (!$post_data['has_poll'] || $post_data['edit_poll'])) || $is_auth['auth_mod']))
{
	$template->assign_block_vars('switch_delete_checkbox', array());
}

// Lock/Unlock topic selection
if ((($mode == 'editpost') || ($mode == 'reply') || ($mode == 'quote') || ($mode == 'newtopic')) && ($is_auth['auth_mod']))
{
	if ($post_info['topic_status'] == TOPIC_LOCKED)
	{
		$template->assign_block_vars('switch_unlock_topic', array());

		$template->assign_vars(array(
			'L_UNLOCK_TOPIC' => $lang['Unlock_topic'],
			'S_UNLOCK_CHECKED' => ($unlock) ? 'checked="checked"' : ''
			)
		);
	}
	elseif ($post_info['topic_status'] == TOPIC_UNLOCKED)
	{
		$template->assign_block_vars('switch_lock_topic', array());
		$template->assign_vars(array(
			'L_LOCK_TOPIC' => $lang['Lock_topic'],
			'S_LOCK_CHECKED' => ($lock) ? 'checked="checked"' : ''
			)
		);
	}
}

// Topic type selection
$topic_type_toggle = '';
if ($mode == 'newtopic' || ($mode == 'editpost' && $post_data['first_post']))
{
	if($is_auth['auth_sticky'])
	{
		$topic_type_toggle .= '<input type="radio" name="topictype" value="' . POST_STICKY . '"';
		if ($post_data['topic_type'] == POST_STICKY || $topic_type == POST_STICKY)
		{
			$topic_type_toggle .= ' checked="checked"';
		}
		$topic_type_toggle .= ' /> ' . $lang['Post_Sticky'] . '&nbsp;&nbsp;';
	}

	if($is_auth['auth_announce'])
	{
		$topic_type_toggle .= '<input type="radio" name="topictype" value="' . POST_ANNOUNCE . '"';
		if ($post_data['topic_type'] == POST_ANNOUNCE || $topic_type == POST_ANNOUNCE)
		{
			$topic_type_toggle .= ' checked="checked"';
		}
		$topic_type_toggle .= ' /> ' . $lang['Post_Announcement'] . '&nbsp;&nbsp;';
	}

	if($is_auth['auth_globalannounce'])
	{
		$topic_type_toggle .= '<input type="radio" name="topictype" value="' . POST_GLOBAL_ANNOUNCE . '"';
		if ($post_data['topic_type'] == POST_GLOBAL_ANNOUNCE || $topic_type == POST_GLOBAL_ANNOUNCE)
		{
			$topic_type_toggle .= ' checked="checked"';
		}
		$topic_type_toggle .= ' /> ' . $lang['Post_global_announcement'] . '&nbsp;&nbsp;';
	}

	if ($topic_type_toggle != '')
	{
		$topic_type_toggle = '<input type="radio" name="topictype" value="' . POST_NORMAL .'"' . (($post_data['topic_type'] == POST_NORMAL || $topic_type == POST_NORMAL) ? ' checked="checked"' : '') . ' /> ' . $lang['Post_Normal'] . '&nbsp;&nbsp;' . $topic_type_toggle;
		$template->assign_block_vars('switch_type_toggle', array());
	}

}

// Calendar type selection
$topic_type_cal = '';
if (($mode == 'newtopic') || ($mode == 'editpost' && $post_data['first_post']))
{
	if($is_auth['auth_cal'])
	{
		$template->assign_block_vars('switch_type_cal', array());
		$months = array(
			' ------------ ',
			$lang['datetime']['January'],
			$lang['datetime']['February'],
			$lang['datetime']['March'],
			$lang['datetime']['April'],
			$lang['datetime']['May'],
			$lang['datetime']['June'],
			$lang['datetime']['July'],
			$lang['datetime']['August'],
			$lang['datetime']['September'],
			$lang['datetime']['October'],
			$lang['datetime']['November'],
			$lang['datetime']['December'],
		);

		// get the date
		$topic_calendar_time = (!isset($_POST['topic_calendar_year']) || (($topic_calendar_time != intval($post_data['topic_calendar_time'])) && !$is_auth['auth_cal'])) ? intval($post_data['topic_calendar_time']) : $topic_calendar_time;
		$topic_calendar_duration = ((!isset($_POST['topic_calendar_duration_day']) && !isset($_POST['topic_calendar_duration_hour']) && !isset($_POST['topic_calendar_duration_min'])) || (($topic_calendar_duration != intval($post_data['topic_calendar_duration'])) && !$is_auth['auth_cal'])) ? intval($post_data['topic_calendar_duration']) : $topic_calendar_duration;

		// get the components of the event date
		$year = '';
		$month = '';
		$day = '';
		$hour = '';
		$min = '';
		if (!empty($topic_calendar_time))
		{
			$year = intval(date('Y', $topic_calendar_time));
			$month = intval(date('m', $topic_calendar_time));
			$day = intval(date('d', $topic_calendar_time));
			$hour = intval(date('H', $topic_calendar_time));
			$min = intval(date('i', $topic_calendar_time));
		}

		// get the components of the duration
		$d_day = '';
		$d_hour = '';
		$d_min = '';
		if (!empty($topic_calendar_time) && !empty($topic_calendar_duration))
		{
			$d_dur = intval($topic_calendar_duration);
			$d_day = intval($d_dur / 86400);
			$d_dur = $d_dur - 86400 * $d_day;
			$d_hour = intval($d_dur / 3600);
			$d_dur = $d_dur - 3600 * $d_hour;
			$d_min = intval($d_dur / 60);
		}

		// raz if no date
		if (empty($year) || empty($month) || empty($day))
		{
			$year = '';
			$month = '';
			$day = '';
			$hour = '';
			$min = '';
			$d_day = '';
			$d_hour = '';
			$d_min = '';
		}

		// day list
		$s_topic_calendar_day = '<select name="topic_calendar_day">';
		for ($i = 0; $i <= 31; $i++)
		{
			$selected = (intval($day) == $i) ? ' selected="selected"' : '';
			$s_topic_calendar_day .= '<option value="' . $i . '"' . $selected . '>' . (($i == 0) ? ' -- ' : str_pad($i, 2, '0', STR_PAD_LEFT)) . '</option>';
		}
		$s_topic_calendar_day .= '</select>';

		// month list
		$s_topic_calendar_month = '<select name="topic_calendar_month">';
		for ($i = 0; $i <= 12; $i++)
		{
			$selected = (intval($month) == $i) ? ' selected="selected"' : '';
			$s_topic_calendar_month .= '<option value="' . $i . '"' . $selected . '>' . $months[$i] . '</option>';
		}
		$s_topic_calendar_month .= '</select>';

		// year list
		$s_topic_calendar_year = '<select name="topic_calendar_year">';

		$selected = empty($year) ? ' selected="selected"' : '';
		$s_topic_calendar_year .= '<option value="0"' . $select . '> ---- </option>';

		$start_year = ((intval($year) > 1971) && (intval($year) <= date('Y', time()))) ? intval($year) - 1 : date('Y', time()) - 1;
		for ($i = $start_year; $i <= date('Y', time()) + 10; $i++)
		{
			$selected = (intval($year) == $i) ? ' selected="selected"' : '';
			$s_topic_calendar_year .= '<option value="' . $i . '"' . $selected . '>' . $i . '</option>';
		}
		$s_topic_calendar_year .= '</select>';

		// time
		if (empty($hour) && empty($min))
		{
			$hour = '';
			$min = '';
		}
		$topic_calendar_hour = $hour;
		$topic_calendar_min = $min;

		// duration
		if (empty($topic_calendar_hour) && empty($topic_calendar_min))
		{
			$d_hour = '';
			$d_min = '';
		}
		if (empty($d_day) && empty($d_hour) && empty($d_min))
		{
			$d_day = '';
			$d_hour = '';
			$d_min = '';
		}
		$topic_calendar_duration_day = $d_day;
		$topic_calendar_duration_hour = $d_hour;
		$topic_calendar_duration_min = $d_min;
	}
}

$hidden_form_fields .= '<input type="hidden" name="mode" value="' . $mode . '" />';
$hidden_form_fields .= '<input type="hidden" name="sid" value="' . $userdata['session_id'] . '" />';
$hidden_form_fields .= ($lock_subject) ? '<input type="hidden" name="lock_subject" value="' . $lock_subject . '" />':'';

switch($mode)
{
	case 'newtopic':
		$page_title = $lang['Post_a_new_topic'];
		$hidden_form_fields .= '<input type="hidden" name="' . POST_FORUM_URL . '" value="' . $forum_id . '" />';
		break;
	case 'reply':
		$page_title = $lang['Post_a_reply'];
		$hidden_form_fields .= '<input type="hidden" name="' . POST_TOPIC_URL . '" value="' . $topic_id . '" />';
		break;
	case 'editpost':
		$page_title = $lang['Edit_Post'];
		$hidden_form_fields .= '<input type="hidden" name="' . POST_POST_URL . '" value="' . $post_id . '" />';
		break;
}

$page_title = ($postreport || $lock_subject) ? $lang['Post_a_report'] : $page_title;
$page_title_alt = $page_title;
$meta_description = '';
$meta_keywords = '';
$nav_add_page_title = true;

// Generate smilies listing for page output
//generate_smilies('inline');

// Include page header
include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);

$template->set_filenames(array(
	'body' => 'posting_body.tpl',
	'pollbody' => 'posting_poll_body.tpl',
	'reviewbody' => 'posting_topic_review.tpl'
	)
);

make_jumpbox(VIEWFORUM_MG);

$rules_bbcode = '';
if (!empty($post_info['rules_in_posting']))
{
	//BBcode Parsing for Olympus rules Start
	$rules_bbcode = $post_info['rules'];
	$bbcode->allow_html = true;
	$bbcode->allow_bbcode = true;
	$bbcode->allow_smilies = true;
	$rules_bbcode = $bbcode->parse($rules_bbcode);
	//BBcode Parsing for Olympus rules Start

	$template->assign_vars(array(
		'S_FORUM_RULES' => true,
		'S_FORUM_RULES_TITLE' => ($post_info['rules_display_title']) ? true : false
		)
	);
}

$template->assign_vars(array(
	'FORUM_ID' => $forum_id,
	'FORUM_NAME' => $forum_name,
	'FORUM_RULES' => $rules_bbcode,
	'L_FORUM_RULES' => (empty($post_info['rules_custom_title'])) ? $lang['Forum_Rules'] : $post_info['rules_custom_title'],
	'L_POST_A' => $page_title_alt,
	'L_POST_SUBJECT' => $lang['Post_subject'],
	'U_VIEW_FORUM' => append_sid(VIEWFORUM_MG . '?' . POST_FORUM_URL . '=' . $forum_id)
	)
);

//
// This enables the forum/topic title to be output for posting
// but not for privmsg (where it makes no sense)
//
$template->assign_block_vars('switch_not_privmsg', array());

// Enable the Topic Description MOD only if this is a new post or if you edit the fist post of a topic
if (($mode == 'newtopic') || (($mode == 'editpost') && $post_data['first_post']))
{
	if($is_auth['auth_news'])
	{
		$template->assign_block_vars('switch_show_portal', array());
	}
	if ($board_config['show_topic_description'])
	{
		$template->assign_block_vars('topic_description', array());
	}
}

// CrackerTracker v5.x
$confirm_image = '';
if (($ctracker_config->settings['vconfirm_guest'] == 1) && !$userdata['session_logged_in'])
{
	define('CRACKER_TRACKER_VCONFIRM', true);
	$template->assign_block_vars('switch_confirm', array());
	include_once(IP_ROOT_PATH . 'ctracker/engines/ct_visual_confirm.' . PHP_EXT);
}
// CrackerTracker v5.x

if ($board_config['ajax_features'] == true)
{
	$ajax_blur = ($mode == 'newtopic') ? 'onblur="AJAXSearch(this.value);"' : '';
	$ajax_pm_user_check = 'onkeyup="AJAXCheckPMUsername(this.value);"';
}
else
{
	$ajax_blur = '';
	$ajax_pm_user_check = '';
}

// MG Drafts - BEGIN
if ($board_config['allow_drafts'] == true)
{
	$template->assign_block_vars('allow_drafts', array());
	$hidden_form_fields .= '<input type="hidden" name="d" value="' . $draft_id . '" />';
	if (($draft == true) && ($draft_confirm == false))
	{
		$template->assign_block_vars('save_draft_confirm', array());
	}
}
// MG Drafts - END

// Convert and clean special chars!
$subject = htmlspecialchars_clean($subject);
$topic_desc = !empty($topic_desc) ? htmlspecialchars_clean($topic_desc) : '';

// Output the data to the template
$template->assign_vars(array(
	'FORUM_ID' => $forum_id,
	'USERNAME' => $username,
	'SUBJECT' => $subject,
	'MESSAGE' => $message,
	'HTML_STATUS' => $html_status,
	'BBCODE_STATUS' => sprintf($bbcode_status, '<a href="' . append_sid('faq.' . PHP_EXT . '?mode=bbcode') . '" target="_phpbbcode">', '</a>'),
	'SMILIES_STATUS' => $smilies_status,

	'L_SUBJECT' => $lang['Subject'],
	'L_TYPE_TOGGLE_TITLE' => $lang['Post_topic_as'],
	'L_MESSAGE_BODY' => $lang['Message_body'],
	'L_OPTIONS' => $lang['Options'],
	'L_PREVIEW' => $lang['Preview'],
	'L_DRAFTS' => $lang['Drafts'],
	'L_DRAFT_SAVE' => $lang['Drafts_Save'],
	'L_DRAFT_CONFIRM' => $lang['Drafts_Save_Question'],
	'L_SPELLCHECK' => $lang['Spellcheck'],
	'L_SUBMIT' => $lang['Submit'],
	'L_CANCEL' => $lang['Cancel'],
	'L_CONFIRM_DELETE' => $lang['Confirm_delete'],
	'L_DISABLE_HTML' => $lang['Disable_HTML_post'],
	'L_DISABLE_ACRO_AUTO' => $lang['Disable_ACRO_AUTO_post'],
	'L_DISABLE_BBCODE' => $lang['Disable_BBCode_post'],
	'L_DISABLE_SMILIES' => $lang['Disable_Smilies_post'],
	'L_ATTACH_SIGNATURE' => $lang['Attach_signature'],
	'L_SET_BOOKMARK' => $lang['Set_Bookmark'],
	'L_NOTIFY_ON_REPLY' => $lang['Notify'],
//<!-- BEGIN Unread Post Information to Database Mod -->
	'L_MARK_EDIT' => $lang['mark_edit'],
//<!-- END Unread Post Information to Database Mod -->
	'L_DELETE_POST' => $lang['Delete_post'],

	'L_SHOW_PORTAL' => $lang['Show_In_Portal'],
	'S_TOPIC_SHOW_PORTAL' => ($topic_show_portal) ? 'checked="checked"' : '',

	'L_POST_HIGHLIGHT' => $lang['PostHighlight'],
	'L_TOPIC_DESCRIPTION' => $lang['Topic_description'],

	'U_SMILEY_CREATOR' => append_sid('smiley_creator.' . PHP_EXT . '?mode=text2shield'),
	'U_VIEWTOPIC' => ($mode == 'reply') ? append_sid(VIEWTOPIC_MG . '?' . (!empty($forum_id_append) ? ($forum_id_append . '&amp;') : '') . $topic_id_append . '&amp;postorder=desc') : '',
	'U_REVIEW_TOPIC' => ($mode == 'reply') ? append_sid('posting.' . PHP_EXT . '?mode=topicreview&amp;' . (!empty($forum_id_append) ? ($forum_id_append . '&amp;') : '') . $topic_id_append) : '',
	'TOPIC_DESCRIPTION' => $topic_desc,

	// AJAX Features - BEGIN
	'S_AJAX_BLUR' => $ajax_blur,
	'S_AJAX_PM_USER_CHECK' => $ajax_pm_user_check,
	'S_IS_PM' => 0,
	'S_DISPLAY_PREVIEW' => ($preview) ? '' : 'style="display:none;"',
	'S_EDIT_POST_ID' => ($mode == 'editpost') ? $post_id : 0,
	'L_SEARCH_RESULTS' => $lang['AJAX_search_results'],
	'L_SEARCH_RESULT' => $lang['AJAX_search_result'],
	'L_EMPTY_SUBJECT' => $lang['Empty_subject'],
	'L_AJAX_NO_RESULTS' => $lang['No_search_match'],
	'L_MAX_POLL_OPTIONS' => $lang['To_many_poll_options'],
	'POLL_MAX_OPTIONS' => $board_config['max_poll_options'],
	// AJAX Features - END

	'L_CALENDAR_TITLE' => $lang['Calendar_event'],
	'L_TIME' => $lang['Event_time'],
	'L_CALENDAR_DURATION' => $lang['Calendar_duration'],
	'L_DAYS' => $lang['Days'],
	'L_HOURS' => $lang['Hours'],
	'L_MINUTES' => $lang['Minutes'],
	'L_TODAY' => $lang['Today'],

	'TODAY_DAY' => date('d', time()),
	'TODAY_MONTH' => date('m', time()),
	'TODAY_YEAR' => date('Y', time()),

	'S_CALENDAR_YEAR' => (!empty($s_topic_calendar_year) ? $s_topic_calendar_year : ''),
	'S_CALENDAR_MONTH' => (!empty($s_topic_calendar_month) ? $s_topic_calendar_month : ''),
	'S_CALENDAR_DAY' => (!empty($s_topic_calendar_day) ? $s_topic_calendar_day : ''),

	'CALENDAR_HOUR' => (!empty($topic_calendar_hour) ? $topic_calendar_hour : ''),
	'CALENDAR_MIN' => (!empty($topic_calendar_min) ? $topic_calendar_min : ''),
	'CALENDAR_DURATION_DAY' => (!empty($topic_calendar_duration_day) ? $topic_calendar_duration_day : ''),
	'CALENDAR_DURATION_HOUR' => (!empty($topic_calendar_duration_hour) ? $topic_calendar_duration_hour : ''),
	'CALENDAR_DURATION_MIN' => (!empty($topic_calendar_duration_min) ? $topic_calendar_duration_min : ''),
	'S_HTML_CHECKED' => (!$html_on) ? 'checked="checked"' : '',
	'S_ACRO_AUTO_CHECKED' => ($acro_auto_on == false) ? ' checked="checked"' : '',
	'S_BBCODE_CHECKED' => (!$bbcode_on) ? 'checked="checked"' : '',
	'S_SMILIES_CHECKED' => (!$smilies_on) ? 'checked="checked"' : '',
	'S_SIGNATURE_CHECKED' => ($attach_sig) ? 'checked="checked"' : '',
	'S_SETBM_CHECKED' => ($setbm) ? 'checked="checked"' : '',

	// Start replacement - Yellow card admin MOD
	'S_NOTIFY_CHECKED' => ($is_auth['auth_read']) ? (($notify_user) ? 'checked="checked"' : '') : 'DISABLED',
	'S_LOCK_SUBJECT' => ($lock_subject) ? ' READONLY ' : '',
	// End replacement - Yellow card admin MOD
//<!-- BEGIN Unread Post Information to Database Mod -->
	'S_MARK_EDIT_CHECKED' => ($mark_edit) ? 'checked="checked"' : '',
//<!-- BEGIN Unread Post Information to Database Mod -->

	// CrackerTracker v5.x
	'CONFIRM_IMAGE' => $confirm_image,
	'L_CT_CONFIRM' => $lang['ctracker_vc_guest_post'],
	'L_CT_CONFIRM_E' => $lang['ctracker_vc_guest_expl'],
	'S_HIDDEN_FIELDS' => $s_hidden_fields,
	// CrackerTracker v5.x

	'S_TYPE_TOGGLE' => $topic_type_toggle,
	'S_TOPIC_ID' => $topic_id,
	'S_POST_ACTION' => append_sid('posting.' . PHP_EXT),
	'S_HIDDEN_FORM_FIELDS' => $hidden_form_fields
	)
);

// Poll entry switch/output
if(($mode == 'newtopic' || ($mode == 'editpost' && $post_data['edit_poll'])) && $is_auth['auth_pollcreate'])
{
	$template->assign_vars(array(
		'L_ADD_A_POLL' => $lang['Add_poll'],
		'L_ADD_POLL_EXPLAIN' => $lang['Add_poll_explain'],
		'L_POLL_QUESTION' => $lang['Poll_question'],
		'L_POLL_OPTION' => $lang['Poll_option'],
		'L_ADD_OPTION' => $lang['Add_option'],
		'L_UPDATE_OPTION' => $lang['Update'],
		'L_DELETE_OPTION' => $lang['Delete'],
		'L_POLL_LENGTH' => $lang['Poll_for'],
		'L_DAYS' => $lang['Days'],
		'L_POLL_LENGTH_EXPLAIN' => $lang['Poll_for_explain'],
		'L_POLL_DELETE' => $lang['Delete_poll'],

		'POLL_TITLE' => $poll_title,
		'POLL_LENGTH' => $poll_length
		)
	);

	if(($mode == 'editpost') && $post_data['edit_poll'] && $post_data['has_poll'])
	{
		$template->assign_block_vars('switch_poll_delete_toggle', array());
	}

	if(!empty($poll_options))
	{
		while(list($option_id, $option_text) = each($poll_options))
		{
			$template->assign_block_vars('poll_option_rows', array(
				'POLL_OPTION' => str_replace('"', '&quot;', $option_text),
				'S_POLL_OPTION_NUM' => $option_id)
			);
		}
	}

	$template->assign_var_from_handle('POLLBOX', 'pollbody');
}

// Topic review
if($mode == 'reply' && $is_auth['auth_read'])
{
	require(IP_ROOT_PATH . 'includes/topic_review.' . PHP_EXT);
	topic_review($forum_id, $topic_id, true);

	$template->assign_block_vars('switch_inline_mode', array());
	$template->assign_var_from_handle('TOPIC_REVIEW_BOX', 'reviewbody');
}

// BBCBMG - BEGIN
include_once(IP_ROOT_PATH . 'language/lang_' . $board_config['default_lang'] . '/lang_bbcb_mg.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/bbcb_mg.' . PHP_EXT);
$template->assign_var_from_handle('BBCB_MG', 'bbcb_mg');
// BBCBMG - END
// BBCBMG SMILEYS - BEGIN
generate_smilies('inline');
include(IP_ROOT_PATH . 'includes/bbcb_smileys_mg.' . PHP_EXT);
$template->assign_var_from_handle('BBCB_SMILEYS_MG', 'bbcb_smileys_mg');
// BBCBMG SMILEYS - END

$template->pparse('body');
include(IP_ROOT_PATH . 'includes/page_tail.' . PHP_EXT);

?>