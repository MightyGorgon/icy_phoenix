<!-- INCLUDE ../common/cms/page_header.tpl -->

<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1 row-center c-r-l" width="100" valign="middle"><img src="{IP_ROOT_PATH}templates/common/images/cms/cms_users_auth.png" alt="{L_CMS_AUTH}" title="{L_CMS_AUTH}" /></td>
	<td class="row1 c-r-r" valign="top"><h1>{L_CMS_AUTH}</h1><span class="genmed">{L_CMS_AUTH_TEXT}</span></td>
</tr>
</table>

<form method="post" name="post" action="{S_AUTH_ACTION}">
<table width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td width="50%" valign="top" style="width: 50%; vertical-align: top; padding-right: 5px;">
		<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
		<tr><th colspan="2">{L_CMS_AUTH_XP}</th></tr>
		<tr>
			<!-- IF NO_AUTH -->
			<td class="row1 row-center" colspan="2">{L_CMS_NO_AUTH}</td>
			<!-- ELSE -->
			<td class="row1 row-center">
				<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<th width="200">{L_CMS_USER}</th>
					<th>{L_CMS_AUTH}</th>
				</tr>
				<!-- BEGIN users -->
				<tr class="{users.ROW_CLASS} {users.ROW_CLASS}h">
					<td class="{users.ROW_CLASS} row-center"><b>{users.USERNAME}</b><br /><br />{users.BUTTON}</td>
					<td class="{users.ROW_CLASS}">
						<!-- BEGIN auth -->
						<div class="{users.auth.AUTH_CLASS}">{users.auth.AUTH_CHECKBOX}&nbsp;{users.auth.AUTH_NAME}</div>
						<!-- END auth -->
					</td>
				</tr>
				<!-- END users -->
				</table>
			</td>
			<!-- ENDIF -->
		</tr>
		<tr><td class="spaceRow" colspan="2"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
		<tr><td class="cat" colspan="2" align="center"><a class="cms-button-small" onclick="window.location.href='{U_AUTH_ADD}'" href="javascript:void(0);">{L_CMS_AUTH_ADD}</a></td></tr>
		</table>
	</td>
	<td width="50%" valign="top" style="width: 50%; vertical-align: top; padding-left: 5px;">
		<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
		<tr><th colspan="2">{L_CMS_ROLES}</th></tr>
		<tr>
			<!-- IF NO_ROLE -->
			<td class="row1 row-center" colspan="2">{L_CMS_NO_AUTH}</td>
			<!-- ELSE -->
			<td class="row1 row-center">
				<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<th width="200">{L_CMS_USER}</th>
					<th>{L_CMS_ROLE}</th>
				</tr>
				<!-- BEGIN roles -->
				<tr class="{roles.ROW_CLASS} {roles.ROW_CLASS}h">
					<td class="{roles.ROW_CLASS} row-center"><b>{roles.USERNAME}</b></td>
					<td class="{roles.ROW_CLASS} row-center">
						<div style="float: right;">{roles.BUTTON}</div>
						<b>{roles.CMS_ROLES}</b>
					</td>
				</tr>
				<!-- END roles -->
				</table>
				<!-- BEGIN roles_desc -->
				<div style="margin-top: 3px; text-align: left;"><b>{roles_desc.ROLE_NAME}</b>: <span class="gensmall">{roles_desc.ROLE_DESC}</span></div>
				<!-- END roles_desc -->
			</td>
			<!-- ENDIF -->
		</tr>
		<tr><td class="spaceRow" colspan="2"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
		<tr><td class="cat" colspan="2" align="center"><a class="cms-button-small" onclick="window.location.href='{U_AUTH_ADDROLE}'" href="javascript:void(0);">{L_CMS_AUTH_ADD}</a></td></tr>
		</table>
	</td>
</tr>
</table>
{S_HIDDEN_FIELDS}
</form>

<!-- INCLUDE ../common/cms/page_footer.tpl -->