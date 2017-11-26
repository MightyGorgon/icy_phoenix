<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

define('IN_ICYPHOENIX', true);

if(!empty($setmodules))
{
	$file = basename(__FILE__);
	$module['1000_Configuration']['127_Clear_Cache'] = $file;
	return;
}

if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('pagestart.' . PHP_EXT);

$confirmation = false;
$meta_tag = '';

if(check_http_var_exists('confirm_clear_cache_main', false))
{
	$files_deleted = empty_cache_folders('', CACHE_FILES_PER_STEP);
	$redirect_url = append_sid('admin_board_clearcache.' . PHP_EXT . '?confirm_clear_cache_main=' . str_replace('sid=', '', $SID));
	if ($files_deleted === CACHE_FILES_PER_STEP)
	{
		//meta_refresh(3, $redirect_url);
		$meta_tag = '</body><head><meta http-equiv="refresh" content="3;url=' . $redirect_url . '"></head><body>';
		$message .= $lang['MG_SW_Empty_Precompiled_Posts_InProgress'] . '<br /><br />' . $lang['MG_SW_Empty_Precompiled_Posts_InProgress_Redirect'] . '<br /><br />' . sprintf($lang['MG_SW_Empty_Precompiled_Posts_InProgress_Redirect_Click'], '<a href="' . $redirect_url . '">', '</a>');
		message_die(GENERAL_MESSAGE, $meta_tag . $message);
	}

	// Clean also data in global cache
	$cache_data = array('config', 'config_plugins', 'config_plugins_config', 'config_style', 'newest_user');
	foreach ($cache_data as $cache_data_section)
	{
		$cache->destroy($cache_data_section);
	}

	// Make sure cron is unlocked... just to make sure that it didn't hang somewhere in time... :-)
	set_config('cron_lock', '0');
	set_config('cron_lock_hour', 0);
	$message = $lang['Empty_Cache_Success'] . '<br /><br />';
	$confirmation = true;
}

if(check_http_var_exists('confirm_clear_cache_posts', false))
{
	$sql = "UPDATE " . POSTS_TABLE . " SET post_text_compiled = ''";
	$db->sql_return_on_error(true);
	$result = $db->sql_query($sql);
	$db->sql_return_on_error(false);
	if(!$result)
	{
		$message .= '<br /><br />' . $lang['MG_SW_Empty_Precompiled_Posts_Fail'] . '<br /><br />';
		message_die(GENERAL_MESSAGE, $message);
	}

	$message = $lang['MG_SW_Empty_Precompiled_Posts_Success'] . '<br /><br />';
	$confirmation = true;
}

if(check_http_var_exists('confirm_clear_cache_thumbs', false))
{
	$files_deleted = empty_images_cache_folders(CACHE_FILES_PER_STEP);
	$redirect_url = append_sid('admin_board_clearcache.' . PHP_EXT . '?confirm_clear_cache_thumbs=' . str_replace('sid=', '', $SID));
	if ($files_deleted === CACHE_FILES_PER_STEP)
	{
		//meta_refresh(3, $redirect_url);
		$meta_tag = '</body><head><meta http-equiv="refresh" content="3;url=' . $redirect_url . '"></head><body>';
		$message .= $lang['MG_SW_Empty_Precompiled_Posts_InProgress'] . '<br /><br />' . $lang['MG_SW_Empty_Precompiled_Posts_InProgress_Redirect'] . '<br /><br />' . sprintf($lang['MG_SW_Empty_Precompiled_Posts_InProgress_Redirect_Click'], '<a href="' . $redirect_url . '">', '</a>');
		message_die(GENERAL_MESSAGE, $meta_tag . $message);
	}

	$message = $lang['Empty_Cache_Success'] . '<br /><br />';
	$confirmation = true;
}

if (!empty($confirmation))
{
	$redirect_url = append_sid('admin_board_clearcache.' . PHP_EXT);
	//meta_refresh(3, $redirect_url);
	//$meta_tag = '</body><head><meta http-equiv="refresh" content="3;url=' . $redirect_url . '"></head><body>';
	$message .= sprintf($lang['MG_SW_Empty_Precompiled_Posts_Redirect_Click'], '<a href="' . append_sid('admin_board_clearcache.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');
	message_die(GENERAL_MESSAGE, $meta_tag . $message);
}

$template->set_filenames(array('body' => ADM_TPL . 'board_config_clearcache_body.tpl'));

$template->assign_vars(array(
	'S_CONFIG_ACTION' => append_sid('admin_board_clearcache.' . PHP_EXT),
	'CLEARCACHE_MAIN' => $lang['Empty_Cache_Main_Question'],
	'CLEARCACHE_POSTS' => $lang['Empty_Cache_Posts_Question'],
	'CLEARCACHE_THUMBNAILS' => $lang['Empty_Cache_Thumbs_Question'],
	'L_YES' => $lang['Yes'],
	'L_NO' => $lang['No']
	)
);

$template->pparse('body');

include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);

?>