<?php

function resign($game, $clock1, $clock2, $id) {
    $db = getDbInstance();

    $game -> resign();

    $data = Array(
        'game_obj' => serialize($game),
        'clock1' => $clock1,
        'clock2' => $clock2,
        'state' => 'finished'
    );

    $db->where('id', $id);
    $db->update('game', $data);
}

require_once 'config.php';
require_once 'game_logic.php';

$db = getDbInstance();

$id = intval($_GET['id']);

$query_result = $db->query("SELECT clock1, clock2, last_move, game_obj FROM game WHERE id=$id");

$game = unserialize($query_result[0]['game_obj']);
$now = microtime(TRUE);
$clock1 = $query_result[0]['clock1'];
$clock2 = $query_result[0]['clock2'];
$lastMove = $query_result[0]['last_move'];

if (!$game->finished) {
    if ($game->player == Color::WHITE) {
        $clock1 -= ($now - $lastMove);
        if ($clock1 < 0) {
            $clock1 = 0;
            resign($game, $clock1, $clock2, $id);
        }
    } else if ($game->player == Color::BLACK) {
        $clock2 -= ($now - $lastMove);
        if ($clock2 < 0) {
            $clock2 = 0;
            resign($game, $clock1, $clock2, $id);
        }
    }
}

?>
