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

/****************************************************************************
/** Functions
/***************************************************************************/
function jr_admin_user_exist($user_id)
{
	global $db, $lang;

	//Do a query and see if our user exists with isset
	$row = sql_query_nivisec(
	'SELECT start_date FROM ' . JR_ADMIN_TABLE . " WHERE user_id = $user_id",
	$lang['Error_Module_Table'],
	false,
	1
	);
	return (isset($row['start_date']));
}

function jr_admin_make_rank_list($user_id, $user_rank)
{
	global $lang;

	/****************
	** Due to a damn bug in some browsers (mozilla firebird for sure)
	** this needs to be disabled for drop down!  return only the name
	** for now.
	****************/
	/*
	//Get a list of ranks and make a nice select box
	$rowset = sql_query_nivisec(
	'SELECT * FROM ' . RANKS_TABLE . " WHERE rank_special = 1
	ORDER BY rank_title ASC",
	$lang['Error_Users_Table'],
	false
	);

	$rank_list = '<select name="user_rank_list_"'.$user_id.'" class="post" size="1">';
	$selected = (0 == $user_rank) ? 'selected="selected"' : '';
	$rank_list .= '<option value="0" '.$selected.'>'.$lang['No_assigned_rank'].'</option>\n';
	for($i = 0; $i < sizeof($rowset); $i++)
	{
	$selected = ($rowset[$i]['rank_id'] == $user_rank) ? ' selected="selected"' : '';
	$rank_list .= '<option value="'.$rowset[$i]['rank_id'].'"'.$selected.'>'.$rowset[$i]['rank_title'].'</option>\n';
	}
	$rank_list .= '</select>';
	*/

	if (empty($user_rank)) return '';

	$row = sql_query_nivisec(
	'SELECT rank_title FROM ' . RANKS_TABLE . " WHERE rank_id = $user_rank",
	$lang['Error_Users_Table'],
	false,
	1
	);

	$rank_list = $row['rank_title'];

	return $rank_list;
}

function jr_admin_make_bookmark_heading($letters_list, $start)
{
	global $lang, $order;

	$sort_item = request_var('sort_item', 'username');

	$separator = ' | ';
	$startb = '[ <a href="' . append_sid('admin_jr_admin.' . PHP_EXT . '?sort_item=' . $sort_item . '&amp;start=0&amp;order=' . $order . '&amp;alphanum=' . strtoupper(chr($first_link))) . '" class="nav">All</a> | ';
	$end = ' ]';

	$list = '';

	$search_list = explode(',', $lang['ASCII_Search_Codes']);

	//Go through each char group
	foreach($search_list as $ord_value)
	{
		//Trim spaces
		$ord_value = trim($ord_value);
		$first_link = false;

		//Check & first
		if (preg_match("/^.+\&.+$/", $ord_value))
		{
			$make_link = false;
			$items = explode('&', $ord_value);
			for($i = $items[0]; $i <= $items[1]; $i++)
			{
				if (isset($letters_list[$i]))
				{
					$make_link = true;
					$first_link = (!$first_link) ? $i : $first_link;
				}
			}
			if ($make_link)
			{
				$list .= '<a href="' . append_sid('admin_jr_admin.' . PHP_EXT . '?sort_item=' . $sort_item . '&amp;start=0&amp;order=' . $order . '&amp;alphanum=0') . '" class="nav">0 - 9</a>';
			}
			else
			{
				$list .= strtoupper(chr($items[0])) . ' - ' . strtoupper(chr($items[1]));
			}
			$list .= $separator;
		}
		//Check for - now
		elseif (preg_match("/^.+\-.+$/", $ord_value))
		{
			$items = explode('-', $ord_value);
			for($i = $items[0]; $i <= $items[1]; $i++)
			{
				if (isset($letters_list[$i]))
				{
					$list .= '<a href="' . append_sid('admin_jr_admin.' . PHP_EXT . '?sort_item=' . $sort_item . '&amp;start=0&amp;order=' . $order . '&amp;alphanum=' . strtoupper(chr($i))) . '" class="nav">' . strtoupper(chr($i)) . '</a>';
				}
				else
				{
					$list .= strtoupper(chr($i));
				}
				$list .= $separator;
			}
		}
		else
		{
			if (isset($letters_list[$ord_value]))
			{
				$list .= '<a href="' . append_sid('admin_jr_admin.' . PHP_EXT . '?sort_item=' . $sort_item . '&amp;start=0&amp;order=' . $order . '&amp;alphanum=' . strtoupper(chr($ord_value))) . '" class="nav">' . strtoupper(chr($ord_value)) . '</a>';
			}
			else
			{
				$list .= strtoupper(chr($ord_value));
			}
			$list .= $separator;
		}
	}

	//Replace the last separator with the ending item
	$list = preg_replace('/' . addcslashes($separator, '|') . '$/', $end, $list);

	return ($startb . $list);
}

?>