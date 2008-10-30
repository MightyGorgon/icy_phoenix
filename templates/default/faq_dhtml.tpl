<script type="text/javascript" src="{U_CFAQ_JSLIB}"></script>
<noscript>
<table class="forumline" width="100%" cellspacing="1" cellpadding="3" border="0" align="center">
<tr><td class="row1 row-center"><span class="gen"><br />{L_CFAQ_NOSCRIPT}<br />&nbsp;</span></td></tr>
</table>
</noscript>

<table class="empty-table" width="100%" align="center" cellspacing="0">
<tr><td align="left" class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></td></tr>
</table>

<table class="forumline" width="100%" cellspacing="0"><tr><th>{L_FAQ_TITLE}</th></tr></table>

<br clear="all" />

<!-- BEGIN faq_block -->
<table class="forumline" width="100%" cellspacing="0">
<tr><td class="row-header" align="center"><span>{faq_block.BLOCK_TITLE}</span></td></tr>
<!-- BEGIN faq_row -->
<tr>
	<td class="{faq_block.faq_row.ROW_CLASS}" align="left" valign="top">
		<div onclick="return CFAQ.display('faq_a_{faq_block.faq_row.U_FAQ_ID}', false);" style="width:100%;cursor:pointer;cursor:hand;">
			<span class="gen"><a class="postlink" name="{faq_block.faq_row.U_FAQ_ID}" href="#{faq_block.faq_row.U_FAQ_ID}" onclick="return CFAQ.display('faq_a_{faq_block.faq_row.U_FAQ_ID}', true);" onfocus="this.blur();"><b>{faq_block.faq_row.FAQ_QUESTION}</b></span></a>
		</div>
		<div id="faq_a_{faq_block.faq_row.U_FAQ_ID}" style="display:none;">
			<table class="bodyline" width="100%" cellspacing="1" cellpadding="3" border="0" align="center">
				<tr><td class="spaceRow"><img src="{SPACER}" alt="" width="1" height="3" /></td></tr>
				<tr><td align="left" valign="top"><div class="post-text">{faq_block.faq_row.FAQ_ANSWER}<br /></div></td></tr>
				<tr><td class="spaceRow"><img src="{SPACER}" alt="" width="1" height="3" /></td></tr>
			</table>
		</div>
	</td>
</tr>
<!-- END faq_row -->
</table>
<!-- END faq_block -->

<table width="100%" cellspacing="2" border="0" align="center">
<tr>
	<td align="left" valign="middle" nowrap="nowrap"><span class="copyright">DHTML Collapsible FAQ v1.0.2 &copy; 2004-2005 by <a href="http://www.phpmix.com/" target="_blank" class="copyright">phpMiX</a></span></td>
	<td align="right" valign="middle" nowrap="nowrap"><span class="gensmall">{S_TIMEZONE}</span></td>
</tr>
<tr><td colspan="2" align="right" valign="middle" nowrap="nowrap">{JUMPBOX}</td></tr>
</table>