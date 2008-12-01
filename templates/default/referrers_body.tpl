<form method="post" action="{S_MODE_ACTION}" name="refersrow_values">
{IMG_THL}{IMG_THC}<span class="forumlink">{L_REFERRERS}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th nowrap="nowrap">#</th>
	<th nowrap="nowrap">{L_HOST}</th>
	<th nowrap="nowrap">{L_URL}</th>
	<th nowrap="nowrap">{L_HITS}</th>
	<th nowrap="nowrap">{L_FIRST}</th>
	<th nowrap="nowrap">{L_LAST}</th>
</tr>
<!-- BEGIN refersrow -->
<tr>
	<td class="row1 row-center"><span class="gen">{refersrow.ID}</span></td>
	<td class="row1" ><span class="gen">{refersrow.HOST}</span></td>
	<td class="row1" ><span class="gen">{refersrow.URL}</span></td>
	<td class="row1 row-center"><span class="gen">{refersrow.HITS}</span></td>
	<td class="row1 row-center"><span class="gensmall">{refersrow.FIRST}</span></td>
	<td class="row1 row-center"><span class="gensmall">{refersrow.LAST}</span></td>
</tr>
<!-- END refersrow -->
<tr><td class="cat" colspan="8">&nbsp;</td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</form>
<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td align="left"><span class="pagination">{PAGINATION}</span></td>
	<td align="right" valign="top" nowrap="nowrap">
		<form method="post" action="{S_MODE_ACTION}">
			<span class="genmed">{L_SELECT_SORT_METHOD}:&nbsp;{S_MODE_SELECT}&nbsp;&nbsp;{L_ORDER}&nbsp;{S_ORDER_SELECT}&nbsp;&nbsp;<input type="submit" name="submit" value="{L_SUBMIT}" class="liteoption jumpbox" /></span>
		</form>
	</td>
</tr>
<tr>
	<td align="left"><span class="gensmall">&nbsp;{PAGE_NUMBER}</span></td>
	<td valign="top" align="right">{JUMPBOX}</td>
</tr>
</table>