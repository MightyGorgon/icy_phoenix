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
* (c) 2005 oxpus (Karsten Ude) <webmaster@oxpus.de> http://www.oxpus.de
* (c) hotschi / demolition fabi / oxpus
*
*/

define('IN_ICYPHOENIX', true);

if(!empty($setmodules))
{
	$file = basename(__FILE__);
	$module['2050_Downloads']['100_DL_Settings'] = $file;
	return;
}

// Let's set the root dir for phpBB
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('./pagestart.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_post.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'language/lang_' . $board_config['default_lang'] . '/lang_downloads.' . PHP_EXT);

/*
* include and create the main class
*/
if ($_GET['action'] == 'edit')
{
	$enable_desc = $enable_rule = true;
}
include(IP_ROOT_PATH . DL_ROOT_PATH . 'classes/class_dlmod.' . PHP_EXT);
$dl_mod = new dlmod();
$dl_config = array();
$dl_config = $dl_mod->get_config();

/*
* load default template
*/
$template->set_filenames(array('body' => ADM_TPL . 'downloads_main.tpl'));

/*
* init mod config
*/
$dl_admin_path = IP_ROOT_PATH . DL_ROOT_PATH . 'admin/';

/*
* build download management page
*/
$u_dl_modules = array(
	0 => 'config',
	1 => 'traffic',
	2 => 'categories',
	3 => 'files',
	4 => 'stats',
);

$l_dl_modules = array(
	0 => $lang['Dl_acp_config_management'],
	1 => $lang['Dl_acp_traffic_management'],
	2 => $lang['Dl_acp_categories_management'],
	3 => $lang['Dl_acp_files_management'],
	4 => $lang['Dl_acp_stats_management'],
);

$i_dl_modules = array(
	0 => $images['Dl_acp_config'],
	1 => $images['Dl_acp_traffic'],
	2 => $images['Dl_acp_categories'],
	3 => $images['Dl_acp_files'],
	4 => $images['Dl_acp_stats'],
);

if (count($u_dl_modules) <> count($l_dl_modules) || count($u_dl_modules) <> count($i_dl_modules))
{
	message_die(GENERAL_MESSAGE, 'Error on preparing Download MOD modules!');
}

/*
* shorten the menu if there are no cats and/or files
*/
$menu_width = count($u_dl_modules);
$index = array();
$index = $dl_mod->full_index(0, 0, 0, 1);
$file_manage = true;
$stat_manage = true;

if (!count($index))
{
	$menu_width -= 2;
	$file_manage = 0;
	$stat_manage = 0;
}
else
{
	$dl_files = $dl_mod->all_files(0, '', 'ASC', 'LIMIT 1');
	if (!count($dl_files))
	{
		$menu_width--;
		$stat_manage = 0;
	}
	else
	{
		$template->assign_block_vars('toolbox', array(
			'L_TOOLBOX' => $lang['Dl_manage'],
			'TOOLBOX_IMG' => $images['Dl_acp_toolbox'],
			'U_TOOLBOX' => append_sid('admin_downloads.' . PHP_EXT . '?submod=toolbox')
			)
		);

		if ($dl_config['use_ext_blacklist'])
		{
			$template->assign_block_vars('ext_blacklist', array(
				'L_EXT_BLACKLIST' => $lang['Dl_ext_blacklist'],
				'EXT_BLACKLIST_IMG' => $images['Dl_acp_ext_blacklist'],
				'U_EXT_BLACKLIST' => append_sid('admin_downloads.' . PHP_EXT . '?submod=ext_blacklist')
				)
			);
		}
	}
}

$row_width = floor(100 / $menu_width);

$template->assign_vars(array(
	'ROW_WIDTH' => $row_width,
	'DL_MANAGEMENT_TITLE' => $lang['Dl_acp_managemant_page'],
	'DL_MANAGEMENT_EXPLAIN' => $lang['Dl_acp_managemant_page_explain'],
	'DL_MOD_RELEASE' => sprintf($lang['Dl_mod_version'], $dl_config['dl_mod_version']),
	'L_BANLIST' => $lang['Dl_acp_banlist'],
	'BANLIST_IMG' => $images['Dl_acp_banlist'],
	'U_BANLIST' => append_sid('admin_downloads.' . PHP_EXT . '?submod=banlist')
	)
);

for ($i = 0; $i < $menu_width; $i++)
{
	$template->assign_block_vars('management_menu_row', array(
		'I_MODULE_IMG' => $i_dl_modules[$i],
		'L_MODULE_TITLE' => $l_dl_modules[$i],
		'U_MODULE_URL' => append_sid('admin_downloads.' . PHP_EXT . '?submod=' . $u_dl_modules[$i])
		)
	);
}

/*
* create overall mini statistics
*/
$total_todo = count($dl_mod->all_files(0, '', 'ASC', "AND todo <> '' AND todo IS NOT NULL"));
$total_size = $dl_mod->get_dl_overall_size();
$total_dl = $dl_mod->get_sublevel_count();
$total_extern = count($dl_mod->all_files(0, '', 'ASC', "AND extern = 1"));

$physical_limit = $dl_config['physical_quota'];
$total_size = ($total_size > $physical_limit) ? $physical_limit : $total_size;

$physical_limit = $dl_mod->dl_size($physical_limit, 2);

if ($total_dl && $total_size)
{
	$total_size = $dl_mod->dl_size($total_size, 2);

	$template->assign_block_vars('total_stat', array(
		'TOTAL_STAT' => sprintf($lang['Dl_total_stat'], $total_dl, $total_size, $physical_limit, $total_extern)
		)
	);
}

if ($dl_config['overall_traffic'] - $dl_config['remain_traffic'] <= 0)
{
	$overall_traffic = $dl_mod->dl_size($dl_config['overall_traffic']);

	$template->assign_block_vars('no_remain_traffic', array(
		'NO_OVERALL_TRAFFIC' => sprintf($lang['Dl_no_more_remain_traffic'], $overall_traffic)
		)
	);
}
else
{
	$remain_traffic = $dl_config['overall_traffic'] - $dl_config['remain_traffic'];

	$remain_text_out = $lang['Dl_remain_overall_traffic'] . '<b>' . $dl_mod->dl_size($remain_traffic, 2) . '</b>';

	$template->assign_block_vars('remain_traffic', array(
		'REMAIN_TRAFFIC' => $remain_text_out
		)
	);
}

/*
* initiate the help system
*/
$template->assign_vars(array(
	'U_HELP_POPUP' => IP_ROOT_PATH . 'dl_help.' . PHP_EXT . '?help_key='
	)
);

/*
* parse the page
*/
$template->pparse('body');

/*
* init and get various values
*/
$params = array(
	'submit' => 'submit',
	'cancel' => 'cancel',
	'confirm' => 'confirm',
	'submod' => 'submod',
	'action' => 'action',
	'delete' => 'delete',
	'sorting' => 'sorting',
	'sort_order' => 'sort_order',
	'filtering' => 'filtering',
	'filter_string' => 'filter_string',
	'func' => 'func',
	'username' => 'username',
	'add' => 'add',
	'edit' => 'edit',
	'move' => 'move',
	'save_cat' => 'save_cat',
	'path' => 'path',
	'dircreate' => 'dircreate',
	'dir_name' => 'dir_name',
	'new_path' => 'new_path',
	'new_cat' => 'new_cat',
	'file_command' => 'file_command',
	'file_assign' => 'file_assign',
	'x' => 'x',
	'y' => 'y',
	'z' => 'z'
);
while( list($var, $param) = @each($params) )
{
	if ( !empty($_POST[$param]) || !empty($_GET[$param]) )
	{
		$$var = ( !empty($_POST[$param]) ) ? htmlspecialchars($_POST[$param]) : htmlspecialchars($_GET[$param]);
	}
	else
	{
		$$var = '';
	}
}

$params = array(
	'df_id' => 'df_id',
	'cat_id' => 'cat_id',
	'new_cat_id' => 'new_cat_id',
	'start' => 'start',
	'show_guests' => 'show_guests',
	'user_id' => 'user_id',
	'user_traffic' => 'user_traffic',
	'all_traffic' => 'all_traffic',
	'group_id' => 'group_id',
	'group_traffic' => 'group_traffic',
	'group_id' => POST_GROUPS_URL,
	'auth_view' => 'auth_view',
	'auth_dl' => 'auth_dl',
	'auth_up' => 'auth_up',
	'auth_mod' => 'auth_mod',
	'del_file' => 'del_file'
);
while( list($var, $param) = @each($params) )
{
	if ( !empty($_POST[$param]) || !empty($_GET[$param]) )
	{
		$$var = ( !empty($_POST[$param]) ) ? intval($_POST[$param]) : intval($_GET[$param]);
	}
	else
	{
		$$var = 0;
	}
}

$df_id = ($df_id < 0) ? 0 : $df_id;
$cat_id = ($cat_id < 0) ? 0 : $cat_id;
$new_cat_id = ($new_cat_id < 0) ? 0 : $new_cat_id;
$start = ($start < 0) ? 0 : $start;
$show_guests = ($show_guests < 0) ? 0 : $show_guests;
$user_id = ($user_id < -1) ? -1 : $user_id;
$user_traffic = ($user_traffic < 0) ? 0 : $user_traffic;
$all_traffic = ($all_traffic < 0) ? 0 : $all_traffic;
$group_id = ($group_id < 0) ? 0 : $group_id;
$group_traffic = ($group_traffic < 0) ? 0 : $group_traffic;
$auth_view = ($auth_view < 0) ? 0 : $auth_view;
$auth_dl = ($auth_dl < 0) ? 0 : $auth_dl;
$auth_up = ($auth_up < 0) ? 0 : $auth_up;
$auth_mod = ($auth_mod < 0) ? 0 : $auth_mod;
$del_file = ($del_file < 0) ? 0 : $del_file;

switch($submod)
{
	case 'config':
		include($dl_admin_path . 'dl_admin_config.' . PHP_EXT);
		break;

	case 'traffic':
		include($dl_admin_path . 'dl_admin_traffic.' . PHP_EXT);
		break;

	case 'categories':
		include($dl_admin_path . 'dl_admin_categories.' . PHP_EXT);
		break;

	case 'files':
		if ($file_manage)
		{
			include($dl_admin_path . 'dl_admin_files.' . PHP_EXT);
		}
		break;

	case 'toolbox':
		if ($file_manage)
		{
			include($dl_admin_path . 'dl_admin_toolbox.' . PHP_EXT);
		}
		break;

	case 'stats':
		if ($stat_manage)
		{
			include($dl_admin_path . 'dl_admin_stats.' . PHP_EXT);
		}
		break;
	case 'ext_blacklist':
		if ($dl_config['use_ext_blacklist'])
		{
			include($dl_admin_path . 'dl_admin_ext_blacklist.' . PHP_EXT);
		}
		break;
	case 'banlist':
		include($dl_admin_path . 'dl_admin_banlist.' . PHP_EXT);
		break;
}

include('./page_footer_admin.' . PHP_EXT);

?>