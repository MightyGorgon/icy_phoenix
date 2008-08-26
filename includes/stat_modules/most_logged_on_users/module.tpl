<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><td class="row-header" colspan="5"><span class="cattitle">{MODULE_NAME}</span></td></tr>
<tr>
	<th><strong>{L_RANK}</strong></th>
	<th width="10%"><strong>{L_USERNAME}</strong></th>
	<th width="10%"><strong>{L_TIME2}</strong></th>
	<th width="10%"><strong>{L_PERCENTAGE}</strong></th>
	<th width="50%"><strong>{L_GRAPH}</strong></th>
</tr>
<!-- BEGIN users -->
<tr>
	<td class="row1 row-center" width="10%"><span class="gen">{users.RANK}</span></td>
	<td class="row1" width="10%"><span class="gen"><a href="{users.URL}">{users.USERNAME}</a></span></td>
	<td class="row1 row-center" width="10%"><span class="gen">{users.TIME}</span></td>
	<td class="row1 row-center" width="10%"><span class="gen">{users.PERCENTAGE}%</span></td>
	<td class="row1" width="50%">
		<table cellspacing="0" cellpadding="0" border="0" align="left">
		<tr>
			<td colspan="3" width="1%" nowrap="nowrap"><img src="{LEFT_GRAPH_IMAGE}" width="4" height="12" alt="" /><img src="{GRAPH_IMAGE}" width="{users.BAR}%" height="12" alt="{users.BAR}%" /><img src="{RIGHT_GRAPH_IMAGE}" width="4" height="12" alt="" /></td>
		</tr>
		</table>
	</td>
</tr>
<!-- END users -->
</table>
