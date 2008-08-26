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

if (!defined('IN_PHPBB'))
{
	die('Hacking attempt');
}

if(!function_exists(imp_news_block_func))
{
	function imp_news_block_func()
	{
		global $phpbb_root_path, $phpEx, $lang, $template, $board_config, $db, $bbcode, $block_id, $cms_config_var, $cms_config_vars;
		@include_once($phpbb_root_path . ATTACH_MOD_PATH . 'displaying.' . $phpEx);
		@include_once($phpbb_root_path . 'includes/news.' . $phpEx);

		$template->_tpldata['no_news.'] = array();
		//reset($template->_tpldata['no_news.']);
		$template->_tpldata['news_categories.'] = array();
		//reset($template->_tpldata['news_categories.']);
		$template->_tpldata['newsrow.'] = array();
		//reset($template->_tpldata['newsrow.']);
		$template->_tpldata['newscol.'] = array();
		//reset($template->_tpldata['newscol.']);
		$template->_tpldata['news_detail.'] = array();
		//reset($template->_tpldata['news_detail.']);
		$template->_tpldata['news_archives.'] = array();
		//reset($template->_tpldata['news_archives.']);
		$template->_tpldata['arch.'] = array();
		//reset($template->_tpldata['arch.']);
		$template->_tpldata['year.'] = array();
		//reset($template->_tpldata['year.']);
		$template->_tpldata['month.'] = array();
		//reset($template->_tpldata['month.']);
		$template->_tpldata['day.'] = array();
		//reset($template->_tpldata['day.']);
		$template->_tpldata['no_articles.'] = array();
		//reset($template->_tpldata['no_articles.']);
		$template->_tpldata['articles.'] = array();
		//reset($template->_tpldata['articles.']);
		$template->_tpldata['comments.'] = array();
		//reset($template->_tpldata['comments.']);
		$template->_tpldata['pagination.'] = array();
		//reset($template->_tpldata['pagination.']);

		//$cms_config_var['md_news_cat_id'] = $cms_config_vars['md_news_cat_id'][$block_id];
		$cms_config_var['md_news_number'] = (intval($cms_config_vars['md_news_number'][$block_id]) && ($cms_config_vars['md_news_number'][$block_id] > 0)) ? $cms_config_vars['md_news_number'][$block_id] : $board_config['news_item_num'];
		$cms_config_var['md_news_sort'] = ($cms_config_vars['md_news_sort'][$block_id] == 1) ? '1' : '0';
		$cms_config_var['md_news_length'] = (intval($cms_config_vars['md_news_length'][$block_id]) && ($cms_config_vars['md_news_length'][$block_id] > 0)) ? $cms_config_vars['md_news_length'][$block_id] : $board_config['news_item_trim'];

		//unset($cms_config_var);

		//$index_file = PORTAL_MG;
		$index_file = (!empty($_SERVER['PHP_SELF'])) ? $_SERVER['PHP_SELF'] : getenv('PHP_SELF');
		//$page_query = $_SERVER['QUERY_STRING'];
		//$page_query = (!empty($_SERVER['QUERY_STRING'])) ? explode('&', $_SERVER['QUERY_STRING']) : explode('&', getenv('QUERY_STRING'));
		$portal_page_id = '';
		if(isset($_GET['page']))
		{
			$portal_page_id = 'page=' . intval($_GET['page']) . '&amp;';
		}

		$ubid_link = '';
		if(isset($_GET['ubid']))
		{
			$ubid_link = 'ubid=' . htmlspecialchars(intval($_GET['ubid'])) . '&amp;';
		}

		$template->set_filenames(array('news' => 'blocks/news_block.tpl'));

		$content =& new NewsModule($phpbb_root_path);

		$content->setVariables(array(
			'INDEX_FILE' => htmlspecialchars(urldecode($index_file)),
			'PORTAL_PAGE_ID' => $portal_page_id . $ubid_link,
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
			'PHP_EXT' => $phpEx,
			'S_COLS' => 4,
			'L_ARCHIVES' => $lang['Archives']
			)
		);

		if(isset($_GET['news']) && ($_GET['news'] == 'categories'))
		{
			// View the news categories.
			$data_access = new NewsDataAccess($phpbb_root_path);
			$news_cats = $data_access->fetchCategories();
			$template->assign_block_vars('news_categories', array());
			$cats = count($news_cats);

			if ($cats == 0)
			{
				$template->assign_block_vars('no_news', array());
			}
			for ($i = 0; $i < count($news_cats); $i += 4)
			{
				if ($cats > 0)
				{
					$template->assign_block_vars('newsrow', array());
				}
				for ($j = $i; $j < ($i + 4); $j++)
				{
					if($j >= count($news_cats))
					{
						break;
					}
					$template->assign_block_vars('newsrow.newscol', array(
						//'THUMBNAIL' => $N_this->root_path . 'images/news/' . $news_cats[$j]['news_image'],
						'THUMBNAIL' => $N_this->root_path . $board_config['news_path'] . '/' . $news_cats[$j]['news_image'],
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
			$content->setVariables(array('TITLE' => $lang['News_Cmx'] . ' ' . $lang['Categories']));
			$content->renderTopics();
		}
		elseif(isset($_GET['news']) && ($_GET['news'] == 'archives'))
		{
			// View the news Archives.
			$year = (isset($_GET['year'])) ? $_GET['year'] : 0;
			$month = (isset($_GET['month'])) ? $_GET['month'] : 0;
			$day = (isset($_GET['day'])) ? $_GET['day'] : 0;
			$key = (isset($_GET['key'])) ? $_GET['key'] : '';

			$template->assign_block_vars('news_archives', array());
			$content->setVariables(array('TITLE' => $lang['News_Cmx'] . ' ' . $lang['Archives']));
			$content->renderArchives($year, $month, $day, $key);
		}
		else
		{
			// View news articles.
			$topic_id = 0;
			if(!empty($_GET['topic_id']))
			{
				$topic_id = intval($_GET['topic_id']);
			}
			elseif(!empty($_GET['news_id']))
			{
				$topic_id = intval($_GET['news_id']);
			}
			$topic_id = ($topic_id < 0) ? 0 : $topic_id;

			$content->setVariables(array('TITLE' => $lang['News_Cmx'] . ' ' . $lang['Articles']));
			$content->renderArticles($topic_id);
		}

		$content->renderPagination();
		//$content->display();
		//$content->clear();
	}
}

imp_news_block_func();

?>