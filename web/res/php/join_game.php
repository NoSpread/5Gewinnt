<?php

require_once 'config.php';

$db = getDbInstance();

$id = $_GET['id'];

$players = $db->query('SELECT player1, player2 FROM game WHERE id=' . $id);

if (is_null($players[0]['player1'])) { // TODO compare player1 and player2 with own id
	$data = Array(
		'player1' => 5242 // TODO do your Fking job Simon 
	);
	$db->where ('id', $id);
	$db->update('game', $data);
	
	echo 1; // Task successful
} else if (is_null($players[0]['player2'])) { // TODO compare player1 and player2 with own id
	$data = Array(
		'player2' => 5242 // TODO do your Fking job Simon 
	);
	$db->where ('id', $id);
	$db->update('game', $data);
	
	echo 1; // Task successful
} else {
	echo 0;
}

?>