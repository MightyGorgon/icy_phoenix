<h1>{PAGE_TITLE}</h1>
<p>{PAGE_EXPLAIN}</p>
<br />
<form method="post" action="{PAGE_ACTION}">
<table class="forumline" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr><th><span class="genmed"><strong>{L_SEARCH_SITE}</strong></span></th></tr>
<tr><td class="row1 row-center"><span class="genmed">{L_SEARCH_SITE_TITLE}&nbsp;&nbsp;</span><input type="text" style="width: 200px" class="post" name="search_keywords" size="30" />&nbsp;<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;</td></tr>
</table>
<br /><br />
<table class="forumline" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<th nowrap="nowrap">{L_LINK_TITLE}</th>
	<th nowrap="nowrap">{L_LINK_CATEGORY}</th>
	<th nowrap="nowrap">{L_LINK_USER}</th>
	<th nowrap="nowrap">{L_LINK_JOINED}</th>
	<th nowrap="nowrap">{L_LINK_USER_IP}</th>
	<th nowrap="nowrap">{L_LINK_ACTIVE}</th>
	<th nowrap="nowrap">{L_LINK_HITS}</th>
	<th>{L_ACTION}</th>
</tr>
<!-- BEGIN linkrow -->
<tr>
	<td class="{linkrow.ROW_CLASS}" nowrap="nowrap"><a href="{linkrow.LINK_URL}" target="_blank">{linkrow.LINK_TITLE}</a></td>
	<td class="{linkrow.ROW_CLASS} row-center" nowrap="nowrap">{linkrow.LINK_CATEGORY}</td>
	<td class="{linkrow.ROW_CLASS} row-center" nowrap="nowrap">{linkrow.U_LINK_USER}</td>
	<td class="{linkrow.ROW_CLASS}" nowrap="nowrap">{linkrow.LINK_JOINED}</td>
	<td class="{linkrow.ROW_CLASS} row-center" nowrap="nowrap">{linkrow.LINK_USER_IP}</td>
	<td class="{linkrow.ROW_CLASS} row-center" nowrap="nowrap">{linkrow.LINK_ACTIVE}</td>
	<td class="{linkrow.ROW_CLASS} row-center" nowrap="nowrap">{linkrow.LINK_HITS}</td>
	<td class="{linkrow.ROW_CLASS} row-center" nowrap="nowrap">&nbsp;<a href="{U_LINK}?mode=edit&amp;link_id={linkrow.LINK_ID}"><img src="{IMG_CMS_ICON_EDIT}" alt="{L_EDIT}" title="{L_EDIT}" /></a>&nbsp;<a href="{U_LINK}?mode=delete&link_id={linkrow.LINK_ID}"><img src="{IMG_CMS_ICON_DELETE}" alt="{L_DELETE}" title="{L_DELETE}" /></a>&nbsp;</td>
</tr>
<!-- END linkrow -->
<tr><td class="cat" colspan="8">&nbsp;</td></tr>
</table>
</form>
<br />
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td valign="top"><span class="gensmall">{PAGE_NUMBER}</span></td>
	<td align="right"><span class="pagination">{PAGINATION}</span></td>
</tr>
</table>
<br clear="all" />
<div align="center"><span class="copyright">Links MOD v1.2.1 by <a href="http://www.phpbb2.de" target="_blank">phpBB2.de</a> and OOHOO and CRLin</span></div>
<br clear="all" />