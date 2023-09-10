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
* (c) 2002 Meik Sievertsen (Acyd Burn)
*
*/

if (!defined('IN_ICYPHOENIX')) define('IN_ICYPHOENIX', true);

if(!empty($setmodules))
{
	$filename = basename(__FILE__);
	$module['1900_Attachments']['150_Extension_control'] = $filename . '?mode=extensions';
	$module['1900_Attachments']['160_Extension_group_manage'] = $filename . '?mode=groups';
	$module['1900_Attachments']['170_Forbidden_extensions'] = $filename . '?mode=forbidden';
	return;
}

// Load default Header
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('pagestart.' . PHP_EXT);

if (!intval($config['allow_ftp_upload']))
{
	if (($config['upload_dir'][0] == '/') || (($config['upload_dir'][0] != '/') && ($config['upload_dir'][1] == ':')))
	{
		$upload_dir = $config['upload_dir'];
	}
	else
	{
		$upload_dir = IP_ROOT_PATH . $config['upload_dir'];
	}
}
else
{
	$upload_dir = $config['download_path'];
}

// Init Vars
$types_download = array(INLINE_LINK, PHYSICAL_LINK);
$modes_download = array('inline', 'physical');

$types_category = array(IMAGE_CAT, STREAM_CAT, SWF_CAT);
$modes_category = array($lang['Category_images'], $lang['Category_stream_files'], $lang['Category_swf_files']);

$size = request_var('size', '');
$mode = request_var('mode', '');
$e_mode = request_var('e_mode', '');

$submit = (isset($_POST['submit'])) ? true : false;

// Extension Management
if ($submit && $mode == 'extensions')
{
	// Change Extensions ?
	$extension_change_list = request_var('extension_change_list', array(0));
	$extension_explain_list = request_var('extension_explain_list', array(''));
	$group_select_list = request_var('group_select', array(0));

	// Generate correct Change List
	$extensions = array();

	for ($i = 0; $i < sizeof($extension_change_list); $i++)
	{
		$extensions['_' . $extension_change_list[$i]]['comment'] = $extension_explain_list[$i];
		$extensions['_' . $extension_change_list[$i]]['group_id'] = intval($group_select_list[$i]);
	}

	$sql = 'SELECT *
		FROM ' . EXTENSIONS_TABLE . '
		ORDER BY ext_id';
	$result = $db->sql_query($sql);
	$num_rows = $db->sql_numrows($result);
	$extension_row = $db->sql_fetchrowset($result);
	$db->sql_freeresult($result);

	if ($num_rows > 0)
	{
		for ($i = 0; $i < sizeof($extension_row); $i++)
		{
			if ($extension_row[$i]['comment'] != $extensions['_' . $extension_row[$i]['ext_id']]['comment'] || intval($extension_row[$i]['group_id']) != intval($extensions['_' . $extension_row[$i]['ext_id']]['group_id']))
			{
				$sql_ary = array(
					'comment' => (string) $extensions['_' . $extension_row[$i]['ext_id']]['comment'],
					'group_id' => (int) $extensions['_' . $extension_row[$i]['ext_id']]['group_id']
				);

				$sql = 'UPDATE ' . EXTENSIONS_TABLE . ' SET ' . $db->sql_build_array('UPDATE', $sql_ary) . '
					WHERE ext_id = ' . (int) $extension_row[$i]['ext_id'];
				$db->sql_query($sql);
			}
		}
	}

	// Delete Extension?
	$extension_id_list = request_var('extension_id_list', array(0));

	$extension_id_sql = implode(', ', $extension_id_list);

	if ($extension_id_sql != '')
	{
		$sql = 'DELETE
			FROM ' . EXTENSIONS_TABLE . '
			WHERE ext_id IN (' . $extension_id_sql . ')';
		$result = $db->sql_query($sql);
	}

	// Add Extension ?
	$extension = request_var('add_extension', '');
	$extension_explain = request_var('add_extension_explain', '');
	$extension_group = request_var('add_group_select', 0);
	$add = (isset($_POST['add_extension_check'])) ? true : false;

	if (($extension != '') && $add)
	{
		$template->assign_vars(array(
			'ADD_EXTENSION' => $extension,
			'ADD_EXTENSION_EXPLAIN' => $extension_explain
			)
		);

		if (!$error)
		{
			// check extension
			$sql = 'SELECT extension
				FROM ' . EXTENSIONS_TABLE;
			$result = $db->sql_query($sql);
			$row = $db->sql_fetchrowset($result);
			$num_rows = $db->sql_numrows($result);
			$db->sql_freeresult($result);

			if ($num_rows > 0)
			{
				for ($i = 0; $i < $num_rows; $i++)
				{
					if (strtolower(trim($row[$i]['extension'])) == strtolower(trim($extension)))
					{
						$error = true;
						if( isset($error_msg) )
						{
							$error_msg .= '<br />';
						}
						$error_msg .= sprintf($lang['Extension_exist'], strtolower(trim($extension)));
					}
				}
			}

			// Extension Forbidden?
			if (!$error)
			{
				$sql = 'SELECT extension
					FROM ' . FORBIDDEN_EXTENSIONS_TABLE;
				$result = $db->sql_query($sql);
				$row = $db->sql_fetchrowset($result);
				$num_rows = $db->sql_numrows($result);
				$db->sql_freeresult($result);

				if ($num_rows > 0)
				{
					for ($i = 0; $i < $num_rows; $i++)
					{
						if (strtolower(trim($row[$i]['extension'])) == strtolower(trim($extension)))
						{
							$error = true;
							if(isset($error_msg))
							{
								$error_msg .= '<br />';
							}
							$error_msg .= sprintf($lang['Unable_add_forbidden_extension'], strtolower(trim($extension)));
						}
					}
				}

			}

			if (!$error)
			{
				$sql_ary = array(
					'group_id' => (int) $extension_group,
					'extension' => (string) strtolower($extension),
					'comment' => (string) $extension_explain
				);

				$sql = 'INSERT INTO ' . EXTENSIONS_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_ary);
				$db->sql_query($sql);
			}
		}
	}

	if (!$error)
	{
		$message = $lang['Attach_config_updated'] . '<br /><br />' . sprintf($lang['Click_return_attach_config'], '<a href="' . append_sid('admin_extensions.' . PHP_EXT . '?mode=extensions') . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

		message_die(GENERAL_MESSAGE, $message);
	}
}

if ($mode == 'extensions')
{
	// Extensions
	$template->set_filenames(array('body' => ADM_TPL . 'attach_extensions.tpl'));

	$template->assign_vars(array(
		'L_EXTENSIONS_TITLE' => $lang['Manage_extensions'],
		'L_EXTENSIONS_EXPLAIN' => $lang['Manage_extensions_explain'],
		'L_SELECT' => $lang['Select'],
		'L_EXPLANATION' => $lang['Explanation'],
		'L_EXTENSION' => $lang['Extension'],
		'L_EXTENSION_GROUP' => $lang['Extension_group'],
		'L_ADD_NEW' => $lang['Add_new'],
		'L_DELETE' => $lang['Delete'],
		'L_CANCEL' => $lang['Cancel'],
		'L_SUBMIT' => $lang['Submit'],

		'S_CANCEL_ACTION' => append_sid('admin_extensions.' . PHP_EXT . '?mode=extensions'),
		'S_ATTACH_ACTION' => append_sid('admin_extensions.' . PHP_EXT . '?mode=extensions')
		)
	);

	if ($submit)
	{
		$template->assign_vars(array(
			'S_ADD_GROUP_SELECT' => group_select('add_group_select', $extension_group)
			)
		);
	}
	else
	{
		$template->assign_vars(array(
			'S_ADD_GROUP_SELECT' => group_select('add_group_select')
			)
		);
	}

	$sql = 'SELECT *
		FROM ' . EXTENSIONS_TABLE . '
		ORDER BY group_id';
	$result = $db->sql_query($sql);
	$extension_row = $db->sql_fetchrowset($result);
	$num_extension_row = $db->sql_numrows($result);
	$db->sql_freeresult($result);

	if ($num_extension_row > 0)
	{
		$extension_row = sort_multi_array($extension_row, 'group_name', 'ASC');

		for ($i = 0; $i < $num_extension_row; $i++)
		{
			if ($submit)
			{
				$template->assign_block_vars('extension_row', array(
					'EXT_ID' => $extension_row[$i]['ext_id'],
					'EXTENSION' => $extension_row[$i]['extension'],
					'EXTENSION_EXPLAIN' => $extension_explain_list[$i],
					'S_GROUP_SELECT' => group_select('group_select[]', $group_select_list[$i])
					)
				);
			}
			else
			{
				$template->assign_block_vars('extension_row', array(
					'EXT_ID' => $extension_row[$i]['ext_id'],
					'EXTENSION' => $extension_row[$i]['extension'],
					'EXTENSION_EXPLAIN' => $extension_row[$i]['comment'],
					'S_GROUP_SELECT' => group_select('group_select[]', $extension_row[$i]['group_id'])
					)
				);
			}
		}
	}

}

// Extension Groups
if ($submit && $mode == 'groups')
{
	// Change Extension Groups ?
	$group_change_list = request_var('group_change_list', array(0));
	$extension_group_list = request_var('extension_group_list', array(''));
	$group_allowed_list = request_var('allowed_list', array(0));
	$download_mode_list = request_var('download_mode_list', array(0));
	$category_list = request_var('category_list', array(0));
	$upload_icon_list = request_var('upload_icon_list', array(''));
	$filesize_list = request_var('max_filesize_list', array(0));
	$size_select_list = request_var('size_select_list', array(''));

	$allowed_list = array();

	for ($i = 0; $i < sizeof($group_allowed_list); $i++)
	{
		for ($j = 0; $j < sizeof($group_change_list); $j++)
		{
			if ($group_allowed_list[$i] == $group_change_list[$j])
			{
				$allowed_list[$j] = 1;
			}
		}
	}

	for ($i = 0; $i < sizeof($group_change_list); $i++)
	{
		$allowed = (isset($allowed_list[$i])) ? 1 : 0;

		$filesize_list[$i] = ($size_select_list[$i] == 'kb') ? round($filesize_list[$i] * 1024) : ( ($size_select_list[$i] == 'mb') ? round($filesize_list[$i] * 1048576) : $filesize_list[$i] );

		$sql_ary = array(
			'group_name' => (string) $extension_group_list[$i],
			'cat_id' => (int) $category_list[$i],
			'allow_group' => (int) $allowed,
			'download_mode' => (int) $download_mode_list[$i],
			'upload_icon' => (string) $upload_icon_list[$i],
			'max_filesize' => (int) $filesize_list[$i]
		);

		$sql = 'UPDATE ' . EXTENSION_GROUPS_TABLE . ' SET ' . $db->sql_build_array('UPDATE', $sql_ary) . '
			WHERE group_id = ' . (int) $group_change_list[$i];
		$result = $db->sql_query($sql);
	}

	// Delete Extension Groups
	$group_id_list = request_var('group_id_list', array(0));

	$group_id_sql = implode(', ', $group_id_list);

	if ($group_id_sql != '')
	{
		$sql = 'DELETE
			FROM ' . EXTENSION_GROUPS_TABLE . '
			WHERE group_id IN (' . $group_id_sql . ')';
		$result = $db->sql_query($sql);

		// Set corresponding Extensions to a pending Group
		$sql = 'UPDATE ' . EXTENSIONS_TABLE . '
			SET group_id = 0
			WHERE group_id IN (' . $group_id_sql . ')';
		$result = $db->sql_query($sql);
	}

	// Add Extensions?
	$extension_group = request_var('add_extension_group', '');
	$download_mode = request_var('add_download_mode', 0);
	$cat_id = request_var('add_category', 0);
	$upload_icon = request_var('add_upload_icon', '');
	$filesize = request_var('add_max_filesize', 0);
	$size_select = request_var('add_size_select', '');

	$is_allowed = (isset($_POST['add_allowed'])) ? 1 : 0;
	$add = (isset($_POST['add_extension_group_check'])) ? true : false;

	if ($extension_group != '' && $add)
	{
		// check Extension Group
		$sql = 'SELECT group_name
			FROM ' . EXTENSION_GROUPS_TABLE;
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrowset($result);
		$num_rows = $db->sql_numrows($result);
		$db->sql_freeresult($result);

		if ($num_rows > 0)
		{
			for ($i = 0; $i < $num_rows; $i++)
			{
				if ($row[$i]['group_name'] == $extension_group)
				{
					$error = true;
					if( isset($error_msg) )
					{
						$error_msg .= '<br />';
					}
					$error_msg .= sprintf($lang['Extension_group_exist'], $extension_group);
				}
			}
		}

		if (!$error)
		{
			$filesize = ($size_select == 'kb') ? round($filesize * 1024) : ( ($size_select == 'mb') ? round($filesize * 1048576) : $filesize );

			$sql_ary = array(
				'group_name' => (string) $extension_group,
				'cat_id' => (int) $cat_id,
				'allow_group' => (int) $is_allowed,
				'download_mode' => (int) $download_mode,
				'upload_icon' => (string) $upload_icon,
				'max_filesize' => (int) $filesize,
				'forum_permissions' => ''
			);

			$sql = 'INSERT INTO ' . EXTENSION_GROUPS_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_ary);
			$db->sql_query($sql);
		}
	}

	if (!$error)
	{
		$message = $lang['Attach_config_updated'] . '<br /><br />' . sprintf($lang['Click_return_attach_config'], '<a href="' . append_sid('admin_extensions.' . PHP_EXT . '?mode=groups') . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

		message_die(GENERAL_MESSAGE, $message);
	}
}

if ($mode == 'groups')
{
	// Extension Groups
	$template->set_filenames(array('body' => ADM_TPL . 'attach_extension_groups.tpl'));

	if (!$size && !$submit)
	{
		$max_add_filesize = $config['max_filesize'];

		$size = ($max_add_filesize >= 1048576) ? 'mb' : ( ($max_add_filesize >= 1024) ? 'kb' : 'b' );
	}

	if ($max_add_filesize >= 1048576)
	{
		$max_add_filesize = round($max_add_filesize / 1048576 * 100) / 100;
	}
	elseif ( $max_add_filesize >= 1024)
	{
		$max_add_filesize = round($max_add_filesize / 1024 * 100) / 100;
	}

	$viewgroup = request_var(POST_GROUPS_URL, 0);

	$template->assign_vars(array(
		'L_EXTENSION_GROUPS_TITLE' => $lang['Manage_extension_groups'],
		'L_EXTENSION_GROUPS_EXPLAIN' => $lang['Manage_extension_groups_explain'],
		'L_EXTENSION_GROUP' => $lang['Extension_group'],
		'L_ADD_NEW' => $lang['Add_new'],
		'L_ALLOWED' => $lang['Allowed'],
		'L_DELETE' => $lang['Delete'],
		'L_CANCEL' => $lang['Cancel'],
		'L_SUBMIT' => $lang['Submit'],
		'L_SPECIAL_CATEGORY' => $lang['Special_category'],
		'L_DOWNLOAD_MODE' => $lang['Download_mode'],
		'L_UPLOAD_ICON' => $lang['Upload_icon'],
		'L_MAX_FILESIZE' => $lang['Max_groups_filesize'],
		'L_ALLOWED_FORUMS' => $lang['Allowed_forums'],
		'L_FORUM_PERMISSIONS' => $lang['Ext_group_permissions'],

		'ADD_GROUP_NAME' => (isset($submit)) ? $extension_group : '',
		'MAX_FILESIZE' => $max_add_filesize,

		'S_FILESIZE' => size_select('add_size_select', $size),
		'S_ADD_DOWNLOAD_MODE' => download_select('add_download_mode'),
		'S_SELECT_CAT' => category_select('add_category'),
		'S_CANCEL_ACTION' => append_sid('admin_extensions.' . PHP_EXT . '?mode=groups'),
		'S_ATTACH_ACTION' => append_sid('admin_extensions.' . PHP_EXT . '?mode=groups')
		)
	);

	$sql = 'SELECT *
		FROM ' . EXTENSION_GROUPS_TABLE;
	$result = $db->sql_query($sql);
	$extension_group = $db->sql_fetchrowset($result);
	$num_extension_group = $db->sql_numrows($result);
	$db->sql_freeresult($result);

	for ($i = 0; $i < $num_extension_group; $i++)
	{
		// Format the filesize
		if (!$extension_group[$i]['max_filesize'])
		{
			$extension_group[$i]['max_filesize'] = $config['max_filesize'];
		}

		$size_format = ($extension_group[$i]['max_filesize'] >= 1048576) ? 'mb' : ( ($extension_group[$i]['max_filesize'] >= 1024) ? 'kb' : 'b' );

		if ($extension_group[$i]['max_filesize'] >= 1048576)
		{
			$extension_group[$i]['max_filesize'] = round($extension_group[$i]['max_filesize'] / 1048576 * 100) / 100;
		}
		else if ($extension_group[$i]['max_filesize'] >= 1024)
		{
			$extension_group[$i]['max_filesize'] = round($extension_group[$i]['max_filesize'] / 1024 * 100) / 100;
		}

		$s_allowed = ($extension_group[$i]['allow_group'] == 1) ? 'checked="checked"' : '';

		$template->assign_block_vars('grouprow', array(
			'GROUP_ID' => $extension_group[$i]['group_id'],
			'EXTENSION_GROUP' => $extension_group[$i]['group_name'],
			'UPLOAD_ICON' => $extension_group[$i]['upload_icon'],

			'S_ALLOW_SELECTED' => $s_allowed,
			'S_SELECT_CAT' => category_select('category_list[]', $extension_group[$i]['group_id']),
			'S_DOWNLOAD_MODE' => download_select('download_mode_list[]', $extension_group[$i]['group_id']),
			'S_FILESIZE' => size_select('size_select_list[]', $size_format),

			'MAX_FILESIZE' => $extension_group[$i]['max_filesize'],
			'CAT_BOX' => ($viewgroup == $extension_group[$i]['group_id']) ? $lang['Decollapse'] : $lang['Collapse'],
			'U_VIEWGROUP' => ($viewgroup == $extension_group[$i]['group_id']) ? append_sid('admin_extensions.' . PHP_EXT . '?mode=groups') : append_sid('admin_extensions.' . PHP_EXT . '?mode=groups&amp;' . POST_GROUPS_URL . '=' . $extension_group[$i]['group_id']),
			'U_FORUM_PERMISSIONS' => append_sid('admin_extensions.' . PHP_EXT . '?mode=' . $mode . '&amp;e_mode=perm&amp;e_group=' . $extension_group[$i]['group_id'])
			)
		);

		if ($viewgroup && $viewgroup == $extension_group[$i]['group_id'])
		{
			$sql = 'SELECT comment, extension
				FROM ' . EXTENSIONS_TABLE . '
				WHERE group_id = ' . (int) $viewgroup;
			$result = $db->sql_query($sql);
			$extension = $db->sql_fetchrowset($result);
			$num_extension = $db->sql_numrows($result);
			$db->sql_freeresult($result);

			for ($j = 0; $j < $num_extension; $j++)
			{
				$template->assign_block_vars('grouprow.extensionrow', array(
					'EXPLANATION' => $extension[$j]['comment'],
					'EXTENSION' => $extension[$j]['extension']
					)
				);
			}
		}
	}
}

// Forbidden Extensions
if ($submit && $mode == 'forbidden')
{
	// Store new forbidden extension or delete selected forbidden extensions
	$extension = request_var('extension_id_list', array(0));

	$extension_id_sql = implode(', ', $extension);

	if ($extension_id_sql != '')
	{
		$sql = 'DELETE
			FROM ' . FORBIDDEN_EXTENSIONS_TABLE . '
			WHERE ext_id IN (' . $extension_id_sql . ')';
		$result = $db->sql_query($sql);
	}

	$extension = request_var('add_extension', '');
	$add = (isset($_POST['add_extension_check'])) ? true : false;

	if ($extension != '' && $add)
	{
		// Check Extension
		$sql = 'SELECT extension
			FROM ' . FORBIDDEN_EXTENSIONS_TABLE;
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrowset($result);
		$num_rows = $db->sql_numrows($result);
		$db->sql_freeresult($result);

		if ($num_rows > 0)
		{
			for ($i = 0; $i < $num_rows; $i++)
			{
				if ($row[$i]['extension'] == $extension)
				{
					$error = true;
					if (isset($error_msg))
					{
						$error_msg .= '<br />';
					}
					$error_msg .= sprintf($lang['Forbidden_extension_exist'], $extension);
				}
			}
		}

		// Check, if extension is allowed
		if (!$error)
		{
			$sql = 'SELECT extension
				FROM ' . EXTENSIONS_TABLE;
			$result = $db->sql_query($sql);
			$row = $db->sql_fetchrowset($result);
			$num_rows = $db->sql_numrows($result);
			$db->sql_freeresult($result);

			if ($num_rows > 0)
			{
				for ($i = 0; $i < $num_rows; $i++)
				{
					if (strtolower(trim($row[$i]['extension'])) == strtolower(trim($extension)))
					{
						$error = true;
						if( isset($error_msg) )
						{
							$error_msg .= '<br />';
						}
						$error_msg .= sprintf($lang['Extension_exist_forbidden'], $extension);
					}
				}
			}
		}

		if (!$error)
		{
			$sql = 'INSERT INTO ' . FORBIDDEN_EXTENSIONS_TABLE . " (extension)
				VALUES ('" . $db->sql_escape(strtolower($extension)) . "')";
			$db->sql_query($sql);
		}
	}

	if (!$error)
	{
		$message = $lang['Attach_config_updated'] . '<br /><br />' . sprintf($lang['Click_return_attach_config'], '<a href="' . append_sid('admin_extensions.' . PHP_EXT . '?mode=forbidden') . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

		message_die(GENERAL_MESSAGE, $message);
	}

}

if ($mode == 'forbidden')
{
	$template->set_filenames(array('body' => ADM_TPL . 'attach_forbidden_extensions.tpl'));

	$template->assign_vars(array(
		'S_ATTACH_ACTION' => append_sid('admin_extensions.' . PHP_EXT . '?mode=forbidden'),

		'L_EXTENSIONS_TITLE' => $lang['Manage_forbidden_extensions'],
		'L_EXTENSIONS_EXPLAIN' => $lang['Manage_forbidden_extensions_explain'],
		'L_EXTENSION' => $lang['Extension'],
		'L_ADD_NEW' => $lang['Add_new'],
		'L_SUBMIT' => $lang['Submit'],
		'L_DELETE' => $lang['Delete']
		)
	);

	$sql = 'SELECT *
		FROM ' . FORBIDDEN_EXTENSIONS_TABLE . '
		ORDER BY extension';
	$result = $db->sql_query($sql);
	$extensionrow = $db->sql_fetchrowset($result);
	$num_extensionrow = $db->sql_numrows($result);
	$db->sql_freeresult($result);

	if ($num_extensionrow > 0)
	{
		for ($i = 0; $i < $num_extensionrow; $i++)
		{
			$template->assign_block_vars('extensionrow', array(
				'EXTENSION_ID' => $extensionrow[$i]['ext_id'],
				'EXTENSION_NAME' => $extensionrow[$i]['extension']
				)
			);
		}
	}
}

if ($e_mode == 'perm')
{
	$group = request_var('e_group', 0);

	$add_forum = (isset($_POST['add_forum'])) ? true : false;
	$delete_forum = (isset($_POST['del_forum'])) ? true : false;

	if (isset($_POST['close_perm']))
	{
		$e_mode = '';
	}
}

// Add Forums
if ($add_forum && $e_mode == 'perm' && $group)
{
	$add_forums_list = request_var('entries', array(0));
	$add_all_forums = false;

	for ($i = 0; $i < sizeof($add_forums_list); $i++)
	{
		if ($add_forums_list[$i] == GPERM_ALL)
		{
			$add_all_forums = true;
		}
	}

	// If we add ALL FORUMS, we are able to overwrite the Permissions
	if ($add_all_forums)
	{
		$sql = 'UPDATE ' . EXTENSION_GROUPS_TABLE . " SET forum_permissions = '' WHERE group_id = " . (int) $group;
		$result = $db->sql_query($sql);
	}

	// Else we have to add Permissions
	if (!$add_all_forums)
	{
		$sql = 'SELECT forum_permissions
			FROM ' . EXTENSION_GROUPS_TABLE . '
			WHERE group_id = ' . intval($group) . '
			LIMIT 1';
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);

		if (trim($row['forum_permissions']) == '')
		{
			$auth_p = array();
		}
		else
		{
			$auth_p = auth_unpack($row['forum_permissions']);
		}

		// Generate array for Auth_Pack, do not add doubled forums
		for ($i = 0; $i < sizeof($add_forums_list); $i++)
		{
			if (!in_array($add_forums_list[$i], $auth_p))
			{
				$auth_p[] = $add_forums_list[$i];
			}
		}

		$auth_bitstream = auth_pack($auth_p);

		$sql = 'UPDATE ' . EXTENSION_GROUPS_TABLE . " SET forum_permissions = '" . $db->sql_escape($auth_bitstream) . "' WHERE group_id = " . (int) $group;
		$result = $db->sql_query($sql);
	}

}

// Delete Forums
if ($delete_forum && $e_mode == 'perm' && $group)
{
	$delete_forums_list = request_var('entries', array(0));

	// Get the current Forums
	$sql = 'SELECT forum_permissions
		FROM ' . EXTENSION_GROUPS_TABLE . '
		WHERE group_id = ' . intval($group) . '
		LIMIT 1';
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);

	$auth_p2 = auth_unpack(trim($row['forum_permissions']));
	$auth_p = array();

	// Generate array for Auth_Pack, delete the chosen ones
	for ($i = 0; $i < sizeof($auth_p2); $i++)
	{
		if (!in_array($auth_p2[$i], $delete_forums_list))
		{
			$auth_p[] = $auth_p2[$i];
		}
	}

	$auth_bitstream = (sizeof($auth_p) > 0) ? auth_pack($auth_p) : '';

	$sql = 'UPDATE ' . EXTENSION_GROUPS_TABLE . " SET forum_permissions = '" . $db->sql_escape($auth_bitstream) . "' WHERE group_id = " . (int) $group;
	$result = $db->sql_query($sql);
}

// Display the Group Permissions Box for configuring it
if ($e_mode == 'perm' && $group)
{
	$template->set_filenames(array('perm_box' => ADM_TPL . 'extension_groups_permissions.tpl'));

	$sql = 'SELECT group_name, forum_permissions
		FROM ' . EXTENSION_GROUPS_TABLE . '
		WHERE group_id = ' . intval($group) . '
		LIMIT 1';
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);

	$group_name = $row['group_name'];
	$allowed_forums = trim($row['forum_permissions']);

	$forum_perm = array();

	if ($allowed_forums == '')
	{
		$forum_perm[0]['forum_id'] = 0;
		$forum_perm[0]['forum_name'] = $lang['Perm_all_forums'];
	}
	else
	{
		$forum_p = array();
		$act_id = 0;
		$forum_p = auth_unpack($allowed_forums);

		$sql = "SELECT forum_id, forum_name FROM " . FORUMS_TABLE . " WHERE forum_id IN (" . implode(', ', $forum_p) . ")";
		$result = $db->sql_query($sql);

		while ($row = $db->sql_fetchrow($result))
		{
			$forum_perm[$act_id]['forum_id'] = $row['forum_id'];
			$forum_perm[$act_id]['forum_name'] = $row['forum_name'];
			$act_id++;
		}
	}

	for ($i = 0; $i < sizeof($forum_perm); $i++)
	{
		$template->assign_block_vars('allow_option_values', array(
			'VALUE' => $forum_perm[$i]['forum_id'],
			'OPTION' => $forum_perm[$i]['forum_name']
			)
		);
	}

	$template->assign_vars(array(
		'L_GROUP_PERMISSIONS_TITLE' => sprintf($lang['Group_permissions_title'], trim($group_name)),
		'L_GROUP_PERMISSIONS_EXPLAIN' => $lang['Group_permissions_explain'],
		'L_REMOVE_SELECTED' => $lang['Remove_selected'],
		'L_CLOSE_WINDOW' => $lang['Close_window'],
		'L_ADD_FORUMS' => $lang['Add_forums'],
		'L_ADD_SELECTED' => $lang['Add_selected'],
		'L_RESET' => $lang['Reset'],
		'A_PERM_ACTION' => append_sid('admin_extensions.' . PHP_EXT . '?mode=groups&amp;e_mode=perm&amp;e_group=' . $group)
		)
	);

	$forum_option_values = array(GPERM_ALL => $lang['Perm_all_forums']);

	$sql = "SELECT forum_id, forum_name FROM " . FORUMS_TABLE . " WHERE forum_type = " . FORUM_POST;
	$result = $db->sql_query($sql);

	while ($row = $db->sql_fetchrow($result))
	{
		$forum_option_values[intval($row['forum_id'])] = $row['forum_name'];
	}
	$db->sql_freeresult($result);

	foreach ($forum_option_values as $value => $option)
	{
		$template->assign_block_vars('forum_option_values', array(
			'VALUE' => $value,
			'OPTION' => $option
			)
		);
	}

	$template->assign_var_from_handle('GROUP_PERMISSIONS_BOX', 'perm_box');

	$empty_perm_forums = array();


	$sql = "SELECT forum_id, forum_name FROM " . FORUMS_TABLE . " WHERE auth_attachments < " . AUTH_ADMIN . " AND forum_type = " . FORUM_POST;
	$result = $db->sql_query($sql);

	while ($row = $db->sql_fetchrow($f_result))
	{
		$forum_id = $row['forum_id'];

		$sql = "SELECT forum_permissions
			FROM " . EXTENSION_GROUPS_TABLE . "
			WHERE allow_group = 1
			ORDER BY group_name ASC";
		$result = $db->sql_query($sql);
		$rows = $db->sql_fetchrowset($result);
		$num_rows = $db->sql_numrows($result);
		$db->sql_freeresult($result);

		$found_forum = false;

		for ($i = 0; $i < $num_rows; $i++)
		{
			$allowed_forums = auth_unpack(trim($rows[$i]['forum_permissions']));
			if (in_array($forum_id, $allowed_forums) || trim($rows[$i]['forum_permissions']) == '')
			{
				$found_forum = true;
				break;
			}
		}

		if (!$found_forum)
		{
			$empty_perm_forums[$forum_id] = $row['forum_name'];
		}
	}
	$db->sql_freeresult($f_result);

	$message = '';

	foreach ($empty_perm_forums as $forum_id => $forum_name)
	{
		$message .= ( $message == '' ) ? $forum_name : '<br />' . $forum_name;
	}

	if (sizeof($empty_perm_forums) > 0)
	{
		$template->set_filenames(array('perm_reg_header' => 'error_body.tpl'));

		$template->assign_vars(array(
			'ERROR_MESSAGE' => $lang['Note_admin_empty_group_permissions'] . $message
			)
		);

		$template->assign_var_from_handle('PERM_ERROR_BOX', 'perm_reg_header');
	}
}

if ($error)
{
	$template->set_filenames(array('reg_header' => 'error_body.tpl'));

	$template->assign_vars(array(
		'ERROR_MESSAGE' => $error_msg
		)
	);

	$template->assign_var_from_handle('ERROR_BOX', 'reg_header');
}

$template->assign_vars(array(
	'ATTACH_VERSION' => sprintf($lang['Attachment_version'], $config['attach_version'])
	)
);

$template->pparse('body');

include('page_footer_admin.' . PHP_EXT);

?>