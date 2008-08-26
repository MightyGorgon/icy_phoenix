<h1>{L_CONFIGURATION_TITLE}</h1>
<p>{L_CONFIGURATION_EXPLAIN}</p>

<form action="{S_ACTION}" method="post">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><th colspan="2">{L_CONFIGURATION_TITLE}</th></tr>
<tr>
	<td class="row1" width="50%">{L_NEW_NAME}<br /><span class="gensmall">{L_NEW_EXPLAIN}</span></td>
	<td class="row2" width="50%">
		<input type="radio" name="allow_new" value="1" {S_NEW_YES} /> {L_YES}&nbsp;&nbsp;
		<input type="radio" name="allow_new" value="0" {S_NEW_NO} /> {L_NO}
	</td>
</tr>
<!--
<tr>
	<td class="row1" width="50%">{L_APPROVE_NEW_NAME}<br /><span class="gensmall">{L_APPROVE_NEW_EXPLAIN}</span></td>
	<td class="row2" width="50%"><input type="radio" name="approve_new" value="1" {S_APPROVE_NEW_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="approve_new" value="0" {S_APPROVE_NEW_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1" width="50%">{L_EDIT_NAME}<br /><span class="gensmall">{L_EDIT_EXPLAIN}</span></td>
	<td class="row2" width="50%"><input type="radio" name="allow_edit" value="1" {S_EDIT_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_edit" value="0" {S_EDIT_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1" width="50%">{L_APPROVE_EDIT_NAME}<br /><span class="gensmall">{L_APPROVE_EDIT_EXPLAIN}</span></td>
	<td class="row2" width="50%"><input type="radio" name="approve_edit" value="1" {S_APPROVE_EDIT_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="approve_edit" value="0" {S_APPROVE_EDIT_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1" width="50%">{L_ANON_NAME}<br /><span class="gensmall">{L_ANON_EXPLAIN}</span></td>
	<td class="row2" width="50%"><input type="radio" name="allow_anon" value="1" {S_ANON_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_anon" value="0" {S_ANON_NO} /> {L_NO}</td>
</tr>
-->
<tr>
	<td class="row1" width="50%">{L_NOTIFY_NAME}<br /><span class="gensmall">{L_NOTIFY_EXPLAIN}</span></td>
	<td class="row2" width="50%"><input type="radio" name="notify" value="0" {S_NOTIFY_NONE} />{L_NONE}&nbsp; &nbsp;<input type="radio" name="notify" value="2" {S_NOTIFY_EMAIL} />{L_EMAIL}&nbsp; &nbsp;<input type="radio" name="notify" value="1" {S_NOTIFY_PM} />{L_PM}</td>
</tr>
<tr>
	<td class="row1" width="50%">{L_ADMIN_ID_NAME}<br /><span class="gensmall">{L_ADMIN_ID_EXPLAIN}</span></td>
	<td class="row2" width="50%"><input class="post" type="text" name="admin_id" value="{ADMIN_ID}" size="5" maxlength="4" /></td>
</tr>
<!--
<tr>
	<td class="row1" width="50%">{L_COMMENTS}<br /><span class="gensmall">{L_COMMENTS_EXPLAIN}</span></td>
	<td class="row2" width="50%"><input type="radio" name="comments" value="1" {S_COMMENTS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="comments" value="0" {S_COMMENTS_NO} /> {L_NO}</td>
</tr>
-->
<tr>
	<td class="row1" width="50%">{L_FORUM_ID}<br /><span class="gensmall">{L_FORUM_ID_EXPLAIN}</span></td>
	<td class="row2" width="50%">{FORUMS}</td>
</tr>
<tr>
	<td class="row1" width="50%">{L_DEL_TOPIC}<br /><span class="gensmall">{L_DEL_TOPIC_EXPLAIN}</span></td>
	<td class="row2" width="50%"><input type="radio" name="del_topic" value="1" {S_DEL_TOPIC_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="del_topic" value="0" {S_DEL_TOPIC_NO} /> {L_NO}</td>
</tr>
<tr>
	<th colspan="2">{L_PRE_TEXT_NAME}</th>
</tr>
<tr>
	<td class="row1" width="50%">{L_PRE_TEXT_NAME}<br /><span class="gensmall">{L_PRE_TEXT_EXPLAIN}</span></td>
	<td class="row2" width="50%"><input type="radio" name="show_pretext" value="1" {S_SHOW_PRETEXT} /> {L_SHOW}&nbsp;&nbsp;<input type="radio" name="show_pretext" value="0" {S_HIDE_PRETEXT} /> {L_HIDE}</td>
</tr>
<tr>
	<td class="row1" width="50%">{L_PRE_TEXT_HEADER}</td>
	<td class="row2" width="50%"><input text="text" name="pt_header" value="{L_PT_HEADER}" size="40" maxlength="100" /></td>
</tr>
<tr>
	<td class="row1" width="50%">{L_PRE_TEXT_BODY}</td>
	<td class="row2" width="50%"><textarea name="pt_body" cols="40" rows="5">{L_PT_BODY}</textarea></td>
</tr>
<tr>
	<td class="cat" colspan="2" align="center">
		{S_HIDDEN_FIELDS}
		<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;
		<input type="reset" value="{L_RESET}" class="liteoption" />
	</td>
</tr>
</table>
</form>
<br clear="all" />