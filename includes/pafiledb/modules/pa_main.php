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
* Todd - (todd@phparena.net) - (http://www.phparena.net)
*
*/

class pafiledb_main extends pafiledb_public
{
	function main($action)
	{
		global $template, $pafiledb_config, $debug, $lang, $config, $theme, $images;
		$template->assign_vars(array(
			'L_HOME' => $lang['Home'],
			'CURRENT_TIME' => sprintf($lang['Current_time'], create_date($config['default_dateformat'], time(), $config['board_timezone'])),
			'TPL_COLOR' => $theme['body_background'],
			'U_INDEX' => append_sid(CMS_PAGE_HOME),
			'U_DOWNLOAD' => append_sid('dload.' . PHP_EXT),

			'CAT_BLOCK_IMG' => $images['category_block'],
			'SPACER' => $images['spacer'],

			'DOWNLOAD' => $pafiledb_config['settings_dbname'],
			'TREE' => $menu_output
			)
		);

		//===================================================
		// Show the Category for the download database index
		//===================================================
		$this->category_display();

		$this->display($lang['Download'], 'pa_main_body.tpl');
	}
}

?>