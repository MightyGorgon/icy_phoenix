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

// Total words
$template->assign_vars(array(
	'L_RANK' => $lang['Rank'],
	'L_COUNT' => $lang['Uses2'],
	'L_PERCENTAGE' => $lang['Percent'],
	'L_WORD' => $lang['Word'],
	'L_GRAPH' => $lang['Graph'],
	'MODULE_NAME' => $lang['module_name_top_words']
	)
);

$rank = 0;

$sql = "SELECT COUNT(word_id) total_words FROM ".SEARCH_MATCH_TABLE;
$result = $stat_db->sql_query($sql);
$words_data = $stat_db->sql_fetchrowset($result);
$total_words = $words_data[0]['total_words'];

// Top words SQL
$sql = "SELECT COUNT(swm.word_id) word_count, swm.word_id word_id, swl.word_text word_text FROM " . SEARCH_MATCH_TABLE . " swm, " . SEARCH_WORD_TABLE . " swl WHERE swm.word_id = swl.word_id GROUP BY swm.word_id ORDER BY word_count DESC LIMIT ".$return_limit*10;
$result = $stat_db->sql_query($sql);
$words_count = $stat_db->sql_numrows($result);
$words_data = $stat_db->sql_fetchrowset($result);
$percentage = 0;
$bar_percent = 0;

$stopwords_array = @file(IP_ROOT_PATH . 'language/lang_' . $config['default_lang'] . "/search_stopwords.txt");
@array_push($stopwords_array, 'quot');

$template->_tpldata['stats_row.'] = array();
//reset($template->_tpldata['stats_row.']);

$j = 1;
$firstcount = 0;
for ($i = 0; $i < $words_count && $j<=($return_limit); $i++)
{
	$stopword_found = false;
	for ($k = 0; $k < sizeof($stopwords_array); $k++)
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
	}

	$percentage = 0;
	$bar_percent = 0;
	$cst = ($firstcount > 0) ? (90 / $firstcount) : 90;
	if ($words_data[$i]['word_count'] != 0)
	{
		$percentage = ($total_words) ? round(min(100, ($words_data[$i]['word_count'] / $total_words) * 100), 2) : 0;
		$bar_percent = round($words_data[$i]['word_count'] * $cst);
	}

	$template->assign_block_vars('stats_row', array(
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

?>