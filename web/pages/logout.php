<?php
require_once '../res/php/config.php';
session_start();
session_unset();

if(isset($_COOKIE['series_id']) && isset($_COOKIE['remember_token'])){
	clearAuthCookie();
}
header('Location:login.php');
exit;

 ?> 