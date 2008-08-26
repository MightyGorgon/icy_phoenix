<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><td class="row-header" colspan="5"><span>{L_MODULE_NAME} {WEEK}</span></td></tr>
<tr>
	<th><strong>{L_RANK}</strong></th>
	<th width="10%"><strong>{L_USERNAME}</strong></th>
	<th width="10%"><strong>{L_POSTS}</strong></th>
	<th width="10%"><strong>{L_PERCENTAGE}</strong></th>
	<th width="50%"><strong>{L_GRAPH}</strong></th>
</tr>
<!-- BEGIN top_posters_week -->
<tr>
	<td class="row1 row-center" width="10%"><span class="gen">{top_posters_week.RANK}</span></td>
	<td class="row1" width="10%"><span class="gen"><a href="{top_posters_week.URL}">{top_posters_week.USERNAME}</a></span></td>
	<td class="row1 row-center" width="10%"><span class="gen">{top_posters_week.POSTS}</span></td>
	<td class="row1 row-center" width="10%"><span class="gen">{top_posters_week.PERCENTAGE}%</span></td>
	<td class="row1" width="50%">
		<table cellspacing="0" cellpadding="0" border="0" align="left">
		<tr>
			<td colspan="3" width="1%" nowrap="nowrap"><img src="{LEFT_GRAPH_IMAGE}" width="4" height="12" alt="" /><img src="{GRAPH_IMAGE}" width="{top_posters_week.BAR}%" height="12" alt="{top_posters_week.BAR}%" /><img src="{RIGHT_GRAPH_IMAGE}" width="4" height="12" alt="" /></td>
		</tr>
		</table>
	</td>
</tr>
<!-- END top_posters_week -->
</table>
