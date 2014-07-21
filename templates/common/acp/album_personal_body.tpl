<h1>{L_ALBUM_PERSONAL_TITLE}</h1>

<p>{L_ALBUM_PERSONAL_EXPLAIN}</p>

<form action="{S_ALBUM_ACTION}" method="post">
<table class="forumline">
<tr><th colspan="2">{L_GROUP_CONTROL}</th></tr>
<!-- BEGIN creation_grouprow -->
<tr>
	<td class="row1 row-center" height="28"><span class="gen">{creation_grouprow.GROUP_NAME}</span></td>
	<td class="row2 row-center"><input name="private[]" type="checkbox" {creation_grouprow.PRIVATE_CHECKED} value="{creation_grouprow.GROUP_ID}" /></td>
</tr>
<!-- END creation_grouprow -->
<tr><td class="cat" height="25" align="center" nowrap="nowrap" colspan="2"><input type="reset" value="{L_RESET}" class="liteoption" />&nbsp;&nbsp;&nbsp;<input name="submit" type="submit" value="{L_SUBMIT}" class="mainoption" /></td></tr>
</table>

<p>{L_ALBUM_AUTH_EXPLAIN}</p>

<table class="forumline">
<tr>
	<th nowrap="nowrap">{L_GROUPS}</th>
	<th nowrap="nowrap">{L_VIEW}</th>
	<th nowrap="nowrap">{L_UPLOAD}</th>
	<th nowrap="nowrap">{L_RATE}</th>
	<th nowrap="nowrap">{L_COMMENT}</th>
	<th nowrap="nowrap">{L_EDIT}</th>
	<th nowrap="nowrap">{L_DELETE}</th>
	<th nowrap="nowrap">{L_IS_MODERATOR}</th>
</tr>
<!-- BEGIN grouprow -->
<tr>
	<td class="row1 row-center" height="28"><span class="gen">{grouprow.GROUP_NAME}</span></td>
	<td class="row2 row-center"><input name="view[]" type="checkbox" {grouprow.VIEW_CHECKED} value="{grouprow.GROUP_ID}" /></td>
	<td class="row2 row-center"><input name="upload[]" type="checkbox" {grouprow.UPLOAD_CHECKED} value="{grouprow.GROUP_ID}" /></td>
	<td class="row2 row-center"><input name="rate[]" type="checkbox" {grouprow.RATE_CHECKED} value="{grouprow.GROUP_ID}" /></td>
	<td class="row2 row-center"><input name="comment[]" type="checkbox" {grouprow.COMMENT_CHECKED} value="{grouprow.GROUP_ID}" /></td>
	<td class="row2 row-center"><input name="edit[]" type="checkbox" {grouprow.EDIT_CHECKED} value="{grouprow.GROUP_ID}" /></td>
	<td class="row2 row-center"><input name="delete[]" type="checkbox" {grouprow.DELETE_CHECKED} value="{grouprow.GROUP_ID}" /></td>
	<td class="row2 row-center"><input name="moderator[]" type="checkbox" {grouprow.MODERATOR_CHECKED} value="{grouprow.GROUP_ID}" /></td>
</tr>
<!-- END grouprow -->
<tr>
	<td class="cat" height="25" align="center" colspan="8"><input type="reset" value="{L_RESET}" class="liteoption" />&nbsp;&nbsp;&nbsp;<input name="submit" type="submit" value="{L_SUBMIT}" class="mainoption" /></td>
</tr>
</table>
</form>

<br />