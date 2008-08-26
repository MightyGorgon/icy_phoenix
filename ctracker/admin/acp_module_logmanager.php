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
* Watch and Manage Log Files CrackerTracker creates
*
* @author Christian Knerr (cback) and Tekin Birdüzen (cYbercOsmOnauT)
* @package ctracker
* @version 5.0.6
* @since 26.07.2006 - 13:29:09
* @copyright (c) 2006 www.cback.de
*
*/

// Constant check
if ( !defined('IN_PHPBB') || !defined('CTRACKER_ACP') )
{
	die('Hacking attempt!');
}

/*
 * Template assignment
 */
$template->set_filenames(array('ct_body' => ADM_TPL . 'acp_logmanager.tpl'));


$logmanager = new log_manager();
$mode       = $_GET['mode'];
$logid      = $_GET['logid'];

if ( $mode == 'delete' )
{
	if ($logid != 6)
	{
		// Do not increment the counter for debug entrys
		$logmanager->increment_counter($logmanager->check_log_size($logid));
	}
	$logmanager->delete_logfile($logid);

	$template->assign_block_vars('infobox', array(
		'L_MESSAGE_TEXT'	=> $lang['ctracker_log_manager_deleted']
		)
	);
}
elseif ( $mode == 'delete_all' )
{
	for($i = 2; $i <= 6; $i++)
	{
		if ($i != 6)
		{
			// Do not increment the counter for debug entrys
			$logmanager->increment_counter($logmanager->check_log_size($i));
		}
		$logmanager->delete_logfile($i);
	}

	$template->assign_block_vars('infobox', array(
			'L_MESSAGE_TEXT'	=> $lang['ctracker_log_manager_all_deleted'])
	);
}
elseif ( ($mode == 'view') || ($mode == 'downloaddebug') )
{
	// Header for logfile output

	if ($logid == 6)
	{
		$filename = file($logmanager->create_ct_path($logid));
		$a = 0;
		$lastclean = 0;

		echo('<tt>');
		for ( $i = count($filename) - 1; $i >= 0; $i-- )
		{
			echo($filename[$i] . "<br />\n");
		}
		echo('</tt>');
		//readfile($logmanager->create_ct_path($logid));
		exit;
	}

	// cell 3 has another description if it is the malformed login log
	( $logid == 4 || $logid == 5 )? $description_cell_2 = $lang['ctracker_log_manager_cell_2b'] : $description_cell_2 = $lang['ctracker_log_manager_cell_2a'];

	$template->assign_block_vars('show_log_header', array(
		'L_MESSAGE_TEXT'	=> ($logmanager->check_log_size($logid) == 1)? sprintf($lang['ctracker_log_manager_showheader1'], append_sid('admin_cracker_tracker.' . $phpEx . '?modu=6')) : sprintf($lang['ctracker_log_manager_showheader'], $logmanager->check_log_size($logid), append_sid('admin_cracker_tracker.' . $phpEx . '?modu=6')),
		'L_LOG_SHOW'		=> $lang['ctracker_log_manager_showlog'],
		'L_LOGCELL1'		=> $lang['ctracker_log_manager_cell_1'],
		'L_LOGCELL2'		=> $description_cell_2,
		'L_LOGCELL3'		=> $lang['ctracker_log_manager_cell_3'],
		'L_LOGCELL4'		=> $lang['ctracker_log_manager_cell_4'],
		'L_LOGCELL5'		=> $lang['ctracker_log_manager_cell_5'],
		'L_LOGCELL6'		=> $lang['ctracker_log_manager_cell_6']
		)
	);

	// Template Loop for Logfile output and naturally logfile output itself
	$filename = file($logmanager->create_ct_path($logid));
	$a = 0;
	$lastclean = 0;

	for ( $i = count($filename) - 1; $i >= 0; $i-- )
	{
		define('SPLIT', '|||');		// File Token
		$line = explode(SPLIT, $filename[$i]);

		if ( $line[0] == 1 )
		{
			$lastclean = intval($line[1]);

			$template->assign_block_vars('show_system_message', array(
				'L_SYS_MSG'		=>	sprintf($lang['ctracker_log_manager_sysmsg'], date($board_config['default_dateformat'], $lastclean)),
				'L_DELETE'		=> $lang['ctracker_log_manager_delete'],
				'S_DELETE'		=> append_sid('admin_cracker_tracker.' . $phpEx . '?modu=6&logid=' . $logid . '&mode=delete')
				)
			);
		}
		else
		{
			$a++;								// Counter-Value
			$entrytime = intval($line[1]);		// Entry Timestamp

			$template->assign_block_vars('show_log', array(
				'TABLE_CLASS'	=> ( $i % 2 == 0)? 'row1' : 'row2',
				'L_OUTPUT_1'	=> date($board_config['default_dateformat'], $entrytime),
				'L_OUTPUT_2'	=> htmlspecialchars($line[2]),
				'L_OUTPUT_3'	=> htmlspecialchars($line[3]),
				'L_OUTPUT_4'	=> htmlspecialchars($line[4]),
				'L_OUTPUT_5'	=> htmlspecialchars($line[5]),
				'L_OUTPUT_6'	=> htmlspecialchars($line[6]),
				'L_NUMBER'		=> $a
				)
			);
		}
	}

	// Footer for logfile output
	$template->assign_block_vars('show_log_footer', array());
}

if ( $mode != 'view')
{
	$template->assign_block_vars('overview', array(
		'L_OVERVIEW'			=> $lang['ctracker_log_manager_overview'],
		'L_COUNTER_VALUE'	=> sprintf($lang['ctracker_log_manager_blocked'], $logmanager->get_counter_value()),
		'L_LOG_OVERVIEW'	=> $lang['ctracker_log_manager_overview'],
		'L_LOGHEAD_1'			=> $lang['ctracker_log_manager_head1'],
		'L_LOGHEAD_2'			=> $lang['ctracker_log_manager_head2'],
		'L_LOGHEAD_3'			=> $lang['ctracker_log_manager_head3'],
		'L_LOGNAME_2'			=> $lang['ctracker_log_manager_name2'],
		'L_LOGNAME_3'			=> $lang['ctracker_log_manager_name3'],
		'L_LOGNAME_4'			=> $lang['ctracker_log_manager_name4'],
		'L_LOGNAME_5'			=> $lang['ctracker_log_manager_name5'],
		'L_LOGNAME_6'			=> $lang['ctracker_log_manager_name6'],
		'L_VIEW'					=> $lang['ctracker_log_manager_view'],
		'L_DELETE'				=> $lang['ctracker_log_manager_delete'],
		'L_DELETE_ALL'		=> $lang['ctracker_log_manager_delete_all'],

		'S_VIEW_2'				=> append_sid('admin_cracker_tracker.' . $phpEx . '?modu=6&logid=2&mode=view'),
		'S_DELETE_2'			=> append_sid('admin_cracker_tracker.' . $phpEx . '?modu=6&logid=2&mode=delete'),
		'S_VIEW_3'				=> append_sid('admin_cracker_tracker.' . $phpEx . '?modu=6&logid=3&mode=view'),
		'S_DELETE_3'			=> append_sid('admin_cracker_tracker.' . $phpEx . '?modu=6&logid=3&mode=delete'),
		'S_VIEW_4'				=> append_sid('admin_cracker_tracker.' . $phpEx . '?modu=6&logid=4&mode=view'),
		'S_DELETE_4'			=> append_sid('admin_cracker_tracker.' . $phpEx . '?modu=6&logid=4&mode=delete'),
		'S_VIEW_5'				=> append_sid('admin_cracker_tracker.' . $phpEx . '?modu=6&logid=5&mode=view'),
		'S_DELETE_5'			=> append_sid('admin_cracker_tracker.' . $phpEx . '?modu=6&logid=5&mode=delete'),
		//'S_VIEW_6'				=> $phpbb_root_path . 'ctracker/logfiles/logfile_debug_mode.txt',
		'S_VIEW_6'				=> append_sid('admin_cracker_tracker.' . $phpEx . '?modu=6&logid=6&mode=view'),
		'S_DELETE_6'			=> append_sid('admin_cracker_tracker.' . $phpEx . '?modu=6&logid=6&mode=delete'),
		'S_VIEW_7'				=> append_sid('admin_cracker_tracker.' . $phpEx . '?modu=99&mode=downloaddebug'),

		'S_DELETE_FORM'		=> append_sid('admin_cracker_tracker.' . $phpEx . '?modu=6&mode=delete_all'),

		'S_LOGVALUE_2'		=> $logmanager->check_log_size(2),
		'S_LOGVALUE_3'		=> $logmanager->check_log_size(3),
		'S_LOGVALUE_4'		=> $logmanager->check_log_size(4),
		'S_LOGVALUE_5'		=> $logmanager->check_log_size(5),
		'S_LOGVALUE_6'		=> $logmanager->check_log_size(6)
		)
	);
}

/*
* Send some vars to the template
*/
$template->assign_vars(array(
	'IMG_ICON'		=> $phpbb_root_path . $images['ctracker_log_manager'],
	'L_HEADLINE'	=> $lang['ctracker_log_manager_title'],
	'L_SUBHEADLINE'	=> $lang['ctracker_log_manager_subtitle']
	)
);


// Generate the page
$template->pparse('ct_body');

?>