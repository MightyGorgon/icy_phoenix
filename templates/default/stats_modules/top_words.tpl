{IMG_THL}{IMG_THC}<span class="forumlink">{MODULE_NAME}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th>{L_RANK}</th>
	<th width="10%">{L_WORD}</th>
	<th width="10%">{L_COUNT}</th>
	<th width="10%">{L_PERCENTAGE}</th>
	<th width="50%">{L_GRAPH}</th>
</tr>
<!-- BEGIN stats_row -->
<tr class="{stats_row.CLASS}h">
	<td class="{stats_row.CLASS} row-center" style="background: none;" width="10%"><span class="gen">{stats_row.RANK}</span></td>
	<td class="{stats_row.CLASS}" style="background: none;" width="10%"><span class="gen">{stats_row.WORD}</span></td>
	<td class="{stats_row.CLASS} row-center" style="background: none;" width="10%"><span class="gen">{stats_row.COUNT}</span></td>
	<td class="{stats_row.CLASS} row-center" style="background: none;" width="10%"><span class="gen">{stats_row.PERCENTAGE}%</span></td>
	<td class="{stats_row.CLASS}" style="background: none;" width="50%"><table cellspacing="0" cellpadding="0" border="0" align="left"><tr><td width="1%" nowrap="nowrap"><img src="<!-- IF stats_row.PERCENTAGE > 66 -->{G_LEFT_GRAPH_IMAGE}<!-- ELSEIF stats_row.PERCENTAGE > 33 -->{B_LEFT_GRAPH_IMAGE}<!-- ELSE -->{R_LEFT_GRAPH_IMAGE}<!-- ENDIF -->" height="13" alt="" /><img src="<!-- IF stats_row.PERCENTAGE > 66 -->{G_GRAPH_IMAGE}<!-- ELSEIF stats_row.PERCENTAGE > 33 -->{B_GRAPH_IMAGE}<!-- ELSE -->{R_GRAPH_IMAGE}<!-- ENDIF -->" width="{stats_row.BAR}%" height="13" alt="{stats_row.PERCENTAGE}%" /><img src="<!-- IF stats_row.PERCENTAGE > 66 -->{G_RIGHT_GRAPH_IMAGE}<!-- ELSEIF stats_row.PERCENTAGE > 33 -->{B_RIGHT_GRAPH_IMAGE}<!-- ELSE -->{R_RIGHT_GRAPH_IMAGE}<!-- ENDIF -->" height="13" alt="" /></td></tr></table></td>
</tr>
<!-- END stats_row -->
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
