
<h1>{L_AUTH_TITLE}</h1>
<p>{L_AUTH_EXPLAIN}</p>
<h2>{L_CATEGORY}<!-- IF CATEGORY_NAME neq '' --> : {CATEGORY_NAME}<!-- ENDIF --></h2>

<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
<!-- BEGIN pertype -->
	<th><a href="{pertype.U_NAME}">{pertype.L_NAME}</a></th>
<!-- END pertype -->
</tr>
</table>
<br />

<form method="post" action="{S_CATAUTH_ACTION}">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th>{L_CATEGORY}</th>
	<!-- BEGIN cat_auth_titles -->
	<th>{cat_auth_titles.CELL_TITLE}</th>
	<!-- END cat_auth_titles -->
</tr>
<!-- BEGIN cat_row -->
<tr>
	<!-- IF cat_row.IS_HIGHER_CAT -->
	<td class="row1" nowrap="nowrap">{cat_row.PRE}{NAV_SEP}<strong><a href="{cat_row.U_CAT}">{cat_row.CATEGORY_NAME}</a></strong></td>
	<!-- ELSE -->
	<td class="row1" nowrap="nowrap">{cat_row.PRE}{NAV_SEP}<a href="{cat_row.U_CAT}">{cat_row.CATEGORY_NAME}</a></td>
	<!-- ENDIF -->

	<!-- BEGIN cat_auth_data -->
	<!-- IF cat_row.IS_HIGHER_CAT -->
	<td class="row1 row-center" valign="center" nowrap="nowrap">{cat_row.cat_auth_data.S_AUTH_LEVELS_SELECT}</td>
	<!-- ELSE -->
	<td class="row1 row-center" valign="center" nowrap="nowrap">{cat_row.cat_auth_data.S_AUTH_LEVELS_SELECT}</td>
	<!-- ENDIF -->
	<!-- END cat_auth_data -->
</tr>
<!-- END cat_row -->
<tr>
	<td colspan="{S_COLUMN_SPAN}" class="cat" align="center">{S_HIDDEN_FIELDS}
		<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;
		<input type="reset" value="{L_RESET}" name="reset" class="liteoption" />
	</td>
</tr>
</table>
</form>
