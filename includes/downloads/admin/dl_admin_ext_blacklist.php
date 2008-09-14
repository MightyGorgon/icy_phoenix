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

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

if ($cancel)
{
	$action = '';
}

if($action == 'add')
{
	$extention = (isset($_POST['extention'])) ? htmlspecialchars($_POST['extention']) : "";

	if ($extention)
	{
		$sql = "SELECT * FROM " . DL_EXT_BLACKLIST . "
			WHERE extention = '$extention'";
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not check existing file extentions', '', __LINE__, __FILE__, $sql);
		}

		$ext_exist = $db->sql_numrows($result);
		$db->sql_freeresult($result);

		if (!$ext_exist)
		{
			$sql = "INSERT INTO " . DL_EXT_BLACKLIST . "
				(extention) VALUES ('".str_replace("\'", "", $extention)."')";
			$db->sql_query($sql);
		}
	}

	$action = '';
}
elseif($action == 'delete')
{
	$extention = (isset($_POST['extention'])) ? $_POST['extention'] : array();

	if (!$confirm)
	{
		$template->set_filenames(array('confirm_body' => 'dl_confirm_body.tpl'));

		for ($i = 0; $i < count($extention); $i++)
		{
			$s_hidden_fields .= '<input type="hidden" name="extention[]" value="'.htmlspecialchars($extention[$i]).'" />';
		}

		$s_hidden_fields .= '<input type="hidden" name="action" value="delete" />';

		$template->assign_vars(array(
			'MESSAGE_TITLE' => $lang['Information'],
			'MESSAGE_TEXT' => (count($extention) == 1) ? sprintf($lang['Dl_confirm_delete_extention'], $extention[0]) : sprintf($lang['Dl_confirm_delete_extentions'], implode(', ', $extention)),

			'L_DELETE_FILE_TOO' => (count($extention) == 1) ? $lang['Dl_delete_extention_confirm'] : $lang['Dl_delete_extentions_confirm'],

			'L_YES' => $lang['Yes'],
			'L_NO' => $lang['No'],

			'S_CONFIRM_ACTION' => append_sid('admin_downloads.' . PHP_EXT . '?submod=ext_blacklist'),
			'S_HIDDEN_FIELDS' => $s_hidden_fields
			)
		);

		$template->pparse('confirm_body');

		include('./page_footer_admin.' . PHP_EXT);
	}
	else
	{
		$sql_ext_in = '';
		for ($i = 0; $i < count($extention); $i++)
		{
			$sql_ext_in .= ($sql_ext_in != '') ? ", '".htmlspecialchars($extention[$i])."'" : "'".htmlspecialchars($extention[$i])."'";
		}

		if ($sql_ext_in)
		{
			$sql = "DELETE FROM " . DL_EXT_BLACKLIST . "
				WHERE extention IN ($sql_ext_in)";

			if( !($db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, "Couldn't delete file extention(s)", "", __LINE__, __FILE__, $sql);
			}

			$message = ((count($extention) == 1) ? $lang['Extention_removed'] : $lang['Extentions_removed']) . '<br /><br />' . sprintf($lang['Click_return_extblacklistadmin'], '<a href="' . append_sid('admin_downloads.' . PHP_EXT . '?submod=ext_blacklist') . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

			message_die(GENERAL_MESSAGE, $message);
		}

		$action = '';
	}
}

if ($action == '')
{
	$template->set_filenames(array(
		'ext_bl' => ADM_TPL . 'dl_ext_blacklist_body.tpl')
	);

	$sql = "SELECT extention FROM " . DL_EXT_BLACKLIST . "
		ORDER BY extention";
	if(!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not query extentions', '', __LINE__, __FILE__, $sql);
	}

	$i = 0;

	while ($row = $db->sql_fetchrow($result))
	{
		$row_class = (($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];

		$extention = $row['extention'];

		$template->assign_block_vars('extention_row', array(
			'ROW_CLASS' => $row_class,
			'EXTENTION' => $extention
			)
		);

		$i++;
	}

	$template->assign_vars(array(
		'L_DL_EXT_BLACKLIST_EXPLAIN' => $lang['Dl_ext_blacklist_explain'],
		'L_DL_EXTENTION' => $lang['Dl_extention'],
		'L_DL_EXTENTIONS' => $lang['Dl_extentions'],
		'L_DL_ADD_EXTENTION' => $lang['Dl_add_extention'],
		'L_DL_DEL_EXTENTIONS' => $lang['Dl_delete'],
		'L_MARK_ALL' => $lang['Mark_all'],
		'L_UNMARK_ALL' => $lang['Unmark_all'],

		'S_DOWNLOADS_ACTION' => append_sid('admin_downloads.' . PHP_EXT . '?submod=ext_blacklist')
		)
	);
}

$template->pparse('ext_bl');

?>