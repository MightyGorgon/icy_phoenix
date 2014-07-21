<!-- IF S_STYLES_SELECT -->
<script type="text/javascript">
function SetTheme_{MAIN_MENU_ID}()
{
	document.ChangeTheme_{MAIN_MENU_ID}.submit();
	return true;
}
</script>
<!-- ENDIF -->

<table class="nav-div" width="100%" cellspacing="0" cellpadding="0" border="0">
<!-- BEGIN index_row -->

<tr>
	<!-- BEGIN index_items -->
	<td class="{index_items.ROW_CLASS} row-center-small" style="width: {index_row.index_items.ROW_WIDTH}%;"><b>{index_row.index_items.CAT_ITEM}</b></td>
	<!-- END index_items -->
</tr>

<tr>
	<!-- BEGIN index_col -->
	<td class="{index_row.index_col.ROW_CLASS} row-center-small">
		<table>
			<tr>
				<td class="tw50px">{index_row.index_col.CAT_ICON}</td>
				<td class="genmed tdalignl">
					<table>
						<!-- BEGIN menu_row -->
						<tr><td><span class="forumlink">{index_row.index_col.menu_row.MENU_URL}<br /></span>{index_row.index_col.menu_row.MENU_DESC}</td></tr>
						<!-- END menu_row -->
					</table>
				</td>
			</tr>
		</table>
	</td>
<!-- END index_col -->	
</tr>

<!-- END index_row -->
</table>