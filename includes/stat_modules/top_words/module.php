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

//
//All your code
//
//Vote Images based on the theme path, (i.e. templates/CURRNT_THEME/ is already inserted below)

$rank = 0;

// Total words
$sql = "SELECT COUNT(word_id) total_words FROM ".SEARCH_MATCH_TABLE;
if (!$result = $db->sql_query($sql))
{
	message_die(GENERAL_ERROR, "Couldn't retrieve words data", "", __LINE__, __FILE__, $sql);
}
$words_data = $db->sql_fetchrowset($result);
$total_words = $words_data[0]['total_words'];

//
// Top words SQL
//
$sql = "SELECT COUNT(swm.word_id) word_count, swm.word_id word_id, swl.word_text word_text FROM " . SEARCH_MATCH_TABLE . " swm, " . SEARCH_WORD_TABLE . " swl WHERE swm.word_id = swl.word_id GROUP BY swm.word_id ORDER BY word_count DESC LIMIT ".$return_limit*10;
if (!$result = $db->sql_query($sql))
{
	message_die(GENERAL_ERROR, "Couldn't retrieve words data", "", __LINE__, __FILE__, $sql);
}

$words_count = $db->sql_numrows($result);
$words_data = $db->sql_fetchrowset($result);
$percentage = 0;
$bar_percent = 0;

$stopwords_array = @file(IP_ROOT_PATH . 'language/lang_' . $board_config['default_lang'] . "/search_stopwords.txt");
@array_push($stopwords_array, 'quot');

$template->_tpldata['words.'] = array();
//reset($template->_tpldata['words.']);

$j = 1;
for ($i = 0; $i < $words_count && $j<=($return_limit); $i++)
{
	$stopword_found = false;
	for ($k = 0; $k < count($stopwords_array); $k++)
	{
		$stopword = trim($stopwords_array[$k]);
		if ($words_data[$i]['word_text'] == $stopword)
		{
			$stopword_found = true;
			break;
		}
	}
	if ($stopword_found)
		continue;

	if ($j == 1)
	{
		$firstcount = $words_data[$i]['word_count'];
		$cst = ($firstcount > 0) ? 90 / $firstcount : 90;
	}

	if ($words_data[$i]['word_count'] != 0 )
	{
		$percentage = ($total_words) ? round(min(100, ($words_data[$i]['word_count'] / $total_words) * 100), 2) : 0;
	}
	else
	{
		$percentage = 0;
	}
	$bar_percent = round($words_data[$i]['word_count'] * $cst);

	$template->assign_block_vars('words', array(
		'RANK' => $j,
		'CLASS' => (!($j + 1 % 2)) ? $theme['td_class2'] : $theme['td_class1'],
		'WORD' => $words_data[$i]['word_text'],
		'PERCENTAGE' => $percentage,
		'BAR' => $bar_percent,
		'COUNT' => $words_data[$i]['word_count']
		)
	);
	$j++;
}

$template->assign_vars(array(
	'L_RANK' => $lang['Rank'],
	'L_COUNT' => $lang['Uses2'],
	'L_PERCENTAGE' => $lang['Percent'],
	'L_WORD' => $lang['Word'],
	'L_GRAPH' => $lang['Graph'],
	'MODULE_NAME' => $lang['module_name_top_words']
	)
);

?>
