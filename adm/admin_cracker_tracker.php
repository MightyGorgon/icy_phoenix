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

// Set constants
define('CTRACKER_ACP', true);
define('IN_ICYPHOENIX', true);

/**
* <b>Module Number Documentation:</b>
*
*  1: acp_module_changedfiles.php
*  2: acp_module_credits.php
*  3: acp_module_filescanner.php
*  4: acp_module_globalmessage.php
*  5: acp_module_ipblocker.php
*  6: acp_module_logmanager.php
*  7: acp_module_maintenance.php
*  8: acp_module_miserableuser.php
*  9: acp_module_settings.php
* 10: acp_module_systemrestore.php
* 11: acp_module_footer.php
* 99: acp_module_logmanager.php (including Download of Debug Log)
*/

// Generate the Modules we need
if(!empty($setmodules))
{
	// Module FileScanner: (ID 3) re-enabled...
	$filename = basename(__FILE__);
	$module['2600_CRACKERTRACKER']['ctracker_module_1']  = $filename . '?modu=1';
	$module['2600_CRACKERTRACKER']['ctracker_module_2']  = $filename . '?modu=2';
	$module['2600_CRACKERTRACKER']['ctracker_module_3']  = $filename . '?modu=3';
	$module['2600_CRACKERTRACKER']['ctracker_module_4']  = $filename . '?modu=4';
	$module['2600_CRACKERTRACKER']['ctracker_module_5']  = $filename . '?modu=5';
	$module['2600_CRACKERTRACKER']['ctracker_module_6']  = $filename . '?modu=6';
	$module['2600_CRACKERTRACKER']['ctracker_module_7']  = $filename . '?modu=7';
	$module['2600_CRACKERTRACKER']['ctracker_module_8']  = $filename . '?modu=8';
	$module['2600_CRACKERTRACKER']['ctracker_module_9']  = $filename . '?modu=9';
	$module['2600_CRACKERTRACKER']['ctracker_module_10'] = $filename . '?modu=10';
	$module['2600_CRACKERTRACKER']['ctracker_module_11'] = $filename . '?modu=11';
	$ja_module['2600_CRACKERTRACKER']['ctracker_module_1']  = false;
	$ja_module['2600_CRACKERTRACKER']['ctracker_module_2']  = false;
	$ja_module['2600_CRACKERTRACKER']['ctracker_module_3']  = false;
	$ja_module['2600_CRACKERTRACKER']['ctracker_module_4']  = false;
	$ja_module['2600_CRACKERTRACKER']['ctracker_module_5']  = false;
	$ja_module['2600_CRACKERTRACKER']['ctracker_module_6']  = false;
	$ja_module['2600_CRACKERTRACKER']['ctracker_module_7']  = false;
	$ja_module['2600_CRACKERTRACKER']['ctracker_module_8']  = false;
	$ja_module['2600_CRACKERTRACKER']['ctracker_module_9']  = false;
	$ja_module['2600_CRACKERTRACKER']['ctracker_module_10'] = false;
	$ja_module['2600_CRACKERTRACKER']['ctracker_module_11'] = false;
	return;
}

// phpBB Adminsite
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
$no_page_header  = true;

require('pagestart.' . PHP_EXT);

if (!class_exists('ct_database'))
{
	include(IP_ROOT_PATH . 'includes/ctracker/classes/class_ct_database.' . PHP_EXT);
	$ctracker_config = new ct_database();
}

// Get module number from URL
$module_number = request_var('modu', 0);

// Include CrackerTracker Class Files
include(IP_ROOT_PATH . 'includes/ctracker/classes/class_ct_adminfunctions.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/ctracker/classes/class_ct_userfunctions.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/ctracker/constants.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/ctracker/classes/class_log_manager.' . PHP_EXT);

// Download Debug Log?
if ($module_number == 99)
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
include(IP_ROOT_PATH . ADM . '/page_header_admin.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/ctracker/admin/acp_header.' . PHP_EXT);

// Include requested modules
switch ($module_number)
{
	case 1:
		include(IP_ROOT_PATH . 'includes/ctracker/admin/acp_module_changedfiles.' . PHP_EXT);
		break;
	case 2:
		include(IP_ROOT_PATH . 'includes/ctracker/admin/acp_module_credits.' . PHP_EXT);
		break;
	case 3:
		include(IP_ROOT_PATH . 'includes/ctracker/admin/acp_module_filescanner.' . PHP_EXT);
		break;
	case 4:
		include(IP_ROOT_PATH . 'includes/ctracker/admin/acp_module_globalmessage.' . PHP_EXT);
		break;
	case 5:
		include(IP_ROOT_PATH . 'includes/ctracker/admin/acp_module_ipblocker.' . PHP_EXT);
		break;
	case 6:
		include(IP_ROOT_PATH . 'includes/ctracker/admin/acp_module_logmanager.' . PHP_EXT);
		break;
	case 7:
		include(IP_ROOT_PATH . 'includes/ctracker/admin/acp_module_maintenance.' . PHP_EXT);
		break;
	case 8:
		include(IP_ROOT_PATH . 'includes/ctracker/admin/acp_module_miserableuser.' . PHP_EXT);
		break;
	case 9:
		include(IP_ROOT_PATH . 'includes/ctracker/admin/acp_module_settings.' . PHP_EXT);
		break;
	case 10:
		include(IP_ROOT_PATH . 'includes/ctracker/admin/acp_module_systemrestore.' . PHP_EXT);
		break;
	case 11:
		include(IP_ROOT_PATH . 'includes/ctracker/admin/acp_module_footer.' . PHP_EXT);
		break;
	case 99:
		include(IP_ROOT_PATH . 'includes/ctracker/admin/acp_module_logmanager.' . PHP_EXT);
		break;
	default:
		message_die(GENERAL_MESSAGE, $lang['ctracker_wrong_module']);
		break;
}

// Include default & CrackerTracker Admin Footer
include(IP_ROOT_PATH . 'includes/ctracker/admin/acp_footer.' . PHP_EXT);
include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);

?>