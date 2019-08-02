<?php

require_once 'config.php';
require_once 'game_logic.php';

$db = getDbInstance();

$id = intval($_GET['id']);
$column = intval($_GET['column']);

$query_result = $db->query("SELECT game_obj FROM game WHERE id=$id");

$game_str = $query_result[0]['game_obj'];

$game = unserialize($game_str);
$game->addDisc($column);

$data = Array(
    'game_obj' => serialize($game),
    'finished' => $game->finished
);

$db->where('id', $id);
$db->update('game', $data);

?>
