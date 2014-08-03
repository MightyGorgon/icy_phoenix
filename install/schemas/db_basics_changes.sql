UPDATE phpbb_config SET config_value = 'icyphoenix' WHERE config_name = 'cookie_name';
UPDATE phpbb_config SET config_value = '/' WHERE config_name = 'cookie_path';
UPDATE phpbb_config SET config_value = 'localhost' WHERE config_name = 'server_name';
UPDATE phpbb_config SET config_value = '/ip/' WHERE config_name = 'script_path';
UPDATE phpbb_config SET config_value = '80' WHERE config_name = 'server_port';
UPDATE phpbb_config SET config_value = '1' WHERE config_name = 'default_style';
UPDATE phpbb_config SET config_value = 'english' WHERE config_name = 'default_lang';
UPDATE phpbb_config SET config_value = '1' WHERE config_name = 'board_timezone';
UPDATE phpbb_config SET config_value = '0' WHERE config_name = 'board_disable';

#UPDATE phpbb_config SET config_value = '' WHERE config_name = 'version';
#UPDATE phpbb_config SET config_value = '' WHERE config_name = 'ip_version';
#UPDATE phpbb_config SET config_value = '' WHERE config_name = '';
