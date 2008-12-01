<!-- BEGIN switch_sub_cats -->
{IMG_THL}{IMG_THC}<span class="forumlink">{L_CATEGORY}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th nowrap="nowrap">&nbsp;{L_SUBCATEGORY}&nbsp;</th>
	<th width="50" nowrap="nowrap">&nbsp;{L_ARTICLES}&nbsp;</th>
</tr>
<!-- BEGIN catrow -->
<tr>
	<td class="row1h row-forum" height="46" ><span class="forumlink">{switch_sub_cats.catrow.CATEGORY}</span><br /><span class="genmed">{switch_sub_cats.catrow.CAT_DESCRIPTION}</span></td>
	<td class="row2 row-center" valign="middle"><span class="genmed">{switch_sub_cats.catrow.CAT_ARTICLES}</span></td>
</tr>
<!-- END catrow -->
<tr><td class="cat" colspan="2">&nbsp;</td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<br />
<!-- END switch_sub_cats -->
{IMG_THL}{IMG_THC}<span class="forumlink">{L_ARTICLES}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th nowrap="nowrap" width="60%">&nbsp;{L_ARTICLE}&nbsp;</th>
	<th nowrap="nowrap">&nbsp;{L_ARTICLE_TYPE}&nbsp;</th>
	<th nowrap="nowrap">&nbsp;{L_ARTICLE_AUTHOR}&nbsp;</th>
	<th nowrap="nowrap">&nbsp;{L_ARTICLE_DATE}&nbsp;</th>
	<th nowrap="nowrap">&nbsp;{L_VIEWS}&nbsp;</th>
</tr>
<!-- BEGIN articlerow -->
<tr>
	<td class="row2">
		<b>{articlerow.ARTICLE}</b><br />
		{articlerow.ARTICLE_DESCRIPTION}
		<div align="right"><span class="post-buttons">{articlerow.U_APPROVE_IMG}&nbsp;{articlerow.U_DELETE_IMG}</span></div>
	</td>
	<td class="row1 row-center">{articlerow.ARTICLE_TYPE}</td>
	<td class="row2 row-center"><b>{articlerow.ARTICLE_AUTHOR}</b></td>
	<td class="row2 row-center" nowrap="nowrap">{articlerow.ARTICLE_DATE}</td>
	<td class="row2 row-center">{articlerow.ART_VIEWS}</td>
</tr>
<!-- END articlerow -->
<!-- BEGIN no_articles -->
<tr><td class="row1 row-center" colspan="5" height="50"><span class="genmed">{no_articles.COMMENT}</span></td></tr>
<!-- END no_articles -->
<tr><td class="cat" colspan="5">&nbsp;</td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}

<!-- BEGIN pagination -->
<table width="100%" cellspacing="2" cellpadding="0" border="0">
<tr>
	<td valign="top" align="left" ><span class="gensmall">{PAGE_NUMBER}</span></td>
	<td valign="top" align="right" ><span class="pagination">{PAGINATION}</span></td>
</tr>
</table>
<!-- END pagination -->
