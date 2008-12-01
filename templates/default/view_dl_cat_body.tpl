{IMG_THL}{IMG_THC}<span class="forumlink">{L_DL_TOP}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th nowrap="nowrap" colspan="2">{L_DL_CAT}</th>
	<th nowrap="nowrap" width="50">{L_DL_FILES}</th>
	<th nowrap="nowrap">{L_DESCRIPTION}</th>
	<th nowrap="nowrap" width="170">{L_LAST}</th>
</tr>
<!-- BEGIN downloads -->
<tr>
	<td class="{downloads.ROW_CLASS} row-center" width="37"><img src="{downloads.CAT_IMG}" alt="{downloads.CAT_NAME}" /></td>
	<td class="{downloads.ROW_CLASS}"><a class="forumlink" href="{downloads.U_CAT_VIEW}">{downloads.CAT_NAME}</a>&nbsp;{downloads.MINI_IMG}<span class="genmed">{downloads.CAT_PAGES}&nbsp;</span></td>
	<td class="{downloads.ROW_CLASS} row-center"><span class="nav">{downloads.CAT_DL}&nbsp;</span></td>
	<td class="{downloads.ROW_CLASS}" {downloads.ROW_SPAN}><span class="genmed">{downloads.CAT_DESC}</span></td>
	<td class="{downloads.ROW_CLASS} row-center" {downloads.ROW_SPAN}><a href="{downloads.U_CAT_LAST_LINK}" class="gensmall">{downloads.CAT_LAST_DL}</a><br /><span class="gensmall">{downloads.CAT_LAST_TIME}</span><br />{downloads.U_CAT_LAST_USER}</td>
</tr>
<!-- BEGIN sub -->
<tr>
	<td class="{downloads.ROW_CLASS}" colspan="5">
		<table width="95%" align="right" cellpadding="2" cellspacing="0" border="0">
		<!-- BEGIN sublevel_row -->
		<tr>
			<td class="{downloads.sub.sublevel_row.ROW_CLASS}"><a href="{downloads.sub.sublevel_row.U_SUBLEVEL}" class="genmed">{downloads.sub.sublevel_row.L_SUBLEVEL}</a>&nbsp;{downloads.sub.sublevel_row.M_SUBLEVEL}</td>
			<td align="right" class="{downloads.sub.sublevel_row.ROW_CLASS}"><span class="genmed">{downloads.sub.sublevel_row.SUBLEVEL_COUNT}&nbsp;</span></td>
		</tr>
		<!-- END sublevel_row -->
		</table>
	</td>
</tr>
<!-- END sub -->
<!-- END downloads -->
<!-- BEGIN no_category -->
<tr><td class="row1 row-center" colspan="5"><span class="gen">{no_category.L_NO_CATEGORY}</td></tr>
<!-- END no_category -->
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<div style="text-align: right;"><div class="post-buttons">{U_SEARCH}</div></div>
<br />