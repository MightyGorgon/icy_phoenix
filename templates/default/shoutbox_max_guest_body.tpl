<!-- INCLUDE overall_header.tpl -->

<form action="{U_SHOUTBOX}" method="post" name="post" onsubmit="return checkForm(this)">
<table class="forumline">
<tr><td class="cat" colspan="2">{S_HIDDEN_FORM_FIELDS}<input type="submit" tabindex="1" name="refresh" class="mainoption" value="{L_SHOUT_REFRESH}" />&nbsp;</td></tr>
</table>
</form>

<table>
<tr>
	<td align="right" valign="bottom">
		<span class="gensmall">&nbsp;{PAGE_NUMBER}</span><br />
		<span class="pagination">{PAGINATION}</span>
	</td>
</tr>
</table>

<!-- INCLUDE shoutbox_inc.tpl -->

<!-- INCLUDE overall_footer.tpl -->