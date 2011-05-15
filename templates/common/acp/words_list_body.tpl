<h1>{L_WORDS_TITLE}</h1>
<p>{L_WORDS_TEXT}</p>

<form method="post" action="{S_WORDS_ACTION}">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th>{L_WORD}</th>
	<th>{L_REPLACEMENT}</th>
	<th>{L_ACTION}</th>
</tr>
<!-- BEGIN words -->
<tr>
	<td class="{words.ROW_CLASS}">{words.WORD}</td>
	<td class="{words.ROW_CLASS}">{words.REPLACEMENT}</td>
	<td class="{words.ROW_CLASS} row-center"><a href="{words.U_WORD_EDIT}"><img src="{IMG_CMS_ICON_EDIT}" alt="{L_EDIT}" title="{L_EDIT}" /></a>&nbsp;<a href="{words.U_WORD_DELETE}"><img src="{IMG_CMS_ICON_DELETE}" alt="{L_DELETE}" title="{L_DELETE}" /></a></td>
</tr>
<!-- END words -->
<tr><td colspan="5" align="center" class="cat">{S_HIDDEN_FIELDS}<input type="submit" name="add" value="{L_ADD_WORD}" class="mainoption" /></td></tr>
</table>
</form>
