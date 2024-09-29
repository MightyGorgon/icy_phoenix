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

if(!function_exists('cms_block_wordgraph'))
{
	function cms_block_wordgraph()
	{
		global $db, $config, $template, $lang, $block_id, $cms_config_vars;

		$template->_tpldata['wordgraph_loop.'] = array();

		$words_array = array();

		$sql = 'SELECT w.word_id, w.word_text, COUNT(*) AS word_count
			FROM ' . SEARCH_WORD_TABLE . ' AS w, ' . SEARCH_MATCH_TABLE . ' AS m
			WHERE m.word_id = w.word_id
			GROUP BY m.word_id, w.word_text
			ORDER BY word_count DESC LIMIT ' . intval($cms_config_vars['md_wordgraph_words'][$block_id]);
		$result = $db->sql_query($sql, 0, 'wordgraph_');

		while ($row = $db->sql_fetchrow($result))
		{
			$word = strtolower($row['word_text']);
			$word_count = $row['word_count'];
			$words_array[$word] = $word_count;
		}
		$db->sql_freeresult($result);

		$minimum = 1000000;
		$maximum = -1000000;

		foreach (array_keys($words_array) as $word)
		{
			if ($words_array[$word] > $maximum)
			{
				$maximum = $words_array[$word];
			}

			if ($words_array[$word] < $minimum)
			{
				$minimum = $words_array[$word];
			}
		}

		$words = array_keys($words_array);
		sort($words);

		foreach ($words as $word)
		{
			$ratio = intval(mt_rand(8, 14));
			$template->assign_block_vars('wordgraph_loop', array(
				'WORD' => ($cms_config_vars['md_wordgraph_count'][$block_id]) ? $word . ' (' . $words_array[$word] . ')' : $word,
				'WORD_FONT_SIZE' => $ratio,
				'WORD_SEARCH_URL' => append_sid(CMS_PAGE_SEARCH . '?search_keywords=' . urlencode($word)),
				)
			);
		}

		$template->assign_vars(array(
			'L_WORDGRAPH' => $lang['Wordgraph'],
			)
		);
	}
}

cms_block_wordgraph();

?>