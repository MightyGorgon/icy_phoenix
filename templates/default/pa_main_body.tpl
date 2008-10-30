<!-- INCLUDE pa_header.tpl -->
{IMG_TBL}<div class="forumline nav-div">
	<p class="nav-header">
		<a href="{U_INDEX}">{L_HOME}</a>{NAV_SEP}<a href="{U_DOWNLOAD}" class="nav-current">{DOWNLOAD}</a>
	</p>
	<div class="nav-links">
		<div class="nav-links-left">{CURRENT_TIME}</div>
		<!-- INCLUDE pa_links.tpl -->
	</div>
</div>{IMG_TBR}
{IMG_THL}{IMG_THC}<span class="forumlink">{DOWNLOAD}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th colspan="2">{L_CATEGORY}</th>
	<th width="10%">{L_LAST_FILE}</th>
	<th width="8%">{L_FILES}</th>
</tr>
<!-- BEGIN no_cat_parent -->
<!-- IF no_cat_parent.IS_HIGHER_CAT -->
<tr><td class="forum-buttons2" align="left" colspan="4"><img src="{CAT_BLOCK_IMG}" style="vertical-align:middle;" alt="" />&nbsp;<span><a href="{no_cat_parent.U_CAT}">{no_cat_parent.CAT_NAME}</a></span></td></tr>
<!-- ELSE -->
<tr>
	<td class="row1 row-center" width="30" style="padding-right:5px;"><a href="{no_cat_parent.U_CAT}" class="cattitle"><img src="{no_cat_parent.CAT_IMAGE}" alt="{no_cat_parent.CAT_NEW_FILE}" /></a></td>
	<td class="row1h{no_cat_parent.XS_NEW} row-forum" onclick="window.location.href='{no_cat_parent.U_CAT}'">
	<span class="forumlink"><a class="forumlink{no_cat_parent.XS_NEW}" href="{no_cat_parent.U_CAT}">{no_cat_parent.CAT_NAME}</a></span><br />
	<span class="genmed">{no_cat_parent.CAT_DESC}</span>
	<!-- IF no_cat_parent.SUB_CAT --><br /><span class="gensmall"><b>{no_cat_parent.L_SUB_CAT}</b></span><span class="forumlink{no_cat_parent.XS_NEW}">{no_cat_parent.SUB_CAT}</span><!-- ENDIF -->
	</td>
	<td class="row2 row-center" nowrap="nowrap"><span class="genmed">{no_cat_parent.LAST_FILE}</span></td>
	<td class="row2 row-center"><span class="genmed">{no_cat_parent.FILECAT}</span></td>
</tr>
<!-- ENDIF -->
<!-- END no_cat_parent -->
<tr><td class="cat" colspan="4">&nbsp;</td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<br />
<!-- INCLUDE pa_footer.tpl -->