<form action="{S_DONATE_ACTION}" method="post">
{IMG_TBL}<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><th colspan="2" nowrap="nowrap">{L_DONATE_TO}</th></tr>
<tr>
	<td class="cat" width="30%" align="center"><b><span class="gen">{L_AMOUNT}</span></b></td>
	<td class="cat" width="70%" align="center"><b><span class="gen">{L_MESSAGE}</span></b></td>
</tr>
<tr>
	<td class="row1">
	<center>
	<table class="forumline" cellspacing="0" cellpadding="0" border="0">
	<table width="100%" cellspacing="0" cellpadding="0" border="0">
		<tr>
			<td class="row1" width="75"></td>
			<td class="row2 row-center" width="75"><span class="gen">{TARGET}</span></td>
			<td class="row2 row-center" width="75"><span class="gen">{L_DONATE}</span></td>
			<td class="row3 row-center" width="75"><span class="gen">{DONATER}</span></td>
		</tr>
		<!-- BEGIN cashrow -->
		<tr>
			<td class="row1 row-center"><span class="gen">{cashrow.CASH_NAME}</span></td>
			<td class="row2 row-center"><span class="gen">{cashrow.RECEIVER_AMOUNT}</span></td>
			<td class="row2 row-center"><input class="post" type="text" style="width:75" name="{cashrow.S_DONATE_FIELD}" /></td>
			<td class="row3 row-center"><span class="gen">{cashrow.DONATER_AMOUNT}</span></td>
		</tr>
		<!-- END cashrow -->
	</table>
	</center>
	</td>
	<td class="row1 row-center"><textarea name="message" rows="10" cols="35" style="width: 450px;"></textarea></td>
</tr>
<tr>
	<td class="cat" colspan="2">
	{S_HIDDEN_FIELDS}
	<input type="submit" name="submit" value="{L_DONATE}" class="mainoption" />&nbsp;&nbsp;
	<input type="reset" value="{L_RESET}" class="liteoption" />
	</td>
</tr>
</table>{IMG_TBR}
</form>