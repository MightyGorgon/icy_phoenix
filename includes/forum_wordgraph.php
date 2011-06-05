<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

$template->set_filenames(array('forum_wordgraph' => 'viewforum_wordgraph.tpl'));

$words_array = array();

if ($config['forum_tags_type'])
{
	$sql = 'SELECT w.word_text, COUNT(*) AS word_count
		FROM ' . SEARCH_WORD_TABLE . ' AS w, ' . SEARCH_MATCH_TABLE . ' AS m, ' . POSTS_TABLE . ' AS p, ' . TOPICS_TABLE . ' AS t
		WHERE m.word_id = w.word_id
			AND m.post_id = p.post_id
			AND p.topic_id = t.topic_id
			AND p.deleted = 0
			AND t.forum_id = ' . $forum_id . '
		GROUP BY m.word_id
		ORDER BY word_count DESC
		LIMIT ' . intval($config['word_graph_max_words']);
	$result = $db->sql_query($sql, 86400, 'forums_wg_' . str_pad($forum_id, 8, '0') . '_', FORUMS_CACHE_FOLDER);
}
else
{
	$sql = "SELECT l.tag_text as word_text, l.tag_count as word_count
					FROM " . TOPICS_TAGS_LIST_TABLE . " l, " . TOPICS_TAGS_MATCH_TABLE . " m
					WHERE m.forum_id = " . $forum_id . "
						AND l.tag_id = m.tag_id
					GROUP BY m.tag_id
					ORDER BY l.tag_count DESC
					LIMIT " . intval($config['word_graph_max_words']);
	$result = $db->sql_query($sql, 86400, 'forums_tags_' . str_pad($forum_id, 8, '0') . '_', FORUMS_CACHE_FOLDER);
}

while ($row = $db->sql_fetchrow($result))
{
	$word = strtolower($row['word_text']);
	$word_count = $row['word_count'];
	$words_array[$word] = $word_count;
}

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

$template->assign_block_vars('forum_wordgraph', array(
	'L_WORDGRAPH' => $lang['Wordgraph'],
	)
);

foreach ($words as $word)
{
	$ratio = intval(mt_rand(8, 14));
	$template->assign_block_vars('forum_wordgraph.wordgraph_loop', array(
		'WORD' => htmlspecialchars($word) . (($config['word_graph_word_counts']) ? (' (' . $words_array[$word] . ')') : ''),
		'WORD_FONT_SIZE' => $ratio,
		'WORD_SEARCH_URL' => $config['forum_tags_type'] ? append_sid(CMS_PAGE_SEARCH . '?search_keywords=' . htmlspecialchars(urlencode($word))) :  append_sid(CMS_PAGE_TAGS . '?mode=view&amp;tag_text=' . htmlspecialchars(urlencode($word))),
		)
	);
}

$template->assign_var_from_handle('FORUM_WORDGRAPH', 'forum_wordgraph');

?>