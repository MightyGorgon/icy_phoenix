<h1>{L_AUTH_TITLE}</h1>
<p>{L_AUTH_EXPLAIN}</p>

<form method="post" action="{S_FORUMAUTH_ACTION}">
<div style="float:right;">
<table class="forumline" width="300" cellspacing="0" cellpadding="0">
<tr><th>{L_FORUM}</th></tr>
<tr><td class="row1 row-center"><select name="forums[]" multiple="multiple" size="25">{S_FORUM_LIST}</select></td></tr>
<tr><td class="cat" align="center">&nbsp;</td></tr>
</table>
</div>
<table class="forumline" width="400" cellspacing="0" cellpadding="0" border="0">
<tr><th colspan="2">{L_AUTH_TITLE}</th></tr>
<!-- BEGIN forum_auth -->
<tr>
	<td class="row1" width="50%" nowrap="nowrap"><b>{forum_auth.CELL_TITLE}</b></td>
	<td class="row1 row-center" width="50%" nowrap="nowrap">{forum_auth.S_AUTH_LEVELS_SELECT}</td>
</tr>
<!-- END forum_auth -->
<tr>
	<td colspan="2" class="cat" align="center">{S_HIDDEN_FIELDS}
	<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;
	<input type="reset" value="{L_RESET}" name="reset" class="liteoption" />
	</td>
</tr>
</table>
</form>