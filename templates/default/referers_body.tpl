<!-- INCLUDE overall_header.tpl -->

<form method="post" action="{S_MODE_ACTION}" name="refersrow_values">
{IMG_THL}{IMG_THC}<span class="forumlink">{L_REFERERS}</span>{IMG_THR}<table class="forumlinenb">
<tr>
	<th class="tdnw">#</th>
	<th class="tdnw">{L_HOST}</th>
	<th class="tdnw">{L_URL}</th>
	<th class="tdnw">{L_REFERER_T_URL}</th>
	<th class="tdnw">{L_HITS}</th>
	<th class="tdnw">{L_FIRST}</th>
	<th class="tdnw">{L_LAST}</th>
</tr>
<!-- BEGIN refersrow -->
<tr>
	<td class="row1 row-center"><span class="gensmall">{refersrow.ID}</span></td>
	<td class="row1" ><span class="gensmall">{refersrow.HOST}</span></td>
	<td class="row1" ><span class="gensmall">{refersrow.URL}</span></td>
	<td class="row1" ><span class="gensmall">{refersrow.T_URL}</span></td>
	<td class="row1 row-center"><span class="gensmall">{refersrow.HITS}</span></td>
	<td class="row1 row-center"><span class="gensmall">{refersrow.FIRST}</span></td>
	<td class="row1 row-center"><span class="gensmall">{refersrow.LAST}</span></td>
</tr>
<!-- END refersrow -->
<tr><td class="cat" colspan="9">&nbsp;</td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</form>

<table>
<tr>
	<td><span class="pagination">{PAGINATION}</span></td>
	<td class="tdalignr tdnw">
		<form method="post" action="{S_MODE_ACTION}">
			<span class="genmed">{L_SELECT_SORT_METHOD}:&nbsp;{S_MODE_SELECT}&nbsp;&nbsp;{L_ORDER}:&nbsp;{S_ORDER_SELECT}&nbsp;&nbsp;{L_REFERER_GROUP_BY}:&nbsp;{S_GROUP_BY_SELECT}&nbsp;&nbsp;<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" /></span>
		</form>
	</td>
</tr>
<tr>
	<td><span class="gensmall">&nbsp;{PAGE_NUMBER}</span></td>
	<td class="tdalignr">{JUMPBOX}</td>
</tr>
</table>

<!-- INCLUDE overall_footer.tpl -->