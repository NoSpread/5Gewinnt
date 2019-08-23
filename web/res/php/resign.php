<?php

// Dieses Spiel lässt den Benutzer das Spiel mit der angegebenen ID aufgeben, falls dies möglich ist.
// Dabei wird auch auf ein Timeout geprüft.

session_start();

require_once '../includes/auth_validate.php';
require_once 'config.php';
require_once 'game_logic.php';

$db = getDbInstance();

// Die ID muss einen Integer-Wert haben.
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

$query= $db->query("SELECT * FROM game WHERE id=$id")[0];

$now = microtime(TRUE);

$game = unserialize($query['game_obj']);
$clock1 = $query['clock1'];
$clock2 = $query['clock2'];
$lastMove = $query['last_move'];
$player1 = $query['player1'];
$player2 = $query['player2'];
$state = $query['state'];

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
        $game->resign($game->player);
    }
    if ($player1 == $_SESSION['id']) {
        $game->resign(Color::WHITE);
    }
    if ($player2 == $_SESSION['id']) {
        $game->resign(Color::BLACK);
    }

    if ($game->finished) {
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

?>
