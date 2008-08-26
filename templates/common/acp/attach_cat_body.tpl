<h1>{L_MANAGE_CAT_TITLE}</h1>
<p>{L_MANAGE_CAT_EXPLAIN}</p>
{ERROR_BOX}

<form action="{S_ATTACH_ACTION}" method="post">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><th colspan="2">{L_SETTINGS_CAT_IMAGES}<br />{L_ASSIGNED_GROUP}: {S_ASSIGNED_GROUP_IMAGES}</th></tr>
<tr>
	<td class="row1" width="80%"><strong>{L_DISPLAY_INLINED}</strong><br /><span class="gensmall">{L_DISPLAY_INLINED_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="img_display_inlined" value="1" {DISPLAY_INLINED_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="img_display_inlined" value="0" {DISPLAY_INLINED_NO} /> {L_NO}</td>
</tr>
<!-- BEGIN switch_thumbnail_support -->
<tr>
	<td class="row1" width="80%"><strong>{L_CREATE_THUMBNAIL}</strong><br /><span class="gensmall">{L_CREATE_THUMBNAIL_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="img_create_thumbnail" value="1" {CREATE_THUMBNAIL_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="img_create_thumbnail" value="0" {CREATE_THUMBNAIL_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1" width="80%"><strong>{L_MIN_THUMB_FILESIZE}</strong><br /><span class="gensmall">{L_MIN_THUMB_FILESIZE_EXPLAIN}</span></td>
	<td class="row2"><input type="text" size="7" maxlength="15" name="img_min_thumb_filesize" value="{IMAGE_MIN_THUMB_FILESIZE}" class="post" /> {L_BYTES}</td>
</tr>
<tr>
	<td class="row1" width="80%"><strong>{L_USE_GD2}</strong><br /><span class="gensmall">{L_USE_GD2_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="use_gd2" value="1" {USE_GD2_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="use_gd2" value="0" {USE_GD2_NO} /> {L_NO}</td>
</tr>
<!-- END switch_thumbnail_support -->
<tr>
	<td class="row1" width="80%"><strong>{L_IMAGICK_PATH}</strong><br /><span class="gensmall">{L_IMAGICK_PATH_EXPLAIN}</span></td>
	<td class="row2"><input type="text" size="20" maxlength="200" name="img_imagick" value="{IMAGE_IMAGICK_PATH}" class="post" /></td>
</tr>
<tr>
	<td class="row1" width="80%"><strong>{L_MAX_IMAGE_SIZE}</strong><br /><span class="gensmall">{L_MAX_IMAGE_SIZE_EXPLAIN}</span></td>
	<td class="row2"><input type="text" size="3" maxlength="4" name="img_max_width" value="{IMAGE_MAX_WIDTH}" class="post" /> x <input type="text" size="3" maxlength="4" name="img_max_height" value="{IMAGE_MAX_HEIGHT}" class="post" /></td>
</tr>
<tr>
	<td class="row1" width="80%"><strong>{L_IMAGE_LINK_SIZE}</strong><br /><span class="gensmall">{L_IMAGE_LINK_SIZE_EXPLAIN}</span></td>
	<td class="row2"><input type="text" size="3" maxlength="4" name="img_link_width" value="{IMAGE_LINK_WIDTH}" class="post" /> x <input type="text" size="3" maxlength="4" name="img_link_height" value="{IMAGE_LINK_HEIGHT}" class="post" /></td>
</tr>
<tr>
	<td class="cat" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" />&nbsp;&nbsp;<input type="submit" name="search_imagick" value="{L_SEARCH_IMAGICK}" class="liteoption" />&nbsp;&nbsp;<input type="submit" name="cat_settings" value="{L_TEST_SETTINGS}" class="liteoption" /></td>
</tr>
</table>
</form>

<br />
<div align="center"><span class="copyright">{ATTACH_VERSION}</span></div>

<br clear="all" />
