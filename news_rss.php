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
* CodeMonkeyX.net (webmaster@codemonkeyx.net)
*
*/

// Added to optimize memory for attachments
define('ATTACH_DISPLAY', true);
define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.' . $phpEx);

if( $board_config['allow_rss'] != 1 )
{
	echo 'RSS has been disabled for this site';
	return;
}

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

include_once($phpbb_root_path . 'includes/news.' . $phpEx);

header("Content-type: text/xml");

// Tell the template class which template to use.
$template->set_filenames(array('news' => 'news_200_rss_body.tpl'));

$content =& new NewsModule($phpbb_root_path);

$content->setVariables( array(
	'L_INDEX' => $lang['Index'],
	'L_CATEGORIES' => $lang['Categories'],
	'L_ARCHIVES' => $lang['Archives']
	)
);

$content->renderSyndication();

$content->display();
$content->clear();

?>