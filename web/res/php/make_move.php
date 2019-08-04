<?php

session_start();

require_once '../includes/auth_validate.php';
require_once 'config.php';
require_once 'game_logic.php';

require 'check_timeout.php';

$db = getDbInstance();

$id = intval($_GET['id']);
$column = intval($_GET['column']);

$query_result = $db->query("SELECT player1, player2, clock1, clock2, last_move, game_obj FROM game WHERE id=$id");

$game = unserialize($query_result[0]['game_obj']);
$now = microtime(TRUE);
$clock1 = $query_result[0]['clock1'];
$clock2 = $query_result[0]['clock2'];
$lastMove = $query_result[0]['last_move'];
$player1 = $query_result[0]['player1'];
$player2 = $query_result[0]['player2'];

if ($game->player == Color::WHITE && $player1 == $_SESSION['id']
        || $game->player == Color::BLACK && $player2 == $_SESSION['id']) {

    if ($game->player == Color::WHITE) {
        $clock1 -= ($now - $lastMove);
    } else if ($game->player == Color::BLACK) {
        $clock2 -= ($now - $lastMove);
    }

    $game->addDisc($column);

    $data = Array(
        'game_obj' => serialize($game),
		'last_move' => $now,
        'clock1' => $clock1,
        'clock2' => $clock2,
        'state' => Array(
            TRUE => 'finished',
            FALSE =>'ongoing'
        )[$game->finished]
    );

    $db->where('id', $id);
    $db->update('game', $data);
}

?>
