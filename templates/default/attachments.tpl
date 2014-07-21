<!-- INCLUDE overall_header.tpl -->

<form method="post" action="{S_MODE_ACTION}">
<table>
<tr>
	<td class="tdalignr tdnw">
		<span class="genmed">
		{L_SELECT_SORT_METHOD}:&nbsp;{S_MODE_SELECT}&nbsp;&nbsp;{L_ORDER}&nbsp;{S_ORDER_SELECT}&nbsp;&nbsp;{L_FORUM}&nbsp;{S_FORUM_SELECT}&nbsp;&nbsp;
		<input type="submit" name="submit" value="{L_SUBMIT}" class="liteoption" />
		</span>
	</td>
</tr>
</table>

{ERROR_BOX}
{IMG_THL}{IMG_THC}<span class="forumlink">{L_DOWNLOADS}</span>{IMG_THR}<table class="forumlinenb">
<tr>
	<th class="tdnw">#</th>
	<th class="tdnw">{L_FILENAME}</th>
	<th class="tdnw">{L_FILECOMMENT}</th>
	<th class="tdnw">{L_SIZE}</th>
	<th class="tdnw">{L_DOWNLOADS}</th>
	<th class="tdnw">{L_POST_TIME}</th>
	<th class="tdnw">{L_POSTED_IN_TOPIC}</th>
</tr>
<!-- BEGIN attachrow -->
<tr>
	<td class="{attachrow.ROW_CLASS} row-center"><span class="gen">&nbsp;{attachrow.ROW_NUMBER}&nbsp;</span></td>
	<td class="{attachrow.ROW_CLASS}"><span class="gen">{attachrow.VIEW_ATTACHMENT}</span></td>
	<td class="{attachrow.ROW_CLASS}"><span class="gen">{attachrow.COMMENT}</span></td>
	<td class="{attachrow.ROW_CLASS} row-center tvalignm"><span class="gen"><b>{attachrow.SIZE}</b></span></td>
	<td class="{attachrow.ROW_CLASS} row-center tvalignm"><span class="gen"><b>{attachrow.DOWNLOAD_COUNT}</b></span></td>
	<td class="{attachrow.ROW_CLASS} row-center tvalignm"><span class="gensmall">{attachrow.POST_TIME}</span></td>
	<td class="{attachrow.ROW_CLASS} tvalignm"><span class="gen">{attachrow.POST_TITLE}</span></td>
</tr>
<!-- END attachrow -->
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}

<br />

<table>
<tr>
	<td><span class="gensmall">{PAGE_NUMBER}</span></td>
	<td class="tdalignr"><span class="pagination">{PAGINATION}</span></td>
</tr>
</table>

</form>

<!-- INCLUDE overall_footer.tpl -->