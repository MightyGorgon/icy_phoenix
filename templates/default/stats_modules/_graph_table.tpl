<table>
<tr>
	<td class="tw1pct tdnw"><img src="<!-- IF stats_row.PERCENTAGE > 66 -->{G_LEFT_GRAPH_IMAGE}<!-- ELSEIF stats_row.PERCENTAGE > 33 -->{B_LEFT_GRAPH_IMAGE}<!-- ELSE -->{R_LEFT_GRAPH_IMAGE}<!-- ENDIF -->" height="13" alt="" /><img src="<!-- IF stats_row.PERCENTAGE > 66 -->{G_GRAPH_IMAGE}<!-- ELSEIF stats_row.PERCENTAGE > 33 -->{B_GRAPH_IMAGE}<!-- ELSE -->{R_GRAPH_IMAGE}<!-- ENDIF -->" width="{stats_row.BAR}%" height="13" alt="{stats_row.PERCENTAGE}%" /><img src="<!-- IF stats_row.PERCENTAGE > 66 -->{G_RIGHT_GRAPH_IMAGE}<!-- ELSEIF stats_row.PERCENTAGE > 33 -->{B_RIGHT_GRAPH_IMAGE}<!-- ELSE -->{R_RIGHT_GRAPH_IMAGE}<!-- ENDIF -->" height="13" alt="" /></td>
</tr>
</table>
