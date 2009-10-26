<!-- INCLUDE overall_header.tpl -->

{IMG_THL}{IMG_THC}<span class="forumlink">{L_WHO_VIEWED}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th width="30">#</th>
	<th>{L_USERNAME}</th>
	<th>{L_CONTACTS}</th>
	<th>{L_FROM}</th>
	<th>{L_JOINED}</th>
	<th width="50">{L_VIEWS_COUNT}</th>
	<th>{L_LAST_VIEWED}</th>
</tr>
<!-- BEGIN memberrow -->
<tr>
	<td class="row1 row-center">{memberrow.ROW_NUMBER}</td>
	<td class="row1" style="padding-left: 2px;">{memberrow.USERNAME}</td>
	<td class="row1 row-center-small">{memberrow.ONLINE_STATUS_IMG}&nbsp;{memberrow.PM_IMG}&nbsp;{memberrow.EMAIL_IMG}&nbsp;{memberrow.WWW_IMG}</td>
	<td class="row1 row-center-small">{memberrow.FROM}</td>
	<td class="row1 row-center-small">{memberrow.JOINED}</td>
	<td class="row1 row-center">{memberrow.VIEWS_COUNT}</td>
	<td class="row1 row-center-small">{memberrow.LAST_VIEWED}</td>
</tr>
<!-- END memberrow -->
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}

<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td align="left"><span class="pagination">{PAGINATION}</span></td>
	<td align="right" valign="top" nowrap="nowrap">
		<form method="post" action="{S_MODE_ACTION}">
			<span class="genmed">
				{L_SELECT_SORT_METHOD}:&nbsp;{S_MODE_SELECT}&nbsp;&nbsp;{L_ORDER}&nbsp;{S_ORDER_SELECT}&nbsp;&nbsp;
				<input type="submit" name="submit" value="{L_SUBMIT}" class="liteoption jumpbox" />
			</span>
		</form>
	</td>
</tr>
<tr>
	<td align="left"><span class="gen">{PAGE_NUMBER}</span></td>
	<td valign="top" align="right">{JUMPBOX}</td>
</tr>
</table>

<!-- INCLUDE overall_footer.tpl -->