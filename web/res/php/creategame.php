<?php

require_once 'config.php';

$db = getDbInstance();

$data = Array(
	'player1' => '42',
	'player2' => '54',
	'last_move' => microtime(true),
	'game_obj' => 'foobar',
);
$id = $db->insert('game', $data);

echo 'Created game with id ' . $id;
?>
