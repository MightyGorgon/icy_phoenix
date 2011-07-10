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
* UseLess
*
*/

define('IN_ICYPHOENIX', true);

if(!empty($setmodules))
{
	$filename = basename(__FILE__);
	$module['1600_News_Admin']['130_XS_News'] = $filename;
	$module['1600_News_Admin']['120_XS_News_Config'] = $filename . '?mode=config';
	return;
}

if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('pagestart.' . PHP_EXT);
$db->clear_cache('xs_');
require_once(IP_ROOT_PATH . 'includes/functions_xs_admin.' . PHP_EXT);
require_once(IP_ROOT_PATH . 'includes/functions_xs_useless.' . PHP_EXT);

// define the path to the admin news templates
define('XS_TPL_PATH', '../../templates/common/xs_mod/tpl_news/');

setup_extra_lang(array('lang_xs_news'));

$news_text = request_var('news_text', '', true);
$news_text = htmlspecialchars_decode($news_text, ENT_COMPAT);
$message = request_var('message', '', true);
$message = htmlspecialchars_decode($message, ENT_COMPAT);

$news_text = !empty($message) ? $message : $news_text;

// Set Date format based on the admin choice
switch ($config['xs_news_dateformat'])
{
	case 0:
	$date_format_ae = 'd/m/Y';
	$date_format_display = 'd M Y'; // displays '01 Jan 2005'
	$date_format_explain = sprintf($lang['xs_news_dateformat_helper'], 'dd/mm/yyyy');
	break;

	case 1:
	$date_format_ae = 'm/d/Y';
	$date_format_display = 'M d Y'; // displays 'Jan 01 2005'
	$date_format_explain = sprintf($lang['xs_news_dateformat_helper'], 'mm/dd/yyyy');
	break;

	case 2:
	$date_format_ae = 'd/m/Y';
	$date_format_display = 'd F Y'; // displays 'Jan 01 2005'
	$date_format_explain = sprintf($lang['xs_news_dateformat_helper'], 'dd/mm/yyyy');
	break;

	case 3:
	$date_format_ae = 'd/m/Y';
	$date_format_display = 'F d Y'; // displays 'January 01 2005'
	$date_format_explain = sprintf($lang['xs_news_dateformat_helper'], 'dd/mm/yyyy');
	break;
	case 4:
	$date_format_ae = 'd/m/Y';
	$date_format_display = 'jS M Y'; //displays '1st Jan 2005'
	$date_format_explain = sprintf($lang['xs_news_dateformat_helper'], 'dd/mm/yyyy');
	break;

	case 5:
	$date_format_ae = 'd/m/Y';
	$date_format_display = 'M jS Y'; //displays 'Jan 1st 2005'
	$date_format_explain = sprintf($lang['xs_news_dateformat_helper'], 'dd/mm/yyyy');
	break;

	case 6:
	$date_format_ae = 'd/m/Y';
	$date_format_display = 'jS F Y'; // displays '1st January 2005'
	$date_format_explain = sprintf($lang['xs_news_dateformat_helper'], 'dd/mm/yyyy');
	break;

	case 7:
	$date_format_ae = 'd/m/Y';
	$date_format_display = 'F jS Y'; // displays 'January 1st 2005'
	$date_format_explain = sprintf($lang['xs_news_dateformat_helper'], 'dd/mm/yyyy');
	break;
}

$mode = request_var('mode', '');

if (isset($_POST['cancel']))
{
	$mode = '';
}

if(isset($_POST['addnews']))
{
	$mode = 'addnews';
}

$confirm = (isset($_POST['confirm'])) ? true : 0;

if(!empty($mode))
{

	switch($mode)
	{
		case 'config':
			$xs_news_config_vars = array('xs_show_news', 'xs_show_ticker', 'xs_news_dateformat', 'xs_show_ticker_subtitle', 'xs_show_news_subtitle');
			for ($i = 0; $i < sizeof($xs_news_config_vars); $i++)
			{
				$config_name = $xs_news_config_vars[$i];
				$config_value = $config[$xs_news_config_vars[$i]];
				$default_config[$config_name] = $config_value;

				$new[$config_name] = (isset($_POST[$config_name])) ? request_post_var($config_name, '') : $default_config[$config_name];

				if(isset($_POST['submit']))
				{
					set_config($config_name, $new[$config_name], false);
				}
			}

			if(isset($_POST['submit']))
			{
				$cache->destroy('config');
				$db->clear_cache('xs_');

				$message = $lang['n_config_updated'] . '<br /><br />' . sprintf($lang['Click_return_config'], '<a href="' . append_sid('admin_xs_news.' . PHP_EXT . '?mode=config') . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

				message_die(GENERAL_MESSAGE, $message);
			}


			$l_title = $lang['n_edit_header'];
			$newmode = 'config';
			$buttonvalue = $lang['Update'];

			$show_xs_news_yes = ($new['xs_show_news']) ? 'checked="checked"' : '';
			$show_xs_news_no = (!$new['xs_show_news']) ? 'checked="checked"' : '';

			$show_xs_ticker_yes = ($new['xs_show_ticker']) ? 'checked="checked"' : '';
			$show_xs_ticker_no = (!$new['xs_show_ticker']) ? 'checked="checked"' : '';

			$show_xs_ticker_subtitle_yes = ($new['xs_show_ticker_subtitle']) ? 'checked="checked"' : '';
			$show_xs_ticker_subtitle_no = (!$new['xs_show_ticker_subtitle']) ? 'checked="checked"' : '';
			$show_xs_news_subtitle_yes = ($new['xs_show_news_subtitle']) ? 'checked="checked"' : '';
			$show_xs_news_subtitle_no = (!$new['xs_show_news_subtitle']) ? 'checked="checked"' : '';

			$xs_news_dateformat_select = '<select name="xs_news_dateformat">';
			$xs_news_dateformat_select .= '<option value="0">' . create_date("d M Y", time(), $config['board_timezone']) . '</option>';
			$xs_news_dateformat_select .= '<option value="1">' . create_date("M d Y", time(), $config['board_timezone']) . '</option>';
			$xs_news_dateformat_select .= '<option value="2">' . create_date("d F Y", time(), $config['board_timezone']) . '</option>';
			$xs_news_dateformat_select .= '<option value="3">' . create_date("F d Y", time(), $config['board_timezone']) . '</option>';
			$xs_news_dateformat_select .= '<option value="4">' . create_date("jS M Y", time(), $config['board_timezone']) . '</option>';
			$xs_news_dateformat_select .= '<option value="5">' . create_date("M jS Y", time(), $config['board_timezone']) . '</option>';
			$xs_news_dateformat_select .= '<option value="6">' . create_date("jS F Y", time(), $config['board_timezone']) . '</option>';
			$xs_news_dateformat_select .= '<option value="7">' . create_date("F jS Y", time(), $config['board_timezone']) . '</option>';

			$xs_news_dateformat_select .= '</select>';
			$xs_news_dateformat_select = str_replace("value=\"" . $new['xs_news_dateformat'] . "\">", "value=\"" . $new['xs_news_dateformat'] . "\" selected=\"selected\">&raquo;" ,$xs_news_dateformat_select);

			$template->set_filenames(array('body' =>  XS_TPL_PATH . 'news_config_body.tpl'));

			$s_hidden_fields = '<input type="hidden" name="mode" value="' . $newmode .'" />';

			$template->assign_vars(array(
				'S_FORUM_ACTION' => append_sid('admin_xs_news.' . PHP_EXT),
				'S_HIDDEN_FIELDS' => $s_hidden_fields,
				'S_SUBMIT_VALUE' => $buttonvalue,

				'L_PAGE_TITLE' => $lang['n_config_title'],
				'L_PAGE_TITLE_EXPLAIN' => $lang['n_config_title_explain'],
				'L_NEWS_SETTINGS' => $l_title,

				'L_XS_NEWS_SETTINGS' => $lang['xs_news_settings'],
				'L_XS_SHOW_NEWS' => $lang['xs_news_show'],
				'L_XS_NEWS_DATEFORMAT' => $lang['xs_news_dateformat'],
				'L_XS_SHOW_TICKER' => $lang['xs_news_show_ticker'],
				'L_XS_SHOW_TICKER_EXPLAIN' => $lang['xs_news_show_ticker_explain'],
				'L_XS_SHOW_TICKER_SUBTITLE' => $lang['xs_news_show_ticker_subtitle'],
				'L_XS_SHOW_TICKER_SUBTITLE_EXPLAIN' => $lang['xs_news_show_ticker_subtitle_explain'],
				'L_XS_SHOW_NEWS_SUBTITLE' => $lang['xs_news_show_news_subtitle'],
				'L_XS_SHOW_NEWS_SUBTITLE_EXPLAIN' => $lang['xs_news_show_news_subtitle_explain'],

				'XS_NEWS_DATEFORMAT' => $xs_news_dateformat_select,
				'XS_SHOWNEWS_YES' => $show_xs_news_yes,
				'XS_SHOWNEWS_NO' => $show_xs_news_no,
				'XS_SHOWTICKER_YES' => $show_xs_ticker_yes,
				'XS_SHOWTICKER_NO' => $show_xs_ticker_no,
				'XS_SHOWTICKER_SUBT_YES' => $show_xs_ticker_subtitle_yes,
				'XS_SHOWTICKER_SUBT_NO' => $show_xs_ticker_subtitle_no,
				'XS_SHOWNEWS_SUBT_YES' => $show_xs_news_subtitle_yes,
				'XS_SHOWNEWS_SUBT_NO' => $show_xs_news_subtitle_no,
				)
			);

			$template->pparse('body');
			break;

		case 'addnews':
		case 'editnews':
			// Show form to create/modify a news item
			if ($mode == 'editnews')
			{
				// $newmode determines if we are going to INSERT or UPDATE after posting?

				$l_title = $lang['n_edit_header'];
				$newmode = 'modnews';
				$buttonvalue = $lang['Update'];

				$news_id = request_get_var('id', 0);

				$row = xsm_get_info('news', $news_id);

				$news_id = $row['news_id'];
				$news_date = create_date($date_format_ae, $row['news_date'], $config['board_timezone']);
				$news_item = xsm_unprepare_message($row['news_text']);

				$news_display_yes = ($row['news_display']) ? 'checked="checked"' : '';
				$news_display_no = (!$row['news_display']) ? 'checked="checked"' : '';
				$news_smilies_yes = ($row['news_smilies']) ? 'checked="checked"' : '';
				$news_smilies_no = (!$row['news_smilies']) ? 'checked="checked"' : '';

			}
			else
			{
				$l_title = $lang['n_add_header'];
				$newmode = 'createnews';
				$buttonvalue = $lang['n_create_item'];

				$news_date = create_date($date_format_ae, time(), $config['board_timezone']);
				$news_item = '';
				$news_display_yes = 'checked="checked"';
				$news_display_no = '';
				$news_smilies_yes = '';
				$news_smilies_no = 'checked="checked"';
			}

			$template->set_filenames(array('body' => XS_TPL_PATH . 'news_edit_body.tpl'));

			$s_hidden_fields = '<input type="hidden" name="mode" value="' . $newmode .'" />';
			$s_hidden_fields .= '<input type="hidden" name="id" value="' . $news_id . '" />';

			$template->assign_vars(array(
				'U_MORE_SMILIES' => append_sid('../posting.' . PHP_EXT . '?mode=smilies'),
				'S_FORUM_ACTION' => append_sid('admin_xs_news.' . PHP_EXT),
				'S_HIDDEN_FIELDS' => $s_hidden_fields,
				'S_SUBMIT_VALUE' => $buttonvalue,

				'L_PAGE_TITLE' => ($newmode == 'modnews') ? $lang['n_edit_page_title'] : $lang['n_add_page_title'],
				'L_PAGE_TITLE_EXPLAIN' => ($newmode == 'modnews') ? $lang['n_edit_page_title_explain'] : $lang['n_add_page_title_explain'],
				'L_NEWS_SETTINGS' => $l_title,

				'L_NEWS_DATE' => $lang['n_news_date'],
				'L_NEWS_ITEM' => $lang['n_news_item'],
				'L_NEWS_DISPLAY' => $lang['n_news_item_display'],

				'L_NEWS_SMILIES' => $lang['n_news_smilies'],
				'L_ALL_SMILIES' => $lang['n_smilies_button'],

				'NEWS_SMILIES_YES' => $news_smilies_yes,
				'NEWS_SMILIES_NO' => $news_smilies_no,

				'NEWS_DATE_EXPLAIN' => $date_format_explain,
				'NEWS_DATE' => $news_date,
				'NEWS_ITEM' => $news_item,
				'NEWS_DISPLAY_YES' => $news_display_yes,
				'NEWS_DISPLAY_NO' => $news_display_no
				)
			);

			$template->pparse('body');
			break;

		case 'createnews':
			// Create a new news item in the DB
			if(empty($news_text))
			{
				$message = $lang['n_create_item_null'] . '<br /><br />' . sprintf($lang['n_click_return_newslist'], '<a href="' . append_sid('admin_xs_news.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);
			}

			$news_item = xsm_prepare_message($news_text);

			$news_date = request_post_var('news_date', '');
			$news_date = (empty($news_date) ? create_date($date_format_ae, time(), $config['board_timezone']) : $news_date);

			$date_split = explode('/', $news_date);
			$date_month = (($config['xs_news_dateformat'] == 1) ? $date_split[0] : $date_split[1]);
			$date_day = (($config['xs_news_dateformat'] == 1) ? $date_split[1] : $date_split[0]);
			$date_error = (($config['xs_news_dateformat'] == 1) ? 'mm/dd' : 'dd/mm');

			if(!checkdate($date_month, $date_day, $date_split[2]))
			{
				$message = str_replace('dd/mm', $date_error, $lang['xs_news_invalid_date']) . '<br /><br />' . sprintf($lang['n_click_return_newslist'], '<a href="' . append_sid('admin_xs_news.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

				message_die(GENERAL_MESSAGE, $message);
			}


			$news_date_posting = gmmktime(gmdate('H'), gmdate('i'), gmdate('s'), $date_month, $date_day, $date_split[2]);

			$sql = "SELECT MAX(news_id) AS max_id
				FROM " . XS_NEWS_TABLE;
			$result = $db->sql_query($sql);
			$row = $db->sql_fetchrow($result);
			$max_id = $row['max_id'];
			$next_id = $max_id + 1;

			$sql = "INSERT INTO " . XS_NEWS_TABLE . " (news_id, news_date, news_text, news_display, news_smilies" . ")
				VALUES ('" . $next_id . "', '" . $news_date_posting . "', '" . $db->sql_escape($news_item) . "', '" . intval($_POST['news_display']) . "', '" . intval($_POST['news_smilies']) . "')";
			$result = $db->sql_query($sql);
			$db->clear_cache('xs_');

			$message = $lang['n_news_item_added'] . '<br /><br />' . sprintf($lang['n_click_return_newslist'], '<a href="' . append_sid('admin_xs_news.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

			message_die(GENERAL_MESSAGE, $message);

			break;

		case 'modnews':
			// Modify a news item in the DB
			$news_item = xsm_prepare_message($news_text);

			$news_date = request_post_var('news_date', '');
			$news_date = (empty($news_date) ? create_date($date_format_ae, time(), $config['board_timezone']) : $news_date);

			$date_split = explode('/', $news_date);
			$date_month = (($config['xs_news_dateformat'] == 1) ? $date_split[0] : $date_split[1]);
			$date_day = (($config['xs_news_dateformat'] == 1) ? $date_split[1] : $date_split[0]);
			$date_error = (($config['xs_news_dateformat'] == 1) ? 'mm/dd' : 'dd/mm');

			if(!checkdate($date_month, $date_day, $date_split[2]))
			{
				$message = str_replace('dd/mm', $date_error, $lang['xs_news_invalid_date']) . '<br /><br />' . sprintf($lang['n_click_return_newslist'], '<a href="' . append_sid('admin_xs_news.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);
			}

			$news_date_posting = gmmktime(gmdate('H'), gmdate('i'), gmdate('s'), $date_month, $date_day, $date_split[2]);

			$sql = "UPDATE " . XS_NEWS_TABLE . "
				SET news_date = " . $news_date_posting . ", news_text = '" . $db->sql_escape($news_item) . "', news_display = " . intval($_POST['news_display']) . ", news_smilies = " . intval($_POST['news_smilies']). "
				WHERE news_id = " . intval($_POST['id']);
			$result = $db->sql_query($sql);
			$db->clear_cache('xs_');

			$message = $lang['n_news_updated'] . '<br /><br />' . sprintf($lang['n_click_return_newslist'], '<a href="' . append_sid('admin_xs_news.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

			message_die(GENERAL_MESSAGE, $message);

			break;

		case 'deletenews':
			// Show form to delete a news item
			$news_id = intval($_GET['id']);

			$buttonvalue = $lang['Delete'];

			$newmode = 'deletenews';

			$news_info = xsm_get_info('news', $news_id);
			$name = $news_info['n_news_item'];

			if($confirm)
			{
				$sql = "DELETE FROM " . XS_NEWS_TABLE . "
					WHERE news_id = $news_id";
				$result = $db->sql_query($sql);
				$db->clear_cache('xs_');

				$message = $lang['n_news_updated'] . '<br /><br />' . sprintf($lang['n_click_return_newslist'], '<a href="' . append_sid('admin_xs_news.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

				message_die(GENERAL_MESSAGE, $message);
			}
			else
			{
				// Set template files
				$template->set_filenames(array('confirm' => XS_TPL_PATH . 'news_confirm_body.tpl'));

				$s_hidden_fields = '<input type="hidden" name="mode" value="' . $newmode . '" /><input type="hidden" name="id" value="' . $news_id . '" />';

				$template->assign_vars(array(
					'MESSAGE_TITLE' => $lang['Confirm'],
					'MESSAGE_TEXT' => sprintf($lang['n_confirm_delete_news'], $name),

					'L_YES' => $lang['Yes'],
					'L_NO' => $lang['No'],

					'S_CONFIRM_ACTION' => append_sid('admin_xs_news.' . PHP_EXT . '?id=' . $news_id),
					'S_HIDDEN_FIELDS' => $s_hidden_fields
					)
				);

				$template->pparse('confirm');
			}
			break;

		default:
			message_die(GENERAL_MESSAGE, $lang['No_mode']);
			break;
	}

	if ($show_index != true)
	{
		include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);
		exit;
	}
}

// Start page proper
$template->set_filenames(array('body' => XS_TPL_PATH . 'news_list_body.tpl'));

$template->assign_vars(array(
	'S_FORUM_ACTION' => append_sid('admin_xs_news.' . PHP_EXT),
	'L_MENU_TITLE' => $lang['n_title'],
	'L_MENU_EXPLAIN' => $lang['n_main_title_explain'],
	'L_MENU_SETTINGS' => $lang['n_main_title'],
	'L_CREATE_NEWS' => $lang['n_create_item'],
	'L_EDIT' => $lang['Edit'],
	'L_DELETE' => $lang['Delete'],
	'XS_PATH' => '../templates/common/xs_mod/',
	)
);

$sql = "SELECT * FROM " . XS_NEWS_TABLE . "
	ORDER BY news_date DESC";
$q_news = $db->sql_query($sql);

if($total_news = $db->sql_numrows($q_news))
{
	$news_rows = $db->sql_fetchrowset($q_news);

	for($i = 0; $i < $total_news; $i++)
	{
		$news_id = $news_rows[$i]['news_id'];
		$news_date = create_date($date_format_display, $news_rows[$i]['news_date'], $config['board_timezone']);
		$news_text = xsm_unprepare_message($news_rows[$i]['news_text']);
		$news_display = $news_rows[$i]['news_display'];
		$news_smilies = $news_rows[$i]['news_smilies'];
		if($news_smilies)
		{
			$news_text = smilies_news($news_text);
		}

		$show_item = (($news_display) ? '[ <span class="text_green">' . $lang['Yes'] . '</span> ]' : '[ <span class="text_red">' . $lang['No'] . '</span> ]');

		$template->assign_block_vars('newsitem', array(
			'NEWS_ID' => $block_id,
			'NEWS_DATE' => $news_date,
			'NEWS_ITEM' => $news_text,
			'NEWS_ITEM_DISPLAY' => $show_item,

			'U_NEWS_EDIT' => append_sid('admin_xs_news.' . PHP_EXT . '?mode=editnews&amp;id=' . $news_id),
			'U_NEWS_DELETE' => append_sid('admin_xs_news.' . PHP_EXT . '?mode=deletenews&amp;id=' . $news_id)
			)
		);

	}

}
elseif($db->sql_numrows($q_news) == 0)
{
	$template->assign_block_vars('no_news', array(
		'NEWS_DATE' => create_date($date_format_display, time(), $config['board_timezone']),
		'NEWS_ITEM' => $lang['xs_no_news']
		)
	);
}

$template->pparse('body');

include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);

?>