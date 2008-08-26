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

if (!defined('IN_PHPBB'))
{
	die('Hacking attempt');
}

if ($cancel)
{
	$action = '';
}

$action = (isset($_POST['edit_banlist'])) ? 'edit' : $action;
$action = (isset($_POST['delete_banlist'])) ? 'delete' : $action;

if($action == 'add')
{
	$ban_id = (isset($_POST['ban_id'])) ? intval($_POST['ban_id']) : 0;
	$user_id = (isset($_POST['user_id'])) ? intval($_POST['user_id']) : 0;
	$user_ip = (!empty($_POST['user_ip'])) ? encode_ip($_POST['user_ip']) : "";
	$user_agent = (isset($_POST['user_agent'])) ? htmlspecialchars($_POST['user_agent']) : "";
	$username = (isset($_POST['username'])) ? phpbb_clean_username($_POST['username']) : "";
	$guests = (isset($_POST['guests'])) ? intval($_POST['guests']) : 0;

	if ($ban_id)
	{
		$sql = "UPDATE " . DL_BANLIST_TABLE . "
			SET user_id = " . (int)$user_id . ", user_ip = '$user_ip', user_agent = '" . str_replace("\'", "''", $user_agent) . "', username = '" . str_replace("\'", "''", $username) . "', guests = " . (int)$guests . "
			WHERE ban_id = " . (int)$ban_id;
	}
	else
	{
		$sql = "INSERT INTO " . DL_BANLIST_TABLE . "
			(user_id, user_ip, user_agent, username, guests)
			VALUES
			(" . (int)$user_id . ", '$user_ip', '" . str_replace("\'", "''", $user_agent) . "', '" . str_replace("\'", "''", $username) . "', " . (int)$guests . ")";
	}

	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not save ban values', '', __LINE__, __FILE__, $sql);
	}

	$action = '';
}
elseif($action == 'delete')
{
	$ban_id = (isset($_POST['ban_id'])) ? $_POST['ban_id'] : array();

	if (!$confirm)
	{
		$template->set_filenames(array('confirm_body' => 'dl_confirm_body.tpl'));

		for ($i = 0; $i < count($ban_id); $i++)
		{
			$s_hidden_fields .= '<input type="hidden" name="ban_id[]" value="'.intval($ban_id[$i]).'" />';
		}

		$s_hidden_fields .= '<input type="hidden" name="action" value="delete" />';

		$template->assign_vars(array(
			'MESSAGE_TITLE' => $lang['Information'],
			'MESSAGE_TEXT' => $lang['Dl_confirm_delete_ban_values'],

			'L_YES' => $lang['Yes'],
			'L_NO' => $lang['No'],

			'S_CONFIRM_ACTION' => append_sid('admin_downloads.' . $phpEx . '?submod=banlist'),
			'S_HIDDEN_FIELDS' => $s_hidden_fields
			)
		);

		$template->pparse('confirm_body');

		include('./page_footer_admin.' . $phpEx);
	}
	else
	{
		$sql_ext_in = '';
		for ($i = 0; $i < count($ban_id); $i++)
		{
			$sql_ext_in .= ($sql_ext_in != '') ? ", ".intval($ban_id[$i]) : intval($ban_id[$i]);
		}

		if ($sql_ext_in)
		{
			$sql = "DELETE FROM " . DL_BANLIST_TABLE . "
				WHERE ban_id IN ($sql_ext_in)";

			if( !($db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, "Couldn't delete banlist value(s)", "", __LINE__, __FILE__, $sql);
			}

			message_die(GENERAL_MESSAGE, $lang['Dl_banlist_updated'] . '<br /><br />' . sprintf($lang['Click_return_banlistadmin'], '<a href="' . append_sid('admin_downloads.' . $phpEx . '?submod=banlist') . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . $phpEx . '?pane=right') . '">', '</a>'));
		}

		$action = '';
	}
}

if ($action == '' || $action == 'edit')
{
	$template->set_filenames(array('banlist' => ADM_TPL . 'dl_banlist_body.tpl'));

	$sql = "SELECT * FROM " . DL_BANLIST_TABLE . "
		ORDER BY ban_id";
	if(!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not query banlist', '', __LINE__, __FILE__, $sql);
	}

	$i = 0;

	while ($row = $db->sql_fetchrow($result))
	{
		$row_class = (($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];

		$ban_id = $row['ban_id'];
		$user_id = $row['user_id'];
		$user_ip = ($row['user_ip']) ? decode_ip($row['user_ip']) : '';
		$user_agent = $row['user_agent'];
		$username = $row['username'];
		$guests = $row['guests'];

		$template->assign_block_vars('banlist_row', array(
			'ROW_CLASS' => $row_class,
			'BAN_ID' => $ban_id,
			'USER_ID' => $user_id,
			'USER_IP' => $user_ip,
			'USER_AGENT' => $user_agent,
			'USERNAME' => $username,
			'GUESTS' => ($guests) ? $lang['Yes'] : $lang['No']
			)
		);

		$i++;
	}
	$db->sql_freeresult($result);

	$ban_id = (isset($_POST['ban_id'])) ? $_POST['ban_id'] : array();
	$banlist_id = intval($ban_id[0]);

	$s_hidden_fields = '<input type="hidden" name="action" value="add" />';

	if ($action == 'edit' && $banlist_id)
	{

		$sql = "SELECT * FROM " . DL_BANLIST_TABLE . "
			WHERE ban_id = $banlist_id";
		if(!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not query banlist', '', __LINE__, __FILE__, $sql);
		}

		while ($row = $db->sql_fetchrow($result))
		{
			$ban_id = $row['ban_id'];
			$user_id = $row['user_id'];
			$user_ip = ($row['user_ip']) ? decode_ip($row['user_ip']) : '';
			$user_agent = $row['user_agent'];
			$username = $row['username'];
			$guests = $row['guests'];
			$s_hidden_fields .= '<input type="hidden" name="ban_id" value="' . $ban_id . '" />';
		}
		$db->sql_freeresult($result);
	}
	else
	{
		$ban_id = '';
		$user_id = '';
		$user_ip = '';
		$user_agent = '';
		$username = '';
		$guests = '';
	}

	$template->assign_vars(array(
		'L_DL_BANLIST_EXPLAIN' => $lang['Dl_acp_banlist_explain'],
		'L_DL_USER_ID' => $lang['Dl_user_id'],
		'L_DL_USER_IP' => $lang['Dl_ip'],
		'L_DL_USER_AGENT' => $lang['Dl_browser'],
		'L_DL_USERNAME' => $lang['Username'],
		'L_DL_GUESTS' => $lang['Guest'],
		'L_DL_ADD_NEW' => $lang['Submit'],
		'L_DL_DELETE' => $lang['Dl_delete'],
		'L_DL_YES' => $lang['Yes'],
		'L_DL_NO' => $lang['No'],
		'L_MARK_ALL' => $lang['Mark_all'],
		'L_UNMARK_ALL' => $lang['Unmark_all'],
		'L_DL_EDIT' => $lang['Edit'],

		'DL_USER_ID' => $user_id,
		'DL_USER_IP' => $user_ip,
		'DL_USER_AGENT' => $user_agent,
		'DL_USERNAME' => $username,
		'CHECKED_YES' => ($guests) ? 'checked="checked"' : '',
		'CHECKED_NO' => (!$guests) ? 'checked="checked"' : '',

		'S_HIDDEN_FIELDS' => $s_hidden_fields,
		'S_DOWNLOADS_ACTION' => append_sid('admin_downloads.' . $phpEx . '?submod=banlist')
		)
	);
}

$template->pparse('banlist');

?>