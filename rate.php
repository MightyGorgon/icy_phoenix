<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

// MG Cash MOD For IP - BEGIN
define('IN_CASHMOD', true);
define('CM_POSTING', true);
// MG Cash MOD For IP - END
define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup();
// End session management

setup_extra_lang(array('lang_rate'));
include_once(IP_ROOT_PATH . 'includes/functions_rate.' . PHP_EXT);

$rate_mode = request_var('rate_mode', '');
$forum_top = request_var('forum_top', '');
$topic_id = request_var('topic_id', 0);
$rating = request_post_var('rating', 0);

// Page Titles if Specific!

$meta_content['description'] = '';
$meta_content['keywords'] = '';
switch($rate_mode)
{
	case 'rate':
		$meta_content['page_title'] = $lang['Rating'];
	case 'rerate':
		$redirect_url = append_sid(CMS_PAGE_VIEWTOPIC . '?' . POST_TOPIC_URL . '=' . $topic_id);
		meta_refresh(3, $redirect_url);
	break;
	case 'detailed':
		if ($topic_id == '')
		{
			message_die(GENERAL_ERROR, $lang['No_Topic_ID'], '', __LINE__, __FILE__);
		}
		$meta_content['page_title'] = $lang['Topic_Rating_Details'];
		break;
	default:
		if ($forum_top == '')
		{
			$forum_top = -1;
		}
		$meta_content['page_title'] = sprintf($lang['Top_Topics'], $config['large_rating_return_limit']);
		break;
}

// Include Header (It Contains Rate Functions).

if ($rate_mode == 'detailed')
{
	$nav_server_url = create_server_url();
	$breadcrumbs['address'] = $lang['Nav_Separator'] . '<a href="' . $nav_server_url . append_sid('rate.' . PHP_EXT) . '">' . $lang['Rating'] . '</a>' . $lang['Nav_Separator'] . '<a class="nav-current" href="' . append_sid(CMS_PAGE_VIEWTOPIC . '?' . POST_TOPIC_URL . '=' . $topic_id) . '">' . id_to_value($topic_id, 'topic') . '</a>';
	$breadcrumbs['bottom_right_links'] = '<span class="gensmall">' . sprintf($lang['Click_return_topic'], '<a href="' . append_sid(CMS_PAGE_VIEWTOPIC . '?' . POST_TOPIC_URL . '=' . $topic_id) . '">', '</a>') . '</span>';
}

// Display modes, for if the page is called seperately

switch($rate_mode)
{
	case 'rate':
		rate_topic($user->data['user_id'], $topic_id, $rating, 'rate');
		break;
	case 'rerate':
		rate_topic($user->data['user_id'], $topic_id, $rating, 'rerate');
	break;
	case 'detailed':
		ratings_detailed($topic_id);
		nivisec_copyright();
		page_header();
		$template->set_filenames(array('body' => 'rate_detailed.tpl'));
		page_footer();
		break;
	default:
		ratings_large();
		nivisec_copyright();
		page_header();
		$template->set_filenames(array('body' => 'rate_main.tpl'));
		page_footer();
		break;
}

?>
