
<h1>{L_CONTROL_PANEL_TITLE}</h1>

<p>{L_CONTROL_PANEL_EXPLAIN}</p>

<form method="post" action="{S_MODE_ACTION}">
<table width="100%" cellspacing="2" cellpadding="2" border="0" align="center">
<tr>
	<!-- <td align="left" nowrap="nowrap"><span class="gen"><a class="gen" href="{U_BACK}" />Back</a></span></td> -->
	<td align="right" nowrap="nowrap">
		<span class="genmed">
			{L_VIEW}:&nbsp;{S_VIEW_SELECT}&nbsp;&nbsp;{L_SELECT_SORT_METHOD}:&nbsp;{S_MODE_SELECT}&nbsp;&nbsp;{L_ORDER}&nbsp;{S_ORDER_SELECT}&nbsp;&nbsp;
			<input type="submit" name="submit" value="{L_SUBMIT}" class="liteoption" />
		</span>
	</td>
</tr>
</table>
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th>#</th>
	<th>{L_USERNAME}</th>
	<th>{L_ATTACHMENTS}</th>
	<th>{L_TOTAL_SIZE}</th>
</tr>
<!-- BEGIN memberrow -->
<tr>
	<td class="{memberrow.ROW_CLASS} row-center"><span class="gen">&nbsp;{memberrow.ROW_NUMBER}&nbsp;</span></td>
	<td class="{memberrow.ROW_CLASS} row-center"><span class="gen"><a href="{memberrow.U_VIEW_MEMBER}" class="gen">{memberrow.USERNAME}</a></span></td>
	<td class="{memberrow.ROW_CLASS} row-center" valign="middle">&nbsp;<b>{memberrow.TOTAL_ATTACHMENTS}</b>&nbsp;</td>
	<td class="{memberrow.ROW_CLASS} row-center">&nbsp;<b>{memberrow.TOTAL_SIZE}</b>&nbsp;</td>
</tr>
<!-- END memberrow -->
<tr><td class="cat" colspan="4">&nbsp;</td></tr>
</table>
<table width="100%" cellspacing="2" border="0" align="center" cellpadding="2"><tr><td align="right" valign="top">&nbsp;</td></tr></table>

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td><span class="gensmall">{PAGE_NUMBER}</span></td>
	<td align="right"><span class="pagination">{PAGINATION}</span></td>
</tr>
</table>
</form>

<br />
<div align="center"><span class="copyright">{ATTACH_VERSION}</span></div>
