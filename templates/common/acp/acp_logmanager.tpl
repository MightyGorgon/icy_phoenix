<h1>{L_HEADLINE}</h1>
<p>{L_SUBHEADLINE}</p>

<br />

<!-- BEGIN infobox -->
<div align="center">
<table class="forumline" width="80%" cellspacing="0" cellpadding="0" border="0">
<tr><td align="center" style="background-color:#DBFFCF;"><b>{infobox.L_MESSAGE_TEXT}</b></td></tr>
</table>
</div>

<br /><br />
<!-- END infobox -->

<!-- BEGIN overview -->
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><th colspan="2">{overview.L_OVERVIEW}</th></tr>
<tr>
	<td class="row2 row-center" width="20%" style="vertical-align:top;"><img src="{IMG_ICON}" title="{overview.L_OVERVIEW}" alt="{overview.L_OVERVIEW}" border="0"></td>
	<td class="row1 row-center" width="80%">
		<table border="0" class="forumline" cellspacing="1" cellpadding="3" width="60%"><tr><td class="row3 row-center">{overview.L_COUNTER_VALUE}</td></tr></table>
		<br /><br />
		<form action="{overview.S_DELETE_FORM}" method="post">
		<table border="0" class="forumline" cellspacing="1" cellpadding="3" width="100%">
			<tr><th colspan="3">{overview.L_LOG_OVERVIEW}</th><tr>
				<th>{overview.L_LOGHEAD_1}</th>
				<th>{overview.L_LOGHEAD_2}</th>
				<th>{overview.L_LOGHEAD_3}</th>
			</tr>
			<tr>
				<td class="row2"><b>{overview.L_LOGNAME_2}</b></td>
				<td class="row2">{overview.S_LOGVALUE_2}</td>
				<td class="row2 row-center"><b>[ <a href="{overview.S_VIEW_2}">{overview.L_VIEW}</a> | <a href="{overview.S_DELETE_2}">{overview.L_DELETE}</a> ]</b></td>
			</tr>
			<tr>
				<td class="row1"><b>{overview.L_LOGNAME_3}</b></td>
				<td class="row1">{overview.S_LOGVALUE_3}</td>
				<td class="row1 row-center"><b>[ <a href="{overview.S_VIEW_3}">{overview.L_VIEW}</a> | <a href="{overview.S_DELETE_3}">{overview.L_DELETE}</a> ]</b></td>
			</tr>
			<tr>
				<td class="row2"><b>{overview.L_LOGNAME_4}</b></td>
				<td class="row2">{overview.S_LOGVALUE_4}</td>
				<td class="row2 row-center"><b>[ <a href="{overview.S_VIEW_4}">{overview.L_VIEW}</a> | <a href="{overview.S_DELETE_4}">{overview.L_DELETE}</a> ]</b></td>
			</tr>
			<tr>
				<td class="row1"><b>{overview.L_LOGNAME_5}</b></td>
				<td class="row1">{overview.S_LOGVALUE_5}</td>
				<td class="row1 row-center"><b>[ <a href="{overview.S_VIEW_5}">{overview.L_VIEW}</a> | <a href="{overview.S_DELETE_5}">{overview.L_DELETE}</a> ]</b></td>
			</tr>
			<tr>
				<td class="row1"><b>{overview.L_LOGNAME_6}</b></td>
				<td class="row1">{overview.S_LOGVALUE_6}</td>
				<td class="row1 row-center"><b>[ <a href="{overview.S_VIEW_6}">{overview.L_VIEW}</a> |  <a href="{overview.S_DELETE_6}">{overview.L_DELETE}</a> ]</b></td>
			</tr>
			<tr>
				<td class="cat" colspan="3" align="center"><input type="Submit" name="submit" value="{overview.L_DELETE_ALL}" class="mainoption"></td>
			</tr>
		</table>
		</form>
	</td>
</tr>
</table>
<!-- END overview -->

<!-- BEGIN show_log_header -->
<div align="center">
<table class="forumline" width="80%" cellspacing="0" cellpadding="0" border="0">
<tr><td align="center" style="background-color:#FFE2BF;">{show_log_header.L_MESSAGE_TEXT}</td></tr>
</table>
</div>

<br /><br />

<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><th colspan="7">{show_log_header.L_LOG_SHOW}</th></tr>
<tr>
	<th><b>#</b></th>
	<th>{show_log_header.L_LOGCELL1}</th>
	<th>{show_log_header.L_LOGCELL2}</th>
	<th>{show_log_header.L_LOGCELL3}</th>
	<th>{show_log_header.L_LOGCELL4}</th>
	<th>{show_log_header.L_LOGCELL5}</th>
	<th>{show_log_header.L_LOGCELL6}</th>
</tr>
<!-- END show_log_header -->

<!-- BEGIN show_system_message -->
<tr><td class="row3" colspan="7" align="center"><b>{show_system_message.L_SYS_MSG}</b> - [ <a href="{show_system_message.S_DELETE}">{show_system_message.L_DELETE}</a> ]</td></tr>
<!-- END show_system_message -->

<!-- BEGIN show_log -->
<tr>
	<td class="{show_log.TABLE_CLASS}"><b>{show_log.L_NUMBER}</b></td>
	<td class="{show_log.TABLE_CLASS}">{show_log.L_OUTPUT_1}</td>
	<td class="{show_log.TABLE_CLASS}">{show_log.L_OUTPUT_2}</td>
	<td class="{show_log.TABLE_CLASS}">{show_log.L_OUTPUT_3}</td>
	<td class="{show_log.TABLE_CLASS}">{show_log.L_OUTPUT_4}</td>
	<td class="{show_log.TABLE_CLASS}">{show_log.L_OUTPUT_5}</td>
	<td class="{show_log.TABLE_CLASS}">{show_log.L_OUTPUT_6}</td>
</tr>
<!-- END show_log -->

<!-- BEGIN show_log_footer -->
<tr><td class="cat" colspan="7">&nbsp;</td></tr>
</table>
<!-- END show_log_footer -->

<br />