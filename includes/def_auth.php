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

$forum_auth_fields = array(
	'auth_view',
	'auth_read',
	'auth_post',
	'auth_reply',
	'auth_edit',
	'auth_delete',
	'auth_sticky',
	'auth_announce',
	'auth_globalannounce',
	'auth_news',
	'auth_cal',
	'auth_vote',
	'auth_pollcreate',
	'auth_attachments',
	'auth_download',
	'auth_ban',
	'auth_greencard',
	'auth_bluecard',
	'auth_rate'
);

$field_names = array(
	'auth_view' => $lang['View'],
	'auth_read' => $lang['Read'],
	'auth_post' => $lang['Post'],
	'auth_reply' => $lang['Reply'],
	'auth_edit' => $lang['Edit'],
	'auth_delete' => $lang['Delete'],
	'auth_sticky' => $lang['Sticky'],
	'auth_announce' => $lang['Announce'],
	'auth_globalannounce' => $lang['Globalannounce'],
	'auth_news' => $lang['Auth_News'],
	'auth_cal' => $lang['Calendar'],
	'auth_vote' => $lang['Vote'],
	'auth_pollcreate' => $lang['Pollcreate'],
	'auth_attachments' => $lang['Auth_attach'],
	'auth_download' => $lang['Auth_download'],
	'auth_ban' => $lang['Ban'],
	'auth_greencard' => $lang['Greencard'],
	'auth_bluecard' => $lang['Bluecard'],
	'auth_rate' => $lang['Auth_Rating']
);

// value description
// SELF AUTH - BEGIN
// Old auth constants replaced...
//$forum_auth_levels = array('NONE', 'ALL', 'REG', 'PRIVATE', 'MOD', 'ADMIN');
//$forum_auth_const = array(AUTH_NONE, AUTH_ALL, AUTH_REG, AUTH_ACL, AUTH_MOD, AUTH_ADMIN);
$forum_auth_levels = array('NONE', 'ALL', 'REG', 'SELF', 'PRIVATE', 'MOD', 'ADMIN');
$forum_auth_const = array(AUTH_NONE, AUTH_ALL, AUTH_REG, AUTH_SELF, AUTH_ACL, AUTH_MOD, AUTH_ADMIN);
// SELF AUTH - END

// presets
if ( defined('IN_ADMIN') )
{
	// all the presets
	/*
	01 View,
	02 Read,
	03 Post,
	04 Reply,
	05 Edit,
	06 Delete,
	07 Sticky,
	08 Announce,
	09 Global Ann,
	10 News,
	11 Calendar,
	12 Vote,
	13 Poll,
	14 Attach,
	15 Download,
	16 Warn,
	17 Unban,
	18 Report,
	19 Rate
	*/

	$simple_auth_types = array();
	$simple_auth_ary = array();

	$simple_auth_types[] = $lang['Default'];
	$simple_auth_ary[] = array(AUTH_ALL, AUTH_ALL, AUTH_REG, AUTH_REG, AUTH_REG, AUTH_MOD, AUTH_MOD, AUTH_ADMIN, AUTH_ADMIN, AUTH_ADMIN, AUTH_ADMIN, AUTH_REG, AUTH_REG, AUTH_REG, AUTH_REG, AUTH_MOD, AUTH_ADMIN, AUTH_REG, AUTH_NONE);

	$simple_auth_types[] = $lang['Public'];
	$simple_auth_ary[] = array(AUTH_ALL, AUTH_ALL, AUTH_ALL, AUTH_ALL, AUTH_REG, AUTH_MOD, AUTH_MOD, AUTH_ADMIN, AUTH_ADMIN, AUTH_ADMIN, AUTH_REG, AUTH_REG, AUTH_REG, AUTH_REG, AUTH_REG, AUTH_MOD, AUTH_ADMIN, AUTH_REG, AUTH_REG);

	$simple_auth_types[] = $lang['Registered'];
	$simple_auth_ary[] = array(AUTH_ALL, AUTH_ALL, AUTH_REG, AUTH_REG, AUTH_REG, AUTH_MOD, AUTH_MOD, AUTH_ADMIN, AUTH_ADMIN, AUTH_ADMIN, AUTH_REG, AUTH_REG, AUTH_REG, AUTH_REG, AUTH_REG, AUTH_MOD, AUTH_ADMIN, AUTH_REG, AUTH_REG);

	$simple_auth_types[] = $lang['Registered'] . ' [' . $lang['Hidden'] . ']';
	$simple_auth_ary[] = array(AUTH_REG, AUTH_REG, AUTH_REG, AUTH_REG, AUTH_REG, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_ADMIN, AUTH_MOD, AUTH_REG, AUTH_REG, AUTH_REG, AUTH_REG, AUTH_REG, AUTH_MOD, AUTH_ADMIN, AUTH_REG, AUTH_REG);

	$simple_auth_types[] = $lang['Private'];
	$simple_auth_ary[] = array(AUTH_ALL, AUTH_ACL, AUTH_ACL, AUTH_ACL, AUTH_ACL, AUTH_MOD, AUTH_ACL, AUTH_MOD, AUTH_ADMIN, AUTH_MOD, AUTH_ACL, AUTH_ACL, AUTH_ACL, AUTH_ACL, AUTH_ACL, AUTH_MOD, AUTH_ADMIN, AUTH_ACL, AUTH_ACL);

	$simple_auth_types[] = $lang['Private'] . ' [' . $lang['Hidden'] . ']';
	$simple_auth_ary[] = array(AUTH_ACL, AUTH_ACL, AUTH_ACL, AUTH_ACL, AUTH_ACL, AUTH_MOD, AUTH_ACL, AUTH_MOD, AUTH_ADMIN, AUTH_MOD, AUTH_ACL, AUTH_ACL, AUTH_ACL, AUTH_ACL, AUTH_ACL, AUTH_MOD, AUTH_ADMIN, AUTH_ACL, AUTH_ACL);

	$simple_auth_types[] =  $lang['Moderators'];
	$simple_auth_ary[] = array(AUTH_ALL, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_ADMIN, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_ADMIN, AUTH_MOD, AUTH_MOD);

	$simple_auth_types[] = $lang['Moderators'] . ' [' . $lang['Hidden'] . ']';
	$simple_auth_ary[] = array(AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_ADMIN, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_ADMIN, AUTH_MOD, AUTH_MOD);

	// Default for new forum in admin_forums.php
	$forum_auth_ary = array();
	for($i = 0; $i < count($forum_auth_fields); $i++)
	{
		$forum_auth_ary[$forum_auth_fields[$i]] = $simple_auth_ary[0][$i];
	}
	// OLD AUTH ARRAY HARDCODED
	/*
	$forum_auth_ary = array(
		'auth_view' => AUTH_ALL,
		'auth_read' => AUTH_ALL,
		'auth_post' => AUTH_REG,
		'auth_reply' => AUTH_REG,
		'auth_edit' => AUTH_REG,
		'auth_delete' => AUTH_MOD,
		'auth_sticky' => AUTH_MOD,
		'auth_announce' => AUTH_ADMIN,
		'auth_globalannounce' => AUTH_ADMIN,
		'auth_news' => AUTH_ADMIN,
		'auth_cal' => AUTH_ADMIN,
		'auth_vote' => AUTH_REG,
		'auth_pollcreate' => AUTH_REG,
		'auth_attachments' => AUTH_REG,
		'auth_download' => AUTH_REG,
		'auth_ban' => AUTH_MOD,
		'auth_greencard' => AUTH_ADMIN,
		'auth_bluecard' => AUTH_REG,
		'auth_rate' => AUTH_NONE
	);
	*/
}

?>