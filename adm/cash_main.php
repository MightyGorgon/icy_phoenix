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
* Xore (mods@xore.ca)
*
*/

// CTracker_Ignore: File checked by human
define('IN_PHPBB', true);
define('IN_CASHMOD', true);

$phpbb_root_path = './../';
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);
include($phpbb_root_path . 'includes/functions_selects.' . $phpEx);

if ($board_config['cash_adminnavbar'])
{
	$navbar = 1;
	include('./admin_cash.' . $phpEx);
}

admin_menu($menu);

$template->set_filenames(array('body' => ADM_TPL . 'cash_menu.tpl'));

for ($i = 0; $i < count($menu); $i++)
{
	$template->assign_block_vars('menucat',array(
		'L_CATEGORY' => $menu[$i]->category
		)
	);
	for ($j = 0; $j < $menu[$i]->num(); $j++)
	{
		$template->assign_block_vars('menucat.menuitem', $menu[$i]->items[$j]->data($phpEx, 1, ''));
	}
}

$template->pparse('body');

include('./page_footer_admin.' . $phpEx);

?>