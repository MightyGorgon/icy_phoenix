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
<!-- BEGIN header_desc -->
<script type="text/javaScript">
// <![CDATA[
function header_text_{MAIN_MENU_ID}(text)
{
	document.getElementById('h_description_{MAIN_MENU_ID}').innerHTML = '&nbsp;' + text + '&nbsp;';
}
// ]]>
</script>
<!-- END header_desc -->
<table class="nav-div" width="100%" border="0" align="center" cellspacing="0" cellpadding="0">
<tr>
	<td style="text-align: center;">
	<!-- BEGIN header_row --><span style="white-space: nowrap; vertical-align: middle;" onmouseover="header_text_{MAIN_MENU_ID}('{header_row.MENU_DESC}')" onmouseout="header_text_{MAIN_MENU_ID}('&nbsp;')">{header_row.MENU_SEP}&nbsp;{header_row.MENU_URL}&nbsp;</span><!-- END header_row -->
	</td>
</tr>
<!-- BEGIN header_desc -->
<tr class="nav-links"><td id="h_description_{MAIN_MENU_ID}" style="font-weight: bold; text-align: center;">&nbsp;</td></tr>
<!-- END header_desc -->
</table>
