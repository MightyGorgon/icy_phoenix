<h1>{L_AUTOLINKS_TITLE}</h1>
<p>{L_AUTOLINKS_TEXT}</p>

<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th>#</th>
	<th>{L_KEYWORD}</th>
	<th>{L_TITLE}</th>
	<th>{L_URL}</th>
	<th>{L_COMMENT}</th>
	<th>{L_STYLE}</th>
	<th>{L_FORUM}</th>
	<th>{L_INTERNAL}</th>
	<th>{L_ACTION}</th>
</tr>
<!-- BEGIN autolinks -->
<tr>
	<td class="{autolinks.ROW_CLASS} row-center">{autolinks.NUMBER}</td>
	<td class="{autolinks.ROW_CLASS} row-center">{autolinks.KEYWORD}</td>
	<td class="{autolinks.ROW_CLASS} row-center">{autolinks.TITLE}</td>
	<td class="{autolinks.ROW_CLASS}" nowrap="nowrap">{autolinks.URL}</td>
	<td class="{autolinks.ROW_CLASS}" nowrap="nowrap">{autolinks.COMMENT}</td>
	<td class="{autolinks.ROW_CLASS}" nowrap="nowrap">{autolinks.STYLE}</td>
	<td class="{autolinks.ROW_CLASS} row-center" nowrap="nowrap">{autolinks.FORUM}</td>
	<td class="{autolinks.ROW_CLASS} row-center">{autolinks.INTERNAL}</td>
	<td class="{autolinks.ROW_CLASS} row-center"><a href="{autolinks.U_KEYWORD_EDIT}">{L_EDIT}</a></td>
</tr>
<!-- END autolinks -->
<!-- BEGIN no_autolinks -->
<tr><td colspan="9" class="row1 row-center">{no_autolinks.NO_AUTOLINKS}</td></tr>
<!-- END no_autolinks -->
</table>

<a name="edit"></a>
<form method="post" action="{S_AUTOLINKS_ACTION}">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><th colspan="2">{L_FORM_TITLE}</th></tr>
<tr>
	<td class="row1"><strong>{L_KEYWORD}</strong></td>
	<td class="row2"><input class="post" type="text" size="20" maxlength="50" name="keyword" value="{KEYWORD}" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_TITLE}</strong></td>
	<td class="row2"><input class="post" type="text" size="20" maxlength="50" name="title" value="{TITLE}" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_URL}</strong></td>
	<td class="row2"><input class="post" type="text" size="40" maxlength="200" name="url" value="{URL}" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_COMMENT}</strong></td>
	<td class="row2"><input class="post" type="text" size="40" maxlength="200" name="comment" value="{COMMENT}" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_STYLE}</strong></td>
	<td class="row2"><input class="post" type="text" size="40" maxlength="200" name="style" value="{STYLE}" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_FORUMS}</strong></td>
	<td class="row2">{S_JUMPBOX_SELECT}</td>
</tr>
<tr>
	<td class="row1"><strong>{L_INTERNAL}</strong></td>
	<td class="row2"><input type="radio" name="internal" value="1"{INTERNAL_YES} />{L_YES}&nbsp;&nbsp;<input type="radio" name="internal" value="0"{INTERNAL_NO} />{L_NO}</td>
</tr>
<!-- BEGIN delete_link -->
<tr><td colspan="2" class="row2"><input type="checkbox" name="delete" value="1" />&nbsp;{delete_link.L_DELETE_LINK}</td></tr>
<!-- END delete_link -->
<tr><td colspan="2" align="center" class="cat">{S_HIDDEN_FIELDS}<input type="submit" value="{L_SUBMIT}" class="mainoption" /></td></tr>
</table>
</form>