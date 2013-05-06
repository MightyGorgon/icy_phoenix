<h1>{L_AUTH_TITLE}</h1>
<p>{L_AUTH_EXPLAIN}</p>
<p>{L_FORUMS_SELECTION_MULTIPLE}</p>
<br />

<form method="post" action="{S_FORUMAUTH_ACTION}">

<div style="float: right;">
<table class="forumline" width="300" cellspacing="0" cellpadding="0">
<tr><th>{L_FORUM}</th></tr>
<tr><td class="row1 row-center"><select name="forums[]" multiple="multiple" size="25" style="min-height: 400px;">{S_FORUM_LIST}</select></td></tr>
<tr><td class="cat" align="center">&nbsp;</td></tr>
</table>
</div>

<table class="forumline" width="400" cellspacing="0" cellpadding="0" border="0" style="float: left; min-width: 400px;">
<tr><th colspan="2">{L_AUTH_TITLE}</th></tr>
<!-- BEGIN forum_auth -->
<tr>
	<td class="row1" width="50%" nowrap="nowrap"><b>{forum_auth.CELL_TITLE}</b></td>
	<td class="row1 row-center" width="50%" nowrap="nowrap">{forum_auth.S_AUTH_LEVELS_SELECT}</td>
</tr>
<!-- END forum_auth -->
<tr><td colspan="2" class="cat" align="center"><input type="submit" name="submit" value="{L_FORUMS_SUBMIT_AUTH}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" name="reset" class="liteoption" /></td></tr>
<tr><td class="spaceRow" colspan="2"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
<tr><th colspan="2">{L_Configuration}</th></tr>
<!-- BEGIN forum_option -->
<tr>
	<td class="row1" width="50%" nowrap="nowrap"><b>{forum_option.CELL_TITLE}</b></td>
	<td class="row1 row-center" width="50%" nowrap="nowrap">
		<input type="checkbox" name="{forum_option.S_AUTH_LEVELS_SELECT}" />
	</td>
</tr>
<!-- END forum_option -->
<tr><td colspan="2" class="cat" align="center"><input type="submit" name="options_submit" value="{L_FORUMS_SUBMIT_CFG}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" name="reset" class="liteoption" /></td></tr>
</table>

{S_HIDDEN_FIELDS}

</form>