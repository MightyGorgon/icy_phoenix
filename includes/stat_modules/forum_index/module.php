<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

if (!defined('IN_PHPBB'))
{
	die('Hacking attempt');
}

$template->assign_vars(array(
	'CURRENT_TIME' => sprintf($lang['Current_time'], create_date($board_config['default_dateformat'], time(), $board_config['board_timezone'])),
	'U_INDEX' => append_sid(PORTAL_MG),
	'L_STATISTICS' => $lang['Statistics'],
	'L_INDEX' => sprintf($lang['Forum_Index'], $board_config['sitename']))
);

?>