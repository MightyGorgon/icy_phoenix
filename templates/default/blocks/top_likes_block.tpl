<div class="post-text">
<!-- IF S_TOPICS_LIKES -->
<div class="gensmall">{L_TOP_LIKES_DESC}:<br />
<ol>
<!-- BEGIN likes_row -->
<li><a href="{likes_row.U_TOPIC}" class="gensmall" title="{likes_row.TOPIC_TITLE}">{likes_row.TOPIC_TITLE_SHORT}&nbsp;[{likes_row.LIKES_COUNT}]</a></li>
<!-- END likes_row -->
</ol>
</div>
<!-- ELSE -->
{L_TOP_LIKES_NO_TOPICS}
<!-- ENDIF -->
</div>