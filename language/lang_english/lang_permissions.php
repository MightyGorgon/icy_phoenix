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
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge($lang, array(

	// ROLES - BEGIN
	'ROLE_CMS_CONTENT_MANAGER' => 'Content Manager',
	'ROLE_CMS_CONTENT_MANAGER_DESCRIPTION' => 'Full permissions in CMS.',
	'ROLE_CMS_REVIEWER' => 'Reviewer',
	'ROLE_CMS_REVIEWER_DESCRIPTION' => 'Almost all permissions in CMS, apart of removing blocks.',
	'ROLE_CMS_PUBLISHER' => 'Publisher',
	'ROLE_CMS_PUBLISHER_DESCRIPTION' => 'Permissions to add content.',
	'ROLE_CMS_USER' => 'User',
	'ROLE_CMS_USER_DESCRIPTION' => 'Permissions to view content.',
	'ROLE_CMS_GUEST' => 'Guest',
	'ROLE_CMS_GUEST_DESCRIPTION' => 'No permissions.',
	'ROLE_ADMIN_FULL' => 'Full Admin',
	'ROLE_ADMIN_FULL_DESCRIPTION' => 'Has access to all administrative functions of this site.',
	'ROLE_ADMIN_STANDARD' => 'Standard Admin',
	'ROLE_ADMIN_STANDARD_DESCRIPTION' => 'Has access to most administrative features but is not allowed to use server or system related tools.',
	'ROLE_MOD_FULL' => 'Full Moderator',
	'ROLE_MOD_FULL_DESCRIPTION' => 'Can use all moderating tools.',
	'ROLE_MOD_STANDARD' => 'Standard Moderator',
	'ROLE_MOD_STANDARD_DESCRIPTION' => 'Can use most moderating tools.',
	'ROLE_MOD_SIMPLE' => 'Simple Moderator',
	'ROLE_MOD_SIMPLE_DESCRIPTION' => 'Can only use basic topic actions.',
	'ROLE_USER_FULL' => 'All Features',
	'ROLE_USER_FULL_DESCRIPTION' => 'Can use all available features for users.',
	'ROLE_USER_STANDARD' => 'Standard Features',
	'ROLE_USER_STANDARD_DESCRIPTION' => 'Can use standard available features for users.',
	'ROLE_USER_LIMITED' => 'Limited Features',
	'ROLE_USER_LIMITED_DESCRIPTION' => 'Can use only some of the features available for users.',
	'ROLE_FORUM_FULL' => 'Full Access',
	'ROLE_FORUM_FULL_DESCRIPTION' => 'Can use all forum features. Not recommended for normal users.',
	'ROLE_FORUM_STANDARD' => 'Standard Access',
	'ROLE_FORUM_STANDARD_DESCRIPTION' => 'Can use most forum features.',
	'ROLE_FORUM_NOACCESS' => 'No Access',
	'ROLE_FORUM_NOACCES_DESCRIPTIONS' => 'Can neither see nor access the forum.',
	// ROLES - END

	)
);

?>