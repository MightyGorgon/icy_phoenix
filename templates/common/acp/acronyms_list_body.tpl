<h1>{L_ACRONYMS_TITLE}</h1>
<p>{L_ACRONYMS_TEXT}</p>

<form method="post" action="{S_ACRONYMS_ACTION}">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th>&nbsp;{L_ACRONYM}&nbsp;</th>
	<th>&nbsp;{L_DESCRIPTION}&nbsp;</th>
	<th>{L_ACTION}</th>
</tr>
<!-- BEGIN acronyms -->
<tr>
	<td class="{acronyms.ROW_CLASS}">{acronyms.ACRONYM}</td>
	<td class="{acronyms.ROW_CLASS}">{acronyms.DESCRIPTION}</td>
	<td class="{acronyms.ROW_CLASS} row-center">&nbsp;<a href="{acronyms.U_ACRONYM_EDIT}"><img src="{IMG_CMS_ICON_EDIT}" alt="{L_EDIT}" title="{L_EDIT}" /></a>&nbsp;<a href="{acronyms.U_ACRONYM_DELETE}"><img src="{IMG_CMS_ICON_DELETE}" alt="{L_DELETE}" title="{L_DELETE}" /></a>&nbsp;</td>
</tr>
<!-- END acronyms -->
<tr><td class="cat" colspan="3" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="add" value="{L_ADD_ACRONYM}" class="mainoption" /></td></tr>
</table>
</form>
<br />