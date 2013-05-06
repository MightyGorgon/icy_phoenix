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

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

if (!defined('IN_ADMIN'))
{
	define('IN_ADMIN', true);
}

define('CTRACKER_DISABLED', true);

// Include files
include(IP_ROOT_PATH . 'common.' . PHP_EXT);

$config['jquery_ui'] = true;
// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup();
// End session management

// FORM CLASS - BEGIN
include(IP_ROOT_PATH . 'includes/class_form.' . PHP_EXT);
$class_form = new class_form();
// FORM CLASS - END

include_once(IP_ROOT_PATH . 'includes/functions_jr_admin.' . PHP_EXT);

if (!$user->data['session_logged_in'])
{
	$redirect_append = '?redirect=' . urlencode(ADM . '/' . 'index.' . PHP_EXT) . '&admin=1';
	redirect(append_sid(IP_ROOT_PATH . CMS_PAGE_LOGIN . $redirect_append, true));
}
elseif (!jr_admin_secure(basename($_SERVER['REQUEST_URI'])))
{
	message_die(GENERAL_ERROR, $lang['Error_Module_ID'], '', __LINE__, __FILE__);
}

$session_id = request_get_var('sid', '');
if ($session_id != $user->data['session_id'])
{
	redirect('index.' . PHP_EXT . '?sid=' . $user->data['session_id']);
}

if (empty($user->data['session_admin']))
{
	$redirect_append = '?redirect=' . urlencode(ADM . '/' . 'index.' . PHP_EXT) . '&admin=1';
	redirect(append_sid(IP_ROOT_PATH . CMS_PAGE_LOGIN . $redirect_append, true));
}

include_once(IP_ROOT_PATH . 'includes/functions_admin_phpbb3.' . PHP_EXT);

if (empty($no_page_header))
{
	// Not including the pageheader can be neccesarry if META tags are needed in the calling script.
	include('page_header_admin.' . PHP_EXT);
}

?>