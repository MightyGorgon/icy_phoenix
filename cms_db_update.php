<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.' . $phpEx);
include($phpbb_root_path . 'cms/constants.' . $phpEx);

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
$mode = '';
if (isset($_POST['mode']) || isset($_GET['mode']))
{
	$mode = (isset($_POST['mode'])) ? $_POST['mode'] : $_GET['mode'];
}

$cms_type = '';
if (isset($_POST['cms_type']) || isset($_GET['cms_type']))
{
	$cms_type = (isset($_POST['cms_type'])) ? $_POST['cms_type'] : $_GET['cms_type'];
}

$type = '';
if (isset($_POST['type']) || isset($_GET['type']))
{
	$type = (isset($_POST['type'])) ? $_POST['type'] : $_GET['type'];
}

$b_id = 0;
if (isset($_POST['b_id']) || isset($_GET['b_id']))
{
	$b_id = (isset($_POST['b_id'])) ? intval($_POST['b_id']) : intval($_GET['b_id']);
}
$b_id = ($b_id < 0) ? 0 : $b_id;

$cat = 0;
if (isset($_POST['cat']) || isset($_GET['cat']))
{
	$cat = (isset($_POST['cat'])) ? intval($_POST['cat']) : intval($_GET['cat']);
}
$cat = ($cat < 0) ? 0 : $cat;

$m_id = 0;
if (isset($_POST['m_id']) || isset($_GET['m_id']))
{
	$m_id = (isset($_POST['m_id'])) ? intval($_POST['m_id']) : intval($_GET['m_id']);
}
$m_id = ($m_id < 0) ? 0 : $m_id;

$old_status = '0';
if (isset($_POST['status']) || isset($_GET['status']))
{
	$old_status = (isset($_POST['status'])) ? intval($_POST['status']) : intval($_GET['status']);
}
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