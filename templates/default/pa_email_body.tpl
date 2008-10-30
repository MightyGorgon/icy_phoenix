<script type="text/javascript">
<!--
function checkForm(formObj) {

	formErrors = false;

	if (formObj.message.value.length < 2) {
		formErrors = "{L_EMPTY_MESSAGE_EMAIL}";
	}
	else if ( formObj.subject.value.length < 2)
	{
		formErrors = "{L_EMPTY_SUBJECT_EMAIL}";
	}

	if (formErrors) {
		alert(formErrors);
		return false;
	}
}
//-->
</script>
<!-- INCLUDE pa_header.tpl -->
{IMG_TBL}<div class="forumline nav-div">
	<p class="nav-header">
		<a href="{U_INDEX}">{L_HOME}</a>{NAV_SEP}<a href="{U_DOWNLOAD_HOME}" class="nav">{DOWNLOAD}</a><!-- BEGIN navlinks -->{NAV_SEP}<a href="{navlinks.U_VIEW_CAT}" class="nav">{navlinks.CAT_NAME}</a><!-- END navlinks -->{NAV_SEP}<a href="{U_FILE_NAME}" class="nav">{FILE_NAME}</a>{NAV_SEP}<a href="#" class="nav-current">{L_EMAIL}</a>
	</p>
	<div class="nav-links">
		<div class="nav-links-left">{CURRENT_TIME}</div>
		<!-- INCLUDE pa_links.tpl -->
	</div>
</div>{IMG_TBR}

<form action="{S_EMAIL_ACTION}" method="post" onSubmit="return checkForm(this)" name="post">
{IMG_THL}{IMG_THC}<span class="forumlink">{L_EMAIL}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr><td class="row2" colspan="2"><span class="genmed">{L_EMAILINFO}</span></td></tr>
<tr>
	<td class="row1" width="30%"><span class="genmed">{L_YNAME}:&nbsp;</span></td>
	<td class="row2" width="70%"><!-- IF USER_LOGGED --><input class="post" type="text" size="50" name="sname"><!-- ELSE --><b><span class="genmed">{SNAME}</span></b><!-- ENDIF --></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_YEMAIL}:&nbsp;</span></td>
	<td class="row2"><!-- IF USER_LOGGED --><input class="post" type="text" size="50" name="semail"><!-- ELSE --><b><span class="genmed">{SEMAIL}</span></b><!-- ENDIF --></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_FNAME}:&nbsp;</span></td>
	<td class="row2"><input class="post" type="text" size="50" name="fname"></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_FEMAIL}: *&nbsp;</span></td>
	<td class="row2"><input class="post" type="text" size="50" name="femail"></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_ESUB}:&nbsp;</span></td>
	<td class="row2"><input class="post" type="text" size="50" name="subject" value="{FILE_NAME}"></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_ETEXT}:&nbsp;</span></td>
	<td class="row2"><textarea cols="38" rows="10" name="message">{L_DEFAULTMAIL} {FILE_URL}</textarea></td>
</tr>
<tr>
	<td class="cat" align="center" colspan="2">{S_HIDDEN_FIELDS}<input type="hidden" name="action" value="email"><input type="hidden" name="file_id" value="{ID}"><input class="liteoption" type="submit" name="submit" value="{L_SEMAIL}"></td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</form>
<br />
<!-- INCLUDE pa_footer.tpl -->