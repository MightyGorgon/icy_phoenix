<h1>{L_HEADLINE}</h1>
<p>{L_SUBHEADLINE}</p>
<br />

<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><th colspan="2">{L_FUNC_HEADER}</th></tr>
<tr>
	<td class="row1 row-center" width="50%"><span class="gen"><a href="{U_LINK_OPTION_1}"><img src="{IMG_ICON_1}" alt="{L_OPTION_1}" title="{L_OPTION_1}" /><br />{L_OPTION_1}</a></span></td>
	<td class="row2 row-center" width="50%"><span class="gen"><a href="{U_LINK_OPTION_2}"><img src="{IMG_ICON_2}" alt="{L_OPTION_2}" title="{L_OPTION_2}" /><br />{L_OPTION_2}</a></span></td>
</tr>
<tr><td class="cat" colspan="2">&nbsp;</td></tr>
</table>

<br /><br />

<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><th colspan="2">{L_TABLE_HEADER}</th></tr>
<!-- BEGIN header_table_cell -->
<tr>
	<th>{L_TABLEHEAD_1}</th>
	<th>{L_TABLEHEAD_2}</th>
</tr>
<!-- END header_table_cell -->
<!-- BEGIN no_action -->
<tr><td class="row2" colspan="2"><h1>{no_action.L_IMPORTANT}</h1><b>{no_action.L_SELECT_ACTION}</b></td></tr>
<!-- END no_action -->
<!-- BEGIN akt_complete -->
<tr><td class="row2" colspan="2"><b>{akt_complete.L_UPDATE_ACTION}</b></td></tr>
<!-- END akt_complete -->
<!-- BEGIN file_output -->
<tr>
	<td class="{file_output.CLASS}">{file_output.PATH}</td>
	<td class="{file_output.CLASS}"><span style="color:{file_output.COLOR};"><b>{file_output.STATUS}</b></span></td>
</tr>
<!-- END file_output -->
<tr><td class="cat" colspan="2">&nbsp;</td></tr>
</table>