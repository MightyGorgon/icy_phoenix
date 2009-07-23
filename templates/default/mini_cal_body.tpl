<!-- BEGIN switch_mini_cal_add_events -->
<form name="mini_cal" id ="mini_cal" action="{U_MINI_CAL_ADD_EVENT}" method="post">
<!-- END switch_mini_cal_add_events -->
{IMG_THL}{IMG_THC}<a href="{U_MINI_CAL_CALENDAR}" class="forumlink">{L_MINI_CAL_CALENDAR}</a>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
	<tr><th colspan="7">{U_PREV_MONTH}&nbsp;&nbsp;{L_MINI_CAL_MONTH}&nbsp;&nbsp;{U_NEXT_MONTH}</th></tr>
	<tr class="row3 row-center">
		<td width="15%"><span class="gensmall">{L_MINI_CAL_MON}</span></td>
		<td width="14%"><span class="gensmall">{L_MINI_CAL_TUE}</span></td>
		<td width="14%"><span class="gensmall">{L_MINI_CAL_WED}</span></td>
		<td width="14%"><span class="gensmall">{L_MINI_CAL_THU}</span></td>
		<td width="14%"><span class="gensmall">{L_MINI_CAL_FRI}</span></td>
		<td width="14%"><span class="gensmall">{L_MINI_CAL_SAT}</span></td>
		<td width="15%"><span class="gensmall">{L_MINI_CAL_SUN}</span></td>
	</tr>
	<!-- BEGIN mini_cal_row -->
	<tr>
		<!-- BEGIN mini_cal_days -->
		<td class="rowcal row-center"><span class="gensmall">{mini_cal_row.mini_cal_days.MINI_CAL_DAY}</span></td>
		<!-- END mini_cal_days -->
	</tr>
	<!-- END mini_cal_row -->

	<tr><td colspan="7" class="row1" align="left"><span class="gensmall">{L_WHOSBIRTHDAY_TODAY}</span></td></tr>
	<tr><td colspan="7" class="row1" align="left"><span class="gensmall">{L_WHOSBIRTHDAY_WEEK}</span></td></tr>

	<!-- BEGIN switch_mini_cal_events -->
	<tr><th colspan="7" height="28"><span class="cattitle">{L_MINI_CAL_EVENTS}</span></th></tr>
	<!-- END switch_mini_cal_events -->
	<!-- BEGIN mini_cal_events -->
	<tr><td colspan="7" class="row1"><span class="gensmall">{mini_cal_events.MINI_CAL_EVENT_DATE} - <a href="{mini_cal_events.U_MINI_CAL_EVENT}" class="gensmall">{mini_cal_events.S_MINI_CAL_EVENT}</a></span></td></tr>
	<!-- END mini_cal_events -->
	<!-- BEGIN mini_cal_no_events -->
	<tr><td colspan="7" class="row1"><span class="genMed">{L_MINI_CAL_NO_EVENTS}</span></td></tr>
	<!-- END mini_cal_no_events -->
	<!-- BEGIN switch_mini_cal_add_events -->
	<tr>
		<td colspan="7" class="row1" height="28" align="center">
			<span class="genmed">
				{S_MINI_CAL_EVENTS_FORUMS_LIST} <input type="submit" value="{L_MINI_CAL_ADD_EVENT}" class="liteoption" />
				<input type="Hidden" name="mode" id="mode" value="newtopic" />
			</span>
		</td>
	</tr>
	<!-- END switch_mini_cal_add_events -->
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<!-- BEGIN switch_mini_cal_add_events -->
</form>
<!-- END switch_mini_cal_add_events -->