<h1>{L_EXTENSIONS_TITLE}</h1>
<p>{L_EXTENSIONS_EXPLAIN}</p>
{ERROR_BOX}

<form method="post" action="{S_ATTACH_ACTION}">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><th colspan="5"><span class="cattitle">{L_EXTENSIONS_TITLE}</span></th></tr>
<tr>
	<th>&nbsp;{L_EXTENSION}&nbsp;</th>
	<th>&nbsp;{L_ADD_NEW}&nbsp;</th>
</tr>
<tr>
	<td class="row1 row-center" valign="middle"><input type="text" size="8" maxlength="15" name="add_extension" class="post" value=""/></td>
	<td class="row2 row-center" valign="middle"><input type="checkbox" name="add_extension_check" /></td>
</tr>
<tr align="right"><td class="cat" colspan="5" height="29"> {S_HIDDEN_FIELDS} <input type="submit" name="submit" class="liteoption" value="{L_SUBMIT}" /></td></tr>
<tr>
	<th>&nbsp;{L_EXTENSION}&nbsp;</th>
	<th>&nbsp;{L_DELETE}&nbsp;</th>
</tr>
<!-- BEGIN extensionrow -->
<tr>
	<td class="row1 row-center" valign="middle"><span class="postdetails">{extensionrow.EXTENSION_NAME}</span></td>
	<td class="row2 row-center" valign="middle"><input type="checkbox" name="extension_id_list[]" value="{extensionrow.EXTENSION_ID}" /></td>
</tr>
<!-- END extensionrow -->
<tr><td class="cat" colspan="5"><input type="submit" name="submit" class="liteoption" value="{L_SUBMIT}" /></td></tr>
</table>

</form>

<br />
<div align="center"><span class="copyright">{ATTACH_VERSION}</span></div>

<br clear="all" />
