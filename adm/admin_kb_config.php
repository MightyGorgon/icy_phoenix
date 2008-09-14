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

define('IN_ICYPHOENIX', true);

if (!empty($setmodules))
{
	$file = basename(__FILE__);
	$module['1800_KB_title']['100_KB_Configuration'] = $file;
	return;
}

if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('./pagestart.' . PHP_EXT);
include(IP_ROOT_PATH . 'config.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/functions_admin.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/kb_constants.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/functions_kb.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/functions_kb_auth.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/functions_kb_field.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/functions_kb_mx.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/functions_search.' . PHP_EXT);

// Pull all config data

$sql = "SELECT *
		 FROM " . KB_CONFIG_TABLE;
if (!$result = $db->sql_query($sql))
{
	message_die(CRITICAL_ERROR, "Could not query knowledge base configuration information", "", __LINE__, __FILE__, $sql);
}
else
{
	while ($row = $db->sql_fetchrow($result))
	{
		$config_name = $row['config_name'];
		$config_value = $row['config_value'];
		$default_config[$config_name] = $config_value;

		$new[$config_name] = (isset($_POST[$config_name])) ? $_POST[$config_name] : $default_config[$config_name];

		if (isset($_POST['submit']))
		{
			$sql = "UPDATE " . KB_CONFIG_TABLE . " SET
				   		config_value = '" . str_replace("\'", "''", $new[$config_name]) . "'
						WHERE config_name = '$config_name'";
			if (!$db->sql_query($sql))
			{
				mx_message_die(GENERAL_ERROR, "Failed to update general configuration for $config_name", "", __LINE__, __FILE__, $sql);
			}
		}
	}

	if (isset($_POST['submit']))
	{
		$message = $lang['KB_config_updated'] . '<br /><br />' . sprintf($lang['Click_return_kb_config'], "<a href=\"" . append_sid('admin_kb_config.' . PHP_EXT . '?mode=config') . "\">", "</a>") . '<br /><br />' . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid(IP_ROOT_PATH . ADM . 'index.' . PHP_EXT . '?pane=right') . "\">", "</a>");

		message_die(GENERAL_MESSAGE, $message);
	}
}

$new_yes = ($new['allow_new']) ? "checked=\"checked\"" : "";
$new_no = (!$new['allow_new']) ? "checked=\"checked\"" : "";

$allow_html_yes = ($new['allow_html']) ? "checked=\"checked\"" : "";
$allow_html_no = (!$new['allow_html']) ? "checked=\"checked\"" : "";

$allow_bbcode_yes = ($new['allow_bbcode']) ? "checked=\"checked\"" : "";
$allow_bbcode_no = (!$new['allow_bbcode']) ? "checked=\"checked\"" : "";

$allow_smilies_yes = ($new['allow_smilies']) ? "checked=\"checked\"" : "";
$allow_smilies_no = (!$new['allow_smilies']) ? "checked=\"checked\"" : "";

$formatting_fixup_yes = ($new['formatting_fixup']) ? "checked=\"checked\"" : "";
$formatting_fixup_no = (!$new['formatting_fixup']) ? "checked=\"checked\"" : "";

$wysiwyg_yes = ($new['wysiwyg']) ? "checked=\"checked\"" : "";
$wysiwyg_no = (!$new['wysiwyg']) ? "checked=\"checked\"" : "";

$kb_allowed_html_tags = $new['allowed_html_tags'];

$wysiwyg_path = $new['wysiwyg_path'];

$pretext_show = ($new['show_pretext']) ? "checked=\"checked\"" : "";
$pretext_hide = (!$new['show_pretext']) ? "checked=\"checked\"" : "";

$pt_header = $new['pt_header'];
$pt_body = $new['pt_body'];

$notify_none = ($new['notify'] == 0) ? "checked=\"checked\"" : "";
$notify_pm = ($new['notify'] == 1) ? "checked=\"checked\"" : "";
$notify_email = ($new['notify'] == 2) ? "checked=\"checked\"" : "";

$admin_id = $new['admin_id'];

$use_comments_yes = ($new['use_comments']) ? "checked=\"checked\"" : "";
$use_comments_no = (!$new['use_comments']) ? "checked=\"checked\"" : "";

$del_topic_yes = ($new['del_topic']) ? "checked=\"checked\"" : "";
$del_topic_no = (!$new['del_topic']) ? "checked=\"checked\"" : "";
// Added by Haplo
$comments_show_yes = ($new['comments_show']) ? "checked=\"checked\"" : "";
$comments_show_no = (!$new['comments_show']) ? "checked=\"checked\"" : "";

$bump_post_yes = ($new['bump_post']) ? "checked=\"checked\"" : "";
$bump_post_no = (!$new['bump_post']) ? "checked=\"checked\"" : "";

$stats_list_yes = ($new['stats_list']) ? "checked=\"checked\"" : "";
$stats_list_no = (!$new['stats_list']) ? "checked=\"checked\"" : "";

$header_banner_yes = ($new['header_banner']) ? "checked=\"checked\"" : "";
$header_banner_no = (!$new['header_banner']) ? "checked=\"checked\"" : "";

//$mod_group = get_groups($new['mod_group']);

$use_ratings_yes = ($new['use_ratings']) ? "checked=\"checked\"" : "";
$use_ratings_no = (!$new['use_ratings']) ? "checked=\"checked\"" : "";

//$allow_anonymos_rating_yes = ($new['allow_anonymos_rating']) ? "checked=\"checked\"" : "";
//$allow_anonymos_rating_no = (!$new['allow_anonymos_rating']) ? "checked=\"checked\"" : "";

$votes_check_ip_yes = ($new['votes_check_ip']) ? "checked=\"checked\"" : "";
$votes_check_ip_no = (!$new['votes_check_ip']) ? "checked=\"checked\"" : "";

$votes_check_userid_yes = ($new['votes_check_userid']) ? "checked=\"checked\"" : "";
$votes_check_userid_no = (!$new['votes_check_userid']) ? "checked=\"checked\"" : "";

$article_pag = $new['art_pagination'];
$comments_pag = $new['comments_pagination'];

$news_sort_options = array();
$news_sort_options = array("Latest", "Creation", "Id", "Userrank", "Alphabetic");

$news_sort_list = '<select name="news_sort">';
for($j = 0; $j < count($news_sort_options); $j++)
{
	if ($new['news_sort'] == $news_sort_options[$j])
	{
		$status = "selected";
	}
	else
	{
		$status = '';
	}
	$news_sort_list .= '<option value="' . $news_sort_options[$j] . '" ' . $status . '>' . $news_sort_options[$j] . '</option>';
}
$news_sort_list .= '</select>';

$news_sort_par_options = array();
$news_sort_par_options = array("DESC", "ASC");

$news_sort_par_list = '<select name="news_sort_par">';
for($j = 0; $j < count($news_sort_par_options); $j++)
{
	if ($new['news_sort_par'] == $news_sort_par_options[$j])
	{
		$status = "selected";
	}
	else
	{
		$status = '';
	}
	$news_sort_par_list .= '<option value="' . $news_sort_par_options[$j] . '" ' . $status . '>' . $news_sort_par_options[$j] . '</option>';
}
$news_sort_par_list .= '</select>';

$template->set_filenames(array('body' => ADM_TPL . 'kb_config_body.tpl')
	);

$template->assign_vars(array(
		'S_ACTION' => append_sid('admin_kb_config.' . PHP_EXT . '?mode=config'),
		'L_SUBMIT' => $lang['Submit'],
		'L_RESET' => $lang['Reset'],

		'L_YES' => $lang['Yes'],
		'L_NO' => $lang['No'],
		'L_NONE' => $lang['Acc_None'],

		'L_CONFIGURATION_TITLE' => $lang['KB_config_title'],
		'L_CONFIGURATION_EXPLAIN' => $lang['KB_config_explain'],

		'L_NEW_NAME' => $lang['New_title'],
		'L_NEW_EXPLAIN' => $lang['New_explain'],
		'S_NEW_YES' => $new_yes,
		'S_NEW_NO' => $new_no,

		'L_SHOW' => $lang['Show'],
		'L_HIDE' => $lang['Hide'],
		'L_PRE_TEXT_NAME' => $lang['Pre_text_name'],
		'L_PRE_TEXT_HEADER' => $lang['Pre_text_header'],
		'L_PRE_TEXT_BODY' => $lang['Pre_text_body'],
		'L_PRE_TEXT_EXPLAIN' => $lang['Pre_text_explain'],
		'S_SHOW_PRETEXT' => $pretext_show,
		'S_HIDE_PRETEXT' => $pretext_hide,
		'L_PT_HEADER' => $pt_header,
		'L_PT_BODY' => $pt_body,

		'L_NOTIFY_NAME' => $lang['Notify_name'],
		'L_NOTIFY_EXPLAIN' => $lang['Notify_explain'],
		'L_EMAIL' => $lang['Email'],
		'L_PM' => $lang['PM'],
		'S_NOTIFY_NONE' => $notify_none,
		'S_NOTIFY_EMAIL' => $notify_email,
		'S_NOTIFY_PM' => $notify_pm,

		'L_ADMIN_ID_NAME' => $lang['Admin_id_name'],
		'L_ADMIN_ID_EXPLAIN' => $lang['Admin_id_explain'],
		'ADMIN_ID' => $admin_id,

		'L_USE_COMMENTS' => $lang['Use_comments'],
		'L_USE_COMMENTS_EXPLAIN' => $lang['Use_comments_explain'],
		'S_USE_COMMENTS_YES' => $use_comments_yes,
		'S_USE_COMMENTS_NO' => $use_comments_no,

		'L_RATINGS_INFO' => $lang['Rating_info'],
		'L_COMMENTS_INFO' => $lang['Comment_info'],

		'L_COMMENTS_SHOW' => $lang['Comments_show'],
		'L_COMMENTS_SHOW_EXPLAIN' => $lang['Comments_show_explain'],
		'S_COMMENTS_SHOW_YES' => $comments_show_yes,
		'S_COMMENTS_SHOW_NO' => $comments_show_no,

		'L_BUMP_POST' => $lang['Bump_post'],
		'L_BUMP_POST_EXPLAIN' => $lang['Bump_post_explain'],
		'S_BUMP_POST_YES' => $bump_post_yes,
		'S_BUMP_POST_NO' => $bump_post_no,

		'L_FORMATTING_FIXUP' => $lang['Formatting_fixup'],
		'L_FORMATTING_FIXUP_EXPLAIN' => $lang['Formatting_fixup_explain'],
		'S_FORMATTING_FIXUP_YES' => $formatting_fixup_yes,
		'S_FORMATTING_FIXUP_NO' => $formatting_fixup_no,

		'L_WYSIWYG' => $lang['Wysiwyg'],
		'L_WYSIWYG_EXPLAIN' => $lang['Wysiwyg_explain'],
		'S_WYSIWYG_YES' => $wysiwyg_yes,
		'S_WYSIWYG_NO' => $wysiwyg_no,

		'L_WYSIWYG_PATH' => $lang['Wysiwyg_path'],
		'L_WYSIWYG_PATH_EXPLAIN' => $lang['Wysiwyg_path_explain'],
		'WYSIWYG_PATH' => $wysiwyg_path,

		'L_ALLOW_HTML' => $lang['Allow_HTML'],
		'L_ALLOW_HTML_EXPLAIN' => $lang['Allow_html_explain'],
		'S_ALLOW_HTML_YES' => $allow_html_yes,
		'S_ALLOW_HTML_NO' => $allow_html_no,

		'L_ALLOW_BBCODE' => $lang['Allow_BBCode'],
		'L_ALLOW_BBCODE_EXPLAIN' => $lang['Allow_bbcode_explain'],
		'S_ALLOW_BBCODE_YES' => $allow_bbcode_yes,
		'S_ALLOW_BBCODE_NO' => $allow_bbcode_no,

		'L_ALLOW_SMILIES' => $lang['Allow_smilies'],
		'L_ALLOW_SMILIES_EXPLAIN' => $lang['Allow_smilies_explain'],
		'S_ALLOW_SMILIES_YES' => $allow_smilies_yes,
		'S_ALLOW_SMILIES_NO' => $allow_smilies_no,

		'L_ALLOWED_HTML_TAGS' => $lang['Allowed_tags'],
		'L_ALLOWED_HTML_TAGS_EXPLAIN' => $lang['Allowed_tags_explain'],
		'ALLOWED_HTML_TAGS' => $kb_allowed_html_tags,

		'L_STATS_LIST' => $lang['Stats_list'],
		'L_STATS_LIST_EXPLAIN' => $lang['Stats_list_explain'],
		'S_STATS_LIST_YES' => $stats_list_yes,
		'S_STATS_LIST_NO' => $stats_list_no,

		'L_HEADER_BANNER' => $lang['Header_banner'],
		'L_HEADER_BANNER_EXPLAIN' => $lang['Header_banner_explain'],
		'S_HEADER_BANNER_YES' => $header_banner_yes,
		'S_HEADER_BANNER_NO' => $header_banner_no,

		'L_ANON_NAME' => $lang['Allow_anon_name'],
		'L_ANON_EXPLAIN' => $lang['Allow_anon_explain'],
		'S_ANON_YES' => $anon_yes,
		'S_ANON_NO' => $anon_no,

		'L_USE_RATINGS' => $lang['Use_ratings'],
		'L_USE_RATINGS_EXPLAIN' => $lang['Use_ratings_explain'],
		'S_USE_RATINGS_YES' => $use_ratings_yes,
		'S_USE_RATINGS_NO' => $use_ratings_no,

		'L_VOTES_CHECK_IP' => $lang['Votes_check_ip'],
		'L_VOTES_CHECK_IP_EXPLAIN' => $lang['Votes_check_ip_explain'],
		'S_VOTES_CHECK_IP_YES' => $votes_check_ip_yes,
		'S_VOTES_CHECK_IP_NO' => $votes_check_ip_no,

		'L_VOTES_CHECK_USERID' => $lang['Votes_check_userid'],
		'L_VOTES_CHECK_USERID_EXPLAIN' => $lang['Votes_check_userid_explain'],
		'S_VOTES_CHECK_USERID_YES' => $votes_check_userid_yes,
		'S_VOTES_CHECK_USERID_NO' => $votes_check_userid_no,

		'L_ARTICLE_PAG' => $lang['Article_pag'],
		'L_ARTICLE_PAG_EXPLAIN' => $lang['Article_pag_explain'],
		'ARTICLE_PAG' => $article_pag,

		'L_COMMENTS_PAG' => $lang['Comments_pag'],
		'L_COMMENTS_PAG_EXPLAIN' => $lang['Comments_pag_explain'],
		'COMMENTS_PAG' => $comments_pag,

		'L_NEWS_SORT' => $lang['News_sort'],
		'L_NEWS_SORT_EXPLAIN' => $lang['News_sort_explain'],
		'NEWS_SORT' => $news_sort_list,

		'L_NEWS_SORT_PAR' => $lang['News_sort_par'],
		'L_NEWS_SORT_PAR_EXPLAIN' => $lang['News_sort_par_explain'],
		'NEWS_SORT_PAR' => $news_sort_par_list,

		'L_DEL_TOPIC' => $lang['Del_topic'],
		'L_DEL_TOPIC_EXPLAIN' => $lang['Del_topic_explain'],
		'S_DEL_TOPIC_YES' => $del_topic_yes,
		'S_DEL_TOPIC_NO' => $del_topic_no)
	);

$template->pparse('body');
// include('./page_footer_admin.' . PHP_EXT);
include_once(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);

?>
