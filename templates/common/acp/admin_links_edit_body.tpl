<h1>{PAGE_TITLE}</h1>
<p>{PAGE_EXPLAIN}</p>

<form name="link_form" action="{PAGE_ACTION}" method="post">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><th colspan="2">{L_LINK_BASIC_SETTING}</th></tr>
<tr>
	<td class="row1" width="40%" nowrap="nowrap"><strong>{L_LINK_TITLE}</strong></td>
	<td class="row2"><input type="text" class="post" size="50" maxlength="50" name="link_title" value="{LINK_TITLE}" /></td>
</tr>
<tr>
	<td class="row1" nowrap="nowrap"><strong>{L_LINK_URL}</strong></td>
	<td class="row2"><input type="text" class="post" size="50" maxlength="100" name="link_url" value="{LINK_URL}"></td>
</tr>
<tr>
	<td class="row1"><strong>{L_LINK_LOGO_SRC}</strong></td>
	<td class="row2"><input type="text" class="post" size="50" maxlength="120" name="link_logo_src" value="{LINK_LOGO_SRC}">&nbsp;[<a href="javascript: void(0)" onclick="var img_src=document.link_form.link_logo_src.value;if(img_src=='') img_src='images/links/no_logo88a.gif';if(img_src.substr(0, 4) != 'http') img_src='../'+img_src;_preview=window.open(img_src, '_preview', 'toolbar=no,width=200,height=100,top=300,left=300');">{L_PREVIEW}</a>]</td>
</tr>
<tr> 
	<td class="row1" nowrap="nowrap"><strong>{L_LINK_CATEGORY}</strong></td>
	<td class="row2"><select name="link_category"><option value="" selected>----------------</option>{LINK_CAT_OPTION}</select></td>
</tr>
<tr>
	<td class="row1" nowrap="nowrap" valign="top"><strong>{L_LINK_DESC}</strong></td>
	<td class="row2" valign="top"><textarea name="link_desc" cols="30" rows="5" class="post" maxsize="120">{LINK_DESC}</textarea>{LINK_LOGO_IMG}</td>
</tr>
<tr><th colspan="2">{L_LINK_ADV_SETTING}</th></tr>
<tr> 
	<td class="row1" nowrap="nowrap"><strong>{L_LINK_ACTIVE}</strong></td>
	<td class="row2"><input type="radio" name="link_active" value="1" {LINK_ACTIVE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="link_active" value="0" {LINK_ACTIVE_NO} /> {L_NO}</td>
</tr>
<tr><td class="cat" colspan="2" align="center"><input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" /></td></tr>
</table>
</form>
<div align="center"><span class="copyright">Links MOD v1.2.1 by <a href="http://www.phpbb2.de" target="_blank">phpBB2.de</a> and OOHOO</span></div>
<br clear="all" />