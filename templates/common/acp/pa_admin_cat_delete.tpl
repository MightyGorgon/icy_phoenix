<script type="text/javascript">
<!--
	function disableFileMode()
	{
		if(document.form.file_mode[0].checked)
		{
			document.form.file_to_cat_id.disabled = true;
		}
		if(document.form.file_mode[1].checked)
		{
			document.form.file_to_cat_id.disabled = false;
		}
		document.form.cat_id.disabled = true;
	}

	function disableSubcatMode()
	{
		if(document.form.subcat_mode[0].checked)
		{
			document.form.subcat_to_cat_id.disabled = true;
		}

		if(document.form.subcat_mode[1].checked)
		{
			document.form.subcat_to_cat_id.disabled = false;
		}
	}

	function checkDelete()
	{
		var error_msg = ""
		if (document.form.file_to_cat_id.value == -1 && document.form.file_mode[1].checked)
		{
			error_msg += "You can't move the file to a category that doesn't allow file on it";
		}

		if(document.form.cat_id.options[document.form.cat_id.selectedIndex].value == document.form.file_to_cat_id.options[document.form.file_to_cat_id.selectedIndex].value && document.form.file_mode[1].checked)
		{
			if(error_msg != "")
			{
				error_msg += "\n";
			}
			error_msg += "You can't move the files to the same deleted category.";
		}

		if(document.form.cat_id.options[document.form.cat_id.selectedIndex].value == document.form.subcat_to_cat_id.options[document.form.subcat_to_cat_id.selectedIndex].value && document.form.subcat_mode[1].checked)
		{
			if(error_msg != "")
			{
				error_msg += "\n";
			}
			error_msg += "You can't move the sub category to the same deleted category.";
		}

		if(error_msg != "")
		{
			alert(error_msg);
			return false;
		}
		else
		{
			return true;
		}
	}

// -->
</script>

<body onLoad="disableFileMode(); disableSubcatMode();">
<form action="{S_DELETE_CAT_ACTION}" method="post" name="form" onsubmit="return checkDelete();">

<h1>{L_CAT_TITLE}</h1>
<p>{L_CAT_EXPLAIN}</p>

<!-- IF ERROR neq '' -->
<table class="forumline"><tr><td class="row2 row-center">{ERROR}</td></tr></table>
<br />
<!-- ENDIF -->

<table class="forumline">
<tr><th colspan="2">{L_CAT_TITLE}</th></tr>
<tr><td colspan="2" class="row1 row-center tvalignm">{L_SELECT_CAT}&nbsp;&nbsp;<select name="cat_id" class="forminput">{S_SELECT_CAT}</select></td></tr>
<tr>
	<td class="row2" colspan="2">
		{L_DO_FILE}&nbsp;&nbsp;
		<input type="radio" name="file_mode" value="delete" checked onclick="disableFileMode();" />{L_DELETE}&nbsp;&nbsp;
		<input type="radio" name="file_mode" value="move" onclick="disableFileMode();" />{L_MOVE}
	</td>
</tr>
<tr><td colspan="2" class="row2 row-center tvalignm">{L_MOVE_TO}:&nbsp;&nbsp;<select name="file_to_cat_id" class="forminput">{S_FILE_SELECT_CAT}</select></td></tr>
<tr>
	<td class="row2" colspan="2">
		{L_DO_CAT}&nbsp;&nbsp;
		<input type="radio" name="subcat_mode" value="delete" checked onclick="disableSubcatMode();" />{L_DELETE}&nbsp;&nbsp;
		<input type="radio" name="subcat_mode" value="move" onclick="disableSubcatMode();" />{L_MOVE}
	</td>
</tr>
<tr><td colspan="2" class="row2 row-center tvalignm">{L_MOVE_TO}:&nbsp;&nbsp;<select name="subcat_to_cat_id" class="forminput">{S_SELECT_CAT}</select></td></tr>
<tr><td class="cat tdalignc" colspan="2">{S_HIDDEN_FIELDS}<input class="liteoption" type="submit" value="{L_CAT_TITLE}" name="submit" /></td></tr>
</table>
</form>
