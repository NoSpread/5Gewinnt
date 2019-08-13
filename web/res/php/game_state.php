<?php

session_start();

require_once '../includes/auth_validate.php';
require_once 'config.php';
require_once 'game_logic.php';

$db = getDbInstance();

$id = filter_input(INPUT_GET, 'id');

// Informationen über ein Spiel aus der Datenbank
$query = $db->query(
    "SELECT u1.username AS name1, u2.username AS name2, game.player1, game.player2, game.clock1, game.clock2, game.last_move, game.game_obj, game.state " .
    "FROM user AS u1, user AS u2, game " .
    "WHERE game.id=$id AND u1.id = game.player1 AND u2.id = game.player2"
)[0];

$now = microtime(TRUE);

// Die Variablen werden mit den Informationen aus der Datenbank belegt
$game = unserialize($query['game_obj']);
$player1 = $query['player1'];
$player2 = $query['player2'];
$clock1 = $query['clock1'];
$clock2 = $query['clock2'];
$name1 = $query['name1'];
$name2 = $query['name2'];
$lastMove = $query['last_move'];
$state = $query['state'];

if ($state == 'ongoing') {
    // Spiel noch nicht beendet
    $timeout = FALSE;

    // Die Spieler haben nur begrenzt Zeit einen Zug zu machen, diese Zeit läuft ab.
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
        // Zeit abgelaufen -> der Spieler, bei welchem die Zeit abgelaufen ist, hat verloren
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
    // neue Informationen über das Spiel
    'gameObj' => $game,
    'player1' => $player1,
    'player2' => $player2,
	'name1' => $name1,
	'name2' => $name2,
    'clock1' => $clock1,
    'clock2' => $clock2
);

echo json_encode($result);

?>
