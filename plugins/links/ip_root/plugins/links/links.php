<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

$links_config = get_links_config(true);

$start = request_var('start', 0);
$start = ($start < 0) ? 0 : $start;

$cat = request_var('cat', 0);
$cat = ($cat < 1) ? 1 : $cat;

$link_id = request_var('link_id', 0);
$link_id = ($link_id < 0) ? 0 : $link_id;

$t = request_var('t', 'index', true);

$search_keywords = request_var('search_keywords', '', true);

// Count and forward
if(($_GET['action'] == 'go') && !empty($link_id))
{
	$sql = "SELECT link_id, link_url, last_user_ip
		FROM " . LINKS_TABLE . "
		WHERE link_id = '" . $link_id . "'
		AND link_active = 1";
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	if($link_url = $row['link_url'])
	{
		if($user_ip != $row['last_user_ip'])
		{
			// Update
			$sql = "UPDATE " . LINKS_TABLE . "
				SET link_hits = link_hits + 1, last_user_ip = '" . $user_ip . "'
				WHERE link_id = '" . $link_id . "'";
			$result = $db->sql_query($sql);
		}

		// Forward to website
		// header("Location: $link_url");
		echo '<script type="text/javascript">location.replace("' . $link_url . '")</script>';
		exit;
	}
}

// Grab link categories
$sql = "SELECT cat_id, cat_title FROM " . LINK_CATEGORIES_TABLE . " WHERE cat_id = '" . $cat . "'";
$result = $db->sql_query($sql);
$row = $db->sql_fetchrow($result);
$current_cat_title = $row['cat_title'];
$db->sql_freeresult($result);

// Output the basic page
$nav_server_url = create_server_url();
$breadcrumbs['address'] = $lang['Nav_Separator'] . '<a href="' . $nav_server_url . append_sid(THIS_FILE) . '"' . (($t == 'sub_pages') ? '' : ' class="nav-current"') . '>' . $lang['Site_links'] . '</a>' . (($t == 'sub_pages') ? ($lang['Nav_Separator'] . '<a href="' . $nav_server_url . append_sid(THIS_FILE . '?cat=' . $cat) . '" class="nav-current"' . '>' . $current_cat_title . '</a>') : '');
$breadcrumbs['bottom_right_links'] = '<a href="' . append_sid(THIS_FILE . '?t=search') . '">' . $lang['Search_site'] . '</a>&nbsp;' . MENU_SEP_CHAR . '&nbsp;<a href="' . append_sid(THIS_FILE . '?t=pop') . '">' . $lang['Descend_by_hits'] . '</a>&nbsp;' . MENU_SEP_CHAR . '&nbsp;<a href="' . append_sid(THIS_FILE . '?t=new') . '">' . $lang['Descend_by_joindate'] . '</a>';

switch($t)
{
	case 'pop':
	case 'new':
		$tmp = 'links_popnew.tpl';
		break;
	case 'search':
		$tmp = 'links_search.tpl';
		break;
	case 'sub_pages':
		$tmp = 'links_body.tpl';
		break;
	default:
		$tmp = 'links_index.tpl';
}

$template_to_parse = $class_plugins->get_tpl_file(LINKS_TPL_PATH, $tmp);

if($links_config['lock_submit_site'] == 0)
{
	// display submit site
	$template->assign_block_vars('lock', array());

	if(!$user->data['session_logged_in'])
	{
		$template->assign_block_vars('lock.logout', array());
	}

	if($user->data['session_logged_in'])
	{
		$template->assign_block_vars('lock.submit', array());
	}
}

if($links_config['allow_no_logo'])
{
	$tmp = $lang['Link_logo_src'];
}
else
{
	$tmp = $lang['Link_logo_src1'];
}

$template->assign_vars(array(
	'U_LINK_REG' => append_sid('link_register.' . PHP_EXT),
	'L_LINK_REGISTER_RULE' => $lang['Link_register_rule'],
	'L_LINK_REGISTER_GUEST_RULE' => $lang['Link_register_guest_rule'],
	'L_LINK_TITLE' => $lang['Link_title'],
	'L_LINK_DESC' => $lang['Link_desc'],
	'L_LINK_URL' => $lang['Link_url'],
	'L_LINK_LOGO_SRC' => $tmp,
	'L_PREVIEW' => $lang['Links_Preview'],
	'L_LINK_CATEGORY' => $lang['Link_category'],
	'L_PLEASE_ENTER_YOUR' => $lang['Please_enter_your'],
	'L_LINK_REGISTER' => $lang['Link_register'],
	'L_SITE_LINKS' => $lang['Site_links'],
	'L_LINK_US' => $lang['Link_us'] . $config['sitename'],
	'L_LINK_US_EXPLAIN' => sprintf($lang['Link_us_explain'], $config['sitename']),
	'L_SUBMIT' => $lang['Submit'],
	'U_SITE_LINKS' => append_sid(THIS_FILE),
	'L_LINK_CATEGORY' => $lang['Link_category'],
	'U_SITE_SEARCH' => append_sid(THIS_FILE . '?t=search'),
	'U_SITE_TOP' => append_sid(THIS_FILE . '?t=pop'),
	'U_SITE_NEW' => append_sid(THIS_FILE . '?t=new'),
	'U_SITE_LOGO' => $links_config['site_logo'],
	'LINK_US_SYNTAX' => str_replace(' ', '&nbsp;', sprintf(htmlentities($lang['Link_us_syntax'], ENT_QUOTES), $links_config['site_url'], $links_config['site_logo'], $links_config['width'], $links_config['height'], htmlspecialchars(str_replace('"', '', $config['sitename'])))),
	'LINKS_HOME' => $lang['Links_home'],
	'L_SEARCH_SITE' => $lang['Search_site'],
	'L_DESCEND_BY_HITS' => $lang['Descend_by_hits'],
	'L_DESCEND_BY_JOINDATE' => $lang['Descend_by_joindate'],
	'L_LINK_JOINED' => $lang['Joined'],
	'L_LINK_HITS' => $lang['link_hits'],
	'L_REMEMBER_ME' => $lang['Remember_Me'],
	)
);

if (($t == 'pop') || ($t == 'new'))
{
	if ($t == 'pop')
	{
		$template->assign_vars(array(
			'L_LINK_TITLE1' => $lang['Descend_by_hits']
			)
		);
	}
	else
	{
		$template->assign_vars(array(
			'L_LINK_TITLE1' => $lang['Descend_by_joindate']
			)
		);
	}

	// Grab link categories
	$sql = "SELECT cat_id, cat_title FROM " . LINK_CATEGORIES_TABLE . " ORDER BY cat_order ASC";
	$result = $db->sql_query($sql);

	while($row = $db->sql_fetchrow($result))
	{
		$link_categories[$row['cat_id']] = $row['cat_title'];
	}
	$db->sql_freeresult($result);

	// Grab links
	$sql = "SELECT * FROM " . LINKS_TABLE . "
		WHERE link_active = 1
		ORDER BY link_hits DESC, link_id DESC
		LIMIT $start, " . $db->sql_escape($links_config['linkspp']);
	if ($t == 'new')
	{
		$sql = "SELECT * FROM " . LINKS_TABLE . "
			WHERE link_active = 1
			ORDER BY link_joined DESC, link_id DESC
			LIMIT $start, " . $db->sql_escape($links_config['linkspp']);
	}
	$result = $db->sql_query($sql);

	if ($row = $db->sql_fetchrow($result))
	{
		$row_class = '';
		$i = 0;
		do
		{
			//if (empty($row['link_logo_src'])) $row['link_logo_src'] = 'images/links/no_logo88a.gif';
			if ($links_config['display_links_logo'])
			{
				if ($row['link_logo_src'])
				{
					$tmp = '<a href="' . append_sid(THIS_FILE . '?action=go&amp;link_id=' . $row['link_id']) . '" alt="' . $row['link_desc'] . '" target="_blank"><img src="' . $row['link_logo_src'] . '" alt="' . $row['link_title'] . '" width="' . $links_config['width'] . '" height="' . $links_config['height'] . '" border="0" hspace="1" /></a>';
				}
				else
				{
					$tmp = $lang['No_Logo_img'];
				}
			}
			else
			{
				$tmp = $lang['No_Display_Links_Logo'];
			}

			$row_class = ip_zebra_rows($row_class);
			$template->assign_block_vars('linkrow', array(
				'ROW_CLASS' => $row_class,
				'LINK_URL' => append_sid(THIS_FILE . '?action=go&amp;link_id=' . $row['link_id']),
				'LINK_TITLE' => $row['link_title'],
				'LINK_DESC' => $row['link_desc'],
				'LINK_LOGO_SRC' => $row['link_logo_src'],
				'LINK_LOGO' => $tmp,
				'LINK_CATEGORY' => $link_categories[$row['link_category']],
				'LINK_JOINED' => create_date_ip($lang['DATE_FORMAT'], $row['link_joined'], $config['board_timezone']),
				'LINK_HITS' => $row['link_hits']
				)
			);
			$i++;
		}
		while ($row = $db->sql_fetchrow($result));
		$db->sql_freeresult($result);
	}

	// Pagination
	$sql = "SELECT count(*) AS total
		FROM " . LINKS_TABLE . "
		WHERE link_active = 1";
	$result = $db->sql_query($sql);

	$total_links = 10;
	$pagination = '&nbsp;';
	if ($row = $db->sql_fetchrow($result))
	{
		$total_links = $row['total'];
		$pagination = generate_pagination(THIS_FILE . '?t=' . $t, $total_links, $links_config['linkspp'], $start);
	}
	$db->sql_freeresult($result);

	// Link categories dropdown list
	foreach($link_categories as $cat_id => $cat_title)
	{
		$link_cat_option .= '<option value="' . $cat_id . '">' . $cat_title . '</option>';
	}


	$template->assign_vars(array(
		'PAGINATION' => $pagination,
		'PAGE_NUMBER' => sprintf($lang['Page_of'], (floor($start / $links_config['linkspp']) + 1), ceil($total_links / $links_config['linkspp'])),
		'L_GOTO_PAGE' => $lang['Goto_page'],
		'LINK_CAT_OPTION' => $link_cat_option
		)
	);
}

if ($t == 'sub_pages')
{
	$mode = request_var('mode', 'link_joined');
	$mode_types_text = array($lang['Joined'], $lang['link_hits'], $lang['Link_title'], $lang['Link_desc']);
	$mode_types = array('link_joined', 'link_hits', 'link_title', 'link_desc');
	$mode = (in_array($mode, $mode_types) ? $mode : $mode_types[0]);

	$sort_order = request_var('order', 'DESC');
	$sort_order = check_var_value($sort_order, array('DESC', 'ASC'));

	$select_sort_mode = '<select name="mode">';
	for($i = 0; $i < sizeof($mode_types_text); $i++)
	{
		$selected = ($mode == $mode_types[$i]) ? ' selected="selected"' : '';
		$select_sort_mode .= '<option value="' . $mode_types[$i] . '"' . $selected . '>' . $mode_types_text[$i] . '</option>';
	}
	$select_sort_mode .= '</select>';

	$select_sort_order = '<select name="order">';
	if($sort_order == 'ASC')
	{
		$select_sort_order .= '<option value="ASC" selected="selected">' . $lang['Sort_Ascending'] . '</option><option value="DESC">' . $lang['Sort_Descending'] . '</option>';
	}
	else
	{
		$select_sort_order .= '<option value="ASC">' . $lang['Sort_Ascending'] . '</option><option value="DESC" selected="selected">' . $lang['Sort_Descending'] . '</option>';
	}
	$select_sort_order .= '</select>';

	$select_sort_order = $select_sort_order . '<input type="hidden" name="t" value="' . $t . '" />';
	$select_sort_order = $select_sort_order . '<input type="hidden" name="cat" value="' . $cat . '" />';

	$template->assign_vars(array(
		'L_SEARCH_SITE' => $lang['Search_site'],
		'L_SELECT_SORT_METHOD' => $lang['Select_sort_method'],
		'L_ORDER' => $lang['Order'],
		'L_SORT' =>  $lang['Sort'],
		//'L_SUBMIT' => 'Submit',
		'U_SITE_LINKS_CAT' => append_sid(THIS_FILE . '?t=' . $t . '&amp;cat=' . $cat),
		'S_MODE_SELECT' => $select_sort_mode,
		'S_ORDER_SELECT' => $select_sort_order
		)
	);

	// Grab link categories
	$sql = "SELECT cat_id, cat_title FROM " . LINK_CATEGORIES_TABLE . " WHERE cat_id = '" . $cat . "'";
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$link_categories[$row['cat_id']] = $row['cat_title'];
	$template->assign_vars(array(
		'LINK_CATEGORY' => $row['cat_title']
		)
	);
	$db->sql_freeresult($result);

	// Grab links
	$sql = "SELECT * FROM " . LINKS_TABLE . "
			WHERE link_active = 1
				AND link_category = '" . $cat . "'
			ORDER BY $mode $sort_order, link_id DESC
			LIMIT $start, " . $db->sql_escape($links_config['linkspp']);
	$result = $db->sql_query($sql);

	if ($row = $db->sql_fetchrow($result))
	{
		$row_class = '';
		$i = 0;
		do
		{
			//if (empty($row['link_logo_src'])) $row['link_logo_src'] = 'images/links/no_logo88a.gif';
			if ($links_config['display_links_logo'])
			{
				if ($row['link_logo_src'])
				{
					$tmp = '<a href="' . append_sid(THIS_FILE . '?action=go&amp;link_id=' . $row['link_id']) . '" alt="' . $row['link_desc'] . '" target="_blank"><img src="' . $row['link_logo_src'] . '" alt="' . $row['link_title'] . '" width="' . $links_config['width'] . '" height="' . $links_config['height'] . '" /></a>';
				}
				else
				{
					$tmp = $lang['No_Logo_img'];
				}
			}
			else
			{
				$tmp = $lang['No_Display_Links_Logo'];
			}

			$row_class = ip_zebra_rows($row_class);
			$template->assign_block_vars('linkrow', array(
				'ROW_CLASS' => $row_class,
				'LINK_URL' => append_sid(THIS_FILE . '?action=go&amp;link_id=' . $row['link_id']),
				'LINK_TITLE' => $row['link_title'],
				'LINK_DESC' => $row['link_desc'],
				'LINK_LOGO_SRC' => $row['link_logo_src'],
				'LINK_LOGO' => $tmp,
				'LINK_CATEGORY' => $link_categories[$row['link_category']],
				'LINK_JOINED' => create_date_ip($lang['DATE_FORMAT'], $row['link_joined'], $config['board_timezone']),
				'LINK_HITS' => $row['link_hits']
				)
			);
			$i++;
		}
		while ($row = $db->sql_fetchrow($result));
		$db->sql_freeresult($result);
	}

	// Pagination
	$sql = "SELECT count(*) AS total
		FROM " . LINKS_TABLE . "
		WHERE link_active = 1
			AND link_category = '" . $cat . "'";
	$result = $db->sql_query($sql);

	$pagination = '&nbsp;';
	$total_links = 10;
	if ($row = $db->sql_fetchrow($result))
	{
		$total_links = $row['total'];
		$pagination = generate_pagination(THIS_FILE . '?t=' . $t . '&amp;cat=' . $cat . '&amp;mode=' . $mode . '&amp;order=' . $sort_order, $total_links, $links_config['linkspp'], $start);
	}
	$db->sql_freeresult($result);

	// Link categories dropdown list
	foreach($link_categories as $cat_id => $cat_title)
	{
		$link_cat_option .= '<option value="' . $cat_id . '">' . $cat_title . '</option>';
	}

	$template->assign_vars(array(
		'PAGINATION' => $pagination,
		'PAGE_NUMBER' => sprintf($lang['Page_of'], (floor($start / $links_config['linkspp']) + 1), ceil($total_links / $links_config['linkspp'])),
		'L_GOTO_PAGE' => $lang['Goto_page'],
		'LINK_CAT_OPTION' => $link_cat_option
		)
	);
}

if ($t == 'search')
{
	if ($search_keywords)
	{
		$link_title = $lang['Search_site'] . '&nbsp;&raquo;&nbsp;' . $search_keywords;
		$template->assign_vars(array(
			'L_LINK_TITLE1' => $link_title,
			'L_SEARCH_SITE_TITLE' => $lang['Search_site_title']
			)
		);
	}
	else
	{
		$template->assign_vars(array(
			'L_LINK_TITLE1' => $lang['Search_site'],
			'L_SEARCH_SITE_TITLE' => $lang['Search_site_title']
			)
		);
		$start = 0;
	}

	// Grab link categories
	$sql = "SELECT cat_id, cat_title FROM " . LINK_CATEGORIES_TABLE . " ORDER BY cat_order ASC";
	$result = $db->sql_query($sql);

	while($row = $db->sql_fetchrow($result))
	{
		$link_categories[$row['cat_id']] = $row['cat_title'];
	}
	$db->sql_freeresult($result);

	// Grab links
	if ($search_keywords)
	{
		$sql = "SELECT * FROM " . LINKS_TABLE . "
			WHERE link_active = 1
				AND (link_title LIKE '%" . $db->sql_escape($search_keywords) . "%' OR link_desc LIKE '%" . $db->sql_escape($search_keywords) . "%')
			LIMIT $start, " . $db->sql_escape($links_config['linkspp']);
		$result = $db->sql_query($sql);

		if ($row = $db->sql_fetchrow($result))
		{
			$row_class = '';
			$i = 0;
			do
			{
				//if (empty($row['link_logo_src'])) $row['link_logo_src'] = 'images/links/no_logo88a.gif';
				if ($links_config['display_links_logo'])
				{
					if ($row['link_logo_src'])
					{
						$tmp = '<a href="' . append_sid(THIS_FILE . '?action=go&amp;link_id=' . $row['link_id']) . '" alt="' . $row['link_desc'] . '" target="_blank"><img src="' . $row['link_logo_src'] . '" alt="' . $row['link_title'] . '" width="' . $links_config['width'] . '" height="' . $links_config['height'] . '" /></a>';
					}
					else
					{
						$tmp = $lang['No_Logo_img'];
					}
				}
				else
				{
					$tmp = $lang['No_Display_Links_Logo'];
				}

				$row_class = ip_zebra_rows($row_class);
				$template->assign_block_vars('linkrow', array(
					'ROW_CLASS' => $row_class,
					'LINK_URL' => append_sid(THIS_FILE . '?action=go&amp;link_id=' . $row['link_id']),
					'LINK_TITLE' => $row['link_title'],
					'LINK_DESC' => $row['link_desc'],
					'LINK_LOGO_SRC' => $row['link_logo_src'],
					'LINK_LOGO' => $tmp,
					'LINK_CATEGORY' => $link_categories[$row['link_category']],
					'LINK_JOINED' => create_date_ip($lang['DATE_FORMAT'], $row['link_joined'], $config['board_timezone']),
					'LINK_HITS' => $row['link_hits']
					)
				);
				$i++;
			}
			while ($row = $db->sql_fetchrow($result));
			$db->sql_freeresult($result);
		}

		// Pagination
		$sql = "SELECT count(*) AS total
			FROM " . LINKS_TABLE . "
			WHERE link_active = 1
				AND (link_title LIKE '%" . $db->sql_escape($search_keywords) . "%' OR link_desc LIKE '%" . $db->sql_escape($search_keywords) . "%')";
		$result = $db->sql_query($sql);

		$total_links = 10;
		$pagination = '&nbsp;';
		if ($row = $db->sql_fetchrow($result))
		{
			$total_links = $row['total'];
			$pagination = generate_pagination(THIS_FILE . '?t=' . $t . '&amp;search_keywords=' . urlencode($search_keywords), $total_links, $links_config['linkspp'], $start);
		}
		$db->sql_freeresult($result);
	}

	// Link categories dropdown list
	foreach($link_categories as $cat_id => $cat_title)
	{
		$link_cat_option .= '<option value="' . $cat_id . '">' . $cat_title . '</option>';
	}

	$template->assign_vars(array(
		'PAGINATION' => $pagination,
		'PAGE_NUMBER' => sprintf($lang['Page_of'], (floor($start / $links_config['linkspp']) + 1), ceil($total_links / $links_config['linkspp'])),
		'L_GOTO_PAGE' => $lang['Goto_page'],
		'LINK_CAT_OPTION' => $link_cat_option
		)
	);
}

$template->assign_vars(array(
	'FOLDER_IMG' => $images['forum_nor_read']
	)
);

// Grab link categories
$sql = "SELECT cat_id, cat_title FROM " . LINK_CATEGORIES_TABLE . " ORDER BY cat_order ASC";
$result = $db->sql_query($sql);

if ($row = $db->sql_fetchrow($result))
{
	$row_class = '';
	do
	{
		$link_categories[$row['cat_id']] = $row['cat_title'];
		$sql = "SELECT link_category FROM " . LINKS_TABLE . "
			WHERE link_active = 1
			AND link_category = '" . $row['cat_id'] . "'";
		$links_result = $db->sql_query($sql);
		$links_number = $db->sql_numrows($links_result);
		$row_class = ip_zebra_rows($row_class);
		$template->assign_block_vars('linkrow', array(
			'ROW_CLASS' => $row_class,
			'LINK_URL' => append_sid(THIS_FILE . '?t=sub_pages&amp;cat=' . $row['cat_id']),
			'LINK_TITLE' => $row['cat_title'],
			'LINK_NUMBER' => $links_number
			)
		);
		$db->sql_freeresult($links_result);
	}
	while ($row = $db->sql_fetchrow($result));
	$db->sql_freeresult($result);
}

// Link categories dropdown list
foreach($link_categories as $cat_id => $cat_title)
{
	$link_cat_option .= '<option value="' . $cat_id . '">' . $cat_title . '</option>';
}

$template->assign_vars(array(
	'LINK_CAT_OPTION' => $link_cat_option
	)
);

full_page_generation($template_to_parse, $lang['Site_links'], '', '');

?>