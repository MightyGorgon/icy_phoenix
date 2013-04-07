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
	'ROLE_PLUGINS_FULL' => 'Full Access',
	'ROLE_PLUGINS_FULL_DESCRIPTION' => 'Can use all PLUGINS features. Not recommended for normal users.',
	'ROLE_PLUGINS_STANDARD' => 'Standard Access',
	'ROLE_PLUGINS_STANDARD_DESCRIPTION' => 'Can use most PLUGINS features.',
	'ROLE_PLUGINS_NOACCESS' => 'No Access',
	'ROLE_PLUGINS_NOACCES_DESCRIPTIONS' => 'Can neither see nor access the PLUGINS.',
	// ROLES - END

	)
);

// Define categories and permission types
$lang = array_merge($lang, array(
	'permission_cat' => array(
		'actions' => 'Actions',
		'blocks' => 'Blocks',
		'cms' => 'CMS',
		'content' => 'Content',
		'forums' => 'Forums',
		'layouts' => 'Pages',
		'misc' => 'Misc',
		'permissions' => 'Permissions',
		'plugins_actions' => 'Plugins Actions',
		'pm' => 'Private messages',
		'polls' => 'Polls',
		'post' => 'Post',
		'post_actions' => 'Post actions',
		'posting' => 'Posting',
		'profile' => 'Profile',
		'settings' => 'Settings',
		'topic_actions' => 'Topic actions',
		'user_group' => 'Users &amp; Groups',
	),

	// With defining 'global' here we are able to specify what is printed out if the permission is within the global scope.
	'permission_type' => array(
		'a_' => 'Admin permissions',
		'cms_' => 'CMS permissions',
		'cmsl_' => 'CMS pages permissions',
		'cmss_' => 'CMS standard pages permissions',
		'cmsb_' => 'CMS blocks permissions',
		'f_' => 'Forum permissions',
		'm_' => 'Moderator permissions',
		'pl_' => 'Plugins permissions',
		'u_' => 'User permissions',
		'global' => array(
			'm_' => 'Global moderator permissions',
		),
	),

	)
);

// Admin Permissions
$lang = array_merge($lang, array(
	'acl_a_user' => array('lang' => 'Can manage users<br /><em>This also includes seeing the users browser agent within the viewonline list.</em>', 'cat' => 'user_group'),
	'acl_a_group' => array('lang' => 'Can manage groups', 'cat' => 'user_group'),

	'acl_a_modules' => array('lang' => 'Can manage modules', 'cat' => 'permissions'),
	'acl_a_viewauth' => array('lang' => 'Can view permission masks', 'cat' => 'permissions'),
	'acl_a_authgroups' => array('lang' => 'Can alter permissions for individual groups', 'cat' => 'permissions'),
	'acl_a_authusers' => array('lang' => 'Can alter permissions for individual users', 'cat' => 'permissions'),
	'acl_a_fauth' => array('lang' => 'Can alter forum permission class', 'cat' => 'permissions'),
	'acl_a_mauth' => array('lang' => 'Can alter moderator permission class', 'cat' => 'permissions'),
	'acl_a_aauth' => array('lang' => 'Can alter admin permission class', 'cat' => 'permissions'),
	'acl_a_uauth' => array('lang' => 'Can alter user permission class', 'cat' => 'permissions'),
	'acl_a_roles' => array('lang' => 'Can manage roles', 'cat' => 'permissions'),
	)
);

// CMS Permissions
$lang = array_merge($lang, array(
	'acl_cms_admin' => array('lang' => 'Can access CMS Management', 'cat' => 'cms'),
	'acl_cms_settings' => array('lang' => 'Can manage CMS Settings', 'cat' => 'cms'),
	'acl_cms_layouts' => array('lang' => 'Can manage CMS Pages', 'cat' => 'cms'),
	'acl_cms_layouts_special' => array('lang' => 'Can manage Standard Pages', 'cat' => 'cms'),
	'acl_cms_blocks' => array('lang' => 'Can manage Blocks', 'cat' => 'cms'),
	'acl_cms_blocks_global' => array('lang' => 'Can manage Global Blocks', 'cat' => 'cms'),
	'acl_cms_permissions' => array('lang' => 'Can manage Permissions', 'cat' => 'cms'),
	'acl_cms_menu' => array('lang' => 'Can manage Navigation Menu', 'cat' => 'cms'),
	'acl_cms_ads' => array('lang' => 'Can manage Advertising', 'cat' => 'cms'),

	// Only Local
	'acl_cmsl_admin' => array('lang' => 'Can manage page content', 'cat' => 'layouts'),

	'acl_cmss_admin' => array('lang' => 'Can manage page content', 'cat' => 'layouts'),

	'acl_cmsb_admin' => array('lang' => 'Can manage block content', 'cat' => 'blocks'),
	)
);

// Forum Permissions
$lang = array_merge($lang, array(
	'acl_f_html' => array('lang' => 'Can insert HTML in posts', 'cat' => 'post'),

	'acl_f_topicdelete' => array('lang' => 'Can delete topics', 'cat' => 'actions'),
	)
);

// Moderator Permissions
$lang = array_merge($lang, array(
	'acl_m_topicdelete' => array('lang' => 'Can delete topics', 'cat' => 'topic_actions'),
	)
);

// Plugins Permissions
$lang = array_merge($lang, array(
	'acl_pl_admin' => array('lang' => 'Can manage content in Plugins', 'cat' => 'plugins_actions'),
	'acl_pl_input' => array('lang' => 'Can insert content in Plugins', 'cat' => 'plugins_actions'),
	'acl_pl_edit' => array('lang' => 'Can edit content in Plugins', 'cat' => 'plugins_actions'),
	'acl_pl_delete' => array('lang' => 'Can remove content in Plugins', 'cat' => 'plugins_actions'),
	)
);

// User Permissions
$lang = array_merge($lang, array(
	'acl_u_html' => array('lang' => 'Can insert HTML code in posts', 'cat' => 'post'),
	)
);


?>