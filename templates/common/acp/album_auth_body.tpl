<h1>{L_ALBUM_AUTH_TITLE}</h1>

<p>{L_ALBUM_AUTH_EXPLAIN}</p>

<form action="{S_ALBUM_ACTION}" method="post">
<table class="forumline">
<tr>
	<th nowrap="nowrap">{L_GROUPS}</th>
	<th nowrap="nowrap">{L_IS_MODERATOR}</th>
	<th nowrap="nowrap">{L_VIEW}</th>
	<th nowrap="nowrap">{L_UPLOAD}</th>
	<th nowrap="nowrap">{L_RATE}</th>
	<th nowrap="nowrap">{L_COMMENT}</th>
	<th nowrap="nowrap">{L_EDIT}</th>
	<th nowrap="nowrap">{L_DELETE}</th>
</tr>
<!-- BEGIN grouprow -->
<tr class="{grouprow.CLASS}h">
	<td class="{grouprow.CLASS}h row-center" style="background: none; height: 28px;"><span class="gen">{grouprow.GROUP_NAME}</span></td>
	<td class="{grouprow.CLASS}h row-center" style="background: none;"><input name="moderator[]" type="checkbox" {grouprow.MODERATOR_CHECKED} value="{grouprow.GROUP_ID}" /></td>
	<td class="{grouprow.CLASS}h row-center" style="background: none;"><input name="view[]" type="checkbox" {grouprow.VIEW_CHECKED} value="{grouprow.GROUP_ID}" /></td>
	<td class="{grouprow.CLASS}h row-center" style="background: none;"><input name="upload[]" type="checkbox" {grouprow.UPLOAD_CHECKED} value="{grouprow.GROUP_ID}" /></td>
	<td class="{grouprow.CLASS}h row-center" style="background: none;"><input name="rate[]" type="checkbox" {grouprow.RATE_CHECKED} value="{grouprow.GROUP_ID}" /></td>
	<td class="{grouprow.CLASS}h row-center" style="background: none;"><input name="comment[]" type="checkbox" {grouprow.COMMENT_CHECKED} value="{grouprow.GROUP_ID}" /></td>
	<td class="{grouprow.CLASS}h row-center" style="background: none;"><input name="edit[]" type="checkbox" {grouprow.EDIT_CHECKED} value="{grouprow.GROUP_ID}" /></td>
	<td class="{grouprow.CLASS}h row-center" style="background: none;"><input name="delete[]" type="checkbox" {grouprow.DELETE_CHECKED} value="{grouprow.GROUP_ID}" /></td>
</tr>
<!-- END grouprow -->
<tr><td class="cat tdalignc" colspan="8"><input type="reset" value="{L_RESET}" class="liteoption" />&nbsp;&nbsp;<input name="submit" type="submit" value="{L_SUBMIT}" class="mainoption" /></td></tr>
</table>
</form>

<br />