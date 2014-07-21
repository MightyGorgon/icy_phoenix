<!-- INCLUDE overall_header.tpl -->

<form action="{S_EXCHANGE_ACTION}" method="post">
{IMG_TBL}<table class="forumline">
<tr><th class="tdnw" colspan="2">{L_EXCHANGE}</th></tr>
<tr>
	<td class="row1 tw50pct"><div class="post-text">{L_CONVERT}</div></td>
	<td class="row1 tw50pct"><input class="post" type="text" maxlength="20" style="width:100;" name="convert_amount" value="0" /></td>
</tr>
<tr>
	<td class="row1"><div class="post-text">{L_FROM}</div></td>
	<td class="row1">
	<select name="from_id" style="width: 100px;">
	<option value="0">{L_SELECT_ONE}</option>
	<!-- BEGIN cashrow -->
	<option value="{cashrow.CASH_ID}">{cashrow.CASH_NAME}</option>
	<!-- END cashrow -->
	</select>
	</td>
</tr>
<tr>
	<td class="row1"><div class="post-text">{L_TO}</div></td>
	<td class="row1">
	<select name="to_id" style="width: 100px;">
	<option value="0">{L_SELECT_ONE}</option>
	<!-- BEGIN cashrow -->
	<option value="{cashrow.CASH_ID}">{cashrow.CASH_NAME}</option>
	<!-- END cashrow -->
	</select>
	</td>
</tr>
<tr>
	<td class="cat" colspan="2">
	{S_HIDDEN_FIELDS}
	<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;
	<input type="reset" value="{L_RESET}" class="liteoption" />
	</td>
</tr>
</table>{IMG_TBR}
</form>

<!-- BEGIN rowrow -->
<table>
<tr>
<!-- BEGIN cashtable -->
<td class="row1">
<table class="forumline">
<tr><th class="tdnw" colspan="2">{rowrow.cashtable.HEADER}</th></tr>
<!-- BEGIN switch_exon -->
<tr>
	<td class="row1 tw50pct"><div class="post-text">{rowrow.cashtable.ONE_WORTH}</div></td>
	<td class="row1 tw50pct">
	<div class="post-text">
<!-- BEGIN exchangeitem -->
	{rowrow.cashtable.switch_exon.exchangeitem.EXCHANGE}<br />
<!-- END exchangeitem -->
	</div>
	</td>
</tr>
<!-- END switch_exon -->
<!-- BEGIN switch_exoff -->
<tr><td colspan="2" class="row1"><div class="post-text">{rowrow.cashtable.NO_EXCHANGE}</div></td></tr>
<!-- END switch_exoff -->
</table>
</td>
<!-- END cashtable -->
</tr>
</table>
<!-- END rowrow -->

<!-- INCLUDE overall_footer.tpl -->