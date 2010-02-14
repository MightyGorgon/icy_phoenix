{SELECT_SCRIPT}
<h1>{L_DATABASE_OPTIMIZE}</h1>
<p>{L_OPTIMIZE_EXPLAIN}</p>

<form method="post" action="{S_DBUTILS_ACTION}" name="tablesForm">
<div align="center">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><th valign="middle" colspan="6"><span class="cattitle">{L_OPTIMIZE_DB}</span></th></tr>
<tr><th colspan="6">{L_CONFIGURATION}</th></tr>
<tr>
	<td class="row1" colspan="2"><strong>{L_CRON_EVERY}:</strong><br /><span class="gensmall">{L_CRON_EVERY_EXPLAIN}</span></td>
	<td class="row2 row-center" colspan="4">{CRON_INTERVALS_SELECT}</td>
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
		<input type="submit" name="configure" value="{L_CONFIGURE}" class="liteoption" />
		<!--
		&nbsp;&nbsp;
		<input type="submit" name="reset" value="{L_RESET}" class="liteoption" onclick="document.tablesForm.show_begin_for.value=''" />
		-->
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
