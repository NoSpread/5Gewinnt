<?php

session_start();
require_once '../includes/auth_validate.php';
require_once 'config.php';

$db = getDbInstance();

$gameId = $_GET['id'];
$playerId = $_SESSION['id'];

// Alle Spiele des Spielers aus der Datenbank, die nicht fertig sind.
$unfinishedGames = $db->query("SELECT id FROM game WHERE state!='finished' AND (player1=$playerId OR player2=$playerId)");

if (count($unfinishedGames) == 0) {
	// keine unfertigen Spiele
	$players = $db->query("SELECT player1, player2 FROM game WHERE id=$gameId");

	if (is_null($players[0]['player1']) && $players[0]['player2'] != $playerId) {
		// Der Spieler wird zu Spieler 1
		$data = Array(
			'player1' => $playerId,
			'last_move' => microtime(TRUE),
			'state' => 'ongoing'
		);
		$db->where('id', $gameId);
		$db->update('game', $data);

		echo 1; // erfolgreich
	} else if (is_null($players[0]['player2']) && $players[0]['player1'] != $playerId) {
		// Der Spieler wird zu Spieler 2
		$data = Array(
			'player2' => $playerId,
			'last_move' => microtime(TRUE),
			'state' => 'ongoing'
		);
		$db->where('id', $gameId);
		$db->update('game', $data);

		echo 1; // erfolgreich
	} else {
		echo 0; // nicht erfolgreich
	}
}

?>
