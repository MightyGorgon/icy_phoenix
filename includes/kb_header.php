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
* MX-System - (jonohlsson@hotmail.com) - (www.mx-system.com)
*
*/

if(!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

// Parse and show the overall header.

$template->set_filenames(array('kb_header' => 'kb_header.tpl'));
if ( isset($_GET['cat']) )
{
	$category_id = intval($_GET['cat'] );
	// Start auth check
	$kb_is_auth = array();
	$kb_is_auth = kb_auth(AUTH_ALL, $category_id, $userdata);
	// End of auth check

	if ( ($kb_config['allow_new'] == 1) && ($kb_is_auth['auth_post'] || $kb_is_auth['auth_mod']) )
	{
		$temp_url = append_sid(this_kb_mxurl('mode=add&cat=' . $category_id));
		$add_article = '<a href="' . $temp_url . '">' . $lang['Add_article'] . '</a>';
	}
	$template->assign_block_vars( 'switch_add_article', array() );
}
else
{
	$add_article = '';
}

$cat_add_article = $lang['Click_cat_to_add'];

$temp_url = append_sid( this_kb_mxurl_search ('', true) );
$search = '<a href="' . $temp_url . '">' . $lang['Search'] . '</a>';

if ( $kb_config['header_banner'] == 1 )
{
	$temp_url = append_sid( this_kb_mxurl() );
	$block_title = '<td align="center"><a href="' . $temp_url . '"><img src="' . $images['kb_title'] . '" width="285" height="45" alt="' . $title . '"></a></td>';
}
else
{
	$block_title = '<td align="center"><b>' . $lang['KB_title'] . '</b></td>';
}

//Bicet - NavBox on header START
$path_kb = ' ';
$path_kb_array = array();
$path_parts2 = pathinfo($_SERVER['PHP_SELF']);
$query = $_SERVER['QUERY_STRING'];

if (preg_match("/mode=cat/", $query))
{
	get_kb_nav( $category_id );
}
if (preg_match("/mode=article/", $query))
{
	get_kb_nav( $article_category_id );
	$path_kb .= '&nbsp;&raquo;&nbsp;<a href="" class="nav-current">' . $article_title . '</a>';
}

if (preg_match("/mode=stats&stats=mostpopular/", $query))
{
	$path_kb .= '&nbsp;&raquo;&nbsp;<a href="" class="nav-current">' . $lang['Top_most_popular'] . '</a>';
}
if (preg_match("/mode=stats&stats=toprated/", $query))
{
	$path_kb .= '&nbsp;&raquo;&nbsp;<a href="" class="nav-current">' . $lang['Top_toprated'] . '</a>';
}
if (preg_match("/mode=stats&stats=latest/", $query))
{
	$path_kb .= '&nbsp;&raquo;&nbsp;<a href="" class="nav-current">' . $lang['Top_latest'] . '</a>';

}
if (preg_match("/mode=edit/", $query) || preg_match("/mode=add/", $query))
{
	$path_kb .= '&nbsp;&raquo;&nbsp;<a href="#" class="nav-current">' . $lang['Add_article'] . '</a>';
}

//get_kb_nav( $article_category_id );
//$path_kb .=
if ( preg_match("/article/", $_SERVER['QUERY_STRING']) )
{
	$print_url = append_sid('./kb.' . PHP_EXT . '?mode=article&amp;k=' . $article_id . '&amp;page_num=' . ($page_num + 1) . '&amp;start=' . $start . '&amp;print=true', true);
	$l_print = $lang['Print_version'];
	$template->assign_block_vars('switch_print_article', array());
}
else
{
	$print_url = '';
	$l_print = '';
}
//Bicet - NavBox on header END

$template->assign_vars(array(
	'U_PORTAL' => IP_ROOT_PATH,
	'L_PORTAL' => '<<',
	'L_KB_TITLE' => $block_title,
	'L_ADD_ARTICLE' => $add_article,
	'L_CAT_ADD_ARTICLE' => $cat_add_article,
	'L_SEARCH' => $search,
	'U_TOPRATED' => append_sid(this_kb_mxurl('mode=stats&amp;stats=toprated')),
	'L_TOPRATED' => $lang['Top_toprated'],
	'U_MOST_POPULAR' => append_sid(this_kb_mxurl('mode=stats&amp;stats=mostpopular')),
	'L_MOST_POPULAR' => $lang['Top_most_popular'],
	'U_LATEST' => append_sid(this_kb_mxurl('mode=stats&amp;stats=latest')),
	'L_LATEST' => $lang['Top_latest'],
	'L_PRINT' => $l_print,
	'U_PRINT' => $print_url,
	'PATH' => $path_kb,
	'U_KB' => append_sid(this_kb_mxurl()),
	'L_KB' => $lang['KB_title'],
	)
);

if ( $kb_config['stats_list'] == 1 )
{
	get_quick_stats( $category_id );
}

$template->pparse('kb_header');

?>