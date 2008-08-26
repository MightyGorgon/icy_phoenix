<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><td class="row-header" colspan="5"><span class="cattitle">{MODULE_NAME}</span></td></tr>
<tr>
	<th><strong>{L_RANK}</strong></th>
	<th width="10%"><strong>{L_WORD}</strong></th>
	<th width="10%"><strong>{L_COUNT}</strong></th>
	<th width="10%"><strong>{L_PERCENTAGE}</strong></th>
	<th width="50%"><strong>{L_GRAPH}</strong></th>
</tr>
<!-- BEGIN words -->
<tr>
	<td class="row1 row-center" width="10%"><span class="gen">{words.RANK}</span></td>
	<td class="row1" width="10%"><span class="gen">{words.WORD}</span></td>
	<td class="row1 row-center" width="10%"><span class="gen">{words.COUNT}</span></td>
	<td class="row1 row-center" width="10%"><span class="gen">{words.PERCENTAGE}%</span></td>
	<td class="row1" width="50%">
	<table cellspacing="0" cellpadding="0" border="0" align="left">
	<tr>
		<td colspan="3" width="1%" nowrap="nowrap"><img src="{LEFT_GRAPH_IMAGE}" width="4" height="12" alt="" /><img src="{GRAPH_IMAGE}" width="{words.BAR}%" height="12" alt="{topsmilies.BAR}%" /><img src="{RIGHT_GRAPH_IMAGE}" width="4" height="12" alt="" /></td>
	</tr>
	</table>
	</td>
</tr>
<!-- END words -->
</table>
