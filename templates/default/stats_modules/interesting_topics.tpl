{IMG_THL}{IMG_THC}<span class="forumlink">{MODULE_NAME}</span>{IMG_THR}<table class="forumlinenb">
<tr>
	<th>{L_RANK}</th>
	<th>{L_TOPIC}</th>
	<th>{L_RATE}</th>
	<th>{L_GRAPH}</th>
</tr>
<!-- BEGIN stats_row -->
<tr class="{stats_row.CLASS}h">
	<td class="{stats_row.CLASS} row-center" style="background: none;" width="5%"><span class="gen">{stats_row.RANK}</span></td>
	<td class="{stats_row.CLASS}" style="background: none;"><span class="gen"><a href="{stats_row.URL}">{stats_row.TITLE}</a></span></td>
	<td class="{stats_row.CLASS} row-center" style="background: none;" width="20%"><span class="gen">{stats_row.RATE}%</span></td>
	<td class="{stats_row.CLASS}" style="background: none;" width="50%"><table><tr><td class="tw1pct tdnw"><img src="<!-- IF stats_row.PERCENTAGE > 66 -->{G_LEFT_GRAPH_IMAGE}<!-- ELSEIF stats_row.PERCENTAGE > 33 -->{B_LEFT_GRAPH_IMAGE}<!-- ELSE -->{R_LEFT_GRAPH_IMAGE}<!-- ENDIF -->" height="13" alt="" /><img src="<!-- IF stats_row.PERCENTAGE > 66 -->{G_GRAPH_IMAGE}<!-- ELSEIF stats_row.PERCENTAGE > 33 -->{B_GRAPH_IMAGE}<!-- ELSE -->{R_GRAPH_IMAGE}<!-- ENDIF -->" width="{stats_row.BAR}%" height="13" alt="{stats_row.PERCENTAGE}%" /><img src="<!-- IF stats_row.PERCENTAGE > 66 -->{G_RIGHT_GRAPH_IMAGE}<!-- ELSEIF stats_row.PERCENTAGE > 33 -->{B_RIGHT_GRAPH_IMAGE}<!-- ELSE -->{R_RIGHT_GRAPH_IMAGE}<!-- ENDIF -->" height="13" alt="" /></td></tr></table></td>
</tr>
<!-- END stats_row -->
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}