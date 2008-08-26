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

if(!function_exists(imp_random_attach_block_func))
{
	function imp_random_attach_block_func()
	{
		global $template, $cms_config_vars, $block_id, $db, $lang, $phpEx, $tree, $attach_config, $phpbb_root_path;

		$template->_tpldata['images.'] = array();
		//reset($template->_tpldata['images.']);
		$template->_tpldata['no_images.'] = array();
		//reset($template->_tpldata['no_images.']);

		$maxheight = $cms_config_vars['md_ran_att_height'][$block_id];
		$maxwidth = $cms_config_vars['md_ran_att_width'][$block_id];
		$maxfiles = $cms_config_vars['md_ran_att_max'][$block_id];
		$fincl = explode(',', $cms_config_vars['md_ran_att_forums_incl'][$block_id]);
		if($cms_config_vars['md_ran_att_forums_excl'][$block_id])
		{
			$sqlexcl = "AND p.forum_id NOT IN (" . $cms_config_vars['md_ran_att_forums_excl'][$block_id] . ")";
		}
		$flist = '';
		for ($i=0; $i < count($tree['keys']); $i++)
		{
			if (($tree['type'][$i] == POST_FORUM_URL) && $tree['auth'][POST_FORUM_URL.$tree['id'][$i]]['auth_download'])
			{
				// this forum is allowed, now check the include param
				// if array is empty (==> first elem of array in null; do not use count)
				// or forum is in array
				if(!$fincl[0] || in_array($tree['id'][$i],$fincl))
				{
					$flist .= (($flist != '') ? ', ' : '') . $tree['id'][$i];
				}
			}
		}
		if(strlen($flist))
		{
			$fsql = "AND p.forum_id IN ($flist)";
		}
		else
		{
			// means that user isn't allowed to see any forum
			$fsql = "AND p.forum_id IN (-1)";
		}
		$sql = "SELECT
							ad.physical_filename,
							p.post_id
						FROM
							".ATTACHMENTS_TABLE." a,
							".ATTACHMENTS_DESC_TABLE." ad,
							".POSTS_TABLE." p
						WHERE
									a.attach_id = ad.attach_id
							AND a.post_id = p.post_id
							AND ad.thumbnail =1
							$fsql
							$sqlexcl
						ORDER BY
							rand()
						LIMIT 0, $maxfiles";
		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not obtain attachments', '', __LINE__, __FILE__, $sql);
		}
		if($db->sql_numrows($result))
		{
			while($imgrow = $db->sql_fetchrow($result))
			{
				$img = trim($attach_config['upload_dir']) . '/' . THUMB_DIR . '/t_' . $imgrow['physical_filename'];
				include_once($phpbb_root_path . ATTACH_MOD_PATH . 'includes/functions_filetypes.' . $phpEx);
				$dim = image_getdimension($img);
				$width = $dim[0];
				$height = $dim[1];
				$size = '';
				// create width and hight
				if ($width > $maxwidth && $width > $height)
				{
					$width = $maxwidth;
					$size = ' width="'.$width.'" ';
				}
				elseif($height > $maxheight)
				{
					$height = $maxheight;
					$size = ' height="'.$height.'" ';
				}
				$template->assign_block_vars('images', array(
					'IMG' => $img,
					'U_IMG' => append_sid(VIEWTOPIC_MG . '?' . POST_POST_URL . '='. $imgrow['post_id'] . '#p' . $imgrow['post_id']),
					'SIZE' => $size
					)
				);
			}
		}
		else
		{
			$template->assign_block_vars('no_images', array(
				'L_NOT_FOUND' => $lang['Not_found']
				)
			);
		}
		$db->sql_freeresult($result);
	}
}

imp_random_attach_block_func();

?>