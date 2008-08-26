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
* 2003 Freakin' Booty ;-P & Antony Bailey
*
*/

define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.' . $phpEx);

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

if( !$userdata['session_logged_in'] )
{
	$header_location = ( @preg_match('/Microsoft|WebSTAR|Xitami/', getenv('SERVER_SOFTWARE')) ) ? 'Refresh: 0; URL=' : 'Location: ';
	header($header_location . append_sid(LOGIN_MG . "?redirect=db_update.$phpEx", true));
	exit;
}

if($userdata['user_level'] != ADMIN)
{
	message_die(GENERAL_MESSAGE, 'You are not authorised to access this page');
}


$page_title = 'Updating the database';
$meta_description = '';
$meta_keywords = '';
include($phpbb_root_path . 'includes/page_header.' . $phpEx);

echo '<table width="100%" cellspacing="1" cellpadding="2" border="0" class="forumline">';
echo '<tr><th>Updating the database</th></tr><tr><td><span class="genmed"><ul type="circle">';


$sql = array();
{SQL_OUTPUT}

for( $i = 0; $i < count($sql); $i++ )
{
	if( !$result = $db->sql_query ($sql[$i]) )
	{
		$error = $db->sql_error();

		echo '<li>' . $sql[$i] . '<br /> +++ <span class="text_red">Error:</span> ' . $error['message'] . '</li><br />';
	}
	else
	{
		echo '<li>' . $sql[$i] . '<br /> +++ <span class="text_green">Successfull</span></li><br />';
	}
}


echo '</ul></span></td></tr><tr><td class="catBottom" height="28">&nbsp;</td></tr>';

echo '<tr><th>End</th></tr><tr><td><span class="genmed">Installation is now finished. Please be sure to delete this file now.<br />If you have run into any errors, please visit the <a href="http://www.phpbbsupport.co.uk" target="_blank">phpBBSupport.co.uk</a> and ask someone for help.</span></td></tr>';
echo '<tr><td class="catBottom" height="28" align="center"><span class="genmed"><a href="' . append_sid(FORUM_MG) . '">Have a nice day</a></span></td></table>';

include($phpbb_root_path . 'includes/page_tail.' . $phpEx);

?>