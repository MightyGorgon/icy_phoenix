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

/**
* Everything wich CrackerTracker can handle on the Database
*
* @author Christian Knerr (cback)
* @package ctracker
* @version 5.0.0
* @since 26.07.2006 - 13:29:09
* @copyright (c) 2006 www.cback.de
*
*/

// Constant check
if ( !defined('IN_ICYPHOENIX') || !defined('CTRACKER_ACP') )
{
	die('Hacking attempt!');
}


/*
 * Template file association
 */
$template->set_filenames(array('ct_body' => ADM_TPL . 'acp_maintenance.tpl'));


// First we look wich mode the user has selected
$mode = $_GET['mode'];

// Reset used vars
$uplink_values = array();
$chmod_values = array();
$chmod_path = array();
$testvalue = array();
$logmanager = new log_manager();
$operation_err = false;
$mode_selected = false;
$error_message = '';

// Lets test if chmod was set correctly on the logfiles
for($i = 1; $i <= 6; $i++)
{
	$chmod_path[$i] = $logmanager->create_ct_path($i);

	if(is_writeable($chmod_path[$i]) && is_readable($chmod_path[$i]))
	{
		$chmod_values[$i] = true;
	}
	else
	{
		$chmod_values[$i] = false;
	}

	$chmod_path[$i] = str_replace('./../', '', $chmod_path[$i]);
}

// We don't need the logmanager any longer
unset($logmanager);


// Lets see what the new versions are (Uplink) [original code (C) phpBB Group]
if ( $fsock = @fsockopen('www.community.cback.de', 80, $errno, $errstr, 10) )
{
	@fputs($fsock, "GET /uplink/ctracker.txt HTTP/1.1\r\n");
	@fputs($fsock, "HOST: www.community.cback.de\r\n");
	@fputs($fsock, "Connection: close\r\n\r\n");

	$get_info = false;

	while ( !@feof($fsock) )
	{
		if ( $get_info )
		{
			$ctinf .= @fread($fsock, 1024);
		}
		else
		{
			if ( @fgets($fsock, 1024) == "\r\n" )
			{
				$get_info = true;
			} // if
		} // else
	} // while

	@fclose($fsock);
	$uplink_values = explode('|', $ctinf);
}
else
{
	for ( $i = 0; $i <= 4; $i++ )
	{
		$uplink_values[$i] = $lang['ctracker_ma_unknown'];
	}
}


// Engine tests
( defined('protection_unit_one') )  ? $testvalue[1] = $lang['ctracker_ma_active'] : $testvalue[1] = $lang['ctracker_ma_inactive'];
( defined('protection_unit_two') )  ? $testvalue[2] = $lang['ctracker_ma_active'] : $testvalue[2] = $lang['ctracker_ma_inactive'];
( defined('protection_unit_three') )? $testvalue[3] = $lang['ctracker_ma_active'] : $testvalue[3] = $lang['ctracker_ma_inactive'];
( count($ct_rules) >= 260 )         ? $testvalue[4] = $lang['ctracker_ma_active'] : $testvalue[4] = $lang['ctracker_ma_inactive'];

// PHP Version test
if ( @phpversion() >= '5.0.0' )
{
	($uplink_values[2] <= @phpversion())? $testvalue[5] = $lang['ctracker_ma_secure'] : $testvalue[5] = $lang['ctracker_ma_warning'];
}
else
{
	($uplink_values[1] <= @phpversion())? $testvalue[5] = $lang['ctracker_ma_secure'] : $testvalue[5] = $lang['ctracker_ma_warning'];
}

// Safemode and Globals test
$testvalue[6] = strtolower(@ini_get('safe_mode'));
$testvalue[7] = strtolower(@ini_get('register_globals'));


if ( $testvalue[6] == 'on' || $testvalue[6] >= '1' )
{
	$testvalue[6] = $lang['ctracker_ma_on'];
	$testvalue[8] = $lang['ctracker_ma_secure'];
}
elseif ( !isset($testvalue[6]) )
{
	$testvalue[6] = $lang['ctracker_ma_unknown'];
	$testvalue[8] = $lang['ctracker_ma_unknown'];
}
else
{
	$testvalue[6] = $lang['ctracker_ma_off'];
	$testvalue[8] = $lang['ctracker_ma_warning'];
}


if ( $testvalue[7] == 'on' || $testvalue[7] >= '1' )
{
	$testvalue[7] = $lang['ctracker_ma_on'];
	$testvalue[9] = $lang['ctracker_ma_warning'];
}
elseif ( !isset($testvalue[7]) )
{
	$testvalue[7] = $lang['ctracker_ma_unknown'];
	$testvalue[9] = $lang['ctracker_ma_unknown'];
}
else
{
	$testvalue[7] = $lang['ctracker_ma_off'];
	$testvalue[9] = $lang['ctracker_ma_secure'];
}


// Maintenance actions
if ( $mode == '1' )
{
	// Delete all entrys in the CrackerTracker IP Blocker
	$mode_selected = true;
	$sql = 'TRUNCATE ' . CTRACKER_IPBLOCKER;

	if ( !($result = $db->sql_query($sql)) )
	{
		 $operation_err = true;
		 $error_message = __LINE__ . '<br />' . __FILE__ . '<br /><br />' . $sql;
	}
}
elseif ( $mode == '2' )
{
	// Delete all entrys in the CrackerTracker IP Blocker and insert the default values
	$mode_selected = true;
	$sql = 'TRUNCATE ' . CTRACKER_IPBLOCKER;
	if ( !($result = $db->sql_query($sql)) )
	{
		$operation_err = true;
		$error_message = __LINE__ . '<br />' . __FILE__ . '<br /><br />' . $sql;
	}

	$sql = array();
	$sql[] = "INSERT INTO " . CTRACKER_IPBLOCKER . " (`id`, `ct_blocker_value`) VALUES (1, '*WebStripper*');";
	$sql[] = "INSERT INTO " . CTRACKER_IPBLOCKER . " (`id`, `ct_blocker_value`) VALUES (2, '*NetMechanic*');";
	$sql[] = "INSERT INTO " . CTRACKER_IPBLOCKER . " (`id`, `ct_blocker_value`) VALUES (3, '*CherryPicker*');";
	$sql[] = "INSERT INTO " . CTRACKER_IPBLOCKER . " (`id`, `ct_blocker_value`) VALUES (4, '*EmailCollector*');";
	$sql[] = "INSERT INTO " . CTRACKER_IPBLOCKER . " (`id`, `ct_blocker_value`) VALUES (5, '*EmailSiphon*');";
	$sql[] = "INSERT INTO " . CTRACKER_IPBLOCKER . " (`id`, `ct_blocker_value`) VALUES (6, '*WebBandit*');";
	$sql[] = "INSERT INTO " . CTRACKER_IPBLOCKER . " (`id`, `ct_blocker_value`) VALUES (7, '*EmailWolf*');";
	$sql[] = "INSERT INTO " . CTRACKER_IPBLOCKER . " (`id`, `ct_blocker_value`) VALUES (8, '*ExtractorPro*');";
	$sql[] = "INSERT INTO " . CTRACKER_IPBLOCKER . " (`id`, `ct_blocker_value`) VALUES (9, '*SiteSnagger*');";
	$sql[] = "INSERT INTO " . CTRACKER_IPBLOCKER . " (`id`, `ct_blocker_value`) VALUES (10, '*CheeseBot*');";
	$sql[] = "INSERT INTO " . CTRACKER_IPBLOCKER . " (`id`, `ct_blocker_value`) VALUES (11, '*ia_archiver*');";
	$sql[] = "INSERT INTO " . CTRACKER_IPBLOCKER . " (`id`, `ct_blocker_value`) VALUES (12, '*Website Quester*');";
	$sql[] = "INSERT INTO " . CTRACKER_IPBLOCKER . " (`id`, `ct_blocker_value`) VALUES (13, '*WebZip*');";
	$sql[] = "INSERT INTO " . CTRACKER_IPBLOCKER . " (`id`, `ct_blocker_value`) VALUES (14, '*moget*');";
	$sql[] = "INSERT INTO " . CTRACKER_IPBLOCKER . " (`id`, `ct_blocker_value`) VALUES (15, '*WebSauger*');";
	$sql[] = "INSERT INTO " . CTRACKER_IPBLOCKER . " (`id`, `ct_blocker_value`) VALUES (16, '*WebCopier*');";
	$sql[] = "INSERT INTO " . CTRACKER_IPBLOCKER . " (`id`, `ct_blocker_value`) VALUES (17, '*WWW-Collector*');";
	$sql[] = "INSERT INTO " . CTRACKER_IPBLOCKER . " (`id`, `ct_blocker_value`) VALUES (18, '*InfoNaviRobot*');";
	$sql[] = "INSERT INTO " . CTRACKER_IPBLOCKER . " (`id`, `ct_blocker_value`) VALUES (19, '*Harvest*');";
	$sql[] = "INSERT INTO " . CTRACKER_IPBLOCKER . " (`id`, `ct_blocker_value`) VALUES (20, '*Bullseye*');";
	$sql[] = "INSERT INTO " . CTRACKER_IPBLOCKER . " (`id`, `ct_blocker_value`) VALUES (21, '*LinkWalker*');";
	$sql[] = "INSERT INTO " . CTRACKER_IPBLOCKER . " (`id`, `ct_blocker_value`) VALUES (22, '*LinkextractorPro*');";
	$sql[] = "INSERT INTO " . CTRACKER_IPBLOCKER . " (`id`, `ct_blocker_value`) VALUES (23, '*WebProxy*');";
	$sql[] = "INSERT INTO " . CTRACKER_IPBLOCKER . " (`id`, `ct_blocker_value`) VALUES (24, '*BlowFish*');";
	$sql[] = "INSERT INTO " . CTRACKER_IPBLOCKER . " (`id`, `ct_blocker_value`) VALUES (25, '*WebEnhancer*');";
	$sql[] = "INSERT INTO " . CTRACKER_IPBLOCKER . " (`id`, `ct_blocker_value`) VALUES (26, '*TightTwatBot*');";
	$sql[] = "INSERT INTO " . CTRACKER_IPBLOCKER . " (`id`, `ct_blocker_value`) VALUES (27, '*LinkScan*');";
	$sql[] = "INSERT INTO " . CTRACKER_IPBLOCKER . " (`id`, `ct_blocker_value`) VALUES (28, '*WebDownloader*');";
	$sql[] = "INSERT INTO " . CTRACKER_IPBLOCKER . " (`id`, `ct_blocker_value`) VALUES (29, 'lwp');";
	$sql[] = "INSERT INTO " . CTRACKER_IPBLOCKER . " (`id`, `ct_blocker_value`) VALUES (30, '*BruteForce*');";
	$sql[] = "INSERT INTO " . CTRACKER_IPBLOCKER . " (`id`, `ct_blocker_value`) VALUES (31, 'lwp-*');";
	$sql[] = "INSERT INTO " . CTRACKER_IPBLOCKER . " (`id`, `ct_blocker_value`) VALUES (32, '*anonym*');";

	for ( $i = 0; $i < count($sql); $i++ )
	{
		if ( !$operation_err && !($result = $db->sql_query($sql[$i])) )
		{
			$operation_err = true;
			$error_message = __LINE__ . '<br />' . __FILE__ . '<br /><br />' . $sql[$i];
		}
	}

}
elseif ( $mode == '3' )
{
	// Delete all entrys from Login-History
	$mode_selected = true;
	$sql = 'TRUNCATE ' . CTRACKER_LOGINHISTORY;

	if ( !($result = $db->sql_query($sql)) )
	{
		$operation_err = true;
		$error_message = __LINE__ . '<br />' . __FILE__ . '<br /><br />' . $sql;
	}
}
elseif ( $mode == '4' )
{
	// Delete all entrys from Hashsum Checker
	$mode_selected = true;
	$sql = 'TRUNCATE ' . CTRACKER_FILECHK;

	if ( !($result = $db->sql_query($sql)) )
	{
		$operation_err = true;
		$error_message = __LINE__ . '<br />' . __FILE__ . '<br /><br />' . $sql;
	}
}
elseif ( $mode == '5' )
{
	// Delete all entrys from CrackerTracker Filescanner
	$mode_selected = true;
	$sql = 'TRUNCATE ' . CTRACKER_FILESCANNER;

	if ( !($result = $db->sql_query($sql)) )
	{
		$operation_err = true;
		$error_message = __LINE__ . '<br />' . __FILE__ . '<br /><br />' . $sql;
	}
}


/*
 * Info or Errorbox
 */
if ( $mode_selected && !$operation_err )
{
	// Successful
	$template->assign_block_vars('infobox', array());
}
elseif ( $mode_selected && $operation_err )
{
	// Error on query (replaces message_die() error on this place)
	$template->assign_block_vars('errorbox', array(
		'ERR_MSG' => $error_message
		)
	);
}

/*
 * Send many, many vars to the template
 */
$template->assign_vars(array(
		'L_HEADLINE' => $lang['ctracker_ma_head'],
		'L_SUBHEADLINE' => $lang['ctracker_ma_subhead'],
		'L_SYSTEMTEST' => $lang['ctracker_ma_systest'],
		'L_MAINTENANCE' => $lang['ctracker_ma_maint'],
		'L_SECTEST' => $lang['ctracker_ma_sectest'],
		'L_NAME_1' => $lang['ctracker_ma_name_1'],
		'L_NAME_2' => $lang['ctracker_ma_name_2'],
		'L_NAME_3' => $lang['ctracker_ma_name_3'],
		'L_NAME_4' => sprintf($lang['ctracker_ma_name_4'], count($ct_rules) + count($ct_spammer_def) + count($ct_mailscn_def) + count($ct_userspm_def) + $ctracker_config->blocklist_count),
		'L_VAL_1' => $testvalue[1],
		'L_VAL_2' => $testvalue[2],
		'L_VAL_3' => $testvalue[3],
		'L_VAL_4' => $testvalue[4],
		'L_SYSHEAD_1' => $lang['ctracker_ma_syshead_1'],
		'L_SYSHEAD_2' => $lang['ctracker_ma_syshead_2'],
		'L_SEC_HEAD_1' => $lang['ctracker_ma_seccheck_1'],
		'L_SEC_HEAD_2' => $lang['ctracker_ma_seccheck_2'],
		'L_SEC_HEAD_3' => $lang['ctracker_ma_seccheck_3'],
		'L_SEC_HEAD_4' => $lang['ctracker_ma_seccheck_4'],

		'L_NAME_5' => $lang['ctracker_ma_chmod'] . $chmod_path[1],
		'L_VAL_5' => ($chmod_values[1] == 1)? $lang['ctracker_ma_ca'] : $lang['ctracker_ma_ci'],
		'L_NAME_6' => $lang['ctracker_ma_chmod'] . $chmod_path[2],
		'L_VAL_6' => ($chmod_values[2] == 1)? $lang['ctracker_ma_ca'] : $lang['ctracker_ma_ci'],
		'L_NAME_7' => $lang['ctracker_ma_chmod'] . $chmod_path[3],
		'L_VAL_7' => ($chmod_values[3] == 1)? $lang['ctracker_ma_ca'] : $lang['ctracker_ma_ci'],
		'L_NAME_8' => $lang['ctracker_ma_chmod'] . $chmod_path[4],
		'L_VAL_8' => ($chmod_values[4] == 1)? $lang['ctracker_ma_ca'] : $lang['ctracker_ma_ci'],
		'L_NAME_9' => $lang['ctracker_ma_chmod'] . $chmod_path[5],
		'L_VAL_9' => ($chmod_values[5] == 1)? $lang['ctracker_ma_ca'] : $lang['ctracker_ma_ci'],
		'L_NAME_10' => $lang['ctracker_ma_chmod'] . $chmod_path[6],
		'L_VAL_10' => ($chmod_values[6] == 1)? $lang['ctracker_ma_ca'] : $lang['ctracker_ma_ci'],

		'L_SEC_INFO_1' => $lang['ctracker_ma_scheck_1'],
		'L_SEC_INFO_V1' => @phpversion(),
		'L_SEC_INFO_OV1' => (@phpversion() >= '5.0.0')? $uplink_values[2] : $uplink_values[1],
		'L_SEC_INFO_D1' => $testvalue[5],

		'L_SEC_INFO_2' => $lang['ctracker_ma_scheck_2'],
		'L_SEC_INFO_V2' => $testvalue[6],
		'L_SEC_INFO_OV2' => $lang['ctracker_ma_on'],
		'L_SEC_INFO_D2' => $testvalue[8],

		'L_SEC_INFO_3' => $lang['ctracker_ma_scheck_3'],
		'L_SEC_INFO_V3' => $testvalue[7],
		'L_SEC_INFO_OV3' => $lang['ctracker_ma_off'],
		'L_SEC_INFO_D3' => $testvalue[9],

		'L_SEC_INFO_4' => $lang['ctracker_ma_scheck_4'],
		'L_SEC_INFO_V4' => '2' . $board_config['version'],
		'L_SEC_INFO_OV4' => $uplink_values[3],
		'L_SEC_INFO_D4' => ('2' . $board_config['version'] >= $uplink_values[3])? $lang['ctracker_ma_secure'] : $lang['ctracker_ma_warning'],

		'L_SEC_INFO_4a' => $lang['ctracker_ma_scheck_4a'],
		'L_SEC_INFO_V4a' => ($board_config['enable_confirm'] == 1)? $lang['ctracker_ma_on'] : $lang['ctracker_ma_off'],
		'L_SEC_INFO_OV4a'=> $lang['ctracker_ma_on'],
		'L_SEC_INFO_D4a' => ($board_config['enable_confirm'] == 1)? $lang['ctracker_ma_secure'] : $lang['ctracker_ma_warning'],

		'L_SEC_INFO_4b' => $lang['ctracker_ma_scheck_4b'],
		'L_SEC_INFO_V4b' => ($board_config['require_activation'] > 0)? $lang['ctracker_ma_on'] : $lang['ctracker_ma_off'],
		'L_SEC_INFO_OV4b'=> $lang['ctracker_ma_on'],
		'L_SEC_INFO_D4b' => ($board_config['require_activation'] > 0)? $lang['ctracker_ma_secure'] : $lang['ctracker_ma_warning'],

		'L_SEC_INFO_5' => $lang['ctracker_ma_scheck_5'],
		'L_SEC_INFO_V5' => CTRACKER_VERSION,
		'L_SEC_INFO_OV5' => $uplink_values[0],
		'L_SEC_INFO_D5' => (CTRACKER_VERSION >= $uplink_values[0])? $lang['ctracker_ma_secure'] : $lang['ctracker_ma_warning'],

		'S_BUILD_LINK_1' => append_sid('admin_cracker_tracker.' . PHP_EXT . '?modu=7&mode=1'),
		'S_BUILD_LINK_2' => append_sid('admin_cracker_tracker.' . PHP_EXT . '?modu=7&mode=2'),
		'S_BUILD_LINK_3' => append_sid('admin_cracker_tracker.' . PHP_EXT . '?modu=7&mode=3'),
		'S_BUILD_LINK_4' => append_sid('admin_cracker_tracker.' . PHP_EXT . '?modu=7&mode=4'),
		'S_BUILD_LINK_5' => append_sid('admin_cracker_tracker.' . PHP_EXT . '?modu=7&mode=5'),

		'L_DESC_1' => $lang['ctracker_ma_desc1'],
		'L_DESC_2' => $lang['ctracker_ma_desc2'],
		'L_DESC_3' => $lang['ctracker_ma_desc3'],
		'L_DESC_4' => $lang['ctracker_ma_desc4'],
		'L_DESC_5' => $lang['ctracker_ma_desc5'],

		'L_LINK_DESC' => $lang['ctracker_ma_desc_link'],

		'L_OK_MESSAGE' => $lang['ctracker_ma_succ_main'],
		'L_ERR_MESSAGE' => $lang['ctracker_ma_err_main'],
		)
	);

// Generate the page
$template->pparse('ct_body');

?>