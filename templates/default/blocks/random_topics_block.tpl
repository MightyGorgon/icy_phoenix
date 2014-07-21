<table>
<tr>
	<td align="left">
		<span class="gensmall">
			<!-- BEGIN scroll -->
			<marquee id="random_topics" behavior="scroll" direction="up" height="200" scrolldelay="100" scrollamount="2" loop="true" onmouseover="this.stop()" onmouseout="this.start()">
				<!-- BEGIN random_topic_row -->
				<b>&nbsp;&#8226;&nbsp;</b>{scroll.random_topic_row.S_POSTTIME}<b>{NAV_SEP}</b>
				<b><a href="{scroll.random_topic_row.U_FORUM}" title="{scroll.random_topic_row.L_FORUM}">{scroll.random_topic_row.L_FORUM}</a>{NAV_SEP}
				<a href="{scroll.random_topic_row.U_TITLE}" title="{scroll.random_topic_row.L_TITLE}">{scroll.random_topic_row.L_TITLE}</a>{NAV_SEP}</b>
				{scroll.random_topic_row.S_POSTER}<br /><br />
				<!-- END random_topic_row -->
			</marquee>
			<!-- END scroll -->
			<!-- BEGIN static -->
				<!-- BEGIN random_topic_row -->
				<b>&nbsp;&#8226;&nbsp;</b>{scroll.random_topic_row.S_POSTTIME}<b>{NAV_SEP}</b>
				<b><a href="{static.random_topic_row.U_FORUM}" title="{static.random_topic_row.L_FORUM}">{static.random_topic_row.L_FORUM}</a>{NAV_SEP}
				<a href="{static.random_topic_row.U_TITLE}" title="{static.random_topic_row.L_TITLE}">{static.random_topic_row.L_TITLE}</a>{NAV_SEP}</b>
				{static.random_topic_row.S_POSTER}<br /><br />
				<!-- END random_topic_row -->
			<!-- END static -->
			<!-- BEGIN no_topics -->
				<b>{no_topics.L_NO_TOPICS}</b>
			<!-- END no_topics -->
		</span>
	</td>
</tr>
</table>