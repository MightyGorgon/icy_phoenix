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
* Ptirhiik (admin@rpgnet-fr.com)
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
	'Lang_extend_lang_extend' => 'Extension for languages packs',
	'Lang_extend__custom' => 'Custom language pack',
	'Lang_extend__phpBB' => 'Icy Phoenix language pack',

	'Languages' => 'Languages',
	'Lang_management' => 'Management',
	'Lang_extend' => 'Lang extend management',
	'Lang_extend_explain' => 'Here you can add or modify languages key entries',
	'Lang_extend_pack' => 'Language Pack',
//	'Lang_extend_pack_explain' => 'This is the name of the pack, usualy the name of the MOD refering to',
	'Lang_extend_pack_explain' => 'Lang pack name',

	'Lang_extend_entry' => 'Language key entry',
	'Lang_extend_entries' => 'Language key entries',
	'Lang_extend_level_edit' => 'Edit',
	'Lang_extend_level_admin' => 'Admin',
	'Lang_extend_level_normal' => 'Normal',

	'Lang_extend_add_entry' => 'Add a new lang key entry',

	'Lang_extend_key_main' => 'Language main key entry',
	'Lang_extend_key_main_explain' => 'This is the main key entry, usualy the only one',
	'Lang_extend_key_sub' => 'Secondary key entry',
	'Lang_extend_key_sub_explain' => 'This second level key entry is usualy not used',
	'Lang_extend_level' => 'Level of the lang key entry',
	'Lang_extend_level_explain' => 'Admin level can only be used in the admin configuration panel. Normal level can be used everywhere.',

	'Lang_extend_missing_value' => 'You have to provide at least the English value',
	'Lang_extend_key_missing' => 'Main entry key is missing',
	'Lang_extend_duplicate_entry' => 'This entry already exists (see pack %)',

	'Lang_extend_update_done' => 'The entry has been successfully updated.<br /><br />Click %sHere%s to return to the entry.<br /><br />Click %sHere%s to return to entries list',
	'Lang_extend_delete_done' => 'The entry has been successfully deleted.<br />Note that only customized key entries are deleted, not the basic key entries if exist.<br /><br />Click %sHere%s to return to entries list',

	'Lang_extend_search' => 'Search in language key entries',
	'Lang_extend_search_words' => 'Words to find',
	'Lang_extend_search_words_explain' => 'Separate words with a space',
	'Lang_extend_search_all' => 'All words',
	'Lang_extend_search_one' => 'One of those',
	'Lang_extend_search_in' => 'Search in',
	'Lang_extend_search_in_explain' => 'Precise where to search',
	'Lang_extend_search_in_key' => 'Keys',
	'Lang_extend_search_in_value' => 'Values',
	'Lang_extend_search_in_both' => 'Both',
	'Lang_extend_search_all_lang' => 'All languages installed',

	'Lang_extend_search_no_words' => 'No words to search provided.<br /><br />Click %sHere%s to return to the pack list.',
	'Lang_extend_search_results' => 'Search results',
	'Lang_extend_value' => 'Value',
	'Lang_extend_level_leg' => 'Level',

	'Lang_extend_added_modified' => '*',
	'Lang_extend_modified' => 'Modified',
	'Lang_extend_added' => 'Added',
	)
);

?>