<script type="text/javascript">
<!--
function emoticon(text)
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
		mozInsert(txtarea, text, "");
	}
	else
	{
		txtarea.value += text;
		txtarea.focus();
	}
}

function mozInsert(txtarea, openTag, closeTag)
{
	if (txtarea.selectionEnd > txtarea.value.length)
	{
		txtarea.selectionEnd = txtarea.value.length;
	}

	var startPos = txtarea.selectionStart;
	var endPos = txtarea.selectionEnd + openTag.length;

	txtarea.value = txtarea.value.slice(0, startPos) + openTag + txtarea.value.slice(startPos);
	txtarea.value = txtarea.value.slice(0, endPos) + closeTag + txtarea.value.slice(endPos);

	txtarea.selectionStart = startPos+openTag.length;
	txtarea.selectionEnd = endPos;
	txtarea.focus();
}

function SetSmileysPerPage()
{
	document.SmileysPerPage.submit();
	return true;
}

//-->
</script>

<form name="SmileysPerPage" method="post" action="{REQUEST_URI}">
<table width="100%" cellspacing="0" class="forumline">
<tr><td class="row-header"><span>{L_EMOTICONS}</span></td></tr>
<tr>
	<td class="row1 row-center" width="100%">
		<table width="100%" border="0" cellspacing="0" cellpadding="5">
		<!-- BEGIN smilies_row -->
		<tr>
		<!-- BEGIN smilies_col -->
			<td align="center" valign="middle">
				<img src="{smilies_row.smilies_col.SMILEY_IMG}" border="0" onmouseover="this.style.cursor='hand';" onclick="emoticon('{smilies_row.smilies_col.SMILEY_CODE}');" alt="{smilies_row.smilies_col.SMILEY_DESC}" title="{smilies_row.smilies_col.SMILEY_DESC}" />
			</td>
		<!-- END smilies_col -->
		</tr>
		<!-- END smilies_row -->
		<!-- BEGIN switch_smilies_extra -->
		<tr>
			<td align="center" colspan="{S_SMILIES_COLSPAN}">
				<span class="nav"><a href="{U_MORE_SMILIES}" onclick="open_window('{U_MORE_SMILIES}', 250, 300);return false" target="_smilies" class="nav">{L_MORE_SMILIES}</a></span>
			</td>
		</tr>
		<!-- END switch_smilies_extra -->
		</table>
	</td>
</tr>
<tr><td align="center"><span class="genmed">{L_SMILEYS_PER_PAGE}:&nbsp;{SELECT_SMILEYS_PP}</span></td></tr>
<tr><td align="center"><span class="genmed">{PAGINATION}</span></td></tr>
<tr><td class="cat"><span class="genmed"><a href="{U_SMILEYS_GALLERY}" class="genmed">{L_SMILEYS_GALLERY}</a></span></td></tr>
<tr><td class="cat"><span class="genmed"><a href="javascript:window.close();" class="genmed">{L_CLOSE_WINDOW}</a></span></td></tr>
</table>
</form>