<h1>{L_KB_AUTH_TITLE}</h1>
<p>{L_KB_AUTH_EXPLAIN}</p>

<form action="{S_KB_ACTION}" method="post">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th nowrap="nowrap">{L_GROUPS}</th>
	<th nowrap="nowrap">{L_VIEW}</th>
	<th nowrap="nowrap">{L_UPLOAD}</th>
	<th nowrap="nowrap">{L_RATE}</th>
	<th nowrap="nowrap">{L_COMMENT}</th>
	<th nowrap="nowrap">{L_EDIT}</th>
	<th nowrap="nowrap">{L_DELETE}</th>
	<!--
	<th nowrap="nowrap">{L_APPROVAL}</th>
	<th nowrap="nowrap">{L_APPROVAL_EDIT}</th>
	-->
	<th nowrap="nowrap">{L_IS_MODERATOR}</th>
</tr>
<!-- BEGIN grouprow -->
<tr>
	<td class="row1 row-center" height="28"><span class="gen">{grouprow.GROUP_NAME}</span></td>
	<td class="row2 row-center"><input name="view[]" type="checkbox" {grouprow.VIEW_CHECKED} value="{grouprow.GROUP_ID}" /></td>
	<td class="row2 row-center"><input name="post[]" type="checkbox" {grouprow.POST_CHECKED} value="{grouprow.GROUP_ID}" /></td>
	<td class="row2 row-center"><input name="rate[]" type="checkbox" {grouprow.RATE_CHECKED} value="{grouprow.GROUP_ID}" /></td>
	<td class="row2 row-center"><input name="comment[]" type="checkbox" {grouprow.COMMENT_CHECKED} value="{grouprow.GROUP_ID}" /></td>
	<td class="row2 row-center"><input name="edit[]" type="checkbox" {grouprow.EDIT_CHECKED} value="{grouprow.GROUP_ID}" /></td>
	<td class="row2 row-center"><input name="delete[]" type="checkbox" {grouprow.DELETE_CHECKED} value="{grouprow.GROUP_ID}" /></td>
	<!--
	<td class="row2 row-center"><input name="approval[]" type="checkbox" {grouprow.APPROVAL_CHECKED} value="{grouprow.GROUP_ID}" /></td>
	<td class="row2 row-center"><input name="approval_edit[]" type="checkbox" {grouprow.APPROVAL_EDIT_CHECKED} value="{grouprow.GROUP_ID}" /></td>
	-->
	<td class="row2 row-center"><input name="moderator[]" type="checkbox" {grouprow.MODERATOR_CHECKED} value="{grouprow.GROUP_ID}" /></td>
</tr>
<!-- END grouprow -->
<tr>
	<td class="cat" align="center" colspan="8">
		<input type="reset" value="{L_RESET}" class="liteoption" />&nbsp;&nbsp;&nbsp;
		<input name="submit" type="submit" value="{L_SUBMIT}" class="mainoption" />
	</td>
</tr>
</table>
</form>

<br />