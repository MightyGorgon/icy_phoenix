<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

define('IN_PHPBB', true);

if( !empty($setmodules) )
{
	$file = basename(__FILE__);
	$module['1000_Configuration']['190_Clear_Cache'] = $file;
	return;
}

$phpbb_root_path = './../';
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);

$confirmation = false;
if( isset($_POST['confirm_clear_cache_main']) )
{
	$db->clear_cache();
	empty_cache_folder('./../', false);

	$message = '<br /><br />' . $lang['Empty_Cache_Success'] . '<br /><br />';
	$confirmation = true;
}

if( isset($_POST['confirm_clear_cache_posts']) )
{
	$sql = "UPDATE " . POSTS_TEXT_TABLE . " SET post_text_compiled = ''";

	if(!$result = $db->sql_query($sql))
	{
		$meta_tag = '</body><head><meta http-equiv="refresh" content="3;url=' . append_sid('admin_board_posting.' . $phpEx) . '"></head><body>';
		$message .=  '<br /><br />' . $lang['MG_SW_Empty_Precompiled_Posts_Fail'] . '<br /><br />';
		message_die(GENERAL_MESSAGE, $meta_tag . $message);
	}

	$message = '<br /><br />' . $lang['MG_SW_Empty_Precompiled_Posts_Success'] . '<br /><br />';
	$confirmation = true;
}

if( isset($_POST['confirm_clear_cache_thumbs']) )
{
	$cache_dir = @opendir('../' . POSTED_IMAGES_THUMBS_PATH);

	while( $cache_file = @readdir($cache_dir) )
	{
		if( preg_match('/(\.gif$|\.png$|\.jpg|\.jpeg)$/is', $cache_file) )
		{
			@unlink('../' . POSTED_IMAGES_THUMBS_PATH . $cache_file);
		}
	}

	@closedir($cache_dir);

	$message = '<br /><br />' . $lang['Empty_Cache_Success'] . '<br /><br />';
	$confirmation = true;
}

if ($confirmation == true)
{
	$meta_tag = '</body><head><meta http-equiv="refresh" content="3;url=' . append_sid('admin_board_clearcache.' . $phpEx) . '"></head><body>';
	message_die(GENERAL_MESSAGE, $meta_tag . $message);
}

$template->set_filenames(array('body' => ADM_TPL . 'board_config_clearcache_body.tpl'));

$template->assign_vars(array(
	'S_CONFIG_ACTION' => append_sid('admin_board_clearcache.' . $phpEx),
	'CLEARCACHE_MAIN' => $lang['Empty_Cache_Main_Question'],
	'CLEARCACHE_POSTS' => $lang['Empty_Cache_Posts_Question'],
	'CLEARCACHE_THUMBNAILS' => $lang['Empty_Cache_Thumbs_Question'],
	'L_YES' => $lang['Yes'],
	'L_NO' => $lang['No']
	)
);

$template->pparse('body');

include('./page_footer_admin.' . $phpEx);

?>