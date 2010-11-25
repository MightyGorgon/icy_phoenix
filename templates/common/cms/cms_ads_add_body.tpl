<!-- INCLUDE ../common/cms/page_header.tpl -->

<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1 row-center" width="100" valign="middle"><img src="images/cms/cms_ads.png" alt="{L_ADS_TITLE}" title="{L_ADS_TITLE}" /></td>
	<td class="row1" valign="top"><h1>{L_ADS_TITLE}</h1><span class="genmed">{L_ADS_TITLE_EXPLAIN}</span></td>
</tr>
</table>

<form method="post" action="{S_ADS_ACTION}">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><th colspan="2">{L_FORM_TITLE}</th></tr>
<tr>
	<td class="row1" style="width: 35%;"><strong>{L_AD_DES}</strong></td>
	<td class="row2" style="width: 65%;"><input class="post" type="text" size="60" maxlength="255" name="ad_title" value="{AD_TITLE}" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_AD_FORMAT}</strong></td>
	<td class="row2">{AD_FORMAT}</td>
</tr>
<tr>
	<td class="row1"><strong>{L_AD_TEXT}</strong></td>
	<td class="row2"><textarea name="ad_text" rows="15" cols="35" style="width: 98%;" class="post">{AD_TEXT}</textarea></td>
</tr>
<tr>
	<td class="row1"><strong>{L_AD_POSITION}</strong></td>
	<td class="row2">{AD_POSITION}</td>
</tr>
<tr>
	<td class="row1"><strong>{L_AD_AUTH}</strong><br /><span class="gensmall">{L_AD_AUTH_EXPLAIN}</span></td>
	<td class="row2">{AD_AUTH}</td>
</tr>
<tr>
	<td class="row1"><strong>{L_AD_STATUS}</strong><br /><span class="gensmall">{L_AD_STATUS_EXPLAIN}</span></td>
	<td class="row2">{AD_ACTIVE}</td>
</tr>
<tr><td colspan="2" align="center" class="cat">{S_HIDDEN_FIELDS}<input type="submit" value="{L_SUBMIT}" class="mainoption" /></td></tr>
</table>
</form>

<!-- INCLUDE ../common/cms/page_footer.tpl -->