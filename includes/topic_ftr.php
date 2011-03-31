<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

$ftr_install_time = $config['ftr_install_time'];

if (empty($ftr_install_time))
{
	$ftr_install_time = time();
	set_config('ftr_install_time', $ftr_install_time);
}

$ftr_view_mode = request_var('mode', '');
$ftr_user_viewed = ftr_get_users_view($user->data['user_id']);
$ftr_all_users = $config['ftr_all_users'];

if ($ftr_user_viewed || ($user->data['session_logged_in'] && !$ftr_all_users && ($user->data['user_regdate'] <= $ftr_install_time)) || ($ftr_view_mode == 'reading'))
{
	$ftr_disabled = true;
}
else
{
	if ($ftr_view_mode == 'read_this')
	{
		ftr_insert_read_topic($user->data['user_id']);
		redirect(append_sid(CMS_PAGE_VIEWTOPIC . '?' . POST_TOPIC_URL . '=' . $config['ftr_topic_number'] . $kb_mode_append_red . '&mode=reading'));
	}
	else
	{
		if (!$ftr_user_viewed && ((!$ftr_all_users && ($user->data['user_regdate'] >= $ftr_install_time)) || $ftr_all_users))
		{
			$ftr_topic = $config['ftr_topic_number'];
			$msg = $config['ftr_message'];
			$lng_msg = '<br /><br />' . sprintf($lang['Click_read_topic'], '<a href="' . append_sid(CMS_PAGE_VIEWTOPIC . '?' . POST_TOPIC_URL . '=' . $ftr_topic . $kb_mode_append . '&amp;mode=read_this') . '">', '</a>');
			message_die(GENERAL_MESSAGE, $msg . $lng_msg);
		}
		else
		{
			$ftr_disabled = true;
		}
	}
}

/* functions_ftr.php - END */
function ftr_get_users_view($target_user)
{
	global $db, $cache, $user;

	if (!$user->data['session_logged_in'])
	{
		return false;
	}

	$sql = "SELECT * FROM ". FORCE_READ_USERS_TABLE . " WHERE user = '" . $target_user . "' LIMIT 1";
	$result = $db->sql_query($sql);
	if ($row = $db->sql_fetchrow($result))
	{
		return true;
	}

	return false;
}

function ftr_insert_read_topic($target_user)
{
	global $db, $cache;

	$q = "INSERT INTO ". FORCE_READ_USERS_TABLE . " VALUES ('" . $target_user . "', " . time() . ")";
	$r = $db -> sql_query($q);

	return true;
}
/* functions_ftr.php - END */

?>