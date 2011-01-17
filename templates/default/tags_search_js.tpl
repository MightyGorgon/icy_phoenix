<div class="align-right"><div id="jquery-live-search-box"><form method="post" action="{U_TAGS_SEARCH_PAGE}" onsubmit="return false;"><label><!-- {L_SEARCH}: -->&nbsp;<input name="tag_text" type="text" class="post search" style="width: 160px;" value="{L_TAGS_SEARCH}" onclick="if(this.value=='{L_TAGS_SEARCH}')this.value='';" onblur="if(this.value=='')this.value='{L_TAGS_SEARCH}';" /><input type="hidden" name="mode" value="view" /></label></form></div></div>

<script type="text/javascript" src="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}jquery/jquery_live_search.js"></script>
<link rel="stylesheet" href="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}jquery/jquery_live_search.css" type="text/css" media="screen" />
<script type="text/javascript">
// <![CDATA[
jQuery('#jquery-live-search-box input[name="tag_text"]').liveSearch({url:'{U_TAGS_SEARCH_PAGE}?tag_text='});
// ]]>
</script>

