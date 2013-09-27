<?php

// eXtreme Styles mod cache. Generated on Fri, 27 Sep 2013 15:34:11 +0000 (time = 1380296051)

if (!defined('IN_ICYPHOENIX')) exit;

?><?php if ($this->vars['S_LOFI']) {  ?>
<?php if ($this->vars['S_LOFI_BOTS']) {  ?>
<?php  $this->set_filename('xs_include_cbae6cc53193b384fadff7e8957e9194', '../common/lofi/bots/lofi_bots_header.tpl', true);  $this->pparse('xs_include_cbae6cc53193b384fadff7e8957e9194');  ?>
<?php } else { ?>
<?php  $this->set_filename('xs_include_65d8ecfa09ec54a9dc7a197337572340', '../common/lofi/lofi_header.tpl', true);  $this->pparse('xs_include_65d8ecfa09ec54a9dc7a197337572340');  ?>
<?php } ?>
<?php } else { ?>
<?php echo isset($this->vars['DOCTYPE_HTML']) ? $this->vars['DOCTYPE_HTML'] : $this->lang('DOCTYPE_HTML'); ?>
<head>
<?php  $this->set_filename('xs_include_288b42a448c5db3f9d5b6d07fd28d0c8', 'overall_inc_header.tpl', true);  $this->pparse('xs_include_288b42a448c5db3f9d5b6d07fd28d0c8');  ?>
<?php echo isset($this->vars['EXTRA_CSS_JS']) ? $this->vars['EXTRA_CSS_JS'] : $this->lang('EXTRA_CSS_JS'); ?>

<?php if ($this->vars['S_HEADER_DROPDOWN']) {  ?>
<script type="text/javascript">

/***********************************************
* AnyLink Drop Down Menu- © Dynamic Drive (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit http://www.dynamicdrive.com/ for full source code
***********************************************/

//Contents for menu 1
var menu1 = new Array();
menu1[0] = '<br /><div class="center-block-text">&nbsp;<form action="<?php echo isset($this->vars['FULL_SITE_PATH']) ? $this->vars['FULL_SITE_PATH'] : $this->lang('FULL_SITE_PATH'); ?><?php echo isset($this->vars['U_SEARCH']) ? $this->vars['U_SEARCH'] : $this->lang('U_SEARCH'); ?>" method="post"><input name="search_keywords" type="text" class="post" style="width: 150px;" />&nbsp;<input type="submit" class="mainoption" value="<?php echo isset($this->vars['L_SEARCH']) ? $this->vars['L_SEARCH'] : $this->lang('L_SEARCH'); ?>" />&nbsp;<br /><\/form><\/div>';
menu1[1] = '&nbsp;<a href="<?php echo isset($this->vars['FULL_SITE_PATH']) ? $this->vars['FULL_SITE_PATH'] : $this->lang('FULL_SITE_PATH'); ?><?php echo isset($this->vars['U_SEARCH']) ? $this->vars['U_SEARCH'] : $this->lang('U_SEARCH'); ?>" title="<?php echo isset($this->vars['L_SEARCH_EXPLAIN']) ? $this->vars['L_SEARCH_EXPLAIN'] : $this->lang('L_SEARCH_EXPLAIN'); ?>"><?php echo isset($this->vars['L_ADV_SEARCH']) ? $this->vars['L_ADV_SEARCH'] : $this->lang('L_ADV_SEARCH'); ?><\/a>';
//Contents for menu 2
var menu2 = new Array();
menu2[0] = '<a href="<?php echo isset($this->vars['FULL_SITE_PATH']) ? $this->vars['FULL_SITE_PATH'] : $this->lang('FULL_SITE_PATH'); ?><?php echo isset($this->vars['U_SEARCH_NEW']) ? $this->vars['U_SEARCH_NEW'] : $this->lang('U_SEARCH_NEW'); ?>" title="<?php echo isset($this->vars['L_SEARCH_NEW']) ? $this->vars['L_SEARCH_NEW'] : $this->lang('L_SEARCH_NEW'); ?>"><?php echo isset($this->vars['L_SEARCH_NEW2']) ? $this->vars['L_SEARCH_NEW2'] : $this->lang('L_SEARCH_NEW2'); ?><\/a>';
menu2[1] = '<?php echo isset($this->vars['L_DISPLAY_UNREAD']) ? $this->vars['L_DISPLAY_UNREAD'] : $this->lang('L_DISPLAY_UNREAD'); ?>';
menu2[2] = '<?php echo isset($this->vars['L_DISPLAY_MARKED']) ? $this->vars['L_DISPLAY_MARKED'] : $this->lang('L_DISPLAY_MARKED'); ?>';
menu2[3] = '<?php echo isset($this->vars['L_DISPLAY_PERMANENT']) ? $this->vars['L_DISPLAY_PERMANENT'] : $this->lang('L_DISPLAY_PERMANENT'); ?>';

var menuwidth = '180px'; //default menu width
var disappeardelay = 250; //menu disappear speed onMouseout (in miliseconds)
var hidemenu_onclick = "no"; //hide menu when user clicks within menu?

/////No further editing needed
if (ie4 || ns6)
{
	document.write('<div id="dropmenudiv" class="row1" style="visibility: hidden; width:' + menuwidth + ';" onmouseover="clearhidemenu();" onmouseout="dynamichide(event);"><\/div>');
}
</script>

<script type="text/javascript" src="<?php echo isset($this->vars['FULL_SITE_PATH']) ? $this->vars['FULL_SITE_PATH'] : $this->lang('FULL_SITE_PATH'); ?><?php echo isset($this->vars['T_COMMON_TPL_PATH']) ? $this->vars['T_COMMON_TPL_PATH'] : $this->lang('T_COMMON_TPL_PATH'); ?>js/ddmenu.js"></script>

<script type="text/javascript">
if (hidemenu_onclick == "yes")
{
	document.onclick = hidemenu;
}
</script>
<?php } ?>

</head>
<body>

<div id="global-wrapper">
<span><a name="top" id="top"></a></span>
<?php echo isset($this->vars['TOP_HTML_BLOCK']) ? $this->vars['TOP_HTML_BLOCK'] : $this->lang('TOP_HTML_BLOCK'); ?>
<?php if ($this->vars['GH_BLOCK']) {  ?><?php

$gheader_blocks_row_count = ( isset($this->_tpldata['gheader_blocks_row.']) ) ? sizeof($this->_tpldata['gheader_blocks_row.']) : 0;
for ($gheader_blocks_row_i = 0; $gheader_blocks_row_i < $gheader_blocks_row_count; $gheader_blocks_row_i++)
{
 $gheader_blocks_row_item = &$this->_tpldata['gheader_blocks_row.'][$gheader_blocks_row_i];
 $gheader_blocks_row_item['S_ROW_COUNT'] = $gheader_blocks_row_i;
 $gheader_blocks_row_item['S_NUM_ROWS'] = $gheader_blocks_row_count;

?><?php echo isset($gheader_blocks_row_item['CMS_BLOCK']) ? $gheader_blocks_row_item['CMS_BLOCK'] : ''; ?><?php

} // END gheader_blocks_row

if(isset($gheader_blocks_row_item)) { unset($gheader_blocks_row_item); } 

?><?php } ?>
<?php if ($this->vars['PROFILE_VIEW']) {  ?><script type="text/javascript">window.open('<?php echo isset($this->vars['U_PROFILE_VIEW']) ? $this->vars['U_PROFILE_VIEW'] : $this->lang('U_PROFILE_VIEW'); ?>','_blank','height=800,width=250,resizable=yes');</script><?php } ?>

<?php echo isset($this->vars['PAGE_BEGIN']) ? $this->vars['PAGE_BEGIN'] : $this->lang('PAGE_BEGIN'); ?>
<table id="forumtable" cellspacing="0" cellpadding="0">
<?php if ($this->vars['GT_BLOCK']) {  ?>
<tr><td width="100%" colspan="3"><?php

$ghtop_blocks_row_count = ( isset($this->_tpldata['ghtop_blocks_row.']) ) ? sizeof($this->_tpldata['ghtop_blocks_row.']) : 0;
for ($ghtop_blocks_row_i = 0; $ghtop_blocks_row_i < $ghtop_blocks_row_count; $ghtop_blocks_row_i++)
{
 $ghtop_blocks_row_item = &$this->_tpldata['ghtop_blocks_row.'][$ghtop_blocks_row_i];
 $ghtop_blocks_row_item['S_ROW_COUNT'] = $ghtop_blocks_row_i;
 $ghtop_blocks_row_item['S_NUM_ROWS'] = $ghtop_blocks_row_count;

?><?php echo isset($ghtop_blocks_row_item['CMS_BLOCK']) ? $ghtop_blocks_row_item['CMS_BLOCK'] : ''; ?><?php

} // END ghtop_blocks_row

if(isset($ghtop_blocks_row_item)) { unset($ghtop_blocks_row_item); } 

?></td></tr>
<?php } ?>
<tr>
	<td width="100%" colspan="3" valign="top">
		<div id="top_logo">
		<table class="" width="100%" cellspacing="0" cellpadding="0" border="0">
		<tr>
		<td align="left" height="100%" valign="middle">
		<?php if ($this->vars['GL_BLOCK']) {  ?>
		<?php

$ghleft_blocks_row_count = ( isset($this->_tpldata['ghleft_blocks_row.']) ) ? sizeof($this->_tpldata['ghleft_blocks_row.']) : 0;
for ($ghleft_blocks_row_i = 0; $ghleft_blocks_row_i < $ghleft_blocks_row_count; $ghleft_blocks_row_i++)
{
 $ghleft_blocks_row_item = &$this->_tpldata['ghleft_blocks_row.'][$ghleft_blocks_row_i];
 $ghleft_blocks_row_item['S_ROW_COUNT'] = $ghleft_blocks_row_i;
 $ghleft_blocks_row_item['S_NUM_ROWS'] = $ghleft_blocks_row_count;

?><?php echo isset($ghleft_blocks_row_item['OUTPUT']) ? $ghleft_blocks_row_item['OUTPUT'] : ''; ?><?php

} // END ghleft_blocks_row

if(isset($ghleft_blocks_row_item)) { unset($ghleft_blocks_row_item); } 

?>
		<?php } else { ?>
		<div id="logo-img"><a href="<?php echo isset($this->vars['FULL_SITE_PATH']) ? $this->vars['FULL_SITE_PATH'] : $this->lang('FULL_SITE_PATH'); ?><?php echo isset($this->vars['U_PORTAL']) ? $this->vars['U_PORTAL'] : $this->lang('U_PORTAL'); ?>" title="<?php echo isset($this->vars['L_HOME']) ? $this->vars['L_HOME'] : $this->lang('L_HOME'); ?>"><img src="<?php echo isset($this->vars['FULL_SITE_PATH']) ? $this->vars['FULL_SITE_PATH'] : $this->lang('FULL_SITE_PATH'); ?><?php echo isset($this->vars['SITELOGO']) ? $this->vars['SITELOGO'] : $this->lang('SITELOGO'); ?>" alt="<?php echo isset($this->vars['L_HOME']) ? $this->vars['L_HOME'] : $this->lang('L_HOME'); ?>" title="<?php echo isset($this->vars['L_HOME']) ? $this->vars['L_HOME'] : $this->lang('L_HOME'); ?>" /></a></div>
		<?php } ?>
		</td>
		<td align="center" valign="middle"><?php if ($this->vars['S_HEADER_BANNER']) {  ?><center><br /><?php echo isset($this->vars['HEADER_BANNER_CODE']) ? $this->vars['HEADER_BANNER_CODE'] : $this->lang('HEADER_BANNER_CODE'); ?></center><?php } else { ?>&nbsp;<?php } ?></td>
		<td align="right" valign="middle">
		<!-- <div class="sitedes"><h1><?php echo isset($this->vars['SITENAME']) ? $this->vars['SITENAME'] : $this->lang('SITENAME'); ?></h1><h2><?php echo isset($this->vars['SITE_DESCRIPTION']) ? $this->vars['SITE_DESCRIPTION'] : $this->lang('SITE_DESCRIPTION'); ?></h2></div> -->
		<?php if ($this->vars['GR_BLOCK']) {  ?>
		<?php

$ghright_blocks_row_count = ( isset($this->_tpldata['ghright_blocks_row.']) ) ? sizeof($this->_tpldata['ghright_blocks_row.']) : 0;
for ($ghright_blocks_row_i = 0; $ghright_blocks_row_i < $ghright_blocks_row_count; $ghright_blocks_row_i++)
{
 $ghright_blocks_row_item = &$this->_tpldata['ghright_blocks_row.'][$ghright_blocks_row_i];
 $ghright_blocks_row_item['S_ROW_COUNT'] = $ghright_blocks_row_i;
 $ghright_blocks_row_item['S_NUM_ROWS'] = $ghright_blocks_row_count;

?><?php echo isset($ghright_blocks_row_item['OUTPUT']) ? $ghright_blocks_row_item['OUTPUT'] : ''; ?><?php

} // END ghright_blocks_row

if(isset($ghright_blocks_row_item)) { unset($ghright_blocks_row_item); } 

?>
		<?php } else { ?>
		<?php if ($this->vars['S_LOGGED_IN']) {  ?>&nbsp;<?php } else { ?>&nbsp;<?php } ?>
		<?php } ?>
		</td>
		</tr>
		</table>
		</div>
	</td>
</tr>

<?php if ($this->vars['GB_BLOCK']) {  ?>
<tr><td width="100%" colspan="3"><?php

$ghbottom_blocks_row_count = ( isset($this->_tpldata['ghbottom_blocks_row.']) ) ? sizeof($this->_tpldata['ghbottom_blocks_row.']) : 0;
for ($ghbottom_blocks_row_i = 0; $ghbottom_blocks_row_i < $ghbottom_blocks_row_count; $ghbottom_blocks_row_i++)
{
 $ghbottom_blocks_row_item = &$this->_tpldata['ghbottom_blocks_row.'][$ghbottom_blocks_row_i];
 $ghbottom_blocks_row_item['S_ROW_COUNT'] = $ghbottom_blocks_row_i;
 $ghbottom_blocks_row_item['S_NUM_ROWS'] = $ghbottom_blocks_row_count;

?><?php echo isset($ghbottom_blocks_row_item['CMS_BLOCK']) ? $ghbottom_blocks_row_item['CMS_BLOCK'] : ''; ?><?php

} // END ghbottom_blocks_row

if(isset($ghbottom_blocks_row_item)) { unset($ghbottom_blocks_row_item); } 

?></td></tr>
<?php } else { ?>
<tr>
	<td width="100%" class="forum-buttons" colspan="3">
		<a href="<?php echo isset($this->vars['FULL_SITE_PATH']) ? $this->vars['FULL_SITE_PATH'] : $this->lang('FULL_SITE_PATH'); ?><?php echo isset($this->vars['U_PORTAL']) ? $this->vars['U_PORTAL'] : $this->lang('U_PORTAL'); ?>"><?php echo isset($this->vars['L_HOME']) ? $this->vars['L_HOME'] : $this->lang('L_HOME'); ?></a>&nbsp;&nbsp;<img src="<?php echo isset($this->vars['FULL_SITE_PATH']) ? $this->vars['FULL_SITE_PATH'] : $this->lang('FULL_SITE_PATH'); ?><?php echo isset($this->vars['IMG_MENU_SEP']) ? $this->vars['IMG_MENU_SEP'] : $this->lang('IMG_MENU_SEP'); ?>" alt="" />&nbsp;
		<a href="<?php echo isset($this->vars['FULL_SITE_PATH']) ? $this->vars['FULL_SITE_PATH'] : $this->lang('FULL_SITE_PATH'); ?><?php echo isset($this->vars['U_INDEX']) ? $this->vars['U_INDEX'] : $this->lang('U_INDEX'); ?>"><?php echo isset($this->vars['L_INDEX']) ? $this->vars['L_INDEX'] : $this->lang('L_INDEX'); ?></a>&nbsp;&nbsp;<img src="<?php echo isset($this->vars['FULL_SITE_PATH']) ? $this->vars['FULL_SITE_PATH'] : $this->lang('FULL_SITE_PATH'); ?><?php echo isset($this->vars['IMG_MENU_SEP']) ? $this->vars['IMG_MENU_SEP'] : $this->lang('IMG_MENU_SEP'); ?>" alt="" />&nbsp;
		<?php

$switch_upi2db_off_count = ( isset($this->_tpldata['switch_upi2db_off.']) ) ? sizeof($this->_tpldata['switch_upi2db_off.']) : 0;
for ($switch_upi2db_off_i = 0; $switch_upi2db_off_i < $switch_upi2db_off_count; $switch_upi2db_off_i++)
{
 $switch_upi2db_off_item = &$this->_tpldata['switch_upi2db_off.'][$switch_upi2db_off_i];
 $switch_upi2db_off_item['S_ROW_COUNT'] = $switch_upi2db_off_i;
 $switch_upi2db_off_item['S_NUM_ROWS'] = $switch_upi2db_off_count;

?>
		<a href="<?php echo isset($this->vars['FULL_SITE_PATH']) ? $this->vars['FULL_SITE_PATH'] : $this->lang('FULL_SITE_PATH'); ?><?php echo isset($this->vars['U_SEARCH_NEW']) ? $this->vars['U_SEARCH_NEW'] : $this->lang('U_SEARCH_NEW'); ?>"><?php echo isset($this->vars['L_NEW2']) ? $this->vars['L_NEW2'] : $this->lang('L_NEW2'); ?></a>&nbsp;&nbsp;<img src="<?php echo isset($this->vars['FULL_SITE_PATH']) ? $this->vars['FULL_SITE_PATH'] : $this->lang('FULL_SITE_PATH'); ?><?php echo isset($this->vars['IMG_MENU_SEP']) ? $this->vars['IMG_MENU_SEP'] : $this->lang('IMG_MENU_SEP'); ?>" alt="" />&nbsp;
		<?php

} // END switch_upi2db_off

if(isset($switch_upi2db_off_item)) { unset($switch_upi2db_off_item); } 

?>
		<?php

$switch_upi2db_on_count = ( isset($this->_tpldata['switch_upi2db_on.']) ) ? sizeof($this->_tpldata['switch_upi2db_on.']) : 0;
for ($switch_upi2db_on_i = 0; $switch_upi2db_on_i < $switch_upi2db_on_count; $switch_upi2db_on_i++)
{
 $switch_upi2db_on_item = &$this->_tpldata['switch_upi2db_on.'][$switch_upi2db_on_i];
 $switch_upi2db_on_item['S_ROW_COUNT'] = $switch_upi2db_on_i;
 $switch_upi2db_on_item['S_NUM_ROWS'] = $switch_upi2db_on_count;

?>
		<span style="vertical-align: top;"><?php echo isset($this->vars['L_POSTS']) ? $this->vars['L_POSTS'] : $this->lang('L_POSTS'); ?>:&nbsp;</span><a href="<?php echo isset($this->vars['FULL_SITE_PATH']) ? $this->vars['FULL_SITE_PATH'] : $this->lang('FULL_SITE_PATH'); ?><?php echo isset($this->vars['U_SEARCH_NEW']) ? $this->vars['U_SEARCH_NEW'] : $this->lang('U_SEARCH_NEW'); ?>"><?php echo isset($this->vars['L_NEW2']) ? $this->vars['L_NEW2'] : $this->lang('L_NEW2'); ?></a><span style="vertical-align: top;">&nbsp;&#8226;&nbsp;</span><?php echo isset($this->vars['L_DISPLAY_U']) ? $this->vars['L_DISPLAY_U'] : $this->lang('L_DISPLAY_U'); ?><span style="vertical-align: top;">&nbsp;&#8226;&nbsp;</span><?php echo isset($this->vars['L_DISPLAY_M']) ? $this->vars['L_DISPLAY_M'] : $this->lang('L_DISPLAY_M'); ?><span style="vertical-align: top;">&nbsp;&#8226;&nbsp;</span><?php echo isset($this->vars['L_DISPLAY_P']) ? $this->vars['L_DISPLAY_P'] : $this->lang('L_DISPLAY_P'); ?>&nbsp;&nbsp;<img src="<?php echo isset($this->vars['FULL_SITE_PATH']) ? $this->vars['FULL_SITE_PATH'] : $this->lang('FULL_SITE_PATH'); ?><?php echo isset($this->vars['IMG_MENU_SEP']) ? $this->vars['IMG_MENU_SEP'] : $this->lang('IMG_MENU_SEP'); ?>" alt="" />&nbsp;
		<?php

} // END switch_upi2db_on

if(isset($switch_upi2db_on_item)) { unset($switch_upi2db_on_item); } 

?>
		<?php if ($this->vars['S_LOGGED_IN']) {  ?>
		<a href="<?php echo isset($this->vars['FULL_SITE_PATH']) ? $this->vars['FULL_SITE_PATH'] : $this->lang('FULL_SITE_PATH'); ?><?php echo isset($this->vars['U_PROFILE']) ? $this->vars['U_PROFILE'] : $this->lang('U_PROFILE'); ?>"><?php echo isset($this->vars['L_PROFILE']) ? $this->vars['L_PROFILE'] : $this->lang('L_PROFILE'); ?></a>&nbsp;&nbsp;<img src="<?php echo isset($this->vars['FULL_SITE_PATH']) ? $this->vars['FULL_SITE_PATH'] : $this->lang('FULL_SITE_PATH'); ?><?php echo isset($this->vars['IMG_MENU_SEP']) ? $this->vars['IMG_MENU_SEP'] : $this->lang('IMG_MENU_SEP'); ?>" alt="" />&nbsp;
		<?php } ?>
		<?php if ($this->vars['S_HEADER_DROPDOWN']) {  ?>
		<a href="<?php echo isset($this->vars['FULL_SITE_PATH']) ? $this->vars['FULL_SITE_PATH'] : $this->lang('FULL_SITE_PATH'); ?><?php echo isset($this->vars['U_SEARCH']) ? $this->vars['U_SEARCH'] : $this->lang('U_SEARCH'); ?>" onmouseover="dropdownmenu(this, event, menu1, '250px');" onmouseout="delayhidemenu();"><?php echo isset($this->vars['L_SEARCH']) ? $this->vars['L_SEARCH'] : $this->lang('L_SEARCH'); ?></a>&nbsp;&nbsp;<img src="<?php echo isset($this->vars['FULL_SITE_PATH']) ? $this->vars['FULL_SITE_PATH'] : $this->lang('FULL_SITE_PATH'); ?><?php echo isset($this->vars['IMG_MENU_SEP']) ? $this->vars['IMG_MENU_SEP'] : $this->lang('IMG_MENU_SEP'); ?>" alt="" />&nbsp;
		<?php } else { ?>
		<a href="<?php echo isset($this->vars['FULL_SITE_PATH']) ? $this->vars['FULL_SITE_PATH'] : $this->lang('FULL_SITE_PATH'); ?><?php echo isset($this->vars['U_SEARCH']) ? $this->vars['U_SEARCH'] : $this->lang('U_SEARCH'); ?>"><?php echo isset($this->vars['L_SEARCH']) ? $this->vars['L_SEARCH'] : $this->lang('L_SEARCH'); ?></a>&nbsp;&nbsp;<img src="<?php echo isset($this->vars['FULL_SITE_PATH']) ? $this->vars['FULL_SITE_PATH'] : $this->lang('FULL_SITE_PATH'); ?><?php echo isset($this->vars['IMG_MENU_SEP']) ? $this->vars['IMG_MENU_SEP'] : $this->lang('IMG_MENU_SEP'); ?>" alt="" />&nbsp;
		<?php } ?>
		<a href="<?php echo isset($this->vars['FULL_SITE_PATH']) ? $this->vars['FULL_SITE_PATH'] : $this->lang('FULL_SITE_PATH'); ?><?php echo isset($this->vars['U_FAQ']) ? $this->vars['U_FAQ'] : $this->lang('U_FAQ'); ?>"><?php echo isset($this->vars['L_FAQ']) ? $this->vars['L_FAQ'] : $this->lang('L_FAQ'); ?></a>&nbsp;&nbsp;<img src="<?php echo isset($this->vars['FULL_SITE_PATH']) ? $this->vars['FULL_SITE_PATH'] : $this->lang('FULL_SITE_PATH'); ?><?php echo isset($this->vars['IMG_MENU_SEP']) ? $this->vars['IMG_MENU_SEP'] : $this->lang('IMG_MENU_SEP'); ?>" alt="" />&nbsp;
		<?php if (! $this->vars['S_LOGGED_IN']) {  ?>
		<a href="<?php echo isset($this->vars['FULL_SITE_PATH']) ? $this->vars['FULL_SITE_PATH'] : $this->lang('FULL_SITE_PATH'); ?><?php echo isset($this->vars['U_REGISTER']) ? $this->vars['U_REGISTER'] : $this->lang('U_REGISTER'); ?>"><?php echo isset($this->vars['L_REGISTER']) ? $this->vars['L_REGISTER'] : $this->lang('L_REGISTER'); ?></a>&nbsp;&nbsp;<img src="<?php echo isset($this->vars['FULL_SITE_PATH']) ? $this->vars['FULL_SITE_PATH'] : $this->lang('FULL_SITE_PATH'); ?><?php echo isset($this->vars['IMG_MENU_SEP']) ? $this->vars['IMG_MENU_SEP'] : $this->lang('IMG_MENU_SEP'); ?>" alt="" />&nbsp;
		<?php } ?>
		<a href="<?php echo isset($this->vars['FULL_SITE_PATH']) ? $this->vars['FULL_SITE_PATH'] : $this->lang('FULL_SITE_PATH'); ?><?php echo isset($this->vars['U_LOGIN_LOGOUT']) ? $this->vars['U_LOGIN_LOGOUT'] : $this->lang('U_LOGIN_LOGOUT'); ?>"><?php echo isset($this->vars['L_LOGIN_LOGOUT2']) ? $this->vars['L_LOGIN_LOGOUT2'] : $this->lang('L_LOGIN_LOGOUT2'); ?></a>
	</td>
</tr>
<?php } ?>

<?php if ($this->vars['S_PAGE_NAV']) {  ?><tr><td width="100%" colspan="3"><div style="margin-left: 7px; margin-right: 7px;"><?php  $this->set_filename('xs_include_8f760c6ef7014bb30119efec29d7fbb2', 'breadcrumbs_main.tpl', true);  $this->pparse('xs_include_8f760c6ef7014bb30119efec29d7fbb2');  ?></div></td></tr><?php } ?>

<?php  $this->set_filename('xs_include_9926807285e1438307b0889ec062e31c', 'overall_inc_body.tpl', true);  $this->pparse('xs_include_9926807285e1438307b0889ec062e31c');  ?>

<?php } ?>