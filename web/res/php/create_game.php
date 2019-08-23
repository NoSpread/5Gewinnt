<?php

// Dieses Skript prüft, ob der Benutzer noch unfertige Spiele hat, und erstellt für ihn eine neue Herausforderung, wenn dies nicht der Fall ist.

session_start();

require_once '../includes/auth_validate.php';
require_once 'config.php';
require_once 'game_logic.php';

$game = new Game();
$player = $_GET['player'];
$theme = $_GET['theme'];
$playerId = $_SESSION['id'];

$db = getDbInstance();

$unfinishedGames = $db->query("SELECT id FROM game WHERE state!='finished' AND (player1=$playerId OR player2=$playerId)");

if (count($unfinishedGames) == 0) {
	$data = Array(
		'game_obj' => serialize($game),
		'color1' => Array(
			'1' => '#000000',
			'2' => '#FF0000',
			'3' => '#FFFF00'
		)[$theme],
		'color2' => Array(
			'1' => '#FFFFFF',
			'2' => '#00FF00',
			'3' => '#0000FF'
		)[$theme]
	);
	
	if ($player == '1') {
		$data['player1'] = $_SESSION['id'];
	} else if ($player == '2') {
		$data['player2'] = $_SESSION['id'];
	}

	$id = $db->insert('game', $data);
}

?>
