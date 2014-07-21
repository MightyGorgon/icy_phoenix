{IMG_THL}{IMG_THC}<span class="forumlink">{L_TITLE}</span>{IMG_THR}<table class="forumlinenb">
<tr>
	<th class="tw20pct">{L_RANK}</th>
	<th class="tw20pct">{L_AGE}</th>
	<th class="tw10pct">{L_HOWMANY}</th>
	<th width="50%">{L_GRAPH}</th>
</tr>
<!-- BEGIN stats_row -->
<tr class="{stats_row.CLASS}h">
	<td class="{stats_row.CLASS} row-center" style="background: none;"><span class="gen">{stats_row.RANK}</span></td>
	<td class="{stats_row.CLASS} row-center" style="background: none;"><span class="gen">{stats_row.YEAR}</span></td>
	<td class="{stats_row.CLASS} row-center" style="background: none;"><span class="gen">{stats_row.HOWMANY}</span></td>
	<td class="{stats_row.CLASS}" width="50%" style="background: none;"><table><tr><td class="tw1pct tdnw"><img src="<!-- IF stats_row.PERCENTAGE > 66 -->{G_LEFT_GRAPH_IMAGE}<!-- ELSEIF stats_row.PERCENTAGE > 33 -->{B_LEFT_GRAPH_IMAGE}<!-- ELSE -->{R_LEFT_GRAPH_IMAGE}<!-- ENDIF -->" height="13" alt="" /><img src="<!-- IF stats_row.PERCENTAGE > 66 -->{G_GRAPH_IMAGE}<!-- ELSEIF stats_row.PERCENTAGE > 33 -->{B_GRAPH_IMAGE}<!-- ELSE -->{R_GRAPH_IMAGE}<!-- ENDIF -->" width="{stats_row.BAR}%" height="13" alt="{stats_row.PERCENTAGE}%" /><img src="<!-- IF stats_row.PERCENTAGE > 66 -->{G_RIGHT_GRAPH_IMAGE}<!-- ELSEIF stats_row.PERCENTAGE > 33 -->{B_RIGHT_GRAPH_IMAGE}<!-- ELSE -->{R_RIGHT_GRAPH_IMAGE}<!-- ENDIF -->" height="13" alt="" /></td></tr></table></td>
</tr>
<!-- END stats_row -->
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
