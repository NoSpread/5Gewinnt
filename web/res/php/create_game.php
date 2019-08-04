<?php

session_start();

require_once '../includes/auth_validate.php';
require_once 'config.php';
require_once 'game_logic.php';

$game = new Game();
$playerId = $_SESSION['id'];

$db = getDbInstance();

$unfinishedGames = $db->query("SELECT id FROM game WHERE state!='finished' AND (player1=$playerId OR player2=$playerId)");

if (count($unfinishedGames) == 0) {
	if ($_GET['player'] == '1') {
		$player = 'player1';
	} else {
		$player = 'player2';
	}

	$data = Array(
		$player => $_SESSION['id'],
		'game_obj' => serialize($game)
	);
	$id = $db->insert('game', $data);
}

?>
