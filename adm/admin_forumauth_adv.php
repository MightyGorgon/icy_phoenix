<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/


if(defined('IN_ICYPHOENIX') && !empty($setmodules))
{
	$filename = basename(__FILE__);
	$module['1200_Forums']['122_Permissions_Adv'] = $filename;
	return;
}
define('IN_ICYPHOENIX', true);

// Load default header
$no_page_header = true;
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('pagestart.' . PHP_EXT);
include(IP_ROOT_PATH . './includes/def_auth.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_selects.' . PHP_EXT);

$forum_options = array(
	//'forum_status' => $lang['Status_locked'],
	'forum_likes' => $lang['FORUM_LIKES'],
	'forum_similar_topics' => $lang['FORUM_SIMILAR_TOPICS'],
	'forum_limit_edit_time' => $lang['FORUM_LIMIT_EDIT_TIME'],
	'forum_topic_views' => $lang['FORUM_TOPIC_VIEWS'],
	'forum_tags' => $lang['FORUM_TAGS'],
	'forum_sort_box' => $lang['FORUM_SORT_BOX'],
	'forum_notify' => $lang['Forum_notify'],
	'forum_postcount' => $lang['Forum_postcount'],
);

if (isset($_POST['options_submit']) || isset($_POST['submit']))
{
	$var_ary = array(
		'forums' => array(0),
	);

	foreach ($var_ary as $var => $default)
	{
		$data[$var] = request_var($var, $default, true);
	}

	$forums_list = false;
	if (sizeof($data['forums']))
	{
		$forums_list = true;
		$forums_to_auth = implode('\',\'', $data['forums']);
	}

	if (isset($_POST['options_submit']) && !empty($forums_list))
	{
		$sql_ary = array();

		foreach ($forum_options as $k => $dummy)
		{
			$sql_ary[$k] = (isset($_POST[$k]) ? 1 : 0);
		}

		$sql = "UPDATE " . FORUMS_TABLE . "
						SET " . $db->sql_build_array('UPDATE', $sql_ary) . "
						WHERE forum_id IN ('" . $forums_to_auth . "')";
		$db->sql_query($sql);
	}
	elseif(isset($_POST['submit']) && !empty($forums_list))
	{
		$sql = '';

		for($i = 0; $i < sizeof($forum_auth_fields); $i++)
		{
			$value = intval($_POST[$forum_auth_fields[$i]]);

			if ($forum_auth_fields[$i] == 'auth_vote')
			{
				if ($_POST['auth_vote'] == AUTH_ALL)
				{
					$value = AUTH_REG;
				}
			}

			$sql .= (($sql != '') ? ', ' : '') .$forum_auth_fields[$i] . ' = ' . $value;
		}

		$sql = "UPDATE " . FORUMS_TABLE . "
						SET $sql
						WHERE forum_id IN ('" . $forums_to_auth . "')";
		$db->sql_query($sql);

		// Delete notifications for not auth users
		if (!class_exists('class_notifications'))
		{
			include(IP_ROOT_PATH . 'includes/class_notifications.' . PHP_EXT);
			$class_notifications = new class_notifications();
		}
		$class_notifications->delete_not_auth_notifications($data['forums']);
	}
	// End of submit

	cache_tree(true);

	$redirect_url = append_sid(ADM . '/admin_forumauth_adv.' . PHP_EXT);
	meta_refresh(3, $redirect_url);

	$message = $lang['Forum_auth_updated'] . '<br /><br />' . sprintf($lang['Click_return_forumauth'], '<a href="' . append_sid('admin_forumauth_adv.' . PHP_EXT) . '">', '</a>');
	message_die(GENERAL_MESSAGE, $message);
}

// Get required information, either all forums if no id was specified or just the requsted if it was
// Output the authorization details if an id was specified
$template->set_filenames(array('body' => ADM_TPL . 'auth_forum_adv_body.tpl'));

$forumlist = get_tree_option_optg('', true, false);

// Output values of individual fields
for($j = 0; $j < sizeof($forum_auth_fields); $j++)
{
	$custom_auth[$j] = '&nbsp;<select name="' . $forum_auth_fields[$j] . '">';

	for($k = 0; $k < sizeof($forum_auth_levels); $k++)
	{
		$selected = ($simple_auth_ary[0][$j] == $forum_auth_const[$k]) ? ' selected="selected"' : '';
		$custom_auth[$j] .= '<option value="' . $forum_auth_const[$k] . '"' . $selected . '>' . $lang['Forum_' . $forum_auth_levels[$k]] . '</option>';
	}
	$custom_auth[$j] .= '</select>&nbsp;';

	$cell_title = $field_names[$forum_auth_fields[$j]];

	$template->assign_block_vars('forum_auth', array(
		'CELL_TITLE' => $cell_title,
		'S_AUTH_LEVELS_SELECT' => $custom_auth[$j]
		)
	);

	$s_column_span++;
}

foreach ($forum_options as $k => $v)
{
	$template->assign_block_vars('forum_option', array(
		'CELL_TITLE' => $v,
		'S_AUTH_LEVELS_SELECT' => $k
		)
	);
}


$s_hidden_fields = '';

$template->assign_vars(array(
	'FORUM_NAME' => $forum_name,
	'S_FORUM_LIST' => $forumlist,

	'L_FORUM' => $lang['Forum'],
	'L_AUTH_TITLE' => $lang['Auth_Control_Forum'],
	'L_AUTH_EXPLAIN' => $lang['Forum_auth_list_explain'],
	'L_SUBMIT' => $lang['Submit'],
	'L_RESET' => $lang['Reset'],

	'S_FORUMAUTH_ACTION' => append_sid('admin_forumauth_adv.' . PHP_EXT),
	'S_COLUMN_SPAN' => $s_column_span,
	'S_HIDDEN_FIELDS' => $s_hidden_fields
	)
);

include(IP_ROOT_PATH . ADM . '/page_header_admin.' . PHP_EXT);

$template->pparse('body');

include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);

?>