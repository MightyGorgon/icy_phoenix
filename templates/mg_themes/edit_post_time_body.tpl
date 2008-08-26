<!-- BEGIN entry_page -->
<form action="{S_ACTION}" method="post">
{IMG_THL}{IMG_THC}<span class="forumlink">{L_POST_EDIT_TIME}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
	<tr><td class="row1 row-center" width="100%" align="center"><span class="genmed">{POST_EDIT_STRING}</span></td></tr>
	<tr>
	<td class="catBottom" align="center">
		{S_HIDDEN_FIELDS}
		<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;&nbsp;
		<input type="submit" name="reset" value="{L_RESET}" class="liteoption" />
	</td>
	</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</form>
<!-- END entry_page -->

<!-- BEGIN submit_finished -->
{IMG_THL}{IMG_THC}<span class="forumlink">{L_INFORMATION}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr><td class="row1 row-center" width="100%" align="center"><span class="genmed">{POST_EDIT_STRING}</span></td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<!-- END submit_finished -->
<?php
	global $close;
	if ($close){
?>
<script type="text/javascript">
<!--
function close_window()
{
	opener.document.location.href = "{U_VIEWTOPIC}";
	opener.document.location.reload();
	window.close();
}
setTimeout('close_window();', 3000);
//-->
</script>
<?php
}
?>