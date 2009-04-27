<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td align="right">{ALBUM_SEARCH_BOX}</td></tr></table>

{ALBUM_BOARD_INDEX}
<!-- BEGIN personal_picrow -->
<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<!-- BEGIN piccol -->
		<td align="center" width="{S_COL_WIDTH}" class="row1g row-center">
			<center>
			<table><tr><td><div class="picshadow"><div class="picframe">
				<a href="{personal_picrow.piccol.U_PIC_DL}"{personal_picrow.piccol.PIC_PREVIEW_HS}><img src="{personal_picrow.piccol.THUMBNAIL}" alt="{personal_picrow.piccol.PIC_TITLE}" title="{personal_picrow.piccol.PIC_TITLE}" vspace="10" border="0" /></a>
			</div></div></td></tr></table>
			</center>
		</td>
		<!-- END piccol -->
	</tr>
	<tr>
		<!-- BEGIN pic_detail -->
		<td class="row2 row-center">
			<span class="gensmall">
				{L_PIC_TITLE}: <a href="{personal_picrow.pic_detail.U_PIC_SP}">{personal_picrow.pic_detail.PIC_TITLE}</a><br />
				{L_DOWNLOAD}: <a href="{personal_picrow.pic_detail.U_PIC_DL}">{personal_picrow.pic_detail.PIC_TITLE}</a><br />
				{L_PIC_ID}: {personal_picrow.pic_detail.PIC_ID}<br />
				{L_POSTED}: {personal_picrow.pic_detail.TIME}<br />
				{L_VIEW}: {picrpersonal_picrowow.pic_detail.VIEW}<br />
				{personal_picrow.pic_detail.RATING}
				{personal_picrow.pic_detail.COMMENTS}
				{personal_picrow.pic_detail.IP}
				{personal_picrow.pic_detail.EDIT} {personal_picrow.pic_detail.DELETE} {personal_picrow.pic_detail.LOCK}
			</span>
		</td>
		<!-- END pic_detail -->
	</tr>
</table>
<!-- END personal_picrow -->

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
			<center>
			<table><tr><td><div class="picshadow"><div class="picframe">
				<a href="{recent_comments_block.comment_row.U_PIC_DL}"{recent_comments_block.comment_row.PIC_PREVIEW_HS}><img src="{recent_comments_block.comment_row.THUMBNAIL}" {THUMB_SIZE} alt="{recent_comments_block.comment_row.PIC_TITLE}" title="{recent_comments_block.comment_row.PIC_TITLE}" vspace="10" border="0" /></a>
			</div></div></td></tr></table>
			</center>
		</td>
		<td class="row1" valign="top" nowrap="nowrap">
			<div style="text-align: right; float: right;"><span class="gensmall"><b>{L_PIC_TITLE}</b>: <a href="{recent_comments_block.comment_row.U_PIC_SP}">{recent_comments_block.comment_row.PIC_TITLE}</a></span></div>
			<span class="gensmall"><b>{L_POSTER}</b>:&nbsp;{recent_comments_block.comment_row.POSTER}&nbsp;[{recent_comments_block.comment_row.TIME}]</span>
			<hr />
			<div class="post-text">{recent_comments_block.comment_row.COMMENT_TEXT}</div>
		</td>
	</tr>
	<!-- END comment_row -->
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<br />
<!-- END recent_comments_block -->

<!-- BEGIN recent_pics_block -->
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
				<a href="{recent_pics_block.recent_pics.recent_col.U_PIC_DL}"{recent_pics_block.recent_pics.recent_col.PIC_PREVIEW_HS}><img src="{recent_pics_block.recent_pics.recent_col.THUMBNAIL}" {THUMB_SIZE} alt="{recent_pics_block.recent_pics.recent_col.PIC_TITLE}" title="{recent_pics_block.recent_pics.recent_col.PIC_TITLE}" vspace="10" border="0" /></a>
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
				{L_PIC_TITLE}: <a href="{recent_pics_block.recent_pics.recent_detail.U_PIC_SP}">{recent_pics_block.recent_pics.recent_detail.PIC_TITLE}</a><br />
				{L_DOWNLOAD}: <a href="{recent_pics_block.recent_pics.recent_detail.U_PIC_DL}">{recent_pics_block.recent_pics.recent_detail.PIC_TITLE}</a><br />
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

<!-- BEGIN highest_pics_block -->
{IMG_THL}{IMG_THC}<span class="forumlink">{L_HI_RATINGS}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
	<!-- BEGIN no_pics -->
	<tr><td class="row1g row-center" colspan="{S_COLS}" height="50"><span class="gen">{L_NO_PICS}</span></td></tr>
	<!-- END no_pics -->
	<!-- BEGIN highest_pics -->
	<tr>
		<!-- BEGIN highest_col -->
		<td class="row1g row-center" width="{S_COL_WIDTH}" align="center">
			<center>
			<table><tr><td><div class="picshadow"><div class="picframe">
				<a href="{highest_pics_block.highest_pics.highest_col.U_PIC_DL}"{highest_pics_block.highest_pics.highest_col.PIC_PREVIEW_HS}><img src="{highest_pics_block.highest_pics.highest_col.THUMBNAIL}" {THUMB_SIZE} alt="{highest_pics_block.highest_pics.highest_col.PIC_TITLE}" title="{highest_pics_block.highest_pics.highest_col.PIC_TITLE}" vspace="10" border="0" /></a>
			</div></div></td></tr></table>
			</center>
		</td>
		<!-- END highest_col -->
	</tr>
	<tr>
		<!-- BEGIN highest_detail -->
		<td class="row2 row-center">
			<span class="gensmall">
				{L_POSTER}: {highest_pics_block.highest_pics.highest_detail.H_POSTER}<br />
				{L_PIC_TITLE}: <a href="{highest_pics_block.highest_pics.highest_detail.U_PIC_SP}">{highest_pics_block.highest_pics.highest_detail.PIC_TITLE}</a><br />
				{L_DOWNLOAD}: <a href="{highest_pics_block.highest_pics.highest_detail.U_PIC_DL}">{highest_pics_block.highest_pics.highest_detail.PIC_TITLE}</a><br />
				{L_PIC_ID}: {highest_pics_block.highest_pics.highest_detail.PIC_ID}<br />
				{L_POSTED}: {highest_pics_block.highest_pics.highest_detail.H_TIME}<br />
				{L_VIEW}: {highest_pics_block.highest_pics.highest_detail.H_VIEW}<br />
				{highest_pics_block.highest_pics.highest_detail.H_RATING}
				{highest_pics_block.highest_pics.highest_detail.H_COMMENTS}
				{highest_pics_block.highest_pics.highest_detail.H_IP}
			</span>
		</td>
	<!-- END highest_detail -->
	</tr>
	<!-- END highest_pics -->
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<br />
<!-- END highest_pics_block -->

<!-- BEGIN mostviewed_pics_block -->
{IMG_THL}{IMG_THC}<span class="forumlink">{L_MOST_VIEWED}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
	<!-- BEGIN no_pics -->
	<tr><td class="row1g row-center" colspan="{S_COLS}" height="50"><span class="gen">{L_NO_PICS}</span></td></tr>
	<!-- END no_pics -->
	<!-- BEGIN mostviewed_pics -->
	<tr>
	<!-- BEGIN mostviewed_col -->
		<td class="row1g row-center" width="{S_COL_WIDTH}" align="center">
			<center>
			<table><tr><td><div class="picshadow"><div class="picframe">
				<a href="{mostviewed_pics_block.mostviewed_pics.mostviewed_col.U_PIC_DL}"{mostviewed_pics_block.mostviewed_pics.mostviewed_col.PIC_PREVIEW_HS}><img src="{mostviewed_pics_block.mostviewed_pics.mostviewed_col.THUMBNAIL}" {THUMB_SIZE} alt="{mostviewed_pics_block.mostviewed_pics.mostviewed_col.PIC_TITLE}" title="{mostviewed_pics_block.mostviewed_pics.mostviewed_col.PIC_TITLE}" vspace="10" border="0" /></a>
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
				{L_PIC_TITLE}: <a href="{mostviewed_pics_block.mostviewed_pics.mostviewed_detail.U_PIC_SP}">{mostviewed_pics_block.mostviewed_pics.mostviewed_detail.PIC_TITLE}</a><br />
				{L_DOWNLOAD}: <a href="{mostviewed_pics_block.mostviewed_pics.mostviewed_detail.U_PIC_DL}">{mostviewed_pics_block.mostviewed_pics.mostviewed_detail.PIC_TITLE}</a><br />
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

<!-- BEGIN random_pics_block -->
{IMG_THL}{IMG_THC}<span class="forumlink">{L_RAND_PICS}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
	<!-- BEGIN no_pics -->
	<tr><td class="row1g row-center" colspan="{S_COLS}" height="50"><span class="gen">{L_NO_PICS}</span></td></tr>
	<!-- END no_pics -->
	<!-- BEGIN rand_pics -->
	<tr>
		<!-- BEGIN rand_col -->
		<td class="row1g row-center" width="{S_COL_WIDTH}" align="center">
			<center>
			<table><tr><td><div class="picshadow"><div class="picframe">
				<a href="{random_pics_block.rand_pics.rand_col.U_PIC_DL}"{random_pics_block.rand_pics.rand_col.PIC_PREVIEW_HS}><img src="{random_pics_block.rand_pics.rand_col.THUMBNAIL}" {THUMB_SIZE} alt="{random_pics_block.rand_pics.rand_col.PIC_TITLE}" title="{random_pics_block.rand_pics.rand_col.PIC_TITLE}" vspace="10" border="0" /></a>
			</div></div></td></tr></table>
			</center>
		</td>
		<!-- END rand_col -->
	</tr>
	<tr>
		<!-- BEGIN rand_detail -->
		<td class="row2 row-center">
			<span class="gensmall">
				{L_POSTER}: {random_pics_block.rand_pics.rand_detail.POSTER}<br />
				{L_PIC_TITLE}: <a href="{random_pics_block.rand_pics.rand_detail.U_PIC_SP}">{random_pics_block.rand_pics.rand_detail.PIC_TITLE}</a><br />
				{L_DOWNLOAD}: <a href="{random_pics_block.rand_pics.rand_detail.U_PIC_DL}">{random_pics_block.rand_pics.rand_detail.PIC_TITLE}</a><br />
				{L_PIC_ID}: {random_pics_block.rand_pics.rand_detail.PIC_ID}<br />
				{L_POSTED}: {random_pics_block.rand_pics.rand_detail.TIME}<br />
				{L_VIEW}: {random_pics_block.rand_pics.rand_detail.VIEW}<br />
				{random_pics_block.rand_pics.rand_detail.RATING}
				{random_pics_block.rand_pics.rand_detail.COMMENTS}
				{random_pics_block.rand_pics.rand_detail.IP}
			</span>
		</td>
		<!-- END rand_detail -->
	</tr>
	<!-- END rand_pics -->
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<br />
<!-- END random_pics_block -->

<br clear="all" />
<!-- You must keep my copyright notice visible with its original content -->
{ALBUM_COPYRIGHT}
