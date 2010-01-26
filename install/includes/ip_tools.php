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

$mode = $ip_functions->request_var('mode', '');
if ($mode == 'update_phpbb')
{
	$mode_test = 'update_phpbb';
}
else
{
	$mode_test = ((substr($mode, 0, 6) == 'update') ? 'update' : $mode);
}

if (!defined('IP_DB_UPDATE'))
{
	$mode_array = array('start', 'chmod', 'clean_old_files', 'fix_birthdays', 'fix_constants', 'fix_forums', 'fix_images_album', 'fix_posts', 'fix_signatures', 'ren_move_images', 'update_phpbb', 'update');
}
else
{
	// We force update to work from automatically detected version to avoid unwanted tables modifications by accidental use of database_update.php
	$mode = ((substr($mode, 0, 6) == 'update') ? 'update' : $mode);
	$mode_array = array('start', 'update_phpbb', 'update');
}

$mode_test = in_array($mode_test, $mode_array) ? $mode_test : $mode_array[0];

$action = $ip_functions->request_var('action', '');

switch ($mode_test)
{
	case 'chmod':

		$page_framework->page_header($lang['IcyPhoenix'], '', false, false);
		echo('<br /><br />' . "\n");
		$box_message = $lang['UpdateInProgress'];
		$page_framework->box('yellow', 'red', $box_message);
		echo('<br /><br />' . "\n");
		$chmod_errors = $page_framework->apply_chmod(false);
		echo('<br /><br />' . "\n");
		if ($chmod_errors)
		{
			$box_message = $lang['CHMOD_Files_Explain_Error'] . '<br /><br />' . sprintf($lang['ClickReturn'], '<a href="' . $ip_functions->append_sid(THIS_FILE) . '">', '</a>');
			$page_framework->box('red', 'red', $box_message);
		}
		else
		{
			$box_message = $lang['CHMOD_Files_Explain_Ok'] . '<br /><br />' . sprintf($lang['ClickReturn'], '<a href="' . $ip_functions->append_sid(THIS_FILE) . '">', '</a>');
			$page_framework->box('green', 'green', $box_message);
		}
		echo('<br clear="all" />' . "\n");
		echo('<br /><br />' . "\n");
		$page_framework->page_footer(false);
		break;

	case 'clean_old_files':

		$page_framework->page_header($lang['IcyPhoenix'], '', false, false);
		echo('<br /><br />' . "\n");
		if ($action == 'clean')
		{
			$box_message = $lang['CleaningInProgress'];
			$page_framework->box('yellow', 'red', $box_message);
			echo('<br /><br />' . "\n");
			echo($page_framework->clean_old_files($action));
			echo('<br clear="all" />' . "\n");
			echo('<br /><br />' . "\n");
			$box_message = $lang['FileDeletion_Complete'] . '<br /><br />' . sprintf($lang['ClickReturn'], '<a href="' . $ip_functions->append_sid(THIS_FILE) . '">', '</a>');
			$page_framework->box('green', 'green', $box_message);
			echo('<br clear="all" />' . "\n");
			echo('<br /><br />' . "\n");
		}
		else
		{
			$box_message = $lang['ActionUndone'];
			$page_framework->box('red', 'red', $box_message);
			echo('<br /><br />' . "\n");
			echo($page_framework->clean_old_files($action));
			echo('<br clear="all" />' . "\n");
			echo('<br /><br />' . "\n");
			$box_message = sprintf($lang['ClickReturn'], '<a href="' . $ip_functions->append_sid(THIS_FILE) . '">', '</a>');
			$page_framework->box('yellow', 'red', $box_message);
			echo('<br clear="all" />' . "\n");
			echo('<br /><br />' . "\n");
		}
		echo('<br /><br />' . "\n");
		$page_framework->page_footer(false);
		break;

	case 'fix_birthdays':

		$wip = $ip_functions->request_var('wip', false);

		$birthdays_number = $ip_functions->request_var('birthdays_number', 0);
		$birthday_start = $ip_functions->request_var('birthday_start', 0);
		$total_birthdays = $ip_functions->request_var('total_birthdays', 0);
		$total_birthdays_modified = $ip_functions->request_var('total_birthdays_modified', 0);

		if (substr($action, 0, 3) == 'fix')
		{
			$fix_results = $page_framework->fix_birthdays($action);

			$url_append = '';

			$url_append .= ($wip ? ('wip=true&amp;') : '');

			$url_append .= 'birthdays_number=' . $birthdays_number . '&amp;' . 'birthday_start=' . $birthday_start . '&amp;' . 'total_birthdays=' . $total_birthdays . '&amp;' . 'total_birthdays_modified=' . $total_birthdays_modified;

			$lang_append = '&amp;lang=' . $language;

			$tmp_url = THIS_FILE . '?' . 'mode=' . $mode . '&amp;action=' . $action . '&amp;' . $url_append . $lang_append;
			$meta_refresh = '';
			if ($wip !== false)
			{
				$meta_refresh = '<meta http-equiv="refresh" content="3;url=' . $ip_functions->append_sid($tmp_url) . '">';
			}

			$page_framework->page_header($lang['IcyPhoenix'], '', false, false, false, false, $meta_refresh);
			echo('<br /><br />' . "\n");

			echo('<br /><br />' . "\n");
			echo($fix_results);
			echo('<br clear="all" />' . "\n");
			echo('<br /><br />' . "\n");
			if ($wip === false)
			{
				$box_message = $lang['FixingBirthdaysComplete'] . '<br /><br />' . sprintf($lang['ClickReturn'], '<a href="' . $ip_functions->append_sid(THIS_FILE) . '">', '</a>');
				$page_framework->box('green', 'green', $box_message);
			}
			else
			{
				$box_message = $lang['FixingBirthdaysInProgress'] . '<br /><br />' . $lang['FixingBirthdaysInProgressRedirect'] . '<br /><br />' . sprintf($lang['FixingBirthdaysInProgressRedirectClick'], '<a href="' . $ip_functions->append_sid($tmp_url) . '">', '</a>');
				$page_framework->box('yellow', 'red', $box_message);
			}
			echo('<br clear="all" />' . "\n");
			echo('<br /><br />' . "\n");
		}
		else
		{
			$page_framework->page_header($lang['IcyPhoenix'], '', false, false);
			echo('<br /><br />' . "\n");
			$box_message = $lang['ActionUndone'];
			$page_framework->box('red', 'red', $box_message);
			echo('<br /><br />' . "\n");
			echo($page_framework->fix_birthdays($action));
			echo('<br clear="all" />' . "\n");
			echo('<br /><br />' . "\n");
			$box_message = sprintf($lang['ClickReturn'], '<a href="' . $ip_functions->append_sid(THIS_FILE) . '">', '</a>');
			$page_framework->box('yellow', 'red', $box_message);
			echo('<br clear="all" />' . "\n");
			echo('<br /><br />' . "\n");
		}
		echo('<br /><br />' . "\n");
		$page_framework->page_footer(false);
		break;

	case 'fix_constants':

		$page_framework->page_header($lang['IcyPhoenix'], '', false, false);
		echo('<br /><br />' . "\n");
		if (substr($action, 0, 3) == 'fix')
		{
			$box_message = $lang['FixingInProgress'];
			$page_framework->box('yellow', 'red', $box_message);
			echo('<br /><br />' . "\n");
			echo($page_framework->fix_constants($action));
			echo('<br clear="all" />' . "\n");
			echo('<br /><br />' . "\n");
			$box_message = $lang['FixingComplete'] . '<br /><br />' . sprintf($lang['ClickReturn'], '<a href="' . $ip_functions->append_sid(THIS_FILE) . '">', '</a>');
			$page_framework->box('green', 'green', $box_message);
			echo('<br clear="all" />' . "\n");
			echo('<br /><br />' . "\n");
		}
		else
		{
			$box_message = $lang['ActionUndone'];
			$page_framework->box('red', 'red', $box_message);
			echo('<br /><br />' . "\n");
			echo($page_framework->fix_constants($action));
			echo('<br clear="all" />' . "\n");
			echo('<br /><br />' . "\n");
			$box_message = sprintf($lang['ClickReturn'], '<a href="' . $ip_functions->append_sid(THIS_FILE) . '">', '</a>');
			$page_framework->box('yellow', 'red', $box_message);
			echo('<br clear="all" />' . "\n");
			echo('<br /><br />' . "\n");
		}
		echo('<br /><br />' . "\n");
		$page_framework->page_footer(false);
		break;

	case 'fix_forums':

		$page_framework->page_header($lang['IcyPhoenix'], '', false, false);
		echo('<br /><br />' . "\n");
		if (substr($action, 0, 3) == 'fix')
		{
			$box_message = $lang['FixingInProgress'];
			$page_framework->box('yellow', 'red', $box_message);
			echo('<br /><br />' . "\n");
			echo($page_framework->fix_forums($action));
			echo('<br clear="all" />' . "\n");
			echo('<br /><br />' . "\n");
			$box_message = $lang['FixingComplete'] . '<br /><br />' . sprintf($lang['ClickReturn'], '<a href="' . $ip_functions->append_sid(THIS_FILE) . '">', '</a>');
			$page_framework->box('green', 'green', $box_message);
			echo('<br clear="all" />' . "\n");
			echo('<br /><br />' . "\n");
		}
		else
		{
			$box_message = $lang['ActionUndone'];
			$page_framework->box('red', 'red', $box_message);
			echo('<br /><br />' . "\n");
			echo($page_framework->fix_forums($action));
			echo('<br clear="all" />' . "\n");
			echo('<br /><br />' . "\n");
			$box_message = sprintf($lang['ClickReturn'], '<a href="' . $ip_functions->append_sid(THIS_FILE) . '">', '</a>');
			$page_framework->box('yellow', 'red', $box_message);
			echo('<br clear="all" />' . "\n");
			echo('<br /><br />' . "\n");
		}
		echo('<br /><br />' . "\n");
		$page_framework->page_footer(false);
		break;

	case 'fix_images_album':

		$wip = $ip_functions->request_var('wip', false);
		$pics_number = $ip_functions->request_var('pics_number', 0);
		$pic_start = $ip_functions->request_var('pic_start', 0);
		$total_pics = $ip_functions->request_var('total_pics', 0);
		$total_pics_modified = $ip_functions->request_var('total_pics_modified', 0);

		if (substr($action, 0, 3) == 'fix')
		{
			$fix_results = $page_framework->fix_pics($action);

			$url_append = '';

			$url_append .= ($wip ? ('wip=true&amp;') : '');

			$url_append .= 'pics_number=' . $pics_number . '&amp;' . 'pic_start=' . $pic_start . '&amp;' . 'total_pics=' . $total_pics . '&amp;' . 'total_pics_modified=' . $total_pics_modified;

			$lang_append = '&amp;lang=' . $language;

			$tmp_url = THIS_FILE . '?' . 'mode=' . $mode . '&amp;action=' . $action . '&amp;' . $url_append . $lang_append;
			$meta_refresh = '';
			if ($wip !== false)
			{
				$meta_refresh = '<meta http-equiv="refresh" content="3;url=' . $ip_functions->append_sid($tmp_url) . '">';
			}

			$page_framework->page_header($lang['IcyPhoenix'], '', false, false, false, false, $meta_refresh);
			echo('<br /><br />' . "\n");

			echo('<br /><br />' . "\n");
			echo($fix_results);
			echo('<br clear="all" />' . "\n");
			echo('<br /><br />' . "\n");
			if ($wip === false)
			{
				$box_message = $lang['FixingPicsComplete'] . '<br /><br />' . sprintf($lang['ClickReturn'], '<a href="' . $ip_functions->append_sid(THIS_FILE) . '">', '</a>');
				$page_framework->box('green', 'green', $box_message);
			}
			else
			{
				$box_message = $lang['FixingPicsInProgress'] . '<br /><br />' . $lang['FixingPicsInProgressRedirect'] . '<br /><br />' . sprintf($lang['FixingPicsInProgressRedirectClick'], '<a href="' . $ip_functions->append_sid($tmp_url) . '">', '</a>');
				$page_framework->box('yellow', 'red', $box_message);
			}
			echo('<br clear="all" />' . "\n");
			echo('<br /><br />' . "\n");
		}
		else
		{
			$page_framework->page_header($lang['IcyPhoenix'], '', false, false);
			echo('<br /><br />' . "\n");
			$box_message = $lang['ActionUndone'];
			$page_framework->box('red', 'red', $box_message);
			echo('<br /><br />' . "\n");
			echo($page_framework->fix_pics($action));
			echo('<br clear="all" />' . "\n");
			echo('<br /><br />' . "\n");
			$box_message = sprintf($lang['ClickReturn'], '<a href="' . $ip_functions->append_sid(THIS_FILE) . '">', '</a>');
			$page_framework->box('yellow', 'red', $box_message);
			echo('<br clear="all" />' . "\n");
			echo('<br /><br />' . "\n");
		}
		echo('<br /><br />' . "\n");
		$page_framework->page_footer(false);
		break;

	case 'fix_posts':

		$wip = $ip_functions->request_var('wip', false);
		$search_word = urldecode($ip_functions->request_var('search_word', ''));
		$replacement_word = urldecode($ip_functions->request_var('replacement_word', ''));

		$remove_bbcode_uid = $ip_functions->request_var('remove_bbcode_uid', false);
		$remove_guess_bbcode_uid = $ip_functions->request_var('remove_guess_bbcode_uid', false);
		$fix_posted_images = $ip_functions->request_var('fix_posted_images', false);

		$posts_number = $ip_functions->request_var('posts_number', 0);
		$post_start = $ip_functions->request_var('post_start', 0);
		$total_posts = $ip_functions->request_var('total_posts', 0);
		$total_posts_modified = $ip_functions->request_var('total_posts_modified', 0);

		if (substr($action, 0, 3) == 'fix')
		{
			$fix_results = $page_framework->fix_posts($action);

			$url_append = '';

			$url_append .= ($wip ? ('wip=true&amp;') : '');
			$url_append .= ($remove_bbcode_uid ? ('remove_bbcode_uid=true&amp;') : '');
			$url_append .= ($remove_guess_bbcode_uid ? ('remove_guess_bbcode_uid=true&amp;') : '');
			$url_append .= ($fix_posted_images ? ('fix_posted_images=true&amp;') : '');

			$url_append .= 'search_word=' . urlencode($search_word) . '&amp;' . 'replacement_word=' . urlencode($replacement_word);

			$url_append .= '&amp;';
			$url_append .= 'posts_number=' . $posts_number . '&amp;' . 'post_start=' . $post_start . '&amp;' . 'total_posts=' . $total_posts . '&amp;' . 'total_posts_modified=' . $total_posts_modified;

			$lang_append = '&amp;lang=' . $language;

			$tmp_url = THIS_FILE . '?' . 'mode=' . $mode . '&amp;action=' . $action . '&amp;' . $url_append . $lang_append;
			$meta_refresh = '';
			if ($wip !== false)
			{
				$meta_refresh = '<meta http-equiv="refresh" content="3;url=' . $ip_functions->append_sid($tmp_url) . '">';
			}

			$page_framework->page_header($lang['IcyPhoenix'], '', false, false, false, false, $meta_refresh);
			echo('<br /><br />' . "\n");

			echo('<br /><br />' . "\n");
			echo($fix_results);
			echo('<br clear="all" />' . "\n");
			echo('<br /><br />' . "\n");
			if ($wip === false)
			{
				$box_message = $lang['FixingPostsComplete'] . '<br /><br />' . sprintf($lang['ClickReturn'], '<a href="' . $ip_functions->append_sid(THIS_FILE) . '">', '</a>');
				$page_framework->box('green', 'green', $box_message);
			}
			else
			{
				$box_message = $lang['FixingPostsInProgress'] . '<br /><br />' . $lang['FixingPostsInProgressRedirect'] . '<br /><br />' . sprintf($lang['FixingPostsInProgressRedirectClick'], '<a href="' . $ip_functions->append_sid($tmp_url) . '">', '</a>');
				$page_framework->box('yellow', 'red', $box_message);
			}
			echo('<br clear="all" />' . "\n");
			echo('<br /><br />' . "\n");
		}
		else
		{
			$page_framework->page_header($lang['IcyPhoenix'], '', false, false);
			echo('<br /><br />' . "\n");
			$box_message = $lang['ActionUndone'];
			$page_framework->box('red', 'red', $box_message);
			echo('<br /><br />' . "\n");
			echo($page_framework->fix_posts($action));
			echo('<br clear="all" />' . "\n");
			echo('<br /><br />' . "\n");
			$box_message = sprintf($lang['ClickReturn'], '<a href="' . $ip_functions->append_sid(THIS_FILE) . '">', '</a>');
			$page_framework->box('yellow', 'red', $box_message);
			echo('<br clear="all" />' . "\n");
			echo('<br /><br />' . "\n");
		}
		echo('<br /><br />' . "\n");
		$page_framework->page_footer(false);
		break;

	case 'fix_signatures':

		$wip = $ip_functions->request_var('wip', false);
		$search_word = urldecode($ip_functions->request_var('search_word', ''));
		$replacement_word = urldecode($ip_functions->request_var('replacement_word', ''));

		$remove_bbcode_uid = $ip_functions->request_var('remove_bbcode_uid', false);
		$remove_guess_bbcode_uid = $ip_functions->request_var('remove_guess_bbcode_uid', false);
		$fix_posted_images = $ip_functions->request_var('fix_posted_images', false);

		$posts_number = $ip_functions->request_var('posts_number', 0);
		$post_start = $ip_functions->request_var('post_start', 0);
		$total_posts = $ip_functions->request_var('total_posts', 0);
		$total_posts_modified = $ip_functions->request_var('total_posts_modified', 0);

		if (substr($action, 0, 3) == 'fix')
		{
			$fix_results = $page_framework->fix_signatures($action);

			$url_append = '';

			$url_append .= ($wip ? ('wip=true&amp;') : '');
			$url_append .= ($remove_bbcode_uid ? ('remove_bbcode_uid=true&amp;') : '');
			$url_append .= ($remove_guess_bbcode_uid ? ('remove_guess_bbcode_uid=true&amp;') : '');
			$url_append .= ($fix_posted_images ? ('fix_posted_images=true&amp;') : '');

			$url_append .= 'search_word=' . urlencode($search_word) . '&amp;' . 'replacement_word=' . urlencode($replacement_word);

			$url_append .= '&amp;';
			$url_append .= 'posts_number=' . $posts_number . '&amp;' . 'post_start=' . $post_start . '&amp;' . 'total_posts=' . $total_posts . '&amp;' . 'total_posts_modified=' . $total_posts_modified;

			$lang_append = '&amp;lang=' . $language;

			$tmp_url = THIS_FILE . '?' . 'mode=' . $mode . '&amp;action=' . $action . '&amp;' . $url_append . $lang_append;
			$meta_refresh = '';
			if ($wip !== false)
			{
				$meta_refresh = '<meta http-equiv="refresh" content="3;url=' . $ip_functions->append_sid($tmp_url) . '">';
			}

			$page_framework->page_header($lang['IcyPhoenix'], '', false, false, false, false, $meta_refresh);
			echo('<br /><br />' . "\n");

			echo('<br /><br />' . "\n");
			echo($fix_results);
			echo('<br clear="all" />' . "\n");
			echo('<br /><br />' . "\n");
			if ($wip === false)
			{
				$box_message = $lang['FixingSignaturesComplete'] . '<br /><br />' . sprintf($lang['ClickReturn'], '<a href="' . $ip_functions->append_sid(THIS_FILE) . '">', '</a>');
				$page_framework->box('green', 'green', $box_message);
			}
			else
			{
				$box_message = $lang['FixingSignaturesInProgress'] . '<br /><br />' . $lang['FixingPostsInProgressRedirect'] . '<br /><br />' . sprintf($lang['FixingPostsInProgressRedirectClick'], '<a href="' . $ip_functions->append_sid($tmp_url) . '">', '</a>');
				$page_framework->box('yellow', 'red', $box_message);
			}
			echo('<br clear="all" />' . "\n");
			echo('<br /><br />' . "\n");
		}
		else
		{
			$page_framework->page_header($lang['IcyPhoenix'], '', false, false);
			echo('<br /><br />' . "\n");
			$box_message = $lang['ActionUndone'];
			$page_framework->box('red', 'red', $box_message);
			echo('<br /><br />' . "\n");
			echo($page_framework->fix_signatures($action));
			echo('<br clear="all" />' . "\n");
			echo('<br /><br />' . "\n");
			$box_message = sprintf($lang['ClickReturn'], '<a href="' . $ip_functions->append_sid(THIS_FILE) . '">', '</a>');
			$page_framework->box('yellow', 'red', $box_message);
			echo('<br clear="all" />' . "\n");
			echo('<br /><br />' . "\n");
		}
		echo('<br /><br />' . "\n");
		$page_framework->page_footer(false);
		break;

	case 'ren_move_images':

		$wip = $ip_functions->request_var('wip', false);
		$pics_number = $ip_functions->request_var('pics_number', 0);
		$total_pics_modified = $ip_functions->request_var('total_pics_modified', 0);

		if (substr($action, 0, 3) == 'fix')
		{
			$fix_results = $page_framework->ren_move_images($action);

			$url_append = '';

			$url_append .= ($wip ? ('wip=true&amp;') : '');

			$url_append .= 'pics_number=' . $pics_number . '&amp;' . 'total_pics_modified=' . $total_pics_modified;

			$lang_append = '&amp;lang=' . $language;

			$tmp_url = THIS_FILE . '?' . 'mode=' . $mode . '&amp;action=' . $action . '&amp;' . $url_append . $lang_append;
			$meta_refresh = '';
			if ($wip !== false)
			{
				$meta_refresh = '<meta http-equiv="refresh" content="3;url=' . $ip_functions->append_sid($tmp_url) . '">';
			}

			$page_framework->page_header($lang['IcyPhoenix'], '', false, false, false, false, $meta_refresh);
			echo('<br /><br />' . "\n");

			echo('<br /><br />' . "\n");
			echo($fix_results);
			echo('<br clear="all" />' . "\n");
			echo('<br /><br />' . "\n");
			if ($wip === false)
			{
				$box_message = $lang['FixingPicsComplete'] . '<br /><br />' . sprintf($lang['ClickReturn'], '<a href="' . $ip_functions->append_sid(THIS_FILE) . '">', '</a>');
				$page_framework->box('green', 'green', $box_message);
			}
			else
			{
				$box_message = $lang['FixingPicsInProgress'] . '<br /><br />' . $lang['FixingPicsInProgressRedirect'] . '<br /><br />' . sprintf($lang['FixingPicsInProgressRedirectClick'], '<a href="' . $ip_functions->append_sid($tmp_url) . '">', '</a>');
				$page_framework->box('yellow', 'red', $box_message);
			}
			echo('<br clear="all" />' . "\n");
			echo('<br /><br />' . "\n");
		}
		else
		{
			$page_framework->page_header($lang['IcyPhoenix'], '', false, false);
			echo('<br /><br />' . "\n");
			$box_message = $lang['ActionUndone'];
			$page_framework->box('red', 'red', $box_message);
			echo('<br /><br />' . "\n");
			echo($page_framework->ren_move_images($action));
			echo('<br clear="all" />' . "\n");
			echo('<br /><br />' . "\n");
			$box_message = sprintf($lang['ClickReturn'], '<a href="' . $ip_functions->append_sid(THIS_FILE) . '">', '</a>');
			$page_framework->box('yellow', 'red', $box_message);
			echo('<br clear="all" />' . "\n");
			echo('<br /><br />' . "\n");
		}
		echo('<br /><br />' . "\n");
		$page_framework->page_footer(false);
		break;

	case 'update_phpbb':

		$page_framework->page_header($lang['IcyPhoenix'], '', false, false);
		echo('<br /><br />' . "\n");
		$box_message = $lang['UpdateInProgress'];
		$page_framework->box('yellow', 'red', $box_message);
		echo('<br /><br />' . "\n");
		include('schemas/sql_update_phpbb.' . PHP_EXT);
		$box_message = $lang['UpdateCompleted_phpBB'] . '<br /><br />' . sprintf($lang['ClickReturn'], '<a href="' . $ip_functions->append_sid(THIS_FILE) . '">', '</a>');
		$page_framework->box('green', 'green', $box_message);
		echo('<br /><br />' . "\n");
		$page_framework->page_footer(false);
		break;

	case 'update':

		$page_framework->page_header($lang['IcyPhoenix'], '', false, false);
		echo('<br /><br />' . "\n");
		$box_message = $lang['UpdateInProgress'];
		$page_framework->box('yellow', 'red', $box_message);
		echo('<br /><br />' . "\n");
		if ($current_phpbb_version != $phpbb_version)
		{
			include('schemas/sql_update_phpbb.' . PHP_EXT);
		}
		include('schemas/sql_update_ip.' . PHP_EXT);
		$sql_results_ok = '';
		$sql_results_error = '';
		// Executing SQL
		for($i = 0; $i < sizeof($sql); $i++)
		{
			$db->sql_return_on_error(true);
			$result = $db->sql_query($sql[$i]);
			$db->sql_return_on_error(false);
			if (!$result)
			{
				$error = $db->sql_error();
				$sql_results_error .= '<li>' . $sql[$i] . '<br /> +++ <span style="color:' . $page_framework->color_error . ';"><b>' . $lang['Error'] . ':</b></span> ' . htmlspecialchars($error['message']) . '<br /><br /></li>' . "\n";
			}
			else
			{
				$sql_results_ok .= '<li>' . $sql[$i] . '<br /> +++ <span style="color:' . $page_framework->color_ok . ';"><b>' . $lang['Successful'] . '</b></span><br /><br /></li>' . "\n";
			}
		}
		if (defined('FIX_FORUMS') && FIX_FORUMS)
		{
			$page_framework->convert_forums();
			empty_cache_folders();
		}
		$page_framework->table_begin($lang['IcyPhoenix'] . ' - ' . $lang['UpdateInProgress'], 'row-post');
		echo('<div class="post-text">' . "\n");
		echo('<b>' . $lang['DBUpdate_Errors'] . ':</b><br />' . "\n");
		$page_framework->spoiler('sql_error', '<ul>' . $sql_results_error . '</ul>' . "\n");
		echo('<br /><br />' . "\n");
		echo('<b>' . $lang['DBUpdate_Success'] . ':</b><br />' . "\n");
		$page_framework->spoiler('sql_ok', '<ul>' . $sql_results_ok . '</ul>' . "\n");
		echo('</div>' . "\n");
		$page_framework->table_end();
		echo('<br /><br /><br /><br />' . "\n");
		$box_message = $lang['UpdateCompleted'] . '<br /><br />' . sprintf($lang['ClickReturn'], '<a href="' . $ip_functions->append_sid(THIS_FILE) . '">', '</a>');
		$page_framework->box('green', 'green', $box_message);
		echo('<br /><br />' . "\n");
		$page_framework->page_footer(false);
		break;

	default:

		$page_framework->page_header($lang['IcyPhoenix'], '', false, false);
		$page_framework->output_lang_select(THIS_FILE, true);
		$page_framework->stats_box($current_ip_version, $current_phpbb_version);
		$page_framework->box_upgrade_info();
		if (!defined('IP_DB_UPDATE'))
		{
			$page_framework->box_ip_tools();
		}
		$page_framework->page_footer(false);
}

exit;

?>