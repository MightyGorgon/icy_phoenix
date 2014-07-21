<tr>
	<td class="row1 tw45pct"><span class="genmed">{L_MAX_PICS}</span></td>
	<td class="row2"><input onchange="setChange();" class="post" type="text" maxlength="9" size="9" name="max_pics" value="{MAX_PICS}" /></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_USER_PICS_LIMIT}</span></td>
	<td class="row2"><input onchange="setChange();" class="post" type="text" maxlength="12" size="5" name="user_pics_limit" value="{USER_PICS_LIMIT}" /></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_MOD_PICS_LIMIT}</span></td>
	<td class="row2"><input onchange="setChange();" class="post" type="text" maxlength="12" size="5" name="mod_pics_limit" value="{MOD_PICS_LIMIT}" /></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_HOTLINK_PREVENT}</span></td>
	<td class="row2"><span class="genmed"><input onchange="setChange();" type="radio" {HOTLINK_PREVENT_ENABLED} name="hotlink_prevent" value="1" />{L_YES}&nbsp;&nbsp;<input onchange="setChange();" type="radio" {HOTLINK_PREVENT_DISABLED} name="hotlink_prevent" value="0" />{L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_HOTLINK_ALLOWED}</span></td>
	<td class="row2"><input onchange="setChange();" class="post" type="text" size="40" name="hotlink_allowed" value="{HOTLINK_ALLOWED}" /></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_ALBUM_CATEGORY_SORTING}</span></td>
	<td class="row2"><span class="genmed"><input onchange="setChange();" type="radio" {ALBUM_CATEGORY_SORTING_ID} name="album_category_sorting" value="cat_id" />{L_ALBUM_CATEGORY_SORTING_ID}&nbsp;&nbsp;<input onchange="setChange();" type="radio" {ALBUM_CATEGORY_SORTING_NAME} name="album_category_sorting" value="cat_title" />{L_ALBUM_CATEGORY_SORTING_NAME}&nbsp;&nbsp;<input onchange="setChange();" type="radio" {ALBUM_CATEGORY_SORTING_ORDER} name="album_category_sorting" value="cat_order" />{L_ALBUM_CATEGORY_SORTING_ORDER}</span></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_ALBUM_CATEGORY_DIRECTION}</span></td>
	<td class="row2"><span class="genmed"><input onchange="setChange();" type="radio" {ALBUM_CATEGORY_SORTING_ASC} name="album_category_sorting_direction" value="ASC" />{L_ALBUM_CATEGORY_SORTING_ASC}&nbsp;&nbsp;<input onchange="setChange();" type="radio" {ALBUM_CATEGORY_SORTING_DESC} name="album_category_sorting_direction" value="DESC" />{L_ALBUM_CATEGORY_SORTING_DESC}</span></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_ALBUM_PICTURE_SORTING}</span></td>
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
	<td class="row1"><span class="genmed">{L_ALBUM_PICTURE_DIRECTION}</span></td>
	<td class="row2">
	<select name="sort_order">
		<option {SORT_ASC} value='ASC'>{L_ASC}</option>
		<option {SORT_DESC} value='DESC'>{L_DESC}</option>
	</select>
	</td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_SHOW_RECENT_IN_SUBCATS}</span></td>
	<td class="row2"><span class="genmed"><input onchange="setChange();" type="radio" {SHOW_RECENT_IN_SUBCATS_ENABLED} name="show_recent_in_subcats" value="1" />{L_YES}&nbsp;&nbsp;<input onchange="setChange();" type="radio" {SHOW_RECENT_IN_SUBCATS_DISABLED} name="show_recent_in_subcats" value="0" />{L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_SHOW_RECENT_INSTEAD_OF_NOPICS}</span></td>
	<td class="row2"><span class="genmed"><input onchange="setChange();" type="radio" {SHOW_RECENT_INSTEAD_OF_NOPICS_ENABLED} name="show_recent_instead_of_nopics" value="1" />{L_YES}&nbsp;&nbsp;<input onchange="setChange();" type="radio" {SHOW_RECENT_INSTEAD_OF_NOPICS_DISABLED} name="show_recent_instead_of_nopics" value="0" />{L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_ALBUM_DEBUG_MODE}</span></td>
	<td class="row2"><span class="genmed"><input onchange="setChange();" type="radio" {ALBUM_DEBUG_MODE_ENABLED} name="album_debug_mode" value="1" />{L_YES}&nbsp;&nbsp;<input onchange="setChange();" type="radio" {ALBUM_DEBUG_MODE_DISABLED} name="album_debug_mode" value="0" />{L_NO}</span></td>
</tr>
<tr>
	<td class="row1">
		<span class="genmed">{L_SET_MEMORY}</span><br />
		<span class="gensmall">{L_SET_MEMORY_EXPLAIN}</span>
	</td>
	<td class="row2"><input onchange="setChange();" class="post" type="text" name="set_memory" value="{SET_MEMORY}" size="3" /></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_RATE_SYSTEM}</span></td>
	<td class="row2"><span class="genmed"><input onchange="setChange();" type="radio" {RATE_ENABLED} name="rate" value="1" />{L_YES}&nbsp;&nbsp;<input onchange="setChange();" type="radio" {RATE_DISABLED} name="rate" value="0" />{L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_RATE_SCALE}</span></td>
	<td class="row2"><input onchange="setChange();" class="post" type="text" name="rate_scale" value="{RATE_SCALE}" size="3" /></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_RATE_TYPE}</span></td>
	<td class="row2" colspan="2" border="1">
		<select onchange="setChange();" name="rate_type">
			<option {RATE_TYPE_0} value="0">{L_RATE_TYPE_0}</option>
			<option {RATE_TYPE_1} value="1">{L_RATE_TYPE_1}</option>
			<option {RATE_TYPE_2} value="2">{L_RATE_TYPE_2}</option>
		</select>
	</td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_HON_USERS}</span></td>
	<td class="row2"><span class="genmed"><input onchange="setChange();" type="radio" {HON_USERS_ENABLED} name="hon_rate_users" value="1" />{L_YES}&nbsp;&nbsp;<input onchange="setChange();" type="radio" {HON_USERS_DISABLED} name="hon_rate_users" value="0" />{L_NO}</span></td>
</tr>
<!--
<tr>
	<td class="row1"><span class="genmed">{L_HON_ALREDY_RATED}</span></td>
	<td class="row2"><span class="genmed"><input onchange="setChange();" type="radio" {HON_ALREADY_RATED_ENABLED} name="hon_rate_times" value="1" />{L_YES}&nbsp;&nbsp;<input onchange="setChange();" type="radio" {HON_ALREADY_RATED_DISABLED} name="hon_rate_times" value="0" />{L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_HON_SEP_RATING}</span></td>
	<td class="row2"><span class="genmed"><input onchange="setChange();" type="radio" {HON_SEP_RATING_ENABLED} name="hon_rate_sep" value="1" />{L_YES}&nbsp;&nbsp;<input onchange="setChange();" type="radio" {HON_SEP_RATING_DISABLED} name="hon_rate_sep" value="0" />{L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_HON_WHERE}</span></td>
	<td class="row2"><input onchange="setChange();" class="post" type="text" size="20" name="hon_rate_where" value="{HON_WHERE}" /></td>
</tr>
<tr>
-->
	<td class="row1"><span class="genmed">{L_COMMENT_SYSTEM}</span></td>
	<td class="row2"><span class="genmed"><input onchange="setChange();" type="radio" {COMMENT_ENABLED} name="comment" value="1" />{L_YES}&nbsp;&nbsp;<input onchange="setChange();" type="radio" {COMMENT_DISABLED} name="comment" value="0" />{L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_WATERMARK}</span></td>
	<td class="row2"><span class="genmed"><input onchange="setChange();" type="radio" {WATERMARK_ENABLED} name="use_watermark" value="1" />{L_YES}&nbsp;&nbsp;<input onchange="setChange();" type="radio" {WATERMARK_DISABLED} name="use_watermark" value="0" />{L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_WATERMARK_USERS}</span></td>
	<td class="row2"><span class="genmed"><input onchange="setChange();" type="radio" {WATERMARK_USERS_ENABLED} name="wut_users" value="1" />{L_YES}&nbsp;&nbsp;<input onchange="setChange();" type="radio" {WATERMARK_USERS_DISABLED} name="wut_users" value="0" />{L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_WATERMARK_PLACENT}</span></td>
	<td class="row2">
		<table cellpadding="4">
			<tr>
				<td><input onchange="setChange();" type="radio" {WATERMARK_PLACEMENT_1} name="disp_watermark_at" value="1" /></td>
				<td><input onchange="setChange();" type="radio" {WATERMARK_PLACEMENT_2} name="disp_watermark_at" value="2" /></td>
				<td><input onchange="setChange();" type="radio" {WATERMARK_PLACEMENT_3} name="disp_watermark_at" value="3" /></td>
			</tr>
			<tr>
				<td><input onchange="setChange();" type="radio" {WATERMARK_PLACEMENT_4} name="disp_watermark_at" value="4" /></td>
				<td><input onchange="setChange();" type="radio" {WATERMARK_PLACEMENT_5} name="disp_watermark_at" value="5" /></td>
				<td><input onchange="setChange();" type="radio" {WATERMARK_PLACEMENT_6} name="disp_watermark_at" value="6" /></td>
			</tr>
			<tr>
				<td><input onchange="setChange();" type="radio" {WATERMARK_PLACEMENT_7} name="disp_watermark_at" value="7" /></td>
				<td><input onchange="setChange();" type="radio" {WATERMARK_PLACEMENT_8} name="disp_watermark_at" value="8" /></td>
				<td><input onchange="setChange();" type="radio" {WATERMARK_PLACEMENT_9} name="disp_watermark_at" value="9" /></td>
			</tr>
		</table>
	</td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_EMAIL_NOTIFICATION}</span></td>
	<td class="row2"><span class="genmed"><input onchange="setChange();" type="radio" {EMAIL_NOTIFICATION_ENABLED} name="email_notification" value="1" />{L_YES}&nbsp;&nbsp;<input onchange="setChange();" type="radio" {EMAIL_NOTIFICATION_DISABLED} name="email_notification" value="0" />{L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_SHOW_DOWNLOAD}</span></td>
	<td class="row2"><span class="genmed"><input onchange="setChange();" type="radio" {SHOW_DOWNLOAD_ALWAYS} name="show_download" value="2" />{L_ALWAYS}&nbsp;&nbsp;<input onchange="setChange();" type="radio" {SHOW_DOWNLOAD_ENABLED} name="show_download" value="1" />{L_YES}&nbsp;&nbsp;<input onchange="setChange();" type="radio" {SHOW_DOWNLOAD_DISABLED} name="show_download" value="0" />{L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_SHOW_SLIDESHOW}</span></td>
	<td class="row2"><span class="genmed"><input onchange="setChange();" type="radio" {SHOW_SLIDESHOW_ENABLED} name="show_slideshow" value="1" />{L_YES}&nbsp;&nbsp;<input onchange="setChange();" type="radio" {SHOW_SLIDESHOW_DISABLED} name="show_slideshow" value="0" />{L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_SHOW_SLIDESHOW_SCRIPT}</span></td>
	<td class="row2"><span class="genmed"><input onchange="setChange();" type="radio" {SLIDESHOW_SCRIPT_ENABLED} name="slideshow_script" value="1" />{L_YES}&nbsp;&nbsp;<input onchange="setChange();" type="radio" {SLIDESHOW_SCRIPT_DISABLED} name="slideshow_script" value="0" />{L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_SHOW_PICS_NAV}</span></td>
	<td class="row2"><span class="genmed"><input onchange="setChange();" type="radio" {SHOW_PICS_NAV_ENABLED} name="show_pics_nav" value="1" />{L_YES}&nbsp;&nbsp;<input onchange="setChange();" type="radio" {SHOW_PICS_NAV_DISABLED} name="show_pics_nav" value="0" />{L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_INVERT_NAV_ARROWS}</span></td>
	<td class="row2"><span class="genmed"><input onchange="setChange();" type="radio" {INVERT_NAV_ARROWS_ENABLED} name="invert_nav_arrows" value="1" />{L_YES}&nbsp;&nbsp;<input onchange="setChange();" type="radio" {INVERT_NAV_ARROWS_DISABLED} name="invert_nav_arrows" value="0" />{L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_SHOW_INLINE_COPYRIGHT}</span></td>
	<td class="row2"><span class="genmed"><input onchange="setChange();" type="radio" {SHOW_INLINE_COPYRIGHT_ENABLED} name="show_inline_copyright" value="1" />{L_YES}&nbsp;&nbsp;<input onchange="setChange();" type="radio" {SHOW_INLINE_COPYRIGHT_DISABLED} name="show_inline_copyright" value="0" />{L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_ENABLE_NUFFIMAGE}</span></td>
	<td class="row2"><span class="genmed"><input onchange="setChange();" type="radio" {NUFFIMAGE_ENABLED} name="enable_nuffimage" value="1" />{L_YES}&nbsp;&nbsp;<input onchange="setChange();" type="radio" {NUFFIMAGE_DISABLED} name="enable_nuffimage" value="0" />{L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_ENABLE_SEPIA_BW}</span></td>
	<td class="row2"><span class="genmed"><input onchange="setChange();" type="radio" {SEPIABW_ENABLED} name="enable_sepia_bw" value="1" />{L_YES}&nbsp;&nbsp;<input onchange="setChange();" type="radio" {SEPIABW_DISABLED} name="enable_sepia_bw" value="0" />{L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_SHOW_EXIF}</span></td>
	<td class="row2"><span class="genmed"><input onchange="setChange();" type="radio" {SHOW_EXIF_ENABLED} name="show_exif" value="1" />{L_YES}&nbsp;&nbsp;<input onchange="setChange();" type="radio" {SHOW_EXIF_DISABLED} name="show_exif" value="0" />{L_NO}</span></td>
</tr>
