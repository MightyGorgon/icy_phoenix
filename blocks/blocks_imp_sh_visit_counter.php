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

if(!function_exists('imp_sh_visit_counter_block_func'))
{
	function imp_sh_visit_counter_block_func()
	{
		global $db, $template, $lang, $board_config;
		$sql = "SELECT SUM(reg + hidden + guests) as total
			FROM " . SITE_HISTORY_TABLE;
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, "Couldn't retrieve site history", "", __LINE__, __FILE__, $sql);
		}
		$row = $db->sql_fetchrow($result);
		$visit_counter = $row['total'];
		$template->assign_vars(array(
			'VISIT_COUNTER' => sprintf($lang['SH_Visit_counter_statement'], $visit_counter, create_date($board_config['default_dateformat'], $board_config['board_startdate'], $board_config['board_timezone'])),
			'L_VISIT_COUNTER' => $lang['Visit_counter']
			)
		);
	}
}

imp_sh_visit_counter_block_func();

?>