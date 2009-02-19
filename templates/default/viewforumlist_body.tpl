<!-- IF not S_BOT --><div style="float: right; text-align: right; margin-top: 5px;"><form action="{FULL_SITE_PATH}{U_SEARCH}" method="post"><input name="search_keywords" type="text" class="post search" style="width: 160px;" value="{L_SEARCH_THIS_FORUM}" onclick="if(this.value=='{L_SEARCH_THIS_FORUM}')this.value='';" onblur="if(this.value=='')this.value='{L_SEARCH_THIS_FORUM}';" /><input type="hidden" name="search_where" value="{FORUM_ID_FULL}" />&nbsp;<input type="submit" class="mainoption" value="{L_SEARCH}" /></form></div><!-- ENDIF -->

<div style="text-align: left; margin-top: 5px;"><a href="{U_VIEW_FORUM}" class="forumlink">{FORUM_NAME}</a><br /><br /></div>

<!-- IF not L_NO_TOPICS -->
<span class="gensmall">{L_SORT_TOPICS}:{DIVIDER}<a href="{U_NEWEST}">{L_SORT_TOPICS_NEWEST}</a>{DIVIDER}<a href="{U_OLDEST}">{L_SORT_TOPICS_OLDEST}</a>{DIVIDER}<a href="{U_AZ}">A-Z</a>{DIVIDER}<a href="{U_ZA}" >Z-A</a>{DIVIDER}<!-- BEGIN alphabetical_sort --><a href="{alphabetical_sort.U_LETTER}" >{alphabetical_sort.LETTER}</a>{alphabetical_sort.DIVIDER}<!-- END alphabetical_sort -->{DIVIDER}</span>
<br /><br />
<!-- ENDIF -->

<div style="text-align: left; margin-top: 5px;">
<!-- BEGIN topicrow -->
<span class="topiclink"><a href="{topicrow.U_VIEW_TOPIC}" class="topiclink"><img src="{FOLDER_IMG}" alt="{topicrow.TOPIC_TITLE_PLAIN}" title="{topicrow.TOPIC_TITLE_PLAIN}" />&nbsp;{topicrow.TOPIC_TITLE}</a></span><br />
<!-- END topicrow -->

<!-- IF L_NO_TOPICS -->
<span class="gen">{L_NO_TOPICS}</span>
<!-- ENDIF -->
</div>

<br />

<!-- IF not L_NO_TOPICS -->
<span class="gensmall">{L_SORT_TOPICS}:{DIVIDER}<a href="{U_NEWEST}">{L_SORT_TOPICS_NEWEST}</a>{DIVIDER}<a href="{U_OLDEST}">{L_SORT_TOPICS_OLDEST}</a>{DIVIDER}<a href="{U_AZ}">A-Z</a>{DIVIDER}<a href="{U_ZA}" >Z-A</a>{DIVIDER}<!-- BEGIN alphabetical_sort --><a href="{alphabetical_sort.U_LETTER}" >{alphabetical_sort.LETTER}</a>{alphabetical_sort.DIVIDER}<!-- END alphabetical_sort -->{DIVIDER}</span>
<br /><br />
<!-- ENDIF -->

<br />

<!-- IF not S_BOT --><div style="float: right; text-align: right; margin-top: 3px;">{JUMPBOX}</div><!-- ENDIF -->
<div style="text-align: left;"><span class="gen">{PAGE_NUMBER}</span><br /><span class="pagination">{PAGINATION}</span><br /></div>