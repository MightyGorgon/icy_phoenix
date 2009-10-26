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
* masterdavid - Ronald John David
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

if(!function_exists('cms_block_sh_visit_counter'))
{
	function cms_block_sh_visit_counter()
	{
		global $db, $config, $template, $lang;
		$sql = "SELECT SUM(reg + hidden + guests) as total
			FROM " . SITE_HISTORY_TABLE;
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);
		$visit_counter = $row['total'];
		$template->assign_vars(array(
			'VISIT_COUNTER' => sprintf($lang['SH_Visit_counter_statement'], $visit_counter, create_date($config['default_dateformat'], $config['board_startdate'], $config['board_timezone'])),
			'L_VISIT_COUNTER' => $lang['Visit_counter']
			)
		);
	}
}

cms_block_sh_visit_counter();

?>