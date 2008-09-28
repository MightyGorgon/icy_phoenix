{SELECT_SCRIPT}
<h1>{L_DATABASE_OPTIMIZE}</h1>
<P>{L_OPTIMIZE_EXPLAIN}</p>

<form method="post" action="{S_DBUTILS_ACTION}" name="tablesForm">
<div align="center">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><th valign="middle" colspan="6"><span class="cattitle">{L_OPTIMIZE_DB}</span></th></tr>
<tr><th colspan="6">{L_CONFIGURATION}</th></tr>
<tr>
	<td class="row1" colspan="2"><strong>{L_ENABLE_CRON}:</strong></td>
	<td class="row1 row-center" colspan="4">
		<input type="radio" name="enable_optimize_cron" value="1" {S_ENABLE_CRON_YES} /> {L_YES}&nbsp;&nbsp;
		<input type="radio" name="enable_optimize_cron" value="0" {S_ENABLE_CRON_NO} /> {L_NO}
	</td>
</tr>

<tr>
	<td class="row1" colspan="2"><strong>{L_CRON_EVERY}:</strong></td>
	<td class="row2" colspan="4">
<!-- BEGIN sel_cron_every -->
		<select name="cron_every">
			<option value="2592000" {sel_cron_every.MONTH}>{sel_cron_every.L_MONTH}</option>
			<option value="1296000" {sel_cron_every.2WEEKS}>{sel_cron_every.L_2WEEKS}</option>
			<option value="604800" {sel_cron_every.WEEK}>{sel_cron_every.L_WEEK}</option>
			<option value="259200" {sel_cron_every.3DAYS}>{sel_cron_every.L_3DAYS}</option>
			<option value="86400" {sel_cron_every.DAY}>{sel_cron_every.L_DAY}</option>
			<option value="21600" {sel_cron_every.6HOURS}>{sel_cron_every.L_6HOURS}</option>
			<option value="3600" {sel_cron_every.HOUR}>{sel_cron_every.L_HOUR}</option>
			<option value="1800" {sel_cron_every.30MINUTES}>{sel_cron_every.L_30MINUTES}</option>
			<option value="20" {sel_cron_every.20SECONDS}>{sel_cron_every.L_20SECONDS}</option>
		</select>
<!-- END sel_cron_every -->
	</td>
</tr>
<tr>
	<td class="row1" colspan="2" valign="top"><strong>
		{L_CURRENT_TIME}:<br />
		{L_NEXT_CRON_ACTION}:<br />
		{L_PERFORMED_CRON}:
	</strong></td>
	<td class="row1 row-center" colspan="4" valign="top">
		{CURRENT_TIME}<br />
		{NEXT_CRON}<br />
		{PERFORMED_CRON}
	</td>
</tr>
<tr>
	<td class="row1" colspan="2"><strong>{L_SHOW_NOT_OPTIMIZED}:</strong></td>
	<td class="row1 row-center" colspan="4"><input type="radio" name="show_not_optimized" value="1" {S_ENABLE_NOT_OPTIMIZED_YES}/> {L_YES}&nbsp;&nbsp;<input type="radio" name="show_not_optimized" value="0" {S_ENABLE_NOT_OPTIMIZED_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1" colspan="2"><strong>{L_SHOW_BEGIN_FOR}:</strong></td>
	<td class="row1 row-center" colspan="4"><input class="post" type="text" maxlength="255" name="show_begin_for" value="{S_SHOW_BEGIN_FOR}" /></td>
</tr>
<tr>
	<td class="row1 row-center" align="center" colspan="6">
		<input type="submit" name="configure" value="{L_CONFIGURE}" class="liteoption" />&nbsp;&nbsp;
		<input type="submit" name="reset" value="{L_RESET}" class="liteoption" onClick="document.tablesForm.show_begin_for.value=''" />
	</td>
</tr>
<tr><td colspan="6" height="1" class="spaceRow"><img src="{SPACER}" alt="" width="1" height="3" /></td></tr>
<tr>
	<th align="center" valign="middle"><span class="cattitle">{L_TABLE}</span></th>
	<th align="center" valign="middle"><span class="cattitle">{L_RECORD}</span></th>
	<th align="center" valign="middle"><span class="cattitle">{L_TYPE}</span></th>
	<th align="center" valign="middle"><span class="cattitle">{L_SIZE}</span></th>
	<th align="center" valign="middle"><span class="cattitle">{L_STATUS}</span></th>
	<th align="center" valign="middle"> &nbsp; &nbsp; </span></th>
</tr>

<!-- BEGIN optimize -->
<tr>
	<td class="{optimize.ROW_CLASS}">{optimize.TABLE}</td>
	<td class="{optimize.ROW_CLASS}" align="right">{optimize.RECORD}</td>
	<td class="{optimize.ROW_CLASS} row-center">{optimize.TYPE}</td>
	<td class="{optimize.ROW_CLASS}" align="right">{optimize.SIZE}</td>
	<td class="{optimize.ROW_CLASS} row-center">{optimize.STATUS}</td>
	<td class="{optimize.ROW_CLASS}">{optimize.S_SELECT_TABLE}</td>
</tr>
<!-- END optimize -->

<tr>
	<td class="row3"><b>{TOT_TABLE}</b></td>
	<td class="row3" align="right"><b>{TOT_RECORD}</b></td>
	<td class="row3 row-center"><b>- -</b></td>
	<td class="row3" align="right"><b>{TOT_SIZE}</b></td>
	<td class="row3 row-center"><b>{TOT_STATUS}</b></td>
	<td class="row3">&nbsp;</td>
</tr>

<tr>
	<td class="row3 row-center" colspan="6">
		<a href="#" onclick="setCheckboxes('tablesForm', true); return false;">{L_CHECKALL}</a>&nbsp;/&nbsp;<a href="#" onclick="setCheckboxes('tablesForm', false); return false;">{L_UNCHECKALL}</a>&nbsp;/&nbsp;<a href="#" onclick="setCheckboxes('tablesForm', 'invert'); return false;">{L_INVERTCHECKED}</a>
	</td>
</tr>

<tr>
	<td class="cat" colspan="6" align="center">
		{S_HIDDEN_FIELDS}
		<input type="submit" name="optimize" value="{L_START_OPTIMIZE}" class="mainoption" />
	</td>
</tr>
</table>
</div>
</form>
