<div class="forumline nav-div">
	<p class="nav-header">
		<a href="{U_PORTAL}">{L_HOME}</a>{NAV_SEP}<a href="{U_INDEX}">{L_INDEX}</a>{NAV_SEP}<a href="{U_DL_TOP}">{L_DL_TOP}</a>{U_DL_CAT}
	</p>
	<div class="nav-links">
		<div class="nav-links-left">{CURRENT_TIME}</div>
		<div class="post-buttons">
			<!-- BEGIN edit_button -->
			<a href="{edit_button.U_EDIT}">{edit_button.EDIT_IMG}</a>&nbsp;
			<!-- END edit_button -->
			{U_SEARCH}
		</div>
		&nbsp;
	</div>
</div>

<!-- BEGIN downloads -->
{IMG_THL}{IMG_THC}<span class="forumlink">{L_DOWNLOADS}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th width="20%" nowrap="nowrap">{L_DESCRIPTION}</th>
	<th colspan="2" nowrap="nowrap">{L_DETAILS}</th>
<!-- BEGIN thumbnail -->
	<th width="20%" colspan="2" nowrap="nowrap">{downloads.thumbnail.L_THUMBNAIL}</th>
<!-- END thumbnail -->
</tr>
<tr>
	<td width="20%" class="{ROW_CLASS2}" align="right" nowrap="nowrap"><span class="gen">{L_NAME}:</span></td>
	<td width="20%" class="{ROW_CLASS1}" align="center"><span class="gen"><b>{downloads.DESCRIPTION}</b> {downloads.HACK_VERSION}</span> {downloads.MINI_IMG}</td>
	<td class="{ROW_CLASS1} row-center" rowspan="{ROWSPAN}"><span class="gen">{downloads.LONG_DESC}</span></td>
<!-- BEGIN thumbnail -->
	<td width="20%" class="{ROW_CLASS2} row-center" rowspan="{ROWSPAN}"><img src="{downloads.thumbnail.THUMBNAIL}" border="0" alt="" title="" /></td>
<!-- END thumbnail -->
</tr>
<tr>
	<td width="20%" class="{ROW_CLASS2}" align="right" nowrap="nowrap"><span class="gen">{L_FILE_NAME}:</span></td>
	<td width="20%" class="{ROW_CLASS1} row-center"><span class="gen">{downloads.FILE_NAME}</span></td>
</tr>
<tr>
	<td width="20%" class="{ROW_CLASS2}" align="right" nowrap="nowrap"><span class="gen">{L_DOWNLOAD}:</span></td>
	<td width="20%" class="{ROW_CLASS1} row-center"><span class="gen">{downloads.STATUS}</span></td>
</tr>
<tr>
	<td width="20%" class="{ROW_CLASS2}" align="right" nowrap="nowrap"><span class="gen">{L_SIZE}:</span></td>
	<td width="20%" class="{ROW_CLASS1} row-center"><span class="gen">{downloads.FILE_SIZE}</span></td>
</tr>
<!-- BEGIN real_filetime -->
<tr>
	<td width="20%" class="{ROW_CLASS2}" align="right" nowrap="nowrap"><span class="gen">{downloads.real_filetime.L_REAL_FILETIME}:</span></td>
	<td width="20%" class="{ROW_CLASS1} row-center"><span class="gen">{downloads.real_filetime.REAL_FILETIME}</span></td>
</tr>
<!-- END real_filetime -->
<tr>
	<td width="20%" class="{ROW_CLASS2}" align="right" nowrap="nowrap"><span class="gen">{L_KLICKS}:</span></td>
	<td width="20%" class="{ROW_CLASS1} row-center"><span class="gen">{downloads.FILE_KLICKS}</span></td>
</tr>
<tr>
	<td width="20%" class="{ROW_CLASS2}" align="right" nowrap="nowrap"><span class="gen">{L_OVERALL_KLICKS}:</span></td>
	<td width="20%" class="{ROW_CLASS1} row-center"><span class="gen">{downloads.FILE_OVERALL_KLICKS}</span></td>
</tr>
<!-- END downloads -->
<!-- BEGIN hacklist -->
<tr>
	<td width="20%" class="{ROW_CLASS2}" align="right"><span class="gen">{L_DL_HACK_AUTHOR}:</span></td>
	<td width="20%" class="{ROW_CLASS1} row-center"><span class="gen">{hacklist.HACK_AUTHOR}</span></td>
</tr>
<tr>
	<td width="20%" class="{ROW_CLASS2}" align="right"><span class="gen">{L_DL_HACK_AUTHOR_WEBSITE}:</span></td>
	<td width="20%" class="{ROW_CLASS1} row-center"><span class="gen">{hacklist.HACK_AUTHOR_WEBSITE}</span></td>
</tr>
<tr>
	<td width="20%" class="{ROW_CLASS2}" align="right"><span class="gen">{L_DL_HACK_DL_URL}:</span></td>
	<td width="20%" class="{ROW_CLASS1} row-center"><span class="gen">{hacklist.HACK_DL_URL}</span></td>
</tr>
<!-- END hacklist -->
<tr>
	<td width="20%" class="{ROW_CLASS2}" align="right"><span class="gen">{L_RATING_TITLE}{RATINGS}:</span></td>
	<td width="20%" class="{ROW_CLASS1} row-center"><span class="gensmall">{RATING_IMG}
		<!-- BEGIN rating_view -->
		<br /><a href="{rating_view.U_RATING}" class="gensmall">{rating_view.L_RATING}</a>
		<!-- END rating_view -->
	</span></td>
</tr>
<!-- BEGIN bug_tracker -->
<tr>
	<td width="20%" class="{ROW_CLASS2}" align="right"><span class="gen">{bug_tracker.L_BUG_TRACKER}:</span></td>
	<td width="20%" class="{ROW_CLASS1} row-center"><a href="{bug_tracker.U_BUG_TRACKER}" class="gen">{bug_tracker.L_BUG_TRACKER_FILE}</a></td>
</tr>
<!-- END bug_tracker -->
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}

<!-- BEGIN download_button -->
<form action="{download_button.U_DOWNLOAD}" method="post" name="download">
<!-- END download_button -->
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<!-- BEGIN allow_trafficfree_download -->
<tr><td align="center"><span class="gensmall"><b class="text_red">{allow_trafficfree_download.L_YOU_CAN_DOWNLOAD}</b></span><br /><br /></td></tr>
<!-- END allow_trafficfree_download -->
<!-- BEGIN download_button -->
<tr>
	<td align="center">
		<!-- BEGIN vc -->
		<img src="{download_button.vc.VC}" style="vertical-align:bottom;" alt="" title="" />&nbsp;
		<input type="text" value="" name="code" class="post" size="5" maxlength="5" />&nbsp;
		<!-- END vc -->
		<input type="submit" value="{L_DOWNLOAD}" class="mainoption" />
		<input type="hidden" name="hotlink_id" value="{download_button.S_HOTLINK_ID}" />
	</td>
</tr>
<!-- END download_button -->
</table>
<!-- BEGIN download_button -->
</form>
<!-- END download_button -->

<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1">
		<div style="float:right;text-align:right;">
		<span class="gensmall">
		<!-- BEGIN report_broken_dl -->
		<a href="{report_broken_dl.U_BROKEN_DOWNLOAD}" class="gensmall">{report_broken_dl.L_BROKEN_DOWNLOAD}</a>
		<!-- END report_broken_dl -->
		<!-- BEGIN dl_broken_cur -->
		<b class="text_red">{dl_broken_cur.L_REPORT}</b>
		<!-- END dl_broken_cur -->
		<br />
		<!-- BEGIN dl_broken_mod -->
		<a href="{dl_broken_mod.U_REPORT}" class="gensmall"><b>{dl_broken_mod.L_REPORT}</b></a>
		<!-- END dl_broken_mod -->
		<!-- BEGIN fav_block -->
		<br /><a href="{U_FAVORITE}" class="gensmall">{L_FAVORITE}</a>
		<!-- END fav_block -->
		</span>
		</div>
		<!-- BEGIN downloads -->
		<span class="gensmall">{downloads.ADD_USER}<br />{downloads.CHANGE_USER}<br />{downloads.LAST_TIME}</span>
		<!-- END downloads -->
	</td>
</tr>
</table>

<br />

<!-- BEGIN rating -->
<br />
<form action="{S_ACTION}" method="post" name="rating">
{IMG_THL}{IMG_THC}<span class="forumlink">{L_RATING_TITLE}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr><td class="row3" nowrap="nowrap" colspan="2" align="center"><span class="topictitle">{L_RATING_TITLE}</span></td></tr>
<tr><td class="{ROW_CLASS2} row-center"><span class="gen">{rating.RATING}</span></td></tr>
<tr>
	<td class="cat" align="center">
		<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption"/>&nbsp;&nbsp;&nbsp;
		<input type="submit" name="cancel" value="{L_CANCEL}" class="liteoption"/>{S_HIDDEN_FIELDS_RATE}
	</td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</form>
<!-- END rating -->

<!-- BEGIN comment_block -->
<!-- BEGIN complete -->
<form action="{comment_block.complete.S_COMMENT_ACTION}" method="post" name="comments">
{IMG_THL}{IMG_THC}<span class="forumlink">&nbsp;</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<!-- BEGIN comments_on -->
<tr><th nowrap="nowrap" colspan="2">{comment_block.complete.L_LAST_COMMENT}</th></tr>
<!-- END comments_on -->
<!-- BEGIN comment_row -->
<tr>
	<td class="{comment_block.complete.comment_row.ROW_CLASS}" width="25%" valign="top"><span class="genmed">{comment_block.complete.comment_row.POSTER}</span><br /><span class="gensmall">{comment_block.complete.comment_row.POST_TIME}</span></td>
	<td class="{comment_block.complete.comment_row.ROW_CLASS}" width="75%" valign="top"><span class="postdetails">{comment_block.complete.comment_row.MESSAGE}{comment_block.complete.comment_row.EDITED_BY}</span></td>
</tr>
<!-- END comment_row -->
<!-- BEGIN view_comments -->
<tr><td class="cat" align="center" colspan="2"><input type="submit" name="show" value="{comment_block.complete.view_comments.L_SHOW_COMMENTS}" class="liteoption" /></td></tr>
<!-- END view_comments -->
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
{comment_block.complete.S_HIDDEN_FIELDS}
</form>
<!-- END complete -->

<!-- BEGIN post -->
<form action="{comment_block.post.S_COMMENT_ACTION}" method="post" name="comments_post">
<table cellpadding="3" cellspacing="1" border="0" align="center">
<tr><td align="center"><input type="submit" name="post" value="{comment_block.post.L_POST_COMMENT}" class="mainoption"/>{comment_block.post.S_HIDDEN_FIELDS}</td></tr>
</table>
</form>
<!-- END post -->
<!-- END comment_block -->

<!-- BEGIN mod_list -->
<br />
{IMG_THL}{IMG_THC}<span class="forumlink">&nbsp;</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<!-- END mod_list -->
<!-- BEGIN modlisttest -->
<tr>
	<td width="20%" class="{ROW_CLASS2}" valign="top" align="right" nowrap="nowrap"><span class="gen">{L_DL_MOD_TEST}:</span></td>
	<td width="80%" class="{ROW_CLASS1}"><span class="gen"><b>{modlisttest.MOD_TEST}</b></span></td>
</tr>
<!-- END modlisttest -->
<!-- BEGIN modrequire -->
<tr>
	<td width="20%" class="{ROW_CLASS2}" valign="top" align="right" nowrap="nowrap"><span class="gen">{L_DL_MOD_REQUIRE}:</span></td>
	<td width="80%" class="{ROW_CLASS1}"><span class="gen">{modrequire.MOD_REQUIRE}</span></td>
</tr>
<!-- END modrequire -->
<!-- BEGIN modlistdesc -->
<tr>
	<td width="20%" class="{ROW_CLASS2}" valign="top" align="right" nowrap="nowrap"><span class="gen">{L_DL_MOD_DESC}:</span></td>
	<td width="80%" class="{ROW_CLASS1}"><span class="gen">{modlistdesc.MOD_DESC}</span></td>
</tr>
<!-- END modlistdesc -->
<!-- BEGIN modwarning -->
<tr>
	<td width="20%" class="{ROW_CLASS2}" valign="top" align="right" nowrap="nowrap"><span class="gen">{L_DL_MOD_WARNING}:</span></td>
	<td width="80%" class="{ROW_CLASS1}"><span class="gen"><span class="text_red">{modwarning.MOD_WARNING}</span></span></td>
</tr>
<!-- END modwarning -->
<!-- BEGIN modtodo -->
<tr>
	<td width="20%" class="{ROW_CLASS2}" valign="top" align="right" nowrap="nowrap"><span class="gen">{L_DL_MOD_TODO}:</span></td>
	<td width="80%" class="{ROW_CLASS1}"><span class="gen">{modtodo.MOD_TODO}</span></td>
</tr>
<!-- END modtodo -->
<!-- BEGIN mod_list -->
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<!-- END mod_list -->

<br />