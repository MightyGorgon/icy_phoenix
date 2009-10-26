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

$active = 0;
$install_time = time();

$viewed_mode = $_GET['mode'];
$check_viewed = ftr_get_users_view($userdata['user_id']);
$ftr_disabled = false;
$q = "SELECT active, effected, install_date FROM " . FORCE_READ_TABLE;
$r = $db -> sql_query($q);
$row = $db -> sql_fetchrow($r);
$db->sql_freeresult($r);
$active = $row['active'];
$effected = $row['effected'];
$ins_date = $row['install_date'];

if ($active && (strlen($ins_date) != 10))
{
	$q = "UPDATE " . FORCE_READ_TABLE . " SET install_date = '" . $install_time . "'";
	$r = $db -> sql_query($q);
}

if (isset($ins_date) && (strlen($ins_date) != 10))
{
	$ins_date = $install_time;
}

if (($viewed_mode == 'reading') || ($check_viewed != 'false'))
{
	$ftr_disabled = true;
}

if ($active && ($check_viewed == 'false') && !$ftr_disabled)
{
	if ($viewed_mode == 'read_this')
	{
		$q = "SELECT topic_number, message FROM " . FORCE_READ_TABLE;
		$r = $db -> sql_query($q);
		$row = $db -> sql_fetchrow($r);
		$db->sql_freeresult($r);
		$ftr_topic = $row['topic_number'];
		$msg = $row['message'];
		ftr_insert_read_topic($userdata['user_id']);
		redirect(append_sid(CMS_PAGE_VIEWTOPIC . '?' . POST_TOPIC_URL . '=' . $ftr_topic . $kb_mode_append_red . '&mode=reading'));
	}
	else
	{
		if ((($check_viewed == 'false') && ($effected != 1) && ($ins_date <= $userdata['user_regdate'])) || (($check_viewed == 'false') && ($effected == '1')))
		{
			$q = "SELECT * FROM " . FORCE_READ_TABLE;
			$r = $db -> sql_query($q);
			$row = $db -> sql_fetchrow($r);
			$db->sql_freeresult($r);
			$ftr_topic = $row['topic_number'];
			$msg = $row['message'];
			$lng_msg = '<br /><br />' . sprintf($lang['Click_read_topic'], '<a href="' . append_sid(CMS_PAGE_VIEWTOPIC . '?' . POST_TOPIC_URL . '=' . $ftr_topic . $kb_mode_append . '&amp;mode=read_this') . '">', '</a>');
			message_die(GENERAL_MESSAGE, $msg . $lng_msg);
		}
		else
		{
			$ftr_disabled = true;
		}
	}
}

?>