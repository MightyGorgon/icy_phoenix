<!-- INCLUDE overall_header.tpl -->

<form method="post" action="{S_MODE_ACTION}">
<table class="empty-table" width="100%" align="center" cellspacing="0">
<tr>
	<td align="right" nowrap="nowrap">
	<span class="genmed">
		{L_SELECT_SORT_METHOD}:&nbsp;{S_MODE_SELECT}&nbsp;&nbsp;{L_ORDER}&nbsp;{S_ORDER_SELECT}&nbsp;&nbsp;
		{S_HIDDEN_FIELDS}
		<input type="submit" name="submit" value="{L_SUBMIT}" class="liteoption" />
	</span>
	</td>
</tr>
</table>

{IMG_THL}{IMG_THC}<span class="forumlink">{L_DOWNLOADS}{NAV_SEP}{L_FILENAME}&nbsp;[&nbsp;{L_COMMENT}&nbsp;]</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th width="4%" nowrap="nowrap">#</th>
	<th width="48%" nowrap="nowrap">{L_DATE}</th>
	<th width="48%" nowrap="nowrap">{L_USER}</th>
</tr>
<!-- BEGIN row -->
<tr>
	<td class="row1 row-center">{row.NUMBER}</td>
	<td class="row1 row-center">{row.DATE}</td>
	<td class="row1 row-center">{row.USER}</td>
</tr>
<!-- END row -->
<tr><td class="cat" colspan="3">&nbsp;</td></tr>
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