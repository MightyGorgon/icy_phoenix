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
* MX-System - (jonohlsson@hotmail.com) - (www.mx-system.com)
*
*/

define('IN_PHPBB', true);

if ( !empty( $setmodules ) )
{
	$file = basename( __FILE__ );
	$module['1800_KB_title']['160_Optimize_tables'] = $file;
	return;
}

$phpbb_root_path = './../';
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);
$start_time = time ();
$time_limit = $_GET['time_limit'];
include($phpbb_root_path . 'includes/functions_admin.' . $phpEx);
include($phpbb_root_path . 'includes/kb_constants.' . $phpEx);
include($phpbb_root_path . 'includes/functions_kb.' . $phpEx);
include($phpbb_root_path . 'includes/functions_kb_auth.' . $phpEx);
include($phpbb_root_path . 'includes/functions_kb_field.' . $phpEx);
include($phpbb_root_path . 'includes/functions_kb_mx.' . $phpEx);
include($phpbb_root_path . 'includes/functions_search.' . $phpEx);

$page_title = $lang['Page_title'];

if (isset ($_GET['start']))
{
	function onTime ()
	{
		global $start_time, $time_limit;
		static $max_execution_time;

		$current_time = time ();

		if (empty ($max_execution_time))
		{
			if (ini_get ('safe_mode') == false)
			{
				set_time_limit (0);

				$max_execution_time = $time_limit;
			}
			else
			{
				$max_execution_time = ini_get ('max_execution_time');
			}
		}

		return (($current_time - $start_time) < $max_execution_time) ? true : false;
	}

	$start = intval($_GET['start']);

	if ($start == 0)
	{
		$sql = "DELETE FROM " . KB_SEARCH_TABLE;
		$result = $db->sql_query ($sql);

		$sql = "DELETE FROM " . KB_WORD_TABLE;
		$result = $db->sql_query ($sql);

		$sql = "DELETE FROM " . KB_MATCH_TABLE;
		$result = $db->sql_query ($sql);

		$sql = "SELECT article_id FROM " . KB_ARTICLES_TABLE;
		$result = $db->sql_query ($sql);
		$total_num_rows = $db->sql_numrows ($result);
	}

	$total_num_rows = (isset ($_GET['total_num_rows'])) ? $_GET['total_num_rows'] : $total_num_rows;

	$sql = "SELECT article_id, article_title, article_body FROM " . KB_ARTICLES_TABLE . " LIMIT $start, " . $_GET['post_limit'];
	$result = $db->sql_query ($sql);

	$num_rows = 0;
	while (($row = $db->sql_fetchrow ($result)) )
	{
		mx_add_search_words('single', $row['article_id'], stripslashes($row['article_body']), stripslashes($row['article_title']), 'kb');
		$num_rows++;
	}

	$template->set_filenames(array('body' => ADM_TPL . 'admin_message_body.tpl'));

	if (($start + $num_rows) != $total_num_rows) {
		$form_action = append_sid ("admin_kb_rebuild_search.$phpEx?start=". ($start + $num_rows) ."&total_num_rows=$total_num_rows&post_limit=". $_GET['post_limit'] ."&time_limit=$time_limit&refresh_rate=". $_GET['refresh_rate']);
		$next = $lang['Next'];
		$template->assign_vars(array(
			"META" => '<meta http-equiv="refresh" content="'. $_GET['refresh_rate'] .';url='. $form_action .'">')
		);
	} else {
		$next = $lang['Finished'];
		$form_action = append_sid ("admin_kb_rebuild_search.$phpEx");
	}

	$template->assign_vars (array (
		'PERCENT' => round ((($start + $num_rows) / $total_num_rows) * 100),
		'L_NEXT' => $next,
		'START' => $start + $num_rows,
		'TOTAL_NUM_ROWS' => $total_num_rows,
		'S_REBUILD_SEARCH_ACTION' => $form_action)
	);

	$template->set_filenames (array ('body' => ADM_TPL . 'kb_rebuild_search_progress.tpl'));
}
else
{
	$template->assign_vars (array (
		'L_REBUILD_SEARCH' => $lang['Rebuild_search'],
		'L_REBUILD_SEARCH_DESC' => $lang['Rebuild_search_desc'],
		'L_POST_LIMIT' => $lang['Post_limit'],
		'L_TIME_LIMIT' => $lang['Time_limit'],
		'L_REFRESH_RATE' => $lang['Refresh_rate'],
		'SESSION_ID' => $userdata['session_id'],

		'S_REBUILD_SEARCH_ACTION' => append_sid ("admin_kb_rebuild_search.$phpEx"))
	);

	$template->set_filenames (array ('body' => ADM_TPL . 'kb_rebuild_search.tpl'));
}

$template->pparse ('body');

//
// Page Footer
//
// include('./page_footer_admin.' . $phpEx);
include($phpbb_root_path . ADM . '/page_footer_admin.' . $phpEx);

?>