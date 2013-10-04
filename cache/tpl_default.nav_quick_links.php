<?php

// eXtreme Styles mod cache. Generated on Fri, 04 Oct 2013 15:39:59 +0000 (time = 1380901199)

if (!defined('IN_ICYPHOENIX')) exit;

?><?php if ($this->vars['SWITCH_CMS_SHOW_HIDE']) {  ?>
<script type="text/javascript">
<!--
tmp = 'quick_links';
if(GetCookie(tmp) == '2')
{
	ShowHide('quick_links', 'quick_links2', 'quick_links');
	setWidth('var_width', 16);
}
//-->
</script>
<?php } ?>

<form name="ChangeThemeLang" method="post" action="<?php echo isset($this->vars['REQUEST_URI']) ? $this->vars['REQUEST_URI'] : $this->lang('REQUEST_URI'); ?>">
<?php echo isset($this->vars['IMG_THL']) ? $this->vars['IMG_THL'] : $this->lang('IMG_THL'); ?><?php echo isset($this->vars['IMG_THC']) ? $this->vars['IMG_THC'] : $this->lang('IMG_THC'); ?>
<?php if ($this->vars['SWITCH_CMS_SHOW_HIDE']) {  ?>
<img class="max-min-right" style="<?php echo isset($this->vars['SHOW_HIDE_PADDING']) ? $this->vars['SHOW_HIDE_PADDING'] : $this->lang('SHOW_HIDE_PADDING'); ?>" src="<?php echo isset($this->vars['IMG_MINIMISE']) ? $this->vars['IMG_MINIMISE'] : $this->lang('IMG_MINIMISE'); ?>" onclick="ShowHide('quick_links', 'quick_links2', 'quick_links'); setWidth('var_width', 16); setWidth('full_width', '100%'); setWidth('full_width_cpl', '100%');" alt="<?php echo isset($this->vars['L_HIDE']) ? $this->vars['L_HIDE'] : $this->lang('L_HIDE'); ?>" />
<?php } ?>
<span class="forumlink"><?php echo isset($this->vars['L_QUICK_LINKS']) ? $this->vars['L_QUICK_LINKS'] : $this->lang('L_QUICK_LINKS'); ?></span><?php echo isset($this->vars['IMG_THR']) ? $this->vars['IMG_THR'] : $this->lang('IMG_THR'); ?><table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">

<?php if ($this->vars['NAV_MENU_ADS_TOP']) {  ?>
<tr>
	<th style="cursor: pointer;" align="left">
		<img src="<?php echo isset($this->vars['IMG_NAV_MENU_SPONSOR']) ? $this->vars['IMG_NAV_MENU_SPONSOR'] : $this->lang('IMG_NAV_MENU_SPONSOR'); ?>" alt="<?php echo isset($this->vars['L_SPONSORS_LINKS']) ? $this->vars['L_SPONSORS_LINKS'] : $this->lang('L_SPONSORS_LINKS'); ?>" title="<?php echo isset($this->vars['L_SPONSORS_LINKS']) ? $this->vars['L_SPONSORS_LINKS'] : $this->lang('L_SPONSORS_LINKS'); ?>" />&nbsp;
		<a href="#" onclick="return false;" title="<?php echo isset($this->vars['L_SPONSORS_LINKS']) ? $this->vars['L_SPONSORS_LINKS'] : $this->lang('L_SPONSORS_LINKS'); ?>" class="nav-menu-link"><b><?php echo isset($this->vars['L_SPONSORS_LINKS']) ? $this->vars['L_SPONSORS_LINKS'] : $this->lang('L_SPONSORS_LINKS'); ?></b></a>
	</th>
</tr>
<tr><td class="row1"><?php echo isset($this->vars['NAV_MENU_ADS_TOP']) ? $this->vars['NAV_MENU_ADS_TOP'] : $this->lang('NAV_MENU_ADS_TOP'); ?></td></tr>
<?php } ?>

<tr>
	<th style="cursor: pointer;" align="left" onclick="ShowHide('main_links', 'main_links2', 'main_links');">
		<img src="<?php echo isset($this->vars['IMG_NAV_MENU_APPLICATION']) ? $this->vars['IMG_NAV_MENU_APPLICATION'] : $this->lang('IMG_NAV_MENU_APPLICATION'); ?>" alt="<?php echo isset($this->vars['L_MAIN_LINKS']) ? $this->vars['L_MAIN_LINKS'] : $this->lang('L_MAIN_LINKS'); ?>" title="<?php echo isset($this->vars['L_MAIN_LINKS']) ? $this->vars['L_MAIN_LINKS'] : $this->lang('L_MAIN_LINKS'); ?>" />&nbsp;
		<a href="#" onclick="return false;" title="<?php echo isset($this->vars['L_MAIN_LINKS']) ? $this->vars['L_MAIN_LINKS'] : $this->lang('L_MAIN_LINKS'); ?>" class="nav-menu-link"><b><?php echo isset($this->vars['L_MAIN_LINKS']) ? $this->vars['L_MAIN_LINKS'] : $this->lang('L_MAIN_LINKS'); ?></b></a>
	</th>
</tr>
<tr>
	<td class="row5">
		<div id="main_links2" class="nav-menu">
			<table class="forumline-no2" width="100%" cellspacing="0" cellpadding="2" border="0">
				<?php if ($this->vars['S_ADMIN']) {  ?>
				<tr>
					<td align="left" width="8"><?php echo isset($this->vars['IMG_NAV_MENU_SEP']) ? $this->vars['IMG_NAV_MENU_SEP'] : $this->lang('IMG_NAV_MENU_SEP'); ?></td>
					<td class="genmed" align="left"><a href="<?php echo isset($this->vars['U_CMS_ACP']) ? $this->vars['U_CMS_ACP'] : $this->lang('U_CMS_ACP'); ?>"><?php echo isset($this->vars['L_LINK_ACP']) ? $this->vars['L_LINK_ACP'] : $this->lang('L_LINK_ACP'); ?></a></td>
				</tr>
				<tr>
					<td align="left" width="8"><?php echo isset($this->vars['IMG_NAV_MENU_SEP']) ? $this->vars['IMG_NAV_MENU_SEP'] : $this->lang('IMG_NAV_MENU_SEP'); ?></td>
					<td class="genmed" align="left"><a href="<?php echo isset($this->vars['U_CMS']) ? $this->vars['U_CMS'] : $this->lang('U_CMS'); ?>"><?php echo isset($this->vars['L_LINK_CMS']) ? $this->vars['L_LINK_CMS'] : $this->lang('L_LINK_CMS'); ?></a></td>
				</tr>
				<?php } ?>
				<tr>
					<td align="left" width="8"><?php echo isset($this->vars['IMG_NAV_MENU_SEP']) ? $this->vars['IMG_NAV_MENU_SEP'] : $this->lang('IMG_NAV_MENU_SEP'); ?></td>
					<td class="genmed" align="left"><a href="<?php echo isset($this->vars['U_PORTAL']) ? $this->vars['U_PORTAL'] : $this->lang('U_PORTAL'); ?>"><?php echo isset($this->vars['L_LINK_HOME']) ? $this->vars['L_LINK_HOME'] : $this->lang('L_LINK_HOME'); ?></a></td>
				</tr>
				<?php if ($this->vars['S_LOGGED_IN']) {  ?>
				<tr>
					<td width="8" align="left"><?php echo isset($this->vars['IMG_NAV_MENU_SEP']) ? $this->vars['IMG_NAV_MENU_SEP'] : $this->lang('IMG_NAV_MENU_SEP'); ?></td>
					<td class="genmed" align="left"><a href="<?php echo isset($this->vars['U_PROFILE']) ? $this->vars['U_PROFILE'] : $this->lang('U_PROFILE'); ?>"><?php echo isset($this->vars['L_LINK_PROFILE']) ? $this->vars['L_LINK_PROFILE'] : $this->lang('L_LINK_PROFILE'); ?></a></td>
				</tr>
				<?php } ?>
				<tr>
					<td align="left" width="8"><?php echo isset($this->vars['IMG_NAV_MENU_SEP']) ? $this->vars['IMG_NAV_MENU_SEP'] : $this->lang('IMG_NAV_MENU_SEP'); ?></td>
					<td class="genmed" align="left"><a href="<?php echo isset($this->vars['U_INDEX']) ? $this->vars['U_INDEX'] : $this->lang('U_INDEX'); ?>"><?php echo isset($this->vars['L_LINK_FORUM']) ? $this->vars['L_LINK_FORUM'] : $this->lang('L_LINK_FORUM'); ?></a></td>
				</tr>
				<tr>
					<td align="left" width="8"><?php echo isset($this->vars['IMG_NAV_MENU_SEP']) ? $this->vars['IMG_NAV_MENU_SEP'] : $this->lang('IMG_NAV_MENU_SEP'); ?></td>
					<td class="genmed" align="left"><a href="<?php echo isset($this->vars['U_BOARDRULES']) ? $this->vars['U_BOARDRULES'] : $this->lang('U_BOARDRULES'); ?>"><?php echo isset($this->vars['L_LINK_BOARDRULES']) ? $this->vars['L_LINK_BOARDRULES'] : $this->lang('L_LINK_BOARDRULES'); ?></a></td>
				</tr>
				<tr>
					<td align="left" width="8"><?php echo isset($this->vars['IMG_NAV_MENU_SEP']) ? $this->vars['IMG_NAV_MENU_SEP'] : $this->lang('IMG_NAV_MENU_SEP'); ?></td>
					<td class="genmed" align="left"><a href="<?php echo isset($this->vars['U_FAQ']) ? $this->vars['U_FAQ'] : $this->lang('U_FAQ'); ?>"><?php echo isset($this->vars['L_LINK_FAQ']) ? $this->vars['L_LINK_FAQ'] : $this->lang('L_LINK_FAQ'); ?></a></td>
				</tr>
				<tr>
					<td align="left" width="8"><?php echo isset($this->vars['IMG_NAV_MENU_SEP']) ? $this->vars['IMG_NAV_MENU_SEP'] : $this->lang('IMG_NAV_MENU_SEP'); ?></td>
					<td class="genmed" align="left"><a href="<?php echo isset($this->vars['U_SEARCH']) ? $this->vars['U_SEARCH'] : $this->lang('U_SEARCH'); ?>"><?php echo isset($this->vars['L_LINK_SEARCH']) ? $this->vars['L_LINK_SEARCH'] : $this->lang('L_LINK_SEARCH'); ?></a></td>
				</tr>
				<tr>
					<td align="left" width="8"><?php echo isset($this->vars['IMG_NAV_MENU_SEP']) ? $this->vars['IMG_NAV_MENU_SEP'] : $this->lang('IMG_NAV_MENU_SEP'); ?></td>
					<td class="genmed" align="left"><a href="<?php echo isset($this->vars['U_SITEMAP']) ? $this->vars['U_SITEMAP'] : $this->lang('U_SITEMAP'); ?>"><?php echo isset($this->vars['L_LINK_SITEMAP']) ? $this->vars['L_LINK_SITEMAP'] : $this->lang('L_LINK_SITEMAP'); ?></a></td>
				</tr>
				<tr>
					<td width="8" align="left"><?php echo isset($this->vars['IMG_NAV_MENU_SEP']) ? $this->vars['IMG_NAV_MENU_SEP'] : $this->lang('IMG_NAV_MENU_SEP'); ?></td>
					<td class="genmed" align="left"><a href="<?php echo isset($this->vars['U_ALBUM']) ? $this->vars['U_ALBUM'] : $this->lang('U_ALBUM'); ?>"><?php echo isset($this->vars['L_LINK_ALBUM']) ? $this->vars['L_LINK_ALBUM'] : $this->lang('L_LINK_ALBUM'); ?></a></td>
				</tr>
				<tr>
					<td align="left" width="8"><?php echo isset($this->vars['IMG_NAV_MENU_SEP']) ? $this->vars['IMG_NAV_MENU_SEP'] : $this->lang('IMG_NAV_MENU_SEP'); ?></td>
					<td class="genmed" align="left"><a href="<?php echo isset($this->vars['U_CALENDAR']) ? $this->vars['U_CALENDAR'] : $this->lang('U_CALENDAR'); ?>"><?php echo isset($this->vars['L_LINK_CALENDAR']) ? $this->vars['L_LINK_CALENDAR'] : $this->lang('L_LINK_CALENDAR'); ?></a></td>
				</tr>
				<tr>
					<td width="8" align="left"><?php echo isset($this->vars['IMG_NAV_MENU_SEP']) ? $this->vars['IMG_NAV_MENU_SEP'] : $this->lang('IMG_NAV_MENU_SEP'); ?></td>
					<td class="genmed" align="left"><a href="<?php echo isset($this->vars['U_DOWNLOADS_NAV']) ? $this->vars['U_DOWNLOADS_NAV'] : $this->lang('U_DOWNLOADS_NAV'); ?>"><?php echo isset($this->vars['L_LINK_DOWNLOADS']) ? $this->vars['L_LINK_DOWNLOADS'] : $this->lang('L_LINK_DOWNLOADS'); ?></a></td>
				</tr>
				<?php if ($this->vars['S_LOGGED_IN']) {  ?>
				<tr>
					<td align="left" width="8"><?php echo isset($this->vars['IMG_NAV_MENU_SEP']) ? $this->vars['IMG_NAV_MENU_SEP'] : $this->lang('IMG_NAV_MENU_SEP'); ?></td>
					<td class="genmed" align="left"><a href="<?php echo isset($this->vars['U_BOOKMARKS']) ? $this->vars['U_BOOKMARKS'] : $this->lang('U_BOOKMARKS'); ?>"><?php echo isset($this->vars['L_LINK_BOOKMARKS']) ? $this->vars['L_LINK_BOOKMARKS'] : $this->lang('L_LINK_BOOKMARKS'); ?></a></td>
				</tr>
				<tr>
					<td align="left" width="8"><?php echo isset($this->vars['IMG_NAV_MENU_SEP']) ? $this->vars['IMG_NAV_MENU_SEP'] : $this->lang('IMG_NAV_MENU_SEP'); ?></td>
					<td class="genmed" align="left"><a href="<?php echo isset($this->vars['U_DRAFTS']) ? $this->vars['U_DRAFTS'] : $this->lang('U_DRAFTS'); ?>"><?php echo isset($this->vars['L_LINK_DRAFTS']) ? $this->vars['L_LINK_DRAFTS'] : $this->lang('L_LINK_DRAFTS'); ?></a></td>
				</tr>
				<tr>
					<td align="left" width="8"><?php echo isset($this->vars['IMG_NAV_MENU_SEP']) ? $this->vars['IMG_NAV_MENU_SEP'] : $this->lang('IMG_NAV_MENU_SEP'); ?></td>
					<td class="genmed" align="left"><a href="<?php echo isset($this->vars['U_UPLOADED_IMAGES']) ? $this->vars['U_UPLOADED_IMAGES'] : $this->lang('U_UPLOADED_IMAGES'); ?>"><?php echo isset($this->vars['L_LINK_UPLOADED_IMAGES']) ? $this->vars['L_LINK_UPLOADED_IMAGES'] : $this->lang('L_LINK_UPLOADED_IMAGES'); ?></a></td>
				</tr>
				<?php } ?>
				<tr>
					<td align="left" width="8"><?php echo isset($this->vars['IMG_NAV_MENU_SEP']) ? $this->vars['IMG_NAV_MENU_SEP'] : $this->lang('IMG_NAV_MENU_SEP'); ?></td>
					<td class="genmed" align="left"><a href="<?php echo isset($this->vars['U_AJAX_SHOUTBOX']) ? $this->vars['U_AJAX_SHOUTBOX'] : $this->lang('U_AJAX_SHOUTBOX'); ?>"><?php echo isset($this->vars['L_LINK_AJAX_SHOUTBOX']) ? $this->vars['L_LINK_AJAX_SHOUTBOX'] : $this->lang('L_LINK_AJAX_SHOUTBOX'); ?></a></td>
				</tr>
				<tr>
					<td width="8" align="left"><?php echo isset($this->vars['IMG_NAV_MENU_SEP']) ? $this->vars['IMG_NAV_MENU_SEP'] : $this->lang('IMG_NAV_MENU_SEP'); ?></td>
					<td class="genmed" align="left"><a href="<?php echo isset($this->vars['U_LINKS']) ? $this->vars['U_LINKS'] : $this->lang('U_LINKS'); ?>"><?php echo isset($this->vars['L_LINK_LINKS']) ? $this->vars['L_LINK_LINKS'] : $this->lang('L_LINK_LINKS'); ?></a></td>
				</tr>
				<tr>
					<td align="left" width="8"><?php echo isset($this->vars['IMG_NAV_MENU_SEP']) ? $this->vars['IMG_NAV_MENU_SEP'] : $this->lang('IMG_NAV_MENU_SEP'); ?></td>
					<td class="genmed" align="left"><a href="<?php echo isset($this->vars['U_CONTACT_US']) ? $this->vars['U_CONTACT_US'] : $this->lang('U_CONTACT_US'); ?>"><?php echo isset($this->vars['L_LINK_CONTACT_US']) ? $this->vars['L_LINK_CONTACT_US'] : $this->lang('L_LINK_CONTACT_US'); ?></a></td>
				</tr>
				<?php if ($this->vars['S_LOGGED_IN']) {  ?>
				<tr>
					<td width="8" align="left"><?php echo isset($this->vars['IMG_NAV_MENU_SEP']) ? $this->vars['IMG_NAV_MENU_SEP'] : $this->lang('IMG_NAV_MENU_SEP'); ?></td>
					<td class="genmed" align="left"><a href="<?php echo isset($this->vars['U_SUDOKU']) ? $this->vars['U_SUDOKU'] : $this->lang('U_SUDOKU'); ?>"><?php echo isset($this->vars['L_LINK_SUDOKU']) ? $this->vars['L_LINK_SUDOKU'] : $this->lang('L_LINK_SUDOKU'); ?></a></td>
				</tr>
				<?php } ?>
			</table>
		</div>
		<div id="main_links" class="js-sh-box">
			<script type="text/javascript">
			<!--
			tmp = 'main_links';
			if(GetCookie(tmp) == '2')
			{
				ShowHide('main_links', 'main_links2', 'main_links');
			}
			//-->
			</script>
		</div>
	</td>
</tr>
<tr>
	<th style="cursor: pointer;" align="left" onclick="ShowHide('news2_links', 'news2_links2', 'news2_links');">
		<img src="<?php echo isset($this->vars['IMG_NAV_MENU_NEWSPAPER']) ? $this->vars['IMG_NAV_MENU_NEWSPAPER'] : $this->lang('IMG_NAV_MENU_NEWSPAPER'); ?>" alt="<?php echo isset($this->vars['L_NEWS_LINKS']) ? $this->vars['L_NEWS_LINKS'] : $this->lang('L_NEWS_LINKS'); ?>" title="<?php echo isset($this->vars['L_NEWS_LINKS']) ? $this->vars['L_NEWS_LINKS'] : $this->lang('L_NEWS_LINKS'); ?>" />&nbsp;
		<a href="#" onclick="return false;" title="<?php echo isset($this->vars['L_NEWS_LINKS']) ? $this->vars['L_NEWS_LINKS'] : $this->lang('L_NEWS_LINKS'); ?>" class="nav-menu-link"><b><?php echo isset($this->vars['L_NEWS_LINKS']) ? $this->vars['L_NEWS_LINKS'] : $this->lang('L_NEWS_LINKS'); ?></b></a>
	</th>
</tr>
<tr>
	<td class="row5">
		<div id="news2_links2" class="nav-menu">
			<table class="forumline-no2" width="100%" cellspacing="0" cellpadding="2" border="0">
				<tr>
					<td width="8" align="left"><?php echo isset($this->vars['IMG_NAV_MENU_SEP']) ? $this->vars['IMG_NAV_MENU_SEP'] : $this->lang('IMG_NAV_MENU_SEP'); ?></td>
					<td class="genmed" align="left"><a href="<?php echo isset($this->vars['U_PORTAL_NEWS_CAT']) ? $this->vars['U_PORTAL_NEWS_CAT'] : $this->lang('U_PORTAL_NEWS_CAT'); ?>"><?php echo isset($this->vars['L_LINK_NEWS_CAT']) ? $this->vars['L_LINK_NEWS_CAT'] : $this->lang('L_LINK_NEWS_CAT'); ?></a></td>
				</tr>
				<tr>
					<td width="8" align="left"><?php echo isset($this->vars['IMG_NAV_MENU_SEP']) ? $this->vars['IMG_NAV_MENU_SEP'] : $this->lang('IMG_NAV_MENU_SEP'); ?></td>
					<td class="genmed" align="left"><a href="<?php echo isset($this->vars['U_PORTAL_NEWS_ARC']) ? $this->vars['U_PORTAL_NEWS_ARC'] : $this->lang('U_PORTAL_NEWS_ARC'); ?>"><?php echo isset($this->vars['L_LINK_NEWS_ARC']) ? $this->vars['L_LINK_NEWS_ARC'] : $this->lang('L_LINK_NEWS_ARC'); ?></a></td>
				</tr>
				<?php if ($this->vars['S_LOGGED_IN']) {  ?>
				<tr>
					<td align="left" width="8"><?php echo isset($this->vars['IMG_NAV_MENU_SEP']) ? $this->vars['IMG_NAV_MENU_SEP'] : $this->lang('IMG_NAV_MENU_SEP'); ?></td>
					<td class="genmed" align="left"><a href="<?php echo isset($this->vars['U_SEARCH_NEW']) ? $this->vars['U_SEARCH_NEW'] : $this->lang('U_SEARCH_NEW'); ?>"><?php echo isset($this->vars['L_LINK_NEW_MESSAGES']) ? $this->vars['L_LINK_NEW_MESSAGES'] : $this->lang('L_LINK_NEW_MESSAGES'); ?></a></td>
				</tr>
				<?php } ?>
			</table>
		</div>
		<div id="news2_links" class="js-sh-box">
			<script type="text/javascript">
			<!--
			tmp = 'news2_links';
			if(GetCookie(tmp) == '2')
			{
				ShowHide('news2_links', 'news2_links2', 'news2_links');
			}
			//-->
			</script>
		</div>
	</td>
</tr>
<tr>
	<th style="cursor: pointer;" align="left" onclick="ShowHide('info_links', 'info_links2', 'info_links');">
		<img src="<?php echo isset($this->vars['IMG_NAV_MENU_INFORMATION']) ? $this->vars['IMG_NAV_MENU_INFORMATION'] : $this->lang('IMG_NAV_MENU_INFORMATION'); ?>" alt="<?php echo isset($this->vars['L_INFO_LINKS']) ? $this->vars['L_INFO_LINKS'] : $this->lang('L_INFO_LINKS'); ?>" title="<?php echo isset($this->vars['L_INFO_LINKS']) ? $this->vars['L_INFO_LINKS'] : $this->lang('L_INFO_LINKS'); ?>" />&nbsp;
		<a href="#" onclick="return false;" title="<?php echo isset($this->vars['L_INFO_LINKS']) ? $this->vars['L_INFO_LINKS'] : $this->lang('L_INFO_LINKS'); ?>" class="nav-menu-link"><b><?php echo isset($this->vars['L_INFO_LINKS']) ? $this->vars['L_INFO_LINKS'] : $this->lang('L_INFO_LINKS'); ?></b></a>
	</th>
</tr>
<tr>
	<td class="row5">
		<div id="info_links2" class="nav-menu">
			<table class="forumline-no2" width="100%" cellspacing="0" cellpadding="2" border="0">
				<tr>
					<td width="8" align="left"><?php echo isset($this->vars['IMG_NAV_MENU_SEP']) ? $this->vars['IMG_NAV_MENU_SEP'] : $this->lang('IMG_NAV_MENU_SEP'); ?></td>
					<td class="genmed" align="left"><a href="<?php echo isset($this->vars['U_CREDITS']) ? $this->vars['U_CREDITS'] : $this->lang('U_CREDITS'); ?>"><?php echo isset($this->vars['L_LINK_CREDITS']) ? $this->vars['L_LINK_CREDITS'] : $this->lang('L_LINK_CREDITS'); ?></a></td>
				</tr>
				<tr>
					<td width="8" align="left"><?php echo isset($this->vars['IMG_NAV_MENU_SEP']) ? $this->vars['IMG_NAV_MENU_SEP'] : $this->lang('IMG_NAV_MENU_SEP'); ?></td>
					<td class="genmed" align="left"><a href="<?php echo isset($this->vars['U_REFERERS']) ? $this->vars['U_REFERERS'] : $this->lang('U_REFERERS'); ?>"><?php echo isset($this->vars['L_LINK_REFERERS']) ? $this->vars['L_LINK_REFERERS'] : $this->lang('L_LINK_REFERERS'); ?></a></td>
				</tr>
				<tr>
					<td width="8" align="left"><?php echo isset($this->vars['IMG_NAV_MENU_SEP']) ? $this->vars['IMG_NAV_MENU_SEP'] : $this->lang('IMG_NAV_MENU_SEP'); ?></td>
					<td class="genmed" align="left"><a href="<?php echo isset($this->vars['U_VIEWONLINE']) ? $this->vars['U_VIEWONLINE'] : $this->lang('U_VIEWONLINE'); ?>"><?php echo isset($this->vars['L_LINK_VIEWONLINE']) ? $this->vars['L_LINK_VIEWONLINE'] : $this->lang('L_LINK_VIEWONLINE'); ?></a></td>
				</tr>
				<tr>
					<td width="8" align="left"><?php echo isset($this->vars['IMG_NAV_MENU_SEP']) ? $this->vars['IMG_NAV_MENU_SEP'] : $this->lang('IMG_NAV_MENU_SEP'); ?></td>
					<td class="genmed" align="left"><a href="<?php echo isset($this->vars['U_STATISTICS']) ? $this->vars['U_STATISTICS'] : $this->lang('U_STATISTICS'); ?>"><?php echo isset($this->vars['L_LINK_STATISTICS']) ? $this->vars['L_LINK_STATISTICS'] : $this->lang('L_LINK_STATISTICS'); ?></a></td>
				</tr>
				<tr>
					<td width="8" align="left"><?php echo isset($this->vars['IMG_NAV_MENU_SEP']) ? $this->vars['IMG_NAV_MENU_SEP'] : $this->lang('IMG_NAV_MENU_SEP'); ?></td>
					<td class="genmed" align="left"><a href="<?php echo isset($this->vars['U_DELETE_COOKIES']) ? $this->vars['U_DELETE_COOKIES'] : $this->lang('U_DELETE_COOKIES'); ?>"><?php echo isset($this->vars['L_LINK_DELETE_COOKIES']) ? $this->vars['L_LINK_DELETE_COOKIES'] : $this->lang('L_LINK_DELETE_COOKIES'); ?></a></td>
				</tr>
			</table>
		</div>
		<div id="info_links" class="js-sh-box">
			<script type="text/javascript">
			<!--
			tmp = 'info_links';
			if(GetCookie(tmp) == '2')
			{
				ShowHide('info_links', 'info_links2', 'info_links');
			}
			//-->
			</script>
		</div>
	</td>
</tr>
<tr>
	<th style="cursor: pointer;" align="left" onclick="ShowHide('users_links', 'users_links2', 'users_links');">
		<img src="<?php echo isset($this->vars['IMG_NAV_MENU_GROUP']) ? $this->vars['IMG_NAV_MENU_GROUP'] : $this->lang('IMG_NAV_MENU_GROUP'); ?>" alt="<?php echo isset($this->vars['L_USERS_LINKS']) ? $this->vars['L_USERS_LINKS'] : $this->lang('L_USERS_LINKS'); ?>" title="<?php echo isset($this->vars['L_USERS_LINKS']) ? $this->vars['L_USERS_LINKS'] : $this->lang('L_USERS_LINKS'); ?>" />&nbsp;
		<a href="#" onclick="return false;" title="<?php echo isset($this->vars['L_USERS_LINKS']) ? $this->vars['L_USERS_LINKS'] : $this->lang('L_USERS_LINKS'); ?>" class="nav-menu-link"><b><?php echo isset($this->vars['L_USERS_LINKS']) ? $this->vars['L_USERS_LINKS'] : $this->lang('L_USERS_LINKS'); ?></b></a>
	</th>
</tr>
<tr>
	<td class="row5">
		<div id="users_links2" class="nav-menu">
			<table class="forumline-no2" width="100%" cellspacing="0" cellpadding="2" border="0">
				<tr>
					<td width="8" align="left"><?php echo isset($this->vars['IMG_NAV_MENU_SEP']) ? $this->vars['IMG_NAV_MENU_SEP'] : $this->lang('IMG_NAV_MENU_SEP'); ?></td>
					<td class="genmed" align="left"><a href="<?php echo isset($this->vars['U_MEMBERLIST']) ? $this->vars['U_MEMBERLIST'] : $this->lang('U_MEMBERLIST'); ?>"><?php echo isset($this->vars['L_LINK_MEMBERLIST']) ? $this->vars['L_LINK_MEMBERLIST'] : $this->lang('L_LINK_MEMBERLIST'); ?></a></td>
				</tr>
				<tr>
					<td width="8" align="left"><?php echo isset($this->vars['IMG_NAV_MENU_SEP']) ? $this->vars['IMG_NAV_MENU_SEP'] : $this->lang('IMG_NAV_MENU_SEP'); ?></td>
					<td class="genmed" align="left"><a href="<?php echo isset($this->vars['U_GROUP_CP']) ? $this->vars['U_GROUP_CP'] : $this->lang('U_GROUP_CP'); ?>"><?php echo isset($this->vars['L_LINK_USERGROUPS']) ? $this->vars['L_LINK_USERGROUPS'] : $this->lang('L_LINK_USERGROUPS'); ?></a></td>
				</tr>
				<tr>
					<td width="8" align="left"><?php echo isset($this->vars['IMG_NAV_MENU_SEP']) ? $this->vars['IMG_NAV_MENU_SEP'] : $this->lang('IMG_NAV_MENU_SEP'); ?></td>
					<td class="genmed" align="left"><a href="<?php echo isset($this->vars['U_RANKS']) ? $this->vars['U_RANKS'] : $this->lang('U_RANKS'); ?>"><?php echo isset($this->vars['L_LINK_RANKS']) ? $this->vars['L_LINK_RANKS'] : $this->lang('L_LINK_RANKS'); ?></a></td>
				</tr>
				<tr>
					<td width="8" align="left"><?php echo isset($this->vars['IMG_NAV_MENU_SEP']) ? $this->vars['IMG_NAV_MENU_SEP'] : $this->lang('IMG_NAV_MENU_SEP'); ?></td>
					<td class="genmed" align="left"><a href="<?php echo isset($this->vars['U_STAFF']) ? $this->vars['U_STAFF'] : $this->lang('U_STAFF'); ?>"><?php echo isset($this->vars['L_LINK_STAFF']) ? $this->vars['L_LINK_STAFF'] : $this->lang('L_LINK_STAFF'); ?></a></td>
				</tr>
			</table>
		</div>
		<div id="users_links" class="js-sh-box">
			<script type="text/javascript">
			<!--
			tmp = 'users_links';
			if(GetCookie(tmp) == '2')
			{
				ShowHide('users_links', 'users_links2', 'users_links');
			}
			//-->
			</script>
		</div>
	</td>
</tr>
<?php if ($this->vars['S_STYLES_SELECT']) {  ?>
<tr>
	<th style="cursor: pointer;" align="left" onclick="ShowHide('style_select', 'style_select2', 'style_select');">
		<img src="<?php echo isset($this->vars['IMG_NAV_MENU_PALETTE']) ? $this->vars['IMG_NAV_MENU_PALETTE'] : $this->lang('IMG_NAV_MENU_PALETTE'); ?>" alt="<?php echo isset($this->vars['L_SELECT_STYLE']) ? $this->vars['L_SELECT_STYLE'] : $this->lang('L_SELECT_STYLE'); ?>" title="<?php echo isset($this->vars['L_SELECT_STYLE']) ? $this->vars['L_SELECT_STYLE'] : $this->lang('L_SELECT_STYLE'); ?>" />&nbsp;
		<a href="#" onclick="return false;" title="<?php echo isset($this->vars['L_SELECT_STYLE']) ? $this->vars['L_SELECT_STYLE'] : $this->lang('L_SELECT_STYLE'); ?>" class="nav-menu-link"><b><?php echo isset($this->vars['L_SELECT_STYLE']) ? $this->vars['L_SELECT_STYLE'] : $this->lang('L_SELECT_STYLE'); ?></b></a>
	</th>
</tr>
<tr>
	<td class="row5">
		<div id="style_select2" class="nav-menu"><table class="forumline-no2" width="100%" cellspacing="0" cellpadding="2" border="0"><tr><td class="genmed" align="center"><?php echo isset($this->vars['STYLE_SELECT_H']) ? $this->vars['STYLE_SELECT_H'] : $this->lang('STYLE_SELECT_H'); ?></td></tr></table></div>
		<div id="style_select" class="js-sh-box">
			<script type="text/javascript">
			<!--
			tmp = 'style_select';
			if(GetCookie(tmp) == '2')
			{
				ShowHide('style_select', 'style_select2', 'style_select');
			}
			//-->
			</script>
		</div>
	</td>
</tr>
<?php } ?>
<?php if ($this->vars['S_LANGS_SELECT']) {  ?>
<tr>
	<th style="cursor: pointer;" align="left" onclick="ShowHide('lang_select', 'lang_select2', 'lang_select');">
		<img src="<?php echo isset($this->vars['IMG_NAV_MENU_WORLD']) ? $this->vars['IMG_NAV_MENU_WORLD'] : $this->lang('IMG_NAV_MENU_WORLD'); ?>" alt="<?php echo isset($this->vars['L_SELECT_LANG']) ? $this->vars['L_SELECT_LANG'] : $this->lang('L_SELECT_LANG'); ?>" title="<?php echo isset($this->vars['L_SELECT_LANG']) ? $this->vars['L_SELECT_LANG'] : $this->lang('L_SELECT_LANG'); ?>" />&nbsp;
		<a href="#" onclick="return false;" title="<?php echo isset($this->vars['L_SELECT_LANG']) ? $this->vars['L_SELECT_LANG'] : $this->lang('L_SELECT_LANG'); ?>" class="nav-menu-link"><b><?php echo isset($this->vars['L_SELECT_LANG']) ? $this->vars['L_SELECT_LANG'] : $this->lang('L_SELECT_LANG'); ?></b></a>
	</th>
</tr>
<tr>
	<td class="row5">
		<div id="lang_select2" class="nav-menu">
			<table class="forumline-no2" width="100%" cellspacing="0" cellpadding="2" border="0">
				<?php

$lang_select_count = ( isset($this->_tpldata['lang_select.']) ) ? sizeof($this->_tpldata['lang_select.']) : 0;
for ($lang_select_i = 0; $lang_select_i < $lang_select_count; $lang_select_i++)
{
 $lang_select_item = &$this->_tpldata['lang_select.'][$lang_select_i];
 $lang_select_item['S_ROW_COUNT'] = $lang_select_i;
 $lang_select_item['S_NUM_ROWS'] = $lang_select_count;

?>
				<tr>
					<td width="8" align="left"><img src="<?php echo isset($lang_select_item['LANG_FLAG']) ? $lang_select_item['LANG_FLAG'] : ''; ?>" alt="<?php echo isset($lang_select_item['LANG_NAME']) ? $lang_select_item['LANG_NAME'] : ''; ?>" /></td>
					<td class="genmed" align="left"><a href="<?php echo isset($lang_select_item['U_LANG_CHANGE']) ? $lang_select_item['U_LANG_CHANGE'] : ''; ?>"><?php echo isset($lang_select_item['LANG_NAME']) ? $lang_select_item['LANG_NAME'] : ''; ?></a>
					</td>
				</tr>
				<?php

} // END lang_select

if(isset($lang_select_item)) { unset($lang_select_item); } 

?>
			</table>
		</div>
		<div id="lang_select" class="js-sh-box">
			<script type="text/javascript">
			<!--
			tmp = 'lang_select';
			if(GetCookie(tmp) == '2')
			{
				ShowHide('lang_select', 'lang_select2', 'lang_select');
			}
			//-->
			</script>
		</div>
	</td>
</tr>
<?php } ?>
<tr>
	<th style="cursor: pointer;" align="left" onclick="ShowHide('rss_feed', 'rss_feed2', 'rss_feed');">
		<img src="<?php echo isset($this->vars['IMG_NAV_MENU_FEED']) ? $this->vars['IMG_NAV_MENU_FEED'] : $this->lang('IMG_NAV_MENU_FEED'); ?>" alt="<?php echo isset($this->vars['L_RSS_FEEDS']) ? $this->vars['L_RSS_FEEDS'] : $this->lang('L_RSS_FEEDS'); ?>" title="<?php echo isset($this->vars['L_RSS_FEEDS']) ? $this->vars['L_RSS_FEEDS'] : $this->lang('L_RSS_FEEDS'); ?>" />&nbsp;
		<a href="#" onclick="return false;" title="<?php echo isset($this->vars['L_RSS_FEEDS']) ? $this->vars['L_RSS_FEEDS'] : $this->lang('L_RSS_FEEDS'); ?>" class="nav-menu-link"><b><?php echo isset($this->vars['L_RSS_FEEDS']) ? $this->vars['L_RSS_FEEDS'] : $this->lang('L_RSS_FEEDS'); ?></b></a>
	</th>
</tr>
<tr>
	<td class="row5">
		<div id="rss_feed2" class="nav-menu">
			<table class="forumline-no2" width="100%" cellspacing="0" cellpadding="2" border="0">
				<tr>
					<td width="8" align="left"><img src="<?php echo isset($this->vars['IMG_NAV_MENU_RSS_FEED']) ? $this->vars['IMG_NAV_MENU_RSS_FEED'] : $this->lang('IMG_NAV_MENU_RSS_FEED'); ?>" alt="RSS" title="RSS" /></td>
					<td class="genmed" align="left">&nbsp;<?php echo isset($this->vars['RSS_NEWS_HELP']) ? $this->vars['RSS_NEWS_HELP'] : $this->lang('RSS_NEWS_HELP'); ?></td>
				</tr>
			</table>
		</div>
		<div id="rss_feed" class="js-sh-box">
			<script type="text/javascript">
			<!--
			tmp = 'rss_feed';
			if(GetCookie(tmp) == '2')
			{
				ShowHide('rss_feed', 'rss_feed2', 'rss_feed');
			}
			//-->
			</script>
		</div>
	</td>
</tr>

<?php if ($this->vars['NAV_MENU_ADS_BOTTOM']) {  ?>
<tr>
	<th style="cursor: pointer;" align="left">
		<img src="<?php echo isset($this->vars['IMG_NAV_MENU_SPONSOR']) ? $this->vars['IMG_NAV_MENU_SPONSOR'] : $this->lang('IMG_NAV_MENU_SPONSOR'); ?>" alt="<?php echo isset($this->vars['L_SPONSORS_LINKS']) ? $this->vars['L_SPONSORS_LINKS'] : $this->lang('L_SPONSORS_LINKS'); ?>" title="<?php echo isset($this->vars['L_SPONSORS_LINKS']) ? $this->vars['L_SPONSORS_LINKS'] : $this->lang('L_SPONSORS_LINKS'); ?>" />&nbsp;
		<a href="#" title="<?php echo isset($this->vars['L_SPONSORS_LINKS']) ? $this->vars['L_SPONSORS_LINKS'] : $this->lang('L_SPONSORS_LINKS'); ?>" class="nav-menu-link"><b><?php echo isset($this->vars['L_SPONSORS_LINKS']) ? $this->vars['L_SPONSORS_LINKS'] : $this->lang('L_SPONSORS_LINKS'); ?></b></a>
	</th>
</tr>
<tr><td class="row1"><?php echo isset($this->vars['NAV_MENU_ADS_BOTTOM']) ? $this->vars['NAV_MENU_ADS_BOTTOM'] : $this->lang('NAV_MENU_ADS_BOTTOM'); ?></td></tr>
<?php } ?>

<!--
<tr>
	<th style="cursor: pointer;" align="left">
		<img src="<?php echo isset($this->vars['IMG_NAV_MENU_SPONSOR']) ? $this->vars['IMG_NAV_MENU_SPONSOR'] : $this->lang('IMG_NAV_MENU_SPONSOR'); ?>" alt="<?php echo isset($this->vars['L_SPONSORS_LINKS']) ? $this->vars['L_SPONSORS_LINKS'] : $this->lang('L_SPONSORS_LINKS'); ?>" title="<?php echo isset($this->vars['L_SPONSORS_LINKS']) ? $this->vars['L_SPONSORS_LINKS'] : $this->lang('L_SPONSORS_LINKS'); ?>" />&nbsp;
		<a href="#" onclick="return false;" title="<?php echo isset($this->vars['L_SPONSORS_LINKS']) ? $this->vars['L_SPONSORS_LINKS'] : $this->lang('L_SPONSORS_LINKS'); ?>" class="nav-menu-link"><b><?php echo isset($this->vars['L_SPONSORS_LINKS']) ? $this->vars['L_SPONSORS_LINKS'] : $this->lang('L_SPONSORS_LINKS'); ?></b></a>
	</th>
</tr>
<tr>
	<td class="row5">
		<div id="sponsors" style="position: relative; padding-top: 0px; padding-bottom: 0px;">
			<table class="forumline-no2" width="100%" cellspacing="0" cellpadding="2" border="0">
				<tr>
					<td width="8" align="left"><?php echo isset($this->vars['IMG_NAV_MENU_SEP']) ? $this->vars['IMG_NAV_MENU_SEP'] : $this->lang('IMG_NAV_MENU_SEP'); ?></td>
					<td class="genmed" align="left"><a href="http://www.mightygorgon.com/">Mighty Gorgon</a></td>
				</tr>
			</table>
		</div>
	</td>
</tr>
-->

</table><?php echo isset($this->vars['IMG_TFL']) ? $this->vars['IMG_TFL'] : $this->lang('IMG_TFL'); ?><?php echo isset($this->vars['IMG_TFC']) ? $this->vars['IMG_TFC'] : $this->lang('IMG_TFC'); ?><?php echo isset($this->vars['IMG_TFR']) ? $this->vars['IMG_TFR'] : $this->lang('IMG_TFR'); ?>
</form>