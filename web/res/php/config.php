<?php

// Hinweis für die Entwickler: Diese Datei sollte als erstes auf jeder php-Seite eingebunden werden.
error_reporting(0);
ini_set('display_errors', 'Off');
define('BASE_PATH', dirname(dirname(__FILE__)));
define('CURRENT_PAGE', basename($_SERVER['REQUEST_URI']));

require_once BASE_PATH . '/php/MysqliDb.php';
require_once BASE_PATH . '/php/helpers.php';

/*
|--------------------------------------------------------------------------
| DATENBANK-KONFIGURATION
|--------------------------------------------------------------------------
 */

define('DB_HOST', "");
define('DB_USER', "");
define('DB_PASSWORD', "");
define('DB_NAME', "");

// Allgemeine Datenbank-Konfiguration:
/* define('DB_HOST', "localhost");
define('DB_USER', "root");
define('DB_PASSWORD', "");
define('DB_NAME', ""); */

// Erhalten der Instanz des Datenbank-Objektes
function getDbInstance() {
	return new MysqliDb(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
}

$sqli = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die ("DB-system nicht verfuegbar");

?>
