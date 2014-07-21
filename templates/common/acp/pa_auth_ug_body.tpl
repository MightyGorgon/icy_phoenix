<table class="forumline">
<tr>
<!-- BEGIN pertype -->
	<td class="cat" align="left" nowrap="nowrap"><a href="{pertype.U_NAME}">{pertype.L_NAME}</a></td>
<!-- END pertype -->
</tr>
</table>
<br />

<h1>{L_AUTH_TITLE}</h1>
<h2>{L_USER_OR_GROUPNAME}: {USERNAME}</h2>

<form method="post" action="{S_AUTH_ACTION}">
<!-- IF USER -->
<p>{USER_LEVEL}</p>
<p>{USER_GROUP_MEMBERSHIPS}</p>
<!-- ELSE -->
<p>{GROUP_MEMBERSHIP}</p>
<!-- ENDIF -->

<h2>{L_PERMISSIONS}</h2>
<p>{L_AUTH_EXPLAIN}</p>

<table class="forumline">
<tr>
	<th class="tw30pct">{L_CAT}</th>
	<!-- BEGIN acltype -->
	<th>{acltype.L_UG_ACL_TYPE}</th>
	<!-- END acltype -->
	<!-- IF SHOW_MOD -->
	<th>{L_MODERATOR_STATUS}</th>
	<!-- ENDIF -->
</tr>
<!-- BEGIN cat_row -->
<tr>
	<!-- IF cat_row.IS_HIGHER_CAT -->
		<td class="cat" nowrap="nowrap">{cat_row.PRE}{NAV_SEP}<a href="{cat_row.U_CAT}">{cat_row.CAT_NAME}</a></td>
	<!-- ELSE -->
		<td class="row1" nowrap="nowrap">{cat_row.PRE}{NAV_SEP}<a href="{cat_row.U_CAT}">{cat_row.CAT_NAME}</a></td>
	<!-- ENDIF -->

	<!-- BEGIN aclvalues -->
	<!-- IF cat_row.IS_HIGHER_CAT -->
	<td class="cat">{cat_row.aclvalues.S_ACL_SELECT}</td>
	<!-- ELSE -->
	<td class="row1 row-center">{cat_row.aclvalues.S_ACL_SELECT}</td>
	<!-- ENDIF -->
	<!-- END aclvalues -->
	<!-- IF SHOW_MOD -->
	<!-- IF cat_row.IS_HIGHER_CAT -->
	<td class="cat">{cat_row.S_MOD_SELECT}</td>
	<!-- ELSE -->
	<td class="row1 row-center">{cat_row.S_MOD_SELECT}</td>
	<!-- ENDIF -->
	<!-- ENDIF -->
</tr>
<!-- END cat_row -->
<tr>
	<td colspan="{S_COLUMN_SPAN}" class="cat">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" name="reset" /></td>
</tr>
</table>
</form>
