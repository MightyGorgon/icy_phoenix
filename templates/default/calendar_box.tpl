<!-- The javascript presents here comes from MyCalendar 2.2.6 from MojavLinux -->
<script type="text/javascript">
// <![CDATA[
var agt = navigator.userAgent.toLowerCase();
var originalFirstChild;

function createTitle(which, string, x, y)
{
	// record the original first child (protection when deleting)
	if (typeof(originalFirstChild) == 'undefined')
	{
		originalFirstChild = document.body.firstChild;
	}

	x = document.all ? (event.clientX + document.body.scrollLeft) : x;
	y = document.all ? (event.clientY + document.body.scrollTop) : y;
	element = document.createElement('div');
	element.style.position = 'absolute';
	element.style.zIndex = 1000;
	element.style.visibility = 'hidden';
	excessWidth = 0;
	if (document.all)
	{
		excessWidth = 50;
	}
	excessHeight = 20;
	string = string;
	element.innerHTML = '<div class="bodyline"><table width="300" cellspacing="0" cellpadding="0" border="0"><tr><td><table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td><span class="gen">' + string + '<\/span><\/td><\/tr><\/table><\/td><\/tr><\/table><\/div>';
	renderedElement = document.body.insertBefore(element, document.body.firstChild);
	renderedWidth = renderedElement.offsetWidth;
	renderedHeight = renderedElement.offsetHeight;

	// fix overflowing off the right side of the screen
	overFlowX = x + renderedWidth + excessWidth - document.body.offsetWidth;
	x = overFlowX > 0 ? x - overFlowX : x;

	// fix overflowing off the bottom of the screen
	overFlowY = y + renderedHeight + excessHeight - window.innerHeight - window.pageYOffset;
	y = overFlowY > 0 ? y - overFlowY : y;

	renderedElement.style.top = (y + 15) + 'px';
	renderedElement.style.left = (x + 15) + 'px';

	// windows versions of mozilla are like too fast here...we have to slow it down
	if (agt.indexOf('gecko') != -1 && agt.indexOf('win') != -1)
	{
		setTimeout("renderedElement.style.visibility = 'visible'", 1);
	}
	else
	{
		renderedElement.style.visibility = 'visible';
	}
}

function destroyTitle()
{
	// make sure we don't delete the actual page contents (javascript can get out of alignment)
	if (document.body.firstChild != originalFirstChild)
	{
		document.body.removeChild(document.body.firstChild);
	}
}
// ]]>
</script>

<!-- BEGIN _calendar_box -->
<div id="calendarbox_h" style="display:none;">
{IMG_THL}{IMG_THC}<img class="max-min-right" style="{SHOW_HIDE_PADDING}" src="{IMG_MAXIMISE}" onclick="ShowHide('calendarbox','calendarbox_h','calendarbox');" alt="{L_SHOW}" /><a href="{U_CALENDAR}" class="forumlink">{L_CALENDAR}</a>{IMG_THR_ALT}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr><td>&nbsp;</td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</div>
<div id="calendarbox">
<script type="text/javascript">
<!--
tmp = 'calendarbox';
if(GetCookie(tmp) == '2')
{
	ShowHide('calendarbox', 'calendarbox_h', 'calendarbox');
}
//-->
</script>
{IMG_THL}{IMG_THC}<img class="max-min-right" style="{SHOW_HIDE_PADDING}" src="{IMG_MINIMISE}" onclick="ShowHide('calendarbox','calendarbox_h','calendarbox');" alt="{L_HIDE}" /><a href="{U_CALENDAR}" class="forumlink">{L_CALENDAR}</a>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<!-- BEGIN switch_full_month -->
<tr>
	<!-- BEGIN _cell -->
	<th width="{_calendar_box.switch_full_month._cell.WIDTH}%" nowrap="nowrap">{_calendar_box.switch_full_month._cell.L_DAY}</th>
	<!-- END _cell -->
</tr>
<!-- END switch_full_month -->
<!-- BEGIN switch_full_month_no -->
<tr><td colspan="2">
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><th align="center" colspan="{_calendar_box.SPAN_ALL}" width="100%">{_calendar_box.L_CALENDAR}</th></tr>
<!-- END switch_full_month_no -->
<!-- BEGIN _row -->
<tr>
	<!-- BEGIN _cell -->
	<!-- BEGIN switch_filled_no -->
	<td class="row1" colspan="{_calendar_box._row._cell.SPAN}" width="{_calendar_box._row._cell.WIDTH}%">&nbsp;</td>
	<!-- END switch_filled_no -->
	<!-- BEGIN switch_filled -->
	<td class="row1" valign="top" colspan="{_calendar_box._row._cell.SPAN}" width="{_calendar_box._row._cell.WIDTH}%">
		<table cellspacing="0" cellpadding="2" width="100%" style="height:94px;vertical-align:top;">
		<tr>
			<td class="rowcal3" valign="top" align="center" height="4" nowrap="nowrap"><span class="genmed"><a href="{_calendar_box._row._cell.U_DATE}" title="{_calendar_box._row._cell.DATE}" class="genmed">{_calendar_box._row._cell.DATE}</a></span></td>
		</tr>
		<tr valign="top">
			<td class="rowcal2" nowrap="nowrap">
				<table width="100%" cellspacing="0" cellpadding="0">
				<!-- BEGIN _event -->
				<!-- BEGIN switch_event -->
				<tr>
					<td class="rowcal2" nowrap="nowrap" valign="top"><span class="genmed">{_calendar_box._row._cell.switch_filled._event.EVENT_TYPE}<a href="{_calendar_box._row._cell.switch_filled._event.U_EVENT}" onmouseover="createTitle(this, '{_calendar_box._row._cell.switch_filled._event.EVENT_MESSAGE}', event.pageX, event.pageY);" onmouseout="destroyTitle();" class="{_calendar_box._row._cell.switch_filled._event.EVENT_CLASS}">{_calendar_box._row._cell.switch_filled._event.EVENT_TITLE}</a></span></td>
					<!-- BEGIN _more -->
					<td class="rowcal2" align="right"><span class="genmed"><a href="#" onclick="hdr_toggle('calendar_display_extend_{_calendar_box._row._cell.switch_filled.EVENT_DATE}','calendar_open_close_{_calendar_box._row._cell.switch_filled.EVENT_DATE}', '{DOWN_ARROW2}', '{UP_ARROW2}'); return false;" class="gensmall" style="text-decoration: none;">...<img src="{_calendar_box._row._cell.switch_filled.TOGGLE_ICON}" id="calendar_open_close_{_calendar_box._row._cell.switch_filled.EVENT_DATE}" hspace="2" /></a></span></td>
					<!-- END _more -->
					<!-- BEGIN _more_no -->
					<td></td>
					<!-- END _more_no -->
				</tr>
				<!-- END switch_event -->
				<!-- BEGIN switch_event_no -->
				<tr>
					<td class="rowcal2"><span class="genmed">&nbsp;</span></td>
					<!-- BEGIN _more -->
					<td class="row1" align="right"><span class="genmed"><a href="#" onclick="hdr_toggle('calendar_display_extend_{_calendar_box._row._cell.switch_filled.EVENT_DATE}','calendar_open_close_{_calendar_box._row._cell.switch_filled.EVENT_DATE}', '{DOWN_ARROW2}', '{UP_ARROW2}'); return false;" class="gensmall" style="text-decoration: none;">...<img src="{_calendar_box._row._cell.switch_filled.TOGGLE_ICON2}" id="calendar_open_close_{_calendar_box._row._cell.switch_filled.EVENT_DATE}" hspace="2" /></a></span></td>
					<!-- END _more -->
					<!-- BEGIN _more_no -->
					<td></td>
					<!-- END _more_no -->
				</tr>
				<!-- END switch_event_no -->
				<!-- BEGIN _more_header -->
				<tbody id="calendar_display_extend_{_calendar_box._row._cell.switch_filled.EVENT_DATE}" style="display:{_calendar_box._row._cell.switch_filled.TOGGLE_STATUS}">
				<!-- END _more_header -->
				<!-- BEGIN _more_footer -->
				</tbody>
				<!-- END _more_footer -->
				<!-- END _event -->
				</table>
			</td>
		</tr>
		</table>
	</td>
	<!-- END switch_filled -->
	<!-- END _cell -->
</tr>
<!-- END _row -->
<!-- BEGIN switch_full_month -->
<tr>
	<td align="center" colspan="{_calendar_box.SPAN_ALL}" width="100%">
		<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
		<tr>
			<td>&nbsp;</td>
			<td><b>&nbsp;<a href="{_calendar_box.U_PREC}" class="gen">&laquo;</a>&nbsp;</b></td>
			<td width="100%" align="center">{_calendar_box.S_MONTH}&nbsp;{_calendar_box.S_YEAR}&nbsp;{_calendar_box.S_FORUM_LIST}&nbsp;<input type="submit" value="{_calendar_box.L_GO}" class="liteoption" /></td>
			<td><b>&nbsp;<a href="{_calendar_box.U_NEXT}" class="gen">&raquo;</a>&nbsp;</b></td>
			<td>&nbsp;</td>
		</tr>
		</table>
	</td>
</tr>
<!-- END switch_full_month -->
<!-- BEGIN switch_full_month_no -->
</table></td></tr>
<!-- END switch_full_month_no -->
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</div>
<!-- END _calendar_box -->