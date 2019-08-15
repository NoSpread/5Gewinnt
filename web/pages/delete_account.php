<?php
require_once '../res/php/config.php';
session_start();
$db = getDbInstance();
$db->where('username', $_SESSION['username']);
$db->delete('user');

$db = MysqliDb::getInstance();
$db->where('username', $_SESSION['username']);
$db->delete('avatar');


session_unset();

if(isset($_COOKIE['series_id']) && isset($_COOKIE['remember_token'])){
	// Löschen der vorhandenen Session-Daten
	clearAuthCookie();
}

header('Location:login.php');
exit;
?>