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

if(!empty($setmodules))
{
	$file = basename(__FILE__);
	$module['1150_CMS']['100_CMS_CUSTOM_PAGES'] = IP_ROOT_PATH . CMS_PAGE_CMS . '?mode=layouts';
	$module['1150_CMS']['110_CMS_STANDARD_PAGES'] = IP_ROOT_PATH . CMS_PAGE_CMS . '?mode=layouts_special';
	$module['1150_CMS']['120_CMS_BLOCK_SETTINGS'] = IP_ROOT_PATH . CMS_PAGE_CMS . '?mode=block_settings';
	$module['1150_CMS']['130_CMS_GLOBAL_BLOCKS'] = IP_ROOT_PATH . CMS_PAGE_CMS . '?mode=blocks&amp;l_id=0&amp;action=editglobal';
	$module['1150_CMS']['140_CMS_AUTH'] = IP_ROOT_PATH . CMS_PAGE_CMS . '?mode=auth';
	$module['1150_CMS']['150_CMS_CONFIG'] = IP_ROOT_PATH . CMS_PAGE_CMS . '?mode=config';
	$module['1150_CMS']['160_CMS_MENU_PAGE'] = IP_ROOT_PATH . 'cms_menu.' . PHP_EXT;
	$module['1150_CMS']['170_CMS_ADS'] = IP_ROOT_PATH . 'cms_ads.' . PHP_EXT;

}

?>