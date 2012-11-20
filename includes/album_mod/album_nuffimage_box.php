<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

// ------------------------------------
// NUFFIMAGE SWITCHES
// ------------------------------------
require(IP_ROOT_PATH . 'includes/class_image.' . PHP_EXT);
$nuff_http = nuff_http_vars();

/*
if ($album_config['enable_nuffimage'] == 1)
{
	include(ALBUM_MOD_PATH . 'album_nuffimage_box.' . PHP_EXT);
	$template->assign_var_from_handle('NUFFIMAGE_BOX', 'nuffimage_box');
}
*/

$sort_method = request_var('sort_method', $album_config['sort_method']);
$sort_method = check_var_value($sort_method, array('pic_time', 'pic_title', 'pic_view_count'));

$sort_order = request_var('sort_order', $album_config['sort_order']);
$sort_order = check_var_value(strtoupper($sort_order), array('ASC', 'DESC'));

$sort_append = '&amp;sort_method=' . $sort_method . '&amp;sort_order=' . $sort_order;

if ($album_config['enable_sepia_bw'] == 1)
{
	$template->assign_block_vars('sepia_bw_enabled', array(
		)
	);
}

$template->set_filenames(array('nuffimage_box' => 'album_nuffimage_box.tpl'));

$template->assign_vars(array(
	'L_NUFF_TITLE' => $lang['Nuff_Title'],
	'L_NUFF_EXPLAIN' => $lang['Nuff_Explain'],

	'L_NUFF_NORMAL' => $lang['Nuff_Normal'],
	'L_NUFF_NORMAL_EXPLAIN' => $lang['Nuff_Normal_Explain'],
	'L_NUFF_RESIZE' => $lang['Nuff_Resize'],
	'L_NUFF_RESIZE_EXPLAIN' => $lang['Nuff_Resize_Explain'],
	'L_NUFF_RESIZE_W' => $lang['Nuff_Resize_W'],
	'L_NUFF_RESIZE_H' => $lang['Nuff_Resize_H'],
	'L_NUFF_RESIZE_NO_RESIZE' => $lang['Nuff_Resize_No_Resize'],
	'L_NUFF_RECOMPRESS' => $lang['Nuff_Recompress'],
	'L_NUFF_RECOMPRESS_EXPLAIN' => $lang['Nuff_Recompress_Explain'],
	'L_NUFF_BW' => $lang['Nuff_BW'],
	'L_NUFF_BW_EXPLAIN' => $lang['Nuff_BW_Explain'],
	'L_NUFF_SEPIA' => $lang['Nuff_Sepia'],
	'L_NUFF_SEPIA_EXPLAIN' => $lang['Nuff_Sepia_Explain'],
	'L_NUFF_FLIP' => $lang['Nuff_Flip'],
	'L_NUFF_FLIP_EXPLAIN' => $lang['Nuff_Flip_Explain'],
	'L_NUFF_MIRROR' => $lang['Nuff_Mirror'],
	'L_NUFF_MIRROR_EXPLAIN' => $lang['Nuff_Mirror_Explain'],
	'L_NUFF_ROTATE' => $lang['Nuff_Rotate'],
	'L_NUFF_ROTATE_EXPLAIN' => $lang['Nuff_Rotate_Explain'],
	'L_NUFF_ALPHA' => $lang['Nuff_Alpha'],
	'L_NUFF_ALPHA_EXPLAIN' => $lang['Nuff_Alpha_Explain'],
	'L_NUFF_BLUR' => $lang['Nuff_Blur'],
	'L_NUFF_BLUR_EXPLAIN' => $lang['Nuff_Blur_Explain'],
	'L_NUFF_PIXELATE' => $lang['Nuff_Pixelate'],
	'L_NUFF_PIXELATE_EXPLAIN' => $lang['Nuff_Pixelate_Explain'],
	'L_NUFF_SCATTER' => $lang['Nuff_Scatter'],
	'L_NUFF_SCATTER_EXPLAIN' => $lang['Nuff_Scatter_Explain'],
	'L_NUFF_INFRARED' => $lang['Nuff_Infrared'],
	'L_NUFF_INFRARED_EXPLAIN' => $lang['Nuff_Infrared_Explain'],
	'L_NUFF_TINT' => $lang['Nuff_Tint'],
	'L_NUFF_TINT_EXPLAIN' => $lang['Nuff_Tint_Explain'],
	'L_NUFF_INTERLACE' => $lang['Nuff_Interlace'],
	'L_NUFF_INTERLACE_EXPLAIN' => $lang['Nuff_Interlace_Explain'],
	'L_NUFF_SCREEN' => $lang['Nuff_Screen'],
	'L_NUFF_SCREEN_EXPLAIN' => $lang['Nuff_Screen_Explain'],
	'L_NUFF_STEREOGRAM' => $lang['Nuff_Stereogram'],
	'L_NUFF_STEREOGRAM_EXPLAIN' => $lang['Nuff_Stereogram_Explain'],
	'L_NUFF_WATERMARK' => $lang['Nuff_Watermark'],
	'L_NUFF_WATERMARK_EXPLAIN' => $lang['Nuff_Watermark_Explain'],

	'IMG_NORMAL' => ALBUM_MOD_IMG_PATH . 'fap_normal.png',
	'IMG_RESIZE' => ALBUM_MOD_IMG_PATH . 'fap_resize.png',
	'IMG_RECOMPRESS' => ALBUM_MOD_IMG_PATH . 'fap_recompress.png',
	'IMG_BW' => ALBUM_MOD_IMG_PATH . 'fap_bw.png',
	'IMG_SEPIA' => ALBUM_MOD_IMG_PATH . 'fap_sepia.png',
	'IMG_FLIP' => ALBUM_MOD_IMG_PATH . 'fap_flip.png',
	'IMG_MIRROR' => ALBUM_MOD_IMG_PATH . 'fap_mirror.png',
	'IMG_ROTATE' => ALBUM_MOD_IMG_PATH . 'fap_rotate.png',
	'IMG_ALPHA' => ALBUM_MOD_IMG_PATH . 'fap_alpha.png',
	'IMG_BLUR' => ALBUM_MOD_IMG_PATH . 'fap_blur.png',
	'IMG_PIXELATE' => ALBUM_MOD_IMG_PATH . 'fap_pixelate.png',
	'IMG_SCATTER' => ALBUM_MOD_IMG_PATH . 'fap_scatter.png',
	'IMG_INFRARED' => ALBUM_MOD_IMG_PATH . 'fap_infrared.png',
	'IMG_TINT' => ALBUM_MOD_IMG_PATH . 'fap_tint.png',
	'IMG_INTERLACE' => ALBUM_MOD_IMG_PATH . 'fap_interlace.png',
	'IMG_SCREEN' => ALBUM_MOD_IMG_PATH . 'fap_screen.png',
	'IMG_STEREOGRAM' => ALBUM_MOD_IMG_PATH . 'fap_stereogram.png',
	'IMG_WATERMARK' => ALBUM_MOD_IMG_PATH . 'fap_watermark.png',

	'NUFF_RESIZE_CHECKED' => ($nuff_http['nuff_resize'] == 0) ? '' : ' checked="checked"',
	'NUFF_RECOMPRESS_CHECKED' => ($nuff_http['nuff_recompress'] == 0) ? '' : ' checked="checked"',
	'NUFF_BW_CHECKED' => ($nuff_http['nuff_bw'] == 0) ? '' : ' checked="checked"',
	'NUFF_SEPIA_CHECKED' => ($nuff_http['nuff_sepia'] == 0) ? '' : ' checked="checked"' ,
	'NUFF_FLIP_CHECKED' => ($nuff_http['nuff_flip'] == 0) ? '' : ' checked="checked"',
	'NUFF_MIRROR_CHECKED' => ($nuff_http['nuff_mirror'] == 0) ? '' : ' checked="checked"',
	'NUFF_ALPHA_CHECKED' => ($nuff_http['nuff_alpha'] == 0) ? '' : ' checked="checked"',
	'NUFF_BLUR_CHECKED' => ($nuff_http['nuff_blur'] == 0) ? '' : ' checked="checked"',
	'NUFF_SCATTER_CHECKED' => ($nuff_http['nuff_scatter'] == 0) ? '' : ' checked="checked"',
	'NUFF_PIXELATE_CHECKED' => ($nuff_http['nuff_pixelate'] == 0) ? '' : ' checked="checked"',
	'NUFF_INFRARED_CHECKED' => ($nuff_http['nuff_infrared'] == 0) ? '' : ' checked="checked"',
	'NUFF_TINT_CHECKED' => ($nuff_http['nuff_tint'] == 0) ? '' : ' checked="checked"',
	'NUFF_INTERLACE_CHECKED' => ($nuff_http['nuff_interlace'] == 0) ? '' : ' checked="checked"',
	'NUFF_SCREEN_CHECKED' => ($nuff_http['nuff_screen'] == 0) ? '' : ' checked="checked"',
	'NUFF_STEREOGRAM_CHECKED' => ($nuff_http['nuff_stereogram'] == 0) ? '' : ' checked="checked"',

	'NUFF_PIC_ID' => $pic_id,

	//'HIDDEN_FIELDS_NUFF' => append_sid(album_append_uid(('album_pic_nuffed.' . PHP_EXT . '?pic_id=' . $pic_id . $nuff_mode)),
	'U_NUFFIMAGE_ACTION' => append_sid(album_append_uid('album_showpage.' . PHP_EXT . '?pic_id=' . $pic_id . $full_size_param . $sort_append . '&amp;nuffimage=true')),

	//'U_NUFFIMAGE_ACTION' => append_sid(album_append_uid('album_showpage.' . PHP_EXT)),
	// This would be required in tpl if you want to hide pic id and nuffimage var.
	/*
	<input type="hidden" name="pic_id" value="{NUFF_PIC_ID}" class="mainoption" />
	<input type="hidden" name="nuffimage" value="true" class="mainoption" />
	*/

	'L_SUBMIT' => $lang['Submit'],
	)
);

?>