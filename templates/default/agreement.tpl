{ERROR_BOX}
<form method="post" action="{S_AGREE_ACTION}">
{IMG_THL}{IMG_THC}<span class="forumlink">{SITENAME} - {REGISTRATION}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1g" align="center">
		<table width="80%" cellspacing="2" cellpadding="2" border="0" align="center">
		<tr>
			<td align="left">
				<br /><div class="post-text"><span class="genmed">{AGREEMENT}</span></div><br /><br /><br />
				<br /><div class="post-text"><span class="genmed">{L_PRIVACY_DISCLAIMER}</span></div><br /><br /><br />
				<label><input type="checkbox" name="privacy" />&nbsp;{AGREE_CHECKBOX}</label><br /><br />
			</td>
		</tr>
		</table>
	</td>
</tr>
<tr>
	<td class="cat">
		{S_HIDDEN_FIELDS}
		<input type="submit" name="not_agreed" value="{DO_NOT_AGREE}" class="liteoption" />
		<input type="submit" name="agreed" value="{AGREE_OVER_13}" class="mainoption" />
	</td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</form>
