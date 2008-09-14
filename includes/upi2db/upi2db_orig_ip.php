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
* BigRib (bigrib@gmx.de)
*
*/

/***************************************************************************
 *
 *   Included Functions:
 *   -------------------
 *
 * index_display_new
 *
 ***************************************************************************/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

//################################### index_display_new ##########################################
// Version 1.0.0

if(!function_exists(index_display_new))
{
	function index_display_new($unread)
	{
		global $lang, $images, $board_config, $unread_new_posts, $unread_edit_posts;

		$edit_posts = count($unread['edit_posts']) - $unread_edit_posts;
		$new_posts = count($unread['new_posts']) - $unread_new_posts;
		$unread_posts = $new_posts + $edit_posts;
		$always_read = count($unread['always_read']['topics']);
		$mark_unread = count($unread['mark_posts']);

		$max_perm_read = $board_config['upi2db_max_permanent_topics'];
		$max_mark = $board_config['upi2db_max_mark_posts'];

		$u_display_new['all'] = '<a href="' . append_sid(SEARCH_MG . '?search_id=upi2db&s2=new') . '" class="mainmenu" title="' . $lang['Neue_Beitraege'] . ' (' . $new_posts . ') / ' . $lang['Editierte_Beitraege'] . ' (' . $edit_posts . ')" alt="' . $lang['Neue_Beitraege'] . ' (' . $new_posts . ') / ' . $lang['Editierte_Beitraege'] . ' (' . $edit_posts . ')"> U: ' . $unread_posts . '</a>';
		$u_display_new['all'] .= '<a href="' . append_sid(SEARCH_MG . '?search_id=upi2db&s2=mark') . '" class="mainmenu" title="' . $lang['Ungelesen_Markiert'] . ' (' . $mark_unread . '/' . $max_mark . ')" alt="' . $lang['Ungelesen_Markiert'] . ' (' . $mark_unread . '/' . $max_mark . ')"> M: ' . $mark_unread . '</a>';
		$u_display_new['all'] .= '<a href="' . append_sid(SEARCH_MG . '?search_id=upi2db&s2=perm') . '" class="mainmenu" title="' . $lang['Permanent_Gelesen'] . ' (' . $always_read . '/' . $max_perm_read . ')" alt="' . $lang['Permanent_Gelesen'] . ' (' . $always_read . '/' . $max_perm_read . ')"> P: ' . $always_read . '</a>';

		$u_display_new['u'] = '<a href="' . append_sid(SEARCH_MG . '?search_id=upi2db&s2=new') . '" class="mainmenu" title="' . $lang['Neue_Beitraege'] . ' (' . $new_posts . ') / ' . $lang['Editierte_Beitraege'] . ' (' . $edit_posts . ')" alt="' . $lang['Neue_Beitraege'] . ' (' . $new_posts . ') / ' . $lang['Editierte_Beitraege'] . ' (' . $edit_posts . ')">' . $lang['upi2db_u'] . ' (' . $unread_posts . ')</a>';
		$u_display_new['m'] = '<a href="' . append_sid(SEARCH_MG . '?search_id=upi2db&s2=mark') . '" class="mainmenu" title="' . $lang['Ungelesen_Markiert'] . ' (' . $mark_unread . '/' . $max_mark . ')" alt="' . $lang['Ungelesen_Markiert'] . ' (' . $mark_unread . '/' . $max_mark . ')">' . $lang['upi2db_m'] . ' (' . $mark_unread . ')</a>';
		$u_display_new['p'] = '<a href="' . append_sid(SEARCH_MG . '?search_id=upi2db&s2=perm') . '" class="mainmenu" title="' . $lang['Permanent_Gelesen'] . ' (' . $always_read . '/' . $max_perm_read . ')" alt="' . $lang['Permanent_Gelesen'] . ' (' . $always_read . '/' . $max_perm_read . ')">' . $lang['upi2db_p'] . ' (' . $always_read . ')</a>';

		$u_display_new['unread'] = '<a href="' . append_sid(SEARCH_MG . '?search_id=upi2db&s2=new') . '" class="mainmenu" title="' . $lang['Neue_Beitraege'] . ' (' . $new_posts . ') / ' . $lang['Editierte_Beitraege'] . ' (' . $edit_posts . ')" alt="' . $lang['Neue_Beitraege'] . ' (' . $new_posts . ') / ' . $lang['Editierte_Beitraege'] . ' (' . $edit_posts . ')">' . $lang['upi2db_unread'] . ' (' . $unread_posts . ')</a>';
		$u_display_new['marked'] = '<a href="' . append_sid(SEARCH_MG . '?search_id=upi2db&s2=mark') . '" class="mainmenu" title="' . $lang['Ungelesen_Markiert'] . ' (' . $mark_unread . '/' . $max_mark . ')" alt="' . $lang['Ungelesen_Markiert'] . ' (' . $mark_unread . '/' . $max_mark . ')">' . $lang['upi2db_marked'] . ' (' . $mark_unread . ')</a>';
		$u_display_new['permanent'] = '<a href="' . append_sid(SEARCH_MG . '?search_id=upi2db&s2=perm') . '" class="mainmenu" title="' . $lang['Permanent_Gelesen'] . ' (' . $always_read . '/' . $max_perm_read . ')" alt="' . $lang['Permanent_Gelesen'] . ' (' . $always_read . '/' . $max_perm_read . ')">' . $lang['upi2db_perm_read'] . ' (' . $always_read . ')</a>';

		// Mighty Gorgon - Full Lang Explain For Quick Links - BEGIN
		$u_display_new['unread_string'] = $lang['upi2db_unread'] . ' (' . $unread_posts . ')';
		$u_display_new['u_string'] = $lang['upi2db_u'] . ' (' . $unread_posts . ')';
		$u_display_new['u_string_full'] = $lang['Neue_Beitraege'] . ' (' . $new_posts . ') / ' . $lang['Editierte_Beitraege'] . ' (' . $edit_posts . ')';
		$u_display_new['u_url'] = append_sid(SEARCH_MG . '?search_id=upi2db&amp;s2=new');

		$u_display_new['marked_string'] = $lang['upi2db_marked'] . ' (' . $mark_unread . ')';
		$u_display_new['m_string'] = $lang['upi2db_m'] . ' (' . $mark_unread . ')';
		$u_display_new['m_string_full'] = $lang['Ungelesen_Markiert'] . ' (' . $mark_unread . '/' . $max_mark . ')';
		$u_display_new['m_url'] = append_sid(SEARCH_MG.'?search_id=upi2db&amp;s2=mark');

		$u_display_new['permanent_string'] = $lang['upi2db_perm_read'] . ' (' . $always_read . ')';
		$u_display_new['p_string'] = $lang['upi2db_p'] . ' (' . $always_read . ')';
		$u_display_new['p_string_full'] = $lang['Permanent_Gelesen'] . ' (' . $always_read . '/' . $max_perm_read . ')';
		$u_display_new['p_url'] = append_sid(SEARCH_MG . '?search_id=upi2db&amp;s2=perm');;
		// Mighty Gorgon - Full Lang Explain For Quick Links - END

		return $u_display_new;
	}
}

?>