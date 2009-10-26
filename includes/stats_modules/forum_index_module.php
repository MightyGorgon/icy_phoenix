<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

$template->assign_vars(array(
	'CURRENT_TIME' => sprintf($lang['Current_time'], create_date($config['default_dateformat'], time(), $config['board_timezone'])),
	'U_INDEX' => append_sid(CMS_PAGE_HOME),
	'L_STATISTICS' => $lang['Statistics'],
	'L_INDEX' => sprintf($lang['Forum_Index'], htmlspecialchars($config['sitename']))
	)
);

?>