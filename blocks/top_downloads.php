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

if(!function_exists('cms_block_top_downloads'))
{
	function cms_block_top_downloads()
	{
		global $db, $cache, $template, $theme, $lang, $table_prefix, $block_id, $cms_config_vars;

		$sql = "SELECT * FROM " . $table_prefix . "pa_files ORDER BY file_dls DESC LIMIT 0," . $cms_config_vars['md_num_top_downloads'][$block_id];
		$result = $db->sql_query($sql);

		$i = 1;
		while ($file_most = $db->sql_fetchrow($result))
		{
			$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];
			$template->assign_block_vars('dlrow', array(
				'NUMBER_MOST' => strval($i),
				'ROW_CLASS' => $row_class,
				'FILELINK_MOST' => append_sid('dload.' . PHP_EXT . '?action=file&file_id=' . $file_most['file_id']),
				'FILENAME_MOST' => $file_most['file_name'],
				'DESCRIP_MOST' => $file_most['file_desc'],
				'INFO_MOST' => $file_most['file_dls'] . ' ' . $lang['Dls']
				)
			);

			$i++;
		}
		$db->sql_freeresult($result);
	}
}

cms_block_top_downloads();

?>