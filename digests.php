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
* Mark D. Hamill (mhamill@computer.org)
*
*/

// Written by Mark D. Hamill, mhamill@computer.org
// This software is designed to work with phpBB Version 2.0.20

// This is the user interface for the digest software. Users can use it to create and modify their digest
// settings, or remove their digest subscription.

// Warning: this was only tested with MySQL. I don't have access to other databases. Consequently,
// the SQL may need tweaking for other relational databases.

define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

$page_title = $lang['digest_page_title'];
$meta_description = '';
$meta_keywords = '';
include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/digest_constants.' . PHP_EXT);
include(IP_ROOT_PATH . 'language/lang_' . $board_config['default_lang'] . '/lang_digests.' . PHP_EXT);

if ( empty($board_config['enable_digests']) || ($board_config['enable_digests'] == 0) )
{
	message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
}

// Get the server time zone. This is not necessarily what appears in $board_config['board_timezone']
$board_timezone = date('Z') / 3600;

// Get current user's timezone
$user_timezone = (float) $userdata['user_timezone'];

// Offset the timezone information. We will store in the subscriptions table the
// server time to send the digest, since mail_digests.php expects it this way.

$offset = $board_timezone - $user_timezone;

if ($_SERVER['REQUEST_METHOD'] == 'GET')
{

	if ( $userdata['session_logged_in'] )
	{

		$template->set_filenames(array('digests' => 'digests.tpl'));

		// get current subscription data for this user, if any
		$sql = 'SELECT count(*) AS count FROM ' . DIGEST_SUBSCRIPTIONS_TABLE . ' WHERE user_id = ' . $userdata['user_id'];
		if ( !($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not get count from '. DIGEST_SUBSCRIPTIONS_TABLE . ' table', '', __LINE__, __FILE__, $sql);
		}
		$row = $db->sql_fetchrow($result);
		$create_new = ($row['count'] == 0) ? true : false;

		if ($create_new)
		{
			// default values if no digest subscription for user
			$digest_type = 'DAY';
			$format = 'HTML';
			$show_text = 'YES';
			$show_mine = 'YES';
			$new_only = 'TRUE';
			$send_on_no_messages = 'YES';
			$send_hour = '0';
			$text_length = '150';
		}
		else
		{
			// read current digest options into local variables, because we have one inherent connection

			$sql = 'SELECT digest_type, format, show_text, show_mine, new_only, send_on_no_messages, send_hour, text_length FROM ' . DIGEST_SUBSCRIPTIONS_TABLE . ' WHERE user_id = ' . $userdata['user_id'];

			if ( !($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'Could not get count from ' . DIGEST_SUBSCRIPTIONS_TABLE . 'table', '', __LINE__, __FILE__, $sql);
			}
			$row = $db->sql_fetchrow($result);

			$digest_type = $row['digest_type'];
			$format = $row['format'];
			$show_text = $row['show_text'];
			$show_mine = $row['show_mine'];
			$new_only = $row['new_only'];
			$send_on_no_messages = $row['send_on_no_messages'];
			$send_hour = (float) $row['send_hour'] - $offset;
			if ($send_hour < 0)
			{
				$send_hour = $send_hour + 24;
			}
			elseif ($send_hour >= 24)
			{
				$send_hour = $send_hour - 24;
			}
			$text_length = $row['text_length'];
		}
		$db->sql_freeresult ($result);

		// get current subscribed forums for this user, if any
		$sql = 'SELECT count(*) AS count FROM ' . DIGEST_SUBSCRIBED_FORUMS_TABLE . ' WHERE user_id = ' . $userdata['user_id'];
		if ( !($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not get count from ' . DIGEST_SUBSCRIBED_FORUMS_TABLE . ' table', '', __LINE__, __FILE__, $sql);
		}
		$row = $db->sql_fetchrow($result);
		$all_forums_new = ($row['count'] == 0) ? true : false;
		$db->sql_freeresult ($result);

		// fill template with current digest options for user
		$template->assign_vars(array(
			'PAGE_TITLE' => $lang['digest_subject_line'],
			'NO_FORUMS_SELECTED' => $lang['digest_no_forums_selected'],
			'DIGEST_EXPLANATION' => $lang['digest_explanation'],
			'S_POST_ACTION' => append_sid('digests.' . PHP_EXT),
			'DIGEST_CREATE_NEW_VALUE' => ($create_new) ? '1' : '0',
			'L_DIGEST_TYPE' => $lang['digest_wanted'],
			'L_NONE' => $lang['digest_none'],
			'DAY_CHECKED' => ($digest_type=='DAY') ? 'checked="checked"' : '',
			'L_DAILY' => $lang['digest_daily'],
			'WEEK_CHECKED' => ($digest_type=='WEEK') ? 'checked="checked"' : '',
			'L_WEEKLY' => $lang['digest_weekly'],
			'L_FORMAT' => $lang['digest_format'],
			'HTML_CHECKED' => ($format=='HTML') ? 'checked="checked"' : '',
			'L_HTML' => $lang['digest_html'],
			'TEXT_CHECKED' => ($format=='TEXT') ? 'checked="checked"' : '',
			'L_TEXT' => $lang['digest_text'],
			'L_SHOW_TEXT' => $lang['digest_excerpt'],
			'SHOW_TEXT_YES_CHECKED' => ($show_text=='YES') ? 'checked="checked"' : '',
			'L_YES' => $lang['digest_yes'],
			'SHOW_TEXT_NO_CHECKED' => ($show_text=='NO') ? 'checked="checked"' : '',
			'L_NO' => $lang['digest_no'],
			'L_SHOW_MINE' => $lang['digest_l_show_my_messages'],
			'SHOW_MINE_YES_CHECKED' => ($show_mine=='YES') ? 'checked="checked"' : '',
			'SHOW_MINE_NO_CHECKED' => ($show_mine=='NO') ? 'checked="checked"' : '',
			'L_NEW_ONLY' => $lang['digest_l_show_new_only'],
			'NEW_ONLY_YES_CHECKED' => ($new_only=='TRUE') ? 'checked="checked"' : '',
			'NEW_ONLY_NO_CHECKED' => ($new_only=='FALSE') ? 'checked="checked"' : '',
			'L_SEND_ON_NO_MESSAGES' => $lang['digest_send_if_no_msgs'],
			'SEND_ON_NO_MESSAGES_YES_CHECKED' => ($send_on_no_messages=='YES') ? 'checked="checked"' : '',
			'SEND_ON_NO_MESSAGES_NO_CHECKED' => ($send_on_no_messages=='NO') ? 'checked="checked"' : '',
			'L_SEND_HOUR' => $lang['digest_hour_to_send'],
			'MIDNIGHT_SELECTED' => ($send_hour=='0') ? 'selected="selected"' : '',
			'1AM_SELECTED' => ($send_hour=='1') ? 'selected="selected"' : '',
			'2AM_SELECTED' => ($send_hour=='2') ? 'selected="selected"' : '',
			'3AM_SELECTED' => ($send_hour=='3') ? 'selected="selected"' : '',
			'4AM_SELECTED' => ($send_hour=='4') ? 'selected="selected"' : '',
			'5AM_SELECTED' => ($send_hour=='5') ? 'selected="selected"' : '',
			'6AM_SELECTED' => ($send_hour=='6') ? 'selected="selected"' : '',
			'7AM_SELECTED' => ($send_hour=='7') ? 'selected="selected"' : '',
			'8AM_SELECTED' => ($send_hour=='8') ? 'selected="selected"' : '',
			'9AM_SELECTED' => ($send_hour=='9') ? 'selected="selected"' : '',
			'10AM_SELECTED' => ($send_hour=='10') ? 'selected="selected"' : '',
			'11AM_SELECTED' => ($send_hour=='11') ? 'selected="selected"' : '',
			'12PM_SELECTED' => ($send_hour=='12') ? 'selected="selected"' : '',
			'1PM_SELECTED' => ($send_hour=='13') ? 'selected="selected"' : '',
			'2PM_SELECTED' => ($send_hour=='14') ? 'selected="selected"' : '',
			'3PM_SELECTED' => ($send_hour=='15') ? 'selected="selected"' : '',
			'4PM_SELECTED' => ($send_hour=='16') ? 'selected="selected"' : '',
			'5PM_SELECTED' => ($send_hour=='17') ? 'selected="selected"' : '',
			'6PM_SELECTED' => ($send_hour=='18') ? 'selected="selected"' : '',
			'7PM_SELECTED' => ($send_hour=='19') ? 'selected="selected"' : '',
			'8PM_SELECTED' => ($send_hour=='20') ? 'selected="selected"' : '',
			'9PM_SELECTED' => ($send_hour=='21') ? 'selected="selected"' : '',
			'10PM_SELECTED' => ($send_hour=='22') ? 'selected="selected"' : '',
			'11PM_SELECTED' => ($send_hour=='23') ? 'selected="selected"' : '',
			'L_MIDNIGHT' => $lang['digest_midnight'],
			'L_1AM' => $lang['digest_1am'],
			'L_2AM' => $lang['digest_2am'],
			'L_3AM' => $lang['digest_3am'],
			'L_4AM' => $lang['digest_4am'],
			'L_5AM' => $lang['digest_5am'],
			'L_6AM' => $lang['digest_6am'],
			'L_7AM' => $lang['digest_7am'],
			'L_8AM' => $lang['digest_8am'],
			'L_9AM' => $lang['digest_9am'],
			'L_10AM' => $lang['digest_10am'],
			'L_11AM' => $lang['digest_11am'],
			'L_12PM' => $lang['digest_12pm'],
			'L_1PM' => $lang['digest_1pm'],
			'L_2PM' => $lang['digest_2pm'],
			'L_3PM' => $lang['digest_3pm'],
			'L_4PM' => $lang['digest_4pm'],
			'L_5PM' => $lang['digest_5pm'],
			'L_6PM' => $lang['digest_6pm'],
			'L_7PM' => $lang['digest_7pm'],
			'L_8PM' => $lang['digest_8pm'],
			'L_9PM' => $lang['digest_9pm'],
			'L_10PM' => $lang['digest_10pm'],
			'L_11PM' => $lang['digest_11pm'],
			'50_SELECTED' => ($text_length=='50') ? 'selected="selected"' : '',
			'100_SELECTED' => ($text_length=='100') ? 'selected="selected"' : '',
			'150_SELECTED' => ($text_length=='150') ? 'selected="selected"' : '',
			'300_SELECTED' => ($text_length=='300') ? 'selected="selected"' : '',
			'600_SELECTED' => ($text_length=='600') ? 'selected="selected"' : '',
			'MAX_SELECTED' => ($text_length=='32000') ? 'selected="selected"' : '',
			'L_TEXT_LENGTH' => $lang['digest_size'],
			'L_50' => $lang['digest_size_50'],
			'L_100' => $lang['digest_size_100'],
			'L_150' => $lang['digest_size_150'],
			'L_300' => $lang['digest_size_300'],
			'L_600' => $lang['digest_size_600'],
			'L_MAX' => $lang['digest_size_max'],
			'L_FORUM_SELECTION' => $lang['digest_select_forums'],
			'L_ALL_SUBSCRIBED_FORUMS' => $lang['digest_all_forums'],
			'L_SUBMIT' => $lang['digest_submit_text'],
			'L_RESET' => $lang['digest_reset_text'],
			'ALL_FORUMS_CHECKED' => ($create_new || ((!($create_new)) && $all_forums_new)) ? 'checked="checked"' : '',
			'DIGEST_VERSION' => $lang['digest_version_text'] . ' ' . DIGEST_VERSION
			)
		);

		// Retrieve a list of forum_ids that all members can access
		$sql = 'SELECT f.forum_id, f.forum_name, c.cat_order, f.forum_order
			FROM ' . FORUMS_TABLE . ' f, ' . CATEGORIES_TABLE . ' c
			WHERE f.cat_id = c.cat_id AND auth_read IN (' . AUTH_ALL. ',' . AUTH_REG .')
			ORDER BY c.cat_order, f.forum_order';

		if ( !($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not query forum information', '', __LINE__, __FILE__, $sql);
		}

		// We have to do a lot of array processing mainly because MySQL can't handle unions or
		// intersections. Basically we need to figure out: of all forums, which are those this
		// user can potentially read? We only want to send digests for forums for which a user
		// has read privileges.
		$forum_ids = array();
		$forum_names = array();
		$cat_orders = array();
		$forum_orders = array();

		$i=0;
		while ($row = $db->sql_fetchrow ($result))
		{
			$forum_ids [$i] = $row['forum_id'];
			$forum_names [$i] = $row['forum_name'];
			$cat_orders [$i] = $row['cat_order'];
			$forum_orders [$i] = $row['forum_order'];
			$i++;
		}
		$db->sql_freeresult ($result);

		// Now we need to add to our forums array other forums that may be private for which
		// the user has access.

		$sql = 'SELECT DISTINCT a.forum_id, f.forum_name, c.cat_order, f.forum_order
			FROM ' . AUTH_ACCESS_TABLE . ' a, ' . USER_GROUP_TABLE . ' ug, ' . FORUMS_TABLE . ' f, ' . CATEGORIES_TABLE . ' c
			WHERE ug.user_id = ' . $userdata['user_id']
			. ' AND ug.user_pending = 0
			AND a.group_id = ug.group_id AND
			a.forum_id = f.forum_id AND f.cat_id = c.cat_id';

		if ( !($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not query forum information', '', __LINE__, __FILE__, $sql);
		}

		while ($row = $db->sql_fetchrow ($result))
		{
			$forum_ids [$i] = $row['forum_id'];
			$forum_names [$i] = $row['forum_name'];
			$cat_orders [$i] = $row['cat_order'];
			$forum_orders [$i] = $row['forum_order'];
			$i++;
		}
		$i--;
		$db->sql_freeresult ($result);

		// Sort forums so they appear as they would appear on the main index. This makes for a more
		// natural presentation.

		array_multisort($cat_orders, SORT_ASC, $forum_orders, SORT_ASC, $forum_ids, SORT_ASC, $forum_names, SORT_ASC);

		// now print the forums on the web page, each forum being a checkbox with appropriate label
		for ($j=0; $j<=$i; $j++)
		{
			// Don't print if a duplicate
			if (!(($j>0) && ($cat_orders[$j] == $cat_orders[$j-1]) && ($forum_orders[$j] == $forum_orders[$j-1])))
			{
				// Is this forum currently subscribed? If so it needs to be checkmarked
				if (!($all_forums_new))
				{
					$sql = 'SELECT count(*) AS count FROM ' . DIGEST_SUBSCRIBED_FORUMS_TABLE . ' WHERE forum_id = ' . $forum_ids [$j] . ' AND user_id = ' . $userdata['user_id'];
					if ( !($result = $db->sql_query($sql)))
					{
						message_die(GENERAL_ERROR, 'Could not get count from ' . DIGEST_SUBSCRIBED_FORUMS_TABLE . ' table', '', __LINE__, __FILE__, $sql);
					}
					$row = $db->sql_fetchrow($result);
					if ($row['count'] == 0)
					{
						$forum_checked = false;
					}
					else
					{
						$forum_checked = true;
					}
					$db->sql_freeresult ($result);
				}
				else
				{
					$forum_checked = true;
				}

				$template->assign_block_vars('forums', array(
					'FORUM_NAME' => 'forum_' . $forum_ids [$j],
					'CHECKED' => ($forum_checked || $create_new) ? 'checked="checked"' : '',
					'FORUM_LABEL' => $forum_names[$j]));
			}
		}

		$template->pparse('digests');

	}
}
else
{

	// The user has submitted the form. This logic takes the necessary action to update the database
	// and gives an appropriate confirmation message.

	if ($_POST['digest_type'] == 'NONE')
	{

		// user no longer wants a digest
		// first remove all individual forum subscriptions
		$sql = 'DELETE FROM ' . DIGEST_SUBSCRIBED_FORUMS_TABLE . ' WHERE user_id = ' . $userdata['user_id'];

		if ( !($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not delete from ' . DIGEST_SUBSCRIBED_FORUMS_TABLE . ' table', '', __LINE__, __FILE__, $sql);
		}

		// remove subscription itself
		$sql = 'DELETE FROM ' . DIGEST_SUBSCRIPTIONS_TABLE . ' WHERE user_id = ' . $userdata['user_id'];

		if ( !($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not delete from ' . DIGEST_SUBSCRIPTIONS_TABLE . ' table', '', __LINE__, __FILE__, $sql);
		}

		$update_type = 'unsubscribe';

	}
	else
	{
		// In all other cases a digest has to be either created or updated

		// From the offset, calculate the real hour digest is wanted based on server time
		$send_hour = (float) $_POST['send_hour'] + $offset;
		if ($send_hour < 0)
		{
			$send_hour = $send_hour + 24;
		}
		elseif ($send_hour >= 24)
		{
			$send_hour = $send_hour - 24;
		}

		// first, create or update the subscription
		if ($_POST['create_new'] == '1')// new digest
		{
			$sql = 'INSERT INTO ' . DIGEST_SUBSCRIPTIONS_TABLE . ' (user_id, digest_type, format, show_text, show_mine, new_only, send_on_no_messages, send_hour, text_length) VALUES (' .
				intval($userdata['user_id']) . ', ' .
				"'" . htmlspecialchars($_POST['digest_type']) . "', " .
				"'" . htmlspecialchars($_POST['format']) . "', " .
				"'" . htmlspecialchars($_POST['show_text']) . "', " .
				"'" . htmlspecialchars($_POST['show_mine']) . "', " .
				"'" . htmlspecialchars($_POST['new_only']) . "', " .
				"'" . htmlspecialchars($_POST['send_on_no_messages']) . "', " .
				"'" . intval($send_hour) . "', " .
				intval($_POST['text_length']). ')';
			$update_type = 'create';
		}
		else
		{
			$sql = 'UPDATE ' . DIGEST_SUBSCRIPTIONS_TABLE . ' SET ' .
				"digest_type = '" . htmlspecialchars($_POST['digest_type']) . "', " .
				"format = '" . htmlspecialchars($_POST['format']) . "', " .
				"show_text = '" . htmlspecialchars($_POST['show_text']) . "', " .
				"show_mine = '" . htmlspecialchars($_POST['show_mine']) . "', " .
				"new_only = '" . htmlspecialchars($_POST['new_only']) . "', " .
				"send_on_no_messages = '" . htmlspecialchars($_POST['send_on_no_messages']) . "', " .
				"send_hour = '" . intval($send_hour) . "', " .
				'text_length = ' . intval($_POST['text_length']) . ' ' .
				' WHERE user_id = ' . intval($userdata['user_id']);
			$update_type = 'modify';
		}
		if ( !($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not insert or update ' . DIGEST_SUBSCRIPTIONS_TABLE . ' table', '', __LINE__, __FILE__, $sql);
		}

		// next, if there are any individual forum subscriptions, remove the old ones and create the new ones

		$sql = 'DELETE FROM ' . DIGEST_SUBSCRIBED_FORUMS_TABLE . ' WHERE user_id = ' . $userdata['user_id'];
		if ( !($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not delete from ' . DIGEST_SUBSCRIBED_FORUMS_TABLE . ' table', '', __LINE__, __FILE__, $sql);
		}

		// Note that if "all_forums" is checked, this is noted in the subscriptions table. It does not put
		// each forum in the subscribed_forums table. This conserves disk space. "all_forums" means all
		// forums this user is allowed to access.

		if ($_POST['all_forums'] !== 'on')
		{
			foreach ($_POST as $key => $value)
			{
				if (substr($key, 0, 6) == 'forum_')
				{
					$sql = 'INSERT INTO ' . DIGEST_SUBSCRIBED_FORUMS_TABLE . ' (user_id, forum_id) VALUES (' .
					$userdata['user_id'] . ', ' . htmlspecialchars(substr($key,6)) . ')';
					if ( !($result = $db->sql_query($sql)))
					{
						message_die(GENERAL_ERROR, 'Could not insert into ' . DIGEST_SUBSCRIBED_FORUMS_TABLE . ' table', '', __LINE__, __FILE__, $sql);
					}
				}
			}
		}

	}

	$template->set_filenames(array('digests_post' => 'digests_post.tpl'));

	// Show appropriate confirmation message
	if ($update_type == 'unsubscribe')
	{
		$template->assign_vars(array('CREATE_MODIFY_UNSUBSCRIBE_MSG' => $lang['digest_unsubscribe']));
	}
	elseif ($update_type == 'create')
	{
		$template->assign_vars(array('CREATE_MODIFY_UNSUBSCRIBE_MSG' => $lang['digest_create']));
	}
	else
	{
		$template->assign_vars(array('CREATE_MODIFY_UNSUBSCRIBE_MSG' => $lang['digest_modify']));
	}
	$template->assign_vars(array(
		'U_INDEX' => append_sid(FORUM_MG),
		'L_INDEX' => sprintf($lang['Forum_Index'], $board_config['sitename'])
		)
	);

	$template->pparse('digests_post');

}

include(IP_ROOT_PATH . 'includes/page_tail.' . PHP_EXT);

?>