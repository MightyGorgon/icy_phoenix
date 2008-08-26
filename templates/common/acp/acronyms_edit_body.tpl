<div class="maintitle">{L_ACRONYMS_TITLE}</div>
<br />
<div class="genmed">{L_ACRONYMS_TEXT}</div>
<br />
<form method="post" action="{S_ACRONYMS_ACTION}">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><th colspan="2">{L_ACRONYM_EDIT}</th></tr>
<tr>
	<td align="right" class="row1">{L_ACRONYM}:</td>
	<td class="row2"><input type="text" name="acronym" value="{ACRONYM}" class="post" maxlength="80" /></td>
</tr>
<tr>
	<td align="right" class="row1">&nbsp;{L_DESCRIPTION}:</td>
	<td class="row2"><input type="text" name="description" value="{DESCRIPTION}" class="post" maxlength="255"/></td>
</tr>
<tr><td class="cat" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="save" value="{L_SUBMIT}" class="mainoption" /></td></tr>
</table>
</form>
<br />
