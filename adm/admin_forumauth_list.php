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
* 2003 Freakin' Booty ;-P & Antony Bailey
*
*/

if (!defined('IN_ICYPHOENIX')) define('IN_ICYPHOENIX', true);

if(!empty($setmodules))
{
	$filename = basename(__FILE__);
	$module['1200_Forums']['120_Permissions_List'] = $filename;
	return;
}

// Load default header
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
$no_page_header = true;
require('pagestart.' . PHP_EXT);

// Start program - define vars
include(IP_ROOT_PATH . './includes/def_auth.' . PHP_EXT);

// Get required information, for all forums
$sql = "SELECT f.*
		FROM " . FORUMS_TABLE . " f
		WHERE f.forum_type = " . FORUM_POST . "
		ORDER BY f.forum_order ASC";
$result = $db->sql_query($sql);
$forum_rows = $db->sql_fetchrowset($result);
$db->sql_freeresult($result);

// Start program proper
if($_POST['submit'])
{
	for($i = 0; $i < sizeof($forum_rows); $i++)
	{
		$sql = '';
		$forum_id = $forum_rows[$i]['forum_id'];

		for($j = 0; $j < sizeof($forum_auth_fields); $j++)
		{
			$value = $_POST[$forum_auth_fields[$j]][$forum_id];

			if ($forum_auth_fields[$j] == 'auth_vote')
			{
				if ($_POST['auth_vote'][$forum_id] == AUTH_ALL)
				{
					$value = AUTH_REG;
				}
			}

			$sql .= (($sql != '') ? ', ' : '') . $forum_auth_fields[$j] . ' = ' . $value;
		}

		$sql = "UPDATE " . FORUMS_TABLE . " SET $sql WHERE forum_id = $forum_id";
		$db->sql_query($sql);
	}

	// Delete notifications for not auth users
	if (!class_exists('class_notifications'))
	{
		include(IP_ROOT_PATH . 'includes/class_notifications.' . PHP_EXT);
		$class_notifications = new class_notifications();
	}
	$class_notifications->delete_not_auth_notifications();

	cache_tree(true);

	$redirect_url = append_sid(ADM . '/admin_forumauth_list.' . PHP_EXT);
	meta_refresh(3, $redirect_url);

	$message = $lang['Forum_auth_updated'] . '<br /><br />' . sprintf($lang['Click_return_forumauth'],  '<a href="' . append_sid('admin_forumauth_list.' . PHP_EXT) . '">', '</a>');
	message_die(GENERAL_MESSAGE, $message);
}


// Default page
$colspan = sizeof($forum_auth_fields) + 2;

// Output the authorization details
$template->set_filenames(array('body' => ADM_TPL . 'auth_forum_list_body.tpl'));

$template->assign_vars(array(
	'L_AUTH_LIST_TITLE' => $lang['Auth_list_Control_Forum'],
	'L_AUTH_LIST_EXPLAIN' => $lang['Forum_auth_list_explain'],
	'L_SUBMIT' => $lang['Submit'],
	'L_RESET' => $lang['Reset'],
	'COLSPAN' => $colspan,
	'S_FORM_ACTION' => append_sid('admin_forumauth_list.' . PHP_EXT)
	)
);

$template->assign_block_vars('forum_auth_titles', array(
	'CELL_TITLE' => $lang['Forum']
	)
);

for($i = 0; $i < sizeof($forum_auth_fields); $i++)
{
	$template->assign_block_vars('forum_auth_titles', array(
		'CELL_TITLE' => $field_names[$forum_auth_fields[$i]]
		)
	);
}

$template->assign_block_vars('forum_auth_titles', array(
	'CELL_TITLE' => $lang['Forum']
	)
);

for ($i = 0; $i < sizeof($forum_rows); $i++)
{
	$temp_url = append_sid('admin_forumauth.' . PHP_EXT . '?' . POST_FORUM_URL . '=' . $forum_rows[$i]['forum_id']);
	$s_forum = '<a href="' . $temp_url . '">' . $forum_rows[$i]['forum_name'] . '</a>';

	$template->assign_block_vars('forum_row', array(
		'ROW_CLASS' => (!($i % 2)) ? 'row1' : 'row2',
		'S_FORUM' => $s_forum
		)
	);

	for($j = 0; $j < sizeof($forum_auth_fields); $j++)
	{
		$custom_auth[$j] = '&nbsp;<select name="' . $forum_auth_fields[$j] . '[' . $forum_rows[$i]['forum_id'] . ']">';

		for($k = 0; $k < sizeof($forum_auth_levels); $k++)
		{
			$selected = ($forum_rows[$i][$forum_auth_fields[$j]] == $forum_auth_const[$k]) ? ' selected="selected"' : '';
			$custom_auth[$j] .= '<option value="' . $forum_auth_const[$k] . '"' . $selected . '>' . $lang['Forum_' . $forum_auth_levels[$k]] . '</option>';
		}
		$custom_auth[$j] .= '</select>&nbsp;';

		$cell_title = $field_names[$forum_auth_fields[$j]];

		$template->assign_block_vars('forum_row.forum_auth_data', array(
			'S_AUTH_LEVELS_SELECT' => $custom_auth[$j]
			)
		);
	}
}

include(IP_ROOT_PATH . ADM . '/page_header_admin.' . PHP_EXT);

$template->pparse('body');

include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);

?>