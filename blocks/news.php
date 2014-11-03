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

if(!function_exists('cms_block_news'))
{
	function cms_block_news()
	{
		global $db, $cache, $config, $template, $user, $lang, $bbcode, $block_id, $cms_config_var, $cms_config_vars;
		@include_once(IP_ROOT_PATH . ATTACH_MOD_PATH . 'displaying.' . PHP_EXT);
		@include_once(IP_ROOT_PATH . 'includes/news.' . PHP_EXT);

		$template->_tpldata['no_news.'] = array();
		$template->_tpldata['news_categories.'] = array();
		$template->_tpldata['newsrow.'] = array();
		$template->_tpldata['newscol.'] = array();
		$template->_tpldata['news_detail.'] = array();
		$template->_tpldata['news_archives.'] = array();
		$template->_tpldata['arch.'] = array();
		$template->_tpldata['year.'] = array();
		$template->_tpldata['month.'] = array();
		$template->_tpldata['day.'] = array();
		$template->_tpldata['no_articles.'] = array();
		$template->_tpldata['articles.'] = array();
		$template->_tpldata['comments.'] = array();
		$template->_tpldata['pagination.'] = array();

		//$cms_config_var['md_news_cat_id'] = $cms_config_vars['md_news_cat_id'][$block_id];
		$cms_config_var['md_news_number'] = (intval($cms_config_vars['md_news_number'][$block_id]) && ($cms_config_vars['md_news_number'][$block_id] > 0)) ? $cms_config_vars['md_news_number'][$block_id] : $config['news_item_num'];
		$cms_config_var['md_news_sort'] = ($cms_config_vars['md_news_sort'][$block_id] == 1) ? '1' : '0';
		$cms_config_var['md_news_length'] = ((int) ($cms_config_vars['md_news_length'][$block_id] >= 0)) ? $cms_config_vars['md_news_length'][$block_id] : $config['news_item_trim'];
		//unset($cms_config_var);

		//$index_file = CMS_PAGE_HOME;
		$index_file = (!empty($_SERVER['SCRIPT_NAME'])) ? $_SERVER['SCRIPT_NAME'] : getenv('SCRIPT_NAME');
		//$page_query = $_SERVER['QUERY_STRING'];
		//$page_query = (!empty($_SERVER['QUERY_STRING'])) ? explode('&', $_SERVER['QUERY_STRING']) : explode('&', getenv('QUERY_STRING'));

		$portal_page_id = request_var('page', 0);
		$portal_page_id = !empty($portal_page_id) ? ('page=' . $portal_page_id . '&amp;') : '';

		$ubid_link = request_var('ubid', 0);
		$ubid_link = !empty($ubid_link) ? ('ubid=' . $ubid_link . '&amp;') : '';

		$template->set_filenames(array('news' => 'blocks/news_block.tpl'));

		$content =& new NewsModule(IP_ROOT_PATH);

		$content->setVariables(array(
			'INDEX_FILE' => htmlspecialchars(urldecode($index_file)),
			'PORTAL_PAGE_ID' => $portal_page_id . $ubid_link,
			'PHP_EXT' => PHP_EXT,
			'S_NEWS_VIEWS' => (($user->data['user_level'] == ADMIN) && !empty($config['disable_topic_view'])) ? true : false,
			'S_COLS' => 4,

			'L_INDEX' => $lang['Index'],
			'L_CATEGORIES' => $lang['Categories'],
			'L_BY' => $lang['By'],
			'L_NO_NEWS_CATS' => $lang['No_News_Cats'],
			'L_NO_NEWS' => $lang['No_News'],
			'L_NEWS_CATEGORIES' => $lang['News_Categories'],
			'L_NEWS_ARCHIVES' => $lang['News_Archives'],
			'L_NEWS_SUMMARY' => $lang['News_Summary'],
			'L_NEWS_VIEWS' => $lang['News_Views'],
			'L_NEWS_AND' => $lang['News_And'],
			'L_NEWS_COMMENTS' => $lang['News_Comments'],
			'L_NEWS_CATS' => $lang['News_Cats'],
			'L_REPLY_NEWS' => $lang['News_Reply'],
			'L_PRINT_NEWS' => $lang['News_Print'],
			'L_EMAIL_NEWS' => $lang['News_Email'],
			'L_ARCHIVES' => $lang['Archives']
			)
		);

		$news = request_var('news', '');
		if($news == 'categories')
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
			for ($i = 0; $i < sizeof($news_cats); $i += 4)
			{
				if ($cats > 0)
				{
					$template->assign_block_vars('newsrow', array());
				}
				for ($j = $i; $j < ($i + 4); $j++)
				{
					if($j >= sizeof($news_cats))
					{
						break;
					}
					$template->assign_block_vars('newsrow.newscol', array(
						//'THUMBNAIL' => $N_this->root_path . 'images/news/' . $news_cats[$j]['news_image'],
						'THUMBNAIL' => $news_cats[$j]['news_image'] ? $N_this->root_path . $config['news_path'] . '/' . $news_cats[$j]['news_image'] : '',
						'ID' => $news_cats[$j]['news_id'],
						'DESC' => $news_cats[$j]['news_category'],
						)
					);
					$template->assign_block_vars('newsrow.news_detail', array(
						'NEWSCAT' => $news_cats[$j]['news_category'],
						'CATEGORY' => $newsrow[$j]['news_category']
						)
					);
				}
			}
			$content->setVariables(array('NEWS_TITLE' => $lang['News_Cmx'] . ' ' . $lang['Categories']));
			$content->renderTopics();
		}
		elseif($news == 'archives')
		{
			$year = request_var('year', 0);
			$month = request_var('month', 0);
			$day = request_var('day', 0);
			$key = request_var('key', '');

			$template->assign_block_vars('news_archives', array());
			$content->setVariables(array('NEWS_TITLE' => $lang['News_Cmx'] . ' ' . $lang['Archives']));
			$content->renderArchives($year, $month, $day, $key);
		}
		else
		{
			// View news articles.
			$topic_id = request_var('topic_id', 0);
			$news_id = request_var('news_id', 0);
			$topic_id = (empty($topic_id) && !empty($news_id)) ? $news_id : $topic_id;
			$topic_id = ($topic_id < 0) ? 0 : $topic_id;

			if (!empty($topic_id))
			{
				$content->is_topic = true;
			}

			$content->setVariables(array('NEWS_TITLE' => $lang['News_Cmx'] . ' ' . $lang['Articles']));
			$content->renderArticles($topic_id);
		}

		$content->renderPagination();
		//$content->display();
		//$content->clear();
	}
}

cms_block_news();

?>