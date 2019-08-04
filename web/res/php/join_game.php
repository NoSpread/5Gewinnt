<?php

session_start();
require_once '../includes/auth_validate.php';
require_once 'config.php';

$db = getDbInstance();

$gameId = $_GET['id'];
$playerId = $_SESSION['id'];

$unfinishedGames = $db->query('SELECT id FROM game WHERE finished=0 AND (player1=' . $_SESSION['id'] . ' OR player2=' . $_SESSION['id'] . ')');

if (count($unfinishedGames) == 0) {
	$players = $db->query('SELECT player1, player2 FROM game WHERE id=' . $gameId);

	if (is_null($players[0]['player1']) && $players[0]['player2'] != $playerId) {
		$data = Array(
			'player1' => $playerId
		);
		$db->where('id', $gameId);
		$db->update('game', $data);

		echo 1; // Task successful
	} else if (is_null($players[0]['player2']) && $players[0]['player1'] != $playerId) {
		$data = Array(
			'player2' => $playerId
		);
		$db->where('id', $gameId);
		$db->update('game', $data);

		echo 1; // Task successful
	} else {
		echo 0;
	}
}

?>
