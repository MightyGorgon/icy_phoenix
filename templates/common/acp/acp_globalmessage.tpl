<h1>{L_HEADLINE}</h1>
<p>{L_SUBHEADLINE}</p>

<br />

<script type="text/javascript">
<!--
	var text1 = "{L_MSG_LINK}";
	var text2 = "{L_MSG_TXT}";

	function change_field_desc ( onoff )
	{
		if ( onoff == 1 )
		{
			document.getElementById("field_desc").innerHTML = text1;
		}
		else
		{
			document.getElementById("field_desc").innerHTML = text2;
		}
	}
//-->
</script>

<form action="{S_FORM_ACTION}" method="post">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><th colspan="2">{L_GLOBALMSG}</th></tr>
<tr>
	<td class="row2 row-center" width="20%" style="vertical-align:top;"><img src="{IMG_ICON_GLOB_MSG}" alt="{L_HEADLINE}" title="{L_HEADLINE}" border="0"></td>
	<td class="row1" width="80%" style="vertical-align:top;">
		<table border="0" cellspacing="4" cellpadding="4" width="100%">
		<tr>
			<td width="40%" align="right" style="vertical-align:top;"><b>{L_MSG_TYPE} :</b></td>
			<td width="60%" align="left" style="vertical-align:top;"><input type="radio" name="global_message_type" onchange="change_field_desc(0)" value="1"{S_CHK_STATUS_1}>{L_MSG_TYPE_1} <input type="radio" name="global_message_type" onchange="change_field_desc(1)" value="0"{S_CHK_STATUS_2}>{L_MSG_TYPE_2}</td>
		</tr>
		<tr>
			<td width="40%" align="right" style="vertical-align:top;"><b><span id="field_desc">{L_FIELD_DESC}</span> :</b></td>
			<td width="60%" align="left" style="vertical-align:top;"><textarea name="global_message" maxlength="255" rows="10" cols="56">{S_CURRENT_TEXT}</textarea></td>
		</tr>
		</table>
	</td>
</tr>
<tr><td class="cat" colspan="2" align="center"><input type="Submit" name="submit" value="{L_SUBMIT}" class="mainoption"></td></tr>
</table>

<br /><br />

<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><th colspan="2">{L_GLOBALMSG_RESET}</th></tr>
<tr>
	<td class="row2 row-center" width="20%" style="vertical-align:top;"><img src="{IMG_ICON_GLOB_RES}" alt="{L_GLOBALMSG_RESET}" title="{L_GLOBALMSG_RESET}" border="0"></td>
	<td class="row1" width="80%" style="vertical-align:top;">{L_GLOB_RESET_TXT}</td>
</tr>
<tr><td class="cat" colspan="2" align="center"><input type="Submit" name="pull_back" value="{L_RESET}" class="mainoption"></td></tr>
</table>
</form>