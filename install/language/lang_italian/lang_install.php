<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge($lang, array(
	'ENCODING' => 'iso-8859-1',
	'ENCODING_ALT' => 'utf8',
	'DIRECTION' => 'ltr',
	'HEADER_LANG' => 'it',
	'HEADER_XML_LANG' => 'it',
	'LEFT' => 'sinistra',
	'RIGHT' => 'destra',

	'Welcome_install' => 'Installazione di Icy Phoenix',
	'Initial_config' => 'Configurazione',
	'DB_config' => 'Configurazione Database',
	'Admin_config' => 'Configurazione Amministrazione',
	'continue_upgrade' => 'Dopo aver scaricato il file di configurazione sul tuo computer puoi cliccare sul bottone \"Continua l\'Aggiornamento\" qui sotto per avanzare con il processo di aggiornamento. Caricare il file di configurazione aspetta la fine del processo di aggiornamento.',
	'upgrade_submit' => 'Continua Aggiornamento',

	'Installer_Error' => 'Si &egrave; verificato un errore durante l\'installazione',
	'Previous_Install' => 'E\' stata rilevata una precedente installazione',
	'Install_db_error' => 'Si &egrave; verificato un errore durante l\'aggiornamento del database',

	'Re_install' => 'La tua installazione precedente &egrave; ancora attiva. <br /><br />Se vuoi installare di nuovo Icy Phoenix clicca il bottone di conferma qui sotto. Sappi che questa operazione distrugger&agrave; tutti i dati esistenti, non verr&agrave; fatto alcun backup! Username e Password dell\'amministratore che hai usato per entrare nel forum verranno ricreate dopo la nuova installazione, nessun altra impostazione verr&agrave; mantenuta. <br /><br />Pensaci bene prima di CONFERMARE!',

	'Inst_Step_0' => 'Grazie per aver scelto Icy Phoenix. Questa procedura guidata ti aiuter&agrave; a completare l\'installazione.<br /><span class="text_red">Prima di procedere assicurati di aver caricato tutti i files necessari sul tuo server e di avere un database con tutti i dati d\'accesso.</span>',

	'Inst_Step_1' => 'Per completare correttamente l\'installazione devi riempire tutti i campi sottostanti.<br /><span class="text_red">Prima di procedere assicurati di avere tutti i dati di accesso per il database (il database in cui vuoi installare Icy Phoenix deve essere gi&agrave; esistente, perch&eacute; la procedura di installazione non pu&ograve; crearne uno!).</span>',

	'Start_Install' => 'Inizia Installazione',
	'Start_Install_Anyway' => 'Inizia Installazione Comunque',
	'Finish_Install' => 'Termina Installazione',
	'Continue_Install' => 'Prosegui Installazione',

	'CHMOD_Files' => 'Permessi Files &amp; Cartelle',
	'CHMOD_OK' => 'Ok',
	'CHMOD_Error' => 'Errore',
	'CHMOD_777' => 'CHMOD 777',
	'CHMOD_666' => 'CHMOD 666',
	'CHMOD_Files_Explain_Error' => 'Si sono verificati degli errori durante la verifica dei permessi a files e cartelle. Assicurati che tutti i files del pacchetto siano stati caricati correttamente sul server ed abbiano i permessi corretti, altrimenti Icy Phoenix potrebbe non funzionare correttamente..',
	'Confirm_Install_anyway' => 'Se sei sicuro di aver caricato tutti i files ed aver applicato i permessi correttamente allora puoi proseguire cliccando su "<i>Inizia Installazione Comunque</i>".',
	'CHMOD_Files_Explain_Ok' => 'Files e Cartelle sembrano avere i permessi impostati correttamente.',
	'Can_Install' => 'Puoi proseguire con l\'installazione.',
	'CHMOD_File_Exists' => 'Permessi impostati correttamente per questo File/Cartella',
	'CHMOD_File_NotExists' => 'Questo File/Cartella non esiste, per favore effettua l\'upload ed applica i permessi corretti',
	'CHMOD_File_Exists_Read_Only' => 'Questo File/Cartella esiste ma i permessi non possono essere impostati automaticamente, per favore impostali manualmente (se non lo hai gi&agrave; fatto) e poi clicca su "<i>Inizia Installazione Comunque</i>".',
	'CHMOD_File_UnknownError' => 'Errore sconosciuto. Per favore verifica che questo File/Cartella esista sul server, abbia i permessi assegnati correttamente e poi clicca su "<i>Inizia Installazione Comunque</i>".',
	'CHMOD_Apply' => 'Applica automaticamente i permessi CHMOD alle cartelle di Icy Phoenix.',
	'CHMOD_Apply_Warn' => 'Attenzione, non tutti i server supportano CHMOD via PHP, potrebbero essere necessari interventi manuali!!!',

	'Default_lang' => 'Lingua predefinita',
	'Select_lang' => 'Lingua',
	'DB_Host' => 'Database Server Hostname / DSN',
	'DB_Name' => 'Nome Database',
	'DB_Username' => 'Username Database',
	'DB_Password' => 'Password Database',
	'Database' => 'Il Tuo Database',
	'Install_lang' => 'Scegli una Lingua per l\'Installazione',
	'dbms' => 'Tipo di Database',
	'Table_Prefix' => 'Prefisso Tabelle Database',
	'Admin_Username' => 'Username Amministratore',
	'Admin_Password' => 'Password Amministratore',
	'Admin_Password_confirm' => 'Password Amministratore [ Conferma ]',

	'Inst_Step_2' => 'L\'account di Amministratore &egrave; stato creato.<br />Prima di poter procedere oltre &egrave; necessario <span class="text_red">eliminare la cartella <b>install</b> e la cartella <b>contrib</b> (se presente)</span> dallo spazio web.<br />Dopo aver effettuato queste modifiche, clicca sul pulsante <b>Termina Installazione</b> per essere reindirizzato sul sito. Una volta effettuato l\'accesso &egrave; consigliabile accedere Pannello Di Amministrazione (ACP) per poter configurare le impostazioni principali (lingua, stili, forum, download, album, permessi, ecc.). Le impostazioni riguardanti le pagine del sito, i blocchi, i menu personalizzati e l\'accesso alle varie pagine del sito debbono essere gestiti tramite la sezione CMS (Content Management System). Altre impostazioni sono contenute nei files <b>.htaccess</b> e <b>lang_main_settings.php</b> dove &egrave; possibile personalizzare ad esempio le parole chiave, il titolo del sito, le pagine di errore ed altre impostazioni.<br /><br />Grazie per aver scelto Icy Phoenix, e ricordati di effettuare periodicamente dei backup del tuo database.<br /><br />',

	'Unwriteable_config' => 'Il programma di installazione non riesce a scrivere il file config.php automaticamente. Puoi scaricare una copia del tuo file di configurazione cliccando sul bottone qui sotto. Devi caricare questo file nella stessa directory di Icy Phoenix. Successivamente potrai eliminare la cartella install e potrai effettuare l\'accesso con nome e password di amministrazione che hai fornito nel modulo precedente e andare nel pannello di controllo (un link apparir&agrave; in fondo ad ogni pagina dopo che sei entrato) per verificare le impostazioni generali di configurazione. Grazie per aver scelto Icy Phoenix.',
	'Download_config' => 'Scarica il file di Configurazione',

	'ftp_choose' => 'Scegli Metodo Scaricamento',
	'ftp_option' => '<br />Poich&egrave; le estensioni FTP non sono disponibili in questa versione di  PHP usa l\'opzione di caricare automaticamente via ftp il file di configurazione.',
	'ftp_instructs' => 'Hai scelto di caricare automaticamente via ftp il file sull\'account che contiene Icy Phoenix. Inserisci le informazioni per facilitare il processo. Il percorso FTP deve essere il percorso esatto dell\'installazione di Icy Phoenix come se stessi caricando via ftp con un normale programma client.',
	'ftp_info' => 'Inserisci le Tue Informazioni FTP',
	'Attempt_ftp' => 'Tentativo di caricare via FTP il file di configurazione',
	'Send_file' => 'Inviatemi il file e lo caricher&ograve; via FTP manualmente',
	'ftp_path' => 'Percorso FTP per Icy Phoenix',
	'ftp_username' => 'Il tuo Username FTP',
	'ftp_password' => 'La tua Password FTP',
	'Transfer_config' => 'Inizio Trasferimento',
	'NoFTP_config' => 'Il tentativo di trasferire il file via FTP &egrave; fallito. Scarica il file config e trasferiscilo sul server manualmente.',

	'Install' => 'Installa',
	'Upgrade' => 'Aggiorna',

	'Install_Method' => 'Scegli un metodo di installazione',
	'Install_No_Ext' => 'La configurazione php del tuo server non supporta il tipo di database che hai scelto',
	'Install_No_PCRE' => 'Icy Phoenix Richiede il Perl-Compatible Regular Expressions Module. La tua configurazione PHP non lo supporta!',

	'Server_name' => 'Nome Dominio',
	'Script_path' => 'Percorso Script',
	'Server_port' => 'Porta',
	'Admin_email' => 'Indirizzo Email Amministratore',

	'IP_Utilities' => 'Icy Phoenix Utility',
	'Upgrade_Options' => 'Opzioni Aggiornamento:',
	'Upgrade_From' => 'Aggiorna all\'ultima versione di Icy Phoenix',
	'Upgrade_From_Version' => 'dalla versione',
	'Upgrade_From_phpBB' => 'da qualunque versione di phpBB o phpBB XS',
	'Upgrade_Higher' => 'o superiore',

	'IcyPhoenix' => 'Icy Phoenix',
	'phpBB' => 'phpBB',
	'Information' => 'Informazioni',
	'VersionInformation' => 'Informazioni Server E Versione',
	'NotInstalled' => 'Non installato',
	'Current_IP_Version' => 'Icy Phoenix versione installata',
	'Current_phpBB_Version' => 'phpBB versione installata',
	'Latest_Release' => 'Ultima versione',
	'Version_UpToDate' => 'Versione aggiornata',
	'Version_NotUpdated' => 'Versione non aggiornata',
	'UpdateInProgress' => 'Aggiornamento in corso',
	'CleaningInProgress' => 'Rimozione files in corso',
	'UpdateCompleted' => 'Aggiornamento completato!',
	'UpdateCompleted_phpBB' => 'Aggiornamento phpBB completato, ora puoi installare Icy Phoenix!',
	'UpdateInProgress_Schema' => 'Aggiornamento struttura tabelle',
	'UpdateInProgress_Data' => 'Aggiornamento dati',
	'Optimizing_Tables' => 'Ottimizzazione tabelle',
	'Progress' => 'In corso',
	'Done' => 'Completato',
	'NotDone' => 'Non Completato',
	'Result' => 'Risultato',
	'Error' => 'Errore',
	'Successful' => 'Correttamente',
	'NoErrors' => 'Nessun errore',
	'NoUpdate' => 'Nessun aggiornamento richiesto',
	'phpBB_NotDetected' => 'phpBB non &egrave; stato rilevato. Per favore verifica che phpBB sia correttamente installato.',
	'Update_Errors' => 'Alcune query hanno restituito degli errori',

	'DBUpdate_Success' => 'Le seguenti SQL sono state eseguite correttamente',
	'DBUpdate_Errors' => 'Le seguenti SQL hanno restituito degli errori',

	'FileWriting' => 'Scrittura Files',
	'FileCreation_OK' => 'Il tuo server sembrerebbe supportare la creazione / modifica automatica dei files.',
	'FileCreation_OK_Explain' => 'Questo script cercher&agrave; di creare / modificare automaticamente i files necessari.',
	'FileCreation_ERROR' => 'Il tuo server non supporta la creazione / modifica dei files.',
	'FileCreation_ERROR_Explain' => 'Questo script non pu&ograve; creare / modificare automaticamente i files necessari. Purtroppo dovrai farlo a mano..',

	'IcyPhoenix_Version_UpToDate' => 'La tua versione di Icy Phoenix &egrave; aggiornata',
	'IcyPhoenix_Version_NotUpToDate' => 'La tua versione di Icy Phoenix non &egrave; aggiornata',
	'IcyPhoenix_Version_NotInstalled' => 'Icy Phoenix non &egrave; installato',
	'phpBB_Version_UpToDate' => 'Your phpBB is version is up-to-date',
	'phpBB_Version_NotUpToDate' => 'Your phpBB is version is not up-to-date',
	'ClickUpdate' => 'Clicca %sQUI%s per aggiornare!',
	'ClickReturn' => 'Clicca %sQUI%s per tornare al menu!',

	'Clean_OldFiles_Explain' => 'Rimuovi tutti i files inutilizzati di Icy Phoenix (files rimasti sul server da vecchie versioni)',
	'ActionUndone' => 'Questa procedura non pu&ograve; essere annullata, i files eliminati non potranno essere ripristinati. Fai un backup prima di lanciarla!!!',
	'ClickToClean' => 'Clicca sul link sottostante per procedere',
	'FileDeletion_OK' => 'Il file &egrave; stato eliminato correttamente',
	'FileDeletion_ERROR' => 'Il file non pu&ograve; essere eliminato',
	'FileDeletion_NF' => 'Il file non &egrave; stato trovato',
	'FilesDeletion_OK' => 'Files eliminati correttamente',
	'FilesDeletion_NO' => 'Files non eliminati',
	'FilesDeletion_ERROR' => 'Files non possono essere eliminati automaticamente',
	'FilesDeletion_NF' => 'Files non trovati',
	'FilesDeletion_None' => 'Nessuno',
	'FileDeletion_Complete' => 'Pulizia files completata!',

	'Spoiler' => 'Spoiler',
	'Show' => 'Mostra',
	'Hide' => 'Nascondi',
	'None' => 'Nessuno',
	'Start' => 'Inizia',

	'Upgrade_Steps' => 'Upgrade Steps',
	'MakeFullBackup' => 'Make a full backup (both files and DB) and keep it in a safe place!',
	'Update_phpBB' => 'Update phpBB DB (if needed)',
	'Remove_BBCodeUID' => 'Process all posts: remove BBCode UID, replace text, remove old BBCodes',
	'Merge_PostsTables' => 'Merge posts tables',
	'Update_IcyPhoenix' => 'Update Icy Phoenix DB',
	'Upload_NewFiles' => 'Upload all new files',
	'Adjust_Config' => 'Update constants in config.php (only works if files are writeable)',
	'Adjust_CMSPages' => 'Update constants in CMS pages (only works if files are writeable)',
	'MoveImages' => 'Move images (optional: only if you want to use posted images into subfolders)',
	'Clean_OldFiles' => 'Clean Old Files',

	'Upgrade_Steps' => 'Passi per completare l\'aggiornamento',
	'MakeFullBackup' => 'Fai un backup completo di files e DB e tienilo in un posto sicuro!',
	'Update_phpBB' => 'Aggiornamento DB phpBB (se necessario)',
	'Remove_BBCodeUID' => 'Aggiornamento tabella messaggi: rimozione BBCode UID, sostituzioni di testo',
	'Merge_PostsTables' => 'Unisci tabelle messaggi',
	'Update_IcyPhoenix' => 'Aggiornamento DB Icy Phoenix',
	'Upload_NewFiles' => 'Carica i files della nuova versione',
	'Adjust_Config' => 'Aggiornamento costanti nel config.php (per funzionare &egrave; necessario che i files abbiano i permessi di scrittura)',
	'Adjust_CMSPages' => 'Aggiornamento costanti nelle pagine CMS (per funzionare &egrave; necessario che i files abbiano i permessi di scrittura)',
	'MoveImagesAlbum' => 'Spostamento immagini album (opzionale: da lanciare soltanto se vuoi memorizzare le immagini in sottocartelle per ogni utente)',
	'MoveImages' => 'Spostamento immagini inviate (opzionale: da lanciare soltanto se vuoi memorizzare le immagini in sottocartelle per ogni utente)',
	'Clean_OldFiles' => 'Rimuovi files non pi&ugrave; utilizzati',

	'ColorsLegend' => 'Legenda Colori',
	'ColorsLegendRed' => 'Rosso: azione obbligatoria da essere eseguita manualmente',
	'ColorsLegendOrange' => 'Arancione: azione obbligatoria che pu&ograve; essere eseguita automaticamente (se i requisiti di sistema lo consentono)',
	'ColorsLegendGray' => 'Grigio: azione che potrebbe essere necessaria, ma che pu&ograve; essere eseguita automaticamente',
	'ColorsLegendBlue' => 'Blu: azione facoltativa che pu&ograve; essere eseguita automaticamente (sebbene potrebbero essere necessarie alcune modifiche manuali)',
	'ColorsLegendGreen' => 'Verde: azione suggerita che pu&ograve; essere eseguita automaticamente (se i requisiti di sistema lo consentono)',

	'FixBirthdays' => 'Correggi Compleanni',
	'FixBirthdaysExplain' => 'Questa funzione serve per correggere il formato dei compleanni.',
	'FixingBirthdaysInProgress' => 'Correzione compleanni in corso',
	'FixingBirthdaysInProgressRedirect' => 'Verrai automaticamente reindirizzato al passo successivo in tre secondi',
	'FixingBirthdaysInProgressRedirectClick' => 'Se il reindirizzamento automatico non dovesse funzionare, puoi cliccare %sQUI%s',
	'FixingBirthdaysFrom' => 'Messaggi modificati in questo ciclo da %s a %s',
	'FixingBirthdaysTotal' => '%s compleanni modificati su un totale di %s ',
	'FixingBirthdaysModified' => ' compleanni modificati',
	'FixingBirthdaysComplete' => 'Correzione compleanni completata',
	'BirthdaysPerStep' => 'Numero compleanni per ciclo',

	'FixConstantsInFiles' => 'Aggiornamento nuove costanti',
	'FixConstantsInFilesExplain' => 'Aggiornamento delle nuove costanti nei files del CMS',
	'FixingInProgress' => 'Aggiornamento files in corso',
	'FixingComplete' => 'Aggiornamento files completato',
	'ClickToFix' => 'Clicca su uno dei link sottostanti per procedere',
	'FixAllFiles' => 'Modifica tutti i files necessari (pagine CMS e config.php)',
	'FixCMSPages' => 'Modifica soltanto le pagine CMS',
	'Fixed' => 'Corretto',
	'NotFixed' => 'Non corretto',
	'FilesProcessed' => 'Files processati',

	'FixPosts' => 'Correggi Messaggi',
	'FixPostsExplain' => 'Questa funzione serve per correggere i messaggi del forum. Si pu&ograve; utilizzare per: sostituire del testo all\'interno dei messaggi, eliminare il BBCode UID, modificare l\'indirizzo delle immagini inviate.',
	'FixingPostsInProgress' => 'Correzione messaggi in corso',
	'FixingPostsInProgressRedirect' => 'Verrai automaticamente reindirizzato al passo successivo in tre secondi',
	'FixingPostsInProgressRedirectClick' => 'Se il reindirizzamento automatico non dovesse funzionare, puoi cliccare %sQUI%s',
	'FixingPostsFrom' => 'Messaggi modificati in questo ciclo da %s a %s',
	'FixingPostsTotal' => '%s messaggi modificati su un totale di %s ',
	'FixingPostsModified' => ' messaggi modificati',
	'FixingPostsComplete' => 'Correzione messaggi completata',
	'SearchWhat' => 'Cerca nel testo',
	'ReplaceWith' => 'Sostituisci con',
	'PostsPerStep' => 'Numero messaggi per ciclo',
	'StartFrom' => 'Inizia dal messaggio',
	'RemoveBBCodeUID' => 'Elimina i BBCode UID (presi dalla tabella messaggi)',
	'RemoveBBCodeUID_Guess' => 'Cerca di rimuovere tutti i codici che somigliano a BBCode UID',
	'FixPostedImagesPaths' => 'Correggi tutti i percorsi delle immagini inviate (aggiungendo la sottocartella utente)',

	'FixSignatures' => 'Correggi Firme',
	'FixSignaturesExplain' => 'Questa funzione serve per correggere i le firme degli utenti. Si pu&ograve; utilizzare per: sostituire del testo all\'interno delle firme, eliminare il BBCode UID, modificare l\'indirizzo delle immagini inviate.',
	'FixingSignaturesInProgress' => 'Correzione firme in corso',
	'FixingSignaturesFrom' => 'Firme modificate in questo ciclo da %s a %s',
	'FixingSignaturesTotal' => '%s firme modificate su un totale di %s ',
	'FixingSignaturesModified' => ' firme modificate',
	'FixingSignaturesComplete' => 'Correzione firme completata',
	'SignaturesPerStep' => 'Numero firme per ciclo',
	'StartFromSignature' => 'Inizia dalla firma',

	'FixPics' => 'Correggi Immagini Album',
	'FixPicsExplain' => 'Questa funzione serve per spostare le immagini dell\'album nelle sottocartelle degli utenti e aggiornare il database con i nuovi percorsi',
	'FixingPicsInProgress' => 'Correzione immagini album in corso',
	'FixingPicsInProgressRedirect' => 'Verrai automaticamente reindirizzato al passo successivo in tre secondi',
	'FixingPicsInProgressRedirectClick' => 'Se il reindirizzamento automatico non dovesse funzionare, puoi cliccare %sQUI%s',
	'FixingPicsFrom' => 'Immagini modificate in questo ciclo da %s a %s',
	'FixingPicsTotal' => '%s immagini modificate su un totale di %s ',
	'FixingPicsModified' => ' immagini modificate',
	'FixingPicsComplete' => 'Correzione immagini completata',
	'PicStartFrom' => 'Inizia dall\'immagine numero',
	'PicsPerStep' => 'Numero di immagini per ciclo',

	'RenMovePics' => 'Rinomina E Sposta Immagini Inviate',
	'RenMovePicsExplain' => 'Questa funzione rinomina e sposta tutte le immagini inviate dagli utenti che si trovano nella cartella principale muovendole in sottocartelle per utente: se lanci questa applicazione avrai bisogno poi di modificare tutti i messaggi tramite la funzione <i>Correggi Messaggi</i>',
	)
);

$lang['BBC_IP_CREDITS_STATIC'] = '
<a href="http://www.icyphoenix.com" title="Icy Phoenix"><img src="./style/icy_phoenix_small.png" alt="Icy Phoenix" title="Icy Phoenix" /></a><br />
<span style="color: #FF5500;"><b>Mighty Gorgon</b></span><br />
<i>(Luca Libralato)</i><br />
<b><i>Developer</i></b><br />
Interests: Heroes Of Might And Magic III, 69, #FF5522<br />
Location: Homer\'s Head<br />
<br />
<br />
<span style="color: #DD2222;"><b>hpl</b></span><br />
<i>(Alessandro Drago)</i><br />
<b><i>Developer</i></b><br />
Interests: CMS, little animals<br />
Location: Global Header<br />
<br />
<br />
<span style="color: #DD2222;"><b>Bicet</b></span><br />
<b><i>phpBB XS Developer</i></b><br />
<br />
<br />
<b><i>Valued Contributors</i></b><br />
<span style="color: #228844;"><b>Andrea75</b></span><br />
<span style="color: #DD2222;"><b>Artie</b></span><br />
<span style="color: #228844;"><b>buldo</b></span><br />
<span style="color: #228844;"><b>casimedicos</b></span><br />
<span style="color: #DD2222;"><b>CyberAlien</b></span><br />
<span style="color: #800080;"><b>darkone</b></span><br />
<span style="color: #228844;"><b>difus</b></span><br />
<span style="color: #800080;"><b>fare85</b></span><br />
<span style="color: #228844;"><b>fracs</b></span><br />
<span style="color: #800080;"><b>ganesh</b></span><br />
<span style="color: #228844;"><b>JANU1535</b></span><br />
<span style="color: #800080;"><b>jz</b></span><br />
<span style="color: #228844;"><b>KasLimon</b></span><br />
<span style="color: #AAFF00;"><b>KugeLSichA</b></span><br />
<span style="color: #228844;"><b>Lopalong</b></span><br />
<span style="color: #228844;"><b>moreteavicar</b></span><br />
<span style="color: #228844;"><b>Nikola</b></span><br />
<span style="color: #228844;"><b>novice programmer</b></span><br />
<span style="color: #228844;"><b>ThE KuKa</b></span><br />
<span style="color: #FF7700;"><b>TheSteffen</b></span><br />
<span style="color: #0000BB;"><b>Tom</b></span><br />
<span style="color: #228844;"><b>z3d0</b></span><br />
<span style="color: #228844;"><b>Zuker</b></span><br />
<br />
Interests: Icy Phoenix<br />
Location: <a href="http://www.icyphoenix.com/">http://www.icyphoenix.com</a>
';

$lang['BBC_IP_CREDITS'] = '<div class="center-block"><marquee behavior="scroll" direction="up" scrolldelay="120">' . $lang['BBC_IP_CREDITS_STATIC'] . '</marquee></div>';

?>