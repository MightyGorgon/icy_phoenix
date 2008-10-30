<form action="{S_ACTION}" name="upload_img" method="post" enctype="multipart/form-data">
{IMG_THL}{IMG_THC}<span class="forumlink">{L_UPLOAD_IMAGE}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="row1 row-center">
			<br /><br />
			{L_UPLOAD_IMAGE_EXPLAIN}&nbsp;<input type="file" class="post" name="userfile" maxlength="60" size="30" /><br />
			<span class="gensmall"><i>{L_ALLOWED_EXT}</i></span><br /><br />
		</td>
	</tr>
	<tr>
		<td class="cat" align="center">
			<input type="submit" class="mainoption" value="{L_SUBMIT}" />&nbsp;
			<input type="button" class="liteoption" value="{L_CLOSE_WINDOW}" onclick="window.close()" />
		</td>
	</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</form>