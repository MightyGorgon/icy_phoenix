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
* IdleVoid (idlevoid@slater.dk)
*
*/

// $album_data : structure description
// indexes :
// - id  : the category id : ie ALBUM_ROOT_CATEGORY, 1, 20, 12 and so on
// - idx : rank order
// $album_data['keys'][id]		=> idx, returns the key value for the sub, parent, id and data array
// $album_data['auth'][id]		=> auth_value array : ie album_tree_data['auth'][id]['auth_view'],
// $album_data['sub'][id]		=> array of sub-level ids,
// $album_data['parent'][idx]	=> parent id,
// $album_data['id'][idx]		=> value of the row id : cat_id for cats
// $album_data['personal'][idx]	=> list of db table row which indicated if it's personal category,
// $album_data['data'][idx]		=> db table row,
// --------------------------------------------------------------------------------------------------

//-----------------------------------------------------------------
// this check validates if the needed defines has been implemented
// in the album_constats.php file or that it is included in the
// includes/constants.php file.
// NOTE : it doesn't check for ALL but for some userfull onces
//   but if one is there then all should be there (not fool proof)
//-----------------------------------------------------------------

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

if (!defined('ALBUM_PUBLIC_GALLERY') || !defined('ALBUM_AUTH_CREATE_PERSONAL'))
{
	message_die(GENERAL_ERROR, 'Could not find the needed defines<br />Has they been implemented in ' . ALBUM_MOD_PATH . 'album_constants.' . PHP_EXT . ' ?', '', __LINE__, __FILE__);
}

// this is a album_category_hierarchy global variable...
// man I hate global variables, I really should code this in the OOP maner instead
$album_data = array();

require_once(ALBUM_MOD_PATH . 'album_hierarchy_debug.' . PHP_EXT);
require_once(ALBUM_MOD_PATH . 'album_hierarchy_auth.' . PHP_EXT);
require_once(ALBUM_MOD_PATH . 'album_hierarchy_sql.' . PHP_EXT);

//-----------------------------------------------
// build the album category administration panel
//-----------------------------------------------
function album_display_admin_index($cur = ALBUM_ROOT_CATEGORY, $level = 0, $max_level = -1, $column_offset=1)
{
	global $db, $template, $lang, $images, $album_data, $userdata, $user_id;

	static $username = '';

	// display 'the' level
	$AH_this = isset($album_data['keys'][$cur]) ? $album_data['keys'][$cur] : ALBUM_ROOT_CATEGORY; //-1;

	if (defined('IN_ADMIN'))
	{
		$admin_url = 'admin_album_cat.' . PHP_EXT;
		$is_root = false;
	}
	else
	{
		$admin_url = 'album_personal_cat_admin.' . PHP_EXT;
		$is_root = (($AH_this == ALBUM_ROOT_CATEGORY || $AH_this == 0)) ? true : false;
	}

	// root level
	if ($AH_this == ALBUM_ROOT_CATEGORY)
	{
		$level = ALBUM_ROOT_CATEGORY;

		// the the maximum level of categories,counting from the root category
		// this is used in the indentation of the categories
		$keys = array();
		$max_level = album_get_max_depth($keys, $cur, ALBUM_AUTH_VIEW, true);

		$template->assign_vars(array(
			'INC_SPAN_ALL' => $max_level + 4,
			'HEADER_INC_SPAN' => $max_level + 1,
			'L_ALBUM_TITLE' => $lang['Category_Title'],
			'L_ALBUM_ACTION' => $lang['Album_Categories_Title'],
			'L_ALBUM_CAT_TITLE' => $lang['Album_Categories_Title'],
			'L_ALBUM_CAT_EXPLAIN' => $lang['Album_Categories_Explain']
			)
		);

		// get user name of the root category
		$username = album_get_user_name( $album_data['data'][0]['cat_user_id'] ); //$album_data['data'][0]['username'];

		if (defined('IN_ADMIN'))
		{
			$template->assign_block_vars('switch_board_footer', array());
		}
	}

	// if we are above the 'root' level, thenadd it to the template (root level is -1)
	if ($AH_this > ALBUM_ROOT_CATEGORY)
	{
		// display a cat row
		$cat = $album_data['data'][$AH_this];
		$cat_id = $album_data['id'][$AH_this];

		// get the class colors
		$class_catLeft = "cat";
		$class_catMiddle = "cat";
		$class_catRight = "cat";

		// get category title
		$cat_title = ($is_root) ? sprintf($lang['Personal_Gallery_Of_User'], $username): $cat['cat_title'];

		// send to template
		$template->assign_block_vars('catrow', array());

		$template->assign_block_vars('catrow.cathead', array(
			'CAT_ID' => $cat_id,
			'CAT_TITLE' => $cat_title,

			'CLASS_CATLEFT' => $class_catLeft,
			'CLASS_CATRIGHT' => $class_catRight,
			'CLASS_CATMIDDLE' => $class_catMiddle,
			'WIDTH' => ($max_level == $level) ? 'width="50%"' : '',
			'INC_SPAN' => $max_level - $level +1 , // + $column_offset,

			'U_CAT_EDIT' => append_sid(album_append_uid("$admin_url?action=edit&amp;cat_id=$cat_id")),
			'U_CAT_DELETE' => ($is_root && $userdata['user_level'] != ADMIN) ? '' : append_sid(album_append_uid("$admin_url?action=delete&amp;cat_id=$cat_id")),
			'U_CAT_MOVE_UP' => ($is_root) ? '' : append_sid(album_append_uid("$admin_url?action=move&amp;move=-15&amp;cat_id=$cat_id")),
			'U_CAT_MOVE_DOWN' => ($is_root) ? '' : append_sid(album_append_uid("$admin_url?action=move&amp;move=15&amp;cat_id=$cat_id")),
			'U_VIEWCAT' => append_sid(album_append_uid("$admin_url?action=edit&amp;cat_id=$cat_id")),

			'L_MOVE_UP' => ($is_root) ? '' : $lang['Move_up'],
			'L_MOVE_DOWN' => ($is_root) ? '' : $lang['Move_down'],
			'L_EDIT' => $lang['Edit'],
			'L_DELETE' => ($is_root && $userdata['user_level'] != ADMIN) ? '' : $lang['Delete']
			)
		);

		// add expandsion of the row height in order to have room for the description
		$rowspan = empty($cat['cat_desc']) ? 1 : 2;

		// creat a table data for each level down to the level where the title (and maybe description) is
		// in other words put 'X' times '<td class="row2" rowspan="1" width="46">&nbsp;</td>' string in
		// or incase there is a desciption use this text '<td class="row2" rowspan="2" width="46">&nbsp;'1'</td>' instead
		for ($k = 1; $k <= $level; $k++)
		{
			$template->assign_block_vars('catrow.cathead.inc', array('ROWSPAN' => $rowspan));
		}

		// send the category description to template... if its specified
		if (!empty($cat['cat_desc']))
		{
			$cat_desc = $cat['cat_desc'];

			$template->assign_block_vars('catrow', array());
			$template->assign_block_vars('catrow.cattitle', array(
				'CAT_DESCRIPTION' => $cat_desc,
				'INC_SPAN_ALL' => $max_level - $level + 4
				)
			);
		}
	} // if we are above the root level

	// display the sub-level
	for ($i = 0; $i < count($album_data['sub'][$cur]); $i++)
	{
		$column_offset = album_display_admin_index($album_data['sub'][$cur][$i], $level + 1, $max_level, $column_offset);
	}

	// if we are 'above' the root level then add the add category 'footer'
	if ($AH_this > ALBUM_ROOT_CATEGORY)
	{
		// cat footer, add the footer
		$template->assign_block_vars('catrow', array());
		$template->assign_block_vars('catrow.catfoot', array(
			'S_ADD_NAME' => "name[$cat_id]",
			'INC_SPAN' => $max_level - $level + 1,
			'INC_SPAN_ALL' => $max_level - $level + 4,
			'S_ADD_CAT_SUBMIT' => "addcategory[$cat_id]"
			)
		);
	}

	// add indentation to the display
	for ($k = 1; $k <= $level; $k++)
	{
		$template->assign_block_vars('catrow.catfoot.inc', array());
	}

	// at the moment is isn't used, so it might be removed, but not until
	// this patch has been approved by other ppl then me.
	return $column_offset;
}

// --------------------------------
// Build the album hierarchy index table
// --------------------------------
function album_build_index($user_id, &$keys, $cur_cat_id = ALBUM_ROOT_CATEGORY, $real_level = ALBUM_ROOT_CATEGORY, $max_level = ALBUM_ROOT_CATEGORY, $newestpic = NULL)
{
	global $template, $db, $board_config, $album_config, $lang, $images, $userdata, $album_data;

	// init some variables
	$display = false;
	$moderators = '';
	$last_pic_info = '';
	$cat_total_comments = 0;

	$album_show_pic_url = 'album_showpage.' . PHP_EXT;

	// display the level
	$AH_this = isset($album_data['keys'][$cur_cat_id]) ? $album_data['keys'][$cur_cat_id] : ALBUM_ROOT_CATEGORY;
	// root level head
	if (($real_level == ALBUM_ROOT_CATEGORY) || !is_array($keys))
	{
		// get max inc level
		$keys = array();
		$keys = album_get_auth_keys($cur_cat_id, ALBUM_AUTH_VIEW ); //, true, -1, -1);
		$max_level = album_get_max_depth($keys, ALBUM_AUTH_VIEW, $cur_cat_id ); //, false);
		$newestpic = album_no_newest_pictures($album_config['new_pic_check_interval'], $album_data['id']);
	}

	// get the level
	$level = $keys['level'][$keys['keys'][$cur_cat_id]];

	// if 'top level category, then write the link to it
	if ($level == (ALBUM_ROOT_CATEGORY + 1))
	{
		$links = '';
		$newpics_sub_link = '';
		$total_pics = 0;
		$first_pic_id = 0;
		$last_pic_id = 0;
		$sub_total_pics = 0;
		$total_comments = 0;

		// display a cat row
		$cat = $album_data['data'][$AH_this];
		$cat_id = $cat['cat_id'];

		// sub categories for current category
		$cats = array();

		// specific to the data type
		$title = album_get_object_lang($cur_cat_id, 'name');
		$desc = album_get_object_lang($cur_cat_id, 'desc');

		// get all the cat id for current cat and it's subs, $cats will hold all the ids
		album_get_sub_cat_ids($cat_id, $cats, ALBUM_AUTH_VIEW, ALBUM_INCLUDE_PARENT_ID);

		// we got the cat_id, we now need to get the value for the next sub category for this category
		for ($j = 0; $j < count($album_data['sub'][$cur_cat_id]); $j++)
		{
			$link = '';

			// get the 'cur' for the current sub category
			$subcur = $album_data['sub'][$cur_cat_id][$j];
			// get the keys for current iterated sub level
			$subthis = $album_data['keys'][$subcur];

			if ($album_data['auth'][$subcur]['view'] == 0)
			{
				continue;
			}

			// get the row data for current iterated sub level
			$subdata = $album_data['data'][$subthis];
			// get the name of the category
			$subname = album_get_object_lang($subcur, 'name');
			// get the description of the category
			$subdesc = album_get_object_lang($subcur, 'desc');

			$subpgm = append_sid(album_append_uid('album_cat.' . PHP_EXT . '?cat_id=' . $album_data['id'][$subthis]));

			// the number of picture for the sub category (only one level down)
			$sub_cats = array();
			// get the all the sub category ids for this sub category
			album_get_sub_cat_ids($subcur, $sub_cats, ALBUM_AUTH_VIEW, ALBUM_INCLUDE_PARENT_ID);

			// get the number of pictures in current sub category and its sub categories
			$sub_total_pics = album_get_total_pics($sub_cats);
			$new_images_flag = false;
			for ($i = 0; $i < count($sub_cats); $i++)
			{
				$total_new = $total_new + $newestpic[$sub_cats[$i]];
				if ( ($new_images_flag == false) && ($total_new > 0) )
				{
					$new_images_flag = true;
				}
			}

			// are they any pictures in the current category ?
			// then display it after the category name (only sub)
			switch (intval($sub_total_pics))
			{
				case 0:
					$sub_total_pics = sprintf($lang['Multiple_Sub_Total_Pics'], $sub_total_pics);
					break;
				case 1:
					$sub_total_pics = sprintf($lang['One_Sub_Total_Pics'], $sub_total_pics);
					break;
				default:
					$sub_total_pics = sprintf($lang['Multiple_Sub_Total_Pics'], $sub_total_pics);
			}

			if ($subname != '')
			{
				$total = 0;
				// calculate for all the subcats in this branch
				for ($i = 0; $i < count($sub_cats); $i++)
				{
					$total = $total + $newestpic[ $sub_cats[$i] ];
				}
				//$last_pic_id = album_get_last_pic_id($album_data['sub'][$cur_cat_id][$j]);
				$new_images_class = ($total > 0) ? '-new' : '';
				$xs_new = ($total > 0) ? '-new' : '';
				$slideshow_img_xs = ($xs_new) ? $images['icon_minipost_new'] : $images['icon_minipost'];
				$link_spacer = '<img src="' . $images['spacer'] . '" width="1" height="0" />';
				$subfolder_img = '<img src="' . $slideshow_img_xs . '" valign="middle" title="' . $sub_total_pics . '" alt="' . $sub_total_pics . '"/>';
				$sub_cat_separator = ( $i != count ($sub_cats) ) ? ',':'';
				//$slideshow_link = append_sid(album_append_uid("album_showpage." . PHP_EXT . "?pic_id=" . $last_pic_id . "&amp;slideshow=5"));
				$link = $link_spacer . $subfolder_img . '&nbsp;<a href="' . $subpgm . '" title="' . $subdesc . '" class="forumlink2' . $new_images_class . '"><b>' . $subname . '</b></a><b>' . $sub_cat_separator .'</b>&nbsp;';
			}

			if ($link != '')
			{
				$total = 0;
				// calculate for all the subcats in this branch
				for ($i = 0; $i < count($sub_cats); $i++)
				{
					$total = $total + $newestpic[$sub_cats[$i]];
				}

				// Mighty Gorgon - Slideshow - BEGIN
				$ss_cat_id = $album_data['sub'][$cur_cat_id][$j];
				if ( (album_get_total_pic_cat($ss_cat_id) > 0) && ($album_config['show_slideshow'] == 1) )
				{
					//$xs_new = ($total > 0)  ? '-new' : '';
					$first_pic_id = album_get_first_pic_id($cur_cat_id);
					$last_pic_id = album_get_last_pic_id($ss_cat_id);
					$slideshow_link = append_sid(album_append_uid("album_showpage." . PHP_EXT . "?pic_id=" . $last_pic_id . "&amp;slideshow=5"));
					$slideshow_link_full = '[<a href="' . $slideshow_link . '">' . $lang['Slideshow'] . '</a>]';
					//$slideshow_link_full = '<a href="' . $slideshow_link . '">' . $lang['Slideshow'] . '&nbsp;' . $slideshow_img . '</a>';
				}
				else
				{
					$slideshow_link_full = '';
				}
				// Mighty Gorgon - Slideshow - END
				if ( $total > 0 )
				{
					$new_text = ($total == 1) ? sprintf($lang['One_new_picture'], $total) : sprintf($lang['Multiple_new_pictures'], $total);
					$newpics_sub_link = '&nbsp;<img src="' . $images['mini_new_pictures'] . '" alt="' . $new_text . '" title="' . $new_text . '">&nbsp;';
					$link = $link . $slideshow_link_full;
				}

				if ($album_config['line_break_subcats'] == 1)
				{
					$links .= ($links != '') ? $link . '<br />&nbsp;' : '<br />&nbsp;' . $link . '<br />&nbsp;';
				}
				else
				{
					$links .= ($links != '') ? ', ' . $link : $link;
				}
			}
		} // for ....

		// is there a moderator group for this category ?
		if ($cat['cat_moderator_groups'] != '')
		{
		// if we got some moderators AND some sub categories,
		// then make sure the sub categories are on a new line
			if ( !empty($links) && ($moderators = album_get_moderator_info($cat)) != '' )
			{
				$moderators .= '<br />';
			}
		}

		$cat_desc = album_get_object_lang($cur_cat_id, 'desc');
		if ( !empty($cat_desc) && !empty($links) )
		{
			$cat_desc .= '<br />';
		}

		// Mighty Gorgon - Slideshow - BEGIN
		$new_images = ((intval(($newestpic[$cur_cat_id])) != 0 ) || $new_images_flag) ? true : false;
		$xs_new = ((intval(($newestpic[$cur_cat_id])) != 0 ) || $new_images_flag)  ? '-new' : '';

		if ((album_get_total_pic_cat($cur_cat_id) > 0) && ($album_config['show_slideshow'] == 1))
		{
			$first_pic_id = album_get_first_pic_id($cur_cat_id);
			// Bicet - XS Support - BEGIN
			$slideshow_img_xs = ($xs_new) ? $images['icon_newest_reply'] : $images['icon_latest_reply'];
			$slideshow_img = '<img src="' . $slideshow_img_xs . '" alt="' . $lang['Slideshow'] . '" title="' . $lang['Slideshow'] . '" />';
			// Bicet - XS Support - END
			$slideshow_link = append_sid(album_append_uid('album_showpage.' . PHP_EXT . '?pic_id=' . $first_pic_id . '&amp;slideshow=5'));
			//$slideshow_link_full = '&nbsp;(<a href="' . $slideshow_link . '">' . $lang['Slideshow'] . '</a>)&nbsp;';
			$slideshow_link_full = '&nbsp;[<a href="' . $slideshow_link . '">' . $lang['Slideshow'] . '</a>]&nbsp;';
		}
		else
		{
			$slideshow_link_full = '';
		}
		// Mighty Gorgon - Slideshow - END

		if ($xs_new)
		{
			$cat_img = ( intval(count($sub_cats)) >0 ) ? $images['forum_sub_unread'] : $cat_img = $images['forum_nor_unread'];
		}
		else
		{
			$cat_img = ( intval(count($sub_cats)) >0 ) ? $images['forum_sub_read'] : $cat_img = $images['forum_nor_read'];
		}
		if ( ($board_config['url_rw'] == '1') || ( ($board_config['url_rw_guests'] == '1') && ($userdata['user_id'] == ANONYMOUS) ) )
		{
			$cat_url = append_sid(str_replace ('--', '-', make_url_friendly(album_get_object_lang($cur_cat_id, 'name')) . '-ac' . $cat_id . '.html'));
		}
		else
		{
			$cat_url = append_sid(album_append_uid('album_cat.' . PHP_EXT . '?cat_id=' . $cat_id));
		}

		// send all the data to the template, except for the sub categories links
		$template->assign_block_vars('catmain', array());
		$template->assign_block_vars('catmain.catrow', array(
			'CAT_TITLE' => album_get_object_lang($cur_cat_id, 'name'),
			'CAT_IMG' => $cat_img,
			'SLIDESHOW' => $slideshow_link_full,
			'CAT_DESC' => $cat_desc,
			'XS_NEW' => $xs_new,
			'U_VIEWCAT' => $cat_url,
			'L_MODERATORS' => empty($moderators) ? '' : $lang['Moderators'] . ' :',
			'MODERATORS' => $moderators,
			)
		);

		if (intval(($newestpic[$cur_cat_id])) != 0)
		{
			$new_text = ($newestpic[$cur_cat_id] > 1) ? sprintf($lang['Multiple_new_pictures'], $newestpic[ $cur_cat_id ]) : sprintf($lang['One_new_picture'], $newestpic[$cur_cat_id]);
			$xs_new = (intval(($newestpic[$cur_cat_id])) != 0) ? '-new' : '';

			$template->assign_block_vars('catmain.catrow.newpics', array(
				'I_NEWEST_PICS' => $images['mini_new_pictures'],
				'L_NEWEST_PICS' => $new_text
				)
			);
		}

		if ($album_config['show_index_total_pics'] == 1)
		{
			// get the total amount of pictures for current category and its sub categories
			$template->assign_block_vars('catmain.catrow.total_pics', array(
				'TOTAL_PICS' => album_get_total_pics($cats)
				)
			);
		}

		if ($album_config['show_index_total_comments'] == 1)
		{
			// the total number of comments for current category and its sub categories
			$template->assign_block_vars('catmain.catrow.total_comments', array(
				'TOTAL_COMMENTS' => album_get_comment_count($cats)
				)
			);
		}

		if ($album_config['show_index_pics'] == 1)
		{
			$template->assign_block_vars('catmain.catrow.pics', array(
				'PICS' => $cat['count'],
				)
			);
		}

		if ($album_config['show_index_comments'] == 1)
		{
			$cat_total_comments = album_get_comment_count($cat_id);
			$template->assign_block_vars('catmain.catrow.comments', array(
				'COMMENTS' => empty($cat_total_comments) ? 0 : $cat_total_comments
				)
			);
		}

		if ($album_config['show_index_last_comment'] == 1)
		{
			$last_comment_info = album_get_last_comment_info($cats);
			$template->assign_block_vars('catmain.catrow.last_comment', array(
				'LAST_COMMENT_INFO' => empty($last_comment_info) ? $lang['No_Comment_Info'] : $last_comment_info
				)
			);
		}

		if ($album_config['show_index_last_pic'] == 1)
		{
			// get the last picture information and the last comment information
			$last_pic_info = album_get_last_pic_info($cats, $last_pic_id);
			$template->assign_block_vars('catmain.catrow.last_pic', array(
				'LAST_PIC_INFO' => empty($last_pic_info) ? $lang['No_Pics'] : $last_pic_info
				)
			);
		}

		if ($album_config['show_index_thumb'] == 1)
		{
			// add the index thumbnail picture to the template
			if (($last_pic_id == 0) || ($album_config['show_index_last_pic'] == 0))
			{
				album_get_last_pic_info($cats, $last_pic_id);
			}

			if ($album_config['fullpic_popup'] == 0)
			{
				$pic_url_sid = append_sid(album_append_uid('album_showpage.' . PHP_EXT . '?pic_id=' . $last_pic_id));
				$pic_target = '_self';
			}
			else
			{
				$pic_url_sid = append_sid(album_append_uid('album_pic.' . PHP_EXT . '?pic_id=' . $last_pic_id));
				$pic_target = '_blank';
			}
			$pic_thumb_sid = append_sid(album_append_uid('album_thumbnail.' . PHP_EXT . '?pic_id=' . $last_pic_id));

			if ($album_config['show_img_no_gd'] == 1)
			{
				//$thumb_size = 'width="' . $album_config['thumbnail_size'] . '" height="' . $album_config['thumbnail_size'] . '"';
				$thumb_size = 'width="' . $album_config['thumbnail_size'] . '"';
			}
			else
			{
				$thumb_size = '';
			}

			if ($last_pic_id == 0)
			{
				$pic_url = '';
			}
			else
			{
				$pic_url = '<a href="' . $pic_url_sid . '" target="' . $pic_target . '"><img src="' . $pic_thumb_sid . '" ' . $thumb_size . ' alt="' . $lang['Last_Index_Thumbnail'] . '" title="' . $lang['Last_Index_Thumbnail'] . '" /></a>';
			}

			$template->assign_block_vars('catmain.catrow.thumb', array(
				'LAST_PIC_URL' => $pic_url
				)
			);
		}

		// add the sub category links row to the template
		if (!empty($links))
		{
			if ((($user_id == ALBUM_PUBLIC_GALLERY) && ($album_config['show_index_subcats'] == 1)) || (($user_id != ALBUM_PUBLIC_GALLERY) && ($album_config['personal_show_subcats_in_index'] == 1)))
			{
				$template->assign_block_vars('catmain.catrow.subcat_link', array(
					'L_LINKS' => $lang['Album_sub_categories'],
					'LINKS' => $links
					)
				);
			}
		}
		// something displayed, yeah baby
		$display = true;
	} // if ($level == 0)...

	// display sub-levels
	for ($i = 0; $i < count($album_data['sub'][$cur_cat_id]); $i++)
	{
		if (!empty($keys['keys'][$album_data['sub'][$cur_cat_id][$i]]))
		{
			$subdisplay = album_build_index($user_id, $keys, $album_data['sub'][$cur_cat_id][$i], $level + 1, $max_level, $newestpic);
			if ($subdisplay)
			{
				$display = true;
			}
		}
	}

	return $display;
}

//-----------------------------------------------
// unsets the global $album_data array
//-----------------------------------------------
function album_free_album_data()
{
	unset($GLOBALS['album_data']);
}

//-----------------------------------------------
// builds the album_tree strcture
//-----------------------------------------------
function album_build_tree(&$cats, &$parents, $level = ALBUM_ROOT_CATEGORY, $parent = ALBUM_ROOT_CATEGORY)
{
	global $db, $album_data, $album_config;
	$album_data_level = array();

	// add the categories of this level
	for ($i = 0; $i < count($parents[$parent]); $i++)
	{
		$idx = $parents[$parent][$i];

		$album_data_level['id'][] = $cats[$idx]['cat_id'];
		$album_data_level['sort'][] = $cats[$idx][ $album_config['album_category_sorting'] ];
		$album_data_level['data'][] = $cats[$idx];
		$album_data_level['personal'][] = ($cats[$idx]['cat_user_id'] == 0) ? 0 : 1;
	}

	// sort the tree level acordingly to the desired category sort
	if (!empty($album_data_level['data']))
	{
		if ($album_config['album_category_sorting'] != 'cat_order')
		{
			if ($album_config['album_category_sorting_direction'] == 'ASC')
			{
				array_multisort($album_data_level['sort'], SORT_ASC ,$album_data_level['id'], $album_data_level['data']);
			}
			else
			{
				array_multisort($album_data_level['sort'], SORT_DESC ,$album_data_level['id'], $album_data_level['data']);
			}
		}
		else
		{
			array_multisort($album_data_level['sort'], SORT_ASC , $album_data_level['id'], $album_data_level['data']);
		}
	}

	// add the tree_level to the tree
	$level++;
	for ($i = 0; $i < count($album_data_level['data']); $i++)
	{
		$AH_this = count($album_data['data']);
		$key = $album_data_level['id'][$i];
		$album_data['sub'][$parent][] = $key;
		$album_data['keys'][$key]     = $AH_this;
		$album_data['parent'][]       = $parent;
		$album_data['id'][]           = $album_data_level['id'][$i];
		$album_data['data'][]         = $album_data_level['data'][$i];
		$album_data['personal'][$key] = $album_data_level['personal'][$i];

		// add sub levels
		album_build_tree($cats, $parents, $level, $key);
	}

	return;
}

//-----------------------------------------------
// Append $user_id to a url.
// Borrowed from append_sid in session.php
//-----------------------------------------------
function album_append_uid($user_id, $url = '', $non_html_amp = false)
{
	global $album_user_id;

	if (gettype($user_id) == 'string' || strlen($url) == 0)
	{
		$non_html_amp = $url;
		$url = $user_id;
		$user_id = $album_user_id;
	}

	if ( !empty($user_id) && $user_id != ALBUM_PUBLIC_GALLERY && !preg_match('#user_id=#', $url) )
	{
		$url .= ( ( strpos($url, '?') != false ) ? ( ( $non_html_amp ) ? '&' : '&amp;' ) : '?' ) . "user_id=$user_id";
	}

	return $url;
}

function album_append_mode($url, $non_html_amp = false)
{
	global $album_view_mode;

	if ( !empty($album_view_mode) && !preg_match('#mode=#', $url) )
	{
		$url .= ( ( strpos($url, '?') != false ) ? ( ( $non_html_amp ) ? '&' : '&amp;' ) : '?' ) . "mode=$album_view_mode";
	}

	return $url;
}

function album_append_type($url, $non_html_amp = false)
{
	global $album_list_type;

	if ( !empty($album_list_type) && !preg_match('#type=#', $url) )
	{
		$url .= ( ( strpos($url, '?') != false ) ? ( ( $non_html_amp ) ? '&' : '&amp;' ) : '?' ) . "type=$album_list_type";
	}

	return $url;
}

function album_append_ref($url, $non_html_amp = false)
{
	global $album_ref_value, $SID;

	if ( !empty($album_ref_value) && !preg_match('#ref=#', $url) )
	{
		$url .= ( ( strpos($url, '?') != false ) ? ( ( $non_html_amp ) ? '&' : '&amp;' ) : '?' ) . "ref=$album_ref_value";
	}

	return $url;
}

//-----------------------------------------------
// Get the maximum level of sub categories for
// specified category (cur_cat_id)
//-----------------------------------------------
function album_get_max_depth(&$keys, $cur_cat_id = ALBUM_ROOT_CATEGORY, $auth_key = ALBUM_AUTH_VIEW, $all = false)
{
	$max_level = 0;

	// if keys aren't set, then get them from the album_tree
	if (empty($keys['id']))
	{
		$keys = array();
		$keys = album_get_auth_keys($cur_cat_id, $auth_key, $all);
	}

	// loop through the keys to find the maximum level.. aka max level
	for ($i = 0; $i < count($keys['id']); $i++)
	{
		if ($keys['level'][$i] > $max_level)
		{
			$max_level = $keys['level'][$i];
		}
	}
	return $max_level;
}

//-----------------------------------------------
// Returns all the category id for current
// category and it subs
//-----------------------------------------------
function album_get_sub_cat_ids($cur_cat_id = ALBUM_ROOT_CATEGORY, &$cats, $auth_key = ALBUM_AUTH_VIEW, $include_cur_cat_id = false)
{
	global $album_data;

	if ($include_cur_cat_id == true)
	{
		if (album_check_permission($album_data['auth'][$cur_cat_id], $auth_key))
		{
			$cats[] = $cur_cat_id;
		}
	}

	// get all the sub category id for current sub category
	for ($j = 0; $j < count($album_data['sub'][$cur_cat_id]); $j++)
	{
		$subcur = $album_data['sub'][$cur_cat_id][$j];
		$subthis = $album_data['keys'][$subcur];
		$subdata = $album_data['data'][$subthis];

		// add the category id
		//if ($album_data['auth'][$cur][$auth_key] == 1)
		//if (album_check_permission($wdata['cat_id'], $auth_key))
		if (album_check_permission($album_data['auth'][$subcur], $auth_key))
		{
			$cats[] = $subdata['cat_id'];
		}
	}

	// do this for each sub category... recursive
	for ($i = 0; $i < count($album_data['sub'][$cur_cat_id]); $i++)
	{
		album_get_sub_cat_ids($album_data['sub'][$cur_cat_id][$i], &$cats);
	}
}

//-----------------------------------------------
// Returns the description or name of a field
//-----------------------------------------------
function album_get_object_lang($cur_cat_id, $field)
{
	global $lang, $album_data , $album_user_id;

	$res = '';
	$AH_this = $album_data['keys'][$cur_cat_id];

	switch ($field)
	{
		case 'name':

			// check wheter we are working on a personal category or not and if it is the root
			if (album_is_personal_gallery($cur_cat_id) == true && album_get_personal_root_id($album_user_id) == $cur_cat_id )
			{
				return sprintf($lang['Personal_Gallery_Of_User'], $album_data['data'][$AH_this]['username']);
			}

			if ($cur_cat_id == ALBUM_ROOT_CATEGORY)
			{
				return $lang['Public_Categories'];
			}

				$field = 'cat_title';
					break;
		case 'desc':
			$field = 'cat_desc';
			break;
	}

	$res = $album_data['data'][$AH_this][$field];
	if (isset($lang[$res]))
	{
		$res = $lang[$res];
	}
	return $res;
}

//-----------------------------------------------
// Create the navigation tree at the top of the
// page..like : fortum title -> categori -> forum
//-----------------------------------------------
function album_make_nav_tree($cur_cat_id, $pgm, $nav_class = 'nav', $user_id = ALBUM_PUBLIC_GALLERY)
{
	global $album_data;
	// get topic or post level
	$topic_title = '';
	$fcur = '';

	// keep the compliancy with prec versions
	if (!isset($album_data['keys'][$cur_cat_id]))
	{
		$cur_cat_id = isset($album_data['keys'][$cur_cat_id]) ? $cur_cat_id : $cur_cat_id;
	}

	// find the object
	$AH_this = isset($album_data['keys'][$cur_cat_id]) ? $album_data['keys'][$cur_cat_id] : ALBUM_ROOT_CATEGORY;
	$res = '';
	$k = 0;
	while (($AH_this >= 0) || ($fcur != ''))
	{
		$field_name = album_get_object_lang($cur_cat_id, 'name');
		$param_type = 'cat_id';
		$param_value = $album_data['id'][$AH_this];

		if ($pgm != '')
		{
			$pgm_name = $pgm;
		}
		$k = $k + 1;
		if (!empty($field_name) && ( $album_data['auth'][$param_value]['view']==1) && $k == 1 )
		{
			$res = '<a href="' . append_sid(album_append_uid('./' . $pgm_name . (($field_name != '') ? "?$param_type=$param_value" : ''))) . '" class="nav-current">' . $field_name . '</a>' . (($res != '') ? ALBUM_NAV_ARROW . $res : '');
		}
		elseif (!empty($field_name) && ( $album_data['auth'][$param_value]['view']==1) )
		{
			$res = '<a href="' . append_sid(album_append_uid('./' . $pgm_name . (($field_name != '') ? "?$param_type=$param_value" : ''))) . '" class="nav">' . $field_name . '</a>' . (($res != '') ? ALBUM_NAV_ARROW . $res : '');
		}

		// find parent object
		if ($fcur != '')
		{
			$cur = $fcur;
			$pgm = '';
			$fcur = '';
			$topic_title = '';
		}
		else
		{
			$cur_cat_id = $album_data['parent'][$AH_this];
		}

		if ( isset($album_data['keys'][$cur_cat_id]) )
		{
			$AH_this = $album_data['keys'][$cur_cat_id];
		}
		else
		{
			$AH_this = ALBUM_ROOT_CATEGORY;
		}
	} // while
	return $res;
}

//-----------------------------------------------
// Builds an option selection list
//-----------------------------------------------
function album_get_tree_option($selected_cat_id = ALBUM_ROOT_CATEGORY, $auth_key = ALBUM_AUTH_VIEW, $options = ALBUM_SELECTBOX_INCLUDE_ROOT)
{
	global $album_data, $lang, $album_user_id;

	$all = checkFlag($options, ALBUM_SELECTBOX_INCLUDE_ALL);
	$include_delete = checkFlag($options, ALBUM_SELECTBOX_DELETING);
	$include_root = checkFlag($options, ALBUM_SELECTBOX_INCLUDE_ROOT);

	//--------------------------------------------------------------------------
	// check wheter the first gallery is a personal gallery or a public gallery
	// -------------------------------------------------------------------------
	$offset = ($album_data['personal'][$selected_cat_id] != 0) ? 1 : 0;
	$user_root_id = album_get_personal_root_id($album_user_id);

	$keys = array();
	$keys = album_get_auth_keys(ALBUM_ROOT_CATEGORY, $auth_key, $all, -1, -1);

	$delete_res = '';
	$public_res = '';
	$personal_res = '';

	for ($i = $offset; $i < count($keys['id']); $i++)
	{
		// should we include the 'Root' cat id, or substitude it with a -1 ?
		if ( ($keys['id'][$i] == ALBUM_ROOT_CATEGORY) && (!$include_root) )
		{
			$cat_id = ALBUM_ROOT_CATEGORY;
		}
		else
		{
			$cat_id = $keys['id'][$i];
		}

		$res = '';
		if ($cat_id != ALBUM_ROOT_CATEGORY)
		{
			$selected = ($selected_cat_id == $cat_id) ? ' selected="selected"' : '';
			$res .= '<option value="' . $cat_id . '"' . $selected . '>';

			// get category name..
			$name = album_get_object_lang($cat_id, 'name');

			// increment
			$inc = '';
			if ($user_root_id != $cat_id)
			{
				for ($k = 1; $k <= $keys['real_level'][$i]-$offset; $k++)
				{
					$inc .= '|&nbsp;&nbsp;&nbsp;';
				}

				if ($keys['level'][$i] >= $offset)
				{
					$inc .= '|--';
				}
			}
			$name = $inc . $name;
			$res .= $name . '</option>';

			// it's a personal gallery
			if (1 == $album_data['personal'][$cat_id])
			{
				$personal_res .= $res;
			}
			else
			{
				$public_res .= $res;
			}
		}
	}

	if (!empty($public_res))
	{
		$public_res = sprintf('<option value="%d">%s</option><option value="%d">------------------------------</option>', ALBUM_JUMPBOX_PUBLIC_GALLERY, $lang['Public_Categories'], ALBUM_JUMPBOX_SEPERATOR) . $public_res;
	}

	if (!empty($personal_res))
	{
		$seperator = (!empty($public_res)) ? sprintf('<option value="%d">------------------------------</option>',ALBUM_JUMPBOX_SEPERATOR) : '';
		$personal_res = sprintf('%s<option value="%d">%s</option><option value="%d">------------------------------</option>', $seperator, ALBUM_JUMPBOX_USERS_GALLERY, $lang['Users_Personal_Galleries'], ALBUM_JUMPBOX_SEPERATOR) . $personal_res;
	}

	if ($include_delete)
	{
		$delete_res = '<option value="' . ALBUM_JUMPBOX_DELETE . '" selected="selected">' . $lang['Delete_all_pics'] . '</option>' ;
	}

	//TODO (maybe) : make it selectable by the ACP to set wheter public or personal galleries should be shown first
	return $delete_res . $public_res . $personal_res;
}

//-----------------------------------------------
// Builds a simple option selection list
//-----------------------------------------------
function album_get_simple_tree_option($selected_cat_id = ALBUM_ROOT_CATEGORY, $auth_key = ALBUM_AUTH_VIEW, $options = ALBUM_SELECTBOX_INCLUDE_ROOT)
{
	global $album_data, $lang, $album_user_id;

	$all = checkFlag($options, ALBUM_SELECTBOX_INCLUDE_ALL);
	$include_delete = checkFlag($options, ALBUM_SELECTBOX_DELETING);
	$include_root = checkFlag($options, ALBUM_SELECTBOX_INCLUDE_ROOT);

	//--------------------------------------------------------------------------
	// check wheter the first gallery is a personal gallery or a public gallery
	// -------------------------------------------------------------------------
	$offset = ($album_data['personal'][$selected_cat_id] != 0) ? 1 : 0;
	$user_root_id = album_get_personal_root_id($album_user_id);

	$keys = array();
	$keys = album_get_auth_keys(ALBUM_ROOT_CATEGORY, $auth_key, $all, -1, -1);

	$delete_res = '';
	$public_res = '';
	$personal_res = '';

	for ($i = $offset; $i < count($keys['id']); $i++)
	{
		// should we include the 'Root' cat id, or substitude it with a -1 ?
		if ( ($keys['id'][$i] == ALBUM_ROOT_CATEGORY) && (!$include_root) )
		{
			$cat_id = ALBUM_ROOT_CATEGORY;
		}
		else
		{
			$cat_id = $keys['id'][$i];
		}

		$res = '';
		if ($cat_id != ALBUM_ROOT_CATEGORY)
		{
			$selected = ($selected_cat_id == $cat_id) ? ' selected="selected"' : '';
			$res .= '<option value="' . $cat_id . '"' . $selected . '>';

			// get category name..
			$name = album_get_object_lang($cat_id, 'name');

			// increment
			$inc = '';
			if ($user_root_id != $cat_id)
			{
				for ($k = 1; $k <= $keys['real_level'][$i]-$offset; $k++)
				{
					$inc .= '|&nbsp;&nbsp;&nbsp;';
				}

				if ($keys['level'][$i] >= $offset)
				{
					$inc .= '|--';
				}
			}
			$name = $inc . $name;
			$res .= $name . '</option>';

			// it's a personal gallery
			if (1 == $album_data['personal'][$cat_id])
			{
				$personal_res .= $res;
			}
			else
			{
				$public_res .= $res;
			}
		}
	}

	if ($include_delete)
	{
		$delete_res = '<option value="' . ALBUM_JUMPBOX_DELETE . '" selected="selected">' . $lang['Delete_all_pics'] . '</option>' ;
	}

	return $delete_res . $public_res . $personal_res;
}

//-----------------------------------------------
// Builds a full option selection list
//-----------------------------------------------
function album_get_full_tree_option()
{
	global $album_data, $lang, $album_user_id;

	$all = checkFlag($options, ALBUM_SELECTBOX_INCLUDE_ALL);
	$include_root = checkFlag($options, ALBUM_SELECTBOX_INCLUDE_ROOT);

	$keys = array();
	$keys = album_get_auth_keys(ALBUM_PUBLIC_GALLERY, ALBUM_AUTH_VIEW, $all, -1, -1);

	$delete_res = '';
	$public_res = '';
	$personal_res = '';

	for ($i = $offset; $i < count($keys['id']); $i++)
	{
		if ( ($keys['id'][$i] == ALBUM_ROOT_CATEGORY) && (!$include_root) )
		{
			$cat_id = ALBUM_ROOT_CATEGORY;
		}
		else
		{
			$cat_id = $keys['id'][$i];
		}

		$res = '';
		if ($cat_id != ALBUM_ROOT_CATEGORY)
		{
			$selected = ($selected_cat_id == $cat_id) ? ' selected="selected"' : '';
			$res .= '<option value="' . $cat_id . '"' . $selected . '>';

			// get category name..
			$name = album_get_object_lang($cat_id, 'name');

			// increment
			$inc = '';
			for ($k = 1; $k <= $keys['real_level'][$i]-$offset; $k++)
			{
				$inc .= '|&nbsp;&nbsp;&nbsp;';
			}

			if ($keys['level'][$i] >= $offset)
			{
				$inc .= '|--';
			}
			$name = $inc . $name;
			$res .= $name . '</option>';

			// it's a personal gallery
			if (1 == $album_data['personal'][$cat_id])
			{
				$personal_res .= $res;
			}
			else
			{
				$public_res .= $res;
			}
		}
	}

	return $delete_res . $public_res . $personal_res;
}

function album_get_javascript_validation($js_error_var, $error_message, $include_delete_validation = true)
{
	$javascript = "case '".ALBUM_JUMPBOX_SEPERATOR."': \n";
	$javascript .= ($include_delete_validation) ? "case '".ALBUM_JUMPBOX_DELETE."': \n" : '';
	$javascript .= "case '".ALBUM_JUMPBOX_USERS_GALLERY."': \n";
	$javascript .= "case '".ALBUM_JUMPBOX_PUBLIC_GALLERY."': \n";
	$javascript .= "    $js_error_var = \"".$error_message."\"; \n";
	$javascript .= "default: \n";
	$javascript .= "  // do nothing \n";

	return $javascript;
}

function album_validate_jumpbox_selection($cat_id)
{
	if ($cat_id == ALBUM_JUMPBOX_USERS_GALLERY || $cat_id == ALBUM_ROOT_CATEGORY ||
		$cat_id == ALBUM_JUMPBOX_PUBLIC_GALLERY || $cat_id == ALBUM_JUMPBOX_SEPERATOR)
	{
		return false;
	}

	return true;
}

//-----------------------------------------------
// Builds the album category jump/selection box
//-----------------------------------------------
function album_build_jumpbox($cat_id, $user_id = ALBUM_PUBLIC_GALLERY, $auth_key = ALBUM_AUTH_VIEW)
{
	global $lang, $album_data , $userdata;

	if ( count($album_data['data']) == 0 )
	{
		// if $user_id != 0 then it's a personal gallery
			album_read_tree($user_id);
	}

	$user_ref = ( ($user_id == ALBUM_PUBLIC_GALLERY) ? "" : "?user_id=$user_id");

	$javascript = "<script type=\"text/JavaScript\"><!-- \n";
	$javascript .= "function onChangeCheck() {\n";
	$javascript .= "    if( document.jumpbox.cat_id.value != " . ALBUM_JUMPBOX_SEPERATOR . ") {\n";
	$javascript .= "        document.jumpbox.submit();";
	$javascript .= "    }\n";
	$javascript .= "}\n";
	$javascript .= "// -->\n";
	$javascript .= "</script>\n";

	$res = $javascript;

	$res .= '<form name="jumpbox" action="'. append_sid(album_append_uid("album_cat." . PHP_EXT)) .'" method="get">';
	$res .= $lang['Jump_to'] . ':&nbsp;<select name="cat_id" onChange="onChangeCheck()">';
	$res .= album_get_tree_option($cat_id, $auth_key, ALBUM_SELECTBOX_INCLUDE_ROOT);
	$res .= '</select>';
	$res .= '&nbsp;<input type="submit" class="liteoption" value="'. $lang['Go'] .'" />';
	$res .= '<input type="hidden" name="sid" value="'. $userdata['session_id'] .'" />';
	$res .= ($user_id != ALBUM_PUBLIC_GALLERY) ? '<input type="hidden" name="user_id" value="'. $user_id .'" />' : '';
	$res .= '</form>';

	return $res;
}

function album_has_sub_cats($cat_id)
{
	global $album_data;

	// validate the cat_id parameter...
	if ($cat_id == ALBUM_ROOT_CATEGORY)
	{
		return false;
	}

	// if category id exists as key in the sub array
	// then the category have subs
	// but also check if user is allowed to view it
	if (@array_key_exists($cat_id, $album_data['sub']))
	{
		return ($album_data['auth'][$cat_id]['view'] == 1);
	}

	// if we end up here then we either didn't find the sub category
	// OR the current logged in user isn't allowed to view it
	return false;
}

function album_has_parent_cats($cat_id)
{
	global $album_data;

	// validate the cat_id parameter...
	if ($cat_id == ALBUM_ROOT_CATEGORY)
	{
		return false;
	}

	$key_id = $album_data['keys'][$cat_id];

	// if 'parent' is greater then zero, there is a parent
	// now check if user is allowed to view it
	if ($album_data['parent'][ $key_id ] > 0)
	{
		return ($album_data['auth'][$cat_id]['view'] == 1);
	}

	return false;
}

//-----------------------------------------------
// Get the row data for the category
//-----------------------------------------------
function album_get_album_data($cat_id)
{
	global $album_data;

	if ( @!array_key_exists($cat_id, $album_data['keys']) )
	{
		return NULL;
	}

	return $album_data['data'][ $album_data['keys'][$cat_id] ];
}

function album_build_url_parameters($parameters)
{
	$url_prefix = '?';
	$url_parameters = '';
	reset($parameters);

	while (list($key, $value) = each($parameters))
	{
		$url_parameters .= "$url_prefix$key=$value";
		$url_prefix = '&';
	}

	return $url_parameters;
}

//-----------------------------------------------
// Display the album hierarchy index table
// IF there is anything to display
//-----------------------------------------------
function album_display_index($user_id, $cur_cat_id = ALBUM_ROOT_CATEGORY, $show_header = false, $show_public_footer = false, $force_display = false)
{
	global $lang, $board_config, $template, $images, $album_data, $album_config, $userdata;
	$keys = array();

	// for testing ONLY
	if (album_is_debug_enabled() == true)
	{
		if (strcmp($cur_cat_id,'Root') == 0)
		{
			die('WRONG ROOT VALUE');
		}
	}

	$is_personal_gallery = ($user_id != ALBUM_PUBLIC_GALLERY) ? true : false;

	// if we are showing a personal gallery AND we are at the root of personal gallery
	// then ignore the root folder of the personal gallery, since it's 'hidden'
	if ($is_personal_gallery && ($cur_cat_id == ALBUM_ROOT_CATEGORY))
	{
		$cur_cat_id = album_get_personal_root_id($user_id);
	}

	$template->set_filenames(array('album' => 'album_box.tpl'));

	$keys = album_get_auth_keys($cur_cat_id, ALBUM_AUTH_VIEW);
	$display = album_build_index($user_id, $keys, $cur_cat_id, ALBUM_ROOT_CATEGORY, ALBUM_ROOT_CATEGORY);

	if (($force_display) && (!$is_personal_gallery) && (count($album_data) == 0))
	{
		$template->assign_block_vars('catmain', array());
		$template->assign_block_vars('catmain.catrow', array(
			'CAT_TITLE' => $lang['No_Public_Galleries'],
			'CAT_IMG' => $images['forum_nor_locked_read']
			)
		);

		$display = true;
	}

	// lets do some debugging..
	if (album_is_debug_enabled() == true)
	{
		album_debug('$user_id = %d<br />$cur_cat_id = %d<br />$display = %d<br />album data = %s<br />authentication keys = %s',
		$user_id, $cur_cat_id, intval($display), $album_data, $keys);
	}

	if ($display || album_is_debug_enabled() == true)
	{
		if ($show_header)
		{
			// create header and send it to template
			$template->assign_block_vars('catheader', array(
					'L_CATEGORY' => $lang['Category'],
					'L_PUBLIC_CATS' => (!$is_personal_gallery) ? $lang['Public_Categories'] : sprintf($lang['Personal_Gallery_Of_User'], album_get_user_name($user_id)),
					'U_YOUR_PERSONAL_GALLERY' => append_sid(album_append_uid('album.' . PHP_EXT . '?user_id=' . $userdata['user_id'])),
					//'U_YOUR_PERSONAL_GALLERY' => append_sid(album_append_uid('album_cat.' . PHP_EXT . '?cat_id=' . album_get_personal_root_id($userdata['user_id']) . 'user_id=' . $userdata['user_id'])),
					'L_YOUR_PERSONAL_GALLERY' => $lang['Your_Personal_Gallery'],

					'U_USERS_PERSONAL_GALLERIES' => append_sid(album_append_uid('album_personal_index.' . PHP_EXT)),
					'L_USERS_PERSONAL_GALLERIES' => $lang['Users_Personal_Galleries']
				)
			);

			$cols_span = album_generate_index_columns($username);

			// but we need to specific ly specify if we want to show the public gallery header
			if ($show_public_footer == true)
			{
				$template->assign_block_vars('catfooter.cat_public_footer', array(
					'U_YOUR_PERSONAL_GALLERY' => append_sid(album_append_uid('album.' . PHP_EXT . '?user_id=' . $userdata['user_id'])),
					//'U_YOUR_PERSONAL_GALLERY' => append_sid(album_append_uid('album_cat.' . PHP_EXT . '?cat_id=' . album_get_personal_root_id($userdata['user_id']) . 'user_id=' . $userdata['user_id'])),
					'L_YOUR_PERSONAL_GALLERY' => $lang['Your_Personal_Gallery'],

					'U_USERS_PERSONAL_GALLERIES' => append_sid(album_append_uid('album_personal_index.' . PHP_EXT)),
					'L_USERS_PERSONAL_GALLERIES' => $lang['Users_Personal_Galleries'],

					'FOOTER_COL_SPAN' => $cols_span
					)
				);

				if ($album_config['show_otf_link'] == 1)
				{
					$template->assign_block_vars('catfooter.cat_public_footer.show_otf_link', array());
				}

				if ($album_config['show_all_pics_link'] == 1)
				{
					$template->assign_block_vars('catfooter.cat_public_footer.show_all_pics_link', array());
				}

				if ($album_config['show_personal_galleries_link'] == 1)
				{
					$template->assign_block_vars('catfooter.cat_public_footer.show_personal_galleries_link', array());
				}

			}
		}
		$template->assign_var_from_handle('ALBUM_BOARD_INDEX', 'album');
	}

		return $display;
}

//-----------------------------------------------
// Creates the table column header, footer and
// the super_cells for the columns in the album
// category hierarchy index table
// it also calculates the col psna for the footer
//-----------------------------------------------
function album_generate_index_columns()
{
	//-----------------------------------------------
	// Special note on these lines !!!!
	// $indexes[] = <some number>;
	//
	// The numbers assinged are the 'indexes' in the
	// album hierarchy index table, minus the first
	// one. The values assigned are used in the
	// album_generate_super_cells function in order
	// to alternate the columns, no matter how many
	// columns are shown.
	//-----------------------------------------------

	global $album_config, $template, $lang;

	$indexes = array();
	$table_head_class = "thTop";
	$header_col_span = 2;

	if ($album_config['show_index_thumb'] == 1)
	{
		if ($album_config['show_index_total_pics'] == 0 &&
			$album_config['show_index_total_comments'] == 0 &&
			$album_config['show_index_pics'] == 0 &&
			$album_config['show_index_comments'] == 0 &&
			$album_config['show_index_last_comment'] == 0 &&
			$album_config['show_index_last_pic'] == 0 )
		{
			$table_head_class = "thCornerR";
		}

		$template->assign_block_vars('catheader.thumb', array(
			'L_LAST_PIC_THUMB' => $lang['Last_Index_Thumbnail'],
			'CLASS' => $table_head_class,
			)
		);

		$indexes[] = 0;
		$header_col_span ++;
	}

	if ($album_config['show_index_total_pics'] == 1)
	{
		if ($album_config['show_index_total_comments'] == 0 &&
			$album_config['show_index_pics'] == 0 &&
			$album_config['show_index_comments'] == 0 &&
			$album_config['show_index_last_comment'] == 0 &&
			$album_config['show_index_last_pic'] == 0 )
		{
			$table_head_class = "thCornerR";
		}

		$template->assign_block_vars('catheader.total_pics', array(
				'L_TOTAL_PICS' => $lang['Total_Pics'],
				'CLASS' => $table_head_class
			)
		);

		$indexes[] = 1;
		$header_col_span ++;
	}

	if ($album_config['show_index_total_comments'] == 1)
	{
		if ($album_config['show_index_pics'] == 0 &&
			$album_config['show_index_comments'] == 0 &&
			$album_config['show_index_last_comment'] == 0 &&
			$album_config['show_index_last_pic'] == 0 )
		{
			$table_head_class = "thCornerR";
		}

		$template->assign_block_vars('catheader.total_comments', array(
			'L_TOTAL_COMMENTS' => $lang['Total_Comments'],
			'CLASS' => $table_head_class
			)
		);

		$indexes[] = 2;
		$header_col_span ++;
	}

	if ($album_config['show_index_pics'] == 1)
	{
		if ($album_config['show_index_comments'] == 0 &&
			$album_config['show_index_last_comment'] == 0 &&
			$album_config['show_index_last_pic'] == 0 )
		{
			$table_head_class = 'th';
		}

		$template->assign_block_vars('catheader.pics', array(
			'L_PICS' => $lang['Pics'],
			'CLASS' => $table_head_class
			)
		);

		$indexes[] = 3;
		$header_col_span ++;
	}

	if ($album_config['show_index_comments'] == 1)
	{
		if ($album_config['show_index_last_comment'] == 0 &&
			$album_config['show_index_last_pic'] == 0 )
		{
			$table_head_class = 'th';
		}

		$template->assign_block_vars('catheader.comments', array(
			'L_COMMENTS' => $lang['Comments'],
			'CLASS' => $table_head_class
			)
		);

		$indexes[] = 4;
		$header_col_span ++;
	}

	if ($album_config['show_index_last_comment'] == 1)
	{
		if ($album_config['show_index_last_pic'] == 0 )
		{
			$table_head_class = 'th';
		}

		$template->assign_block_vars('catheader.last_comment', array(
			'L_LAST_COMMENT_INFO' => $lang['Last_Comments'],
			'CLASS' => $table_head_class
			)
		);

		$indexes[] = 5;
		$header_col_span ++;
	}

	if ($album_config['show_index_last_pic'] == 1)
	{
		$template->assign_block_vars('catheader.last_pic', array(
			'L_LAST_PIC' => $lang['Last_Pic'],
			'CLASS' => 'th'
			)
		);
		$indexes[] = 6;
		$header_col_span ++;
	}

	// create header and send it to template
	$template->assign_block_vars('catheader.col_span', array(
		'HEADER_COL_SPAN' => $header_col_span + 1
		)
	);

	// and if we are shoing header, then also show footer
	$template->assign_block_vars('catfooter', array());

	// substract 1, since the first column shouldn't be included,
	// it's the column which show the name(s) of the categories
	album_generate_super_cells($header_col_span - 2, $indexes);

	return $header_col_span;
}


//-----------------------------------------------
// Generates the colors for the super cell
// mouseover/mouseout script.
// thefunction also takes care if not columns are
// displayed. it makes sure that every column
// alternates (looking $indexes and $i)
//-----------------------------------------------
function album_generate_super_cells($columns, $indexes)
{
	global $album_config, $template, $theme;

	$supercells_enabled = $album_config['index_enable_supercells'];

	$toggle = false;

	if ($supercells_enabled == 0)
	{
		$overColor = '';
		$outColor = '';
	}
	else
	{
		$overColor = '#' . $theme['tr_color3'];
	}

	for($i = 0; $i < $columns; $i++)
	{
		if ($toggle)
		{
			if ($supercells_enabled != 0)
			{
				$outColor = '#' . $theme['tr_color1'];
			}
			$rowClass = $theme['td_class1']; // yeah it IS a row class for the COLUMN !!
		}
		else
		{
			if ($supercells_enabled != 0)
			{
				$outColor = '#' . $theme['tr_color2'];
			}
			$rowClass = $theme['td_class2']; // yeah it IS a row class for the COLUMN !!
		}

		if ($album_config['show_index_thumb'] == 1 && $indexes[$i] == 0)
		{
			$template->assign_vars(array(
				'COL0' => $rowClass,
				'THUMB_OVER_COLOR' => $overColor,
				'THUMB_OUT_COLOR' => $outColor
				)
			);
		}

		if ( ($album_config['show_index_total_pics'] == 1) && ($indexes[$i] == 1) )
		{
			$template->assign_vars(array(
				'COL1' => $rowClass,
				'TOTAL_PICS_OVER_COLOR' => $overColor,
				'TOTAL_PICS_OUT_COLOR' => $outColor
				)
			);
		}

		if ($album_config['show_index_total_comments'] == 1 && $indexes[$i] == 2)
		{
			$template->assign_vars(array(
				'COL2' => $rowClass,
				'TOTAL_COMMENTS_OVER_COLOR' => $overColor,
				'TOTAL_COMMENTS_OUT_COLOR' => $outColor
				)
			);
		}

		if ($album_config['show_index_pics'] == 1 && $indexes[$i] == 3)
		{
			$template->assign_vars(array(
				'COL3' => $rowClass,
				'PICS_OVER_COLOR' => $overColor,
				'PICS_OUT_COLOR' => $outColor
				)
			);
		}

		if ($album_config['show_index_comments'] == 1 && $indexes[$i] == 4)
		{
			$template->assign_vars(array(
				'COL4' => $rowClass,
				'COMMENTS_OVER_COLOR' => $overColor,
				'COMMENTS_OUT_COLOR' => $outColor
				)
			);
		}

		if ($album_config['show_index_last_comment'] == 1 && $indexes[$i] == 5)
		{
			$template->assign_vars(array(
				'COL5' => $rowClass,
				'LAST_COMMENT_OVER_COLOR' => $overColor,
				'LAST_COMMENT_OUT_COLOR' => $outColor
				)
			);
		}

		if ($album_config['show_index_last_pic'] == 1 && $indexes[$i] == 6)
		{
			$template->assign_vars(array(
				'COL6' => $rowClass,
				'LAST_PIC_OVER_COLOR' => $overColor,
				'LAST_PIC_OUT_COLOR' => $outColor
				)
			);
		}
		$toggle = !$toggle;
	}
}

?>