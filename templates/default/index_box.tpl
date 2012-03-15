<?php
$banner_processed = false;
$catrow_count = (isset($this->_tpldata['catrow.'])) ? count($this->_tpldata['catrow.']) : 0;
for($catrow_i = 0; $catrow_i < $catrow_count; $catrow_i++)
{
	$catrow_item = &$this->_tpldata['catrow.'][$catrow_i];
	// check for new messages
	$new_msg = false;
	$forumrow_count = isset($catrow_item['forumrow.']) ? count($catrow_item['forumrow.']) : 0;
	for ($forumrow_i = 0; $forumrow_i < $forumrow_count; $forumrow_i++)
	{
		$forumrow_item = &$catrow_item['forumrow.'][$forumrow_i];
		$new_item = strpos($forumrow_item['FORUM_FOLDER_IMG'], '_unread') > 0 ? true : false;
		if($new_item)
		{
			$new_msg = true;
			$forumrow_item['LINK_CLASS'] = '-new';
		}
	}
	// add xs switch
	$catrow_item['LINK_CLASS'] = $new_msg ? '-new' : '';
}
?>
<!-- BEGIN catrow -->
<!-- BEGIN tablehead -->
<div id="{catrow.MAIN_CAT_ID}_f_h" style="display: none;">
{IMG_THL}{IMG_THC}<img class="max-min-right" style="{SHOW_HIDE_PADDING}" src="{IMG_MAXIMISE}" onclick="ShowHide('{catrow.MAIN_CAT_ID}_f','{catrow.MAIN_CAT_ID}_f_h','{catrow.MAIN_CAT_ID}_f');" alt="{L_SHOW}" /><span class="forumlink">{catrow.tablehead.L_FORUM}</span>{IMG_THR_ALT}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr><td>&nbsp;</td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</div>
<div id="{catrow.MAIN_CAT_ID}_f">
<script type="text/javascript">
<!--
tmp = '{catrow.MAIN_CAT_ID}_f';
if(GetCookie(tmp) == '2')
{
	ShowHide('{catrow.MAIN_CAT_ID}_f', '{catrow.MAIN_CAT_ID}_f_h', '{catrow.MAIN_CAT_ID}_f');
}
//-->
</script>
{IMG_THL_ALT}{IMG_THC}<img class="max-min-right" style="{SHOW_HIDE_PADDING}" src="{IMG_MINIMISE}" onclick="ShowHide('{catrow.MAIN_CAT_ID}_f','{catrow.MAIN_CAT_ID}_f_h','{catrow.MAIN_CAT_ID}_f');" alt="{L_HIDE}" /><span class="forumlink">{catrow.tablehead.L_FORUM}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<!-- BEGIN cathead -->
<tr>
	<!-- BEGIN inc -->
	<td width="37" class="{catrow.cathead.inc.INC_CLASS}"><img src="{SPACER}" width="37" height="0" /></td>
	<!-- END inc -->
	<td class="{catrow.cathead.CLASS_CAT}" width="100%" colspan="{catrow.cathead.INC_SPAN}"><span class="cattitle"><a href="{catrow.cathead.U_VIEWCAT}" class="cattitle" title="{catrow.cathead.CAT_DESC}">{catrow.cathead.CAT_TITLE}</a></span></td>
	<td class="{catrow.cathead.CLASS_ROWPIC}" colspan="3" align="right">&nbsp;</td>
</tr>
<!-- END cathead -->
<tr>
	<th colspan="{catrow.tablehead.INC_SPAN}" nowrap="nowrap">&nbsp;{L_FORUM}&nbsp;</th>
	<!--
	<th width="50">&nbsp;{L_TOPICS}&nbsp;</th>
	<th width="50">&nbsp;{L_POSTS}&nbsp;</th>
	-->
	<th width="120" colspan="2">&nbsp;{L_TOPICS}&nbsp;/&nbsp;{L_POSTS}&nbsp;</th>
	<th width="180">&nbsp;{L_LASTPOST}&nbsp;</th>
</tr>
<!-- END tablehead -->
<?php
if (ereg("c=", $_SERVER['REQUEST_URI']) || ereg("-vc", $_SERVER['REQUEST_URI']))
{
?>
<!-- BEGIN cathead -->
<tr>
	<!-- BEGIN inc -->
	<td width="37" class="{catrow.cathead.inc.INC_CLASS}"><img src="{SPACER}" width="37" height="0" /></td>
	<!-- END inc -->
	<td class="forum-buttons2" colspan="6" align="left"><img src="{CAT_BLOCK_IMG}" style="vertical-align: middle;" />&nbsp;<a href="{catrow.cathead.U_VIEWCAT}" title="{catrow.cathead.CAT_DESC}">{catrow.cathead.CAT_TITLE}</a></td>
</tr>
<!-- END cathead -->
<?php
}
?>
<!-- BEGIN forumrow -->
<tr>
	<!-- BEGIN inc -->
	<td width="37" class="{catrow.forumrow.inc.INC_CLASS}"{catrow.forumrow.LINKS_ROWSPAN}><img src="{SPACER}" width="37" height="0" alt="" /></td>
	<!-- END inc -->
	<td class="{catrow.forumrow.INC_CLASS}" style="padding-left: 1px; padding-right: 3px; vertical-align: middle; min-height: 50px;" width="37" height="50"{catrow.forumrow.LINKS_ROWSPAN}>{catrow.forumrow.U_MARK_ALWAYS_READ}</td>
	<td class="row1h{catrow.forumrow.LINK_CLASS} row-forum" width="100%" colspan="{catrow.forumrow.INC_SPAN}" onclick="window.location.href='{catrow.forumrow.U_VIEWFORUM}'">
		<table width="100%" cellspacing="0" cellpadding="2" border="0">
		<tr>
			<!-- BEGIN forum_icon -->
			<td align="center"><a href="{catrow.forumrow.U_VIEWFORUM}"><img src="{catrow.forumrow.ICON_IMG}" alt="{catrow.forumrow.FORUM_NAME}" style="padding:3px;" /></a></td>
			<!-- END forum_icon -->
			<td width="100%">
				<!-- BEGIN forum_link_no -->
				<div style="float: right; padding: 6px; vertical-align: bottom;">{catrow.forumrow.RSS_FEED_ICON}</div>
				<!-- END forum_link_no -->
				<span class="forumlink"><a class="forumlink{catrow.forumrow.LINK_CLASS}" href="{catrow.forumrow.U_VIEWFORUM}">{catrow.forumrow.FORUM_NAME}</a></span><br />
				<span class="genmed">{catrow.forumrow.FORUM_DESC}</span>
				<span class="moderators">{catrow.forumrow.L_MODERATOR}<b>{catrow.forumrow.MODERATORS}&nbsp;</b></span>
			</td>
		</tr>
		</table>
	</td>
	<!-- BEGIN forum_link_no -->
	<td class="row1 row-center-small" width="120" colspan="2"{catrow.forumrow.LINKS_ROWSPAN}><div class="gensmall">{L_TOPICS}:&nbsp;{catrow.forumrow.TOPICS}<br />{L_POSTS}:&nbsp;{catrow.forumrow.POSTS}{catrow.forumrow.ONLINE}</div></td>
	<td class="row1 row-center-small" width="180" nowrap="nowrap"{catrow.forumrow.LINKS_ROWSPAN}><span class="gensmall"><!-- IF S_BOT -->&nbsp;<!-- ELSE -->{catrow.forumrow.LAST_POST}<!-- ENDIF --></span></td>
	<!-- END forum_link_no -->
	<!-- BEGIN forum_link -->
	<td class="row1 row-center" align="center" valign="middle" height="50" colspan="3"{catrow.forumrow.LINKS_ROWSPAN}><span class="gensmall">{catrow.forumrow.forum_link.HIT_COUNT}&nbsp;</span></td>
	<!-- END forum_link -->
</tr>
<!-- IF catrow.forumrow.LINKS -->
<tr><td class="row1h" style="padding: 0px;"><span class="gensmall">{catrow.forumrow.L_LINKS}&nbsp;</span><span class="forumlink2{XS_NEW}">{catrow.forumrow.LINKS}&nbsp;</span></td></tr>
<!-- ENDIF -->
<?php
if (!$banner_processed)
{
?>
<!-- IF FORUMINDEX_BANNER_ELEMENT -->
<tr><td class="row1h-new" colspan="8"><span class="forumlink2-new">{FORUMINDEX_BANNER_ELEMENT}&nbsp;</span></td></tr>
<!-- ENDIF -->
<?php
	$banner_processed = true;
}
?>
<!-- END forumrow -->

<!-- BEGIN tablefoot -->
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</div>
<!-- END tablefoot -->
<!-- END catrow -->
