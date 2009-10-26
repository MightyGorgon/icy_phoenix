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
	$filename = basename(__FILE__);
	$module['1610_Users']['300_Picscount_Config'] = $filename;
	return;
}

if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('./pagestart.' . PHP_EXT);

if(isset($_POST['confirm_sync']))
{
	$sql = "UPDATE " . USERS_TABLE . "
		SET user_personal_pics_count = 0";
	$result = $db->sql_query($sql);

	$sql = "SELECT u.user_id, u.username, u.user_personal_pics_count, a.pic_cat_id, Count(a.pic_user_id) AS new_counter
		FROM " . USERS_TABLE . " u, " . ALBUM_TABLE . " a, " . ALBUM_CAT_TABLE . " ac
		WHERE u.user_id = a.pic_user_id
			AND u.user_id <> " . ANONYMOUS . "
			AND a.pic_cat_id = ac.cat_id
			AND ac.cat_user_id > 0
		GROUP BY u.user_id, u.username, u.user_personal_pics_count";
	$result_array = array();
	$result = $db->sql_query($sql);

	$list_exec = '<span class="topic_ann">' . $lang['Pics_Count_Synchronized'] . '<br /><ul>';
	$list_errors = '<span class="topic_glo">' . $lang['Pics_Count_Not_Synchronized'] . '<br /><ul>';
	while ($row = $db->sql_fetchrow($result))
	{
		if ($row['new_counter'] != $row['user_personal_pics_count'])
		{
			$list_exec .= '<li>' . htmlspecialchars($row['username']) . '&nbsp;&raquo;&nbsp;' . $row['new_counter'] . '</li>';
			$sql2 = "UPDATE " . USERS_TABLE . "
				SET user_personal_pics_count = " . $row['new_counter'] . "
				WHERE user_id = " . $row['user_id'];
			$db->sql_return_on_error(true);
			$result2 = $db->sql_query($sql2);
			$db->sql_return_on_error(false);
			if (!$result2)
			{
				$list_errors .= '<li>' . htmlspecialchars($row['username']) . '</li>';
			}
		}
	}
	$list_exec .= '</ul></span>';
	$list_errors .= '</ul></span>';
	$message = $list_exec . '<br /><br />' . $list_errors;
	message_die(GENERAL_MESSAGE, $message);
}
else
{
	$template->set_filenames(array('body' => ADM_TPL . 'sync_pics_count.tpl'));

	$template->assign_vars(array(
		'U_ACTION' => append_sid('admin_picscount.' . PHP_EXT),
		'SYNC_PICS_COUNT_TEXT' => $lang['Sync_Pics_Count'],
		'L_YES' => $lang['Yes'],
		'L_NO' => $lang['No']
		)
	);
}

$template->pparse('body');

include('./page_footer_admin.' . PHP_EXT);

?>