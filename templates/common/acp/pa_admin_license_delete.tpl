<h1>{L_DLICENSETITLE}</h1>
<p>{L_LICENSEEXPLAIN}</p>

<form action="{S_DELETE_LIC_ACTION}" method="post">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><th colspan="2">{L_DLICENSETITLE}</th></tr>
{ROW}
<tr>
	<td align="center" class="cat" colspan="2">
		<input class="liteoption" type="submit" value="{L_DLICENSETITLE}" name="B1" />
		<input type="hidden" name="action" value="admin" />
		<input type="hidden" name="ad" value="license" />
		<input type="hidden" name="license" value="delete" />
		<input type="hidden" name="delete" value="do" />
	</td>
</tr>
</table>
</form>
