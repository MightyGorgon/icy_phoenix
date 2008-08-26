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
* UseLess
*
*/

define('IN_PHPBB', true);

if( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	$module['1600_News_Admin']['140_XS_News_Tickers'] = $filename;
	return;
}

$phpbb_root_path = './../';
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);
require_once($phpbb_root_path . 'includes/functions_xs_admin.' . $phpEx);
require_once($phpbb_root_path . 'includes/functions_xs_useless.' . $phpEx);

// define the path to the admin news templates
define('XS_TPL_PATH', '../../templates/common/xs_mod/tpl_news/');

// check if ESM is installed
if(empty($template->xs_version) || $template->xs_version < 6)
{
	message_die(GENERAL_ERROR, 'One of the following is probably true:<br /><br />1. eXtreme Styles mod is not installed<br />2. you forgot to upload includes/template.php<br />3. Your using an old version of eXtreme Styles mod');
}

// Query Config
$sql = "SELECT * FROM " . XS_NEWS_CONFIG_TABLE;
if( !($result = $db->sql_query($sql, false, 'xs_config_')) )
{
	message_die(CRITICAL_ERROR, 'Could not query XS News config information', '', __LINE__, __FILE__, $sql);
}
while ( $row = $db->sql_fetchrow($result) )
{
	$xs_news_config[$row['config_name']] = $row['config_value'];
}

// load the admin language file
include($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_xs_news.' . $phpEx);

//
// Mode setting
//
if( isset($_POST['mode']) || isset($_GET['mode']) )
{
	$mode = ( isset($_POST['mode']) ) ? $_POST['mode'] : $_GET['mode'];
	$mode = htmlspecialchars($mode);
}
else
{
	$mode = "";
}

if ( isset($_POST['cancel']) )
{
	$mode = '';
}

if( isset($_POST['addxml']) )
{
	$mode = ( isset($_POST['addxml']) ) ? "addxml" : "";
}

$confirm = ( isset($_POST['confirm']) ) ? true : 0;

if( !empty($mode) )
{

	switch($mode)
	{
		case 'addxml':
		case 'editxml':
			//
			// Show form to create/modify a news ticker
			//
			if ($mode == 'editxml')
			{
				// $newmode determines if we are going to INSERT or UPDATE after posting?

				$l_title = $lang['n_xml_edit_header'];
				$newmode = 'modxml';
				$buttonvalue = $lang['Update'];

				$xml_id = intval($_GET['id']);

				$row = xsm_get_info('ticker', $xml_id);

				$xml_id = $row['xml_id'];
				$xml_title = $row['xml_title'];

				$xml_display_yes = ( $row['xml_show'] ) ? 'checked="checked"' : '';
				$xml_display_no = ( !$row['xml_show'] ) ? 'checked="checked"' : '';
				$xml_feed = xsm_unprepare_message($row['xml_feed']);
				$xml_width = $row['xml_width'];
				$xml_height = $row['xml_height'];
				$xml_fontsize = $row['xml_font'];
				$xml_speed = $row['xml_speed'];
				$xml_sd_left = ( !$row['xml_direction'] ) ? 'checked="checked"' : '';
				$xml_sd_right = ( $row['xml_direction'] ) ? 'checked="checked"' : '';
				$xml_is_feed_yes = ( $row['xml_is_feed'] ) ? 'checked="checked"' : '';
				$xml_is_feed_no = ( !$row['xml_is_feed'] ) ? 'checked="checked"' : '';

			}
			else
			{
				$l_title = $lang['n_xml_add_header'];
				$newmode = 'createxml';
				$buttonvalue = $lang['n_xml_create_item'];

				$xml_title = '';

				$xml_display_yes = 'checked="checked"';
				$xml_display_no = '';
				$xml_feed = '';
				$xml_width = '98%';
				$xml_height = '20';
				$xml_fontsize = '0';
				$xml_speed = '3';
				$xml_sd_left = 'checked="checked"';
				$xml_sd_right = '';
				$xml_is_feed_yes = 'checked="checked"';
				$xml_is_feed_no = '';

			}

			$template->set_filenames(array(
				'body' =>  XS_TPL_PATH . "news_ticker_edit_body.tpl"
			));

			$s_hidden_fields = '<input type="hidden" name="mode" value="' . $newmode .'" />';
			$s_hidden_fields .= '<input type="hidden" name="id" value="' . $xml_id . '" />';

			$template->assign_vars(array(
				'S_FORUM_ACTION' => append_sid("admin_xs_news_xml.$phpEx"),
				'S_HIDDEN_FIELDS' => $s_hidden_fields,
				'S_SUBMIT_VALUE' => $buttonvalue,

				'L_PAGE_TITLE' => ( $newmode == 'modxml' ) ? $lang['n_xml_edit_page_title'] : $lang['n_xml_add_page_title'],
				'L_PAGE_TITLE_EXPLAIN' => ( $newmode == 'modxml' ) ? $lang['n_xml_edit_page_title_explain'] : $lang['n_xml_add_page_title_explain'],

				'L_XS_NEWS_TICKER_SETTINGS' => $l_title,

				'L_XS_NEWS_TICKER_TITLE' => $lang['xs_news_ticker_title'],
				'L_XS_NEWS_TICKER_TITLE_EXPLAIN' => $lang['xs_news_ticker_title_explain'],
				'L_XS_NEWS_TICKER_SHOW' => $lang['xs_news_ticker_show'],
				'L_XS_NEWS_TICKER_FEED' => $lang['xs_news_ticker_feed'],
				'L_XS_NEWS_TICKER_FEED_EXPLAIN' => $lang['xs_news_ticker_feed_explain'],
				'L_XS_NEWS_TICKER_IS_FEED' => $lang['xs_news_ticker_is_feed'],
				'L_XS_NEWS_TICKER_IS_FEED_EXPLAIN' => $lang['xs_news_ticker_is_feed_explain'],
				'L_XS_NEWS_TICKER_WH' => $lang['xs_news_ticker_wh'],
				'L_XS_NEWS_TICKER_WH_EXPLAIN' => $lang['xs_news_ticker_wh_explain'],
				'L_XS_NEWS_TICKER_FONTSIZE' => $lang['xs_news_ticker_fontsize'],
				'L_XS_NEWS_TICKER_FONTSIZE_EXPLAIN' => $lang['xs_news_ticker_fontsize_explain'],
				'L_XS_NEWS_TICKER_SS' => $lang['xs_news_ticker_ss'],
				'L_XS_NEWS_TICKER_SS_EXPLAIN' => $lang['xs_news_ticker_ss_explain'],
				'L_XS_NEWS_TICKER_SD' => $lang['xs_news_ticker_sd'],
				'L_XS_LEFT' => $lang['xs_news_left'],
				'L_XS_RIGHT' => $lang['xs_news_right'],

				'XS_NEWS_TICKER_TITLE' => $xml_title,
				'XS_SHOWTICKER_YES' => $xml_display_yes,
				'XS_SHOWTICKER_NO' => $xml_display_no,
				'XS_NEWS_TICKER_FEED' => $xml_feed,
				'XS_NEWS_TICKER_WIDTH' => $xml_width,
				'XS_NEWS_TICKER_HEIGHT' => $xml_height,
				'XS_NEWS_TICKER_FONTSIZE' => $xml_fontsize,
				'XS_NEWS_TICKER_SS' => $xml_speed,
				'XS_NEWS_TICKER_SD_LEFT' => $xml_sd_left,
				'XS_NEWS_TICKER_SD_RIGHT' => $xml_sd_right,
				'XS_NEWS_ISFEED_YES' => $xml_is_feed_yes,
				'XS_NEWS_ISFEED_NO' => $xml_is_feed_no
			));

			$template->pparse('body');
			break;

		case 'createxml':
			//
			// Create a new news ticker in the DB
			//
			if( trim($_POST['xml_feed']) == "" )
			{
				$message = $lang['n_xml_create_item_null'] . '<br /><br />' . sprintf($lang['n_xml_click_return_newslist'], "<a href=\"" . append_sid("admin_xs_news_xml.$phpEx") . "\">", "</a>") . '<br /><br />' . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid('index.' . $phpEx . '?pane=right') . "\">", "</a>");
				message_die(GENERAL_MESSAGE, $message);
			}

			$xml_feed = xsm_prepare_message(trim($_POST['xml_feed']));

			$sql = "SELECT MAX(xml_id) AS max_id
				FROM " . XS_NEWS_XML_TABLE;
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't get next ID number from News Ticker table", "", __LINE__, __FILE__, $sql);
			}
			$row = $db->sql_fetchrow($result);

			$max_id = $row['max_id'];
			$next_id = $max_id + 1;

			$sql = "INSERT INTO " . XS_NEWS_XML_TABLE . " (xml_id, xml_title, xml_show, xml_feed, xml_is_feed, xml_width, xml_height, xml_font, xml_speed, xml_direction" . ")
				VALUES ('" . $next_id . "', '" . str_replace("\'", "''", $_POST['xml_title']) . "', '" . intval($_POST['xml_show']) . "', '" . str_replace("\'", "''", $xml_feed) . "', '" . intval($_POST['xml_is_feed']) . "', '" . str_replace("\'", "''", $_POST['xml_width']) . "', '" . str_replace("\'", "''", $_POST['xml_height']) . "', '" . str_replace("\'", "''", $_POST['xml_font']) . "', '" . str_replace("\'", "''", $_POST['xml_speed']) . "', '" . intval($_POST['xml_direction']) . "')";
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't insert row in news table", "", __LINE__, __FILE__, $sql);
			}

			$message = $lang['n_xml_news_item_added'] . '<br /><br />' . sprintf($lang['n_xml_click_return_newslist'], "<a href=\"" . append_sid("admin_xs_news_xml.$phpEx") . "\">", "</a>") . '<br /><br />' . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid('index.' . $phpEx . '?pane=right') . "\">", "</a>");

			message_die(GENERAL_MESSAGE, $message);

			break;

		case 'modxml':
			//
			// Modify a news ticker in the DB
			//
			$xml_feed = xsm_prepare_message(trim($_POST['xml_feed']));

			$sql = "UPDATE " . XS_NEWS_XML_TABLE . "
				SET xml_title = '" . str_replace("\'", "''", $_POST['xml_title']) . "', xml_show = " . intval($_POST['xml_show']) . ", xml_feed = '" . str_replace("\'", "''", $xml_feed) . "', xml_is_feed = '" . intval($_POST['xml_is_feed']) . "', xml_width = '" . str_replace("\'", "''", $_POST['xml_width']). "', xml_height = '" . str_replace("\'", "''", $_POST['xml_height']). "', xml_font = '" . str_replace("\'", "''", $_POST['xml_font']). "', xml_speed = '" . str_replace("\'", "''", $_POST['xml_speed']). "', xml_direction = " . intval($_POST['xml_direction']). "
				WHERE xml_id = " . intval($_POST['id']);
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't update XML Feed information", "", __LINE__, __FILE__, $sql);
			}

			$message = $lang['n_xml_news_updated'] . '<br /><br />' . sprintf($lang['n_xml_click_return_newslist'], "<a href=\"" . append_sid("admin_xs_news_xml.$phpEx") . "\">", "</a>") . '<br /><br />' . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid('index.' . $phpEx . '?pane=right') . "\">", "</a>");

			message_die(GENERAL_MESSAGE, $message);

			break;

		case 'deletexml':
			//
			// Show form to delete a news item
			//
			$xml_id = intval($_GET['id']);

			$buttonvalue = $lang['Delete'];

			$newmode = 'deletexml';

			$xml_info = xsm_get_info('ticker', $xml_id);
			$name = $news_info['n_news_item'];

			if( $confirm )
			{
				$sql = "DELETE FROM " . XS_NEWS_XML_TABLE . "
					WHERE xml_id = $xml_id";
				if( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't delete XML Feed", "", __LINE__, __FILE__, $sql);
				}

				$message = $lang['n_news_updated'] . '<br /><br />' . sprintf($lang['n_click_return_newslist'], "<a href=\"" . append_sid("admin_xs_news_xml.$phpEx") . "\">", "</a>") . '<br /><br />' . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid('index.' . $phpEx . '?pane=right') . "\">", "</a>");

				message_die(GENERAL_MESSAGE, $message);
			}
			else
			{
				//
				// Set template files
				//
				$template->set_filenames(array(
					'confirm' =>  XS_TPL_PATH . 'news_confirm_body.tpl')
				);

				$s_hidden_fields = '<input type="hidden" name="mode" value="' . $newmode . '" /><input type="hidden" name="id" value="' . $news_id . '" />';

				$template->assign_vars(array(
					'MESSAGE_TITLE' => $lang['Confirm'],
					'MESSAGE_TEXT' => sprintf($lang['n_confirm_delete_news'], $name),

					'L_YES' => $lang['Yes'],
					'L_NO' => $lang['No'],

					'S_CONFIRM_ACTION' => append_sid("admin_xs_news_xml.$phpEx?id=$xml_id"),
					'S_HIDDEN_FIELDS' => $s_hidden_fields
				));

				$template->pparse('confirm');
			}
			break;

		default:
			message_die(GENERAL_MESSAGE, $lang['No_mode']);
			break;
	}

	if ($show_index != true)
	{
		include('./page_footer_admin.' . $phpEx);
		exit;
	}
}

//
// Start page proper
//
$template->set_filenames(array('body' => XS_TPL_PATH . 'news_ticker_list_body.tpl'));

$master_switch = ( ($xs_news_config['xs_show_ticker'] == 1) ? true : false);

$template->assign_vars(array(
	'S_FORUM_ACTION' => append_sid("admin_xs_news_xml.$phpEx"),
	'L_MENU_TITLE' => $lang['n_xml_title'],
	'L_MENU_EXPLAIN' => $lang['n_xml_title_explain'],
	'L_MENU_EXPLAINS' => $lang['n_xml_title_explain_0'],
	'L_MENU_SETTINGS' => $lang['n_xml_sub_title'],
	'L_CREATE_FEED' => $lang['n_xml_create_item'],
	'L_EDIT' => $lang['Edit'],
	'L_DELETE' => $lang['Delete'],
	'L_SHOW' => $lang['n_xml_show'],
	'L_TITLE' => $lang['n_xml_title'],
	'XS_PATH' => '../templates/common/xs_mod/',
	'L_MASTER_SWITCH' => sprintf($lang['n_xml_master_switch'], (($master_switch) ? 'On' : 'Off'), (($master_switch) ? $lang['n_xml_ms_will'] : $lang['n_xml_ms_not']) )
));

$sql = "SELECT xml_id, xml_title, xml_show FROM " . XS_NEWS_XML_TABLE . "
	ORDER BY xml_id ASC";
if( !$q_xml = $db->sql_query($sql) )
{
	message_die(GENERAL_ERROR, "Could not query xml news ticker item list", "", __LINE__, __FILE__, $sql);
}

if( $total_xml = $db->sql_numrows($q_xml) )
{
	$xml_rows = $db->sql_fetchrowset($q_xml);

	for($i = 0; $i < $total_xml; $i++)
	{
		$xml_id = $xml_rows[$i]['xml_id'];
		$xml_title = $xml_rows[$i]['xml_title'];
		$xml_show = $xml_rows[$i]['xml_show'];

		$show_item = (($xml_show) ? '[ <span style="color: green">' . $lang['Yes'] . '</span> ]' : '[ <span style="color: red">' . $lang['No'] . '</span> ]');

		$template->assign_block_vars("xml_feed", array(
			'XML_ID' => $block_id,
			'XML_TITLE' => $xml_title,
			'XML_FEED_DISPLAY' => $show_item,

			'U_XML_EDIT' => append_sid("admin_xs_news_xml.$phpEx?mode=editxml&amp;id=$xml_id"),
			'U_XML_DELETE' => append_sid("admin_xs_news_xml.$phpEx?mode=deletexml&amp;id=$xml_id")
		));

	}

}
elseif($db->sql_numrows($q_xml) == 0)
{
	$template->assign_block_vars('xml_no_feeds', array(
		'XML_TITLE' => $lang['n_xml_no_feeds']
	));
}

$template->pparse('body');

include('./page_footer_admin.' . $phpEx);

?>