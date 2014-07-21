{NAVBAR}
<h1>{L_CASH_GROUP_TITLE}</h1>
<p>{L_CASH_GROUP_EXPLAIN}</p>

<form action="{S_CASH_GROUP_ACTION}" method="post">
<table class="forumline">
<tr><th colspan="{NUM_COLUMNS}">{L_DISPLAY}</th></tr>
<tr>
	<td class="row1">{L_CASH_CURRENCY}</td>
<!-- BEGIN cashrow -->
	<td class="row2">{cashrow.CURRENCY}</td>
<!-- END cashrow -->
</tr>

<tr><th colspan="{NUM_COLUMNS}">{L_IMPLEMENTATION}</th></tr>

<tr>
	<td class="row1">{L_CASH_AMOUNT_PER_POST}</td>
<!-- BEGIN cashrow -->
	<td class="row2"><input class="post" type="text" maxlength="10" size="10" name="cash_{cashrow.CASH_INDEX}[cash_perpost]" value="{cashrow.AMOUNT_PER_POST}" /></td>
<!-- END cashrow -->
</tr>
<tr>
	<td class="row1">{L_CASH_AMOUNT_POST_BONUS}</td>
<!-- BEGIN cashrow -->
	<td class="row2"><input class="post" type="text" maxlength="10" size="10" name="cash_{cashrow.CASH_INDEX}[cash_postbonus]" value="{cashrow.AMOUNT_POST_BONUS}" /></td>
<!-- END cashrow -->
</tr>
<tr>
	<td class="row1">{L_CASH_AMOUNT_PER_REPLY}</td>
<!-- BEGIN cashrow -->
	<td class="row2"><input class="post" type="text" maxlength="10" size="10" name="cash_{cashrow.CASH_INDEX}[cash_perreply]" value="{cashrow.AMOUNT_PER_REPLY}" /></td>
<!-- END cashrow -->
</tr>
<tr>
	<td class="row1">{L_CASH_AMOUNT_PER_CHARACTER}</td>
<!-- BEGIN cashrow -->
	<td class="row2"><input class="post" type="text" maxlength="10" size="10" name="cash_{cashrow.CASH_INDEX}[cash_perchar]" value="{cashrow.AMOUNT_PER_CHAR}" /></td>
<!-- END cashrow -->
</tr>
<tr>
	<td class="row1">{L_CASH_MAXEARN}</td>
<!-- BEGIN cashrow -->
	<td class="row2"><input class="post" type="text" maxlength="10" size="10" name="cash_{cashrow.CASH_INDEX}[cash_maxearn]" value="{cashrow.MAXEARN}" /></td>
<!-- END cashrow -->
</tr>
<tr>
	<td class="row1">{L_CASH_AMOUNT_PER_PM}</td>
<!-- BEGIN cashrow -->
	<td class="row2"><input class="post" type="text" maxlength="10" size="10" name="cash_{cashrow.CASH_INDEX}[cash_perpm]" value="{cashrow.AMOUNT_PER_PM}" /></td>
<!-- END cashrow -->
</tr>

<tr><th colspan="{NUM_COLUMNS}">{L_ALLOWANCES}</th></tr>
<tr><th colspan="{NUM_COLUMNS}"><span class="gensmall">{L_ALLOWANCES_EXPLAIN}</span></th></tr>

<tr>
	<td class="row1">{L_CASH_ALLOWANCE_ENABLED}</td>
<!-- BEGIN cashrow -->
	<td class="row2"><input type="radio" name="cash_{cashrow.CASH_INDEX}[cash_allowance]" value="1" {cashrow.ALLOWANCES_ENABLED_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="cash_{cashrow.CASH_INDEX}[cash_allowance]" value="0" {cashrow.ALLOWANCES_ENABLED_NO} /> {L_NO}</td>
<!-- END cashrow -->
</tr>
<tr>
	<td class="row1">{L_CASH_ALLOWANCE_AMOUNT}</td>
<!-- BEGIN cashrow -->
	<td class="row2"><input class="post" type="text" maxlength="10" size="10" name="cash_{cashrow.CASH_INDEX}[cash_allowanceamount]" value="{cashrow.ALLOWANCE_AMOUNT}" /></td>
<!-- END cashrow -->
</tr>
<tr>
	<td class="row1">{L_CASH_ALLOWANCE_FREQUNECY}</td>
<!-- BEGIN cashrow -->
	<td class="row2"><input type="radio" name="cash_{cashrow.CASH_INDEX}[cash_allowancetime]" value="1" {cashrow.ALLOWANCES_FREQ_DAY} /> {L_CASH_ALLOWANCE_FREQUNECIES_DAY}<br />
			<input type="radio" name="cash_{cashrow.CASH_INDEX}[cash_allowancetime]" value="2" {cashrow.ALLOWANCES_FREQ_WEEK} /> {L_CASH_ALLOWANCE_FREQUNECIES_WEEK}<br />
			<input type="radio" name="cash_{cashrow.CASH_INDEX}[cash_allowancetime]" value="3" {cashrow.ALLOWANCES_FREQ_MONTH} /> {L_CASH_ALLOWANCE_FREQUNECIES_MONTH}<br />
			<input type="radio" name="cash_{cashrow.CASH_INDEX}[cash_allowancetime]" value="4" {cashrow.ALLOWANCES_FREQ_YEAR} /> {L_CASH_ALLOWANCE_FREQUNECIES_YEAR}<br />
			</td>
<!-- END cashrow -->
</tr>
<tr>
	<td class="row1">{L_CASH_ALLOWANCE_NEXT}</td>
<!-- BEGIN cashrow -->
	<td class="row2">{cashrow.ALLOWANCE_NEXT}</td>
<!-- END cashrow -->
</tr>
<tr>
	<td class="cat" colspan="{NUM_COLUMNS}" align="center">
	{S_HIDDEN_FIELDS}
	<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;
	<input type="reset" value="{L_RESET}" class="liteoption" />
	</td>
</tr>
</table>
</form>

<br clear="all" />
