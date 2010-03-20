<script type="text/javascript">
<!--
function checkCategoryID()
{
	if ({CATEGORY_ID} == document.acp.cat_parent_id.value && {CATEGORY_ID} != {ROOT_CATEGORY_ID})
	{
		alert("{L_NO_SELF_REFERING}");
		return false;
	}
	else
	{
		switch (document.acp.cat_parent_id.value)
		{
			case '{ALBUM_ROOT_CATEGORY}':
			case '{ALBUM_PUBLIC_GALLERY}':
			case '{ALBUM_USERS_GALLERY}':
			case '{ALBUM_JUMPBOX_SEPARATOR}':
				alert("{L_NO_VALID_CAT_SELECTED}");
				return false;
			default:
		}
	}
}
// -->
</script>
<form name="acp" action="{S_ALBUM_ACTION}" method="post" onsubmit="return checkCategoryID()">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td class="nav" width="100%">
		<span class="nav">
			<a href="{U_PORTAL}" class="nav">{L_HOME}</a>
			{NAV_SEP}
			<a href="{U_INDEX}" class="nav">{L_INDEX}</a>
			{NAV_SEP}
			<a href="{U_ALBUM}" class="nav">{L_ALBUM}</a>
			{NAV_SEP}
			<a href="{U_PERSONAL_ALBUM}" class="nav">{L_PERSONAL_ALBUM}</a>
			{NAV_SEP}
			<a href="{U_PERSONAL_CAT_ADMIN}" class="nav">{L_PERSONAL_CAT_ADMIN}</a>
		</span>
	</td>
</tr>
</table>

<table class="forumline" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr><th colspan="2">{L_PERSONAL_CAT_ADMIN}</th></tr>
<tr>
	<td class="row1" colspan="2"><br />
		<table class="forumline" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><th nowrap="nowrap" colspan="2">{L_PANEL_TITLE}</th></tr>
		<tr>
			<td class="row1" width="20%"><span class="gen">{L_CAT_TITLE}:</span></td>
			<td class="row2"><input {READ_ONLY} name="cat_title" type="text" class="post" size="35" value="{S_CAT_TITLE}" /></td>
		</tr>
		<tr>
			<td valign="top" nowrap="nowrap" class="row1"><span class="gen">{L_CAT_DESC}:&nbsp;</span></td>
			<td class="row2"><textarea {READ_ONLY} name="cat_desc" class="post" cols="50" rows="5">{S_CAT_DESC}</textarea></td>
		</tr>
		<tr>
			<td valign="top" nowrap="nowrap" class="row1"><span class="gen">{L_CAT_PARENT_TITLE}:&nbsp;</span></td>
			<td class="row2"><select {DISABLED} name="cat_parent_id">{S_CAT_PARENT_OPTIONS}</select></td>
		</tr>
		<tr><th nowrap="nowrap" colspan="2">{L_CAT_PERMISSIONS}</th></tr>
		<tr>
			<td class="row1"><span class="gen">{L_VIEW_LEVEL}:</span></td>
			<td class="row2"><select name="cat_view_level"><option {VIEW_GUEST} value="{S_GUEST}">{L_GUEST}</option><option {VIEW_REG} value="{S_USER}">{L_REG}</option><option {VIEW_PRIVATE} value="{S_PRIVATE}">{L_PRIVATE}</option>
	<!-- BEGIN acp -->
			<option {VIEW_MOD} value="{S_MOD}">{L_MOD}</option><option {VIEW_ADMIN} value="{S_ADMIN}">{L_ADMIN}</option></select>
	<!-- END acp -->
			</td>
		</tr>
		<tr>
			<td class="row1"><span class="gen">{L_UPLOAD_LEVEL}:</span></td>
			<td class="row2"><select name="cat_upload_level"><option {UPLOAD_GUEST} value="{S_GUEST}">{L_GUEST}</option><option {UPLOAD_REG} value="{S_USER}">{L_REG}</option><option {UPLOAD_PRIVATE} value="{S_PRIVATE}">{L_PRIVATE}</option>
	<!-- BEGIN acp -->
			<option {UPLOAD_MOD} value="{S_MOD}">{L_MOD}</option><option {UPLOAD_ADMIN} value="{S_ADMIN}">{L_ADMIN}</option></select>
	<!-- END acp -->
			</td>
		</tr>
		<tr>
			<td class="row1"><span class="gen">{L_RATE_LEVEL}:</span></td>
			<td class="row2"><select name="cat_rate_level"><option {RATE_GUEST} value="{S_GUEST}">{L_GUEST}</option><option {RATE_REG} value="{S_USER}">{L_REG}</option><option {RATE_PRIVATE} value="{S_PRIVATE}">{L_PRIVATE}</option>
	<!-- BEGIN acp -->
			<option {RATE_MOD} value="{S_MOD}">{L_MOD}</option><option {RATE_ADMIN} value="{S_ADMIN}">{L_ADMIN}</option></select>
	<!-- END acp -->
			</td>
		</tr>
		<tr>
			<td class="row1"><span class="gen">{L_COMMENT_LEVEL}:</span></td>
			<td class="row2"><span class="gen"><select name="cat_comment_level"><option {COMMENT_GUEST} value="{S_GUEST}">{L_GUEST}</option><option {COMMENT_REG} value="{S_USER}">{L_REG}</option><option {COMMENT_PRIVATE} value="{S_PRIVATE}">{L_PRIVATE}</option>
	<!-- BEGIN acp -->
			<option {COMMENT_MOD} value="{S_MOD}">{L_MOD}</option><option {COMMENT_ADMIN} value="{S_ADMIN}">{L_ADMIN}</option></select></span>
	<!-- END acp -->
			</td>
		</tr>
	<!-- BEGIN acp -->
		<tr>
			<td class="row1"><span class="gen">{L_EDIT_LEVEL}:</span></td>
			<td class="row2"><select name="cat_edit_level"><option {EDIT_REG} value="{S_USER}">{L_REG}</option><option {EDIT_PRIVATE} value="{S_PRIVATE}">{L_PRIVATE}</option>
			<option {EDIT_MOD} value="{S_MOD}">{L_MOD}</option><option {EDIT_ADMIN} value="{S_ADMIN}">{L_ADMIN}</option></select></td>
		</tr>
		<tr>
			<td class="row1"><span class="gen">{L_DELETE_LEVEL}:</span></td>
			<td class="row2"><select name="cat_delete_level"><option {DELETE_REG} value="{S_USER}">{L_REG}</option><option {DELETE_PRIVATE} value="{S_PRIVATE}">{L_PRIVATE}</option><option {DELETE_MOD} value="{S_MOD}">{L_MOD}</option><option {DELETE_ADMIN} value="{S_ADMIN}">{L_ADMIN}</option></select></td>
		</tr>
		<tr>
			<td class="row1"><span class="gen">{L_PICS_APPROVAL}:</span></td>
			<td class="row2"><select name="cat_approval"><option {APPROVAL_DISABLED} value="{S_USER}">{L_DISABLED}</option><option {APPROVAL_MOD} value="{S_MOD}">{L_MOD}</option><option {APPROVAL_ADMIN} value="{S_ADMIN}">{L_ADMIN}</option></select></td>
		</tr>
	<!-- END acp -->
		<tr><td class="cat" align="center" height="28" colspan="2"><input type="hidden" value="{S_MODE}" name="mode" /><input name="submit" type="submit" value="{L_PANEL_TITLE}" class="liteoption" /></td></tr>
		</table>
	</td>
</tr>
</table>
</form>

<br />