<h1>{L_ALICENSETITLE}</h1>
<p>{L_LICENSEEXPLAIN}</p>

<form action="{S_ADD_LIC_ACTION}" method="post">
<table class="forumline">
<tr><th colspan="2">{L_ALICENSETITLE}</th></tr>
<tr>
	<td class="row1 tw50pct"><strong>{L_LNAME}</strong></td>
	<td class="row2"><input type="text" class="post" size="50" name="license_name" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_LTEXT}</strong></td>
	<td class="row2"><textarea name="license_text" cols="50" rows="10" class="forminput"></textarea></td>
</tr>
<tr>
	<td class="cat tdalignc" colspan="2">
		<input class="liteoption" type="submit" value="{L_ALICENSETITLE}" name="B1" />
		<input type="hidden" name="action" value="admin" />
		<input type="hidden" name="ad" value="license" />
		<input type="hidden" name="license" value="add" />
		<input type="hidden" name="add" value="do" />
	</td>
</tr>
</table>
</form>
