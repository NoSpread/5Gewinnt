<?php
include_once __DIR__ . '/../php/helpers.php';
//If User is logged in the session['user_logged_in'] will be set to true

//if user is Not Logged in, redirect to login.php page.
if (!isset($_SESSION['user_logged_in'])) {
	redirect("login.php");
}

?>
