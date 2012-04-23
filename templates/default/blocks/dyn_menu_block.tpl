<!-- IF S_STYLES_SELECT -->
<script type="text/javascript">
// <![CDATA[
function SetTheme_{MAIN_MENU_ID}()
{
	document.ChangeTheme_{MAIN_MENU_ID}.submit();
	return true;
}
// ]]>
</script>
<!-- ENDIF -->
<!-- BEGIN show_hide_switch -->
<script type="text/javascript">
<!--
tmp = 'quick_links';
if(GetCookie(tmp) == '2')
{
	ShowHide('quick_links', 'quick_links2', 'quick_links');
	setWidth('var_width', 16);
	//setWidth('full_width', '100%');
}
//-->
</script>
<!-- END show_hide_switch -->
{IMG_THL}{IMG_THC}
<!-- BEGIN show_hide_switch -->
<img class="max-min-right" style="{SHOW_HIDE_PADDING}" src="{IMG_MINIMISE}" onclick="ShowHide('quick_links', 'quick_links2', 'quick_links'); setWidth('var_width',16); setWidth('full_width', '100%');" alt="{L_HIDE}" />
<!-- END show_hide_switch -->
<span class="forumlink">{MAIN_MENU_NAME}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">

<!-- IF NAV_MENU_ADS_TOP -->
<tr>
	<th style="cursor: pointer;" align="left">
		<img src="{IMG_NAV_MENU_SPONSOR}" alt="{L_SPONSORS_LINKS}" title="{L_SPONSORS_LINKS}" />&nbsp;
		<a href="#" title="{L_SPONSORS_LINKS}" class="nav-menu-link"><b>{L_SPONSORS_LINKS}</b></a>
	</th>
</tr>
<tr><td class="row1">{NAV_MENU_ADS_TOP}</td></tr>
<!-- ENDIF -->

<!-- BEGIN cat_row -->
<tr>
	<th style="cursor: pointer;" align="left" onclick="ShowHide('menu_cat_{cat_row.CAT_ID}', 'menu_cat_{cat_row.CAT_ID}_h', 'menu_cat_{cat_row.CAT_ID}');">
		{cat_row.CAT_ICON}<a href="#" onclick="return false;" title="{cat_row.CAT_ITEM}" class="nav-menu-link"><b>{cat_row.CAT_ITEM}</b></a>
	</th>
</tr>
<tr>
	<td>
		<div id="menu_cat_{cat_row.CAT_ID}_h" class="nav-menu">
			<div class="nav-div" style="padding: 2px;">
				<!-- BEGIN menu_row -->
				{cat_row.menu_row.MENU_URL}
				<!-- END menu_row -->
			</div>
		</div>
		<div id="menu_cat_{cat_row.CAT_ID}" class="js-sh-box">
			<script type="text/javascript">
			// <![CDATA[
			tmp = 'menu_cat_{cat_row.CAT_ID}';
			if(GetCookie(tmp) == '2')
			{
				ShowHide('menu_cat_{cat_row.CAT_ID}', 'menu_cat_{cat_row.CAT_ID}_h', 'menu_cat_{cat_row.CAT_ID}');
			}
			// ]]>
			</script>
		</div>
	</td>
</tr>
<!-- END cat_row -->

<!-- IF NAV_MENU_ADS_BOTTOM -->
<tr>
	<th style="cursor: pointer;" align="left">
		<img src="{IMG_NAV_MENU_SPONSOR}" alt="{L_SPONSORS_LINKS}" title="{L_SPONSORS_LINKS}" />&nbsp;
		<a href="#" title="{L_SPONSORS_LINKS}" class="nav-menu-link"><b>{L_SPONSORS_LINKS}</b></a>
	</th>
</tr>
<tr><td class="row1">{NAV_MENU_ADS_BOTTOM}</td></tr>
<!-- ENDIF -->

</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}