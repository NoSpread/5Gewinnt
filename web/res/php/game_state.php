<?php

session_start();

require_once '../includes/auth_validate.php';
require_once 'config.php';
require_once 'game_logic.php';

require 'check_timeout.php';

$db = getDbInstance();

$id = filter_input(INPUT_GET, 'id');
//$id = intval($_GET['id']);

// Informationen über ein Spiel aus der Datenbank
$query = $db->query("SELECT u.username AS name1, c.username AS name2, game.player1, game.player2, game.clock1, game.clock2, game.last_move, game.game_obj FROM user AS u, user AS c, game WHERE game.id=$id AND u.id = game.player1 AND c.id = game.player2");

// Die Variablen ewrden mit den Informationen aus der Datenbank belegt
$game = unserialize($query[0]['game_obj']);
$clock1 = $query[0]['clock1'];
$clock2 = $query[0]['clock2'];
$lastMove = $query[0]['last_move'];
$now = microtime(TRUE);

if (!$game->finished) {
    // Spiel ist noch nicht beendet
    if ($game->player == Color::WHITE) {
        // Setzen des neuen Timestamps für den weissen Spieler
        $clock1 -= ($now - $lastMove);
    } else if ($game->player == Color::BLACK) {
        // Setzen des neuen Timestamps für den schwarzen Spieler
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
