<h1>{L_ELICENSETITLE}</h1>
<p>{L_LICENSEEXPLAIN}</p>

<!-- BEGIN license_form -->
<form action="{S_EDIT_LIC_ACTION}" method="post">
<table class="forumline">
<tr><th colspan="2">{L_ELICENSETITLE}</b></th></tr>
<tr>
	<td class="row1 tw50pct"><strong>{L_LNAME}</strong></td>
	<td class="row2"><input type="text" class="post" size="50" name="license_name" value="{LICENSE_NAME}" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_LTEXT}</strong></td>
	<td class="row2"><textarea name="license_text" cols="50" rows="10" class="post">{TEXT}</textarea></td>
</tr>
<tr>
	<td class="cat tdalignc" colspan="2"><input class="liteoption" type="submit" value="{L_ELICENSETITLE}" name="B1"><input type="hidden" name="action" value="admin"><input type="hidden" name="ad" value="license"><input type="hidden" name="license" value="edit"><input type="hidden" name="edit" value="do"><input type="hidden" name="id" value="{SELECT}"></td>
</tr>
</table>
</form>
<!-- END license_form -->

<!-- BEGIN license -->
<form action="{S_EDIT_LIC_ACTION}" method="post">
<table class="forumline">
<tr><th colspan="2">{L_ELICENSETITLE}</th></tr>
{ROW}
<tr>
	<td class="cat tdalignc" colspan="2">
		<input class="liteoption" type="submit" value="{L_ELICENSETITLE}" name="B1" />
		<input type="hidden" name="action" value="admin" />
		<input type="hidden" name="ad" value="license" />
		<input type="hidden" name="license" value="edit" />
		<input type="hidden" name="edit" value="form" />
	</td>
</tr>
</table>
</form>
<!-- END license -->
