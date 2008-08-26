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
* Martyn Hackett (webmaster@tophostinguk.com)
*
*/

define('IN_PHPBB', true);

if( !empty($setmodules) )
{
	$file = basename(__FILE__);
	$module['1100_General']['200_Notepad'] = $file;

	return;
}

// Load default header
$phpbb_root_path = './../';
$no_page_header = true;
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);

//
// Output the authorisation details
//
$template->set_filenames(array(
	'body' => ADM_TPL . 'admin_notepad_body.tpl')
);


if(isset($_POST['post']))
{
	$tnote = addslashes($_POST['noteme']);
	$query = mysql_query("UPDATE " . NOTES_ADMIN_TABLE . "
		SET text = '" . addslashes($_POST['noteme']) . "'
		WHERE id = 1");
}

$sql = mysql_query("SELECT text FROM " . NOTES_ADMIN_TABLE);
if(!$sql)
{
	echo mysql_error();
}
$note = mysql_fetch_array($sql);

include('./page_header_admin.' . $phpEx);

$template->assign_vars(array(
	"L_TITLE" => $lang['Admin_notepad_title'],
	"L_TITLE_EXPLAIN" => $lang['Admin_notepad_explain'],
	"U_NOTEPAD" => stripslashes($note['text']))
);

$template->pparse('body');

include('./page_footer_admin.' . $phpEx);

?>