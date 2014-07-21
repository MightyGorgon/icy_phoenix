<h1>{L_USER_TITLE}</h1>
<p>{L_USER_EXPLAIN}</p>

<form action="{S_ACP_PROFILE_ACTION}" method="post">
<table class="forumline">
<tr><th colspan="{S_COLSPAN}" height="25" valign="middle">{L_AVATAR_GALLERY}</th></tr>
<tr>
	<td class="cat tdalignc tvalignm" colspan="6" height="28"><span class="genmed">{L_CATEGORY}:&nbsp;{S_CATEGORY_SELECT}&nbsp;<input type="submit" class="liteoption" value="{L_GO}" name="avatargallery" /></span></td>
</tr>
<!-- BEGIN avatar_row -->
<tr>
<!-- BEGIN avatar_column -->
	<td class="row1 row-center"><img src="{avatar_row.avatar_column.AVATAR_IMAGE}" /></td>
<!-- END avatar_column -->
</tr>
<tr>
<!-- BEGIN avatar_option_column -->
	<td class="row2 row-center"><input type="radio" name="avatarselect" value="{avatar_row.avatar_option_column.S_OPTIONS_AVATAR}" /></td>
<!-- END avatar_option_column -->
</tr>

<!-- END avatar_row -->
<tr>
	<td class="cat tdalignc" colspan="{S_COLSPAN}">{S_HIDDEN_FIELDS}
	<input type="submit" name="submitavatar" value="{L_SELECT_AVATAR}" class="mainoption" />&nbsp;&nbsp;
	<input type="submit" name="cancelavatar" value="{L_RETURN_PROFILE}" class="liteoption" />
	</td>
</tr>
</table>
</form>
