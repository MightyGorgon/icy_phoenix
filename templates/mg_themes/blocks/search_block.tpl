<script type="text/javascript">
<!--
function checkSearch()
{
	if (document.search_block.search_engine.value == 'google')
	{
		window.open('http://www.google.com/search?q=' + document.search_block.search_keywords.value, '_google', '');
		return false;
	}
	else
	{
		return true;
	}
}
//-->
</script>
<form name="search_block" method="post" action="{U_SEARCH}" onsubmit="return checkSearch()">
<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr><td align="center"><br /><span class="gensmall">{L_SEARCH2}</span></td></tr>
	<tr><td align="center"><input class="post" type="text" name="search_keywords" size="20" /></td></tr>
	<tr><td align="center"><span class="gensmall">{L_SEARCH_AT}</span></td></tr>
	<tr>
		<td align="center"><select class="post" name="search_engine">
			<option value="site">{L_FORUM_OPTION}</option>
			<option value="google">Google</option>
		</select></td>
	</tr>
	<tr><td align="center"><a href="{U_SEARCH}" class="gensmall">{L_ADVANCED_SEARCH}</a></td></tr>
	<tr><td align="center"><br /><input class="mainoption" type="submit" value="{L_SEARCH}" /><br /><br /></td></tr>
</table>
<input type="hidden" name="search_fields" value="all" />
<input type="hidden" name="show_results" value="topics" />
</form>