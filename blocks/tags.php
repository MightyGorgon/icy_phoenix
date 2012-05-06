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
* Bicet
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

if(!function_exists('cms_block_tags'))
{
	function cms_block_tags()
	{
		global $db, $config, $template, $lang, $block_id, $cms_config_vars;

		$template->_tpldata['tags_loop.'] = array();

		// This block requires jquery_ui
		$config['jquery_ui'] = true;

		$sql_sort = (empty($cms_config_vars['md_tags_words'][$block_id]) ? ("l.tag_count DESC, l.tag_text ASC") : ("RAND()"));
		$sql_limit = (int) $cms_config_vars['md_tags_words'][$block_id];
		$sql_limit = (($sql_limit < 0) || ($sql_limit > 500)) ? 50 : $sql_limit;

		$tags = array();
		$sql = "SELECT l.*
						FROM " . TOPICS_TAGS_LIST_TABLE . " l
						ORDER BY " . $sql_sort . "
						LIMIT 0, " . $sql_limit;
		// Cache results for one our!
		$result = $db->sql_query($sql, 3600, 'tags_');
		$tags = $db->sql_fetchrowset($result);
		$db->sql_freeresult($result);

		$row_class = '';
		$i = 0;
		foreach ($tags as $tag)
		{
			$row_class = ip_zebra_rows($row_class);
			$tag_font_size = intval(mt_rand(8, 12));
			$template->assign_block_vars('tags_loop', array(
				'CLASS' => $row_class,
				'ROW_NUMBER' => $i + 1,

				'U_TAG_TEXT' => append_sid(CMS_PAGE_TAGS . '?mode=view&amp;tag_text=' . htmlspecialchars(urlencode($tag['tag_text']))),
				'TAG_TEXT' => htmlspecialchars($tag['tag_text']),
				'TAG_FONT_SIZE' => $tag_font_size,
				'TAG_COUNT' => $tag['tag_count'],
				)
			);
			$i++;
		}

		$template->assign_vars(array(
			'U_TAGS_SEARCH_PAGE' => append_sid(CMS_PAGE_TAGS),
			'U_TAGS' => append_sid(CMS_PAGE_TAGS),

			'S_TAGS_BLOCK_ID' => $block_id,
			'S_TAGS_SEARCH' => !empty($cms_config_vars['md_tags_search'][$block_id]) ? true : false,
			'S_TAGS_COUNT' => !empty($cms_config_vars['md_tags_count'][$block_id]) ? true : false,
			)
		);
	}
}

cms_block_tags();

?>