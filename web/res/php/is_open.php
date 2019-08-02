<?php

session_start();
require_once '../includes/auth_validate.php';
require_once 'config.php';

$db = getDbInstance();

$id = $_GET['id'];

$players = $db->query('SELECT game.player1, game.player2, game.id FROM game, user WHERE user.username = ' . $_Session['username'] . ' AND (user.id = game.player1 OR user.id = game.player2) AND game.state = 1');

if (is_null($players[0]['player1']) || is_null($players[0]['player2'])) {
	echo 0;
} else {
	echo $players[0]['id'];
}

?>

