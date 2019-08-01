<?php

require_once 'config.php';

$db = getDbInstance();

$db->query("set collation_connection='utf16_unicode_ci'"); 

$open_games = $db->query('SELECT game.id, game.player1, game.player2, user.username FROM game INNER JOIN user ON (game.player1 = user.id) OR (game.player2 = user.id) WHERE (player1 IS NULL) OR (player2 IS NULL)');

echo json_encode($open_games);

?>
