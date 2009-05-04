<form action="{S_FORM_ACTION}" method="post" name="add_new_report">
{IMG_THL}{IMG_THC}<span class="forumlink">{L_ADD_REPORT}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1" width="20%"><span class="gen"><b>{L_REPORT_FILE}</b></span></td>
	<td class="row2" width="80%"><span class="gen">{S_SELECT_DOWNLOAD}</span>&nbsp;<span class="genmed">{L_REPORT_FILE_VER}</span>&nbsp;<input type="text" size="20" maxlength="50" class="post" name="report_file_ver" /></td>
</tr>
<tr>
	<td class="row1" width="20%"><span class="gen"><b>{L_REPORT_TITLE}</b></span></td>
	<td class="row2" width="80%"><input type="text" size="50" maxlength="255" class="post" name="report_title" /></td>
</tr>
<tr>
	<td class="row1" width="20%" valign="top"><span class="gen"><b>BBCode</b></span></td>
	<td class="row2" width="80%">
		<span class="genmed">
		<input type="button" class="button" name="addbbcode0" value=" b " style="font-weight: bold" onclick="bbstyle(0)" />
		<input type="button" class="button" name="addbbcode2" value=" i " style="font-style: italic" onclick="bbstyle(2)" />
		<input type="button" class="button" name="addbbcode4" value=" u " style="text-decoration: underline" onclick="bbstyle(4)" />
		<input type="button" class="button" name="addbbcode6" value="Quote" onclick="bbstyle(6)" />
		<input type="button" class="button" name="addbbcode8" value="Code" onclick="bbstyle(8)" />
		<input type="button" class="button" name="addbbcode10" value="IMG" onclick="bbstyle(10)" />
		<input type="button" class="button" name="addbbcode12" value="URL" onclick="bbstyle(12)" />
		<br />{L_FONT_COLOR}:
		<select name="addbbcode14" onChange="bbfontstyle('[color=' + this.form.addbbcode14.options[this.form.addbbcode14.selectedIndex].value + ']', '[/color]')" >
			<option style="color:darkred; background-color: darkred" value="darkred" class="genmed">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
			<option style="color:red; background-color: red" value="red" class="genmed">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
			<option style="color:orange; background-color: orange" value="orange" class="genmed">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
			<option style="color:brown; background-color: brown" value="brown" class="genmed">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
			<option style="color:yellow; background-color: yellow" value="yellow" class="genmed">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
			<option style="color:green; background-color: green" value="green" class="genmed">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
			<option style="color:olive; background-color: olive" value="olive" class="genmed">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
			<option style="color:cyan; background-color: cyan" value="cyan" class="genmed">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
			<option style="color:blue; background-color: blue" value="blue" class="genmed">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
			<option style="color:darkblue; background-color: darkblue" value="darkblue" class="genmed">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
			<option style="color:indigo; background-color: indigo" value="indigo" class="genmed">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
			<option style="color:violet; background-color: violet" value="violet" class="genmed">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
			<option style="color:white; background-color: white" value="white" class="genmed">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
			<option style="color:black; background-color: black" value="black" class="genmed">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
		</select>
		&nbsp;{L_FONT_SIZE}:
		<select name="addbbcode16" onChange="bbfontstyle('[size=' + this.form.addbbcode16.options[this.form.addbbcode16.selectedIndex].value + ']', '[/size]')" >
			<option value="1" class="genmed">size 1</option>
			<option value="2" class="genmed">size 2</option>
			<option value="3" selected class="genmed">size 3 (*)</option>
			<option value="4" class="genmed">size 4</option>
			<option  value="5" class="genmed">size 5</option>
			<option  value="6" class="genmed">size 6</option>
			<option  value="7" class="genmed">size 7</option>
		</select>
		&nbsp;<a href="javascript:bbstyle(-1)" class="genmed">{L_BBCODE_CLOSE_TAGS}</a></span>
	</td>
</tr>
<tr>
	<td class="row1" width="20%" valign="top"><span class="gen"><b>{L_REPORT_TEXT}</b></span></td>
	<td class="row2" width="80%"><textarea cols="75" rows="10" name="report_text" class="post"></textarea></td>
</tr>
<tr>
	<td class="row1" width="20%"><span class="gen"><b>{L_REPORT_PHP}</b></span></td>
	<td class="row2" width="80%"><input type="text" size="50" maxlength="255" class="post" name="report_php" /></td>
</tr>
<tr>
	<td class="row1" width="20%"><span class="gen"><b>{L_REPORT_DB}</b></span></td>
	<td class="row2" width="80%"><input type="text" size="50" maxlength="255" class="post" name="report_db" /></td>
</tr>
<tr>
	<td class="row1" width="20%"><span class="gen"><b>{L_REPORT_FORUM}</b></span></td>
	<td class="row2" width="80%"><input type="text" size="50" maxlength="255" class="post" name="report_forum" /></td>
</tr>
<tr>
	<td class="cat" colspan="2" align="center"><input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" /></td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}

{S_HIDDEN_FIELDS}
</form>
<br />
