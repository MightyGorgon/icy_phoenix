<!-- INCLUDE ../common/cms/page_header.tpl -->

<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1 row-center" width="100" valign="middle"><img src="{IP_ROOT_PATH}templates/common/images/cms/cms_users_auth.png" alt="{L_CMS_AUTH}" title="{L_CMS_AUTH}" /></td>
	<td class="row1" valign="top"><h1>{L_CMS_AUTH}</h1><span class="genmed">{L_CMS_AUTH_TEXT}</span></td>
</tr>
</table>

<form method="post" name="post" action="{S_AUTH_ACTION}">
<table width="100%" cellspacing="5" cellpadding="0">
	<tr>
		<td valign="top">
		<div class="cms_auth">
			<div class="cms_button" onclick="window.location.href='{U_AUTH_ADD}'">{L_CMS_AUTH_ADD}</div>
			<div class="box_title">{L_CMS_AUTH_XP}</div>
			<div style="clear: both;"></div>
			<!-- IF NO_AUTH -->
			{L_CMS_NO_AUTH}
			<!-- ELSE -->
			<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
			<tr>
				<th width="200">{L_CMS_USER}</th>
				<th>{L_CMS_AUTH}</th>
			</tr>
			<!-- BEGIN users -->
			<tr class="{users.ROW_CLASS} {users.ROW_CLASS}h" style="background-image: none;">
				<td class="{users.ROW_CLASS} row-center" style="background: none;"><b>{users.USERNAME}</b></td>
				<td class="{users.ROW_CLASS}" style="background: none;">
					{users.BUTTON}
					<!-- BEGIN auth -->
					<div class="{users.auth.AUTH_CLASS}">{users.auth.AUTH_CHECKBOX}&nbsp;{users.auth.AUTH_NAME}</div>
					<!-- END auth -->
				</td>
			</tr>
			<!-- END users -->
			</table>
			<!-- ENDIF -->
		</div>
		</td>
		<td width="50%" valign="top">
		<div class="cms_auth">
			<div class="cms_button" onclick="window.location.href='{U_AUTH_ADDROLE}'">{L_CMS_AUTH_ADD}</div>
			<div class="box_title">{L_CMS_ROLES}</div>
			<div style="clear:both"></div>
			<!-- IF NO_ROLE -->
			{L_CMS_NO_ROLE}<hr/>
			<!-- ELSE -->
			<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
			<tr>
				<th width="200">{L_CMS_USER}</th>
				<th>{L_CMS_ROLE}</th>
			</tr>
			<!-- BEGIN roles -->
			<tr class="{roles.ROW_CLASS} {roles.ROW_CLASS}h" style="background-image: none;">
				<td class="{roles.ROW_CLASS} row-center" style="background: none;"><b>{roles.USERNAME}</b></td>
				<td class="{roles.ROW_CLASS} row-center" style="background: none;">
					<div style="float: right;">{roles.BUTTON}</div>
					{roles.CMS_ROLES}
				</td>
			</tr>
			<!-- END roles -->
			</table>
			<!-- ENDIF -->
			<!-- BEGIN roles_desc -->
			<div style="margin-top: 3px;"><b>{roles_desc.ROLE_NAME}</b></div>
			<div class="gensmall">{roles_desc.ROLE_DESC}</div>
			<!-- END roles_desc -->
		</div>
		</td>
	</tr>
</table>
{S_HIDDEN_FIELDS}
</form>

<!-- INCLUDE ../common/cms/page_footer.tpl -->