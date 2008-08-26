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

define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.' . $phpEx);

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

$cms_page_id = '13';
$cms_page_name = 'links';
$auth_level_req = $board_config['auth_view_links'];
if ($auth_level_req > AUTH_ALL)
{
	if (($auth_level_req == AUTH_REG) && (!$userdata['session_logged_in']))
	{
		message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
	}
	if ($userdata['user_level'] != ADMIN)
	{
		if ($auth_level_req == AUTH_ADMIN)
		{
			message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
		}
		if (($auth_level_req == AUTH_MOD) && ($userdata['user_level'] != MOD))
		{
			message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
		}
	}
}
$cms_global_blocks = ($board_config['wide_blocks_links'] == 1) ? true : false;

require($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_main_link.' . $phpEx);

// Count and forwrad
if(($_GET['action'] == 'go') && ($_GET['link_id']))
{
	$link_id = intval($_GET['link_id']);
	$link_id = ($link_id < 0) ? 0 : $link_id;
	// Secure check
	if(is_numeric($link_id))
	{
		$sql = "SELECT link_id, link_url, last_user_ip
			FROM " . LINKS_TABLE . "
			WHERE link_id = '" . $link_id . "'
			AND link_active = 1";

		if($result = $db->sql_query($sql))
		{
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
	}
}

// Output the basic page
$page_title = $lang['Site_links'];
$meta_description = '';
$meta_keywords = '';
include('includes/page_header.' . $phpEx);

// Define initial vars
$start = isset($_GET['start']) ? intval($_GET['start']) : 0;
$start = ($start < 0) ? 0 : $start;

if (isset($_POST['t']) || isset($_GET['t']))
{
	$t = (isset($_POST['t'])) ? htmlspecialchars($_POST['t']) : htmlspecialchars($_GET['t']);
}
else
{
	$t = 'index';
}

if (isset($_POST['cat']) || isset($_GET['cat']))
{
	$cat = (isset($_POST['cat'])) ? $_POST['cat'] : $_GET['cat'];
}
else
{
	$cat = 1;
}
$cat = (intval($cat) > 0) ? intval($cat) : 1;

if (isset($_POST['search_keywords']) || isset($_GET['search_keywords']))
{
	$search_keywords = (isset($_POST['search_keywords'])) ? $_POST['search_keywords'] : $_GET['search_keywords'];
}
else
{
	$search_keywords = '';
}

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

$template->set_filenames(array('body' => $tmp));

// Get Link Config
$sql = "SELECT * FROM ". LINK_CONFIG_TABLE;
if(!$result = $db->sql_query($sql, false, 'links_'))
{
	message_die(GENERAL_ERROR, "Could not query Link config information", "", __LINE__, __FILE__, $sql);
}
while($row = $db->sql_fetchrow($result))
{
	$link_config_name = $row['config_name'];
	$link_config_value = $row['config_value'];
	$link_config[$link_config_name] = $link_config_value;
	$linkspp=$link_config['linkspp'];
}

if($link_config['lock_submit_site'] == 0)
{
	// display submit site
	$template->assign_block_vars('lock', array());

	if(!$userdata['session_logged_in'])
	{
		$template->assign_block_vars('lock.logout', array());
	}

	if($userdata['session_logged_in'])
	{
		$template->assign_block_vars('lock.submit', array());
	}
}

if($link_config['allow_no_logo'])
{
	$tmp = $lang['Link_logo_src'];
}
else
{
	$tmp = $lang['Link_logo_src1'];
}

$template->assign_vars(array(
	'U_LINK_REG' => append_sid('link_register.' . $phpEx),
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
	'L_LINK_US' => $lang['Link_us'] . $board_config['sitename'],
	'L_LINK_US_EXPLAIN' => sprintf($lang['Link_us_explain'], $board_config['sitename']),'L_SUBMIT' => $lang['Submit'],
	'U_SITE_LINKS' => append_sid('links.' . $phpEx),
	'L_LINK_CATEGORY' => $lang['Link_category'],
	'U_SITE_SEARCH' => append_sid('links.' . $phpEx . '?t=search'),
	'U_SITE_TOP' => append_sid('links.' . $phpEx . '?t=pop'),
	'U_SITE_NEW' => append_sid('links.' . $phpEx . '?t=new'),
	'U_SITE_LOGO' => $link_config['site_logo'],
	'LINK_US_SYNTAX' => str_replace(' ', '&nbsp;', sprintf(htmlentities($lang['Link_us_syntax'], ENT_QUOTES), $link_config['site_url'], $link_config['site_logo'], $link_config['width'],$link_config['height'], $board_config['sitename'])),
	'LINKS_HOME' => $lang['Links_home'],
	'L_SEARCH_SITE' => $lang['Search_site'],
	'L_DESCEND_BY_HITS' => $lang['Descend_by_hits'],
	'L_DESCEND_BY_JOINDATE' => $lang['Descend_by_joindate'],
	'L_LINK_JOINED' => $lang['Joined'],
	'L_LINK_HITS' => $lang['link_hits'],
	'L_REMEMBER_ME' => $lang['Remember_Me'],
	)
);

if ($t == 'pop' || $t == 'new')
{
	if ($t=='pop')
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
	$sql = "SELECT cat_id, cat_title FROM " . LINK_CATEGORIES_TABLE . " ORDER BY cat_order";

	if(!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not query link categories list', '', __LINE__, __FILE__, $sql);
	}

	while($row = $db->sql_fetchrow($result))
	{
		$link_categories[$row['cat_id']] = $row['cat_title'];
	}

	// Grab links
	$sql = "SELECT * FROM " . LINKS_TABLE . "
		WHERE link_active = 1
		ORDER BY link_hits DESC, link_id DESC
		LIMIT $start, $linkspp";
	if ($t == 'new')
	{
		$sql = "SELECT * FROM " . LINKS_TABLE . "
			WHERE link_active = 1
			ORDER BY link_joined DESC, link_id DESC
			LIMIT $start, $linkspp";
	}

	if(!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not query links list', '', __LINE__, __FILE__, $sql);
	}

	if ($row = $db->sql_fetchrow($result))
	{
		$i = 0;
		do
		{
			// if (empty($row['link_logo_src'])) $row['link_logo_src'] = 'images/links/no_logo88a.gif';
			if ($link_config['display_links_logo'])
			{
				if ($row['link_logo_src'])
				{
					$tmp = '<a href=' . append_sid('links.' . $phpEx . '?action=go&amp;link_id=' . $row['link_id']) . ' alt="' . $row['link_desc'] . '" target="_blank"><img src="' . $row['link_logo_src'] . '" alt="' . $row['link_title'] . '" width="' . $link_config['width'] . '" height="' . $link_config['height'] . '" border="0" hspace="1" /></a>';
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

			$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];

			$template->assign_block_vars('linkrow', array(
				'ROW_CLASS' => $row_class,
				'LINK_URL' => append_sid('links.' . $phpEx . '?action=go&link_id=' . $row['link_id']),
				'LINK_TITLE' => $row['link_title'],
				'LINK_DESC' => $row['link_desc'],
				'LINK_LOGO_SRC' => $row['link_logo_src'],
				'LINK_LOGO' => $tmp,
				'LINK_CATEGORY' => $link_categories[$row['link_category']],
				'LINK_JOINED' => create_date2($lang['DATE_FORMAT'], $row['link_joined'], $board_config['board_timezone']),
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

	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not query links number', '', __LINE__, __FILE__, $sql);
	}

	if ($row = $db->sql_fetchrow($result))
	{
		$total_links = $row['total'];
		$pagination = generate_pagination('links.' . $phpEx . '?t=' . $t, $total_links, $linkspp, $start) . '&nbsp;';
	}
	else
	{
		$pagination = '&nbsp;';
		$total_links = 10;
	}

	// Link categories dropdown list
	foreach($link_categories as $cat_id => $cat_title)
	{
		$link_cat_option .= '<option value="' . $cat_id . '">' . $cat_title . '</option>';
	}


	$template->assign_vars(array(
		'PAGINATION' => $pagination,
		'PAGE_NUMBER' => sprintf($lang['Page_of'], (floor($start / $linkspp) + 1), ceil($total_links / $linkspp)),
		'L_GOTO_PAGE' => $lang['Goto_page'],
		'LINK_CAT_OPTION' => $link_cat_option
		)
	);

	$template->pparse('body');

	include($phpbb_root_path . 'includes/page_tail.' . $phpEx);
	exit;
}

if ($t == 'sub_pages')
{
	if (isset($_GET['mode']) || isset($_POST['mode']))
	{
		$mode = (isset($_POST['mode'])) ? $_POST['mode'] : $_GET['mode'];
	}
	else
	{
		$mode = 'link_joined';
	}

	if(isset($_POST['order']))
	{
		$sort_order = ($_POST['order'] == 'ASC') ? 'ASC' : 'DESC';
	}
	elseif(isset($_GET['order']))
	{
		$sort_order = ($_GET['order'] == 'ASC') ? 'ASC' : 'DESC';
	}
	else
	{
		$sort_order = 'DESC';
	}

	// Links sites sorting
	$mode_types_text = array($lang['Joined'], $lang['link_hits'], $lang['Link_title'], $lang['Link_desc']);
	$mode_types = array('link_joined', 'link_hits', 'link_title', 'link_desc');
	$mode = (in_array($mode, $mode_types) ? $mode : $mode_types[0]);

	$select_sort_mode = '<select name="mode">';
	for($i = 0; $i < count($mode_types_text); $i++)
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

	$select_sort_order = $select_sort_order . '<input type="hidden" name="t" value="' . $t .'">';
	$select_sort_order = $select_sort_order . '<input type="hidden" name="cat" value="' . $cat .'">';

	$template->assign_vars(array(
		'L_SEARCH_SITE' => $lang['Search_site'],
		'L_SELECT_SORT_METHOD' => $lang['Select_sort_method'],
		'L_ORDER' => $lang['Order'],
		'L_SORT' =>  $lang['Sort'],
		//'L_SUBMIT' => 'Submit',
		'U_SITE_LINKS_CAT' => append_sid('links.' . $phpEx . '?t=' . $t . '&amp;cat=' . $cat),
		'S_MODE_SELECT' => $select_sort_mode,
		'S_ORDER_SELECT' => $select_sort_order
		)
	);

	// Grab link categories
	$sql = "SELECT cat_id, cat_title FROM " . LINK_CATEGORIES_TABLE . " WHERE cat_id = '" . $cat . "'";
	if(!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not query link categories list', '', __LINE__, __FILE__, $sql);
	}

	$row = $db->sql_fetchrow($result);
	$link_categories[$row['cat_id']] = $row['cat_title'];
	$template->assign_vars(array(
		'LINK_CATEGORY' => $row['cat_title']
		)
	);

	// Grab links
	$sql = "SELECT * FROM " . LINKS_TABLE . "
			WHERE link_active = 1
				AND link_category = '" . $cat . "'
			ORDER BY $mode $sort_order, link_id DESC
			LIMIT $start, $linkspp";

	if(!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not query links list', '', __LINE__, __FILE__, $sql);
	}


	if ($row = $db->sql_fetchrow($result))
	{
		$i = 0;
		do
		{
			//if (empty($row['link_logo_src'])) $row['link_logo_src'] = 'images/links/no_logo88a.gif';
			if ($link_config['display_links_logo'])
			{
				if ($row['link_logo_src'])
				{
					$tmp = '<a href="' . append_sid('links.' . $phpEx . '?action=go&amp;link_id=' . $row['link_id']) . '" alt="' . $row['link_desc'] . '" target="_blank"><img src="' . $row['link_logo_src'] . '" alt="' . $row['link_title'] . '" width="' . $link_config['width'] . '" height="' . $link_config['height'] . '" border="0" hspace="1" /></a>';
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

			$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];

			$template->assign_block_vars('linkrow', array(
				'ROW_CLASS' => $row_class,
				'LINK_URL' => append_sid('links.' . $phpEx . '?action=go&link_id=' . $row['link_id']),
				'LINK_TITLE' => $row['link_title'],
				'LINK_DESC' => $row['link_desc'],
				'LINK_LOGO_SRC' => $row['link_logo_src'],
				'LINK_LOGO' => $tmp,
				'LINK_CATEGORY' => $link_categories[$row['link_category']],
				'LINK_JOINED' => create_date2($lang['DATE_FORMAT'], $row['link_joined'], $board_config['board_timezone']),
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
		WHERE link_active = 1
			AND link_category = '" . $cat . "'";

	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not query links number', '', __LINE__, __FILE__, $sql);
	}

	if ($row = $db->sql_fetchrow($result))
	{
		$total_links = $row['total'];
		$pagination = generate_pagination('links.' . $phpEx . '?t=' . $t . '&amp;cat=' . $cat . '&amp;mode=' . $mode . '&amp;order=' . $sort_order, $total_links, $linkspp, $start). '&nbsp;';
	}
	else
	{
		$pagination = '&nbsp;';
		$total_links = 10;
	}

	// Link categories dropdown list
	foreach($link_categories as $cat_id => $cat_title)
	{
		$link_cat_option .= '<option value="' . $cat_id . '">' . $cat_title . '</option>';
	}

	$template->assign_vars(array(
		'PAGINATION' => $pagination,
		'PAGE_NUMBER' => sprintf($lang['Page_of'], (floor($start / $linkspp) + 1), ceil($total_links / $linkspp)),
		'L_GOTO_PAGE' => $lang['Goto_page'],
		'LINK_CAT_OPTION' => $link_cat_option
		)
	);

	$template->pparse('body');

	include($phpbb_root_path . 'includes/page_tail.' . $phpEx);
	exit;
}

if ($t == 'search')
{
	if ($search_keywords)
	{
		$search_keywords = trim(stripslashes($search_keywords));
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
	$sql = "SELECT cat_id, cat_title FROM " . LINK_CATEGORIES_TABLE . " ORDER BY cat_order";

	if(!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not query link categories list', '', __LINE__, __FILE__, $sql);
	}

	while($row = $db->sql_fetchrow($result))
	{
		$link_categories[$row['cat_id']] = $row['cat_title'];
	}

	// Grab links
	if ($search_keywords)
	{
		$sql = "SELECT * FROM " . LINKS_TABLE . "
			WHERE link_active = 1
				AND (link_title LIKE '%$search_keywords%' OR link_desc LIKE '% $search_keywords%')
			LIMIT $start, $linkspp";

		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not query links list', '', __LINE__, __FILE__, $sql);
		}

		if ($row = $db->sql_fetchrow($result))
		{
			$i = 0;
			do
			{
				//if (empty($row['link_logo_src'])) $row['link_logo_src'] = 'images/links/no_logo88a.gif';
				if ($link_config['display_links_logo'])
				{
					if ($row['link_logo_src'])
					{
						$tmp = '<a href=' . append_sid('links.' . $phpEx . '?action=go&amp;link_id=' . $row['link_id']) . ' alt="' . $row['link_desc'] . '" target="_blank"><img src="' . $row['link_logo_src'] . '" alt="' . $row['link_title'] . '" width="' . $link_config['width'] . '" height="' . $link_config['height'] . '" border="0" hspace="1" /></a>';
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

				$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];

				$template->assign_block_vars('linkrow', array(
					'ROW_CLASS' => $row_class,
					'LINK_URL' => append_sid('links.' . $phpEx . '?action=go&amp;link_id=' . $row['link_id']),
					'LINK_TITLE' => $row['link_title'],
					'LINK_DESC' => $row['link_desc'],
					'LINK_LOGO_SRC' => $row['link_logo_src'],
					'LINK_LOGO' => $tmp,
					'LINK_CATEGORY' => $link_categories[$row['link_category']],
					'LINK_JOINED' => create_date2($lang['DATE_FORMAT'], $row['link_joined'], $board_config['board_timezone']),
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
			WHERE link_active = 1
				AND (link_title LIKE '%$search_keywords%' OR link_desc LIKE '%$search_keywords %')";

		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not query links number', '', __LINE__, __FILE__, $sql);
		}

		if ($row = $db->sql_fetchrow($result))
		{
			$total_links = $row['total'];
			$pagination = generate_pagination('links.' . $phpEx . '?t=' . $t . '&amp;search_keywords=' . $search_keywords, $total_links, $linkspp, $start). '&nbsp;';
		}
		else
		{
			$pagination = '&nbsp;';
			$total_links = 10;
		}
	}

	// Link categories dropdown list
	foreach($link_categories as $cat_id => $cat_title)
	{
		$link_cat_option .= '<option value="' . $cat_id . '">' . $cat_title . '</option>';
	}

	$template->assign_vars(array(
		'PAGINATION' => $pagination,
		'PAGE_NUMBER' => sprintf($lang['Page_of'], (floor($start / $linkspp) + 1), ceil($total_links / $linkspp)),
		'L_GOTO_PAGE' => $lang['Goto_page'],
		'LINK_CAT_OPTION' => $link_cat_option
		)
	);

	$template->pparse('body');

	include($phpbb_root_path . 'includes/page_tail.' . $phpEx);
	exit;
}

$template->assign_vars(array(
	'FOLDER_IMG' => $images['forum_nor_read']
	)
);

// Grab link categories
$sql = "SELECT cat_id, cat_title FROM " . LINK_CATEGORIES_TABLE . " ORDER BY cat_order";
if(!$result = $db->sql_query($sql))
{
	message_die(GENERAL_ERROR, 'Could not query link categories list', '', __LINE__, __FILE__, $sql);
}

// Separate link categories into 2 columns
$i = 0;
if ($row = $db->sql_fetchrow($result))
{
	do
	{
		$i = ($i + 1) % 2;
		$link_categories[$row['cat_id']] = $row['cat_title'];
		$sql = "SELECT link_category FROM " . LINKS_TABLE . "
			WHERE link_active = 1
			AND link_category = '" . $row['cat_id'] . "'";
		if(!$linknum = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not query links list', '', __LINE__, __FILE__, $sql);
		}
		$template->assign_block_vars('linkrow' . $i, array(
			'LINK_URL' => append_sid('links.' . $phpEx . '?t=sub_pages&amp;cat=' . $row['cat_id']),
			'LINK_TITLE' => $row['cat_title'],
			'LINK_NUMBER' => $db->sql_numrows($linknum)
			)
		);
	}
	while ($row = $db->sql_fetchrow($result));
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

$template->pparse('body');

include($phpbb_root_path . 'includes/page_tail.' . $phpEx);

?>