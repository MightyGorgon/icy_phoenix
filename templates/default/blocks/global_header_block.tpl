<!-- IF S_STYLES_SELECT -->
<script type="text/javascript">
function SetTheme_{MAIN_MENU_ID}()
{
	document.ChangeTheme_{MAIN_MENU_ID}.submit();
	return true;
}
</script>
<!-- ENDIF -->
<script type="text/javascript" src="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}js/chrome.js"></script>
<div id="chromemenu_{MAIN_MENU_ID}" class="chromestyle">
<ul>
	<!-- BEGIN header_row -->
	<li>{header_row.CAT_ICON}<a href="#" rel="dropmenu_{MAIN_MENU_ID}_{header_row.CAT_ID}">{header_row.CAT_ITEM}</a>
	<div id="dropmenu_{MAIN_MENU_ID}_{header_row.CAT_ID}" class="dropmenudiv"><!-- BEGIN menu -->{header_row.menu.MENU_URL}<!-- END menu --></div>
	</li>
	<!-- END header_row -->
</ul>
</div>
<script type="text/javascript">cssdropdown.startchrome("chromemenu_{MAIN_MENU_ID}");</script>
