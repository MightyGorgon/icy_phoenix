<!-- INCLUDE overall_header.tpl -->

<style type="text/css">@import url('http://www.google.com/cse/api/branding.css');</style>
<form action="{S_SEARCH_ACTION}" id="cse-search-box">
{IMG_THL}{IMG_THC}<span class="forumlink">{L_SEARCH_QUERY}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1" style="padding: 5px;" width="100%">

<div>
<script>
(function() {
	var cx = '{S_GOOGLE_CUSTOM_SEARCH}';
	var gcse = document.createElement('script');
	gcse.type = 'text/javascript';
	gcse.async = true;
	gcse.src = (document.location.protocol == 'https:' ? 'https:' : 'http:') +
			'//www.google.com/cse/cse.js?cx=' + cx;
	var s = document.getElementsByTagName('script')[0];
	s.parentNode.insertBefore(gcse, s);
})();
</script>
<gcse:search></gcse:search>
</div>

	</td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</form>

<!-- INCLUDE overall_footer.tpl -->