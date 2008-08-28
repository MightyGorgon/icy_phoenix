<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/*
* This script is meant to remove from search tables all stopwords and smileys
* to reduce table size but also to allow better tags indexing
*/

define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.' . $phpEx);
include($phpbb_root_path . 'includes/functions_search.' . $phpEx);

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

if(!$userdata['session_logged_in'])
{
	redirect(append_sid(LOGIN_MG . '?redirect=stopwords.' . $phpEx, true));
	/*
	$header_location = (@preg_match('/Microsoft|WebSTAR|Xitami/', getenv('SERVER_SOFTWARE'))) ? 'Refresh: 0; URL=' : 'Location: ';
	header($header_location . append_sid(LOGIN_MG . "?redirect=stopwords.$phpEx", true));
	exit;
	*/
}

if($userdata['user_level'] != ADMIN)
{
	message_die(GENERAL_MESSAGE, 'You are not authorised to access this page');
}

$stopwords_array = file($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/search_stopwords.txt');

$exclude_list = '';
foreach($stopwords_array as $curr_word)
{
	$exclude_list .= (($exclude_list != '') ? ', ' : '') . "'" . trim($curr_word) . "'";
}

// Smileys
$sql = "SELECT code FROM " . SMILIES_TABLE;
if ($result = $db->sql_query($sql))
{
	while ($row = $db->sql_fetchrow($result))
	{
		$exclude_list .= (($exclude_list != '') ? ', ' : '') . "'" . trim(ereg_replace("[^A-Za-z0-9]", '', $row['code'])) . "'";
	}
	$db->sql_freeresult($result);
}

$sql = "SELECT word_id
	FROM " . SEARCH_WORD_TABLE . "
	WHERE word_text IN (" . $exclude_list . ")";
if (!($result = $db->sql_query($sql)))
{
	message_die(GENERAL_ERROR, 'Could not obtain common word list', '', __LINE__, __FILE__, $sql);
}

$common_word_id = '';
while ($row = $db->sql_fetchrow($result))
{
	$common_word_id .= (($common_word_id != '') ? ', ' : '') . $row['word_id'];
}

if ($common_word_id == '')
{
	message_die(GENERAL_ERROR,'None of the words in the list are in your search_tables.<br />Note: This could also mean the list is empty ;-)');
}
//echo '>'.trim($curr_word)."<<br />";
//echo $exclude_list .'<br />'. $common_word_id;
//exit;

$sql = "DELETE FROM " . SEARCH_WORD_TABLE . "
	WHERE word_id IN ($common_word_id)";
if (!$db->sql_query($sql))
{
	message_die(GENERAL_ERROR, 'Could not delete word match entry', '', __LINE__, __FILE__, $sql);
}
$sql = "OPTIMIZE TABLE " . SEARCH_WORD_TABLE;
if (!$db->sql_query($sql))
{
	message_die(GENERAL_ERROR, 'Could not optimize', '', __LINE__, __FILE__, $sql);
}

$sql = "DELETE FROM " . SEARCH_MATCH_TABLE . "
	WHERE word_id IN ($common_word_id)";
if (!$db->sql_query($sql))
{
	message_die(GENERAL_ERROR, 'Could not delete word match entry', '', __LINE__, __FILE__, $sql);
}
$sql = "OPTIMIZE TABLE " . SEARCH_MATCH_TABLE;
if (!$db->sql_query($sql))
{
	message_die(GENERAL_ERROR, 'Could not optimize', '', __LINE__, __FILE__, $sql);
}

$db->clear_cache();

message_die(GENERAL_MESSAGE,'<b>Done!</b><br /><br />The following list-entries have been removed from your searchtables:<br /><br />' . $exclude_list);
//echo $exclude_list .'<br />'. $common_word_id;

?>