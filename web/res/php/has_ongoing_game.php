<?php

session_start();
require_once '../includes/auth_validate.php';
require_once 'config.php';

$db = getDbInstance();

$query = $db->query('SELECT id FROM game WHERE (player1=' . $_SESSION['id'] . ' OR player2=' . $_SESSION['id'] . ') AND finished=0 AND player1 IS NOT NULL AND player2 IS NOT NULL');

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
