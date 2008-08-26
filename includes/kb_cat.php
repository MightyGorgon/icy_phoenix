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

if (!defined('IN_PHPBB'))
{
	die('Hacking attempt');
}

$start = ( isset( $_GET['start'] ) ) ? intval( $_GET['start'] ) : 0;
$start = ($start < 0) ? 0 : $start;

$category_id = ( isset( $_GET['cat'] ) ) ? intval ( $_GET['cat'])  : intval ( $_POST['cat'] );

$category = get_kb_cat( $category_id );
$category_name = $category['category_name'];

$page_title = $category_name;
$meta_description = '';
$meta_keywords = '';

// Start auth check
//
	$kb_is_auth_all = array();
	$kb_is_auth_all = kb_auth(AUTH_ALL, AUTH_LIST_ALL, $userdata);
	$kb_is_auth = $kb_is_auth_all[$category_id];

// End of auth check

//
//
// User authorisation levels output
//
$kb_auth_can = '<br />' . ( ( $kb_is_auth['auth_post'] ) ? $lang['KB_Rules_post_can'] : $lang['KB_Rules_post_cannot'] ) . '<br />';
$kb_auth_can .= ( ( $kb_is_auth['auth_edit'] ) ? $lang['KB_Rules_edit_can'] : $lang['KB_Rules_edit_cannot'] ) . '<br />';
$kb_auth_can .= ( ( $kb_is_auth['auth_delete'] ) ? $lang['KB_Rules_delete_can'] : $lang['KB_Rules_delete_cannot'] ) . '<br />';
$kb_auth_can .= ( ( $kb_is_auth['auth_comment'] ) ? $lang['KB_Rules_comment_can'] : $lang['KB_Rules_comment_cannot'] ) . '<br />';
$kb_auth_can .= ( ( $kb_is_auth['auth_rate'] ) ? $lang['KB_Rules_rate_can'] : $lang['KB_Rules_rate_cannot'] ) . '<br />';
$kb_auth_can .= ( ( $kb_is_auth['auth_approval'] ) ? $lang['KB_Rules_approval_can'] : $lang['KB_Rules_approval_cannot'] ) . '<br />';
$kb_auth_can .= ( ( $kb_is_auth['auth_approval_edit'] ) ? $lang['KB_Rules_approval_edit_can'] : $lang['KB_Rules_approval_edit_cannot'] ) . '<br />';

if ( $kb_is_auth['auth_mod'] )
{
	$kb_auth_can .= $lang['KB_Rules_moderate_can'];
}

$kb_quick_nav = get_kb_cat_list( 'auth_view', $category_id, $category_id, true, $kb_is_auth_all );

if ( !$kb_is_auth['auth_view'] )
{
	//
	// The user is not authed to read this cat ...
	//
	$message = $lang['Not_authorized'];

	mx_message_die(GENERAL_MESSAGE, $message);
}

if ( $kb_config['use_comments'] && $category['comments_forum_id'] < 1 )
{
	//
	// Commenting is enabled but no category forum id specified
	//
	$message = $lang['No_cat_comments_forum_id'];

	mx_message_die(GENERAL_MESSAGE, $message);
}

if(!$is_block)
{
	include($phpbb_root_path . 'includes/page_header.' . $phpEx);
}

// load header
include($phpbb_root_path . 'includes/kb_header.' . $phpEx );
$kb_news_sort_par = $kb_config['news_sort_par'];

switch ( $kb_config['news_sort'] )
{
	case 'Id':
		$kb_news_sort_method = 't.article_id';
		$kb_news_sort_method_extra = 't.article_type' . " DESC, " ;
		break;
	case 'Creation':
		$kb_news_sort_method = 't.article_date';
		$kb_news_sort_method_extra = 't.article_type' . " DESC, " ;
		break;
	case 'Latest':
		//$kb_news_sort_method = 't.topic_last_post_id'; // This option is used if you want articles sorted for latest comments
		$kb_news_sort_method = 't.article_date';
		$kb_news_sort_method_extra = 't.article_type' . " DESC, " ;
		break;
	case 'Userrank':
		$kb_news_sort_method = 'u.user_rank';
		$kb_news_sort_method_extra = 't.article_type' . " DESC, " ;
		break;
	case 'Alphabetic':
		$kb_news_sort_method = 't.article_title';
		$kb_news_sort_method_extra = 't.article_type' . " DESC, " ;
		break;
}

$template->set_filenames(array('body' => 'kb_cat_body.tpl'));

if ( !$category_name )
{
	$message = $lang['Category_not_exsist'] . '<br /><br />' . sprintf( $lang['Click_return_kb'], '<a href="' . append_sid(this_kb_mxurl() ) . '">', '</a>' ) . '<br /><br />' . sprintf( $lang['Click_return_index'], '<a href="' . append_sid($phpbb_root_path . FORUM_MG ) . '">', '</a>');

	mx_message_die( GENERAL_MESSAGE, $message );
}
else
{
	// get sub-cats
	get_kb_cat_subs( $category_id, $kb_is_auth_all );

	$path_kb = ' ';
	$path_kb_array = array();
	get_kb_nav($category_id);

	// Pagination
	$sql_pag = "SELECT count(article_id) AS total
		FROM " . KB_ARTICLES_TABLE . "
		WHERE ";
	// newssuite addon
	if ( $kb_config['news_operate_mode'] )
	{
		$kb_types_list = ns_auth_item( $category_id );
		$sql_pag .= " article_type IN " . $kb_types_list . ' AND';
	}
	$sql_pag .= " article_category_id = '$category_id'";

	if ( !( $result = $db->sql_query( $sql_pag ) ) )
	{
		mx_message_die( GENERAL_ERROR, 'Error getting total articles', '', __LINE__, __FILE__, $sql );
	}

	if ( $total = $db->sql_fetchrow( $result ) )
	{
		$total_articles = $total['total'];
		$pagination = generate_pagination( this_kb_mxurl( "mode=cat&cat=$category_id" ), $total_articles, $kb_config['art_pagination'], $start ) . '&nbsp;';
	}

	if ( $total_articles > 0 )
	{
		$template->assign_block_vars( 'pagination', array() );
	}

	$template->assign_vars(array(
			'PAGINATION' => $pagination,
			'PAGE_NUMBER' => sprintf( $lang['Page_of'], ( floor( $start / $kb_config['art_pagination'] ) + 1 ), ceil( $total_articles / $kb_config['art_pagination'] ) ),
			'L_GOTO_PAGE' => $lang['Goto_page'],
			'L_CATEGORY_NAME' => $category_name,
			'L_ARTICLE' => $lang['Article'],
			'L_ARTICLE_TYPE' => $lang['Article_type'],
			'L_ARTICLE_CATEGORY' => $lang['Category'],
			'L_ARTICLE_DATE' => $lang['Date'],
			'L_ARTICLE_AUTHOR' => $lang['Author'],
			'L_VIEWS' => $lang['Views'],
			'L_VOTES' => $lang['Votes'],

			'L_CATEGORY' => $lang['Category_sub'],
			'L_ARTICLES' => $lang['Articles'],

			'PATH' => $path_kb,

			'U_CAT' => append_sid(this_kb_mxurl('mode=cat&cat=' . $category_id))
			)
		);

	get_kb_articles( $category_id, '1', 'articlerow', $start, $kb_config['art_pagination'], $kb_is_auth );
}

?>