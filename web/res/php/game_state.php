<?php

session_start();

require_once '../includes/auth_validate.php';
require_once 'config.php';
require_once 'game_logic.php';

require 'check_timeout.php';

$db = getDbInstance();

$id = intval($_GET['id']);

$query = $db->query("SELECT player1, player2, clock1, clock2, last_move, game_obj FROM game WHERE id=$id");

$game = unserialize($query[0]['game_obj']);
$clock1 = $query[0]['clock1'];
$clock2 = $query[0]['clock2'];
$lastMove = $query[0]['last_move'];
$now = microtime(TRUE);

if (!$game->finished) {
    if ($game->player == Color::WHITE) {
        $clock1 -= ($now - $lastMove);
    } else if ($game->player == Color::BLACK) {
        $clock2 -= ($now - $lastMove);
    }
}

$result = Array(
    'gameObj' => $game,
    'player1' => $query[0]['player1'],
    'player2' => $query[0]['player2'],
    'clock1' => $clock1,
    'clock2' => $clock2
);

echo json_encode($result);

?>
