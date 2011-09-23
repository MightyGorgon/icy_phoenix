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
* Jeremy Conley - (pentapenguin@bluebottle.com) - (www.pentapenguin.com)
*
*/

define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup();
// End session management

$words_array = array();

$sql = 'SELECT w.word_text, COUNT(*) AS word_count
	FROM ' . SEARCH_WORD_TABLE . ' AS w, ' . SEARCH_MATCH_TABLE . ' AS m
	WHERE m.word_id = w.word_id
	GROUP BY m.word_id
	ORDER BY word_count DESC LIMIT ' . intval($config['word_graph_max_words']);
$result = $db->sql_query($sql);

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

foreach ($words as $word)
{
	$ratio = intval(mt_rand(8, 14));
	$template->assign_block_vars('wordgraph_loop', array(
		'WORD' => ($config['word_graph_word_counts']) ? $word . ' (' . $words_array[$word] . ')' : $word,
		'WORD_FONT_SIZE' => $ratio,
		'WORD_SEARCH_URL' => append_sid(CMS_PAGE_SEARCH . '?search_keywords=' . urlencode($word)),
		)
	);
}

$template->assign_vars(array(
	'L_PAGE_TITLE' => $lang['Wordgraph'],
	'L_WORDGRAPH' => $lang['Wordgraph'],
	)
);

full_page_generation('wordgraph_body.tpl', $lang['Wordgraph'], '', '');

?>