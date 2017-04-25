<h1>{L_MANAGE_QUOTAS_TITLE}</h1>
<p>{L_MANAGE_QUOTAS_EXPLAIN}</p>

<form method="post" action="{S_ATTACH_ACTION}">
<table class="forumline">
<tr><th colspan="5"><span class="cattitle">{L_MANAGE_QUOTAS_TITLE}</span></td></tr>
<tr>
	<th>{L_DESCRIPTION}</th>
	<th>{L_SIZE}</th>
	<th>{L_ADD_NEW}</th>
</tr>
<tr>
	<td class="row1 tvalignm">&nbsp;<input type="text" size="20" maxlength="25" name="quota_description" class="post" value="" /></td>
	<td class="row1 row-center tvalignm"><input type="text" size="8" maxlength="15" name="add_max_filesize" class="post" value="{MAX_FILESIZE}" /> {S_FILESIZE}</td>
	<td class="row1 row-center tvalignm"><input type="checkbox" name="add_quota_check" /></td>
</tr>
<tr><td class="cat" colspan="5">{S_HIDDEN_FIELDS}<input type="submit" name="submit" class="liteoption" value="{L_SUBMIT}" /></td></tr>
<tr>
	<th>{L_DESCRIPTION}</th>
	<th>{L_SIZE}</th>
	<th>{L_DELETE}</th>
</tr>
<!-- BEGIN limit_row -->
<tr>
	<td class="row1 tvalignm">
	<input type="hidden" name="quota_change_list[]" value="{limit_row.QUOTA_ID}" />
	&nbsp;<b><span class="gensmall"><a href="{limit_row.U_VIEW}" class="gensmall">{L_VIEW}</a></span></b>&nbsp;<input type="text" size="20" maxlength="25" name="quota_desc_list[]" class="post" value="{limit_row.QUOTA_NAME}" />
	</td>
	<td class="row1 row-center tvalignm"><input type="text" size="8" maxlength="15" name="max_filesize_list[]" class="post" value="{limit_row.MAX_FILESIZE}" /> {limit_row.S_FILESIZE}</td>
	<td class="row1 row-center tvalignm"><input type="checkbox" name="quota_id_list[]" value="{limit_row.QUOTA_ID}" /></td>
</tr>
<!-- END limit_row -->
<tr><td class="cat" colspan="5"> <input type="submit" name="submit" class="liteoption" value="{L_SUBMIT}" /></td></tr>
</table>
</form>

<!-- {QUOTA_LIMIT_SETTINGS} -->

<!-- BEGIN switch_quota_limit_desc -->
<div style="text-align: center;"><h1>{L_QUOTA_LIMIT_DESC}</h1></div>

<table class="tw99pct talignc"
<tr>
	<td align="left" width="49%">
		<table width="100%" class="forumline" cellspacing="1" cellpadding="4" border="0" align="left">
		<tr><th>{L_ASSIGNED_USERS} - {L_UPLOAD_QUOTA}</th></tr>
		<tr>
			<td class="row1 row-center">
				<select style="width:99%" name="entries[]" multiple="multiple" size="5" style="min-height: 200px;">
<!-- END switch_quota_limit_desc -->
				<!-- BEGIN users_upload_row -->
				<option value="{users_upload_row.USER_ID}">{users_upload_row.USERNAME}</option>
				<!-- END users_upload_row -->
<!-- BEGIN switch_quota_limit_desc -->
				</select>
			</td>
		</tr>
		</table>
	</td>
	<td width="2%">&nbsp;&nbsp;&nbsp;</td>
	<td align="right" width="49%">
		<table class="forumline">
		<tr><th>{L_ASSIGNED_GROUPS} - {L_UPLOAD_QUOTA}</th></tr>
		<tr>
			<td class="row1 row-center">
			<select style="width:99%" name="entries[]" multiple="multiple" size="5" style="min-height: 200px;">
<!-- END switch_quota_limit_desc -->
			<!-- BEGIN groups_upload_row -->
			<option value="{groups_upload_row.GROUP_ID}">{groups_upload_row.GROUPNAME}</option>
			<!-- END groups_upload_row -->
<!-- BEGIN switch_quota_limit_desc -->
			</select>
			</td>
		</tr>
		</table>
	</td>
</tr>
<tr>
	<td colspan="3">
		&nbsp;&nbsp;&nbsp;
	</td>
</tr>
<tr>
	<td align="left" width="49%">
		<table class="forumline">
		<tr><th>{L_ASSIGNED_USERS} - {L_PM_QUOTA}</th></tr>
		<tr>
			<td class="row1 row-center">
				<select style="width:99%" name="entries[]" multiple="multiple" size="5" style="min-height: 200px;">
<!-- END switch_quota_limit_desc -->
				<!-- BEGIN users_pm_row -->
				<option value="{users_pm_row.USER_ID}">{users_pm_row.USERNAME}</option>
				<!-- END users_pm_row -->
<!-- BEGIN switch_quota_limit_desc -->
				</select>
			</td>
		</tr>
		</table>
	</td>
	<td width="2%">
		&nbsp;&nbsp;&nbsp;
	</td>
	<td align="right" width="49%">
		<table class="forumline">
		<tr><th>{L_ASSIGNED_GROUPS} - {L_PM_QUOTA}</th></tr>
		<tr>
			<td class="row1 row-center">
			<select style="width:99%" name="entries[]" multiple="multiple" size="5" style="min-height: 200px;">
<!-- END switch_quota_limit_desc -->
			<!-- BEGIN groups_pm_row -->
			<option value="{groups_pm_row.GROUP_ID}">{groups_pm_row.GROUPNAME}</option>
			<!-- END groups_pm_row -->
<!-- BEGIN switch_quota_limit_desc -->
			</select>
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>
<!-- END switch_quota_limit_desc -->

<br />
<div align="center"><span class="copyright">{ATTACH_VERSION}</span></div>

<br class="clear" />
