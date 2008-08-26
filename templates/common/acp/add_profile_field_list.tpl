<h1>{L_PROFILE_FIELD_LIST_TITLE}</h1>
<P>{L_PROFILE_FIELD_LIST_EXPLAIN}</p>

<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th width="5%">{L_ID}</th>
	<th>{L_NAME}</th>
	<th colspan="2">{L_ACTION}</th>
</tr>
<!-- BEGIN switch_no_fields -->
<tr><td class="row1" colspan="4">{switch_no_fields.NO_FIELDS_EXIST}</td></tr>
<!-- END switch_no_fields -->
<!-- BEGIN switch_fields -->
<!-- BEGIN profile_fields -->
<tr>
	<td class="{switch_fields.profile_fields.ROW_CLASS} row-center">{switch_fields.profile_fields.ID}</td>
	<td class="{switch_fields.profile_fields.ROW_CLASS}">{switch_fields.profile_fields.NAME}</td>
	<td class="{switch_fields.profile_fields.ROW_CLASS} row-center"><a href="{switch_fields.profile_fields.U_PROFILE_FIELD_EDIT}">{L_EDIT}</a></td>
	<td class="{switch_fields.profile_fields.ROW_CLASS} row-center"><a href="{switch_fields.profile_fields.U_PROFILE_FIELD_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- END profile_fields -->
<!-- END switch_fields -->
<tr><td class="cat" colspan="4" align="center">{S_HIDDEN_FIELDS}</td></tr>
</table>
