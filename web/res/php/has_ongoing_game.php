<?php

// Dieses Skript prüft, ob der Benutzer ein laufendes Spiel hat.

session_start();
require_once '../includes/auth_validate.php';
require_once 'config.php';

$playerId = $_SESSION['id'];

$db = getDbInstance();

$ongoingGames = $db->query("SELECT id FROM game WHERE state='ongoing' AND (player1=$playerId OR player2=$playerId)");

if (count($ongoingGames) == 0) {
	echo json_encode(array(
		'ongoing' => false,
		'id' => NULL
	));
} else {
	echo json_encode(array(
		'ongoing' => true,
		'id' => $query[0]['id']
	));
}

?>
