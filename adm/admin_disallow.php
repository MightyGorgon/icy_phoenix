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


if(defined('IN_ICYPHOENIX') && !empty($setmodules))
{
	$filename = basename(__FILE__);
	$module['1610_Users']['200_Disallow'] = $filename;

	return;
}
define('IN_ICYPHOENIX', true);

// Include required files, get PHP_EXT and check permissions
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('pagestart.' . PHP_EXT);

if( isset($_POST['add_name']) )
{
	include(IP_ROOT_PATH . 'includes/functions_validate.' . PHP_EXT);

	$disallowed_user = request_var('disallowed_user', '', true);

	if (empty($disallowed_user))
	{
		message_die(GENERAL_MESSAGE, $lang['Fields_empty']);
	}

	if(!validate_username($disallowed_user))
	{
		$message = $lang['Disallowed_already'];
	}
	else
	{
		$sql = "INSERT INTO " . DISALLOW_TABLE . " (disallow_username)
			VALUES('" . $db->sql_escape($disallowed_user) . "')";
		$result = $db->sql_query( $sql );

		$message = $lang['Disallow_successful'];
	}

	$message .= '<br /><br />' . sprintf($lang['Click_return_disallowadmin'], '<a href="' . append_sid("admin_disallow." . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

	message_die(GENERAL_MESSAGE, $message);
}
elseif(isset($_POST['delete_name']))
{
	$disallowed_id = request_var('disallowed_id', 0);

	$sql = "DELETE FROM " . DISALLOW_TABLE . " WHERE disallow_id = $disallowed_id";
	$result = $db->sql_query($sql);

	$message .= $lang['Disallowed_deleted'] . '<br /><br />' . sprintf($lang['Click_return_disallowadmin'], '<a href="' . append_sid('admin_disallow.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

	message_die(GENERAL_MESSAGE, $message);

}

// Grab the current list of disallowed usernames...
$sql = "SELECT * FROM " . DISALLOW_TABLE;
$result = $db->sql_query($sql);
$disallowed = $db->sql_fetchrowset($result);

// Ok now generate the info for the template, which will be put out no matter what mode we are in.
$disallow_select = '<select name="disallowed_id">';

if(empty($disallowed) || !is_array($disallowed))
{
	$disallow_select .= '<option value="">' . $lang['no_disallowed'] . '</option>';
}
else
{
	for($i = 0; $i < sizeof($disallowed); $i++)
	{
		$disallow_select .= '<option value="' . $disallowed[$i]['disallow_id'] . '">' . $disallowed[$i]['disallow_username'] . '</option>';
	}
}

$disallow_select .= '</select>';

$template->set_filenames(array('body' => ADM_TPL . 'disallow_body.tpl'));

$template->assign_vars(array(
	'S_DISALLOW_SELECT' => $disallow_select,
	'S_FORM_ACTION' => append_sid('admin_disallow.' . PHP_EXT),

	'L_INFO' => $output_info,
	'L_DISALLOW_TITLE' => $lang['Disallow_control'],
	'L_DISALLOW_EXPLAIN' => $lang['Disallow_explain'],
	'L_DELETE' => $lang['Delete_disallow'],
	'L_DELETE_DISALLOW' => $lang['Delete_disallow_title'],
	'L_DELETE_EXPLAIN' => $lang['Delete_disallow_explain'],
	'L_ADD' => $lang['Add_disallow'],
	'L_ADD_DISALLOW' => $lang['Add_disallow_title'],
	'L_ADD_EXPLAIN' => $lang['Add_disallow_explain'],
	'L_USERNAME' => $lang['Username']
	)
);

$template->pparse('body');

include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);

?>
