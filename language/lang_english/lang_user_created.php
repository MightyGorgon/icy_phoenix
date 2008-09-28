<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

$lang['BBC_Overview'] = '
<ul>
<li><span class="topic_ann"><b>What is Icy Phoenix?</b></span><br /></li>
<li><span class="topic_imp">Icy Phoenix is a CMS based on phpBB (a fully scalable and highly customisable open-source Bulletin Board package PHP based) plus many modifications and code integrations which add flexibility to the whole package. The official home page for phpBB is <a class="post-url" href="http://www.phpbb.com/" target="_blank">www.phpbb.com</a>. Icy Phoenix has some features originally developed for <acronym title="phpBB eXpertS">phpBB XS Project</acronym> which has been founded by Bicet and then developed by both Bicet and Mighty Gorgon. Icy Phoenix has been created by <a class="post-url" href="http://www.mightygorgon.com/" target="_blank">Mighty Gorgon</a> after he left the <acronym title="phpBB eXpertS">phpBB XS Project</acronym>.</span><br /><br /></li>
<li><span class="topic_ann"><b>What are the main features of Icy Phoenix?</b></span><br /></li>
<li>
<span class="topic_imp">Icy Phoenix has many features, most of them are listed in the <a class="post-url" href="http://www.icyphoenix.com/credits.php">Credits</a> on this site, but the main ones are:</span><br />
<ul>
<li><span class="topic_imp">phpBB bulletin board and permission system</span><br /></li>
<li><span class="topic_imp">CMS features allowing the creation of new pages and blocks (some of the functions are based on the abandoned IM Portal project)</span><br /></li>
<li><span class="topic_imp">Overall template integration among all site sections</span><br /></li>
<li><span class="topic_imp">Many ready to use features: Photo Gallery, Downloads, Knowledge Base, Links, Chat...</span><br /></li>
<li><span class="topic_imp">Multi-language and multi-template ready</span><br /></li>
<li><span class="topic_imp">Almost 100% XHTML and CSS W3C compliant</span><br /></li>
<li><span class="topic_imp">...and many others...</span><br /></li>
</ul>
<br /><br />
</li>
<li><span class="topic_ann"><b>Is Icy Phoenix supported for bugs, security issues, improvements?</b></span><br /></li>
<li><span class="topic_imp">Icy Phoenix is an open source project. As like many open source projects it is developed by people using their free time. At the moment there are several persons in the staff willing to help and to contribute to this project. We hope the community will continue to grow and be able to provide all the necessary support to all the users who may need help.</span><br /><br /></li>
<li><span class="topic_ann"><b>Is Icy Phoenix easy to install and upgrade from other platforms?</b></span><br /></li>
<li><span class="topic_imp">Icy Phoenix has its own setup procedure which guides the user through the steps of the setup process. An upgrading file is provided to upgrade the package from standard phpBB and <acronym title="Icy Phoenix Ancestor">phpBB XS</acronym>. Hopefully an upgrade procedure from any other platform will be written in the future: at the moment the only way to upgrade from another pre-modded is by downgrading it to phpBB (has been written a procedure for this) and then run the provided upgrade procedure.</span><br /><br /></li>
<li><span class="topic_ann"><b>Does Icy Phoenix have many templates?</b></span><br /></li>
<li><span class="topic_imp">Yes, at the moment there are some free templates (some of them with multicolour variations) and we are working to create new ones. If you are interested in new templates you should regularly check the <a class="post-url" href="http://www.icyphoenix.com/" target="_blank">support forum</a>.</span><br /><br /></li>
<li><span class="topic_ann"><b>Is Icy Phoenix multi-language?</b></span><br /></li>
<li><span class="topic_imp">English is the main language of Icy Phoenix, but it has been translated into other languages (alphabetical order): Catalan, Dutch, Galego, German, Italian, Serbian, Spanish... and more to come! If you don\'t find your language listed here, please ask at the <a class="post-url" href="http://www.icyphoenix.com/" target="_blank">support forum</a>, maybe someone else is working on the translation you need and you would be welcome to join the translation team to give your contribution.</span><br /><br /></li>
<li><span class="topic_ann"><b>Will Icy Phoenix be upgraded to phpBB 3?</b></span><br /></li>
<li><span class="topic_imp">At the moment there is no plan to upgrade Icy Phoenix to phpBB 3. There are several reasons for this. Of course when phpBB 3 will be out for a while, things may change...</span><br /><br /></li>
<li><span class="topic_ann"><b>May I join the Icy Phoenix Project?</b></span><br /></li>
<li><span class="topic_imp">Of course. Icy Phoenix is an open source project, and anyone who is willing to give his own contribution on a stable basis may then apply to join the Team.</span><br /><br /></li>
</ul>
';

$lang['BBC_License'] = '
<b>Icy Phoenix</b>, which has been created by Mighty Gorgon, is CMS based on a pre-modded version of phpBB plus several phpBB MODs, plus many other features coded by Mighty Gorgon and Icy Phoenix Staff. Since phpBB is released under GNU, Icy Phoenix as well is released under the GNU License (you may read its term here: <a class="post-url" href="http://www.icyphoenix.com/docs/COPYING" target="_blank">GNU</a>)<br /><br />
Credits for all included MODs and their Authors could be found at this link: <a class="post-url" href="http://www.icyphoenix.com/credits.php" target="_blank">Credits</a>.<br /><br />
phpBB &copy; 2007 <a class="post-url" href="http://www.phpbb.com/" target="_blank">phpBB Group</a>.<br />
Icy Phoenix &copy; 2007 <a class="post-url" href="http://www.icyphoenix.com/" target="_blank">Icy Phoenix Staff</a>.
';

$lang['BBC_Requirements'] = '
Before trying to install Icy Phoenix make sure you have the following <b>STRICTLY NEEDED</b> requirements:
<ul>
<li>At least 20 MB of free space in the folder you would like to install the package</li>
<li>Web server with PHP (4 or higher) installed and running (works best on Linux + Apache)</li>
<li>MySQL (3 or higher) database with at least 1MB of free space (of course the space needed depends on how big your site will be... 1MB is enough for a basic empty setup)</li>
<li>Ability to set CHMOD permissions</li>
</ul>
<br /><br />
These other requirements (even if not strictly needed) are suggested for optimal performance of Icy Phoenix:
<ul>
<li>Webserver with .htaccess capability</li>
<li>Apache Rewrite Mod installed and running</li>
<li>GD Libraries (at least 2.0.28) installed and running</li>
<li>Register Globals set to OFF</li>
</ul>
';

$lang['BBC_Fresh_Installation'] = '
<ol type="1">
<li>Unpack Icy Phoenix package to one folder on your HD.</li>
<li>Copy all Icy Phoenix files in one folder on your webserver (i.e. /icyphoenix/)</li>
<li>
Set the permissions to <span class="text_red">CHMOD 777</span> to the following files and folders (please note that you may not have all of them in your setup):<br />
<ul>
<li><b>backup/</b></li>
<li><b>cache/</b></li>
<li><b>cache/sql/</b></li>
<li><b>cache/users/</b></li>
<li><b>ctracker/logfiles/logfile_attempt_counter.txt</b></li>
<li><b>ctracker/logfiles/logfile_blocklist.txt</b></li>
<li><b>ctracker/logfiles/logfile_debug_mode.txt</b></li>
<li><b>ctracker/logfiles/logfile_malformed_logins.txt</b></li>
<li><b>ctracker/logfiles/logfile_spammer.txt</b></li>
<li><b>ctracker/logfiles/logfile_worms.txt</b></li>
<li><b>downloads/</b></li>
<li><b>files/</b></li>
<li><b>files/album/</b></li>
<li><b>files/album/cache/</b></li>
<li><b>files/album/med_cache/</b></li>
<li><b>files/album/users/</b></li>
<li><b>files/album/wm_cache/</b></li>
<li><b>files/posted_images/</b></li>
<li><b>files/thumbs/</b></li>
<li><b>images/avatars/</b></li>
<li><b>logs/</b></li>
<li><b>pafiledb/uploads/</b></li>
<li><b>pafiledb/cache/</b></li>
<li><b>pafiledb/cache/templates/</b></li>
<li><b>pafiledb/cache/templates/mg_themes/</b></li>
<li><b>pafiledb/images/screenshots/</b></li>
</ul>
<br />
</li>
<li>
Set the permissions to <span class="text_red">CHMOD 666</span> to the following files:<br />
<ul>
<li><b>includes/def_themes.php</b></li>
<li><b>includes/def_tree.php</b></li>
<li><b>includes/def_words.php</b></li>
<!-- <li><b>language/lang_*/lang_extend.php</b></li> -->
</ul>
<br />
</li>
<li>Launch the setup from the install folder: <b>install/install.php</b> (i.e. <a class="post-url" href="http://www.mysite.com/icyphoenix/install/install.php">http://www.mysite.com/forum/install/install.php</a>)</li>
<li>Follow all the instructions on the screen, fill all requested data and complete the setup.</li>
<li>Delete or rename <b>install</b> folder.</li>
<li>Customize what needs to be customized in files and db (some files needs to be edited manually, while most of the options may be set in ACP and CMS).</li>
<li>Enjoy your New Site <img src="http://www.icyphoenix.com/images/smiles/icon_mrgreen.gif" alt="Enjoy!" title="Enjoy!" />.</li>
</ol>
';

$lang['BBC_Upgrade_phpbb'] = '
If you\'re already using phpBB, you can easily upgrade your board by following these instructions:<br />
<ol type="1">
<li><span class="text_red">Make a full backup of all files and database</span> (if you don\'t do a backup, don\'t dare to ask for support! <img src="http://www.icyphoenix.com/images/smiles/icon_mrgreen.gif" alt="Backup Rules!" title="Backup Rules!" />)</li>
<li>Did you backup everything? If you didn\'t make the backup, please, step back to the previous point.</li>
<li>Copy your current <b>config.php</b> and keep it in a safe place.</li>
<li>Check again that your backup is ok and keep it in a safe place. <img src="http://www.icyphoenix.com/images/smiles/icon_mrgreen.gif" alt="Backup Rules!" title="Backup Rules!" /></li>
<li>Login as administrator.</li>
<li>Unpack Icy Phoenix package to one folder on your HD.</li>
<li>Upload and launch <b>update_to_ip.php</b> (which is in <b>install</b> folder) to the root of your phpBB forum (i.e. http://www.mysite.com/phpbb/update_to_ip.php). Please that if <b>update_to_ip.php</b> is not in the root of your forum, update it won\'t work correctly.</li>
<li>Delete <b>update_to_ip.php</b>.</li>
<li>Remove the following folders (make sure you don\'t have in those folders some files you may need for some mods you have installed in standard phpBB, but in any case you should have a backup <img src="http://www.icyphoenix.com/images/smiles/icon_mrgreen.gif" alt="Backup Rules!" title="Backup Rules!" />):
<ul>
<li><b>admin</b></li>
<li><b>db</b></li>
<li><b>includes</b></li>
<li><b>language</b></li>
<li><b>templates</b></li>
</ul>
</li>
<li>Remove <b>all files</b> but <b>config.php</b> in your forum root (make sure you don\'t have some files you may need for some mods you have installed in standard phpBB, but in any case you should have a backup <img src="http://www.icyphoenix.com/images/smiles/icon_mrgreen.gif" alt="Backup Rules!" title="Backup Rules!" />).</li>
<li>Upload all Icy Phoenix files (all but <b>config.php</b>) in the same folder where phpBB was installed and running. Pay attention that all older files eventually still there must be replaced by new ones.</li>
<li>Apply all permissions listed in Fresh Installation.</li>
<li>Customize what needs to be customized in files and db (some files needs to be edited manually, while most of the options may be set in ACP and CMS).</li>
<li>Unlock your site.</li>
<li>Enjoy your New <b>Icy Phoenix</b> Site <img src="http://www.icyphoenix.com/images/smiles/icon_mrgreen.gif" alt="Enjoy!" title="Enjoy!" />.</li>
</ol>
';

$lang['BBC_Upgrade_XS'] = '
If you\'re using phpBB XS, you can upgrade your board by following these instructions (based on phpBB XS 058 standard setup):<br />
<ol type="1">
<li><span class="text_red">Make a full backup of all files and database</span> (if you don\'t do a backup, don\'t dare to ask for support! <img src="http://www.icyphoenix.com/images/smiles/icon_mrgreen.gif" alt="Backup Rules!" title="Backup Rules!" />)</li>
<li>Did you backup everything? If you didn\'t make the backup, please, step back to the previous point.</li>
<li>Copy your current <b>.htaccess</b>, <b>config.php</b>, <b>includes/def_themes.php</b>, <b>includes/def_tree.php</b>, <b>includes/def_words.php</b> and keep them in a safe place.</li>
<li>Check again that your backup is ok and keep it in a safe place. <img src="http://www.icyphoenix.com/images/smiles/icon_mrgreen.gif" alt="Backup Rules!" title="Backup Rules!" /></li>
<li>Login as administrator.</li>
<li>Lock your site.</li>
<li>Unpack Icy Phoenix package to one folder on your HD.</li>
<li>Upload and launch <b>update_to_ip.php</b> (which is in <b>install</b> folder) to the root of your phpBB XS (i.e. http://www.mysite.com/xs/update_to_ip.php). Please that if <b>update_to_ip.php</b> is not in the root of your phpBB XS, update it won\'t work correctly.</li>
<li>Delete <b>update_to_ip.php</b>.</li>
<li>Move the following folders:
<ul>
<li><b>album_mod/upload/*.*</b> ==> <b>files/album/*.*</b></li>
</ul>
</li>
<li>Remove the following folders (make sure you don\'t have in those folders some files you may need for some mods you have installed, but in any case you should have a backup <img src="http://www.icyphoenix.com/images/smiles/icon_mrgreen.gif" alt="Backup Rules!" title="Backup Rules!" />):
<ul>
<li><b>adm</b></li>
<li><b>album_mod</b></li>
<li><b>attach_mod</b></li>
<li><b>bb_usage_stats</b></li>
<li><b>blocks</b></li>
<li><b>captcha</b></li>
<li><b>ctracker</b></li>
<li><b>db</b></li>
<li><b>docs</b></li>
<li><b>errors</b></li>
<li><b>hl</b></li>
<li><b>includes</b> (all but <b>includes/def_tree.php</b> and <b>includes/def_words.php</b>)</li>
<li><b>language</b></li>
<li><b>mo</b></li>
<li><b>mods</b></li>
<li><b>stat_modules</b></li>
<li><b>templates</b></li>
<li><b>xs_mod</b></li>
</ul>
</li>
<li>Remove <b>all files</b> but <b>config.php</b> in your phpBB XS root (make sure you don\'t have some files you may need for some mods you have installed, but in any case you should have a backup <img src="http://www.icyphoenix.com/images/smiles/icon_mrgreen.gif" alt="Backup Rules!" title="Backup Rules!" />).</li>
<li>Upload all Icy Phoenix files (all but <b>config.php</b>, <b>includes/def_tree.php</b> and <b>includes/def_words.php</b>) in the same folder where phpBB XS was installed and running. Pay attention that all older files eventually still there must be replaced by new ones.</li>
<li>Apply all permissions listed in Fresh Installation.</li>
<li>Customize what needs to be customized in files and db (some files needs to be edited manually, while most of the options may be set in ACP and CMS).</li>
<li>Unlock your site.</li>
<li>Enjoy your New <b>Icy Phoenix</b> Site <img src="http://www.icyphoenix.com/images/smiles/icon_mrgreen.gif" alt="Enjoy!" title="Enjoy!" />.</li>
</ol>
';

$lang['BBC_Upgrade_IP'] = '
If you\'re using Icy Phoenix 1.0.9.9 or above, you can upgrade your board by following these instructions:<br />
<ol type="1">
<li><span class="text_red">Make a full backup of all files and database</span> (if you don\'t do a backup, don\'t dare to ask for support! <img src="http://www.icyphoenix.com/images/smiles/icon_mrgreen.gif" alt="Backup Rules!" title="Backup Rules!" />)</li>
<li>Did you backup everything? If you didn\'t make the backup, please, step back to the previous point.</li>
<li>Copy your current <b>.htaccess</b>, <b>config.php</b>, <b>includes/def_themes.php</b>, <b>includes/def_tree.php</b>, <b>includes/def_words.php</b> and keep them in a safe place.</li>
<li>Check again that your backup is ok and keep it in a safe place. <img src="http://www.icyphoenix.com/images/smiles/icon_mrgreen.gif" alt="Backup Rules!" title="Backup Rules!" /></li>
<li>Login as administrator.</li>
<li>Lock your site.</li>
<li>Unpack Icy Phoenix package to one folder on your HD.</li>
<li>Upload and launch <b>update_to_ip.php</b> (which is in <b>install</b> folder) to the root of your Icy Phoenix (i.e. http://www.mysite.com/ip/update_to_ip.php). Please that if <b>update_to_ip.php</b> is not in the root of your site, update it won\'t work correctly.</li>
<li>Delete <b>update_to_ip.php</b>.</li>
<li>Remove the following folders (make sure you don\'t have in those folders some files you may need for some mods you have installed, but in any case you should have a backup <img src="http://www.icyphoenix.com/images/smiles/icon_mrgreen.gif" alt="Backup Rules!" title="Backup Rules!" />):
<ul>
<li><b>adm</b></li>
<li><b>includes</b> (all but <b>includes/def_themes.php</b>, <b>includes/def_tree.php</b> and <b>includes/def_words.php</b>)</li>
<li><b>language</b></li>
<li><b>templates</b></li>
</ul>
</li>
<li>Remove <b>all files</b> but <b>config.php</b> in your Icy Phoenix root (make sure you don\'t have some files you may need for some mods you have installed, but in any case you should have a backup <img src="http://www.icyphoenix.com/images/smiles/icon_mrgreen.gif" alt="Backup Rules!" title="Backup Rules!" />).</li>
<li>Upload all Icy Phoenix files (all but <b>config.php</b>, <b>includes/def_themes.php</b>, <b>includes/def_tree.php</b> and <b>includes/def_words.php</b>) in the same folder where Icy Phoenix was installed and running. Pay attention that all older files eventually still there must be replaced by new ones.</li>
<li>Apply all permissions listed in Fresh Installation.</li>
<li>Customize what needs to be customized in files and db (some files needs to be edited manually, while most of the options may be set in ACP and CMS).</li>
<li>Unlock your site.</li>
<li>Enjoy your New <b>Icy Phoenix</b> Site <img src="http://www.icyphoenix.com/images/smiles/icon_mrgreen.gif" alt="Enjoy!" title="Enjoy!" />.</li>
</ol>
';

?>