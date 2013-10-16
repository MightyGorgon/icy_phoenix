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

// First we do the setmodules stuff for the admin cp.
if(!empty($setmodules))
{
	$filename = basename(__FILE__);
	$module['1250_News_Admin']['110_News_Cats'] = $filename;
	return;
}

// Load default header
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('pagestart.' . PHP_EXT);

include_once(IP_ROOT_PATH . 'includes/news_data.' . PHP_EXT);

// Check to see what mode we should operate in.
$mode = request_var('mode', '');

$dir = @opendir(IP_ROOT_PATH . $config['news_path']);

if(!$dir)
{
	message_die(GENERAL_ERROR, "Couldn't find news images", "", __LINE__, __FILE__, "The news images were not found");
}

while($file = @readdir($dir))
{
	if(!@is_dir(IP_ROOT_PATH . $config['news_path'] . '/' . $file))
	{
		$img_size = @getimagesize(IP_ROOT_PATH . $config['news_path'] . '/' . $file);

		if($img_size[0] && $img_size[1])
		{
			$category_images[] = $file;
		}
	}
}

@closedir($dir);

if(is_array($category_images))
{
	sort($category_images);
}

if(check_http_var_exists('add', false))
{
	// Admin has selected to add a smiley.
	$template->set_filenames(array('body' => ADM_TPL . 'news_cat_edit_body.tpl'));

	$filename_list = '';
	for($i = 0; $i < sizeof($category_images); $i++)
	{
		$filename_list .= '<option value="' . $category_images[$i] . '">' . $category_images[$i] . '</option>';
	}

	$s_hidden_fields = '<input type="hidden" name="mode" value="savenew" />';

	$template->assign_vars(array(
		'L_NEWS_TITLE' => $lang['Add_news_categories'],
		'L_NEWS_CONFIG' => $lang['News_Configuration'],
		'L_NEWS_EXPLAIN' => $lang['News_Add_Description'],
		'L_NEWS_ICON' => $lang['Icon'],
		'L_CATEGORY' => $lang['Category'],
		'L_SUBMIT' => $lang['Submit'],
		'L_RESET' => $lang['Reset'],

		'I_NEWS_IMG' => IP_ROOT_PATH . $config['news_path'] . '/' . $category_images[0],

		'S_NEWS_ACTION' => append_sid('admin_news_cats.' . PHP_EXT),
		'S_HIDDEN_FIELDS' => $s_hidden_fields,
		'S_FILENAME_OPTIONS' => $filename_list,
		'S_SMILEY_BASEDIR' => IP_ROOT_PATH . $config['news_path']
		)
	);

	$template->pparse('body');
}
elseif ($mode != '')
{
	switch($mode)
	{
		case 'delete':
			// Admin has selected to delete a category.
			$news_id = request_var('id', 0);

			$sql = "DELETE FROM " . NEWS_TABLE . " WHERE news_id = " . $news_id;
			$result = $db->sql_query($sql);

			$sql = "UPDATE " . TOPICS_TABLE . " SET news_id = 0 WHERE news_id = " . $news_id;
			$result = $db->sql_query($sql);
			$db->clear_cache('news_');

			$message = $lang['Category_Deleted'] . '<br /><br />' . sprintf($lang['Click_return_newsadmin'], '<a href="' . append_sid('admin_news_cats.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

			message_die(GENERAL_MESSAGE, $message);
			break;

		case 'edit':
			// Admin has selected to edit a smiley.
			$news_id = request_var('id', 0);

			$sql = "SELECT *
				FROM " . NEWS_TABLE . "
				WHERE news_id = " . $news_id;
			$result = $db->sql_query($sql);
			$category_data = $db->sql_fetchrow($result);

			$filename_list = '';
			for($i = 0; $i < sizeof($category_images); $i++)
			{
				if($category_images[$i] == $category_data['news_image'])
				{
					$category_selected = 'selected="selected"';
					$category_edit_img = $category_images[$i];
				}
				else
				{
					$category_selected = '';
				}

				$filename_list .= '<option value="' . $category_images[$i] . '"' . $category_selected . '>' . $category_images[$i] . '</option>';
			}

			$template->set_filenames(array('body' => ADM_TPL . 'news_cat_edit_body.tpl'));

			$s_hidden_fields = '<input type="hidden" name="mode" value="save" /><input type="hidden" name="news_id" value="' . $category_data['news_id'] . '" />';

			$template->assign_vars(array(
				'NEWS_CATEGORY' => $category_data['news_category'],
				'NEWS_ICON' => IP_ROOT_PATH . $config['news_path'] . '/' . $category_data['news_image'],

				'L_NEWS_TITLE' => $lang['News_Editing_Utility'],
				'L_NEWS_CONFIG' => $lang['News_Config'],
				'L_NEWS_EXPLAIN' => $lang['News_Explain'],
				'L_NEWS_ICON' => $lang['Icon'],
				'L_CATEGORY' => $lang['Category'],
				'L_SUBMIT' => $lang['Submit'],
				'L_RESET' => $lang['Reset'],

				'I_NEWS_IMG' => IP_ROOT_PATH . $config['news_path'] . '/'. $category_edit_img,

				'S_SMILEY_ACTION' => append_sid('admin_news_cats.' . PHP_EXT),
				'S_HIDDEN_FIELDS' => $s_hidden_fields,
				'S_FILENAME_OPTIONS' => $filename_list,
				'S_SMILEY_BASEDIR' => IP_ROOT_PATH . $config['news_path']
				)
			);

			$template->pparse('body');
			break;

		case 'save':
			// Admin has submitted changes while editing a category

			// Get the submitted data, being careful to ensure that we only accept the data we are looking for.
			$news_id = request_var('news_id', 0);
			$news_category = request_var('category', '', true);
			$news_image = request_var('image_url', '', true);

			// If no code was entered complain ...
			if (empty($news_category) || empty($news_image) || empty($news_id))
			{
				message_die(MESSAGE, $lang['Fields_empty']);
			}

			// Proceed with updating the news table.
			$sql = "UPDATE " . NEWS_TABLE . "
				SET  news_category = '" . $db->sql_escape($news_category) . "', news_image = '" . $db->sql_escape($news_image) . "'
				WHERE news_id = $news_id";
			$result = $db->sql_query($sql);
			$db->clear_cache('news_');

			$message = $lang['Category_Updated'] . '<br /><br />' . sprintf($lang['Click_return_newsadmin'], '<a href="' . append_sid('admin_news_cats.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

			message_die(GENERAL_MESSAGE, $message);
			break;

		case 'savenew':
			// Admin has submitted changes while adding a new category

			// Get the submitted data being careful to ensure the the data we receive and process is only the data we are looking for.
			$news_category = request_var('category', '', true);
			$news_image = request_var('image_url', '', true);

			// If no code was entered complain ...
			if (empty($news_category) || empty($news_image))
			{
				message_die(MESSAGE, $lang['Fields_empty']);
			}

			// Save	the	data to	the	smiley table.
			$sql = "INSERT INTO " . NEWS_TABLE . " (news_image, news_category)
				VALUES ('$news_image', '" . $db->sql_escape($news_category) . "')";
			$result = $db->sql_query($sql);
			$db->clear_cache('news_');

			$message = $lang['Category_Added'] . '<br /><br />' . sprintf($lang['Click_return_newsadmin'], '<a href="' . append_sid('admin_news_cats.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

			message_die(GENERAL_MESSAGE, $message);
			break;
	}
}
else
{
	// This is the main display of the page before the admin has selected any options.
	$db->clear_cache('news_');
	$data_access = new NewsDataAccess(IP_ROOT_PATH);
	$news_cats = $data_access->fetchCategories();

	$template->set_filenames(array('body' => ADM_TPL . 'news_cat_list_body.tpl'));

	$template->assign_vars(array(
		'L_ACTION' => $lang['Action'],
		'L_NEWS_TITLE' => $lang['News_Editing_Utility'],
		'L_NEWS_TEXT' => $lang['News_Explain'],
		'L_DELETE' => $lang['Delete'],
		'L_EDIT' => $lang['Edit'],
		'L_NEWS_ADD' => $lang['Add_new_category'],
		'L_ICON' => $lang['Icon'],
		'L_CATEGORY' => $lang['Category'],
		'L_TOPICS' => $lang['Topics'],

		'S_HIDDEN_FIELDS' => $s_hidden_fields,
		'S_NEWS_ACTION' => append_sid('admin_news_cats.' . PHP_EXT)
		)
	);

	// Loop throuh the rows	of smilies setting block vars	for	the	template.
	for($i = 0; $i < sizeof($news_cats); $i++)
	{
		// Replace htmlentites for < and > with	actual character.
		$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];

		$template->assign_block_vars('news_cats', array(
			'ROW_CLASS' => $row_class,

			'TOPIC_COUNT' => $news_cats[$i]['topic_count'],

			'CATEGORY_IMG' => IP_ROOT_PATH . $config['news_path']	.	'/'	.	$news_cats[$i]['news_image'],
			'L_CATEGORY' => $news_cats[$i]['news_category'],

			'U_NEWS_EDIT' => append_sid('admin_news_cats.' . PHP_EXT . '?mode=edit&amp;id=' . $news_cats[$i]['news_id']),
			'U_NEWS_DELETE' => append_sid('admin_news_cats.' . PHP_EXT . '?mode=delete&amp;id=' . $news_cats[$i]['news_id'])
			)
		);
	}

	// Spit out the page.
	$template->pparse('body');
}

// Page	Footer
include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);

?>