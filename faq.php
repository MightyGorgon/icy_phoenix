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
* @Icy Phoenix is based on phpBB
* @copyright (c) 2008 phpBB Group
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

$cms_page['page_id'] = 'faq';
$cms_page['page_nav'] = (!empty($cms_config_layouts[$cms_page['page_id']]['page_nav']) ? true : false);
$cms_page['global_blocks'] = (!empty($cms_config_layouts[$cms_page['page_id']]['global_blocks']) ? true : false);
$cms_auth_level = (isset($cms_config_layouts[$cms_page['page_id']]['view']) ? $cms_config_layouts[$cms_page['page_id']]['view'] : AUTH_ALL);
check_page_auth($cms_page['page_id'], $cms_auth_level);

// Set vars to prevent naughtiness
$faq = array();

// Load the appropriate faq file
if(isset($_GET['mode']))
{
	switch($_GET['mode'])
	{
		case 'bbcode':
			$lang_file = 'lang_bbcode';
			$l_title = $lang['BBCode_guide'];
			break;
		default:
			$lang_file = 'lang_faq';
			$l_title = $lang['FAQ'];
			break;
	}
}
else
{
	$lang_file = 'lang_faq';
	$l_title = $lang['FAQ'];
}
include(IP_ROOT_PATH . 'language/lang_' . $config['default_lang'] . '/' . $lang_file . '.' . PHP_EXT);

// Pull the array data from the lang pack
$j = 0;
$counter = 0;
$counter_2 = 0;
$faq_block = array();
$faq_block_titles = array();

for($i = 0; $i < sizeof($faq); $i++)
{
	if($faq[$i][0] != '--')
	{
		$faq_block[$j][$counter]['id'] = $counter_2;
		$faq_block[$j][$counter]['question'] = $faq[$i][0];
		$faq_block[$j][$counter]['answer'] = $faq[$i][1];

		$counter++;
		$counter_2++;
	}
	else
	{
		$j = ($counter != 0) ? $j + 1 : 0;

		$faq_block_titles[$j] = $faq[$i][1];

		$counter = 0;
	}
}

$template->assign_vars(array(
	'L_FAQ_TITLE' => $l_title,
	'L_BACK_TO_TOP' => $lang['Back_to_top']
	)
);

for($i = 0; $i < sizeof($faq_block); $i++)
{
	if(sizeof($faq_block[$i]))
	{
		$template->assign_block_vars('faq_block', array(
			'BLOCK_TITLE' => $faq_block_titles[$i])
		);
		$template->assign_block_vars('faq_block_link', array(
			'BLOCK_TITLE' => $faq_block_titles[$i])
		);

		for($j = 0; $j < sizeof($faq_block[$i]); $j++)
		{
			$row_class = (!($j % 2)) ? $theme['td_class1'] : $theme['td_class2'];

			$template->assign_block_vars('faq_block.faq_row', array(
				'ROW_CLASS' => $row_class,
				'FAQ_QUESTION' => $faq_block[$i][$j]['question'],
				'FAQ_ANSWER' => $faq_block[$i][$j]['answer'],
				'U_FAQ_ID' => $faq_block[$i][$j]['id']
				)
			);

			$template->assign_block_vars('faq_block_link.faq_row_link', array(
				'ROW_CLASS' => $row_class,
				'FAQ_LINK' => $faq_block[$i][$j]['question'],
				'U_FAQ_LINK' => '#f' . $faq_block[$i][$j]['id']
				)
			);
		}
	}
}

make_jumpbox(CMS_PAGE_VIEWFORUM);

full_page_generation('faq_body.tpl', $l_title, '', '');

?>