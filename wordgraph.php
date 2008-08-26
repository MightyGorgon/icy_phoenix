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

define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.' . $phpEx);

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

$page_title = $lang['Wordgraph'];
$meta_description = '';
$meta_keywords = '';
include($phpbb_root_path . 'includes/page_header.' . $phpEx);

$template->set_filenames(array('body' => 'wordgraph_body.tpl',));

$words_array = array();

$sql = 'SELECT w.word_text, COUNT(*) AS word_count
	FROM ' . SEARCH_WORD_TABLE . ' AS w, ' . SEARCH_MATCH_TABLE . ' AS m
	WHERE m.word_id = w.word_id
	GROUP BY m.word_id
	ORDER BY word_count DESC LIMIT ' . intval($board_config['word_graph_max_words']);

if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not obtain word list', '', __LINE__, __FILE__, $sql);
}

while ( $row = $db->sql_fetchrow($result) )
{
	$word = strtolower($row['word_text']);
	$word_count = $row['word_count'];
	$words_array[$word] = $word_count;
}

$minimum = 1000000;
$maximum = -1000000;

foreach ( array_keys($words_array) as $word )
{
	if ( $words_array[$word] > $maximum )
	{
		$maximum = $words_array[$word];
	}

	if ( $words_array[$word] < $minimum )
	{
		$minimum = $words_array[$word];
	}
}

$words = array_keys($words_array);
sort($words);

foreach ( $words as $word )
{
	$ratio = intval(mt_rand(8, 14));
	$template->assign_block_vars('wordgraph_loop', array(
		'WORD' => ( $board_config['word_graph_word_counts'] ) ? $word . ' (' . $words_array[$word] . ')' : $word,
		'WORD_FONT_SIZE' => $ratio,
		'WORD_SEARCH_URL' => append_sid(SEARCH_MG . '?search_keywords=' . urlencode($word)),
		)
	);
}

$template->assign_vars(array(
	'L_PAGE_TITLE' => $lang['Wordgraph'],
	'L_WORDGRAPH' => $lang['Wordgraph'],
	)
);

$template->pparse('body');

include($phpbb_root_path . 'includes/page_tail.' . $phpEx);

?>