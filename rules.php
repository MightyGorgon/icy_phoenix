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

$cms_page_id = '10';
$cms_page_name = 'rules';
$auth_level_req = $board_config['auth_view_rules'];
if ($auth_level_req > AUTH_ALL)
{
	if (($auth_level_req == AUTH_REG) && (!$userdata['session_logged_in']))
	{
		message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
	}
	if ($userdata['user_level'] != ADMIN)
	{
		if ($auth_level_req == AUTH_ADMIN)
		{
			message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
		}
		if (($auth_level_req == AUTH_MOD) && ($userdata['user_level'] != MOD))
		{
			message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
		}
	}
}
$cms_global_blocks = ($board_config['wide_blocks_rules'] == 1) ? true : false;

// Load the appropriate Rules file
$lang_file = 'lang_rules';
$l_title = $lang['BoardRules'];

// Include the rules settings
include(IP_ROOT_PATH . 'language/lang_' . $board_config['default_lang'] . '/' . $lang_file . '.' . PHP_EXT);

//
// Pull the array data from the lang pack
//
$j = 0;
$counter = 0;
$counter_2 = 0;
$rules_block = array();
$rules_block_titles = array();

for($i = 0; $i < count($faq); $i++)
{
	if($faq[$i][0] != '--')
	{
		$rules_block[$j][$counter]['id'] = $counter_2;
		$rules_block[$j][$counter]['question'] = $faq[$i][0];
		$rules_block[$j][$counter]['answer'] = $faq[$i][1];

		$counter++;
		$counter_2++;
	}
	else
	{
		$j = ($counter != 0) ? $j + 1 : 0;

		$rules_block_titles[$j] = $faq[$i][1];

		$counter = 0;
	}
}

// Lets build a page ...
$page_title = $lang['BoardRules'];
$meta_description = '';
$meta_keywords = '';
include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);

$template->set_filenames(array('body' => 'rules_body.tpl'));
make_jumpbox(VIEWFORUM_MG, $forum_id);

$template->assign_vars(array(
	'L_RULES_TITLE' => $l_title,
	'L_BACK_TO_TOP' => $lang['Back_to_top'])
);

for($i = 0; $i < count($rules_block); $i++)
{
	if(count($rules_block[$i]))
	{
		$template->assign_block_vars('rules_block', array(
			'BLOCK_TITLE' => $rules_block_titles[$i])
		);
		$template->assign_block_vars('rules_block_link', array(
			'BLOCK_TITLE' => $rules_block_titles[$i])
		);

		for($j = 0; $j < count($rules_block[$i]); $j++)
		{
			$row_color = (!($j % 2)) ? $theme['td_color1'] : $theme['td_color2'];
			$row_class = (!($j % 2)) ? $theme['td_class1'] : $theme['td_class2'];

			$template->assign_block_vars('rules_block.rules_row', array(
				'ROW_COLOR' => '#' . $row_color,
				'ROW_CLASS' => $row_class,
				'RULES_QUESTION' => $rules_block[$i][$j]['question'],
				'RULES_ANSWER' => $rules_block[$i][$j]['answer'],

				'U_RULES_ID' => $rules_block[$i][$j]['id'])
			);

			$template->assign_block_vars('rules_block_link.rules_row_link', array(
				'ROW_COLOR' => '#' . $row_color,
				'ROW_CLASS' => $row_class,
				'RULES_LINK' => $rules_block[$i][$j]['question'],

				'U_RULES_LINK' => '#f' . $rules_block[$i][$j]['id'])
			);
		}
	}
}

$template->pparse('body');

include(IP_ROOT_PATH . 'includes/page_tail.' . PHP_EXT);

?>