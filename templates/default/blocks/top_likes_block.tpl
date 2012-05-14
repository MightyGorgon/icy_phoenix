<div class="post-text">
<!-- IF S_TOPICS_LIKES -->
<div class="gensmall">{L_TOP_LIKES_DESC_T}:<br />
<ol>
<!-- BEGIN likes_row -->
<li><a href="{likes_row.U_TOPIC}" class="gensmall" title="{likes_row.TOPIC_TITLE}">{likes_row.TOPIC_TITLE_SHORT}<!-- IF S_TOPICS_LIKES_COUNTER -->&nbsp;[{likes_row.LIKES_COUNT}]<!-- ENDIF --></a></li>
<!-- END likes_row -->
</ol>
</div>
<!-- ELSE -->
{L_TOP_LIKES_NO_TOPICS_T}
<!-- ENDIF -->
</div>