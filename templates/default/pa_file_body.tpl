<!-- INCLUDE pa_header.tpl -->
{IMG_TBL}<div class="forumline nav-div">
	<p class="nav-header">
		<a href="{U_INDEX}">{L_HOME}</a>{NAV_SEP}<a href="{U_DOWNLOAD_HOME}" class="nav">{DOWNLOAD}</a><!-- BEGIN navlinks -->{NAV_SEP}<a href="{navlinks.U_VIEW_CAT}" class="nav">{navlinks.CAT_NAME}</a><!-- END navlinks -->{NAV_SEP}<a href="#" class="nav-current">{FILE_NAME}</a>
	</p>
	<div class="nav-links">
		<div class="nav-links-left">{CURRENT_TIME}</div>
		<!-- INCLUDE pa_links.tpl -->
	</div>
</div>{IMG_TBR}


{IMG_THL}{IMG_THC}<span class="forumlink">{L_FILE}:</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr><th align="left" colspan="3">{FILE_NAME}</th></tr>
<tr>
	<td class="row2" valign="middle" width="20%"><span class="genmed">{L_DESC}:</span></td>
	<td class="row1" valign="middle" width="80%" colspan="2"><span class="genmed">{FILE_LONGDESC}</span></td>
</tr>
<tr>
	<td class="row2" valign="middle" width="20%"><span class="genmed">{L_SUBMITED_BY}:</span></td>
	<td class="row1" valign="middle" width="80%" colspan="2"><span class="name">{FILE_SUBMITED_BY}</span></td>
</tr>
<!-- IF SHOW_AUTHOR -->
<tr>
	<td class="row2" valign="middle" width="20%"><span class="genmed">{L_AUTHOR}:</span></td>
	<td class="row1" valign="middle" width="80%" colspan="2"><span class="genmed">{FILE_AUTHOR}</span></td>
</tr>
<!-- ENDIF -->
<!-- IF SHOW_VERSION -->
<tr>
	<td class="row2" valign="middle" width="20%"><span class="genmed">{L_VERSION}:</span></td>
	<td class="row1" valign="middle" width="80%" colspan="2"><span class="genmed">{FILE_VERSION}</span></td>
	</tr>
<!-- ENDIF -->
<!-- IF SHOW_SCREENSHOT -->
<tr>
	<td class="row2" valign="middle" width="20%"><span class="genmed">{L_SCREENSHOT}:</span></td>
	<!-- IF SS_AS_LINK -->
	<td class="row1" valign="middle" width="80%" colspan="2"><span class="genmed"><a href="{FILE_SCREENSHOT}" target="_blank">{L_CLICK_HERE}</a></span></td>
	<!-- ELSE -->
	<td class="row1" valign="middle" width="80%" colspan="2"><span class="genmed"><a href="javascript:mpFoto('{FILE_SCREENSHOT}')"><img src="{FILE_SCREENSHOT}" width="100" /></a></span></td>
	<!-- ENDIF -->
	</tr>
<!-- ENDIF -->
<!-- IF SHOW_WEBSITE -->
	<tr>
	<td class="row2" valign="middle" width="20%"><span class="genmed">{L_WEBSITE}:</span></td>
	<td class="row1" valign="middle" width="80%" colspan="2"><span class="genmed"><a href="{FILE_WEBSITE}" target="_blank">{L_CLICK_HERE}</a></span></td>
	</tr>
<!-- ENDIF -->
<tr>
	<td class="row2" valign="middle"><span class="genmed">{L_DATE}:</span></td>
	<td class="row1" valign="middle" colspan="2"><span class="genmed">{TIME}</span></td>
	</tr>
<tr>
	<td class="row2" valign="middle"><span class="genmed">{L_UPDATE_TIME}:</span></td>
	<td class="row1" valign="middle" colspan="2"><span class="genmed">{UPDATE_TIME}</span></td>
	</tr>
	<tr>
	<td class="row2" valign="middle"><span class="genmed">{L_LASTTDL}:</span></td>
	<td class="row1" valign="middle" colspan="2"><span class="genmed">{LAST}</span></td>
	</tr>
	<tr>
	<td class="row2" valign="middle"><span class="genmed">{L_SIZE}:</span></td>
	<td class="row1" valign="middle" colspan="2"><span class="genmed">{FILE_SIZE}</span></td>
	</tr>
	<tr>
	<td class="row2" valign="middle"><span class="genmed">{L_RATING}:</span></td>
	<td class="row2" colspan="2"><span class="postdetails"><img src="images/rates/rate_{RATING}.png" /></td>
	</tr>
	<tr>
	<td class="row2" valign="middle"><span class="genmed">{L_DLS}:</span></td>
	<td class="row1" valign="middle" colspan="2"><span class="genmed">{FILE_DLS}</span></td>
	</tr>
<!-- BEGIN custom_field -->
	<tr>
	<td class="row2" valign="middle"><span class="genmed">{custom_field.CUSTOM_NAME}:</span></td>
	<td class="row1" valign="middle" colspan="2"><span class="genmed">{custom_field.DATA}</span></td>
	</tr>
<!-- END custom_field -->
	<tr>
		<td class= "row1g" align="right" colspan="3" nowrap>
			<!-- IF AUTH_EDIT -->
			<span class="post-buttons-top post-buttons"><a href="{U_EDIT}"><img src="{EDIT_IMG}" alt="{L_EDIT}" /></a></span>
			<!-- ENDIF -->
			<!-- IF AUTH_DELETE -->
			<span class="post-buttons-top post-buttons"><a href="javascript:delete_file('{U_DELETE}')"><img src="{DELETE_IMG}" alt="{L_DELETE}" /></a></span>&nbsp;
			<!-- ENDIF -->
		</td>
	</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<br />
<table width="100%" cellpadding="2" cellspacing="0">
<tr>
<!-- IF AUTH_DOWNLOAD -->
	<td width="33%" align="center"><a href="{U_DOWNLOAD}"><img src="{DOWNLOAD_IMG}" alt="{L_DOWNLOAD}" /></a></td>
<!-- ENDIF -->
<!-- IF AUTH_RATE -->
	<td width="34%" align="center"><a href="{U_RATE}"><img src="{RATE_IMG}" alt="{L_RATE}" /></a></td>
<!-- ENDIF -->
<!-- IF AUTH_EMAIL -->
	<td width="33%" align="center"><a href="{U_EMAIL}"><img src="{EMAIL_IMG}" alt="{L_EMAIL}" /></a></td>
<!-- ENDIF -->
</tr>
</table>
<br />
<!-- IF INCLUDE_COMMENTS -->
	<!-- INCLUDE pa_comment_body.tpl -->
<!-- ENDIF -->
<!-- INCLUDE pa_footer.tpl -->