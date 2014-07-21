<!-- INCLUDE overall_header.tpl -->

<script type="text/javascript">
// <![CDATA[
function select_switch(status)
{
	$("[type='checkbox']").each(function(index, element) {
		element.checked = status;
	});
}
// ]]>
</script>

<form method="post" name="attach_list" action="{S_MODE_ACTION}">
<table>
<tr>
	<td class="tdnw">&nbsp;</td>
	<td class="tdalignr tdnw">
		<span class="genmed">{L_SELECT_SORT_METHOD}:&nbsp;{S_MODE_SELECT}&nbsp;&nbsp;{L_ORDER}&nbsp;{S_ORDER_SELECT}&nbsp;&nbsp;
			<input type="submit" name="submit" value="{L_SUBMIT}" class="liteoption" />
		</span>
	</td>
</tr>
</table>

{IMG_THL}{IMG_THC}<span class="forumlink">{L_UACP} :: {USERNAME}</span>{IMG_THR}<table class="forumlinenb">
<tr>
	<th>#</th>
	<th>{L_FILENAME}</th>
	<th>{L_FILECOMMENT}</th>
	<th>{L_EXTENSION}</th>
	<th>{L_SIZE}</th>
	<th>{L_DOWNLOADS}</th>
	<th>{L_POST_TIME}</th>
	<th>{L_POSTED_IN_TOPIC}</th>
	<th>{L_DELETE}</th>
</tr>
<!-- BEGIN attachrow -->
<tr>
	<td class="{attachrow.ROW_CLASS} row-center"><span class="gen">&nbsp;{attachrow.ROW_NUMBER}&nbsp;</span></td>
	<td class="{attachrow.ROW_CLASS}"><span class="gen"><a href="{attachrow.U_VIEW_ATTACHMENT}" target="_blank">{attachrow.FILENAME}</a></span></td>
	<td class="{attachrow.ROW_CLASS}"><span class="gen">{attachrow.COMMENT}</span></td>
	<td class="{attachrow.ROW_CLASS} row-center"><span class="gen">{attachrow.EXTENSION}</span></td>
	<td class="{attachrow.ROW_CLASS} row-center tvalignm"><span class="gen"><b>{attachrow.SIZE}</b></span></td>
	<td class="{attachrow.ROW_CLASS} row-center tvalignm"><span class="gen"><b>{attachrow.DOWNLOAD_COUNT}</b></span></td>
	<td class="{attachrow.ROW_CLASS} row-center tvalignm"><span class="gensmall">{attachrow.POST_TIME}</span></td>
	<td class="{attachrow.ROW_CLASS} row-center tvalignm"><span class="gen">{attachrow.POST_TITLE}</span></td>
	<td class="{attachrow.ROW_CLASS} row-center">{attachrow.S_DELETE_BOX}</td>
	{attachrow.S_HIDDEN}
</tr>
<!-- END attachrow -->
<tr><td class="catBottom" colspan="9"><input type="submit" name="delete" value="{L_DELETE_MARKED}" class="liteoption" /></td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}

{S_USER_HIDDEN}

<table>
<tr>
	<td class="tdalignr tdnw">
		<span class="gensmall"><a href="javascript:select_switch(true);" class="gensmall">{L_MARK_ALL}</a>&nbsp;&bull;&nbsp;<a href="javascript:select_switch(false);" class="gensmall">{L_UNMARK_ALL}</a></span>
	</td>
</tr>
</table>
<table>
<tr>
	<td><span class="gensmall">{PAGE_NUMBER}</span></td>
	<td class="tdalignr"><span class="pagination">{PAGINATION}&nbsp;</span></td>
</tr>
</table>
</form>

<!-- INCLUDE overall_footer.tpl -->