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
include_once(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);

// Start session management
$user->session_begin();
//$auth->acl($user->data);
$user->setup();
// End session management

$post_id = request_var('post', 0);
$post_id = ($post_id < 0) ? 0 : $post_id;

$code_id = request_var('item', 0);
$code_id = ($code_id < 0) ? 0 : $code_id;

if(!$post_id)
{
	if (!defined('STATUS_404')) define('STATUS_404', true);
	message_die(GENERAL_MESSAGE, 'NO_TOPIC');
}

// get post
$sql = "SELECT * FROM " . POSTS_TABLE . " WHERE deleted = 0 AND post_id = " . $post_id;
$db->sql_return_on_error(true);
$result = $db->sql_query($sql);
$db->sql_return_on_error(false);
if (!$result)
{
	if (!defined('STATUS_404')) define('STATUS_404', true);
	message_die(GENERAL_MESSAGE, 'NO_TOPIC');
}
if (($postrow = $db->sql_fetchrow($result)) === false)
{
	if (!defined('STATUS_404')) define('STATUS_404', true);
	message_die(GENERAL_MESSAGE, 'NO_TOPIC');
}
$db->sql_freeresult($result);

// prepare variables
$code_filename = '';
$code_text = '';
define('EXTRACT_CODE', $code_id);

// compile post
$bbcode->allow_bbcode = true;
$bbcode->allow_smilies = $config['allow_smilies'] && $postrow['user_allowsmile'] ? true : false;
$bbcode->code_post_id = $postrow['post_id'];
$message = $bbcode->parse($postrow['post_text']);
$bbcode->code_post_id = 0;

if(!strlen($code_text))
{
	if (!defined('STATUS_404')) define('STATUS_404', true);
	message_die(GENERAL_MESSAGE, 'NO_TOPIC');
}

$code_text = $bbcode->undo_htmlspecialchars($code_text, true);

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