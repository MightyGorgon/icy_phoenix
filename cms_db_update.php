<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

// CTracker_Ignore: File Checked By Human
define('MG_KILL_CTRACK', true);
define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);
include(IP_ROOT_PATH . 'cms/constants.' . PHP_EXT);

// Define constant to keep page_header.php from sending headers
define('AJAX_HEADERS', true);

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

//========================
//
// Per il CMS bisogna mettere un controllo sullo USER_ID (passandolo via GET)
// e dare il permesso a chi non è admin solo di modificare le proprie pagine
//
//
//========================

// Get SID and check it
if (isset($_POST['sid']) || isset($_GET['sid']))
{
	$sid = (isset($_POST['sid'])) ? $_POST['sid'] : $_GET['sid'];
}
else
{
	$sid = '';
}

if ($sid != $userdata['session_id'])
{
	$result_ar = array(
		'result' => AJAX_ERROR,
		'error_msg' => 'Invalid session_id'
	);
	AJAX_message_die($result_ar);
}

// Get mode
$mode = request_var('mode', '');
$cms_type = request_var('cms_type', '');
$type = request_var('type', '');
$b_id = request_var('b_id', 0);
$b_id = ($b_id < 0) ? 0 : $b_id;
$cat = request_var('cat', 0);
$cat = ($cat < 0) ? 0 : $cat;
$m_id = request_var('m_id', 0);
$m_id = ($m_id < 0) ? 0 : $m_id;
$old_status = request_var('status', 0);
$old_status = ($old_status == 0) ? 0 : 1;

// Send AJAX headers - this is to prevent browsers from caching possible error pages
AJAX_headers();

switch ($mode)
{
	case 'update_block':
		if (($type != '') && ($b_id > 0))
		{
			if($cms_type == '1')
			{
				$cms_block_table = CMS_BLOCKS_TABLE;
			}
			else
			{
				$cms_block_table = CMS_ADV_BLOCKS_TABLE;
			}
			$new_status = ($old_status) ? 0 : 1;
			switch ($type)
			{
				case '0':
					$field = 'active';
					break;
				case '1':
					$field = 'border';
					break;
				case '2':
					$field = 'titlebar';
					break;
				case '3':
					$field = 'local';
					break;
				case '4':
					$field = 'background';
					break;
				default:
					$result_ar = array(
						'result' => AJAX_ERROR,
						'error_msg' => 'Invalid type: ' . $type
					);
					AJAX_message_die($result_ar);
					exit;
			}
			$sql = "UPDATE " . $cms_block_table . " SET " . $field . " = '" . $new_status . "' WHERE bid = '" . $b_id . "'";
			if(!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not insert data into blocks table', $lang['Error'], __LINE__, __FILE__, $sql);
			}
		}
		else
		{
			$result_ar = array(
				'result' => AJAX_ERROR,
				'error_msg' => 'Invalid type: ' . $type
			);
			AJAX_message_die($result_ar);
			exit;
		}
		break;
	case 'update_menu_order':
		if ($userdata['user_level'] != ADMIN)
		{
			$result_ar = array(
				'result' => AJAX_ERROR,
				'error_msg' => 'NOT ALLOWED!!!'
			);
			AJAX_message_die($result_ar);
			exit;
		}
		if (($cat > 0) && !empty($_POST['list_' . $cat]))
		{
			$item_order = 0;
			foreach($_POST['list_' . $cat] as $menu_item_id)
			{
				$item_order++;
				$sql = "UPDATE " . CMS_NAV_MENU_TABLE . " SET menu_order = '" . $item_order . "' WHERE menu_item_id = '" . $menu_item_id . "'";
				if(!$result = $db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'Could not update menu table', $lang['Error'], __LINE__, __FILE__, $sql);
				}
			}
		}
		else
		{
			$result_ar = array(
				'result' => AJAX_ERROR,
				'error_msg' => 'Invalid menu cat: ' . $cat
			);
			AJAX_message_die($result_ar);
			exit;
		}
		break;
	case 'update_modules_order':
		if ($userdata['user_level'] != ADMIN)
		{
			$result_ar = array(
				'result' => AJAX_ERROR,
				'error_msg' => 'NOT ALLOWED!!!'
			);
			AJAX_message_die($result_ar);
			exit;
		}
		if (!empty($_POST['stats_modules']))
		{
			$item_order = 0;
			foreach($_POST['stats_modules'] as $module_item_id)
			{
				$item_order++;
				$sql = "UPDATE " . MODULES_TABLE . " SET display_order = '" . ($item_order * 10) . "' WHERE module_id = '" . $module_item_id . "'";
				if(!$result = $db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'Could not update stats table', $lang['Error'], __LINE__, __FILE__, $sql);
				}
			}
		}
		else
		{
			$result_ar = array(
				'result' => AJAX_ERROR,
				'error_msg' => 'Invalid stats module'
			);
			AJAX_message_die($result_ar);
			exit;
		}
		break;
	case 'update_smileys_order':
		if ($userdata['user_level'] != ADMIN)
		{
			$result_ar = array(
				'result' => AJAX_ERROR,
				'error_msg' => 'NOT ALLOWED!!!'
			);
			AJAX_message_die($result_ar);
			exit;
		}
		if (!empty($_POST['smileys']))
		{
			$item_order = 0;
			foreach($_POST['smileys'] as $smiley_item_id)
			{
				$item_order++;
				$sql = "UPDATE " . SMILIES_TABLE . " SET smilies_order = '" . $item_order . "' WHERE smilies_id = '" . $smiley_item_id . "'";
				if(!$result = $db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'Could not update stats table', $lang['Error'], __LINE__, __FILE__, $sql);
				}
			}
			$db->clear_cache('smileys_');
		}
		else
		{
			$result_ar = array(
				'result' => AJAX_ERROR,
				'error_msg' => 'Invalid smiley position'
			);
			AJAX_message_die($result_ar);
			exit;
		}
		break;
	default:
		$result_ar = array(
			'result' => AJAX_ERROR,
			'error_msg' => 'Invalid mode: ' . $mode
		);
		AJAX_message_die($result_ar);
		exit;
}
$db->clear_cache('cms_');

?>