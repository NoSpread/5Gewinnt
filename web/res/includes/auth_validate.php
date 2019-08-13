<?php
include_once __DIR__ . '/../php/helpers.php';
// Wenn der User in der Session eingeloggt ist, wird ['user_logged_in'] mit dem Wahrheitswert "wahr" belegt.

// Wenn der User nicht eingeloggt ist, wird er auf die Seite "login.php" weitergeleitet.
if (!isset($_SESSION['user_logged_in'])) {
	redirect("login.php");
}

?>