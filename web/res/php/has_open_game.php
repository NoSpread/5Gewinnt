<?php

// Dieses Skript prüft, ob der Benutzer ein offenes Spiel (also eine aktive Herausforderunge) hat.

session_start();
require_once '../includes/auth_validate.php';
require_once 'config.php';

$playerId = $_SESSION['id'];

$db = getDbInstance();

$openGames = $db->query("SELECT id FROM game WHERE state='open' AND (player1=$playerId OR player2=$playerId)");

if (count($openGames) == 0) {
	echo 0;
} else {
	echo 1;
}

?>
