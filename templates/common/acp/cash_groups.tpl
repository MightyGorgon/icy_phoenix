{NAVBAR}
<h1>{L_CASH_GROUPS_TITLE}</h1>
<p>{L_CASH_GROUPS_EXPLAIN}</p>

<table class="forumline">
<tr><th colspan="{NUM_COLUMNS}" align="left"></th></tr>
<!-- BEGIN entryrow -->
<form action="{S_CASH_GROUPS_ACTION}" method="post">{entryrow.S_HIDDEN_FIELDS1}
<tr><td class="row1" colspan="{NUM_COLUMNS}">{entryrow.NAME}<br /><span class="gensmall">{entryrow.DESCRIPTION}</span></td></tr>
<tr>
<!-- BEGIN cashrow -->
	<td class="row2" align="center" width="{entryrow.CELLWIDTH}%">{entryrow.cashrow.NAME}</td>
<!-- END cashrow -->
	<td class="row2" align="center" width="{entryrow.REMAINDERWIDTH}%">&nbsp;</td>
	<td class="row1" align="center" width="0">&nbsp;</td>
</tr>
<tr>
<!-- BEGIN switch_displayon -->
<!-- BEGIN cashrow -->
	<td class="row2" align="center" width="{entryrow.CELLWIDTH}%" rowspan="2">{entryrow.switch_displayon.cashrow.ENTRY}</td>
<!-- END cashrow -->
<!-- END switch_displayon -->
<!-- BEGIN switch_displayoff -->
	<td class="row2" align="center" colspan="{NUM_CURRENCIES}" width="{entryrow.MERGEWIDTH}%" rowspan="2"><hr width="95%" style="border: #000000;" /></td>
<!-- END switch_displayoff -->
	<td class="row3 row-center" width="{entryrow.REMAINDERWIDTH}%" rowspan="2"><input name="submit" type="submit" value="{L_EDIT}" class="mainoption" /></td>
	<td class="row1 row-center" width="0">&nbsp;</td>
</tr>
<tr><td class="row1 row-center" width="0">&nbsp;</td></tr>
</form>
<form action="{S_CASH_GROUPS_ACTION}" method="post">{entryrow.S_HIDDEN_FIELDS2}
<tr>
<!-- BEGIN cashrow -->
	<td class="row2 row-center" width="{entryrow.CELLWIDTH}%"><select name="{entryrow.cashrow.S_TYPE_FIELD}" style="width: 100;"><option value="0" selected="selected">{L_OMIT}</option><option value="1">{L_ADD}</option><option value="2">{L_REMOVE}</option><option value="3">{L_SET}</option></select></td>
<!-- END cashrow -->
	<td class="row3 row-center" width="{entryrow.REMAINDERWIDTH}%" rowspan="2"><input name="submit" type="submit" value="{L_UPDATE}" class="liteoption" /></td>
	<td class="row1 row-center" width="0">&nbsp;</td>
</tr>
<tr>
<!-- BEGIN cashrow -->
	<td class="row2 row-center" width="{entryrow.CELLWIDTH}%"><input name="{entryrow.cashrow.S_AMOUNT_FIELD}" style="width: 100;" class="post" /></td>
<!-- END cashrow -->
	<td class="row1 row-center" width="0">&nbsp;</td>
</tr>
</form>
<tr><td class="row3" colspan="{NUM_COLUMNS}" height="20"></td></tr>
<!-- END entryrow -->
</table>

<br clear="all" />
