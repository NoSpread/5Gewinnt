<?php

// Dieses Skript prüft, ob der Benutzer unfertige Spiele besitzt.
// Ist dies nicht der Fall fügt es ihn als Zweitspieler in das gegebene Spiel hinzu.

session_start();
require_once '../includes/auth_validate.php';
require_once 'config.php';

$db = getDbInstance();

$gameId = filter_input(INPUT_GET, 'id');
$playerId = $_SESSION['id'];

$unfinishedGames = $db->query("SELECT id FROM game WHERE state!='finished' AND (player1=$playerId OR player2=$playerId)");

$now = microtime(TRUE);

if (count($unfinishedGames) == 0) {
	$players = $db->query("SELECT player1, player2 FROM game WHERE id=$gameId");

	if (is_null($players[0]['player1']) && $players[0]['player2'] != $playerId) { // Der Benutzer ist Startspieler und spielt nicht gegen sich selbst
		$data = Array(
			'player1' => $playerId,
			'last_move' => $now,
			'state' => 'ongoing'
		);
		$db->where('id', $gameId);
		$db->update('game', $data);
	} else if (is_null($players[0]['player2']) && $players[0]['player1'] != $playerId) { // Der Benutzer ist Zweitspieler und spielt nicht gegen sich selbst
		$data = Array(
			'player2' => $playerId,
			'last_move' => $now,
			'state' => 'ongoing'
		);
		$db->where('id', $gameId);
		$db->update('game', $data);
	}
}

?>
