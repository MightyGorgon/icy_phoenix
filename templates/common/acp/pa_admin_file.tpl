<script type="text/javascript">
<!--
	var add_file = false;
	var deletefile = false;

	function set_add_file(status)
	{
		add_file = status;
	}

	function set_delete_file(status)
	{
		deletefile = status;
	}

	function delete_file(theURL)
	{
		if (confirm('Are you sure you want to delete this file??'))
		{
			window.location.href=theURL;
		}
		else
		{
			alert ('No Action has been taken.');
		}
	}

	function disable_cat_list()
	{
		if(document.form.mode_js.options[document.form.mode_js.selectedIndex].value != 'file_cat')
		{
			document.form.cat_id.disabled = true;
		}
		if(document.form.mode_js.options[document.form.mode_js.selectedIndex].value == 'file_cat')
		{
			document.form.cat_id.disabled = false;
		}
	}

	//
	// Taking from the Attachment MOD of Acyd Burn
	//
	function select(status)
	{
		for (i = 0; i < document.file_ids.length; i++)
		{
			document.file_ids.elements[i].checked = status;
		}
	}

	function check()
	{
		if(add_file)
		{
			return true;
		}

		for (i = 0; i < document.file_ids.length; i++)
		{
			if(document.file_ids.elements[i].checked == true)
			{
				if(deletefile)
				{
					if (confirm('Are you sure you want to delete these files??'))
					{
						return true;
					}
					else
					{
						return false;
					}
				}
				return true;
			}
		}
		alert('Please Select at least one file.');
		return false;
	}
	-->
</script>

<h1>{L_FILE_TITLE}</h1>
<p>{L_FILE_EXPLAIN}</p>

<body onLoad="disable_cat_list();">
<form method="post" action="{S_FILE_ACTION}" name="form">
<div style="text-align:right;padding:3px;">
<span class="genmed"><strong>{L_MODE}:</strong>&nbsp;<select name="mode_js" onchange="disable_cat_list();">{S_MODE_SELECT}</select>&nbsp;<strong>{L_CATEGORY}:</strong>{S_CAT_LIST}&nbsp;&nbsp;<input type="submit" class="liteoption" name="go" value="{L_GO}" /></span>
</div>
</form>

<form method="post" action="{S_FILE_ACTION}" name="file_ids" onsubmit="return check();">
<!-- BEGIN file_mode -->
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><th colspan="6">{file_mode.L_FILE_MODE}</span></th></tr>
<!-- IF file_mode.DATA -->
<!-- BEGIN file_row -->
<tr>
	<td class="row1 row-center" width="5%"><span class="genmed"><strong>{file_mode.file_row.FILE_NUMBER}</strong></span></td>
	<td class="row1" width="50%"><span class="genmed"><strong>{file_mode.file_row.FILE_NAME}</strong></span></td>
	<td class="row1 row-center" width="10%"><span class="genmed"><a href="{file_mode.file_row.U_FILE_EDIT}">{L_EDIT}</a></span></td>
	<td class="row1 row-center" width="10%"><span class="genmed"><a href="javascript:delete_file('{file_mode.file_row.U_FILE_DELETE}')">{L_DELETE}</a></span></td>
	<td class="row1 row-center" width="20%"><span class="genmed"><a href="{file_mode.file_row.U_FILE_APPROVE}">{file_mode.file_row.L_APPROVE}</a></span></td>
	<td class="row1 row-center" width="5%"><span class="genmed"><input type="checkbox" name="file_ids[]" value="{file_mode.file_row.FILE_ID}" /></span></td>
</tr>
<!-- END file_row -->
<!-- ELSE -->
<tr><td class="row1 row-center"><span class="gen">{L_NO_FILES}</span></td></tr>
<!-- ENDIF -->
</table>
<br />
<!-- END file_mode -->
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td class="cat" align="center">
	{S_HIDDEN_FIELDS}
	<input type="submit" class="liteoption" name="addfile" value="{L_ADD_FILE}" onclick="set_add_file(true); set_delete_file(false);" />
	<input type="submit" class="liteoption" name="delete" value="{L_DELETE_FILE}" onclick="set_add_file(false); set_delete_file(true);" />
	<input type="submit" class="liteoption" name="approve" value="{L_APPROVE_FILE}" onclick="set_add_file(false); set_delete_file(false);" />
	<input type="submit" class="liteoption" name="unapprove" value="{L_UNAPPROVE_FILE}" onclick="set_add_file(false); set_delete_file(false);" />
	</td>
</tr>
</table>
</form>

<table width="100%" cellspacing="2" cellpadding="2" border="0">
<tr><td align="right" nowrap="nowrap"><span class="pagination">{PAGINATION}</span></td></tr>
<tr><td align="right" nowrap="nowrap"><span class="gensmall">{PAGE_NUMBER}</span></td></tr>
</table>
