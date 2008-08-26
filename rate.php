<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

// CTracker_Ignore: File checked by human
define('IN_PHPBB', true);
// MG Cash MOD For IP - BEGIN
define('IN_CASHMOD', true);
define('CM_POSTING', true);
// MG Cash MOD For IP - END
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.' . $phpEx);

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

include_once($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_rate.' . $phpEx);
include_once($phpbb_root_path . 'includes/functions_groups.' . $phpEx);
include_once($phpbb_root_path . 'includes/functions_rate.' . $phpEx);

$params = array('rate_mode', 'forum_top', 'topic_id', 'rating');

foreach($params as $var)
{
	$$var = '';
	if( isset($_POST[$var]) || isset($_GET[$var]) )
	{
		$$var = ( isset($_POST[$var]) ) ? $_POST[$var] : $_GET[$var];
	}
}
/*******************************************************************************************
/** Page Titles if Specific!
/******************************************************************************************/
$meta_description = '';
$meta_keywords = '';
switch($rate_mode)
{
	case 'rate':
		$page_title = $lang['Rating'];
		$template->assign_vars(array(
			"META" => '<meta http-equiv="refresh" content="3;url=' . append_sid(VIEWTOPIC_MG . '?' . POST_TOPIC_URL . '=' . $topic_id) . '">')
		);
	case 'rerate':
		$template->assign_vars(array(
			"META" => '<meta http-equiv="refresh" content="3;url=' . append_sid(VIEWTOPIC_MG . '?' . POST_TOPIC_URL . '=' . $topic_id) . '">')
		);
	break;
	case 'detailed':
	{
		if ($topic_id == '')
		{
			message_die(GENERAL_ERROR, $lang['No_Topic_ID'], '', __LINE__, __FILE__);
		}
		$page_title = $lang['Topic_Rating_Details'];
		break;
	}
	default:
	{
		if ($forum_top == '')
		{
			$forum_top = -1;
		}
		$page_title = sprintf($lang['Top_Topics'], $board_config['large_rating_return_limit']);
		break;
	}
}
/*******************************************************************************************
/** Include Header (It Contains Rate Functions).
/******************************************************************************************/
include($phpbb_root_path . 'includes/page_header.' . $phpEx);

/*******************************************************************************************
/** Display modes, for if the page is called seperately
/******************************************************************************************/
switch($rate_mode)
{
	case 'rate':
		rate_topic($userdata['user_id'], $topic_id, $rating, 'rate');
		break;
	case 'rerate':
		rate_topic($userdata['user_id'], $topic_id, $rating, 'rerate');
	break;
	case 'detailed':
	{
		ratings_detailed($topic_id);
		break;
	}
	default:
	{
		ratings_large();
		break;
	}
}
nivisec_copyright();
include($phpbb_root_path . 'includes/page_tail.' . $phpEx);

?>