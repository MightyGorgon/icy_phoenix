<!-- INCLUDE overall_header.tpl -->

<style type="text/css">@import url('http://www.google.com/cse/api/branding.css');</style>
<form action="{S_SEARCH_ACTION}" id="cse-search-box">
{IMG_THL}{IMG_THC}<span class="forumlink">{L_SEARCH_QUERY}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1" style="padding: 5px;" width="100%">

<br />
<!-- IF not GSEARCH_RESULTS -->
<div class="cse-branding-bottom">
	<div class="cse-branding-logo"><img src="http://www.google.com/images/poweredby_transparent/poweredby_FFFFFF.gif" alt="Google" /></div>
	<div class="cse-branding-form">
		<div>
			{S_ADSENSE_CODE}
			<input type="hidden" name="sitesearch" value="{S_SEARCH_DOMAIN}" />
			<input type="hidden" name="cof" value="FORID:9" />
			<input type="hidden" name="ie" value="{S_CONTENT_ENCODING}" />
			<input type="hidden" name="hl" value="{S_LANGUAGE}" />
			<input type="hidden" name="newwindow" value="1" />
			<input type="text" class="post search" name="q" size="60" onclick="if(this.value=='{L_SEARCH}')this.value='';" onblur="if(this.value=='')this.value='{L_SEARCH}';" value="{L_SEARCH}" />
			<input type="submit" class="post" name="sa" value="{L_SEARCH}" />
		</div>
	</div>
	<!-- <div class="cse-branding-text">{L_SEARCH}</div> -->
</div>
<!-- ELSE -->
<div id="cse-search-results"></div>
<!-- ENDIF -->
<br />

	</td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</form>

<script type="text/javascript">//<![CDATA[
var googleSearchIframeName = "cse-search-results";
var googleSearchFormName = "cse-search-box";
var googleSearchFrameWidth = 600;
var googleSearchDomain = "www.google.com";
var googleSearchPath = "/cse";
//]]>
</script>
<script type="text/javascript" src="http://www.google.com/afsonline/show_afs_search.js"></script>

<!-- INCLUDE overall_footer.tpl -->
