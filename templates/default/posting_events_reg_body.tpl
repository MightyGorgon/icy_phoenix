<tr><th colspan="2">{L_ADD_REGISTRATION}</th></tr>
<tr><td class="row1" colspan="2"><span class="gensmall">{L_ADD_REG_EXPLAIN}</span></td></tr>
<tr>
	<td class="row1" valign="top"><span class="gen"><b>{L_REG_TITLE}</b></span></td>
	<td class="row1">
		<span class="gen"><input type="checkbox" name="start_registration" value="1" {REG_ACTIVE} />&nbsp;<b>{L_REG_ACTIVATE}</b></span><br />
		<span class="gen"><input type="checkbox" name="reset_registration" value="1" />&nbsp;<b>{L_REG_RESET}</b></span><br />
		<span class="gen"><b>{L_REG_MAX_REGISTRATIONS}</b>&nbsp;<input type="text" class="post" name="reg_max_option1" size="3" maxlength="3" value="{L_REG_MAX_OPTION1}" /></span><br />
		<input type="hidden" name="reg_option1" value="{L_REG_OPTION1}" />
		<input type="hidden" name="reg_option2" value="{L_REG_OPTION2}" />
		<input type="hidden" name="reg_option3" value="{L_REG_OPTION3}" />
	</td>
</tr>
<tr>
	<td class="row1" valign="top"><span class="gen"><b>{L_REG_LENGTH}</b></span></td>
	<td class="row2">
		<span class="gen"><input type="text" name="reg_length" size="3" maxlength="3" class="post" value="{REG_LENGTH}" /></span>&nbsp;
		<span class="gen"><b>{L_DAYS}</b></span>&nbsp;
		<span class="gensmall">{L_REG_LENGTH_EXPLAIN}</span>
	</td>
</tr>