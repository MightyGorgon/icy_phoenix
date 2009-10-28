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

if( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	$module['1200_Forums']['122_Permissions_Adv'] = $file;
	return;
}

// Load default header
$no_page_header = true;
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('pagestart.' . PHP_EXT);
include(IP_ROOT_PATH . './includes/def_auth.' . PHP_EXT);

if( isset($_POST['submit']) )
{

	$var_ary = array(
		'forums' => array(0),
	);

	foreach ($var_ary as $var => $default)
	{
		$data[$var] = request_var($var, $default, true);
	}

	if (sizeof($data['forums']))
	{
		$forums_to_auth = implode('\',\'', $data['forums']);

		$sql = '';

		for($i = 0; $i < sizeof($forum_auth_fields); $i++)
		{
			$value = intval($_POST[$forum_auth_fields[$i]]);

			if ( $forum_auth_fields[$i] == 'auth_vote' )
			{
				if ( $_POST['auth_vote'] == AUTH_ALL )
				{
					$value = AUTH_REG;
				}
			}

			$sql .= ( ( $sql != '' ) ? ', ' : '' ) .$forum_auth_fields[$i] . ' = ' . $value;
		}

		$sql = "UPDATE " . FORUMS_TABLE . "
						SET $sql
						WHERE forum_id IN ('" . $forums_to_auth . "')";
		//die($sql);
		$db->sql_query($sql);
	}

	cache_tree(true);

	$redirect_url = append_sid(ADM . '/admin_forumauth_adv.' . PHP_EXT);
	meta_refresh(3, $redirect_url);

	$message = $lang['Forum_auth_updated'] . '<br /><br />' . sprintf($lang['Click_return_forumauth'], '<a href="' . append_sid('admin_forumauth_adv.' . PHP_EXT) . '">', '</a>');
	message_die(GENERAL_MESSAGE, $message);

} // End of submit

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
		$selected = ( $simple_auth_ary[0][$j] == $forum_auth_const[$k] ) ? ' selected="selected"' : '';
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

include('./page_header_admin.' . PHP_EXT);

$template->pparse('body');

include('./page_footer_admin.' . PHP_EXT);

?>