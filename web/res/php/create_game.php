<?php

require_once 'config.php';
require_once 'game_logic.php';

$game = new Game();

$db = getDbInstance();
if ($_GET['player'] == '1') {
	$player = 'player1';
} else {
	$player = 'player2';
}

$data = Array(
	$player => '42',			//SIMON 
	'last_move' => microtime(TRUE),
	'game_obj' => serialize($game)
);
$id = $db->insert('game', $data);

?>
