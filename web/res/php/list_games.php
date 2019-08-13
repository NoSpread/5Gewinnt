<?php

// Es wird eine Liste angezeigt, welche alle offenen und ongoing Spiele darstellt.
require_once 'config.php';

$db = getDbInstance();

$part1 = $db->query(
	'SELECT game.id, game.player1, game.player2, user.username AS name1, NULL AS name2 ' .
	'FROM game, user ' . 
	'WHERE game.player1 = user.id AND game.player2 IS NULL AND game.state = "open"'
);
$part2 = $db->query(
	'SELECT game.id, game.player1, game.player2, user.username AS name2, NULL AS name1 ' .
	'FROM game, user ' . 
	'WHERE game.player2 = user.id AND game.player1 IS NULL AND game.state = "open"'
);
$games['openTable'] = array_merge($part1, $part2);

$games['ongoingTable'] = $db->query(
	'SELECT game.id, game.player1, game.player2, u1.username AS name1, u2.username AS name2, game.state ' .
	'FROM game, user AS u1, user AS u2 ' .
	'WHERE u1.id = game.player1 AND u2.id = game.player2 AND game.state = "ongoing"'
);

echo json_encode($games);

?>