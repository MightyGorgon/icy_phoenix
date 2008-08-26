<!-- BEGIN style_select_on -->
<script type="text/javascript">
function SetTheme_{MAIN_MENU_ID}()
{
	document.ChangeTheme_{MAIN_MENU_ID}.submit();
	return true;
}
</script>
<!-- END style_select_on -->
<!-- BEGIN show_hide_switch -->
<script type="text/javascript">
<!--
tmp = 'quick_links';
if(GetCookie(tmp) == '2')
{
	ShowHide('quick_links','quick_links2','quick_links');
	setWidth(16);
}
//-->
</script>
<!-- END show_hide_switch -->
{IMG_THL}{IMG_THC}
<!-- BEGIN show_hide_switch -->
<img style="{SHOW_HIDE_PADDING}float:right;cursor:pointer;" src="{IMG_MINIMISE}" onclick="javascript:ShowHide('quick_links','quick_links2','quick_links');setWidth(16);" alt="{L_HIDE}" />
<!-- END show_hide_switch -->
<span class="forumlink">{MAIN_MENU_NAME}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<!-- BEGIN cat_row -->
<tr>
	<th style="cursor:pointer;cursor:hand;" align="left" onclick="ShowHide('menu_cat_{cat_row.CAT_ID}','menu_cat_{cat_row.CAT_ID}_h','menu_cat_{cat_row.CAT_ID}');">
		{cat_row.CAT_ICON}<a href="javascript:void(0);" title="{cat_row.CAT_ITEM}" style="vertical-align:top;text-decoration:none;"><b>{cat_row.CAT_ITEM}</b></a>
	</th>
</tr>
<tr>
	<td>
		<div id="menu_cat_{cat_row.CAT_ID}_h" style="display:'';position:relative;padding-top:0px;padding-bottom:0px;">
			<div class="nav-div" style="padding:2px;">
				<!-- BEGIN menu_row -->
				{cat_row.menu_row.MENU_URL}
				<!-- END menu_row -->
			</div>
		</div>
		<div id="menu_cat_{cat_row.CAT_ID}" style="display:'';position:relative;">
			<script type="text/javascript">
			<!--
			tmp = 'menu_cat_{cat_row.CAT_ID}';
			if(GetCookie(tmp) == '2')
			{
				ShowHide('menu_cat_{cat_row.CAT_ID}','menu_cat_{cat_row.CAT_ID}_h','menu_cat_{cat_row.CAT_ID}');
			}
			//-->
			</script>
		</div>
	</td>
</tr>
<!-- END cat_row -->
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}