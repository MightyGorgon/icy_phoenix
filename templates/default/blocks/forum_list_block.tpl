{IMG_THL}{IMG_THC}<span class="forumlink">{TITLE}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<!-- BEGIN cat_row -->
<tr>
	<th style="cursor: pointer;" align="left" onclick="ShowHide('menu_cat_{cat_row.CAT_ID}','menu_cat_{cat_row.CAT_ID}_h','menu_cat_{cat_row.CAT_ID}');">
		{cat_row.CAT_ICON}<a href="javascript:void(0);" title="{cat_row.CAT_ITEM}" style="vertical-align:top;"><b>{cat_row.CAT_ITEM}</b></a>
	</th>
</tr>
<tr>
	<td class="row5">
		<div id="menu_cat_{cat_row.CAT_ID}_h" style="display: inline; position: relative;padding-top:0px;padding-bottom:0px;">
			<table class="forumline-no2" width="100%" cellspacing="0" cellpadding="2" border="0">
				<!-- BEGIN forum_row -->
				<tr>
					<td align="center" width="8">{cat_row.forum_row.FORUM_ICON}</td>
					<td class="genmed" align="left"><a href="{cat_row.forum_row.FORUM_LINK}">{cat_row.forum_row.FORUM_ITEM}</a></td>
				</tr>
				<!-- END forum_row -->
			</table>
		</div>
		<div id="menu_cat_{cat_row.CAT_ID}" class="js-sh-box">
			<script type="text/javascript">
			<!--
			tmp = 'menu_cat_{cat_row.CAT_ID}';
			if(GetCookie(tmp) == '2')
			{
				ShowHide('menu_cat_{cat_row.CAT_ID}','menu_cat_{cat_row.CAT_ID}_h','menu_cat_{cat_row.CAT_ID}');
			}
			//-->
			</script>
		</div>
	</td>
</tr>
<!-- END cat_row -->
<!-- BEGIN no_forum -->
<tr><td class="row1"><br /><br />{no_forum.NO_FORUM}<br /><br /></td></tr>
<!-- END no_forum -->
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}