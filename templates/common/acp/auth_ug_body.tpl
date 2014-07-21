<script type="text/javascript" src="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}js/del_unchanged_selects.js"></script>

<h1>{L_AUTH_TITLE}</h1>
<h2>{L_USER_OR_GROUPNAME}: {USERNAME}</h2>

<form id="acl_form" method="post" action="{S_AUTH_ACTION}" onsubmit="remove_unchanged_selects()">

<!-- BEGIN switch_user_auth -->
<p>{USER_LEVEL}</p>
<p>{USER_GROUP_MEMBERSHIPS}</p>
<!-- END switch_user_auth -->

<!-- BEGIN switch_group_auth -->
<p>{GROUP_MEMBERSHIP}</p>
<!-- END switch_group_auth -->
<span class="pagination">{PAGINATION}</span>
<h2>{L_PERMISSIONS}</h2>

<p>{L_AUTH_EXPLAIN}</p>
<table class="forumline">
<tr>
	<th width="30%" colspan="{INC_SPAN}">{L_FORUM}</th>
	<!-- BEGIN acltype -->
	<th>{acltype.L_UG_ACL_TYPE}</th>
	<!-- END acltype -->
	<th>{L_MODERATOR_STATUS}</th>
</tr>
<!-- BEGIN row -->
<!-- BEGIN cathead -->
<tr>
	<!-- BEGIN inc -->
	<td class="row2 tw46px"><img src="{SPACER}" width="46" height="0" /></td>
	<!-- END inc -->
	<td colspan="{row.cathead.INC_SPAN}" class="{row.cathead.CLASS_CAT}" align="left" nowrap> <span class="cattitlemed">{row.cathead.CAT_TITLE}</span></td>
	<!-- BEGIN aclvalues -->
	<td class="{row.cathead.CLASS_CAT}" align="left" nowrap><span class="cattitlemed">&nbsp;</span></td>
	<!-- END aclvalues -->
	<td class="{row.cathead.CLASS_CAT}" align="left" nowrap><span class="cattitlemed">&nbsp;</span></td>
</tr>
<!-- END cathead -->
<!-- BEGIN forums -->
<tr>
	<!-- BEGIN inc -->
	<td class="row2 tw46px"><img src="{SPACER}" width="46" height="0" /></td>
	<!-- END inc -->
	<td class="row1" align="left" colspan="{row.forums.INC_SPAN}"><span class="gen">{row.forums.FORUM_NAME}</span></td>
	<!-- BEGIN aclvalues -->
	<td class="row2 row-center">{row.forums.aclvalues.S_ACL_SELECT}</td>
	<!-- END aclvalues -->
	<td class="row1 row-center">{row.forums.S_MOD_SELECT}</td>
</tr>
<!-- END forums -->
<!-- END row -->
<tr><td colspan="{S_COLUMN_SPAN}" class="row1 row-center"><span class="gensmall">{U_SWITCH_MODE}</span></td></tr>
<tr>
	<td colspan="{S_COLUMN_SPAN}" class="cat" align="center">{S_HIDDEN_FIELDS}
	<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />
	&nbsp;&nbsp;
	<input type="reset" value="{L_RESET}" class="liteoption" name="reset" />
	</td>
</tr>
</table>
</form>
