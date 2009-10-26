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
* masterdavid - Ronald John David
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

if(!function_exists('cms_block_center_downloads'))
{
	function cms_block_center_downloads()
	{
		global $db, $cache, $config, $template, $theme, $images, $lang, $table_prefix, $block_id, $cms_config_vars;

		include(IP_ROOT_PATH . PA_FILE_DB_PATH . 'pafiledb_constants.' . PHP_EXT);

		$template->_tpldata['dlrow.'] = array();
		$template->_tpldata['dlrow2.'] = array();

		$sql = "SELECT * FROM " . PA_FILES_TABLE . "
						WHERE file_approved = '1'
							ORDER BY file_dls DESC LIMIT 0," . $cms_config_vars['md_num_top_downloads'][$block_id];
		$result = $db->sql_query($sql);

		$i = 1;
		while ($file_most = $db->sql_fetchrow($result))
		{
			$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];
			$template->assign_block_vars('dlrow', array(
				'NUMBER_MOST' => strval($i),
				'ROW_CLASS' => $row_class,
				'FILELINK_MOST' => append_sid('dload.' . PHP_EXT . '?action=file&amp;file_id=' . $file_most['file_id']),
				'FILENAME_MOST' => $file_most['file_name'],
				'DESCRIP_MOST' => $file_most['file_desc'],
				'INFO_MOST' => $file_most['file_dls'] . ' ' . $lang['Dls']
				)
			);

			$i++;
		}

		$sql = "SELECT * FROM " . PA_FILES_TABLE . "
						WHERE file_approved = '1'
							ORDER BY file_time DESC LIMIT 0," . $cms_config_vars['md_num_new_downloads'][$block_id];
		$result = $db->sql_query($sql);

		$i = 1;
		while ($file_latest = $db->sql_fetchrow($result))
		{
			$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];
			$template->assign_block_vars('dlrow2', array(
				'NUMBER_LATEST' => strval($i),
				'FILELINK_LATEST' => append_sid('dload.' . PHP_EXT . '?action=file&amp;file_id=' . $file_latest['file_id']),
				'ROW_CLASS' => $row_class,
				'FILENAME_LATEST' => $file_latest['file_name'],
				'DESCRIP_LATEST' => $file_latest['file_desc'],
				'INFO_LATEST' => create_date_ip($config['default_dateformat'], $file_latest['file_time'], $config['board_timezone'])
				)
			);

			$i++;
		}
		$db->sql_freeresult($result);

		$template->assign_vars(array(
			'TOP_DOWNLOADS' => $lang['Top_downloads'],
			'NEW_DOWNLOADS' => $lang['New_downloads']
			)
		);
	}
}

cms_block_center_downloads();

?>