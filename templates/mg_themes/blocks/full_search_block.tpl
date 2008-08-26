<script type="text/javascript">
	<!--
		function checkSearch()
		{
			if (document.full_search_block.search_engine.value == 'google')
			{
				window.open('http://www.google.com/search?q=' + document.full_search_block.search_keywords.value, '_google', '');
				return false;
			}
			if (document.full_search_block.search_engine.value == 'yahoo')
			{
				window.open('http://search.yahoo.com/search?ei=UTF-8&fr=sfp&p=' + document.full_search_block.search_keywords.value, '_yahoo', '');
				return false;
			}
			if (document.full_search_block.search_engine.value == 'hotbot')
			{
				window.open('http://www.hotbot.com/default.asp?prov=HotBot&query=' + document.full_search_block.search_keywords.value, '_hotbot', '');
				return false;
			}
			if (document.full_search_block.search_engine.value == 'lycos')
			{
				window.open('http://search.lycos.com/default.asp?loc=searchbox&query=' + document.full_search_block.search_keywords.value, '_lycos', '');
				return false;
			}
			if (document.full_search_block.search_engine.value == 'jeeves')
			{
				window.open('http://web.ask.com/web?q=' + document.full_search_block.search_keywords.value, '_jeeves', '');
				return false;
			}
			if (document.full_search_block.search_engine.value == 'mamma')
			{
				window.open('http://www.mamma.com/Mamma?query=' + document.full_search_block.search_keywords.value, '_mamma', '');
				return false;
			}
			if (document.full_search_block.search_engine.value == 'ungoogle')
			{
				window.open('http://search.curryguide.com/execute/search/web.cgi?ac=ungoogle1&query=' + document.full_search_block.search_keywords.value, '_ungoogle', '');
				return false;
			}
			if (document.full_search_block.search_engine.value == 'dogpile')
			{
				window.open('http://www.dogpile.com/info.dogpl/search/web/' + document.full_search_block.search_keywords.value, '_dogpile', '');
				return false;
			}
			if (document.full_search_block.search_engine.value == 'altavista')
			{
				window.open('http://www.altavista.com/web/results?q=' + document.full_search_block.search_keywords.value, '_altavista', '');
				return false;
			}
			if (document.full_search_block.search_engine.value == 'aol')
			{
				window.open('http://netfind.aol.com/aolcom/search?invocationType=topsearchbox.%2Faolcom%2Findex.jsp&query=' + document.full_search_block.search_keywords.value, '_aol', '');
				return false;
			}
			if (document.full_search_block.search_engine.value == 'alltheweb')
			{
				window.open('http://www.alltheweb.com/search?avkw=fogg&cat=web&cs=utf-8&q=' + document.full_search_block.search_keywords.value, '_alltheweb', '');
				return false;
			}
			if (document.full_search_block.search_engine.value == 'wisenut')
			{
				window.open('http://www.wisenut.com/search/query.dll?q=' + document.full_search_block.search_keywords.value, '_wisenut', '');
				return false;
			}
			if (document.full_search_block.search_engine.value == 'webcrawler')
			{
				window.open('http://dpxml.webcrawler.com/info.wbcrwl/search/web/' + document.full_search_block.search_keywords.value, '_webcrawler', '');
				return false;
			}
			if (document.full_search_block.search_engine.value == 'searchcom')
			{
				window.open('http://www.search.com/search?channel=1&tag=st.se.fd..sch&q=' + document.full_search_block.search_keywords.value, '_searchcom', '');
				return false;
			}
			if (document.full_search_block.search_engine.value == 'vivismo')
			{
				window.open('http://vivisimo.com/search?v%3Asources=MSN%2CNetscape%2CLycos%2CLooksmart%2COverture&query=' + document.full_search_block.search_keywords.value, '_vivismo', '');
				return false;
			}
			if (document.full_search_block.search_engine.value == 'teoma')
			{
				window.open('http://s.teoma.com/search?q=' + document.full_search_block.search_keywords.value, '_teoma', '');
				return false;
			}
			if (document.full_search_block.search_engine.value == 'overture')
			{
				window.open('http://www.overture.com/d/search/?type=home&mkt=us&Keywords=' + document.full_search_block.search_keywords.value, '_overture', '');
				return false;
			}
			if (document.full_search_block.search_engine.value == 'gocom')
			{
				window.open('http://go.google.com/hws/search?client=disney-go&cof=AH%3Acenter%3BAWFID%3A7e572b45105f192b%3B&q=' + document.full_search_block.search_keywords.value, '_gocom', '');
				return false;
			}
			if (document.full_search_block.search_engine.value == 'excite')
			{
				window.open('http://msxml.excite.com/_1_24OITEN09FGCZ4__info.xcite/dog/results?otmpl=dog/webresults.htm&qcat=web&foo=bar&qk=20&fs=infospace_excite_search&stype=web&qkw=' + document.full_search_block.search_keywords.value, '_excite', '');
				return false;
			}
			else
			{
				return true;
			}
		}
	//-->
</script>
<form name="full_search_block" method="post" action="{U_SEARCH}" onsubmit="return checkSearch()">
<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td align="center">
		<span class="gensmall">{L_SEARCH2}</span>
		</td>
	</tr>
	<tr>
		<td align="center"><input class="post" type="text" name="search_keywords" size="20" /></td>
	</tr>
	<tr>
		<td align="center">
			<span class="gensmall">{L_SEARCH_AT}</span>
		</td>
	</tr>
	<tr>
		<td align="center">
			<select class="post" name="search_engine">
				<option value="site">{L_FORUM_OPTION}</option>
				<option value="google">Google</option>
				<option value="yahoo">Yahoo</option>
				<option value="hotbot">HotBot</option>
				<option value="lycos">Lycos</option>
				<option value="jeeves">Ask Jeeves</option>
				<option value="mamma">Mamma</option>
				<option value="ungoogle">Ungoogle</option>
				<option value="dogpile">Dogpile</option>
				<option value="altavista">AltaVista</option>
				<option value="aol">AOL NetFind</option>
				<option value="alltheweb">All The Web</option>
				<option value="wisenut">WiseNut</option>
				<option value="webcrawler">WebCrawler</option>
				<option value="searchcom">Search.com</option>
				<option value="vivismo">Vivismo</option>
				<option value="teoma">Teoma</option>
				<option value="overture">Overture</option>
				<option value="gocom">Go.com</option>
				<option value="excite">Excite</option></select>
			</select>
		</td>
	</tr>
	<tr>
		<td align="center">
			<a href="{U_SEARCH}" class="gensmall">{L_ADVANCED_SEARCH}</a>
		</td>
	</tr>
	<tr>
		<td align="center"><br /><input class="mainoption" type="submit" value="{L_SEARCH}" /></td>
	</tr>

</table>
<input type="hidden" name="search_fields" value="all" />
<input type="hidden" name="show_results" value="topics" />
</form>