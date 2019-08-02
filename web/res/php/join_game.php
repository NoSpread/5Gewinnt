<?php

session_start();
require_once '../includes/auth_validate.php';
require_once 'config.php';

$db = getDbInstance();

$id = $_GET['id'];
$_SESSION['gameid'] = $id;


$players = $db->query('SELECT player1, player2 FROM game WHERE id=' . $id);

$ownId = $db->query('SELECT id FROM user WHERE username = ' . $_SESSION['username']); 


if (is_null($players[0]['player1'] && $players[0]['player2'] != $ownId)) {
	$data = Array(
		'player1' => $ownId
	);
	$db->where ('id', $id);
	$db->update('game', $data);
	
	echo 1; // Task successful
} else if (is_null($players[0]['player2']) && $players[0]['player1'] != $ownId) { 
	$data = Array(
		'player2' => $ownId;
	);
	$db->where ('id', $id);
	$db->update('game', $data);
	
	echo 1; // Task successful
} else {
	echo 0;
}

?>