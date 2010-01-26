<!-- INCLUDE pa_header.tpl -->
<!-- INCLUDE pa_links.tpl -->

<!-- IF FILELIST -->
<form action="{S_VIEWALL_ACTION}" method="post">
{IMG_THL}{IMG_THC}<span class="forumlink">{L_VIEWALL}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th class="thTop" width="35%" colspan="2">{L_FILE}</th>
	<th class="thTop" nowrap="nowrap">&nbsp;{L_CATEGORY}&nbsp;</th>
	<th class="thTop" width="15%">{L_DATE}</th>
	<th class="thTop" width="10%">{L_DOWNLOADS}</th>
	<th class="thTop" width="10%">{L_RATING}</th>
	<th class="thCornerR" width="3%">&nbsp;</th>
</tr>

<!-- BEGIN file_rows -->
<tr>
	<td class="row1 row-center" width="30"><a href="{file_rows.U_FILE}" class="topiclink"><img src="{file_rows.PIN_IMAGE}" alt="" /></a></td>
	<td class="row1h{file_rows.XS_NEW} row-forum" onclick="window.location.href='{file_rows.U_FILE}'" width="35%"><span class="forumlink"><a href="{file_rows.U_FILE}" class="forumlink{file_rows.XS_NEW}">{file_rows.FILE_NAME}</a></span>&nbsp;
	<!-- IF file_rows.IS_NEW_FILE -->
	<!-- <img src="{file_rows.FILE_NEW_IMAGE}" alt="{L_NEW_FILE}" /> -->
	<!-- ENDIF -->
	<br /><span class="genmed">{file_rows.FILE_DESC}</span></td>
	<td class="row1h{file_rows.XS_NEW} row-forum" onclick="window.location.href='{file_rows.U_CAT}'"><span class="forumlink"><a href="{file_rows.U_CAT}" class="forumlink{file_rows.XS_NEW}">{file_rows.CAT_NAME}</a></span>&nbsp;</td>

	<td class="row2 row-center" nowrap="nowrap"><span class="postdetails">{file_rows.DATE}</td>
	<td class="row2 row-center"><span class="postdetails">{file_rows.FILE_DLS}</td>
	<td class="row2 row-center" nowrap="nowrap"><span class="postdetails"><img src="images/rates/rate_{file_rows.RATING}.png" alt="" /></td>
	<td class="row2 row-center" align="center" valign="middle">
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
	<td class="cat" align="center" colspan="7">
		<input type="hidden" name="action" value="viewall" />
		<input type="hidden" name="start" value="{START}" />
		<span class="genmed">
		{L_SELECT_SORT_METHOD}:&nbsp;
		<select name="sort_method">
			<option {SORT_NAME} value='file_name'>{L_NAME}</option>
			<option {SORT_TIME} value='file_time'>{L_DATE}</option>
			<option {SORT_RATING} value='file_rating'>{L_RATING}</option>
			<option {SORT_DOWNLOADS} value='file_dls'>{L_DOWNLOADS}</option>
			<option {SORT_UPDATE_TIME} value='file_update_time'>{L_UPDATE_TIME}</option>
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
<br />
<table width="100%" cellspacing="2" cellpadding="2" border="0">
	<tr><td align="right" nowrap="nowrap"><span class="pagination">{PAGINATION}</span></td></tr>
	<tr><td align="right" nowrap="nowrap"><span class="gensmall">{PAGE_NUMBER}</span></td></tr>
</table>
</form>
<!-- ENDIF -->

<!-- IF NO_FILE -->
<table class="forumline" width="100%" cellspacing="0">
<tr><th>{L_NO_FILES}</th></tr>
<tr><td class="row1 row-center" height="30"><span class="genmed">{L_NO_FILES_CAT}</span></td></tr>
</table>
<!-- ENDIF -->
<!-- INCLUDE pa_footer.tpl -->