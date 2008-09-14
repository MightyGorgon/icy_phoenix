<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);

$post_id = isset($_GET['post']) ? intval($_GET['post']) : 0;
$code_id = isset($_GET['item']) ? intval($_GET['item']) : 0;

if( !$post_id )
{
	message_die(GENERAL_MESSAGE, 'Topic_post_not_exist');
}

// get post
$sql = "SELECT * FROM " . POSTS_TABLE . " WHERE post_id='{$post_id}'";
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_MESSAGE, 'Topic_post_not_exist');
}
if (($postrow = $db->sql_fetchrow($result)) === false)
{
	message_die(GENERAL_MESSAGE, 'Topic_post_not_exist');
}
$db->sql_freeresult($result);

$sql = "SELECT * FROM " . POSTS_TEXT_TABLE . " WHERE post_id='{$post_id}'";
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_MESSAGE, 'Topic_post_not_exist');
}
if (($posttext = $db->sql_fetchrow($result)) === false)
{
	message_die(GENERAL_MESSAGE, 'Topic_post_not_exist');
}
$db->sql_freeresult($result);

// prepare variables
$code_filename = '';
$code_text = '';
define('EXTRACT_CODE', $code_id);

// compile post
$bbcode_uid = $posttext['bbcode_uid'];
$bbcode->allow_bbcode = true;
$bbcode->allow_smilies = $board_config['allow_smilies'] && $postrow['user_allowsmile'] ? true : false;
$GLOBALS['code_post_id'] = $postrow['post_id'];
$message = $bbcode->parse($posttext['post_text'], $bbcode_uid);
$GLOBALS['code_post_id'] = 0;

if(!strlen($code_text))
{
	message_die(GENERAL_MESSAGE, 'Topic_post_not_exist');
}

$code_text = undo_htmlspecialchars($code_text, true);

if(empty($code_filename))
{
	$code_filename = 'code_' . $post_id . ($code_id ? '_' . $code_id : '') . '.txt';
}

header('Content-Type: application/zip');
header('Content-Length: ' . strlen($code_text));
header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Content-Disposition: attachment; filename="' . $code_filename . '"');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
echo $code_text;

?>