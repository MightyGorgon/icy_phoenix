<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_post.' . PHP_EXT);

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

if ($userdata['user_level'] != ADMIN)
{
	message_die(GENERAL_MESSAGE, $lang['Not_Authorised']);
}

// Get the needes values from post
$submit = '';
$post_id = 0;
$topic_id = 0;
$edit_post_time = '';
$s_days = '';
$s_months = '';
$s_year = '';
$s_hours = '';
$s_minutes = '';
$s_seconds = '';
$topic_post_time = '';
$post_id = (!empty($_POST[POST_POST_URL])) ? intval($_POST[POST_POST_URL]) : intval($_GET[POST_POST_URL]);
$post_id = ($post_id < 0) ? 0 : $post_id;
$topic_id = (!empty($_POST[POST_TOPIC_URL])) ? intval($_POST[POST_TOPIC_URL]) : intval($_GET[POST_TOPIC_URL]);
$topic_id = ($topic_id < 0) ? 0 : $topic_id;

// Get the submitted values, if a submit was send
$submit = (!empty($_POST['submit'])) ? $_POST['submit'] : $_GET['submit'];

// Submit if submit is given
if ($submit)
{
	$new_poster = (!empty($_POST['username']) ? $_POST['username'] : $_GET['username']);
	$new_poster = (!empty($new_poster) ? phpbb_clean_username($new_poster) : '');
	$topic_post = (!empty($_POST['topic_post'])) ? $_POST['topic_post'] : $_GET['topic_post'];
	$twelve_hours = (!empty($_POST['twelve_hours'])) ? $_POST['twelve_hours'] : $_GET['twelve_hours'];
	$new_day = (!empty($_POST[$topic_post . '_day'])) ? intval($_POST[$topic_post . '_day']) : intval($_GET[$topic_post . '_day']);
	$month = (!empty($_POST[$topic_post . '_month'])) ? intval($_POST[$topic_post . '_month']) : intval($_GET[$topic_post . '_month']);
	$year = (!empty($_POST[$topic_post . '_year'])) ? intval($_POST[$topic_post . '_year']) : intval($_GET[$topic_post . '_year']);
	$hour = (!empty($_POST[$topic_post . '_hour'])) ? intval($_POST[$topic_post . '_hour']) : intval($_GET[$topic_post . '_hour']);
	$minute = (!empty($_POST[$topic_post . '_minute'])) ? intval($_POST[$topic_post . '_minute']) : intval($_GET[$topic_post . '_minute']);
	$second = (!empty($_POST[$topic_post . '_second'])) ? intval($_POST[$topic_post . '_second']) : intval($_GET[$topic_post . '_second']);
	$am_pm_s = (!empty($_POST[$topic_post . '_ampm'])) ? $_POST[$topic_post . '_ampm'] : $_GET[$topic_post . '_ampm'];

	if (($am_pm_s == 'pm') && ($twelve_hours == true))
	{
		$hour += 12;
	}

	$edit_post_time = mktime($hour, $minute, $second, $month, $new_day, $year);
	// Mighty Gorgon: alternative way... it needs to be checked though!!!
	//$edit_post_time = gmmktime($hour, $minute, $second, $month, $new_day, $year) - (3600 * $board_config['board_timezone']);

	$time_changed = change_post_time($post_id, $edit_post_time);

	if (!empty($new_poster))
	{
		$poster_changed = change_poster_id($post_id, $new_poster);
	}

	$page_title = $lang['EDIT_POST_DETAILS'];
	$meta_description = '';
	$meta_keywords = '';
	$gen_simple_header = true;
	include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);

	$template->set_filenames(array('body' => 'edit_post_details_body.tpl'));

	$template->assign_block_vars('submit_finished', array());
	$template->assign_vars(array(
		'L_POST_EDIT_TIME' => $lang['Edit_post_time'],
		'L_TIME' => ($topic_post_time == 'topic') ? $lang['Topic_time_xs'] : $lang['Post_time'],

		'CLOSE' => true,
		'POST_EDIT_STRING' => $lang['DETAILS_CHANGED'],

		'U_VIEWTOPIC' => append_sid(VIEWTOPIC_MG . '?' . POST_POST_URL . '=' . $post_id . '#p' . $post_id)
		)
	);

	$template->pparse('body');

	include(IP_ROOT_PATH . 'includes/page_tail.' . PHP_EXT);
}
else
{
	// Check a post id was given
	if ($post_id == 0)
	{
		message_die(GENERAL_MESSAGE, $lang['No_post_id']);
	}

	// Check a topic_id was given and read it if not
	if ($topic_id == 0)
	{
		$sql = "SELECT p.topic_id
						FROM " . POSTS_TABLE . " p
						WHERE p.post_id = '" . $post_id . "'";
		if (!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not catch topic id for post', '', __LINE__, __FILE__, $sql);
		}
		while ($row = $db->sql_fetchrow($result))
		{
			$topic_id = $row['topic_id'];
		}
		$db->sql_freeresult($result);
	}

	// Check the post is first post in topic or not
	$sql = "SELECT topic_first_post_id FROM " . TOPICS_TABLE . " WHERE topic_id = '" . $topic_id . "'";
	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not get first post id from topic', '', __LINE__, __FILE__, $sql);
	}
	while ($row = $db->sql_fetchrow($result))
	{
		$topic_first_post_id = $row['topic_first_post_id'];
	}
	$db->sql_freeresult($result);

	// Read post or topic time
	if ($topic_first_post_id == $post_id)
	{
		$topic_post_time = 'topic';

		$sql = "SELECT t.topic_time, t.topic_poster, u.user_id, u.username, u.user_active, u.user_color
						FROM " . TOPICS_TABLE . " t, " . USERS_TABLE . " u
						WHERE t.topic_id = '" . $topic_id . "'
							AND u.user_id = t.topic_poster";
		if (!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not get topic time', '', __LINE__, __FILE__, $sql);
		}
		while ($row = $db->sql_fetchrow($result))
		{
			$edit_post_time = $row['topic_time'];
			$poster_id = $row['topic_poster'];
			$poster_name = colorize_username($row['user_id'], $row['username'], $row['user_color'], $row['user_active']);
		}
		$db->sql_freeresult($result);
	}
	else
	{
		$topic_post_time = 'post';

		$sql = "SELECT p.post_time, p.poster_id, u.user_id, u.username, u.user_active, u.user_color
						FROM " . POSTS_TABLE . " p, " . USERS_TABLE . " u
						WHERE p.post_id = '" . $post_id . "'
							AND u.user_id = p.poster_id";
		if (!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not get post time and post edit time', '', __LINE__, __FILE__, $sql);
		}
		while ($row = $db->sql_fetchrow($result))
		{
			$edit_post_time = $row['post_time'];
			$poster_id = $row['poster_id'];
			$poster_name = colorize_username($row['user_id'], $row['username'], $row['user_color'], $row['user_active']);
		}
		$db->sql_freeresult($result);
	}

	// Check user dateformat for output
	$twelve_hours = (strpos($userdata['user_dateformat'], 'g') || strpos($userdata['user_dataformat'], 'h')) ? true : 0;
	$am_pm = (strpos($userdata['user_dateformat'], 'a') || strpos($userdata['user_dataformat'], 'A')) ? true : 0;

	// Create the drop downs
	$s_hours = '<select name="' . $topic_post_time . '_hour">';
	for ($i = 0; $i < (($twelve_hours == true) ? 13 : 24); $i++)
	{
		$j = ($i < 10) ? '0'.$i : $i;

		$s_hours .= '<option value="'.$j.'">'.$j.'</option>';
	}
	$s_hours .= '</select>';
	$s_hours = str_replace('value="' . create_date((($twelve_hours == true) ? 'h' : 'H'), $edit_post_time, $userdata['user_timezone']).'">', 'value="' . create_date((($twelve_hours == true) ? 'h' : 'H'), $edit_post_time, $userdata['user_timezone']) . '" selected="selected">', $s_hours);

	$s_minutes = '<select name="' . $topic_post_time . '_minute">';
	$s_seconds = '<select name="' . $topic_post_time . '_second">';
	for ($i = 0; $i < 60; $i++)
	{
		$j = ($i < 10) ? '0'.$i : $i;

		$s_minutes .= '<option value="' . $j . '">' . $j . '</option>';
		$s_seconds .= '<option value="' . $j . '">' . $j . '</option>';
	}
	$s_minutes .= '</select>';
	$s_seconds .= '</select>';
	$s_minutes = str_replace('value="' . create_date('i', $edit_post_time, $userdata['user_timezone']) . '">', 'value="' . create_date('i', $edit_post_time, $userdata['user_timezone']) . '" selected="selected">', $s_minutes);
	$s_seconds = str_replace('value="' . create_date('s', $edit_post_time, $userdata['user_timezone']) . '">', 'value="' . create_date('s', $edit_post_time, $userdata['user_timezone']) . '" selected="selected">', $s_seconds);

	if ($am_pm == true)
	{
		$s_am_pm = '<select name="' . $topic_post_time . '_ampm">';
		$s_am_pm .= '<option value="am">AM</option>';
		$s_am_pm .= '<option value="pm">PM</option>';
		$s_am_pm .= '</select>';
		$s_am_pm = str_replace('value="' . create_date('a', $edit_post_time, $userdata['user_timezone']) . '">', 'value="' . create_date('a', $edit_post_time, $userdata['user_timezone']) . '" selected="selected">', $s_am_pm);
	}
	else
	{
		$s_am_pm = '';
	}

	$s_days = '<select name="' . $topic_post_time . '_day">';
	for ($i = 1; $i < 32; $i++)
	{
		$j = ($i < 10) ? '0'.$i : $i;

		$s_days .= '<option value="' . $j . '">' . $j . '</option>';
	}
	$s_days .= '</select>';
	$s_days = str_replace('value="' . create_date('d', $edit_post_time, $userdata['user_timezone']) . '">', 'value="' . create_date('d', $edit_post_time, $userdata['user_timezone']) . '" selected="selected">', $s_days);

	$s_months = '<select name="' . $topic_post_time . '_month">';
	$s_months .= '<option value="01">' . $lang['datetime']['January'] . '</option>';
	$s_months .= '<option value="02">' . $lang['datetime']['February'] . '</option>';
	$s_months .= '<option value="03">' . $lang['datetime']['March'] . '</option>';
	$s_months .= '<option value="04">' . $lang['datetime']['April'] . '</option>';
	$s_months .= '<option value="05">' . $lang['datetime']['May'] . '</option>';
	$s_months .= '<option value="06">' . $lang['datetime']['June'] . '</option>';
	$s_months .= '<option value="07">' . $lang['datetime']['July'] . '</option>';
	$s_months .= '<option value="08">' . $lang['datetime']['August'] . '</option>';
	$s_months .= '<option value="09">' . $lang['datetime']['September'] . '</option>';
	$s_months .= '<option value="10">' . $lang['datetime']['October'] . '</option>';
	$s_months .= '<option value="11">' . $lang['datetime']['November'] . '</option>';
	$s_months .= '<option value="12">' . $lang['datetime']['December'] . '</option>';
	$s_months .= '</select>';
	$s_months = str_replace('value="' . create_date('m', $edit_post_time, $userdata['user_timezone']) . '">', 'value="' . create_date('m', $edit_post_time, $userdata['user_timezone']) . '" selected="selected">', $s_months);

	$s_year = '<input type="text" class="post" size="4" maxlength="4" name="' . $topic_post_time . '_year" value="' . create_date('Y', $edit_post_time, $userdata['user_timezone']) . '" />';

	$date_sep = '&nbsp;.&nbsp;';
	$time_sep = '&nbsp;:&nbsp;';
	$blank_sep = '&nbsp;&nbsp;&nbsp;';

	if ($userdata['user_lang'] == 'english')
	{
		$post_edit_string = $s_months . $date_sep . $s_days . $date_sep . $s_year;
	}
	else
	{
		$post_edit_string = $s_days . $date_sep . $s_months . $date_sep . $s_year;
	}

	$post_edit_string .= $blank_sep . $s_hours . $time_sep . $s_minutes . $time_sep . $s_seconds . (($s_am_pm != '') ? $time_sep . $s_am_pm : '');

	$s_hidden_fields = '<input type="hidden" value="' . $topic_id . '" name="' . POST_TOPIC_URL . '" />';
	$s_hidden_fields .= '<input type="hidden" value="' . $post_id . '" name="' . POST_POST_URL . '" />';
	$s_hidden_fields .= '<input type="hidden" value="' . $topic_post_time . '" name="topic_post" />';
	$s_hidden_fields .= '<input type="hidden" value="' . $twelve_hours . '" name="twelve_hours" />';
	$s_hidden_fields .= ($s_am_pm == '') ? '<input type="hidden" value="" name="' . $topic_post_time . '_ampm" />' : '';

	$page_title = $lang['EDIT_POST_DETAILS'];
	$meta_description = '';
	$meta_keywords = '';
	$gen_simple_header = true;
	include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);

	$template->assign_block_vars('entry_page', array());
	$template->set_filenames(array('body' => 'edit_post_details_body.tpl'));

	$template->assign_vars(array(
		'L_POST_EDIT_TIME' => $lang['Edit_post_time'],
		'L_TIME' => ($topic_post_time == 'topic') ? $lang['Topic_time_xs'] : $lang['Post_time'],
		'L_SUBMIT' => $lang['Submit'],
		'L_RESET' => $lang['Reset'],
		'L_FIND_USERNAME' => $lang['Find_username'],

		'U_SEARCH_USER' => append_sid(SEARCH_MG . '?mode=searchuser'),

		'POSTER_NAME' => $poster_name,
		'POST_EDIT_STRING' => $post_edit_string,

		'S_ACTION' => append_sid('edit_post_details.' . PHP_EXT),
		'S_HIDDEN_FIELDS' => $s_hidden_fields
		)
	);

	$template->pparse('body');

	include(IP_ROOT_PATH . 'includes/page_tail.' . PHP_EXT);
}

?>