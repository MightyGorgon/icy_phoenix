<!-- INCLUDE overall_inc_footer.tpl -->
<tr>
	<td colspan="3">
	<div id="bottom_logo_ext">
	{IMG_TBL}
	<div id="bottom_logo">
		<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td nowrap="nowrap" class="min250" align="left"><span class="copyright">&nbsp;Powered by <a href="http://www.icyphoenix.com/" target="_blank">Icy Phoenix</a> based on <a href="http://www.phpbb.com/" target="_blank">phpBB</a>{TRANSLATION_INFO}</span></td>
				<td nowrap="nowrap" align="center">
					<div style="text-align: center;">
						<span class="generation"><b>{LOFI}</b></span>
						<!-- IF S_GENERATION_TIME -->
						<span class="generation">{PAGE_GEN_TIME} <b>{GENERATION_TIME}s</b> (PHP: {PHP_PART}% SQL: {SQL_PART}%){MEMORY_USAGE}</span><br />
						<span class="generation">{SQL_QUERIES}: {NUMBER_QUERIES} - {DEBUG_TEXT} - {GZIP_TEXT}</span>
						<!-- ENDIF -->
					</div>
				</td>
				<td nowrap="nowrap" class="min250" align="right">{COPYRIGHT_LINK}</td>
			</tr>
		</table>
	</div>
	{IMG_TBR}
	</div>
	</td>
</tr>
</table>
{PAGE_END}

{BOTTOM_HTML_BLOCK}
<span><a name="bottom"></a></span>
<!-- IF not S_BOT -->{RUN_CRON_TASK}<!-- ENDIF -->

</div>

{GOOGLE_ANALYTICS}
</body>
</html>