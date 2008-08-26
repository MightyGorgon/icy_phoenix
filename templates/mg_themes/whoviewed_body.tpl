<!-- INCLUDE breadcrumbs_i.tpl -->

{IMG_THL}{IMG_THC}<span class="forumlink">{L_WHO_VIEWED}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th width="30">#</th>
	<th>{L_ONLINE_STATUS}</th>
	<th>{L_USERNAME}</th>
	<th width="50">{L_POSTS}</th>
	<th>{L_JOINED}</th>
	<th>{L_LOGON}</th>
	<th>{L_FROM}</th>
	<th width="50">&nbsp;</th>
	<th width="50">{L_EMAIL}</th>
	<th width="50">{L_WEBSITE}</th>
</tr>
<!-- BEGIN memberrow -->
<tr>
	<td class="row1 row-center">{memberrow.ROW_NUMBER}</td>
	<td class="row1 row-center-small">{memberrow.ONLINE_STATUS_IMG}</td>
	<td class="row1 row-center">{memberrow.USERNAME}</td>
	<td class="row1 row-center">{memberrow.POSTS}</td>
	<td class="row1 row-center-small">{memberrow.JOINED}</td>
	<td class="row1 row-center-small">{memberrow.LAST_LOGON}</td>
	<td class="row1 row-center-small">{memberrow.FROM}</td>
	<td class="row1 post-buttons-single row-center">{memberrow.PM_IMG}</td>
	<td class="row1 post-buttons-single">{memberrow.EMAIL_IMG}</td>
	<td class="row1 post-buttons-single">{memberrow.WWW_IMG}</td>
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