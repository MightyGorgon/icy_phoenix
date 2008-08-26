<!-- INCLUDE breadcrumbs.tpl -->

<form name="_calendar_scheduler" method="post" action="{ACTION}">
{IMG_THL}{IMG_THC}<span class="forumlink">{L_CALENDAR_SCHEDULER}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td colspan="3">
		<table class="forumline" width="100%" cellspacing="0">
		<tr>
			<!-- BEGIN hour -->
			<td class="rowcal{hour.CLASS} row-center" align="center" valign="middle"><span class="genmed"><a href="{hour.U_HOUR}" class="genmed">{hour.HOUR}</a></span></td>
			<!-- END hour -->
		</tr>
		</table>
		<br />
	</td>
</tr>
<tr>
	<td valign="top">
		<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
		<tr><td colspan="10" class="row-header"><span>{L_CALENDAR}</span></td></tr>
		<tr>
			<!-- BEGIN header_cell -->
			<th width="14%">{header_cell.L_DAY}</th>
			<!-- END header_cell -->
		</tr>
		<tr>
			<td class="cat" colspan="7" align="center">
				<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td><b>&nbsp;<a href="{U_PREC}" class="gen">&laquo;</a>&nbsp;</b></td>
					<td width="100%" align="center">{S_MONTH}{S_YEAR}</td>
					<td><b>&nbsp;<a href="{U_NEXT}" class="gen">&raquo;</a>&nbsp;</b></td>
				</tr>
				</table>
			</td>
		</tr>
		<!-- BEGIN row -->
		<tr>
			<!-- BEGIN cell -->
			<td class="{row.cell.CLASS} row-center" height="25"><span class="gen">{row.cell.DAY}</span></td>
			<!-- END cell -->
		</tr>
		<!-- END row -->
		<tr>
			<td class="cat" colspan="7" align="center"><img src="{IMG_CALENDAR}" border="0" align="absbottom" hspace="5" alt="{L_CALENDAR}" title="{L_CALENDAR}" /><span class="genmed"><a href="{U_CALENDAR}" alt="{L_CALENDAR}" title="{L_CALENDAR}">{L_CALENDAR}</a></span></td>
		</tr>
		</table>
	</td>
	<td><span class="gensmall">&nbsp;</span></td>
	<td valign="top" width="100%">{TOPIC_LIST_SCHEDULER}</td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
{S_HIDDEN_FIELDS}
</form>

<br />
<div style="float:right;"><span class="pagination"><b>{PAGINATION}</b></span></div>

<br />
<div style="float:right;">{JUMPBOX}</div>

