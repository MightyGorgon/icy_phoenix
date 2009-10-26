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
* Christian Knerr (cback) - (www.cback.de)
*
*/

/**
* <b>CrackerTracker File: ct_visual_confirm.php</b> <br /><br />
*
* This File implements the functions for the visual confirmation system used
* in CrackerTracker. We used the Visual Confirm generator from the phpBB Group
* that we don't have to include new files.
*
* We can use this file to generate the visual code on login and guest postings
* if we need it.
*
* @author Christian Knerr (cback)
* @package ctracker
* @version 5.0.0
* @since 25.07.2006 - 17:09:31
* @copyright (c) 2006 www.cback.de
*
*/

if(!defined('IN_ICYPHOENIX') || !defined('CRACKER_TRACKER_VCONFIRM'))
{
	die('Hacking attempt!');
}

if (isset($user_id) && ($user_id < 0) && isset($_GET['uid']))
{
	$user_id = $_GET['uid'];
}

/*
 * Visual Confirmation Check
 */

if ($refresh)
{
	$confirm_id = htmlspecialchars($_POST['confirm_id']);
	if (!preg_match('/^[A-Za-z0-9]+$/', $confirm_id))
	{
		$confirm_id = '';
	}
	$sql = 'SELECT code
		FROM ' . CONFIRM_TABLE . "
		WHERE confirm_id = '" . $confirm_id . "'
			AND session_id = '" . $userdata['session_id'] . "'";
	$result = $db->sql_query($sql);

	if ($row = $db->sql_fetchrow($result))
	{
		$sql = 'DELETE FROM ' . CONFIRM_TABLE . "
			WHERE confirm_id = '$confirm_id'
				AND session_id = '" . $userdata['session_id'] . "'";
		$db->sql_query($sql);
	}
}

if ( $mode == 'check' || defined('POST_CONFIRM_CHECK') )
{
	if ( empty($_POST['confirm_id']) )
	{
		$error = true;
		$error_msg = ( ( isset($error_msg) ) ? '<br />' : '' ) . $lang['ctracker_login_wrong'];
	}
	else
	{
		$confirm_id = htmlspecialchars($_POST['confirm_id']);
		$confirm_code = htmlspecialchars($_POST['confirm_code']);
		if (!preg_match('/^[A-Za-z0-9]+$/', $confirm_id))
		{
			$confirm_id = '';
		}
		$sql = 'SELECT code
			FROM ' . CONFIRM_TABLE . "
			WHERE confirm_id = '" . $confirm_id . "'
				AND session_id = '" . $userdata['session_id'] . "'";
		$result = $db->sql_query($sql);

		if ($row = $db->sql_fetchrow($result))
		{
			if ($row['code'] != $confirm_code)
			{
				$error = true;
				$error_msg = ( ( isset($error_msg) ) ? '<br />' : '' ) . $lang['ctracker_login_wrong'];
			}
			else
			{
				$sql = 'DELETE FROM ' . CONFIRM_TABLE . "
					WHERE confirm_id = '$confirm_id'
						AND session_id = '" . $userdata['session_id'] . "'";
				$db->sql_query($sql);
			}
		}
		else
		{
			$error = true;
			$error_msg = ( ( isset($error_msg) ) ? '<br />' : '' ) . $lang['ctracker_login_wrong'];
		}

		$db->sql_freeresult($result);
	}

	if ( $error )
	{
		if ( defined('IN_LOGIN') )
		{
			$error_msg .= '<br /><br />' . sprintf($lang['Click_return_login'], '<a href="ctracker_login.' . PHP_EXT . '">', '</a>');
		}
		message_die(GENERAL_MESSAGE, $error_msg);
	}
	elseif( defined('CTRACKER_ACCOUNT_FREE') )
	{
		//$ctracker_config->reset_login_system($user_id);
		$login_reset = mg_reset_login_system($user_id);
		$message_text = '';
		//$message_text = $user_id;
		$message_text = sprintf($lang['ctracker_login_success'], CMS_PAGE_LOGIN);
		message_die(GENERAL_MESSAGE, $message_text);
	}
}
else
{
	$confirm_image = '';
	$sql = 'SELECT session_id
			FROM ' . SESSIONS_TABLE;
	$result = $db->sql_query($sql);

	if ($row = $db->sql_fetchrow($result))
	{
		$confirm_sql = '';
		do
		{
			$confirm_sql .= (($confirm_sql != '') ? ', ' : '') . "'" . $row['session_id'] . "'";
		}
		while ($row = $db->sql_fetchrow($result));

		$sql = 'DELETE FROM ' . CONFIRM_TABLE . "
			WHERE session_id NOT IN ($confirm_sql)";
		$db->sql_query($sql);
	}

	$db->sql_freeresult($result);

	$sql = 'SELECT COUNT(session_id) AS attempts
		FROM ' . CONFIRM_TABLE . "
		WHERE session_id = '" . $userdata['session_id'] . "'";
	$result = $db->sql_query($sql);

	if ($row = $db->sql_fetchrow($result))
	{
		if ($row['attempts'] > 3)
		{
			message_die(GENERAL_MESSAGE, $lang['ctracker_code_count']);
		}
	}

	$db->sql_freeresult($result);

	// Generate the required confirmation code
	// NB 0 (zero) could get confused with O (the letter) so we make change it
	$code = unique_id();
	$code = substr(str_replace('0', 'Z', strtoupper(base_convert($code, 16, 35))), 2, 6);

	$confirm_id = md5(uniqid($user_ip));

	$sql = 'INSERT INTO ' . CONFIRM_TABLE . " (confirm_id, session_id, code)
		VALUES ('$confirm_id', '". $userdata['session_id'] . "', '$code')";
	$db->sql_query($sql);

	unset($code);

	$confirm_image = '<img src="' . append_sid(CMS_PAGE_PROFILE . '?mode=confirm&amp;id=' . $confirm_id) . '" alt="" title="" />';
	$s_hidden_fields .= '<input type="hidden" name="confirm_id" value="' . $confirm_id . '" />';
}

?>