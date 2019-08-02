<?php

require_once 'config.php';

$db = getDbInstance();

$id = $_GET['id'];

$players = $db->query('SELECT player1, player2, id FROM game WHERE player1=' . $id . ' OR player2=' . $id);

if (is_null($players[0]['player1']) || is_null($players[0]['player2'])) {
	echo 0;
} else {
	echo $players[0]['id'];
}

?>

