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

$cms_page['page_id'] = 'ranks';
$cms_page['page_nav'] = (!empty($cms_config_layouts[$cms_page['page_id']]['page_nav']) ? true : false);
$cms_page['global_blocks'] = (!empty($cms_config_layouts[$cms_page['page_id']]['global_blocks']) ? true : false);
$cms_auth_level = (isset($cms_config_layouts[$cms_page['page_id']]['view']) ? $cms_config_layouts[$cms_page['page_id']]['view'] : AUTH_ALL);
check_page_auth($cms_page['page_id'], $cms_auth_level);

$sql = "SELECT * FROM " . RANKS_TABLE . " ORDER BY rank_special ASC, rank_min ASC";
$result = $db->sql_query($sql);
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
		$template->assign_block_vars('ranks_special', array(
			'ROW_CLASS' => $row_class,
			'IMAGE'=> $rank_rows[$i]['rank_image'],
			'RANK' => $rank_rows[$i]['rank_title'],
			'RANK_SPECIAL_DES' => $rank_special_des
			)
		);
		$j++;
	}
	else
	{
		$template->assign_block_vars('ranks_normal', array(
			'ROW_CLASS' => $row_class,
			'RANK' => $rank_rows[$i]['rank_title'],
			'RANK_MIN' => $rank_rows[$i]['rank_min'],
			'RANK_SPECIAL_DES' => $rank_special_des
			)
		);
		if ($rank_rows[$i]['rank_image'])
		{
			$template->assign_block_vars('ranks_normal.switch_image', array(
				'IMAGE'=> $rank_rows[$i]['rank_image'],
				'RANK' => $rank_rows[$i]['rank_title']
				)
			);
		}

		$k++;
	}
}

if ($j == 0)
{
	$template->assign_block_vars('ranks_no_special', array(
		'L_RANK_NO_SPECIAL' => $lang['No_Ranks_Special']
		)
	);
}

if ($k == 0)
{
	$template->assign_block_vars('ranks_no_normal', array(
		'L_RANK_NO_NORMAL' => $lang['No_Ranks']
		)
	);
}

full_page_generation('ranks_body.tpl', $lang['Rank_Header'], '', '');

?>