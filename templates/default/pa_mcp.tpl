<!-- INCLUDE pa_header.tpl -->
<!-- INCLUDE pa_links.tpl -->

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
		document.form.cat_js_id.disabled = true;
	}
	if(document.form.mode_js.options[document.form.mode_js.selectedIndex].value == 'file_cat')
	{
		document.form.cat_js_id.disabled = false;
	}
}

// Taking from the Attachment MOD of Acyd Burn
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
// -->
</script>

<!-- <body onload="disable_cat_list();"> -->
<form method="post" action="{S_FILE_ACTION}" name="form">
<div class="forumline genmed" style="text-align: center; margin-top: 10px; padding: 5px;">
<b>{L_MCP_EXPLAIN}</b>&nbsp;&raquo;&nbsp;<b><span class="genmed">{L_MODE}:</span></b>&nbsp;<select name="mode_js" onchange="disable_cat_list();">{S_MODE_SELECT}</select>&nbsp;&nbsp;&nbsp;<b><span class="genmed">{L_CATEGORY}:</span></b>&nbsp;{S_CAT_LIST}&nbsp;&nbsp;&nbsp;<input type="submit" class="liteoption" name="go" value="{L_GO}" />
</div>
{S_HIDDEN_FIELDS}
</form>
<form method="post" action="{S_FILE_ACTION}" name="file_ids" onsubmit="return check();">
<!-- BEGIN file_mode -->
<table class="forumline">
<tr><th colspan="6">{file_mode.L_FILE_MODE}</span></th></tr>
<!-- IF file_mode.DATA -->
<!-- BEGIN file_row -->
<tr>
	<td class="row1 row-center tw5pct"><span class="genmed">{file_mode.file_row.FILE_NUMBER}</span></td>
	<td class="row1 tw50pct"><span class="genmed">{file_mode.file_row.FILE_NAME}</span></td>
	<td class="row1 row-center tw10pct"><span class="genmed"><a href="{file_mode.file_row.U_FILE_EDIT}">{L_EDIT}</a></span></td>
	<td class="row1 row-center tw10pct"><span class="genmed"><a href="javascript:delete_file('{file_mode.file_row.U_FILE_DELETE}')">{L_DELETE}</a></span></td>
	<td class="row1 row-center tw20pct"><span class="genmed"><a href="{file_mode.file_row.U_FILE_APPROVE}">{file_mode.file_row.L_APPROVE}</a></span></td>
	<td class="row1 row-center tw5pct"><span class="genmed"><input type="checkbox" name="file_ids[]" value="{file_mode.file_row.FILE_ID}" /></span></td>
</tr>
<!-- END file_row -->
<!-- ELSE -->
<tr><td class="row1 row-center"><span class="gen">{L_NO_FILES}</span></td></tr>
<!-- ENDIF -->
</table>
<br />
<!-- END file_mode -->
<table class="forumline">
<tr>
	<td class="cat tdalignc">
		{S_HIDDEN_FIELDS}
		<input type="submit" class="liteoption" name="approve" value="{L_APPROVE_FILE}" onclick="set_add_file(false); set_delete_file(false);" />
		<input type="submit" class="liteoption" name="unapprove" value="{L_UNAPPROVE_FILE}" onclick="set_add_file(false); set_delete_file(false);" />
	</td>
</tr>
</table>
<br />
</form>
<table>
	<tr><td class="tdalignr tdnw"><span class="pagination">{PAGINATION}</span></td></tr>
	<tr><td class="tdalignr tdnw"><span class="gensmall">{PAGE_NUMBER}</span></td></tr>
</table>
<!-- INCLUDE pa_footer.tpl -->