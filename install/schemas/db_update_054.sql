########################################
##              BUILD 054             ##
########################################



#####################

##UPDATE phpbb_config SET config_value = '2' WHERE config_name = 'main_admin_id';

#-- DB CHANGES FOR VERSIONING
UPDATE phpbb_attachments_config SET config_value = '2.4.5' WHERE config_name = 'attach_version';
UPDATE phpbb_config SET config_value = '3.0.7' WHERE config_name = 'upi2db_version';
UPDATE phpbb_album_config SET config_value = '1.5.0' WHERE config_name = 'fap_version';
UPDATE phpbb_config SET config_value = '.0.23' WHERE config_name = 'version';
UPDATE phpbb_config SET config_value = '1.3.1.54' WHERE config_name = 'ip_version';
