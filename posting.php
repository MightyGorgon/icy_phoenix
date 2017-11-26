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
// Event Registration - BEGIN
include_once(IP_ROOT_PATH . 'includes/functions_events_reg.' . PHP_EXT);
// Event Registration - END

@include_once(IP_ROOT_PATH . 'includes/class_topics.' . PHP_EXT);
$class_topics = new class_topics();

$use_jquery_tags = (!empty($config['use_jquery_tags']) && empty($user->data['mobile_style'])) ? true : false;
//$use_jquery_tags = false;
$config['jquery_ui'] = true;
if (!empty($use_jquery_tags))
{
	$config['jquery_tags'] = true;
}

// Init common vars: forum_id, topic_id, post_id, etc.
$class_topics->var_init(true);

// Check and set various parameters
$sid = request_var('sid', '');

$mode = request_var('mode', '');
$submit = request_var('post', '');
$news_category = request_var('news_category', '');
$preview = request_var('preview', '');
$draft = request_var('draft', '');
$draft_mode = request_var('draft_mode', '');
$delete = request_var('delete', '');
$poll_delete = request_var('poll_delete', '');
$poll_add = request_var('add_poll_option', '');
$poll_edit = request_var('edit_poll_option', '');
// UPI2DB - BEGIN
$mark_edit = request_var('mark_edit', '');
// UPI2DB - END

$start = request_var('start', 0);
$start = ($start < 0) ? 0 : $start;

$confirm = isset($_POST['confirm']) ? true : false;
$draft_confirm = !empty($_POST['draft_confirm']) ? true : false;
$draft = (!empty($draft) || $draft_confirm) ? true : false;

$lock_subject = request_var('lock_subject', 0);

$draft_subject = '';
$draft_message = '';
if ($config['allow_drafts'] && ($draft_mode == 'draft_load') && ($draft_id > 0))
{
	$sql = "SELECT d.*
		FROM " . DRAFTS_TABLE . " d
		WHERE d.draft_id = " . $draft_id . "
		LIMIT 1";
	$result = $db->sql_query($sql);

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
			$draft_subject = $draft_row['draft_subject'];
			$draft_message = htmlspecialchars_decode($draft_row['draft_message'], ENT_COMPAT);
			$preview = true;
		}
		else
		{
			$draft_subject = $draft_row['draft_subject'];
			$draft_message = $draft_row['draft_message'];
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
$refresh = !empty($preview) || $poll_add || $poll_edit || $poll_delete || ($draft && !$draft_confirm);

// Set topic type
//echo $topic_type;
//$topic_type = (in_array($topic_type, array(0, 1, 2, 3, 4))) ? $topic_type : POST_NORMAL;
$topic_show_portal = (!empty($_POST['topic_show_portal'])) ? true : false;
$topic_type = request_var('topictype', POST_NORMAL);
if (!$topic_type)
{
	$topic_type = POST_NORMAL;
}

// Maybe better do not replace these $_POST with request_var, or we may have further problems later
$year = request_post_var('topic_calendar_year', 0);
$month = request_post_var('topic_calendar_month', 0);
$day = request_post_var('topic_calendar_day', 0);
$hour = request_post_var('topic_calendar_hour', 0);
$min = request_post_var('topic_calendar_min', 0);
$d_day = request_post_var('topic_calendar_duration_day', 0);
$d_hour = request_post_var('topic_calendar_duration_hour', 0);
$d_min = request_post_var('topic_calendar_duration_min', 0);

// this array will hold the plugin-specific variables
$extra_vars = array();
/**
* @event posting.post_vars.
* @description Allows to read POST data to be used later.
* @since 3.0
* @var int topic_type The topic type.
* @var array extra_vars The extra variables that'll be carried throughout this file.
*/
$vars = array(
	'topic_type',
	'extra_vars',
);
extract($class_plugins->trigger('posting.post_vars', compact($vars)));

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
	$topic_calendar_time = gmmktime(intval($hour), intval($min), 0, intval($month), intval($day), intval($year));
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
$user->session_begin();
$auth->acl($user->data);
$user->setup();
// End session management

// DNSBL CHECK - BEGIN
if (!empty($config['check_dnsbl_posting']) && in_array($mode, array('newtopic', 'reply', 'editpost')) && !empty($submit))
{
	if (($dnsbl = $user->check_dnsbl('post')) !== false)
	{
		$error[] = sprintf($lang['IP_BLACKLISTED'], $user->ip, $dnsbl[1], $dnsbl[1]);
	}

	if (!empty($error))
	{
		$message = implode('<br />', $error);
		message_die(GENERAL_MESSAGE, $message);
	}
}
// DNSBL CHECK - END

// Was cancel pressed? If so then redirect to the appropriate page, no point in continuing with any further checks
if (isset($_POST['cancel']))
{
	if ($postreport)
	{
		$redirect = CMS_PAGE_VIEWTOPIC . '?' . (!empty($forum_id_append) ? ($forum_id_append . '&') : '') . (!empty($topic_id_append) ? ($topic_id_append . '&') : '') . POST_POST_URL . '=' . $postreport;
		$post_append = '';
	}
	elseif ($post_id)
	{
		$redirect = CMS_PAGE_VIEWTOPIC . '?' . (!empty($forum_id_append) ? ($forum_id_append . '&') : '') . (!empty($topic_id_append) ? ($topic_id_append . '&') : '') . $post_id_append;
		$post_append = '#p' . $post_id;
	}
	elseif ($topic_id)
	{
		$redirect = CMS_PAGE_VIEWTOPIC . '?' . (!empty($forum_id_append) ? ($forum_id_append . '&') : '') . $topic_id_append;
		$post_append = '';
	}
	elseif ($forum_id)
	{
		$redirect = CMS_PAGE_VIEWFORUM . '?' . $forum_id_append;
		$post_append = '';
	}
	else
	{
		$redirect = CMS_PAGE_FORUM;
		$post_append = '';
	}
	redirect(append_sid($redirect, true) . $post_append);
}

// What auth type do we need to check?
$is_auth = array();
$is_auth_type = '';
$is_auth_type_cal = '';
$read_only_write_auth_required = false;
switch($mode)
{
	case 'newtopic':
		// TODO: these also need to be checked if ($mode == 'editpost' && $post_data['first_post'])
		$read_only_write_auth_required = true;
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
		$read_only_write_auth_required = true;
		$is_auth_type = 'auth_reply';
		break;
	case 'editpost':
		$read_only_write_auth_required = true;
		$is_auth_type = 'auth_edit';
		break;
	case 'delete':
	case 'poll_delete':
		$read_only_write_auth_required = true;
		$is_auth_type = 'auth_delete';
		break;
	case 'vote':
		$is_auth_type = 'auth_vote';
		break;
	// Event Registration - BEGIN
	case 'register':
		$is_auth_type = 'auth_vote';
		break;
	// Event Registration - END
	case 'topicreview':
		$is_auth_type = 'auth_read';
		break;
	default:
		message_die(GENERAL_MESSAGE, $lang['No_post_mode']);
		break;
}

//if ($read_only_write_auth_required && $config['read_only_forum'])
if ($read_only_write_auth_required && $config['read_only_forum'] && ($user->data['user_level'] != ADMIN))
{
	message_die(GENERAL_MESSAGE, $lang['READ_ONLY_FORUM']);
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
			if (!defined('STATUS_404')) define('STATUS_404', true);
			message_die(GENERAL_MESSAGE, 'NO_FORUM');
		}

		$sql = "SELECT f.*
			FROM " . FORUMS_TABLE . " f
			WHERE f.forum_id = " . $forum_id . "
			LIMIT 1";
		break;
	case 'reply':
	case 'vote':
	// Event Registration - BEGIN
	case 'register':
	// Event Registration - END
		if (empty($topic_id))
		{
			message_die(GENERAL_MESSAGE, $lang['No_topic_id']);
		}

		$sql = "SELECT f.*, t.*
			FROM " . FORUMS_TABLE . " f, " . TOPICS_TABLE . " t
			WHERE t.topic_id = " . $topic_id . "
				AND f.forum_id = t.forum_id
			LIMIT 1";
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
		if (!empty($config['plugins']['cash']['enabled']))
		{
			$temp = $submit;
			$submit = !(!$submit || (isset($config['cash_disable']) && !$config['cash_disable'] && (($mode == 'editpost') || ($mode == 'delete'))));
		}
		// MG Cash MOD For IP - END

		$query = array(
			'SELECT' => array('f.*', 't.*', 'p.*'),
			'FROM' => array(
				POSTS_TABLE => 'p',
				TOPICS_TABLE => 't',
				FORUMS_TABLE => 'f',
			),
			'WHERE' => array(
				'p.post_id = ' . $post_id,
				't.topic_id = p.topic_id',
				'f.forum_id = p.forum_id',
			),
			'LIMIT' => 1,
		);
		if (!$submit)
		{
			$query['SELECT'] = array_merge($query['SELECT'], array('u.username', 'u.user_id', 'u.user_sig', 'u.user_level', 'u.user_active', 'u.user_color'));
			$query['FROM'][USERS_TABLE] = 'u';
			$query['WHERE'][] = 'u.user_id = p.poster_id';
		}

		/**
		* @event posting.before_select.
		* @description Allows to edit the query to look up the forum / topic / post data.
		* @since 3.0
		* @var array query The SQL query parts.
		*/
		extract($class_plugins->trigger('posting.before_select', compact('query')));

		$sql = $db->sql_build_query('SELECT', $query);

		// MG Cash MOD For IP - BEGIN
		if (!empty($config['plugins']['cash']['enabled']))
		{
			$submit = $temp;
			unset($temp);
		}
		// MG Cash MOD For IP - END
		break;

	default:
		message_die(GENERAL_MESSAGE, $lang['No_valid_mode']);
}

$result = $db->sql_query($sql);
$post_info = $db->sql_fetchrow($result);
if ($result && $post_info)
{
	$db->sql_freeresult($result);

	$forum_id = $post_info['forum_id'];
	if (!empty($post_info['topic_calendar_duration']))
	{
		$post_info['topic_calendar_duration']++;
	}
	$forum_name = get_object_lang(POST_FORUM_URL . $post_info['forum_id'], 'name');

	$is_auth = auth(AUTH_ALL, $forum_id, $user->data, $post_info);

	// Topic Lock/Unlock
	$lock = (isset($_POST['lock'])) ? true : false;
	$unlock = (isset($_POST['unlock'])) ? true : false;

	if (($submit || $confirm) && ($lock || $unlock) && $is_auth['auth_mod'] && ($mode != 'newtopic') && (!$refresh))
	{
		$t_id = (!isset($post_info['topic_id'])) ? $topic_id : $post_info['topic_id'];
		if ($lock || $unlock)
		{
			$sql = "UPDATE " . TOPICS_TABLE . "
			SET topic_status = " . ($lock ? TOPIC_LOCKED : TOPIC_UNLOCKED) . "
			WHERE topic_id = " . $t_id . "
			AND topic_moved_id = 0";
			$result = $db->sql_query($sql);
		}
	}

	if (($post_info['forum_status'] == FORUM_LOCKED) && !$is_auth['auth_mod'])
	{
		message_die(GENERAL_MESSAGE, $lang['Forum_locked']);
	}
	elseif (($mode != 'newtopic') && ($post_info['topic_status'] == TOPIC_LOCKED) && !$is_auth['auth_mod'])
	{
		message_die(GENERAL_MESSAGE, $lang['Topic_locked']);
	}

	// LIMIT POST EDIT TIME - BEGIN
	$is_global_limit_edit_enabled = ($post_info['forum_limit_edit_time'] && (intval($config['forum_limit_edit_time_interval']) > 0)) ? true : false;
	$is_spam_limit_edit_enabled = ((intval($config['spam_posts_number']) > 0) && ($user->data['user_posts'] < (int) $config['spam_posts_number']) && (intval($config['spam_post_edit_interval']) > 0)) ? true : false;
	if (($mode == 'editpost') && ($user->data['user_level'] != ADMIN) && !$is_auth['auth_mod'] && !$submit && ($is_global_limit_edit_enabled || $is_spam_limit_edit_enabled))
	{
		if (($is_global_limit_edit_enabled && (intval($config['forum_limit_edit_time_interval']) < ((time() - $post_info['post_time']) / 60))) || ($is_spam_limit_edit_enabled && (intval($config['spam_post_edit_interval']) < ((time() - $post_info['post_time']) / 60))))
		{
			$message = sprintf($lang['LIMIT_EDIT_TIME_WARN'], intval($config['forum_limit_edit_time_interval'])) . '<br /><br />' . sprintf($lang['Click_view_message'], '<a href="' . append_sid(CMS_PAGE_VIEWTOPIC . '?' . POST_POST_URL . '=' . $post_id) . '#' . $post_id . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_forum'], '<a href="' . append_sid(CMS_PAGE_VIEWFORUM . '?' . POST_FORUM_URL . '=' . $forum_id) . '">', '</a>');
			message_die(GENERAL_MESSAGE, $message);
		}
	}
	// LIMIT POST EDIT TIME - END

	if ($mode == 'editpost')
	{
		if ($is_auth['auth_mod'] || ($user->data['user_level'] == ADMIN))
		{
			$template->assign_block_vars('switch_lock_post', array());
			$template->assign_var('S_POST_LOCKED', $post_info['post_locked'] ? ' checked="checked"' : '');
		}
		elseif ($post_info['post_locked'])
		{
			message_die(GENERAL_MESSAGE, 'POST_LOCKED');
		}
	}

	if (($mode == 'editpost') || ($mode == 'delete') || ($mode == 'poll_delete'))
	{
		$topic_id = $post_info['topic_id'];
		$topic_id_append = (!empty($topic_id) ? (POST_TOPIC_URL . '=' . $topic_id) : '');
		// MG Cash MOD For IP - BEGIN
		if (!empty($config['plugins']['cash']['enabled']))
		{
			$post_data['post_text'] = (($mode == 'editpost') || ($mode == 'delete')) ? $post_info['post_text'] : '';
		}
		// MG Cash MOD For IP - END
		$post_data['poster_post'] = ($post_info['poster_id'] == $user->data['user_id']) ? true : false;
		$post_data['first_post'] = ($post_info['topic_first_post_id'] == $post_id) ? true : false;
		$post_data['last_post'] = ($post_info['topic_last_post_id'] == $post_id) ? true : false;
		$post_data['last_topic'] = ($post_info['forum_last_post_id'] == $post_id) ? true : false;
		$post_data['has_poll'] = (!empty($post_info['poll_start']) ? true : false);
		$post_data['poll_start'] = !empty($post_info['poll_start']) ? $post_info['poll_start'] : 0;
		// Event Registration - BEGIN
		$post_data['has_reg'] = ($post_info['topic_reg']) ? true : false;
		// Event Registration - END
		$post_data['topic_type'] = $post_info['topic_type'];
		$topic_show_portal = ($topic_show_portal || $post_info['topic_show_portal']) ? true : false;
		$post_data['topic_show_portal'] = $topic_show_portal;
		$post_data['topic_calendar_time'] = $post_info['topic_calendar_time'];
		$post_data['topic_calendar_duration'] = $post_info['topic_calendar_duration'];
		$post_data['poster_id'] = $post_info['poster_id'];
		$post_data['post_images'] = $post_info['post_images'];

		/**
		* @event posting.post_data.
		* @description Sets up the post_data from the post_info.
		* @since 3.0
		* @var array query The SQL query parts
		*/
		$vars = array(
			'post_data',
			'post_info',
		);
		extract($class_plugins->trigger('posting.post_data', compact($vars)));

		if (($config['allow_mods_edit_admin_posts'] == false) && ($post_info['user_level'] == ADMIN) && ($user->data['user_level'] != ADMIN))
		{
			message_die(GENERAL_ERROR, $lang['CannotEditAdminsPosts']);
		}

		if ($post_data['first_post'] && $post_data['has_poll'])
		{
			$sql = "SELECT *
				FROM " . POLL_OPTIONS_TABLE . " o
				WHERE o.topic_id = " . $topic_id . "
				ORDER BY o.poll_option_id";
			$result = $db->sql_query($sql);

			$poll_options = array();
			$poll_results_sum = 0;
			if ($row = $db->sql_fetchrow($result))
			{
				$poll_title = $post_info['poll_title'];
				$poll_start = $post_info['poll_start'];
				$poll_length = $post_info['poll_length'] / 86400;
				$poll_max_options = $post_info['poll_max_options'];
				$poll_change = $post_info['poll_change'];
				$poll_data = array(
					'title' => $poll_title,
					'start' => $poll_start,
					'length' => $poll_length,
					'max_options' => $poll_max_options,
					'change' => $poll_change
				);

				do
				{
					$poll_options[$row['poll_option_id']] = $row['poll_option_text'];
					$poll_results_sum += $row['poll_option_total'];
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
		if (($post_info['poster_id'] != $user->data['user_id']) && !$is_auth['auth_mod'])
		{
			$message = ($delete || ($mode == 'delete')) ? $lang['Delete_own_posts'] : $lang['Edit_own_posts'];
			$message .= '<br /><br />' . sprintf($lang['Click_return_topic'], '<a href="' . append_sid(CMS_PAGE_VIEWTOPIC . '?' . (!empty($forum_id_append) ? ($forum_id_append . '&amp;') : '') . $topic_id_append) . '">', '</a>');

			message_die(GENERAL_MESSAGE, $message);
		}
		elseif (!$post_data['last_post'] && !$is_auth['auth_mod'] && (($mode == 'delete') || $delete))
		{
			message_die(GENERAL_MESSAGE, $lang['Cannot_delete_replied']);
		}
		elseif (!$post_data['edit_poll'] && !$is_auth['auth_mod'] && (($mode == 'poll_delete') || $poll_delete))
		{
			message_die(GENERAL_MESSAGE, $lang['Cannot_delete_poll']);
		}

		// Event Registration - BEGIN
		if ($post_data['first_post'] && $post_data['has_reg'])
		{
			$sql = "SELECT *
				FROM " . REGISTRATION_DESC_TABLE . " rd
				WHERE rd.topic_id = $topic_id";
			$result = $db->sql_query($sql);

			if ($row = $db->sql_fetchrow($result))
			{
				$reg_active = ($row['reg_active'] == 1) ? 'checked="checked"' : '';
				$reg_max_option1 = (!empty($row['reg_max_option1'])) ? $row['reg_max_option1'] : '';
				$reg_max_option2 = (!empty($row['reg_max_option2'])) ? $row['reg_max_option2'] : '';
				$reg_max_option3 = (!empty($row['reg_max_option3'])) ? $row['reg_max_option3'] : '';

				$reg_length = (!empty($row['reg_length'])) ? ($row['reg_length']/86400) : '';
			}
			$db->sql_freeresult($result);
		}
		// Event Registration - END
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
		if (!empty($config['plugins']['cash']['enabled']))
		{
			$post_data['topic_poster'] = ($mode == 'reply') ? $post_info['topic_poster'] : 0;
		}
		// MG Cash MOD For IP - END
		$post_data['first_post'] = ($mode == 'newtopic') ? true : 0;
		$post_data['last_post'] = false;
		$post_data['has_poll'] = false;
		$post_data['poll_start'] = 0;
		$post_data['edit_poll'] = false;
	}

	if ($mode == 'poll_delete')
	{
		$meta = '';
		$message = '';
		if (!class_exists('class_mcp')) include(IP_ROOT_PATH . 'includes/class_mcp.' . PHP_EXT);
		if (empty($class_mcp)) $class_mcp = new class_mcp();
		$class_mcp->post_delete($mode, $post_data, $message, $meta, $forum_id, $topic_id, $post_id);

		$redirect_url = append_sid(CMS_PAGE_VIEWTOPIC . '?' . POST_TOPIC_URL . '=' . $topic_id);
		meta_refresh(3, $redirect_url);

		message_die(GENERAL_MESSAGE, $message);
	}

	// BEGIN cmx_slash_news_mod
	// If you want to allow moderators to change news category when editing post you can decomment this...
	//if($config['allow_news'] && $post_data['first_post'] && $is_auth['auth_post'] && ($is_auth['auth_news'] || ($is_auth['auth_mod'] && ($mode == 'editpost'))))
	if($config['allow_news'] && $post_data['first_post'] && $is_auth['auth_post'] && $is_auth['auth_news'])
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
		if($config['allow_news'] && $post_data['first_post'] && $is_auth['auth_post'] && !$is_auth['auth_news'] && ($mode == 'editpost'))
		{
			$post_data['news_id'] = $post_info['news_id'];
		}
		else
		{
			$post_data['news_id'] = 0;
		}
		$post_data['news_id'] = !empty($_POST['news_category']) ? intval($_POST['news_category']) : (!empty($post_data['news_id']) ? intval($post_data['news_id']) : 0);
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
	// Event Registration - BEGIN
	$reg_number_clicked = request_var('register', 0);
	$reg_user_id = request_var(POST_USERS_URL, 0);
	$reg_user_id = ($reg_user_id < 2) ? ANONYMOUS : $reg_user_id;
	// Event Registration - END
	if ($user->data['session_logged_in'])
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
		case 'reply':
		case 'topicreview':
			$redirect = 'mode=reply&' . (!empty($forum_id_append) ? ($forum_id_append . '&') : '') . $topic_id_append;
			break;
		case 'quote':
		case 'editpost':
			$redirect = 'mode=quote&' . (!empty($forum_id_append) ? ($forum_id_append . '&') : '') . (!empty($topic_id_append) ? ($topic_id_append . '&') : '') . $post_id_append;
			break;
		// Event Registration - BEGIN
		case 'register':
			$redirect = 'mode=register&register=' . $reg_number_clicked . '&' . POST_USERS_URL . '=' . $reg_user_id . '&' . (!empty($forum_id_append) ? ($forum_id_append . '&') : '') . $topic_id_append;
			break;
		// Event Registration - END
	}
	$redirect .= ($post_reportid) ? '&post_reportid=' . $post_reportid : '';
	redirect(append_sid(CMS_PAGE_LOGIN . '?redirect=posting.' . PHP_EXT . '?' . $redirect, true));
}
// Self AUTH - BEGIN
elseif (intval($is_auth[$is_auth_type]) == AUTH_SELF)
{
	//self auth mod
	switch($mode)
	{
		case 'quote':
		case 'reply':
			$sql = "SELECT t.topic_id
				FROM " . TOPICS_TABLE . " t, " . USERS_TABLE. " u
				WHERE t.topic_id = " . $topic_id . "
					AND t.topic_poster = u.user_id
					AND u.user_id = " . $user->data['user_id'];
			break;
	}
	$result = $db->sql_query($sql);
	$self_auth = $db->sql_fetchrow($result);
	if (empty($self_auth))
	{
		message_die(GENERAL_MESSAGE, sprintf($lang['Sorry_' . $is_auth_type], $is_auth[$is_auth_type . '_type']));
	}
}
// Self AUTH - END

// Set toggles for various options
if (!$config['allow_html'])
{
	$html_on = 0;
}
else
{
	$html_on = ($submit || $refresh) ? ((!empty($_POST['disable_html'])) ? 0 : 1) : (($user->data['user_id'] == ANONYMOUS) ? $config['allow_html'] : $user->data['user_allowhtml']);
}

$html_on = (!empty($_POST['disable_html']) ? 0 : ((($user->data['user_level'] == ADMIN) && $config['allow_html_only_for_admins']) ? 1 : $html_on));

$acro_auto_on = ($submit || $refresh) ? ((!empty($_POST['disable_acro_auto'])) ? 0 : 1) : 1;

if (!$config['allow_bbcode'])
{
	$bbcode_on = 0;
}
else
{
	$bbcode_on = ($submit || $refresh) ? ((!empty($_POST['disable_bbcode'])) ? 0 : 1) : (($user->data['user_id'] == ANONYMOUS) ? $config['allow_bbcode'] : $user->data['user_allowbbcode']);
}

if (!$config['allow_smilies'])
{
	$smilies_on = 0;
}
else
{
	$smilies_on = ($submit || $refresh) ? ((!empty($_POST['disable_smilies'])) ? 0 : 1) : (($user->data['user_id'] == ANONYMOUS) ? $config['allow_smilies'] : $user->data['user_allowsmile']);
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
	if (($mode != 'newtopic') && $user->data['session_logged_in'] && $is_auth['auth_read'])
	{
		$sql = "SELECT topic_id
			FROM " . TOPICS_WATCH_TABLE . "
			WHERE topic_id = " . $topic_id . "
				AND user_id = " . $user->data['user_id'];
		$result = $db->sql_query($sql);
		$notify_user = ($db->sql_fetchrow($result)) ? true : $user->data['user_notify'];
		$db->sql_freeresult($result);
	}
	else
	{
		$notify_user = ($user->data['session_logged_in'] && $is_auth['auth_read']) ? $user->data['user_notify'] : 0;
	}
}

$attach_sig = ($submit || $refresh) ? ((!empty($_POST['attach_sig'])) ? 1 : 0) : (($user->data['user_id'] == ANONYMOUS) ? 0 : $user->data['user_attachsig']);
$setbm = ($submit || $refresh) ? ((!empty($_POST['setbm'])) ? 1 : 0) : (($user->data['user_id'] == ANONYMOUS) ? 0 : $user->data['user_setbm']);
execute_posting_attachment_handling();

// What shall we do?

// BEGIN cmx_slash_news_mod
// Get News Categories.
if($user->data['session_logged_in'] && $post_data['disp_news'])
{
	if (($mode == 'editpost') && empty($post_id))
	{
		message_die(GENERAL_MESSAGE, $lang['No_post_id']);
	}

	$sql = 'SELECT * FROM ' . NEWS_TABLE . ' ORDER BY news_category';
	$result = $db->sql_query($sql, 0, 'news_cats_');
	$news_sel = array();
	$news_cat = array();
	while ($row = $db->sql_fetchrow($result))
	{
		if((($news_category > 0) && ($news_category == $row['news_id'])) || (($post_data['news_id'] > 0) && ($post_data['news_id'] == $row['news_id'])))
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

	if(sizeof($news_cat) > 0)
	{
		for($i = 0; $i < sizeof($news_cat); $i++)
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
	$s_hidden_fields .= '<input type="hidden" name="sid" value="' . $user->data['session_id'] . '" />';

	$l_confirm = ($delete || ($mode == 'delete')) ? $lang['Confirm_delete'] : $lang['Confirm_delete_poll'];

	$template->assign_vars(array(
		'MESSAGE_TITLE' => $lang['Information'],
		'MESSAGE_TEXT' => $l_confirm,

		'L_YES' => $lang['Yes'],
		'L_NO' => $lang['No'],

		'S_CONFIRM_ACTION' => append_sid('posting.' . PHP_EXT),
		'S_HIDDEN_FIELDS' => $s_hidden_fields
		)
	);
	full_page_generation('confirm_body.tpl', $lang['Confirm'], '', '');
}
elseif ($mode == 'vote')
{
	// Vote in a poll
	$voted_id = request_var('vote_id', array('' => 0));
	$voted_id = (sizeof($voted_id) > 1) ? array_unique($voted_id) : $voted_id;

	// Does this topic contain a poll?
	if (!empty($post_info['poll_start']))
	{
		$sql = "SELECT o.*
			FROM " . POLL_OPTIONS_TABLE . " o
			WHERE o.topic_id = " . $topic_id . "
			ORDER BY o.poll_option_id";
		$result = $db->sql_query($sql);

		$poll_info = array();
		while ($row = $db->sql_fetchrow($result))
		{
			$poll_info[] = $row;
		}
		$db->sql_freeresult($result);

		$cur_voted_id = array();
		if ($user->data['session_logged_in'] && ($user->data['bot_id'] === false))
		{
			$sql = "SELECT poll_option_id
				FROM " . POLL_VOTES_TABLE . "
				WHERE topic_id = " . $topic_id . "
					AND vote_user_id = " . $user->data['user_id'];
			$result = $db->sql_query($sql);

			while ($row = $db->sql_fetchrow($result))
			{
				$cur_voted_id[] = $row['poll_option_id'];
			}
			$db->sql_freeresult($result);
		}
		else
		{
			// Currently disable guests posting...
			$message = $lang['POLL_NO_GUESTS'] . '<br /><br />' . sprintf($lang['Click_return_topic'], '<a href="' . $redirect_url . '">', '</a>');
			message_die(GENERAL_MESSAGE, $message);

			// Cookie based guest tracking... I don't like this but hum ho... it's oft requested. This relies on "nice" users who don't feel the need to delete cookies to mess with results.
			if (isset($_COOKIE[$config['cookie_name'] . '_poll_' . $topic_id]))
			{
				$cur_voted_id = explode(',', $_COOKIE[$config['cookie_name'] . '_poll_' . $topic_id]);
				$cur_voted_id = array_map('intval', $cur_voted_id);
			}
		}

		if (!sizeof($voted_id) || (sizeof($voted_id) > $post_info['poll_max_options']) || in_array(VOTE_CONVERTED, $cur_voted_id))
		{
			if (!sizeof($voted_id))
			{
				$message = $lang['NO_VOTE_OPTION'];
			}
			elseif (sizeof($voted_id) > $post_info['poll_max_options'])
			{
				$message = $lang['TOO_MANY_VOTE_OPTIONS'];
			}
			elseif (in_array(VOTE_CONVERTED, $cur_voted_id))
			{
				$message = $lang['VOTE_CONVERTED'];
			}
			else
			{
				$message = $lang['FORM_INVALID'];
			}

			$redirect_url = append_sid(CMS_PAGE_VIEWTOPIC . '?' . (!empty($forum_id_append) ? ($forum_id_append . '&amp;') : '') . $topic_id_append . '&amp;start=' . $start);
			meta_refresh(3, $redirect_url);

			$message .= '<br /><br />' . sprintf($lang['Click_return_topic'], '<a href="' . $redirect_url . '">', '</a>');
			message_die(GENERAL_MESSAGE, $message);
		}

		foreach ($voted_id as $option)
		{
			if (in_array($option, $cur_voted_id))
			{
				continue;
			}

			$sql = "UPDATE " . POLL_OPTIONS_TABLE . "
				SET poll_option_total = poll_option_total + 1
				WHERE poll_option_id = " . (int) $option . "
					AND topic_id = " . (int) $topic_id;
			$db->sql_query($sql);

			if ($user->data['session_logged_in'] && ($user->data['bot_id'] === false))
			{
				$sql_ary = array(
					'topic_id' => (int) $topic_id,
					'poll_option_id' => (int) $option,
					'vote_user_id' => (int) $user->data['user_id'],
					'vote_user_ip' => (string) $user->data['session_ip'],
				);

				$sql = "INSERT INTO " . POLL_VOTES_TABLE . " " . $db->sql_build_array('INSERT', $sql_ary);
				$db->sql_query($sql);
			}
		}

		foreach ($cur_voted_id as $option)
		{
			if (!in_array($option, $voted_id))
			{
				$sql = "UPDATE " . POLL_OPTIONS_TABLE . "
					SET poll_option_total = poll_option_total - 1
					WHERE poll_option_id = " . (int) $option . "
						AND topic_id = " . (int) $topic_id;
				$db->sql_query($sql);

				if ($user->data['session_logged_in'] && ($user->data['bot_id'] === false))
				{
					$sql = "DELETE FROM " . POLL_VOTES_TABLE . "
						WHERE topic_id = " . (int) $topic_id . "
							AND poll_option_id = " . (int) $option . "
							AND vote_user_id = " . (int) $user->data['user_id'];
					$db->sql_query($sql);
				}
			}
		}

		if ($user->data['session_logged_in'] && ($user->data['bot_id'] === false))
		{
			if (function_exists('set_cookie'))
			{
				set_cookie('poll_' . $topic_id, implode(',', $voted_id), time() + 31536000);
			}
		}

		$sql = "UPDATE " . TOPICS_TABLE . "
			SET poll_last_vote = " . time() . "
			WHERE topic_id = " . $topic_id;
		$db->sql_query($sql);

		$redirect_url = append_sid(CMS_PAGE_VIEWTOPIC . '?' . (!empty($forum_id_append) ? ($forum_id_append . '&amp;') : '') . $topic_id_append . '&amp;start=' . $start);
		meta_refresh(3, $redirect_url);

		$message = $lang['VOTE_SUBMITTED'] . '<br /><br />' . sprintf($lang['Click_return_topic'], '<a href="' . $redirect_url . '">', '</a>');
		message_die(GENERAL_MESSAGE, $message);
	}
	else
	{
		redirect(append_sid(CMS_PAGE_VIEWTOPIC . '?' . (!empty($forum_id_append) ? ($forum_id_append . '&') : '') . $topic_id_append, true));
	}
}
// Event Registration - BEGIN
elseif ($mode == 'register')
{
	// Register for an event
	$register_value = request_var('register', 0);
	$register_value = in_array($register_value, array(REG_OPTION1, REG_OPTION2, REG_OPTION3, REG_UNREGISTER)) ? $register_value : 0;
	if (!empty($register_value))
	{
		$user_id = $user->data['user_id'];
		if ($user->data['user_level'] == ADMIN)
		{
			$target_user_id = request_var(POST_USERS_URL, 0);
			$target_user_id = ($target_user_id < 2) ? ANONYMOUS : $target_user_id;
			$target_username = request_var('username', '', true);
			if (!empty($target_user_id) && ($target_user_id != ANONYMOUS))
			{
				$target_userdata = get_userdata($target_user_id);
			}
			else
			{
				$target_userdata = get_userdata($target_username, true);
			}
			if (!empty($target_userdata))
			{
				$user_id = $target_userdata['user_id'];
			}
		}
		$zeit = time();

		$sql = "SELECT registration_status FROM " . REGISTRATION_TABLE . "
						WHERE topic_id = $topic_id AND registration_user_id = $user_id";
		$result = $db->sql_query($sql);

		if ($reg_info = $db->sql_fetchrow($result))
		{
			if ($register_value == REG_UNREGISTER) // cancel registration
			{
				$sql = "DELETE FROM " . REGISTRATION_TABLE . "
				WHERE topic_id = $topic_id
					AND registration_user_id = $user_id";
				$db->sql_query($sql);
				$message = $lang['Reg_Unregister'];
			}
			else
			{
				$old_regstate = $reg_info['registration_status'];

				if (($user->data['user_level'] != ADMIN) && (check_max_registration($topic_id, $register_value) === false))
				{
					$message = $lang['Reg_Max_Registrations'];
				}
				else
				{
					$sql = "UPDATE " . REGISTRATION_TABLE . "
						SET registration_user_ip = '$user_ip', registration_time = $zeit, registration_status = $register_value
						WHERE topic_id = $topic_id
							AND registration_user_id = $user_id";
					$db->sql_query($sql);
					$message = $lang['Reg_Change'];
				}
			}
		}
		else
		{
			if (($user->data['user_level'] != ADMIN) && (check_max_registration($topic_id, $register_value) === false))
			{
				$message = sprintf($lang['Reg_Max_Registrations'], $num_max_reg);
			}
			else
			{
				$sql = "INSERT INTO " . REGISTRATION_TABLE . " (topic_id, registration_user_id, registration_user_ip, registration_time, registration_status)
					VALUES ($topic_id, $user_id, '$user_ip', $zeit, $register_value)";
				$db->sql_query($sql);
				$message = $lang['Reg_Insert'];
			}
		}

		$redirect_url = append_sid(CMS_PAGE_VIEWTOPIC . '?' . (!empty($forum_id_append) ? ($forum_id_append . '&amp;') : '') . $topic_id_append);
		meta_refresh(3, $redirect_url);

		$message .= '<br /><br />' . sprintf($lang['Click_return_topic'], '<a href="' . $redirect_url . '">', '</a>');
		message_die(GENERAL_MESSAGE, $message);
	}
	else
	{
		message_die(GENERAL_ERROR, 'Missing information for registration', '', __LINE__, __FILE__);
	}
}
// Event Registration - END
elseif ($submit || $confirm || ($draft && $draft_confirm))
{
	// Submit post/vote (newtopic, edit, reply, etc.)
	$return_message = '';
	$return_meta = '';
	// session id check
	if (($sid == '') || ($sid != $user->data['session_id']))
	{
		$error_msg .= (!empty($error_msg)) ? '<br />' . $lang['Session_invalid'] : $lang['Session_invalid'];
	}

	switch ($mode)
	{
		case 'editpost':
		case 'newtopic':
		case 'reply':
			// CrackerTracker v5.x
			if (($config['ctracker_vconfirm_guest'] == 1) && !$user->data['session_logged_in'])
			{
				define('CRACKER_TRACKER_VCONFIRM', true);
				define('POST_CONFIRM_CHECK', true);
				include_once(IP_ROOT_PATH . 'includes/ctracker/engines/ct_visual_confirm.' . PHP_EXT);
			}
			// CrackerTracker v5.x

			$username = htmlspecialchars_decode(request_post_var('username', '', true), ENT_COMPAT);
			$subject = !empty($draft_subject) ? $draft_subject : request_post_var('subject', '', true);
			$topic_desc = request_post_var('topic_desc', '', true);
			$message = !empty($draft_message) ? $draft_message : htmlspecialchars_decode(request_post_var('message', '', true), ENT_COMPAT);
			$notes = htmlspecialchars_decode(request_post_var('notes', '', true), ENT_COMPAT);
			$notes_mod = '';
			if (($user->data['user_level'] == ADMIN) || $is_auth['auth_mod'])
			{
				$notes_mod = htmlspecialchars_decode(request_post_var('notes_mod', '', true), ENT_COMPAT);
			}
			$post_images = request_post_var('post_images', '', true);
			if (!empty($post_images) && (substr($post_images, 0, 4) == 'http'))
			{
				if (!function_exists('get_full_image_info'))
				{
					require(IP_ROOT_PATH . 'includes/class_image.' . PHP_EXT);
				}
				$pic_size = get_full_image_info($post_images);
				if(empty($pic_size))
				{
					$post_images = '';
				}
			}
			else
			{
				$post_images = '';
			}
			$post_data['post_images'] = $post_images;

			$poll_title = (isset($_POST['poll_title']) && $is_auth['auth_pollcreate']) ? request_post_var('poll_title', '', true) : '';
			$poll_options = (isset($_POST['poll_option_text']) && $is_auth['auth_pollcreate']) ? request_post_var('poll_option_text', array(0 => ''), true) : array();
			$poll_start = time();
			$poll_length = (isset($_POST['poll_length']) && $is_auth['auth_pollcreate']) ? request_post_var('poll_length', 0) : 0;
			$poll_length = max(0, $poll_length * 86400);
			$poll_max_options = (isset($_POST['poll_max_options']) && $is_auth['auth_pollcreate']) ? request_post_var('poll_max_options', 1) : 1;
			$poll_max_options = max(1, $poll_max_options);
			$poll_change = (isset($_POST['poll_change']) && $is_auth['auth_pollcreate']) ? 1 : 0;
			$poll_data = array(
				'title' => $poll_title,
				'start' => $poll_start,
				'length' => $poll_length,
				'max_options' => $poll_max_options,
				'change' => $poll_change
			);

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

			// Event Registration - BEGIN
			$reg_active = (isset($_POST['start_registration']) && $is_auth['auth_vote'] && $user->data['session_logged_in']) ? $_POST['start_registration'] : '';
			$reg_reset = (isset($_POST['reset_registration']) && $is_auth['auth_vote'] && $user->data['session_logged_in']) ? $_POST['reset_registration'] : '';
			$reg_max_option1 = (!empty($_POST['reg_max_option1']) && $is_auth['auth_vote'] && $user->data['session_logged_in']) ? $_POST['reg_max_option1'] : '';
			$reg_max_option2 = (!empty($_POST['reg_max_option2']) && $is_auth['auth_vote'] && $user->data['session_logged_in']) ? $_POST['reg_max_option2'] : '';
			$reg_max_option3 = (!empty($_POST['reg_max_option3']) && $is_auth['auth_vote'] && $user->data['session_logged_in']) ? $_POST['reg_max_option3'] : '';
			$reg_length = (isset($_POST['reg_length']) && $is_auth['auth_vote'] && $user->data['session_logged_in']) ? $_POST['reg_length'] : '';
			// Event Registration - END

			prepare_post($mode, $post_data, $bbcode_on, $html_on, $smilies_on, $error_msg, $username, $subject, $message, $poll_title, $poll_options, $poll_data, $reg_active, $reg_reset, $reg_max_option1, $reg_max_option2, $reg_max_option3, $reg_length, $topic_desc, $topic_calendar_time, $topic_calendar_duration);

			// MG Drafts - BEGIN
			if (($config['allow_drafts'] == true) && $draft && $draft_confirm && $user->data['session_logged_in'] && (($mode == 'reply') || ($mode == 'newtopic')))
			{
				save_draft($draft_id, $user->data['user_id'], $forum_id, $topic_id, strip_tags($subject), $message);
				//save_draft($draft_id, $user->data['user_id'], $forum_id, $topic_id, $db->sql_escape(strip_tags($subject)), $db->sql_escape($message));
				$output_message = $lang['Drafts_Saved'];
				$output_message .= '<br /><br />' . sprintf($lang['Click_return_drafts'], '<a href="' . append_sid(CMS_PAGE_DRAFTS) . '">', '</a>');
				$output_message .= '<br /><br />' . sprintf($lang['Click_return_forum'], '<a href="' . append_sid(CMS_PAGE_VIEWFORUM . '?' . POST_FORUM_URL . '=' . $forum_id) . '">', '</a>');

				$redirect_url = append_sid(CMS_PAGE_VIEWFORUM . '?' . POST_FORUM_URL . '=' . $forum_id);
				meta_refresh(3, $redirect_url);

				message_die(GENERAL_MESSAGE, $output_message);
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

				if(($mode == 'editpost') && $config['edit_notes'] && ((strlen($notes) > 2) || (strlen($notes_mod) > 2)))
				{
					$sql = "SELECT edit_notes FROM " . POSTS_TABLE . " WHERE post_id='" . $post_id . "'";
					$result = $db->sql_query($sql);
					$row = $db->sql_fetchrow($result);
					$db->sql_freeresult($result);
					$notes_list = strlen($row['edit_notes']) ? unserialize($row['edit_notes']) : array();

					// Check limit and eventually delete notes
					if(!empty($notes) && (sizeof($notes_list) >= intval($config['edit_notes_n'])))
					{
						$first_edit_note = 0;
						$edit_notes_counter = 0;
						for($i = 0; $i < sizeof($notes_list); $i++)
						{
							if (empty($notes_list[$i]['reserved']))
							{
								$edit_notes_counter++;
								if (empty($first_edit_note))
								{
									$first_edit_note = $i;
								}
							}
						}

						if ($edit_notes_counter > intval($config['edit_notes_n']))
						{
							unset($notes_list[$first_edit_note]);
						}
					}

					if (!empty($notes))
					{
						$notes_list[] = array(
							'poster' => $user->data['user_id'],
							'time' => time(),
							//'text' => htmlspecialchars($notes)
							'text' => $notes,
							'reserved' => false
						);
					}

					if (!empty($notes_mod))
					{
						$notes_list[] = array(
							'poster' => $user->data['user_id'],
							'time' => time(),
							//'text' => htmlspecialchars($notes_mod)
							'text' => $notes_mod,
							'reserved' => true
						);
					}

					empty_cache_folders(POSTS_CACHE_FOLDER);
					$sql = "UPDATE " . POSTS_TABLE . " SET edit_notes = '" . $db->sql_escape(serialize($notes_list)) . "' WHERE post_id = '" . $post_id . "'";
					$db->sql_query($sql);

					if (!empty($notes))
					{
						$edit_count_sql = '';
						// We need this, otherwise editing for normal users will be accounted twice... because the same edit will be updated in functions_post.php
						if($user->data['user_level'] == ADMIN)
						{
							$edit_count_sql = ", post_edit_count = (post_edit_count + 1)";
						}
						$edited_sql = "post_edit_time = '" . time() . "'" . $edit_count_sql . ", post_edit_id = '" . $user->data['user_id'] . "'";
						$sql = "UPDATE " . POSTS_TABLE . " SET " . $edited_sql . " WHERE post_id='" . $post_id . "'";
						$db->sql_query($sql);
					}
				}

				if ($lock_subject)
				{
					$url = '[url="' . CMS_PAGE_VIEWTOPIC . '?' . (!empty($forum_id_append) ? ($forum_id_append . '&amp;') : '') . (!empty($topic_id_append) ? ($topic_id_append . '&amp;') : '') . POST_POST_URL . '=' . $lock_subject . '#p' . $lock_subject . '"]';
					$message = sprintf($lang['Link_to_post'], $url, '[/url]') . $message;
				}

				$topic_title_clean = '';
				$topic_tags = '';
				if ($post_data['first_post'])
				{
					$topic_title_clean = request_var('topic_title_clean', $subject, true);
					$topic_title_clean = substr(ip_clean_string($topic_title_clean, $lang['ENCODING']), 0, 254);

					@include_once(IP_ROOT_PATH . 'includes/class_topics_tags.' . PHP_EXT);
					$class_topics_tags = new class_topics_tags();
					if (!empty($use_jquery_tags))
					{
						if(array_key_exists('ttag', $_POST))
						{
							$all_topic_tags = request_var('ttag', array(0 => ''), true);
							$topic_tags = implode(', ', array_filter(array_unique($all_topic_tags)));
						}
					}
					else
					{
						$topic_tags = request_var('topic_tags', '', true);
					}
					if (!empty($topic_tags))
					{
						$topic_tags = trim($topic_tags);
						while(substr($topic_tags, -1) == ',')
						{
							$topic_tags = trim(substr($topic_tags, 0, -1));
						}
						$topic_tags_array = $class_topics_tags->create_tags_array($topic_tags);
						$topic_tags = implode(', ', array_filter(array_unique($topic_tags_array)));
						$topic_tags = substr($topic_tags, 0, 254);
						//die($topic_tags);
					}
					unset($class_topics_tags);
				}

				submit_post($mode, $post_data, $return_message, $return_meta, $forum_id, $topic_id, $post_id, $topic_type, $bbcode_on, $html_on, $acro_auto_on, $smilies_on, $attach_sig, $username, $subject, $topic_title_clean, $topic_tags, $message, $poll_title, $poll_options, $poll_data, $reg_active, $reg_reset, $reg_max_option1, $reg_max_option2, $reg_max_option3, $reg_length, $news_category, $topic_show_portal, $mark_edit, $topic_desc, $topic_calendar_time, $topic_calendar_duration, $extra_vars);
			}
			break;

		case 'delete':
		case 'poll_delete':
			if ($error_msg != '')
			{
				message_die(GENERAL_MESSAGE, $error_msg);
			}
			if (!class_exists('class_mcp')) include(IP_ROOT_PATH . 'includes/class_mcp.' . PHP_EXT);
			if (empty($class_mcp)) $class_mcp = new class_mcp();
			$class_mcp->post_delete($mode, $post_data, $return_message, $return_meta, $forum_id, $topic_id, $post_id);
			break;
	}

	if ($error_msg == '')
	{
		if ($mode != 'editpost')
		{
			$user_id = (($mode == 'reply') || ($mode == 'newtopic')) ? $user->data['user_id'] : $post_data['poster_id'];
			if (!class_exists('class_mcp')) include(IP_ROOT_PATH . 'includes/class_mcp.' . PHP_EXT);
			if (empty($class_mcp)) $class_mcp = new class_mcp();
			$class_mcp->sync_post_stats($mode, $post_data, $forum_id, $topic_id, $post_id, $user_id);
		}
		$attachment_mod['posting']->insert_attachment($post_id);

		if (($error_msg == '') && ($mode != 'poll_delete'))
		{
			// Forum Notification - BEGIN
			if (!class_exists('class_notifications'))
			{
				include(IP_ROOT_PATH . 'includes/class_notifications.' . PHP_EXT);
				$class_notifications = new class_notifications();
			}
			$post_data['subject'] = $subject;
			$post_data['username'] = ($user->data['user_id'] == ANONYMOUS) ? $username : $user->data['username'];
			$post_data['message'] = $message;
			if ($post_data['first_post'])
			{
				// fetch topic title
				$sql = "SELECT topic_title, topic_id
					FROM " . TOPICS_TABLE . "
					WHERE topic_id = " . $topic_id;
				$result = $db->sql_query($sql);

				if ($topic_info = $db->sql_fetchrow($result))
				{
					$class_notifications->send_notifications('newtopic', $post_data, $topic_info['topic_title'], $forum_id, $topic_id, $post_id, $notify_user);
				}

			}
			else
			{
				if ($setbm)
				{
					set_bookmark($topic_id);
				}
				$class_notifications->send_notifications($mode, $post_data, $post_info['topic_title'], $forum_id, $topic_id, $post_id, $notify_user);
			}
			// Forum Notification - END
		}

		if ($lock_subject)
		{
			$url = '<a href="' . append_sid(CMS_PAGE_VIEWTOPIC . '?' . (!empty($forum_id_append) ? ($forum_id_append . '&amp;') : '') . (!empty($topic_id_append) ? ($topic_id_append . '&amp;') : '') . POST_POST_URL . '=' . $lock_subject . '#p' . $lock_subject) . '">';
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
			$result = $db->sql_query($sql);
		}
		if (($mode == 'newtopic') || ($mode == 'reply'))
		{
			$tracking_forums = (!empty($_COOKIE[$config['cookie_name'] . '_f'])) ? unserialize($_COOKIE[$config['cookie_name'] . '_f']) : array();
			$tracking_topics = (!empty($_COOKIE[$config['cookie_name'] . '_t'])) ? unserialize($_COOKIE[$config['cookie_name'] . '_t']) : array();

			if (((sizeof($tracking_topics) + sizeof($tracking_forums)) >= 150) && empty($tracking_topics[$topic_id]))
			{
				asort($tracking_topics);
				unset($tracking_topics[key($tracking_topics)]);
			}

			$tracking_topics[$topic_id] = time();

			$user->set_cookie('t', serialize($tracking_topics), $user->cookie_expire);
		}

		// MOD: Redirect to Post (normal post) - BEGIN
		if (($mode == 'delete') && $post_data['first_post'] && $post_data['last_post'])
		{
			// URL for redirection after deleting an entire topic
			$redirect = CMS_PAGE_VIEWFORUM . '?' . POST_FORUM_URL . '=' . $forum_id;
			// If the above URL points to a location outside the phpBB directories
			// move the slashes on the next line to the start of the following line:
			//redirect(append_sid($redirect, true), true);
			redirect(append_sid($redirect, true));
		}
		elseif ($mode == 'delete')
		{
			// URL for redirection after deleting a post
			$redirect = CMS_PAGE_VIEWTOPIC . '?' . (!empty($forum_id_append) ? ($forum_id_append . '&') : '') . $topic_id_append;
			if (($config['url_rw'] == '1') || (($config['url_rw_guests'] == '1') && ($user->data['user_id'] == ANONYMOUS)))
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
			$redirect = CMS_PAGE_VIEWTOPIC . '?' . (!empty($forum_id_append) ? ($forum_id_append . '&') : '') . (!empty($topic_id_append) ? ($topic_id_append . '&') : '') . POST_POST_URL . '=' . $post_id;
			$post_append = '#p' . $post_id;
			if (($config['url_rw'] == '1') || (($config['url_rw_guests'] == '1') && ($user->data['user_id'] == ANONYMOUS)))
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
	$username = htmlspecialchars_decode(request_post_var('username', '', true), ENT_COMPAT);
	$subject = !empty($draft_subject) ? $draft_subject : request_post_var('subject', '', true);
	$topic_desc = request_post_var('topic_desc', '', true);
	// Mighty Gorgon: still under testing... if we are refreshing the page, it means that we need to keep the original message in the TEXTBOX, so we don't need to escape htmlspecialchars again...
	//$message = !empty($draft_message) ? $draft_message : htmlspecialchars_decode(request_post_var('message', '', true), ENT_COMPAT);
	$message = !empty($draft_message) ? $draft_message : request_post_var('message', '', true);
	$notes = htmlspecialchars_decode(request_post_var('notes', '', true), ENT_COMPAT);
	$notes_mod = '';
	if (($user->data['user_level'] == ADMIN) || $is_auth['auth_mod'])
	{
		$notes_mod = htmlspecialchars_decode(request_post_var('notes_mod', '', true), ENT_COMPAT);
	}

	$topic_title_clean = (empty($_POST['topic_title_clean']) ? $subject : request_post_var('topic_title_clean', '', true));
	$topic_title_clean = substr(ip_clean_string($topic_title_clean, $lang['ENCODING']), 0, 254);

	@include_once(IP_ROOT_PATH . 'includes/class_topics_tags.' . PHP_EXT);
	$class_topics_tags = new class_topics_tags();
	if (!empty($use_jquery_tags))
	{
		if(array_key_exists('ttag', $_POST))
		{
			$all_topic_tags = request_var('ttag', array(0 => ''), true);
			$topic_tags = implode(', ', array_filter(array_unique($all_topic_tags)));
		}
	}
	else
	{
		$topic_tags = request_var('topic_tags', '', true);
	}
	if (!empty($topic_tags))
	{
		$topic_tags = trim($topic_tags);
		while(substr($topic_tags, -1) == ',')
		{
			$topic_tags = trim(substr($topic_tags, 0, -1));
		}
		$topic_tags_array = $class_topics_tags->create_tags_array($topic_tags);
		$topic_tags = implode(', ', array_filter(array_unique($topic_tags_array)));
		$topic_tags = substr($topic_tags, 0, 254);
		//die($topic_tags);
	}
	unset($class_topics_tags);

	$poll_title = (!empty($_POST['poll_title'])) ? request_post_var('poll_title', '', true) : '';
	$poll_start = time();
	$poll_length = (isset($_POST['poll_length'])) ? request_post_var('poll_length', 0) : 0;
	$poll_length = max(0, $poll_length * 86400);
	$poll_max_options = (isset($_POST['poll_max_options'])) ? request_post_var('poll_max_options', 1) : 1;
	$poll_max_options = max(1, $poll_max_options);
	$poll_change = (isset($_POST['poll_change'])) ? 1 : 0;
	$poll_data = array(
		'title' => $poll_title,
		'start' => $poll_start,
		'length' => $poll_length,
		'max_options' => $poll_max_options,
		'change' => $poll_change
	);

	$poll_options = request_post_var('poll_option_text', array(0 => ''), true);
	if (!empty($poll_options))
	{
		@reset($poll_options);
		while(list($option_id, $option_text) = @each($poll_options))
		{
			if(isset($_POST['del_poll_option'][$option_id]))
			{
				unset($poll_options[$option_id]);
			}
			elseif (!empty($option_text))
			{
				$poll_options[$option_id] = $option_text;
			}
		}
	}

	if (!empty($poll_add) && !empty($_POST['add_poll_option_text']))
	{
		$poll_options[] = request_post_var('add_poll_option_text', '', true);
	}

	// Event Registration - BEGIN
	$reg_active = (isset($_POST['start_registration']) && $is_auth['auth_vote'] && $user->data['session_logged_in']) ? 'checked="checked"' : '';
	$reg_reset = (isset($_POST['reset_registration']) && $is_auth['auth_vote'] && $user->data['session_logged_in']) ? 'checked="checked"' : '';
	$reg_max_option1 = (!empty($_POST['reg_max_option1']) && $is_auth['auth_vote'] && $user->data['session_logged_in']) ? max(0, $_POST['reg_max_option1']) : '';
	$reg_max_option2 = (!empty($_POST['reg_max_option2']) && $is_auth['auth_vote'] && $user->data['session_logged_in']) ? max(0, $_POST['reg_max_option2']) : '';
	$reg_max_option3 = (!empty($_POST['reg_max_option3']) && $is_auth['auth_vote'] && $user->data['session_logged_in']) ? max(0, $_POST['reg_max_option3']) : '';
	$reg_length = (isset($_POST['reg_length']) && $is_auth['auth_vote'] && $user->data['session_logged_in']) ? max(0, $_POST['reg_length']) : '';
	// Event Registration - END

	if (($mode == 'newtopic') || ($mode == 'reply'))
	{
		$user_sig = (($user->data['user_sig'] != '') && $config['allow_sig']) ? $user->data['user_sig'] : '';
	}
	elseif ($mode == 'editpost')
	{
		$user_sig = (($post_info['user_sig'] != '') && $config['allow_sig']) ? $post_info['user_sig'] : '';
	}

	if(!empty($preview))
	{
		$preview_subject = $subject;
		//$preview_message = prepare_message(unprepare_message($message), $html_on, $bbcode_on, $smilies_on);
		// Mighty Gorgon: this line has been commented out because of some issues it could generate with previews... bbcode should be able to parse everything properly
		//$preview_message = htmlspecialchars($message);
		$preview_message = $message;
		$preview_username = $username;

		// Finalise processing as per viewtopic
		if(!$html_on)
		{
			if(($user_sig != '') || !$user->data['user_allowhtml'])
			{
				$user_sig = preg_replace('#(<)([\/]?.*?)(>)#is', '&lt;\2&gt;', $user_sig);
			}
		}

		$preview_username = censor_text($preview_username);
		$preview_subject = censor_text($preview_subject);
		$preview_message = censor_text($preview_message);
		$user_sig = censor_text($user_sig);

		if(($attach_sig) && ($user_sig != ''))
		{
			$bbcode->allow_html = ($user->data['user_allowhtml'] && $config['allow_html']) ? true : false;
			$bbcode->allow_bbcode = ($user->data['user_allowbbcode'] && $config['allow_bbcode']) ? true : false;
			$bbcode->allow_smilies = ($user->data['user_allowsmile'] && $config['allow_smilies']) ? true : false;
			$bbcode->is_sig = true;
			$user_sig = $bbcode->parse($user_sig);
			$bbcode->is_sig = false;
			$user_sig = str_replace('&amp;', '&', $user_sig);
		}

		$bbcode->allow_html = $html_on;
		$bbcode->allow_bbcode = $bbcode_on;
		$bbcode->allow_smilies = $smilies_on;
		$preview_message = $bbcode->parse($preview_message);
		$preview_message = (($bbcode_on == false) && ($html_on == false)) ? str_replace("\n", '<br />', preg_replace("/\r\n/", "\n", $preview_message)) : $preview_message;
		// Start Autolinks For phpBB Mod
		if($acro_auto_on == true)
		{
			$preview_message = $bbcode->acronym_pass($preview_message);
			$preview_message = $bbcode->autolink_text($preview_message, '999999');
		}
		//$preview_message = kb_word_wrap_pass($preview_message);
		// End Autolinks For phpBB Mod
		if($attach_sig && ($user_sig != ''))
		{
			$user_sig = '<br />' . $config['sig_line'] . '<br />' . $user_sig;
		}

		//$preview_message = str_replace("\n", '<br />', $preview_message);
		$url = '[url="' . CMS_PAGE_VIEWTOPIC . '?' . (!empty($forum_id_append) ? ($forum_id_append . '&') : '') . (!empty($topic_id_append) ? ($topic_id_append . '&') : '') . POST_POST_URL . '=' . $lock_subject . '#p' . $lock_subject . '"]';
		$extra_message_body = sprintf($lang['Link_to_post'], $url, '[/url]') . $message;
		$preview_message = ($lock_subject) ? ($extra_message_body . $preview_message) : $preview_message;

		$template->set_filenames(array('preview' => 'posting_preview.tpl'));
		if (!empty($topic_calendar_time))
		{
			$topic_calendar_duration_preview = $topic_calendar_duration - 1;
			if ($topic_calendar_duration_preview < 0)
			{
				$topic_calendar_duration_preview = 0;
			}
			$preview_subject .= get_calendar_title($topic_calendar_time, $topic_calendar_duration_preview);
		}
		$attachment_mod['posting']->preview_attachments();

		if (($mode == 'newtopic') || (($mode == 'editpost') && $post_data['first_post']))
		{
			$template->assign_var('S_POSTING_TOPIC', true);
		}

		//$preview_subject = strtr($preview_subject, array_flip(get_html_translation_table(HTML_ENTITIES)));
		$template->assign_vars(array(
			'TOPIC_TITLE' => $preview_subject,
			'POSTER_NAME' => $preview_username,
			'POST_DATE' => create_date_ip($config['default_dateformat'], time(), $config['board_timezone']),
			'USER_SIG' => ($attach_sig) ? $user_sig : '',

			'PREVIEW_SUBJECT' => $preview_subject,
			'PREVIEW_MESSAGE' => $preview_message,

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
	$postreport = request_var('postreport', 0);
	if ($postreport)
	{
		$sql = 'SELECT topic_id FROM ' . POSTS_TABLE . ' WHERE post_id = ' . $postreport;
		$result = $db->sql_query($sql);
		$post_details = $db->sql_fetchrow($result);
		$post_topic_id = $post_details['topic_id'];
		$sql = 'SELECT p.post_subject FROM ' . POSTS_TABLE . ' p WHERE p.topic_id = ' . $post_topic_id . ' ORDER BY p.post_time ASC LIMIT 1';
		$result = $db->sql_query($sql);
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
		$user_sig = ($user->data['user_sig'] != '') ? $user->data['user_sig'] : '';
		$message = '';
		// Start replacement - Yellow card MOD
		$username = ($user->data['session_logged_in']) ? $user->data['username'] : '';
		$poll_title = '';
		$poll_start = 0;
		$poll_length = 0;
		$poll_max_options = 1;
		$poll_change = 0;
		$poll_data = array(
			'title' => $poll_title,
			'start' => $poll_start,
			'length' => $poll_length,
			'max_options' => $poll_max_options,
			'change' => $poll_change
		);
		// End replacement - Yellow card MOD
	}
	elseif ($mode == 'reply')
	{
		$user_sig = ($user->data['user_sig'] != '') ? $user->data['user_sig'] : '';
		$username = ($user->data['session_logged_in']) ? $user->data['username'] : '';
		$subject = $lang['REPLY_PREFIX'] . $post_info['topic_title'];
		$message = '';
	}
	elseif (($mode == 'quote') || ($mode == 'editpost'))
	{
		$subject = ($post_data['first_post']) ? $post_info['topic_title'] : $post_info['post_subject'];
		$message = $post_info['post_text'];
		if ($mode == 'editpost')
		{
			$topic_desc = '';
			$topic_title_clean = '';
			$topic_tags = '';
			if ($post_data['first_post'])
			{
				$topic_desc = $post_info['topic_desc'];

				$topic_title_clean = (empty($post_info['topic_title_clean']) ? $subject : $post_info['topic_title_clean']);
				$topic_title_clean = substr(ip_clean_string($topic_title_clean, $lang['ENCODING']), 0, 254);

				@include_once(IP_ROOT_PATH . 'includes/class_topics_tags.' . PHP_EXT);
				$class_topics_tags = new class_topics_tags();
				$topic_tags = (empty($post_info['topic_tags']) ? '' : $post_info['topic_tags']);
				if (!empty($topic_tags))
				{
					$topic_tags_array = $class_topics_tags->create_tags_array($topic_tags);
					$topic_tags = implode(', ', array_filter(array_unique($topic_tags_array)));
					$topic_tags = substr($topic_tags, 0, 254);
				}
				unset($class_topics_tags);
			}

			$attach_sig = ($post_info['enable_sig'] && $post_info['user_sig'] != '') ? 1 : 0;
			$user_sig = $post_info['user_sig'];

			$topic_show_portal = ($post_info['topic_show_portal']) ? 1 : 0;
			$html_on = ($post_info['enable_html']) ? 1 : 0;
			$bbcode_on = ($post_info['enable_bbcode']) ? 1 : 0;
			$smilies_on = ($post_info['enable_smilies']) ? 1 : 0;
			$acro_auto_on = ($post_info['enable_autolinks_acronyms']) ? 1 : 0;
		}
		else
		{
			$attach_sig = ($user->data['user_attachsig']) ? 1 : 0;
			$user_sig = $user->data['user_sig'];
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
				$message = preg_replace($search, $replace, $message);
			}

			$msg_date = create_date_ip($config['default_dateformat'], $postrow['post_time'], $config['board_timezone']);

			// Use trim to get rid of spaces placed there by MS-SQL 2000
			$quote_username = (trim($post_info['post_username']) != '') ? $post_info['post_username'] : $post_info['username'];
			//$message = '[quote="' . $quote_username . '"]' . $message . '[/quote]';
			$message = '[quote user="' . $quote_username . '" post="' . $post_id . '"]' . $message . '[/quote]';

			$subject = censor_text($subject);
			$message = censor_text($message);

			$subject = (((strlen($subject) > 0) && ((substr($subject, 0, strlen($lang['REPLY_PREFIX'])) == $lang['REPLY_PREFIX']) || (substr($subject, 0, strlen($lang['REPLY_PREFIX']))) == $lang['REPLY_PREFIX_OLD'])) ? '' : $lang['REPLY_PREFIX']) . $subject;
			$mode = 'reply';
		}
		else
		{
			$username = ($post_info['user_id'] == ANONYMOUS && !empty($post_info['post_username'])) ? $post_info['post_username'] : '';
		}
	}
}

if(($mode == 'editpost') && $config['edit_notes'])
{
	$template->assign_vars(array(
		'S_EDIT_NOTES' => true,
		'L_EDIT_NOTES' => $lang['Edit_notes'],
		'NOTES' => htmlspecialchars($notes),
		'NOTES_MOD' => htmlspecialchars($notes_mod),
		)
	);
}

// Signature toggle selection
if($user_sig != '')
{
	$template->assign_block_vars('switch_signature_checkbox', array());
}

// HTML toggle selection
if ($config['allow_html'] || (($user->data['user_level'] == ADMIN) && $config['allow_html_only_for_admins']))
{
	$html_status = $lang['HTML_is_ON'];
	$template->assign_block_vars('switch_html_checkbox', array());
}
else
{
	$html_status = $lang['HTML_is_OFF'];
}

// BBCode toggle selection
if ($config['allow_bbcode'])
{
	$bbcode_status = $lang['BBCode_is_ON'];
	$template->assign_block_vars('switch_bbcode_checkbox', array());
}
else
{
	$bbcode_status = $lang['BBCode_is_OFF'];
}

// Smilies toggle selection
if ($config['allow_smilies'])
{
	$smilies_status = $lang['Smilies_are_ON'];
	$template->assign_block_vars('switch_smilies_checkbox', array());
}
else
{
	$smilies_status = $lang['Smilies_are_OFF'];
}

if(!$user->data['session_logged_in'] || (($mode == 'editpost') && $post_info['poster_id'] == ANONYMOUS))
{
	$template->assign_block_vars('switch_username_select', array());
}

// UPI2DB - BEGIN
if($user->data['upi2db_access'] && ($mode == 'editpost') && (($user->data['user_level'] == ADMIN) || ($user->data['user_level'] == MOD)))
{
	$template->assign_block_vars('switch_mark_edit_checkbox', array());
	$mark_edit = ($refresh) ? $mark_edit : true;
}
// UPI2DB - END

// Notify checkbox - only show if user is logged in
if ($user->data['session_logged_in'] && $is_auth['auth_read'])
{
	if ($mode != 'editpost' || ($mode == 'editpost' && $post_info['poster_id'] != ANONYMOUS))
	{
		$template->assign_block_vars('switch_notify_checkbox', array());
	}
}

// Bookmark checkbox - only show if user is logged in and not editing a post
if ($user->data['session_logged_in'])
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
	if ($is_auth['auth_sticky'])
	{
		$topic_type_toggle .= '<input type="radio" name="topictype" value="' . POST_STICKY . '"';
		if ($post_data['topic_type'] == POST_STICKY || $topic_type == POST_STICKY)
		{
			$topic_type_toggle .= ' checked="checked"';
		}
		$topic_type_toggle .= ' /> ' . $lang['Post_Sticky'] . '&nbsp;&nbsp;';
	}

	if ($is_auth['auth_announce'])
	{
		$topic_type_toggle .= '<input type="radio" name="topictype" value="' . POST_ANNOUNCE . '"';
		if ($post_data['topic_type'] == POST_ANNOUNCE || $topic_type == POST_ANNOUNCE)
		{
			$topic_type_toggle .= ' checked="checked"';
		}
		$topic_type_toggle .= ' /> ' . $lang['Post_Announcement'] . '&nbsp;&nbsp;';
	}

	if ($is_auth['auth_globalannounce'])
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

		/**
		* @event posting.after_topic_type_toggle.
		* @description Allows to change the topic type toggle HTML.
		* @since 3.0
		* @var string topic_type_toggle The fully-built topic type toggle.
		* @var string mode The current mode.
		* @var array post_data The post data.
		*/
		$vars = array(
			'topic_type_toggle',
			'mode',
			'post_data'
		);
		extract($class_plugins->trigger('posting.after_topic_type_toggle', compact($vars)));

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
			$year = intval(gmdate('Y', $topic_calendar_time));
			$month = intval(gmdate('m', $topic_calendar_time));
			$day = intval(gmdate('d', $topic_calendar_time));
			$hour = intval(gmdate('H', $topic_calendar_time));
			$min = intval(gmdate('i', $topic_calendar_time));
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

		$start_year = ((intval($year) > 1971) && (intval($year) <= gmdate('Y'))) ? intval($year) - 1 : gmdate('Y') - 1;
		for ($i = $start_year; $i <= gmdate('Y') + 10; $i++)
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
$hidden_form_fields .= '<input type="hidden" name="sid" value="' . $user->data['session_id'] . '" />';
$hidden_form_fields .= ($lock_subject) ? '<input type="hidden" name="lock_subject" value="' . $lock_subject . '" />' : '';

switch($mode)
{
	case 'newtopic':
		$meta_content['page_title'] = $lang['Post_a_new_topic'];
		$hidden_form_fields .= '<input type="hidden" name="' . POST_FORUM_URL . '" value="' . $forum_id . '" />';
		break;
	case 'reply':
		$meta_content['page_title'] = $lang['Post_a_reply'];
		$hidden_form_fields .= '<input type="hidden" name="' . POST_TOPIC_URL . '" value="' . $topic_id . '" />';
		break;
	case 'editpost':
		$meta_content['page_title'] = $lang['Edit_Post'];
		$hidden_form_fields .= '<input type="hidden" name="' . POST_POST_URL . '" value="' . $post_id . '" />';
		break;
}

$meta_content['page_title'] = ($postreport || $lock_subject) ? $lang['Post_a_report'] : $meta_content['page_title'];
$page_title_alt = $meta_content['page_title'];
$meta_content['description'] = '';
$meta_content['keywords'] = '';
$nav_add_page_title = true;

// Generate smilies listing for page output
//generate_smilies('inline');

// We need to force these vars here since posting doesn't use standard Icy Phoenix page generation.
$template->assign_vars(array(
	'S_PRINT_SIZE' => (!empty($config['display_print_size']) ? true : false),
	'S_JQUERY_UI' => (!empty($config['jquery_ui']) ? true : false),
	'S_JQUERY_UI_TP' => (!empty($config['jquery_ui_tp']) ? true : false),
	'S_JQUERY_UI_BA' => (!empty($config['jquery_ui_ba']) ? true : false),
	'S_JQUERY_UI_STYLE' => (!empty($config['jquery_ui_style']) ? $config['jquery_ui_style'] : 'cupertino'),
	'S_JQUERY_TAGS' => (!empty($config['jquery_tags']) ? true : false),
	)
);

// Include page header
page_header($meta_content['page_title'], true);

$template->set_filenames(array(
	'body' => 'posting_body.tpl',
	'pollbody' => 'posting_poll_body.tpl',
	// Event Registration - BEGIN
	'regbody' => 'posting_events_reg_body.tpl',
	// Event Registration - END
	'reviewbody' => 'posting_topic_review.tpl'
	)
);

make_jumpbox(CMS_PAGE_VIEWFORUM);

$rules_bbcode = '';
if (!empty($post_info['forum_rules_in_posting']))
{
	//BBcode Parsing for Olympus rules Start
	$rules_bbcode = $post_info['forum_rules'];
	$bbcode->allow_html = true;
	$bbcode->allow_bbcode = true;
	$bbcode->allow_smilies = true;
	$rules_bbcode = $bbcode->parse($rules_bbcode);
	//BBcode Parsing for Olympus rules Start

	$template->assign_vars(array(
		'S_FORUM_RULES' => true,
		'S_FORUM_RULES_TITLE' => ($post_info['forum_rules_display_title']) ? true : false
		)
	);
}

$template->assign_vars(array(
	'FORUM_ID' => $forum_id,
	'FORUM_NAME' => $forum_name,
	'FORUM_RULES' => $rules_bbcode,
	'L_FORUM_RULES' => (empty($post_info['forum_rules_custom_title'])) ? $lang['Forum_Rules'] : $post_info['forum_rules_custom_title'],
	'L_POST_A' => $page_title_alt,
	'L_POST_SUBJECT' => $lang['Post_subject'],
	'U_VIEW_FORUM' => append_sid(CMS_PAGE_VIEWFORUM . '?' . POST_FORUM_URL . '=' . $forum_id)
	)
);

// This enables the forum/topic title to be output for posting but not for privmsg (where it makes no sense)
$template->assign_block_vars('switch_not_privmsg', array());

// Enable the Topic Description MOD only if this is a new post or if you edit the fist post of a topic
if (($mode == 'newtopic') || (($mode == 'editpost') && $post_data['first_post']))
{
	$template->assign_var('S_POSTING_TOPIC', true);

	if($is_auth['auth_news'])
	{
		$template->assign_block_vars('switch_show_portal', array());
	}
	if ($config['show_topic_description'])
	{
		$template->assign_block_vars('topic_description', array());
	}
	if ($config['display_tags_box'] && (($user->data['user_level'] == ADMIN) || ($is_auth['auth_mod'] && $config['allow_moderators_edit_tags'])))
	{
		$template->assign_var('S_TOPIC_TAGS', true);
	}
	if ($config['enable_featured_image'])
	{
		$template->assign_var('S_FEATURED_IMAGE', true);
	}
}

// CrackerTracker v5.x
$confirm_image = '';
if (($config['ctracker_vconfirm_guest'] == 1) && !$user->data['session_logged_in'])
{
	define('CRACKER_TRACKER_VCONFIRM', true);
	$template->assign_block_vars('switch_confirm', array());
	include_once(IP_ROOT_PATH . 'includes/ctracker/engines/ct_visual_confirm.' . PHP_EXT);
}
// CrackerTracker v5.x

if (!empty($config['ajax_features']))
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
if ($config['allow_drafts'] == true)
{
	$template->assign_block_vars('allow_drafts', array());
	$hidden_form_fields .= '<input type="hidden" name="d" value="' . $draft_id . '" />';
	if (($draft == true) && ($draft_confirm == false))
	{
		$template->assign_block_vars('save_draft_confirm', array());
	}
}
// MG Drafts - END

// MG Featured Image - BEGIN
$post_featured_image = $post_info['post_images'];
// MG Featured Image - END

// Convert and clean special chars!
$subject = (($mode == 'editpost') ? $subject : htmlspecialchars_clean($subject));
$topic_desc = !empty($topic_desc) ? htmlspecialchars_clean($topic_desc) : '';
$topic_title_clean = (empty($topic_title_clean) ? $subject : trim($topic_title_clean));
$topic_title_clean = substr(ip_clean_string($topic_title_clean, $lang['ENCODING']), 0, 254);
$topic_tags = (empty($topic_tags) ? '' : trim($topic_tags));

// Clean Name - BEGIN
// Just hidden for now... we can restore it in the future...
$hidden_form_fields .= '<input type="hidden" name="topic_title_clean" value="' . $topic_title_clean . '" />';
// Clean Name - END

if (!empty($topic_tags))
{
	$ttags = explode(', ', $topic_tags);
	foreach ($ttags as $ttag)
	{
		if (!empty($ttag))
		{
			$template->assign_block_vars('ttag', array(
				'TTAG' => $ttag
				)
			);
		}
	}
}

// Output the data to the template
$template->assign_vars(array(
	'FORUM_ID' => $forum_id,
	'USERNAME' => $username,
	'SUBJECT' => $subject,
	'TOPIC_DESCRIPTION' => $topic_desc,
	'TOPIC_TITLE_CLEAN' => $topic_title_clean,
	'TOPIC_TAGS' => $topic_tags,
	'POST_FEATURED_IMAGE' => $post_info['post_images'],
	'S_JQUERY_TOPIC_TAGS' => !empty($use_jquery_tags) ? true : false,
	'MESSAGE' => $message,
	'HTML_STATUS' => $html_status,
	'BBCODE_STATUS' => sprintf($bbcode_status, '<a href="' . append_sid('faq.' . PHP_EXT . '?mode=bbcode') . '" target="_blank">', '</a>'),
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
// UPI2DB - BEGIN
	'L_MARK_EDIT' => $lang['mark_edit'],
// UPI2DB - END
	'L_DELETE_POST' => $lang['Delete_post'],

	'L_SHOW_PORTAL' => $lang['Show_In_Portal'],
	'S_TOPIC_SHOW_PORTAL' => ($topic_show_portal) ? 'checked="checked"' : '',

	'L_POST_HIGHLIGHT' => $lang['PostHighlight'],
	'L_TOPIC_DESCRIPTION' => $lang['Topic_description'],

	'U_SMILEY_CREATOR' => append_sid('smiley_creator.' . PHP_EXT . '?mode=text2shield'),
	'U_VIEWTOPIC' => ($mode == 'reply') ? append_sid(CMS_PAGE_VIEWTOPIC . '?' . (!empty($forum_id_append) ? ($forum_id_append . '&amp;') : '') . $topic_id_append . '&amp;sd=d') : '',
	'U_REVIEW_TOPIC' => ($mode == 'reply') ? append_sid('posting.' . PHP_EXT . '?mode=topicreview&amp;' . (!empty($forum_id_append) ? ($forum_id_append . '&amp;') : '') . $topic_id_append) : '',

	'S_IS_PM' => 0,

	// AJAX Features - BEGIN
	'S_AJAX_BLUR' => $ajax_blur,
	'S_AJAX_PM_USER_CHECK' => $ajax_pm_user_check,
	'S_DISPLAY_PREVIEW' => ($preview) ? '' : 'style="display: none;"',
	'S_EDIT_POST_ID' => ($mode == 'editpost') ? $post_id : 0,
	'L_SEARCH_RESULTS' => $lang['AJAX_search_results'],
	'L_SEARCH_RESULT' => $lang['AJAX_search_result'],
	'L_EMPTY_SUBJECT' => $lang['Empty_subject'],
	'L_AJAX_NO_RESULTS' => $lang['No_search_match'],
	'L_MAX_POLL_OPTIONS' => $lang['To_many_poll_options'],
	'POLL_MAX_OPTIONS' => $config['max_poll_options'],
	// AJAX Features - END

	'L_CALENDAR_TITLE' => $lang['Calendar_event'],
	'L_TIME' => $lang['Event_time'],
	'L_CALENDAR_DURATION' => $lang['Calendar_duration'],
	'L_DAYS' => $lang['Days'],
	'L_HOURS' => $lang['Hours'],
	'L_MINUTES' => $lang['Minutes'],
	'L_TODAY' => $lang['Today'],

	// We need to remove leading zero or we may have problems with the script!
	'TODAY_DAY' => gmdate('j'),
	'TODAY_MONTH' => gmdate('n'),
	'TODAY_YEAR' => gmdate('Y'),

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
// UPI2DB - BEGIN
	'S_MARK_EDIT_CHECKED' => ($mark_edit) ? 'checked="checked"' : '',
// UPI2DB - BEGIN

	// CrackerTracker v5.x
	'CONFIRM_IMAGE' => $confirm_image,
	'L_CT_CONFIRM' => $lang['ctracker_vc_guest_post'],
	'L_CT_CONFIRM_E' => $lang['ctracker_vc_guest_expl'],
	'S_HIDDEN_FIELDS' => $s_hidden_fields,
	// CrackerTracker v5.x

	'S_TYPE_TOGGLE' => $topic_type_toggle,
	'S_TOPIC_ID' => $topic_id,
	'S_POST_ACTION' => append_sid(CMS_PAGE_POSTING),
	'S_HIDDEN_FORM_FIELDS' => $hidden_form_fields
	)
);

// Poll entry switch/output
if((($mode == 'newtopic') || (($mode == 'editpost') && $post_data['edit_poll'])) && $is_auth['auth_pollcreate'])
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
		'POLL_START' => $poll_start,
		'POLL_LENGTH' => $poll_length,
		'POLL_MAX_OPTIONS_INPUT' => $poll_max_options,
		'POLL_CHANGE_CHECKBOX' => (!empty($poll_change) ? ' checked="checked"' : ''),
		'POLL_CHANGE' => $poll_change
		)
	);

	if(($mode == 'editpost') && $post_data['edit_poll'] && $post_data['has_poll'])
	{
		$template->assign_block_vars('switch_poll_delete_toggle', array());
	}

	if(!empty($poll_options))
	{
		@reset($poll_options);
		while(list($option_id, $option_text) = each($poll_options))
		{
			if (!empty($option_text))
			{
				$template->assign_block_vars('poll_option_rows', array(
					'POLL_OPTION' => $option_text,
					'S_POLL_OPTION_NUM' => $option_id
					)
				);
			}
		}
	}

	$template->assign_var_from_handle('POLLBOX', 'pollbody');
}

// Event Registration - BEGIN
// Registration entry switch/output
if((($mode == 'newtopic') || (($mode == 'editpost') && $post_data['first_post'])) && $is_auth['auth_cal'])
{
	if($preview)
	{
		$reg_active = ($_POST['start_registration'] == 1) ? 'checked="checked"' : '';

		$reg_max_option1 = (!empty($_POST['reg_max_option1'])) ? $_POST['reg_max_option1'] : '';
		$reg_max_option2 = (!empty($_POST['reg_max_option2'])) ? $_POST['reg_max_option2'] : '';
		$reg_max_option3 = (!empty($_POST['reg_max_option3'])) ? $_POST['reg_max_option3'] : '';

		$reg_length = (!empty($_POST['reg_length'])) ? $_POST['reg_length'] : '';
	}

	// secure integer-values
	$reg_max_option1 = (!empty($reg_max_option1)) ? max(0, intval($reg_max_option1)) : '';
	$reg_max_option2 = (!empty($reg_max_option2)) ? max(0, intval($reg_max_option2)) : '';
	$reg_max_option3 = (!empty($reg_max_option3)) ? max(0, intval($reg_max_option3)) : '';
	$reg_length = (isset($reg_length)) ? max(0, intval($reg_length)) : 0;

	$template->assign_vars(array(
		'REG_ACTIVE' => $reg_active,

		'L_REG_TITLE' => $lang['Reg_Title'],
		'L_ADD_REGISTRATION' => $lang['Add_registration'],
		'L_ADD_REG_EXPLAIN' => $lang['Add_reg_explain'],
		'L_REG_ACTIVATE' => $lang['reg_activate'],
		'L_REG_RESET' => $lang['reg_reset'],

		//'L_REG_OPTION1_OPTION' => $lang['Reg_Green_Option'],
		//'L_REG_OPTION2_OPTION' => $lang['Reg_Blue_Option'],
		//'L_REG_OPTION3_OPTION' => $lang['Reg_Red_Option'],

		'L_REG_OPTION1' => $lang['Reg_Do'],
		'L_REG_OPTION2' => $lang['Reg_Maybe'],
		'L_REG_OPTION3' => $lang['Reg_Dont'],

		'L_REG_MAX_OPTION1' => $reg_max_option1,
		'L_REG_MAX_OPTION2' => $reg_max_option2,
		'L_REG_MAX_OPTION3' => $reg_max_option3,

		'L_REG_MAX_REGISTRATIONS' => $lang['Reg_Value_Max_Registrations'],

		'REG_LENGTH' => $reg_length,

		'L_REG_LENGTH' => $lang['Reg_for'],
		'L_REG_LENGTH_EXPLAIN' => $lang['Reg_for_explain'],
		'L_REG_DAYS' => $lang['Days']
		)
	);

	$template->assign_var_from_handle('REGBOX', 'regbody');
}
// Event Registration - END

// Topic review
if($mode == 'reply' && $is_auth['auth_read'])
{
	require(IP_ROOT_PATH . 'includes/topic_review.' . PHP_EXT);
	topic_review($forum_id, $topic_id, true);
	$template->assign_block_vars('switch_inline_mode', array());
	$template->assign_var_from_handle('TOPIC_REVIEW_BOX', 'reviewbody');
}

// BBCBMG - BEGIN
include(IP_ROOT_PATH . 'includes/bbcb_mg.' . PHP_EXT);
$template->assign_var_from_handle('BBCB_MG', 'bbcb_mg');
// BBCBMG - END
// BBCBMG SMILEYS - BEGIN
generate_smilies('inline');
include(IP_ROOT_PATH . 'includes/bbcb_smileys_mg.' . PHP_EXT);
$template->assign_var_from_handle('BBCB_SMILEYS_MG', 'bbcb_smileys_mg');
// BBCBMG SMILEYS - END

page_footer();

?>