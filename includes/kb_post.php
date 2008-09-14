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

$category_id = ( isset( $_GET['cat'] ) ) ? intval ( $_GET['cat'] ) : intval ( $_POST['cat'] );
$article_id = ( isset( $_GET['k'] ) ) ? intval ( $_GET['k'] ) : intval ( $_POST['k'] );

if ( empty( $category_id ) )
{
	// Get old data first
	$sql = "SELECT article_category_id
		FROM " . KB_ARTICLES_TABLE . "
		WHERE article_id = $article_id";

	if ( !( $result = $db->sql_query( $sql ) ) )
	{
		mx_message_die( GENERAL_ERROR, "Could not obtain article data", '', __LINE__, __FILE__, $sql );
	}
	$kb_row = $db->sql_fetchrow( $result );
	$category_id = $kb_row['article_category_id'];
}

$kb_post_mode = empty( $article_id ) ? 'add' : 'edit';

// Parameters
$submit = ( isset( $_POST['article_submit'] ) ) ? true : false;
$cancel = ( isset( $_POST['cancel'] ) ) ? true : false;
$preview = ( isset( $_POST['preview'] ) ) ? true : false;

$kb_wysiwyg = false;
$bbcode_on = $kb_config['allow_bbcode'] ? 1 : 0;
$html_on = $kb_config['allow_html'] ? 1 : 0;
$acro_auto_on = 0;
$smilies_on = $kb_config['allow_smilies'] ? 1 : 0;

$board_config['allow_html_tags'] = $kb_config['allowed_html_tags'];

$template->assign_block_vars('formatting', array());

// Start auth check
$kb_is_auth = array();
$kb_is_auth = kb_auth(AUTH_ALL, $category_id, $userdata);
// End of auth check

$page_title = $kb_post_mode == 'add' ? $lang['Add_article'] : $lang['Edit_article'];
$meta_description = '';
$meta_keywords = '';

// post article ----------------------------------------------------------------------------ADD/EDIT
if ( $submit )
{
	if ( empty( $_POST['article_name'] ) || empty( $_POST['article_desc'] ) || empty( $_POST['message'] ) )
	{
		$message = $lang['Empty_fields'] . '<br /><br />' . sprintf( $lang['Empty_fields_return'], '<a href="' . append_sid( this_kb_mxurl( 'mode=add' ) ) . '">', '</a>' );
		mx_message_die( GENERAL_MESSAGE, $message );
	}

	$article_title = ( !empty( $_POST['article_name'] ) ) ? htmlspecialchars( trim ( $_POST['article_name'] ) ) : '';
	$article_description = ( !empty( $_POST['article_desc'] ) ) ? htmlspecialchars( trim ( $_POST['article_desc'] ) ) : '';
	$article_text = ( !empty( $_POST['message'] ) ) ? $_POST['message'] : '';

	$bbcode_uid = ( !empty( $_POST['bbcode_uid'] ) ) ? $_POST['bbcode_uid'] : '';

	$date = time();
	$author_id = $userdata['user_id'] > 0 ? intval ( $userdata['user_id'] ) : '-1';
	$type_id = intval ( $_POST['type_id'] );

	$username = $_POST['username'];
	// Check username
	if (!empty($username))
	{
		$username = phpbb_clean_username($username);

		if (!$userdata['session_logged_in'] || ($userdata['session_logged_in'] && $username != $userdata['username']))
		{
			include(IP_ROOT_PATH . 'includes/functions_validate.' . PHP_EXT);

			$result = validate_username($username);
			if ($result['error'])
			{
				$error_msg = (!empty($error_msg)) ? '<br />' . $result['error_msg'] : $result['error_msg'];

				mx_message_die(GENERAL_MESSAGE, $error_msg );
			}
		}
		else
		{
			$username = '';
		}
	}

	// Check message
	if ( !empty( $article_text ) )
	{
		if ( empty( $bbcode_uid ) )
		{
			$bbcode_uid = ( $bbcode_on ) ? make_bbcode_uid() : '';
		}
		$article_text = prepare_message( trim( $article_text ), $html_on, $bbcode_on, $smilies_on, $bbcode_uid );
	}

	switch ( $kb_post_mode )
	{
		case 'edit': // UPDATE Article -------------------------------------------

			if ( !($kb_is_auth['auth_edit'] || $kb_is_auth['auth_mod']) )
			{
				$message = $lang['No_edit'] . '<br /><br />' . sprintf( $lang['Click_return_kb'], '<a href="' . append_sid( this_kb_mxurl() ) . '">', '</a>' ) . '<br /><br />' . sprintf( $lang['Click_return_index'], '<a href="' . append_sid(IP_ROOT_PATH . FORUM_MG ) . '">', '</a>' );
				mx_message_die( GENERAL_MESSAGE, $message );
			}

			// Get old data first
			$sql = "SELECT *
				  FROM " . KB_ARTICLES_TABLE . "
				  WHERE article_id = $article_id";

			if ( !( $result = $db->sql_query( $sql ) ) )
			{
				mx_message_die( GENERAL_ERROR, "Could not obtain article data", '', __LINE__, __FILE__, $sql );
			}
			$kb_row = $db->sql_fetchrow( $result );

			$old_approve = $kb_row['approved'];
			$old_topic_id = $kb_row['topic_id'];
			$old_category_id = $kb_row['article_category_id'];

			$error_msg = '';

			$cat_switch = $old_category_id != $category_id; // Has switched category

			if ( $kb_is_auth['auth_mod'] || $kb_is_auth['auth_approval_edit'] ) // approval auth
			{
				$approve = 1;

				if ( $cat_switch )
				{
					update_kb_number( $old_category_id, ( $old_approve == 1 ? '- 1' : '0' ) );
				}
			}
			else
			{
				$approve = 2;

				if ( $cat_switch )
				{
					update_kb_number( $old_category_id, ( $old_approve == 1 ? '- 1' : '0' ) );
				}
			}

			$sql = "UPDATE " . KB_ARTICLES_TABLE . "
					SET article_category_id = '$category_id',
					article_title = '$article_title',
					article_description = '$article_description',
					article_date = '$date',
					article_body = '$article_text',
					article_type = '$type_id',
					approved = '$approve',
					bbcode_uid = '$bbcode_uid'
					WHERE article_id = '$article_id'";

			if ( !( $edit_article = $db->sql_query( $sql ) ) )
			{
				mx_message_die( GENERAL_ERROR, "Could not edit article", '', __LINE__, __FILE__, $sql );
			}

			mx_remove_search_post( $article_id, 'kb' );

			// Update kb_row
			$sql = "SELECT *
				  FROM " . KB_ARTICLES_TABLE . "
				  WHERE article_id = $article_id";

			if ( !( $result = $db->sql_query( $sql ) ) )
			{
				mx_message_die( GENERAL_ERROR, "Could not obtain article data", '', __LINE__, __FILE__, $sql );
			}
			$kb_row = $db->sql_fetchrow( $result );

			break;

		case 'add': // ADD NEW ---------------------------------------------------------------------------------

			if ( !($kb_is_auth['auth_post'] || $kb_is_auth['auth_mod']) )
			{
				$message = $lang['No_add'] . '<br /><br />' . sprintf( $lang['Click_return_kb'], '<a href="' . append_sid( this_kb_mxurl() ) . '">', '</a>' ) . '<br /><br />' . sprintf( $lang['Click_return_index'], '<a href="' . append_sid(IP_ROOT_PATH . FORUM_MG ) . '">', '</a>' );
				mx_message_die( GENERAL_MESSAGE, $message );
			}

			if ( $kb_is_auth['auth_approval'] || $kb_is_auth['auth_mod'] )
			{
				$approve = 1;
				update_kb_number( $category_id, '+ 1' );
			}
			else
			{
				$approve = 0;
			}

			$sql = "INSERT INTO " . KB_ARTICLES_TABLE . " ( article_category_id , article_title , article_description , article_date , article_author_id , username , bbcode_uid , article_body , article_type , approved, views )
			VALUES ( '$category_id', '$article_title', '$article_description', '$date', '$author_id', '$username', '$bbcode_uid', '$article_text', '$type_id', '$approve', '0')";

			if ( !( $results = $db->sql_query( $sql ) ) )
			{
				mx_message_die( GENERAL_ERROR, "Could not submit aritcle", '', __LINE__, __FILE__, $sql );
			}

			// Update kb_row
			$sql = "SELECT *
				  FROM " . KB_ARTICLES_TABLE . "
				  WHERE article_date = $date";

			if ( !( $result = $db->sql_query( $sql ) ) )
			{
				mx_message_die( GENERAL_ERROR, "Could not obtain article data", '', __LINE__, __FILE__, $sql );
			}
			$kb_row = $db->sql_fetchrow( $result );
			$article_id = $kb_row['article_id'];

			break;
	}

	$kb_comment = array();

	// Populate the kb_comment variable
	$kb_comment = kb_get_data($kb_row, $userdata, $kb_post_mode);

	// Compose post header
	$subject = $lang['KB_comment_prefix'] . $kb_comment['article_title'];
	$message_temp = kb_compose_comment( $kb_comment );

	$kb_message = $message_temp['message'];
	$kb_update_message = $message_temp['update_message'];

	// Insert phpBB post if using kb commenting
	if ( $approve == 1 && $kb_config['use_comments'] && $kb_is_auth['auth_comment'])
	{
		$topic_data = kb_insert_post( $kb_message, $subject, $kb_comment['category_forum_id'], $kb_comment['article_editor_id'], $kb_comment['article_editor'], $kb_comment['article_editor_sig'], $kb_comment['topic_id'], $kb_update_message );

		$sql = "UPDATE " . KB_ARTICLES_TABLE . " SET topic_id = " . $topic_data['topic_id'] . "
	 	 WHERE article_id = " . $kb_comment['article_id'];

		if ( !( $result = $db->sql_query( $sql ) ) )
		{
			mx_message_die( GENERAL_ERROR, "Could not update article data", '', __LINE__, __FILE__, $sql );
		}
	}

	$kb_custom_field->file_update_data( $article_id );
	$kb_notify_info = $kb_post_mode == 'add' ? 'new' : 'edited';
	kb_notify( $kb_config['notify'], $kb_message, $kb_config['admin_id'], $kb_comment['article_editor_id'], $kb_notify_info );

	if ( $approve == 1 )
	{
		mx_add_search_words( 'single', $article_id, stripslashes( $article_text ), stripslashes( $article_title ), 'kb' );

		// $message = $lang['Article_submitted'] . '<br /><br />' . sprintf( $lang['Click_return_kb'], '<a href="' . append_sid( this_kb_mxurl() ) . '">', '</a>' ) . '<br /><br />' . sprintf( $lang['Click_return_index'], '<a href="' . append_sid(IP_ROOT_PATH . FORUM_MG ) . '">', '</a>' );
		$message = $lang['Article_submitted'] . '<br /><br />' . sprintf( $lang['Click_return_kb'], '<a href="' . append_sid( this_kb_mxurl() ) . '">', '</a>' ) . '<br /><br />' . sprintf($lang['Click_return_article'], '<a href="' . append_sid(this_kb_mxurl("mode=article&amp;k=" . $article_id)). '">', '</a>') . '<br /><br />' . sprintf( $lang['Click_return_index'], '<a href="' . append_sid(IP_ROOT_PATH . FORUM_MG ) . '">', '</a>' );
	}
	else
	{
		$message = $lang['Article_submitted_Approve'] . '<br /><br />' . sprintf( $lang['Click_return_kb'], '<a href="' . append_sid( this_kb_mxurl() ) . '">', '</a>' ) . '<br /><br />' . sprintf( $lang['Click_return_index'], '<a href="' . append_sid(IP_ROOT_PATH . FORUM_MG ) . '">', '</a>' );
	}

	mx_message_die( GENERAL_MESSAGE, $message );

}

// BEGIN - PreText HIDE/SHOW
if ( $kb_config['show_pretext'] )
{
	// Pull Header/Body info.
	$pt_header = $kb_config['pt_header'];
	$pt_body = $kb_config['pt_body'];
	$template->set_filenames(array('pretext' => 'kb_post_pretext.tpl'));
	$template->assign_vars( array(
		'PRETEXT_HEADER' => $pt_header,
		'PRETEXT_BODY' => $pt_body
		)
	);
	$template->assign_var_from_handle( 'KB_PRETEXT_BOX', 'pretext' );
}
// END - PreText HIDE/SHOW

// ---------------------------------------------------------------------------------------------------------- MAIN FORM
// ----------------------------------------------------------------------------------------------------------
// ----------------------------------------------------------------------------------------------------------

// Security
if ( !$kb_is_auth['auth_mod'] )
{
	if ( $kb_post_mode == 'edit' && !$kb_is_auth['auth_edit'] )
	{
		$message = $lang['No_edit'] . '<br /><br />' . sprintf( $lang['Click_return_kb'], '<a href="' . append_sid( this_kb_mxurl() ) . '">', '</a>' ) . '<br /><br />' . sprintf( $lang['Click_return_index'], '<a href="' . append_sid(IP_ROOT_PATH . FORUM_MG ) . '">', '</a>' );
		mx_message_die( GENERAL_MESSAGE, $message );
	}

	if ( $kb_post_mode == 'add' && ( !$kb_is_auth['auth_post'] || $kb_config['allow_new'] == 0 ) )
	{
		$message = $lang['No_add'] . '<br /><br />' . sprintf( $lang['Click_return_kb'], '<a href="' . append_sid( this_kb_mxurl() ) . '">', '</a>' ) . '<br /><br />' . sprintf( $lang['Click_return_index'], '<a href="' . append_sid(IP_ROOT_PATH . FORUM_MG ) . '">', '</a>' );
		mx_message_die( GENERAL_MESSAGE, $message );
	}
}

// First (re)declare basic variables

if ( $kb_post_mode == 'edit' )
{
	$sql = "SELECT *
		 FROM " . KB_ARTICLES_TABLE . "
		 WHERE article_id = '" . $article_id . "'";

	if ( !( $result = $db->sql_query( $sql ) ) )
	{
		mx_message_die( GENERAL_ERROR, "Could not obtain article data", '', __LINE__, __FILE__, $sql );
	}

	$kb_row = $db->sql_fetchrow( $result );
}

$kb_title = ( isset( $_POST['article_name'] ) ) ? htmlspecialchars( trim( stripslashes( $_POST['article_name'] ) ) ) : $kb_row['article_title'];
$kb_desc = ( isset( $_POST['article_desc'] ) ) ? htmlspecialchars( trim( stripslashes( $_POST['article_desc'] ) ) ): $kb_row['article_description'];
$kb_text = ( isset( $_POST['message'] ) ) ? htmlspecialchars( trim( stripslashes( $_POST['message'] ) ) ) : $kb_row['article_body'];

$type_id = ( isset( $_POST['type_id'] ) ) ? htmlspecialchars( trim( stripslashes( $_POST['type_id'] ) ) ) : $kb_row['article_type'];
$bbcode_uid = ( isset( $_POST['bbcode_uid'] ) ) ? htmlspecialchars( trim( stripslashes( $_POST['bbcode_uid'] ) ) ) : $kb_row['bbcode_uid'];
$username = ( isset( $_POST['username'] ) ) ? htmlspecialchars( trim( stripslashes( $_POST['username'] ) ) ) : $kb_row['username'];

if ( $preview )
{
	$preview_title = $kb_title;
	$preview_desc = $kb_desc;
	$preview_text = $kb_text;

	if (!$userdata['user_allowswearywords'])
	{
		$orig_word = array();
		$replacement_word = array();
		obtain_word_list($orig_word, $replacement_word);
	}

	$bbcode_uid = ( $bbcode_on ) ? make_bbcode_uid() : '';
	$preview_text = stripslashes(prepare_message(addslashes(unprepare_message($preview_text)), $html_on, $bbcode_on, $smilies_on, $bbcode_uid));

	$bbcode->allow_html = $html_on;
	if ( $smilies_on )
	{
		$bbcode->allow_smilies = $board_config['allow_smilies'];
	}
	else
	{
		$bbcode->allow_smilies = false;
	}

	$clean_tags = false;
	if ($bbcode_on)
	{
		$bbcode->allow_bbcode = true;
	}
	else
	{
		$bbcode->allow_bbcode = false;
		$clean_tags = true;
	}
	$preview_text = $bbcode->parse($preview_text, $bbcode_uid, false, $clean_tags);

	$orig_autolink = array();
	$replacement_autolink = array();
	obtain_autolink_list($orig_autolink, $replacement_autolink, 99999999);
	if( function_exists( 'acronym_pass' ) )
	{
		$preview_text = acronym_pass( $preview_text );
	}
	if( count($orig_autolink) )
	{
		$preview_text = autolink_transform($preview_text, $orig_autolink, $replacement_autolink);
	}
	//$preview_text = kb_word_wrap_pass ($preview_text);
	if ( count($orig_word) && !$userdata['user_allowswearywords'])
	{
		$preview_text = preg_replace($orig_word, $replacement_word, $preview_text);
	}

	$template->set_filenames( array( 'preview' => 'kb_post_preview.tpl' )
		);

	$template->assign_vars( array(
		'L_PREVIEW' => $lang['Preview'],
		'ARTICLE_TITLE' => $preview_title,
		'ARTICLE_DESC' => $preview_desc,
		'ARTICLE_BODY' => $preview_text,
		'PREVIEW_MESSAGE' => $preview_text,
		)
	);
	$template->assign_var_from_handle( 'KB_PREVIEW_BOX', 'preview' );
}

// show article form - MAIN

if ( $kb_post_mode == 'edit' )
{
	$s_hidden_vars = '<input type="hidden" name="k" value="' . $article_id . '"><input type="hidden" name="bbcode_uid" value="' . $bbcode_uid . '"><input type="hidden" name="author_id" value="' . $author_id . '">';
}
else
{
	$s_hidden_vars = '<input type="hidden" name="cat" value="' . $category_id . '">';
}

// $bbcode_uid = $block_config[$block_id][$block_text_par]['bbcode_uid'];

if ( $bbcode_uid != '' )
{
	$kb_text = bbcuid_killer_mg($kb_text, $bbcode_uid);
}

$kb_text = str_replace('<', '&lt;', $kb_text);
$kb_text = str_replace('>', '&gt;', $kb_text);
$kb_text = str_replace('<br />', "\n", $kb_text);

if ( !$is_block )
{
	include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT );
}

// HTML toggle selection

if ( $html_on )
{
	$html_status = $lang['HTML_is_ON'];
}
else
{
	$html_status = $lang['HTML_is_OFF'];
}

// BBCode toggle selection

if ( $bbcode_on )
{
	$bbcode_status = $lang['BBCode_is_ON'];
}
else
{
	$bbcode_status = $lang['BBCode_is_OFF'];
}

// Smilies toggle selection

if ( $smilies_on )
{
	$smilies_status = $lang['Smilies_are_ON'];

	// Generate smilies listing for page output
	//mx_generate_smilies('inline');
}
else
{
	$smilies_status = $lang['Smilies_are_OFF'];
}

if ( $bbcode_on )
{
	$template->assign_block_vars('switch_bbcodes', array());
}
// load header
include(IP_ROOT_PATH . 'includes/kb_header.' . PHP_EXT );

// set up page
$template->set_filenames(array('body' => 'kb_post_body.tpl'));

if ( !$userdata['session_logged_in'] )
{
	$template->assign_block_vars('switch_name', array());
}

$kb_action_url = $kb_post_mode == 'add' ? this_kb_mxurl( 'mode=add' ) : this_kb_mxurl( 'mode=edit' );
$custom_data = $kb_post_mode == 'add' ? $kb_custom_field->display_edit() : $kb_custom_field->display_edit( $article_id );

if ( $custom_data )
{
	$template->assign_block_vars( 'custom_data_fields', array(
		'L_ADDTIONAL_FIELD' => $lang['Addtional_field']
		)
	);
}

$template->assign_vars( array(
	'S_ACTION' => $kb_action_url,
	'S_HIDDEN_FIELDS' => $s_hidden_vars,

	'ARTICLE_TITLE' => $kb_title,
	'ARTICLE_DESC' => $kb_desc,
	'ARTICLE_BODY' => $kb_text,
	'USERNAME' => $username,

	'L_ADD_ARTICLE' => $lang['Add_article'],

	'L_ARTICLE_TITLE' => $lang['Article_title'],
	'L_ARTICLE_DESCRIPTION' => $lang['Article_description'],
	'L_ARTICLE_TEXT' => $lang['Article_text'],
	'L_ARTICLE_CATEGORY' => $lang['Category'],
	'L_ARTICLE_TYPE' => $lang['Article_type'],
	'L_SUBMIT' => $lang['Submit'],
	'L_PREVIEW' => $lang['Preview'],
	'L_SELECT_TYPE' => $lang['Select'],
	'L_NAME' => $lang['Username'],

	'HTML_STATUS' => $html_status,
	'BBCODE_STATUS' => sprintf( $bbcode_status, '<a href="' . append_sid('faq.' . PHP_EXT . '?mode=bbcode' ) . '" target="_phpbbcode">', '</a>' ),
	'SMILIES_STATUS' => $smilies_status,

	'L_EMPTY_MESSAGE' => $lang['Empty_message'],
	'L_EMPTY_ARTICLE_NAME' => $lang['Empty_article_name'],
	'L_EMPTY_ARTICLE_DESC' => $lang['Empty_article_desc'],
	'L_EMPTY_CAT' => $lang['Empty_category'],
	'L_EMPTY_TYPE' => $lang['Empty_type'],

	'L_PAGES' => $lang['L_Pages'],
	'L_PAGES_EXPLAIN' => $lang['L_Pages_explain'],

	'L_TOC' => $lang['L_Toc'],
	'L_TOC_EXPLAIN' => $lang['L_Toc_explain'],
	'L_ABSTRACT' => $lang['L_Abstract'],
	'L_ABSTRACT_EXPLAIN' => $lang['L_Abstract_explain'],
	'L_TITLE_FORMAT' => $lang['L_Title_Format'],
	'L_TITLE_FORMAT_EXPLAIN' => $lang['L_Title_Format_explain'],
	'L_SUBTITLE_FORMAT' => $lang['L_Subtitle_Format'],
	'L_SUBTITLE_FORMAT_EXPLAIN' => $lang['L_Subtitle_Format_explain'],
	'L_SUBSUBTITLE_FORMAT' => $lang['L_Subsubtitle_Format'],
	'L_SUBSUBTITLE_FORMAT_EXPLAIN' => $lang['L_Subsubtitle_Format_explain'],

	'L_OPTIONS' => $lang['L_Options'],
	'L_FORMATTING' => $lang['L_Formatting'],
	)
);

get_kb_type_list( $type_id );

define('IN_ICYPHOENIX', true);
// BBCBMG - BEGIN
//$bbcbmg_in_acp = true;
include(IP_ROOT_PATH . 'language/lang_' . $board_config['default_lang'] . '/lang_bbcb_mg.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/bbcb_mg.' . PHP_EXT);
$template->assign_var_from_handle('BBCB_MG', 'bbcb_mg');
// BBCBMG - END
// BBCBMG SMILEYS - BEGIN
include_once(IP_ROOT_PATH . 'includes/functions_post.' . PHP_EXT);
generate_smilies('inline');
include(IP_ROOT_PATH . 'includes/bbcb_smileys_mg.' . PHP_EXT);
$template->assign_var_from_handle('BBCB_SMILEYS_MG', 'bbcb_smileys_mg');
// BBCBMG SMILEYS - END

if ( $kb_post_mode == 'edit' )
{
	$template->assign_block_vars( 'switch_edit', array(
		'CAT_LIST' => get_kb_cat_list( 'auth_edit', $category_id, $category_id, true )
		)
	);
}
?>