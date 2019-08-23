<?php
require_once '../res/php/config.php';
// Die Session wird geladen und anschließend alle Variablen gelöscht.
session_start();
session_destroy();

if(isset($_COOKIE['series_id']) && isset($_COOKIE['remember_token'])){
	// Löschen der vorhandenen Session-Daten
	clearAuthCookie();
}
header('Location:login.php');
exit;

 ?> 