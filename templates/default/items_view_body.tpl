<!-- IF not S_HEADER_PROCESSED -->
<!-- INCLUDE overall_header.tpl -->
<!-- ENDIF -->

<!-- IF L_ITEM_TITLE --><div class="topic-title-hide-flow-header" style="text-align: left;"><h2><a href="{U_ITEM_TITLE}" style="text-decoration: none;">{L_ITEM_TITLE}</a></h2></div><br /><!-- ENDIF -->

{EXTRA_CONTENT_TOP}

<!-- IF S_INPUT_ALLOWED or S_EDIT_ALLOWED -->
<div class="css-button-wrap">

<!-- IF S_INPUT_ALLOWED -->
<div class="forumline css-button-left"><div class="row1h css-button-body" data-href="{U_ITEM_ADD}"><img src="{IMG_CMS_ICON_ADD}" alt="{L_DB_ITEM_ADD}" title="{L_DB_ITEM_ADD}" />&nbsp;<b>{L_DB_ITEM_ADD}</b>&nbsp;</div></div>
<!-- ENDIF -->

<!-- IF S_EDIT_ALLOWED -->
<div class="forumline css-button-left"><div class="row1h css-button-body" data-href="{U_ITEM_EDIT}"><img src="{IMG_CMS_ICON_EDIT}" alt="{L_EDIT}" title="{L_EDIT}" />&nbsp;<b>{L_EDIT}</b>&nbsp;</div></div>
<!-- ENDIF -->

&nbsp;
</div>
<br clear="all" />
<!-- ENDIF -->

{IMG_THL}{IMG_THC}<span class="forumlink">{L_PAGE_NAME}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<!-- BEGIN field -->
<tr class="{field.CLASS} {field.CLASS}h" style="background-image: none;">
	<td class="{field.CLASS}" width="30%" style="vertical-align: top; padding: 5px; background: none;">
		<span class="gen"><b>{field.L_NAME}</b></span>
		<!-- IF field.L_EXPLAIN --><br /><div class="gensmall">{field.L_EXPLAIN}</div><!-- ENDIF -->
	</td>
	<td class="{field.CLASS}" style="padding: 5px; background: none;"><!-- IF field.S_BBCB --><div class="post-text post-text-hide-flow"><!-- ELSE --><div class="gen"><!-- ENDIF -->{field.VALUE}</div></td>
</tr>
<!-- END field -->
<tr><td class="cat" colspan="2">&nbsp;{EXTRA_CONTENT_BOTTOM_FORM}&nbsp;</td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}

<!-- IF S_INPUT_ALLOWED or S_EDIT_ALLOWED -->
<div class="css-button-wrap">

<!-- IF S_INPUT_ALLOWED -->
<div class="forumline css-button-left"><div class="row1h css-button-body" data-href="{U_ITEM_ADD}"><img src="{IMG_CMS_ICON_ADD}" alt="{L_DB_ITEM_ADD}" title="{L_DB_ITEM_ADD}" />&nbsp;<b>{L_DB_ITEM_ADD}</b>&nbsp;</div></div>
<!-- ENDIF -->

<!-- IF S_EDIT_ALLOWED -->
<div class="forumline css-button-left"><div class="row1h css-button-body" data-href="{U_ITEM_EDIT}"><img src="{IMG_CMS_ICON_EDIT}" alt="{L_EDIT}" title="{L_EDIT}" />&nbsp;<b>{L_EDIT}</b>&nbsp;</div></div>
<!-- ENDIF -->

&nbsp;
</div>
<!-- ENDIF -->

{EXTRA_CONTENT_BOTTOM}

<!-- IF not S_HEADER_PROCESSED -->
<!-- INCLUDE overall_footer.tpl -->
<!-- ENDIF -->
