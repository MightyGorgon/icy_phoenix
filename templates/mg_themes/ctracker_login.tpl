<!-- INCLUDE breadcrumbs_i.tpl -->
<br />
<form action="{S_FORM_ACTION}" method="post" target="_top">
<center>
{IMG_TBL}<table class="forumline" width="80%" cellspacing="0" cellpadding="0" border="0">
<tr><th height="25" nowrap="nowrap" colspan="2">{L_HEADER_TEXT}</th></tr>
<tr>
	<td class="row2" rowspan="2"><img src="{PAGE_ICON}" alt="{L_HEADER_TEXT}" title="{L_HEADER_TEXT}"></td>
	<td class="row1"><span class="gen">{L_DESCRIPTION}<br /><br /><br /><br /></span></td>
</tr>
<tr>
	<td class="row2 row-center">
		<br />
		{CONFIRM_IMAGE}
		{S_HIDDEN_FIELDS}
		<br /><br />
		<span class="gen">
			<input type="text" name="confirm_code" value="" class="post" />{NAV_SEP}<input type="submit" name="submit" value="{L_BUTTON_TEXT}" class="mainoption" />
		</span>
		<br /><br />
	</td>
</tr>
<tr><td class="row2" align="right" colspan="2">&nbsp;</td></tr>
</table>{IMG_TBR}
</center>
<br />
</form>