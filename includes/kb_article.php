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

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

include_once(IP_ROOT_PATH . 'includes/functions_groups.' . PHP_EXT );
$article_id = !isset( $article_id ) ? intval( $_GET['k'] ) : $article_id;
$start = ( isset( $_GET['start'] ) ) ? intval( $_GET['start'] ) : 0;
$start = ($start < 0) ? 0 : $start;

$sql = "SELECT *
		FROM " . KB_ARTICLES_TABLE . "
		WHERE article_id = $article_id";

if ( !( $result = $db->sql_query( $sql ) ) )
{
	message_die( GENERAL_ERROR, "Could not obtain article data", '', __LINE__, __FILE__, $sql );
}

$kb_row = $db->sql_fetchrow( $result );

if ( count($kb_row) > 0 )
{
	$article_title = $kb_row['article_title'] ;
	// Define censored word matches
	if (!$userdata['user_allowswearywords'])
	{
		$orig_word = array();
		$replacement_word = array();
		obtain_word_list($orig_word, $replacement_word);
	}

	$approved = $kb_row['approved'];

	$article_category_id = $kb_row['article_category_id'];

	// Start auth check
	$kb_is_auth_all = array();
	$kb_is_auth_all = kb_auth(AUTH_ALL, AUTH_LIST_ALL, $userdata);
	$kb_is_auth = $kb_is_auth_all[$article_category_id];
	// End of auth check

	// User authorisation levels output
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

	$kb_quick_nav = get_kb_cat_list( 'auth_view', $article_category_id, $article_category_id, true, $kb_is_auth_all );

	$category = get_kb_cat( $article_category_id );
	$article_category_name = $category['category_name'];

	$temp_url = append_sid( this_kb_mxurl( "mode=cat&amp;cat=$article_category_id" ) );
	$category = '<a href="' . $temp_url . '">' . $article_category_name . '</a>';

	$date = create_date2( $board_config['default_dateformat'], $kb_row['article_date'], $board_config['board_timezone'] );

	// author information

	$author_id = $kb_row['article_author_id'];
	$author_kb_art = ( $author_id == -1 ) ? $lang['Guest'] : colorize_username ($kb_row['article_author_id']);

	$art_pages = explode( '[page]', stripslashes( $kb_row['article_body'] ) );
	$article = trim( $art_pages[$page_num] );
	$article = str_replace( '[toc]', '', $article );

	$kb_art_description = $kb_row['article_description'] ;

	$article = article_formatting( $article );

	$type_id = $kb_row['article_type'];
	$type = get_kb_type( $type_id );
	$topic_id = $kb_row['topic_id'];

	$new_views = $kb_row['views'] + 1;
	$views = '<b>' . $lang['Views'] . '</b> ' . $new_views;

	if ( $kb_row['article_rating'] == 0 || $kb_row['article_totalvotes'] == 0 )
	{
		$rating = 0;
		$rating_votes = 0;
		$rating_message = $lang['No_votes'] ;
		$rate_message = '<b>' . $lang['Votes_label'] . '</b> ' . $rating_message;
	}
	else
	{
		$rating = round( $kb_row['article_rating'] / $kb_row['article_totalvotes'], 2 );
		$rating_votes = $kb_row['article_totalvotes'];
		$rating_message = $rating . '/10, ' . $rating_votes . ' ' . $lang['Votes'] ;
		$rate_message = '<b>' . $lang['Votes_label'] . '</b> ' . $rating_message;
	}
}

if ( $page_num == 0 )
{
	$sql = "UPDATE " . KB_ARTICLES_TABLE . " SET views = '" . $new_views . "'
			WHERE article_id = " . $article_id;
}
if ( !( $result2 = $db->sql_query( $sql ) ) )
{
	message_die( GENERAL_ERROR, "Could not update article's views", '', __LINE__, __FILE__, $sql );
}

//
// Was a highlight request part of the URI?
//

$original_highlight = '';
$highlight_match = $highlight = '';
if (isset($_GET['highlight']))
{
	// Split words and phrases

	$original_highlight = '&highlight=' . trim(htmlspecialchars($_GET['highlight']));
	$words = explode(' ', trim(htmlspecialchars($_GET['highlight'])));

	for($i = 0; $i < count($words); $i++)
	{
		if (trim($words[$i]) != '')
		{
			$highlight_match .= (($highlight_match != '') ? '|' : '') . str_replace('*', '\w*', phpbb_preg_quote($words[$i], '#'));
		}
	}
	unset($words);

	$highlight = urlencode($_GET['highlight']);
	$highlight_match = phpbb_rtrim($highlight_match, "\\");
}

if ( !$html_on )
{
	$article = preg_replace( '#(<)([\/]?.*?)(>)#is', "&lt;\\2&gt;", $article );
}
$bbcode->allow_html = true;

// Parse message

$bbcode_uid = $kb_row['bbcode_uid'];

if ( $smilies_on && !$lofi )
{
	$bbcode->allow_smilies = $board_config['allow_smilies'];
}
else
{
	$bbcode->allow_smilies = false;
}

if ( $bbcode_on )
{
	if ( $bbcode_uid != '' )
	{
		$bbcode->allow_bbcode = true;
		$article = $bbcode->parse($article, $bbcode_uid);
	}
}
else
{
	$bbcode->allow_bbcode = false;
	$article = bbcode_killer_00($article);
	$article = $bbcode->parse($article, $bbcode_uid);
}


$orig_autolink = array();
$replacement_autolink = array();
obtain_autolink_list($orig_autolink, $replacement_autolink, 99999999);
if( function_exists( 'acronym_pass' ) )
{
	$article = acronym_pass( $article );
}
if( count($orig_autolink) )
{
	$article = autolink_transform($article, $orig_autolink, $replacement_autolink);
}

//$article = kb_word_wrap_pass ($article);

//
// Highlight active words (primarily for search)
//

if ($highlight_match)
{
	// This was shamelessly 'borrowed' from volker at multiartstudio dot de
	// via php.net's annotated manual
	$article = str_replace('\"', '"', substr(@preg_replace('#(\>(((?>([^><]+|(?R)))*)\<))#se', "@preg_replace('#\b(" . $highlight_match . ")\b#i', '<span style=\"color:#" . $theme['fontcolor3'] . "\"><b>\\\\1</b></span>', '\\0')", '>' . $article . '<'), 1, -1));
}

// Replace naughty words

if ( count( $orig_word ) )
{
	$article_title = preg_replace( $orig_word, $replacement_word, $article_title );

	$article = str_replace( '\"', '"', substr( preg_replace( '#(\>(((?>([^><]+|(?R)))*)\<))#se', "preg_replace(\$orig_word, \$replacement_word, '\\0')", '>' . $article . '<' ), 1, -1 ) );
}

// Replace newlines (we use this rather than nl2br because
// till recently it wasn't XHTML compliant)

//$article = str_replace( "\n", "\n<br />\n", $article );

// Highlight active words (primarily for search)

if ( $highlight_match )
{
	// This was shamelessly 'borrowed' from volker at multiartstudio dot de
	// via php.net's annotated manual
	$article = str_replace( '\"', '"', substr( preg_replace( '#(\>(((?>([^><]+|(?R)))*)\<))#se', "preg_replace('#\b(" . $highlight_match . ")\b#i', '<span style=\"color:#" . $theme['fontcolor3'] . "\"><b>\\\\1</b></span>', '\\0')", '>' . $article . '<' ), 1, -1 ) );
}

$page_title = $article_title;
$meta_description = '';
$meta_keywords = '';

if ( $print_version )
{
	$gen_simple_header = true;
	include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);
}

if ( !$is_block && !$print_version)
{
	include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);
}

// fixup (truncates) urls, images and words for a narrow column layout
if ( $kb_config['formatting_fixup'] )
{
	$kb_art_description = kb_decode_truncate_fixup( $kb_art_description );
	if ( count($orig_word) && !$userdata['user_allowswearywords'])
	{
		$kb_art_description = preg_replace($orig_word, $replacement_word, $kb_art_description);
	}
	$article = kb_decode_truncate_fixup( $article );
}

// load header

if ( !$print_version && !$reader_mode )
{
	include(IP_ROOT_PATH . 'includes/kb_header.' . PHP_EXT);
}

// edit

if ( ( $userdata['user_id'] == $author_id && $kb_is_auth['auth_edit'] ) || $kb_is_auth['auth_mod'] )
{
	$temp_url = append_sid( this_kb_mxurl( "mode=edit&amp;k=" . $article_id ) );
	$edit_img = '<a href="' . $temp_url . '"><img src="' . IP_ROOT_PATH . $images['icon_edit'] . '" alt="' . $lang['Edit_delete_post'] . '" title="' . $lang['Edit_delete_post'] . '" /></a>';
	$edit = '<a href="' . $temp_url . '">' . $lang['Edit_delete_post'] . '</a>';
}
else
{
	$edit_img = '';
	$edit = '';
}

// Build page

if ( !$print_version )
{
	if ( $reader_mode )
	{
		$template->set_filenames( array( 'body' => 'kb_article_reader.tpl' ) );
	}
	else
	{
		$template->set_filenames( array( 'body' => 'kb_article_body.tpl' ) );
	}
}
else
{
	$template->set_filenames( array( 'body' => 'kb_article_body_print.tpl' ) );
}

if ( !$kb_is_auth['auth_view'] || !$article_title || ( !$approved && !$kb_is_auth['auth_mod'] ) || ( !ns_auth_cat( $article_category_id ) && !$print_version ) )
{
	$message = $lang['Article_not_exsist'] . '<br /><br />' . sprintf( $lang['Click_return_kb'], '<a href="' . append_sid( this_kb_mxurl() ) . '">', '</a>' ) . '<br /><br />' . sprintf( $lang['Click_return_index'], '<a href="' . append_sid( IP_ROOT_PATH . FORUM_MG ) . '">', '</a>' );
	mx_message_die( GENERAL_MESSAGE, $message );
}
else
{
	$kb_comment = array();

	// Populate the kb_comment variable
	$kb_comment = kb_get_data($kb_row, $userdata );

	// Compose post header
	$subject = $lang['KB_comment_prefix'] . $kb_comment['article_title'];
	$message_temp = kb_compose_comment( $kb_comment );

	$kb_message = $message_temp['message'];
	$kb_update_message = $message_temp['update_message'];

	$topic_id_tmp = $kb_row['topic_id'];
	//
	// Check if this topic exists. It could have been deleted by accident ;) If so recreate it!
	//
	if ( !empty($topic_id_tmp) )
	{
		$sql = "SELECT *
			FROM " . TOPICS_TABLE . "
			WHERE topic_id = $topic_id_tmp";
		if ( !($result = $db->sql_query($sql)) )
		{
			mx_message_die(GENERAL_ERROR, 'Could not obtain forums information', '', __LINE__, __FILE__, $sql);
		}
	}

	//
	// If the query doesn't return any rows this isn't a valid topic. Set to null.
	//
	if ( !($topic_row = $db->sql_fetchrow($result)) )
	{
		$topic_id = 0;
	}

	// If no phpbb topic id is created, create on ;)
	if ( !$topic_id && $approved && $kb_config['use_comments'] && $kb_comment['category_forum_id'] > 0)
	{
		// Post
		$topic_data = kb_insert_post( $kb_message, $subject, $kb_comment['category_forum_id'], $kb_comment['article_editor_id'], $kb_comment['article_editor'], $kb_comment['article_editor_sig'], $kb_comment['topic_id'], $kb_update_message );

		$sql = "UPDATE " . KB_ARTICLES_TABLE . " SET topic_id = " . $topic_data['topic_id'] . "
						WHERE article_id = " . $kb_comment['article_id'];

		if ( !( $result = $db->sql_query( $sql ) ) )
		{
			mx_message_die( GENERAL_ERROR, "Could not update article data", '', __LINE__, __FILE__, $sql );
		}

		$topic_id = $topic_data['topic_id'];
	}

	if ( $kb_is_auth['auth_comment'] && $kb_config['use_comments'] )
	{
		$sql = "SELECT topic_id, topic_replies FROM " . TOPICS_TABLE . " WHERE topic_id = " . $topic_id;

		if ( !( $result4 = $db->sql_query( $sql ) ) )
		{
			mx_message_die( GENERAL_ERROR, 'Error getting comments', '', __LINE__, __FILE__, $sql );
		}
		$topic = $db->sql_fetchrow( $result4 );
		$num_of_replies = intval( $topic['topic_replies'] );

		$temp_url = append_sid( IP_ROOT_PATH . VIEWTOPIC_MG . "?" . POST_TOPIC_URL . "=" . $topic['topic_id'] );
		$comments = $lang['Comments'];
		//$comments_img = '<a href="' . $temp_url . '">[' . $num_of_replies . ' - ' . $lang['Post_comments'] . ']</a>';
		$comments_num = $num_of_replies;
		$comments_url = '<a href="' . $temp_url . '">' . $lang['Post_comments'] . '</a>';

		$template->assign_block_vars( 'switch_comments', array() );
	}
	else
	{
		$comments = '';
	}

	if ( $kb_config['comments_show'] && $topic_id )
	{

		// page number

		if ( isset( $page_num ) )
		{
			$page_numm = '&page_num=' . ( $page_num + 1 ) ;
		}
		else
		{
			$page_numm = '';
		}

		$show_num_comments = $kb_config['comments_pagination'];
		$pagination = generate_pagination( this_kb_mxurl( "mode=article&k=$article_id" . $page_numm ), $num_of_replies, $show_num_comments, $start ) . '&nbsp;';
		get_kb_comments( $topic_id, $start, $show_num_comments );
	}

	// rate
	if ( $kb_is_auth['auth_rate'] && $kb_config['use_ratings'])
	{
		$temp_url = append_sid( this_kb_mxurl( "mode=rate&amp;k=" . $article_id ) );
		$rate_img = '<a href="' . $temp_url . '">' . $lang['ADD_RATING'] . '</a>';
		$rate = '<a href="' . $temp_url . '">' . $lang['ADD_RATING'] . '</a>';

		$template->assign_block_vars( 'switch_ratings', array() );
	}
	else
	{
		$rate_img = '';
		$rate = '';
	}

	$path_kb = ' ';
	$path_kb_array = array();

	get_kb_nav( $article_category_id );

	// $print_url = append_sid( this_kb_mxurl( "mode=article&amp;k=" . $article_id . "&print=true", true ) );
	$print_url = append_sid(   "./kb.php?mode=article&amp;k=" . $article_id ."&page_num=".($page_num+1)."&start=".$start ."&print=true", true  );

	$template->assign_vars( array( 'PAGINATION' => $pagination,
		'PAGE_NUMBER' => sprintf( $lang['Page_of'], ( floor( $start / $kb_config['comments_pagination'] ) + 1 ), ceil( $num_of_replies / $kb_config['comments_pagination'] ) ),
		'L_GOTO_PAGE' => $lang['Goto_page'],

		'L_ARTICLE_DESCRIPTION' => $lang['Article_description'],
		'L_ARTICLE_DATE' => $lang['Date'],
		'L_ARTICLE_TYPE' => $lang['Article_type'],
		'L_ARTICLE_CATEGORY' => $lang['Category'],
		'L_ARTICLE_AUTHOR' => $lang['Author'],
		'L_GOTO_PAGE' => $lang['Goto_page'],
		'L_TOC' => $lang['TOC'],
		'L_COMMENTS' => $lang['Comments_show_title'],
		'L_PRINT' => $lang['Print_version'],

		'U_PRINT' => $print_url,

		'ARTICLE_TITLE' => $article_title,
		'ARTICLE_AUTHOR' => $author_kb_art,
		'ARTICLE_CATEGORY' => $category,
		'ARTICLE_TEXT' => $article,
		'ARTICLE_DESCRIPTION' => $kb_art_description,
		'ARTICLE_DATE' => $date,
		'ARTICLE_TYPE' => $type,
		'EDIT_IMG' => $edit_img,
		'EDIT' => $edit,
		'VIEWS' => $views,

		'RATINGS' => $rate_message,
		'RATE_IMG' => $rate_img,
		'RATE' => $rate,
		'COMMENTS' => $comments,
		//'COMMENTS_IMG' => $comments_img,
		'COMMENTS_NUM' => $comments_num,
		'COMMENTS_URL' => $comments_url,

		'PATH' => $path_kb
		)
	);

	// article pages table of contents
	$kb_custom_field->display_data( $article_id );

	if ( count( $art_pages ) > 1 )
	{
		$template->assign_block_vars( 'switch_toc', array() );

		$i = 0;

		while ( $i < count( $art_pages ) )
		{
			$page_number = $i + 1;

			$art_split = explode( '[toc]', $art_pages[$i] );
			$article_toc = $art_split[0];

			// Fix up the toc title

			if ( !$html_on )
			{
				$article_toc = preg_replace( '#(<)([\/]?.*?)(>)#is', "&lt;\\2&gt;", $article_toc );
			}

			// Parse message

			$article_toc = preg_replace( "'\[[\/\!]*?[^\[\]]*?\]'si", "", $article_toc ); // Fixed
			$article_toc = make_clickable( $article_toc );

			// Parse smilies

			if ( $smilies_on )
			{
				$article_toc = mx_smilies_pass( $article_toc );
			}

			// Replace naughty words

			if ( count( $orig_word ) )
			{
				$article_toc = str_replace( '\"', '"', substr( preg_replace( '#(\>(((?>([^><]+|(?R)))*)\<))#se', "preg_replace(\$orig_word, \$replacement_word, '\\0')", '>' . $article_toc . '<' ), 1, -1 ) );
			}

			// Replace newlines (we use this rather than nl2br because
			// till recently it wasn't XHTML compliant)
			// $article_toc = str_replace("\n", "\n<br />\n", $article_toc);

			$page_toc = $art_pages[$i];

			// Sync with comments pagination

			if ( $start > -1 )
			{
				$start_pag = "&start=" . $start;
			}
			else
			{
				$start_pag = "";
			}

			if ( $page_num != $i )
			{
				if ( !$print_version )
				{
					$temp_url = append_sid( this_kb_mxurl( "mode=article&k=$article_id&page_num=$page_number" . $start_pag . $original_highlight ) );
				}
				else
				{
					$temp_url = append_sid( this_kb_mxurl( "mode=article&k=$article_id&page_num=$page_number&print=true" . $start_pag . $original_highlight, true ) );
				}
				$page_link = '<a href="' . $temp_url . '" class="pagination">' . $page_number . ' - ' . $article_toc . '</a>';
			}
			else
			{
				$page_link = $page_number . ' - ' . $article_toc ;
			}

			if ( $i < count( $art_pages ) - 1 )
			{
				$page_link .= '<br />';
			}

			$template->assign_block_vars( 'switch_toc.pages', array( 'TOC_ITEM' => $page_link ) );

			$i++;
		}
	}

	// article pages TOC navigation

	if ( count( $art_pages ) > 1 )
	{
		$template->assign_block_vars( 'switch_pages', array() );

		if ( $start > -1 )
		{
			$start_pag = "&start=" . $start;
		}
		else
		{
			$start_pag = "";
		}

		$i = 0;

		while ( $i < count( $art_pages ) )
		{
			$page_number = $i + 1;

			if ( $page_num != $i )
			{
				if ( !$print_version )
				{
					$temp_url = append_sid( this_kb_mxurl( "mode=article&k=$article_id&page_num=$page_number" . $start_pag . $original_highlight) );
				}
				else
				{
					$temp_url = append_sid( this_kb_mxurl( "mode=article&k=$article_id&page_num=$page_number&print=true" . $start_pag . $original_highlight, true ) );
				}
				$page_link = '<a href="' . $temp_url . '" class="pagination">' . $page_number . '</a>';
			}
			else
			{
				$page_link = $page_number;
			}

			if ( $i < count( $art_pages ) - 1 )
			{
				$page_link .= ', ';
			}

			$template->assign_block_vars( 'switch_pages.pages', array( 'PAGE_LINK' => $page_link ) );

			$i++;
		}
	}
}

?>