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
* OOHOO < webdev@phpbb-tw.net >
* Stefan2k1 and ddonker from www.portedmods.com
* CRLin from http://mail.dhjh.tcc.edu.tw/~gzqbyr/
*
*/

define('IN_ICYPHOENIX', true);

// Admin Panel
if(!empty($setmodules))
{
	$filename = basename(__FILE__);
	$module['2100_Links']['120_Add_new'] = $filename . '?mode=add';
	$module['2100_Links']['130_Link_Manage'] = $filename . '?mode=view';

	return;
}

// Load default header
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('pagestart.' . PHP_EXT);

// Check link_id
$link_id = request_var('link_id', '');
$mode = request_var('mode', '');
$action = request_var('action', '');

// Set template
$template->set_filenames(array('body' => ($mode == 'view' ? ADM_TPL . 'admin_links_body.tpl' : ADM_TPL . 'admin_links_edit_body.tpl')));

// Grab link categories
$sql = "SELECT cat_id, cat_title FROM " . LINK_CATEGORIES_TABLE . " ORDER BY cat_order";
$result = $db->sql_query($sql);

while($row = $db->sql_fetchrow($result))
{
	$link_categories[$row['cat_id']] = $row['cat_title'];
}

$template->assign_vars(array(
	'L_LINK_BASIC_SETTING' => $lang['Link_basic_setting'],
	'L_LINK_ADV_SETTING' => $lang['Link_adv_setting'],
	'L_LINK_TITLE' => $lang['Link_title'],
	'L_LINK_DESC' => $lang['Link_desc'],
	'L_LINK_URL' => $lang['Link_url'],
	'L_LINK_LOGO_SRC' => $lang['Link_logo_src'],
	'L_LINK_USER' => $lang['Username'],
	'L_LINK_JOINED' => $lang['Joined'],
	'L_LINK_USER_IP' => $lang['IP_Address'],
	'L_LINK_CATEGORY' => $lang['Link_category'],
	'L_LINK_ACTIVE' => $lang['Link_active'],
	'L_YES' => $lang['ON'],
	'L_NO' => $lang['OFF'],
	'L_LINK_HITS' => $lang['link_hits'],
	'L_PREVIEW' => $lang['Preview']
	)
);


// Switch mode
switch ($mode)
{
	case 'add':
		// Link categories dropdown list
		foreach($link_categories as $cat_id => $cat_title)
		{
			$link_cat_option .= "<option value=\"$cat_id\">$cat_title</option>";
		}

		$template->assign_vars(array(
			'PAGE_TITLE' => $lang['Add_link'],
			'PAGE_EXPLAIN' => $lang['Add_link_explain'],
			'PAGE_ACTION' => append_sid ('admin_links.' . PHP_EXT . '?mode=update&action=add'),
			'LINK_ACTIVE_YES' => 'checked="checked"',
			'LINK_CAT_OPTION' => $link_cat_option,
			'L_SUBMIT' => $lang['Add_link']
			)
		);
		break;

	case 'view':
		$linkspp = 50;

		$start = request_var('start', 0);
		$start = ($start < 0) ? 0 : $start;

		$search_keywords = request_var('search_keywords', '', true);

		$template->assign_vars(array(
			'PAGE_TITLE' => $lang['Links'],
			'PAGE_EXPLAIN' => $lang['Links_explain'],
			'PAGE_ACTION' => append_sid ('admin_links.' . PHP_EXT . '?mode=view'),
			'L_SEARCH_SITE_TITLE' => $lang['Search_site_title'],
			'U_LINK' => 'admin_links.' . PHP_EXT,
			'L_EDIT' => $lang['Edit_link'],
			'L_DELETE' => $lang['Delete_link'],
			'L_SUBMIT' => $lang['Submit']
			)
		);

		$sql = "SELECT l.*
				FROM " . LINKS_TABLE . " l";
		if ($search_keywords)
		{
			$sql .= " AND (l.link_title LIKE '%" . $db->sql_escape($search_keywords) . "%' OR l.link_desc LIKE '%" . $db->sql_escape($search_keywords) . "%') ORDER BY l.link_id DESC LIMIT $start, $linkspp";
		}
		else
		{
			$sql .= " ORDER BY l.link_id DESC LIMIT $start, $linkspp";
		}

		$result = $db->sql_query($sql);

		if ($row = $db->sql_fetchrow($result))
		{
			$i = 0;
			do
			{
				$row_class = !($i % 2) ? $theme['td_class1'] : $theme['td_class2'];
				$link_id = $row['link_id'];
				$link_id .= '&sid=' . $user->data['session_id'] . '';
				$user_id = $row['user_id'];

				$template->assign_block_vars('linkrow', array(
					'ROW_CLASS' => $row_class,
					'LINK_ID' => $link_id,
					'LINK_TITLE' => $row['link_title'],
					'LINK_URL' => $row['link_url'],
					'LINK_CATEGORY' => $link_categories[$row['link_category']],
					'U_LINK_USER' => colorize_username($user_id),
					'LINK_JOINED' => create_date($lang['DATE_FORMAT'], $row['link_joined'], $config['board_timezone']),
					'LINK_USER_IP' => $row['user_ip'],
					'LINK_DESC' => $row['link_desc'],
					'LINK_ACTIVE' => '<span class="text_' . ($row['link_active'] ? 'green">' . $lang['ON'] : 'red">' . $lang['OFF']) . '</span>',
					'LINK_HITS' => $row['link_hits']
					)
				);
				$i++;
			}
			while ($row = $db->sql_fetchrow($result));
		}

		// Pagination
		$sql = "SELECT count(*) AS total
			FROM " . LINKS_TABLE . "
			WHERE link_active = 1";
		if ($search_keywords)
		{
			$sql .= " AND (link_title LIKE '%" . $db->sql_escape($search_keywords) . "%' OR link_desc LIKE '%" . $db->sql_escape($search_keywords) . "%')";
			$link_search = $lang['Search_site'] . "&nbsp;&raquo;&nbsp;" . $search_keywords;
			$template->assign_vars(array(
				'L_SEARCH_SITE' => $link_search
				)
			);
		}

		$result = $db->sql_query($sql);

		$total_links = '50';
		$pagination = '&nbsp;';
		if ($row = $db->sql_fetchrow($result))
		{
			$total_links = $row['total'];
			$pagination = generate_pagination('admin_links.' . PHP_EXT . '?mode=' . $mode . '&amp;search_keywords=' . urlencode($search_keywords), $total_links, $linkspp, $start) . '&nbsp;';
		}

		$template->assign_vars(array(
			'PAGINATION' => $pagination,
			'PAGE_NUMBER' => sprintf($lang['Page_of'], (floor($start / $linkspp) + 1), ceil($total_links / $linkspp)),
			'L_GOTO_PAGE' => $lang['Goto_page']
			)
		);
		break;
	case 'edit':
	case 'delete':
		$sql = "SELECT * FROM " . LINKS_TABLE . " WHERE link_id = '$link_id'";
		$result = $db->sql_query($sql);

		if ($row = $db->sql_fetchrow($result))
		{
			// Link categories dropdown list
			foreach($link_categories as $cat_id => $cat_title)
			{
				$link_cat_option .= "<option value=\"$cat_id\"" . ($cat_id == $row['link_category'] ? " selected" : "") . ">$cat_title</option>";
			}

			$link_logo_src = $row['link_logo_src'];
			if (empty($link_logo_src)) $link_logo_src = 'images/links/no_logo88a.gif';

			$template->assign_vars(array(
				'PAGE_TITLE' => ($mode == 'edit' ? $lang['Edit_link'] : $lang['Delete_link']),
				'PAGE_EXPLAIN' => ($mode == 'edit' ? $lang['Edit_link_explain'] . (' <a href="' . append_sid('admin_links.' . PHP_EXT . '?mode=delete&amp;link_id=' . $link_id) . '">' . $lang['Delete_link'] . '</a>') : $lang['Delete_link_explain'] . (' <a href="' . append_sid('admin_links.' . PHP_EXT . '?mode=edit&amp;link_id=' . $link_id) . '">' . $lang['Edit_link'] . '</a>')),
				'PAGE_ACTION' => ($mode == 'edit' ? 'admin_links.' . PHP_EXT . '?mode=update&amp;action=modify&amp;link_id=' . $link_id . '&amp;sid=' . $user->data['session_id'] : 'admin_links.' . PHP_EXT . '?mode=update&amp;action=delete&amp;link_id=' . $link_id . '&amp;sid=' . $user->data['session_id']),

				'L_SUBMIT' => ($mode == 'edit' ? $lang['Link_update'] : $lang['Link_delete']),

				'LINK_ID' => $link_id,
				'LINK_TITLE' => $row['link_title'],
				'LINK_DESC' => $row['link_desc'],
				'LINK_URL' => $row['link_url'],
				'LINK_LOGO_SRC' => $row['link_logo_src'],
				'LINK_LOGO_IMG' => '<img src="' . (substr($link_logo_src, 0, 4) == 'http' ? $link_logo_src : '../' . $link_logo_src) . '" vspace="10" hspace="10" />',

				'LINK_ACTIVE_YES' => ($row['link_active'] ? 'checked="checked"' : ''),
				'LINK_ACTIVE_NO' => (!$row['link_active'] ? 'checked="checked"' : ''),

				'LINK_CAT_OPTION' => $link_cat_option
				)
			);
		}
		break;
	case 'update':
		$link_title = request_post_var('link_title', '', true);
		$link_desc = request_post_var('link_desc', '', true);
		$link_category = request_post_var('link_category', 0);
		$link_url = request_post_var('link_url', '', true);
		$link_logo_src = request_post_var('link_logo_src', '', true);
		$link_active = (!empty($_POST['link_active'])) ? 1 : 0;

		$link_joined = time();
		$user_id = $user->data['user_id'];

		switch ($action)
		{
			case 'add':
				if($link_title && $link_desc && $link_category && $link_url)
				{
					$sql = "INSERT INTO " . LINKS_TABLE . " (link_title, link_desc, link_category, link_url, link_logo_src, link_joined, link_active, user_id , user_ip)
						VALUES ('" . $db->sql_escape($link_title) . "', '" . $db->sql_escape($link_desc) . "', '$link_category', '" . $db->sql_escape($link_url) . "', '" . $db->sql_escape($link_logo_src) . "', '$link_joined', '$link_active', '$user_id ', '$user_ip')";
					$db->sql_query($sql);
					$message = $lang['Link_admin_add_success'];
					$action_success = true;
				}
				else
				{
					$message = $lang['Link_incomplete'];
				}
				break;
			case 'modify':
				if($link_id && $link_title && $link_desc && $link_category && $link_url)
				{

					$sql = "UPDATE " . LINKS_TABLE . " SET link_title = '" . $db->sql_escape($link_title) . "', link_desc = '" . $db->sql_escape($link_desc) . "', link_url = '" . $db->sql_escape($link_url) . "', link_logo_src = '" . $db->sql_escape($link_logo_src) . "', link_category = '$link_category', link_active = '$link_active' WHERE link_id = '$link_id'";
					$db->sql_query($sql);
					$message = $lang['Link_admin_update_success'];
					$action_success = true;
				}
				else
				{
					$message = $lang['Link_incomplete'];
				}
				break;
			case 'delete':

				if($link_id)
				{
					$sql = "DELETE FROM " . LINKS_TABLE . " WHERE link_id = '$link_id'";
					$db->sql_query($sql);
					$message = $lang['Link_admin_delete_success'];
					$action_success = true;
				}
				else
				{
					$message = $lang['Link_admin_delete_fail'];
				}
				break;
		} // Close Update Switch

		if(!$action_success)
		{
			$message .= '<br /><br />' . sprintf($lang['Click_return_lastpage'], '<a href="' . $HTTP_REFERER . '">', '</a>');
		}

		$message .= '<br /><br />' . sprintf($lang['Click_return_admin_links'], '<a href="' . append_sid('admin_links.' . PHP_EXT . '?mode=view') . '">', '</a>');

		$db->clear_cache('links_');
		message_die(GENERAL_MESSAGE, $message);

		break;
}

$template->pparse('body');

// Page Footer
include('page_footer_admin.' . PHP_EXT);

?>