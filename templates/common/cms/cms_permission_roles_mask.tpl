<!-- BEGIN role_mask -->

<table class="forumline">
<tr><th>{L_USERS} {role_mask.NAME}</th></tr>
<tr>
	<td class="row1">
		<!-- BEGIN users -->
			{role_mask.users.USERNAME_FULL}<!-- IF not role_mask.users.S_LAST_ROW --> &bull; <!-- ENDIF -->
		<!-- BEGINELSE -->
			{L_USERS_NOT_ASSIGNED}
		<!-- END users -->
	</td>
</tr>
<tr><th>{L_GROUPS} {role_mask.NAME}</th></tr>
<tr>
	<td class="row2">
		<!-- BEGIN groups -->
			{role_mask.groups.GROUP_FULL}<!-- IF not role_mask.groups.S_LAST_ROW --> &bull; <!-- ENDIF -->
		<!-- BEGINELSE -->
			{L_GROUPS_NOT_ASSIGNED}
		<!-- END groups -->
	</td>
</tr>
</table>

<!-- BEGINELSE -->

<p>{L_ROLE_NOT_ASSIGNED}</p>

<!-- END role_mask -->