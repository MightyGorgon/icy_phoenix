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
* masterdavid - Ronald John David
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

if(!function_exists('cms_block_news_archive'))
{
	function cms_block_news_archive()
	{
		global $db, $cache, $config, $template, $user, $lang, $bbcode, $block_id, $cms_config_var, $cms_config_vars;
		@include_once(IP_ROOT_PATH . ATTACH_MOD_PATH . 'displaying.' . PHP_EXT);
		@include_once(IP_ROOT_PATH . 'includes/news.' . PHP_EXT);

		//$template->_tpldata['news_detail.'] = array();
		//$template->_tpldata['newscol.'] = array();
		$template->_tpldata['articles_fp.'] = array();
		$template->_tpldata['news_categories.'] = array();
		$template->_tpldata['newsrow.'] = array();
		$template->_tpldata['news_archives.'] = array();
		$template->_tpldata['arch.'] = array();
		$template->_tpldata['yes_news.'] = array();
		$template->_tpldata['no_news.'] = array();

		$img_width = ($cms_config_vars['md_news_images_width'][$block_id] < 20) ? '' : ('width="' . $cms_config_vars['md_news_images_width'][$block_id] . '"');

		//$index_file = CMS_PAGE_HOME;
		$index_file = (!empty($_SERVER['SCRIPT_NAME'])) ? $_SERVER['SCRIPT_NAME'] : getenv('SCRIPT_NAME');
		//$page_query = $_SERVER['QUERY_STRING'];
		//$page_query = (!empty($_SERVER['QUERY_STRING'])) ? explode('&', $_SERVER['QUERY_STRING']) : explode('&', getenv('QUERY_STRING'));

		$portal_page_id = request_var('page', 0);
		$portal_page_id = !empty($portal_page_id) ? ('page=' . $portal_page_id . '&amp;') : '';

		$ubid_link = request_var('ubid', 0);
		$ubid_link = !empty($ubid_link) ? ('ubid=' . $ubid_link . '&amp;') : '';

		$template->set_filenames(array('news' => 'blocks/news_archive_block.tpl'));

		$content = new NewsModule(IP_ROOT_PATH);

		$content->setVariables(array(
			'INDEX_FILE' => htmlspecialchars(urldecode($index_file)),
			'PORTAL_PAGE_ID' => $portal_page_id . $ubid_link,
			'PHP_EXT' => PHP_EXT,
			'S_NEWS_VIEWS' => (($user->data['user_level'] == ADMIN) && !empty($config['disable_topic_view'])) ? true : false,
			'S_COLS' => 4,

			'L_INDEX' => $lang['Index'],
			'L_CATEGORIES' => $lang['Categories'],
			'L_NO_NEWS_CATS' => $lang['No_News_Cats'],
			'L_NO_NEWS' => $lang['No_News'],
			'L_NEWS_CATEGORIES' => $lang['News_Categories'],
			'L_ALL_NEWS_CATEGORIES' => $lang['All_News_Categories'],
			'L_NEWS_ARCHIVES' => $lang['News_Archives'],
			'L_ALL_NEWS_ARCHIVES' => $lang['All_News_Archives'],
			'L_NEWS_SUMMARY' => $lang['News_Summary'],
			'L_NEWS_VIEWS' => $lang['News_Views'],
			'L_NEWS_CATS' => $lang['News_Cats'],
			'L_ARCHIVES' => $lang['Archives']
			)
		);

		if ($cms_config_vars['md_news_archive_type'][$block_id] == 1)
		{
			// View the news categories.
			$data_access = new NewsDataAccess(IP_ROOT_PATH);
			$news_cats = $data_access->fetchCategories();
			$template->assign_block_vars('news_categories', array());
			$cats = sizeof($news_cats);

			if ($cats == 0)
			{
				$template->assign_block_vars('no_news', array());
			}
			else
			{
				$template->assign_block_vars('yes_news', array());
			}
			$img_w = (empty($cms_config_vars['md_news_images_width'][$block_id]) ? '' : (' width: ' . $cms_config_vars['md_news_images_width'][$block_id] . ';'));
			for ($i = 0; $i < sizeof($news_cats); $i++)
			{
				$template->assign_block_vars('newsrow', array(
					//'THUMBNAIL' => $N_this->root_path . 'images/news/' . $news_cats[$j]['news_image'],
					'THUMBNAIL' => $N_this->root_path . $config['news_path'] . '/' . $news_cats[$i]['news_image'],
					'ID' => $news_cats[$i]['news_id'],
					'DESC' => $news_cats[$i]['news_category'],
					'NEWSCAT' => $news_cats[$i]['news_category'],
					'CATEGORY' => $newsrow[$i]['news_category'],
					'IMG_W' => $img_w,
					)
				);
			}
			$content->setVariables(array('NEWS_TITLE' => $lang['News_Cmx'] . ' ' . $lang['Categories']));
			$content->renderTopics();
		}
		else
		{
			// View the news Archives.
			$year = request_var('year', 0);
			$month = request_var('month', 0);
			$day = request_var('day', 0);
			$key = request_var('key', '');

			$template->assign_block_vars('news_archives', array());
			$content->setVariables(array('NEWS_TITLE' => $lang['News_Cmx'] . ' ' . $lang['Archives']));
			$content->renderArchives($year, $month, $day, $key, false);
		}
	}
}

cms_block_news_archive();

?>