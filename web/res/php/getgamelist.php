<?php

require_once 'config.php';

$db = getDbInstance();

$gamelist = $db->query('SELECT id, player1 FROM game WHERE state="open"');

echo json_encode($gamelist);

?>
