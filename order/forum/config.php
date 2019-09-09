<?php


// phpBB 2.x auto-generated config file
// Do not change anything in this file!
if(defined('IN_ADMIN')){
require('../../includes/application_top.php');  
}elseif(defined('IN_PHPBB')){
require('../includes/application_top.php');  
}
define("SQL_LAYER","mysql");

$dbms = 'mysql';

$dbhost = DB_SERVER;
$dbname = DB_DATABASE;
$dbuser = DB_SERVER_USERNAME;
$dbpasswd = DB_SERVER_PASSWORD;

$table_prefix = 'phpbb_';

define('PHPBB_INSTALLED', true);

?>