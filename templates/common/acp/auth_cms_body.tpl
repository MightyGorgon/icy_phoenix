<h1>{L_AUTH_TITLE}</h1>
<h2>{L_USER}: {USERNAME}</h2>

<form method="post" action="{S_AUTH_ACTION}">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><td class="row1 row-center"><span class="gen">{USER_LEVEL}</span></td></tr>
<tr>
	<td class="cat" align="center">
	{S_HIDDEN_FIELDS}
	<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;
	<input type="reset" value="{L_RESET}" class="liteoption" name="reset" />
	</td>
</tr>
</table>
</form>