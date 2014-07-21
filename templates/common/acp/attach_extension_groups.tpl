{GROUP_PERMISSIONS_BOX}
{PERM_ERROR_BOX}
<h1>{L_EXTENSION_GROUPS_TITLE}</h1>
<p>{L_EXTENSION_GROUPS_EXPLAIN}</p>
{ERROR_BOX}

<form method="post" action="{S_ATTACH_ACTION}">
<table class="forumline">
<tr><th colspan="8"><span class="cattitle">{L_EXTENSION_GROUPS_TITLE}</span></th></tr>
<tr>
	<th>&nbsp;{L_EXTENSION_GROUP}&nbsp;</th>
	<th>&nbsp;{L_SPECIAL_CATEGORY}&nbsp;</th>
	<th>&nbsp;{L_ALLOWED}&nbsp;</th>
	<th>&nbsp;{L_DOWNLOAD_MODE}&nbsp;</th>
	<th>&nbsp;{L_UPLOAD_ICON}&nbsp;</th>
	<th>&nbsp;{L_MAX_FILESIZE}&nbsp;</th>
	<th>&nbsp;{L_ALLOWED_FORUMS}&nbsp;</th>
	<th>&nbsp;{L_ADD_NEW}&nbsp;</th>
</tr>
<tr>
	<td class="row1 row-center tvalignm">
		<table>
		<tr>
			<td class="row1 row-center tvalignm" width="10%" wrap="nowrap">&nbsp;</td>
			<td class="row1" align="left" valign="middle"><input type="text" size="20" maxlength="100" name="add_extension_group" class="post" value="{ADD_GROUP_NAME}" /></td>
		</tr>
		</table>
	</td>
	<td class="row2 row-center tvalignm">{S_SELECT_CAT}</td>
	<td class="row1 row-center tvalignm"><input type="checkbox" name="add_allowed" /></td>
	<td class="row2 row-center tvalignm">{S_ADD_DOWNLOAD_MODE}</td>
	<td class="row1 row-center tvalignm"><input type="text" size="15" maxlength="100" name="add_upload_icon" class="post" value="{UPLOAD_ICON}" /></td>
	<td class="row2 row-center tvalignm"><input type="text" size="3" maxlength="15" name="add_max_filesize" class="post" value="{MAX_FILESIZE}" /> {S_FILESIZE}</td>
	<td class="row1 row-center tvalignm">&nbsp;</td>
	<td class="row2 row-center tvalignm"><input type="checkbox" name="add_extension_group_check" /></td>
</tr>
<tr><td class="cat" colspan="8" height="29"><input type="submit" name="submit" class="liteoption" value="{L_SUBMIT}" /></td></tr>
<tr>
	<th>&nbsp;{L_EXTENSION_GROUP}&nbsp;</th>
	<th>&nbsp;{L_SPECIAL_CATEGORY}&nbsp;</th>
	<th>&nbsp;{L_ALLOWED}&nbsp;</th>
	<th>&nbsp;{L_DOWNLOAD_MODE}&nbsp;</th>
	<th>&nbsp;{L_UPLOAD_ICON}&nbsp;</th>
	<th>&nbsp;{L_MAX_FILESIZE}&nbsp;</th>
	<th>&nbsp;{L_ALLOWED_FORUMS}&nbsp;</th>
	<th>&nbsp;{L_DELETE}&nbsp;</th>
</tr>
<!-- BEGIN grouprow -->
<tr>
	<input type="hidden" name="group_change_list[]" value="{grouprow.GROUP_ID}" />
	<td class="row1 row-center tvalignm">
		<table>
		<tr>
			<td class="row1 row-center tvalignm" width="10%" wrap="nowrap"><b><span class="gensmall"><a href="{grouprow.U_VIEWGROUP}" class="gensmall">{grouprow.CAT_BOX}</a></span></b></td>
			<td class="row1 tvalignm"><input type="text" size="20" maxlength="100" name="extension_group_list[]" class="post" value="{grouprow.EXTENSION_GROUP}" /></td>
		</tr>
		</table>
	</td>
	<td class="row2 row-center tvalignm">{grouprow.S_SELECT_CAT}</td>
	<td class="row1 row-center tvalignm"><input type="checkbox" name="allowed_list[]" value="{grouprow.GROUP_ID}" {grouprow.S_ALLOW_SELECTED} /></td>
	<td class="row2 row-center tvalignm">{grouprow.S_DOWNLOAD_MODE}</td>
	<td class="row1 row-center tvalignm"><input type="text" size="15" maxlength="100" name="upload_icon_list[]" class="post" value="{grouprow.UPLOAD_ICON}" /></td>
	<td class="row2 row-center tvalignm"><input type="text" size="3" maxlength="15" name="max_filesize_list[]" class="post" value="{grouprow.MAX_FILESIZE}" /> {grouprow.S_FILESIZE}</td>
	<td class="row1 row-center tvalignm"><span class="gensmall"><a href="{grouprow.U_FORUM_PERMISSIONS}" class="gensmall">{L_FORUM_PERMISSIONS}</a></span></td>
	<td class="row2 row-center tvalignm"><input type="checkbox" name="group_id_list[]" value="{grouprow.GROUP_ID}" /></td>
</tr>
<!-- BEGIN extensionrow -->

<tr>
	<td class="row2 row-center tvalignm"><span class="postdetails">{grouprow.extensionrow.EXTENSION}</span></td>
	<td class="row2 row-center tvalignm"><span class="postdetails">{grouprow.extensionrow.EXPLANATION}</span></td>
	<td class="row2 row-center tvalignm">&nbsp;</td>
	<td class="row2 row-center tvalignm">&nbsp;</td>
	<td class="row2 row-center tvalignm">&nbsp;</td>
	<td class="row2 row-center tvalignm">&nbsp;</td>
	<td class="row2 row-center tvalignm">&nbsp;</td>
	<td class="row2 row-center tvalignm">&nbsp;</td>
</tr>

<!-- END extensionrow -->
<!-- END grouprow -->

<tr>
	<td class="cat" colspan="8">
	<input type="submit" name="{L_CANCEL}" class="liteoption" value="{L_CANCEL}" onclick="self.location.href='{S_CANCEL_ACTION}'" />
	<input type="submit" name="submit" class="liteoption" value="{L_SUBMIT}" />
	</td>
</tr>
</table>

</form>

<br />
<div align="center"><span class="copyright">{ATTACH_VERSION}</span></div>

<br class="clear" />
