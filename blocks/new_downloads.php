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

if(!function_exists('cms_block_new_downloads'))
{
	function cms_block_new_downloads()
	{
		global $db, $cache, $config, $template, $theme, $lang, $table_prefix, $block_id, $cms_config_vars;

		$template->_tpldata['dlrow2.'] = array();

		$sql = "SELECT * FROM " . $table_prefix . "pa_files ORDER BY file_time DESC LIMIT 0," . $cms_config_vars['md_num_new_downloads'][$block_id];
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not query database for the most downloads');
		}

		$i = 1;
		while ($file_latest = $db->sql_fetchrow($result))
		{
			$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];
			$template->assign_block_vars('dlrow2', array(
				'NUMBER_LATEST' => strval($i),
				'FILELINK_LATEST' => append_sid('dload.' . PHP_EXT . '?action=file&file_id=' . $file_latest['file_id']),
				'ROW_CLASS' => $row_class,
				'FILENAME_LATEST' => $file_latest['file_name'],
				'DESCRIP_LATEST' => $file_latest['file_desc'],
				'INFO_LATEST' => create_date_ip($config['default_dateformat'], $file_latest['file_time'], $config['board_timezone'])
				)
			);

			$i++;
		}
	}
}

cms_block_new_downloads();

?>