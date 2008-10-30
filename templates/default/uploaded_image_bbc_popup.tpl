<script type="text/javascript">
<!--
function emoticon_sc(text)
{
	var txtarea = opener.document.forms['post'].message;
	text = ' ' + text + ' ';
	if (txtarea.createTextRange && txtarea.caretPos)
	{
		if (opener.baseHeight != txtarea.caretPos.boundingHeight)
		{
			txtarea.focus();
			opener.storeCaret(txtarea);
		}
		var caretPos = txtarea.caretPos;
		caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? text + ' ' : text;
		txtarea.focus();
	}
	else if ((txtarea.selectionEnd | txtarea.selectionEnd == 0) && (txtarea.selectionStart | txtarea.selectionStart == 0))
	{
		mozInsert_sc(txtarea, text, "");
	}
	else
	{
		txtarea.value += text;
		txtarea.focus();
	}
}

function mozInsert_sc(txtarea, openTag, closeTag)
{
	if (txtarea.selectionEnd > txtarea.value.length)
	{
		txtarea.selectionEnd = txtarea.value.length;
	}

	var startPos = txtarea.selectionStart;
	var endPos = txtarea.selectionEnd + openTag.length;

	txtarea.value = txtarea.value.slice(0, startPos) + openTag + txtarea.value.slice(startPos);
	txtarea.value = txtarea.value.slice(0, endPos) + closeTag + txtarea.value.slice(endPos);

	txtarea.selectionStart = startPos + openTag.length;
	txtarea.selectionEnd = endPos;
	txtarea.focus();
}

//-->
</script>
<form action="{S_ACTION}" name="select_all" method="post" enctype="multipart/form-data">
{IMG_THL}{IMG_THC}<span class="forumlink">{L_UPLOAD_IMAGE}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="row1 row-center">
			<br /><br />
			{L_BBCODE_DES}<br /><br />
			<b>{L_BBCODE}</b>:&nbsp;<input class="post" name="BBCode" size="80" maxlength="200" value="{IMG_BBCODE}" type="text" readonly="readonly" onClick="javascript:this.form.BBCode.focus(); this.form.BBCode.select();" />
			<br /><br />
		</td>
	</tr>
	<tr>
		<td class="cat" align="center">
			<input type="button" class="mainoption" value="{L_INSERT_BBC}" onclick="emoticon_sc(this.form.BBCode.value)" />&nbsp;
			<input type="button" class="liteoption" value="{L_CLOSE_WINDOW}" onclick="window.close()" />
		</td>
	</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</form>