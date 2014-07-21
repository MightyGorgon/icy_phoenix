{NAVBAR}
<h1>{L_EXCHANGE_TITLE}</h1>
<p>{L_EXCHANGE_EXPLAIN}</p>

<form action="{S_EXCHANGE_ACTION}" method="post">
<table class="forumline">
<tr>
<!-- BEGIN cashrow -->
	<th>{cashrow.CURRENCY_NAME}</th>
<!-- END cashrow -->
</tr>
<tr>
<!-- BEGIN cashrow -->
	<td class="{cashrow.ROW_CLASS} row-center"><input name="currency_val[{cashrow.CURRENCY_ID}]" value="{cashrow.CURRENCY_EXCHANGE}" class="post" /></td>
<!-- END cashrow -->
</tr>
<tr>
	<td class="cat" colspan="{NUM_COLUMNS}" align="center">
	<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;
	<input type="reset" value="{L_RESET}" class="liteoption" />
	</td>
</tr>
</table>
</form>

<br />
<br />

<form action="{S_EXCHANGE_ACTION}" method="post">
<table class="forumline">
<tr>
	<td class="{CORNER_CLASS}" colspan="2" rowspan="2" valign="middle" align="center">{L_EXCHANGE}</th>
	<td class="{SIDE_CLASS}" colspan="{NUM_COLUMNS}" valign="middle" align="center">{L_TO}</th>
</tr>
<tr>
	<!-- BEGIN cashrow -->
	<td class="{cashrow.ROW_CLASS} row-center">{cashrow.CURRENCY_NAME}</td>
	<!-- END cashrow -->
</tr>
<!-- BEGIN siderow -->
<tr>
	<!-- BEGIN switch_first -->
	<td class="{SIDE_CLASS}" rowspan="{NUM_COLUMNS}" valign="middle" align="center">{L_FROM}</th>
	<!-- END switch_first -->
	<td class="{siderow.ROW_CLASS} row-center">{siderow.CURRENCY_NAME}</td>
	<!-- BEGIN entry -->
	<td class="{siderow.entry.ROW_CLASS} row-center">{siderow.entry.CURRENCY_EX}</td>
	<!-- END entry -->
</tr>
<!-- END siderow -->
</table>
</form>

