<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><td class="row-header" colspan="4" height="28"> <span class="cattitle">{MODULE_NAME}</span></td></tr>
<tr>
	<th><strong>{L_RANK}</strong></th>
	<th><strong>{L_TOPIC}</strong></th>
	<th><strong>{L_RATE}</strong></th>
	<th><strong>{L_GRAPH}</strong></th>
</tr>
<!-- BEGIN mosttopics -->
<tr>
	<td class="row1 row-center" width="5%"><span class="gen">{mosttopics.RANK}</span></td>
	<td class="row1"><span class="gen"><a href="{mosttopics.URL}">{mosttopics.TITLE}</a></span></td>
	<td class="row1 row-center" width="20%"><span class="gen">{mosttopics.RATE}%</span></td>
	<td class="row1" width="50%">
		<table cellspacing="0" cellpadding="0" border="0" align="left">
		<tr>
			<td colspan="3" width="1%" nowrap="nowrap"><img src="{LEFT_GRAPH_IMAGE}" width="4" height="12" alt="" /><img src="{GRAPH_IMAGE}" width="{mosttopics.RATE}}%%" height="12" alt="{traffic.BAR}%%" /><img src="{RIGHT_GRAPH_IMAGE}" width="4" height="12" alt="" /></td>
		</tr>
		</table>
	</td>
</tr>
<!-- END mosttopics -->
</table>