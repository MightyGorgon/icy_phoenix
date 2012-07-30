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
	exit;
}

// get the board & user settings ...
$html_on = ($user->data['user_allowhtml'] && $config['allow_html']) ? 1 : 0 ;
$bbcode_on = ($user->data['user_allowbbcode'] && $config['allow_bbcode']) ? 1 : 0 ;
$smilies_on = ($user->data['user_allowsmile'] && $config['allow_smilies']) ? 1 : 0 ;

if (!class_exists('bbcode') || empty($bbcode))
{
	@include_once(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);
}

$bbcode->allow_html = $html_on;
$bbcode->allow_bbcode = $bbcode_on;
$bbcode->allow_smilies = $smilies_on;
$bbcode->is_sig = true;

$signature = request_var('message', '', true);
if (empty($signature))
{
	$signature = request_var('signature', '', true);
}
$signature = htmlspecialchars_decode($signature, ENT_COMPAT);
$signature = str_replace('<br />', "\n", $signature);

$mode = request_var('mode', '');
$submit = request_var('save', '');
$preview = request_var('preview', '');

// if cancel pressed then redirect to the index page
if (isset($_POST['cancel']))
{
	$redirect = CMS_PAGE_FORUM;

// redirect 2.0.4 only
	redirect(append_sid($redirect, true));

// redirect 2.0.x
//	$header_location = (@preg_match('/Microsoft|WebSTAR|Xitami/', getenv('SERVER_SOFTWARE'))) ? 'Refresh: 0; URL=' : 'Location: ';
//	header($header_location . append_sid($redirect, true));
//	exit;

}

include_once(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_post.' . PHP_EXT);

$link_name = $lang['Signature'];
$nav_server_url = create_server_url();
$breadcrumbs['address'] = $lang['Nav_Separator'] . '<a href="' . $nav_server_url . append_sid(CMS_PAGE_PROFILE_MAIN) . '"' . (!empty($link_name) ? '' : ' class="nav-current"') . '>' . $lang['Profile'] . '</a>' . (!empty($link_name) ? ($lang['Nav_Separator'] . '<a class="nav-current" href="#">' . $link_name . '</a>') : '');

// save new signature
if ($submit)
{
	$template->assign_block_vars('switch_save_sig', array());

	if (isset($signature))
	{
		if (strlen($signature) > $config['max_sig_chars'])
		{
			$save_message = $lang['Signature_too_long'];
		}

		else
		{
			$signature = prepare_message($signature, $html_on, $bbcode_on, $smilies_on);
			$user_id =  $user->data['user_id'];

			$sql = "UPDATE " . USERS_TABLE . "
			SET user_sig = '" . $db->sql_escape($signature) . "'
			WHERE user_id = $user_id";
			$result = $db->sql_query($sql);
			$save_message = $lang['sig_save_message'];
		}
	}
	else
	{
		message_die(GENERAL_MESSAGE, 'An Error occured while submitting Signature');
	}
}

// catch the submitted message and prepare it for a preview
elseif ($preview)
{
	$template->assign_block_vars('switch_preview_sig', array());

	if (isset($signature))
	{
		$preview_sig = $signature;

		if (strlen($preview_sig) > $config['max_sig_chars'])
		{
			$preview_sig = $lang['Signature_too_long'];
		}
		else
		{
			$preview_sig = htmlspecialchars($preview_sig);
			$preview_sig = stripslashes(prepare_message(addslashes(unprepare_message($preview_sig)), $html_on, $bbcode_on, $smilies_on));
			if($preview_sig != '')
			{
				$bbcode->is_sig = true;
				$preview_sig = $bbcode->parse($preview_sig);
				$bbcode->is_sig = false;
				$preview_sig = '<br />' . $config['sig_line'] . '<br />' . $preview_sig;
				//$preview_sig = nl2br($preview_sig);
				$preview_sig = censor_text($preview_sig);
			}
			else
			{
				$preview_sig = $lang['sig_none'];
			}
		}
	}
	else
	{
		message_die(GENERAL_MESSAGE, 'An Error occured while submitting Signature');
	}
}
// read current signature and prepare it for a preview
elseif ($mode)
{

	$template->assign_block_vars('switch_current_sig', array());

	$signature = $user->data['user_sig'];
	//$user_sig = prepare_message($user->data['user_sig'], $html_on, $bbcode_on, $smilies_on);
	$user_sig = $user->data['user_sig'];

	if($user_sig != '')
	{
		$bbcode->is_sig = true;
		$user_sig = $bbcode->parse($user_sig);
		$bbcode->is_sig = false;
		$user_sig = censor_text($user_sig);
		$user_sig = '<br />' . $config['sig_line'] . '<br />' . $user_sig;
		//$user_sig = nl2br($user_sig);
	}
	else
	{
		$user_sig = $lang['sig_none'];
	}
}

$template->assign_vars(array(
	'SIG_SAVE' => $lang['sig_save'],
	'SIG_CANCEL' => $lang['Cancel'],
	'SIG_PREVIEW' => $lang['Preview'],
	'SIG_EDIT' => $lang['sig_edit'],
	'SIG_CURRENT' => $lang['sig_current'],
	'SIG_LINK' => append_sid(CMS_PAGE_PROFILE . '?mode=signature'),

	'L_SIGNATURE' => $lang['Signature'],
	'L_SIGNATURE_EXPLAIN' => sprintf($lang['Signature_explain'], $config['max_sig_chars']),
	'HTML_STATUS' => $html_status,
	'BBCODE_STATUS' => sprintf($bbcode_status, '<a href="' . append_sid('faq.' . PHP_EXT . '?mode=bbcode') . '" target="_blank">', '</a>'),
	'SMILIES_STATUS' => $smilies_status,

	'SIGNATURE' => stripslashes($signature),
	'CURRENT_PREVIEW' => $user_sig,
	'PREVIEW' => htmlspecialchars(stripslashes($signature)),
	'REAL_PREVIEW' => $preview_sig,
	'SAVE_MESSAGE' => $save_message,
	)
);

// BBCBMG - BEGIN
$s_disable_bbc_special_content = (empty($config['allow_all_bbcode']) ? true : false);
include(IP_ROOT_PATH . 'includes/bbcb_mg.' . PHP_EXT);
$template->assign_var_from_handle('BBCB_MG', 'bbcb_mg');
// BBCBMG - END
// BBCBMG SMILEYS - BEGIN
include_once(IP_ROOT_PATH . 'includes/functions_post.' . PHP_EXT);
generate_smilies('inline');
include(IP_ROOT_PATH . 'includes/bbcb_smileys_mg.' . PHP_EXT);
$template->assign_var_from_handle('BBCB_SMILEYS_MG', 'bbcb_smileys_mg');
// BBCBMG SMILEYS - END

full_page_generation('profile_signature.tpl', $lang['Signature'], '', '');

?>