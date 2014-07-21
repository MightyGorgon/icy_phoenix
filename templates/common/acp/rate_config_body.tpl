<form method="post" action="{S_MODE_ACTION}">
<table class="forumline">
<tr><th>{L_STATUS}</th></tr>
<tr><td align="center" class="{CLASS_1}" colspan="2"><span class="genmed">{ADMIN_MESSAGE}<br /><br /></span></td></tr>
</table>
<br />
<br />
<table class="forumline">
<tr><th colspan="2">Topic Rating {L_PAGE_NAME}</th></tr>
<!-- BEGIN config_row -->
<tr>
	<td class="{CLASS_1}"><span class="genmed"><strong>{config_row.L_CONFIG}:</strong></span></td>
	<td class="{CLASS_2}"><span class="genmed">{config_row.S_CONFIG}</span></td>
</tr>
<!-- END config_row -->
<tr>
	<td class="cat tdalignc" colspan="2">
		{S_HIDDEN_FIELDS}
		<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;
		<input type="reset" value="{L_RESET}" class="liteoption" />
	</td>
</tr>
</table>

</form>