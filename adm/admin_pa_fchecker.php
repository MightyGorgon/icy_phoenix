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

if( !empty($setmodules) )
{
	$file = basename(__FILE__);
	$module['2000_Downloads']['130_Fchecker'] = $file;
	return;
}

if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('./pagestart.' . PHP_EXT);
include(IP_ROOT_PATH . 'language/lang_' . $board_config['default_lang'] . '/lang_admin_pafiledb.' . PHP_EXT);
include(IP_ROOT_PATH . PA_FILE_DB_PATH . 'pafiledb_common.' . PHP_EXT);

$this_dir = IP_ROOT_PATH . PA_FILE_DB_PATH . 'uploads/';

$html_path = get_formated_url() . '/' . PA_FILE_DB_PATH . 'uploads/';

if( isset($_GET['safety']) || isset($_POST['safety']) )
{
	$safety = (isset($_POST['safety'])) ? intval($_POST['safety']) : intval($_GET['safety']);
}

$template->set_filenames(array(
		'admin' => ADM_TPL . 'pa_admin_file_checker.tpl')
);

$template->assign_vars(array(
	'L_FILE_CHECKER' => $lang['File_checker'],
	'L_FCHECKER_EXPLAIN' => $lang['File_checker_explain'])
);

if ($safety == 1)
{
	$saved = 0;

	$template->assign_block_vars("check", array());

	$template->assign_vars(array(
		'L_FILE_CHECKER_SP1' => $lang['Checker_sp1'])
	);

	$sql = "SELECT * FROM " . PA_FILES_TABLE;

	if ( !($overall_result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Couldnt Query info', '', __LINE__, __FILE__, $sql);
	}

	while ($temp = $db->sql_fetchrow($overall_result))
	{
		$temp_dlurl = $temp['file_dlurl'];
		if (substr($temp_dlurl,0,strlen($html_path)) !== $html_path)
		{
			continue;
		}

		if (!is_file($this_dir."/".str_replace($html_path, "", $temp_dlurl)))
		{
/*			$sql = "DELETE FROM " . PA_FILES_TABLE . " WHERE file_dlurl = '" . $temp_dlurl . "'";
			if ( !($db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Couldnt Query info', '', __LINE__, __FILE__, $sql);
			}*/
			$template->assign_block_vars("check.check_step1", array(
				'DEL_DURL' => $temp_dlurl)
			);
		}
	}

	$template->assign_vars(array(
		'L_FILE_CHECKER_SP2' => $lang['Checker_sp2'])
	);
	$sql = "SELECT * FROM " . PA_FILES_TABLE;

	if ( !($overall_result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Couldnt Query info', '', __LINE__, __FILE__, $sql);
	}
	while ($temp = $db->sql_fetchrow($overall_result))
	{
		$temp_ssurl = $temp['file_ssurl'];
		$temp_file_id = $temp['file_id'];
		if (substr($temp_ssurl,0,strlen($html_path)) !== $html_path)
		{
			continue;
		}

		if (!is_file($this_dir."/".str_replace($html_path, "", $temp_ssurl)))
		{
			/*$sql = "UPDATE " . PA_FILES_TABLE . " SET file_ssurl='' WHERE file_id = '" . $temp_file_id . "'";

			if ( !($db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Couldnt Query info', '', __LINE__, __FILE__, $sql);
			}*/

			$template->assign_block_vars("check.check_step2", array(
				'DEL_SSURL' => $temp_file_id)
			);
		}
	}

	$template->assign_vars(array(
		'L_FILE_CHECKER_SP3' => $lang['Checker_sp3'])
	);

	$files = opendir($this_dir);
	while ($temp = readdir($files))
	{
		if ($temp == "." || $temp == "..")
		{
			continue;
		}
		if (!is_file($this_dir.$temp))
		{
			continue;
		}

		$sql = "SELECT * FROM " . PA_FILES_TABLE . " WHERE file_dlurl = '" . $html_path.$temp . "' OR file_ssurl = '" . $html_path.$temp . "'";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Couldnt Query info', '', __LINE__, __FILE__, $sql);
		}
		$numhits = $db->sql_numrows($result);

		if (!$numhits)
		{
			$saved = $saved + filesize($this_dir.$temp);
			//unlink($this_dir.$temp);
			$template->assign_block_vars('check.check_step3', array(
				'DEL_FILE' => $temp
				)
			);
		}

	}
	closedir($files);

	if($saved == 0)
	{
		$saved = "N/A";
	}
	elseif($saved >= 1073741824)
	{
		$saved = round($saved / 1073741824 * 100) / 100 . " Giga Byte";
	}
	elseif($saved >= 1048576)
	{
		$saved = round($saved / 1048576 * 100) / 100 . " Mega Byte";
	}
	elseif($saved >= 1024)
	{
		$saved = round($saved / 1024 * 100) / 100 . " Kilo Byte";
	}
	else
	{
		$saved = $saved . " Bytes";
	}

	$template->assign_vars(array(
		'L_FILE_CHECKER_SAVED' => $lang['Checker_saved'],
		'SAVED' => $saved
		)
	);
	$template->pparse('admin');
}
else
{
	$template->assign_block_vars("perform", array());

	$lang['File_saftey'] = str_replace("{html_path}", $html_path, $lang['File_saftey']);

	$template->assign_vars(array(
		'L_FILE_CHECKER' => $lang['File_checker'],
		'L_FILE_PERFORM' => $lang['File_checker_perform'],
		'L_FILE_SAFTEY' => $lang['File_saftey']
		)
	);

	$template->pparse('admin');
}

include('./page_footer_admin.' . PHP_EXT);

?>