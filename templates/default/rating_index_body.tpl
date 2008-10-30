<!--
# Advanced Users will probably want to customize their template table
# Below is a list of other items you can add between the <--! BEGIN ratingrow - -> and END of it
# to have display for each topic.
#
#	{ratingrow.CLASS} = an alternating variable for each topic that specifies row1 or row2
#	{ratingrow.RANK} = the topic's rank from one to max in admin panel
#	{ratingrow.LAST_RATER} = last person to rate this topc
#	{ratingrow.LAST_RATER_TIME} = the date/time the last person rated it
#	{ratingrow.FORUM} = the forum containing the topic
#	{ratingrow.RATING} = the topics average rating
#	{ratingrow.MIN} = miniumum rating given
#	{ratingrow.MAX} = maximum rating given
#	{ratingrow.L_VIEW_DETAILS} = if the admin option is on, this gives a little "View details" area link
#	{ratingrow.NUMBER_OF_RATES} = # of times the topic has been rated
-->

{IMG_THL}{IMG_THC}<span class="forumlink">{L_TOP_RATED}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<!-- BEGIN notopics -->
<tr><td class="row1" valign="top"><span class="gensmall">{notopics.MESSAGE}</span></td></tr>
<!-- END notopics -->
<!-- BEGIN ratingrow -->
<tr><td class="row1" valign="top"><span class="gensmall">{ratingrow.RATING}&nbsp;:&nbsp;<a href="{ratingrow.URL}">{ratingrow.TITLE}</a></span></td></tr>
<!-- END ratingrow -->
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}