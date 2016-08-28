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
*
*/

define('IN_PM', true);
// MG Cash MOD For IP - BEGIN
define('IN_CASHMOD', true);
// MG Cash MOD For IP - END
// Added to optimize memory for attachments
define('ATTACH_DISPLAY', true);
define('ATTACH_PM', true);
define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_post.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_users.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_zebra.' . PHP_EXT);

// Adding CPL_NAV only if needed
define('PARSE_CPL_NAV', true);

// Is PM disabled?
if (!empty($config['privmsg_disable']))
{
	message_die(GENERAL_MESSAGE, 'PM_disabled');
}

$html_entities_match = array('#&(?!(\#[0-9]+;))#', '#<#', '#>#', '#"#');
$html_entities_replace = array('&amp;', '&lt;', '&gt;', '&quot;');

// Parameters
$privmsg_id = request_var(POST_POST_URL, 0);

$mode = request_var('mode', '');

$start = request_var('start', 0);
$start = ($start < 0) ? 0 : $start;

$sid = request_post_var('sid', '');

$submit = (isset($_POST['post'])) ? true : false;
$submit_search = (isset($_POST['usersubmit'])) ? true : false;
$submit_msgdays = (isset($_POST['submit_msgdays'])) ? true : false;
$cancel = (isset($_POST['cancel'])) ? true : false;
$preview = (isset($_POST['preview'])) ? true : false;
$confirm = (isset($_POST['confirm'])) ? true : false;
$delete = (isset($_POST['delete'])) ? true : false;
$delete_all = (isset($_POST['deleteall'])) ? true : false;
$download = (isset($_POST['download'])) ? true : false;
$save = (isset($_POST['save'])) ? true : false;

$draft = request_var('draft', '');
$draft_mode = request_var('draft_mode', '');
$draft_confirm = !empty($_POST['draft_confirm']) ? true : false;
$draft = (!empty($draft) || $draft_confirm) ? true : false;
$draft_id = request_var('d', 0);

if (($config['allow_drafts'] == true) && ($draft_mode == 'draft_load') && ($draft_id > 0))
{
	$sql = "SELECT d.*
		FROM " . DRAFTS_TABLE . " d
		WHERE d.draft_id = '" . $draft_id . "'
		AND d.user_id = '" . $user->data['user_id'] . "'
		LIMIT 1";
	$result = $db->sql_query($sql);

	if ($draft_row = $db->sql_fetchrow($result))
	{
		$db->sql_freeresult($result);
		$draft_subject = $draft_row['draft_subject'];
		$draft_message = htmlspecialchars_decode($draft_row['draft_message'], ENT_COMPAT);
		$preview = true;
	}
}

$search_type = request_var('searchvar', '');
$search_value = request_var('searchvalue', '');
$search_value_tmp = request_var('searchvalue2', '');
$search_value = (!empty($search_value) && !empty($search_value_tmp)) ? $search_value_tmp : $search_value;

$refresh = $preview || $submit_search || ($draft && !$draft_confirm);

$mark_list = request_var('mark', array(0));

$folders_array = array('inbox', 'outbox', 'sentbox', 'savebox');
$folder = request_var('folder', 'inbox');
$folder = check_var_value($folder, $folders_array);

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup();
// End session management

// Cancel
if ($cancel)
{
	redirect(append_sid(CMS_PAGE_PRIVMSG . '?folder=' . $folder, true));
}

// Check search fields
$search_list = array(
	'author' => 'author is',
	'subject' => 'subject contains',
	);
if(empty($search_value) || empty($search_type) || intval($search_type) || !isset($search_list[$search_type]))
{
	$search_type = '';
}
if(empty($search_type))
{
	$search_value = '';
}
$template->vars['SEARCH_TYPES'] = '';
foreach($search_list as $var => $value)
{
	$template->vars['SEARCH_TYPES'] .= '<option value="' . $var . '"' . ($search_type === $var ? ' selected="selected"' : '') . '>' . $value . '</option>';
}
$template->vars['SEARCH_VALUE'] = htmlspecialchars($search_value);


$error = false;

// Define the box image links
$inbox_img = ($folder != 'inbox' || $mode != '') ? '<a href="' . append_sid(CMS_PAGE_PRIVMSG . '?folder=inbox') . '"><img src="' . $images['pm_inbox'] . '" alt="' . $lang['Inbox'] . '" title="' . $lang['Inbox'] . '" /></a>' : '<img src="' . $images['pm_inbox'] . '" alt="' . $lang['Inbox'] . '" title="' . $lang['Inbox'] . '" />';
$inbox_url = ($folder != 'inbox' || $mode != '') ? '<a href="' . append_sid(CMS_PAGE_PRIVMSG . '?folder=inbox') . '">' . $lang['Inbox'] . '</a>' : $lang['Inbox'];

$outbox_img = ($folder != 'outbox' || $mode != '') ? '<a href="' . append_sid(CMS_PAGE_PRIVMSG . '?folder=outbox') . '"><img src="' . $images['pm_outbox'] . '" alt="' . $lang['Outbox'] . '" title="' . $lang['Outbox'] . '" /></a>' : '<img src="' . $images['pm_outbox'] . '" alt="' . $lang['Outbox'] . '" title="' . $lang['Outbox'] . '" />';
$outbox_url = ($folder != 'outbox' || $mode != '') ? '<a href="' . append_sid(CMS_PAGE_PRIVMSG . '?folder=outbox') . '">' . $lang['Outbox'] . '</a>' : $lang['Outbox'];

$sentbox_img = ($folder != 'sentbox' || $mode != '') ? '<a href="' . append_sid(CMS_PAGE_PRIVMSG . '?folder=sentbox') . '"><img src="' . $images['pm_sentbox'] . '" alt="' . $lang['Sentbox'] . '" title="' . $lang['Sentbox'] . '" /></a>' : '<img src="' . $images['pm_sentbox'] . '" alt="' . $lang['Sentbox'] . '" title="' . $lang['Sentbox'] . '" />';
$sentbox_url = ($folder != 'sentbox' || $mode != '') ? '<a href="' . append_sid(CMS_PAGE_PRIVMSG . '?folder=sentbox') . '">' . $lang['Sentbox'] . '</a>' : $lang['Sentbox'];

$savebox_img = ($folder != 'savebox' || $mode != '') ? '<a href="' . append_sid(CMS_PAGE_PRIVMSG . '?folder=savebox') . '"><img src="' . $images['pm_savebox'] . '" alt="' . $lang['Savebox'] . '" title="' . $lang['Savebox'] . '" /></a>' : '<img src="' . $images['pm_savebox'] . '" alt="' . $lang['Savebox'] . '" title="' . $lang['Savebox'] . '" />';
$savebox_url = ($folder != 'savebox' || $mode != '') ? '<a href="' . append_sid(CMS_PAGE_PRIVMSG . '?folder=savebox') . '">' . $lang['Savebox'] . '</a>' : $lang['Savebox'];
execute_privmsgs_attachment_handling($mode);

// Start main
if ($mode == 'newpm')
{
	$link_name = '';
	if ($mode == 'post')
	{
		$link_name = $lang['Send_a_new_message'];
	}
	elseif ($mode == 'reply')
	{
		$link_name = $lang['Send_a_reply'];
	}
	elseif ($mode == 'edit')
	{
		$link_name = $lang['Edit_message'];
	}
	$nav_server_url = create_server_url();
	$breadcrumbs['address'] = $lang['Nav_Separator'] . '<a href="' . $nav_server_url . append_sid(CMS_PAGE_PRIVMSG . '?folder=inbox') . '"' . (!empty($link_name) ? '' : ' class="nav-current"') . '>' . $lang['Private_Messaging'] . '</a>' . (!empty($link_name) ? ($lang['Nav_Separator'] . '<a class="nav-current" href="#">' . $link_name . '</a>') : '');
	include_once(IP_ROOT_PATH . 'includes/users_zebra_block.' . PHP_EXT);

	if ($user->data['session_logged_in'])
	{
		if ($user->data['user_new_privmsg'])
		{
			$l_new_message = ($user->data['user_new_privmsg'] == 1) ? $lang['You_new_pm'] : $lang['You_new_pms'];
		}
		else
		{
			$l_new_message = $lang['You_no_new_pm'];
		}

		$l_new_message .= '<br /><br />' . sprintf($lang['Click_view_privmsg'], '<a href="' . append_sid(CMS_PAGE_PRIVMSG . '?folder=inbox') . '" onclick="jump_to_inbox();return false;" target="_new">', '</a>');
	}
	else
	{
		$l_new_message = $lang['Login_check_pm'];
	}

	$template->assign_vars(array(
		'L_CLOSE_WINDOW' => $lang['Close_window'],
		'L_MESSAGE' => $l_new_message
		)
	);

	$gen_simple_header = true;
	full_page_generation('privmsgs_popup.tpl', $lang['Private_Messaging'], '', '');
}
elseif ($mode == 'read')
{
	if (!empty($_GET[POST_POST_URL]))
	{
		$privmsgs_id = intval($_GET[POST_POST_URL]);
	}
	else
	{
		message_die(GENERAL_ERROR, $lang['No_post_id']);
	}

	if (!$user->data['session_logged_in'])
	{
		redirect(append_sid(CMS_PAGE_LOGIN . '?redirect=privmsg.' . PHP_EXT . '&folder=' . $folder . '&mode=' . $mode . '&' . POST_POST_URL . '=' . $privmsgs_id, true));
	}

	$ranks_array = $cache->obtain_ranks(false);

	// SQL to pull appropriate message, prevents nosey people
	// reading other peoples messages ... hopefully!
	switch($folder)
	{
		case 'inbox':
			$l_box_name = $lang['Inbox'];
			$pm_sql_user = "AND pm.privmsgs_to_userid = " . $user->data['user_id'] . "
				AND (pm.privmsgs_type = " . PRIVMSGS_READ_MAIL . "
					OR pm.privmsgs_type = " . PRIVMSGS_NEW_MAIL . "
					OR pm.privmsgs_type = " . PRIVMSGS_UNREAD_MAIL . ")";
			break;
		case 'outbox':
			$l_box_name = $lang['Outbox'];
			$pm_sql_user = "AND pm.privmsgs_from_userid =  " . $user->data['user_id'] . "
				AND (pm.privmsgs_type = " . PRIVMSGS_NEW_MAIL . "
					OR pm.privmsgs_type = " . PRIVMSGS_UNREAD_MAIL . ") ";
			break;
		case 'sentbox':
			$l_box_name = $lang['Sentbox'];
			$pm_sql_user = "AND pm.privmsgs_from_userid =  " . $user->data['user_id'] . "
				AND pm.privmsgs_type = " . PRIVMSGS_SENT_MAIL;
			break;
		case 'savebox':
			$l_box_name = $lang['Savebox'];
			$pm_sql_user = "AND ((pm.privmsgs_to_userid = " . $user->data['user_id'] . "
					AND pm.privmsgs_type = " . PRIVMSGS_SAVED_IN_MAIL . ")
				OR (pm.privmsgs_from_userid = " . $user->data['user_id'] . "
					AND pm.privmsgs_type = " . PRIVMSGS_SAVED_OUT_MAIL . ")
				)";
			break;
		default:
			message_die(GENERAL_ERROR, $lang['No_such_folder']);
			break;
	}
// BEGIN PM Navigation MOD
	if(($_GET['view'] == 'next') || ($_GET['view'] == 'prev'))
	{
		$sql_condition = ($_GET['view'] == 'next') ? '>' : '<';
		$sql_ordering = ($_GET['view'] == 'next') ? 'ASC' : 'DESC';

		$sql_nav = "SELECT pm.privmsgs_id FROM ". PRIVMSGS_TABLE ." pm, ". PRIVMSGS_TABLE ." p2
			WHERE p2.privmsgs_id = $privmsgs_id
			$pm_sql_user
			AND pm.privmsgs_date $sql_condition p2.privmsgs_date
			ORDER BY pm.privmsgs_date $sql_ordering LIMIT 1" ;
		$result_nav = $db->sql_query($sql_nav);
		if ($row = $db->sql_fetchrow($result_nav))
		{
			$privmsgs_id = intval($row['privmsgs_id']);
		}
		else
		{
			$output_message = (($_GET['view'] == 'next') ? $lang['No_newer_pm'] : $lang['No_older_pm']) . '<br /><br />' . sprintf($lang['Click_return_inbox'], '<a href="' . append_sid(CMS_PAGE_PRIVMSG . '?folder=inbox') . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid(CMS_PAGE_FORUM) . '">', '</a>');
			message_die(GENERAL_MESSAGE, $output_message);
		}
	}
	// END PM Navigation MOD

	// Major query obtains the message ...
	$sql = "SELECT u.username AS username_1, u.user_id AS user_id_1, u.user_active AS user_active_1, u.user_color AS user_color_1, u2.username AS username_2, u2.user_id AS user_id_2, u2.user_active AS user_active_2, u2.user_color AS user_color_2, u.user_posts, u.user_from, u.user_website, u.user_email, u.user_icq, u.user_aim, u.user_yim, u.user_skype, u.user_regdate, u.user_msnm, u.user_allow_viewemail, u.user_rank, u.user_sig, u.user_avatar, u.user_avatar_type, u.user_allowavatar, u.user_allow_viewonline, u.user_session_time, u.user_from, u.user_gender, pm.*
		FROM " . PRIVMSGS_TABLE . " pm, " . USERS_TABLE . " u, " . USERS_TABLE . " u2
		WHERE pm.privmsgs_id = $privmsgs_id
			$pm_sql_user
			AND u.user_id = pm.privmsgs_from_userid
			AND u2.user_id = pm.privmsgs_to_userid";
	$result = $db->sql_query($sql);

	// Did the query return any data?
	if (!($privmsg = $db->sql_fetchrow($result)))
	{
		redirect(append_sid(CMS_PAGE_PRIVMSG . '?folder=' . $folder, true));
	}

	$privmsg_id = $privmsg['privmsgs_id'];

	// Is this a new message in the inbox? If it is then save a copy in the posters sent box
	if ((($privmsg['privmsgs_type'] == PRIVMSGS_NEW_MAIL) || ($privmsg['privmsgs_type'] == PRIVMSGS_UNREAD_MAIL)) && ($folder == 'inbox'))
	{
		// Update appropriate counter
		switch ($privmsg['privmsgs_type'])
		{
			case PRIVMSGS_NEW_MAIL:
				$sql = "user_new_privmsg = user_new_privmsg - 1";
				break;
			case PRIVMSGS_UNREAD_MAIL:
				$sql = "user_unread_privmsg = user_unread_privmsg - 1";
				break;
		}

		$sql = "UPDATE " . USERS_TABLE . "
			SET $sql
			WHERE user_id = " . $user->data['user_id'];
		$result = $db->sql_query($sql);

		$sql = "UPDATE " . PRIVMSGS_TABLE . "
			SET privmsgs_type = " . PRIVMSGS_READ_MAIL . "
			WHERE privmsgs_id = " . $privmsg['privmsgs_id'];
		$result = $db->sql_query($sql);

		// Check to see if the poster has a 'full' sent box
		$sql = "SELECT COUNT(privmsgs_id) AS sent_items, MIN(privmsgs_date) AS oldest_post_time
			FROM " . PRIVMSGS_TABLE . "
			WHERE privmsgs_type = " . PRIVMSGS_SENT_MAIL . "
				AND privmsgs_from_userid = " . $privmsg['privmsgs_from_userid'];
		$result = $db->sql_query($sql);

		if ($sent_info = $db->sql_fetchrow($result))
		{
			if ($config['max_sentbox_privmsgs'] && ($sent_info['sent_items'] >= $config['max_sentbox_privmsgs']))
			{
				$sql = "SELECT privmsgs_id FROM " . PRIVMSGS_TABLE . "
					WHERE privmsgs_type = " . PRIVMSGS_SENT_MAIL . "
						AND privmsgs_date = " . $sent_info['oldest_post_time'] . "
						AND privmsgs_from_userid = " . $privmsg['privmsgs_from_userid'];
				$result = $db->sql_query($sql);
				$old_privmsgs_id = $db->sql_fetchrow($result);
				$old_privmsgs_id = $old_privmsgs_id['privmsgs_id'];

				if (!empty($old_privmsgs_id))
				{
					$sql = "DELETE FROM " . PRIVMSGS_TABLE . "
						WHERE privmsgs_id = " . $old_privmsgs_id;
					$result = $db->sql_query($sql);
				}
			}
		}

		//
		// This makes a copy of the post and stores it as a SENT message from the sender. Perhaps
		// not the most DB friendly way but a lot easier to manage, besides the admin will be able to
		// set limits on numbers of storable posts for users... hopefully!
		//
		$sql = "INSERT INTO " . PRIVMSGS_TABLE . " (privmsgs_type, privmsgs_subject, privmsgs_text, privmsgs_from_userid, privmsgs_to_userid, privmsgs_date, privmsgs_ip, privmsgs_enable_html, privmsgs_enable_bbcode, privmsgs_enable_smilies, privmsgs_enable_autolinks_acronyms, privmsgs_attach_sig)
			VALUES (" . PRIVMSGS_SENT_MAIL . ", '" . $db->sql_escape($privmsg['privmsgs_subject']) . "', '" . $db->sql_escape($privmsg['privmsgs_text']) . "', " . $privmsg['privmsgs_from_userid'] . ", " . $privmsg['privmsgs_to_userid'] . ", " . $privmsg['privmsgs_date'] . ", '" . $privmsg['privmsgs_ip'] . "', " . $privmsg['privmsgs_enable_html'] . ", " . $privmsg['privmsgs_enable_bbcode'] . ", " . $privmsg['privmsgs_enable_smilies'] . ", " . $privmsg['privmsgs_enable_autolinks_acronyms'] . ", " . $privmsg['privmsgs_attach_sig'] . ")";
		$result = $db->sql_query($sql);
		$privmsg_sent_id = $db->sql_nextid();
	}
	$attachment_mod['pm']->duplicate_attachment_pm($privmsg['privmsgs_attachment'], $privmsg['privmsgs_id'], $privmsg_sent_id);

	// Pick a folder, any folder, so long as it's one below ...
	$post_urls = array(
		'post' => append_sid(CMS_PAGE_PRIVMSG . '?mode=post'),
		'reply' => append_sid(CMS_PAGE_PRIVMSG . '?mode=reply&amp;' . POST_POST_URL . '=' . $privmsg_id),
		'quote' => append_sid(CMS_PAGE_PRIVMSG . '?mode=quote&amp;' . POST_POST_URL . '=' . $privmsg_id),
		'edit' => append_sid(CMS_PAGE_PRIVMSG . '?mode=edit&amp;' . POST_POST_URL . '=' . $privmsg_id)
	);
	$post_icons = array(
		'post_img' => '<a href="' . $post_urls['post'] . '"><img src="' . $images['pm_postmsg'] . '" alt="' . $lang['Post_new_pm'] . '" /></a>',
		'post' => '<a href="' . $post_urls['post'] . '">' . $lang['Post_new_pm'] . '</a>',
		'reply_img' => '<a href="' . $post_urls['reply'] . '"><img src="' . $images['pm_replymsg'] . '" alt="' . $lang['Post_reply_pm'] . '" /></a>',
		'reply' => '<a href="' . $post_urls['reply'] . '">' . $lang['Post_reply_pm'] . '</a>',
		'quote_img' => '<a href="' . $post_urls['quote'] . '"><img src="' . $images['pm_quotemsg'] . '" alt="' . $lang['Post_quote_pm'] . '" /></a>',
		'quote' => '<a href="' . $post_urls['quote'] . '">' . $lang['Post_quote_pm'] . '</a>',
		'edit_img' => '<a href="' . $post_urls['edit'] . '"><img src="' . $images['pm_editmsg'] . '" alt="' . $lang['Edit_pm'] . '" /></a>',
		'edit' => '<a href="' . $post_urls['edit'] . '">' . $lang['Edit_pm'] . '</a>'
	);

	if ($folder == 'inbox')
	{
		$post_img = $post_icons['post_img'];
		$reply_img = $post_icons['reply_img'];
		$quote_img = $post_icons['quote_img'];
		$edit_img = '';
		$post = $post_icons['post'];
		$reply = $post_icons['reply'];
		$quote = $post_icons['quote'];
		$edit = '';
		$post_url = $post_urls['post'];
		$reply_url = $post_urls['reply'];
		$quote_url = $post_urls['quote'];
		$edit_url = '';
		$l_box_name = $lang['Inbox'];
	}
	elseif ($folder == 'outbox')
	{
		$post_img = $post_icons['post_img'];
		$reply_img = '';
		$quote_img = '';
		$edit_img = $post_icons['edit_img'];
		$post = $post_icons['post'];
		$reply = '';
		$quote = '';
		$edit = $post_icons['edit'];
		$post_url = $post_urls['post'];
		$reply_url = '';
		$quote_url = '';
		$edit_url = $post_urls['edit'];
		$l_box_name = $lang['Outbox'];
	}
	elseif ($folder == 'savebox')
	{
		if ($privmsg['privmsgs_type'] == PRIVMSGS_SAVED_IN_MAIL)
		{
			$post_img = $post_icons['post_img'];
			$reply_img = $post_icons['reply_img'];
			$quote_img = $post_icons['quote_img'];
			$edit_img = '';
			$post = $post_icons['post'];
			$reply = $post_icons['reply'];
			$quote = $post_icons['quote'];
			$edit = '';
			$post_url = $post_urls['post'];
			$reply_url = $post_urls['reply'];
			$quote_url = $post_urls['quote'];
			$edit_url = '';
		}
		else
		{
			$post_img = $post_icons['post_img'];
			$reply_img = '';
			$quote_img = '';
			$edit_img = '';
			$post = $post_icons['post'];
			$reply = '';
			$quote = '';
			$edit = '';
			$post_url = $post_urls['post'];
			$reply_url = '';
			$quote_url = '';
			$edit_url = '';
		}
		$l_box_name = $lang['Saved'];
	}
	elseif ($folder == 'sentbox')
	{
		$post_img = $post_icons['post_img'];
		$reply_img = '';
		$quote_img = '';
		$edit_img = '';
		$post = $post_icons['post'];
		$reply = '';
		$quote = '';
		$edit = '';
		$post_url = $post_urls['post'];
		$reply_url = '';
		$quote_url = '';
		$edit_url = '';
		$l_box_name = '';
	}

	$s_hidden_fields = '<input type="hidden" name="mark[]" value="' . $privmsgs_id . '" />';

	$nav_server_url = create_server_url();
	$breadcrumbs['address'] = $lang['Nav_Separator'] . '<a href="' . $nav_server_url . append_sid(CMS_PAGE_PRIVMSG . '?folder=inbox') . '">' . $lang['Private_Messaging'] . '</a>' . $lang['Nav_Separator'] . '<a class="nav-current" href="#">' . $privmsg['privmsgs_subject'] . '</a>';
	$breadcrumbs['bottom_right_links'] = '<a href="' . append_sid(CMS_PAGE_PRIVMSG . '?folder=' . $folder . '&amp;mode=' . $mode . '&amp;' . POST_POST_URL . '=' . $privmsgs_id . '&amp;view=prev', true) . '">' . $lang['Previous_privmsg'] . '</a> &bull; <a href="' . append_sid(CMS_PAGE_PRIVMSG . '?folder=' . $folder . '&amp;mode=' . $mode . '&amp;' . POST_POST_URL . '=' . $privmsgs_id . '&amp;view=next', true) . '">' . $lang['Next_privmsg'] . '</a>';
	$skip_nav_cat = true;
	include_once(IP_ROOT_PATH . 'includes/users_zebra_block.' . PHP_EXT);

	make_jumpbox(CMS_PAGE_VIEWFORUM);

	$template->assign_vars(array(
		'INBOX_IMG' => $inbox_img,
		'SENTBOX_IMG' => $sentbox_img,
		'OUTBOX_IMG' => $outbox_img,
		'SAVEBOX_IMG' => $savebox_img,
		'INBOX' => $inbox_url,
		'SENTBOX' => $sentbox_url,
		'OUTBOX' => $outbox_url,
		'SAVEBOX' => $savebox_url,
		'BOX_NAME' => $l_box_name,

		'L_QUICK_REPLY' => $lang['Quick_Reply'],
		'L_EDIT_PM' => $lang['Edit_pm'],
		'L_QUOTE_PM' => $lang['Post_quote_pm'],
		'L_POST_PM' => $lang['Post_new_pm'],
		'L_REPLY_PM' => $lang['Post_reply_pm'],
		'EDIT_PM_URL' => $edit_url,
		'POST_PM_URL' => $post_url,
		'QUOTE_PM_URL' => $quote_url,
		'REPLY_PM_URL' => $reply_url,

		'POST_PM_IMG' => $post_img,
		'REPLY_PM_IMG' => $reply_img,
		'EDIT_PM_IMG' => $edit_img,
		'QUOTE_PM_IMG' => $quote_img,
		'POST_PM' => $post,
		'REPLY_PM' => $reply,
		'EDIT_PM' => $edit,
		'QUOTE_PM' => $quote,
		'IMG_QUICK_QUOTE' => $images['icon_quick_quote'],
		'IMG_OFFTOPIC' => $images['icon_offtopic'],

		'L_MESSAGE' => $lang['Message'],
		'L_INBOX' => $lang['Inbox'],
		'L_OUTBOX' => $lang['Outbox'],
		'L_SENTBOX' => $lang['Sent'],
		'L_SAVEBOX' => $lang['Saved'],
		'L_FLAG' => $lang['Flag'],
		'L_SUBJECT' => $lang['Subject'],
		'L_QUICK_QUOTE' => $lang['QuickQuote'],
		'L_OFFTOPIC' => $lang['OffTopic'],
		'L_POSTED' => $lang['Posted'],
		'L_DATE' => $lang['Date'],
		'L_FROM' => $lang['From'],
		'L_TO' => $lang['To'],
		'L_SAVE_MSG' => $lang['Save_message'],
		'L_DELETE_MSG' => $lang['Delete_message'],
		'L_PM' => $lang['Private_Message'],
		'L_EMAIL' => $lang['Email'],
		'L_POSTS' => $lang['Posts'],
		'L_CONTACTS' => $lang['User_Contacts'],
		'L_WEBSITE' => $lang['Website'],
		'L_FROM' => $lang['Location'],
		'L_ONLINE_STATUS' => $lang['Online_status'],
		'L_USER_WWW' => $lang['Website'],
		'L_USER_EMAIL' => $lang['Send_Email'],
		'L_USER_PROFILE' => $lang['Profile'],

		// BEGIN PM Navigation MOD
		'L_PRIVMSG_NEXT' => $lang['Next_privmsg'],
		'L_PRIVMSG_PREVIOUS' => $lang['Previous_privmsg'],
		'U_PRIVMSG_NEXT' => append_sid(CMS_PAGE_PRIVMSG . '?folder=' . $folder . '&amp;mode=' . $mode . '&amp;' . POST_POST_URL . '=' . $privmsgs_id . '&amp;view=next', true),
		'U_PRIVMSG_PREVIOUS' => append_sid(CMS_PAGE_PRIVMSG . '?folder=' . $folder . '&amp;mode=' . $mode . '&amp;' . POST_POST_URL . '=' . $privmsgs_id . '&amp;view=prev', true),
		// END PM Navigation MOD

		'S_PRIVMSGS_ACTION' => append_sid(CMS_PAGE_PRIVMSG . '?folder=' . $folder),
		'S_HIDDEN_FIELDS' => $s_hidden_fields
		)
	);

	$username_from = colorize_username($privmsg['user_id_1'], $privmsg['username_1'], $privmsg['user_color_1'], $privmsg['user_active_1']);
	$user_id_from = $privmsg['user_id_1'];
	$username_to = colorize_username($privmsg['user_id_2'], $privmsg['username_2'], $privmsg['user_color_2'], $privmsg['user_active_2']);
	$user_id_to = $privmsg['user_id_2'];

	// Needed for attachments... do not remove!
	$template_to_parse = 'privmsgs_read_body.tpl';
	$template->set_filenames(array('body' => $template_to_parse));
	init_display_pm_attachments($privmsg['privmsgs_attachment']);

	$post_date = create_date_ip($config['default_dateformat'], $privmsg['privmsgs_date'], $config['board_timezone']);

	$privmsg['user_id'] = $privmsg['user_id_1'];
	$privmsg['username'] = $privmsg['username_1'];
	$privmsg['user_color'] = $privmsg['user_color_1'];
	$privmsg['user_active'] = $privmsg['user_active_1'];
	$user_info = array();
	$user_info = generate_user_info($privmsg);
	foreach ($user_info as $k => $v)
	{
		$$k = $v;
	}

	$poster_avatar = $user_info['avatar'];
	$poster_posts = ($privmsg['user_id'] != ANONYMOUS) ? $lang['Posts'] . ': ' . $privmsg['user_posts'] : '';
	$poster_from = ($privmsg['user_from']) ? $lang['Location'] . ': ' . $privmsg['user_from'] : '';
	$poster_joined = ($privmsg['user_id'] != ANONYMOUS) ? $lang['Joined'] . ': ' . create_date($lang['JOINED_DATE_FORMAT'], $privmsg['user_regdate'], $config['board_timezone']) : '';

	// Mighty Gorgon - Quick Quote - BEGIN
	$look_up_array = array(
		'\"',
		'"',
		"<",
		">",
		"\n",
		chr(13),
	);

	$replacement_array = array(
		'&q_mg;',
		'\"',
		"&lt_mg;",
		"&gt_mg;",
		"\\n",
		"",
	);

	$plain_message = $privmsg['privmsgs_text'];
	$plain_message = strtr($plain_message, array_flip(get_html_translation_table(HTML_ENTITIES)));
	$plain_message = censor_text($plain_message);
	$plain_message = str_replace($look_up_array, $replacement_array, $plain_message);
	// Mighty Gorgon - Quick Quote - END

	// Processing of post
	$post_subject = $privmsg['privmsgs_subject'];
	$private_message = $privmsg['privmsgs_text'];

	if ($config['allow_sig'])
	{
		$user_sig = ($privmsg['privmsgs_from_userid'] == $user->data['user_id']) ? $user->data['user_sig'] : $privmsg['user_sig'];
	}
	else
	{
		$user_sig = '';
	}

	// If the board has HTML off but the post has HTML on then we process it, else leave it alone
	if (!$config['allow_html'])
	{
		if ($user_sig != '' && $privmsg['privmsgs_enable_sig'] && $user->data['user_allowhtml'])
		{
			$user_sig = preg_replace('#(<)([\/]?.*?)(>)#is', "&lt;\\2&gt;", $user_sig);
		}

		if ($privmsg['privmsgs_enable_html'])
		{
			$private_message = preg_replace('#(<)([\/]?.*?)(>)#is', "&lt;\\2&gt;", $private_message);
		}
	}

	if (($user_sig != '') && $privmsg['privmsgs_attach_sig'])
	{
		$bbcode->allow_html = $config['allow_html'];
		$bbcode->allow_bbcode = $config['allow_bbcode'] ? true : false;
		$bbcode->allow_smilies = $config['allow_smilies'];
		$bbcode->is_sig = true;
		$user_sig = $bbcode->parse($user_sig);
		$bbcode->is_sig = false;
	}

	$bbcode->allow_html = (($config['allow_html'] && $user->data['user_allowhtml']) || $config['allow_html_only_for_admins']) && $privmsg['privmsgs_enable_html'];
	$bbcode->allow_bbcode = $config['allow_bbcode'] ? true : false;
	$bbcode->allow_smilies = $config['allow_smilies'] && $privmsg['privmsgs_enable_smilies'];
	$private_message = $bbcode->parse($private_message);

	if ($privmsg['privmsgs_attach_sig'] && $user_sig != '')
	{
		$signature = '<br />' . $config['sig_line'] . '<br />' . $user_sig;
	}

	$post_subject = censor_text($post_subject);
	$private_message = censor_text($private_message);

	//Acronyms, AutoLinks - BEGIN
	if ($privmsg['privmsgs_enable_autolinks_acronyms'])
	{
		$private_message = $bbcode->acronym_pass($private_message);
		$private_message = $bbcode->autolink_text($private_message, '999999');
	}
	//Acronyms, AutoLinks -END

	// Mighty Gorgon - Multiple Ranks - BEGIN
	$user_ranks = generate_ranks($privmsg, $ranks_array);
	if (($user_ranks['rank_01_html'] == '') && ($user_ranks['rank_01_img_html'] == '') && ($user_ranks['rank_02_html'] == '') && ($user_ranks['rank_02_img_html'] == '') && ($user_ranks['rank_03_html'] == '') && ($user_ranks['rank_03_img_html'] == '') && ($user_ranks['rank_04_html'] == '') && ($user_ranks['rank_04_img_html'] == '') && ($user_ranks['rank_05_html'] == '') && ($user_ranks['rank_05_img_html'] == ''))
	{
		$user_ranks['rank_01_html'] = '&nbsp;';
	}
	// Mighty Gorgon - Multiple Ranks - END

	$poster_rank = $user_ranks['rank_01_html'];
	$rank_image = $user_ranks['rank_01_img_html'];

	// Dump it to the templating engine
	$template->assign_vars(array(
		'MESSAGE_TO' => $username_to,
		'RECIPIENT_QQ' => $privmsg['username_1'],
		'PM_ID' => $privmsgs_id,
		'MESSAGE_FROM' => $username_from,
		'RANK_IMAGE' => $rank_image,
		'POSTER_JOINED' => $poster_joined,
		'POSTER_POSTS' => $poster_posts,
		'POSTER_FROM' => $poster_from,
		'POSTER_AVATAR' => $poster_avatar,
		'POST_SUBJECT' => $post_subject,
		'POST_DATE' => $post_date,
		'MESSAGE' => $private_message,
		'PLAIN_MESSAGE' => $plain_message,
		'SIGNATURE' => $signature,
		'POSTER_RANK' => $poster_rank,
		'RANK_IMAGE' => $rank_image,
		'POSTER_GENDER' => $user_info['gender'],

		'PROFILE_URL' => $user_info['profile_url'],
		'PROFILE_IMG' => $user_info['profile_img'],
		'PROFILE' => $user_info['profile'],
		'PM_URL' => $user_info['pm_url'],
		'PM_IMG' => $user_info['pm_img'],
		'PM' => $user_info['pm'],
		'SEARCH_URL' => $user_info['search_url'],
		'SEARCH_IMG' => $user_info['search_img'],
		'SEARCH' => $user_info['search'],
		'IP_URL' => $user_info['ip_url'],
		'IP_IMG' => $user_info['ip_img'],
		'IP' => $user_info['ip'],
		'EMAIL_URL' => $user_info['email_url'],
		'EMAIL_IMG' => $user_info['email_img'],
		'EMAIL' => $user_info['email'],
		'WWW_URL' => $user_info['www_url'],
		'WWW_IMG' => $user_info['www_img'],
		'WWW' => $user_info['www'],
		'AIM_URL' => $user_info['aim_url'],
		'AIM_IMG' => $user_info['aim_img'],
		'AIM' => $user_info['aim'],
		'ICQ_STATUS_IMG' => $user_info['icq_status_img'],
		'ICQ_URL' => $user_info['icq_url'],
		'ICQ_IMG' => $user_info['icq_img'],
		'ICQ' => $user_info['icq'],
		'MSN_URL' => $user_info['msn_url'],
		'MSN_IMG' => $user_info['msn_img'],
		'MSN' => $user_info['msn'],
		'SKYPE_URL' => $user_info['skype_url'],
		'SKYPE_IMG' => $user_info['skype_img'],
		'SKYPE' => $user_info['skype'],
		'YIM_URL' => $user_info['yahoo_url'],
		'YIM_IMG' => $user_info['yahoo_img'],
		'YIM' => $user_info['yahoo'],
		'ONLINE_STATUS_URL' => $user_info['online_status_url'],
		'ONLINE_STATUS_CLASS' => $user_info['online_status_class'],
		'ONLINE_STATUS_IMG' => $user_info['online_status_img'],
		'ONLINE_STATUS' => $user_info['online_status'],
		'L_ONLINE_STATUS' => $user_info['online_status_lang'],
		'L_READ_MESSAGE' => $lang['Read_pm'],
		)
	);

	if (!function_exists('generate_smilies_row'))
	{
		include_once(IP_ROOT_PATH . 'includes/functions_bbcode.' . PHP_EXT);
	}
	generate_smilies_row();
	$template->assign_vars(array(
		'L_SMILEYS_MORE' => $lang['More_emoticons'],
		'U_SMILEYS_MORE' => append_sid('posting.' . PHP_EXT . '?mode=smilies'),
		)
	);

	full_page_generation($template_to_parse, $lang['Read_pm'], '', '');
}
elseif (($delete && $mark_list) || $delete_all)
{
	if (!$user->data['session_logged_in'])
	{
		redirect(append_sid(CMS_PAGE_LOGIN . '?redirect=privmsg.' . PHP_EXT . '&folder=inbox', true));
	}

	if (isset($mark_list) && !is_array($mark_list))
	{
		// Set to empty array instead of '0' if nothing is selected.
		$mark_list = array();
	}

	if (!$confirm)
	{
		$s_hidden_fields = '<input type="hidden" name="mode" value="' . $mode . '" />';
		$s_hidden_fields .= (isset($_POST['delete'])) ? '<input type="hidden" name="delete" value="true" />' : '<input type="hidden" name="deleteall" value="true" />';
		$s_hidden_fields .= '<input type="hidden" name="sid" value="' . $user->data['session_id'] . '" />';

		for($i = 0; $i < sizeof($mark_list); $i++)
		{
			$s_hidden_fields .= '<input type="hidden" name="mark[]" value="' . intval($mark_list[$i]) . '" />';
		}

		// Output confirmation page
		$nav_server_url = create_server_url();
		$breadcrumbs['address'] = $lang['Nav_Separator'] . '<a href="' . $nav_server_url . append_sid(CMS_PAGE_PRIVMSG . '?folder=inbox') . '" class="nav-current">' . $lang['Private_Messaging'] . '</a>';
		$template->assign_vars(array(
			'MESSAGE_TITLE' => $lang['Information'],
			'MESSAGE_TEXT' => (sizeof($mark_list) == 1) ? $lang['Confirm_delete_pm'] : $lang['Confirm_delete_pms'],

			'L_YES' => $lang['Yes'],
			'L_NO' => $lang['No'],

			'S_CONFIRM_ACTION' => append_sid(CMS_PAGE_PRIVMSG . '?folder=' . $folder),
			'S_HIDDEN_FIELDS' => $s_hidden_fields
			)
		);
		full_page_generation('confirm_body.tpl', $lang['Confirm'], '', '');
	}
	elseif ($confirm && $sid === $user->data['session_id'])
	{
		if ($delete_all)
		{
			switch($folder)
			{
				case 'inbox':
					$delete_type = "privmsgs_to_userid = " . $user->data['user_id'] . " AND (
					privmsgs_type = " . PRIVMSGS_READ_MAIL . " OR privmsgs_type = " . PRIVMSGS_NEW_MAIL . " OR privmsgs_type = " . PRIVMSGS_UNREAD_MAIL . ")";
					break;

				case 'outbox':
					$delete_type = "privmsgs_from_userid = " . $user->data['user_id'] . " AND (privmsgs_type = " . PRIVMSGS_NEW_MAIL . " OR privmsgs_type = " . PRIVMSGS_UNREAD_MAIL . ")";
					break;

				case 'sentbox':
					$delete_type = "privmsgs_from_userid = " . $user->data['user_id'] . " AND privmsgs_type = " . PRIVMSGS_SENT_MAIL;
					break;

				case 'savebox':
					$delete_type = "((privmsgs_from_userid = " . $user->data['user_id'] . "
						AND privmsgs_type = " . PRIVMSGS_SAVED_OUT_MAIL . ")
					OR (privmsgs_to_userid = " . $user->data['user_id'] . "
						AND privmsgs_type = " . PRIVMSGS_SAVED_IN_MAIL . "))";
					break;
			}

			$sql = "SELECT privmsgs_id
				FROM " . PRIVMSGS_TABLE . "
				WHERE $delete_type";
			$result = $db->sql_query($sql);

			while ($row = $db->sql_fetchrow($result))
			{
				$mark_list[] = $row['privmsgs_id'];
			}

			unset($delete_type);
		}
		$attachment_mod['pm']->delete_all_pm_attachments($mark_list);
		if (sizeof($mark_list))
		{
			$delete_sql_id = '';
			for ($i = 0; $i < sizeof($mark_list); $i++)
			{
				$delete_sql_id .= (($delete_sql_id != '') ? ', ' : '') . intval($mark_list[$i]);
			}

			if ($folder == 'inbox' || $folder == 'outbox')
			{
				switch ($folder)
				{
					case 'inbox':
						$sql = "privmsgs_to_userid = " . $user->data['user_id'];
						break;
					case 'outbox':
						$sql = "privmsgs_from_userid = " . $user->data['user_id'];
						break;
				}

				// Get information relevant to new or unread mail
				// so we can adjust users counters appropriately
				$sql = "SELECT privmsgs_to_userid, privmsgs_type
					FROM " . PRIVMSGS_TABLE . "
					WHERE privmsgs_id IN ($delete_sql_id)
						AND $sql
						AND privmsgs_type IN (" . PRIVMSGS_NEW_MAIL . ", " . PRIVMSGS_UNREAD_MAIL . ")";
				$result = $db->sql_query($sql);

				if ($row = $db->sql_fetchrow($result))
				{
					$update_users = $update_list = array();

					do
					{
						switch ($row['privmsgs_type'])
						{
							case PRIVMSGS_NEW_MAIL:
								$update_users['new'][$row['privmsgs_to_userid']]++;
								break;

							case PRIVMSGS_UNREAD_MAIL:
								$update_users['unread'][$row['privmsgs_to_userid']]++;
								break;
						}
					}
					while ($row = $db->sql_fetchrow($result));

					if (sizeof($update_users))
					{
						while (list($type, $users) = each($update_users))
						{
							while (list($user_id, $dec) = each($users))
							{
								$update_list[$type][$dec][] = $user_id;
							}
						}
						unset($update_users);

						while (list($type, $dec_ary) = each($update_list))
						{
							switch ($type)
							{
								case 'new':
									$type = "user_new_privmsg";
									break;

								case 'unread':
									$type = "user_unread_privmsg";
									break;
							}

							while (list($dec, $user_ary) = each($dec_ary))
							{
								$user_ids = implode(', ', $user_ary);

								$sql = "UPDATE " . USERS_TABLE . "
									SET $type = $type - $dec
									WHERE user_id IN ($user_ids)";
								$db->sql_query($sql);
							}
						}
						unset($update_list);
					}
				}
				$db->sql_freeresult($result);
			}

			// Delete the messages
			$delete_sql = "DELETE FROM " . PRIVMSGS_TABLE . "
				WHERE privmsgs_id IN ($delete_sql_id)
					AND ";

			switch($folder)
			{
				case 'inbox':
					$delete_sql .= "privmsgs_to_userid = " . $user->data['user_id'] . " AND (
						privmsgs_type = " . PRIVMSGS_READ_MAIL . " OR privmsgs_type = " . PRIVMSGS_NEW_MAIL . " OR privmsgs_type = " . PRIVMSGS_UNREAD_MAIL . ")";
					break;

				case 'outbox':
					$delete_sql .= "privmsgs_from_userid = " . $user->data['user_id'] . " AND (
						privmsgs_type = " . PRIVMSGS_NEW_MAIL . " OR privmsgs_type = " . PRIVMSGS_UNREAD_MAIL . ")";
					break;

				case 'sentbox':
					$delete_sql .= "privmsgs_from_userid = " . $user->data['user_id'] . " AND privmsgs_type = " . PRIVMSGS_SENT_MAIL;
					break;

				case 'savebox':
					$delete_sql .= "((privmsgs_from_userid = " . $user->data['user_id'] . "
						AND privmsgs_type = " . PRIVMSGS_SAVED_OUT_MAIL . ")
					OR (privmsgs_to_userid = " . $user->data['user_id'] . "
						AND privmsgs_type = " . PRIVMSGS_SAVED_IN_MAIL . "))";
					break;
			}
			$db->sql_query($delete_sql);
		}
	}
}
elseif ($download && $mark_list)
{
	if (!$user->data['session_logged_in'])
	{
		$header_location = (@preg_match('/Microsoft|WebSTAR|Xitami/', getenv('SERVER_SOFTWARE'))) ? 'Refresh: 0; URL=' : 'Location: ';
		header($header_location . append_sid(CMS_PAGE_LOGIN .'?redirect=privmsg.' . PHP_EXT . '&folder=inbox', true));
		exit;
	}

	switch($folder)
	{
		case 'inbox':
			$disp_folder = $lang['Inbox'];
		break;

		case 'outbox':
			$disp_folder = $lang['Outbox'];
		break;

		case 'sentbox':
			$disp_folder = $lang['Sentbox'];
		break;

		case 'savebox':
			$disp_folder = $lang['Savebox'];
		break;
	}

	if (sizeof($mark_list))
	{
		$i = 0;
		$crlf = "\r\n";
		$pmtext = $lang['Private_Messaging'] . ' (' . $config['sitename'] . ')' . $crlf;
		$user_dateformat = ($user->data['user_dateformat']) ? $user->data['user_dateformat'] : $config['default_dateformat'];
		$pmtext .= $disp_folder . ' (' . gmdate($user_dateformat) . ')' . $crlf;
		while($mark_list[$i] != '')
		{
			$sql = "SELECT pm.privmsgs_date, pm.privmsgs_subject, pm.privmsgs_text, us.username, us.user_id
				FROM " . PRIVMSGS_TABLE . " pm, " . USERS_TABLE . " us
				WHERE pm.privmsgs_id = " . $mark_list[$i] . "
				AND us.user_id = pm.privmsgs_from_userid";
			$db->sql_return_on_error(true);
			$result = $db->sql_query($sql);
			$db->sql_return_on_error(false);
			if ($result)
			{
				$db_row = $db->sql_fetchrow($result);
				$tmpmsg = wordwrap(htmlspecialchars_decode($db_row['privmsgs_text'], ENT_COMPAT), 78, $crlf);
				$from = (($folder == 'inbox') || ($folder == 'savebox')) ? $lang['From'] : $lang['To'];
				$pmtext .= '------------------------------------------------------------------------------' . $crlf;
				$pmtext .= $lang['Mailbox'] . ': ' . $user->data['username'] . $crlf;
				$pmtext .= $from . ': ' . $db_row['username'] . $crlf;
				$pmtext .= $lang['Posted'] . ': ' . gmdate($user_dateformat, $db_row['privmsgs_date']) . $crlf;
				$pmtext .= $lang['Subject'] . ': ' . htmlspecialchars_decode($db_row['privmsgs_subject'], ENT_COMPAT) . $crlf . $crlf;
				$pmtext .= $tmpmsg . $crlf;
			}
			else
			{
				print $sql . '<p>';
				message_die(GENERAL_ERROR, 'Could not read private message info', '', __LINE__, __FILE__, $sql);
			}
			$i++;
		}
		$filename = $config['sitename'] . '_' . $disp_folder . '_' . gmdate('Ymd');
		$filename = preg_replace('/[^A-Za-z0-9]+/', '_', strtolower($filename)) . '.txt';
		header('Content-Type: text/x-delimtext; name="' . $filename . '"');
		header('Content-Disposition: attachment;filename=' . $filename);
		header('Content-Transfer-Encoding: plain/text');
		header('Content-Length: ' . strlen($pmtext));
		print $pmtext;
		exit;
	}
}
elseif ($save && $mark_list && ($folder != 'savebox') && ($folder != 'outbox'))
{
	if (!$user->data['session_logged_in'])
	{
		redirect(append_sid(CMS_PAGE_LOGIN . '?redirect=privmsg.' . PHP_EXT . '&folder=inbox', true));
	}

	if (sizeof($mark_list))
	{
		// See if recipient is at their savebox limit
		$sql = "SELECT COUNT(privmsgs_id) AS savebox_items, MIN(privmsgs_date) AS oldest_post_time
			FROM " . PRIVMSGS_TABLE . "
			WHERE ((privmsgs_to_userid = " . $user->data['user_id'] . "
					AND privmsgs_type = " . PRIVMSGS_SAVED_IN_MAIL . ")
				OR (privmsgs_from_userid = " . $user->data['user_id'] . "
					AND privmsgs_type = " . PRIVMSGS_SAVED_OUT_MAIL . "))";
		$result = $db->sql_query($sql);

		if ($saved_info = $db->sql_fetchrow($result))
		{
			if ($config['max_savebox_privmsgs'] && $saved_info['savebox_items'] >= $config['max_savebox_privmsgs'])
			{
				$sql = "SELECT privmsgs_id FROM " . PRIVMSGS_TABLE . "
					WHERE ((privmsgs_to_userid = " . $user->data['user_id'] . "
								AND privmsgs_type = " . PRIVMSGS_SAVED_IN_MAIL . ")
							OR (privmsgs_from_userid = " . $user->data['user_id'] . "
								AND privmsgs_type = " . PRIVMSGS_SAVED_OUT_MAIL . "))
						AND privmsgs_date = " . $saved_info['oldest_post_time'];
				$result = $db->sql_query($sql);
				$old_privmsgs_id = $db->sql_fetchrow($result);
				$old_privmsgs_id = $old_privmsgs_id['privmsgs_id'];

				$sql = "DELETE FROM " . PRIVMSGS_TABLE . "
					WHERE privmsgs_id = $old_privmsgs_id";
				$result = $db->sql_query($sql);
			}
		}

		$saved_sql_id = '';
		for ($i = 0; $i < sizeof($mark_list); $i++)
		{
			$saved_sql_id .= (($saved_sql_id != '') ? ', ' : '') . intval($mark_list[$i]);
		}

		// Process request
		$saved_sql = "UPDATE " . PRIVMSGS_TABLE;

		// Decrement read/new counters if appropriate
		if ($folder == 'inbox' || $folder == 'outbox')
		{
			switch ($folder)
			{
				case 'inbox':
					$sql = "privmsgs_to_userid = " . $user->data['user_id'];
					break;
				case 'outbox':
					$sql = "privmsgs_from_userid = " . $user->data['user_id'];
					break;
			}

			// Get information relevant to new or unread mail
			// so we can adjust users counters appropriately
			$sql = "SELECT privmsgs_to_userid, privmsgs_type
				FROM " . PRIVMSGS_TABLE . "
				WHERE privmsgs_id IN ($saved_sql_id)
					AND $sql
					AND privmsgs_type IN (" . PRIVMSGS_NEW_MAIL . ", " . PRIVMSGS_UNREAD_MAIL . ")";
			$result = $db->sql_query($sql);

			if ($row = $db->sql_fetchrow($result))
			{
				$update_users = $update_list = array();

				do
				{
					switch ($row['privmsgs_type'])
					{
						case PRIVMSGS_NEW_MAIL:
							$update_users['new'][$row['privmsgs_to_userid']]++;
							break;

						case PRIVMSGS_UNREAD_MAIL:
							$update_users['unread'][$row['privmsgs_to_userid']]++;
							break;
					}
				}
				while ($row = $db->sql_fetchrow($result));

				if (sizeof($update_users))
				{
					while (list($type, $users) = each($update_users))
					{
						while (list($user_id, $dec) = each($users))
						{
							$update_list[$type][$dec][] = $user_id;
						}
					}
					unset($update_users);

					while (list($type, $dec_ary) = each($update_list))
					{
						switch ($type)
						{
							case 'new':
								$type = "user_new_privmsg";
								break;

							case 'unread':
								$type = "user_unread_privmsg";
								break;
						}

						while (list($dec, $user_ary) = each($dec_ary))
						{
							$user_ids = implode(', ', $user_ary);

							$sql = "UPDATE " . USERS_TABLE . "
								SET $type = $type - $dec
								WHERE user_id IN ($user_ids)";
							$result_tmp = $db->sql_query($sql);
						}
					}
					unset($update_list);
				}
			}
			$db->sql_freeresult($result);
		}

		switch ($folder)
		{
			case 'inbox':
				$saved_sql .= " SET privmsgs_type = " . PRIVMSGS_SAVED_IN_MAIL . "
					WHERE privmsgs_to_userid = " . $user->data['user_id'] . "
						AND (privmsgs_type = " . PRIVMSGS_READ_MAIL . "
							OR privmsgs_type = " . PRIVMSGS_NEW_MAIL . "
							OR privmsgs_type = " . PRIVMSGS_UNREAD_MAIL . ")";
				break;

			case 'outbox':
				$saved_sql .= " SET privmsgs_type = " . PRIVMSGS_SAVED_OUT_MAIL . "
					WHERE privmsgs_from_userid = " . $user->data['user_id'] . "
						AND (privmsgs_type = " . PRIVMSGS_NEW_MAIL . "
							OR privmsgs_type = " . PRIVMSGS_UNREAD_MAIL . ") ";
				break;

			case 'sentbox':
				$saved_sql .= " SET privmsgs_type = " . PRIVMSGS_SAVED_OUT_MAIL . "
					WHERE privmsgs_from_userid = " . $user->data['user_id'] . "
						AND privmsgs_type = " . PRIVMSGS_SENT_MAIL;
				break;
		}

		$saved_sql .= " AND privmsgs_id IN ($saved_sql_id)";

		$result_saved = $db->sql_query($saved_sql);

		redirect(append_sid(CMS_PAGE_PRIVMSG . '?folder=savebox', true));
	}
}
elseif ($submit || $refresh || ($mode != ''))
{
	if (!$user->data['session_logged_in'])
	{
		$user_id = (isset($_GET[POST_USERS_URL])) ? '&' . POST_USERS_URL . '=' . intval($_GET[POST_USERS_URL]) : '';
		redirect(append_sid(CMS_PAGE_LOGIN . '?redirect=privmsg.' . PHP_EXT . '&folder=' . $folder . '&mode=' . $mode . $user_id, true));
	}

	// Toggles
	if (!$config['allow_html'])
	{
		$html_on = 0;
	}
	else
	{
		$html_on = ($submit || $refresh) ? ((!empty($_POST['disable_html'])) ? 0 : 1) : $user->data['user_allowhtml'];
	}

	$acro_auto_on = ($submit || $refresh) ? ((!empty($_POST['disable_acro_auto'])) ? 0 : 1) : 1;

	if (!$config['allow_bbcode'])
	{
		$bbcode_on = 0;
	}
	else
	{
		$bbcode_on = ($submit || $refresh) ? ((!empty($_POST['disable_bbcode'])) ? 0 : 1) : $user->data['user_allowbbcode'];
	}

	if (!$config['allow_smilies'])
	{
		$smilies_on = 0;
	}
	else
	{
		$smilies_on = ($submit || $refresh) ? ((!empty($_POST['disable_smilies'])) ? 0 : 1) : $user->data['user_allowsmile'];
	}

	$attach_sig = ($submit || $refresh) ? ((!empty($_POST['attach_sig'])) ? 1 : 0) : $user->data['user_attachsig'];
	$user_sig = ($user->data['user_sig'] != '' && $config['allow_sig']) ? $user->data['user_sig'] : '';

	if (($submit) && ($mode != 'edit') && ($user->data['user_level'] != ADMIN))
	{
		// Flood control
		$sql = "SELECT MAX(privmsgs_date) AS last_post_time
			FROM " . PRIVMSGS_TABLE . "
			WHERE privmsgs_from_userid = " . $user->data['user_id'];
		$db->sql_return_on_error(true);
		$result = $db->sql_query($sql);
		$db->sql_return_on_error(false);
		if ($result)
		{
			$db_row = $db->sql_fetchrow($result);

			$last_post_time = $db_row['last_post_time'];
			$current_time = time();

			if (($current_time - $last_post_time) < $config['flood_interval'])
			{
				message_die(GENERAL_MESSAGE, $lang['Flood_Error']);
			}
		}
		// End Flood control
	}

	if ($submit && ($mode == 'edit'))
	{
		$sql = 'SELECT privmsgs_from_userid
			FROM ' . PRIVMSGS_TABLE . '
			WHERE privmsgs_id = ' . (int) $privmsg_id . '
				AND privmsgs_from_userid = ' . $user->data['user_id'];
		$result = $db->sql_query($sql);

		if (!($row = $db->sql_fetchrow($result)))
		{
			message_die(GENERAL_MESSAGE, $lang['No_such_post']);
		}
		$db->sql_freeresult($result);

		unset($row);
	}

	if ($submit || ($draft && $draft_confirm))
	{
		$username = request_var('username', '', true);
		$username = htmlspecialchars_decode($username, ENT_COMPAT);
		$subject = !empty($draft_subject) ? $draft_subject : request_post_var('subject', '', true);
		$message = !empty($draft_message) ? $draft_message : htmlspecialchars_decode(request_post_var('message', '', true), ENT_COMPAT);

		// session id check
		if (($sid == '') || ($sid != $user->data['session_id']))
		{
			$error = true;
			$error_msg .= ((!empty($error_msg)) ? '<br />' : '') . $lang['Session_invalid'];
		}

		if (!empty($username))
		{
			$to_username = phpbb_clean_username($username);
			$sql = "SELECT user_id, user_notify_pm, user_email, user_lang, user_active
				FROM " . USERS_TABLE . "
				WHERE username_clean = '" . $db->sql_escape(utf8_clean_string($to_username)) . "'
					AND user_id <> " . ANONYMOUS;
			$db->sql_return_on_error(true);
			$result = $db->sql_query($sql);
			$db->sql_return_on_error(false);
			if (!$result)
			{
				$error = true;
				$error_msg = $lang['NO_USER'];
			}

			if (!($to_userdata = $db->sql_fetchrow($result)))
			{
				$error = true;
				$error_msg = $lang['NO_USER'];
			}
		}
		else
		{
			$error = true;
			$error_msg .= ((!empty($error_msg)) ? '<br />' : '') . $lang['No_to_user'];
		}

		$privmsg_subject = $subject;
		if (empty($privmsg_subject))
		{
			$error = true;
			$error_msg .= ((!empty($error_msg)) ? '<br />' : '') . $lang['Empty_subject'];
		}

		if (!empty($message) && !$error)
		{
			$privmsg_message = prepare_message($message, $html_on, $bbcode_on, $smilies_on, '');
		}
		else
		{
			$error = true;
			$error_msg .= ((!empty($error_msg)) ? '<br />' : '') . $lang['Empty_message'];
		}
	}

	if (($submit || ($draft && $draft_confirm)) && !$error)
	{
		// Has admin prevented user from sending PM's?
		if (!$user->data['user_allow_pm'])
		{
			$error_message = $lang['Cannot_send_privmsg'];
			message_die(GENERAL_MESSAGE, $error_message);
		}

		// MG Drafts - BEGIN
		if (($config['allow_drafts'] == true) && $draft && $draft_confirm && $user->data['session_logged_in'])
		{
			save_draft($draft_id, $user->data['user_id'], 0, 0, $privmsg_subject, $message);
			$output_message = $lang['Drafts_Saved'];
			$output_message .= '<br /><br />' . sprintf($lang['Click_return_drafts'], '<a href="' . append_sid(CMS_PAGE_DRAFTS) . '">', '</a>');
			$output_message .= '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid(CMS_PAGE_FORUM) . '">', '</a>');

			$redirect_url = append_sid(CMS_PAGE_PRIVMSG . '?folder=inbox');
			meta_refresh(3, $redirect_url);

			message_die(GENERAL_MESSAGE, $output_message);
		}
		// MG Drafts - END

		$msg_time = time();

		if ($mode != 'edit')
		{
			// See if recipient is at their inbox limit
			$sql = "SELECT COUNT(privmsgs_id) AS inbox_items, MIN(privmsgs_date) AS oldest_post_time
				FROM " . PRIVMSGS_TABLE . "
				WHERE (privmsgs_type = " . PRIVMSGS_NEW_MAIL . "
						OR privmsgs_type = " . PRIVMSGS_READ_MAIL . "
						OR privmsgs_type = " . PRIVMSGS_UNREAD_MAIL . ")
					AND privmsgs_to_userid = " . $to_userdata['user_id'];
			$result = $db->sql_query($sql);

			if ($inbox_info = $db->sql_fetchrow($result))
			{
				if ($config['max_inbox_privmsgs'] && ($inbox_info['inbox_items'] >= $config['max_inbox_privmsgs']))
				{
					$sql = "SELECT privmsgs_id FROM " . PRIVMSGS_TABLE . "
						WHERE (privmsgs_type = " . PRIVMSGS_NEW_MAIL . "
								OR privmsgs_type = " . PRIVMSGS_READ_MAIL . "
								OR privmsgs_type = " . PRIVMSGS_UNREAD_MAIL . " )
							AND privmsgs_date = " . $inbox_info['oldest_post_time'] . "
							AND privmsgs_to_userid = " . $to_userdata['user_id'];
					$result = $db->sql_query($sql);
					$old_privmsgs_id = $db->sql_fetchrow($result);
					$old_privmsgs_id = $old_privmsgs_id['privmsgs_id'];

					$sql = "DELETE FROM " . PRIVMSGS_TABLE . "
						WHERE privmsgs_id = $old_privmsgs_id";
					$db->sql_query($sql);
				}
			}

			if ($user->data['user_level'] > 0)
			{
				$pm_send = true;
			}
			else
			{
				$u_pm_in = user_check_pm_in_allowed($to_userdata['user_id']);
				if ($u_pm_in == true)
				{
					$pm_send = true;
				}
				else
				{
					$u_pm_friend = user_check_friend_foe($to_userdata['user_id'], true);
					if ($u_pm_friend == true)
					{
						$pm_send = true;
					}
					else
					{
						$msg = $lang['Allow_PM_IN_SEND_ERROR'] . '<br /><br />' . sprintf($lang['Click_return_inbox'], '<a href="' . append_sid(CMS_PAGE_PRIVMSG . '?folder=inbox') . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid(CMS_PAGE_FORUM) . '">', '</a>');
						message_die(GENERAL_MESSAGE, $msg);
					}
				}
			}
			$sql_info = "INSERT INTO " . PRIVMSGS_TABLE . " (privmsgs_type, privmsgs_subject, privmsgs_text, privmsgs_from_userid, privmsgs_to_userid, privmsgs_date, privmsgs_ip, privmsgs_enable_html, privmsgs_enable_bbcode, privmsgs_enable_smilies, privmsgs_enable_autolinks_acronyms, privmsgs_attach_sig)
				VALUES (" . PRIVMSGS_NEW_MAIL . ", '" . $db->sql_escape($privmsg_subject) . "', '" . $db->sql_escape($privmsg_message) . "', " . $user->data['user_id'] . ", " . $to_userdata['user_id'] . ", $msg_time, '$user_ip', $html_on, $bbcode_on, $smilies_on, $acro_auto_on, $attach_sig)";
		}
		else
		{
			if ($user->data['user_level'] > 0)
			{
				$pm_send = true;
			}
			else
			{
				$u_pm_in = user_check_pm_in_allowed($to_userdata['user_id']);
				if ($u_pm_in == true)
				{
					$pm_send = true;
				}
				else
				{
					$u_pm_friend = user_check_friend_foe($to_userdata['user_id'], true);
					if ($u_pm_friend == true)
					{
						$pm_send = true;
					}
					else
					{
						$msg = $lang['Allow_PM_IN_SEND_ERROR'] . '<br /><br />' . sprintf($lang['Click_return_inbox'], '<a href="' . append_sid(CMS_PAGE_PRIVMSG . '?folder=inbox') . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid(CMS_PAGE_FORUM) . '">', '</a>');
						message_die(GENERAL_MESSAGE, $msg);
					}
				}
			}
			$sql_info = "UPDATE " . PRIVMSGS_TABLE . "
				SET privmsgs_type = " . PRIVMSGS_NEW_MAIL . ", privmsgs_subject = '" . $db->sql_escape($privmsg_subject) . "', privmsgs_text = '" . $db->sql_escape($privmsg_message) . "', privmsgs_from_userid = " . $user->data['user_id'] . ", privmsgs_to_userid = " . $to_userdata['user_id'] . ", privmsgs_date = $msg_time, privmsgs_ip = '$user_ip', privmsgs_enable_html = $html_on, privmsgs_enable_bbcode = $bbcode_on, privmsgs_enable_smilies = $smilies_on, privmsgs_enable_autolinks_acronyms = $acro_auto_on, privmsgs_attach_sig = $attach_sig
				WHERE privmsgs_id = $privmsg_id";
		}
		$result = $db->sql_query($sql_info);

		if ($mode != 'edit')
		{
			$privmsg_sent_id = $db->sql_nextid();
		}

		$attachment_mod['pm']->insert_attachment_pm($privmsg_id);
		if ($mode != 'edit')
		{
			// Add to the users new pm counter
			$sql = "UPDATE " . USERS_TABLE . "
				SET user_new_privmsg = user_new_privmsg + 1, user_last_privmsg = " . time() . "
				WHERE user_id = " . $to_userdata['user_id'];
			$status = $db->sql_query($sql);

			if ($to_userdata['user_notify_pm'] && !empty($to_userdata['user_email']) && $to_userdata['user_active'])
			{
				$server_url = create_server_url();
				$privmsg_url = $server_url . CMS_PAGE_PRIVMSG;

				include(IP_ROOT_PATH . 'includes/emailer.' . PHP_EXT);
				$emailer = new emailer();
				$emailer->use_template('privmsg_notify', $to_userdata['user_lang']);
				$emailer->to($to_userdata['user_email']);
				$emailer->set_subject($lang['Notification_subject']);

				if (!empty($config['html_email']))
				{
					//HTML Message
					$bbcode->allow_html = ($html_on ? true : false);
					$bbcode->allow_bbcode = ($bbcode_on ? true : false);
					$bbcode->allow_smilies = ($smilies_on ? true : false);
					$message = $bbcode->parse($privmsg_message);
					$message = $message;
					//HTML Message
				}
				else
				{
					$message = $bbcode->bbcode_killer($privmsg_message, '');
				}
				$email_sig = create_signature($config['board_email_sig']);
				$emailer->assign_vars(array(
					'USERNAME' => stripslashes($to_username),
					'SITENAME' => $config['sitename'],
					'EMAIL_SIG' => $email_sig,
					// Mighty Gorgon - Begin
					'FROM' => $user->data['username'],
					'DATE' => create_date($config['default_dateformat'], time(), $config['board_timezone']),
					'SUBJECT' => $privmsg_subject,
					'PRIV_MSG_TEXT' => $message,
					// Mighty Gorgon - End
					'FROM_USERNAME' => $user->data['username'],
					'U_INBOX' => $privmsg_url . '?folder=inbox'
					)
				);

				$emailer->send();
				$emailer->reset();
			}
			// MG Cash MOD For IP - BEGIN
			if (!empty($config['plugins']['cash']['enabled']))
			{
				$pmer = new cash_user($user->data['user_id'], $user->data);
				$pmer->give_pm_amount();
				while (false) {}
			}
			// MG Cash MOD For IP - END
		}

		$redirect_url = append_sid(CMS_PAGE_PRIVMSG . '?folder=inbox');
		meta_refresh(3, $redirect_url);

		$msg = $lang['Message_sent'] . '<br /><br />' . sprintf($lang['Click_return_inbox'], '<a href="' . append_sid(CMS_PAGE_PRIVMSG . '?folder=inbox') . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid(CMS_PAGE_FORUM) . '">', '</a>');

		message_die(GENERAL_MESSAGE, $msg);
	}
	elseif ($preview || $refresh || $error)
	{
		// If we're previewing or refreshing then obtain the data passed to the script, process it a little, do some checks where neccessary, etc.
		$to_username = request_var('username', '', true);
		$to_username = htmlspecialchars_decode($to_username, ENT_COMPAT);
		$privmsg_subject = request_var('subject', '', true);
		$privmsg_message = request_var('message', '', true);
		$privmsg_message = htmlspecialchars_decode($privmsg_message, ENT_COMPAT);

		// Do mode specific things
		$meta_content['description'] = '';
		$meta_content['keywords'] = '';
		if ($mode == 'post')
		{
			$meta_content['page_title'] = $lang['Post_new_pm'];
			$user_sig = ($user->data['user_sig'] != '' && $config['allow_sig']) ? $user->data['user_sig'] : '';
		}
		elseif ($mode == 'reply')
		{
			$meta_content['page_title'] = $lang['Post_reply_pm'];
			$user_sig = ($user->data['user_sig'] != '' && $config['allow_sig']) ? $user->data['user_sig'] : '';
		}
		elseif ($mode == 'edit')
		{
			$meta_content['page_title'] = $lang['Edit_pm'];

			$sql = "SELECT u.user_id, u.user_sig
				FROM " . PRIVMSGS_TABLE . " pm, " . USERS_TABLE . " u
				WHERE pm.privmsgs_id = $privmsg_id
					AND u.user_id = pm.privmsgs_from_userid";
			$result = $db->sql_query($sql);

			if ($postrow = $db->sql_fetchrow($result))
			{
				if ($user->data['user_id'] != $postrow['user_id'])
				{
					message_die(GENERAL_MESSAGE, $lang['Edit_own_posts']);
				}

				$user_sig = ($postrow['user_sig'] != '' && $config['allow_sig']) ? $postrow['user_sig'] : '';
			}
		}
	}
	else
	{
		if (!$privmsg_id && (($mode == 'reply') || ($mode == 'edit') || ($mode == 'quote')))
		{
			message_die(GENERAL_ERROR, $lang['No_post_id']);
		}

		if (!empty($_GET[POST_USERS_URL]))
		{
			$user_id = intval($_GET[POST_USERS_URL]);

			$sql = "SELECT username
				FROM " . USERS_TABLE . "
				WHERE user_id = $user_id
					AND user_id <> " . ANONYMOUS;
			$db->sql_return_on_error(true);
			$result = $db->sql_query($sql);
			$db->sql_return_on_error(false);
			if (!$result)
			{
				$error = true;
				$error_msg = $lang['NO_USER'];
			}

			if ($row = $db->sql_fetchrow($result))
			{
				$to_username = $row['username'];
			}
		}
		elseif ($mode == 'edit')
		{
			$sql = "SELECT pm.*, u.username, u.user_id, u.user_sig
				FROM " . PRIVMSGS_TABLE . " pm, " . USERS_TABLE . " u
				WHERE pm.privmsgs_id = $privmsg_id
					AND pm.privmsgs_from_userid = " . $user->data['user_id'] . "
					AND (pm.privmsgs_type = " . PRIVMSGS_NEW_MAIL . "
						OR pm.privmsgs_type = " . PRIVMSGS_UNREAD_MAIL . ")
					AND u.user_id = pm.privmsgs_to_userid";
			$result = $db->sql_query($sql);

			if (!($privmsg = $db->sql_fetchrow($result)))
			{
				redirect(append_sid(CMS_PAGE_PRIVMSG . '?folder=' . $folder, true));
			}

			$privmsg_subject = $privmsg['privmsgs_subject'];
			$privmsg_message = $privmsg['privmsgs_text'];
			$privmsg_bbcode_enabled = ($privmsg['privmsgs_enable_bbcode'] == 1);
			$privmsg_message = str_replace('<br />', "\n", $privmsg_message);
			//$privmsg_message = preg_replace('#</textarea>#si', '&lt;/textarea&gt;', $privmsg_message);

			$user_sig = ($config['allow_sig']) ? (($privmsg['privmsgs_type'] == PRIVMSGS_NEW_MAIL) ? $user_sig : $privmsg['user_sig']) : '';

			$to_username = $privmsg['username'];
			$to_userid = $privmsg['user_id'];

		}
		elseif (($mode == 'reply') || ($mode == 'quote'))
		{

			$sql = "SELECT pm.privmsgs_subject, pm.privmsgs_date, pm.privmsgs_text, u.username, u.user_id
				FROM " . PRIVMSGS_TABLE . " pm, " . USERS_TABLE . " u
				WHERE pm.privmsgs_id = $privmsg_id
					AND pm.privmsgs_to_userid = " . $user->data['user_id'] . "
					AND u.user_id = pm.privmsgs_from_userid";
			$result = $db->sql_query($sql);

			if (!($privmsg = $db->sql_fetchrow($result)))
			{
				redirect(append_sid(CMS_PAGE_PRIVMSG . '?folder=' . $folder, true));
			}

			$privmsg_subject = (((strlen($privmsg['privmsgs_subject']) > 0) && ((substr($privmsg['privmsgs_subject'], 0, strlen($lang['REPLY_PREFIX'])) == $lang['REPLY_PREFIX']) || (substr($privmsg['privmsgs_subject'], 0, strlen($lang['REPLY_PREFIX']))) == $lang['REPLY_PREFIX_OLD'])) ? '' : $lang['REPLY_PREFIX']) . $privmsg['privmsgs_subject'];

			$to_username = $privmsg['username'];
			$to_userid = $privmsg['user_id'];

			if (($mode == 'quote') || ($mode == 'reply'))
			{
				$privmsg_message = $privmsg['privmsgs_text'];
				$privmsg_message = str_replace('<br />', "\n", $privmsg_message);
				//$privmsg_message = preg_replace('#</textarea>#si', '&lt;/textarea&gt;', $privmsg_message);

				$msg_date = create_date_ip($config['default_dateformat'], $privmsg['privmsgs_date'], $config['board_timezone']);

				$privmsg_message = '[quote user="' . $to_username . '"]' . $privmsg_message . '[/quote]';

				$mode = 'reply';
			}
		}
		else
		{
			$privmsg_subject = $privmsg_message = $to_username = '';
		}
	}

	// Has admin prevented user from sending PM's?
	if (!$user->data['user_allow_pm'] && ($mode != 'edit'))
	{
		$error_message = $lang['Cannot_send_privmsg'];
		message_die(GENERAL_MESSAGE, $error_message);
	}

	// Start output, first preview, then errors then post form
	$link_name = '';
	if ($mode == 'post')
	{
		$link_name = $lang['Send_a_new_message'];
	}
	elseif ($mode == 'reply')
	{
		$link_name = $lang['Send_a_reply'];
	}
	elseif ($mode == 'edit')
	{
		$link_name = $lang['Edit_message'];
	}
	$nav_server_url = create_server_url();
	$breadcrumbs['address'] = $lang['Nav_Separator'] . '<a href="' . $nav_server_url . append_sid(CMS_PAGE_PRIVMSG . '?folder=inbox') . '"' . (!empty($link_name) ? '' : ' class="nav-current"') . '>' . $lang['Private_Messaging'] . '</a>' . (!empty($link_name) ? ($lang['Nav_Separator'] . '<a class="nav-current" href="#">' . $link_name . '</a>') : '');
	$skip_nav_cat = true;
	include_once(IP_ROOT_PATH . 'includes/users_zebra_block.' . PHP_EXT);

	if ($preview && !$error)
	{
		$privmsg_message = !empty($draft_message) ? $draft_message : $privmsg_message;
		$privmsg_subject = !empty($draft_subject) ? $draft_subject : $privmsg_subject;

		$preview_message = prepare_message($privmsg_message, $html_on, $bbcode_on, $smilies_on);
		$privmsg_message = preg_replace($html_entities_match, $html_entities_replace, $privmsg_message);

		// Finalise processing as per viewtopic
		if (!$html_on)
		{
			if ($user_sig != '' || !$user->data['user_allowhtml'])
			{
				$user_sig = preg_replace('#(<)([\/]?.*?)(>)#is', "&lt;\\2&gt;", $user_sig);
			}
		}

		$preview_subject = censor_text($privmsg_subject);
		$preview_message = censor_text($preview_message);

		if ($attach_sig && ($user_sig != ''))
		{
			$bbcode->allow_html = ($html_on ? true : false);
			$bbcode->allow_bbcode = ($bbcode_on ? true : false);
			$bbcode->allow_smilies = ($smilies_on ? true : false);
			$bbcode->is_sig = true;
			$user_sig = $bbcode->parse($user_sig);
			$bbcode->is_sig = false;
		}

		$bbcode->allow_html = ($html_on ? true : false);
		$bbcode->allow_bbcode = ($bbcode_on ? true : false);
		$bbcode->allow_smilies = ($smilies_on ? true : false);
		$preview_message = $bbcode->parse($preview_message);

		$signature = '';
		if ($attach_sig && $user_sig != '')
		{
			$signature = '<br />' . $config['sig_line'] . '<br />' . $user_sig;
		}

		if($acro_auto_on)
		{
			$preview_message = $bbcode->acronym_pass($preview_message);
			$preview_message = $bbcode->autolink_text($preview_message, '999999');
		}
		//$preview_message = kb_word_wrap_pass($preview_message);
		$s_hidden_fields = '<input type="hidden" name="folder" value="' . $folder . '" />';
		$s_hidden_fields .= '<input type="hidden" name="mode" value="' . $mode . '" />';

		if (isset($privmsg_id))
		{
			$s_hidden_fields .= '<input type="hidden" name="' . POST_POST_URL . '" value="' . $privmsg_id . '" />';
		}

		$template->set_filenames(array('preview' => 'privmsgs_preview.tpl'));
		$attachment_mod['pm']->preview_attachments();
		$template->assign_vars(array(
			'TOPIC_TITLE' => $preview_subject,
			'POST_SUBJECT' => $preview_subject,
			'MESSAGE_TO' => $to_username,
			'MESSAGE_FROM' => $user->data['username'],
			'POST_DATE' => create_date_ip($config['default_dateformat'], time(), $config['board_timezone']),
			'MESSAGE' => $preview_message,
			'SIGNATURE' => $signature,
			'PLAIN_MESSAGE' => $plain_message,

			'S_HIDDEN_FIELDS' => $s_hidden_fields,

			'L_SUBJECT' => $lang['Subject'],
			'L_DATE' => $lang['Date'],
			'L_FROM' => $lang['From'],
			'L_TO' => $lang['To'],
			'L_PREVIEW' => $lang['Preview'],
			'L_POSTED' => $lang['Posted'])
		);

		$template->assign_var_from_handle('POST_PREVIEW_BOX', 'preview');
	}

	// Start error handling
	if ($error)
	{
		$privmsg_message = htmlspecialchars($privmsg_message);
		$template->set_filenames(array('reg_header' => 'error_body.tpl'));
		$template->assign_vars(array(
			'ERROR_MESSAGE' => $error_msg)
		);
		$template->assign_var_from_handle('ERROR_BOX', 'reg_header');
	}

	make_jumpbox(CMS_PAGE_VIEWFORUM);

	// Enable extensions in posting_body
	$template->assign_block_vars('switch_privmsg', array());
	$template->assign_var('S_POSTING_PM', true);

	// HTML toggle selection
	if ($config['allow_html'] || (($user->data['user_level'] == ADMIN) && $config['allow_html_only_for_admins']))
	{
		$html_status = $lang['HTML_is_ON'];
		$template->assign_block_vars('switch_html_checkbox', array());
	}
	else
	{
		$html_status = $lang['HTML_is_OFF'];
	}

	// BBCode toggle selection
	if ($config['allow_bbcode'])
	{
		$bbcode_status = $lang['BBCode_is_ON'];
		$template->assign_block_vars('switch_bbcode_checkbox', array());
	}
	else
	{
		$bbcode_status = $lang['BBCode_is_OFF'];
	}

	// Smilies toggle selection
	if ($config['allow_smilies'])
	{
		$smilies_status = $lang['Smilies_are_ON'];
		$template->assign_block_vars('switch_smilies_checkbox', array());
	}
	else
	{
		$smilies_status = $lang['Smilies_are_OFF'];
	}

	// Signature toggle selection - only show if the user has a signature
	if ($user_sig != '')
	{
		$template->assign_block_vars('switch_signature_checkbox', array());
	}

	if ($mode == 'post')
	{
		$post_a = $lang['Send_a_new_message'];
	}
	elseif ($mode == 'reply')
	{
		$post_a = $lang['Send_a_reply'];
		$mode = 'post';
	}
	elseif ($mode == 'edit')
	{
		$post_a = $lang['Edit_message'];
	}

	$s_hidden_fields = '<input type="hidden" name="folder" value="' . $folder . '" />';
	$s_hidden_fields .= '<input type="hidden" name="mode" value="' . $mode . '" />';
	$s_hidden_fields .= '<input type="hidden" name="sid" value="' . $user->data['session_id'] . '" />';
	if ($mode == 'edit')
	{
		$s_hidden_fields .= '<input type="hidden" name="' . POST_POST_URL . '" value="' . $privmsg_id . '" />';
	}

	/* Start Private Message Review By aUsTiN */
	//$post_to_review = $_GET['p'];
	$post_to_review = request_var(POST_POST_URL, 0);
	$review_mode = request_var('mode', '');
	if (($post_to_review > 0) && ($review_mode == 'reply'))
	{
		$q = "SELECT *
				FROM " . PRIVMSGS_TABLE . "
				WHERE privmsgs_id = '" . $post_to_review . "'";
		$r = $db->sql_query($q);
		$row = $db->sql_fetchrow($r);

		$prv_msg_review = $row['privmsgs_text'];
		$bbcode->allow_html = (($config['allow_html'] && $user->data['user_allowhtml']) || $config['allow_html_only_for_admins']) && $row['privmsgs_enable_html'];
		$bbcode->allow_bbcode = ($config['allow_bbcode'] ? true : false);
		$bbcode->allow_smilies = ($config['allow_smilies'] ? true : false);
		$prv_msg_review = $bbcode->parse($prv_msg_review);
		if ($row['privmsgs_enable_autolinks_acronyms'])
		{
			$prv_msg_review = $bbcode->acronym_pass($prv_msg_review);
			$prv_msg_review = $bbcode->autolink_text($prv_msg_review, '999999');
		}

		$prv_msg_review = censor_text($prv_msg_review);

		if(!$prv_msg_review)
		{
			$prv_msg_review = $lang['private_msg_review_error'];
		}

		$template->assign_block_vars('switch_prv_msg_review', array(
			'PRIVATE_MSG_REVIEW' => $prv_msg_review,
			'PRIVATE_MSG_TITLE' => $lang['private_msg_review_title']
			)
		);
	}
	/* End Private Message Review By aUsTiN */

	// Send smilies to template
	//generate_smilies('inline');

	/*
	$privmsg_subject = preg_replace($html_entities_match, $html_entities_replace, $privmsg_subject);
	$privmsg_subject = str_replace('"', '&quot;', $privmsg_subject);
	*/

	if (!empty($config['ajax_features']))
	{
		$ajax_blur = ($mode == 'newtopic') ? 'onblur="AJAXSearch(this.value);"' : '';
		$ajax_pm_user_check = 'onkeyup="AJAXCheckPMUsername(this.value);"';
	}
	else
	{
		$ajax_blur = '';
		$ajax_pm_user_check = '';
	}

	// MG Drafts - BEGIN
	if ($config['allow_drafts'] == true)
	{
		$template->assign_block_vars('allow_drafts', array());
		$s_hidden_fields .= '<input type="hidden" name="d" value="' . $draft_id . '" />';
		if (($draft == true) && ($draft_confirm == false))
		{
			$template->assign_block_vars('save_draft_confirm', array());
		}
	}
	// MG Drafts - END

	$template->assign_vars(array(
		'SUBJECT' => $privmsg_subject,
		'USERNAME' => $to_username,
		'MESSAGE' => $privmsg_message,
		'HTML_STATUS' => $html_status,
		'SMILIES_STATUS' => $smilies_status,
		'BBCODE_STATUS' => sprintf($bbcode_status, '<a href="' . append_sid('faq.' . PHP_EXT . '?mode=bbcode') . '" target="_blank">', '</a>'),
		'FORUM_NAME' => $lang['Private_Message'],
		'BOX_NAME' => $l_box_name,
		'INBOX_IMG' => $inbox_img,
		'SENTBOX_IMG' => $sentbox_img,
		'OUTBOX_IMG' => $outbox_img,
		'SAVEBOX_IMG' => $savebox_img,
		'INBOX' => $inbox_url,
		'SENTBOX' => $sentbox_url,
		'OUTBOX' => $outbox_url,
		'SAVEBOX' => $savebox_url,

		'S_IS_PM' => 1,

		// AJAX Features - BEGIN
		'S_AJAX_BLUR' => $ajax_blur,
		'S_AJAX_PM_USER_CHECK' => $ajax_pm_user_check,
		'S_DISPLAY_PREVIEW' => ($preview) ? '' : 'style="display: none;"',
		'S_EDIT_POST_ID' => ($mode == 'edit') ? $privmsg_id : 0,
		'L_EMPTY_SUBJECT' => $lang['Empty_subject'],
		'L_MORE_MATCHES' => $lang['More_matches_username'],
		// AJAX Features - END

		'L_SUBJECT' => $lang['Subject'],
		'L_MESSAGE_BODY' => $lang['Message_body'],
		'L_OPTIONS' => $lang['Options'],
		'L_SPELLCHECK' => $lang['Spellcheck'],
		'L_PREVIEW' => $lang['Preview'],
		'L_DRAFTS' => $lang['Drafts'],
		'L_DRAFT_SAVE' => $lang['Drafts_Save'],
		'L_DRAFT_CONFIRM' => $lang['Drafts_Save_Question'],
		'L_SUBMIT' => $lang['Submit'],
		'L_CANCEL' => $lang['Cancel'],
		'L_POST_A' => $post_a,
		'L_FIND' => $lang['Find'],
		'L_DISABLE_HTML' => $lang['Disable_HTML_pm'],
		'L_DISABLE_ACRO_AUTO' => $lang['Disable_ACRO_AUTO_pm'],
		'L_DISABLE_BBCODE' => $lang['Disable_BBCode_pm'],
		'L_DISABLE_SMILIES' => $lang['Disable_Smilies_pm'],
		'L_ATTACH_SIGNATURE' => $lang['Attach_signature'],

		'L_POST_HIGHLIGHT' => $lang['PostHighlight'],

		'S_HTML_CHECKED' => (!$html_on) ? ' checked="checked"' : '',
		'S_ACRO_AUTO_CHECKED' => (!$acro_auto_on) ? ' checked="checked"' : '' ,
		'S_BBCODE_CHECKED' => (!$bbcode_on) ? ' checked="checked"' : '',
		'S_SMILIES_CHECKED' => (!$smilies_on) ? ' checked="checked"' : '',
		'S_SIGNATURE_CHECKED' => ($attach_sig) ? ' checked="checked"' : '',
		'S_NAMES_SELECT' => $user_names_select,
		'S_HIDDEN_FORM_FIELDS' => $s_hidden_fields,
		'S_POST_ACTION' => append_sid(CMS_PAGE_PRIVMSG),

		'U_SEARCH_USER' => append_sid(CMS_PAGE_SEARCH . '?mode=searchuser'),
		'U_VIEW_FORUM' => append_sid(CMS_PAGE_PRIVMSG)
		)
	);

	// BBCBMG - BEGIN
	include(IP_ROOT_PATH . 'includes/bbcb_mg.' . PHP_EXT);
	$template->assign_var_from_handle('BBCB_MG', 'bbcb_mg');
	// BBCBMG - END
	// BBCBMG SMILEYS - BEGIN
	generate_smilies('inline');
	include(IP_ROOT_PATH . 'includes/bbcb_smileys_mg.' . PHP_EXT);
	$template->assign_var_from_handle('BBCB_SMILEYS_MG', 'bbcb_smileys_mg');
	// BBCBMG SMILEYS - END

	full_page_generation('posting_body.tpl', $lang['Send_private_message'], '', '');
}

// Default page
if (!$user->data['session_logged_in'])
{
	redirect(append_sid(CMS_PAGE_LOGIN . '?redirect=privmsg.' . PHP_EXT . '&folder=inbox', true));
}

// Update unread status
$sql = "UPDATE " . USERS_TABLE . "
	SET user_unread_privmsg = user_unread_privmsg + user_new_privmsg, user_new_privmsg = 0, user_last_privmsg = " . $user->data['session_start'] . "
	WHERE user_id = " . $user->data['user_id'];
$db->sql_query($sql);

$sql = "UPDATE " . PRIVMSGS_TABLE . "
	SET privmsgs_type = " . PRIVMSGS_UNREAD_MAIL . "
	WHERE privmsgs_type = " . PRIVMSGS_NEW_MAIL . "
		AND privmsgs_to_userid = " . $user->data['user_id'];
$db->sql_query($sql);

// Reset PM counters
$user->data['user_new_privmsg'] = 0;
$user->data['user_unread_privmsg'] = ($user->data['user_new_privmsg'] + $user->data['user_unread_privmsg']);

// Generate page
$nav_server_url = create_server_url();
$breadcrumbs['address'] = $lang['Nav_Separator'] . '<a href="' . $nav_server_url . append_sid(CMS_PAGE_PRIVMSG . '?folder=inbox') . '" class="nav-current">' . $lang['Private_Messaging'] . '</a>';
$breadcrumbs['bottom_right_links'] = '<a href="#" onclick="setCheckboxes(\'privmsg_list\', \'mark[]\', true); return false;" class="gensmall">' . $lang['MARK_ALL'] . '</a>&nbsp;&bull;&nbsp;<a href="#" onclick="setCheckboxes(\'privmsg_list\', \'mark[]\', false); return false;" class="gensmall">' . $lang['UNMARK_ALL'] . '</a>';

include_once(IP_ROOT_PATH . 'includes/users_zebra_block.' . PHP_EXT);

make_jumpbox(CMS_PAGE_VIEWFORUM);

// New message
$post_new_mesg_url = '<a href="' . append_sid(CMS_PAGE_PRIVMSG . '?mode=post') . '"><img src="' . $images['post_new'] . '" alt="' . $lang['Send_a_new_message'] . '" /></a>';

// Search messages
$search_sql = '';
$search_userid = 0;
$search_subject = '';
$search_text = '';
if($search_type === 'author')
{
	$sql = get_users_sql($search_value, true, false, true, false);
	$result = $db->sql_query($sql);
	if($result)
	{
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);
		if($row !== false)
		{
			$search_userid = $row['user_id'];
			$search_sql = ' AND privmsgs_' . ((($folder === 'inbox') || ($folder === 'savebox')) ? 'from' : 'to') . '_userid = ' . $search_userid . ' ';
		}
	}
}
elseif($search_type === 'subject')
{
	$search_sql = ' AND privmsgs_subject LIKE \'%' . $db->sql_escape($search_value) . '%\' ';
}

// General SQL to obtain messages
$sql_tot = "SELECT COUNT(privmsgs_id) AS total
	FROM " . PRIVMSGS_TABLE . " ";
$sql = "SELECT pm.privmsgs_type, pm.privmsgs_id, pm.privmsgs_date, pm.privmsgs_subject, u.user_id, u.username, u.user_active, u.user_color
	FROM " . PRIVMSGS_TABLE . " pm, " . USERS_TABLE . " u ";
switch($folder)
{
 case 'inbox':
		$sql_tot .= "WHERE privmsgs_to_userid = " . $user->data['user_id'] . " $search_sql
			AND (privmsgs_type = " . PRIVMSGS_NEW_MAIL . "
				OR privmsgs_type = " . PRIVMSGS_READ_MAIL . "
				OR privmsgs_type = " . PRIVMSGS_UNREAD_MAIL . ")";

		$sql .= "WHERE pm.privmsgs_to_userid = " . $user->data['user_id'] . " $search_sql
			AND u.user_id = pm.privmsgs_from_userid
			AND (pm.privmsgs_type = " . PRIVMSGS_NEW_MAIL . "
				OR pm.privmsgs_type = " . PRIVMSGS_READ_MAIL . "
				OR privmsgs_type = " . PRIVMSGS_UNREAD_MAIL . ")";
		break;

	case 'outbox':
		$sql_tot .= "WHERE privmsgs_from_userid = " . $user->data['user_id'] . " $search_sql
			AND (privmsgs_type = " . PRIVMSGS_NEW_MAIL . "
				OR privmsgs_type = " . PRIVMSGS_UNREAD_MAIL . ")";

		$sql .= "WHERE pm.privmsgs_from_userid = " . $user->data['user_id'] . " $search_sql
			AND u.user_id = pm.privmsgs_to_userid
			AND (pm.privmsgs_type = " . PRIVMSGS_NEW_MAIL . "
				OR privmsgs_type = " . PRIVMSGS_UNREAD_MAIL . ")";
		break;

	case 'sentbox':
		$sql_tot .= "WHERE privmsgs_from_userid = " . $user->data['user_id'] . " $search_sql
			AND privmsgs_type = " . PRIVMSGS_SENT_MAIL;

		$sql .= "WHERE pm.privmsgs_from_userid = " . $user->data['user_id'] . " $search_sql
			AND u.user_id = pm.privmsgs_to_userid
			AND pm.privmsgs_type = " . PRIVMSGS_SENT_MAIL;
		break;

	case 'savebox':
		$sql_tot .= "WHERE ((privmsgs_to_userid = " . $user->data['user_id'] . "
				AND privmsgs_type = " . PRIVMSGS_SAVED_IN_MAIL . ")
			OR (privmsgs_from_userid = " . $user->data['user_id'] . "
				AND privmsgs_type = " . PRIVMSGS_SAVED_OUT_MAIL . "))";

		$sql .= "WHERE u.user_id = pm.privmsgs_from_userid
			AND ((pm.privmsgs_to_userid = " . $user->data['user_id'] . "
				AND pm.privmsgs_type = " . PRIVMSGS_SAVED_IN_MAIL . ")
			OR (pm.privmsgs_from_userid = " . $user->data['user_id'] . "
				AND pm.privmsgs_type = " . PRIVMSGS_SAVED_OUT_MAIL . "))";
		break;

	default:
		message_die(GENERAL_MESSAGE, $lang['No_such_folder']);
		break;
}

// Show messages over previous x days/months
$msg_days = request_var('msgdays', 0);
if ($submit_msgdays && !empty($msg_days))
{
	$min_msg_time = time() - ($msg_days * 86400);

	$limit_msg_time_total = " AND privmsgs_date > $min_msg_time";
	$limit_msg_time = " AND pm.privmsgs_date > $min_msg_time ";

	if (!empty($_POST['msgdays']))
	{
		$start = 0;
	}
}
else
{
	$limit_msg_time = $limit_msg_time_total = '';
	$msg_days = 0;
}

$sql .= $limit_msg_time . " ORDER BY pm.privmsgs_date DESC LIMIT $start, " . $config['topics_per_page'];
$sql_all_tot = $sql_tot;
$sql_tot .= $limit_msg_time_total;

// Get messages
$result = $db->sql_query($sql_tot);
$pm_total = ($row = $db->sql_fetchrow($result)) ? $row['total'] : 0;
$result = $db->sql_query($sql_all_tot);
$pm_all_total = ($row = $db->sql_fetchrow($result)) ? $row['total'] : 0;

// Build select box
$previous_days = array(0, 1, 7, 14, 30, 90, 180, 364);
$previous_days_text = array($lang['ALL_POSTS'], $lang['1_DAY'], $lang['7_DAYS'], $lang['2_WEEKS'], $lang['1_MONTH'], $lang['3_MONTHS'], $lang['6_MONTHS'], $lang['1_YEAR']);

$select_msg_days = '';
for($i = 0; $i < sizeof($previous_days); $i++)
{
	$selected = ($msg_days == $previous_days[$i]) ? ' selected="selected"' : '';
	$select_msg_days .= '<option value="' . $previous_days[$i] . '"' . $selected . '>' . $previous_days_text[$i] . '</option>';
}

// Define correct icons
switch ($folder)
{
	case 'inbox':
		$l_box_name = $lang['Inbox'];
		break;
	case 'outbox':
		$l_box_name = $lang['Outbox'];
		break;
	case 'savebox':
		$l_box_name = $lang['Savebox'];
		break;
	case 'sentbox':
		$l_box_name = $lang['Sentbox'];
		break;
}
$post_pm_url = append_sid(CMS_PAGE_PRIVMSG . '?mode=post');
$post_pm_img = '<a href="' . $post_pm_url . '"><img src="' . $images['pm_postmsg'] . '" alt="' . $lang['Post_new_pm'] . '" /></a>';
$post_pm = '<a href="' . $post_pm_url . '">' . $lang['Post_new_pm'] . '</a>';

// Output data for inbox status
if ($folder != 'outbox')
{
	$inbox_limit_pct = ($config['max_' . $folder . '_privmsgs'] > 0) ? round(($pm_all_total / $config['max_' . $folder . '_privmsgs']) * 100) : 100;
	$inbox_limit_img_length = ($config['max_' . $folder . '_privmsgs'] > 0) ? round(($pm_all_total / $config['max_' . $folder . '_privmsgs']) * $config['privmsg_graphic_length']) : $config['privmsg_graphic_length'];
	$inbox_limit_remain = ($config['max_' . $folder . '_privmsgs'] > 0) ? $config['max_' . $folder . '_privmsgs'] - $pm_all_total : 0;

	if ($inbox_limit_pct <= 30)
	{
		$bar_color = 'green';
	}
	elseif (($inbox_limit_pct > 30) && ($inbox_limit_pct <= 70))
	{
		$bar_color = 'blue';
	}
	elseif ($inbox_limit_pct > 70)
	{
		$bar_color = 'red';
	}

	$vote_color = $bar_color;
	$voting_bar = 'voting_graphic_' . $vote_color;
	$voting_bar_body = 'voting_graphic_' . $vote_color . '_body';
	$voting_bar_left = 'voting_graphic_' . $vote_color . '_left';
	$voting_bar_right = 'voting_graphic_' . $vote_color . '_right';

	$voting_bar_img = $images[$voting_bar];
	$voting_bar_body_img = $images[$voting_bar_body];
	$voting_bar_left_img = $images[$voting_bar_left];
	$voting_bar_right_img = $images[$voting_bar_right];

	$vote_graphic_img = $images['voting_graphic'][$vote_graphic];

	$template->assign_block_vars('switch_box_size_notice', array());

	switch($folder)
	{
		case 'inbox':
			$l_box_size_status = sprintf($lang['Inbox_size'], $inbox_limit_pct);
			break;
		case 'sentbox':
			$l_box_size_status = sprintf($lang['Sentbox_size'], $inbox_limit_pct);
			break;
		case 'savebox':
			$l_box_size_status = sprintf($lang['Savebox_size'], $inbox_limit_pct);
			break;
		default:
			$l_box_size_status = '';
			break;
	}
}

// Dump vars to template
$template->assign_vars(array(
	'BOX_NAME' => $l_box_name,
	'INBOX_IMG' => $inbox_img,
	'SENTBOX_IMG' => $sentbox_img,
	'OUTBOX_IMG' => $outbox_img,
	'SAVEBOX_IMG' => $savebox_img,
	'INBOX' => $inbox_url,
	'SENTBOX' => $sentbox_url,
	'OUTBOX' => $outbox_url,
	'SAVEBOX' => $savebox_url,

	'L_POST_PM' => $lang['Post_new_pm'],
	'POST_PM_URL' => $post_pm_url,

	'POST_PM_IMG' => $post_pm_img,
	'POST_PM' => $post_pm,

	'INBOX_LIMIT_IMG_WIDTH' => $inbox_limit_img_length,
	'INBOX_LIMIT_PERCENT' => $inbox_limit_pct,
	'BAR_GRAPHIC' => $voting_bar_img,
	'BAR_GRAPHIC_BODY' => $voting_bar_body_img,
	'BAR_GRAPHIC_LEFT' => $voting_bar_left_img,
	'BAR_GRAPHIC_RIGHT' => $voting_bar_right_img,
	'BAR_COLOR' => $bar_color,
	'BOX_SIZE_STATUS' => $l_box_size_status,

	'L_INBOX' => $lang['Inbox'],
	'L_OUTBOX' => $lang['Outbox'],
	'L_SENTBOX' => $lang['Sent'],
	'L_SAVEBOX' => $lang['Saved'],
	'L_MARK' => $lang['Mark'],
	'L_FLAG' => $lang['Flag'],
	'L_SUBJECT' => $lang['Subject'],
	'L_DATE' => $lang['Date'],
	'L_DISPLAY_MESSAGES' => $lang['Display_messages'],
	'L_FROM_OR_TO' => ($folder == 'inbox' || $folder == 'savebox') ? $lang['From'] : $lang['To'],
	'L_DELETE_MARKED' => $lang['Delete_marked'],
	'L_DELETE_ALL' => $lang['Delete_all'],
	'L_SAVE_MARKED' => $lang['Save_marked'],
	'L_DOWNLOAD_MARKED' => $lang['Download_marked'],

	'S_PRIVMSGS_ACTION' => append_sid(CMS_PAGE_PRIVMSG . '?folder=' . $folder),
	'S_HIDDEN_FIELDS' => '',
	'S_POST_NEW_MSG' => $post_new_mesg_url,
	'S_SELECT_MSG_DAYS' => $select_msg_days,

	'U_POST_NEW_TOPIC' => append_sid(CMS_PAGE_PRIVMSG . '?mode=post')
	)
);

// Okay, let's build the correct folder
$result = $db->sql_query($sql);

if ($row = $db->sql_fetchrow($result))
{
	$i = 0;
	do
	{
		$privmsg_id = $row['privmsgs_id'];

		$flag = $row['privmsgs_type'];

		$icon_flag = ($flag == PRIVMSGS_NEW_MAIL || $flag == PRIVMSGS_UNREAD_MAIL) ? $images['pm_unreadmsg'] : $images['pm_readmsg'];
		$icon_flag_alt = ($flag == PRIVMSGS_NEW_MAIL || $flag == PRIVMSGS_UNREAD_MAIL) ? $lang['Unread_message'] : $lang['Read_message'];

		$msg_userid = $row['user_id'];
		$msg_username = colorize_username($row['user_id'], $row['username'], $row['user_color'], $row['user_active']);

		$u_from_user_profile = append_sid(CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $msg_userid);

		$msg_subject = $row['privmsgs_subject'];
		$msg_subject = censor_text($msg_subject);
		$u_subject = append_sid(CMS_PAGE_PRIVMSG . '?folder=' . $folder . '&amp;mode=read&amp;' . POST_POST_URL . '=' . $privmsg_id);

		$msg_date = create_date_ip($config['default_dateformat'], $row['privmsgs_date'], $config['board_timezone']);

		if (($flag == PRIVMSGS_NEW_MAIL) && ($folder == 'inbox'))
		{
			$msg_subject = '<b>' . $msg_subject . '</b>';
			$msg_date = '<b>' . $msg_date . '</b>';
			$msg_username = '<b>' . $msg_username . '</b>';
		}

		$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];
		$i++;

		$template->assign_block_vars('listrow', array(
			'ROW_CLASS' => $row_class,
			'FROM' => $msg_username,
			'SUBJECT' => $msg_subject,
			'DATE' => $msg_date,
			'PRIVMSG_ATTACHMENTS_IMG' => privmsgs_attachment_image($privmsg_id),

			'PRIVMSG_FOLDER_IMG' => $icon_flag,

			'L_PRIVMSG_FOLDER_ALT' => $icon_flag_alt,

			'S_MARK_ID' => $privmsg_id,

			'U_READ' => $u_subject,
			'U_FROM_USER_PROFILE' => $u_from_user_profile
			)
		);
	}
	while($row = $db->sql_fetchrow($result));

	$search_pagination = $search_type ? ('&searchvar=' . $search_type . '&searchvalue=' . urlencode($search_value)) : '';
	$template->assign_vars(array(
		'PAGINATION' => generate_pagination(CMS_PAGE_PRIVMSG . '?folder=' . $folder . $search_pagination, $pm_total, $config['topics_per_page'], $start),
		'PAGE_NUMBER' => sprintf($lang['Page_of'], (floor($start / $config['topics_per_page']) + 1), ceil($pm_total / $config['topics_per_page'])),

		'L_GOTO_PAGE' => $lang['Goto_page']
		)
	);

}
else
{
	$template->assign_vars(array(
		'L_NO_MESSAGES' => $lang['No_messages_folder']
		)
	);

	$template->assign_block_vars('switch_no_messages', array());
}

full_page_generation('privmsgs_body.tpl', $lang['Private_Messaging'], '', '');

?>