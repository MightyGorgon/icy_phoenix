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

if(!function_exists('cms_block_kb'))
{
	function cms_block_kb()
	{
		global $db, $cache, $config, $template, $theme, $images, $table_prefix, $user, $lang, $block_id, $cms_config_vars;

		if (!class_exists('class_topics'))
		{
			include(IP_ROOT_PATH . 'includes/class_topics.' . PHP_EXT);
		}
		$class_topics = new class_topics();

		@include_once(IP_ROOT_PATH . ATTACH_MOD_PATH . 'displaying.' . PHP_EXT);

		$template->_tpldata['kb_list.'] = array();
		$template->_tpldata['kb_article.'] = array();
		$template->_tpldata['cat_row.'] = array();
		$template->_tpldata['menu_row.'] = array();

		$template->set_filenames(array('kb_block' => 'blocks/kb_block.tpl'));

		$template->assign_vars(array(
			'L_COMMENTS' => $lang['Comments'],
			'L_VIEW_COMMENTS' => $lang['View_comments'],
			'L_POST_COMMENT' => $lang['Post_your_comment'],
			'L_POSTED' => $lang['Posted'],
			'L_ANNOUNCEMENT' => $lang['Post_Announcement'],
			'L_REPLIES' => $lang['Replies'],
			'L_REPLY_ARTICLE' => $lang['Article_Reply'],
			'L_PRINT_ARTICLE' => $lang['Article_Print'],
			'L_EMAIL_ARTICLE' => $lang['Article_Email'],
			'L_TOPIC' => $lang['Topic'],
			'L_ARTICLES' => $lang['Articles'],
			'L_TIME' => $lang['Articles_time'],
			'L_OPTIONS' => $lang['Articles_options'],
			'MINIPOST_IMG' => $images['icon_minipost'],
			'ARTICLE_COMMENTS_IMG' => $images['vf_topic_nor'],
			'ARTICLE_REPLY_IMG' => $images['news_reply'],
			'ARTICLE_PRINT_IMG' => $images['news_print'],
			'ARTICLE_EMAIL_IMG' => $images['news_email'],
			)
		);

		if(isset($_GET['kb']) && ($_GET['kb'] == 'article'))
		{
			$template->assign_block_vars('kb_article', array());

			$forum_id = request_var(POST_FORUM_URL, 0);
			// Mighty Gorgon: edited by JHL, I still need to check the impacts on the auth system
			//$fetchposts = $class_topics->fetch_posts($forum_id, 0, 0, false, false, false, false);
			$fetchposts = $class_topics->fetch_posts($forum_id, 0, 0);

			$id = (isset($_GET[POST_TOPIC_URL])) ? intval($_GET[POST_TOPIC_URL]) : intval($_POST[POST_TOPIC_URL]);
			$i = 0;

			while($fetchposts[$i]['topic_id'] <> $id)
			{
				$i++;
			}

			init_display_post_attachments($fetchposts[$i]['topic_attachment'], $fetchposts[$i], true, $block_id);

			$template->assign_vars(array(
				'TOPIC_ID' => $fetchposts[$i]['topic_id'],
				'TITLE' => $fetchposts[$i]['topic_title'],
				'TOPIC_DESC' => $fetchposts[$i]['topic_desc'],
				'POSTER' => $fetchposts[$i]['username'],
				'POSTER_CG' => colorize_username($fetchposts[$i]['user_id'], $fetchposts[$i]['username'], $fetchposts[$i]['user_color'], $fetchposts[$i]['user_active']),
				'TIME' => $fetchposts[$i]['topic_time'],
				'TEXT' => $fetchposts[$i]['post_text'],
				'REPLIES' => $fetchposts[$i]['topic_replies'],

				'U_VIEW_COMMENTS' => append_sid(CMS_PAGE_VIEWTOPIC . '?' . POST_FORUM_URL . '=' . $forum_id . '&amp;' . POST_TOPIC_URL . '=' . $fetchposts[$i]['topic_id'], true),
				'U_POST_COMMENT' => append_sid('posting.' . PHP_EXT . '?mode=reply&amp;' . POST_FORUM_URL . '=' . $forum_id . '&amp;' . POST_TOPIC_URL . '=' . $fetchposts[$i]['topic_id']),
				'U_PRINT_TOPIC' => append_sid('printview.' . PHP_EXT . '?' . POST_FORUM_URL . '=' . $forum_id . '&amp;' . POST_TOPIC_URL . '=' . $fetchposts[$i]['topic_id'] . '&amp;start=0'),
				'U_EMAIL_TOPIC' => append_sid('tellafriend.' . PHP_EXT . '?topic_title=' . urlencode(ip_utf8_decode($fetchposts[$i]['topic_title'])) . '&amp;topic_id=' . $fetchposts[$i]['topic_id']),
				)
			);
			display_attachments($fetchposts[$i]['post_id'], 'articles_fp');

		}
		else
		{
			if(isset($_GET['kb']) && ($_GET['kb'] == 'category'))
			{
				$template->assign_block_vars('kb_list', array());

				$forum_id = request_var(POST_FORUM_URL, 0);
				// Mighty Gorgon: edited by JHL, I still need to check the impacts on the auth system
				//$fetchposts = $class_topics->fetch_posts($forum_id, 0, 0, false, false, false, false);
				$fetchposts = $class_topics->fetch_posts($forum_id, 0, 0);

				for ($i = 0; $i < sizeof($fetchposts); $i++)
				{
					init_display_post_attachments($fetchposts[$i]['topic_attachment'], $fetchposts[$i], true, $block_id);
					$template->assign_block_vars('kb_list.kb_articles', array(
						'TOPIC_ID' => $fetchposts[$i]['topic_id'],
						'TOPIC_TITLE' => $fetchposts[$i]['topic_title'],
						'TOPIC_DESC' => $fetchposts[$i]['topic_desc'],
						'POSTER' => $fetchposts[$i]['username'],
						'POSTER_CG' => colorize_username($fetchposts[$i]['user_id'], $fetchposts[$i]['username'], $fetchposts[$i]['user_color'], $fetchposts[$i]['user_active']),
						'TIME' => $fetchposts[$i]['topic_time'],
						'REPLIES' => $fetchposts[$i]['topic_replies'],
						'U_VIEW_ARTICLE' => append_sid($_SERVER['SCRIPT_NAME'] . '?kb=article&f=' . $forum_id . '&' . POST_TOPIC_URL . '=' . $fetchposts[$i]['topic_id'], true),

						'U_VIEW_COMMENTS' => append_sid(CMS_PAGE_VIEWTOPIC . '?' . POST_FORUM_URL . '=' . $forum_id . '&amp;' . POST_TOPIC_URL . '=' . $fetchposts[$i]['topic_id'], true),
						'U_POST_COMMENT' => append_sid('posting.' . PHP_EXT . '?mode=reply&amp;' . POST_FORUM_URL . '=' . $forum_id . '&amp;' . POST_TOPIC_URL . '=' . $fetchposts[$i]['topic_id']),
						'U_PRINT_TOPIC' => append_sid('printview.' . PHP_EXT . '?' . POST_FORUM_URL . '=' . $forum_id . '&amp;' . POST_TOPIC_URL . '=' . $fetchposts[$i]['topic_id'] . '&amp;start=0'),
						'U_EMAIL_TOPIC' => append_sid('tellafriend.' . PHP_EXT . '?topic_title=' . urlencode(ip_utf8_decode($fetchposts[$i]['topic_title'])) . '&amp;topic_id=' . $fetchposts[$i]['topic_id']),
						)
					);

					display_attachments($fetchposts[$i]['post_id'], 'articles_fp');
				}
				$template->assign_vars(array(
					'TITLE' => $lang['Kb_name'],
					)
				);
			}
			else
			{

				$template->assign_block_vars('cat_row', array());

				$sql = "SELECT * FROM " . CMS_NAV_MENU_TABLE . "
							WHERE menu_id = '" . intval($cms_config_vars['kb_cat_id'][$block_id]) . "'
							LIMIT 1";
				$result = $db->sql_query($sql, 0, 'cms_menu_', CMS_CACHE_FOLDER);

				//$row = $db->sql_fetchrow($result);
				while ($row = $db->sql_fetchrow($result))
				{
					break;
				}
				$db->sql_freeresult($result);

				if (($row['menu_name_lang'] != '') && isset($lang[$row['menu_name_lang']]))
				{
					$main_menu_name = $lang[$row['menu_name_lang']];
				}
				else
				{
					$main_menu_name = (($row['menu_name'] != '') ? $row['menu_name'] : $lang['quick_links']) ;
				}

				$sql = "SELECT * FROM " . CMS_NAV_MENU_TABLE . "
							WHERE menu_parent_id = '" . intval($cms_config_vars['kb_cat_id'][$block_id]) . "'
							ORDER BY cat_parent_id ASC, menu_order ASC";
				$result = $db->sql_query($sql, 0, 'cms_menu_', CMS_CACHE_FOLDER);

				$menu_cat = array();
				$cat_item = array();
				$menu_item = array();
				while ($menu_item = $db->sql_fetchrow($result))
				{
					if ($menu_item['cat_id'] > 0)
					{
						$cat_item[$menu_item['cat_id']] = $menu_item;
					}
					if ($menu_item['cat_parent_id'] > 0)
					{
						$menu_cat[$menu_item['cat_parent_id']][$menu_item['menu_item_id']] = $menu_item;
					}
				}
				$db->sql_freeresult($result);

				foreach($cat_item as $cat_item_data)
				{
					if ($cat_item_data['menu_status'] == false)
					{
						$cat_allowed = false;
					}
					else
					{
						$cat_allowed = true;
						$auth_level_req = $cat_item_data['auth_view'];
						switch($auth_level_req)
						{
							case '0':
								$cat_allowed = true;
								break;
							case '1':
								$cat_allowed = ($user->data['session_logged_in'] ? false : true);
								break;
							case '2':
								$cat_allowed = ($user->data['session_logged_in'] ? true : false);
								break;
							case '3':
								$cat_allowed = ((($user->data['user_level'] == MOD) || ($user->data['user_level'] == ADMIN)) ? true : false);
								break;
							case '4':
								$cat_allowed = (($user->data['user_level'] == ADMIN)? true : false);
								break;
							default:
								$cat_allowed = true;
								break;
						}
					}

					if ($cat_allowed == true)
					{
						//echo($cat_item_data['menu_name'] . '<br />');
						$cat_id = ($cat_item_data['cat_id']);
						if (($cat_item_data['menu_name_lang'] != '') && isset($lang[$cat_item_data['menu_name_lang']]))
						{
							$cat_name = $lang[$cat_item_data['menu_name_lang']];
						}
						else
						{
							$cat_name = (($cat_item_data['menu_name'] != '') ? stripslashes($cat_item_data['menu_name']) : 'cat_item' . $cat_item_data['cat_id']) ;
						}
						$cat_icon = (($cat_item_data['menu_icon'] != '') ? '<img src="' . $cat_item_data['menu_icon'] . '" alt="" title="' . $cat_name . '" style="vertical-align:middle;" />&nbsp;&nbsp;' : '<img src="' . $images['nav_menu_sep'] . '" alt="" title="" style="vertical-align:middle;" />&nbsp;&nbsp;');
						//$cat_icon = (($cat_item_data['menu_icon'] != '') ? '<img src="' . $cat_item_data['menu_icon'] . '" alt="" title="' . $cat_name . '" style="vertical-align:middle;" />&nbsp;&nbsp;' : '&nbsp;');
						if ($cat_item_data['menu_link'] != '')
						{
							$cat_link = append_sid($cat_item_data['menu_link']);
							if ($cat_item_data['menu_link_external'] == true)
							{
								$cat_link .= '" target="_blank';
							}
						}

						$template->assign_block_vars('cat_row', array(
							'CAT_ID' => $cat_item_data['cat_id'],
							'CAT_ITEM' => $cat_name,
							'CAT_ICON' => $cat_icon,
							)
						);

						foreach($menu_cat[$cat_id] as $menu_cat_item_data)
						{

							if ($menu_cat_item_data['menu_status'] == false)
							{
								$menu_allowed = false;
							}
							else
							{
								$menu_allowed = true;
								$auth_level_req = $menu_cat_item_data['auth_view'];
								switch($auth_level_req)
								{
									case '0':
										$menu_allowed = true;
										break;
									case '1':
										$menu_allowed = ($user->data['session_logged_in'] ? false : true);
										break;
									case '2':
										$menu_allowed = ($user->data['session_logged_in'] ? true : false);
										break;
									case '3':
										$menu_allowed = ((($user->data['user_level'] == MOD) || ($user->data['user_level'] == ADMIN)) ? true : false);
										break;
									case '4':
										$menu_allowed = (($user->data['user_level'] == ADMIN)? true : false);
										break;
									default:
										$menu_allowed = true;
										break;
								}
							}

							if ($menu_allowed == true)
							{
								//echo($menu_cat_item_data['menu_name'] . '<br />');
								if (($menu_cat_item_data['menu_name_lang'] != '') && isset($lang[$menu_cat_item_data['menu_name_lang']]))
								{
									$menu_name = $lang[$menu_cat_item_data['menu_name_lang']];
								}
								else
								{
									$menu_name = (($menu_cat_item_data['menu_name'] != '') ? stripslashes($menu_cat_item_data['menu_name']) : 'cat_item' . $menu_cat_item_data['cat_id']) ;
								}
								if ($menu_cat_item_data['menu_link_external'] == true)
								{
									$menu_link .= '" target="_blank';
									$menu_link = $menu_cat_item_data['menu_link'];
								}
								else
								{
									$menu_link = append_sid($menu_cat_item_data['menu_link']);
								}
								$menu_icon = (($menu_cat_item_data['menu_icon'] != '') ? '<img src="' . $menu_cat_item_data['menu_icon'] . '" alt="" title="' . $menu_name . '" style="vertical-align:middle;" />' : '<img src="' . $images['nav_menu_sep'] . '" alt="" title="" style="vertical-align:middle;" />');
								$menu_desc = $menu_cat_item_data['menu_desc'];

								$template->assign_block_vars('cat_row.menu_row', array(
									'MENU_ITEM' => $menu_name,
									'MENU_LINK' => $menu_link,
									'MENU_ICON' => $menu_icon,
									'MENU_DESC' => $menu_desc,
									)
								);
							}
						}
					}
				}

				$template->assign_vars(array(
					'TITLE' => $lang['Kb_name'],
					)
				);
			}
		}
	}
}

cms_block_kb();

?>