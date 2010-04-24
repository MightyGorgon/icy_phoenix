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
* @Icy Phoenix is based on phpBB
* @copyright (c) 2008 phpBB Group
*
*/

define('IN_ICYPHOENIX', true);

if(!empty($setmodules))
{
	$filename = basename(__FILE__);
	$module['1200_Forums']['125_Permissions_Forum'] = $file;
	return;
}

// Load default header
$no_page_header = true;
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('pagestart.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/def_auth.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_selects.' . PHP_EXT);

// build an indexed array on field names
/*
@reset($field_names);
$forum_auth_fields = array();
while (list($auth_key, $auth_name) = @each($field_names))
{
	$forum_auth_fields[] = $auth_key;
}
*/

$fid = request_var(POST_FORUM_URL, '');
if(!empty($fid))
{
	$f_type = substr($fid, 0, 1);
	if ($f_type == POST_FORUM_URL)
	{
		$forum_id = intval(substr($fid, 1));
		$forum_sql = " WHERE forum_id = '" . $forum_id . "'";
	}
	else
	{
		unset($forum_id);
		$forum_sql = '';
	}
}
else
{
	unset($forum_id);
	$forum_sql = '';
}

$adv = request_get_var('adv', 0);

// Start program proper
if(isset($_POST['submit']))
{
	$sql = '';

	if(!empty($forum_id))
	{
		if(isset($_POST['simpleauth']))
		{
			$simple_ary = $simple_auth_ary[intval($_POST['simpleauth'])];

			for($i = 0; $i < sizeof($simple_ary); $i++)
			{
				$sql .= (($sql != '') ? ', ' : '') . $forum_auth_fields[$i] . ' = ' . $simple_ary[$i];
			}

			if (is_array($simple_ary))
			{
				$sql = "UPDATE " . FORUMS_TABLE . " SET $sql WHERE forum_id = $forum_id";
			}
		}
		else
		{
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

			$sql = "UPDATE " . FORUMS_TABLE . " SET $sql WHERE forum_id = $forum_id";
		}

		// Delete notifications for not auth users
		include_once(IP_ROOT_PATH . 'includes/class_notifications.' . PHP_EXT);
		$notifications->delete_not_auth_notifications($forum_id);

		if ($sql != '')
		{
			$db->sql_query($sql);
		}

		$forum_sql = '';
		$adv = 0;
	}

	cache_tree(true);

	$redirect_url = append_sid(ADM . '/admin_forumauth.' . PHP_EXT . '?' . POST_FORUM_URL . '=' . $forum_id);
	meta_refresh(3, $redirect_url);

	$message = $lang['Forum_auth_updated'] . '<br /><br />' . sprintf($lang['Click_return_forumauth'],  '<a href="' . append_sid('admin_forumauth.' . PHP_EXT) . '">', '</a>');
	message_die(GENERAL_MESSAGE, $message);

} // End of submit

//
// Get required information, either all forums if
// no id was specified or just the requsted if it
// was
//

if(empty($forum_id))
{
	// Output the selection table if no forum id was specified
	$template->set_filenames(array('body' => ADM_TPL . 'auth_select_body.tpl'));

	$select_list = make_forum_select(POST_FORUM_URL, false, '', true);

	$template->assign_vars(array(
		'L_AUTH_TITLE' => $lang['Auth_Control_Forum'],
		'L_AUTH_EXPLAIN' => $lang['Forum_auth_explain'],
		'L_AUTH_SELECT' => $lang['Select_a_Forum'],
		'L_LOOK_UP' => $lang['Look_up_Forum'],

		'S_AUTH_ACTION' => append_sid('admin_forumauth.' . PHP_EXT),
		'S_AUTH_SELECT' => $select_list
		)
	);

}
else
{
	// Output the authorization details if an id was specified
	$template->set_filenames(array('body' => ADM_TPL . 'auth_forum_body.tpl'));

	$sql = "SELECT f.*
		FROM " . FORUMS_TABLE . " f
		WHERE forum_id = " . $forum_id;
	$result = $db->sql_query($sql);
	$forum_rows = $db->sql_fetchrowset($result);
	$db->sql_freeresult($result);

	/*
	$forum_rows[0] = $tree['data'][$tree['keys'][POST_FORUM_URL . $forum_id]];
	$forum_name_trad = get_object_lang(POST_FORUM_URL . $forum_id, 'name');
	$forum_name = $forum_rows[0]['forum_name'];
	if ($forum_name != $forum_name_trad)
	{
		$forum_name = '(' . $forum_name . ') ' . $forum_name_trad;
	}
	*/

	$forum_name = $forum_rows[0]['forum_name'];
	@reset($simple_auth_ary);
	while(list($key, $auth_levels) = each($simple_auth_ary))
	{
		$matched = 1;
		for($k = 0; $k < sizeof($auth_levels); $k++)
		{
			$matched_type = $key;

			if ($forum_rows[0][$forum_auth_fields[$k]] != $auth_levels[$k])
			{
				$matched = 0;
			}
		}

		if ($matched)
		{
			break;
		}
	}

	// If we didn't get a match above then we automatically switch into 'advanced' mode
	if (!isset($adv) && !$matched)
	{
		$adv = 1;
	}

	$s_column_span == 0;

	if (empty($adv))
	{
		$simple_auth = '<select name="simpleauth">';

		for($j = 0; $j < sizeof($simple_auth_types); $j++)
		{
			$selected = ($matched_type == $j) ? ' selected="selected"' : '';
			$simple_auth .= '<option value="' . $j . '"' . $selected . '>' . $simple_auth_types[$j] . '</option>';
		}

		$simple_auth .= '</select>';

		$template->assign_block_vars('forum_auth_titles', array(
			'CELL_TITLE' => $lang['Simple_mode']
			)
		);
		$template->assign_block_vars('forum_auth_data', array(
			'S_AUTH_LEVELS_SELECT' => $simple_auth
			)
		);

		$s_column_span++;
	}
	else
	{
		// Output values of individual fields
		for($j = 0; $j < sizeof($forum_auth_fields); $j++)
		{
			$custom_auth[$j] = '&nbsp;<select name="' . $forum_auth_fields[$j] . '">';

			for($k = 0; $k < sizeof($forum_auth_levels); $k++)
			{
				$selected = ($forum_rows[0][$forum_auth_fields[$j]] == $forum_auth_const[$k]) ? ' selected="selected"' : '';
				$custom_auth[$j] .= '<option value="' . $forum_auth_const[$k] . '"' . $selected . '>' . $lang['Forum_' . $forum_auth_levels[$k]] . '</option>';
			}
			$custom_auth[$j] .= '</select>&nbsp;';

			$cell_title = $field_names[$forum_auth_fields[$j]];

			$template->assign_block_vars('forum_auth_titles', array(
				'CELL_TITLE' => $cell_title)
			);
			$template->assign_block_vars('forum_auth_data', array(
				'S_AUTH_LEVELS_SELECT' => $custom_auth[$j])
			);

			$s_column_span++;
		}
	}

	$adv_mode = (empty($adv)) ? '1' : '0';
	$switch_mode = append_sid('admin_forumauth.' . PHP_EXT . '?' . POST_FORUM_URL . '=f' . $forum_id . '&adv=' . $adv_mode);
	$switch_mode_text = (empty($adv)) ? $lang['Advanced_mode'] : $lang['Simple_mode'];
	$u_switch_mode = '<a href="' . $switch_mode . '">' . $switch_mode_text . '</a>';
	$s_hidden_fields = '<input type="hidden" name="' . POST_FORUM_URL . '" value="f' . $forum_id . '">';

	$template->assign_vars(array(
		'FORUM_NAME' => $forum_name,

		'L_FORUM' => $lang['Forum'],
		'L_AUTH_TITLE' => $lang['Auth_Control_Forum'],
		'L_AUTH_EXPLAIN' => $lang['Forum_auth_explain'],
		'L_SUBMIT' => $lang['Submit'],
		'L_RESET' => $lang['Reset'],

		'U_SWITCH_MODE' => $u_switch_mode,

		'S_FORUMAUTH_ACTION' => append_sid('admin_forumauth.' . PHP_EXT),
		'S_COLUMN_SPAN' => $s_column_span,
		'S_HIDDEN_FIELDS' => $s_hidden_fields
		)
	);

}

include('./page_header_admin.' . PHP_EXT);

$template->pparse('body');

include('./page_footer_admin.' . PHP_EXT);

?>