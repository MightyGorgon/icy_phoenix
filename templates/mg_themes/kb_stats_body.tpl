<!-- BEGIN switch_sub_cats -->
{IMG_THL}{IMG_THC}<span class="forumlink">{L_CATEGORY}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<!-- BEGIN catrow -->
<tr>
	<td class="row1" height="50">
		<span class="forumlink">{switch_sub_cats.catrow.CATEGORY}</span><br />
		<span class="genmed">{switch_sub_cats.catrow.CAT_DESCRIPTION}</span>
	</td>
	<td class="row2 row-center" valign="middle"><span class="genmed">{switch_sub_cats.catrow.CAT_ARTICLES}</span></td>
</tr>
<!-- END catrow -->
<tr><td class="cat" colspan="2">&nbsp;</td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<br />
<!-- END switch_sub_cats -->
{IMG_THL}{IMG_THC}<span class="forumlink">{PATH}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th nowrap="nowrap">{L_ARTICLE}</th>
	<th nowrap="nowrap">{L_CAT}</th>
	<th nowrap="nowrap">{L_ARTICLE_TYPE}</th>
	<th nowrap="nowrap">{L_ARTICLE_AUTHOR}</th>
	<th nowrap="nowrap">{L_ARTICLE_DATE}</th>
	<th nowrap="nowrap">{L_VIEWS}</th>
</tr>
<!-- BEGIN articlerow -->
<tr>
	<td class="row2" valign="middle"><span class="forumlink">{articlerow.ARTICLE}</span></td>
	<td class="row2 row-center" valign="middle"><span class="gen">{articlerow.CATEGORY}</span></td>
	<td class="row2 row-center" valign="middle"><span class="genmed">{articlerow.ARTICLE_TYPE}</span></td>
	<td class="row2 row-center" valign="middle"><span class="genmed">{articlerow.ARTICLE_AUTHOR}</span></td>
	<td class="row2 row-center" valign="middle" nowrap="nowrap"><span class="gensmall">{articlerow.ARTICLE_DATE}</span></td>
	<td class="row2 row-center" valign="middle"><span class="genmed">{articlerow.ART_VIEWS}</span></td>
</tr>
<tr>
	<td class="row1" colspan="4"><span class="genmed">{articlerow.ARTICLE_DESCRIPTION}</span></td>
	<td class="row1 row-center" colspan="2">{articlerow.U_APPROVE}&nbsp;<span class="post-buttons">{articlerow.U_DELETE}</span></td>
</tr>
<!-- END articlerow -->
<!-- BEGIN no_articles -->
<tr><td class="row1 row-center" colspan="6" height="50"><span class="genmed">{no_articles.COMMENT}</span></td></tr>
<!-- END no_articles -->
<tr><td class="cat" colspan="6">&nbsp;</td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}

<!-- BEGIN pagination -->
<table width="100%" cellspacing="2" cellpadding="0" border="0">
<tr>
	<td valign="top" align="left"><span class="gensmall">{PAGE_NUMBER}</span></td>
	<td valign="top" align="left"><span class="gensmall">{S_TIMEZONE}</span><br /><span class="pagination">{PAGINATION}</span></td>
</tr>
</table>
<!-- END pagination -->