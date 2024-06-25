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
* @Extra credits for this file
* Dimitri Seitz (dimitri.seitz@weingarten-net.de)
*
*/


if (defined('IN_ICYPHOENIX') && !empty($setmodules))
{
	$filename = basename(__FILE__);
	$module['1000_Configuration']['230_PHP_INFO'] = $filename;
	return;
}
define('IN_ICYPHOENIX', true);

// Load default header
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('pagestart.' . PHP_EXT);

// Get the PHP Info
ob_start();
phpinfo(INFO_GENERAL | INFO_CONFIGURATION | INFO_MODULES | INFO_VARIABLES);
$phpinfo = ob_get_contents();
ob_end_clean();

// Get used layout
$layout = (preg_match('#bgcolor#i', $phpinfo)) ? 'old' : 'new';

// Here we play around a little with the PHP Info HTML to try and stylise
// it along phpBB's lines ... hopefully without breaking anything. The idea
// for this was nabbed from the PHP annotated manual
preg_match_all('#<body[^>]*>(.*)</body>#siU', $phpinfo, $output);

switch ($layout)
{
	case 'old':
		$output = preg_replace('#<table#', '<table class="forumline"', $output[1][0]);
		$output = preg_replace('# bgcolor="\#(\w){6}"#', '', $output);
		$output = preg_replace('#(\w),(\w)#', '\1, \2', $output);
		$output = preg_replace('#border="0" cellspacing="0" cellpadding="0" width="600"#', 'width="100%" cellspacing="0" cellpadding="0" border="0"', $output);
		$output = preg_replace('#<tr valign="top"><td>(.*?<a .*?</a>)(.*?)</td></tr>#s', '<tr><td class="row1"><table><tr><td class="row1">\2</td><td class="row1">\1</td></tr></table></td></tr>', $output);
		$output = preg_replace('#<tr valign="baseline"><td[ ]{0,1}><b>(.*?)</b>#', '<tr><td class="row1">\1', $output);
		$output = preg_replace('#<td align="(center|left)">#', '<td class="row2">', $output);
		$output = preg_replace('#<td>#', '<td class="row2">', $output);
		$output = preg_replace('#valign="middle"#', '', $output);
		$output = preg_replace('#<tr >#', '<tr>', $output);
		$output = preg_replace('#<hr(.*?)>#', '', $output);
		$output = preg_replace('#<h1 align="center">#i', '<h1>', $output);
		$output = preg_replace('#<h2 align="center">#i', '<h2>', $output);
		preg_match_all('#<div class="center">(.*)</div>#siU', $output, $output);
		$output = $output[1][0];
		break;
	case 'new':
		$output = preg_replace('#<table#', '<table class="forumline"', $output[1][0]);
		$output = preg_replace('#(\w),(\w)#', '\1, \2', $output);
		$output = preg_replace('#border="0" cellpadding="3" width="600"#', 'width="100%" cellspacing="0" cellpadding="0" border="0"', $output);
		$output = preg_replace('#<tr class="v"><td>(.*?<a .*?</a>)(.*?)</td></tr>#s', '<tr><td class="row1"><table><tr><td>\2</td><td>\1</td></tr></table></td></tr>', $output);
		$output = preg_replace('#<td>#', '<td class="row1">', $output);
		$output = preg_replace('#class="e"#', 'class="row1 tdnw"', $output);
		$output = preg_replace('#class="v"#', 'class="row2"', $output);
		$output = preg_replace('# class="h"#', '', $output);
		$output = preg_replace('#<hr />#', '', $output);
		preg_match_all('#<div class="center">(.*)</div>#siU', $output, $output);
		$output = $output[1][0];
		break;
}

// The Final output
echo '<h1>PHP Info</h1>';
echo $lang['Php_Info_Explain'] . '<br /><br />';
echo $output;

include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);

?>