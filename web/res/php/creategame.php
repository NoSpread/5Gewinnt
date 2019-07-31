<?php

require_once 'config.php';
require_once 'game_logic.php';

$game = new Game();

$db = getDbInstance();

$data = Array(
	'player1' => '42',
	'player2' => '54',
	'last_move' => microtime(true),
	'game_obj' => serialize($game)
);
$id = $db->insert('game', $data);

echo 'Created game with id ' . $id;
?>
