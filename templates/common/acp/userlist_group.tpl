<form action="{S_ACTION}" method="post">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><th valign="middle"><span class="forumlink">{MESSAGE_TITLE}</span></th></tr>
<tr>
	<td class="row1 row-center">
		<br />
		<span class="gen">{MESSAGE_TEXT}</span><br /><br />
		<span class="gen">
		{S_HIDDEN_FIELDS}
		<select name="{S_GROUP_VARIABLE}" class="post">
			<option value="">{L_SELECT}</option>
			<!-- BEGIN grouprow -->
			<option value="{grouprow.GROUP_ID}">{grouprow.GROUP_NAME}</option>
			<!-- END grouprow -->
		</select>
		<input type="submit" name="confirm" value="{L_GO}" class="mainoption" />
		<input type="submit" name="cancel" value="{L_CANCEL}" class="liteoption" />
		</span>
	</td>
</tr>
</table>
</form>
<br clear="all" />
