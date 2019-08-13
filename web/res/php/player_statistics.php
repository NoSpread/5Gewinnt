<?php
session_start();

require_once 'config.php';
require_once '../includes/auth_validate.php';

$id = $_GET['id'];;

$db = getDbInstance();

// Alle beendeten Spieler aus der Datenbank, in welchenn der Spieler mitgespielt hat.
$finishedGames = $db->query("SELECT * FROM game WHERE state='finished' AND (player1=$id OR player2=$id)");

$stats = Array(
    'wins' => 0,
    'ties' => 0,
    'losses' => 0
);

// Zählen, wie oft der Spieler gewonnen, verloren und unentschieden gespielt hat.
foreach ($finishedGames as $game) {
    if ($game['winner'] == $id) {
        $stats['wins']++;
    } else if ($game['winner'] == NULL) {
        $stats['ties']++;
    } else {
        $stats['losses']++;
    }
};

echo json_encode($stats);

?>
