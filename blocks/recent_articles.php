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

if(!function_exists('cms_block_recent_articles'))
{
	function cms_block_recent_articles()
	{
		// if article approved in the table is equal to 1 the article has been approved, else it is not approved, so don't show it.
		global $db, $cache, $config, $template, $userdata, $lang, $table_prefix, $style_row, $block_id, $cms_config_vars;

		$template->_tpldata['recent_articles.'] = array();

		@include_once(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);
		$sql = "SELECT * FROM " . KB_ARTICLES_TABLE . " ORDER BY article_id DESC LIMIT " . $cms_config_vars['md_total_articles'][$block_id];
		$result = $db->sql_query($sql);

		//now lets get our info
		if ($row = $db->sql_fetchrow($result))
		{
			$i = 0;
			do
			{
				if($row['approved'] == 1)
				{
					$title = $row['article_title'];
					$author_id = $row['article_author_id'];
					$author = colorize_username ($author_id);
					$article_category_id = $row['article_id'];
					$url = append_sid(IP_ROOT_PATH . 'kb.' . PHP_EXT . '?mode=article&amp;k=' . $article_category_id);
					if($cms_config_vars['md_recent_articles_style'][$block_id] == '1')
					{
						$style_row = 'articles_scroll';
					}
					else
					{
						$style_row = 'articles_static';
					}
					$template->assign_block_vars($style_row, '');
					// Convert and clean special chars!
					$title = htmlspecialchars_clean($title);
					$template->assign_block_vars ($style_row . '.recent_articles', array(
							'TITLE' => $title,
							'U_ARTICLE' => $url,
							'AUTHOR' => $author,
							'DATE' => create_date_ip($config['default_dateformat'], $row['article_date'], $config['board_timezone'])
						)
					);
				}
				$i++;
			}
			while($row = $db->sql_fetchrow($result));
			$db->sql_freeresult($result);
		}
	}
}

//call the function to output the block.
cms_block_recent_articles();

?>