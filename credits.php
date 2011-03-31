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
* Nivisec.com (support@nivisec.com)
*
*/

define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);

// Start session management
$user->session_begin();
//$auth->acl($user->data);
$user->setup();
// End session management

include(IP_ROOT_PATH . 'includes/functions_credits.' . PHP_EXT);

$mode = request_var('mode', '', true);

/*******************************************************************************************
/** Parse for modes...
/******************************************************************************************/
setup_hacks_list_array();
scan_hl_files();
switch($mode)
{
	default:
	{
		$sql = 'SELECT * FROM ' . HACKS_LIST_TABLE . "
				WHERE hack_hide = 'No'
				ORDER BY hack_name ASC";
		$result = $db->sql_query($sql, 0, 'credits_');
		$i = 0;
		while ($row = $db->sql_fetchrow($result))
		{
			$template->assign_block_vars('listrow', array(
				'ROW_CLASS' => (!(++$i% 2)) ? $theme['td_class1'] : $theme['td_class2'],
				'HACK_ID' => $row['hack_id'],
				'HACK_AUTHOR' => ($row['hack_author_email'] != '') ? ((USE_CRYPTIC_EMAIL) ? $row['hack_author'] . '<br />' . cryptize_hl_email($row['hack_author_email']) : '<a href="mailto:' . $row['hack_author_email'] . '">' . $row['hack_author'] . '</a>') : $row['hack_author'],
				'HACK_WEBSITE' => ($row['hack_author_website'] != '') ? '<a target="blank" href="' . $row['hack_author_website'] . '">' . $row['hack_author_website'] . '</a>' : $lang['No_Website'],
				'HACK_NAME' => ($row['hack_download_url'] != '') ? '<a target="blank" href="' . $row['hack_download_url'] . '">' . $row['hack_name'] . '</a>' : $row['hack_name'],
				'HACK_DESC' => $row['hack_desc'],
				//'HACK_VERSION' => ($row['hack_version'] != '') ? ' v' . $row['hack_version'] : ''));
				'HACK_VERSION' => ($row['hack_version'] != '') ? $row['hack_version'] : ''
				)
			);
		}

		if ($i == 0 || !isset($i))
		{
			$template->assign_block_vars('empty_switch', array());
			$template->assign_var('L_NO_HACKS', $lang['No_Hacks']);
		}
	}
}
$template->assign_vars(array(
	'L_PAGE_NAME' => $meta_content['page_title'],
	'S_MODE_ACTION' => append_sid(basename(__FILE__)),
	'L_VERSION' => $lang['Version'],
	'L_AUTHOR' => $lang['Author'],
	'L_DESCRIPTION' => $lang['Description'],
	'L_HACK_NAME' => $lang['Hack_Name'],
	'L_WEBSITE' => $lang['Website']
	)
);

//$template->assign_block_vars('google_ad', array());
//copyright_nivisec($meta_content['page_title'], '2003');
full_page_generation('credits_display.tpl', $lang['Hacks_List'], '', '');

?>