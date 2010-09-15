<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

define('CTRACKER_DISABLED', true);
define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);

$config['jquery_ui'] = true;

// Define constant to keep page_header.php from sending headers
define('AJAX_HEADERS', true);

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

// We need to add a USER_ID check-in (passed via GET) to give proper AUTH to non admin users to edit only own pages

// Get SID and check it
$sid = request_var('sid', '');
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
			$result = $db->sql_query($sql);
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
				$result = $db->sql_query($sql);
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
				$result = $db->sql_query($sql);
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
				$result = $db->sql_query($sql);
			}
			$cache->destroy('_smileys');
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

$cache->destroy('_cms_layouts_config');
empty_cache_folders(CMS_CACHE_FOLDER);

?>