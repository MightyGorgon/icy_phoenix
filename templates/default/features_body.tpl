<!-- INCLUDE overall_header.tpl -->

{IMG_THL}{IMG_THC}<span class="forumlink">{L_FEATURES}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th>{L_FEATURES}</th>
	<th width="180">{L_PHPBB2}</th>
	<th width="180">{L_ICYPHOENIX}</th>
	<th width="180">{L_PHPBB3}</th>
</tr>
<!-- BEGIN feature_cat -->
<tr><th colspan="4">{feature_cat.CAT_NAME}</th></tr>
<!-- BEGIN feature_item -->
<tr class="{feature_cat.feature_item.ROW_CLASS} row1h" style="background-image: none;">
	<td class="{feature_cat.feature_item.ROW_CLASS}" style="background: none;"><span class="gen">{feature_cat.feature_item.ITEM_NAME}</span></td>
	<td class="{feature_cat.feature_item.ROW_CLASS} row-center" style="background: none;"><span class="gensmall">{feature_cat.feature_item.BB2}</span></td>
	<td class="{feature_cat.feature_item.ROW_CLASS} row-center" style="background: none;"><span class="gensmall">{feature_cat.feature_item.IP}</span></td>
	<td class="{feature_cat.feature_item.ROW_CLASS} row-center" style="background: none;"><span class="gensmall">{feature_cat.feature_item.BB3}</span></td>
</tr>
<!-- END feature_item -->
<!-- END feature_cat -->
<tr><td class="catBottom" align="center" colspan="4">&nbsp;</td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}

<br /><br />
<div class="gensmall">{L_PHPBB_FEATURES_PAGE}</div>

<!-- INCLUDE overall_footer.tpl -->