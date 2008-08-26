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
* The Footer wich we can display in all ACP Modules
*
* @author Christian Knerr (cback)
* @package ctracker
* @version 5.0.0
* @since 26.07.2006 - 13:29:09
* @copyright (c) 2006 www.cback.de
*
*/

// Constant check
if ( !defined('IN_PHPBB') || !defined('CTRACKER_ACP') )
{
	die('Hacking attempt!');
}


$template->set_filenames(array('ct_footer' => ADM_TPL . 'acp_footer.tpl'));


// Send some vars to the template
$template->assign_vars(array(
	'L_YEAR'     => date(Y),
	'L_VERSION'  => CTRACKER_VERSION
	)
);


// Generate the page
$template->pparse('ct_footer');


// Unset CTracker Config Object
unset($ctracker_config);


?>