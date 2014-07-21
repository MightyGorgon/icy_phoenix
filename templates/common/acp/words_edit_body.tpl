<h1>{L_WORDS_TITLE}</h1>
<p>{L_WORDS_TEXT}</p>

<form method="post" action="{S_WORDS_ACTION}">
<table class="forumline">
<tr><th colspan="2">{L_WORD_CENSOR}</th></tr>
<tr>
	<td class="row1"><strong>{L_WORD}</strong></td>
	<td class="row2"><input class="post" type="text" name="word" value="{WORD}" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_REPLACEMENT}</strong></td>
	<td class="row2"><input class="post" type="text" name="replacement" value="{REPLACEMENT}" /></td>
</tr>
<tr><td class="cat tdalignc" colspan="2">{S_HIDDEN_FIELDS}<input type="submit" name="save" value="{L_SUBMIT}" class="mainoption" /></td></tr>
</table>
</form>
