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
if (!defined('CASH_MOD'))
{
	return;
}

if (!defined('ADMIN_MENU'))
{
	define('ADMIN_MENU', 1);
	function admin_menu(&$menu)
	{
		global $lang;
		$i = 0;
		$j = 0;
		$menu[$i] = new cash_menucat($lang['Cmcat_main']);
		$menu[$i]->additem(new cash_menuitem($j, 'Cash_Configuration', 'cash_config', $lang['Cmenu_cash_config']));
		$menu[$i]->additem(new cash_menuitem($j, 'Cash_Currencies', 'cash_currencies', $lang['Cmenu_cash_currencies']));
		$menu[$i]->additem(new cash_menuitem($j, 'Cash_Forums', 'cash_forums', $lang['Cmenu_cash_forums']));
		$menu[$i]->additem(new cash_menuitem($j, 'Cash_Settings', 'cash_settings', $lang['Cmenu_cash_settings']));
		$i++;
		$menu[$i] = new cash_menucat($lang['Cmcat_addons']);
		$menu[$i]->additem(new cash_menuitem($j, 'Cash_Events', 	'cash_events', $lang['Cmenu_cash_events']));
		$menu[$i]->additem(new cash_menuitem($j, 'Cash_Reset', 'cash_reset', $lang['Cmenu_cash_reset']));
		$i++;
		$menu[$i] = new cash_menucat($lang['Cmcat_other']);
		$menu[$i]->additem(new cash_menuitem($j, 'Cash_Exchange', 'cash_exchange', $lang['Cmenu_cash_exchange']));
		$menu[$i]->additem(new cash_menuitem($j, 'Cash_Groups', 'cash_groups', $lang['Cmenu_cash_groups']));
		$menu[$i]->additem(new cash_menuitem($j, 'Cash_Logs', 'cash_log', $lang['Cmenu_cash_log']));
		$i++;
		$menu[$i] = new cash_menucat($lang['Cmcat_help']);
		$menu[$i]->additem(new cash_menuitem($j, 'Cash_Help', 'cash_help', $lang['Cmenu_cash_help']));
	}
}

if (!empty($navbar) && defined('IN_ICYPHOENIX'))
{
	$menu = array();
	if (!defined('CASH_INCLUDE'))
	{
		message_die(GENERAL_ERROR, 'To enable Cash Mod open <b>includes/constants.php</b> and decomment this line: <b>//define(\'CASH_MOD\', true);</b>.<br /><br />To decomment the line just remove the double slashes //');
	}
	admin_menu($menu);

	$template->set_filenames(array('navbar' => ADM_TPL . 'cash_navbar.tpl'));

	$class = 0;
	for ($i = 0; $i < count($menu); $i++)
	{
		$template->assign_block_vars('navcat',array(
			'L_CATEGORY' => $menu[$i]->category,
			'WIDTH' => $menu[$i]->num()
			)
		);
		for ($j = 0; $j < $menu[$i]->num(); $j++)
		{
			$template->assign_block_vars('navitem', $menu[$i]->items[$j]->data($class + 1, ''));
			$class = ($class + 1) % 2;
		}
	}
	$template->assign_var_from_handle('NAVBAR', 'navbar');
	return;
}

if (!empty($setmodules) && defined('IN_ICYPHOENIX'))
{
	if (empty($table_prefix))
	{
		// jr admin mod
		/*
			since this gets included from within a function,
			and we require these base-scope variables, we
			copy them in from the global scope
		*/
		global $table_prefix, $board_config, $lang;
		/*
		$table_prefix = $GLOBALS['table_prefix'];
		$board_config = $GLOBALS['board_config'];
		$lang = $GLOBALS['lang'];
		*/
	}
	if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
	if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
	include(IP_ROOT_PATH . 'includes/functions_cash.' . PHP_EXT);
	$menu = array();
	admin_menu($menu);

	if ($board_config['cash_adminbig'])
	{
		for ($i = 0; $i < count($menu); $i++)
		{
			for ($j = 0; $j < $menu[$i]->num(); $j++)
			{
				$module['Cash Mod'][$menu[$i]->items[$j]->title] = $menu[$i]->items[$j]->linkage();
				if (($j == $menu[$i]->num() - 1) && !($i == count($menu) - 1))
				{
					$lang[$menu[$i]->items[$j]->title] = $lang[$menu[$i]->items[$j]->title] . '</a></span></td></tr><tr><td class="row2" height="7"><span class="genmed"><a name="cm' . $menu[$i]->num() . '">';
				}
			}
		}
	}
	else
	{
		$file = basename(__FILE__);
		//$module['Cash Mod']['Cash_Admin'] = $file;
		$module['Cash Mod']['Cash_Admin'] = 'cash_main.' . PHP_EXT;;
		$module['Cash Mod']['Cash_Help'] = 'cash_help.' . PHP_EXT;
	}
	return;
}

define('IN_ICYPHOENIX', true);
define('IN_CASHMOD', true);

// Let's set the root dir for phpBB
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('./pagestart.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/functions_selects.' . PHP_EXT);

if (!defined('CASH_MOD'))
{
	message_die(GENERAL_ERROR, 'To enable Cash Mod open <b>includes/constants.php</b> and decomment this line: <b>//define(\'CASH_MOD\', true);</b>.<br /><br />To decomment the line just remove the double slashes //');
}

/*
if ($board_config['cash_adminnavbar'])
{
	$navbar = 1;
	include('./admin_cash.' . PHP_EXT);
}

//$menu = array();
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
		$template->assign_block_vars('menucat.menuitem', $menu[$i]->items[$j]->data(1, ''));
	}
}

$template->pparse('body');

include('./page_footer_admin.' . PHP_EXT);
*/

?>