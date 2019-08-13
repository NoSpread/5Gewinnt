<?php

// Dieses Skript liefert eine Übersicht über die Anzahl der Siege, Niederlagen und Unentschieden eines gegebenen Nutzers

session_start();

require_once 'config.php';
require_once '../includes/auth_validate.php';

$id = $_GET['id'];

$db = getDbInstance();

$finishedGames = $db->query("SELECT * FROM game WHERE state='finished' AND (player1=$id OR player2=$id)");

$stats = Array(
    'wins' => 0,
    'ties' => 0,
    'losses' => 0
);

// Wir iterieren über die fertigen Spiele des Nutzers und aktualisieren unsere Statistik entsprechend.
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
