{IMG_THL}{IMG_THC}<a href="{U_CALENDAR}" class="forumlink">{L_CALENDAR}</a>{IMG_THR}<table class="forumlinenb">
<!-- IF NO_EVENTS -->
<tr><td><br /><br /><span class="genmed">{L_NO_EVENTS}</span><br /><br /></td></tr>
<!-- ELSE -->
<tr>
	<th>{L_EVENT_START_DATE}</th>
	<th>{L_EVENT_START_TIME}</th>
	<!-- IF SHOW_END_TIME -->
	<th>{L_EVENT_END_DATE}</th>
	<th>{L_EVENT_END_TIME}</th>
	<!-- ENDIF -->
	<th>{L_EVENT_TITLE}</th>
	<th>{L_EVENT_FORUM}</th>
</tr>
<!-- BEGIN event_row -->
<tr>
	<td class="{event_row.ROW_CLASS} row-center">{event_row.EVENT_START_DATE}</td>
	<td class="{event_row.ROW_CLASS} row-center">{event_row.EVENT_START_TIME}</td>
	<!-- IF SHOW_END_TIME -->
	<td class="{event_row.ROW_CLASS} row-center">{event_row.EVENT_END_DATE}</td>
	<td class="{event_row.ROW_CLASS} row-center">{event_row.EVENT_END_TIME}</td>
	<!-- ENDIF -->
	<td class="{event_row.ROW_CLASS}"><span class="topiclink"><a href="{event_row.U_EVENT_TITLE}">{event_row.L_EVENT_TITLE}</a></span></td>
	<td class="{event_row.ROW_CLASS}"><span class="topiclink"><a href="{event_row.U_EVENT_FORUM}">{event_row.L_EVENT_FORUM}</a></span></td>
</tr>
<!-- END event_row -->
<!-- ENDIF -->
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
