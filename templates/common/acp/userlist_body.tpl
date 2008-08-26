<script type="text/javascript">
<!--

function handleClick(id) {
	var obj = "";

	// Check browser compatibility
	if(document.getElementById)
	{
		obj = document.getElementById(id);
	}
	else if(document.all)
	{
		obj = document.all[id];
	}
	else if(document.layers)
	{
		obj = document.layers[id];
	}
	else
	{
		return 1;
	}

	if (!obj)
	{
		return 1;
	}
	else if (obj.style)
	{
		obj.style.display = ( obj.style.display != "none" ) ? "none" : "";
	}
	else
	{
		obj.visibility = "show";
	}
}
//-->
</script>

<h1>{L_TITLE}</h1>

<p>{L_DESCRIPTION}</p>

<form action="{S_ACTION}" method="post">
<table width="100%" cellpadding="3" cellspacing="1" border="0">
<tr>
	<td width="100%">&nbsp;</td>
	<td align="right" nowrap="nowrap"><span class="gen">{L_SORT_BY}</td>
	<td nowrap="nowrap">
		<select name="sort" class="post">
			<option value="user_id">{L_USER_ID}</option>
			<option value="user_active">{L_ACTIVE}</option>
			<option value="username">{L_USERNAME}</option>
			<option value="user_regdate">{L_JOINED}</option>
			<option value="user_session_time">{L_ACTIVTY}</option>
			<option value="user_level">{L_USER_LEVEL}</option>
			<option value="user_posts">{L_POSTS}</option>
			<option value="user_rank">{L_RANK}</option>
			<option value="user_email">{L_EMAIL}</option>
			<option value="user_website">{L_WEBSITE}</option>
			<option value="user_birthday">{L_BIRTHDAY}</option>
			<option value="user_lang">{L_LANG}</option>
		</select>
	</td>
	<td nowrap="nowrap"><select name="order" class="post">
		<option value="ASC">{L_ASCENDING}</option>
		<option value="DESC">{L_DESCENDING}</option>
	</select></td>
	<td nowrap="nowrap"><span class="gen">{L_SHOW}</span></td>
	<td nowrap="nowrap"><input type="text" size="5" value="{S_SHOW}" name="show"></td>
	<td nowrap="nowrap">{S_HIDDEN_FIELDS}<input type="submit" value="{S_SORT}" name="change_sort" class="liteoption"></td>
</tr>
</table>
</form>

<table width="100%" cellspacing="2" cellpadding="2" border="0" align="center">
<tr>
	<!-- BEGIN alphanumsearch -->
	<td align="left" width="{alphanumsearch.SEARCH_SIZE}">
		<span class="genmed"><a href="{alphanumsearch.SEARCH_LINK}" class="genmed">{alphanumsearch.SEARCH_TERM}</a></span>
	</td>
	<!-- END alphanumsearch -->
</tr>
</table>

<form action="{S_ACTION}" method="post">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th width="15%">&nbsp;</th>
	<th width="20%">{L_USERNAME}</th>
	<th width="5%">{L_ACTIVE}</th>
	<th width="15%">{L_JOINED}</th>
	<th width="15%">{L_ACTIVTY}</th>
	<th width="15%">{L_BIRTHDAY}</th>
	<th width="15%">{L_LANG}</th>
</tr>
<!-- BEGIN user_row -->
<tr>
	<td class="row1" nowrap="nowrap"><input type="checkbox" name="{S_USER_VARIABLE}[]" value="{user_row.USER_ID}">&nbsp;&nbsp;&nbsp;<a href="javascript:handleClick('user{user_row.USER_ID}');">{L_OPEN_CLOSE}</a></td>
	<td class="row1"><span class="genmed">{user_row.USERNAME}</span></td>
	<td class="row1 row-center"><span class="genmed">{user_row.ACTIVE}</span></td>
	<td class="row1 row-center"><span class="genmed">{user_row.JOINED}</span></td>
	<td class="row1 row-center"><span class="genmed">{user_row.LAST_ACTIVITY}</span></td>
	<td class="row1 row-center"><span class="genmed">{user_row.BIRTHDAY}</span></td>
	<td class="row1 row-center"><span class="genmed">{user_row.USER_LANG}</span></td>
</tr>
<tr id="user{user_row.USER_ID}" style="display: none">
	<td class="row1" width="13%">&nbsp;</td>
	<td class="row1" colspan="6" width="100%">
		<table width="100%" cellpadding="3" cellspacing="1" border="0">
			<tr>
				<td class="row1" width="33%"><span class="genmed"><b>{L_RANK}:</b> {user_row.RANK} &nbsp; {user_row.I_RANK}</td>
				<td class="row1" width="34%"><span class="genmed"><b>{L_GROUP}:</b>
					<!-- BEGIN group_row -->
					<a href="{user_row.group_row.U_GROUP}" class="genmed" target="_blank">{user_row.group_row.GROUP_NAME}</a> ({user_row.group_row.GROUP_STATUS})<br />
					<!-- END group_row -->
					<!-- BEGIN no_group_row -->
					{user_row.no_group_row.L_NONE}<br />
					<!-- END no_group_row -->
				</span></td>
				<td class="row1" width="33%"><span class="genmed"><b>{L_POSTS}:</b> {user_row.POSTS} &nbsp; <a href="{user_row.U_SEARCH}" class="genmed" target="_blank">{L_FIND_ALL_POSTS}</a></span></td>
			</tr>
			<tr>
				<td class="row1" colspan="3"><span class="genmed"><b>{L_WEBSITE}:</b> <a href="{user_row.U_WEBSITE}" class="gen" target="_blank">{user_row.U_WEBSITE}</a></span></td>
			</tr>
			<tr>
				<td class="row1"><span class="genmed">
					<a href="{user_row.U_MANAGE}" class="genmed">{L_MANAGE}</a><br />
					<a href="{user_row.U_PERMISSIONS}" class="genmed">{L_PERMISSIONS}</a><br />
					<a href="mailto:{user_row.EMAIL}" class="genmed">{L_EMAIL} [ {user_row.EMAIL} ]</a><br />
					<a href="{user_row.U_PM}" class="genmed">{L_PM}</a>
				</span></td>
				<td colspan="2" class="{user_row.ROW_CLASS}">{user_row.I_AVATAR}</td>
			</tr>
		</table>
	</td>
</tr>
<!-- END user_row -->
<tr>
	<td class="cat" colspan="10">
		<select name="mode" class="post">
			<option value="">{L_SELECT}</option>
			<option value="delete">{L_DELETE}</option>
			<option value="ban">{L_BAN}</option>
			<option value="activate">{L_ACTIVATE_DEACTIVATE}</option>
			<option value="group">{L_ADD_GROUP}</option>
		</select>
		<input type="submit" name="go" value="{L_GO}" class="mainoption" />
	</td>
</tr>
</table>
</form>

<table width="100%" cellpadding="3" cellspacing="1" border="0">
<tr>
	<td align="left" width="50%"><span class="gensmall">{PAGE_NUMBER}</span></td>
	<td align="right" width="50%"><span class="pagination">{PAGINATION}</span></td>
</tr>
</table>

<br clear="all" />