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

/*
* Default Links Array
*/
function cms_menu_default_links_array()
{
	global $lang;

	$default_links_array = array(
		0 => array('lang' => 'CMS_MENU_NO_DEFAULT_LINK_SELECT', 'link' => '', 'auth' => AUTH_CMS_ALL),
		1 => array('lang' => 'Admin_panel', 'link' => 'adm/index.' . PHP_EXT, 'auth' => AUTH_CMS_ADMIN, 'sid' => true),
		2 => array('lang' => 'CMS_TITLE', 'link' => CMS_PAGE_CMS, 'auth' => AUTH_CMS_ADMIN, 'sid' => true),
		3 => array('lang' => 'Home', 'link' => CMS_PAGE_HOME, 'auth' => AUTH_CMS_ALL),
		4 => array('lang' => 'Profile', 'link' => CMS_PAGE_PROFILE_MAIN, 'auth' => AUTH_CMS_REG),
		5 => array('lang' => 'Forum_Index', 'link' => CMS_PAGE_FORUM, 'auth' => AUTH_CMS_ALL),
		6 => array('lang' => 'FAQ', 'link' => CMS_PAGE_FAQ, 'auth' => AUTH_CMS_ALL),
		7 => array('lang' => 'Search', 'link' => CMS_PAGE_SEARCH, 'auth' => AUTH_CMS_ALL),
		8 => array('lang' => 'Sitemap', 'link' => 'sitemap.' . PHP_EXT, 'auth' => AUTH_CMS_ALL),
		9 => array('lang' => 'Album', 'link' => CMS_PAGE_ALBUM, 'auth' => AUTH_CMS_ALL),
		10 => array('lang' => 'Calendar', 'link' => CMS_PAGE_CALENDAR, 'auth' => AUTH_CMS_ALL),
		11 => array('lang' => 'Downloads', 'link' => CMS_PAGE_DL_DEFAULT, 'auth' => AUTH_CMS_ALL),
		12 => array('lang' => 'Bookmarks', 'link' => CMS_PAGE_SEARCH . '?search_id=bookmarks', 'auth' => AUTH_CMS_REG),
		13 => array('lang' => 'Drafts', 'link' => CMS_PAGE_DRAFTS, 'auth' => AUTH_CMS_REG),
		14 => array('lang' => 'Uploaded_Images_Local', 'link' => CMS_PAGE_IMAGES, 'auth' => AUTH_CMS_REG),
		15 => array('lang' => 'Ajax_Chat', 'link' => CMS_PAGE_AJAX_CHAT, 'auth' => AUTH_CMS_ALL),
		16 => array('lang' => 'Links', 'link' => CMS_PAGE_LINKS, 'auth' => AUTH_CMS_ALL),
		17 => array('lang' => 'KB_title', 'link' => 'kb.' . PHP_EXT, 'auth' => AUTH_CMS_ALL),
		18 => array('lang' => 'Contact_us', 'link' => CMS_PAGE_CONTACT_US, 'auth' => AUTH_CMS_ALL),
		19 => array('lang' => 'BoardRules', 'link' => CMS_PAGE_RULES, 'auth' => AUTH_CMS_ALL),
		20 => array('lang' => 'TAGS_TEXT', 'link' => CMS_PAGE_TAGS, 'auth' => AUTH_CMS_ALL),
		21 => array('lang' => 'Sudoku', 'link' => 'sudoku.' . PHP_EXT, 'auth' => AUTH_CMS_REG),
		22 => array('lang' => 'LINK_NEWS_CAT', 'link' => CMS_PAGE_HOME . '?news=categories', 'auth' => AUTH_CMS_ALL),
		23 => array('lang' => 'LINK_NEWS_ARC', 'link' => CMS_PAGE_HOME . '?news=archives', 'auth' => AUTH_CMS_ALL),
		24 => array('lang' => 'NEW_POSTS_LINK', 'link' => CMS_PAGE_SEARCH . '?search_id=newposts', 'auth' => AUTH_CMS_REG),
		25 => array('lang' => 'UPI2DB_LINK_U', 'link' => CMS_PAGE_SEARCH . '?search_id=upi2db&s2=new', 'auth' => AUTH_CMS_REG, 'function' => 'upi2db_menu_links(\'unread\')'),
		26 => array('lang' => 'UPI2DB_LINK_M', 'link' => CMS_PAGE_SEARCH . '?search_id=upi2db&s2=mark', 'auth' => AUTH_CMS_REG, 'function' => 'upi2db_menu_links(\'marked\')'),
		27 => array('lang' => 'UPI2DB_LINK_P', 'link' => CMS_PAGE_SEARCH . '?search_id=upi2db&s2=perm', 'auth' => AUTH_CMS_REG, 'function' => 'upi2db_menu_links(\'perm\')'),
		28 => array('lang' => 'UPI2DB_LINK_FULL', 'link' => '', 'auth' => AUTH_CMS_REG, 'function' => 'upi2db_menu_links(\'full\')'),
		29 => array('lang' => 'DIGESTS', 'link' => 'digests.' . PHP_EXT, 'auth' => AUTH_CMS_REG),
		30 => array('lang' => 'Hacks_List', 'link' => CMS_PAGE_CREDITS, 'auth' => AUTH_CMS_ALL),
		31 => array('lang' => 'REFERERS', 'link' => CMS_PAGE_REFERERS, 'auth' => AUTH_CMS_ALL),
		32 => array('lang' => 'Who_is_Online', 'link' => CMS_PAGE_VIEWONLINE, 'auth' => AUTH_CMS_ALL),
		33 => array('lang' => 'Statistics', 'link' => CMS_PAGE_STATISTICS, 'auth' => AUTH_CMS_ALL),
		34 => array('lang' => 'RSS', 'link' => 'rss.' . PHP_EXT, 'auth' => AUTH_CMS_ALL),
		35 => array('lang' => 'Delete_cookies', 'link' => 'remove_cookies.' . PHP_EXT, 'auth' => AUTH_CMS_ALL),
		36 => array('lang' => 'Memberlist', 'link' => CMS_PAGE_MEMBERLIST, 'auth' => AUTH_CMS_ALL),
		37 => array('lang' => 'Usergroups', 'link' => CMS_PAGE_GROUP_CP, 'auth' => AUTH_CMS_ALL),
		38 => array('lang' => 'Rank_Header', 'link' => 'ranks.' . PHP_EXT, 'auth' => AUTH_CMS_ALL),
		39 => array('lang' => 'Staff', 'link' => CMS_PAGE_MEMBERLIST . '?mode=staff', 'auth' => AUTH_CMS_ALL),
		40 => array('lang' => 'Change_Style', 'link' => CMS_PAGE_PROFILE_MAIN, 'auth' => AUTH_CMS_ALL, 'function' => 'select_style_lang_link(\'style\')'),
		41 => array('lang' => 'Change_Lang', 'link' => CMS_PAGE_PROFILE_MAIN, 'auth' => AUTH_CMS_GUESTS_ONLY, 'function' => 'select_style_lang_link(\'lang\')'),
		42 => array('lang' => 'Rss_news_feeds', 'link' => 'javascript:rss_news_help();', 'auth' => AUTH_CMS_ALL),
		43 => array('lang' => 'Register', 'link' => CMS_PAGE_PROFILE . '?mode=register', 'auth' => AUTH_CMS_GUESTS_ONLY),
		44 => array('lang' => 'LOGIN_LOGOUT_LINK', 'link' => '', 'auth' => AUTH_CMS_ALL, 'function' => 'login_logout_link()'),
	);

	return $default_links_array;
}

/*
* Build Link URL
*/
function cms_menu_build_link($item_data, $block_id)
{
	global $db, $cache, $config, $user, $lang, $template, $theme, $images;
	global $default_links_array;

	$menu_link = array(
		'icon' => '',
		'name' => '',
		'link' => '',
		'url' => ''
	);

	$menu_link['icon'] = '<img src="' . (($item_data['menu_icon'] != '') ? $item_data['menu_icon'] : $images['nav_menu_sep']) . '" alt="" title="" style="vertical-align: middle;" />&nbsp;';

	if (($item_data['menu_name_lang'] != '') && isset($lang['menu_item'][$item_data['menu_name_lang']]))
	{
		$menu_link['name'] = $lang['menu_item'][$item_data['menu_name_lang']];
	}
	else
	{
		$menu_link['name'] = (($item_data['menu_name'] != '') ? htmlspecialchars(stripslashes($item_data['menu_name'])) : ('cat_item' . $item_data['cat_id']));
	}

	if (empty($item_data['menu_default']))
	{
		if (!empty($item_data['menu_link_external']))
		{
			$menu_link['link'] = htmlspecialchars($item_data['menu_link']);
			$menu_link['link'] .= '" target="_blank';
		}
		else
		{
			$menu_link['link'] = append_sid(htmlspecialchars($item_data['menu_link']));
		}
		$menu_link['url'] = (!empty($menu_link['link']) ? '<a href="' . $menu_link['link'] . '">' . $menu_link['icon'] . $menu_link['name'] . '</a>' : '');
	}
	else
	{
		$menu_link['link'] = cms_menu_build_complete_url($item_data['menu_default'], $block_id, $item_data['menu_link'], $menu_link['icon']);
		$menu_link['url'] = (!empty($menu_link['link']) ? $menu_link['link'] : '');
	}
	$menu_link['url'] = (!empty($menu_link['url']) ? '<div class="genmed" align="left">' . $menu_link['url'] . '</div>' : '');

	return $menu_link;
}

/*
* Build Complete URL
*/
function cms_menu_build_complete_url($default_id, $block_id, $link, $menu_icon)
{
	global $db, $cache, $config, $user, $lang, $template, $theme, $images;
	global $default_links_array;

	if (empty($default_links_array))
	{
		$default_links_array = cms_menu_default_links_array();
	}

	if (!empty($default_links_array[$default_id]['function']))
	{
		$eval_f = $default_links_array[$default_id]['function'];
		eval('$new_link_array = ' . $eval_f . ';');
		$default_links_array[$default_id] = $new_link_array;
	}

	if (!empty($default_links_array[$default_id]['noicon']))
	{
		$menu_icon = '';
	}

	if (!empty($default_links_array[$default_id]['full_link']))
	{
		$menu_url = $menu_icon . $default_links_array[$default_id]['full_link'];
	}
	else
	{
		$menu_name_lang_value = $lang[$default_links_array[$default_id]['lang']];
		$menu_name = !empty($menu_name_lang_value) ? $menu_name_lang_value : $lang['MENU_EMPTY_LINK'];
		$menu_url_title = !empty($default_links_array[$default_id]['title']) ? (' title="' . htmlspecialchars($default_links_array[$default_id]['title']) . '"') : '';

		if (!empty($default_links_array[$default_id]['sid']))
		{
			$menu_link = $default_links_array[$default_id]['link'] . '?sid=' . $user->data['session_id'];
		}
		else
		{
			$menu_link = append_sid($default_links_array[$default_id]['link']);
		}

		$menu_url = '<a href="' . $menu_link . '"' . $menu_url_title . '>' . $menu_icon . htmlspecialchars($menu_name) . '</a>';
	}

	return $menu_url;
}

/**
* upi2db_menu_links
*/
function upi2db_menu_links($link_type)
{
	global $db, $cache, $config, $user, $lang, $template, $theme, $images;

	$link = array('lang' => 'NEW_POSTS_SHORT', 'link' => CMS_PAGE_SEARCH . '?search_id=newposts', 'auth' => AUTH_CMS_ADMIN);
	if(empty($user->data['upi2db_access']))
	{
		return $link;
	}

	if (!defined('UPI2DB_UNREAD'))
	{
		$user->data['upi2db_unread'] = upi2db_unread();
	}

	$u_display_new = index_display_new($user->data['upi2db_unread']);

	switch ($link_type)
	{
		case 'unread':
			$link = array('lang' => $u_display_new['unread_string'], 'link' => $u_display_new['u_url'], 'auth' => AUTH_CMS_REG, 'title' => $u_display_new['u_string_full']);
		break;
		case 'marked':
			$link = array('lang' => $u_display_new['marked_string'], 'link' => $u_display_new['m_url'], 'auth' => AUTH_CMS_REG, 'title' => $u_display_new['m_string_full']);
		break;
		case 'perm':
			$link = array('lang' => $u_display_new['permanent_string'], 'link' => $u_display_new['p_url'], 'auth' => AUTH_CMS_REG, 'title' => $u_display_new['p_string_full']);
		break;
		case 'full':
		default:
			$full_link = $lang['Posts'] . ': <a href="search.' . PHP_EXT . '?search_id=newposts">' . $lang['NEW_POSTS_SHORT'] . '</a>' . '&nbsp;&#8226;&nbsp;' . $u_display_new['u'] . '&nbsp;&#8226;&nbsp;' . $u_display_new['m'] . '&nbsp;&#8226;&nbsp;' . $u_display_new['p'];
			$link = array('full_link' => $full_link, 'lang' => 'UPI2DB_LINK_FULL', 'link' => CMS_PAGE_SEARCH . '?search_id=newposts', 'auth' => AUTH_CMS_REG);
		break;
	}

	return $link;
}

/**
* select_style_lang_link()
*/
function select_style_lang_link($select_type)
{
	global $db, $cache, $config, $user, $lang, $template, $theme, $images;

	$link = array('lang' => 'Profile', 'link' => CMS_PAGE_PROFILE_MAIN, 'auth' => AUTH_CMS_REG);

	if(!defined('IN_CMS') && ((($select_type == 'style') && empty($config['select_theme'])) || (($select_type == 'lang') && empty($config['select_lang']))))
	{
		return $link;
	}

	global $block_id;
	if ($select_type == 'style')
	{
		$default_style = $config['default_style'];
		$select_name = STYLE_URL;
		$dirname = 'templates';

		$style_select = '<select name="' . $select_name . '" onchange="SetTheme_' . $block_id . '();" class="gensmall">';
		$styles = $cache->obtain_styles(true);
		foreach ($styles as $k => $v)
		{
			$selected = ($k == $default_style) ? ' selected="selected"' : '';
			$style_select .= '<option value="' . $k . '"' . $selected . '>' . htmlspecialchars($v) . '</option>';
		}
		$style_select .= '</select>';
		$full_link = '<form name="ChangeTheme_' . $block_id . '" method="post" action="' . htmlspecialchars(urldecode($_SERVER['REQUEST_URI'])) . '">' . $style_select . '</form>';
		$link = array('full_link' => $full_link, 'lang' => 'Change_Style', 'link' => CMS_PAGE_PROFILE_MAIN, 'auth' => AUTH_CMS_ALL);
	}
	else
	{
		$full_link = '';
		if (!function_exists('language_select'))
		{
			@include_once(IP_ROOT_PATH . 'includes/functions_selects.' . PHP_EXT);
		}
		$lang_installed = language_select(LANG_URL, $config['default_lang'], 'language', true);
		while (list($displayname) = @each($lang_installed))
		{
			$lang_value = $displayname;
			$lang_name = ucwords($displayname);
			$lang_url = append_sid(CMS_PAGE_HOME . '?' . LANG_URL . '=' . $lang_value);
			$lang_icon = '<img src="language/lang_' . $displayname . '/flag.png" alt="" title="" style="vertical-align: middle;" />&nbsp;';
			$full_link .= '<a href="' . $lang_url . '">' . $lang_icon . $lang_name . '&nbsp;<br /></a>';
		}
		$link = array('full_link' => $full_link, 'lang' => 'Change_Lang', 'link' => CMS_PAGE_PROFILE_MAIN, 'auth' => AUTH_CMS_GUESTS_ONLY, 'noicon' => true);
	}

	return $link;
}

/**
* login_logout_link()
*/
function login_logout_link()
{
	global $db, $cache, $config, $user, $lang, $template, $theme, $images;

	if (!$user->data['session_logged_in'])
	{
		$link = array('lang' => 'Login', 'link' => CMS_PAGE_LOGIN, 'auth' => AUTH_CMS_GUESTS_ONLY);
	}
	else
	{
		$link = array('lang' => 'Logout', 'link' => CMS_PAGE_LOGIN . '?logout=true&amp;sid=' . $user->data['session_id'], 'auth' => AUTH_CMS_REG);
	}

	return $link;
}

?>