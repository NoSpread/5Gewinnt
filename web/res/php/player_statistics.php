<?php

// Dieses Skript liefert eine Übersicht über die Anzahl der Siege, Niederlagen und Unentschieden, sowie den Namen des Nutzers

session_start();

require_once 'config.php';
require_once '../includes/auth_validate.php';

$id = $_SESSION['id'];

$db = getDbInstance();

$finishedGames = $db->query("SELECT * FROM game WHERE state='finished' AND (player1=$id OR player2=$id)");
$name = $db->query("SELECT username FROM user WHERE user.id=$id")[0];

$stats = Array(
	'name' => $name['username'] . "#" . "$id",
    'wins' => 0,
    'ties' => 0,
    'losses' => 0,
	'total' => count($finishedGames)
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
