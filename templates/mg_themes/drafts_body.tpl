<script type="text/javascript">
<!--
function setCheckboxes(theForm, elementName, isChecked)
{
	var chkboxes = document.forms[theForm].elements[elementName];
	var count = chkboxes.length;

	if (count)
	{
		for (var i = 0; i < count; i++)
		{
			chkboxes[i].checked = isChecked;
		}
	}
	else
	{
		chkboxes.checked = isChecked;
	}
	return true;
}
//-->
</script>
<form name="drafts_form" id="drafts_form" method="post" action="{S_FORM_ACTION}">
<input type="hidden" name="mode" value="editprofile" />
{IMG_TBL}<div class="forumline nav-div">
	<p class="nav-header">
		<a href="{U_PORTAL}">{L_HOME}</a>{NAV_SEP}<a href="{U_PROFILE}" >{L_CPL_NAV}</a>{NAV_SEP}<a href="{U_CPL_DRAFTS}" class="nav-current">{L_DRAFTS}</a>
	</p>
	<div class="nav-links">
		<div class="nav-links-left">{CURRENT_TIME}</div>
		&nbsp;
		<a href="#" onclick="setCheckboxes('drafts_form', 'drafts_list[]', true); return false;">{L_MARK_ALL}</a>&nbsp;|&nbsp;<a href="#" onclick="setCheckboxes('drafts_form', 'drafts_list[]', false); return false;">{L_UNMARK_ALL}</a>
	</div>
</div>{IMG_TBR}

<!-- INCLUDE profile_cpl_menu_inc_start.tpl -->

<!-- BEGIN switch_drafts -->
{IMG_THL}{IMG_THC}<span class="forumlink">{L_DRAFTS}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th width="4%">&nbsp;</th>
	<th width="28%">{L_DRAFTS_CATEGORY}</th>
	<th width="38%">{L_DRAFTS_SUBJECT}</th>
	<th width="8%">{L_DRAFTS_ACTION}</th>
	<th width="20%">{L_DATE}</th>
	<th width="2%">&nbsp;</th>
</tr>
<!-- END switch_drafts -->
<!-- BEGIN draft_row -->
<tr>
	<td class="{draft_row.ROW_CLASS} row-center">{draft_row.DRAFT_IMG}&nbsp;</td>
	<td class="{draft_row.ROW_CLASS}h row-forum" onclick="window.location.href='{draft_row.DRAFT_CAT_LINK}'"><span class="topiclink">{draft_row.DRAFT_CAT}</span></td>
	<td class="{draft_row.ROW_CLASS}h row-forum" onclick="window.location.href='{draft_row.DRAFT_TITLE_LINK}'"><span class="topiclink">{draft_row.DRAFT_TITLE}</span></td>
	<td class="{draft_row.ROW_CLASS} row-center">
		<span class="gensmall"><a href="{draft_row.U_DRAFT_LOAD}">{L_DRAFTS_LOAD}</a><br /><a href="{draft_row.U_DRAFT_DELETE}">{L_DRAFTS_DELETE}</a></span>
	</td>
	<td class="{draft_row.ROW_CLASS} row-center"><span class="gensmall">{draft_row.DRAFT_TIME}</span></td>
	<td class="{draft_row.ROW_CLASS} row-center" nowrap="nowrap"><input type="checkbox" name="drafts_list[]" value="{draft_row.S_DRAFT_ID}" /></td>
</tr>
<!-- END draft_row -->
<!-- BEGIN switch_drafts -->
<tr><td class="spaceRow" colspan="6"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
<tr><td colspan="6" class="catBottom"><input type="submit" name="kill_drafts" class="liteoption" value="{L_DRAFTS_DELETE_SEL}" />&nbsp;&nbsp;</td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<div align="right"><span class="pagination"><b>{PAGINATION}</b></span></div>
<!-- END switch_drafts -->
<!-- BEGIN switch_no_drafts -->
{IMG_THL}{IMG_THC}<span class="forumlink">{L_DRAFTS}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr><td class="row1 row-center"><br /><br /><span class="gen">{L_NO_DRAFTS}</span><br /><br /><br /></td></tr>
<tr><td class="spaceRow"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
<tr><td class="catBottom">&nbsp;&nbsp;</td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<!-- END switch_no_drafts -->
<!-- INCLUDE profile_cpl_menu_inc_end.tpl -->

</form>