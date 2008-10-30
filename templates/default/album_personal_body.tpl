<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td width="100%">
		<a class="maintitle" href="{U_PERSONAL_GALLERY}">{L_PERSONAL_GALLERY_OF_USER}</a><br />
		<span class="genmed">{L_PERSONAL_GALLERY_EXPLAIN}</span>
	</td>
	<td align="right" valign="bottom" nowrap="nowrap">
		{ALBUM_SEARCH_BOX}
		<span class="pagination">{PAGINATION}</span>
	</td>
</tr>
</table>

<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td class="nav" align="left">
		<!-- BEGIN your_personal_gallery -->
		{UPLOAD_FULL_LINK}
		<!-- END your_personal_gallery -->
		<!-- BEGIN enable_picture_download -->
		{DOWNLOAD_FULL_LINK}
		<!-- END enable_picture_download -->
		<br />
		<span class="nav">
			<a href="{U_PORTAL}">{L_HOME}</a>{NAV_SEP}<a href="{U_ALBUM}">{L_ALBUM}</a>{NAV_SEP}<a href="{U_PERSONAL_GALLERY}" class="nav-current">{L_PERSONAL_GALLERY_OF_USER}</a>
		</span>
	</td>
	<td class="nav" align="right" valign="bottom"><span class="gensmall"><b>{SLIDESHOW}</b></span></td>
</tr>
</table>

{IMG_THL}{IMG_THC}<span class="forumlink">{L_PERSONAL_GALLERY_OF_USER}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<!-- BEGIN no_pics_personal -->
<tr>
	<td class="row1 row-center">
		<span class="gen">
			<br />
			{L_PERSONAL_GALLERY_NOT_CREATED}<br /><br />
			<a href="{U_CREATE_PERSONAL_GALLERY}">{L_CREATE_PERSONAL_GALLERY}</a><br />
			<br />
		</span>
	</td>
</tr>
<!-- END no_pics_personal -->
<!-- BEGIN picrow -->
<tr>
<!-- BEGIN piccol -->
	<td class="row1g row-center" width="{S_COL_WIDTH}" align="center">
		<center>
		<table><tr><td><div class="picshadow"><div class="picframe">
			<a href="{picrow.piccol.U_PIC}" {TARGET_BLANK}><img src="{picrow.piccol.THUMBNAIL}" {THUMB_SIZE} alt="{picrow.piccol.PIC_TITLE}" title="{picrow.piccol.PIC_TITLE}" vspace="10" border="0" {picrow.piccol.PIC_PREVIEW} /></a>
		</div></div></td></tr></table>
		</center>
	</td>
<!-- END piccol -->
</tr>
<tr>
	<!-- BEGIN pic_detail -->
	<td class="row2 row-center"><span class="gensmall">
		{L_PIC_TITLE}: {picrow.pic_detail.TITLE}<br />
		{L_PIC_ID}: {picrow.pic_detail.PIC_ID}<br />
		{L_POSTED}: {picrow.pic_detail.TIME}<br />
		{L_VIEW}: {picrow.pic_detail.VIEW}<br />
		{picrow.pic_detail.RATING}
		{picrow.pic_detail.COMMENTS}
		{picrow.pic_detail.IP}
		{picrow.pic_detail.EDIT}&nbsp;&nbsp;{picrow.pic_detail.DELETE}&nbsp;&nbsp;{picrow.pic_detail.LOCK}</span>
	</td>
	<!-- END pic_detail -->
</tr>
<!-- END picrow -->
<tr>
	<td class="cat" colspan="{S_COLS}" align="center" height="28">
		<form action="{U_PERSONAL_GALLERY}" method="post">
		<span class="gensmall">{L_SELECT_SORT_METHOD}:
		<select name="sort_method">
			<option {SORT_TIME} value='pic_time'>{L_TIME}</option>
			<option {SORT_PIC_TITLE} value='pic_title'>{L_PIC_TITLE}</option>
			<option {SORT_VIEW} value='pic_view_count'>{L_VIEW}</option>
			{SORT_RATING_OPTION}
			{SORT_COMMENTS_OPTION}
			{SORT_NEW_COMMENT_OPTION}
		</select>
		&nbsp;{L_ORDER}:
		<select name="sort_order">
			<option {SORT_ASC} value='ASC'>{L_ASC}</option>
			<option {SORT_DESC} value='DESC'>{L_DESC}</option>
		</select>
		&nbsp;<input type="submit" name="submit" value="{L_SORT}" class="liteoption" /></span>
	</td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}

<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td class="nav" width="100%">
		<!-- BEGIN your_personal_gallery -->
		{UPLOAD_FULL_LINK}
		<!-- END your_personal_gallery -->
		<!-- BEGIN enable_picture_download -->
		{DOWNLOAD_FULL_LINK}
		<!-- END enable_picture_download -->
		<br />
		<span class="nav">
			<a href="{U_PORTAL}">{L_HOME}</a>{NAV_SEP}<a href="{U_ALBUM}">{L_ALBUM}</a>{NAV_SEP}<a href="{U_PERSONAL_GALLERY}" class="nav-current">{L_PERSONAL_GALLERY_OF_USER}</a>
		</span>
	</td>
	<td align="right" nowrap="nowrap">
		<span class="gensmall">{S_TIMEZONE}</span><br />
		<span class="pagination">{PAGINATION}</span>
	</td>
</tr>
<tr><td colspan="3"><span class="gensmall">{PAGE_NUMBER}</span></td></tr>
</table>
</form>
<br />
<!-- You must keep my copyright notice visible with its original content -->
{ALBUM_COPYRIGHT}