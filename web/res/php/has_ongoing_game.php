<?php

// Es wird überprüft, ob der Spieler laufende Spiele hat.
session_start();
require_once '../includes/auth_validate.php';
require_once 'config.php';

$playerId = $_SESSION['id'];

$db = getDbInstance();

$query = $db->query("SELECT id FROM game WHERE state='ongoing' AND (player1=$playerId OR player2=$playerId)");

if (count($query) == 0) {
	echo json_encode(array(
		'ongoing' => false,
		'id' => NULL
	)); // keine laufenden Spiele
} else {
	echo json_encode(array(
		'ongoing' => true,
		'id' => $query[0]['id']
	)); // laufende Spiele
}

?>
