<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

check_page_auth(0, 'ranks');

$page_title = $lang['Rank_Header'];
$meta_description = '';
$meta_keywords = '';
include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);

$template->set_filenames(array('body' => 'ranks_body.tpl'));

$sql = "SELECT * FROM " . RANKS_TABLE . " ORDER BY rank_special ASC, rank_min ASC";
if(!$result = $db->sql_query($sql))
{
	message_die(GENERAL_ERROR, "Couldn't obtain ranks data", "", __LINE__, __FILE__, $sql);
}

$rank_count = $db->sql_numrows($result);
$rank_rows = $db->sql_fetchrowset($result);

$template->assign_vars(array(
	'L_RANKS_TITLE' => $lang['Rank_Header'],
	'L_RANKS_IMAGE'=> $lang['Rank_Image'],
	'L_RANK_TITLE' => $lang['Rank'],
	'L_RANK_MIN_M' => $lang['Rank_Min_Des']
	)
);

$j = 0;
$k = 0;

for($i = 0; $i < $rank_count; $i++)
{
	$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];

	$special_rank = $rank_rows[$i]['rank_special'];

	switch($special_rank)
	{
		case '-1':
			$rank_special_des = $lang['Rank_Days_Count'];
			break;
		case '0':
			$rank_special_des = $lang['Rank_Posts_Count'];
			break;
		case '1':
			$rank_special_des = $lang['Rank_Special'];
			break;
		case '2':
			$rank_special_des = $lang['Rank_Special_Guest'];
			break;
		case '3':
			$rank_special_des = $lang['Rank_Special_Banned'];
			break;
		default:
			break;
	}

	if ($special_rank > 0)
	{
		$template->assign_block_vars("ranks_special", array(
			"ROW_CLASS" => $row_class,
			"IMAGE"=> $rank_rows[$i]['rank_image'],
			"RANK" => $rank_rows[$i]['rank_title'],
			"RANK_SPECIAL_DES" => $rank_special_des
			)
		);
		$j++;
	}
	else
	{
		$template->assign_block_vars("ranks_normal", array(
			"ROW_CLASS" => $row_class,
			"RANK" => $rank_rows[$i]['rank_title'],
			"RANK_MIN" => $rank_rows[$i]['rank_min'],
			"RANK_SPECIAL_DES" => $rank_special_des
			)
		);
		if ($rank_rows[$i]['rank_image'])
		{
			$template->assign_block_vars("ranks_normal.switch_image", array(
				"IMAGE"=> $rank_rows[$i]['rank_image'],
				"RANK" => $rank_rows[$i]['rank_title']
				)
			);
		}

		$k++;
	}
}

if ($j == 0)
{
	$template->assign_block_vars("ranks_no_special", array(
		"L_RANK_NO_SPECIAL" => $lang['No_Ranks_Special']
		)
	);
}

if ($k == 0)
{
	$template->assign_block_vars("ranks_no_normal", array(
		"L_RANK_NO_NORMAL" => $lang['No_Ranks']
		)
	);
}

$template->pparse('body');

include(IP_ROOT_PATH . 'includes/page_tail.' . PHP_EXT);
?>