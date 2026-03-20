<?php

$online = ($_SERVER['HTTP_HOST'] == "localhost" || $_SERVER['HTTP_HOST'] == "127.0.0.1" || $_SERVER['HTTP_HOST'] == "192.168.2.240") ? false : true;

defined('SITE_FOLDER')  ? '' : define('SITE_FOLDER', 'synhawk');
defined('SITE_STR')     ? '' : define('SITE_STR', '');

if ($online) { // ONLINE SETUP

    define('DB_SERVER', 'localhost');
    define('DB_USER',   'peninsul_user');
    define('DB_PASS',   'P@ssw0rd123');
    define('DB_NAME',   'peninsul_db');

} else {    // LOCAL SETUP

    define('DB_SERVER', 'localhost');
    define('DB_USER',   'root');
    define('DB_PASS',   '');
    define('DB_NAME',   'synhawk');

}

?>
