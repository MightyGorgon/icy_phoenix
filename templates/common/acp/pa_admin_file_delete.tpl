<script type="text/javascript">
<!--
	var error_msg = "";
	function checkAddForm()
	{
		error_msg = "";
		if (document.form.cat_id.value == -1)
		{
			error_msg = "There is no file in this category";
		}

		if(error_msg != "")
		{
			alert(error_msg);
			error_msg = "";
			return false;
		}
		else
		{
			return true;
		}
	}
// -->
</script>

<h1>{L_DFILETITLE}</h1>
<p>{L_FILEEXPLAIN}</p>

<form action="{S_DELETE_FILE_ACTION}" method="post" name="form" onsubmit="return checkAddForm();">
<!-- IF ERROR neq '' -->
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td class="row2 row-center">{ERROR}</td></tr></table>
<br />
<!-- ENDIF -->
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><th colspan="2">{L_DFILETITLE}</th></tr>
<tr><td colspan="2" class="cat" align="center" valign="middle"><select name="cat_id" class="forminput">{S_CAT_SELECT}</select>&nbsp;&nbsp;<input class="liteoption" type="submit" value="{L_GO}" name="select_cat" /></td></tr>
<!-- BEGIN file_list -->
<tr>
	<td width="3%" class="row1 row-center" valign="middle"><input type="checkbox" name="select[{file_list.FILE_ID}]" value="yes" {file_list.CHECKBOX} /></td>
	<td width="97%" class="row1"><span class="gen"> {file_list.FILE_NAME}</span>&nbsp;<span class="gensmall">{file_list.FILE_APPROVED}</span><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="gensmall">{file_list.FILE_DESC}</span></td>
</tr>
<!-- END file_list -->
<tr><td align="center" class="cat" colspan="2"><input class="liteoption" type="submit" value="{L_DFILETITLE}" name="submit" /><input type="hidden" name="file" value="delete"></td></tr>
</table>
</form>
