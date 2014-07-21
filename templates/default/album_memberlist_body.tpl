<!-- INCLUDE overall_header.tpl -->

<table>
<tr>
	<td class="tw100pct"><span class="genmed">{L_MEMBERLIST_GALLERY_EXPLAIN}</span></td>
	<!-- BEGIN switch_show_album_search -->
	<td class="tdalignr tvalignb tdnw">{ALBUM_SEARCH_BOX}</td>
	<!-- END switch_show_album_search -->
</tr>
</table>

<table>
<tr>
	<td class="tw50pct">
		<!-- BEGIN switch_show_all_pics -->
		<span class="img-btn"><a href="{U_SHOW_ALL_PICS}"><img src="{SHOW_ALL_PICS_IMG}" alt="{L_SHOW_ALL_PICS}" title="{L_SHOW_ALL_PICS}" /></a></span>&nbsp;
		<!-- END switch_show_all_pics -->
		<!-- BEGIN switch_show_all_ratings -->
		<span class="img-btn"><a href="{U_SHOW_ALL_RATINGS}"><img src="{SHOW_ALL_RATINGS_IMG}" alt="{L_SHOW_ALL_RATINGS}" title="{L_SHOW_ALL_RATINGS}" /></a></span>&nbsp;
		<!-- END switch_show_all_ratings -->
		<!-- BEGIN switch_show_all_comments -->
		<span class="img-btn"><a href="{U_SHOW_ALL_COMMENTS}"><img src="{SHOW_ALL_COMMENTS_IMG}" alt="{L_SHOW_ALL_COMMENTS}" title="{L_SHOW_ALL_COMMENTS}" /></a></span>&nbsp;
		<!-- END switch_show_all_comments -->
	</td>
	<td class="tw50pct tdalignr tdnw"><span class="pagination">{PAGINATION}</span></td>
</tr>
</table>

{IMG_THL}{IMG_THC}<span class="forumlink">{L_PICTURES_OF_USER}</span>{IMG_THR}<table class="forumlinenb">
<!-- BEGIN no_pics -->
<tr><td class="row1 row-center th50px"><span class="gen">{L_NO_PICTURES_BY_USER}</span></td></tr>
<!-- END no_pics -->
<!-- BEGIN picrow -->
<tr>
	<!-- BEGIN piccol -->
	<td class="row1g row-center" style="min-width: {S_COL_WIDTH}px;">
		<center>
		<table><tr><td><div class="picshadow"><div class="picframe">
			<a href="{picrow.piccol.U_PIC_DL}"{picrow.piccol.PIC_PREVIEW_HS}><img class="vs10px" src="{picrow.piccol.THUMBNAIL}" {THUMB_SIZE} alt="{picrow.piccol.PIC_TITLE}" title="{picrow.piccol.PIC_TITLE}" border="0" /></a>
		</div></div></td></tr></table>
		</center>
	</td>
	<!-- END piccol -->
</tr>
<tr>
	<!-- BEGIN pic_detail -->
	<td class="row2">
		<div class="gensmall">
			{L_PIC_TITLE}: <a href="{picrow.pic_detail.U_PIC_SP}" {TARGET_BLANK}>{picrow.pic_detail.TITLE}</a><br />
			{L_DOWNLOAD}: <a href="{picrow.pic_detail.U_PIC_DL}" {TARGET_BLANK}>{picrow.pic_detail.TITLE}</a><br />
			{L_PIC_ID}: {picrow.pic_detail.PIC_ID}<br />
			{L_PIC_CAT}: <a href="{picrow.pic_detail.U_PIC_CAT}" {TARGET_BLANK}>{picrow.pic_detail.CATEGORY}</a><br />
			{L_POSTED}: {picrow.pic_detail.TIME}<br />
			{L_VIEW}: {picrow.pic_detail.VIEW}<br />
			<!-- IF picrow.pic_detail.RATING --> 
			{picrow.pic_detail.RATING}<br />
			<!-- ENDIF -->
			<!-- IF picrow.pic_detail.COMMENTS --> 
			{picrow.pic_detail.COMMENTS}<br />
			<!-- ENDIF -->
			<!-- IF picrow.pic_detail.IP --> 
			{picrow.pic_detail.IP}<br />
			<!-- ENDIF -->
			{picrow.pic_detail.EDIT}
			{picrow.pic_detail.DELETE}
			{picrow.pic_detail.LOCK}
			{picrow.pic_detail.MOVE}
			{picrow.pic_detail.COPY}
		</div>
	</td>
	<!-- END pic_detail -->
</tr>
<!-- END picrow -->
<tr>
	<td class="cat tdalignc" colspan="{S_COLS}">
		<form action="{U_MEMBERLIST_GALLERY}" method="post">
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

<table>
<tr>
	<td class="tw50pct">
		<!-- BEGIN switch_show_all_pics -->
		<span class="img-btn"><a href="{U_SHOW_ALL_PICS}"><img src="{SHOW_ALL_PICS_IMG}" alt="{L_SHOW_ALL_PICS}" title="{L_SHOW_ALL_PICS}" /></a></span>&nbsp;
		<!-- END switch_show_all_pics -->
		<!-- BEGIN switch_show_all_ratings -->
		<span class="img-btn"><a href="{U_SHOW_ALL_RATINGS}"><img src="{SHOW_ALL_RATINGS_IMG}" alt="{L_SHOW_ALL_RATINGS}" title="{L_SHOW_ALL_RATINGS}" /></a></span>&nbsp;
		<!-- END switch_show_all_ratings -->
		<!-- BEGIN switch_show_all_comments -->
		<span class="img-btn"><a href="{U_SHOW_ALL_COMMENTS}"><img src="{SHOW_ALL_COMMENTS_IMG}" alt="{L_SHOW_ALL_COMMENTS}" title="{L_SHOW_ALL_COMMENTS}" /></a></span>&nbsp;
		<!-- END switch_show_all_comments -->
	</td>
	<td class="tw50pct tdalignr tdnw"><span class="pagination">{PAGINATION}</span><br /></td>
</tr>
<tr><td class="tdalignr" colspan="2"><span class="gensmall">{PAGE_NUMBER}</span></td></tr>
</table>
</form>

<br />
<!-- You must keep my copyright notice visible with its original content -->
{ALBUM_COPYRIGHT}

<!-- INCLUDE overall_footer.tpl -->