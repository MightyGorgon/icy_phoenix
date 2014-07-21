<!-- INCLUDE overall_header.tpl -->

<form action="{S_MODEDIT_ACTION}" method="post">
{IMG_TBL}<table class="forumline">
<tr><th class="tdnw" colspan="2">{CASH_TITLE}</th></tr>
<tr>
	<td class="cat tw30pct tdalignc"><b><span class="gen">{L_AMOUNT}</span></b></td>
	<td class="cat tw70pct tdalignc"><b><span class="gen">{L_MESSAGE}</span></b></td>
</tr>
<tr>
	<td class="row1">
	<center>
	<table class="forumline">
		<tr>
			<td class="row1 tw75px"></td>
			<td class="row2 row-center tw75px"><span class="gen">{TARGET}</span></td>
			<td colspan="2" class="row3 row-center" width="150"><span class="gen"></span></td>
		</tr>
		<!-- BEGIN cashrow -->
		<tr>
			<td class="row1 row-center"><span class="gen">{cashrow.CASH_NAME}</span></td>
			<td class="row2 row-center"><span class="gen">{cashrow.RECEIVER_AMOUNT}</span></td>
			<td class="row3 row-center"><select name="{cashrow.S_TYPE_FIELD}">
			<option value="0" selected="selected">{L_OMIT}</option>
			<option value="1">{L_ADD}</option>
			<option value="2">{L_REMOVE}</option>
			<option value="3">{L_SET}</option>
			</select></td>
			<td class="row3 row-center"><input class="post" type="text" style="width:75" name="{cashrow.S_CHANGE_FIELD}"></td>
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
	<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;
	<input type="reset" value="{L_RESET}" class="liteoption" />
	</td>
</tr>
</table>{IMG_TBL}
</form>

<!-- INCLUDE overall_footer.tpl -->