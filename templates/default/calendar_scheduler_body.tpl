<!-- INCLUDE overall_header.tpl -->

<form name="f_calendar_scheduler" method="post" action="{ACTION}">
{IMG_THL}{IMG_THC}<span class="forumlink">{L_CALENDAR_SCHEDULER}</span>{IMG_THR}<table class="forumlinenb">
<tr>
	<td colspan="3">
		<table class="forumline">
		<tr>
			<!-- BEGIN hour -->
			<td class="rowcal{hour.CLASS} row-center tvalignm"><span class="genmed"><a href="{hour.U_HOUR}" class="genmed">{hour.HOUR}</a></span></td>
			<!-- END hour -->
		</tr>
		</table>
		<br />
	</td>
</tr>
<tr>
	<td>
		<table class="forumline">
		<tr><td colspan="10" class="row-header"><span>{L_CALENDAR}</span></td></tr>
		<tr>
			<!-- BEGIN header_cell -->
			<th width="14%">{header_cell.L_DAY}</th>
			<!-- END header_cell -->
		</tr>
		<tr>
			<td class="cat tdalignc" colspan="7">
				<table>
				<tr>
					<td><b>&nbsp;<a href="{U_PREC}" class="gen">&laquo;</a>&nbsp;</b></td>
					<td class="tw100pct tdalignc">{S_MONTH}&nbsp;{S_YEAR}</td>
					<td><b>&nbsp;<a href="{U_NEXT}" class="gen">&raquo;</a>&nbsp;</b></td>
				</tr>
				</table>
			</td>
		</tr>
		<!-- BEGIN row -->
		<tr>
			<!-- BEGIN cell -->
			<td class="{row.cell.CLASS} row-center"><span class="gen">{row.cell.DAY}</span></td>
			<!-- END cell -->
		</tr>
		<!-- END row -->
		<tr><td class="cat tdalignc" colspan="7"><img src="{IMG_CALENDAR}" border="0" alt="{L_CALENDAR}" title="{L_CALENDAR}" />&nbsp;<span class="genmed" style="vertical-align:top;"><b><a href="{U_CALENDAR}">{L_CALENDAR}</a></b></span></td></tr>
		</table>
		<table class="forumline">
		<tr><td class="row-header"><span>{L_BIRTHDAYS}</span></td></tr>
		<tr><td class="row-post"><div class="post-text"><span class="gensmall">{TODAY_BIRTHDAYS_LIST}</span></div></td></tr>
		<tr><td class="cat"><img src="{IMG_CALENDAR}" border="0" alt="{L_CALENDAR}" title="{L_CALENDAR}" />&nbsp;<span class="genmed" style="vertical-align:top;"><b><a href="{U_CALENDAR}">{L_CALENDAR}</a></b></span></td>
		</table>
	</td>
	<td><span class="gensmall">&nbsp;</span></td>
	<td class="tw100pct">{TOPIC_LIST_SCHEDULER}</td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
{S_HIDDEN_FIELDS}
</form>

<br />
<div style="float: right;"><span class="pagination">{PAGINATION}</span></div>

<br />
<div style="float: right;">{JUMPBOX}</div>

<!-- INCLUDE overall_footer.tpl -->