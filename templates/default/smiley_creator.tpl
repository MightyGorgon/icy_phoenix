<!-- INCLUDE simple_header.tpl -->

<script type="text/javascript">
// <![CDATA[

function makeshield()
{
	var sm_id = '1';
	var text = document.shielderstellung.shieldtext.value;
	var color = document.shielderstellung.color.value;
	var shadowcolor = document.shielderstellung.shadowcolor.value;
	var shieldshadow = document.shielderstellung.shieldshadow.value;
	var text2form = '';

{SMILIES_JS}

	if (text)
	{
		if (sm_id == '')
		{
			text2form = '[smiley smilie=1 fontcolor='+color+' shadowcolor='+shadowcolor+' shieldshadow='+shieldshadow+']'+text+'[/smiley]';
		}
		else
		{
			text2form = '[smiley smilie='+sm_id+' fontcolor='+color+' shadowcolor='+shadowcolor+' shieldshadow='+shieldshadow+']'+text+'[/smiley]';
		}

		emoticon_sc(text2form);
		//opener.document.forms['post'].message.value += text2form;
		if (!confirm("{L_ANOTHER_SHIELD}"))
		{
			window.close();
			//opener.document.forms['post'].message.focus();
		}
		else
		{
			document.shielderstellung.reset();
		}
	}
	else
	{
		alert("{L_NOTEXT_ERROR}");
	}
}

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

// ]]>
</script>

<form name="shielderstellung">
{IMG_THL}{IMG_THC}<span class="forumlink">{L_SMILEY_CREATOR}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr id="tablec">
	<td class="row1"><span class="gen"><b>{L_SHIELDTEXT}:</b></span></td>
	<td class="row2"><input type="text" name="shieldtext" class="post" size="30" maxlength="396" /></td>
</tr>
<tr>
	<td class="row1"><span class="gen"><b>{L_FONTCOLOR}:</b></span></td>
	<td class="row2">
		<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td>
					<select name="color">
						<option style="color:#000000; background-color: white" value="000000" class="genmed">{L_COLOR_BLACK}</option>
						<option style="color:#AA3333; background-color: white" value="AA3333" class="genmed">{L_COLOR_BROWN}</option>
						<option style="color:#CC6622; background-color: white" value="CC6622" class="genmed">{L_COLOR_CHOCOLATE}</option>
						<option style="color:#800000; background-color: white" value="800000" class="genmed">{L_COLOR_DARK_RED}</option>
						<option style="color:#DD2244; background-color: white" value="DD2244" class="genmed">{L_COLOR_CRIMSON}</option>
						<option style="color:#FF0000; background-color: white" value="FF0000" class="genmed">{L_COLOR_RED}</option>
						<option style="color:#FF8866; background-color: white" value="FF8866" class="genmed">{L_COLOR_LIGHT_ORANGE}</option>
						<option style="color:#FF5500; background-color: white" value="FF5500" class="genmed">{L_COLOR_POWER_ORANGE}</option>
						<option style="color:#FF9900; background-color: white" value="FF9900" class="genmed">{L_COLOR_ORANGE}</option>
						<option style="color:#FFDD88; background-color: white" value="FFDD88" class="genmed">{L_COLOR_GOLD}</option>
						<option style="color:#FFDDBB; background-color: white" value="FFDDBB" class="genmed">{L_COLOR_PEACH}</option>
						<option style="color:#FFFF00; background-color: white" value="FFFF00" class="genmed">{L_COLOR_YELLOW}</option>
						<option style="color:#00FF00; background-color: white" value="00FF00" class="genmed">{L_COLOR_LIGHT_GREEN}</option>
						<option style="color:#669966; background-color: white" value="669966" class="genmed">{L_COLOR_SEA_GREEN}</option>
						<option style="color:#008000; background-color: white" value="008000" class="genmed">{L_COLOR_GREEN}</option>
						<option style="color:#808000; background-color: white" value="808000" class="genmed">{L_COLOR_OLIVE}</option>
						<option style="color:#006600; background-color: white" value="006600" class="genmed">{L_COLOR_DARKGREEN}</option>
						<option style="color:#DDEEFF; background-color: white" value="DDEEFF" class="genmed">{L_COLOR_LIGHT_CYAN}</option>
						<option style="color:#AACCEE; background-color: white" value="AACCEE" class="genmed">{L_COLOR_LIGHT_BLUE}</option>
						<option style="color:#6699AA; background-color: white" value="6699AA" class="genmed">{L_COLOR_CADET_BLUE}</option>
						<option style="color:#00FFFF; background-color: white" value="00FFFF" class="genmed">{L_COLOR_CYAN}</option>
						<option style="color:#666699; background-color: white" value="666699" class="genmed">{L_COLOR_TURQUOISE}</option>
						<option style="color:#0000FF; background-color: white" value="0000FF" class="genmed">{L_COLOR_BLUE}</option>
						<option style="color:#00BBFF; background-color: white" value="00BBFF" class="genmed">{L_COLOR_DEEPSKYBLUE}</option>
						<option style="color:#222266; background-color: white" value="222266" class="genmed">{L_COLOR_MIDNIGHTBLUE}</option>
						<option style="color:#000080; background-color: white" value="000080" class="genmed">{L_COLOR_DARK_BLUE}</option>
						<option style="color:#550088; background-color: white" value="550088" class="genmed">{L_COLOR_INDIGO}</option>
						<option style="color:#9933CC; background-color: white" value="9933CC" class="genmed">{L_COLOR_DARK_ORCHID}</option>
						<option style="color:#EE88EE; background-color: white" value="EE88EE" class="genmed">{L_COLOR_VIOLET}</option>
						<option style="color:#FFFFFF; background-color: white" value="FFFFFF" class="genmed">{L_COLOR_WHITE}</option>
						<option style="color:#CCCCCC; background-color: white" value="CCCCCC" class="genmed">{L_COLOR_LIGHT_GREY}</option>
						<option style="color:#BBBBBB; background-color: white" value="BBBBBB" class="genmed">{L_COLOR_SILVER}</option>
						<option style="color:#808080; background-color: white" value="808080" class="genmed">{L_COLOR_GRAY}</option>
						<option style="color:#555555; background-color: white" value="555555" class="genmed">{L_COLOR_DARK_GREY}</option>
					</select>
				</td>
			</tr>
		</table>
	</td>
</tr>
<tr>
	<td class="row1"><span class="gen"><b>{L_SHADOWCOLOR}:</b></span></td>
	<td class="row2">
		<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td>
					<select name="shadowcolor">
						<option style="color:#000000; background-color: white" value="000000" class="genmed">{L_COLOR_BLACK}</option>
						<option style="color:#AA3333; background-color: white" value="AA3333" class="genmed">{L_COLOR_BROWN}</option>
						<option style="color:#CC6622; background-color: white" value="CC6622" class="genmed">{L_COLOR_CHOCOLATE}</option>
						<option style="color:#800000; background-color: white" value="800000" class="genmed">{L_COLOR_DARK_RED}</option>
						<option style="color:#DD2244; background-color: white" value="DD2244" class="genmed">{L_COLOR_CRIMSON}</option>
						<option style="color:#FF0000; background-color: white" value="FF0000" class="genmed">{L_COLOR_RED}</option>
						<option style="color:#FF8866; background-color: white" value="FF8866" class="genmed">{L_COLOR_LIGHT_ORANGE}</option>
						<option style="color:#FF5500; background-color: white" value="FF5500" class="genmed">{L_COLOR_POWER_ORANGE}</option>
						<option style="color:#FF9900; background-color: white" value="FF9900" class="genmed">{L_COLOR_ORANGE}</option>
						<option style="color:#FFDD88; background-color: white" value="FFDD88" class="genmed">{L_COLOR_GOLD}</option>
						<option style="color:#FFDDBB; background-color: white" value="FFDDBB" class="genmed">{L_COLOR_PEACH}</option>
						<option style="color:#FFFF00; background-color: white" value="FFFF00" class="genmed">{L_COLOR_YELLOW}</option>
						<option style="color:#00FF00; background-color: white" value="00FF00" class="genmed">{L_COLOR_LIGHT_GREEN}</option>
						<option style="color:#669966; background-color: white" value="669966" class="genmed">{L_COLOR_SEA_GREEN}</option>
						<option style="color:#008000; background-color: white" value="008000" class="genmed">{L_COLOR_GREEN}</option>
						<option style="color:#808000; background-color: white" value="808000" class="genmed">{L_COLOR_OLIVE}</option>
						<option style="color:#006600; background-color: white" value="006600" class="genmed">{L_COLOR_DARKGREEN}</option>
						<option style="color:#DDEEFF; background-color: white" value="DDEEFF" class="genmed">{L_COLOR_LIGHT_CYAN}</option>
						<option style="color:#AACCEE; background-color: white" value="AACCEE" class="genmed">{L_COLOR_LIGHT_BLUE}</option>
						<option style="color:#6699AA; background-color: white" value="6699AA" class="genmed">{L_COLOR_CADET_BLUE}</option>
						<option style="color:#00FFFF; background-color: white" value="00FFFF" class="genmed">{L_COLOR_CYAN}</option>
						<option style="color:#666699; background-color: white" value="666699" class="genmed">{L_COLOR_TURQUOISE}</option>
						<option style="color:#0000FF; background-color: white" value="0000FF" class="genmed">{L_COLOR_BLUE}</option>
						<option style="color:#00BBFF; background-color: white" value="00BBFF" class="genmed">{L_COLOR_DEEPSKYBLUE}</option>
						<option style="color:#222266; background-color: white" value="222266" class="genmed">{L_COLOR_MIDNIGHTBLUE}</option>
						<option style="color:#000080; background-color: white" value="000080" class="genmed">{L_COLOR_DARK_BLUE}</option>
						<option style="color:#550088; background-color: white" value="550088" class="genmed">{L_COLOR_INDIGO}</option>
						<option style="color:#9933CC; background-color: white" value="9933CC" class="genmed">{L_COLOR_DARK_ORCHID}</option>
						<option style="color:#EE88EE; background-color: white" value="EE88EE" class="genmed">{L_COLOR_VIOLET}</option>
						<option style="color:#FFFFFF; background-color: white" value="FFFFFF" class="genmed">{L_COLOR_WHITE}</option>
						<option style="color:#CCCCCC; background-color: white" value="CCCCCC" class="genmed">{L_COLOR_LIGHT_GREY}</option>
						<option style="color:#BBBBBB; background-color: white" value="BBBBBB" class="genmed">{L_COLOR_SILVER}</option>
						<option style="color:#808080; background-color: white" value="808080" class="genmed">{L_COLOR_GRAY}</option>
						<option style="color:#555555; background-color: white" value="555555" class="genmed">{L_COLOR_DARK_GREY}</option>
					</select>
				</td>
			</tr>
		</table>
	</td>
</tr>
<tr>
	<td class="row1"><span class="gen"><b>{L_SHIELDSHADOW}:</b></span></td>
	<td class="row2">
		<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td>
					<select name="shieldshadow">
						<option value="1" class="genmed">{L_SHIELDSHADOW_ON}</option>
						<option value="0" class="genmed">{L_SHIELDSHADOW_OFF}</option>
					</select>
				</td>
			</tr>
		</table>
	</td>
</tr>
<tr>
	<td valign="top" class="row1"><span class="gen"><b>{L_SMILIECHOOSER}:</b></span></td>
	<td class="row2">
		<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr>
				{SMILIES_WAHL}
			</tr>
			<!--
			<tr>
				<td colspan="5">
					<input type="radio" name="smiley" value="random" checked><span class="gen">{L_RANDOM_SMILIE}</span>
				</td>
			</tr>
			<tr>
				<td colspan="5">
					<input type="radio" name="smiley" value="standard">
					<span class="gen">{L_DEFAULT_SMILIE}</span>
				</td>
			</tr>
			-->
		</table>
	</td>
</tr>
<tr><td class="spaceRow" colspan="6"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
<tr>
	<td class="catBottom" colspan="5" valign="middle">
		<span class="cattitle">
			<input type="button" class="button" value="{L_CREATE_SMILIE}" onclick="makeshield()" />
			<input type="button" class="button" value="{L_STOP_CREATING}" onclick="window.close()" />
		</span>
	</td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</form>

<!-- INCLUDE simple_footer.tpl -->