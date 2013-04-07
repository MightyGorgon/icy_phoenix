<!-- IF B_ADMIN --><div class="block-container"><a class="block-edit-link" href="{B_EDIT_LINK}"><img src="{IMG_CMS_ICON_EDIT}" alt="" title="{L_CMS_EDIT_PARENT_BLOCK}" /></a><!-- ENDIF -->
<!-- IF BORDER -->
<table width="100%" class="forum-buttons" style="background: none; background-image: none;" align="center" cellspacing="0" cellpadding="0">
<!-- ELSE -->
<table width="100%" align="center" cellspacing="0" cellpadding="0" border="0">
<!-- ENDIF -->
<tr>
<!-- IF BACKGROUND -->
<!-- IF TITLE --><td width="100%" class="forum-buttons"><!-- ELSE --><td width="100%" class="forum-buttons" style="border: none;"><!-- ENDIF -->
<!-- ELSE -->
<td width="100%" style="background: none;background-image: none;">
<!-- ENDIF -->
{OUTPUT}
</td>
</tr>
</table>
<!-- IF B_ADMIN --></div><!-- ENDIF -->