<?php

// eXtreme Styles mod cache. Generated on Fri, 27 Sep 2013 15:34:11 +0000 (time = 1380296051)

if (!defined('IN_ICYPHOENIX')) exit;

?><meta http-equiv="content-type" content="text/html; charset=<?php echo isset($this->vars['S_CONTENT_ENCODING']) ? $this->vars['S_CONTENT_ENCODING'] : $this->lang('S_CONTENT_ENCODING'); ?>" />
<meta http-equiv="content-style-type" content="text/css" />
<?php echo isset($this->vars['META']) ? $this->vars['META'] : $this->lang('META'); ?>
<?php echo isset($this->vars['META_TAG']) ? $this->vars['META_TAG'] : $this->lang('META_TAG'); ?>
<?php echo isset($this->vars['NAV_LINKS']) ? $this->vars['NAV_LINKS'] : $this->lang('NAV_LINKS'); ?>
<title><?php echo isset($this->vars['PAGE_TITLE']) ? $this->vars['PAGE_TITLE'] : $this->lang('PAGE_TITLE'); ?></title>

<link rel="shortcut icon" href="<?php echo isset($this->vars['FULL_SITE_PATH']) ? $this->vars['FULL_SITE_PATH'] : $this->lang('FULL_SITE_PATH'); ?>images/favicon.ico" />
<link rel="stylesheet" href="<?php echo isset($this->vars['FULL_SITE_PATH']) ? $this->vars['FULL_SITE_PATH'] : $this->lang('FULL_SITE_PATH'); ?><?php echo isset($this->vars['T_TPL_PATH']) ? $this->vars['T_TPL_PATH'] : $this->lang('T_TPL_PATH'); ?>style_<?php echo isset($this->vars['CSS_COLOR']) ? $this->vars['CSS_COLOR'] : $this->lang('CSS_COLOR'); ?>.css" type="text/css" />
<?php

$css_style_include_count = ( isset($this->_tpldata['css_style_include.']) ) ? sizeof($this->_tpldata['css_style_include.']) : 0;
for ($css_style_include_i = 0; $css_style_include_i < $css_style_include_count; $css_style_include_i++)
{
 $css_style_include_item = &$this->_tpldata['css_style_include.'][$css_style_include_i];
 $css_style_include_item['S_ROW_COUNT'] = $css_style_include_i;
 $css_style_include_item['S_NUM_ROWS'] = $css_style_include_count;

?>
<link rel="stylesheet" href="<?php echo isset($this->vars['FULL_SITE_PATH']) ? $this->vars['FULL_SITE_PATH'] : $this->lang('FULL_SITE_PATH'); ?><?php echo isset($this->vars['T_TPL_PATH']) ? $this->vars['T_TPL_PATH'] : $this->lang('T_TPL_PATH'); ?><?php echo isset($css_style_include_item['CSS_FILE']) ? $css_style_include_item['CSS_FILE'] : ''; ?>" type="text/css" />
<?php

} // END css_style_include

if(isset($css_style_include_item)) { unset($css_style_include_item); } 

?>
<?php

$css_include_count = ( isset($this->_tpldata['css_include.']) ) ? sizeof($this->_tpldata['css_include.']) : 0;
for ($css_include_i = 0; $css_include_i < $css_include_count; $css_include_i++)
{
 $css_include_item = &$this->_tpldata['css_include.'][$css_include_i];
 $css_include_item['S_ROW_COUNT'] = $css_include_i;
 $css_include_item['S_NUM_ROWS'] = $css_include_count;

?>
<link rel="stylesheet" href="<?php echo isset($this->vars['FULL_SITE_PATH']) ? $this->vars['FULL_SITE_PATH'] : $this->lang('FULL_SITE_PATH'); ?><?php echo isset($this->vars['T_COMMON_TPL_PATH']) ? $this->vars['T_COMMON_TPL_PATH'] : $this->lang('T_COMMON_TPL_PATH'); ?><?php echo isset($css_include_item['CSS_FILE']) ? $css_include_item['CSS_FILE'] : ''; ?>" type="text/css" />
<?php

} // END css_include

if(isset($css_include_item)) { unset($css_include_item); } 

?>

<?php  $this->set_filename('xs_include_2e5667ce0b829a6c71304886b995c479', 'overall_inc_header_js.tpl', true);  $this->pparse('xs_include_2e5667ce0b829a6c71304886b995c479');  ?>

<?php if ($this->vars['S_XMAS_FX']) {  ?>
<script type="text/javascript" src="<?php echo isset($this->vars['FULL_SITE_PATH']) ? $this->vars['FULL_SITE_PATH'] : $this->lang('FULL_SITE_PATH'); ?><?php echo isset($this->vars['T_COMMON_TPL_PATH']) ? $this->vars['T_COMMON_TPL_PATH'] : $this->lang('T_COMMON_TPL_PATH'); ?>js/snowstorm.js"></script>
<?php } ?>

<?php

$switch_new_download_count = ( isset($this->_tpldata['switch_new_download.']) ) ? sizeof($this->_tpldata['switch_new_download.']) : 0;
for ($switch_new_download_i = 0; $switch_new_download_i < $switch_new_download_count; $switch_new_download_i++)
{
 $switch_new_download_item = &$this->_tpldata['switch_new_download.'][$switch_new_download_i];
 $switch_new_download_item['S_ROW_COUNT'] = $switch_new_download_i;
 $switch_new_download_item['S_NUM_ROWS'] = $switch_new_download_count;

?>
<script type="text/javascript">
<!--
	window.open('<?php echo isset($switch_new_download_item['U_NEW_DOWNLOAD_POPUP']) ? $switch_new_download_item['U_NEW_DOWNLOAD_POPUP'] : ''; ?>', '_blank', 'width=400,height=225,resizable=yes');
//-->
</script>
<?php

} // END switch_new_download

if(isset($switch_new_download_item)) { unset($switch_new_download_item); } 

?>

<?php echo isset($this->vars['UPI2DB_FIRST_USE']) ? $this->vars['UPI2DB_FIRST_USE'] : $this->lang('UPI2DB_FIRST_USE'); ?>
