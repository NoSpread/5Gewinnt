<?php

// Es wird überprüft, ob der Spieler bereits Teilnehmer eines Spiels ist.
session_start();
require_once '../includes/auth_validate.php';
require_once 'config.php';

$playerId = $_SESSION['id'];

$db = getDbInstance();

$query = $db->query("SELECT id FROM game WHERE state='open' AND (player1=$playerId OR player2=$playerId)");

if (count($query) == 0) {
	echo 0; // keine offenen Spiele
} else {
	echo 1; // offene Spiele
}

?>
