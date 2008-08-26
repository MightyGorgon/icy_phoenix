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

if (!defined('IN_PHPBB'))
{
	define('IN_PHPBB', true);
}
$aprvmUtil = new aprvmUtils();
$aprvmUtil->modVersion = '1.6.0';
$aprvmUtil->copyrightYear = '2001-2005';

/****************************************************************************
/** Module Setup
/***************************************************************************/
define('PRIVMSGS_ALL_MAIL', -1);
$phpbb_root_path = './../';
include($phpbb_root_path . 'extension.inc');
include_once('pagestart.' . $phpEx);
include_once($phpbb_root_path . 'includes/bbcode.' . $phpEx);
//$aprvmUtil->find_lang_file('lang_admin_priv_msgs');
if (!empty($setmodules))
{
	$filename = basename(__FILE__);
	$module['1610_Users']['150_Private_Messages'] = $filename;
	return;
}

/****************************************************************************
/** Module Actual Start
/***************************************************************************/
/*******************************************************************************************
/** Get parameters.  'var_name' => 'default_value'
/** This is outdated insecure, but I don't feel like rewriting the whole thing to be even more
/**    class structured.  Maybe some day when phpBB moves to php5 I will.
/******************************************************************************************/
//Normal sections.
$params = array('mode' => '', 'order' => 'DESC',
'sort' => 'privmsgs_date', 'pmaction' => 'none',
'filter_from' => '', 'filter_to' => '', 'filter_from_text' => '', 'filter_to_text' => '');
foreach($params as $var => $default)
{
	$$var = $default;
	if(isset($_POST[$var]) || isset($_GET[$var]))
	{
		$$var = (isset($_POST[$var])) ? $_POST[$var] : $_GET[$var];
	}
}

//Sections requiring intval assignments
$params = array('view_id' => '', 'start' => 0, 'pmtype' => PRIVMSGS_ALL_MAIL);
foreach($params as $var => $default)
{
	$$var = $default;
	if(isset($_POST[$var]) || isset($_GET[$var]))
	{
		$$var = intval((isset($_POST[$var])) ? $_POST[$var] : $_GET[$var]);
	}
}
/****************************************************************************
/** Main Vars.
/***************************************************************************/
$status_message = '';
$aprvmUtil->init();

$topics_per_pg = max(1, $board_config['aprvmRows']); //Just in case someone manually changes it to be some crazy number, we'll show 1 row always
$page_title = $lang['Private_Messages'];
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
$archive_text = ($board_config['aprvmArchive'] && $mode == 'archive') ? '_archive' : '';
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

if (count($_POST))
{
	$aprvmMan = new aprvmManager();
	foreach($_POST as $key => $val)
	{
		/*******************************************************************************************
		/** Check for archive items
		/******************************************************************************************/
		if ($board_config['aprvmArchive'] && substr_count($key, 'archive_id_'))
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
		$sql = 'SELECT pm.*, pmt.*
			FROM ' . PRIVMSGS_TABLE . "$archive_text pm, " . PRIVMSGS_TEXT_TABLE . " pmt
			WHERE pm.privmsgs_id = pmt.privmsgs_text_id
			AND pmt.privmsgs_text_id = $view_id";
		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, $lang['Error_Posts_Table'], '', __LINE__, __FILE__);
		}
		$privmsg = $db->sql_fetchrow($result);
		/************************/
		/* Just stole all the phpBB code for message processing :) And edited a ton of it out since we are all admins here */
		/**********************/
		$private_message = $privmsg['privmsgs_text'];
		$bbcode_uid = $privmsg['privmsgs_bbcode_uid'];
		global $bbcode;
		$bbcode->allow_html = ( $board_config['allow_html'] ? true : false );
		$bbcode->allow_bbcode = ( $board_config['allow_bbcode'] && $privmsg['privmsgs_enable_bbcode'] ? true : false );
		$bbcode->allow_smilies = ( $board_config['allow_smilies'] && $privmsg['privmsgs_enable_smilies'] ? true : false );
		$private_message = $bbcode->parse($private_message, $bbcode_uid);
		/*
		if ( $bbcode_uid != '' )
		{
			$private_message = bbencode_second_pass($private_message, $bbcode_uid);
		}
		$private_message = make_clickable($private_message);
		if ( $privmsg['privmsgs_enable_smilies'] )
		{
			$old_config = $board_config['smilies_path'];
			$board_config['smilies_path'] = '../' . $board_config['smilies_path'];
			$private_message = smilies_pass($private_message);
			$board_config['smilies_path'] = $old_config;
		}
		*/
		$private_message = str_replace("\n", '<br />', $private_message);

		$template->set_filenames(array(
		'viewmsg_body' => ADM_TPL . 'admin_priv_msgs_view_body.tpl')
		);
		$template->assign_vars(array(
		'L_SUBJECT' => $lang['Subject'],
		'L_TO' => $lang['To'],
		'L_FROM' => $lang['From'],
		'L_SENT_DATE' => $lang['Sent_Date'],
		'L_PRIVATE_MESSAGES' => $aprvmUtil->modName)
		);
		$template->assign_vars(array(
		'SUBJECT' => $privmsg['privmsgs_subject'],
		'FROM' => $aprvmUtil->id_2_name($privmsg['privmsgs_from_userid']),
		'FROM_IP' => ($board_config['aprvmIP']) ? ' : ('.decode_ip($privmsg['privmsgs_ip']).')' : '',
		'TO' => $aprvmUtil->id_2_name($privmsg['privmsgs_to_userid']),
		'DATE' => create_date($lang['DATE_FORMAT'], $privmsg['privmsgs_date'], $board_config['board_timezone']),
		'MESSAGE' => $private_message)
		);

		if ($board_config['aprvmView'])
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
			if(!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, $lang['Error_Other_Table'], '', __LINE__, __FILE__);
			}
			while($row = $db->sql_fetchrow($result))
			{
				$user_id_sql_list .= ($user_id_sql_list != '') ? ', '.$row['user_id'] : $row['user_id'];
			}

			// Get orphan PM ids
			$priv_msgs_id_sql_list = '';
			$sql = 'SELECT privmsgs_id FROM '. PRIVMSGS_TABLE ."$archive_text
				WHERE privmsgs_to_userid NOT IN ($user_id_sql_list)";
			//print $sql;
			if(!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, $lang['Error_Posts_Table'], '', __LINE__, __FILE__, $sql);
			}
			while ($row = $db->sql_fetchrow($result))
			{
				$priv_msgs_id_sql_list .= ($priv_msgs_id_sql_list != '') ? ', '.$row['privmsgs_id'] : $row['privmsgs_id'];
			}
			if ($priv_msgs_id_sql_list != '')
			{
				$sql = "DELETE FROM " . PRIVMSGS_TEXT_TABLE . "
					WHERE privmsgs_text_id IN ($priv_msgs_id_sql_list)";
				//print $sql;
				if(!$db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, $lang['Error_Posts_Table'], '', __LINE__, __FILE__, $sql);
				}

				$sql = "DELETE FROM " . PRIVMSGS_TABLE . "$archive_text
					WHERE privmsgs_id  IN ($priv_msgs_id_sql_list)";
				//print $sql;
				if(!$db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, $lang['Error_Posts_Table'], '', __LINE__, __FILE__, $sql);
				}
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
			$sql = 'SELECT privmsgs_id FROM '. PRIVMSGS_TABLE ."$archive_text
				WHERE privmsgs_type = ". PRIVMSGS_SENT_MAIL;
			if(!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, $lang['Error_Posts_Table'], '', __LINE__, __FILE__, $sql);
			}
			while ($row = $db->sql_fetchrow($result))
			{
				$priv_msgs_id_sql_list .= ($priv_msgs_id_sql_list != '') ? ', '.$row['privmsgs_id'] : $row['privmsgs_id'];
			}
			if ($priv_msgs_id_sql_list != '')
			{
				$sql = "DELETE FROM " . PRIVMSGS_TEXT_TABLE . "
					WHERE privmsgs_text_id IN ($priv_msgs_id_sql_list)";
				//print $sql;
				if(!$db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, $lang['Error_Posts_Table'], '', __LINE__, __FILE__, $sql);
				}

				$sql = "DELETE FROM " . PRIVMSGS_TABLE . "$archive_text
					WHERE privmsgs_id  IN ($priv_msgs_id_sql_list)";
				//print $sql;
				if(!$db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, $lang['Error_Posts_Table'], '', __LINE__, __FILE__, $sql);
				}
			}

			$status_message .= $lang['Removed_Sent'];
			$status_message .= (SQL_LAYER == 'db2' || SQL_LAYER == 'mysql' || SQL_LAYER == 'mysql4') ? sprintf($lang['Affected_Rows'], $db->sql_affectedrows()) : '';
		}
	}
	default:
	{
		$sql = 'SELECT pm.*, pmt.* FROM ' . PRIVMSGS_TABLE . "$archive_text pm, " . PRIVMSGS_TEXT_TABLE . " pmt
				WHERE pm.privmsgs_id = pmt.privmsgs_text_id
				$pmtype_text
				$filter_from_text
				$filter_to_text
				ORDER BY $sort $order
			   LIMIT $start, $topics_per_pg";

		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, $lang['Error_Posts_Archive_Table'], '', __LINE__, __FILE__);
		}

		$i = 0;
		while($row = $db->sql_fetchrow($result))
		{
			$view_url = (!$board_config['aprvmView']) ? append_sid($aprvmUtil->urlStart . '&amp;pmaction=view_message&amp;view_id=' . $row['privmsgs_id']) : '#';
			$onclick_url = ($board_config['aprvmView']) ? "JavaScript:window.open('" . append_sid($aprvmUtil->urlStart . '&amp;pmaction=view_message&amp;view_id=' . $row['privmsgs_id']) . "','_privmsg','width=550,height=450,resizable=yes')" : '';
			$template->assign_block_vars('msgrow', array(
			'ROW_CLASS' => (!(++$i% 2)) ? $theme['td_class1'] : $theme['td_class2'],
			'ATTACHMENT_INFO' => (defined('ATTACH_VERSION')) ? 'Not Here Yet' : '',
			'PM_ID' => $row['privmsgs_id'],
			'PM_TYPE' => $lang['PM_' . $row['privmsgs_type']],
			'SUBJECT' => $row['privmsgs_subject'],
			'FROM' => $aprvmUtil->id_2_name($row['privmsgs_from_userid']),
			'TO' => $aprvmUtil->id_2_name($row['privmsgs_to_userid']),
			'FROM_IP' => ($board_config['aprvmIP']) ? '<br />('.decode_ip($row['privmsgs_ip']).')' : '',
			'U_VIEWMSG' => $onclick_url,
			'U_INLINE_VIEWMSG' => $view_url,
			'DATE' => create_date($lang['DATE_FORMAT'], $row['privmsgs_date'], $board_config['board_timezone']))
			);
			if ($mode != 'archive' && $board_config['aprvmArchive'])
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

		if ($mode != 'archive' && $board_config['aprvmArchive'])
		{
			$template->assign_block_vars('archive_avail_switch', array());
		}
		else {
			/* Send the comment area to the archive only parts to prevent JS errors */
			$template->assign_vars(array(
			'JS_ARCHIVE_COMMENT_1' => '/* ',
			'JS_ARCHIVE_COMMENT_2' => ' */'));
		}

		$template->set_filenames(array(
		'body' => ADM_TPL . 'admin_priv_msgs_body.tpl')
		);

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
		'CURRENT_ROWS' => $board_config['aprvmRows'],
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
		'URL_INLINE_MESSAGE_TYPE' => ($board_config['aprvmView'] == 1) ? '<a href="' . append_sid($aprvmUtil->urlStart . '&config_name=aprvmView&config_value=0') . "\">{$lang['Inline']}</a>" : $lang['Inline'],
		'URL_POPUP_MESSAGE_TYPE' => ($board_config['aprvmView'] == 0) ? '<a href="' . append_sid($aprvmUtil->urlStart . '&config_name=aprvmView&config_value=1') . "\">{$lang['Pop_up']}</a>" : $lang['Pop_up'],
		'URL_ROWS_PLUS_5' => '<a href="' . append_sid($aprvmUtil->urlStart . '&config_name=aprvmRows&config_value='.strval($board_config['aprvmRows']+5)) . "\">{$lang['Rows_Plus_5']}</a>",
		'URL_ROWS_MINUS_5' => ($board_config['aprvmRows'] > 5) ? '<a href="' . append_sid($aprvmUtil->urlStart . '&config_name=aprvmRows&config_value='.strval($board_config['aprvmRows']-5)) . "\">{$lang['Rows_Minus_5']}</a>" : $lang['Rows_Minus_5'],
		'URL_SHOW_IP_ON' => ($board_config['aprvmIP'] == 0) ? '<a href="' . append_sid($aprvmUtil->urlStart . '&config_name=aprvmIP&config_value=1') . "\">{$lang['Enable']}</a>" : $lang['Enable'],
		'URL_SHOW_IP_OFF' => ($board_config['aprvmIP'] == 1) ? '<a href="' . append_sid($aprvmUtil->urlStart . '&config_name=aprvmIP&config_value=0') . "\">{$lang['Disable']}</a>" : $lang['Disable'],
		'URL_ARCHIVE_ENABLE_LINK' => ($board_config['aprvmArchive'] == 0) ? '<a href="' . append_sid($aprvmUtil->urlStart . '&config_name=aprvmArchive&config_value=1') . "\">{$lang['Enable']}</a>" : $lang['Enable'],
		'URL_ARCHIVE_DISABLE_LINK' => ($board_config['aprvmArchive'] == 1) ? '<a href="' . append_sid($aprvmUtil->urlStart . '&config_name=aprvmArchive&config_value=0') . "\">{$lang['Disable']}</a>" : $lang['Disable'],
		'URL_SWITCH_MODE' => ($board_config['aprvmArchive'] == 1) ? ($mode == 'archive') ? '<b><a class="gen" href="' . append_sid($aprvmUtil->urlBase . '&mode=normal') . "\">{$lang['Switch_Normal']}</a></b>" :'<b><a class="gen" href="' . append_sid($aprvmUtil->urlBase . '&mode=archive') . "\">{$lang['Switch_Archive']}</a></b>" : '',

		'S_MODE' => $mode,
		'S_PMTYPE' => $pmtype,
		'S_FILTER_FROM' => $filter_from,
		'S_FILTER_TO' => $filter_to,
		'S_PMTYPE_SELECT' => $aprvmUtil->make_drop_box('pmtype'),
		'S_MODE_SELECT' => $aprvmUtil->make_drop_box('sort'),
		'S_ORDER_SELECT' => $aprvmUtil->make_drop_box('order'),
		'S_FILENAME' => basename(__FILE__),
		'S_MODE_ACTION' => append_sid(basename(__FILE__)))
		);


		if ($status_message != '')
		{
			$template->assign_block_vars('statusrow', array());
			$template->assign_vars(array(
			'L_STATUS' => $lang['Status'],
			'I_STATUS_MESSAGE' => $status_message)
			);
		}

		$template->pparse('body');
		$aprvmUtil->copyright($page_title, '2001-2003');
		include('page_footer_admin.' . $phpEx);
		break;
	}
}

class aprvmUtils
{
	var $modVersion;
	var $modName;
	var $copyrightYear;
	var $archiveText;
	var $inArchiveText;
	var $urlPage;
	var $urlStart;

	function aprvmUtils()
	{
		$this->archiveText = '_archive';
	}

	function init()
	{
		global $lang, $mode, $board_config;

		$this->modName = ($board_config['aprvmArchive'] && $mode == 'archive') ? $lang['Private_Messages_Archive'] : $lang['Private_Messages'];
		$this->setupConfig();
		$this->makeURLStart();
		$this->inArchiveText = ($mode == 'archive') ? '_archive' : '';
	}

	function makeURLStart()
	{
		global $filter_from, $filter_to, $order;
		global $mode, $pmtype, $sort, $pmtype_text, $start, $phpEx;

		$this->urlBase = basename(__FILE__). "?order=$order&amp;sort=$sort&amp;pmtype=$pmtype&filter_from=$filter_from&filter_to=$filter_to";
		$this->urlPage = $this->urlBase. "&mode=$mode";
		$this->urlStart = $this->urlPage . '&start='.$start;
	}


	function setupConfig()
	{
		global $board_config, $db, $status_message, $lang;

		$configList = array('aprvmArchive', 'aprvmVersion', 'aprvmView', 'aprvmRows', 'aprvmIP');
		$configLangs = array('aprvmArchive' => $lang['Archive_Feature'],
							'aprvmVersion' => $lang['Version'],
							'aprvmView' => $lang['PM_View_Type'],
							'aprvmRows' => $lang['Rows_Per_Page'],
							'aprvmIP' => $lang['Show_IP']);
		$configDefaults = array('0', $this->modVersion, '0', '25', '1');
								//off, version, inline, 25, yes
		//Check for an update config command
		//Also do an array check to make sure our config is in our config list array to update
		if (isset($_GET['config_name']) && in_array($_GET['config_name'], $configList))
		{
			$sql = 'UPDATE '. CONFIG_TABLE . "
					set config_value = '{$_GET['config_value']}'
					WHERE config_name = '{$_GET['config_name']}'";
			$db->sql_query($sql);
			$board_config[$_GET['config_name']] = $_GET['config_value'];
			$status_message .= sprintf($lang['Updated_Config'], $configLangs[$_GET['config_name']]);
		}

		//Loop through and see if a config name is set, if not set up a default
		foreach($configList as $num => $val)
		{
			if (!isset($board_config[$val]))
			{
				$sql = 'INSERT INTO '. CONFIG_TABLE . "
					(config_name, config_value)
					VALUES
					('$val', '{$configDefaults[$num]}')";
				$db->sql_query($sql);
				$board_config[$val] = $configDefaults[$num];
				$status_message .= sprintf($lang['Inserted_Default_Value'], $configLangs[$_GET['config_name']]);
			}

		}

		//If archive is enabled, check to see if the archive table exists
		if ($board_config['aprvmArchive'])
		{
			$sql = 'SELECT privmsgs_id FROM ' . PRIVMSGS_TABLE .$this->archiveText;
			if(!$result = $db->sql_query($sql))
			{
				//Cheap way for checking if the archive table exists
				$errorMessage = $db->sql_error();
				if (strpos($errorMessage['message'], 'exist') !== false)
				{
					$this->doArchiveTable();
				}
			}
		}

		//Check to see if board_config has the right version we are running
		if ($board_config['aprvmVersion'] != $this->modVersion)
		{
			$sql = 'UPDATE '. CONFIG_TABLE . "
					set config_value = '{$this->modVersion}'
					WHERE config_name = 'aprvmVersion'";
			$db->sql_query($sql);
			$board_config['aprvmVersion'] = $this->modVersion;
			$status_message .= sprintf($lang['Updated_Config'], $configLangs['aprvmVersion']);
		}
	}

	function resync($type, $user_id, $num = 1)
	{
		global $db;

		if (($type == PRIVMSGS_NEW_MAIL || $type == PRIVMSGS_UNREAD_MAIL))
		{
			// Update appropriate counter
			switch ($type)
			{
				case PRIVMSGS_NEW_MAIL:
				$sql = "user_new_privmsg = user_new_privmsg - $num";
				break;
				case PRIVMSGS_UNREAD_MAIL:
				$sql = "user_unread_privmsg = user_unread_privmsg - $num";
				break;
			}

			$sql = "UPDATE " . USERS_TABLE . "
				SET $sql
				WHERE user_id = $user_id";
			if ( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, $lang['Error_Posts_Table'], '', __LINE__, __FILE__, $sql);
			}
		}
	}

	function make_drop_box($prefix = 'sort')
	{
		global $sort_types, $order_types, $pmtypes, $lang, $sort, $order, $pmtype, $page_title;

		$rval = '<select name="'.$prefix.'">';

		switch($prefix)
		{
			case 'sort':
			foreach($sort_types as $val)
			{
				$selected = ($sort == $val) ? 'selected="selected"' : '';
				$rval .= "<option value=\"$val\" $selected>" . $lang[$val] . '</option>';
			}
			break;
			case 'order':
			foreach($order_types as $val)
			{
				$selected = ($order == $val) ? 'selected="selected"' : '';
				$rval .= "<option value=\"$val\" $selected>" . $lang[$val] . '</option>';
			}
			break;
			case 'pmtype':
			foreach($pmtypes as $val)
			{
				$selected = ($pmtype == $val) ? 'selected="selected"' : '';
				$rval .= "<option value=\"$val\" $selected>" . $lang['PM_' . $val] . '</option>';
			}
			break;
		}
		$rval .= '</select>';

		return $rval;
	}

	function id_2_name($id, $mode = 'user')
	{
		global $db;

		static $nameCache; //Stores names we've already sent a query for
						   //Has array sections ['user'] and ['reverse']
						   //['user']['user_id'] => ['username']
						   //['reverse']['username'] => ['user_id']

		if ($id == '')
		{
			return '?';
		}

		switch($mode)
		{
			case 'user':
			{
				if (isset($nameCache['user'][$id]))
				{
					return $nameCache['user'][$id];
				}

				$sql = 'SELECT username FROM ' . USERS_TABLE . "
					WHERE user_id = $id";

				if(!$result = $db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, $lang['Error_Other_Table'], '', __LINE__, __FILE__, $sql);
				}
				$row = $db->sql_fetchrow($result);
				//Setupcache
				$nameCache['user'][$row['user_id']] = $row['username'];
				$nameCache['reverse'][$row['username']] = $row['user_id'];
				return $row['username'];
				break;
			}
			case 'reverse':
			{
				if (isset($nameCache['reverse'][$id]))
				{
					return $nameCache['reverse'][$id];
				}
				$sql = 'SELECT user_id FROM ' . USERS_TABLE . "
					WHERE username = '$id'";

				if(!$result = $db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, $lang['Error_Other_Table'], '', __LINE__, __FILE__, $sql);
				}
				$row = $db->sql_fetchrow($result);
				if (empty($row['user_id']))
				{
					return 0;
				}
				else
				{
					//Setupcache
					$nameCache['user'][$row['user_id']] = $row['username'];
					$nameCache['reverse'][$row['username']] = $row['user_id'];
					return $row['user_id'];
				}
				break;
			}
		}
	}

	function do_pagination($mode = 'normal')
	{
		global $db, $filter_from_text, $filter_to_text, $filter_from, $filter_to, $lang, $template, $order;
		global $mode, $pmtype, $sort, $pmtype_text, $archive_text, $start, $archive_start, $topics_per_pg, $phpEx;

		$sql = 'SELECT count(*) AS total FROM ' . PRIVMSGS_TABLE . $this->inArchiveText." pm
			WHERE 1
			$pmtype_text
			$filter_from_text
			$filter_to_text";

		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, $lang['Error_Posts_Table'], '', __LINE__, __FILE__, $sql);
		}
		$total = $db->sql_fetchrow($result);
		$total_pms = ($total['total'] > 0) ? $total['total'] : 1;

		$pagination = generate_pagination($this->urlPage, $total_pms, $topics_per_pg, $start)."&nbsp;";

		$template->assign_vars(array(
			"PAGINATION" => $pagination,
			"PAGE_NUMBER" => sprintf($lang['Page_of'], ( floor( $start / $topics_per_pg ) + 1 ), ceil( $total_pms / $topics_per_pg )),

			"L_GOTO_PAGE" => $lang['Goto_page'])
		);
	}

	/**
	* @return boolean
	* @param filename string
	* @desc Tries to locate and include the specified language file.  Do not include the .php extension!
	*/
	function find_lang_file($filename)
	{
		global $lang, $phpbb_root_path, $board_config, $phpEx;

		if (file_exists($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . "/$filename.$phpEx"))
		{
			include_once($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . "/$filename.$phpEx");
		}
		elseif (file_exists($phpbb_root_path . "language/lang_english/$filename.$phpEx"))
		{
			include_once($phpbb_root_path . "language/lang_english/$filename.$phpEx");
		}
		else
		{
			message_die(GENERAL_ERROR, "Unable to find a suitable language file for $filename!", '');
		}
		return true;
	}

	/**
	* @return void
	* @desc Prints a sytlized line of copyright for module
	*/
	function copyright()
	{
		printf('<br /><div class="copyright" style="text-align:center;"> %s &copy; %s <a href="http://www.nivisec.com" class="copyright">Nivisec.com</a>.', $this->modName, $this->copyrightYear);
		printf('<br />PHP Version %s</div>', phpversion());
	}

	function doArchiveTable()
	{
		global $db, $status_message, $lang, $table_prefix;

		switch (SQL_LAYER)
		{
			case 'mysql':
			case 'mysql4':
			{
				$create[] = "CREATE TABLE `".$table_prefix."privmsgs_archive` (
					`privmsgs_id` mediumint( 8 ) unsigned NOT NULL AUTO_INCREMENT ,
					`privmsgs_type` tinyint( 4 ) NOT NULL default '0',
					`privmsgs_subject` varchar( 255 ) NOT NULL default '0',
					`privmsgs_from_userid` mediumint( 8 ) NOT NULL default '0',
					`privmsgs_to_userid` mediumint( 8 ) NOT NULL default '0',
					`privmsgs_date` int( 11 ) NOT NULL default '0',
					`privmsgs_ip` varchar( 8 ) NOT NULL default '',
					`privmsgs_enable_bbcode` tinyint( 1 ) NOT NULL default '1',
					`privmsgs_enable_html` tinyint( 1 ) NOT NULL default '0',
					`privmsgs_enable_smilies` tinyint( 1 ) NOT NULL default '1',
					`privmsgs_attach_sig` tinyint( 1 ) NOT NULL default '1',
					`privmsgs_attachment` tinyint(1) NOT NULL default '0',
					`privmsgs_enable_autolinks_acronyms` tinyint( 1 ) NOT NULL default '1',
					PRIMARY KEY ( `privmsgs_id` ) ,
					KEY `privmsgs_from_userid` ( `privmsgs_from_userid` ) ,
					KEY `privmsgs_to_userid` ( `privmsgs_to_userid` )
					)";
				break;
			}
		}

		foreach($create as $sql)
		{
			if(!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, $lang['Error_Posts_Archive_Table'], '', __LINE__, __FILE__);
			}
		}
		$status_message .= $lang['Archive_Table_Inserted'];
	}
}

class aprvmManager
{
	var $deleteQueue;
	var $archiveQueue;
	var $syncNums;

	function arpvmManager()
	{
	}

	function addArchiveItem($post_id)
	{
		$this->archiveQueue[] = $post_id;
	}

	function addDeleteItem($post_id)
	{
		$this->deleteQueue[] = $post_id;
	}

	function doArchive()
	{
		global $lang, $db, $status_message, $aprvmUtil;

		if (!count($this->archiveQueue)) return;

		$postList = '';
		foreach($this->archiveQueue as $post_id)
		{
			$postList .= ($postList != '') ? ', '.$post_id : $post_id;
		}

		$sql = 'SELECT * FROM ' . PRIVMSGS_TABLE . "
				WHERE privmsgs_id IN ($postList)";
		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, $lang['Error_Posts_Table'], '', __LINE__, __FILE__, $sql);
		}
		while ($row = $db->sql_fetchrow($result))
		{
			$sql = 'INSERT INTO ' . PRIVMSGS_TABLE . $aprvmUtil->archiveText.' VALUES
				(' . $row['privmsgs_id'] . ', ' . $row['privmsgs_type'] . ", '" . addslashes($row['privmsgs_subject']) . "', " .
				$row['privmsgs_from_userid'] . ', ' . $row['privmsgs_to_userid'] . ', ' . $row['privmsgs_date'] . ", '" .
				$row['privmsgs_ip'] . "', " . $row['privmsgs_enable_bbcode'] . ', ' . $row['privmsgs_enable_html'] . ', ' .
				$row['privmsgs_enable_smilies'] . ', ' . $row['privmsgs_attach_sig'] . ', ' . $row['privmsgs_attachment'] . ', ' . $row['privmsgs_enable_autolinks_acronyms'] . ')';
			if(!$db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, $lang['Error_Posts_Text_Table'], '', __LINE__, __FILE__, $sql);
			}
			else
			{
				$status_message .= sprintf($lang['Archived_Message'], $row['privmsgs_subject']);
				$this->syncNums[$row['privmsgs_to_userid']][$row['privmsgs_type']]++;
			}
		}
		$sql = 'DELETE FROM ' . PRIVMSGS_TABLE . "
				WHERE privmsgs_id IN ($postList)";
		if(!$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, $lang['Error_Posts_Text_Table'], '', __LINE__, __FILE__, $sql);
		}

	}

	function doDelete()
	{
		global $board_config, $db, $lang, $status_message, $aprvmUtil, $mode;

		if (!count($this->deleteQueue)) return;

		$postList = '';
		foreach($this->deleteQueue as $post_id)
		{
			if ($board_config['aprvmArchive'] && isset($_POST['archive_id_' . $post_id]))
			{
				/* This query isn't really needed, but makes the hey we deleted this title isntead of id show up */
				$sql = 'SELECT privmsgs_subject FROM ' . PRIVMSGS_TABLE . $aprvmUtil->archiveText . "
							WHERE privmsgs_id = $post_id";
				if(!$result = $db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, $lang['Error_Posts_Archive_Table'], '', __LINE__, __FILE__, $sql);
				}
				$row = $db->sql_fetchrow($result);
				$status_message .= sprintf($lang['Archived_Message_No_Delete'], $row['privmsgs_subject']);
			}
			else
			{
				$postList .= ($postList != '') ? ', '.$post_id : $post_id;
			}
		}

			$sql = 'SELECT privmsgs_subject, privmsgs_to_userid, privmsgs_type FROM ' . PRIVMSGS_TABLE . $aprvmUtil->inArchiveText."
			   WHERE privmsgs_id IN ($postList)";
			if(!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, $lang['Error_Posts_Table'], '', __LINE__, __FILE__, $sql);
			}
			while ($row = $db->sql_fetchrow($result))
			{
				$status_message .= sprintf($lang['Deleted_Message'], $row['privmsgs_subject']);

				if (!$board_config['aprvmArchive'] || $mode != 'archive')
				{
					$this->syncNums[$row['privmsgs_to_userid']][$row['privmsgs_type']]++;
				}
			}

			$sql = "DELETE FROM " . PRIVMSGS_TEXT_TABLE . "
			   	   WHERE privmsgs_text_id IN ($postList)";
			if(!$db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, $lang['Error_Posts_Table'], '', __LINE__, __FILE__, $sql);
			}

			$sql = "DELETE FROM " . PRIVMSGS_TABLE . $aprvmUtil->inArchiveText."
			   	   WHERE privmsgs_id IN ($postList)";
			if(!$db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, $lang['Error_Posts_Table'], '', __LINE__, __FILE__, $sql);
			}
	}

	function go()
	{
		global $aprvmUtil;

		$this->doArchive();
		$this->doDelete();
		if (count($this->syncNums))
		{
			foreach($this->syncNums as $user_id => $type)
			{
				foreach($type as $pmType => $num)
				{
					$aprvmUtil->resync($pmType, $user_id, $num);
				}
			}
		}
	}
}
?>
