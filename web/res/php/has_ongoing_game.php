<?php

session_start();
require_once '../includes/auth_validate.php';
require_once 'config.php';

$db = getDbInstance();

$query = $db->query('SELECT game.id FROM game INNER JOIN user ON game.player1=user.id OR game.player2=user.id WHERE user.id="' . $_SESSION['id'] . '" AND game.finished=0 AND game.player1 IS NOT NULL AND game.player2 IS NOT NULL');

if (count($query) == 0) {
	echo json_encode(array(
		'ongoing' => false,
		'id' => NULL
	));
} else {
	echo json_encode(array(
		'ongoing' => true,
		'id' => $query[0]['id']
	));
}

?>
