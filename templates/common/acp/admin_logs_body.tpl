<script type="text/javascript">
<!--
function toggle_check_all()
{
	for(var i=0; i < document.logs_values.elements.length; i++)
	{
		var checkbox_element = document.logs_values.elements[i];
		if((checkbox_element.name != 'check_all_box') && (checkbox_element.type == 'checkbox'))
		{
			checkbox_element.checked = document.logs_values.check_all_box.checked;
		}
	}
}
-->
</script>

<h1>{L_TITLE}</h1>
<p>{L_TITLE_EXPLAIN}</p>
<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td align="left"><span class="gensmall">&nbsp;{PAGE_NUMBER}</span></td>
	<td align="right"><span class="pagination">{PAGINATION}</span></td>
</tr>
</table>
<form method="post" action="{S_MODE_ACTION}" name="logs_values">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th width="1" nowrap="nowrap"><input type="checkbox" name="check_all_box" onClick="toggle_check_all()" /></th>
	<th align="center" nowrap="nowrap" width="100">{L_DATE}</th>
	<th align="center" nowrap="nowrap" width="250">{L_LINK}</th>
	<th align="center" nowrap="nowrap" width="100">{L_USERNAME}</th>
	<th align="center" nowrap="nowrap" width="100">{L_ACTION}</th>
	<th align="center" nowrap="nowrap" width="100">{L_LOGS_TARGET}</th>
	<th align="center" nowrap="nowrap">{L_DESCRIPTION}</th>
</tr>
<!-- BEGIN log_row -->
<tr>
	<td class="row1 row-center"><span class="gensmall"><input type="checkbox" name="delete_id_{log_row.LOG_ID}" /></span></td>
	<td class="row1 row-center"><span class="gensmall">{log_row.LOG_TIME}</span></td>
	<td class="row1"><span class="gensmall">{log_row.LOG_PAGE}</span></td>
	<td class="row1 row-center"><span class="gensmall">{log_row.LOG_USERNAME}</span></td>
	<td class="row1"><span class="gensmall">{log_row.LOG_ACTION}</span></td>
	<td class="row1 row-center"><span class="gensmall">{log_row.LOG_TARGET}</span></td>
	<td class="row1">
	<!-- IF !(log_row.S_LOG_DESC_EXTRA) -->
	<span class="gensmall">{log_row.LOG_DESC}</span>
	<!-- ELSE -->
	<table width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<td class="gensmall" style="cursor:pointer;cursor:hand;" onclick="ShowHide('log_desc_{log_row.LOG_ID}','log_desc_{log_row.LOG_ID}_h','log_desc_{log_row.LOG_ID}');">
				<a href="javascript:void(0);" style="vertical-align:top;text-decoration:none;">{log_row.LOG_DESC}</a>
			</td>
		</tr>
		<tr>
			<td class="gensmall">
				<div id="log_desc_{log_row.LOG_ID}_h" style="display:none;position:relative;padding-top:0px;padding-bottom:0px;">
					<div class="nav-div" style="padding:2px;">{log_row.LOG_DESC_EXTRA}</div>
				</div>
				<div id="log_desc_{log_row.LOG_ID}" style="display:'';position:relative;">
					<script type="text/javascript">
					<!--
					tmp = 'log_desc_{log_row.LOG_ID}';
					if(GetCookie(tmp) == '2')
					{
						ShowHide('log_desc_{log_row.LOG_ID}','log_desc_{log_row.LOG_ID}_h','log_desc_{log_row.LOG_ID}');
					}
					//-->
					</script>
				</div>
			</td>
		</tr>
	</table>
	<!-- ENDIF -->
	</td>
</tr>
<!-- END log_row -->
<tr>
	<td class="cat" colspan="7" height="28">
		<input type="submit" name="delete_sub" value="{L_DELETE}" class="liteoption" />
		<input type="submit" name="clear" value="{L_CLEAR}" class="liteoption" />
	</td>
</tr>
</table>
</form>
<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td align="left"><span class="gensmall">&nbsp;{PAGE_NUMBER}</span></td>
	<td align="right"><span class="pagination">{PAGINATION}</span></td>
</tr>
</table>