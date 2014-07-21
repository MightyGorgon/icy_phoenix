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
<table>
<tr>
	<td><span class="gensmall">{PAGE_NUMBER}</span></td>
	<td class="tdalignr"><span class="pagination">{PAGINATION}</span></td>
</tr>
</table>

<form method="post" action="{S_MODE_ACTION}" name="logs_values">
<div><b>{L_LOGS_ACTIONS_FILTER}</b>:&nbsp;{LOGS_ACTIONS_FILTER}&nbsp;</div>
<table class="forumline">
<tr>
	<th class="tw1pct tdnw"><input type="checkbox" name="check_all_box" onclick="toggle_check_all()" /></th>
	<th nowrap="nowrap" width="100"><a href="{U_LOG_TIME_SORT}" title="{L_CURRENT_SORT}">{L_DATE}{LOG_TIME_SORT}</a></th>
	<th nowrap="nowrap" width="250"><a href="{U_LOG_PAGE_SORT}" title="{L_CURRENT_SORT}">{L_LINK}{LOG_PAGE_SORT}</a></th>
	<th nowrap="nowrap" width="100"><a href="{U_LOG_USER_ID_SORT}" title="{L_CURRENT_SORT}">{L_USERNAME}{LOG_USER_ID_SORT}</a></th>
	<th nowrap="nowrap" width="100"><a href="{U_LOG_ACTION_SORT}" title="{L_CURRENT_SORT}">{L_ACTION}{LOG_ACTION_SORT}</a></th>
	<th nowrap="nowrap" width="100"><a href="{U_LOG_TARGET_SORT}" title="{L_CURRENT_SORT}">{L_LOGS_TARGET}{LOG_TARGET_SORT}</a></th>
	<th class="tdnw"><a href="{U_LOG_DESC_SORT}" title="{L_CURRENT_SORT}">{L_DESCRIPTION}{LOG_DESC_SORT}</a></th>
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
	<table>
		<tr>
			<td class="gensmall" style="cursor: pointer;" onclick="ShowHide('log_desc_{log_row.LOG_ID}','log_desc_{log_row.LOG_ID}_h','log_desc_{log_row.LOG_ID}');">
				<a href="#" onclick="return false;" class="nav-menu-link">{log_row.LOG_DESC}</a>
			</td>
		</tr>
		<tr>
			<td class="gensmall">
				<div id="log_desc_{log_row.LOG_ID}_h" class="nav-menu">
					<div class="nav-div" style="padding: 2px;">{log_row.LOG_DESC_EXTRA}</div>
				</div>
				<div id="log_desc_{log_row.LOG_ID}" class="js-sh-box">
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
		<input type="submit" name="delete_sub" value="{L_LOGS_DELETE}" class="liteoption" />
		<!-- &nbsp;<input type="submit" name="clear" value="{L_LOGS_DELETE_ALL}" class="liteoption" /> -->
	</td>
</tr>
</table>
</form>

<table>
<tr>
	<td><span class="gensmall">{PAGE_NUMBER}</span></td>
	<td class="tdalignr"><span class="pagination">{PAGINATION}</span></td>
</tr>
</table>