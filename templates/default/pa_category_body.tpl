<!-- INCLUDE pa_header.tpl -->
<!-- INCLUDE pa_links.tpl -->

<!-- IF CAT_PARENT -->
{IMG_THL}{IMG_THC}<span class="forumlink">{DOWNLOAD}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th colspan="2">{L_CATEGORY}</th>
	<th width="10%">{L_LAST_FILE}</th>
	<th width="8%">{L_FILES}</th>
</tr>
<!-- BEGIN no_cat_parent -->
	<!-- IF no_cat_parent.IS_HIGHER_CAT -->
	<tr><td class="forum-buttons2" align="left" colspan="4"><img src="{CAT_BLOCK_IMG}" style="vertical-align: middle;" />&nbsp;<span><a href="{no_cat_parent.U_CAT}">{no_cat_parent.CAT_NAME}</a></span></td></tr>
	<!-- ELSE -->
	<tr>
		<td class="row1 row-center" width="30" style="padding-right:5px;"><a href="{no_cat_parent.U_CAT}" class="cattitle"><img src="{no_cat_parent.CAT_IMAGE}" alt="{no_cat_parent.CAT_NEW_FILE}" /></a></td>
		<td class="row1h{no_cat_parent.XS_NEW} row-forum" onclick="window.location.href='{no_cat_parent.U_CAT}'">
		<span class="forumlink"><a class="forumlink{no_cat_parent.XS_NEW}" href="{no_cat_parent.U_CAT}">{no_cat_parent.CAT_NAME}</a></span><br />
		<span class="genmed">{no_cat_parent.CAT_DESC}</span>
		<!-- IF no_cat_parent.IS_HIGHER_CAT --><br /><span class="gensmall"><b>{no_cat_parent.L_SUB_CAT}</b></span><span class="forumlink{no_cat_parent.XS_NEW}">{no_cat_parent.SUB_CAT}</span><!-- ENDIF -->
		</td>
		<td class="row2 row-center" nowrap="nowrap"><span class="genmed">{no_cat_parent.LAST_FILE}</span></td>
		<td class="row2 row-center"><span class="genmed">{no_cat_parent.FILECAT}</span></td>
	</tr>
	<!-- ENDIF -->
<!-- END no_cat_parent -->

<tr><td class="cat" colspan="4">&nbsp;</td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<br />
<!-- ENDIF -->

<!-- IF FILELIST -->
<form action="{S_ACTION_SORT}" method="post">
{IMG_THL}{IMG_THC}<span class="forumlink">{DOWNLOAD}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th colspan="2">{L_FILE}</th>
	<th width="15%">{L_DATE}</th>
	<th width="10%">{L_DOWNLOADS}</th>
	<th width="10%">{L_RATING}</th>
	<th width="3%">&nbsp;</th>
</tr>

<!-- BEGIN file_rows -->
<tr>
	<td class="row1 row-center" width="30" style="padding-right: 5px;"><a href="{file_rows.U_FILE}" class="topiclink"><img src="{file_rows.PIN_IMAGE}" alt="" /></a></td>
	<td class="row1h{file_rows.XS_NEW} row-forum" onclick="window.location.href='{file_rows.U_FILE}'" width="75%"><span class="forumlink"><a href="{file_rows.U_FILE}" class="forumlink{file_rows.XS_NEW}">{file_rows.FILE_NAME}</a></span>&nbsp;
	<!-- IF file_rows.IS_NEW_FILE -->
	<!-- <img src="{file_rows.FILE_NEW_IMAGE}" alt="{L_NEW_FILE}" /> -->
	<!-- ENDIF -->
	<br /><span class="genmed">{file_rows.FILE_DESC}</span></td>
	<td class="row2 row-center" nowrap="nowrap"><span class="postdetails">{file_rows.DATE}</td>
	<td class="row2 row-center"><span class="postdetails">{file_rows.FILE_DLS}</td>
	<td class="row2 row-center" nowrap="nowrap"><span class="postdetails"><img src="images/rates/rate_{file_rows.RATING}.png" alt="" /></td>
	<td class="row2 row-center">
	<!-- IF file_rows.HAS_SCREENSHOTS -->
		<!-- IF file_rows.SS_AS_LINK -->
		<a href="{file_rows.FILE_SCREENSHOT}" class="topiclink" target="_blank"><img src="{file_rows.FILE_SCREENSHOT_URL}" alt="{L_SCREENSHOTS}" /></a>
		<!-- ELSE -->
		<a href="javascript:mpFoto('{file_rows.FILE_SCREENSHOT}')" class="topiclink"><img src="{file_rows.FILE_SCREENSHOT_URL}" alt="{L_SCREENSHOTS}" /></a>
		<!-- ENDIF -->
	<!-- ELSE -->
	&nbsp;
	<!-- ENDIF -->
	</td>
</tr>
<!-- END file_rows -->
<tr>
	<td class="cat" colspan="6">
		<input type="hidden" name="action" value="category" />
		<input type="hidden" name="cat_id" value="{ID}" />
		<input type="hidden" name="start" value="{START}" />
		<span class="genmed">
			{L_SELECT_SORT_METHOD}:&nbsp;
			<select name="sort_method">
				<option {SORT_NAME} value="file_name">{L_NAME}</option>
				<option {SORT_TIME} value="file_time">{L_DATE}</option>
				<option {SORT_RATING} value="file_rating">{L_RATING}</option>
				<option {SORT_DOWNLOADS} value="file_dls">{L_DOWNLOADS}</option>
				<option {SORT_UPDATE_TIME} value="file_update_time">{L_UPDATE_TIME}</option>
			</select>
			&nbsp;{L_ORDER}:&nbsp;
			<select name="sort_order">
				<option {SORT_ASC} value="ASC">{L_ASC}</option>
				<option {SORT_DESC} value="DESC">{L_DESC}</option>
			</select>
			&nbsp;<input type="submit" name="submit" value="{L_SORT}" class="liteoption" />
			</span>
	</td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</form>
<br />
<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><td align="right" nowrap="nowrap"><span class="pagination">{PAGINATION}</span></td></tr>
<tr><td align="right" nowrap="nowrap"><span class="gensmall">{PAGE_NUMBER}</span></td></tr>
</table>
<!-- ENDIF -->

<!-- IF NO_FILE -->
{IMG_THL}{IMG_THC}<span class="forumlink">{L_NO_FILES}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="3">
<tr><td class="row1g" align="center" height="30"><span class="gen">{L_NO_FILES_CAT}</span></td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<!-- ENDIF -->
<!-- INCLUDE pa_footer.tpl -->