<form action="{S_CHECKER_FILE_ACTION}" method="post">

<h1>{L_FILE_CHECKER}</h1>
<p>{L_FCHECKER_EXPLAIN}</p>

<table class="forumline">
<tr><th colspan="2">{L_FILE_CHECKER}</th></tr>
<!-- BEGIN check -->
<tr><td class="row1"><span class="cattitle"><strong>{L_FILE_CHECKER_SP1}</strong></span></td></tr>
<!-- BEGIN check_step1 -->
<tr><td class="row1"><span class="gensmall">{check.check_step1.DEL_DURL}</span></td></tr>
<!-- END check_step1 -->
<tr><td class="row1"><span class="cattitle"><strong>{L_FILE_CHECKER_SP2}</strong></span></B></td></tr>
<!-- BEGIN check_step2 -->
<tr><td class="row1"><span class="gensmall">{check.check_step2.DEL_SSURL}</span></td></tr>
<!-- END check_step2 -->
<tr><td class="row1"><span class="cattitle"><strong>{L_FILE_CHECKER_SP3}</strong></span></B></td></tr>
<!-- BEGIN check_step3 -->
<tr><td class="row1"><span class="gensmall">{check.check_step3.DEL_FILE}</span></td></tr>
<!-- END check_step3 -->
<tr><td class="row1"><span class="cattitle"><strong>{L_FILE_CHECKER_SAVED}:</strong></span> <span class="gensmall">{SAVED}.</span></td></tr>
<tr><td class="cat">&nbsp;</td></tr>
<!-- END check -->

<!-- BEGIN perform -->
<tr><td class="row1">{L_FILE_SAFTEY}</td></tr>
<tr><td align="center" class="cat"><input class="mainoption" type="submit" value="{L_FILE_PERFORM}" name="B1" /><input type="hidden" name="safety" value="1" /></td></tr>
<!-- END perform -->
</table>
</form>
