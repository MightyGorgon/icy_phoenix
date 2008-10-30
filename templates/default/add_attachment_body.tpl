<tr><th colspan="2">{L_ADD_ATTACH_TITLE}</th></tr>
<tr><td class="row1" colspan="2"><span class="gensmall">{L_ADD_ATTACH_EXPLAIN}<br />{RULES}</span></td></tr>
<tr>
	<td class="row1"><span class="gen"><b>{L_FILE_NAME}</b></span></td>
	<td class="row2"><span class="genmed"><input type="file" name="fileupload" size="40" maxlength="{FILESIZE}" style="width:98%" value="" class="post" /></span></td>
</tr>
<tr>
	<td class="row1" valign="top"><span class="gen"><b>{L_FILE_COMMENT}</b></span></td>
	<td class="row2">
		<span class="genmed"><textarea name="filecomment" rows="3" cols="50" wrap="virtual" style="width:98%" size="40" class="post">{FILE_COMMENT}</textarea></span><br />
		<input type="submit" name="add_attachment" value="{L_ADD_ATTACHMENT}" class="liteoption" />
	</td>
</tr>