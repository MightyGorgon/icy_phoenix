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

function build_default_link_array()
{
	global $lang;
	$link_name_array = '';
	$link_name_array = array(
		'0' => '--&nbsp;' . $lang['CMS_Menu_No_default_link'] . '&nbsp;--',
		'1' => $lang['Admin_panel'],
		'2' => $lang['CMS_TITLE'],
		'3' => $lang['Home'],
		'4' => $lang['Profile'],
		'5' => $lang['Forum_Index'],
		'6' => $lang['FAQ'],
		'7' => $lang['Search'],
		'8' => $lang['Sitemap'],
		'9' => $lang['Album'],
		'10' => $lang['Calendar'],
		'11' => $lang['Downloads'],
		'12' => $lang['Bookmarks'],
		'13' => $lang['Drafts'],
		'14' => $lang['Uploaded_Images_Local'],
		'15' => $lang['Ajax_Chat'],
		'16' => $lang['Links'],
		'17' => $lang['KB_title'],
		'18' => $lang['Contact_us'],
		'19' => $lang['BoardRules'],
		//'20' => $lang['DBGenerator'],
		'21' => $lang['Sudoku'],
		'22' => $lang['LINK_NEWS_CAT'],
		'23' => $lang['LINK_NEWS_ARC'],
		'24' => $lang['New3'],
		'25' => $lang['upi2db_unread'],
		'26' => $lang['upi2db_marked'],
		'27' => $lang['upi2db_perm_read'],
		'28' => $lang['Posts'] . ': ' . $lang['New2'] . ' - ' . $lang['upi2db_u'] . ' - ' . $lang['upi2db_m'] . ' - ' . $lang['upi2db_p'],
		'29' => $lang['Digests'],
		'30' => $lang['Hacks_List'],
		'31' => $lang['Referrers'],
		'32' => $lang['Who_is_Online'],
		'33' => $lang['Statistics'],
		//'34' => $lang['Site_Hist'],
		'35' => $lang['Delete_cookies'],
		'36' => $lang['Memberlist'],
		'37' => $lang['Usergroups'],
		'38' => $lang['Rank_Header'],
		'39' => $lang['Staff'],
		'40' => $lang['Change_Style'],
		'41' => $lang['Change_Lang'],
		'42' => $lang['Rss_news_feeds'],
		'43' => $lang['Register'],
		'44' => $lang['Login'] . ' - ' . $lang['Logout']
	);
	return $link_name_array;
}

function build_default_link_name($default_id)
{
	global $lang;
	$link_name = '';
	$link_name = build_default_link_array();
	return $link_name[$default_id];
}

function build_complete_url($default_id, $block_id, $link, $menu_icon)
{
	global $db, $cache, $template, $config, $userdata, $lang, $theme, $images;
	global $unread;

	switch ($default_id)
	{
		case '1':
			$menu_name = stripslashes(build_default_link_name($default_id));
			$menu_link = append_sid($link) . '?sid=' . $userdata['session_id'];
			$menu_url = '<a href="' . $menu_link . '">' . $menu_icon . $menu_name . '</a>';
			break;
		case '2':
			$menu_name = stripslashes(build_default_link_name($default_id));
			$menu_link = append_sid($link) . '?sid=' . $userdata['session_id'];
			$menu_url = '<a href="' . $menu_link . '">' . $menu_icon . $menu_name . '</a>';
			break;
		case '25':
			if($userdata['upi2db_access'])
			{
				if (empty($unread))
				{
					$unread = unread();
				}
				$u_display_new = index_display_new($unread);
				$upi2db_first_use = ($userdata['user_upi2db_datasync'] == '0') ? '<script type="text/javascript"><!--alert ("' . $lang['upi2db_first_use_txt'] . '")//--></script>' : '';
				$menu_url = '<a href="' . $u_display_new['u_url'] . '" title=" ' . $u_display_new['u_string_full'] . '">' . $menu_icon . $u_display_new['unread_string'] . '</a>';
			}
			else
			{
				$menu_url = '';
			}
			break;
		case '26':
			if($userdata['upi2db_access'])
			{
				if (empty($unread))
				{
					$unread = unread();
				}
				$u_display_new = index_display_new($unread);
				$upi2db_first_use = ($userdata['user_upi2db_datasync'] == '0') ? '<script type="text/javascript"><!--alert ("' . $lang['upi2db_first_use_txt'] . '")//--></script>' : '';
				$menu_url = '<a href="' . $u_display_new['m_url'] . '" title=" ' . $u_display_new['m_string_full'] . '">' . $menu_icon . $u_display_new['marked_string'] . '</a>';
			}
			else
			{
				$menu_url = '';
			}
			break;
		case '27':
			if($userdata['upi2db_access'])
			{
				if (empty($unread))
				{
					$unread = unread();
				}
				$u_display_new = index_display_new($unread);
				$upi2db_first_use = ($userdata['user_upi2db_datasync'] == '0') ? '<script type="text/javascript"><!--alert ("' . $lang['upi2db_first_use_txt'] . '")//--></script>' : '';
				$menu_url = '<a href="' . $u_display_new['p_url'] . '" title=" ' . $u_display_new['p_string_full'] . '">' . $menu_icon . $u_display_new['permanent_string'] . '</a>';
			}
			else
			{
				$menu_url = '';
			}
			break;
		case '28':
			if($userdata['upi2db_access'])
			{
				if (empty($unread))
				{
					$unread = unread();
				}
				$u_display_new = index_display_new($unread);
				$upi2db_first_use = ($userdata['user_upi2db_datasync'] == '0') ? '<script type="text/javascript"><!--alert ("' . $lang['upi2db_first_use_txt'] . '")//--></script>' : '';
				$menu_url = $menu_icon . $lang['Posts'] . ': <a href="search.' . PHP_EXT . '?search_id=newposts">' . $lang['New2'] . '</a>';
				$menu_url .= '&nbsp;&#8226;&nbsp;' . $u_display_new['u'] . '&nbsp;&#8226;&nbsp;' . $u_display_new['m'] . '&nbsp;&#8226;&nbsp;' . $u_display_new['p'];
				//$menu_url .= $u_display_new['u'] . $u_display_new['m'] . $u_display_new['p'];
			}
			else
			{
				$menu_url = '<a href="search.' . PHP_EXT . '?search_id=newposts">' . $menu_icon . $lang['New2'] . '</a>';
			}
			break;
		case '29':
			if ($config['enable_digests'])
			{
			$menu_name = stripslashes(build_default_link_name($default_id));
			$menu_link = append_sid($link);
			$menu_url = '<a href="' . $menu_link . '">' . $menu_icon . $menu_name . '</a>';
			}
			else
			{
				$menu_url = '';
			}
			break;
		case '40':
			if ($config['select_theme'])
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
				$menu_url = '<form name="ChangeTheme_' . $block_id . '" method="post" action="' . htmlspecialchars(urldecode($_SERVER['REQUEST_URI'])) . '">' . $menu_icon . $style_select . '</form>';
			}
			else
			{
				$menu_url = '';
			}
			break;
		case '41':
			if (($config['select_lang'] == true))
			{
				$menu_url = '';
				include_once(IP_ROOT_PATH . 'includes/functions_selects.' . PHP_EXT);
				$lang_installed = language_select($config['default_lang'], LANG_URL, 'language', true);
				while ( list($displayname) = @each($lang_installed) )
				{
					$lang_value = $displayname;
					$lang_name = ucwords($displayname);
					$lang_url = append_sid(CMS_PAGE_HOME . '?' . LANG_URL . '=' . $lang_value);
					$lang_icon = '<img src="language/lang_' . $displayname . '/flag.png" alt="" title="" style="vertical-align:middle;" />&nbsp;';
					$menu_url .= '<a href="' . $lang_url . '">' . $lang_icon . $lang_name . '&nbsp;<br /></a>';
				}
			}
			else
			{
				$menu_url = '';
			}
			break;
		case '42':
			$menu_url = '<a href="javascript:rss_news_help()">' . $menu_icon . $lang['Rss_news_feeds'] . '</a>';
			break;
		case '44':
			if (!$userdata['session_logged_in'])
			{
				$menu_link = 'login_ip.' . PHP_EXT . '?redirect=forum.' . PHP_EXT;
				$menu_name = $lang['Login'];
			}
			else
			{
				$menu_link = 'login_ip.' . PHP_EXT . '?logout=true&amp;sid=' . $userdata['session_id'];
				$menu_name = $lang['Logout'];
			}
			$menu_url = '<a href="' . $menu_link . '">' . $menu_icon . $menu_name . '</a>';
			break;
		default:
			$menu_name = stripslashes(build_default_link_name($default_id));
			$menu_link = append_sid($link);
			$menu_url = '<a href="' . $menu_link . '">' . $menu_icon . $menu_name . '</a>';
			break;
		}
	return $menu_url;
}

?>