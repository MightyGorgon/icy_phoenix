<!-- INCLUDE ../common/cms/page_header.tpl -->

<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1 row-center c-r-l" width="100" valign="middle"><img src="{IP_ROOT_PATH}templates/common/images/cms/cms_ads.png" alt="{L_ADS_TITLE}" title="{L_ADS_TITLE}" /></td>
	<td class="row1 c-r-r" valign="top"><h1>{L_ADS_TITLE}</h1><span class="genmed">{L_ADS_TITLE_EXPLAIN}</span></td>
</tr>
</table>

<form method="post" action="{S_ADS_ACTION}">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th>{L_AD_POSITION}</th>
	<th>{L_AD_ENABLED}</th>
</tr>
<!-- BEGIN ads_cfg -->
<tr class="{ads_cfg.ROW_CLASS} {ads_cfg.ROW_CLASS}h" style="background-image:none;">
	<td class="{ads_cfg.ROW_CLASS}" style="background: none;"><b class="gensmall">{ads_cfg.AD_CFG}</b></td>
	<td class="{ads_cfg.ROW_CLASS} row-center" style="background: none;">{ads_cfg.AD_RADIO}</td>
</tr>
<!-- END ads_cfg -->
<tr><td class="spaceRow" colspan="2"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
<tr><td colspan="2" align="center" class="cat">{S_HIDDEN_FIELDS}<input type="submit" name="update" value="{L_CMS_SAVE_CHANGES}" class="liteoption" /></td></tr>
</table>

<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th><a href="{U_AD_SORT_ID}">#</a></th>
	<th><a href="{U_AD_SORT_ACTIVE}">{L_AD_STATUS}</a></th>
	<th><a href="{U_AD_SORT_TITLE}">{L_AD_DES}</a></th>
	<th><a href="{U_AD_SORT_POSITION}">{L_AD_POSITION}</a></th>
	<th><a href="{U_AD_AUTH}">{L_AD_AUTH}</a></th>
	<th><a href="{U_AD_FORMAT}">{L_AD_FORMAT}</a></th>
	<th>{L_ACTION}</th>
</tr>
<!-- BEGIN ads -->
<tr class="{ads.ROW_CLASS} {ads.ROW_CLASS}h" style="background-image: none;">
	<td class="{ads.ROW_CLASS} row-center" style="background: none; width: 30px;"><b class="gensmall">{ads.AD_ID}</b></td>
	<td class="{ads.ROW_CLASS} row-center" style="background: none; width: 50px;"><input type="checkbox" name="ads[]" value="{ads.AD_ID}"{ads.AD_ACTIVE_CHECKED} /></td>
	<td class="{ads.ROW_CLASS}" style="background: none;"><b class="gensmall">{ads.AD_TITLE}</b></td>
	<td class="{ads.ROW_CLASS} row-center" style="background: none;"><span class="gensmall">{ads.AD_POSITION}</span></td>
	<td class="{ads.ROW_CLASS} row-center" style="background: none;"><span class="gensmall">{ads.AD_AUTH}</span></td>
	<td class="{ads.ROW_CLASS} row-center" style="background: none;" nowrap="nowrap"><span class="gensmall">{ads.AD_FORMAT}</span></td>
	<td class="{ads.ROW_CLASS} row-center" style="background: none; width: 80px;"><b class="gensmall"><a href="{ads.U_EDIT}"><img src="{IMG_CMS_ICON_EDIT}" alt="{L_EDIT}" title="{L_EDIT}" /></a>&nbsp;<a href="{ads.U_DELETE}"><img src="{IMG_CMS_ICON_DELETE}" alt="{L_DELETE}" title="{L_DELETE}" /></a></b></td>
</tr>
<!-- END ads -->
<!-- BEGIN no_ads -->
<tr><td colspan="9" class="row1 row-center">{L_AD_NO_ADS}</td></tr>
<!-- END no_ads -->
<tr><td class="spaceRow" colspan="9"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
<tr><td colspan="9" align="center" class="cat">{S_HIDDEN_FIELDS}<input type="submit" name="update" value="{L_CMS_SAVE_CHANGES}" class="liteoption" />&nbsp;&nbsp;<input type="submit" value="{L_AD_ADD}" class="mainoption" /></td></tr>
</table>
</form>

<!-- INCLUDE ../common/cms/page_footer.tpl -->