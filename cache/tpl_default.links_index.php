<?php

// eXtreme Styles mod cache. Generated on Fri, 27 Sep 2013 15:34:11 +0000 (time = 1380296051)

if (!defined('IN_ICYPHOENIX')) exit;

?><?php  $this->set_filename('xs_include_8544d414511c995c79decb294d9243c2', 'overall_header.tpl', true);  $this->pparse('xs_include_8544d414511c995c79decb294d9243c2');  ?>

<script type="text/javascript">
// <![CDATA[
function checkForm()
{
	formErrors = false;

	if (document.linkdata.link_title.value == '')
	{
		formErrors = '<?php echo isset($this->vars['L_LINK_TITLE']) ? $this->vars['L_LINK_TITLE'] : $this->lang('L_LINK_TITLE'); ?>';
	}
	else if (document.linkdata.link_url.value == 'http://')
	{
		formErrors = '<?php echo isset($this->vars['L_LINK_URL']) ? $this->vars['L_LINK_URL'] : $this->lang('L_LINK_URL'); ?>';
	}
	else if (document.linkdata.link_logo_src.value == 'http://' )
	{
		formErrors = '<?php echo isset($this->vars['L_LINK_LOGO_SRC']) ? $this->vars['L_LINK_LOGO_SRC'] : $this->lang('L_LINK_LOGO_SRC'); ?>';
	}
	else if (document.linkdata.link_category.value == '' )
	{
		formErrors = '<?php echo isset($this->vars['L_LINK_CATEGORY']) ? $this->vars['L_LINK_CATEGORY'] : $this->lang('L_LINK_CATEGORY'); ?>';
	}
	else if (document.linkdata.link_desc.value == '' )
	{
		formErrors = '<?php echo isset($this->vars['L_LINK_DESC']) ? $this->vars['L_LINK_DESC'] : $this->lang('L_LINK_DESC'); ?>';
	}

	if (formErrors)
	{
		alert('<?php echo isset($this->vars['L_PLEASE_ENTER_YOUR']) ? $this->vars['L_PLEASE_ENTER_YOUR'] : $this->lang('L_PLEASE_ENTER_YOUR'); ?>' + formErrors);
		return false;
	}

	return true;
}
// ]]>
</script>
<?php  $this->set_filename('xs_include_701f14a2db23df28ce9cb0ed564e6a4a', 'links_leftblock.tpl', true);  $this->pparse('xs_include_701f14a2db23df28ce9cb0ed564e6a4a');  ?>
	<td width="100%" nowrap="nowrap" valign="top">
		<?php echo isset($this->vars['IMG_THL']) ? $this->vars['IMG_THL'] : $this->lang('IMG_THL'); ?><?php echo isset($this->vars['IMG_THC']) ? $this->vars['IMG_THC'] : $this->lang('IMG_THC'); ?><span class="forumlink"><?php echo isset($this->vars['L_SITE_LINKS']) ? $this->vars['L_SITE_LINKS'] : $this->lang('L_SITE_LINKS'); ?></span><?php echo isset($this->vars['IMG_THR']) ? $this->vars['IMG_THR'] : $this->lang('IMG_THR'); ?><table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<th colspan="2" width="75%"><?php echo isset($this->vars['L_LINK_CATEGORY']) ? $this->vars['L_LINK_CATEGORY'] : $this->lang('L_LINK_CATEGORY'); ?></th>
			<th><?php echo isset($this->vars['L_SITE_LINKS']) ? $this->vars['L_SITE_LINKS'] : $this->lang('L_SITE_LINKS'); ?></th>
		</tr>
		<?php

$linkrow_count = ( isset($this->_tpldata['linkrow.']) ) ? sizeof($this->_tpldata['linkrow.']) : 0;
for ($linkrow_i = 0; $linkrow_i < $linkrow_count; $linkrow_i++)
{
 $linkrow_item = &$this->_tpldata['linkrow.'][$linkrow_i];
 $linkrow_item['S_ROW_COUNT'] = $linkrow_i;
 $linkrow_item['S_NUM_ROWS'] = $linkrow_count;

?>
		<tr>
			<td class="<?php echo isset($linkrow_item['ROW_CLASS']) ? $linkrow_item['ROW_CLASS'] : ''; ?> row-center" width="30" style="padding-right:5px;" nowrap="nowrap"><img src="<?php echo isset($this->vars['FOLDER_IMG']) ? $this->vars['FOLDER_IMG'] : $this->lang('FOLDER_IMG'); ?>" alt="<?php echo isset($linkrow_item['LINK_TITLE']) ? $linkrow_item['LINK_TITLE'] : ''; ?>" title="<?php echo isset($linkrow_item['LINK_TITLE']) ? $linkrow_item['LINK_TITLE'] : ''; ?>" /></td>
			<td class="<?php echo isset($linkrow_item['ROW_CLASS']) ? $linkrow_item['ROW_CLASS'] : ''; ?> row-forum" width="100%" data-href="<?php echo isset($linkrow_item['LINK_URL']) ? $linkrow_item['LINK_URL'] : ''; ?>"><span class="forumlink"><a href="<?php echo isset($linkrow_item['LINK_URL']) ? $linkrow_item['LINK_URL'] : ''; ?>" class="forumlink"><?php echo isset($linkrow_item['LINK_TITLE']) ? $linkrow_item['LINK_TITLE'] : ''; ?></a></span></td>
			<td class="<?php echo isset($linkrow_item['ROW_CLASS']) ? $linkrow_item['ROW_CLASS'] : ''; ?> row-center-small"><span class="genmed"><?php echo isset($linkrow_item['LINK_NUMBER']) ? $linkrow_item['LINK_NUMBER'] : ''; ?></span></td>
		</tr>
		<?php

} // END linkrow

if(isset($linkrow_item)) { unset($linkrow_item); } 

?>
		</table><?php echo isset($this->vars['IMG_TFL']) ? $this->vars['IMG_TFL'] : $this->lang('IMG_TFL'); ?><?php echo isset($this->vars['IMG_TFC']) ? $this->vars['IMG_TFC'] : $this->lang('IMG_TFC'); ?><?php echo isset($this->vars['IMG_TFR']) ? $this->vars['IMG_TFR'] : $this->lang('IMG_TFR'); ?>
		<div align="center" style="font-family: Verdana; font-size: 10px; letter-spacing: -1px"><br />Links MOD v1.2.2 by <a href="http://www.phpbb2.de" target="_blank">phpBB2.de</a> and OOHOO and CRLin.</div>
	</td>
</tr>
</table>

<?php  $this->set_filename('xs_include_36847d2de93631b4b57519c62733dac7', 'overall_footer.tpl', true);  $this->pparse('xs_include_36847d2de93631b4b57519c62733dac7');  ?>