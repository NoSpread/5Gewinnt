<?php

require_once 'config.php';

$db = getDbInstance();

$open_games = $db->query('SELECT id, player1 FROM game WHERE state="open"');

echo json_encode($open_games);

?>
