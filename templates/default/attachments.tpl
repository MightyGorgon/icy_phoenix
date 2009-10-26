<!-- INCLUDE overall_header.tpl -->

<form method="post" action="{S_MODE_ACTION}">
<table class="empty-table" width="100%" align="center" cellspacing="0">
<tr>
	<td align="right" nowrap="nowrap">
		<span class="genmed">
		{L_SELECT_SORT_METHOD}:&nbsp;{S_MODE_SELECT}&nbsp;&nbsp;{L_ORDER}&nbsp;{S_ORDER_SELECT}&nbsp;&nbsp;{L_FORUM}&nbsp;{S_FORUM_SELECT}&nbsp;&nbsp;
		<input type="submit" name="submit" value="{L_SUBMIT}" class="liteoption" />
		</span>
	</td>
</tr>
</table>

{ERROR_BOX}
{IMG_THL}{IMG_THC}<span class="forumlink">{L_DOWNLOADS}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th height="25" nowrap="nowrap">#</th>
	<th nowrap="nowrap">{L_FILENAME}</th>
	<th nowrap="nowrap">{L_FILECOMMENT}</th>
	<th nowrap="nowrap">{L_SIZE}</th>
	<th nowrap="nowrap">{L_DOWNLOADS}</th>
	<th nowrap="nowrap">{L_POST_TIME}</th>
	<th nowrap="nowrap">{L_POSTED_IN_TOPIC}</th>
</tr>
<!-- BEGIN attachrow -->
<tr>
	<td class="row1 row-center" align="center"><span class="gen">&nbsp;{attachrow.ROW_NUMBER}&nbsp;</span></td>
	<td class="row1"><span class="gen">{attachrow.VIEW_ATTACHMENT}</span></td>
	<td class="row1"><span class="gen">{attachrow.COMMENT}</span></td>
	<td class="row1 row-center" align="center" valign="middle"><span class="gen"><b>{attachrow.SIZE}</b></span></td>
	<td class="row1 row-center" align="center" valign="middle"><span class="gen"><b>{attachrow.DOWNLOAD_COUNT}</b></span></td>
	<td class="row1 row-center" valign="middle"><span class="gensmall">{attachrow.POST_TIME}</span></td>
	<td class="row1" valign="middle"><span class="gen">{attachrow.POST_TITLE}</span></td>
</tr>
<!-- END attachrow -->
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}

<br />

<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td><span class="gensmall">{PAGE_NUMBER}</span></td>
	<td align="right"><span class="pagination">{PAGINATION}</span></td>
</tr>
</table>

</form>

<!-- INCLUDE overall_footer.tpl -->