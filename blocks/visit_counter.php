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

if(!function_exists('cms_block_visit_counter'))
{
	function cms_block_visit_counter()
	{
		global $config, $template, $lang;
		$template->assign_vars(array(
			'VISIT_COUNTER' => sprintf($lang['Visit_counter_statement'], $config['visit_counter'] + 1, create_date($config['default_dateformat'], $config['board_startdate'], $config['board_timezone'])),
			'L_VISIT_COUNTER' => $lang['Visit_counter']
			)
		);
	}
}

cms_block_visit_counter();

?>