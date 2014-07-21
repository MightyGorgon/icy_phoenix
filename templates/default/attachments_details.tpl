<!-- INCLUDE overall_header.tpl -->

<form method="post" action="{S_MODE_ACTION}">
<table>
<tr>
	<td class="tdalignr tdnw">
	<span class="genmed">
		{L_SELECT_SORT_METHOD}:&nbsp;{S_MODE_SELECT}&nbsp;&nbsp;{L_ORDER}&nbsp;{S_ORDER_SELECT}&nbsp;&nbsp;
		{S_HIDDEN_FIELDS}
		<input type="submit" name="submit" value="{L_SUBMIT}" class="liteoption" />
	</span>
	</td>
</tr>
</table>

{IMG_THL}{IMG_THC}<span class="forumlink">{L_DOWNLOADS}{NAV_SEP}{L_FILENAME}&nbsp;[&nbsp;{L_COMMENT}&nbsp;]</span>{IMG_THR}<table class="forumlinenb">
<tr>
	<th class="tw4pct tdnw">#</th>
	<th class="tw48pct tdnw">{L_DATE}</th>
	<th class="tw48pct tdnw">{L_USER}</th>
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

<table>
<tr>
	<td><span class="gensmall">{PAGE_NUMBER}</span></td>
	<td class="tdalignr"><span class="pagination">{PAGINATION}</span></td>
</tr>
</table>

</form>

<!-- INCLUDE overall_footer.tpl -->