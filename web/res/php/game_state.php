<?php

session_start();

require_once '../includes/auth_validate.php';
require_once 'config.php';
require_once 'game_logic.php';

$db = getDbInstance();

$id = intval($_GET['id']);

$query = $db->query("SELECT player1, player2, game_obj FROM game WHERE id=$id");

$result = Array(
    'gameObj' => unserialize($query[0]['game_obj']),
    'player1' => $query[0]['player1'],
    'player2' => $query[0]['player2']
);

echo json_encode($result);

?>
