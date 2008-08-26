{NAVBAR}
<h1>{L_LOG_TITLE}</h1>
<p>{L_LOG_EXPLAIN}</p>

<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th colspan="{NUMTIMEFILTERS}">{L_TIME}</th>
	<th colspan="{NUMACTIONFILTERS}">{L_TYPE}</th>
</tr>
<tr>
	<!-- BEGIN timefilter -->
	<td class="{timefilter.ROW_CLASS}">
		<!-- BEGIN switch_linkpage_on -->
		<a href="{timefilter.LINK}">{timefilter.NAME}</a>
		<!-- END switch_linkpage_on -->
		<!-- BEGIN switch_linkpage_off -->
		<b>{timefilter.NAME}</b>
		<!-- END switch_linkpage_off -->
	</td>
	<!-- END timefilter -->
	<!-- BEGIN actionfilter -->
	<td class="{actionfilter.ROW_CLASS}">
		<!-- BEGIN switch_linkpage_on -->
		<a href="{actionfilter.LINK}">{actionfilter.NAME}</a>
		<!-- END switch_linkpage_on -->
		<!-- BEGIN switch_linkpage_off -->
		<b>{actionfilter.NAME}</b>
		<!-- END switch_linkpage_off -->
	</td>
	<!-- END actionfilter -->
</tr>
</table>

<table border="0">
<tr>
<!-- BEGIN countfilter -->
<td>
	<!-- BEGIN switch_linkpage_on -->
	<a href="{countfilter.LINK}">{countfilter.NAME}</a>
	<!-- END switch_linkpage_on -->
	<!-- BEGIN switch_linkpage_off -->
	<b>{countfilter.NAME}</b>
	<!-- END switch_linkpage_off -->
</td>
<!-- END countfilter -->
<td>{L_PER_PAGE}</td>
</tr>
</table>

<table width="100%" border="0"><tr><td><span class="pagination">{PAGINATION}</span></td></tr></table>

<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th>{L_TIME}</th>
	<th>{L_TYPE}</th>
	<th>{L_ACTION}</th>
	<th>{L_LOG}</th>
</tr>
<!-- BEGIN logrow -->
<tr>
	<td class="{logrow.ROW_CLASS}">{logrow.TIME}</td>
	<td class="{logrow.ROW_CLASS}">{logrow.TYPE}</td>
	<td class="{logrow.ROW_CLASS}">{logrow.ACTION}</td>
	<td class="{logrow.ROW_CLASS}">{logrow.TEXT}</td>
</tr>
<!-- END logrow -->
</table>

<table width="100%" border="0"><tr><td><span class="pagination">{PAGINATION}</span></td></tr></table>
<br />

<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<!-- BEGIN actionfilter -->
	<td class="{actionfilter.ROW_CLASS}"><a href="{actionfilter.DELETECOMMAND}"><b>{actionfilter.DELETE}</b></a></td>
	<!-- END actionfilter -->
</tr>
</table>
<br />
