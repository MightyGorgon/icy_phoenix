{IMG_TBL}<div class="forumline nav-div">
	<p class="nav-header">
	<?php
		$query = $_SERVER['QUERY_STRING'];
		if (!$query)
		{
	?>
		<a href="{U_PORTAL}">{L_HOME}</a>{NAV_SEP}<a href="{U_KB}" class="nav-current">{L_KB}</a>{PATH}
	<?php
		}
		else
		{
	?>
		<a href="{U_PORTAL}">{L_HOME}</a>{NAV_SEP}<a href="{U_KB}" class="nav">{L_KB}</a>{PATH}
	<?php
	}
	?>
	</p>
	<div class="nav-links">
		<div class="nav-links-left">
			{CURRENT_TIME}&nbsp;|&nbsp;{L_CAT_ADD_ARTICLE}
		</div>
		<!-- BEGIN switch_add_article -->
		{L_ADD_ARTICLE}&nbsp;|&nbsp;
		<!-- END switch_add_article -->
		{L_SEARCH}
		<!-- BEGIN switch_print_article -->
		&nbsp;|&nbsp;<a href="{U_PRINT}">{L_PRINT}</a>
		<!-- END switch_print_article -->
	</div>
</div>{IMG_TBR}

<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
<!-- BEGIN switch_quick_stats -->
	<tr valign="top">
		<td width="100%" colspan="3">
			{IMG_THL}{IMG_THC}<span class="forumlink">{switch_quick_stats.L_QUICK_STATS}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
				<!-- BEGIN quick_stats -->
				<tr>
					<td class="row2 row-center" width="100%">
						<span class="gen">
							{switch_quick_stats.quick_stats.Q_TYPE_NAME}&nbsp;{switch_quick_stats.quick_stats.Q_TYPE_AMOUNT}
						</span>
					</td>
				</tr>
				<!-- END quick_stats -->
				<tr>
					<td class="row2 row-center" width="100%">
						<span class="gen">::
							<a href="{U_MOST_POPULAR}" class="nav">{L_MOST_POPULAR}</a> ::
							<a href="{U_TOPRATED}" class="nav">{L_TOPRATED}</a> ::
							<a href="{U_LATEST}" class="nav">{L_LATEST}</a> ::
						</span>
					</td>
				</tr>
			</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
		</td>
	</tr>
	<!-- END switch_quick_stats -->
	<tr valign="top">
		<td colspan="3">