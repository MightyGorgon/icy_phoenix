<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
*
* @Extra credits for this file
* Christian Knerr (cback) - (www.cback.de)
*
*/

/**
* In this file we have some constants we use in some places of the
* CrackerTracker System and we define some definitions for the Spammer
* and Throw-Away Mail detection. Definition Updates will also just be
* a replacement of this file. ;-)
*
* @author Christian Knerr (cback)
* @package ctracker
* @version 5.0.6
* @since 22.07.2006 - 01:57:01
* @copyright (c) 2006 www.cback.de
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt!');
}

/*
* Constants we need
*/

$ct_config_array = array(); // Reset CT Config Array

$ct_config_array = array('ctracker_ipblock_enabled', 'ctracker_ipblock_logsize', 'ctracker_auto_recovery', 'ctracker_vconfirm_guest', 'ctracker_autoban_mails', 'ctracker_search_time_guest', 'ctracker_search_time_user', 'ctracker_search_count_guest', 'ctracker_search_count_user', 'ctracker_massmail_protection', 'ctracker_reg_protection', 'ctracker_reg_blocktime', 'ctracker_reg_lastip', 'ctracker_pwreset_time', 'ctracker_massmail_time', 'ctracker_spammer_time', 'ctracker_spammer_postcount', 'ctracker_spammer_blockmode', 'ctracker_loginfeature', 'ctracker_pw_reset_feature', 'ctracker_reg_last_reg', 'ctracker_login_history', 'ctracker_login_history_count', 'ctracker_login_ip_check', 'ctracker_pw_validity', 'ctracker_pw_complex_min', 'ctracker_pw_complex_mode', 'ctracker_pw_control', 'ctracker_pw_complex', 'ctracker_last_file_scan', 'ctracker_last_checksum_scan', 'ctracker_logsize_logins', 'ctracker_logsize_spammer', 'ctracker_reg_ip_scan', 'ctracker_global_message', 'ctracker_global_message_type', 'ctracker_search_feature_enabled', 'ctracker_spam_attack_boost', 'ctracker_spam_keyword_det', 'ctracker_footer_layout');

/*
* Extended Definitions
*/
$ct_spammer_def = array(); // Reset Definition Array "Spammer"
$ct_mailscn_def = array(); // Reset Definition Array "Mailscan"
$ct_userspm_def = array(); // Reset Definition Array "Userblocks"

$ct_spammer_def = array(
	'*pills*',
	'*viagra*',
	'*phentermine*',
	'*buycheapphenter*',
	'*animalporn*',
	'*online*roulette*',
	'*on*line*casino*',
	'*casino*on*line*',
	'*masterbell.net*',
	'*Your*site*there*is*future*',
	'*online*dating*',
	'*forumgratis.com*',
	'*valium*',
	'*fantasticsex*',
	'*free-sex*',
	'*free*nice*pics*',
	'*you*search*friend*',
	'*dating*',
	'*flirt*',
	'*my_photos*',
);

$ct_mailscn_def = array(
	'*@bumpymail.com',
	'*@centermail.com',
	'*@centermail.net',
	'*@discardmail.com',
	'*@dodgeit.com',
	'*@dontsendmespam.de',
	'*@emailias.com',
	'*@ghosttexter.de',
	'*@jetable.net',
	'*@kasmail.com',
	'*@mailexpire.com',
	'*@mailinator.com',
	'*@messagebeamer.de',
	'*@mytrashmail.com',
	'*@trashmail.net',
	'*@nervmich.net',
	'*@nervtmich.net',
	'*@netzidiot.de',
	'*@nurfuerspam.de',
	'*@privacy.net',
	'*@punkass.com',
	'*@sneakemail.com',
	'*@sofort-mail.de',
	'*@spam.la',
	'*@spambob.com',
	'*@spamex.com',
	'*@spamgourmet.com',
	'*@spamhole.com',
	'*@spaminator.de',
	'*@spammotel.com',
	'*@spamtrail.com',
	'*@trash-mail.de',
	'*@emaildienst.de',
	'*@temporarily.de',
	'*@temporaryinbox.com',
	'*@willhackforfood.biz',
	'*@wegwerfadresse.de',
	'*@emailto.de',
	'*@dumpmail.de',
	'*@spamoff.de',
	'*@twinmail.de',
	'*@gawab.com',
	'*@mail.ru',
	'*@*boom.ru',
	'*@m-s-n.net',
	'*@*cigarettes.*',
	'*@*pharmacy.*',
	'*@net-search.org',
	'*@my-yep.com',
	'*@ezigaretten.*',
	'*@portsaid.cc',
	'*@pisem.net',
);

$ct_userspm_def = array(
	'funtklakow',
	'jtfoe1974',
	'unmmyns',
	'coldsorin',
	'fairlande',
	'largepafilis',
	'pirsrv',
	'sadlatour',
	'bighor-lam',
	'greatfintan',
	'budowa_cepa',
	'cepelin',
	'yuriyhoy',
	'printerxp',
	'kololos',
);

?>