<!-- INCLUDE print_header.tpl -->

<!-- <div style="margin: 0 auto; display: block; width: 960px !important;"> -->
<div style="margin: 0 auto; display: block; width: 680px !important;">
<div class="forumline" style="width: 100%;">
<div class="genmed" style="text-align: right; float: right;">
<!-- IF IS_ARTICLE -->
<!-- <b>{TOPIC_TITLE}</b> -->
<!-- ELSE -->
<form name="printview_form" action="{S_ACTION}" method="post">
<b><a href="{U_TOPIC}" class="topic_link">{TOPIC_TITLE}</a></b>&nbsp;&raquo;&nbsp;
<b>{L_SHOW_POSTS_FROM}</b>&nbsp;<input type="text" name="start" value="{POSTS_START}" size="6" maxlength="6" class="post" style="width: 30px;" />&nbsp;&nbsp;
<b>{L_SHOW_POSTS_TO}</b>&nbsp;<input type="text" name="limit" value="{POSTS_LIMIT}" size="6" maxlength="6" class="post" style="width: 30px;" />&nbsp;&nbsp;
{S_HIDDEN_FIELDS}
<input type="submit" name="submit" value="{L_GO}" class="mainoption" />&nbsp;<br />
</form>
<!-- ENDIF -->
</div>
<h1>{SITENAME}</h1><br />
<h2>{FORUM_NAME} - {TOPIC_TITLE}</h2><br /><br />
<!-- BEGIN postrow -->
<div class="forumline" style="margin: 10px; padding: 5px; display: block;">
<b>{postrow.POSTER_NAME}</b>&nbsp;[&nbsp;{postrow.POST_DATE}&nbsp;]<br />
<b>{L_POST_SUBJECT}:&nbsp;</b>{postrow.POST_SUBJECT}
<hr width="95%" class="sep" />
<!-- <div class="post-text post-text-hide-flow">&nbsp;</div> -->
<div class="post-text">{postrow.MESSAGE}</div>
<br clear="all" />
<br clear="all" />
</div>
<!-- END postrow -->
</div>
</div>

<!-- INCLUDE print_footer.tpl -->
