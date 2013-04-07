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
* Todd - (todd@phparena.net) - (http://www.phparena.net)
*
*/

define('IN_ICYPHOENIX', true);

if(!empty($setmodules))
{
	$file = basename(__FILE__);
	$module['2000_Downloads']['150_License_title'] = $file;
	return;
}

if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('pagestart.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/pafiledb_common.' . PHP_EXT);

$license = request_var('license', '');
if(!empty($license))
{
	switch($license)
	{
		case 'add':
		{
			$template->set_filenames(array('admin' => ADM_TPL . 'pa_admin_license_add.tpl'));

			$add = request_var('add', '');

			if ($add == 'do')
			{
				$license_name = request_var('license_name', '', true);
				$license_text = request_var('license_text', '', true);

				$sql = "INSERT INTO " . PA_LICENSE_TABLE . " VALUES('NULL', '" . $db->sql_escape($license_name) . "', '" . $db->sql_escape($license_text) . "')";
				$db->sql_query($sql);

				$message = $lang['Licenseadded'] . '<br /><br />' . sprintf($lang['Click_return'], '<a href="' . append_sid('admin_pa_license.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

				message_die(GENERAL_MESSAGE, $message);
			}

			if (empty($add))
			{
				$template->assign_vars(array(
					'S_ADD_LIC_ACTION' => append_sid('admin_pa_license.' . PHP_EXT),
					'L_ALICENSETITLE' => $lang['Alicensetitle'],
					'L_LICENSEEXPLAIN' => $lang['Licenseexplain'],
					'L_LNAME' => $lang['Lname'],
					'L_LTEXT' => $lang['Ltext']
					)
				);
			}

			$template->pparse('admin');

			break;
		}

		case 'edit':
		{
			$template->set_filenames(array('admin' => ADM_TPL . 'pa_admin_license_edit.tpl'));

			$edit = request_var('edit', '');

			if ($edit == 'do')
			{
				$license_name = request_var('license_name', '', true);
				$license_text = request_var('license_text', '', true);

				$id = request_var('id', 0);

				$sql = "UPDATE " . PA_LICENSE_TABLE . " SET license_name = '" . $db->sql_escape($license_name) . "', license_text = '" . $db->sql_escape($license_text) . "' WHERE license_id = '" . $id . "'";
				$db->sql_query($sql);

				$message = $lang['Licenseedited'] . '<br /><br />' . sprintf($lang['Click_return'], '<a href="' . append_sid("admin_pa_license." . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

				message_die(GENERAL_MESSAGE, $message);
			}

			if ($edit == 'form')
			{
				$select = request_var('select', '');

				$sql = "SELECT * FROM " . PA_LICENSE_TABLE . " WHERE license_id = '" . $select . "'";
				$result = $db->sql_query($sql);
				$license = $db->sql_fetchrow($result);
				$text = str_replace("<br />", "\n", $license['license_text']);

				$template->assign_block_vars('license_form', array());

				$template->assign_vars(array(
					'S_EDIT_LIC_ACTION' => append_sid('admin_pa_license.' . PHP_EXT),
					'L_ELICENSETITLE' => $lang['Elicensetitle'],
					'L_LICENSEEXPLAIN' => $lang['Licenseexplain'],
					'L_LNAME' => $lang['Lname'],
					'LICENSE_NAME' => $license['license_name'],
					'TEXT' => $text,
					'SELECT' => $select,
					'L_LTEXT' => $lang['Ltext']
					)
				);
			}

			if (empty($edit))
			{
				$sql = "SELECT * FROM " . PA_LICENSE_TABLE;
				$result = $db->sql_query($sql);

				while ($license = $db->sql_fetchrow($result))
				{
					$row .= '<tr><td width="3%" class="row1 row-center" valign="middle"><input type="radio" name="select" value="' . $license['license_id'] . '"></td><td width="97%" class="row1">' . $license['license_name'] . '</td></tr>';
				}

				$template->assign_block_vars('license', array());

				$template->assign_vars(array(
					'S_EDIT_LIC_ACTION' => append_sid('admin_pa_license.' . PHP_EXT),
					'L_ELICENSETITLE' => $lang['Elicensetitle'],
					'L_LICENSEEXPLAIN' => $lang['Licenseexplain'],
					'ROW' => $row
					)
				);
			}

			$template->pparse('admin');

			break;
		}

		case 'delete':
		{
			$template->set_filenames(array('admin' => ADM_TPL . 'pa_admin_license_delete.tpl'));

			$delete = request_var('delete', '');

			if ($delete == 'do')
			{
				$select = request_var('select', array(''), true);

				if (empty($select))
				{
					$message = $lang['lderror'] . '<br /><br />' . sprintf($lang['Click_return'], '<a href="' . append_sid('admin_pa_license.' . PHP_EXT . '?license=delete') . '">', '</a>');

					message_die(GENERAL_MESSAGE, $message);
				}
				else
				{
					foreach ($select as $key => $value)
					{
						$sql = "DELETE FROM " . PA_LICENSE_TABLE . " WHERE license_id = '" . $key . "'";
						$db->sql_query($sql);

						$sql = "UPDATE " . PA_FILES_TABLE . " SET file_license = '0' WHERE file_license = '$key'";
						$db->sql_query($sql);
					}

					$message = $lang['Ldeleted'] . '<br /><br />' . sprintf($lang['Click_return'], '<a href="' . append_sid("admin_pa_license." . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

					message_die(GENERAL_MESSAGE, $message);
				}
			}

			if (empty($delete))
			{
				$sql = "SELECT * FROM " . PA_LICENSE_TABLE;
				$result = $db->sql_query($sql);

				while ($license = $db->sql_fetchrow($result))
				{
					$row .= '<tr><td width="3%" class="row1 row-center" valign="middle"><input type="checkbox" name="select[' . $license['license_id'] . ']" value="yes"></td><td width="97%" class="row1">' . $license['license_name'] . '</td></tr>';
				}

				$template->assign_vars(array(
					'S_DELETE_LIC_ACTION' => append_sid('admin_pa_license.' . PHP_EXT),
					'L_DLICENSETITLE' => $lang['Dlicensetitle'],
					'L_LICENSEEXPLAIN' => $lang['Licenseexplain'],
					'ROW' => $row
					)
				);

			}

			$template->pparse('admin');

			break;
		}
	}
}
// MX Addon
else
{
	// main
	$template->set_filenames(array('admin' => ADM_TPL . 'pa_admin_license.tpl'));

	$sql = "SELECT * FROM " . PA_LICENSE_TABLE;
	$result = $db->sql_query($sql);

	while ($license = $db->sql_fetchrow($result))
	{
		$row .= '<tr><td width="80%" class="row1 row-center">' . $license['license_name'] . '</td></tr>';
	}

	$template->assign_vars(array(
		'S_DELETE_LIC_ACTION' => append_sid("admin_pa_license." . PHP_EXT),
		'L_LICENSETITLE' => $lang['License_title'],
		'L_ALICENSETITLE' => $lang['Alicensetitle'],
		'L_ELICENSETITLE' => $lang['Elicensetitle'],
		'L_DLICENSETITLE' => $lang['Dlicensetitle'],
		'L_LICENSEEXPLAIN' => $lang['Licenseexplain'],
		'ROW' => $row
		)
	);
	$template->pparse('admin');
}

include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);

?>