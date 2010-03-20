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
* Nivisec.com (support@nivisec.com)
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	define('IN_ICYPHOENIX', true);
}

// Mighty Gorgon - ACP Privacy - BEGIN
if (function_exists('check_acp_module_access'))
{
	$is_allowed = check_acp_module_access();
	if ($is_allowed == false)
	{
		return;
	}
}
// Mighty Gorgon - ACP Privacy - END

if (!empty($setmodules))
{
	$filename = basename(__FILE__);
	$module['1610_Users']['150_Private_Messages'] = $filename;
	$ja_module['1610_Users']['150_Private_Messages'] = false;
	return;
}

if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include_once('pagestart.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/class_privmsgs_admin.' . PHP_EXT);

define('PRIVMSGS_ALL_MAIL', -1);
$aprvmUtil = new aprvmUtils();
$aprvmUtil->modVersion = '1.6.0';
$aprvmUtil->copyrightYear = '2001-2005';

//$aprvmUtil->find_lang_file('lang_admin_priv_msgs');

// Mighty Gorgon - ACP Privacy - BEGIN
$is_allowed = check_acp_module_access();
if ($is_allowed == false)
{
	message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
}
// Mighty Gorgon - ACP Privacy - END

$mode = request_var('mode', '');
$order = request_var('order', 'DESC');
$sort = request_var('sort', 'privmsgs_date');
$pmaction = request_var('pmaction', 'none');
$filter_from = request_var('filter_from', '', true);
$filter_to = request_var('filter_to', '', true);
$filter_from_text = request_var('filter_from_text', '', true);
$filter_to_text = request_var('filter_to_text', '', true);

$view_id = request_var('view_id', 0);
$start = request_var('start', 0);
$pmtype = request_var('pmtype', PRIVMSGS_ALL_MAIL);

/****************************************************************************
/** Main Vars.
/***************************************************************************/
$status_message = '';
$aprvmUtil->init();

$topics_per_pg = max(1, $config['aprvmRows']); //Just in case someone manually changes it to be some crazy number, we'll show 1 row always
$meta_content['page_title'] = $lang['Private_Messages'];
$order_types = array('DESC', 'ASC');
$sort_types = array('privmsgs_date', 'privmsgs_subject', 'privmsgs_from_userid', 'privmsgs_to_userid', 'privmsgs_type');
$pmtypes = array(PRIVMSGS_ALL_MAIL, PRIVMSGS_READ_MAIL, PRIVMSGS_NEW_MAIL, PRIVMSGS_SENT_MAIL, PRIVMSGS_SAVED_IN_MAIL, PRIVMSGS_SAVED_OUT_MAIL, PRIVMSGS_UNREAD_MAIL);
/*
// Private messaging defintions from constants.php for reference
define('PRIVMSGS_READ_MAIL', 0);
define('PRIVMSGS_NEW_MAIL', 1);
define('PRIVMSGS_SENT_MAIL', 2);
define('PRIVMSGS_SAVED_IN_MAIL', 3);
define('PRIVMSGS_SAVED_OUT_MAIL', 4);
define('PRIVMSGS_UNREAD_MAIL', 5);
*/

/*******************************************************************************************
/** Setup some options
/******************************************************************************************/
$archive_text = ($config['aprvmArchive'] && ($mode == 'archive')) ? '_archive' : '';
$pmtype_text = ($pmtype != PRIVMSGS_ALL_MAIL) ? "AND pm.privmsgs_type = $pmtype" : '';

// Assign text filters if specified
if ($filter_from != '')
{
	$filter_from_user = $aprvmUtil->id_2_name($filter_from, 'reverse');
	$filter_from_text = (!empty($filter_from_user)) ? "AND pm.privmsgs_from_userid = $filter_from_user" : '';
}
if ($filter_to != '')
{
	$filter_to_user = $aprvmUtil->id_2_name($filter_to, 'reverse');
	$filter_to_text = (!empty($filter_to_user)) ? "AND pm.privmsgs_to_userid = $filter_to_user" : '';
}

if (sizeof($_POST))
{
	$aprvmMan = new aprvmManager();
	foreach($_POST as $key => $val)
	{
		/*******************************************************************************************
		/** Check for archive items
		/******************************************************************************************/
		if ($config['aprvmArchive'] && substr_count($key, 'archive_id_'))
		{
			$aprvmMan->addArchiveItem(substr($key, 11));
		}
		/*******************************************************************************************
		/** Check for deletion items
		/******************************************************************************************/
		elseif (substr_count($key, 'delete_id_'))
		{
			$aprvmMan->addDeleteItem(substr($key, 10));
		}
	}
	$aprvmMan->go();
}
/*******************************************************************************************
/** Switch our Mode to the right one
/******************************************************************************************/
switch($pmaction)
{
	case 'view_message':
	{
		if ($view_id == '')
		{
			message_die(GENERAL_ERROR, $lang['No_Message_ID'], '', __LINE__, __FILE__);
		}
		$sql = 'SELECT pm.*
			FROM ' . PRIVMSGS_TABLE . "$archive_text pm
			WHERE pm.privmsgs_id = $view_id";
		$result = $db->sql_query($sql);
		$privmsg = $db->sql_fetchrow($result);
		/************************/
		/* Just stole all the phpBB code for message processing :) And edited a ton of it out since we are all admins here */
		/**********************/
		$private_message = $privmsg['privmsgs_text'];

		$bbcode->allow_html = ($config['allow_html'] ? true : false);
		$bbcode->allow_bbcode = ($config['allow_bbcode'] && $privmsg['privmsgs_enable_bbcode'] ? true : false);
		$bbcode->allow_smilies = ($config['allow_smilies'] && $privmsg['privmsgs_enable_smilies'] ? true : false);
		$private_message = $bbcode->parse($private_message);
		//$private_message = str_replace("\n", '<br />', $private_message);

		$template->set_filenames(array('viewmsg_body' => ADM_TPL . 'admin_priv_msgs_view_body.tpl'));
		$template->assign_vars(array(
			'L_SUBJECT' => $lang['Subject'],
			'L_TO' => $lang['To'],
			'L_FROM' => $lang['From'],
			'L_SENT_DATE' => $lang['Sent_Date'],
			'L_PRIVATE_MESSAGES' => $aprvmUtil->modName,

			'SUBJECT' => $privmsg['privmsgs_subject'],
			'FROM_IP' => ($config['aprvmIP']) ? ' : ('.decode_ip($privmsg['privmsgs_ip']).')' : '',
			'FROM' => $aprvmUtil->id_2_name($privmsg['privmsgs_from_userid'], 'user_formatted'),
			'TO' => $aprvmUtil->id_2_name($privmsg['privmsgs_to_userid'], 'user_formatted'),
			'DATE' => create_date($lang['DATE_FORMAT'], $privmsg['privmsgs_date'], $config['board_timezone']),
			'MESSAGE' => $private_message
			)
		);

		if ($config['aprvmView'])
		{
			$template->assign_block_vars('popup_switch', array());
			$template->pparse('viewmsg_body');
			$aprvmUtil->copyright();
			break;
		}
		else
		{
			$template->assign_var_from_handle('PM_MESSAGE', 'viewmsg_body');
		}
	}
	case 'remove_old':
	{
		if ($pmaction == 'remove_old')
		{
			// Build user sql list
			$user_id_sql_list = '';
			$sql = 'SELECT user_id FROM '. USERS_TABLE .'
							WHERE user_id <> '. ANONYMOUS;
			$result = $db->sql_query($sql);

			while($row = $db->sql_fetchrow($result))
			{
				$user_id_sql_list .= ($user_id_sql_list != '') ? ', '.$row['user_id'] : $row['user_id'];
			}

			// Get orphan PM ids
			$priv_msgs_id_sql_list = '';
			$sql = 'SELECT privmsgs_id FROM ' . PRIVMSGS_TABLE . "$archive_text
				WHERE privmsgs_to_userid NOT IN ($user_id_sql_list)";
			//print $sql;
			$result = $db->sql_query($sql);

			while ($row = $db->sql_fetchrow($result))
			{
				$priv_msgs_id_sql_list .= ($priv_msgs_id_sql_list != '') ? ', '.$row['privmsgs_id'] : $row['privmsgs_id'];
			}
			if ($priv_msgs_id_sql_list != '')
			{
				$sql = "DELETE FROM " . PRIVMSGS_TABLE . "
					WHERE privmsgs_id IN ($priv_msgs_id_sql_list)";
				//print $sql;
				$db->sql_query($sql);

				$sql = "DELETE FROM " . PRIVMSGS_TABLE . "$archive_text
					WHERE privmsgs_id  IN ($priv_msgs_id_sql_list)";
				//print $sql;
				$db->sql_query($sql);
			}

			$status_message .= $lang['Removed_Old'];
			$status_message .= (SQL_LAYER == 'db2' || SQL_LAYER == 'mysql' || SQL_LAYER == 'mysql4') ? sprintf($lang['Affected_Rows'], $db->sql_affectedrows()) : '';
		}
	}
	case 'remove_sent':
	{
		if ($pmaction == 'remove_sent')
		{
			// Get sent PM ids
			$priv_msgs_id_sql_list = '';
			$sql = 'SELECT privmsgs_id FROM ' . PRIVMSGS_TABLE . "$archive_text
				WHERE privmsgs_type = " . PRIVMSGS_SENT_MAIL;
			$result = $db->sql_query($sql);

			while ($row = $db->sql_fetchrow($result))
			{
				$priv_msgs_id_sql_list .= ($priv_msgs_id_sql_list != '') ? ', '.$row['privmsgs_id'] : $row['privmsgs_id'];
			}
			if ($priv_msgs_id_sql_list != '')
			{
				$sql = "DELETE FROM " . PRIVMSGS_TABLE . "
					WHERE privmsgs_id IN ($priv_msgs_id_sql_list)";
				//print $sql;
				$db->sql_query($sql);

				$sql = "DELETE FROM " . PRIVMSGS_TABLE . "$archive_text
					WHERE privmsgs_id  IN ($priv_msgs_id_sql_list)";
				//print $sql;
				$db->sql_query($sql);
			}

			$status_message .= $lang['Removed_Sent'];
			$status_message .= sprintf($lang['Affected_Rows'], $db->sql_affectedrows());
		}
	}
	default:
	{
		$sql = 'SELECT pm.* FROM ' . PRIVMSGS_TABLE . "$archive_text pm
				WHERE pm.privmsgs_id = pm.privmsgs_id
				$pmtype_text
				$filter_from_text
				$filter_to_text
				ORDER BY $sort $order
				LIMIT $start, $topics_per_pg";
		$result = $db->sql_query($sql);

		$i = 0;
		while($row = $db->sql_fetchrow($result))
		{
			$view_url = (!$config['aprvmView']) ? append_sid($aprvmUtil->urlStart . '&amp;pmaction=view_message&amp;view_id=' . $row['privmsgs_id']) : '#';
			$onclick_url = ($config['aprvmView']) ? "JavaScript:window.open('" . append_sid($aprvmUtil->urlStart . '&amp;pmaction=view_message&amp;view_id=' . $row['privmsgs_id']) . "','_privmsg','width=550,height=450,resizable=yes')" : '';
			$template->assign_block_vars('msgrow', array(
				'ROW_CLASS' => (!(++$i% 2)) ? $theme['td_class1'] : $theme['td_class2'],
				'ATTACHMENT_INFO' => (defined('ATTACH_VERSION')) ? 'Not Here Yet' : '',
				'PM_ID' => $row['privmsgs_id'],
				'PM_TYPE' => $lang['PM_' . $row['privmsgs_type']],
				'SUBJECT' => $row['privmsgs_subject'],
				'FROM_IP' => ($config['aprvmIP']) ? '<br />('.decode_ip($row['privmsgs_ip']).')' : '',
				'FROM' => $aprvmUtil->id_2_name($row['privmsgs_from_userid'], 'user_formatted'),
				'TO' => $aprvmUtil->id_2_name($row['privmsgs_to_userid'], 'user_formatted'),
				'U_VIEWMSG' => $onclick_url,
				'U_INLINE_VIEWMSG' => $view_url,
				'DATE' => create_date($lang['DATE_FORMAT'], $row['privmsgs_date'], $config['board_timezone'])
				)
			);
			if (($mode != 'archive') && $config['aprvmArchive'])
			{
				$template->assign_block_vars('msgrow.archive_avail_switch_msg', array());
			}
		}

		if ($i == 0)
		{
			$template->assign_block_vars('empty_switch', array());
			$template->assign_var('L_NO_PMS', $lang['No_PMS']);
		}

		$aprvmUtil->do_pagination();

		if (($mode != 'archive') && $config['aprvmArchive'])
		{
			$template->assign_block_vars('archive_avail_switch', array());
		}
		else {
			/* Send the comment area to the archive only parts to prevent JS errors */
			$template->assign_vars(array(
				'JS_ARCHIVE_COMMENT_1' => '/* ',
				'JS_ARCHIVE_COMMENT_2' => ' */'
				)
			);
		}

		$template->set_filenames(array('body' => ADM_TPL . 'admin_priv_msgs_body.tpl'));

		$template->assign_vars(array(
			'L_SELECT_SORT_METHOD' => $lang['Select_sort_method'],
			'L_SUBJECT' => $lang['Subject'],
			'L_TO' => $lang['To'],
			'L_FROM' => $lang['From'],
			'L_SENT_DATE' => $lang['Sent_Date'],
			'L_PAGE_NAME' => $aprvmUtil->modName,
			'L_ORDER' => $lang['Order'],
			'L_SORT' => $lang['Sort'],
			'L_SUBMIT' => $lang['Submit'],
			'L_DELETE' => $lang['Delete'],
			'L_PM_TYPE' => $lang['PM_Type'],
			'L_FILTER_BY' => $lang['Filter_By'],
			'L_RESET' => $lang['Reset'],
			'L_ARCHIVE' => $lang['Archive'],
			'L_PAGE_DESC' => ($mode == 'archive') ? $lang['Archive_Desc'] : $lang['Normal_Desc'],
			'L_VERSION' => $lang['Version'],
			'VERSION' => $aprvmUtil->modVersion,
			'L_CURRENT' => $lang['Current'],
			'CURRENT_ROWS' => $config['aprvmRows'],
			'L_REMOVE_OLD' => $lang['Remove_Old'],
			'L_REMOVE_SENT' => $lang['Remove_Sent'],
			'L_UTILS' => $lang['Utilities'],
			'L_PM_VIEW_TYPE' =>$lang['PM_View_Type'],
			'L_SHOW_IP' =>$lang['Show_IP'],
			'L_ROWS_PER_PAGE' =>$lang['Rows_Per_Page'],
			'L_ARCHIVE_FEATURE' =>$lang['Archive_Feature'],
			'L_OPTIONS' => $lang['Options'],

			'URL_ORPHAN' => append_sid($aprvmUtil->urlStart . '&pmaction=remove_old'),
			'URL_SENT' => append_sid($aprvmUtil->urlStart . '&pmaction=remove_sent'),
			'URL_INLINE_MESSAGE_TYPE' => ($config['aprvmView'] == 1) ? '<a href="' . append_sid($aprvmUtil->urlStart . '&config_name=aprvmView&config_value=0') . "\">{$lang['Inline']}</a>" : $lang['Inline'],
			'URL_POPUP_MESSAGE_TYPE' => ($config['aprvmView'] == 0) ? '<a href="' . append_sid($aprvmUtil->urlStart . '&config_name=aprvmView&config_value=1') . "\">{$lang['Pop_up']}</a>" : $lang['Pop_up'],
			'URL_ROWS_PLUS_5' => '<a href="' . append_sid($aprvmUtil->urlStart . '&config_name=aprvmRows&config_value='.strval($config['aprvmRows']+5)) . "\">{$lang['Rows_Plus_5']}</a>",
			'URL_ROWS_MINUS_5' => ($config['aprvmRows'] > 5) ? '<a href="' . append_sid($aprvmUtil->urlStart . '&config_name=aprvmRows&config_value='.strval($config['aprvmRows']-5)) . "\">{$lang['Rows_Minus_5']}</a>" : $lang['Rows_Minus_5'],
			'URL_SHOW_IP_ON' => ($config['aprvmIP'] == 0) ? '<a href="' . append_sid($aprvmUtil->urlStart . '&config_name=aprvmIP&config_value=1') . "\">{$lang['Enable']}</a>" : $lang['Enable'],
			'URL_SHOW_IP_OFF' => ($config['aprvmIP'] == 1) ? '<a href="' . append_sid($aprvmUtil->urlStart . '&config_name=aprvmIP&config_value=0') . "\">{$lang['Disable']}</a>" : $lang['Disable'],
			'URL_ARCHIVE_ENABLE_LINK' => ($config['aprvmArchive'] == 0) ? '<a href="' . append_sid($aprvmUtil->urlStart . '&config_name=aprvmArchive&config_value=1') . "\">{$lang['Enable']}</a>" : $lang['Enable'],
			'URL_ARCHIVE_DISABLE_LINK' => ($config['aprvmArchive'] == 1) ? '<a href="' . append_sid($aprvmUtil->urlStart . '&config_name=aprvmArchive&config_value=0') . "\">{$lang['Disable']}</a>" : $lang['Disable'],
			'URL_SWITCH_MODE' => ($config['aprvmArchive'] == 1) ? ($mode == 'archive') ? '<b><a class="gen" href="' . append_sid($aprvmUtil->urlBase . '&mode=normal') . "\">{$lang['Switch_Normal']}</a></b>" :'<b><a class="gen" href="' . append_sid($aprvmUtil->urlBase . '&mode=archive') . "\">{$lang['Switch_Archive']}</a></b>" : '',

			'S_MODE' => $mode,
			'S_PMTYPE' => $pmtype,
			'S_FILTER_FROM' => $filter_from,
			'S_FILTER_TO' => $filter_to,
			'S_PMTYPE_SELECT' => $aprvmUtil->make_drop_box('pmtype'),
			'S_MODE_SELECT' => $aprvmUtil->make_drop_box('sort'),
			'S_ORDER_SELECT' => $aprvmUtil->make_drop_box('order'),
			'S_FILENAME' => basename(__FILE__),
			'S_MODE_ACTION' => append_sid(basename(__FILE__))
			)
		);


		if ($status_message != '')
		{
			$template->assign_block_vars('statusrow', array());
			$template->assign_vars(array(
				'L_STATUS' => $lang['Status'],
				'I_STATUS_MESSAGE' => $status_message
				)
			);
		}

		$template->pparse('body');
		$aprvmUtil->copyright($meta_content['page_title'], '2001-2003');
		include('page_footer_admin.' . PHP_EXT);
		break;
	}
}

?>