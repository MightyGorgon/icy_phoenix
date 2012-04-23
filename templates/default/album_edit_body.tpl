<!-- INCLUDE overall_header.tpl -->

<script type="text/javascript">
// <![CDATA[
function checkAlbumForm()
{
	formErrors = false;

	if (document.editform.pic_title.value.length < 2)
	{
		formErrors = "{L_UPLOAD_NO_TITLE}";
	}
	else if (document.editform.pic_desc.value.length > {S_PIC_DESC_MAX_LENGTH})
	{
		formErrors = "{L_DESC_TOO_LONG}";
	}

	if (formErrors)
	{
		alert(formErrors);
		return false;
	}
	else
	{
		return true;
	}
}
// ]]>
</script>
<form name="editform" action="{S_ALBUM_ACTION}" method="post" onsubmit="return checkAlbumForm()">

{IMG_THL}{IMG_THC}<span class="forumlink">{L_EDIT_PIC_INFO}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="row1" width="30%" height="28"><span class="gen">{L_PIC_TITLE}</span></td>
		<td class="row2"><input class="post" type="text" name="pic_title" size="60" value="{PIC_TITLE}" /></td>
	</tr>
	<tr>
		<td class="row1" valign="top" width="30%" height="28"><span class="gen">{L_PIC_DESC}<br /></span><span class="genmed">{L_PLAIN_TEXT_ONLY}<br />{L_MAX_LENGTH}: <b>{S_PIC_DESC_MAX_LENGTH}</b></span></td>
		<td class="row2"><textarea class="post" cols="60" rows="4" name="pic_desc" style="width: 98%;">{PIC_DESC}</textarea></td>
	</tr>
	<tr>
		<td class="catBottom" align="center" height="28" colspan="2"><input type="reset" value="{L_RESET}" class="liteoption" />&nbsp;&nbsp;&nbsp;<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" /></td>
	</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</form>
<br />
<!-- You must keep my copyright notice visible with its original content -->
{ALBUM_COPYRIGHT}

<!-- INCLUDE overall_footer.tpl -->