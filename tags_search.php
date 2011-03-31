<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);

// Start session management
$user->session_begin();
//$auth->acl($user->data);
$user->setup();
// End session management

// CMS - BEGIN
$cms_page['page_id'] = 'tags';
$cms_page['page_nav'] = false;
$cms_page['global_blocks'] = false;
$cms_auth_level = (isset($cms_config_layouts[$cms_page['page_id']]['view']) ? $cms_config_layouts[$cms_page['page_id']]['view'] : AUTH_ALL);
check_page_auth($cms_page['page_id'], $cms_auth_level);
// CMS - END

// COMMON - BEGIN
@include_once(IP_ROOT_PATH . 'includes/class_topics_tags.' . PHP_EXT);
$class_topics_tags = new class_topics_tags();
// COMMON - END

// VARS - BEGIN
$tag_text = request_var('tag_text', '', true);
$tag_text = ip_clean_string(urldecode(trim($tag_text)), $lang['ENCODING'], true);
// VARS - END

if (!empty($tag_text) && ($tag_text != $lang['TAGS_SEARCH']))
{
	$tags_list = $class_topics_tags->search_tag($tag_text);
	if (!empty($tags_list))
	{
		$server_url = create_server_url();
		$tags_box = '<ul class="no-bullet">';
		foreach ($tags_list as $k => $v)
		{
			$tags_box .= '<li><a href="' . $server_url . 'tags.' . PHP_EXT . '?mode=view&amp;tag_text=' . htmlspecialchars(urlencode($v['tag_text'])) . '">' . htmlspecialchars($v['tag_text']) . '</a></li>';
		}
		$tags_box .= '</ul>';
		echo($tags_box);
	}
	else
	{
		echo($lang['TAGS_SEARCH_NO_RESULTS']);
	}
}

flush;
exit;

?>