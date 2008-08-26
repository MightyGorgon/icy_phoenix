<tr>
	<td class="row1">
		<span class="genmed">{L_USE_OLD_PICS_GEN}</span><br />
		<span class="gensmall">{L_USE_OLD_PICS_GEN_EXPLAIN}</span><br />
	</td>
	<td class="row2"><span class="genmed"><input onchange="setChange();" type="radio" {USE_OLD_PICS_GEN_ENABLED} name="use_old_pics_gen" value="1" />{L_YES}&nbsp;&nbsp;<input onchange="setChange();" type="radio" {USE_OLD_PICS_GEN_DISABLED} name="use_old_pics_gen" value="0" />{L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_MIDTHUMB_USE}</span></td>
	<td class="row2"><span class="genmed"><input onchange="setChange();" type="radio" {MIDTHUMB_ENABLED} name="midthumb_use" value="1" />{L_YES}&nbsp;&nbsp;<input onchange="setChange();" type="radio" {MIDTHUMB_DISABLED} name="midthumb_use" value="0" />{L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_MIDTHUMB_CACHE}</span></td>
	<td class="row2"><span class="genmed"><input onchange="setChange();" type="radio" {MIDTHUMB_CACHE_ENABLED} name="midthumb_cache" value="1" />{L_YES}&nbsp;&nbsp;<input onchange="setChange();" type="radio" {MIDTHUMB_CACHE_DISABLED} name="midthumb_cache" value="0" />{L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_MIDTHUMB_WIDTH}</span></td>
	<td class="row2"><input onchange="setChange();" class="post" type="text" maxlength="12" size="5" name="midthumb_width" value="{MIDTHUMB_WIDTH}" /></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_MIDTHUMB_HEIGHT}</span></td>
	<td class="row2"><input onchange="setChange();" class="post" type="text" maxlength="12" size="5" name="midthumb_height" value="{MIDTHUMB_HEIGHT}" /></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_THUMBNAIL_CACHE}</span></td>
	<td class="row2"><span class="genmed"><input onchange="setChange();" type="radio" {THUMBNAIL_CACHE_ENABLED} name="thumbnail_cache" value="1" />{L_YES}&nbsp;&nbsp;<input onchange="setChange();" type="radio" {THUMBNAIL_CACHE_DISABLED} name="thumbnail_cache" value="0" />{L_NO}</span></td>
</tr>
<tr>
	<td class="row1">
		<span class="genmed">{L_QUICK_THUMBNAILS}</span><br />
		<span class="gensmall">{L_QUICK_THUMBNAILS_EXPLAIN}</span><br />
	</td>
	<td class="row2"><span class="genmed"><input onchange="setChange();" type="radio" {QUICK_THUMBNAILS_ENABLED} name="quick_thumbs" value="1" />{L_YES}&nbsp;&nbsp;<input onchange="setChange();" type="radio" {QUICK_THUMBNAILS_DISABLED} name="quick_thumbs" value="0" />{L_NO}</span></td>
</tr>
<tr>
	<td class="row1">
		<span class="genmed">{L_LB_PREVIEW}</span><br />
		<span class="gensmall">{L_LB_PREVIEW_EXPLAIN}</span><br />
	</td>
	<td class="row2"><span class="genmed"><input onchange="setChange();" type="radio" {LB_PREVIEW_ENABLED} name="lb_preview" value="1" />{L_YES}&nbsp;&nbsp;<input onchange="setChange();" type="radio" {LB_PREVIEW_DISABLED} name="lb_preview" value="0" />{L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_THUMBNAIL_SIZE}</span></td>
	<td class="row2"><input onchange="setChange();" class="post" type="text" maxlength="4" size="4" name="thumbnail_size" value="{THUMBNAIL_SIZE}" /></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_THUMBNAIL_QUALITY}</span></td>
	<td class="row2"><input onchange="setChange();" class="post" type="text" maxlength="3" size="3" name="thumbnail_quality" value="{THUMBNAIL_QUALITY}" /></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_ROWS_PER_PAGE}</span></td>
	<td class="row2"><input onchange="setChange();" class="post" type="text" maxlength="2" size="2" name="rows_per_page" value="{ROWS_PER_PAGE}" /></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_COLS_PER_PAGE}</span></td>
	<td class="row2"><input onchange="setChange();" class="post" type="text" maxlength="2" size="2" name="cols_per_page" value="{COLS_PER_PAGE}" /></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_DEFAULT_SORT_METHOD}</span></td>
	<td class="row2">
	<select name="sort_method" onchange="setChange();">
		<option {SORT_TIME} value='pic_time'>{L_TIME}</option>
		<option {SORT_PIC_TITLE} value='pic_title'>{L_PIC_TITLE}</option>
		<option {SORT_USERNAME} value='username'>{L_USERNAME}</option>
		<option {SORT_VIEW} value='pic_view_count'>{L_VIEW}</option>
		<option {SORT_RATING} value='rating'>{L_RATING}</option>
		<option {SORT_COMMENTS} value='comments'>{L_COMMENTS}</option>
		<option {SORT_NEW_COMMENT} value='new_comment'>{L_NEW_COMMENT}</option>
	</select>
	</td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_DEFAULT_SORT_ORDER}</span></td>
	<td class="row2">
	<select name="sort_order">
		<option {SORT_ASC} value='ASC'>{L_ASC}</option>
		<option {SORT_DESC} value='DESC'>{L_DESC}</option>
	</select>
	</td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_FULLPIC_POPUP}</span></td>
	<td class="row2"><span class="genmed"><input onchange="setChange();" type="radio" {FULLPIC_POPUP_ENABLED} name="fullpic_popup" value="1" />{L_YES}&nbsp;&nbsp;<input onchange="setChange();" type="radio" {FULLPIC_POPUP_DISABLED} name="fullpic_popup" value="0" />{L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_SHOW_IMG_NO_GD}</span></td>
	<td class="row2"><span class="genmed"><input onchange="setChange();" type="radio" {SHOW_IMG_NO_GD_ENABLED} name="show_img_no_gd" value="1" />{L_YES}&nbsp;&nbsp;<input onchange="setChange();" type="radio" {SHOW_IMG_NO_GD_DISABLED} name="show_img_no_gd" value="0" />{L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_SHOW_GIF_MID_THUMB}</span></td>
	<td class="row2"><span class="genmed"><input onchange="setChange();" type="radio" {SHOW_GIF_MID_THUMB_ENABLED} name="show_gif_mid_thumb" value="1" />{L_YES}&nbsp;&nbsp;<input onchange="setChange();" type="radio" {SHOW_GIF_MID_THUMB_DISABLED} name="show_gif_mid_thumb" value="0" />{L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_SHOW_PIC_SIZE}</span></td>
	<td class="row2"><span class="genmed"><input onchange="setChange();" type="radio" {SHOW_PIC_SIZE_ENABLED} name="show_pic_size_on_thumb" value="1" />{L_YES}&nbsp;&nbsp;<input onchange="setChange();" type="radio" {SHOW_PIC_SIZE_DISABLED} name="show_pic_size_on_thumb" value="0" />{L_NO}</span></td>
</tr>