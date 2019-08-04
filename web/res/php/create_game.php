<?php

session_start();

require_once '../includes/auth_validate.php';
require_once 'config.php';
require_once 'game_logic.php';

$game = new Game();

$db = getDbInstance();

$unfinishedGames = $db->query('SELECT id FROM game WHERE state!="finished" AND (player1=' . $_SESSION['id'] . ' OR player2=' . $_SESSION['id'] . ')');

if (count($unfinishedGames) == 0) {
	if ($_GET['player'] == '1') {
		$player = 'player1';
	} else {
		$player = 'player2';
	}

	$data = Array(
		$player => $_SESSION['id'],
		'last_move' => microtime(TRUE),
		'game_obj' => serialize($game)
	);
	$id = $db->insert('game', $data);
}

?>
