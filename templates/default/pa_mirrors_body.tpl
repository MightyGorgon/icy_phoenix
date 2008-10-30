<!-- INCLUDE pa_header.tpl -->
<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td valign="bottom">
		<span class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a>{NAV_SEP}<a href="{U_DOWNLOAD_HOME}" class="nav">{DOWNLOAD}</a><!-- BEGIN navlinks -->{NAV_SEP}<a href="{navlinks.U_VIEW_CAT}" class="nav">{navlinks.CAT_NAME}</a><!-- END navlinks -->{NAV_SEP}{FILE_NAME}</span>
	</td>
</tr>
</table>

<table class="forumline" width="100%" cellspacing="0">
<tr>
	<th>{L_MIRRORS}</th>
	<th>{L_MIRROR_LOCATION}</th>
</tr>
<!-- BEGIN mirror_row -->
<tr>
	<td class="row2" valign="middle"><span class="genmed"><a href="{mirror_row.U_DOWNLOAD}">{L_DOWNLOAD}</a></span></td>
	<td class="row1" valign="middle"><span class="genmed">{mirror_row.MIRROR_LOCATION}</span></td>
</tr>
<!-- END mirror_row -->
<tr><td class="cat" align="center" colspan="2"></td></tr>
</table>
<br />
<!-- INCLUDE pa_footer.tpl -->