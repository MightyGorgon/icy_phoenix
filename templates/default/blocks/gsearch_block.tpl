<!-- SiteSearch Google -->
<form method="get" action="http://www.google.com/custom" target="google_window">
<center>
<a href="http://www.google.com/"><img src="http://www.google.com/logos/Logo_25wht.gif" alt="Google" title="Google" align="middle" /></a>
<input type="hidden" name="ie" value="utf8" />
<input type="hidden" name="oe" value="utf8" />
<input type="hidden" name="safe" value="active" />
<input type="hidden" name="cof" value="GALT:#DD3333;GL:1;DIV:#557799;VLC:DD3333;AH:center;BGC:EEEEEE;LBGC:557799;ALC:557799;LC:557799;T:224488;GFNT:DD3333;GIMP:DD3333;LH:31;LW:88;L:http://{GSEARCH_BANNER};S:http://{GSEARCH_SITE}/;FORID:1" />
<!-- <input type="hidden" name="domains" value="{GSEARCH_SITE};www.icyphoenix.com" /> -->
<input type="hidden" name="domains" value="{GSEARCH_SITE};" />
<!-- IF GSEARCH_HOR -->
<input type="text" name="q" size="60" maxlength="255" value="{GSEARCH_TEXT}" class="post search" onclick="if(this.value=='{GSEARCH_TEXT}')this.value='';" onblur="if(this.value=='')this.value='{GSEARCH_TEXT}';" />&nbsp;<input type="submit" name="sa" class="mainoption" value="{L_SEARCH}" /><br />
<div style="width: 100%; text-align: center; padding: 10px;">
<input type="radio" name="sitesearch" value="" checked="checked" /><span class="gensmall">Web</span>&nbsp;
<input type="radio" name="sitesearch" value="{GSEARCH_SITE}" /><span class="gensmall">{GSEARCH_TEXT}</span>&nbsp;
<!-- <input type="radio" name="sitesearch" value="www.icyphoenix.com" /><span class="gensmall">Icy Phoenix</span> -->
</div>
<!-- ELSE -->
<input type="text" name="q" size="20" maxlength="255" value="{GSEARCH_TEXT}" class="post search" onclick="if(this.value=='{GSEARCH_TEXT}')this.value='';" onblur="if(this.value=='')this.value='{GSEARCH_TEXT}';" /><br /><br />
<input type="submit" name="sa" class="mainoption" value="{L_SEARCH}" /><br /><br />
<div style="width: 100%; text-align: left; padding-left: 20px;">
<input type="radio" name="sitesearch" value="" checked="checked" /><span class="gensmall">Web</span><br />
<input type="radio" name="sitesearch" value="{GSEARCH_SITE}" /><span class="gensmall">{GSEARCH_TEXT}</span><br />
<!-- <input type="radio" name="sitesearch" value="www.icyphoenix.com" /><span class="gensmall">Icy Phoenix</span><br /> -->
</div>
<!-- ENDIF -->
</center>
</form>
<!-- SiteSearch Google -->