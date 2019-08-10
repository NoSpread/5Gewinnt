<?php

// Es wird eine Liste angezeigt, welche alle offenen Spiele (1/2 Spielern) darstellt.
require_once 'config.php';

$db = getDbInstance();

$open_games = $db->query('SELECT game.id, game.player1, game.player2, user.username FROM game INNER JOIN user ON COALESCE(game.player1, game.player2) = user.id WHERE state="open"');

echo json_encode($open_games);

?>
