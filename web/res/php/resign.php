<?php

// Der Spieler hat die Möglichkeit das Spiel aufzugeben.
session_start();

require_once '../includes/auth_validate.php';
require_once 'config.php';
require_once 'game_logic.php';

$db = getDbInstance();

// Die ID muss einen Integer-Wert haben.
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

$query= $db->query("SELECT * FROM game WHERE id=$id")[0];

$now = microtime(TRUE);

// Die Variablen werden mit den Informationen aus der Datenbank belegt.
$game = unserialize($query['game_obj']);
$clock1 = $query['clock1'];
$clock2 = $query['clock2'];
$lastMove = $query['last_move'];
$player1 = $query['player1'];
$player2 = $query['player2'];
$state = $query['state'];

if ($state == 'ongoing') {
    // Spiel läuft
    $timeout = FALSE;

    if ($game->player == Color::WHITE) {
        $clock1 -= ($now - $lastMove);
        if ($clock1 <= 0) {
            // Die Zeit ist abgelaufen
            $clock1 = 0;
            $timeout = TRUE;
        }
    } else if ($game->player == Color::BLACK) {
        $clock2 -= ($now - $lastMove);
        if ($clock2 <= 0) {
            // Die Zeit ist abgelaufen
            $clock2 = 0;
            $timeout = TRUE;
        }
    }

    if ($timeout
            || $game->player == Color::WHITE && $player1 == $_SESSION['id']
            || $game->player == Color::BLACK && $player2 == $_SESSION['id']) {
        $game->resign(); // Spiel beendet
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

        // Aktualisieren der Datenbank
        $db->where('id', $id);
        $db->update('game', $data);
    }
}

?>
