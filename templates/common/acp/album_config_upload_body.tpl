<tr>
	<td class="row1"><span class="genmed">{L_MAX_FILE_SIZE}</span></td>
	<td class="row2"><input onchange="setChange();" class="post" type="text" maxlength="12" size="12" name="max_file_size" value="{MAX_FILE_SIZE}" /></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_DYNAMIC_PIC_RESAMPLING}</span></td>
	<td class="row2"><span class="genmed"><input onchange="setChange();" type="radio" {DYNAMIC_PIC_RESAMPLING_ENABLED} name="dynamic_pic_resampling" value="1" />{L_YES}&nbsp;&nbsp;<input onchange="setChange();" type="radio" {DYNAMIC_PIC_RESAMPLING_DISABLED} name="dynamic_pic_resampling" value="0" />{L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_MAX_FILE_SIZE_RESAMPLING}</span></td>
	<td class="row2"><input onchange="setChange();" class="post" type="text" maxlength="12" size="12" name="max_file_size_resampling" value="{MAX_FILE_SIZE_RESAMPLING}" /></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_MAX_WIDTH}</span></td>
	<td class="row2"><input onchange="setChange();" class="post" type="text" maxlength="9" size="9" name="max_width" value="{MAX_WIDTH}" /></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_MAX_HEIGHT}</span></td>
	<td class="row2"><input onchange="setChange();" class="post" type="text" maxlength="9" size="9" name="max_height" value="{MAX_HEIGHT}" /></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_MAX_FILES_TO_UPLOAD}</span></td>
	<td class="row2"><input class="post" type="text" maxlength="2" size="2" name="max_files_to_upload" value="{MAX_FILES_TO_UPLOAD}" /></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_MAX_PREGENERATED_FIELDS}</span></td>
	<td class="row2"><input class="post" type="text" maxlength="2" size="2" name="max_pregenerated_fields" value="{MAX_PREGENERATED_FIELDS}" /></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_DYN_GENERATE_FIELDS}</span></td>
	<td class="row2"><span class="genmed"><input type="radio" {DYNFIELDS_ENABLED} name="dynamic_fields" value="1" />{L_YES}&nbsp;&nbsp;<input type="radio" {DYNFIELDS_DISABLED} name="dynamic_fields" value="0" />{L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_PRE_GENERATE_FIELDS}</span></td>
	<td class="row2"><span class="genmed"><input type="radio" {PREGENFIELDS_ENABLED} name="pregenerate_fields" value="1" />{L_YES}&nbsp;&nbsp;<input type="radio" {PREGENFIELDS_DISABLED} name="pregenerate_fields" value="0" />{L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_PROPERCASE_TITLE}</span></td>
	<td class="row2"><span class="genmed"><input type="radio" {PROPERCASE_TITLE_ENABLED} name="propercase_pic_title" value="1" />{L_YES}&nbsp;&nbsp;<input type="radio" {PROPERCASE_TITLE_DISABLED} name="propercase_pic_title" value="0" />{L_NO}</span></td>
</tr>
<tr>
<tr>
	<td class="row1"><span class="genmed">{L_PIC_DESC_MAX_LENGTH}</span></td>
	<td class="row2"><input onchange="setChange();" class="post" type="text" size="6" name="desc_length" value="{PIC_DESC_MAX_LENGTH}" /></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_GD_VERSION}</span></td>
	<td class="row2"><span class="genmed"><input onchange="setChange();" type="radio" {NO_GD} name="gd_version" value="0" />{L_MANUAL_THUMBNAIL}&nbsp;&nbsp;<input onchange="setChange();" type="radio" {GD_V1} name="gd_version" value="1" />GD1&nbsp;&nbsp;<input onchange="setChange();" type="radio" {GD_V2} name="gd_version" value="2" />GD2</span></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_JPG_ALLOWED}</span></td>
	<td class="row2"><span class="genmed"><input onchange="setChange();" type="radio" {JPG_ENABLED} name="jpg_allowed" value="1" />{L_YES}&nbsp;&nbsp;<input onchange="setChange();" type="radio" {JPG_DISABLED} name="jpg_allowed" value="0" />{L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_PNG_ALLOWED}</span></td>
	<td class="row2"><span class="genmed"><input onchange="setChange();" type="radio" {PNG_ENABLED} name="png_allowed" value="1" />{L_YES}&nbsp;&nbsp;<input onchange="setChange();" type="radio" {PNG_DISABLED} name="png_allowed" value="0" />{L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_GIF_ALLOWED}</span></td>
	<td class="row2"><span class="genmed"><input onchange="setChange();" type="radio" {GIF_ENABLED} name="gif_allowed" value="1" />{L_YES}&nbsp;&nbsp;<input onchange="setChange();" type="radio" {GIF_DISABLED} name="gif_allowed" value="0" />{L_NO}</span></td>
</tr>
<tr><th colspan="2">&nbsp;</th></tr>
<!-- BEGIN switch_nuffload -->
<tr><th colspan="2">{L_ALBUM_NUFFLOAD_CONFIG}</th></tr>
<tr>
	<td class="row1">
		<span class="genmed">{L_ENABLE_NUFFLOAD}</span><br />
		<span class="gensmall">{L_ENABLE_NUFFLOAD_EXPLAIN}</span>
	</td>
	<td class="row2"><span class="genmed"><input onchange="setChange();" type="radio" {NUFFLOAD_ENABLED} name="switch_nuffload" value="1" />{L_YES}&nbsp;&nbsp;<input onchange="setChange();" type="radio" {NUFFLOAD_DISABLED} name="switch_nuffload" value="0" />{L_NO}</span></td>
</tr>
<tr><th colspan="2">{L_PROGRESS_BAR_CONFIG}</th></tr>
<tr>
	<td class="row1"><span class="genmed">{L_PERL_UPLOADER}</span></td>
	<td class="row2"><span class="genmed"><input type="radio" {PERL_UPLOADER_ENABLED} name="perl_uploader" value="1" />{L_YES}&nbsp;&nbsp;<input type="radio" {PERL_UPLOADER_DISABLED} name="perl_uploader" value="0" />{L_NO}</span></td>
</tr>
<tr>
	<td class="row1" width="45%"><span class="genmed">{L_PATH_TO_BIN}</span></td>
	<td class="row2"><input class="post" type="text" size="15" name="path_to_bin" value="{PATH_TO_BIN}" /></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_SHOW_PROGRESS_BAR}</span></td>
	<td class="row2"><span class="genmed"><input type="radio" {SHOW_PROGRESS_BAR_ENABLED} name="show_progress_bar" value="1" />{L_YES}&nbsp;&nbsp;<input type="radio" {SHOW_PROGRESS_BAR_DISABLED} name="show_progress_bar" value="0" />{L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_CLOSE_ON_FINISH}</span></td>
	<td class="row2"><span class="genmed"><input type="radio" {CLOSE_ON_FINISH_ENABLED} name="close_on_finish" value="1" />{L_YES}&nbsp;&nbsp;<input type="radio" {CLOSE_ON_FINISH_DISABLED} name="close_on_finish" value="0" />{L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_SIMPLE_FORMAT}</span></td>
	<td class="row2"><span class="genmed"><input type="radio" {SIMPLE_FORMAT_ENABLED} name="simple_format" value="1" />{L_YES}&nbsp;&nbsp;<input type="radio" {SIMPLE_FORMAT_DISABLED} name="simple_format" value="0" />{L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_MAX_PAUSE}</span></td>
	<td class="row2"><input class="post" type="text" maxlength="2" size="2" name="max_pause" value="{MAX_PAUSE}" /></td>
</tr>
<tr><th colspan="2">{L_MULTIPLE_UPLOADS_CONFIG}</th></tr>
<tr>
	<td class="row1"><span class="genmed">{L_MULTIPLE_UPLOADS}</span></td>
	<td class="row2"><span class="genmed"><input type="radio" {MULTIPLE_UPLOADS_ENABLED} name="multiple_uploads" value="1" />{L_YES}&nbsp;&nbsp;<input type="radio" {MULTIPLE_UPLOADS_DISABLED} name="multiple_uploads" value="0" />{L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_MAX_UPLOADS}</span></td>
	<td class="row2"><input class="post" type="text" maxlength="4" size="4" name="max_uploads" value="{MAX_UPLOADS}" /></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_ZIP_UPLOADS}</span></td>
	<td class="row2"><span class="genmed"><input type="radio" {ZIP_UPLOADS_ENABLED} name="zip_uploads" value="1" />{L_YES}&nbsp;&nbsp;<input type="radio" {ZIP_UPLOADS_DISABLED} name="zip_uploads" value="0" />{L_NO}</span></td>
</tr>
<tr><th colspan="2">{L_RESIZE_PICS_CONFIG}</th></tr>
<tr>
	<td class="row1"><span class="genmed">{L_RESIZE_PIC}</span></td>
	<td class="row2"><span class="genmed"><input type="radio" {RESIZE_PIC_ENABLED} name="resize_pic" value="1" />{L_YES}&nbsp;&nbsp;<input type="radio" {RESIZE_PIC_DISABLED} name="resize_pic" value="0" />{L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_RESIZE_WIDTH}</span></td>
	<td class="row2"><input class="post" type="text" maxlength="4" size="4" name="resize_width" value="{RESIZE_WIDTH}" /></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_RESIZE_HEIGHT}</span></td>
	<td class="row2"><input class="post" type="text" maxlength="4" size="4" name="resize_height" value="{RESIZE_HEIGHT}" /></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_RESIZE_QUALITY}</span></td>
	<td class="row2"><input class="post" type="text" maxlength="3" size="3" name="resize_quality" value="{RESIZE_QUALITY}" /></td>
</tr>
<!-- END switch_nuffload -->