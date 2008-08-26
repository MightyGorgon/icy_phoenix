	<!-- IF SWITCH_CMS_GLOBAL_BLOCKS -->
	<!-- IF TC_BLOCK --><div style="vertical-align:top;"><!-- BEGIN tailcenter_blocks_row -->{tailcenter_blocks_row.CMS_BLOCK}<!-- END tailcenter_blocks_row --></div><!-- ENDIF -->
	</td>
	<!-- IF TR_BLOCK --><td width="5"><img src="{SPACER}" alt="" width="5" height="10" /></td><td width="{FOOTER_WIDTH}" valign="top"><!-- BEGIN tailright_blocks_row -->{tailright_blocks_row.CMS_BLOCK}<!-- END tailright_blocks_row --></td><!-- ENDIF -->
	</tr>
	</table>
	<div style="vertical-align:top;"><!-- BEGIN tail_blocks_row -->{tail_blocks_row.CMS_BLOCK}<!-- END tail_blocks_row --></div>
	<!-- ENDIF -->

	{CMS_ACP}
	<div style="text-align:center;"><br /><span class="admin-link">{ADMIN_LINK}</span><br /><br /></div>

	</td>
</tr>
<!-- BEGIN switch_footer_table -->
<tr>
	<td width="100%" colspan="3">
		<div style="padding-left:5px; padding-right:5px;">
		{IMG_TBL}<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
			<tr><td class="row-header"><span>{L_STAFF_MESSAGE}</span></td></tr>
			<tr><td><div class="post-text"><br />{switch_footer_table.FOOTER_TEXT}<br /><br /></div></td></tr>
		</table>{IMG_TBR}
		</div>
	</td>
</tr>
<!-- END switch_footer_table -->