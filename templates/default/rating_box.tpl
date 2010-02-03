<!-- IF S_RATING_ALLOWED -->
<form name="ratingform" method="post" action="{S_RATE_ACTION}">
<span class="gensmall">{L_HON_SELECT_RATE}:&nbsp;</span>{S_RATING_LINK}<span class="gensmall">&nbsp;{S_RATING_SELECT}&nbsp;</span><input type="submit" name="submit" value="{L_HON_RATE}" class="mainoption" /><br />
<span class="gensmall">{ALREADY_RATED}</span><br />
{S_RATE_HIDDEN_FIELDS}
</form>
<!-- ELSE -->
<br clear="all" /><span class="genmed"><b>{L_HON_LOCKED}</b></span><br clear="all" /><br clear="all" />
<!-- ENDIF -->