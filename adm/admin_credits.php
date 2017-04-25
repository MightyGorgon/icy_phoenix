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

define('IN_ICYPHOENIX', true);

if(!empty($setmodules))
{
	$filename = basename(__FILE__);
	$module['1100_General']['Hacks_List'] = $filename;
	return;
}

if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('pagestart.' . PHP_EXT);

/*
If for some reason you need to disable the version check in THIS HACK ONLY,
change the blow to TRUE instead of FALSE.  No other hacks will be affected
by this change.
*/
define('DISABLE_VERSION_CHECK', false);
define('MOD_VERSION', '1.20');

if ($user->data['user_level'] != ADMIN)
{
	message_die(GENERAL_ERROR, 'Not Authorized');
}

include(IP_ROOT_PATH . 'includes/functions_credits.' . PHP_EXT);

/****************************************************************************
/** Constants and Main Vars.
/***************************************************************************/
$meta_content['page_title'] = $lang['Hacks_List'];
$required_fields = array('hack_name', 'hack_desc', 'hack_author');
$dbase_fields = array('hack_download_url', 'hack_hide', 'hack_name', 'hack_desc', 'hack_author', 'hack_author_email', 'hack_author_website', 'hack_version');
$status_message = '';
$update_sql = '';
$insert_sql = '';
$insert_val_sql = '';

/*******************************************************************************************
/** Get parameters.  'var_name' => 'default'
/******************************************************************************************/
$mode = request_var('mode', '');
$hack_id = request_var('hack_id', 0);

if (sizeof($_POST))
{
	foreach($_POST as $key => $valx)
	{
		/*******************************************************************************************
		/** Check for deletion items
		/******************************************************************************************/
		if (substr_count($key, 'delete_id_'))
		{
			$hack_id = substr($key, 10);

			$sql = "SELECT hack_name FROM " . HACKS_LIST_TABLE . "
				WHERE hack_id = '" . $db->sql_escape($hack_id) . "'";
			$result = $db->sql_query($sql);
			$row = $db->sql_fetchrow($result);

			$neat_bc_name = str_replace(" ", "_", $row['hack_name']) . '_list_info';
			$sql = "DELETE FROM " . CONFIG_TABLE . " WHERE config_name = '" . $db->sql_escape($neat_bc_name) . "'";
			$db->sql_query($sql);

			$sql = "DELETE FROM " . HACKS_LIST_TABLE . " WHERE hack_id = '" . $db->sql_escape($hack_id) . "'";
			$db->sql_query($sql);

			$status_message .= sprintf($lang['Deleted_Hack'], htmlspecialchars($row['hack_name']));
		}
		/*******************************************************************************************
		/** Check for update items
		/******************************************************************************************/
		elseif (substr_count($key, 'update_id_'))
		{
			$hack_id = substr($key, 10);

			foreach ($dbase_fields as $val)
			{
				/* Check for required items */
				if (in_array($val, $required_fields) && ($_POST[$val] == ''))
				{
					message_die(GENERAL_ERROR, $lang['Required_Field_Missing'], '', __LINE__, __FILE__);
				}

				/* Compile the SQL Lists */
				$update_sql .= (($update_sql != '') ? ', ' : '') . "$val = '" . $db->sql_escape(htmlspecialchars($_POST[$val])) . "'";
			}

			$sql = "UPDATE " . HACKS_LIST_TABLE . "
				SET $update_sql
				WHERE hack_id = '" . $db->sql_escape($hack_id) . "'";
			$db->sql_query($sql);

			$status_message .= sprintf($lang['Updated_Hack'], htmlspecialchars($_POST['hack_name']));
		}

		/*******************************************************************************************
		/** Check for add items
		/******************************************************************************************/
		elseif (substr_count($key, 'add_id_'))
		{
			$hack_id = substr($key, 7);

			foreach ($dbase_fields as $val)
			{
				/* Check for required items */
				if (in_array($val, $required_fields) && ($_POST[$val] == ''))
				{
					message_die(GENERAL_ERROR, $lang['Required_Field_Missing'], '', __LINE__, __FILE__);
				}

				/* Compile the SQL Lists */
				$insert_sql .= (($insert_sql != '') ? ', ' : '') . $db->sql_escape($val);
				$insert_val_sql .= (($insert_val_sql != '') ? ', ' : '') . "'" . $db->sql_escape(htmlspecialchars($_POST[$val])) . "'";
			}

			$sql = "INSERT INTO " . HACKS_LIST_TABLE . "
				($insert_sql)
				VALUES
				($insert_val_sql)";
			$db->sql_query($sql);

			$status_message .= sprintf($lang['Added_Hack'], htmlspecialchars($_POST['hack_name']));
		}
	}
}
/*******************************************************************************************
/** Parse for modes...Two seperate pages (add + edit, display list)
/******************************************************************************************/
setup_hacks_list_array();
scan_hl_files();
switch($mode)
{
	case 'edit':
	{
		/* Fetch the data for the specified ID in edit mode, then do the same thing as add */
		$sql = "SELECT * FROM " . HACKS_LIST_TABLE . "
			WHERE hack_id = '" . $db->sql_escape($hack_id) . "'";
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);

		$template->assign_vars(array(
			'S_HACK_ID' => $row['hack_id'],
			'S_HIDDEN' => 'update_id_' . $row['hack_id'],
			'S_HACK_NAME' => htmlspecialchars_decode($row['hack_name'], ENT_COMPAT),
			'S_HACK_DESC' => htmlspecialchars_decode($row['hack_desc'], ENT_COMPAT),
			'S_HACK_DOWNLOAD' => htmlspecialchars_decode($row['hack_download_url'], ENT_COMPAT),
			'S_HACK_AUTHOR' => htmlspecialchars_decode($row['hack_author'], ENT_COMPAT),
			'S_HACK_AUTHOR_EMAIL' => htmlspecialchars_decode($row['hack_author_email'], ENT_COMPAT),
			'S_HACK_WEBSITE' => htmlspecialchars_decode($row['hack_author_website'], ENT_COMPAT),
			'S_HACK_HIDE_NO' => ($row['hack_hide'] == 'No') ? 'checked="checked"' : '',
			'S_HACK_HIDE_YES' => ($row['hack_hide'] == 'Yes') ? 'checked="checked"' : '',
			'S_HACK_VERSION' => htmlspecialchars_decode($row['hack_version'], ENT_COMPAT)
			)
		);

	}
	case 'add':
	{
		if ($mode != 'edit')
		{
			$template->assign_vars(array(
				'S_HIDDEN' => 'add_id_' . $row['hack_id'],
				'S_HACK_HIDE_NO' => 'checked="checked"'
				)
			);
		}

		$template->set_filenames(array('body' => ADM_TPL . 'admin_credits_add.tpl'));
		break;
	}
	case 'display':
	default:
	{
		$template->set_filenames(array('body' => ADM_TPL . 'admin_credits_display.tpl'));
		$sql = 'SELECT * FROM ' . HACKS_LIST_TABLE . "
			ORDER BY hack_name ASC";
		$result = $db->sql_query($sql);

		$i = 0;
		while ($row = $db->sql_fetchrow($result))
		{
			$template->assign_block_vars('listrow', array(
				'ROW_CLASS' => (!(++$i% 2)) ? $theme['td_class1'] : $theme['td_class2'],
				'HACK_ID' => $row['hack_id'],
				'HACK_AUTHOR' => ($row['hack_author_email'] != '') ? '<a href="mailto:' . $row['hack_author_email'] . '">' . $row['hack_author'] . '</a>' : $row['hack_author'],
				'HACK_WEBSITE' => ($row['hack_author_website'] != '') ? '<a target="blank" href="' . $row['hack_author_website'] . '">' . $row['hack_author_website'] . '</a>' : $lang['No_Website'],
				'HACK_NAME' => ($row['hack_download_url'] != '') ? '<a href="' . $row['hack_download_url'] . '">' . $row['hack_name'] . '</a>' : $row['hack_name'],
				'HACK_DESC' => $row['hack_desc'],
				'HACK_VERSION' => ($row['hack_version'] != '') ? ' v' . $row['hack_version'] : '',
				'S_ACTION_EDIT' => '<a href="' . append_sid(basename(__FILE__) . '?mode=edit&amp;hack_id=' . $row['hack_id']) . '">' . $lang['Edit'] . '</a>',
				'HACK_DISPLAY' => $lang[$row['hack_hide']],
				'ADD_DATE' => create_date($lang['DATE_FORMAT'], $row['log_time'], $config['board_timezone'])
				)
			);
		}

		if (($i == 0) || !isset($i))
		{
			$template->assign_block_vars('empty_switch', array());
			$template->assign_var('L_NO_HACKS', $lang['No_Hacks']);
		}
	}
}


$template->assign_vars(array(
	'L_VERSION' => $lang['Version'],
	'VERSION' => MOD_VERSION,
	'L_PAGE_NAME' => $meta_content['page_title'],
	'S_ACTION_ADD' => '<a href="' . append_sid(basename(__FILE__) . '?mode=add') . '">' . $lang['Add_New_Hack'] . '</a>',

	'S_MODE_ACTION' => append_sid(basename(__FILE__)),
	'L_EDIT' => $lang['Edit'],
	'L_DELETE' => $lang['Delete'],
	'L_ADD_NEW_HACK' => $lang['Add_New_Hack'],
	'L_AUTHOR' => $lang['Author'],
	'L_DESCRIPTION' => $lang['Description'],
	'L_SUBMIT' => $lang['Submit'],
	'L_RESET' => $lang['Reset'],
	'L_HACK_NAME' => $lang['Hack_Name'],
	'L_AUTHOR_EMAIL' => $lang['Author_Email'],
	'L_REQUIRED' => $lang['Required'],
	'L_WEBSITE' => $lang['Website'],
	'L_DOWNLOAD_URL' => $lang['Download_URL'],
	'L_YES' => $lang['Yes'],
	'L_NO' => $lang['No'],
	'L_VERSION' => $lang['Version'],
	'L_USER_VIEWABLE' => $lang['User_Viewable'],
	'L_PAGE_DESC' => $lang['Page_Desc']
	)
);

if ($status_message != '')
{
	$template->assign_block_vars('statusrow', array());
	$template->assign_vars(array(
	'L_STATUS' => $lang['Status'],
	'I_STATUS_MESSAGE' => $status_message)
	);
}

/************************************************************************
** Begin The Version Check Feature
************************************************************************/
if (file_exists(IP_ROOT_PATH . 'nivisec_version_check.' . PHP_EXT) && !DISABLE_VERSION_CHECK)
{
	define('MOD_CODE', 17);
	include(IP_ROOT_PATH . 'nivisec_version_check.' . PHP_EXT);
}
/************************************************************************
** End The Version Check Feature
************************************************************************/

$template->pparse('body');
copyright_nivisec($lang['Hacks_List'], '2003');
include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);

?>