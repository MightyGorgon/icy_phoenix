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
$user->session_begin();
//$auth->acl($user->data);
$user->setup();
// End session management

$cms_page['page_id'] = 'rules';
$cms_page['page_nav'] = (!empty($cms_config_layouts[$cms_page['page_id']]['page_nav']) ? true : false);
$cms_page['global_blocks'] = (!empty($cms_config_layouts[$cms_page['page_id']]['global_blocks']) ? true : false);
$cms_auth_level = (isset($cms_config_layouts[$cms_page['page_id']]['view']) ? $cms_config_layouts[$cms_page['page_id']]['view'] : AUTH_ALL);
check_page_auth($cms_page['page_id'], $cms_auth_level);

// Load the appropriate Rules file
$lang_file = 'lang_rules';
$l_title = $lang['BoardRules'];

// Include the rules settings
setup_extra_lang(array($lang_file));

// Pull the array data from the lang pack
$j = 0;
$counter = 0;
$counter_2 = 0;
$rules_block = array();
$rules_block_titles = array();

for($i = 0; $i < sizeof($faq); $i++)
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

make_jumpbox(CMS_PAGE_VIEWFORUM, $forum_id);

$template->assign_vars(array(
	'L_RULES_TITLE' => $l_title,
	'L_BACK_TO_TOP' => $lang['Back_to_top']
	)
);

for($i = 0; $i < sizeof($rules_block); $i++)
{
	if(sizeof($rules_block[$i]))
	{
		$template->assign_block_vars('rules_block', array(
			'BLOCK_TITLE' => $rules_block_titles[$i])
		);
		$template->assign_block_vars('rules_block_link', array(
			'BLOCK_TITLE' => $rules_block_titles[$i])
		);

		for($j = 0; $j < sizeof($rules_block[$i]); $j++)
		{
			$row_class = (!($j % 2)) ? $theme['td_class1'] : $theme['td_class2'];

			$template->assign_block_vars('rules_block.rules_row', array(
				'ROW_CLASS' => $row_class,
				'RULES_QUESTION' => $rules_block[$i][$j]['question'],
				'RULES_ANSWER' => $rules_block[$i][$j]['answer'],

				'U_RULES_ID' => $rules_block[$i][$j]['id'])
			);

			$template->assign_block_vars('rules_block_link.rules_row_link', array(
				'ROW_CLASS' => $row_class,
				'RULES_LINK' => $rules_block[$i][$j]['question'],

				'U_RULES_LINK' => '#f' . $rules_block[$i][$j]['id'])
			);
		}
	}
}

full_page_generation('rules_body.tpl', $lang['BoardRules'], '', '');

?>