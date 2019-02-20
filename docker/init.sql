CREATE DATABASE if not exists icy_phoenix;
use icy_phoenix;
CREATE SCHEMA if not exists icy_phoenix;
CREATE USER 'icy_phoenix' IDENTIFIED BY 'icy_phoenix';
GRANT ALL PRIVILEGES ON icy_phoenix.* TO icy_phoenix;
FLUSH PRIVILEGES;
