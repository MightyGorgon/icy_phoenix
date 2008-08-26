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

if (!defined('IN_PHPBB'))
{
	die('Hacking attempt');
}

if(!function_exists(imp_top_downloads_block_func))
{
	function imp_top_downloads_block_func()
	{
		global $template, $cms_config_vars, $block_id, $table_prefix, $phpEx, $db, $lang, $theme;

		$sql = "SELECT * FROM " . $table_prefix . "pa_files ORDER BY file_dls DESC LIMIT 0," . $cms_config_vars['md_num_top_downloads'][$block_id];
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not query database for the most downloads');
		}

		$i = 1;
		while ($file_most = $db->sql_fetchrow($result))
		{
			$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];
			$template->assign_block_vars('dlrow', array(
				'NUMBER_MOST' => strval($i),
				'ROW_CLASS' => $row_class,
				'FILELINK_MOST' => append_sid('dload.' . $phpEx . '?action=file&file_id=' . $file_most['file_id']),
				'FILENAME_MOST' => $file_most['file_name'],
				'DESCRIP_MOST' => $file_most['file_desc'],
				'INFO_MOST' => $file_most['file_dls'] . ' ' . $lang['Dls'])
			);

			$i++;
		}
	}
}

imp_top_downloads_block_func();
?>