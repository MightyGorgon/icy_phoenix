{IMG_THL}{IMG_THC}<a href="{U_CALENDAR}" class="forumlink">{L_CALENDAR}</a>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<!-- IF NO_EVENTS -->
<tr><td><br /><br /><span class="genmed">{L_NO_EVENTS}</span><br /><br /></td></tr>
<!-- ELSE -->
<tr>
	<th>{L_EVENT_START_DATE}</th>
	<th>{L_EVENT_START_TIME}</th>
	<!-- IF SHOW_END_TIME -->
	<th>{L_EVENT_END_TIME}</th>
	<th>{L_EVENT_END_TIME}</th>
	<!-- ENDIF -->
	<th>{L_EVENT_TITLE}</th>
	<th>{L_EVENT_FORUM}</th>
</tr>
<!-- BEGIN event_row -->
<tr>
	<td class="{event_row.CLASS} row-center">{event_row.EVENT_START_DATE}</td>
	<td class="{event_row.CLASS} row-center">{event_row.EVENT_START_TIME}</td>
	<!-- IF SHOW_END_TIME -->
	<td class="{event_row.CLASS} row-center">{event_row.EVENT_END_TIME}</td>
	<td class="{event_row.CLASS} row-center">{event_row.EVENT_END_TIME}</td>
	<!-- ENDIF -->
	<td class="{event_row.CLASS}">{event_row.EVENT_TITLE}</td>
	<td class="{event_row.CLASS}">{event_row.EVENT_FORUM}</td>
</tr>
<!-- END event_row -->
<!-- ENDIF -->
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
