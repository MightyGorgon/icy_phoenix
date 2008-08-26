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
* Ptirhiik (admin@rpgnet-fr.com)
*
*/

/**
*
* LAST UPDATE {TIME} by {USERNAME}
*
*/

if (!defined('IN_PHPBB'))
{
	die('Hacking attempt');
	exit;
}

//--------------------------------------------------------------------------------------------------
//
// $tree : designed to get all the hierarchy
// ------
//
//	indexes :
//		- id : full designation : ie Root, f3, c20
//		- idx : rank order
//
//	$tree['keys'][id]			=> idx,
//	$tree['type'][idx]			=> type of the row, can be 'c' for categories or 'f' for forums,
//	$tree['id'][idx]			=> value of the row id : cat_id for cats, forum_id for forums,
//	$tree['data'][idx]			=> db table row,
//	$tree['main'][idx]			=> parent id,
//	$tree['sub'][id]			=> array of sub-level ids,
//--------------------------------------------------------------------------------------------------

$tree = array(
	'keys'	=> array({KEYS}),
	'type'	=> array({TYPES}),
	'id'	=> array({IDS}),
	'data'	=> array(
		<!-- BEGIN data -->
		array(
			<!-- BEGIN field -->
			'{data.field.FIELD_NAME}'	=> '{data.field.FIELD_VALUE}',
			<!-- END field -->
		),
		<!-- END data -->
	),
	'main'	=> array({MAINS}),
	'sub'	=> array(
		<!-- BEGIN sub -->
		'{sub.THIS}'	=> array({sub.SUBS}),
		<!-- END sub -->
	),
	'mods'	=> array(
		<!-- BEGIN mods -->
		{mods.IDX} => array(
			'user_id'		=> array({mods.USER_IDS}),
			'username'		=> array({mods.USERNAMES}),
			'group_id'		=> array({mods.GROUP_IDS}),
			'group_name'	=> array({mods.GROUP_NAMES}),
		),
		<!-- END mods -->
	),
);