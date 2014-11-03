<!-- INCLUDE overall_header.tpl -->

<table>
<tr>
	<td>
		<span class="gensmall"><b>{L_MODERATORS}: {MODERATORS}</b></span>
		{WAITING}
		<!-- BEGIN personal_gallery_header -->
		<br />
		<span class="genmed">{L_PERSONAL_GALLERY_EXPLAIN}</span>
		<!-- END personal_gallery_header -->
	</td>
	<td class="tdalignr tdnw">{ALBUM_SEARCH_BOX}</td>
</tr>
</table>

<table>
<tr>
	<td>
		<!-- BEGIN manage_personal_gal_folders -->
		<span class="img-btn"><a href="{U_MANAGE_PIC}"><img src="{MANAGE_PIC_IMG}" alt="{L_MANAGE_PIC}" title="{L_MANAGE_PIC}" align="middle" /></a></span>
		<!-- END manage_personal_gal_folders -->
		<!-- BEGIN enable_view_toggle -->
		<span class="img-btn"><a href="{U_TOGGLE_VIEW_ALL}"><img src="{TOGGLE_VIEW_ALL_IMG}" alt="{L_TOGGLE_VIEW_ALL}" title="{L_TOGGLE_VIEW_ALL}" align="middle" /></a></span>
		<!-- END enable_view_toggle -->
		<!-- BEGIN enable_picture_upload -->
		<span class="img-btn">{UPLOAD_FULL_LINK}</span>
		<!-- END enable_picture_upload -->
		<!-- BEGIN enable_picture_upload_pg -->
		<span class="img-btn">{UPLOAD_FULL_LINK}</span>
		<!-- END enable_picture_upload_pg -->
		<!-- BEGIN enable_picture_download -->
		<span class="img-btn">{DOWNLOAD_FULL_LINK}</span>
		<!-- END enable_picture_download -->
		<!-- BEGIN enable_picture_download_pg -->
		<span class="img-btn">{DOWNLOAD_FULL_LINK}</span>
		<!-- END enable_picture_download_pg -->
	</td>
	<td class="tdalignr tvalignb" rowspan="2"><span class="gensmall">{PAGE_NUMBER}</span><br /><span class="pagination">{PAGINATION}</span></td>
</tr>
</table>
<!-- BEGIN index_pics_block -->
{IMG_THL}{IMG_THC}<span class="forumlink">{CAT_TITLE}</span>{IMG_THR}<table class="forumlinenb">
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
	<td class="row1g row-center" width="{S_COL_WIDTH}">
		<a href="{index_pics_block.picrow.piccol.U_PIC_DL}"{index_pics_block.picrow.piccol.PIC_PREVIEW_HS}><img class="picframe" src="{index_pics_block.picrow.piccol.THUMBNAIL}" {THUMB_SIZE} alt="{index_pics_block.picrow.piccol.PIC_TITLE}" title="{index_pics_block.picrow.piccol.PIC_TITLE}" /></a>
		<!-- IF index_pics_block.picrow.piccol.APPROVAL --><br /><span class="genmed">{index_pics_block.picrow.piccol.APPROVAL}</span><!-- ENDIF -->
	</td>
	<!-- END piccol -->
</tr>
<tr>
	<!-- BEGIN pic_detail -->
	<td class="row2 row-center">
		<div class="gensmall">
			{L_PIC_TITLE}: <a href="{index_pics_block.picrow.piccol.pic_detail.U_PIC_SP}">{index_pics_block.picrow.pic_detail.PIC_TITLE}</a><br />
			{L_DOWNLOAD}: <a href="{index_pics_block.picrow.piccol.pic_detail.U_PIC_DL}">{index_pics_block.picrow.pic_detail.PIC_TITLE}</a><br />
			{L_PIC_ID}: {index_pics_block.picrow.pic_detail.PIC_ID}<br />
			<!-- BEGIN cats -->
			{L_PIC_CAT}: <a href="{index_pics_block.picrow.pic_detail.cats.U_PIC_CAT}" {TARGET_BLANK}>{index_pics_block.picrow.pic_detail.cats.CATEGORY}</a><br />
			<!-- END cats -->
			{L_POSTER}: {index_pics_block.picrow.pic_detail.POSTER}<br />
			{L_POSTED}: {index_pics_block.picrow.pic_detail.TIME}<br />
			{L_VIEW}: {index_pics_block.picrow.pic_detail.VIEW}<br />
			<!-- IF index_pics_block.picrow.pic_detail.RATING --> 
			{index_pics_block.picrow.pic_detail.RATING}<br />
			<!-- ENDIF -->
			<!-- IF index_pics_block.picrow.pic_detail.COMMENTS --> 
			{index_pics_block.picrow.pic_detail.COMMENTS}<br />
			<!-- ENDIF -->
			<!-- IF index_pics_block.picrow.pic_detail.IP --> 
			{index_pics_block.picrow.pic_detail.IP}<br />
			<!-- ENDIF -->
			{index_pics_block.picrow.pic_detail.EDIT} {index_pics_block.picrow.pic_detail.DELETE} {index_pics_block.picrow.pic_detail.LOCK} {index_pics_block.picrow.pic_detail.MOVE} {index_pics_block.picrow.pic_detail.COPY} {index_pics_block.picrow.pic_detail.AVATAR_PIC} <!-- {index_pics_block.picrow.pic_detail.IMG_BBCODE} -->
		</div>
	</td>
	<!-- END pic_detail -->
</tr>
<!-- END picrow -->
<tr>
	<td class="cat tdalignc" colspan="{S_COLS}">
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
<br />

{ALBUM_BOARD_INDEX}

<!-- BEGIN recent_comments_block -->
{IMG_THL}{IMG_THC}<span class="forumlink">{recent_comments_block.L_LAST_COMMENT_INFO}</span>{IMG_THR}<table class="forumlinenb">
<tr>
	<th class="tw10pct tdnw">{L_PIC_TITLE}</th>
	<th class="tw90pct tdnw">{recent_comments_block.L_COMMENTS}</th>
</tr>
<!-- BEGIN comment_row -->
<tr>
	<td class="row1 row-center tvalignm">
		<a href="{recent_comments_block.comment_row.U_PIC_DL}"{recent_comments_block.comment_row.PIC_PREVIEW_HS}><img class="picframe" src="{recent_comments_block.comment_row.THUMBNAIL}" {THUMB_SIZE} alt="{recent_comments_block.comment_row.PIC_TITLE}" title="{recent_comments_block.comment_row.PIC_TITLE}" /></a>
	</td>
	<td class="row1 tdnw">
		<div style="text-align: right; float: right;"><span class="gensmall"><b>{L_PIC_TITLE}</b>: <a href="{recent_comments_block.comment_row.U_PIC_SP}">{recent_comments_block.comment_row.PIC_TITLE}</a></span></div>
		<span class="gensmall"><b>{L_POSTER}</b>:&nbsp;{recent_comments_block.comment_row.POSTER}&nbsp;[{recent_comments_block.comment_row.TIME}]</span>
		<hr />
		<div class="post-text post-text-hide-flow">{recent_comments_block.comment_row.COMMENT_TEXT}</div>
	</td>
</tr>
<!-- END comment_row -->
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<br />
<br />
<!-- END recent_comments_block -->

<!-- BEGIN recent_pics_block -->
{IMG_THL}{IMG_THC}<span class="forumlink">{L_RECENT_PUBLIC_PICS}</span>{IMG_THR}<table class="forumlinenb">
<!-- BEGIN no_pics -->
<tr><td class="row1g row-center th50px" colspan="{S_COLS}"><span class="gen">{L_NO_PICS}</span></td></tr>
<!-- END no_pics -->
<!-- BEGIN recent_pics -->
<tr>
<!-- BEGIN recent_col -->
<td class="row1g row-center" width="{S_COL_WIDTH}">
	<a href="{recent_pics_block.recent_pics.recent_col.U_PIC_DL}"{recent_pics_block.recent_pics.recent_col.PIC_PREVIEW_HS}><img class="picframe" src="{recent_pics_block.recent_pics.recent_col.THUMBNAIL}" {THUMB_SIZE} alt="{recent_pics_block.recent_pics.recent_col.PIC_TITLE}" title="{recent_pics_block.recent_pics.recent_col.PIC_TITLE}" /></a>
</td>
<!-- END recent_col -->
</tr>
<tr>
	<!-- BEGIN recent_detail -->
	<td class="row2 row-center">
		<div class="gensmall">
			{L_POSTER}: {recent_pics_block.recent_pics.recent_detail.POSTER}<br />
			{L_PIC_TITLE}: <a href="{recent_pics_block.recent_pics.recent_detail.U_PIC_SP}">{recent_pics_block.recent_pics.recent_detail.PIC_TITLE}</a><br />
			{L_DOWNLOAD}: <a href="{recent_pics_block.recent_pics.recent_detail.U_PIC_DL}">{recent_pics_block.recent_pics.recent_detail.PIC_TITLE}</a><br />
			{L_PIC_ID}: {recent_pics_block.recent_pics.recent_detail.PIC_ID}<br />
			{L_POSTED}: {recent_pics_block.recent_pics.recent_detail.TIME}<br />
			{L_VIEW}: {recent_pics_block.recent_pics.recent_detail.VIEW}<br />
			<!-- IF recent_pics_block.recent_pics.recent_detail.RATING --> 
			{recent_pics_block.recent_pics.recent_detail.RATING}<br />
			<!-- ENDIF -->
			<!-- IF recent_pics_block.recent_pics.recent_detail.COMMENTS --> 
			{recent_pics_block.recent_pics.recent_detail.COMMENTS}<br />
			<!-- ENDIF -->
			<!-- IF recent_pics_block.recent_pics.recent_detail.IP --> 
			{recent_pics_block.recent_pics.recent_detail.IP}<br />
			<!-- ENDIF -->
		</div>
	</td>
	<!-- END recent_detail -->
</tr>
<!-- END recent_pics -->
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<br />
<!-- END recent_pics_block -->

<!-- BEGIN mostviewed_pics_block -->
{IMG_THL}{IMG_THC}<span class="forumlink">{L_MOST_VIEWED}</span>{IMG_THR}<table class="forumlinenb">
<!-- BEGIN no_pics -->
<tr><td class="row1g row-center th50px" colspan="{S_COLS}"><span class="gen">{L_NO_PICS}</span></td></tr>
<!-- END no_pics -->
<!-- BEGIN mostviewed_pics -->
<tr>
<!-- BEGIN mostviewed_col -->
<td class="row1g row-center" width="{S_COL_WIDTH}">
	<a href="{mostviewed_pics_block.mostviewed_pics.mostviewed_col.U_PIC_DL}"{mostviewed_pics_block.mostviewed_pics.mostviewed_col.PIC_PREVIEW_HS}><img class="picframe" src="{mostviewed_pics_block.mostviewed_pics.mostviewed_col.THUMBNAIL}" {THUMB_SIZE} alt="{mostviewed_pics_block.mostviewed_pics.mostviewed_col.PIC_TITLE}" title="{mostviewed_pics_block.mostviewed_pics.mostviewed_col.PIC_TITLE}" /></a>
</td>
<!-- END mostviewed_col -->
</tr>
<tr>
<!-- BEGIN mostviewed_detail -->
	<td class="row2 row-center">
		<div class="gensmall">
			{L_POSTER}: {mostviewed_pics_block.mostviewed_pics.mostviewed_detail.POSTER}<br />
			{L_PIC_TITLE}: <a href="{mostviewed_pics_block.mostviewed_pics.mostviewed_detail.U_PIC_SP}">{mostviewed_pics_block.mostviewed_pics.mostviewed_detail.PIC_TITLE}</a><br />
			{L_DOWNLOAD}: <a href="{mostviewed_pics_block.mostviewed_pics.mostviewed_detail.U_PIC_DL}">{mostviewed_pics_block.mostviewed_pics.mostviewed_detail.PIC_TITLE}</a><br />
			{L_PIC_ID}: {mostviewed_pics_block.mostviewed_pics.mostviewed_detail.PIC_ID}<br />
			{L_POSTED}: {mostviewed_pics_block.mostviewed_pics.mostviewed_detail.TIME}<br />
			{L_VIEW}: {mostviewed_pics_block.mostviewed_pics.mostviewed_detail.VIEW}<br />
			<!-- IF mostviewed_pics_block.mostviewed_pics.mostviewed_detail.RATING --> 
			{mostviewed_pics_block.mostviewed_pics.mostviewed_detail.RATING}<br />
			<!-- ENDIF -->
			<!-- IF mostviewed_pics_block.mostviewed_pics.mostviewed_detail.COMMENTS --> 
			{mostviewed_pics_block.mostviewed_pics.mostviewed_detail.COMMENTS}<br />
			<!-- ENDIF -->
			<!-- IF mostviewed_pics_block.mostviewed_pics.mostviewed_detail.IP --> 
			{mostviewed_pics_block.mostviewed_pics.mostviewed_detail.IP}<br />
			<!-- ENDIF -->
		</div>
	</td>
<!-- END mostviewed_detail -->
</tr>
<!-- END mostviewed_pics -->
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<br />
<!-- END mostviewed_pics_block -->

<table>
<tr>
	<td>
		<!-- BEGIN manage_personal_gal_folders -->
		<span class="img-btn"><a href="{U_MANAGE_PIC}"><img src="{MANAGE_PIC_IMG}" alt="{L_MANAGE_PIC}" title="{L_MANAGE_PIC}" align="middle" /></a></span>
		<!-- END manage_personal_gal_folders -->
		<!-- BEGIN enable_view_toggle -->
		<span class="img-btn"><a href="{U_TOGGLE_VIEW_ALL}"><img src="{TOGGLE_VIEW_ALL_IMG}" alt="{L_TOGGLE_VIEW_ALL}" title="{L_TOGGLE_VIEW_ALL}" align="middle" /></a></span>
		<!-- END enable_view_toggle -->
		<!-- BEGIN enable_picture_upload -->
		<span class="img-btn">{UPLOAD_FULL_LINK}</span>
		<!-- END enable_picture_upload -->
		<!-- BEGIN enable_picture_upload_pg -->
		<span class="img-btn">{UPLOAD_FULL_LINK}</span>
		<!-- END enable_picture_upload_pg -->
		<!-- BEGIN enable_picture_download -->
		<span class="img-btn">{DOWNLOAD_FULL_LINK}</span>
		<!-- END enable_picture_download -->
		<!-- BEGIN enable_picture_download_pg -->
		<span class="img-btn">{DOWNLOAD_FULL_LINK}</span>
		<!-- END enable_picture_download_pg -->
	</td>
	<td class="tdalignr"><span class="gensmall">{PAGE_NUMBER}</span><br /><span class="pagination">{PAGINATION}</span></td>
</tr>
</table>

<table>
<tr>
	<td class="gensmall tw40pct"><div class="forumline row-post gensmall" style="padding: 5px; text-align: left !important; vertical-align: top !important;">{S_AUTH_LIST}</div></td>
	<td class="tdalignr">{ALBUM_JUMPBOX}<br /><br />{S_TOPIC_ADMIN}</td>
</tr>
</table>
<br />
<!-- You must keep my copyright notice visible with its original content -->
{ALBUM_COPYRIGHT}

<!-- INCLUDE overall_footer.tpl -->