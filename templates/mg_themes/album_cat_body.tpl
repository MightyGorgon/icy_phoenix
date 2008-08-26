<div class="forumline nav-div">
	<p class="nav-header"><a href="{U_PORTAL}">{L_HOME}</a>{NAV_SEP}<a href="{U_ALBUM}" class="nav">{L_ALBUM}</a>{NAV_CAT_DESC}</p>
	<div class="nav-links">
		<div class="nav-links-left">{CURRENT_TIME}</div>
		{SLIDESHOW}&nbsp;
	</div>
</div>
<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td align="left" valign="top">
		<span class="gensmall"><b>{L_MODERATORS}: {MODERATORS}</b></span>
		{WAITING}
		<!-- BEGIN personal_gallery_header -->
		<br />
		<span class="genmed">{L_PERSONAL_GALLERY_EXPLAIN}</span>
		<!-- END personal_gallery_header -->
	</td>
	<td align="right" nowrap="nowrap">{ALBUM_SEARCH_BOX}</td>
</tr>
</table>

<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td align="left">
		<!-- BEGIN manage_personal_gal_folders -->
		<a href="{U_MANAGE_PIC}"><img src="{MANAGE_PIC_IMG}" alt="{L_MANAGE_PIC}" title="{L_MANAGE_PIC}" align="middle" /></a>
		<!-- END manage_personal_gal_folders -->
		<!-- BEGIN enable_view_toggle -->
		<a href="{U_TOGGLE_VIEW_ALL}"><img src="{TOGGLE_VIEW_ALL_IMG}" alt="{L_TOGGLE_VIEW_ALL}" title="{L_TOGGLE_VIEW_ALL}" align="middle" /></a>
		<!-- END enable_view_toggle -->
		<!-- BEGIN enable_picture_upload -->
		{UPLOAD_FULL_LINK}
		<!-- END enable_picture_upload -->
		<!-- BEGIN enable_picture_upload_pg -->
		{UPLOAD_FULL_LINK}
		<!-- END enable_picture_upload_pg -->
		<!-- BEGIN enable_picture_download -->
		{DOWNLOAD_FULL_LINK}
		<!-- END enable_picture_download -->
		<!-- BEGIN enable_picture_download_pg -->
		{DOWNLOAD_FULL_LINK}
		<!-- END enable_picture_download_pg -->
	</td>
	<td align="right" valign="bottom" rowspan="2"><span class="gensmall">{PAGE_NUMBER}</span><br /><span class="pagination">{PAGINATION}</span></td>
</tr>
</table>
{ALBUM_BOARD_INDEX}
<!-- BEGIN index_pics_block -->
{IMG_THL}{IMG_THC}<span class="forumlink">{CAT_TITLE}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<!-- BEGIN no_pics -->
<tr>
	<td class="row1g row-center" colspan="{S_COLS}">
		<span class="gen">
			<br />
			{L_NO_PICS}<br />
			<br />
			<!-- BEGIN manage_personal_gal_folders -->
			{U_CREATE_PERSONAL_GALLERY}<br />
			<br />
			<!-- END manage_personal_gal_folders -->
		</span>
	</td>
</tr>
<!-- END no_pics -->
<!-- BEGIN picrow -->
<tr>
	<!-- BEGIN piccol -->
	<td class="row1g row-center" align="center" width="{S_COL_WIDTH}">
		<center>
		<table><tr><td><div class="picshadow"><div class="picframe">
			<a href="{index_pics_block.picrow.piccol.U_PIC}" {TARGET_BLANK}><img src="{index_pics_block.picrow.piccol.THUMBNAIL}" {THUMB_SIZE} alt="{index_pics_block.picrow.piccol.DESC}" title="{index_pics_block.picrow.piccol.DESC}" vspace="10" border="0" {index_pics_block.picrow.piccol.PIC_PREVIEW} /></a>
		</div></div></td></tr></table>
		</center>
		<span class="genmed"><br />{index_pics_block.picrow.piccol.APPROVAL}</span>
	</td>
	<!-- END piccol -->
</tr>
<tr>
	<!-- BEGIN pic_detail -->
	<td class="row2 row-center">
		<span class="gensmall">
			{L_PIC_TITLE}: {index_pics_block.picrow.pic_detail.TITLE}<br />
			{L_PIC_ID}: {index_pics_block.picrow.pic_detail.PIC_ID}<br />
			<!-- BEGIN cats -->
			{L_PIC_CAT}: <a href="{index_pics_block.picrow.pic_detail.cats.U_PIC_CAT}" {TARGET_BLANK}>{index_pics_block.picrow.pic_detail.cats.CATEGORY}</a><br />
			<!-- END cats -->
			{L_POSTER}: {index_pics_block.picrow.pic_detail.POSTER}<br />
			{L_POSTED}: {index_pics_block.picrow.pic_detail.TIME}<br />
			{L_VIEW}: {index_pics_block.picrow.pic_detail.VIEW}<br />
			{index_pics_block.picrow.pic_detail.RATING}
			{index_pics_block.picrow.pic_detail.COMMENTS}
			{index_pics_block.picrow.pic_detail.IP}
			{index_pics_block.picrow.pic_detail.EDIT} {index_pics_block.picrow.pic_detail.DELETE} {index_pics_block.picrow.pic_detail.LOCK} {index_pics_block.picrow.pic_detail.MOVE} {index_pics_block.picrow.pic_detail.COPY} {index_pics_block.picrow.pic_detail.AVATAR_PIC} <!-- {index_pics_block.picrow.pic_detail.IMG_BBCODE} -->
		</span>
	</td>
	<!-- END pic_detail -->
</tr>
<!-- END picrow -->
<tr>
	<td class="cat" colspan="{S_COLS}" align="center" height="28">
		<form action="{S_ALBUM_ACTION}" method="post">
		<span class="gensmall">{L_SELECT_SORT_METHOD}:
		<select name="sort_method">
			<option {SORT_TIME} value='pic_time'>{L_TIME}</option>
			<option {SORT_PIC_TITLE} value='pic_title'>{L_PIC_TITLE}</option>
			{SORT_USERNAME_OPTION}
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
		</form>
	</td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<!-- END index_pics_block -->

<!-- BEGIN recent_comments_block -->
<br />
{IMG_THL}{IMG_THC}<span class="forumlink">{recent_comments_block.L_LAST_COMMENT_INFO}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th width="10%" nowrap="nowrap">{L_PIC_TITLE}</th>
	<th width="90%" nowrap="nowrap">{recent_comments_block.L_COMMENTS}</th>
</tr>
<!-- BEGIN comment_row -->
<tr>
	<td class="row1 row-center" valign="middle">
		<center><table><tr><td><div class="picshadow"><div class="picframe">
			<a href="{recent_comments_block.comment_row.U_PIC}" {TARGET_BLANK}><img src="{recent_comments_block.comment_row.THUMBNAIL}" {THUMB_SIZE} alt="{recent_comments_block.comment_row.DESC}" title="{recent_comments_block.comment_row.DESC}" vspace="10" border="0" {recent_comments_block.comment_row.PIC_PREVIEW} /></a>
		</div></div></td></tr></table></center>
	</td>
	<td class="row1" valign="top" nowrap="nowrap">
		<div style="text-align: right; float: right;"><span class="gensmall"><b>{L_PIC_TITLE}</b>: {recent_comments_block.comment_row.TITLE}</span></div>
		<span class="gensmall"><b>{L_POSTER}</b>:&nbsp;{recent_comments_block.comment_row.POSTER}&nbsp;[{recent_comments_block.comment_row.TIME}]</span>
		<hr />
		<div class="post-text">{recent_comments_block.comment_row.COMMENT_TEXT}</div>
		<!--
		<span class="gensmall"><b>{L_PIC_TITLE}</b>: {recent_comments_block.comment_row.TITLE}</span><br />
		<span class="gensmall"><b>{L_POSTER}</b>: {recent_comments_block.comment_row.POSTER}</span><br />
		<span class="gensmall"><b>{L_POSTED}</b>: {recent_comments_block.comment_row.TIME}</span><hr />
		<span class="gensmall"><b>{recent_comments_block.L_LAST_COMMENT}</b>:</span><hr />
		<div class="post-text">{recent_comments_block.comment_row.COMMENT_TEXT}</div>
		-->
	</td>
</tr>
<!-- END comment_row -->
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<br />
<!-- END recent_comments_block -->

<!-- BEGIN recent_pics_block -->
<br />
{IMG_THL}{IMG_THC}<span class="forumlink">{L_RECENT_PUBLIC_PICS}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<!-- BEGIN no_pics -->
<tr><td class="row1g row-center" colspan="{S_COLS}" height="50"><span class="gen">{L_NO_PICS}</span></td></tr>
<!-- END no_pics -->
<!-- BEGIN recent_pics -->
<tr>
<!-- BEGIN recent_col -->
<td class="row1g row-center" width="{S_COL_WIDTH}" align="center">
	<center>
	<table><tr><td><div class="picshadow"><div class="picframe">
		<a href="{recent_pics_block.recent_pics.recent_col.U_PIC}" {TARGET_BLANK}><img src="{recent_pics_block.recent_pics.recent_col.THUMBNAIL}" {THUMB_SIZE} alt="{recent_pics_block.recent_pics.recent_col.DESC}" title="{recent_pics_block.recent_pics.recent_col.DESC}" vspace="10" border="0" {recent_pics_block.recent_pics.recent_col.PIC_PREVIEW} /></a>
	</div></div></td></tr></table>
	</center>
</td>
<!-- END recent_col -->
</tr>
<tr>
	<!-- BEGIN recent_detail -->
	<td class="row2 row-center">
		<span class="gensmall">
			{L_POSTER}: {recent_pics_block.recent_pics.recent_detail.POSTER}<br />
			{L_PIC_TITLE}: {recent_pics_block.recent_pics.recent_detail.TITLE}<br />
			{L_PIC_ID}: {recent_pics_block.recent_pics.recent_detail.PIC_ID}<br />
			{L_POSTED}: {recent_pics_block.recent_pics.recent_detail.TIME}<br />
			{L_VIEW}: {recent_pics_block.recent_pics.recent_detail.VIEW}<br />
			{recent_pics_block.recent_pics.recent_detail.RATING}
			{recent_pics_block.recent_pics.recent_detail.COMMENTS}
			{recent_pics_block.recent_pics.recent_detail.IP}
		</span>
	</td>
	<!-- END recent_detail -->
</tr>
<!-- END recent_pics -->
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<br />
<!-- END recent_pics_block -->

<!-- BEGIN mostviewed_pics_block -->
{IMG_THL}{IMG_THC}<span class="forumlink">{L_MOST_VIEWED}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<!-- BEGIN no_pics -->
<tr><td class="row1g row-center" align="center" colspan="{S_COLS}" height="50"><span class="gen">{L_NO_PICS}</span></td></tr>
<!-- END no_pics -->
<!-- BEGIN mostviewed_pics -->
<tr>
<!-- BEGIN mostviewed_col -->
<td class="row1g row-center" width="{S_COL_WIDTH}" align="center">
	<center>
	<table><tr><td><div class="picshadow"><div class="picframe">
		<a href="{mostviewed_pics_block.mostviewed_pics.mostviewed_col.U_PIC}" {TARGET_BLANK}><img src="{mostviewed_pics_block.mostviewed_pics.mostviewed_col.THUMBNAIL}" {THUMB_SIZE} alt="{mostviewed_pics_block.mostviewed_pics.mostviewed_col.DESC}" title="{mostviewed_pics_block.mostviewed_pics.mostviewed_col.DESC}" vspace="10" border="0" {mostviewed_pics_block.mostviewed_pics.mostviewed_col.PIC_PREVIEW} /></a>
	</div></div></td></tr></table>
	</center>
</td>
<!-- END mostviewed_col -->
</tr>
<tr>
<!-- BEGIN mostviewed_detail -->
	<td class="row2 row-center">
		<span class="gensmall">
			{L_POSTER}: {mostviewed_pics_block.mostviewed_pics.mostviewed_detail.H_POSTER}<br />
			{L_PIC_TITLE}: {mostviewed_pics_block.mostviewed_pics.mostviewed_detail.H_TITLE}<br />
			{L_PIC_ID}: {mostviewed_pics_block.mostviewed_pics.mostviewed_detail.PIC_ID}<br />
			{L_POSTED}: {mostviewed_pics_block.mostviewed_pics.mostviewed_detail.H_TIME}<br />
			{L_VIEW}: {mostviewed_pics_block.mostviewed_pics.mostviewed_detail.H_VIEW}<br />
			{mostviewed_pics_block.mostviewed_pics.mostviewed_detail.H_RATING}
			{mostviewed_pics_block.mostviewed_pics.mostviewed_detail.H_COMMENTS}
			{mostviewed_pics_block.mostviewed_pics.mostviewed_detail.H_IP}
		</span>
	</td>
<!-- END mostviewed_detail -->
</tr>
<!-- END mostviewed_pics -->
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<br />
<!-- END mostviewed_pics_block -->
<div class="forumline nav-div">
	<p class="nav-header">
		<a href="{U_PORTAL}">{L_HOME}</a>{NAV_SEP}<a href="{U_ALBUM}" class="nav">{L_ALBUM}</a>{NAV_CAT_DESC}
	<div class="nav-links">
		<div class="nav-links-left">{CURRENT_TIME}</div>
		{SLIDESHOW}&nbsp;
	</div>
</div>
<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td align="left">
		<!-- BEGIN manage_personal_gal_folders -->
		<a href="{U_MANAGE_PIC}"><img src="{MANAGE_PIC_IMG}" alt="{L_MANAGE_PIC}" title="{L_MANAGE_PIC}" align="middle" /></a>
		<!-- END manage_personal_gal_folders -->
		<!-- BEGIN enable_view_toggle -->
		<a href="{U_TOGGLE_VIEW_ALL}"><img src="{TOGGLE_VIEW_ALL_IMG}" alt="{L_TOGGLE_VIEW_ALL}" title="{L_TOGGLE_VIEW_ALL}" align="middle" /></a>
		<!-- END enable_view_toggle -->
		<!-- BEGIN enable_picture_upload -->
		{UPLOAD_FULL_LINK}
		<!-- END enable_picture_upload -->
		<!-- BEGIN enable_picture_upload_pg -->
		{UPLOAD_FULL_LINK}
		<!-- END enable_picture_upload_pg -->
		<!-- BEGIN enable_picture_download -->
		{DOWNLOAD_FULL_LINK}
		<!-- END enable_picture_download -->
		<!-- BEGIN enable_picture_download_pg -->
		{DOWNLOAD_FULL_LINK}
		<!-- END enable_picture_download_pg -->
	</td>
	<td align="right" valign="top"><span class="gensmall">{PAGE_NUMBER}</span><br /><span class="pagination">{PAGINATION}</span></td>
</tr>
</table>

<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td align="left" valign="top" class="gensmall" width="40%">
		<div class="forumline nav-div">
			<!-- <p class="nav-header">{L_PERMISSIONS_LIST}</p> -->
			<div class="nav-links2">{S_AUTH_LIST}</div>
		</div>
		<!--
		<div class="quote">
			{S_AUTH_LIST}
		</div>
		-->
	</td>
	<td align="right" valign="top">{ALBUM_JUMPBOX}<br /><br />{S_TOPIC_ADMIN}</td>
</tr>
</table>
<br />
<!-- You must keep my copyright notice visible with its original content -->
{ALBUM_COPYRIGHT}