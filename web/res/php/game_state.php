<?php

// Dieses Skript liefert alle relevanten Informationen eines gegebenen Spiels zur Anzeige durch play.php
// Dabei wird auch auf ein Timeout geprüft.

session_start();

require_once '../includes/auth_validate.php';
require_once 'config.php';
require_once 'game_logic.php';

$db = getDbInstance();

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_SPECIAL_CHARS);

$query = $db->query(
    "SELECT u1.username AS name1, u2.username AS name2, game.player1, game.player2, game.clock1, game.clock2, game.last_move, game.game_obj, game.state, game.color1, game.color2 " .
    "FROM user AS u1, user AS u2, game " .
    "WHERE game.id=$id AND u1.id = game.player1 AND u2.id = game.player2"
)[0];

$now = microtime(TRUE);

$game = unserialize($query['game_obj']);
$player1 = $query['player1'];
$player2 = $query['player2'];
$clock1 = $query['clock1'];
$clock2 = $query['clock2'];
$name1 = $query['name1'];
$name2 = $query['name2'];
$lastMove = $query['last_move'];
$state = $query['state'];
$color1 = $query['color1'];
$color2 = $query['color2'];

// Berechnung der verbleibenden Bedenkzeiten mit Prüfung auf ein Timeout
if ($state == 'ongoing') {
    $timeout = FALSE;

    if ($game->player == Color::WHITE) {
        $clock1 -= ($now - $lastMove);
        if ($clock1 <= 0) {
            $clock1 = 0;
            $timeout = TRUE;
        }
    } else if ($game->player == Color::BLACK) {
        $clock2 -= ($now - $lastMove);
        if ($clock2 <= 0) {
            $clock2 = 0;
            $timeout = TRUE;
        }
    }

    if ($timeout) {
        $game->resign();
        $winner = Array(
            Color::NONE => NULL,
            Color::WHITE => $player1,
            Color::BLACK => $player2
        )[$game->winner];

        $data = Array(
            'game_obj' => serialize($game),
            'clock1' => $clock1,
            'clock2' => $clock2,
            'state' => 'finished',
            'winner' => $winner
        );

        $db->where('id', $id);
        $db->update('game', $data);
    }
}

$result = Array(
    'gameObj' => $game,
    'player1' => $player1,
    'player2' => $player2,
	'name1' => $name1,
	'name2' => $name2,
    'clock1' => $clock1,
    'clock2' => $clock2,
    'state' => $state,
    'color1' => $color1,
    'color2' => $color2
);

echo json_encode($result);

?>
