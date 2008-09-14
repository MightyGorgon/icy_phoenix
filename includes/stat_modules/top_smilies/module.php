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

// Result Cache used here

// Top Smilies

// Start user modifiable variables

//
// Set smile_pref to 0, if you want that smilies are only counted once per post.
// This means that, if the same smilie is entered ten times in a message, only one is counted in that message.
//
$smile_pref = 0;

// Functions

//
// sort multi-dimensional array - from File Attachment Mod
//
function smilies_sort_multi_array_attachment ($sort_array, $key, $sort_order)
{
	$last_element = count($sort_array) - 1;

	$string_sort = (is_string($sort_array[$last_element-1][$key])) ? true : false;

	for ($i = 0; $i < $last_element; $i++)
	{
		$num_iterations = $last_element - $i;

		for ($j = 0; $j < $num_iterations; $j++)
		{
			$next = 0;

			//
			// do checks based on key
			//
			$switch = false;
			if (!($string_sort))
			{
				if ((($sort_order == 'DESC') && (intval($sort_array[$j][$key]) < intval($sort_array[$j + 1][$key]))) || (($sort_order == 'ASC') &&    (intval($sort_array[$j][$key]) > intval($sort_array[$j + 1][$key]))))
				{
					$switch = true;
				}
			}
			else
			{
				if ((($sort_order == 'DESC') && (strcasecmp($sort_array[$j][$key], $sort_array[$j + 1][$key]) < 0)) || (($sort_order ==   'ASC') && (strcasecmp($sort_array[$j][$key], $sort_array[$j + 1][$key]) > 0)))
				{
					$switch = true;
				}
			}

			if ($switch)
			{
				$temp = $sort_array[$j];
				$sort_array[$j] = $sort_array[$j + 1];
				$sort_array[$j + 1] = $temp;
			}
		}
	}

	return ($sort_array);
}

// END Functions

$template->assign_vars(array(
	'L_TOP_SMILIES' => $lang['module_name_top_smilies'],
	'L_USES' => $lang['Uses'],
	'L_RANK' => $lang['Rank'],
	'L_PERCENTAGE' => $lang['Percent'],
	'L_GRAPH' => $lang['Graph'],
	'L_IMAGE' => $lang['smiley_url'],
	'L_CODE' => $lang['smiley_code']
	)
);

// Most used smilies

// Determine if Caching is used
if (!$statistics->result_cache_used)
{
	@set_time_limit(0);

	// Init Cache -- tells the Stats Mod that we want to use the result cache
	$result_cache->init_result_cache();

	// With every new sql_query insult, the Statistics Mod will end the previous Control. ;)
	$sql = "SELECT code, smile_url FROM " . SMILIES_TABLE;

	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Couldn\'t retrieve smilies data', '', __LINE__, __FILE__, $sql);
	}

	$rows = $db->sql_fetchrowset($result);
	$num_rows = $db->sql_numrows($result);

	$all_smilies = array();
	$total_smilies = 0;
	$where_query = '';
	$smile_group = array();
	$smile_urls = array();
	$smile_urls['url'] = array();
	$count = 0;

	for ($i = 0; $i < $num_rows; $i++)
	{
		$where_query .= ($where_query == '') ? ' (post_text LIKE \'%' . str_replace("'", "\'", $rows[$i]['code']) . '%\')' : ' OR (post_text LIKE \'%' . str_replace("'", "\'", $rows[$i]['code']) . '%\')';

		if (!in_array($rows[$i]['smile_url'], $smile_urls['url']))
		{
			$smile_urls['url'][] = $rows[$i]['smile_url'];
			$smile_urls[$rows[$i]['smile_url']] = $count;
			$count++;
			$all_smilies[$smile_urls[$rows[$i]['smile_url']]]['code'] = str_replace("'", "\'", $rows[$i]['code']);
			$all_smilies[$smile_urls[$rows[$i]['smile_url']]]['smile_url'] = $rows[$i]['smile_url'];
		}

		$smile_group[$smile_urls[$rows[$i]['smile_url']]]['code'][] = str_replace("'", "\'", $rows[$i]['code']);
		$smile_group[$smile_urls[$rows[$i]['smile_url']]]['url'][] = $rows[$i]['smile_url'];

		$all_smilies[$smile_urls[$rows[$i]['smile_url']]]['count'] = 0;
	}

	$sql = "SELECT post_text
	FROM " . POSTS_TEXT_TABLE . "
	WHERE " . $where_query . "
	GROUP BY post_text";

	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Couldn\'t retrieve smilies data', '', __LINE__, __FILE__, $sql);
	}

	$rows = $db->sql_fetchrowset($result);
	$message = '';
	for ($i = 0; $i < count($rows); $i++)
	{
		$message .= $rows[$i]['post_text'];
	}

//	echo ";".$message.";";
	for ($i = 0; $i < count($smile_group); $i++)
	{
		$found = false;
		$match_regexp = '';
		for ($j = 0; $j < count($smile_group[$i]['code']) && $found == false; $j++)
		{
			if ($smile_pref == 0)
			{
				if (strstr($message, $smile_group[$i]['code'][$j]))
				{
					$all_smilies[$i]['count'] = $all_smilies[$i]['count'] + 1;
					$found = true;
				}
			}
			else
			{
				$match_regexp .= ($match_regexp == '') ? '/(?<=.\W|\W.|^\W)' . preg_quote($smile_group[$i]['code'][$j], "/") . '(?=.\W|\W.|\W$)' : '|(?<=.\W|\W.|^\W)' . preg_quote($smile_group[$i]['code'][$j], "/") . '(?=.\W|\W.|\W$)';
			}
		}

		if (!$found)
		{
			if ($match_regexp != '')
			{
				$match_regexp .= '/';
	//			echo '<br /><br />' . $match_regexp . "<br />";
	//			echo "#".$all_smilies[$i]['smile_url']."#";
				preg_match_all($match_regexp, ' ' . $message . ' ', $matches);
	//			echo "<br />-" . count($matches[0]) . "-<br />";
				$all_smilies[$i]['count'] = $all_smilies[$i]['count'] + count($matches[0]);
			}
		}
	}

	for ($i = 0; $i < count($all_smilies); $i++)
	{
		$total_smilies = $total_smilies + $all_smilies[$i]['count'];
	}

	// Sort array
	$all_smilies = smilies_sort_multi_array_attachment($all_smilies, 'count', 'DESC');

	$limit = ($return_limit > count($all_smilies)) ? count($all_smilies) : $return_limit;

	$template->_tpldata['topsmilies.'] = array();
	//reset($template->_tpldata['topsmilies.']);

	for ($i = 0; $i < $limit; $i++)
	{
		$class = ($i % 2) ? $theme['td_class2'] : $theme['td_class1'];

		$statistics->do_math($all_smilies[0]['count'], $all_smilies[$i]['count'], $total_smilies);

		if ($all_smilies[$i]['count'] != 0)
		{
			$template->assign_block_vars('topsmilies', array(
				'RANK' => $i + 1,
				'CLASS' => $class,
				'CODE' => $all_smilies[$i]['code'],
				'USES' => $all_smilies[$i]['count'],
				'PERCENTAGE' => $statistics->percentage,
				'BAR' => $statistics->bar_percent,
				'URL' => '<img src="http://' . $_SERVER['HTTP_HOST'] . $board_config['script_path'] . $board_config['smilies_path'] . '/' . $all_smilies[$i]['smile_url'] . '" alt="' . $all_smilies[$i]['smile_url'] . '" />'
				)
			);
		}

		$result_cache->assign_template_block_vars('topsmilies');
	}
}
else
{
	// Now use the result cache, with block_num_vars we are getting the number of variables within the block
	for ($i = 0; $i < $result_cache->block_num_vars('topsmilies'); $i++)
	{
		$template->assign_block_vars('topsmilies', $result_cache->get_block_array('topsmilies', $i));
	}

}

?>