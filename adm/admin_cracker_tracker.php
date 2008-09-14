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

// CTracker_Ignore: File Checked By Human

// Set constants
define('IN_ICYPHOENIX', true);
define('CTRACKER_ACP', true);

/**
 * <b>Module Number Documentation:</b><br /><br />
 *
 *  1:	acp_module_changedfiles.php	<br />
 *  2:	acp_module_credits.php	<br />
 *  3:	acp_module_filescanner.php	<br />
 *  4:	acp_module_globalmessage.php	<br />
 *  5:	acp_module_ipblocker.php	<br />
 *  6:	acp_module_logmanager.php	<br />
 *  7:	acp_module_maintenance.php	<br />
 *  8:	acp_module_miserableuser.php	<br />
 *  9:	acp_module_settings.php	 <br />
 * 10:	acp_module_systemrestore.php	<br />
 * 11:	acp_module_footer.php <br />
 * 99:	acp_module_logmanager.php (including Download of Debug Log) <br />
 */

// Generate the Modules we need
if( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	$module['ctracker_module_category']['ctracker_module_1']  = $filename . '?modu=1';
	$module['ctracker_module_category']['ctracker_module_2']  = $filename . '?modu=2';
	$module['ctracker_module_category']['ctracker_module_3']  = $filename . '?modu=3';
	$module['ctracker_module_category']['ctracker_module_4']  = $filename . '?modu=4';
	$module['ctracker_module_category']['ctracker_module_5']  = $filename . '?modu=5';
	$module['ctracker_module_category']['ctracker_module_6']  = $filename . '?modu=6';
	$module['ctracker_module_category']['ctracker_module_7']  = $filename . '?modu=7';
	$module['ctracker_module_category']['ctracker_module_8']  = $filename . '?modu=8';
	$module['ctracker_module_category']['ctracker_module_9']  = $filename . '?modu=9';
	$module['ctracker_module_category']['ctracker_module_10'] = $filename . '?modu=10';
	$module['ctracker_module_category']['ctracker_module_11'] = $filename . '?modu=11';
	return;
}

// phpBB Adminsite
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
$no_page_header  = true;

require('./pagestart.' . PHP_EXT);

// Get module number from URL
$module_number = $_GET['modu'];

// Include CrackerTracker Class Files
include(IP_ROOT_PATH . 'ctracker/classes/class_ct_adminfunctions.' . PHP_EXT);
include(IP_ROOT_PATH . 'ctracker/classes/class_ct_userfunctions.' . PHP_EXT);
include(IP_ROOT_PATH . 'ctracker/constants.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'ctracker/classes/class_log_manager.' . PHP_EXT);

// Download Debug Log?
if ( $module_number == 99 )
{
	$log_filepath = IP_ROOT_PATH . 'ctracker/logfiles/logfile_debug_mode.txt';
	$size = filesize($log_filepath);
	header('Content-Type: text/plain');
	header('Content-disposition: attachment; filename=logfile_debug_mode.txt');
	header('Content-Length: ' . $size);
	header('Pragma: no-cache');
	header('Expires: 0');
	readfile($log_filepath);
}

// Include default & CrackerTracker Admin Header
include('./page_header_admin.' . PHP_EXT);
include(IP_ROOT_PATH . 'ctracker/admin/acp_header.' . PHP_EXT);

// Include requested modules
switch ( $module_number )
{
	case 1:
		include(IP_ROOT_PATH . 'ctracker/admin/acp_module_changedfiles.' . PHP_EXT);
		break;
	case 2:
		include(IP_ROOT_PATH . 'ctracker/admin/acp_module_credits.' . PHP_EXT);
		break;
	case 3:
		include(IP_ROOT_PATH . 'ctracker/admin/acp_module_filescanner.' . PHP_EXT);
		break;
	case 4:
		include(IP_ROOT_PATH . 'ctracker/admin/acp_module_globalmessage.' . PHP_EXT);
		break;
	case 5:
		include(IP_ROOT_PATH . 'ctracker/admin/acp_module_ipblocker.' . PHP_EXT);
		break;
	case 6:
		include(IP_ROOT_PATH . 'ctracker/admin/acp_module_logmanager.' . PHP_EXT);
		break;
	case 7:
		include(IP_ROOT_PATH . 'ctracker/admin/acp_module_maintenance.' . PHP_EXT);
		break;
	case 8:
		include(IP_ROOT_PATH . 'ctracker/admin/acp_module_miserableuser.' . PHP_EXT);
		break;
	case 9:
		include(IP_ROOT_PATH . 'ctracker/admin/acp_module_settings.' . PHP_EXT);
		break;
	case 10:
		include(IP_ROOT_PATH . 'ctracker/admin/acp_module_systemrestore.' . PHP_EXT);
		break;
	case 11:
		include(IP_ROOT_PATH . 'ctracker/admin/acp_module_footer.' . PHP_EXT);
		break;
	case 99:
		include(IP_ROOT_PATH . 'ctracker/admin/acp_module_logmanager.' . PHP_EXT);
		break;
	default:
		message_die(GENERAL_MESSAGE, $lang['ctracker_wrong_module']);
		break;
}

// Include default & CrackerTracker Admin Footer
include(IP_ROOT_PATH . 'ctracker/admin/acp_footer.' . PHP_EXT);
include('./page_footer_admin.' . PHP_EXT);

?>