<?php

session_start();

require_once '../includes/auth_validate.php';
require_once 'config.php';
require_once 'game_logic.php';

$db = getDbInstance();

$id = intval($_GET['id']);
$column = intval($_GET['column']);

$query_result = $db->query("SELECT player1, player2, game_obj FROM game WHERE id=$id");

$game_str = $query_result[0]['game_obj'];

$game = unserialize($game_str);

if ($game->player == Color::WHITE && $query_result[0]['player1'] == $_SESSION['id']
        || $game->player == Color::BLACK && $query_result[0]['player2'] == $_SESSION['id']) {
    $game->addDisc($column);

    $data = Array(
        'game_obj' => serialize($game),
        'finished' => $game->finished
    );

    $db->where('id', $id);
    $db->update('game', $data);
}

?>
