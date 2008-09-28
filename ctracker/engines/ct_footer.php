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
* This file outputs the footer with attack counter and so on.
* After this File we don't need the CrackerTracker Settings Object
* anymore so this file ends up with unsetting this Object.
*
*
* @author Christian Knerr (cback)
* @package ctracker
* @version 5.0.0
* @since 15.07.2006 - 21:36:24
* @copyright (c) 2006 www.cback.de
*
*/

if(!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt!');
}

/**
 * <b>create_footer_layout</b>
 * Generates the CrackerTracker Footer with or without the Counter value.
 *
 * @param $ct_gfn (Integer) Footer Layout Identification Number
 * @return $ctracker_footer_template (String) HTML Code for the footer Template
 * 											  output
 */
function create_footer_layout($ct_gfn)
{
	// Globals we need
	global $images, $lang;

	// Reset used vars
	$counter_value_now 		  = 0;
	$ctracker_footer_template = '';

	// Secure $ct_gfn
	$ct_gfn = intval($ct_gfn);

	/*
	 * Do we need a counter for the footer?
	 */
	if ( $ct_gfn == 3 || $ct_gfn == 4 || $ct_gfn == 6 || $ct_gfn == 7 || $ct_gfn == 8 )
	{
		include_once(IP_ROOT_PATH . 'ctracker/classes/class_log_manager.' . PHP_EXT);
		$footer_mgr = new log_manager();
		$footer_mgr->get_counter_value();
		$counter_value_now = $footer_mgr->ct_counter_value;
		unset($footer_mgr);
	}

	$footer_mini = '<a href="http://www.cback.de" target="_blank"><img src="' . $images['ctracker_footer_s'] . '" title="' . $lang['ctracker_fdisplay_imgdesc'] . '" alt="' . $lang['ctracker_fdisplay_imgdesc'] . '" border="0" align="middle" /></a>';
	$footer_big  = '<a href="http://www.cback.de" target="_blank"><img src="' . $images['ctracker_footer_b'] . '" title="' . $lang['ctracker_fdisplay_imgdesc'] . '" alt="' . $lang['ctracker_fdisplay_imgdesc'] . '" border="0" align="middle" /></a>';

	switch( $ct_gfn )
	{
		case 1: $ctracker_footer_template = $footer_mini;
			break;

		case 2: $ctracker_footer_template = $footer_big;
			break;

		//case 3: $ctracker_footer_template = $footer_mini . '&nbsp;' . sprintf($lang['ctracker_fdisplay_g'], $counter_value_now);
		//case 3: $ctracker_footer_template = $footer_mini . '<br />' . sprintf($lang['ctracker_fdisplay_g'], $counter_value_now);
		case 3: $ctracker_footer_template = sprintf($lang['ctracker_fdisplay_g'], $counter_value_now) . '<br />' . $footer_mini;
			break;

		case 4: $ctracker_footer_template = $footer_big . '<br />' . sprintf($lang['ctracker_fdisplay_g'], $counter_value_now);
			break;

		case 5: $ctracker_footer_template = $lang['ctracker_fdisplay_n'];
			break;

		case 6: $ctracker_footer_template = sprintf($lang['ctracker_fdisplay_c'], $counter_value_now);
			break;

		case 7: $ctracker_footer_template = 'CrackerTracker &copy; 2004 - ' . date('Y') . ' <a href="http://www.cback.de" target="_blank">CBACK.de</a>';
			break;

		case 8: $ctracker_footer_template = '<a href="http://www.cback.de" target="_blank">' . sprintf($lang['ctracker_fdisplay_g'], $counter_value_now) . '</a>';
			break;

		default: $ctracker_footer_template = $footer_mini . '<br />CrackerTracker &copy; 2004 - ' . date(Y) . ' <a href="http://www.cback.de" target="_blank">CBACK.de</a>';
			break;
	}

	return $ctracker_footer_template;
}

?>